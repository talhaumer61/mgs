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
-- Table structure for table `mail_sent`
--

CREATE TABLE `mail_sent` (
  `mail_id` int(11) NOT NULL,
  `mail_status` int(11) NOT NULL DEFAULT 1,
  `is_sent` varchar(10) NOT NULL,
  `is_read_by_adm` tinyint(4) NOT NULL DEFAULT 0,
  `mail_slug` text NOT NULL,
  `mail_subject` text NOT NULL,
  `mail_body` longtext NOT NULL,
  `mail_custom_body` longtext NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modify` datetime NOT NULL,
  `date_deleted` datetime NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `ip_added` varchar(30) NOT NULL,
  `ip_modify` varchar(30) NOT NULL,
  `ip_deleted` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mail_sent`
--

INSERT INTO `mail_sent` (`mail_id`, `mail_status`, `is_sent`, `is_read_by_adm`, `mail_slug`, `mail_subject`, `mail_body`, `mail_custom_body`, `date_added`, `date_modify`, `date_deleted`, `is_deleted`, `ip_added`, `ip_modify`, `ip_deleted`) VALUES
(1, 1, 'sent', 1, 'testing-mailer-1', 'Testing Mailer 66cc6d5ba64fa', '\n                    <thead class=\"table-light\">\n                        <tr style=\"vertical-align: middle;\">\n                            <th width=\"40\" class=\"text-center\">Sr.</th>\n                            <th width=\"300\">Sender/Reciver</th>\n                            <th width=\"300\">Subject</th>\n                            <th>Body</th>\n                            <th width=\"200\">Dated</th>\n                            <th width=\"110\">Sent Or Not</th>\n                            <th width=\"70\" class=\"text-center\">Status</th>\n                            <th width=\"80\" class=\"text-center\">Action</th>\n                        </tr>\n                    </thead>', '', '2024-08-26 16:56:12', '2024-08-27 12:59:06', '0000-00-00 00:00:00', 0, '203.130.19.226', '::1', ''),
(2, 1, 'sent', 1, 'testing-mailer-2', 'Testing Mailer 66cc6d938ca4a', '66cc6d938ca51', '', '2024-08-26 16:57:07', '2024-08-27 12:58:59', '0000-00-00 00:00:00', 0, '203.130.19.226', '::1', ''),
(3, 1, 'sent', 1, 'testing-mailer-3', 'Testing Mailer 66cc6d956ae1c', '66cc6d956ae21', '', '2024-08-26 16:57:09', '2024-08-27 12:57:07', '0000-00-00 00:00:00', 0, '203.130.19.226', '::1', ''),
(4, 1, 'sent', 1, 'testing-mailer-4', 'Testing Mailer 66cc6d9724ef9', '66cc6d9724efe', '', '2024-08-26 16:57:11', '2024-08-27 12:57:05', '0000-00-00 00:00:00', 0, '203.130.19.226', '::1', ''),
(5, 1, 'sent', 1, 'testing-mailer-5', 'Testing Mailer 66cc6f2b14707', '66cc6f2b14713', '', '2024-08-26 17:03:55', '2024-08-27 12:57:00', '0000-00-00 00:00:00', 0, '203.130.19.226', '::1', ''),
(6, 1, 'sent', 1, 'testing-mailer-6', 'Testing Mailer 66cc6f5a7f678', '66cc6f5a7f67e', '', '2024-08-26 17:04:42', '2024-08-27 12:56:53', '0000-00-00 00:00:00', 0, '203.130.19.226', '::1', ''),
(7, 1, 'sent', 1, 'testing-mailer-7', 'Testing Mailer 66cc70110a622', '<p class=\"fs-14 fw-semibold mb-2\">Hi, Json Taylor Greetings 🖐</p>             <p class=\"mb-2 fs-12 text-muted\">Earth, our home, is the third planet from the sun.  While scientists continue to hunt for clues of life beyond Earth,is the third planet from the sun.  While scientists continue to hunt for clues of life beyond Earth, our home planet remains the only place in the universe where weve ever identified living organisms. .</p>             <p class=\"mb-2 fs-12 text-muted\">Earth has a diameter of roughly 8,000 miles (13,000 kilometers) and is mostly round because gravity generally pulls matter into a ball. But the spin of our home planet causes it to be squashed at its poles and swollen at the equator, making the true shape of the Earth an \"oblate spheroidEarth has a diameter of roughly 8,000 miles (13,000 kilometers) and is mostly round because gravity generally pulls matter into a ball. But the spin of our home planet causes it to be squashed at its poles and swollen at the equator,                  making the true shape of the Earth an \"oblate spheroid.\".</p>             <p class=\"mb-0 mt-4\">                 <span class=\"d-block\">Regards,</span>                 <span class=\"d-block\">Michael Jeremy</span>             </p>', '<p class=\"fs-14 fw-semibold mb-2\">Hi, Json Taylor Greetings 🖐</p>             <p class=\"mb-2 fs-12 text-muted\">Earth, our home, is the third planet from the sun.  While scientists continue to hunt for clues of life beyond Earth,is the third planet from the sun.  While scientists continue to hunt for clues of life beyond Earth, our home planet remains the only place in the universe where weve ever identified living organisms. .</p>             <p class=\"mb-2 fs-12 text-muted\">Earth has a diameter of roughly 8,000 miles (13,000 kilometers) and is mostly round because gravity generally pulls matter into a ball. But the spin of our home planet causes it to be squashed at its poles and swollen at the equator, making the true shape of the Earth an \"oblate spheroidEarth has a diameter of roughly 8,000 miles (13,000 kilometers) and is mostly round because gravity generally pulls matter into a ball. But the spin of our home planet causes it to be squashed at its poles and swollen at the equator,                  making the true shape of the Earth an \"oblate spheroid.\".</p>             <p class=\"mb-0 mt-4\">                 <span class=\"d-block\">Regards,</span>                 <span class=\"d-block\">Michael Jeremy</span>             </p>', '2024-08-26 17:07:45', '2024-08-27 13:28:58', '0000-00-00 00:00:00', 0, '203.130.19.226', '::1', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mail_sent`
--
ALTER TABLE `mail_sent`
  ADD PRIMARY KEY (`mail_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mail_sent`
--
ALTER TABLE `mail_sent`
  MODIFY `mail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
