-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2025 at 11:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cbc_ims`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('main_admin','membership_admin','finance_admin','assimilation_admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `role`) VALUES
(1, 'main_admin', 'cbcims', 'main_admin'),
(2, 'membership_admin', 'cbcims1', 'membership_admin'),
(3, 'finance_admin', 'cbcims2', 'finance_admin'),
(4, 'assimilation_admin', 'cbcims3', 'assimilation_admin');

-- --------------------------------------------------------

--
-- Table structure for table `assimilation_history`
--

CREATE TABLE `assimilation_history` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `attendees` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assimilation_history`
--

INSERT INTO `assimilation_history` (`id`, `date`, `description`, `attendees`, `created_at`) VALUES
(1, '2024-12-31', 'Sunday Service', 'Kleyr Carmelina, John Doe', '2024-12-31 07:06:01');

-- --------------------------------------------------------

--
-- Table structure for table `assimilation_viewattendees`
--

CREATE TABLE `assimilation_viewattendees` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_history`
--

CREATE TABLE `attendance_history` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` enum('sunday_service','others') NOT NULL,
  `attendees` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_history`
--

INSERT INTO `attendance_history` (`id`, `date`, `description`, `attendees`) VALUES
(21, '2024-12-31', '', 'Kleyr Carmelina, John Doe, Maria Lopez, Juan Dela Cruz'),
(22, '2024-12-31', '', 'Kleyr Carmelina, John Doe, Maria Lopez, Juan Dela Cruz, Maria Santos, Luz Hernandez, Anna Garcia, Mark Villanueva, Catherine Lopez, Sophia Cruz'),
(23, '2025-01-05', '', 'Kleyr Carmelina, John Doe, Maria Lopez, Juan Dela Cruz, Maria Santos'),
(24, '2025-01-05', '', 'Kleyr Carmelina, John Doe'),
(25, '2025-01-05', '', 'Kleyr Carmelina, John Doe, Maria Lopez'),
(26, '2025-01-05', 'sunday_service', 'Kleyr Carmelina, John Doe, Maria Lopez, Juan Dela Cruz, Maria Santos, Luz Hernandez'),
(27, '2025-01-06', 'sunday_service', 'Kleyr Carmelina, John Doe, Maria Lopez, Juan Dela Cruz, Maria Santos');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_viewattendees`
--

CREATE TABLE `attendance_viewattendees` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `total_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `other_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `event_date`, `start_time`, `end_time`, `location`, `contact_person`, `other_details`) VALUES
(2, 'Communion sunday', '2025-01-05', '00:00:00', '00:00:00', '', '', NULL),
(3, 'sample', '2025-01-07', '00:00:00', '00:00:00', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `age` int(5) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `network` enum('none','youth','singles','women','men','senior') NOT NULL,
  `birthdate` date NOT NULL,
  `contact_number` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `full_name`, `age`, `address`, `email`, `sex`, `network`, `birthdate`, `contact_number`, `timestamp`) VALUES
(2, 'Kleyr Carmelina', 22, '123 sample', 'kleyrcarmelina5@gmail.com', 'female', 'singles', '2002-05-04', 2147483647, '2024-12-31 04:05:44'),
(3, 'John Doe', 25, '123 Maple Street, Springfield', 'john.doe@example.com', 'male', 'youth', '1998-06-15', 2147483647, '2024-12-31 04:05:44'),
(4, 'Maria Lopez', 32, '789 Palm Avenue, Miami', 'maria.lopez@example.com', 'female', 'singles', '1991-03-22', 2147483647, '2024-12-31 04:05:44'),
(13, 'Juan Dela Cruz', 28, '123 Rizal Street, Manila', 'juan.delacruz@example.com', 'male', 'youth', '1995-03-15', 2147483647, '2024-12-31 04:05:44'),
(14, 'Maria Santos', 35, '456 Bonifacio Street, Quezon City', 'maria.santos@example.com', 'female', 'singles', '1988-07-22', 2147483647, '2024-12-31 04:05:44'),
(16, 'Luz Hernandez', 29, '101 Luna Avenue, Davao City', 'luz.hernandez@example.com', 'female', 'singles', '1994-11-08', 2147483647, '2024-12-31 04:05:44'),
(18, 'Anna Garcia', 23, '303 Rizal Boulevard, Iloilo City', 'anna.garcia@example.com', 'female', 'youth', '2000-05-12', 2147483647, '2024-12-31 04:05:44'),
(19, 'Mark Villanueva', 38, '404 Quezon Avenue, Baguio City', 'mark.villanueva@example.com', 'male', 'singles', '1985-08-19', 2147483647, '2024-12-31 04:05:44'),
(20, 'Catherine Lopez', 27, '505 Luna Street, Bacolod City', 'catherine.lopez@example.com', 'female', 'singles', '1996-01-25', 2147483647, '2024-12-31 04:05:44'),
(22, 'Sophia Cruz', 40, '707 Magsaysay Avenue, Zamboanga City', 'sophia.cruz@example.com', 'female', '', '1983-06-09', 2147483647, '2024-12-31 04:05:44'),
(23, 'sample', 34, '123 sample', 'sample@gmail.com', 'male', 'youth', '2024-12-03', 2147483647, '2024-12-31 04:05:44');

-- --------------------------------------------------------

--
-- Table structure for table `member_attendance`
--

CREATE TABLE `member_attendance` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `attendees` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_attendance`
--

INSERT INTO `member_attendance` (`id`, `date`, `attendees`) VALUES
(1, '0000-00-00', ''),
(2, '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` enum('Collection','Expense') NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `sex` enum('male','female') NOT NULL,
  `birthdate` date NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `network` varchar(255) NOT NULL,
  `date_of_visit` date NOT NULL,
  `invited_by` varchar(255) DEFAULT NULL,
  `assimilated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `full_name`, `age`, `address`, `email`, `sex`, `birthdate`, `contact_number`, `network`, `date_of_visit`, `invited_by`, `assimilated_by`) VALUES
(2, 'John Doe', 25, '123 Main Street, Cityville', 'johndoe@example.com', 'male', '1998-05-14', '1234567890', 'youth', '2024-12-01', 'Jane Smith', 'Paul Brown'),
(3, 'Jane Smith', 30, '456 Elm Street, Townville', 'janesmith@example.com', 'female', '1994-08-22', '0987654321', 'singles', '2024-12-02', 'Sarah Taylor', 'Emily Davis'),
(4, 'Michael Johnson', 35, '789 Pine Street, Villageville', 'michaelj@example.com', 'male', '1989-03-10', '1122334455', 'men', '2024-12-03', 'John Doe', 'Andrew Green'),
(5, 'Emily Davis', 28, '101 Oak Street, Hamletville', 'emilyd@example.com', 'female', '1996-12-15', '6677889900', 'women', '2024-12-01', 'Paul Brown', 'Jane Smith');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assimilation_history`
--
ALTER TABLE `assimilation_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assimilation_viewattendees`
--
ALTER TABLE `assimilation_viewattendees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_history`
--
ALTER TABLE `attendance_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_viewattendees`
--
ALTER TABLE `attendance_viewattendees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `member_attendance`
--
ALTER TABLE `member_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `assimilation_history`
--
ALTER TABLE `assimilation_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assimilation_viewattendees`
--
ALTER TABLE `assimilation_viewattendees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_history`
--
ALTER TABLE `attendance_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `attendance_viewattendees`
--
ALTER TABLE `attendance_viewattendees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `member_attendance`
--
ALTER TABLE `member_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
