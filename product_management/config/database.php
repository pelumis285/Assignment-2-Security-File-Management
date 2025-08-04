<?php
$host = '172.31.22.43';
$dbname = 'Samuel200595786';
$username = 'Samuel200595786';
$password = 'vb9dRKhq-o';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>