<?php

namespace App\models;

use App\core\Model;
use PDO;

class Airport extends Model {
    public function __construct(?PDO $db) {
        parent::__construct($db);
    }

    public function getAllAirports($limit, $offset): bool|array {
        $stmt = $this->db->prepare("SELECT * FROM airports LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAirportCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM airports");
        return $stmt->fetch()['count'];
    }

    public function getAirportById($id) {
        $stmt = $this->db->prepare("SELECT * FROM airports WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function searchAirports($query, $limit, $offset): bool|array {
        $stmt = $this->db->prepare("
            SELECT * FROM airports
            WHERE name LIKE :query
                OR city LIKE :query
                OR country LIKE :query
                OR iata LIKE :query
            LIMIT :limit OFFSET :offset
        ");

        $searchQuery = '%' . $query . '%';
        $stmt->bindParam(':query', $searchQuery);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchAirportCount($query) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM airports
            WHERE name LIKE :query
                OR city LIKE :query
                OR country LIKE :query
                OR iata LIKE :query
        ");

        $searchQuery = '%' . $query . '%';
        $stmt->bindParam(':query', $searchQuery);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
