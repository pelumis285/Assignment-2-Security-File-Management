<?php
// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = '172.31.22.43';
$dbname = 'Samuel200595786';
$username = 'Samuel200595786';
$password = 'vb9dRKhq-o';

try {
    // Create connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Connection Successful!</h2>";
    
    // Test query
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Tables in your database:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    die("<h2>Connection failed:</h2> <p>" . $e->getMessage() . "</p>");
}
?>