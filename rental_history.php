<!-- Ale is still working on this page -->
<?php
$servername = "localhost";
$username = "root";
$password = "";

session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}
if (!isset($_SESSION['account_ID'])) {
    header("Location: index.php");
    exit;
}

$db = mysqli_connect($servername, $username, $password);
$db_select = mysqli_select_db($db, "Car_rental_DB");

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>rent history</title>
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

    <main>
        <div class="main-box top">
            <h1>Rental History</h1>
            <div class="history box">
                <form action="rental_history.php" method="post">
                    <table cellspacing="12">
                        <thead>
                            <tr>
                                <th>Time recorded</th>
                                <th>Payment</th>
                                <th>Car Name</th>
                                <th>Car Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // use view for to build the customer history of vehicles 
                                $customerID = $_SESSION['account_ID'];
                                $history_query = "SELECT * FROM customer_history WHERE account_ID = '$customerID'";
                                if ($result = $db->query($history_query)) {
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo $row["time_stamp"];?> 
                                    <td><?php echo $row["total_payment"];?> 
                                    <td><?php echo $row["full_car_name"];?> 
                                    <td><?php echo $row["car_type"];?> 
                                </tr>
                            <?php
                                        }
                                    }
                                } else {
                                    echo "There is no rental history";
                                }
                                ?>
                            
                        </tbody>
                    </table>
                    <div class="field output">
                        
                    </div>
                </form>  


            </div>

        </div>
    </main>

</body>

<?php mysqli_close($db); ?>