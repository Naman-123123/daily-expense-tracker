-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 12:16 PM
--
-- Demo data: both accounts use the password demo1234
--   demo@example.com  (expenses and income)
--   test@example.com  (a few expenses)
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
-- Database: `daily_expense`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `CategoryId` int(11) NOT NULL,
  `CategoryName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Mode` enum('expense','income') NOT NULL DEFAULT 'expense',
  `UserId` int(11) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`CategoryId`, `CategoryName`, `Mode`, `UserId`, `CreatedAt`) VALUES
(87, 'Food', 'expense', 69, '2025-10-27 15:31:10'),
(88, 'shopping', 'expense', 69, '2025-10-27 15:42:55'),
(89, 'mutual funds', 'income', 69, '2025-10-28 08:55:40'),
(90, 'academics', 'expense', 69, '2025-10-29 08:47:37'),
(91, 'medical', 'expense', 70, '2025-10-29 08:55:43'),
(92, 'food', 'expense', 70, '2025-10-29 08:57:02');

-- --------------------------------------------------------

--
-- Table structure for table `tblexpense`
--

CREATE TABLE `tblexpense` (
  `ID` int(10) NOT NULL,
  `UserId` int(10) NOT NULL,
  `ExpenseDate` date DEFAULT NULL,
  `CategoryId` int(11) NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ExpenseCost` varchar(200) DEFAULT NULL,
  `Description` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NoteDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblexpense`
--

INSERT INTO `tblexpense` (`ID`, `UserId`, `ExpenseDate`, `CategoryId`, `category`, `ExpenseCost`, `Description`, `NoteDate`) VALUES
(148, 69, '2025-10-27', 88, 'shopping', '10030', 'shoes ', '2025-10-27 15:43:08'),
(150, 69, '2025-10-29', 87, 'Food', '500', 'dominos ', '2025-10-29 08:47:18'),
(151, 69, '2025-10-29', 90, 'academics', '50', 'book ', '2025-10-29 08:47:45'),
(152, 70, '2025-10-28', 91, 'medical', '300', 'meds ', '2025-10-29 08:55:53'),
(153, 70, '2025-10-29', 92, 'food', '60', 'pani-puri\r\n ', '2025-10-29 08:57:15');

-- --------------------------------------------------------

--
-- Table structure for table `tblincome`
--

CREATE TABLE `tblincome` (
  `ID` int(10) NOT NULL,
  `UserId` int(10) NOT NULL,
  `IncomeDate` date DEFAULT NULL,
  `CategoryId` int(11) NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IncomeAmount` varchar(200) DEFAULT NULL,
  `Description` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NoteDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblincome`
--

INSERT INTO `tblincome` (`ID`, `UserId`, `IncomeDate`, `CategoryId`, `category`, `IncomeAmount`, `Description`, `NoteDate`) VALUES
(11, 69, '2025-10-28', 89, 'mutual funds', '5020', 'monthly ', '2025-10-28 08:56:07'),
(12, 69, '2025-10-22', 89, 'mutual funds', '100', 'monthly', '2025-10-28 09:26:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` varchar(12) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `verification_code`, `created_at`) VALUES
(69, 'Demo User', 'demo@example.com', '9000000001', '$2y$10$1Oc/RpG46jxi.rF/GvOex.lYzzM/OdO4jF1LJ0IYbHjRB24WcJ.G.', '1425a8f6c28d', '2025-10-27 20:59:09'),
(70, 'Test User', 'test@example.com', '9000000002', '$2y$10$5g39zIg0ZqouaWaFHNyOge7/SjLuDbPUF6IYd.BvjVR0GUZE8R7hu', '4613cf57c057', '2025-10-29 14:24:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`CategoryId`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `tblexpense`
--
ALTER TABLE `tblexpense`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblincome`
--
ALTER TABLE `tblincome`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `CategoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `tblexpense`
--
ALTER TABLE `tblexpense`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `tblincome`
--
ALTER TABLE `tblincome`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD CONSTRAINT `tblcategory_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
