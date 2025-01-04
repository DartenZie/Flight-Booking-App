<?php
$host = getenv('DB_HOST') ?: 'db';
$dbname = getenv('DB_NAME') ?: 'flight_booking_system';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: 'root';

$users = [
    [ 'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'johndoe@example.com', 'role_id' => 3],
    [ 'first_name' => 'Cheryl', 'last_name' => 'Sharp', 'email' => 'cherylsharp@stafford.com', 'role_id' => 1],
    [ 'first_name' => 'Lindsey', 'last_name' => 'Jackson', 'email' => 'lindseyjackson@moore-cooley.com', 'role_id' => 1 ],
    [ 'first_name' => 'Diana', 'last_name' => 'Bradley', 'email' => 'dianabradley@perry-reeves.com', 'role_id' => 2 ],
    [ 'first_name' => 'Allison', 'last_name' => 'Taylor', 'email' => 'allisontaylor@gmail.com', 'role_id' => 1 ],
    [ 'first_name' => 'Darin', 'last_name' => 'Baker', 'email' => 'darinbaker@frazier-davis.com', 'role_id' => 2 ],
    [ 'first_name' => 'Brent', 'last_name' => 'Marshall', 'email' => 'brentmarshall@barr.com', 'role_id' => 1 ],
    [ 'first_name' => 'William', 'last_name' => 'Thomas', 'email' => 'williamthomas@hotmail.com', 'role_id' => 1 ],
    [ 'first_name' => 'Randy', 'last_name' => 'Jackson', 'email' => 'randyjackson@lane.com', 'role_id' => 1 ],
    [ 'first_name' => 'Amy', 'last_name' => 'Brown', 'email' => 'amybrown@terry-ramos.com', 'role_id' => 3 ],
    [ 'first_name' => 'Lindsey', 'last_name' => 'Parker', 'email' => 'lindseyparker@yahoo.com', 'role_id' => 1 ],
    [ 'first_name' => 'Randy', 'last_name' => 'Hall', 'email' => 'randyhall@yahoo.com', 'role_id' => 1 ],
    [ 'first_name' => 'Justin', 'last_name' => 'Bishop', 'email' => 'justinbishop@hotmail.com', 'role_id' => 2 ],
    [ 'first_name' => 'Jimmy', 'last_name' => 'Lewis', 'email' => 'jimmylewis@yahoo.com', 'role_id' => 1 ],
    [ 'first_name' => 'Patrick', 'last_name' => 'Brooks', 'email' => 'patrickbrooks@duncan.com', 'role_id' => 1 ],
    [ 'first_name' => 'Anthony', 'last_name' => 'Olson', 'email' => 'anthonyolson@hotmail.com', 'role_id' => 1 ],
    [ 'first_name' => 'Tina', 'last_name' => 'Robertson', 'email' => 'tinarobertson@richardson-compton.com', 'role_id' => 1 ],
    [ 'first_name' => 'Kenneth', 'last_name' => 'Stevenson', 'email' => 'kennethstevenson@gmail.com', 'role_id' => 1 ],
    [ 'first_name' => 'Joseph', 'last_name' => 'Bush', 'email' => 'josephbush@lynch.com', 'role_id' => 2 ],
    [ 'first_name' => 'Mark', 'last_name' => 'Lee', 'email' => 'marklee@dalton-mercado.com', 'role_id' => 2 ],
    [ 'first_name' => 'Jessica', 'last_name' => 'Nguyen', 'email' => 'jessicanguyen@roberts-bell.com', 'role_id' => 1 ],
    [ 'first_name' => 'Kimberly', 'last_name' => 'Clark', 'email' => 'kimberlyclark@gmail.com', 'role_id' => 3 ],
    [ 'first_name' => 'Amanda', 'last_name' => 'Schmitt', 'email' => 'amandaschmitt@gmail.com', 'role_id' => 1 ],
    [ 'first_name' => 'Amber', 'last_name' => 'Turner', 'email' => 'amberturner@williams.com', 'role_id' => 1 ],
    [ 'first_name' => 'Mark', 'last_name' => 'Giles', 'email' => 'markgiles@wilson-lee.biz', 'role_id' => 1 ],
    [ 'first_name' => 'Diane', 'last_name' => 'Morales', 'email' => 'dianemorales@best-huyng.com', 'role_id' => 2 ],
    [ 'first_name' => 'Tracy', 'last_name' => 'Holland', 'email' => 'tracyholland@gutierrez.com', 'role_id' => 1 ],
];

$maxRetries = 3;
$retryDelay = 5;

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
        $sqlFile = __DIR__ . "/feed_users.sql";
        $sql = file_get_contents($sqlFile);
        $pdo->exec($sql);

        echo "Database feed was successful" . PHP_EOL;
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage() . PHP_EOL;
    }
}
