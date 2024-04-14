-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.6.0.6823
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table grapesjs.actions_logs
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.actions_logs: ~0 rows (approximately)

-- Dumping structure for table grapesjs.active_guests
DROP TABLE IF EXISTS `active_guests`;
CREATE TABLE IF NOT EXISTS `active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.active_guests: ~2 rows (approximately)
INSERT INTO `active_guests` (`ip`, `timestamp`) VALUES
	('127.0.0.1', '2024-04-05 07:28:01'),
	('::1', '2024-04-05 17:08:49');

-- Dumping structure for table grapesjs.active_sessions
DROP TABLE IF EXISTS `active_sessions`;
CREATE TABLE IF NOT EXISTS `active_sessions` (
  `session` char(128) DEFAULT NULL,
  `date_session` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table grapesjs.active_sessions: ~0 rows (approximately)

-- Dumping structure for table grapesjs.active_users
DROP TABLE IF EXISTS `active_users`;
CREATE TABLE IF NOT EXISTS `active_users` (
  `username` varchar(65) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.active_users: ~0 rows (approximately)

-- Dumping structure for table grapesjs.admins
DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `adminid` char(64) NOT NULL DEFAULT 'uuid_short();',
  `userid` char(128) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `superadmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.admins: ~0 rows (approximately)

-- Dumping structure for table grapesjs.announcement
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

-- Dumping data for table grapesjs.announcement: ~0 rows (approximately)

-- Dumping structure for table grapesjs.articles
DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(250) DEFAULT NULL,
  `link` char(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.articles: ~0 rows (approximately)

-- Dumping structure for table grapesjs.auth_tokens
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

-- Dumping data for table grapesjs.auth_tokens: ~0 rows (approximately)

-- Dumping structure for table grapesjs.banned_users
DROP TABLE IF EXISTS `banned_users`;
CREATE TABLE IF NOT EXISTS `banned_users` (
  `user_id` char(128) NOT NULL,
  `banned_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `banned_hours` float NOT NULL,
  `hours_remaining` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.banned_users: ~0 rows (approximately)

-- Dumping structure for table grapesjs.blacklist_ip
DROP TABLE IF EXISTS `blacklist_ip`;
CREATE TABLE IF NOT EXISTS `blacklist_ip` (
  `blacklist_ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `timestamp_create` datetime DEFAULT NULL,
  PRIMARY KEY (`blacklist_ip_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.blacklist_ip: ~0 rows (approximately)

-- Dumping structure for table grapesjs.blocks
DROP TABLE IF EXISTS `blocks`;
CREATE TABLE IF NOT EXISTS `blocks` (
  `idB` int(11) NOT NULL AUTO_INCREMENT,
  `blockId` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `pageId` int(11) DEFAULT NULL,
  PRIMARY KEY (`idB`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.blocks: ~0 rows (approximately)

-- Dumping structure for table grapesjs.blocks_content
DROP TABLE IF EXISTS `blocks_content`;
CREATE TABLE IF NOT EXISTS `blocks_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blockId` int(11) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.blocks_content: ~0 rows (approximately)

-- Dumping structure for table grapesjs.blog_categories
DROP TABLE IF EXISTS `blog_categories`;
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` char(50) DEFAULT NULL,
  `description` char(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.blog_categories: ~0 rows (approximately)

-- Dumping structure for table grapesjs.blog_posts
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

-- Dumping data for table grapesjs.blog_posts: ~0 rows (approximately)

-- Dumping structure for table grapesjs.blog_post_tags
DROP TABLE IF EXISTS `blog_post_tags`;
CREATE TABLE IF NOT EXISTS `blog_post_tags` (
  `blog_post_id` int(11) NOT NULL,
  `tag_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.blog_post_tags: ~0 rows (approximately)

-- Dumping structure for table grapesjs.breadcrumblinks
DROP TABLE IF EXISTS `breadcrumblinks`;
CREATE TABLE IF NOT EXISTS `breadcrumblinks` (
  `page_title` varchar(100) NOT NULL,
  `page_url` varchar(100) NOT NULL,
  `lft` int(4) NOT NULL,
  `rgt` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.breadcrumblinks: ~0 rows (approximately)

-- Dumping structure for table grapesjs.carousel_picture
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.carousel_picture: ~0 rows (approximately)

-- Dumping structure for table grapesjs.carousel_widget
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.carousel_widget: ~0 rows (approximately)

-- Dumping structure for table grapesjs.categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.categories: ~0 rows (approximately)

-- Dumping structure for table grapesjs.comment
DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL,
  `message` varchar(250) DEFAULT NULL,
  `date` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.comment: ~0 rows (approximately)

-- Dumping structure for table grapesjs.cookies
DROP TABLE IF EXISTS `cookies`;
CREATE TABLE IF NOT EXISTS `cookies` (
  `cookieid` char(23) NOT NULL,
  `userid` char(128) NOT NULL,
  `tokenid` char(25) NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.cookies: ~0 rows (approximately)

-- Dumping structure for table grapesjs.counter
DROP TABLE IF EXISTS `counter`;
CREATE TABLE IF NOT EXISTS `counter` (
  `counter` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.counter: ~0 rows (approximately)

-- Dumping structure for table grapesjs.countries
DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.countries: ~0 rows (approximately)

-- Dumping structure for table grapesjs.currency
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

-- Dumping data for table grapesjs.currency: ~0 rows (approximately)

-- Dumping structure for table grapesjs.deleted_users
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

-- Dumping data for table grapesjs.deleted_users: ~0 rows (approximately)

-- Dumping structure for table grapesjs.domains
DROP TABLE IF EXISTS `domains`;
CREATE TABLE IF NOT EXISTS `domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_name` char(50) DEFAULT NULL,
  `short_url` char(50) DEFAULT NULL,
  `fully_url` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_name` (`domain_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table grapesjs.domains: ~0 rows (approximately)

-- Dumping structure for table grapesjs.eventlog
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

-- Dumping data for table grapesjs.eventlog: ~0 rows (approximately)

-- Dumping structure for table grapesjs.faq
DROP TABLE IF EXISTS `faq`;
CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `created` timestamp NULL DEFAULT current_timestamp(),
  `updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.faq: ~0 rows (approximately)

-- Dumping structure for table grapesjs.follow
DROP TABLE IF EXISTS `follow`;
CREATE TABLE IF NOT EXISTS `follow` (
  `follow_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.follow: ~0 rows (approximately)

-- Dumping structure for table grapesjs.forgot_pass
DROP TABLE IF EXISTS `forgot_pass`;
CREATE TABLE IF NOT EXISTS `forgot_pass` (
  `idFgp` int(11) NOT NULL,
  `username` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `password_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `create` timestamp NULL DEFAULT current_timestamp(),
  `expire` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.forgot_pass: ~0 rows (approximately)

-- Dumping structure for table grapesjs.forgot_pin
DROP TABLE IF EXISTS `forgot_pin`;
CREATE TABLE IF NOT EXISTS `forgot_pin` (
  `idFgp` int(11) NOT NULL,
  `username` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `pin_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `create` timestamp NULL DEFAULT current_timestamp(),
  `expire` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.forgot_pin: ~0 rows (approximately)

-- Dumping structure for table grapesjs.form_main
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.form_main: ~0 rows (approximately)

-- Dumping structure for table grapesjs.galleries
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

-- Dumping data for table grapesjs.galleries: ~0 rows (approximately)

-- Dumping structure for table grapesjs.gallery
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `link` varchar(100) DEFAULT '#',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.gallery: ~0 rows (approximately)

-- Dumping structure for table grapesjs.gallery_config
DROP TABLE IF EXISTS `gallery_config`;
CREATE TABLE IF NOT EXISTS `gallery_config` (
  `gallery_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_sort` varchar(255) DEFAULT NULL,
  `user_admin_id` int(11) DEFAULT NULL,
  `timestamp_update` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`gallery_config_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.gallery_config: ~0 rows (approximately)

-- Dumping structure for table grapesjs.gallery_db
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.gallery_db: ~0 rows (approximately)

-- Dumping structure for table grapesjs.gateways
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

-- Dumping data for table grapesjs.gateways: ~0 rows (approximately)

-- Dumping structure for table grapesjs.gateways_fields
DROP TABLE IF EXISTS `gateways_fields`;
CREATE TABLE IF NOT EXISTS `gateways_fields` (
  `id` int(11) NOT NULL,
  `gateway_id` int(11) DEFAULT NULL,
  `field_name` varchar(255) DEFAULT NULL,
  `field_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.gateways_fields: ~0 rows (approximately)

-- Dumping structure for table grapesjs.help
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

-- Dumping data for table grapesjs.help: ~0 rows (approximately)

-- Dumping structure for table grapesjs.help_categories
DROP TABLE IF EXISTS `help_categories`;
CREATE TABLE IF NOT EXISTS `help_categories` (
  `category_id` int(11) NOT NULL,
  `language` char(2) NOT NULL,
  `category_description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.help_categories: ~0 rows (approximately)

-- Dumping structure for table grapesjs.history
DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `id` int(11) NOT NULL,
  `user_id` char(128) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.history: ~0 rows (approximately)

-- Dumping structure for table grapesjs.image_gal
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

-- Dumping data for table grapesjs.image_gal: ~0 rows (approximately)

-- Dumping structure for table grapesjs.ip
DROP TABLE IF EXISTS `ip`;
CREATE TABLE IF NOT EXISTS `ip` (
  `id_session` char(128) DEFAULT NULL,
  `user_data` char(64) NOT NULL,
  `address` char(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.ip: ~0 rows (approximately)
INSERT INTO `ip` (`id_session`, `user_data`, `address`, `timestamp`) VALUES
	('066343939e5e060dd838f97dbd03b5787302d722', 'contact@labemotion.net', '127.0.0.1', '2024-04-05 17:15:26');

-- Dumping structure for table grapesjs.items
DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.items: ~0 rows (approximately)

-- Dumping structure for table grapesjs.languages
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

-- Dumping data for table grapesjs.languages: ~0 rows (approximately)

-- Dumping structure for table grapesjs.link_statistic
DROP TABLE IF EXISTS `link_statistic`;
CREATE TABLE IF NOT EXISTS `link_statistic` (
  `link_statistic_id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `timestamp_create` datetime DEFAULT NULL,
  PRIMARY KEY (`link_statistic_id`) USING BTREE,
  KEY `link` (`link`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.link_statistic: ~0 rows (approximately)

-- Dumping structure for table grapesjs.link_stat_mgt
DROP TABLE IF EXISTS `link_stat_mgt`;
CREATE TABLE IF NOT EXISTS `link_stat_mgt` (
  `link_stat_mgt_id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `timestamp_create` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`link_stat_mgt_id`) USING BTREE,
  KEY `url` (`url`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.link_stat_mgt: ~0 rows (approximately)

-- Dumping structure for table grapesjs.lk_sess
DROP TABLE IF EXISTS `lk_sess`;
CREATE TABLE IF NOT EXISTS `lk_sess` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.lk_sess: ~0 rows (approximately)

-- Dumping structure for table grapesjs.login_attempts
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id_session` varchar(128) DEFAULT NULL,
  `user_data` varchar(65) DEFAULT NULL,
  `ip_address` varchar(20) NOT NULL,
  `attempts` int(11) NOT NULL,
  `lastlogin` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.login_attempts: ~0 rows (approximately)

-- Dumping structure for table grapesjs.mail
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

-- Dumping data for table grapesjs.mail: ~0 rows (approximately)

-- Dumping structure for table grapesjs.mail_log
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

-- Dumping data for table grapesjs.mail_log: ~0 rows (approximately)

-- Dumping structure for table grapesjs.members
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

-- Dumping data for table grapesjs.members: ~0 rows (approximately)

-- Dumping structure for table grapesjs.member_info
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

-- Dumping data for table grapesjs.member_info: ~0 rows (approximately)

-- Dumping structure for table grapesjs.member_roles
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

-- Dumping data for table grapesjs.member_roles: ~0 rows (approximately)

-- Dumping structure for table grapesjs.menu
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

-- Dumping data for table grapesjs.menu: ~6 rows (approximately)
INSERT INTO `menu` (`idMenu`, `sort`, `page_id`, `title_page`, `link_page`, `parent_id`) VALUES
	(1, NULL, 1, 'Home', 'home', 0),
	(2, NULL, 2, 'Abous Us', 'abous-us', 0),
	(3, NULL, 3, 'Shaman', 'shaman', 2),
	(4, NULL, 4, 'Retreats', 'retreats', 0),
	(5, NULL, 5, 'Diets', 'diets', 0),
	(6, NULL, 6, 'Ayahuasca', 'ayahuasca', 0);

-- Dumping structure for table grapesjs.menu_options
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

-- Dumping data for table grapesjs.menu_options: ~2 rows (approximately)
INSERT INTO `menu_options` (`id`, `id_menu`, `fluid`, `placement`, `aligment`, `background`, `color`) VALUES
	(1, 'main_navbar', 'Yes', 'top', 'start', 'secondary', 'dark'),
	(2, 'main_menu', 'Yes', 'top', 'end', 'dark', 'dark');

-- Dumping structure for table grapesjs.multimedia_gal
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

-- Dumping data for table grapesjs.multimedia_gal: ~0 rows (approximately)

-- Dumping structure for table grapesjs.page
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

-- Dumping data for table grapesjs.page: ~6 rows (approximately)
INSERT INTO `page` (`id`, `language`, `position`, `title`, `link`, `url`, `keyword`, `classification`, `description`, `image`, `type`, `menu`, `hidden_page`, `path_file`, `script_name`, `template`, `base_template`, `content`, `style`, `startpage`, `level`, `parent`, `sort`, `active`, `created`, `updated`) VALUES
	(1, 1, 0, 'Home', 'home', NULL, 'Home', 'Home', 'Home', '29853.jpg', 'Design', 1, 0, NULL, NULL, NULL, NULL, '&amp;lt;body&amp;gt;&amp;lt;div id=&amp;quot;i5zn&amp;quot;&amp;gt;&amp;lt;img src=&amp;quot;../uploads/circulodepiccha-slideshow-1.jpg&amp;quot; id=&amp;quot;irjt&amp;quot; class=&amp;quot;img-fluid&amp;quot;/&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;section id=&amp;quot;in4g&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;py-5 about-area about-two&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;izwf&amp;quot; class=&amp;quot;py-5 text-center&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i1z3m&amp;quot; class=&amp;quot;gjs-row&amp;quot;&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i155&amp;quot; class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;ie1n&amp;quot; class=&amp;quot;mx-auto col-lg-5 col-md-7 col-10&amp;quot;&amp;gt;&amp;lt;h1 id=&amp;quot;i9ph&amp;quot; draggable=&amp;quot;true&amp;quot;&amp;gt;O my friend&amp;lt;/h1&amp;gt;&amp;lt;p id=&amp;quot;iji2h&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;mb-3&amp;quot;&amp;gt;Como estan las cosas ahora&amp;lt;br type=&amp;quot;_moz&amp;quot;/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;a id=&amp;quot;imbxq&amp;quot; draggable=&amp;quot;true&amp;quot; role=&amp;quot;button&amp;quot; data-cke-saved-href=&amp;quot;#&amp;quot; href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-primary&amp;quot;&amp;gt;Act now&amp;lt;/a&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div id=&amp;quot;id1p&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;igm3&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;id3l&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;col-lg-12&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;ik8n&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;about-title text-center&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i6w9&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;section-title&amp;quot;&amp;gt;&amp;lt;h2 id=&amp;quot;iqk1&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;fw-bold&amp;quot;&amp;gt;Our Key Features\n            &amp;lt;/h2&amp;gt;&amp;lt;p id=&amp;quot;ij0l4&amp;quot; draggable=&amp;quot;true&amp;quot;&amp;gt;\n              Lorem ipsum dolor sit amet, consectetur adipisicing elit sed do\n              eiusmod tempor incididunt ut labore et dolore magna aliqua.\n            &amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;!-- row --&amp;gt;&amp;lt;div id=&amp;quot;izhsl&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;row justify-content-center&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;iafkz&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;col-xl-5 col-lg-6 col-md-8 col-sm-11&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;ied4f&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;single-features-one-items text-center&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;ihaol&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;features-image&amp;quot;&amp;gt;&amp;lt;img src=&amp;quot;http://localhost:130/assets/images/about/about-02/viral.svg&amp;quot; id=&amp;quot;i8mfp&amp;quot; draggable=&amp;quot;true&amp;quot; alt=&amp;quot;image&amp;quot; class=&amp;quot;img-fluid&amp;quot;/&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div id=&amp;quot;iunmf&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;features-content&amp;quot;&amp;gt;&amp;lt;h3 id=&amp;quot;iji93&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;features-title&amp;quot;&amp;gt;Social Media Marketin\n            &amp;lt;/h3&amp;gt;&amp;lt;p id=&amp;quot;iomun&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;text&amp;quot;&amp;gt;\n              Lorem ipsum dolor sit amet, consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et\n              dolore magna aliqua.\n            &amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;!-- single features one items --&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div id=&amp;quot;iannt&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;col-xl-5 col-lg-6 col-md-8 col-sm-11&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i8akp&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;single-features-one-items text-center&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i7cqu&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;features-image&amp;quot;&amp;gt;&amp;lt;img src=&amp;quot;http://localhost:130/assets/images/about/about-02/remote-team.svg&amp;quot; id=&amp;quot;iyb2j&amp;quot; draggable=&amp;quot;true&amp;quot; alt=&amp;quot;image&amp;quot; class=&amp;quot;img-fluid&amp;quot;/&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div id=&amp;quot;ix8ce&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;features-content&amp;quot;&amp;gt;&amp;lt;h3 id=&amp;quot;id1nu&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;features-title&amp;quot;&amp;gt;Dedicated Team\n            &amp;lt;/h3&amp;gt;&amp;lt;p id=&amp;quot;iloaj&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;text&amp;quot;&amp;gt;\n              Lorem ipsum dolor sit amet, consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et\n              dolore magna aliqua.\n            &amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;!-- single features one items --&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;!-- row --&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;!-- container --&amp;gt;&amp;lt;/section&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin:0;}.gjs-row{display:flex;justify-content:flex-start;align-items:stretch;flex-wrap:nowrap;padding:10px;}@media (max-width: 768px){.gjs-row{flex-wrap:wrap;}}', 1, 1, 0, 0, 1, '2023-09-30 23:39:21', '2023-09-25 17:03:12'),
	(2, 1, 0, 'Abous Us', 'abous-us', NULL, 'Abous Us', 'Abous Us', 'Abous Us', 'logopao2.jpg', 'Design', 2, 0, NULL, NULL, NULL, NULL, '&amp;lt;body&amp;gt;&amp;lt;div class=&amp;quot;py-5 text-center text-primary h-100 align-items-center d-flex&amp;quot; id=&amp;quot;i6kb&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container py-5&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;mx-auto col-lg-8 col-md-10&amp;quot;&amp;gt;&amp;lt;h1 class=&amp;quot;display-3 mb-4&amp;quot;&amp;gt;A wonderful serenity&amp;lt;/h1&amp;gt;&amp;lt;p class=&amp;quot;lead mb-5&amp;quot;&amp;gt;Has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence.&amp;lt;/p&amp;gt;&amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg btn-primary mx-1&amp;quot;&amp;gt;Take me there&amp;lt;/a&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg mx-1 btn-outline-primary&amp;quot;&amp;gt;Let&amp;#039;s Go&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;py-5&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-md-12&amp;quot;&amp;gt;&amp;lt;h1&amp;gt;O my friend&amp;lt;/h1&amp;gt;&amp;lt;p class=&amp;quot;mb-4&amp;quot;&amp;gt;A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.\n                        I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.&amp;lt;/p&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-md-3 order-3 order-md-1&amp;quot;&amp;gt; &amp;lt;img src=&amp;quot;http://localhost:130/assets/images/img-placeholder-1.svg&amp;quot; class=&amp;quot;img-fluid d-block&amp;quot;/&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-md-6 col-8 d-flex flex-column justify-content-center p-3 order-1 order-md-2&amp;quot;&amp;gt;&amp;lt;h3&amp;gt;Mere tranquil existence&amp;lt;/h3&amp;gt;&amp;lt;p&amp;gt;I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.&amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-md-2 col-4 d-flex flex-column align-items-center justify-content-center order-2 order-md-2 p-3&amp;quot;&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-outline-primary mb-3&amp;quot;&amp;gt;Read more&amp;lt;/a&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-primary mb-3&amp;quot;&amp;gt;Main action&amp;lt;/a&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-link&amp;quot;&amp;gt;Link&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}#i6kb{background-image:linear-gradient(to bottom, rgba(0, 0, 0, .75), rgba(0, 0, 0, .75)), url(http://localhost:130/assets/images/cover-bubble-dark.svg);background-position:center center, center center;background-size:cover, cover;background-repeat:repeat, repeat;}', 0, 1, 0, 0, 1, '2023-09-30 23:39:21', '2023-06-02 03:59:09'),
	(3, 1, 0, 'Shaman', 'shaman', NULL, 'Shaman', 'Shaman', 'Shaman', NULL, 'Design', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, 0, 1, '2023-09-30 23:39:21', '2022-09-16 09:35:48'),
	(4, 1, 0, 'Retreats', 'retreats', NULL, 'Retreats', 'Retreats', 'Retreats', NULL, 'Design', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 0, 0, 1, '2023-09-30 23:39:21', '2022-11-15 02:46:52'),
	(5, 1, 0, 'Diets', 'diets', NULL, 'Diets', 'Diets', 'Diets', NULL, 'Design', 1, 0, NULL, NULL, NULL, NULL, '&amp;lt;body id=&amp;quot;ife5&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;icwnu&amp;quot; class=&amp;quot;py-5 text-center text-primary h-100 align-items-center d-flex&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container py-5&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;mx-auto col-lg-8 col-md-10&amp;quot;&amp;gt;&amp;lt;h1 class=&amp;quot;display-3 mb-4&amp;quot;&amp;gt;A wonderful serenity&amp;lt;/h1&amp;gt;&amp;lt;p class=&amp;quot;lead mb-5&amp;quot;&amp;gt;Has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence.&amp;lt;/p&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg btn-primary mx-1&amp;quot;&amp;gt;Take me there&amp;lt;/a&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg mx-1 btn-outline-primary&amp;quot;&amp;gt;Let&amp;#039;s Go&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;py-5 text-center&amp;quot; id=&amp;quot;i46e&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-md-8 mx-auto&amp;quot;&amp;gt;&amp;lt;p class=&amp;quot;mb-3&amp;quot;&amp;gt;&amp;quot;A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.&amp;quot;&amp;lt;/p&amp;gt;&amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-primary&amp;quot;&amp;gt;Act now!&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div id=&amp;quot;iycj1&amp;quot; class=&amp;quot;py-5 text-center&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i2m2s&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;bg-white p-4 col-10 mx-auto mx-md-0 col-lg-6&amp;quot;&amp;gt;&amp;lt;h1 id=&amp;quot;ipgac&amp;quot; draggable=&amp;quot;true&amp;quot;&amp;gt;I am so happy\n  &amp;lt;/h1&amp;gt;&amp;lt;p id=&amp;quot;itc5r&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;mb-4&amp;quot;&amp;gt;A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone.\n  &amp;lt;/p&amp;gt;&amp;lt;form method=&amp;quot;get&amp;quot; id=&amp;quot;ihzng&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;form-inline d-flex justify-content-center&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;idi6t&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;input-group&amp;quot;&amp;gt;&amp;lt;input type=&amp;quot;text&amp;quot; name=&amp;quot;name&amp;quot; id=&amp;quot;name&amp;quot; draggable=&amp;quot;true&amp;quot; placeholder=&amp;quot;Your name&amp;quot; autocomplete=&amp;quot;off&amp;quot; class=&amp;quot;form-control&amp;quot;/&amp;gt;&amp;lt;input type=&amp;quot;email&amp;quot; id=&amp;quot;form6&amp;quot; draggable=&amp;quot;true&amp;quot; placeholder=&amp;quot;Your email&amp;quot; autocomplete=&amp;quot;off&amp;quot; class=&amp;quot;form-control&amp;quot;/&amp;gt;&amp;lt;div id=&amp;quot;iot1p&amp;quot; draggable=&amp;quot;true&amp;quot; class=&amp;quot;input-group-append&amp;quot;&amp;gt;&amp;lt;button type=&amp;quot;button&amp;quot; id=&amp;quot;inbmo&amp;quot; draggable=&amp;quot;true&amp;quot; autocomplete=&amp;quot;off&amp;quot; class=&amp;quot;btn btn-primary&amp;quot;&amp;gt;Subscribe&amp;lt;/button&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/form&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;py-3&amp;quot; id=&amp;quot;ikc1t&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-lg-4 col-6 p-3&amp;quot;&amp;gt; &amp;lt;i class=&amp;quot;d-block fa fa-circle-o fa-5x text-primary&amp;quot;&amp;gt;&amp;lt;/i&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-lg-4 col-6 p-3&amp;quot;&amp;gt;&amp;lt;p&amp;gt; &amp;lt;a href=&amp;quot;https://goo.gl/maps/AUq7b9W7yYJ2&amp;quot; target=&amp;quot;_blank&amp;quot;&amp;gt; Fake street, 100\n                            &amp;lt;br/&amp;gt;NYC - 20179, USA&amp;lt;/a&amp;gt; &amp;lt;/p&amp;gt;&amp;lt;p&amp;gt; &amp;lt;a href=&amp;quot;tel:+246 - 542 550 5462&amp;quot;&amp;gt;+1 - 256 845 87 86&amp;lt;/a&amp;gt; &amp;lt;/p&amp;gt;&amp;lt;p class=&amp;quot;mb-0&amp;quot;&amp;gt; &amp;lt;a href=&amp;quot;mailto:info@PHP GrapesJS.com&amp;quot;&amp;gt;info@PHP GrapesJS.com&amp;lt;/a&amp;gt; &amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-md-4 p-3&amp;quot;&amp;gt;&amp;lt;h5&amp;gt; &amp;lt;b&amp;gt;About&amp;lt;/b&amp;gt; &amp;lt;/h5&amp;gt;&amp;lt;p class=&amp;quot;mb-0&amp;quot;&amp;gt; I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence.&amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-md-12 text-center&amp;quot;&amp;gt;&amp;lt;p class=&amp;quot;mb-0 mt-2&amp;quot;&amp;gt;&amp;copy; 2021 PHP GrapesJS. All rights reserved&amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin:0;}#icwnu{background-image:linear-gradient(to bottom, rgba(0, 0, 0, .75), rgba(0, 0, 0, .75)), url(http://localhost:130/assets/images/cover-bubble-dark.svg);background-position:center center, center center;background-size:cover, cover;background-repeat:repeat, repeat;}#iycj1{background-image:url(http://localhost:130/assets/images/cover-bubble-dark.svg);background-position:right bottom;background-size:cover;background-repeat:repeat;background-attachment:fixed;}', 0, 1, 0, 0, 1, '2023-09-30 23:39:21', '2023-10-02 08:57:19'),
	(6, 1, 0, 'Ayahuasca', 'ayahuasca', NULL, 'Ayahuasca', 'Ayahuasca', 'Ayahuasca', NULL, 'Design', 1, 0, NULL, NULL, NULL, NULL, '&amp;lt;body&amp;gt;&amp;lt;div id=&amp;quot;iyih&amp;quot; class=&amp;quot;py-5 text-center align-items-center d-flex&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container py-5&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot; id=&amp;quot;ipvi&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-md-8 mx-auto&amp;quot;&amp;gt; &amp;lt;i class=&amp;quot;d-block fa fa-stop-circle mb-3 text-muted fa-5x&amp;quot;&amp;gt;&amp;lt;/i&amp;gt;&amp;lt;h1 class=&amp;quot;display-3 mb-4&amp;quot;&amp;gt;O my friend&amp;lt;/h1&amp;gt;&amp;lt;p class=&amp;quot;lead mb-5&amp;quot;&amp;gt;Heaven and earth seem to dwell in my soul and absorb its power, like the form of a beloved mistress, then I often think with longing, Oh, would I could describe these conceptions, could impress upon paper all that is living.&amp;lt;/p&amp;gt;&amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg mx-1 btn-outline-dark&amp;quot;&amp;gt;Do&amp;lt;/a&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg btn-primary mx-1&amp;quot;&amp;gt;Something&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;gjs-row&amp;quot; id=&amp;quot;ivlba&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;gjs-cell&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-6 p-0 pr-1&amp;quot;&amp;gt;&amp;lt;img src=&amp;quot;../uploads/icon-pepiuox.png&amp;quot; id=&amp;quot;ih1m4&amp;quot; class=&amp;quot;img-fluid d-block&amp;quot;/&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-6 p-0 pl-1&amp;quot;&amp;gt;&amp;lt;img src=&amp;quot;http://localhost:130/assets/images/img-placeholder-3.svg&amp;quot; class=&amp;quot;img-fluid d-block rounded-circle&amp;quot;/&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin:0;}#iyih{background-image:linear-gradient(to left bottom, rgba(189, 195, 199, .75), rgba(44, 62, 80, .75));background-size:100%;}.gjs-row{display:flex;justify-content:flex-start;align-items:stretch;flex-wrap:nowrap;padding:10px;}.gjs-cell{min-height:75px;flex-grow:1;flex-basis:100%;}@media (max-width: 768px){.gjs-row{flex-wrap:wrap;}}', 0, 1, 0, 0, 1, '2023-09-30 23:39:21', '2023-08-25 10:17:31');

-- Dumping structure for table grapesjs.pageviews
DROP TABLE IF EXISTS `pageviews`;
CREATE TABLE IF NOT EXISTS `pageviews` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `page` char(250) DEFAULT NULL,
  `ip` char(20) DEFAULT NULL,
  `date_view` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.pageviews: ~5 rows (approximately)
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES
	(1, 'Home', '127.0.0.1', '2024-04-05 07:28:11'),
	(2, 'Home', '::1', '2024-04-05 17:08:49'),
	(3, 'Abous Us', '::1', '2024-04-05 17:09:36'),
	(4, 'Retreats', '::1', '2024-04-05 17:10:42'),
	(5, 'Home', '127.0.0.1', '2024-04-14 01:33:12');

-- Dumping structure for table grapesjs.page_menu
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.page_menu: ~0 rows (approximately)

-- Dumping structure for table grapesjs.people
DROP TABLE IF EXISTS `people`;
CREATE TABLE IF NOT EXISTS `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.people: ~0 rows (approximately)

-- Dumping structure for table grapesjs.personal_config
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

-- Dumping data for table grapesjs.personal_config: ~0 rows (approximately)

-- Dumping structure for table grapesjs.plugins_app
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

-- Dumping data for table grapesjs.plugins_app: ~34 rows (approximately)
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

-- Dumping structure for table grapesjs.preset
DROP TABLE IF EXISTS `preset`;
CREATE TABLE IF NOT EXISTS `preset` (
  `id` int(11) NOT NULL,
  `preset` char(50) DEFAULT NULL,
  `plugins` char(50) DEFAULT NULL,
  `plugins_opts` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.preset: ~0 rows (approximately)

-- Dumping structure for table grapesjs.press_gal
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

-- Dumping data for table grapesjs.press_gal: ~0 rows (approximately)

-- Dumping structure for table grapesjs.profiles
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

-- Dumping data for table grapesjs.profiles: ~0 rows (approximately)
INSERT INTO `profiles` (`idp`, `mkhash`, `firstname`, `lastname`, `gender`, `age`, `avatar`, `birthday`, `phone`, `website`, `social_media`, `profession`, `occupation`, `public_email`, `address`, `followers_count`, `profile_image`, `profile_cover`, `profile_bio`, `language`, `active`, `banned`, `date`, `update`) VALUES
	('1768770676660fa7f610b8e', '7d89d1d7b2e60f2f4d66cf2eb396c9e116990ffc', 'Jose', 'Mantilla', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2024-04-05 07:27:50', '2024-04-14 01:42:56');

-- Dumping structure for table grapesjs.role_permissions
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

-- Dumping data for table grapesjs.role_permissions: ~24 rows (approximately)
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

-- Dumping structure for table grapesjs.secrets
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

-- Dumping data for table grapesjs.secrets: ~0 rows (approximately)

-- Dumping structure for table grapesjs.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `set_time` int(11) NOT NULL,
  `data` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `session_key` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ip` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.sessions: ~0 rows (approximately)

-- Dumping structure for table grapesjs.setsession
DROP TABLE IF EXISTS `setsession`;
CREATE TABLE IF NOT EXISTS `setsession` (
  `ssID` varchar(128) NOT NULL,
  `data` mediumblob DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`ssID`),
  KEY `timestamp` (`timestamp`,`ssID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.setsession: ~0 rows (approximately)

-- Dumping structure for table grapesjs.site_configuration
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

-- Dumping data for table grapesjs.site_configuration: ~0 rows (approximately)
INSERT INTO `site_configuration` (`ID_Site`, `DOMAIN_SITE`, `SITE_NAME`, `SITE_BRAND_IMG`, `SITE_PATH`, `SITE_DESCRIPTION`, `SITE_KEYWORDS`, `SITE_CLASSIFICATION`, `SITE_EMAIL`, `SITE_IMAGE`, `SITE_ADMIN`, `SITE_CONTROL`, `SITE_CONFIG`, `SITE_LANGUAGE_1`, `SITE_LANGUAGE_2`, `FOLDER_IMAGES`, `SITE_CREATOR`, `SITE_EDITOR`, `SITE_BUILDER`, `SITE_LIST`, `NAME_CONTACT`, `PHONE_CONTACT`, `EMAIL_CONTACT`, `ADDRESS`, `TWITTER`, `FACEBOOKID`, `SKYPE`, `TELEGRAM`, `WHATSAPP`, `MAILSERVER`, `PORTSERVER`, `USEREMAIL`, `PASSMAIL`, `SUPERADMIN_NAME`, `SUPERADMIN_LEVEL`, `ADMIN_NAME`, `ADMIN_LEVEL`, `SECURE_HASH`, `SECURE_TOKEN`, `CREATE`, `UPDATED`) VALUES
	(1, 'http://www.pepiuox.net', 'PEPIUOX', 'icon-pepiuox.png', 'http://localhost:180/', 'Your description for your domains', 'Your keywords for your domains', 'Your classification for your domains', 'info@phpgrapesjs.com', 'dashboard', 'dashboard', 'users', 'siteconf', 'English', 'Spanish', 'uploads', 'admin', 'admin, editor', 'builder', 'list', 'Jose Mantilla', '0051999063645', 'contact@pepiuox.net', 'Lima - Peru', '@pepiuox', 'pepiuox30675', 'pepiuox', 'pepiuox', '+51 999063645', 'smtp.hostinger.com', '461', 'contact@pepiuox.net', 'truelove', 'Super Admin', 9, 'Admin', 5, 'ab6e7b43c06ecbb7021355026796265875c1da0006c7cdd261079f3cccf769e6', 'ckJ6w2S5Clja}ADs4kskWlorTE3jbk9ZizF6JJAL]Yr9xSHB3}F@No0(akJseo]caxZact$$YhP09&h8GZYsICC&kC44xi2$8@EfRutbmqS]ihvdx68BYYS(1$96R3ZZ', '2022-01-08 13:42:41', '2024-04-05 07:26:39');

-- Dumping structure for table grapesjs.slideshow
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

-- Dumping data for table grapesjs.slideshow: ~0 rows (approximately)

-- Dumping structure for table grapesjs.slideshow_image
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

-- Dumping data for table grapesjs.slideshow_image: ~0 rows (approximately)

-- Dumping structure for table grapesjs.social_link
DROP TABLE IF EXISTS `social_link`;
CREATE TABLE IF NOT EXISTS `social_link` (
  `social_name` varchar(20) DEFAULT NULL,
  `social_url` varchar(150) DEFAULT NULL,
  KEY `social_name` (`social_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.social_link: ~0 rows (approximately)

-- Dumping structure for table grapesjs.stats_counter
DROP TABLE IF EXISTS `stats_counter`;
CREATE TABLE IF NOT EXISTS `stats_counter` (
  `Type` varchar(50) NOT NULL DEFAULT '',
  `Variable` varchar(50) NOT NULL DEFAULT '',
  `Counter` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`Type`,`Variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.stats_counter: ~0 rows (approximately)

-- Dumping structure for table grapesjs.stats_counterlog
DROP TABLE IF EXISTS `stats_counterlog`;
CREATE TABLE IF NOT EXISTS `stats_counterlog` (
  `IP_Address` varchar(50) NOT NULL DEFAULT '',
  `Hostname` varchar(50) DEFAULT NULL,
  `First_Visit` datetime NOT NULL,
  `Last_Visit` datetime NOT NULL,
  `Counter` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`IP_Address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.stats_counterlog: ~0 rows (approximately)

-- Dumping structure for table grapesjs.stats_date
DROP TABLE IF EXISTS `stats_date`;
CREATE TABLE IF NOT EXISTS `stats_date` (
  `Year` smallint(6) NOT NULL DEFAULT 0,
  `Month` tinyint(4) NOT NULL DEFAULT 0,
  `Date` tinyint(4) NOT NULL DEFAULT 0,
  `Hits` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Date`,`Month`,`Year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.stats_date: ~0 rows (approximately)

-- Dumping structure for table grapesjs.stats_hour
DROP TABLE IF EXISTS `stats_hour`;
CREATE TABLE IF NOT EXISTS `stats_hour` (
  `Year` smallint(6) NOT NULL DEFAULT 0,
  `Month` tinyint(4) NOT NULL DEFAULT 0,
  `Date` tinyint(4) NOT NULL DEFAULT 0,
  `Hour` tinyint(4) NOT NULL DEFAULT 0,
  `Hits` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Date`,`Hour`,`Month`,`Year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.stats_hour: ~0 rows (approximately)

-- Dumping structure for table grapesjs.stats_month
DROP TABLE IF EXISTS `stats_month`;
CREATE TABLE IF NOT EXISTS `stats_month` (
  `Year` smallint(6) NOT NULL DEFAULT 0,
  `Month` tinyint(4) NOT NULL DEFAULT 0,
  `Hits` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Year`,`Month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.stats_month: ~0 rows (approximately)

-- Dumping structure for table grapesjs.stats_pages
DROP TABLE IF EXISTS `stats_pages`;
CREATE TABLE IF NOT EXISTS `stats_pages` (
  `id` int(11) DEFAULT NULL,
  `page` int(11) DEFAULT NULL,
  `ip_visitor` char(50) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.stats_pages: ~0 rows (approximately)

-- Dumping structure for table grapesjs.stats_year
DROP TABLE IF EXISTS `stats_year`;
CREATE TABLE IF NOT EXISTS `stats_year` (
  `Year` smallint(6) NOT NULL DEFAULT 0,
  `Hits` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.stats_year: ~0 rows (approximately)

-- Dumping structure for table grapesjs.sub_categories
DROP TABLE IF EXISTS `sub_categories`;
CREATE TABLE IF NOT EXISTS `sub_categories` (
  `subcat_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_category` int(11) NOT NULL,
  `sub_category_name` varchar(50) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`subcat_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.sub_categories: ~0 rows (approximately)

-- Dumping structure for table grapesjs.table_column_settings
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

-- Dumping data for table grapesjs.table_column_settings: ~5 rows (approximately)
INSERT INTO `table_column_settings` (`tqop_Id`, `table_name`, `col_name`, `col_list`, `col_add`, `col_update`, `col_view`, `col_type`, `input_type`, `joins`, `j_table`, `j_id`, `j_value`, `j_as`, `j_order`, `where`, `dependent`, `main_field`, `lookup_field`, `jvpos`) VALUES
	(1, 'menu', 'sort', 0, 0, 0, 0, 'int', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 'menu', 'page_id', 1, 1, 1, 1, 'int', 3, 'LEFT JOIN', 'page', 'id', 'title', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(3, 'menu', 'title_page', 0, 0, 0, 0, 'varchar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, 'menu', 'link_page', 0, 0, 0, 0, 'varchar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(5, 'menu', 'parent_id', 0, 0, 0, 0, 'int', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- Dumping structure for table grapesjs.table_config
DROP TABLE IF EXISTS `table_config`;
CREATE TABLE IF NOT EXISTS `table_config` (
  `tcon_Id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` text DEFAULT NULL,
  PRIMARY KEY (`tcon_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.table_config: ~0 rows (approximately)
INSERT INTO `table_config` (`tcon_Id`, `table_name`) VALUES
	(1, 'menu,page,templates,theme_base_colors,theme_base_font,theme_headings_font,theme_lead_font,theme_palette,theme_settings,themes');

-- Dumping structure for table grapesjs.table_settings
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

-- Dumping data for table grapesjs.table_settings: ~0 rows (approximately)
INSERT INTO `table_settings` (`IdTbset`, `table_name`, `table_list`, `table_add`, `table_update`, `table_delete`, `table_view`, `table_secure`) VALUES
	(1, 'menu', 1, 1, 1, 0, 1, 0);

-- Dumping structure for table grapesjs.tags
DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.tags: ~0 rows (approximately)

-- Dumping structure for table grapesjs.templates
DROP TABLE IF EXISTS `templates`;
CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `templates` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.templates: ~0 rows (approximately)

-- Dumping structure for table grapesjs.themes
DROP TABLE IF EXISTS `themes`;
CREATE TABLE IF NOT EXISTS `themes` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` char(50) NOT NULL,
  `theme_bootstrap` char(50) NOT NULL,
  `base_default` enum('Yes','No') NOT NULL DEFAULT 'No',
  `active_theme` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`theme_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.themes: ~0 rows (approximately)
INSERT INTO `themes` (`theme_id`, `theme_name`, `theme_bootstrap`, `base_default`, `active_theme`) VALUES
	(1, 'test theme color', 'lumen', 'Yes', 'Yes');

-- Dumping structure for table grapesjs.theme_base_colors
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

-- Dumping data for table grapesjs.theme_base_colors: ~0 rows (approximately)
INSERT INTO `theme_base_colors` (`idtbc`, `body_color`, `text_color`, `links_color`) VALUES
	(1, '#ffffff', '#2f0202', '#172fa2');

-- Dumping structure for table grapesjs.theme_base_font
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

-- Dumping data for table grapesjs.theme_base_font: ~0 rows (approximately)
INSERT INTO `theme_base_font` (`idtbf`, `family`, `size`, `weight`, `line_height`) VALUES
	(1, '', '', 'default', '');

-- Dumping structure for table grapesjs.theme_headings_font
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

-- Dumping data for table grapesjs.theme_headings_font: ~0 rows (approximately)
INSERT INTO `theme_headings_font` (`idthf`, `family`, `weight`, `line_weight`) VALUES
	(1, '', 'default', '');

-- Dumping structure for table grapesjs.theme_lead_font
DROP TABLE IF EXISTS `theme_lead_font`;
CREATE TABLE IF NOT EXISTS `theme_lead_font` (
  `idtlf` int(11) NOT NULL AUTO_INCREMENT,
  `size` char(6) DEFAULT NULL,
  `weight` char(6) DEFAULT NULL,
  PRIMARY KEY (`idtlf`) USING BTREE,
  UNIQUE KEY `theme_id` (`idtlf`) USING BTREE,
  CONSTRAINT `FK_theme_lead_font` FOREIGN KEY (`idtlf`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.theme_lead_font: ~0 rows (approximately)
INSERT INTO `theme_lead_font` (`idtlf`, `size`, `weight`) VALUES
	(1, '', '');

-- Dumping structure for table grapesjs.theme_palette
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

-- Dumping data for table grapesjs.theme_palette: ~0 rows (approximately)
INSERT INTO `theme_palette` (`idtp`, `primary_color`, `secondary_color`, `info_color`, `light_color`, `dark_color`, `success_color`, `warning_color`, `danger_color`, `custom_color`, `custom_light_color`, `custom_dark_color`) VALUES
	(1, '#993f3f', '#814040', '#1f53a5', '#000000', '#4b3737', '#000000', '#d24747', '#000000', '#000000', '#d4b8b8', '#000000');

-- Dumping structure for table grapesjs.theme_settings
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

-- Dumping data for table grapesjs.theme_settings: ~0 rows (approximately)
INSERT INTO `theme_settings` (`idts`, `container`, `spacer`, `radius`, `radius_sm`, `radius_lg`, `font_size`) VALUES
	(1, 'default', 'default', NULL, NULL, NULL, 'default');

-- Dumping structure for table grapesjs.timezone
DROP TABLE IF EXISTS `timezone`;
CREATE TABLE IF NOT EXISTS `timezone` (
  `Timezone` varchar(50) NOT NULL,
  `Default` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`Timezone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.timezone: ~0 rows (approximately)

-- Dumping structure for table grapesjs.tokens
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

-- Dumping data for table grapesjs.tokens: ~0 rows (approximately)

-- Dumping structure for table grapesjs.total_visitors
DROP TABLE IF EXISTS `total_visitors`;
CREATE TABLE IF NOT EXISTS `total_visitors` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `session` char(128) DEFAULT NULL,
  `time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.total_visitors: ~2 rows (approximately)
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES
	(1, 'ZGVXS0Q0UDF6dFVkdFhsUXo0ODlLdz09', '2024-04-14 08:43:01'),
	(2, 'cFpGeHdIcmd1aGZYZ215UTdZYTg2Zz09', '2024-04-06 00:13:20');

-- Dumping structure for table grapesjs.type_blocks
DROP TABLE IF EXISTS `type_blocks`;
CREATE TABLE IF NOT EXISTS `type_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_block` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.type_blocks: ~0 rows (approximately)

-- Dumping structure for table grapesjs.type_gallery
DROP TABLE IF EXISTS `type_gallery`;
CREATE TABLE IF NOT EXISTS `type_gallery` (
  `idTG` int(11) NOT NULL AUTO_INCREMENT,
  `type_gallery` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idTG`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.type_gallery: ~0 rows (approximately)

-- Dumping structure for table grapesjs.type_menu
DROP TABLE IF EXISTS `type_menu`;
CREATE TABLE IF NOT EXISTS `type_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_menu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.type_menu: ~0 rows (approximately)

-- Dumping structure for table grapesjs.type_page
DROP TABLE IF EXISTS `type_page`;
CREATE TABLE IF NOT EXISTS `type_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_page` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.type_page: ~0 rows (approximately)

-- Dumping structure for table grapesjs.users
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

-- Dumping data for table grapesjs.users: ~1 rows (approximately)
INSERT INTO `users` (`idUser`, `username`, `email`, `password`, `verified`, `status`, `ip`, `signup_time`, `email_verified`, `document_verified`, `mobile_verified`, `mkpin`, `create_user`, `update_user`, `deleted_user`, `last_login`) VALUES
	('1768770676660fa7f610b8e', 'ZXVsdUdaeHFTaXlQWUJ0ckNrcGsvdz09', 'N2hYZGpaaVhsVFhxVE5ncTdWL1k4eUtiSVhjSGF4YkkzMXQ5RnF1MzFXcz0=', 'K3luRnViVUUveHg0TmJzbUhYZEM2Zz09', 1, 0, '127.0.0.1', '2024-04-05 14:27:50', NULL, 0, 0, '665970', '2024-04-05 07:27:50', '2024-04-05 07:27:50', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- Dumping structure for table grapesjs.users_roles
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

-- Dumping data for table grapesjs.users_roles: ~5 rows (approximately)
INSERT INTO `users_roles` (`idRol`, `name`, `description`, `required`, `default_role`) VALUES
	(1, 'Super Admin', 'Master administrator of site', 1, 9),
	(2, 'Admin', 'Site administrator', 1, 5),
	(3, 'Manager', 'Manager content', 1, 3),
	(4, 'Stantard User', 'Default site role for standard users', 1, 1),
	(5, 'Guest', 'Guest visit', 0, 0);

-- Dumping structure for table grapesjs.users_sys
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

-- Dumping data for table grapesjs.users_sys: ~0 rows (approximately)

-- Dumping structure for table grapesjs.user_admin
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.user_admin: ~0 rows (approximately)

-- Dumping structure for table grapesjs.user_groups
DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_level` (`group_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.user_groups: ~0 rows (approximately)

-- Dumping structure for table grapesjs.user_info
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

-- Dumping data for table grapesjs.user_info: ~0 rows (approximately)

-- Dumping structure for table grapesjs.user_jail
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

-- Dumping data for table grapesjs.user_jail: ~0 rows (approximately)

-- Dumping structure for table grapesjs.user_to_group
DROP TABLE IF EXISTS `user_to_group`;
CREATE TABLE IF NOT EXISTS `user_to_group` (
  `user_admin_id` int(11) NOT NULL,
  `user_groups_id` int(11) NOT NULL,
  PRIMARY KEY (`user_admin_id`,`user_groups_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.user_to_group: ~0 rows (approximately)

-- Dumping structure for table grapesjs.uverify
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

-- Dumping data for table grapesjs.uverify: ~0 rows (approximately)
INSERT INTO `uverify` (`iduv`, `username`, `email`, `password`, `mktoken`, `mkkey`, `mkhash`, `mkpin`, `level`, `recovery_phrase`, `activation_code`, `password_key`, `pin_key`, `rp_active`, `is_activated`, `verified`, `banned`, `timestamp`) VALUES
	('1768770676660fa7f610b8e', 'pepiuox', 'contact@labemotion.net', 'K3luRnViVUUveHg0TmJzbUhYZEM2Zz09', '5014056631b21995f7bbec118e290c7b602c3f97', '4382e79d966262a9c98b8029ede60da4a4f9ed67', '7d89d1d7b2e60f2f4d66cf2eb396c9e116990ffc', '665970', 'Super Admin', NULL, NULL, NULL, NULL, 0, 1, 1, 0, '2024-04-14 01:42:56');

-- Dumping structure for table grapesjs.videos
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

-- Dumping data for table grapesjs.videos: ~0 rows (approximately)

-- Dumping structure for table grapesjs.video_gal
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

-- Dumping data for table grapesjs.video_gal: ~0 rows (approximately)

-- Dumping structure for table grapesjs.visitor
DROP TABLE IF EXISTS `visitor`;
CREATE TABLE IF NOT EXISTS `visitor` (
  `ip` varchar(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table grapesjs.visitor: ~3 rows (approximately)
INSERT INTO `visitor` (`ip`, `timestamp`, `updated`) VALUES
	('127.0.0.1', '2024-04-05 07:28:01', '2024-04-06 00:15:33'),
	('::1', '2024-04-05 17:08:49', '2024-04-06 00:13:20'),
	('127.0.0.1', '2024-04-14 01:33:12', '2024-04-14 08:43:01');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
