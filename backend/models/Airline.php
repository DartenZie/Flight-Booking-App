<?php

/**
 * The class extends the base `Model` class to provide database operations related to users.
 */
class Airline extends Model {
    public function getAirlineById($id) {
        $stmt = $this->db->prepare("SELECT * FROM airlines WHERE id = :id");
    }
}
