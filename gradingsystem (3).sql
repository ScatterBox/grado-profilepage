-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2025 at 06:14 AM
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
-- Database: `gradingsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `ename` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `birthdate` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `img` varchar(255) DEFAULT 'default-profile.jpg',
  `bio` varchar(101) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`user_id`, `fname`, `mname`, `lname`, `ename`, `nickname`, `age`, `sex`, `birthdate`, `address`, `username`, `password`, `email`, `img`, `bio`) VALUES
(1, 'Eren Sky', 'Mediadero', 'Balsomo', '', 'Eren Sky', '22', 'male', 'October 14 2001', 'oringao', 'es123', '1234', 'balsomoe101@gmail.com', 'admin_1_1739337095.png', NULL),
(6, 'Erenism', 'Skyism', 'Balsomism', '', 'italic bold', '12', 'male', '1993-11-01', 'purok omsim', 'ksksks', '1234', NULL, 'default-profile.jpg', NULL),
(7, 'Serene', 'Peace', 'Concord', '', 'calm', '22', 'female', '2001-09-05', 'purok ambience', 'pespes', '1234', NULL, 'default-profile.jpg', NULL),
(8, 'Kian', 'Sumagaysay', 'Pablo', '', 'Bataaaa', '11', 'male', '2013-04-21', 'Oringao', 'bebekian2cute', '$2y$10$9rxcGNiSVVN14759hiSzrO8MyKOKVhQ2D42lg..XtaK', NULL, 'default-profile.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(1, 'balsomoe101@gmail.com', '08cc99aa8c9ccbffa9d263bb8238542a3b977d477259e40890799038ead31b3debab261f33f11eff6985a8b6daa0dae5fc8a', '2025-02-05 10:49:13', '2025-02-05 01:49:13'),
(2, 'balsomoe101@gmail.com', '2850555dac91974e0889fa99ebd5ea168c7fca6761b65d45e4174d71a4dca46aaa2027e310b2196fc44736da8b1924ab4a3d', '2025-02-05 11:19:45', '2025-02-05 02:19:45'),
(3, 'balsomoe101@gmail.com', 'bc16812b02e2d578c21e07fdcbf5f98e8a0a9c6ac8da564f09404de27d1d6f529916dcd3efd33db06c707d25e1b7e6bd63f2', '2025-02-05 11:34:59', '2025-02-05 02:34:59'),
(4, 'balsomoe101@gmail.com', '6813c040f83ff3f3ceeffc592cc3d0992dbb088c72be8e5cae61985b34f2503d4a14cf197859306a90f7a61bf1f0c5027b11', '2025-02-05 04:52:20', '2025-02-05 02:52:20'),
(5, 'balsomoe101@gmail.com', '4781272227b437e7cc5dbe8e18cc5960e22ae8523fb1fce230f71f2fa3b87fd212c2e51df1478922406f3c2375072ad6364c', '2025-02-05 04:56:05', '2025-02-05 02:56:05'),
(6, 'balsomoe101@gmail.com', 'cf437181ff776892961eee576323bb4025c7638a864fbdbc7047043c41b69594e0353756d9481c2a81ba4105b8d1fdd5f710', '2025-02-05 05:00:35', '2025-02-05 03:00:35'),
(7, 'adg123@gmail.com', '7d939d77bc177a6965f8cad5c84beffbdcbfc7929d34820594b40137edfa2eeec6a4e4505baa3744b002d26165b55ed6ce44', '2025-02-05 05:01:04', '2025-02-05 03:01:04'),
(8, 'balsomoe101@gmail.com', 'e115607bf085fdbbd8c66f0eafcfd96bfc11aedaa61518854ee923c7ca42a6bef67cd6b706911ddaeed246fab4c68a5ed289', '2025-02-05 05:16:17', '2025-02-05 03:16:17'),
(9, 'balsomoe101@gmail.com', 'dc9eed3a20fac21b458f90c788494b8503cbeb411d1e95588e48a01254960ab9a9a5c4e9358a2593fbe516bdd0c8568bdcf2', '2025-02-05 05:17:28', '2025-02-05 03:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `performance_tasks`
--

CREATE TABLE `performance_tasks` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `total_score` int(11) NOT NULL,
  `date` date NOT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `performance_tasks`
--

INSERT INTO `performance_tasks` (`id`, `name`, `total_score`, `date`, `subject_id`) VALUES
(3, 'performance_tasks', 40, '2025-02-09', 45),
(4, 'performance_tasks', 20, '2025-02-09', 50);

-- --------------------------------------------------------

--
-- Table structure for table `quarterly_assessment`
--

CREATE TABLE `quarterly_assessment` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `total_score` int(11) NOT NULL,
  `date` date NOT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quarterly_assessment`
--

INSERT INTO `quarterly_assessment` (`id`, `name`, `total_score`, `date`, `subject_id`) VALUES
(3, 'quarterly_assessment', 40, '2025-02-09', 51),
(4, 'quarterly_assessment', 50, '2025-02-11', 48),
(5, 'quarterly_assessment', 50, '2025-02-11', 48);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `ename` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `pname` varchar(50) NOT NULL,
  `lrn` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `birthdate` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `year_level` enum('grade9','grade10') NOT NULL,
  `section` varchar(20) NOT NULL,
  `img` varchar(255) DEFAULT 'default-profile.jpg',
  `bio` varchar(101) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`user_id`, `fname`, `mname`, `lname`, `ename`, `nickname`, `pname`, `lrn`, `age`, `sex`, `birthdate`, `address`, `username`, `password`, `email`, `year_level`, `section`, `img`, `bio`) VALUES
(1, 'Eren', 'Sky', 'Balsomo', '', 'Eren Sky', 'Mr. B', '1234567890', '22', 'male', 'October 14 2001', 'Oringao', 'eren123', '1234', 'balsomo@gmail.com', 'grade9', 'section1', 'default-profile.jpg', NULL),
(13, 'Albert ', 'D.', 'Gangsta', 'III', 'Steinbert', 'Mr. Stein', '1174012312', '12', 'male', '2012-10-14', 'purok japan', 'steins123', 'berto', 'adg123@gmail.com', 'grade9', 'Bittern', 'default-profile.jpg', NULL),
(14, 'Jose', 'P.', 'Rizzler', 'Jr.', 'Gyatt Rizz', 'Mr. Jacks', '11740312321', '12', 'male', '2012-10-01', 'purok usa', 'rizzler123', '1234', 'jprizz123@gmail.com', 'grade9', 'sampaguita', 'default-profile.jpg', NULL),
(15, 'Andries', 'U.', 'Wilandress', '', 'CallMeWhat', 'Mrs. Undresd', '1140731231', '12', 'male', '2012-12-31', 'Purok Egypt', 'Andried', '1234', 'owtna123@gmail.com', 'grade9', 'Magma', 'default-profile.jpg', NULL),
(16, 'Von', 'Z.', 'Lance', '', 'VOnLaZce', 'Mr. Voidleia', '1170431231244', '12', 'male', '2010-02-14', 'Purok Ewan', 'vvldrizzy', 'qwer', 'lzv123@gmail.com', 'grade10', 'Turbo', 'default-profile.jpg', NULL),
(17, 'Cutie', 'L', 'Angs', '', 'AquH LnG Tohh', 'Mrs. Angs', '12308978775', '12', 'female', '2014-07-26', 'Purok aslomania', 'cl21', '1234', 'bhangsd123@gmail.com', 'grade10', 'Blast', 'default-profile.jpg', NULL),
(18, 'Abdul', 'Raken', 'Rhul', '', 'Shakolet', 'Mr. Rhul', '1114324342', '12', 'male', '2009-06-08', 'purok minda', 'rakul123', '1234', 'arul123@gmail.com', 'grade10', 'Cake', 'default-profile.jpg', NULL),
(19, 'arn', 'Sky', 'Balsomo', '', 'Eren Sky', 'Mr. Z', '123213123', '12', 'male', '2024-07-04', 'oringao', '1234312', '1234', 'erse123@gmail.com', 'grade9', 'section1', 'default-profile.jpg', NULL),
(20, 'Aries', 'Zodiac', 'Skin', '', 'shingy', 'Mr. Tookey', '21323134341341', '13', 'female', '2024-07-18', 'Stars', 'Aries123', '1234', 'azdc123@gmail.com', 'grade10', 'lemon', 'default-profile.jpg', NULL),
(21, 'Kian', 'sumagaysay', 'Imut', '', 'Batata', '', '', '11', 'male', '2013-04-21', 'oringao', 'bebekian2cute', '$2y$10$AwgMH9QbEbLzfsMtkaBESe6omGWEzfwQRQczqcq27Jx', '', 'grade9', '', 'default-profile.jpg', NULL),
(22, 'Travis', 'Fein', 'Scott', 'III', 'Fein', 'Feinfeinfein', '1231525464572564', '11', 'male', '2013-04-21', 'oringao', 'Feinis123', '1234', 'fein123@gmail.com', 'grade9', 'Bluert', 'default-profile.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_subjects`
--

INSERT INTO `student_subjects` (`id`, `student_id`, `subject_id`) VALUES
(23, 13, 45),
(24, 13, 46),
(26, 13, 48),
(27, 13, 49),
(28, 16, 50),
(29, 16, 51),
(30, 13, 51);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(20) NOT NULL,
  `year_level` enum('grade9','grade10') NOT NULL,
  `section` varchar(20) NOT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `year_level`, `section`, `created_by`) VALUES
(45, 'English', 'grade9', 'Bittern', 1),
(46, 'M.A.PE.H', 'grade9', 'Bittern', 1),
(48, 'Science', 'grade9', 'Bittern', 1),
(49, 'ESP', 'grade9', 'Bittern', 1),
(50, 'Ap', 'grade10', 'Turbo', 1),
(51, 'Filipino', 'grade10', 'Turbo', 1);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `ename` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `birthdate` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `img` varchar(255) DEFAULT 'default-profile.jpg',
  `bio` varchar(101) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`user_id`, `fname`, `mname`, `lname`, `ename`, `age`, `sex`, `birthdate`, `address`, `nickname`, `username`, `password`, `email`, `img`, `bio`) VALUES
(1, 'Kian', 'Sumagaysay', 'Pablo', '', '11', 'male', 'April 21 2013', 'Oringao', 'Kian Pablo', 'bbkian', '1234', 'kian123@gmail.com', 'default-profile.jpg', NULL),
(7, 'Yan', 'Yan', 'Balongcas', '', '22', 'male', '2001-10-14', 'Crossing lapus', 'yanyan', 'yan123', '1234', NULL, 'default-profile.jpg', NULL),
(8, 'Joe', 'Jone', 'Jonel', '', '22', 'male', '2001-02-14', 'campo', 'Joel', 'j123', '1234', NULL, 'default-profile.jpg', NULL),
(9, 'Kiani', 'sumagaysay', 'Imut', 'II', '24', 'male', '2000-04-17', 'Gemilina', 'qian1231', 'yanki1234', '1234', NULL, 'default-profile.jpg', NULL),
(10, 'Dave', 'Dra', 'Gonheart', 'Sr.', '25', 'male', '1999-10-12', 'Gemilina', 'ddrgon123', 'dragonic1231', '1234', 'ddgragon123@gmail.com', 'default-profile.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `written_works`
--

CREATE TABLE `written_works` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `total_score` int(11) NOT NULL,
  `date` date NOT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `written_works`
--

INSERT INTO `written_works` (`id`, `name`, `total_score`, `date`, `subject_id`) VALUES
(6, 'written_works', 40, '2025-02-09', 50);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `performance_tasks`
--
ALTER TABLE `performance_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `quarterly_assessment`
--
ALTER TABLE `quarterly_assessment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `written_works`
--
ALTER TABLE `written_works`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `performance_tasks`
--
ALTER TABLE `performance_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quarterly_assessment`
--
ALTER TABLE `quarterly_assessment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `written_works`
--
ALTER TABLE `written_works`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `performance_tasks`
--
ALTER TABLE `performance_tasks`
  ADD CONSTRAINT `performance_tasks_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `quarterly_assessment`
--
ALTER TABLE `quarterly_assessment`
  ADD CONSTRAINT `quarterly_assessment_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `teachers` (`user_id`);

--
-- Constraints for table `written_works`
--
ALTER TABLE `written_works`
  ADD CONSTRAINT `written_works_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
