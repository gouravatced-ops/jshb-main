-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2026 at 06:10 AM
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
-- Database: `16042026_jesa`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'success',
  `action` varchar(255) NOT NULL DEFAULT 'login_attempt',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `email`, `ip_address`, `user_agent`, `status`, `action`, `created_at`) VALUES
(1, 1, 'pchamplin@example.org', '225.69.12.17', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/537.1 (KHTML, like Gecko) Version/15.0 EdgiOS/94.01016.21 Mobile/15E148 Safari/537.1', 'failed', 'login_attempt', '2026-04-16 04:58:30'),
(2, 2, 'kaylah.barton@example.org', '26.42.102.58', 'Opera/8.95 (X11; Linux x86_64; en-US) Presto/2.8.293 Version/11.00', 'success', 'login_attempt', '2026-04-16 04:58:30'),
(3, 3, 'wlowe@example.com', '148.59.7.209', 'Opera/8.68 (X11; Linux x86_64; nl-NL) Presto/2.10.254 Version/11.00', 'success', 'login_attempt', '2026-04-16 04:58:31'),
(4, 4, 'aufderhar.godfrey@example.org', '50.146.30.106', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 6.0; Trident/4.1)', 'failed', 'login_attempt', '2026-04-16 04:58:31'),
(5, 5, 'leora11@example.com', '23.22.81.153', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5320 (KHTML, like Gecko) Chrome/36.0.881.0 Mobile Safari/5320', 'success', 'login_attempt', '2026-04-16 04:58:31'),
(6, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-04-16 06:09:13'),
(7, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'otp_login', '2026-04-16 06:09:47'),
(8, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-16 06:15:00'),
(9, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-16 06:21:40'),
(10, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-16 06:21:50'),
(11, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'otp_login', '2026-04-16 07:11:16'),
(12, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'success', 'otp_login', '2026-04-16 07:28:17'),
(13, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'otp_login', '2026-04-16 22:44:33'),
(14, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-16 23:13:25'),
(15, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-17 06:39:50'),
(16, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-17 06:40:22'),
(17, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-17 07:10:22'),
(18, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-17 07:10:40'),
(19, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-17 07:34:49'),
(20, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-17 07:34:54'),
(21, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-17 07:48:29'),
(22, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-17 07:48:43'),
(23, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-17 07:49:06'),
(24, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-17 08:01:59'),
(25, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'otp_login', '2026-04-17 09:10:23'),
(26, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-17 09:10:44'),
(27, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'success', 'otp_login', '2026-04-17 09:29:33'),
(28, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-17 09:30:26'),
(29, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'otp_login', '2026-04-17 10:20:54'),
(30, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-17 10:50:54'),
(31, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'otp_login', '2026-04-17 10:51:25'),
(32, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'success', 'auto_logout', '2026-04-17 11:22:20'),
(33, 6, 'admin@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-17 11:50:49'),
(34, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-17 12:20:50'),
(35, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-17 12:21:23'),
(36, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-17 12:51:23'),
(37, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-17 12:51:34');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_16_000000_create_user_details_table', 1),
(5, '2026_04_16_000100_create_login_logs_table', 1),
(6, '2026_04_16_000200_create_otp_logs_table', 1),
(7, '2026_04_17_161334_add_new_fields_to_user_details_table', 2),
(8, '2026_04_17_161557_add_photo_to_users_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `otp_logs`
--

CREATE TABLE `otp_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `otp_code` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `purpose` varchar(255) NOT NULL DEFAULT 'login',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp_logs`
--

INSERT INTO `otp_logs` (`id`, `user_id`, `email`, `otp_code`, `verified`, `purpose`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'pchamplin@example.org', '5452', 1, 'login', '218.123.223.35', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_2 rv:5.0) Gecko/20161111 Firefox/35.0', '2026-04-16 04:58:30'),
(2, 2, 'kaylah.barton@example.org', '9786', 0, 'login', '195.215.111.14', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.0 (KHTML, like Gecko) Chrome/92.0.4563.42 Safari/537.0 EdgA/92.01072.62', '2026-04-16 04:58:31'),
(3, 3, 'wlowe@example.com', '3174', 1, 'login', '139.168.206.127', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_7_1 rv:6.0; nl-NL) AppleWebKit/533.18.6 (KHTML, like Gecko) Version/4.0.2 Safari/533.18.6', '2026-04-16 04:58:31'),
(4, 4, 'aufderhar.godfrey@example.org', '5059', 0, 'login', '232.22.9.60', 'Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20110313 Firefox/37.0', '2026-04-16 04:58:31'),
(5, 5, 'leora11@example.com', '3282', 1, 'login', '100.187.94.54', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_7_8) AppleWebKit/532.1 (KHTML, like Gecko) Chrome/92.0.4308.43 Safari/532.1 Edg/92.01144.79', '2026-04-16 04:58:31'),
(6, 6, 'admin@example.com', '474129', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 06:09:19'),
(7, 6, 'admin@example.com', '625421', 0, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 06:14:26'),
(8, 6, 'admin@example.com', '003956', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 07:11:02'),
(9, 6, 'admin@example.com', '731229', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '2026-04-16 07:27:56'),
(10, 6, 'admin@example.com', '815486', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 22:42:41'),
(11, 6, 'admin@example.com', '416581', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 09:10:08'),
(12, 6, 'admin@example.com', '223045', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '2026-04-17 09:28:48'),
(13, 6, 'admin@example.com', '816865', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 10:20:44'),
(14, 6, 'admin@example.com', '759982', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 10:51:13');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('NcE91xAFrH39PDLsalez0VLUbswyffvBqGDsL0eg', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiSkpYdjZPdlBRN0xlbG1RWWJCYUhsZ1BMc0tNVnFjUlBRcXRhbnFlWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9maWxlP3RhYj1iYXNpYyI7czo1OiJyb3V0ZSI7czoxMzoiYWRtaW4ucHJvZmlsZSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjY7czoyMToic2Vzc2lvbl9leHBpcmVzX2F0X3RzIjtpOjE3NzY0MzIwOTQ7czoxODoic2Vzc2lvbl9leHBpcmVzX2F0IjtzOjE5OiIyMDI2LTA0LTE3IDE4OjUxOjM0IjtzOjIxOiJzZXNzaW9uX2xhc3RfYWN0aXZpdHkiO3M6MTk6IjIwMjYtMDQtMTcgMTg6MjE6MzQiO30=', 1776430842);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `login_with_otp` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password_created_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `login_with_otp`, `email_verified_at`, `password_created_at`, `password`, `remember_token`, `created_at`, `updated_at`, `photo`) VALUES
(1, 'Kari Crooks DVM', 'pchamplin@example.org', 'user', 0, '2026-04-16 04:58:30', NULL, '$2y$12$wWPCBrIrr39LXBXIV2j2XeNstxiVm8.E77cGYBy97PL6I8DB2ILgu', 'xCpZ5b41G1', '2026-04-16 04:58:30', '2026-04-16 04:58:30', NULL),
(2, 'Ethyl Erdman MD', 'kaylah.barton@example.org', 'user', 0, '2026-04-16 04:58:30', NULL, '$2y$12$wWPCBrIrr39LXBXIV2j2XeNstxiVm8.E77cGYBy97PL6I8DB2ILgu', 'JXXdMhbDNV', '2026-04-16 04:58:30', '2026-04-16 04:58:30', NULL),
(3, 'Mr. Bailey Terry I', 'wlowe@example.com', 'user', 0, '2026-04-16 04:58:30', NULL, '$2y$12$wWPCBrIrr39LXBXIV2j2XeNstxiVm8.E77cGYBy97PL6I8DB2ILgu', 'cgdviXRo8m', '2026-04-16 04:58:30', '2026-04-16 04:58:30', NULL),
(4, 'Keaton Harris', 'aufderhar.godfrey@example.org', 'user', 0, '2026-04-16 04:58:30', NULL, '$2y$12$wWPCBrIrr39LXBXIV2j2XeNstxiVm8.E77cGYBy97PL6I8DB2ILgu', 'mlfE3mFYf1', '2026-04-16 04:58:30', '2026-04-16 04:58:30', NULL),
(5, 'Gillian Friesen', 'leora11@example.com', 'user', 0, '2026-04-16 04:58:30', NULL, '$2y$12$wWPCBrIrr39LXBXIV2j2XeNstxiVm8.E77cGYBy97PL6I8DB2ILgu', 'GYRvuWFMBz', '2026-04-16 04:58:30', '2026-04-16 04:58:30', NULL),
(6, 'JESA ADMIN', 'admin@jesa.com', 'admin', 0, '2026-04-16 04:58:31', '2026-04-16 04:58:31', '$2y$12$wWPCBrIrr39LXBXIV2j2XeNstxiVm8.E77cGYBy97PL6I8DB2ILgu', 'PkT8E8zbvFqLiEKUnVTmN936ZhDYgAmRYsTm1jtCnm0AqAunEtsX2vERzuOK', '2026-04-16 04:58:31', '2026-04-17 12:19:56', '1776427933_69e2239d82da3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT 'India',
  `organization` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `anniversary_date` date DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `spouse_name` varchar(255) DEFAULT NULL,
  `no_of_children` int(11) DEFAULT NULL,
  `boys` int(11) DEFAULT NULL,
  `girls` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `user_id`, `phone`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `organization`, `designation`, `additional_info`, `created_at`, `updated_at`, `anniversary_date`, `date_of_birth`, `spouse_name`, `no_of_children`, `boys`, `girls`) VALUES
(1, 1, '+1.364.738.5315', '439 Corwin Islands Apt. 068', 'Suite 482', 'North Silasfort', 'Nevada', '84083-6868', 'India', 'Runolfsson-Barrows', 'Postal Service Mail Sorter', 'Harum ab incidunt aspernatur suscipit nihil cum.', '2026-04-16 04:58:30', '2026-04-16 04:58:30', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, '1-352-476-5491', '36373 Cesar Mountains', 'Suite 019', 'Vanessafurt', 'District of Columbia', '48438', 'India', 'Botsford LLC', 'Travel Clerk', 'Est unde quibusdam cupiditate.', '2026-04-16 04:58:30', '2026-04-16 04:58:30', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, '346-304-1738', '55534 Hammes Tunnel', 'Suite 930', 'Lake Ivahstad', 'California', '37716', 'India', 'Rippin, Hartmann and Kreiger', 'Manager Tactical Operations', 'Accusamus sapiente corrupti itaque qui quae.', '2026-04-16 04:58:31', '2026-04-16 04:58:31', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, '+1-760-346-7658', '2714 Ratke Mills Suite 247', 'Apt. 522', 'Lilliestad', 'Florida', '67686-2321', 'India', 'Kemmer, Lubowitz and Wilderman', 'School Bus Driver', 'Ab illo sit quia et.', '2026-04-16 04:58:31', '2026-04-16 04:58:31', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 5, '814.332.5138', '432 Legros Vista Suite 775', 'Suite 268', 'Auerhaven', 'Florida', '49434-3704', 'India', 'Mohr Ltd', 'Environmental Engineering Technician', 'Veritatis quis assumenda consequatur enim voluptas sed delectus.', '2026-04-16 04:58:31', '2026-04-16 04:58:31', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 6, '+91  9876352644', 'Ranchi , Jharkhand', NULL, 'Ranchi', 'Jharkhand', '123456', 'India', 'JESA', 'Chief Eng.', 'I\'m  Engineer', '2026-04-17 12:07:40', '2026-04-17 12:17:53', '2024-10-20', '2000-04-13', 'Kiran  Kumari', 2, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_logs`
--
ALTER TABLE `otp_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otp_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_details_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `otp_logs`
--
ALTER TABLE `otp_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD CONSTRAINT `login_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `otp_logs`
--
ALTER TABLE `otp_logs`
  ADD CONSTRAINT `otp_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `user_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
