<?php
$servername = "localhost:3306";
$username = "username";
$password = "dingus";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SET PASSWORD = 'INPUT';";

$result = $conn->query($sql);

$conn-> close();
?>
