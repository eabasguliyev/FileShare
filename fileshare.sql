-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2022 at 08:34 PM
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
(181, 'rap_god.mp3', 'fileshare\\uploadedfiles\\public\\dir_61d5dfd970f008.40390639', '5780819', '2022-01-05 22:13:45', 'mp3');

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
(179, 181, 3, 0, 0, NULL, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `generatedlinks`
--

CREATE TABLE `generatedlinks` (
  `id` int(11) NOT NULL,
  `guid` varchar(255) NOT NULL,
  `file_info_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `fileinfo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `name`, `email`, `description`, `fileinfo_id`) VALUES
(3, 'test', 'test@test.com', 'this is test report', 179);

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
(1, '21474836480', '0', 0, 1),
(3, '21474836480', '5780819', 1, 2);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT for table `fileinfo`
--
ALTER TABLE `fileinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `generatedlinks`
--
ALTER TABLE `generatedlinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
