<?php
$host = getenv('DB_HOST') ?: 'db';
$dbname = getenv('DB_NAME') ?: 'flight_booking_system';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: 'root';

$maxRetries = 10; // Maximum number of retries
$retryDelay = 5;  // Delay between retries in seconds

$pdo = null;

for ($i = 0; $i < $maxRetries; $i++) {
    try {
        echo "Attempting to connect to the database (try " . ($i + 1) . ")...\n";
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected to the database successfully!\n";
        break;
    } catch (PDOException $e) {
        echo "Database connection failed: " . $e->getMessage() . "\n";
        if ($i < $maxRetries - 1) {
            sleep($retryDelay);
        } else {
            echo "Exceeded maximum retries. Exiting.\n";
            exit(1);
        }
    }
}

if ($pdo) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Load init sql script
        echo "Initializing tables.." . PHP_EOL;
        $sqlFile = __DIR__ . "/init.sql";
        $sql = file_get_contents($sqlFile);
        $pdo->exec($sql);

        echo "Feeding airports.." . PHP_EOL;
        $sqlFile = __DIR__ . "/feed_airports.sql";
        $sql = file_get_contents($sqlFile);
        $pdo->exec($sql);

        echo "Feeding roles.." . PHP_EOL;
        $sqlFile = __DIR__ . "/feed_roles.sql";
        $sql = file_get_contents($sqlFile);
        $pdo->exec($sql);

        echo "Feeding users.." . PHP_EOL;
        $fistName = 'Super';
        $lastName = 'Admin';
        $email = 'admin@gmail.com';
        $password = password_hash('flightadmin', PASSWORD_BCRYPT);
        $roleId = 4;

        $sql = "INSERT INTO users (first_name, last_name, password, email, role_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$fistName, $lastName, $password, $email, $roleId]);


        echo "Database feed was successful" . PHP_EOL;
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage() . PHP_EOL;
    }
}
