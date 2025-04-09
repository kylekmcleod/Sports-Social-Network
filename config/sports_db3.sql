-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2025 at 07:15 PM
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
-- Database: `sports_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `status` enum('pending','accepted','blocked') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `media_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_type` enum('image','video','audio') NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `related_table` enum('posts','comments','profiles') NOT NULL,
  `related_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('like','comment','message','friend_request','mention') NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `likes` int(11) DEFAULT 0,
  `comments` int(11) DEFAULT 0,
  `tags` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `content`, `image_url`, `created_at`, `updated_at`, `likes`, `comments`) VALUES
(17, 3, 'The big game is tomorrow! I’m so excited to see how our team performs. We’ve been training hard and it’s finally time to show what we’ve got. #GoTeam', NULL, '2025-03-10 21:00:00', '2025-03-10 21:00:00', 0, 0),
(18, 3, 'Watched an incredible soccer match today! The last-minute goal was unbelievable. That’s the kind of moment that keeps you coming back for more. #SoccerLove', NULL, '2025-03-10 21:05:00', '2025-03-10 21:05:00', 0, 0),
(19, 4, 'Finally hit a personal record on my 5k run today! It’s been a long journey, but persistence pays off. Can’t wait to push further next time! #RunnerGoals', NULL, '2025-03-10 21:10:00', '2025-03-10 21:10:00', 0, 0),
(20, 4, 'Big basketball game tonight! The energy in the arena is electric. Let’s see if we can bring home the win. I’m all in for the team! #BasketballLife', NULL, '2025-03-10 21:15:00', '2025-03-10 21:15:00', 0, 0),
(21, 5, 'Great workout at the gym today. Working on my upper body strength and feeling stronger every week. Consistency is key! #GymMotivation #Strength', NULL, '2025-03-10 21:20:00', '2025-03-10 21:20:00', 0, 0),
(22, 5, 'Had an amazing time watching the championship match. The intensity, the passion, it was all there. Sports like these inspire greatness. #ChampionshipVibes', NULL, '2025-03-10 21:25:00', '2025-03-10 21:25:00', 0, 0),
(23, 3, 'Can’t believe how many records were broken in today’s football match. It’s a reminder that the game is always evolving. Excited to see what’s next!', NULL, '2025-03-10 21:30:00', '2025-03-10 21:30:00', 0, 0),
(24, 3, 'My team’s finally making it to the playoffs! All the hard work and dedication is paying off. Let’s keep this momentum going and make it to the finals! #PlayoffsBound', NULL, '2025-03-10 21:35:00', '2025-03-10 21:35:00', 0, 0),
(25, 4, 'The World Cup is just around the corner! Can’t wait to see the best teams in the world compete for the title. Who’s your pick to win? #WorldCup2025', NULL, '2025-03-10 21:40:00', '2025-03-10 21:40:00', 0, 0),
(26, 5, 'Just finished watching an intense tennis match. The level of skill and precision was mind-blowing. Respect to both players! #Tennis #Sportsmanship', NULL, '2025-03-10 21:45:00', '2025-03-10 21:45:00', 0, 0),
(58, 17, 'Just posted this by typing legit on the webpage! #LebronJames', NULL, '2025-03-19 20:04:45', '2025-03-19 20:04:45', 0, 0),
(59, 18, 'Just claimed the name @kylekmcleod. Gonna be worth big bucks in the future. Can\'t wait to be rich guys...', NULL, '2025-03-19 20:21:08', '2025-03-19 20:21:08', 0, 0),
(60, 18, 'Made a post in the big 25\'. So lit!!!!', NULL, '2025-03-20 18:52:53', '2025-03-20 18:52:53', 0, 0),
(61, 17, 'yo', NULL, '2025-03-21 00:58:51', '2025-03-23 07:02:14', 0, 0);

--
-- Triggers `posts`
--
DELIMITER $$
CREATE TRIGGER `update_posts_count_after_insert` AFTER INSERT ON `posts` FOR EACH ROW UPDATE users
SET posts_count = posts_count + 1
WHERE user_id = NEW.user_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `followers_count` int(11) DEFAULT 0,
  `following_count` int(11) DEFAULT 0,
  `posts_count` int(11) DEFAULT 0,
  `is_admin` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `first_name`, `last_name`, `profile_picture`, `bio`, `created_at`, `updated_at`, `followers_count`, `following_count`, `posts_count`, `is_admin`, `is_active`) VALUES
(3, 'testuser1', 'testuser1@example.com', 'hashedpassword1', 'John', 'Doe', 'http://example.com/profile1.jpg', 'This is a test bio for John.', '2025-03-10 20:57:30', '2025-03-10 20:57:30', 0, 0, 0, 0, 1),
(4, 'testuser2', 'testuser2@example.com', 'hashedpassword2', 'Jane', 'Smith', 'http://example.com/profile2.jpg', 'This is a test bio for Jane.', '2025-03-10 20:57:30', '2025-03-10 20:57:30', 0, 0, 0, 0, 1),
(5, 'testuser3', 'testuser3@example.com', 'hashedpassword3', 'Mark', 'Johnson', 'http://example.com/profile3.jpg', 'This is a test bio for Mark.', '2025-03-10 20:57:30', '2025-03-10 20:57:30', 0, 0, 0, 0, 1),
(17, 's', 's@gmail.com', '$2y$10$EpugjwUdIIQHizt6j9aFe./9vZIlk04WXHvjE38y9YoFgcoc5Ify.', 's', 's', 'uploads/a3.PNG', NULL, '2025-03-15 20:37:31', '2025-03-21 00:58:51', 0, 0, 1, 0, 1),
(18, 'kylekmcleod', 'kylekmcleod1@gmail.com', '$2y$10$188KbpkbZd/miGmSbuPtl.5jWoO5KUxvnpGet.K1XboyNZFdPbCLm', 'Kyle', 'McLeod', NULL, NULL, '2025-03-19 20:20:19', '2025-03-23 07:12:34', 0, 0, 1, 0, 1),
(19, 'Batman', 'batman@gmail.com', '$2y$10$skOQm73xmxCKzzmGC0VLLOi58j5N41ymVhdcOgsaT7.8V1DH/MwyW', 'Bruce', 'Wayne', 'uploads/1742617909_Llama3-1.jpg', NULL, '2025-03-22 04:31:49', '2025-03-23 17:12:55', 0, 0, 1, 1, 1),
(21, 'admin', 'admin@gmail.com', '$2y$10$fh/R61F52R1dfNb/OoC6Z.jiaOTQiOC0xUsSVIMjyEXffxRluhk3q', 'Admin', 'Account', NULL, NULL, '2025-03-23 04:47:50', '2025-03-23 04:48:38', 0, 0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `activity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_type` enum('post','comment','like','login','logout','friend_request') NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`user_id`,`friend_id`),
  ADD KEY `friend_id` (`friend_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
