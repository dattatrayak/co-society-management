-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 10:20 AM
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
-- Database: `society`
--

-- --------------------------------------------------------


INSERT INTO `menus` (`id`, `name`, `url`, `icon`, `page_heading`, `sub_heading`, `parent_id`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Menu Management', 'admin/menus', 'fa-align-left', 'Manage Menu', NULL, NULL, 1, '2024-11-30 03:33:53', '2024-11-30 03:33:53'),
(12, 'User Management', 'admin/users', 'fa-child', NULL, NULL, NULL, 2, '2024-11-30 03:36:28', '2024-11-30 03:36:28'),
(20, 'Society Managment', 'admin/society-user-types', 'fa-newspaper-o', 'Society User Type Managment', NULL, NULL, 3, '2024-11-30 03:38:17', '2024-11-30 03:38:17'),
(21, 'User Type', 'admin/user-types', 'fa-bookmark-o', 'User Type management', NULL, 2, 1, '2024-11-30 03:39:17', '2024-11-30 03:39:17'),
(22, 'User Type Access', 'admin/user-types-permissions', 'fa-cogs', 'User Type Access Management', NULL, 2, 2, '2024-11-30 03:41:55', '2024-11-30 03:41:55'),
(23, 'Dashboard', 'admin/dashboard', 'fa-arrow-circle-right', 'Society Managment admin Dashboard', NULL, NULL, 0, '2024-11-30 03:45:11', '2024-11-30 03:45:11');

-- --------------------------------------------------------

--
-- Table structure for table `society_user_types`
--

CREATE TABLE `society_user_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `society_user_types`
--

INSERT INTO `society_user_types` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Manager', 'Main Admin to manage all User access and reports access', 1, '2024-11-30 03:49:27', '2024-11-30 03:49:27'),
(2, 'Account', 'Account is a user to get access for all adding and general entries and manage the Money in the system', 1, '2024-11-30 03:49:27', '2024-11-30 03:49:27'),
(3, 'Security', 'Account is a user to get access for all adding and general entries and manage the Money in the system', 1, '2024-11-30 03:49:27', '2024-11-30 03:49:27'),
(4, 'Cleaning staff', 'Cleaning member ', 1, '2024-11-30 03:49:27', '2024-11-30 03:49:27'),
(5, 'Building', 'This is used to create building in the system', 1, '2024-11-30 03:49:27', '2024-11-30 03:49:27'),
(6, 'Flat', 'Adding flat to building', 1, '2024-11-30 03:49:27', '2024-11-30 03:49:27'),
(7, 'Parking', 'Adding parking to flat', 1, '2024-11-30 03:49:27', '2024-11-30 03:49:27');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'webmaster', '2024-11-30 03:47:46', '2024-11-30 03:47:46'),
(2, 'SuperAdmin', '2024-11-30 03:47:58', '2024-11-30 03:47:58'),
(3, 'Admin', '2024-11-30 03:48:16', '2024-11-30 03:48:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menus_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `society_user_types`
--
ALTER TABLE `society_user_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `society_user_types_name_unique` (`name`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_types_name_unique` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `society_user_types`
--
ALTER TABLE `society_user_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
