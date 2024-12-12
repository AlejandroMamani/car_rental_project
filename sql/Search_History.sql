CREATE TABLE IF NOT EXISTS Search_History (
    search_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10), 
    branch_location VARCHAR(50),
    search_date DATE,
    car_id VARCHAR(10),
    search_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
