-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2022 at 05:01 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fileshare`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `access_status`) VALUES
(1, 'admin', '$2y$10$zzLOfOl8056/GGxzUDdW8uG8bFgKgN3gyiZnpYu1cMMa3as9xYDMW', 2);

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`id`, `name`, `path`, `size`, `created_at`, `type`) VALUES
(184, 'note.txt', 'fileshare\\uploadedfiles\\public\\dir_61d81d92c5a8f7.84186603', '36', '2022-01-07 15:01:38', 'txt'),
(185, 'rap_god.mp3', 'fileshare\\uploadedfiles\\public\\dir_61d81d9f2bc280.79461532', '5780819', '2022-01-07 15:01:51', 'mp3'),
(188, 'compressedFolder.zip', 'fileshare\\uploadedfiles\\public\\dir_61d81dec4750c7.94950588', '1711', '2022-01-07 15:03:08', 'zip'),
(189, 'newNote1.txt', 'fileshare\\uploadedfiles\\private\\dir_61d827a341d9d9.36947421', '10', '2022-01-07 15:44:35', 'txt'),
(190, 'note.txt', 'fileshare\\uploadedfiles\\public\\dir_61d835063889e0.68312051', '36', '2022-01-07 16:41:42', 'txt'),
(191, 'note.txt', 'fileshare\\uploadedfiles\\public\\dir_61d841e1237dc6.64863174', '36', '2022-01-07 17:36:33', 'txt'),
(192, 'query.sql', 'fileshare\\uploadedfiles\\public\\dir_61d85e3776df91.47603731', '3642', '2022-01-07 19:37:27', 'pdf');

-- --------------------------------------------------------

--
-- Table structure for table `fileinfo`
--

CREATE TABLE `fileinfo` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `storage_id` int(11) DEFAULT NULL,
  `download_count` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `old_status` int(11) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fileinfo`
--

INSERT INTO `fileinfo` (`id`, `file_id`, `storage_id`, `download_count`, `status`, `old_status`, `password`, `description`) VALUES
(182, 184, NULL, 1, 0, NULL, '', ''),
(183, 185, NULL, 0, 0, NULL, '', ''),
(186, 188, 3, 0, 0, NULL, '', ''),
(187, 189, 1, 0, 1, NULL, '$2y$10$o2GV1YMF/lRBNZwPUtYjSePqGg.d1JczYMM7UcwV7IhYuvHH6bbQ6', 'test note'),
(188, 190, 1, 0, 0, NULL, '', ''),
(189, 191, NULL, 0, 0, NULL, '', ''),
(190, 192, 1, 0, 0, NULL, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `generatedlinks`
--

CREATE TABLE `generatedlinks` (
  `id` int(11) NOT NULL,
  `guid` varchar(255) NOT NULL,
  `file_info_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `generatedlinks`
--

INSERT INTO `generatedlinks` (`id`, `guid`, `file_info_id`) VALUES
(71, '259516A523FF5D10BA8F295DA382FCBF', 182),
(73, '798F32F46FCF4BCD11F589B7BCE34277', 188),
(74, 'D08FB24D756BD0ADA9B96E092271DFD2', 189);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `fileinfo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `name`, `email`, `description`, `created_at`, `fileinfo_id`) VALUES
(20, 'elgun', 'elgun@gmail.com', 'contains virus', '2022-01-07 15:14:35', 186),
(21, 'ahmed', 'ahmed@hotmail.com', 'this file is malicious', '2022-01-07 15:14:56', 186),
(22, 'abil', 'abil@gmail.com', 'copyright issue', '2022-01-07 15:15:13', 183);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthdate` datetime DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `username`, `email`, `password`, `birthdate`, `gender`, `created_at`, `status`) VALUES
(1, 'Elgun Abasquliyev', 'elgun', 'elgun@gmail.com', '$2y$10$Rxnh1dn.x/Xw9t4339nkX.XEjmUswUGVNvoqwhdDuOheSUzZdDyEC', NULL, NULL, '2021-12-28 06:47:42', 1),
(2, 'abil yagublu', 'abil', 'abil@gmail.com', '$2y$10$5AZAED31.zHanGncIa4ymuAYF49NFgUaGvGeKE0TXR4m9RThetYRa', NULL, NULL, '2022-01-05 21:13:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `userstorage`
--

CREATE TABLE `userstorage` (
  `id` int(11) NOT NULL,
  `storage_size` varchar(255) NOT NULL DEFAULT '''21474836480''',
  `used_size` varchar(255) NOT NULL,
  `file_count` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userstorage`
--

INSERT INTO `userstorage` (`id`, `storage_size`, `used_size`, `file_count`, `user_id`) VALUES
(1, '21474836480', '3688', 3, 1),
(3, '21474836480', '1711', 1, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fileinfo`
--
ALTER TABLE `fileinfo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `storage_id` (`storage_id`);

--
-- Indexes for table `generatedlinks`
--
ALTER TABLE `generatedlinks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_info_id` (`file_info_id`),
  ADD KEY `guid` (`guid`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fileinfo_id` (`fileinfo_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userstorage`
--
ALTER TABLE `userstorage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `fileinfo`
--
ALTER TABLE `fileinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `generatedlinks`
--
ALTER TABLE `generatedlinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `userstorage`
--
ALTER TABLE `userstorage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fileinfo`
--
ALTER TABLE `fileinfo`
  ADD CONSTRAINT `fileinfo_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fileinfo_ibfk_3` FOREIGN KEY (`storage_id`) REFERENCES `userstorage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `generatedlinks`
--
ALTER TABLE `generatedlinks`
  ADD CONSTRAINT `generatedlinks_ibfk_1` FOREIGN KEY (`file_info_id`) REFERENCES `fileinfo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`fileinfo_id`) REFERENCES `fileinfo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userstorage`
--
ALTER TABLE `userstorage`
  ADD CONSTRAINT `userstorage_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
