
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

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-27a4e46046752acaa0a5d532ad5e31ecd4c7c4d3','i:2;',1791435426),('laravel-cache-27a4e46046752acaa0a5d532ad5e31ecd4c7c4d3:timer','i:1791435426;',1791435426),('laravel-cache-73059b4e34f670fb3b7f9a433048d72da20c702b','i:3;',1791451899),('laravel-cache-73059b4e34f670fb3b7f9a433048d72da20c702b:timer','i:1791451899;',1791451899),('laravel-cache-881ea9585949dc6d042fdefbdf0fa75d39dda19e','i:1;',1791442132),('laravel-cache-881ea9585949dc6d042fdefbdf0fa75d39dda19e:timer','i:1791442132;',1791442132),('laravel-cache-ab0243e21d009422de8fed9019d4e662e9389b6d','i:1;',1791435496),('laravel-cache-ab0243e21d009422de8fed9019d4e662e9389b6d:timer','i:1791435496;',1791435496),('laravel-cache-fd0f98cac7b6a296047668636d37afb2315e6c56','i:1;',1791444567),('laravel-cache-fd0f98cac7b6a296047668636d37afb2315e6c56:timer','i:1791444567;',1791444567);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `feed_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feed_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Satuan pengukuran: KG, Karung, Liter, dll.',
  `price_per_unit` decimal(10,2) unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feed_types_user_id_foreign` (`user_id`),
  CONSTRAINT `feed_types_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `feed_types` WRITE;
/*!40000 ALTER TABLE `feed_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `feed_types` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `feeding_record_feed_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feeding_record_feed_type` (
  `feeding_record_id` bigint unsigned NOT NULL,
  `feed_type_id` bigint unsigned NOT NULL,
  `quantity_morning` decimal(8,2) NOT NULL COMMENT 'Jumlah pakan yang diberikan, sesuai unit di tabel feed_types.',
  `quantity_evening` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`feeding_record_id`,`feed_type_id`),
  KEY `feeding_record_feed_type_feed_type_id_foreign` (`feed_type_id`),
  CONSTRAINT `feeding_record_feed_type_feed_type_id_foreign` FOREIGN KEY (`feed_type_id`) REFERENCES `feed_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `feeding_record_feed_type_feeding_record_id_foreign` FOREIGN KEY (`feeding_record_id`) REFERENCES `feeding_records` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `feeding_record_feed_type` WRITE;
/*!40000 ALTER TABLE `feeding_record_feed_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `feeding_record_feed_type` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `feeding_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feeding_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shelter_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `date` date NOT NULL COMMENT 'Tanggal pemberian pakan',
  `time_morning` time DEFAULT NULL COMMENT 'Waktu pakan pagi diberikan.',
  `time_evening` time DEFAULT NULL COMMENT 'Waktu pakan sore diberikan.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `feeding_records_shelter_id_date_unique` (`shelter_id`,`date`),
  KEY `feeding_records_user_id_foreign` (`user_id`),
  CONSTRAINT `feeding_records_shelter_id_foreign` FOREIGN KEY (`shelter_id`) REFERENCES `shelters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `feeding_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `feeding_records` WRITE;
/*!40000 ALTER TABLE `feeding_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `feeding_records` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `health_record_symptom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `health_record_symptom` (
  `health_record_id` bigint unsigned NOT NULL,
  `symptom_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`health_record_id`,`symptom_id`),
  KEY `health_record_symptom_symptom_id_foreign` (`symptom_id`),
  CONSTRAINT `health_record_symptom_health_record_id_foreign` FOREIGN KEY (`health_record_id`) REFERENCES `health_records` (`id`) ON DELETE CASCADE,
  CONSTRAINT `health_record_symptom_symptom_id_foreign` FOREIGN KEY (`symptom_id`) REFERENCES `symptoms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `health_record_symptom` WRITE;
/*!40000 ALTER TABLE `health_record_symptom` DISABLE KEYS */;
/*!40000 ALTER TABLE `health_record_symptom` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `health_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `health_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sheep_id` bigint unsigned NOT NULL,
  `handler_id` bigint unsigned DEFAULT NULL,
  `record_date` date NOT NULL,
  `status` enum('Reported','Pending Treatment','In Treatment','Completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Reported',
  `diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `treatment_details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `medication_used` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `health_records_sheep_id_foreign` (`sheep_id`),
  KEY `health_records_handler_id_foreign` (`handler_id`),
  CONSTRAINT `health_records_handler_id_foreign` FOREIGN KEY (`handler_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `health_records_sheep_id_foreign` FOREIGN KEY (`sheep_id`) REFERENCES `sheep` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `health_records` WRITE;
/*!40000 ALTER TABLE `health_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `health_records` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_11_06_092930_create_shelters_table',1),(5,'2025_11_06_094915_create_sheep_table',1),(6,'2025_11_06_102245_create_weight_records_table',1),(7,'2025_11_06_102433_create_symptoms_table',1),(8,'2025_11_06_102452_create_health_records_table',1),(9,'2025_11_06_102534_create_health_record_symptom_table',1),(10,'2025_11_06_121358_create_feeding_records_table',1),(11,'2025_11_06_121602_create_reproduction_records_table',1),(12,'2025_11_06_123648_create_feed_types_table',1),(13,'2025_11_06_123815_create_feeding_record_feed__table',1),(14,'2025_11_25_100647_add_description_to_feed_types_table',1),(15,'2025_11_25_110643_add_portion_to_feed_type_pivot_table',1),(16,'2025_12_07_085003_create_profit_loss_records_table',1),(17,'2025_12_09_122031_add_partnership_columns_to_sheep_table',1),(18,'2025_12_09_123516_add_role_to_users_table',1),(19,'2026_01_06_024719_placement_requests',1),(20,'2026_01_06_035220_add_user_id_to_feed_types_table',1),(21,'2026_10_07_120000_create_places_table',2),(22,'2026_10_08_000000_add_qr_template_sale_fields_to_places_table',3),(23,'2026_10_08_010000_create_dynamic_qr_tables',4),(24,'2026_10_08_020000_create_qr_scan_events_table',5),(25,'2026_10_08_030000_create_qr_client_billing_tables',6),(26,'2026_10_08_040000_add_qr_subscription_snapshots',7),(27,'2026_10_08_050000_add_activation_codes_to_qr_units',8),(28,'2026_10_08_060000_add_shared_qr_templates',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `placement_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `placement_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sheep_id` bigint unsigned NOT NULL,
  `requester_id` bigint unsigned NOT NULL,
  `target_partner_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `placement_requests_sheep_id_foreign` (`sheep_id`),
  KEY `placement_requests_requester_id_foreign` (`requester_id`),
  KEY `placement_requests_target_partner_id_foreign` (`target_partner_id`),
  CONSTRAINT `placement_requests_requester_id_foreign` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`),
  CONSTRAINT `placement_requests_sheep_id_foreign` FOREIGN KEY (`sheep_id`) REFERENCES `sheep` (`id`) ON DELETE CASCADE,
  CONSTRAINT `placement_requests_target_partner_id_foreign` FOREIGN KEY (`target_partner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `placement_requests` WRITE;
/*!40000 ALTER TABLE `placement_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `placement_requests` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `places` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `google_maps_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_review_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `qr_sale_status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_for_sale',
  `buyer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_price` bigint unsigned DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `places_user_id_foreign` (`user_id`),
  CONSTRAINT `places_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `places` WRITE;
/*!40000 ALTER TABLE `places` DISABLE KEYS */;
INSERT INTO `places` VALUES (1,1,'CV SOLUSI KITA','Kompleks Ruko Metro Trade Center Jl. Soekarno Hatta. 590, MTC VI Blok H-26, Sekejati, Kec. Buahbatu, Kota Bandung, Jawa Barat 40286',NULL,'https://www.google.com/maps/search/?api=1&query=CV+SOLUSI+KITA+Kompleks+Ruko+Metro+Trade+Center+Jl.+Soekarno+Hatta.+590%2C+MTC+VI+Blok+H-26%2C+Sekejati%2C+Kec.+Buahbatu%2C+Kota+Bandung%2C+Jawa+Barat+40286&query_place_id=ChIJSVGGrInnaC4Rexb9i56KrMo','https://search.google.com/local/writereview?placeid=ChIJSVGGrInnaC4Rexb9i56KrMo','2026-10-07 01:07:04','2026-10-07 01:33:51','not_for_sale',NULL,NULL,NULL,NULL),(2,1,'Kan Kei cafe','RUKO MTC, Blk E3, Sekejati, Kec. Buahbatu, Kota Bandung, Jawa Barat 40266',NULL,'https://www.google.com/maps/search/?api=1&query=Kan+Kei+cafe+RUKO+MTC%2C+Blk+E3%2C+Sekejati%2C+Kec.+Buahbatu%2C+Kota+Bandung%2C+Jawa+Barat+40266&query_place_id=ChIJf8Jdi6TpaC4RmACQxwydr_I','https://search.google.com/local/writereview?placeid=ChIJf8Jdi6TpaC4RmACQxwydr_I','2026-10-07 01:08:24','2026-10-07 01:33:52','not_for_sale',NULL,NULL,NULL,NULL),(6,1,'SOLARIA','Solaria Metro Indah Mall Kawasan Niaga, Jl. Soekarno-Hatta No.590 Lt GF, Sekejati, Kec. Buahbatu, Kota Bandung, Jawa Barat 40286, Indonesia',NULL,'https://www.google.com/maps/search/?api=1&query=SOLARIA+Solaria+Metro+Indah+Mall+Kawasan+Niaga%2C+Jl.+Soekarno-Hatta+No.590+Lt+GF%2C+Sekejati%2C+Kec.+Buahbatu%2C+Kota+Bandung%2C+Jawa+Barat+40286%2C+Indonesia',NULL,'2026-10-07 20:33:45','2026-10-07 20:39:12','not_for_sale',NULL,NULL,NULL,NULL),(7,1,'RS Humana Prima','Jl. Rancabolang No.21, Manjahlega, Kec. Rancasari, Kota Bandung, Jawa Barat 40286',NULL,'https://maps.google.com/?cid=14314986389580285590&g_mp=Cidnb29nbGUubWFwcy5wbGFjZXMudjEuUGxhY2VzLlNlYXJjaFRleHQQAhgEIAA','https://search.google.com/local/writereview?placeid=ChIJnXYs0CPoaC4RlgZ8-0YMqcY','2026-10-07 20:47:38','2026-10-07 20:50:38','not_for_sale',NULL,NULL,NULL,NULL),(11,1,'Warung Bara','Jl. Batu Rahayu No.5, Batununggal, Kec. Bandung Kidul, Kota Bandung, Jawa Barat 40266',NULL,'https://maps.google.com/?cid=223884130422774361&g_mp=Cidnb29nbGUubWFwcy5wbGFjZXMudjEuUGxhY2VzLlNlYXJjaFRleHQQAhgEIAA','https://search.google.com/local/writereview?placeid=ChIJRUJoAkrpaC4RWVqyMG5lGwM','2026-10-07 21:01:05','2026-10-07 21:01:23','not_for_sale',NULL,NULL,NULL,NULL),(12,1,'','',NULL,NULL,NULL,'2026-10-07 21:12:49','2026-10-07 21:12:49','pending_payment',NULL,NULL,NULL,NULL),(13,1,'','',NULL,NULL,NULL,'2026-10-07 21:45:09','2026-10-07 21:45:09','pending_payment',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `places` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `profit_loss_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profit_loss_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sheep_id` bigint unsigned DEFAULT NULL,
  `shelter_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profit_loss_records_sheep_id_foreign` (`sheep_id`),
  KEY `profit_loss_records_shelter_id_foreign` (`shelter_id`),
  KEY `profit_loss_records_user_id_foreign` (`user_id`),
  CONSTRAINT `profit_loss_records_sheep_id_foreign` FOREIGN KEY (`sheep_id`) REFERENCES `sheep` (`id`) ON DELETE SET NULL,
  CONSTRAINT `profit_loss_records_shelter_id_foreign` FOREIGN KEY (`shelter_id`) REFERENCES `shelters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `profit_loss_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `profit_loss_records` WRITE;
/*!40000 ALTER TABLE `profit_loss_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `profit_loss_records` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `qr_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qr_clients_user_id_unique` (`user_id`),
  KEY `qr_clients_status_index` (`status`),
  CONSTRAINT `qr_clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `qr_clients` WRITE;
/*!40000 ALTER TABLE `qr_clients` DISABLE KEYS */;
INSERT INTO `qr_clients` VALUES (1,4,'KELONTONG','ACTIVE','2026-10-07 23:47:53','2026-10-07 23:47:53');
/*!40000 ALTER TABLE `qr_clients` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `qr_destinations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_destinations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `qr_unit_id` bigint unsigned NOT NULL,
  `place_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activation_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maps_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `review_url` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `deactivated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qr_destinations_activation_code_unique` (`activation_code`),
  KEY `qr_destinations_qr_unit_id_deactivated_at_index` (`qr_unit_id`,`deactivated_at`),
  CONSTRAINT `qr_destinations_qr_unit_id_foreign` FOREIGN KEY (`qr_unit_id`) REFERENCES `qr_units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `qr_destinations` WRITE;
/*!40000 ALTER TABLE `qr_destinations` DISABLE KEYS */;
INSERT INTO `qr_destinations` VALUES (3,11,'Istana Kawaluyaan','Jl. Kawaluyaan Raya No.9, Jatisari, Kec. Buahbatu, Kota Bandung, Jawa Barat 40286','ChIJIxEEqhzoaC4R4gKONS9AHLI',NULL,'https://maps.google.com/?cid=12834203609605210850&g_mp=Cidnb29nbGUubWFwcy5wbGFjZXMudjEuUGxhY2VzLlNlYXJjaFRleHQQAhgEIAA','https://search.google.com/local/writereview?placeid=ChIJIxEEqhzoaC4R4gKONS9AHLI','2026-10-07 23:53:59',NULL,'2026-10-07 23:53:59','2026-10-07 23:53:59'),(4,32,'CV Solusi Kita: Konsultan Pajak Bandung - Eks DJP & STAN','Kompleks Ruko Metro Trade Center Jl. Soekarno Hatta. 590, MTC VI Blok H-26, Sekejati, Kec. Buahbatu, Kota Bandung, Jawa Barat 40286, Indonesia','ChIJSVGGrInnaC4Rexb9i56KrMo','SWWXAARQA6','https://maps.google.com/?cid=14604200105213761147&g_mp=CiVnb29nbGUubWFwcy5wbGFjZXMudjEuUGxhY2VzLkdldFBsYWNlEAIYBCAA','https://search.google.com/local/writereview?placeid=ChIJSVGGrInnaC4Rexb9i56KrMo','2026-10-08 02:01:24',NULL,'2026-10-08 02:01:24','2026-10-08 02:01:24'),(5,43,'CV Solusi Kita: Konsultan Pajak Bandung - Eks DJP & STAN','Kompleks Ruko Metro Trade Center Jl. Soekarno Hatta. 590, MTC VI Blok H-26, Sekejati, Kec. Buahbatu, Kota Bandung, Jawa Barat 40286, Indonesia','ChIJSVGGrInnaC4Rexb9i56KrMo',NULL,'https://maps.google.com/?cid=14604200105213761147&g_mp=CiVnb29nbGUubWFwcy5wbGFjZXMudjEuUGxhY2VzLkdldFBsYWNlEAIYBCAA','https://search.google.com/local/writereview?placeid=ChIJSVGGrInnaC4Rexb9i56KrMo','2026-10-08 02:30:45',NULL,'2026-10-08 02:30:45','2026-10-08 02:30:45');
/*!40000 ALTER TABLE `qr_destinations` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `qr_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `qr_subscription_id` bigint unsigned NOT NULL,
  `invoice_number` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `proof_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_note` text COLLATE utf8mb4_unicode_ci,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `due_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qr_invoices_invoice_number_unique` (`invoice_number`),
  KEY `qr_invoices_qr_subscription_id_foreign` (`qr_subscription_id`),
  KEY `qr_invoices_status_created_at_index` (`status`,`created_at`),
  KEY `qr_invoices_status_index` (`status`),
  CONSTRAINT `qr_invoices_qr_subscription_id_foreign` FOREIGN KEY (`qr_subscription_id`) REFERENCES `qr_subscriptions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `qr_invoices` WRITE;
/*!40000 ALTER TABLE `qr_invoices` DISABLE KEYS */;
INSERT INTO `qr_invoices` VALUES (1,1,'QR-261008-UTSOS7',50000.00,'PAID','qr-payment-proofs/tn2AaxYKFudCcTgp4W2cXwi4pAHRWDKHBDPyncDL.jpg',NULL,NULL,'2026-10-10 23:49:12','2026-10-07 23:49:46','2026-10-07 23:49:12','2026-10-07 23:49:46');
/*!40000 ALTER TABLE `qr_invoices` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `qr_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(14,2) NOT NULL,
  `billing_cycle` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `unit_limit` int unsigned NOT NULL DEFAULT '1',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `qr_plans_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `qr_plans` WRITE;
/*!40000 ALTER TABLE `qr_plans` DISABLE KEYS */;
INSERT INTO `qr_plans` VALUES (1,'PAKET GACOR','ONLY ONE',50000.00,'monthly',1,'ACTIVE','2026-10-07 23:49:05','2026-10-07 23:49:05');
/*!40000 ALTER TABLE `qr_plans` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `qr_scan_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_scan_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `qr_unit_id` bigint unsigned NOT NULL,
  `event_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `qr_scan_events_event_type_created_at_index` (`event_type`,`created_at`),
  KEY `qr_scan_events_qr_unit_id_created_at_index` (`qr_unit_id`,`created_at`),
  KEY `qr_scan_events_event_type_index` (`event_type`),
  CONSTRAINT `qr_scan_events_qr_unit_id_foreign` FOREIGN KEY (`qr_unit_id`) REFERENCES `qr_units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `qr_scan_events` WRITE;
/*!40000 ALTER TABLE `qr_scan_events` DISABLE KEYS */;
INSERT INTO `qr_scan_events` VALUES (5,11,'scan','2026-10-07 23:52:46','2026-10-07 23:52:46'),(6,11,'review_click','2026-10-07 23:53:59','2026-10-07 23:53:59'),(9,32,'scan','2026-10-08 02:00:34','2026-10-08 02:00:34'),(10,32,'scan','2026-10-08 02:01:24','2026-10-08 02:01:24'),(11,32,'review_click','2026-10-08 02:01:27','2026-10-08 02:01:27'),(12,32,'scan','2026-10-08 02:02:23','2026-10-08 02:02:23'),(13,32,'review_click','2026-10-08 02:02:42','2026-10-08 02:02:42'),(14,43,'scan','2026-10-08 02:30:33','2026-10-08 02:30:33'),(15,43,'review_click','2026-10-08 02:30:45','2026-10-08 02:30:45'),(16,43,'scan','2026-10-08 02:34:18','2026-10-08 02:34:18'),(17,43,'review_click','2026-10-08 02:34:18','2026-10-08 02:34:18');
/*!40000 ALTER TABLE `qr_scan_events` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `qr_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `qr_client_id` bigint unsigned NOT NULL,
  `qr_plan_id` bigint unsigned NOT NULL,
  `billing_cycle` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `unit_limit` int unsigned NOT NULL DEFAULT '1',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `qr_subscriptions_qr_plan_id_foreign` (`qr_plan_id`),
  KEY `qr_subscriptions_qr_client_id_status_index` (`qr_client_id`,`status`),
  KEY `qr_subscriptions_status_index` (`status`),
  CONSTRAINT `qr_subscriptions_qr_client_id_foreign` FOREIGN KEY (`qr_client_id`) REFERENCES `qr_clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `qr_subscriptions_qr_plan_id_foreign` FOREIGN KEY (`qr_plan_id`) REFERENCES `qr_plans` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `qr_subscriptions` WRITE;
/*!40000 ALTER TABLE `qr_subscriptions` DISABLE KEYS */;
INSERT INTO `qr_subscriptions` VALUES (1,1,1,'monthly',1,'ACTIVE','2026-10-07 23:49:46','2026-11-07 23:49:46','2026-10-07 23:49:12','2026-10-07 23:49:46');
/*!40000 ALTER TABLE `qr_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `qr_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'google_review',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `shared_mode` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `qr_templates_created_by_foreign` (`created_by`),
  KEY `qr_templates_status_index` (`status`),
  CONSTRAINT `qr_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `qr_templates` WRITE;
/*!40000 ALTER TABLE `qr_templates` DISABLE KEYS */;
INSERT INTO `qr_templates` VALUES (2,'SUBS',NULL,'google_review','ACTIVE',0,1,'2026-10-07 23:52:00','2026-10-07 23:52:00'),(6,'sss',NULL,'google_review','ACTIVE',1,1,'2026-10-08 02:00:08','2026-10-08 02:00:08'),(7,'QR NFC',NULL,'qr_nfc','ACTIVE',0,1,'2026-10-08 02:16:07','2026-10-08 02:16:07'),(8,'sssq',NULL,'qr_nfc','ACTIVE',0,1,'2026-10-08 02:25:21','2026-10-08 02:25:21'),(9,'sadq',NULL,'qr_nfc','ACTIVE',0,1,'2026-10-08 02:53:09','2026-10-08 02:53:09');
/*!40000 ALTER TABLE `qr_templates` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `qr_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_units` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `qr_template_id` bigint unsigned NOT NULL,
  `qr_client_id` bigint unsigned DEFAULT NULL,
  `unit_code` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activation_code` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EMPTY',
  `production_batch` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qr_units_qr_template_id_unit_code_unique` (`qr_template_id`,`unit_code`),
  UNIQUE KEY `qr_units_token_unique` (`token`),
  KEY `qr_units_qr_template_id_status_index` (`qr_template_id`,`status`),
  KEY `qr_units_production_batch_index` (`production_batch`),
  KEY `qr_units_qr_client_id_status_index` (`qr_client_id`,`status`),
  CONSTRAINT `qr_units_qr_client_id_foreign` FOREIGN KEY (`qr_client_id`) REFERENCES `qr_clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `qr_units_qr_template_id_foreign` FOREIGN KEY (`qr_template_id`) REFERENCES `qr_templates` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `qr_units` WRITE;
/*!40000 ALTER TABLE `qr_units` DISABLE KEYS */;
INSERT INTO `qr_units` VALUES (11,2,1,'UNIT-000001','5de0ed94feb73cfe3e787a4151dab049f7cad08818bd4e98',NULL,'ACTIVE','5e920c06-cc1f-41ba-918e-9e512b4f1b69','2026-10-07 23:52:08','2026-10-07 23:53:59'),(32,6,NULL,'UNIT-000001','f50b634c886628f83e2af056987cf1b53deecf0fd247f6b4',NULL,'ACTIVE','5d3c68ae-e399-4a54-b049-cb1304c29814','2026-10-08 02:00:08','2026-10-08 02:01:24'),(33,7,NULL,'NFC-000001','b7ff8dfd0347693c3ec4633d78c5dedd4fa54d554f4fee45',NULL,'EMPTY','5dc72067-6709-4d46-b518-07facddf794d','2026-10-08 02:16:07','2026-10-08 02:16:07'),(34,7,NULL,'NFC-000002','4e305c3cce82279150a245f65b87e0fd82000481b16f5682',NULL,'EMPTY','5dc72067-6709-4d46-b518-07facddf794d','2026-10-08 02:16:07','2026-10-08 02:16:07'),(35,7,NULL,'NFC-000003','2bf4120f672a2168a210774c681ff9b552b923ae70ca453d',NULL,'EMPTY','5dc72067-6709-4d46-b518-07facddf794d','2026-10-08 02:16:07','2026-10-08 02:16:07'),(36,7,NULL,'NFC-000004','5a21594a56b6409e7895c84a7379279e9ae747b78d179eb4',NULL,'EMPTY','5dc72067-6709-4d46-b518-07facddf794d','2026-10-08 02:16:07','2026-10-08 02:16:07'),(37,7,NULL,'NFC-000005','fda871064c20998c12e034d1e75d67af44d6323ecc0a28bb',NULL,'EMPTY','5dc72067-6709-4d46-b518-07facddf794d','2026-10-08 02:16:07','2026-10-08 02:16:07'),(38,7,NULL,'NFC-000006','f2168eb72738ad4318caa9b8a036ae5d82ad7455a1cbeb38',NULL,'EMPTY','5dc72067-6709-4d46-b518-07facddf794d','2026-10-08 02:16:07','2026-10-08 02:16:07'),(39,7,NULL,'NFC-000007','ce3a3b66ba7b0fe2a5dfd34b9d9acd2f81d3871ff7fa7997',NULL,'EMPTY','5dc72067-6709-4d46-b518-07facddf794d','2026-10-08 02:16:07','2026-10-08 02:16:07'),(40,7,NULL,'NFC-000008','1a165cc816616b0f8edb618ec987b47daaebe15b4c228564',NULL,'EMPTY','5dc72067-6709-4d46-b518-07facddf794d','2026-10-08 02:16:07','2026-10-08 02:16:07'),(41,7,NULL,'NFC-000009','d0a7574f678f1c5d61bacc844ce50e667b697a94f60b91ba',NULL,'EMPTY','5dc72067-6709-4d46-b518-07facddf794d','2026-10-08 02:16:07','2026-10-08 02:16:07'),(42,7,NULL,'NFC-000010','2b68778370e8537f07272a503c7d447f4a0033ef9b6c0acf',NULL,'EMPTY','5dc72067-6709-4d46-b518-07facddf794d','2026-10-08 02:16:07','2026-10-08 02:16:07'),(43,8,NULL,'NFC-000001','4b4ae1cbafb5038b52b639c85395711aa1e4d5ee85f41d9c',NULL,'ACTIVE','ff383d61-a682-4a49-80b6-5710d8741eaa','2026-10-08 02:25:21','2026-10-08 02:30:45'),(44,8,NULL,'NFC-000002','77189919cbe8cb05e41adfcc11bb7c4d0e9c32efe1ac4103',NULL,'EMPTY','ff383d61-a682-4a49-80b6-5710d8741eaa','2026-10-08 02:25:21','2026-10-08 02:25:21'),(45,8,NULL,'NFC-000003','89d7c4212207caea965fee7f85366b66fd0f9900166ea371',NULL,'EMPTY','ff383d61-a682-4a49-80b6-5710d8741eaa','2026-10-08 02:25:21','2026-10-08 02:25:21'),(46,8,NULL,'NFC-000004','bdcedafc35b41ffa832acaf8c7f3818b60b2c1dc883ac755',NULL,'EMPTY','ff383d61-a682-4a49-80b6-5710d8741eaa','2026-10-08 02:25:21','2026-10-08 02:25:21'),(47,8,NULL,'NFC-000005','d01e965402f13d6c9aed7095e18564fa7a0adf63d643c7d5',NULL,'EMPTY','ff383d61-a682-4a49-80b6-5710d8741eaa','2026-10-08 02:25:21','2026-10-08 02:25:21'),(48,9,NULL,'NFC-000001','e76559214e279058a6e4cbe31f69b143ae7cefc832332197',NULL,'EMPTY','2fb40fc9-5f6f-4b62-aa60-2ed55d6a32c8','2026-10-08 02:53:09','2026-10-08 02:53:09'),(49,9,NULL,'NFC-000002','0e3fd6e0057799f03cff75d4dc6290589fb1faf4d07adb5b',NULL,'EMPTY','2fb40fc9-5f6f-4b62-aa60-2ed55d6a32c8','2026-10-08 02:53:09','2026-10-08 02:53:09'),(50,9,NULL,'NFC-000003','53d7e6c14ee37a0ac7b8f95bbef8e426639fceb2fc89db64',NULL,'EMPTY','2fb40fc9-5f6f-4b62-aa60-2ed55d6a32c8','2026-10-08 02:53:09','2026-10-08 02:53:09'),(51,9,NULL,'NFC-000004','7bd11dd8483a129acda2f9f5bcb2ad99b93df41e7b15fc9d',NULL,'EMPTY','2fb40fc9-5f6f-4b62-aa60-2ed55d6a32c8','2026-10-08 02:53:09','2026-10-08 02:53:09'),(52,9,NULL,'NFC-000005','42d9935c094d08a13cf01472b2d33d190c64dfab15e36146',NULL,'EMPTY','2fb40fc9-5f6f-4b62-aa60-2ed55d6a32c8','2026-10-08 02:53:09','2026-10-08 02:53:09'),(53,9,NULL,'NFC-000006','b61a77d1a118d29a3ac238576508e8190bdd173f77827def',NULL,'EMPTY','2fb40fc9-5f6f-4b62-aa60-2ed55d6a32c8','2026-10-08 02:53:09','2026-10-08 02:53:09'),(54,9,NULL,'NFC-000007','d705346cb1644db1853adc31380d16a35675ce46ebfea809',NULL,'EMPTY','2fb40fc9-5f6f-4b62-aa60-2ed55d6a32c8','2026-10-08 02:53:09','2026-10-08 02:53:09'),(55,9,NULL,'NFC-000008','08c3d5737e5ea5565a015b1a7f6ec6caf54974e8950c362d',NULL,'EMPTY','2fb40fc9-5f6f-4b62-aa60-2ed55d6a32c8','2026-10-08 02:53:09','2026-10-08 02:53:09'),(56,9,NULL,'NFC-000009','e5f2e31fe5d7b0799d78401ab007a639edb28bc2d74432bf',NULL,'EMPTY','2fb40fc9-5f6f-4b62-aa60-2ed55d6a32c8','2026-10-08 02:53:09','2026-10-08 02:53:09'),(57,9,NULL,'NFC-000010','7272a85e07f908989f1cc2ac9dc72a84a190d4d76d9bef2a',NULL,'EMPTY','2fb40fc9-5f6f-4b62-aa60-2ed55d6a32c8','2026-10-08 02:53:09','2026-10-08 02:53:09');
/*!40000 ALTER TABLE `qr_units` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `reproduction_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reproduction_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `female_sheep_id` bigint unsigned NOT NULL,
  `male_sheep_id` bigint unsigned NOT NULL,
  `status` enum('Planned','Mated','Pregnant','Delivered','Failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Mated',
  `mating_date` date NOT NULL COMMENT 'Tanggal Kawin',
  `expected_delivery_date` date DEFAULT NULL,
  `actual_delivery_date` date DEFAULT NULL COMMENT 'Tanggal Domba Melahirkan',
  `weaning_date` date DEFAULT NULL COMMENT 'Tanggal Sapih',
  `offspring_count` smallint unsigned DEFAULT NULL COMMENT 'Jumlah anak yang lahir',
  `assistance_user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reproduction_records_female_sheep_id_foreign` (`female_sheep_id`),
  KEY `reproduction_records_male_sheep_id_foreign` (`male_sheep_id`),
  KEY `reproduction_records_assistance_user_id_foreign` (`assistance_user_id`),
  CONSTRAINT `reproduction_records_assistance_user_id_foreign` FOREIGN KEY (`assistance_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reproduction_records_female_sheep_id_foreign` FOREIGN KEY (`female_sheep_id`) REFERENCES `sheep` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reproduction_records_male_sheep_id_foreign` FOREIGN KEY (`male_sheep_id`) REFERENCES `sheep` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `reproduction_records` WRITE;
/*!40000 ALTER TABLE `reproduction_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `reproduction_records` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('5nWCUaCMdBTmlRpkGUeoCjVCyc6bPgyWhp6C1H0d',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNG0yZnQ4RmxsZHFNa0NVU1hlSU1STmh2VFdmUTd5MGx3VnJObE9zTCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3BsYWNlcy80Ijt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1791450940),('6k6RJYPcIBNkl5S6JyQVxNZ8bdAFuVmPpfJUggzr',NULL,'2404:c0:a703:7849:68dd:c1a9:68f4:1334','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ0kxUGlDNTZuQnN3Rm5vOXkwa0NENjA5T2YxNFpTcHZiV3ZvSVVwTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTAwOiJodHRwczovL2FubmllLWZpcm1zLXJlZWQtd2l0aGluLnRyeWNsb3VkZmxhcmUuY29tL24vNGI0YWUxY2JhZmI1MDM4YjUyYjYzOWM4NTM5NTcxMWFhMWU0ZDVlZTg1ZjQxZDljIjtzOjU6InJvdXRlIjtzOjExOiJxci5uZmMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1791452058),('b9eiaW8hfdhRZJ3PNS3fQNWjss0smXjhsovsiM1i',4,'2404:c0:a703:7849:75d2:5170:80ae:43c7','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTVR1NWw5a0plenFhdVJOWFpIeDZrU0Y5MHR1ajlYMnk0ZUgwN0c1bSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjI6Imh0dHBzOi8vYW5uaWUtZmlybXMtcmVlZC13aXRoaW4udHJ5Y2xvdWRmbGFyZS5jb20vY2xpZW50L3BsYW5zIjtzOjU6InJvdXRlIjtzOjEyOiJjbGllbnQucGxhbnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=',1791444272),('DBPf1XbjTfbpt4JiQXLn13V1MRrGMz6aBkTWgqbC',1,'2404:c0:a703:7849:75d2:5170:80ae:43c7','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMzlQQkpvVVYza1hMeW9qWFRXTGMySUN0M3Z2ZDVBb2w0ZWRNVDBwSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTEzOiJodHRwczovL2FubmllLWZpcm1zLXJlZWQtd2l0aGluLnRyeWNsb3VkZmxhcmUuY29tL2FkbWluL3FyLXVuaXRzL2JhdGNoLzJmYjQwZmM5LTVmNmYtNGI2Mi1hYTYwLTJlZDU1ZDZhMzJjOC9wcmludCI7czo1OiJyb3V0ZSI7czoyNjoiYWRtaW4ucXItdW5pdHMuYmF0Y2gucHJpbnQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1791453190),('kDuNmT7nkAUx71wpvDI8LACHlxSlgYm9RGrSA54w',NULL,'2404:c0:a703:7849:68dd:c1a9:68f4:1334','WhatsApp/2.23.20.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZnJ0UHd1ZkhrU1RSSllCY2lMMmYxN3Zmc0xVMTNPRjZoWUYwaDJYRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHBzOi8vYW5uaWUtZmlybXMtcmVlZC13aXRoaW4udHJ5Y2xvdWRmbGFyZS5jb20vcC8xIjtzOjU6InJvdXRlIjtzOjEzOiJwbGFjZXMucHVibGljIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1791447110),('kgqXFDHhEZ8JDAIdEgg9igGvYw9p3oZ5OFpBrsQZ',NULL,'2404:c0:a703:7849:75d2:5170:80ae:43c7','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQndkckxreXJTYW85TUhBOFg3dUpOZmY3MkttQ3RCcDJEMldneFFhZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYW5uaWUtZmlybXMtcmVlZC13aXRoaW4udHJ5Y2xvdWRmbGFyZS5jb20vbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1791436106),('M1cKpRvdEFQ9mDZQbTVB7xsU0K3izneXUOKnxEJm',NULL,'2404:c0:2a10::5c4d:7b77','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRDFCczhTV1ltWWNlb3diN0lZUmhJWG1CMlQwUUNTT3FmRFBxR1BaZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTAwOiJodHRwczovL2FubmllLWZpcm1zLXJlZWQtd2l0aGluLnRyeWNsb3VkZmxhcmUuY29tL3EvNzk2ZGE3MzU4NGI2YTNlYjE4MTBhODVjOGQ5YmVlODc5YjFmZjIyM2Y4ODNjYjJkIjtzOjU6InJvdXRlIjtzOjE0OiJxci5wdWJsaWMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1791436080),('WsiaPSV14tvmuKOQL5IeKGQl6BgOhu5eugmAl08b',NULL,'2404:c0:2a10::5c4d:7b77','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMkFJSldhS05ITHBFSmppR1N4UFQzNUpmZ0V0WktqR2R0azJMS3NlciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTAwOiJodHRwczovL2FubmllLWZpcm1zLXJlZWQtd2l0aGluLnRyeWNsb3VkZmxhcmUuY29tL3EvNzk2ZGE3MzU4NGI2YTNlYjE4MTBhODVjOGQ5YmVlODc5YjFmZjIyM2Y4ODNjYjJkIjtzOjU6InJvdXRlIjtzOjE0OiJxci5wdWJsaWMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1791436089),('xcj3o6NdFhwK7nYnavs7WjwqslAGeUpXGMcb8bpb',NULL,'2404:c0:2a10::5c4d:7b77','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVkdjNW81Mnk4OFNhZkw4QmQ0aDBSY2tvMXBmdkhMT1E3bzJIUmhnWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTAwOiJodHRwczovL2FubmllLWZpcm1zLXJlZWQtd2l0aGluLnRyeWNsb3VkZmxhcmUuY29tL3EvNzk2ZGE3MzU4NGI2YTNlYjE4MTBhODVjOGQ5YmVlODc5YjFmZjIyM2Y4ODNjYjJkIjtzOjU6InJvdXRlIjtzOjE0OiJxci5wdWJsaWMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1791436142),('xizNdv2J6jiYR4xuZ4WdS70ESN1yLOYVjlgAuDbw',NULL,'2404:c0:2a10::5c4d:7b77','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUElFbk1yRjRKM2dPOU5nejNtcWlRc0NYRjlPZkNXemt2WEdYNDM0aCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHBzOi8vYW5uaWUtZmlybXMtcmVlZC13aXRoaW4udHJ5Y2xvdWRmbGFyZS5jb20vcC8xIjtzOjU6InJvdXRlIjtzOjEzOiJwbGFjZXMucHVibGljIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1791447205),('zdqXGXTibvEEaU6BYAT433Y12auWLfL0Lw9L7bSf',NULL,'2404:c0:a703:7849:75d2:5170:80ae:43c7','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoib2lsWmQwNkdxRTl6ZXhTcUltcHE0dUh4engyOXRBYXk2WWxreW5rbiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo2ODoiaHR0cHM6Ly9hbm5pZS1maXJtcy1yZWVkLXdpdGhpbi50cnljbG91ZGZsYXJlLmNvbS9hZG1pbi9xci10ZW1wbGF0ZXMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo2ODoiaHR0cHM6Ly9hbm5pZS1maXJtcy1yZWVkLXdpdGhpbi50cnljbG91ZGZsYXJlLmNvbS9hZG1pbi9xci10ZW1wbGF0ZXMiO3M6NToicm91dGUiO3M6MjQ6ImFkbWluLnFyLXRlbXBsYXRlcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1791436105);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `sheep`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sheep` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `shelter_id` bigint unsigned DEFAULT NULL,
  `partner_id` bigint unsigned DEFAULT NULL,
  `tag_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('Jantan','Betina') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `birth_weight` decimal(5,2) DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kategori domba: Pedaging, Indukan, dll.',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tipe/Ras domba: Garut, Texel, dll.',
  `placement_status` enum('Internal','Partner') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Internal',
  `father_id` bigint unsigned DEFAULT NULL,
  `mother_id` bigint unsigned DEFAULT NULL,
  `purchase_price` bigint unsigned DEFAULT NULL,
  `is_pedigree` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Flag untuk struktur populasi/silsilah unggul',
  `special_characteristics` text COLLATE utf8mb4_unicode_ci,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sheep_tag_number_unique` (`tag_number`),
  KEY `sheep_user_id_foreign` (`user_id`),
  KEY `sheep_shelter_id_foreign` (`shelter_id`),
  KEY `sheep_father_id_foreign` (`father_id`),
  KEY `sheep_mother_id_foreign` (`mother_id`),
  KEY `sheep_partner_id_foreign` (`partner_id`),
  CONSTRAINT `sheep_father_id_foreign` FOREIGN KEY (`father_id`) REFERENCES `sheep` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sheep_mother_id_foreign` FOREIGN KEY (`mother_id`) REFERENCES `sheep` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sheep_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sheep_shelter_id_foreign` FOREIGN KEY (`shelter_id`) REFERENCES `shelters` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `sheep_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sheep` WRITE;
/*!40000 ALTER TABLE `sheep` DISABLE KEYS */;
INSERT INTO `sheep` VALUES (1,1,1,NULL,'DMB-DEMO-001','Jantan','2025-04-07',4.20,'Pedaging','Garut','Internal',NULL,NULL,2500000,1,'Tubuh besar, kondisi sehat',NULL,'Data contoh untuk demonstrasi aplikasi.','2026-10-07 00:51:54','2026-10-07 00:51:54'),(2,1,1,NULL,'DMB-DEMO-002','Betina','2025-07-07',3.80,'Indukan','Garut','Internal',NULL,NULL,2200000,1,'Indukan produktif',NULL,'Data contoh untuk demonstrasi aplikasi.','2026-10-07 00:51:54','2026-10-07 00:51:54'),(3,1,1,NULL,'DMB-DEMO-003','Jantan','2025-12-07',3.50,'Pedaging','Texel','Internal',NULL,NULL,1800000,0,'Pertumbuhan normal',NULL,'Data contoh untuk demonstrasi aplikasi.','2026-10-07 00:51:54','2026-10-07 00:51:54'),(4,1,1,NULL,'DMB-DEMO-004','Betina','2026-02-07',3.20,'Indukan','Garut','Internal',NULL,NULL,1700000,0,'Bulu putih, aktif',NULL,'Data contoh untuk demonstrasi aplikasi.','2026-10-07 00:51:54','2026-10-07 00:51:54'),(5,1,1,NULL,'DMB-DEMO-005','Jantan','2026-05-07',2.90,'Pedaging','Lokal','Internal',NULL,NULL,1300000,0,'Anak domba sehat',NULL,'Data contoh untuk demonstrasi aplikasi.','2026-10-07 00:51:54','2026-10-07 00:51:54');
/*!40000 ALTER TABLE `sheep` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `shelters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shelters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `capacity` smallint unsigned NOT NULL DEFAULT '0' COMMENT 'Kapasitas maksimal domba',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shelters_name_unique` (`name`),
  KEY `shelters_user_id_foreign` (`user_id`),
  CONSTRAINT `shelters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `shelters` WRITE;
/*!40000 ALTER TABLE `shelters` DISABLE KEYS */;
INSERT INTO `shelters` VALUES (1,1,'Kandang Utama A','Kandang pembibitan utama',50,'2026-10-07 00:50:15','2026-10-07 00:50:15'),(2,1,'Kandang Isolasi B','Untuk domba sakit atau karantina',10,'2026-10-07 00:50:15','2026-10-07 00:50:15'),(3,1,'Kandang dolor','Quis eos exercitationem maiores vel magnam odit.',23,'2026-10-07 00:50:15','2026-10-07 00:50:15'),(4,2,'Kandang delectus','Quam eveniet sit sed eos.',53,'2026-10-07 00:50:15','2026-10-07 00:50:15'),(5,3,'Kandang illo','Maxime corrupti ea ipsam doloribus error laboriosam similique.',41,'2026-10-07 00:50:15','2026-10-07 00:50:15'),(6,1,'Kandang animi','Voluptatum est et sint iusto.',82,'2026-10-07 00:51:54','2026-10-07 00:51:54'),(7,2,'Kandang eaque','Consequuntur ut mollitia placeat magnam magni ut velit.',20,'2026-10-07 00:51:54','2026-10-07 00:51:54'),(8,3,'Kandang voluptatibus','Consequatur nostrum modi repellendus.',67,'2026-10-07 00:51:54','2026-10-07 00:51:54');
/*!40000 ALTER TABLE `shelters` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `symptoms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `symptoms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `symptoms_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `symptoms` WRITE;
/*!40000 ALTER TABLE `symptoms` DISABLE KEYS */;
/*!40000 ALTER TABLE `symptoms` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','staff','mitra','qr_client') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@admin.com','admin','081234567890','Kantor Pusat JAS Farm','2026-10-07 00:51:54','$2y$12$taVtSlspG4zLbnM1RMc1EuxnYcJgMAR6pZeznnB5f6XaP1.NR3RDe',NULL,'2026-10-07 00:50:14','2026-10-07 00:51:54'),(2,'Budi (Mitra Plasma)','mitra@example.com','mitra','089876543210','Desa Suka Maju, Blok C','2026-10-07 00:51:54','$2y$12$EL4XWuy2wKtfuHBdxMVGSOHT9z/AR.Uj/d7H6E8ETmXqPYNSyrIAG',NULL,'2026-10-07 00:50:14','2026-10-07 00:51:54'),(3,'Ujang (Anak Kandang)','staff@example.com','staff','081122334455','Mess Karyawan','2026-10-07 00:51:54','$2y$12$IbV4DAqfMqFg2nzQm1N49eBk3.82jeiqKECU/DecGN6TngKFfpty.',NULL,'2026-10-07 00:50:15','2026-10-07 00:51:54'),(4,'Rahman','rahman@email.com','qr_client','08511111111111',NULL,NULL,'$2y$12$P7vs8FQXT9x/c9yfIFShhu3BJwTzKb2BHSXo7XiFytZP/JbcBHYZe',NULL,'2026-10-07 23:47:53','2026-10-07 23:47:53');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `weight_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `weight_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sheep_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `weighing_date` date NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `weight_records_sheep_id_foreign` (`sheep_id`),
  KEY `weight_records_user_id_foreign` (`user_id`),
  CONSTRAINT `weight_records_sheep_id_foreign` FOREIGN KEY (`sheep_id`) REFERENCES `sheep` (`id`) ON DELETE CASCADE,
  CONSTRAINT `weight_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `weight_records` WRITE;
/*!40000 ALTER TABLE `weight_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `weight_records` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

