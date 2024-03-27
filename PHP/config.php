<?php
$dbHost = 'localhost';
$dbUsername = '98890320';
$dbPassword = '98890320'; 
$dbName = 'db_98890320';

// Create database connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
