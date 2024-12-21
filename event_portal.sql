-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2024 at 05:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts_tbl`
--

CREATE TABLE `accounts_tbl` (
  `id` int(30) NOT NULL,
  `event_id` varchar(15) DEFAULT NULL,
  `username` varchar(24) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts_tbl`
--

INSERT INTO `accounts_tbl` (`id`, `event_id`, `username`, `password`, `token`) VALUES
(59, '111', 'jirodichos', '$2y$10$NjVlOGUxMGExZDMxYTBkMO0uZwyDSy3.C5SWPN0NInvaeBFaDpElO', 'NGQ4MjEyNTJkZGJlZDc3NGMwZDZjNmUyZTFjYTBkMGJiNzgwNWE2MTJkNWEyZjE5OTQxMmIxMDMwOTc3MzMxMg==');

-- --------------------------------------------------------

--
-- Table structure for table `events_tbl`
--

CREATE TABLE `events_tbl` (
  `id` int(30) NOT NULL,
  `event_code` int(255) NOT NULL,
  `ticket_price` int(255) DEFAULT NULL,
  `event_title` varchar(200) DEFAULT NULL,
  `event_venue` varchar(200) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events_tbl`
--

INSERT INTO `events_tbl` (`id`, `event_code`, `ticket_price`, `event_title`, `event_venue`, `event_date`, `event_time`) VALUES
(1, 114, 1500, 'battle of the bands II', 'sbma near 711 store', '2025-12-12', '13:21:58'),
(2, 112, 2500, 'Cultural Night Gala', 'Metro Theater Hall, Manila', '2024-02-20', '19:30:00'),
(3, 113, 1800, 'Jazz and Blues Night', 'Blue Note Jazz Club, Quezon City', '2024-05-10', '21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_tbl`
--

CREATE TABLE `ticket_tbl` (
  `id` int(100) NOT NULL,
  `ticket_id` int(100) NOT NULL,
  `event_code` varchar(255) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `Invoice` varchar(100) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_tbl`
--

INSERT INTO `ticket_tbl` (`id`, `ticket_id`, `event_code`, `customer_name`, `Invoice`, `created_at`) VALUES
(17, 111, '43', 'rita p. dichos', 'unpaid', '2024-12-12');

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `id` int(30) NOT NULL,
  `role` varchar(30) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`id`, `role`, `name`, `email`, `isdeleted`) VALUES
(1, 'customer', 'princess', 'gaudiaprincesstiong@gmail.com', 0),
(2, 'customer', 'iverson', 'iversonjavier@gmail.com', 0),
(3, 'admin', 'christopher test test', 'manzanochrisg@gmail.com', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts_tbl`
--
ALTER TABLE `accounts_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `event_id` (`event_id`);

--
-- Indexes for table `events_tbl`
--
ALTER TABLE `events_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`event_code`),
  ADD UNIQUE KEY `title` (`event_title`);

--
-- Indexes for table `ticket_tbl`
--
ALTER TABLE `ticket_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_id` (`ticket_id`),
  ADD UNIQUE KEY `customer_name` (`customer_name`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts_tbl`
--
ALTER TABLE `accounts_tbl`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `events_tbl`
--
ALTER TABLE `events_tbl`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=856;

--
-- AUTO_INCREMENT for table `ticket_tbl`
--
ALTER TABLE `ticket_tbl`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4422134;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
