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
