-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 24, 2024 at 09:04 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coursework`
--

-- --------------------------------------------------------

--
-- Table structure for table `Answer`
--

CREATE TABLE `Answer` (
  `id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `questionID` int(11) NOT NULL,
  `authorID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Dumping data for table `Answer`
--

INSERT INTO `Answer` (`id`, `answer`, `createdAt`, `updatedAt`, `isActive`, `questionID`, `authorID`) VALUES
(6, 'test', '2024-04-21 19:41:51', '2024-04-21 19:41:51', 1, 12, 5),
(7, 'test12321312', '2024-04-21 19:44:24', '2024-04-21 19:44:24', 1, 12, 5),
(8, 'that khong', '2024-04-21 20:09:21', '2024-04-21 20:09:21', 1, 12, 5),
(9, 'hay qua', '2024-04-21 20:38:15', '2024-04-21 20:38:15', 1, 12, 5),
(10, 'test', '2024-04-21 20:39:44', '2024-04-21 20:39:44', 1, 12, 5),
(11, 'test', '2024-04-21 20:41:23', '2024-04-21 20:41:23', 1, 12, 5),
(12, 'test', '2024-04-21 20:43:37', '2024-04-21 20:43:37', 1, 12, 5),
(13, 'tegst', '2024-04-21 20:45:48', '2024-04-21 20:45:48', 1, 12, 5),
(14, 'test', '2024-04-21 20:46:33', '2024-04-21 20:46:33', 1, 12, 5),
(15, 'test notify', '2024-04-21 20:48:26', '2024-04-21 20:48:26', 1, 12, 5),
(16, 'Dữ dị sao? Qua du', '2024-04-22 08:35:13', '2024-04-23 00:01:15', 0, 12, 3),
(17, 'Is it necessary to do this?', '2024-04-22 08:36:41', '2024-04-23 00:01:27', 0, 14, 3),
(18, 'Threat or Thread? If it is thread, it is a wrong keyword. otherwise, threats are negative risks which affected from outside of the project.', '2024-04-22 10:11:15', '2024-04-22 10:11:15', 1, 15, 8),
(19, 'Oh, Thanks for your answer! You saved my life. Thanks!?', '2024-04-22 10:11:58', '2024-04-23 06:43:44', 1, 15, 3),
(20, 'How to do this?', '2024-04-22 11:29:00', '2024-04-22 11:29:00', 1, 18, 5),
(21, 'Bạn muốn hỏi cái gì. Tui bị ngu oke', '2024-04-23 00:53:12', '2024-04-23 00:53:27', 1, 16, 17),
(22, 'oke', '2024-04-23 19:02:35', '2024-04-23 19:02:35', 1, 18, 18);

-- --------------------------------------------------------

--
-- Table structure for table `Contact`
--

CREATE TABLE `Contact` (
  `id` int(11) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Dumping data for table `Contact`
--

INSERT INTO `Contact` (`id`, `emailAddress`, `subject`, `message`, `createdAt`, `updatedAt`) VALUES
(1, 'tuyenhtgcs220307@fpt.edu.vn', 'How to apply to become an admin of the AskToLearn forum?', 'I want to become an administrator because I am curious about this site.', '2024-04-20 21:29:08', '2024-04-21 18:04:36'),
(4, 'tuyenhtgcs220307@fpt.edu.vn', 'Who is your daddy?', 'Me.', '2024-04-21 10:26:19', '2024-04-21 10:26:19'),
(5, 'dragonchina@gmail.com', 'I have an issue with the login page', 'I forgot password.', '2024-04-21 14:45:33', '2024-04-21 14:45:33'),
(8, 'tuyen@vietnam-cloud.vn', 'Who is your daddy?', 'test', '2024-04-22 20:15:07', '2024-04-22 20:15:07');

-- --------------------------------------------------------

--
-- Table structure for table `Module`
--

CREATE TABLE `Module` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Dumping data for table `Module`
--

INSERT INTO `Module` (`id`, `name`, `isActive`, `createdAt`, `updatedAt`) VALUES
(1, 'Common', 1, '2024-04-20 21:14:07', '2024-04-20 21:14:07'),
(3, 'C Programming Language', 1, '2024-04-21 18:01:43', '2024-04-21 18:01:43'),
(4, 'Python Programming Language', 1, '2024-04-21 18:01:48', '2024-04-21 18:01:48'),
(5, 'Professional Project Management', 1, '2024-04-21 18:01:54', '2024-04-21 18:01:54');

-- --------------------------------------------------------

--
-- Table structure for table `Permission`
--

CREATE TABLE `Permission` (
  `id` int(11) NOT NULL,
  `perm` varchar(255) NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Dumping data for table `Permission`
--

INSERT INTO `Permission` (`id`, `perm`, `isActive`, `createdAt`, `updatedAt`) VALUES
(4, 'addQuestion', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(5, 'getQuestion', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(6, 'editQuestion', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(7, 'deleteQuestion', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(8, 'addAnswer', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(9, 'getAnswer', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(10, 'editAnswer', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(11, 'deleteAnswer', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(12, 'addContact', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(13, 'getContact', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(14, 'editContact', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(15, 'deleteContact', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(16, 'addUser', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(17, 'getUser', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(18, 'editUser', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(19, 'deleteUser', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(20, 'addPermission', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(21, 'getPermission', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(22, 'editPermission', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(23, 'deletePermission', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(24, 'addRole', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(25, 'getRole', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(26, 'editRole', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34'),
(27, 'deleteRole', 1, '2024-04-22 18:47:34', '2024-04-22 18:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `Question`
--

CREATE TABLE `Question` (
  `id` int(11) NOT NULL,
  `thread` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(5028) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `authorID` int(11) NOT NULL,
  `moduleID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Dumping data for table `Question`
--

INSERT INTO `Question` (`id`, `thread`, `content`, `image`, `createdAt`, `updatedAt`, `isActive`, `authorID`, `moduleID`) VALUES
(12, 'How to write CV for the software engineer job', 'I want to get 1 million dollars in a month.', '/uploads/LWScreenShot 2024-04-04 at 08.14.34.png', '2024-04-21 12:16:59', '2024-04-21 18:09:00', 0, 3, 4),
(14, 'Does Python support Switch/Case clause?', 'In many programming languages, Switch/Case clause is supported well, but I didn&#39;t find any documents about this.', '/uploads/LWScreenShot 2024-04-04 at 08.14.34.png', '2024-04-21 18:48:22', '2024-04-21 18:48:22', 1, 5, 4),
(15, 'What is thread and opportunity in the project risk? ', 'How to define them.', '/uploads/Screenshot 2024-04-03 at 15.36.21.png', '2024-04-22 10:09:16', '2024-04-22 10:09:16', 1, 3, 5),
(16, 'Is C Programming Language difficult for newbies?', 'About C++', '', '2024-04-22 10:26:35', '2024-04-22 10:26:35', 1, 3, 1),
(17, 'A How to create a responsive layout?', 'Can I use Bootstrap 5?', '/uploads/Screenshot 2024-04-07 at 15.44.54.png', '2024-04-22 11:13:27', '2024-04-22 11:13:27', 1, 5, 1),
(18, 'Python Decorator', 'When to use decorator?', '/uploads/LWScreenShot 2024-04-04 at 08.14.34.png', '2024-04-22 11:14:04', '2024-04-22 11:14:04', 1, 5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `Role`
--

CREATE TABLE `Role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Dumping data for table `Role`
--

INSERT INTO `Role` (`id`, `name`, `isActive`, `createdAt`, `updatedAt`) VALUES
(8, 'Editor', 1, '2024-04-22 18:07:54', '2024-04-22 18:07:54'),
(9, 'Moderator', 1, '2024-04-22 19:58:22', '2024-04-22 19:58:22'),
(10, 'Admin', 1, '2024-04-22 20:58:00', '2024-04-22 20:58:00');

-- --------------------------------------------------------

--
-- Table structure for table `RolePermission`
--

CREATE TABLE `RolePermission` (
  `id` int(11) NOT NULL,
  `roleID` int(11) NOT NULL,
  `permissionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Dumping data for table `RolePermission`
--

INSERT INTO `RolePermission` (`id`, `roleID`, `permissionID`) VALUES
(503, 8, 4),
(486, 9, 4),
(511, 8, 5),
(498, 9, 5),
(507, 8, 6),
(492, 9, 6),
(501, 8, 8),
(483, 9, 8),
(509, 8, 9),
(495, 9, 9),
(505, 8, 10),
(489, 9, 10),
(502, 8, 12),
(484, 9, 12),
(510, 8, 13),
(496, 9, 13),
(506, 8, 14),
(490, 9, 14),
(504, 8, 16),
(488, 9, 16),
(512, 8, 17),
(500, 9, 17),
(508, 8, 18),
(494, 9, 18),
(485, 9, 20),
(519, 10, 20),
(497, 9, 21),
(525, 10, 21),
(491, 9, 22),
(523, 10, 22),
(521, 10, 23),
(487, 9, 24),
(520, 10, 24),
(499, 9, 25),
(526, 10, 25),
(493, 9, 26),
(524, 10, 26),
(522, 10, 27);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(2000) NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `isSuperAdmin` tinyint(1) NOT NULL,
  `registeredAt` datetime NOT NULL,
  `reputation` int(11) NOT NULL,
  `birthday` date DEFAULT NULL,
  `aboutMe` text DEFAULT NULL,
  `loginedAt` datetime DEFAULT NULL,
  `image` varchar(5028) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `firstName`, `lastName`, `emailAddress`, `password`, `isActive`, `isSuperAdmin`, `registeredAt`, `reputation`, `birthday`, `aboutMe`, `loginedAt`, `image`) VALUES
(1, 'Tuyen', 'Admin', 'tuyen+admin@vietnam-cloud.vn', '$2y$10$6/WvvRFwjrHR60dstRdrs.L.Qh8rYYtWv4xl6s6op8l30y9Uconw6', 1, 1, '2024-04-20 21:11:22', 0, '2024-04-17', NULL, '2024-04-24 07:30:35', NULL),
(3, 'Thanh Tuyền', 'Huỳnh', 'dragonchina@gmail.com', '$2y$10$B6pmaH1bQBTdXs4LmaEf0uEtc/vJVvlXn/Dx7S19wJbqDfYK/mbWi', 1, 0, '2024-04-20 22:35:16', 10, '1995-05-18', 'I&#39;m a Python developer. I have many years of experience in developing e-commerce websites.', '2024-04-24 07:35:27', '/uploads/LWScreenShot 2024-04-04 at 08.14.34.png'),
(5, 'User', 'Design', 'tuyen+designer@vietnam-cloud.vn', '$2y$10$XM9YUpJvFlnOnkiT./RNF.AUEB16.pwFZGf8XnwpUXHh0kGDsE8e.', 1, 0, '2024-04-21 09:28:01', 0, '2024-04-10', NULL, '2024-04-23 14:04:35', '/uploads/Screenshot 2024-04-05 at 16.15.40.png'),
(8, 'Tuyen', 'Huynh', 'tuyen@vietnam-cloud.vn', '$2y$10$NGf6KcC95YYLNBU/gfwBHO.IrMvLBdfh04lD9yOF7ScnC4Ml0B4I2', 1, 0, '2024-04-21 18:04:06', 2, '2024-04-05', NULL, '2024-04-24 08:38:37', '/uploads/DSCF7584.JPEG'),
(9, 'Thanh Tuyền', 'Huỳnh', 'tuyen+moderator@vietnam-cloud.vn', '$2y$10$yysQdk/0Adu7NSuAi1sJEuMTTcnykz19BL.khF3hBC7hU5BT5N2si', 1, 0, '2024-04-22 20:43:21', 0, '2024-04-24', NULL, NULL, NULL),
(10, 'Thanh Tuyền', 'Huỳnh', 'tuyen+editor@vietnam-cloud.vn', '$2y$10$ZkB19FgTIve3PY681EhluucQrCB40quSSEbMfa3urYDTR69qiNmem', 1, 0, '2024-04-22 21:04:39', 0, '2024-04-24', 'test', '2024-04-22 22:11:53', NULL),
(11, 'Thanh Tuyền', 'Huỳnh', 'tuyen+tester@vietnam-cloud.vn', '$2y$10$IPbfNZdb6XuOLpOXGCmefeNUQKg.MV6de5rHgWxL27xsJYRSBU2P2', 1, 0, '2024-04-22 21:53:09', 0, '2024-04-24', 'Please work for me', NULL, NULL),
(13, 'Thanh Tuyền', 'Huỳnh', 'tuyen123123@vietnam-cloud.vn', '$2y$10$6vtRyyFrEdm1x22rZ/Yane7UcbELixPJ9gEoU.sjyjhbPGEefD0oa', 1, 0, '2024-04-22 22:24:44', 0, '2024-05-02', 'tetse', NULL, NULL),
(14, 'Thanh Tuyền', 'Huỳnh', 'tuyen12312312211212@vietnam-cloud.vn', '$2y$10$/j0YlF8ZLlxLJlRYOgcTFO.OxoHVDOYdkEuikb.BZi231WuHTVjSe', 1, 0, '2024-04-22 22:25:17', 0, '2024-05-02', 'tetse', NULL, NULL),
(15, 'Vinh', 'Long', 'dragonchina123123123@gmail.com', '$2y$10$BxJLlchGusuAsiL61/1FoeKa4/Z1EwG0CJVOlZfMGeuW9ZAj0AaZa', 1, 0, '2024-04-22 22:26:18', 0, NULL, 'sdaf', NULL, NULL),
(16, 'Vinh', 'Long', 'dragonchina1231212122123123@gmail.com', '$2y$10$fGJmQTymUHaExOYkCq9QAONTXoM4Cs5uJtR1COwGh4trarX.ZkO4y', 1, 0, '2024-04-22 22:54:13', 0, NULL, 'asd', NULL, NULL),
(17, 'Vinh', 'Long', 'daovinhlong@gmail.com', '$2y$10$7OSD7LxpMrX08vOEULQtue1LxybDhNuW9j.XluIb6a4.FdXkA.fAC', 1, 0, '2024-04-23 00:51:55', 0, '2024-04-18', 'Vip Pro thế', '2024-04-23 00:52:00', '/uploads/Screenshot 2024-04-22 at 15.12.14.png'),
(18, 'Thanh Tuyền', 'Huỳnh', 'tuyen111@vietnam-cloud.vn', '$2y$10$m8XN4fpgHNrwNYXlpJRHs./TA8ngpkSn6Cw66SZyqnRqZqtqgKoTm', 1, 0, '2024-04-23 13:23:39', 0, NULL, NULL, '2024-04-23 18:58:27', '/uploads/Screenshot 2024-04-22 at 15.12.14.png');

-- --------------------------------------------------------

--
-- Table structure for table `UserRole`
--

CREATE TABLE `UserRole` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `roleID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Dumping data for table `UserRole`
--

INSERT INTO `UserRole` (`id`, `userID`, `roleID`) VALUES
(35, 3, 9),
(2, 10, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Answer`
--
ALTER TABLE `Answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkQuestionAnswer` (`questionID`),
  ADD KEY `fkAuthorAnswer` (`authorID`);

--
-- Indexes for table `Contact`
--
ALTER TABLE `Contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Module`
--
ALTER TABLE `Module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Permission`
--
ALTER TABLE `Permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `perm` (`perm`);

--
-- Indexes for table `Question`
--
ALTER TABLE `Question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkQuestionAuthor` (`authorID`),
  ADD KEY `fkQuestionModule` (`moduleID`);

--
-- Indexes for table `Role`
--
ALTER TABLE `Role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `RolePermission`
--
ALTER TABLE `RolePermission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_perm_id` (`permissionID`,`roleID`),
  ADD KEY `fkPermissionRole` (`roleID`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `UserRole`
--
ALTER TABLE `UserRole`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_role_id` (`userID`,`roleID`),
  ADD KEY `fkUserRole` (`roleID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Answer`
--
ALTER TABLE `Answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `Contact`
--
ALTER TABLE `Contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Module`
--
ALTER TABLE `Module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Permission`
--
ALTER TABLE `Permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `Question`
--
ALTER TABLE `Question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `Role`
--
ALTER TABLE `Role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `RolePermission`
--
ALTER TABLE `RolePermission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=527;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `UserRole`
--
ALTER TABLE `UserRole`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Answer`
--
ALTER TABLE `Answer`
  ADD CONSTRAINT `fkAuthorAnswer` FOREIGN KEY (`authorID`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fkQuestionAnswer` FOREIGN KEY (`questionID`) REFERENCES `Question` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Question`
--
ALTER TABLE `Question`
  ADD CONSTRAINT `fkQuestionAuthor` FOREIGN KEY (`authorID`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fkQuestionModule` FOREIGN KEY (`moduleID`) REFERENCES `Module` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `RolePermission`
--
ALTER TABLE `RolePermission`
  ADD CONSTRAINT `fkPermission` FOREIGN KEY (`permissionID`) REFERENCES `Permission` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fkPermissionRole` FOREIGN KEY (`roleID`) REFERENCES `Role` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `UserRole`
--
ALTER TABLE `UserRole`
  ADD CONSTRAINT `fkUser` FOREIGN KEY (`userID`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fkUserRole` FOREIGN KEY (`roleID`) REFERENCES `Role` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
