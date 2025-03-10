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
-- Table structure for table `sms_examtypes`
--

CREATE TABLE `sms_examtypes` (
  `type_id` int(3) NOT NULL,
  `type_status` tinyint(1) NOT NULL,
  `type_term` int(1) NOT NULL,
  `type_name` varchar(150) NOT NULL,
  `total_marks` float NOT NULL,
  `pass_marks` float NOT NULL,
  `type_details` varchar(150) NOT NULL,
  `type_ordering` float NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `id_added` int(11) NOT NULL,
  `id_modify` int(11) NOT NULL,
  `id_deleted` int(11) NOT NULL,
  `ip_deleted` varchar(150) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modify` datetime NOT NULL,
  `date_deleted` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sms_examtypes`
--
ALTER TABLE `sms_examtypes`
  ADD PRIMARY KEY (`type_id`);
ALTER TABLE `sms_examtypes` ADD FULLTEXT KEY `cat_name` (`type_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sms_examtypes`
--
ALTER TABLE `sms_examtypes`
  MODIFY `type_id` int(3) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
