-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 16, 2026 at 01:38 AM
-- Server version: 8.4.7
-- PHP Version: 8.4.15

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

DROP TABLE IF EXISTS `assets`;
CREATE TABLE IF NOT EXISTS `assets` (
  `asset_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` int UNSIGNED DEFAULT NULL,
  `asset_tag` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `property_no` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `brand_model` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `operating_system` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_license_key` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_license_type` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_license_expiry` date DEFAULT NULL,
  `os_last_updated` date DEFAULT NULL,
  `os_is_updated` tinyint(1) NOT NULL DEFAULT '0',
  `software_installed` text COLLATE utf8mb4_general_ci,
  `software_license` text COLLATE utf8mb4_general_ci,
  `software_list` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `section_id` int DEFAULT NULL,
  `assigned_to` int DEFAULT NULL,
  `assigned_unit_id` int DEFAULT NULL,
  `date_acquired` date DEFAULT NULL,
  `acquisition_cost` decimal(15,2) DEFAULT NULL,
  `depreciation_cost` decimal(15,2) DEFAULT NULL,
  `warranty_end` date DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Active',
  `lifecycle` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `supplier` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `po_number` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_number` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `procurement_mode` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fund_source` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `asset_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`asset_id`),
  KEY `idx_asset_serial` (`serial_number`),
  KEY `idx_asset_property_no` (`property_no`)
) ;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`asset_id`, `group_id`, `asset_tag`, `property_no`, `brand_model`, `serial_number`, `category`, `operating_system`, `os_license_key`, `os_license_type`, `os_license_expiry`, `os_last_updated`, `os_is_updated`, `software_installed`, `software_license`, `software_list`, `section_id`, `assigned_to`, `assigned_unit_id`, `date_acquired`, `acquisition_cost`, `depreciation_cost`, `warranty_end`, `status`, `lifecycle`, `supplier`, `po_number`, `invoice_number`, `procurement_mode`, `fund_source`, `asset_image`, `created_at`, `updated_at`) VALUES
(168, 63, 'ASSET-009', 'PN-2024-001', 'Dell Latitude 5520', 'DOBJ3SP001251001CA3000', 'Computer', 'Windows 11 Home', '1234-5678-9101', 'Freeware', '2026-02-27', '2026-02-28', 1, NULL, NULL, '[{\"name\":\"Microsoft Word\",\"license_type\":\"Subscription\",\"license_expiry\":\"2026-03-07\",\"last_updated\":\"2026-02-27\",\"is_updated\":\"0\",\"notes\":\"nice\"}]', 2, 3, 1, '2026-02-27', 50000.00, 5000.00, '2026-03-07', 'Active', '2 yrs', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-26 22:13:48', '2026-03-04 17:11:18'),
(170, 63, 'ASSET-005', 'PN-2024-001', 'All-in-One 23.6 - Acer', 'DOBJ3SP001251001CA3000', 'Printer', 'Windows 11 Home', '1234-5678-9101', 'Subscription', '2026-03-02', '2026-03-03', 1, NULL, NULL, '[{\"name\":\"Adobe Premiere Pro\",\"license_type\":\"\",\"license_expiry\":\"\",\"last_updated\":\"\",\"is_updated\":\"0\",\"notes\":\"2 yrs \"}]', 3, 3, 5, '2026-03-02', 50000.00, 2500.00, '2026-03-03', 'Active', '2 yrs', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-01 22:29:35', '2026-03-01 22:33:41'),
(171, 63, 'ASSET-200', 'PN-2024-001', 'All-in-One 23.6 - Acer', 'DOBJ3SP001251001CA3000', 'Computer', 'Windows 11 Home', '1234-5678-9101', 'Subscription', '2026-03-02', '2026-03-03', 0, NULL, NULL, '[{\"name\":\"Microsoft Project\",\"license_type\":\"Perpetual\",\"license_expiry\":\"\",\"is_updated\":\"0\",\"last_updated\":\"\",\"notes\":\"\"}]', 3, 8, NULL, '2026-03-02', 50000.00, 5220.00, '2026-03-03', 'Under Repair', '5 years', 'Abc trading co', '20548120', '1231243423', 'Direct Contracting', 'Trust Fund', NULL, '2026-03-05 17:59:09', '2026-03-05 17:59:09'),
(172, 64, 'ICTU-SRV-001', NULL, 'Dell PowerEdge R740', 'SN-DELL-R740-001', 'Server', 'Windows Server 2022', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(173, 64, 'ICTU-SRV-002', NULL, 'HP ProLiant DL380 Gen10', 'SN-HP-DL380-002', 'Server', 'Windows Server 2022', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(174, 64, 'ICTU-SRV-003', NULL, 'IBM System x3550 M5', 'SN-IBM-X3550-003', 'Server', 'Linux Ubuntu 22.04', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(175, 65, 'ADMIN-PC-001', NULL, 'Lenovo ThinkCentre M720', 'SN-LNV-M720-001', 'Desktop Computer', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(176, 65, 'ADMIN-PC-002', NULL, 'Dell OptiPlex 7090', 'SN-DELL-7090-002', 'Desktop Computer', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(177, 65, 'ADMIN-PC-003', NULL, 'HP EliteDesk 800 G8', 'SN-HP-ED800-003', 'Desktop Computer', 'Windows 10 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(178, 66, 'SRRO-WS-001', NULL, 'Acer Veriton X4680G', 'SN-ACR-X4680-001', 'Desktop Computer', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(179, 66, 'SRRO-WS-002', NULL, 'HP ProDesk 400 G7', 'SN-HP-PD400-002', 'Desktop Computer', 'Windows 10 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(180, 66, 'SRRO-WS-003', NULL, 'Lenovo IdeaCentre 5', 'SN-LNV-IC5-003', 'Desktop Computer', 'Windows 11 Home', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(181, 67, 'CEA-PC-001', NULL, 'Dell OptiPlex 5090', 'SN-DELL-5090-001', 'Desktop Computer', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(182, 67, 'CEA-PC-002', NULL, 'Acer Aspire TC-1660', 'SN-ACR-TC1660-002', 'Desktop Computer', 'Windows 11 Home', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(183, 67, 'CEA-PC-003', NULL, 'HP ProDesk 600 G6', 'SN-HP-PD600-003', 'Desktop Computer', 'Windows 10 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(184, 68, 'CCS-LT-001', NULL, 'HP EliteBook 840 G8', 'SN-HP-EB840-001', 'Laptop', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(185, 68, 'CCS-LT-002', NULL, 'Lenovo ThinkPad T14 Gen 2', 'SN-LNV-T14-002', 'Laptop', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(186, 68, 'CCS-LT-003', NULL, 'Dell Latitude 5420', 'SN-DELL-L5420-003', 'Laptop', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58');

-- --------------------------------------------------------

--
-- Table structure for table `asset_disposals`
--

DROP TABLE IF EXISTS `asset_disposals`;
CREATE TABLE IF NOT EXISTS `asset_disposals` (
  `disposal_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_id` bigint UNSIGNED NOT NULL,
  `disposal_reason` text COLLATE utf8mb4_general_ci,
  `disposal_date` date DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `condition_status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `disposal_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`disposal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_groups`
--

DROP TABLE IF EXISTS `asset_groups`;
CREATE TABLE IF NOT EXISTS `asset_groups` (
  `group_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `group_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `quantity` int NOT NULL DEFAULT '1',
  `tag_prefix` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'e.g. IT-PC → generates IT-PC-001, IT-PC-002 ...',
  `section_id` int UNSIGNED DEFAULT NULL,
  `assigned_unit_id` int UNSIGNED DEFAULT NULL,
  `assigned_to` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_acquired` date DEFAULT NULL,
  `acquisition_cost` decimal(15,2) DEFAULT NULL,
  `depreciation_cost` decimal(15,2) DEFAULT NULL,
  `warranty_end` date DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `lifecycle` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asset_groups`
--

INSERT INTO `asset_groups` (`group_id`, `group_name`, `group_code`, `category`, `description`, `quantity`, `tag_prefix`, `section_id`, `assigned_unit_id`, `assigned_to`, `date_acquired`, `acquisition_cost`, `depreciation_cost`, `warranty_end`, `status`, `lifecycle`, `created_at`, `updated_at`) VALUES
(63, 'CS Desktop Computers Batch 2026', 'GRP-IT-2026-02', 'IT Equipment', 'checking', 3, 'CS-PC', NULL, 5, '8', NULL, 50000.00, 4240.00, NULL, 'Active', '4', '2026-03-02 06:33:41', '2026-03-06 01:59:09'),
(64, 'ICTU Server Equipment', 'ICTU-SRV', 'Server', 'Servers and storage units managed by the ICTU department.', 3, 'ICTU-SRV', NULL, 1, 'Sean Matthew C. Capistrano', '2021-01-15', 450000.00, NULL, NULL, 'Active', NULL, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(65, 'Administrative Desktop Computers', 'ADMIN-PC', 'Desktop Computer', 'Desktop workstations used by the Records and Administrative Office.', 3, 'ADMIN-PC', NULL, 2, 'Maria Santos', '2022-06-10', 120000.00, NULL, NULL, 'Active', NULL, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(66, 'SRRO Workstations', 'SRRO-WS', 'Desktop Computer', 'Workstations assigned to the Student Records and Registrar Office.', 3, 'SRRO-WS', NULL, 3, 'Jose Reyes', '2022-03-20', 95000.00, NULL, NULL, 'Active', NULL, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(67, 'CEA Computer Laboratory', 'CEA-LAB', 'Desktop Computer', 'Computer laboratory units for the College of Engineering and Architecture.', 3, 'CEA-PC', NULL, 4, 'Eng. Ricardo Luna', '2023-01-05', 210000.00, NULL, NULL, 'Active', NULL, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(68, 'CCS Laptop Pool', 'CCS-LT', 'Laptop', 'Laptops used by faculty and students of the College of Computer Studies.', 3, 'CCS-LT', NULL, 5, 'Prof. Ana Cruz', '2023-08-01', 180000.00, NULL, NULL, 'Active', NULL, '2026-03-10 01:48:58', '2026-03-10 01:48:58');

-- --------------------------------------------------------

--
-- Table structure for table `asset_maintenance`
--

DROP TABLE IF EXISTS `asset_maintenance`;
CREATE TABLE IF NOT EXISTS `asset_maintenance` (
  `maintenance_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_id` int UNSIGNED DEFAULT NULL,
  `group_id` int UNSIGNED DEFAULT NULL,
  `job_ticket_id` int UNSIGNED DEFAULT NULL,
  `equipment_type` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `frequency` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activities` text COLLATE utf8mb4_general_ci,
  `conducted_by` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `conducted_date` date DEFAULT NULL,
  `verified_by` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `verified_date` date DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_general_ci,
  `issue_description` text COLLATE utf8mb4_general_ci,
  `action_taken` text COLLATE utf8mb4_general_ci,
  `parts_replaced` text COLLATE utf8mb4_general_ci,
  `maintenance_date` date DEFAULT NULL,
  `technician_id` int DEFAULT NULL,
  `cost` decimal(15,2) DEFAULT '0.00',
  `corrective_action` text COLLATE utf8mb4_general_ci,
  `corrective_date` date DEFAULT NULL,
  `responsible_person` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `responsible_date` date DEFAULT NULL,
  `responsible_remarks` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`maintenance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asset_maintenance`
--

INSERT INTO `asset_maintenance` (`maintenance_id`, `asset_id`, `group_id`, `job_ticket_id`, `equipment_type`, `frequency`, `activities`, `conducted_by`, `conducted_date`, `verified_by`, `verified_date`, `remarks`, `issue_description`, `action_taken`, `parts_replaced`, `maintenance_date`, `technician_id`, `cost`, `corrective_action`, `corrective_date`, `responsible_person`, `responsible_date`, `responsible_remarks`, `created_at`, `updated_at`) VALUES
(3, 168, 63, NULL, NULL, 'Monthly', 'Repair, Installation', 'Sean', '2026-03-10', 'Sir jonie', '2026-03-11', 'goods', NULL, NULL, NULL, '2026-03-09', NULL, 0.00, 'Goods', '2026-03-09', 'matthew', '2026-03-11', 'Nicely done', '2026-03-09 03:08:57', '2026-03-09 03:08:57'),
(4, 170, 63, NULL, NULL, 'Monthly', 'Repair, Installation', 'Sean', '2026-03-10', 'Sir jonie', '2026-03-11', 'goods', NULL, NULL, NULL, '2026-03-09', NULL, 0.00, 'Goods', '2026-03-09', 'matthew', '2026-03-11', 'Nicely done', '2026-03-09 03:08:57', '2026-03-09 03:08:57'),
(5, 171, 63, NULL, NULL, 'Monthly', 'Repair, Installation', 'Sean', '2026-03-10', 'Sir jonie', '2026-03-11', 'goods', NULL, NULL, NULL, '2026-03-09', NULL, 0.00, 'Goods', '2026-03-09', 'matthew', '2026-03-11', 'Nicely done', '2026-03-09 03:08:57', '2026-03-09 03:08:57'),
(6, 172, 64, NULL, 'Server', 'Quarterly', '• Dust cleaning\\n• Check hardware components\\n• OS updates\\n• Backup verification', 'Sean Matthew C. Capistrano', '2025-01-10', 'Rey T. Cortez', '2025-01-10', 'All systems functioning normally. RAID array healthy.', 'Minor dust accumulation on intake fans.', 'Cleaned intake fans and internal components. Verified RAID status.', NULL, '2025-01-10', NULL, 0.00, 'No corrective action required.', '2025-01-10', 'Sean Matthew C. Capistrano', '2025-01-10', 'Routine PM completed.', '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(7, 173, 64, NULL, 'Server', 'Quarterly', '• Dust cleaning\\n• Firmware update\\n• Memory check\\n• Network connectivity test', 'Sean Matthew C. Capistrano', '2025-01-10', 'Rey T. Cortez', '2025-01-11', 'Firmware updated to latest version. All memory modules passed POST.', 'Firmware was 2 versions behind.', 'Updated firmware to v2.72. Restarted server during off-hours.', NULL, '2025-01-10', NULL, 0.00, 'Applied firmware update.', '2025-01-11', 'Sean Matthew C. Capistrano', '2025-01-11', 'Firmware update completed successfully.', '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(8, 175, 65, NULL, 'Desktop Computer', 'Quarterly', '• Dust cleaning\\n• Antivirus update\\n• OS patches\\n• Disk defragmentation', 'Sean Matthew C. Capistrano', '2025-04-08', 'Rey T. Cortez', '2025-04-08', 'Unit in good working condition. Disk fragmentation at 12%.', 'Antivirus definitions were outdated by 7 days.', 'Updated antivirus definitions. Applied 14 pending Windows updates.', NULL, '2025-04-08', NULL, 0.00, 'Enabled automatic antivirus update schedule.', '2025-04-08', 'Sean Matthew C. Capistrano', '2025-04-08', 'Auto-update re-enabled. User informed.', '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(9, 176, 65, NULL, 'Desktop Computer', 'Quarterly', '• Dust cleaning\\n• Antivirus update\\n• OS patches\\n• RAM inspection', 'Sean Matthew C. Capistrano', '2025-04-08', 'Rey T. Cortez', '2025-04-09', 'RAM slot 2 showing intermittent errors. Reseated module.', 'System occasionally crashes during heavy workloads.', 'Reseated RAM modules. Ran MemTest86 — passed after reseating.', NULL, '2025-04-08', NULL, 0.00, 'Reseated RAM. Advised user to report recurrence.', '2025-04-09', 'Sean Matthew C. Capistrano', '2025-04-09', 'Monitoring unit for further issues.', '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(10, 178, 66, NULL, 'Desktop Computer', 'Semi-Annual', '• Dust cleaning\\n• Antivirus update\\n• OS patches\\n• Keyboard and mouse cleaning', 'Sean Matthew C. Capistrano', '2025-07-05', 'Rey T. Cortez', '2025-07-05', 'Unit cleaned. All peripherals functioning.', 'Keyboard keys sticking due to debris.', 'Cleaned keyboard with compressed air and isopropyl alcohol.', NULL, '2025-07-05', NULL, 0.00, 'Keyboard cleaned. Replacement keyboard on standby.', '2025-07-05', 'Sean Matthew C. Capistrano', '2025-07-05', 'Unit operational.', '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(11, 172, 64, NULL, 'Server', 'Quarterly', '• Dust cleaning\\n• Check hardware components\\n• OS updates\\n• Backup verification\\n• UPS battery test', 'Sean Matthew C. Capistrano', '2025-07-12', 'Rey T. Cortez', '2025-07-12', 'UPS battery at 82% capacity. Backup restore test successful.', 'UPS battery capacity has degraded to 82%.', 'Noted battery degradation. Recommended replacement within 6 months.', NULL, '2025-07-12', NULL, 0.00, 'UPS battery replacement scheduled for Q4 2025.', '2025-07-15', 'Rey T. Cortez', '2025-07-15', 'Purchase request for replacement battery filed.', '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(12, 181, 67, NULL, 'Desktop Computer', 'Quarterly', '• Dust cleaning\\n• Antivirus update\\n• OS patches\\n• Software license check', 'Sean Matthew C. Capistrano', '2025-07-18', 'Eng. Ricardo Luna', '2025-07-18', 'AutoCAD license expiring in 30 days. Renewal initiated.', 'AutoCAD 2024 license expires August 18, 2025.', 'Coordinated with admin for license renewal. Applied OS patches.', NULL, '2025-07-18', NULL, 0.00, 'License renewal request submitted to admin office.', '2025-07-20', 'Eng. Ricardo Luna', '2025-07-20', 'License renewal in process.', '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(13, 172, 64, NULL, 'Server', 'Quarterly', '• Dust cleaning\\n• UPS battery replacement\\n• OS updates\\n• Backup verification', 'Sean Matthew C. Capistrano', '2025-10-10', 'Rey T. Cortez', '2025-10-10', 'UPS battery replaced. New battery at 100% capacity.', 'UPS battery capacity was 82%, per Q3 recommendation.', 'Replaced UPS battery with APC RBC7 compatible unit.', 'APC UPS Battery RBC7 (1 unit)', '2025-10-10', NULL, 0.00, 'Battery replaced. UPS runtime restored to full capacity.', '2025-10-10', 'Sean Matthew C. Capistrano', '2025-10-10', 'UPS fully operational. Next battery check Q3 2027.', '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(15, 177, 65, NULL, 'Desktop Computer', 'Quarterly', '• Dust cleaning\\n• Antivirus update\\n• OS patches\\n• Disk health check', 'Sean Matthew C. Capistrano', '2026-01-08', 'Rey T. Cortez', '2026-01-08', 'HDD showing early signs of failure (SMART warning). SSD upgrade recommended.', 'SMART diagnostic shows reallocated sector count increasing.', 'Backed up all user data. Flagged for SSD replacement.', NULL, '2026-01-08', NULL, 0.00, 'Data backed up. SSD replacement scheduled.', '2026-01-10', 'Sean Matthew C. Capistrano', '2026-01-10', 'Purchase request for SSD submitted.', '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(16, 173, 64, NULL, NULL, 'Quarterly', '• Dust cleaning\\n• Firmware check\\n• Network performance test\\n• Log review', 'Sean Matthew C. Capistrano', '2026-01-15', 'Rey T. Cortez', '2026-01-15', 'Server logs reviewed. No critical errors. Network throughput normal.', 'None identified during this PM cycle.', 'Routine PM completed. Logs archived.', NULL, '2026-03-12', NULL, 0.00, 'No corrective action required.', '2026-01-15', 'Sean Matthew C. Capistrano', '2026-01-15', 'Unit in excellent condition.', '2026-03-10 01:48:58', '2026-03-12 07:34:21'),
(18, 182, 67, NULL, 'Desktop Computer', 'Quarterly', '• Dust cleaning\\n• Antivirus update\\n• OS patches\\n• Software installation', 'Sean Matthew C. Capistrano', '2026-02-05', 'Eng. Ricardo Luna', '2026-02-05', 'MATLAB R2025a installed per faculty request.', 'Faculty requested MATLAB installation for new curriculum.', 'Installed MATLAB R2025a with educational license.', NULL, '2026-02-05', NULL, 0.00, 'MATLAB installed and verified.', '2026-02-05', 'Eng. Ricardo Luna', '2026-02-05', 'Software installation confirmed by faculty.', '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(20, 174, 64, NULL, NULL, 'Semi-Annual', '• Dust cleaning\\n• Linux kernel update\\n• Security patches\\n• Service health check, Virus', 'Sean Matthew C. Capistrano', '2026-03-12', 'Rey T. Cortez', '2026-01-21', 'Kernel updated to 6.8.x. All services running normally after reboot.', 'Pending security patches (CVE-2024-1234, CVE-2024-5678).', 'Applied all pending security patches. Rebooted server.', NULL, '2026-03-12', NULL, 0.00, 'Critical security patches applied.', '2026-03-12', 'Sean Matthew C. Capistrano', '2026-01-21', 'Server secured and fully operational.', '2026-03-10 01:48:58', '2026-03-12 07:33:55'),
(21, 175, 65, NULL, NULL, 'Monthly', 'Repair', 'Sean', '2026-03-11', 'Sir jonie', '2026-03-11', 'asdas', NULL, NULL, NULL, '2026-03-11', NULL, 0.00, 'asda', '2026-03-11', 'asd', '2026-03-11', 'asd', '2026-03-11 06:03:43', '2026-03-11 06:03:43'),
(22, 176, 65, NULL, NULL, 'Monthly', 'Repair', 'Sean', '2026-03-11', 'Sir jonie', '2026-03-11', 'asdas', NULL, NULL, NULL, '2026-03-11', NULL, 0.00, 'asda', '2026-03-11', 'asd', '2026-03-11', 'asd', '2026-03-11 06:03:43', '2026-03-11 06:03:43'),
(23, 177, 65, NULL, NULL, 'Monthly', 'Repair', 'Sean', '2026-03-11', 'Sir jonie', '2026-03-11', 'asdas', NULL, NULL, NULL, '2026-03-11', NULL, 0.00, 'asda', '2026-03-11', 'asd', '2026-03-11', 'asd', '2026-03-11 06:03:43', '2026-03-11 06:03:43');

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

DROP TABLE IF EXISTS `buildings`;
CREATE TABLE IF NOT EXISTS `buildings` (
  `building_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`building_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

DROP TABLE IF EXISTS `expertise`;
CREATE TABLE IF NOT EXISTS `expertise` (
  `expertise_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `skill` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`expertise_id`),
  KEY `skill` (`skill`(250)),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

DROP TABLE IF EXISTS `expertise_signal_map`;
CREATE TABLE IF NOT EXISTS `expertise_signal_map` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `expertise_id` bigint UNSIGNED NOT NULL,
  `signal_type` enum('equipment','request_type','platform','action','issue_type') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `signal_value` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_expertise_id` (`expertise_id`),
  KEY `idx_signal` (`signal_type`,`signal_value`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

DROP TABLE IF EXISTS `form_option_role_access`;
CREATE TABLE IF NOT EXISTS `form_option_role_access` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `option_type` enum('request_type','request_platform','request_action','equipment') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Which form-option table this row governs',
  `option_id` bigint UNSIGNED NOT NULL COMMENT 'PK of the governed row in its source table',
  `role_id` tinyint UNSIGNED NOT NULL COMMENT 'References UserRole enum value (5=Employee, 6=Student)',
  `is_enabled` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `option_type_option_id_role_id` (`option_type`,`option_id`,`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

DROP TABLE IF EXISTS `issue_types`;
CREATE TABLE IF NOT EXISTS `issue_types` (
  `issue_type_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `issue_type_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_type_domain` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `section_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`issue_type_id`),
  KEY `issue_types_section_id_index` (`section_id`),
  KEY `issue_types_issue_type_name_index` (`issue_type_name`),
  KEY `issue_types_issue_type_domain_index` (`issue_type_domain`),
  KEY `issue_types_created_at_index` (`created_at`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

DROP TABLE IF EXISTS `job_status`;
CREATE TABLE IF NOT EXISTS `job_status` (
  `status_id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dot_color` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `activity_label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`status_id`),
  KEY `idx_status_color` (`label`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

DROP TABLE IF EXISTS `job_tickets`;
CREATE TABLE IF NOT EXISTS `job_tickets` (
  `job_ticket_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `requestor_id` bigint UNSIGNED DEFAULT NULL,
  `requestor_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`job_ticket_id`),
  KEY `job_tickets_requestor_id_index` (`requestor_id`),
  KEY `job_tickets_job_status_index` (`job_status`),
  KEY `job_tickets_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_tickets`
--

INSERT INTO `job_tickets` (`job_ticket_id`, `requestor_id`, `requestor_name`, `job_status`, `created_at`, `updated_at`) VALUES
(23, 1, NULL, 4, '2026-03-09 22:56:40', '2026-03-11 18:51:34'),
(24, 1, NULL, 4, '2026-03-10 19:13:55', '2026-03-11 18:52:24'),
(26, 1, NULL, 4, '2026-03-11 21:31:02', '2026-03-11 22:08:12'),
(27, 18, NULL, 2, '2026-03-11 22:37:18', '2026-03-11 22:37:18'),
(28, 1, NULL, 1, '2026-03-11 22:53:30', '2026-03-11 22:53:30'),
(29, 12, NULL, 1, '2026-03-15 07:31:32', '2026-03-15 07:31:32'),
(30, 12, NULL, 1, '2026-03-15 15:45:53', '2026-03-15 15:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `job_ticket_requests`
--

DROP TABLE IF EXISTS `job_ticket_requests`;
CREATE TABLE IF NOT EXISTS `job_ticket_requests` (
  `job_ticket_request_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`job_ticket_request_id`),
  KEY `job_ticket_requests_job_ticket_id_index` (`job_ticket_id`),
  KEY `job_ticket_requests_verifier_id_index` (`verifier_id`),
  KEY `job_ticket_requests_request_type_index` (`request_type`(250)),
  KEY `job_ticket_requests_request_platform_index` (`request_platform`(250)),
  KEY `job_ticket_requests_priority_level_index` (`priority_level`),
  KEY `job_ticket_requests_created_at_index` (`created_at`),
  KEY `job_ticket_id` (`job_ticket_id`),
  KEY `request_type` (`request_type`(250)),
  KEY `request_platform` (`request_platform`(250)),
  KEY `priority_level` (`priority_level`),
  KEY `request_equipment` (`request_equipment`(250)),
  KEY `section_id` (`section_id`),
  KEY `idx_asset_id_request` (`asset_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_ticket_requests`
--

INSERT INTO `job_ticket_requests` (`job_ticket_request_id`, `job_ticket_id`, `section_id`, `problem_description`, `asset_id`, `pre_repair_form`, `request_type`, `request_platform`, `request_equipment`, `request_action`, `peripheral_description`, `priority_level`, `hardware_issues`, `software_issues`, `additional_details`, `additional_request_file`, `verifier_id`, `verification_date`, `created_at`, `updated_at`) VALUES
(19, 23, 1, 'Google Account recovery request for student no. ? with email: kebuquid@my.cspc.edu.ph and alt email: kennethleonardbuquid@gmail.com', NULL, NULL, '1', '2', NULL, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 22:56:40', '2026-03-09 22:56:40'),
(20, 24, 1, 'Google Account recovery request for student no. ? with email: kebuquid@my.cspc.edu.ph and alt email: kennethleonardbuquid@gmail.com', NULL, NULL, '1', '2', NULL, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-10 19:13:55', '2026-03-10 19:13:55'),
(22, 26, 1, 'Google Account recovery request for student no. ? with email: kebuquid@my.cspc.edu.ph and alt email: kennethleonardbuquid@gmail.com', NULL, NULL, '1', '2', NULL, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-11 21:31:02', '2026-03-11 21:31:02'),
(23, 27, 1, 'Google Account recovery request for student no. 221001410 with email: cioscoro@my.cspc.edu.ph and alt email: kebuquid@gmail.com', NULL, NULL, '1', '2', NULL, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-11 22:37:18', '2026-03-11 22:37:18'),
(24, 28, 1, 'Google Account recovery request for student no. 221001251 with email: kebuquid@my.cspc.edu.ph and alt email: kennethleonardbuquid@gmail.com', NULL, NULL, '1', '2', NULL, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-11 22:53:30', '2026-03-11 22:53:30'),
(25, 29, 3, 'computer', 170, 'uploads/tickets/pre-repair/1773588692_0b72b609d3fa10d6d017.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'On behalf of: jonieberina@cspc.edu.ph', NULL, NULL, NULL, '2026-03-15 07:31:32', '2026-03-15 07:31:32'),
(26, 30, 3, 'printer', 170, 'uploads/tickets/pre-repair/1773589553_28fbc6acb8924794d29a.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'On behalf of: jonieberina@cspc.edu.ph', NULL, NULL, NULL, '2026-03-15 15:45:53', '2026-03-15 15:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `job_ticket_responses`
--

DROP TABLE IF EXISTS `job_ticket_responses`;
CREATE TABLE IF NOT EXISTS `job_ticket_responses` (
  `job_ticket_response_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`job_ticket_response_id`),
  KEY `job_ticket_responses_job_ticket_id_index` (`job_ticket_id`),
  KEY `job_ticket_responses_staff_id_index` (`staff_id`),
  KEY `job_ticket_responses_verifier_id_index` (`verifier_id`),
  KEY `job_ticket_responses_completion_status_index` (`completion_status`(250)),
  KEY `job_ticket_responses_is_completed_in_timeline_index` (`is_completed_in_timeline`),
  KEY `job_ticket_responses_created_at_index` (`created_at`),
  KEY `job_ticket_id` (`job_ticket_id`),
  KEY `staff_id` (`staff_id`),
  KEY `verifier_id` (`verifier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_ticket_responses`
--

INSERT INTO `job_ticket_responses` (`job_ticket_response_id`, `job_ticket_id`, `control_no`, `action_performed`, `estimated_cost`, `staff_id`, `transferred_by`, `transferred_at`, `start_date`, `completion_date`, `completion_status`, `is_completed_in_timeline`, `timeliness`, `quality`, `communication`, `responsiveness`, `overall`, `additional_comments`, `feedback_date`, `verifier_id`, `verified_date`, `created_at`, `updated_at`) VALUES
(16, 23, NULL, 'Emailed the new credential to the alternative email of the student', NULL, 10, NULL, NULL, '2026-03-10', '2026-03-12', 'completed', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 22:56:40', '2026-03-11 18:51:34'),
(17, 24, NULL, 'Emailed the alternative email the new credential for the google account', NULL, 10, NULL, NULL, '2026-03-11', '2026-03-12', 'completed', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-10 19:13:55', '2026-03-11 18:52:24'),
(19, 26, NULL, 'Emailed the alternative email of the student the new password of the google account. The student was also advised to change the password after logging in.', NULL, 10, NULL, NULL, '2026-03-12', '2026-03-12', 'completed', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-11 21:31:02', '2026-03-11 22:08:16'),
(20, 27, NULL, NULL, NULL, 10, NULL, NULL, '2026-03-12', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-11 22:37:18', '2026-03-11 22:37:18'),
(21, 28, NULL, NULL, NULL, 10, NULL, NULL, '2026-03-12', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-11 22:53:30', '2026-03-11 22:53:30'),
(22, 29, NULL, NULL, NULL, 5, NULL, NULL, '2026-03-15', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-15 07:31:32', '2026-03-15 07:31:32'),
(23, 30, NULL, NULL, NULL, 5, NULL, NULL, '2026-03-15', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-15 15:45:53', '2026-03-15 15:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `keyword_rules`
--

DROP TABLE IF EXISTS `keyword_rules`;
CREATE TABLE IF NOT EXISTS `keyword_rules` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_id` bigint UNSIGNED NOT NULL,
  `keyword` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tip_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tip_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_default` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizational_units`
--

DROP TABLE IF EXISTS `organizational_units`;
CREATE TABLE IF NOT EXISTS `organizational_units` (
  `unit_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `building_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`unit_id`),
  KEY `idx_unit_building` (`building_id`),
  KEY `building_id` (`building_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `pm_plans`
--

DROP TABLE IF EXISTS `pm_plans`;
CREATE TABLE IF NOT EXISTS `pm_plans` (
  `plan_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `plan_year` smallint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'ICTU Equipment',
  `department` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `document_code` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'CSPC-F-ICTU-13',
  `prepared_by` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prepared_title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reviewed_by` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reviewed_title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `approved_by` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `approved_title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pm_plans`
--

INSERT INTO `pm_plans` (`plan_id`, `plan_year`, `title`, `department`, `document_code`, `prepared_by`, `prepared_title`, `reviewed_by`, `reviewed_title`, `approved_by`, `approved_title`, `created_at`, `updated_at`) VALUES
(2, 2026, 'ICTU Equipment', 'Management Information System', 'CSPC-F-ICTU-13', 'Sean Matthew C. Capistrano', 'Information Systems Analyst II', 'Rey T. Cortez', 'Head, ICTU', 'Mrs. Nancy S. Penetrante', 'Vice President Admin & Finance', '2026-03-10 01:15:19', '2026-03-12 07:24:58'),
(3, 2025, 'ICTU Equipment', 'Management Information System', 'CSPC-F-ICTU-13', 'Sean Matthew C. Capistrano', 'Information Systems Analyst II', 'Rey T. Cortez', 'Head, ICTU', 'Mrs. Nancy S. Penetrante', 'Vice President Admin & Finance', '2024-12-01 08:00:00', '2024-12-01 08:00:00'),
(4, 2026, 'ICTU Equipment', 'Management Information System', 'CSPC-F-ICTU-13', 'Sean Matthew C. Capistrano', 'Information Systems Analyst II', 'Rey T. Cortez', 'Head, ICTU', 'Mrs. Nancy S. Penetrante', 'Vice President Admin & Finance', '2026-03-10 01:48:58', '2026-03-10 01:48:58');

-- --------------------------------------------------------

--
-- Table structure for table `pm_plan_activities`
--

DROP TABLE IF EXISTS `pm_plan_activities`;
CREATE TABLE IF NOT EXISTS `pm_plan_activities` (
  `activity_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `plan_id` int UNSIGNED NOT NULL,
  `activity_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `sort_order` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`activity_id`),
  KEY `pm_plan_activities_plan_id_foreign` (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pm_plan_items`
--

DROP TABLE IF EXISTS `pm_plan_items`;
CREATE TABLE IF NOT EXISTS `pm_plan_items` (
  `item_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `plan_id` int UNSIGNED NOT NULL,
  `asset_id` int UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `frequency` enum('quarterly','semi_annually','annually','monthly','as_needed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'quarterly',
  `schedule_months` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_order` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `pm_plan_items_plan_id_foreign` (`plan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pm_plan_items`
--

INSERT INTO `pm_plan_items` (`item_id`, `plan_id`, `asset_id`, `description`, `frequency`, `schedule_months`, `sort_order`, `created_at`, `updated_at`) VALUES
(5, 3, 172, 'Dell PowerEdge R740', 'quarterly', '[1,4,7,10]', 1, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(6, 3, 173, 'HP ProLiant DL380 Gen10', 'quarterly', '[1,4,7,10]', 2, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(7, 3, 174, 'IBM System x3550 M5', 'semi_annually', '[1,7]', 3, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(8, 3, 175, 'Lenovo ThinkCentre M720', 'quarterly', '[1,4,7,10]', 4, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(9, 3, 176, 'Dell OptiPlex 7090', 'quarterly', '[1,4,7,10]', 5, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(10, 3, 178, 'Acer Veriton X4680G', 'semi_annually', '[1,7]', 6, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(11, 3, 184, 'HP EliteBook 840 G8', 'annually', '[1]', 7, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(12, 3, 185, 'Lenovo ThinkPad T14 Gen 2', 'annually', '[1]', 8, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(13, 4, 172, 'Dell PowerEdge R740', 'quarterly', '[1,4,7,10]', 1, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(14, 4, 173, 'HP ProLiant DL380 Gen10', 'quarterly', '[1,4,7,10]', 2, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(15, 4, 174, 'IBM System x3550 M5', 'semi_annually', '[1,7]', 3, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(16, 4, 175, 'Lenovo ThinkCentre M720', 'quarterly', '[1,4,7,10]', 4, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(17, 4, 176, 'Dell OptiPlex 7090', 'quarterly', '[1,4,7,10]', 5, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(18, 4, 177, 'HP EliteDesk 800 G8', 'quarterly', '[1,4,7,10]', 6, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(19, 4, 178, 'Acer Veriton X4680G', 'semi_annually', '[1,7]', 7, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(20, 4, 179, 'HP ProDesk 400 G7', 'semi_annually', '[1,7]', 8, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(21, 4, 181, 'Dell OptiPlex 5090', 'quarterly', '[1,4,7,10]', 9, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(22, 4, 182, 'Acer Aspire TC-1660', 'quarterly', '[1,4,7,10]', 10, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(23, 4, 184, 'HP EliteBook 840 G8', 'annually', '[1]', 11, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(24, 4, 185, 'Lenovo ThinkPad T14 Gen 2', 'annually', '[1]', 12, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(25, 4, 186, 'Dell Latitude 5420', 'annually', '[1]', 13, '2026-03-10 01:48:58', '2026-03-10 01:48:58'),
(26, 2, 170, 'All-in-One 23.6 - Acer', 'monthly', '[1,2,3,4,5,6,7,8,9,10,11,12]', 0, '2026-03-12 07:24:58', '2026-03-12 07:24:58'),
(27, 2, 171, 'All-in-One 23.6 - Acer', 'monthly', '[1,2,3,4,5,6,7,8,9,10,11,12]', 1, '2026-03-12 07:24:58', '2026-03-12 07:24:58'),
(28, 2, 168, 'Dell Latitude 5520', 'monthly', '[1,2,3,4,5,6,7,8,9,10,11,12]', 2, '2026-03-12 07:24:58', '2026-03-12 07:24:58'),
(29, 2, 179, 'HP ProDesk 400 G7', 'monthly', '[1,2,3,4,5,6,7,8,9,10,11,12]', 3, '2026-03-12 07:24:58', '2026-03-12 07:24:58'),
(30, 2, 172, 'Dell PowerEdge R740', 'monthly', '[1,2,3,4,5,6,7,8,9,10,11,12]', 4, '2026-03-12 07:24:58', '2026-03-12 07:24:58');

-- --------------------------------------------------------

--
-- Table structure for table `priority_levels`
--

DROP TABLE IF EXISTS `priority_levels`;
CREATE TABLE IF NOT EXISTS `priority_levels` (
  `priority_level_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `priority_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation_status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`priority_level_id`),
  KEY `priority_levels_priority_name_index` (`priority_name`),
  KEY `priority_levels_operation_status_index` (`operation_status`),
  KEY `priority_levels_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

DROP TABLE IF EXISTS `request_actions`;
CREATE TABLE IF NOT EXISTS `request_actions` (
  `action_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `action_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_type_id` bigint UNSIGNED DEFAULT NULL,
  `section_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`action_id`),
  KEY `request_actions_request_type_id_index` (`request_type_id`),
  KEY `request_actions_section_id_index` (`section_id`),
  KEY `request_actions_action_name_index` (`action_name`),
  KEY `request_actions_created_at_index` (`created_at`),
  KEY `request_type_id` (`request_type_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

DROP TABLE IF EXISTS `request_platforms`;
CREATE TABLE IF NOT EXISTS `request_platforms` (
  `platform_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `platform_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `request_type_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`platform_id`),
  KEY `request_platforms_request_type_id_index` (`request_type_id`),
  KEY `request_platforms_platform_name_index` (`platform_name`),
  KEY `request_platforms_created_at_index` (`created_at`),
  KEY `request_type_id` (`request_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

DROP TABLE IF EXISTS `request_types`;
CREATE TABLE IF NOT EXISTS `request_types` (
  `request_type_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `request_type_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`request_type_id`),
  KEY `request_types_section_id_index` (`section_id`),
  KEY `request_types_request_type_name_index` (`request_type_name`),
  KEY `request_types_created_at_index` (`created_at`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

DROP TABLE IF EXISTS `response_parts`;
CREATE TABLE IF NOT EXISTS `response_parts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_ticket_response_id` bigint UNSIGNED NOT NULL,
  `part_type` enum('replaced','used') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'used',
  `part_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `unit_cost` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_response_id` (`job_ticket_response_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `access` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_color` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`role_id`),
  KEY `idx_role_label` (`label`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

DROP TABLE IF EXISTS `sections`;
CREATE TABLE IF NOT EXISTS `sections` (
  `section_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `acronym` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`section_id`),
  KEY `idx_section_acronym` (`acronym`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

DROP TABLE IF EXISTS `section_role_access`;
CREATE TABLE IF NOT EXISTS `section_role_access` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` tinyint UNSIGNED NOT NULL,
  `section_id` int UNSIGNED NOT NULL,
  `is_enabled` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_section_unique` (`role_id`,`section_id`),
  KEY `sra_section_fk` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `section_role_access`
--

INSERT INTO `section_role_access` (`id`, `role_id`, `section_id`, `is_enabled`, `updated_at`) VALUES
(1, 5, 1, 1, '2026-03-16 08:48:41'),
(2, 5, 2, 1, '2026-03-16 08:48:41'),
(3, 5, 3, 1, '2026-03-16 08:48:41'),
(4, 6, 1, 1, '2026-03-02 06:52:44'),
(5, 6, 2, 1, '2026-03-02 06:52:44'),
(6, 6, 3, 1, '2026-03-02 06:52:44'),
(7, 4, 1, 1, '2026-03-16 08:48:41'),
(8, 4, 2, 1, '2026-03-16 08:48:41'),
(9, 4, 3, 1, '2026-03-16 08:48:41');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_equipments`
--

DROP TABLE IF EXISTS `ticket_equipments`;
CREATE TABLE IF NOT EXISTS `ticket_equipments` (
  `equipment_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`equipment_id`),
  KEY `idx_equipment_section` (`section_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

DROP TABLE IF EXISTS `ticket_history`;
CREATE TABLE IF NOT EXISTS `ticket_history` (
  `history_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_ticket_id` bigint UNSIGNED NOT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `old_status` tinyint UNSIGNED DEFAULT NULL,
  `new_status` tinyint UNSIGNED DEFAULT NULL,
  `performed_by` bigint UNSIGNED DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`history_id`),
  KEY `idx_ticket_history_ticket` (`job_ticket_id`),
  KEY `idx_ticket_history_action` (`action`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(19, 26, 'completed', 2, 4, 10, 'Marked as completed', '2026-03-11 22:08:12'),
(20, 29, 'created', NULL, 1, 12, 'Ticket submitted', '2026-03-15 07:31:32'),
(21, 29, 'assigned', 1, 1, NULL, 'Auto-assigned to Sir Jam', '2026-03-15 07:31:32'),
(22, 30, 'created', NULL, 1, 12, 'Ticket submitted', '2026-03-15 15:45:53'),
(23, 30, 'assigned', 1, 1, NULL, 'Auto-assigned to Sir Jam', '2026-03-15 15:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_sla_rules`
--

DROP TABLE IF EXISTS `ticket_sla_rules`;
CREATE TABLE IF NOT EXISTS `ticket_sla_rules` (
  `sla_rule_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_id` bigint UNSIGNED NOT NULL,
  `request_type_id` bigint UNSIGNED DEFAULT NULL,
  `platform_id` bigint UNSIGNED DEFAULT NULL,
  `action_id` bigint UNSIGNED DEFAULT NULL,
  `equipment_id` bigint UNSIGNED DEFAULT NULL,
  `target_hours` int UNSIGNED NOT NULL DEFAULT '24',
  `is_active` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `notes` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sla_rule_id`),
  KEY `idx_sla_section` (`section_id`),
  KEY `idx_sla_section_active` (`section_id`,`is_active`),
  KEY `fk_sla_request_type` (`request_type_id`),
  KEY `fk_sla_platform` (`platform_id`),
  KEY `fk_sla_action` (`action_id`),
  KEY `fk_sla_equipment` (`equipment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_sla_rules`
--

INSERT INTO `ticket_sla_rules` (`sla_rule_id`, `section_id`, `request_type_id`, `platform_id`, `action_id`, `equipment_id`, `target_hours`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 1, NULL, 24, 1, NULL, '2026-03-16 00:41:19', '2026-03-16 00:41:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` int DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_unit_id` bigint DEFAULT NULL,
  `expertise` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_id` bigint UNSIGNED DEFAULT NULL,
  `role_id` int NOT NULL DEFAULT '6',
  `is_ictu_employee` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  KEY `idx_email` (`email`(250)),
  KEY `idx_user_expertise` (`expertise`(250)),
  KEY `section_id` (`section_id`),
  KEY `idx_account_no` (`account_no`),
  KEY `idx_alt_email` (`alt_email`),
  KEY `idx_org_id` (`org_unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

DROP TABLE IF EXISTS `user_expertise`;
CREATE TABLE IF NOT EXISTS `user_expertise` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `expertise_id` bigint UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_expertise_id` (`user_id`,`expertise_id`),
  KEY `user_expertise_expertise_id_foreign` (`expertise_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Constraints for table `pm_plan_activities`
--
ALTER TABLE `pm_plan_activities`
  ADD CONSTRAINT `pm_plan_activities_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `pm_plans` (`plan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pm_plan_items`
--
ALTER TABLE `pm_plan_items`
  ADD CONSTRAINT `pm_plan_items_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `pm_plans` (`plan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `ticket_sla_rules`
--
ALTER TABLE `ticket_sla_rules`
  ADD CONSTRAINT `fk_sla_action` FOREIGN KEY (`action_id`) REFERENCES `request_actions` (`action_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sla_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `ticket_equipments` (`equipment_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sla_platform` FOREIGN KEY (`platform_id`) REFERENCES `request_platforms` (`platform_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sla_request_type` FOREIGN KEY (`request_type_id`) REFERENCES `request_types` (`request_type_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sla_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
