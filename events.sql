-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2026 at 04:22 PM
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
-- Database: `events`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_content`
--

CREATE TABLE `about_content` (
  `about_id` int(11) NOT NULL,
  `section_key` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_content`
--

INSERT INTO `about_content` (`about_id`, `section_key`, `title`, `content`, `image_path`, `updated_at`) VALUES
(1, 'hero', 'About Sustainable Events', 'We think that little things can make a big difference. Through our website, enthusiastic people may find meaningful sustainability events in their local areas.', NULL, '2026-03-14 10:27:55'),
(2, 'platform', 'Our Platform', 'Our platform connects the planning, participation, and monitoring of sustainable events by APU students and organizers. Administrators ensure overall sustainability performance, organizers oversee events, and students document eco-friendly activities. Together, we raise awareness of campus-based sustainability projects and encourage involvement.', 'images/about/pingu.jpg', '2026-03-14 10:57:02'),
(3, 'mission', 'Our Mission', 'Our goal is to encourage eco-friendly event planning and active involvement in order to work toward sustainable practices on the APU campus. Our goal is to create a platform where administrators, organizers, and students can work together to track sustainability performance, cut waste, and significantly improve the environment.', 'images/about/pingu.jpg', '2026-03-14 10:57:02'),
(4, 'commitment', 'Sustainability Commitment', 'Through every facet of our platform, we are dedicated to promoting environmentally friendly practices. This involves giving administrators the tools to monitor and assess sustainability results, assisting event planners in creating eco-friendly events, and asking participants to document and enhance their eco-friendly behavior. We work to establish a long-lasting sustainable culture on campus by encouraging awareness and accountability.', 'images/about/pingu.jpg', '2026-03-14 10:57:02'),
(5, 'green_points', 'About Green Points System', 'Participate in sustainable activities to earn Green Points, which you can then exchange for environmentally beneficial prizes.', NULL, '2026-03-14 10:27:55'),
(6, 'redeem_intro', 'Redeem Your Points For:', 'Rewards that encourage eco-friendly participation and sustainable habits.', NULL, '2026-03-14 10:27:55');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `event_location` varchar(255) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sustainability_goals` text DEFAULT NULL,
  `event_image` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated_at` timestamp NULL DEFAULT NULL,
  `update_notice` varchar(255) DEFAULT NULL,
  `show_update_notice` tinyint(1) DEFAULT 0,
  `waste_report` text DEFAULT NULL,
  `organizer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `event_date`, `event_time`, `event_location`, `event_type`, `max_participants`, `description`, `sustainability_goals`, `event_image`, `status`, `created_at`, `last_updated_at`, `update_notice`, `show_update_notice`, `waste_report`, `organizer_id`) VALUES
(6, 'Test 1', '2026-01-04', '15:00:00', 'Klang', 'Clean', 20, 'Clear Beach', '500kg', '1774359040_apu background.jpg', 'Approved', '2026-03-24 13:30:40', '2026-03-26 09:30:27', 'An approved event was updated by the organizer.', 1, NULL, 13),
(7, 'Test 2', '2026-02-01', '12:00:00', 'APU', 'Clean', 100, 'test', '500kg', '', 'Approved', '2026-03-24 16:53:55', '2026-03-26 16:40:29', 'An approved event was updated by the organizer.', 1, NULL, 13),
(8, 'Test 3 ', '2026-03-24', '12:00:00', 'APU', '123', 100, 'test', '500kg', '', 'Rejected', '2026-03-24 16:54:33', NULL, NULL, 0, 'Waste Type: vghvhj\nQuantity: 3455\nCollected: vbygiuguhh\nCollection Method: uhgio\nDisposal Method: uoigi', 13),
(9, 'dnnwhdiwd', '2026-03-31', '18:00:00', 'idjwiqdjhqiod', 'qwndqwdio', 69, 'iddqwindn', 'snmkwdw', '1774517045_apu background.jpg', 'Approved', '2026-03-26 09:24:05', '2026-03-27 14:50:29', 'An approved event was updated by the organizer.', 1, NULL, 13);

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE `event_participants` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `participation_status` varchar(50) NOT NULL DEFAULT 'joined',
  `attendance_status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_participants`
--

INSERT INTO `event_participants` (`id`, `event_id`, `user_id`, `joined_at`, `participation_status`, `attendance_status`) VALUES
(31, 7, 10, '2026-03-30 09:20:56', 'cancelled', 'pending'),
(32, 6, 10, '2026-03-26 09:30:59', 'joined', 'absent'),
(33, 6, 14, '2026-03-26 09:32:36', 'joined', 'present'),
(34, 9, 10, '2026-03-29 13:18:18', 'cancelled', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `event_photos`
--

CREATE TABLE `event_photos` (
  `photo_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_photos`
--

INSERT INTO `event_photos` (`photo_id`, `event_id`, `user_id`, `image_path`, `uploaded_at`) VALUES
(4, 6, 10, 'uploads/images/1774507215_pingu.jpg', '2026-03-26 06:40:15'),
(6, 6, 10, 'uploads/images/69c552836c125_ocean_sweep.jpg', '2026-03-26 15:36:35'),
(7, 6, 14, 'uploads/images/69c562dce0a8a_workshop.jpg', '2026-03-26 16:46:20');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `feedback_text` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `event_id`, `rating`, `feedback_text`, `submitted_at`, `user_email`) VALUES
(6, 6, 5, 'nice event haha', '2026-03-27 06:22:37', 'Zayden@gmail.com'),
(14, 6, 5, 'asdasascasmasjdqjdasascasas', '2026-03-28 07:04:32', 'boonyong0410@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `event_id`, `title`, `message`, `is_read`, `created_at`) VALUES
(36, 10, 4, 'Event Joined Successfully', 'You have successfully joined hi.', 0, '2026-03-23 04:31:57'),
(37, 10, 1, 'Event Joined Successfully', 'You have successfully joined hi.', 0, '2026-03-23 06:14:44'),
(38, 10, 6, 'Event Joined Successfully', 'You have successfully joined Test 1.', 0, '2026-03-24 16:17:21'),
(39, 14, 6, 'Event Joined Successfully', 'You have successfully joined Test 1.', 0, '2026-03-24 16:59:21'),
(40, 14, 1, 'Event Joined Successfully', 'You have successfully joined hi.', 0, '2026-03-24 18:55:54'),
(41, 10, 7, 'Event Joined Successfully', 'You have successfully joined Test 2.', 0, '2026-03-25 08:37:01'),
(42, 10, 6, 'Event Joined Successfully', 'You have successfully joined Test 1.', 0, '2026-03-25 15:05:04'),
(43, 14, 6, 'Event Joined Successfully', 'You have successfully joined Test 1.', 0, '2026-03-26 09:32:36'),
(44, 10, 9, 'Event Joined Successfully', 'You have successfully joined dnnwhdiwd.', 0, '2026-03-27 07:17:39'),
(45, 10, 6, 'Feedback Submitted Successfully', 'You submitted feedback for Test 1 and earned 5 green points.', 0, '2026-03-27 07:42:37'),
(46, 10, NULL, 'Reward Redeemed Successfully', 'You redeemed Voucher for 200 green points.', 0, '2026-03-27 08:07:45'),
(47, 10, 6, 'Feedback Submitted Successfully', 'You submitted feedback for Test 1 and earned 5 green points.', 0, '2026-03-27 08:11:40'),
(48, 10, 6, 'Feedback Submitted Successfully', 'You submitted feedback for Test 1 and earned 5 green points.', 0, '2026-03-27 08:17:42'),
(49, 10, 6, 'Feedback Submitted Successfully', 'You submitted feedback for Test 1 and earned 5 green points.', 0, '2026-03-27 08:21:22'),
(50, 10, 9, 'Event Time Changed', 'The time for dnnwhdiwd has been changed. Please check the updated event details.', 0, '2026-03-27 09:40:47'),
(51, 10, 9, 'Event Date Changed', 'The date for dnnwhdiwd has been changed. Please check the updated event details.', 0, '2026-03-27 09:46:10'),
(52, 10, 9, 'Event Date Changed', 'The date for dnnwhdiwd has been changed. Please check the updated event details.', 0, '2026-03-27 09:48:55'),
(53, 10, 9, 'Event Date Changed', 'The date for dnnwhdiwd has been changed. Please check the updated event details.', 0, '2026-03-27 09:49:04'),
(54, 10, 9, 'Event Date Changed', 'The date for dnnwhdiwd has been changed. Please check the updated event details.', 0, '2026-03-27 14:24:30'),
(55, 10, 6, 'Feedback Submitted Successfully', 'You submitted feedback for Test 1 and earned 5 green points.', 0, '2026-03-27 14:49:35'),
(56, 10, 9, 'Event Date Changed', 'The date for dnnwhdiwd has been changed. Please check the updated event details.', 0, '2026-03-27 14:50:29'),
(57, 10, 6, 'Feedback Submitted Successfully', 'You submitted feedback for Test 1 and earned 5 green points.', 0, '2026-03-28 05:33:44'),
(58, 10, 6, 'Feedback Submitted Successfully', 'You submitted feedback for Test 1 and earned 5 green points.', 0, '2026-03-28 07:04:32'),
(59, 10, NULL, 'Reward Redeemed Successfully', 'You redeemed Recycle Bag for 200 green points.', 0, '2026-03-28 15:46:16');

-- --------------------------------------------------------

--
-- Table structure for table `pointsdistribution`
--

CREATE TABLE `pointsdistribution` (
  `id` int(11) NOT NULL,
  `points_activity` varchar(255) NOT NULL,
  `points_description` varchar(255) NOT NULL,
  `points_points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pointsdistribution`
--

INSERT INTO `pointsdistribution` (`id`, `points_activity`, `points_description`, `points_points`) VALUES
(1, 'Event Completion', 'Attend an event', 10),
(2, 'Feedback Submission', 'Providing event feedback', 5);

-- --------------------------------------------------------

--
-- Table structure for table `pointshistory`
--

CREATE TABLE `pointshistory` (
  `pointsHistory_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pointsHistory_activity` varchar(255) NOT NULL,
  `pointsHistory_points` int(11) NOT NULL,
  `pointsHistory_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pointshistory`
--

INSERT INTO `pointshistory` (`pointsHistory_id`, `user_id`, `pointsHistory_activity`, `pointsHistory_points`, `pointsHistory_time`) VALUES
(1, 10, 'Redeemed reward: Recycle Bag', -200, '2026-03-23 16:04:35'),
(13, 10, 'Event Completion: Test 2', 10, '2026-03-26 00:46:09'),
(15, 14, 'Event Completion: Test 1', 10, '2026-03-26 17:33:27'),
(20, 10, 'Redeemed reward: Voucher', -200, '2026-03-27 16:00:02'),
(21, 10, 'Redeemed reward: Voucher', -200, '2026-03-27 16:07:45'),
(25, 10, 'Event Completion: dnnwhdiwd', 10, '2026-03-27 22:24:37'),
(26, 10, 'Feedback submitted for Test 1', 5, '2026-03-27 22:49:35'),
(27, 10, 'Feedback submitted for Test 1', 5, '2026-03-28 13:33:44'),
(28, 10, 'Feedback submitted for Test 1', 5, '2026-03-28 15:04:32'),
(29, 10, 'Redeemed reward: Recycle Bag', -200, '2026-03-28 23:46:16');

-- --------------------------------------------------------

--
-- Table structure for table `reward`
--

CREATE TABLE `reward` (
  `reward_id` int(11) NOT NULL,
  `reward_name` varchar(255) NOT NULL,
  `reward_points` int(11) NOT NULL,
  `reward_quantity` int(11) NOT NULL,
  `reward_image` varchar(255) NOT NULL,
  `reward_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reward`
--

INSERT INTO `reward` (`reward_id`, `reward_name`, `reward_points`, `reward_quantity`, `reward_image`, `reward_status`) VALUES
(7, 'Recycle Bag', 200, 97, 'recycle bag.jpeg', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `reward_redemptions`
--

CREATE TABLE `reward_redemptions` (
  `redemption_id` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `points_used` int(11) NOT NULL,
  `redeemed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(100) NOT NULL,
  `gmail` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reward_redemptions`
--

INSERT INTO `reward_redemptions` (`redemption_id`, `reward_id`, `points_used`, `redeemed_at`, `created_at`, `username`, `gmail`) VALUES
(9, 2, 200, '2026-03-09 19:13:38', '2026-03-09 19:13:38', '', ''),
(13, 4, 300, '2026-03-10 17:50:54', '2026-03-10 17:50:54', '', ''),
(14, 2, 200, '2026-03-11 01:44:45', '2026-03-11 01:44:45', '', ''),
(15, 2, 200, '2026-03-17 09:05:57', '2026-03-17 09:05:57', '', ''),
(16, 2, 200, '2026-03-17 09:06:02', '2026-03-17 09:06:02', '', ''),
(17, 1, 500, '2026-03-18 08:40:31', '2026-03-18 08:40:31', '', ''),
(19, 7, 200, '2026-03-23 07:57:00', '2026-03-23 07:57:00', 'WenYong', 'boonyong0410@gmail.com'),
(20, 7, 200, '2026-03-23 08:04:35', '2026-03-23 08:04:35', 'WenYong', 'boonyong0410@gmail.com'),
(21, 8, 200, '2026-03-27 08:00:02', '2026-03-27 08:00:02', 'WenYong', 'boonyong0410@gmail.com'),
(22, 8, 200, '2026-03-27 08:07:45', '2026-03-27 08:07:45', 'WenYong', 'boonyong0410@gmail.com'),
(23, 7, 200, '2026-03-28 15:46:16', '2026-03-28 15:46:16', 'WenYong', 'boonyong0410@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_fullname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_username` varchar(255) NOT NULL,
  `user_phoneNumber` varchar(50) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_profilePicture` varchar(255) DEFAULT NULL,
  `user_role` varchar(20) NOT NULL,
  `user_point` int(11) NOT NULL,
  `user_organization` varchar(255) DEFAULT NULL,
  `user_reason` varchar(255) DEFAULT NULL,
  `user_document` varchar(255) DEFAULT NULL,
  `user_status` varchar(255) NOT NULL,
  `user_registerDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_fullname`, `user_email`, `user_username`, `user_phoneNumber`, `user_password`, `user_profilePicture`, `user_role`, `user_point`, `user_organization`, `user_reason`, `user_document`, `user_status`, `user_registerDate`) VALUES
(3, 'Chai Shi Xuen', 'jessicacsx0118@gmail.com', 'sxuen', '0176869989', '$2y$10$QY56jtnNuuwqV2hrf0mnce0n2JonKGNvtRAn5Sr1jtvFEPRf.8nrm', 'defaultProfile.png', 'Admin', 0, '', '', '', 'Active', '2026-03-19 13:45:27'),
(6, 'Yee Tze Ying', 'yingtyee@gmail.com', 'ying', '0123456789', '$2y$10$fU0cw.37hmXklhONf7WAWOQCynnbgxbMuynTIScJNp.P/eOehAb3O', 'defaultProfile.png', 'Event Organizer', 0, 'ABC Organization', 'Want to attract more customer', 'Lab 1 - Introduction to HTML (Part 1) (1).pdf', 'Deactivated', '2026-03-19 14:10:32'),
(7, 'Wong Jun Xi', 'izwjx21@gmail.com', 'junxii', '0123456789', '$2y$10$CGNO2s32udw.5BpP8jtGfOrICCmS1FKNhx6GHEYShyTsTB3L5HZAC', 'defaultProfile.png', 'Event Organizer', 0, 'WJX Organization', 'Want to attract more customerssss', 'Lab 2 - Introduction to HTML (Part 2).pdf', 'Rejected', '2026-03-19 14:11:32'),
(8, 'wdwxd', 'dwd@gmail.com', 'eded', '012073927387', '$2y$10$P.wBw3eKuyQp03L/sknhMuxBxbaSJu1P7zViHJvPOy2nBrPWDIdoW', 'defaultProfile.png', 'Event Organizer', 0, 'sdn Organization', 'djfncj', 'RWDD Assignment.pdf', 'Active', '2026-03-19 14:12:19'),
(10, 'WenYong', 'boonyong0410@gmail.com', 'wenyong', '0123456789', '$2y$10$.dMzO1rS6nw3TJG1/bcSluhHvIiNtuVRMD/gUkA9SmFDrTiwEKgOy', 'defaultProfile.png', 'Participant', 105, '', '', '', 'Active', '2026-03-23 01:00:21'),
(13, 'Lee Kenny', 'kennylee3k@gmail.com', 'Kenny', '0193137875', '$2y$10$nvMroIzXSnAQPLq2hlApWuchVu7pbnDt0UGvR110DyVUY4zW1oX6i', 'defaultProfile.png', 'Event Organizer', 0, 'DR Kenny Snd Bhd ', 'Want to create more activity to participant', 'Lab 2 - Introduction to HTML (Part 2).pdf', 'Active', '2026-03-23 12:49:12'),
(14, 'Zayden', 'Zayden@gmail.com', 'Zayden is Black', '0123456789', '$2y$10$rkeIYRYck9GR4MgfIth2ee/u1rH8H8y2S0xPTxvGAldNdMSyNuvLi', 'defaultProfile.png', 'Participant', 35, '', '', '', 'Active', '2026-03-25 00:58:24'),
(15, 'elknfnnkflk', 'asjsdn@gmail.com', 'hgvhvg', '02832434348', '$2y$10$f4qW73nLWb9KOuiD4.JmruKqX4TUbLrUqM4rPvdeI4yiTMMyw42P6', 'defaultProfile.png', 'Participant', 0, '', '', '', 'Active', '2026-03-26 17:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `waste_reports`
--

CREATE TABLE `waste_reports` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `waste_type` varchar(255) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `collected` decimal(10,2) NOT NULL,
  `collection_method` varchar(255) NOT NULL,
  `disposal_method` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_reports`
--

INSERT INTO `waste_reports` (`id`, `event_id`, `waste_type`, `quantity`, `collected`, `collection_method`, `disposal_method`, `created_at`) VALUES
(1, 7, 'hdowediowd', 6.70, 13123.00, 'dkknwked', 'kdwndlkw', '2026-03-26 08:03:32'),
(2, 9, 'hhihoihio', 6.70, 8768686.00, 'jguguig', 'jkhihin', '2026-03-26 09:25:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_content`
--
ALTER TABLE `about_content`
  ADD PRIMARY KEY (`about_id`),
  ADD UNIQUE KEY `section_key` (`section_key`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `participant_id` (`user_id`);

--
-- Indexes for table `event_photos`
--
ALTER TABLE `event_photos`
  ADD PRIMARY KEY (`photo_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `pointsdistribution`
--
ALTER TABLE `pointsdistribution`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pointshistory`
--
ALTER TABLE `pointshistory`
  ADD PRIMARY KEY (`pointsHistory_id`);

--
-- Indexes for table `reward`
--
ALTER TABLE `reward`
  ADD PRIMARY KEY (`reward_id`);

--
-- Indexes for table `reward_redemptions`
--
ALTER TABLE `reward_redemptions`
  ADD PRIMARY KEY (`redemption_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `waste_reports`
--
ALTER TABLE `waste_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_content`
--
ALTER TABLE `about_content`
  MODIFY `about_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `event_photos`
--
ALTER TABLE `event_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `pointsdistribution`
--
ALTER TABLE `pointsdistribution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pointshistory`
--
ALTER TABLE `pointshistory`
  MODIFY `pointsHistory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `reward`
--
ALTER TABLE `reward`
  MODIFY `reward_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reward_redemptions`
--
ALTER TABLE `reward_redemptions`
  MODIFY `redemption_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `waste_reports`
--
ALTER TABLE `waste_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `waste_reports`
--
ALTER TABLE `waste_reports`
  ADD CONSTRAINT `waste_reports_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
