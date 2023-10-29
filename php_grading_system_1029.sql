-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2023 at 02:27 AM
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
  `grade` int(11) DEFAULT NULL,
  `section` varchar(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grade_info`
--

INSERT INTO `grade_info` (`id`, `grade`, `section`) VALUES
(1, 1, 'section a'),
(2, 1, 'section b'),
(3, 2, 'section c');

-- --------------------------------------------------------

--
-- Table structure for table `page_visited_info`
--

CREATE TABLE IF NOT EXISTS `page_visited_info` (
  `id` int(11) NOT NULL,
  `user_id` int(25) DEFAULT NULL,
  `date_visited` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `page_visited_info`
--

INSERT INTO `page_visited_info` (`id`, `user_id`, `date_visited`) VALUES
(1, 1, '2023-10-24 20:34:29'),
(2, 3, '2023-10-24 20:36:34'),
(3, 3, '2023-10-24 20:36:44'),
(4, 1, '2023-10-24 20:46:42'),
(5, 3, '2023-10-24 20:46:56'),
(6, 3, '2023-10-24 20:47:28'),
(7, 3, '2023-10-24 21:13:45'),
(8, 3, '2023-10-24 21:15:39'),
(9, 3, '2023-10-24 21:15:55'),
(10, 2, '2023-10-24 22:01:55'),
(11, 2, '2023-10-25 20:24:35'),
(12, 2, '2023-10-25 22:56:31'),
(13, 2, '2023-10-26 05:46:36'),
(14, 2, '2023-10-26 07:08:17'),
(15, 2, '2023-10-26 07:14:57'),
(16, 2, '2023-10-26 13:45:54'),
(17, 2, '2023-10-26 18:46:01'),
(18, 1, '2023-10-26 18:52:21'),
(19, 1, '2023-10-26 18:57:25'),
(20, 1, '2023-10-26 19:49:46'),
(21, 4, '2023-10-26 19:53:06'),
(22, 2, '2023-10-26 19:54:34'),
(23, 1, '2023-10-26 20:10:27'),
(24, 4, '2023-10-26 20:10:37'),
(25, 2, '2023-10-26 22:21:07'),
(26, 4, '2023-10-26 22:23:10'),
(27, 2, '2023-10-26 23:07:00'),
(28, 4, '2023-10-26 23:10:57'),
(29, 2, '2023-10-26 23:12:27'),
(30, 4, '2023-10-26 23:12:48'),
(31, 2, '2023-10-26 23:14:00'),
(32, 4, '2023-10-26 23:14:15'),
(33, 2, '2023-10-28 15:11:58'),
(34, 2, '2023-10-28 20:43:06'),
(35, 2, '2023-10-28 20:43:37'),
(36, 2, '2023-10-28 20:43:49'),
(37, 2, '2023-10-28 20:53:33'),
(38, 2, '2023-10-28 20:53:42'),
(39, 1, '2023-10-28 20:54:19'),
(40, 2, '2023-10-28 21:16:06'),
(41, 2, '2023-10-28 21:16:25'),
(42, 1, '2023-10-29 00:29:04'),
(43, 3, '2023-10-29 00:35:48'),
(44, 1, '2023-10-29 00:40:33'),
(45, 1, '2023-10-29 00:41:13'),
(46, 1, '2023-10-29 00:41:24'),
(47, 3, '2023-10-29 00:42:03'),
(48, 1, '2023-10-29 00:42:12'),
(49, 2, '2023-10-29 00:51:01'),
(50, 1, '2023-10-29 00:53:57'),
(51, 2, '2023-10-29 00:54:21'),
(52, 1, '2023-10-29 00:55:19'),
(53, 2, '2023-10-29 00:55:42');

-- --------------------------------------------------------

--
-- Table structure for table `promoted_students`
--

CREATE TABLE IF NOT EXISTS `promoted_students` (
  `id` int(11) NOT NULL,
  `student_lrn` varchar(50) DEFAULT NULL,
  `teacher_lrn` varchar(25) NOT NULL,
  `section` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `promoted_students_history`
--

CREATE TABLE IF NOT EXISTS `promoted_students_history` (
  `id` int(11) NOT NULL,
  `student_lrn` varchar(50) DEFAULT NULL,
  `grade` int(11) DEFAULT NULL,
  `section` varchar(25) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `promoted_students_history`
--

INSERT INTO `promoted_students_history` (`id`, `student_lrn`, `grade`, `section`, `date`) VALUES
(1, 'S0000000', 1, 'section a', '2023-10-24'),
(2, 'S0000000', 1, 'section a', '2023-10-26'),
(3, 'S0000000', 1, 'section a', '2023-10-26'),
(4, 'S0000000', 1, 'section a', '2023-10-26'),
(5, 'S0000000', 1, 'section a', '2023-10-26'),
(6, 'S0000000', 1, 'section a', '2023-10-26');

-- --------------------------------------------------------

--
-- Table structure for table `school_years_info`
--

CREATE TABLE IF NOT EXISTS `school_years_info` (
  `id` int(11) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `current` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `students_enrollment_info`
--

CREATE TABLE IF NOT EXISTS `students_enrollment_info` (
  `id` int(11) NOT NULL,
  `students_info_lrn` varchar(50) NOT NULL,
  `grade` int(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  `school_year` int(25) NOT NULL,
  `date_enrolled` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `grade_status` varchar(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students_enrollment_info`
--

INSERT INTO `students_enrollment_info` (`id`, `students_info_lrn`, `grade`, `section`, `school_year`, `date_enrolled`, `status`, `grade_status`) VALUES
(1, 'S0000000', 2, 'section c', 2023, '2023-10-14', 'continuing', 'Passed');

-- --------------------------------------------------------

--
-- Table structure for table `students_grade_attendance_info`
--

CREATE TABLE IF NOT EXISTS `students_grade_attendance_info` (
  `id` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `student_lrn` varchar(25) DEFAULT NULL,
  `teacher_lrn` varchar(25) DEFAULT NULL,
  `june_days_classes` int(11) DEFAULT NULL,
  `june_days_presents` int(11) DEFAULT NULL,
  `july_days_classes` int(11) DEFAULT NULL,
  `july_days_presents` int(11) DEFAULT NULL,
  `aug_days_classes` int(11) DEFAULT NULL,
  `aug_days_presents` int(11) DEFAULT NULL,
  `sep_days_classes` int(11) DEFAULT NULL,
  `sep_days_presents` int(11) DEFAULT NULL,
  `oct_days_classes` int(11) DEFAULT NULL,
  `oct_days_presents` int(11) DEFAULT NULL,
  `nov_days_classes` int(11) DEFAULT NULL,
  `nov_days_presents` int(11) DEFAULT NULL,
  `dec_days_classes` int(11) DEFAULT NULL,
  `dec_days_presents` int(11) DEFAULT NULL,
  `jan_days_classes` int(11) DEFAULT NULL,
  `jan_days_presents` int(11) DEFAULT NULL,
  `feb_days_classes` int(11) DEFAULT NULL,
  `feb_days_presents` int(11) DEFAULT NULL,
  `mar_days_classes` int(11) DEFAULT NULL,
  `mar_days_presents` int(11) DEFAULT NULL,
  `apr_days_classes` int(11) DEFAULT NULL,
  `apr_days_presents` int(11) DEFAULT NULL,
  `may_days_classes` int(11) DEFAULT NULL,
  `may_days_presents` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students_grade_attendance_info`
--

INSERT INTO `students_grade_attendance_info` (`id`, `grade`, `student_lrn`, `teacher_lrn`, `june_days_classes`, `june_days_presents`, `july_days_classes`, `july_days_presents`, `aug_days_classes`, `aug_days_presents`, `sep_days_classes`, `sep_days_presents`, `oct_days_classes`, `oct_days_presents`, `nov_days_classes`, `nov_days_presents`, `dec_days_classes`, `dec_days_presents`, `jan_days_classes`, `jan_days_presents`, `feb_days_classes`, `feb_days_presents`, `mar_days_classes`, `mar_days_presents`, `apr_days_classes`, `apr_days_presents`, `may_days_classes`, `may_days_presents`) VALUES
(4, 2, 'S0000000', 'T0000002', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `students_grade_average_info`
--

CREATE TABLE IF NOT EXISTS `students_grade_average_info` (
  `id` int(11) NOT NULL,
  `students_lrn` varchar(25) DEFAULT NULL,
  `grade` int(11) NOT NULL,
  `average` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students_grade_average_info`
--

INSERT INTO `students_grade_average_info` (`id`, `students_lrn`, `grade`, `average`) VALUES
(3, 'S0000000', 1, 99),
(4, 'S0000000', 2, 77);

-- --------------------------------------------------------

--
-- Table structure for table `students_grade_info`
--

CREATE TABLE IF NOT EXISTS `students_grade_info` (
  `id` int(11) NOT NULL,
  `student_lrn` varchar(25) DEFAULT NULL,
  `teacher_lrn` varchar(25) NOT NULL,
  `subject` varchar(25) NOT NULL,
  `grade` varchar(25) NOT NULL,
  `first_grade` int(11) DEFAULT NULL,
  `second_grade` int(11) DEFAULT NULL,
  `third_grade` int(11) DEFAULT NULL,
  `fourth_grade` int(11) DEFAULT NULL,
  `final` int(11) DEFAULT NULL,
  `units` int(11) NOT NULL,
  `status` varchar(25) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students_grade_info`
--

INSERT INTO `students_grade_info` (`id`, `student_lrn`, `teacher_lrn`, `subject`, `grade`, `first_grade`, `second_grade`, `third_grade`, `fourth_grade`, `final`, `units`, `status`) VALUES
(4, 'S0000000', 'T0000002', 'Math', '2', 100, 100, 100, 9, 77, 0, 'Passed');

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
  `addedBy` int(11) NOT NULL,
  `teacher_lrn` varchar(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students_info`
--

INSERT INTO `students_info` (`id`, `lrn`, `f_name`, `l_name`, `m_name`, `gender`, `b_date`, `b_place`, `c_status`, `age`, `nationality`, `religion`, `contact_number`, `email_address`, `home_address`, `guardian_name`, `addedBy`, `teacher_lrn`) VALUES
(1, 'S0000000', 'fname', 'student a', 'm name', 'Male', '2023-10-24', '1', 'Single', 1111, '1', '1', '1', 'stud@gmail.com', 'address', 'guaridian name', 1, 'T0000002');

-- --------------------------------------------------------

--
-- Table structure for table `subject_available_info`
--

CREATE TABLE IF NOT EXISTS `subject_available_info` (
  `id` int(11) NOT NULL,
  `grade_level` varchar(25) DEFAULT NULL,
  `subject` varchar(25) DEFAULT NULL,
  `available_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subject_list_info`
--

CREATE TABLE IF NOT EXISTS `subject_list_info` (
  `id` int(11) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `applicable_for` varchar(25) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_list_info`
--

INSERT INTO `subject_list_info` (`id`, `name`, `applicable_for`, `description`) VALUES
(1, 'Math', '1', ' sd'),
(2, 'Filipino', '1', ' 1');

-- --------------------------------------------------------

--
-- Table structure for table `teachers_info`
--

CREATE TABLE IF NOT EXISTS `teachers_info` (
  `id` int(11) NOT NULL,
  `lrn` varchar(25) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `address` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `civil_status` varchar(25) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `section` varchar(25) NOT NULL,
  `grade` varchar(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers_info`
--

INSERT INTO `teachers_info` (`id`, `lrn`, `first_name`, `last_name`, `address`, `gender`, `civil_status`, `email_address`, `section`, `grade`) VALUES
(1, 'T0000000', 'teacher a', '1', '1', 'Male', 'Single', '1@gmail.com', 'section a', '1'),
(2, 'T0000002', 'teacher 2', 'teacher 2 lname', 'address', 'Male', 'Single', 'teacher2@gmail.com', 'section c', '2');

-- --------------------------------------------------------

--
-- Table structure for table `teachers_subject_info`
--

CREATE TABLE IF NOT EXISTS `teachers_subject_info` (
  `id` int(11) NOT NULL,
  `teachers_lrn` varchar(25) NOT NULL,
  `subject` varchar(25) NOT NULL,
  `room` varchar(25) NOT NULL,
  `grade` varchar(25) NOT NULL,
  `schedule_time_in` time NOT NULL,
  `schedule_time_out` time NOT NULL,
  `schedule_day` varchar(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers_subject_info`
--

INSERT INTO `teachers_subject_info` (`id`, `teachers_lrn`, `subject`, `room`, `grade`, `schedule_time_in`, `schedule_time_out`, `schedule_day`) VALUES
(1, 'T0000000', 'Math', '1', '1', '00:00:00', '00:00:00', ''),
(2, 'T0000002', 'Math', '1', '2', '00:00:00', '00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `trash_info`
--

CREATE TABLE IF NOT EXISTS `trash_info` (
  `id` int(11) NOT NULL,
  `user_lrn` varchar(25) DEFAULT NULL,
  `teacher_lrn` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `history` varchar(9999) DEFAULT NULL,
  `removed_date` date NOT NULL,
  `removed_by` varchar(25) NOT NULL,
  `position` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE IF NOT EXISTS `users_info` (
  `id` int(11) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  `email` varchar(25) NOT NULL,
  `user_type` varchar(25) NOT NULL,
  `user_lrn` varchar(25) NOT NULL,
  `img_path` varchar(255) NOT NULL,
  `dark_mode` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_info`
--

INSERT INTO `users_info` (`id`, `last_name`, `first_name`, `username`, `password`, `email`, `user_type`, `user_lrn`, `img_path`, `dark_mode`) VALUES
(1, 'limpangog', 'daisy', 'admin', 'admin', '', 'admin', '1', '', 1),
(2, '12', 'teacher a', 'T0000000', '1', '', 'teacher', 'T0000000', '../../assets/users_img/T0000000.png', 1),
(3, 'student a', '12', 'S0000000', 'student a', '', 'student', 'S0000000', '', 0),
(4, 'teacher 2 lname', 'teacher 22', 'T0000002', 'teacher 2 lname', '', 'teacher', 'T0000002', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grade_info`
--
ALTER TABLE `grade_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_visited_info`
--
ALTER TABLE `page_visited_info`
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
-- Indexes for table `students_grade_attendance_info`
--
ALTER TABLE `students_grade_attendance_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students_grade_average_info`
--
ALTER TABLE `students_grade_average_info`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `page_visited_info`
--
ALTER TABLE `page_visited_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT for table `promoted_students`
--
ALTER TABLE `promoted_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `promoted_students_history`
--
ALTER TABLE `promoted_students_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `school_years_info`
--
ALTER TABLE `school_years_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `students_enrollment_info`
--
ALTER TABLE `students_enrollment_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `students_grade_attendance_info`
--
ALTER TABLE `students_grade_attendance_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `students_grade_average_info`
--
ALTER TABLE `students_grade_average_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `students_grade_info`
--
ALTER TABLE `students_grade_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `students_info`
--
ALTER TABLE `students_info`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `subject_available_info`
--
ALTER TABLE `subject_available_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `subject_list_info`
--
ALTER TABLE `subject_list_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `teachers_info`
--
ALTER TABLE `teachers_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `teachers_subject_info`
--
ALTER TABLE `teachers_subject_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `trash_info`
--
ALTER TABLE `trash_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users_info`
--
ALTER TABLE `users_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
