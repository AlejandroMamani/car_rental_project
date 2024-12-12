set SQL_SAFE_UPDATES = 0;
delete from book;
delete from car_history;
delete from car_storage;
delete from vehicle_details;
delete from customers;
delete from employees;
delete from account_access;
delete from branch;

set SQL_SAFE_UPDATES = 1;

use car_rental_db;
drop table if exists book;
drop table if exists car_history;
drop table if exists car_storage;
drop table if exists employees;
drop table if exists customers;
drop table if exists vehicle_details;
drop table if exists account_access;
drop table if exists branch;
drop view if exists customer_history;