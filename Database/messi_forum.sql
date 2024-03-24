-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2024 at 07:05 PM
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
-- Database: `messi_forum`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role` enum('superadmin','moderator') DEFAULT 'moderator'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_actions`
--

CREATE TABLE `admin_actions` (
  `action_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action_type` varchar(255) NOT NULL,
  `action_description` text DEFAULT NULL,
  `action_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `parent_category_id`) VALUES
(1, 'WeLoveMESSI', 'A category dedicated to Lionel Messi fans.', NULL),
(2, 'MessiGOATArgument', 'A category for debating why Messi is considered the Greatest Of All Time.', NULL),
(3, 'WhyMessitheGOAT', 'A category for sharing reasons why Messi is the best.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `is_hidden` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `content`, `timestamp`, `is_hidden`) VALUES
(1, 3, 26, 'hi', '2024-03-23 13:39:58', 0),
(2, 2, 26, 'hi', '2024-03-23 14:23:43', 0),
(3, 1, 26, 'no', '2024-03-23 14:23:49', 0),
(4, 4, 26, 'why', '2024-03-23 15:09:10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `thread_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `vote_count` int(11) DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `is_hidden` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `thread_id`, `user_id`, `content`, `timestamp`, `vote_count`, `title`, `image`, `category_id`, `is_hidden`) VALUES
(1, NULL, 26, 'test', '2024-03-23 13:38:42', 1, 'test', NULL, NULL, 0),
(2, NULL, 26, 'test', '2024-03-23 13:38:58', -1, 'test', NULL, NULL, 0),
(3, NULL, 26, 'test1', '2024-03-23 13:39:36', -1, 'test1', NULL, NULL, 0),
(4, 1, 26, 'hello1', '2024-03-23 15:08:43', -1, 'hello1', NULL, NULL, 0),
(5, NULL, 26, 'testtt', '2024-03-23 15:22:09', 1, 'testttt', NULL, NULL, 0),
(6, NULL, 26, 'amr bya5do', '2024-03-23 20:46:22', 0, 'amr 5awal', NULL, NULL, 0),
(7, NULL, 26, 'madrid', '2024-03-23 21:16:25', 0, 'hala', NULL, NULL, 0),
(8, NULL, 26, 'test', '2024-03-23 21:46:52', 0, 'test for category', NULL, 1, 0),
(9, NULL, 26, 'test', '2024-03-23 21:55:06', 0, 'test12', NULL, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `post_tags`
--

CREATE TABLE `post_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE `threads` (
  `thread_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT current_timestamp(),
  `body` text DEFAULT NULL,
  `is_hidden` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `threads`
--

INSERT INTO `threads` (`thread_id`, `title`, `user_id`, `category_id`, `creation_date`, `body`, `is_hidden`) VALUES
(1, 'hello', 26, NULL, '2024-03-23 13:59:11', 'hello', 0),
(2, 'hello', 26, NULL, '2024-03-23 13:59:33', 'hello', 0),
(3, 'hi', 26, NULL, '2024-03-23 15:50:05', 'hello', 0),
(4, 'what&#039;s up', 26, NULL, '2024-03-23 15:50:37', 'yo', 0),
(5, 'hala', 26, NULL, '2024-03-23 21:36:32', 'hala', 0),
(6, 'test for thread category', 26, 2, '2024-03-23 21:47:23', 'test for thread category', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic_blob` blob DEFAULT NULL,
  `registration_date` datetime DEFAULT current_timestamp(),
  `status` enum('active','blocked') DEFAULT 'active',
  `profile_pic_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `profile_pic_blob`, `registration_date`, `status`, `profile_pic_type`) VALUES
(26, 'yahia', 'yahia@gmail.com', '$2y$10$L/ssvx1Eec5QsohdX41xDeoJVe7VqRjyx4MXRIFTgnkzJ.8kp9qJ2', 0xffd8ffe000104a46494600010100000100010000ffdb0043000505050505050506060508080708080b0a09090a0b110c0d0c0d0c111a1013101013101a171b1615161b1729201c1c20292f2725272f393333394744475d5d7dffc2000b0801a602ee01011100ffc4001d000100020203010100000000000000000000080901070405060302ffda000801010000000086400000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001f4db1e03a200000000000000000000007327b757a07b3d99e1635b000000000000000000000136b62c649ddcbf37a621563a300000000000000000001b3f77c42c7756b7003c1496939e3e0fcecfbd5d7180000000000000000000f6b64f5d7ade5148aad7fc24a6b49e3af644577e8e00000000000000000001bda6a576d864428f387266b6e0acfb09f23054000000000000000000066404e9f9552f170eced7a0d475911376a73e6000000000000000000033655ed6ad3abc3794f1aa4e3ee2b22a9bf3e00000000000000000003d0dac784d6903bf39b04eba05a4c4e6aaef24000000000000000000036ad8655d584c638edf7b6caebd419b0fee6b67f00000000000000000000275f0a127aab38abee6daad45f5beeed1e0cc64c00000000000000000001cab46ae8f129efa4b8d3c2a67833bf7655ff004b800000000000000000003d158e561633dc7b8ee2c46aebbfb2fadfd3b800000000000000000001b464b418658ed6d3ba6f5d1ca0ae000000000000000000002407bd88419da12ba2e6a5fc800000000000000000003606ec8a980c86000000000000000000001cde10000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000fffc400271000020203000202020105010000000000040503060102070811006010141612131520a021ffda0008010100010800ff00ac98a2de69348e3af711e8762ffd8ac48f7aeb3216cdf5a001299183062f19e35155022cdb3755eedba234badd72b5c52ed74872d887fe3d5912004971f3df1f11fe88e65a3bcf345553893b74debeafe34d46089330b119d96ec55539f4e405c02a435aad45147843e35d7d4a40c1160cd17c8c4200db1a8de4c1f08f4a5414a96b8eec536d0aa775c795d23f5db7d439473ecf44b368b64e99e3bad4c9896d59ce3d6738fc579494f1dab5a2aa8855cba10e3ee3d0a1b9583f55752ba058e886feca9ac793e3cbbeb15895385af530ec105fba2a3e7d14339b6ab658bab5861862e75cf5451d241089d268a05e6ac78131634a19440d2fd3e8371368d6501c0d9dd574da36db8f7de66fa8647a3be78d94515cba22c66f66b64157a337cfcdb6ced9ce76f7f8e2d717e8ab37d961adadb2756b72d584f36e508f9f0f3ef0dfbb431417e02b60ae61037062262ef34d9aaf7430ad3ea1c57a9cb4675818fb856eb7d06ac40915879d5bab3fd7232e7b5b1e9d4850107e4658856f6c08283f301650f191143e2ed5a6116b6b24dd8acf8ab509c1182cf30f23624be1d7302c357d4017c9644c9ad696b58fea3c93b3e68fa40a18c2c90d9c1122daecd7082a0e8b88c2a73499ca9ff2997e5b355c062ab5e16a6855a70fbf74362e6cec90878cfaf9e369dbc578905cdae08da556c226d2699d37df5cfd431f38ad5a14f4b0259ae617f20aebf4913852c11b12d731fcf02a8c168bbc12937eb28954a9b3645153c84933cf27ce0d8df3d42b9fd1b43a4b1cdb66d108a3d8ddc227d42a62c06d9128a40016a1a8184821bdd4e2b3475adfc83e63ad856ff2b579c671ef19fc78f7cfc4415f8ec52793798a4a98f9c7e3c67adfefda8b77bec4e82c04ff0076f0680c6d4ecb5ff50e3b5a6761bc27c876a75b21af3675960ecd3de96e73c8fa5817bad6a01bdc792eb4b23476afe0b0ec411043ad5d746a2b68c083b5d837717eb0620fc78f0ae65fcef42a5ecd6e8aa1486598f39f7f51f1a6ae02d4653fcf92d79d805c25581f7f294e3fc15a519fb38409aeb5d9842adb5d2aa96168989e7feff99d6bd17bc418254b9733fed36673e3e73ca71974b32c5d12a0425408c085e4b5bc17763192419fa8022487182891d22b60526b10038e8b62ded37074cb3f3dfaf9e3c748c3b53b57da7936b620ba0c73e9e3a7eacb75c444b993132e6636ece0c0ac0e831f3c6fa49e8d61ee0de89710e8b5639c1160787591c1cd8ecfd42a58d336741fdce98d855bcfecc44f9fca17acab6d4368b3a6f4723a31294c2384bd2555f150da6f04f2e0cd20e855e6159b6b85e7f2ee68dafce87d748620112ed22d7aff004b3af2fcc8e1fa8f1a04761d16b5011e513cfd144993c5f8f7f94ace74cd57b11e9b62576a4223756f396526ca7927b1489525554c6101df7ac2a1d1135b4b9cfbcfd4bc6e9e28fa24316fe55431e0cac49fed8f9cefa7bda0178c8af3c93a9a98a2c0371ec56db7c6cc49b6cedb6739cfd4b99dc3147b72d6fbf906b88b5ee9ae49b3fedefe7bcfcf79f9efea9864c35172263feb43ffc400331000020103010604030803010000000000010203000411120513213141511014226020526123243242437172816291a063ffda0008010100093f00ff00ac952cec40551c4926b6235b47a35092e4eec1abb8679e06292988e555c1c15f6dc2d2cf3b848d1464926b665a4db44cc1e073ebdda015691ddcd25b81e6e37c842fd140a64b38ee64244b7590ce6b68c17862fd3841c9adedc5e1f59b589f11a8ecd56a2dad272609a104901c7b636742f70d71a6ca471eb016ae592f768ce6da23d63f9aa27912c537c1ba1735cd49d01870502b8c4c08d4bcff00aad721d2153ae01ea6a0479eeaec10d9e3198eb674d72eb8c8419c66b664f69274122100fec7da37060b48537b7320e612af6626df064b69faaf8a6a9ae6e1117fb35122c7670a890a2e172abc48a9cb6ceb376c74d72d5c8084fdac0e329256c6116485335bd4e93db5c1055d390ef9140cb7b395431c678aa7cd566640f3aa471440f1e818f6ab364b89b4cb3b4843387ed9a8c0bb4432dacbd5645a5d32432346e3b15383ed063a11b4cf1fcf11e628ba5b6d4b338e3a5d09a314d033616584e40ec1bc0931ecc70208fbca6ae773797719b7b548ce183b51c93ccf8dfe6db676cc32dbdbbf2595ce030ada134f24ac4c92b9cee62ce58d0f317d29c4970fd8559448b1dd44b3ceefc1e1929088a55e4d43367b40efa2217017fc3da333beccba2b1be496dd7d545490cad3c45ada68db389398cd6c69e2837dba4988f439ab4115d5c42b2ca79932b8cf1353095f67daeeae1872de9f82e1d12750b2aa9c075073835101e6bec2dc95e385a66134e9e5e228c55834957324d3b63548eda98e3ea69e417361120944b56b94b09099df5720fed2b159f664939696624974068c17105dc026449101d51b76cd7a0c166eb163f29d385a919e595cb3b31c924fc1288fccdc470eb3c9759c66b1e5ad6055fdcf56ada65f645b108620060b8f0b94549ed9b10b672ee3b547912d84cb8fea8608247b4a57b87ba0970ad2f340453e27bbb291213d03f4cd5b3c17503959236f819843b3545d151d596a79221ba312488ba8ac920c29a72ef23b3331ea49ce7c0f5933fb69a39565656fa835ab711de4cb1eae780ded18166865bb8d1e36240604f2245462048a211c609ce950302b6b09efb77bcce46977ce34e7e61daa2c5f5a45f7841ce58850f16737fb4a0c15e88957332c915cc6376af846cf75f1d41767c2447d8bc9584863899cb93c0003249a24dacb72e633dfda28fbbb39d27b8917f2229a89e5f296ecc11464161ca9f757535d35c068f8687273c2a747dab6d004bb4718de8a39d977721ca139313b780c99245403f91c5460086ca15c1ee056d0965b3595504649d28d18c11e3628925c4cc63d1f89d3b9a9905fdf2182dd0f3c3f063ed2975ed1ba250a86e11c5538592efed6ef1cc47e172f04515d46657427f0678d4227b2bc8c3c65b9ae47a58503aed666407e64e8d51a3fdfa2e0cba86334da238a091b3d82ad397125ccae18f5cb1f0858dbb4ebe624e8882add61b785022228c0e1c2ad984bb209479ba36bf68fe39e548d7f7738ab92cf6d087ba95fa10b9353996379d9216ffcd0e17c6fc1bdb6c0b74c7e90a90937763148e2ad51c2a6f6393465d1ea36c3db48980325b529185fad06c473ba7a861b81ea3c22d22f821b721c323c74353e375027cf2b0e029f55c5cb966f68ab14f3d06a0a3271aaa77843d998e2907a5a46906001f05c986eaddf523d5b0867b5b4dd4c0722fdc514f2d792699c355ce9690369908d611b1c0e3e95389a6131937c39481ce755425366c3206b99c8f4e0735142382ced21fe2a8882ae1d76441268b7841f4be8e527b486537fabfb14e337b319255fa45f136996da74914ff135762649500988183bc03d408ad908f7171108e67c905b0739a862b5b3807d17fd9ada4935f5d10972623911c5ed3803992ddf49f90ad3969344df1c9bdb47f4b4121251759e2e00ab77da13bda89729e94127c8d57661b1bab859442871a1539251249ea7da76e9342ada2607a23f024514bad8be40234e8c3d041f6f5ecc2d8f38759d1febfeb47fffd9, '2024-03-23 11:04:53', 'active', 'image/jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `vote_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vote_type` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`vote_id`, `post_id`, `user_id`, `vote_type`, `timestamp`) VALUES
(1, 5, 26, 1, '2024-03-23 17:18:25'),
(2, 4, 26, -1, '2024-03-23 17:18:28'),
(3, 3, 26, -1, '2024-03-23 17:18:33'),
(4, 2, 26, -1, '2024-03-23 17:18:35'),
(5, 1, 26, 1, '2024-03-23 17:18:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `parent_category_id` (`parent_category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `thread_id` (`thread_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_posts_category` (`category_id`);
ALTER TABLE `posts` ADD FULLTEXT KEY `content` (`content`);

--
-- Indexes for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `threads`
--
ALTER TABLE `threads`
  ADD PRIMARY KEY (`thread_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_category` (`category_id`);
ALTER TABLE `threads` ADD FULLTEXT KEY `title` (`title`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD UNIQUE KEY `user_post_unique` (`user_id`,`post_id`),
  ADD KEY `post_id` (`post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_actions`
--
ALTER TABLE `admin_actions`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `threads`
--
ALTER TABLE `threads`
  MODIFY `thread_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD CONSTRAINT `admin_actions_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`thread_id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD CONSTRAINT `post_tags_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `post_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`);

--
-- Constraints for table `threads`
--
ALTER TABLE `threads`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `threads_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
