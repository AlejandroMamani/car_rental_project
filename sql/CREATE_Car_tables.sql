create schema if not exists Car_rental_DB;
use Car_rental_DB;

-- make sure to run them in order!!!!

create table if not exists Branch (
	branch_name varchar(30),
    location varchar(50) NOT NULL,
    branch_ID varchar(10) NOT NULL unique,
    primary key (branch_ID)
);

create table if not exists Account_Access (
	account_ID varchar(10) NOT NULL unique,
    email varchar(30) NOT NULL unique, 		-- set to key value and changed to be unique
    password varchar(50) NOT NULL,
    primary key (account_ID, email)
);

create table if not exists Employees (
	Fname varchar(20) NOT NULL,
    Lname varchar(30) NOT NULL,					-- changed to a regular attribute (not key anymore)
    branch_ID varchar(10) NOT NULL unique,
    department varchar(20) NOT NULL,
    account_ID varchar(10) NOT NULL unique,	-- set to key value
    email varchar(30) NOT NULL unique, 		-- set to key value and changed to be unique
    address varchar(70),
    phone_No varchar(20) NOT NULL,
    primary key (account_ID),
    foreign key (branch_ID) references Branch(branch_ID) ON UPDATE cascade,
    foreign key (account_ID) references Account_Access(account_ID)
		ON DELETE cascade
);

create table if not exists Customers (
	Fname varchar(20) NOT NULL,
    Lname varchar(30) NOT NULL,
    address varchar(50),
    account_ID varchar(10) NOT NULL unique,
    driver_licence varchar(20) NOT NULL unique,
    email varchar(50) NOT NULL unique, 		-- set to key value and changed to be unique
    phone_No varchar(20) NOT NULL,
    primary key (driver_licence),
    foreign key (account_ID) references Account_Access(account_ID)
		ON DELETE cascade
);

create table if not exists Vehicle_Details(				-- manufacturer is removed as it is irrelevant
	full_car_name varchar(60) NOT NULL,
    info_ID int auto_increment NOT NULL unique,				-- added to match with car_storage
    seat_capacity tinyint default '2',
    model_year varchar(4) NOT NULL,
    car_description text default 'No description',		-- name changed changed due to conflicts with sql naming convenctions!!!
    color varchar(20) NOT NULL,
	fuel_type varchar(15),
    user_rate char NOT NULL default '0',		-- use letters for rating (subject to be changed if needed to numerical val)
	car_type varchar(5) NOT NULL,
    millage int NOT NULL,
    primary key(info_ID, model_year)
);

create table if not exists Car_Storage(					-- table name changed due to conflicts with sql naming convenctions!!!
    info_ID int NOT NULL,					-- added to match with vehicle_details. !!! Must be set !!!
    car_ID varchar(10) NOT NULL unique,
    branch_ID varchar(10) NOT NULL,
    car_status char NOT NULL,					-- to save space (involved logic to print in webpage) and name changed!!!
    location varchar(50) NOT NULL,
    price decimal(11,2) NOT NULL,						-- needs to be formated for printing
    registration_No varchar(25) NOT NULL unique,		-- moved from vehicle details to car_storage!!!
    daily_rate int check (daily_rate >= 0 AND daily_rate <= 100),								-- int as it will be a factor of calculatoins and storage for details	-- moved from vehicle details to car_storage!!!
    primary key(car_ID, branch_ID, registration_No),
    foreign key(info_ID) references Vehicle_Details (info_ID) ON UPDATE cascade
);		-- car_name varchar(20) NOT NULL,					-- removed due to redundancy!!!

create table if not exists Car_History(				-- table name changed due to conflicts with sql naming convenctions!!!
	rental_status text,
    time_stamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,						-- record time of creation
    pickup_agent_ID varchar(10) NOT NULL,					-- same as account_ID
    drop_agent_ID varchar(10) NOT NULL,
    total_payment decimal(11,2) NOT NULL,
    pickup_location varchar(50) NOT NULL,
    drop_location varchar(50) NOT NULL,
    car_ID varchar(10) NOT NULL,
    primary key (time_stamp),
    foreign key (car_ID) references Car_Storage (car_ID) ON UPDATE cascade
);

CREATE TABLE IF NOT EXISTS Book(
    pickup_Location VARCHAR(50) NOT NULL,
    drop_Location VARCHAR(50) NOT NULL,
    pickup_time DATETIME NOT NULL,
    drop_time DATETIME NOT NULL,
    car_ID VARCHAR(10) NOT NULL,
    book_status CHAR NOT NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    account_ID VARCHAR(10) NOT NULL,
    PRIMARY KEY (car_ID, pickup_time, drop_time), -- Ensure no overlap for same car at same time
    FOREIGN KEY (car_ID) REFERENCES Car_Storage (car_ID) ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Search_History (
    search_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10), 
    branch_location VARCHAR(50),
    search_date DATE,
    car_id VARCHAR(10),
    search_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);






