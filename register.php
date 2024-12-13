<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "Car_rental_DB";
$conn = new mysqli($servername, $username, $password, $database);
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Retrieve form data
    $email = $_POST["email"];
    $password = $_POST["password"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $phone_number = $_POST["phone_number"];
    $driver_license = $_POST["driver_license"];
    $address = $_POST["address"];

    // Extra checks
    $check_email_sql = "SELECT email FROM Customers WHERE Email = '$email'";
    $check_email_result = $conn->query($check_email_sql);
    
    $check_password_length = strlen($password);
    $check_password_cases = (preg_match('/[A-Z]+/', $password) && preg_match('/[a-z]+/', $password) && preg_match('/[\d!$%^&]+/', $password));
    if ($check_email_result->num_rows > 0)
    {
        $error = "Email already exists. Please use a different email.";
    }
    else if ($check_password_length < 8)
    {
        $error = "Password must be at least 8 characters long. Please try again.";
    }
    else if(!$check_password_cases)
    {
        $error = "Passwords must be at least eight characters long and include at least one uppercase letter, one lowercase letter, one number, 
        and one special character.";
    }    
    else
    {
        // Insert user into the database
        $sql = "INSERT INTO Account_Access (email, password, Account_ID)
            VALUES ('$email', '$password', '$first_name')";
        $sql2 = "INSERT INTO Customers (Fname, LName, Address, Account_ID, driver_license, email, phone_No)
        VALUES ('$first_name', '$last_name','$address', '$first_name', '$driver_license', '$email', '$phone_number')";

        if ($conn->query($sql) == TRUE)
        {
            $success = "User registered successfully.";
        }
        else
        {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang= "en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AEM</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
    <header>
          <nav class="navbar">
            <ul class="navbar-left">
                <li><a href="index.php">Back to log in</a></li>
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
        <div class="field input">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="field input">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="field input">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>

        <div class="field input">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>

        <div class="field input">
            <label for="phone_number">Phone Number:</label>
            <input type="tel" id="phone_number" name="phone_number" required>
        </div>

        <div class="field input">
            <label for="driver_license">Driver License:</label>
            <input type="text" id="driver_license" name="driver license" required>
        </div>

        <div class="field input">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
        </div>

        <div class="field input">
            <button type="submit">Register</button>
        </div>
        </form>
    </main>
</body>
</html>
