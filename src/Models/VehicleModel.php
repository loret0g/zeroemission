<?php
namespace App\Models;

use App\Database\DBConnection;

class VehicleModel
{
    protected $db;

    public function __construct()
    {
        $this->db = DBConnection::getConnection();
    }

    /**
     * Obtiene los datos del vehículo según su tipo (sin consulta a BD en este ejemplo)
     * y añade la descripción, el nombre en español y la ruta de la imagen.
     *
     * @param string $type Tipo de vehículo en inglés (p.ej. 'bike', 'scooter', 'motorbike', 'car')
     * @return array|null Devuelve un array con datos o null si no se encuentra.
     */
    public function getVehicleByType(string $type): ?array
    {
        // Convertimos a minúsculas para consistencia.
        $type = strtolower($type);

        $descriptions = [
            'bike' => 'Explora el centro de una forma ecológica y saludable con nuestras bicicletas. Perfectas para desplazamientos cortos o para disfrutar de un paseo por la ciudad.',
            'scooter' => 'Movilidad rápida y ágil con nuestros patinetes eléctricos, ideales para moverse por zonas urbanas con facilidad.',
            'motorbike' => 'Combina velocidad y sostenibilidad con nuestras motocicletas eléctricas, perfectas para desplazamientos más largos o para evitar el tráfico pesado.',
            'car' => 'Máximo confort y eficiencia con nuestros coches eléctricos, para viajes más cómodos dentro y fuera de la ciudad.'
        ];

        // Nombres en español para mostrar al usuario
        $names = [
            'bike' => 'Bicicleta',
            'scooter' => 'Patinete eléctrico',
            'motorbike' => 'Moto eléctrica',
            'car' => 'Coche eléctrico'
        ];

        // Rutas de imágenes
        $images = [
            'bike' => '/assets/media/img/bicicleta.webp',
            'scooter' => '/assets/media/img/patinete.webp',
            'motorbike' => '/assets/media/img/moto.webp',
            'car' => '/assets/media/img/coche.webp'
        ];

        if (!isset($descriptions[$type])) {
            return null;
        }

        // Se retornan los datos que se usarán en la vista
        return [
            'type' => $type,
            'spanish_name' => $names[$type] ?? '',
            'description' => $descriptions[$type],
            'image' => $images[$type] ?? ''
        ];
    }

    public function getAvailableVehiclesByTypeAndParking(string $type, int $parkingId, string $startDate, string $endDate): array
    {
        $sql = "SELECT v.id, v.type, v.parking_id
            FROM vehicles v
            WHERE v.type = ? 
              AND v.parking_id = ?
              AND v.id NOT IN (
                  SELECT r.vehicle_id 
                  FROM reservations r 
                  WHERE (r.start_date <= ? AND r.end_date >= ?)
              )";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return [];
        }
        // Se pasan $endDate y $startDate en ese orden para la condición
        $stmt->bind_param("siss", $type, $parkingId, $endDate, $startDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $vehicles = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $vehicles;
    }
}