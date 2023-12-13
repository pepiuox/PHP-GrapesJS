-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.6.0.6773
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table test_cms.actions_logs
DROP TABLE IF EXISTS `actions_logs`;
CREATE TABLE IF NOT EXISTS `actions_logs` (
  `actions_logs_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_login` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `actions` varchar(255) DEFAULT NULL,
  `timestamp_create` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`actions_logs_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.actions_logs: 0 rows
/*!40000 ALTER TABLE `actions_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `actions_logs` ENABLE KEYS */;

-- Dumping structure for table test_cms.active_guests
DROP TABLE IF EXISTS `active_guests`;
CREATE TABLE IF NOT EXISTS `active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.active_guests: ~0 rows (approximately)
INSERT INTO `active_guests` (`ip`, `timestamp`) VALUES
	('127.0.0.1', '2023-10-01 01:51:11');

-- Dumping structure for table test_cms.active_sessions
DROP TABLE IF EXISTS `active_sessions`;
CREATE TABLE IF NOT EXISTS `active_sessions` (
  `session` char(128) DEFAULT NULL,
  `date_session` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table test_cms.active_sessions: ~0 rows (approximately)

-- Dumping structure for table test_cms.active_users
DROP TABLE IF EXISTS `active_users`;
CREATE TABLE IF NOT EXISTS `active_users` (
  `username` varchar(65) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.active_users: ~0 rows (approximately)

-- Dumping structure for table test_cms.admins
DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `adminid` char(64) NOT NULL DEFAULT 'uuid_short();',
  `userid` char(128) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `superadmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.admins: ~0 rows (approximately)

-- Dumping structure for table test_cms.announcement
DROP TABLE IF EXISTS `announcement`;
CREATE TABLE IF NOT EXISTS `announcement` (
  `announcement_id` int(11) unsigned NOT NULL,
  `is_active` enum('N','Y') NOT NULL DEFAULT 'N',
  `topic` varchar(50) NOT NULL,
  `message` mediumtext NOT NULL,
  `date_lastupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `language` char(2) NOT NULL DEFAULT 'en',
  `auto_publish` enum('Y','N') DEFAULT 'N',
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(200) DEFAULT NULL,
  `translated_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.announcement: ~0 rows (approximately)

-- Dumping structure for table test_cms.articles
DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(250) DEFAULT NULL,
  `link` char(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.articles: ~0 rows (approximately)

-- Dumping structure for table test_cms.auth_tokens
DROP TABLE IF EXISTS `auth_tokens`;
CREATE TABLE IF NOT EXISTS `auth_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) NOT NULL,
  `auth_type` varchar(255) NOT NULL,
  `selector` text NOT NULL,
  `token` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table test_cms.auth_tokens: ~0 rows (approximately)

-- Dumping structure for table test_cms.banned_users
DROP TABLE IF EXISTS `banned_users`;
CREATE TABLE IF NOT EXISTS `banned_users` (
  `user_id` char(128) NOT NULL,
  `banned_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `banned_hours` float NOT NULL,
  `hours_remaining` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.banned_users: ~0 rows (approximately)

-- Dumping structure for table test_cms.blacklist_ip
DROP TABLE IF EXISTS `blacklist_ip`;
CREATE TABLE IF NOT EXISTS `blacklist_ip` (
  `blacklist_ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `timestamp_create` datetime DEFAULT NULL,
  PRIMARY KEY (`blacklist_ip_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.blacklist_ip: 0 rows
/*!40000 ALTER TABLE `blacklist_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `blacklist_ip` ENABLE KEYS */;

-- Dumping structure for table test_cms.blocks
DROP TABLE IF EXISTS `blocks`;
CREATE TABLE IF NOT EXISTS `blocks` (
  `idB` int(11) NOT NULL AUTO_INCREMENT,
  `blockId` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `pageId` int(11) DEFAULT NULL,
  PRIMARY KEY (`idB`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.blocks: ~0 rows (approximately)

-- Dumping structure for table test_cms.blocks_content
DROP TABLE IF EXISTS `blocks_content`;
CREATE TABLE IF NOT EXISTS `blocks_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blockId` int(11) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.blocks_content: ~0 rows (approximately)

-- Dumping structure for table test_cms.blog_categories
DROP TABLE IF EXISTS `blog_categories`;
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` char(50) DEFAULT NULL,
  `description` char(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.blog_categories: ~0 rows (approximately)

-- Dumping structure for table test_cms.blog_posts
DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `style` longtext DEFAULT NULL,
  `keyword` char(150) DEFAULT NULL,
  `classification` char(150) DEFAULT NULL,
  `description` char(150) DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `menu` int(11) DEFAULT NULL,
  `hidden_blog` tinyint(1) DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  `date_posted` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.blog_posts: ~0 rows (approximately)

-- Dumping structure for table test_cms.blog_post_tags
DROP TABLE IF EXISTS `blog_post_tags`;
CREATE TABLE IF NOT EXISTS `blog_post_tags` (
  `blog_post_id` int(11) NOT NULL,
  `tag_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.blog_post_tags: ~0 rows (approximately)

-- Dumping structure for table test_cms.breadcrumblinks
DROP TABLE IF EXISTS `breadcrumblinks`;
CREATE TABLE IF NOT EXISTS `breadcrumblinks` (
  `page_title` varchar(100) NOT NULL,
  `page_url` varchar(100) NOT NULL,
  `lft` int(4) NOT NULL,
  `rgt` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.breadcrumblinks: ~0 rows (approximately)

-- Dumping structure for table test_cms.carousel_picture
DROP TABLE IF EXISTS `carousel_picture`;
CREATE TABLE IF NOT EXISTS `carousel_picture` (
  `carousel_picture_id` int(11) NOT NULL AUTO_INCREMENT,
  `carousel_widget_id` int(11) DEFAULT NULL,
  `file_upload` varchar(255) DEFAULT NULL,
  `photo_url` varchar(512) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `arrange` int(11) DEFAULT NULL,
  `carousel_type` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `timestamp_create` datetime DEFAULT NULL,
  `timestamp_update` datetime DEFAULT NULL,
  PRIMARY KEY (`carousel_picture_id`) USING BTREE,
  KEY `carousel_widget_id` (`carousel_widget_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.carousel_picture: 0 rows
/*!40000 ALTER TABLE `carousel_picture` DISABLE KEYS */;
/*!40000 ALTER TABLE `carousel_picture` ENABLE KEYS */;

-- Dumping structure for table test_cms.carousel_widget
DROP TABLE IF EXISTS `carousel_widget`;
CREATE TABLE IF NOT EXISTS `carousel_widget` (
  `carousel_widget_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `custom_temp_active` int(11) DEFAULT NULL,
  `custom_template` text DEFAULT NULL,
  `timestamp_create` datetime DEFAULT current_timestamp(),
  `timestamp_update` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`carousel_widget_id`) USING BTREE,
  KEY `carousel_widget_id` (`carousel_widget_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.carousel_widget: 0 rows
/*!40000 ALTER TABLE `carousel_widget` DISABLE KEYS */;
/*!40000 ALTER TABLE `carousel_widget` ENABLE KEYS */;

-- Dumping structure for table test_cms.categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.categories: ~0 rows (approximately)
INSERT INTO `categories` (`categoryId`, `category_name`, `description`) VALUES
	(1, 'Media', 'Videos and photos');

-- Dumping structure for table test_cms.comment
DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL,
  `message` varchar(250) DEFAULT NULL,
  `date` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.comment: ~0 rows (approximately)

-- Dumping structure for table test_cms.cookies
DROP TABLE IF EXISTS `cookies`;
CREATE TABLE IF NOT EXISTS `cookies` (
  `cookieid` char(23) NOT NULL,
  `userid` char(128) NOT NULL,
  `tokenid` char(25) NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.cookies: ~0 rows (approximately)

-- Dumping structure for table test_cms.counter
DROP TABLE IF EXISTS `counter`;
CREATE TABLE IF NOT EXISTS `counter` (
  `counter` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.counter: ~0 rows (approximately)
INSERT INTO `counter` (`counter`) VALUES
	(8);

-- Dumping structure for table test_cms.countries
DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.countries: ~0 rows (approximately)

-- Dumping structure for table test_cms.currency
DROP TABLE IF EXISTS `currency`;
CREATE TABLE IF NOT EXISTS `currency` (
  `id` int(11) NOT NULL,
  `coin` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `symbol` varchar(50) DEFAULT NULL,
  `rating` varchar(50) DEFAULT NULL,
  `total_supply` varchar(50) DEFAULT NULL,
  `max_supply` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.currency: ~0 rows (approximately)

-- Dumping structure for table test_cms.datos_personales
DROP TABLE IF EXISTS `datos_personales`;
CREATE TABLE IF NOT EXISTS `datos_personales` (
  `idd` char(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mkhash` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `edad` tinyint(2) DEFAULT NULL,
  `tipo_figura` enum('Delgada','Delgada pechugona','Delgada potoncita','Esbelta','Esbelta pechugona','Esbelta potoncita','Curvilineo','Llenita') DEFAULT NULL,
  `estatura` varchar(50) DEFAULT NULL,
  `busto` varchar(50) DEFAULT NULL,
  `cintura` varchar(50) DEFAULT NULL,
  `caderas` varchar(50) DEFAULT NULL,
  `detalles_fisicos` varchar(250) DEFAULT NULL,
  `zonas` varchar(250) DEFAULT NULL,
  `citas` varchar(250) DEFAULT NULL,
  `salidas` varchar(250) DEFAULT NULL,
  `dias` varchar(250) DEFAULT NULL,
  `horarios` varchar(250) DEFAULT NULL,
  `descripcion_servicio` varchar(250) DEFAULT NULL,
  `servicios` varchar(250) DEFAULT NULL,
  `adicionales` varchar(250) DEFAULT NULL,
  `pack_videos` varchar(250) DEFAULT NULL,
  `otras_atenciones` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`idd`),
  UNIQUE KEY `idd` (`idd`),
  CONSTRAINT `FK1_idd` FOREIGN KEY (`idd`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table test_cms.datos_personales: ~0 rows (approximately)

-- Dumping structure for table test_cms.deleted_users
DROP TABLE IF EXISTS `deleted_users`;
CREATE TABLE IF NOT EXISTS `deleted_users` (
  `user_id` char(128) NOT NULL,
  `username` varchar(65) NOT NULL,
  `password` varchar(65) NOT NULL,
  `email` varchar(65) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `banned` tinyint(1) NOT NULL DEFAULT 0,
  `mod_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.deleted_users: ~0 rows (approximately)

-- Dumping structure for table test_cms.domains
DROP TABLE IF EXISTS `domains`;
CREATE TABLE IF NOT EXISTS `domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_name` char(50) DEFAULT NULL,
  `short_url` char(50) DEFAULT NULL,
  `fully_url` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_name` (`domain_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table test_cms.domains: ~0 rows (approximately)

-- Dumping structure for table test_cms.eventlog
DROP TABLE IF EXISTS `eventlog`;
CREATE TABLE IF NOT EXISTS `eventlog` (
  `id` bigint(20) unsigned NOT NULL,
  `event` varchar(200) NOT NULL,
  `eventRowIdOrRef` varchar(20) DEFAULT NULL,
  `eventDesc` text DEFAULT NULL,
  `eventTable` varchar(20) DEFAULT NULL,
  `staffInCharge` bigint(20) unsigned NOT NULL,
  `eventTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.eventlog: ~0 rows (approximately)

-- Dumping structure for table test_cms.faq
DROP TABLE IF EXISTS `faq`;
CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `created` timestamp NULL DEFAULT current_timestamp(),
  `updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.faq: ~0 rows (approximately)

-- Dumping structure for table test_cms.follow
DROP TABLE IF EXISTS `follow`;
CREATE TABLE IF NOT EXISTS `follow` (
  `follow_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.follow: ~0 rows (approximately)

-- Dumping structure for table test_cms.forgot_pass
DROP TABLE IF EXISTS `forgot_pass`;
CREATE TABLE IF NOT EXISTS `forgot_pass` (
  `idFgp` int(11) NOT NULL,
  `username` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `password_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `create` timestamp NULL DEFAULT current_timestamp(),
  `expire` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.forgot_pass: ~0 rows (approximately)

-- Dumping structure for table test_cms.forgot_pin
DROP TABLE IF EXISTS `forgot_pin`;
CREATE TABLE IF NOT EXISTS `forgot_pin` (
  `idFgp` int(11) NOT NULL,
  `username` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `pin_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `create` timestamp NULL DEFAULT current_timestamp(),
  `expire` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.forgot_pin: ~0 rows (approximately)

-- Dumping structure for table test_cms.form_main
DROP TABLE IF EXISTS `form_main`;
CREATE TABLE IF NOT EXISTS `form_main` (
  `form_main_id` int(11) NOT NULL AUTO_INCREMENT,
  `form_name` varchar(255) DEFAULT NULL,
  `form_enctype` varchar(255) DEFAULT NULL,
  `form_method` varchar(255) DEFAULT NULL,
  `success_txt` varchar(255) DEFAULT NULL,
  `captchaerror_txt` varchar(255) DEFAULT NULL,
  `error_txt` varchar(255) DEFAULT NULL,
  `sendmail` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `send_to_visitor` int(11) DEFAULT NULL,
  `email_field_id` int(11) DEFAULT NULL,
  `visitor_subject` varchar(255) DEFAULT NULL,
  `visitor_body` text DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `captcha` int(11) DEFAULT NULL,
  `save_to_db` int(11) DEFAULT NULL,
  `dont_repeat_field` varchar(255) DEFAULT NULL,
  `repeat_txt` varchar(255) DEFAULT NULL,
  `timestamp_create` datetime DEFAULT NULL,
  `timestamp_update` datetime DEFAULT NULL,
  PRIMARY KEY (`form_main_id`) USING BTREE,
  KEY `form_name` (`form_name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.form_main: 0 rows
/*!40000 ALTER TABLE `form_main` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_main` ENABLE KEYS */;

-- Dumping structure for table test_cms.galleries
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.galleries: ~0 rows (approximately)

-- Dumping structure for table test_cms.gallery
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `link` varchar(100) DEFAULT '#',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.gallery: ~0 rows (approximately)

-- Dumping structure for table test_cms.gallery_config
DROP TABLE IF EXISTS `gallery_config`;
CREATE TABLE IF NOT EXISTS `gallery_config` (
  `gallery_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_sort` varchar(255) DEFAULT NULL,
  `user_admin_id` int(11) DEFAULT NULL,
  `timestamp_update` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`gallery_config_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.gallery_config: 0 rows
/*!40000 ALTER TABLE `gallery_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery_config` ENABLE KEYS */;

-- Dumping structure for table test_cms.gallery_db
DROP TABLE IF EXISTS `gallery_db`;
CREATE TABLE IF NOT EXISTS `gallery_db` (
  `gallery_db_id` int(11) NOT NULL AUTO_INCREMENT,
  `album_name` varchar(255) DEFAULT NULL,
  `url_rewrite` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `short_desc` text DEFAULT NULL,
  `lang_iso` varchar(10) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `arrange` int(11) DEFAULT NULL,
  `user_groups_idS` text DEFAULT NULL,
  `timestamp_create` datetime DEFAULT current_timestamp(),
  `timestamp_update` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`gallery_db_id`) USING BTREE,
  KEY `url_rewrite` (`url_rewrite`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.gallery_db: 0 rows
/*!40000 ALTER TABLE `gallery_db` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery_db` ENABLE KEYS */;

-- Dumping structure for table test_cms.gateways
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.gateways: ~0 rows (approximately)

-- Dumping structure for table test_cms.gateways_fields
DROP TABLE IF EXISTS `gateways_fields`;
CREATE TABLE IF NOT EXISTS `gateways_fields` (
  `id` int(11) NOT NULL,
  `gateway_id` int(11) DEFAULT NULL,
  `field_name` varchar(255) DEFAULT NULL,
  `field_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.gateways_fields: ~0 rows (approximately)

-- Dumping structure for table test_cms.help
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
  `Last_Updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.help: ~0 rows (approximately)

-- Dumping structure for table test_cms.help_categories
DROP TABLE IF EXISTS `help_categories`;
CREATE TABLE IF NOT EXISTS `help_categories` (
  `category_id` int(11) NOT NULL,
  `language` char(2) NOT NULL,
  `category_description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.help_categories: ~0 rows (approximately)

-- Dumping structure for table test_cms.history
DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `id` int(11) NOT NULL,
  `user_id` char(128) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.history: ~0 rows (approximately)

-- Dumping structure for table test_cms.image_gal
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.image_gal: ~0 rows (approximately)

-- Dumping structure for table test_cms.ip
DROP TABLE IF EXISTS `ip`;
CREATE TABLE IF NOT EXISTS `ip` (
  `id_session` char(128) DEFAULT NULL,
  `user_data` char(64) NOT NULL,
  `address` char(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.ip: ~8 rows (approximately)
INSERT INTO `ip` (`id_session`, `user_data`, `address`, `timestamp`) VALUES
	('0592ad6e0a352cd36b91970f6a7a9dc98d45485d', 'pepiuox@contact.net', '127.0.0.1', '2021-09-14 05:29:43'),
	('22a0fbc2d9a8667bea2a38d69a6e0f41cff9a798', 'contatct@pepiuox.net', '127.0.0.1', '2021-09-16 04:21:49'),
	('b1ed8551a80fa6c03457119e068b536f4d92b271', 'contact@ppiuox.net', '127.0.0.1', '2021-10-22 05:10:53'),
	('b1ed8551a80fa6c03457119e068b536f4d92b271', 'pepiuox@pepiuox.net', '127.0.0.1', '2021-10-22 05:11:27'),
	('424e04c9623c18a4de597bd01327b604a0b96491', 'contact@pepiuox.net', '127.0.0.1', '2022-06-19 04:49:21'),
	('3ab3627de5edd26396fee60a9f5ffc3d14e987c2', 'contact@labemotion.net', '127.0.0.1', '2022-09-02 22:24:16'),
	('3ab3627de5edd26396fee60a9f5ffc3d14e987c2', 'contact@labemotion.net', '127.0.0.1', '2022-09-02 23:53:41'),
	('3ab3627de5edd26396fee60a9f5ffc3d14e987c2', 'contact@pepiuox.net', '127.0.0.1', '2022-09-02 23:54:13');

-- Dumping structure for table test_cms.items
DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.items: ~0 rows (approximately)

-- Dumping structure for table test_cms.languages
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.languages: ~0 rows (approximately)

-- Dumping structure for table test_cms.link_statistic
DROP TABLE IF EXISTS `link_statistic`;
CREATE TABLE IF NOT EXISTS `link_statistic` (
  `link_statistic_id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `timestamp_create` datetime DEFAULT NULL,
  PRIMARY KEY (`link_statistic_id`) USING BTREE,
  KEY `link` (`link`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.link_statistic: 0 rows
/*!40000 ALTER TABLE `link_statistic` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_statistic` ENABLE KEYS */;

-- Dumping structure for table test_cms.link_stat_mgt
DROP TABLE IF EXISTS `link_stat_mgt`;
CREATE TABLE IF NOT EXISTS `link_stat_mgt` (
  `link_stat_mgt_id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `timestamp_create` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`link_stat_mgt_id`) USING BTREE,
  KEY `url` (`url`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.link_stat_mgt: 0 rows
/*!40000 ALTER TABLE `link_stat_mgt` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_stat_mgt` ENABLE KEYS */;

-- Dumping structure for table test_cms.lk_sess
DROP TABLE IF EXISTS `lk_sess`;
CREATE TABLE IF NOT EXISTS `lk_sess` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.lk_sess: ~0 rows (approximately)

-- Dumping structure for table test_cms.login_attempts
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id_session` varchar(128) DEFAULT NULL,
  `user_data` varchar(65) DEFAULT NULL,
  `ip_address` varchar(20) NOT NULL,
  `attempts` int(11) NOT NULL,
  `lastlogin` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.login_attempts: ~0 rows (approximately)
INSERT INTO `login_attempts` (`id_session`, `user_data`, `ip_address`, `attempts`, `lastlogin`) VALUES
	('3ab3627de5edd26396fee60a9f5ffc3d14e987c2', 'contact@pepiuox.net', '127.0.0.1', 1, '2022-09-02 23:54:13');

-- Dumping structure for table test_cms.mail
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.mail: ~0 rows (approximately)

-- Dumping structure for table test_cms.mail_log
DROP TABLE IF EXISTS `mail_log`;
CREATE TABLE IF NOT EXISTS `mail_log` (
  `id` int(11) NOT NULL,
  `type` varchar(45) NOT NULL DEFAULT 'generic',
  `status` varchar(45) DEFAULT NULL,
  `recipient` varchar(5000) DEFAULT NULL,
  `response` mediumtext NOT NULL,
  `isread` tinyint(1) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.mail_log: ~0 rows (approximately)

-- Dumping structure for table test_cms.members
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.members: ~0 rows (approximately)

-- Dumping structure for table test_cms.member_info
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
  `bio` varchar(2000) DEFAULT NULL,
  `userimage` varchar(255) DEFAULT NULL,
  UNIQUE KEY `userid_UNIQUE` (`userid`),
  KEY `fk_userid_idx` (`userid`),
  CONSTRAINT `fk_userid` FOREIGN KEY (`userid`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.member_info: ~0 rows (approximately)

-- Dumping structure for table test_cms.member_roles
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.member_roles: ~0 rows (approximately)

-- Dumping structure for table test_cms.menu
DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `idMenu` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `title_page` varchar(100) DEFAULT NULL,
  `link_page` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT 0,
  PRIMARY KEY (`idMenu`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.menu: ~6 rows (approximately)
INSERT INTO `menu` (`idMenu`, `sort`, `page_id`, `title_page`, `link_page`, `parent_id`) VALUES
	(1, NULL, 1, 'Home', 'home', 0),
	(2, NULL, 2, 'Abous Us', 'abous-us', 0),
	(3, NULL, 3, 'Shaman', 'shaman', 2),
	(4, NULL, 4, 'Retreats', 'retreats', 0),
	(5, NULL, 5, 'Diets', 'diets', 0),
	(6, NULL, 6, 'Ayahuasca', 'ayahuasca', 0);

-- Dumping structure for table test_cms.menu_options
DROP TABLE IF EXISTS `menu_options`;
CREATE TABLE IF NOT EXISTS `menu_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` char(50) NOT NULL,
  `fluid` enum('Yes','No') DEFAULT NULL,
  `placement` enum('top','bottom','sticky-top') DEFAULT NULL,
  `aligment` enum('start','center','end') DEFAULT NULL,
  `background` enum('primary','secondary','light','dark','info','success','warning','danger') DEFAULT NULL,
  `color` enum('light','dark') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_menu` (`id_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table test_cms.menu_options: ~2 rows (approximately)
INSERT INTO `menu_options` (`id`, `id_menu`, `fluid`, `placement`, `aligment`, `background`, `color`) VALUES
	(1, 'main_navbar', 'Yes', 'top', 'start', 'secondary', 'dark'),
	(2, 'main_menu', 'Yes', 'top', 'end', 'dark', 'dark');

-- Dumping structure for table test_cms.multimedia_gal
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.multimedia_gal: ~0 rows (approximately)

-- Dumping structure for table test_cms.page
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
  `type` enum('Design','File','Link') NOT NULL,
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
  `created` timestamp NULL DEFAULT current_timestamp(),
  `updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.page: ~6 rows (approximately)
INSERT INTO `page` (`id`, `language`, `position`, `title`, `link`, `url`, `keyword`, `classification`, `description`, `image`, `type`, `menu`, `hidden_page`, `path_file`, `script_name`, `template`, `base_template`, `content`, `style`, `startpage`, `level`, `parent`, `sort`, `active`, `created`, `updated`) VALUES
	(1, 1, 0, 'Home', 'home', NULL, 'Home', 'Home', 'Home', '29853.jpg', 'Design', 1, 0, NULL, NULL, NULL, NULL, '&amp;lt;body&amp;gt;&amp;lt;div id=&amp;quot;i5zn&amp;quot;&amp;gt;&amp;lt;img src=&amp;quot;../uploads/circulodepiccha-slideshow-1.jpg&amp;quot; id=&amp;quot;irjt&amp;quot; class=&amp;quot;img-fluid&amp;quot;/&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;section id=&amp;quot;in4g&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;py-5 about-area about-two&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;izwf&amp;quot; class=&amp;quot;py-5 text-center&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i1z3m&amp;quot; class=&amp;quot;gjs-row&amp;quot;&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i155&amp;quot; class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;ie1n&amp;quot; class=&amp;quot;mx-auto col-lg-5 col-md-7 col-10&amp;quot;&amp;gt;&amp;lt;h1 id=&amp;quot;i9ph&amp;quot; draggable=&amp;quot;true&amp;quot;&amp;gt;O my friend&amp;lt;/h1&amp;gt;&amp;lt;p id=&amp;quot;iji2h&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;mb-3&amp;quot;&amp;gt;Como estan las cosas ahora&amp;lt;br type=&amp;quot;_moz&amp;quot;/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;a id=&amp;quot;imbxq&amp;quot; draggable=&amp;quot;true&amp;quot; role=&amp;quot;button&amp;quot; data-cke-saved-href=&amp;quot;#&amp;quot; href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-primary&amp;quot;&amp;gt;Act now&amp;lt;/a&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div id=&amp;quot;id1p&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;igm3&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;id3l&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;col-lg-12&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;ik8n&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;about-title text-center&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i6w9&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;section-title&amp;quot;&amp;gt;&amp;lt;h2 id=&amp;quot;iqk1&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;fw-bold&amp;quot;&amp;gt;Our Key Features\n            &amp;lt;/h2&amp;gt;&amp;lt;p id=&amp;quot;ij0l4&amp;quot; draggable=&amp;quot;true&amp;quot;&amp;gt;\n              Lorem ipsum dolor sit amet, consectetur adipisicing elit sed do\n              eiusmod tempor incididunt ut labore et dolore magna aliqua.\n            &amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;!-- row --&amp;gt;&amp;lt;div id=&amp;quot;izhsl&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;row justify-content-center&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;iafkz&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;col-xl-5 col-lg-6 col-md-8 col-sm-11&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;ied4f&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;single-features-one-items text-center&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;ihaol&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;features-image&amp;quot;&amp;gt;&amp;lt;img src=&amp;quot;http://localhost:130/assets/images/about/about-02/viral.svg&amp;quot; id=&amp;quot;i8mfp&amp;quot; draggable=&amp;quot;true&amp;quot; alt=&amp;quot;image&amp;quot; class=&amp;quot;img-fluid&amp;quot;/&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div id=&amp;quot;iunmf&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;features-content&amp;quot;&amp;gt;&amp;lt;h3 id=&amp;quot;iji93&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;features-title&amp;quot;&amp;gt;Social Media Marketin\n            &amp;lt;/h3&amp;gt;&amp;lt;p id=&amp;quot;iomun&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;text&amp;quot;&amp;gt;\n              Lorem ipsum dolor sit amet, consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et\n              dolore magna aliqua.\n            &amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;!-- single features one items --&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div id=&amp;quot;iannt&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;col-xl-5 col-lg-6 col-md-8 col-sm-11&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i8akp&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;single-features-one-items text-center&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i7cqu&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;features-image&amp;quot;&amp;gt;&amp;lt;img src=&amp;quot;http://localhost:130/assets/images/about/about-02/remote-team.svg&amp;quot; id=&amp;quot;iyb2j&amp;quot; draggable=&amp;quot;true&amp;quot; alt=&amp;quot;image&amp;quot; class=&amp;quot;img-fluid&amp;quot;/&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div id=&amp;quot;ix8ce&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;features-content&amp;quot;&amp;gt;&amp;lt;h3 id=&amp;quot;id1nu&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;features-title&amp;quot;&amp;gt;Dedicated Team\n            &amp;lt;/h3&amp;gt;&amp;lt;p id=&amp;quot;iloaj&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;text&amp;quot;&amp;gt;\n              Lorem ipsum dolor sit amet, consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et\n              dolore magna aliqua.\n            &amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;!-- single features one items --&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;!-- row --&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;!-- container --&amp;gt;&amp;lt;/section&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin:0;}.gjs-row{display:flex;justify-content:flex-start;align-items:stretch;flex-wrap:nowrap;padding:10px;}@media (max-width: 768px){.gjs-row{flex-wrap:wrap;}}', 1, 1, 0, 0, 1, '2023-09-30 23:39:21', '2023-09-25 17:03:12'),
	(2, 1, 0, 'Abous Us', 'abous-us', NULL, 'Abous Us', 'Abous Us', 'Abous Us', 'logopao2.jpg', 'Design', 2, 0, NULL, NULL, NULL, NULL, '&amp;lt;body&amp;gt;&amp;lt;div class=&amp;quot;py-5 text-center text-primary h-100 align-items-center d-flex&amp;quot; id=&amp;quot;i6kb&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container py-5&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;mx-auto col-lg-8 col-md-10&amp;quot;&amp;gt;&amp;lt;h1 class=&amp;quot;display-3 mb-4&amp;quot;&amp;gt;A wonderful serenity&amp;lt;/h1&amp;gt;&amp;lt;p class=&amp;quot;lead mb-5&amp;quot;&amp;gt;Has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence.&amp;lt;/p&amp;gt;&amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg btn-primary mx-1&amp;quot;&amp;gt;Take me there&amp;lt;/a&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg mx-1 btn-outline-primary&amp;quot;&amp;gt;Let&amp;#039;s Go&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;py-5&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-md-12&amp;quot;&amp;gt;&amp;lt;h1&amp;gt;O my friend&amp;lt;/h1&amp;gt;&amp;lt;p class=&amp;quot;mb-4&amp;quot;&amp;gt;A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.\n                        I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.&amp;lt;/p&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-md-3 order-3 order-md-1&amp;quot;&amp;gt; &amp;lt;img src=&amp;quot;http://localhost:130/assets/images/img-placeholder-1.svg&amp;quot; class=&amp;quot;img-fluid d-block&amp;quot;/&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-md-6 col-8 d-flex flex-column justify-content-center p-3 order-1 order-md-2&amp;quot;&amp;gt;&amp;lt;h3&amp;gt;Mere tranquil existence&amp;lt;/h3&amp;gt;&amp;lt;p&amp;gt;I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.&amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-md-2 col-4 d-flex flex-column align-items-center justify-content-center order-2 order-md-2 p-3&amp;quot;&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-outline-primary mb-3&amp;quot;&amp;gt;Read more&amp;lt;/a&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-primary mb-3&amp;quot;&amp;gt;Main action&amp;lt;/a&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-link&amp;quot;&amp;gt;Link&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}#i6kb{background-image:linear-gradient(to bottom, rgba(0, 0, 0, .75), rgba(0, 0, 0, .75)), url(http://localhost:130/assets/images/cover-bubble-dark.svg);background-position:center center, center center;background-size:cover, cover;background-repeat:repeat, repeat;}', 0, 1, 0, 0, 1, '2023-09-30 23:39:21', '2023-06-02 03:59:09'),
	(3, 1, 0, 'Shaman', 'shaman', NULL, 'Shaman', 'Shaman', 'Shaman', NULL, 'Design', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, 0, 1, '2023-09-30 23:39:21', '2022-09-16 09:35:48'),
	(4, 1, 0, 'Retreats', 'retreats', NULL, 'Retreats', 'Retreats', 'Retreats', NULL, 'Design', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 0, 0, 1, '2023-09-30 23:39:21', '2022-11-15 02:46:52'),
	(5, 1, 0, 'Diets', 'diets', NULL, 'Diets', 'Diets', 'Diets', NULL, 'Design', 1, 0, NULL, NULL, NULL, NULL, '&amp;lt;body id=&amp;quot;ife5&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;icwnu&amp;quot; class=&amp;quot;py-5 text-center text-primary h-100 align-items-center d-flex&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container py-5&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;mx-auto col-lg-8 col-md-10&amp;quot;&amp;gt;&amp;lt;h1 class=&amp;quot;display-3 mb-4&amp;quot;&amp;gt;A wonderful serenity&amp;lt;/h1&amp;gt;&amp;lt;p class=&amp;quot;lead mb-5&amp;quot;&amp;gt;Has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence.&amp;lt;/p&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg btn-primary mx-1&amp;quot;&amp;gt;Take me there&amp;lt;/a&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg mx-1 btn-outline-primary&amp;quot;&amp;gt;Let&amp;#039;s Go&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;py-5 text-center&amp;quot; id=&amp;quot;i46e&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-md-8 mx-auto&amp;quot;&amp;gt;&amp;lt;p class=&amp;quot;mb-3&amp;quot;&amp;gt;&amp;quot;A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.&amp;quot;&amp;lt;/p&amp;gt;&amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-primary&amp;quot;&amp;gt;Act now!&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div id=&amp;quot;iycj1&amp;quot; class=&amp;quot;py-5 text-center&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i2m2s&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;bg-white p-4 col-10 mx-auto mx-md-0 col-lg-6&amp;quot;&amp;gt;&amp;lt;h1 id=&amp;quot;ipgac&amp;quot; draggable=&amp;quot;true&amp;quot;&amp;gt;I am so happy\n  &amp;lt;/h1&amp;gt;&amp;lt;p id=&amp;quot;itc5r&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;mb-4&amp;quot;&amp;gt;A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone.\n  &amp;lt;/p&amp;gt;&amp;lt;form method=&amp;quot;get&amp;quot; id=&amp;quot;ihzng&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;form-inline d-flex justify-content-center&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;idi6t&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;input-group&amp;quot;&amp;gt;&amp;lt;input type=&amp;quot;text&amp;quot; name=&amp;quot;name&amp;quot; id=&amp;quot;name&amp;quot; draggable=&amp;quot;true&amp;quot; placeholder=&amp;quot;Your name&amp;quot; autocomplete=&amp;quot;off&amp;quot; class=&amp;quot;form-control&amp;quot;/&amp;gt;&amp;lt;input type=&amp;quot;email&amp;quot; id=&amp;quot;form6&amp;quot; draggable=&amp;quot;true&amp;quot; placeholder=&amp;quot;Your email&amp;quot; autocomplete=&amp;quot;off&amp;quot; class=&amp;quot;form-control&amp;quot;/&amp;gt;&amp;lt;div id=&amp;quot;iot1p&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;input-group-append&amp;quot;&amp;gt;&amp;lt;button type=&amp;quot;button&amp;quot; id=&amp;quot;inbmo&amp;quot; draggable=&amp;quot;true&amp;quot; autocomplete=&amp;quot;off&amp;quot; class=&amp;quot;btn btn-primary&amp;quot;&amp;gt;Subscribe&amp;lt;/button&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/form&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;py-3&amp;quot; id=&amp;quot;ikc1t&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-lg-4 col-6 p-3&amp;quot;&amp;gt; &amp;lt;i class=&amp;quot;d-block fa fa-circle-o fa-5x text-primary&amp;quot;&amp;gt;&amp;lt;/i&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-lg-4 col-6 p-3&amp;quot;&amp;gt;&amp;lt;p&amp;gt; &amp;lt;a href=&amp;quot;https://goo.gl/maps/AUq7b9W7yYJ2&amp;quot; target=&amp;quot;_blank&amp;quot;&amp;gt; Fake street, 100\n                            &amp;lt;br/&amp;gt;NYC - 20179, USA&amp;lt;/a&amp;gt; &amp;lt;/p&amp;gt;&amp;lt;p&amp;gt; &amp;lt;a href=&amp;quot;tel:+246 - 542 550 5462&amp;quot;&amp;gt;+1 - 256 845 87 86&amp;lt;/a&amp;gt; &amp;lt;/p&amp;gt;&amp;lt;p class=&amp;quot;mb-0&amp;quot;&amp;gt; &amp;lt;a href=&amp;quot;mailto:info@PHP GrapesJS.com&amp;quot;&amp;gt;info@PHP GrapesJS.com&amp;lt;/a&amp;gt; &amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-md-4 p-3&amp;quot;&amp;gt;&amp;lt;h5&amp;gt; &amp;lt;b&amp;gt;About&amp;lt;/b&amp;gt; &amp;lt;/h5&amp;gt;&amp;lt;p class=&amp;quot;mb-0&amp;quot;&amp;gt; I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence.&amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-md-12 text-center&amp;quot;&amp;gt;&amp;lt;p class=&amp;quot;mb-0 mt-2&amp;quot;&amp;gt;&amp;copy; 2021 PHP GrapesJS. All rights reserved&amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin:0;}#icwnu{background-image:linear-gradient(to bottom, rgba(0, 0, 0, .75), rgba(0, 0, 0, .75)), url(http://localhost:130/assets/images/cover-bubble-dark.svg);background-position:center center, center center;background-size:cover, cover;background-repeat:repeat, repeat;}#iycj1{background-image:url(http://localhost:130/assets/images/cover-bubble-dark.svg);background-position:right bottom;background-size:cover;background-repeat:repeat;background-attachment:fixed;}', 0, 1, 0, 0, 1, '2023-09-30 23:39:21', '2023-10-02 08:57:19'),
	(6, 1, 0, 'Ayahuasca', 'ayahuasca', NULL, 'Ayahuasca', 'Ayahuasca', 'Ayahuasca', NULL, 'Design', 1, 0, NULL, NULL, NULL, NULL, '&amp;lt;body&amp;gt;&amp;lt;div id=&amp;quot;iyih&amp;quot; class=&amp;quot;py-5 text-center align-items-center d-flex&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container py-5&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot; id=&amp;quot;ipvi&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-md-8 mx-auto&amp;quot;&amp;gt; &amp;lt;i class=&amp;quot;d-block fa fa-stop-circle mb-3 text-muted fa-5x&amp;quot;&amp;gt;&amp;lt;/i&amp;gt;&amp;lt;h1 class=&amp;quot;display-3 mb-4&amp;quot;&amp;gt;O my friend&amp;lt;/h1&amp;gt;&amp;lt;p class=&amp;quot;lead mb-5&amp;quot;&amp;gt;Heaven and earth seem to dwell in my soul and absorb its power, like the form of a beloved mistress, then I often think with longing, Oh, would I could describe these conceptions, could impress upon paper all that is living.&amp;lt;/p&amp;gt;&amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg mx-1 btn-outline-dark&amp;quot;&amp;gt;Do&amp;lt;/a&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg btn-primary mx-1&amp;quot;&amp;gt;Something&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;gjs-row&amp;quot; id=&amp;quot;ivlba&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;gjs-cell&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-6 p-0 pr-1&amp;quot;&amp;gt;&amp;lt;img src=&amp;quot;../uploads/icon-pepiuox.png&amp;quot; id=&amp;quot;ih1m4&amp;quot; class=&amp;quot;img-fluid d-block&amp;quot;/&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-6 p-0 pl-1&amp;quot;&amp;gt;&amp;lt;img src=&amp;quot;http://localhost:130/assets/images/img-placeholder-3.svg&amp;quot; class=&amp;quot;img-fluid d-block rounded-circle&amp;quot;/&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin:0;}#iyih{background-image:linear-gradient(to left bottom, rgba(189, 195, 199, .75), rgba(44, 62, 80, .75));background-size:100%;}.gjs-row{display:flex;justify-content:flex-start;align-items:stretch;flex-wrap:nowrap;padding:10px;}.gjs-cell{min-height:75px;flex-grow:1;flex-basis:100%;}@media (max-width: 768px){.gjs-row{flex-wrap:wrap;}}', 0, 1, 0, 0, 1, '2023-09-30 23:39:21', '2023-08-25 10:17:31');

-- Dumping structure for table test_cms.pageviews
DROP TABLE IF EXISTS `pageviews`;
CREATE TABLE IF NOT EXISTS `pageviews` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `page` char(250) DEFAULT NULL,
  `ip` char(20) DEFAULT NULL,
  `date_view` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.pageviews: ~25 rows (approximately)
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES
	(1, 'Ayahuasca', '127.0.0.1', '2023-10-01 17:43:22'),
	(2, 'Home', '127.0.0.1', '2023-10-01 17:43:23'),
	(3, 'Diets', '127.0.0.1', '2023-10-01 17:47:04'),
	(4, 'Abous Us', '127.0.0.1', '2023-10-01 17:57:56'),
	(5, 'Retreats', '127.0.0.1', '2023-10-02 02:18:26'),
	(6, 'Shaman', '127.0.0.1', '2023-10-02 02:51:39'),
	(7, 'Diets', '127.0.0.1', '2023-10-02 16:29:34'),
	(8, 'Home', '127.0.0.1', '2023-10-02 16:29:35'),
	(9, 'Abous Us', '127.0.0.1', '2023-10-03 05:59:41'),
	(10, 'Ayahuasca', '127.0.0.1', '2023-10-03 06:48:13'),
	(11, 'Shaman', '127.0.0.1', '2023-10-03 08:32:33'),
	(12, 'Retreats', '127.0.0.1', '2023-10-03 09:02:30'),
	(13, 'Home', '127.0.0.1', '2023-10-03 18:58:46'),
	(14, 'Home', '127.0.0.1', '2023-10-10 16:07:21'),
	(15, 'Home', '127.0.0.1', '2023-10-11 17:21:06'),
	(16, 'Home', '127.0.0.1', '2023-10-13 05:38:11'),
	(17, 'Home', '127.0.0.1', '2023-10-24 08:06:43'),
	(18, 'Home', '127.0.0.1', '2023-11-09 08:07:46'),
	(19, 'Shaman', '127.0.0.1', '2023-11-09 08:07:50'),
	(20, 'Ayahuasca', '127.0.0.1', '2023-11-09 08:07:57'),
	(21, 'Diets', '127.0.0.1', '2023-11-09 09:01:13'),
	(22, 'Home', '127.0.0.1', '2023-11-25 00:47:10'),
	(23, 'Ayahuasca', '127.0.0.1', '2023-11-25 00:49:05'),
	(24, 'Diets', '127.0.0.1', '2023-11-25 00:49:06'),
	(25, 'Retreats', '127.0.0.1', '2023-11-25 00:49:07'),
	(26, 'Abous Us', '127.0.0.1', '2023-11-25 00:49:08');

-- Dumping structure for table test_cms.page_menu
DROP TABLE IF EXISTS `page_menu`;
CREATE TABLE IF NOT EXISTS `page_menu` (
  `page_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) DEFAULT NULL,
  `lang_iso` varchar(10) DEFAULT NULL,
  `pages_id` int(11) DEFAULT NULL,
  `other_link` varchar(512) DEFAULT NULL,
  `plugin_menu` varchar(255) DEFAULT NULL,
  `drop_menu` int(11) DEFAULT NULL,
  `drop_page_menu_id` int(11) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `new_windows` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `arrange` int(11) DEFAULT NULL,
  `timestamp_create` timestamp NULL DEFAULT current_timestamp(),
  `timestamp_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`page_menu_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.page_menu: 0 rows
/*!40000 ALTER TABLE `page_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_menu` ENABLE KEYS */;

-- Dumping structure for table test_cms.people
DROP TABLE IF EXISTS `people`;
CREATE TABLE IF NOT EXISTS `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.people: ~0 rows (approximately)

-- Dumping structure for table test_cms.personal_config
DROP TABLE IF EXISTS `personal_config`;
CREATE TABLE IF NOT EXISTS `personal_config` (
  `idPconf` int(11) DEFAULT NULL,
  `looking_for` varchar(50) DEFAULT NULL,
  `education` varchar(50) DEFAULT NULL,
  `occupation_industry` varchar(50) DEFAULT NULL,
  `relationship` varchar(50) DEFAULT NULL,
  `ethnicity` varchar(50) DEFAULT NULL,
  `smokes` varchar(50) DEFAULT NULL,
  `drinks` varchar(50) DEFAULT NULL,
  `children` varchar(50) DEFAULT NULL,
  `body_type` varchar(50) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `width` varchar(50) DEFAULT NULL,
  `about_me` varchar(50) DEFAULT NULL,
  `interest` varchar(50) DEFAULT NULL,
  `skills` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table test_cms.personal_config: ~0 rows (approximately)

-- Dumping structure for table test_cms.plugins_app
DROP TABLE IF EXISTS `plugins_app`;
CREATE TABLE IF NOT EXISTS `plugins_app` (
  `id` int(11) NOT NULL,
  `plugins` char(50) DEFAULT NULL,
  `plugins_opts` char(50) DEFAULT NULL,
  `script` varchar(500) DEFAULT NULL,
  `css` varchar(500) DEFAULT NULL,
  `buttons` varchar(500) DEFAULT NULL,
  `plugins_script` char(250) DEFAULT NULL,
  `plugins_css` char(250) DEFAULT NULL,
  `active` enum('Yes','No') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table test_cms.plugins_app: ~34 rows (approximately)
INSERT INTO `plugins_app` (`id`, `plugins`, `plugins_opts`, `script`, `css`, `buttons`, `plugins_script`, `plugins_css`, `active`) VALUES
	(1, 'gjs-component-countdown', '', '', '', '', 'gjs-component-countdown.js', '', 'Yes'),
	(2, 'gjs-navbar', '', '', '', '', 'gjs-navbar.js', '', 'Yes'),
	(3, 'gjs-plugin-ckeditor', '', '', '', '', 'gjs-plugin-ckeditor.js', '', 'Yes'),
	(4, 'gjs-plugin-filestack', '', '', '', '', 'gjs-plugin-filestack.js', '', 'Yes'),
	(5, 'gjs-preset-newsletter', '', '', '', '', '', '', NULL),
	(6, 'gjs-preset-webpage', '', '', '', '', '', '', NULL),
	(7, 'gjs-component-bootstrap4', '', '', '', '', 'grapesjs-blocks-bootstrap4.min.js', '', 'Yes'),
	(8, 'grapesjs-code-editor', '', '', '', '', '', '', NULL),
	(9, 'grapesjs-component-code-editor', '', '', '', '', '', '', NULL),
	(10, 'grapesjs-custom-code', '', '', '', '', '', '', NULL),
	(11, 'grapesjs-echarts', '', '', '', '', '', '', NULL),
	(12, 'grapesjs-firestore', '', '', '', '', '', '', NULL),
	(13, 'grapesjs-ga', '', '', '', '', '', '', NULL),
	(14, 'grapesjs-indexeddb', '', '', '', '', '', '', NULL),
	(15, 'grapesjs-lory-slider', '', '', '', '', '', '', NULL),
	(16, 'grapesjs-mjml', '', '', '', '', '', '', NULL),
	(17, 'grapesjs-page-break', '', '', '', '', '', '', NULL),
	(18, 'grapesjs-parser-postcss', '', '', '', '', '', '', NULL),
	(19, 'grapesjs-plugin-export', '', '', '', '', '', '', NULL),
	(20, 'grapesjs-plugin-forms', '', '', '', '', '', '', NULL),
	(21, 'grapesjs-plugin-toolbox', '', '', '', '', '', '', NULL),
	(22, 'grapesjs-project-manager', '', '', '', '', '', '', NULL),
	(23, 'grapesjs-script-editor', '', '', '', '', '', '', NULL),
	(24, 'grapesjs-style-bg', '', '', '', '', '', '', NULL),
	(25, 'grapesjs-style-filter', '', '', '', '', '', '', NULL),
	(26, 'grapesjs-style-gradient', '', '', '', '', '', '', NULL),
	(27, 'grapesjs-swiper-slider', '', '', '', '', '', '', NULL),
	(28, 'grapesjs-table', '', '', '', '', '', '', NULL),
	(29, 'grapesjs-tabs', '', '', '', '', '', '', NULL),
	(30, 'grapesjs-tooltip', '', '', '', '', '', '', NULL),
	(31, 'grapesjs-touch', '', '', '', '', '', '', NULL),
	(32, 'grapesjs-tui-image-editor', '', '', '', '', '', '', NULL),
	(33, 'grapesjs-typed', '', '', '', '', '', '', NULL),
	(34, 'grapesjs-uikit', '', '', '', '', '', '', NULL);

-- Dumping structure for table test_cms.preset
DROP TABLE IF EXISTS `preset`;
CREATE TABLE IF NOT EXISTS `preset` (
  `id` int(11) NOT NULL,
  `preset` char(50) DEFAULT NULL,
  `plugins` char(50) DEFAULT NULL,
  `plugins_opts` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.preset: ~0 rows (approximately)

-- Dumping structure for table test_cms.press_gal
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.press_gal: ~0 rows (approximately)

-- Dumping structure for table test_cms.profiles
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.profiles: ~0 rows (approximately)
INSERT INTO `profiles` (`idp`, `mkhash`, `firstname`, `lastname`, `gender`, `age`, `avatar`, `birthday`, `phone`, `website`, `social_media`, `profession`, `occupation`, `public_email`, `address`, `followers_count`, `profile_image`, `profile_cover`, `profile_bio`, `language`, `active`, `banned`, `date`, `update`) VALUES
	('46571922665046f9167217', '08ecd4d0c7246a7a498baf977ffa887b473fe90d', 'Jose', 'Mantilla', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2023-09-15 14:52:01', '2023-10-10 16:07:39');

-- Dumping structure for table test_cms.role_permissions
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.role_permissions: ~24 rows (approximately)
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

-- Dumping structure for table test_cms.secrets
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.secrets: ~0 rows (approximately)

-- Dumping structure for table test_cms.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `set_time` int(11) NOT NULL,
  `data` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `session_key` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ip` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.sessions: ~0 rows (approximately)

-- Dumping structure for table test_cms.setsession
DROP TABLE IF EXISTS `setsession`;
CREATE TABLE IF NOT EXISTS `setsession` (
  `ssID` varchar(128) NOT NULL,
  `data` mediumblob DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`ssID`),
  KEY `timestamp` (`timestamp`,`ssID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.setsession: ~0 rows (approximately)

-- Dumping structure for table test_cms.site_configuration
DROP TABLE IF EXISTS `site_configuration`;
CREATE TABLE IF NOT EXISTS `site_configuration` (
  `ID_Site` int(11) NOT NULL,
  `DOMAIN_SITE` varchar(60) NOT NULL,
  `SITE_NAME` varchar(60) NOT NULL,
  `SITE_BRAND_IMG` varchar(250) DEFAULT NULL,
  `SITE_PATH` varchar(250) DEFAULT NULL,
  `SITE_DESCRIPTION` tinytext DEFAULT NULL,
  `SITE_KEYWORDS` tinytext DEFAULT NULL,
  `SITE_CLASSIFICATION` tinytext DEFAULT NULL,
  `SITE_EMAIL` varchar(60) DEFAULT NULL,
  `SITE_IMAGE` varchar(250) DEFAULT NULL,
  `SITE_ADMIN` varchar(60) DEFAULT NULL,
  `SITE_CONTROL` varchar(60) DEFAULT NULL,
  `SITE_CONFIG` varchar(60) DEFAULT NULL,
  `SITE_LANGUAGE_1` varchar(60) DEFAULT NULL,
  `SITE_LANGUAGE_2` varchar(60) DEFAULT NULL,
  `FOLDER_IMAGES` varchar(60) DEFAULT NULL,
  `SITE_CREATOR` varchar(60) DEFAULT NULL,
  `SITE_EDITOR` varchar(60) DEFAULT NULL,
  `SITE_BUILDER` varchar(60) DEFAULT NULL,
  `SITE_LIST` varchar(60) DEFAULT NULL,
  `NAME_CONTACT` varchar(60) DEFAULT NULL,
  `PHONE_CONTACT` varchar(60) DEFAULT NULL,
  `EMAIL_CONTACT` varchar(60) DEFAULT NULL,
  `ADDRESS` tinytext DEFAULT NULL,
  `TWITTER` varchar(60) DEFAULT NULL,
  `FACEBOOKID` varchar(60) DEFAULT NULL,
  `SKYPE` varchar(60) DEFAULT NULL,
  `TELEGRAM` varchar(60) DEFAULT NULL,
  `WHATSAPP` varchar(60) DEFAULT NULL,
  `MAILSERVER` varchar(60) DEFAULT NULL,
  `PORTSERVER` varchar(60) DEFAULT NULL,
  `USEREMAIL` varchar(60) DEFAULT NULL,
  `PASSMAIL` varchar(60) DEFAULT NULL,
  `SUPERADMIN_NAME` varchar(60) DEFAULT NULL,
  `SUPERADMIN_LEVEL` tinyint(4) DEFAULT NULL,
  `ADMIN_NAME` varchar(60) NOT NULL DEFAULT '',
  `ADMIN_LEVEL` tinyint(4) NOT NULL,
  `SECURE_HASH` varchar(256) DEFAULT NULL,
  `SECURE_TOKEN` varchar(256) DEFAULT NULL,
  `CREATE` timestamp NOT NULL DEFAULT current_timestamp(),
  `UPDATED` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `SITE_NAME` (`SITE_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.site_configuration: ~0 rows (approximately)
INSERT INTO `site_configuration` (`ID_Site`, `DOMAIN_SITE`, `SITE_NAME`, `SITE_BRAND_IMG`, `SITE_PATH`, `SITE_DESCRIPTION`, `SITE_KEYWORDS`, `SITE_CLASSIFICATION`, `SITE_EMAIL`, `SITE_IMAGE`, `SITE_ADMIN`, `SITE_CONTROL`, `SITE_CONFIG`, `SITE_LANGUAGE_1`, `SITE_LANGUAGE_2`, `FOLDER_IMAGES`, `SITE_CREATOR`, `SITE_EDITOR`, `SITE_BUILDER`, `SITE_LIST`, `NAME_CONTACT`, `PHONE_CONTACT`, `EMAIL_CONTACT`, `ADDRESS`, `TWITTER`, `FACEBOOKID`, `SKYPE`, `TELEGRAM`, `WHATSAPP`, `MAILSERVER`, `PORTSERVER`, `USEREMAIL`, `PASSMAIL`, `SUPERADMIN_NAME`, `SUPERADMIN_LEVEL`, `ADMIN_NAME`, `ADMIN_LEVEL`, `SECURE_HASH`, `SECURE_TOKEN`, `CREATE`, `UPDATED`) VALUES
	(1, 'http://www.pepiuox.net', 'PEPIUOX', 'icon-pepiuox.png', 'http://localhost:130/', 'Your description for your domains', 'Your keywords for your domains', 'Your classification for your domains', 'info@phpgrapesjs.com', 'dashboard', 'dashboard', 'users', 'siteconf', 'English', 'Spanish', 'uploads', 'admin', 'admin, editor', 'builder', 'list', 'Jose Mantilla', '0051999063645', 'contact@pepiuox.net', 'Lima - Peru', '@pepiuox', 'pepiuox30675', 'pepiuox', 'pepiuox', '+51 999063645', 'smtp.hostinger.com', '461', 'contact@pepiuox.net', 'truelove', 'Super Admin', 9, 'Admin', 5, 'd03ba90951cb364f60e89a82ba989172ced435d6365a199d1ef210c6d4e5f3fe', 'V{l{lf)WCdLwau#EQbYNB}93oW78ExU{lzAQ4%ODev4w&RJdDdNvve4819BJcU]M&JrTkL8wAb6T&gEe2}vQ(9}6@r5BfkIpRR2Nad0ViRwSkeg5ouO4atdS$DEkAPQC', '2022-01-08 13:42:41', '2023-09-15 14:50:53');

-- Dumping structure for table test_cms.slideshow
DROP TABLE IF EXISTS `slideshow`;
CREATE TABLE IF NOT EXISTS `slideshow` (
  `idSld` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) NOT NULL,
  `page_id` int(11) NOT NULL,
  `comment` char(150) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `create` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idSld`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table test_cms.slideshow: ~0 rows (approximately)

-- Dumping structure for table test_cms.slideshow_image
DROP TABLE IF EXISTS `slideshow_image`;
CREATE TABLE IF NOT EXISTS `slideshow_image` (
  `idImg` int(11) NOT NULL AUTO_INCREMENT,
  `slideshow_id` int(11) DEFAULT NULL,
  `title_image` char(60) DEFAULT NULL,
  `description` int(100) DEFAULT NULL,
  `slide_image` varchar(250) DEFAULT NULL,
  `btn_color` enum('primary','secondary','light','dark','info','success','warning','danger','gray') DEFAULT 'primary',
  `btn_text` char(30) DEFAULT NULL,
  `link` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`idImg`),
  KEY `SLD1` (`slideshow_id`),
  CONSTRAINT `SLD1` FOREIGN KEY (`slideshow_id`) REFERENCES `slideshow` (`idSld`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table test_cms.slideshow_image: ~0 rows (approximately)

-- Dumping structure for table test_cms.social_link
DROP TABLE IF EXISTS `social_link`;
CREATE TABLE IF NOT EXISTS `social_link` (
  `social_name` varchar(20) DEFAULT NULL,
  `social_url` varchar(150) DEFAULT NULL,
  KEY `social_name` (`social_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.social_link: ~0 rows (approximately)

-- Dumping structure for table test_cms.stats_counter
DROP TABLE IF EXISTS `stats_counter`;
CREATE TABLE IF NOT EXISTS `stats_counter` (
  `Type` varchar(50) NOT NULL DEFAULT '',
  `Variable` varchar(50) NOT NULL DEFAULT '',
  `Counter` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`Type`,`Variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.stats_counter: ~0 rows (approximately)

-- Dumping structure for table test_cms.stats_counterlog
DROP TABLE IF EXISTS `stats_counterlog`;
CREATE TABLE IF NOT EXISTS `stats_counterlog` (
  `IP_Address` varchar(50) NOT NULL DEFAULT '',
  `Hostname` varchar(50) DEFAULT NULL,
  `First_Visit` datetime NOT NULL,
  `Last_Visit` datetime NOT NULL,
  `Counter` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`IP_Address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.stats_counterlog: ~0 rows (approximately)

-- Dumping structure for table test_cms.stats_date
DROP TABLE IF EXISTS `stats_date`;
CREATE TABLE IF NOT EXISTS `stats_date` (
  `Year` smallint(6) NOT NULL DEFAULT 0,
  `Month` tinyint(4) NOT NULL DEFAULT 0,
  `Date` tinyint(4) NOT NULL DEFAULT 0,
  `Hits` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Date`,`Month`,`Year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.stats_date: ~0 rows (approximately)

-- Dumping structure for table test_cms.stats_hour
DROP TABLE IF EXISTS `stats_hour`;
CREATE TABLE IF NOT EXISTS `stats_hour` (
  `Year` smallint(6) NOT NULL DEFAULT 0,
  `Month` tinyint(4) NOT NULL DEFAULT 0,
  `Date` tinyint(4) NOT NULL DEFAULT 0,
  `Hour` tinyint(4) NOT NULL DEFAULT 0,
  `Hits` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Date`,`Hour`,`Month`,`Year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.stats_hour: ~0 rows (approximately)

-- Dumping structure for table test_cms.stats_month
DROP TABLE IF EXISTS `stats_month`;
CREATE TABLE IF NOT EXISTS `stats_month` (
  `Year` smallint(6) NOT NULL DEFAULT 0,
  `Month` tinyint(4) NOT NULL DEFAULT 0,
  `Hits` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Year`,`Month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.stats_month: ~0 rows (approximately)

-- Dumping structure for table test_cms.stats_pages
DROP TABLE IF EXISTS `stats_pages`;
CREATE TABLE IF NOT EXISTS `stats_pages` (
  `id` int(11) DEFAULT NULL,
  `page` int(11) DEFAULT NULL,
  `ip_visitor` char(50) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.stats_pages: ~0 rows (approximately)

-- Dumping structure for table test_cms.stats_year
DROP TABLE IF EXISTS `stats_year`;
CREATE TABLE IF NOT EXISTS `stats_year` (
  `Year` smallint(6) NOT NULL DEFAULT 0,
  `Hits` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.stats_year: ~0 rows (approximately)

-- Dumping structure for table test_cms.sub_categories
DROP TABLE IF EXISTS `sub_categories`;
CREATE TABLE IF NOT EXISTS `sub_categories` (
  `subcat_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_category` int(11) NOT NULL,
  `sub_category_name` varchar(50) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`subcat_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.sub_categories: ~0 rows (approximately)

-- Dumping structure for table test_cms.table_column_settings
DROP TABLE IF EXISTS `table_column_settings`;
CREATE TABLE IF NOT EXISTS `table_column_settings` (
  `tqop_Id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) DEFAULT NULL,
  `col_name` varchar(50) DEFAULT NULL,
  `col_list` tinyint(1) DEFAULT 0,
  `col_add` tinyint(1) DEFAULT 0,
  `col_update` tinyint(1) DEFAULT 0,
  `col_view` tinyint(1) DEFAULT 0,
  `col_type` varchar(50) DEFAULT NULL,
  `input_type` int(11) DEFAULT NULL,
  `joins` varchar(50) DEFAULT NULL,
  `j_table` varchar(50) DEFAULT NULL,
  `j_id` varchar(50) DEFAULT NULL,
  `j_value` varchar(50) DEFAULT NULL,
  `j_as` varchar(50) DEFAULT NULL,
  `j_order` varchar(50) DEFAULT NULL,
  `where` varchar(250) DEFAULT NULL,
  `dependent` varchar(50) DEFAULT NULL,
  `main_field` varchar(50) DEFAULT NULL,
  `lookup_field` varchar(50) DEFAULT NULL,
  `jvpos` int(11) DEFAULT NULL,
  PRIMARY KEY (`tqop_Id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.table_column_settings: ~5 rows (approximately)
INSERT INTO `table_column_settings` (`tqop_Id`, `table_name`, `col_name`, `col_list`, `col_add`, `col_update`, `col_view`, `col_type`, `input_type`, `joins`, `j_table`, `j_id`, `j_value`, `j_as`, `j_order`, `where`, `dependent`, `main_field`, `lookup_field`, `jvpos`) VALUES
	(1, 'menu', 'sort', 0, 0, 0, 0, 'int', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 'menu', 'page_id', 1, 1, 1, 1, 'int', 3, 'LEFT JOIN', 'page', 'id', 'title', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(3, 'menu', 'title_page', 0, 0, 0, 0, 'varchar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, 'menu', 'link_page', 0, 0, 0, 0, 'varchar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(5, 'menu', 'parent_id', 0, 0, 0, 0, 'int', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- Dumping structure for table test_cms.table_config
DROP TABLE IF EXISTS `table_config`;
CREATE TABLE IF NOT EXISTS `table_config` (
  `tcon_Id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` text DEFAULT NULL,
  PRIMARY KEY (`tcon_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.table_config: ~0 rows (approximately)
INSERT INTO `table_config` (`tcon_Id`, `table_name`) VALUES
	(1, 'menu,page,templates,theme_base_colors,theme_base_font,theme_headings_font,theme_lead_font,theme_palette,theme_settings,themes');

-- Dumping structure for table test_cms.table_settings
DROP TABLE IF EXISTS `table_settings`;
CREATE TABLE IF NOT EXISTS `table_settings` (
  `IdTbset` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` char(50) NOT NULL DEFAULT '',
  `table_list` tinyint(1) NOT NULL DEFAULT 0,
  `table_add` tinyint(1) NOT NULL DEFAULT 0,
  `table_update` tinyint(1) NOT NULL DEFAULT 0,
  `table_delete` tinyint(1) NOT NULL DEFAULT 0,
  `table_view` tinyint(1) NOT NULL DEFAULT 0,
  `table_secure` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`IdTbset`) USING BTREE,
  UNIQUE KEY `table_name` (`table_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.table_settings: ~0 rows (approximately)
INSERT INTO `table_settings` (`IdTbset`, `table_name`, `table_list`, `table_add`, `table_update`, `table_delete`, `table_view`, `table_secure`) VALUES
	(1, 'menu', 1, 1, 1, 0, 1, 0);

-- Dumping structure for table test_cms.tags
DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.tags: ~0 rows (approximately)

-- Dumping structure for table test_cms.templates
DROP TABLE IF EXISTS `templates`;
CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `templates` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.templates: ~0 rows (approximately)

-- Dumping structure for table test_cms.themes
DROP TABLE IF EXISTS `themes`;
CREATE TABLE IF NOT EXISTS `themes` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` char(50) NOT NULL,
  `theme_bootstrap` char(50) NOT NULL,
  `base_default` enum('Yes','No') NOT NULL DEFAULT 'No',
  `active_theme` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`theme_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.themes: ~0 rows (approximately)
INSERT INTO `themes` (`theme_id`, `theme_name`, `theme_bootstrap`, `base_default`, `active_theme`) VALUES
	(1, 'test theme color', 'lumen', 'Yes', 'Yes');

-- Dumping structure for table test_cms.theme_base_colors
DROP TABLE IF EXISTS `theme_base_colors`;
CREATE TABLE IF NOT EXISTS `theme_base_colors` (
  `idtbc` int(11) NOT NULL AUTO_INCREMENT,
  `body_color` char(8) DEFAULT NULL,
  `text_color` char(8) DEFAULT NULL,
  `links_color` char(8) DEFAULT NULL,
  PRIMARY KEY (`idtbc`) USING BTREE,
  UNIQUE KEY `theme_id` (`idtbc`) USING BTREE,
  CONSTRAINT `FK_theme_base_colors` FOREIGN KEY (`idtbc`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.theme_base_colors: ~0 rows (approximately)
INSERT INTO `theme_base_colors` (`idtbc`, `body_color`, `text_color`, `links_color`) VALUES
	(1, '#ffffff', '#2f0202', '#172fa2');

-- Dumping structure for table test_cms.theme_base_font
DROP TABLE IF EXISTS `theme_base_font`;
CREATE TABLE IF NOT EXISTS `theme_base_font` (
  `idtbf` int(11) NOT NULL AUTO_INCREMENT,
  `family` char(50) DEFAULT NULL,
  `size` char(50) DEFAULT NULL,
  `weight` enum('default','light','normal','bold') NOT NULL DEFAULT 'default',
  `line_height` char(50) DEFAULT NULL,
  PRIMARY KEY (`idtbf`) USING BTREE,
  UNIQUE KEY `theme_id` (`idtbf`) USING BTREE,
  CONSTRAINT `FK_theme_base_font` FOREIGN KEY (`idtbf`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.theme_base_font: ~0 rows (approximately)
INSERT INTO `theme_base_font` (`idtbf`, `family`, `size`, `weight`, `line_height`) VALUES
	(1, '', '', 'default', '');

-- Dumping structure for table test_cms.theme_headings_font
DROP TABLE IF EXISTS `theme_headings_font`;
CREATE TABLE IF NOT EXISTS `theme_headings_font` (
  `idthf` int(11) NOT NULL AUTO_INCREMENT,
  `family` char(50) DEFAULT NULL,
  `weight` enum('default','light','normal','bold') NOT NULL DEFAULT 'default',
  `line_weight` char(50) DEFAULT NULL,
  PRIMARY KEY (`idthf`) USING BTREE,
  UNIQUE KEY `theme_id` (`idthf`) USING BTREE,
  CONSTRAINT `FK_theme_headings_font` FOREIGN KEY (`idthf`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.theme_headings_font: ~0 rows (approximately)
INSERT INTO `theme_headings_font` (`idthf`, `family`, `weight`, `line_weight`) VALUES
	(1, '', 'default', '');

-- Dumping structure for table test_cms.theme_lead_font
DROP TABLE IF EXISTS `theme_lead_font`;
CREATE TABLE IF NOT EXISTS `theme_lead_font` (
  `idtlf` int(11) NOT NULL AUTO_INCREMENT,
  `size` char(6) DEFAULT NULL,
  `weight` char(6) DEFAULT NULL,
  PRIMARY KEY (`idtlf`) USING BTREE,
  UNIQUE KEY `theme_id` (`idtlf`) USING BTREE,
  CONSTRAINT `FK_theme_lead_font` FOREIGN KEY (`idtlf`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.theme_lead_font: ~0 rows (approximately)
INSERT INTO `theme_lead_font` (`idtlf`, `size`, `weight`) VALUES
	(1, '', '');

-- Dumping structure for table test_cms.theme_palette
DROP TABLE IF EXISTS `theme_palette`;
CREATE TABLE IF NOT EXISTS `theme_palette` (
  `idtp` int(11) NOT NULL AUTO_INCREMENT,
  `primary_color` char(8) DEFAULT NULL,
  `secondary_color` char(8) DEFAULT NULL,
  `info_color` char(8) DEFAULT NULL,
  `light_color` char(8) DEFAULT NULL,
  `dark_color` char(8) DEFAULT NULL,
  `success_color` char(8) DEFAULT NULL,
  `warning_color` char(8) DEFAULT NULL,
  `danger_color` char(8) DEFAULT NULL,
  `custom_color` char(8) DEFAULT NULL,
  `custom_light_color` char(8) DEFAULT NULL,
  `custom_dark_color` char(8) DEFAULT NULL,
  PRIMARY KEY (`idtp`) USING BTREE,
  UNIQUE KEY `theme_id` (`idtp`) USING BTREE,
  CONSTRAINT `FK_theme_palette` FOREIGN KEY (`idtp`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.theme_palette: ~0 rows (approximately)
INSERT INTO `theme_palette` (`idtp`, `primary_color`, `secondary_color`, `info_color`, `light_color`, `dark_color`, `success_color`, `warning_color`, `danger_color`, `custom_color`, `custom_light_color`, `custom_dark_color`) VALUES
	(1, '#993f3f', '#814040', '#1f53a5', '#000000', '#4b3737', '#000000', '#d24747', '#000000', '#000000', '#d4b8b8', '#000000');

-- Dumping structure for table test_cms.theme_settings
DROP TABLE IF EXISTS `theme_settings`;
CREATE TABLE IF NOT EXISTS `theme_settings` (
  `idts` int(11) NOT NULL AUTO_INCREMENT,
  `container` enum('default','narrow') NOT NULL DEFAULT 'default',
  `spacer` enum('x 2','x 1.5','x 1.2','default','x .8','x .5') NOT NULL DEFAULT 'default',
  `radius` char(50) DEFAULT NULL,
  `radius_sm` char(50) DEFAULT NULL,
  `radius_lg` char(50) DEFAULT NULL,
  `font_size` enum('default','responsive','unresponsive') NOT NULL DEFAULT 'default',
  PRIMARY KEY (`idts`) USING BTREE,
  UNIQUE KEY `theme_id` (`idts`) USING BTREE,
  CONSTRAINT `FK_theme_settings` FOREIGN KEY (`idts`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.theme_settings: ~0 rows (approximately)
INSERT INTO `theme_settings` (`idts`, `container`, `spacer`, `radius`, `radius_sm`, `radius_lg`, `font_size`) VALUES
	(1, 'default', 'default', NULL, NULL, NULL, 'default');

-- Dumping structure for table test_cms.timezone
DROP TABLE IF EXISTS `timezone`;
CREATE TABLE IF NOT EXISTS `timezone` (
  `Timezone` varchar(50) NOT NULL,
  `Default` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`Timezone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.timezone: ~0 rows (approximately)

-- Dumping structure for table test_cms.tokens
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.tokens: ~0 rows (approximately)

-- Dumping structure for table test_cms.total_visitors
DROP TABLE IF EXISTS `total_visitors`;
CREATE TABLE IF NOT EXISTS `total_visitors` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `session` char(128) DEFAULT NULL,
  `time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.total_visitors: ~0 rows (approximately)
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES
	(1, 'VzB2V21zVUVpVGJvUGt6TEoxTFJHZz09', '2023-12-04 09:15:04');

-- Dumping structure for table test_cms.type_blocks
DROP TABLE IF EXISTS `type_blocks`;
CREATE TABLE IF NOT EXISTS `type_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_block` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.type_blocks: ~0 rows (approximately)

-- Dumping structure for table test_cms.type_gallery
DROP TABLE IF EXISTS `type_gallery`;
CREATE TABLE IF NOT EXISTS `type_gallery` (
  `idTG` int(11) NOT NULL AUTO_INCREMENT,
  `type_gallery` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idTG`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.type_gallery: ~0 rows (approximately)

-- Dumping structure for table test_cms.type_menu
DROP TABLE IF EXISTS `type_menu`;
CREATE TABLE IF NOT EXISTS `type_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_menu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.type_menu: ~0 rows (approximately)

-- Dumping structure for table test_cms.type_page
DROP TABLE IF EXISTS `type_page`;
CREATE TABLE IF NOT EXISTS `type_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_page` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.type_page: ~0 rows (approximately)

-- Dumping structure for table test_cms.ubigeo_peru_departments
DROP TABLE IF EXISTS `ubigeo_peru_departments`;
CREATE TABLE IF NOT EXISTS `ubigeo_peru_departments` (
  `id` varchar(2) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.ubigeo_peru_departments: ~25 rows (approximately)
INSERT INTO `ubigeo_peru_departments` (`id`, `name`) VALUES
	('01', 'Amazonas'),
	('02', 'Áncash'),
	('03', 'Apurímac'),
	('04', 'Arequipa'),
	('05', 'Ayacucho'),
	('06', 'Cajamarca'),
	('07', 'Callao'),
	('08', 'Cusco'),
	('09', 'Huancavelica'),
	('10', 'Huánuco'),
	('11', 'Ica'),
	('12', 'Junín'),
	('13', 'La Libertad'),
	('14', 'Lambayeque'),
	('15', 'Lima'),
	('16', 'Loreto'),
	('17', 'Madre de Dios'),
	('18', 'Moquegua'),
	('19', 'Pasco'),
	('20', 'Piura'),
	('21', 'Puno'),
	('22', 'San Martín'),
	('23', 'Tacna'),
	('24', 'Tumbes'),
	('25', 'Ucayali');

-- Dumping structure for table test_cms.ubigeo_peru_districts
DROP TABLE IF EXISTS `ubigeo_peru_districts`;
CREATE TABLE IF NOT EXISTS `ubigeo_peru_districts` (
  `id` varchar(6) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `province_id` varchar(4) DEFAULT NULL,
  `department_id` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.ubigeo_peru_districts: ~1,874 rows (approximately)
INSERT INTO `ubigeo_peru_districts` (`id`, `name`, `province_id`, `department_id`) VALUES
	('010101', 'Chachapoyas', '0101', '01'),
	('010102', 'Asunción', '0101', '01'),
	('010103', 'Balsas', '0101', '01'),
	('010104', 'Cheto', '0101', '01'),
	('010105', 'Chiliquin', '0101', '01'),
	('010106', 'Chuquibamba', '0101', '01'),
	('010107', 'Granada', '0101', '01'),
	('010108', 'Huancas', '0101', '01'),
	('010109', 'La Jalca', '0101', '01'),
	('010110', 'Leimebamba', '0101', '01'),
	('010111', 'Levanto', '0101', '01'),
	('010112', 'Magdalena', '0101', '01'),
	('010113', 'Mariscal Castilla', '0101', '01'),
	('010114', 'Molinopampa', '0101', '01'),
	('010115', 'Montevideo', '0101', '01'),
	('010116', 'Olleros', '0101', '01'),
	('010117', 'Quinjalca', '0101', '01'),
	('010118', 'San Francisco de Daguas', '0101', '01'),
	('010119', 'San Isidro de Maino', '0101', '01'),
	('010120', 'Soloco', '0101', '01'),
	('010121', 'Sonche', '0101', '01'),
	('010201', 'Bagua', '0102', '01'),
	('010202', 'Aramango', '0102', '01'),
	('010203', 'Copallin', '0102', '01'),
	('010204', 'El Parco', '0102', '01'),
	('010205', 'Imaza', '0102', '01'),
	('010206', 'La Peca', '0102', '01'),
	('010301', 'Jumbilla', '0103', '01'),
	('010302', 'Chisquilla', '0103', '01'),
	('010303', 'Churuja', '0103', '01'),
	('010304', 'Corosha', '0103', '01'),
	('010305', 'Cuispes', '0103', '01'),
	('010306', 'Florida', '0103', '01'),
	('010307', 'Jazan', '0103', '01'),
	('010308', 'Recta', '0103', '01'),
	('010309', 'San Carlos', '0103', '01'),
	('010310', 'Shipasbamba', '0103', '01'),
	('010311', 'Valera', '0103', '01'),
	('010312', 'Yambrasbamba', '0103', '01'),
	('010401', 'Nieva', '0104', '01'),
	('010402', 'El Cenepa', '0104', '01'),
	('010403', 'Río Santiago', '0104', '01'),
	('010501', 'Lamud', '0105', '01'),
	('010502', 'Camporredondo', '0105', '01'),
	('010503', 'Cocabamba', '0105', '01'),
	('010504', 'Colcamar', '0105', '01'),
	('010505', 'Conila', '0105', '01'),
	('010506', 'Inguilpata', '0105', '01'),
	('010507', 'Longuita', '0105', '01'),
	('010508', 'Lonya Chico', '0105', '01'),
	('010509', 'Luya', '0105', '01'),
	('010510', 'Luya Viejo', '0105', '01'),
	('010511', 'María', '0105', '01'),
	('010512', 'Ocalli', '0105', '01'),
	('010513', 'Ocumal', '0105', '01'),
	('010514', 'Pisuquia', '0105', '01'),
	('010515', 'Providencia', '0105', '01'),
	('010516', 'San Cristóbal', '0105', '01'),
	('010517', 'San Francisco de Yeso', '0105', '01'),
	('010518', 'San Jerónimo', '0105', '01'),
	('010519', 'San Juan de Lopecancha', '0105', '01'),
	('010520', 'Santa Catalina', '0105', '01'),
	('010521', 'Santo Tomas', '0105', '01'),
	('010522', 'Tingo', '0105', '01'),
	('010523', 'Trita', '0105', '01'),
	('010601', 'San Nicolás', '0106', '01'),
	('010602', 'Chirimoto', '0106', '01'),
	('010603', 'Cochamal', '0106', '01'),
	('010604', 'Huambo', '0106', '01'),
	('010605', 'Limabamba', '0106', '01'),
	('010606', 'Longar', '0106', '01'),
	('010607', 'Mariscal Benavides', '0106', '01'),
	('010608', 'Milpuc', '0106', '01'),
	('010609', 'Omia', '0106', '01'),
	('010610', 'Santa Rosa', '0106', '01'),
	('010611', 'Totora', '0106', '01'),
	('010612', 'Vista Alegre', '0106', '01'),
	('010701', 'Bagua Grande', '0107', '01'),
	('010702', 'Cajaruro', '0107', '01'),
	('010703', 'Cumba', '0107', '01'),
	('010704', 'El Milagro', '0107', '01'),
	('010705', 'Jamalca', '0107', '01'),
	('010706', 'Lonya Grande', '0107', '01'),
	('010707', 'Yamon', '0107', '01'),
	('020101', 'Huaraz', '0201', '02'),
	('020102', 'Cochabamba', '0201', '02'),
	('020103', 'Colcabamba', '0201', '02'),
	('020104', 'Huanchay', '0201', '02'),
	('020105', 'Independencia', '0201', '02'),
	('020106', 'Jangas', '0201', '02'),
	('020107', 'La Libertad', '0201', '02'),
	('020108', 'Olleros', '0201', '02'),
	('020109', 'Pampas Grande', '0201', '02'),
	('020110', 'Pariacoto', '0201', '02'),
	('020111', 'Pira', '0201', '02'),
	('020112', 'Tarica', '0201', '02'),
	('020201', 'Aija', '0202', '02'),
	('020202', 'Coris', '0202', '02'),
	('020203', 'Huacllan', '0202', '02'),
	('020204', 'La Merced', '0202', '02'),
	('020205', 'Succha', '0202', '02'),
	('020301', 'Llamellin', '0203', '02'),
	('020302', 'Aczo', '0203', '02'),
	('020303', 'Chaccho', '0203', '02'),
	('020304', 'Chingas', '0203', '02'),
	('020305', 'Mirgas', '0203', '02'),
	('020306', 'San Juan de Rontoy', '0203', '02'),
	('020401', 'Chacas', '0204', '02'),
	('020402', 'Acochaca', '0204', '02'),
	('020501', 'Chiquian', '0205', '02'),
	('020502', 'Abelardo Pardo Lezameta', '0205', '02'),
	('020503', 'Antonio Raymondi', '0205', '02'),
	('020504', 'Aquia', '0205', '02'),
	('020505', 'Cajacay', '0205', '02'),
	('020506', 'Canis', '0205', '02'),
	('020507', 'Colquioc', '0205', '02'),
	('020508', 'Huallanca', '0205', '02'),
	('020509', 'Huasta', '0205', '02'),
	('020510', 'Huayllacayan', '0205', '02'),
	('020511', 'La Primavera', '0205', '02'),
	('020512', 'Mangas', '0205', '02'),
	('020513', 'Pacllon', '0205', '02'),
	('020514', 'San Miguel de Corpanqui', '0205', '02'),
	('020515', 'Ticllos', '0205', '02'),
	('020601', 'Carhuaz', '0206', '02'),
	('020602', 'Acopampa', '0206', '02'),
	('020603', 'Amashca', '0206', '02'),
	('020604', 'Anta', '0206', '02'),
	('020605', 'Ataquero', '0206', '02'),
	('020606', 'Marcara', '0206', '02'),
	('020607', 'Pariahuanca', '0206', '02'),
	('020608', 'San Miguel de Aco', '0206', '02'),
	('020609', 'Shilla', '0206', '02'),
	('020610', 'Tinco', '0206', '02'),
	('020611', 'Yungar', '0206', '02'),
	('020701', 'San Luis', '0207', '02'),
	('020702', 'San Nicolás', '0207', '02'),
	('020703', 'Yauya', '0207', '02'),
	('020801', 'Casma', '0208', '02'),
	('020802', 'Buena Vista Alta', '0208', '02'),
	('020803', 'Comandante Noel', '0208', '02'),
	('020804', 'Yautan', '0208', '02'),
	('020901', 'Corongo', '0209', '02'),
	('020902', 'Aco', '0209', '02'),
	('020903', 'Bambas', '0209', '02'),
	('020904', 'Cusca', '0209', '02'),
	('020905', 'La Pampa', '0209', '02'),
	('020906', 'Yanac', '0209', '02'),
	('020907', 'Yupan', '0209', '02'),
	('021001', 'Huari', '0210', '02'),
	('021002', 'Anra', '0210', '02'),
	('021003', 'Cajay', '0210', '02'),
	('021004', 'Chavin de Huantar', '0210', '02'),
	('021005', 'Huacachi', '0210', '02'),
	('021006', 'Huacchis', '0210', '02'),
	('021007', 'Huachis', '0210', '02'),
	('021008', 'Huantar', '0210', '02'),
	('021009', 'Masin', '0210', '02'),
	('021010', 'Paucas', '0210', '02'),
	('021011', 'Ponto', '0210', '02'),
	('021012', 'Rahuapampa', '0210', '02'),
	('021013', 'Rapayan', '0210', '02'),
	('021014', 'San Marcos', '0210', '02'),
	('021015', 'San Pedro de Chana', '0210', '02'),
	('021016', 'Uco', '0210', '02'),
	('021101', 'Huarmey', '0211', '02'),
	('021102', 'Cochapeti', '0211', '02'),
	('021103', 'Culebras', '0211', '02'),
	('021104', 'Huayan', '0211', '02'),
	('021105', 'Malvas', '0211', '02'),
	('021201', 'Caraz', '0212', '02'),
	('021202', 'Huallanca', '0212', '02'),
	('021203', 'Huata', '0212', '02'),
	('021204', 'Huaylas', '0212', '02'),
	('021205', 'Mato', '0212', '02'),
	('021206', 'Pamparomas', '0212', '02'),
	('021207', 'Pueblo Libre', '0212', '02'),
	('021208', 'Santa Cruz', '0212', '02'),
	('021209', 'Santo Toribio', '0212', '02'),
	('021210', 'Yuracmarca', '0212', '02'),
	('021301', 'Piscobamba', '0213', '02'),
	('021302', 'Casca', '0213', '02'),
	('021303', 'Eleazar Guzmán Barron', '0213', '02'),
	('021304', 'Fidel Olivas Escudero', '0213', '02'),
	('021305', 'Llama', '0213', '02'),
	('021306', 'Llumpa', '0213', '02'),
	('021307', 'Lucma', '0213', '02'),
	('021308', 'Musga', '0213', '02'),
	('021401', 'Ocros', '0214', '02'),
	('021402', 'Acas', '0214', '02'),
	('021403', 'Cajamarquilla', '0214', '02'),
	('021404', 'Carhuapampa', '0214', '02'),
	('021405', 'Cochas', '0214', '02'),
	('021406', 'Congas', '0214', '02'),
	('021407', 'Llipa', '0214', '02'),
	('021408', 'San Cristóbal de Rajan', '0214', '02'),
	('021409', 'San Pedro', '0214', '02'),
	('021410', 'Santiago de Chilcas', '0214', '02'),
	('021501', 'Cabana', '0215', '02'),
	('021502', 'Bolognesi', '0215', '02'),
	('021503', 'Conchucos', '0215', '02'),
	('021504', 'Huacaschuque', '0215', '02'),
	('021505', 'Huandoval', '0215', '02'),
	('021506', 'Lacabamba', '0215', '02'),
	('021507', 'Llapo', '0215', '02'),
	('021508', 'Pallasca', '0215', '02'),
	('021509', 'Pampas', '0215', '02'),
	('021510', 'Santa Rosa', '0215', '02'),
	('021511', 'Tauca', '0215', '02'),
	('021601', 'Pomabamba', '0216', '02'),
	('021602', 'Huayllan', '0216', '02'),
	('021603', 'Parobamba', '0216', '02'),
	('021604', 'Quinuabamba', '0216', '02'),
	('021701', 'Recuay', '0217', '02'),
	('021702', 'Catac', '0217', '02'),
	('021703', 'Cotaparaco', '0217', '02'),
	('021704', 'Huayllapampa', '0217', '02'),
	('021705', 'Llacllin', '0217', '02'),
	('021706', 'Marca', '0217', '02'),
	('021707', 'Pampas Chico', '0217', '02'),
	('021708', 'Pararin', '0217', '02'),
	('021709', 'Tapacocha', '0217', '02'),
	('021710', 'Ticapampa', '0217', '02'),
	('021801', 'Chimbote', '0218', '02'),
	('021802', 'Cáceres del Perú', '0218', '02'),
	('021803', 'Coishco', '0218', '02'),
	('021804', 'Macate', '0218', '02'),
	('021805', 'Moro', '0218', '02'),
	('021806', 'Nepeña', '0218', '02'),
	('021807', 'Samanco', '0218', '02'),
	('021808', 'Santa', '0218', '02'),
	('021809', 'Nuevo Chimbote', '0218', '02'),
	('021901', 'Sihuas', '0219', '02'),
	('021902', 'Acobamba', '0219', '02'),
	('021903', 'Alfonso Ugarte', '0219', '02'),
	('021904', 'Cashapampa', '0219', '02'),
	('021905', 'Chingalpo', '0219', '02'),
	('021906', 'Huayllabamba', '0219', '02'),
	('021907', 'Quiches', '0219', '02'),
	('021908', 'Ragash', '0219', '02'),
	('021909', 'San Juan', '0219', '02'),
	('021910', 'Sicsibamba', '0219', '02'),
	('022001', 'Yungay', '0220', '02'),
	('022002', 'Cascapara', '0220', '02'),
	('022003', 'Mancos', '0220', '02'),
	('022004', 'Matacoto', '0220', '02'),
	('022005', 'Quillo', '0220', '02'),
	('022006', 'Ranrahirca', '0220', '02'),
	('022007', 'Shupluy', '0220', '02'),
	('022008', 'Yanama', '0220', '02'),
	('030101', 'Abancay', '0301', '03'),
	('030102', 'Chacoche', '0301', '03'),
	('030103', 'Circa', '0301', '03'),
	('030104', 'Curahuasi', '0301', '03'),
	('030105', 'Huanipaca', '0301', '03'),
	('030106', 'Lambrama', '0301', '03'),
	('030107', 'Pichirhua', '0301', '03'),
	('030108', 'San Pedro de Cachora', '0301', '03'),
	('030109', 'Tamburco', '0301', '03'),
	('030201', 'Andahuaylas', '0302', '03'),
	('030202', 'Andarapa', '0302', '03'),
	('030203', 'Chiara', '0302', '03'),
	('030204', 'Huancarama', '0302', '03'),
	('030205', 'Huancaray', '0302', '03'),
	('030206', 'Huayana', '0302', '03'),
	('030207', 'Kishuara', '0302', '03'),
	('030208', 'Pacobamba', '0302', '03'),
	('030209', 'Pacucha', '0302', '03'),
	('030210', 'Pampachiri', '0302', '03'),
	('030211', 'Pomacocha', '0302', '03'),
	('030212', 'San Antonio de Cachi', '0302', '03'),
	('030213', 'San Jerónimo', '0302', '03'),
	('030214', 'San Miguel de Chaccrampa', '0302', '03'),
	('030215', 'Santa María de Chicmo', '0302', '03'),
	('030216', 'Talavera', '0302', '03'),
	('030217', 'Tumay Huaraca', '0302', '03'),
	('030218', 'Turpo', '0302', '03'),
	('030219', 'Kaquiabamba', '0302', '03'),
	('030220', 'José María Arguedas', '0302', '03'),
	('030301', 'Antabamba', '0303', '03'),
	('030302', 'El Oro', '0303', '03'),
	('030303', 'Huaquirca', '0303', '03'),
	('030304', 'Juan Espinoza Medrano', '0303', '03'),
	('030305', 'Oropesa', '0303', '03'),
	('030306', 'Pachaconas', '0303', '03'),
	('030307', 'Sabaino', '0303', '03'),
	('030401', 'Chalhuanca', '0304', '03'),
	('030402', 'Capaya', '0304', '03'),
	('030403', 'Caraybamba', '0304', '03'),
	('030404', 'Chapimarca', '0304', '03'),
	('030405', 'Colcabamba', '0304', '03'),
	('030406', 'Cotaruse', '0304', '03'),
	('030407', 'Ihuayllo', '0304', '03'),
	('030408', 'Justo Apu Sahuaraura', '0304', '03'),
	('030409', 'Lucre', '0304', '03'),
	('030410', 'Pocohuanca', '0304', '03'),
	('030411', 'San Juan de Chacña', '0304', '03'),
	('030412', 'Sañayca', '0304', '03'),
	('030413', 'Soraya', '0304', '03'),
	('030414', 'Tapairihua', '0304', '03'),
	('030415', 'Tintay', '0304', '03'),
	('030416', 'Toraya', '0304', '03'),
	('030417', 'Yanaca', '0304', '03'),
	('030501', 'Tambobamba', '0305', '03'),
	('030502', 'Cotabambas', '0305', '03'),
	('030503', 'Coyllurqui', '0305', '03'),
	('030504', 'Haquira', '0305', '03'),
	('030505', 'Mara', '0305', '03'),
	('030506', 'Challhuahuacho', '0305', '03'),
	('030601', 'Chincheros', '0306', '03'),
	('030602', 'Anco_Huallo', '0306', '03'),
	('030603', 'Cocharcas', '0306', '03'),
	('030604', 'Huaccana', '0306', '03'),
	('030605', 'Ocobamba', '0306', '03'),
	('030606', 'Ongoy', '0306', '03'),
	('030607', 'Uranmarca', '0306', '03'),
	('030608', 'Ranracancha', '0306', '03'),
	('030609', 'Rocchacc', '0306', '03'),
	('030610', 'El Porvenir', '0306', '03'),
	('030611', 'Los Chankas', '0306', '03'),
	('030701', 'Chuquibambilla', '0307', '03'),
	('030702', 'Curpahuasi', '0307', '03'),
	('030703', 'Gamarra', '0307', '03'),
	('030704', 'Huayllati', '0307', '03'),
	('030705', 'Mamara', '0307', '03'),
	('030706', 'Micaela Bastidas', '0307', '03'),
	('030707', 'Pataypampa', '0307', '03'),
	('030708', 'Progreso', '0307', '03'),
	('030709', 'San Antonio', '0307', '03'),
	('030710', 'Santa Rosa', '0307', '03'),
	('030711', 'Turpay', '0307', '03'),
	('030712', 'Vilcabamba', '0307', '03'),
	('030713', 'Virundo', '0307', '03'),
	('030714', 'Curasco', '0307', '03'),
	('040101', 'Arequipa', '0401', '04'),
	('040102', 'Alto Selva Alegre', '0401', '04'),
	('040103', 'Cayma', '0401', '04'),
	('040104', 'Cerro Colorado', '0401', '04'),
	('040105', 'Characato', '0401', '04'),
	('040106', 'Chiguata', '0401', '04'),
	('040107', 'Jacobo Hunter', '0401', '04'),
	('040108', 'La Joya', '0401', '04'),
	('040109', 'Mariano Melgar', '0401', '04'),
	('040110', 'Miraflores', '0401', '04'),
	('040111', 'Mollebaya', '0401', '04'),
	('040112', 'Paucarpata', '0401', '04'),
	('040113', 'Pocsi', '0401', '04'),
	('040114', 'Polobaya', '0401', '04'),
	('040115', 'Quequeña', '0401', '04'),
	('040116', 'Sabandia', '0401', '04'),
	('040117', 'Sachaca', '0401', '04'),
	('040118', 'San Juan de Siguas', '0401', '04'),
	('040119', 'San Juan de Tarucani', '0401', '04'),
	('040120', 'Santa Isabel de Siguas', '0401', '04'),
	('040121', 'Santa Rita de Siguas', '0401', '04'),
	('040122', 'Socabaya', '0401', '04'),
	('040123', 'Tiabaya', '0401', '04'),
	('040124', 'Uchumayo', '0401', '04'),
	('040125', 'Vitor', '0401', '04'),
	('040126', 'Yanahuara', '0401', '04'),
	('040127', 'Yarabamba', '0401', '04'),
	('040128', 'Yura', '0401', '04'),
	('040129', 'José Luis Bustamante Y Rivero', '0401', '04'),
	('040201', 'Camaná', '0402', '04'),
	('040202', 'José María Quimper', '0402', '04'),
	('040203', 'Mariano Nicolás Valcárcel', '0402', '04'),
	('040204', 'Mariscal Cáceres', '0402', '04'),
	('040205', 'Nicolás de Pierola', '0402', '04'),
	('040206', 'Ocoña', '0402', '04'),
	('040207', 'Quilca', '0402', '04'),
	('040208', 'Samuel Pastor', '0402', '04'),
	('040301', 'Caravelí', '0403', '04'),
	('040302', 'Acarí', '0403', '04'),
	('040303', 'Atico', '0403', '04'),
	('040304', 'Atiquipa', '0403', '04'),
	('040305', 'Bella Unión', '0403', '04'),
	('040306', 'Cahuacho', '0403', '04'),
	('040307', 'Chala', '0403', '04'),
	('040308', 'Chaparra', '0403', '04'),
	('040309', 'Huanuhuanu', '0403', '04'),
	('040310', 'Jaqui', '0403', '04'),
	('040311', 'Lomas', '0403', '04'),
	('040312', 'Quicacha', '0403', '04'),
	('040313', 'Yauca', '0403', '04'),
	('040401', 'Aplao', '0404', '04'),
	('040402', 'Andagua', '0404', '04'),
	('040403', 'Ayo', '0404', '04'),
	('040404', 'Chachas', '0404', '04'),
	('040405', 'Chilcaymarca', '0404', '04'),
	('040406', 'Choco', '0404', '04'),
	('040407', 'Huancarqui', '0404', '04'),
	('040408', 'Machaguay', '0404', '04'),
	('040409', 'Orcopampa', '0404', '04'),
	('040410', 'Pampacolca', '0404', '04'),
	('040411', 'Tipan', '0404', '04'),
	('040412', 'Uñon', '0404', '04'),
	('040413', 'Uraca', '0404', '04'),
	('040414', 'Viraco', '0404', '04'),
	('040501', 'Chivay', '0405', '04'),
	('040502', 'Achoma', '0405', '04'),
	('040503', 'Cabanaconde', '0405', '04'),
	('040504', 'Callalli', '0405', '04'),
	('040505', 'Caylloma', '0405', '04'),
	('040506', 'Coporaque', '0405', '04'),
	('040507', 'Huambo', '0405', '04'),
	('040508', 'Huanca', '0405', '04'),
	('040509', 'Ichupampa', '0405', '04'),
	('040510', 'Lari', '0405', '04'),
	('040511', 'Lluta', '0405', '04'),
	('040512', 'Maca', '0405', '04'),
	('040513', 'Madrigal', '0405', '04'),
	('040514', 'San Antonio de Chuca', '0405', '04'),
	('040515', 'Sibayo', '0405', '04'),
	('040516', 'Tapay', '0405', '04'),
	('040517', 'Tisco', '0405', '04'),
	('040518', 'Tuti', '0405', '04'),
	('040519', 'Yanque', '0405', '04'),
	('040520', 'Majes', '0405', '04'),
	('040601', 'Chuquibamba', '0406', '04'),
	('040602', 'Andaray', '0406', '04'),
	('040603', 'Cayarani', '0406', '04'),
	('040604', 'Chichas', '0406', '04'),
	('040605', 'Iray', '0406', '04'),
	('040606', 'Río Grande', '0406', '04'),
	('040607', 'Salamanca', '0406', '04'),
	('040608', 'Yanaquihua', '0406', '04'),
	('040701', 'Mollendo', '0407', '04'),
	('040702', 'Cocachacra', '0407', '04'),
	('040703', 'Dean Valdivia', '0407', '04'),
	('040704', 'Islay', '0407', '04'),
	('040705', 'Mejia', '0407', '04'),
	('040706', 'Punta de Bombón', '0407', '04'),
	('040801', 'Cotahuasi', '0408', '04'),
	('040802', 'Alca', '0408', '04'),
	('040803', 'Charcana', '0408', '04'),
	('040804', 'Huaynacotas', '0408', '04'),
	('040805', 'Pampamarca', '0408', '04'),
	('040806', 'Puyca', '0408', '04'),
	('040807', 'Quechualla', '0408', '04'),
	('040808', 'Sayla', '0408', '04'),
	('040809', 'Tauria', '0408', '04'),
	('040810', 'Tomepampa', '0408', '04'),
	('040811', 'Toro', '0408', '04'),
	('050101', 'Ayacucho', '0501', '05'),
	('050102', 'Acocro', '0501', '05'),
	('050103', 'Acos Vinchos', '0501', '05'),
	('050104', 'Carmen Alto', '0501', '05'),
	('050105', 'Chiara', '0501', '05'),
	('050106', 'Ocros', '0501', '05'),
	('050107', 'Pacaycasa', '0501', '05'),
	('050108', 'Quinua', '0501', '05'),
	('050109', 'San José de Ticllas', '0501', '05'),
	('050110', 'San Juan Bautista', '0501', '05'),
	('050111', 'Santiago de Pischa', '0501', '05'),
	('050112', 'Socos', '0501', '05'),
	('050113', 'Tambillo', '0501', '05'),
	('050114', 'Vinchos', '0501', '05'),
	('050115', 'Jesús Nazareno', '0501', '05'),
	('050116', 'Andrés Avelino Cáceres Dorregaray', '0501', '05'),
	('050201', 'Cangallo', '0502', '05'),
	('050202', 'Chuschi', '0502', '05'),
	('050203', 'Los Morochucos', '0502', '05'),
	('050204', 'María Parado de Bellido', '0502', '05'),
	('050205', 'Paras', '0502', '05'),
	('050206', 'Totos', '0502', '05'),
	('050301', 'Sancos', '0503', '05'),
	('050302', 'Carapo', '0503', '05'),
	('050303', 'Sacsamarca', '0503', '05'),
	('050304', 'Santiago de Lucanamarca', '0503', '05'),
	('050401', 'Huanta', '0504', '05'),
	('050402', 'Ayahuanco', '0504', '05'),
	('050403', 'Huamanguilla', '0504', '05'),
	('050404', 'Iguain', '0504', '05'),
	('050405', 'Luricocha', '0504', '05'),
	('050406', 'Santillana', '0504', '05'),
	('050407', 'Sivia', '0504', '05'),
	('050408', 'Llochegua', '0504', '05'),
	('050409', 'Canayre', '0504', '05'),
	('050410', 'Uchuraccay', '0504', '05'),
	('050411', 'Pucacolpa', '0504', '05'),
	('050412', 'Chaca', '0504', '05'),
	('050501', 'San Miguel', '0505', '05'),
	('050502', 'Anco', '0505', '05'),
	('050503', 'Ayna', '0505', '05'),
	('050504', 'Chilcas', '0505', '05'),
	('050505', 'Chungui', '0505', '05'),
	('050506', 'Luis Carranza', '0505', '05'),
	('050507', 'Santa Rosa', '0505', '05'),
	('050508', 'Tambo', '0505', '05'),
	('050509', 'Samugari', '0505', '05'),
	('050510', 'Anchihuay', '0505', '05'),
	('050511', 'Oronccoy', '0505', '05'),
	('050601', 'Puquio', '0506', '05'),
	('050602', 'Aucara', '0506', '05'),
	('050603', 'Cabana', '0506', '05'),
	('050604', 'Carmen Salcedo', '0506', '05'),
	('050605', 'Chaviña', '0506', '05'),
	('050606', 'Chipao', '0506', '05'),
	('050607', 'Huac-Huas', '0506', '05'),
	('050608', 'Laramate', '0506', '05'),
	('050609', 'Leoncio Prado', '0506', '05'),
	('050610', 'Llauta', '0506', '05'),
	('050611', 'Lucanas', '0506', '05'),
	('050612', 'Ocaña', '0506', '05'),
	('050613', 'Otoca', '0506', '05'),
	('050614', 'Saisa', '0506', '05'),
	('050615', 'San Cristóbal', '0506', '05'),
	('050616', 'San Juan', '0506', '05'),
	('050617', 'San Pedro', '0506', '05'),
	('050618', 'San Pedro de Palco', '0506', '05'),
	('050619', 'Sancos', '0506', '05'),
	('050620', 'Santa Ana de Huaycahuacho', '0506', '05'),
	('050621', 'Santa Lucia', '0506', '05'),
	('050701', 'Coracora', '0507', '05'),
	('050702', 'Chumpi', '0507', '05'),
	('050703', 'Coronel Castañeda', '0507', '05'),
	('050704', 'Pacapausa', '0507', '05'),
	('050705', 'Pullo', '0507', '05'),
	('050706', 'Puyusca', '0507', '05'),
	('050707', 'San Francisco de Ravacayco', '0507', '05'),
	('050708', 'Upahuacho', '0507', '05'),
	('050801', 'Pausa', '0508', '05'),
	('050802', 'Colta', '0508', '05'),
	('050803', 'Corculla', '0508', '05'),
	('050804', 'Lampa', '0508', '05'),
	('050805', 'Marcabamba', '0508', '05'),
	('050806', 'Oyolo', '0508', '05'),
	('050807', 'Pararca', '0508', '05'),
	('050808', 'San Javier de Alpabamba', '0508', '05'),
	('050809', 'San José de Ushua', '0508', '05'),
	('050810', 'Sara Sara', '0508', '05'),
	('050901', 'Querobamba', '0509', '05'),
	('050902', 'Belén', '0509', '05'),
	('050903', 'Chalcos', '0509', '05'),
	('050904', 'Chilcayoc', '0509', '05'),
	('050905', 'Huacaña', '0509', '05'),
	('050906', 'Morcolla', '0509', '05'),
	('050907', 'Paico', '0509', '05'),
	('050908', 'San Pedro de Larcay', '0509', '05'),
	('050909', 'San Salvador de Quije', '0509', '05'),
	('050910', 'Santiago de Paucaray', '0509', '05'),
	('050911', 'Soras', '0509', '05'),
	('051001', 'Huancapi', '0510', '05'),
	('051002', 'Alcamenca', '0510', '05'),
	('051003', 'Apongo', '0510', '05'),
	('051004', 'Asquipata', '0510', '05'),
	('051005', 'Canaria', '0510', '05'),
	('051006', 'Cayara', '0510', '05'),
	('051007', 'Colca', '0510', '05'),
	('051008', 'Huamanquiquia', '0510', '05'),
	('051009', 'Huancaraylla', '0510', '05'),
	('051010', 'Hualla', '0510', '05'),
	('051011', 'Sarhua', '0510', '05'),
	('051012', 'Vilcanchos', '0510', '05'),
	('051101', 'Vilcas Huaman', '0511', '05'),
	('051102', 'Accomarca', '0511', '05'),
	('051103', 'Carhuanca', '0511', '05'),
	('051104', 'Concepción', '0511', '05'),
	('051105', 'Huambalpa', '0511', '05'),
	('051106', 'Independencia', '0511', '05'),
	('051107', 'Saurama', '0511', '05'),
	('051108', 'Vischongo', '0511', '05'),
	('060101', 'Cajamarca', '0601', '06'),
	('060102', 'Asunción', '0601', '06'),
	('060103', 'Chetilla', '0601', '06'),
	('060104', 'Cospan', '0601', '06'),
	('060105', 'Encañada', '0601', '06'),
	('060106', 'Jesús', '0601', '06'),
	('060107', 'Llacanora', '0601', '06'),
	('060108', 'Los Baños del Inca', '0601', '06'),
	('060109', 'Magdalena', '0601', '06'),
	('060110', 'Matara', '0601', '06'),
	('060111', 'Namora', '0601', '06'),
	('060112', 'San Juan', '0601', '06'),
	('060201', 'Cajabamba', '0602', '06'),
	('060202', 'Cachachi', '0602', '06'),
	('060203', 'Condebamba', '0602', '06'),
	('060204', 'Sitacocha', '0602', '06'),
	('060301', 'Celendín', '0603', '06'),
	('060302', 'Chumuch', '0603', '06'),
	('060303', 'Cortegana', '0603', '06'),
	('060304', 'Huasmin', '0603', '06'),
	('060305', 'Jorge Chávez', '0603', '06'),
	('060306', 'José Gálvez', '0603', '06'),
	('060307', 'Miguel Iglesias', '0603', '06'),
	('060308', 'Oxamarca', '0603', '06'),
	('060309', 'Sorochuco', '0603', '06'),
	('060310', 'Sucre', '0603', '06'),
	('060311', 'Utco', '0603', '06'),
	('060312', 'La Libertad de Pallan', '0603', '06'),
	('060401', 'Chota', '0604', '06'),
	('060402', 'Anguia', '0604', '06'),
	('060403', 'Chadin', '0604', '06'),
	('060404', 'Chiguirip', '0604', '06'),
	('060405', 'Chimban', '0604', '06'),
	('060406', 'Choropampa', '0604', '06'),
	('060407', 'Cochabamba', '0604', '06'),
	('060408', 'Conchan', '0604', '06'),
	('060409', 'Huambos', '0604', '06'),
	('060410', 'Lajas', '0604', '06'),
	('060411', 'Llama', '0604', '06'),
	('060412', 'Miracosta', '0604', '06'),
	('060413', 'Paccha', '0604', '06'),
	('060414', 'Pion', '0604', '06'),
	('060415', 'Querocoto', '0604', '06'),
	('060416', 'San Juan de Licupis', '0604', '06'),
	('060417', 'Tacabamba', '0604', '06'),
	('060418', 'Tocmoche', '0604', '06'),
	('060419', 'Chalamarca', '0604', '06'),
	('060501', 'Contumaza', '0605', '06'),
	('060502', 'Chilete', '0605', '06'),
	('060503', 'Cupisnique', '0605', '06'),
	('060504', 'Guzmango', '0605', '06'),
	('060505', 'San Benito', '0605', '06'),
	('060506', 'Santa Cruz de Toledo', '0605', '06'),
	('060507', 'Tantarica', '0605', '06'),
	('060508', 'Yonan', '0605', '06'),
	('060601', 'Cutervo', '0606', '06'),
	('060602', 'Callayuc', '0606', '06'),
	('060603', 'Choros', '0606', '06'),
	('060604', 'Cujillo', '0606', '06'),
	('060605', 'La Ramada', '0606', '06'),
	('060606', 'Pimpingos', '0606', '06'),
	('060607', 'Querocotillo', '0606', '06'),
	('060608', 'San Andrés de Cutervo', '0606', '06'),
	('060609', 'San Juan de Cutervo', '0606', '06'),
	('060610', 'San Luis de Lucma', '0606', '06'),
	('060611', 'Santa Cruz', '0606', '06'),
	('060612', 'Santo Domingo de la Capilla', '0606', '06'),
	('060613', 'Santo Tomas', '0606', '06'),
	('060614', 'Socota', '0606', '06'),
	('060615', 'Toribio Casanova', '0606', '06'),
	('060701', 'Bambamarca', '0607', '06'),
	('060702', 'Chugur', '0607', '06'),
	('060703', 'Hualgayoc', '0607', '06'),
	('060801', 'Jaén', '0608', '06'),
	('060802', 'Bellavista', '0608', '06'),
	('060803', 'Chontali', '0608', '06'),
	('060804', 'Colasay', '0608', '06'),
	('060805', 'Huabal', '0608', '06'),
	('060806', 'Las Pirias', '0608', '06'),
	('060807', 'Pomahuaca', '0608', '06'),
	('060808', 'Pucara', '0608', '06'),
	('060809', 'Sallique', '0608', '06'),
	('060810', 'San Felipe', '0608', '06'),
	('060811', 'San José del Alto', '0608', '06'),
	('060812', 'Santa Rosa', '0608', '06'),
	('060901', 'San Ignacio', '0609', '06'),
	('060902', 'Chirinos', '0609', '06'),
	('060903', 'Huarango', '0609', '06'),
	('060904', 'La Coipa', '0609', '06'),
	('060905', 'Namballe', '0609', '06'),
	('060906', 'San José de Lourdes', '0609', '06'),
	('060907', 'Tabaconas', '0609', '06'),
	('061001', 'Pedro Gálvez', '0610', '06'),
	('061002', 'Chancay', '0610', '06'),
	('061003', 'Eduardo Villanueva', '0610', '06'),
	('061004', 'Gregorio Pita', '0610', '06'),
	('061005', 'Ichocan', '0610', '06'),
	('061006', 'José Manuel Quiroz', '0610', '06'),
	('061007', 'José Sabogal', '0610', '06'),
	('061101', 'San Miguel', '0611', '06'),
	('061102', 'Bolívar', '0611', '06'),
	('061103', 'Calquis', '0611', '06'),
	('061104', 'Catilluc', '0611', '06'),
	('061105', 'El Prado', '0611', '06'),
	('061106', 'La Florida', '0611', '06'),
	('061107', 'Llapa', '0611', '06'),
	('061108', 'Nanchoc', '0611', '06'),
	('061109', 'Niepos', '0611', '06'),
	('061110', 'San Gregorio', '0611', '06'),
	('061111', 'San Silvestre de Cochan', '0611', '06'),
	('061112', 'Tongod', '0611', '06'),
	('061113', 'Unión Agua Blanca', '0611', '06'),
	('061201', 'San Pablo', '0612', '06'),
	('061202', 'San Bernardino', '0612', '06'),
	('061203', 'San Luis', '0612', '06'),
	('061204', 'Tumbaden', '0612', '06'),
	('061301', 'Santa Cruz', '0613', '06'),
	('061302', 'Andabamba', '0613', '06'),
	('061303', 'Catache', '0613', '06'),
	('061304', 'Chancaybaños', '0613', '06'),
	('061305', 'La Esperanza', '0613', '06'),
	('061306', 'Ninabamba', '0613', '06'),
	('061307', 'Pulan', '0613', '06'),
	('061308', 'Saucepampa', '0613', '06'),
	('061309', 'Sexi', '0613', '06'),
	('061310', 'Uticyacu', '0613', '06'),
	('061311', 'Yauyucan', '0613', '06'),
	('070101', 'Callao', '0701', '07'),
	('070102', 'Bellavista', '0701', '07'),
	('070103', 'Carmen de la Legua Reynoso', '0701', '07'),
	('070104', 'La Perla', '0701', '07'),
	('070105', 'La Punta', '0701', '07'),
	('070106', 'Ventanilla', '0701', '07'),
	('070107', 'Mi Perú', '0701', '07'),
	('080101', 'Cusco', '0801', '08'),
	('080102', 'Ccorca', '0801', '08'),
	('080103', 'Poroy', '0801', '08'),
	('080104', 'San Jerónimo', '0801', '08'),
	('080105', 'San Sebastian', '0801', '08'),
	('080106', 'Santiago', '0801', '08'),
	('080107', 'Saylla', '0801', '08'),
	('080108', 'Wanchaq', '0801', '08'),
	('080201', 'Acomayo', '0802', '08'),
	('080202', 'Acopia', '0802', '08'),
	('080203', 'Acos', '0802', '08'),
	('080204', 'Mosoc Llacta', '0802', '08'),
	('080205', 'Pomacanchi', '0802', '08'),
	('080206', 'Rondocan', '0802', '08'),
	('080207', 'Sangarara', '0802', '08'),
	('080301', 'Anta', '0803', '08'),
	('080302', 'Ancahuasi', '0803', '08'),
	('080303', 'Cachimayo', '0803', '08'),
	('080304', 'Chinchaypujio', '0803', '08'),
	('080305', 'Huarocondo', '0803', '08'),
	('080306', 'Limatambo', '0803', '08'),
	('080307', 'Mollepata', '0803', '08'),
	('080308', 'Pucyura', '0803', '08'),
	('080309', 'Zurite', '0803', '08'),
	('080401', 'Calca', '0804', '08'),
	('080402', 'Coya', '0804', '08'),
	('080403', 'Lamay', '0804', '08'),
	('080404', 'Lares', '0804', '08'),
	('080405', 'Pisac', '0804', '08'),
	('080406', 'San Salvador', '0804', '08'),
	('080407', 'Taray', '0804', '08'),
	('080408', 'Yanatile', '0804', '08'),
	('080501', 'Yanaoca', '0805', '08'),
	('080502', 'Checca', '0805', '08'),
	('080503', 'Kunturkanki', '0805', '08'),
	('080504', 'Langui', '0805', '08'),
	('080505', 'Layo', '0805', '08'),
	('080506', 'Pampamarca', '0805', '08'),
	('080507', 'Quehue', '0805', '08'),
	('080508', 'Tupac Amaru', '0805', '08'),
	('080601', 'Sicuani', '0806', '08'),
	('080602', 'Checacupe', '0806', '08'),
	('080603', 'Combapata', '0806', '08'),
	('080604', 'Marangani', '0806', '08'),
	('080605', 'Pitumarca', '0806', '08'),
	('080606', 'San Pablo', '0806', '08'),
	('080607', 'San Pedro', '0806', '08'),
	('080608', 'Tinta', '0806', '08'),
	('080701', 'Santo Tomas', '0807', '08'),
	('080702', 'Capacmarca', '0807', '08'),
	('080703', 'Chamaca', '0807', '08'),
	('080704', 'Colquemarca', '0807', '08'),
	('080705', 'Livitaca', '0807', '08'),
	('080706', 'Llusco', '0807', '08'),
	('080707', 'Quiñota', '0807', '08'),
	('080708', 'Velille', '0807', '08'),
	('080801', 'Espinar', '0808', '08'),
	('080802', 'Condoroma', '0808', '08'),
	('080803', 'Coporaque', '0808', '08'),
	('080804', 'Ocoruro', '0808', '08'),
	('080805', 'Pallpata', '0808', '08'),
	('080806', 'Pichigua', '0808', '08'),
	('080807', 'Suyckutambo', '0808', '08'),
	('080808', 'Alto Pichigua', '0808', '08'),
	('080901', 'Santa Ana', '0809', '08'),
	('080902', 'Echarate', '0809', '08'),
	('080903', 'Huayopata', '0809', '08'),
	('080904', 'Maranura', '0809', '08'),
	('080905', 'Ocobamba', '0809', '08'),
	('080906', 'Quellouno', '0809', '08'),
	('080907', 'Kimbiri', '0809', '08'),
	('080908', 'Santa Teresa', '0809', '08'),
	('080909', 'Vilcabamba', '0809', '08'),
	('080910', 'Pichari', '0809', '08'),
	('080911', 'Inkawasi', '0809', '08'),
	('080912', 'Villa Virgen', '0809', '08'),
	('080913', 'Villa Kintiarina', '0809', '08'),
	('080914', 'Megantoni', '0809', '08'),
	('081001', 'Paruro', '0810', '08'),
	('081002', 'Accha', '0810', '08'),
	('081003', 'Ccapi', '0810', '08'),
	('081004', 'Colcha', '0810', '08'),
	('081005', 'Huanoquite', '0810', '08'),
	('081006', 'Omachaç', '0810', '08'),
	('081007', 'Paccaritambo', '0810', '08'),
	('081008', 'Pillpinto', '0810', '08'),
	('081009', 'Yaurisque', '0810', '08'),
	('081101', 'Paucartambo', '0811', '08'),
	('081102', 'Caicay', '0811', '08'),
	('081103', 'Challabamba', '0811', '08'),
	('081104', 'Colquepata', '0811', '08'),
	('081105', 'Huancarani', '0811', '08'),
	('081106', 'Kosñipata', '0811', '08'),
	('081201', 'Urcos', '0812', '08'),
	('081202', 'Andahuaylillas', '0812', '08'),
	('081203', 'Camanti', '0812', '08'),
	('081204', 'Ccarhuayo', '0812', '08'),
	('081205', 'Ccatca', '0812', '08'),
	('081206', 'Cusipata', '0812', '08'),
	('081207', 'Huaro', '0812', '08'),
	('081208', 'Lucre', '0812', '08'),
	('081209', 'Marcapata', '0812', '08'),
	('081210', 'Ocongate', '0812', '08'),
	('081211', 'Oropesa', '0812', '08'),
	('081212', 'Quiquijana', '0812', '08'),
	('081301', 'Urubamba', '0813', '08'),
	('081302', 'Chinchero', '0813', '08'),
	('081303', 'Huayllabamba', '0813', '08'),
	('081304', 'Machupicchu', '0813', '08'),
	('081305', 'Maras', '0813', '08'),
	('081306', 'Ollantaytambo', '0813', '08'),
	('081307', 'Yucay', '0813', '08'),
	('090101', 'Huancavelica', '0901', '09'),
	('090102', 'Acobambilla', '0901', '09'),
	('090103', 'Acoria', '0901', '09'),
	('090104', 'Conayca', '0901', '09'),
	('090105', 'Cuenca', '0901', '09'),
	('090106', 'Huachocolpa', '0901', '09'),
	('090107', 'Huayllahuara', '0901', '09'),
	('090108', 'Izcuchaca', '0901', '09'),
	('090109', 'Laria', '0901', '09'),
	('090110', 'Manta', '0901', '09'),
	('090111', 'Mariscal Cáceres', '0901', '09'),
	('090112', 'Moya', '0901', '09'),
	('090113', 'Nuevo Occoro', '0901', '09'),
	('090114', 'Palca', '0901', '09'),
	('090115', 'Pilchaca', '0901', '09'),
	('090116', 'Vilca', '0901', '09'),
	('090117', 'Yauli', '0901', '09'),
	('090118', 'Ascensión', '0901', '09'),
	('090119', 'Huando', '0901', '09'),
	('090201', 'Acobamba', '0902', '09'),
	('090202', 'Andabamba', '0902', '09'),
	('090203', 'Anta', '0902', '09'),
	('090204', 'Caja', '0902', '09'),
	('090205', 'Marcas', '0902', '09'),
	('090206', 'Paucara', '0902', '09'),
	('090207', 'Pomacocha', '0902', '09'),
	('090208', 'Rosario', '0902', '09'),
	('090301', 'Lircay', '0903', '09'),
	('090302', 'Anchonga', '0903', '09'),
	('090303', 'Callanmarca', '0903', '09'),
	('090304', 'Ccochaccasa', '0903', '09'),
	('090305', 'Chincho', '0903', '09'),
	('090306', 'Congalla', '0903', '09'),
	('090307', 'Huanca-Huanca', '0903', '09'),
	('090308', 'Huayllay Grande', '0903', '09'),
	('090309', 'Julcamarca', '0903', '09'),
	('090310', 'San Antonio de Antaparco', '0903', '09'),
	('090311', 'Santo Tomas de Pata', '0903', '09'),
	('090312', 'Secclla', '0903', '09'),
	('090401', 'Castrovirreyna', '0904', '09'),
	('090402', 'Arma', '0904', '09'),
	('090403', 'Aurahua', '0904', '09'),
	('090404', 'Capillas', '0904', '09'),
	('090405', 'Chupamarca', '0904', '09'),
	('090406', 'Cocas', '0904', '09'),
	('090407', 'Huachos', '0904', '09'),
	('090408', 'Huamatambo', '0904', '09'),
	('090409', 'Mollepampa', '0904', '09'),
	('090410', 'San Juan', '0904', '09'),
	('090411', 'Santa Ana', '0904', '09'),
	('090412', 'Tantara', '0904', '09'),
	('090413', 'Ticrapo', '0904', '09'),
	('090501', 'Churcampa', '0905', '09'),
	('090502', 'Anco', '0905', '09'),
	('090503', 'Chinchihuasi', '0905', '09'),
	('090504', 'El Carmen', '0905', '09'),
	('090505', 'La Merced', '0905', '09'),
	('090506', 'Locroja', '0905', '09'),
	('090507', 'Paucarbamba', '0905', '09'),
	('090508', 'San Miguel de Mayocc', '0905', '09'),
	('090509', 'San Pedro de Coris', '0905', '09'),
	('090510', 'Pachamarca', '0905', '09'),
	('090511', 'Cosme', '0905', '09'),
	('090601', 'Huaytara', '0906', '09'),
	('090602', 'Ayavi', '0906', '09'),
	('090603', 'Córdova', '0906', '09'),
	('090604', 'Huayacundo Arma', '0906', '09'),
	('090605', 'Laramarca', '0906', '09'),
	('090606', 'Ocoyo', '0906', '09'),
	('090607', 'Pilpichaca', '0906', '09'),
	('090608', 'Querco', '0906', '09'),
	('090609', 'Quito-Arma', '0906', '09'),
	('090610', 'San Antonio de Cusicancha', '0906', '09'),
	('090611', 'San Francisco de Sangayaico', '0906', '09'),
	('090612', 'San Isidro', '0906', '09'),
	('090613', 'Santiago de Chocorvos', '0906', '09'),
	('090614', 'Santiago de Quirahuara', '0906', '09'),
	('090615', 'Santo Domingo de Capillas', '0906', '09'),
	('090616', 'Tambo', '0906', '09'),
	('090701', 'Pampas', '0907', '09'),
	('090702', 'Acostambo', '0907', '09'),
	('090703', 'Acraquia', '0907', '09'),
	('090704', 'Ahuaycha', '0907', '09'),
	('090705', 'Colcabamba', '0907', '09'),
	('090706', 'Daniel Hernández', '0907', '09'),
	('090707', 'Huachocolpa', '0907', '09'),
	('090709', 'Huaribamba', '0907', '09'),
	('090710', 'Ñahuimpuquio', '0907', '09'),
	('090711', 'Pazos', '0907', '09'),
	('090713', 'Quishuar', '0907', '09'),
	('090714', 'Salcabamba', '0907', '09'),
	('090715', 'Salcahuasi', '0907', '09'),
	('090716', 'San Marcos de Rocchac', '0907', '09'),
	('090717', 'Surcubamba', '0907', '09'),
	('090718', 'Tintay Puncu', '0907', '09'),
	('090719', 'Quichuas', '0907', '09'),
	('090720', 'Andaymarca', '0907', '09'),
	('090721', 'Roble', '0907', '09'),
	('090722', 'Pichos', '0907', '09'),
	('090723', 'Santiago de Tucuma', '0907', '09'),
	('100101', 'Huanuco', '1001', '10'),
	('100102', 'Amarilis', '1001', '10'),
	('100103', 'Chinchao', '1001', '10'),
	('100104', 'Churubamba', '1001', '10'),
	('100105', 'Margos', '1001', '10'),
	('100106', 'Quisqui (Kichki)', '1001', '10'),
	('100107', 'San Francisco de Cayran', '1001', '10'),
	('100108', 'San Pedro de Chaulan', '1001', '10'),
	('100109', 'Santa María del Valle', '1001', '10'),
	('100110', 'Yarumayo', '1001', '10'),
	('100111', 'Pillco Marca', '1001', '10'),
	('100112', 'Yacus', '1001', '10'),
	('100113', 'San Pablo de Pillao', '1001', '10'),
	('100201', 'Ambo', '1002', '10'),
	('100202', 'Cayna', '1002', '10'),
	('100203', 'Colpas', '1002', '10'),
	('100204', 'Conchamarca', '1002', '10'),
	('100205', 'Huacar', '1002', '10'),
	('100206', 'San Francisco', '1002', '10'),
	('100207', 'San Rafael', '1002', '10'),
	('100208', 'Tomay Kichwa', '1002', '10'),
	('100301', 'La Unión', '1003', '10'),
	('100307', 'Chuquis', '1003', '10'),
	('100311', 'Marías', '1003', '10'),
	('100313', 'Pachas', '1003', '10'),
	('100316', 'Quivilla', '1003', '10'),
	('100317', 'Ripan', '1003', '10'),
	('100321', 'Shunqui', '1003', '10'),
	('100322', 'Sillapata', '1003', '10'),
	('100323', 'Yanas', '1003', '10'),
	('100401', 'Huacaybamba', '1004', '10'),
	('100402', 'Canchabamba', '1004', '10'),
	('100403', 'Cochabamba', '1004', '10'),
	('100404', 'Pinra', '1004', '10'),
	('100501', 'Llata', '1005', '10'),
	('100502', 'Arancay', '1005', '10'),
	('100503', 'Chavín de Pariarca', '1005', '10'),
	('100504', 'Jacas Grande', '1005', '10'),
	('100505', 'Jircan', '1005', '10'),
	('100506', 'Miraflores', '1005', '10'),
	('100507', 'Monzón', '1005', '10'),
	('100508', 'Punchao', '1005', '10'),
	('100509', 'Puños', '1005', '10'),
	('100510', 'Singa', '1005', '10'),
	('100511', 'Tantamayo', '1005', '10'),
	('100601', 'Rupa-Rupa', '1006', '10'),
	('100602', 'Daniel Alomía Robles', '1006', '10'),
	('100603', 'Hermílio Valdizan', '1006', '10'),
	('100604', 'José Crespo y Castillo', '1006', '10'),
	('100605', 'Luyando', '1006', '10'),
	('100606', 'Mariano Damaso Beraun', '1006', '10'),
	('100607', 'Pucayacu', '1006', '10'),
	('100608', 'Castillo Grande', '1006', '10'),
	('100609', 'Pueblo Nuevo', '1006', '10'),
	('100610', 'Santo Domingo de Anda', '1006', '10'),
	('100701', 'Huacrachuco', '1007', '10'),
	('100702', 'Cholon', '1007', '10'),
	('100703', 'San Buenaventura', '1007', '10'),
	('100704', 'La Morada', '1007', '10'),
	('100705', 'Santa Rosa de Alto Yanajanca', '1007', '10'),
	('100801', 'Panao', '1008', '10'),
	('100802', 'Chaglla', '1008', '10'),
	('100803', 'Molino', '1008', '10'),
	('100804', 'Umari', '1008', '10'),
	('100901', 'Puerto Inca', '1009', '10'),
	('100902', 'Codo del Pozuzo', '1009', '10'),
	('100903', 'Honoria', '1009', '10'),
	('100904', 'Tournavista', '1009', '10'),
	('100905', 'Yuyapichis', '1009', '10'),
	('101001', 'Jesús', '1010', '10'),
	('101002', 'Baños', '1010', '10'),
	('101003', 'Jivia', '1010', '10'),
	('101004', 'Queropalca', '1010', '10'),
	('101005', 'Rondos', '1010', '10'),
	('101006', 'San Francisco de Asís', '1010', '10'),
	('101007', 'San Miguel de Cauri', '1010', '10'),
	('101101', 'Chavinillo', '1011', '10'),
	('101102', 'Cahuac', '1011', '10'),
	('101103', 'Chacabamba', '1011', '10'),
	('101104', 'Aparicio Pomares', '1011', '10'),
	('101105', 'Jacas Chico', '1011', '10'),
	('101106', 'Obas', '1011', '10'),
	('101107', 'Pampamarca', '1011', '10'),
	('101108', 'Choras', '1011', '10'),
	('110101', 'Ica', '1101', '11'),
	('110102', 'La Tinguiña', '1101', '11'),
	('110103', 'Los Aquijes', '1101', '11'),
	('110104', 'Ocucaje', '1101', '11'),
	('110105', 'Pachacutec', '1101', '11'),
	('110106', 'Parcona', '1101', '11'),
	('110107', 'Pueblo Nuevo', '1101', '11'),
	('110108', 'Salas', '1101', '11'),
	('110109', 'San José de Los Molinos', '1101', '11'),
	('110110', 'San Juan Bautista', '1101', '11'),
	('110111', 'Santiago', '1101', '11'),
	('110112', 'Subtanjalla', '1101', '11'),
	('110113', 'Tate', '1101', '11'),
	('110114', 'Yauca del Rosario', '1101', '11'),
	('110201', 'Chincha Alta', '1102', '11'),
	('110202', 'Alto Laran', '1102', '11'),
	('110203', 'Chavin', '1102', '11'),
	('110204', 'Chincha Baja', '1102', '11'),
	('110205', 'El Carmen', '1102', '11'),
	('110206', 'Grocio Prado', '1102', '11'),
	('110207', 'Pueblo Nuevo', '1102', '11'),
	('110208', 'San Juan de Yanac', '1102', '11'),
	('110209', 'San Pedro de Huacarpana', '1102', '11'),
	('110210', 'Sunampe', '1102', '11'),
	('110211', 'Tambo de Mora', '1102', '11'),
	('110301', 'Nasca', '1103', '11'),
	('110302', 'Changuillo', '1103', '11'),
	('110303', 'El Ingenio', '1103', '11'),
	('110304', 'Marcona', '1103', '11'),
	('110305', 'Vista Alegre', '1103', '11'),
	('110401', 'Palpa', '1104', '11'),
	('110402', 'Llipata', '1104', '11'),
	('110403', 'Río Grande', '1104', '11'),
	('110404', 'Santa Cruz', '1104', '11'),
	('110405', 'Tibillo', '1104', '11'),
	('110501', 'Pisco', '1105', '11'),
	('110502', 'Huancano', '1105', '11'),
	('110503', 'Humay', '1105', '11'),
	('110504', 'Independencia', '1105', '11'),
	('110505', 'Paracas', '1105', '11'),
	('110506', 'San Andrés', '1105', '11'),
	('110507', 'San Clemente', '1105', '11'),
	('110508', 'Tupac Amaru Inca', '1105', '11'),
	('120101', 'Huancayo', '1201', '12'),
	('120104', 'Carhuacallanga', '1201', '12'),
	('120105', 'Chacapampa', '1201', '12'),
	('120106', 'Chicche', '1201', '12'),
	('120107', 'Chilca', '1201', '12'),
	('120108', 'Chongos Alto', '1201', '12'),
	('120111', 'Chupuro', '1201', '12'),
	('120112', 'Colca', '1201', '12'),
	('120113', 'Cullhuas', '1201', '12'),
	('120114', 'El Tambo', '1201', '12'),
	('120116', 'Huacrapuquio', '1201', '12'),
	('120117', 'Hualhuas', '1201', '12'),
	('120119', 'Huancan', '1201', '12'),
	('120120', 'Huasicancha', '1201', '12'),
	('120121', 'Huayucachi', '1201', '12'),
	('120122', 'Ingenio', '1201', '12'),
	('120124', 'Pariahuanca', '1201', '12'),
	('120125', 'Pilcomayo', '1201', '12'),
	('120126', 'Pucara', '1201', '12'),
	('120127', 'Quichuay', '1201', '12'),
	('120128', 'Quilcas', '1201', '12'),
	('120129', 'San Agustín', '1201', '12'),
	('120130', 'San Jerónimo de Tunan', '1201', '12'),
	('120132', 'Saño', '1201', '12'),
	('120133', 'Sapallanga', '1201', '12'),
	('120134', 'Sicaya', '1201', '12'),
	('120135', 'Santo Domingo de Acobamba', '1201', '12'),
	('120136', 'Viques', '1201', '12'),
	('120201', 'Concepción', '1202', '12'),
	('120202', 'Aco', '1202', '12'),
	('120203', 'Andamarca', '1202', '12'),
	('120204', 'Chambara', '1202', '12'),
	('120205', 'Cochas', '1202', '12'),
	('120206', 'Comas', '1202', '12'),
	('120207', 'Heroínas Toledo', '1202', '12'),
	('120208', 'Manzanares', '1202', '12'),
	('120209', 'Mariscal Castilla', '1202', '12'),
	('120210', 'Matahuasi', '1202', '12'),
	('120211', 'Mito', '1202', '12'),
	('120212', 'Nueve de Julio', '1202', '12'),
	('120213', 'Orcotuna', '1202', '12'),
	('120214', 'San José de Quero', '1202', '12'),
	('120215', 'Santa Rosa de Ocopa', '1202', '12'),
	('120301', 'Chanchamayo', '1203', '12'),
	('120302', 'Perene', '1203', '12'),
	('120303', 'Pichanaqui', '1203', '12'),
	('120304', 'San Luis de Shuaro', '1203', '12'),
	('120305', 'San Ramón', '1203', '12'),
	('120306', 'Vitoc', '1203', '12'),
	('120401', 'Jauja', '1204', '12'),
	('120402', 'Acolla', '1204', '12'),
	('120403', 'Apata', '1204', '12'),
	('120404', 'Ataura', '1204', '12'),
	('120405', 'Canchayllo', '1204', '12'),
	('120406', 'Curicaca', '1204', '12'),
	('120407', 'El Mantaro', '1204', '12'),
	('120408', 'Huamali', '1204', '12'),
	('120409', 'Huaripampa', '1204', '12'),
	('120410', 'Huertas', '1204', '12'),
	('120411', 'Janjaillo', '1204', '12'),
	('120412', 'Julcán', '1204', '12'),
	('120413', 'Leonor Ordóñez', '1204', '12'),
	('120414', 'Llocllapampa', '1204', '12'),
	('120415', 'Marco', '1204', '12'),
	('120416', 'Masma', '1204', '12'),
	('120417', 'Masma Chicche', '1204', '12'),
	('120418', 'Molinos', '1204', '12'),
	('120419', 'Monobamba', '1204', '12'),
	('120420', 'Muqui', '1204', '12'),
	('120421', 'Muquiyauyo', '1204', '12'),
	('120422', 'Paca', '1204', '12'),
	('120423', 'Paccha', '1204', '12'),
	('120424', 'Pancan', '1204', '12'),
	('120425', 'Parco', '1204', '12'),
	('120426', 'Pomacancha', '1204', '12'),
	('120427', 'Ricran', '1204', '12'),
	('120428', 'San Lorenzo', '1204', '12'),
	('120429', 'San Pedro de Chunan', '1204', '12'),
	('120430', 'Sausa', '1204', '12'),
	('120431', 'Sincos', '1204', '12'),
	('120432', 'Tunan Marca', '1204', '12'),
	('120433', 'Yauli', '1204', '12'),
	('120434', 'Yauyos', '1204', '12'),
	('120501', 'Junin', '1205', '12'),
	('120502', 'Carhuamayo', '1205', '12'),
	('120503', 'Ondores', '1205', '12'),
	('120504', 'Ulcumayo', '1205', '12'),
	('120601', 'Satipo', '1206', '12'),
	('120602', 'Coviriali', '1206', '12'),
	('120603', 'Llaylla', '1206', '12'),
	('120604', 'Mazamari', '1206', '12'),
	('120605', 'Pampa Hermosa', '1206', '12'),
	('120606', 'Pangoa', '1206', '12'),
	('120607', 'Río Negro', '1206', '12'),
	('120608', 'Río Tambo', '1206', '12'),
	('120609', 'Vizcatan del Ene', '1206', '12'),
	('120701', 'Tarma', '1207', '12'),
	('120702', 'Acobamba', '1207', '12'),
	('120703', 'Huaricolca', '1207', '12'),
	('120704', 'Huasahuasi', '1207', '12'),
	('120705', 'La Unión', '1207', '12'),
	('120706', 'Palca', '1207', '12'),
	('120707', 'Palcamayo', '1207', '12'),
	('120708', 'San Pedro de Cajas', '1207', '12'),
	('120709', 'Tapo', '1207', '12'),
	('120801', 'La Oroya', '1208', '12'),
	('120802', 'Chacapalpa', '1208', '12'),
	('120803', 'Huay-Huay', '1208', '12'),
	('120804', 'Marcapomacocha', '1208', '12'),
	('120805', 'Morococha', '1208', '12'),
	('120806', 'Paccha', '1208', '12'),
	('120807', 'Santa Bárbara de Carhuacayan', '1208', '12'),
	('120808', 'Santa Rosa de Sacco', '1208', '12'),
	('120809', 'Suitucancha', '1208', '12'),
	('120810', 'Yauli', '1208', '12'),
	('120901', 'Chupaca', '1209', '12'),
	('120902', 'Ahuac', '1209', '12'),
	('120903', 'Chongos Bajo', '1209', '12'),
	('120904', 'Huachac', '1209', '12'),
	('120905', 'Huamancaca Chico', '1209', '12'),
	('120906', 'San Juan de Iscos', '1209', '12'),
	('120907', 'San Juan de Jarpa', '1209', '12'),
	('120908', 'Tres de Diciembre', '1209', '12'),
	('120909', 'Yanacancha', '1209', '12'),
	('130101', 'Trujillo', '1301', '13'),
	('130102', 'El Porvenir', '1301', '13'),
	('130103', 'Florencia de Mora', '1301', '13'),
	('130104', 'Huanchaco', '1301', '13'),
	('130105', 'La Esperanza', '1301', '13'),
	('130106', 'Laredo', '1301', '13'),
	('130107', 'Moche', '1301', '13'),
	('130108', 'Poroto', '1301', '13'),
	('130109', 'Salaverry', '1301', '13'),
	('130110', 'Simbal', '1301', '13'),
	('130111', 'Victor Larco Herrera', '1301', '13'),
	('130201', 'Ascope', '1302', '13'),
	('130202', 'Chicama', '1302', '13'),
	('130203', 'Chocope', '1302', '13'),
	('130204', 'Magdalena de Cao', '1302', '13'),
	('130205', 'Paijan', '1302', '13'),
	('130206', 'Rázuri', '1302', '13'),
	('130207', 'Santiago de Cao', '1302', '13'),
	('130208', 'Casa Grande', '1302', '13'),
	('130301', 'Bolívar', '1303', '13'),
	('130302', 'Bambamarca', '1303', '13'),
	('130303', 'Condormarca', '1303', '13'),
	('130304', 'Longotea', '1303', '13'),
	('130305', 'Uchumarca', '1303', '13'),
	('130306', 'Ucuncha', '1303', '13'),
	('130401', 'Chepen', '1304', '13'),
	('130402', 'Pacanga', '1304', '13'),
	('130403', 'Pueblo Nuevo', '1304', '13'),
	('130501', 'Julcan', '1305', '13'),
	('130502', 'Calamarca', '1305', '13'),
	('130503', 'Carabamba', '1305', '13'),
	('130504', 'Huaso', '1305', '13'),
	('130601', 'Otuzco', '1306', '13'),
	('130602', 'Agallpampa', '1306', '13'),
	('130604', 'Charat', '1306', '13'),
	('130605', 'Huaranchal', '1306', '13'),
	('130606', 'La Cuesta', '1306', '13'),
	('130608', 'Mache', '1306', '13'),
	('130610', 'Paranday', '1306', '13'),
	('130611', 'Salpo', '1306', '13'),
	('130613', 'Sinsicap', '1306', '13'),
	('130614', 'Usquil', '1306', '13'),
	('130701', 'San Pedro de Lloc', '1307', '13'),
	('130702', 'Guadalupe', '1307', '13'),
	('130703', 'Jequetepeque', '1307', '13'),
	('130704', 'Pacasmayo', '1307', '13'),
	('130705', 'San José', '1307', '13'),
	('130801', 'Tayabamba', '1308', '13'),
	('130802', 'Buldibuyo', '1308', '13'),
	('130803', 'Chillia', '1308', '13'),
	('130804', 'Huancaspata', '1308', '13'),
	('130805', 'Huaylillas', '1308', '13'),
	('130806', 'Huayo', '1308', '13'),
	('130807', 'Ongon', '1308', '13'),
	('130808', 'Parcoy', '1308', '13'),
	('130809', 'Pataz', '1308', '13'),
	('130810', 'Pias', '1308', '13'),
	('130811', 'Santiago de Challas', '1308', '13'),
	('130812', 'Taurija', '1308', '13'),
	('130813', 'Urpay', '1308', '13'),
	('130901', 'Huamachuco', '1309', '13'),
	('130902', 'Chugay', '1309', '13'),
	('130903', 'Cochorco', '1309', '13'),
	('130904', 'Curgos', '1309', '13'),
	('130905', 'Marcabal', '1309', '13'),
	('130906', 'Sanagoran', '1309', '13'),
	('130907', 'Sarin', '1309', '13'),
	('130908', 'Sartimbamba', '1309', '13'),
	('131001', 'Santiago de Chuco', '1310', '13'),
	('131002', 'Angasmarca', '1310', '13'),
	('131003', 'Cachicadan', '1310', '13'),
	('131004', 'Mollebamba', '1310', '13'),
	('131005', 'Mollepata', '1310', '13'),
	('131006', 'Quiruvilca', '1310', '13'),
	('131007', 'Santa Cruz de Chuca', '1310', '13'),
	('131008', 'Sitabamba', '1310', '13'),
	('131101', 'Cascas', '1311', '13'),
	('131102', 'Lucma', '1311', '13'),
	('131103', 'Marmot', '1311', '13'),
	('131104', 'Sayapullo', '1311', '13'),
	('131201', 'Viru', '1312', '13'),
	('131202', 'Chao', '1312', '13'),
	('131203', 'Guadalupito', '1312', '13'),
	('140101', 'Chiclayo', '1401', '14'),
	('140102', 'Chongoyape', '1401', '14'),
	('140103', 'Eten', '1401', '14'),
	('140104', 'Eten Puerto', '1401', '14'),
	('140105', 'José Leonardo Ortiz', '1401', '14'),
	('140106', 'La Victoria', '1401', '14'),
	('140107', 'Lagunas', '1401', '14'),
	('140108', 'Monsefu', '1401', '14'),
	('140109', 'Nueva Arica', '1401', '14'),
	('140110', 'Oyotun', '1401', '14'),
	('140111', 'Picsi', '1401', '14'),
	('140112', 'Pimentel', '1401', '14'),
	('140113', 'Reque', '1401', '14'),
	('140114', 'Santa Rosa', '1401', '14'),
	('140115', 'Saña', '1401', '14'),
	('140116', 'Cayalti', '1401', '14'),
	('140117', 'Patapo', '1401', '14'),
	('140118', 'Pomalca', '1401', '14'),
	('140119', 'Pucala', '1401', '14'),
	('140120', 'Tuman', '1401', '14'),
	('140201', 'Ferreñafe', '1402', '14'),
	('140202', 'Cañaris', '1402', '14'),
	('140203', 'Incahuasi', '1402', '14'),
	('140204', 'Manuel Antonio Mesones Muro', '1402', '14'),
	('140205', 'Pitipo', '1402', '14'),
	('140206', 'Pueblo Nuevo', '1402', '14'),
	('140301', 'Lambayeque', '1403', '14'),
	('140302', 'Chochope', '1403', '14'),
	('140303', 'Illimo', '1403', '14'),
	('140304', 'Jayanca', '1403', '14'),
	('140305', 'Mochumi', '1403', '14'),
	('140306', 'Morrope', '1403', '14'),
	('140307', 'Motupe', '1403', '14'),
	('140308', 'Olmos', '1403', '14'),
	('140309', 'Pacora', '1403', '14'),
	('140310', 'Salas', '1403', '14'),
	('140311', 'San José', '1403', '14'),
	('140312', 'Tucume', '1403', '14'),
	('150101', 'Lima', '1501', '15'),
	('150102', 'Ancón', '1501', '15'),
	('150103', 'Ate', '1501', '15'),
	('150104', 'Barranco', '1501', '15'),
	('150105', 'Breña', '1501', '15'),
	('150106', 'Carabayllo', '1501', '15'),
	('150107', 'Chaclacayo', '1501', '15'),
	('150108', 'Chorrillos', '1501', '15'),
	('150109', 'Cieneguilla', '1501', '15'),
	('150110', 'Comas', '1501', '15'),
	('150111', 'El Agustino', '1501', '15'),
	('150112', 'Independencia', '1501', '15'),
	('150113', 'Jesús María', '1501', '15'),
	('150114', 'La Molina', '1501', '15'),
	('150115', 'La Victoria', '1501', '15'),
	('150116', 'Lince', '1501', '15'),
	('150117', 'Los Olivos', '1501', '15'),
	('150118', 'Lurigancho', '1501', '15'),
	('150119', 'Lurin', '1501', '15'),
	('150120', 'Magdalena del Mar', '1501', '15'),
	('150121', 'Pueblo Libre', '1501', '15'),
	('150122', 'Miraflores', '1501', '15'),
	('150123', 'Pachacamac', '1501', '15'),
	('150124', 'Pucusana', '1501', '15'),
	('150125', 'Puente Piedra', '1501', '15'),
	('150126', 'Punta Hermosa', '1501', '15'),
	('150127', 'Punta Negra', '1501', '15'),
	('150128', 'Rímac', '1501', '15'),
	('150129', 'San Bartolo', '1501', '15'),
	('150130', 'San Borja', '1501', '15'),
	('150131', 'San Isidro', '1501', '15'),
	('150132', 'San Juan de Lurigancho', '1501', '15'),
	('150133', 'San Juan de Miraflores', '1501', '15'),
	('150134', 'San Luis', '1501', '15'),
	('150135', 'San Martín de Porres', '1501', '15'),
	('150136', 'San Miguel', '1501', '15'),
	('150137', 'Santa Anita', '1501', '15'),
	('150138', 'Santa María del Mar', '1501', '15'),
	('150139', 'Santa Rosa', '1501', '15'),
	('150140', 'Santiago de Surco', '1501', '15'),
	('150141', 'Surquillo', '1501', '15'),
	('150142', 'Villa El Salvador', '1501', '15'),
	('150143', 'Villa María del Triunfo', '1501', '15'),
	('150201', 'Barranca', '1502', '15'),
	('150202', 'Paramonga', '1502', '15'),
	('150203', 'Pativilca', '1502', '15'),
	('150204', 'Supe', '1502', '15'),
	('150205', 'Supe Puerto', '1502', '15'),
	('150301', 'Cajatambo', '1503', '15'),
	('150302', 'Copa', '1503', '15'),
	('150303', 'Gorgor', '1503', '15'),
	('150304', 'Huancapon', '1503', '15'),
	('150305', 'Manas', '1503', '15'),
	('150401', 'Canta', '1504', '15'),
	('150402', 'Arahuay', '1504', '15'),
	('150403', 'Huamantanga', '1504', '15'),
	('150404', 'Huaros', '1504', '15'),
	('150405', 'Lachaqui', '1504', '15'),
	('150406', 'San Buenaventura', '1504', '15'),
	('150407', 'Santa Rosa de Quives', '1504', '15'),
	('150501', 'San Vicente de Cañete', '1505', '15'),
	('150502', 'Asia', '1505', '15'),
	('150503', 'Calango', '1505', '15'),
	('150504', 'Cerro Azul', '1505', '15'),
	('150505', 'Chilca', '1505', '15'),
	('150506', 'Coayllo', '1505', '15'),
	('150507', 'Imperial', '1505', '15'),
	('150508', 'Lunahuana', '1505', '15'),
	('150509', 'Mala', '1505', '15'),
	('150510', 'Nuevo Imperial', '1505', '15'),
	('150511', 'Pacaran', '1505', '15'),
	('150512', 'Quilmana', '1505', '15'),
	('150513', 'San Antonio', '1505', '15'),
	('150514', 'San Luis', '1505', '15'),
	('150515', 'Santa Cruz de Flores', '1505', '15'),
	('150516', 'Zúñiga', '1505', '15'),
	('150601', 'Huaral', '1506', '15'),
	('150602', 'Atavillos Alto', '1506', '15'),
	('150603', 'Atavillos Bajo', '1506', '15'),
	('150604', 'Aucallama', '1506', '15'),
	('150605', 'Chancay', '1506', '15'),
	('150606', 'Ihuari', '1506', '15'),
	('150607', 'Lampian', '1506', '15'),
	('150608', 'Pacaraos', '1506', '15'),
	('150609', 'San Miguel de Acos', '1506', '15'),
	('150610', 'Santa Cruz de Andamarca', '1506', '15'),
	('150611', 'Sumbilca', '1506', '15'),
	('150612', 'Veintisiete de Noviembre', '1506', '15'),
	('150701', 'Matucana', '1507', '15'),
	('150702', 'Antioquia', '1507', '15'),
	('150703', 'Callahuanca', '1507', '15'),
	('150704', 'Carampoma', '1507', '15'),
	('150705', 'Chicla', '1507', '15'),
	('150706', 'Cuenca', '1507', '15'),
	('150707', 'Huachupampa', '1507', '15'),
	('150708', 'Huanza', '1507', '15'),
	('150709', 'Huarochiri', '1507', '15'),
	('150710', 'Lahuaytambo', '1507', '15'),
	('150711', 'Langa', '1507', '15'),
	('150712', 'Laraos', '1507', '15'),
	('150713', 'Mariatana', '1507', '15'),
	('150714', 'Ricardo Palma', '1507', '15'),
	('150715', 'San Andrés de Tupicocha', '1507', '15'),
	('150716', 'San Antonio', '1507', '15'),
	('150717', 'San Bartolomé', '1507', '15'),
	('150718', 'San Damian', '1507', '15'),
	('150719', 'San Juan de Iris', '1507', '15'),
	('150720', 'San Juan de Tantaranche', '1507', '15'),
	('150721', 'San Lorenzo de Quinti', '1507', '15'),
	('150722', 'San Mateo', '1507', '15'),
	('150723', 'San Mateo de Otao', '1507', '15'),
	('150724', 'San Pedro de Casta', '1507', '15'),
	('150725', 'San Pedro de Huancayre', '1507', '15'),
	('150726', 'Sangallaya', '1507', '15'),
	('150727', 'Santa Cruz de Cocachacra', '1507', '15'),
	('150728', 'Santa Eulalia', '1507', '15'),
	('150729', 'Santiago de Anchucaya', '1507', '15'),
	('150730', 'Santiago de Tuna', '1507', '15'),
	('150731', 'Santo Domingo de Los Olleros', '1507', '15'),
	('150732', 'Surco', '1507', '15'),
	('150801', 'Huacho', '1508', '15'),
	('150802', 'Ambar', '1508', '15'),
	('150803', 'Caleta de Carquin', '1508', '15'),
	('150804', 'Checras', '1508', '15'),
	('150805', 'Hualmay', '1508', '15'),
	('150806', 'Huaura', '1508', '15'),
	('150807', 'Leoncio Prado', '1508', '15'),
	('150808', 'Paccho', '1508', '15'),
	('150809', 'Santa Leonor', '1508', '15'),
	('150810', 'Santa María', '1508', '15'),
	('150811', 'Sayan', '1508', '15'),
	('150812', 'Vegueta', '1508', '15'),
	('150901', 'Oyon', '1509', '15'),
	('150902', 'Andajes', '1509', '15'),
	('150903', 'Caujul', '1509', '15'),
	('150904', 'Cochamarca', '1509', '15'),
	('150905', 'Navan', '1509', '15'),
	('150906', 'Pachangara', '1509', '15'),
	('151001', 'Yauyos', '1510', '15'),
	('151002', 'Alis', '1510', '15'),
	('151003', 'Allauca', '1510', '15'),
	('151004', 'Ayaviri', '1510', '15'),
	('151005', 'Azángaro', '1510', '15'),
	('151006', 'Cacra', '1510', '15'),
	('151007', 'Carania', '1510', '15'),
	('151008', 'Catahuasi', '1510', '15'),
	('151009', 'Chocos', '1510', '15'),
	('151010', 'Cochas', '1510', '15'),
	('151011', 'Colonia', '1510', '15'),
	('151012', 'Hongos', '1510', '15'),
	('151013', 'Huampara', '1510', '15'),
	('151014', 'Huancaya', '1510', '15'),
	('151015', 'Huangascar', '1510', '15'),
	('151016', 'Huantan', '1510', '15'),
	('151017', 'Huañec', '1510', '15'),
	('151018', 'Laraos', '1510', '15'),
	('151019', 'Lincha', '1510', '15'),
	('151020', 'Madean', '1510', '15'),
	('151021', 'Miraflores', '1510', '15'),
	('151022', 'Omas', '1510', '15'),
	('151023', 'Putinza', '1510', '15'),
	('151024', 'Quinches', '1510', '15'),
	('151025', 'Quinocay', '1510', '15'),
	('151026', 'San Joaquín', '1510', '15'),
	('151027', 'San Pedro de Pilas', '1510', '15'),
	('151028', 'Tanta', '1510', '15'),
	('151029', 'Tauripampa', '1510', '15'),
	('151030', 'Tomas', '1510', '15'),
	('151031', 'Tupe', '1510', '15'),
	('151032', 'Viñac', '1510', '15'),
	('151033', 'Vitis', '1510', '15'),
	('160101', 'Iquitos', '1601', '16'),
	('160102', 'Alto Nanay', '1601', '16'),
	('160103', 'Fernando Lores', '1601', '16'),
	('160104', 'Indiana', '1601', '16'),
	('160105', 'Las Amazonas', '1601', '16'),
	('160106', 'Mazan', '1601', '16'),
	('160107', 'Napo', '1601', '16'),
	('160108', 'Punchana', '1601', '16'),
	('160110', 'Torres Causana', '1601', '16'),
	('160112', 'Belén', '1601', '16'),
	('160113', 'San Juan Bautista', '1601', '16'),
	('160201', 'Yurimaguas', '1602', '16'),
	('160202', 'Balsapuerto', '1602', '16'),
	('160205', 'Jeberos', '1602', '16'),
	('160206', 'Lagunas', '1602', '16'),
	('160210', 'Santa Cruz', '1602', '16'),
	('160211', 'Teniente Cesar López Rojas', '1602', '16'),
	('160301', 'Nauta', '1603', '16'),
	('160302', 'Parinari', '1603', '16'),
	('160303', 'Tigre', '1603', '16'),
	('160304', 'Trompeteros', '1603', '16'),
	('160305', 'Urarinas', '1603', '16'),
	('160401', 'Ramón Castilla', '1604', '16'),
	('160402', 'Pebas', '1604', '16'),
	('160403', 'Yavari', '1604', '16'),
	('160404', 'San Pablo', '1604', '16'),
	('160501', 'Requena', '1605', '16'),
	('160502', 'Alto Tapiche', '1605', '16'),
	('160503', 'Capelo', '1605', '16'),
	('160504', 'Emilio San Martín', '1605', '16'),
	('160505', 'Maquia', '1605', '16'),
	('160506', 'Puinahua', '1605', '16'),
	('160507', 'Saquena', '1605', '16'),
	('160508', 'Soplin', '1605', '16'),
	('160509', 'Tapiche', '1605', '16'),
	('160510', 'Jenaro Herrera', '1605', '16'),
	('160511', 'Yaquerana', '1605', '16'),
	('160601', 'Contamana', '1606', '16'),
	('160602', 'Inahuaya', '1606', '16'),
	('160603', 'Padre Márquez', '1606', '16'),
	('160604', 'Pampa Hermosa', '1606', '16'),
	('160605', 'Sarayacu', '1606', '16'),
	('160606', 'Vargas Guerra', '1606', '16'),
	('160701', 'Barranca', '1607', '16'),
	('160702', 'Cahuapanas', '1607', '16'),
	('160703', 'Manseriche', '1607', '16'),
	('160704', 'Morona', '1607', '16'),
	('160705', 'Pastaza', '1607', '16'),
	('160706', 'Andoas', '1607', '16'),
	('160801', 'Putumayo', '1608', '16'),
	('160802', 'Rosa Panduro', '1608', '16'),
	('160803', 'Teniente Manuel Clavero', '1608', '16'),
	('160804', 'Yaguas', '1608', '16'),
	('170101', 'Tambopata', '1701', '17'),
	('170102', 'Inambari', '1701', '17'),
	('170103', 'Las Piedras', '1701', '17'),
	('170104', 'Laberinto', '1701', '17'),
	('170201', 'Manu', '1702', '17'),
	('170202', 'Fitzcarrald', '1702', '17'),
	('170203', 'Madre de Dios', '1702', '17'),
	('170204', 'Huepetuhe', '1702', '17'),
	('170301', 'Iñapari', '1703', '17'),
	('170302', 'Iberia', '1703', '17'),
	('170303', 'Tahuamanu', '1703', '17'),
	('180101', 'Moquegua', '1801', '18'),
	('180102', 'Carumas', '1801', '18'),
	('180103', 'Cuchumbaya', '1801', '18'),
	('180104', 'Samegua', '1801', '18'),
	('180105', 'San Cristóbal', '1801', '18'),
	('180106', 'Torata', '1801', '18'),
	('180201', 'Omate', '1802', '18'),
	('180202', 'Chojata', '1802', '18'),
	('180203', 'Coalaque', '1802', '18'),
	('180204', 'Ichuña', '1802', '18'),
	('180205', 'La Capilla', '1802', '18'),
	('180206', 'Lloque', '1802', '18'),
	('180207', 'Matalaque', '1802', '18'),
	('180208', 'Puquina', '1802', '18'),
	('180209', 'Quinistaquillas', '1802', '18'),
	('180210', 'Ubinas', '1802', '18'),
	('180211', 'Yunga', '1802', '18'),
	('180301', 'Ilo', '1803', '18'),
	('180302', 'El Algarrobal', '1803', '18'),
	('180303', 'Pacocha', '1803', '18'),
	('190101', 'Chaupimarca', '1901', '19'),
	('190102', 'Huachon', '1901', '19'),
	('190103', 'Huariaca', '1901', '19'),
	('190104', 'Huayllay', '1901', '19'),
	('190105', 'Ninacaca', '1901', '19'),
	('190106', 'Pallanchacra', '1901', '19'),
	('190107', 'Paucartambo', '1901', '19'),
	('190108', 'San Francisco de Asís de Yarusyacan', '1901', '19'),
	('190109', 'Simon Bolívar', '1901', '19'),
	('190110', 'Ticlacayan', '1901', '19'),
	('190111', 'Tinyahuarco', '1901', '19'),
	('190112', 'Vicco', '1901', '19'),
	('190113', 'Yanacancha', '1901', '19'),
	('190201', 'Yanahuanca', '1902', '19'),
	('190202', 'Chacayan', '1902', '19'),
	('190203', 'Goyllarisquizga', '1902', '19'),
	('190204', 'Paucar', '1902', '19'),
	('190205', 'San Pedro de Pillao', '1902', '19'),
	('190206', 'Santa Ana de Tusi', '1902', '19'),
	('190207', 'Tapuc', '1902', '19'),
	('190208', 'Vilcabamba', '1902', '19'),
	('190301', 'Oxapampa', '1903', '19'),
	('190302', 'Chontabamba', '1903', '19'),
	('190303', 'Huancabamba', '1903', '19'),
	('190304', 'Palcazu', '1903', '19'),
	('190305', 'Pozuzo', '1903', '19'),
	('190306', 'Puerto Bermúdez', '1903', '19'),
	('190307', 'Villa Rica', '1903', '19'),
	('190308', 'Constitución', '1903', '19'),
	('200101', 'Piura', '2001', '20'),
	('200104', 'Castilla', '2001', '20'),
	('200105', 'Catacaos', '2001', '20'),
	('200107', 'Cura Mori', '2001', '20'),
	('200108', 'El Tallan', '2001', '20'),
	('200109', 'La Arena', '2001', '20'),
	('200110', 'La Unión', '2001', '20'),
	('200111', 'Las Lomas', '2001', '20'),
	('200114', 'Tambo Grande', '2001', '20'),
	('200115', 'Veintiseis de Octubre', '2001', '20'),
	('200201', 'Ayabaca', '2002', '20'),
	('200202', 'Frias', '2002', '20'),
	('200203', 'Jilili', '2002', '20'),
	('200204', 'Lagunas', '2002', '20'),
	('200205', 'Montero', '2002', '20'),
	('200206', 'Pacaipampa', '2002', '20'),
	('200207', 'Paimas', '2002', '20'),
	('200208', 'Sapillica', '2002', '20'),
	('200209', 'Sicchez', '2002', '20'),
	('200210', 'Suyo', '2002', '20'),
	('200301', 'Huancabamba', '2003', '20'),
	('200302', 'Canchaque', '2003', '20'),
	('200303', 'El Carmen de la Frontera', '2003', '20'),
	('200304', 'Huarmaca', '2003', '20'),
	('200305', 'Lalaquiz', '2003', '20'),
	('200306', 'San Miguel de El Faique', '2003', '20'),
	('200307', 'Sondor', '2003', '20'),
	('200308', 'Sondorillo', '2003', '20'),
	('200401', 'Chulucanas', '2004', '20'),
	('200402', 'Buenos Aires', '2004', '20'),
	('200403', 'Chalaco', '2004', '20'),
	('200404', 'La Matanza', '2004', '20'),
	('200405', 'Morropon', '2004', '20'),
	('200406', 'Salitral', '2004', '20'),
	('200407', 'San Juan de Bigote', '2004', '20'),
	('200408', 'Santa Catalina de Mossa', '2004', '20'),
	('200409', 'Santo Domingo', '2004', '20'),
	('200410', 'Yamango', '2004', '20'),
	('200501', 'Paita', '2005', '20'),
	('200502', 'Amotape', '2005', '20'),
	('200503', 'Arenal', '2005', '20'),
	('200504', 'Colan', '2005', '20'),
	('200505', 'La Huaca', '2005', '20'),
	('200506', 'Tamarindo', '2005', '20'),
	('200507', 'Vichayal', '2005', '20'),
	('200601', 'Sullana', '2006', '20'),
	('200602', 'Bellavista', '2006', '20'),
	('200603', 'Ignacio Escudero', '2006', '20'),
	('200604', 'Lancones', '2006', '20'),
	('200605', 'Marcavelica', '2006', '20'),
	('200606', 'Miguel Checa', '2006', '20'),
	('200607', 'Querecotillo', '2006', '20'),
	('200608', 'Salitral', '2006', '20'),
	('200701', 'Pariñas', '2007', '20'),
	('200702', 'El Alto', '2007', '20'),
	('200703', 'La Brea', '2007', '20'),
	('200704', 'Lobitos', '2007', '20'),
	('200705', 'Los Organos', '2007', '20'),
	('200706', 'Mancora', '2007', '20'),
	('200801', 'Sechura', '2008', '20'),
	('200802', 'Bellavista de la Unión', '2008', '20'),
	('200803', 'Bernal', '2008', '20'),
	('200804', 'Cristo Nos Valga', '2008', '20'),
	('200805', 'Vice', '2008', '20'),
	('200806', 'Rinconada Llicuar', '2008', '20'),
	('210101', 'Puno', '2101', '21'),
	('210102', 'Acora', '2101', '21'),
	('210103', 'Amantani', '2101', '21'),
	('210104', 'Atuncolla', '2101', '21'),
	('210105', 'Capachica', '2101', '21'),
	('210106', 'Chucuito', '2101', '21'),
	('210107', 'Coata', '2101', '21'),
	('210108', 'Huata', '2101', '21'),
	('210109', 'Mañazo', '2101', '21'),
	('210110', 'Paucarcolla', '2101', '21'),
	('210111', 'Pichacani', '2101', '21'),
	('210112', 'Plateria', '2101', '21'),
	('210113', 'San Antonio', '2101', '21'),
	('210114', 'Tiquillaca', '2101', '21'),
	('210115', 'Vilque', '2101', '21'),
	('210201', 'Azángaro', '2102', '21'),
	('210202', 'Achaya', '2102', '21'),
	('210203', 'Arapa', '2102', '21'),
	('210204', 'Asillo', '2102', '21'),
	('210205', 'Caminaca', '2102', '21'),
	('210206', 'Chupa', '2102', '21'),
	('210207', 'José Domingo Choquehuanca', '2102', '21'),
	('210208', 'Muñani', '2102', '21'),
	('210209', 'Potoni', '2102', '21'),
	('210210', 'Saman', '2102', '21'),
	('210211', 'San Anton', '2102', '21'),
	('210212', 'San José', '2102', '21'),
	('210213', 'San Juan de Salinas', '2102', '21'),
	('210214', 'Santiago de Pupuja', '2102', '21'),
	('210215', 'Tirapata', '2102', '21'),
	('210301', 'Macusani', '2103', '21'),
	('210302', 'Ajoyani', '2103', '21'),
	('210303', 'Ayapata', '2103', '21'),
	('210304', 'Coasa', '2103', '21'),
	('210305', 'Corani', '2103', '21'),
	('210306', 'Crucero', '2103', '21'),
	('210307', 'Ituata', '2103', '21'),
	('210308', 'Ollachea', '2103', '21'),
	('210309', 'San Gaban', '2103', '21'),
	('210310', 'Usicayos', '2103', '21'),
	('210401', 'Juli', '2104', '21'),
	('210402', 'Desaguadero', '2104', '21'),
	('210403', 'Huacullani', '2104', '21'),
	('210404', 'Kelluyo', '2104', '21'),
	('210405', 'Pisacoma', '2104', '21'),
	('210406', 'Pomata', '2104', '21'),
	('210407', 'Zepita', '2104', '21'),
	('210501', 'Ilave', '2105', '21'),
	('210502', 'Capazo', '2105', '21'),
	('210503', 'Pilcuyo', '2105', '21'),
	('210504', 'Santa Rosa', '2105', '21'),
	('210505', 'Conduriri', '2105', '21'),
	('210601', 'Huancane', '2106', '21'),
	('210602', 'Cojata', '2106', '21'),
	('210603', 'Huatasani', '2106', '21'),
	('210604', 'Inchupalla', '2106', '21'),
	('210605', 'Pusi', '2106', '21'),
	('210606', 'Rosaspata', '2106', '21'),
	('210607', 'Taraco', '2106', '21'),
	('210608', 'Vilque Chico', '2106', '21'),
	('210701', 'Lampa', '2107', '21'),
	('210702', 'Cabanilla', '2107', '21'),
	('210703', 'Calapuja', '2107', '21'),
	('210704', 'Nicasio', '2107', '21'),
	('210705', 'Ocuviri', '2107', '21'),
	('210706', 'Palca', '2107', '21'),
	('210707', 'Paratia', '2107', '21'),
	('210708', 'Pucara', '2107', '21'),
	('210709', 'Santa Lucia', '2107', '21'),
	('210710', 'Vilavila', '2107', '21'),
	('210801', 'Ayaviri', '2108', '21'),
	('210802', 'Antauta', '2108', '21'),
	('210803', 'Cupi', '2108', '21'),
	('210804', 'Llalli', '2108', '21'),
	('210805', 'Macari', '2108', '21'),
	('210806', 'Nuñoa', '2108', '21'),
	('210807', 'Orurillo', '2108', '21'),
	('210808', 'Santa Rosa', '2108', '21'),
	('210809', 'Umachiri', '2108', '21'),
	('210901', 'Moho', '2109', '21'),
	('210902', 'Conima', '2109', '21'),
	('210903', 'Huayrapata', '2109', '21'),
	('210904', 'Tilali', '2109', '21'),
	('211001', 'Putina', '2110', '21'),
	('211002', 'Ananea', '2110', '21'),
	('211003', 'Pedro Vilca Apaza', '2110', '21'),
	('211004', 'Quilcapuncu', '2110', '21'),
	('211005', 'Sina', '2110', '21'),
	('211101', 'Juliaca', '2111', '21'),
	('211102', 'Cabana', '2111', '21'),
	('211103', 'Cabanillas', '2111', '21'),
	('211104', 'Caracoto', '2111', '21'),
	('211105', 'San Miguel', '2111', '21'),
	('211201', 'Sandia', '2112', '21'),
	('211202', 'Cuyocuyo', '2112', '21'),
	('211203', 'Limbani', '2112', '21'),
	('211204', 'Patambuco', '2112', '21'),
	('211205', 'Phara', '2112', '21'),
	('211206', 'Quiaca', '2112', '21'),
	('211207', 'San Juan del Oro', '2112', '21'),
	('211208', 'Yanahuaya', '2112', '21'),
	('211209', 'Alto Inambari', '2112', '21'),
	('211210', 'San Pedro de Putina Punco', '2112', '21'),
	('211301', 'Yunguyo', '2113', '21'),
	('211302', 'Anapia', '2113', '21'),
	('211303', 'Copani', '2113', '21'),
	('211304', 'Cuturapi', '2113', '21'),
	('211305', 'Ollaraya', '2113', '21'),
	('211306', 'Tinicachi', '2113', '21'),
	('211307', 'Unicachi', '2113', '21'),
	('220101', 'Moyobamba', '2201', '22'),
	('220102', 'Calzada', '2201', '22'),
	('220103', 'Habana', '2201', '22'),
	('220104', 'Jepelacio', '2201', '22'),
	('220105', 'Soritor', '2201', '22'),
	('220106', 'Yantalo', '2201', '22'),
	('220201', 'Bellavista', '2202', '22'),
	('220202', 'Alto Biavo', '2202', '22'),
	('220203', 'Bajo Biavo', '2202', '22'),
	('220204', 'Huallaga', '2202', '22'),
	('220205', 'San Pablo', '2202', '22'),
	('220206', 'San Rafael', '2202', '22'),
	('220301', 'San José de Sisa', '2203', '22'),
	('220302', 'Agua Blanca', '2203', '22'),
	('220303', 'San Martín', '2203', '22'),
	('220304', 'Santa Rosa', '2203', '22'),
	('220305', 'Shatoja', '2203', '22'),
	('220401', 'Saposoa', '2204', '22'),
	('220402', 'Alto Saposoa', '2204', '22'),
	('220403', 'El Eslabón', '2204', '22'),
	('220404', 'Piscoyacu', '2204', '22'),
	('220405', 'Sacanche', '2204', '22'),
	('220406', 'Tingo de Saposoa', '2204', '22'),
	('220501', 'Lamas', '2205', '22'),
	('220502', 'Alonso de Alvarado', '2205', '22'),
	('220503', 'Barranquita', '2205', '22'),
	('220504', 'Caynarachi', '2205', '22'),
	('220505', 'Cuñumbuqui', '2205', '22'),
	('220506', 'Pinto Recodo', '2205', '22'),
	('220507', 'Rumisapa', '2205', '22'),
	('220508', 'San Roque de Cumbaza', '2205', '22'),
	('220509', 'Shanao', '2205', '22'),
	('220510', 'Tabalosos', '2205', '22'),
	('220511', 'Zapatero', '2205', '22'),
	('220601', 'Juanjuí', '2206', '22'),
	('220602', 'Campanilla', '2206', '22'),
	('220603', 'Huicungo', '2206', '22'),
	('220604', 'Pachiza', '2206', '22'),
	('220605', 'Pajarillo', '2206', '22'),
	('220701', 'Picota', '2207', '22'),
	('220702', 'Buenos Aires', '2207', '22'),
	('220703', 'Caspisapa', '2207', '22'),
	('220704', 'Pilluana', '2207', '22'),
	('220705', 'Pucacaca', '2207', '22'),
	('220706', 'San Cristóbal', '2207', '22'),
	('220707', 'San Hilarión', '2207', '22'),
	('220708', 'Shamboyacu', '2207', '22'),
	('220709', 'Tingo de Ponasa', '2207', '22'),
	('220710', 'Tres Unidos', '2207', '22'),
	('220801', 'Rioja', '2208', '22'),
	('220802', 'Awajun', '2208', '22'),
	('220803', 'Elías Soplin Vargas', '2208', '22'),
	('220804', 'Nueva Cajamarca', '2208', '22'),
	('220805', 'Pardo Miguel', '2208', '22'),
	('220806', 'Posic', '2208', '22'),
	('220807', 'San Fernando', '2208', '22'),
	('220808', 'Yorongos', '2208', '22'),
	('220809', 'Yuracyacu', '2208', '22'),
	('220901', 'Tarapoto', '2209', '22'),
	('220902', 'Alberto Leveau', '2209', '22'),
	('220903', 'Cacatachi', '2209', '22'),
	('220904', 'Chazuta', '2209', '22'),
	('220905', 'Chipurana', '2209', '22'),
	('220906', 'El Porvenir', '2209', '22'),
	('220907', 'Huimbayoc', '2209', '22'),
	('220908', 'Juan Guerra', '2209', '22'),
	('220909', 'La Banda de Shilcayo', '2209', '22'),
	('220910', 'Morales', '2209', '22'),
	('220911', 'Papaplaya', '2209', '22'),
	('220912', 'San Antonio', '2209', '22'),
	('220913', 'Sauce', '2209', '22'),
	('220914', 'Shapaja', '2209', '22'),
	('221001', 'Tocache', '2210', '22'),
	('221002', 'Nuevo Progreso', '2210', '22'),
	('221003', 'Polvora', '2210', '22'),
	('221004', 'Shunte', '2210', '22'),
	('221005', 'Uchiza', '2210', '22'),
	('230101', 'Tacna', '2301', '23'),
	('230102', 'Alto de la Alianza', '2301', '23'),
	('230103', 'Calana', '2301', '23'),
	('230104', 'Ciudad Nueva', '2301', '23'),
	('230105', 'Inclan', '2301', '23'),
	('230106', 'Pachia', '2301', '23'),
	('230107', 'Palca', '2301', '23'),
	('230108', 'Pocollay', '2301', '23'),
	('230109', 'Sama', '2301', '23'),
	('230110', 'Coronel Gregorio Albarracín Lanchipa', '2301', '23'),
	('230111', 'La Yarada los Palos', '2301', '23'),
	('230201', 'Candarave', '2302', '23'),
	('230202', 'Cairani', '2302', '23'),
	('230203', 'Camilaca', '2302', '23'),
	('230204', 'Curibaya', '2302', '23'),
	('230205', 'Huanuara', '2302', '23'),
	('230206', 'Quilahuani', '2302', '23'),
	('230301', 'Locumba', '2303', '23'),
	('230302', 'Ilabaya', '2303', '23'),
	('230303', 'Ite', '2303', '23'),
	('230401', 'Tarata', '2304', '23'),
	('230402', 'Héroes Albarracín', '2304', '23'),
	('230403', 'Estique', '2304', '23'),
	('230404', 'Estique-Pampa', '2304', '23'),
	('230405', 'Sitajara', '2304', '23'),
	('230406', 'Susapaya', '2304', '23'),
	('230407', 'Tarucachi', '2304', '23'),
	('230408', 'Ticaco', '2304', '23'),
	('240101', 'Tumbes', '2401', '24'),
	('240102', 'Corrales', '2401', '24'),
	('240103', 'La Cruz', '2401', '24'),
	('240104', 'Pampas de Hospital', '2401', '24'),
	('240105', 'San Jacinto', '2401', '24'),
	('240106', 'San Juan de la Virgen', '2401', '24'),
	('240201', 'Zorritos', '2402', '24'),
	('240202', 'Casitas', '2402', '24'),
	('240203', 'Canoas de Punta Sal', '2402', '24'),
	('240301', 'Zarumilla', '2403', '24'),
	('240302', 'Aguas Verdes', '2403', '24'),
	('240303', 'Matapalo', '2403', '24'),
	('240304', 'Papayal', '2403', '24'),
	('250101', 'Calleria', '2501', '25'),
	('250102', 'Campoverde', '2501', '25'),
	('250103', 'Iparia', '2501', '25'),
	('250104', 'Masisea', '2501', '25'),
	('250105', 'Yarinacocha', '2501', '25'),
	('250106', 'Nueva Requena', '2501', '25'),
	('250107', 'Manantay', '2501', '25'),
	('250201', 'Raymondi', '2502', '25'),
	('250202', 'Sepahua', '2502', '25'),
	('250203', 'Tahuania', '2502', '25'),
	('250204', 'Yurua', '2502', '25'),
	('250301', 'Padre Abad', '2503', '25'),
	('250302', 'Irazola', '2503', '25'),
	('250303', 'Curimana', '2503', '25'),
	('250304', 'Neshuya', '2503', '25'),
	('250305', 'Alexander Von Humboldt', '2503', '25'),
	('250401', 'Purus', '2504', '25');

-- Dumping structure for table test_cms.ubigeo_peru_provinces
DROP TABLE IF EXISTS `ubigeo_peru_provinces`;
CREATE TABLE IF NOT EXISTS `ubigeo_peru_provinces` (
  `id` varchar(4) NOT NULL,
  `name` varchar(45) NOT NULL,
  `department_id` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.ubigeo_peru_provinces: ~196 rows (approximately)
INSERT INTO `ubigeo_peru_provinces` (`id`, `name`, `department_id`) VALUES
	('0101', 'Chachapoyas', '01'),
	('0102', 'Bagua', '01'),
	('0103', 'Bongará', '01'),
	('0104', 'Condorcanqui', '01'),
	('0105', 'Luya', '01'),
	('0106', 'Rodríguez de Mendoza', '01'),
	('0107', 'Utcubamba', '01'),
	('0201', 'Huaraz', '02'),
	('0202', 'Aija', '02'),
	('0203', 'Antonio Raymondi', '02'),
	('0204', 'Asunción', '02'),
	('0205', 'Bolognesi', '02'),
	('0206', 'Carhuaz', '02'),
	('0207', 'Carlos Fermín Fitzcarrald', '02'),
	('0208', 'Casma', '02'),
	('0209', 'Corongo', '02'),
	('0210', 'Huari', '02'),
	('0211', 'Huarmey', '02'),
	('0212', 'Huaylas', '02'),
	('0213', 'Mariscal Luzuriaga', '02'),
	('0214', 'Ocros', '02'),
	('0215', 'Pallasca', '02'),
	('0216', 'Pomabamba', '02'),
	('0217', 'Recuay', '02'),
	('0218', 'Santa', '02'),
	('0219', 'Sihuas', '02'),
	('0220', 'Yungay', '02'),
	('0301', 'Abancay', '03'),
	('0302', 'Andahuaylas', '03'),
	('0303', 'Antabamba', '03'),
	('0304', 'Aymaraes', '03'),
	('0305', 'Cotabambas', '03'),
	('0306', 'Chincheros', '03'),
	('0307', 'Grau', '03'),
	('0401', 'Arequipa', '04'),
	('0402', 'Camaná', '04'),
	('0403', 'Caravelí', '04'),
	('0404', 'Castilla', '04'),
	('0405', 'Caylloma', '04'),
	('0406', 'Condesuyos', '04'),
	('0407', 'Islay', '04'),
	('0408', 'La Uniòn', '04'),
	('0501', 'Huamanga', '05'),
	('0502', 'Cangallo', '05'),
	('0503', 'Huanca Sancos', '05'),
	('0504', 'Huanta', '05'),
	('0505', 'La Mar', '05'),
	('0506', 'Lucanas', '05'),
	('0507', 'Parinacochas', '05'),
	('0508', 'Pàucar del Sara Sara', '05'),
	('0509', 'Sucre', '05'),
	('0510', 'Víctor Fajardo', '05'),
	('0511', 'Vilcas Huamán', '05'),
	('0601', 'Cajamarca', '06'),
	('0602', 'Cajabamba', '06'),
	('0603', 'Celendín', '06'),
	('0604', 'Chota', '06'),
	('0605', 'Contumazá', '06'),
	('0606', 'Cutervo', '06'),
	('0607', 'Hualgayoc', '06'),
	('0608', 'Jaén', '06'),
	('0609', 'San Ignacio', '06'),
	('0610', 'San Marcos', '06'),
	('0611', 'San Miguel', '06'),
	('0612', 'San Pablo', '06'),
	('0613', 'Santa Cruz', '06'),
	('0701', 'Prov. Const. del Callao', '07'),
	('0801', 'Cusco', '08'),
	('0802', 'Acomayo', '08'),
	('0803', 'Anta', '08'),
	('0804', 'Calca', '08'),
	('0805', 'Canas', '08'),
	('0806', 'Canchis', '08'),
	('0807', 'Chumbivilcas', '08'),
	('0808', 'Espinar', '08'),
	('0809', 'La Convención', '08'),
	('0810', 'Paruro', '08'),
	('0811', 'Paucartambo', '08'),
	('0812', 'Quispicanchi', '08'),
	('0813', 'Urubamba', '08'),
	('0901', 'Huancavelica', '09'),
	('0902', 'Acobamba', '09'),
	('0903', 'Angaraes', '09'),
	('0904', 'Castrovirreyna', '09'),
	('0905', 'Churcampa', '09'),
	('0906', 'Huaytará', '09'),
	('0907', 'Tayacaja', '09'),
	('1001', 'Huánuco', '10'),
	('1002', 'Ambo', '10'),
	('1003', 'Dos de Mayo', '10'),
	('1004', 'Huacaybamba', '10'),
	('1005', 'Huamalíes', '10'),
	('1006', 'Leoncio Prado', '10'),
	('1007', 'Marañón', '10'),
	('1008', 'Pachitea', '10'),
	('1009', 'Puerto Inca', '10'),
	('1010', 'Lauricocha ', '10'),
	('1011', 'Yarowilca ', '10'),
	('1101', 'Ica ', '11'),
	('1102', 'Chincha ', '11'),
	('1103', 'Nasca ', '11'),
	('1104', 'Palpa ', '11'),
	('1105', 'Pisco ', '11'),
	('1201', 'Huancayo ', '12'),
	('1202', 'Concepción ', '12'),
	('1203', 'Chanchamayo ', '12'),
	('1204', 'Jauja ', '12'),
	('1205', 'Junín ', '12'),
	('1206', 'Satipo ', '12'),
	('1207', 'Tarma ', '12'),
	('1208', 'Yauli ', '12'),
	('1209', 'Chupaca ', '12'),
	('1301', 'Trujillo ', '13'),
	('1302', 'Ascope ', '13'),
	('1303', 'Bolívar ', '13'),
	('1304', 'Chepén ', '13'),
	('1305', 'Julcán ', '13'),
	('1306', 'Otuzco ', '13'),
	('1307', 'Pacasmayo ', '13'),
	('1308', 'Pataz ', '13'),
	('1309', 'Sánchez Carrión ', '13'),
	('1310', 'Santiago de Chuco ', '13'),
	('1311', 'Gran Chimú ', '13'),
	('1312', 'Virú ', '13'),
	('1401', 'Chiclayo ', '14'),
	('1402', 'Ferreñafe ', '14'),
	('1403', 'Lambayeque ', '14'),
	('1501', 'Lima ', '15'),
	('1502', 'Barranca ', '15'),
	('1503', 'Cajatambo ', '15'),
	('1504', 'Canta ', '15'),
	('1505', 'Cañete ', '15'),
	('1506', 'Huaral ', '15'),
	('1507', 'Huarochirí ', '15'),
	('1508', 'Huaura ', '15'),
	('1509', 'Oyón ', '15'),
	('1510', 'Yauyos ', '15'),
	('1601', 'Maynas ', '16'),
	('1602', 'Alto Amazonas ', '16'),
	('1603', 'Loreto ', '16'),
	('1604', 'Mariscal Ramón Castilla ', '16'),
	('1605', 'Requena ', '16'),
	('1606', 'Ucayali ', '16'),
	('1607', 'Datem del Marañón ', '16'),
	('1608', 'Putumayo', '16'),
	('1701', 'Tambopata ', '17'),
	('1702', 'Manu ', '17'),
	('1703', 'Tahuamanu ', '17'),
	('1801', 'Mariscal Nieto ', '18'),
	('1802', 'General Sánchez Cerro ', '18'),
	('1803', 'Ilo ', '18'),
	('1901', 'Pasco ', '19'),
	('1902', 'Daniel Alcides Carrión ', '19'),
	('1903', 'Oxapampa ', '19'),
	('2001', 'Piura ', '20'),
	('2002', 'Ayabaca ', '20'),
	('2003', 'Huancabamba ', '20'),
	('2004', 'Morropón ', '20'),
	('2005', 'Paita ', '20'),
	('2006', 'Sullana ', '20'),
	('2007', 'Talara ', '20'),
	('2008', 'Sechura ', '20'),
	('2101', 'Puno ', '21'),
	('2102', 'Azángaro ', '21'),
	('2103', 'Carabaya ', '21'),
	('2104', 'Chucuito ', '21'),
	('2105', 'El Collao ', '21'),
	('2106', 'Huancané ', '21'),
	('2107', 'Lampa ', '21'),
	('2108', 'Melgar ', '21'),
	('2109', 'Moho ', '21'),
	('2110', 'San Antonio de Putina ', '21'),
	('2111', 'San Román ', '21'),
	('2112', 'Sandia ', '21'),
	('2113', 'Yunguyo ', '21'),
	('2201', 'Moyobamba ', '22'),
	('2202', 'Bellavista ', '22'),
	('2203', 'El Dorado ', '22'),
	('2204', 'Huallaga ', '22'),
	('2205', 'Lamas ', '22'),
	('2206', 'Mariscal Cáceres ', '22'),
	('2207', 'Picota ', '22'),
	('2208', 'Rioja ', '22'),
	('2209', 'San Martín ', '22'),
	('2210', 'Tocache ', '22'),
	('2301', 'Tacna ', '23'),
	('2302', 'Candarave ', '23'),
	('2303', 'Jorge Basadre ', '23'),
	('2304', 'Tarata ', '23'),
	('2401', 'Tumbes ', '24'),
	('2402', 'Contralmirante Villar ', '24'),
	('2403', 'Zarumilla ', '24'),
	('2501', 'Coronel Portillo ', '25'),
	('2502', 'Atalaya ', '25'),
	('2503', 'Padre Abad ', '25'),
	('2504', 'Purús', '25');

-- Dumping structure for table test_cms.users
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
  `deleted_user` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `ID_user` (`idUser`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  CONSTRAINT `FK_users_uverify` FOREIGN KEY (`idUser`) REFERENCES `uverify` (`iduv`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.users: ~0 rows (approximately)
INSERT INTO `users` (`idUser`, `username`, `email`, `password`, `verified`, `status`, `ip`, `signup_time`, `email_verified`, `document_verified`, `mobile_verified`, `mkpin`, `create_user`, `update_user`, `deleted_user`, `last_login`) VALUES
	('46571922665046f9167217', 'MVVsM2F4SjBSS1FvRldadmxMRGoyZz09', 'VktETy9YOW5Db2RoeStSL0RBUVh5czNsMkdkekduZ2ttTHQzQkNkUGt4UT0=', 'S1ltKy9adHNPS2NzMWJBNFhxODFCZz09', 1, 0, '127.0.0.1', '2023-09-15 21:52:01', NULL, 0, 0, '472025', '2023-09-15 14:52:01', '2023-09-15 14:52:01', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- Dumping structure for table test_cms.users_roles
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.users_roles: ~5 rows (approximately)
INSERT INTO `users_roles` (`idRol`, `name`, `description`, `required`, `default_role`) VALUES
	(1, 'Super Admin', 'Master administrator of site', 1, 9),
	(2, 'Admin', 'Site administrator', 1, 5),
	(3, 'Manager', 'Manager content', 1, 3),
	(4, 'Stantard User', 'Default site role for standard users', 1, 1),
	(5, 'Guest', 'Guest visit', 0, 0);

-- Dumping structure for table test_cms.users_sys
DROP TABLE IF EXISTS `users_sys`;
CREATE TABLE IF NOT EXISTS `users_sys` (
  `username` varchar(65) NOT NULL,
  `password` varchar(64) NOT NULL,
  `first_name` varchar(60) DEFAULT NULL,
  `last_name` varchar(60) DEFAULT NULL,
  `email` varchar(256) NOT NULL,
  `user_level` int(11) DEFAULT NULL,
  `report_to` int(11) DEFAULT NULL,
  `activated` enum('N','Y') NOT NULL DEFAULT 'N',
  `locked` enum('Y','N') DEFAULT 'N',
  `profile` text DEFAULT NULL,
  `current_URL` text DEFAULT NULL,
  `theme` varchar(30) DEFAULT 'theme-default.css',
  `menu_horizontal` enum('N','Y') DEFAULT 'Y',
  `table_width_style` enum('3','2','1') DEFAULT '2' COMMENT '1 = Scroll, 2 = Normal, 3 = 100%',
  `scroll_table_width` int(11) DEFAULT 1100,
  `scroll_table_height` int(11) DEFAULT 300,
  `rows_vertical_align_top` enum('Y','N') DEFAULT 'Y',
  `language` char(2) DEFAULT 'en',
  `Redirect_To_Last_Visited_Page_After_Login` enum('Y','N') DEFAULT 'N',
  `Font_Name` varchar(50) DEFAULT 'arial',
  `Font_Size` varchar(4) DEFAULT '13px',
  PRIMARY KEY (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.users_sys: ~0 rows (approximately)

-- Dumping structure for table test_cms.user_admin
DROP TABLE IF EXISTS `user_admin`;
CREATE TABLE IF NOT EXISTS `user_admin` (
  `user_admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `md5_hash` varchar(255) DEFAULT NULL,
  `md5_lasttime` datetime DEFAULT NULL,
  `pm_sendmail` int(11) DEFAULT NULL,
  `timestamp_login` datetime DEFAULT NULL,
  `pass_change` int(11) DEFAULT NULL,
  `timestamp_create` datetime DEFAULT NULL,
  `timestamp_update` datetime DEFAULT NULL,
  PRIMARY KEY (`user_admin_id`) USING BTREE,
  KEY `email` (`email`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.user_admin: 0 rows
/*!40000 ALTER TABLE `user_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_admin` ENABLE KEYS */;

-- Dumping structure for table test_cms.user_groups
DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_level` (`group_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.user_groups: ~0 rows (approximately)

-- Dumping structure for table test_cms.user_info
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.user_info: ~0 rows (approximately)

-- Dumping structure for table test_cms.user_jail
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.user_jail: ~0 rows (approximately)

-- Dumping structure for table test_cms.user_to_group
DROP TABLE IF EXISTS `user_to_group`;
CREATE TABLE IF NOT EXISTS `user_to_group` (
  `user_admin_id` int(11) NOT NULL,
  `user_groups_id` int(11) NOT NULL,
  PRIMARY KEY (`user_admin_id`,`user_groups_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.user_to_group: 0 rows
/*!40000 ALTER TABLE `user_to_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_to_group` ENABLE KEYS */;

-- Dumping structure for table test_cms.uverify
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.uverify: ~0 rows (approximately)
INSERT INTO `uverify` (`iduv`, `username`, `email`, `password`, `mktoken`, `mkkey`, `mkhash`, `mkpin`, `level`, `recovery_phrase`, `activation_code`, `password_key`, `pin_key`, `rp_active`, `is_activated`, `verified`, `banned`, `timestamp`) VALUES
	('46571922665046f9167217', 'pepiuox', 'contact@pepiuox.net', 'S1ltKy9adHNPS2NzMWJBNFhxODFCZz09', 'e4f01f03353bf29824cacbe944934c836d551ebf', 'e65df920bd8cbfc52ae0f39d53375243cf980a28', '08ecd4d0c7246a7a498baf977ffa887b473fe90d', '472025', 'Super Admin', NULL, NULL, NULL, NULL, 0, 1, 1, 0, '2023-10-10 16:07:39');

-- Dumping structure for table test_cms.videos
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.videos: ~0 rows (approximately)

-- Dumping structure for table test_cms.video_gal
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.video_gal: ~0 rows (approximately)

-- Dumping structure for table test_cms.visitor
DROP TABLE IF EXISTS `visitor`;
CREATE TABLE IF NOT EXISTS `visitor` (
  `ip` varchar(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.visitor: ~4 rows (approximately)
INSERT INTO `visitor` (`ip`, `timestamp`, `updated`) VALUES
	('127.0.0.1', '2023-10-01 01:51:11', '2023-10-04 06:34:03'),
	('127.0.0.1', '2023-10-03 06:39:20', '2023-10-04 06:34:03'),
	('127.0.0.1', '2023-10-10 16:07:21', '2023-10-10 23:07:43'),
	('127.0.0.1', '2023-10-11 17:21:05', '2023-10-12 08:27:23'),
	('127.0.0.1', '2023-10-13 05:36:55', '2023-10-13 12:38:12'),
	('127.0.0.1', '2023-10-24 08:06:43', '2023-10-25 00:07:05'),
	('127.0.0.1', '2023-11-09 08:07:46', '2023-11-09 15:01:14'),
	('127.0.0.1', '2023-11-25 00:47:10', '2023-11-25 06:54:02'),
	('127.0.0.1', '2023-12-04 03:15:03', '2023-12-04 09:15:04');

-- Dumping structure for table test_cms.volunteer
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
  `profession` varchar(250) DEFAULT NULL,
  `personal_interest` varchar(500) DEFAULT NULL,
  `skills` varchar(250) DEFAULT NULL,
  `allergies` varchar(150) DEFAULT NULL,
  `allergy_description` text DEFAULT NULL,
  `diseases` varchar(50) DEFAULT NULL,
  `disease_description` text DEFAULT NULL,
  `comments` varchar(50) DEFAULT NULL,
  `contact_person_name` varchar(250) DEFAULT NULL,
  `contact_person_phone` varchar(250) DEFAULT NULL,
  `contact_person_email` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table test_cms.volunteer: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
