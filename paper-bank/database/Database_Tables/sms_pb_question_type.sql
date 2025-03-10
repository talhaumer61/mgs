-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2021 at 07:49 AM
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
-- Table structure for table `sms_pb_question_type`
--

CREATE TABLE `sms_pb_question_type` (
  `question_type_id` int(11) NOT NULL,
  `question_type_name` varchar(100) NOT NULL,
  `question_type_status` tinyint(1) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `id_added` int(11) NOT NULL,
  `id_modify` int(11) DEFAULT NULL,
  `id_deleted` int(11) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modify` datetime DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL,
  `ip_deleted` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sms_pb_question_type`
--

INSERT INTO `sms_pb_question_type` (`question_type_id`, `question_type_name`, `question_type_status`, `is_deleted`, `id_added`, `id_modify`, `id_deleted`, `date_added`, `date_modify`, `date_deleted`, `ip_deleted`) VALUES
(1, 'Long', 1, 0, 1, 1, NULL, '2021-08-02 11:14:11', '2021-09-13 06:42:06', NULL, NULL),
(2, 'Short', 1, 0, 1, 1, NULL, '2021-08-02 11:14:28', '2021-08-03 13:40:51', NULL, NULL),
(3, 'MCQs', 1, 0, 1, 1, NULL, '2021-08-02 11:14:36', '2021-08-24 09:28:08', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sms_pb_question_type`
--
ALTER TABLE `sms_pb_question_type`
  ADD PRIMARY KEY (`question_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sms_pb_question_type`
--
ALTER TABLE `sms_pb_question_type`
  MODIFY `question_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
