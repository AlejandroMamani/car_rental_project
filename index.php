<?php
$servername = "localhost";
$username = "root";
$password = "";

$db = mysqli_connect($servername, $username, $password);

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();     //may also use die instead
}

function redirect_to($location) {
    header("Location: ". $location);      
    exit;
}

session_start();

if (isset($_SESSION['User_email'])) {
    redirect_to("home.php");            // still needs to create 
}

?>

<!-- doing all this crap at 12pm and feeling like sleepy rn so there may be some bugs -->

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
            <form action="login.php" method="post"> <!-- still needs to be added -->

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id ="email"  required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="login" required>
                </div>

                <?php if (isset($_GET['error'])) {
                    if ($_GET['error'] === "0") {
                        echo "Incorrect email. Try again.";
                    }
                    else if ($_GET['error'] === "1") {
                        echo "Incorrect password. Try again.";
                    }
                    else if ($_GET['error'] === "2") {
                        echo "There has been a fatal internal DB error.";
                    }
                }
                ?>
                <div class="links">
                    If you don't have an account, <a href="register.php">register</a>  <!-- register still needs to be added -->
                </div>

            </form>
        </div>
    </div>
</body>

</html>



