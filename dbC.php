  <?php

    DEFINE ('DB_USER', 'nume user baza de date');
    DEFINE ('DB_PASSWORD', 'parola user');
    DEFINE ('DB_HOST', 'localhost');
    DEFINE ('DB_NAME', 'proiectMDS');

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        error_log("Error: " . $conn->connect_error . PHP_EOL, 3, "errorLog.txt");
        die('Connect Error: ' . $conn->connect_error);
    }
?>
