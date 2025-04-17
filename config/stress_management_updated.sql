-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 03:59 PM
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
-- Database: `stress_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_questions`
--

CREATE TABLE `academic_questions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `index_no` varchar(50) NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `faculty` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `status` enum('Pending','Resolved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_questions`
--

INSERT INTO `academic_questions` (`id`, `user_id`, `index_no`, `reg_no`, `full_name`, `faculty`, `telephone`, `email`, `question`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '22020284', '2022/IS/028', 'Ovindu Gunatunga', 'science', '0776558322', 'abfg@gmail.com', 'hello', 'Pending', '2025-01-24 14:58:04', '2025-01-24 14:58:04');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `counselor_id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `topic` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('Pending','Accepted','Denied') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `student_id`, `counselor_id`, `appointment_date`, `topic`, `email`, `phone`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 3, '2025-01-31 20:21:00', 'Stress Management', 'abfg@gmail.com', '0761944580', '2025-01-24 14:51:47', '2025-01-24 14:51:47', 'Pending'),
(2, 1, 2, '2025-01-25 10:00:00', 'Stress Management', 'student1@example.com', '1234567890', '2025-01-24 14:54:26', '2025-01-24 14:54:26', 'Pending'),
(3, 1, 1, '2025-01-26 15:30:00', 'Career Guidance', 'student2@example.com', '0987654321', '2025-01-24 14:54:26', '2025-01-24 14:54:26', 'Accepted'),
(4, 1, 3, '2025-01-27 09:00:00', 'Time Management', 'student3@example.com', '1122334455', '2025-01-24 14:54:26', '2025-01-24 14:54:26', 'Pending'),
(5, 4, 4, '2025-01-28 14:00:00', 'Mental Health Support', 'student4@example.com', '6677889900', '2025-01-24 14:54:26', '2025-01-24 14:54:26', 'Denied');

-- --------------------------------------------------------

--
-- Table structure for table `counselors`
--

CREATE TABLE `counselors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('Student','Professional') NOT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `counselors`
--

INSERT INTO `counselors` (`id`, `name`, `type`, `specialization`, `profile_image`, `description`, `email`, `password`, `username`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'Student', NULL, NULL, 'A student-level counselor available for peer guidance.', 'johndoe@student.edu', '123', 'counselorjd', '2025-01-24 14:17:51', '2025-01-24 14:19:14'),
(2, 'Dr. Emily Smith', 'Professional', 'Psychology', '', 'A professional counselor specializing in mental health and stress management.', 'emily.smith@procare.com', '123', 'counselores', '2025-01-24 14:17:51', '2025-01-24 14:18:37'),
(3, 'Mark Taylor', 'Student', NULL, '', 'An approachable student counselor passionate about helping peers.', 'marktaylor@student.edu', '123', 'counselormt', '2025-01-24 14:17:51', '2025-01-24 14:19:34'),
(4, 'Dr. Rachel Green', 'Professional', 'Career Counseling', NULL, 'Provides expert guidance in career development and decision-making.', 'rachel.green@careerpro.com', '123', 'counselorrg', '2025-01-24 14:17:51', '2025-01-24 14:19:54'),
(5, 'Sarah Lee', 'Professional', 'Stress Management', '', 'Specializes in techniques to reduce workplace and academic stress.', 'sarah.lee@stressrelief.com', '123', 'counselorsl', '2025-01-24 14:17:51', '2025-01-24 14:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `title`, `description`, `image`, `user_id`, `created_at`) VALUES
(1, 'Post 1', 'This is the description for post 1.', 'image1.jpg', 1, '2024-11-01 10:00:00'),
(2, 'Post 2', 'This is the description for post 2.', 'image2.jpg', 2, '2024-11-02 14:30:00'),
(3, 'Post 3', 'This is the description for post 3.', NULL, 3, '2024-11-03 09:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `relaxation_activities`
--

CREATE TABLE `relaxation_activities` (
  `id` int(11) NOT NULL,
  `activity_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `playlist_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `relaxation_activities`
--

INSERT INTO `relaxation_activities` (`id`, `activity_name`, `description`, `image_url`, `playlist_url`) VALUES
(40, 'Deep Breathing Guide', 'Take a moment to center yourself with this guided breathing exercise. Follow the visual prompts to inhale, hold, and exhale deeply, helping you to relax and reduce stress.', 'breathing-exercise-concept-illustration_114360-9010 (1).avif', 'https://youtu.be/FW1yK8ahhas?si=kV-whWXWnvICdXzf'),
(41, 'Soothing Sounds', 'Immerse yourself in calming sounds and music designed to ease your mind. Let soothing melodies guide you. Close your eyes, take a deep breath, and feel the stress melt away.', 'organic-flat-person-meditating-peacefully_23-2148909902.avif', 'https://youtu.be/uDmW_Ge5RLE?si=JiQ9s_QCbpPgm1eQ'),
(42, 'Muscle Relaxation', 'Release tension with this progressive muscle relaxation guide. Follow the steps to gently tense and then relax different muscle groups, promoting overall relaxation and physical calm.', 'stretching-exercises-concept-illustration_114360-9012.avif', 'https://youtu.be/_1h-zizAGsc?si=g5BHkcAQiuDAxFO9'),
(43, 'Meditation Timer', 'Set a timer for your meditation practice and focus on your breath without worrying about time. Choose a duration and let yourself meditate peacefully until a chime brings you back.', 'cartoon-business-people-meditating-illustration_23-2148924044.avif', 'https://youtu.be/Knyp824QSnA?si=5g7f9plzx8RdVcda'),
(44, 'Yoga Poses', 'Take a moment to flow through a series of gentle yoga poses. Focus on your breath as you stretch, strengthen, and find balance, allowing yourself to relax and rejuvenate your body.', 'vector-woman-in-yoga-pose.jpg', 'https://youtu.be/REIAGCwrzcI?si=QGsq2AdOz_ZQujkb');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `counselor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `counselor_id`, `user_id`, `rating`, `review_text`, `created_at`) VALUES
(1, 1, 1, 5, 'John is an excellent counselor and very understanding.', '2025-01-24 14:44:37'),
(2, 2, 1, 4, 'Dr. Emily Smith helped me manage my stress effectively.', '2025-01-24 14:44:37'),
(3, 3, 1, 3, 'Mark is friendly, but the advice could have been more specific.', '2025-01-24 14:44:37'),
(4, 4, 4, 5, 'Dr. Rachel Green gave me amazing career guidance.', '2025-01-24 14:44:37'),
(5, 5, 4, 4, 'Sarah shared some great stress management tips.', '2025-01-24 14:44:37');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_events`
--

CREATE TABLE `schedule_events` (
  `id` int(11) NOT NULL,
  `title` varchar(90) NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_events`
--

INSERT INTO `schedule_events` (`id`, `title`, `description`, `date`, `start_time`, `end_time`) VALUES
(6, 'Asdgd', 'Asfas', '2024-11-25', '16:28:00', '04:28:00'),
(7, 'ASD', 'Come as', '2024-11-25', '02:51:00', '16:53:00'),
(15, 'asd', 'asda', '2024-10-24', '04:16:00', '15:14:00'),
(18, 'asda', 'asdada', '2024-11-25', '00:00:00', '00:00:00'),
(21, 'Karthee', 'Need to bedone ', '2024-11-25', '02:43:00', '15:45:00'),
(22, 'asd', 'asda', '2024-11-19', '01:30:00', '15:32:00'),
(24, 'asdfg', '', '2024-11-29', '02:36:00', '13:35:00'),
(26, 'mathematics exam', '', '2024-11-14', '14:15:00', '15:20:00'),
(29, 'mathematics exam', '', '2024-11-13', '17:06:00', '18:06:00'),
(35, 'maths', 'fsgf', '2024-12-10', '07:27:00', '07:31:00'),
(36, 'maths', 'ahfs', '2024-12-04', '04:29:00', '06:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `stress_management_responses`
--

CREATE TABLE `stress_management_responses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sleep_hours` decimal(4,2) DEFAULT NULL CHECK (`sleep_hours` between 0 and 24),
  `exercise_hours` decimal(4,2) DEFAULT NULL CHECK (`exercise_hours` between 0 and 24),
  `work_hours` decimal(4,2) DEFAULT NULL CHECK (`work_hours` between 0 and 24),
  `mood_status` varchar(50) DEFAULT NULL,
  `response_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `year` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `phone`, `year`, `password`, `role`, `created_at`) VALUES
(1, 'student1', 'student1@example.com', '0775889658', '1st Year', '123', 'student', '2024-09-15 13:52:10'),
(2, 'admin1', 'admin1@example.com', '0778552145', NULL, '123', 'admin', '2024-09-15 13:52:10'),
(3, 'superadmin1', 'superadmin1@example.com', '0778563254', NULL, '123', 'super_admin', '2024-09-15 13:52:10'),
(4, 'student2', 'ovindumandith@gmail.com', '0703871009', '1', '111111111', 'student', '2024-09-22 20:53:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_questions`
--
ALTER TABLE `academic_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_academic_questions_user` (`user_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_appointments_student` (`student_id`),
  ADD KEY `fk_appointments_counselor` (`counselor_id`);

--
-- Indexes for table `counselors`
--
ALTER TABLE `counselors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_user_created` (`user_id`,`created_at`);

--
-- Indexes for table `relaxation_activities`
--
ALTER TABLE `relaxation_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reviews_counselor` (`counselor_id`),
  ADD KEY `fk_reviews_user` (`user_id`);

--
-- Indexes for table `schedule_events`
--
ALTER TABLE `schedule_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stress_management_responses`
--
ALTER TABLE `stress_management_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_questions`
--
ALTER TABLE `academic_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `counselors`
--
ALTER TABLE `counselors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `relaxation_activities`
--
ALTER TABLE `relaxation_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `schedule_events`
--
ALTER TABLE `schedule_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `stress_management_responses`
--
ALTER TABLE `stress_management_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_questions`
--
ALTER TABLE `academic_questions`
  ADD CONSTRAINT `fk_academic_questions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_appointments_counselor` FOREIGN KEY (`counselor_id`) REFERENCES `counselors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_appointments_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_counselor` FOREIGN KEY (`counselor_id`) REFERENCES `counselors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `stress_management_responses`
--
ALTER TABLE `stress_management_responses`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
