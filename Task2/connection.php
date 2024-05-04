<?php
// Database connection parameters
$host = 'localhost'; // or '127.0.0.1' for local server
$db_name = 'dataBase';
$username = 'root';
$password = '';

// Attempt to connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
