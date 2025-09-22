<?php
// Database credentials
$host = 'localhost';
$db   = 'pikaai';
$user = 'root';   // change this if needed
$pass = '';       // change this if needed
$charset = 'utf8mb4';

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch as associative array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // use real prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Stop execution and show error if DB connection fails
    exit('Database connection failed: ' . $e->getMessage());
}

// $pdo is now ready to be used in models
