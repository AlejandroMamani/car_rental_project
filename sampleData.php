<?php
$servername = "localhost";
$username = "root";
$password = "";

// start connection
$db = mysqli_connect($servername, $username, $password);
$db_select = mysqli_select_db($db, "Car_rental_DB");

$query= 'INSERT INTO Branch (branch_name, location, branch_ID) VALUES ("East Coast", "1234 Main St", "B1"), ("West Coast", "5678 Second St", "B2"), ("Main Branch", "9012 Third St", "B3");';
$isInserted = mysqli_query($db, $query);

if (!$isInserted) {
    echo mysqli_error($db);
} 

$query= 'INSERT INTO Account_Access (account_ID, email, password) VALUES 
    ("E1", "a@b.com", "12345678"), ("E2", "c@d.com", "45678901"), ("C1", "e@f.com", "aaaaaaaa1");';
$isInserted = mysqli_query($db, $query);

if (!$isInserted) {
    echo mysqli_error($db);
} 

$query = 'INSERT INTO Employees (Fname, Lname, branch_ID, department, account_ID, email, address, phone_No) VALUES ("John", "Doe", "B1", "Sales", "E1", "a@b.com", "123 House St", "123-456-7890")
    , ("Jane", "Doe", "B2", "Sales", "E2", "c@d.com", "456 Main St", "987-654-3210");';
$isInserted = mysqli_query($db, $query);

if (!$isInserted) {
    echo mysqli_error($db);
} 

$query= 'INSERT INTO Customers (Fname, Lname, address, account_ID, driver_licence, email, phone_No) VALUES 
    ("John", "Doe", "123 Main St", "C1", "D12345601", "e@f.com", "999-000-1234");';
$isInserted = mysqli_query($db, $query);

if (!$isInserted) {
    echo mysqli_error($db);
} 

$query= 'INSERT INTO Vehicle_Details (full_car_name, info_ID, seat_capacity, model_year, car_description, color, fuel_type, user_rate, car_type, millage) VALUES
    ("Toyota Camry S", 1, 4, "2020", "Great car", "Red", "Gas", "A", "Sedan", 10000),
    ("Honda Civic", 2, 4, "2019", "Average car", "Blue", "Gas", "B", "Sedan", 80000),
    ("Toyota Tacoma", 3, 2, "2018", "Average car but great space", "Black", "Gas", "B", "Pickup", 50000),
    ("Honda Accord", 4, 4, "2017", "Average car", "White", "Gas", "A", "Sedan", 60300);'; // no need for info ID as it is auto incremented
$isInserted = mysqli_query($db, $query);

if (!$isInserted) {
    echo mysqli_error($db);
} 

$query= 'INSERT INTO Car_Storage (info_ID, car_ID, branch_ID, car_status, location, price, registration_No, daily_rate ) VALUES 
    (1, "TCAS1", "B1","A","101 Garage St", "200.00", "A123BS8092", 20), (2, "HCI1", "B1","U","200 Repairshop St", "220.00", "A123BS8093", 0),
    (4, "HAC1", "B2","A","1001 garage St.", "200.00", "A123BS8094", 80), (3, "TTA2", "B1","A","200 Repairshop St", "220.00", "A123BS8095", 17);';
$isInserted = mysqli_query($db, $query);

if (!$isInserted) {
    echo mysqli_error($db);
} 

$query= 'INSERT INTO Car_History (rental_status, pickup_agent_ID, drop_agent_ID, total_payment, pickup_location, drop_location, car_ID) VALUES 
    ("No new reports", "E1", "E2", "225.00", "101 Garage St", "200 Repairshop St", "HCI1");';
$isInserted = mysqli_query($db, $query);

if (!$isInserted) {
    echo mysqli_error($db);
} 

$query= 'INSERT INTO Book (pickup_Location, drop_Location, pickup_time, drop_time, car_ID, book_status, account_ID) VALUES
    ("101 Garage St", "200 Repairshop St", "2022-01-01 10:00:00", "2022-01-02 12:00:00", "HCI1", "C", "C1");';
$isInserted = mysqli_query($db, $query);

if (!$isInserted) {
    echo mysqli_error($db);
} 
?>