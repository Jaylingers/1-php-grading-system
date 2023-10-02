-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2023 at 04:11 PM
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
-- Table structure for table `grade_info`
--

CREATE TABLE IF NOT EXISTS `grade_info` (
  `id` int(11) NOT NULL,
  `grade` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grade_info`
--

INSERT INTO `grade_info` (`id`, `grade`) VALUES
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `promoted_students`
--

CREATE TABLE IF NOT EXISTS `promoted_students` (
  `id` int(11) NOT NULL,
  `student_lrn` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `promoted_students`
--

INSERT INTO `promoted_students` (`id`, `student_lrn`) VALUES
(6, 'S0000001');

-- --------------------------------------------------------

--
-- Table structure for table `promoted_students_history`
--

CREATE TABLE IF NOT EXISTS `promoted_students_history` (
  `id` int(11) NOT NULL,
  `student_lrn` varchar(50) DEFAULT NULL,
  `grade` int(11) DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `school_years_info`
--

CREATE TABLE IF NOT EXISTS `school_years_info` (
  `id` int(11) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `current` varchar(25) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `school_years_info`
--

INSERT INTO `school_years_info` (`id`, `year`, `current`) VALUES
(1, 2005, 'No'),
(2, 2002, 'No'),
(3, 2023, 'Yes'),
(4, 1904, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `students_enrollment_info`
--

CREATE TABLE IF NOT EXISTS `students_enrollment_info` (
  `id` int(11) NOT NULL,
  `students_info_lrn` varchar(50) NOT NULL,
  `grade` int(50) NOT NULL,
  `school_year` int(25) NOT NULL,
  `date_enrolled` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `grade_status` varchar(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students_enrollment_info`
--

INSERT INTO `students_enrollment_info` (`id`, `students_info_lrn`, `grade`, `school_year`, `date_enrolled`, `status`, `grade_status`) VALUES
(3, 'S0000002', 1, 2024, '2023-08-31', 'transferee', 'failed'),
(4, 'S0000005', 0, 2023, '2023-09-08', 'continuing', ''),
(5, 'S0000006', 3, 2023, '2023-09-30', 'continuing', ''),
(6, 'S0000006', 3, 2023, '2023-09-23', 'transferee', ''),
(7, 'S0000004', 3, 2023, '2023-09-07', 'continuing', '');

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students_grade_info`
--

INSERT INTO `students_grade_info` (`id`, `teacher_lrn`, `student_lrn`, `subject`, `grade_level`, `first_grade`, `second_grade`, `third_grade`, `fourth_grade`, `final`, `status`) VALUES
(14, 'T0000002', 'S0000002', '1', 'Grade 1', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'T0000002', 'S0000002', 'P.E', 'Grade 1', NULL, NULL, NULL, NULL, NULL, NULL);

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
  `guardian_name` varchar(50) NOT NULL,
  `addedBy` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students_info`
--

INSERT INTO `students_info` (`id`, `lrn`, `f_name`, `l_name`, `m_name`, `gender`, `b_date`, `b_place`, `c_status`, `age`, `nationality`, `religion`, `contact_number`, `email_address`, `home_address`, `guardian_name`, `addedBy`) VALUES
(2, 'S0000002', 'Mark', 'Zucker Burg1', '2', 'Male', '2023-09-21', '2', '2', 2, '2', '2', '2', '2@gmail.com', '2', '22', 0),
(3, 'S0000003', '31', '31', '3', '', '2023-09-21', '3', '3', 3, '3', '3', '3', '3@gmail.com', '3', '3', 0),
(4, 'S0000004', '2', '211111', '2', '', '2023-09-27', '2', '2', 2, '2', '2', '2', '2@gmail.com', '2', '22', 0),
(5, 'S0000005', '5', '5', '5', '', '2023-09-08', '5', '5', 5, '5', '5', '5', '5@gmail.com', '5', '5', 1),
(6, 'S0000006', 'zan', 'xan', '1', 'Female', '2023-09-29', '1', '1', 1, '1', '1', '1', '1@gmail', '1', '1', 1);

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
-- Table structure for table `subject_list_info`
--

CREATE TABLE IF NOT EXISTS `subject_list_info` (
  `id` int(11) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `applicable_for` varchar(25) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_list_info`
--

INSERT INTO `subject_list_info` (`id`, `name`, `applicable_for`, `description`) VALUES
(1, 'Math', 'all', ' sad');

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
(1, 'T000001', 'Limpangog, Daisy A.', 'Sudtonggan Basak Lapu-Lapu City', 'Male', 'Widow', 'Daisy@gmail.com', '123', '123');

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
-- Table structure for table `trash_info`
--

CREATE TABLE IF NOT EXISTS `trash_info` (
  `id` int(11) NOT NULL,
  `student_lrn` varchar(25) DEFAULT NULL,
  `name` varchar(25) NOT NULL,
  `history` varchar(9999) DEFAULT NULL,
  `removed_date` date NOT NULL,
  `removed_by` varchar(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trash_info`
--

INSERT INTO `trash_info` (`id`, `student_lrn`, `name`, `history`, `removed_date`, `removed_by`) VALUES
(33, 'S0000001', 'Jay Lingers1', ' <h3> Student Info</h3>id: 1 <br/>lrn: S0000001 <br/>f_name: Jay <br/>l_name: Lingers1 <br/>m_name: M <br/>gender: Male <br/>b_date: 2023-09-21 <br/>b_place: bplace <br/>c_status: Single <br/>age: 31 <br/>nationality: Filipino <br/>religion: catholic <br/>contact_number: 09999999 <br/>email_address: 1@gmail <br/>home_address: Deca 3, Basak LLC <br/>guardian_name: guardian angel <br/>addedBy: 0 <br/> <h3> Student Enrollment Info</h3>id: 1 <br/>students_info_lrn: S0000001 <br/>grade: 11 <br/>school_year: 2026 <br/>date_enrolled: 2023-09-22 <br/>status: new <br/>grade_status: passed <br/>id: 2 <br/>students_info_lrn: S0000001 <br/>grade: 5 <br/>school_year: 2023 <br/>date_enrolled: 2023-09-15 <br/>status: transferee <br/>grade_status: promoted <br/> <h3> Student Grade Info</h3>id: 22 <br/>teacher_lrn: T0000001 <br/>student_lrn: S0000001 <br/>subject: Math <br/>grade_level: Grade 3 <br/>first_grade:  <br/>second_grade:  <br/>third_grade:  <br/>fourth_grade:  <br/>final:  <br/>status:  <br/>id: 23 <br/>teacher_lrn: T0000001 <br/>student_lrn: S0000001 <br/>subject: English <br/>grade_level: Grade 11 <br/>first_grade:  <br/>second_grade:  <br/>third_grade:  <br/>fourth_grade:  <br/>final:  <br/>status:  <br/>', '2023-09-30', 'admin lastname'),
(34, 'S0000001', ' ', ' <h3> Student Info</h3> <h3> Student Enrollment Info</h3> <h3> Student Grade Info</h3>', '2023-09-30', 'admin lastname');

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE IF NOT EXISTS `users_info` (
  `id` int(11) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `grade` int(50) NOT NULL,
  `subject` varchar(25) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  `email` varchar(25) NOT NULL,
  `user_type` varchar(25) NOT NULL,
  `user_lrn` varchar(25) NOT NULL,
  `img_path` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_info`
--

INSERT INTO `users_info` (`id`, `last_name`, `first_name`, `grade`, `subject`, `username`, `password`, `email`, `user_type`, `user_lrn`, `img_path`) VALUES
(1, 'lastname', 'admin', 1, 'Math', 'admin', 'admin', 'jaylingers@gmail.com', 'admin', '', '../../assets/users_img/noImage.png'),
(2, 'lastname', 'student', 1, 'one', 'student', 'student', 'jhon@gmail.com', 'student', '', '../../assets/users_img/noImage.png'),
(3, 'lastname', 'teacher', 2, 'two', 'teacher', 'teacher', '', 'teacher', 'T000001', '../../assets/users_img/T000001.png'),
(4, '2', '2', 1, '2', '2', '2', '', '2', '', ''),
(5, '3', '3', 1, '3', '3', '3', '', '3', '', ''),
(6, '4', '4', 0, '4', '4', '4', '', '4', '', ''),
(7, '5', '5', 0, '5', '5', '5', '', '5', '', ''),
(8, '6', '6', 0, '6', '6', '6', '', '6', '', ''),
(9, '7', '7', 0, '7', '7', '7', '', '7', '', ''),
(10, '8', '8', 0, '8', '8', '8', '', '8', '', ''),
(11, '9', '9', 0, '9', '9', '9', '', '99', '', ''),
(12, '10', '10', 0, '10', '10', '10', '', '10', '', ''),
(13, '', '', 0, '', '', '', '', '', '', ''),
(14, '', '', 0, '', '', '', '', '', '', ''),
(15, '', '', 0, '', '', '', '', '', '', ''),
(16, 'xan', 'zan', 0, '', 'S0000006', 'xan', '', 'student', 'S0000006', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grade_info`
--
ALTER TABLE `grade_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promoted_students`
--
ALTER TABLE `promoted_students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promoted_students_history`
--
ALTER TABLE `promoted_students_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_years_info`
--
ALTER TABLE `school_years_info`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `subject_list_info`
--
ALTER TABLE `subject_list_info`
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
-- Indexes for table `trash_info`
--
ALTER TABLE `trash_info`
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
-- AUTO_INCREMENT for table `grade_info`
--
ALTER TABLE `grade_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `promoted_students`
--
ALTER TABLE `promoted_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `promoted_students_history`
--
ALTER TABLE `promoted_students_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `school_years_info`
--
ALTER TABLE `school_years_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `students_enrollment_info`
--
ALTER TABLE `students_enrollment_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `students_grade_info`
--
ALTER TABLE `students_grade_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `students_info`
--
ALTER TABLE `students_info`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `subject_available_info`
--
ALTER TABLE `subject_available_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `subject_list_info`
--
ALTER TABLE `subject_list_info`
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
-- AUTO_INCREMENT for table `trash_info`
--
ALTER TABLE `trash_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `users_info`
--
ALTER TABLE `users_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
