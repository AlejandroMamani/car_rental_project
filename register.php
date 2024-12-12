<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Retrieve form data
    $email = $_POST["email"];
    $password = $_POST["password"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $phone_number = $_POST["phone_number"];
    $driver_licence = $_POST["driver_license"];

    // Extra checks
    $check_email_sql = "SELECT * FROM User WHERE Email = '$email'";
    $check_email_result = $conn->query($check_email_sql);
    
    $check_password_length = strlen($password);
    $check_password_cases = (preg_match('/[A-Z]+/', $password) && preg_match('/[a-z]+/', $password) && preg_match('/[\d!$%^&]+/', $password));
    if ($check_email_result->num_rows > 0)
    {
        $error = "Email already exists. Please use a different email.";
    }
    else if ($check_password_length >= 8)
    {
        $error = "Password must be at least 8 characters long. Please try again.";
    }
    else if($check_password_cases)
    {
        $error = "Passwords must be at least eight characters long and include at least one uppercase letter, one lowercase letter, one number, 
        and one special character.";
    }    
    else
    {
        // Insert user into the database
        $sql = "INSERT INTO User (Email, Password, F_Name, LName, Phone_Number, Driver_License, Created_At)
                VALUES ('$email', '$password', '$first_name', '$last_name', '$phone_number', '$driver_license', NOW())";

        if ($conn->query($sql) === TRUE) {
            $success = "User registered successfully.";
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - AEM</title>
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
            </ul>
        </nav>
    </header>

    <main>
        <h2>Register</h2>
        <?php if (isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <?php if (isset($success)) { ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php } ?>
        <form action="register.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="phone_number">Phone Number:</label>
            <input type="tel" id="phone_number" name="phone_number" required>

            <label for="driver_license">Driver License:</label>
            <input type="text" id="driver_license" name="driver license" required>

            <button type="submit">Register</button>
        </form>
    </main>
</body>
</html>
