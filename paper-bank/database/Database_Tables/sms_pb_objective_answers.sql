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
-- Table structure for table `sms_pb_objective_answers`
--

CREATE TABLE `sms_pb_objective_answers` (
  `answer_id` int(10) UNSIGNED NOT NULL,
  `id_question` int(11) NOT NULL,
  `e_option_a` varchar(255) NOT NULL,
  `u_option_a` varchar(255) NOT NULL,
  `e_option_b` varchar(255) NOT NULL,
  `u_option_b` varchar(255) NOT NULL,
  `e_option_c` varchar(255) NOT NULL,
  `u_option_c` varchar(255) NOT NULL,
  `e_option_d` varchar(255) NOT NULL,
  `u_option_d` varchar(255) NOT NULL,
  `e_option_correct` varchar(255) NOT NULL,
  `u_option_correct` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sms_pb_objective_answers`
--
ALTER TABLE `sms_pb_objective_answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `objective_belongs_to_question` (`id_question`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sms_pb_objective_answers`
--
ALTER TABLE `sms_pb_objective_answers`
  MODIFY `answer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
