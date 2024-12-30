CREATE DATABASE IF NOT EXISTS flight_booking_system;

USE flight_booking_system;

DROP TABLE IF EXISTS airports;
CREATE TABLE airports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    city VARCHAR(255),
    country VARCHAR(255),
    iata CHAR(3),
    timezone VARCHAR(255)
);

DROP TABLE IF EXISTS roles;
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    permission_level INT
);

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    nationality VARCHAR(255),
    date_of_birth VARCHAR(255),
    phone VARCHAR(255),
    sex VARCHAR(255),
    role_id INT,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

DROP TABLE IF EXISTS refresh_token;
CREATE TABLE refresh_token (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hash VARCHAR(255),
    expiry INT
);

DROP TABLE IF EXISTS airlines;
CREATE TABLE airlines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    logo_path VARCHAR(255)
);

DROP TABLE IF EXISTS planes;
CREATE TABLE planes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    configuration VARCHAR(255),
    airline_id INT,
    FOREIGN KEY (airline_id) REFERENCES airlines(id)
);

DROP TABLE IF EXISTS flights;
CREATE TABLE flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    price VARCHAR(255),
    cancelled BOOL,
    departure_time DATETIME,
    arrival_time DATETIME,
    plane_id INT,
    departure_airport_id INT,
    arrival_airport_id INT,
    FOREIGN KEY (plane_id) REFERENCES planes(id),
    FOREIGN KEY (departure_airport_id) REFERENCES airports(id),
    FOREIGN KEY (arrival_airport_id) REFERENCES airports(id)
);
