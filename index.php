<?php
// Start the session for user authentication
session_start();

// Basic database connection
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);
$dbname = mysqli_select_db($conn, "Car_rental_DB");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function redirect_to($location) {
    header("Location: ". $location);      
    exit;
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $checkEmailAccess = "SELECT * FROM Account_Access WHERE email = '$email';";

        if ($result = $conn->query($checkEmailAccess)) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row["password"] === $password) {
    
                    session_start();
                    $_SESSION["user_email"] = $email;
                    $_SESSION["account_ID"] = $row["account_ID"];
    
                    if ($row["account_ID"][0] == 'E') {
                        redirect_to("employeeHome.php");
                    }
                    redirect_to("home.php");
                }
                else {
                    echo "Incorrect password. Try again.";
                }
            }
            else {
                echo "Incorrect email or password. Try again.";
            }
        }
        else {
            echo "There has been a fatal internal DB error.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Ride Car Company</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            line-height: 1.6;
            color: #333;
        }

        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: rgb(234, 240, 233);
            line-height: 1.6;
            color: #333;
            position: relative;
            min-height: 100vh;
            padding-bottom: 50px; /* For footer */
        }

        /* Container styles */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header styles */
        header {
            background-color: rgb(34, 52, 70);
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
        }

        /* Navigation styles */
        nav {
            background-color: rgb(44, 62, 80);
            padding: 10px 0;
        }

        nav ul {
            display: flex;
            justify-content: center;
            list-style-type: none;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #3498db;
        }

        /* Login and Registration Form Styles */
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #34495e;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3498db;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        /* Car Listing Styles */
        /*.car-listing {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .car-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: calc(33.333% - 20px);
            margin-bottom: 20px;
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .car-card:hover {
            transform: scale(1.05);
        }

        .car-card img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .car-card h3 {
            margin: 10px 0;
            color: #2c3e50;
        }

        .car-card .details {
            color: #7f8c8d;
        } */

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            nav ul {
                flex-direction: column;
                align-items: center;
            }

            nav ul li {
                margin: 10px 0;
            }

            .car-card {
                width: 100%;
            }
        }

        /* Footer Styles */
        footer {
            background-color: rgb(34, 52, 70);
            color: white;
            text-align: center;
            padding: 15px 0;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">Easy Ride Car Co.</div>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>

    <div class="container">
        <!-- Error/Success Messages -->
        <?php if (isset($login_error)): ?>
            <div class="alert alert-danger"><?php echo $login_error; ?></div>
        <?php endif; ?>

        <?php if (isset($register_error)): ?>
            <div class="alert alert-danger"><?php echo $register_error; ?></div>
        <?php endif; ?>

        <?php if (isset($register_success)): ?>
            <div class="alert alert-success"><?php echo $register_success; ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <div id="login" class="form-container">
            <h2>Employee Login</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn">Login</button>
                <div class="links">
                    If you don't have an account, <a href="register.php">register</a>  <!-- register still needs to be added -->
                </div>
            </form>
        </div>

    <footer>
        <div class="container">
            <p>Easy Ride Car Company. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>
