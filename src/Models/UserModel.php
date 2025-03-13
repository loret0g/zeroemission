<?php
namespace App\Models;

use App\Database\DBConnection;

class UserModel
{
    /**
     * Objeto de conexión a la base de datos.
     * @var \mysqli
     */
    protected $db;

    /**
     * Constructor: obtiene la conexión a la base de datos mediante el patrón Singleton.
     */
    public function __construct()
    {
        $this->db = DBConnection::getConnection();
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $passwordHash
     * @return void
     * @throws \Exception
     */
    public function createUser(string $name, string $surname, string $email, string $passwordHash): void
    {
        $sql = "INSERT INTO users (name, surname, email, password) VALUES (?, ?, ?, ?)";
        if (!$stmt = $this->db->prepare($sql)) {
            throw new \Exception("Error en la preparación de la consulta: " . $this->db->error);
        }
        $stmt->bind_param("ssss", $name, $surname, $email, $passwordHash);
        if (!$stmt->execute()) {
            throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        $stmt->close();
    }

    /**
     * Verifica si un email ya existe en la base de datos.
     *
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        $sql = "SELECT id FROM users WHERE email = ?";
        if (!$stmt = $this->db->prepare($sql)) {
            return false;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = ($result->fetch_assoc() !== null);
        $stmt->close();
        return $exists;
    }

    /**
     * Obtiene un usuario por su email.
     *
     * @param string $email
     * @return array|null
     */
    public function getByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        if (!$stmt = $this->db->prepare($sql)) {
            return null;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }

    /**
     * Obtiene un usuario por su ID.
     *
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        if (!$stmt = $this->db->prepare($sql)) {
            return null;
        }
        $stmt->bind_param("i", $id); // 'i' para integer
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }

    /**
     * Actualiza los datos de un usuario.
     *
     * @param int $id
     * @param string $name
     * @param string $surname
     * @param string $email
     * @return bool
     * @throws \Exception
     */
    public function updateUser(int $id, string $name, string $surname, string $email): bool
    {
        $sql = "UPDATE users SET name = ?, surname = ?, email = ? WHERE id = ?";
        if (!$stmt = $this->db->prepare($sql)) {
            throw new \Exception("Error en la preparación: " . $this->db->error);
        }
        $stmt->bind_param("sssi", $name, $surname, $email, $id);
        if (!$stmt->execute()) {
            throw new \Exception("Error al actualizar: " . $stmt->error);
        }
        $stmt->close();
        return true;
    }
}