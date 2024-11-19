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

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(255),
    lastName VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255)
)
