<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
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
    $pickup_location = $_GET['pickup_location'];
}

// Booking logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = $_POST['car_id'];
    $pickup_location = $_POST['pickup_location'];
    $drop_location = $_POST['drop_location'];
    $pickup_time = $_POST['pickup_time'];
    $drop_time = $_POST['drop_time'];
    $account_id = $_POST['account_id'];

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
        $insert_query = "INSERT INTO Book (pickup_Location, drop_Location, pickup_time, drop_time, car_ID, book_status, account_ID)
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
            $insert_in_history="INSERT INTO Car_History (rental_status, pickup_agent_ID, drop_agent_ID, total_payment, pickup_location, drop_location, car_ID)
                VALUES ('Waiting for pick up', 'NA', 'NA', '$price', '$pickup_location', '$pickup_location', '$car_id')";
            if ($conn->query($insert_in_history) === FALSE){
                echo "Error: " . $insert_in_history . "<br>" . $conn->error;
            } 
        } else {
            $error = "Failed to book car: " . $insert_stmt->error;
        }
    }
}
// get unavailable schedules
$unavailable_query = "SELECT pickup_time, drop_time FROM Book WHERE car_ID = '$car_id' AND book_status = 'A' ORDER BY pickup_time asc";
if ($result = $conn->query($unavailable_query)) {
    if ($result->num_rows > 0) {
        $result = $conn->query($unavailable_query);
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
            <!-- <li><a href="rental_history.php">View Rental History</a></li> -->
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
        <label for="account_id">Account ID:</label>
        <input type="text" id="account_id" name="account_id" required>

        <label for="car_id">Car ID:</label>
        <input type="text" id="car_id" name="car_id" value="<?php echo $car_id; ?>" required>

        <label for="pickup_location">Pickup Location:</label>
        <input type="text" id="pickup_location" name="pickup_location" value="<?php echo $pickup_location; ?>" required>

        <label for="drop_location">Drop Location:</label>
        <input type="text" id="drop_location" name="drop_location" value="<?php echo $pickup_location; ?>" required>

        <label for="pickup_time">Pickup Time:</label>
        <input type="datetime-local" id="pickup_time" name="pickup_time" required>

        <label for="drop_time">Drop Time:</label>
        <input type="datetime-local" id="drop_time" name="drop_time" required>

        <button type="submit">Book Now</button>
    </form>
    <h2>Unavailable times</h2>
    <?php if (isset($result) && $result->num_rows > 0) { ?>
        <table cellspacing="12">
            <thead>
                <tr>
                    <th>Date:</th>
                    <th>Time:</th>
                </tr>
            </thead>
            
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['pickup_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['drop_time']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No unavailable times found.</p>
    <?php } ?>
</body>
</html>
