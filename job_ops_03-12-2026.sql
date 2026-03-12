-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 12, 2026 at 07:13 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job_ops`
--
CREATE DATABASE IF NOT EXISTS `job_ops` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `job_ops`;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` bigint UNSIGNED NOT NULL,
  `group_id` int UNSIGNED DEFAULT NULL,
  `asset_tag` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `property_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `brand_model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `serial_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `operating_system` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_license_key` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_license_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_license_expiry` date DEFAULT NULL,
  `os_last_updated` date DEFAULT NULL,
  `os_is_updated` tinyint(1) NOT NULL DEFAULT '0',
  `software_installed` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `software_license` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `software_list` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `section_id` int DEFAULT NULL,
  `assigned_to` int DEFAULT NULL,
  `assigned_unit_id` int DEFAULT NULL,
  `date_acquired` date DEFAULT NULL,
  `acquisition_cost` decimal(15,2) DEFAULT NULL,
  `depreciation_cost` decimal(15,2) DEFAULT NULL,
  `warranty_end` date DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Active',
  `lifecycle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `supplier` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `po_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `procurement_mode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fund_source` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `asset_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`asset_id`, `group_id`, `asset_tag`, `property_no`, `brand_model`, `serial_number`, `category`, `operating_system`, `os_license_key`, `os_license_type`, `os_license_expiry`, `os_last_updated`, `os_is_updated`, `software_installed`, `software_license`, `software_list`, `section_id`, `assigned_to`, `assigned_unit_id`, `date_acquired`, `acquisition_cost`, `depreciation_cost`, `warranty_end`, `status`, `lifecycle`, `supplier`, `po_number`, `invoice_number`, `procurement_mode`, `fund_source`, `asset_image`, `created_at`, `updated_at`) VALUES
(168, 62, 'ASSET-009', 'PN-2024-001', 'Dell Latitude 5520', 'DOBJ3SP001251001CA3000', 'Computer', 'Windows 11 Home', '1234-5678-9101', 'Freeware', '2026-02-27', '2026-02-28', 1, NULL, NULL, '[{\"name\":\"Microsoft Word\",\"license_type\":\"Subscription\",\"license_expiry\":\"2026-03-07\",\"last_updated\":\"2026-02-27\",\"is_updated\":\"0\",\"notes\":\"nice\"}]', 2, 3, 1, '2026-02-27', 50000.00, 5000.00, '2026-03-07', 'Active', '2 yrs', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-26 22:13:48', '2026-02-27 01:02:15'),
(170, 63, 'ASSET-005', 'PN-2024-001', 'All-in-One 23.6 - Acer', 'DOBJ3SP001251001CA3000', 'Printer', 'Windows 11 Home', '1234-5678-9101', 'Subscription', '2026-03-02', '2026-03-03', 1, NULL, NULL, '[{\"name\":\"Adobe Premiere Pro\",\"license_type\":\"\",\"license_expiry\":\"\",\"last_updated\":\"\",\"is_updated\":\"0\",\"notes\":\"2 yrs \"}]', 3, 3, 5, '2026-03-02', 50000.00, 2500.00, '2026-03-03', 'Active', '2 yrs', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-01 22:29:35', '2026-03-01 22:33:41');

-- --------------------------------------------------------

--
-- Table structure for table `asset_disposals`
--

CREATE TABLE `asset_disposals` (
  `disposal_id` bigint UNSIGNED NOT NULL,
  `asset_id` bigint UNSIGNED NOT NULL,
  `disposal_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `disposal_date` date DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `condition_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `disposal_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_groups`
--

CREATE TABLE `asset_groups` (
  `group_id` int UNSIGNED NOT NULL,
  `group_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `quantity` int NOT NULL DEFAULT '1',
  `tag_prefix` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'e.g. IT-PC → generates IT-PC-001, IT-PC-002 ...',
  `section_id` int UNSIGNED DEFAULT NULL,
  `assigned_unit_id` int UNSIGNED DEFAULT NULL,
  `assigned_to` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_acquired` date DEFAULT NULL,
  `acquisition_cost` decimal(15,2) DEFAULT NULL,
  `depreciation_cost` decimal(15,2) DEFAULT NULL,
  `warranty_end` date DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `lifecycle` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asset_groups`
--

INSERT INTO `asset_groups` (`group_id`, `group_name`, `group_code`, `category`, `description`, `quantity`, `tag_prefix`, `section_id`, `assigned_unit_id`, `assigned_to`, `date_acquired`, `acquisition_cost`, `depreciation_cost`, `warranty_end`, `status`, `lifecycle`, `created_at`, `updated_at`) VALUES
(62, 'IT Desktop Computers Batch 2026', 'GRP-IT-2026-01', 'IT Equipment', 'nice', 2, 'IT-PC', NULL, 5, '8', NULL, 50000.00, 3750.00, NULL, 'Active', '3 years', '2026-02-27 09:02:15', '2026-03-02 06:29:35'),
(63, 'CS Desktop Computers Batch 2026', 'GRP-IT-2026-02', 'IT Equipment', 'checking', 1, 'CS-PC', NULL, 5, '8', NULL, 50000.00, 2500.00, NULL, 'Active', '4', '2026-03-02 06:33:41', '2026-03-02 06:33:41');

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `building_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`building_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Academic Building 1', '2026-02-19 01:00:19', '2026-02-19 01:00:19'),
(2, 'Academic Building 2', '2026-02-19 01:01:02', '2026-02-19 01:01:02'),
(3, 'Academic Building 3', '2026-02-19 05:42:29', '2026-02-19 05:42:29'),
(4, 'Academic Building 4', '2026-02-19 05:42:36', '2026-02-19 05:42:36'),
(5, 'Academic Building 5', '2026-02-19 05:42:44', '2026-02-19 05:42:44'),
(6, 'Administrative Building 1', '2026-02-19 21:44:45', '2026-02-19 21:44:45');

-- --------------------------------------------------------

--
-- Table structure for table `expertise`
--

CREATE TABLE `expertise` (
  `expertise_id` bigint UNSIGNED NOT NULL,
  `skill` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expertise`
--

INSERT INTO `expertise` (`expertise_id`, `skill`, `description`, `section_id`, `created_at`, `updated_at`) VALUES
(1, 'Web Development', 'Designing and developing  web applications', 1, '2026-02-19 01:55:12', '2026-02-19 15:45:59'),
(2, 'SIAS Administration', 'Management of SIAS accounts.', 1, '2026-02-19 01:55:42', '2026-02-19 15:45:29'),
(3, 'Data Analytics & Reporting', 'Is about collecting, processing, and interpreting raw data to create actionable insights, structured reports, and visualizations', 1, '2026-02-19 01:56:41', '2026-02-19 01:56:41'),
(4, 'Network Administration', 'Designing and managing Local Area Networks (LAN) and Wide Area Networks (WAN) across multiple campus buildings', 2, '2026-02-19 01:57:47', '2026-02-19 01:57:47'),
(5, 'CCTV Management', 'Involves the strategic planning, installation, operation, and maintenance of surveillance systems to monitor, record, and analyze video footage for security and safety purposes.', 2, '2026-02-19 01:58:32', '2026-02-19 01:58:32'),
(6, 'Telephone Management', 'Involves controlling, monitoring, and optimizing an organization’s incoming and outgoing voice communications to improve efficiency, customer service, and cost control.', 2, '2026-02-19 01:59:51', '2026-02-19 01:59:51'),
(7, 'Computer Assembly and Disassembly', 'The systematic process of building, upgrading, and breaking down a PC by installing or removing internal components—such as the CPU, motherboard, RAM, and power supply—to repair, maintain, or customize computer systems.', 3, '2026-02-19 02:01:04', '2026-02-19 02:01:04'),
(8, 'Printer and Scanner Repair Maintenance', 'Involves cleaning, repairing, and optimizing devices to ensure long-term functionality, print quality, and to prevent costly breakdowns.', 3, '2026-02-19 02:01:56', '2026-02-19 02:01:56'),
(9, 'Operating System (OS) Installation', 'The crucial process of loading, configuring, and initializing core system software onto a computer\'s storage drive to enable hardware, manage memory, and run applications.', 3, '2026-02-19 02:03:15', '2026-02-19 02:03:15'),
(10, 'Google Account Management', 'Management of Google Workspace accounts unique to CSPC (creation, reset, reactivation).', 1, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(11, 'Office365 Account Management', 'Management of Microsoft Office 365 accounts (creation, reset, reactivation).', 1, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(12, 'LeOnS Administration', 'Administration of the LeOnS Learning Management System.', 1, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(13, 'SPIMS Administration', 'Administration of the SPIMS platform.', 1, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(14, 'Koha Library System Administration', 'Administration of the Koha integrated library system.', 1, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(15, 'UniSAP Administration', 'Administration of the UniSAP Student and Employee Profiling system.', 1, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(16, 'HRIS Administration', 'Administration of the Human Resource Information System.', 1, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(17, 'FMS Administration', 'Administration of the Facility Management System.', 1, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(18, 'Queueing System Administration', 'Administration of the queueing system.', 1, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(19, 'Travel Order System Administration', 'Administration of the Travel Order system.', 1, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(20, 'RMS Administration', 'Administration of the RMS platform.', 1, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(21, 'Peripheral Device Support', 'Support and troubleshooting for peripheral devices (keyboards, mice, monitors, cables, adapters).', 3, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(22, 'Hardware Diagnostics & Troubleshooting', 'Diagnosing and resolving hardware issues including power failures, overheating, display problems.', 3, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(23, 'Software Troubleshooting & Recovery', 'Resolving software crashes, driver issues, application errors, and performance problems.', 3, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(24, 'Malware Removal & Security', 'Detecting and removing malware, viruses, and securing systems.', 3, '2026-02-22 06:41:25', '2026-02-22 06:41:25'),
(25, 'Data Backup & Recovery', 'Data backup, restoration, and recovery from storage failures.', 3, '2026-02-22 06:41:25', '2026-02-22 06:41:25');

-- --------------------------------------------------------

--
-- Table structure for table `expertise_signal_map`
--

CREATE TABLE `expertise_signal_map` (
  `id` bigint UNSIGNED NOT NULL,
  `expertise_id` bigint UNSIGNED NOT NULL,
  `signal_type` enum('equipment','request_type','platform','action','issue_type') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `signal_value` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expertise_signal_map`
--

INSERT INTO `expertise_signal_map` (`id`, `expertise_id`, `signal_type`, `signal_value`, `created_at`) VALUES
(1, 4, 'equipment', 2, '2026-02-22 06:46:33'),
(2, 5, 'equipment', 3, '2026-02-22 06:46:33'),
(3, 6, 'equipment', 1, '2026-02-22 06:46:33'),
(4, 10, 'platform', 2, '2026-02-22 06:46:33'),
(5, 11, 'platform', 1, '2026-02-22 06:46:33'),
(6, 2, 'platform', 3, '2026-02-22 06:46:33'),
(7, 2, 'platform', 13, '2026-02-22 06:46:33'),
(8, 12, 'platform', 4, '2026-02-22 06:46:33'),
(9, 13, 'platform', 5, '2026-02-22 06:46:33'),
(10, 13, 'platform', 14, '2026-02-22 06:46:33'),
(11, 14, 'platform', 6, '2026-02-22 06:46:33'),
(12, 14, 'platform', 15, '2026-02-22 06:46:33'),
(13, 15, 'platform', 7, '2026-02-22 06:46:33'),
(14, 16, 'platform', 10, '2026-02-22 06:46:33'),
(15, 17, 'platform', 8, '2026-02-22 06:46:33'),
(16, 18, 'platform', 11, '2026-02-22 06:46:33'),
(17, 19, 'platform', 12, '2026-02-22 06:46:33'),
(18, 20, 'platform', 9, '2026-02-22 06:46:33'),
(19, 7, 'equipment', 4, '2026-02-22 06:46:33'),
(20, 7, 'equipment', 5, '2026-02-22 06:46:33'),
(21, 7, 'equipment', 6, '2026-02-22 06:46:33'),
(22, 8, 'equipment', 7, '2026-02-22 06:46:33'),
(23, 8, 'equipment', 8, '2026-02-22 06:46:33'),
(24, 21, 'equipment', 9, '2026-02-22 06:46:33'),
(25, 7, 'request_type', 4, '2026-02-22 06:46:33'),
(26, 7, 'request_type', 8, '2026-02-22 06:46:33'),
(27, 8, 'request_type', 3, '2026-02-22 06:46:33'),
(28, 8, 'request_type', 5, '2026-02-22 06:46:33'),
(29, 9, 'request_type', 4, '2026-02-22 06:46:33'),
(30, 22, 'request_type', 3, '2026-02-22 06:46:33'),
(31, 22, 'request_type', 6, '2026-02-22 06:46:33'),
(32, 22, 'request_type', 7, '2026-02-22 06:46:33'),
(33, 22, 'issue_type', 1, '2026-02-22 06:46:33'),
(34, 22, 'issue_type', 2, '2026-02-22 06:46:33'),
(35, 22, 'issue_type', 4, '2026-02-22 06:46:33'),
(36, 22, 'issue_type', 5, '2026-02-22 06:46:33'),
(37, 22, 'issue_type', 6, '2026-02-22 06:46:33'),
(38, 22, 'issue_type', 9, '2026-02-22 06:46:33'),
(39, 22, 'issue_type', 10, '2026-02-22 06:46:33'),
(40, 22, 'issue_type', 13, '2026-02-22 06:46:33'),
(41, 8, 'issue_type', 3, '2026-02-22 06:46:33'),
(42, 8, 'issue_type', 8, '2026-02-22 06:46:33'),
(43, 8, 'issue_type', 12, '2026-02-22 06:46:33'),
(44, 21, 'issue_type', 7, '2026-02-22 06:46:33'),
(45, 21, 'issue_type', 11, '2026-02-22 06:46:33'),
(46, 23, 'issue_type', 14, '2026-02-22 06:46:33'),
(47, 23, 'issue_type', 15, '2026-02-22 06:46:33'),
(48, 23, 'issue_type', 16, '2026-02-22 06:46:33'),
(49, 23, 'issue_type', 18, '2026-02-22 06:46:33'),
(50, 23, 'issue_type', 19, '2026-02-22 06:46:33'),
(51, 23, 'issue_type', 20, '2026-02-22 06:46:33'),
(52, 23, 'issue_type', 22, '2026-02-22 06:46:33'),
(53, 23, 'issue_type', 23, '2026-02-22 06:46:33'),
(54, 9, 'issue_type', 14, '2026-02-22 06:46:33'),
(55, 9, 'issue_type', 16, '2026-02-22 06:46:33'),
(56, 24, 'issue_type', 21, '2026-02-22 06:46:33'),
(57, 25, 'issue_type', 17, '2026-02-22 06:46:33'),
(58, 2, 'request_type', 1, '2026-03-01 08:52:09'),
(59, 10, 'request_type', 1, '2026-03-01 08:52:09'),
(60, 11, 'request_type', 1, '2026-03-01 08:52:09'),
(61, 12, 'request_type', 1, '2026-03-01 08:52:09'),
(62, 13, 'request_type', 1, '2026-03-01 08:52:09'),
(63, 14, 'request_type', 1, '2026-03-01 08:52:09'),
(64, 15, 'request_type', 1, '2026-03-01 08:52:09'),
(65, 1, 'request_type', 2, '2026-03-01 08:52:09'),
(66, 2, 'request_type', 2, '2026-03-01 08:52:09'),
(67, 3, 'request_type', 2, '2026-03-01 08:52:09'),
(68, 13, 'request_type', 2, '2026-03-01 08:52:09'),
(69, 14, 'request_type', 2, '2026-03-01 08:52:09'),
(70, 16, 'request_type', 2, '2026-03-01 08:52:09'),
(71, 17, 'request_type', 2, '2026-03-01 08:52:09'),
(72, 18, 'request_type', 2, '2026-03-01 08:52:09'),
(73, 19, 'request_type', 2, '2026-03-01 08:52:09'),
(74, 20, 'request_type', 2, '2026-03-01 08:52:09'),
(75, 2, 'action', 1, '2026-03-01 08:57:51'),
(76, 2, 'action', 2, '2026-03-01 08:57:51'),
(77, 2, 'action', 3, '2026-03-01 08:57:51'),
(78, 10, 'action', 1, '2026-03-01 08:57:51'),
(79, 10, 'action', 2, '2026-03-01 08:57:51'),
(80, 10, 'action', 3, '2026-03-01 08:57:51'),
(81, 11, 'action', 1, '2026-03-01 08:57:51'),
(82, 11, 'action', 2, '2026-03-01 08:57:51'),
(83, 11, 'action', 3, '2026-03-01 08:57:51'),
(84, 12, 'action', 1, '2026-03-01 08:57:51'),
(85, 12, 'action', 2, '2026-03-01 08:57:51'),
(86, 12, 'action', 3, '2026-03-01 08:57:51'),
(87, 13, 'action', 1, '2026-03-01 08:57:51'),
(88, 13, 'action', 2, '2026-03-01 08:57:51'),
(89, 13, 'action', 3, '2026-03-01 08:57:51'),
(90, 14, 'action', 1, '2026-03-01 08:57:51'),
(91, 14, 'action', 2, '2026-03-01 08:57:51'),
(92, 14, 'action', 3, '2026-03-01 08:57:51'),
(93, 15, 'action', 1, '2026-03-01 08:57:51'),
(94, 15, 'action', 2, '2026-03-01 08:57:51'),
(95, 15, 'action', 3, '2026-03-01 08:57:51'),
(96, 1, 'action', 4, '2026-03-01 08:57:51'),
(97, 1, 'action', 5, '2026-03-01 08:57:51'),
(98, 1, 'action', 6, '2026-03-01 08:57:51'),
(99, 2, 'action', 4, '2026-03-01 08:57:51'),
(100, 2, 'action', 5, '2026-03-01 08:57:51'),
(101, 2, 'action', 6, '2026-03-01 08:57:51'),
(102, 3, 'action', 4, '2026-03-01 08:57:51'),
(103, 3, 'action', 5, '2026-03-01 08:57:51'),
(104, 3, 'action', 6, '2026-03-01 08:57:51'),
(105, 16, 'action', 4, '2026-03-01 08:57:51'),
(106, 16, 'action', 5, '2026-03-01 08:57:51'),
(107, 16, 'action', 6, '2026-03-01 08:57:51'),
(108, 17, 'action', 4, '2026-03-01 08:57:51'),
(109, 17, 'action', 5, '2026-03-01 08:57:51'),
(110, 17, 'action', 6, '2026-03-01 08:57:51'),
(111, 18, 'action', 4, '2026-03-01 08:57:51'),
(112, 18, 'action', 5, '2026-03-01 08:57:51'),
(113, 18, 'action', 6, '2026-03-01 08:57:51'),
(114, 19, 'action', 4, '2026-03-01 08:57:51'),
(115, 19, 'action', 5, '2026-03-01 08:57:51'),
(116, 19, 'action', 6, '2026-03-01 08:57:51'),
(117, 20, 'action', 4, '2026-03-01 08:57:51'),
(118, 20, 'action', 5, '2026-03-01 08:57:51'),
(119, 20, 'action', 6, '2026-03-01 08:57:51'),
(120, 4, 'action', 7, '2026-03-01 08:58:06'),
(121, 4, 'action', 8, '2026-03-01 08:58:06'),
(122, 5, 'action', 7, '2026-03-01 08:58:06'),
(123, 5, 'action', 8, '2026-03-01 08:58:06'),
(124, 6, 'action', 7, '2026-03-01 08:58:06'),
(125, 6, 'action', 8, '2026-03-01 08:58:06'),
(126, 9, 'equipment', 4, '2026-03-01 08:58:53'),
(127, 9, 'equipment', 5, '2026-03-01 08:58:53'),
(128, 9, 'equipment', 6, '2026-03-01 08:58:53'),
(129, 23, 'equipment', 4, '2026-03-01 08:58:53'),
(130, 23, 'equipment', 5, '2026-03-01 08:58:53'),
(131, 23, 'equipment', 6, '2026-03-01 08:58:53'),
(132, 24, 'equipment', 4, '2026-03-01 08:58:53'),
(133, 24, 'equipment', 5, '2026-03-01 08:58:53'),
(134, 24, 'equipment', 6, '2026-03-01 08:58:53'),
(135, 25, 'equipment', 4, '2026-03-01 08:58:53'),
(136, 25, 'equipment', 5, '2026-03-01 08:58:53'),
(137, 25, 'equipment', 6, '2026-03-01 08:58:53'),
(138, 21, 'request_type', 3, '2026-03-01 09:00:36'),
(139, 21, 'request_type', 5, '2026-03-01 09:00:36'),
(140, 23, 'request_type', 3, '2026-03-01 09:00:36'),
(141, 23, 'request_type', 5, '2026-03-01 09:00:36'),
(142, 24, 'request_type', 3, '2026-03-01 09:00:36'),
(143, 25, 'request_type', 5, '2026-03-01 09:00:36');

-- --------------------------------------------------------

--
-- Table structure for table `form_option_role_access`
--

CREATE TABLE `form_option_role_access` (
  `id` int UNSIGNED NOT NULL,
  `option_type` enum('request_type','request_platform','request_action','equipment') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Which form-option table this row governs',
  `option_id` bigint UNSIGNED NOT NULL COMMENT 'PK of the governed row in its source table',
  `role_id` tinyint UNSIGNED NOT NULL COMMENT 'References UserRole enum value (5=Employee, 6=Student)',
  `is_enabled` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_option_role_access`
--

INSERT INTO `form_option_role_access` (`id`, `option_type`, `option_id`, `role_id`, `is_enabled`, `updated_at`) VALUES
(1, 'request_type', 1, 6, 1, '2026-03-01 10:35:24'),
(2, 'request_type', 1, 5, 1, '2026-03-01 10:35:24'),
(3, 'request_type', 2, 6, 0, '2026-03-01 10:35:24'),
(4, 'request_type', 2, 5, 1, '2026-03-01 10:35:24'),
(5, 'request_type', 3, 6, 1, '2026-03-01 10:35:24'),
(6, 'request_type', 3, 5, 1, '2026-03-01 10:35:24'),
(7, 'request_type', 4, 6, 1, '2026-03-01 10:35:24'),
(8, 'request_type', 4, 5, 1, '2026-03-01 10:35:24'),
(9, 'request_type', 5, 6, 1, '2026-03-01 10:35:24'),
(10, 'request_type', 5, 5, 1, '2026-03-01 10:35:24'),
(11, 'request_type', 6, 6, 1, '2026-03-01 10:35:24'),
(12, 'request_type', 6, 5, 1, '2026-03-01 10:35:24'),
(13, 'request_type', 7, 6, 1, '2026-03-01 10:35:24'),
(14, 'request_type', 7, 5, 1, '2026-03-01 10:35:24'),
(15, 'request_type', 8, 6, 1, '2026-03-01 10:35:24'),
(16, 'request_type', 8, 5, 1, '2026-03-01 10:35:24'),
(17, 'request_platform', 1, 6, 1, '2026-03-01 10:35:25'),
(18, 'request_platform', 1, 5, 1, '2026-03-01 10:35:25'),
(19, 'request_platform', 2, 6, 1, '2026-03-01 10:35:25'),
(20, 'request_platform', 2, 5, 1, '2026-03-01 10:35:25'),
(21, 'request_platform', 3, 6, 1, '2026-03-01 10:35:25'),
(22, 'request_platform', 3, 5, 1, '2026-03-01 10:35:25'),
(23, 'request_platform', 4, 6, 1, '2026-03-01 10:35:25'),
(24, 'request_platform', 4, 5, 1, '2026-03-01 10:35:25'),
(25, 'request_platform', 5, 6, 1, '2026-03-01 10:35:25'),
(26, 'request_platform', 5, 5, 1, '2026-03-01 10:35:25'),
(27, 'request_platform', 6, 6, 1, '2026-03-01 10:35:25'),
(28, 'request_platform', 6, 5, 1, '2026-03-01 10:35:25'),
(29, 'request_platform', 7, 6, 1, '2026-03-01 10:35:25'),
(30, 'request_platform', 7, 5, 1, '2026-03-01 10:35:25'),
(31, 'request_platform', 8, 6, 1, '2026-03-01 10:35:25'),
(32, 'request_platform', 8, 5, 1, '2026-03-01 10:35:25'),
(33, 'request_platform', 9, 6, 1, '2026-03-01 10:35:25'),
(34, 'request_platform', 9, 5, 1, '2026-03-01 10:35:25'),
(35, 'request_platform', 10, 6, 1, '2026-03-01 10:35:25'),
(36, 'request_platform', 10, 5, 1, '2026-03-01 10:35:25'),
(37, 'request_platform', 11, 6, 1, '2026-03-01 10:35:25'),
(38, 'request_platform', 11, 5, 1, '2026-03-01 10:35:25'),
(39, 'request_platform', 12, 6, 1, '2026-03-01 10:35:25'),
(40, 'request_platform', 12, 5, 1, '2026-03-01 10:35:25'),
(41, 'request_platform', 13, 6, 1, '2026-03-01 10:35:25'),
(42, 'request_platform', 13, 5, 1, '2026-03-01 10:35:25'),
(43, 'request_platform', 14, 6, 1, '2026-03-01 10:35:25'),
(44, 'request_platform', 14, 5, 1, '2026-03-01 10:35:25'),
(45, 'request_platform', 15, 6, 1, '2026-03-01 10:35:25'),
(46, 'request_platform', 15, 5, 1, '2026-03-01 10:35:25'),
(47, 'request_action', 1, 6, 1, '2026-03-01 10:35:24'),
(48, 'request_action', 1, 5, 1, '2026-03-01 10:35:24'),
(49, 'request_action', 2, 6, 1, '2026-03-01 10:35:24'),
(50, 'request_action', 2, 5, 1, '2026-03-01 10:35:24'),
(51, 'request_action', 3, 6, 1, '2026-03-01 10:35:25'),
(52, 'request_action', 3, 5, 1, '2026-03-01 10:35:24'),
(53, 'request_action', 4, 6, 1, '2026-03-01 10:35:25'),
(54, 'request_action', 4, 5, 1, '2026-03-01 10:35:24'),
(55, 'request_action', 5, 6, 1, '2026-03-01 10:35:25'),
(56, 'request_action', 5, 5, 1, '2026-03-01 10:35:24'),
(57, 'request_action', 6, 6, 1, '2026-03-01 10:35:25'),
(58, 'request_action', 6, 5, 1, '2026-03-01 10:35:24'),
(59, 'request_action', 7, 6, 1, '2026-03-01 10:35:25'),
(60, 'request_action', 7, 5, 1, '2026-03-01 10:35:24'),
(61, 'request_action', 8, 6, 1, '2026-03-01 10:35:25'),
(62, 'request_action', 8, 5, 1, '2026-03-01 10:35:24'),
(63, 'equipment', 1, 6, 1, '2026-03-01 10:35:25'),
(64, 'equipment', 1, 5, 1, '2026-03-01 10:35:25'),
(65, 'equipment', 2, 6, 1, '2026-03-01 10:35:25'),
(66, 'equipment', 2, 5, 1, '2026-03-01 10:35:25'),
(67, 'equipment', 3, 6, 1, '2026-03-01 10:35:25'),
(68, 'equipment', 3, 5, 1, '2026-03-01 10:35:25'),
(69, 'equipment', 4, 6, 1, '2026-03-01 10:35:25'),
(70, 'equipment', 4, 5, 1, '2026-03-01 10:35:25'),
(71, 'equipment', 5, 6, 1, '2026-03-01 10:35:25'),
(72, 'equipment', 5, 5, 1, '2026-03-01 10:35:25'),
(73, 'equipment', 6, 6, 1, '2026-03-01 10:35:25'),
(74, 'equipment', 6, 5, 1, '2026-03-01 10:35:25'),
(75, 'equipment', 7, 6, 1, '2026-03-01 10:35:25'),
(76, 'equipment', 7, 5, 1, '2026-03-01 10:35:25'),
(77, 'equipment', 8, 6, 1, '2026-03-01 10:35:25'),
(78, 'equipment', 8, 5, 1, '2026-03-01 10:35:25'),
(79, 'equipment', 9, 6, 1, '2026-03-01 10:35:25'),
(80, 'equipment', 9, 5, 1, '2026-03-01 10:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `issue_types`
--

CREATE TABLE `issue_types` (
  `issue_type_id` bigint UNSIGNED NOT NULL,
  `issue_type_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_type_domain` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `section_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `issue_types`
--

INSERT INTO `issue_types` (`issue_type_id`, `issue_type_name`, `issue_type_domain`, `description`, `section_id`, `created_at`, `updated_at`) VALUES
(1, 'Device not powering on', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(2, 'Overheating or unusual fan noise', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(3, 'Printer/Scanner power issue', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(4, 'Storage device failure (HDD/SSD)', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(5, 'Missing or damaged accessories (cables, adapters)', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(6, 'Intermittent restarts or shutdowns', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(7, 'Keyboard or mouse not working', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(8, 'Paper jams in Printers/Scanners', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(9, 'Network hardware or connectivity issues', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(10, 'No display or video output', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(11, 'USB or peripheral ports faulty', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(12, 'Poor print or scan output quality', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(13, 'Damaged power supply or cables', 'Hardware', 'Issues related to physical components of computers and peripherals.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(14, 'Operating system not booting or loading', 'Software', 'Issues related to software applications and operating systems.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(15, 'Application failures or errors', 'Software', 'Issues related to software applications and operating systems.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(16, 'OS Licensing or activation problems', 'Software', 'Issues related to software applications and operating systems.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(17, 'Data backup, recovery, or loss issues', 'Software', 'Issues related to software applications and operating systems.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(18, 'Frequent crashes, freezes, or slow performance', 'Software', 'Issues related to software applications and operating systems.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(19, 'Missing or corrupted drivers', 'Software', 'Issues related to software applications and operating systems.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(20, 'Network configuration or connectivity problems', 'Software', 'Issues related to software applications and operating systems.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(21, 'Suspected or detected malware/virus', 'Software', 'Issues related to software applications and operating systems.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(22, 'Software installation or updates required', 'Software', 'Issues related to software applications and operating systems.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07'),
(23, 'Profile or login problems', 'Software', 'Issues related to software applications and operating systems.', 3, '2025-12-21 08:55:07', '2025-12-21 08:55:07');

-- --------------------------------------------------------

--
-- Table structure for table `job_status`
--

CREATE TABLE `job_status` (
  `status_id` int NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dot_color` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `activity_label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_status`
--

INSERT INTO `job_status` (`status_id`, `label`, `color`, `dot_color`, `activity_label`, `created_at`, `updated_at`) VALUES
(1, 'Open', 'amber', 'bg-amber-500', 'opened', '2026-03-06 06:04:29', '2026-03-11 07:00:23'),
(2, 'In Progress', 'blue', 'bg-blue-500', 'moved to In Progress', '2026-03-06 06:04:29', '2026-03-11 07:00:23'),
(3, 'Waiting for Parts', 'yellow', 'bg-yellow-500', 'waiting for parts', '2026-03-06 06:04:29', '2026-03-11 07:01:03'),
(4, 'Completed', 'emerald', 'bg-emerald-500', 'marked Completed', '2026-03-06 06:04:29', '2026-03-11 07:00:23'),
(5, 'Closed', 'gray', 'bg-gray-500', 'was Closed', '2026-03-06 06:04:29', '2026-03-11 07:00:23'),
(6, 'Cancelled', 'red', 'bg-red-500', 'was Cancelled', '2026-03-06 06:04:29', '2026-03-11 07:00:23');

-- --------------------------------------------------------

--
-- Table structure for table `job_tickets`
--

CREATE TABLE `job_tickets` (
  `job_ticket_id` bigint UNSIGNED NOT NULL,
  `requestor_id` bigint UNSIGNED DEFAULT NULL,
  `requestor_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_tickets`
--

INSERT INTO `job_tickets` (`job_ticket_id`, `requestor_id`, `requestor_name`, `job_status`, `created_at`, `updated_at`) VALUES
(23, 1, NULL, 4, '2026-03-09 22:56:40', '2026-03-11 18:51:34'),
(24, 1, NULL, 4, '2026-03-10 19:13:55', '2026-03-11 18:52:24'),
(26, 1, NULL, 4, '2026-03-11 21:31:02', '2026-03-11 22:08:12'),
(27, 18, NULL, 2, '2026-03-11 22:37:18', '2026-03-11 22:37:18'),
(28, 1, NULL, 1, '2026-03-11 22:53:30', '2026-03-11 22:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `job_ticket_requests`
--

CREATE TABLE `job_ticket_requests` (
  `job_ticket_request_id` bigint UNSIGNED NOT NULL,
  `job_ticket_id` bigint UNSIGNED NOT NULL,
  `section_id` bigint UNSIGNED NOT NULL,
  `problem_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `asset_id` bigint DEFAULT NULL,
  `pre_repair_form` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_platform` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_equipment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_action` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `peripheral_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority_level` bigint UNSIGNED DEFAULT NULL,
  `hardware_issues` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `software_issues` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `additional_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `additional_request_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verifier_id` bigint UNSIGNED DEFAULT NULL,
  `verification_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_ticket_requests`
--

INSERT INTO `job_ticket_requests` (`job_ticket_request_id`, `job_ticket_id`, `section_id`, `problem_description`, `asset_id`, `pre_repair_form`, `request_type`, `request_platform`, `request_equipment`, `request_action`, `peripheral_description`, `priority_level`, `hardware_issues`, `software_issues`, `additional_details`, `additional_request_file`, `verifier_id`, `verification_date`, `created_at`, `updated_at`) VALUES
(19, 23, 1, 'Google Account recovery request for student no. ? with email: kebuquid@my.cspc.edu.ph and alt email: kennethleonardbuquid@gmail.com', NULL, NULL, '1', '2', NULL, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 22:56:40', '2026-03-09 22:56:40'),
(20, 24, 1, 'Google Account recovery request for student no. ? with email: kebuquid@my.cspc.edu.ph and alt email: kennethleonardbuquid@gmail.com', NULL, NULL, '1', '2', NULL, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-10 19:13:55', '2026-03-10 19:13:55'),
(22, 26, 1, 'Google Account recovery request for student no. ? with email: kebuquid@my.cspc.edu.ph and alt email: kennethleonardbuquid@gmail.com', NULL, NULL, '1', '2', NULL, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-11 21:31:02', '2026-03-11 21:31:02'),
(23, 27, 1, 'Google Account recovery request for student no. 221001410 with email: cioscoro@my.cspc.edu.ph and alt email: kebuquid@gmail.com', NULL, NULL, '1', '2', NULL, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-11 22:37:18', '2026-03-11 22:37:18'),
(24, 28, 1, 'Google Account recovery request for student no. 221001251 with email: kebuquid@my.cspc.edu.ph and alt email: kennethleonardbuquid@gmail.com', NULL, NULL, '1', '2', NULL, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-11 22:53:30', '2026-03-11 22:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `job_ticket_responses`
--

CREATE TABLE `job_ticket_responses` (
  `job_ticket_response_id` bigint UNSIGNED NOT NULL,
  `job_ticket_id` bigint UNSIGNED NOT NULL,
  `control_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_performed` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `estimated_cost` decimal(15,2) DEFAULT NULL,
  `staff_id` bigint UNSIGNED NOT NULL,
  `transferred_by` bigint UNSIGNED DEFAULT NULL,
  `transferred_at` timestamp NULL DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `completion_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_completed_in_timeline` tinyint(1) NOT NULL DEFAULT '0',
  `timeliness` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quality` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `communication` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responsiveness` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `overall` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `feedback_date` date DEFAULT NULL,
  `verifier_id` bigint UNSIGNED DEFAULT NULL,
  `verified_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_ticket_responses`
--

INSERT INTO `job_ticket_responses` (`job_ticket_response_id`, `job_ticket_id`, `control_no`, `action_performed`, `estimated_cost`, `staff_id`, `transferred_by`, `transferred_at`, `start_date`, `completion_date`, `completion_status`, `is_completed_in_timeline`, `timeliness`, `quality`, `communication`, `responsiveness`, `overall`, `additional_comments`, `feedback_date`, `verifier_id`, `verified_date`, `created_at`, `updated_at`) VALUES
(16, 23, NULL, 'Emailed the new credential to the alternative email of the student', NULL, 10, NULL, NULL, '2026-03-10', '2026-03-12', 'completed', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 22:56:40', '2026-03-11 18:51:34'),
(17, 24, NULL, 'Emailed the alternative email the new credential for the google account', NULL, 10, NULL, NULL, '2026-03-11', '2026-03-12', 'completed', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-10 19:13:55', '2026-03-11 18:52:24'),
(19, 26, NULL, 'Emailed the alternative email of the student the new password of the google account. The student was also advised to change the password after logging in.', NULL, 10, NULL, NULL, '2026-03-12', '2026-03-12', 'completed', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-11 21:31:02', '2026-03-11 22:08:16'),
(20, 27, NULL, NULL, NULL, 10, NULL, NULL, '2026-03-12', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-11 22:37:18', '2026-03-11 22:37:18'),
(21, 28, NULL, NULL, NULL, 10, NULL, NULL, '2026-03-12', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-11 22:53:30', '2026-03-11 22:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `keyword_rules`
--

CREATE TABLE `keyword_rules` (
  `id` int UNSIGNED NOT NULL,
  `section_id` bigint UNSIGNED NOT NULL,
  `keyword` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tip_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tip_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_default` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keyword_rules`
--

INSERT INTO `keyword_rules` (`id`, `section_id`, `keyword`, `tip_title`, `tip_body`, `is_default`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, '_default', 'Network & Connectivity Support', 'Before submitting, please check if your device is connected to the correct Wi-Fi network (CSPC-NET). Try toggling your Wi-Fi off and on, or restarting your device. If you are using a wired connection, ensure the LAN cable is securely plugged in.', 1, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(2, 2, 'wifi', 'Wi-Fi Troubleshooting', 'Try toggling your Wi-Fi off and on in your device settings. Make sure you are connecting to the correct network (CSPC-NET). If the issue persists, try forgetting the network and reconnecting.', 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(3, 2, 'wi-fi', 'Wi-Fi Troubleshooting', 'Try toggling your Wi-Fi off and on in your device settings. Make sure you are connecting to the correct network (CSPC-NET). If the issue persists, try forgetting the network and reconnecting.', 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(4, 2, 'internet', 'Internet Connection Issue', 'Check if other devices in your area can connect to the internet. Try restarting your device or switching between Wi-Fi and mobile data to isolate the problem.', 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(5, 2, 'connectivity', 'Connectivity Check', 'Verify that your network adapter is enabled. Open your device settings and check if Wi-Fi or Ethernet is turned on. Try connecting to a different network to help us isolate the issue.', 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(6, 2, 'no connection', 'No Connection Detected', 'Please check all physical connections (cables, adapters). Try restarting your router/modem if you have access to it. If on Wi-Fi, try moving closer to the access point.', 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(7, 2, 'dns', 'DNS Resolution Issue', 'Try flushing your DNS cache: open Command Prompt as administrator and type \"ipconfig /flushdns\". You can also try changing your DNS server to 8.8.8.8 (Google DNS) temporarily.', 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(8, 2, 'ip address', 'IP Address Conflict', 'Try releasing and renewing your IP address: open Command Prompt and run \"ipconfig /release\" then \"ipconfig /renew\". If you have a static IP configured, try switching to automatic (DHCP).', 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(9, 2, 'router', 'Router/Access Point Issue', 'If you have access, try power-cycling the router (unplug for 30 seconds, then plug back in). Note the router model and any blinking light patterns to help our technicians diagnose faster.', 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(10, 2, 'network', NULL, NULL, 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(11, 2, 'lan', NULL, NULL, 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(12, 2, 'cable', NULL, NULL, 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(13, 2, 'ethernet', NULL, NULL, 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(14, 2, 'switch', NULL, NULL, 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(15, 2, 'access point', NULL, NULL, 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(16, 2, 'fiber', NULL, NULL, 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(17, 2, 'signal', NULL, NULL, 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(18, 2, 'bandwidth', NULL, NULL, 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(19, 2, 'ping', NULL, NULL, 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(20, 2, 'packet', NULL, NULL, 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(21, 2, 'firewall', NULL, NULL, 0, 1, '2026-02-26 14:27:40', '2026-02-26 14:27:40'),
(22, 3, '_default', 'Hardware & Equipment Support', 'Before submitting, try restarting the device or equipment. Check all cable connections and power sources. If the device shows an error message or code, please include it in your description to help our technicians diagnose the issue faster.', 1, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(23, 3, 'printer', 'Printer Troubleshooting', 'Check if the printer is powered on and connected (USB or network). Verify there is paper in the tray and no paper jam. Try restarting the printer and your computer. Check if the correct printer is set as default in your system settings.', 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(24, 3, 'paper jam', 'Paper Jam Fix', 'Turn off the printer before attempting to remove jammed paper. Gently pull the paper in the direction of the paper path. Check for any small torn pieces that may remain inside. Avoid using excessive force to prevent damage.', 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(25, 3, 'ink', 'Ink/Toner Issue', 'Check the ink or toner levels through the printer software or control panel. If levels are low, please note the printer model and cartridge type so we can prepare a replacement. Avoid printing until resolved to prevent print head damage.', 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(26, 3, 'toner', 'Ink/Toner Issue', 'Check the ink or toner levels through the printer software or control panel. If levels are low, please note the printer model and cartridge type so we can prepare a replacement.', 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(27, 3, 'blue screen', 'Blue Screen (BSOD) Recovery', 'Note the error code displayed on the blue screen (e.g., CRITICAL_PROCESS_DIED). Try restarting the computer. If it happens repeatedly, try booting into Safe Mode (press F8 during startup). Do not force shutdown repeatedly.', 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(28, 3, 'bsod', 'Blue Screen (BSOD) Recovery', 'Note the error code displayed on the blue screen. Try restarting the computer. If it happens repeatedly, try booting into Safe Mode (press F8 during startup). Include the error code in your description.', 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(29, 3, 'slow', 'Slow Computer Performance', 'Try restarting your computer first. Close unnecessary programs and browser tabs. Check if Windows Update is running in the background. Clear temporary files using Disk Cleanup. If the issue persists, note when the slowness started.', 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(30, 3, 'overheat', 'Overheating Device', 'Shut down the device immediately if it feels excessively hot. Ensure ventilation openings are not blocked. Place the device on a flat, hard surface. Do not use the device until it cools down. If this happens frequently, it may need internal cleaning.', 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(31, 3, 'reformat', 'Reformat/Format Request', 'Please backup all important files before requesting a reformat. Note any specific software you need reinstalled after formatting. A reformat will erase all data on the drive.', 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(32, 3, 'hardware', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(33, 3, 'computer', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(34, 3, 'pc', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(35, 3, 'laptop', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(36, 3, 'monitor', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(37, 3, 'keyboard', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(38, 3, 'mouse', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(39, 3, 'ups', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(40, 3, 'avr', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(41, 3, 'scanner', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(42, 3, 'projector', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(43, 3, 'peripheral', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(44, 3, 'cpu', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(45, 3, 'ram', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(46, 3, 'harddisk', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(47, 3, 'hard drive', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(48, 3, 'ssd', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(49, 3, 'motherboard', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(50, 3, 'power supply', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(51, 3, 'screen', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(52, 3, 'display', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(53, 3, 'cartridge', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(54, 3, 'format', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(55, 3, 'repair', NULL, NULL, 0, 1, '2026-02-26 14:30:58', '2026-02-26 14:30:58'),
(56, 1, '_default', 'Account & System Support', 'Before submitting, please try clearing your browser cache and cookies, then attempt again. If you are having login issues, make sure you are using the correct portal URL and that Caps Lock is not enabled. You can also try using a different browser.', 1, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(57, 1, 'password', 'Password Reset Help', 'Try using the \"Forgot Password\" link on the login page first. Make sure Caps Lock is off when typing your password. If your account is locked after multiple failed attempts, please wait 15 minutes before trying again.', 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(58, 1, 'forgot password', 'Password Recovery', 'Use the \"Forgot Password\" link on the login page. Enter your registered email address and check your inbox (and spam folder) for the reset link. The link expires after 24 hours.', 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(59, 1, 'login', 'Login Issue', 'Verify you are using the correct portal URL. Clear your browser cache and cookies. Try using an incognito/private browsing window. Make sure your internet connection is stable.', 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(60, 1, 'locked out', 'Account Locked', 'Your account may be locked due to multiple failed login attempts. Please wait 15 minutes and try again. If the issue persists, include your username/employee ID in your description so we can verify your account.', 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(61, 1, 'enrollment', 'Enrollment System Help', 'Check if the enrollment period is currently open. Make sure you have completed all prerequisite requirements. Clear your browser cache and try using Google Chrome for the best experience.', 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(62, 1, 'enroll', 'Enrollment System Help', 'Check if the enrollment period is currently open. Make sure you have completed all prerequisite requirements. Clear your browser cache and try using Google Chrome for the best experience.', 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(63, 1, 'grade', 'Grade Viewing Issue', 'Grades may take time to appear after the encoding period. Clear your browser cache and refresh the page. If specific subjects are missing, note the subject code and section in your description.', 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(64, 1, 'portal', 'Portal Access Issue', 'Make sure you are using the correct portal URL. Try clearing your browser cache or using incognito mode. If the portal is loading slowly, try accessing it during off-peak hours.', 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(65, 1, 'account', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(66, 1, 'reset', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(67, 1, 'credential', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(68, 1, 'access', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(69, 1, 'permission', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(70, 1, 'system', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(71, 1, 'mis', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(72, 1, 'student portal', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(73, 1, 'employee portal', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(74, 1, 'lms', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(75, 1, 'e-learning', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(76, 1, 'website', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(77, 1, 'web app', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(78, 1, 'database', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(79, 1, 'record', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(80, 1, 'registrar', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(81, 1, 'sign in', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58'),
(82, 1, 'log in', NULL, NULL, 0, 1, '2026-02-26 14:32:58', '2026-02-26 14:32:58');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizational_units`
--

CREATE TABLE `organizational_units` (
  `unit_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `building_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizational_units`
--

INSERT INTO `organizational_units` (`unit_id`, `name`, `description`, `building_id`, `created_at`, `updated_at`) VALUES
(1, 'President\'s Office', '', 6, '2026-02-19 21:45:19', '2026-02-19 21:45:19'),
(2, 'Records Office', '', 6, '2026-02-19 21:45:41', '2026-02-19 21:45:41'),
(3, 'SRRO', '', 6, '2026-02-19 21:45:56', '2026-02-19 21:45:56'),
(4, 'CEA Deans Office', '', 3, '2026-02-19 21:46:24', '2026-02-19 21:46:24'),
(5, 'CCS Deans Office', '', 4, '2026-02-19 21:46:42', '2026-02-19 21:46:42');

-- --------------------------------------------------------

--
-- Table structure for table `priority_levels`
--

CREATE TABLE `priority_levels` (
  `priority_level_id` bigint UNSIGNED NOT NULL,
  `priority_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation_status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `priority_levels`
--

INSERT INTO `priority_levels` (`priority_level_id`, `priority_name`, `operation_status`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Critical', 'Operations STOPPED', 'Indicates that the issue is of utmost importance and requires immediate attention as it has caused a complete halt in operations, leading to significant disruptions and potential losses.', '2025-12-19 06:50:35', '2025-12-19 06:50:35'),
(2, 'High', 'Operations SEVERELY AFFECTED', 'Indicates that the issue is of high importance and requires prompt attention as it has severely impacted operations, causing major disruptions and hindering productivity significantly.', '2025-12-19 06:50:35', '2025-12-19 06:50:35'),
(3, 'Medium', 'Operations PARTIALLY AFFECTED', 'Indicates that the issue is of moderate importance and requires attention as it has partially impacted operations, causing some disruptions but allowing most activities to continue.', '2025-12-19 06:50:35', '2025-12-19 06:50:35'),
(4, 'Low', 'Operations NOT AFFECTED', 'Indicates that the issue is of low importance and does not significantly impact operations, allowing all activities to continue without disruption.', '2025-12-19 06:50:35', '2025-12-19 06:50:35');

-- --------------------------------------------------------

--
-- Table structure for table `request_actions`
--

CREATE TABLE `request_actions` (
  `action_id` bigint UNSIGNED NOT NULL,
  `action_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_type_id` bigint UNSIGNED DEFAULT NULL,
  `section_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_actions`
--

INSERT INTO `request_actions` (`action_id`, `action_name`, `request_type_id`, `section_id`, `created_at`, `updated_at`) VALUES
(1, 'New', 1, 1, '2025-12-19 02:28:01', '2025-12-19 02:28:01'),
(2, 'Reset Password', 1, 1, '2025-12-19 02:28:01', '2025-12-19 02:28:01'),
(3, 'Reactivation', 1, 1, '2025-12-19 02:28:01', '2025-12-19 02:28:01'),
(4, 'Restart', 2, 1, '2025-12-19 02:28:01', '2025-12-19 02:28:01'),
(5, 'Re-installation', 2, 1, '2025-12-19 02:28:01', '2025-12-19 02:28:01'),
(6, 'Restore', 2, 1, '2025-12-19 02:28:01', '2025-12-19 02:28:01'),
(7, 'New Connection', NULL, 2, '2025-12-19 02:28:01', '2025-12-19 02:28:01'),
(8, 'Repair', NULL, 2, '2025-12-19 02:28:01', '2025-12-19 02:28:01');

-- --------------------------------------------------------

--
-- Table structure for table `request_platforms`
--

CREATE TABLE `request_platforms` (
  `platform_id` bigint UNSIGNED NOT NULL,
  `platform_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `request_type_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_platforms`
--

INSERT INTO `request_platforms` (`platform_id`, `platform_name`, `platform_description`, `request_type_id`, `created_at`, `updated_at`) VALUES
(1, 'Office365', 'Microsoft Office 365 suite of productivity tools', 1, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(2, 'Google', 'Google Account Unique to CSPC', 1, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(3, 'SIAS', 'Enrollment and Student Information Access System', 1, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(4, 'LeOnS', 'CSPC\'s Learning Management System', 1, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(5, 'SPIMS', 'SPIMS', 1, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(6, 'Koha', 'Koha', 1, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(7, 'UniSAP', 'Student and Employee Profiling', 1, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(8, 'FMS', 'Facility Management System', 2, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(9, 'RMS', 'RMS', 2, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(10, 'HRIS', 'Human Resource Information System', 2, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(11, 'Queueing', 'Queueing System', 2, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(12, 'Travel Order', 'Travel Order System', 2, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(13, 'SIAS', 'Enrollment and Student Information Access System', 2, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(14, 'SPIMS', 'SPIMS', 2, '2025-12-19 02:18:56', '2025-12-19 02:18:56'),
(15, 'KOHA', 'Koha', 2, '2025-12-19 02:18:56', '2025-12-19 02:18:56');

-- --------------------------------------------------------

--
-- Table structure for table `request_types`
--

CREATE TABLE `request_types` (
  `request_type_id` bigint UNSIGNED NOT NULL,
  `request_type_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_types`
--

INSERT INTO `request_types` (`request_type_id`, `request_type_name`, `section_id`, `created_at`, `updated_at`) VALUES
(1, 'Accounts', 1, '2025-12-19 02:10:43', '2025-12-19 02:10:43'),
(2, 'Systems', 1, '2025-12-19 02:10:43', '2025-12-19 02:10:43'),
(3, 'Repair', 3, '2025-12-19 02:10:43', '2025-12-19 02:10:43'),
(4, 'Installation/Setup', 3, '2025-12-19 02:10:43', '2025-12-19 02:10:43'),
(5, 'Maintenance', 3, '2025-12-19 02:10:43', '2025-12-19 02:10:43'),
(6, 'Inspection', 3, '2025-12-19 02:10:43', '2025-12-19 02:10:43'),
(7, 'Upgrade', 3, '2025-12-19 02:10:43', '2025-12-19 02:10:43'),
(8, 'Disposal', 3, '2025-12-19 02:10:43', '2025-12-19 02:10:43');

-- --------------------------------------------------------

--
-- Table structure for table `response_parts`
--

CREATE TABLE `response_parts` (
  `id` bigint UNSIGNED NOT NULL,
  `job_ticket_response_id` bigint UNSIGNED NOT NULL,
  `part_type` enum('replaced','used') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'used',
  `part_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `unit_cost` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int NOT NULL,
  `access` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_path` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_color` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `access`, `label`, `url_path`, `role_color`) VALUES
(1, 'Super Admin', 'ICTU Head', '/super-admin', 'red'),
(2, 'Admin', 'Head of Section', '/admin', 'blue'),
(3, 'ICTU Staff', 'ICTU Staff', '/ictu-staff', 'green'),
(4, 'Employee', 'Employee', '/employee', 'gray'),
(5, 'Student', 'Student', '/student', 'purple');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `section_id` bigint UNSIGNED NOT NULL,
  `acronym` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`section_id`, `acronym`, `name`, `color`, `description`, `created_at`, `updated_at`) VALUES
(1, 'MIS', 'Management Information Systems', 'blue', 'Responsible for systems development, data management, and user support.', '2026-02-17 04:26:36', '2026-03-11 07:00:23'),
(2, 'NICM', 'Network Internet Communications Management', 'green', 'Manages internet connectivity, security, and access infrastructure. As well as the multimedia.', '2026-02-17 04:26:36', '2026-03-11 07:00:23'),
(3, 'ICTRAM', 'ICT Repair and Maintenance', 'yellow', 'Handles the upkeep, troubleshooting, and lifecycle management of ICT hardware.', '2026-02-17 04:26:36', '2026-03-11 07:00:23');

-- --------------------------------------------------------

--
-- Table structure for table `section_role_access`
--

CREATE TABLE `section_role_access` (
  `id` int UNSIGNED NOT NULL,
  `role_id` tinyint UNSIGNED NOT NULL,
  `section_id` int UNSIGNED NOT NULL,
  `is_enabled` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `section_role_access`
--

INSERT INTO `section_role_access` (`id`, `role_id`, `section_id`, `is_enabled`, `updated_at`) VALUES
(1, 5, 1, 1, '2026-03-02 06:52:44'),
(2, 5, 2, 1, '2026-03-02 06:52:44'),
(3, 5, 3, 1, '2026-03-02 06:52:44'),
(4, 6, 1, 1, '2026-03-02 06:52:44'),
(5, 6, 2, 1, '2026-03-02 06:52:44'),
(6, 6, 3, 1, '2026-03-02 06:52:44');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_equipments`
--

CREATE TABLE `ticket_equipments` (
  `equipment_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_equipments`
--

INSERT INTO `ticket_equipments` (`equipment_id`, `name`, `description`, `section_id`, `created_at`, `updated_at`) VALUES
(1, 'Telephone', 'A telecommunications device that transmits sound, especially human voice, over distances by converting sound waves into electrical signals, allowing people to talk to each other instantly.', 2, '2026-02-19 03:26:47', '2026-02-19 03:26:47'),
(2, 'Internet', 'A global, decentralized network of interconnected computers and devices that use the TCP/IP protocol suite to communicate.', 2, '2026-02-19 05:38:25', '2026-02-19 05:38:25'),
(3, 'CCTV', 'A video surveillance system using strategically placed cameras to transmit signals to specific, limited monitors for security monitoring, intrusion prevention, and recording purposes.', 2, '2026-02-19 05:38:53', '2026-02-19 05:38:53'),
(4, 'System Unit', 'The main housing for a computer\'s core electronic components, excluding peripherals like monitors and keyboards. It protects and contains essential hardware such as the motherboard, CPU, RAM, power supply, and storage drives (HDD/SSD). It is the central h', 3, '2026-02-19 05:39:39', '2026-02-19 05:39:39'),
(5, 'All-in-One PC', 'Integrate the computer\'s components, including the CPU, into the monitor, offering a sleek, space-saving design perfect for home offices and minimalist workspaces.', 3, '2026-02-19 05:40:18', '2026-02-19 05:40:18'),
(6, 'Laptop', 'A portable computer with an integrated screen, keyboard, and battery, designed for mobile use while providing similar functionality to desktop computers.', 3, '2026-02-19 05:40:41', '2026-02-19 05:40:41'),
(7, 'Printer', 'A device that produces a hard copy of documents or images from a computer or other digital sources.', 3, '2026-02-19 05:40:59', '2026-02-19 05:40:59'),
(8, 'Scanner', 'A device that converts physical documents or images into digital format for storage, editing, or sharing.', 3, '2026-02-19 05:41:15', '2026-02-19 05:41:15'),
(9, 'Peripherals', 'External devices connected to a computer, such as keyboards, mice, printers, and monitors, that enhance its functionality.', 3, '2026-02-19 05:41:54', '2026-02-19 05:41:54');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_history`
--

CREATE TABLE `ticket_history` (
  `history_id` bigint UNSIGNED NOT NULL,
  `job_ticket_id` bigint UNSIGNED NOT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `old_status` tinyint UNSIGNED DEFAULT NULL,
  `new_status` tinyint UNSIGNED DEFAULT NULL,
  `performed_by` bigint UNSIGNED DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_history`
--

INSERT INTO `ticket_history` (`history_id`, `job_ticket_id`, `action`, `old_status`, `new_status`, `performed_by`, `remarks`, `created_at`) VALUES
(1, 16, 'created', NULL, 1, 12, 'Ticket submitted', '2026-02-28 17:00:40'),
(2, 16, 'assigned', 1, 2, NULL, 'Auto-assigned to EMAN ESCURO', '2026-02-28 17:00:40'),
(3, 17, 'created', NULL, 1, 12, 'Ticket submitted', '2026-02-28 18:42:09'),
(4, 17, 'assigned', 1, 1, NULL, 'Auto-assigned to EMAN ESCURO', '2026-02-28 18:42:09'),
(5, 17, 'transferred', NULL, NULL, 13, 'Transferred to Sir Jam', '2026-02-28 18:43:04'),
(6, 18, 'created', NULL, 1, 12, 'Ticket submitted', '2026-03-01 23:05:37'),
(7, 18, 'assigned', 1, 1, NULL, 'Auto-assigned to EMAN ESCURO', '2026-03-01 23:05:37'),
(8, 19, 'created', NULL, 1, 12, 'Ticket submitted', '2026-03-01 23:10:15'),
(9, 19, 'assigned', 1, 2, NULL, 'Auto-assigned to Danjho Orbita', '2026-03-01 23:10:15'),
(10, 19, 'in_progress', 1, 2, NULL, 'Automatically set to In Progress (staff has no other active ticket)', '2026-03-01 23:10:15'),
(11, 20, 'created', NULL, 1, 12, 'Ticket submitted', '2026-03-01 23:15:14'),
(12, 20, 'assigned', 1, 2, NULL, 'Auto-assigned to Julius Arroyo', '2026-03-01 23:15:14'),
(13, 20, 'in_progress', 1, 2, NULL, 'Automatically set to In Progress (staff has no other active ticket)', '2026-03-01 23:15:14'),
(14, 20, 'completed', 2, 4, 7, 'Marked as completed', '2026-03-01 23:17:26'),
(15, 20, 'verified', 4, 5, 3, 'Verified and closed by section head', '2026-03-01 23:18:25'),
(16, 23, 'completed', 2, 4, 10, 'Marked as completed', '2026-03-11 18:51:34'),
(17, 24, 'in_progress', 1, 2, 10, 'Automatically set to In Progress (previous ticket completed)', '2026-03-11 18:51:34'),
(18, 24, 'completed', 2, 4, 10, 'Marked as completed', '2026-03-11 18:52:24'),
(19, 26, 'completed', 2, 4, 10, 'Marked as completed', '2026-03-11 22:08:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint UNSIGNED NOT NULL,
  `account_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` int DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_unit_id` bigint DEFAULT NULL,
  `expertise` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_id` bigint UNSIGNED DEFAULT NULL,
  `role_id` int NOT NULL DEFAULT '6',
  `is_ictu_employee` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `account_no`, `name`, `email`, `alt_email`, `phone_number`, `avatar`, `org_unit_id`, `expertise`, `section_id`, `role_id`, `is_ictu_employee`, `created_at`, `updated_at`) VALUES
(1, '221001251', 'Kenneth Leonard Buquid', 'kebuquid@my.cspc.edu.ph', 'kennethleonardbuquid@gmail.com', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocLedIsGFJIpM7HA-_6rEfyQ_3fdTRHZMgXRFbUW7iqfPzaeaHQ=s96-c', 0, NULL, NULL, 5, 0, '2026-02-16 14:17:18', '2026-03-12 06:59:36'),
(3, '', 'Jonie Berina', 'jonieberina@cspc.edu.ph', '', NULL, NULL, 0, NULL, 1, 2, 0, '2026-02-19 13:37:29', '2026-02-19 15:54:04'),
(4, '', 'Danjho Orbita', 'danjhoorbita@cspc.edu.ph', '', NULL, NULL, 0, NULL, 2, 2, 0, '2026-02-19 13:37:29', '2026-02-19 15:54:35'),
(5, '', 'Sir Jam', 'sirjam@cspc.edu.ph', '', NULL, NULL, 0, NULL, 3, 2, 0, '2026-02-19 13:37:29', '2026-03-11 01:17:45'),
(6, '', 'Joyce Arbaja', 'joycearbaja@cspc.edu.ph', '', NULL, NULL, 0, NULL, 1, 4, 0, '2026-02-19 13:37:29', '2026-02-19 15:56:50'),
(7, '', 'Julius Arroyo', 'juliusarroyo@cspc.edu.ph', '', NULL, NULL, 0, NULL, 1, 4, 0, '2026-02-19 13:37:29', '2026-02-19 15:57:21'),
(8, '', 'Alex Nagales', 'alexnagales@cspc.edu.ph', '', NULL, NULL, 0, NULL, 2, 3, 0, '2026-02-19 13:37:29', '2026-02-19 15:57:57'),
(9, '', 'Sir Ronald', 'sirronald@cspc.edu.ph', '', NULL, NULL, 0, NULL, 2, 3, 0, '2026-02-19 13:37:29', '2026-02-19 15:58:21'),
(10, '', 'Sir Pol', 'sirpol@cspc.edu.ph', '', NULL, NULL, 0, NULL, 1, 3, 0, '2026-02-19 13:37:29', '2026-03-10 03:15:46'),
(11, '', 'Sir Pol 2', 'sirpol2@cspc.edu.ph', '', NULL, NULL, 0, NULL, 3, 3, 0, '2026-02-19 13:37:29', '2026-03-12 02:00:02'),
(12, '', 'Juan Dela Cruz', 'jucruz@cspc.edu.ph', '', NULL, NULL, 0, NULL, NULL, 5, 0, '2026-02-20 12:58:44', '2026-02-20 12:58:44'),
(14, '', 'Rey Cortez', 'reycortez@cspc.edu.ph', '', NULL, NULL, 0, NULL, NULL, 1, 0, '2026-02-26 06:38:31', '2026-02-26 06:38:31'),
(15, '', 'Leorence Baybayon', 'lebaybayon@my.cspc.edu.ph', '', NULL, NULL, NULL, NULL, NULL, 5, 0, '2026-03-10 06:54:45', '2026-03-10 06:54:45'),
(17, '221001291', 'EMAN ESCURO', 'emescuro@my.cspc.edu.ph', '', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocJjvcBAIeNQ50yN-bXLvh2CT3YeGNN4Wm__L89CcGtu93HrXC0=s96-c', NULL, NULL, NULL, 5, 0, '2026-03-11 08:02:03', '2026-03-11 08:02:03'),
(18, '221001410', 'Cindy Oscoro', 'cioscoro@my.cspc.edu.ph', 'kebuquid@gmail.com', NULL, NULL, NULL, NULL, NULL, 5, 0, '2026-03-12 06:35:37', '2026-03-12 06:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `user_expertise`
--

CREATE TABLE `user_expertise` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `expertise_id` bigint UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_expertise`
--

INSERT INTO `user_expertise` (`id`, `user_id`, `expertise_id`, `created_at`) VALUES
(1, 2, 4, NULL),
(2, 2, 5, NULL),
(3, 3, 1, NULL),
(4, 3, 3, NULL),
(5, 4, 4, NULL),
(6, 4, 5, NULL),
(7, 4, 6, NULL),
(11, 6, 1, NULL),
(12, 6, 3, NULL),
(13, 7, 1, NULL),
(14, 7, 2, NULL),
(15, 8, 4, NULL),
(16, 9, 5, NULL),
(17, 9, 6, NULL),
(28, 13, 7, NULL),
(29, 13, 8, NULL),
(30, 13, 9, NULL),
(36, 10, 10, NULL),
(37, 5, 23, NULL),
(38, 5, 7, NULL),
(39, 5, 25, NULL),
(40, 5, 22, NULL),
(41, 11, 7, NULL),
(42, 11, 22, NULL),
(43, 11, 9, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`),
  ADD KEY `idx_asset_serial` (`serial_number`),
  ADD KEY `idx_asset_property_no` (`property_no`);

--
-- Indexes for table `asset_disposals`
--
ALTER TABLE `asset_disposals`
  ADD PRIMARY KEY (`disposal_id`);

--
-- Indexes for table `asset_groups`
--
ALTER TABLE `asset_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`building_id`);

--
-- Indexes for table `expertise`
--
ALTER TABLE `expertise`
  ADD PRIMARY KEY (`expertise_id`),
  ADD KEY `skill` (`skill`(250)),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `expertise_signal_map`
--
ALTER TABLE `expertise_signal_map`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_expertise_id` (`expertise_id`),
  ADD KEY `idx_signal` (`signal_type`,`signal_value`);

--
-- Indexes for table `form_option_role_access`
--
ALTER TABLE `form_option_role_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `option_type_option_id_role_id` (`option_type`,`option_id`,`role_id`);

--
-- Indexes for table `issue_types`
--
ALTER TABLE `issue_types`
  ADD PRIMARY KEY (`issue_type_id`),
  ADD KEY `issue_types_section_id_index` (`section_id`),
  ADD KEY `issue_types_issue_type_name_index` (`issue_type_name`),
  ADD KEY `issue_types_issue_type_domain_index` (`issue_type_domain`),
  ADD KEY `issue_types_created_at_index` (`created_at`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `job_status`
--
ALTER TABLE `job_status`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `idx_status_color` (`label`);

--
-- Indexes for table `job_tickets`
--
ALTER TABLE `job_tickets`
  ADD PRIMARY KEY (`job_ticket_id`),
  ADD KEY `job_tickets_requestor_id_index` (`requestor_id`),
  ADD KEY `job_tickets_job_status_index` (`job_status`),
  ADD KEY `job_tickets_created_at_index` (`created_at`);

--
-- Indexes for table `job_ticket_requests`
--
ALTER TABLE `job_ticket_requests`
  ADD PRIMARY KEY (`job_ticket_request_id`),
  ADD KEY `job_ticket_requests_job_ticket_id_index` (`job_ticket_id`),
  ADD KEY `job_ticket_requests_verifier_id_index` (`verifier_id`),
  ADD KEY `job_ticket_requests_request_type_index` (`request_type`(250)),
  ADD KEY `job_ticket_requests_request_platform_index` (`request_platform`(250)),
  ADD KEY `job_ticket_requests_priority_level_index` (`priority_level`),
  ADD KEY `job_ticket_requests_created_at_index` (`created_at`),
  ADD KEY `job_ticket_id` (`job_ticket_id`),
  ADD KEY `request_type` (`request_type`(250)),
  ADD KEY `request_platform` (`request_platform`(250)),
  ADD KEY `priority_level` (`priority_level`),
  ADD KEY `request_equipment` (`request_equipment`(250)),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `idx_asset_id_request` (`asset_id`);

--
-- Indexes for table `job_ticket_responses`
--
ALTER TABLE `job_ticket_responses`
  ADD PRIMARY KEY (`job_ticket_response_id`),
  ADD KEY `job_ticket_responses_job_ticket_id_index` (`job_ticket_id`),
  ADD KEY `job_ticket_responses_staff_id_index` (`staff_id`),
  ADD KEY `job_ticket_responses_verifier_id_index` (`verifier_id`),
  ADD KEY `job_ticket_responses_completion_status_index` (`completion_status`(250)),
  ADD KEY `job_ticket_responses_is_completed_in_timeline_index` (`is_completed_in_timeline`),
  ADD KEY `job_ticket_responses_created_at_index` (`created_at`),
  ADD KEY `job_ticket_id` (`job_ticket_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `verifier_id` (`verifier_id`);

--
-- Indexes for table `keyword_rules`
--
ALTER TABLE `keyword_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizational_units`
--
ALTER TABLE `organizational_units`
  ADD PRIMARY KEY (`unit_id`),
  ADD KEY `idx_unit_building` (`building_id`),
  ADD KEY `building_id` (`building_id`);

--
-- Indexes for table `priority_levels`
--
ALTER TABLE `priority_levels`
  ADD PRIMARY KEY (`priority_level_id`),
  ADD KEY `priority_levels_priority_name_index` (`priority_name`),
  ADD KEY `priority_levels_operation_status_index` (`operation_status`),
  ADD KEY `priority_levels_created_at_index` (`created_at`);

--
-- Indexes for table `request_actions`
--
ALTER TABLE `request_actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `request_actions_request_type_id_index` (`request_type_id`),
  ADD KEY `request_actions_section_id_index` (`section_id`),
  ADD KEY `request_actions_action_name_index` (`action_name`),
  ADD KEY `request_actions_created_at_index` (`created_at`),
  ADD KEY `request_type_id` (`request_type_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `request_platforms`
--
ALTER TABLE `request_platforms`
  ADD PRIMARY KEY (`platform_id`),
  ADD KEY `request_platforms_request_type_id_index` (`request_type_id`),
  ADD KEY `request_platforms_platform_name_index` (`platform_name`),
  ADD KEY `request_platforms_created_at_index` (`created_at`),
  ADD KEY `request_type_id` (`request_type_id`);

--
-- Indexes for table `request_types`
--
ALTER TABLE `request_types`
  ADD PRIMARY KEY (`request_type_id`),
  ADD KEY `request_types_section_id_index` (`section_id`),
  ADD KEY `request_types_request_type_name_index` (`request_type_name`),
  ADD KEY `request_types_created_at_index` (`created_at`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `response_parts`
--
ALTER TABLE `response_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_response_id` (`job_ticket_response_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD KEY `idx_role_label` (`label`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`section_id`),
  ADD KEY `idx_section_acronym` (`acronym`);

--
-- Indexes for table `section_role_access`
--
ALTER TABLE `section_role_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_section_unique` (`role_id`,`section_id`),
  ADD KEY `sra_section_fk` (`section_id`);

--
-- Indexes for table `ticket_equipments`
--
ALTER TABLE `ticket_equipments`
  ADD PRIMARY KEY (`equipment_id`),
  ADD KEY `idx_equipment_section` (`section_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `ticket_history`
--
ALTER TABLE `ticket_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `idx_ticket_history_ticket` (`job_ticket_id`),
  ADD KEY `idx_ticket_history_action` (`action`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `idx_email` (`email`(250)),
  ADD KEY `idx_user_expertise` (`expertise`(250)),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `idx_account_no` (`account_no`),
  ADD KEY `idx_alt_email` (`alt_email`),
  ADD KEY `idx_org_id` (`org_unit_id`);

--
-- Indexes for table `user_expertise`
--
ALTER TABLE `user_expertise`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_expertise_id` (`user_id`,`expertise_id`),
  ADD KEY `user_expertise_expertise_id_foreign` (`expertise_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `asset_disposals`
--
ALTER TABLE `asset_disposals`
  MODIFY `disposal_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `asset_groups`
--
ALTER TABLE `asset_groups`
  MODIFY `group_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `building_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `expertise`
--
ALTER TABLE `expertise`
  MODIFY `expertise_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `expertise_signal_map`
--
ALTER TABLE `expertise_signal_map`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `form_option_role_access`
--
ALTER TABLE `form_option_role_access`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `issue_types`
--
ALTER TABLE `issue_types`
  MODIFY `issue_type_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `job_status`
--
ALTER TABLE `job_status`
  MODIFY `status_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job_tickets`
--
ALTER TABLE `job_tickets`
  MODIFY `job_ticket_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `job_ticket_requests`
--
ALTER TABLE `job_ticket_requests`
  MODIFY `job_ticket_request_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `job_ticket_responses`
--
ALTER TABLE `job_ticket_responses`
  MODIFY `job_ticket_response_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `keyword_rules`
--
ALTER TABLE `keyword_rules`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizational_units`
--
ALTER TABLE `organizational_units`
  MODIFY `unit_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `priority_levels`
--
ALTER TABLE `priority_levels`
  MODIFY `priority_level_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `request_actions`
--
ALTER TABLE `request_actions`
  MODIFY `action_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `request_platforms`
--
ALTER TABLE `request_platforms`
  MODIFY `platform_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `request_types`
--
ALTER TABLE `request_types`
  MODIFY `request_type_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `response_parts`
--
ALTER TABLE `response_parts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `section_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `section_role_access`
--
ALTER TABLE `section_role_access`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ticket_equipments`
--
ALTER TABLE `ticket_equipments`
  MODIFY `equipment_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ticket_history`
--
ALTER TABLE `ticket_history`
  MODIFY `history_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_expertise`
--
ALTER TABLE `user_expertise`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expertise_signal_map`
--
ALTER TABLE `expertise_signal_map`
  ADD CONSTRAINT `expertise_signal_map_ibfk_1` FOREIGN KEY (`expertise_id`) REFERENCES `expertise` (`expertise_id`) ON DELETE CASCADE;

--
-- Constraints for table `issue_types`
--
ALTER TABLE `issue_types`
  ADD CONSTRAINT `issue_types_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_tickets`
--
ALTER TABLE `job_tickets`
  ADD CONSTRAINT `job_tickets_ibfk_2` FOREIGN KEY (`requestor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_ticket_requests`
--
ALTER TABLE `job_ticket_requests`
  ADD CONSTRAINT `job_ticket_requests_ibfk_1` FOREIGN KEY (`job_ticket_id`) REFERENCES `job_tickets` (`job_ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_ticket_responses`
--
ALTER TABLE `job_ticket_responses`
  ADD CONSTRAINT `job_ticket_responses_ibfk_1` FOREIGN KEY (`job_ticket_id`) REFERENCES `job_tickets` (`job_ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `keyword_rules`
--
ALTER TABLE `keyword_rules`
  ADD CONSTRAINT `kw_section_fk` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `organizational_units`
--
ALTER TABLE `organizational_units`
  ADD CONSTRAINT `organizational_units_ibfk_1` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`building_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `request_actions`
--
ALTER TABLE `request_actions`
  ADD CONSTRAINT `request_actions_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `request_platforms`
--
ALTER TABLE `request_platforms`
  ADD CONSTRAINT `request_platforms_ibfk_1` FOREIGN KEY (`request_type_id`) REFERENCES `request_types` (`request_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `response_parts`
--
ALTER TABLE `response_parts`
  ADD CONSTRAINT `fk_response_parts_response` FOREIGN KEY (`job_ticket_response_id`) REFERENCES `job_ticket_responses` (`job_ticket_response_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_equipments`
--
ALTER TABLE `ticket_equipments`
  ADD CONSTRAINT `ticket_equipments_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
