-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2025 at 07:02 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `odbc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `id` int(11) NOT NULL,
  `AccountID` varchar(20) NOT NULL,
  `yearlevel` int(11) NOT NULL,
  `semester` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`id`, `AccountID`, `yearlevel`, `semester`, `date_created`) VALUES
(155, '25-0102', 4, '1st Semester', '2025-01-17 08:37:42'),
(156, '25-0103', 4, '1st Semester', '2025-01-17 08:48:57'),
(159, '25-01-6341', 1, '1st Semester', '2025-01-18 02:52:56'),
(160, '25-01-3583', 1, '1st Semester', '2025-01-18 02:55:08'),
(161, '25-01-5665', 1, '2nd Semester', '2025-01-18 02:58:30'),
(162, '25-01-9283', 4, '2nd Semester', '2025-01-18 15:15:02'),
(163, '25-01-3183', 0, '', '2025-01-18 16:48:06');

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `gradeid` int(11) NOT NULL,
  `AccountID` varchar(25) NOT NULL,
  `InstructorID` text NOT NULL,
  `instructorName` text NOT NULL,
  `code` varchar(10) NOT NULL,
  `units` int(5) NOT NULL,
  `quiz1` double NOT NULL,
  `quiz2` double NOT NULL,
  `quiz3` double NOT NULL,
  `prelim` double NOT NULL,
  `midterm` double NOT NULL,
  `finals` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`gradeid`, `AccountID`, `InstructorID`, `instructorName`, `code`, `units`, `quiz1`, `quiz2`, `quiz3`, `prelim`, `midterm`, `finals`) VALUES
(119, '25-0102', 'I25-0319', 'James L. Castro', 'CS 411', 3, 10, 98, 100, 100, 100, 100),
(120, '25-0102', 'I25-0605', 'Angela R. Santos', 'CS 412', 1, 85, 89, 93, 84, 87, 85),
(121, '25-0102', 'I25-0140', 'Nathan P. Fernandez', 'CS 413', 3, 0, 0, 0, 0, 0, 0),
(122, '25-0102', 'I25-0128', 'Liam J. Ramirez', 'CS 414', 3, 0, 0, 0, 0, 0, 0),
(123, '25-0103', 'I25-0319', 'James L. Castro', 'CS 411', 3, 98, 31, 87, 31, 99, 97),
(124, '25-0103', 'I25-0605', 'Angela R. Santos', 'CS 412', 1, 0, 0, 0, 0, 0, 0),
(125, '25-0103', 'I25-0140', 'Nathan P. Fernandez', 'CS 413', 3, 0, 0, 0, 0, 0, 0),
(126, '25-0103', 'I25-0128', 'Liam J. Ramirez', 'CS 414', 3, 0, 0, 0, 0, 0, 0),
(136, '25-01-6341', 'I25-0140', 'Nathan P. Fernandez', 'CS 111', 3, 0, 0, 0, 0, 0, 0),
(137, '25-01-6341', 'I25-1218', 'Grace L. Navarro', 'CS 112', 3, 0, 0, 0, 0, 0, 0),
(138, '25-01-6341', 'I25-0443', 'Olivia R. Dela Cruz', 'CS INST 1', 3, 0, 0, 0, 0, 0, 0),
(139, '25-01-6341', 'I25-1215', 'Eric J. Fernandez', 'GE ELEC CS', 3, 0, 0, 0, 0, 0, 0),
(140, '25-01-6341', 'I25-0920', 'James S. Bautista', 'GE ELEC CS', 3, 0, 0, 0, 0, 0, 0),
(141, '25-01-6341', 'I25-0924', 'Jonathan V. Ramirez', 'GEC 3', 3, 0, 0, 0, 0, 0, 0),
(142, '25-01-6341', 'I25-0256', 'Sophia T. Morales', 'GEC 4', 3, 0, 0, 0, 0, 0, 0),
(143, '25-01-6341', 'I25-0204', 'Angela D. Ramirez', 'NSTP 1', 3, 0, 0, 0, 0, 0, 0),
(144, '25-01-6341', 'I25-0127', 'Leo J. Mendoza', 'PE 1', 0, 78, 76, 89, 75, 89, 90),
(145, '25-01-3583', 'I25-0140', 'Nathan P. Fernandez', 'CS 111', 3, 0, 0, 0, 0, 0, 0),
(146, '25-01-3583', 'I25-1218', 'Grace L. Navarro', 'CS 112', 3, 0, 0, 0, 0, 0, 0),
(147, '25-01-3583', 'I25-0443', 'Olivia R. Dela Cruz', 'CS INST 1', 3, 0, 0, 0, 0, 0, 0),
(148, '25-01-3583', 'I25-1215', 'Eric J. Fernandez', 'GE ELEC CS', 3, 0, 0, 0, 0, 0, 0),
(149, '25-01-3583', 'I25-0920', 'James S. Bautista', 'GE ELEC CS', 3, 0, 0, 0, 0, 0, 0),
(150, '25-01-3583', 'I25-0924', 'Jonathan V. Ramirez', 'GEC 3', 3, 0, 0, 0, 0, 0, 0),
(151, '25-01-3583', 'I25-0256', 'Sophia T. Morales', 'GEC 4', 3, 0, 0, 0, 0, 0, 0),
(152, '25-01-3583', 'I25-0204', 'Angela D. Ramirez', 'NSTP 1', 3, 0, 0, 0, 0, 0, 0),
(153, '25-01-3583', 'I25-0127', 'Leo J. Mendoza', 'PE 1', 0, 87, 97, 98, 67, 65, 90),
(154, '25-01-5665', 'I25-0127', 'Leo J. Mendoza', 'CS 121', 3, 87, 89, 57, 90, 95, 92),
(155, '25-01-5665', 'I25-0341', 'Nathan P. Santiago', 'CS 122', 3, 0, 0, 0, 0, 0, 0),
(156, '25-01-5665', 'I25-0103', 'd d d', 'GE ELEC CS', 3, 0, 0, 0, 0, 0, 0),
(157, '25-01-5665', 'I25-0341', 'Nathan P. Santiago', 'GEC 1', 3, 0, 0, 0, 0, 0, 0),
(158, '25-01-5665', 'I25-1145', 'Patrick E. Cruz', 'GEC 2', 3, 0, 0, 0, 0, 0, 0),
(159, '25-01-5665', 'I25-1215', 'Eric J. Fernandez', 'GEC 5', 3, 0, 0, 0, 0, 0, 0),
(160, '25-01-5665', 'I25-0329', 'Lily A. Mendoza', 'NSTP 2', 3, 0, 0, 0, 0, 0, 0),
(161, '25-01-5665', 'I25-0319', 'James L. Castro', 'PE 2', 3, 86, 96, 56, 88, 90, 96),
(162, '25-01-3183', 'I25-0140', 'Nathan P. Fernandez', 'CS 111', 3, 0, 0, 0, 0, 0, 0),
(163, '25-01-3183', 'I25-1218', 'Grace L. Navarro', 'CS 112', 3, 0, 0, 0, 0, 0, 0),
(164, '25-01-3183', 'I25-0127', 'Leo J. Mendoza', 'CS 121', 3, 0, 0, 0, 0, 0, 0),
(165, '25-01-3183', 'I25-0341', 'Nathan P. Santiago', 'CS 122', 3, 0, 0, 0, 0, 0, 0),
(166, '25-01-3183', 'I25-1145', 'Patrick E. Cruz', 'CS 211', 3, 0, 0, 0, 0, 0, 0),
(167, '25-01-3183', 'I25-0924', 'Jonathan V. Ramirez', 'CS 212', 3, 0, 0, 0, 0, 0, 0),
(168, '25-01-3183', 'I25-0256', 'Sophia T. Morales', 'CS 213', 3, 0, 0, 0, 0, 0, 0),
(169, '25-01-3183', 'I25-0326', 'Kevin L. Bautista', 'CS 214', 3, 0, 0, 0, 0, 0, 0),
(170, '25-01-3183', 'I25-0140', 'Nathan P. Fernandez', 'CS 221', 3, 0, 0, 0, 0, 0, 0),
(171, '25-01-3183', 'I25-0605', 'Angela R. Santos', 'CS 222', 3, 0, 0, 0, 0, 0, 0),
(172, '25-01-3183', 'I25-0212', 'Daniel S. Fernandez', 'CS 311', 3, 0, 0, 0, 0, 0, 0),
(173, '25-01-3183', 'I25-0235', 'Mark A. Villanueva', 'CS 312', 3, 0, 0, 0, 0, 0, 0),
(174, '25-01-3183', 'I25-0204', 'Angela D. Ramirez', 'CS 313', 3, 0, 0, 0, 0, 0, 0),
(175, '25-01-3183', 'I25-0923', 'Reynaldo R. Corpuz', 'CS 314', 3, 0, 0, 0, 0, 0, 0),
(176, '25-01-3183', 'I25-0128', 'Liam J. Ramirez', 'CS 315', 3, 0, 0, 0, 0, 0, 0),
(177, '25-01-3183', 'I25-0443', 'Olivia R. Dela Cruz', 'CS 321', 3, 0, 0, 0, 0, 0, 0),
(178, '25-01-3183', 'I25-0923', 'Reynaldo R. Corpuz', 'CS 322', 3, 0, 0, 0, 0, 0, 0),
(179, '25-01-3183', 'I25-0920', 'James S. Bautista', 'CS 323', 3, 0, 0, 0, 0, 0, 0),
(180, '25-01-3183', 'I25-0329', 'Lily A. Mendoza', 'CS 324', 3, 0, 0, 0, 0, 0, 0),
(181, '25-01-3183', 'I25-0319', 'James L. Castro', 'CS 411', 3, 0, 0, 0, 0, 0, 0),
(182, '25-01-3183', 'I25-0605', 'Angela R. Santos', 'CS 412', 1, 0, 0, 0, 0, 0, 0),
(183, '25-01-3183', 'I25-0140', 'Nathan P. Fernandez', 'CS 413', 3, 0, 0, 0, 0, 0, 0),
(184, '25-01-3183', 'I25-0128', 'Liam J. Ramirez', 'CS 414', 3, 0, 0, 0, 0, 0, 0),
(185, '25-01-3183', 'I25-0255', 'Sophia T. Mendoza', 'CS DM 1', 3, 0, 0, 0, 0, 0, 0),
(186, '25-01-3183', 'I25-0326', 'Kevin L. Bautista', 'CS DM 2', 3, 0, 0, 0, 0, 0, 0),
(187, '25-01-3183', 'I25-0605', 'Angela R. Santos', 'CS DM 3', 3, 0, 0, 0, 0, 0, 0),
(188, '25-01-3183', 'I25-0212', 'Daniel S. Fernandez', 'CS DM 4', 3, 0, 0, 0, 0, 0, 0),
(189, '25-01-3183', 'I25-0212', 'Daniel S. Fernandez', 'CS DM 5', 3, 0, 0, 0, 0, 0, 0),
(190, '25-01-3183', 'I25-0447', 'Patrick T. Mendoza', 'CS DM 6', 3, 0, 0, 0, 0, 0, 0),
(191, '25-01-3183', 'I25-0447', 'Patrick T. Mendoza', 'CS ELEC 1', 3, 0, 0, 0, 0, 0, 0),
(192, '25-01-3183', 'I25-0447', 'Patrick T. Mendoza', 'CS ELEC 2', 3, 0, 0, 0, 0, 0, 0),
(193, '25-01-3183', 'I25-0204', 'Angela D. Ramirez', 'CS ELEC 3', 3, 0, 0, 0, 0, 0, 0),
(194, '25-01-3183', 'I25-0204', 'Angela D. Ramirez', 'CS GE ELEC', 3, 0, 0, 0, 0, 0, 0),
(195, '25-01-3183', 'I25-0204', 'Angela D. Ramirez', 'CS GE ELEC', 3, 0, 0, 0, 0, 0, 0),
(196, '25-01-3183', 'I25-0103', 'd d d', 'CS GE ELEC', 3, 0, 0, 0, 0, 0, 0),
(197, '25-01-3183', 'I25-0326', 'Kevin L. Bautista', 'CS GE ELEC', 3, 0, 0, 0, 0, 0, 0),
(198, '25-01-3183', 'I25-0443', 'Olivia R. Dela Cruz', 'CS INST 1', 3, 0, 0, 0, 0, 0, 0),
(199, '25-01-3183', 'I25-0235', 'Mark A. Villanueva', 'CS INST 2', 3, 0, 0, 0, 0, 0, 0),
(200, '25-01-3183', 'I25-1215', 'Eric J. Fernandez', 'GE ELEC CS', 3, 0, 0, 0, 0, 0, 0),
(201, '25-01-3183', 'I25-0920', 'James S. Bautista', 'GE ELEC CS', 3, 0, 0, 0, 0, 0, 0),
(202, '25-01-3183', 'I25-0103', 'd d d', 'GE ELEC CS', 3, 0, 0, 0, 0, 0, 0),
(203, '25-01-3183', 'I25-0341', 'Nathan P. Santiago', 'GEC 1', 3, 0, 0, 0, 0, 0, 0),
(204, '25-01-3183', 'I25-1145', 'Patrick E. Cruz', 'GEC 2', 3, 0, 0, 0, 0, 0, 0),
(205, '25-01-3183', 'I25-0924', 'Jonathan V. Ramirez', 'GEC 3', 3, 0, 0, 0, 0, 0, 0),
(206, '25-01-3183', 'I25-0256', 'Sophia T. Morales', 'GEC 4', 3, 0, 0, 0, 0, 0, 0),
(207, '25-01-3183', 'I25-1215', 'Eric J. Fernandez', 'GEC 5', 3, 0, 0, 0, 0, 0, 0),
(208, '25-01-3183', 'I25-0127', 'Leo J. Mendoza', 'GEC 6', 3, 0, 0, 0, 0, 0, 0),
(209, '25-01-3183', 'I25-1217', 'Ethan R. Morales', 'GEC 7', 3, 0, 0, 0, 0, 0, 0),
(210, '25-01-3183', 'I25-1217', 'Ethan R. Morales', 'GEC 8', 3, 0, 0, 0, 0, 0, 0),
(211, '25-01-3183', 'I25-0103', 'd d d', 'GEC 9', 3, 0, 0, 0, 0, 0, 0),
(212, '25-01-3183', 'I25-0204', 'Angela D. Ramirez', 'NSTP 1', 3, 0, 0, 0, 0, 0, 0),
(213, '25-01-3183', 'I25-0329', 'Lily A. Mendoza', 'NSTP 2', 3, 0, 0, 0, 0, 0, 0),
(214, '25-01-3183', 'I25-0127', 'Leo J. Mendoza', 'PE 1', 0, 0, 0, 0, 0, 0, 0),
(215, '25-01-3183', 'I25-0319', 'James L. Castro', 'PE 2', 3, 0, 0, 0, 0, 0, 0),
(216, '25-01-3183', 'I25-0319', 'James L. Castro', 'PE 3', 3, 0, 0, 0, 0, 0, 0),
(217, '25-01-3183', 'I25-0923', 'Reynaldo R. Corpuz', 'PE 4', 3, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `loginaccount`
--

CREATE TABLE `loginaccount` (
  `AccountID` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `UserType` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `token_expiry` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loginaccount`
--

INSERT INTO `loginaccount` (`AccountID`, `password`, `UserType`, `token`, `token_expiry`) VALUES
('I25-0103', 'dgKQ0a', '', '', '0000-00-00'),
('I25-1218', 'kncWaC', '', '', '0000-00-00'),
('I25-0127', '7pdVUk', '', '', '0000-00-00'),
('I25-0341', '7AL48J', '', '', '0000-00-00'),
('I25-1145', 'ympfhK', '', '', '0000-00-00'),
('I25-0924', 'NmYHau', '', '', '0000-00-00'),
('I25-0256', 'dIQ62j', '', '', '0000-00-00'),
('I25-0326', 'Pg2xXg', '', '', '0000-00-00'),
('I25-1215', 'bqERPV', '', '', '0000-00-00'),
('I25-0140', 'A7yMhG', '', '', '0000-00-00'),
('I25-0605', '4OciYS', '', '', '0000-00-00'),
('I25-0212', 'UsnBKz', '', '', '0000-00-00'),
('I25-0235', 'mmPV97', '', '', '0000-00-00'),
('I25-0447', 'yJWWsg', '', '', '0000-00-00'),
('I25-0204', 'crJnv2', '', '', '0000-00-00'),
('I25-0923', 'IBtkt4', '', '', '0000-00-00'),
('I25-0128', 'WGYpJu', '', '', '0000-00-00'),
('I25-0443', 'SPWFTS', '', '', '0000-00-00'),
('I25-0255', 'j7Gn0V', '', '', '0000-00-00'),
('I25-0920', 'NBeYcG', '', '', '0000-00-00'),
('I25-0329', 'Jc01tG', '', '', '0000-00-00'),
('I25-1217', 'WbHRBR', '', '', '0000-00-00'),
('I25-0319', 'W5kJ8a', '', '', '0000-00-00'),
('I25-0251', 'LorhkV', '', '', '0000-00-00'),
('I25-1131', '6qnEdK', '', '', '0000-00-00'),
('I25-0321', 'YcIR8D', '', '', '0000-00-00'),
('25-0102', 'jqsVoU', 'student', '', '0000-00-00'),
('25-0103', 'ctAAEW', 'student', '', '0000-00-00'),
('25-01-7264', '1lZ4dO', 'student', '', '0000-00-00'),
('25-01-6341', 'JZdpCE', 'student', '', '0000-00-00'),
('25-01-6341', 'JZdpCE', 'student', '', '0000-00-00'),
('25-01-3583', '123456', 'student', '05a3d3ba019bf9fe84a47f30', '2025-01-19'),
('25-01-3583', '123456', 'student', '05a3d3ba019bf9fe84a47f30', '2025-01-19'),
('25-01-5665', '0yVA5Q', 'student', '', '0000-00-00'),
('25-01-9283', '5L8rrX', 'student', '', '0000-00-00'),
('25-01-3183', 'qslgki', 'student', '', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `personaldata`
--

CREATE TABLE `personaldata` (
  `id` int(11) NOT NULL,
  `AccountID` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_initial` varchar(5) DEFAULT NULL,
  `surname` varchar(50) NOT NULL,
  `birthdate` text NOT NULL,
  `age` int(11) NOT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `civil_status` varchar(20) DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `mother_contact` varchar(20) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `father_contact` varchar(20) DEFAULT NULL,
  `student_contact` varchar(20) DEFAULT NULL,
  `gmail` varchar(100) DEFAULT NULL,
  `home_address` text DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `userType` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personaldata`
--

INSERT INTO `personaldata` (`id`, `AccountID`, `first_name`, `middle_initial`, `surname`, `birthdate`, `age`, `blood_type`, `civil_status`, `religion`, `mother_name`, `mother_contact`, `father_name`, `father_contact`, `student_contact`, `gmail`, `home_address`, `present_address`, `userType`, `date_created`) VALUES
(105, 'I25-0103', 'd', 'd', 'd', '0000-00-00', 0, 'd', 'd', 'd', 'd', 'd', 'd', 'd', 'd', 'd', 'd', 'd', 'instructor', '2025-01-15 12:45:17'),
(106, 'I25-1218', 'Grace', 'L.', 'Navarro', '1999-05-16', 25, 'O+', 'Single', 'Christian', 'Maria Navarro', '09123456789', 'Antonio Navarro', '09123456789', '09123456789', 'grace.navarro@gmail.com', '123 Home St.', '456 Present St.', 'instructor', '2025-01-15 14:11:44'),
(107, 'I25-0127', 'Leo', 'J.', 'Mendoza', '1998-07-22', 26, 'A-', 'Married', 'Catholic', 'Liza Mendoza', '09123456789', 'Ricardo Mendoza', '09123456789', '09123456789', 'leo.mendoza@gmail.com', '789 Home St.', '101 Present St.', 'instructor', '2025-01-15 14:11:44'),
(108, 'I25-0341', 'Nathan', 'P.', 'Santiago', '2000-11-13', 24, 'B+', 'Single', 'Muslim', 'Maria Santiago', '09123456789', 'Jose Santiago', '09123456789', '09123456789', 'nathan.santiago@gmail.com', '234 Home St.', '567 Present St.', 'instructor', '2025-01-15 14:11:44'),
(109, 'I25-1145', 'Patrick', 'E.', 'Cruz', '1998-03-25', 26, 'AB-', 'Single', 'Christian', 'Anna Cruz', '09123456789', 'Juan Cruz', '09123456789', '09123456789', 'patrick.cruz@gmail.com', '345 Home St.', '678 Present St.', 'instructor', '2025-01-15 14:11:44'),
(110, 'I25-0924', 'Jonathan', 'V.', 'Ramirez', '1997-12-30', 27, 'O+', 'Married', 'Catholic', 'Margarita Ramirez', '09123456789', 'Luis Ramirez', '09123456789', '09123456789', 'jonathan.ramirez@gmail.com', '456 Home St.', '789 Present St.', 'instructor', '2025-01-15 14:11:44'),
(111, 'I25-0256', 'Sophia', 'T.', 'Morales', '1999-01-17', 25, 'B-', 'Single', 'Christian', 'Dina Morales', '09123456789', 'Carlos Morales', '09123456789', '09123456789', 'sophia.morales@gmail.com', '567 Home St.', '890 Present St.', 'instructor', '2025-01-15 14:11:44'),
(112, 'I25-0326', 'Kevin', 'L.', 'Bautista', '1997-10-05', 27, 'O-', 'Single', 'Christian', 'Marissa Bautista', '09123456789', 'Felix Bautista', '09123456789', '09123456789', 'kevin.bautista@gmail.com', '678 Home St.', '901 Present St.', 'instructor', '2025-01-15 14:11:44'),
(113, 'I25-1215', 'Eric', 'J.', 'Fernandez', '1998-02-18', 26, 'A+', 'Single', 'Catholic', 'Isabel Fernandez', '09123456789', 'Eduardo Fernandez', '09123456789', '09123456789', 'eric.fernandez@gmail.com', '789 Home St.', '102 Present St.', 'instructor', '2025-01-15 14:11:44'),
(114, 'I25-0140', 'Nathan', 'P.', 'Fernandez', '1999-09-08', 25, 'B+', 'Married', 'Christian', 'Veronica Fernandez', '09123456789', 'Roberto Fernandez', '09123456789', '09123456789', 'nathan.fernandez@gmail.com', '890 Home St.', '203 Present St.', 'instructor', '2025-01-15 14:11:44'),
(115, 'I25-0605', 'Angela', 'R.', 'Santos', '1998-06-12', 26, 'AB+', 'Single', 'Catholic', 'Felicia Santos', '09123456789', 'Ricardo Santos', '09123456789', '09123456789', 'angela.santos@gmail.com', '901 Home St.', '304 Present St.', 'instructor', '2025-01-15 14:11:44'),
(116, 'I25-0212', 'Daniel', 'S.', 'Fernandez', '1997-05-22', 27, 'O+', 'Single', 'Catholic', 'Cristina Fernandez', '09123456789', 'Carlos Fernandez', '09123456789', '09123456789', 'daniel.fernandez@gmail.com', '102 Home St.', '205 Present St.', 'instructor', '2025-01-15 14:11:44'),
(117, 'I25-0235', 'Mark', 'A.', 'Villanueva', '1998-12-13', 26, 'A-', 'Single', 'Christian', 'Lucia Villanueva', '09123456789', 'Felipe Villanueva', '09123456789', '09123456789', 'mark.villanueva@gmail.com', '203 Home St.', '306 Present St.', 'instructor', '2025-01-15 14:11:44'),
(118, 'I25-0447', 'Patrick', 'T.', 'Mendoza', '1999-04-17', 25, 'O+', 'Single', 'Christian', 'Florence Mendoza', '09123456789', 'Ricardo Mendoza', '09123456789', '09123456789', 'patrick.mendoza@gmail.com', '304 Home St.', '407 Present St.', 'instructor', '2025-01-15 14:11:44'),
(119, 'I25-0204', 'Angela', 'D.', 'Ramirez', '2000-02-28', 24, 'B+', 'Single', 'Catholic', 'Veronica Ramirez', '09123456789', 'Felipe Ramirez', '09123456789', '09123456789', 'angela.ramirez@gmail.com', '405 Home St.', '508 Present St.', 'instructor', '2025-01-15 14:11:44'),
(120, 'I25-0923', 'Reynaldo', 'R.', 'Corpuz', '1998-08-05', 26, 'AB-', 'Single', 'Christian', 'Elena Cruz', '09123456789', 'Victor Cruz', '09123456789', '09123456789', 'jonathan.cruz@gmail.com', '506 Home St.', '609 Present St.', 'instructor', '2025-01-15 14:11:44'),
(121, 'I25-0128', 'Liam', 'J.', 'Ramirez', '1997-10-19', 27, 'O-', 'Single', 'Catholic', 'Beatrice Ramirez', '09123456789', 'Ricardo Ramirez', '09123456789', '09123456789', 'liam.ramirez@gmail.com', '607 Home St.', '710 Present St.', 'instructor', '2025-01-15 14:11:44'),
(122, 'I25-0443', 'Olivia', 'R.', 'Dela Cruz', '1999-05-16', 25, 'A+', 'Married', 'Catholic', 'Paula Dela Cruz', '09123456789', 'Emilio Dela Cruz', '09123456789', '09123456789', 'olivia.delacruz@gmail.com', '708 Home St.', '811 Present St.', 'instructor', '2025-01-15 14:11:44'),
(123, 'I25-0255', 'Sophia', 'T.', 'Mendoza', '2000-03-11', 24, 'B-', 'Single', 'Christian', 'Maria Mendoza', '09123456789', 'Juan Mendoza', '09123456789', '09123456789', 'sophia.mendoza@gmail.com', '809 Home St.', '912 Present St.', 'instructor', '2025-01-15 14:11:44'),
(124, 'I25-0920', 'James', 'S.', 'Bautista', '1997-11-23', 27, 'A-', 'Single', 'Christian', 'Lucia Bautista', '09123456789', 'Oscar Bautista', '09123456789', '09123456789', 'james.bautista@gmail.com', '910 Home St.', '101 Present St.', 'instructor', '2025-01-15 14:11:44'),
(125, 'I25-0329', 'Lily', 'A.', 'Mendoza', '1999-02-19', 25, 'O-', 'Single', 'Catholic', 'Teresa Mendoza', '09123456789', 'Carlos Mendoza', '09123456789', '09123456789', 'lily.mendoza@gmail.com', '1011 Home St.', '1112 Present St.', 'instructor', '2025-01-15 14:11:44'),
(126, 'I25-1217', 'Ethan', 'R.', 'Morales', '1998-09-15', 26, 'AB-', 'Single', 'Christian', 'Gina Morales', '09123456789', 'Hector Morales', '09123456789', '09123456789', 'ethan.morales@gmail.com', '1113 Home St.', '1214 Present St.', 'instructor', '2025-01-15 14:11:44'),
(127, 'I25-0319', 'James', 'L.', 'Castro', '1997-06-11', 27, 'O+', 'Married', 'Catholic', 'Roberta Castro', '09123456789', 'Ernesto Castro', '09123456789', '09123456789', 'james.castro@gmail.com', '1215 Home St.', '1316 Present St.', 'instructor', '2025-01-15 14:11:44'),
(128, 'I25-0251', 'Sophia', 'C.', 'Cruz', '1998-01-29', 26, 'B+', 'Single', 'Christian', 'Angela Cruz', '09123456789', 'Gerald Cruz', '09123456789', '09123456789', 'sophia.cruz@gmail.com', '1317 Home St.', '1418 Present St.', 'instructor', '2025-01-15 14:11:44'),
(129, 'I25-1131', 'Maria', 'D.', 'Bautista', '1999-11-09', 25, 'AB+', 'Single', 'Catholic', 'Rosa Bautista', '09123456789', 'Alejandro Bautista', '09123456789', '09123456789', 'maria.bautista@gmail.com', '1419 Home St.', '1510 Present St.', 'instructor', '2025-01-15 14:11:44'),
(130, 'I25-0321', 'John', 'P.', 'Martinez', '1998-04-26', 26, 'O+', 'Married', 'Christian', 'Sonia Martinez', '09123456789', 'Alfredo Martinez', '09123456789', '09123456789', 'john.martinez@gmail.com', '1511 Home St.', '1612 Present St.', 'instructor', '2025-01-15 14:11:44'),
(180, '25-0102', 'Frank Ellis', 'Avila', 'Gines', '0000-00-00', 0, 'iuy', 'single', 'uiy', 'iu', 'yiu', 'yiu', 'yu', 'iy', 'iuy', 'iu', 'yi', 'student', '2025-01-17 08:37:42'),
(181, '25-0103', 'Kyla Mae', 'P.', 'Segundo', '0000-00-00', 0, 'j', 'j', 'j', 'j', 'j', 'j', 'j', 'j', 'jj', 'j', 'j', 'student', '2025-01-17 08:48:57'),
(184, '25-01-6341', 'Benz Yvan Ross', 'P', 'Visaya', '0000-00-00', 0, 'j', 'j', 'j', 'j', 'j', 'j', 'j', 'j', 'j', 'j', 'j', 'student', '2025-01-18 02:52:56'),
(185, '25-01-3583', 'Sharon', 'A.', 'Portales', '06/25/2030', 0, 'f', 'f', 'i', 'u', 'h', 'h', 'h', 'h', 'aguinaldogwyneth69@gmail.com', 'h', 'g', 'student', '2025-01-18 02:55:08'),
(186, '25-01-5665', 'Mark Josep', 'B.', 'Orino', '0000-00-00', 0, 'k', 'k', 'k', 'k', 'k', 'k', 'k', 'kk', 'k', 'k', 'k', 'student', '2025-01-18 02:58:30'),
(187, '25-01-9283', 'Rosenda', 'R.', 'Soriano', '0000-00-00', 0, 'h', 'h', 'j', 'j', 'j', 'j', 'j', 'k', 'o', 'o', 'o', 'student', '2025-01-18 15:15:02'),
(188, '25-01-6156', 'Roxas', 'H.', 'Sampaguita', '0000-00-00', 0, 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'student', '2025-01-18 16:44:31'),
(189, '25-01-4455', 'Roxas', 'H.', 'Sampaguita', '0000-00-00', 0, 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'student', '2025-01-18 16:46:30'),
(190, '25-01-2296', 'Roxas', 'H.', 'Sampaguita', '0000-00-00', 0, 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'student', '2025-01-18 16:47:37'),
(191, '25-01-3183', 'Roxas', 'H.', 'Sampaguita', '0000-00-00', 0, 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 'student', '2025-01-18 16:48:06');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `code` varchar(255) NOT NULL,
  `subjectname` text NOT NULL,
  `units` int(5) NOT NULL,
  `yearlevel` int(2) NOT NULL,
  `semester` text NOT NULL,
  `InstructorID` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`code`, `subjectname`, `units`, `yearlevel`, `semester`, `InstructorID`) VALUES
('CS 111', 'Introduction to Computing', 3, 1, '1st Semester', 'I25-0140'),
('CS 112', 'Fundamentals of Programming', 3, 1, '1st Semester', 'I25-1218'),
('CS 121', 'Discrete Structures', 3, 1, '2nd Semester', 'I25-0127'),
('CS 122', 'Intermediate Programming', 3, 1, '2nd Semester', 'I25-0341'),
('CS 211', 'Discrete Structures 2', 3, 2, '1st Semester', 'I25-1145'),
('CS 212', 'Object-Oriented Programming', 3, 2, '1st Semester', 'I25-0924'),
('CS 213', 'Data Structures and Algorithms', 3, 2, '1st Semester', 'I25-0256'),
('CS 214', 'Calculus with Analytic Geometry', 3, 2, '1st Semester', 'I25-0326'),
('CS 221', 'Algorithms and Complexity', 3, 2, '2nd Semester', 'I25-0140'),
('CS 222', 'Information Management', 3, 2, '2nd Semester', 'I25-0605'),
('CS 311', 'Automata Theory and Formal Languages', 3, 3, '1st Semester', 'I25-0212'),
('CS 312', 'Architecture and Organization', 3, 3, '1st Semester', 'I25-0235'),
('CS 313', 'Information Assurance and Security', 3, 3, '1st Semester', 'I25-0204'),
('CS 314', 'Software Engineering 1', 3, 3, '1st Semester', 'I25-0923'),
('CS 315', 'Social Issues and Professional Practice', 3, 3, '1st Semester', 'I25-0128'),
('CS 321', 'CS Thesis Writing 1', 3, 3, '2nd Semester', 'I25-0443'),
('CS 322', 'Software Engineering 2', 3, 3, '2nd Semester', 'I25-0923'),
('CS 323', 'Application Development and Emerging Technologies', 3, 3, '2nd Semester', 'I25-0920'),
('CS 324', 'Programming Languages', 3, 3, '2nd Semester', 'I25-0329'),
('CS 411', 'CS Thesis Writing 2', 3, 4, '1st Semester', 'I25-0319'),
('CS 412', 'Human Computer Interaction', 1, 4, '1st Semester', 'I25-0605'),
('CS 413', 'Networks and Communications', 3, 4, '1st Semester', 'I25-0140'),
('CS 414', 'Operating Systems', 3, 4, '1st Semester', 'I25-0128'),
('CS DM 1', 'Statistical Methods for Data Analysis and Inference', 3, 2, '2nd Semester', 'I25-0255'),
('CS DM 2', 'Data Preparation and Analysis', 3, 2, '2nd Semester', 'I25-0326'),
('CS DM 3', 'Data Mining Techniques and Tools', 3, 3, '1st Semester', 'I25-0605'),
('CS DM 4', 'Data Mining Applications', 3, 3, '1st Semester', 'I25-0212'),
('CS DM 5', 'Algorithms for Data Mining', 3, 3, '2nd Semester', 'I25-0212'),
('CS DM 6', 'Data Mining Methodology', 3, 3, '2nd Semester', 'I25-0447'),
('CS ELEC 1', 'CS Elective 1', 3, 2, '1st Semester', 'I25-0447'),
('CS ELEC 2', 'CS Elective 2', 3, 3, '2nd Semester', 'I25-0447'),
('CS ELEC 3', 'CS Elective 3', 3, 3, 'Midyear', 'I25-0204'),
('CS GE ELEC 1', 'The Entrepreneurial Mind', 3, 2, '2nd Semester', 'I25-0204'),
('CS GE ELEC 2', 'Multi-cultural Education', 3, 3, 'Midyear', 'I25-0204'),
('CS GE ELEC 3', 'Leadership and Management in the Profession', 3, 3, 'Midyear', 'I25-0103'),
('CS GE ELEC 4', 'Technical Writing', 3, 2, '2nd Semester', 'I25-0326'),
('CS INST 1', 'Climate Change and Disaster Risk Management', 3, 1, '1st Semester', 'I25-0443'),
('CS INST 2', 'Creative and Critical Thinking', 3, 2, '1st Semester', 'I25-0235'),
('GE ELEC CS 1', 'HEALTH AND WELLNESS SCIENCE', 3, 1, '1st Semester', 'I25-1215'),
('GE ELEC CS 2', 'GENDER AND SOCIETY', 3, 1, '1st Semester', 'I25-0920'),
('GE ELEC CS 3', 'Foreign Language', 3, 1, '2nd Semester', 'I25-0103'),
('GEC 1', 'Understanding the Self', 3, 1, '2nd Semester', 'I25-0341'),
('GEC 2', 'Readings in the Philippine History', 3, 1, '2nd Semester', 'I25-1145'),
('GEC 3', 'Mathematics in the Modern World', 3, 1, '1st Semester', 'I25-0924'),
('GEC 4', 'Purposive Communication', 3, 1, '1st Semester', 'I25-0256'),
('GEC 5', 'Art Appreciation', 3, 1, '2nd Semester', 'I25-1215'),
('GEC 6', 'Science, Technology and Society', 3, 2, '1st Semester', 'I25-0127'),
('GEC 7', 'Ethics', 3, 2, '2nd Semester', 'I25-1217'),
('GEC 8', 'The Contemporary World', 3, 2, '1st Semester', 'I25-1217'),
('GEC 9', 'The Life and Works of Rizal', 3, 2, '2nd Semester', 'I25-0103'),
('NSTP 1', 'CWTS/LTS/MS 1', 3, 1, '1st Semester', 'I25-0204'),
('NSTP 2', 'Civic Welfare Training Services 2', 3, 1, '2nd Semester', 'I25-0329'),
('PE 1', 'Physical Activity Towards Health Fitness I', 0, 1, '1st Semester', 'I25-0127'),
('PE 2', 'Physical Activity Towards Health Fitness II (Exercise Program)', 3, 1, '2nd Semester', 'I25-0319'),
('PE 3', 'Physical Activity Towards Health Fitness III', 3, 2, '1st Semester', 'I25-0319'),
('PE 4', 'Physical Activity Towards Health Fitness IV', 3, 2, '2nd Semester', 'I25-0923');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`gradeid`);

--
-- Indexes for table `personaldata`
--
ALTER TABLE `personaldata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`code`),
  ADD UNIQUE KEY `unique_instructor_subject` (`code`,`InstructorID`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `gradeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `personaldata`
--
ALTER TABLE `personaldata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
