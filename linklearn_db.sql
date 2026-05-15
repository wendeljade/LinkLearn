-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2026 at 11:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `linklearn_db`
--
CREATE DATABASE IF NOT EXISTS `linklearn_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `linklearn_db`;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `allow_late_submissions` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-central_version', 's:7:\"v.1.1.5\";', 2093927541),
('laravel-cache-github_latest_release_version', 's:6:\"v1.0.0\";', 1778577133);

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
-- Table structure for table `domains`
--

CREATE TABLE `domains` (
  `id` int(10) UNSIGNED NOT NULL,
  `domain` varchar(255) NOT NULL,
  `tenant_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `domains`
--

INSERT INTO `domains` (`id`, `domain`, `tenant_id`, `created_at`, `updated_at`) VALUES
(1, 'bukidnon-state-university', 'bukidnon-state-university', '2026-04-28 17:53:45', '2026-04-28 17:53:45'),
(4, 'sti', 'sti', '2026-04-28 20:15:23', '2026-04-28 20:15:23'),
(5, 'central-mindanao-university', 'central-mindanao-university', '2026-05-03 16:33:08', '2026-05-03 16:33:08'),
(6, 'university-of-the-philippines', 'university-of-the-philippines', '2026-05-08 23:55:09', '2026-05-08 23:55:09'),
(7, 'jade-arts', 'jade-arts', '2026-05-09 01:05:45', '2026-05-09 01:05:45'),
(8, 'san-isidro-college', 'san-isidro-college', '2026-05-09 01:11:01', '2026-05-09 01:11:01');

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
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 200.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_purchases`
--

CREATE TABLE `file_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2019_09_15_000010_create_tenants_table', 1),
(4, '2019_09_15_000020_create_domains_table', 1),
(5, '2020_05_15_000010_create_tenant_user_impersonation_tokens_table', 1),
(6, '2024_01_01_000001_create_organizations_table', 1),
(7, '2024_01_01_000002_create_users_table', 1),
(8, '2026_04_10_060232_add_google_id_to_users_table', 1),
(9, '2026_04_12_180615_add_role_to_users_table', 1),
(10, '2026_04_12_190331_create_rooms_table', 1),
(11, '2026_04_12_210533_add_status_to_organizations_table', 1),
(12, '2026_04_12_212129_add_details_to_organizations_table', 1),
(13, '2026_04_12_220806_add_user_id_to_organizations_table', 1),
(14, '2026_04_14_023458_add_organization_id_to_rooms_table', 1),
(15, '2026_04_14_023520_create_room_user_table', 1),
(16, '2026_04_14_064352_add_cover_photo_to_rooms_table', 1),
(17, '2026_04_14_072716_update_rooms_status_enum', 1),
(18, '2026_04_14_090459_create_files_and_purchases_tables', 1),
(19, '2026_04_16_141714_create_activities_table', 1),
(20, '2026_04_16_141714_create_submissions_table', 1),
(21, '2026_04_20_000000_make_tutor_id_nullable_in_rooms_table', 1),
(22, '2026_04_20_000001_add_subscription_paid_at_to_organizations_table', 1),
(23, '2026_04_20_000734_add_file_path_to_activities_table', 1),
(24, '2026_04_20_012612_add_proof_of_payment_to_organizations_table', 1),
(25, '2026_04_26_000003_add_data_to_organizations_table', 1),
(26, '2026_04_26_114456_fix_domains_tenant_foreign_key', 1),
(27, '2026_04_26_130416_fix_domains_tenant_id_to_organizations_id', 1),
(28, '2026_04_26_140000_fix_domains_tenant_id_to_slug', 1),
(29, '2026_04_30_063532_create_organization_user_table', 2),
(30, '2026_05_04_143931_create_support_tickets_table', 3),
(31, '2026_05_04_143932_create_support_messages_table', 3),
(32, '2026_05_09_090207_add_total_payments_to_organizations_table', 4),
(33, '2026_05_09_100251_add_allow_late_submissions_to_activities_table', 5),
(34, '2026_05_09_103224_add_link_to_activities_table', 6),
(35, '2026_05_09_142200_create_announcements_table', 7),
(36, '2026_05_09_234935_add_status_to_room_user_table', 8),
(37, '2026_05_10_050930_create_notifications_table', 9),
(38, '2026_05_10_162541_add_gcash_qr_code_to_organizations_table', 10),
(39, '2026_05_10_180703_add_disable_reason_to_organizations_table', 11),
(40, '2026_05_10_180944_add_disable_reason_to_organizations_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `read_at`, `created_at`, `updated_at`) VALUES
(3, 8, 'support_ticket_admin_reply', 'Admin Replied to Ticket', 'An admin has replied to your support ticket: \"Sample Issue\".', 'http://localhost:8000/support/4', '👨‍💻', '2026-05-10 06:49:28', '2026-05-09 21:41:01', '2026-05-10 06:49:28'),
(4, 1, 'support_ticket_replied', 'New Support Ticket Reply', 'CMU replied to the support ticket: \"Sample Issue\".', 'http://localhost:8000/admin/support/4', '💬', '2026-05-09 21:41:44', '2026-05-09 21:41:27', '2026-05-09 21:41:44'),
(5, 1, 'support_ticket_created', 'New Support Ticket', 'JOSEMARI ROSALIM opened a new support ticket: \"Sample from student\".', 'http://localhost:8000/admin/support/5', '🎫', '2026-05-10 09:55:20', '2026-05-09 21:58:09', '2026-05-10 09:55:20'),
(6, 7, 'support_ticket_admin_reply', 'Admin Replied to Ticket', 'An admin has replied to your support ticket: \"Sample from student\".', 'http://localhost:8000/support/5', '👨‍💻', '2026-05-09 21:59:22', '2026-05-09 21:58:39', '2026-05-09 21:59:22'),
(7, 7, 'support_ticket_admin_reply', 'Admin Replied to Ticket', 'An admin has replied to your support ticket: \"Sample from student\".', 'http://localhost:8000/support/5', '👨‍💻', '2026-05-09 21:59:22', '2026-05-09 21:58:41', '2026-05-09 21:59:22'),
(8, 1, 'support_ticket_replied', 'New Support Ticket Reply', 'JOSEMARI ROSALIM replied to the support ticket: \"Sample from student\".', 'http://localhost:8000/admin/support/5', '💬', '2026-05-10 09:55:20', '2026-05-09 21:58:58', '2026-05-10 09:55:20'),
(10, 7, 'removed_from_room', 'Removed from Classroom', 'You have been removed from the classroom \"Photoshop\".', NULL, '🚫', '2026-05-10 06:58:07', '2026-05-10 06:56:53', '2026-05-10 06:58:07'),
(11, 11, 'join_request', 'New Join Request', 'JOSEMARI ROSALIM requested to join your classroom \"Photoshop\".', NULL, '👋', '2026-05-11 22:19:28', '2026-05-10 06:57:16', '2026-05-11 22:19:28'),
(12, 7, 'join_approved', 'Request Approved', 'Your request to join \"Photoshop\" has been approved.', NULL, '✅', '2026-05-10 06:58:02', '2026-05-10 06:57:42', '2026-05-10 06:58:02'),
(14, 7, 'join_rejected', 'Request Declined', 'Your request to join \"Networking 1\" has been declined.', NULL, '❌', '2026-05-10 07:26:43', '2026-05-10 07:02:27', '2026-05-10 07:26:43'),
(15, 6, 'join_request', 'New Join Request', 'JOSEMARI ROSALIM requested to join your classroom \"Networking 1\".', NULL, '👋', '2026-05-10 07:02:51', '2026-05-10 07:02:36', '2026-05-10 07:02:51'),
(16, 7, 'join_approved', 'Request Approved', 'Your request to join \"Networking 1\" has been approved.', NULL, '✅', '2026-05-10 07:26:43', '2026-05-10 07:25:11', '2026-05-10 07:26:43'),
(17, 6, 'material_purchased', 'New Material Purchase', 'JOSEMARI ROSALIM has submitted payment for \"Module 1\".', 'http://localhost:8000/rooms/3/enter/central-mindanao-university', '💸', '2026-05-10 07:51:43', '2026-05-10 07:33:59', '2026-05-10 07:58:09'),
(18, 7, 'purchase_approved', 'Purchase Approved', 'Your purchase of \"Module 1\" has been verified. You can now access the file.', 'http://localhost:8000/dashboard', '🔓', '2026-05-10 08:07:02', '2026-05-10 08:06:10', '2026-05-10 08:07:02'),
(19, 6, 'material_purchased', 'New Material Purchase', 'JOSEMARI ROSALIM has submitted payment for \"Module 2\".', 'http://localhost:8000/rooms/3/enter/central-mindanao-university', '💸', '2026-05-10 08:12:28', '2026-05-10 08:07:48', '2026-05-10 08:12:28'),
(20, 7, 'purchase_approved', 'Purchase Approved', 'Your purchase of \"Module 2\" has been verified. You can now access the file.', 'http://localhost:8000/dashboard', '🔓', '2026-05-10 08:08:56', '2026-05-10 08:08:25', '2026-05-10 08:08:56'),
(21, 6, 'material_purchased', 'New Material Purchase', 'JOSEMARI ROSALIM has submitted payment for \"Module 3\".', 'http://localhost:8000/rooms/3/enter/central-mindanao-university', '💸', '2026-05-10 09:35:46', '2026-05-10 09:35:15', '2026-05-10 09:35:46'),
(22, 7, 'purchase_approved', 'Purchase Approved', 'Your purchase of \"Module 3\" has been verified. You can now access the file.', 'http://localhost:8000/dashboard', '🔓', '2026-05-10 09:36:06', '2026-05-10 09:35:41', '2026-05-10 09:36:06'),
(31, 8, 'org_status_changed', 'Organization Disabled', 'Your organization \"Central Mindanao University\" has been disabled by the system administrator. Reason: Temporarily disabled for an issue.', NULL, '⚠️', '2026-05-10 10:25:44', '2026-05-10 10:19:10', '2026-05-10 10:25:44'),
(32, 8, 'org_status_changed', 'Organization Re-activated', 'Your organization \"Central Mindanao University\" has been re-activated by the system administrator.', NULL, '✅', '2026-05-10 10:25:44', '2026-05-10 10:19:41', '2026-05-10 10:25:44'),
(33, 8, 'org_status_changed', 'Organization Disabled', 'Your organization \"Central Mindanao University\" has been disabled by the system administrator. Reason: Missing monthly payment.', NULL, '⚠️', '2026-05-10 10:25:44', '2026-05-10 10:19:52', '2026-05-10 10:25:44'),
(34, 8, 'org_status_changed', 'Organization Re-activated', 'Your organization \"Central Mindanao University\" has been re-activated by the system administrator.', NULL, '✅', '2026-05-10 10:25:44', '2026-05-10 10:20:13', '2026-05-10 10:25:44'),
(35, 8, 'org_status_changed', 'Organization Disabled', 'Your organization \"Central Mindanao University\" has been disabled by the system administrator. Reason: Temporarily disabled for an issue.', NULL, '⚠️', '2026-05-10 10:31:41', '2026-05-10 10:26:00', '2026-05-10 10:31:41'),
(36, 8, 'org_status_changed', 'Organization Re-activated', 'Your organization \"Central Mindanao University\" has been re-activated by the system administrator.', NULL, '✅', '2026-05-10 10:31:41', '2026-05-10 10:29:25', '2026-05-10 10:31:41'),
(37, 8, 'org_status_changed', 'Organization Disabled', 'Your organization \"Central Mindanao University\" has been disabled by the system administrator. Reason: Missing monthly payment.', NULL, '⚠️', '2026-05-10 10:31:41', '2026-05-10 10:29:33', '2026-05-10 10:31:41'),
(38, 8, 'org_status_changed', 'Organization Re-activated', 'Your organization \"Central Mindanao University\" has been re-activated by the system administrator.', NULL, '✅', '2026-05-10 10:31:41', '2026-05-10 10:30:21', '2026-05-10 10:31:41'),
(39, 8, 'org_status_changed', 'Organization Disabled', 'Your organization \"Central Mindanao University\" has been disabled by the system administrator. Reason: Missing monthly payment.', NULL, '⚠️', '2026-05-10 10:31:41', '2026-05-10 10:30:34', '2026-05-10 10:31:41'),
(40, 1, 'org_payment_submitted', 'New Payment Received', 'Organization \"Central Mindanao University\" has submitted a payment and is awaiting approval.', 'http://localhost:8000/admin/dashboard', '💳', '2026-05-10 10:31:53', '2026-05-10 10:30:46', '2026-05-10 10:31:53'),
(41, 8, 'org_approved', 'Organization Approved 🎉', 'Your organization \"Central Mindanao University\" has been approved! You can now start creating classrooms.', 'http://localhost:8000/dashboard', '🏢', '2026-05-10 10:31:41', '2026-05-10 10:31:10', '2026-05-10 10:31:41'),
(42, 8, 'org_status_changed', 'Organization Disabled', 'Your organization \"Central Mindanao University\" has been disabled by the system administrator. Reason: Missing monthly payment.', NULL, '⚠️', '2026-05-10 10:55:42', '2026-05-10 10:32:16', '2026-05-10 10:55:42'),
(43, 8, 'org_status_changed', 'Organization Re-activated', 'Your organization \"Central Mindanao University\" has been re-activated by the system administrator.', NULL, '✅', '2026-05-10 10:55:42', '2026-05-10 10:32:57', '2026-05-10 10:55:42'),
(44, 7, 'removed_from_room', 'Removed from Classroom', 'You have been removed from the classroom \"Graphic Design\".', NULL, '🚫', '2026-05-10 19:46:44', '2026-05-10 19:02:15', '2026-05-10 19:46:44'),
(45, 6, 'join_request', 'New Join Request', 'JOSEMARI ROSALIM requested to join your classroom \"Graphic Design\".', NULL, '👋', '2026-05-10 19:59:34', '2026-05-10 19:58:28', '2026-05-10 19:59:34'),
(46, 7, 'join_approved', 'Request Approved', 'Your request to join \"Graphic Design\" has been approved.', NULL, '✅', '2026-05-10 20:00:15', '2026-05-10 19:59:21', '2026-05-10 20:00:15'),
(47, 6, 'material_purchased', 'New Material Purchase', 'JOSEMARI ROSALIM has submitted payment for \"Module 4\".', 'http://localhost:8000/rooms/3/enter/central-mindanao-university', '💸', '2026-05-11 21:39:40', '2026-05-11 21:37:58', '2026-05-11 21:39:40'),
(48, 11, 'join_request', 'New Join Request', 'JOSEMARI ROSALIM requested to join your classroom \"Sample Classroom\".', NULL, '👋', '2026-05-11 22:19:28', '2026-05-11 22:17:05', '2026-05-11 22:19:28'),
(49, 7, 'purchase_approved', 'Purchase Approved', 'Your purchase of \"Module 4\" has been verified. You can now access the file.', 'http://localhost:8000/dashboard', '🔓', '2026-05-11 22:21:09', '2026-05-11 22:18:53', '2026-05-11 22:21:09'),
(50, 8, 'org_status_changed', 'Organization Disabled', 'Your organization \"Central Mindanao University\" has been disabled by the system administrator. Reason: Temporarily disabled for an issue.', NULL, '⚠️', '2026-05-11 22:34:50', '2026-05-11 22:32:41', '2026-05-11 22:34:50'),
(51, 8, 'org_status_changed', 'Organization Re-activated', 'Your organization \"Central Mindanao University\" has been re-activated by the system administrator.', NULL, '✅', '2026-05-11 22:34:50', '2026-05-11 22:33:26', '2026-05-11 22:34:50'),
(52, 8, 'org_status_changed', 'Organization Disabled', 'Your organization \"Central Mindanao University\" has been disabled by the system administrator. Reason: Missing monthly payment.', NULL, '⚠️', '2026-05-11 22:34:50', '2026-05-11 22:33:33', '2026-05-11 22:34:50'),
(53, 8, 'org_status_changed', 'Organization Re-activated', 'Your organization \"Central Mindanao University\" has been re-activated by the system administrator.', NULL, '✅', '2026-05-11 22:34:50', '2026-05-11 22:34:22', '2026-05-11 22:34:50');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `gcash_qr_code` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `disable_reason` varchar(255) DEFAULT NULL,
  `subscription_paid_at` timestamp NULL DEFAULT NULL,
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `total_payments_made` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `user_id`, `name`, `description`, `slug`, `cover_photo`, `gcash_qr_code`, `status`, `disable_reason`, `subscription_paid_at`, `proof_of_payment`, `total_payments_made`, `created_at`, `updated_at`, `data`) VALUES
(1, 3, 'Bukidnon State University', NULL, 'bukidnon-state-university', NULL, 'gcash_qr/J71Fp6XeehjuuQuUv91DBFT9tSvo1JzTIzLykNWm.jpg', 'active', NULL, '2026-04-28 17:44:15', 'proofs/XqDr3JUwmkDz9mxgMEKr9wKDAZJ5iDvGmtBh7Roj.png', 1, '2026-04-28 17:43:17', '2026-05-11 22:16:14', '{\"disable_reason\":null,\"tenancy_db_name\":\"linklearn_org_bukidnon-state-university\"}'),
(4, 5, 'STI', NULL, 'sti', NULL, 'gcash_qr/MKiZlOe4g5WJWRYQ1Z9wE11stLnyLDT9VgwAj0pf.jpg', 'active', NULL, '2026-04-28 19:58:07', 'proofs/DeKTztnzQzBWk3CHhtPWMnTuFDZOD5knZWOT6sxy.png', 1, '2026-04-28 19:57:42', '2026-05-10 09:00:23', '{\"tenancy_db_name\":\"linklearn_org_sti\"}'),
(5, 8, 'Central Mindanao University', NULL, 'central-mindanao-university', NULL, 'gcash_qr/UN6zi0BM71WfBjEmPlv4PoywLL2UikaP3vloaP5T.jpg', 'active', NULL, '2026-05-10 10:30:46', 'proofs/Drnthui6XoVl5ZlPicYrVxxa5Hgy6DIMzIwKSvnH.jpg', 2, '2026-04-29 23:37:19', '2026-05-11 22:34:22', '{\"disable_reason\":null,\"tenancy_db_name\":\"linklearn_org_central-mindanao-university\"}'),
(6, 12, 'Jade Arts', NULL, 'jade-arts', NULL, NULL, 'active', NULL, '2026-05-03 17:21:49', 'proofs/eLPNKJUGeUG5ESkleo1tL2Qa0VpMHwp7Shet5QJE.png', 1, '2026-05-03 17:21:13', '2026-05-09 01:15:17', '{\"total_payments_made\":0,\"tenancy_db_name\":\"linklearn_org_jade-arts\"}'),
(7, 14, 'University of the Philippines', NULL, 'university-of-the-philippines', NULL, NULL, 'active', NULL, '2026-05-08 23:51:05', 'proofs/3mCwXdfPgtnFK3cqHq0ZmCNam9ZGHbfw884XUUlA.png', 1, '2026-05-08 23:45:07', '2026-05-09 01:05:32', '{\"total_payments_made\":1,\"tenancy_db_name\":\"linklearn_org_university-of-the-philippines\"}'),
(8, 15, 'San Isidro College', NULL, 'san-isidro-college', NULL, NULL, 'active', NULL, '2026-05-09 01:10:41', 'proofs/yZFVrOiMDN2zv0N9xk1OeeYAa9oOwN3IgRWuC85q.jpg', 1, '2026-05-09 01:10:22', '2026-05-09 01:15:17', '{\"total_payments_made\":0,\"tenancy_db_name\":\"linklearn_org_san-isidro-college\"}');

-- --------------------------------------------------------

--
-- Table structure for table `organization_user`
--

CREATE TABLE `organization_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization_user`
--

INSERT INTO `organization_user` (`id`, `user_id`, `organization_id`, `created_at`, `updated_at`) VALUES
(1, 7, 1, '2026-04-29 22:44:03', '2026-04-29 22:44:03'),
(2, 6, 4, '2026-04-29 22:44:03', '2026-04-29 22:44:03');

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
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tutor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `fee` decimal(8,2) NOT NULL DEFAULT 200.00,
  `room_link` varchar(255) DEFAULT NULL,
  `status` enum('open','full','done','archived') DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_user`
--

CREATE TABLE `room_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved') NOT NULL DEFAULT 'approved'
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
('0kSMNZ9eJ6i2wUAbsCCMfRyuDU8Wyblp6jx8c1fm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR2k3Z01NQUJscER5U3BtNXowQVg4UURCd0ZkcmJBR3ZCQ2s2c2E0USI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NDIzMCI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778564231),
('0LjnyNphaPadQ3Rt4VhhtwBebZ0u8miEbwTeLg9q', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiemo4SU80V0ZWRXlCYVpxN2FxN05VSXBHaUs4alBxYWNwbmtnaEd5biI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1NzoiaHR0cDovL2NlbnRyYWwtbWluZGFuYW8tdW5pdmVyc2l0eS5sb2NhbGhvc3Q6ODAwMC9yb29tcy8zIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTc6Imh0dHA6Ly9jZW50cmFsLW1pbmRhbmFvLXVuaXZlcnNpdHkubG9jYWxob3N0OjgwMDAvcm9vbXMvMyI7czo1OiJyb3V0ZSI7czoxNDoib3JnLnJvb21zLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo3O30=', 1778564281),
('2d7to12OsHxhOhPHYPwH636kt1SiNX3fbGxDSE9Q', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSlhkaUNLOXQxZlJVdllKY2k1aTY3ZWt0WnFzQ2VGbk1ING1jbDJwbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NDI5OCI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778564299),
('4a2IAg7kRfCy3OjiY3FivdZ2b0FaZNk0BvSgUYNP', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibTJOUmlzYkwwMGszdlNtMG5nN25DSTZya3FKejA4Mms2d2RyN0JQVyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjc7fQ==', 1778577087),
('7KRt0VqGWnXDFYj76RPHSxTsms0rV8cKiiptwOKT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY3VsUVRWRXpTQkZwVXMzMjljTzJCdW4zUUo3d2lqMnE4ZlVmcGowOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NTI4NCI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778565285),
('8GSh384KcbGNt1q40LN1jz3WqaKFlAOMyC5vvSi0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHMxR0pZaHhjWWtWUnlhMFB3QVFxcUdPc3piMG56cFhZRkdSMDBwSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvc3RpP3Y9MTc3ODU2NjY3OSI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566681),
('9sLfhWlPZtid4RXkqhX7cmWysUtFLN56rFUAPyeZ', 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRjVuS0ZLdndMaGpHdjk4a0JWWGROanMzazBEZHdGNTN2N0xOUmN5ViI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTE7fQ==', 1778566826),
('AFRg12h8eg61cRfqjboGJNDCapHZ18K84CVno4xN', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUEIwdXAyUWpnOVhIeUpzdGxxWHZFcEhWejVGQ1ZuNWRrMmQ4b0J5ZyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NjtzOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1NToiaHR0cDovL2J1a2lkbm9uLXN0YXRlLXVuaXZlcnNpdHkubG9jYWxob3N0OjgwMDAvcm9vbXMvMSI7czo1OiJyb3V0ZSI7czoxNDoib3JnLnJvb21zLnNob3ciO319', 1778566667),
('ATC7rGN4ZBuzQIy8KEkVpZbRW4t0pkTarwX5sYEO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMW9QNXJQNUlQMlZLdlRubTVmUks3RTRWaFJOanVhRG9iZG1hc3dJUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NjQxMCI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566411),
('axoN3Y2PQox3gytYkOFw3Zr9qw0UFOYmtHisbSkG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUGJFb0l5WlB5RWNVZEtKWlltQ25XMHFkQmVRZGtoVWFrSFBHVFU2VCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NjczNCI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566735),
('AzqYwN5DAHrvz9xwzbnY89rceSp3PxIkKO2v1kuP', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUVhwdG82cFZSMDV2Rmp4ckJNdzFGUVJERUVXRlAwQUxkT3paWTBqNCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1NzoiaHR0cDovL2NlbnRyYWwtbWluZGFuYW8tdW5pdmVyc2l0eS5sb2NhbGhvc3Q6ODAwMC9yb29tcy8zIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTc6Imh0dHA6Ly9jZW50cmFsLW1pbmRhbmFvLXVuaXZlcnNpdHkubG9jYWxob3N0OjgwMDAvcm9vbXMvMyI7czo1OiJyb3V0ZSI7czoxNDoib3JnLnJvb21zLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1778566734),
('b0nIb3WkJdZYZNeNLOqYR1tmQ1eVTBkRNDqCinvW', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicmx4ZWFKR3MyWWxSMTF5QWFvbmF5TTd2UnVmRjdsM01iQng0ZHhhZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NzY3OSI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778567680),
('bF5HEia9jrQelNXh7kuE7mf2QhsQD1vvV3VMDjF5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoib2M4bUh2bjEyeVpLVXkya3NEaVNhT2k5NVJyN0prSHBIT3hBaXdvVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566442),
('BJPtuPy4bmKFq2emvydlynRqD5ih8krEucrKOaCc', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieUJKcHlsa3hNSVNXTXR2UDZBVldNaEN1NFJJckswN0VKTUVMNk1mRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NDI5MCI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778564291),
('cTuFHVHM6HBIZYFWwHidY99O9B0aL06KLDD3kduH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOXZxMk9KY2V2bE5zSkdiRDluNWpvNUFUcjVUZkNxWmoyZEV2WjZ6aCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NjcyMiI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566723),
('dPGi4y70SLLU1kapBFXuPIc0GH4uKm3JUdStJc0Y', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia09DZ3ZCSnJlcVJydDlKajdBT1VOb0xManBUTmwzeG5oc1k1QkZ2MyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvc3RpP3Y9MTc3ODU2NTM3NyI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778565378),
('E7dDYrkAcXyz0J1FshpsuPqNeShtPFeai3Uk8iCv', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYTlZOHZWTWxEZzVRUXRWWFBQaW9YWndzdzNmTHR4bDRsWXdyc0JTdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NDMyNiI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778564330),
('FPiBdI6m7qU8K7O7Nt5Y92p1KR5pIEpADmHu4dNz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY2NJdnJESFp4N3dqdFlDSk9TTUx1WmVGRHhaYjJkQ3h6MlhpVnBCdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NjcwMyI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566704),
('FSONuM7j5f2pDY52u3Y7WA256cMPsOUO7pRnopVp', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTks5QnN4TUkwZHVzeHAxWUV6WG5YWGVIakZGVHBVSnhZaUlzOGlSUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6ODtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1OToiaHR0cDovL2NlbnRyYWwtbWluZGFuYW8tdW5pdmVyc2l0eS5sb2NhbGhvc3Q6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTk6Im9yZy5hZG1pbi5kYXNoYm9hcmQiO319', 1778567695),
('gnsZfEqaWvJNsdJueUEyG0UMJt9vGNkm9YDD2mqW', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOENkZmdKVWlVcE5la2pFa0VOTFdvZVdXUk1SZTE0d3RNTE1NMVVSeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5IjtzOjU6InJvdXRlIjtzOjE1OiJvcmcuZ2Nhc2guc2VydmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778563740),
('HPsfMn3xgGnevUMsdSM71Vk7xTvNcpAF5h26ZxmO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTTZ3YmpDMFRuaG1oMUxxWjViY25FZ0d5cGh1RzdoSXVjRWtaalkwcCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566457),
('J5WpLeLJ130c9HrDfmDfS0G0hlIZxK7DOm1Scbqi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicTBTVXlaSVZWdGsxc1JIWEVjem1zZWdkRFlDWTRKeXp2eWs5NWFCbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvYnVraWRub24tc3RhdGUtdW5pdmVyc2l0eT92PTE3Nzg1NjY1ODEiO3M6NToicm91dGUiO3M6MTU6Im9yZy5nY2FzaC5zZXJ2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778566582),
('j9sQnMFcYgqqeTcO4hw5FRYZ9SPvwzjq3qcWTyI3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRXdWaGJIbWNJa29uUWlJc01VT0p5WnVKdkt4RjFRTUNod21ycXRFeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvYnVraWRub24tc3RhdGUtdW5pdmVyc2l0eT92PTE3Nzg1NjY1MTkiO3M6NToicm91dGUiO3M6MTU6Im9yZy5nY2FzaC5zZXJ2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778566520),
('jD0cVAad4ZXYNsYXXlXX37GIQqh6Cg3qWjRkl8l2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia0VvV0xTc3Q5YmV5NVBRUXAwMGFvaUlwYzM5eXJ2SEljeEpjOUxpYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NDI5MiI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778564293),
('JvVqgPwjK7RHQ7vq1wUAUGaIjaDaYsNjUmZzDMEr', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic20weHlidTJvTUhjc1Y4NE9JVFN6a1NPa2FVWDZHSVBSNWpMYzhZeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5IjtzOjU6InJvdXRlIjtzOjE1OiJvcmcuZ2Nhc2guc2VydmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778563580),
('kdN7GjfSCV4ocUjTvFp5vBU46w7AGwzcPjQ9Nlmg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMTYydG9EcFVnVm5ZcGhIWjhQdFBLZERXVEplcjRSQnlKWFpxZFNyZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvYnVraWRub24tc3RhdGUtdW5pdmVyc2l0eT92PTE3Nzg1NjY2NjciO3M6NToicm91dGUiO3M6MTU6Im9yZy5nY2FzaC5zZXJ2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778566669),
('L6Fmhu9CYvwtbyfIY7hJ9ib8ZkkKUxji624kN5wP', 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNmFoS056RzJFbjVJallheklTWTIzRFpHNGNGaDk1NTRrYUJQTnYzMyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTE7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9zdGkubG9jYWxob3N0OjgwMDAvcm9vbXMvMyI7czo1OiJyb3V0ZSI7czoxNDoib3JnLnJvb21zLnNob3ciO319', 1778566782),
('lTYfptj3iDrl8BD6uryVrCVbPmKb2gFm5Ji3IOf5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia0pMZ2Jka01RaGozM0pBVjVLbHE4V2U3Rjh2SXR3Mk5HM2VsbkU3WiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NDI0MiI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778564243),
('m1C4c8bqOHx9B2Tugf8Hgc5tXdl8LXlj9LuBODyq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia0VpN1lvd1luRk5LV1IwTnpRU3JySHZRdUtINUNqOXdPZUUzcnJDSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvYnVraWRub24tc3RhdGUtdW5pdmVyc2l0eT92PTE3Nzg1NjY0OTYiO3M6NToicm91dGUiO3M6MTU6Im9yZy5nY2FzaC5zZXJ2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778566497),
('mCGVZlirLtGAWpN9xx6hGfXCGPtxOcShd97TIyH2', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibVYySXJxNXliREtmUWhMcFlQcTNEekVHSlZjZHBLd0ZXd2NEYUY1SCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NztzOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1NToiaHR0cDovL2J1a2lkbm9uLXN0YXRlLXVuaXZlcnNpdHkubG9jYWxob3N0OjgwMDAvcm9vbXMvMSI7czo1OiJyb3V0ZSI7czoxNDoib3JnLnJvb21zLnNob3ciO319', 1778566581),
('noLsbZEa3chobgD0H1utzgyYWi8CUddQnmVknLDK', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVXpDeWN0WThkem5LNTJHRFlHeW1FMmQxMEhiOWpiZGVtTlY4dTEzRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ub3RpZmljYXRpb25zIjtzOjU6InJvdXRlIjtzOjE5OiJub3RpZmljYXRpb25zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Nzt9', 1778566870),
('NRVjJF4XAca3UZyhmgmmwi3lotgEGB5baB1P2inH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUXFGcDVnVXZzcEV5dXpzam1zVHNDRXB5MEo1M3lvRFJDYkFoWGhONSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvc3RpP3Y9MTc3ODU2NjQyNCI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566425),
('oCtE407mhdduFMCiwhMRkQrLnWOTkT4wr98heswG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0tVSjAycGJwWkpwdW9SNkljS3BZeml5WWlqUFBGQXBTb3lOMFQxMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NzY5MSI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778567692),
('odu7UvaWM1GF5a1jDSVgblb6ilqKsVn2G1PCWupO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUkhMQU95dWhNdUhKUFQ3MGJCOVNWWER0VmRjdzJ2UkRhOVg5VmIyNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NjQwMCI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566401),
('OMy2VDN0MZu1Dx6TqK9oN7SiRbz2aSNtSkEbPopB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidm5XZ1hyTVF3cDAyYUhsWVVaRGtQVTRUUkM4aFhkYjZLYXJZZVpFUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5IjtzOjU6InJvdXRlIjtzOjE1OiJvcmcuZ2Nhc2guc2VydmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778563787),
('qmMw7V9XfghvhvRQdqW5bECnT1ayPXle7YK6y9iG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTVNKeEwyV3R5Y3pXRUtpZUV0eWJodGRQVjBNM1dMbXZQRTdicnh2cCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NDE3MCI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778564170),
('QN2dFjOdPYj7MDuMI2WVTbVOLhtJjpMuoLwvuk8z', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN2NlelBGUlJacERRbU5VWEQ2R2NPNUEzazJOVUFtREY5NUpFUlo3eSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5IjtzOjU6InJvdXRlIjtzOjE1OiJvcmcuZ2Nhc2guc2VydmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778563696),
('RGfn9m2X2H7oGyIBjHDabTO2VxyglLGjHLj7su33', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUkV5VDZyUWpPdmxTSDlpNnh3SXVyQUxZQjdJVUZoOUkyakN1cWFVRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NDMxNCI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778564314),
('Shmv4blC59sV6T6FOoBpCJ6FG0pBfBoYLjN3yYjw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib2wzZmd1QWlDb1Vha1g2RVhxVW9WbVBYTWtaVFRpQjR3djIxY1NWWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NzY5NSI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778567695),
('SIx6wdJVZQ47bStU4oKpfEpM5iOkLmgul7zQTe1i', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT2I4c3lyZVF3NmVHd0JvdjBsdFNiU3p0TmpuN0Jnbktub0k5d0sxTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NDI4MSI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778564282),
('SO4E8aSVp0Be1YfHoLxWQlogPEo3Awy28CJ8kbCr', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiM0F3eEdQakFzUk1yaFlhUGlkbXE1emI5dkZySXNyQkM0YlM4b2NIciI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vb3JnYW5pemF0aW9ucyI7czo1OiJyb3V0ZSI7czoxOToiYWRtaW4ub3JnYW5pemF0aW9ucyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1778567663),
('SWLQm48DhlPjlszjHaRrG4h04VDE2AxAOQXUNV9N', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTEZDRWlNTDNxTTBXeTdBV3Y3THh0eGFYSExkWktnNmwyMXlUR3ZNRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvc3RpP3Y9MTc3ODU2NjY4OSI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566690),
('T17qjF4ewXR8e6FDhCk09YAjPENZgOo0TnMFJuJW', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS0s3UGt4Qkp4bHljVGRsWW1EOFF2U0hTc3NtWkFMWEwyRDdHWFlzcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvYnVraWRub24tc3RhdGUtdW5pdmVyc2l0eT92PTE3Nzg1NjY1NTMiO3M6NToicm91dGUiO3M6MTU6Im9yZy5nY2FzaC5zZXJ2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778566555),
('trZbr0xK6AttOri3j2OdP2WfuU21DEEmK583DWJW', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZkwxVXRmWUZ3SHExM3M3V2xaQUQ1amN2akdVNW8yYlhUelExN2tWbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NDEyNyI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778564128),
('V7mrsQlnRoGOEQpX5yv6uWh4Tkw7K1haMA7aWdZB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiNUV1eTZUbEFKQW9JS243SlNVYjFaOWJ3WGxFSzhJQU1mMHdPS2JyciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778567570),
('vFOUBqn4ia7UC9BIUruKFQBh5en9x63BauKs3Cao', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZGdpb21veTZONE5Va2VreENISnVKQ00xRkk1aFozVjMyeTFEQm1BYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NjMzOCI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566339),
('W5PhQsLoqk6jvrkERHQiSArEcjdRXyceezywVMt9', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWGMwTERVMmNxZnRrTGIwSllCdVJrcTFFOWVVRWl4WnhSeDNNZE9UbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6ODt9', 1778567676),
('WMqwLRwpmefWdxZHI4FIIxhGiwWwj429rGL7y70R', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkpOQ3VXNVIySHdxeGhwcnlGa1hqenFKS0NQdWhDSW5OTmNGcVYweSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvc3RpP3Y9MTc3ODU2Njc4MiI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566783),
('ww3osnVr502mUru4U9YnX3jzqucJcsAnufvpT6Xh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ0huTDVTc1ZKNFpMdW1QN0JGNTZxMUNlWDVXMWVQMW93eUxqREFjRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NDE5MSI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778564192),
('xtBfqEnv6pbiV9gKBtfrRMQeYfSau5kFRglWibL0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVUt4OVNCclFDelIzeGs4eHZWOUo5TFQ5MVdTYUhyNjJUQlFZbk9PNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvY2VudHJhbC1taW5kYW5hby11bml2ZXJzaXR5P3Y9MTc3ODU2NzY4MyI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778567684),
('Y8j2przSYPzqKGstn7Wo5fUv2Y8gGZGUumFx5sdL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaEFIanFtN2prb0c4b3JGV2RJSmRyRlNtd1o0Q3JWS2dEN1dlam9uVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvYnVraWRub24tc3RhdGUtdW5pdmVyc2l0eT92PTE3Nzg1NjY1NzUiO3M6NToicm91dGUiO3M6MTU6Im9yZy5nY2FzaC5zZXJ2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778566576),
('zJ1rGFOhdw8zi47kb5WGIVsDnboMU8jdk4YtTM07', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibUk2Z0RzM2l0SzFHajFOdkhJM1NWOVU2Z3J6SEJJREE2VzFVbzNYWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvYnVraWRub24tc3RhdGUtdW5pdmVyc2l0eT92PTE3Nzg1NjY1NDIiO3M6NToicm91dGUiO3M6MTU6Im9yZy5nY2FzaC5zZXJ2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778566543),
('ZjbCLpKCBamCQq7oaMJTWyxzmY2V10FrmZEA5wmZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoienAxVm1UcDBjbW1DR3NQbGV0NkJ2Y0toV1dyeGhvb3BhZXgzUkVDNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vcmctcXIvc3RpP3Y9MTc3ODU2NjM4MyI7czo1OiJyb3V0ZSI7czoxNToib3JnLmdjYXNoLnNlcnZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778566384);

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_messages`
--

CREATE TABLE `support_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `support_ticket_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_messages`
--

INSERT INTO `support_messages` (`id`, `support_ticket_id`, `user_id`, `message`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 'Nganu mani inyung kuan nga kuan maniiii??????', '2026-05-04 15:40:05', '2026-05-04 15:40:05'),
(2, 1, 1, 'Sagdi rana ahh.', '2026-05-05 03:35:01', '2026-05-05 03:35:01'),
(3, 2, 7, 'gvjgtfhgdyfjhvhg', '2026-05-05 03:41:30', '2026-05-05 03:41:30'),
(4, 3, 5, 'sahbchdbcbiedc', '2026-05-06 15:08:53', '2026-05-06 15:08:53'),
(5, 3, 1, 'nhbquhxbugeb', '2026-05-06 15:09:30', '2026-05-06 15:09:30'),
(6, 4, 8, 'khbajhcbuaehvcjhjcae', '2026-05-09 21:33:30', '2026-05-09 21:33:30'),
(7, 4, 1, 'fehbehfads', '2026-05-09 21:41:01', '2026-05-09 21:41:01'),
(8, 4, 8, 'fsbatrtghfyt', '2026-05-09 21:41:27', '2026-05-09 21:41:27'),
(9, 5, 7, 'dcn ejvn dkj ev', '2026-05-09 21:58:09', '2026-05-09 21:58:09'),
(10, 5, 1, 'sajchebcjdh cahefbn', '2026-05-09 21:58:39', '2026-05-09 21:58:39'),
(11, 5, 1, 'sajchebcjdh cahefbn', '2026-05-09 21:58:41', '2026-05-09 21:58:41'),
(12, 5, 7, 'hhbkaswhb', '2026-05-09 21:58:58', '2026-05-09 21:58:58');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `user_id`, `organization_id`, `subject`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 4, 'Payment not successful', 'open', '2026-05-04 15:40:05', '2026-05-04 15:40:05'),
(2, 7, 1, 'Payment not successful', 'closed', '2026-05-05 03:41:30', '2026-05-06 15:07:51'),
(3, 5, 4, 'Okay na ang payment', 'open', '2026-05-06 15:08:53', '2026-05-06 15:08:53'),
(4, 8, 5, 'Sample Issue', 'open', '2026-05-09 21:33:30', '2026-05-09 21:33:30'),
(5, 7, 1, 'Sample from student', 'open', '2026-05-09 21:58:09', '2026-05-09 21:58:09');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_user_impersonation_tokens`
--

CREATE TABLE `tenant_user_impersonation_tokens` (
  `token` varchar(128) NOT NULL,
  `tenant_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `auth_guard` varchar(255) NOT NULL,
  `redirect_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'student',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `organization_id`, `role`, `created_at`, `updated_at`, `google_id`, `avatar`) VALUES
(1, 'Super Admin', 'admin@example.com', NULL, '$2y$12$6Ex7zSO18TqEtCOZ0wSu/ufG4fpng3yVVBBFdupZw9Bn7jfS9kefi', NULL, NULL, 'super_admin', '2026-04-27 20:59:27', '2026-05-11 22:32:19', NULL, NULL),
(3, 'Bukidnon State University', 'bukidnonstateuniversity@gmail.com', NULL, '$2y$12$66t8vsuYXqQWaKpaJ0loVeYmoDNk4H9pnGs.xPemeL6ZWrYCAUAey', NULL, NULL, 'org_admin', '2026-04-28 17:43:17', '2026-05-03 16:35:48', NULL, NULL),
(5, 'STI', 'sti@gmail.com', NULL, '$2y$12$cuXfTdM8xtJvaue.5wTdXu6PRHnbJOXxjDIB8A70y/Ay9ye9ULS4K', NULL, 4, 'org_admin', '2026-04-28 19:57:42', '2026-05-03 16:35:48', NULL, NULL),
(6, 'Wendel Jade Callado', '2301104851@student.buksu.edu.ph', NULL, '$2y$12$xYp/LY45U6oMuxsxI80Rhu8iCxJrKsmAJXf6b8muQxDsn4j1Rh7Vq', NULL, 4, 'teacher', '2026-04-29 14:33:34', '2026-04-29 22:22:23', '111143325910528580470', 'https://lh3.googleusercontent.com/a/ACg8ocJ51vFM8EH-3CnvNp-ds8AiO_HI0f3GJyepI1q4CyIW2f_4tiw=s96-c'),
(7, 'JOSEMARI ROSALIM', '2301105510@student.buksu.edu.ph', NULL, '$2y$12$fnCvsESUC7LEKzZB1lKJYeyDuvdCFR33ktonJpZA4djFL2JACr18K', NULL, 1, 'student', '2026-04-29 14:49:07', '2026-04-29 19:41:45', '116834754320115682909', 'https://lh3.googleusercontent.com/a/ACg8ocIqfhrU2J7uwLv_9UmZSnQN_IzWDrAwmmDtZIiC5YjIjA0G1w=s96-c'),
(8, 'CMU', 'cmu@gmail.com', NULL, '$2y$12$pb8LLX5/opy/YHBDtXDN/.j.F/TE5huTvGLsECLcuvc3P6j5CLy6S', NULL, 5, 'org_admin', '2026-04-29 23:37:19', '2026-05-03 16:35:48', NULL, NULL),
(9, 'Wendel Jade Callado', 'wendeljadecallado90@gmail.com', NULL, '$2y$12$u6wqiX5ozUwBAvzzb3iP9uAuv/8Wa8F/UbHKh8Cc6ULcGwDab665e', NULL, NULL, 'student', '2026-05-03 16:14:05', '2026-05-03 16:14:05', '118024781303399658459', 'https://lh3.googleusercontent.com/a/ACg8ocKrblt2Th-hO8guXi3SciDSPOq_JDqvALhmeyianElluarRCw=s96-c'),
(10, 'Wendel Jade Callado', 'wendeljadecallado@gmail.com', NULL, '$2y$12$cwrXpOWbInnZG5gkbAaw/OAIn7nTaxMLnTTu9ZxMrhMo8FezPph7i', NULL, NULL, 'teacher', '2026-05-03 16:15:06', '2026-05-03 16:35:49', NULL, NULL),
(11, 'Vannex Jade Orcajada', 'vanexjade47@gmail.com', NULL, '$2y$12$4EzSgfHW2cN0KrQpkStU..0II7emT2Jok1M6o9mCrjCV0pMV0V84i', NULL, NULL, 'teacher', '2026-05-03 17:03:01', '2026-05-03 17:03:01', '106712111749082117174', 'https://lh3.googleusercontent.com/a/ACg8ocKpMt7PUqLMDpIboZ4yg97f80B7-OIxLdJrC-Dnkr_zVAGMwFlBWA=s96-c'),
(12, 'Jade Arts', 'jadearts@gmail.com', NULL, '$2y$12$Gn7zRBKw/ttT7jDuDU3Qm.JDeQuBO9G5VHAVdVmNvUClX2w/ba4d.', NULL, 6, 'org_admin', '2026-05-03 17:21:13', '2026-05-09 01:05:45', NULL, NULL),
(13, 'Organization Admin', 'orgadmin@example.com', NULL, '$2y$12$Yd8H70i.dve07hWtzmYYxeQOjWTEaSWpcSi5Tb4J0YDoFWeJWHdzO', NULL, NULL, 'admin', '2026-05-08 23:03:39', '2026-05-11 22:32:20', NULL, NULL),
(14, 'University of the Philippines', 'universityofthephilippines@gmail.com', NULL, '$2y$12$aqCxVPxRDFPL2Wmv6Kr11e3c5Gp1DrIEkjY57BlUEZxw/.XrB5iRG', NULL, 7, 'org_admin', '2026-05-08 23:45:07', '2026-05-08 23:55:09', NULL, NULL),
(15, 'San Isidro College', 'sic@gmail.com', NULL, '$2y$12$ZSvbJH6tg7QNHh7rqyN91O75b.U/62uFw7SEtZHAyAX48hHXdkUnG', NULL, 8, 'org_admin', '2026-05-09 01:10:22', '2026-05-09 01:11:01', NULL, NULL),
(16, 'Erica Casiño', 'ericacasino53@gmail.com', NULL, '$2y$12$N6me2d3oUW5V/hOUJwGAcemNw53jZhcHO6AiourYMhk4jebgHRIyC', NULL, NULL, 'student', '2026-05-09 01:29:39', '2026-05-09 01:29:39', '101379604263880208408', 'https://lh3.googleusercontent.com/a/ACg8ocJoG9-UzHsiqrhSSS3gIEzuFPdedoZ4BYOdV-K9U-ntspgVcjs=s96-c'),
(17, 'Erica Casiño', '2301114354@student.buksu.edu.ph', NULL, '$2y$12$bP5GNzECITlOZm2VBeiA0ONWtQIa.J51bcTwunVlUO.pOjd/6RX3m', NULL, 1, 'student', '2026-05-09 01:33:28', '2026-05-09 01:34:14', '105918229922204229695', 'https://lh3.googleusercontent.com/a/ACg8ocJqScJn-uttB4f5TjiM7dGb-N_q4HcL5EjLFXmWjD2qa1SAn3U=s96-c'),
(19, 'Jm Rosalim', 'josemari143682@gmail.com', NULL, '$2y$12$mdf4UuKWujWvyrL16o1ZdeRTMsC3gQ0e/HLGr81uEmiy2Bg6mHgYe', NULL, NULL, 'teacher', '2026-05-10 09:39:30', '2026-05-10 09:39:30', '115597876616483896598', 'https://lh3.googleusercontent.com/a/ACg8ocK22S2Qdbg1A5PO8gK30r4s6WJ3drrtqwrccC7Wwdv-0tgWeg=s96-c');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_room_id_foreign` (`room_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_room_id_foreign` (`room_id`);

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
-- Indexes for table `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domains_domain_unique` (`domain`),
  ADD KEY `domains_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_room_id_foreign` (`room_id`);

--
-- Indexes for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_purchases_user_id_foreign` (`user_id`),
  ADD KEY `file_purchases_file_id_foreign` (`file_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `organizations_slug_unique` (`slug`),
  ADD KEY `organizations_user_id_foreign` (`user_id`);

--
-- Indexes for table `organization_user`
--
ALTER TABLE `organization_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `organization_user_user_id_organization_id_unique` (`user_id`,`organization_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_organization_id_foreign` (`organization_id`),
  ADD KEY `rooms_tutor_id_foreign` (`tutor_id`);

--
-- Indexes for table `room_user`
--
ALTER TABLE `room_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_user_room_id_foreign` (`room_id`),
  ADD KEY `room_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submissions_activity_id_foreign` (`activity_id`),
  ADD KEY `submissions_student_id_foreign` (`student_id`);

--
-- Indexes for table `support_messages`
--
ALTER TABLE `support_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_messages_support_ticket_id_foreign` (`support_ticket_id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenant_user_impersonation_tokens`
--
ALTER TABLE `tenant_user_impersonation_tokens`
  ADD PRIMARY KEY (`token`),
  ADD KEY `tenant_user_impersonation_tokens_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`),
  ADD KEY `users_organization_id_foreign` (`organization_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `domains`
--
ALTER TABLE `domains`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_purchases`
--
ALTER TABLE `file_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `organization_user`
--
ALTER TABLE `organization_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_user`
--
ALTER TABLE `room_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_messages`
--
ALTER TABLE `support_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `domains`
--
ALTER TABLE `domains`
  ADD CONSTRAINT `domains_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `organizations` (`slug`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD CONSTRAINT `file_purchases_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `file_purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organization_user`
--
ALTER TABLE `organization_user`
  ADD CONSTRAINT `organization_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rooms_tutor_id_foreign` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `room_user`
--
ALTER TABLE `room_user`
  ADD CONSTRAINT `room_user_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_messages`
--
ALTER TABLE `support_messages`
  ADD CONSTRAINT `support_messages_support_ticket_id_foreign` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenant_user_impersonation_tokens`
--
ALTER TABLE `tenant_user_impersonation_tokens`
  ADD CONSTRAINT `tenant_user_impersonation_tokens_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;
--
-- Database: `linklearn_org_bukidnon-state-university`
--
CREATE DATABASE IF NOT EXISTS `linklearn_org_bukidnon-state-university` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `linklearn_org_bukidnon-state-university`;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `allow_late_submissions` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `room_id`, `title`, `description`, `file_path`, `link`, `deadline`, `allow_late_submissions`, `created_at`, `updated_at`) VALUES
(1, 1, 'Activity 1', NULL, 'activities/3vthfK2g3ZIULSnoUOICh02xYrtd1VwqMOd0Sp2M.png', NULL, '2026-04-30 23:59:00', 0, '2026-04-29 19:43:40', '2026-05-09 02:41:57'),
(2, 1, 'Activity 2', NULL, 'activities/7q38brvzCugebGRBMlJCkA1mNfdJ5hVsFuhNYft2.pdf', 'https://www.youtube.com/watch?v=l0wJqJT3gh8&list=RDl0wJqJT3gh8&start_radio=1', '2026-05-08 07:39:00', 1, '2026-05-06 15:39:45', '2026-05-09 02:41:42');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `room_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 'This is a sample announcement', '2026-05-09 07:18:10', '2026-05-09 07:18:10');

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
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 200.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `room_id`, `title`, `file_path`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 'Module 1', 'rooms/files/raz5G6WRWAVHBUFzacxgy8jwMCfw1D3cjzdRUWCJ.docx', 200.00, '2026-04-29 19:44:02', '2026-04-29 19:44:02'),
(2, 1, 'Module 2', 'rooms/files/rZsIXEBKFlb79sx34BghbxV4EuPHRbEMmzICeShC.pdf', 200.00, '2026-05-10 07:31:29', '2026-05-10 07:31:29');

-- --------------------------------------------------------

--
-- Table structure for table `file_purchases`
--

CREATE TABLE `file_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_purchases`
--

INSERT INTO `file_purchases` (`id`, `user_id`, `file_id`, `status`, `proof_of_payment`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 'completed', 'proofs/30dReAyagrq9pIfIqVAwvLY3pcSq432fAs6BXN4x.jpg', '2026-05-03 14:05:42', '2026-05-03 18:28:56'),
(2, 17, 1, 'completed', 'proofs/CocQW1YqCESWMQjXP8QrdwShYPwEsB9NRJEBHpb2.png', '2026-05-09 01:37:47', '2026-05-09 01:38:22');

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
(1, '2026_04_20_033735_create_tenant_users_table', 1),
(2, '2026_04_20_033805_create_tenant_rooms_table', 1),
(3, '2026_04_20_033833_create_tenant_files_and_purchases_tables', 1),
(4, '2026_04_20_033911_create_tenant_activities_and_submissions_tables', 1),
(5, '2026_04_20_034119_create_tenant_room_user_table', 1),
(6, '2026_04_26_000001_create_cache_table', 1),
(7, '2026_04_26_000002_create_jobs_table', 1),
(8, '2026_04_26_000003_create_sessions_table', 1),
(9, '2026_05_09_100653_add_allow_late_submissions_to_tenant_activities_table', 2),
(10, '2026_05_09_103300_add_link_to_tenant_activities_table', 3),
(11, '2026_05_09_142422_create_tenant_announcements_table', 4),
(12, '2026_05_09_234950_add_status_to_tenant_room_user_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tutor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` enum('open','full','done','archived') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `tutor_id`, `subject_name`, `description`, `cover_photo`, `fee`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 'Networking 1', 'This is Networking subject.', 'rooms/covers/zLxHn9Q5r5ANXXm6zJORNj7bYI49nocGW8cnRn77.jpg', 0.00, 'open', '2026-04-29 14:14:12', '2026-05-10 06:29:18');

-- --------------------------------------------------------

--
-- Table structure for table `room_user`
--

CREATE TABLE `room_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved') NOT NULL DEFAULT 'approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_user`
--

INSERT INTO `room_user` (`id`, `room_id`, `user_id`, `created_at`, `updated_at`, `status`) VALUES
(2, 1, 17, NULL, NULL, 'approved'),
(4, 1, 7, NULL, NULL, 'approved');

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
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `activity_id`, `student_id`, `file_path`, `grade`, `feedback`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 'submissions/m2Qu8Kj38W3r2nEe7L9aelYKhLWfKij828Hgvon3.pdf', '100', NULL, '2026-05-06 15:37:30', '2026-05-06 15:38:30'),
(2, 2, 17, 'submissions/34zrWBTP9NOnedDmR3bo0FboYPDbtpcgffQ2P0qc.pdf', '100', NULL, '2026-05-09 02:42:53', '2026-05-09 02:44:22'),
(3, 2, 7, 'submissions/XHrCCiilPShmgXpruksSF8tWNnqJgoOS9h5ATXTy.pdf', '100', NULL, '2026-05-09 02:44:08', '2026-05-09 02:44:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_room_id_foreign` (`room_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_room_id_foreign` (`room_id`);

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
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_room_id_foreign` (`room_id`);

--
-- Indexes for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_purchases_user_id_foreign` (`user_id`),
  ADD KEY `file_purchases_file_id_foreign` (`file_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_tutor_id_foreign` (`tutor_id`);

--
-- Indexes for table `room_user`
--
ALTER TABLE `room_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_user_room_id_foreign` (`room_id`),
  ADD KEY `room_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submissions_activity_id_foreign` (`activity_id`),
  ADD KEY `submissions_student_id_foreign` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `file_purchases`
--
ALTER TABLE `file_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `room_user`
--
ALTER TABLE `room_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD CONSTRAINT `file_purchases_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_user`
--
ALTER TABLE `room_user`
  ADD CONSTRAINT `room_user_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;
--
-- Database: `linklearn_org_central-mindanao-university`
--
CREATE DATABASE IF NOT EXISTS `linklearn_org_central-mindanao-university` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `linklearn_org_central-mindanao-university`;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `allow_late_submissions` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 200.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `room_id`, `title`, `file_path`, `price`, `created_at`, `updated_at`) VALUES
(1, 3, 'Module 1', 'rooms/files/6yoysaER5bX6eYTbfWInGuSOs3IGlfHKBs0ffCfW.pdf', 200.00, '2026-05-10 07:32:36', '2026-05-10 07:32:36'),
(2, 3, 'Module 2', 'rooms/files/r9SWmbTmoVJR950Y9odrgzCVg9rukGB8YJMXThis.pdf', 300.00, '2026-05-10 07:33:00', '2026-05-10 07:33:00'),
(3, 3, 'Module 3', 'rooms/files/HrTP1WcIJgr0mWZmYR6UJo0CjrSTPuNbvAg1iZXd.pdf', 100.00, '2026-05-10 08:38:16', '2026-05-10 08:38:16'),
(4, 3, 'Module 4', 'rooms/files/J2WC1BTuc5OszkW385lSgHS7TQ7Jh6FGCP0QHXRD.pdf', 200.00, '2026-05-10 20:01:27', '2026-05-10 20:01:27');

-- --------------------------------------------------------

--
-- Table structure for table `file_purchases`
--

CREATE TABLE `file_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_purchases`
--

INSERT INTO `file_purchases` (`id`, `user_id`, `file_id`, `status`, `proof_of_payment`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 'completed', 'proofs/l00U96t02cvm9DE7wX0HAAom63lM0fFbfEk8tSqQ.png', '2026-05-10 07:33:59', '2026-05-10 08:06:10'),
(2, 7, 2, 'completed', 'proofs/xPJdKCnzEELPh4zjth7ZRyC0jGZIDST5FyE8e2c4.png', '2026-05-10 08:07:48', '2026-05-10 08:08:25'),
(3, 7, 3, 'completed', 'proofs/M97Dr1IEaIccg6PZXFkOxq8uffefMopCq3rK0BAD.jpg', '2026-05-10 09:35:15', '2026-05-10 09:35:41'),
(4, 7, 4, 'completed', 'proofs/t8ql7GoiYaZGehnMXQyZv099houT74z3ytXgkWWH.jpg', '2026-05-11 21:37:58', '2026-05-11 22:18:53');

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
(1, '2026_04_20_033805_create_tenant_rooms_table', 1),
(2, '2026_04_20_033833_create_tenant_files_and_purchases_tables', 1),
(3, '2026_04_20_033911_create_tenant_activities_and_submissions_tables', 1),
(4, '2026_04_20_034119_create_tenant_room_user_table', 1),
(5, '2026_04_26_000001_create_cache_table', 1),
(6, '2026_04_26_000002_create_jobs_table', 1),
(7, '2026_04_26_000003_create_sessions_table', 1),
(8, '2026_05_09_100653_add_allow_late_submissions_to_tenant_activities_table', 2),
(9, '2026_05_09_103300_add_link_to_tenant_activities_table', 3),
(10, '2026_05_09_142422_create_tenant_announcements_table', 4),
(11, '2026_05_09_234950_add_status_to_tenant_room_user_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tutor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` enum('open','full','done','archived') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `tutor_id`, `subject_name`, `description`, `cover_photo`, `fee`, `status`, `created_at`, `updated_at`) VALUES
(1, 10, 'Classroom 1', 'Classroom ni', 'rooms/covers/4f3rJCbksjQ6R0KmazPLBS7YCZ4pbmNdDTCheKT7.png', 0.00, 'open', '2026-05-03 16:37:06', '2026-05-03 16:58:47'),
(2, 11, 'Multimedia', 'hsd', 'rooms/covers/8dlUb2JDW2Foy2fVv6UqoygZ1a1QMHkWjJGR8vUq.jpg', 0.00, 'open', '2026-05-03 17:03:50', '2026-05-03 17:04:45'),
(3, 6, 'Graphic Design', 'This is a Graphic Design Subject', 'rooms/covers/aOTOJYtHXPhA2P3KQmUIHQtBKZ870GwnB4j0NPR2.png', 0.00, 'open', '2026-05-06 15:10:46', '2026-05-06 15:11:23'),
(4, 11, 'Photoshop', 'A comprehensive guide to Adobe Photoshop. Designed for students looking to improve their photo manipulation, digital painting, and design skills.', 'rooms/covers/R49TRmnrnnXfgYzxBDCooV1Le6aj9FVqnMpxboNB.png', 0.00, 'open', '2026-05-09 20:45:34', '2026-05-09 20:47:03');

-- --------------------------------------------------------

--
-- Table structure for table `room_user`
--

CREATE TABLE `room_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved') NOT NULL DEFAULT 'approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_user`
--

INSERT INTO `room_user` (`id`, `room_id`, `user_id`, `created_at`, `updated_at`, `status`) VALUES
(2, 2, 7, NULL, NULL, 'approved'),
(5, 4, 7, NULL, NULL, 'approved'),
(6, 3, 7, NULL, NULL, 'approved');

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
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_room_id_foreign` (`room_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_room_id_foreign` (`room_id`);

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
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_room_id_foreign` (`room_id`);

--
-- Indexes for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_purchases_file_id_foreign` (`file_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_tutor_id_index` (`tutor_id`);

--
-- Indexes for table `room_user`
--
ALTER TABLE `room_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_user_room_id_foreign` (`room_id`),
  ADD KEY `room_user_user_id_index` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submissions_activity_id_foreign` (`activity_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `file_purchases`
--
ALTER TABLE `file_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `room_user`
--
ALTER TABLE `room_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD CONSTRAINT `file_purchases_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_user`
--
ALTER TABLE `room_user`
  ADD CONSTRAINT `room_user_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;
--
-- Database: `linklearn_org_jade-arts`
--
CREATE DATABASE IF NOT EXISTS `linklearn_org_jade-arts` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `linklearn_org_jade-arts`;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `allow_late_submissions` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 200.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_purchases`
--

CREATE TABLE `file_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '2026_04_20_033805_create_tenant_rooms_table', 1),
(2, '2026_04_20_033833_create_tenant_files_and_purchases_tables', 1),
(3, '2026_04_20_033911_create_tenant_activities_and_submissions_tables', 1),
(4, '2026_04_20_034119_create_tenant_room_user_table', 1),
(5, '2026_04_26_000001_create_cache_table', 1),
(6, '2026_04_26_000002_create_jobs_table', 1),
(7, '2026_04_26_000003_create_sessions_table', 1),
(8, '2026_05_09_100653_add_allow_late_submissions_to_tenant_activities_table', 2),
(9, '2026_05_09_103300_add_link_to_tenant_activities_table', 3),
(10, '2026_05_09_142422_create_tenant_announcements_table', 4),
(11, '2026_05_09_234950_add_status_to_tenant_room_user_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tutor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` enum('open','full','done','archived') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_user`
--

CREATE TABLE `room_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved') NOT NULL DEFAULT 'approved'
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

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_room_id_foreign` (`room_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_room_id_foreign` (`room_id`);

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
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_room_id_foreign` (`room_id`);

--
-- Indexes for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_purchases_file_id_foreign` (`file_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_tutor_id_index` (`tutor_id`);

--
-- Indexes for table `room_user`
--
ALTER TABLE `room_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_user_room_id_foreign` (`room_id`),
  ADD KEY `room_user_user_id_index` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submissions_activity_id_foreign` (`activity_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_purchases`
--
ALTER TABLE `file_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_user`
--
ALTER TABLE `room_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD CONSTRAINT `file_purchases_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_user`
--
ALTER TABLE `room_user`
  ADD CONSTRAINT `room_user_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;
--
-- Database: `linklearn_org_san-isidro-college`
--
CREATE DATABASE IF NOT EXISTS `linklearn_org_san-isidro-college` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `linklearn_org_san-isidro-college`;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `allow_late_submissions` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 200.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_purchases`
--

CREATE TABLE `file_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '2026_04_20_033805_create_tenant_rooms_table', 1),
(2, '2026_04_20_033833_create_tenant_files_and_purchases_tables', 1),
(3, '2026_04_20_033911_create_tenant_activities_and_submissions_tables', 1),
(4, '2026_04_20_034119_create_tenant_room_user_table', 1),
(5, '2026_04_26_000001_create_cache_table', 1),
(6, '2026_04_26_000002_create_jobs_table', 1),
(7, '2026_04_26_000003_create_sessions_table', 1),
(8, '2026_05_09_100653_add_allow_late_submissions_to_tenant_activities_table', 2),
(9, '2026_05_09_103300_add_link_to_tenant_activities_table', 3),
(10, '2026_05_09_142422_create_tenant_announcements_table', 4),
(11, '2026_05_09_234950_add_status_to_tenant_room_user_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tutor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` enum('open','full','done','archived') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_user`
--

CREATE TABLE `room_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved') NOT NULL DEFAULT 'approved'
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

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_room_id_foreign` (`room_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_room_id_foreign` (`room_id`);

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
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_room_id_foreign` (`room_id`);

--
-- Indexes for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_purchases_file_id_foreign` (`file_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_tutor_id_index` (`tutor_id`);

--
-- Indexes for table `room_user`
--
ALTER TABLE `room_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_user_room_id_foreign` (`room_id`),
  ADD KEY `room_user_user_id_index` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submissions_activity_id_foreign` (`activity_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_purchases`
--
ALTER TABLE `file_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_user`
--
ALTER TABLE `room_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD CONSTRAINT `file_purchases_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_user`
--
ALTER TABLE `room_user`
  ADD CONSTRAINT `room_user_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;
--
-- Database: `linklearn_org_sti`
--
CREATE DATABASE IF NOT EXISTS `linklearn_org_sti` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `linklearn_org_sti`;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `allow_late_submissions` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 200.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_purchases`
--

CREATE TABLE `file_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '2026_04_20_033735_create_tenant_users_table', 1),
(2, '2026_04_20_033805_create_tenant_rooms_table', 1),
(3, '2026_04_20_033833_create_tenant_files_and_purchases_tables', 1),
(4, '2026_04_20_033911_create_tenant_activities_and_submissions_tables', 1),
(5, '2026_04_20_034119_create_tenant_room_user_table', 1),
(6, '2026_04_26_000001_create_cache_table', 1),
(7, '2026_04_26_000002_create_jobs_table', 1),
(8, '2026_04_26_000003_create_sessions_table', 1),
(9, '2026_05_09_100653_add_allow_late_submissions_to_tenant_activities_table', 2),
(10, '2026_05_09_103300_add_link_to_tenant_activities_table', 3),
(11, '2026_05_09_142422_create_tenant_announcements_table', 4),
(12, '2026_05_09_234950_add_status_to_tenant_room_user_table', 5);

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
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tutor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` enum('open','full','done','archived') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `tutor_id`, `subject_name`, `description`, `cover_photo`, `fee`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 'Programming', 'Programming nganiii', 'rooms/covers/s8crbe2ClzemDwvrYlpYdn7wlzEilgCfmS56KKQd.jpg', 0.00, 'open', '2026-04-29 21:58:52', '2026-05-03 15:36:47'),
(2, 6, 'Networking', 'Networking ni', 'rooms/covers/8505ZWKDqK5R78mk85O9V4nw54nbOiQ6QhkuDTXe.jpg', 0.00, 'open', '2026-04-29 22:48:21', '2026-04-29 22:48:21'),
(3, 11, 'Sample Classroom', 'sample', 'rooms/covers/iVw6k0gLBl2hslX94P6HIveePWIOhzwTrhdV6Hpm.jpg', 0.00, 'open', '2026-05-08 23:07:53', '2026-05-10 09:00:01');

-- --------------------------------------------------------

--
-- Table structure for table `room_user`
--

CREATE TABLE `room_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved') NOT NULL DEFAULT 'approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_user`
--

INSERT INTO `room_user` (`id`, `room_id`, `user_id`, `created_at`, `updated_at`, `status`) VALUES
(2, 2, 7, NULL, NULL, 'approved'),
(3, 3, 7, NULL, NULL, 'pending');

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
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'student',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_room_id_foreign` (`room_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_room_id_foreign` (`room_id`);

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
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_room_id_foreign` (`room_id`);

--
-- Indexes for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_purchases_user_id_foreign` (`user_id`),
  ADD KEY `file_purchases_file_id_foreign` (`file_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_tutor_id_foreign` (`tutor_id`);

--
-- Indexes for table `room_user`
--
ALTER TABLE `room_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_user_room_id_foreign` (`room_id`),
  ADD KEY `room_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submissions_activity_id_foreign` (`activity_id`),
  ADD KEY `submissions_student_id_foreign` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_purchases`
--
ALTER TABLE `file_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `room_user`
--
ALTER TABLE `room_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD CONSTRAINT `file_purchases_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `file_purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_user`
--
ALTER TABLE `room_user`
  ADD CONSTRAINT `room_user_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Database: `linklearn_org_university-of-the-philippines`
--
CREATE DATABASE IF NOT EXISTS `linklearn_org_university-of-the-philippines` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `linklearn_org_university-of-the-philippines`;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `allow_late_submissions` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 200.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_purchases`
--

CREATE TABLE `file_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '2026_04_20_033805_create_tenant_rooms_table', 1),
(2, '2026_04_20_033833_create_tenant_files_and_purchases_tables', 1),
(3, '2026_04_20_033911_create_tenant_activities_and_submissions_tables', 1),
(4, '2026_04_20_034119_create_tenant_room_user_table', 1),
(5, '2026_04_26_000001_create_cache_table', 1),
(6, '2026_04_26_000002_create_jobs_table', 1),
(7, '2026_04_26_000003_create_sessions_table', 1),
(8, '2026_05_09_100653_add_allow_late_submissions_to_tenant_activities_table', 2),
(9, '2026_05_09_103300_add_link_to_tenant_activities_table', 3),
(10, '2026_05_09_142422_create_tenant_announcements_table', 4),
(11, '2026_05_09_234950_add_status_to_tenant_room_user_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tutor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` enum('open','full','done','archived') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_user`
--

CREATE TABLE `room_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved') NOT NULL DEFAULT 'approved'
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

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_room_id_foreign` (`room_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_room_id_foreign` (`room_id`);

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
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_room_id_foreign` (`room_id`);

--
-- Indexes for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_purchases_file_id_foreign` (`file_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_tutor_id_index` (`tutor_id`);

--
-- Indexes for table `room_user`
--
ALTER TABLE `room_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_user_room_id_foreign` (`room_id`),
  ADD KEY `room_user_user_id_index` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submissions_activity_id_foreign` (`activity_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_purchases`
--
ALTER TABLE `file_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_user`
--
ALTER TABLE `room_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_purchases`
--
ALTER TABLE `file_purchases`
  ADD CONSTRAINT `file_purchases_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_user`
--
ALTER TABLE `room_user`
  ADD CONSTRAINT `room_user_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
