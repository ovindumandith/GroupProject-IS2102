-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 06:56 PM
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
-- Table structure for table `relaxation_activities`
--

CREATE TABLE `relaxation_activities` (
  `id` int(11) NOT NULL,
  `activity_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `relaxation_activities`
--

INSERT INTO `relaxation_activities` (`id`, `activity_name`, `description`, `image_url`) VALUES
(1, 'Deep Breathing Guide', 'Take a moment to center yourself with this guided breathing exercise. Follow the visual prompts to inhale, hold, and exhale deeply, helping you to relax and reduce stress.', 'breathing-exercise-concept-illustration_114360-9010 (1).avif'),
(2, 'Soothing Sounds', 'Immerse yourself in calming sounds and music designed to ease your mind. Let soothing melodies guide you. Close your eyes, take a deep breath, and feel the stress melt away.', 'organic-flat-person-meditating-peacefully_23-2148909902.avif'),
(3, 'Muscle Relaxation', 'Release tension with this progressive muscle relaxation guide. Follow the steps to gently tense and then relax different muscle groups, promoting overall relaxation and physical calm.', 'stretching-exercises-concept-illustration_114360-9012.avif'),
(4, 'Meditation Timer', 'Set a timer for your meditation practice and focus on your breath without worrying about time. Choose a duration and let yourself meditate peacefully until a chime brings you back.', 'cartoon-business-people-meditating-illustration_23-2148924044.avif'),
(5, 'Yoga Poses', 'Take a moment to flow through a series of gentle yoga poses. Focus on your breath as you stretch, strengthen, and find balance, allowing yourself to relax and rejuvenate your body.', 'vector-woman-in-yoga-pose.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `relaxation_activities`
--
ALTER TABLE `relaxation_activities`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `relaxation_activities`
--
ALTER TABLE `relaxation_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
