<?php

namespace App\models;

use App\core\Model;
use PDO;

/**
 * The class extends the base `Model` class to provide database operations related to roles.
 */
class Role extends Model {
    public function __construct(?PDO $db) {
        parent::__construct($db);
    }

    /**
     * Retrieves a role's details by its name.
     *
     * @param string $name Role name.
     * @return array | false
     */
    public function getRoleByName(string $name): array | false {
        $stmt = $this->db->prepare('SELECT * from roles WHERE name = :name');
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
