-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2022 at 03:23 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bus_reservation`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(100) NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `route_id` varchar(255) NOT NULL,
  `customer_route` varchar(200) NOT NULL,
  `booked_amount` int(100) NOT NULL,
  `booked_seat` varchar(100) NOT NULL,
  `booking_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_id`, `customer_id`, `route_id`, `customer_route`, `booked_amount`, `booked_seat`, `booking_created`) VALUES
(70, 'MW7UX70', 'CUST-253411', 'RT-4119461', 'KATHMANDU &rarr; BHAKTAPUR', 80, '3', '2022-06-05 18:47:05');

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` int(100) NOT NULL,
  `bus_no` varchar(255) NOT NULL,
  `bus_assigned` tinyint(1) NOT NULL DEFAULT 0,
  `bus_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `bus_no`, `bus_assigned`, `bus_created`) VALUES
(45, 'ABC0010', 1, '2021-10-17 22:32:46'),
(46, 'XYZ7890', 0, '2021-10-17 22:33:15'),
(47, 'BCC9999', 0, '2021-10-17 22:33:22'),
(48, 'RDH4255', 1, '2021-10-17 22:33:36'),
(49, 'TTH8888', 1, '2021-10-18 00:05:32'),
(50, 'MMM9969', 1, '2021-10-18 00:06:02'),
(51, 'LLL7699', 0, '2021-10-18 00:06:42'),
(52, 'SSX6633', 0, '2021-10-18 00:06:52'),
(53, 'NBS4455', 0, '2021-10-18 09:27:49');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(100) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `customer_name` varchar(30) NOT NULL,
  `customer_phone` varchar(10) NOT NULL,
  `customer_created` datetime NOT NULL DEFAULT current_timestamp(),
  `user_name` varchar(30) NOT NULL,
  `user_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_id`, `customer_name`, `customer_phone`, `customer_created`, `user_name`, `user_password`) VALUES
(1, 'CUST-253411', 'aayush twayanas', '9860627', '2022-06-05 18:41:25', 'aayush_don', '$2y$10$dH6vCVrTQa.fsCdX.rDSh.RDExrdIttD2yyqI9GvF4cXEPMdSuM32');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(100) NOT NULL,
  `route_id` varchar(255) NOT NULL,
  `bus_no` varchar(155) NOT NULL,
  `route_cities` varchar(255) NOT NULL,
  `route_dep_date` date NOT NULL,
  `route_dep_time` time NOT NULL,
  `route_step_cost` int(100) NOT NULL,
  `route_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `route_id`, `bus_no`, `route_cities`, `route_dep_date`, `route_dep_time`, `route_step_cost`, `route_created`) VALUES
(53, 'RT-1908653', 'MVL1000', 'CITY1,CITY2', '2021-10-17', '22:05:00', 100, '2021-10-16 22:05:42'),
(54, 'RT-3835554', 'MMM9969', 'ZEKA,ZREGOW', '2021-10-19', '23:13:00', 70, '2021-10-16 22:12:32'),
(55, 'RT-9941455', 'RDH4255', 'EDROISCHESTER,BRUGOW', '2021-10-18', '10:00:00', 110, '2021-10-17 22:34:47'),
(56, 'RT-9069556', 'XYZ7890', 'ANTALAND,ZREGOW', '2021-10-19', '11:40:00', 85, '2021-10-17 23:39:57'),
(57, 'RT-775557', 'ABC0010', 'ENCEFORD,VLIRGINIA', '2021-10-19', '13:30:00', 131, '2021-10-17 23:42:12'),
(58, 'RT-753558', 'TTH8888', 'ARKBY,VEIM', '2021-10-20', '12:04:00', 55, '2021-10-18 00:04:42'),
(61, 'RT-4119461', 'BA1252', 'KATHMANDU,BHAKTAPUR', '2022-06-05', '11:00:00', 80, '2022-06-04 12:16:16');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `bus_no` varchar(155) NOT NULL,
  `seat_booked` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`bus_no`, `seat_booked`) VALUES
('ABC0010', NULL),
('BCC9999', NULL),
('LLL7699', NULL),
('MMM9969', ''),
('NBS4455', NULL),
('RDH4255', ''),
('SSX6633', NULL),
('TTH8888', ''),
('XYZ7890', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_fullname` varchar(100) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_fullname`, `user_name`, `user_password`, `user_created`) VALUES
(1, 'Abhisek Sanjel', 'admin', '$2y$10$7rLSvRVyTQORapkDOqmkhetjF6H9lJHngr4hJMSM2lHObJbW5EQh6', '2021-06-02 13:55:21'),
(3, 'aayush twayana', 'aayush_don', '$2y$10$ZeChUsHhJIhDyDKscAjM7.EbkHKx70r56.lZMHDVymMeoZINsD86m', '2022-06-05 14:22:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`bus_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
