<?php
try {
  require_once 'DBsetup.php';
  require_once 'sampleData.php';
} catch (mysqli_sql_exception $e) {
  echo "The sample data was inserted alredy";
}


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

// Check if the customer_history view already exists
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

// Additional views

// View: Frequent_Customers
$sql = "SELECT * FROM information_schema.views WHERE table_name = 'Frequent_Customers'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "The view 'Frequent_Customers' already exists.";
} else {
  $sql = "CREATE VIEW Frequent_Customers AS
  SELECT c.Fname, c.Lname, c.email, COUNT(b.car_ID) AS rental_count
  FROM Customers c
  JOIN Book b ON c.account_ID = b.account_ID
  WHERE b.book_status = 'A'
  GROUP BY c.account_ID
  HAVING rental_count > 3";

  if ($conn->query($sql) === TRUE) {
    echo "The view 'Frequent_Customers' was created successfully.";
  } else {
    echo "Error creating the view 'Frequent_Customers': " . $conn->error;
  }
}

// View: Canceled_Bookings
$sql = "SELECT * FROM information_schema.views WHERE table_name = 'Canceled_Bookings'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "The view 'Canceled_Bookings' already exists.";
} else {
  $sql = "CREATE VIEW Canceled_Bookings AS
  SELECT c.Fname, c.Lname, c.email, c.phone_No
  FROM Customers c
  JOIN Book b ON c.account_ID = b.account_ID
  WHERE b.book_status = 'C'";

  if ($conn->query($sql) === TRUE) {
    echo "The view 'Canceled_Bookings' was created successfully.";
  } else {
    echo "Error creating the view 'Canceled_Bookings': " . $conn->error;
  }
}

// View: Cars_Interested
$sql = "SELECT * FROM information_schema.views WHERE table_name = 'Cars_Interested'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "The view 'Cars_Interested' already exists.";
} else {
  $sql = "CREATE VIEW Cars_Interested AS
  SELECT 
      cs.car_ID,
      vd.full_car_name,
      COUNT(sh.car_id) AS search_count
  FROM Search_History sh
  LEFT JOIN Car_Storage cs ON sh.car_id = cs.car_ID
  LEFT JOIN Vehicle_Details vd ON cs.info_ID = vd.info_ID
  GROUP BY cs.car_ID, vd.full_car_name
  ORDER BY search_count DESC";

  if ($conn->query($sql) === TRUE) {
    echo "The view 'Cars_Interested' was created successfully.";
  } else {
    echo "Error creating the view 'Cars_Interested': " . $conn->error;
  }
}

// End of views creation

$conn->close();
?>

<!-- may add butons to clear the sample tables and fill them with the sample data -->
