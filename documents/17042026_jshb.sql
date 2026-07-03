-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2026 at 01:12 PM
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
-- Database: `17042026_jshb`
--

-- --------------------------------------------------------

--
-- Table structure for table `allottees`
--

CREATE TABLE `allottees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subdivision_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `property_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `p_sub_type_id` int(11) DEFAULT NULL,
  `quarter_id` bigint(20) UNSIGNED DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `scheme_id` bigint(20) UNSIGNED DEFAULT NULL,
  `application_no` varchar(100) DEFAULT NULL,
  `application_day` tinyint(4) DEFAULT NULL,
  `application_month` varchar(4) DEFAULT NULL,
  `application_year` year(4) DEFAULT NULL,
  `allotment_no` varchar(100) DEFAULT NULL,
  `allotment_day` tinyint(4) DEFAULT NULL,
  `allotment_month` varchar(4) DEFAULT NULL,
  `allotment_year` year(4) DEFAULT NULL,
  `property_number` varchar(100) DEFAULT NULL,
  `prefix` varchar(20) DEFAULT NULL,
  `allottee_name` varchar(100) DEFAULT NULL,
  `allottee_middle_name` varchar(100) DEFAULT NULL,
  `allottee_surname` varchar(100) DEFAULT NULL,
  `allottee_prefix_hindi` varchar(20) DEFAULT NULL,
  `allottee_name_hindi` varchar(100) DEFAULT NULL,
  `allottee_middle_hindi` varchar(100) DEFAULT NULL,
  `allottee_surname_hindi` varchar(100) DEFAULT NULL,
  `allottee_relation_type` varchar(20) DEFAULT NULL,
  `relation_name` varchar(150) DEFAULT NULL,
  `marital_status` varchar(20) DEFAULT NULL,
  `allottee_gender` varchar(20) DEFAULT NULL,
  `pan_card_number` varchar(10) DEFAULT NULL,
  `aadhar_card_number` varchar(12) DEFAULT NULL,
  `allottee_category` varchar(30) DEFAULT NULL,
  `allottee_religion` varchar(30) DEFAULT NULL,
  `allottee_nationality` varchar(50) DEFAULT NULL,
  `date_of_birth_day` tinyint(4) DEFAULT NULL,
  `date_of_birth_month` varchar(4) DEFAULT NULL,
  `date_of_birth_year` year(4) DEFAULT NULL,
  `allottee_remarks` varchar(255) DEFAULT NULL,
  `payment_option` enum('emi','one_time') DEFAULT NULL,
  `current_step` int(11) DEFAULT 1,
  `is_step_completed` int(11) NOT NULL DEFAULT 0,
  `allottee_create_date` date DEFAULT NULL,
  `create_ip_address` varchar(100) DEFAULT NULL,
  `update_ip_address` varchar(100) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allottees`
--

INSERT INTO `allottees` (`id`, `division_id`, `subdivision_id`, `pcategory_id`, `property_type_id`, `p_sub_type_id`, `quarter_id`, `username`, `password`, `scheme_id`, `application_no`, `application_day`, `application_month`, `application_year`, `allotment_no`, `allotment_day`, `allotment_month`, `allotment_year`, `property_number`, `prefix`, `allottee_name`, `allottee_middle_name`, `allottee_surname`, `allottee_prefix_hindi`, `allottee_name_hindi`, `allottee_middle_hindi`, `allottee_surname_hindi`, `allottee_relation_type`, `relation_name`, `marital_status`, `allottee_gender`, `pan_card_number`, `aadhar_card_number`, `allottee_category`, `allottee_religion`, `allottee_nationality`, `date_of_birth_day`, `date_of_birth_month`, `date_of_birth_year`, `allottee_remarks`, `payment_option`, `current_step`, `is_step_completed`, `allottee_create_date`, `create_ip_address`, `update_ip_address`, `updated_by`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 1, 1, 1, 3, 'RNCLIHRM87405', '$2y$12$34PV.Ytf3AN4K2GKvLgZOOFGOScclz6FymY5hPWIOWyClBxOqOvGq', 5, 'JSR29473GJ98723', 4, '05', '2021', '001/EUS/975/2026', 3, '06', '2026', 'A-0980', 'Shri', 'Krishna', 'Kumar', 'Rao', 'श्री', 'vkoaVu', 'vkns’k', 'la[;k', 'Father', 'Shiv Narayan', 'Married', 'Male', 'ABCDE123F', '985479357893', 'General', 'Hindu', 'Indian', 1, '02', '1986', NULL, 'emi', 1, 1, NULL, '127.0.0.1', '127.0.0.1', 6, 6, '2026-06-03 07:03:59', '2026-06-23 09:57:54', NULL),
(2, 2, 5, 1, 1, 1, 2, 'JSRHIDND28657', '$2y$12$Bo53BTk42B1dMTJsW5jOae4a9UGQw6NnlRUVCMCtbKhwmwZ4omG.S', 4, 'JSR295489399997', 2, '04', '2021', '002/OVF/983/2026', 4, '06', '2026', NULL, 'Shri', 'Prince', NULL, 'Rao', 'श्री', 'संजीव', NULL, 'सिंह', 'Father', 'Upendra Narain', 'Unmarried', 'Male', 'ABCDE123F', '985479357893', 'General', 'Hindu', 'Indian', 2, '02', '1979', NULL, NULL, 3, 1, NULL, '127.0.0.1', '127.0.0.1', 6, 6, '2026-06-04 05:15:48', '2026-06-23 07:04:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `allottees_contact_details`
--

CREATE TABLE `allottees_contact_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allottee_id` bigint(20) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `alternate_mobile` varchar(15) DEFAULT NULL,
  `stdCode` varchar(10) DEFAULT NULL,
  `landline` varchar(15) DEFAULT NULL,
  `whatsapp_number` varchar(15) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `relation_type` varchar(50) DEFAULT NULL,
  `prefix_relation_eng` varchar(20) DEFAULT NULL,
  `relation_name` varchar(150) DEFAULT NULL,
  `prefix_relation_hindi` varchar(20) DEFAULT NULL,
  `relation_name_hindi` varchar(150) DEFAULT NULL,
  `relation_address` text DEFAULT NULL,
  `relation_address_hindi` text DEFAULT NULL,
  `relation_state` int(11) DEFAULT NULL,
  `relation_district` int(11) DEFAULT NULL,
  `relation_pincode` varchar(10) DEFAULT NULL,
  `relation_post_office` varchar(150) DEFAULT NULL,
  `relation_post_office_hindi` varchar(150) DEFAULT NULL,
  `relation_police_station` varchar(150) DEFAULT NULL,
  `relation_police_station_hindi` varchar(150) DEFAULT NULL,
  `same_as_relation_copy` varchar(10) DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `present_address_hindi` text DEFAULT NULL,
  `present_state` int(11) DEFAULT NULL,
  `present_district` int(11) DEFAULT NULL,
  `present_pincode` varchar(10) DEFAULT NULL,
  `present_post_office` varchar(150) DEFAULT NULL,
  `present_post_office_hindi` varchar(150) DEFAULT NULL,
  `present_police_station` varchar(150) DEFAULT NULL,
  `present_police_station_hindi` varchar(150) DEFAULT NULL,
  `same_as_present_place_residance` varchar(10) DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `permanent_address_hindi` text DEFAULT NULL,
  `permanent_state` int(11) DEFAULT NULL,
  `permanent_district` int(11) DEFAULT NULL,
  `permanent_pincode` varchar(10) DEFAULT NULL,
  `permanent_post_office` varchar(150) DEFAULT NULL,
  `permanent_post_office_hindi` varchar(150) DEFAULT NULL,
  `permanent_police_station` varchar(150) DEFAULT NULL,
  `permanent_police_station_hindi` varchar(150) DEFAULT NULL,
  `same_as_permanent_address` varchar(10) DEFAULT NULL,
  `correspondence_address` text DEFAULT NULL,
  `correspondence_address_hindi` text DEFAULT NULL,
  `correspondence_state` int(11) DEFAULT NULL,
  `correspondence_district` int(11) DEFAULT NULL,
  `correspondence_pincode` varchar(10) DEFAULT NULL,
  `correspondence_post_office` varchar(150) DEFAULT NULL,
  `correspondence_post_office_hindi` varchar(150) DEFAULT NULL,
  `correspondence_police_station` varchar(150) DEFAULT NULL,
  `correspondence_police_station_hindi` varchar(150) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_ip_address` varchar(45) DEFAULT NULL,
  `update_ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allottees_contact_details`
--

INSERT INTO `allottees_contact_details` (`id`, `allottee_id`, `mobile_number`, `alternate_mobile`, `stdCode`, `landline`, `whatsapp_number`, `email`, `relation_type`, `prefix_relation_eng`, `relation_name`, `prefix_relation_hindi`, `relation_name_hindi`, `relation_address`, `relation_address_hindi`, `relation_state`, `relation_district`, `relation_pincode`, `relation_post_office`, `relation_post_office_hindi`, `relation_police_station`, `relation_police_station_hindi`, `same_as_relation_copy`, `present_address`, `present_address_hindi`, `present_state`, `present_district`, `present_pincode`, `present_post_office`, `present_post_office_hindi`, `present_police_station`, `present_police_station_hindi`, `same_as_present_place_residance`, `permanent_address`, `permanent_address_hindi`, `permanent_state`, `permanent_district`, `permanent_pincode`, `permanent_post_office`, `permanent_post_office_hindi`, `permanent_police_station`, `permanent_police_station_hindi`, `same_as_permanent_address`, `correspondence_address`, `correspondence_address_hindi`, `correspondence_state`, `correspondence_district`, `correspondence_pincode`, `correspondence_post_office`, `correspondence_post_office_hindi`, `correspondence_police_station`, `correspondence_police_station_hindi`, `created_by`, `updated_by`, `create_ip_address`, `update_ip_address`, `created_at`, `updated_at`) VALUES
(1, 1, '8957698475', '9847569348', '02357', '9835749', '9873593857', 'krishnakumar0029@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'HARMU , RANCHI', NULL, 15, 281, '841428', 'Harmu Post Office', NULL, 'Harmu Police Station', NULL, 'on', 'HARMU , RANCHI', NULL, 15, 281, '841428', 'Harmu Post Office', NULL, 'Harmu Police Station', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 6, '127.0.0.1', '127.0.0.1', '2026-06-03 07:05:16', '2026-06-03 07:05:16'),
(2, 2, '8957698475', '9847569348', '02357', '9835749', '9873593857', 'sanjeevKumar0098@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dindli Basti , Adityapur', NULL, 15, 267, '841428', 'Jamshedpur', NULL, 'Jamshedpur', NULL, 'on', 'Dindli Basti , Adityapur', NULL, 15, 267, '841428', 'Jamshedpur', NULL, 'Jamshedpur', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 6, '127.0.0.1', '127.0.0.1', '2026-06-04 05:20:50', '2026-06-04 05:20:50');

-- --------------------------------------------------------

--
-- Table structure for table `allottee_documents`
--

CREATE TABLE `allottee_documents` (
  `id` bigint(20) NOT NULL,
  `allottee_id` bigint(20) NOT NULL,
  `document_id` int(11) NOT NULL,
  `doc_no` varchar(100) DEFAULT NULL,
  `doc_day` char(2) DEFAULT NULL,
  `doc_month` char(2) DEFAULT NULL,
  `doc_year` char(4) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `is_sadmin_read` int(11) NOT NULL DEFAULT 0,
  `sadmin_read_date` varchar(100) DEFAULT NULL,
  `is_divisional_read` int(11) NOT NULL DEFAULT 0,
  `divisional_read_date` varchar(100) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `allottee_emi_accounts`
--

CREATE TABLE `allottee_emi_accounts` (
  `id` bigint(20) NOT NULL,
  `allottee_id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `account_no` varchar(100) DEFAULT NULL,
  `principal_amount` decimal(15,2) DEFAULT 0.00,
  `annual_interest_rate` decimal(5,2) DEFAULT 0.00,
  `penalty_interest_rate` decimal(5,2) DEFAULT 0.00,
  `admin_charge` decimal(15,2) DEFAULT 0.00,
  `tenure_months` int(11) DEFAULT NULL,
  `emi_amount` decimal(15,2) DEFAULT 0.00,
  `total_interest` decimal(15,2) DEFAULT 0.00,
  `total_payable` decimal(15,2) DEFAULT 0.00,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `remaining_amount` decimal(15,2) DEFAULT 0.00,
  `emi_start_date` date DEFAULT NULL,
  `emi_end_date` date DEFAULT NULL,
  `account_status` enum('active','closed','defaulted','cancelled') DEFAULT 'active',
  `closed_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allottee_emi_accounts`
--

INSERT INTO `allottee_emi_accounts` (`id`, `allottee_id`, `order_id`, `account_no`, `principal_amount`, `annual_interest_rate`, `penalty_interest_rate`, `admin_charge`, `tenure_months`, `emi_amount`, `total_interest`, `total_payable`, `paid_amount`, `remaining_amount`, `emi_start_date`, `emi_end_date`, `account_status`, `closed_at`, `created_by`, `created_at`, `updated_at`) VALUES
(71, 1, 36, 'EMI-000001', 391500.00, 13.50, 2.50, 10.00, 60, 9008.35, 149001.00, 540501.00, 397005.61, 0.00, '2026-07-01', '2031-06-01', 'closed', '2026-06-23 15:28:26', 6, '2026-06-23 09:57:54', '2026-06-23 09:58:26');

-- --------------------------------------------------------

--
-- Table structure for table `allottee_emi_demands`
--

CREATE TABLE `allottee_emi_demands` (
  `id` int(11) NOT NULL,
  `allottee_id` int(11) NOT NULL,
  `emi_account_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `emi_no` int(11) DEFAULT NULL,
  `due_date` date NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `emi_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `interest_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `interest_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `penalty_interest_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `penalty_interest_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `principle_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `late_fine_penalty` decimal(15,2) NOT NULL DEFAULT 0.00,
  `penalty_admin_charges` decimal(15,2) NOT NULL DEFAULT 0.00,
  `annualized_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_demand_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `demand_status` enum('Pending','Partially Paid','Paid','Overdue') DEFAULT 'Pending',
  `is_late_payment` tinyint(1) DEFAULT 0,
  `outstanding_amount` decimal(15,2) DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `generated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allottee_emi_demands`
--

INSERT INTO `allottee_emi_demands` (`id`, `allottee_id`, `emi_account_id`, `order_id`, `emi_no`, `due_date`, `opening_balance`, `emi_amount`, `interest_rate`, `interest_amount`, `penalty_interest_rate`, `penalty_interest_amount`, `principle_amount`, `late_fine_penalty`, `penalty_admin_charges`, `annualized_amount`, `balance_amount`, `total_demand_amount`, `total_paid_amount`, `demand_status`, `is_late_payment`, `outstanding_amount`, `payment_date`, `remarks`, `paid_at`, `generated_at`, `created_at`, `created_by`, `updated_at`) VALUES
(80, 1, 71, 36, 1, '2026-07-07', 391500.00, 9008.35, 13.50, 4404.38, 2.50, 0.00, 295595.62, 0.00, 0.00, 395904.38, 95904.38, 9008.35, 300000.00, 'Paid', 0, 0.00, '2026-06-23', NULL, '2026-06-23 15:28:09', '2026-06-23 15:27:54', '2026-06-23 09:57:54', NULL, '2026-06-23 09:58:09'),
(81, 1, 71, 36, 2, '2026-08-07', 95904.38, 9008.35, 13.50, 1078.92, 2.50, 0.00, 93921.08, 0.00, 0.00, 96983.30, 1983.30, 9008.35, 95000.00, 'Paid', 0, 0.00, '2026-06-23', NULL, '2026-06-23 15:28:22', '2026-06-23 15:28:09', '2026-06-23 09:58:09', NULL, '2026-06-23 09:58:22'),
(82, 1, 71, 36, 3, '2026-09-07', 1983.30, 9008.35, 13.50, 22.31, 2.50, 0.00, 1983.30, 0.00, 0.00, 2005.61, 0.00, 2005.61, 2005.61, 'Paid', 0, 0.00, '2026-06-23', NULL, '2026-06-23 15:28:26', '2026-06-23 15:28:22', '2026-06-23 09:58:22', NULL, '2026-06-23 09:58:26');

-- --------------------------------------------------------

--
-- Table structure for table `allottee_emi_ledger`
--

CREATE TABLE `allottee_emi_ledger` (
  `id` bigint(20) NOT NULL,
  `allottee_id` bigint(20) DEFAULT NULL,
  `calculation_type` varchar(100) DEFAULT NULL,
  `total_amount` varchar(20) DEFAULT NULL,
  `total_emi_count` varchar(10) DEFAULT NULL,
  `start_month` varchar(10) DEFAULT NULL,
  `start_year` varchar(10) DEFAULT NULL,
  `last_emi_month` varchar(20) DEFAULT NULL,
  `last_emi_year` varchar(10) DEFAULT NULL,
  `amount_without_penalty` varchar(20) DEFAULT NULL,
  `amount_with_penalty` varchar(20) DEFAULT NULL,
  `without_penalty_count` varchar(10) DEFAULT NULL,
  `with_penalty_count` varchar(10) DEFAULT NULL,
  `completed_emi` varchar(10) DEFAULT NULL,
  `late_emi` varchar(10) DEFAULT NULL,
  `remaining_emi` varchar(10) DEFAULT NULL,
  `total_paid` varchar(20) DEFAULT NULL,
  `total_remaining` varchar(20) DEFAULT NULL,
  `current_balance` varchar(20) DEFAULT NULL,
  `emi_status` varchar(50) DEFAULT NULL,
  `expected_emi` varchar(10) DEFAULT NULL,
  `payment_gap` varchar(10) DEFAULT NULL,
  `emi_active` varchar(5) DEFAULT NULL,
  `emi_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`emi_config`)),
  `emi_inputs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`emi_inputs`)),
  `emi_timeline` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`emi_timeline`)),
  `emi_calculated` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`emi_calculated`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `allottee_emi_schedules`
--

CREATE TABLE `allottee_emi_schedules` (
  `id` bigint(20) NOT NULL,
  `emi_account_id` bigint(20) NOT NULL,
  `allottee_id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `emi_no` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `opening_principal` decimal(15,2) DEFAULT NULL,
  `emi_amount` decimal(15,2) DEFAULT NULL,
  `principal_component` decimal(15,2) DEFAULT NULL,
  `interest_component` decimal(15,2) DEFAULT NULL,
  `penalty_amount` decimal(15,2) DEFAULT 0.00,
  `admin_charge` decimal(15,2) DEFAULT 0.00,
  `total_payable` decimal(15,2) DEFAULT NULL,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `balance_amount` decimal(15,2) DEFAULT 0.00,
  `payment_status` enum('pending','partial','paid','overdue') DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `allottee_generated_documents`
--

CREATE TABLE `allottee_generated_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allottee_id` bigint(20) UNSIGNED NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_type` varchar(50) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `generated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `generated_at` timestamp NULL DEFAULT NULL,
  `issue_date` varchar(50) DEFAULT NULL,
  `document_number` varchar(150) DEFAULT NULL,
  `signed_file_name` varchar(255) DEFAULT NULL,
  `signed_file_path` varchar(500) DEFAULT NULL,
  `signed_uploaded_by` bigint(20) DEFAULT NULL,
  `signed_uploaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `allottee_generated_documents`
--

INSERT INTO `allottee_generated_documents` (`id`, `allottee_id`, `document_name`, `document_type`, `file_name`, `file_path`, `generated_by`, `generated_at`, `issue_date`, `document_number`, `signed_file_name`, `signed_file_path`, `signed_uploaded_by`, `signed_uploaded_at`, `created_at`, `updated_at`) VALUES
(83, 1, 'Allotment Letter', 'allotment-letter', 'allotment-letter-2026063132023-1923.pdf', 'documents/allotment-letter/generated/2026/06/3/allotment-letter-2026063132023-1923.pdf', 6, '2026-06-23 07:50:23', '2026-06-23', 'ALT23948', 'signed-allotment-letter-20260623132041-w2tiqV.pdf', 'documents/allotment-letter/signed/2026/06/23/signed-allotment-letter-20260623132041-w2tiqV.pdf', 6, '2026-06-23 07:50:41', '2026-06-23 07:50:23', '2026-06-23 07:50:41'),
(84, 1, 'Allotment Payment Receipt', 'allotment-payment-receipt', 'allotment-payment-receipt-35-20260623132044-4150.pdf', 'documents/allottee/payment/allotment/2026/06/23/allotment-payment-receipt-35-20260623132044-4150.pdf', 6, '2026-06-23 07:50:45', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-23 07:50:45', '2026-06-23 07:50:45'),
(85, 1, 'Possession Letter', 'possession-letter', 'possession-letter-2026063132054-6549.pdf', 'documents/possession-letter/generated/2026/06/23/possession-letter-2026063132054-6549.pdf', 6, '2026-06-23 07:50:54', '2026-06-23', 'PLT982374', 'signed-possession-letter-20260623132110-O8FFMV.pdf', 'documents/possession-letter/signed/2026/06/23/signed-possession-letter-20260623132110-O8FFMV.pdf', 6, '2026-06-23 07:51:10', '2026-06-23 07:50:54', '2026-06-23 07:51:10'),
(86, 1, 'Agreement', 'agreement', 'signed-agreement-20260623132128-vvDT3B.pdf', 'documents/agreement/signed/2026/06/23/signed-agreement-20260623132128-vvDT3B.pdf', 6, '2026-06-23 07:51:28', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-23 07:51:28', '2026-06-23 07:51:28'),
(99, 1, 'EMI Payment Receipt - July 2026', 'emi-payment-receipt', 'emi-payment-receipt-80-20260623152809-1423.pdf', 'documents/allottee/payment/emi/2026/06/23/emi-payment-receipt-80-20260623152809-1423.pdf', 6, '2026-06-23 09:58:10', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-23 09:58:10', '2026-06-23 09:58:10'),
(100, 1, 'EMI Payment Receipt - August 2026', 'emi-payment-receipt', 'emi-payment-receipt-81-20260623152822-9184.pdf', 'documents/allottee/payment/emi/2026/06/23/emi-payment-receipt-81-20260623152822-9184.pdf', 6, '2026-06-23 09:58:22', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-23 09:58:22', '2026-06-23 09:58:22'),
(101, 1, 'EMI Payment Receipt - September 2026', 'emi-payment-receipt', 'emi-payment-receipt-82-20260623152826-2413.pdf', 'documents/allottee/payment/emi/2026/06/23/emi-payment-receipt-82-20260623152826-2413.pdf', 6, '2026-06-23 09:58:26', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-23 09:58:26', '2026-06-23 09:58:26'),
(148, 1, 'Site Verification Report', 'Site Verification', 'site-verification-2026063131637-2863.pdf', 'documents/site-verification/pdf/2026/06/25/site-verification-2026063131637-2863.pdf', 6, '2026-06-25 07:46:37', '2026-06-25', 'SVR-20260625-7142', NULL, NULL, NULL, NULL, '2026-06-25 07:46:37', '2026-06-25 07:46:37');

-- --------------------------------------------------------

--
-- Table structure for table `allottee_ledger`
--

CREATE TABLE `allottee_ledger` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allottee_id` bigint(20) UNSIGNED NOT NULL,
  `emi_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `demand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `transaction_type` enum('lottery_payment','allotment_payment','principal_charge','interest_charge','penalty_charge','late_fee_charge','admin_charge','payment_received','refund','adjustment','loan_closed') NOT NULL,
  `transaction_mode` enum('debit','credit') NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `debit_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `running_principal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `running_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `reference_no` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `allottee_nominee_bank_details`
--

CREATE TABLE `allottee_nominee_bank_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allottee_id` bigint(20) UNSIGNED NOT NULL,
  `nominee_prefix` varchar(10) DEFAULT NULL,
  `nominee_name` varchar(150) DEFAULT NULL,
  `nominee_relationship` varchar(100) DEFAULT NULL,
  `nominee_pan_card` varchar(20) DEFAULT NULL,
  `nominee_aadhaar` varchar(20) DEFAULT NULL,
  `family_name_prefix` varchar(10) DEFAULT NULL,
  `family_name` varchar(150) DEFAULT NULL,
  `family_gender` varchar(20) DEFAULT NULL,
  `family_dob` date DEFAULT NULL,
  `family_relationship` varchar(100) DEFAULT NULL,
  `family_aadhaar` varchar(20) DEFAULT NULL,
  `family_pan` varchar(20) DEFAULT NULL,
  `bank_name` varchar(150) DEFAULT NULL,
  `bank_account_no` varchar(50) DEFAULT NULL,
  `bank_branch` varchar(150) DEFAULT NULL,
  `bank_ifsc` varchar(20) DEFAULT NULL,
  `bank_account_holder` varchar(150) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `create_ip_address` varchar(45) DEFAULT NULL,
  `update_ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `allottee_payment_orders`
--

CREATE TABLE `allottee_payment_orders` (
  `id` bigint(20) NOT NULL,
  `allottee_id` bigint(20) NOT NULL,
  `order_type` enum('lottery','allotment','emi','penalty','final','registry','other') DEFAULT NULL,
  `order_no` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `property_amount` decimal(15,2) DEFAULT 0.00,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `base_amount` decimal(15,2) DEFAULT 0.00,
  `penalty_amount` decimal(15,2) DEFAULT 0.00,
  `admin_charge` decimal(15,2) DEFAULT 0.00,
  `total_payable` decimal(15,2) DEFAULT 0.00,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `remaining_amount` decimal(15,2) DEFAULT 0.00,
  `payment_option` enum('emi','one_time') DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `issued_at` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `order_status` enum('draft','issued','partial','paid','overdue','cancelled') DEFAULT 'draft',
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allottee_payment_orders`
--

INSERT INTO `allottee_payment_orders` (`id`, `allottee_id`, `order_type`, `order_no`, `title`, `property_amount`, `percentage`, `base_amount`, `penalty_amount`, `admin_charge`, `total_payable`, `paid_amount`, `remaining_amount`, `payment_option`, `due_date`, `issued_at`, `paid_at`, `order_status`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(35, 1, 'allotment', 'ODR-ALT-20260623-203342', '15% Allotment Payment Order', 522000.00, 15.00, 78300.00, 0.00, 0.00, 78300.00, 78300.00, 0.00, NULL, '2026-07-23', '2026-06-23 13:20:41', '2026-06-23 13:20:44', 'paid', 'Payment received successfully', 6, '2026-06-23 07:50:41', '2026-06-23 07:50:44'),
(36, 1, 'emi', 'ORD-EMI-20260623-841583', '75% EMI Payment Order', 522000.00, 75.00, 391500.00, 0.00, 0.00, 391500.00, 0.00, 391500.00, 'emi', NULL, '2026-06-23 15:27:54', NULL, 'issued', 'Auto generated EMI order', 6, '2026-06-23 07:51:37', '2026-06-23 09:57:54');

-- --------------------------------------------------------

--
-- Table structure for table `allottee_process_steps`
--

CREATE TABLE `allottee_process_steps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allottee_id` bigint(20) UNSIGNED NOT NULL,
  `menu_order` int(11) DEFAULT NULL,
  `step_order` int(11) DEFAULT NULL,
  `icons` varchar(150) DEFAULT NULL,
  `menu_key` varchar(100) NOT NULL,
  `sub_menu_key` varchar(100) DEFAULT NULL,
  `process_group` varchar(100) DEFAULT NULL,
  `step_no` smallint(5) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `blade` varchar(150) DEFAULT NULL,
  `status` enum('locked','pending','in_progress','completed','rejected','cancelled','hidden') DEFAULT 'locked',
  `is_active` tinyint(1) DEFAULT 1,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `completed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allottee_process_steps`
--

INSERT INTO `allottee_process_steps` (`id`, `allottee_id`, `menu_order`, `step_order`, `icons`, `menu_key`, `sub_menu_key`, `process_group`, `step_no`, `title`, `description`, `blade`, `status`, `is_active`, `started_at`, `completed_at`, `due_date`, `remarks`, `meta`, `completed_by`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(717, 1, 1, 1, 'fa-solid fa-gauge-high', 'quick-overview', NULL, 'quick-overview', 1, 'Overview', 'Quick Overview', 'overview', 'completed', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\",\"null\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(718, 1, 2, 1, 'fa-solid fa-user', 'allottee-details', NULL, 'allottee-details', 2, 'Allottee Details', 'Allottee Details', 'allottee-details', 'completed', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\",\"null\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(719, 1, 3, 1, 'fa-solid fa-file-signature', 'letter-order-issued', NULL, 'letter-order-issued', 3, 'Letter/Orders Issued', 'Letter/Orders Issued', 'letter-order-issued', 'completed', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\",\"null\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(720, 1, 4, 1, 'fa-solid fa-money-check-dollar', 'lottery', 'payment-details', 'lottery', 4, 'Lottery Payment', 'Lottery related activities', 'payment-details', 'completed', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\",\"null\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(721, 1, 5, 1, 'fa-solid fa-file-lines', 'allotment', 'generate-allotment', 'allotment', 5, 'Allotment Letter', 'Allotment related activities', 'allotment-letter', 'completed', 1, '2026-06-23 07:05:58', '2026-06-23 07:50:41', NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\",\"null\"]}}', 6, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:50:41'),
(722, 1, 5, 2, 'fa-solid fa-file-invoice-dollar', 'allotment', 'allotment-demand-note', 'allotment', 6, '15% Demand Note', 'Allotment related activities', 'initial-payment', 'completed', 1, '2026-06-23 07:05:58', '2026-06-23 07:50:45', NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\",\"null\"]}}', 6, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:50:45'),
(723, 1, 5, 3, 'fa-solid fa-key', 'allotment', 'allotment-possession-letter', 'allotment', 7, 'Possession Letter', 'Allotment related activities', 'allotment-possession-letter', 'completed', 1, '2026-06-23 07:05:58', '2026-06-23 07:51:10', NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\",\"null\"]}}', 6, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:51:10'),
(724, 1, 5, 4, 'fa-solid fa-key', 'allotment', 'agreement-document-letter', 'allotment', 8, 'Agreement', 'Allotment related activities', 'allotment-agreement-letter', 'completed', 1, '2026-06-23 07:05:58', '2026-06-23 07:51:28', NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\",\"null\"]}}', 6, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:51:28'),
(725, 1, 6, 1, 'fa-solid fa-wallet', 'choose-payment-option', NULL, 'choose-payment-option', 9, 'Choose Payment Option', 'Choose EMI or One Time Payment', 'choose-payment-option', 'pending', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":false,\"visible_if\":{\"payment_option\":[\"null\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:51:28'),
(726, 1, 7, 1, 'fa-solid fa-indian-rupee-sign', 'property-payment', 'one-time-payment', 'property-payment', 10, 'One Time Payment', 'One time property payment', 'one-time-payment', 'locked', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":false,\"visible_if\":{\"payment_option\":[\"one_time\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(727, 1, 7, 2, 'fa-solid fa-clock-rotate-left', 'property-payment', 'payment-history', 'property-payment', 11, 'Payment History', 'One time property payment', 'payment-history', 'locked', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":false,\"visible_if\":{\"payment_option\":[\"one_time\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(728, 1, 8, 1, 'fa-solid fa-chart-line', 'emi-management', 'emi-dashboard', 'emi-management', 12, 'EMI Dashboard', 'EMI Management', 'emi-dashboard', 'completed', 1, '2026-06-23 07:05:58', '2026-06-23 09:58:26', NULL, NULL, '{\"always_show\":false,\"visible_if\":{\"payment_option\":[\"emi\"]}}', 6, 6, 6, '2026-06-23 07:05:58', '2026-06-23 09:58:26'),
(729, 1, 8, 2, 'fa-solid fa-credit-card', 'emi-management', 'monthly-emi', 'emi-management', 13, 'Pay EMI', 'EMI Management', 'monthly-emi', 'completed', 1, '2026-06-23 07:05:58', '2026-06-23 09:58:26', NULL, NULL, '{\"always_show\":false,\"visible_if\":{\"payment_option\":[\"emi\"]}}', 6, 6, 6, '2026-06-23 07:05:58', '2026-06-23 09:58:26'),
(730, 1, 8, 3, 'fa-solid fa-calendar-check', 'emi-management', 'emi-schedule', 'emi-management', 14, 'EMI Schedule', 'EMI Management', 'emi-schedule', 'completed', 1, '2026-06-23 07:05:58', '2026-06-23 09:58:26', NULL, NULL, '{\"always_show\":false,\"visible_if\":{\"payment_option\":[\"emi\"]}}', 6, 6, 6, '2026-06-23 07:05:58', '2026-06-23 09:58:26'),
(731, 1, 8, 4, 'fa-solid fa-receipt', 'emi-management', 'emi-history', 'emi-management', 15, 'EMI History', 'EMI Management', 'emi-history', 'completed', 1, '2026-06-23 07:05:58', '2026-06-23 09:58:26', NULL, NULL, '{\"always_show\":false,\"visible_if\":{\"payment_option\":[\"emi\"]}}', 6, 6, 6, '2026-06-23 07:05:58', '2026-06-23 09:58:26'),
(732, 1, 9, 1, 'fa-solid fa-location-dot', 'noc', 'site-verification', 'noc', 16, 'Site Verification', 'NOC related process', 'site-verification', 'completed', 1, '2026-06-23 07:05:58', '2026-06-25 09:07:35', NULL, 'Site Verification completed', '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\"]}}', 6, 6, 6, '2026-06-23 07:05:58', '2026-06-25 09:07:35'),
(733, 1, 9, 2, 'fa-solid fa-ruler-combined', 'noc', 'extra-construction-calculation', 'noc', 17, 'Extra Construction', 'NOC related process', 'extra-construction-calculation', 'completed', 1, '2026-06-23 07:05:58', '2026-06-25 09:20:24', NULL, 'Site Verification completed', '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\"]}}', 6, 6, 6, '2026-06-23 07:05:58', '2026-06-25 09:20:24'),
(734, 1, 9, 3, 'fa-solid fa-file-circle-plus', 'noc', 'generate-noc', 'noc', 18, 'Generate NOC', 'NOC related process', 'generate-noc', 'pending', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-25 09:20:24'),
(735, 1, 10, 1, 'fa-solid fa-calculator', 'final-calculation', 'final-calculate-value', 'final-calculation', 19, 'Calculate Value', 'Final calculation process', 'final-calculate-value', 'locked', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(736, 1, 10, 2, 'fa-solid fa-file-invoice', 'final-calculation', 'final-payment-demand-note', 'final-calculation', 20, 'Payment Demand Note', 'Final calculation process', 'final-payment-demand-note', 'locked', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(737, 1, 10, 3, 'fa-solid fa-envelope-open-text', 'final-calculation', 'final-generate-letter', 'final-calculation', 21, 'Generate Letter', 'Final calculation process', 'final-generate-letter', 'locked', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(738, 1, 11, 1, 'fa-solid fa-folder-open', 'registry', 'request-for-documentation', 'registry', 22, 'Documentation Request', 'Registry related process', 'request-for-documentation', 'locked', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(739, 1, 11, 2, 'fa-solid fa-upload', 'registry', 'upload-registry-deed', 'registry', 23, 'Upload Registry Deed', 'Registry related process', 'upload-registry-deed', 'locked', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(740, 1, 11, 3, 'fa-solid fa-file-shield', 'registry', 'registry-generate-noc', 'registry', 24, 'Generate Registry NOC', 'Registry related process', 'registry-generate-noc', 'locked', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(741, 1, 12, 1, 'fa-solid fa-user-pen', 'name-transfer', NULL, 'name-transfer', 25, 'Name Transfer', 'Name transfer process', 'name-transfer', 'locked', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(742, 1, 13, 1, 'fa-solid fa-building-shield', 'lease-free-hold', NULL, 'lease-free-hold', 26, 'Lease Free Hold', 'Lease free hold process', 'lease-free-hold', 'locked', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"emi\",\"one_time\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58'),
(743, 1, 14, 1, 'fa-solid fa-ban', 'allotment-cancellation', NULL, 'allotment-cancellation', 27, 'Allotment Cancellation', 'Allotment cancellation process', 'allotment-cancellation', 'locked', 1, '2026-06-23 07:05:58', NULL, NULL, NULL, '{\"always_show\":true,\"visible_if\":{\"payment_option\":[\"null\"]}}', NULL, 6, 6, '2026-06-23 07:05:58', '2026-06-23 07:05:58');

-- --------------------------------------------------------

--
-- Table structure for table `allottee_property_fin_details`
--

CREATE TABLE `allottee_property_fin_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allottee_id` bigint(20) UNSIGNED NOT NULL,
  `tentative_price` decimal(12,2) DEFAULT NULL,
  `amount_words` varchar(255) DEFAULT NULL,
  `maav_day` tinyint(4) DEFAULT NULL,
  `maav_month` tinyint(4) DEFAULT NULL,
  `maav_year` year(4) DEFAULT NULL,
  `deposit_type` varchar(100) DEFAULT 'amount',
  `high_income_percent` decimal(5,2) DEFAULT NULL,
  `low_income_percent` decimal(5,2) DEFAULT NULL,
  `deposited_amount` decimal(12,2) DEFAULT NULL,
  `legal_fee` decimal(10,2) DEFAULT NULL COMMENT 'As EMD Amount',
  `legal_document_fee` decimal(10,2) DEFAULT NULL COMMENT 'As Administration Fee',
  `total_payment` decimal(12,2) DEFAULT NULL,
  `interim_price` decimal(12,2) DEFAULT NULL,
  `remaining_amount` decimal(12,2) DEFAULT NULL,
  `payment_months` int(11) DEFAULT NULL,
  `payment_start_month` tinyint(4) DEFAULT NULL,
  `payment_start_year` year(4) DEFAULT NULL,
  `last_payment_due_date` varchar(50) DEFAULT NULL,
  `interest_calculation_mode` varchar(100) DEFAULT NULL,
  `interest_type` enum('simple','compound') DEFAULT NULL,
  `pre_interest` decimal(5,2) DEFAULT NULL,
  `late_interest` decimal(5,2) DEFAULT NULL,
  `pre_interest_amount` decimal(12,2) DEFAULT NULL,
  `late_interest_amount` decimal(12,2) DEFAULT NULL,
  `allot_day` tinyint(4) DEFAULT NULL,
  `allot_month` tinyint(4) DEFAULT NULL,
  `allot_year` year(4) DEFAULT NULL,
  `lottery_details` varchar(255) DEFAULT NULL,
  `colony_name` varchar(255) DEFAULT NULL,
  `plot_number` varchar(100) DEFAULT NULL,
  `area_sqft` decimal(10,2) DEFAULT NULL,
  `mohalla` varchar(150) DEFAULT NULL,
  `post_office` varchar(150) DEFAULT NULL,
  `city` varchar(150) DEFAULT NULL,
  `police_station` varchar(150) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `district` int(11) DEFAULT NULL,
  `north_boundary` varchar(100) DEFAULT NULL,
  `south_boundary` varchar(100) DEFAULT NULL,
  `east_boundary` varchar(100) DEFAULT NULL,
  `west_boundary` varchar(100) DEFAULT NULL,
  `ew_north` varchar(100) DEFAULT NULL,
  `ew_south` varchar(100) DEFAULT NULL,
  `ns_east` varchar(100) DEFAULT NULL,
  `ns_west` varchar(100) DEFAULT NULL,
  `specified_days` varchar(50) DEFAULT NULL,
  `last_day` tinyint(4) DEFAULT NULL,
  `last_month` tinyint(4) DEFAULT NULL,
  `last_year` year(4) DEFAULT NULL,
  `created_ip` varchar(45) DEFAULT NULL,
  `updated_ip` varchar(45) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `allottee_site_verifications`
--

CREATE TABLE `allottee_site_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allottee_id` bigint(20) UNSIGNED NOT NULL,
  `colony_name` varchar(255) DEFAULT NULL,
  `allottee_name` varchar(255) DEFAULT NULL,
  `unit_number` varchar(255) DEFAULT NULL,
  `unit_use` varchar(255) DEFAULT NULL,
  `road_front` varchar(255) DEFAULT NULL,
  `road_beside` varchar(255) DEFAULT NULL,
  `plot_size_allotment` varchar(255) DEFAULT NULL,
  `plot_size_agreement` varchar(255) DEFAULT NULL,
  `plot_size_possession` varchar(255) DEFAULT NULL,
  `plot_size_difference_reason` varchar(255) DEFAULT NULL,
  `encroachment_area` varchar(255) DEFAULT NULL,
  `encroachment_public_use` varchar(255) DEFAULT NULL,
  `encroachment_independent_use` varchar(255) DEFAULT NULL,
  `encroachment_future_use` varchar(255) DEFAULT NULL,
  `encroachment_settlement` varchar(255) DEFAULT NULL,
  `is_house_constructed` varchar(255) DEFAULT NULL,
  `approved_map_authority` varchar(255) DEFAULT NULL,
  `approved_map_case` varchar(255) DEFAULT NULL,
  `approved_map_date` date DEFAULT NULL,
  `is_construction_as_per_map` varchar(255) DEFAULT NULL,
  `alteration_map_authority` varchar(255) DEFAULT NULL,
  `alteration_map_case` varchar(255) DEFAULT NULL,
  `alteration_map_date` date DEFAULT NULL,
  `map_parameters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`map_parameters`)),
  `map_image` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `allottee_site_verifications`
--

INSERT INTO `allottee_site_verifications` (`id`, `allottee_id`, `colony_name`, `allottee_name`, `unit_number`, `unit_use`, `road_front`, `road_beside`, `plot_size_allotment`, `plot_size_agreement`, `plot_size_possession`, `plot_size_difference_reason`, `encroachment_area`, `encroachment_public_use`, `encroachment_independent_use`, `encroachment_future_use`, `encroachment_settlement`, `is_house_constructed`, `approved_map_authority`, `approved_map_case`, `approved_map_date`, `is_construction_as_per_map`, `alteration_map_authority`, `alteration_map_case`, `alteration_map_date`, `map_parameters`, `map_image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Harmu Housing', 'Shri Krishna Kumar Rao', 'A-0980', 'Residential', '80\'0\'\'', 'No', NULL, '90\'0\'\'x50\'0\'\' = 4500 sq ft', '90\'0\'\'x50\'0\'\' = 4500 sq ft', 'No', '4500 sq ft', 'no', 'no', 'no', 'no', 'no', 'Shivam Kumar', '987546', '2026-06-01', 'no', 'Shivam Kumar', '987546', '2026-06-01', '{\"plotNo\":\"A-0980\",\"north\":\"90\",\"northLabel\":\"PLOT NO. 23\",\"south\":\"90\",\"southLabel\":\"5.00 M. WIDE ROAD\",\"east\":\"50\",\"eastLabel\":\"PLOT NO. 21\",\"west\":\"50\",\"westLabel\":\"BOARD\'S LAND\"}', 'documents/site-verification/photo/2026/06/25/site-verification-map-2026063131637-4852.png', '2026-06-23 11:05:31', '2026-06-25 08:53:47');

-- --------------------------------------------------------

--
-- Table structure for table `allottee_step_durations`
--

CREATE TABLE `allottee_step_durations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allottee_id` bigint(20) UNSIGNED NOT NULL,
  `step_no` tinyint(4) NOT NULL,
  `started_at` varchar(100) DEFAULT NULL,
  `completed_at` varchar(100) DEFAULT NULL,
  `duration_min` int(11) DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `allottee_transactions`
--

CREATE TABLE `allottee_transactions` (
  `id` bigint(20) NOT NULL,
  `allottee_id` bigint(20) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `demand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_type` enum('lottery_payment','lottery_refund','allotment_payment','emi_payment','penalty_payment','one_time_payment','extra_payment','refund','adjustment') DEFAULT NULL,
  `payment_stage` enum('application','allotment','emi','closure') DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT 0.00,
  `principal_amount` decimal(15,2) DEFAULT 0.00,
  `interest_amount` decimal(15,2) DEFAULT 0.00,
  `penalty_amount` decimal(15,2) DEFAULT 0.00,
  `admin_charge` decimal(15,2) DEFAULT 0.00,
  `total_amount` decimal(15,2) DEFAULT 0.00,
  `payment_mode` enum('cash','cheque','dd','upi','netbanking','gateway','adjustment') DEFAULT NULL,
  `payment_status` enum('initiated','success','failed','refunded') DEFAULT 'initiated',
  `transaction_no` varchar(255) DEFAULT NULL,
  `utr_no` varchar(255) DEFAULT NULL,
  `receipt_file` varchar(500) DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `payment_day` int(11) DEFAULT NULL,
  `payment_month` int(11) DEFAULT NULL,
  `payment_year` int(11) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allottee_transactions`
--

INSERT INTO `allottee_transactions` (`id`, `allottee_id`, `order_id`, `demand_id`, `transaction_type`, `payment_stage`, `amount`, `principal_amount`, `interest_amount`, `penalty_amount`, `admin_charge`, `total_amount`, `payment_mode`, `payment_status`, `transaction_no`, `utr_no`, `receipt_file`, `receipt_path`, `remarks`, `payment_day`, `payment_month`, `payment_year`, `paid_at`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 'lottery_payment', 'application', 52200.00, 52200.00, 0.00, 0.00, 0.00, 52200.00, 'cheque', 'success', 'TXN-6A1FD1DF67727', '823GHJ28429', 'payment-receipt-130808-123359-3641.jpg', 'uploads/payments/2013/08/08/payment-receipt-130808-123359-3641.jpg', 'pending', 8, 8, 2013, '2013-08-08 13:26:51', 6, '2026-06-03 07:03:59', '2026-06-09 07:56:51'),
(9, 2, NULL, NULL, 'lottery_payment', 'application', 208800.00, 208800.00, 0.00, 0.00, 0.00, 208800.00, 'cheque', 'success', 'TXN-6A210A04D2F29', '347GHHJT884723', 'payment-receipt-180509-104548-7672.jpg', 'uploads/payments/2018/05/09/payment-receipt-180509-104548-7672.jpg', 'pending', 9, 5, 2018, '2018-05-09 10:45:48', 6, '2026-06-04 05:15:48', '2026-06-04 05:15:48'),
(69, 1, 35, NULL, 'allotment_payment', 'allotment', 78300.00, 78300.00, 0.00, 0.00, 0.00, 78300.00, 'gateway', 'success', 'TXN202606231320445666', NULL, 'allotment-payment-receipt-35-20260623132044-4150.pdf', 'documents/allottee/payment/allotment/2026/06/23/allotment-payment-receipt-35-20260623132044-4150.pdf', 'Allotment payment successful', NULL, NULL, NULL, '2026-06-23 13:20:44', 6, '2026-06-23 07:50:44', '2026-06-23 07:50:45'),
(82, 1, 36, 80, 'emi_payment', 'emi', 300000.00, 295595.62, 4404.38, 0.00, 0.00, 300000.00, 'gateway', 'success', 'TXN-6A3A58B1B0DFF', NULL, 'emi-payment-receipt-80-20260623152809-1423.pdf', 'documents/allottee/payment/emi/2026/06/23/emi-payment-receipt-80-20260623152809-1423.pdf', NULL, 23, 6, 2026, '2026-06-23 15:28:09', 6, '2026-06-23 09:58:09', '2026-06-23 09:58:10'),
(83, 1, 36, 81, 'emi_payment', 'emi', 95000.00, 93921.08, 1078.92, 0.00, 0.00, 95000.00, 'gateway', 'success', 'TXN-6A3A58BE68B5F', NULL, 'emi-payment-receipt-81-20260623152822-9184.pdf', 'documents/allottee/payment/emi/2026/06/23/emi-payment-receipt-81-20260623152822-9184.pdf', NULL, 23, 6, 2026, '2026-06-23 15:28:22', 6, '2026-06-23 09:58:22', '2026-06-23 09:58:22'),
(84, 1, 36, 82, 'emi_payment', 'emi', 2005.61, 1983.30, 22.31, 0.00, 0.00, 2005.61, 'gateway', 'success', 'TXN-6A3A58C210405', NULL, 'emi-payment-receipt-82-20260623152826-2413.pdf', 'documents/allottee/payment/emi/2026/06/23/emi-payment-receipt-82-20260623152826-2413.pdf', NULL, 23, 6, 2026, '2026-06-23 15:28:26', 6, '2026-06-23 09:58:26', '2026-06-23 09:58:26');

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
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(10) UNSIGNED NOT NULL,
  `state_id` int(10) UNSIGNED NOT NULL,
  `name_en` varchar(150) NOT NULL,
  `name_hi` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `state_id`, `name_en`, `name_hi`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nicobar', 'निकोबार', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(2, 1, 'North and Middle Andaman', 'उत्तर और मध्य अंडमान', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(3, 1, 'South Andaman', 'दक्षिण अंडमान', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(4, 2, 'Alluri Sitharama Raju', 'अल्लूरी सीताराम राजू', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(5, 2, 'Anakapalli', 'अनकापल्ली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(6, 2, 'Anantapur', 'अनंतपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(7, 2, 'Annamayya', 'अन्नमय्या', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(8, 2, 'Bapatla', 'बपटला', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(9, 2, 'Chittoor', 'चित्तूर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(10, 2, 'Dr. B.R. Ambedkar Konaseema', 'डॉ. बी.आर. अंबेडकर कोनसीमा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(11, 2, 'East Godavari', 'पूर्व गोदावरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(12, 2, 'Eluru', 'एलुरु', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(13, 2, 'Guntur', 'गुंटूर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(14, 2, 'Kakinada', 'काकिनाडा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(15, 2, 'Krishna', 'कृष्णा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(16, 2, 'Kurnool', 'कुरनूल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(17, 2, 'Nandyal', 'नंद्याल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(18, 2, 'NTR', 'एन.टी.आर.', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(19, 2, 'Palnadu', 'पालनाडु', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(20, 2, 'Parvathipuram Manyam', 'पार्वतीपुरम मन्यम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(21, 2, 'Prakasam', 'प्रकाशम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(22, 2, 'Sri Sathya Sai', 'श्री सत्य साई', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(23, 2, 'Sri Balaji', 'श्री बालाजी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(24, 2, 'Srikakulam', 'श्रीकाकुलम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(25, 2, 'Tirupati', 'तिरुपति', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(26, 2, 'Visakhapatnam', 'विशाखापत्तनम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(27, 2, 'Vizianagaram', 'विजयनगरम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(28, 2, 'West Godavari', 'पश्चिम गोदावरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(29, 2, 'YSR Kadapa', 'वाई.एस.आर. कडप्पा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(30, 3, 'Anjaw', 'अंजाव', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(31, 3, 'Changlang', 'चांगलांग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(32, 3, 'Dibang Valley', 'दिबांग घाटी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(33, 3, 'East Kameng', 'पूर्वी कामेंग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(34, 3, 'East Siang', 'पूर्वी सियांग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(35, 3, 'Kamle', 'कामले', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(36, 3, 'Kra Daadi', 'क्रा दादी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(37, 3, 'Kurung Kumey', 'कुरुंग कुमेय', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(38, 3, 'Lepa Rada', 'लेपा राडा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(39, 3, 'Lohit', 'लोहित', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(40, 3, 'Longding', 'लॉन्गदिंग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(41, 3, 'Lower Dibang Valley', 'निचली दिबांग घाटी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(42, 3, 'Lower Subansiri', 'निचली सुबनसिरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(43, 3, 'Namsai', 'नामसाई', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(44, 3, 'Pakke Kessang', 'पक्के केस्सांग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(45, 3, 'Papum Pare', 'पापुम पारे', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(46, 3, 'Shi Yomi', 'शी योमी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(47, 3, 'Siang', 'सियांग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(48, 3, 'Tawang', 'तवांग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(49, 3, 'Tirap', 'तिराप', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(50, 3, 'Upper Siang', 'ऊपरी सियांग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(51, 3, 'Upper Subansiri', 'ऊपरी सुबनसिरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(52, 3, 'West Kameng', 'पश्चिमी कामेंग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(53, 3, 'West Siang', 'पश्चिमी सियांग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(54, 4, 'Baksa', 'बक्सा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(55, 4, 'Barpeta', 'बारपेटा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(56, 4, 'Biswanath', 'बिश्वनाथ', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(57, 4, 'Bongaigaon', 'बोंगईगांव', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(58, 4, 'Cachar', 'कछार', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(59, 4, 'Charaideo', 'चराईदेव', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(60, 4, 'Chirang', 'चिरांग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(61, 4, 'Darrang', 'दरांग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(62, 4, 'Dhemaji', 'धेमाजी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(63, 4, 'Dhubri', 'धुबरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(64, 4, 'Dibrugarh', 'डिब्रूगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(65, 4, 'Dima Hasao', 'दिमा हसाओ', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(66, 4, 'Goalpara', 'गोआलपारा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(67, 4, 'Golaghat', 'गोलाघाट', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(68, 4, 'Hailakandi', 'हैलाकांडी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(69, 4, 'Hojai', 'होजाई', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(70, 4, 'Jorhat', 'जोरहाट', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(71, 4, 'Kamrup', 'कामरूप', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(72, 4, 'Kamrup Metropolitan', 'कामरूप महानगरीय', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(73, 4, 'Karbi Anglong', 'कार्बी आंगलोंग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(74, 4, 'Karimganj', 'करीमगंज', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(75, 4, 'Kokrajhar', 'कोकराझार', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(76, 4, 'Lakhimpur', 'लखीमपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(77, 4, 'Majuli', 'माजुली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(78, 4, 'Morigaon', 'मोरिगांव', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(79, 4, 'Nagaon', 'नगांव', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(80, 4, 'Nalbari', 'नलबाड़ी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(81, 4, 'Sivasagar', 'शिवसागर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(82, 4, 'Sonitpur', 'सोनितपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(83, 4, 'South Salmara Mankachar', 'दक्षिण सलमारा मानकचार', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(84, 4, 'Tinsukia', 'तिनसुकिया', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(85, 4, 'Udalguri', 'उदालगुड़ी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(86, 4, 'West Karbi Anglong', 'पश्चिम कार्बी आंगलोंग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(87, 5, 'Araria', 'अररिया', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(88, 5, 'Arwal', 'अरवल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(89, 5, 'Aurangabad', 'औरंगाबाद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(90, 5, 'Banka', 'बांका', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(91, 5, 'Begusarai', 'बेगूसराय', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(92, 5, 'Bhagalpur', 'भागलपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(93, 5, 'Bhojpur', 'भोजपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(94, 5, 'Buxar', 'बक्सर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(95, 5, 'Darbhanga', 'दरभंगा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(96, 5, 'East Champaran', 'पूर्वी चंपारण', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(97, 5, 'Gaya', 'गया', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(98, 5, 'Gopalganj', 'गोपालगंज', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(99, 5, 'Jamui', 'जमुई', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(100, 5, 'Jehanabad', 'जहानाबाद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(101, 5, 'Kaimur', 'कैमूर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(102, 5, 'Katihar', 'कटिहार', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(103, 5, 'Khagaria', 'खगड़िया', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(104, 5, 'Kishanganj', 'किशनगंज', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(105, 5, 'Lakhisarai', 'लखीसराय', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(106, 5, 'Madhepura', 'मधेपुरा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(107, 5, 'Madhubani', 'मधुबनी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(108, 5, 'Munger', 'मुंगेर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(109, 5, 'Muzaffarpur', 'मुजफ्फरपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(110, 5, 'Nalanda', 'नालंदा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(111, 5, 'Nawada', 'नवादा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(112, 5, 'Patna', 'पटना', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(113, 5, 'Purnia', 'पूर्णिया', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(114, 5, 'Rohtas', 'रोहतास', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(115, 5, 'Saharsa', 'सहरसा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(116, 5, 'Samastipur', 'समस्तीपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(117, 5, 'Saran', 'सारण', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(118, 5, 'Sheikhpura', 'शेखपुरा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(119, 5, 'Sheohar', 'शिवहर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(120, 5, 'Sitamarhi', 'सीतामढ़ी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(121, 5, 'Siwan', 'सिवान', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(122, 5, 'Supaul', 'सुपौल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(123, 5, 'Vaishali', 'वैशाली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(124, 5, 'West Champaran', 'पश्चिमी चंपारण', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(125, 6, 'Chandigarh', 'चंडीगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(126, 7, 'Balod', 'बालोद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(127, 7, 'Baloda Bazar', 'बलौदा बाजार', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(128, 7, 'Balrampur', 'बलरामपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(129, 7, 'Bastar', 'बस्तर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(130, 7, 'Bemetara', 'बेमेतरा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(131, 7, 'Bijapur', 'बीजापुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(132, 7, 'Bilaspur', 'बिलासपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(133, 7, 'Dantewada', 'दंतेवाड़ा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(134, 7, 'Dhamtari', 'धमतरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(135, 7, 'Durg', 'दुर्ग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(136, 7, 'Gariaband', 'गरियाबंद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(137, 7, 'Gaurela-Pendra-Marwahi', 'गौरेला-पेंड्रा-मारवाही', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(138, 7, 'Janjgir-Champa', 'जांजगीर-चांपा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(139, 7, 'Jashpur', 'जशपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(140, 7, 'Kabirdham', 'कबीरधाम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(141, 7, 'Kanker', 'कांकेर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(142, 7, 'Khairagarh-Chhuikhadan-Gandai', 'खैरागढ़-छुईखदान-गंडई', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(143, 7, 'Kondagaon', 'कोंडागांव', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(144, 7, 'Korba', 'कोरबा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(145, 7, 'Korea', 'कोरिया', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(146, 7, 'Mahasamund', 'महासमुंद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(147, 7, 'Manendragarh-Chirmiri-Bharatpur', 'मनेन्द्रगढ़-चिरमिरी-भारतपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(148, 7, 'Mohla-Manpur-Ambagarh', 'मोहला-मानपुर-अंबागढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(149, 7, 'Mungeli', 'मुंगेली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(150, 7, 'Narayanpur', 'नारायणपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(151, 7, 'Raigarh', 'रायगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(152, 7, 'Raipur', 'रायपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(153, 7, 'Rajnandgaon', 'राजनांदगांव', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(154, 7, 'Sakti', 'सकती', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(155, 7, 'Sarangarh-Bilaigarh', 'सारंगढ़-बिलाईगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(156, 7, 'Sukma', 'सुकमा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(157, 7, 'Surajpur', 'सूरजपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(158, 7, 'Surguja', 'सurguja', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(159, 8, 'Dadra and Nagar Haveli', 'दादरा और नगर हवेली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(160, 8, 'Daman', 'दमन', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(161, 8, 'Diu', 'दीव', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(162, 9, 'Central Delhi', 'मध्य दिल्ली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(163, 9, 'East Delhi', 'पूर्वी दिल्ली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(164, 9, 'New Delhi', 'नई दिल्ली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(165, 9, 'North Delhi', 'उत्तरी दिल्ली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(166, 9, 'North East Delhi', 'उत्तर-पूर्वी दिल्ली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(167, 9, 'North West Delhi', 'उत्तर-पश्चिमी दिल्ली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(168, 9, 'Shahdara', 'शाहदरा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(169, 9, 'South Delhi', 'दक्षिण दिल्ली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(170, 9, 'South East Delhi', 'दक्षिण-पूर्वी दिल्ली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(171, 9, 'South West Delhi', 'दक्षिण-पश्चिमी दिल्ली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(172, 9, 'West Delhi', 'पश्चिमी दिल्ली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(173, 10, 'North Goa', 'उत्तर गोवा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(174, 10, 'South Goa', 'दक्षिण गोवा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(175, 11, 'Ahmedabad', 'अहमदाबाद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(176, 11, 'Amreli', 'अमरेली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(177, 11, 'Anand', 'आनंद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(178, 11, 'Aravalli', 'अरावली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(179, 11, 'Banaskantha', 'बनासकांठा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(180, 11, 'Bharuch', 'भरूच', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(181, 11, 'Bhavnagar', 'भावनगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(182, 11, 'Botad', 'बोटाद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(183, 11, 'Chhota Udaipur', 'छोटा उदयपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(184, 11, 'Dahod', 'दाहोद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(185, 11, 'Dang', 'डांग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(186, 11, 'Devbhoomi Dwarka', 'देवभूमि द्वारका', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(187, 11, 'Gandhinagar', 'गांधीनगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(188, 11, 'Gir Somnath', 'गिर सोमनाथ', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(189, 11, 'Jamnagar', 'जामनगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(190, 11, 'Junagadh', 'जूनागढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(191, 11, 'Kheda', 'खेड़ा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(192, 11, 'Kutch', 'कच्छ', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(193, 11, 'Mahisagar', 'माहीसागर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(194, 11, 'Mehsana', 'मेहसाणा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(195, 11, 'Morbi', 'मोरबी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(196, 11, 'Narmada', 'नर्मदा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(197, 11, 'Navsari', 'नवसारी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(198, 11, 'Panchmahal', 'पंचमहाल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(199, 11, 'Patan', 'पाटण', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(200, 11, 'Porbandar', 'पोरबंदर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(201, 11, 'Rajkot', 'राजकोट', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(202, 11, 'Sabarkantha', 'साबरकांठा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(203, 11, 'Surat', 'सूरत', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(204, 11, 'Surendranagar', 'सुरेंद्रनगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(205, 11, 'Tapi', 'तापी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(206, 11, 'Vadodara', 'वडोदरा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(207, 11, 'Valsad', 'वलसाड', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(208, 12, 'Ambala', 'अंबाला', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(209, 12, 'Bhiwani', 'भिवानी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(210, 12, 'Charkhi Dadri', 'चरखी दादरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(211, 12, 'Faridabad', 'फरीदाबाद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(212, 12, 'Fatehabad', 'फतेहाबाद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(213, 12, 'Gurugram', 'गुरुग्राम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(214, 12, 'Hisar', 'हिसार', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(215, 12, 'Jhajjar', 'झज्जर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(216, 12, 'Jind', 'जींद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(217, 12, 'Kaithal', 'कैथल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(218, 12, 'Karnal', 'करनाल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(219, 12, 'Kurukshetra', 'कुरुक्षेत्र', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(220, 12, 'Mahendragarh', 'महेंद्रगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(221, 12, 'Nuh', 'नूंह', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(222, 12, 'Palwal', 'पलवल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(223, 12, 'Panchkula', 'पंचकूला', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(224, 12, 'Panipat', 'पानीपत', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(225, 12, 'Rewari', 'रेवाड़ी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(226, 12, 'Rohtak', 'रोहतक', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(227, 12, 'Sirsa', 'सिरसा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(228, 12, 'Sonipat', 'सोनीपत', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(229, 12, 'Yamunanagar', 'यमुनानगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(230, 13, 'Bilaspur', 'बिलासपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(231, 13, 'Chamba', 'चंबा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(232, 13, 'Hamirpur', 'हमीरपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(233, 13, 'Kangra', 'कांगड़ा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(234, 13, 'Kinnaur', 'किन्नौर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(235, 13, 'Kullu', 'कुल्लू', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(236, 13, 'Lahaul and Spiti', 'लाहौल और स्पीति', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(237, 13, 'Mandi', 'मंडी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(238, 13, 'Shimla', 'शिमला', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(239, 13, 'Sirmaur', 'सिरमौर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(240, 13, 'Solan', 'सोलन', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(241, 13, 'Una', 'ऊना', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(242, 14, 'Anantnag', 'अनंतनाग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(243, 14, 'Bandipora', 'बांदीपोरा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(244, 14, 'Baramulla', 'बारामूला', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(245, 14, 'Budgam', 'बडगाम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(246, 14, 'Doda', 'डोडा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(247, 14, 'Ganderbal', 'गांदरबल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(248, 14, 'Jammu', 'जम्मू', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(249, 14, 'Kathua', 'कठुआ', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(250, 14, 'Kishtwar', 'किश्तवाड़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(251, 14, 'Kulgam', 'कुलगाम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(252, 14, 'Kupwara', 'कुपवाड़ा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(253, 14, 'Poonch', 'पुंछ', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(254, 14, 'Pulwama', 'पुलवामा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(255, 14, 'Rajouri', 'राजौरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(256, 14, 'Ramban', 'रामबन', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(257, 14, 'Reasi', 'रियासी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(258, 14, 'Samba', 'सांबा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(259, 14, 'Shopian', 'शोपियां', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(260, 14, 'Srinagar', 'श्रीनगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(261, 14, 'Udhampur', 'उधमपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(262, 15, 'Bokaro', 'बोकारो', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(263, 15, 'Chatra', 'चतरा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(264, 15, 'Deoghar', 'देवघर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(265, 15, 'Dhanbad', 'धनबाद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(266, 15, 'Dumka', 'दुमका', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(267, 15, 'East Singhbhum', 'पूर्वी सिंहभूम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(268, 15, 'Garhwa', 'गढ़वा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(269, 15, 'Giridih', 'गिरिडीह', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(270, 15, 'Godda', 'गोड्डा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(271, 15, 'Gumla', 'गुमला', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(272, 15, 'Hazaribagh', 'हजारीबाग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(273, 15, 'Jamtara', 'जामताड़ा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(274, 15, 'Khunti', 'खूंटी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(275, 15, 'Koderma', 'कोडरमा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(276, 15, 'Latehar', 'लातेहार', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(277, 15, 'Lohardaga', 'लोहरदगा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(278, 15, 'Pakur', 'पाकुड़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(279, 15, 'Palamu', 'पलामू', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(280, 15, 'Ramgarh', 'रामगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(281, 15, 'Ranchi', 'रांची', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(282, 15, 'Sahibganj', 'साहिबगंज', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(283, 15, 'Seraikela Kharsawan', 'सरायकेला खरसावां', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(284, 15, 'Simdega', 'सिमडेगा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(285, 15, 'West Singhbhum', 'पश्चिमी सिंहभूम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(286, 16, 'Bagalkot', 'बागलकोट', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(287, 16, 'Ballari', 'बल्लारी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(288, 16, 'Belagavi', 'बेलगावी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(289, 16, 'Bengaluru Rural', 'बेंगलुरु ग्रामीण', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(290, 16, 'Bengaluru Urban', 'बेंगलुरु शहरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(291, 16, 'Bidar', 'बीदर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(292, 16, 'Chamarajanagar', 'चामराजनगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(293, 16, 'Chikkaballapur', 'चikkaballapur', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(294, 16, 'Chikkamagaluru', 'चिक्कमगलुरु', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(295, 16, 'Chitradurga', 'चित्रदुर्ग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(296, 16, 'Dakshina Kannada', 'दक्षिण कन्नड़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(297, 16, 'Davanagere', 'दावणगेरे', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(298, 16, 'Dharwad', 'धारवाड़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(299, 16, 'Gadag', 'गदग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(300, 16, 'Hassan', 'हासन', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(301, 16, 'Haveri', 'हावेरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(302, 16, 'Kalaburagi', 'कलबुरगी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(303, 16, 'Kodagu', 'कोडगु', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(304, 16, 'Kolar', 'कोलार', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(305, 16, 'Koppal', 'कोप्पल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(306, 16, 'Mandya', 'मंड्या', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(307, 16, 'Mysuru', 'मैसूरु', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(308, 16, 'Raichur', 'रायचूर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(309, 16, 'Ramanagara', 'रामनगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(310, 16, 'Shivamogga', 'शिवमोग्गा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(311, 16, 'Tumakuru', 'तुमकुरु', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(312, 16, 'Udupi', 'उडुपी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(313, 16, 'Uttara Kannada', 'उत्तर कन्नड़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(314, 16, 'Vijayanagara', 'विजयनगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(315, 16, 'Vijayapura', 'विजयपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(316, 16, 'Yadgir', 'यादगीर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(317, 17, 'Alappuzha', 'अलप्पुझा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(318, 17, 'Ernakulam', 'एर्नाकुलम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(319, 17, 'Idukki', 'इडुक्की', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(320, 17, 'Kannur', 'कन्नूर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(321, 17, 'Kasaragod', 'कासरगोड', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(322, 17, 'Kollam', 'कोल्लम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(323, 17, 'Kottayam', 'कोट्टायम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(324, 17, 'Kozhikode', 'कोझिकोड', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(325, 17, 'Malappuram', 'मलप्पुरम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(326, 17, 'Palakkad', 'पालक्काड़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(327, 17, 'Pathanamthitta', 'पठानमथिट्टा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(328, 17, 'Thiruvananthapuram', 'तिरुवनंतपुरम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(329, 17, 'Thrissur', 'त्रिशूर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(330, 17, 'Wayanad', 'वायनाड', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(331, 18, 'Kargil', 'कारगिल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(332, 18, 'Leh', 'लेह', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(333, 19, 'Lakshadweep', 'लक्षद्वीप', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(334, 20, 'Agar Malwa', 'अगर मालवा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(335, 20, 'Alirajpur', 'अलीराजपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(336, 20, 'Anuppur', 'अनूपपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(337, 20, 'Ashoknagar', 'अशोकनगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(338, 20, 'Balaghat', 'बालाघाट', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(339, 20, 'Barwani', 'बरवानी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(340, 20, 'Betul', 'बैतूल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(341, 20, 'Bhind', 'भिंड', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(342, 20, 'Bhopal', 'भोपाल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(343, 20, 'Burhanpur', 'बुरहानपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(344, 20, 'Chhatarpur', 'छतरपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(345, 20, 'Chhindwara', 'छिंदवाड़ा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(346, 20, 'Damoh', 'दमोह', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(347, 20, 'Datia', 'दतिया', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(348, 20, 'Dewas', 'देवास', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(349, 20, 'Dhar', 'धार', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(350, 20, 'Dindori', 'डिंडोरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(351, 20, 'Guna', 'गुना', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(352, 20, 'Gwalior', 'ग्वालियर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(353, 20, 'Harda', 'हारदा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(354, 20, 'Hoshangabad', 'होशंगाबाद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(355, 20, 'Indore', 'इंदौर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(356, 20, 'Jabalpur', 'जबलपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(357, 20, 'Jhabua', 'झाबुआ', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(358, 20, 'Katni', 'कटनी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(359, 20, 'Khandwa', 'खंडवा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(360, 20, 'Khargone', 'खरगोन', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(361, 20, 'Maihar', 'मैहर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(362, 20, 'Mandla', 'मंडला', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(363, 20, 'Mandsaur', 'मंदसौर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(364, 20, 'Morena', 'मुरैना', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(365, 20, 'Narmadapuram', 'नर्मदापुरम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(366, 20, 'Narsinghpur', 'नरसिंहपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(367, 20, 'Neemuch', 'नीमच', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(368, 20, 'Niwari', 'निवाड़ी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(369, 20, 'Panna', 'पन्ना', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(370, 20, 'Raisen', 'रायसेन', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(371, 20, 'Rajgarh', 'राजगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(372, 20, 'Ratlam', 'रतलाम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(373, 20, 'Rewa', 'रीवा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(374, 20, 'Sagar', 'सागर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(375, 20, 'Satna', 'सतना', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(376, 20, 'Sehore', 'सीहोर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(377, 20, 'Seoni', 'सिवनी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(378, 20, 'Shahdol', 'शहडोल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(379, 20, 'Shajapur', 'शाजापुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(380, 20, 'Sheopur', 'श्योपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(381, 20, 'Shivpuri', 'शिवपुरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(382, 20, 'Sidhi', 'सीधी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(383, 20, 'Singrauli', 'सिंगरौली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(384, 20, 'Tikamgarh', 'टीकमगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(385, 20, 'Ujjain', 'उज्जैन', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(386, 20, 'Umaria', 'उमरिया', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(387, 20, 'Vidisha', 'विदिशा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(388, 21, 'Ahmednagar', 'अहमदनगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(389, 21, 'Akola', 'अकोला', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(390, 21, 'Amravati', 'अमरावती', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(391, 21, 'Aurangabad', 'औरंगाबाद', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(392, 21, 'Beed', 'बीड़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(393, 21, 'Bhandara', 'भंडारा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(394, 21, 'Buldhana', 'बुलढाणा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(395, 21, 'Chandrapur', 'चंद्रपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(396, 21, 'Dhule', 'धुले', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(397, 21, 'Gadchiroli', 'गढ़चिरौली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(398, 21, 'Gondia', 'गोंदिया', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(399, 21, 'Hingoli', 'हिंगोली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(400, 21, 'Jalgaon', 'जलगांव', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(401, 21, 'Jalna', 'जालना', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(402, 21, 'Kolhapur', 'कोल्हापुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(403, 21, 'Latur', 'लातूर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(404, 21, 'Mumbai City', 'मुंबई शहर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(405, 21, 'Mumbai Suburban', 'मुंबई उपनगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(406, 21, 'Nagpur', 'नागपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(407, 21, 'Nanded', 'नांदेड़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(408, 21, 'Nandurbar', 'नंदुरबार', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(409, 21, 'Nashik', 'नासिक', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(410, 21, 'Osmanabad', ' Osmanabad', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(411, 21, 'Palghar', 'पालघर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(412, 21, 'Parbhani', 'परभणी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(413, 21, 'Pune', 'पुणे', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(414, 21, 'Raigad', 'रायगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(415, 21, 'Ratnagiri', 'रत्नागिरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(416, 21, 'Sangli', 'सांगली', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(417, 21, 'Satara', 'सातारा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(418, 21, 'Sindhudurg', 'सिंधुदुर्ग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(419, 21, 'Solapur', 'सोलापुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(420, 21, 'Thane', 'ठाणे', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(421, 21, 'Wardha', 'वर्धा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(422, 21, 'Washim', 'वाशिम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(423, 21, 'Yavatmal', 'यवतमाल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(424, 22, 'Bishnupur', 'बिश्नुपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(425, 22, 'Chandel', 'चंदेल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(426, 22, 'Churachandpur', 'चुराचांदपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(427, 22, 'Imphal East', 'इंफाल पूर्वी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(428, 22, 'Imphal West', 'इंफाल पश्चिमी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(429, 22, 'Jiribam', 'जिरिबाम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(430, 22, 'Kakching', 'काकचिंग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(431, 22, 'Kamjong', 'कामजोंग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(432, 22, 'Kangpokpi', 'कांगपोकपी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(433, 22, 'Noney', 'नोनी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(434, 22, 'Pherzawl', 'फेरजावल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(435, 22, 'Senapati', 'सेनापति', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(436, 22, 'Tamenglong', 'तामेंगलॉन्ग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(437, 22, 'Tengnoupal', 'तेंगनौपाल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(438, 22, 'Thoubal', 'थौबाल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(439, 22, 'Ukhrul', 'उखरुल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(440, 23, 'East Garo Hills', 'पूर्वी गारो हिल्स', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(441, 23, 'East Jaintia Hills', 'पूर्वी जैंतिया हिल्स', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(442, 23, 'East Khasi Hills', 'पूर्वी खासी हिल्स', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(443, 23, 'Mawkyrwat', 'मावकिरवट', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(444, 23, 'North Garo Hills', 'उत्तरी गारो हिल्स', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(445, 23, 'Ri Bhoi', 'री भोई', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(446, 23, 'South Garo Hills', 'दक्षिण गारो हिल्स', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(447, 23, 'South West Garo Hills', 'दक्षिण पश्चिम गारो हिल्स', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(448, 23, 'South West Khasi Hills', 'दक्षिण पश्चिम खासी हिल्स', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(449, 23, 'West Garo Hills', 'पश्चिम गारो हिल्स', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(450, 23, 'West Jaintia Hills', 'पश्चिम जैंतिया हिल्स', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(451, 23, 'West Khasi Hills', 'पश्चिम खासी हिल्स', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(452, 24, 'Aizawl', 'आइजोल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(453, 24, 'Champhai', 'चम्फाई', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(454, 24, 'Hnahthial', 'ह्नाथ्थियाल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(455, 24, 'Khawzawl', 'खावजॉल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(456, 24, 'Kolasib', 'कोलासिब', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(457, 24, 'Lawngtlai', 'लॉन्गतलाई', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(458, 24, 'Lunglei', 'लुंगलेई', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(459, 24, 'Mamit', 'मामित', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(460, 24, 'Saiha', 'सैहा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(461, 24, 'Saitual', 'सैतुअल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(462, 24, 'Serchhip', 'सेरछिप', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(463, 25, 'Chumukedima', 'चुमुकेदिमा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(464, 25, 'Dimapur', 'दीमापुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(465, 25, 'Kiphire', 'किफिरे', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(466, 25, 'Kohima', 'कोहिमा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(467, 25, 'Longleng', 'लॉन्गलेंग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(468, 25, 'Mokokchung', 'मोकोकचुंग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(469, 25, 'Mon', 'मोन', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(470, 25, 'Niuland', 'न्यूलैंड', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(471, 25, 'Noklak', 'नोकलाक', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(472, 25, 'Peren', 'पेरेन', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(473, 25, 'Phek', 'फेक', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(474, 25, 'Shamator', 'शामاتور', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(475, 25, 'Tseminyu', 'त्सेमिन्यू', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(476, 25, 'Tuensang', 'तुएंसांग', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(477, 25, 'Wokha', 'वोखा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(478, 25, 'Zunheboto', 'जुन्हेबोटो', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(479, 26, 'Angul', 'अंगुल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(480, 26, 'Balangir', 'बलांगिर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(481, 26, 'Balasore', 'बालासोर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(482, 26, 'Bargarh', 'बरगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(483, 26, 'Bhadrak', 'भद्रक', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(484, 26, 'Boudh', 'बौध', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(485, 26, 'Cuttack', 'कटक', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(486, 26, 'Deogarh', 'देवगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(487, 26, 'Dhenkanal', 'धेंकनाल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(488, 26, 'Gajapati', 'गजपति', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(489, 26, 'Ganjam', 'गंजाम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(490, 26, 'Jagatsinghpur', 'जगतसिंहपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(491, 26, 'Jajpur', 'जाजपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(492, 26, 'Jharsuguda', 'झारसुगुड़ा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(493, 26, 'Kalahandi', 'कलाहांडी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(494, 26, 'Kandhamal', 'कंधमाल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(495, 26, 'Kendrapara', 'केन्द्रपाड़ा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(496, 26, 'Kendujhar', 'केन्द्रझर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(497, 26, 'Khordha', 'खोरधा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(498, 26, 'Koraput', 'कोरापुट', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(499, 26, 'Malkangiri', 'मलकांगिरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(500, 26, 'Mayurbhanj', 'मयूरभंज', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(501, 26, 'Nabarangpur', 'नबरंगपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(502, 26, 'Nayagarh', 'नयागढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(503, 26, 'Nuapada', 'नुआपड़ा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(504, 26, 'Puri', 'पुरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(505, 26, 'Rayagada', 'रायगड़ा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(506, 26, 'Sambalpur', 'संभलपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(507, 26, 'Subarnapur', 'सुबर्णपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(508, 26, 'Sundargarh', 'सुंदरगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(509, 27, 'Karaikal', 'कराईकल', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(510, 27, 'Mahe', 'माहे', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(511, 27, 'Puducherry', 'पुडुचेरी', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(512, 27, 'Yanam', 'यानम', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(513, 28, 'Amritsar', 'अमृतसर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(514, 28, 'Barnala', 'बरनाला', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(515, 28, 'Bathinda', 'बठिंडा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(516, 28, 'Faridkot', 'फरीदकोट', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(517, 28, 'Fatehgarh Sahib', 'फतेहगढ़ साहिब', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(518, 28, 'Fazilka', 'फजिल्का', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(519, 28, 'Ferozepur', 'फिरोजपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(520, 28, 'Gurdaspur', 'गुरदासपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(521, 28, 'Hoshiarpur', 'होशियारपुर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(522, 28, 'Jalandhar', 'जालंधर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(523, 28, 'Kapurthala', 'कपूरथला', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(524, 28, 'Ludhiana', 'लुधियाना', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(525, 28, 'Malerkotla', 'मलेरकोटला', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(526, 28, 'Mansa', 'मानसा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(527, 28, 'Moga', 'मोगा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(528, 28, 'Pathankot', 'पठानकोट', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(529, 28, 'Patiala', 'पटियाला', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(530, 28, 'Rupnagar', 'रूपनगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(531, 28, 'Sangrur', 'संगरूर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(532, 28, 'SAS Nagar', 'एस.ए.एस. नगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(533, 28, 'SBS Nagar', 'एस.बी.एस. नगर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(534, 28, 'Sri Muktsar Sahib', 'श्री मुक्तसर साहिब', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(535, 28, 'Tarn Taran', 'तरन तारन', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(536, 29, 'Ajmer', 'अजमेर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(537, 29, 'Alwar', 'अलवर', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(538, 29, 'Anupgarh', 'अनूपगढ़', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(539, 29, 'Balotra', 'बालोतरा', '2026-02-28 11:03:30', '2026-02-28 11:03:30'),
(540, 29, 'Banswara', 'बांसवाड़ा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(541, 29, 'Baran', 'बारां', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(542, 29, 'Barmer', 'बाड़मेर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(543, 29, 'Beawar', 'ब्यावर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(544, 29, 'Bharatpur', 'भरतपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(545, 29, 'Bhilwara', 'भीलवाड़ा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(546, 29, 'Bikaner', 'बीकानेर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(547, 29, 'Bundi', 'बूंदी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(548, 29, 'Chittorgarh', 'चित्तौड़गढ़', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(549, 29, 'Churu', 'चूरू', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(550, 29, 'Dausa', 'दौसा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(551, 29, 'Deeg', 'डीग', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(552, 29, 'Dholpur', 'धौलपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(553, 29, 'Didwana-Kuchaman', 'दिदवाना-कुचामन', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(554, 29, 'Dudu', 'दूदू', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(555, 29, 'Dungarpur', 'डूंगरपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(556, 29, 'Ganganagar', 'गंगानगर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(557, 29, 'Gangapur City', 'गंगापुर सिटी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(558, 29, 'Hanumangarh', 'हनुमानगढ़', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(559, 29, 'Jaipur', 'जयपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(560, 29, 'Jaipur Rural', 'जयपुर ग्रामीण', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(561, 29, 'Jaisalmer', 'जैसलमेर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(562, 29, 'Jalore', 'जालोर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(563, 29, 'Jhalawar', 'झालावाड़', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(564, 29, 'Jhunjhunu', 'झुंझुनू', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(565, 29, 'Jodhpur', 'जोधपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(566, 29, 'Jodhpur Rural', 'जोधपुर ग्रामीण', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(567, 29, 'Karauli', 'करौली', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(568, 29, 'Kekri', 'केकड़ी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(569, 29, 'Khairthal-Tijara', 'खैरथल-तिजारा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(570, 29, 'Kotputli-Behror', 'कोटपूतली-बहरोड़', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(571, 29, 'Nagaur', 'नागौर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(572, 29, 'Neem ka Thana', 'नीम का थाना', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(573, 29, 'Pali', 'पाली', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(574, 29, 'Phalodi', 'फलोदी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(575, 29, 'Pratapgarh', 'प्रतापगढ़', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(576, 29, 'Rajsamand', 'राजसमंद', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(577, 29, 'Salumbar', 'सलुम्बर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(578, 29, 'Sanchore', 'सांचोर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(579, 29, 'Sawai Madhopur', 'सवाई माधोपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(580, 29, 'Shahpura', 'शाहपुरा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(581, 29, 'Sikar', 'सीकर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(582, 29, 'Sirohi', 'सिरोही', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(583, 29, 'Tonk', 'टोंक', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(584, 29, 'Udaipur', 'उदयपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(585, 30, 'Gangtok', 'गंगटोक', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(586, 30, 'Gyalshing', 'ग्यालशिंग', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(587, 30, 'Mangan', 'मंगन', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(588, 30, 'Namchi', 'नामची', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(589, 30, 'Pakyong', 'पाकयोंग', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(590, 30, 'Soreng', 'सोरेंग', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(591, 31, 'Ariyalur', 'अरियालुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(592, 31, 'Chengalpattu', 'चेंगलपट्टू', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(593, 31, 'Chennai', 'चेन्नई', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(594, 31, 'Coimbatore', 'कोयंबटूर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(595, 31, 'Cuddalore', 'कडलूर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(596, 31, 'Dharmapuri', 'धर्मपुरी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(597, 31, 'Dindigul', 'दिंडिगुल', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(598, 31, 'Erode', 'ईरोड', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(599, 31, 'Kallakurichi', 'कल्लाकुरिची', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(600, 31, 'Kancheepuram', 'कांचीपुरम', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(601, 31, 'Kanniyakumari', 'कन्याकुमारी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(602, 31, 'Karur', 'करूर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(603, 31, 'Krishnagiri', 'कृष्णगिरि', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(604, 31, 'Madurai', 'मदुरै', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(605, 31, 'Mayiladuthurai', 'मयिलादुतुरै', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(606, 31, 'Nagapattinam', 'नागपट्टिनम', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(607, 31, 'Namakkal', 'नामक्कल', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(608, 31, 'Nilgiris', 'नीलगिरि', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(609, 31, 'Perambalur', 'पेरंबलूर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(610, 31, 'Pudukkottai', 'पुदुक्कोट्टै', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(611, 31, 'Ramanathapuram', 'रामनाथपुरम', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(612, 31, 'Ranipet', 'रणिपेट', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(613, 31, 'Salem', 'सेलम', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(614, 31, 'Sivaganga', 'शिवगंगा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(615, 31, 'Tenkasi', 'तेनकासी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(616, 31, 'Thanjavur', 'तंजावुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(617, 31, 'Theni', 'थेनी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(618, 31, 'Thoothukudi', 'तूथुकुडी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(619, 31, 'Tiruchirappalli', 'तिरुचिरापल्ली', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(620, 31, 'Tirunelveli', 'तिरुनेलवेली', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(621, 31, 'Tirupathur', 'तिरुपत्तूर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(622, 31, 'Tiruppur', 'तिरुप्पूर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(623, 31, 'Tiruvallur', 'तिरुवल्लूर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(624, 31, 'Tiruvannamalai', 'तिरुवन्नामलै', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(625, 31, 'Tiruvarur', 'तिरुवारूर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(626, 31, 'Vellore', 'वेल्लोर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(627, 31, 'Viluppuram', 'विलुप्पुरम', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(628, 31, 'Virudhunagar', 'विरुद्धुनगर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(629, 32, 'Adilabad', 'आदिलाबाद', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(630, 32, 'Bhadradri Kothagudem', 'भद्राद्री कोठागुडेम', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(631, 32, 'Hyderabad', 'हैदराबाद', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(632, 32, 'Jagtial', 'जगतियाल', '2026-02-28 11:03:31', '2026-02-28 11:03:31');
INSERT INTO `districts` (`id`, `state_id`, `name_en`, `name_hi`, `created_at`, `updated_at`) VALUES
(633, 32, 'Jangaon', 'जंगांव', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(634, 32, 'Jayashankar Bhupalpally', 'जयशंकर भूपालपल्ली', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(635, 32, 'Jogulamba Gadwal', 'जोगुलंबा गडवाल', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(636, 32, 'Kamareddy', 'कामारेड्डी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(637, 32, 'Karimnagar', 'करीमनगर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(638, 32, 'Khammam', 'खम्मम', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(639, 32, 'Komaram Bheem Asifabad', 'कोमारम भीम असिफाबाद', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(640, 32, 'Mahabubabad', 'महबूबाबाद', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(641, 32, 'Mahbubnagar', 'महबूबनगर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(642, 32, 'Mancherial', 'मंचेरियाल', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(643, 32, 'Medak', 'मेदक', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(644, 32, 'Medchal Malkajgiri', 'मेडचल मलकजगिरी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(645, 32, 'Mulugu', 'मुलुगु', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(646, 32, 'Nagarkurnool', 'नागरकुरनूल', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(647, 32, 'Nalgonda', 'नलगोंडा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(648, 32, 'Narayanpet', 'नारायणपेट', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(649, 32, 'Nirmal', 'निर्मल', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(650, 32, 'Nizamabad', 'निजामाबाद', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(651, 32, 'Peddapalli', 'पेद्दापल्ली', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(652, 32, 'Rajanna Sircilla', 'राजन्ना सिरसिल्ला', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(653, 32, 'Ranga Reddy', 'रंगा रेड्डी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(654, 32, 'Sangareddy', 'संगारेड्डी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(655, 32, 'Siddipet', 'सिद्दीपेट', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(656, 32, 'Suryapet', 'सूर्यापेट', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(657, 32, 'Vikarabad', 'विकाराबाद', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(658, 32, 'Wanaparthy', 'वानापर्ती', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(659, 32, 'Warangal', 'वारंगल', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(660, 32, 'Warangal Rural', 'वारंगल ग्रामीण', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(661, 32, 'Yadadri Bhuvanagiri', 'यादाद्री भुवनगिरि', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(662, 33, 'Dhalai', 'धलाई', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(663, 33, 'Gomati', 'गोमती', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(664, 33, 'Khowai', 'खोवाई', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(665, 33, 'North Tripura', 'उत्तर त्रिपुरा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(666, 33, 'Sepahijala', 'सेपाहिजला', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(667, 33, 'South Tripura', 'दक्षिण त्रिपुरा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(668, 33, 'Unakoti', 'उनाकोटी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(669, 33, 'West Tripura', 'पश्चिम त्रिपुरा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(670, 34, 'Agra', 'आगरा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(671, 34, 'Aligarh', 'अलीगढ़', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(672, 34, 'Ambedkar Nagar', 'अंबेडकर नगर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(673, 34, 'Amethi', 'अमेठी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(674, 34, 'Amroha', 'अमरोहा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(675, 34, 'Auraiya', 'औरैया', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(676, 34, 'Ayodhya', 'अयोध्या', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(677, 34, 'Azamgarh', 'आजमगढ़', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(678, 34, 'Baghpat', 'बागपत', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(679, 34, 'Bahraich', 'बहराइच', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(680, 34, 'Ballia', 'बलिया', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(681, 34, 'Balrampur', 'बलरामपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(682, 34, 'Banda', 'बांदा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(683, 34, 'Barabanki', 'बाराबंकी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(684, 34, 'Bareilly', 'बरेली', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(685, 34, 'Basti', 'बस्ती', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(686, 34, 'Bhadohi', 'भदोही', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(687, 34, 'Bijnor', 'बिजनौर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(688, 34, 'Budaun', 'बदायूं', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(689, 34, 'Bulandshahr', 'बुलंदशहर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(690, 34, 'Chandauli', 'चंदौली', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(691, 34, 'Chitrakoot', 'चित्रकूट', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(692, 34, 'Deoria', 'देवरिया', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(693, 34, 'Etah', 'एटा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(694, 34, 'Etawah', 'इटावा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(695, 34, 'Farrukhabad', 'फर्रुखाबाद', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(696, 34, 'Fatehpur', 'फतेहपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(697, 34, 'Firozabad', 'फिरोजाबाद', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(698, 34, 'Gautam Buddha Nagar', 'गौतम बुद्ध नगर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(699, 34, 'Ghaziabad', 'गाजियाबाद', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(700, 34, 'Ghazipur', 'गाजीपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(701, 34, 'Gonda', 'गोंडा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(702, 34, 'Gorakhpur', 'गोरखपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(703, 34, 'Hamirpur', 'हमीरपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(704, 34, 'Hapur', 'हापुड़', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(705, 34, 'Hardoi', 'हरदोई', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(706, 34, 'Hathras', 'हाथरस', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(707, 34, 'Jalaun', 'जालौन', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(708, 34, 'Jaunpur', 'जौनपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(709, 34, 'Jhansi', 'झांसी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(710, 34, 'Kannauj', 'कन्नौज', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(711, 34, 'Kanpur Dehat', 'कानपुर देहात', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(712, 34, 'Kanpur Nagar', 'कानपुर नगर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(713, 34, 'Kasganj', 'कासगंज', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(714, 34, 'Kaushambi', 'कौशांबी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(715, 34, 'Kheri', 'खीरी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(716, 34, 'Kushinagar', 'कुशीनगर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(717, 34, 'Lalitpur', 'ललितपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(718, 34, 'Lucknow', 'लखनऊ', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(719, 34, 'Maharajganj', 'महाराजगंज', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(720, 34, 'Mahoba', 'महोबा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(721, 34, 'Mainpuri', 'मैनपुरी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(722, 34, 'Mathura', 'मथुरा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(723, 34, 'Mau', 'मऊ', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(724, 34, 'Meerut', 'मेरठ', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(725, 34, 'Mirzapur', 'मिर्ज़ापुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(726, 34, 'Moradabad', 'मुरादाबाद', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(727, 34, 'Muzaffarnagar', 'मुजफ्फरनगर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(728, 34, 'Pilibhit', 'पीलीभीत', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(729, 34, 'Pratapgarh', 'प्रतापगढ़', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(730, 34, 'Prayagraj', 'प्रयागराज', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(731, 34, 'Raebareli', 'रायबरेली', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(732, 34, 'Rampur', 'रामपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(733, 34, 'Saharanpur', 'सहारनपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(734, 34, 'Sambhal', 'संभल', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(735, 34, 'Sant Kabir Nagar', 'संत कबीर नगर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(736, 34, 'Shahjahanpur', 'शाहजहांपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(737, 34, 'Shamli', 'शामली', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(738, 34, 'Shravasti', 'श्रावस्ती', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(739, 34, 'Siddharthnagar', 'सिद्धार्थनगर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(740, 34, 'Sitapur', 'सीतापुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(741, 34, 'Sonbhadra', 'सोनभद्र', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(742, 34, 'Sultanpur', 'सुल्तानपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(743, 34, 'Unnao', 'उन्नाव', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(744, 34, 'Varanasi', 'वाराणसी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(745, 35, 'Almora', 'अल्मोड़ा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(746, 35, 'Bageshwar', 'बागेश्वर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(747, 35, 'Chamoli', 'चमोली', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(748, 35, 'Champawat', 'चंपावत', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(749, 35, 'Dehradun', 'देहरादून', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(750, 35, 'Haridwar', 'हरिद्वार', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(751, 35, 'Nainital', 'नैनीताल', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(752, 35, 'Pauri Garhwal', 'पौड़ी गढ़वाल', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(753, 35, 'Pithoragarh', 'पिथौरागढ़', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(754, 35, 'Rudraprayag', 'रुद्रप्रयाग', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(755, 35, 'Tehri Garhwal', 'टिहरी गढ़वाल', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(756, 35, 'Udham Singh Nagar', 'उधम सिंह नगर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(757, 35, 'Uttarkashi', 'उत्तरकाशी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(758, 36, 'Alipurduar', 'अलीपुरद्वार', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(759, 36, 'Bankura', 'बांकुड़ा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(760, 36, 'Birbhum', 'बी रभूम', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(761, 36, 'Cooch Behar', 'कूच बिहार', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(762, 36, 'Dakshin Dinajpur', 'दक्षिण दिनाजपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(763, 36, 'Darjeeling', 'दार्जिलिंग', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(764, 36, 'Hooghly', 'हुगली', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(765, 36, 'Howrah', 'हावड़ा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(766, 36, 'Jalpaiguri', 'जलपाईगुड़ी', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(767, 36, 'Jhargram', 'झाड़ग्राम', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(768, 36, 'Kalimpong', 'कालिम्पोंग', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(769, 36, 'Kolkata', 'कोलकाता', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(770, 36, 'Malda', 'मालदा', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(771, 36, 'Murshidabad', 'मुर्शिदाबाद', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(772, 36, 'Nadia', 'नादिया', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(773, 36, 'North 24 Parganas', 'उत्तर 24 परगना', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(774, 36, 'Paschim Bardhaman', 'पश्चिम बर्धमान', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(775, 36, 'Paschim Medinipur', 'पश्चिम मेदिनीपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(776, 36, 'Purba Bardhaman', 'पूर्व बर्धमान', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(777, 36, 'Purba Medinipur', 'पूर्व मेदिनीपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(778, 36, 'Purulia', 'पुरुलिया', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(779, 36, 'South 24 Parganas', 'दक्षिण 24 परगना', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(780, 36, 'Uttar Dinajpur', 'उत्तर दिनाजपुर', '2026-02-28 11:03:31', '2026-02-28 11:03:31'),
(781, 37, 'Bokaro', 'बोकारो', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(782, 37, 'Chatra', 'चतरा', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(783, 37, 'Deoghar', 'देवघर', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(784, 37, 'Dhanbad', 'धनबाद', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(785, 37, 'Dumka', 'दुमका', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(786, 37, 'East Singhbhum', 'पूर्वी सिंहभूम', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(787, 37, 'Garhwa', 'गढ़वा', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(788, 37, 'Giridih', 'गिरिडीह', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(789, 37, 'Godda', 'गोड्डा', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(790, 37, 'Gumla', 'गुमला', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(791, 37, 'Hazaribagh', 'हजारीबाग', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(792, 37, 'Jamtara', 'जामताड़ा', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(793, 37, 'Khunti', 'खूंटी', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(794, 37, 'Koderma', 'कोडरमा', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(795, 37, 'Latehar', 'लातेहार', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(796, 37, 'Lohardaga', 'लोहरदगा', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(797, 37, 'Pakur', 'पाकुड़', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(798, 37, 'Palamu', 'पलामू', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(799, 37, 'Ramgarh', 'रामगढ़', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(800, 37, 'Ranchi', 'रांची', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(801, 37, 'Sahibganj', 'साहिबगंज', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(802, 37, 'Seraikela Kharsawan', 'सरायकेला खरसावां', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(803, 37, 'Simdega', 'सिमडेगा', '2026-02-28 05:33:30', '2026-02-28 05:33:30'),
(804, 37, 'West Singhbhum', 'पश्चिमी सिंहभूम', '2026-02-28 05:33:30', '2026-02-28 05:33:30');

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `division_code` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `name`, `division_code`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Ranchi Division', 'RNC', 1, '2026-02-05 17:09:52', '2026-05-02 11:55:10', NULL),
(2, 'Jamshedpur Division', 'JSR', 1, '2026-02-05 17:14:35', '2026-02-24 13:12:47', NULL),
(3, 'Dumka Division', 'DUM', 1, '2026-02-05 17:15:43', '2026-06-15 12:51:29', NULL),
(4, 'Dhanbad Division', 'DHN', 1, '2026-02-05 17:15:43', '2026-02-24 13:13:03', NULL),
(5, 'Hazaribag Division', 'HZB', 1, '2026-02-05 17:15:43', '2026-02-24 13:13:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `document_master`
--

CREATE TABLE `document_master` (
  `id` int(11) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_key` varchar(100) NOT NULL,
  `document_category` varchar(50) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, NULL, 'pchamplin@example.org', '225.69.12.17', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/537.1 (KHTML, like Gecko) Version/15.0 EdgiOS/94.01016.21 Mobile/15E148 Safari/537.1', 'failed', 'login_attempt', '2026-04-16 04:58:30'),
(2, NULL, 'kaylah.barton@example.org', '26.42.102.58', 'Opera/8.95 (X11; Linux x86_64; en-US) Presto/2.8.293 Version/11.00', 'success', 'login_attempt', '2026-04-16 04:58:30'),
(3, NULL, 'wlowe@example.com', '148.59.7.209', 'Opera/8.68 (X11; Linux x86_64; nl-NL) Presto/2.10.254 Version/11.00', 'success', 'login_attempt', '2026-04-16 04:58:31'),
(4, NULL, 'aufderhar.godfrey@example.org', '50.146.30.106', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 6.0; Trident/4.1)', 'failed', 'login_attempt', '2026-04-16 04:58:31'),
(5, NULL, 'leora11@example.com', '23.22.81.153', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5320 (KHTML, like Gecko) Chrome/36.0.881.0 Mobile Safari/5320', 'success', 'login_attempt', '2026-04-16 04:58:31'),
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
(37, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-17 12:51:34'),
(38, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 04:47:57'),
(39, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-18 05:07:03'),
(40, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 05:07:32'),
(41, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-18 05:47:55'),
(42, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 05:48:08'),
(43, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-18 06:18:20'),
(44, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 06:23:56'),
(45, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-18 06:25:24'),
(46, NULL, 'leora11@example.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 06:25:39'),
(47, NULL, 'ritikkumar@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-18 06:28:12'),
(48, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 06:28:26'),
(49, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-18 06:30:37'),
(50, NULL, 'ritikkumar@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 06:30:59'),
(51, NULL, 'ritikkumar@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-18 06:35:49'),
(52, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 06:36:08'),
(53, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-18 06:40:17'),
(54, NULL, 'ritikkumar@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 06:40:38'),
(55, NULL, 'ritikkumar@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-18 06:50:53'),
(56, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 06:51:09'),
(57, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-18 07:20:55'),
(58, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 07:21:18'),
(59, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-18 07:51:18'),
(60, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 07:54:36'),
(61, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-18 08:25:15'),
(62, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 09:08:03'),
(63, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 09:54:57'),
(64, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-18 10:14:24'),
(65, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 10:14:36'),
(66, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-18 11:03:52'),
(67, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 11:04:07'),
(68, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-18 11:04:47'),
(69, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-18 11:05:03'),
(70, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 04:54:27'),
(71, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-20 05:11:38'),
(72, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 05:11:54'),
(73, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-20 05:17:12'),
(74, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 05:18:25'),
(75, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-20 05:27:10'),
(76, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 05:27:24'),
(77, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-20 05:28:05'),
(78, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 05:28:19'),
(79, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-20 05:28:31'),
(80, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 05:28:54'),
(81, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-20 05:30:57'),
(82, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 05:35:10'),
(83, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-20 05:35:50'),
(84, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 05:39:02'),
(85, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 05:49:30'),
(86, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-20 05:54:28'),
(87, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 05:54:46'),
(88, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-20 06:16:02'),
(89, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-20 06:16:11'),
(90, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 06:16:47'),
(91, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-20 06:31:48'),
(92, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 06:32:13'),
(93, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-20 06:49:13'),
(94, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 07:01:48'),
(95, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-20 07:33:39'),
(96, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 07:39:27'),
(97, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 10:56:58'),
(98, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 11:36:19'),
(99, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-20 12:36:19'),
(100, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-20 12:36:33'),
(101, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-21 04:36:04'),
(102, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-21 05:36:28'),
(103, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-21 05:38:08'),
(104, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-21 06:38:11'),
(105, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-21 06:39:39'),
(106, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-21 07:40:28'),
(107, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-21 07:41:44'),
(108, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-21 07:41:56'),
(109, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-21 07:48:41'),
(110, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-21 09:38:35'),
(111, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-21 10:45:46'),
(112, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-21 10:46:00'),
(113, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-21 11:46:28'),
(114, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-21 11:48:04'),
(115, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-22 05:38:14'),
(116, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-22 05:41:42'),
(117, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-29 09:34:49'),
(118, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-29 10:35:45'),
(119, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-29 10:36:35'),
(120, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-04-29 11:36:35'),
(121, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-29 11:36:52'),
(122, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-29 11:40:45'),
(123, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-29 12:05:34'),
(124, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-29 12:06:00'),
(125, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'success', 'password_login', '2026-04-29 12:06:47'),
(126, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-29 12:40:17'),
(127, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-29 12:56:30'),
(128, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-29 12:57:38'),
(129, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'otp_login', '2026-04-30 04:49:02'),
(130, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-30 04:52:33'),
(131, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'otp_login', '2026-04-30 05:36:47'),
(132, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-30 05:42:22'),
(133, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'otp_login', '2026-04-30 05:42:56'),
(134, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'otp_login', '2026-04-30 09:37:16'),
(135, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-30 09:37:34'),
(136, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-30 09:45:11'),
(137, 6, 'admin@jesa.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-30 09:53:13'),
(138, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-30 09:58:34'),
(139, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-04-30 10:19:19'),
(140, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-04-30 11:02:00'),
(141, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-01 04:21:01'),
(142, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-01 04:51:52'),
(143, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-01 04:54:56'),
(144, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-01 04:55:05'),
(145, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-01 05:01:13'),
(146, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-01 05:32:52'),
(147, NULL, 'staff@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-01 05:37:02'),
(148, NULL, 'staff@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-01 05:41:09'),
(149, NULL, 'staff@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-01 05:41:40'),
(150, NULL, 'staff@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-01 05:57:26'),
(151, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-01 05:58:08'),
(152, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-01 06:01:54'),
(153, NULL, 'staff@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-01 06:02:35'),
(154, NULL, 'staff@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-01 06:22:33'),
(155, NULL, 'divisionstaff@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-01 06:24:23'),
(156, NULL, 'divisionstaff@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-01 06:25:33'),
(157, NULL, 'subdivisionstaff@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-01 06:25:48'),
(158, NULL, 'subdivisionstaff@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-01 07:02:56'),
(159, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-01 07:03:19'),
(160, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-01 11:25:45'),
(161, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-01 12:25:50'),
(162, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-02 04:27:18'),
(163, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-02 05:37:24'),
(164, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-02 05:37:39'),
(165, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-02 06:17:19'),
(166, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-02 07:17:20'),
(167, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-02 07:20:23'),
(168, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-02 08:20:33'),
(169, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-04 04:37:06'),
(170, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-04 05:24:30'),
(171, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-04 06:24:30'),
(172, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-04 06:44:23'),
(173, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-04 07:44:36'),
(174, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-04 07:45:55'),
(175, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-04 09:20:45'),
(176, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-04 10:20:45'),
(177, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-04 10:22:10'),
(178, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-04 11:24:23'),
(179, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-04 11:24:38'),
(180, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-04 13:00:28'),
(181, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-05 04:22:40'),
(182, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-05 05:54:28'),
(183, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-05 06:54:28'),
(184, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-05 07:04:07'),
(185, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-05 08:04:53'),
(186, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-05 09:01:15'),
(187, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-05-05 09:45:30'),
(188, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-05 09:45:58'),
(189, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-05 10:45:58'),
(190, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-05 10:47:35'),
(191, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-05 12:08:48'),
(192, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-05 13:01:31'),
(193, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-05 13:01:44'),
(194, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-06 04:14:31'),
(195, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-06 05:09:44'),
(196, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-06 06:22:39'),
(197, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-06 07:22:39'),
(198, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-06 07:23:13'),
(199, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-06 08:23:57'),
(200, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-06 09:00:49'),
(201, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-06 10:00:49'),
(202, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-06 10:01:46'),
(203, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-06 10:24:09'),
(204, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-05-06 10:24:25'),
(205, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-06 10:24:49'),
(206, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-06 11:24:49'),
(207, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-06 11:38:18'),
(208, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-06 12:38:59'),
(209, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-06 12:40:32'),
(210, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-07 04:23:54'),
(211, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-07 05:24:47'),
(212, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-07 05:31:28'),
(213, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-07 06:31:28'),
(214, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-07 06:31:50'),
(215, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-07 07:34:59'),
(216, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-07 07:35:19'),
(217, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-07 09:00:43'),
(218, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-07 09:03:29'),
(219, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-07 09:04:04'),
(220, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-07 10:04:04'),
(221, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-07 10:04:27'),
(222, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-07 10:36:11'),
(223, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-07 10:36:33'),
(224, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-07 11:36:33'),
(225, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-08 04:48:32'),
(226, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-08 05:48:57'),
(227, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-08 05:56:05'),
(228, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-08 06:56:30'),
(229, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-08 06:57:08'),
(230, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-08 07:57:08'),
(231, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-08 07:58:20'),
(232, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-08 08:55:26'),
(233, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-08 10:34:39'),
(234, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-08 11:34:43'),
(235, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-08 11:38:08'),
(236, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-08 12:38:08'),
(237, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-08 12:42:36'),
(238, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-05-09 09:25:20'),
(239, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-09 09:25:41'),
(240, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-09 10:49:27'),
(241, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-11 04:51:24');
INSERT INTO `login_logs` (`id`, `user_id`, `email`, `ip_address`, `user_agent`, `status`, `action`, `created_at`) VALUES
(242, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-11 05:27:19'),
(243, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-11 07:25:08'),
(244, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-11 07:38:22'),
(245, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-11 09:15:11'),
(246, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-11 10:15:46'),
(247, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-11 10:27:26'),
(248, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-11 11:27:28'),
(249, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-11 11:34:11'),
(250, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-11 12:34:43'),
(251, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-12 04:18:28'),
(252, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-12 05:18:30'),
(253, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-12 05:56:30'),
(254, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-12 06:58:32'),
(255, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-12 07:58:32'),
(256, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-12 09:00:54'),
(257, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-12 09:11:49'),
(258, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-12 09:12:31'),
(259, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-12 09:13:59'),
(260, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-12 09:14:27'),
(261, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-12 10:50:04'),
(262, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-13 10:16:06'),
(263, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-13 12:04:32'),
(264, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-13 12:53:05'),
(265, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-14 04:11:00'),
(266, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-14 04:25:37'),
(267, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-14 07:26:11'),
(268, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-14 07:26:44'),
(269, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-14 08:55:59'),
(270, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-14 09:36:07'),
(271, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-15 04:18:03'),
(272, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-15 05:53:25'),
(273, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-15 07:14:17'),
(274, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-15 09:09:34'),
(275, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-15 11:19:23'),
(276, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-15 12:13:56'),
(277, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-16 04:21:44'),
(278, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-16 05:13:51'),
(279, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-18 06:07:29'),
(280, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-18 06:48:45'),
(281, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-18 06:49:18'),
(282, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-18 06:49:28'),
(283, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-18 07:50:28'),
(284, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-18 09:24:13'),
(285, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-18 10:24:22'),
(286, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-18 10:30:16'),
(287, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-18 11:31:31'),
(288, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-18 12:31:33'),
(289, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-18 12:32:37'),
(290, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-19 04:27:45'),
(291, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-19 05:28:27'),
(292, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-19 05:29:04'),
(293, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-19 06:29:09'),
(294, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-19 06:33:11'),
(295, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-19 09:15:16'),
(296, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-19 10:16:09'),
(297, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-19 10:21:39'),
(298, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-19 11:22:09'),
(299, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-19 11:23:12'),
(300, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-19 12:23:45'),
(301, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-19 12:24:00'),
(302, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-20 04:18:19'),
(303, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-05-20 05:04:58'),
(304, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-20 05:05:17'),
(305, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-20 06:05:45'),
(306, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-20 06:27:41'),
(307, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-20 09:44:32'),
(308, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-20 11:57:10'),
(309, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-20 12:44:40'),
(310, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-21 04:13:02'),
(311, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-21 05:13:34'),
(312, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-21 05:18:16'),
(313, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-21 06:18:50'),
(314, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-21 06:21:13'),
(315, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-05-21 07:14:02'),
(316, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-21 07:14:15'),
(317, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-21 08:55:22'),
(318, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-21 11:26:40'),
(319, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-21 12:26:50'),
(320, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-21 12:28:49'),
(321, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-22 05:28:03'),
(322, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-22 06:28:21'),
(323, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-22 09:13:08'),
(324, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-22 10:14:16'),
(325, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-22 10:15:49'),
(326, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-22 11:25:12'),
(327, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-22 12:25:29'),
(328, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-22 12:30:23'),
(329, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-23 04:13:56'),
(330, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-23 05:14:21'),
(331, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-23 06:00:31'),
(332, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-23 09:12:46'),
(333, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-23 10:13:41'),
(334, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-23 10:27:53'),
(335, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-23 11:28:43'),
(336, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-23 11:31:53'),
(337, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-23 12:51:11'),
(338, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-25 10:31:45'),
(339, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-25 11:31:50'),
(340, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-25 12:11:40'),
(341, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-29 06:15:33'),
(342, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-29 07:15:40'),
(343, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-29 07:27:20'),
(344, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-29 09:17:45'),
(345, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-29 10:22:22'),
(346, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-05-29 11:22:40'),
(347, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-05-30 06:36:11'),
(348, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-01 06:57:30'),
(349, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-01 07:57:52'),
(350, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-01 07:59:07'),
(351, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-01 08:49:07'),
(352, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-01 09:58:32'),
(353, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-01 10:40:32'),
(354, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-01 12:30:53'),
(355, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-02 04:35:28'),
(356, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-02 05:37:25'),
(357, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-02 05:41:04'),
(358, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-02 10:10:26'),
(359, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-02 11:11:26'),
(360, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-02 11:13:10'),
(361, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-02 12:13:26'),
(362, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-02 12:16:33'),
(363, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-03 04:22:12'),
(364, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-03 05:23:01'),
(365, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-03 05:23:50'),
(366, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-03 06:23:50'),
(367, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-03 06:24:49'),
(368, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-03 07:25:00'),
(369, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-03 07:25:17'),
(370, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-03 08:25:29'),
(371, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-03 08:41:14'),
(372, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-03 09:41:29'),
(373, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-03 09:45:06'),
(374, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-03 10:46:01'),
(375, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-03 10:56:54'),
(376, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-04 05:08:44'),
(377, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-04 06:09:28'),
(378, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-04 06:38:16'),
(379, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-04 07:38:30'),
(380, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-04 07:48:30'),
(381, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-04 09:04:14'),
(382, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-04 11:10:41'),
(383, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-04 11:10:53'),
(384, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-05 04:21:04'),
(385, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-05 05:21:08'),
(386, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-05 05:21:38'),
(387, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-05 06:07:27'),
(388, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-05 07:07:27'),
(389, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-05 07:07:52'),
(390, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-05 08:07:52'),
(391, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-05 08:08:09'),
(392, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-05 08:44:27'),
(393, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-05 10:02:51'),
(394, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-05 12:10:37'),
(395, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-06-06 04:21:42'),
(396, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-06-06 04:22:00'),
(397, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-06 04:22:37'),
(398, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-06 05:23:20'),
(399, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-06 05:24:28'),
(400, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-06 06:20:53'),
(401, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-06 06:21:08'),
(402, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-06 06:24:28'),
(403, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-06-06 06:36:09'),
(404, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-06 06:36:26'),
(405, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-06 07:27:51'),
(406, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-06 07:37:11'),
(407, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-06 10:16:36'),
(408, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-06 11:16:36'),
(409, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-06 11:16:59'),
(410, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-08 04:26:05'),
(411, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-08 05:24:07'),
(412, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-08 06:24:51'),
(413, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-08 06:28:12'),
(414, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-08 07:00:10'),
(415, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-08 11:22:04'),
(416, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-08 12:22:50'),
(417, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-06-09 07:38:45'),
(418, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-09 07:38:59'),
(419, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-06-09 09:13:26'),
(420, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-09 09:13:39'),
(421, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-09 10:13:53'),
(422, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-06-09 10:28:28'),
(423, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-09 10:28:49'),
(424, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-10 05:07:48'),
(425, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-10 10:11:48'),
(426, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-06-10 11:00:41'),
(427, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-10 11:00:54'),
(428, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-10 12:00:55'),
(429, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-10 12:02:26'),
(430, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-10 13:02:45'),
(431, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-11 06:20:33'),
(432, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-11 07:21:28'),
(433, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-11 07:24:36'),
(434, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-11 08:24:43'),
(435, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-11 09:50:56'),
(436, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-11 10:35:02'),
(437, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-11 12:16:07'),
(438, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-11 13:16:36'),
(439, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-12 04:31:47'),
(440, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-12 05:31:47'),
(441, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-12 05:49:37'),
(442, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-12 06:50:19'),
(443, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-06-12 06:52:08'),
(444, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-12 06:52:22'),
(445, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-12 07:52:23'),
(446, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-13 04:58:06'),
(447, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-13 06:22:18'),
(448, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-13 07:22:56'),
(449, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-13 07:26:14'),
(450, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-13 08:26:58'),
(451, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-13 09:11:55'),
(452, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-13 10:11:56'),
(453, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-13 10:12:44'),
(454, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-13 11:57:53'),
(455, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-06-13 12:05:09'),
(456, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-13 12:05:28'),
(457, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-15 07:19:48'),
(458, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-15 08:20:42'),
(459, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-15 09:28:40'),
(460, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-16 06:07:04'),
(461, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-16 09:06:10'),
(462, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-17 04:47:33'),
(463, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-17 07:11:56'),
(464, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-17 08:12:21'),
(465, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-17 08:14:51'),
(466, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-17 09:48:10'),
(467, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-17 10:48:23'),
(468, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-18 06:19:55'),
(469, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-22 04:27:52'),
(470, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-23 07:01:13'),
(471, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-23 07:50:13'),
(472, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-23 09:14:43'),
(473, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-23 10:15:17'),
(474, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-23 10:21:26'),
(475, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-23 11:22:17'),
(476, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-23 11:31:02'),
(477, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-23 12:31:17'),
(478, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-23 12:32:03'),
(479, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-24 06:37:08'),
(480, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-24 09:29:49'),
(481, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-24 10:30:04'),
(482, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-24 10:40:00'),
(483, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-24 12:25:25'),
(484, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-25 04:41:52');
INSERT INTO `login_logs` (`id`, `user_id`, `email`, `ip_address`, `user_agent`, `status`, `action`, `created_at`) VALUES
(485, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-25 05:42:50'),
(486, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-25 05:43:39'),
(487, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-25 06:27:16'),
(488, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-25 07:04:51'),
(489, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-25 08:05:50'),
(490, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-25 08:47:26'),
(491, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'auto_logout', '2026-06-25 09:47:50'),
(492, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-27 05:10:58'),
(493, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-27 05:33:14'),
(494, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-27 05:33:26'),
(495, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 05:19:27'),
(496, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 05:25:12'),
(497, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 05:26:10'),
(498, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-06-29 05:26:24'),
(499, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 05:27:49'),
(500, NULL, 'kajalritik098@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 05:31:30'),
(501, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 06:08:59'),
(502, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 06:09:47'),
(503, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 06:10:02'),
(504, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 06:12:41'),
(505, 11, 'superadmin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 06:12:53'),
(506, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 06:28:14'),
(507, 11, 'superadmin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 07:10:38'),
(508, 12, 'sunilkumar@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 07:10:54'),
(509, 12, 'sunilkumar@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 07:38:47'),
(510, 12, 'sunilkumar@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 07:39:01'),
(511, 12, 'sunilkumar@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 09:07:45'),
(512, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 09:16:31'),
(513, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 09:20:46'),
(514, 11, 'superadmin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-06-29 09:21:14'),
(515, 11, 'superadmin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 09:21:49'),
(516, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 09:53:16'),
(517, 18, 'partikkumar@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 09:53:30'),
(518, 18, 'partikkumar@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 09:54:15'),
(519, 20, 'kirankumar@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 09:57:10'),
(520, 20, 'kirankumar@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 09:57:53'),
(521, 21, 'ajitkumar@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'failed', 'password_login', '2026-06-29 10:10:56'),
(522, 21, 'ajitkumar@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 10:11:17'),
(523, 21, 'ajitkumar@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 10:12:16'),
(524, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 10:12:30'),
(525, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 10:47:28'),
(526, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-29 10:49:20'),
(527, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-29 10:50:34'),
(528, 6, 'admin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'manual_logout', '2026-06-30 06:24:18'),
(529, 11, 'superadmin@jshb.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'success', 'password_login', '2026-06-30 06:24:34');

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
(8, '2026_04_17_161557_add_photo_to_users_table', 2),
(9, '2026_04_18_000000_add_is_locked_to_users_table', 3),
(10, '2026_04_18_120000_create_or_update_divisions_table', 4),
(11, '2026_04_18_121000_create_or_update_sub_divisions_table', 5),
(12, '2026_04_18_122000_create_organizations_table', 6),
(20, '2026_04_18_123000_create_post_types_table', 7),
(21, '2026_04_18_124000_create_or_update_block_lists_table', 8),
(22, '2026_04_18_130000_create_engineer_details_table', 14),
(23, '2026_04_18_130000_create_engineer_details_table', 8),
(24, '2026_04_20_120000_create_guest_house_requisitions_table', 9),
(25, '2026_04_20_000000_add_parent_organization_to_organizations_table', 15),
(26, '2026_04_20_100000_create_departments_table', 15),
(27, '2026_04_20_101000_add_department_id_to_engineer_details_table', 15),
(28, '2026_04_20_140000_encrypt_sensitive_employee_fields', 15),
(29, '2026_04_20_200000_create_parent_organizations_table', 15),
(30, '2026_04_21_000000_modify_parent_organizations_table', 15),
(31, '2026_05_11_000000_add_payment_fields_to_allottees_table', 16),
(32, '2026_05_11_160000_create_allottee_process_tracking_tables', 17),
(33, '2026_06_23_154042_create_allottee_site_verifications_table', 18),
(34, '2026_06_29_103033_create_roles_table', 19),
(35, '2026_06_29_115502_add_status_to_users_table', 20),
(36, '2026_06_29_125429_add_user_type_to_users_table', 21),
(37, '2026_06_30_000001_add_username_to_users_table', 22);

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
(1, NULL, 'pchamplin@example.org', '5452', 1, 'login', '218.123.223.35', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_2 rv:5.0) Gecko/20161111 Firefox/35.0', '2026-04-16 04:58:30'),
(2, NULL, 'kaylah.barton@example.org', '9786', 0, 'login', '195.215.111.14', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.0 (KHTML, like Gecko) Chrome/92.0.4563.42 Safari/537.0 EdgA/92.01072.62', '2026-04-16 04:58:31'),
(3, NULL, 'wlowe@example.com', '3174', 1, 'login', '139.168.206.127', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_7_1 rv:6.0; nl-NL) AppleWebKit/533.18.6 (KHTML, like Gecko) Version/4.0.2 Safari/533.18.6', '2026-04-16 04:58:31'),
(4, NULL, 'aufderhar.godfrey@example.org', '5059', 0, 'login', '232.22.9.60', 'Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20110313 Firefox/37.0', '2026-04-16 04:58:31'),
(5, NULL, 'leora11@example.com', '3282', 1, 'login', '100.187.94.54', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_7_8) AppleWebKit/532.1 (KHTML, like Gecko) Chrome/92.0.4308.43 Safari/532.1 Edg/92.01144.79', '2026-04-16 04:58:31'),
(6, 6, 'admin@example.com', '474129', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 06:09:19'),
(7, 6, 'admin@example.com', '625421', 0, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 06:14:26'),
(8, 6, 'admin@example.com', '003956', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 07:11:02'),
(9, 6, 'admin@example.com', '731229', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '2026-04-16 07:27:56'),
(10, 6, 'admin@example.com', '815486', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 22:42:41'),
(11, 6, 'admin@example.com', '416581', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 09:10:08'),
(12, 6, 'admin@example.com', '223045', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '2026-04-17 09:28:48'),
(13, 6, 'admin@example.com', '816865', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 10:20:44'),
(14, 6, 'admin@example.com', '759982', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 10:51:13'),
(15, 6, 'admin@jesa.com', '875825', 1, 'password_reset', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 05:35:55'),
(16, 6, 'admin@jesa.com', '656176', 1, 'password_reset', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 06:53:26'),
(17, 6, 'admin@jesa.com', '481983', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-30 04:48:38'),
(18, 6, 'admin@jesa.com', '530326', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-30 05:36:25'),
(19, 6, 'admin@jshb.com', '507663', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-30 05:42:44'),
(20, 6, 'admin@jesa.com', '320006', 0, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-30 06:43:17'),
(21, 6, 'admin@jesa.com', '015272', 1, 'password_reset', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-30 09:29:15'),
(22, 6, 'admin@jesa.com', '534657', 1, 'login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-30 09:37:06'),
(23, 6, 'admin@jshb.com', '685811', 1, 'password_reset', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-30 10:35:00'),
(24, NULL, 'kajalritik098@gmail.com', '233066', 1, 'password_reset', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-29 05:26:39');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('kajalritik098@gmail.com', '$2y$12$j5tx2OxicQX6YZWB3eBNYOhkEqZOqZNAw0mWxmP8RQ6iK8mZZFqs.', '2026-04-20 05:31:06');

-- --------------------------------------------------------

--
-- Table structure for table `payment_penalty_rules`
--

CREATE TABLE `payment_penalty_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_day` int(11) NOT NULL,
  `to_day` int(11) NOT NULL,
  `penalty_percentage` decimal(5,2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_penalty_rules`
--

INSERT INTO `payment_penalty_rules` (`id`, `from_day`, `to_day`, `penalty_percentage`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 30, 0.50, 1, '2026-05-15 07:09:53', '2026-05-15 07:09:53'),
(2, 31, 60, 1.50, 1, '2026-05-15 07:09:53', '2026-05-15 07:09:53'),
(3, 61, 90, 4.00, 1, '2026-05-15 07:09:53', '2026-05-15 07:09:53'),
(4, 91, 120, 6.50, 1, '2026-05-15 07:09:53', '2026-05-15 07:09:53'),
(5, 121, 150, 9.00, 1, '2026-05-15 07:09:53', '2026-05-15 07:09:53'),
(6, 151, 180, 12.00, 1, '2026-05-15 07:09:53', '2026-05-15 07:09:53');

-- --------------------------------------------------------

--
-- Table structure for table `post_types`
--

CREATE TABLE `post_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `level` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_types`
--

INSERT INTO `post_types` (`id`, `level`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Top-Level (Executive / Head)', 'Chief Engineer (CE)', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(2, 'Top-Level (Executive / Head)', 'Engineer-in-Chief (E-in-C)', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(3, 'Top-Level (Executive / Head)', 'Director (Engineering)', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(4, 'Top-Level (Executive / Head)', 'Technical Director', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(5, 'Senior Management', 'Superintending Engineer (SE)', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(6, 'Senior Management', 'Senior General Manager (Engineering)', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(7, 'Senior Management', 'General Manager (Engineering)', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(8, 'Middle Management', 'Executive Engineer (EE)', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(9, 'Middle Management', 'Deputy General Manager (DGM - Engineering)', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(10, 'Middle Management', 'Project Manager', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(11, 'Middle Management', 'Divisional Engineer', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(12, 'Junior Management', 'Assistant Executive Engineer (AEE)', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(13, 'Junior Management', 'Assistant Engineer (AE)', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(14, 'Junior Management', 'Deputy Engineer', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(15, 'Entry-Level / Field Roles', 'Junior Engineer (JE)', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(16, 'Entry-Level / Field Roles', 'Section Engineer', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(17, 'Entry-Level / Field Roles', 'Site Engineer', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(18, 'Entry-Level / Field Roles', 'Field Engineer', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(19, 'Entry-Level / Field Roles', 'Trainee Engineer / Graduate Engineer Trainee (GET)', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(20, 'Specialized Roles (Non-IT but domain-specific)', 'Design Engineer', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(21, 'Specialized Roles (Non-IT but domain-specific)', 'Planning Engineer', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(22, 'Specialized Roles (Non-IT but domain-specific)', 'Quality Control Engineer', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(23, 'Specialized Roles (Non-IT but domain-specific)', 'Maintenance Engineer', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(24, 'Specialized Roles (Non-IT but domain-specific)', 'Production Engineer', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL),
(25, 'Specialized Roles (Non-IT but domain-specific)', 'Safety Engineer', 1, '2026-04-21 05:20:16', '2026-04-21 05:20:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `property_category`
--

CREATE TABLE `property_category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(4) DEFAULT 1 COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_category`
--

INSERT INTO `property_category` (`id`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Residential', 1, '2026-02-06 11:11:46', '2026-05-02 05:10:03', NULL),
(2, 'Commercial', 1, '2026-02-06 11:12:06', '2026-06-13 10:02:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `property_sub_type`
--

CREATE TABLE `property_sub_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ptype_id` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT 1 COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_sub_type`
--

INSERT INTO `property_sub_type` (`id`, `name`, `ptype_id`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1 BHK', 1, 1, '2026-02-06 12:49:34', '2026-02-07 04:23:22', NULL),
(2, '2 BHK', 1, 1, '2026-02-06 12:50:54', '2026-02-06 12:50:54', NULL),
(3, '3 BHK', 1, 1, '2026-02-06 12:51:41', '2026-02-06 12:59:39', NULL),
(4, 'Shop', 5, 1, '2026-02-07 04:40:58', '2026-02-10 13:50:45', NULL),
(5, '1 BHK', 2, 1, '2026-02-10 14:11:24', '2026-02-10 14:11:24', NULL),
(6, '2 BHK', 2, 1, '2026-02-10 14:11:35', '2026-02-10 14:11:35', NULL),
(7, '3 BHK', 2, 1, '2026-02-10 14:12:05', '2026-02-10 14:12:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `property_type`
--

CREATE TABLE `property_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `parent_type_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1 COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_type`
--

INSERT INTO `property_type` (`id`, `name`, `category_id`, `parent_type_id`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Flat', 1, NULL, 1, '2026-02-06 11:56:35', '2026-06-13 07:57:06', NULL),
(2, 'House', 1, NULL, 1, '2026-02-06 11:56:53', '2026-02-06 11:56:53', NULL),
(3, 'Plot', 1, NULL, 1, '2026-02-06 11:57:01', '2026-02-06 11:57:01', NULL),
(4, 'Plot', 2, NULL, 1, '2026-02-06 11:57:10', '2026-02-06 11:57:10', NULL),
(5, 'Shop', 2, NULL, 1, '2026-02-06 11:57:21', '2026-02-06 12:00:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quarter_type`
--

CREATE TABLE `quarter_type` (
  `quarter_id` int(11) NOT NULL,
  `quarter_code` varchar(10) NOT NULL COMMENT 'HIG, LIG, MIG, EWS',
  `quarter_name` varchar(100) NOT NULL,
  `quarter_full_name` varchar(200) DEFAULT NULL,
  `min_income` decimal(12,2) DEFAULT NULL COMMENT 'Minimum annual income in lakhs',
  `max_income` decimal(12,2) DEFAULT NULL COMMENT 'Maximum annual income in lakhs',
  `display_order` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1 COMMENT '0=Inactive, 1=Active',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quarter_type`
--

INSERT INTO `quarter_type` (`quarter_id`, `quarter_code`, `quarter_name`, `quarter_full_name`, `min_income`, `max_income`, `display_order`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 'H.I.G', 'High Income Group', 'High Income Group Quarters', 12.00, NULL, 1, 1, NULL, '2026-02-07 05:58:34', '2026-06-25 07:46:03'),
(3, 'L.I.G', 'Low Income Group', 'Low Income Group Quarters', 3.00, 6.00, 3, 1, NULL, '2026-02-07 05:58:56', '2026-06-25 07:46:08'),
(4, 'M.I.G', 'Middle Income Group', 'Middle Income Group Quarters', 6.00, 12.00, 2, 1, NULL, '2026-02-10 13:54:45', '2026-06-25 07:46:13'),
(5, 'E.W.S', 'Economically Weaker Section', 'Economically Weaker Section Quarters', 1.00, 3.00, 4, 1, NULL, '2026-02-10 13:55:34', '2026-06-25 07:46:17'),
(6, 'I.S.H.S', 'Industrial housing scheme', 'Industrial housing scheme', 3.00, 6.00, 5, 1, NULL, '2026-06-15 07:56:48', '2026-06-15 07:57:00');

-- --------------------------------------------------------

--
-- Table structure for table `quota_types`
--

CREATE TABLE `quota_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quota_types`
--

INSERT INTO `quota_types` (`id`, `name`, `code`) VALUES
(1, 'General', 'GENERAL'),
(2, 'General Divyang', 'GENERAL_DIVYANG'),
(3, 'SC', 'SC'),
(4, 'SC Divyang', 'SC_DIVYANG'),
(5, 'ST', 'ST'),
(6, 'ST Divyang', 'ST_DIVYANG'),
(7, 'OBC', 'OBC'),
(8, 'OBC Divyang', 'OBC_DIVYANG'),
(9, 'Sports', 'SPORTS'),
(10, 'Ex Serviceman', 'EX_SERVICEMAN'),
(11, 'Govt Employee', 'GOVT_EMPLOYEE'),
(12, 'MLA/MP Quota', 'MLA_MP'),
(13, 'Direct Allotment', 'DIRECT_ALLOTMENT');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `short_name` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `short_name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dealing Assistant', 'dealing-assistant', 'DA', 1, '2026-06-29 05:02:57', '2026-06-29 05:02:57', NULL),
(2, 'Office Superintendent', 'office-superintendent', 'OS', 1, '2026-06-29 05:02:58', '2026-06-29 05:02:58', NULL),
(3, 'Estate Officer', 'estate-officer', 'EO', 1, '2026-06-29 05:02:58', '2026-06-29 05:02:58', NULL),
(4, 'Managing Director', 'managing-director', 'MD', 1, '2026-06-29 05:02:58', '2026-06-29 05:02:58', NULL),
(5, 'Executive Engineer', 'executive-engineer', 'EE', 1, '2026-06-29 05:02:58', '2026-06-29 05:02:58', NULL),
(6, 'Assistant Engineer', 'assistant-engineer', 'AE', 1, '2026-06-29 05:02:58', '2026-06-29 05:02:58', NULL),
(7, 'Junior Engineer', 'junior-engineer', 'JE', 1, '2026-06-29 05:02:58', '2026-06-29 05:02:58', NULL),
(8, 'Admin', 'admin', 'AD', 1, '2026-06-29 05:02:58', '2026-06-29 05:02:58', NULL),
(9, 'Super Admin', 'super-admin', 'SA', 1, '2026-06-29 05:02:58', '2026-06-29 05:02:58', NULL),
(10, 'Operator', 'operator', 'OP', 1, '2026-06-29 05:02:58', '2026-06-29 05:02:58', NULL),
(11, 'Staff', 'staff', 'STAFF', 1, '2026-06-29 05:02:58', '2026-06-29 05:02:58', NULL),
(12, 'Allottee', 'allottee', 'ALLOTTEE', 1, '2026-06-29 05:02:58', '2026-06-29 05:02:58', NULL),
(13, 'Division Officer', 'division-officer', 'DO', 1, '2026-06-29 05:11:58', '2026-06-29 05:11:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `schemes`
--

CREATE TABLE `schemes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `division_id` bigint(20) UNSIGNED NOT NULL,
  `sub_division_id` bigint(20) UNSIGNED NOT NULL,
  `pcategory_id` bigint(20) UNSIGNED NOT NULL,
  `p_type_id` bigint(20) UNSIGNED NOT NULL,
  `p_sub_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quarter_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `scheme_name` varchar(255) NOT NULL,
  `scheme_name_hindi` varchar(255) DEFAULT NULL,
  `scheme_code` varchar(255) DEFAULT NULL,
  `total_units` int(11) NOT NULL,
  `lease_period` int(11) NOT NULL DEFAULT 90,
  `initiation_year` year(4) NOT NULL,
  `scheme_start_date` date NOT NULL,
  `scheme_end_date` date DEFAULT NULL,
  `status` enum('draft','active','completed','cancelled') NOT NULL DEFAULT 'active',
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schemes`
--

INSERT INTO `schemes` (`id`, `division_id`, `sub_division_id`, `pcategory_id`, `p_type_id`, `p_sub_type_id`, `quarter_type_id`, `scheme_name`, `scheme_name_hindi`, `scheme_code`, `total_units`, `lease_period`, `initiation_year`, `scheme_start_date`, `scheme_end_date`, `status`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 2, 5, 1, 1, 1, 2, '44 HIG -Adityapur Dindli Basti , Seraikela-kharsawan', '44 एचआईजी-आदित्यपुर दिंदली बस्ती, सरायकेला-खरसावां', 'SCH-44-DINDLI-ADTP', 44, 90, '1962', '1962-12-12', NULL, 'active', 1, 6, NULL, '2026-02-18 07:24:13', '2026-04-22 05:47:33', NULL),
(5, 1, 1, 1, 1, 1, 3, '67 - RNC HI FLAT HARMU, RANCHI', '67 - आरएनसी हाई फ्लैट हारमू, रांची', 'SCH-67-RNC-HRMU', 67, 90, '1990', '1990-12-02', NULL, 'active', 1, 6, NULL, '2026-02-18 07:54:39', '2026-02-18 07:54:39', NULL),
(6, 2, 15, 1, 2, 6, 4, '33-Jasmhedpur Scheme for MIG Class', '33-एमआईजी श्रेणी के लिए जसमेहदपुर योजना', '33-SCH-JSR-CHTG-2423', 33, 90, '2018', '2026-05-13', NULL, 'active', 1, 6, NULL, '2026-05-04 11:46:00', '2026-05-05 06:26:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `scheme_blocks`
--

CREATE TABLE `scheme_blocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scheme_id` bigint(20) UNSIGNED NOT NULL,
  `scheme_property_type` varchar(100) DEFAULT NULL,
  `block_name` varchar(255) NOT NULL,
  `area_sqft` int(11) DEFAULT NULL,
  `undivided_land_share` int(11) DEFAULT NULL,
  `total_buildup` int(11) DEFAULT NULL,
  `total_area_of_construction` int(11) DEFAULT NULL,
  `dimension_east` varchar(50) DEFAULT NULL,
  `dimension_west` varchar(50) DEFAULT NULL,
  `dimension_north` varchar(50) DEFAULT NULL,
  `dimension_south` varchar(50) DEFAULT NULL,
  `arm_east_west_north` varchar(50) DEFAULT NULL,
  `arm_east_west_south` varchar(50) DEFAULT NULL,
  `arm_north_south_east` varchar(50) DEFAULT NULL,
  `arm_north_south_west` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scheme_blocks`
--

INSERT INTO `scheme_blocks` (`id`, `scheme_id`, `scheme_property_type`, `block_name`, `area_sqft`, `undivided_land_share`, `total_buildup`, `total_area_of_construction`, `dimension_east`, `dimension_west`, `dimension_north`, `dimension_south`, `arm_east_west_north`, `arm_east_west_south`, `arm_north_south_east`, `arm_north_south_west`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 'Flat', 'Block 1', 1200, 200, 1000, NULL, '20', '20', '20', '20', '4', '4', '4', '4', 1, 3, 3, '2026-02-18 10:21:25', '2026-05-05 07:11:17', NULL),
(2, 5, 'Flat', 'Block Type 2', 1400, 250, 1150, NULL, '30', '30', '30', '30', '4', '4', '4', '4', 1, 3, NULL, '2026-02-18 10:23:05', '2026-05-05 07:11:19', NULL),
(3, 6, 'House', 'Block 1', 1200, 100, 300, 1200, '40', '60', '45', '80', '40', '60', '60', '40', 1, 6, 6, '2026-05-05 07:39:09', '2026-05-05 07:57:00', '2026-05-05 07:57:00'),
(4, 6, 'House', 'Block 2', 1300, 230, 200, 1800, '40', '30', '33', '34', '56', '66', '30', '22', 1, 6, NULL, '2026-05-05 07:42:01', '2026-05-05 07:42:01', NULL),
(5, 4, 'Flat', 'Block 1', 1200, 100, 1500, 1000, '30', '40', '15', '24', '60', '25', '10', '56', 1, 6, NULL, '2026-05-25 10:39:15', '2026-05-25 10:39:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `scheme_financials`
--

CREATE TABLE `scheme_financials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scheme_id` bigint(20) UNSIGNED NOT NULL,
  `property_total_cost` decimal(15,2) NOT NULL,
  `lottery_percentage` decimal(5,2) NOT NULL,
  `lottery_amount` decimal(15,2) NOT NULL,
  `allotment_percentage` decimal(5,2) NOT NULL,
  `allotement_amount` decimal(15,2) NOT NULL,
  `balance_amount` decimal(15,2) NOT NULL,
  `emi_count` int(11) NOT NULL,
  `normal_interest_rate` decimal(5,2) NOT NULL DEFAULT 13.50,
  `emi_without_penalty` decimal(15,2) NOT NULL,
  `penalty_interest_rate` decimal(5,2) NOT NULL DEFAULT 2.50,
  `emi_with_penalty` decimal(15,2) NOT NULL,
  `admin_charges` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scheme_financials`
--

INSERT INTO `scheme_financials` (`id`, `scheme_id`, `property_total_cost`, `lottery_percentage`, `lottery_amount`, `allotment_percentage`, `allotement_amount`, `balance_amount`, `emi_count`, `normal_interest_rate`, `emi_without_penalty`, `penalty_interest_rate`, `emi_with_penalty`, `admin_charges`, `created_at`, `updated_at`) VALUES
(2, 4, 2088000.00, 10.00, 208800.00, 15.00, 313200.00, 1566000.00, 60, 13.50, 36034.00, 2.50, 38083.00, 5.00, '2026-02-18 07:24:13', '2026-02-18 07:24:13'),
(3, 5, 522000.00, 10.00, 52200.00, 15.00, 78300.00, 391500.00, 60, 13.50, 8456.00, 2.50, 8865.00, 3000.00, '2026-02-18 07:54:39', '2026-02-18 10:03:46'),
(4, 6, 2088000.00, 10.00, 208800.00, 15.00, 313200.00, 1566000.00, 60, 13.50, 36034.00, 2.50, 38083.00, 300.00, '2026-05-04 11:46:00', '2026-05-05 06:05:44');

-- --------------------------------------------------------

--
-- Table structure for table `scheme_quarter_fees`
--

CREATE TABLE `scheme_quarter_fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scheme_id` bigint(20) UNSIGNED NOT NULL,
  `quarter_type_id` bigint(20) UNSIGNED NOT NULL,
  `application_fee` decimal(15,2) NOT NULL,
  `emd_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scheme_quarter_fees`
--

INSERT INTO `scheme_quarter_fees` (`id`, `scheme_id`, `quarter_type_id`, `application_fee`, `emd_amount`, `created_at`, `updated_at`) VALUES
(1, 4, 2, 3000.00, 10000.00, '2026-02-18 07:24:13', '2026-02-18 07:24:13'),
(2, 4, 3, 2000.00, 7000.00, '2026-02-18 07:24:13', '2026-02-18 07:24:13'),
(3, 4, 4, 1000.00, 4000.00, '2026-02-18 07:24:13', '2026-02-18 07:24:13'),
(4, 4, 5, 1000.00, 4000.00, '2026-02-18 07:24:13', '2026-02-18 07:24:13'),
(5, 5, 2, 2500.00, 8000.00, '2026-02-18 07:54:39', '2026-02-18 07:54:39'),
(6, 5, 3, 1500.00, 4000.00, '2026-02-18 07:54:39', '2026-02-18 07:54:39'),
(7, 5, 4, 500.00, 2000.00, '2026-02-18 07:54:39', '2026-02-18 07:54:39'),
(8, 5, 5, 500.00, 2000.00, '2026-02-18 07:54:39', '2026-02-18 07:54:39'),
(9, 6, 2, 4000.00, 400.00, '2026-05-04 11:46:00', '2026-05-04 11:46:00'),
(10, 6, 3, 3000.00, 300.00, '2026-05-04 11:46:00', '2026-05-04 11:46:00'),
(11, 6, 4, 2500.00, 250.00, '2026-05-04 11:46:00', '2026-05-04 11:46:00'),
(12, 6, 5, 2000.00, 200.00, '2026-05-04 11:46:00', '2026-05-04 11:46:00');

-- --------------------------------------------------------

--
-- Table structure for table `scheme_unit_quotas`
--

CREATE TABLE `scheme_unit_quotas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scheme_id` bigint(20) UNSIGNED NOT NULL,
  `quota_type_id` varchar(100) NOT NULL,
  `total_units` int(11) NOT NULL DEFAULT 0,
  `allotted_units` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scheme_unit_quotas`
--

INSERT INTO `scheme_unit_quotas` (`id`, `scheme_id`, `quota_type_id`, `total_units`, `allotted_units`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 6, '1', 5, 2, 6, 6, '2026-05-05 09:47:57', '2026-05-05 10:59:31', NULL),
(2, 6, '10', 4, 3, 6, 6, '2026-05-05 09:48:13', '2026-05-05 10:59:31', NULL),
(3, 5, '1', 10, 6, 6, 6, '2026-05-05 10:31:10', '2026-05-05 11:09:03', NULL),
(4, 5, '2', 3, 2, 6, 6, '2026-05-05 10:31:10', '2026-05-05 11:09:03', NULL),
(5, 5, '3', 0, 0, 6, 6, '2026-05-05 10:31:10', '2026-05-05 11:09:03', NULL),
(6, 5, '4', 7, 5, 6, 6, '2026-05-05 10:31:10', '2026-05-05 11:09:03', NULL),
(7, 6, '2', 3, 3, 6, NULL, '2026-05-05 10:59:31', '2026-05-05 10:59:31', NULL),
(8, 5, '5', 5, 3, 6, NULL, '2026-05-25 10:38:11', '2026-05-25 10:38:11', NULL);

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
('0CaDwKblWQxxfshf401IywWX1lcjOYFxyoHVh9I2', 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiclhiMHU0eDExVjMwVGhkWFFqOEo4TjZSN0hmUkFOV2l3TFNWWkxkSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6OTk5OS9wYXNzd29yZC9jaGVjay1leHBpcnkiO3M6NToicm91dGUiO3M6MjE6InBhc3N3b3JkLmNoZWNrLWV4cGlyeSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjExO30=', 1782726788),
('gob0PhmsnVvoZBdGKuz15EImeQlHlZkmzVPWDKH8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSG9xRll6bkZYRFVpTVFPZmdtcno3eWl2RXZMS3c3QjgyUThPODE2bSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6OTk5OS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1782380870),
('lbaDVrJjjlt2Ek7nHmdfrJZPgnGluFm2lec5Zj2I', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoickpmQzkyVXpUbndqTGtDZ3FyaFZPYnZEaXlKeEZJT1NNNU5MbHF4NCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjQxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZ2V0LXByb3BlcnR5LXR5cGVzL2V5SnBkaUk2SWt3cloxQmpNSGN3Y21aeGFraHNRbGd2ZDFaSFFtYzlQU0lzSW5aaGJIVmxJam9pVGs5S1prbDRhMmd6ZFdwVVpVVXpkVTUzVUROblVUMDlJaXdpYldGaklqb2laakJoTmpsbU1qQm1OV0U0WkRWbE1EQTNOelExTURnNE1UZGlOVGN4TnpNd09EZGhaV1V3T1RSa05HTXhPVFF3TkRKa01ETTJaRGxoTkRVNE4yWTBPQ0lzSW5SaFp5STZJaUo5IjtzOjU6InJvdXRlIjtOO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1782731048),
('rlNcfJKuO6VZXABHv5UdJaEtDgVklxExJZ0LQ1Bt', 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVmFMM3pYR2c1dXdJYUtXY0djczJZaXdPeDdoaE02SlpnTG53aXlLaiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM4OiJodHRwOi8vMTI3LjAuMC4xOjk5OTkvYWRtaW4vYWxsb3R0ZWVzL2V5SnBkaUk2SWxZclZFbHdVMjE2ZWxsMlVuTklkMXBSV1ZaTlpXYzlQU0lzSW5aaGJIVmxJam9pVHpoU0szVXZjamRaVWtwNWMwaHFaRmxSY1cwMFVUMDlJaXdpYldGaklqb2lORFEyTXpJM09XWTRNbVV4WWpVeU1EbGxPVEptTkdVMFptVmhOR1ZqWkRrNE9HWm1aR1JtT1RNNVl6ZzNaall5TW1ReU1qQTFZemxqWldaaE5qaGhZaUlzSW5SaFp5STZJaUo5IjtzOjU6InJvdXRlIjtzOjIwOiJhZG1pbi5hbGxvdHRlZXMuc2hvdyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjExO3M6MTA6ImNhcHRjaGFfMTEiO2E6Mzp7czo4OiJxdWVzdGlvbiI7czo1OiI0ICogNSI7czo2OiJhbnN3ZXIiO2k6MjA7czoxMjoiZ2VuZXJhdGVkX2F0IjtPOjI1OiJJbGx1bWluYXRlXFN1cHBvcnRcQ2FyYm9uIjozOntzOjQ6ImRhdGUiO3M6MjY6IjIwMjYtMDYtMzAgMTQ6NDg6MTkuNTMxMDk3IjtzOjEzOiJ0aW1lem9uZV90eXBlIjtpOjM7czo4OiJ0aW1lem9uZSI7czoxMjoiQXNpYS9Lb2xrYXRhIjt9fX0=', 1782811230);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(10) UNSIGNED NOT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `name_en` varchar(150) NOT NULL,
  `name_hi` varchar(150) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `type` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `display_name`, `name_en`, `name_hi`, `code`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Andaman and Nicobar Islands', 'Andaman and Nicobar Islands', 'अंडमान और निकोबार द्वीप समूह', NULL, 'Union Territory', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(2, 'Andhra Pradesh', 'Andhra Pradesh', 'आंध्र प्रदेश', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(3, 'Arunachal Pradesh', 'Arunachal Pradesh', 'अरुणाचल प्रदेश', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(4, 'Assam', 'Assam', 'असम', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(5, 'Bihar', 'Bihar', 'बिहार', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(6, 'Chandigarh', 'Chandigarh', 'चंडीगढ़', NULL, 'Union Territory', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(7, 'Chhattisgarh', 'Chhattisgarh', 'छत्तीसगढ़', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(8, 'Dadra and Nagar Haveli and Daman and Diu', 'Dadra and Nagar Haveli and Daman and Diu', 'दादरा और नगर हवेली और दमन और दीव', NULL, 'Union Territory', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(9, 'Delhi', 'Delhi', 'दिल्ली', NULL, 'Union Territory', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(10, 'Goa', 'Goa', 'गोवा', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(11, 'Gujarat', 'Gujarat', 'गुजरात', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(12, 'Haryana', 'Haryana', 'हरियाणा', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(13, 'Himachal Pradesh', 'Himachal Pradesh', 'हिमाचल प्रदेश', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(14, 'Jammu and Kashmir', 'Jammu and Kashmir', 'जम्मू और कश्मीर', NULL, 'Union Territory', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(15, 'Jharkhand', 'Jharkhand', 'झारखंड', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(16, 'Karnataka', 'Karnataka', 'कर्नाटक', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(17, 'Kerala', 'Kerala', 'केरल', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(18, 'Ladakh', 'Ladakh', 'लद्दाख', NULL, 'Union Territory', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(19, 'Lakshadweep', 'Lakshadweep', 'लक्षद्वीप', NULL, 'Union Territory', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(20, 'Madhya Pradesh', 'Madhya Pradesh', 'मध्य प्रदेश', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(21, 'Maharashtra', 'Maharashtra', 'महाराष्ट्र', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(22, 'Manipur', 'Manipur', 'मणिपुर', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(23, 'Meghalaya', 'Meghalaya', 'मेघालय', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(24, 'Mizoram', 'Mizoram', 'मिजोरम', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(25, 'Nagaland', 'Nagaland', 'नागालैंड', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(26, 'Odisha', 'Odisha', 'ओडिशा', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(27, 'Puducherry', 'Puducherry', 'पुडुचेरी', NULL, 'Union Territory', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(28, 'Punjab', 'Punjab', 'पंजाब', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(29, 'Rajasthan', 'Rajasthan', 'राजस्थान', NULL, 'State', '2026-02-28 11:03:30', '2026-03-14 07:41:31'),
(30, 'Sikkim', 'Sikkim', 'सिक्किम', NULL, 'State', '2026-02-28 11:03:31', '2026-03-14 07:41:31'),
(31, 'Tamil Nadu', 'Tamil Nadu', 'तमिलनाडु', NULL, 'State', '2026-02-28 11:03:31', '2026-03-14 07:41:31'),
(32, 'Telangana', 'Telangana', 'तेलंगाना', NULL, 'State', '2026-02-28 11:03:31', '2026-03-14 07:41:31'),
(33, 'Tripura', 'Tripura', 'त्रिपुरा', NULL, 'State', '2026-02-28 11:03:31', '2026-03-14 07:41:31'),
(34, 'Uttar Pradesh', 'Uttar Pradesh', 'उत्तर प्रदेश', NULL, 'State', '2026-02-28 11:03:31', '2026-03-14 07:41:31'),
(35, 'Uttarakhand', 'Uttarakhand', 'उत्तराखंड', NULL, 'State', '2026-02-28 11:03:31', '2026-03-14 07:41:31'),
(36, 'West Bengal', 'West Bengal', 'पश्चिम बंगाल', NULL, 'State', '2026-02-28 11:03:31', '2026-03-14 07:41:31'),
(37, 'Bihar', 'Bihar (Now Jharkhand)', 'बिहार (अब झारखंड)', NULL, 'State', '2026-03-14 06:59:59', '2026-03-14 07:41:40');

-- --------------------------------------------------------

--
-- Table structure for table `sub_divisions`
--

CREATE TABLE `sub_divisions` (
  `id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subdivision_code` varchar(50) DEFAULT NULL,
  `colony_name` varchar(255) DEFAULT NULL,
  `locality_address` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_divisions`
--

INSERT INTO `sub_divisions` (`id`, `division_id`, `name`, `subdivision_code`, `colony_name`, `locality_address`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Harmu-Ranchi', 'HRM', 'Harmu Housing Colony', 'Harmu', 1, '2026-02-05 17:19:24', NULL, NULL),
(2, 1, 'Argora-Ranchi', 'ARG', 'Argora Colony', 'Argora', 1, '2026-02-05 17:19:24', NULL, NULL),
(3, 1, 'Bariatu-Ranchi', 'BTU', 'Bariatu Colony', 'Bariatu', 1, '2026-02-05 17:19:24', NULL, NULL),
(4, 1, 'Kadru-Ranchi', 'KDR', 'Kadru Colony', 'Kadru', 1, '2026-02-05 17:19:24', NULL, NULL),
(5, 2, 'Dindli-Adityapur', 'DND', 'Dindli Colony', 'Dindli', 1, '2026-02-05 17:23:25', NULL, NULL),
(6, 3, 'Sahibganj', 'SBG', 'Sahibganj Colony', 'Sahibganj', 1, '2026-02-05 17:23:25', NULL, NULL),
(7, 4, 'Kumardhubi-Dhanbad', 'KMD', 'Kumardhubi Colony', 'Kumardhubi', 1, '2026-02-05 17:23:25', NULL, NULL),
(8, 4, 'Balidih-Bokaro', 'BLH', 'Balidih Colony', 'Balidih', 1, '2026-02-05 17:23:25', NULL, NULL),
(9, 4, 'Gomia-Bokaro', 'GOM', 'Gomia Colony', 'Gomia', 1, '2026-02-05 17:23:25', NULL, NULL),
(10, 5, 'Sarle-Hazaribag', 'SLE', 'Sarle Colony', 'Sarle', 1, '2026-02-05 17:23:25', NULL, NULL),
(11, 5, 'Hazaribagh', 'HZB', 'Hazaribag Colony', 'Hazaribag', 1, '2026-02-05 17:23:25', NULL, NULL),
(12, 5, 'Daltanganj', 'DTO', 'Daltanganj Colony', 'Daltanganj', 1, '2026-02-06 14:27:48', '2026-04-18 07:40:21', NULL),
(13, 4, 'Hirapur-Dhanbad', 'HRP', 'Hirapur Colony', 'Hirapur', 1, '2026-02-10 19:05:56', NULL, NULL),
(14, 2, 'Adityapur-Jamshedpur', 'ADP', 'Adityapur Colony', 'Adityapur', 1, '2026-02-10 19:12:21', NULL, NULL),
(15, 2, 'Bagbera-Jamshedpur', 'BGB', 'Bagbera Colony', 'Bagbera', 1, '2026-02-10 19:13:19', NULL, NULL),
(16, 2, 'Chhotagovindpur-Jamshedpur', 'CGP', 'Chhotagovindpur Colony', 'Chhota Govindpur', 1, '2026-02-10 19:13:53', '2026-04-18 07:29:56', NULL),
(17, 2, 'Adityapur-1', 'ADP1', NULL, NULL, 0, '2026-02-10 19:14:29', '2026-04-18 07:19:17', '2026-04-18 07:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT 12,
  `division_id` int(11) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `login_with_otp` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password_created_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `secure_pin` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `role_id`, `division_id`, `user_type`, `login_with_otp`, `email_verified_at`, `password_created_at`, `password`, `secure_pin`, `remember_token`, `created_at`, `updated_at`, `photo`, `is_locked`, `status`) VALUES
(6, 'JSHB ADMIN', 'admin@jshb.com', 'JSHBAD95874564', 8, NULL, 'administration', 0, '2026-03-11 04:58:31', '2026-06-16 09:06:41', '$2y$12$xBWeUvERHSssg1C3LNaAre/xVa6wRqTZ5nFJDKLDjEAwKli9KD.zK', '$2y$12$qhMEqI8vEKKy0AbizMQ0t.670YF4fPSAeugbY90LuiRgGvbNqrg1e', 'rXlWl89d8mwRDMBeAuGe1Kpis7din6lOGMYTzUtaNgk1MaWRUh6wSpJ28RGI', '2026-04-16 04:58:31', '2026-06-29 07:25:42', '1776494411_69e3274bd11c7.jpg', 0, 1),
(11, 'Super Admin User', 'superadmin@jshb.com', 'JSHBSAD5788964', 9, NULL, 'administration', 0, '2026-06-29 06:11:28', '2026-06-29 06:11:28', '$2y$12$xBWeUvERHSssg1C3LNaAre/xVa6wRqTZ5nFJDKLDjEAwKli9KD.zK', NULL, NULL, '2026-06-29 06:11:28', '2026-06-29 07:25:42', NULL, 0, 1),
(12, 'Sunil Kumar', 'sunilkumar@jshb.com', 'JSHBDUM83445', 1, 3, 'engineer', 0, NULL, '2026-06-29 06:41:09', '$2y$12$6ai4fSKEfr1z7OmFoyGeAOo5AFwGBD0EbY.zeJ/ReekqHvJGw2ksK', NULL, NULL, '2026-06-29 06:41:09', '2026-06-30 06:26:35', NULL, 0, 1),
(13, 'Ravi Kumar', 'ravikumar@jshb.com', 'JSHBDHN28913', 6, 4, 'engineer', 0, NULL, '2026-06-29 09:23:05', '$2y$12$PQoWOmKTor4o.e/3WLFvK.OuUE3zao6UgNr5rwCZ69zLFX1.D06jW', NULL, NULL, '2026-06-29 09:23:05', '2026-06-30 06:29:11', NULL, 0, 1),
(14, 'Suraj Kumar', 'surajkumar@jshb.com', 'JSHBHZB41547', 13, 5, 'engineer', 0, NULL, '2026-06-29 09:24:12', '$2y$12$soyy6C7O/myPVdg1VNjjWOnKUOyR4YF9BPnbe.GP2rjkdqXmLeYrq', NULL, NULL, '2026-06-29 09:24:12', '2026-06-30 06:29:00', NULL, 0, 1),
(15, 'Ritik Kumar', 'ritikkumar@jshb.com', 'JSHBRNC61730', 3, 1, 'engineer', 0, NULL, '2026-06-29 09:25:31', '$2y$12$X4Ly01NYUrPbPF.wvb37vuzmI/OoBZ8MKvFBAWmPDLL432Qg0ntcO', NULL, NULL, '2026-06-29 09:25:31', '2026-06-30 06:28:47', NULL, 0, 1),
(16, 'Shiva Kumar', 'shivakumar@jshb.com', 'JSHBJSR43868', 5, 2, 'engineer', 0, NULL, '2026-06-29 09:26:34', '$2y$12$DvuZbkUPOAm621sP4JvvM.ts.Y7/pydhNrR9.9PkUeybflDGp.lhe', NULL, NULL, '2026-06-29 09:26:34', '2026-06-30 06:28:35', NULL, 0, 1),
(17, 'Teeja Kumar', 'teejakumar@jshb.com', 'JSHBRNC18788', 7, 1, 'engineer', 0, NULL, '2026-06-29 09:28:21', '$2y$12$hrsYc4o4RimiL1VFDw9qeuY4/MZ1fUbRjGDfl0A2QG8vYg2jbyC62', NULL, NULL, '2026-06-29 09:28:21', '2026-06-30 06:28:22', NULL, 0, 1),
(18, 'Partik Kumar', 'partikkumar@jshb.com', 'JSHB7914444', 4, NULL, 'administration', 0, NULL, '2026-06-29 09:29:50', '$2y$12$1xpCWhV/0Q9INrPJwcKcl.lnhOQGKwGKCQ.GvIlPskRO9vo/faX/K', NULL, NULL, '2026-06-29 09:29:50', '2026-06-30 06:27:02', NULL, 0, 1),
(20, 'Kiran Kumar', 'kirankumar@jshb.com', 'JSHB9529964', 10, NULL, 'operator', 0, NULL, '2026-06-29 09:32:01', '$2y$12$dbFh2ZTT4bp/OQfGd8zF3OZ1asNaS3K2zdkqIrO/wVezrh1D4I0KC', NULL, NULL, '2026-06-29 09:32:01', '2026-06-30 06:28:04', NULL, 0, 1),
(21, 'Ajit Kumar', 'ajitkumar@jshb.com', 'JSHBRNC81130', 11, 1, 'staff', 0, NULL, '2026-06-29 09:32:57', '$2y$12$4ZvhlrJoq4/Uq7Mc6.0Na./bkwB.jxzYj6Oyzrm6Ruemjw.06Qa3i', NULL, NULL, '2026-06-29 09:32:57', '2026-06-30 06:27:52', NULL, 0, 1),
(22, 'Murthy Kumar', 'murthykumar@jshb.com', 'JSHBJSR69132', 2, 2, 'engineer', 0, NULL, '2026-06-29 09:46:40', '$2y$12$7iStk1DuErdb9G.jUi66dOpH0alKdoTNNSZvmu5eZ32hDTW3Y6/BO', NULL, NULL, '2026-06-29 09:46:40', '2026-06-30 06:27:41', NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `phone` text DEFAULT NULL,
  `phone_hash` varchar(255) DEFAULT NULL,
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

INSERT INTO `user_details` (`id`, `user_id`, `phone`, `phone_hash`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `organization`, `designation`, `additional_info`, `created_at`, `updated_at`, `anniversary_date`, `date_of_birth`, `spouse_name`, `no_of_children`, `boys`, `girls`) VALUES
(1, 12, '9385749835', NULL, NULL, NULL, NULL, NULL, NULL, 'India', NULL, 'Engineer Staff', NULL, '2026-06-29 06:41:09', '2026-06-30 06:25:17', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 13, '9876984768', NULL, NULL, NULL, NULL, NULL, NULL, 'India', NULL, 'AE', NULL, '2026-06-29 09:23:05', '2026-06-30 06:29:12', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 14, '9874554789', NULL, NULL, NULL, NULL, NULL, NULL, 'India', NULL, 'DO', NULL, '2026-06-29 09:24:12', '2026-06-30 06:29:00', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 15, '9843579873', NULL, NULL, NULL, NULL, NULL, NULL, 'India', NULL, 'EO', NULL, '2026-06-29 09:25:31', '2026-06-30 06:28:47', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 16, '6738945673', NULL, NULL, NULL, NULL, NULL, NULL, 'India', NULL, 'EE', NULL, '2026-06-29 09:26:34', '2026-06-30 06:28:35', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 17, '9375436485', NULL, NULL, NULL, NULL, NULL, NULL, 'India', NULL, 'JE', NULL, '2026-06-29 09:28:21', '2026-06-30 06:28:22', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 18, '9345793845', NULL, NULL, NULL, NULL, NULL, NULL, 'India', NULL, 'MD', NULL, '2026-06-29 09:29:50', '2026-06-30 06:27:02', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 20, '9385749874', NULL, NULL, NULL, NULL, NULL, NULL, 'India', NULL, 'OOPS', NULL, '2026-06-29 09:32:01', '2026-06-30 06:28:04', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 21, '9834578345', NULL, NULL, NULL, NULL, NULL, NULL, 'India', NULL, 'STAFF OF JSHB', NULL, '2026-06-29 09:32:57', '2026-06-30 06:27:52', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 22, '9875394587', NULL, NULL, NULL, NULL, NULL, NULL, 'India', NULL, 'Chief Eng.', NULL, '2026-06-29 09:46:40', '2026-06-30 06:27:41', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allottees`
--
ALTER TABLE `allottees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `allottees_contact_details`
--
ALTER TABLE `allottees_contact_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allottee_documents`
--
ALTER TABLE `allottee_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_id` (`document_id`),
  ADD KEY `allottee_id` (`allottee_id`);

--
-- Indexes for table `allottee_emi_accounts`
--
ALTER TABLE `allottee_emi_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allottee_emi_demands`
--
ALTER TABLE `allottee_emi_demands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_allottee_id` (`allottee_id`),
  ADD KEY `idx_emi_account_id` (`emi_account_id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_due_date` (`due_date`);

--
-- Indexes for table `allottee_emi_ledger`
--
ALTER TABLE `allottee_emi_ledger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allottee_emi_schedules`
--
ALTER TABLE `allottee_emi_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allottee_generated_documents`
--
ALTER TABLE `allottee_generated_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `allottee_generated_documents_allottee_id_document_type_index` (`allottee_id`,`document_type`);

--
-- Indexes for table `allottee_ledger`
--
ALTER TABLE `allottee_ledger`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_allottee_id` (`allottee_id`),
  ADD KEY `idx_emi_account_id` (`emi_account_id`),
  ADD KEY `idx_demand_id` (`demand_id`),
  ADD KEY `idx_payment_id` (`payment_id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_transaction_date` (`transaction_date`),
  ADD KEY `idx_transaction_type` (`transaction_type`);

--
-- Indexes for table `allottee_nominee_bank_details`
--
ALTER TABLE `allottee_nominee_bank_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_allottee_id` (`allottee_id`);

--
-- Indexes for table `allottee_payment_orders`
--
ALTER TABLE `allottee_payment_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allottee_process_steps`
--
ALTER TABLE `allottee_process_steps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_allottee_step` (`allottee_id`,`menu_key`,`sub_menu_key`,`step_no`),
  ADD UNIQUE KEY `uk_allottee_process` (`allottee_id`,`menu_key`,`sub_menu_key`),
  ADD KEY `idx_allottee` (`allottee_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_menu` (`menu_key`),
  ADD KEY `idx_submenu` (`sub_menu_key`),
  ADD KEY `idx_step` (`step_no`),
  ADD KEY `idx_allottee_status` (`allottee_id`,`status`),
  ADD KEY `idx_menu_order` (`allottee_id`,`menu_order`,`step_order`);

--
-- Indexes for table `allottee_property_fin_details`
--
ALTER TABLE `allottee_property_fin_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_allottee_id` (`allottee_id`);

--
-- Indexes for table `allottee_site_verifications`
--
ALTER TABLE `allottee_site_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `allottee_site_verifications_allottee_id_foreign` (`allottee_id`);

--
-- Indexes for table `allottee_step_durations`
--
ALTER TABLE `allottee_step_durations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_allottee` (`allottee_id`),
  ADD KEY `idx_step` (`step_no`);

--
-- Indexes for table `allottee_transactions`
--
ALTER TABLE `allottee_transactions`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_master`
--
ALTER TABLE `document_master`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `document_key` (`document_key`);

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
-- Indexes for table `payment_penalty_rules`
--
ALTER TABLE `payment_penalty_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_types`
--
ALTER TABLE `post_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_category`
--
ALTER TABLE `property_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_sub_type`
--
ALTER TABLE `property_sub_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_type`
--
ALTER TABLE `property_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quarter_type`
--
ALTER TABLE `quarter_type`
  ADD PRIMARY KEY (`quarter_id`);

--
-- Indexes for table `quota_types`
--
ALTER TABLE `quota_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`),
  ADD UNIQUE KEY `roles_short_name_unique` (`short_name`);

--
-- Indexes for table `schemes`
--
ALTER TABLE `schemes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `scheme_code` (`scheme_code`);

--
-- Indexes for table `scheme_blocks`
--
ALTER TABLE `scheme_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scheme_id` (`scheme_id`);

--
-- Indexes for table `scheme_financials`
--
ALTER TABLE `scheme_financials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scheme_quarter_fees`
--
ALTER TABLE `scheme_quarter_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scheme_unit_quotas`
--
ALTER TABLE `scheme_unit_quotas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_divisions`
--
ALTER TABLE `sub_divisions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

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
-- AUTO_INCREMENT for table `allottees`
--
ALTER TABLE `allottees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `allottees_contact_details`
--
ALTER TABLE `allottees_contact_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `allottee_documents`
--
ALTER TABLE `allottee_documents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allottee_emi_accounts`
--
ALTER TABLE `allottee_emi_accounts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `allottee_emi_demands`
--
ALTER TABLE `allottee_emi_demands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `allottee_emi_ledger`
--
ALTER TABLE `allottee_emi_ledger`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allottee_emi_schedules`
--
ALTER TABLE `allottee_emi_schedules`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=573;

--
-- AUTO_INCREMENT for table `allottee_generated_documents`
--
ALTER TABLE `allottee_generated_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `allottee_ledger`
--
ALTER TABLE `allottee_ledger`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allottee_nominee_bank_details`
--
ALTER TABLE `allottee_nominee_bank_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allottee_payment_orders`
--
ALTER TABLE `allottee_payment_orders`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `allottee_process_steps`
--
ALTER TABLE `allottee_process_steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=744;

--
-- AUTO_INCREMENT for table `allottee_property_fin_details`
--
ALTER TABLE `allottee_property_fin_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allottee_site_verifications`
--
ALTER TABLE `allottee_site_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `allottee_step_durations`
--
ALTER TABLE `allottee_step_durations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allottee_transactions`
--
ALTER TABLE `allottee_transactions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=805;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `document_master`
--
ALTER TABLE `document_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=530;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `otp_logs`
--
ALTER TABLE `otp_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `payment_penalty_rules`
--
ALTER TABLE `payment_penalty_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `post_types`
--
ALTER TABLE `post_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `property_category`
--
ALTER TABLE `property_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `property_sub_type`
--
ALTER TABLE `property_sub_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `property_type`
--
ALTER TABLE `property_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quarter_type`
--
ALTER TABLE `quarter_type`
  MODIFY `quarter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quota_types`
--
ALTER TABLE `quota_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `schemes`
--
ALTER TABLE `schemes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `scheme_blocks`
--
ALTER TABLE `scheme_blocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `scheme_financials`
--
ALTER TABLE `scheme_financials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `scheme_quarter_fees`
--
ALTER TABLE `scheme_quarter_fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `scheme_unit_quotas`
--
ALTER TABLE `scheme_unit_quotas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `sub_divisions`
--
ALTER TABLE `sub_divisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allottee_site_verifications`
--
ALTER TABLE `allottee_site_verifications`
  ADD CONSTRAINT `allottee_site_verifications_allottee_id_foreign` FOREIGN KEY (`allottee_id`) REFERENCES `allottees` (`id`) ON DELETE CASCADE;

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
