<?php


/// Configuration File
///
/// This file defines configuration constants and database connection function for the application.
/// It uses environment variables (if available) for flexibility, otherwise falls back to default values.

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'flight_booking_system');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: 'root');

define('SECRET_KEY', getenv('SECRET_KEY') ?: 'super-secret-key');
define('ACCESS_TOKEN_EXPIRY', getenv('ACCESS_TOKEN_EXPIRY') ?: 900);
define('REFRESH_TOKEN_EXPIRY', getenv('REFRESH_TOKEN_EXPIRY') ?: 432000);

/**
 * Establishes a connection to the database using PDO.
 *
 * If the connection fails, it terminates the script with an error message.
 *
 * @return PDO|void The PDO connection instance.
 */
function connectDb(): ?PDO {
    try {
        return new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        // Output an error message and terminate the script if the connection fails.
        die("Database connection failed: " . $e->getMessage());
    }
}
