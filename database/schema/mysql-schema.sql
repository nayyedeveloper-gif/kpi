/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `board_collaborators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `board_collaborators` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `board_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` enum('owner','editor','viewer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'viewer',
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `cursor_position` json DEFAULT NULL,
  `cursor_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `board_collaborators_board_id_user_id_unique` (`board_id`,`user_id`),
  KEY `board_collaborators_user_id_foreign` (`user_id`),
  CONSTRAINT `board_collaborators_board_id_foreign` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `board_collaborators_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `board_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `board_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `board_id` bigint unsigned NOT NULL,
  `element_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` json DEFAULT NULL,
  `resolved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `board_comments_board_id_foreign` (`board_id`),
  KEY `board_comments_element_id_foreign` (`element_id`),
  KEY `board_comments_user_id_foreign` (`user_id`),
  CONSTRAINT `board_comments_board_id_foreign` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `board_comments_element_id_foreign` FOREIGN KEY (`element_id`) REFERENCES `board_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `board_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `board_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `board_elements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `board_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `properties` json NOT NULL,
  `z_index` int NOT NULL DEFAULT '0',
  `created_by` bigint unsigned NOT NULL,
  `locked_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `board_elements_board_id_foreign` (`board_id`),
  KEY `board_elements_created_by_foreign` (`created_by`),
  KEY `board_elements_locked_by_foreign` (`locked_by`),
  CONSTRAINT `board_elements_board_id_foreign` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `board_elements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `board_elements_locked_by_foreign` FOREIGN KEY (`locked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `board_reactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `board_reactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `board_id` bigint unsigned NOT NULL,
  `element_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `emoji` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `board_reactions_board_id_element_id_user_id_emoji_unique` (`board_id`,`element_id`,`user_id`,`emoji`),
  KEY `board_reactions_element_id_foreign` (`element_id`),
  KEY `board_reactions_user_id_foreign` (`user_id`),
  CONSTRAINT `board_reactions_board_id_foreign` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `board_reactions_element_id_foreign` FOREIGN KEY (`element_id`) REFERENCES `board_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `board_reactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `board_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `board_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `board_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `snapshot` json NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `board_versions_board_id_foreign` (`board_id`),
  KEY `board_versions_user_id_foreign` (`user_id`),
  CONSTRAINT `board_versions_board_id_foreign` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `board_versions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `boards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `boards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `share_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `boards_share_link_unique` (`share_link`),
  KEY `boards_created_by_foreign` (`created_by`),
  CONSTRAINT `boards_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bonus_awards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bonus_awards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sales_person_id` bigint unsigned NOT NULL,
  `bonus_configuration_id` bigint unsigned DEFAULT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `total_revenue` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_quantity` int NOT NULL DEFAULT '0',
  `total_transactions` int NOT NULL DEFAULT '0',
  `rank` int DEFAULT NULL,
  `bonus_amount` decimal(15,2) NOT NULL,
  `bonus_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `awarded_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bonus_awards_bonus_configuration_id_foreign` (`bonus_configuration_id`),
  KEY `bonus_awards_sales_person_id_index` (`sales_person_id`),
  KEY `bonus_awards_period_start_period_end_index` (`period_start`,`period_end`),
  KEY `bonus_awards_status_index` (`status`),
  CONSTRAINT `bonus_awards_bonus_configuration_id_foreign` FOREIGN KEY (`bonus_configuration_id`) REFERENCES `bonus_configurations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bonus_awards_sales_person_id_foreign` FOREIGN KEY (`sales_person_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bonus_configurations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bonus_configurations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `criteria` json DEFAULT NULL,
  `bonus_amount` decimal(15,2) DEFAULT NULL,
  `bonus_percentage` decimal(5,2) DEFAULT NULL,
  `rank_limit` int NOT NULL DEFAULT '3' COMMENT 'Top N performers',
  `minimum_revenue` decimal(15,2) NOT NULL DEFAULT '0.00',
  `minimum_quantity` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bonus_tiers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bonus_tiers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `threshold` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Minimum to qualify',
  `bonus_amount` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Fixed bonus amount',
  `bonus_percentage` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Percentage bonus',
  `calculation_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `priority` int NOT NULL DEFAULT '0' COMMENT 'Order of application',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bonus_tiers_type_index` (`type`),
  KEY `bonus_tiers_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_id` bigint unsigned DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_code_unique` (`code`),
  KEY `branches_manager_id_foreign` (`manager_id`),
  KEY `branches_department_id_foreign` (`department_id`),
  CONSTRAINT `branches_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `branches_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `branch_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departments_branch_id_foreign` (`branch_id`),
  CONSTRAINT `departments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `entry_level_checklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entry_level_checklists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `evaluator_id` bigint unsigned NOT NULL,
  `evaluation_date` date NOT NULL,
  `personality_score` tinyint(1) NOT NULL DEFAULT '0',
  `personality_notes` text COLLATE utf8mb4_unicode_ci,
  `performance_score` tinyint(1) NOT NULL DEFAULT '0',
  `performance_notes` text COLLATE utf8mb4_unicode_ci,
  `hospitality_score` tinyint(1) NOT NULL DEFAULT '0',
  `hospitality_notes` text COLLATE utf8mb4_unicode_ci,
  `cleaning_score` tinyint(1) NOT NULL DEFAULT '0',
  `cleaning_notes` text COLLATE utf8mb4_unicode_ci,
  `learning_achievement_score` tinyint(1) NOT NULL DEFAULT '0',
  `learning_achievement_notes` text COLLATE utf8mb4_unicode_ci,
  `total_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` enum('compliant','violation') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'compliant',
  `general_comments` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entry_level_checklists_evaluator_id_foreign` (`evaluator_id`),
  KEY `entry_level_checklists_user_id_evaluation_date_index` (`user_id`,`evaluation_date`),
  CONSTRAINT `entry_level_checklists_evaluator_id_foreign` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `entry_level_checklists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `entry_level_impacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entry_level_impacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `checklist_id` bigint unsigned NOT NULL,
  `affected_user_id` bigint unsigned NOT NULL,
  `affected_role` enum('supervisor','assistant_manager','manager') COLLATE utf8mb4_unicode_ci NOT NULL,
  `impact_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `impact_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entry_level_impacts_checklist_id_foreign` (`checklist_id`),
  KEY `entry_level_impacts_affected_user_id_foreign` (`affected_user_id`),
  CONSTRAINT `entry_level_impacts_affected_user_id_foreign` FOREIGN KEY (`affected_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `entry_level_impacts_checklist_id_foreign` FOREIGN KEY (`checklist_id`) REFERENCES `entry_level_checklists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `group_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `group_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role_in_group` enum('leader','member','coordinator') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `joined_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_user_group_id_user_id_unique` (`group_id`,`user_id`),
  KEY `group_user_user_id_foreign` (`user_id`),
  CONSTRAINT `group_user_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `group_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `group_type` enum('team','project','committee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'team',
  `leader_id` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_code_unique` (`code`),
  KEY `groups_leader_id_foreign` (`leader_id`),
  KEY `groups_branch_id_foreign` (`branch_id`),
  CONSTRAINT `groups_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `groups_leader_id_foreign` FOREIGN KEY (`leader_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kpi_cascade_impacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kpi_cascade_impacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kpi_log_id` bigint unsigned NOT NULL,
  `kpi_measurement_id` bigint unsigned DEFAULT NULL,
  `source_user_id` bigint unsigned NOT NULL,
  `affected_user_id` bigint unsigned NOT NULL,
  `hierarchy_level` int NOT NULL COMMENT '0=direct, 1=supervisor, 2=manager, etc',
  `hierarchy_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Position name at this level',
  `impact_type` enum('good','bad') COLLATE utf8mb4_unicode_ci NOT NULL,
  `impact_score` decimal(8,2) NOT NULL COMMENT 'Score impact (positive or negative)',
  `weight_multiplier` decimal(5,2) NOT NULL DEFAULT '1.00' COMMENT 'Weight based on hierarchy level',
  `reason` text COLLATE utf8mb4_unicode_ci COMMENT 'Why this user was affected',
  `notification_sent` tinyint(1) NOT NULL DEFAULT '0',
  `applied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kpi_cascade_impacts_kpi_measurement_id_foreign` (`kpi_measurement_id`),
  KEY `kpi_cascade_impacts_source_user_id_foreign` (`source_user_id`),
  KEY `kpi_cascade_impacts_affected_user_id_impact_type_index` (`affected_user_id`,`impact_type`),
  KEY `kpi_cascade_impacts_kpi_log_id_hierarchy_level_index` (`kpi_log_id`,`hierarchy_level`),
  CONSTRAINT `kpi_cascade_impacts_affected_user_id_foreign` FOREIGN KEY (`affected_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `kpi_cascade_impacts_kpi_log_id_foreign` FOREIGN KEY (`kpi_log_id`) REFERENCES `kpi_logs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kpi_cascade_impacts_kpi_measurement_id_foreign` FOREIGN KEY (`kpi_measurement_id`) REFERENCES `kpi_measurements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kpi_cascade_impacts_source_user_id_foreign` FOREIGN KEY (`source_user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kpi_configurations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kpi_configurations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Configuration name',
  `level_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'operation or entry',
  `checker_role_id` bigint unsigned NOT NULL,
  `target_role_id` bigint unsigned NOT NULL,
  `cascade_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Enable cascading impact',
  `impact_weights` json DEFAULT NULL COMMENT 'Score impact weights for each hierarchy level',
  `good_impact` json DEFAULT NULL COMMENT 'Points for good performance by hierarchy',
  `bad_impact` json DEFAULT NULL COMMENT 'Points for bad performance by hierarchy',
  `max_cascade_levels` int NOT NULL DEFAULT '5' COMMENT 'Maximum levels to cascade',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kpi_config_unique` (`level_type`,`checker_role_id`,`target_role_id`),
  KEY `kpi_configurations_checker_role_id_foreign` (`checker_role_id`),
  KEY `kpi_configurations_target_role_id_foreign` (`target_role_id`),
  CONSTRAINT `kpi_configurations_checker_role_id_foreign` FOREIGN KEY (`checker_role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `kpi_configurations_target_role_id_foreign` FOREIGN KEY (`target_role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kpi_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kpi_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kpi_measurement_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `kpi_field` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('good','bad') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logged_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kpi_logs_kpi_measurement_id_foreign` (`kpi_measurement_id`),
  KEY `kpi_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `kpi_logs_kpi_measurement_id_foreign` FOREIGN KEY (`kpi_measurement_id`) REFERENCES `kpi_measurements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kpi_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kpi_measurements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kpi_measurements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `measurement_date` date NOT NULL,
  `ready_to_sale` tinyint(1) NOT NULL DEFAULT '0',
  `counter_check` tinyint(1) NOT NULL DEFAULT '0',
  `cleanliness` tinyint(1) NOT NULL DEFAULT '0',
  `stock_check` tinyint(1) NOT NULL DEFAULT '0',
  `order_handling` tinyint(1) NOT NULL DEFAULT '0',
  `customer_followup` tinyint(1) NOT NULL DEFAULT '0',
  `total_score` int NOT NULL DEFAULT '0' COMMENT 'Sum of all boolean fields (max 6)',
  `percentage` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Percentage score (total_score / 6 * 100)',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kpi_measurements_user_id_measurement_date_index` (`user_id`,`measurement_date`),
  CONSTRAINT `kpi_measurements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `performance_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `performance_scores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `period_date` date NOT NULL,
  `period_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `overall_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `kpi_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `task_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `quality_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `attendance_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `collaboration_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tasks_completed` int NOT NULL DEFAULT '0',
  `tasks_total` int NOT NULL DEFAULT '0',
  `kpis_completed` int NOT NULL DEFAULT '0',
  `kpis_total` int NOT NULL DEFAULT '0',
  `days_present` int NOT NULL DEFAULT '0',
  `days_total` int NOT NULL DEFAULT '0',
  `department_rank` int DEFAULT NULL,
  `company_rank` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `performance_scores_user_id_period_date_index` (`user_id`,`period_date`),
  KEY `performance_scores_period_type_period_date_index` (`period_type`,`period_date`),
  CONSTRAINT `performance_scores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permission_role` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `hierarchy_level` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `staff_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_diamond` tinyint(1) NOT NULL DEFAULT '0',
  `is_solid_gold` tinyint(1) NOT NULL DEFAULT '0',
  `item_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gold_quality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `goldsmith_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `goldsmith_date` date DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_k` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_p` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_y` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_tg` decimal(10,3) NOT NULL DEFAULT '0.000',
  `waste_k` decimal(10,2) NOT NULL DEFAULT '0.00',
  `waste_p` decimal(10,2) NOT NULL DEFAULT '0.00',
  `waste_y` decimal(10,2) NOT NULL DEFAULT '0.00',
  `waste_t` decimal(10,3) NOT NULL DEFAULT '0.000',
  `pwaste_k` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pwaste_p` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pwaste_y` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pwaste_tg` decimal(10,3) NOT NULL DEFAULT '0.000',
  `sale_fixed_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `original_fixed_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `original_price_tk` decimal(15,2) NOT NULL DEFAULT '0.00',
  `original_price_gram` decimal(15,2) NOT NULL DEFAULT '0.00',
  `design_charges` decimal(15,2) NOT NULL DEFAULT '0.00',
  `plating_charges` decimal(15,2) NOT NULL DEFAULT '0.00',
  `mounting_charges` decimal(15,2) NOT NULL DEFAULT '0.00',
  `white_charges` decimal(15,2) NOT NULL DEFAULT '0.00',
  `other_charges` decimal(15,2) NOT NULL DEFAULT '0.00',
  `remark` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ranking_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ranking_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guardian_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ranking_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ranking_codes_ranking_id_unique` (`ranking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permission` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permission_role_id_permission_id_unique` (`role_id`,`permission_id`),
  KEY `role_permission_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_user` (
  `role_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`,`user_id`),
  KEY `role_user_user_id_foreign` (`user_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sales_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales_data` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoiced_date` date NOT NULL,
  `voucher_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_address` text COLLATE utf8mb4_unicode_ci,
  `township` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `division` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_nrc_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_categories` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `density` decimal(10,2) NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `g_price` decimal(15,2) DEFAULT NULL,
  `g_gross_amount` decimal(15,2) DEFAULT NULL,
  `m_price` decimal(15,2) DEFAULT NULL,
  `m_gross_amount` decimal(15,2) DEFAULT NULL,
  `dis` decimal(15,2) DEFAULT NULL,
  `promotion_dis` decimal(15,2) DEFAULT NULL,
  `special_dis` decimal(15,2) DEFAULT NULL,
  `dis_net_amount` decimal(15,2) DEFAULT NULL,
  `promotion_net_amount` decimal(15,2) DEFAULT NULL,
  `total_net_amount` decimal(15,2) NOT NULL,
  `tax` decimal(15,2) DEFAULT NULL,
  `sale_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sales_people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales_people` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '3.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_people_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sales_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sales_person_id` bigint unsigned DEFAULT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_nrc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `goldsmith_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cashier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_manager` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responsible_signature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` decimal(15,2) DEFAULT NULL,
  `unit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'g',
  `gold_quality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `item_k` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_p` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_y` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_tg` decimal(10,3) NOT NULL DEFAULT '0.000',
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(15,2) NOT NULL,
  `price_per_gram` decimal(15,2) DEFAULT NULL,
  `price_per_piece` decimal(15,2) DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `payment_status` enum('pending','partial','paid','overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `commission` decimal(12,2) NOT NULL DEFAULT '0.00',
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Percentage',
  `commission_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sale_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `branch` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_transactions_sales_person_id_index` (`sales_person_id`),
  KEY `sales_transactions_sale_date_index` (`sale_date`),
  KEY `sales_transactions_product_id_foreign` (`product_id`),
  KEY `sales_transactions_sale_date_branch_index` (`sale_date`,`branch`),
  KEY `sales_transactions_invoice_no_index` (`invoice_no`),
  KEY `sales_transactions_customer_phone_index` (`customer_phone`),
  CONSTRAINT `sales_transactions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_transactions_sales_person_id_foreign` FOREIGN KEY (`sales_person_id`) REFERENCES `sales_people` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `position_id` bigint unsigned DEFAULT NULL,
  `supervisor_id` bigint unsigned DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_department_id_foreign` (`department_id`),
  KEY `users_position_id_foreign` (`position_id`),
  KEY `users_supervisor_id_foreign` (`supervisor_id`),
  KEY `users_role_id_foreign` (`role_id`),
  KEY `users_branch_id_foreign` (`branch_id`),
  CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_10_28_152500_create_departments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_10_28_152504_create_positions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_10_28_152507_create_kpi_measurements_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_10_28_152513_add_organization_fields_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_10_28_175726_create_roles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_10_29_021946_create_kpi_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_10_29_025543_add_kpi_field_to_kpi_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_10_29_031518_create_permissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_10_29_034234_create_branches_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_10_29_034940_create_groups_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_10_29_044729_create_entry_level_checklists_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_10_29_045900_modify_entry_level_checklists_to_boolean',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_10_29_072010_add_profile_photo_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_10_29_083000_create_performance_scores_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_10_29_090000_create_collaborative_boards_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_10_29_104730_create_kpi_configurations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_10_29_104737_create_kpi_cascade_impacts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_10_29_130003_add_department_id_to_branches_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_10_29_131507_create_sales_transactions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_10_29_131518_create_bonus_configurations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_10_29_131526_create_bonus_awards_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_10_29_133458_create_bonus_tiers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_10_29_164500_create_products_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_10_29_165000_update_products_table_for_jewelry',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_10_30_031206_update_sales_transactions_table_for_jewelry_sales',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_10_30_032839_add_invoice_no_to_sales_transactions',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_11_05_063337_create_ranking_codes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_11_07_072749_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_11_07_101405_add_net_amount_to_sales_transactions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_11_07_102112_add_commission_to_sales_transactions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_11_07_102253_add_branch_to_sales_transactions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_11_07_102727_add_category_to_sales_transactions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_11_08_043101_create_sales_people_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2025_11_08_043424_update_sales_person_foreign_key',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_11_08_053411_create_sales_data_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_11_08_053737_update_sales_data_table_structure',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_11_08_130000_enhance_sales_transactions_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_11_08_130100_create_roles_and_permissions_tables',3);
