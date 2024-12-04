-- Create the database (if it doesn't exist)
CREATE DATABASE IF NOT EXISTS cbc_ims;

-- Use the database
USE cbc_ims;

-- Create the admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('main_admin', 'membership_admin', 'finance_admin') NOT NULL
);

-- Insert example data (you can modify these values)
INSERT INTO admins (username, password, role) VALUES 
('main_admin', 'cbcims', 'main_admin'),
('membership_admin', 'cbcims', 'membership_admin'),
('finance_admin', 'cbcims', 'finance_admin');

CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    status ENUM('active', 'inactive') NOT NULL,
    join_date DATE NOT NULL
);



CREATE TABLE IF NOT EXISTS `visitors` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(255) NOT NULL,
    `age` INT NOT NULL,
    `address` TEXT NOT NULL,
    `email` VARCHAR(255),
    `sex` ENUM('male', 'female') NOT NULL,
    `birthdate` DATE NOT NULL,
    `contact_number` VARCHAR(15) NOT NULL,
    `network` VARCHAR(255) NOT NULL,
    `date_of_visit` DATE NOT NULL,
    `invited_by` VARCHAR(255),
    `assimilated_by` VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



