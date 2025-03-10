-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 27, 2024 at 10:29 AM
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
-- Database: `gptech_dodl`
--

-- --------------------------------------------------------

--
-- Table structure for table `mail_sent_detail`
--

CREATE TABLE `mail_sent_detail` (
  `mail_detail_id` int(11) NOT NULL,
  `id_mail` int(11) NOT NULL,
  `mail_sender` text NOT NULL,
  `mail_sender_name` text NOT NULL,
  `mail_reciver` text NOT NULL,
  `mail_reciver_name` text NOT NULL,
  `mail_cc` text NOT NULL,
  `mail_cc_name` text NOT NULL,
  `mail_bcc` text NOT NULL,
  `mail_bcc_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mail_sent_detail`
--

INSERT INTO `mail_sent_detail` (`mail_detail_id`, `id_mail`, `mail_sender`, `mail_sender_name`, `mail_reciver`, `mail_reciver_name`, `mail_cc`, `mail_cc_name`, `mail_bcc`, `mail_bcc_name`) VALUES
(1, 1, 'no-reply.dodl@mul.edu.pk', 'DODL', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', '', '', ''),
(2, 2, 'no-reply.dodl@mul.edu.pk', 'DODL', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', '', '', ''),
(3, 3, 'no-reply.dodl@mul.edu.pk', 'DODL', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', '', '', ''),
(4, 4, 'no-reply.dodl@mul.edu.pk', 'DODL', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', '', '', ''),
(5, 5, 'no-reply.dodl@mul.edu.pk', 'DODL', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', '', '', ''),
(6, 6, 'no-reply.dodl@mul.edu.pk', 'DODL', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', '', '', ''),
(7, 6, 'no-reply.dodl@mul.edu.pk', 'DODL', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', '', '', ''),
(8, 6, 'no-reply.dodl@mul.edu.pk', 'DODL', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', '', '', ''),
(9, 6, 'no-reply.dodl@mul.edu.pk', 'DODL', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', '', '', ''),
(10, 6, 'no-reply.dodl@mul.edu.pk', 'DODL', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', '', '', ''),
(11, 6, 'no-reply.dodl@mul.edu.pk', 'DODL', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', '', '', ''),
(12, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(13, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(14, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(15, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(16, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(17, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(18, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(19, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(20, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(21, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(22, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(23, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(24, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(25, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(26, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(27, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(28, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(29, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(30, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', ''),
(31, 7, 'no-reply.dodl@mul.edu.pk', 'DODL', 'hamad.basharat80@gmail.com', 'hamad basharat', 'humzamughal960@gmail.com', 'Hamza Shahzad', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mail_sent_detail`
--
ALTER TABLE `mail_sent_detail`
  ADD PRIMARY KEY (`mail_detail_id`),
  ADD KEY `id_mail` (`id_mail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mail_sent_detail`
--
ALTER TABLE `mail_sent_detail`
  MODIFY `mail_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
