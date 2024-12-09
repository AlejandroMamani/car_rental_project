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