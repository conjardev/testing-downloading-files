<?php
$servername = "localhost:3306";
$username = "username";
$password = "password";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SET PASSWORD = 'INPUT'; RENAME USER ".username."@localhost TO INPUT@localhost;"

$result = $conn->query($sql);

$conn-> close();
?>
