<?php
namespace App\Models;

use App\Database\DBConnection;

class ReservationModel
{
    protected $db;

    public function __construct()
    {
        $this->db = DBConnection::getConnection();
    }

    /**
     * Obtiene todas las reservas de un usuario.
     *
     * @param int $userId
     * @return array
     */
    public function getReservationsByUser(int $userId): array
    {
        $sql = "SELECT 
                r.*,
                v.spanish_name AS vehicle,
                p.parking_name AS parking,
                ps.spot_number AS spot
            FROM reservations r
            INNER JOIN vehicles v ON r.vehicle_id = v.id
            INNER JOIN parking_spots ps ON r.parking_spot_id = ps.id
            INNER JOIN parkings p ON ps.parking_id = p.id
            WHERE r.user_id = ?
            ORDER BY r.start_date DESC, p.parking_name ASC, ps.spot_number ASC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new \Exception("Error preparando consulta: " . $this->db->error);
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservations = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $reservations;
    }

    /**
     * Comprueba la disponibilidad mensual para un vehículo de un tipo determinado
     * en un parking dado para un año. Retorna un array asociativo: [1 => true, 2 => false, ...]
     * para los meses 1 a 12.
     *
     * @param string $type         Tipo de vehículo
     * @param int    $parkingId    Identificador del parking
     * @param int    $year         Año
     * @return array
     * @throws \Exception
     */
    public function checkMonthlyAvailability(string $type, int $parkingId, int $year): array
    {
        $availability = [];
        // Recorremos los meses del 1 al 12
        for ($month = 1; $month <= 12; $month++) {
            // Calculamos el primer y último día del mes
            $startDate = date("Y-m-d", strtotime("$year-$month-01"));
            $endDate = date("Y-m-t", strtotime($startDate));

            // Consulta: contamos cuántos vehículos del tipo y parking están disponibles
            // Es decir, aquellos que NO tienen reservas que se solapen con el rango solicitado.
            $sql = "
                SELECT COUNT(v.id) AS available_count
                FROM vehicles v
                LEFT JOIN reservations r 
                  ON v.id = r.vehicle_id 
                  AND r.start_date <= ? 
                  AND r.end_date >= ?
                WHERE v.type = ?
                  AND v.parking_id = ?
                  AND r.id IS NULL
            ";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new \Exception("Error preparando consulta de disponibilidad: " . $this->db->error);
            }
            // El orden de los parámetros es importante: primero se comprueba que la reserva no se solape.
            $stmt->bind_param("sssi", $endDate, $startDate, $type, $parkingId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $availableCount = (int) $row['available_count'];
            $availability[$month] = ($availableCount > 0);
            $stmt->close();
        }
        return $availability;
    }

    /**
     * Crea una nueva reserva de manera segura, revalidando la disponibilidad dentro de una transacción.
     *
     * Se utiliza una transacción con SELECT ... FOR UPDATE para bloquear la fila del vehículo y así evitar condiciones de carrera.
     *
     * @param int    $userId      ID del usuario
     * @param string $type        Tipo de vehículo
     * @param int    $parkingId   ID del parking seleccionado (para buscar plaza y vehículo)
     * @param string $startDate   Fecha de inicio (YYYY-MM-DD)
     * @param string $endDate     Fecha de fin (YYYY-MM-DD)
     * @return bool
     * @throws \Exception
     */
    public function createReservation(int $userId, string $type, int $parkingId, string $startDate, string $endDate): bool
    {
        // Iniciamos la transacción
        $this->db->begin_transaction();
        try {
            // 1. Obtener el ID de una plaza disponible en el parking para el rango de fechas dado
            $spotId = $this->getAvailableSpotId($parkingId, $startDate, $endDate);
            if (!$spotId) {
                throw new \Exception("No hay plazas disponibles en este parking para las fechas seleccionadas.");
            }

            // 2. Revalidar la disponibilidad del vehículo: usamos SELECT ... FOR UPDATE para bloquear la fila y evitar condiciones de carrera.
            $sql = "SELECT id FROM vehicles 
                    WHERE type = ? 
                      AND parking_id = ? 
                      AND id NOT IN (
                          SELECT vehicle_id FROM reservations 
                          WHERE (start_date <= ? AND end_date >= ?)
                      )
                    LIMIT 1 FOR UPDATE";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new \Exception("Error preparando la consulta de disponibilidad del vehículo: " . $this->db->error);
            }
            $stmt->bind_param("siss", $type, $parkingId, $endDate, $startDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if (!$row) {
                // No se encontró un vehículo disponible; revertimos la transacción y lanzamos excepción.
                $this->db->rollback();
                throw new \Exception("No hay vehículos disponibles para el parking seleccionado.");
            }
            $vehicleId = (int) $row['id'];

            // 3. Insertar la reserva usando los IDs obtenidos
            $sqlInsert = "INSERT INTO reservations (user_id, vehicle_id, parking_spot_id, start_date, end_date)
                          VALUES (?, ?, ?, ?, ?)";
            $stmtInsert = $this->db->prepare($sqlInsert);
            if (!$stmtInsert) {
                throw new \Exception("Error preparando la consulta de reserva: " . $this->db->error);
            }
            $stmtInsert->bind_param("iiiss", $userId, $vehicleId, $spotId, $startDate, $endDate);
            if (!$stmtInsert->execute()) {
                throw new \Exception("Error al ejecutar la reserva: " . $stmtInsert->error);
            }
            $stmtInsert->close();

            // 4. Confirmamos la transacción
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            // En caso de error, se revierte la transacción y se propaga la excepción
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * Obtiene el ID de una plaza disponible en un parking para un rango de fechas dado.
     *
     * @param int    $parkingId
     * @param string $startDate (YYYY-MM-DD)
     * @param string $endDate   (YYYY-MM-DD)
     * @return int|null
     * @throws \Exception
     */
    public function getAvailableSpotId(int $parkingId, string $startDate, string $endDate): ?int
    {
        $sql = "SELECT ps.id
                FROM parking_spots ps
                WHERE ps.parking_id = ?
                  AND ps.id NOT IN (
                      SELECT r.parking_spot_id
                      FROM reservations r
                      WHERE (r.start_date <= ? AND r.end_date >= ?)
                  )
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new \Exception("Error preparando consulta de plaza disponible: " . $this->db->error);
        }
        $stmt->bind_param("iss", $parkingId, $endDate, $startDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $spot = $result->fetch_assoc();
        $stmt->close();
        return $spot ? (int) $spot['id'] : null;
    }
}