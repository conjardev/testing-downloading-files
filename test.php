<?php
$servername = "localhost:3306";
$username = "root";
$password = "dingus";

// Create connection
$conn = new mysqli($servername, $username);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE myDB";
if ($conn->query($sql) === TRUE) {
  echo "Database created successfully";
} else {
  echo "Error creating database: " . $conn->error;
}
?>
