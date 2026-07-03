-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2026 at 12:20 PM
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
  `payment_reference` varchar(255) DEFAULT NULL,
  `create_ip_address` varchar(100) DEFAULT NULL,
  `payment_receipt_path` varchar(500) DEFAULT NULL,
  `update_ip_address` varchar(100) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `department_code` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `engineer_details`
--

CREATE TABLE `engineer_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `current_organization_id` bigint(20) UNSIGNED NOT NULL,
  `parent_organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `district_id` bigint(20) UNSIGNED NOT NULL,
  `block_id` bigint(20) UNSIGNED NOT NULL,
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `post_type_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_hindi_name` varchar(255) DEFAULT NULL,
  `state_government_engineer_id` text NOT NULL,
  `state_government_engineer_id_hash` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `anniversary_date` date DEFAULT NULL,
  `spouse_name` varchar(255) DEFAULT NULL,
  `no_of_children` int(10) UNSIGNED DEFAULT NULL,
  `aadhar_no` text DEFAULT NULL,
  `aadhar_no_hash` varchar(255) DEFAULT NULL,
  `pan_card_no` text DEFAULT NULL,
  `pan_card_no_hash` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
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

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_code` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `pin_code` varchar(20) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `locality` varchar(255) DEFAULT NULL,
  `police_station` varchar(255) DEFAULT NULL,
  `post_office` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `parent_organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `district_wise_posting` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `parent_organizations`
--

CREATE TABLE `parent_organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_code` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `pin_code` varchar(20) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `locality` varchar(255) DEFAULT NULL,
  `police_station` varchar(255) DEFAULT NULL,
  `post_office` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `quota_types`
--

CREATE TABLE `quota_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `scheme_financials`
--

CREATE TABLE `scheme_financials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scheme_id` bigint(20) UNSIGNED NOT NULL,
  `property_total_cost` decimal(15,2) NOT NULL,
  `down_payment_percentage` decimal(5,2) NOT NULL,
  `down_payment_amount` decimal(15,2) NOT NULL,
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
  `secure_pin` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `allottee_emi_ledger`
--
ALTER TABLE `allottee_emi_ledger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allottee_nominee_bank_details`
--
ALTER TABLE `allottee_nominee_bank_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_allottee_id` (`allottee_id`);

--
-- Indexes for table `allottee_property_fin_details`
--
ALTER TABLE `allottee_property_fin_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_allottee_id` (`allottee_id`);

--
-- Indexes for table `allottee_step_durations`
--
ALTER TABLE `allottee_step_durations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_allottee` (`allottee_id`),
  ADD KEY `idx_step` (`step_no`);

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
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`),
  ADD UNIQUE KEY `departments_department_code_unique` (`department_code`);

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
-- Indexes for table `engineer_details`
--
ALTER TABLE `engineer_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `engineer_details_govt_id_hash_unique` (`state_government_engineer_id_hash`),
  ADD KEY `engineer_details_user_id_foreign` (`user_id`),
  ADD KEY `engineer_details_current_organization_id_foreign` (`current_organization_id`),
  ADD KEY `engineer_details_parent_organization_id_foreign` (`parent_organization_id`),
  ADD KEY `engineer_details_department_id_foreign` (`department_id`);

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
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizations_parent_organization_id_foreign` (`parent_organization_id`);

--
-- Indexes for table `otp_logs`
--
ALTER TABLE `otp_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otp_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `parent_organizations`
--
ALTER TABLE `parent_organizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parent_organizations_display_code_unique` (`display_code`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
-- AUTO_INCREMENT for table `allottees`
--
ALTER TABLE `allottees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allottees_contact_details`
--
ALTER TABLE `allottees_contact_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allottee_documents`
--
ALTER TABLE `allottee_documents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allottee_emi_ledger`
--
ALTER TABLE `allottee_emi_ledger`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allottee_nominee_bank_details`
--
ALTER TABLE `allottee_nominee_bank_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allottee_property_fin_details`
--
ALTER TABLE `allottee_property_fin_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allottee_step_durations`
--
ALTER TABLE `allottee_step_durations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_master`
--
ALTER TABLE `document_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `engineer_details`
--
ALTER TABLE `engineer_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_logs`
--
ALTER TABLE `otp_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parent_organizations`
--
ALTER TABLE `parent_organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_types`
--
ALTER TABLE `post_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_category`
--
ALTER TABLE `property_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_sub_type`
--
ALTER TABLE `property_sub_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_type`
--
ALTER TABLE `property_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quarter_type`
--
ALTER TABLE `quarter_type`
  MODIFY `quarter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quota_types`
--
ALTER TABLE `quota_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schemes`
--
ALTER TABLE `schemes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheme_blocks`
--
ALTER TABLE `scheme_blocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheme_financials`
--
ALTER TABLE `scheme_financials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheme_quarter_fees`
--
ALTER TABLE `scheme_quarter_fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheme_unit_quotas`
--
ALTER TABLE `scheme_unit_quotas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_divisions`
--
ALTER TABLE `sub_divisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `engineer_details`
--
ALTER TABLE `engineer_details`
  ADD CONSTRAINT `engineer_details_current_organization_id_foreign` FOREIGN KEY (`current_organization_id`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `engineer_details_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `engineer_details_parent_organization_id_foreign` FOREIGN KEY (`parent_organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `engineer_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD CONSTRAINT `login_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_parent_organization_id_foreign` FOREIGN KEY (`parent_organization_id`) REFERENCES `parent_organizations` (`id`) ON DELETE SET NULL;

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
