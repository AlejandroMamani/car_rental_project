<?php
session_start(); 

$servername = "localhost";
$username = "root";
$password = "";

$db = mysqli_connect($servername, $username, $password);    // same as new mysqli() in theory
$db_select = mysqli_select_db($db, "Car_rental_DB");

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

function redirect_to($location) {
    header("Location: " . $location);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $checkEmailAccess = "SELECT account_ID, password FROM Account_Access WHERE Email = '$email';";

    if ($result = $db->query($checkEmailAccess)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($row["password"] === $password) {

                $_SESSION["user_email"] = $email;
                $_SESSION["account_ID"] = $row["account_ID"]; // ensure consistency in naming

                // redirect based on acount type (Employee vs Customer)
                if ($row["account_ID"][0] == 'E') {
                    redirect_to("employeeHome.php");
                }
                redirect_to("home.php");
            } else {
                echo "Incorrect password. Try again.";
            }
        } else {
            echo "Incorrect email or password. Try again.";
        }
    } else {
        echo "There has been a fatal internal DB error.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body>
    <div class="container">
        <div class="center-box">
            <h1>Login</h1>
            <form action="index.php" method="post"> <!-- still needs to be added -->

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="login" required>
                </div>

                <div class="links">
                    If you don't have an account, <a href="register.php">register</a> <!-- register still needs to be added -->
                </div>

            </form>
        </div>
    </div>
</body>

</html>

 <!-- <?php 
//  no need to free data 
mysqli_close($db);
?> -->
