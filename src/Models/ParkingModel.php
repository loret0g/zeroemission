<?php
namespace App\Models;

use App\Database\DBConnection;

class ParkingModel
{
    protected $db;

    public function __construct()
    {
        $this->db = DBConnection::getConnection();
    }

    /**
     * Obtiene todos los parkings.
     *
     * @return array
     */
    public function getAllParkings(): array
    {
        $sql = "SELECT id, parking_name AS name FROM parkings ORDER BY parking_name";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}