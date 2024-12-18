<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}
// Check if account_id is in session
if (!isset($_SESSION['account_ID'])) {
    die("Account ID is missing. Please log in again.");
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "Car_rental_DB";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$car_id = "";
//get book needed info
if (isset($_GET['car_id'])) {
    $car_id = $_GET['car_id'];
    $user_ID= $_GET['user_ID'];
    $pickup_time = $_GET['pickup_time'];
    $drop_time = $_GET['drop_time'];
    $price = $_GET['price'];
    $timeStamp= $_GET['timeStamp'];
    
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $status_text = $_POST['status_text'];
    $switch= $_POST['switch'];
    $account_ID = $_SESSION['account_ID'];

    if ($switch == "pickup") {
        $insert_in_history="UPDATE Car_History SET rental_status='$status_text', pickup_agent_ID='$account_ID'
            WHERE car_ID='$car_id' AND time_stamp='$timeStamp'";
        if ($conn->query($insert_in_history) === FALSE){
            $error = "Failed to sign for the car";
        } else {$success = "Car pickup signed successfully!";}
    } else if ($switch == "drop") {
        $insert_in_history="UPDATE Car_History SET rental_status='$status_text', drop_agent_ID='$account_ID'
            WHERE car_ID='$car_id' AND time_stamp='$timeStamp'";
        if ($conn->query($insert_in_history) === FALSE){
            $error = "Failed to sign for the car";
        } else {
            $success = "Car drop signed successfully!";
            $book_quert = "UPDATE Book SET book_status='D' WHERE car_ID='$car_id' AND time_stamp='$timeStamp'";
            if ($conn->query($book_quert) === FALSE){
                $error = "Failed to sign for the car";
            }
        }
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Car</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<header>
    <nav class="navbar">
        <ul>
            <li><a href="employeeHome.php">Home</a></li>
            <li><a href="ESearch.php">Search Cars</a></li>
            <li><a href="EBook.php">Book for customer</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>
<body>
    <h1>Worker sign book</h1>
    <?php if (isset($success)) { ?>
        <p style="color: green;">
            <?php echo $success; ?>
        </p>
    <?php } ?>
    <?php if (isset($error)) { ?>
        <p style="color: red;">
            <?php echo $error; ?>
        </p>
    <?php } ?>
    <form method="POST">
        <select id="switch" name="switch">
            <option value="pickup">Sign for pick up</option>
            <option value="drop">sign for drop</option>
        </select>

        <label for="account_id">Customer Account ID:</label>
        <input type="text" id="account_id" name="account_id" value="<?php echo $user_ID; ?>" readonly>

        <label for="car_id">Car ID:</label>
        <input type="text" id="car_id" name="car_id" value="<?php echo $car_id; ?>" readonly>

        <label for="pickup_time">Pickup Time:</label>
        <input type="datetime-local" id="pickup_time" name="pickup_time" value="<?php echo $pickup_time; ?>" required>

        <label for="drop_time">Drop Time:</label>
        <input type="datetime-local" id="drop_time" name="drop_time" value="<?php echo $drop_time; ?>" required>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?php echo $price; ?>" readonly><br>

        <label for="status_text">Status of rent:</label>
        <input type="text" id="status_text" name="status_text" required>

        <button type="submit">Sign and give/recive keys</button>
    </form>
    
</body>
</html>