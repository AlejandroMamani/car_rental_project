<?php

require_once 'DBsetup.php';
require_once 'sampleData.php';

$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

?>


<!-- may add butons to clear the sample tables and fill them with the sample data -->