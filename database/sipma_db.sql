-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: sipma_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Ensure database exists and is selected
--
CREATE DATABASE IF NOT EXISTS `sipma_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sipma_db`;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `case_actions`
--

DROP TABLE IF EXISTS `case_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `case_actions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `violation_report_id` bigint(20) unsigned NOT NULL,
  `admin_id` bigint(20) unsigned NOT NULL,
  `action_date` date NOT NULL,
  `notes` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `case_actions_violation_report_id_foreign` (`violation_report_id`),
  KEY `case_actions_admin_id_foreign` (`admin_id`),
  CONSTRAINT `case_actions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `case_actions_violation_report_id_foreign` FOREIGN KEY (`violation_report_id`) REFERENCES `violation_reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case_actions`
--

LOCK TABLES `case_actions` WRITE;
/*!40000 ALTER TABLE `case_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `case_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_students_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000001_create_users_table',1),(4,'0001_01_01_000002_create_jobs_table',1),(5,'2026_01_01_000001_create_violation_categories_table',1),(6,'2026_01_01_000002_create_violation_types_table',1),(7,'2026_01_01_000003_create_violation_subtypes_table',1),(8,'2026_01_01_000004_create_violation_reports_table',1),(9,'2026_01_01_000005_create_report_evidences_table',1),(10,'2026_01_01_000006_create_case_actions_table',1),(11,'2026_01_01_000007_create_violation_status_histories_table',1),(12,'2026_01_01_000008_create_sanctions_table',1),(13,'2026_01_01_000009_create_notifications_table',1),(14,'2026_10_15_000001_update_sipma_workflow_schema',2),(15,'2026_10_15_000002_add_nim_to_users_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_evidences`
--

DROP TABLE IF EXISTS `report_evidences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_evidences` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `violation_report_id` bigint(20) unsigned NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `report_evidences_violation_report_id_foreign` (`violation_report_id`),
  CONSTRAINT `report_evidences_violation_report_id_foreign` FOREIGN KEY (`violation_report_id`) REFERENCES `violation_reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_evidences`
--

LOCK TABLES `report_evidences` WRITE;
/*!40000 ALTER TABLE `report_evidences` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_evidences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sanctions`
--

DROP TABLE IF EXISTS `sanctions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sanctions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `violation_report_id` bigint(20) unsigned NOT NULL,
  `sanction_type` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `decision_date` date NOT NULL,
  `decided_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sanctions_violation_report_id_unique` (`violation_report_id`),
  KEY `sanctions_decided_by_foreign` (`decided_by`),
  CONSTRAINT `sanctions_decided_by_foreign` FOREIGN KEY (`decided_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sanctions_violation_report_id_foreign` FOREIGN KEY (`violation_report_id`) REFERENCES `violation_reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sanctions`
--

LOCK TABLES `sanctions` WRITE;
/*!40000 ALTER TABLE `sanctions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sanctions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('9EkdTQ7pLOBckhDBW9OHV0D9DmK5xY2mwHR8JWuX',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0','eyJfdG9rZW4iOiI4OXhTcThGdFpUbTVlaUo4Q0RVeU9HVmkzRUFEa201UnNwZWJWRmkwIiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsInBhc3N3b3JkX2hhc2hfd2ViIjoiOGNmZDNkOTc4NGFlY2VkZjc0NmRmNjMyZTk2ZDNiZDgwMWE1MmJhMjMwMjg2OWMzOWFiODI2MjI3M2ZjYzY0ZiIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbGFwb3JhblwvYnVhdCIsInJvdXRlIjoicmVwb3J0cy5jcmVhdGUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790612338),('nvdImk0x55BIZ6Ir1zQoQEj7C4itbquxXhgq4X6C',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36 Edg/155.0.0.0','eyJfdG9rZW4iOiJlMjZWQUVPOEZnMTJldlJKaGxtUWNZQWhuczBkTVZNNG5Rb2lFcXBIIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJwYXNzd29yZF9oYXNoX3dlYiI6IjZhOWYxOWZhMWQ0ZDhkY2FhYWE4MzFlMjU0ZTA5ODRjMTI2M2NjMzczNmRjNzY2YWExNDNiYmJlZWViNzkxOWEifQ==',1792044979),('w8wYwOAQLWjZCj8eODjEKrB68lapbsGfMBPaytKB',3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0','eyJfdG9rZW4iOiJZWFZYN1JFN0tseU9wa0ZJSDZTcERmbDNJWWU5dzh3SloyU0lGYnFRIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9xdWljay1sb2dpblwvYWRtaW4iLCJyb3V0ZSI6InF1aWNrLmxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjMsInBhc3N3b3JkX2hhc2hfd2ViIjoiMmFjZDE0NzViZjI3MTRjNTk5OGQzYzkzN2E2NzYyYTRlZjI1ZDM5NWI4M2RkY2Q4NGRhMzBhY2U2ODNkYTc5NCJ9',1790612234);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nim` varchar(25) NOT NULL,
  `name` varchar(150) NOT NULL,
  `study_program` varchar(150) NOT NULL,
  `entry_year` smallint(5) unsigned NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_nim_unique` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'2024001','Ahmad Fauzi','Teknik Informatika',2024,'081234567890','2026-10-14 23:27:59','2026-10-14 23:27:59'),(2,'2024002','Budi Santoso','Teknik Informatika',2024,'081234567891','2026-10-14 23:27:59','2026-10-14 23:27:59'),(3,'2024003','Citra Lestari','Sistem Informasi',2023,'081234567892','2026-10-14 23:27:59','2026-10-14 23:27:59'),(4,'2024004','Doni Kusuma','Teknik Elektro',2024,'081234567893','2026-10-14 23:27:59','2026-10-14 23:27:59'),(5,'2024005','Eka Prasetya','Manajemen',2023,'081234567894','2026-10-14 23:27:59','2026-10-14 23:27:59');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `nim` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'mahasiswa',
  `department` varchar(150) DEFAULT NULL,
  `faculty` varchar(150) DEFAULT NULL,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_nim_unique` (`nim`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_student_id_foreign` (`student_id`),
  CONSTRAINT `users_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Ahmad Fauzi (Mahasiswa)','mahasiswa@sipma.test','2024001','2024001','$2y$12$/aDJzpfegvdldEE4cPUEKOb4taKOmXfsmdmRnroQrnANoKaXZE6X2','mahasiswa','Teknik Informatika','Fakultas Ilmu Komputer',1,NULL,NULL,'2026-10-14 23:27:59','2026-10-14 23:27:59'),(2,'Dra. Siti Rahma, M.Pd. (Kemahasiswaan)','kemahasiswaan@sipma.test',NULL,'kemahasiswaan','$2y$12$72BY/qQWrw0PsKUMr60Z6.CPQQhMpitPL7a.ZYUXhCAQ3NZSdPiru','kemahasiswaan',NULL,NULL,NULL,NULL,NULL,'2026-10-14 23:27:59','2026-10-14 23:27:59'),(3,'Admin Kemahasiswaan','admin@sipma.test',NULL,'admin','$2y$12$mexZ9y7vHHVHgEjM.sc.s.g/LVyDJsseWD/ZuVc5WK1jMuVuPE8FW','kemahasiswaan',NULL,NULL,NULL,NULL,NULL,'2026-10-14 23:27:59','2026-10-14 23:27:59'),(4,'Dr. Hendra Wijaya, S.Kom., M.T.','kajur@sipma.test',NULL,'kajur_ti','$2y$12$ChXROy0GwPA/7wQuXjgbZuI9myUzqRZLEeBcpg8iTcGPcmLqxDIyC','kajur','Teknik Informatika','Fakultas Ilmu Komputer',NULL,NULL,NULL,'2026-10-14 23:27:59','2026-10-14 23:27:59'),(5,'Dr. Ir. Maya Sari, M.T.','kajur.si@sipma.test',NULL,'kajur_si','$2y$12$BNvdtqZAZ76r6XKyCV4Iy.tVal5ZM2QxBrRIwDUP3r1U08h4BPjpW','kajur','Sistem Informasi','Fakultas Ilmu Komputer',NULL,NULL,NULL,'2026-10-14 23:27:59','2026-10-14 23:27:59'),(6,'Prof. Dr. Ir. Budi Darmawan, M.Eng.','dekan@sipma.test',NULL,'dekan','$2y$12$26TJ2NjJiKH9bQ/yLH7NeuA6zxs9xK3aKTv3LyrJZu1fqT.DqgbnS','dekan',NULL,'Fakultas Ilmu Komputer',NULL,NULL,NULL,'2026-10-14 23:27:59','2026-10-14 23:27:59'),(7,'Prof. Dr. Suharto, M.Sc.','warek@sipma.test',NULL,'warek','$2y$12$S6S0Mi93eq1BhdAQ7lNSou5qdBQ7xb5GVFy6grlP0ziYvbi5n00Ju','warek',NULL,NULL,NULL,NULL,NULL,'2026-10-14 23:27:59','2026-10-14 23:27:59');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `violation_categories`
--

DROP TABLE IF EXISTS `violation_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `violation_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `violation_categories_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violation_categories`
--

LOCK TABLES `violation_categories` WRITE;
/*!40000 ALTER TABLE `violation_categories` DISABLE KEYS */;
INSERT INTO `violation_categories` VALUES (1,'Akademik','Pelanggaran yang berkaitan dengan kegiatan akademik & ujian','2026-10-14 23:27:59','2026-10-14 23:27:59'),(2,'Tata Tertib Kampus','Pelanggaran tata tertib, fasilitas, dan ketertiban umum','2026-10-14 23:27:59','2026-10-14 23:27:59'),(3,'Etika & Moral','Pelanggaran etika, kesopanan, perundungan, atau pelecehan','2026-10-14 23:27:59','2026-10-14 23:27:59');
/*!40000 ALTER TABLE `violation_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `violation_reports`
--

DROP TABLE IF EXISTS `violation_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `violation_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `report_number` varchar(30) NOT NULL,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `reported_name` varchar(150) DEFAULT NULL,
  `reported_nim` varchar(50) DEFAULT NULL,
  `reported_department` varchar(150) DEFAULT NULL,
  `target_department` varchar(150) DEFAULT NULL,
  `violation_type_id` bigint(20) unsigned DEFAULT NULL,
  `violation_subtype_id` bigint(20) unsigned DEFAULT NULL,
  `reporter_id` bigint(20) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL DEFAULT 0,
  `severity` varchar(50) DEFAULT NULL,
  `incident_date` date NOT NULL,
  `incident_time` time DEFAULT NULL,
  `incident_location` varchar(255) NOT NULL,
  `chronology` text NOT NULL,
  `academic_year` varchar(20) NOT NULL DEFAULT '2026/2027',
  `semester` varchar(20) NOT NULL DEFAULT 'Ganjil',
  `evidence_path` varchar(255) DEFAULT NULL,
  `evidence_name` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `sanction_type` varchar(150) DEFAULT NULL,
  `kajur_notes` text DEFAULT NULL,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `handled_by` bigint(20) unsigned DEFAULT NULL,
  `handled_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `violation_reports_report_number_unique` (`report_number`),
  KEY `violation_reports_student_id_foreign` (`student_id`),
  KEY `violation_reports_violation_type_id_foreign` (`violation_type_id`),
  KEY `violation_reports_violation_subtype_id_foreign` (`violation_subtype_id`),
  KEY `violation_reports_reporter_id_foreign` (`reporter_id`),
  KEY `violation_reports_verified_by_foreign` (`verified_by`),
  KEY `violation_reports_handled_by_foreign` (`handled_by`),
  CONSTRAINT `violation_reports_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `violation_reports_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `violation_reports_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `violation_reports_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `violation_reports_violation_subtype_id_foreign` FOREIGN KEY (`violation_subtype_id`) REFERENCES `violation_subtypes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `violation_reports_violation_type_id_foreign` FOREIGN KEY (`violation_type_id`) REFERENCES `violation_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violation_reports`
--

LOCK TABLES `violation_reports` WRITE;
/*!40000 ALTER TABLE `violation_reports` DISABLE KEYS */;
INSERT INTO `violation_reports` VALUES (1,'REP-202610-001',2,'Budi Santoso','2024002','Teknik Informatika','Teknik Informatika',1,NULL,1,75,'Berat','2026-10-13','10:30:00','Laboratorium Komputer 3, Gedung B','Terlapor terbukti menyontek tugas akhir dan menggunakan source code rekan lain tanpa izin pada saat praktikum.','2026/2027','Ganjil',NULL,NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-12 23:28:03','2026-10-12 23:28:03',NULL,NULL),(2,'REP-202610-002',3,'Citra Lestari','2024003','Sistem Informasi','Sistem Informasi',2,NULL,1,20,'Ringan','2026-10-11','14:15:00','Kantin Gedung C','Terlapor merokok di area tertutup kantin yang sudah terdapat tanda dilarang merokok.','2026/2027','Ganjil',NULL,NULL,'dikonfirmasi',NULL,NULL,NULL,2,'2026-10-11 23:28:03',NULL,'2026-10-10 23:28:03','2026-10-11 23:28:03',NULL,NULL),(3,'REP-202610-003',2,'Budi Santoso','2024002','Teknik Informatika','Teknik Informatika',3,NULL,1,50,'Sedang','2026-10-09','16:00:00','Area Parkir Barat','Terjadi adu argumen yang berlanjut pada aksi dorong-mendorong dengan sesama mahasiswa di area parkir.','2026/2027','Ganjil',NULL,NULL,'diproses',NULL,'Pemanggilan Mahasiswa & Klarifikasi','Telah dilakukan pemanggilan awal mahasiswa terlapor ke ruang jurusan pada hari Selasa.',2,'2026-10-09 23:28:03',NULL,'2026-10-08 23:28:03','2026-10-10 23:28:03',4,'2026-10-10 23:28:03'),(4,'REP-202610-004',1,'Ahmad Fauzi','2024001','Teknik Informatika','Teknik Informatika',2,NULL,2,20,'Ringan','2026-10-05','11:00:00','Lantai 2 Koridor Gedung A','Membawa rokok elektrik dan menghembuskan uap di dalam ruang koridor perkuliahan.','2026/2027','Ganjil',NULL,NULL,'selesai',NULL,'Teguran Lisan & Surat Pernyataan','Mahasiswa telah hadir didampingi dosen wali, menandatangani surat pernyataan tidak mengulangi perbuatan.',2,'2026-10-05 23:28:03','2026-10-07 23:28:03','2026-10-04 23:28:03','2026-10-07 23:28:03',4,'2026-10-07 23:28:03'),(5,'REP-202610-005',NULL,'Rian Hidayat','2024999','Teknik Elektro','Teknik Elektro',NULL,NULL,1,0,'Suspek','2026-10-07','09:00:00','Gerbang Utama Kampus','Informasi dugaan coret-coret dinding pos satpam.','2026/2027','Ganjil',NULL,NULL,'ditolak','Bukti foto tidak jelas dan NIM terlapor tidak terdaftar pada pangkalan data kampus.',NULL,NULL,2,'2026-10-07 23:28:03',NULL,'2026-10-06 23:28:03','2026-10-07 23:28:03',NULL,NULL),(6,'REP-202610-006',4,'Doni Kusuma','2024004','Teknik Elektro','Teknik Elektro',1,NULL,1,75,'Berat','2026-09-15','13:00:00','Ruang Ujian Gedung Elektro','Membawa contekan lembar kertas saat ujian tengah semester.','2026/2027','Ganjil',NULL,NULL,'selesai',NULL,'Surat Peringatan (SP 1) & Nilai Matkul Dibatalkan','Sanksi telah diputuskan rapat jurusan Teknik Elektro.',2,NULL,NULL,'2026-09-14 23:28:03','2026-09-14 23:28:03',NULL,NULL),(7,'REP-202610-007',5,'Eka Prasetya','2024005','Manajemen','Manajemen',3,NULL,1,50,'Sedang','2026-08-15','15:30:00','Lapangan Olahraga','Keributan antar suporter pertandingan futsal kampus.','2025/2026','Genap',NULL,NULL,'selesai',NULL,'Pembinaan Karakter & Kerja Sosial Kampus','Mahasiswa menjalankan pembinaan selama 1 minggu.',2,NULL,NULL,'2026-08-14 23:28:03','2026-08-14 23:28:03',NULL,NULL);
/*!40000 ALTER TABLE `violation_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `violation_status_histories`
--

DROP TABLE IF EXISTS `violation_status_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `violation_status_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `violation_report_id` bigint(20) unsigned NOT NULL,
  `previous_status` varchar(255) DEFAULT NULL,
  `current_status` varchar(255) NOT NULL,
  `changed_by` bigint(20) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `violation_status_histories_violation_report_id_foreign` (`violation_report_id`),
  KEY `violation_status_histories_changed_by_foreign` (`changed_by`),
  CONSTRAINT `violation_status_histories_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `violation_status_histories_violation_report_id_foreign` FOREIGN KEY (`violation_report_id`) REFERENCES `violation_reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violation_status_histories`
--

LOCK TABLES `violation_status_histories` WRITE;
/*!40000 ALTER TABLE `violation_status_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `violation_status_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `violation_subtypes`
--

DROP TABLE IF EXISTS `violation_subtypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `violation_subtypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `violation_type_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `violation_subtypes_violation_type_id_foreign` (`violation_type_id`),
  CONSTRAINT `violation_subtypes_violation_type_id_foreign` FOREIGN KEY (`violation_type_id`) REFERENCES `violation_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violation_subtypes`
--

LOCK TABLES `violation_subtypes` WRITE;
/*!40000 ALTER TABLE `violation_subtypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `violation_subtypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `violation_types`
--

DROP TABLE IF EXISTS `violation_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `violation_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `violation_category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `severity` enum('ringan','sedang','berat') NOT NULL DEFAULT 'ringan',
  `default_points` int(10) unsigned NOT NULL DEFAULT 10,
  `default_sanction` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `violation_types_violation_category_id_foreign` (`violation_category_id`),
  CONSTRAINT `violation_types_violation_category_id_foreign` FOREIGN KEY (`violation_category_id`) REFERENCES `violation_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violation_types`
--

LOCK TABLES `violation_types` WRITE;
/*!40000 ALTER TABLE `violation_types` DISABLE KEYS */;
INSERT INTO `violation_types` VALUES (1,1,'Plagiarisme & Kecurangan Ujian','berat',75,'Skorsing 1 Semester / Nilai E',NULL,'2026-10-14 23:27:59','2026-10-14 23:27:59'),(2,2,'Merokok / Vape di Kawasan Terlarang','ringan',20,'Teguran Lisan / Tertulis',NULL,'2026-10-14 23:27:59','2026-10-14 23:27:59'),(3,3,'Perkelahian & Keributan','sedang',50,'Surat Peringatan & Pemanggilan Ortu',NULL,'2026-10-14 23:27:59','2026-10-14 23:27:59');
/*!40000 ALTER TABLE `violation_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-15 13:43:25
