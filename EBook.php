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

//get book needed info
if (isset($_GET['car_id'])) {
    $car_id = $_GET['car_id'];
}

// Booking logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = $_POST['car_id'];
    $pickup_location = $_POST['pickup_location'];
    $drop_location = $_POST['drop_location'];
    $pickup_time = $_POST['pickup_time'];
    $drop_time = $_POST['drop_time'];
    $account_id = $_SESSION['account_id'];

    // Check for overlapping bookings
    $overlap_query = "
        SELECT * FROM Book 
        WHERE car_ID = ? 
        AND (
            (pickup_time < ? AND drop_time > ?)
        )
    ";
    $overlap_stmt = $conn->prepare($overlap_query);
    $overlap_stmt->bind_param("sss", $car_id, $drop_time, $pickup_time);
    $overlap_stmt->execute();
    $overlap_result = $overlap_stmt->get_result();

    if ($overlap_result->num_rows > 0) {
        $error = "This car is already booked for the selected time slot.";
    } else {
        // Insert booking into Book table
        $insert_query = "
            INSERT INTO Book (pickup_Location, drop_Location, pickup_time, drop_time, car_ID, book_status, account_ID)
            VALUES (?, ?, ?, ?, ?, 'A', ?)
        ";

        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param(
            "ssssss",
            $pickup_location,
            $drop_location,
            $pickup_time,
            $drop_time,
            $car_id,
            $account_id
        );

        if ($insert_stmt->execute()) {
            $success = "Car booked successfully!";
        } else {
            $error = "Failed to book car: " . $insert_stmt->error;
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
            <li><a href="home.php">Home</a></li>
            <li><a href="search.php">Search Cars</a></li>
            <li><a href="book.php">Book a Car</a></li>
            <li><a href="rental_history.php">View Rental History</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>
<body>
    <h1>Book a Car</h1>
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
        <label for="car_id">Car ID:</label>
        <input type="text" id="car_id" name="car_id" value="<?php echo $car_id; ?>" required>

        <label for="pickup_location">Pickup Location:</label>
        <input type="text" id="pickup_location" name="pickup_location" required>

        <label for="drop_location">Drop Location:</label>
        <input type="text" id="drop_location" name="drop_location" required>

        <label for="pickup_time">Pickup Time:</label>
        <input type="datetime-local" id="pickup_time" name="pickup_time" required>

        <label for="drop_time">Drop Time:</label>
        <input type="datetime-local" id="drop_time" name="drop_time" required>

        <button type="submit">Book Now</button>
    </form>
</body>
</html>
