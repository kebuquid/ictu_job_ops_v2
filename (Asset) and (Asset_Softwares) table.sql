-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2026 at 06:24 AM
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
-- Database: `job_ops`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` bigint(20) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED DEFAULT NULL,
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
  `os_is_updated` tinyint(1) NOT NULL DEFAULT 0,
  `software_installed` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `software_license` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_unit_id` int(11) DEFAULT NULL,
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

INSERT INTO `assets` (`asset_id`, `group_id`, `asset_tag`, `property_no`, `brand_model`, `serial_number`, `category`, `operating_system`, `os_license_key`, `os_license_type`, `os_license_expiry`, `os_last_updated`, `os_is_updated`, `software_installed`, `software_license`, `section_id`, `assigned_to`, `assigned_unit_id`, `date_acquired`, `acquisition_cost`, `depreciation_cost`, `warranty_end`, `status`, `lifecycle`, `supplier`, `po_number`, `invoice_number`, `procurement_mode`, `fund_source`, `asset_image`, `created_at`, `updated_at`) VALUES
(172, 64, 'ICTU-SRV-001', '', 'Dell PowerEdge R740', 'SN-DELL-R740-001', 'Server', 'Windows Server 2022', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 'Active', '', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-19 03:03:18'),
(173, 64, 'ICTU-SRV-002', NULL, 'HP ProLiant DL380 Gen10', 'SN-HP-DL380-002', 'Server', 'Windows Server 2022', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(174, 64, 'ICTU-SRV-003', NULL, 'IBM System x3550 M5', 'SN-IBM-X3550-003', 'Server', 'Linux Ubuntu 22.04', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(175, 65, 'ADMIN-PC-001', NULL, 'Lenovo ThinkCentre M720', 'SN-LNV-M720-001', 'Desktop Computer', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(176, 65, 'ADMIN-PC-002', NULL, 'Dell OptiPlex 7090', 'SN-DELL-7090-002', 'Desktop Computer', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(177, 65, 'ADMIN-PC-003', NULL, 'HP EliteDesk 800 G8', 'SN-HP-ED800-003', 'Desktop Computer', 'Windows 10 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(178, 66, 'SRRO-WS-001', NULL, 'Acer Veriton X4680G', 'SN-ACR-X4680-001', 'Desktop Computer', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(179, 66, 'SRRO-WS-002', NULL, 'HP ProDesk 400 G7', 'SN-HP-PD400-002', 'Desktop Computer', 'Windows 10 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(180, 66, 'SRRO-WS-003', NULL, 'Lenovo IdeaCentre 5', 'SN-LNV-IC5-003', 'Desktop Computer', 'Windows 11 Home', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(181, 67, 'CEA-PC-001', NULL, 'Dell OptiPlex 5090', 'SN-DELL-5090-001', 'Desktop Computer', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(182, 67, 'CEA-PC-002', NULL, 'Acer Aspire TC-1660', 'SN-ACR-TC1660-002', 'Desktop Computer', 'Windows 11 Home', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(183, 67, 'CEA-PC-003', NULL, 'HP ProDesk 600 G6', 'SN-HP-PD600-003', 'Desktop Computer', 'Windows 10 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(184, 68, 'CCS-LT-001', NULL, 'HP EliteBook 840 G8', 'SN-HP-EB840-001', 'Laptop', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(185, 68, 'CCS-LT-002', NULL, 'Lenovo ThinkPad T14 Gen 2', 'SN-LNV-T14-002', 'Laptop', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(186, 68, 'CCS-LT-003', NULL, 'Dell Latitude 5420', 'SN-DELL-L5420-003', 'Laptop', 'Windows 11 Pro', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 17:48:58', '2026-03-09 17:48:58'),
(192, 63, 'Sean12', '123123', 'Botlog', '123123151233', 'Computer', 'Windows 10 Home', '123123', 'Subscription', '2026-03-25', '2026-03-23', 1, '10,11,12', 'Subscription', 3, 8, 5, '2026-03-24', 50000.00, 20000.00, '2026-03-25', 'Active', 'NNA', 'adzcas', '123123', '123213', 'Small Value Procurement', '5123', '1774329506_4f726ec22fbb4aba90e8.png', '2026-03-24 05:18:26', '2026-03-24 05:18:26');

-- --------------------------------------------------------

--
-- Table structure for table `asset_softwares`
--

CREATE TABLE `asset_softwares` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `license_type` varchar(50) DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `last_updated` date DEFAULT NULL,
  `is_updated` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asset_softwares`
--

INSERT INTO `asset_softwares` (`id`, `asset_id`, `name`, `license_type`, `license_expiry`, `last_updated`, `is_updated`, `notes`, `created_at`, `updated_at`) VALUES
(10, 192, 'Adobe Acrobat Reader', 'Subscription', '2026-03-24', '2026-03-23', 1, 'nana', '2026-03-24 05:18:26', '2026-03-24 05:18:26'),
(11, 192, 'Adobe Photoshop', 'Subscription', '2026-03-24', '2026-03-23', 1, NULL, '2026-03-24 05:18:26', '2026-03-24 05:18:26'),
(12, 192, 'Microsoft Edge', 'Subscription', '2026-03-24', '2026-03-23', 1, NULL, '2026-03-24 05:18:26', '2026-03-24 05:18:26');

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
-- Indexes for table `asset_softwares`
--
ALTER TABLE `asset_softwares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_softwares_asset_id_index` (`asset_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `asset_softwares`
--
ALTER TABLE `asset_softwares`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `asset_softwares`
--
ALTER TABLE `asset_softwares`
  ADD CONSTRAINT `fk_asset_softwares_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
