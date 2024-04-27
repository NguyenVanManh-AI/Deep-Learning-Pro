-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.32 - MySQL Community Server - GPL
-- Server OS:                    Linux
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for linebot
CREATE DATABASE IF NOT EXISTS `linebot` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `linebot`;

-- Dumping structure for table linebot.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.admins: ~0 rows (approximately)
DELETE FROM `admins`;

-- Dumping structure for table linebot.broadcasts
CREATE TABLE IF NOT EXISTS `broadcasts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `line_user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_at` timestamp NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `broadcasts_sender_id_foreign` (`sender_id`),
  CONSTRAINT `broadcasts_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.broadcasts: ~0 rows (approximately)
DELETE FROM `broadcasts`;

-- Dumping structure for table linebot.channels
CREATE TABLE IF NOT EXISTS `channels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_manager_id` bigint unsigned NOT NULL,
  `channel_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_access_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `channels_account_manager_id_foreign` (`account_manager_id`),
  CONSTRAINT `channels_account_manager_id_foreign` FOREIGN KEY (`account_manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.channels: ~0 rows (approximately)
DELETE FROM `channels`;

-- Dumping structure for table linebot.channel_statisticals
CREATE TABLE IF NOT EXISTS `channel_statisticals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `follower` int DEFAULT '0',
  `broadcast` int DEFAULT '0',
  `targeting` int DEFAULT '0',
  `receive_message` int DEFAULT '0',
  `sent_message` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_statisticals_channel_id_foreign` (`channel_id`),
  CONSTRAINT `channel_statisticals_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.channel_statisticals: ~0 rows (approximately)
DELETE FROM `channel_statisticals`;

-- Dumping structure for table linebot.contents
CREATE TABLE IF NOT EXISTS `contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `content_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_data` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `create_id` bigint unsigned NOT NULL,
  `update_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contents_user_id_foreign` (`user_id`),
  KEY `contents_create_id_foreign` (`create_id`),
  KEY `contents_update_id_foreign` (`update_id`),
  CONSTRAINT `contents_create_id_foreign` FOREIGN KEY (`create_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contents_update_id_foreign` FOREIGN KEY (`update_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.contents: ~0 rows (approximately)
DELETE FROM `contents`;

-- Dumping structure for table linebot.content_broadcasts
CREATE TABLE IF NOT EXISTS `content_broadcasts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `broadcast_id` bigint unsigned NOT NULL,
  `content_id` bigint unsigned NOT NULL,
  `sequence` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_broadcasts_user_id_foreign` (`user_id`),
  KEY `content_broadcasts_broadcast_id_foreign` (`broadcast_id`),
  KEY `content_broadcasts_content_id_foreign` (`content_id`),
  CONSTRAINT `content_broadcasts_broadcast_id_foreign` FOREIGN KEY (`broadcast_id`) REFERENCES `broadcasts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `content_broadcasts_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `content_broadcasts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.content_broadcasts: ~0 rows (approximately)
DELETE FROM `content_broadcasts`;

-- Dumping structure for table linebot.content_keywords
CREATE TABLE IF NOT EXISTS `content_keywords` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `content_id` bigint unsigned NOT NULL,
  `keyword_id` bigint unsigned NOT NULL,
  `sequence` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_keywords_user_id_foreign` (`user_id`),
  KEY `content_keywords_content_id_foreign` (`content_id`),
  KEY `content_keywords_keyword_id_foreign` (`keyword_id`),
  CONSTRAINT `content_keywords_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `content_keywords_keyword_id_foreign` FOREIGN KEY (`keyword_id`) REFERENCES `keywords` (`id`) ON DELETE CASCADE,
  CONSTRAINT `content_keywords_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.content_keywords: ~0 rows (approximately)
DELETE FROM `content_keywords`;

-- Dumping structure for table linebot.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table linebot.interaction_logs
CREATE TABLE IF NOT EXISTS `interaction_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `timestamp` timestamp NOT NULL,
  `action_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `interaction_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `interaction_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.interaction_logs: ~0 rows (approximately)
DELETE FROM `interaction_logs`;

-- Dumping structure for table linebot.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table linebot.keywords
CREATE TABLE IF NOT EXISTS `keywords` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `create_id` bigint unsigned NOT NULL,
  `update_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keywords_user_id_foreign` (`user_id`),
  KEY `keywords_create_id_foreign` (`create_id`),
  KEY `keywords_update_id_foreign` (`update_id`),
  CONSTRAINT `keywords_create_id_foreign` FOREIGN KEY (`create_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `keywords_update_id_foreign` FOREIGN KEY (`update_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `keywords_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.keywords: ~0 rows (approximately)
DELETE FROM `keywords`;

-- Dumping structure for table linebot.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.migrations: ~14 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(15, '2014_10_12_000000_create_users_table', 1),
	(16, '2014_10_12_100000_create_password_resets_table', 1),
	(17, '2019_08_19_000000_create_failed_jobs_table', 1),
	(18, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(19, '2024_02_04_062034_create_admins_table', 1),
	(20, '2024_02_04_103740_create_channels_table', 1),
	(21, '2024_02_04_103907_create_channel_statisticals_table', 1),
	(22, '2024_02_04_104001_create_interaction_logs_table', 1),
	(23, '2024_02_04_104024_create_contents_table', 1),
	(24, '2024_02_04_104049_create_broadcasts_table', 1),
	(25, '2024_02_04_104112_create_content_broadcasts_table', 1),
	(26, '2024_02_04_104140_create_keywords_table', 1),
	(27, '2024_02_04_104201_create_content_keywords_table', 1),
	(28, '2024_02_06_110318_create_jobs_table', 1);

-- Dumping structure for table linebot.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.password_resets: ~0 rows (approximately)
DELETE FROM `password_resets`;

-- Dumping structure for table linebot.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.personal_access_tokens: ~0 rows (approximately)
DELETE FROM `personal_access_tokens`;

-- Dumping structure for table linebot.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line_user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel_id` bigint unsigned DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `is_block` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` int DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `token_verify_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_line_user_id_unique` (`line_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table linebot.users: ~0 rows (approximately)
DELETE FROM `users`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
