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
-- Table structure for table `sms_pb_papers`
--

CREATE TABLE `sms_pb_papers` (
  `paper_id` int(11) NOT NULL,
  `id_subject` int(11) NOT NULL,
  `id_class` int(11) NOT NULL,
  `id_exam_type` int(11) NOT NULL,
  `id_chapter_from` int(11) NOT NULL,
  `id_chapter_to` int(11) NOT NULL,
  `id_paper_style` int(11) NOT NULL,
  `paper_time` varchar(100) NOT NULL,
  `no_mcqs` int(11) NOT NULL,
  `marks_mcq` int(11) NOT NULL,
  `no_short_question` int(11) NOT NULL,
  `marks_short_question` int(11) NOT NULL,
  `no_long_question` int(11) NOT NULL,
  `marks_long_question` int(11) NOT NULL,
  `no_lines_short_question` int(11) NOT NULL,
  `no_lines_long_question` int(11) NOT NULL,
  `id_added` int(11) NOT NULL,
  `id_modify` int(11) DEFAULT NULL,
  `id_deleted` int(11) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modify` datetime DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sms_pb_papers`
--
ALTER TABLE `sms_pb_papers`
  ADD PRIMARY KEY (`paper_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sms_pb_papers`
--
ALTER TABLE `sms_pb_papers`
  MODIFY `paper_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
