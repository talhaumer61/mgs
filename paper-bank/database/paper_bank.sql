-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2021 at 02:44 PM
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
-- Table structure for table `sms_classes`
--

CREATE TABLE `sms_classes` (
  `class_id` int(11) NOT NULL,
  `class_status` tinyint(1) NOT NULL DEFAULT 1,
  `class_code` varchar(30) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `id_added` int(11) DEFAULT NULL,
  `id_modify` int(11) DEFAULT NULL,
  `id_deleted` int(11) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modify` datetime DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL,
  `ip_deleted` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sms_classes`
--

INSERT INTO `sms_classes` (`class_id`, `class_status`, `class_code`, `class_name`, `is_deleted`, `id_added`, `id_modify`, `id_deleted`, `date_added`, `date_modify`, `date_deleted`, `ip_deleted`) VALUES
(8, 1, '', 'Class 1', 0, 1, 1, NULL, '2021-08-23 10:59:14', '2021-09-10 08:35:11', NULL, 0),
(9, 1, '', 'Class 2', 1, 1, NULL, NULL, '2021-08-23 10:59:14', NULL, NULL, 0),
(10, 1, '', 'Class 3', 0, 1, 1, NULL, '2021-08-23 10:59:14', '2021-09-10 08:47:39', NULL, 0);

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
-- Dumping data for table `sms_classsubjects`
--

INSERT INTO `sms_classsubjects` (`subject_id`, `subject_status`, `subject_code`, `subject_name`, `subject_totalmarks`, `subject_passmarks`, `subject_type`, `subject_book`, `subject_edition`, `subject_publisher`, `id_cat`, `id_class`, `id_campus`, `is_deleted`, `id_deleted`, `ip_deleted`, `date_deleted`) VALUES
(1, 1, '1', 'Urdu', 0, 0, 2, 'English', 'NA', 'NA', 0, 8, 0, 0, 0, '', '0000-00-00 00:00:00'),
(2, 1, '2', 'Islameyat ', 0, 0, 2, 'Islameyat', 'NA', 'NA', 0, 10, 0, 0, 0, '', '0000-00-00 00:00:00'),
(3, 1, '2', 'eng\r\n', 0, 0, 2, 'Islameyat', 'NA', 'NA', 0, 10, 0, 0, 0, '', '0000-00-00 00:00:00');

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
-- Dumping data for table `sms_examtypes`
--

INSERT INTO `sms_examtypes` (`type_id`, `type_status`, `type_term`, `type_name`, `total_marks`, `pass_marks`, `type_details`, `type_ordering`, `is_deleted`, `id_added`, `id_modify`, `id_deleted`, `ip_deleted`, `date_added`, `date_modify`, `date_deleted`) VALUES
(1, 1, 0, '1st Term 2021', 0, 0, '', 0, 0, 0, 0, 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 1, 0, 'Mid Term 2021', 0, 0, '', 0, 0, 0, 0, 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 1, 0, 'Final Term 2022', 0, 0, '', 0, 0, 0, 0, 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 1, 0, 'Fareed-e-Millat Scholarship Exam', 0, 0, '', 0, 0, 0, 0, 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 1, 0, 'Mothly Test Session (SSC &amp; HSSC)', 0, 0, '', 0, 1, 0, 0, 1, '127.0.0.1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2021-09-11 14:22:37'),
(6, 1, 0, 'Pre Board Exams (SSC &amp; HSSC)', 0, 0, '', 0, 0, 0, 0, 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 1, 0, '2nd Term', 0, 0, '', 0, 0, 1, 1, 0, '', '0000-00-00 00:00:00', '2021-09-11 14:20:21', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `sms_pb_boards`
--

CREATE TABLE `sms_pb_boards` (
  `board_id` int(11) NOT NULL,
  `board_status` tinyint(1) NOT NULL DEFAULT 1,
  `board_name` varchar(100) NOT NULL,
  `id_added` int(11) NOT NULL,
  `id_modify` int(11) DEFAULT NULL,
  `id_deleted` int(11) DEFAULT NULL,
  `ip_deleted` varchar(15) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modify` datetime DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sms_pb_boards`
--

INSERT INTO `sms_pb_boards` (`board_id`, `board_status`, `board_name`, `id_added`, `id_modify`, `id_deleted`, `ip_deleted`, `date_added`, `date_modify`, `date_deleted`) VALUES
(8, 1, 'Board 1', 1, NULL, NULL, NULL, '2021-08-23 11:02:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sms_pb_campus`
--

CREATE TABLE `sms_pb_campus` (
  `campus_id` int(11) NOT NULL,
  `campus_name` varchar(200) NOT NULL,
  `campus_address` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sms_pb_chapters`
--

CREATE TABLE `sms_pb_chapters` (
  `chapter_id` int(11) NOT NULL,
  `id_subject` int(11) NOT NULL,
  `chapter_no` int(11) NOT NULL,
  `chapter_name` varchar(100) NOT NULL,
  `id_added` int(11) NOT NULL,
  `id_modify` int(11) DEFAULT NULL,
  `id_deleted` int(11) DEFAULT NULL,
  `ip_deleted` varchar(15) DEFAULT NULL,
  `date_added` datetime DEFAULT current_timestamp(),
  `date_modify` datetime DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sms_pb_chapters`
--

INSERT INTO `sms_pb_chapters` (`chapter_id`, `id_subject`, `chapter_no`, `chapter_name`, `id_added`, `id_modify`, `id_deleted`, `ip_deleted`, `date_added`, `date_modify`, `date_deleted`) VALUES
(1, 1, 1, 'Chapter(urdu)', 1, 1, NULL, NULL, '2021-09-10 12:57:43', '2021-09-10 09:57:58', NULL),
(2, 1, 2, 'Urdu. Chapter#2', 1, NULL, NULL, NULL, '2021-09-10 12:58:13', NULL, NULL),
(3, 3, 1, 'Eng. Chapter#1', 1, NULL, NULL, NULL, '2021-09-10 12:58:24', NULL, NULL),
(4, 3, 2, 'Eng. Chapter#2', 1, NULL, NULL, NULL, '2021-09-10 12:58:37', NULL, NULL),
(5, 3, 3, 'Eng. Chapter#3', 1, NULL, NULL, NULL, '2021-09-10 12:58:49', NULL, NULL),
(6, 2, 1, 'Isl. Chapter#1', 1, NULL, NULL, NULL, '2021-09-10 13:43:12', NULL, NULL);

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
-- Dumping data for table `sms_pb_objective_answers`
--

INSERT INTO `sms_pb_objective_answers` (`answer_id`, `id_question`, `e_option_a`, `u_option_a`, `e_option_b`, `u_option_b`, `e_option_c`, `u_option_c`, `e_option_d`, `u_option_d`, `e_option_correct`, `u_option_correct`) VALUES
(80, 0, '', 'asd', '', 'asds', '', 'adasa', '', 'mknjkl', '', 'aslkdj'),
(81, 0, '', 'sa', '', 'fas', '', 'faf', '', 'sasdff', '', 'asf'),
(82, 0, 'sa', 'ad', 'das', 'dsa', 'asd', 'sdasd', 'ada', 'sada', 'asd', 'asa'),
(83, 0, 'ass', '', 'hgjh', '', 'hgjh', '', 'jhg', '', 'hjg', ''),
(84, 0, 'z\\', '', 'hjh', '', 'kjh', '', 'kjh', '', 'jh', ''),
(85, 0, 'sad', '', 'jkhk', '', 'jkhk', '', 'kjhk', '', 'jh', ''),
(86, 16, 'hhj', '', 'jhj', '', 'kjjk', '', 'kjkk', '', 'kjkjj', ''),
(87, 17, 'kjhj', '', 'jh', '', 'jhk', '', 'kjh', '', 'kjh', ''),
(88, 18, 'kj', '', 'jkj', '', 'kj', '', 'kj', '', 'kjk', ''),
(89, 19, 'jkh', '', 'J', '', 'hkj', '', 'jh', '', 'jkh', ''),
(90, 20, 'kkj', '', 'KJHJ', '', 'JHK', '', 'JH', '', 'JH', ''),
(91, 57, 'jh', 'k', 'jh', 'jhkj', 'hk', 'h', 'jhk', 'kj', 'jh', 'h'),
(92, 58, 'jh', '', 'ty', '', 'tr', '', 'rr', '', 'yyu', ''),
(93, 73, '', 'asd', '', 'adsadas', '', 'adsdas', '', 'dasdad', '', 'adsdsad'),
(94, 74, '', 'sd', '', 'In perspiciatis ea ', '', 'asd', '', 'Dolor aperiam porro ', '', 'sdd'),
(95, 75, '', 'Qui possimus dolore', '', 'In perspiciatis ea ', '', 'qwe', '', 'Dolor aperiam porro ', '', 'sdd'),
(96, 76, '', 'Qui possimus dolore', '', 'qwe', '', 'sd', '', 'Laboris molestias vo', '', 'asd'),
(97, 77, '', 'Qui possimus dolore', '', 'das', '', 'sd', '', 'asd', '', 'sds'),
(98, 78, '', 'Qui possimus dolore', '', 'In perspiciatis ea ', '', 'sd', '', 'Dolor aperiam porro ', '', 'sds'),
(99, 79, 'sdaf', 'safdsf', 'asdf', 'asdf', 'fasf', 'safasdf', 'sdfas', 'asffsadf', 'fasdfas', 'sfadfasdf'),
(100, 80, 'dsfg', 'fasdf', 'asdf', 'asfd', 'asfd', 'fsafd', 'asfd', 'asdf', 'asfd', 'fsadf'),
(101, 81, 'asdfsad', 'fsad', 'fsadf', 'asfd', 'sdafs', 'asdf', 'sdaf', 'asdf', 'sadf', 'fasdf'),
(102, 82, 'asdsa', 'sadfa', 'sadfsadf', 'asfdfasd', 'sadfsad', 'sdaf', 'fsadf', 'fasdf', 'asdfa', 'sadfas'),
(103, 83, 'dsaf', 'sadf', 'asdf', 'sadf', 'sadf', 'sadf', 'sdaf', 'sdaf', 'sdaf', 'dsfa'),
(104, 84, 'asfdds', 'asfddsa', 'asdff', 'afsd', 'asdfsadf', 'as', 'sfad', 'sadf', 'sdfa', 'sdfsdf');

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
-- Dumping data for table `sms_pb_papers`
--

INSERT INTO `sms_pb_papers` (`paper_id`, `id_subject`, `id_class`, `id_exam_type`, `id_chapter_from`, `id_chapter_to`, `id_paper_style`, `paper_time`, `no_mcqs`, `marks_mcq`, `no_short_question`, `marks_short_question`, `no_long_question`, `marks_long_question`, `no_lines_short_question`, `no_lines_long_question`, `id_added`, `id_modify`, `id_deleted`, `date_added`, `date_modify`, `date_deleted`) VALUES
(1, 3, 10, 3, 3, 4, 2, '100', 5, 1, 5, 2, 3, 5, 2, 5, 1, NULL, NULL, '2021-09-11 18:10:21', NULL, NULL),
(2, 1, 8, 3, 1, 2, 3, '100', 1, 1, 2, 2, 3, 5, 2, 5, 1, NULL, 1, '2021-09-11 20:13:25', NULL, '2021-09-13 07:43:33'),
(3, 1, 8, 2, 1, 2, 3, '100', 5, 1, 5, 2, 3, 5, 3, 5, 1, NULL, NULL, '2021-09-13 10:42:16', NULL, NULL);

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
-- Dumping data for table `sms_pb_paper_questions`
--

INSERT INTO `sms_pb_paper_questions` (`id`, `id_paper`, `id_question`, `date_deleted`) VALUES
(1, 1, 19, NULL),
(2, 1, 18, NULL),
(3, 1, 20, NULL),
(4, 1, 17, NULL),
(5, 1, 16, NULL),
(6, 1, 14, NULL),
(7, 1, 8, NULL),
(8, 1, 4, NULL),
(9, 1, 13, NULL),
(10, 1, 11, NULL),
(11, 1, 22, NULL),
(12, 1, 29, NULL),
(13, 1, 27, NULL),
(14, 2, 58, NULL),
(15, 2, 56, NULL),
(16, 2, 55, NULL),
(17, 2, 52, NULL),
(18, 2, 42, NULL),
(19, 2, 51, NULL),
(20, 3, 75, NULL),
(21, 3, 74, NULL),
(22, 3, 77, NULL),
(23, 3, 73, NULL),
(24, 3, 78, NULL),
(25, 3, 68, NULL),
(26, 3, 71, NULL),
(27, 3, 69, NULL),
(28, 3, 67, NULL),
(29, 3, 72, NULL),
(30, 3, 65, NULL),
(31, 3, 62, NULL),
(32, 3, 64, NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `sms_pb_publishers`
--

CREATE TABLE `sms_pb_publishers` (
  `publisher_id` int(11) NOT NULL,
  `publisher_name` varchar(100) NOT NULL,
  `publisher_status` tinyint(1) NOT NULL DEFAULT 1,
  `id_added` int(11) NOT NULL,
  `id_modify` int(11) DEFAULT NULL,
  `id_deleted` int(11) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modify` datetime DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL,
  `ip_deleted` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sms_pb_publishers`
--

INSERT INTO `sms_pb_publishers` (`publisher_id`, `publisher_name`, `publisher_status`, `id_added`, `id_modify`, `id_deleted`, `date_added`, `date_modify`, `date_deleted`, `ip_deleted`) VALUES
(3, 'Publisher 1', 1, 1, NULL, NULL, '2021-08-23 11:02:31', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sms_pb_questions`
--

CREATE TABLE `sms_pb_questions` (
  `question_id` int(11) NOT NULL,
  `id_board` int(11) NOT NULL,
  `id_publisher` int(11) NOT NULL,
  `id_class` int(11) NOT NULL,
  `id_subject` int(11) NOT NULL,
  `id_topic` int(11) NOT NULL,
  `id_chapter` int(11) NOT NULL,
  `id_question_type` int(11) NOT NULL,
  `page_num` int(11) NOT NULL,
  `question_english` longtext NOT NULL,
  `question_urdu` longtext NOT NULL,
  `id_added` int(11) NOT NULL,
  `id_modify` int(11) DEFAULT NULL,
  `id_deleted` int(11) DEFAULT NULL,
  `ip_deleted` varchar(15) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modify` datetime DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sms_pb_questions`
--

INSERT INTO `sms_pb_questions` (`question_id`, `id_board`, `id_publisher`, `id_class`, `id_subject`, `id_topic`, `id_chapter`, `id_question_type`, `page_num`, `question_english`, `question_urdu`, `id_added`, `id_modify`, `id_deleted`, `ip_deleted`, `date_added`, `date_modify`, `date_deleted`) VALUES
(1, 8, 3, 10, 3, 5, 5, 1, 9, '&lt;span style=&quot;text-align: center;&quot;&gt;Long Question 2 Chapter 3 Subject English Topic Hello World!3&amp;nbsp;&lt;/span&gt;', '', 1, 1, NULL, NULL, '2021-09-10 14:43:54', '2021-09-10 11:47:53', NULL),
(2, 8, 3, 10, 3, 3, 3, 1, 3, '&lt;p&gt;&lt;span style=&quot;text-align: center;&quot;&gt;Long Question 1 Chapter 1 Subject English Topic Hello World!1&lt;/span&gt;&lt;br&gt;&lt;/p&gt;', '', 1, 1, NULL, NULL, '2021-09-10 14:50:02', '2021-09-10 11:51:30', NULL),
(3, 8, 3, 10, 3, 3, 3, 1, 3, '&lt;span style=&quot;text-align: center;&quot;&gt;Long Question 2 Chapter 2 Subject English Topic Hello World!2&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 14:50:25', NULL, NULL),
(4, 8, 3, 10, 3, 3, 3, 2, 2, '&lt;p&gt;Short Question Chapter 1 Subject English&lt;/p&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 14:54:24', NULL, NULL),
(5, 8, 3, 10, 3, 5, 5, 2, 66, '&lt;p&gt;Short Question Chapter 3 Subject English Topic Hello World!3 Update&lt;/p&gt;', '', 1, 1, NULL, NULL, '2021-09-10 15:18:33', '2021-09-10 12:19:07', NULL),
(6, 8, 3, 10, 3, 4, 4, 2, 53, '&lt;p&gt;Short Question 1 Chapter 2 Subject English Topic Hello World!2&amp;nbsp;&lt;/p&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:47:38', NULL, NULL),
(7, 8, 3, 10, 3, 4, 4, 2, 54, '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 2 Chapter 2 Subject English Topic Hello World!2&amp;nbsp;&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:48:12', NULL, NULL),
(8, 8, 3, 10, 3, 4, 4, 2, 55, '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 3 Chapter 2 Subject English Topic Hello World!2&amp;nbsp;&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:48:43', NULL, NULL),
(9, 8, 3, 10, 3, 4, 4, 2, 56, '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 4 Chapter 2 Subject English Topic Hello World!2&amp;nbsp;&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:49:04', NULL, NULL),
(10, 8, 3, 10, 3, 4, 4, 2, 57, '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 5 Chapter 2 Subject English Topic Hello World!2&amp;nbsp;&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:49:17', NULL, NULL),
(11, 8, 3, 10, 3, 4, 4, 2, 58, '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 6 Chapter 2 Subject English Topic Hello World!2&amp;nbsp;&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:49:38', NULL, NULL),
(12, 8, 3, 10, 3, 4, 4, 2, 59, '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 7 Chapter 2 Subject English Topic Hello World!2&amp;nbsp;&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:49:56', NULL, NULL),
(13, 8, 3, 10, 3, 4, 4, 2, 60, '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 8 Chapter 2 Subject English Topic Hello World!2&amp;nbsp;&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:50:21', NULL, NULL),
(14, 8, 3, 10, 3, 4, 4, 2, 61, '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 9 Chapter 2 Subject English Topic Hello World!2&amp;nbsp;&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:50:38', NULL, NULL),
(15, 8, 3, 10, 3, 4, 4, 2, 62, '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 10 Chapter 2 Subject English Topic Hello World!2 Update&lt;/span&gt;', '', 1, 1, NULL, NULL, '2021-09-10 15:50:55', '2021-09-10 12:51:20', NULL),
(16, 8, 3, 10, 3, 4, 4, 3, 34, '&lt;p&gt;MCQs 1 Chapter 1 Subject English&amp;nbsp; Topic Hello World!2&lt;/p&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:56:01', NULL, NULL),
(17, 8, 3, 10, 3, 4, 4, 3, 35, '&lt;span style=&quot;text-align: center;&quot;&gt;MCQs 2 Chapter 1 Subject English&amp;nbsp; Topic Hello World!2&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:56:58', NULL, NULL),
(18, 8, 3, 10, 3, 4, 4, 3, 36, '&lt;span style=&quot;text-align: center;&quot;&gt;MCQs 3 Chapter 1 Subject English&amp;nbsp; Topic Hello World!2&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:57:26', NULL, NULL),
(19, 8, 3, 10, 3, 4, 4, 3, 37, '&lt;span style=&quot;text-align: center;&quot;&gt;MCQs 4 Chapter 1 Subject English&amp;nbsp; Topic Hello World!2&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:57:57', NULL, NULL),
(20, 8, 3, 10, 3, 4, 4, 3, 38, '&lt;span style=&quot;text-align: center;&quot;&gt;MCQs 5 Chapter 1 Subject English&amp;nbsp; Topic Hello World!2&lt;/span&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 15:58:24', NULL, NULL),
(21, 8, 3, 10, 3, 4, 4, 1, 31, '&lt;p&gt;Long Question 1 Chapter 2 Subject English Topic Hello World!2&lt;/p&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 16:04:26', NULL, NULL),
(22, 8, 3, 10, 3, 4, 4, 1, 32, 'Long Question 2 Chapter 2 Subject English Topic Hello World!2', '', 1, NULL, NULL, NULL, '2021-09-10 16:04:35', NULL, NULL),
(23, 8, 3, 10, 3, 4, 4, 1, 33, 'Long Question 3Chapter 2 Subject English Topic Hello World!2', '', 1, NULL, NULL, NULL, '2021-09-10 16:04:49', NULL, NULL),
(24, 8, 3, 10, 3, 4, 4, 1, 34, 'Long Question 4 Chapter 2 Subject English Topic Hello World!2', '', 1, NULL, NULL, NULL, '2021-09-10 16:05:08', NULL, NULL),
(25, 8, 3, 10, 3, 4, 4, 1, 35, 'Long Question 5 Chapter 2 Subject English Topic Hello World!2', '', 1, NULL, NULL, NULL, '2021-09-10 16:05:21', NULL, NULL),
(26, 8, 3, 10, 3, 4, 4, 1, 36, 'Long Question 6 Chapter 2 Subject English Topic Hello World!2', '', 1, NULL, NULL, NULL, '2021-09-10 16:07:42', NULL, NULL),
(27, 8, 3, 10, 3, 4, 4, 1, 37, 'Long Question 7 Chapter 2 Subject English Topic Hello World!2', '', 1, NULL, NULL, NULL, '2021-09-10 16:07:55', NULL, NULL),
(28, 8, 3, 10, 3, 4, 4, 1, 38, 'Long Question 8 Chapter 2 Subject English Topic Hello World!2', '', 1, NULL, NULL, NULL, '2021-09-10 16:08:09', NULL, NULL),
(29, 8, 3, 10, 3, 4, 4, 1, 39, 'Long Question 1 Chapter 2 Subject English Topic Hello World!2', '', 1, NULL, NULL, NULL, '2021-09-10 16:08:18', NULL, NULL),
(30, 8, 3, 10, 3, 4, 4, 1, 40, 'Long Question 10 Chapter 2 Subject English Topic Hello World!2', '', 1, NULL, NULL, NULL, '2021-09-10 16:08:30', NULL, NULL),
(31, 8, 3, 10, 3, 3, 3, 1, 66, '&lt;p&gt;TEsttttttt update&lt;/p&gt;', '', 1, 1, NULL, NULL, '2021-09-10 17:13:39', '2021-09-10 14:14:52', NULL),
(32, 8, 3, 10, 3, 3, 3, 1, 99, '&lt;p&gt;Restttt in new&lt;/p&gt;', '', 1, 1, NULL, NULL, '2021-09-10 17:19:02', '2021-09-10 14:28:00', NULL),
(33, 8, 3, 10, 2, 6, 6, 1, 60, '&lt;p&gt;Welcome New One&amp;nbsp;&amp;nbsp;&lt;/p&gt;', '&lt;p&gt;Welcome New One&amp;nbsp;&lt;/p&gt;', 1, 1, 1, '127.0.0.1', '2021-09-10 17:28:55', '2021-09-13 14:04:57', '2021-09-13 14:08:36'),
(34, 8, 3, 10, 3, 3, 3, 1, 3, '&lt;p&gt;new again&lt;/p&gt;', '', 1, 1, NULL, NULL, '2021-09-10 17:37:40', '2021-09-10 14:37:52', NULL),
(35, 8, 3, 10, 3, 4, 4, 1, 78, '&lt;p&gt;new update&lt;/p&gt;', '', 1, 1, NULL, NULL, '2021-09-10 17:39:58', '2021-09-10 14:40:13', NULL),
(36, 8, 3, 10, 3, 4, 4, 1, 45, '&lt;p&gt;hello new&lt;/p&gt;', '', 1, 1, NULL, NULL, '2021-09-10 17:41:20', '2021-09-10 14:41:30', NULL),
(37, 8, 3, 10, 2, 6, 6, 1, 54, '&lt;p&gt;Hi! Welcome to the Heaven&lt;/p&gt;', '&lt;p&gt;Welcome to the Heaven&lt;/p&gt;', 1, 1, 1, '127.0.0.1', '2021-09-10 17:46:51', '2021-09-13 13:34:33', '2021-09-13 14:09:28'),
(38, 8, 3, 10, 3, 5, 5, 1, 34, '&lt;p&gt;new again&lt;/p&gt;', '', 1, 1, NULL, NULL, '2021-09-10 17:49:40', '2021-09-10 14:49:51', NULL),
(41, 8, 3, 10, 2, 6, 6, 1, 45, '&lt;p&gt;Welcome to the Heaven Update&lt;/p&gt;', '&lt;p&gt;Welcome to the Heaven&amp;nbsp;&amp;nbsp;Update&lt;/p&gt;', 1, 1, 1, '127.0.0.1', '2021-09-10 17:54:45', '2021-09-13 13:31:43', '2021-09-13 14:14:04'),
(44, 8, 3, 10, 3, 4, 4, 1, 0, '', '', 1, 1, NULL, NULL, '2021-09-10 18:12:07', '2021-09-10 15:13:16', NULL),
(45, 8, 3, 10, 3, 5, 5, 1, 33, '&lt;p&gt;hello&lt;/p&gt;', '', 1, 1, NULL, NULL, '2021-09-10 18:13:54', '2021-09-10 15:15:26', NULL),
(46, 8, 3, 10, 2, 6, 6, 1, 5, '&lt;p&gt;retweet1 update&lt;/p&gt;', '&lt;p&gt;retweet1 update&lt;/p&gt;', 1, 1, 1, '127.0.0.1', '2021-09-10 18:16:59', '2021-09-13 12:19:55', '2021-09-13 12:36:18'),
(47, 8, 3, 10, 2, 6, 6, 1, 45, '&lt;p&gt;tweet1 Update&lt;/p&gt;', '&lt;p&gt;tweet1 Update&lt;/p&gt;', 1, 1, 1, '127.0.0.1', '2021-09-10 18:18:39', '2021-09-13 12:20:16', '2021-09-13 12:31:48'),
(48, 8, 3, 10, 3, 4, 4, 1, 34, '&lt;p&gt;hello one&lt;/p&gt;', '', 1, 1, NULL, NULL, '2021-09-10 18:20:52', '2021-09-10 15:21:19', NULL),
(49, 8, 3, 10, 3, 5, 5, 1, 43, '&lt;p&gt;hello new&lt;/p&gt;', '', 1, 1, NULL, NULL, '2021-09-10 18:25:40', '2021-09-10 15:25:59', NULL),
(50, 8, 3, 10, 3, 5, 5, 1, 43, '&lt;p&gt;Tweetsassa&lt;/p&gt;', '', 1, NULL, NULL, NULL, '2021-09-10 18:50:39', NULL, NULL),
(54, 8, 3, 10, 3, 5, 5, 1, 44, '&lt;p&gt;Testt Fiboni Update&lt;/p&gt;', '', 1, 1, NULL, NULL, '2021-09-10 19:06:44', '2021-09-10 16:07:05', NULL),
(57, 8, 3, 10, 2, 6, 6, 3, 77, '&lt;p&gt;Test Update&lt;/p&gt;', '&lt;p&gt;Test Update&lt;/p&gt;', 1, 1, 1, '127.0.0.1', '2021-09-11 14:23:40', '2021-09-13 12:25:01', '2021-09-13 13:10:05'),
(59, 8, 3, 10, 2, 6, 6, 2, 66, '&lt;p&gt;Islamiyaat Short Question&amp;nbsp;&lt;/p&gt;', '&lt;p&gt;Islamiyaat Short Question&amp;nbsp;&lt;/p&gt;', 1, 1, 1, '127.0.0.1', '2021-09-12 17:19:04', '2021-09-13 13:48:00', '2021-09-13 14:38:38'),
(60, 8, 3, 10, 2, 6, 6, 2, 77, 'Islamiyaat Short Question 1', 'Islamiyaat Short Question 1', 1, 1, 1, '127.0.0.1', '2021-09-12 17:19:18', '2021-09-13 13:49:17', '2021-09-13 14:33:00'),
(61, 8, 3, 8, 1, 1, 1, 1, 1, '', 'Long Question 1 Chapter 1 Subject Urdu', 1, NULL, NULL, NULL, '2021-09-13 10:31:56', NULL, NULL),
(62, 8, 3, 8, 1, 1, 1, 1, 2, '', 'Long Question 2 Chapter 1 Subject Urdu', 1, NULL, NULL, NULL, '2021-09-13 10:32:07', NULL, NULL),
(63, 8, 3, 8, 1, 1, 1, 1, 3, '', 'Long Question 3 Chapter 1 Subject Urdu', 1, NULL, NULL, NULL, '2021-09-13 10:32:15', NULL, NULL),
(64, 8, 3, 8, 1, 2, 2, 1, 4, '', 'Long Question 1 Chapter 2 Subject Urdu', 1, NULL, NULL, NULL, '2021-09-13 10:32:34', NULL, NULL),
(65, 8, 3, 8, 1, 2, 2, 1, 5, '', 'Long Question 2 Chapter 2 Subject Urdu', 1, NULL, NULL, NULL, '2021-09-13 10:32:46', NULL, NULL),
(66, 8, 3, 8, 1, 2, 2, 1, 6, '', 'Long Question 3 Chapter 2 Subject Urdu', 1, NULL, NULL, NULL, '2021-09-13 10:32:56', NULL, NULL),
(67, 8, 3, 8, 1, 1, 1, 2, 1, '', '&lt;p&gt;Short Question 1 Chapter 1 Subject Urdu&lt;br&gt;&lt;/p&gt;', 1, NULL, NULL, NULL, '2021-09-13 10:34:12', NULL, NULL),
(68, 8, 3, 8, 1, 1, 1, 2, 2, '', 'Short Question 2 Chapter 1 Subject Urdu', 1, NULL, NULL, NULL, '2021-09-13 10:34:28', NULL, NULL),
(69, 8, 3, 8, 1, 1, 1, 2, 3, '', '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 3 Chapter 1 Subject Urdu&lt;/span&gt;', 1, NULL, NULL, NULL, '2021-09-13 10:34:47', NULL, NULL),
(70, 8, 3, 8, 1, 2, 2, 2, 3, '', '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 1 Chapter 2 Subject Urdu&lt;/span&gt;', 1, NULL, NULL, NULL, '2021-09-13 10:35:04', NULL, NULL),
(71, 8, 3, 8, 1, 2, 2, 2, 6, '', '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 2 Chapter 2 Subject Urdu&lt;/span&gt;', 1, NULL, NULL, NULL, '2021-09-13 10:35:14', NULL, NULL),
(72, 8, 3, 8, 1, 2, 2, 2, 7, '', '&lt;span style=&quot;text-align: center;&quot;&gt;Short Question 3 Chapter 2 Subject Urdu&lt;/span&gt;', 1, NULL, NULL, NULL, '2021-09-13 10:35:26', NULL, NULL),
(73, 8, 3, 8, 1, 1, 1, 3, 5, '', '&lt;p&gt;MCQS Chapter 1 Subject Urdu&lt;/p&gt;', 1, 1, NULL, NULL, '2021-09-13 10:37:16', '2021-09-13 12:43:17', NULL),
(74, 8, 3, 8, 1, 1, 1, 3, 3, '', '&lt;span style=&quot;text-align: center;&quot;&gt;MCQ Question 2 Chapter 1 Subject Urdu&lt;/span&gt;', 1, NULL, NULL, NULL, '2021-09-13 10:37:42', NULL, NULL),
(75, 8, 3, 8, 1, 1, 1, 3, 4, '', '&lt;span style=&quot;text-align: center;&quot;&gt;MCQ Question 3 Chapter 1 Subject Urdu&lt;/span&gt;', 1, NULL, NULL, NULL, '2021-09-13 10:38:05', NULL, NULL),
(76, 8, 3, 8, 1, 2, 2, 3, 6, '', '&lt;span style=&quot;text-align: center;&quot;&gt;MCQ Question 1 Chapter 2 Subject Urdu&lt;/span&gt;', 1, NULL, NULL, NULL, '2021-09-13 10:38:40', NULL, NULL),
(77, 8, 3, 8, 1, 2, 2, 3, 9, '', '&lt;span style=&quot;text-align: center;&quot;&gt;MCQ Question 2 Chapter 2 Subject Urdu&lt;/span&gt;', 1, NULL, NULL, NULL, '2021-09-13 10:39:08', NULL, NULL),
(78, 8, 3, 8, 1, 2, 2, 3, 11, '', '&lt;span style=&quot;text-align: center;&quot;&gt;MCQ Question 3 Chapter 2 Subject Urdu&lt;/span&gt;', 1, NULL, NULL, NULL, '2021-09-13 10:39:46', NULL, NULL),
(79, 8, 3, 10, 2, 6, 6, 3, 33, '&lt;p&gt;MCQ 1 Chapter 1 Subject Islamiyaat Ipdate&lt;/p&gt;', '&lt;p&gt;MCQ 1 Chapter 1 Subject Islamiyaat Update&lt;/p&gt;', 1, 1, 1, '127.0.0.1', '2021-09-13 16:11:08', '2021-09-13 13:16:59', '2021-09-13 14:38:44'),
(80, 8, 3, 10, 2, 6, 6, 3, 63, 'MCQ 2 Chapter 1 Subject Islamiyaat', 'MCQ 2 Chapter 1 Subject Islamiyaat', 1, NULL, 1, '127.0.0.1', '2021-09-13 16:11:33', NULL, '2021-09-13 14:37:36'),
(81, 8, 3, 10, 2, 6, 6, 3, 66, 'MCQ 3 Chapter 1 Subject Islamiyaat', 'MCQ 3 Chapter 1 Subject Islamiyaat', 1, NULL, 1, '127.0.0.1', '2021-09-13 16:11:57', NULL, '2021-09-13 14:30:00'),
(82, 0, 3, 10, 2, 6, 6, 3, 3, '&lt;p&gt;MCQS 1 Chapter 1 Subject Urdu&lt;/p&gt;', '&lt;p&gt;MCQS 1 Chapter 1 Subject Urdu&lt;br&gt;&lt;/p&gt;', 1, NULL, NULL, NULL, '2021-09-13 17:39:41', NULL, NULL),
(83, 8, 3, 10, 2, 6, 6, 3, 4, 'MCQS 2 Chapter 1 Subject Urdu', 'MCQS 2 Chapter 1 Subject Urdu', 1, NULL, NULL, NULL, '2021-09-13 17:40:15', NULL, NULL),
(84, 8, 3, 10, 2, 6, 6, 3, 5, 'MCQS 3 Chapter 1 Subject Urdu', 'MCQS 3 Chapter 1 Subject Urdu', 1, NULL, NULL, NULL, '2021-09-13 17:40:38', NULL, NULL),
(85, 8, 3, 10, 2, 6, 6, 1, 4, '&lt;p&gt;Long Question 1 Chapter 2 Subject Urdu&lt;br&gt;&lt;/p&gt;', '&lt;p&gt;Long Question 1 Chapter 2 Subject Urdu&lt;br&gt;&lt;/p&gt;', 1, NULL, NULL, NULL, '2021-09-13 17:41:19', NULL, NULL),
(86, 8, 3, 10, 2, 6, 6, 1, 5, 'Long Question 2 Chapter 2 Subject Urdu', 'Long Question 2 Chapter 2 Subject Urdu', 1, NULL, NULL, NULL, '2021-09-13 17:41:34', NULL, NULL),
(87, 8, 3, 10, 2, 6, 6, 1, 6, 'Long Question 3 Chapter 2 Subject Urdu', 'Long Question 3 Chapter 2 Subject Urdu', 1, NULL, NULL, NULL, '2021-09-13 17:41:45', NULL, NULL),
(88, 8, 3, 10, 2, 6, 6, 2, 4, '&lt;p&gt;Short Question 1 Chapter 1 Subject Islamiyaat&lt;br&gt;&lt;/p&gt;', '&lt;p&gt;Short Question 1 Chapter 1 Subject Islamiyaat&lt;br&gt;&lt;/p&gt;', 1, NULL, NULL, NULL, '2021-09-13 17:42:39', NULL, NULL),
(89, 8, 3, 10, 2, 6, 6, 2, 5, 'Short Question 2 Chapter 1 Subject Islamiyaat', 'Short Question 2 Chapter 1 Subject Islamiyaat', 1, NULL, NULL, NULL, '2021-09-13 17:42:54', NULL, NULL),
(90, 8, 3, 10, 2, 6, 6, 2, 6, 'Short Question 3 Chapter 1 Subject Islamiyaat', 'Short Question 3&amp;nbsp; Chapter 1 Subject Islamiyaat', 1, NULL, NULL, NULL, '2021-09-13 17:43:09', NULL, NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `sms_pb_teachers`
--

CREATE TABLE `sms_pb_teachers` (
  `teacher_id` int(11) NOT NULL,
  `teacher_name` varchar(100) NOT NULL,
  `email` varchar(70) NOT NULL,
  `teacher_cnic` varchar(13) NOT NULL,
  `dob` date NOT NULL,
  `phone` varchar(11) NOT NULL,
  `salary` int(11) NOT NULL,
  `gender` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sms_pb_topics`
--

CREATE TABLE `sms_pb_topics` (
  `topic_id` int(11) NOT NULL,
  `id_subject` int(11) NOT NULL,
  `id_class` int(11) NOT NULL,
  `id_chapter` int(11) NOT NULL,
  `topic_name` varchar(200) NOT NULL,
  `topic_status` tinyint(1) NOT NULL DEFAULT 1,
  `id_added` int(11) NOT NULL,
  `id_modify` int(11) DEFAULT NULL,
  `id_deleted` int(11) DEFAULT NULL,
  `ip_deleted` int(11) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modify` int(11) DEFAULT NULL,
  `date_deleted` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sms_pb_topics`
--

INSERT INTO `sms_pb_topics` (`topic_id`, `id_subject`, `id_class`, `id_chapter`, `topic_name`, `topic_status`, `id_added`, `id_modify`, `id_deleted`, `ip_deleted`, `date_added`, `date_modify`, `date_deleted`) VALUES
(1, 1, 8, 1, 'ہیلو ورلڈ!1', 1, 1, NULL, NULL, NULL, '2021-09-10 13:20:37', NULL, NULL),
(2, 1, 8, 2, 'ہیلو ورلڈ!2', 1, 1, NULL, NULL, NULL, '2021-09-10 13:20:54', NULL, NULL),
(3, 3, 10, 3, 'Hello World!1', 1, 1, NULL, NULL, NULL, '2021-09-10 13:21:10', NULL, NULL),
(4, 3, 10, 4, 'Hello World!2', 1, 1, NULL, NULL, NULL, '2021-09-10 13:21:22', NULL, NULL),
(5, 3, 10, 5, 'Hello World!3', 1, 1, NULL, NULL, NULL, '2021-09-10 13:21:37', NULL, NULL),
(6, 2, 10, 6, 'Islamiyat #1', 1, 1, NULL, NULL, NULL, '2021-09-10 13:43:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sms_pb_users`
--

CREATE TABLE `sms_pb_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sms_pb_users`
--

INSERT INTO `sms_pb_users` (`user_id`, `username`, `password`, `created_at`, `status`, `employee_id`) VALUES
(1, 'admin', '$2y$12$ptgaNwRnXxRRqgRaGIuM2.WN6fqV5pOYtSLWRzqTznWZmFDY2Sb1y', '2021-07-30 04:51:17', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sms_classes`
--
ALTER TABLE `sms_classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `sms_classsubjects`
--
ALTER TABLE `sms_classsubjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `sms_examtypes`
--
ALTER TABLE `sms_examtypes`
  ADD PRIMARY KEY (`type_id`);
ALTER TABLE `sms_examtypes` ADD FULLTEXT KEY `cat_name` (`type_name`);

--
-- Indexes for table `sms_pb_boards`
--
ALTER TABLE `sms_pb_boards`
  ADD PRIMARY KEY (`board_id`);

--
-- Indexes for table `sms_pb_campus`
--
ALTER TABLE `sms_pb_campus`
  ADD PRIMARY KEY (`campus_id`);

--
-- Indexes for table `sms_pb_chapters`
--
ALTER TABLE `sms_pb_chapters`
  ADD PRIMARY KEY (`chapter_id`);

--
-- Indexes for table `sms_pb_objective_answers`
--
ALTER TABLE `sms_pb_objective_answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `objective_belongs_to_question` (`id_question`);

--
-- Indexes for table `sms_pb_papers`
--
ALTER TABLE `sms_pb_papers`
  ADD PRIMARY KEY (`paper_id`);

--
-- Indexes for table `sms_pb_paper_questions`
--
ALTER TABLE `sms_pb_paper_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paper_question_belong_to_question` (`id_paper`);

--
-- Indexes for table `sms_pb_paper_style`
--
ALTER TABLE `sms_pb_paper_style`
  ADD PRIMARY KEY (`paper_style_id`);

--
-- Indexes for table `sms_pb_publishers`
--
ALTER TABLE `sms_pb_publishers`
  ADD PRIMARY KEY (`publisher_id`);

--
-- Indexes for table `sms_pb_questions`
--
ALTER TABLE `sms_pb_questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `sms_pb_question_type`
--
ALTER TABLE `sms_pb_question_type`
  ADD PRIMARY KEY (`question_type_id`);

--
-- Indexes for table `sms_pb_teachers`
--
ALTER TABLE `sms_pb_teachers`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `sms_pb_topics`
--
ALTER TABLE `sms_pb_topics`
  ADD PRIMARY KEY (`topic_id`);

--
-- Indexes for table `sms_pb_users`
--
ALTER TABLE `sms_pb_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sms_classes`
--
ALTER TABLE `sms_classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sms_classsubjects`
--
ALTER TABLE `sms_classsubjects`
  MODIFY `subject_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sms_examtypes`
--
ALTER TABLE `sms_examtypes`
  MODIFY `type_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sms_pb_boards`
--
ALTER TABLE `sms_pb_boards`
  MODIFY `board_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sms_pb_campus`
--
ALTER TABLE `sms_pb_campus`
  MODIFY `campus_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_pb_chapters`
--
ALTER TABLE `sms_pb_chapters`
  MODIFY `chapter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sms_pb_objective_answers`
--
ALTER TABLE `sms_pb_objective_answers`
  MODIFY `answer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `sms_pb_papers`
--
ALTER TABLE `sms_pb_papers`
  MODIFY `paper_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sms_pb_paper_questions`
--
ALTER TABLE `sms_pb_paper_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `sms_pb_paper_style`
--
ALTER TABLE `sms_pb_paper_style`
  MODIFY `paper_style_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sms_pb_publishers`
--
ALTER TABLE `sms_pb_publishers`
  MODIFY `publisher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sms_pb_questions`
--
ALTER TABLE `sms_pb_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `sms_pb_question_type`
--
ALTER TABLE `sms_pb_question_type`
  MODIFY `question_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sms_pb_teachers`
--
ALTER TABLE `sms_pb_teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_pb_topics`
--
ALTER TABLE `sms_pb_topics`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sms_pb_users`
--
ALTER TABLE `sms_pb_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
