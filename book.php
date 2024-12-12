<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}

require_once 'config.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = $_POST['car_id'];
    $pickup_location = $_POST['pickup_location'];
    $drop_location = $_POST['drop_location'];
    $pickup_time = $_POST['pickup_time'];
    $drop_time = $_POST['drop_time'];
    $account_id = $_SESSION['account_id'];

    $query = "
        INSERT INTO Book (pickup_Location, drop_Location, pickup_time, drop_time, car_ID, book_status, account_ID)
        VALUES (?, ?, ?, ?, ?, 'A', ?)
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $pickup_location, $drop_location, $pickup_time, $drop_time, $car_id, $account_id);

    if ($stmt->execute()) {
        $success = "Car booked successfully!";
    } else {
        $error = "Failed to book car: " . $stmt->error;
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

