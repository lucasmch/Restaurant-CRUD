<?php
$servername = "IP";
$username = "LOGIN";
$password = "SENHA";
$dbname = "DATABASE";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
date_default_timezone_set("america/sao_paulo");

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
?>