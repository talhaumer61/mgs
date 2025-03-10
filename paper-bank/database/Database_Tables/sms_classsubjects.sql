-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2021 at 07:46 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `paper_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `sms_classsubjects`
--

CREATE TABLE `sms_classsubjects` (
  `subject_id` int(7) NOT NULL,
  `subject_status` int(1) NOT NULL,
  `subject_code` varchar(35) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `subject_totalmarks` float NOT NULL,
  `subject_passmarks` float NOT NULL,
  `subject_type` int(1) NOT NULL,
  `subject_book` varchar(50) NOT NULL,
  `subject_edition` varchar(20) NOT NULL,
  `subject_publisher` varchar(70) NOT NULL,
  `id_cat` int(3) NOT NULL,
  `id_class` int(5) NOT NULL,
  `id_campus` int(4) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `id_deleted` int(11) NOT NULL,
  `ip_deleted` varchar(150) NOT NULL,
  `date_deleted` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sms_classsubjects`
--
ALTER TABLE `sms_classsubjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sms_classsubjects`
--
ALTER TABLE `sms_classsubjects`
  MODIFY `subject_id` int(7) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
