-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2024 at 10:54 AM
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
-- Database: `fitness`
--

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `viewed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactus`
--

INSERT INTO `contactus` (`id`, `name`, `email`, `message`, `viewed`) VALUES
(7, 'Rahim Morgan', 'libicasu@mailinator.com', 'Tempora velit error', 1),
(8, 'Mariko Baker', 'zylaferyby@mailinator.com', 'Laborum Ullamco aut', 1),
(9, 'Mariko Baker', 'zylaferyby@mailinator.com', 'Laborum Ullamco aut', 1),
(10, 'Mariko Baker', 'zylaferyby@mailinator.com', 'Laborum Ullamco aut', 1),
(11, 'Mariko Baker', 'zylaferyby@mailinator.com', 'Laborum Ullamco aut', 1),
(12, 'Mariko Baker', 'zylaferyby@mailinator.com', 'Laborum Ullamco aut', 1),
(13, 'Mariko Baker', 'zylaferyby@mailinator.com', 'Laborum Ullamco aut', 1),
(14, 'Mariko Baker', 'zylaferyby@mailinator.com', 'Laborum Ullamco aut', 1),
(15, 'Mariko Baker', 'zylaferyby@mailinator.com', 'Laborum Ullamco aut', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
