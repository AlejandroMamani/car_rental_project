<?php
$servername = "localhost";
$username = "root";
$password = "";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php"); // Redirect to login page
    exit;
}

$user_email = $_SESSION['user_email'];

$conn = mysqli_connect("localhost", "root", "", "Car_rental_DB");
$db_select = mysqli_select_db($conn, "Car_rental_DB");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Car Rental</title>
    <link rel="stylesheet" href="style/style.css" type= "text/css">
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <nav class="navbar">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="search.php">Search Cars</a></li>
                <!-- <li><a href="book.php">Book a Car</a></li> -->
                <li><a href="rental_history.php">View Rental History</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <h1>Welcome to Easy Ride Car Rental</h1>
        
        <p>Hello, <?php echo htmlspecialchars($user_email); ?>! What would you like to do today? <?php echo htmlspecialchars($_SESSION['account_ID']); ?></p>

        <div class="features">
            <a href="search.php" class="feature-link">Search Cars</a>
            <a href="book.php" class="feature-link">Book a Car</a>
            <a href="rental_history.php" class="feature-link">View Rental History</a>
        </div>

        <div class="bottom-box" >
            <div class="left-side">

                <h3> Our locations: <br></h3>
                <h4>Branches Location<br></h4>
                <?php
                    $sql = "SELECT location FROM branch";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<td>" . $row["location"] . "<br></td>";
                        }
                    }
                ?>
                <h4>Car Storages<br></h4>
                <?php
                    $sql = "SELECT location FROM car_storage";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<td>" . $row["location"] . "<br></td>";
                        }
                    }
                ?>
            </div>
            <div class="right-side">
                <img src="https://shortwork.com/wp-content/uploads/2019/10/DSC_9554_5_6.jpg" alt="Car Rental" width="100%" height="100%" object-fit="cover">
            </div>
                
        </div>               
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Easy Ride Car Rental. All Rights Reserved.</p>
    </footer>
</body>
</html>

