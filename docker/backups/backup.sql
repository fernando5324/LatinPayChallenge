-- MySQL dump 10.13  Distrib 8.4.9, for Linux (x86_64)
--
-- Host: localhost    Database: latinpaychallenge
-- ------------------------------------------------------
-- Server version	8.4.9

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
-- Table structure for table `bank_notifications`
--

DROP TABLE IF EXISTS `bank_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bank_notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_id` varchar(30) NOT NULL,
  `bank_transaction_id` varchar(50) NOT NULL,
  `payment_code` varchar(30) NOT NULL,
  `payload` json DEFAULT NULL COMMENT 'payload original recibido',
  `amount` decimal(8,2) NOT NULL,
  `is_deleted` int DEFAULT '0' COMMENT '0 = no eliminado, 1 = eliminado',
  `currency` char(5) NOT NULL,
  `status` char(20) NOT NULL,
  `paid_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora del registro en TimeZone 0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_id` (`event_id`),
  UNIQUE KEY `bank_transaction_id` (`bank_transaction_id`),
  KEY `idx_payment_code` (`payment_code`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='tabla de notificaciones de banco';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_notifications`
--

LOCK TABLES `bank_notifications` WRITE;
/*!40000 ALTER TABLE `bank_notifications` DISABLE KEYS */;
INSERT INTO `bank_notifications` VALUES (13,'evt_0012','bank_tx_999','LTP-20260505-000001','{\"amount\": 150.51, \"status\": \"PAID\", \"paid_at\": \"2026-04-24 20:44:00\", \"currency\": \"PEN\", \"event_id\": \"evt_0012\", \"payment_code\": \"LTP-20260505-000001\", \"bank_transaction_id\": \"bank_tx_999\"}',150.51,0,'PEN','PAID','2026-04-24 20:44:00','2026-05-16 18:10:25','2026-05-16 18:10:25');
/*!40000 ALTER TABLE `bank_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_reconciliation_movements`
--

DROP TABLE IF EXISTS `bank_reconciliation_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bank_reconciliation_movements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bank` varchar(60) NOT NULL,
  `bank_transaction_id` varchar(50) NOT NULL,
  `bank_movement_id` varchar(30) NOT NULL,
  `process_date` date NOT NULL,
  `status` char(20) NOT NULL,
  `payment_code` varchar(30) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `currency` char(5) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Tabla de movimientos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_reconciliation_movements`
--

LOCK TABLES `bank_reconciliation_movements` WRITE;
/*!40000 ALTER TABLE `bank_reconciliation_movements` DISABLE KEYS */;
INSERT INTO `bank_reconciliation_movements` VALUES (1,'BANK_A','bank_tx_999','mov_001','2026-04-24','UNMATCHED','LTP-20260503-000001',150.50,'PEN','2026-05-16 02:10:58',NULL),(2,'BANK_A','bank_tx_1000','mov_002','2026-04-24','UNMATCHED','LTP-20260504-000002',200.00,'PEN','2026-05-16 02:10:58',NULL);
/*!40000 ALTER TABLE `bank_reconciliation_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `external_notifications`
--

DROP TABLE IF EXISTS `external_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `external_notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `payment_id` int NOT NULL,
  `status` char(20) NOT NULL,
  `attempts` int DEFAULT '0',
  `error` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_id` (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Tabla de notificaciones externas';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `external_notifications`
--

LOCK TABLES `external_notifications` WRITE;
/*!40000 ALTER TABLE `external_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `external_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_audits`
--

DROP TABLE IF EXISTS `payment_audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_audits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payment_code` varchar(30) NOT NULL,
  `action` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Tabla de auditorÃ­a de pagos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_audits`
--

LOCK TABLES `payment_audits` WRITE;
/*!40000 ALTER TABLE `payment_audits` DISABLE KEYS */;
INSERT INTO `payment_audits` VALUES (1,'LTP-20260515-000001','CREATED','PENDING','','2026-05-15 21:10:19','2026-05-15 21:10:19'),(2,'LTP-20260505-000001','PAYMENT_NOT_FOUND','','','2026-05-16 18:10:26','2026-05-16 18:10:26');
/*!40000 ALTER TABLE `payment_audits` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-17  6:10:58
