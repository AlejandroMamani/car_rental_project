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
echo "\nConnected successfully";

// Views creation

// Check if the view already exists
$sql = "SELECT * FROM information_schema.views WHERE table_name = 'customer_history'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "The view 'customer_history' already exists.";
} else {
  $db_select = mysqli_select_db($conn, "Car_rental_DB");
  // Create customer_history view
  $sql = "CREATE VIEW customer_history AS
  SELECT B.account_ID, CH.time_stamp, CH.total_payment, CS.info_ID, VD.full_car_name, VD.car_type
  FROM Book AS B, Car_History as CH, car_storage as CS, vehicle_details as VD
  WHERE B.car_ID = CH.car_ID and CS.car_ID = CH.car_ID and VD.info_ID = CS.info_ID
  Order by CH.time_stamp desc;";

  if ($conn->query($sql) === TRUE) {
    echo "The view 'customer_history' was created successfully.";
  } else {
    echo "Error creating the view: " . $conn->error;
  }
}


// End of views creation

$conn->close();
?>


<!-- may add butons to clear the sample tables and fill them with the sample data -->