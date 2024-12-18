<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php"); // Redirect to login page
    exit;
}
$user_email = $_SESSION['user_email'];

$conn = mysqli_connect("localhost", "root", "", "Car_rental_DB");
$db_select = mysqli_select_db($conn, "Car_rental_DB");

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

$query = "SELECT E.account_ID, E.branch_ID FROM Employees as E WHERE E.email = '$user_email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $account_ID = $row["account_ID"];
        $branch_ID = $row["branch_ID"];
    }
}
$_SESSION['account_ID'] = $account_ID;
$_SESSION['branch_ID'] = $branch_ID;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Car Rental</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <nav class="navbar">
            <ul>
                <li><a href="employeeHome.php">Home</a></li>
                <li><a href="search.php">Search Cars</a></li>
                <li><a href="EBook.php">Stamp Book</a></li>
                <li><a href="rental_history.php">View Rental History</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <h1>Welcome to Easy Ride Car Rental</h1>
        <p>Hello, <?php echo htmlspecialchars($user_email); ?>! What would you like to do today?</p>

        <div class="bottom-box" >
            <div class="left-box" height="auto" overflow="auto">
                <h3> Upcoming Bookings: <br></h3>
                <ul>
                    <?php
                        $sql = "SELECT *, cs.location FROM Book as b, Car_Storage as cs 
                        WHERE b.book_status = 'A' AND cs.car_ID = b.car_id AND cs.branch_ID = '$branch_ID' 
                        ORDER BY b.pickup_time ASC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {?>
                                <li style="border: 3px solid #ccc; padding: 5px;">
                                    <?php echo $row["car_ID"].' '.$row["pickup_Location"].' '.$row["drop_Location"];?><br>
                                    <?php echo $row['pickup_time'].' '.$row['drop_time']; ?><br> 
                                    <a href="EBook.php?car_id=<?php echo $row['car_ID']?>">check IN/OUT car</a>
                                </li>
                            <?php
                            }
                        }
                    ?>
                </ul>
            </div>
            <div class="middle-box">
                <h3> Cancelled Bookings: <br></h3>
                <ul>
                    <?php
                        $sql = "SELECT * FROM Canceled_Bookings";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {?>
                                <li style="border: 3px solid #ccc; padding: 5px;">
                                    <?php echo $row["Fname"].' '.$row["Lname"].' '.$row["email"];?><br>
                                </li>
                            <?php
                            }
                        }
                    ?>
                </ul>
            </div>

            <div class="right-box">
            <h3> Frequent Customers: <br></h3>
                <ul>
                    <?php
                        $sql = "SELECT * FROM Frequent_Customers";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {?>
                                <li style="border: 3px solid #ccc; padding: 5px;">
                                    <?php echo $row["Fname"].' '.$row["Lname"].' '.$row["email"];?><br>
                                    <?php echo $row["rental_count"];?><br>
                                </li>
                            <?php
                            }
                        }
                    ?>
                </ul>
            </div>
                
        </div>               
        
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Easy Ride Car Rental. All Rights Reserved.</p>
    </footer>
</body>
</html>

 <?php
    $conn->close();
?>

