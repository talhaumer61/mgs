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
-- Table structure for table `sms_pb_paper_questions`
--

CREATE TABLE `sms_pb_paper_questions` (
  `id` int(11) NOT NULL,
  `id_paper` int(11) NOT NULL,
  `id_question` int(11) NOT NULL,
  `date_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sms_pb_paper_questions`
--
ALTER TABLE `sms_pb_paper_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paper_question_belong_to_question` (`id_paper`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sms_pb_paper_questions`
--
ALTER TABLE `sms_pb_paper_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sms_pb_paper_questions`
--
ALTER TABLE `sms_pb_paper_questions`
  ADD CONSTRAINT `paper_question_belong_to_question` FOREIGN KEY (`id_paper`) REFERENCES `sms_pb_papers` (`paper_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
