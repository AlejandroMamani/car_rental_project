<?php
session_start();

// Check user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php"); 
    exit;
}

// chekc if database connection exists
if (!isset($conn)) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "Car_rental_DB";

    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $branch_location = $_POST['branch_location'];
    $search_date = $_POST['search_date'];

    $query = "
        SELECT cs.car_ID, vd.full_car_name, vd.color, vd.seat_capacity, cs.daily_rate
        FROM Car_Storage cs
        JOIN Vehicle_Details vd ON cs.info_ID = vd.info_ID
        JOIN Branch b ON cs.branch_ID = b.branch_ID
        WHERE b.location = ? AND cs.car_status = 'A'
          AND NOT EXISTS (
              SELECT 1
              FROM Book b
              WHERE b.car_ID = cs.car_ID
                AND (
                    b.pickup_time <= ? AND b.drop_time >= ?
                )
          );
    ";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("sss", $branch_location, $search_date, $search_date);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
    } else {
        $error = "Query execution failed: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Cars</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <h1>Search Available Cars</h1>
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>
    <form method="POST">
        <label for="branch_location">Branch Location:</label>
        <input type="text" id="branch_location" name="branch_location" required>

        <label for="search_date">Search Date:</label>
        <input type="date" id="search_date" name="search_date" required>

        <button type="submit">Search</button>
    </form>

    <?php if (isset($result) && $result->num_rows > 0) { ?>
        <h2>Available Cars</h2>
        <table>
            <thead>
                <tr>
                    <th>Car ID</th>
                    <th>Car Name</th>
                    <th>Color</th>
                    <th>Seats</th>
                    <th>Daily Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['car_ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['full_car_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['color']); ?></td>
                        <td><?php echo htmlspecialchars($row['seat_capacity']); ?></td>
                        <td><?php echo htmlspecialchars($row['daily_rate']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } elseif ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
        <p>No available cars found for the given location and date.</p>
    <?php } ?>
</body>
</html>
