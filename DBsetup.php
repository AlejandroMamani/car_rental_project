<?php
$servername = "localhost";
$username = "root";
$password = "";

// start connection
$db = mysqli_connect($servername, $username, $password);

// if (mysqli_connect_errno()) {
//     die("Connection failed: " . mysqli_connect_error());
// }
// echo "Connected successfully.";

$query = file_get_contents('sql/CREATE_Car_tables.sql');
$queries = explode(';', $query);

foreach ($queries as $sql) {
    if (trim($sql) != '') {
        $result = mysqli_query($db, $sql);

        if (!$result) {
            die("Error executing query: " . mysqli_error($db));
        }
    }
}
echo "\nMade it through the execution of each SQL statement. EMPTY DATABASE SET UP (unless one was already there).";

if (!$result) {
    exit("Failed database set up");
}

mysqli_close($db);