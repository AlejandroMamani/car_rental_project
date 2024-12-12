<?php
@session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Loging Out</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body>
    <div class="container">
        <div class="center-box">
            <h1>Thanks for using our services</h1>
            <form action="logout.php" method="post"> 

                <div class="links">
                    You can close the tab or go to <a href="register.php">register</a> or <a href="index.php">login</a>  
                </div>

            </form>
        </div>
    </div>
</body>

</html>



