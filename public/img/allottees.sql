-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2026 at 02:40 PM
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
  `current_step` int(11) DEFAULT 1,
  `is_step_completed` int(11) NOT NULL DEFAULT 0,
  `allottee_document_path` varchar(255) DEFAULT NULL,
  `payment_amount` decimal(14,2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `allottee_create_date` date DEFAULT NULL,
  `payment_mode` varchar(100) DEFAULT NULL,
  `payment_utr_no` varchar(255) DEFAULT NULL,
  `payment_option` varchar(20) DEFAULT NULL,
  `payment_option_selected_at` timestamp NULL DEFAULT NULL,
  `remaining_amount` decimal(14,2) DEFAULT NULL,
  `emi_months` smallint(5) UNSIGNED NOT NULL DEFAULT 60,
  `emi_monthly_amount` decimal(14,2) DEFAULT NULL,
  `final_calculation_generated` tinyint(1) NOT NULL DEFAULT 0,
  `recalculation_allowed` tinyint(1) NOT NULL DEFAULT 1,
  `create_ip_address` varchar(100) DEFAULT NULL,
  `payment_receipt_path` varchar(500) DEFAULT NULL,
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

INSERT INTO `allottees` (`id`, `division_id`, `subdivision_id`, `pcategory_id`, `property_type_id`, `p_sub_type_id`, `quarter_id`, `username`, `password`, `scheme_id`, `application_no`, `application_day`, `application_month`, `application_year`, `allotment_no`, `allotment_day`, `allotment_month`, `allotment_year`, `property_number`, `prefix`, `allottee_name`, `allottee_middle_name`, `allottee_surname`, `allottee_prefix_hindi`, `allottee_name_hindi`, `allottee_middle_hindi`, `allottee_surname_hindi`, `allottee_relation_type`, `relation_name`, `marital_status`, `allottee_gender`, `pan_card_number`, `aadhar_card_number`, `allottee_category`, `allottee_religion`, `allottee_nationality`, `date_of_birth_day`, `date_of_birth_month`, `date_of_birth_year`, `allottee_remarks`, `current_step`, `is_step_completed`, `allottee_document_path`, `payment_amount`, `payment_date`, `allottee_create_date`, `payment_mode`, `payment_utr_no`, `payment_option`, `payment_option_selected_at`, `remaining_amount`, `emi_months`, `emi_monthly_amount`, `final_calculation_generated`, `recalculation_allowed`, `create_ip_address`, `payment_receipt_path`, `update_ip_address`, `updated_by`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 15, 1, 2, 6, 4, 'JSR2004HRM10765', '$2y$12$1lLgk93Q0LsFBl0h7hKfQ.l5aUe2WosTEufFsROYr3zZdO9FJbdq6', 6, '3838844', 15, '09', '2004', '23434/2004', 12, '09', '2004', NULL, 'Shri', 'Krishna', NULL, 'Yadav', NULL, NULL, NULL, NULL, 'Father', 'Rai Surendra Prasad', 'Unmarried', 'Male', NULL, NULL, 'General', 'Hindu', 'Indian', 10, '09', '1973', NULL, 2, 0, NULL, NULL, NULL, '2026-05-08', NULL, NULL, NULL, NULL, NULL, 60, NULL, 0, 1, '127.0.0.1', NULL, NULL, NULL, 6, '2026-05-08 12:23:40', '2026-05-08 12:23:40', NULL),
(2, 2, 15, 1, 2, 6, 4, 'JSR2016HRM70952', '$2y$12$C2cBpPicE1TaT5yqTOgq1ex1Jo2hBARRbUqja0lzJAEtmTnG2mPQy', 6, '3838844', 13, '11', '2016', '567456/2016', 23, '11', '2016', NULL, 'Shri', 'Prince', NULL, 'Yadav', NULL, NULL, NULL, NULL, 'Father', 'Kameshwar Prasad', 'Married', 'Male', 'ABCDE123F', '985479357893', 'General', 'Hindu', 'Indian', 4, '04', '1960', NULL, 2, 0, NULL, NULL, NULL, '2026-05-08', NULL, NULL, NULL, NULL, NULL, 60, NULL, 0, 1, '127.0.0.1', NULL, NULL, NULL, 6, '2026-05-08 12:45:08', '2026-05-08 12:45:08', NULL),
(3, 2, 15, 1, 2, 6, 4, 'JSR2013HRM27169', '$2y$12$Ll65iXRavpSp/N.m4X92BeLK.ONARy8OGvwS/i3cpSsUYPO7jkhSO', 6, '3838844', 13, '09', '2011', '3454/2011', 13, '11', '2013', NULL, 'Shri', 'Prince AA', NULL, NULL, NULL, NULL, NULL, NULL, 'Father', 'Rai Surendra Prasad', 'Unmarried', 'Male', 'ABCDE123F', '985479357893', 'General', 'Christian', 'Indian', 12, '09', '2012', NULL, 2, 0, NULL, NULL, NULL, '2026-05-09', NULL, NULL, NULL, NULL, NULL, 60, NULL, 0, 1, '127.0.0.1', NULL, NULL, NULL, 6, '2026-05-09 10:53:12', '2026-05-09 10:53:12', NULL),
(4, 2, 15, 1, 2, 6, 4, 'JSR2012HRM15632', '$2y$12$akf9AW7luxGjeTQzVVEMEe9yajTjtvfr.wICl/ytjBTtkN1HSC9yq', 6, '3838844', 15, '11', '2012', '23434/2012', 13, '12', '2012', NULL, 'Shri', 'Prince', 'Kumar', 'Yadav', NULL, NULL, NULL, NULL, 'Father', 'KK Khanti', 'Married', 'Male', 'ABCDE123F', '985479357893', 'General', 'Hindu', 'Indian', 17, '11', '1954', NULL, 1, 0, NULL, 345345.00, '2026-05-06', NULL, 'UPI', '5345435', NULL, NULL, NULL, 60, NULL, 0, 1, '127.0.0.1', 'allottee_payments/bw6Q9467bYaslGWuoRHEMa6fnkNR0X2Wws9ROjB3.png', '127.0.0.1', 6, 6, '2026-05-11 05:33:05', '2026-05-11 05:35:14', NULL),
(5, NULL, NULL, NULL, NULL, NULL, NULL, 'DRAFT_53FONPWE82WE', '$2y$12$pnjpUZIeI2TvifiSzZakruSdOLzPWojdhwtxOQOAMw12WuD4hBafa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, NULL, 324234.00, '2026-05-07', NULL, 'UPI', NULL, 'emi_60', '2026-05-11 10:33:58', 972702.00, 60, 16211.70, 0, 1, '127.0.0.1', 'allottee_payments/p6ZiJpzJTuadpkilyDWpTzmH0IxGpxM8sYyTEiD5.png', '127.0.0.1', 6, 6, '2026-05-11 09:26:01', '2026-05-11 10:34:07', NULL);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allottees`
--
ALTER TABLE `allottees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
