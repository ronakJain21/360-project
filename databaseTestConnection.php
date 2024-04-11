<?php

// Database connection parameters
$servername = "localhost";
$username = "98890320";
$password = "98890320";
$database = "db_98890320";

// Create database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";

// Close the database connection
$conn->close();
?>