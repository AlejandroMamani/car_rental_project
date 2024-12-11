<!-- Ale is still working on this page -->

<?php
session_start();

if (!isset($_SESSION['User_email'])) {
    header("Location: index.php");
    exit;
}
// use view for 

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
            <div class="search options">
                <form action="rental_history.php" method="post">
                    <div class="field input">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" required>


            </div>

        </div>
    </main>

</body>