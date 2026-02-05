-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 05, 2026 at 01:37 PM
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
-- Database: `cyber_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `title`, `content`) VALUES
(1, 'Password Security Basics', 'A strong password is your first line of defense against unauthorized access.\n\nKey rules:\n- Minimum 12–16 characters\n- Mix uppercase, lowercase, numbers, and symbols\n- Avoid common words, names, or dates\n- Use a password manager for unique passwords per site\n\nBad example: password123\nGood example: Tr0ub4dor&3xtr3m3!'),
(2, 'Phishing Awareness', 'Phishing is a common cyber attack where attackers pretend to be trustworthy entities (e.g., banks, companies) to trick you into revealing sensitive information like passwords or credit card details.\n\nCommon signs:\n- Urgent language (\"Your account will be locked in 24 hours!\")\n- Suspicious sender email (e.g., support@yourbank-secure.com instead of @yourbank.com)\n- Requests for personal info or clicking unknown links\n- Poor grammar/spelling in official-looking emails\n\nBest defense: Never click links in unexpected emails. Type the URL manually or use bookmarks.'),
(3, 'Two-Factor Authentication (2FA)', 'Two-Factor Authentication adds an extra layer of security beyond just a password.\n\nTypes:\n- Something you know (password)\n- Something you have (phone/app for code)\n- Something you are (biometric)\n\nCommon methods:\n- SMS code (less secure)\n- Authenticator apps (Google Authenticator, Authy – more secure)\n- Hardware keys (YubiKey – best)\n\nAlways enable 2FA on important accounts (email, banking, social media).');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `options` text DEFAULT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `type` enum('mcq','short') DEFAULT 'mcq'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `lesson_id`, `question`, `options`, `correct_answer`, `type`) VALUES
(2, 1, 'What is the minimum length for a strong password?', 'A: 6 characters,B: 8 characters,C: 12 characters,D: 4 characters', 'C', 'mcq'),
(3, 1, 'Which of these is the BEST example of a strong password?', 'A: ilovemy dog,B: P@ssw0rd,C: Tr0ub4dor&3xtr3m3!,D: 1234567890', 'C', 'mcq'),
(4, 1, 'Why should you avoid using the same password on multiple sites?', 'A: It saves time,B: One breach can compromise many accounts,C: It makes passwords stronger,D: Websites require it', 'B', 'mcq'),
(5, 1, 'What is the recommended minimum length for a strong password in most security guidelines?', 'A: 6 characters,B: 8 characters,C: 12 characters,D: 16 characters', 'C', 'mcq'),
(6, 1, 'Which of these is the strongest password?', 'A: password123,B: Summer2025,C: Tr0ub4dor&3xtr3m3!,D: qwertyuiop', 'C', 'mcq'),
(8, 2, 'What is a common red flag of a phishing email?', 'A: It comes from your boss,B: It creates urgency and asks you to click a link quickly,C: It has perfect grammar,D: It includes your full name', 'B', 'mcq'),
(9, 2, 'What should you do if you receive an unexpected email asking for your password?', 'A: Reply and give it,B: Click the link to verify,C: Ignore or report it as phishing,D: Forward it to friends', 'C', 'mcq'),
(10, 2, 'How would you verify if an email is legitimate before clicking any links?', 'A: Click the link to check,B: Hover over the link to see the real URL,C: Reply asking for more info,D: Forward it to friends', 'B', 'mcq'),
(11, 3, 'Which 2FA method is generally considered the most secure?', 'A: SMS text message,B: Email code,C: Authenticator app (TOTP),D: Security questions', 'C', 'mcq'),
(12, 3, 'Why is SMS-based 2FA less secure than app-based 2FA?', 'A: SMS is free,B: SMS can be intercepted via SIM swapping,C: Apps are slower,D: SMS works offline', 'B', 'mcq'),
(13, 3, 'Which is one popular authenticator app you can use for 2FA?', 'A: Google Authenticator, B: WhatsApp Auth,C: CyberLearn Auth,D: None of the above.', 'A', 'mcq'),
(14, 3, 'True or False: Enabling 2FA makes your account completely unhackable.', 'A: True,B: False', 'B', 'mcq'),
(16, 2, 'What is one safe way to check if an email link is legitimate?', 'A: Click it to see,B: Hover over the link to see the real URL,C: Reply to the email asking,D: Forward to friends', 'B', 'mcq'),
(17, 3, 'Which is a popular authenticator app for 2FA?', 'A: WhatsApp,B: Google Authenticator,C: Notepad,D: Calculator', 'B', 'mcq');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT 'default_avatar.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `profile_image`) VALUES
(1, 'Mark_OGrin', '$2y$10$Ia3B4c.PosX4Wp2yFw6kbuYPd6SCYFagobsSu3gyUjf2h5CuZnr.q', 'oyinmark@gmail.com', '2026-01-23 20:38:08', 'uploads/1_profile.jpg'),
(2, 'Paul', '$2y$10$XGbkbAETFFDX/96mlEZSZeQeqrTA0BExw5P4W3ozGeE4z91OVc0Tm', 'PaulBarnes@proton.me', '2026-01-31 19:22:00', 'default_avatar.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE `user_answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_answers`
--

INSERT INTO `user_answers` (`id`, `user_id`, `question_id`, `answer`, `is_correct`, `submitted_at`) VALUES
(35, 1, 1, 'C', 1, '2026-01-28 23:00:16'),
(36, 1, 2, 'C', 1, '2026-01-28 23:00:16'),
(37, 1, 3, 'C', 1, '2026-01-28 23:00:16'),
(38, 1, 4, 'B', 0, '2026-01-28 23:00:16'),
(39, 1, 5, 'C', 1, '2026-01-28 23:00:16'),
(40, 1, 6, 'C', 1, '2026-01-28 23:00:16'),
(41, 1, 7, 'B', 0, '2026-01-28 23:00:16'),
(42, 1, 15, 'B', 1, '2026-01-28 23:00:16'),
(43, 1, 8, 'B', 1, '2026-01-31 16:21:15'),
(44, 1, 9, 'C', 1, '2026-01-31 16:21:15'),
(45, 1, 10, '', 0, '2026-01-31 16:21:15'),
(46, 1, 16, 'B', 1, '2026-01-31 16:21:15'),
(47, 1, 8, 'B', 1, '2026-01-31 16:45:42'),
(48, 1, 9, 'C', 1, '2026-01-31 16:45:42'),
(49, 1, 10, 'B', 1, '2026-01-31 16:45:42'),
(50, 1, 16, 'B', 1, '2026-01-31 16:45:42'),
(51, 1, 11, 'C', 1, '2026-01-31 16:48:38'),
(52, 1, 12, 'B', 1, '2026-01-31 16:48:38'),
(53, 1, 13, 'A', 1, '2026-01-31 16:48:38'),
(54, 1, 14, 'B', 1, '2026-01-31 16:48:38'),
(55, 1, 17, 'B', 1, '2026-01-31 16:48:38'),
(56, 1, 1, 'C', 1, '2026-01-31 16:52:38'),
(57, 1, 2, 'C', 1, '2026-01-31 16:52:38'),
(58, 1, 3, 'C', 1, '2026-01-31 16:52:38'),
(59, 1, 4, 'B', 1, '2026-01-31 16:52:38'),
(60, 1, 5, 'C', 1, '2026-01-31 16:52:38'),
(61, 1, 6, 'C', 1, '2026-01-31 16:52:38'),
(62, 1, 7, 'B', 0, '2026-01-31 16:52:38'),
(63, 1, 15, 'B', 1, '2026-01-31 16:52:38'),
(64, 1, 2, 'C', 1, '2026-01-31 16:57:39'),
(65, 1, 3, 'C', 1, '2026-01-31 16:57:39'),
(66, 1, 4, 'B', 1, '2026-01-31 16:57:39'),
(67, 1, 5, 'C', 1, '2026-01-31 16:57:39'),
(68, 1, 6, 'C', 1, '2026-01-31 16:57:39'),
(69, 1, 7, 'B', 0, '2026-01-31 16:57:39'),
(70, 1, 15, 'B', 1, '2026-01-31 16:57:39'),
(71, 1, 2, 'C', 1, '2026-02-05 10:44:22'),
(72, 1, 3, 'C', 1, '2026-02-05 10:44:22'),
(73, 1, 4, 'B', 1, '2026-02-05 10:44:23'),
(74, 1, 5, 'C', 1, '2026-02-05 10:44:23'),
(75, 1, 6, 'C', 1, '2026-02-05 10:44:23'),
(76, 1, 8, 'B', 1, '2026-02-05 10:55:32'),
(77, 1, 9, 'C', 1, '2026-02-05 10:55:32'),
(78, 1, 10, 'B', 1, '2026-02-05 10:55:32'),
(79, 1, 16, 'B', 1, '2026-02-05 10:55:32'),
(80, 1, 2, 'C', 1, '2026-02-05 11:00:03'),
(81, 1, 3, 'C', 1, '2026-02-05 11:00:03'),
(82, 1, 4, 'B', 1, '2026-02-05 11:00:03'),
(83, 1, 5, 'C', 1, '2026-02-05 11:00:03'),
(84, 1, 6, 'C', 1, '2026-02-05 11:00:03'),
(85, 1, 2, 'C', 1, '2026-02-05 11:15:57'),
(86, 1, 3, 'C', 1, '2026-02-05 11:15:57'),
(87, 1, 4, 'B', 1, '2026-02-05 11:15:57'),
(88, 1, 5, 'C', 1, '2026-02-05 11:15:57'),
(89, 1, 6, 'C', 1, '2026-02-05 11:15:57'),
(90, 1, 8, 'B', 1, '2026-02-05 11:38:32'),
(91, 1, 9, 'C', 1, '2026-02-05 11:38:32'),
(92, 1, 10, 'B', 1, '2026-02-05 11:38:32'),
(93, 1, 16, 'B', 1, '2026-02-05 11:38:32'),
(94, 1, 11, 'C', 1, '2026-02-05 11:39:58'),
(95, 1, 12, 'B', 1, '2026-02-05 11:39:58'),
(96, 1, 13, 'A', 1, '2026-02-05 11:39:58'),
(97, 1, 14, 'B', 1, '2026-02-05 11:39:58'),
(98, 1, 17, 'B', 1, '2026-02-05 11:39:58');

-- --------------------------------------------------------

--
-- Table structure for table `user_lesson_progress`
--

CREATE TABLE `user_lesson_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_lesson_progress`
--

INSERT INTO `user_lesson_progress` (`id`, `user_id`, `lesson_id`, `completed_at`) VALUES
(1, 1, 1, '2026-02-05 11:15:57'),
(2, 1, 2, '2026-02-05 11:38:32'),
(3, 1, 3, '2026-02-05 11:39:58');

-- --------------------------------------------------------

--
-- Table structure for table `user_quiz_scores`
--

CREATE TABLE `user_quiz_scores` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `percentage` decimal(5,1) NOT NULL,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_quiz_scores`
--

INSERT INTO `user_quiz_scores` (`id`, `user_id`, `lesson_id`, `score`, `total`, `percentage`, `attempted_at`) VALUES
(1, 1, 1, 5, 5, 100.0, '2026-02-05 11:15:57'),
(2, 1, 2, 4, 4, 100.0, '2026-02-05 11:38:32'),
(3, 1, 3, 5, 5, 100.0, '2026-02-05 11:39:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_lesson_progress`
--
ALTER TABLE `user_lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_progress` (`user_id`,`lesson_id`);

--
-- Indexes for table `user_quiz_scores`
--
ALTER TABLE `user_quiz_scores`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `user_lesson_progress`
--
ALTER TABLE `user_lesson_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_quiz_scores`
--
ALTER TABLE `user_quiz_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
