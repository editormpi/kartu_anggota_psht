
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
DROP TABLE IF EXISTS `bills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bills` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) unsigned NOT NULL,
  `tahun` year(4) NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `nominal` bigint(20) unsigned NOT NULL,
  `dibayar` bigint(20) unsigned NOT NULL DEFAULT 0,
  `sisa` bigint(20) NOT NULL DEFAULT 0,
  `status` enum('Lunas','Belum Lunas','Sebagian') NOT NULL DEFAULT 'Belum Lunas',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bills_member_id_tahun_index` (`member_id`,`tahun`),
  CONSTRAINT `bills_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DELIMITER $$
CREATE TRIGGER `bills_sisa_bi` BEFORE INSERT ON `bills` FOR EACH ROW SET NEW.sisa = NEW.nominal - NEW.dibayar$$
CREATE TRIGGER `bills_sisa_bu` BEFORE UPDATE ON `bills` FOR EACH ROW SET NEW.sisa = NEW.nominal - NEW.dibayar$$
DELIMITER ;

LOCK TABLES `bills` WRITE;
/*!40000 ALTER TABLE `bills` DISABLE KEYS */;
INSERT INTO `bills` VALUES (1,1,2025,'Iuran Tahunan 2025',240000,240000,0,'Lunas','2026-05-17 04:43:29','2026-05-17 04:43:29'),(2,1,2026,'Iuran Tahunan 2026',240000,60000,180000,'Sebagian','2026-05-17 04:43:29','2026-05-17 04:43:29'),(3,2,2025,'Iuran Tahunan 2025',240000,240000,0,'Lunas','2026-05-17 04:43:29','2026-05-17 04:43:29'),(4,2,2026,'Iuran Tahunan 2026',240000,60000,180000,'Sebagian','2026-05-17 04:43:29','2026-05-17 04:43:29'),(5,3,2025,'Iuran Tahunan 2025',240000,240000,0,'Lunas','2026-05-17 04:43:29','2026-05-17 04:43:29'),(6,3,2026,'Iuran Tahunan 2026',240000,60000,180000,'Sebagian','2026-05-17 04:43:29','2026-05-17 04:43:29');
/*!40000 ALTER TABLE `bills` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('psht-jember-portal-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3','i:1;',1779021098),('psht-jember-portal-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer','i:1779021098;',1779021098),('psht-jember-portal-cache-login:127.0.0.1','i:2;',1779020893),('psht-jember-portal-cache-login:127.0.0.1:timer','i:1779020893;',1779020893);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `certificates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) unsigned NOT NULL,
  `jenis` varchar(255) NOT NULL,
  `nomor` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificates_nomor_unique` (`nomor`),
  KEY `certificates_member_id_index` (`member_id`),
  CONSTRAINT `certificates_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `certificates` WRITE;
/*!40000 ALTER TABLE `certificates` DISABLE KEYS */;
INSERT INTO `certificates` VALUES (1,1,'Sabuk Polos','CERT/1/2026','2023-08-17','2026-05-17 04:43:29','2026-05-17 04:43:29'),(2,2,'Sabuk Hijau','CERT/2/2026','2023-08-17','2026-05-17 04:43:29','2026-05-17 04:43:29'),(3,3,'Sabuk Putih','CERT/3/2026','2023-08-17','2026-05-17 04:43:29','2026-05-17 04:43:29');
/*!40000 ALTER TABLE `certificates` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_attempts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nik_hash` varchar(64) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `successful` tinyint(1) NOT NULL,
  `failure_reason` varchar(255) DEFAULT NULL,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `login_attempts_nik_hash_index` (`nik_hash`),
  KEY `login_attempts_ip_address_index` (`ip_address`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
INSERT INTO `login_attempts` VALUES (1,'ac646e4006464692937ef0fb598036660491d9e018e24655a69792ef3b0bf4fb','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',0,'wrong_password','2026-05-17 05:21:50'),(2,'ac646e4006464692937ef0fb598036660491d9e018e24655a69792ef3b0bf4fb','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',1,NULL,'2026-05-17 05:22:20'),(3,'ac646e4006464692937ef0fb598036660491d9e018e24655a69792ef3b0bf4fb','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',0,'wrong_password','2026-05-17 05:26:03'),(4,'ac646e4006464692937ef0fb598036660491d9e018e24655a69792ef3b0bf4fb','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',0,'wrong_password','2026-05-17 05:26:15'),(5,'ac646e4006464692937ef0fb598036660491d9e018e24655a69792ef3b0bf4fb','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',0,'wrong_password','2026-05-17 05:26:47'),(6,'ac646e4006464692937ef0fb598036660491d9e018e24655a69792ef3b0bf4fb','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',0,'wrong_password','2026-05-17 05:26:58');
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nik_hash` varchar(64) NOT NULL,
  `nik_encrypted` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `full_name` varchar(255) NOT NULL,
  `tingkat` varchar(255) DEFAULT NULL,
  `status_keanggotaan` varchar(255) NOT NULL DEFAULT 'Aktif',
  `tanggal_keanggotaan` date DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `weton` varchar(255) DEFAULT NULL,
  `agama` varchar(255) DEFAULT NULL,
  `pekerjaan` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `ranting` varchar(255) DEFAULT NULL,
  `rayon` varchar(255) DEFAULT NULL,
  `tempat_latihan` varchar(255) DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_nik_hash_unique` (`nik_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES (1,'ac646e4006464692937ef0fb598036660491d9e018e24655a69792ef3b0bf4fb','eyJpdiI6IjRnNGtUMW10Q0JQb0FyTG1CSkVpaFE9PSIsInZhbHVlIjoiWnhHNm5KaDIxRWN3dGhydUh6Y3QrY09Xb3Fzang5eG5vV0ZTbzFWZ2V5WT0iLCJtYWMiOiIyZjBkYzU4NWNmM2EyMzU5ZTA0NjQ5MTIxMGYxOWM2OTcxMmQxZDg3ZjVjOTI1ZWE0MzY0ODBkNWIzYmJlMzBiIiwidGFnIjoiIn0=','$2y$12$B6ehcODcy6a8as6U7ucG5e5ADTwLqk2qb9OmXHdo0Q7fbwZ8RSjGG',0,1,'Andi Wijaya','Sabuk Polos','Aktif','2020-01-15',NULL,'L','Jember','1995-11-29',NULL,'Islam','Mahasiswa','Jl. Contoh No. 1, Jember','Sukowono','Sukosari','GOR Kaliwates','081234567890',NULL,'2026-05-17 05:22:20',NULL,'2026-05-17 04:43:29','2026-05-17 05:24:34'),(2,'ad45851a1fac2b8d9dde3e05de4cf7f2fd51cc32fb119f9f7d904ded22cc49e4','eyJpdiI6IlpvSnVYaTJLUWdlNXNzMWhJbjhPOFE9PSIsInZhbHVlIjoiRHE5NU1iMEpBazlyaVJPejkzUzgzNFlaWFhsNGkzOHROQ3FLd3FiY3Z3OD0iLCJtYWMiOiIxN2MwOWI1MTQzZjhhOWM2MDE4NjQ5NzdhYWU4NmI5NGY1ZDJjMWQwZTViZWMxZGQ5NzViODI5MjgyN2Q4NjQ5IiwidGFnIjoiIn0=','$2y$12$D1ThdlLzNbH2rr.Ofc5Y5.nv.zOUjbs/hqJ99kqUVlw2cTQeMqspu',1,1,'Budi Santoso','Sabuk Hijau','Aktif','2020-01-15',NULL,'L','Jember','1987-10-15',NULL,'Islam','Mahasiswa','Jl. Contoh No. 1, Jember','Patrang','Slawu','GOR Kaliwates','081234567890',NULL,NULL,NULL,'2026-05-17 04:43:29','2026-05-17 04:43:29'),(3,'ad7cd816bf015406d24a8626ca4dce530d3eb809942c7f764cba97ca00aa73cf','eyJpdiI6Ilc1MnZWT2Q1amY0OUhCVEZPeWJSVmc9PSIsInZhbHVlIjoiK1Vyd01zb21td09tQlBsczQzcHpEZzh0aGNlaWJkLzNUWWYzb3psQkR1UT0iLCJtYWMiOiI2OGE3NjhhM2E3MjEzOWJjNGVjZmQ5NzFjYWIzYTQwYjQ0N2NiZGJlMDllNDkzNTlkYTQyMjJhOTUxMDI0ODkzIiwidGFnIjoiIn0=','$2y$12$mRKMX8/EF6AsQn4OufbScu0rvpag6d1wS5ERrPzXcvlxQw0shdjx2',1,1,'Citra Dewi','Sabuk Putih','Aktif','2020-01-15',NULL,'P','Jember','1995-05-05',NULL,'Islam','Mahasiswa','Jl. Contoh No. 1, Jember','Kaliwates','Mangli','GOR Kaliwates','081234567890',NULL,NULL,NULL,'2026-05-17 04:43:29','2026-05-17 04:43:29');
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_01_000001_create_members_table',1),(5,'2026_01_01_000002_create_bills_table',1),(6,'2026_01_01_000003_create_payment_histories_table',1),(7,'2026_01_01_000004_create_certificates_table',1),(8,'2026_01_01_000005_create_login_attempts_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `payment_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) unsigned NOT NULL,
  `bill_id` bigint(20) unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `nominal` bigint(20) unsigned NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_histories_bill_id_foreign` (`bill_id`),
  KEY `payment_histories_member_id_index` (`member_id`),
  CONSTRAINT `payment_histories_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payment_histories_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `payment_histories` WRITE;
/*!40000 ALTER TABLE `payment_histories` DISABLE KEYS */;
INSERT INTO `payment_histories` VALUES (1,1,NULL,'2025-03-12','Iuran Tahunan 2025 (cicilan 1)',120000,'Operator: Sekretariat','2026-05-17 04:43:29','2026-05-17 04:43:29'),(2,1,NULL,'2025-09-01','Iuran Tahunan 2025 (pelunasan)',120000,'Operator: Sekretariat','2026-05-17 04:43:29','2026-05-17 04:43:29'),(3,1,NULL,'2026-02-10','Iuran Tahunan 2026 (cicilan 1)',60000,'Operator: Sekretariat','2026-05-17 04:43:29','2026-05-17 04:43:29'),(4,2,NULL,'2025-03-12','Iuran Tahunan 2025 (cicilan 1)',120000,'Operator: Sekretariat','2026-05-17 04:43:29','2026-05-17 04:43:29'),(5,2,NULL,'2025-09-01','Iuran Tahunan 2025 (pelunasan)',120000,'Operator: Sekretariat','2026-05-17 04:43:29','2026-05-17 04:43:29'),(6,2,NULL,'2026-02-10','Iuran Tahunan 2026 (cicilan 1)',60000,'Operator: Sekretariat','2026-05-17 04:43:29','2026-05-17 04:43:29'),(7,3,NULL,'2025-03-12','Iuran Tahunan 2025 (cicilan 1)',120000,'Operator: Sekretariat','2026-05-17 04:43:29','2026-05-17 04:43:29'),(8,3,NULL,'2025-09-01','Iuran Tahunan 2025 (pelunasan)',120000,'Operator: Sekretariat','2026-05-17 04:43:29','2026-05-17 04:43:29'),(9,3,NULL,'2026-02-10','Iuran Tahunan 2026 (cicilan 1)',60000,'Operator: Sekretariat','2026-05-17 04:43:29','2026-05-17 04:43:29');
/*!40000 ALTER TABLE `payment_histories` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('dQ8cDxf702hrv2McSFdWfY9jl1yFvAZJf2SaQvY9',NULL,'127.0.0.1','curl/8.18.0','ZXlKcGRpSTZJbk5ZVUhaek9YSnVLM0o1VldOMk5qSlFTVWRaUzBFOVBTSXNJblpoYkhWbElqb2lTMlk1ZERsMVEySnBla1pRU2xWclNscHdiREpIVm5oM1dIbGtaVEZwYms1NFpsQlBTMk4zSzFCWE5uZ3hORE5QWkZGNFUyMUVRVlkyT0dkME1VUklSVUowYmtSS2RVWjJPVTg0YVRCdVNFZHdiSFprVGxkaVpVazFiMGt6VkRRM1JscExjSEJJVTJWM1psUk5hRlJyTkZCUlMyaGliMjFySzJwUVdqQlVSekpYV2xaV2VuTXdWMlJhVlZSc2VsUlZRMlpxWjFRdmVrbHNUekJqVDB0WmNqWndXbHBQVjBzM2JtWlRVM1JYY1dWTE5rMXNNR0p3VFZCU09FZFlaVUZIY21KWVpETjVTMUJSUmpWVGVsSk1kbFUyT1hreU1HdDRRMjVMZEdGRlFuazJPSHBFVTNoMVlWVXlkVlZWVVRaVlpUWndNM2hCZFhZNUwwMXpVMnR5YkhoaE9UUjBXVk15WW1NMmJrNXdTREZhUkdwcmVtUkNja0V3WmxaU2IwRktSMWRKYVVKVU5sRm1OVGN4Y2xsdlJVOTNZbXBzTXpKS1pGRTFRVkl4WmpSNWQwZHZLMjlVUjJNMmFXSTFkM2wwZW14SGRFdEJQVDBpTENKdFlXTWlPaUptTlRJek5HTmhNelJsT1RjeE16ZGlaVGMxT1RZM1pEQTJZV1JpTTJZMll6WTBPVE5pTkRGaU5EZ3dOVFJrTVRobFpERmhaRFkzTldVM01UazVZemswSWl3aWRHRm5Jam9pSW4wPQ==',1779020326),('faaQ1DmjVTnbWtWCTDvyJMFT5JyhDXf4atBUoPMk',NULL,'127.0.0.1','curl/8.18.0','ZXlKcGRpSTZJbWRGUjI5M1FqWkRWMjk1WkdzM1VtVjZkVzk0UmxFOVBTSXNJblpoYkhWbElqb2lVM0YwY0dkUmJFcHhkMmxVVHpoc1RtVm1iV0pVUlROTk9XSkdNRWxJVkVoemRVZHlOeTg0YTJWRWRuRjBhMFExTHk5R2NFMDFTemhKY2pWUmRWSXZWR2N6U1hod1NYVTNhVFJPTVRZeU5qQnpPR3RGZDJOTVRYUkthVk54T0UxdFVUbHBTa1JDSzNWemVTOXZaa0pEY1N0NVEzcDVSbnBzVWxVeFVVRndhV0ZZYlVwMk5YRXhNbWhtYjFobEswTjZXbFo0VTNsbVFrMTRSRlJOWnpBd1lVSllOMFpTWWpkUFZVSk1WMHR6WVdKamJHdGFhMDVrTVM5bFZsbFNTMWg1ZFRaVlRTOTRkR2xuTVRSR2NVWlVZa0VyZVdwdFVIaG5iVVoyTmxoYVFuRlRaemRNYldzMVlreHZTREozVjBaVWQydG5SVEl5VTIwemVuZElOMVkyU0U5Q1R5dGlSbmxGV0N0ck5EaHJXalpwYVhSTmJucHFUMHd2WjB0dE9DdE9NWEp2V1c5U1pEVlFkM005SWl3aWJXRmpJam9pWW1JeU1XSmtPVEUyTVRka056YzVaV1V5WlRabVlUZG1NamszT1RrMU5ESmxZVFEwWldObE9UVTFaakF5T1RrMU1UTXpNakUxWm1GbE5ETTFPRE5tT0NJc0luUmhaeUk2SWlKOQ==',1779020317),('FPEH2ERC5xT8xo7pbFe55blpO2Jmsg700QKvcCgG',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','ZXlKcGRpSTZJbVJ4ZFRWMlVWbExXa1EzYW1wNVV6bHdUR1JIYlZFOVBTSXNJblpoYkhWbElqb2lkVGxHWml0dVVtdEdVRGxRVTNkS2NrWmtWMDlFTkdKWVpEaE1TSEJIWTNFd0wwSmliMjQzZG5CSk9VTTFOWE0xVWtOaVR5dFBORVJqVTJGUWNUQjRTR0ZFYmpoMmRtVkNVbWxEUW14d1EwZG5jRkUzUjFsMVUwMXNhREJoUzBkeFQybE1VWHA2THpoV0szVkZRV2xOYTJ0VmNraFhWRlpaS3pkdFlrMDFaREEyUjNVcmEwWnBOVXR3ZFc5MmVrMUhlRTAwYXpOaWRsZFBiRkZKWW00NWNWRm9lVGswVkdwcU1GUkxSR3cyTUVFeWVIQjNNV0l5UmtGRlJqVlFlWFJaY25OR2F6VlFhRlp4ZFdFeWNHNWliamxDVDNsYU1tMU1TbWxRWjNwSlptSlhZMmRzUWxKemJEVnZXV3BDYTFoaWRsaFlWRWxNWW0xSlNXZHhhRk0xWWxsdFRqbFdhemR1T1ZNMUsxRTBiV2RQUVRsS1JtWldORE5YTWpadFYyVk1aMkZFVjNOM01HNWpVbXQwY0VKa1JrbE1UbVp0Tkhka1pFRnBkVWQ1Y2tadmFVRlNhM2xKV0dOR2F5OUJlVTVsVGsxWU4yVkZRMGx1Um1Nek1tRXhVVVI0V2toeVluY3pOSFpDYnpWM056QjRNMkp0ZFN0M1F6ZHNNME1yTkVZMWNHOUVSSFpJWVZwM1N6SmlTalpoUkdwUVFVaE9UMmx6T1V4MVVrNVZSU3N6Y1VWMFoyaDVXRmhOUkhWd1NVWkVSVkIwTDBGU1RYaDBXVXhUWWpkSk1rRTBRamw0VURGWlIwZDFabTV6VTJNeGJrMDVVMFJrUTFreU5IUkdUMnhxU0RObWRuQkRMMGx6U2pkemFHOWtRM2hLZDIxMk1HMTFjV3MxVVVab1UxRlZZWEZRUmxOYWRUUXdka1J5U1VjdmRWbDVXSG94WTJGNGFUZElaR2hGUTJSQ1RrbFhka05OTlZoM1dFTkJaVmRFUTJGSVMyNXNLMHMzVmxOR04wUndOV1Z0UVZKblJWRXhiMHRHT1d0RlpYaERUa3RvTlhwME4wbHVSSGhCYkV4cGJXbFdZazVFYWxveU5EMGlMQ0p0WVdNaU9pSm1PVGN5TVdKaU9EaGxOemxtTkRjek56TXpPR1UxTlRnM1kySm1NR1ZpWXpNMllUUTFZalJrT0dKbFlUUmhOekpsWXprNU1qRmhOems1WWpCaU1EWmhJaXdpZEdGbklqb2lJbjA9',1779021181),('IcteMjbMsXpR3ceBb7u14vvI1zJaQe0A6KMOeeR9',NULL,'127.0.0.1','curl/8.18.0','ZXlKcGRpSTZJbXBFUW13M01GQmlWVGRpSzJKM1ZrdDNPRUYzT0hjOVBTSXNJblpoYkhWbElqb2liMGRwVEdwS1YyWlNTeko2Um0xdlpHNTZUMmRSVFZOaFMxRk9jRFJuYkVZeFJ6ZE5kRVp5U1c5b2RFMU1hRGcxZFVSUk9TdFZWak5RUldjMlEwRm9ka3AzYzJNeE1HVk9UVFJIUmxNMFJUZzBkMFphVkUxU1pIRnlPSEJFYjNsdFZFWnhlaXRDYUZsSFZVNTNZa0ZRVUhsNmMyYzJSRFJoTm5OV1dXd3ZRbkZQYmtKVlpERm9ZMVkwVVVOR1dURTVSeXRGUVc1ME1FZEhMelJ3ZGtZeVJETkxOM2hFUnpCWmJUbGthMUpNZUdsaVRIUnBhMEpYVUVKc2EyYzFObFJKU2t3clZrWnRiRFJUTm5odU5UYzRhMHc0UWsxWGNVeFZjeXR1V1RKSGFEWlZVa3gwVGprelVYQk1iVXhXYkZwd01YZE5RVXQyYlRObE5WazNXak5YVUZSVU1tdEJRbGw1T1VOVloyazRhekJpVTJZM2RWUnVNMnhVYWtSM1FscG5hRzVrT0V0TlIwTmtTVVV3VUU1dWVHWlVRV3BGYmpOQmFWRkdSaTlRZVd0VE1FZHFNVVF5UjBkdlUxSTNlRk14UzJsVlEycERZakp3WjFKSU5tdDFkbTVuT1RWWU9HeGxiV0ZQT0hCS2RrSmlUMlpyU1c5aVdHTTFhRzVRTVcxSlZtWlZZbVYzVUd4WGJFWkZSMEY0VlUxS05FRlZLMlozWWpsQ2NVWlhMek5ZUlZsNUszWjRMM0pIUVQwaUxDSnRZV01pT2lJMllqUmtOemRpWVRRMk5tWXlPR1EzTXpBNFpqVTVaRGxsTW1OaE4yUmlNbVpoWkdGaVpUY3lZVFUwTmpoaU9EWXhNRFkwTVdGaU5qUXdNRFExWVRreElpd2lkR0ZuSWpvaUluMD0=',1779020317);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@psht-jember.local','2026-05-17 04:43:28','$2y$12$oLCN2VnXjd61pxUnxci6nOTky62gAzMtm04XOpG2ibNgAMYmDe0tC',1,NULL,'2026-05-17 04:43:28','2026-05-17 04:43:28');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

