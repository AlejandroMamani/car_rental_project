<?php
require_once 'config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check user credentials
    $sql = "SELECT * FROM User WHERE Email = '$email' AND Password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // User logged in successfully
        $user = $result->fetch_assoc();
        $_SESSION["user_id"] = $user['User_ID'];
        $_SESSION["f_name"] = $user['F_Name'];
        $_SESSION["role"] = $user['Role'];

        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid email or password.";
        error_log("Login failed for email: $email");
    }
} else {
    error_log("Invalid request method: " . $_SERVER["REQUEST_METHOD"]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - AEM</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <header>
            <nav class="navbar">
                <ul class="navbar-left">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="rentals.php">Rentals</a></li>
                </ul>

                <ul class="navbar-right">
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                </ul>
            </nav>
    </header>

    <main>
        <h2>Login</h2>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>