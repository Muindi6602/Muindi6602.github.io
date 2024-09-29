CREATE DATABASE IF NOT EXISTS Manchester;
USE Manchester;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(15) NOT NULL UNIQUE,
    phone_type VARCHAR(50),
    mac_address VARCHAR(50),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    is_logged_in TINYINT(1) DEFAULT 0, -- Tracks login status (1 for logged in, 0 for logged out)
    last_activity TIMESTAMP NULL DEFAULT NULL -- Records last user activity time
);



-- Create the 'admins' table if it doesn't already exist
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(15) NOT NULL UNIQUE,
    phone_type VARCHAR(50) DEFAULT 'Samsung A25',
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN NOT NULL DEFAULT TRUE
);

-- Insert the admin only if the phone_number or email doesn't exist already
INSERT INTO admins (name, phone_number, phone_type, email, password, is_admin)
VALUES ('Joseph Muindi', '254115783375', 'Samsung A25', 'admin@gmail.com', '1234567', TRUE)
WHERE NOT EXISTS (
    SELECT 1 FROM admins WHERE phone_number = '254115783375' OR email = 'admin@gmail.com'
);







CREATE TABLE IF NOT EXISTS vouchers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE, -- Unique voucher code
    price DECIMAL(10, 2) NOT NULL, -- Voucher price
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    user_message VARCHAR(255),
    admin_reply VARCHAR(255),  -- Column for admin's reply
    read_status TINYINT(1) DEFAULT 0,  -- Column for user message read status
    admin_reply_read_status TINYINT(1) DEFAULT 0,  -- Column for admin reply read status
    Time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp for user message
    admin_reply_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- Timestamp for admin reply
);



