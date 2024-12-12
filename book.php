<?php
session_start();

// Check if user is logged 
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}

//require_once 'config.php'; // Include database 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = $_POST['car_id'];
    $pickup_location = $_POST['pickup_location'];
    $drop_location = $_POST['drop_location'];
    $pickup_time = $_POST['pickup_time'];
    $drop_time = $_POST['drop_time'];
    $account_id = $_SESSION['account_id'];

    // Check if agent exists for pickup/drop-off locations
    $pickup_agent_query = "
        SELECT account_ID 
        FROM Employees 
        WHERE branch_ID = (
            SELECT branch_ID 
            FROM Branch 
            WHERE location = ?
        )
        ORDER BY RAND()
        LIMIT 1
    ";
    $pickup_agent_stmt = $conn->prepare($pickup_agent_query);
    $pickup_agent_stmt->bind_param("s", $pickup_location);
    $pickup_agent_stmt->execute();
    $pickup_agent_result = $pickup_agent_stmt->get_result();
    $pickup_agent = $pickup_agent_result->fetch_assoc()['account_ID'];

    $drop_agent_query = "
        SELECT account_ID 
        FROM Employees 
        WHERE branch_ID = (
            SELECT branch_ID 
            FROM Branch 
            WHERE location = ?
        )
        ORDER BY RAND()
        LIMIT 1
    ";
    $drop_agent_stmt = $conn->prepare($drop_agent_query);
    $drop_agent_stmt->bind_param("s", $drop_location);
    $drop_agent_stmt->execute();
    $drop_agent_result = $drop_agent_stmt->get_result();
    $drop_agent = $drop_agent_result->fetch_assoc()['account_ID'];

    if (!$pickup_agent || !$drop_agent) {
        $error = "No agents available at the specified locations.";
    } else {
        // Insert booking into Book 
        $query = "
            INSERT INTO Book (pickup_Location, drop_Location, pickup_time, drop_time, car_ID, book_status, account_ID, pickup_agent_ID, drop_agent_ID)
            VALUES (?, ?, ?, ?, ?, 'A', ?, ?, ?)
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "ssssssss", 
            $pickup_location, 
            $drop_location, 
            $pickup_time, 
            $drop_time, 
            $car_id, 
            $account_id, 
            $pickup_agent, 
            $drop_agent
        );

        if ($stmt->execute()) {
            $success = "Car booked successfully! Pickup agent: $pickup_agent, Drop agent: $drop_agent";
        } else {
            $error = "Failed to book car: " . $stmt->error;
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
        <p style="color: green;"><?php echo $success; ?></p>
    <?php } ?>
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>
    <form method="POST">
        <label for="car_id">Car ID:</label>
        <input type="text" id="car_id" name="car_id" required>

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

