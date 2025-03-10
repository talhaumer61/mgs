-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2021 at 07:48 AM
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
-- Table structure for table `sms_pb_paper_style`
--

CREATE TABLE `sms_pb_paper_style` (
  `paper_style_id` int(11) NOT NULL,
  `paper_style_name` varchar(100) NOT NULL,
  `paper_style_status` tinyint(1) NOT NULL,
  `id_added` int(11) DEFAULT NULL,
  `id_modify` int(11) DEFAULT NULL,
  `id_deleted` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_modify` datetime DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sms_pb_paper_style`
--

INSERT INTO `sms_pb_paper_style` (`paper_style_id`, `paper_style_name`, `paper_style_status`, `id_added`, `id_modify`, `id_deleted`, `date_added`, `date_modify`, `date_deleted`) VALUES
(2, 'English Medium', 1, 1, NULL, NULL, '2021-09-03 12:27:07', NULL, NULL),
(3, 'Urdu Medium', 1, 1, NULL, NULL, '2021-09-03 12:48:47', NULL, NULL),
(4, 'English & Urdu Medium', 1, 1, NULL, NULL, '2021-09-03 12:53:19', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sms_pb_paper_style`
--
ALTER TABLE `sms_pb_paper_style`
  ADD PRIMARY KEY (`paper_style_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sms_pb_paper_style`
--
ALTER TABLE `sms_pb_paper_style`
  MODIFY `paper_style_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
