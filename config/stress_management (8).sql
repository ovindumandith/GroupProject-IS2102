-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2025 at 08:39 AM
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
  `category` varchar(30) DEFAULT NULL,
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

INSERT INTO `academic_questions` (`id`, `user_id`, `index_no`, `reg_no`, `full_name`, `faculty`, `category`, `telephone`, `email`, `question`, `status`, `created_at`, `updated_at`) VALUES
(10, 1, '22020284', '2022/IS/028', 'Ovindu Gunatunga', 'Science', 'Medical', '0761944580', 'ovindumandith@gmail.com', 'Time Period for a medical? is it a week or more', '', '2025-04-08 08:56:55', '2025-04-08 08:57:13'),
(11, 1, '22020284', '2022/IS/028', 'Ovindu Gunatunga', 'Science', 'Examination', '0761944580', 'ovindumandith@gmail.com', 'if I am repeated what to-do', 'Resolved', '2025-04-08 08:58:58', '2025-04-18 07:52:43'),
(12, 1, '22020284', '2022/IS/028', 'Ovindu Gunatunga', 'Computing', 'Accommodation', '0761944580', 'ovindumandith@gmail.com', 'Hostel Facilities fo 1 st years', 'Resolved', '2025-04-14 17:38:25', '2025-04-18 07:52:40'),
(13, 1, '22020284', '2022/IS/028', 'Ovindu Gunatunga', 'Computing', 'Coursework', '0761944580', 'ovindumandith@gmail.com', 'When to submit module IT4010 assignment 1 ?', 'Resolved', '2025-04-18 07:43:14', '2025-04-18 07:52:35');

-- --------------------------------------------------------

--
-- Table structure for table `academic_question_response`
--

CREATE TABLE `academic_question_response` (
  `response_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `response` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_question_response`
--

INSERT INTO `academic_question_response` (`response_id`, `question_id`, `admin_id`, `response`, `created_at`, `updated_at`) VALUES
(15, 10, 5, 'yes, you must submit it within one week from the examination held date for that particular module  ', '2025-04-15 17:51:01', '2025-04-15 17:51:01'),
(16, 12, 5, 'We will inform you soon through VLE.', '2025-04-17 09:11:58', '2025-04-17 09:11:58'),
(18, 12, 5, '123', '2025-04-17 09:18:15', '2025-04-17 09:18:15'),
(19, 13, 5, '12 june', '2025-04-18 07:52:13', '2025-04-18 07:52:13');

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
(4, 1, 3, '2025-01-27 09:00:00', 'Time Management', 'student3@example.com', '1122334455', '2025-01-24 14:54:26', '2025-01-24 15:00:25', 'Accepted'),
(5, 4, 4, '2025-01-28 14:00:00', 'Mental Health Support', 'student4@example.com', '6677889900', '2025-01-24 14:54:26', '2025-01-24 14:54:26', 'Denied'),
(6, 1, 2, '2025-05-01 18:34:00', 'Stress Management', '2022is028@stu.ucsc.cmb.ac.lk', '0761944580', '2025-04-07 09:01:36', '2025-04-07 09:31:46', 'Accepted'),
(7, 1, 2, '2025-04-22 17:10:00', 'Stress Management', 'ovindumandith@gmail.com', '0761944580', '2025-04-19 10:13:22', '2025-04-19 11:41:40', 'Accepted'),
(8, 1, 2, '2025-04-24 18:30:00', 'Stress Management', 'ovindumandith@gmail.com', '0761944580', '2025-04-20 07:50:40', '2025-04-20 07:58:33', 'Accepted'),
(9, 1, 2, '2025-04-30 10:30:00', 'Stress Management', 'ovindumandith@gmail.com', '0761944580', '2025-04-24 04:01:30', '2025-04-24 04:02:03', 'Denied'),
(10, 1, 2, '2025-05-08 11:00:00', 'Stress Management', 'ovindumandith@gmail.com', '0761944580', '2025-04-24 04:24:18', '2025-04-24 04:24:18', 'Pending'),
(11, 1, 2, '2025-04-26 06:13:00', 'Stress Management', 'ovindumandith@gmail.com', '0761944580', '2025-04-25 18:43:24', '2025-04-25 18:43:24', 'Pending');

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
(2, 'Dr. Emily Smith', 'Professional', 'Psychology', '', 'A professional counselor specializing in mental health and stress management.', 'emily.smith2@procare.com', '123456789', 'counselores', '2025-01-24 14:17:51', '2025-04-26 03:23:31'),
(3, 'Mark Taylor', 'Student', NULL, '', 'An approachable student counselor passionate about helping peers.', 'marktaylor@student.edu', '123', 'counselormt', '2025-01-24 14:17:51', '2025-01-24 14:19:34'),
(4, 'Dr. Rachel Green', 'Professional', 'Career Counseling', NULL, 'Provides expert guidance in career development and decision-making.', 'rachel.green@careerpro.com', '123', 'counselorrg', '2025-01-24 14:17:51', '2025-01-24 14:19:54'),
(5, 'Sarah Lee', 'Professional', 'Stress Management', '', 'Specializes in techniques to reduce workplace and academic stress.', 'sarah.lee@stressrelief.com', '123', 'counselorsl', '2025-01-24 14:17:51', '2025-01-24 14:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `forwarded_questions`
--

CREATE TABLE `forwarded_questions` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `status` enum('Unread','Read','Responded') DEFAULT 'Unread',
  `forwarded_by` int(11) NOT NULL,
  `forwarded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forwarded_questions`
--

INSERT INTO `forwarded_questions` (`id`, `question_id`, `lecturer_id`, `status`, `forwarded_by`, `forwarded_at`, `read_at`, `responded_at`) VALUES
(1, 10, 13, 'Read', 5, '2025-04-08 08:57:13', '2025-04-14 08:37:39', NULL),
(2, 10, 18, 'Unread', 5, '2025-04-08 08:57:13', NULL, NULL),
(3, 11, 9, 'Unread', 5, '2025-04-08 08:59:16', NULL, NULL),
(4, 11, 11, 'Unread', 5, '2025-04-08 08:59:16', NULL, NULL),
(5, 11, 22, 'Unread', 5, '2025-04-08 08:59:16', NULL, NULL),
(6, 12, 16, 'Responded', 5, '2025-04-15 18:04:37', '2025-04-15 18:06:24', '2025-04-15 18:13:19'),
(7, 12, 25, 'Unread', 5, '2025-04-15 18:04:37', NULL, NULL),
(8, 13, 7, 'Unread', 5, '2025-04-18 07:44:21', NULL, NULL),
(9, 13, 10, 'Unread', 5, '2025-04-18 07:44:21', NULL, NULL),
(10, 13, 21, 'Unread', 5, '2025-04-18 07:44:21', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lecturers`
--

CREATE TABLE `lecturers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecturers`
--

INSERT INTO `lecturers` (`id`, `user_id`, `name`, `email`, `department`, `category`, `profile_image`, `bio`, `created_at`, `updated_at`) VALUES
(21, 7, 'Dr. Amara Perera', 'amara.perera@ucsc.edu', 'Computing', 'Coursework', 'amara_perera.jpg', 'Senior Lecturer with 15 years of experience in Computer Science education. Specializes in programming languages and software engineering.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(22, 8, 'Prof. Nimal Fernando', 'nimal.fernando@ucsc.edu', 'Computing', 'Assignment', 'nimal_fernando.jpg', 'Professor of Data Science with expertise in big data analytics and machine learning algorithms. Leads the Data Analytics Research Group.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(23, 9, 'Dr. Lakshman Wijekoon', 'lak.wijekoon@ucsc.edu', 'Computing', 'Examination', 'lakshman_wijekoon.jpg', 'PhD in Computer Engineering with research focus on cybersecurity and network systems. Coordinator for examination matters at UCSC.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(24, 10, 'Prof. Dilani Jayawardena', 'dilani.j@eng.edu', 'Engineering', 'Coursework', 'dilani_j.jpg', 'Professor of Mechanical Engineering with industrial experience. Focuses on fluid dynamics and thermodynamics.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(25, 11, 'Dr. Rohan Silva', 'rohan.silva@eng.edu', 'Engineering', 'Examination', 'rohan_silva.jpg', 'PhD from MIT with specialization in structural engineering. Examination coordinator for the Faculty of Engineering.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(26, 12, 'Dr. Priyanthi Gunasekara', 'priyanthi.g@eng.edu', 'Engineering', 'Registration', 'priyanthi_g.jpg', 'Senior Lecturer in Civil Engineering and faculty advisor for course registration matters.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(27, 13, 'Prof. Anura Bandara', 'anura.b@sci.edu', 'Science', 'Medical', 'anura_b.jpg', 'Professor of Biology with medical background. Advises on health-related academic issues.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(28, 14, 'Dr. Chandima Ranasinghe', 'chandima.r@sci.edu', 'Science', 'Financial', 'chandima_r.jpg', 'Senior Lecturer in Chemistry and coordinator for financial aid programs within the faculty.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(29, 15, 'Dr. Sunil Premaratne', 'sunil.p@sci.edu', 'Science', 'Mahapola', 'sunil_p.jpg', 'Lecturer in Physics and faculty representative for Mahapola and Bursary matters.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(30, 16, 'Prof. Kumari Dissanayake', 'kumari.d@arts.edu', 'Arts', 'Accommodation', 'kumari_d.jpg', 'Professor of Sociology and liaison for student accommodation concerns.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(31, 17, 'Dr. Ajith Karunaratne', 'ajith.k@arts.edu', 'Arts', 'Assignment', 'ajith_k.jpg', 'Senior Lecturer in English Literature specializing in academic writing and assignment methodologies.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(32, 18, 'Prof. Ranjith Seneviratne', 'ranjith.s@med.edu', 'Medicine', 'Medical', 'ranjith_s.jpg', 'Professor of Medicine and head of student affairs dealing with medical issues affecting academic performance.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(33, 19, 'Dr. Chamari Weerasinghe', 'chamari.w@med.edu', 'Medicine', 'Repeat_Registration', 'chamari_w.jpg', 'Senior Lecturer in Pharmacology and advisor for students needing to repeat courses.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(34, 20, 'Dr. Tharaka Gunawardena', 'tharaka.g@bus.edu', 'Business', 'Financial', 'tharaka_g.jpg', 'PhD in Finance and coordinator for financial assistance programs for business students.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(35, 21, 'Prof. Deepani Alwis', 'deepani.a@bus.edu', 'Business', 'Coursework', 'deepani_a.jpg', 'Professor of Management Studies with expertise in curriculum development and academic advising.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(36, 22, 'Prof. Lalith Mendis', 'lalith.m@law.edu', 'Law', 'Examination', 'lalith_m.jpg', 'Professor of Constitutional Law and chairman of the examination board.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(37, 23, 'Dr. Gayani Peris', 'gayani.p@law.edu', 'Law', 'Registration', 'gayani_p.jpg', 'Senior Lecturer in Criminal Law and faculty registration coordinator.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(38, 24, 'Dr. Wasantha Rathnayake', 'wasantha.r@ucsc.edu', 'Computing', 'Other', 'wasantha_r.jpg', 'Lecturer specializing in general academic support and student mentoring.', '2025-04-08 08:16:05', '2025-04-08 08:16:05'),
(39, 25, 'Dr. Mala Samarawickrama', 'mala.s@sci.edu', 'Science', 'Accommodation', 'mala_s.jpg', 'Senior Lecturer and coordinator for international student accommodation concerns.', '2025-04-08 08:16:05', '2025-04-08 08:16:05');

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `module_code` varchar(10) NOT NULL,
  `module_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`module_code`, `module_name`) VALUES
('CS102', 'Artificial Intelligence'),
('CS101', 'Data Structures'),
('CS103', 'Database Systems'),
('CS104', 'Machine Learning');

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
-- Table structure for table `question_replies`
--

CREATE TABLE `question_replies` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_replies`
--

INSERT INTO `question_replies` (`id`, `question_id`, `user_id`, `reply_text`, `created_at`, `updated_at`) VALUES
(1, 10, 13, 'yes, you must submit it within one week from the examination held date for that particular module. Thanks', '2025-04-14 08:52:27', '2025-04-17 08:22:29'),
(2, 12, 16, 'we will inform you soon', '2025-04-15 18:13:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `relaxation_activities`
--

CREATE TABLE `relaxation_activities` (
  `id` int(11) NOT NULL,
  `activity_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `playlist_url` varchar(255) DEFAULT NULL,
  `stress_level` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `relaxation_activities`
--

INSERT INTO `relaxation_activities` (`id`, `activity_name`, `description`, `image_url`, `playlist_url`, `stress_level`) VALUES
(83, 'Deep Breathing Exercises', 'BreatheEasy is a delightful mobile and web-based game designed to guide users through deep breathing exercises. This game transforms stress-relieving breathing into a calming adventure, making it an ideal companion for low-stress relaxation.', 'breathing-exercise-concept-illustration_114360-9010 (1).avif', 'https://g.co/kgs/dC6ZhyW', 'low'),
(84, 'Listening to Calming Music', 'This YouTube video, \'The Most Relaxing Waves Ever – Ocean Sounds to Sleep, Study and Chill,\' offers a serene soundscape of ocean waves. Perfect for relaxation, focus, or sleep, it features high-quality ambient recordings ideal for calming activities like deep breathing.', 'images (1).jfif', 'https://www.youtube.com/watch?v=lFcSrYw-ARY', 'low'),
(85, 'Gentle Stretching', 'A 15-min guided gentle stretching video for low stress relief. A yoga instructor leads neck rolls and shoulder shrugs in a calm setting with soft music. Great for beginners, it boosts blood flow and eases tension. Ideal for a daily routine on RelaxU to maintain relaxation and flexibility.', 'online-sport-classes-person-doing-stretching-exercises_23-2148824309.avif', 'https://youtu.be/lNPaDikuRhU?si=sVascpeWGNnfNzCi', 'low'),
(86, 'Mindful Coloring', 'A free mandala coloring page for low-stress relief at ColoringBook.pics. The circular design promotes mindful coloring to ease anxiety. Ideal for 5-15 min, it enhances focus and calm. Great for beginners on RelaxU, using pencils or crayons for a meditative escape.', 'pngtree-mandala-art-design-vector-png-image_13908327.png', 'https://coloringbook.pics/antistress-coloring-pages/mandala/#coloring', 'low'),
(87, 'Sipping Herbal Tea Mindfully', 'Brew chamomile tea & sip slowly for 5-10 min, savoring its warmth & aroma. This mindful ritual on RelaxU eases low stress, grounding you in the moment. Ideal for a quick, calming break, it promotes relaxation with chamomile’s natural soothing effects. Perfect for beginners seeking calm & wellness daily.', 'images.jfif', 'https://senbirdtea.com/blogs/health/6-tips-on-mindful-tea-drinking', 'low'),
(88, 'Aromatherapy', 'Learn the AromaTouch® Hand Technique step-by-step. Use doTERRA oils to master 5 moves that relax hands, ease stress, and boost well-being. Perfect for beginners, this 5-10 min method fosters connection. Try oils like Lavender or Balance for best results. Follow along to enhance wellness with ease.', 'hand-drawn-aromatherapy-element-set_23-2148813979.avif', 'https://youtu.be/i_448mpgLO0?si=biVFxLTTssty67RS', 'low'),
(89, 'Guided Meditation', 'Tamara Levitt leads a 10-min Daily Calm mindfulness meditation to restore inner peace and reconnect with the present. Her gentle guidance helps release stress and boost focus, offering a calming escape into mindful tranquility. Ideal for moderate stress relief on RelaxU.', 'meditation-clipart.webp', 'https://youtu.be/ZToicYcHIOU?si=IB9dkFkpp-P3FPo6', 'moderate'),
(90, 'Progressive Muscle Relaxation', 'This 15-minute guided Progressive Muscle Relaxation video helps ease moderate stress and anxiety. Led by a therapist, it uses deep breathing and muscle exercises in a calming setting with soft narration. Ideal for nervous system regulation and daily relaxation.', 'stretching-exercises-concept-illustration_114360-9012.avif', 'https://youtu.be/SNqYG95j_UQ?si=q6kcMlTimjlcT8qt', 'moderate'),
(91, 'Board Game', 'Playing light board games like Sushi Go or Uno with friends or family is a fun, low-stress way to stay social. Enjoy cozy game nights with snacks, laughs, and easy rules. It’s perfect for unwinding together and strengthening bonds in a relaxed, low-pressure setting.', 'group-of-four-friends-enjoying-a-board-game-night-in-a-flat-design-illustration-vector.jpg', 'https://en.boardgamearena.com/welcome', 'moderate'),
(92, 'Brisk Walk', 'Take a walk in the park or around your neighborhood at a steady, comfortable pace. The fresh air, light movement, and peaceful surroundings help lift your mood and clear your mind. It’s a simple, low-effort way to relax and reduce stress without leaving you feeling overwhelmed.', '680ce8323721b_download (1).jfif', 'https://youtu.be/tVpUCkMLgms?si=QhS7akt9YDPSAVYA', 'moderate');

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
(3, 3, 1, 3, 'Mark is friendly, but the advice should have been more specific.', '2025-01-24 14:44:37'),
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
-- Table structure for table `stress_assessment`
--

CREATE TABLE `stress_assessment` (
  `assessment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `section1_q1` int(11) NOT NULL,
  `section1_q2` int(11) NOT NULL,
  `section1_q3` int(11) NOT NULL,
  `section1_q4` int(11) NOT NULL,
  `section1_q5` int(11) NOT NULL,
  `section2_q1` int(11) NOT NULL,
  `section2_q2` int(11) NOT NULL,
  `section2_q3` int(11) NOT NULL,
  `section2_q4` int(11) NOT NULL,
  `section2_q5` int(11) NOT NULL,
  `section1_score` int(11) NOT NULL,
  `section2_score` int(11) NOT NULL,
  `stress_level` varchar(20) NOT NULL,
  `assessment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stress_assessment`
--

INSERT INTO `stress_assessment` (`assessment_id`, `user_id`, `section1_q1`, `section1_q2`, `section1_q3`, `section1_q4`, `section1_q5`, `section2_q1`, `section2_q2`, `section2_q3`, `section2_q4`, `section2_q5`, `section1_score`, `section2_score`, `stress_level`, `assessment_date`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 2, 2, 2, 2, 3, 2, 2, 2, 4, 11, 13, 'Moderate', '2025-04-07 07:40:27', '2025-04-07 07:40:27', '2025-04-07 07:40:27'),
(2, 1, 2, 2, 2, 2, 2, 3, 2, 2, 2, 3, 10, 12, 'Moderate', '2025-04-07 07:44:09', '2025-04-07 07:44:09', '2025-04-07 07:44:09'),
(3, 1, 1, 2, 2, 3, 1, 3, 2, 2, 2, 3, 9, 12, 'Low', '2025-04-07 07:52:41', '2025-04-07 07:52:41', '2025-04-07 07:52:41');

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
(1, 'student1', 'student1@example.com', '0775889652', '1st Year', '123', 'student', '2024-09-15 13:52:10'),
(2, 'admin1', 'admin1@example.com', '0778552146', NULL, '123', 'admin', '2024-09-15 13:52:10'),
(3, 'superadmin1', 'superadmin1@example.com', '0778563254', NULL, '123', 'super_admin', '2024-09-15 13:52:10'),
(4, 'student2', 'ovindumandith@gmail.com', '0703871009', '1', '111111111', 'student', '2024-09-22 20:53:12'),
(5, 'hous1', 'hou1@gmail.com', '0776558235', NULL, '123', 'hous', '2025-02-10 03:34:08'),
(6, 'lecturerMark', 'lecturerm@gmail.com', '0775263255', NULL, '123', 'lecturer', '2025-04-08 08:04:26'),
(7, 'amara_perera', 'amara.perera@ucsc.edu', '0771234567', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(8, 'nimal_fernando', 'nimal.fernando@ucsc.edu', '0771234568', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(9, 'lakshman_wijekoon', 'lak.wijekoon@ucsc.edu', '0771234569', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(10, 'dilani_j', 'dilani.j@eng.edu', '0771234570', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(11, 'rohan_silva', 'rohan.silva@eng.edu', '0771234571', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(12, 'priyanthi_g', 'priyanthi.g@eng.edu', '0771234572', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(13, 'anura_b', 'anura.b@sci.edu', '0771234573', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(14, 'chandima_r', 'chandima.r@sci.edu', '0771234574', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(15, 'sunil_p', 'sunil.p@sci.edu', '0771234575', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(16, 'kumari_d', 'kumari.d@arts.edu', '0771234576', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(17, 'ajith_k', 'ajith.k@arts.edu', '0771234577', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(18, 'ranjith_s', 'ranjith.s@med.edu', '0771234578', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(19, 'chamari_w', 'chamari.w@med.edu', '0771234579', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(20, 'tharaka_g', 'tharaka.g@bus.edu', '0771234580', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(21, 'deepani_a', 'deepani.a@bus.edu', '0771234581', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(22, 'lalith_m', 'lalith.m@law.edu', '0771234582', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(23, 'gayani_p', 'gayani.p@law.edu', '0771234583', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(24, 'wasantha_r', 'wasantha.r@ucsc.edu', '0771234584', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(25, 'mala_s', 'mala.s@sci.edu', '0771234585', NULL, '123', 'lecturer', '2025-04-08 08:13:10'),
(26, 'indika_k', 'indika.k@eng.edu', '0771234586', NULL, '123', 'lecturer', '2025-04-08 08:13:10');

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
-- Indexes for table `academic_question_response`
--
ALTER TABLE `academic_question_response`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `fk_question_id` (`question_id`),
  ADD KEY `fk_admin_id` (`admin_id`);

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
-- Indexes for table `forwarded_questions`
--
ALTER TABLE `forwarded_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `lecturer_id` (`lecturer_id`),
  ADD KEY `forwarded_by` (`forwarded_by`);

--
-- Indexes for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`module_code`),
  ADD UNIQUE KEY `module_name` (`module_name`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_user_created` (`user_id`,`created_at`);

--
-- Indexes for table `question_replies`
--
ALTER TABLE `question_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `stress_assessment`
--
ALTER TABLE `stress_assessment`
  ADD PRIMARY KEY (`assessment_id`),
  ADD KEY `user_id` (`user_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `academic_question_response`
--
ALTER TABLE `academic_question_response`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `counselors`
--
ALTER TABLE `counselors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `forwarded_questions`
--
ALTER TABLE `forwarded_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lecturers`
--
ALTER TABLE `lecturers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `question_replies`
--
ALTER TABLE `question_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `relaxation_activities`
--
ALTER TABLE `relaxation_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `schedule_events`
--
ALTER TABLE `schedule_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `stress_assessment`
--
ALTER TABLE `stress_assessment`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stress_management_responses`
--
ALTER TABLE `stress_management_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_questions`
--
ALTER TABLE `academic_questions`
  ADD CONSTRAINT `fk_academic_questions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `academic_question_response`
--
ALTER TABLE `academic_question_response`
  ADD CONSTRAINT `fk_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_question_id` FOREIGN KEY (`question_id`) REFERENCES `academic_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_appointments_counselor` FOREIGN KEY (`counselor_id`) REFERENCES `counselors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_appointments_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `forwarded_questions`
--
ALTER TABLE `forwarded_questions`
  ADD CONSTRAINT `forwarded_questions_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `academic_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forwarded_questions_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forwarded_questions_ibfk_3` FOREIGN KEY (`forwarded_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD CONSTRAINT `lecturers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `question_replies`
--
ALTER TABLE `question_replies`
  ADD CONSTRAINT `question_replies_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `academic_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `question_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_counselor` FOREIGN KEY (`counselor_id`) REFERENCES `counselors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `stress_assessment`
--
ALTER TABLE `stress_assessment`
  ADD CONSTRAINT `stress_assessment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `stress_management_responses`
--
ALTER TABLE `stress_management_responses`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
