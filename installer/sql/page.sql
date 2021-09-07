-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.19-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6337
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table newcms.active_guests
DROP TABLE IF EXISTS `active_guests`;
CREATE TABLE IF NOT EXISTS `active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.active_guests: ~0 rows (approximately)
/*!40000 ALTER TABLE `active_guests` DISABLE KEYS */;
/*!40000 ALTER TABLE `active_guests` ENABLE KEYS */;

-- Dumping structure for table newcms.active_sessions
DROP TABLE IF EXISTS `active_sessions`;
CREATE TABLE IF NOT EXISTS `active_sessions` (
  `session` char(64) COLLATE utf8_bin DEFAULT NULL,
  `date_session` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table newcms.active_sessions: ~0 rows (approximately)
/*!40000 ALTER TABLE `active_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `active_sessions` ENABLE KEYS */;

-- Dumping structure for table newcms.active_users
DROP TABLE IF EXISTS `active_users`;
CREATE TABLE IF NOT EXISTS `active_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.active_users: ~0 rows (approximately)
/*!40000 ALTER TABLE `active_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `active_users` ENABLE KEYS */;

-- Dumping structure for table newcms.admins
DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `adminid` char(23) NOT NULL DEFAULT 'uuid_short();',
  `userid` char(128) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `superadmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.admins: ~0 rows (approximately)
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;

-- Dumping structure for table newcms.announcement
DROP TABLE IF EXISTS `announcement`;
CREATE TABLE IF NOT EXISTS `announcement` (
  `Announcement_ID` int(11) unsigned NOT NULL,
  `Is_Active` enum('N','Y') NOT NULL DEFAULT 'N',
  `Topic` varchar(50) NOT NULL,
  `Message` mediumtext NOT NULL,
  `Date_LastUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Language` char(2) NOT NULL DEFAULT 'en',
  `Auto_Publish` enum('Y','N') DEFAULT 'N',
  `Date_Start` datetime DEFAULT NULL,
  `Date_End` datetime DEFAULT NULL,
  `Date_Created` datetime DEFAULT NULL,
  `Created_By` varchar(200) DEFAULT NULL,
  `Translated_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.announcement: ~0 rows (approximately)
/*!40000 ALTER TABLE `announcement` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcement` ENABLE KEYS */;

-- Dumping structure for table newcms.app_config
DROP TABLE IF EXISTS `app_config`;
CREATE TABLE IF NOT EXISTS `app_config` (
  `setting` char(26) NOT NULL,
  `value` varchar(12000) NOT NULL,
  `sortorder` int(5) DEFAULT NULL,
  `category` varchar(25) NOT NULL,
  `type` varchar(15) NOT NULL,
  `description` varchar(140) DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.app_config: ~0 rows (approximately)
/*!40000 ALTER TABLE `app_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_config` ENABLE KEYS */;

-- Dumping structure for table newcms.balance
DROP TABLE IF EXISTS `balance`;
CREATE TABLE IF NOT EXISTS `balance` (
  `id` int(11) NOT NULL,
  `user_id` char(128) NOT NULL,
  `currency` varchar(50) DEFAULT NULL,
  `balance` varchar(50) DEFAULT NULL,
  `awaiting_deposit` varchar(50) DEFAULT NULL,
  `reserved_in_orders` varchar(50) DEFAULT NULL,
  `est_USD` varchar(50) DEFAULT NULL,
  `deposit` varchar(50) DEFAULT NULL,
  `withdrawal` varchar(50) DEFAULT NULL,
  `history` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.balance: ~0 rows (approximately)
/*!40000 ALTER TABLE `balance` DISABLE KEYS */;
/*!40000 ALTER TABLE `balance` ENABLE KEYS */;

-- Dumping structure for table newcms.banned_users
DROP TABLE IF EXISTS `banned_users`;
CREATE TABLE IF NOT EXISTS `banned_users` (
  `user_id` char(128) NOT NULL,
  `banned_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `banned_hours` float NOT NULL,
  `hours_remaining` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.banned_users: ~0 rows (approximately)
/*!40000 ALTER TABLE `banned_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `banned_users` ENABLE KEYS */;

-- Dumping structure for table newcms.blocks
DROP TABLE IF EXISTS `blocks`;
CREATE TABLE IF NOT EXISTS `blocks` (
  `idB` int(11) NOT NULL AUTO_INCREMENT,
  `blockId` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `pageId` int(11) DEFAULT NULL,
  PRIMARY KEY (`idB`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.blocks: ~0 rows (approximately)
/*!40000 ALTER TABLE `blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocks` ENABLE KEYS */;

-- Dumping structure for table newcms.blocks_content
DROP TABLE IF EXISTS `blocks_content`;
CREATE TABLE IF NOT EXISTS `blocks_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blockId` int(11) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.blocks_content: ~0 rows (approximately)
/*!40000 ALTER TABLE `blocks_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocks_content` ENABLE KEYS */;

-- Dumping structure for table newcms.breadcrumblinks
DROP TABLE IF EXISTS `breadcrumblinks`;
CREATE TABLE IF NOT EXISTS `breadcrumblinks` (
  `page_title` varchar(100) NOT NULL,
  `page_url` varchar(100) NOT NULL,
  `lft` int(4) NOT NULL,
  `rgt` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.breadcrumblinks: ~0 rows (approximately)
/*!40000 ALTER TABLE `breadcrumblinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `breadcrumblinks` ENABLE KEYS */;

-- Dumping structure for table newcms.category
DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.category: ~0 rows (approximately)
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

-- Dumping structure for table newcms.cols_set
DROP TABLE IF EXISTS `cols_set`;
CREATE TABLE IF NOT EXISTS `cols_set` (
  `id` int(11) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `col_name` varchar(50) DEFAULT NULL,
  `type_input` varchar(50) DEFAULT NULL,
  `list_page` varchar(50) DEFAULT NULL,
  `add_page` varchar(50) DEFAULT NULL,
  `update_page` varchar(50) DEFAULT NULL,
  `view_page` varchar(50) DEFAULT NULL,
  `delete_page` varchar(50) DEFAULT NULL,
  `search_text` varchar(50) DEFAULT NULL,
  `col_set` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.cols_set: ~0 rows (approximately)
/*!40000 ALTER TABLE `cols_set` DISABLE KEYS */;
/*!40000 ALTER TABLE `cols_set` ENABLE KEYS */;

-- Dumping structure for table newcms.comment
DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL,
  `message` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.comment: ~0 rows (approximately)
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;

-- Dumping structure for table newcms.configuration
DROP TABLE IF EXISTS `configuration`;
CREATE TABLE IF NOT EXISTS `configuration` (
  `config_name` varchar(20) DEFAULT NULL,
  `config_value` varchar(250) DEFAULT NULL,
  UNIQUE KEY `type_name` (`config_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.configuration: ~30 rows (approximately)
/*!40000 ALTER TABLE `configuration` DISABLE KEYS */;
INSERT INTO `configuration` (`config_name`, `config_value`) VALUES
	('DOMAIN_SITE', 'www.yourdomain.com'),
	('SITE_NAME', 'PHP GrapesJS'),
	('SITE_DESC', 'Your keywords for your domains'),
	('SITE_KEYWORDS', 'Your keywords for your domains'),
	('SITE_DESCRIPTION', 'Your keywords for your domains'),
	('SITE_ADMIN', 'Super Admin'),
	('SITE_CONTROL', 'dashboard'),
	('SITE_CONFIG', 'config'),
	('SITE_LIST', 'list'),
	('SITE_EDITOR', 'editor'),
	('SITE_BUILDER', 'builder'),
	('SITE_LANGUAGE_1', 'English'),
	('SITE_LANGUAGE_2', 'EspaÃ±ol'),
	('SITE_EMAIL', 'info@yourdomain.com'),
	('IMG_PAGE', 'http://yourdomain.com/uploads/image-page.jpg'),
	('NAME_CONTACT', 'Your contact Name'),
	('PHONE_CONTACT', '0051 999888777'),
	('EMAIL_CONTACT', 'info@yourdomain.com'),
	('ADDRESS', 'Your local contact address'),
	('FOLDER_IMAGES', 'uploads'),
	('SITE_CREATOR', '@yourdomain'),
	('TWITTER', '@yourdomain'),
	('FACEBOOKID', '26245712364571234572'),
	('SKYPE', 'yourdomain'),
	('TELEGRAM', 'yourdomain'),
	('WHATSAPP', '+51 999888777'),
	('SUPERADMIN_NAME', 'Super Admin'),
	('SUPERADMIN_LEVEL', '9'),
	('ADMIN_NAME', 'Admin'),
	('ADMIN_LEVEL', '5');
/*!40000 ALTER TABLE `configuration` ENABLE KEYS */;

-- Dumping structure for table newcms.cookies
DROP TABLE IF EXISTS `cookies`;
CREATE TABLE IF NOT EXISTS `cookies` (
  `cookieid` char(23) NOT NULL,
  `userid` char(128) NOT NULL,
  `tokenid` char(25) NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.cookies: ~0 rows (approximately)
/*!40000 ALTER TABLE `cookies` DISABLE KEYS */;
/*!40000 ALTER TABLE `cookies` ENABLE KEYS */;

-- Dumping structure for table newcms.countries
DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.countries: ~0 rows (approximately)
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;

-- Dumping structure for table newcms.currency
DROP TABLE IF EXISTS `currency`;
CREATE TABLE IF NOT EXISTS `currency` (
  `id` int(11) NOT NULL,
  `coin` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `symbol` varchar(50) DEFAULT NULL,
  `rating` varchar(50) DEFAULT NULL,
  `total_supply` varchar(50) DEFAULT NULL,
  `max_supply` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.currency: ~0 rows (approximately)
/*!40000 ALTER TABLE `currency` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency` ENABLE KEYS */;

-- Dumping structure for table newcms.customers
DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `Customer_ID` int(11) NOT NULL,
  `Customer_Number` varchar(20) NOT NULL,
  `Customer_Name` varchar(50) NOT NULL,
  `Address` text NOT NULL,
  `City` varchar(50) NOT NULL,
  `Country` varchar(30) NOT NULL,
  `Contact_Person` varchar(50) NOT NULL,
  `Phone_Number` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Mobile_Number` varchar(50) NOT NULL,
  `Notes` varchar(50) NOT NULL,
  `Balance` double DEFAULT 0,
  `Date_Added` datetime DEFAULT NULL,
  `Added_By` varchar(50) DEFAULT NULL,
  `Date_Updated` datetime DEFAULT NULL,
  `Updated_By` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.customers: ~0 rows (approximately)
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;

-- Dumping structure for table newcms.deleted_users
DROP TABLE IF EXISTS `deleted_users`;
CREATE TABLE IF NOT EXISTS `deleted_users` (
  `user_id` char(128) NOT NULL,
  `username` varchar(65) NOT NULL,
  `password` varchar(65) NOT NULL,
  `email` varchar(65) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `banned` tinyint(1) NOT NULL DEFAULT 0,
  `mod_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.deleted_users: ~0 rows (approximately)
/*!40000 ALTER TABLE `deleted_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `deleted_users` ENABLE KEYS */;

-- Dumping structure for table newcms.deposit
DROP TABLE IF EXISTS `deposit`;
CREATE TABLE IF NOT EXISTS `deposit` (
  `id` int(11) NOT NULL,
  `user_id` char(128) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.deposit: ~0 rows (approximately)
/*!40000 ALTER TABLE `deposit` DISABLE KEYS */;
/*!40000 ALTER TABLE `deposit` ENABLE KEYS */;

-- Dumping structure for table newcms.employee
DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `id` int(11) NOT NULL COMMENT 'primary key',
  `employee_name` varchar(255) NOT NULL COMMENT 'employee name',
  `employee_lastname` varchar(255) NOT NULL COMMENT 'employee lastname',
  `employee_email` varchar(255) NOT NULL COMMENT 'employee email',
  `employee_phone` int(11) NOT NULL COMMENT 'employee phone',
  `employee_bridge` varchar(255) NOT NULL COMMENT 'employee bridge',
  `employee_comments` varchar(255) NOT NULL COMMENT 'employee comments'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.employee: ~0 rows (approximately)
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;

-- Dumping structure for table newcms.eventlog
DROP TABLE IF EXISTS `eventlog`;
CREATE TABLE IF NOT EXISTS `eventlog` (
  `id` bigint(20) unsigned NOT NULL,
  `event` varchar(200) NOT NULL,
  `eventRowIdOrRef` varchar(20) DEFAULT NULL,
  `eventDesc` text DEFAULT NULL,
  `eventTable` varchar(20) DEFAULT NULL,
  `staffInCharge` bigint(20) unsigned NOT NULL,
  `eventTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.eventlog: ~0 rows (approximately)
/*!40000 ALTER TABLE `eventlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `eventlog` ENABLE KEYS */;

-- Dumping structure for table newcms.exchange
DROP TABLE IF EXISTS `exchange`;
CREATE TABLE IF NOT EXISTS `exchange` (
  `id` int(11) NOT NULL,
  `exchange_name` varchar(100) DEFAULT NULL,
  `trading_pairs` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.exchange: ~0 rows (approximately)
/*!40000 ALTER TABLE `exchange` DISABLE KEYS */;
/*!40000 ALTER TABLE `exchange` ENABLE KEYS */;

-- Dumping structure for table newcms.exchanges
DROP TABLE IF EXISTS `exchanges`;
CREATE TABLE IF NOT EXISTS `exchanges` (
  `id` int(11) NOT NULL,
  `uid` varchar(128) DEFAULT NULL,
  `wid` int(11) DEFAULT NULL,
  `gateway_send` int(11) DEFAULT NULL,
  `gateway_receive` int(11) DEFAULT NULL,
  `amount_send` varchar(255) DEFAULT NULL,
  `amount_receive` varchar(255) DEFAULT NULL,
  `rate_from` varchar(255) DEFAULT NULL,
  `rate_to` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created` int(11) NOT NULL DEFAULT 0,
  `updated` int(11) NOT NULL DEFAULT 0,
  `expired` int(11) NOT NULL DEFAULT 0,
  `u_field_1` varchar(255) DEFAULT NULL,
  `u_field_2` varchar(255) DEFAULT NULL,
  `u_field_3` varchar(255) DEFAULT NULL,
  `u_field_4` varchar(255) DEFAULT NULL,
  `u_field_5` varchar(255) DEFAULT NULL,
  `u_field_6` varchar(255) DEFAULT NULL,
  `u_field_7` varchar(255) DEFAULT NULL,
  `u_field_8` varchar(255) DEFAULT NULL,
  `u_field_9` varchar(255) DEFAULT NULL,
  `u_field_10` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `transaction_id` text DEFAULT NULL,
  `exchange_id` varchar(255) DEFAULT NULL,
  `referral_id` varchar(255) NOT NULL DEFAULT '0',
  `referral_amount` varchar(255) DEFAULT NULL,
  `referral_currency` varchar(255) DEFAULT NULL,
  `referral_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.exchanges: ~0 rows (approximately)
/*!40000 ALTER TABLE `exchanges` DISABLE KEYS */;
/*!40000 ALTER TABLE `exchanges` ENABLE KEYS */;

-- Dumping structure for table newcms.faq
DROP TABLE IF EXISTS `faq`;
CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL,
  `question` varchar(255) DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `created` timestamp NULL DEFAULT current_timestamp(),
  `updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.faq: ~0 rows (approximately)
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;

-- Dumping structure for table newcms.finance
DROP TABLE IF EXISTS `finance`;
CREATE TABLE IF NOT EXISTS `finance` (
  `id` int(11) NOT NULL,
  `user_id` char(128) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.finance: ~0 rows (approximately)
/*!40000 ALTER TABLE `finance` DISABLE KEYS */;
/*!40000 ALTER TABLE `finance` ENABLE KEYS */;

-- Dumping structure for table newcms.financial_institution
DROP TABLE IF EXISTS `financial_institution`;
CREATE TABLE IF NOT EXISTS `financial_institution` (
  `id` int(11) NOT NULL,
  `financial_institution` varchar(50) DEFAULT NULL,
  `link` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.financial_institution: ~0 rows (approximately)
/*!40000 ALTER TABLE `financial_institution` DISABLE KEYS */;
/*!40000 ALTER TABLE `financial_institution` ENABLE KEYS */;

-- Dumping structure for table newcms.follow
DROP TABLE IF EXISTS `follow`;
CREATE TABLE IF NOT EXISTS `follow` (
  `follow_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.follow: ~0 rows (approximately)
/*!40000 ALTER TABLE `follow` DISABLE KEYS */;
/*!40000 ALTER TABLE `follow` ENABLE KEYS */;

-- Dumping structure for table newcms.forgot_pass
DROP TABLE IF EXISTS `forgot_pass`;
CREATE TABLE IF NOT EXISTS `forgot_pass` (
  `idFgp` int(11) NOT NULL,
  `username` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `password_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `create` timestamp NULL DEFAULT current_timestamp(),
  `expire` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.forgot_pass: ~0 rows (approximately)
/*!40000 ALTER TABLE `forgot_pass` DISABLE KEYS */;
/*!40000 ALTER TABLE `forgot_pass` ENABLE KEYS */;

-- Dumping structure for table newcms.forgot_pin
DROP TABLE IF EXISTS `forgot_pin`;
CREATE TABLE IF NOT EXISTS `forgot_pin` (
  `idFgp` int(11) NOT NULL,
  `username` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `pin_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `create` timestamp NULL DEFAULT current_timestamp(),
  `expire` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.forgot_pin: ~0 rows (approximately)
/*!40000 ALTER TABLE `forgot_pin` DISABLE KEYS */;
/*!40000 ALTER TABLE `forgot_pin` ENABLE KEYS */;

-- Dumping structure for table newcms.galleries
DROP TABLE IF EXISTS `galleries`;
CREATE TABLE IF NOT EXISTS `galleries` (
  `idGal` int(11) NOT NULL AUTO_INCREMENT,
  `gallery` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` int(11) DEFAULT 1,
  `description` text DEFAULT NULL,
  `pageId` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT 0,
  PRIMARY KEY (`idGal`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.galleries: ~0 rows (approximately)
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;

-- Dumping structure for table newcms.gallery
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `link` varchar(100) DEFAULT '#',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.gallery: ~0 rows (approximately)
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;

-- Dumping structure for table newcms.gateways
DROP TABLE IF EXISTS `gateways`;
CREATE TABLE IF NOT EXISTS `gateways` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `reserve` varchar(255) DEFAULT NULL,
  `min_amount` varchar(255) DEFAULT NULL,
  `max_amount` varchar(255) DEFAULT NULL,
  `exchange_type` int(11) DEFAULT NULL,
  `include_fee` int(11) DEFAULT NULL,
  `extra_fee` varchar(255) DEFAULT NULL,
  `fee` varchar(255) DEFAULT NULL,
  `allow_send` int(11) DEFAULT NULL,
  `allow_receive` int(11) DEFAULT NULL,
  `default_send` int(11) DEFAULT NULL,
  `default_receive` int(11) DEFAULT NULL,
  `allow_payouts` int(11) DEFAULT NULL,
  `field_1` varchar(255) DEFAULT NULL,
  `field_2` varchar(255) DEFAULT NULL,
  `field_3` varchar(255) DEFAULT NULL,
  `field_4` varchar(255) DEFAULT NULL,
  `field_5` varchar(255) DEFAULT NULL,
  `field_6` varchar(255) DEFAULT NULL,
  `field_7` varchar(255) DEFAULT NULL,
  `field_8` varchar(255) DEFAULT NULL,
  `field_9` varchar(255) DEFAULT NULL,
  `field_10` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `external_gateway` int(11) NOT NULL DEFAULT 0,
  `external_icon` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.gateways: ~0 rows (approximately)
/*!40000 ALTER TABLE `gateways` DISABLE KEYS */;
/*!40000 ALTER TABLE `gateways` ENABLE KEYS */;

-- Dumping structure for table newcms.gateways_fields
DROP TABLE IF EXISTS `gateways_fields`;
CREATE TABLE IF NOT EXISTS `gateways_fields` (
  `id` int(11) NOT NULL,
  `gateway_id` int(11) DEFAULT NULL,
  `field_name` varchar(255) DEFAULT NULL,
  `field_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.gateways_fields: ~0 rows (approximately)
/*!40000 ALTER TABLE `gateways_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `gateways_fields` ENABLE KEYS */;

-- Dumping structure for table newcms.help
DROP TABLE IF EXISTS `help`;
CREATE TABLE IF NOT EXISTS `help` (
  `Help_ID` int(11) NOT NULL,
  `Language` char(2) NOT NULL,
  `Topic` varchar(255) NOT NULL,
  `Description` longtext NOT NULL,
  `Category` int(11) NOT NULL,
  `Order` int(11) NOT NULL,
  `Display_in_Page` varchar(100) NOT NULL,
  `Updated_By` varchar(20) DEFAULT NULL,
  `Last_Updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.help: ~0 rows (approximately)
/*!40000 ALTER TABLE `help` DISABLE KEYS */;
/*!40000 ALTER TABLE `help` ENABLE KEYS */;

-- Dumping structure for table newcms.help_categories
DROP TABLE IF EXISTS `help_categories`;
CREATE TABLE IF NOT EXISTS `help_categories` (
  `category_id` int(11) NOT NULL,
  `language` char(2) NOT NULL,
  `category_description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.help_categories: ~0 rows (approximately)
/*!40000 ALTER TABLE `help_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `help_categories` ENABLE KEYS */;

-- Dumping structure for table newcms.history
DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `id` int(11) NOT NULL,
  `user_id` char(128) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.history: ~0 rows (approximately)
/*!40000 ALTER TABLE `history` DISABLE KEYS */;
/*!40000 ALTER TABLE `history` ENABLE KEYS */;

-- Dumping structure for table newcms.image_gal
DROP TABLE IF EXISTS `image_gal`;
CREATE TABLE IF NOT EXISTS `image_gal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `galId` int(11) DEFAULT 0,
  `sort` int(11) DEFAULT 0,
  `image` varchar(100) DEFAULT '',
  `caption_en` text DEFAULT NULL,
  `caption_es` text DEFAULT NULL,
  `paypal_code` text DEFAULT NULL,
  `link` varchar(250) DEFAULT '#',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.image_gal: ~0 rows (approximately)
/*!40000 ALTER TABLE `image_gal` DISABLE KEYS */;
/*!40000 ALTER TABLE `image_gal` ENABLE KEYS */;

-- Dumping structure for table newcms.ip
DROP TABLE IF EXISTS `ip`;
CREATE TABLE IF NOT EXISTS `ip` (
  `id_session` char(128) DEFAULT NULL,
  `user_data` char(64) NOT NULL,
  `address` char(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.ip: ~0 rows (approximately)
/*!40000 ALTER TABLE `ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `ip` ENABLE KEYS */;

-- Dumping structure for table newcms.items
DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.items: ~0 rows (approximately)
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
/*!40000 ALTER TABLE `items` ENABLE KEYS */;

-- Dumping structure for table newcms.languages
DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `Language_Code` char(2) NOT NULL,
  `Language_Name` varchar(20) NOT NULL,
  `Default` enum('Y','N') DEFAULT 'N',
  `Site_Logo` varchar(100) NOT NULL,
  `Site_Title` varchar(100) NOT NULL,
  `Default_Thousands_Separator` varchar(5) DEFAULT NULL,
  `Default_Decimal_Point` varchar(5) DEFAULT NULL,
  `Default_Currency_Symbol` varchar(10) DEFAULT NULL,
  `Default_Money_Thousands_Separator` varchar(5) DEFAULT NULL,
  `Default_Money_Decimal_Point` varchar(5) DEFAULT NULL,
  `Terms_And_Condition_Text` text NOT NULL,
  `Announcement_Text` text NOT NULL,
  `About_Text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.languages: ~0 rows (approximately)
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;

-- Dumping structure for table newcms.last_transaction
DROP TABLE IF EXISTS `last_transaction`;
CREATE TABLE IF NOT EXISTS `last_transaction` (
  `id` int(11) NOT NULL,
  `user_id` char(128) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(50) NOT NULL,
  `send_amount` varchar(50) NOT NULL,
  `received_amount` varchar(50) NOT NULL,
  `bonus_amount` varchar(50) NOT NULL,
  `rate` varchar(50) NOT NULL,
  `total` varchar(50) NOT NULL,
  `fee` varchar(50) NOT NULL,
  `acctions` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.last_transaction: ~0 rows (approximately)
/*!40000 ALTER TABLE `last_transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `last_transaction` ENABLE KEYS */;

-- Dumping structure for table newcms.lk_sess
DROP TABLE IF EXISTS `lk_sess`;
CREATE TABLE IF NOT EXISTS `lk_sess` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.lk_sess: ~0 rows (approximately)
/*!40000 ALTER TABLE `lk_sess` DISABLE KEYS */;
/*!40000 ALTER TABLE `lk_sess` ENABLE KEYS */;

-- Dumping structure for table newcms.login_attempts
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id_session` varchar(128) DEFAULT NULL,
  `user_data` varchar(65) DEFAULT NULL,
  `ip_address` varchar(20) NOT NULL,
  `attempts` int(11) NOT NULL,
  `lastlogin` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.login_attempts: ~0 rows (approximately)
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;

-- Dumping structure for table newcms.mail
DROP TABLE IF EXISTS `mail`;
CREATE TABLE IF NOT EXISTS `mail` (
  `mail_id` int(80) NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT 0,
  `UserTo` tinytext NOT NULL,
  `UserFrom` tinytext NOT NULL,
  `Subject` mediumtext NOT NULL,
  `Message` longtext NOT NULL,
  `status` text NOT NULL,
  `SentDate` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.mail: ~0 rows (approximately)
/*!40000 ALTER TABLE `mail` DISABLE KEYS */;
/*!40000 ALTER TABLE `mail` ENABLE KEYS */;

-- Dumping structure for table newcms.mail_log
DROP TABLE IF EXISTS `mail_log`;
CREATE TABLE IF NOT EXISTS `mail_log` (
  `id` int(11) NOT NULL,
  `type` varchar(45) NOT NULL DEFAULT 'generic',
  `status` varchar(45) DEFAULT NULL,
  `recipient` varchar(5000) DEFAULT NULL,
  `response` mediumtext NOT NULL,
  `isread` tinyint(1) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.mail_log: ~0 rows (approximately)
/*!40000 ALTER TABLE `mail_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `mail_log` ENABLE KEYS */;

-- Dumping structure for table newcms.members
DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` char(128) NOT NULL,
  `username` varchar(65) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(65) NOT NULL DEFAULT '',
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `banned` tinyint(1) NOT NULL DEFAULT 0,
  `mod_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `recovery_password` varchar(128) NOT NULL DEFAULT '0',
  `mktoken` varchar(128) NOT NULL DEFAULT '',
  `mkkey` varchar(128) NOT NULL,
  `mkhash` varchar(128) NOT NULL,
  `mkiv` varchar(128) NOT NULL,
  `mkpin` char(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.members: ~0 rows (approximately)
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
/*!40000 ALTER TABLE `members` ENABLE KEYS */;

-- Dumping structure for table newcms.member_info
DROP TABLE IF EXISTS `member_info`;
CREATE TABLE IF NOT EXISTS `member_info` (
  `userid` char(128) NOT NULL,
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address1` varchar(45) DEFAULT NULL,
  `address2` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `bio` varchar(20000) DEFAULT NULL,
  `userimage` varchar(255) DEFAULT NULL,
  UNIQUE KEY `userid_UNIQUE` (`userid`),
  KEY `fk_userid_idx` (`userid`),
  CONSTRAINT `fk_userid` FOREIGN KEY (`userid`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.member_info: ~0 rows (approximately)
/*!40000 ALTER TABLE `member_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `member_info` ENABLE KEYS */;

-- Dumping structure for table newcms.member_roles
DROP TABLE IF EXISTS `member_roles`;
CREATE TABLE IF NOT EXISTS `member_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` char(128) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_unique_idx` (`member_id`,`role_id`),
  KEY `member_id_idx` (`member_id`),
  KEY `fk_role_id_idx` (`role_id`),
  CONSTRAINT `fk_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.member_roles: ~0 rows (approximately)
/*!40000 ALTER TABLE `member_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `member_roles` ENABLE KEYS */;

-- Dumping structure for table newcms.menu
DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `idMenu` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `title_page` varchar(100) DEFAULT NULL,
  `link_page` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT 0,
  PRIMARY KEY (`idMenu`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.menu: ~0 rows (approximately)
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;

-- Dumping structure for table newcms.multimedia_gal
DROP TABLE IF EXISTS `multimedia_gal`;
CREATE TABLE IF NOT EXISTS `multimedia_gal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `galId` int(11) DEFAULT 0,
  `sort` int(11) DEFAULT 0,
  `name` varchar(100) DEFAULT NULL,
  `image` varchar(250) DEFAULT '',
  `description_en` text DEFAULT NULL,
  `description_es` text DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `idlink` varchar(20) DEFAULT '#',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.multimedia_gal: ~0 rows (approximately)
/*!40000 ALTER TABLE `multimedia_gal` DISABLE KEYS */;
/*!40000 ALTER TABLE `multimedia_gal` ENABLE KEYS */;

-- Dumping structure for table newcms.orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `order_name` varchar(255) NOT NULL,
  `order_email` varchar(255) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `name` (`order_name`),
  KEY `email` (`order_email`),
  KEY `order_date` (`order_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.orders: ~0 rows (approximately)
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;

-- Dumping structure for table newcms.orders_items
DROP TABLE IF EXISTS `orders_items`;
CREATE TABLE IF NOT EXISTS `orders_items` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.orders_items: ~0 rows (approximately)
/*!40000 ALTER TABLE `orders_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders_items` ENABLE KEYS */;

-- Dumping structure for table newcms.page
DROP TABLE IF EXISTS `page`;
CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` int(11) NOT NULL DEFAULT 1,
  `position` int(11) NOT NULL DEFAULT 0,
  `title` varchar(50) DEFAULT 'Title',
  `link` varchar(150) DEFAULT NULL,
  `url` varchar(150) DEFAULT NULL,
  `keyword` varchar(150) DEFAULT NULL,
  `classification` varchar(150) DEFAULT NULL,
  `description` varchar(160) DEFAULT NULL,
  `image` varchar(150) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `menu` int(11) DEFAULT 1,
  `hidden_page` tinyint(1) DEFAULT 0,
  `path_file` varchar(250) DEFAULT NULL,
  `script_name` varchar(250) DEFAULT NULL,
  `template` varchar(150) DEFAULT NULL,
  `base_template` varchar(150) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `style` longtext DEFAULT NULL,
  `startpage` int(11) DEFAULT 0,
  `level` int(11) DEFAULT 1,
  `parent` int(11) DEFAULT 0,
  `sort` int(11) DEFAULT 0,
  `active` int(11) DEFAULT 1,
  `update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.page: ~0 rows (approximately)
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
/*!40000 ALTER TABLE `page` ENABLE KEYS */;

-- Dumping structure for table newcms.payment_transactions
DROP TABLE IF EXISTS `payment_transactions`;
CREATE TABLE IF NOT EXISTS `payment_transactions` (
  `Payment_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Ref_ID` varchar(20) DEFAULT NULL,
  `Type` enum('sales','purchase') DEFAULT NULL,
  `Customer` varchar(20) DEFAULT NULL,
  `Supplier` varchar(20) DEFAULT NULL,
  `Sub_Total` double NOT NULL DEFAULT 0,
  `Payment` double NOT NULL DEFAULT 0,
  `Balance` double NOT NULL DEFAULT 0,
  `Due_Date` date DEFAULT NULL,
  `Date_Transaction` date DEFAULT NULL,
  `Date_Added` datetime DEFAULT NULL,
  `Added_By` varchar(50) DEFAULT NULL,
  `Date_Updated` datetime DEFAULT NULL,
  `Updated_By` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Payment_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.payment_transactions: ~0 rows (approximately)
/*!40000 ALTER TABLE `payment_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_transactions` ENABLE KEYS */;

-- Dumping structure for table newcms.personal_config
DROP TABLE IF EXISTS `personal_config`;
CREATE TABLE IF NOT EXISTS `personal_config` (
  `idPconf` int(11) DEFAULT NULL,
  `looking_for` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `education` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `occupation_industry` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `relationship` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `ethnicity` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `smokes` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `drinks` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `children` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `body_type` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `height` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `width` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `about_me` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `interest` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `skills` varchar(50) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table newcms.personal_config: ~0 rows (approximately)
/*!40000 ALTER TABLE `personal_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_config` ENABLE KEYS */;

-- Dumping structure for table newcms.plugins_app
DROP TABLE IF EXISTS `plugins_app`;
CREATE TABLE IF NOT EXISTS `plugins_app` (
  `id` int(11) NOT NULL,
  `plugins` char(50) COLLATE utf8_bin DEFAULT NULL,
  `pluginsOpts` char(50) COLLATE utf8_bin DEFAULT NULL,
  `script` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `css` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `buttons` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `plugins_script` char(250) COLLATE utf8_bin DEFAULT NULL,
  `plugins_css` char(250) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table newcms.plugins_app: ~35 rows (approximately)
/*!40000 ALTER TABLE `plugins_app` DISABLE KEYS */;
INSERT INTO `plugins_app` (`id`, `plugins`, `pluginsOpts`, `script`, `css`, `buttons`, `plugins_script`, `plugins_css`) VALUES
	(1, 'gjs-component-countdown', '', '', '', '', '', ''),
	(2, 'gjs-navbar', '', '', '', '', '', ''),
	(3, 'gjs-plugin-ckeditor', '', '', '', '', '', ''),
	(4, 'gjs-plugin-filestack', '', '', '', '', '', ''),
	(5, 'gjs-preset-newsletter', '', '', '', '', '', ''),
	(6, 'gjs-preset-webpage', '', '', '', '', '', ''),
	(7, 'grapesjs-blocks-bootstrap4', '', '', '', '', '', ''),
	(8, 'grapesjs-code-editor', '', '', '', '', '', ''),
	(9, 'grapesjs-component-code-editor', '', '', '', '', '', ''),
	(10, 'grapesjs-custom-code', '', '', '', '', '', ''),
	(11, 'grapesjs-echarts', '', '', '', '', '', ''),
	(12, 'grapesjs-firestore', '', '', '', '', '', ''),
	(13, 'grapesjs-ga', '', '', '', '', '', ''),
	(14, 'grapesjs-indexeddb', '', '', '', '', '', ''),
	(15, 'grapesjs-lory-slider', '', '', '', '', '', ''),
	(16, 'grapesjs-mjml', '', '', '', '', '', ''),
	(17, 'grapesjs-page-break', '', '', '', '', '', ''),
	(18, 'grapesjs-parser-postcss', '', '', '', '', '', ''),
	(19, 'grapesjs-plugin-export', '', '', '', '', '', ''),
	(20, 'grapesjs-plugin-forms', '', '', '', '', '', ''),
	(21, 'grapesjs-plugin-toolbox', '', '', '', '', '', ''),
	(22, 'grapesjs-project-manager', '', '', '', '', '', ''),
	(23, 'grapesjs-script-editor', '', '', '', '', '', ''),
	(24, 'grapesjs-style-bg', '', '', '', '', '', ''),
	(25, 'grapesjs-style-filter', '', '', '', '', '', ''),
	(26, 'grapesjs-style-gradient', '', '', '', '', '', ''),
	(27, 'grapesjs-swiper-slider', '', '', '', '', '', ''),
	(28, 'grapesjs-table', '', '', '', '', '', ''),
	(29, 'grapesjs-tabs', '', '', '', '', '', ''),
	(30, 'grapesjs-tooltip', '', '', '', '', '', ''),
	(31, 'grapesjs-touch', '', '', '', '', '', ''),
	(32, 'grapesjs-tui-image-editor', '', '', '', '', '', ''),
	(33, 'grapesjs-typed', '', '', '', '', '', ''),
	(34, 'grapesjs-uikit', '', '', '', '', '', '');
/*!40000 ALTER TABLE `plugins_app` ENABLE KEYS */;

-- Dumping structure for table newcms.press_gal
DROP TABLE IF EXISTS `press_gal`;
CREATE TABLE IF NOT EXISTS `press_gal` (
  `idPr` int(11) NOT NULL AUTO_INCREMENT,
  `galId` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `image` varchar(150) DEFAULT NULL,
  `title` varchar(50) DEFAULT '',
  `subtitle` varchar(100) DEFAULT '',
  `description` text DEFAULT NULL,
  `printing_date` varchar(30) DEFAULT NULL,
  `type_press` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idPr`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.press_gal: ~0 rows (approximately)
/*!40000 ALTER TABLE `press_gal` DISABLE KEYS */;
/*!40000 ALTER TABLE `press_gal` ENABLE KEYS */;

-- Dumping structure for table newcms.profiles
DROP TABLE IF EXISTS `profiles`;
CREATE TABLE IF NOT EXISTS `profiles` (
  `idp` char(128) NOT NULL,
  `mkhash` varchar(256) NOT NULL DEFAULT '',
  `firstname` varchar(128) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `gender` enum('Woman','Male','With doubt') DEFAULT NULL,
  `age` tinyint(3) DEFAULT NULL,
  `avatar` varchar(250) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `phone` varchar(128) DEFAULT NULL,
  `website` varchar(128) DEFAULT NULL,
  `social_media` varchar(350) DEFAULT NULL,
  `profession` varchar(128) DEFAULT NULL,
  `occupation` varchar(128) DEFAULT NULL,
  `public_email` varchar(128) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `followers_count` int(11) DEFAULT NULL,
  `profile_image` varchar(128) DEFAULT NULL,
  `profile_cover` varchar(128) DEFAULT NULL,
  `profile_bio` text DEFAULT NULL,
  `language` varchar(128) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `banned` tinyint(1) NOT NULL DEFAULT 1,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idp`) USING BTREE,
  UNIQUE KEY `id` (`idp`) USING BTREE,
  UNIQUE KEY `phone` (`phone`),
  CONSTRAINT `FK_profiles_uverify` FOREIGN KEY (`idp`) REFERENCES `uverify` (`iduv`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.profiles: ~1 rows (approximately)


-- Dumping structure for table newcms.purchases
DROP TABLE IF EXISTS `purchases`;
CREATE TABLE IF NOT EXISTS `purchases` (
  `Purchase_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Purchase_Number` varchar(20) NOT NULL,
  `Purchase_Date` datetime NOT NULL,
  `Supplier_ID` varchar(20) NOT NULL,
  `Notes` varchar(50) DEFAULT NULL,
  `Total_Amount` double(20,0) DEFAULT 0,
  `Total_Payment` double(20,0) DEFAULT 0,
  `Total_Balance` double(20,0) DEFAULT 0,
  `Date_Added` datetime DEFAULT NULL,
  `Added_By` varchar(50) DEFAULT NULL,
  `Date_Updated` datetime DEFAULT NULL,
  `Updated_By` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Purchase_ID`),
  KEY `TSupplierTBeli` (`Supplier_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.purchases: ~0 rows (approximately)
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;

-- Dumping structure for table newcms.purchases_detail
DROP TABLE IF EXISTS `purchases_detail`;
CREATE TABLE IF NOT EXISTS `purchases_detail` (
  `Purchase_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Purchase_Number` varchar(20) NOT NULL,
  `Supplier_Number` varchar(20) NOT NULL,
  `Stock_Item` varchar(15) NOT NULL,
  `Purchasing_Quantity` double(20,0) NOT NULL DEFAULT 0,
  `Purchasing_Price` double(20,0) NOT NULL DEFAULT 0,
  `Selling_Price` double(20,0) NOT NULL DEFAULT 0,
  `Purchasing_Total_Amount` double(20,0) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Purchase_ID`),
  KEY `TBarangTDBeli` (`Stock_Item`),
  KEY `TBeliTDBeli` (`Purchase_Number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.purchases_detail: ~0 rows (approximately)
/*!40000 ALTER TABLE `purchases_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchases_detail` ENABLE KEYS */;

-- Dumping structure for table newcms.role_permissions
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Role_Id_idx` (`role_id`),
  KEY `fk_Permission_Id_idx` (`permission_id`),
  CONSTRAINT `fk_Permission_Id` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Role_Id_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.role_permissions: ~24 rows (approximately)
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES
	(1, 1, 1),
	(2, 1, 2),
	(3, 1, 3),
	(4, 1, 4),
	(5, 1, 5),
	(6, 1, 6),
	(7, 1, 7),
	(8, 1, 8),
	(9, 1, 9),
	(10, 1, 10),
	(11, 1, 11),
	(12, 1, 12),
	(13, 1, 13),
	(14, 1, 14),
	(15, 1, 15),
	(16, 2, 1),
	(17, 2, 2),
	(18, 2, 3),
	(19, 2, 4),
	(20, 2, 5),
	(21, 2, 12),
	(22, 2, 13),
	(23, 2, 14),
	(24, 2, 15);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;

-- Dumping structure for table newcms.route_page
DROP TABLE IF EXISTS `route_page`;
CREATE TABLE IF NOT EXISTS `route_page` (
  `idRpg` int(11) NOT NULL AUTO_INCREMENT,
  `language` int(11) NOT NULL DEFAULT 1,
  `position` int(11) NOT NULL DEFAULT 0,
  `title` varchar(50) DEFAULT 'Title',
  `link` varchar(150) DEFAULT '#',
  `url` varchar(150) DEFAULT '#',
  `keyword` varchar(150) DEFAULT NULL,
  `classification` varchar(150) DEFAULT NULL,
  `description` varchar(160) DEFAULT NULL,
  `image` varchar(150) DEFAULT '#',
  `type` int(11) DEFAULT NULL,
  `menu` int(11) DEFAULT 1,
  `path` varchar(250) DEFAULT NULL,
  `file_name` varchar(250) DEFAULT NULL,
  `template` varchar(150) DEFAULT NULL,
  `base_template` varchar(150) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `style` longtext DEFAULT NULL,
  `starpage` int(11) DEFAULT 0,
  `level` int(11) DEFAULT 1,
  `parent` int(11) DEFAULT 0,
  `sort` int(11) DEFAULT 0,
  `active` int(11) DEFAULT 1,
  `update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idRpg`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.route_page: ~0 rows (approximately)
/*!40000 ALTER TABLE `route_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `route_page` ENABLE KEYS */;

-- Dumping structure for table newcms.sales
DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `Sales_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Sales_Number` varchar(20) NOT NULL,
  `Sales_Date` datetime NOT NULL,
  `Customer_ID` varchar(20) NOT NULL,
  `Notes` varchar(50) DEFAULT NULL,
  `Total_Amount` double DEFAULT 0,
  `Total_Payment` double DEFAULT 0,
  `Total_Balance` double DEFAULT 0,
  `Discount_Type` char(1) DEFAULT NULL,
  `Discount_Percentage` double DEFAULT 0,
  `Discount_Amount` double DEFAULT 0,
  `Tax_Percentage` double DEFAULT 0,
  `Tax_Amount` double DEFAULT 0,
  `Tax_Description` varchar(50) DEFAULT NULL,
  `Final_Total_Amount` double DEFAULT 0,
  `Date_Added` datetime DEFAULT NULL,
  `Added_By` varchar(50) DEFAULT NULL,
  `Date_Updated` datetime DEFAULT NULL,
  `Updated_By` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Sales_ID`),
  UNIQUE KEY `NoFaktur` (`Sales_Number`),
  KEY `TCustomerTJual` (`Customer_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.sales: ~0 rows (approximately)
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;

-- Dumping structure for table newcms.sales_detail
DROP TABLE IF EXISTS `sales_detail`;
CREATE TABLE IF NOT EXISTS `sales_detail` (
  `Sales_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Sales_Number` varchar(20) NOT NULL,
  `Supplier_Number` varchar(20) NOT NULL,
  `Stock_Item` varchar(15) NOT NULL,
  `Sales_Quantity` double NOT NULL DEFAULT 0,
  `Purchasing_Price` double NOT NULL DEFAULT 0,
  `Sales_Price` double NOT NULL DEFAULT 0,
  `Sales_Total_Amount` double NOT NULL DEFAULT 0,
  PRIMARY KEY (`Sales_ID`),
  KEY `TBarangTDJual` (`Stock_Item`),
  KEY `TJualTDJual` (`Sales_Number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.sales_detail: ~0 rows (approximately)
/*!40000 ALTER TABLE `sales_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_detail` ENABLE KEYS */;

-- Dumping structure for table newcms.secrets
DROP TABLE IF EXISTS `secrets`;
CREATE TABLE IF NOT EXISTS `secrets` (
  `secretid` char(128) NOT NULL DEFAULT '',
  `userid` char(128) NOT NULL,
  `tokenusr` varchar(256) DEFAULT NULL,
  `hashusr` varchar(256) DEFAULT NULL,
  `keyusr` varchar(256) DEFAULT NULL,
  `ivusr` varchar(256) DEFAULT NULL,
  `codeusr` char(8) DEFAULT NULL,
  PRIMARY KEY (`secretid`),
  UNIQUE KEY `secretid` (`secretid`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.secrets: ~0 rows (approximately)
/*!40000 ALTER TABLE `secrets` DISABLE KEYS */;
/*!40000 ALTER TABLE `secrets` ENABLE KEYS */;

-- Dumping structure for table newcms.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `set_time` int(11) NOT NULL,
  `data` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `session_key` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.sessions: ~0 rows (approximately)
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

-- Dumping structure for table newcms.settings
DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `Option_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Option_Default` enum('Y','N') DEFAULT 'N',
  `Default_Theme` varchar(30) DEFAULT NULL,
  `Menu_Horizontal` enum('Y','N') DEFAULT 'Y',
  `Vertical_Menu_Width` int(3) DEFAULT 150,
  `Show_Border_Layout` enum('N','Y') DEFAULT 'Y',
  `Show_Shadow_Layout` enum('N','Y') DEFAULT 'Y',
  `Show_Announcement` enum('Y','N') DEFAULT 'N',
  `Demo_Mode` enum('N','Y') DEFAULT 'N',
  `Show_Page_Processing_Time` enum('Y','N') DEFAULT 'N',
  `Allow_User_Preferences` enum('N','Y') DEFAULT 'Y',
  `SMTP_Server` varchar(50) DEFAULT NULL,
  `SMTP_Server_Port` varchar(5) DEFAULT NULL,
  `SMTP_Server_Username` varchar(50) DEFAULT NULL,
  `SMTP_Server_Password` varchar(50) DEFAULT NULL,
  `Sender_Email` varchar(50) DEFAULT NULL,
  `Recipient_Email` varchar(50) DEFAULT NULL,
  `Use_Default_Locale` enum('Y','N') DEFAULT 'Y',
  `Default_Language` varchar(5) DEFAULT NULL,
  `Default_Timezone` varchar(50) DEFAULT NULL,
  `Default_Thousands_Separator` varchar(5) DEFAULT NULL,
  `Default_Decimal_Point` varchar(5) DEFAULT NULL,
  `Default_Currency_Symbol` varchar(10) DEFAULT NULL,
  `Default_Money_Thousands_Separator` varchar(5) DEFAULT NULL,
  `Default_Money_Decimal_Point` varchar(5) DEFAULT NULL,
  `Maintenance_Mode` enum('N','Y') DEFAULT 'N',
  `Maintenance_Finish_DateTime` datetime DEFAULT NULL,
  `Auto_Normal_After_Maintenance` enum('Y','N') DEFAULT 'Y',
  `Allow_User_To_Register` enum('Y','N') DEFAULT 'Y',
  `Suspend_New_User_Account` enum('N','Y') DEFAULT 'N',
  `User_Need_Activation_After_Registered` enum('Y','N') DEFAULT 'Y',
  `Show_Captcha_On_Registration_Page` enum('Y','N') DEFAULT 'N',
  `Show_Terms_And_Conditions_On_Registration_Page` enum('Y','N') DEFAULT 'Y',
  `Show_Captcha_On_Login_Page` enum('N','Y') DEFAULT 'N',
  `Show_Captcha_On_Forgot_Password_Page` enum('N','Y') DEFAULT 'N',
  `Show_Captcha_On_Change_Password_Page` enum('N','Y') DEFAULT 'N',
  `User_Auto_Login_After_Activation_Or_Registration` enum('Y','N') DEFAULT 'Y',
  `User_Auto_Logout_After_Idle_In_Minutes` int(3) DEFAULT 20,
  `User_Login_Maximum_Retry` int(3) DEFAULT 3,
  `User_Login_Retry_Lockout` int(3) DEFAULT 5,
  `Redirect_To_Last_Visited_Page_After_Login` enum('Y','N') DEFAULT 'Y',
  `Enable_Password_Expiry` enum('Y','N') DEFAULT 'Y',
  `Password_Expiry_In_Days` int(3) DEFAULT 90,
  `Show_Entire_Header` enum('Y','N') DEFAULT 'Y',
  `Logo_Width` int(3) DEFAULT 170,
  `Show_Site_Title_In_Header` enum('Y','N') DEFAULT 'Y',
  `Show_Current_User_In_Header` enum('Y','N') DEFAULT 'Y',
  `Text_Align_In_Header` enum('left','center','right') DEFAULT 'left',
  `Site_Title_Text_Style` enum('normal','capitalize','uppercase') DEFAULT 'normal',
  `Language_Selector_Visibility` enum('inheader','belowheader','hidethemall') DEFAULT 'inheader',
  `Language_Selector_Align` enum('autoadjust','left','center','right') DEFAULT 'autoadjust',
  `Show_Entire_Footer` enum('Y','N') DEFAULT 'Y',
  `Show_Text_In_Footer` enum('Y','N') DEFAULT 'Y',
  `Show_Back_To_Top_On_Footer` enum('N','Y') DEFAULT 'Y',
  `Show_Terms_And_Conditions_On_Footer` enum('Y','N') DEFAULT 'Y',
  `Show_About_Us_On_Footer` enum('N','Y') DEFAULT 'Y',
  `Pagination_Position` enum('1','2','3') DEFAULT '3',
  `Pagination_Style` enum('1','2') DEFAULT '2',
  `Selectable_Records_Per_Page` varchar(50) DEFAULT '1,2,3,5,10,15,20,50',
  `Selectable_Groups_Per_Page` varchar(50) DEFAULT '1,2,3,5,10',
  `Default_Record_Per_Page` int(3) DEFAULT 10,
  `Default_Group_Per_Page` int(3) DEFAULT 3,
  `Maximum_Selected_Records` int(3) DEFAULT 50,
  `Maximum_Selected_Groups` int(3) DEFAULT 50,
  `Show_PageNum_If_Record_Not_Over_Pagesize` enum('Y','N') DEFAULT 'Y',
  `Table_Width_Style` enum('1','2','3') DEFAULT '2' COMMENT '1 = Scroll, 2 = Normal, 3 = 100%',
  `Scroll_Table_Width` int(4) DEFAULT 800,
  `Scroll_Table_Height` int(4) DEFAULT 300,
  `Show_Record_Number_On_List_Page` enum('N','Y') DEFAULT 'Y',
  `Show_Empty_Table_On_List_Page` enum('N','Y') DEFAULT 'Y',
  `Search_Panel_Collapsed` enum('Y','N') DEFAULT 'Y',
  `Filter_Panel_Collapsed` enum('Y','N') DEFAULT 'Y',
  `Rows_Vertical_Align_Top` enum('N','Y') DEFAULT 'Y',
  `Show_Add_Success_Message` enum('N','Y') DEFAULT 'Y',
  `Show_Edit_Success_Message` enum('N','Y') DEFAULT 'Y',
  `jQuery_Auto_Hide_Success_Message` enum('N','Y') DEFAULT 'N',
  `Show_Record_Number_On_Detail_Preview` enum('N','Y') DEFAULT 'Y',
  `Show_Empty_Table_In_Detail_Preview` enum('N','Y') DEFAULT 'Y',
  `Detail_Preview_Table_Width` int(3) DEFAULT 100,
  `Password_Minimum_Length` int(2) DEFAULT 6,
  `Password_Maximum_Length` int(2) DEFAULT 20,
  `Password_Must_Comply_With_Minumum_Length` enum('N','Y') DEFAULT 'Y',
  `Password_Must_Comply_With_Maximum_Length` enum('N','Y') DEFAULT 'Y',
  `Password_Must_Contain_At_Least_One_Lower_Case` enum('N','Y') DEFAULT 'Y',
  `Password_Must_Contain_At_Least_One_Upper_Case` enum('N','Y') DEFAULT 'Y',
  `Password_Must_Contain_At_Least_One_Numeric` enum('N','Y') DEFAULT 'Y',
  `Password_Must_Contain_At_Least_One_Symbol` enum('N','Y') DEFAULT 'Y',
  `Password_Must_Be_Difference_Between_Old_And_New` enum('N','Y') DEFAULT 'Y',
  `Export_Record_Options` enum('selectedrecords','currentpage','allpages') DEFAULT 'selectedrecords',
  `Show_Record_Number_On_Exported_List_Page` enum('N','Y') DEFAULT 'Y',
  `Use_Table_Setting_For_Export_Field_Caption` enum('N','Y') DEFAULT 'Y',
  `Use_Table_Setting_For_Export_Original_Value` enum('N','Y') DEFAULT 'Y',
  `Font_Name` varchar(50) DEFAULT 'tahoma',
  `Font_Size` varchar(4) DEFAULT '11px',
  `Use_Javascript_Message` enum('1','0') DEFAULT '1',
  `Login_Window_Type` enum('popup','default') DEFAULT 'popup',
  `Forgot_Password_Window_Type` enum('popup','default') DEFAULT 'popup',
  `Change_Password_Window_Type` enum('popup','default') DEFAULT 'popup',
  `Registration_Window_Type` enum('popup','default') DEFAULT 'popup',
  `Reset_Password_Field_Options` enum('EmailOrUsername','Username','Email') DEFAULT 'EmailOrUsername',
  `Action_Button_Alignment` enum('Right','Left') DEFAULT 'Right',
  PRIMARY KEY (`Option_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.settings: ~0 rows (approximately)
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;

-- Dumping structure for table newcms.slideshow
DROP TABLE IF EXISTS `slideshow`;
CREATE TABLE IF NOT EXISTS `slideshow` (
  `idSld` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) COLLATE utf8_bin NOT NULL,
  `page_id` int(11) NOT NULL,
  `comment` char(150) COLLATE utf8_bin NOT NULL,
  `active` tinyint(1) NOT NULL,
  `create` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idSld`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table newcms.slideshow: ~0 rows (approximately)
/*!40000 ALTER TABLE `slideshow` DISABLE KEYS */;
/*!40000 ALTER TABLE `slideshow` ENABLE KEYS */;

-- Dumping structure for table newcms.slideshow_image
DROP TABLE IF EXISTS `slideshow_image`;
CREATE TABLE IF NOT EXISTS `slideshow_image` (
  `idImg` int(11) NOT NULL AUTO_INCREMENT,
  `slideshow_id` int(11) DEFAULT NULL,
  `title_image` char(60) COLLATE utf8_bin DEFAULT NULL,
  `description` int(100) DEFAULT NULL,
  `slide_image` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `btn_color` enum('primary','secondary','light','dark','info','success','warning','danger','gray') COLLATE utf8_bin DEFAULT 'primary',
  `btn_text` char(30) COLLATE utf8_bin DEFAULT NULL,
  `link` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`idImg`),
  KEY `SLD1` (`slideshow_id`),
  CONSTRAINT `SLD1` FOREIGN KEY (`slideshow_id`) REFERENCES `slideshow` (`idSld`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table newcms.slideshow_image: ~0 rows (approximately)
/*!40000 ALTER TABLE `slideshow_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `slideshow_image` ENABLE KEYS */;

-- Dumping structure for table newcms.social_link
DROP TABLE IF EXISTS `social_link`;
CREATE TABLE IF NOT EXISTS `social_link` (
  `social_name` varchar(20) DEFAULT NULL,
  `social_url` varchar(150) DEFAULT NULL,
  KEY `social_name` (`social_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.social_link: ~0 rows (approximately)
/*!40000 ALTER TABLE `social_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_link` ENABLE KEYS */;

-- Dumping structure for table newcms.stats_counter
DROP TABLE IF EXISTS `stats_counter`;
CREATE TABLE IF NOT EXISTS `stats_counter` (
  `Type` varchar(50) NOT NULL DEFAULT '',
  `Variable` varchar(50) NOT NULL DEFAULT '',
  `Counter` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`Type`,`Variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.stats_counter: ~0 rows (approximately)
/*!40000 ALTER TABLE `stats_counter` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_counter` ENABLE KEYS */;

-- Dumping structure for table newcms.stats_counterlog
DROP TABLE IF EXISTS `stats_counterlog`;
CREATE TABLE IF NOT EXISTS `stats_counterlog` (
  `IP_Address` varchar(50) NOT NULL DEFAULT '',
  `Hostname` varchar(50) DEFAULT NULL,
  `First_Visit` datetime NOT NULL,
  `Last_Visit` datetime NOT NULL,
  `Counter` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`IP_Address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.stats_counterlog: ~0 rows (approximately)
/*!40000 ALTER TABLE `stats_counterlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_counterlog` ENABLE KEYS */;

-- Dumping structure for table newcms.stats_date
DROP TABLE IF EXISTS `stats_date`;
CREATE TABLE IF NOT EXISTS `stats_date` (
  `Year` smallint(6) NOT NULL DEFAULT 0,
  `Month` tinyint(4) NOT NULL DEFAULT 0,
  `Date` tinyint(4) NOT NULL DEFAULT 0,
  `Hits` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Date`,`Month`,`Year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.stats_date: ~0 rows (approximately)
/*!40000 ALTER TABLE `stats_date` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_date` ENABLE KEYS */;

-- Dumping structure for table newcms.stats_hour
DROP TABLE IF EXISTS `stats_hour`;
CREATE TABLE IF NOT EXISTS `stats_hour` (
  `Year` smallint(6) NOT NULL DEFAULT 0,
  `Month` tinyint(4) NOT NULL DEFAULT 0,
  `Date` tinyint(4) NOT NULL DEFAULT 0,
  `Hour` tinyint(4) NOT NULL DEFAULT 0,
  `Hits` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Date`,`Hour`,`Month`,`Year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.stats_hour: ~0 rows (approximately)
/*!40000 ALTER TABLE `stats_hour` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_hour` ENABLE KEYS */;

-- Dumping structure for table newcms.stats_month
DROP TABLE IF EXISTS `stats_month`;
CREATE TABLE IF NOT EXISTS `stats_month` (
  `Year` smallint(6) NOT NULL DEFAULT 0,
  `Month` tinyint(4) NOT NULL DEFAULT 0,
  `Hits` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Year`,`Month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.stats_month: ~0 rows (approximately)
/*!40000 ALTER TABLE `stats_month` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_month` ENABLE KEYS */;

-- Dumping structure for table newcms.stats_year
DROP TABLE IF EXISTS `stats_year`;
CREATE TABLE IF NOT EXISTS `stats_year` (
  `Year` smallint(6) NOT NULL DEFAULT 0,
  `Hits` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.stats_year: ~0 rows (approximately)
/*!40000 ALTER TABLE `stats_year` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats_year` ENABLE KEYS */;

-- Dumping structure for table newcms.stock_categories
DROP TABLE IF EXISTS `stock_categories`;
CREATE TABLE IF NOT EXISTS `stock_categories` (
  `Category_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Category_Name` varchar(20) NOT NULL,
  PRIMARY KEY (`Category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.stock_categories: ~0 rows (approximately)
/*!40000 ALTER TABLE `stock_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_categories` ENABLE KEYS */;

-- Dumping structure for table newcms.stock_items
DROP TABLE IF EXISTS `stock_items`;
CREATE TABLE IF NOT EXISTS `stock_items` (
  `Stock_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Supplier_Number` varchar(20) NOT NULL,
  `Stock_Number` varchar(15) NOT NULL,
  `Stock_Name` varchar(50) NOT NULL,
  `Unit_Of_Measurement` varchar(20) NOT NULL,
  `Category` int(11) NOT NULL,
  `Purchasing_Price` double(20,0) NOT NULL DEFAULT 0,
  `Selling_Price` double(20,0) NOT NULL DEFAULT 0,
  `Notes` varchar(50) NOT NULL,
  `Quantity` double(20,0) NOT NULL DEFAULT 0,
  `Date_Added` datetime DEFAULT NULL,
  `Added_By` varchar(50) DEFAULT NULL,
  `Date_Updated` datetime DEFAULT NULL,
  `Updated_By` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Stock_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.stock_items: ~0 rows (approximately)
/*!40000 ALTER TABLE `stock_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_items` ENABLE KEYS */;

-- Dumping structure for table newcms.suppliers
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `Supplier_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Supplier_Number` varchar(20) NOT NULL,
  `Supplier_Name` varchar(50) NOT NULL,
  `Address` text NOT NULL,
  `City` varchar(20) NOT NULL,
  `Country` varchar(50) NOT NULL,
  `Contact_Person` varchar(50) NOT NULL,
  `Phone_Number` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Mobile_Number` varchar(50) NOT NULL,
  `Notes` text NOT NULL,
  `Balance` double DEFAULT 0,
  `Is_Stock_Available` enum('N','Y') NOT NULL DEFAULT 'N',
  `Date_Added` datetime DEFAULT NULL,
  `Added_By` varchar(50) DEFAULT NULL,
  `Date_Updated` datetime DEFAULT NULL,
  `Updated_By` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Supplier_ID`),
  UNIQUE KEY `KodeCust` (`Supplier_Number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.suppliers: ~0 rows (approximately)
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;

-- Dumping structure for table newcms.table_config
DROP TABLE IF EXISTS `table_config`;
CREATE TABLE IF NOT EXISTS `table_config` (
  `tcon_Id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` text DEFAULT NULL,
  PRIMARY KEY (`tcon_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.table_config: ~0 rows (approximately)
/*!40000 ALTER TABLE `table_config` DISABLE KEYS */;
INSERT INTO `table_config` (`tcon_Id`, `table_name`) VALUES
	(3, 'blocks,cols_set,plugins_app');
/*!40000 ALTER TABLE `table_config` ENABLE KEYS */;

-- Dumping structure for table newcms.table_queries
DROP TABLE IF EXISTS `table_queries`;
CREATE TABLE IF NOT EXISTS `table_queries` (
  `tque_Id` int(11) NOT NULL AUTO_INCREMENT,
  `name_table` varchar(50) DEFAULT NULL,
  `col_name` varchar(50) DEFAULT NULL,
  `col_type` varchar(50) DEFAULT NULL,
  `input_type` int(11) DEFAULT NULL,
  `joins` varchar(50) DEFAULT NULL,
  `j_table` varchar(50) DEFAULT NULL,
  `j_id` varchar(50) DEFAULT NULL,
  `j_value` varchar(50) DEFAULT NULL,
  `j_as` varchar(50) DEFAULT NULL,
  `query` varchar(250) DEFAULT NULL,
  `jvpos` int(11) DEFAULT NULL,
  PRIMARY KEY (`tque_Id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.table_queries: ~0 rows (approximately)
/*!40000 ALTER TABLE `table_queries` DISABLE KEYS */;
/*!40000 ALTER TABLE `table_queries` ENABLE KEYS */;

-- Dumping structure for table newcms.table_settings
DROP TABLE IF EXISTS `table_settings`;
CREATE TABLE IF NOT EXISTS `table_settings` (
  `IdTbset` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` text NOT NULL,
  `views_name` text NOT NULL,
  `adds_name` text NOT NULL,
  `updates_name` text NOT NULL,
  `deletes_name` text NOT NULL,
  `permits_name` text NOT NULL,
  PRIMARY KEY (`IdTbset`) USING BTREE,
  UNIQUE KEY `table_name` (`table_name`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.table_settings: ~0 rows (approximately)
/*!40000 ALTER TABLE `table_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `table_settings` ENABLE KEYS */;

-- Dumping structure for table newcms.templates
DROP TABLE IF EXISTS `templates`;
CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `templates` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.templates: ~0 rows (approximately)
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;

-- Dumping structure for table newcms.themes
DROP TABLE IF EXISTS `themes`;
CREATE TABLE IF NOT EXISTS `themes` (
  `Theme_ID` varchar(25) NOT NULL,
  `Theme_Name` varchar(25) NOT NULL,
  `Default` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`Theme_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.themes: ~0 rows (approximately)
/*!40000 ALTER TABLE `themes` DISABLE KEYS */;
/*!40000 ALTER TABLE `themes` ENABLE KEYS */;

-- Dumping structure for table newcms.timezone
DROP TABLE IF EXISTS `timezone`;
CREATE TABLE IF NOT EXISTS `timezone` (
  `Timezone` varchar(50) NOT NULL,
  `Default` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`Timezone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.timezone: ~0 rows (approximately)
/*!40000 ALTER TABLE `timezone` DISABLE KEYS */;
/*!40000 ALTER TABLE `timezone` ENABLE KEYS */;

-- Dumping structure for table newcms.tokens
DROP TABLE IF EXISTS `tokens`;
CREATE TABLE IF NOT EXISTS `tokens` (
  `tokenid` char(25) NOT NULL,
  `userid` char(128) NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`tokenid`),
  UNIQUE KEY `tokenid_UNIQUE` (`tokenid`),
  UNIQUE KEY `userid_UNIQUE` (`userid`),
  CONSTRAINT `userid_t` FOREIGN KEY (`userid`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.tokens: ~0 rows (approximately)
/*!40000 ALTER TABLE `tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `tokens` ENABLE KEYS */;

-- Dumping structure for table newcms.type_blocks
DROP TABLE IF EXISTS `type_blocks`;
CREATE TABLE IF NOT EXISTS `type_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_block` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.type_blocks: ~0 rows (approximately)
/*!40000 ALTER TABLE `type_blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `type_blocks` ENABLE KEYS */;

-- Dumping structure for table newcms.type_gallery
DROP TABLE IF EXISTS `type_gallery`;
CREATE TABLE IF NOT EXISTS `type_gallery` (
  `idTG` int(11) NOT NULL AUTO_INCREMENT,
  `type_gallery` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idTG`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.type_gallery: ~0 rows (approximately)
/*!40000 ALTER TABLE `type_gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `type_gallery` ENABLE KEYS */;

-- Dumping structure for table newcms.type_page
DROP TABLE IF EXISTS `type_page`;
CREATE TABLE IF NOT EXISTS `type_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_page` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.type_page: ~0 rows (approximately)
/*!40000 ALTER TABLE `type_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `type_page` ENABLE KEYS */;

-- Dumping structure for table newcms.unit_of_measurement
DROP TABLE IF EXISTS `unit_of_measurement`;
CREATE TABLE IF NOT EXISTS `unit_of_measurement` (
  `UOM_ID` varchar(10) NOT NULL,
  `UOM_Description` varchar(20) NOT NULL,
  PRIMARY KEY (`UOM_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.unit_of_measurement: ~0 rows (approximately)
/*!40000 ALTER TABLE `unit_of_measurement` DISABLE KEYS */;
/*!40000 ALTER TABLE `unit_of_measurement` ENABLE KEYS */;

-- Dumping structure for table newcms.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `idUser` char(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `ip` char(50) NOT NULL,
  `signup_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email_verified` varchar(128) DEFAULT NULL,
  `document_verified` int(11) NOT NULL DEFAULT 0,
  `mobile_verified` int(11) NOT NULL DEFAULT 0,
  `mkpin` char(6) NOT NULL,
  `create_user` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_user` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `ID_user` (`idUser`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  CONSTRAINT `FK_users_uverify` FOREIGN KEY (`idUser`) REFERENCES `uverify` (`iduv`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.users: ~1 rows (approximately)


-- Dumping structure for table newcms.users_mk
DROP TABLE IF EXISTS `users_mk`;
CREATE TABLE IF NOT EXISTS `users_mk` (
  `id` char(128) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `password_recovery` text DEFAULT NULL,
  `email_verified` int(11) DEFAULT NULL,
  `email_hash` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `signup_time` int(11) DEFAULT NULL,
  `document_verified` int(11) DEFAULT NULL,
  `document_1` text DEFAULT NULL,
  `document_2` text DEFAULT NULL,
  `mobile_verified` int(11) DEFAULT NULL,
  `mobile_number` text DEFAULT NULL,
  `mktoken` varchar(128) DEFAULT NULL,
  `mkkey` varchar(128) DEFAULT NULL,
  `mkhash` varchar(128) DEFAULT NULL,
  `mkpin` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `password` (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.users_mk: ~0 rows (approximately)
/*!40000 ALTER TABLE `users_mk` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_mk` ENABLE KEYS */;

-- Dumping structure for table newcms.users_permissions
DROP TABLE IF EXISTS `users_permissions`;
CREATE TABLE IF NOT EXISTS `users_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'General',
  `required` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.users_permissions: ~15 rows (approximately)
/*!40000 ALTER TABLE `users_permissions` DISABLE KEYS */;
INSERT INTO `users_permissions` (`id`, `name`, `description`, `category`, `required`) VALUES
	(1, 'Verify Users', 'Administration permission allowing for the verification of new users', 'Users', 1),
	(2, 'Delete Unverified Users', 'Administration permission allowing the deletion of unverified users', 'Users', 1),
	(3, 'Ban Users', 'Moderation permission allowing the banning of users', 'Users', 1),
	(4, 'Assign Roles to Users', 'Administration permission allowing the assignment of roles to users', 'Users', 1),
	(5, 'Assign Users to Roles', 'Administration permission allowing the assignment of users to roles', 'Roles', 1),
	(6, 'Create Roles', 'Administration permission allowing for the creation of new roles', 'Roles', 1),
	(7, 'Delete Roles', 'Administration permission allowing for the deletion of roles', 'Roles', 1),
	(8, 'Create Permissions', 'Administration permission allowing for the creation of new permissions', 'Permissions', 1),
	(9, 'Delete Permissions', 'Administration permission allowing for the deletion of permissions', 'Permissions', 1),
	(10, 'Assign Permissions to Roles', 'Administration permission allowing the assignment of permissions to roles', 'Roles', 1),
	(11, 'Edit Site Config', 'Administration permission allowing the editing of core site configuration (dangerous)', 'Administration', 1),
	(12, 'View Permissions', 'Administration permission allowing the viewing of all permissions', 'Permissions', 1),
	(13, 'View Roles', 'Administration permission allowing for the viewing of all roles', 'Roles', 1),
	(14, 'View Users', 'Administration permission allowing for the viewing of all users', 'Users', 1),
	(15, 'Delete Users', 'Administration permission allowing for the deletion of users', 'Users', 1);
/*!40000 ALTER TABLE `users_permissions` ENABLE KEYS */;

-- Dumping structure for table newcms.users_roles
DROP TABLE IF EXISTS `users_roles`;
CREATE TABLE IF NOT EXISTS `users_roles` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `default_role` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idRol`) USING BTREE,
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `default_role_UNIQUE` (`default_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.users_roles: ~4 rows (approximately)
/*!40000 ALTER TABLE `users_roles` DISABLE KEYS */;
INSERT INTO `users_roles` (`idRol`, `name`, `description`, `required`, `default_role`) VALUES
	(1, 'Super Admin', 'Master administrator of site', 1, 9),
	(2, 'Admin', 'Site administrator', 1, 5),
	(3, 'Manager', 'Manager content', 1, 3),
	(4, 'Stantard User', 'Default site role for standard users', 1, 1);
/*!40000 ALTER TABLE `users_roles` ENABLE KEYS */;

-- Dumping structure for table newcms.users_shop
DROP TABLE IF EXISTS `users_shop`;
CREATE TABLE IF NOT EXISTS `users_shop` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(65) NOT NULL,
  `password1` varchar(255) NOT NULL,
  `password2` varchar(255) NOT NULL,
  `firstname` char(60) NOT NULL,
  `lastname` char(60) NOT NULL,
  `who` char(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.users_shop: ~0 rows (approximately)
/*!40000 ALTER TABLE `users_shop` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_shop` ENABLE KEYS */;

-- Dumping structure for table newcms.users_sys
DROP TABLE IF EXISTS `users_sys`;
CREATE TABLE IF NOT EXISTS `users_sys` (
  `Username` varchar(65) NOT NULL,
  `Password` varchar(64) NOT NULL,
  `First_Name` varchar(60) DEFAULT NULL,
  `Last_Name` varchar(60) DEFAULT NULL,
  `Email` varchar(256) NOT NULL,
  `User_Level` int(11) DEFAULT NULL,
  `Report_To` int(11) DEFAULT NULL,
  `Activated` enum('N','Y') NOT NULL DEFAULT 'N',
  `Locked` enum('Y','N') DEFAULT 'N',
  `Profile` text DEFAULT NULL,
  `Current_URL` text DEFAULT NULL,
  `Theme` varchar(30) DEFAULT 'theme-default.css',
  `Menu_Horizontal` enum('N','Y') DEFAULT 'Y',
  `Table_Width_Style` enum('3','2','1') DEFAULT '2' COMMENT '1 = Scroll, 2 = Normal, 3 = 100%',
  `Scroll_Table_Width` int(11) DEFAULT 1100,
  `Scroll_Table_Height` int(11) DEFAULT 300,
  `Rows_Vertical_Align_Top` enum('Y','N') DEFAULT 'Y',
  `Language` char(2) DEFAULT 'en',
  `Redirect_To_Last_Visited_Page_After_Login` enum('Y','N') DEFAULT 'N',
  `Font_Name` varchar(50) DEFAULT 'arial',
  `Font_Size` varchar(4) DEFAULT '13px',
  PRIMARY KEY (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.users_sys: ~0 rows (approximately)
/*!40000 ALTER TABLE `users_sys` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_sys` ENABLE KEYS */;

-- Dumping structure for table newcms.user_groups
DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_level` (`group_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.user_groups: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;

-- Dumping structure for table newcms.user_info
DROP TABLE IF EXISTS `user_info`;
CREATE TABLE IF NOT EXISTS `user_info` (
  `userid` char(128) NOT NULL,
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address1` varchar(45) DEFAULT NULL,
  `address2` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `bio` varchar(20000) DEFAULT NULL,
  `userimage` varchar(255) DEFAULT NULL,
  UNIQUE KEY `userid_UNIQUE` (`userid`),
  KEY `fk_userids_idx` (`userid`),
  CONSTRAINT `fk_userids` FOREIGN KEY (`userid`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.user_info: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_info` ENABLE KEYS */;

-- Dumping structure for table newcms.user_jail
DROP TABLE IF EXISTS `user_jail`;
CREATE TABLE IF NOT EXISTS `user_jail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` char(128) NOT NULL,
  `banned_hours` float NOT NULL DEFAULT 24,
  `reason` varchar(2000) DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  KEY `fk_userid_idx` (`user_id`),
  CONSTRAINT `fk_userid_jail` FOREIGN KEY (`user_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.user_jail: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_jail` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_jail` ENABLE KEYS */;

-- Dumping structure for table newcms.uverify
DROP TABLE IF EXISTS `uverify`;
CREATE TABLE IF NOT EXISTS `uverify` (
  `iduv` char(128) NOT NULL,
  `username` varchar(65) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `mktoken` varchar(256) NOT NULL,
  `mkkey` varchar(256) NOT NULL,
  `mkhash` varchar(256) NOT NULL,
  `mkpin` varchar(6) NOT NULL,
  `level` char(50) NOT NULL DEFAULT 'Guest',
  `recovery_phrase` varchar(128) DEFAULT NULL,
  `activation_code` varchar(128) DEFAULT NULL,
  `password_key` varchar(256) DEFAULT NULL,
  `pin_key` varchar(256) DEFAULT NULL,
  `rp_active` tinyint(1) NOT NULL DEFAULT 0,
  `is_activated` tinyint(1) NOT NULL DEFAULT 0,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `banned` tinyint(1) NOT NULL DEFAULT 1,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`iduv`),
  UNIQUE KEY `iduv` (`iduv`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.uverify: ~1 rows (approximately)


-- Dumping structure for table newcms.videos
DROP TABLE IF EXISTS `videos`;
CREATE TABLE IF NOT EXISTS `videos` (
  `idVd` int(11) NOT NULL AUTO_INCREMENT,
  `pageId` int(11) DEFAULT 0,
  `title` varchar(100) DEFAULT '',
  `image` varchar(150) DEFAULT '',
  `description_en` text DEFAULT NULL,
  `description_es` text DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `idlink` varchar(20) DEFAULT '#',
  `active` int(11) DEFAULT 0,
  PRIMARY KEY (`idVd`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.videos: ~0 rows (approximately)
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;

-- Dumping structure for table newcms.video_gal
DROP TABLE IF EXISTS `video_gal`;
CREATE TABLE IF NOT EXISTS `video_gal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `galId` int(11) DEFAULT 0,
  `title` varchar(100) DEFAULT '',
  `image` varchar(100) DEFAULT '',
  `description` text DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `idlink` varchar(20) DEFAULT '#',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.video_gal: ~0 rows (approximately)
/*!40000 ALTER TABLE `video_gal` DISABLE KEYS */;
/*!40000 ALTER TABLE `video_gal` ENABLE KEYS */;

-- Dumping structure for view newcms.view_purchases_details
DROP VIEW IF EXISTS `view_purchases_details`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_purchases_details` (
	`Purchase_ID` INT(11) NOT NULL,
	`Purchase_Number` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`Supplier_Number` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`Stock_Item` VARCHAR(15) NOT NULL COLLATE 'utf8_general_ci',
	`Purchasing_Quantity` DOUBLE(20,0) NOT NULL,
	`Purchasing_Price` DOUBLE(20,0) NOT NULL,
	`Selling_Price` DOUBLE(20,0) NOT NULL,
	`Purchasing_Total_Amount` DOUBLE(20,0) NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view newcms.view_purchases_outstandings
DROP VIEW IF EXISTS `view_purchases_outstandings`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_purchases_outstandings` (
	`Purchase_ID` INT(11) NOT NULL,
	`Purchase_Number` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`Purchase_Date` DATETIME NOT NULL,
	`Supplier_ID` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`Notes` VARCHAR(50) NULL COLLATE 'utf8_general_ci',
	`Total_Amount` DOUBLE(20,0) NULL,
	`Total_Payment` DOUBLE(20,0) NULL,
	`Total_Balance` DOUBLE(20,0) NULL
) ENGINE=MyISAM;

-- Dumping structure for view newcms.view_sales_details
DROP VIEW IF EXISTS `view_sales_details`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_sales_details` (
	`Sales_ID` INT(11) NOT NULL,
	`Sales_Number` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`Supplier_Number` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`Stock_Item` VARCHAR(15) NOT NULL COLLATE 'utf8_general_ci',
	`Sales_Quantity` DOUBLE NOT NULL,
	`Purchasing_Price` DOUBLE NOT NULL,
	`Sales_Price` DOUBLE NOT NULL,
	`Sales_Total_Amount` DOUBLE NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view newcms.view_sales_outstandings
DROP VIEW IF EXISTS `view_sales_outstandings`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_sales_outstandings` (
	`Sales_ID` INT(11) NOT NULL,
	`Sales_Number` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`Sales_Date` DATETIME NOT NULL,
	`Customer_ID` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`Notes` VARCHAR(50) NULL COLLATE 'utf8_general_ci',
	`Total_Amount` DOUBLE NULL,
	`Total_Payment` DOUBLE NULL,
	`Total_Balance` DOUBLE NULL,
	`Discount_Type` CHAR(1) NULL COLLATE 'utf8_general_ci',
	`Discount_Percentage` DOUBLE NULL,
	`Discount_Amount` DOUBLE NULL,
	`Tax_Percentage` DOUBLE NULL,
	`Tax_Description` VARCHAR(50) NULL COLLATE 'utf8_general_ci',
	`Final_Total_Amount` DOUBLE NULL
) ENGINE=MyISAM;

-- Dumping structure for table newcms.visitor
DROP TABLE IF EXISTS `visitor`;
CREATE TABLE IF NOT EXISTS `visitor` (
  `ip` varchar(15) NOT NULL,
  `timestamp` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.visitor: ~2 rows (approximately)
/*!40000 ALTER TABLE `visitor` DISABLE KEYS */;
INSERT INTO `visitor` (`ip`, `timestamp`) VALUES
	('127.0.0.1', '1629510472'),
	('::1', '1630197317');
/*!40000 ALTER TABLE `visitor` ENABLE KEYS */;

-- Dumping structure for table newcms.volunteer
DROP TABLE IF EXISTS `volunteer`;
CREATE TABLE IF NOT EXISTS `volunteer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `gender` enum('Woman','Male') DEFAULT NULL,
  `birthday` datetime DEFAULT NULL,
  `age` tinyint(2) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `social_media` varchar(50) DEFAULT NULL,
  `web_blog` varchar(150) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `address_line_2` varchar(150) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state_province_region` varchar(50) DEFAULT NULL,
  `zip_code` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `profession` varchar(50) DEFAULT NULL,
  `personal_interest` varchar(50) DEFAULT NULL,
  `skills` varchar(50) DEFAULT NULL,
  `allergies` varchar(50) DEFAULT NULL,
  `allergy_description` text DEFAULT NULL,
  `diseases` varchar(50) DEFAULT NULL,
  `disease_description` text DEFAULT NULL,
  `comments` varchar(50) DEFAULT NULL,
  `contact_person_name` varchar(50) DEFAULT NULL,
  `contact_person_phone` varchar(50) DEFAULT NULL,
  `contact_person_email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.volunteer: ~0 rows (approximately)
/*!40000 ALTER TABLE `volunteer` DISABLE KEYS */;
/*!40000 ALTER TABLE `volunteer` ENABLE KEYS */;

-- Dumping structure for view newcms.view_purchases_details
DROP VIEW IF EXISTS `view_purchases_details`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_purchases_details`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `view_purchases_details` AS SELECT
purchases_detail.Purchase_ID,
purchases_detail.Purchase_Number,
purchases_detail.Supplier_Number,
purchases_detail.Stock_Item,
purchases_detail.Purchasing_Quantity,
purchases_detail.Purchasing_Price,
purchases_detail.Selling_Price,
purchases_detail.Purchasing_Total_Amount
FROM
purchases_detail ;

-- Dumping structure for view newcms.view_purchases_outstandings
DROP VIEW IF EXISTS `view_purchases_outstandings`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_purchases_outstandings`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `view_purchases_outstandings` AS SELECT
purchases.Purchase_ID,
purchases.Purchase_Number,
purchases.Purchase_Date,
purchases.Supplier_ID,
purchases.Notes,
purchases.Total_Amount,
purchases.Total_Payment,
purchases.Total_Balance
FROM
purchases 
WHERE purchases.Total_Balance <> 0 ;

-- Dumping structure for view newcms.view_sales_details
DROP VIEW IF EXISTS `view_sales_details`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_sales_details`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `view_sales_details` AS SELECT
sales_detail.Sales_ID,
sales_detail.Sales_Number,
sales_detail.Supplier_Number,
sales_detail.Stock_Item,
sales_detail.Sales_Quantity,
sales_detail.Purchasing_Price,
sales_detail.Sales_Price,
sales_detail.Sales_Total_Amount
FROM
sales_detail ;

-- Dumping structure for view newcms.view_sales_outstandings
DROP VIEW IF EXISTS `view_sales_outstandings`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `view_sales_outstandings`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `view_sales_outstandings` AS SELECT
sales.Sales_ID,
sales.Sales_Number,
sales.Sales_Date,
sales.Customer_ID,
sales.Notes,
sales.Total_Amount,
sales.Total_Payment,
sales.Total_Balance,
sales.Discount_Type,
sales.Discount_Percentage,
sales.Discount_Amount,
sales.Tax_Percentage,
sales.Tax_Description,
sales.Final_Total_Amount
FROM
sales 
WHERE sales.Total_Balance <> 0 ;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
