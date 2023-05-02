-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for kodatos
-- CREATE DATABASE IF NOT EXISTS `kodatos` /*!40100 DEFAULT CHARACTER SET latin1 */;
-- USE `kodatos`;

-- Dumping structure for table kodatos.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` text,
  `email` text,
  `password` text,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `role_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `first_name` text,
  `middle_name` text,
  `last_name` text,
  `suffix` text,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table kodatos.accounts: ~2 rows (approximately)
DELETE FROM `accounts`;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` (`id`, `username`, `email`, `password`, `enabled`, `role_id`, `group_id`, `first_name`, `middle_name`, `last_name`, `suffix`) VALUES
	(5, 'admin', 'admin@admin.com', '$2y$10$YFXIB6SMdripXD1WnogxWuQXNynPM91faOKNQfXm8fVp.mPYCXhq2', 1, 0, 0, 'Admin', '', 'Admin', '');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;

-- Dumping structure for table kodatos.account_sessions
CREATE TABLE IF NOT EXISTS `account_sessions` (
  `session_id` varchar(255) DEFAULT NULL,
  `creation` timestamp NULL DEFAULT NULL,
  `expiry` timestamp NULL DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table kodatos.account_sessions: ~2 rows (approximately)
DELETE FROM `account_sessions`;
/*!40000 ALTER TABLE `account_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_sessions` ENABLE KEYS */;

-- Dumping structure for table kodatos.locations
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table kodatos.locations: ~0 rows (approximately)
DELETE FROM `locations`;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;

-- Dumping structure for table kodatos.patients
CREATE TABLE IF NOT EXISTS `patients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_code` tinytext,
  `security_code` tinytext,
  `first_name` text,
  `last_name` text,
  `middle_name` text,
  `suffix` text,
  `gender` int(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `civil_status` int(11) DEFAULT NULL,
  `contact_number` int(11) DEFAULT NULL,
  `email` text,
  `location_id` int(11) DEFAULT NULL,
  `street_address` text,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table kodatos.patients: ~0 rows (approximately)
DELETE FROM `patients`;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;

-- Dumping structure for table kodatos.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vax_name` text,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table kodatos.products: ~5 rows (approximately)
DELETE FROM `products`;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `vax_name`) VALUES
	(1, 'Sinovac'),
	(2, 'Pfizer (Comirnaty)'),
	(3, 'Moderna'),
	(4, 'Novavax'),
	(5, 'AstraZeneca');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table kodatos.sites
CREATE TABLE IF NOT EXISTS `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `location_id` int(11) DEFAULT NULL,
  `is_laboratory` tinyint(1) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Dumping data for table kodatos.sites: ~0 rows (approximately)
DELETE FROM `sites`;
/*!40000 ALTER TABLE `sites` DISABLE KEYS */;
/*!40000 ALTER TABLE `sites` ENABLE KEYS */;

-- Dumping structure for table kodatos.testrecords
CREATE TABLE IF NOT EXISTS `testrecords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) DEFAULT '0',
  `test_date` date DEFAULT NULL,
  `test_site_id` int(11) DEFAULT NULL,
  `test_type` int(11) DEFAULT NULL,
  `test_result` tinyint(1) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table kodatos.testrecords: ~0 rows (approximately)
DELETE FROM `testrecords`;
/*!40000 ALTER TABLE `testrecords` DISABLE KEYS */;
/*!40000 ALTER TABLE `testrecords` ENABLE KEYS */;

-- Dumping structure for table kodatos.testtype
CREATE TABLE IF NOT EXISTS `testtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table kodatos.testtype: ~3 rows (approximately)
DELETE FROM `testtype`;
/*!40000 ALTER TABLE `testtype` DISABLE KEYS */;
INSERT INTO `testtype` (`id`, `name`) VALUES
	(1, 'RT-PCR Test (Nasal Swab)'),
	(2, 'RT-PCR Test (Saliva)'),
	(3, 'Rapid Antigen Test');
/*!40000 ALTER TABLE `testtype` ENABLE KEYS */;

-- Dumping structure for table kodatos.vaxrecords
CREATE TABLE IF NOT EXISTS `vaxrecords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) DEFAULT NULL,
  `vax_dosenum` int(10) unsigned DEFAULT NULL,
  `vax_product_id` int(10) unsigned DEFAULT NULL,
  `vax_lotnum` text,
  `vax_expiry` date DEFAULT NULL,
  `vax_date` date DEFAULT NULL,
  `vax_site_id` int(11) DEFAULT NULL,
  `vax_hcw_id` int(11) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table kodatos.vaxrecords: ~0 rows (approximately)
DELETE FROM `vaxrecords`;
/*!40000 ALTER TABLE `vaxrecords` DISABLE KEYS */;
/*!40000 ALTER TABLE `vaxrecords` ENABLE KEYS */;

-- Dumping structure for table kodatos.workers
CREATE TABLE IF NOT EXISTS `workers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` text,
  `middle_name` text,
  `last_name` text,
  `suffix` text,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table kodatos.workers: ~0 rows (approximately)
DELETE FROM `workers`;
/*!40000 ALTER TABLE `workers` DISABLE KEYS */;
/*!40000 ALTER TABLE `workers` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
