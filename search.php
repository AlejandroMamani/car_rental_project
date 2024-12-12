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
    $location = $_POST['branch_location'];
    //$search_date = $_POST['search_date'];
    $search_car = $_POST['search_car'];

    $user_id = $_SESSION['account_ID']; // if user id stored in session
    $log_query = "
        INSERT INTO Search_History (user_id, branch_location, search_date, car_id)
        VALUES (?, ?, NOW(), NULL)
    ";
    $log_stmt = $conn->prepare($log_query);
    $log_stmt->bind_param("ss", $user_id, $location);
    $log_stmt->execute();
    
    $query = "SELECT cs.car_ID, vd.full_car_name, vd.color, vd.seat_capacity, cs.daily_rate, cs.location
        From car_storage as cs, vehicle_details as vd
        where cs.info_ID = vd.info_ID and car_status = 'A' and cs.location like '%". $location ."%' 
        and vd.full_car_name like '%". $search_car ."%'";

    if ($result = $conn->query($query)) {
        if ($result->num_rows > 0) {
            $result = $conn->query($query);
        } else {
            $error = "No available cars found";
        }
    } else {
        $error = "Error executing query: " . $conn->error;
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

    <h1>Search Available Cars</h1>
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>
    <form method="POST">
        <label for="branch_location">Branch Location:</label>
        <input type="text" id="branch_location" name="branch_location" >

        <label for="search_car">Search Car:</label>
        <input type="text" id="search_car" name="search_car" >

        <button type="submit">Search</button>
    </form>

    <?php if (isset($result) && $result->num_rows > 0) { ?>
        <h2>Available Cars</h2>
        <table cellspacing="12">
            <thead>
                <tr>
                    <th>Car ID</th>
                    <th>Car Name</th>
                    <th>Color</th>
                    <th>Seats</th>
                    <th>Daily Rate</th>
                    <th>Location</th>
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
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } elseif ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
        <p>No available cars found for the given location and date.</p>
    <?php } ?>
</body>
</html>

