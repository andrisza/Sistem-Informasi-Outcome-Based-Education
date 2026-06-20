-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: si_obe
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_al_user` (`user_id`),
  KEY `idx_al_model` (`model_type`,`model_id`),
  KEY `idx_al_created` (`created_at`),
  CONSTRAINT `activity_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES (1,1,'delete','App\\Models\\User',2,'{\"name\": \"Test User\", \"role\": \"kaprodi\", \"email\": \"test@example.com\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-25 19:35:52'),(2,4,'login','User',4,NULL,NULL,'127.0.0.1',NULL,'2026-05-25 19:49:24'),(3,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 03:49:45'),(4,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 03:58:15'),(5,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 04:49:57'),(6,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 09:48:37'),(7,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:11:37'),(8,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:11:45'),(9,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:12:41'),(10,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:13:23'),(11,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:14:03'),(12,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:14:10'),(13,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:23:24'),(14,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:25:16'),(15,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:27:45'),(16,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:40:48'),(17,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:41:31'),(18,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:41:55'),(19,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:42:06'),(20,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:42:26'),(21,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:43:07'),(22,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:43:18'),(23,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:44:31'),(24,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 10:44:40'),(25,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 11:25:20'),(26,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 11:27:10'),(27,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 11:27:23'),(28,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 11:30:29'),(29,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 11:32:18'),(30,5,'delete','App\\Models\\MataKuliah',55,'{\"kode_mk\": \"UNV101\", \"nama_mk\": \"Agama\", \"semester\": 1}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 12:11:14'),(31,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 13:19:18'),(32,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 13:19:31'),(33,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 15:29:37'),(34,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 15:29:49'),(35,5,'create','App\\Models\\Kurikulum',4,NULL,'{\"kode\": \"K-A-S1-2026\", \"status\": \"draft\", \"nama_kurikulum\": \"Kurikulum Sistem Informasi 2026\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 15:31:45'),(36,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 15:32:05'),(37,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 15:32:15'),(38,4,'delete','App\\Models\\Kurikulum',4,'{\"kode\": \"K-A-S1-2026\", \"nama_kurikulum\": \"Kurikulum Sistem Informasi 2026\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 15:32:34'),(39,4,'delete','App\\Models\\Kurikulum',3,'{\"kode\": \"K-IF-S1-2026\", \"nama_kurikulum\": \"Kurikulum Teknik Informatika 2026\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 15:35:34'),(40,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 15:35:50'),(41,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 15:36:02'),(42,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-30 19:04:15'),(43,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-31 01:04:46'),(44,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-31 01:10:40'),(45,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-31 01:10:54'),(46,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-31 15:21:54'),(47,4,'arsip','App\\Models\\Kurikulum',5,'{\"status\": \"aktif\"}','{\"status\": \"arsip\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-31 15:36:39'),(48,4,'delete','App\\Models\\SemesterAkademik',2,'{\"nama\": \"Genap 2023/2024\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-31 15:42:11'),(49,4,'delete','App\\Models\\SemesterAkademik',1,'{\"nama\": \"Ganjil 2024/2025\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-31 15:42:17'),(50,4,'create','App\\Models\\SemesterAkademik',3,NULL,'{\"nama\": \"Genap 2025/2026\", \"jenis\": \"Genap\", \"tahun_akademik\": \"2025/2026\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-31 15:51:21'),(51,4,'update','App\\Models\\SemesterAkademik',3,'{\"nama\": \"Genap 2025/2026\", \"jenis\": \"Genap\", \"tahun_akademik\": \"2025/2026\"}','{\"nama\": \"Genap 2025/2026\", \"jenis\": \"Genap\", \"tahun_akademik\": \"2025\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-31 15:51:48'),(52,4,'create','App\\Models\\Kurikulum',1,NULL,'{\"kode\": \"K-SI-S1-2026\", \"status\": \"draft\", \"nama_kurikulum\": \"Kurikulum Sistem Informasi 2021\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-31 15:53:40'),(53,4,'delete','App\\Models\\Kurikulum',1,'{\"kode\": \"K-SI-S1-2026\", \"nama_kurikulum\": \"Kurikulum Sistem Informasi 2021\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-31 16:44:44'),(54,4,'create','App\\Models\\MataKuliah',26,NULL,'{\"kode_mk\": \"MK26\", \"nama_mk\": \"Agama\", \"semester\": \"1\", \"sks_teori\": \"2\", \"sks_praktikum\": \"0\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-31 18:32:57'),(55,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 03:44:00'),(56,4,'delete','App\\Models\\SemesterAkademik',5,'{\"nama\": \"Genap 2023/2024\"}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 03:46:34'),(57,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 09:55:45'),(58,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 10:28:19'),(59,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 10:44:55'),(60,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 12:23:59'),(61,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 12:24:08'),(62,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 12:25:09'),(63,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 12:25:18'),(64,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 14:39:48'),(65,8,'login','App\\Models\\User',8,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 14:39:59'),(66,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 16:48:36'),(67,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-01 16:49:21'),(68,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:06:58'),(69,4,'create','App\\Models\\User',11,NULL,'{\"name\": \"Ari Attala\", \"role\": \"mahasiswa\", \"email\": \"ariputra215.ap@gmail.com\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:10:11'),(70,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:10:27'),(71,11,'login','App\\Models\\User',11,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:10:57'),(72,11,'logout','App\\Models\\User',11,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:18:27'),(73,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:18:46'),(74,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:26:07'),(75,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:26:17'),(76,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:26:39'),(77,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:27:10'),(78,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:27:55'),(79,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:28:02'),(80,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 01:39:12'),(81,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 04:32:06'),(82,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 04:32:26'),(83,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 05:55:14'),(84,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-02 08:13:48'),(85,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 01:30:34'),(86,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:00:58'),(87,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:01:06'),(88,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:15:21'),(89,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:15:29'),(90,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:23:27'),(91,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:23:42'),(92,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:36:59'),(93,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:37:17'),(94,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:37:52'),(95,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:38:00'),(96,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:38:26'),(97,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:38:35'),(98,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:38:51'),(99,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:39:12'),(100,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:40:47'),(101,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:40:58'),(102,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:41:21'),(103,7,'login','App\\Models\\User',7,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:42:48'),(104,7,'logout','App\\Models\\User',7,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:43:16'),(105,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 03:44:31'),(106,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 09:10:52'),(107,4,'buat_cqi','App\\Models\\LogEvaluasiCqi',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 09:22:14'),(108,4,'update_cqi_status','App\\Models\\LogEvaluasiCqi',1,'{\"status\": \"proses\"}','{\"status\": \"proses\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 09:22:58'),(109,4,'buat_cqi','App\\Models\\LogEvaluasiCqi',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 09:23:46'),(110,4,'buat_cqi','App\\Models\\LogEvaluasiCqi',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 09:25:10'),(111,4,'setujui_cqi','App\\Models\\LogEvaluasiCqi',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 09:25:33'),(112,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 10:28:07'),(113,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 10:50:30'),(114,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 10:50:38'),(115,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 10:53:54'),(116,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 10:55:32'),(117,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 11:23:25'),(118,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 11:23:37'),(119,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 12:00:44'),(120,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 12:00:52'),(121,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 12:01:07'),(122,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 12:01:18'),(123,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 12:07:06'),(124,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 12:09:13'),(125,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 12:44:27'),(126,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-07 12:44:36'),(127,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 00:37:27'),(128,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 00:55:10'),(129,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 00:55:20'),(130,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:00:15'),(131,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:00:23'),(132,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:00:41'),(133,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:00:47'),(134,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:04:34'),(135,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:04:44'),(136,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:25:28'),(137,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:25:39'),(138,4,'create','App\\Models\\MataKuliah',27,NULL,'{\"kode_mk\": \"MK27\", \"nama_mk\": \"Bahasa Indonesia\", \"semester\": \"1\", \"sks_teori\": \"2\", \"sks_praktikum\": \"0\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:42:11'),(139,4,'create','App\\Models\\MataKuliah',28,NULL,'{\"kode_mk\": \"MK28\", \"nama_mk\": \"Bahasa Indonesia\", \"semester\": \"1\", \"sks_teori\": \"2\", \"sks_praktikum\": \"0\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:42:56'),(140,4,'delete','App\\Models\\MataKuliah',28,'{\"kode_mk\": \"MK28\", \"nama_mk\": \"Bahasa Indonesia\", \"semester\": 1}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:43:04'),(141,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:51:26'),(142,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:51:32'),(143,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:55:27'),(144,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:55:33'),(145,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:56:46'),(146,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:56:55'),(147,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 01:59:29'),(148,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 02:17:39'),(149,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 02:18:20'),(150,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 02:18:28'),(151,4,'buat_cqi','App\\Models\\LogEvaluasiCqi',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-08 02:19:41'),(152,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-13 14:05:01'),(153,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 14:56:44'),(154,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 14:57:54'),(155,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 14:58:26'),(156,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 15:24:02'),(157,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 15:24:28'),(158,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 15:25:48'),(159,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 15:48:24'),(160,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 15:48:47'),(161,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 15:48:54'),(162,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 17:19:44'),(163,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 17:20:43'),(164,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 17:20:52'),(165,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 17:21:29'),(166,8,'login','App\\Models\\User',8,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 17:22:20'),(167,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 23:39:26'),(168,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 23:40:56'),(169,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-15 23:41:37'),(170,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-16 00:06:27'),(171,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-16 00:06:37'),(172,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-16 00:55:19'),(173,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-16 00:55:28'),(174,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-16 00:56:22'),(175,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 10:21:41'),(176,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:05:28'),(177,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:05:42'),(178,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:06:37'),(179,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:06:46'),(180,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:07:31'),(181,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:07:41'),(182,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:17:53'),(183,8,'login','App\\Models\\User',8,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:18:18'),(184,8,'logout','App\\Models\\User',8,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:20:29'),(185,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:20:46'),(186,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:41:52'),(187,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:42:01'),(188,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:43:09'),(189,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 12:43:16'),(190,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 16:12:52'),(191,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 16:46:37'),(192,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 16:46:51'),(193,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 16:50:00'),(194,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 16:50:08'),(195,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 16:50:33'),(196,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 16:50:45'),(197,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 16:51:46'),(198,6,'login','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 16:51:56'),(199,6,'logout','App\\Models\\User',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 16:58:58'),(200,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 17:04:20'),(201,4,'logout','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 17:31:08'),(202,5,'login','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 17:31:17'),(203,5,'logout','App\\Models\\User',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 17:32:57'),(204,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-19 17:33:06'),(205,4,'login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 10:46:25');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arsip_rapat`
--

DROP TABLE IF EXISTS `arsip_rapat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `arsip_rapat` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_kurikulum` bigint unsigned NOT NULL,
  `judul_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `tempat` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temuan` longtext COLLATE utf8mb4_unicode_ci,
  `tindak_lanjut` longtext COLLATE utf8mb4_unicode_ci,
  `file_lampiran` json DEFAULT NULL,
  `dibuat_oleh` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `arsip_rapat_dibuat_oleh_foreign` (`dibuat_oleh`),
  KEY `idx_rapat_kur` (`id_kurikulum`,`tanggal`),
  CONSTRAINT `arsip_rapat_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `arsip_rapat_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsip_rapat`
--

LOCK TABLES `arsip_rapat` WRITE;
/*!40000 ALTER TABLE `arsip_rapat` DISABLE KEYS */;
INSERT INTO `arsip_rapat` VALUES (2,2,'Rapat Perdana','2026-06-19','Ruang Rapat','Notulen',NULL,'[{\"name\": \"Screenshot (429).png\", \"path\": \"arsip-rapat/2/gtcACg5bLwxVJ2OUUmusA4GsuvDO4cAQBEO25zi6.png\"}]',5,'2026-06-19 05:22:53','2026-06-19 05:22:54');
/*!40000 ALTER TABLE `arsip_rapat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bahan_kajian`
--

DROP TABLE IF EXISTS `bahan_kajian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bahan_kajian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_kurikulum` bigint unsigned NOT NULL,
  `kode_bk` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_bk` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `kompetensi` enum('Utama','Pendukung','Umum') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referensi` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bidang_keilmuan` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `status` enum('draft','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_bk_kode` (`id_kurikulum`,`kode_bk`),
  KEY `bahan_kajian_approved_by_foreign` (`approved_by`),
  CONSTRAINT `bahan_kajian_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bahan_kajian_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bahan_kajian`
--

LOCK TABLES `bahan_kajian` WRITE;
/*!40000 ALTER TABLE `bahan_kajian` DISABLE KEYS */;
INSERT INTO `bahan_kajian` VALUES (1,2,'BK01','Foundation of Information Systems','Memperkenalkan konsep dasar sistem informasi untuk mendukung proses bisnis transaksional, keputusan, dan kolaboratif dengan menggunakan alat dan metode pengembangan IS yang relevan.','Utama','IS2020',NULL,1,'draft',NULL,NULL,NULL),(2,2,'BK02','Data/Information Management','Fokus pada cara mengelola data dan informasi sebagai aset bisnis, termasuk teknik penyimpanan, pengambilan, dan pengolahan basis data serta prinsip-prinsip manajemen beserta keamanan basis data.','Utama','IS2020',NULL,2,'draft',NULL,NULL,NULL),(3,2,'BK03','IT Infrastructure','Fokus pada enterprise architecture yang mencakup pemahaman tentang komponen fisik dan virtual yang membentuk infrastruktur IT, termasuk perangkat keras, perangkat lunak, jaringan, dan cloud computing.','Utama','IS2020',NULL,3,'draft',NULL,NULL,NULL),(4,2,'BK04','IS Project Management','Pembelajaran tentang metodologi dan teknik manajemen proyek untuk mengelola proyek-proyek sistem informasi, termasuk perencanaan, pengorganisasian, pengendalian, dan penutupan proyek.','Utama','IS2020',NULL,4,'draft',NULL,NULL,NULL),(5,2,'BK05','Systems Analysis & Design','Mempelajari proses analisis kebutuhan dan desain sistem informasi yang efektif, termasuk teknik pemodelan sistem, pengembangan diagram, dan pembuatan spesifikasi sistem.','Utama','IS2020',NULL,5,'draft',NULL,NULL,NULL),(6,2,'BK06','IS Management and Strategy','Fokus pada pengembangan strategi untuk pengelolaan sistem informasi yang selaras dengan tujuan bisnis, termasuk pengelolaan sumber daya IT, tata kelola IT, dan penerapan kebijakan teknologi.','Utama','IS2020',NULL,6,'draft',NULL,NULL,NULL),(7,2,'BK07','Application Development/Programming','Fokus dalam pengembangan aplikasi dan pemrograman, termasuk penggunaan bahasa pemrograman, framework, dan alat pengembangan untuk menciptakan solusi perangkat lunak.','Utama','IS2020',NULL,7,'draft',NULL,NULL,NULL),(8,2,'BK08','Secure Computing','Menekankan pada pentingnya keamanan informasi dan sistem, termasuk konsep dasar keamanan komputer, enkripsi, pengelolaan ancaman, dan pengendalian akses.','Utama','IS2020',NULL,8,'draft',NULL,NULL,NULL),(9,2,'BK09','Ethics, use and implications for society','Mengeksplorasi aspek etika penggunaan teknologi informasi, dampak sosial, privasi, dan implikasi hukum dari implementasi sistem informasi dalam masyarakat.','Utama','IS2020',NULL,9,'draft',NULL,NULL,NULL),(10,2,'BK10','Internship','Program magang yang memberikan pengalaman praktis bagi mahasiswa dalam dunia kerja nyata di bidang sistem informasi, memungkinkan mahasiswa menerapkan pengetahuan yang telah diperoleh.','Utama','IABEE',NULL,10,'draft',NULL,NULL,NULL),(11,2,'BK11','Mathematics and Statistics','Membangun dasar pengetahuan matematika dan statistik yang diperlukan untuk analisis data, pemodelan, dan pengambilan keputusan yang berbasis data dalam sistem informasi.','Utama','IABEE',NULL,11,'draft',NULL,NULL,NULL),(12,2,'BK12','Research Methodology','Mencakup langkah-langkah sistematis dalam melakukan penelitian di bidang sistem informasi, mulai dari perumusan masalah, tinjauan literatur, pemilihan metode, hingga analisis data.','Umum',NULL,NULL,12,'draft',NULL,NULL,NULL),(13,2,'BK13','Data/Business Analytics','Memperkenalkan teknik dan alat analisis data untuk pengambilan keputusan bisnis, termasuk penggunaan big data, data mining, dan analisis prediktif.','Pendukung','IS2020',NULL,13,'draft',NULL,NULL,NULL),(14,2,'BK14','Personality Development','Pengembangan keterampilan interpersonal dan soft skills, seperti komunikasi, kerja sama tim, dan manajemen waktu yang penting bagi profesional di bidang sistem informasi.','Pendukung','IS2020/IABEE',NULL,14,'draft',NULL,NULL,NULL),(15,2,'BK15','Business Process Management','Fokus pada analisis, desain, implementasi, pemantauan, dan penyempurnaan proses bisnis untuk meningkatkan efisiensi dan efektivitas manajemen bisnis.','Pendukung','IS2020/ASIIN',NULL,15,'draft',NULL,NULL,NULL),(16,2,'BK16','Enterprise Architecture','Pembelajaran tentang bagaimana merancang dan mengelola arsitektur organisasi secara holistik untuk memastikan bahwa teknologi informasi sejalan dengan tujuan strategis bisnis.','Pendukung','CC2020',NULL,16,'draft',NULL,NULL,NULL),(17,2,'BK17','User Interface Design','Prinsip dan praktik desain antarmuka pengguna yang efektif, termasuk pemahaman tentang pengalaman pengguna (UX), navigasi, dan desain interaksi yang intuitif.','Pendukung','IS2020',NULL,17,'draft',NULL,NULL,NULL),(18,2,'BK18','Emerging Technologies','Eksplorasi teknologi-teknologi baru dan inovatif seperti kecerdasan buatan, Internet of Things (IoT), blockchain, dan teknologi disruptif lainnya.','Pendukung','IS2020',NULL,18,'draft',NULL,NULL,NULL),(19,2,'BK19','Digital Innovation','Pengembangan ide-ide inovatif dan penerapan solusi digital untuk menciptakan nilai baru bagi bisnis dan masyarakat, termasuk pemikiran desain dan kewirausahaan digital.','Pendukung','IS2020',NULL,19,'draft',NULL,NULL,NULL);
/*!40000 ALTER TABLE `bahan_kajian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batas_ketercapaian`
--

DROP TABLE IF EXISTS `batas_ketercapaian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `batas_ketercapaian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_cpl` bigint unsigned NOT NULL,
  `id_kurikulum` bigint unsigned NOT NULL,
  `batas_nilai` decimal(5,2) NOT NULL DEFAULT '70.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_batas` (`id_cpl`,`id_kurikulum`),
  KEY `batas_ketercapaian_id_kurikulum_foreign` (`id_kurikulum`),
  CONSTRAINT `batas_ketercapaian_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `batas_ketercapaian_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batas_ketercapaian`
--

LOCK TABLES `batas_ketercapaian` WRITE;
/*!40000 ALTER TABLE `batas_ketercapaian` DISABLE KEYS */;
INSERT INTO `batas_ketercapaian` VALUES (18,27,5,70.00),(19,28,5,70.00),(20,29,5,70.00),(21,30,5,70.00),(22,31,5,70.00),(23,32,5,70.00),(24,33,5,70.00),(25,34,5,70.00),(26,35,5,70.00),(27,36,5,70.00),(28,37,5,70.00),(29,38,5,70.00),(30,39,5,70.00),(31,40,5,70.00),(32,1,2,70.00),(33,2,2,70.00),(34,3,2,70.00),(35,4,2,70.00),(36,5,2,70.00),(37,6,2,70.00),(38,7,2,70.00);
/*!40000 ALTER TABLE `batas_ketercapaian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba','i:1;',1781952444),('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1781952444;',1781952444),('laravel-cache-ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4','i:1;',1780880508),('laravel-cache-ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4:timer','i:1780880508;',1780880508);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
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
-- Table structure for table `cpl_prodi`
--

DROP TABLE IF EXISTS `cpl_prodi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cpl_prodi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_kurikulum` bigint unsigned NOT NULL,
  `kode_cpl` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` enum('Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `referensi` text COLLATE utf8mb4_unicode_ci,
  `urutan` int NOT NULL DEFAULT '0',
  `status` enum('draft','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cplp_kode` (`id_kurikulum`,`kode_cpl`),
  KEY `cpl_prodi_approved_by_foreign` (`approved_by`),
  CONSTRAINT `cpl_prodi_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cpl_prodi_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cpl_prodi`
--

LOCK TABLES `cpl_prodi` WRITE;
/*!40000 ALTER TABLE `cpl_prodi` DISABLE KEYS */;
INSERT INTO `cpl_prodi` VALUES (1,2,'CPL01','Mampu memahami, menganalisis permasalahan computing yang kompleks, dan menilai konsep dasar serta peran sistem informasi dalam mengelola data dan memberikan rekomendasi pengambilan keputusan pada sistem organisasi.','Pengetahuan','P1, CPL-KU01',1,'draft',NULL,NULL,NULL),(2,2,'CPL02','Mampu memahami, merancang, menggunakan sistem manajemen basis data, serta mengolah dan menganalisa data dengan peralatan dan metode pengolahan data.','Keterampilan Khusus','CPL-P01, CPL-KU02, CPL-KU03',2,'draft',NULL,NULL,NULL),(3,2,'CPL03','Mampu memahami dan menggunakan berbagai metodologi pengembangan sistem beserta alat pemodelan sistem serta menganalisis kebutuhan pengguna dalam membangun sistem informasi yang berkualitas untuk mencapai tujuan organisasi.','Keterampilan Khusus','CPL-P01',3,'draft',NULL,NULL,NULL),(4,2,'CPL04','Mampu menganalisis infrastruktur SI, arsitektur jaringan, layanan fisik dan cloud, konsep identifikasi, otentikasi, otorisasi akses dalam konteks melindungi orang dan perangkat.','Keterampilan Khusus',NULL,4,'draft',NULL,NULL,NULL),(5,2,'CPL05','Mampu memahami dan menerapkan kode etik organisasi dalam penggunaan informasi maupun data pada perancangan dan implementasi suatu sistem.','Sikap',NULL,5,'draft',NULL,NULL,NULL),(6,2,'CPL06','Memiliki kemampuan merencanakan, menerapkan, memelihara serta meningkatkan sistem informasi organisasi untuk mencapai tujuan dan sasaran organisasi yang strategis baik jangka pendek maupun jangka panjang.','Keterampilan Khusus',NULL,6,'draft',NULL,NULL,NULL),(7,2,'CPL07','Mampu memahami, mengidentifikasi dan menerapkan konsep, teknik dan metodologi manajemen proyek sistem informasi terintegrasi untuk peningkatan proses bisnis organisasi.','Keterampilan Khusus',NULL,7,'draft',NULL,NULL,NULL),(8,2,'CPL08','test','Pengetahuan','CPL-KK02, CPL-KK03',8,'draft',NULL,NULL,'2026-06-07 21:16:51');
/*!40000 ALTER TABLE `cpl_prodi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cpl_sndikti`
--

DROP TABLE IF EXISTS `cpl_sndikti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cpl_sndikti` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` enum('Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `status` enum('draft','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cplsn_kode` (`kode`),
  KEY `cpl_sndikti_approved_by_foreign` (`approved_by`),
  CONSTRAINT `cpl_sndikti_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cpl_sndikti`
--

LOCK TABLES `cpl_sndikti` WRITE;
/*!40000 ALTER TABLE `cpl_sndikti` DISABLE KEYS */;
INSERT INTO `cpl_sndikti` VALUES (1,'S1','Bertaqwa kepada Tuhan Yang Maha Esa.','Sikap',1,'draft',NULL,NULL),(2,'KU1','Mampu menerapkan pemikiran logis, kritis, sistematis, dan inovatif.','Keterampilan Umum',1,'draft',NULL,NULL),(3,'KK1','Mampu merancang dan mengimplementasikan sistem informasi.','Keterampilan Khusus',1,'draft',NULL,NULL),(4,'P1','Menguasai konsep dan teori dasar sistem informasi.','Pengetahuan',1,'draft',NULL,NULL),(5,'CPL-S01','Bertakwa kepada Tuhan Yang Maha Esa dan mampu menunjukkan sikap religius.','Sikap',1,'draft',NULL,NULL),(6,'CPL-S02','Menjunjung tinggi nilai kemanusiaan dalam menjalankan tugas berdasarkan agama, moral, dan etika.','Sikap',2,'draft',NULL,NULL),(7,'CPL-S03','Berkontribusi dalam peningkatan mutu kehidupan bermasyarakat, berbangsa, dan bernegara.','Sikap',3,'draft',NULL,NULL),(8,'CPL-S04','Berperan sebagai warga negara yang bangga dan cinta tanah air, memiliki nasionalisme.','Sikap',4,'draft',NULL,NULL),(9,'CPL-S05','Menghargai keanekaragaman budaya, pandangan, agama, dan kepercayaan, serta pendapat atau temuan orisinal orang lain.','Sikap',5,'draft',NULL,NULL),(10,'CPL-S06','Bekerja sama dan memiliki kepekaan sosial serta kepedulian terhadap masyarakat dan lingkungan.','Sikap',6,'draft',NULL,NULL),(11,'CPL-S07','Taat hukum dan disiplin dalam kehidupan bermasyarakat dan bernegara.','Sikap',7,'draft',NULL,NULL),(12,'CPL-S08','Menginternalisasi nilai, norma, dan etika akademik.','Sikap',8,'draft',NULL,NULL),(13,'CPL-S09','Menunjukkan sikap bertanggungjawab atas pekerjaan di bidang keahliannya secara mandiri.','Sikap',9,'draft',NULL,NULL),(14,'CPL-S10','Menginternalisasi semangat kemandirian, kejuangan, dan kewirausahaan.','Sikap',10,'draft',NULL,NULL),(15,'CPL-S11','Menginternalisasi nilai-nilai ahlussunah waljamaah (Aswaja).','Sikap',11,'draft',NULL,NULL),(16,'CPL-KU01','Mampu menerapkan pemikiran logis, kritis, sistematis, dan inovatif dalam konteks pengembangan atau implementasi ipteks.','Keterampilan Umum',1,'draft',NULL,NULL),(17,'CPL-KU02','Mampu menunjukkan kinerja mandiri, bermutu, dan terukur.','Keterampilan Umum',2,'draft',NULL,NULL),(18,'CPL-KU03','Mampu mengkaji implikasi pengembangan atau implementasi ilmu pengetahuan teknologi sesuai keahliannya.','Keterampilan Umum',3,'draft',NULL,NULL),(19,'CPL-KU04','Menyusun deskripsi saintifik hasil kajian tersebut di atas dalam bentuk skripsi atau laporan tugas akhir.','Keterampilan Umum',4,'draft',NULL,NULL),(20,'CPL-KU05','Mampu mengambil keputusan secara tepat dalam konteks penyelesaian masalah di bidang keahliannya.','Keterampilan Umum',5,'draft',NULL,NULL),(21,'CPL-KU06','Mampu memelihara dan mengembangkan jaringan kerja dengan pembimbing, kolega, sejawat.','Keterampilan Umum',6,'draft',NULL,NULL),(22,'CPL-KU07','Mampu bertanggungjawab atas pencapaian hasil kerja kelompok dan melakukan supervisi serta evaluasi.','Keterampilan Umum',7,'draft',NULL,NULL),(23,'CPL-KU08','Mampu melakukan proses evaluasi diri terhadap kelompok kerja yang berada di bawah tanggung jawabnya.','Keterampilan Umum',8,'draft',NULL,NULL),(24,'CPL-KU09','Mampu mendokumentasikan, menyimpan, mengamankan, dan menemukan kembali data untuk menjamin kesahihan dan mencegah plagiasi.','Keterampilan Umum',9,'draft',NULL,NULL),(25,'CPL-KU10','Berkomunikasi secara efektif dalam berbagai konteks profesional.','Keterampilan Umum',10,'draft',NULL,NULL),(26,'CPL-KK01','Mampu membangun, mengelola, menggunakan dan mengamankan database dengan alat dan teknik pengolahan data yang sesuai.','Keterampilan Khusus',1,'draft',NULL,NULL),(27,'CPL-KK02','Mampu membuat perencanaan infrastruktur TI, arsitektur jaringan, layanan fisik dan cloud.','Keterampilan Khusus',2,'draft',NULL,NULL),(28,'CPL-KK03','Mampu menerapkan metodologi pengembangan sistem informasi beserta alat pemodelan sistem dan menganalisa kebutuhan pengguna.','Keterampilan Khusus',3,'draft',NULL,NULL),(29,'CPL-KK04','Mampu menerapkan dasar logika, prinsip matematika, ekspresi, aspek modular, linearitas dan non-linearitas struktur data pada pemrograman perangkat lunak.','Keterampilan Khusus',4,'draft',NULL,NULL),(30,'CPL-KK05','Mampu memahami, menerapkan kode etik dalam penggunaan informasi dan data pada sistem informasi.','Keterampilan Khusus',5,'draft',NULL,NULL),(31,'CPL-P01','Menguasai konsep dan teori dasar sistem informasi serta penerapannya pada organisasi.','Pengetahuan',1,'draft',NULL,NULL),(32,'CPL-P02','Menguasai konsep pengembangan perangkat lunak dan database.','Pengetahuan',2,'draft',NULL,NULL),(33,'CPL-P03','Menguasai konsep manajemen proses bisnis, big data, dan business intelligence.','Pengetahuan',3,'draft',NULL,NULL);
/*!40000 ALTER TABLE `cpl_sndikti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cpmk`
--

DROP TABLE IF EXISTS `cpmk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cpmk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_kurikulum` bigint unsigned NOT NULL,
  `id_mk` bigint unsigned NOT NULL,
  `id_cpl` bigint unsigned NOT NULL,
  `kode_cpmk` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `level_bloom` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cpmk_kode_mk` (`id_mk`,`kode_cpmk`),
  KEY `cpmk_id_kurikulum_foreign` (`id_kurikulum`),
  KEY `idx_cpmk_cpl` (`id_cpl`),
  CONSTRAINT `cpmk_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cpmk_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cpmk_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cpmk`
--

LOCK TABLES `cpmk` WRITE;
/*!40000 ALTER TABLE `cpmk` DISABLE KEYS */;
INSERT INTO `cpmk` VALUES (1,2,1,1,'CPMK011','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Konsep Sistem Informasi yang terkait dengan capaian CPL01.',1,NULL,'2026-06-19 04:00:51'),(2,2,2,4,'CPMK041','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Pengantar Teknologi Informasi yang terkait dengan capaian CPL04.',1,NULL,NULL),(3,2,3,3,'CPMK031','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Pemrograman Dasar yang terkait dengan capaian CPL03.',1,NULL,NULL),(4,2,4,1,'CPMK011','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Sistem Informasi Manajemen yang terkait dengan capaian CPL01.',1,NULL,'2026-06-19 04:00:56'),(5,2,5,1,'CPMK011','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Sistem Basis Data yang terkait dengan capaian CPL01.',1,NULL,NULL),(6,2,5,2,'CPMK022','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Sistem Basis Data yang terkait dengan capaian CPL02.',2,NULL,'2026-06-19 04:02:18'),(7,2,5,3,'CPMK033','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Sistem Basis Data yang terkait dengan capaian CPL03.',3,NULL,'2026-06-19 04:03:47'),(8,2,6,4,'CPMK041','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Sistem Operasi yang terkait dengan capaian CPL04.',1,NULL,NULL),(9,2,7,2,'CPMK021','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Statistika dan Probabilitas yang terkait dengan capaian CPL02.',1,NULL,'2026-06-19 04:01:55'),(10,2,8,1,'CPMK011','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Sistem Basis Data Lanjut yang terkait dengan capaian CPL01.',1,NULL,NULL),(11,2,8,2,'CPMK022','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Sistem Basis Data Lanjut yang terkait dengan capaian CPL02.',2,NULL,'2026-06-19 04:02:24'),(12,2,8,3,'CPMK033','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Sistem Basis Data Lanjut yang terkait dengan capaian CPL03.',3,NULL,'2026-06-19 04:03:53'),(13,2,9,4,'CPMK041','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Jaringan Komputer yang terkait dengan capaian CPL04.',1,NULL,NULL),(14,2,10,3,'CPMK031','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Transformasi Digital yang terkait dengan capaian CPL03.',1,NULL,NULL),(15,2,10,6,'CPMK062','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Transformasi Digital yang terkait dengan capaian CPL06.',2,NULL,'2026-06-19 04:02:55'),(16,2,10,7,'CPMK073','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Transformasi Digital yang terkait dengan capaian CPL07.',3,NULL,'2026-06-19 04:01:20'),(17,2,11,3,'CPMK031','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Pemrograman Berorientasi Objek yang terkait dengan capaian CPL03.',1,NULL,NULL),(18,2,12,5,'CPMK051','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Kepemimpinan dan Manajemen Organisasi yang terkait dengan capaian CPL05.',1,NULL,'2026-06-19 04:01:40'),(19,2,13,2,'CPMK021','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Analisis dan Perancangan Sistem Informasi yang terkait dengan capaian CPL02.',1,NULL,'2026-06-19 04:02:00'),(20,2,13,3,'CPMK032','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Analisis dan Perancangan Sistem Informasi yang terkait dengan capaian CPL03.',2,NULL,'2026-06-19 04:03:24'),(21,2,13,6,'CPMK063','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Analisis dan Perancangan Sistem Informasi yang terkait dengan capaian CPL06.',3,NULL,'2026-06-19 04:02:49'),(22,2,14,3,'CPMK031','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Pemrograman Berbasis Web yang terkait dengan capaian CPL03.',1,NULL,NULL),(23,2,15,4,'CPMK041','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Keamanan Jaringan yang terkait dengan capaian CPL04.',1,NULL,NULL),(24,2,16,5,'CPMK051','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Etika Profesi dan Profesional yang terkait dengan capaian CPL05.',1,NULL,'2026-06-07 04:38:03'),(25,2,17,1,'CPMK011','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Manajemen Proyek Sistem Informasi yang terkait dengan capaian CPL01.',1,NULL,NULL),(26,2,17,2,'CPMK022','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Manajemen Proyek Sistem Informasi yang terkait dengan capaian CPL02.',2,NULL,NULL),(27,2,17,3,'CPMK033','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Manajemen Proyek Sistem Informasi yang terkait dengan capaian CPL03.',3,NULL,'2026-06-19 04:04:00'),(28,2,18,3,'CPMK031','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Tata Kelola Teknologi Informasi yang terkait dengan capaian CPL03.',1,NULL,NULL),(29,2,18,6,'CPMK062','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Tata Kelola Teknologi Informasi yang terkait dengan capaian CPL06.',2,NULL,'2026-06-19 04:03:07'),(30,2,18,7,'CPMK073','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Tata Kelola Teknologi Informasi yang terkait dengan capaian CPL07.',3,NULL,'2026-06-19 04:01:24'),(31,2,19,2,'CPMK021','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Software Testing dan Quality Assurance yang terkait dengan capaian CPL02.',1,NULL,'2026-06-19 04:02:06'),(32,2,19,3,'CPMK032','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Software Testing dan Quality Assurance yang terkait dengan capaian CPL03.',2,NULL,'2026-06-19 04:03:41'),(33,2,19,6,'CPMK063','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Software Testing dan Quality Assurance yang terkait dengan capaian CPL06.',3,NULL,NULL),(34,2,20,3,'CPMK031','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Audit Sistem Informasi yang terkait dengan capaian CPL03.',1,NULL,NULL),(35,2,20,6,'CPMK062','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Audit Sistem Informasi yang terkait dengan capaian CPL06.',2,NULL,'2026-06-19 04:03:13'),(36,2,20,7,'CPMK073','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Audit Sistem Informasi yang terkait dengan capaian CPL07.',3,NULL,'2026-06-19 04:02:43'),(37,2,21,4,'CPMK041','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Keamanan Sistem Informasi yang terkait dengan capaian CPL04.',1,NULL,NULL),(38,2,22,3,'CPMK031','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Kerja Praktek/Magang yang terkait dengan capaian CPL03.',1,NULL,NULL),(39,2,22,7,'CPMK072','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Kerja Praktek/Magang yang terkait dengan capaian CPL07.',2,NULL,'2026-06-19 04:02:34'),(40,2,23,1,'CPMK011','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Proyek Sistem Informasi yang terkait dengan capaian CPL01.',1,NULL,NULL),(41,2,23,2,'CPMK022','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Proyek Sistem Informasi yang terkait dengan capaian CPL02.',2,NULL,NULL),(42,2,23,3,'CPMK033','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Proyek Sistem Informasi yang terkait dengan capaian CPL03.',3,NULL,'2026-06-19 04:04:07'),(43,2,24,3,'CPMK031','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Metodologi Penelitian yang terkait dengan capaian CPL03.',1,NULL,NULL),(44,2,24,7,'CPMK072','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Metodologi Penelitian yang terkait dengan capaian CPL07.',2,NULL,'2026-06-19 04:04:45'),(45,2,25,3,'CPMK031','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Tugas Akhir yang terkait dengan capaian CPL03.',1,NULL,NULL),(46,2,25,7,'CPMK072','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Tugas Akhir yang terkait dengan capaian CPL07.',2,NULL,'2026-06-19 04:06:08'),(47,2,24,2,'CPMK021','Mahasiswa mampu mengintegrasikan database ke aplikasi web.',2,NULL,'2026-06-19 04:05:04'),(48,2,16,2,'CPMK021','Mahasiswa mampu merancang skema basis data.',1,NULL,'2026-06-19 04:05:09'),(49,2,12,5,'CPMK053','test',2,NULL,'2026-06-07 04:09:26'),(50,2,4,5,'CPMK054','Membuat sistem informasi',2,NULL,'2026-06-19 04:00:38'),(51,2,1,5,'CPMK051','Mahasiswa mampu memahami, menerapkan, dan menganalisis konsep Kepemimpinan dan Manajemen Organisasi yang terkait dengan capaian CPL05.',2,NULL,NULL),(52,2,1,5,'CPMK054','Membuat sistem informasi',3,NULL,'2026-06-19 04:01:47'),(53,2,1,3,'CPMK-MK01-4','Mahasiswa mampu memahami dan menerapkan konsep Konsep Sistem Informasi yang terkait dengan capaian CPL03.',4,NULL,'2026-06-19 04:03:37'),(54,2,1,4,'CPMK-MK01-5','Mahasiswa mampu memahami dan menerapkan konsep Konsep Sistem Informasi yang terkait dengan capaian CPL04.',5,NULL,'2026-06-19 04:04:20'),(55,2,7,6,'CPMK-MK23-2','Mahasiswa mampu memahami dan menerapkan konsep Statistika dan Probabilitas yang terkait dengan capaian CPL06.',2,NULL,'2026-06-19 04:03:00'),(56,2,27,1,'CPMK-MK27-1','Mahasiswa mampu memahami dan menerapkan konsep Bahasa Indonesia yang terkait dengan capaian CPL01.',1,NULL,'2026-06-19 04:01:02'),(57,2,27,2,'CPMK-MK27-2','Mahasiswa mampu memahami dan menerapkan konsep Bahasa Indonesia yang terkait dengan capaian CPL02.',2,NULL,'2026-06-19 04:02:13'),(58,2,27,3,'CPMK-MK27-3','Mahasiswa mampu memahami dan menerapkan konsep Bahasa Indonesia yang terkait dengan capaian CPL03.',3,NULL,'2026-06-19 04:03:32'),(59,2,27,4,'CPMK-MK27-4','Mahasiswa mampu memahami dan menerapkan konsep Bahasa Indonesia yang terkait dengan capaian CPL04.',4,NULL,'2026-06-19 04:04:28'),(60,2,1,5,'CPMK057','test',6,NULL,'2026-06-19 03:48:03'),(61,2,5,5,'CPMK-MK03-4','Mahasiswa mampu memahami dan menerapkan konsep Sistem Basis Data yang terkait dengan capaian CPL05.',4,NULL,'2026-06-19 04:01:35'),(62,2,20,1,'CPMK-MK13-4','Mahasiswa mampu memahami dan menerapkan konsep Audit Sistem Informasi yang terkait dengan capaian CPL01.',4,NULL,'2026-06-19 04:01:11'),(63,2,20,2,'CPMK-MK13-5','Mahasiswa mampu memahami dan menerapkan konsep Audit Sistem Informasi yang terkait dengan capaian CPL02.',5,NULL,'2026-06-19 04:01:51'),(64,2,26,1,'CPMK019','test',1,NULL,'2026-06-19 04:00:44');
/*!40000 ALTER TABLE `cpmk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cpmk_penilaian`
--

DROP TABLE IF EXISTS `cpmk_penilaian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cpmk_penilaian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_cpmk` bigint unsigned NOT NULL,
  `skor_maks` decimal(5,2) DEFAULT NULL COMMENT 'Skor maks per CPMK (default = sum bobot penilaian)',
  `tahap_penilaian` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Awal-Tengah Semester',
  `teknik_quiz` tinyint(1) NOT NULL DEFAULT '0',
  `teknik_observasi` tinyint(1) NOT NULL DEFAULT '0',
  `teknik_unjuk_kerja` tinyint(1) NOT NULL DEFAULT '0',
  `teknik_uts` tinyint(1) NOT NULL DEFAULT '0',
  `teknik_uas` tinyint(1) NOT NULL DEFAULT '0',
  `teknik_tes_lisan` tinyint(1) NOT NULL DEFAULT '0',
  `bobot_quiz` decimal(5,2) DEFAULT NULL,
  `bobot_observasi` decimal(5,2) DEFAULT NULL,
  `bobot_unjuk_kerja` decimal(5,2) DEFAULT NULL,
  `bobot_uts` decimal(5,2) DEFAULT NULL,
  `bobot_uas` decimal(5,2) DEFAULT NULL,
  `bobot_tes_lisan` decimal(5,2) DEFAULT NULL,
  `instrumen` text COLLATE utf8mb4_unicode_ci,
  `kriteria` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cpmk_penilaian` (`id_cpmk`),
  CONSTRAINT `cpmk_penilaian_id_cpmk_foreign` FOREIGN KEY (`id_cpmk`) REFERENCES `cpmk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cpmk_penilaian`
--

LOCK TABLES `cpmk_penilaian` WRITE;
/*!40000 ALTER TABLE `cpmk_penilaian` DISABLE KEYS */;
INSERT INTO `cpmk_penilaian` VALUES (1,18,100.00,'Awal-Tengah Semester',1,1,1,1,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,24,100.00,'Awal-Tengah Semester',1,1,0,1,1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,6,100.00,'Awal-Tengah Semester',1,0,0,1,1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,9,100.00,'Awal-Tengah Semester',1,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,11,100.00,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,19,100.00,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,48,100.00,'Awal-Tengah Semester',1,1,0,1,1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(8,26,100.00,'Awal-Tengah Semester',0,0,0,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9,31,100.00,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(10,41,100.00,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(11,47,100.00,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(12,3,100.00,'Awal-Tengah Semester',1,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(13,7,100.00,'Awal-Tengah Semester',0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(14,12,100.00,'Awal-Tengah Semester',1,1,0,1,1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(15,14,100.00,'Awal-Tengah Semester',0,0,0,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(16,17,100.00,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,20,100.00,'Awal-Tengah Semester',0,1,1,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(18,22,100.00,'Awal-Tengah Semester',0,0,0,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(19,27,100.00,'Awal-Tengah Semester',1,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(20,28,100.00,'Awal-Tengah Semester',0,1,0,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(21,32,100.00,'Awal-Tengah Semester',0,1,0,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(22,34,100.00,'Awal-Tengah Semester',0,0,0,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(23,38,100.00,'Awal-Tengah Semester',0,0,0,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(24,42,100.00,'Awal-Tengah Semester',1,0,0,1,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,43,100.00,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(26,45,100.00,'Awal-Tengah Semester',1,0,1,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,2,100.00,'Awal-Tengah Semester',0,0,0,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,8,100.00,'Awal-Tengah Semester',0,1,0,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(29,13,100.00,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(30,23,100.00,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(31,37,100.00,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(32,15,100.00,'Awal-Tengah Semester',1,0,1,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(33,21,100.00,'Awal-Tengah Semester',1,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(34,29,100.00,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(35,33,100.00,'Awal-Tengah Semester',1,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(36,35,100.00,'Awal-Tengah Semester',0,0,1,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(37,16,100.00,'Awal-Tengah Semester',0,1,0,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(38,30,100.00,'Awal-Tengah Semester',1,0,1,1,1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(39,36,100.00,'Awal-Tengah Semester',1,1,0,1,1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(40,39,100.00,'Awal-Tengah Semester',1,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(41,44,100.00,'Awal-Tengah Semester',0,1,0,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(42,46,100.00,'Awal-Tengah Semester',0,0,0,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(43,1,70.00,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(44,4,100.00,'Awal-Tengah Semester',0,1,0,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(45,5,100.00,'Awal-Tengah Semester',0,0,1,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(46,10,100.00,'Awal-Tengah Semester',0,0,1,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(47,25,100.00,'Awal-Tengah Semester',0,0,1,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(48,40,100.00,'Awal-Tengah Semester',0,1,1,1,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(49,51,NULL,'Awal-Tengah Semester',1,1,1,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(50,52,NULL,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(51,50,NULL,'Awal-Tengah Semester',0,0,0,1,1,1,NULL,NULL,NULL,20.00,50.00,29.99,NULL,NULL),(52,53,NULL,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(53,54,NULL,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(54,55,NULL,'Awal-Tengah Semester',0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `cpmk_penilaian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distribusi_semester`
--

DROP TABLE IF EXISTS `distribusi_semester`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distribusi_semester` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_kurikulum` bigint unsigned NOT NULL,
  `semester` int NOT NULL,
  `total_sks` int NOT NULL DEFAULT '0',
  `jumlah_mk` int NOT NULL DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_dist_kur_smt` (`id_kurikulum`,`semester`),
  CONSTRAINT `distribusi_semester_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distribusi_semester`
--

LOCK TABLES `distribusi_semester` WRITE;
/*!40000 ALTER TABLE `distribusi_semester` DISABLE KEYS */;
INSERT INTO `distribusi_semester` VALUES (1,2,1,13,5,NULL),(2,2,2,9,3,NULL),(3,2,3,18,6,NULL),(4,2,4,15,5,NULL),(5,2,5,6,2,NULL),(6,2,6,9,3,NULL),(7,2,7,7,2,NULL),(8,2,8,6,1,NULL);
/*!40000 ALTER TABLE `distribusi_semester` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enrollment_mk`
--

DROP TABLE IF EXISTS `enrollment_mk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enrollment_mk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` bigint unsigned NOT NULL,
  `id_mk` bigint unsigned NOT NULL,
  `id_semester` bigint unsigned NOT NULL,
  `tanggal_daftar` date DEFAULT NULL,
  `status` enum('aktif','mengulang','lulus','tidak_lulus') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_enroll` (`id_mahasiswa`,`id_mk`,`id_semester`),
  KEY `enrollment_mk_id_mk_foreign` (`id_mk`),
  KEY `enrollment_mk_id_semester_foreign` (`id_semester`),
  CONSTRAINT `enrollment_mk_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollment_mk_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollment_mk_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enrollment_mk`
--

LOCK TABLES `enrollment_mk` WRITE;
/*!40000 ALTER TABLE `enrollment_mk` DISABLE KEYS */;
INSERT INTO `enrollment_mk` VALUES (4,8,1,4,'2026-06-07','aktif'),(5,11,1,4,'2026-06-07','aktif'),(6,9,1,4,'2026-06-07','aktif'),(7,10,1,4,'2026-06-07','aktif'),(8,8,4,4,'2026-06-19','aktif'),(9,11,4,4,'2026-06-19','aktif'),(10,9,4,4,'2026-06-19','aktif'),(11,10,4,4,'2026-06-19','aktif');
/*!40000 ALTER TABLE `enrollment_mk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
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
-- Table structure for table `hasil_cpl`
--

DROP TABLE IF EXISTS `hasil_cpl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hasil_cpl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` bigint unsigned NOT NULL,
  `id_cpl` bigint unsigned NOT NULL,
  `id_kurikulum` bigint unsigned NOT NULL,
  `id_semester` bigint unsigned NOT NULL,
  `nilai_cpl` decimal(6,2) NOT NULL,
  `skor_maks` decimal(6,2) NOT NULL DEFAULT '100.00',
  `total` decimal(6,2) DEFAULT NULL,
  `status_tercapai` tinyint NOT NULL DEFAULT '0',
  `recalculated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hk_cpl` (`id_mahasiswa`,`id_cpl`,`id_semester`),
  KEY `hasil_cpl_id_cpl_foreign` (`id_cpl`),
  KEY `hasil_cpl_id_semester_foreign` (`id_semester`),
  KEY `idx_hk_cpl_kur` (`id_kurikulum`,`id_cpl`,`id_semester`),
  CONSTRAINT `hasil_cpl_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_cpl_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_cpl_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_cpl_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hasil_cpl`
--

LOCK TABLES `hasil_cpl` WRITE;
/*!40000 ALTER TABLE `hasil_cpl` DISABLE KEYS */;
INSERT INTO `hasil_cpl` VALUES (1,8,3,2,4,80.80,100.00,NULL,1,'2026-06-15 16:49:41'),(2,8,2,2,4,82.00,100.00,NULL,1,'2026-05-31 16:25:52'),(3,9,3,2,4,72.00,100.00,NULL,1,'2026-05-31 16:25:52'),(4,9,2,2,4,68.00,100.00,NULL,0,'2026-05-31 16:25:52'),(5,10,3,2,4,88.80,100.00,NULL,1,'2026-05-31 16:25:52'),(6,10,2,2,4,92.00,100.00,NULL,1,'2026-05-31 16:25:52'),(7,8,1,2,4,28.57,100.00,NULL,0,'2026-06-15 16:54:22'),(8,11,1,2,4,28.57,100.00,NULL,0,'2026-06-15 16:54:22'),(9,9,1,2,4,28.57,100.00,NULL,0,'2026-06-15 16:54:22'),(10,10,1,2,4,100.00,100.00,NULL,1,'2026-06-15 16:54:22');
/*!40000 ALTER TABLE `hasil_cpl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hasil_cpmk`
--

DROP TABLE IF EXISTS `hasil_cpmk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hasil_cpmk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` bigint unsigned NOT NULL,
  `id_cpmk` bigint unsigned NOT NULL,
  `id_semester` bigint unsigned NOT NULL,
  `nilai` decimal(6,2) NOT NULL,
  `recalculated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hk_cpmk` (`id_mahasiswa`,`id_cpmk`,`id_semester`),
  KEY `hasil_cpmk_id_cpmk_foreign` (`id_cpmk`),
  KEY `hasil_cpmk_id_semester_foreign` (`id_semester`),
  CONSTRAINT `hasil_cpmk_id_cpmk_foreign` FOREIGN KEY (`id_cpmk`) REFERENCES `cpmk` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_cpmk_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_cpmk_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hasil_cpmk`
--

LOCK TABLES `hasil_cpmk` WRITE;
/*!40000 ALTER TABLE `hasil_cpmk` DISABLE KEYS */;
INSERT INTO `hasil_cpmk` VALUES (1,8,43,4,80.80,'2026-05-31 16:25:52'),(2,8,47,4,82.00,'2026-05-31 16:25:52'),(3,9,43,4,72.00,'2026-05-31 16:25:52'),(4,9,47,4,68.00,'2026-05-31 16:25:52'),(5,10,43,4,88.80,'2026-05-31 16:25:52'),(6,10,47,4,92.00,'2026-05-31 16:25:52'),(8,8,1,4,28.57,'2026-06-15 16:54:22'),(9,11,1,4,28.57,'2026-06-15 16:54:22'),(10,9,1,4,28.57,'2026-06-15 16:54:22'),(11,10,1,4,100.00,'2026-06-15 16:54:22');
/*!40000 ALTER TABLE `hasil_cpmk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hasil_pl`
--

DROP TABLE IF EXISTS `hasil_pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hasil_pl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` bigint unsigned NOT NULL,
  `id_pl` bigint unsigned NOT NULL,
  `id_kurikulum` bigint unsigned NOT NULL,
  `id_semester` bigint unsigned NOT NULL,
  `nilai_pl` decimal(6,2) NOT NULL,
  `status_tercapai` tinyint NOT NULL DEFAULT '0',
  `recalculated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hk_pl` (`id_mahasiswa`,`id_pl`,`id_semester`),
  KEY `hasil_pl_id_pl_foreign` (`id_pl`),
  KEY `hasil_pl_id_kurikulum_foreign` (`id_kurikulum`),
  KEY `hasil_pl_id_semester_foreign` (`id_semester`),
  CONSTRAINT `hasil_pl_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_pl_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_pl_id_pl_foreign` FOREIGN KEY (`id_pl`) REFERENCES `pl` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_pl_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hasil_pl`
--

LOCK TABLES `hasil_pl` WRITE;
/*!40000 ALTER TABLE `hasil_pl` DISABLE KEYS */;
INSERT INTO `hasil_pl` VALUES (1,8,1,2,4,80.80,1,'2026-06-15 16:49:41'),(2,8,2,2,4,81.40,1,'2026-06-15 16:49:41'),(3,9,1,2,4,72.00,1,'2026-06-15 16:45:19'),(4,9,2,2,4,70.00,1,'2026-06-15 16:45:19'),(5,10,1,2,4,88.80,1,'2026-06-15 16:45:19'),(6,10,2,2,4,90.40,1,'2026-06-15 16:45:19');
/*!40000 ALTER TABLE `hasil_pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hasil_sub_cpmk`
--

DROP TABLE IF EXISTS `hasil_sub_cpmk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hasil_sub_cpmk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` bigint unsigned NOT NULL,
  `id_sub_cpmk` bigint unsigned NOT NULL,
  `id_semester` bigint unsigned NOT NULL,
  `nilai` decimal(6,2) NOT NULL,
  `recalculated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hk_sc` (`id_mahasiswa`,`id_sub_cpmk`,`id_semester`),
  KEY `hasil_sub_cpmk_id_sub_cpmk_foreign` (`id_sub_cpmk`),
  KEY `hasil_sub_cpmk_id_semester_foreign` (`id_semester`),
  CONSTRAINT `hasil_sub_cpmk_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_sub_cpmk_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_sub_cpmk_id_sub_cpmk_foreign` FOREIGN KEY (`id_sub_cpmk`) REFERENCES `sub_cpmk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hasil_sub_cpmk`
--

LOCK TABLES `hasil_sub_cpmk` WRITE;
/*!40000 ALTER TABLE `hasil_sub_cpmk` DISABLE KEYS */;
/*!40000 ALTER TABLE `hasil_sub_cpmk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
-- Table structure for table `jurnal_mengajar`
--

DROP TABLE IF EXISTS `jurnal_mengajar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jurnal_mengajar` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_rps_pertemuan` bigint unsigned NOT NULL,
  `id_dosen` bigint unsigned NOT NULL,
  `tanggal_pelaksanaan` date NOT NULL,
  `realisasi_materi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_hadir` int DEFAULT NULL,
  `file_bukti_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jurnal_mengajar_id_rps_pertemuan_foreign` (`id_rps_pertemuan`),
  KEY `jurnal_mengajar_id_dosen_foreign` (`id_dosen`),
  CONSTRAINT `jurnal_mengajar_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jurnal_mengajar_id_rps_pertemuan_foreign` FOREIGN KEY (`id_rps_pertemuan`) REFERENCES `rps_pertemuan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jurnal_mengajar`
--

LOCK TABLES `jurnal_mengajar` WRITE;
/*!40000 ALTER TABLE `jurnal_mengajar` DISABLE KEYS */;
/*!40000 ALTER TABLE `jurnal_mengajar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `komentar_review`
--

DROP TABLE IF EXISTS `komentar_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `komentar_review` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `id_user` bigint unsigned NOT NULL,
  `konten` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `elemen` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('open','resolved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `komentar_review_id_user_foreign` (`id_user`),
  KEY `komentar_review_model_type_model_id_index` (`model_type`,`model_id`),
  CONSTRAINT `komentar_review_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `komentar_review`
--

LOCK TABLES `komentar_review` WRITE;
/*!40000 ALTER TABLE `komentar_review` DISABLE KEYS */;
/*!40000 ALTER TABLE `komentar_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `komponen_asesmen`
--

DROP TABLE IF EXISTS `komponen_asesmen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `komponen_asesmen` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mk` bigint unsigned NOT NULL,
  `id_semester` bigint unsigned NOT NULL,
  `id_sub_cpmk` bigint unsigned DEFAULT NULL,
  `nama_komponen` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_komponen` enum('Partisipasi','Observasi','Unjuk Kerja','UTS','UAS','Lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot_persen` decimal(5,2) NOT NULL,
  `skor_maks` decimal(5,2) NOT NULL DEFAULT '100.00',
  PRIMARY KEY (`id`),
  KEY `komponen_asesmen_id_semester_foreign` (`id_semester`),
  KEY `komponen_asesmen_id_sub_cpmk_foreign` (`id_sub_cpmk`),
  KEY `idx_komponen_mk_smt` (`id_mk`,`id_semester`),
  CONSTRAINT `komponen_asesmen_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `komponen_asesmen_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE,
  CONSTRAINT `komponen_asesmen_id_sub_cpmk_foreign` FOREIGN KEY (`id_sub_cpmk`) REFERENCES `sub_cpmk` (`id`) ON DELETE SET NULL,
  CONSTRAINT `chk_bobot` CHECK ((`bobot_persen` between 0 and 100))
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `komponen_asesmen`
--

LOCK TABLES `komponen_asesmen` WRITE;
/*!40000 ALTER TABLE `komponen_asesmen` DISABLE KEYS */;
INSERT INTO `komponen_asesmen` VALUES (1,1,4,1,'Quiz','Partisipasi',10.00,100.00),(2,1,4,1,'Tugas','Unjuk Kerja',20.00,100.00),(3,1,4,1,'UTS','UTS',30.00,100.00),(4,1,4,2,'UAS','UAS',40.00,100.00),(5,2,4,3,'Quiz','Partisipasi',10.00,100.00),(6,2,4,3,'Tugas','Unjuk Kerja',20.00,100.00),(7,2,4,3,'UTS','UTS',30.00,100.00),(8,2,4,4,'UAS','UAS',40.00,100.00),(9,3,4,5,'Quiz','Partisipasi',10.00,100.00),(10,3,4,5,'Tugas','Unjuk Kerja',20.00,100.00),(11,3,4,5,'UTS','UTS',30.00,100.00),(12,3,4,6,'UAS','UAS',40.00,100.00),(13,4,4,7,'Quiz','Partisipasi',10.00,100.00),(14,4,4,7,'Tugas','Unjuk Kerja',20.00,100.00),(15,4,4,7,'UTS','UTS',30.00,100.00),(16,4,4,8,'UAS','UAS',40.00,100.00),(17,5,4,9,'Quiz','Partisipasi',10.00,100.00),(18,5,4,9,'Tugas','Unjuk Kerja',20.00,100.00),(19,5,4,9,'UTS','UTS',30.00,100.00),(20,5,4,10,'UAS','UAS',40.00,100.00),(21,6,4,15,'Quiz','Partisipasi',10.00,100.00),(22,6,4,15,'Tugas','Unjuk Kerja',20.00,100.00),(23,6,4,15,'UTS','UTS',30.00,100.00),(24,6,4,16,'UAS','UAS',40.00,100.00),(25,7,4,17,'Quiz','Partisipasi',10.00,100.00),(26,7,4,17,'Tugas','Unjuk Kerja',20.00,100.00),(27,7,4,17,'UTS','UTS',30.00,100.00),(28,7,4,18,'UAS','UAS',40.00,100.00),(29,8,4,19,'Quiz','Partisipasi',10.00,100.00),(30,8,4,19,'Tugas','Unjuk Kerja',20.00,100.00),(31,8,4,19,'UTS','UTS',30.00,100.00),(32,8,4,20,'UAS','UAS',40.00,100.00),(33,9,4,25,'Quiz','Partisipasi',10.00,100.00),(34,9,4,25,'Tugas','Unjuk Kerja',20.00,100.00),(35,9,4,25,'UTS','UTS',30.00,100.00),(36,9,4,26,'UAS','UAS',40.00,100.00),(37,10,4,27,'Quiz','Partisipasi',10.00,100.00),(38,10,4,27,'Tugas','Unjuk Kerja',20.00,100.00),(39,10,4,27,'UTS','UTS',30.00,100.00),(40,10,4,28,'UAS','UAS',40.00,100.00),(41,11,4,33,'Quiz','Partisipasi',10.00,100.00),(42,11,4,33,'Tugas','Unjuk Kerja',20.00,100.00),(43,11,4,33,'UTS','UTS',30.00,100.00),(44,11,4,34,'UAS','UAS',40.00,100.00),(45,12,4,35,'Quiz','Partisipasi',10.00,100.00),(46,12,4,35,'Tugas','Unjuk Kerja',20.00,100.00),(47,12,4,35,'UTS','UTS',30.00,100.00),(48,12,4,36,'UAS','UAS',40.00,100.00),(49,13,4,37,'Quiz','Partisipasi',10.00,100.00),(50,13,4,37,'Tugas','Unjuk Kerja',20.00,100.00),(51,13,4,37,'UTS','UTS',30.00,100.00),(52,13,4,38,'UAS','UAS',40.00,100.00),(53,14,4,43,'Quiz','Partisipasi',10.00,100.00),(54,14,4,43,'Tugas','Unjuk Kerja',20.00,100.00),(55,14,4,43,'UTS','UTS',30.00,100.00),(56,14,4,44,'UAS','UAS',40.00,100.00),(57,15,4,45,'Quiz','Partisipasi',10.00,100.00),(58,15,4,45,'Tugas','Unjuk Kerja',20.00,100.00),(59,15,4,45,'UTS','UTS',30.00,100.00),(60,15,4,46,'UAS','UAS',40.00,100.00),(61,16,4,47,'Quiz','Partisipasi',10.00,100.00),(62,16,4,47,'Tugas','Unjuk Kerja',20.00,100.00),(63,16,4,47,'UTS','UTS',30.00,100.00),(64,16,4,48,'UAS','UAS',40.00,100.00),(65,17,4,49,'Quiz','Partisipasi',10.00,100.00),(66,17,4,49,'Tugas','Unjuk Kerja',20.00,100.00),(67,17,4,49,'UTS','UTS',30.00,100.00),(68,17,4,50,'UAS','UAS',40.00,100.00),(69,18,4,55,'Quiz','Partisipasi',10.00,100.00),(70,18,4,55,'Tugas','Unjuk Kerja',20.00,100.00),(71,18,4,55,'UTS','UTS',30.00,100.00),(72,18,4,56,'UAS','UAS',40.00,100.00),(73,19,4,61,'Quiz','Partisipasi',10.00,100.00),(74,19,4,61,'Tugas','Unjuk Kerja',20.00,100.00),(75,19,4,61,'UTS','UTS',30.00,100.00),(76,19,4,62,'UAS','UAS',40.00,100.00),(77,20,4,67,'Quiz','Partisipasi',10.00,100.00),(78,20,4,67,'Tugas','Unjuk Kerja',20.00,100.00),(79,20,4,67,'UTS','UTS',30.00,100.00),(80,20,4,68,'UAS','UAS',40.00,100.00),(81,21,4,73,'Quiz','Partisipasi',10.00,100.00),(82,21,4,73,'Tugas','Unjuk Kerja',20.00,100.00),(83,21,4,73,'UTS','UTS',30.00,100.00),(84,21,4,74,'UAS','UAS',40.00,100.00),(85,22,4,75,'Quiz','Partisipasi',10.00,100.00),(86,22,4,75,'Tugas','Unjuk Kerja',20.00,100.00),(87,22,4,75,'UTS','UTS',30.00,100.00),(88,22,4,76,'UAS','UAS',40.00,100.00),(89,23,4,79,'Quiz','Partisipasi',10.00,100.00),(90,23,4,79,'Tugas','Unjuk Kerja',20.00,100.00),(91,23,4,79,'UTS','UTS',30.00,100.00),(92,23,4,80,'UAS','UAS',40.00,100.00),(93,24,4,85,'Quiz','Partisipasi',10.00,100.00),(94,24,4,85,'Tugas','Unjuk Kerja',20.00,100.00),(95,24,4,85,'UTS','UTS',30.00,100.00),(96,24,4,86,'UAS','UAS',40.00,100.00),(97,25,4,89,'Quiz','Partisipasi',10.00,100.00),(98,25,4,89,'Tugas','Unjuk Kerja',20.00,100.00),(99,25,4,89,'UTS','UTS',30.00,100.00),(100,25,4,NULL,'UAS','UAS',40.00,100.00),(101,24,4,85,'Tugas Mingguan','Unjuk Kerja',30.00,100.00);
/*!40000 ALTER TABLE `komponen_asesmen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kurikulum`
--

DROP TABLE IF EXISTS `kurikulum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kurikulum` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kurikulum` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_studi` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenjang` enum('D3','D4','S1','S2','S3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S1',
  `tahun_mulai` year NOT NULL,
  `tahun_selesai` year NOT NULL,
  `visi` text COLLATE utf8mb4_unicode_ci,
  `misi` text COLLATE utf8mb4_unicode_ci,
  `tujuan` text COLLATE utf8mb4_unicode_ci,
  `sasaran` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','aktif','arsip') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `locked_at` timestamp NULL DEFAULT NULL,
  `locked_by` bigint unsigned DEFAULT NULL,
  `dibuat_oleh` bigint unsigned DEFAULT NULL,
  `disahkan_oleh` bigint unsigned DEFAULT NULL,
  `disahkan_pada` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_kurikulum_kode` (`kode`),
  KEY `idx_kur_status` (`status`),
  KEY `fk_kur_locked` (`locked_by`),
  KEY `fk_kur_dibuat` (`dibuat_oleh`),
  KEY `fk_kur_disahkan` (`disahkan_oleh`),
  CONSTRAINT `fk_kur_dibuat` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_kur_disahkan` FOREIGN KEY (`disahkan_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_kur_locked` FOREIGN KEY (`locked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kurikulum`
--

LOCK TABLES `kurikulum` WRITE;
/*!40000 ALTER TABLE `kurikulum` DISABLE KEYS */;
INSERT INTO `kurikulum` VALUES (2,'K-SI-S1-2021','Kurikulum Sistem Informasi 2021','Sistem Informasi','S1',2021,2025,'Menjadi program studi unggul dalam bidang sistem informasi berbasis OBE.','Menyelenggarakan pendidikan berkualitas, penelitian inovatif, dan pengabdian masyarakat.','Menghasilkan lulusan yang kompeten, adaptif, dan berintegritas.','Mahasiswa Sistem Informasi angkatan 2021 ke atas.','aktif',NULL,NULL,4,NULL,NULL,'2026-05-31 09:23:19','2026-05-31 09:23:19');
/*!40000 ALTER TABLE `kurikulum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_kategori`
--

DROP TABLE IF EXISTS `master_kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `master_kategori` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jenis` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `urutan` int NOT NULL DEFAULT '0',
  `is_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `master_kategori_jenis_nama_unique` (`jenis`,`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_kategori`
--

LOCK TABLES `master_kategori` WRITE;
/*!40000 ALTER TABLE `master_kategori` DISABLE KEYS */;
INSERT INTO `master_kategori` VALUES (1,'pl','PL Penciri Utama',NULL,1,1,'2026-05-30 06:47:58','2026-05-31 21:38:35'),(2,'pl','PL Tambahan KK dan P',NULL,2,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(3,'pl','PL Sikap',NULL,3,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(4,'pl','PL Keterampilan Umum dan Sikap',NULL,4,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(5,'cpl','Sikap',NULL,1,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(6,'cpl','Keterampilan Umum',NULL,2,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(7,'cpl','Keterampilan Khusus',NULL,3,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(8,'cpl','Pengetahuan',NULL,4,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(9,'bk','Ilmu Dasar',NULL,1,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(10,'bk','Ilmu Terapan',NULL,2,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(11,'bk','Teknologi Informasi',NULL,3,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(12,'bk','Matematika & Statistika',NULL,4,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(13,'bk','Manajemen Bisnis',NULL,5,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(14,'bk','Sosial Humaniora',NULL,6,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(15,'mk','Wajib',NULL,1,1,'2026-05-30 06:47:58','2026-06-07 18:10:36'),(16,'mk','Pilihan',NULL,2,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(17,'mk','MKWK',NULL,3,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(18,'mk','MKDU',NULL,4,1,'2026-05-30 06:47:58','2026-05-30 06:47:58'),(21,'mata_kuliah_asing','Kategori A',NULL,1,0,'2026-06-07 18:26:48','2026-06-07 18:27:05');
/*!40000 ALTER TABLE `master_kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mata_kuliah`
--

DROP TABLE IF EXISTS `mata_kuliah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mata_kuliah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_kurikulum` bigint unsigned NOT NULL,
  `kode_mk` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_mk` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks_teori` int NOT NULL DEFAULT '0',
  `sks_praktikum` int NOT NULL DEFAULT '0',
  `sks_total` int GENERATED ALWAYS AS ((`sks_teori` + `sks_praktikum`)) STORED,
  `semester` int NOT NULL,
  `kategori_mk` enum('Wajib','Pilihan','MKWK','MKDU') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kompetensi_mk` enum('Utama','Pendukung') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Utama',
  `kode_prasyarat` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konsentrasi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_mk_kode` (`id_kurikulum`,`kode_mk`),
  KEY `idx_mk_semester` (`id_kurikulum`,`semester`),
  CONSTRAINT `mata_kuliah_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mata_kuliah`
--

LOCK TABLES `mata_kuliah` WRITE;
/*!40000 ALTER TABLE `mata_kuliah` DISABLE KEYS */;
INSERT INTO `mata_kuliah` (`id`, `id_kurikulum`, `kode_mk`, `nama_mk`, `sks_teori`, `sks_praktikum`, `semester`, `kategori_mk`, `kompetensi_mk`, `kode_prasyarat`, `konsentrasi`, `deleted_at`) VALUES (1,2,'MK01','Konsep Sistem Informasi',3,0,1,'Wajib','Utama',NULL,NULL,NULL),(2,2,'MK07','Pengantar Teknologi Informasi',3,0,1,'Wajib','Utama',NULL,NULL,NULL),(3,2,'MK14','Pemrograman Dasar',2,1,1,'Wajib','Utama',NULL,NULL,NULL),(4,2,'MK02','Sistem Informasi Manajemen',3,0,2,'Wajib','Utama',NULL,NULL,NULL),(5,2,'MK03','Sistem Basis Data',2,1,3,'Wajib','Utama',NULL,NULL,NULL),(6,2,'MK05','Sistem Operasi',3,0,2,'Wajib','Utama',NULL,NULL,NULL),(7,2,'MK23','Statistika dan Probabilitas',3,0,2,'Wajib','Utama',NULL,NULL,NULL),(8,2,'MK04','Sistem Basis Data Lanjut',2,1,4,'Wajib','Utama',NULL,NULL,NULL),(9,2,'MK06','Jaringan Komputer',2,1,3,'Wajib','Utama',NULL,NULL,NULL),(10,2,'MK15','Transformasi Digital',3,0,3,'MKWK','Utama',NULL,NULL,NULL),(11,2,'MK16','Pemrograman Berorientasi Objek',2,1,3,'Wajib','Utama',NULL,NULL,NULL),(12,2,'MK20','Kepemimpinan dan Manajemen Organisasi',3,0,3,'Wajib','Utama',NULL,NULL,NULL),(13,2,'MK10','Analisis dan Perancangan Sistem Informasi',3,0,4,'Wajib','Utama',NULL,NULL,NULL),(14,2,'MK17','Pemrograman Berbasis Web',2,1,4,'Wajib','Utama',NULL,NULL,NULL),(15,2,'MK18','Keamanan Jaringan',3,0,4,'Wajib','Utama',NULL,NULL,NULL),(16,2,'MK21','Etika Profesi dan Profesional',3,0,4,'Wajib','Utama',NULL,NULL,NULL),(17,2,'MK08','Manajemen Proyek Sistem Informasi',3,0,5,'Wajib','Utama',NULL,NULL,NULL),(18,2,'MK12','Tata Kelola Teknologi Informasi',3,0,5,NULL,'Utama',NULL,NULL,NULL),(19,2,'MK11','Software Testing dan Quality Assurance',2,1,3,'MKWK','Utama',NULL,NULL,NULL),(20,2,'MK13','Audit Sistem Informasi',3,0,6,'Wajib','Utama',NULL,NULL,NULL),(21,2,'MK19','Keamanan Sistem Informasi',3,0,6,'Wajib','Utama',NULL,NULL,NULL),(22,2,'MK25','Kerja Praktek/Magang',0,3,6,'Wajib','Utama',NULL,NULL,NULL),(23,2,'MK09','Proyek Sistem Informasi',2,2,7,NULL,'Utama',NULL,NULL,NULL),(24,2,'MK22','Metodologi Penelitian',3,0,7,'Wajib','Utama',NULL,NULL,NULL),(25,2,'MK24','Tugas Akhir',0,6,8,'MKDU','Utama',NULL,NULL,NULL),(26,2,'MK26','Agama',2,0,1,'Wajib','Utama',NULL,NULL,NULL),(27,2,'MK27','Bahasa Indonesia',2,0,1,'Wajib','Utama',NULL,NULL,NULL),(28,2,'MK28','Bahasa Indonesia',2,0,1,'Wajib','Utama',NULL,NULL,'2026-06-07 18:43:04');
/*!40000 ALTER TABLE `mata_kuliah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_01_01_000001_create_kurikulum_table',1),(5,'2025_01_01_000002_create_semester_akademik_table',1),(7,'2025_01_01_000004_create_arsip_rapat_table',1),(9,'2025_01_01_000006_create_pl_table',1),(10,'2025_01_01_000007_create_cpl_sndikti_table',1),(11,'2025_01_01_000008_create_cpl_prodi_table',1),(12,'2025_01_01_000009_create_bahan_kajian_table',1),(13,'2025_01_01_000010_create_mata_kuliah_table',1),(14,'2025_01_01_000011_create_cpmk_table',1),(15,'2025_01_01_000012_create_sub_cpmk_table',1),(16,'2025_01_01_000013_create_distribusi_semester_table',1),(17,'2025_01_01_000014_create_pivot_pl_cpl_table',1),(18,'2025_01_01_000015_create_pivot_cplsn_cplp_table',1),(19,'2025_01_01_000016_create_pivot_cpl_bk_table',1),(20,'2025_01_01_000017_create_pivot_mk_bk_table',1),(21,'2025_01_01_000018_create_pivot_cpl_cpmk_table',1),(22,'2025_01_01_000019_create_pivot_mk_cpl_table',1),(23,'2025_01_01_000020_create_pivot_cpl_bk_mk_table',1),(24,'2025_01_01_000021_create_pengampuan_mk_table',1),(25,'2025_01_01_000022_create_rps_header_table',1),(26,'2025_01_01_000023_create_rps_detail_mk_table',1),(27,'2025_01_01_000024_create_rps_pertemuan_table',1),(28,'2025_01_01_000025_create_jurnal_mengajar_table',1),(29,'2025_01_01_000026_create_repositori_materi_table',1),(30,'2025_01_01_000027_create_komponen_asesmen_table',1),(31,'2025_01_01_000028_create_enrollment_mk_table',1),(32,'2025_01_01_000029_create_nilai_mahasiswa_table',1),(33,'2025_01_01_000030_create_hasil_sub_cpmk_table',1),(34,'2025_01_01_000031_create_hasil_cpmk_table',1),(35,'2025_01_01_000032_create_hasil_cpl_table',1),(36,'2025_01_01_000033_create_hasil_pl_table',1),(37,'2025_01_01_000034_create_batas_ketercapaian_table',1),(38,'2025_01_01_000035_create_log_evaluasi_cqi_table',1),(39,'2025_01_01_000036_create_activity_log_table',1),(40,'2025_01_01_000037_add_foreign_keys_circular',1),(41,'2025_06_01_000001_add_missing_fields',2),(42,'2025_06_01_000002_create_missing_tables',2),(43,'2025_06_02_000001_create_master_kategori_table',3),(44,'2025_06_03_000001_add_konsentrasi_to_mata_kuliah',4),(45,'2026_05_31_161844_add_obe_fields_to_tables',4),(46,'2026_06_01_000001_create_cpmk_penilaian_table',5),(47,'2026_06_01_100001_add_skor_maks_to_cpmk_penilaian',6),(48,'2026_06_01_000001_extend_rps_tables',7),(49,'2026_06_07_000001_drop_removed_features',8),(50,'2026_06_07_000002_add_sks_to_rps_header',9),(51,'2026_06_07_043005_create_evaluasi_eksternal_table',10),(52,'2026_06_07_153026_make_kategori_mk_nullable_in_mata_kuliah',11),(53,'2026_06_08_004354_change_jenis_to_varchar_in_master_kategori',12),(54,'2026_06_13_000001_add_status_to_cpl_bk_tables',13),(55,'2026_06_15_000000_drop_log_evaluasi_cqi_and_evaluasi_eksternal_tables',14),(56,'2026_06_16_000000_add_kelas_to_users_table',15),(57,'2026_06_15_235139_simplify_arsip_rapat_table',16),(58,'2026_06_16_000330_drop_jenis_rapat_from_arsip_rapat',17),(59,'2026_06_19_163254_rename_arsip_rapat_fields',18);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nilai_mahasiswa`
--

DROP TABLE IF EXISTS `nilai_mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_mahasiswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` bigint unsigned NOT NULL,
  `id_komponen` bigint unsigned NOT NULL,
  `id_semester` bigint unsigned NOT NULL,
  `nilai_mentah` decimal(6,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_nilai` (`id_mahasiswa`,`id_komponen`,`id_semester`),
  KEY `nilai_mahasiswa_id_semester_foreign` (`id_semester`),
  KEY `idx_nilai_mhs_smt` (`id_mahasiswa`,`id_semester`),
  KEY `idx_nilai_komponen` (`id_komponen`,`id_semester`),
  CONSTRAINT `nilai_mahasiswa_id_komponen_foreign` FOREIGN KEY (`id_komponen`) REFERENCES `komponen_asesmen` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_mahasiswa_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_mahasiswa_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_nilai` CHECK ((`nilai_mentah` between 0 and 100))
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilai_mahasiswa`
--

LOCK TABLES `nilai_mahasiswa` WRITE;
/*!40000 ALTER TABLE `nilai_mahasiswa` DISABLE KEYS */;
INSERT INTO `nilai_mahasiswa` VALUES (1,8,101,4,85.00,'2026-05-31 09:25:52','2026-05-31 09:25:52'),(2,8,95,4,78.00,'2026-05-31 09:25:52','2026-05-31 09:25:52'),(3,8,96,4,82.00,'2026-05-31 09:25:52','2026-05-31 09:25:52'),(4,9,101,4,75.00,'2026-05-31 09:25:52','2026-05-31 09:25:52'),(5,9,95,4,70.00,'2026-05-31 09:25:52','2026-05-31 09:25:52'),(6,9,96,4,68.00,'2026-05-31 09:25:52','2026-05-31 09:25:52'),(7,10,101,4,90.00,'2026-05-31 09:25:52','2026-05-31 09:25:52'),(8,10,95,4,88.00,'2026-05-31 09:25:52','2026-05-31 09:25:52'),(9,10,96,4,92.00,'2026-05-31 09:25:52','2026-05-31 09:25:52');
/*!40000 ALTER TABLE `nilai_mahasiswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `dibaca` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `notifikasi_id_user_foreign` (`id_user`),
  CONSTRAINT `notifikasi_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifikasi`
--

LOCK TABLES `notifikasi` WRITE;
/*!40000 ALTER TABLE `notifikasi` DISABLE KEYS */;
INSERT INTO `notifikasi` VALUES (1,5,'Profil Lulusan Disetujui','PL PL1 pada kurikulum KUR-SI-2021 telah disetujui oleh Kaprodi.','http://si-obe.test:8080/kurikulum/1/pl','success',0,'2026-05-30 13:26:50'),(2,6,'Profil Lulusan Disetujui','PL PL1 pada kurikulum KUR-SI-2021 telah disetujui oleh Kaprodi.','http://si-obe.test:8080/kurikulum/1/pl','success',0,'2026-05-30 13:26:50');
/*!40000 ALTER TABLE `notifikasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

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

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengampuan_mk`
--

DROP TABLE IF EXISTS `pengampuan_mk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengampuan_mk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mk` bigint unsigned NOT NULL,
  `id_dosen` bigint unsigned NOT NULL,
  `id_semester` bigint unsigned NOT NULL,
  `is_koordinator` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pengampuan` (`id_mk`,`id_dosen`,`id_semester`),
  KEY `pengampuan_mk_id_semester_foreign` (`id_semester`),
  KEY `idx_pengampuan_dosen` (`id_dosen`,`id_semester`),
  CONSTRAINT `pengampuan_mk_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengampuan_mk_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengampuan_mk_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengampuan_mk`
--

LOCK TABLES `pengampuan_mk` WRITE;
/*!40000 ALTER TABLE `pengampuan_mk` DISABLE KEYS */;
INSERT INTO `pengampuan_mk` VALUES (3,4,6,4,1,NULL);
/*!40000 ALTER TABLE `pengampuan_mk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pivot_cpl_bk`
--

DROP TABLE IF EXISTS `pivot_cpl_bk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pivot_cpl_bk` (
  `id_cpl` bigint unsigned NOT NULL,
  `id_bk` bigint unsigned NOT NULL,
  PRIMARY KEY (`id_cpl`,`id_bk`),
  KEY `pivot_cpl_bk_id_bk_foreign` (`id_bk`),
  CONSTRAINT `pivot_cpl_bk_id_bk_foreign` FOREIGN KEY (`id_bk`) REFERENCES `bahan_kajian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pivot_cpl_bk_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pivot_cpl_bk`
--

LOCK TABLES `pivot_cpl_bk` WRITE;
/*!40000 ALTER TABLE `pivot_cpl_bk` DISABLE KEYS */;
INSERT INTO `pivot_cpl_bk` VALUES (1,1),(1,2),(2,2),(4,3),(1,4),(6,4),(7,4),(2,5),(3,5),(6,5),(7,5),(3,6),(6,6),(7,6),(3,7),(4,8),(5,9),(3,10),(7,10),(2,11),(3,12),(7,12),(1,13),(2,13),(3,13),(4,13),(5,13),(6,13),(7,13);
/*!40000 ALTER TABLE `pivot_cpl_bk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pivot_cpl_bk_mk`
--

DROP TABLE IF EXISTS `pivot_cpl_bk_mk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pivot_cpl_bk_mk` (
  `id_cpl` bigint unsigned NOT NULL,
  `id_bk` bigint unsigned NOT NULL,
  `id_mk` bigint unsigned NOT NULL,
  PRIMARY KEY (`id_cpl`,`id_bk`,`id_mk`),
  KEY `pivot_cpl_bk_mk_id_bk_foreign` (`id_bk`),
  KEY `pivot_cpl_bk_mk_id_mk_foreign` (`id_mk`),
  CONSTRAINT `pivot_cpl_bk_mk_id_bk_foreign` FOREIGN KEY (`id_bk`) REFERENCES `bahan_kajian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pivot_cpl_bk_mk_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pivot_cpl_bk_mk_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pivot_cpl_bk_mk`
--

LOCK TABLES `pivot_cpl_bk_mk` WRITE;
/*!40000 ALTER TABLE `pivot_cpl_bk_mk` DISABLE KEYS */;
INSERT INTO `pivot_cpl_bk_mk` VALUES (1,1,1),(1,1,4),(1,2,5),(1,2,8),(2,2,5),(2,2,8),(4,3,2),(4,3,6),(4,3,9),(1,4,17),(1,4,23),(6,4,17),(6,4,23),(7,4,17),(7,4,23),(2,5,5),(2,5,8),(2,5,13),(2,5,17),(2,5,19),(2,5,23),(3,5,5),(3,5,8),(3,5,13),(3,5,17),(3,5,19),(3,5,23),(6,5,5),(6,5,8),(6,5,13),(6,5,17),(6,5,19),(6,5,23),(7,5,5),(7,5,8),(7,5,13),(7,5,17),(7,5,19),(7,5,23),(3,6,10),(3,6,13),(3,6,17),(3,6,18),(3,6,20),(3,6,23),(6,6,10),(6,6,13),(6,6,17),(6,6,18),(6,6,20),(6,6,23),(7,6,10),(7,6,13),(7,6,17),(7,6,18),(7,6,20),(7,6,23),(3,7,3),(3,7,11),(3,7,14),(3,7,19),(4,8,15),(4,8,21),(5,9,12),(5,9,16),(3,10,20),(3,10,22),(3,10,25),(7,10,20),(7,10,22),(7,10,25),(2,11,7),(2,11,20),(3,12,20),(3,12,22),(3,12,24),(3,12,25),(7,12,20),(7,12,22),(7,12,24),(7,12,25),(1,13,20),(2,13,20),(3,13,20),(4,13,20),(5,13,20),(6,13,20),(7,13,20);
/*!40000 ALTER TABLE `pivot_cpl_bk_mk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pivot_cpl_cpmk`
--

DROP TABLE IF EXISTS `pivot_cpl_cpmk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pivot_cpl_cpmk` (
  `id_cpl` bigint unsigned NOT NULL,
  `id_cpmk` bigint unsigned NOT NULL,
  `bobot` decimal(5,2) NOT NULL DEFAULT '1.00',
  PRIMARY KEY (`id_cpl`,`id_cpmk`),
  KEY `pivot_cpl_cpmk_id_cpmk_foreign` (`id_cpmk`),
  CONSTRAINT `pivot_cpl_cpmk_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pivot_cpl_cpmk_id_cpmk_foreign` FOREIGN KEY (`id_cpmk`) REFERENCES `cpmk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pivot_cpl_cpmk`
--

LOCK TABLES `pivot_cpl_cpmk` WRITE;
/*!40000 ALTER TABLE `pivot_cpl_cpmk` DISABLE KEYS */;
INSERT INTO `pivot_cpl_cpmk` VALUES (1,1,1.00),(1,4,1.00),(1,5,1.00),(1,10,1.00),(1,25,1.00),(1,40,1.00),(1,56,1.00),(1,62,1.00),(2,6,1.00),(2,9,1.00),(2,11,1.00),(2,19,1.00),(2,26,1.00),(2,31,1.00),(2,41,1.00),(2,57,1.00),(2,63,1.00),(3,3,1.00),(3,7,1.00),(3,12,1.00),(3,14,1.00),(3,17,1.00),(3,20,1.00),(3,22,1.00),(3,27,1.00),(3,28,1.00),(3,32,1.00),(3,34,1.00),(3,38,1.00),(3,42,1.00),(3,43,1.00),(3,45,1.00),(3,53,1.00),(3,58,1.00),(4,2,1.00),(4,8,1.00),(4,13,1.00),(4,23,1.00),(4,37,1.00),(4,54,1.00),(4,59,1.00),(5,18,1.00),(5,24,1.00),(5,61,1.00),(6,15,1.00),(6,21,1.00),(6,29,1.00),(6,33,1.00),(6,35,1.00),(6,55,1.00),(7,16,1.00),(7,30,1.00),(7,36,1.00),(7,39,1.00),(7,44,1.00),(7,46,1.00);
/*!40000 ALTER TABLE `pivot_cpl_cpmk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pivot_cplsn_cplp`
--

DROP TABLE IF EXISTS `pivot_cplsn_cplp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pivot_cplsn_cplp` (
  `id_cpl_sndikti` bigint unsigned NOT NULL,
  `id_cpl_prodi` bigint unsigned NOT NULL,
  PRIMARY KEY (`id_cpl_sndikti`,`id_cpl_prodi`),
  KEY `pivot_cplsn_cplp_id_cpl_prodi_foreign` (`id_cpl_prodi`),
  CONSTRAINT `pivot_cplsn_cplp_id_cpl_prodi_foreign` FOREIGN KEY (`id_cpl_prodi`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pivot_cplsn_cplp_id_cpl_sndikti_foreign` FOREIGN KEY (`id_cpl_sndikti`) REFERENCES `cpl_sndikti` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pivot_cplsn_cplp`
--

LOCK TABLES `pivot_cplsn_cplp` WRITE;
/*!40000 ALTER TABLE `pivot_cplsn_cplp` DISABLE KEYS */;
INSERT INTO `pivot_cplsn_cplp` VALUES (4,1),(16,1),(17,2),(18,2),(31,2),(31,3),(27,8),(28,8);
/*!40000 ALTER TABLE `pivot_cplsn_cplp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pivot_mk_bk`
--

DROP TABLE IF EXISTS `pivot_mk_bk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pivot_mk_bk` (
  `id_mk` bigint unsigned NOT NULL,
  `id_bk` bigint unsigned NOT NULL,
  PRIMARY KEY (`id_mk`,`id_bk`),
  KEY `pivot_mk_bk_id_bk_foreign` (`id_bk`),
  CONSTRAINT `pivot_mk_bk_id_bk_foreign` FOREIGN KEY (`id_bk`) REFERENCES `bahan_kajian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pivot_mk_bk_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pivot_mk_bk`
--

LOCK TABLES `pivot_mk_bk` WRITE;
/*!40000 ALTER TABLE `pivot_mk_bk` DISABLE KEYS */;
INSERT INTO `pivot_mk_bk` VALUES (1,1),(4,1),(5,2),(8,2),(2,3),(6,3),(9,3),(17,4),(23,4),(5,5),(8,5),(13,5),(17,5),(19,5),(23,5),(10,6),(13,6),(17,6),(18,6),(20,6),(23,6),(3,7),(11,7),(14,7),(19,7),(15,8),(21,8),(12,9),(16,9),(20,10),(22,10),(25,10),(7,11),(20,11),(20,12),(22,12),(24,12),(25,12),(20,13),(20,14);
/*!40000 ALTER TABLE `pivot_mk_bk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pivot_mk_cpl`
--

DROP TABLE IF EXISTS `pivot_mk_cpl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pivot_mk_cpl` (
  `id_mk` bigint unsigned NOT NULL,
  `id_cpl` bigint unsigned NOT NULL,
  `id_cpmk` bigint unsigned NOT NULL,
  `bobot` decimal(5,2) NOT NULL DEFAULT '1.00',
  PRIMARY KEY (`id_mk`,`id_cpl`,`id_cpmk`),
  KEY `pivot_mk_cpl_id_cpl_foreign` (`id_cpl`),
  KEY `pivot_mk_cpl_id_cpmk_foreign` (`id_cpmk`),
  CONSTRAINT `pivot_mk_cpl_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pivot_mk_cpl_id_cpmk_foreign` FOREIGN KEY (`id_cpmk`) REFERENCES `cpmk` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pivot_mk_cpl_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pivot_mk_cpl`
--

LOCK TABLES `pivot_mk_cpl` WRITE;
/*!40000 ALTER TABLE `pivot_mk_cpl` DISABLE KEYS */;
INSERT INTO `pivot_mk_cpl` VALUES (1,1,1,1.00),(1,3,53,1.00),(1,4,54,1.00),(1,5,51,1.00),(2,4,2,1.00),(3,3,3,1.00),(4,1,4,1.00),(5,1,5,1.00),(5,2,6,1.00),(5,3,7,1.00),(5,5,61,1.00),(6,4,8,1.00),(7,2,9,1.00),(7,6,55,1.00),(8,1,10,1.00),(8,2,11,1.00),(8,3,12,1.00),(9,4,13,1.00),(10,3,14,1.00),(10,6,15,1.00),(10,7,16,1.00),(11,3,17,1.00),(12,5,18,1.00),(13,2,19,1.00),(13,3,20,1.00),(13,6,21,1.00),(14,3,22,1.00),(15,4,23,1.00),(16,5,24,1.00),(17,1,25,1.00),(17,2,26,1.00),(17,3,27,1.00),(18,3,28,1.00),(18,6,29,1.00),(18,7,30,1.00),(19,2,31,1.00),(19,3,32,1.00),(19,6,33,1.00),(20,1,62,1.00),(20,2,63,1.00),(20,3,34,1.00),(20,6,35,1.00),(20,7,36,1.00),(21,4,37,1.00),(22,3,38,1.00),(22,7,39,1.00),(23,1,40,1.00),(23,2,41,1.00),(23,3,42,1.00),(24,3,43,1.00),(24,7,44,1.00),(25,3,45,1.00),(25,7,46,1.00),(27,1,56,1.00),(27,2,57,1.00),(27,3,58,1.00),(27,4,59,1.00);
/*!40000 ALTER TABLE `pivot_mk_cpl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pivot_pl_cpl`
--

DROP TABLE IF EXISTS `pivot_pl_cpl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pivot_pl_cpl` (
  `id_pl` bigint unsigned NOT NULL,
  `id_cpl` bigint unsigned NOT NULL,
  PRIMARY KEY (`id_pl`,`id_cpl`),
  KEY `pivot_pl_cpl_id_cpl_foreign` (`id_cpl`),
  CONSTRAINT `pivot_pl_cpl_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pivot_pl_cpl_id_pl_foreign` FOREIGN KEY (`id_pl`) REFERENCES `pl` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pivot_pl_cpl`
--

LOCK TABLES `pivot_pl_cpl` WRITE;
/*!40000 ALTER TABLE `pivot_pl_cpl` DISABLE KEYS */;
INSERT INTO `pivot_pl_cpl` VALUES (2,2),(1,3),(2,3),(1,4),(1,5),(2,5),(3,5),(1,6),(2,6),(2,7),(3,7);
/*!40000 ALTER TABLE `pivot_pl_cpl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pl`
--

DROP TABLE IF EXISTS `pl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_kurikulum` bigint unsigned NOT NULL,
  `kode_pl` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referensi` text COLLATE utf8mb4_unicode_ci,
  `ref_area_fungsi_1` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_area_fungsi_2` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_area_fungsi_3` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `status` enum('draft','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pl_kode` (`id_kurikulum`,`kode_pl`),
  KEY `pl_approved_by_foreign` (`approved_by`),
  CONSTRAINT `pl_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pl_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pl`
--

LOCK TABLES `pl` WRITE;
/*!40000 ALTER TABLE `pl` DISABLE KEYS */;
INSERT INTO `pl` VALUES (1,2,'PL01','Lulusan memiliki kemampuan menganalisis, merancang, mengembangkan, dan menjamin kualitas sistem informasi sesuai dengan kebutuhan pengguna serta standar industri.','Kompetensi Utama','IS2020, Permendikbudristek No. 53/2023, SKKNI level 6 bidang TIK',NULL,NULL,NULL,1,'approved',4,'2026-06-07 21:46:23',NULL),(2,2,'PL02','Lulusan memiliki kemampuan memahami, menerapkan dan mengintegrasikan model sistem, menggunakan metode dan berbagai teknik peningkatan bisnis proses yang mendatangkan suatu nilai untuk organisasi.','Kompetensi Utama','IS2020, Permendikbudristek No. 53/2023, SKKNI level 6 bidang TIK',NULL,NULL,NULL,2,'draft',NULL,NULL,NULL),(3,2,'PL03','Mampu untuk bekerja secara kolaboratif, proaktif, dan bertanggungjawab dalam tim untuk mencapai tujuan bersama dalam berbagai konteks profesional.','Kompetensi Sikap','IABEE, ABET',NULL,NULL,NULL,3,'draft',NULL,NULL,NULL);
/*!40000 ALTER TABLE `pl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repositori_materi`
--

DROP TABLE IF EXISTS `repositori_materi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `repositori_materi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mk` bigint unsigned NOT NULL,
  `id_semester` bigint unsigned NOT NULL,
  `id_dosen` bigint unsigned NOT NULL,
  `id_rps_pertemuan` bigint unsigned DEFAULT NULL,
  `jenis_file` enum('modul','presentasi','referensi','tugas','video','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ukuran_kb` int DEFAULT NULL,
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `repositori_materi_id_mk_foreign` (`id_mk`),
  KEY `repositori_materi_id_semester_foreign` (`id_semester`),
  KEY `repositori_materi_id_rps_pertemuan_foreign` (`id_rps_pertemuan`),
  KEY `repositori_materi_id_dosen_foreign` (`id_dosen`),
  CONSTRAINT `repositori_materi_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `repositori_materi_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `repositori_materi_id_rps_pertemuan_foreign` FOREIGN KEY (`id_rps_pertemuan`) REFERENCES `rps_pertemuan` (`id`) ON DELETE SET NULL,
  CONSTRAINT `repositori_materi_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repositori_materi`
--

LOCK TABLES `repositori_materi` WRITE;
/*!40000 ALTER TABLE `repositori_materi` DISABLE KEYS */;
/*!40000 ALTER TABLE `repositori_materi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rps_detail_mk`
--

DROP TABLE IF EXISTS `rps_detail_mk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rps_detail_mk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_rps` bigint unsigned NOT NULL,
  `id_cpl` bigint unsigned NOT NULL,
  `id_cpmk` bigint unsigned NOT NULL,
  `id_sub_cpmk` bigint unsigned DEFAULT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rps_detail_mk_id_rps_foreign` (`id_rps`),
  KEY `rps_detail_mk_id_cpl_foreign` (`id_cpl`),
  KEY `rps_detail_mk_id_cpmk_foreign` (`id_cpmk`),
  KEY `rps_detail_mk_id_sub_cpmk_foreign` (`id_sub_cpmk`),
  CONSTRAINT `rps_detail_mk_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rps_detail_mk_id_cpmk_foreign` FOREIGN KEY (`id_cpmk`) REFERENCES `cpmk` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rps_detail_mk_id_rps_foreign` FOREIGN KEY (`id_rps`) REFERENCES `rps_header` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rps_detail_mk_id_sub_cpmk_foreign` FOREIGN KEY (`id_sub_cpmk`) REFERENCES `sub_cpmk` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rps_detail_mk`
--

LOCK TABLES `rps_detail_mk` WRITE;
/*!40000 ALTER TABLE `rps_detail_mk` DISABLE KEYS */;
/*!40000 ALTER TABLE `rps_detail_mk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rps_header`
--

DROP TABLE IF EXISTS `rps_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rps_header` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_mk` bigint unsigned NOT NULL,
  `id_semester` bigint unsigned NOT NULL,
  `id_dosen_pengembang` bigint unsigned NOT NULL,
  `id_koordinator_bk` bigint unsigned DEFAULT NULL,
  `id_kaprodi_pengesah` bigint unsigned DEFAULT NULL,
  `tanggal_penyusunan` date NOT NULL,
  `kode_dokumen` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sks_teori` tinyint unsigned DEFAULT NULL,
  `sks_praktikum` tinyint unsigned DEFAULT NULL,
  `nama_perguruan_tinggi` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_fakultas` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi_mk` text COLLATE utf8mb4_unicode_ci,
  `materi_pembelajaran` text COLLATE utf8mb4_unicode_ci,
  `pustaka_utama` text COLLATE utf8mb4_unicode_ci,
  `pustaka_pendukung` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','review','disahkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `disahkan_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_rps_mk_smt` (`id_mk`,`id_semester`),
  KEY `rps_header_id_semester_foreign` (`id_semester`),
  KEY `rps_header_id_dosen_pengembang_foreign` (`id_dosen_pengembang`),
  KEY `rps_header_id_koordinator_bk_foreign` (`id_koordinator_bk`),
  KEY `rps_header_id_kaprodi_pengesah_foreign` (`id_kaprodi_pengesah`),
  CONSTRAINT `rps_header_id_dosen_pengembang_foreign` FOREIGN KEY (`id_dosen_pengembang`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rps_header_id_kaprodi_pengesah_foreign` FOREIGN KEY (`id_kaprodi_pengesah`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `rps_header_id_koordinator_bk_foreign` FOREIGN KEY (`id_koordinator_bk`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `rps_header_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rps_header_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rps_header`
--

LOCK TABLES `rps_header` WRITE;
/*!40000 ALTER TABLE `rps_header` DISABLE KEYS */;
INSERT INTO `rps_header` VALUES (1,24,4,6,NULL,4,'2024-08-20','RPS/MK22/GNJ2024',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'disahkan','2026-05-31 09:25:52','2026-05-31 09:25:52','2026-05-31 09:25:52'),(2,4,4,6,NULL,NULL,'2026-06-01','RPS/MK02/GNJ-242',3,0,NULL,NULL,NULL,NULL,NULL,NULL,'draft',NULL,'2026-06-01 05:26:07','2026-06-15 16:40:24');
/*!40000 ALTER TABLE `rps_header` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rps_pertemuan`
--

DROP TABLE IF EXISTS `rps_pertemuan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rps_pertemuan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_rps` bigint unsigned NOT NULL,
  `minggu_ke` int NOT NULL,
  `materi_pembelajaran` text COLLATE utf8mb4_unicode_ci,
  `metode_pembelajaran` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_sub_cpmk` bigint unsigned DEFAULT NULL,
  `indikator_penilaian` text COLLATE utf8mb4_unicode_ci,
  `kriteria_teknik` text COLLATE utf8mb4_unicode_ci,
  `bentuk_luring` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bentuk_daring` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bobot_penilaian` decimal(5,2) DEFAULT NULL,
  `estimasi_waktu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `media_pembelajaran` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referensi` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_rps_minggu` (`id_rps`,`minggu_ke`),
  KEY `idx_rps_pertemuan_id_rps` (`id_rps`),
  KEY `rps_pertemuan_id_sub_cpmk_foreign` (`id_sub_cpmk`),
  CONSTRAINT `rps_pertemuan_id_rps_foreign` FOREIGN KEY (`id_rps`) REFERENCES `rps_header` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rps_pertemuan_id_sub_cpmk_foreign` FOREIGN KEY (`id_sub_cpmk`) REFERENCES `sub_cpmk` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rps_pertemuan`
--

LOCK TABLES `rps_pertemuan` WRITE;
/*!40000 ALTER TABLE `rps_pertemuan` DISABLE KEYS */;
INSERT INTO `rps_pertemuan` VALUES (1,1,1,'Pengenalan HTML5 dan struktur dokumen web','Ceramah & Demonstrasi',85,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(2,1,2,'CSS3 - Styling dan Layout Flexbox','Ceramah & Praktikum',85,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(3,1,3,'CSS3 - Grid Layout dan Responsif Design','Praktikum',85,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(4,1,4,'JavaScript Dasar - Variabel dan Fungsi','Ceramah & Latihan',86,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(5,1,5,'JavaScript - DOM Manipulation','Praktikum',86,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(6,1,6,'JavaScript - Event Handling & AJAX','Praktikum',86,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(7,1,7,'PHP Dasar - Sintaks dan Tipe Data','Ceramah & Praktikum',93,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(8,1,8,'UTS - Evaluasi Tengah Semester','Ujian',93,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(9,1,9,'PHP - Form Handling dan Validasi','Praktikum',93,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(10,1,10,'PHP - Koneksi ke Database MySQL','Praktikum',93,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(11,1,11,'PHP - CRUD Operasi Dasar','Praktikum',93,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(12,1,12,'PHP - Session dan Cookie','Ceramah & Praktikum',93,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(13,1,13,'Framework Laravel - Routing & MVC','Ceramah & Praktikum',93,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(14,1,14,'Framework Laravel - Eloquent ORM','Praktikum',93,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(15,1,15,'Proyek Mini - Implementasi Aplikasi Web','Proyek',93,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(16,1,16,'UAS - Evaluasi Akhir Semester','Ujian',93,NULL,NULL,NULL,NULL,NULL,'150 menit',NULL,NULL),(17,2,1,'teest',NULL,7,'Mahasiswa mampu memahami','Tes','Kuliah','Video zoom',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `rps_pertemuan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `semester_akademik`
--

DROP TABLE IF EXISTS `semester_akademik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `semester_akademik` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_akademik` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('Ganjil','Genap','Pendek') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_aktif` tinyint NOT NULL DEFAULT '0',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_semester_nama` (`nama`),
  KEY `idx_semester_aktif` (`is_aktif`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `semester_akademik`
--

LOCK TABLES `semester_akademik` WRITE;
/*!40000 ALTER TABLE `semester_akademik` DISABLE KEYS */;
INSERT INTO `semester_akademik` VALUES (3,'Genap 2025/2026','2025/2026','Genap',0,'2026-01-31','2026-06-30',NULL),(4,'Ganjil 2024/2025','2024/2025','Ganjil',1,'2024-09-01','2025-01-31',NULL);
/*!40000 ALTER TABLE `semester_akademik` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

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

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('HPtObQt0ivetYLoABR5DVU2TlRlVuVGh7Wdb5BZQ',4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','ZXlKcGRpSTZJbXQyY21keVZuWkxUMWs0VERsb04xWTBjVGxFVFZFOVBTSXNJblpoYkhWbElqb2lhVFZ6VGs5SE5VeEZaMVIwVFhobFMwSm9WMloyWkdOSU5ERmlWMDVJWTFVeFZsRmhTbmRNZUhaWVFXMUNXbWtyVmpWVGJtSkhOa2htUkZOelpYTnpTM1ZpTWxWcmRuYzBSakI2Ym5wTmEwUXZjamhqVlZGVU9VaHdNalJwZVM5alMweERZMEpKTjFNMlFubFFVR0pCVVcwMEt6TnRZbmRvYVZKSGJUaFdZV2t2U1VvM1NDOVdaR2M0V0VKYVZtcGlTVEZ3ZW1WT2Nta3ZOVU5qUVdOV2RrWjJVbTlsTlhsbFRFNVZiekJTUkVZMlpHMW5UMmcwVEZVd01sUmxWRTh5Ym1jMFVraExRMDlHTjJ4cFpWVmpjRFpMTHpSNFUyTXlaV3BOVEdaWFkzWkllVUZtY1RWeFNISnVRV3hZU1ZaR1VYVTBUbkJZTVhOeFkwZFBja1pXVnpWeldWZDJhVTF2T0doMmFqRm5RMlY1VUhSalJYUXdUMVpKYUhGTFdGSktabTVFUlRBME1IVktlWEY0V2tkRkwwSnhPV1ZIYjA5T1VGVnBXazlDTjJ0NWNYQndXbTQ1WXpWV09VdGtNbXBYTVRka2FFVm5QVDBpTENKdFlXTWlPaUl3WXprek9HWTNObUpsTmpReE56TmlOVGRrWVdObVl6STNPV0UyTlRreFlXUmxOakF6TURNd1ptRTBOelF3WkdRM05HVTJNalkxT1dabU1EZzFOalppSWl3aWRHRm5Jam9pSW4wPQ==',1781892891),('xX9n6iXw5PDAlPN3Kxu45zbTYIJjdQsoob0lS6CX',4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','ZXlKcGRpSTZJbTh6ZGt4NlREaGlVMWxzVm1oVFdEbHBRbVZNWkZFOVBTSXNJblpoYkhWbElqb2lSV2RhVUZkcFoyZFRaRlJ4VFRSUVZXODNRMk5hZDA1aVNqVnlSRzFwT0c1T1RIUnBiWGxRVDJKTE5HYzBOMjVhTjFoTk5sTldkbEZLWWxWeVNXWnhSR3AyYWs5MmNHVjJabWR5YnpSclRtdHRjWFIwV0VKS05XTmxWeXN2YlVwSFkweG5PUzl0WTBWUFIxTlpOblY1V1ZaSlYxVlBSakpyUmxSS1FqWjVXWHBHT1dGM2FpdEpPRVpQWlZoa1NuSjZORXBWZDBaTWIyZHZXVEpwU1ZKcVFYSnBZWFpoVHpsSE4ybHRPSGx1Y2pkM1VHOW9UV0ZFVDJ0a2FHOVZLM1p1WjNKaWNuRlNOMWRyVEcxMVdIcEpNbU5OZFVaa1NUUXlRVGxSYTJOdlVWazROMUI2UTJSWlJscFNiVVZaTmpVd0wwdzRUSFo0ZURkR01taFpOV3hTU205QmNXMDRkMHRrYVhGbWIwUndNU3RRVXpGdWRFbDRSamM0YlVsQlFtMXVjbEJ3Wm1kUlJIaHROQ3MyWkRRclVGUTBlV05DU1doSWMyY3lMMUEwVEVaQ1ZVZElhMUpsVUhORmRrTjRTVWxuVXpoV2J6QklUa3BVVmtZMWNtd3dabk5hTW5WR1VuWlZOMUozUFNJc0ltMWhZeUk2SW1Fd016RmlOVEprWWpVMFl6UmpNVGcwTTJNellUY3hZekUxWmpBeE1qTmtaRE5qT0dVMU5HUTBNRFJrTTJJNVlUVTJZV1V4TWpSaVkyRTNOV0l4WVRNaUxDSjBZV2NpT2lJaWZRPT0=',1781952772);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_cpmk`
--

DROP TABLE IF EXISTS `sub_cpmk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_cpmk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_cpmk` bigint unsigned NOT NULL,
  `kode_sub_cpmk` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot` decimal(5,2) NOT NULL DEFAULT '0.00',
  `urutan` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_subcpmk_kode` (`id_cpmk`,`kode_sub_cpmk`),
  CONSTRAINT `sub_cpmk_id_cpmk_foreign` FOREIGN KEY (`id_cpmk`) REFERENCES `cpmk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_cpmk`
--

LOCK TABLES `sub_cpmk` WRITE;
/*!40000 ALTER TABLE `sub_cpmk` DISABLE KEYS */;
INSERT INTO `sub_cpmk` VALUES (1,1,'Sub-CPMK0111','Memahami konsep dasar dan teori Konsep Sistem Informasi.',30.00,1),(2,1,'Sub-CPMK0112','Menerapkan Konsep Sistem Informasi dalam studi kasus / praktik.',50.00,2),(3,2,'Sub-CPMK0411','Memahami konsep dasar dan teori Pengantar Teknologi Informasi.',50.00,1),(4,2,'Sub-CPMK0412','Menerapkan Pengantar Teknologi Informasi dalam studi kasus / praktik.',30.00,2),(5,3,'Sub-CPMK0311','Memahami konsep dasar dan teori Pemrograman Dasar.',50.00,1),(6,3,'Sub-CPMK0312','Menerapkan Pemrograman Dasar dalam studi kasus / praktik.',50.00,2),(7,50,'Sub-CPMK0111','Memahami konsep dasar dan teori Sistem Informasi Manajemen.',50.00,1),(8,50,'Sub-CPMK0112','Menerapkan Sistem Informasi Manajemen dalam studi kasus / praktik.',50.00,2),(9,5,'Sub-CPMK0111','Memahami konsep dasar dan teori Sistem Basis Data.',50.00,1),(10,5,'Sub-CPMK0112','Menerapkan Sistem Basis Data dalam studi kasus / praktik.',50.00,2),(11,6,'Sub-CPMK0221','Memahami konsep dasar dan teori Sistem Basis Data.',50.00,1),(12,6,'Sub-CPMK0222','Menerapkan Sistem Basis Data dalam studi kasus / praktik.',50.00,2),(13,7,'Sub-CPMK0331','Memahami konsep dasar dan teori Sistem Basis Data.',50.00,1),(14,7,'Sub-CPMK0332','Menerapkan Sistem Basis Data dalam studi kasus / praktik.',50.00,2),(15,8,'Sub-CPMK0411','Memahami konsep dasar dan teori Sistem Operasi.',50.00,1),(16,8,'Sub-CPMK0412','Menerapkan Sistem Operasi dalam studi kasus / praktik.',50.00,2),(17,9,'Sub-CPMK0211','Memahami konsep dasar dan teori Statistika dan Probabilitas.',50.00,1),(18,9,'Sub-CPMK0212','Menerapkan Statistika dan Probabilitas dalam studi kasus / praktik.',50.00,2),(19,10,'Sub-CPMK0111','Memahami konsep dasar dan teori Sistem Basis Data Lanjut.',50.00,1),(20,10,'Sub-CPMK0112','Menerapkan Sistem Basis Data Lanjut dalam studi kasus / praktik.',50.00,2),(21,11,'Sub-CPMK0221','Memahami konsep dasar dan teori Sistem Basis Data Lanjut.',50.00,1),(22,11,'Sub-CPMK0222','Menerapkan Sistem Basis Data Lanjut dalam studi kasus / praktik.',50.00,2),(23,12,'Sub-CPMK0331','Memahami konsep dasar dan teori Sistem Basis Data Lanjut.',50.00,1),(24,12,'Sub-CPMK0332','Menerapkan Sistem Basis Data Lanjut dalam studi kasus / praktik.',50.00,2),(25,13,'Sub-CPMK0411','Memahami konsep dasar dan teori Jaringan Komputer.',50.00,1),(26,13,'Sub-CPMK0412','Menerapkan Jaringan Komputer dalam studi kasus / praktik.',50.00,2),(27,14,'Sub-CPMK0311','Memahami konsep dasar dan teori Transformasi Digital.',50.00,1),(28,14,'Sub-CPMK0312','Menerapkan Transformasi Digital dalam studi kasus / praktik.',50.00,2),(29,15,'Sub-CPMK0621','Memahami konsep dasar dan teori Transformasi Digital.',50.00,1),(30,15,'Sub-CPMK0622','Menerapkan Transformasi Digital dalam studi kasus / praktik.',50.00,2),(31,16,'Sub-CPMK0731','Memahami konsep dasar dan teori Transformasi Digital.',50.00,1),(32,16,'Sub-CPMK0732','Menerapkan Transformasi Digital dalam studi kasus / praktik.',50.00,2),(33,17,'Sub-CPMK0311','Memahami konsep dasar dan teori Pemrograman Berorientasi Objek.',50.00,1),(34,17,'Sub-CPMK0312','Menerapkan Pemrograman Berorientasi Objek dalam studi kasus / praktik.',50.00,2),(35,18,'Sub-CPMK0511','Memahami konsep dasar dan teori Kepemimpinan dan Manajemen Organisasi.',50.00,1),(36,18,'Sub-CPMK0512','Menerapkan Kepemimpinan dan Manajemen Organisasi dalam studi kasus / praktik.',50.00,2),(37,19,'Sub-CPMK0211','Memahami konsep dasar dan teori Analisis dan Perancangan Sistem Informasi.',50.00,1),(38,19,'Sub-CPMK0212','Menerapkan Analisis dan Perancangan Sistem Informasi dalam studi kasus / praktik.',50.00,2),(39,20,'Sub-CPMK0321','Memahami konsep dasar dan teori Analisis dan Perancangan Sistem Informasi.',50.00,1),(40,20,'Sub-CPMK0322','Menerapkan Analisis dan Perancangan Sistem Informasi dalam studi kasus / praktik.',50.00,2),(41,21,'Sub-CPMK0631','Memahami konsep dasar dan teori Analisis dan Perancangan Sistem Informasi.',50.00,1),(42,21,'Sub-CPMK0632','Menerapkan Analisis dan Perancangan Sistem Informasi dalam studi kasus / praktik.',50.00,2),(43,22,'Sub-CPMK0311','Memahami konsep dasar dan teori Pemrograman Berbasis Web.',50.00,1),(44,22,'Sub-CPMK0312','Menerapkan Pemrograman Berbasis Web dalam studi kasus / praktik.',50.00,2),(45,23,'Sub-CPMK0411','Memahami konsep dasar dan teori Keamanan Jaringan.',50.00,1),(46,23,'Sub-CPMK0412','Menerapkan Keamanan Jaringan dalam studi kasus / praktik.',50.00,2),(47,24,'Sub-CPMK0511','Memahami konsep dasar dan teori Etika Profesi dan Profesional.',50.00,1),(48,24,'Sub-CPMK0512','Menerapkan Etika Profesi dan Profesional dalam studi kasus / praktik.',50.00,2),(49,25,'Sub-CPMK0111','Memahami konsep dasar dan teori Manajemen Proyek Sistem Informasi.',50.00,1),(50,25,'Sub-CPMK0112','Menerapkan Manajemen Proyek Sistem Informasi dalam studi kasus / praktik.',50.00,2),(51,26,'Sub-CPMK0221','Memahami konsep dasar dan teori Manajemen Proyek Sistem Informasi.',50.00,1),(52,26,'Sub-CPMK0222','Menerapkan Manajemen Proyek Sistem Informasi dalam studi kasus / praktik.',50.00,2),(53,27,'Sub-CPMK0331','Memahami konsep dasar dan teori Manajemen Proyek Sistem Informasi.',50.00,1),(54,27,'Sub-CPMK0332','Menerapkan Manajemen Proyek Sistem Informasi dalam studi kasus / praktik.',50.00,2),(55,28,'Sub-CPMK0311','Memahami konsep dasar dan teori Tata Kelola Teknologi Informasi.',50.00,1),(56,28,'Sub-CPMK0312','Menerapkan Tata Kelola Teknologi Informasi dalam studi kasus / praktik.',50.00,2),(57,29,'Sub-CPMK0621','Memahami konsep dasar dan teori Tata Kelola Teknologi Informasi.',50.00,1),(58,29,'Sub-CPMK0622','Menerapkan Tata Kelola Teknologi Informasi dalam studi kasus / praktik.',50.00,2),(59,30,'Sub-CPMK0731','Memahami konsep dasar dan teori Tata Kelola Teknologi Informasi.',50.00,1),(60,30,'Sub-CPMK0732','Menerapkan Tata Kelola Teknologi Informasi dalam studi kasus / praktik.',50.00,2),(61,31,'Sub-CPMK0211','Memahami konsep dasar dan teori Software Testing dan Quality Assurance.',50.00,1),(62,31,'Sub-CPMK0212','Menerapkan Software Testing dan Quality Assurance dalam studi kasus / praktik.',50.00,2),(63,32,'Sub-CPMK0321','Memahami konsep dasar dan teori Software Testing dan Quality Assurance.',50.00,1),(64,32,'Sub-CPMK0322','Menerapkan Software Testing dan Quality Assurance dalam studi kasus / praktik.',50.00,2),(65,33,'Sub-CPMK0631','Memahami konsep dasar dan teori Software Testing dan Quality Assurance.',50.00,1),(66,33,'Sub-CPMK0632','Menerapkan Software Testing dan Quality Assurance dalam studi kasus / praktik.',50.00,2),(67,34,'Sub-CPMK0311','Memahami konsep dasar dan teori Audit Sistem Informasi.',50.00,1),(68,34,'Sub-CPMK0312','Menerapkan Audit Sistem Informasi dalam studi kasus / praktik.',50.00,2),(69,35,'Sub-CPMK0621','Memahami konsep dasar dan teori Audit Sistem Informasi.',50.00,1),(70,35,'Sub-CPMK0622','Menerapkan Audit Sistem Informasi dalam studi kasus / praktik.',50.00,2),(71,36,'Sub-CPMK0731','Memahami konsep dasar dan teori Audit Sistem Informasi.',50.00,1),(72,36,'Sub-CPMK0732','Menerapkan Audit Sistem Informasi dalam studi kasus / praktik.',50.00,2),(73,37,'Sub-CPMK0411','Memahami konsep dasar dan teori Keamanan Sistem Informasi.',50.00,1),(74,37,'Sub-CPMK0412','Menerapkan Keamanan Sistem Informasi dalam studi kasus / praktik.',50.00,2),(75,38,'Sub-CPMK0311','Memahami konsep dasar dan teori Kerja Praktek/Magang.',50.00,1),(76,38,'Sub-CPMK0312','Menerapkan Kerja Praktek/Magang dalam studi kasus / praktik.',50.00,2),(77,39,'Sub-CPMK0721','Memahami konsep dasar dan teori Kerja Praktek/Magang.',50.00,1),(78,39,'Sub-CPMK0722','Menerapkan Kerja Praktek/Magang dalam studi kasus / praktik.',50.00,2),(79,40,'Sub-CPMK0111','Memahami konsep dasar dan teori Proyek Sistem Informasi.',50.00,1),(80,40,'Sub-CPMK0112','Menerapkan Proyek Sistem Informasi dalam studi kasus / praktik.',50.00,2),(81,41,'Sub-CPMK0221','Memahami konsep dasar dan teori Proyek Sistem Informasi.',50.00,1),(82,41,'Sub-CPMK0222','Menerapkan Proyek Sistem Informasi dalam studi kasus / praktik.',50.00,2),(83,42,'Sub-CPMK0331','Memahami konsep dasar dan teori Proyek Sistem Informasi.',50.00,1),(84,42,'Sub-CPMK0332','Menerapkan Proyek Sistem Informasi dalam studi kasus / praktik.',50.00,2),(85,43,'Sub-CPMK0311','Memahami konsep dasar dan teori Metodologi Penelitian.',50.00,1),(86,43,'Sub-CPMK0312','Menerapkan Metodologi Penelitian dalam studi kasus / praktik.',50.00,2),(87,44,'Sub-CPMK0721','Memahami konsep dasar dan teori Metodologi Penelitian.',50.00,1),(88,44,'Sub-CPMK0722','Menerapkan Metodologi Penelitian dalam studi kasus / praktik.',50.00,2),(89,45,'Sub-CPMK0311','Memahami konsep dasar dan teori Tugas Akhir.',50.00,1),(91,46,'Sub-CPMK0721','Memahami konsep dasar dan teori Tugas Akhir.',50.00,1),(92,46,'Sub-CPMK0722','Menerapkan Tugas Akhir dalam studi kasus / praktik.',50.00,2),(93,47,'Sub-CPMK0211','Menghubungkan PHP ke MySQL untuk operasi CRUD.',100.00,1),(94,53,'SUB-MK01-4.1','Memahami konsep dasar Konsep Sistem Informasi.',50.00,1),(95,53,'SUB-MK01-4.2','Menerapkan Konsep Sistem Informasi dalam studi kasus.',50.00,2),(96,54,'SUB-MK01-5.1','Memahami konsep dasar Konsep Sistem Informasi.',50.00,1),(97,54,'SUB-MK01-5.2','Menerapkan Konsep Sistem Informasi dalam studi kasus.',50.00,2),(98,55,'SUB-MK23-2.1','Memahami konsep dasar Statistika dan Probabilitas.',50.00,1),(99,55,'SUB-MK23-2.2','Menerapkan Statistika dan Probabilitas dalam studi kasus.',50.00,2),(100,56,'SUB-MK27-1.1','Memahami konsep dasar Bahasa Indonesia.',50.00,1),(101,56,'SUB-MK27-1.2','Menerapkan Bahasa Indonesia dalam studi kasus.',50.00,2),(102,57,'SUB-MK27-2.1','Memahami konsep dasar Bahasa Indonesia.',50.00,1),(103,57,'SUB-MK27-2.2','Menerapkan Bahasa Indonesia dalam studi kasus.',50.00,2),(104,58,'SUB-MK27-3.1','Memahami konsep dasar Bahasa Indonesia.',50.00,1),(105,58,'SUB-MK27-3.2','Menerapkan Bahasa Indonesia dalam studi kasus.',50.00,2),(106,59,'SUB-MK27-4.1','Memahami konsep dasar Bahasa Indonesia.',50.00,1),(107,59,'SUB-MK27-4.2','Menerapkan Bahasa Indonesia dalam studi kasus.',50.00,2),(108,61,'SUB-MK03-4.1','Memahami konsep dasar Sistem Basis Data.',50.00,1),(109,61,'SUB-MK03-4.2','Menerapkan Sistem Basis Data dalam studi kasus.',50.00,2),(110,62,'SUB-MK13-4.1','Memahami konsep dasar Audit Sistem Informasi.',50.00,1),(111,62,'SUB-MK13-4.2','Menerapkan Audit Sistem Informasi dalam studi kasus.',50.00,2),(112,63,'SUB-MK13-5.1','Memahami konsep dasar Audit Sistem Informasi.',50.00,1),(113,63,'SUB-MK13-5.2','Menerapkan Audit Sistem Informasi dalam studi kasus.',50.00,2);
/*!40000 ALTER TABLE `sub_cpmk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('kaprodi','tim_kurikulum','dosen','mahasiswa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program_studi` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fakultas` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perguruan_tinggi` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_masuk` year DEFAULT NULL,
  `kelas` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_kurikulum` bigint unsigned DEFAULT NULL,
  `foto` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_aktif` enum('aktif','nonaktif','cuti') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_users_email` (`email`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_identifier` (`identifier`),
  KEY `idx_users_angkatan` (`tahun_masuk`,`role`),
  KEY `fk_users_kurikulum` (`id_kurikulum`),
  CONSTRAINT `fk_users_kurikulum` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Bapak/Ibu Kaprodi','kaprodi@example.com','2026-05-25 07:00:18','$2y$12$Ncdlq.rSV7h2mT1sY6CrCu3alUtMysVTGTW1UOUmfo5DhHgUVZJp.','kaprodi','198001012005011001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'aktif','2026-05-25 12:33:26','9uOlzLMyRV2RAZoVzYwJmkvzbR2jfcm9JTmpn8R2cwF3tULnK8MKPzcn47HV','2026-05-25 07:00:18','2026-05-25 12:33:26',NULL),(2,'Test User','test@example.com','2026-05-25 07:00:18','$2y$12$0Uz6IWdgaSDp.8v9KlEsze0B6F37Ax621VKjLyQfkf3KeC/STr6zu','kaprodi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'aktif','2026-05-25 12:32:28','E9ojlLyub8U9eQvYlht9XHMf0UMRwogn4Fkru3u6FqYgL63OK2h8VRzKCXxU','2026-05-25 07:00:18','2026-05-25 12:35:52','2026-05-25 12:35:52'),(4,'Dr. Ahmad Kaprodi, M.Kom','kaprodi@si-obe.id',NULL,'$2y$12$j9ewlQkvcE05Dk2iK143e.8wOTfF0.5FM2aVm3pQDZpuLjga/xtou','kaprodi','197001012000011001','Sistem Informasi',NULL,NULL,NULL,NULL,NULL,NULL,'aktif','2026-06-20 03:46:25',NULL,'2026-05-25 12:46:36','2026-06-20 03:46:25',NULL),(5,'Siti Kurikulum, M.T','kurikulum@si-obe.id',NULL,'$2y$12$clzpNPNTr1Cgm6SXNtAYtOTOahMbiT50USUdVVdO3EDDfM2VW2Y.W','tim_kurikulum','198001012005012001','Sistem Informasi',NULL,NULL,NULL,NULL,NULL,NULL,'aktif','2026-06-19 10:31:17',NULL,'2026-05-25 12:46:37','2026-06-19 10:31:17',NULL),(6,'Dr. Budi Santoso, M.Kom','dosen1@si-obe.id',NULL,'$2y$12$1P5si8B8JBNG5ySGKc2peOnIVr8Whf77BXdxSNlSR7uiMEGs4kCMq','dosen','198505152010011005','Sistem Informasi',NULL,NULL,NULL,NULL,NULL,NULL,'aktif','2026-06-19 09:51:56','nrvJuazd9VPobbym1lrnL35MgJd6ybp3XJ4cnLTi99d5fBm6YjZe4scXEYci','2026-05-25 12:46:37','2026-06-19 09:51:56',NULL),(7,'Dewi Rahayu, M.Si','dosen2@si-obe.id',NULL,'$2y$12$y1AN13ZtHzvpoNYyQpLx.O6Hx0B4RQyMtH.61T4uDyUcm9DS9.Jky','dosen','199002202015012003','Sistem Informasi',NULL,NULL,NULL,NULL,NULL,NULL,'aktif','2026-06-06 20:42:48',NULL,'2026-05-25 12:46:37','2026-06-06 20:42:48',NULL),(8,'Andi Pratama','mahasiswa1@si-obe.id',NULL,'$2y$12$x1zaY1cGXzSJVAiTIsG91ue1D5kSaLBs94DblEeavDpb1t6HHXJBG','mahasiswa','21523001','Sistem Informasi',NULL,NULL,2021,NULL,NULL,NULL,'aktif','2026-06-19 05:18:18',NULL,'2026-05-25 12:46:37','2026-06-19 05:18:18',NULL),(9,'Bela Sari','mahasiswa2@si-obe.id',NULL,'$2y$12$Ebpny0f.VA6ByW0y9Mp8IeD8cfYEklw6V6F4pu3/M0mBvz7XQp3K2','mahasiswa','21523002','Sistem Informasi',NULL,NULL,2021,NULL,NULL,NULL,'aktif','2026-05-26 07:46:10',NULL,'2026-05-25 12:46:37','2026-06-15 10:14:25',NULL),(10,'Candra Wijaya','mahasiswa3@si-obe.id',NULL,'$2y$12$YOyOp60sK2KIIAqy3GOBlerr0fKYbK08xNzTJMtWS6URgSo3mGSam','mahasiswa','21523003','Sistem Informasi',NULL,NULL,2021,NULL,NULL,NULL,'aktif',NULL,NULL,'2026-05-25 12:46:38','2026-06-15 10:14:25',NULL),(11,'Ari Attala','ariputra215.ap@gmail.com',NULL,'$2y$12$ygGVU0FSLzHGMLzSbOv6tOut16dhQYOvdvxOrhAeG7UCaHuq4Q5R2','mahasiswa','3130024003','Sistem Inforrmasi',NULL,NULL,2024,NULL,NULL,NULL,'aktif','2026-06-01 18:10:57',NULL,'2026-06-01 18:10:11','2026-06-15 10:14:25',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'si_obe'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-20 17:54:18
