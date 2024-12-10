<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php"); // Redirect to login page
    exit;
}

$user_email = $_SESSION['user_email'];
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
                <li><a href="home.php">Home</a></li>
                <li><a href="search.php">Search Cars</a></li>
                <li><a href="book.php">Book a Car</a></li>
                <li><a href="rental_history.php">View Rental History</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <h1>Welcome to Easy Ride Car Rental</h1>
        <p>Hello, <?php echo htmlspecialchars($user_email); ?>! What would you like to do today?</p>

        <div class="features">
            <a href="search.php" class="feature-link">Search Cars</a>
            <a href="book.php" class="feature-link">Book a Car</a>
            <a href="rental_history.php" class="feature-link">View Rental History</a>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Easy Ride Car Rental. All Rights Reserved.</p>
    </footer>
</body>
</html>

