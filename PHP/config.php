<?php
$dbHost = 'localhost';
$dbUsername = 'ron';
$dbPassword = '12345678'; 
$dbName = 'messi_forum';

// Create database connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
