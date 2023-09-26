-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2023 at 10:29 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `php_grading_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `students_enrollment_info`
--

CREATE TABLE IF NOT EXISTS `students_enrollment_info` (
  `id` int(11) NOT NULL,
  `students_info_lrn` varchar(50) NOT NULL,
  `grade` varchar(50) NOT NULL,
  `school_year` date NOT NULL,
  `date_enrolled` date NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students_enrollment_info`
--

INSERT INTO `students_enrollment_info` (`id`, `students_info_lrn`, `grade`, `school_year`, `date_enrolled`, `status`) VALUES
(1, 'S0000001', 'Grade 11', '2023-09-21', '2023-09-22', 'new'),
(2, 'S0000001', 'Grade 5', '2023-09-21', '2023-09-15', 'transferee'),
(3, 'S0000002', 'Grade 1', '2023-09-16', '2023-08-31', 'transferee');

-- --------------------------------------------------------

--
-- Table structure for table `students_grade_info`
--

CREATE TABLE IF NOT EXISTS `students_grade_info` (
  `id` int(11) NOT NULL,
  `teacher_lrn` varchar(25) NOT NULL,
  `student_lrn` varchar(25) DEFAULT NULL,
  `subject` varchar(25) NOT NULL,
  `grade_level` varchar(25) NOT NULL,
  `first_grade` int(11) DEFAULT NULL,
  `second_grade` int(11) DEFAULT NULL,
  `third_grade` int(11) DEFAULT NULL,
  `fourth_grade` int(11) DEFAULT NULL,
  `final` int(11) DEFAULT NULL,
  `status` varchar(25) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students_grade_info`
--

INSERT INTO `students_grade_info` (`id`, `teacher_lrn`, `student_lrn`, `subject`, `grade_level`, `first_grade`, `second_grade`, `third_grade`, `fourth_grade`, `final`, `status`) VALUES
(14, 'T0000002', 'S0000002', '1', 'Grade 1', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'T0000002', 'S0000002', 'P.E', 'Grade 1', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'T0000001', 'S0000001', 'Math', 'Grade 11', NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'T0000001', 'S0000001', 'English', 'Grade 11', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students_info`
--

CREATE TABLE IF NOT EXISTS `students_info` (
  `id` int(4) NOT NULL,
  `lrn` varchar(255) NOT NULL,
  `f_name` varchar(50) NOT NULL,
  `l_name` varchar(50) NOT NULL,
  `m_name` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `b_date` date NOT NULL,
  `b_place` varchar(50) NOT NULL,
  `c_status` varchar(50) NOT NULL,
  `age` int(3) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `home_address` varchar(50) NOT NULL,
  `guardian_name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students_info`
--

INSERT INTO `students_info` (`id`, `lrn`, `f_name`, `l_name`, `m_name`, `gender`, `b_date`, `b_place`, `c_status`, `age`, `nationality`, `religion`, `contact_number`, `email_address`, `home_address`, `guardian_name`) VALUES
(1, 'S0000001', 'Jay', 'Lingers', 'M', 'Male', '2023-09-21', 'bplace', 'Single', 31, 'Filipino', 'catholic', '09999999', '1@gmail', 'Deca 3, Basak LLC', 'guardian angel'),
(2, 'S0000002', 'Mark', 'Zucker Burg', '2', 'Male', '2023-09-21', '2', '2', 2, '2', '2', '2', '2@gmail.com', '2', '22'),
(3, 'S0000003', '3', '3', '3', '', '2023-09-21', '3', '3', 3, '3', '3', '3', '3@gmail.com', '3', '3');

-- --------------------------------------------------------

--
-- Table structure for table `subject_available_info`
--

CREATE TABLE IF NOT EXISTS `subject_available_info` (
  `id` int(11) NOT NULL,
  `grade_level` varchar(25) DEFAULT NULL,
  `subject` varchar(25) DEFAULT NULL,
  `available_number` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_available_info`
--

INSERT INTO `subject_available_info` (`id`, `grade_level`, `subject`, `available_number`) VALUES
(1, 'Grade 1', 'SCIENCE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `teachers_info`
--

CREATE TABLE IF NOT EXISTS `teachers_info` (
  `id` int(11) NOT NULL,
  `lrn` varchar(25) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `civil_status` varchar(25) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password` varchar(25) NOT NULL,
  `re_type_password` varchar(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers_info`
--

INSERT INTO `teachers_info` (`id`, `lrn`, `fullname`, `address`, `gender`, `civil_status`, `email_address`, `password`, `re_type_password`) VALUES
(1, 'T0000001', 'Limpangog, Daisy A.', 'Sudtonggan Basak Lapu-Lapu City', 'Male', 'Widow', 'Daisy@gmail.com', '123', '123');

-- --------------------------------------------------------

--
-- Table structure for table `teachers_subject_info`
--

CREATE TABLE IF NOT EXISTS `teachers_subject_info` (
  `id` int(11) NOT NULL,
  `teachers_info_lrn` varchar(25) NOT NULL,
  `subject` varchar(25) NOT NULL,
  `room` varchar(25) NOT NULL,
  `grade_level` varchar(25) NOT NULL,
  `schedule_time_in` time NOT NULL,
  `schedule_time_out` time NOT NULL,
  `schedule_day` varchar(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers_subject_info`
--

INSERT INTO `teachers_subject_info` (`id`, `teachers_info_lrn`, `subject`, `room`, `grade_level`, `schedule_time_in`, `schedule_time_out`, `schedule_day`) VALUES
(8, 'T0000001 ', 'Math', '11', 'Grade 11', '01:00:00', '21:15:00', 'MWF'),
(11, 'T0000001', 'Filipino', 'room1', 'Grade 5', '22:02:00', '21:03:00', 'SAT'),
(12, 'T0000001', 'English', 'Room 401', 'Grade 11', '19:17:00', '07:17:00', 'SUN');

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE IF NOT EXISTS `users_info` (
  `id` int(11) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `grade` varchar(25) NOT NULL,
  `subject` varchar(25) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  `email` varchar(25) NOT NULL,
  `user_type` varchar(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_info`
--

INSERT INTO `users_info` (`id`, `last_name`, `first_name`, `grade`, `subject`, `username`, `password`, `email`, `user_type`) VALUES
(1, 'lingers', 'jay', 'Grade 10', 'Math', 'jaylingers', '123', 'jaylingers@gmail.com', 'admin'),
(2, '', '', '', '', 'jhon', '123', 'jhon@gmail.com', ''),
(3, '1', '1', '', '1', '1', '1', '', '1'),
(4, '2', '2', 'Grade 1', '2', '2', '2', '', '2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students_enrollment_info`
--
ALTER TABLE `students_enrollment_info`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `students_grade_info`
--
ALTER TABLE `students_grade_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students_info`
--
ALTER TABLE `students_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_available_info`
--
ALTER TABLE `subject_available_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers_info`
--
ALTER TABLE `teachers_info`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `teachers_subject_info`
--
ALTER TABLE `teachers_subject_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_info`
--
ALTER TABLE `users_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students_enrollment_info`
--
ALTER TABLE `students_enrollment_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `students_grade_info`
--
ALTER TABLE `students_grade_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `students_info`
--
ALTER TABLE `students_info`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `subject_available_info`
--
ALTER TABLE `subject_available_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `teachers_info`
--
ALTER TABLE `teachers_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `teachers_subject_info`
--
ALTER TABLE `teachers_subject_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `users_info`
--
ALTER TABLE `users_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
