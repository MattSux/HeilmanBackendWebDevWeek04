<?php

// Connect to the MySQL database using PDO
$dsn = "mysql:host=localhost;dbname=todolist";
$username = "root";
$password = "7077Mgh257!!!!!";


try {
    $conn = new PDO($dsn, $username, $password);
}
catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
