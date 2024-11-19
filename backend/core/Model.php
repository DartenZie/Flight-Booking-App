<?php

/**
 * This is a base class for database models that provides a connection to the database using PDO.
 * It is intended to be extended by model classes that interact with the database.
 */
abstract class Model {
    /**
     * @var PDO|null The PDO database connection instance.
     */
    protected ?PDO $db;

    public function __construct() {
        $this->db = connectDb();
    }
}
