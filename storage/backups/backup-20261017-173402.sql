-- Respaldo logico generado por tools/backup-db.php
-- Base de datos: phpgrapesjs
-- Fecha: 2026-10-17T17:34:02+00:00

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `actions_logs`;
CREATE TABLE `actions_logs` (
  `actions_logs_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_login` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `actions` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `timestamp_create` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`actions_logs_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `active_guests`;
CREATE TABLE `active_guests` (
  `ip` varchar(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `active_sessions`;
CREATE TABLE `active_sessions` (
  `session` char(128) DEFAULT NULL,
  `access` char(128) DEFAULT NULL,
  `date_session` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `active_users`;
CREATE TABLE `active_users` (
  `username` varchar(256) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `adminid` char(128) NOT NULL,
  `usercode` char(128) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `superadmin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`adminid`),
  KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `admins` (`adminid`, `usercode`, `is_active`, `superadmin`) VALUES ('100546964394868738', 'Xn46z0PGr9Y5MlFkBVkjyp&N4OcMo%@TXrIf[$45tGRG7AJeRx9#3viK66468102', '1', '1');

DROP TABLE IF EXISTS `admins_users`;
CREATE TABLE `admins_users` (
  `admin_id` char(128) NOT NULL,
  `admincode` char(128) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_type` char(50) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `mk_hash` varchar(255) DEFAULT NULL,
  `mk_lasttime` datetime DEFAULT NULL,
  `pm_sendmail` int(11) DEFAULT NULL,
  `timestamp_login` datetime DEFAULT NULL,
  `pass_change` int(11) DEFAULT NULL,
  `timestamp_create` datetime DEFAULT NULL,
  `timestamp_update` datetime DEFAULT NULL,
  PRIMARY KEY (`admin_id`) USING BTREE,
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `admins_users` (`admin_id`, `admincode`, `username`, `email`, `password`, `user_type`, `first_name`, `last_name`, `birthday`, `gender`, `address`, `phone`, `picture`, `is_active`, `session_id`, `mk_hash`, `mk_lasttime`, `pm_sendmail`, `timestamp_login`, `pass_change`, `timestamp_create`, `timestamp_update`) VALUES ('100546964394868738', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `announcement`;
CREATE TABLE `announcement` (
  `announcement_id` int(10) unsigned NOT NULL,
  `is_active` enum('N','Y') NOT NULL DEFAULT 'N',
  `topic` int(11) NOT NULL,
  `message` mediumtext NOT NULL,
  `date_lastupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `auto_publish` enum('Y','N') DEFAULT 'N',
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(200) DEFAULT NULL,
  `translated_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `blacklist_ip`;
CREATE TABLE `blacklist_ip` (
  `blacklist_ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `timestamp_create` datetime DEFAULT NULL,
  PRIMARY KEY (`blacklist_ip_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `blog_categories`;
CREATE TABLE `blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` char(50) DEFAULT NULL,
  `description` char(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `blog_post_tags`;
CREATE TABLE `blog_post_tags` (
  `blog_post_id` int(11) NOT NULL,
  `tag_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `hidden_blog` tinyint(1) DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  `date_posted` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`categoryId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `components`;
CREATE TABLE `components` (
  `id` int(11) NOT NULL,
  `name_component` varchar(50) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `version` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `counter`;
CREATE TABLE `counter` (
  `counter` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `counter` (`counter`) VALUES ('85');

DROP TABLE IF EXISTS `deleted_users`;
CREATE TABLE `deleted_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usercode` char(128) NOT NULL,
  `username` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `districts`;
CREATE TABLE `districts` (
  `idDist` char(6) NOT NULL,
  `name` varchar(50) NOT NULL,
  `region_id` char(6) NOT NULL,
  `province_id` char(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idDist`),
  UNIQUE KEY `idDist` (`idDist`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010101', 'Chachapoyas', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010102', 'Asunción', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010103', 'Balsas', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010104', 'Cheto', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010105', 'Chiliquin', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010106', 'Chuquibamba', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010107', 'Granada', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010108', 'Huancas', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010109', 'La Jalca', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010110', 'Leimebamba', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010111', 'Levanto', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010112', 'Magdalena', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010113', 'Mariscal Castilla', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010114', 'Molinopampa', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010115', 'Montevideo', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010116', 'Olleros', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010117', 'Quinjalca', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010118', 'San Francisco de Daguas', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010119', 'San Isidro de Maino', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010120', 'Soloco', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010121', 'Sonche', '010100', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010201', 'Bagua', '010200', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010202', 'Aramango', '010200', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010203', 'Copallin', '010200', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010204', 'El Parco', '010200', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010205', 'Imaza', '010200', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010206', 'La Peca', '010200', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010301', 'Jumbilla', '010300', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010302', 'Chisquilla', '010300', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010303', 'Churuja', '010300', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010304', 'Corosha', '010300', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010305', 'Cuispes', '010300', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010306', 'Florida', '010300', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010307', 'Jazan', '010300', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010308', 'Recta', '010300', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010309', 'San Carlos', '010300', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010310', 'Shipasbamba', '010300', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010311', 'Valera', '010300', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010312', 'Yambrasbamba', '010300', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010401', 'Nieva', '010400', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010402', 'El Cenepa', '010400', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010403', 'Río Santiago', '010400', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010501', 'Lamud', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010502', 'Camporredondo', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010503', 'Cocabamba', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010504', 'Colcamar', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010505', 'Conila', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010506', 'Inguilpata', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010507', 'Longuita', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010508', 'Lonya Chico', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010509', 'Luya', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010510', 'Luya Viejo', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010511', 'María', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010512', 'Ocalli', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010513', 'Ocumal', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010514', 'Pisuquia', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010515', 'Providencia', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010516', 'San Cristóbal', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010517', 'San Francisco de Yeso', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010518', 'San Jerónimo', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010519', 'San Juan de Lopecancha', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010520', 'Santa Catalina', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010521', 'Santo Tomas', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010522', 'Tingo', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010523', 'Trita', '010500', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010601', 'San Nicolás', '010600', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010602', 'Chirimoto', '010600', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010603', 'Cochamal', '010600', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010604', 'Huambo', '010600', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010605', 'Limabamba', '010600', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010606', 'Longar', '010600', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010607', 'Mariscal Benavides', '010600', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010608', 'Milpuc', '010600', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010609', 'Omia', '010600', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010610', 'Santa Rosa', '010600', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010611', 'Totora', '010600', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010612', 'Vista Alegre', '010600', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010701', 'Bagua Grande', '010700', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010702', 'Cajaruro', '010700', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010703', 'Cumba', '010700', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010704', 'El Milagro', '010700', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010705', 'Jamalca', '010700', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010706', 'Lonya Grande', '010700', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('010707', 'Yamon', '010700', '010000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020101', 'Huaraz', '020100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020102', 'Cochabamba', '020100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020103', 'Colcabamba', '020100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020104', 'Huanchay', '020100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020105', 'Independencia', '020100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020106', 'Jangas', '020100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020107', 'La Libertad', '020100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020108', 'Olleros', '020100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020109', 'Pampas Grande', '020100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020110', 'Pariacoto', '020100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020111', 'Pira', '020100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020112', 'Tarica', '020100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020201', 'Aija', '020200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020202', 'Coris', '020200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020203', 'Huacllan', '020200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020204', 'La Merced', '020200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020205', 'Succha', '020200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020301', 'Llamellin', '020300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020302', 'Aczo', '020300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020303', 'Chaccho', '020300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020304', 'Chingas', '020300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020305', 'Mirgas', '020300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020306', 'San Juan de Rontoy', '020300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020401', 'Chacas', '020400', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020402', 'Acochaca', '020400', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020501', 'Chiquian', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020502', 'Abelardo Pardo Lezameta', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020503', 'Antonio Raymondi', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020504', 'Aquia', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020505', 'Cajacay', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020506', 'Canis', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020507', 'Colquioc', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020508', 'Huallanca', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020509', 'Huasta', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020510', 'Huayllacayan', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020511', 'La Primavera', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020512', 'Mangas', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020513', 'Pacllon', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020514', 'San Miguel de Corpanqui', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020515', 'Ticllos', '020500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020601', 'Carhuaz', '020600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020602', 'Acopampa', '020600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020603', 'Amashca', '020600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020604', 'Anta', '020600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020605', 'Ataquero', '020600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020606', 'Marcara', '020600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020607', 'Pariahuanca', '020600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020608', 'San Miguel de Aco', '020600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020609', 'Shilla', '020600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020610', 'Tinco', '020600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020611', 'Yungar', '020600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020701', 'San Luis', '020700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020702', 'San Nicolás', '020700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020703', 'Yauya', '020700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020801', 'Casma', '020800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020802', 'Buena Vista Alta', '020800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020803', 'Comandante Noel', '020800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020804', 'Yautan', '020800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020901', 'Corongo', '020900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020902', 'Aco', '020900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020903', 'Bambas', '020900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020904', 'Cusca', '020900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020905', 'La Pampa', '020900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020906', 'Yanac', '020900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('020907', 'Yupan', '020900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021001', 'Huari', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021002', 'Anra', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021003', 'Cajay', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021004', 'Chavin de Huantar', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021005', 'Huacachi', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021006', 'Huacchis', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021007', 'Huachis', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021008', 'Huantar', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021009', 'Masin', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021010', 'Paucas', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021011', 'Ponto', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021012', 'Rahuapampa', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021013', 'Rapayan', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021014', 'San Marcos', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021015', 'San Pedro de Chana', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021016', 'Uco', '021000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021101', 'Huarmey', '021100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021102', 'Cochapeti', '021100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021103', 'Culebras', '021100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021104', 'Huayan', '021100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021105', 'Malvas', '021100', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021201', 'Caraz', '021200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021202', 'Huallanca', '021200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021203', 'Huata', '021200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021204', 'Huaylas', '021200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021205', 'Mato', '021200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021206', 'Pamparomas', '021200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021207', 'Pueblo Libre', '021200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021208', 'Santa Cruz', '021200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021209', 'Santo Toribio', '021200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021210', 'Yuracmarca', '021200', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021301', 'Piscobamba', '021300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021302', 'Casca', '021300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021303', 'Eleazar Guzmán Barron', '021300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021304', 'Fidel Olivas Escudero', '021300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021305', 'Llama', '021300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021306', 'Llumpa', '021300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021307', 'Lucma', '021300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021308', 'Musga', '021300', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021401', 'Ocros', '021400', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021402', 'Acas', '021400', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021403', 'Cajamarquilla', '021400', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021404', 'Carhuapampa', '021400', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021405', 'Cochas', '021400', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021406', 'Congas', '021400', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021407', 'Llipa', '021400', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021408', 'San Cristóbal de Rajan', '021400', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021409', 'San Pedro', '021400', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021410', 'Santiago de Chilcas', '021400', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021501', 'Cabana', '021500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021502', 'Bolognesi', '021500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021503', 'Conchucos', '021500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021504', 'Huacaschuque', '021500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021505', 'Huandoval', '021500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021506', 'Lacabamba', '021500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021507', 'Llapo', '021500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021508', 'Pallasca', '021500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021509', 'Pampas', '021500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021510', 'Santa Rosa', '021500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021511', 'Tauca', '021500', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021601', 'Pomabamba', '021600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021602', 'Huayllan', '021600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021603', 'Parobamba', '021600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021604', 'Quinuabamba', '021600', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021701', 'Recuay', '021700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021702', 'Catac', '021700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021703', 'Cotaparaco', '021700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021704', 'Huayllapampa', '021700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021705', 'Llacllin', '021700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021706', 'Marca', '021700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021707', 'Pampas Chico', '021700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021708', 'Pararin', '021700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021709', 'Tapacocha', '021700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021710', 'Ticapampa', '021700', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021801', 'Chimbote', '021800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021802', 'Cáceres del Perú', '021800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021803', 'Coishco', '021800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021804', 'Macate', '021800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021805', 'Moro', '021800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021806', 'Nepeña', '021800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021807', 'Samanco', '021800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021808', 'Santa', '021800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021809', 'Nuevo Chimbote', '021800', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021901', 'Sihuas', '021900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021902', 'Acobamba', '021900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021903', 'Alfonso Ugarte', '021900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021904', 'Cashapampa', '021900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021905', 'Chingalpo', '021900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021906', 'Huayllabamba', '021900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021907', 'Quiches', '021900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021908', 'Ragash', '021900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021909', 'San Juan', '021900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('021910', 'Sicsibamba', '021900', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('022001', 'Yungay', '022000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('022002', 'Cascapara', '022000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('022003', 'Mancos', '022000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('022004', 'Matacoto', '022000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('022005', 'Quillo', '022000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('022006', 'Ranrahirca', '022000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('022007', 'Shupluy', '022000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('022008', 'Yanama', '022000', '020000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030101', 'Abancay', '030100', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030102', 'Chacoche', '030100', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030103', 'Circa', '030100', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030104', 'Curahuasi', '030100', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030105', 'Huanipaca', '030100', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030106', 'Lambrama', '030100', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030107', 'Pichirhua', '030100', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030108', 'San Pedro de Cachora', '030100', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030109', 'Tamburco', '030100', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030201', 'Andahuaylas', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030202', 'Andarapa', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030203', 'Chiara', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030204', 'Huancarama', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030205', 'Huancaray', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030206', 'Huayana', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030207', 'Kishuara', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030208', 'Pacobamba', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030209', 'Pacucha', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030210', 'Pampachiri', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030211', 'Pomacocha', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030212', 'San Antonio de Cachi', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030213', 'San Jerónimo', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030214', 'San Miguel de Chaccrampa', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030215', 'Santa María de Chicmo', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030216', 'Talavera', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030217', 'Tumay Huaraca', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030218', 'Turpo', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030219', 'Kaquiabamba', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030220', 'José María Arguedas', '030200', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030301', 'Antabamba', '030300', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030302', 'El Oro', '030300', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030303', 'Huaquirca', '030300', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030304', 'Juan Espinoza Medrano', '030300', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030305', 'Oropesa', '030300', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030306', 'Pachaconas', '030300', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030307', 'Sabaino', '030300', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030401', 'Chalhuanca', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030402', 'Capaya', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030403', 'Caraybamba', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030404', 'Chapimarca', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030405', 'Colcabamba', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030406', 'Cotaruse', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030407', 'Ihuayllo', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030408', 'Justo Apu Sahuaraura', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030409', 'Lucre', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030410', 'Pocohuanca', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030411', 'San Juan de Chacña', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030412', 'Sañayca', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030413', 'Soraya', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030414', 'Tapairihua', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030415', 'Tintay', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030416', 'Toraya', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030417', 'Yanaca', '030400', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030501', 'Tambobamba', '030500', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030502', 'Cotabambas', '030500', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030503', 'Coyllurqui', '030500', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030504', 'Haquira', '030500', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030505', 'Mara', '030500', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030506', 'Challhuahuacho', '030500', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030601', 'Chincheros', '030600', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030602', 'Anco_Huallo', '030600', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030603', 'Cocharcas', '030600', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030604', 'Huaccana', '030600', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030605', 'Ocobamba', '030600', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030606', 'Ongoy', '030600', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030607', 'Uranmarca', '030600', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030608', 'Ranracancha', '030600', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030609', 'Rocchacc', '030600', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030610', 'El Porvenir', '030600', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030611', 'Los Chankas', '030600', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030701', 'Chuquibambilla', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030702', 'Curpahuasi', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030703', 'Gamarra', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030704', 'Huayllati', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030705', 'Mamara', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030706', 'Micaela Bastidas', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030707', 'Pataypampa', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030708', 'Progreso', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030709', 'San Antonio', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030710', 'Santa Rosa', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030711', 'Turpay', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030712', 'Vilcabamba', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030713', 'Virundo', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('030714', 'Curasco', '030700', '030000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040101', 'Arequipa', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040102', 'Alto Selva Alegre', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040103', 'Cayma', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040104', 'Cerro Colorado', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040105', 'Characato', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040106', 'Chiguata', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040107', 'Jacobo Hunter', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040108', 'La Joya', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040109', 'Mariano Melgar', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040110', 'Miraflores', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040111', 'Mollebaya', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040112', 'Paucarpata', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040113', 'Pocsi', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040114', 'Polobaya', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040115', 'Quequeña', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040116', 'Sabandia', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040117', 'Sachaca', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040118', 'San Juan de Siguas', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040119', 'San Juan de Tarucani', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040120', 'Santa Isabel de Siguas', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040121', 'Santa Rita de Siguas', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040122', 'Socabaya', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040123', 'Tiabaya', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040124', 'Uchumayo', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040125', 'Vitor', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040126', 'Yanahuara', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040127', 'Yarabamba', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040128', 'Yura', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040129', 'José Luis Bustamante Y Rivero', '040100', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040201', 'Camaná', '040200', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040202', 'José María Quimper', '040200', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040203', 'Mariano Nicolás Valcárcel', '040200', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040204', 'Mariscal Cáceres', '040200', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040205', 'Nicolás de Pierola', '040200', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040206', 'Ocoña', '040200', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040207', 'Quilca', '040200', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040208', 'Samuel Pastor', '040200', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040301', 'Caravelí', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040302', 'Acarí', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040303', 'Atico', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040304', 'Atiquipa', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040305', 'Bella Unión', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040306', 'Cahuacho', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040307', 'Chala', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040308', 'Chaparra', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040309', 'Huanuhuanu', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040310', 'Jaqui', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040311', 'Lomas', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040312', 'Quicacha', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040313', 'Yauca', '040300', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040401', 'Aplao', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040402', 'Andagua', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040403', 'Ayo', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040404', 'Chachas', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040405', 'Chilcaymarca', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040406', 'Choco', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040407', 'Huancarqui', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040408', 'Machaguay', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040409', 'Orcopampa', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040410', 'Pampacolca', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040411', 'Tipan', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040412', 'Uñon', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040413', 'Uraca', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040414', 'Viraco', '040400', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040501', 'Chivay', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040502', 'Achoma', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040503', 'Cabanaconde', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040504', 'Callalli', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040505', 'Caylloma', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040506', 'Coporaque', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040507', 'Huambo', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040508', 'Huanca', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040509', 'Ichupampa', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040510', 'Lari', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040511', 'Lluta', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040512', 'Maca', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040513', 'Madrigal', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040514', 'San Antonio de Chuca', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040515', 'Sibayo', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040516', 'Tapay', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040517', 'Tisco', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040518', 'Tuti', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040519', 'Yanque', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040520', 'Majes', '040500', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040601', 'Chuquibamba', '040600', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040602', 'Andaray', '040600', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040603', 'Cayarani', '040600', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040604', 'Chichas', '040600', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040605', 'Iray', '040600', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040606', 'Río Grande', '040600', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040607', 'Salamanca', '040600', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040608', 'Yanaquihua', '040600', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040701', 'Mollendo', '040700', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040702', 'Cocachacra', '040700', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040703', 'Dean Valdivia', '040700', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040704', 'Islay', '040700', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040705', 'Mejia', '040700', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040706', 'Punta de Bombón', '040700', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040801', 'Cotahuasi', '040800', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040802', 'Alca', '040800', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040803', 'Charcana', '040800', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040804', 'Huaynacotas', '040800', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040805', 'Pampamarca', '040800', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040806', 'Puyca', '040800', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040807', 'Quechualla', '040800', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040808', 'Sayla', '040800', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040809', 'Tauria', '040800', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040810', 'Tomepampa', '040800', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('040811', 'Toro', '040800', '040000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050101', 'Ayacucho', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050102', 'Acocro', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050103', 'Acos Vinchos', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050104', 'Carmen Alto', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050105', 'Chiara', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050106', 'Ocros', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050107', 'Pacaycasa', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050108', 'Quinua', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050109', 'San José de Ticllas', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050110', 'San Juan Bautista', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050111', 'Santiago de Pischa', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050112', 'Socos', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050113', 'Tambillo', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050114', 'Vinchos', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050115', 'Jesús Nazareno', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050116', 'Andrés Avelino Cáceres Dorregaray', '050100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050201', 'Cangallo', '050200', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050202', 'Chuschi', '050200', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050203', 'Los Morochucos', '050200', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050204', 'María Parado de Bellido', '050200', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050205', 'Paras', '050200', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050206', 'Totos', '050200', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050301', 'Sancos', '050300', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050302', 'Carapo', '050300', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050303', 'Sacsamarca', '050300', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050304', 'Santiago de Lucanamarca', '050300', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050401', 'Huanta', '050400', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050402', 'Ayahuanco', '050400', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050403', 'Huamanguilla', '050400', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050404', 'Iguain', '050400', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050405', 'Luricocha', '050400', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050406', 'Santillana', '050400', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050407', 'Sivia', '050400', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050408', 'Llochegua', '050400', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050409', 'Canayre', '050400', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050410', 'Uchuraccay', '050400', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050411', 'Pucacolpa', '050400', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050412', 'Chaca', '050400', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050501', 'San Miguel', '050500', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050502', 'Anco', '050500', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050503', 'Ayna', '050500', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050504', 'Chilcas', '050500', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050505', 'Chungui', '050500', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050506', 'Luis Carranza', '050500', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050507', 'Santa Rosa', '050500', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050508', 'Tambo', '050500', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050509', 'Samugari', '050500', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050510', 'Anchihuay', '050500', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050511', 'Oronccoy', '050500', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050601', 'Puquio', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050602', 'Aucara', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050603', 'Cabana', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050604', 'Carmen Salcedo', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050605', 'Chaviña', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050606', 'Chipao', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050607', 'Huac-Huas', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050608', 'Laramate', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050609', 'Leoncio Prado', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050610', 'Llauta', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050611', 'Lucanas', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050612', 'Ocaña', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050613', 'Otoca', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050614', 'Saisa', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050615', 'San Cristóbal', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050616', 'San Juan', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050617', 'San Pedro', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050618', 'San Pedro de Palco', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050619', 'Sancos', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050620', 'Santa Ana de Huaycahuacho', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050621', 'Santa Lucia', '050600', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050701', 'Coracora', '050700', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050702', 'Chumpi', '050700', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050703', 'Coronel Castañeda', '050700', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050704', 'Pacapausa', '050700', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050705', 'Pullo', '050700', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050706', 'Puyusca', '050700', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050707', 'San Francisco de Ravacayco', '050700', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050708', 'Upahuacho', '050700', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050801', 'Pausa', '050800', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050802', 'Colta', '050800', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050803', 'Corculla', '050800', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050804', 'Lampa', '050800', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050805', 'Marcabamba', '050800', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050806', 'Oyolo', '050800', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050807', 'Pararca', '050800', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050808', 'San Javier de Alpabamba', '050800', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050809', 'San José de Ushua', '050800', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050810', 'Sara Sara', '050800', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050901', 'Querobamba', '050900', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050902', 'Belén', '050900', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050903', 'Chalcos', '050900', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050904', 'Chilcayoc', '050900', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050905', 'Huacaña', '050900', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050906', 'Morcolla', '050900', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050907', 'Paico', '050900', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050908', 'San Pedro de Larcay', '050900', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050909', 'San Salvador de Quije', '050900', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050910', 'Santiago de Paucaray', '050900', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('050911', 'Soras', '050900', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051001', 'Huancapi', '051000', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051002', 'Alcamenca', '051000', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051003', 'Apongo', '051000', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051004', 'Asquipata', '051000', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051005', 'Canaria', '051000', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051006', 'Cayara', '051000', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051007', 'Colca', '051000', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051008', 'Huamanquiquia', '051000', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051009', 'Huancaraylla', '051000', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051010', 'Huaya', '051000', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051011', 'Sarhua', '051000', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051012', 'Vilcanchos', '051000', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051101', 'Vilcas Huaman', '051100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051102', 'Accomarca', '051100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051103', 'Carhuanca', '051100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051104', 'Concepción', '051100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051105', 'Huambalpa', '051100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051106', 'Independencia', '051100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051107', 'Saurama', '051100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('051108', 'Vischongo', '051100', '050000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060101', 'Cajamarca', '060100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060102', 'Asunción', '060100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060103', 'Chetilla', '060100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060104', 'Cospan', '060100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060105', 'Encañada', '060100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060106', 'Jesús', '060100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060107', 'Llacanora', '060100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060108', 'Los Baños del Inca', '060100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060109', 'Magdalena', '060100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060110', 'Matara', '060100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060111', 'Namora', '060100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060112', 'San Juan', '060100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060201', 'Cajabamba', '060200', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060202', 'Cachachi', '060200', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060203', 'Condebamba', '060200', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060204', 'Sitacocha', '060200', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060301', 'Celendín', '060300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060302', 'Chumuch', '060300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060303', 'Cortegana', '060300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060304', 'Huasmin', '060300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060305', 'Jorge Chávez', '060300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060306', 'José Gálvez', '060300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060307', 'Miguel Iglesias', '060300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060308', 'Oxamarca', '060300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060309', 'Sorochuco', '060300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060310', 'Sucre', '060300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060311', 'Utco', '060300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060312', 'La Libertad de Pallan', '060300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060401', 'Chota', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060402', 'Anguia', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060403', 'Chadin', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060404', 'Chiguirip', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060405', 'Chimban', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060406', 'Choropampa', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060407', 'Cochabamba', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060408', 'Conchan', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060409', 'Huambos', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060410', 'Lajas', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060411', 'Llama', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060412', 'Miracosta', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060413', 'Paccha', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060414', 'Pion', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060415', 'Querocoto', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060416', 'San Juan de Licupis', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060417', 'Tacabamba', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060418', 'Tocmoche', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060419', 'Chalamarca', '060400', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060501', 'Contumaza', '060500', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060502', 'Chilete', '060500', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060503', 'Cupisnique', '060500', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060504', 'Guzmango', '060500', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060505', 'San Benito', '060500', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060506', 'Santa Cruz de Toledo', '060500', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060507', 'Tantarica', '060500', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060508', 'Yonan', '060500', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060601', 'Cutervo', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060602', 'Callayuc', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060603', 'Choros', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060604', 'Cujillo', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060605', 'La Ramada', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060606', 'Pimpingos', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060607', 'Querocotillo', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060608', 'San Andrés de Cutervo', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060609', 'San Juan de Cutervo', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060610', 'San Luis de Lucma', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060611', 'Santa Cruz', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060612', 'Santo Domingo de la Capilla', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060613', 'Santo Tomas', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060614', 'Socota', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060615', 'Toribio Casanova', '060600', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060701', 'Bambamarca', '060700', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060702', 'Chugur', '060700', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060703', 'Hualgayoc', '060700', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060801', 'Jaén', '060800', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060802', 'Bellavista', '060800', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060803', 'Chontali', '060800', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060804', 'Colasay', '060800', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060805', 'Huabal', '060800', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060806', 'Las Pirias', '060800', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060807', 'Pomahuaca', '060800', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060808', 'Pucara', '060800', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060809', 'Sallique', '060800', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060810', 'San Felipe', '060800', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060811', 'San José del Alto', '060800', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060812', 'Santa Rosa', '060800', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060901', 'San Ignacio', '060900', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060902', 'Chirinos', '060900', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060903', 'Huarango', '060900', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060904', 'La Coipa', '060900', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060905', 'Namballe', '060900', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060906', 'San José de Lourdes', '060900', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('060907', 'Tabaconas', '060900', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061001', 'Pedro Gálvez', '061000', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061002', 'Chancay', '061000', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061003', 'Eduardo Villanueva', '061000', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061004', 'Gregorio Pita', '061000', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061005', 'Ichocan', '061000', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061006', 'José Manuel Quiroz', '061000', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061007', 'José Sabogal', '061000', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061101', 'San Miguel', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061102', 'Bolívar', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061103', 'Calquis', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061104', 'Catilluc', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061105', 'El Prado', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061106', 'La Florida', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061107', 'Llapa', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061108', 'Nanchoc', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061109', 'Niepos', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061110', 'San Gregorio', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061111', 'San Silvestre de Cochan', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061112', 'Tongod', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061113', 'Unión Agua Blanca', '061100', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061201', 'San Pablo', '061200', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061202', 'San Bernardino', '061200', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061203', 'San Luis', '061200', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061204', 'Tumbaden', '061200', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061301', 'Santa Cruz', '061300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061302', 'Andabamba', '061300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061303', 'Catache', '061300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061304', 'Chancaybaños', '061300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061305', 'La Esperanza', '061300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061306', 'Ninabamba', '061300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061307', 'Pulan', '061300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061308', 'Saucepampa', '061300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061309', 'Sexi', '061300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061310', 'Uticyacu', '061300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('061311', 'Yauyucan', '061300', '060000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('070101', 'Callao', '070100', '070000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('070102', 'Bellavista', '070100', '070000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('070103', 'Carmen de la Legua Reynoso', '070100', '070000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('070104', 'La Perla', '070100', '070000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('070105', 'La Punta', '070100', '070000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('070106', 'Ventanilla', '070100', '070000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('070107', 'Mi Perú', '070100', '070000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080101', 'Cusco', '080100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080102', 'Ccorca', '080100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080103', 'Poroy', '080100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080104', 'San Jerónimo', '080100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080105', 'San Sebastian', '080100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080106', 'Santiago', '080100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080107', 'Saylla', '080100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080108', 'Wanchaq', '080100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080201', 'Acomayo', '080200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080202', 'Acopia', '080200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080203', 'Acos', '080200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080204', 'Mosoc Llacta', '080200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080205', 'Pomacanchi', '080200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080206', 'Rondocan', '080200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080207', 'Sangarara', '080200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080301', 'Anta', '080300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080302', 'Ancahuasi', '080300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080303', 'Cachimayo', '080300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080304', 'Chinchaypujio', '080300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080305', 'Huarocondo', '080300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080306', 'Limatambo', '080300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080307', 'Mollepata', '080300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080308', 'Pucyura', '080300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080309', 'Zurite', '080300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080401', 'Calca', '080400', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080402', 'Coya', '080400', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080403', 'Lamay', '080400', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080404', 'Lares', '080400', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080405', 'Pisac', '080400', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080406', 'San Salvador', '080400', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080407', 'Taray', '080400', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080408', 'Yanatile', '080400', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080501', 'Yanaoca', '080500', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080502', 'Checca', '080500', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080503', 'Kunturkanki', '080500', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080504', 'Langui', '080500', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080505', 'Layo', '080500', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080506', 'Pampamarca', '080500', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080507', 'Quehue', '080500', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080508', 'Tupac Amaru', '080500', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080601', 'Sicuani', '080600', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080602', 'Checacupe', '080600', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080603', 'Combapata', '080600', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080604', 'Marangani', '080600', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080605', 'Pitumarca', '080600', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080606', 'San Pablo', '080600', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080607', 'San Pedro', '080600', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080608', 'Tinta', '080600', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080701', 'Santo Tomas', '080700', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080702', 'Capacmarca', '080700', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080703', 'Chamaca', '080700', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080704', 'Colquemarca', '080700', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080705', 'Livitaca', '080700', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080706', 'Llusco', '080700', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080707', 'Quiñota', '080700', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080708', 'Velille', '080700', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080801', 'Espinar', '080800', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080802', 'Condoroma', '080800', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080803', 'Coporaque', '080800', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080804', 'Ocoruro', '080800', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080805', 'Pallpata', '080800', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080806', 'Pichigua', '080800', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080807', 'Suyckutambo', '080800', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080808', 'Alto Pichigua', '080800', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080901', 'Santa Ana', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080902', 'Echarate', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080903', 'Huayopata', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080904', 'Maranura', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080905', 'Ocobamba', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080906', 'Quellouno', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080907', 'Kimbiri', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080908', 'Santa Teresa', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080909', 'Vilcabamba', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080910', 'Pichari', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080911', 'Inkawasi', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080912', 'Villa Virgen', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080913', 'Villa Kintiarina', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('080914', 'Megantoni', '080900', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081001', 'Paruro', '081000', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081002', 'Accha', '081000', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081003', 'Ccapi', '081000', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081004', 'Colcha', '081000', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081005', 'Huanoquite', '081000', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081006', 'Omacha', '081000', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081007', 'Paccaritambo', '081000', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081008', 'Pillpinto', '081000', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081009', 'Yaurisque', '081000', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081101', 'Paucartambo', '081100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081102', 'Caicay', '081100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081103', 'Challabamba', '081100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081104', 'Colquepata', '081100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081105', 'Huancarani', '081100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081106', 'Kosñipata', '081100', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081201', 'Urcos', '081200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081202', 'Andahuaylillas', '081200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081203', 'Camanti', '081200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081204', 'Ccarhuayo', '081200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081205', 'Ccatca', '081200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081206', 'Cusipata', '081200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081207', 'Huaro', '081200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081208', 'Lucre', '081200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081209', 'Marcapata', '081200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081210', 'Ocongate', '081200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081211', 'Oropesa', '081200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081212', 'Quiquijana', '081200', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081301', 'Urubamba', '081300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081302', 'Chinchero', '081300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081303', 'Huayllabamba', '081300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081304', 'Machupicchu', '081300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081305', 'Maras', '081300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081306', 'Ollantaytambo', '081300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('081307', 'Yucay', '081300', '080000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090101', 'Huancavelica', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090102', 'Acobambilla', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090103', 'Acoria', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090104', 'Conayca', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090105', 'Cuenca', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090106', 'Huachocolpa', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090107', 'Huayllahuara', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090108', 'Izcuchaca', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090109', 'Laria', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090110', 'Manta', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090111', 'Mariscal Cáceres', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090112', 'Moya', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090113', 'Nuevo Occoro', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090114', 'Palca', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090115', 'Pilchaca', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090116', 'Vilca', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090117', 'Yauli', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090118', 'Ascensión', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090119', 'Huando', '090100', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090201', 'Acobamba', '090200', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090202', 'Andabamba', '090200', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090203', 'Anta', '090200', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090204', 'Caja', '090200', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090205', 'Marcas', '090200', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090206', 'Paucara', '090200', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090207', 'Pomacocha', '090200', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090208', 'Rosario', '090200', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090301', 'Lircay', '090300', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090302', 'Anchonga', '090300', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090303', 'Callanmarca', '090300', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090304', 'Ccochaccasa', '090300', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090305', 'Chincho', '090300', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090306', 'Congalla', '090300', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090307', 'Huanca-Huanca', '090300', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090308', 'Huayllay Grande', '090300', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090309', 'Julcamarca', '090300', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090310', 'San Antonio de Antaparco', '090300', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090311', 'Santo Tomas de Pata', '090300', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090312', 'Secclla', '090300', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090401', 'Castrovirreyna', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090402', 'Arma', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090403', 'Aurahua', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090404', 'Capillas', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090405', 'Chupamarca', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090406', 'Cocas', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090407', 'Huachos', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090408', 'Huamatambo', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090409', 'Mollepampa', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090410', 'San Juan', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090411', 'Santa Ana', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090412', 'Tantara', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090413', 'Ticrapo', '090400', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090501', 'Churcampa', '090500', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090502', 'Anco', '090500', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090503', 'Chinchihuasi', '090500', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090504', 'El Carmen', '090500', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090505', 'La Merced', '090500', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090506', 'Locroja', '090500', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090507', 'Paucarbamba', '090500', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090508', 'San Miguel de Mayocc', '090500', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090509', 'San Pedro de Coris', '090500', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090510', 'Pachamarca', '090500', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090511', 'Cosme', '090500', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090601', 'Huaytara', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090602', 'Ayavi', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090603', 'Córdova', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090604', 'Huayacundo Arma', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090605', 'Laramarca', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090606', 'Ocoyo', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090607', 'Pilpichaca', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090608', 'Querco', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090609', 'Quito-Arma', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090610', 'San Antonio de Cusicancha', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090611', 'San Francisco de Sangayaico', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090612', 'San Isidro', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090613', 'Santiago de Chocorvos', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090614', 'Santiago de Quirahuara', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090615', 'Santo Domingo de Capillas', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090616', 'Tambo', '090600', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090701', 'Pampas', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090702', 'Acostambo', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090703', 'Acraquia', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090704', 'Ahuaycha', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090705', 'Colcabamba', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090706', 'Daniel Hernández', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090707', 'Huachocolpa', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090709', 'Huaribamba', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090710', 'Ñahuimpuquio', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090711', 'Pazos', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090713', 'Quishuar', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090714', 'Salcabamba', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090715', 'Salcahuasi', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090716', 'San Marcos de Rocchac', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090717', 'Surcubamba', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090718', 'Tintay Puncu', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090719', 'Quichuas', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090720', 'Andaymarca', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090721', 'Roble', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090722', 'Pichos', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('090723', 'Santiago de Tucuma', '090700', '090000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100101', 'Huanuco', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100102', 'Amarilis', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100103', 'Chinchao', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100104', 'Churubamba', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100105', 'Margos', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100106', 'Quisqui (Kichki)', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100107', 'San Francisco de Cayran', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100108', 'San Pedro de Chaulan', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100109', 'Santa María del Valle', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100110', 'Yarumayo', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100111', 'Pillco Marca', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100112', 'Yacus', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100113', 'San Pablo de Pillao', '100100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100201', 'Ambo', '100200', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100202', 'Cayna', '100200', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100203', 'Colpas', '100200', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100204', 'Conchamarca', '100200', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100205', 'Huacar', '100200', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100206', 'San Francisco', '100200', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100207', 'San Rafael', '100200', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100208', 'Tomay Kichwa', '100200', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100301', 'La Unión', '100300', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100307', 'Chuquis', '100300', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100311', 'Marías', '100300', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100313', 'Pachas', '100300', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100316', 'Quivilla', '100300', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100317', 'Ripan', '100300', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100321', 'Shunqui', '100300', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100322', 'Sillapata', '100300', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100323', 'Yanas', '100300', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100401', 'Huacaybamba', '100400', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100402', 'Canchabamba', '100400', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100403', 'Cochabamba', '100400', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100404', 'Pinra', '100400', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100501', 'Llata', '100500', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100502', 'Arancay', '100500', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100503', 'Chavín de Pariarca', '100500', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100504', 'Jacas Grande', '100500', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100505', 'Jircan', '100500', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100506', 'Miraflores', '100500', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100507', 'Monzón', '100500', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100508', 'Punchao', '100500', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100509', 'Puños', '100500', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100510', 'Singa', '100500', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100511', 'Tantamayo', '100500', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100601', 'Rupa-Rupa', '100600', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100602', 'Daniel Alomía Robles', '100600', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100603', 'Hermílio Valdizan', '100600', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100604', 'José Crespo y Castillo', '100600', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100605', 'Luyando', '100600', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100606', 'Mariano Damaso Beraun', '100600', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100607', 'Pucayacu', '100600', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100608', 'Castillo Grande', '100600', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100609', 'Pueblo Nuevo', '100600', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100610', 'Santo Domingo de Anda', '100600', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100701', 'Huacrachuco', '100700', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100702', 'Cholon', '100700', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100703', 'San Buenaventura', '100700', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100704', 'La Morada', '100700', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100705', 'Santa Rosa de Alto Yanajanca', '100700', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100801', 'Panao', '100800', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100802', 'Chaglla', '100800', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100803', 'Molino', '100800', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100804', 'Umari', '100800', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100901', 'Puerto Inca', '100900', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100902', 'Codo del Pozuzo', '100900', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100903', 'Honoria', '100900', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100904', 'Tournavista', '100900', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('100905', 'Yuyapichis', '100900', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101001', 'Jesús', '101000', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101002', 'Baños', '101000', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101003', 'Jivia', '101000', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101004', 'Queropalca', '101000', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101005', 'Rondos', '101000', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101006', 'San Francisco de Asís', '101000', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101007', 'San Miguel de Cauri', '101000', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101101', 'Chavinillo', '101100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101102', 'Cahuac', '101100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101103', 'Chacabamba', '101100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101104', 'Aparicio Pomares', '101100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101105', 'Jacas Chico', '101100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101106', 'Obas', '101100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101107', 'Pampamarca', '101100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('101108', 'Choras', '101100', '100000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110101', 'Ica', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110102', 'La Tinguiña', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110103', 'Los Aquijes', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110104', 'Ocucaje', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110105', 'Pachacutec', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110106', 'Parcona', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110107', 'Pueblo Nuevo', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110108', 'Salas', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110109', 'San José de Los Molinos', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110110', 'San Juan Bautista', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110111', 'Santiago', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110112', 'Subtanjalla', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110113', 'Tate', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110114', 'Yauca del Rosario', '110100', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110201', 'Chincha Alta', '110200', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110202', 'Alto Laran', '110200', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110203', 'Chavin', '110200', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110204', 'Chincha Baja', '110200', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110205', 'El Carmen', '110200', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110206', 'Grocio Prado', '110200', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110207', 'Pueblo Nuevo', '110200', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110208', 'San Juan de Yanac', '110200', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110209', 'San Pedro de Huacarpana', '110200', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110210', 'Sunampe', '110200', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110211', 'Tambo de Mora', '110200', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110301', 'Nasca', '110300', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110302', 'Changuillo', '110300', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110303', 'El Ingenio', '110300', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110304', 'Marcona', '110300', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110305', 'Vista Alegre', '110300', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110401', 'Palpa', '110400', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110402', 'Llipata', '110400', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110403', 'Río Grande', '110400', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110404', 'Santa Cruz', '110400', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110405', 'Tibillo', '110400', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110501', 'Pisco', '110500', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110502', 'Huancano', '110500', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110503', 'Humay', '110500', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110504', 'Independencia', '110500', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110505', 'Paracas', '110500', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110506', 'San Andrés', '110500', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110507', 'San Clemente', '110500', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('110508', 'Tupac Amaru Inca', '110500', '110000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120101', 'Huancayo', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120104', 'Carhuacallanga', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120105', 'Chacapampa', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120106', 'Chicche', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120107', 'Chilca', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120108', 'Chongos Alto', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120111', 'Chupuro', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120112', 'Colca', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120113', 'Cullhuas', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120114', 'El Tambo', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120116', 'Huacrapuquio', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120117', 'Hualhuas', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120119', 'Huancan', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120120', 'Huasicancha', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120121', 'Huayucachi', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120122', 'Ingenio', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120124', 'Pariahuanca', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120125', 'Pilcomayo', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120126', 'Pucara', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120127', 'Quichuay', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120128', 'Quilcas', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120129', 'San Agustín', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120130', 'San Jerónimo de Tunan', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120132', 'Saño', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120133', 'Sapallanga', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120134', 'Sicaya', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120135', 'Santo Domingo de Acobamba', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120136', 'Viques', '120100', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120201', 'Concepción', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120202', 'Aco', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120203', 'Andamarca', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120204', 'Chambara', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120205', 'Cochas', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120206', 'Comas', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120207', 'Heroínas Toledo', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120208', 'Manzanares', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120209', 'Mariscal Castilla', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120210', 'Matahuasi', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120211', 'Mito', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120212', 'Nueve de Julio', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120213', 'Orcotuna', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120214', 'San José de Quero', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120215', 'Santa Rosa de Ocopa', '120200', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120301', 'Chanchamayo', '120300', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120302', 'Perene', '120300', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120303', 'Pichanaqui', '120300', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120304', 'San Luis de Shuaro', '120300', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120305', 'San Ramón', '120300', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120306', 'Vitoc', '120300', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120401', 'Jauja', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120402', 'Acolla', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120403', 'Apata', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120404', 'Ataura', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120405', 'Canchayllo', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120406', 'Curicaca', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120407', 'El Mantaro', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120408', 'Huamali', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120409', 'Huaripampa', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120410', 'Huertas', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120411', 'Janjaillo', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120412', 'Julcán', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120413', 'Leonor Ordóñez', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120414', 'Llocllapampa', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120415', 'Marco', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120416', 'Masma', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120417', 'Masma Chicche', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120418', 'Molinos', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120419', 'Monobamba', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120420', 'Muqui', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120421', 'Muquiyauyo', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120422', 'Paca', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120423', 'Paccha', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120424', 'Pancan', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120425', 'Parco', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120426', 'Pomacancha', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120427', 'Ricran', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120428', 'San Lorenzo', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120429', 'San Pedro de Chunan', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120430', 'Sausa', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120431', 'Sincos', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120432', 'Tunan Marca', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120433', 'Yauli', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120434', 'Yauyos', '120400', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120501', 'Junin', '120500', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120502', 'Carhuamayo', '120500', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120503', 'Ondores', '120500', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120504', 'Ulcumayo', '120500', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120601', 'Satipo', '120600', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120602', 'Coviriali', '120600', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120603', 'Llaylla', '120600', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120604', 'Mazamari', '120600', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120605', 'Pampa Hermosa', '120600', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120606', 'Pangoa', '120600', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120607', 'Río Negro', '120600', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120608', 'Río Tambo', '120600', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120609', 'Vizcatan del Ene', '120600', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120701', 'Tarma', '120700', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120702', 'Acobamba', '120700', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120703', 'Huaricolca', '120700', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120704', 'Huasahuasi', '120700', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120705', 'La Unión', '120700', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120706', 'Palca', '120700', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120707', 'Palcamayo', '120700', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120708', 'San Pedro de Cajas', '120700', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120709', 'Tapo', '120700', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120801', 'La Oroya', '120800', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120802', 'Chacapalpa', '120800', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120803', 'Huay-Huay', '120800', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120804', 'Marcapomacocha', '120800', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120805', 'Morococha', '120800', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120806', 'Paccha', '120800', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120807', 'Santa Bárbara de Carhuacayan', '120800', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120808', 'Santa Rosa de Sacco', '120800', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120809', 'Suitucancha', '120800', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120810', 'Yauli', '120800', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120901', 'Chupaca', '120900', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120902', 'Ahuac', '120900', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120903', 'Chongos Bajo', '120900', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120904', 'Huachac', '120900', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120905', 'Huamancaca Chico', '120900', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120906', 'San Juan de Iscos', '120900', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120907', 'San Juan de Jarpa', '120900', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120908', 'Tres de Diciembre', '120900', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('120909', 'Yanacancha', '120900', '120000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130101', 'Trujillo', '130100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130102', 'El Porvenir', '130100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130103', 'Florencia de Mora', '130100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130104', 'Huanchaco', '130100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130105', 'La Esperanza', '130100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130106', 'Laredo', '130100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130107', 'Moche', '130100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130108', 'Poroto', '130100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130109', 'Salaverry', '130100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130110', 'Simbal', '130100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130111', 'Victor Larco Herrera', '130100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130201', 'Ascope', '130200', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130202', 'Chicama', '130200', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130203', 'Chocope', '130200', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130204', 'Magdalena de Cao', '130200', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130205', 'Paijan', '130200', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130206', 'Rázuri', '130200', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130207', 'Santiago de Cao', '130200', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130208', 'Casa Grande', '130200', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130301', 'Bolívar', '130300', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130302', 'Bambamarca', '130300', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130303', 'Condormarca', '130300', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130304', 'Longotea', '130300', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130305', 'Uchumarca', '130300', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130306', 'Ucuncha', '130300', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130401', 'Chepen', '130400', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130402', 'Pacanga', '130400', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130403', 'Pueblo Nuevo', '130400', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130501', 'Julcan', '130500', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130502', 'Calamarca', '130500', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130503', 'Carabamba', '130500', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130504', 'Huaso', '130500', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130601', 'Otuzco', '130600', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130602', 'Agallpampa', '130600', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130604', 'Charat', '130600', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130605', 'Huaranchal', '130600', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130606', 'La Cuesta', '130600', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130608', 'Mache', '130600', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130610', 'Paranday', '130600', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130611', 'Salpo', '130600', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130613', 'Sinsicap', '130600', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130614', 'Usquil', '130600', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130701', 'San Pedro de Lloc', '130700', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130702', 'Guadalupe', '130700', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130703', 'Jequetepeque', '130700', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130704', 'Pacasmayo', '130700', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130705', 'San José', '130700', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130801', 'Tayabamba', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130802', 'Buldibuyo', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130803', 'Chillia', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130804', 'Huancaspata', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130805', 'Huaylillas', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130806', 'Huayo', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130807', 'Ongon', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130808', 'Parcoy', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130809', 'Pataz', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130810', 'Pias', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130811', 'Santiago de Challas', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130812', 'Taurija', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130813', 'Urpay', '130800', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130901', 'Huamachuco', '130900', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130902', 'Chugay', '130900', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130903', 'Cochorco', '130900', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130904', 'Curgos', '130900', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130905', 'Marcabal', '130900', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130906', 'Sanagoran', '130900', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130907', 'Sarin', '130900', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('130908', 'Sartimbamba', '130900', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131001', 'Santiago de Chuco', '131000', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131002', 'Angasmarca', '131000', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131003', 'Cachicadan', '131000', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131004', 'Mollebamba', '131000', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131005', 'Mollepata', '131000', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131006', 'Quiruvilca', '131000', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131007', 'Santa Cruz de Chuca', '131000', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131008', 'Sitabamba', '131000', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131101', 'Cascas', '131100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131102', 'Lucma', '131100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131103', 'Marmot', '131100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131104', 'Sayapullo', '131100', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131201', 'Viru', '131200', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131202', 'Chao', '131200', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('131203', 'Guadalupito', '131200', '130000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140101', 'Chiclayo', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140102', 'Chongoyape', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140103', 'Eten', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140104', 'Eten Puerto', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140105', 'José Leonardo Ortiz', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140106', 'La Victoria', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140107', 'Lagunas', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140108', 'Monsefu', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140109', 'Nueva Arica', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140110', 'Oyotun', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140111', 'Picsi', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140112', 'Pimentel', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140113', 'Reque', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140114', 'Santa Rosa', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140115', 'Saña', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140116', 'Cayalti', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140117', 'Patapo', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140118', 'Pomalca', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140119', 'Pucala', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140120', 'Tuman', '140100', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140201', 'Ferreñafe', '140200', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140202', 'Cañaris', '140200', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140203', 'Incahuasi', '140200', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140204', 'Manuel Antonio Mesones Muro', '140200', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140205', 'Pitipo', '140200', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140206', 'Pueblo Nuevo', '140200', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140301', 'Lambayeque', '140300', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140302', 'Chochope', '140300', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140303', 'Illimo', '140300', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140304', 'Jayanca', '140300', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140305', 'Mochumi', '140300', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140306', 'Morrope', '140300', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140307', 'Motupe', '140300', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140308', 'Olmos', '140300', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140309', 'Pacora', '140300', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140310', 'Salas', '140300', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140311', 'San José', '140300', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('140312', 'Tucume', '140300', '140000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150101', 'Lima', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150102', 'Ancón', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150103', 'Ate', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150104', 'Barranco', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150105', 'Breña', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150106', 'Carabayllo', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150107', 'Chaclacayo', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150108', 'Chorrillos', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150109', 'Cieneguilla', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150110', 'Comas', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150111', 'El Agustino', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150112', 'Independencia', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150113', 'Jesús María', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150114', 'La Molina', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150115', 'La Victoria', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150116', 'Lince', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150117', 'Los Olivos', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150118', 'Lurigancho', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150119', 'Lurin', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150120', 'Magdalena del Mar', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150121', 'Pueblo Libre', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150122', 'Miraflores', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150123', 'Pachacamac', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150124', 'Pucusana', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150125', 'Puente Piedra', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150126', 'Punta Hermosa', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150127', 'Punta Negra', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150128', 'Rímac', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150129', 'San Bartolo', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150130', 'San Borja', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150131', 'San Isidro', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150132', 'San Juan de Lurigancho', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150133', 'San Juan de Miraflores', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150134', 'San Luis', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150135', 'San Martín de Porres', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150136', 'San Miguel', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150137', 'Santa Anita', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150138', 'Santa María del Mar', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150139', 'Santa Rosa', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150140', 'Santiago de Surco', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150141', 'Surquillo', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150142', 'Villa El Salvador', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150143', 'Villa María del Triunfo', '150100', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150201', 'Barranca', '150200', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150202', 'Paramonga', '150200', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150203', 'Pativilca', '150200', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150204', 'Supe', '150200', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150205', 'Supe Puerto', '150200', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150301', 'Cajatambo', '150300', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150302', 'Copa', '150300', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150303', 'Gorgor', '150300', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150304', 'Huancapon', '150300', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150305', 'Manas', '150300', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150401', 'Canta', '150400', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150402', 'Arahuay', '150400', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150403', 'Huamantanga', '150400', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150404', 'Huaros', '150400', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150405', 'Lachaqui', '150400', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150406', 'San Buenaventura', '150400', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150407', 'Santa Rosa de Quives', '150400', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150501', 'San Vicente de Cañete', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150502', 'Asia', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150503', 'Calango', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150504', 'Cerro Azul', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150505', 'Chilca', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150506', 'Coayllo', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150507', 'Imperial', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150508', 'Lunahuana', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150509', 'Mala', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150510', 'Nuevo Imperial', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150511', 'Pacaran', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150512', 'Quilmana', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150513', 'San Antonio', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150514', 'San Luis', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150515', 'Santa Cruz de Flores', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150516', 'Zúñiga', '150500', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150601', 'Huaral', '150600', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150602', 'Atavillos Alto', '150600', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150603', 'Atavillos Bajo', '150600', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150604', 'Aucallama', '150600', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150605', 'Chancay', '150600', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150606', 'Ihuari', '150600', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150607', 'Lampian', '150600', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150608', 'Pacaraos', '150600', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150609', 'San Miguel de Acos', '150600', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150610', 'Santa Cruz de Andamarca', '150600', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150611', 'Sumbilca', '150600', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150612', 'Veintisiete de Noviembre', '150600', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150701', 'Matucana', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150702', 'Antioquia', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150703', 'Callahuanca', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150704', 'Carampoma', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150705', 'Chicla', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150706', 'Cuenca', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150707', 'Huachupampa', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150708', 'Huanza', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150709', 'Huarochiri', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150710', 'Lahuaytambo', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150711', 'Langa', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150712', 'Laraos', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150713', 'Mariatana', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150714', 'Ricardo Palma', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150715', 'San Andrés de Tupicocha', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150716', 'San Antonio', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150717', 'San Bartolomé', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150718', 'San Damian', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150719', 'San Juan de Iris', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150720', 'San Juan de Tantaranche', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150721', 'San Lorenzo de Quinti', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150722', 'San Mateo', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150723', 'San Mateo de Otao', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150724', 'San Pedro de Casta', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150725', 'San Pedro de Huancayre', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150726', 'Sangallaya', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150727', 'Santa Cruz de Cocachacra', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150728', 'Santa Eulalia', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150729', 'Santiago de Anchucaya', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150730', 'Santiago de Tuna', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150731', 'Santo Domingo de Los Olleros', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150732', 'Surco', '150700', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150801', 'Huacho', '150800', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150802', 'Ambar', '150800', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150803', 'Caleta de Carquin', '150800', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150804', 'Checras', '150800', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150805', 'Hualmay', '150800', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150806', 'Huaura', '150800', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150807', 'Leoncio Prado', '150800', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150808', 'Paccho', '150800', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150809', 'Santa Leonor', '150800', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150810', 'Santa María', '150800', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150811', 'Sayan', '150800', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150812', 'Vegueta', '150800', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150901', 'Oyon', '150900', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150902', 'Andajes', '150900', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150903', 'Caujul', '150900', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150904', 'Cochamarca', '150900', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150905', 'Navan', '150900', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('150906', 'Pachangara', '150900', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151001', 'Yauyos', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151002', 'Alis', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151003', 'Allauca', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151004', 'Ayaviri', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151005', 'Azángaro', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151006', 'Cacra', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151007', 'Carania', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151008', 'Catahuasi', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151009', 'Chocos', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151010', 'Cochas', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151011', 'Colonia', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151012', 'Hongos', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151013', 'Huampara', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151014', 'Huancaya', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151015', 'Huangascar', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151016', 'Huantan', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151017', 'Huañec', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151018', 'Laraos', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151019', 'Lincha', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151020', 'Madean', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151021', 'Miraflores', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151022', 'Omas', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151023', 'Putinza', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151024', 'Quinches', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151025', 'Quinocay', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151026', 'San Joaquín', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151027', 'San Pedro de Pilas', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151028', 'Tanta', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151029', 'Tauripampa', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151030', 'Tomas', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151031', 'Tupe', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151032', 'Viñac', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('151033', 'Vitis', '151000', '150000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160101', 'Iquitos', '160100', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160102', 'Alto Nanay', '160100', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160103', 'Fernando Lores', '160100', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160104', 'Indiana', '160100', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160105', 'Las Amazonas', '160100', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160106', 'Mazan', '160100', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160107', 'Napo', '160100', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160108', 'Punchana', '160100', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160110', 'Torres Causana', '160100', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160112', 'Belén', '160100', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160113', 'San Juan Bautista', '160100', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160201', 'Yurimaguas', '160200', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160202', 'Balsapuerto', '160200', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160205', 'Jeberos', '160200', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160206', 'Lagunas', '160200', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160210', 'Santa Cruz', '160200', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160211', 'Teniente Cesar López Rojas', '160200', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160301', 'Nauta', '160300', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160302', 'Parinari', '160300', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160303', 'Tigre', '160300', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160304', 'Trompeteros', '160300', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160305', 'Urarinas', '160300', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160401', 'Ramón Castilla', '160400', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160402', 'Pebas', '160400', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160403', 'Yavari', '160400', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160404', 'San Pablo', '160400', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160501', 'Requena', '160500', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160502', 'Alto Tapiche', '160500', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160503', 'Capelo', '160500', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160504', 'Emilio San Martín', '160500', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160505', 'Maquia', '160500', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160506', 'Puinahua', '160500', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160507', 'Saquena', '160500', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160508', 'Soplin', '160500', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160509', 'Tapiche', '160500', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160510', 'Jenaro Herrera', '160500', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160511', 'Yaquerana', '160500', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160601', 'Contamana', '160600', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160602', 'Inahuaya', '160600', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160603', 'Padre Márquez', '160600', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160604', 'Pampa Hermosa', '160600', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160605', 'Sarayacu', '160600', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160606', 'Vargas Guerra', '160600', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160701', 'Barranca', '160700', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160702', 'Cahuapanas', '160700', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160703', 'Manseriche', '160700', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160704', 'Morona', '160700', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160705', 'Pastaza', '160700', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160706', 'Andoas', '160700', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160801', 'Putumayo', '160800', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160802', 'Rosa Panduro', '160800', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160803', 'Teniente Manuel Clavero', '160800', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('160804', 'Yaguas', '160800', '160000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('170101', 'Tambopata', '170100', '170000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('170102', 'Inambari', '170100', '170000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('170103', 'Las Piedras', '170100', '170000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('170104', 'Laberinto', '170100', '170000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('170201', 'Manu', '170200', '170000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('170202', 'Fitzcarrald', '170200', '170000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('170203', 'Madre de Dios', '170200', '170000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('170204', 'Huepetuhe', '170200', '170000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('170301', 'Iñapari', '170300', '170000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('170302', 'Iberia', '170300', '170000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('170303', 'Tahuamanu', '170300', '170000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180101', 'Moquegua', '180100', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180102', 'Carumas', '180100', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180103', 'Cuchumbaya', '180100', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180104', 'Samegua', '180100', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180105', 'San Cristóbal', '180100', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180106', 'Torata', '180100', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180201', 'Omate', '180200', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180202', 'Chojata', '180200', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180203', 'Coalaque', '180200', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180204', 'Ichuña', '180200', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180205', 'La Capilla', '180200', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180206', 'Lloque', '180200', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180207', 'Matalaque', '180200', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180208', 'Puquina', '180200', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180209', 'Quinistaquillas', '180200', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180210', 'Ubinas', '180200', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180211', 'Yunga', '180200', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180301', 'Ilo', '180300', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180302', 'El Algarrobal', '180300', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('180303', 'Pacocha', '180300', '180000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190101', 'Chaupimarca', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190102', 'Huachon', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190103', 'Huariaca', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190104', 'Huayllay', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190105', 'Ninacaca', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190106', 'Pallanchacra', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190107', 'Paucartambo', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190108', 'San Francisco de Asís de Yarusyacan', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190109', 'Simon Bolívar', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190110', 'Ticlacayan', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190111', 'Tinyahuarco', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190112', 'Vicco', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190113', 'Yanacancha', '190100', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190201', 'Yanahuanca', '190200', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190202', 'Chacayan', '190200', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190203', 'Goyllarisquizga', '190200', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190204', 'Paucar', '190200', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190205', 'San Pedro de Pillao', '190200', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190206', 'Santa Ana de Tusi', '190200', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190207', 'Tapuc', '190200', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190208', 'Vilcabamba', '190200', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190301', 'Oxapampa', '190300', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190302', 'Chontabamba', '190300', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190303', 'Huancabamba', '190300', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190304', 'Palcazu', '190300', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190305', 'Pozuzo', '190300', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190306', 'Puerto Bermúdez', '190300', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190307', 'Villa Rica', '190300', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('190308', 'Constitución', '190300', '190000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200101', 'Piura', '200100', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200104', 'Castilla', '200100', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200105', 'Catacaos', '200100', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200107', 'Cura Mori', '200100', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200108', 'El Tallan', '200100', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200109', 'La Arena', '200100', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200110', 'La Unión', '200100', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200111', 'Las Lomas', '200100', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200114', 'Tambo Grande', '200100', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200115', 'Veintiseis de Octubre', '200100', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200201', 'Ayabaca', '200200', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200202', 'Frias', '200200', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200203', 'Jilili', '200200', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200204', 'Lagunas', '200200', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200205', 'Montero', '200200', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200206', 'Pacaipampa', '200200', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200207', 'Paimas', '200200', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200208', 'Sapillica', '200200', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200209', 'Sicchez', '200200', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200210', 'Suyo', '200200', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200301', 'Huancabamba', '200300', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200302', 'Canchaque', '200300', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200303', 'El Carmen de la Frontera', '200300', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200304', 'Huarmaca', '200300', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200305', 'Lalaquiz', '200300', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200306', 'San Miguel de El Faique', '200300', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200307', 'Sondor', '200300', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200308', 'Sondorillo', '200300', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200401', 'Chulucanas', '200400', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200402', 'Buenos Aires', '200400', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200403', 'Chalaco', '200400', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200404', 'La Matanza', '200400', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200405', 'Morropon', '200400', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200406', 'Salitral', '200400', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200407', 'San Juan de Bigote', '200400', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200408', 'Santa Catalina de Mossa', '200400', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200409', 'Santo Domingo', '200400', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200410', 'Yamango', '200400', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200501', 'Paita', '200500', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200502', 'Amotape', '200500', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200503', 'Arenal', '200500', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200504', 'Colan', '200500', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200505', 'La Huaca', '200500', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200506', 'Tamarindo', '200500', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200507', 'Vichayal', '200500', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200601', 'Sullana', '200600', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200602', 'Bellavista', '200600', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200603', 'Ignacio Escudero', '200600', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200604', 'Lancones', '200600', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200605', 'Marcavelica', '200600', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200606', 'Miguel Checa', '200600', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200607', 'Querecotillo', '200600', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200608', 'Salitral', '200600', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200701', 'Pariñas', '200700', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200702', 'El Alto', '200700', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200703', 'La Brea', '200700', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200704', 'Lobitos', '200700', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200705', 'Los Organos', '200700', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200706', 'Mancora', '200700', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200801', 'Sechura', '200800', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200802', 'Bellavista de la Unión', '200800', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200803', 'Bernal', '200800', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200804', 'Cristo Nos Valga', '200800', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200805', 'Vice', '200800', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('200806', 'Rinconada Llicuar', '200800', '200000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210101', 'Puno', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210102', 'Acora', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210103', 'Amantani', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210104', 'Atuncolla', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210105', 'Capachica', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210106', 'Chucuito', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210107', 'Coata', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210108', 'Huata', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210109', 'Mañazo', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210110', 'Paucarcolla', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210111', 'Pichacani', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210112', 'Plateria', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210113', 'San Antonio', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210114', 'Tiquillaca', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210115', 'Vilque', '210100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210201', 'Azángaro', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210202', 'Achaya', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210203', 'Arapa', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210204', 'Asillo', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210205', 'Caminaca', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210206', 'Chupa', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210207', 'José Domingo Choquehuanca', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210208', 'Muñani', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210209', 'Potoni', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210210', 'Saman', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210211', 'San Anton', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210212', 'San José', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210213', 'San Juan de Salinas', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210214', 'Santiago de Pupuja', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210215', 'Tirapata', '210200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210301', 'Macusani', '210300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210302', 'Ajoyani', '210300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210303', 'Ayapata', '210300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210304', 'Coasa', '210300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210305', 'Corani', '210300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210306', 'Crucero', '210300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210307', 'Ituata', '210300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210308', 'Ollachea', '210300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210309', 'San Gaban', '210300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210310', 'Usicayos', '210300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210401', 'Juli', '210400', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210402', 'Desaguadero', '210400', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210403', 'Huacullani', '210400', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210404', 'Kelluyo', '210400', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210405', 'Pisacoma', '210400', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210406', 'Pomata', '210400', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210407', 'Zepita', '210400', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210501', 'Ilave', '210500', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210502', 'Capazo', '210500', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210503', 'Pilcuyo', '210500', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210504', 'Santa Rosa', '210500', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210505', 'Conduriri', '210500', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210601', 'Huancane', '210600', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210602', 'Cojata', '210600', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210603', 'Huatasani', '210600', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210604', 'Inchupalla', '210600', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210605', 'Pusi', '210600', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210606', 'Rosaspata', '210600', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210607', 'Taraco', '210600', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210608', 'Vilque Chico', '210600', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210701', 'Lampa', '210700', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210702', 'Cabanilla', '210700', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210703', 'Calapuja', '210700', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210704', 'Nicasio', '210700', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210705', 'Ocuviri', '210700', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210706', 'Palca', '210700', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210707', 'Paratia', '210700', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210708', 'Pucara', '210700', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210709', 'Santa Lucia', '210700', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210710', 'Vilavila', '210700', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210801', 'Ayaviri', '210800', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210802', 'Antauta', '210800', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210803', 'Cupi', '210800', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210804', 'Llalli', '210800', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210805', 'Macari', '210800', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210806', 'Nuñoa', '210800', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210807', 'Orurillo', '210800', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210808', 'Santa Rosa', '210800', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210809', 'Umachiri', '210800', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210901', 'Moho', '210900', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210902', 'Conima', '210900', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210903', 'Huayrapata', '210900', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('210904', 'Tilali', '210900', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211001', 'Putina', '211000', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211002', 'Ananea', '211000', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211003', 'Pedro Vilca Apaza', '211000', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211004', 'Quilcapuncu', '211000', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211005', 'Sina', '211000', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211101', 'Juliaca', '211100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211102', 'Cabana', '211100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211103', 'Cabanillas', '211100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211104', 'Caracoto', '211100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211105', 'San Miguel', '211100', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211201', 'Sandia', '211200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211202', 'Cuyocuyo', '211200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211203', 'Limbani', '211200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211204', 'Patambuco', '211200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211205', 'Phara', '211200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211206', 'Quiaca', '211200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211207', 'San Juan del Oro', '211200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211208', 'Yanahuaya', '211200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211209', 'Alto Inambari', '211200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211210', 'San Pedro de Putina Punco', '211200', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211301', 'Yunguyo', '211300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211302', 'Anapia', '211300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211303', 'Copani', '211300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211304', 'Cuturapi', '211300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211305', 'Ollaraya', '211300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211306', 'Tinicachi', '211300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('211307', 'Unicachi', '211300', '210000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220101', 'Moyobamba', '220100', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220102', 'Calzada', '220100', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220103', 'Habana', '220100', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220104', 'Jepelacio', '220100', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220105', 'Soritor', '220100', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220106', 'Yantalo', '220100', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220201', 'Bellavista', '220200', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220202', 'Alto Biavo', '220200', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220203', 'Bajo Biavo', '220200', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220204', 'Huallaga', '220200', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220205', 'San Pablo', '220200', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220206', 'San Rafael', '220200', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220301', 'San José de Sisa', '220300', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220302', 'Agua Blanca', '220300', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220303', 'San Martín', '220300', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220304', 'Santa Rosa', '220300', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220305', 'Shatoja', '220300', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220401', 'Saposoa', '220400', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220402', 'Alto Saposoa', '220400', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220403', 'El Eslabón', '220400', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220404', 'Piscoyacu', '220400', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220405', 'Sacanche', '220400', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220406', 'Tingo de Saposoa', '220400', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220501', 'Lamas', '220500', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220502', 'Alonso de Alvarado', '220500', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220503', 'Barranquita', '220500', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220504', 'Caynarachi', '220500', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220505', 'Cuñumbuqui', '220500', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220506', 'Pinto Recodo', '220500', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220507', 'Rumisapa', '220500', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220508', 'San Roque de Cumbaza', '220500', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220509', 'Shanao', '220500', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220510', 'Tabalosos', '220500', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220511', 'Zapatero', '220500', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220601', 'Juanjuí', '220600', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220602', 'Campanilla', '220600', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220603', 'Huicungo', '220600', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220604', 'Pachiza', '220600', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220605', 'Pajarillo', '220600', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220701', 'Picota', '220700', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220702', 'Buenos Aires', '220700', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220703', 'Caspisapa', '220700', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220704', 'Pilluana', '220700', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220705', 'Pucacaca', '220700', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220706', 'San Cristóbal', '220700', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220707', 'San Hilarión', '220700', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220708', 'Shamboyacu', '220700', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220709', 'Tingo de Ponasa', '220700', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220710', 'Tres Unidos', '220700', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220801', 'Rioja', '220800', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220802', 'Awajun', '220800', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220803', 'Elías Soplin Vargas', '220800', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220804', 'Nueva Cajamarca', '220800', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220805', 'Pardo Miguel', '220800', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220806', 'Posic', '220800', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220807', 'San Fernando', '220800', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220808', 'Yorongos', '220800', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220809', 'Yuracyacu', '220800', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220901', 'Tarapoto', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220902', 'Alberto Leveau', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220903', 'Cacatachi', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220904', 'Chazuta', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220905', 'Chipurana', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220906', 'El Porvenir', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220907', 'Huimbayoc', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220908', 'Juan Guerra', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220909', 'La Banda de Shilcayo', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220910', 'Morales', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220911', 'Papaplaya', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220912', 'San Antonio', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220913', 'Sauce', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('220914', 'Shapaja', '220900', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('221001', 'Tocache', '221000', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('221002', 'Nuevo Progreso', '221000', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('221003', 'Polvora', '221000', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('221004', 'Shunte', '221000', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('221005', 'Uchiza', '221000', '220000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230101', 'Tacna', '230100', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230102', 'Alto de la Alianza', '230100', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230103', 'Calana', '230100', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230104', 'Ciudad Nueva', '230100', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230105', 'Inclan', '230100', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230106', 'Pachia', '230100', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230107', 'Palca', '230100', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230108', 'Pocollay', '230100', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230109', 'Sama', '230100', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230110', 'Coronel Gregorio Albarracín Lanchipa', '230100', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230111', 'La Yarada los Palos', '230100', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230201', 'Candarave', '230200', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230202', 'Cairani', '230200', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230203', 'Camilaca', '230200', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230204', 'Curibaya', '230200', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230205', 'Huanuara', '230200', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230206', 'Quilahuani', '230200', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230301', 'Locumba', '230300', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230302', 'Ilabaya', '230300', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230303', 'Ite', '230300', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230401', 'Tarata', '230400', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230402', 'Héroes Albarracín', '230400', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230403', 'Estique', '230400', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230404', 'Estique-Pampa', '230400', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230405', 'Sitajara', '230400', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230406', 'Susapaya', '230400', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230407', 'Tarucachi', '230400', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('230408', 'Ticaco', '230400', '230000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240101', 'Tumbes', '240100', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240102', 'Corrales', '240100', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240103', 'La Cruz', '240100', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240104', 'Pampas de Hospital', '240100', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240105', 'San Jacinto', '240100', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240106', 'San Juan de la Virgen', '240100', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240201', 'Zorritos', '240200', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240202', 'Casitas', '240200', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240203', 'Canoas de Punta Sal', '240200', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240301', 'Zarumilla', '240300', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240302', 'Aguas Verdes', '240300', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240303', 'Matapalo', '240300', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('240304', 'Papayal', '240300', '240000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250101', 'Calleria', '250100', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250102', 'Campoverde', '250100', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250103', 'Iparia', '250100', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250104', 'Masisea', '250100', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250105', 'Yarinacocha', '250100', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250106', 'Nueva Requena', '250100', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250107', 'Manantay', '250100', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250201', 'Raymondi', '250200', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250202', 'Sepahua', '250200', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250203', 'Tahuania', '250200', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250204', 'Yurua', '250200', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250301', 'Padre Abad', '250300', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250302', 'Irazola', '250300', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250303', 'Curimana', '250300', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250304', 'Neshuya', '250300', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250305', 'Alexander Von Humboldt', '250300', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');
INSERT INTO `districts` (`idDist`, `name`, `region_id`, `province_id`, `created_at`, `updated_at`) VALUES ('250401', 'Purús', '250400', '250000', '2023-10-18 01:08:35', '2023-10-18 01:08:35');

DROP TABLE IF EXISTS `eventlog`;
CREATE TABLE `eventlog` (
  `id` bigint(20) unsigned NOT NULL,
  `event` int(11) NOT NULL,
  `eventRowIdOrRef` varchar(20) DEFAULT NULL,
  `eventDesc` text DEFAULT NULL,
  `eventTable` varchar(20) DEFAULT NULL,
  `staffInCharge` bigint(20) unsigned NOT NULL,
  `eventTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `eventId` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` char(200) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`eventId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `follow`;
CREATE TABLE `follow` (
  `follow_id` int(11) NOT NULL,
  `sender` char(128) NOT NULL DEFAULT '',
  `receiver` char(128) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`follow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `forgot_pass`;
CREATE TABLE `forgot_pass` (
  `idFpa` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `password_key` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `expired_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idFpa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `forgot_pin`;
CREATE TABLE `forgot_pin` (
  `idFpi` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `pin_key` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `expired_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idFpi`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `help`;
CREATE TABLE `help` (
  `helpId` int(11) NOT NULL AUTO_INCREMENT,
  `language` char(2) NOT NULL,
  `topic` int(11) NOT NULL,
  `description` text NOT NULL,
  `category` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `display_in_page` varchar(100) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`helpId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `help_categories`;
CREATE TABLE `help_categories` (
  `category_id` int(11) NOT NULL,
  `language` char(2) NOT NULL,
  `category_description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `history`;
CREATE TABLE `history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usercode` char(128) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description_text` mediumtext NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `uploaded_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `ip`;
CREATE TABLE `ip` (
  `id_session` char(128) NOT NULL,
  `user_data` char(128) NOT NULL,
  `address` char(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `ip` (`id_session`, `user_data`, `address`, `timestamp`) VALUES ('5753683774646c68706436666d736d75364f733246576c556a63503331386566', 'contact@labemotion.net', '127.0.0.1', '2026-01-02 11:25:14');
INSERT INTO `ip` (`id_session`, `user_data`, `address`, `timestamp`) VALUES ('78f79851d007c29ec23e12392857a6f7d069cc21e2a5d07dcb76bc35a214dffd', 'contact@pepiuox.net', '::1', '2026-10-17 12:20:26');
INSERT INTO `ip` (`id_session`, `user_data`, `address`, `timestamp`) VALUES ('ef37d5c2b66c645b408d5690083fa69908f170d8b568e2f6483cce1ecdea6be9', 'contact@pepiuox.net', '::1', '2026-10-17 12:21:09');
INSERT INTO `ip` (`id_session`, `user_data`, `address`, `timestamp`) VALUES ('4c54295a214e9bcd7395b6ea82ea2b37b43fcc2a4a8a0ae79da69767794e2b55', 'contact@pepiuox.net', '::1', '2026-10-17 12:21:22');
INSERT INTO `ip` (`id_session`, `user_data`, `address`, `timestamp`) VALUES ('e74aea0b2b3ea869e05f3db45a7c06eb3650d03aad0bcb59b5bcbccd7b1404fd', 'contact@pepiuox.net', '127.0.0.1', '2026-10-17 12:21:48');

DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id_session` varchar(128) NOT NULL,
  `user_data` varchar(100) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `attempts` int(11) NOT NULL,
  `lastlogin` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `member_info`;
CREATE TABLE `member_info` (
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
  CONSTRAINT `fk_userid` FOREIGN KEY (`userid`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `member_jail`;
CREATE TABLE `member_jail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` char(128) NOT NULL,
  `banned_hours` float NOT NULL DEFAULT 24,
  `reason` varchar(2000) DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `user_id_UNIQUE` (`user_id`) USING BTREE,
  KEY `fk_userid_idx` (`user_id`) USING BTREE,
  CONSTRAINT `fk_userid_jail` FOREIGN KEY (`user_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `member_roles`;
CREATE TABLE `member_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` char(128) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_unique_idx` (`member_id`,`role_id`),
  KEY `member_id_idx` (`member_id`),
  KEY `fk_role_id_idx` (`role_id`),
  CONSTRAINT `FK_member_roles_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `idMenu` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `title_page` varchar(100) DEFAULT NULL,
  `link_page` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT 0,
  PRIMARY KEY (`idMenu`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `menu` (`idMenu`, `sort`, `page_id`, `title_page`, `link_page`, `parent_id`) VALUES ('1', NULL, '1', 'Inicio', 'inicio', '0');
INSERT INTO `menu` (`idMenu`, `sort`, `page_id`, `title_page`, `link_page`, `parent_id`) VALUES ('2', NULL, '2', 'Servicios', 'servicios', '0');
INSERT INTO `menu` (`idMenu`, `sort`, `page_id`, `title_page`, `link_page`, `parent_id`) VALUES ('3', NULL, '3', 'Promociones', 'promociones', '2');
INSERT INTO `menu` (`idMenu`, `sort`, `page_id`, `title_page`, `link_page`, `parent_id`) VALUES ('4', NULL, '4', 'Proveedores', 'proveedores', '0');
INSERT INTO `menu` (`idMenu`, `sort`, `page_id`, `title_page`, `link_page`, `parent_id`) VALUES ('5', NULL, '5', 'Ofertas', 'ofertas', '4');
INSERT INTO `menu` (`idMenu`, `sort`, `page_id`, `title_page`, `link_page`, `parent_id`) VALUES ('6', NULL, '6', 'Contacto', 'contacto', '0');

DROP TABLE IF EXISTS `menu_options`;
CREATE TABLE `menu_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` char(50) NOT NULL,
  `fluid` enum('Yes','No') DEFAULT NULL,
  `placement` enum('top','bottom','sticky-top') DEFAULT NULL,
  `aligment` enum('start','center','end') DEFAULT NULL,
  `background` enum('primary','secondary','light','dark','info','success','warning','danger') DEFAULT NULL,
  `color` enum('light','dark') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_menu` (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `menu_options` (`id`, `id_menu`, `fluid`, `placement`, `aligment`, `background`, `color`) VALUES ('1', 'main_navbar', 'Yes', 'top', 'start', 'primary', 'dark');
INSERT INTO `menu_options` (`id`, `id_menu`, `fluid`, `placement`, `aligment`, `background`, `color`) VALUES ('2', 'main_menu', 'Yes', 'top', 'end', 'dark', 'light');

DROP TABLE IF EXISTS `page_cache`;
CREATE TABLE `page_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `cached_content` longtext DEFAULT NULL,
  `hash` varchar(64) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  KEY `page_id` (`page_id`) USING BTREE,
  CONSTRAINT `page_cache_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;


DROP TABLE IF EXISTS `page_menu`;
CREATE TABLE `page_menu` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `page_statistics`;
CREATE TABLE `page_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `visits` int(11) DEFAULT 0,
  `version` varchar(50) DEFAULT '1.0',
  `last_visit` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `page_id` (`page_id`) USING BTREE,
  CONSTRAINT `page_statistics_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;


DROP TABLE IF EXISTS `page_visits`;
CREATE TABLE `page_visits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(10) unsigned NOT NULL,
  `page_title` varchar(200) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `referer` varchar(500) DEFAULT NULL,
  `path` varchar(500) DEFAULT NULL,
  `visited_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_page` (`page_id`),
  KEY `idx_visited` (`visited_at`),
  KEY `idx_ip` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` char(128) NOT NULL,
  `language` int(11) NOT NULL DEFAULT 1,
  `position` int(11) NOT NULL DEFAULT 0,
  `system_path` varchar(150) DEFAULT NULL,
  `view_page` enum('public','system','private') DEFAULT NULL,
  `title` varchar(50) DEFAULT 'Title',
  `slug` varchar(50) DEFAULT 'Title',
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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `pages` (`id`, `user_id`, `language`, `position`, `system_path`, `view_page`, `title`, `slug`, `link`, `url`, `keyword`, `classification`, `description`, `image`, `type`, `menu`, `hidden_page`, `path_file`, `script_name`, `template`, `base_template`, `content`, `style`, `startpage`, `level`, `parent`, `sort`, `active`, `created_at`, `updated_at`) VALUES ('1', '', '1', '0', '', 'public', 'Inicio', 'Title', 'inicio', NULL, 'Inicio', 'Inicio', 'Inicio', '29853.jpg', 'Design', '1', '0', NULL, NULL, NULL, NULL, '&amp;lt;body&amp;gt;&amp;lt;div id=&amp;quot;i8u5&amp;quot; class=&amp;quot;py-5 text-center text-primary h-100 align-items-center d-flex&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;iq5y&amp;quot; class=&amp;quot;container py-5&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;i73b&amp;quot; class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;mx-auto col-lg-8 col-md-10&amp;quot;&amp;gt;&amp;lt;h1 class=&amp;quot;display-3 mb-4&amp;quot;&amp;gt;A wonderful serenity&amp;lt;/h1&amp;gt;&amp;lt;p class=&amp;quot;lead mb-5&amp;quot;&amp;gt;Has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence.&amp;lt;/p&amp;gt;&amp;lt;a href=&amp;quot;contacto&amp;quot; id=&amp;quot;i0mwi&amp;quot; title=&amp;quot;Contact&amp;quot; class=&amp;quot;btn btn-lg btn-primary mx-1&amp;quot;&amp;gt;Take me there&amp;lt;/a&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; id=&amp;quot;i18vv&amp;quot; class=&amp;quot;btn btn-lg mx-1 btn-outline-primary&amp;quot;&amp;gt;Let&amp;#039;s Go&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;section class=&amp;quot;py-4 py-md-5&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-12&amp;quot;&amp;gt;&amp;lt;div id=&amp;quot;iyamm&amp;quot; class=&amp;quot;container-fluid bsb-service-8 bsb-overlay&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row justify-content-lg-around align-items-md-center&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-12 col-lg-4&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;card border-0 bg-transparent text-center&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;card-body text-center p-4 p-xxl-5&amp;quot;&amp;gt;&amp;lt;svg xmlns=&amp;quot;http://www.w3.org/2000/svg&amp;quot; width=&amp;quot;56&amp;quot; height=&amp;quot;56&amp;quot; fill=&amp;quot;currentColor&amp;quot; viewBox=&amp;quot;0 0 16 16&amp;quot; class=&amp;quot;bi bi-gear-fill text-primary mb-4&amp;quot;&amp;gt;&amp;lt;path d=&amp;quot;M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z&amp;quot;&amp;gt;&amp;lt;/path&amp;gt;&amp;lt;/svg&amp;gt;&amp;lt;h4 class=&amp;quot;text-shadow-head mb-4&amp;quot;&amp;gt;Tune Packages&amp;lt;/h4&amp;gt;&amp;lt;p class=&amp;quot;text-shadow-body mb-4&amp;quot;&amp;gt;The Tune Packages offered by our bike repair shop provide maintenance for cyclists of all levels.&amp;lt;/p&amp;gt;&amp;lt;a id=&amp;quot;iml1f&amp;quot; draggable=&amp;quot;true&amp;quot; href=&amp;quot;http://localhost:130/&amp;quot; class=&amp;quot;fw-bold text-decoration-none link-primary&amp;quot;&amp;gt;                    Learn More                   &amp;lt;svg id=&amp;quot;i838f&amp;quot; draggable=&amp;quot;true&amp;quot; xmlns=&amp;quot;http://www.w3.org/2000/svg&amp;quot; width=&amp;quot;16&amp;quot; height=&amp;quot;16&amp;quot; fill=&amp;quot;currentColor&amp;quot; viewBox=&amp;quot;0 0 16 16&amp;quot; class=&amp;quot;bi bi-arrow-right-short&amp;quot;&amp;gt;&amp;lt;path id=&amp;quot;ilegg&amp;quot; draggable=&amp;quot;true&amp;quot; fill-rule=&amp;quot;evenodd&amp;quot; d=&amp;quot;M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z&amp;quot;&amp;gt;\\n  &amp;lt;/path&amp;gt;&amp;lt;/svg&amp;gt;&amp;lt;/a&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-12 col-lg-4&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;card border-0 bg-transparent text-center&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;card-body text-center p-4 p-xxl-5&amp;quot;&amp;gt;&amp;lt;svg xmlns=&amp;quot;http://www.w3.org/2000/svg&amp;quot; width=&amp;quot;56&amp;quot; height=&amp;quot;56&amp;quot; fill=&amp;quot;currentColor&amp;quot; viewBox=&amp;quot;0 0 16 16&amp;quot; class=&amp;quot;bi bi-box-fill text-primary mb-4&amp;quot;&amp;gt;&amp;lt;path fill-rule=&amp;quot;evenodd&amp;quot; d=&amp;quot;M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z&amp;quot;&amp;gt;&amp;lt;/path&amp;gt;&amp;lt;/svg&amp;gt;&amp;lt;h4 class=&amp;quot;text-shadow-head mb-4&amp;quot;&amp;gt;Bike Boxing&amp;lt;/h4&amp;gt;&amp;lt;p class=&amp;quot;text-shadow-body mb-4&amp;quot;&amp;gt;The Bike Boxing service offered by our bike repair shop is a reliable solution for cyclists needing to safely.&amp;lt;/p&amp;gt;&amp;lt;a id=&amp;quot;i8g1k&amp;quot; draggable=&amp;quot;true&amp;quot; href=&amp;quot;http://localhost:130/&amp;quot; class=&amp;quot;fw-bold text-decoration-none link-primary&amp;quot;&amp;gt;                    Learn More                    &amp;lt;svg id=&amp;quot;iufs4&amp;quot; draggable=&amp;quot;true&amp;quot; xmlns=&amp;quot;http://www.w3.org/2000/svg&amp;quot; width=&amp;quot;16&amp;quot; height=&amp;quot;16&amp;quot; fill=&amp;quot;currentColor&amp;quot; viewBox=&amp;quot;0 0 16 16&amp;quot; class=&amp;quot;bi bi-arrow-right-short&amp;quot;&amp;gt;&amp;lt;path id=&amp;quot;izncv&amp;quot; draggable=&amp;quot;true&amp;quot; fill-rule=&amp;quot;evenodd&amp;quot; d=&amp;quot;M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z&amp;quot;&amp;gt;\\n  &amp;lt;/path&amp;gt;&amp;lt;/svg&amp;gt;&amp;lt;/a&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-12 col-lg-4&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;card border-0 bg-transparent text-center&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;card-body text-center p-4 p-xxl-5&amp;quot;&amp;gt;&amp;lt;svg xmlns=&amp;quot;http://www.w3.org/2000/svg&amp;quot; width=&amp;quot;56&amp;quot; height=&amp;quot;56&amp;quot; fill=&amp;quot;currentColor&amp;quot; viewBox=&amp;quot;0 0 16 16&amp;quot; class=&amp;quot;bi bi-truck-front-fill text-primary mb-4&amp;quot;&amp;gt;&amp;lt;path d=&amp;quot;M3.5 0A2.5 2.5 0 0 0 1 2.5v9c0 .818.393 1.544 1 2v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5V14h6v1.5a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2c.607-.456 1-1.182 1-2v-9A2.5 2.5 0 0 0 12.5 0zM3 3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3.9c0 .625-.562 1.092-1.17.994C10.925 7.747 9.208 7.5 8 7.5s-2.925.247-3.83.394A1.008 1.008 0 0 1 3 6.9zm1 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2m8 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2m-5-2h2a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2&amp;quot;&amp;gt;&amp;lt;/path&amp;gt;&amp;lt;/svg&amp;gt;&amp;lt;h4 class=&amp;quot;text-shadow-head mb-4&amp;quot;&amp;gt;Corporate Services&amp;lt;/h4&amp;gt;&amp;lt;p class=&amp;quot;text-shadow-body mb-4&amp;quot;&amp;gt;Our Corporate Services encompass a range of offerings designed to support cycling among employees.&amp;lt;/p&amp;gt;&amp;lt;a id=&amp;quot;ikxad&amp;quot; draggable=&amp;quot;true&amp;quot; href=&amp;quot;http://localhost:130/&amp;quot; class=&amp;quot;fw-bold text-decoration-none link-primary&amp;quot;&amp;gt;                    Learn More                    &amp;lt;svg id=&amp;quot;i3k6g&amp;quot; draggable=&amp;quot;true&amp;quot; xmlns=&amp;quot;http://www.w3.org/2000/svg&amp;quot; width=&amp;quot;16&amp;quot; height=&amp;quot;16&amp;quot; fill=&amp;quot;currentColor&amp;quot; viewBox=&amp;quot;0 0 16 16&amp;quot; class=&amp;quot;bi bi-arrow-right-short&amp;quot;&amp;gt;&amp;lt;path id=&amp;quot;igo68&amp;quot; draggable=&amp;quot;true&amp;quot; fill-rule=&amp;quot;evenodd&amp;quot; d=&amp;quot;M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z&amp;quot;&amp;gt;\\n  &amp;lt;/path&amp;gt;&amp;lt;/svg&amp;gt;&amp;lt;/a&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/section&amp;gt;&amp;lt;div class=&amp;quot;py-3&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-md-6 p-4&amp;quot;&amp;gt;&amp;lt;h4 id=&amp;quot;ixrhp&amp;quot;&amp;gt;&amp;lt;b id=&amp;quot;i65th&amp;quot;&amp;gt;#1 Hello boys&amp;lt;/b&amp;gt;&amp;lt;/h4&amp;gt;&amp;lt;p id=&amp;quot;iov2y&amp;quot;&amp;gt;A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot.&amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-md-6 p-4&amp;quot;&amp;gt;&amp;lt;h4&amp;gt; &amp;lt;b&amp;gt;#2&amp;lt;/b&amp;gt; &amp;lt;/h4&amp;gt;&amp;lt;p&amp;gt;Which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke.&amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-md-6 p-4&amp;quot;&amp;gt;&amp;lt;h4&amp;gt; &amp;lt;b&amp;gt;#3&amp;lt;/b&amp;gt; &amp;lt;/h4&amp;gt;&amp;lt;p&amp;gt;At the present moment; and yet I feel that I never was a greater artist than now. When, while the lovely valley teems with vapour around me, and the meridian sun strikes the upper surface of the impenetrable foliage.&amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;col-md-6 p-4&amp;quot;&amp;gt;&amp;lt;h4&amp;gt; &amp;lt;b&amp;gt;#4&amp;lt;/b&amp;gt; &amp;lt;/h4&amp;gt;&amp;lt;p&amp;gt;My trees, and but a few stray gleams steal into the inner sanctuary, I throw myself down among the tall grass by the trickling stream; and, as I lie close to the earth, a thousand unknown plants are noticed by me. &amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div class=&amp;quot;py-5 text-center&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-md-8 mx-auto&amp;quot;&amp;gt;&amp;lt;p class=&amp;quot;mb-3&amp;quot;&amp;gt;&amp;quot;A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.&amp;quot;&amp;lt;/p&amp;gt;&amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-primary&amp;quot;&amp;gt;Act now!&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}#i8u5{background-image:linear-gradient(to bottom, rgba(0, 0, 0, .75), rgba(0, 0, 0, .75)), url(http://localhost:130/assets/images/cover-bubble-dark.svg);background-position:center center, center center;background-size:cover, cover;background-repeat:repeat, repeat;}#iyamm{--bsb-overlay-opacity:0.8;--bsb-overlay-bg-color:var(--bs-light-rgb);background-image:url(&amp;#039;./assets/img/service-bg-1.webp&amp;#039;);}*{box-sizing:border-box;}body{margin:0;}', '1', '1', '0', '0', '1', '2023-09-30 18:39:21', '2025-08-15 21:42:28');
INSERT INTO `pages` (`id`, `user_id`, `language`, `position`, `system_path`, `view_page`, `title`, `slug`, `link`, `url`, `keyword`, `classification`, `description`, `image`, `type`, `menu`, `hidden_page`, `path_file`, `script_name`, `template`, `base_template`, `content`, `style`, `startpage`, `level`, `parent`, `sort`, `active`, `created_at`, `updated_at`) VALUES ('2', '', '1', '1', '', 'public', 'Servicios', 'Title', 'servicios', NULL, 'Servicios', 'Servicios', 'Servicios', NULL, 'Design', '2', '0', NULL, NULL, NULL, NULL, '&amp;lt;body&amp;gt;&amp;lt;div class=&amp;quot;py-5 text-center text-primary h-100 align-items-center d-flex&amp;quot; id=&amp;quot;ia9j&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container py-5&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;mx-auto col-lg-8 col-md-10&amp;quot;&amp;gt;&amp;lt;h1 class=&amp;quot;display-3 mb-4&amp;quot;&amp;gt;A wonderful serenity&amp;lt;/h1&amp;gt;&amp;lt;p class=&amp;quot;lead mb-5&amp;quot;&amp;gt;Has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence.&amp;lt;/p&amp;gt;&amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg btn-primary mx-1&amp;quot;&amp;gt;Take me there&amp;lt;/a&amp;gt; &amp;lt;a href=&amp;quot;#&amp;quot; class=&amp;quot;btn btn-lg mx-1 btn-outline-primary&amp;quot;&amp;gt;Let&amp;#039;s Go&amp;lt;/a&amp;gt; &amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}#ia9j{background-image:linear-gradient(to bottom, rgba(0, 0, 0, .75), rgba(0, 0, 0, .75)), url(http://localhost:130/assets/images/cover-bubble-dark.svg);background-position:center center, center center;background-size:cover, cover;background-repeat:repeat, repeat;}', '0', '1', '0', '1', '1', '2023-09-30 18:39:21', '2025-09-17 22:20:45');
INSERT INTO `pages` (`id`, `user_id`, `language`, `position`, `system_path`, `view_page`, `title`, `slug`, `link`, `url`, `keyword`, `classification`, `description`, `image`, `type`, `menu`, `hidden_page`, `path_file`, `script_name`, `template`, `base_template`, `content`, `style`, `startpage`, `level`, `parent`, `sort`, `active`, `created_at`, `updated_at`) VALUES ('3', '', '1', '2', '', 'public', 'Promociones', 'Title', 'promociones', NULL, 'Promociones', 'Promociones', 'Promociones', NULL, 'Design', '1', '0', NULL, NULL, NULL, NULL, '&amp;lt;body&amp;gt;&amp;lt;section class=&amp;quot;py-5&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;container&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;row justify-content-center&amp;quot;&amp;gt;&amp;lt;div class=&amp;quot;col-12 col-md-8 text-center&amp;quot;&amp;gt;&amp;lt;p class=&amp;quot;lead&amp;quot; id=&amp;quot;i621&amp;quot;&amp;gt;&amp;quot;Separated they live in Bookmarksgrove right at the coast of the Semantics, far far away, hellooooooood&amp;lt;br/&amp;gt;the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast.&amp;quot;\\n            &amp;lt;/p&amp;gt;&amp;lt;p class=&amp;quot;mt-5 mt-sm-4&amp;quot;&amp;gt;&amp;lt;a href=&amp;quot;https://pepiuox.net&amp;quot; class=&amp;quot;btn btn-primary&amp;quot;&amp;gt;Download&amp;lt;/a&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/section&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin:0;}', '0', '1', '2', '0', '1', '2023-09-30 18:39:21', '2025-08-25 18:07:25');
INSERT INTO `pages` (`id`, `user_id`, `language`, `position`, `system_path`, `view_page`, `title`, `slug`, `link`, `url`, `keyword`, `classification`, `description`, `image`, `type`, `menu`, `hidden_page`, `path_file`, `script_name`, `template`, `base_template`, `content`, `style`, `startpage`, `level`, `parent`, `sort`, `active`, `created_at`, `updated_at`) VALUES ('4', '', '1', '4', '', 'public', 'Proveedores', 'Title', 'proveedores', NULL, 'Proveedores', 'Proveedores', 'Proveedores', NULL, 'Design', '1', '0', NULL, NULL, NULL, NULL, '&amp;lt;body&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}', '0', '1', '0', '1', '1', '2023-09-30 18:39:21', '2025-09-17 22:20:45');
INSERT INTO `pages` (`id`, `user_id`, `language`, `position`, `system_path`, `view_page`, `title`, `slug`, `link`, `url`, `keyword`, `classification`, `description`, `image`, `type`, `menu`, `hidden_page`, `path_file`, `script_name`, `template`, `base_template`, `content`, `style`, `startpage`, `level`, `parent`, `sort`, `active`, `created_at`, `updated_at`) VALUES ('5', '', '1', '3', '', 'public', 'Ofertas', 'Title', 'ofertas', NULL, 'Ofertas', 'Ofertas', 'Ofertas', NULL, 'Design', '1', '0', NULL, NULL, NULL, NULL, '&amp;lt;body&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}', '0', '1', '4', '0', '1', '2023-09-30 18:39:21', '2025-09-17 22:20:45');
INSERT INTO `pages` (`id`, `user_id`, `language`, `position`, `system_path`, `view_page`, `title`, `slug`, `link`, `url`, `keyword`, `classification`, `description`, `image`, `type`, `menu`, `hidden_page`, `path_file`, `script_name`, `template`, `base_template`, `content`, `style`, `startpage`, `level`, `parent`, `sort`, `active`, `created_at`, `updated_at`) VALUES ('6', '', '1', '5', '', 'public', 'Contacto', 'Title', 'contacto', NULL, 'Contacto', 'Contacto', 'Contacto', NULL, 'Design', '1', '0', NULL, NULL, NULL, NULL, '&amp;lt;body&amp;gt;&amp;lt;/body&amp;gt;', '* { box-sizing: border-box; } body {margin: 0;}', '0', '1', '0', '0', '1', '2023-09-30 18:39:21', '2025-09-17 22:20:45');

DROP TABLE IF EXISTS `pages_contents`;
CREATE TABLE `pages_contents` (
  `idContent` int(11) NOT NULL AUTO_INCREMENT,
  `idPage` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `script` mediumtext NOT NULL,
  `style` mediumtext NOT NULL,
  `version` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idContent`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `pageviews`;
CREATE TABLE `pageviews` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `page` char(250) DEFAULT NULL,
  `ip` char(20) DEFAULT NULL,
  `date_view` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('1', 'Inicio', '127.0.0.1', '2024-09-06 01:40:35');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('2', '404', '127.0.0.1', '2024-09-06 01:40:36');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('3', 'Proveedores', '127.0.0.1', '2024-09-06 22:06:01');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('4', '404', '127.0.0.1', '2024-09-06 22:06:01');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('5', 'Contacto', '127.0.0.1', '2024-09-06 22:06:03');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('6', 'Ofertas', '127.0.0.1', '2024-09-06 22:06:07');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('7', 'Servicios', '127.0.0.1', '2024-09-06 22:06:13');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('8', 'Inicio', '127.0.0.1', '2024-09-06 22:06:16');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('9', 'Inicio', '127.0.0.1', '2024-09-07 22:04:47');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('10', '404', '127.0.0.1', '2024-09-07 22:04:47');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('11', 'Register', '127.0.0.1', '2024-09-07 22:04:49');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('12', 'Inicio', '127.0.0.1', '2024-10-01 23:48:04');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('13', '404', '127.0.0.1', '2024-10-01 23:48:05');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('14', 'Contacto', '127.0.0.1', '2024-10-01 23:48:08');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('15', 'Proveedores', '127.0.0.1', '2024-10-01 23:48:10');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('16', 'Ofertas', '127.0.0.1', '2024-10-01 23:48:12');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('17', 'Register', '127.0.0.1', '2024-10-01 23:48:30');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('18', 'Login', '127.0.0.1', '2024-10-01 23:48:34');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('19', '404', '127.0.0.1', '2024-10-30 23:39:35');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('20', '404', '127.0.0.1', '2024-11-04 21:56:01');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('21', 'Inicio', '127.0.0.1', '2024-11-04 23:20:09');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('22', 'Contacto', '127.0.0.1', '2024-11-04 23:58:19');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('23', 'Proveedores', '127.0.0.1', '2024-11-04 23:58:26');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('24', 'Servicios', '127.0.0.1', '2024-11-04 23:58:30');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('25', 'Login', '127.0.0.1', '2024-11-04 23:58:49');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('26', 'Register', '127.0.0.1', '2024-11-04 23:58:51');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('27', 'Promociones', '127.0.0.1', '2024-11-04 23:59:00');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('28', 'Login', '127.0.0.1', '2024-11-05 21:48:05');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('29', '404', '127.0.0.1', '2024-11-05 21:48:05');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('30', 'Inicio', '127.0.0.1', '2024-12-14 19:12:02');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('31', '404', '127.0.0.1', '2024-12-14 19:12:02');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('32', 'Contacto', '127.0.0.1', '2024-12-14 19:12:32');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('33', 'Inicio', '127.0.0.1', '2025-01-02 20:51:28');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('34', '404', '127.0.0.1', '2025-01-02 20:51:28');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('35', 'Login', '127.0.0.1', '2025-01-02 20:51:45');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('36', 'Contacto', '127.0.0.1', '2025-01-02 20:52:02');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('37', 'Proveedores', '127.0.0.1', '2025-01-02 20:52:04');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('38', 'Inicio', '127.0.0.1', '2025-01-11 11:00:03');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('39', '404', '127.0.0.1', '2025-01-11 11:00:04');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('40', 'Contacto', '127.0.0.1', '2025-01-11 11:24:38');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('41', 'Proveedores', '127.0.0.1', '2025-01-11 11:24:41');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('42', 'Promociones', '127.0.0.1', '2025-01-11 11:24:43');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('43', 'Login', '127.0.0.1', '2025-01-11 11:33:19');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('44', 'Register', '127.0.0.1', '2025-01-11 14:02:00');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('45', 'Inicio', '127.0.0.1', '2025-01-17 19:20:22');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('46', '404', '127.0.0.1', '2025-01-17 19:20:22');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('47', 'Contacto', '127.0.0.1', '2025-01-17 19:22:05');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('48', 'Proveedores', '127.0.0.1', '2025-01-17 19:22:33');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('49', 'Ofertas', '127.0.0.1', '2025-01-17 19:26:04');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('50', 'Login', '127.0.0.1', '2025-01-17 20:32:12');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('51', NULL, '127.0.0.1', '2025-01-18 12:11:43');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('52', NULL, '127.0.0.1', '2025-01-18 12:11:45');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('53', NULL, '127.0.0.1', '2025-01-18 12:11:56');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('54', 'Inicio', '127.0.0.1', '2025-01-18 22:26:29');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('55', NULL, '127.0.0.1', '2025-01-18 22:26:29');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('56', NULL, '127.0.0.1', '2025-01-18 22:26:36');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('57', NULL, '127.0.0.1', '2025-01-18 22:26:36');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('58', NULL, '127.0.0.1', '2025-01-18 22:27:36');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('59', NULL, '127.0.0.1', '2025-01-18 22:27:36');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('60', 'Register', '127.0.0.1', '2025-01-18 22:29:01');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('61', 'Login', '127.0.0.1', '2025-01-18 22:29:03');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('62', 'Inicio', '127.0.0.1', '2025-01-20 12:19:01');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('63', 'Login', '127.0.0.1', '2025-01-20 12:19:51');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('64', 'Login', '127.0.0.1', '2025-01-21 18:15:57');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('65', 'Inicio', '127.0.0.1', '2025-01-21 19:29:32');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('66', 'Login', '127.0.0.1', '2025-03-27 03:00:56');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('67', 'Inicio', '127.0.0.1', '2025-03-27 03:03:02');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('68', 'Contacto', '127.0.0.1', '2025-03-27 03:03:07');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('69', 'Proveedores', '127.0.0.1', '2025-03-27 03:03:10');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('70', 'Promociones', '127.0.0.1', '2025-03-27 03:03:13');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('71', 'Inicio', '127.0.0.1', '2025-03-28 21:18:27');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('72', 'Login', '127.0.0.1', '2025-03-28 21:18:30');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('73', 'Contacto', '127.0.0.1', '2025-03-28 21:26:01');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('74', 'Dashboard', '127.0.0.1', '2025-03-28 21:26:17');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('75', 'Contacto', '::1', '2025-03-28 23:38:26');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('76', 'Login', '::1', '2025-03-28 23:38:28');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('77', 'Forgot username', '127.0.0.1', '2025-03-29 10:43:46');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('78', 'Inicio', '::1', '2025-03-29 11:15:29');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('79', 'Inicio', '127.0.0.1', '2025-04-03 23:34:59');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('80', 'Login', '127.0.0.1', '2025-04-03 23:35:05');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('81', 'Inicio', '127.0.0.1', '2025-04-05 00:30:39');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('82', 'Login', '127.0.0.1', '2025-04-05 00:30:44');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('83', 'Dashboard', '127.0.0.1', '2025-04-05 00:32:11');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('84', 'Servicios', '127.0.0.1', '2025-04-05 00:56:01');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('85', 'Login', '::1', '2025-04-05 01:37:46');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('86', 'Inicio', '::1', '2025-04-05 01:52:37');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('87', 'Servicios', '::1', '2025-04-05 01:52:39');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('88', 'Proveedores', '::1', '2025-04-05 02:12:56');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('89', 'Ofertas', '::1', '2025-04-05 02:12:58');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('90', 'Promociones', '::1', '2025-04-05 02:13:00');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('91', 'Servicios', '::1', '2025-04-05 19:22:33');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('92', 'Proveedores', '::1', '2025-04-05 19:22:37');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('93', 'Inicio', '::1', '2025-04-05 19:23:45');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('94', 'Login', '::1', '2025-04-06 01:43:30');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('95', 'Inicio', '127.0.0.1', '2025-04-06 02:55:09');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('96', 'Servicios', '127.0.0.1', '2025-04-06 02:55:12');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('97', 'Proveedores', '127.0.0.1', '2025-04-06 03:08:00');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('98', 'Servicios', '127.0.0.1', '2025-04-07 20:17:47');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('99', 'Proveedores', '127.0.0.1', '2025-04-09 20:59:09');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('100', 'Contacto', '127.0.0.1', '2025-04-09 20:59:13');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('101', 'Inicio', '127.0.0.1', '2025-04-29 22:24:47');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('102', 'Login', '127.0.0.1', '2025-04-29 22:24:51');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('103', 'Contacto', '127.0.0.1', '2025-04-29 23:01:50');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('104', 'Inicio', '127.0.0.1', '2025-05-25 21:28:29');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('105', 'Servicios', '127.0.0.1', '2025-05-25 21:28:36');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('106', 'Promociones', '127.0.0.1', '2025-05-25 21:28:37');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('107', 'Proveedores', '127.0.0.1', '2025-05-25 21:28:39');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('108', 'Ofertas', '127.0.0.1', '2025-05-25 21:28:46');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('109', 'Register', '127.0.0.1', '2025-05-25 21:28:53');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('110', 'Login', '127.0.0.1', '2025-05-25 21:28:56');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('111', 'Inicio', '127.0.0.1', '2025-05-30 01:31:12');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('112', 'Login', '127.0.0.1', '2025-05-30 01:32:39');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('113', 'Servicios', '127.0.0.1', '2025-05-30 02:47:05');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('114', 'Promociones', '127.0.0.1', '2025-05-30 02:47:06');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('115', 'Contacto', '127.0.0.1', '2025-05-30 04:47:42');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('116', 'Proveedores', '127.0.0.1', '2025-05-30 05:41:16');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('117', 'Inicio', '127.0.0.1', '2025-05-31 02:09:29');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('118', 'Promociones', '127.0.0.1', '2025-05-31 15:52:20');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('119', 'Proveedores', '127.0.0.1', '2025-05-31 15:52:43');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('120', 'Servicios', '127.0.0.1', '2025-05-31 15:52:47');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('121', 'Contacto', '127.0.0.1', '2025-05-31 15:59:20');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('122', 'Ofertas', '127.0.0.1', '2025-06-01 22:23:47');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('123', 'Proveedores', '127.0.0.1', '2025-06-02 00:23:05');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('124', 'Contacto', '127.0.0.1', '2025-06-02 00:45:05');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('125', 'Inicio', '127.0.0.1', '2025-06-03 22:46:23');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('126', 'Contacto', '127.0.0.1', '2025-06-03 22:46:52');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('127', 'Servicios', '127.0.0.1', '2025-06-03 23:00:58');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('128', 'Inicio', '127.0.0.1', '2025-06-06 18:25:45');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('129', 'Contacto', '127.0.0.1', '2025-06-06 18:25:52');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('130', 'Promociones', '127.0.0.1', '2025-06-06 22:30:38');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('131', 'Inicio', '127.0.0.1', '2025-06-07 11:55:59');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('132', 'Inicio', '127.0.0.1', '2025-06-08 22:02:14');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('133', 'Inicio', '127.0.0.1', '2025-06-10 10:38:35');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('134', 'Contacto', '127.0.0.1', '2025-06-10 12:20:23');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('135', 'Promociones', '127.0.0.1', '2025-06-10 15:16:54');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('136', 'Inicio', '127.0.0.1', '2025-06-11 14:58:17');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('137', 'Servicios', '127.0.0.1', '2025-06-11 20:48:57');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('138', 'Proveedores', '127.0.0.1', '2025-06-11 21:02:44');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('139', 'Promociones', '127.0.0.1', '2025-06-11 21:02:47');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('140', 'Inicio', '127.0.0.1', '2025-06-12 09:44:00');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('141', 'Contacto', '127.0.0.1', '2025-06-12 09:44:33');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('142', 'Ofertas', '127.0.0.1', '2025-06-12 15:57:08');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('143', 'Proveedores', '127.0.0.1', '2025-06-12 19:51:36');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('144', 'Servicios', '127.0.0.1', '2025-06-12 19:51:39');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('145', 'Inicio', '127.0.0.1', '2025-06-13 23:19:11');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('146', 'Inicio', '127.0.0.1', '2025-07-08 22:20:59');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('147', 'Inicio', '127.0.0.1', '2025-07-16 19:13:07');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('148', 'Servicios', '127.0.0.1', '2025-07-16 19:13:58');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('149', 'Servicios', '127.0.0.1', '2025-07-18 10:29:39');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('150', 'Inicio', '127.0.0.1', '2025-07-18 23:59:41');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('151', 'Contacto', '127.0.0.1', '2025-07-19 00:37:38');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('152', 'Inicio', '127.0.0.1', '2025-07-19 18:42:59');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('153', 'Servicios', '127.0.0.1', '2025-07-19 18:43:35');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('154', 'Contacto', '127.0.0.1', '2025-07-19 18:43:39');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('155', 'Proveedores', '127.0.0.1', '2025-07-19 18:43:40');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('156', 'Ofertas', '127.0.0.1', '2025-07-19 19:23:32');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('157', 'Inicio', '127.0.0.1', '2025-07-20 22:44:00');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('158', 'Contacto', '127.0.0.1', '2025-07-20 23:26:38');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('159', 'Proveedores', '127.0.0.1', '2025-07-20 23:26:52');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('160', 'Servicios', '127.0.0.1', '2025-07-20 23:26:54');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('161', 'Promociones', '127.0.0.1', '2025-07-20 23:41:10');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('162', 'Ofertas', '127.0.0.1', '2025-07-21 00:08:03');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('163', 'Inicio', '127.0.0.1', '2025-07-21 18:06:37');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('164', 'Inicio', '127.0.0.1', '2025-08-09 15:49:20');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('165', 'Inicio', '127.0.0.1', '2025-08-11 21:39:16');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('166', 'Inicio', '127.0.0.1', '2025-08-12 23:43:48');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('167', 'Inicio', '127.0.0.1', '2025-08-13 23:03:22');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('168', 'Inicio', '127.0.0.1', '2025-08-16 16:46:54');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('169', 'Servicios', '127.0.0.1', '2025-08-16 16:47:02');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('170', 'Contacto', '127.0.0.1', '2025-08-16 16:48:22');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('171', 'Promociones', '127.0.0.1', '2025-08-16 16:48:31');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('172', 'Servicios', '127.0.0.1', '2025-08-18 23:23:14');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('173', 'Inicio', '127.0.0.1', '2025-08-18 23:28:46');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('174', 'Inicio', '127.0.0.1', '2025-08-21 20:45:44');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('175', 'Inicio', '::1', '2025-08-22 19:44:14');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('176', 'Inicio', '127.0.0.1', '2025-08-23 00:51:34');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('177', 'Promociones', '::1', '2025-08-25 18:07:29');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('178', 'Inicio', '127.0.0.1', '2025-08-26 12:34:05');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('179', 'Inicio', '127.0.0.1', '2025-09-17 21:56:50');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('180', 'Contacto', '127.0.0.1', '2025-09-17 22:25:30');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('181', 'Proveedores', '127.0.0.1', '2025-09-17 22:25:33');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('182', 'Servicios', '127.0.0.1', '2025-09-17 22:25:35');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('183', 'Inicio', '127.0.0.1', '2025-12-08 22:22:19');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('184', 'Inicio', '127.0.0.1', '2026-01-02 01:13:39');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('185', 'Inicio', '127.0.0.1', '2026-01-08 11:56:34');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('186', 'Inicio', '127.0.0.1', '2026-09-02 18:36:04');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('187', 'register', '127.0.0.1', '2026-09-02 18:38:27');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('188', 'Ofertas', '127.0.0.1', '2026-09-02 18:38:39');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('189', 'Proveedores', '127.0.0.1', '2026-09-02 18:38:41');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('190', 'Servicios', '127.0.0.1', '2026-09-02 18:38:44');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('191', 'Inicio', '127.0.0.1', '2026-09-07 15:08:20');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('192', 'login', '127.0.0.1', '2026-09-07 20:57:06');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('193', 'Inicio', '127.0.0.1', '2026-09-15 18:55:06');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('194', 'Inicio', '127.0.0.1', '2026-10-16 14:22:28');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('195', 'Promociones', '127.0.0.1', '2026-10-16 15:07:43');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('196', 'Contacto', '127.0.0.1', '2026-10-16 15:08:12');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('197', 'Proveedores', '127.0.0.1', '2026-10-16 15:08:25');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('198', 'Servicios', '127.0.0.1', '2026-10-16 16:15:09');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('199', 'login', '127.0.0.1', '2026-10-17 12:09:59');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('200', 'favicon.ico', '127.0.0.1', '2026-10-17 12:10:10');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('201', 'register', '127.0.0.1', '2026-10-17 12:10:14');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('202', 'Inicio', '::1', '2026-10-17 12:18:44');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('203', 'login', '::1', '2026-10-17 12:18:45');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('204', 'register', '::1', '2026-10-17 12:18:45');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('205', 'dashboard', '::1', '2026-10-17 12:18:45');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('206', '404', '::1', '2026-10-17 12:18:45');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('207', 'index.php', '::1', '2026-10-17 12:19:00');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('208', 'Ofertas', '127.0.0.1', '2026-10-17 12:19:37');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('209', 'logout', '::1', '2026-10-17 12:20:26');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('210', 'userprofile', '::1', '2026-10-17 12:28:21');
INSERT INTO `pageviews` (`id`, `page`, `ip`, `date_view`) VALUES ('211', 'Inicio', '127.0.0.1', '2026-10-17 12:30:13');

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'General',
  `required` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('1', 'Verify Users', 'Administration permission allowing for the verification of new users', 'Users', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('2', 'Delete Unverified Users', 'Administration permission allowing the deletion of unverified users', 'Users', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('3', 'Ban Users', 'Moderation permission allowing the banning of users', 'Users', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('4', 'Assign Roles to Users', 'Administration permission allowing the assignment of roles to users', 'Users', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('5', 'Assign Users to Roles', 'Administration permission allowing the assignment of users to roles', 'Roles', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('6', 'Create Roles', 'Administration permission allowing for the creation of new roles', 'Roles', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('7', 'Delete Roles', 'Administration permission allowing for the deletion of roles', 'Roles', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('8', 'Create Permissions', 'Administration permission allowing for the creation of new permissions', 'Permissions', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('9', 'Delete Permissions', 'Administration permission allowing for the deletion of permissions', 'Permissions', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('10', 'Assign Permissions to Roles', 'Administration permission allowing the assignment of permissions to roles', 'Roles', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('11', 'Edit Site Config', 'Administration permission allowing the editing of core site configuration (dangerous)', 'Administration', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('12', 'View Permissions', 'Administration permission allowing the viewing of all permissions', 'Permissions', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('13', 'View Roles', 'Administration permission allowing for the viewing of all roles', 'Roles', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('14', 'View Users', 'Administration permission allowing for the viewing of all users', 'Users', '1');
INSERT INTO `permissions` (`id`, `name`, `description`, `category`, `required`) VALUES ('15', 'Delete Users', 'Administration permission allowing for the deletion of users', 'Users', '1');

DROP TABLE IF EXISTS `personal_config`;
CREATE TABLE `personal_config` (
  `usercode` char(128) DEFAULT NULL,
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
  `skills` varchar(50) DEFAULT NULL,
  UNIQUE KEY `usercode_U` (`usercode`),
  KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `personal_services`;
CREATE TABLE `personal_services` (
  `idd` char(128) NOT NULL,
  `usercode` char(128) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `edad` tinyint(4) DEFAULT NULL,
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
  PRIMARY KEY (`idd`) USING BTREE,
  UNIQUE KEY `idd` (`idd`) USING BTREE,
  KEY `usercode` (`usercode`),
  CONSTRAINT `FK1_idd` FOREIGN KEY (`idd`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `plugins_app`;
CREATE TABLE `plugins_app` (
  `id` int(11) NOT NULL,
  `plugins` char(150) DEFAULT NULL,
  `plugins_opts` char(150) DEFAULT NULL,
  `script` char(150) DEFAULT NULL,
  `css` char(150) DEFAULT NULL,
  `buttons` char(150) DEFAULT NULL,
  `plugins_script` char(150) DEFAULT NULL,
  `plugins_css` char(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `provinces`;
CREATE TABLE `provinces` (
  `idProv` char(6) NOT NULL,
  `name` varchar(50) NOT NULL,
  `region_id` char(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idProv`),
  UNIQUE KEY `idProv` (`idProv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('010100', 'Chachapoyas', '010000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('010200', 'Bagua', '010000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('010300', 'BongarÃ¡', '010000', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('010400', 'Condorcanqui', '010000', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('010500', 'Luya', '010000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('010600', 'RodrÃ­guez de Mendoza', '010000', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('010700', 'Utcubamba', '010000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('020100', 'Huaraz', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('020200', 'Aija', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('020300', 'Antonio Raymondi', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('020400', 'AsunciÃ³n', '020000', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('020500', 'Bolognesi', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('020600', 'Carhuaz', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('020700', 'Carlos Fermín Fitzcarrald', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('020800', 'Casma', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('020900', 'Corongo', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('021000', 'Huari', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('021100', 'Huarmey', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('021200', 'Huaylas', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('021300', 'Mariscal Luzuriaga', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('021400', 'Ocros', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('021500', 'Pallasca', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('021600', 'Pomabamba', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('021700', 'Recuay', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('021800', 'Santa', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('021900', 'Sihuas', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('022000', 'Yungay', '020000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('030100', 'Abancay', '030000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('030200', 'Andahuaylas', '030000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('030300', 'Antabamba', '030000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('030400', 'Aymaraes', '030000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('030500', 'Cotabambas', '030000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('030600', 'Chincheros', '030000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('030700', 'Grau', '030000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('040100', 'Arequipa', '040000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('040200', 'Camaná', '040000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('040300', 'Caravelí', '040000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('040400', 'Castilla', '040000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('040500', 'Caylloma', '040000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('040600', 'Condesuyos', '040000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('040700', 'Islay', '040000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('040800', 'La Uniòn', '040000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('050100', 'Huamanga', '050000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('050200', 'Cangallo', '050000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('050300', 'Huanca Sancos', '050000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('050400', 'Huanta', '050000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('050500', 'La Mar', '050000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('050600', 'Lucanas', '050000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('050700', 'Parinacochas', '050000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('050800', 'Pàucar del Sara Sara', '050000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('050900', 'Sucre', '050000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('051000', 'Víctor Fajardo', '050000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('051100', 'Vilcas Huamán', '050000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('060100', 'Cajamarca', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('060200', 'Cajabamba', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('060300', 'Celendín', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('060400', 'Chota', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('060500', 'Contumazá', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('060600', 'Cutervo', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('060700', 'Hualgayoc', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('060800', 'Jaén', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('060900', 'San Ignacio', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('061000', 'San Marcos', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('061100', 'San Miguel', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('061200', 'San Pablo', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('061300', 'Santa Cruz', '060000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('070100', 'Prov. Const. del Callao', '070000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('080100', 'Cusco', '080000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('080200', 'Acomayo', '080000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('080300', 'Anta', '080000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('080400', 'Calca', '080000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('080500', 'Canas', '080000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('080600', 'Canchis', '080000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('080700', 'Chumbivilcas', '080000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('080800', 'Espinar', '080000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('080900', 'La ConvenciÃ³n', '080000', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('081000', 'Paruro', '080000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('081100', 'Paucartambo', '080000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('081200', 'Quispicanchi', '080000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('081300', 'Urubamba', '080000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('090100', 'Huancavelica', '090000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('090200', 'Acobamba', '090000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('090300', 'Angaraes', '090000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('090400', 'Castrovirreyna', '090000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('090500', 'Churcampa', '090000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('090600', 'Huaytará', '090000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('090700', 'Tayacaja', '090000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('100100', 'HuÃ¡nuco', '100000', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('100200', 'Ambo', '100000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('100300', 'Dos de Mayo', '100000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('100400', 'Huacaybamba', '100000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('100500', 'Huamalíes', '100000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('100600', 'Leoncio Prado', '100000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('100700', 'Marañón', '100000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('100800', 'Pachitea', '100000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('100900', 'Puerto Inca', '100000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('101000', 'Lauricocha ', '100000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('101100', 'Yarowilca ', '100000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('110100', 'Ica ', '110000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('110200', 'Chincha ', '110000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('110300', 'Nasca ', '110000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('110400', 'Palpa ', '110000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('110500', 'Pisco ', '110000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('120100', 'Huancayo ', '120000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('120200', 'Concepción ', '120000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('120300', 'Chanchamayo ', '120000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('120400', 'Jauja ', '120000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('120500', 'Junín ', '120000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('120600', 'Satipo ', '120000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('120700', 'Tarma ', '120000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('120800', 'Yauli ', '120000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('120900', 'Chupaca ', '120000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('130100', 'Trujillo ', '130000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('130200', 'Ascope ', '130000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('130300', 'Bolívar ', '130000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('130400', 'Chepén ', '130000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('130500', 'Julcán ', '130000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('130600', 'Otuzco ', '130000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('130700', 'Pacasmayo ', '130000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('130800', 'Pataz ', '130000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('130900', 'Sánchez Carrión ', '130000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('131000', 'Santiago de Chuco ', '130000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('131100', 'Gran Chimú ', '130000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('131200', 'Virú ', '130000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('140100', 'Chiclayo ', '140000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('140200', 'Ferreñafe ', '140000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('140300', 'Lambayeque ', '140000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('150100', 'Lima ', '150000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('150200', 'Barranca ', '150000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('150300', 'Cajatambo ', '150000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('150400', 'Canta ', '150000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('150500', 'Cañete ', '150000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('150600', 'Huaral ', '150000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('150700', 'Huarochirí ', '150000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('150800', 'Huaura ', '150000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('150900', 'Oyón ', '150000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('151000', 'Yauyos ', '150000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('160100', 'Maynas ', '160000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('160200', 'Alto Amazonas ', '160000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('160300', 'Loreto ', '160000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('160400', 'Mariscal Ramón Castilla ', '160000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('160500', 'Requena ', '160000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('160600', 'Ucayali ', '160000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('160700', 'Datem del Marañón ', '160000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('160800', 'Putumayo', '160000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('170100', 'Tambopata ', '170000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('170200', 'Manu ', '170000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('170300', 'Tahuamanu ', '170000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('180100', 'Mariscal Nieto ', '180000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('180200', 'General Sánchez Cerro ', '180000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('180300', 'Ilo ', '180000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('190100', 'Pasco ', '190000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('190200', 'Daniel Alcides Carrión ', '190000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('190300', 'Oxapampa ', '190000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('200100', 'Piura ', '200000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('200200', 'Ayabaca ', '200000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('200300', 'Huancabamba ', '200000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('200400', 'Morropón ', '200000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('200500', 'Paita ', '200000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('200600', 'Sullana ', '200000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('200700', 'Talara ', '200000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('200800', 'Sechura ', '200000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('210100', 'Puno ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('210200', 'Azángaro ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('210300', 'Carabaya ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('210400', 'Chucuito ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('210500', 'El Collao ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('210600', 'Huancané ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('210700', 'Lampa ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('210800', 'Melgar ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('210900', 'Moho ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('211000', 'San Antonio de Putina ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('211100', 'San Román ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('211200', 'Sandia ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('211300', 'Yunguyo ', '210000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('220100', 'Moyobamba ', '220000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('220200', 'Bellavista ', '220000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('220300', 'El Dorado ', '220000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('220400', 'Huallaga ', '220000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('220500', 'Lamas ', '220000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('220600', 'Mariscal Cáceres ', '220000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('220700', 'Picota ', '220000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('220800', 'Rioja ', '220000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('220900', 'San Martín ', '220000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('221000', 'Tocache ', '220000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('230100', 'Tacna ', '230000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('230200', 'Candarave ', '230000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('230300', 'Jorge Basadre ', '230000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('230400', 'Tarata ', '230000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('240100', 'Tumbes ', '240000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('240200', 'Contralmirante Villar ', '240000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('240300', 'Zarumilla ', '240000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('250100', 'Coronel Portillo ', '250000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('250200', 'Atalaya ', '250000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('250300', 'Padre Abad ', '250000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');
INSERT INTO `provinces` (`idProv`, `name`, `region_id`, `created_at`, `updated_at`) VALUES ('250400', 'Purús', '250000', '2023-10-18 01:07:13', '2023-10-18 01:07:13');

DROP TABLE IF EXISTS `rating_sp`;
CREATE TABLE `rating_sp` (
  `idRat` int(11) NOT NULL AUTO_INCREMENT,
  `usercode` char(128) NOT NULL DEFAULT '',
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idRat`),
  UNIQUE KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `regions`;
CREATE TABLE `regions` (
  `idRegi` char(6) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idRegi`),
  UNIQUE KEY `idRegi` (`idRegi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('010000', 'Amazonas', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('020000', 'Ãncash', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('030000', 'ApurÃ­mac', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('040000', 'Arequipa', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('050000', 'Ayacucho', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('060000', 'Cajamarca', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('070000', 'Callao', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('080000', 'Cusco', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('090000', 'Huancavelica', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('100000', 'HuÃ¡nuco', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('110000', 'Ica', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('120000', 'Junín', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('130000', 'La Libertad', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('140000', 'Lambayeque', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('150000', 'Lima', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('160000', 'Loreto', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('170000', 'Madre de Dios', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('180000', 'Moquegua', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('190000', 'Pasco', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('200000', 'Piura', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('210000', 'Puno', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('220000', 'San MartÃ­n', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('230000', 'Tacna', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('240000', 'Tumbes', NULL, NULL);
INSERT INTO `regions` (`idRegi`, `name`, `created_at`, `updated_at`) VALUES ('250000', 'Ucayali', NULL, NULL);

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Role_Id_idx` (`role_id`),
  KEY `fk_Permission_Id_idx` (`permission_id`),
  CONSTRAINT `fk_Permission_Id` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Role_Id_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('1', '1', '1');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('2', '1', '2');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('3', '1', '3');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('4', '1', '4');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('5', '1', '5');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('6', '1', '6');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('7', '1', '7');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('8', '1', '8');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('9', '1', '9');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('10', '1', '10');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('11', '1', '11');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('12', '1', '12');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('13', '1', '13');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('14', '1', '14');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('15', '1', '15');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('16', '2', '1');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('17', '2', '2');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('18', '2', '3');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('19', '2', '4');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('20', '2', '5');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('21', '2', '12');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('22', '2', '13');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('23', '2', '14');
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES ('24', '2', '15');

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `default_role` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `default_role_UNIQUE` (`default_role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `roles` (`id`, `name`, `description`, `required`, `default_role`) VALUES ('1', 'Superadmin', 'Master administrator of site', '1', NULL);
INSERT INTO `roles` (`id`, `name`, `description`, `required`, `default_role`) VALUES ('2', 'Admin', 'Site administrator', '1', NULL);
INSERT INTO `roles` (`id`, `name`, `description`, `required`, `default_role`) VALUES ('3', 'Standard User', 'Default site role for standard users', '1', '1');

DROP TABLE IF EXISTS `secrets`;
CREATE TABLE `secrets` (
  `secretid` char(128) NOT NULL,
  `userid` char(128) NOT NULL,
  `usercode` char(128) NOT NULL,
  `tokenusr` varchar(256) DEFAULT NULL,
  `hashusr` varchar(256) DEFAULT NULL,
  `keyusr` varchar(256) DEFAULT NULL,
  `ivusr` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`secretid`) USING BTREE,
  UNIQUE KEY `secretid` (`secretid`) USING BTREE,
  UNIQUE KEY `userid` (`userid`) USING BTREE,
  UNIQUE KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `set_time` int(11) NOT NULL DEFAULT 2,
  `data` varchar(128) NOT NULL,
  `session_key` varchar(128) NOT NULL,
  `ip` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `site_configuration`;
CREATE TABLE `site_configuration` (
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
  `NAME_CONTACT` varchar(128) DEFAULT NULL,
  `PHONE_CONTACT` varchar(60) DEFAULT NULL,
  `EMAIL_CONTACT` varchar(128) DEFAULT NULL,
  `ADDRESS` tinytext DEFAULT NULL,
  `TWITTER` varchar(60) DEFAULT NULL,
  `FACEBOOKID` varchar(60) DEFAULT NULL,
  `SKYPE` varchar(60) DEFAULT NULL,
  `TELEGRAM` varchar(60) DEFAULT NULL,
  `WHATSAPP` varchar(60) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID_Site`),
  UNIQUE KEY `SITE_NAME` (`SITE_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `site_configuration` (`ID_Site`, `DOMAIN_SITE`, `SITE_NAME`, `SITE_BRAND_IMG`, `SITE_PATH`, `SITE_DESCRIPTION`, `SITE_KEYWORDS`, `SITE_CLASSIFICATION`, `SITE_EMAIL`, `SITE_IMAGE`, `SITE_ADMIN`, `SITE_CONTROL`, `SITE_CONFIG`, `SITE_LANGUAGE_1`, `SITE_LANGUAGE_2`, `FOLDER_IMAGES`, `SITE_CREATOR`, `SITE_EDITOR`, `SITE_BUILDER`, `SITE_LIST`, `NAME_CONTACT`, `PHONE_CONTACT`, `EMAIL_CONTACT`, `ADDRESS`, `TWITTER`, `FACEBOOKID`, `SKYPE`, `TELEGRAM`, `WHATSAPP`, `created_at`, `updated_at`) VALUES ('1', '//www.php-grapesjs.com/', 'PHP GrapesJS', 'icon-pepiuox.png', 'http://localhost:130/', 'Your description for your domains', 'Your keywords for your domains', 'Your classification for your domains', 'contact@pepiuox.net', 'dashboard', 'dashboard', 'users', 'siteconf', 'English', 'Spanish', 'uploads', 'admin', 'admin, editor', 'builder', 'list', 'Jose Mantilla', '0051999063645', 'contact@pepiuox.net', 'Lima - Peru', '@pepiuox', 'pepiuox30675', 'pepiuox', 'pepiuox', '+51 999063645', '2022-01-08 08:42:41', '2025-05-30 06:19:50');

DROP TABLE IF EXISTS `site_mail_configuration`;
CREATE TABLE `site_mail_configuration` (
  `idMc` int(11) NOT NULL AUTO_INCREMENT,
  `idSite` int(11) NOT NULL,
  `mail_support` char(60) DEFAULT NULL,
  `subject_support` char(150) DEFAULT NULL,
  `mail_help` char(50) DEFAULT NULL,
  `subject_help` char(150) DEFAULT NULL,
  `mail_faq` char(50) DEFAULT NULL,
  `subject_faq` char(150) DEFAULT NULL,
  PRIMARY KEY (`idMc`),
  KEY `FK_email_site` (`idSite`),
  CONSTRAINT `FK_email_site` FOREIGN KEY (`idSite`) REFERENCES `site_configuration` (`ID_Site`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `site_mail_configuration` (`idMc`, `idSite`, `mail_support`, `subject_support`, `mail_help`, `subject_help`, `mail_faq`, `subject_faq`) VALUES ('1', '1', 'support@esrapi.com', NULL, 'help@esrapi.com', NULL, 'faq@esrapi.com', NULL);

DROP TABLE IF EXISTS `site_security`;
CREATE TABLE `site_security` (
  `idSs` int(11) NOT NULL AUTO_INCREMENT,
  `site` int(11) NOT NULL,
  `MAILSERVER` varchar(60) DEFAULT NULL,
  `PORTSERVER` varchar(60) DEFAULT NULL,
  `USEREMAIL` varchar(128) DEFAULT NULL,
  `PASSMAIL` varchar(128) DEFAULT NULL,
  `SUPERADMIN_NAME` varchar(60) DEFAULT NULL,
  `SUPERADMIN_LEVEL` tinyint(1) DEFAULT NULL,
  `ADMIN_NAME` varchar(60) NOT NULL DEFAULT '',
  `ADMIN_LEVEL` tinyint(1) NOT NULL,
  `SECURE_HASH` char(128) DEFAULT NULL,
  `SECURE_TOKEN` char(128) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idSs`) USING BTREE,
  KEY `FK_site_secutity` (`site`),
  CONSTRAINT `FK_site_secutity` FOREIGN KEY (`site`) REFERENCES `site_configuration` (`ID_Site`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `site_security` (`idSs`, `site`, `MAILSERVER`, `PORTSERVER`, `USEREMAIL`, `PASSMAIL`, `SUPERADMIN_NAME`, `SUPERADMIN_LEVEL`, `ADMIN_NAME`, `ADMIN_LEVEL`, `SECURE_HASH`, `SECURE_TOKEN`, `created_at`, `updated_at`) VALUES ('1', '1', 'smtp.hostinger.com', '461', 'YWlsMldhQ3E0UEtGbjhQTGQ4YUVPbUFvQjI4ZWxsNXEycjdVbXFSNG5MZz0=', 'bnVEa3VFZXV6eERRNjVDcEVpZWJNWEoxVW9WTEE1YTBmMnczTXB4aElFMD0=', 'Super Admin', '9', 'Admin', '5', 'a323dd668edc79a1a294ce4d7ef25f242e55697384877f8fcd57d67fead56249', 'vrHPYiZV9bytr{I4Js5WoIuxJhTq5P9ATa({s(Cj1Dj%S67scb}z8#V0pC@ZjeZe5VOF[FK[wDGFCM{vLNsPo4OT)%r@veC#yASk8vwZh%oQ)j1YFlDu}1iUnUzEhJrO', '2023-11-14 15:20:47', '2025-03-28 23:18:51');

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `idSet` int(11) NOT NULL,
  `site` varchar(60) DEFAULT NULL,
  `load_content` enum('Pages','Blogs') DEFAULT NULL,
  PRIMARY KEY (`idSet`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `social_link`;
CREATE TABLE `social_link` (
  `social_name` varchar(20) DEFAULT NULL,
  `social_url` varchar(150) DEFAULT NULL,
  KEY `social_name` (`social_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `table_column_settings`;
CREATE TABLE `table_column_settings` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `table_config`;
CREATE TABLE `table_config` (
  `tcon_Id` int(11) NOT NULL AUTO_INCREMENT,
  `db_name` char(50) NOT NULL,
  `table_name` text DEFAULT NULL,
  PRIMARY KEY (`tcon_Id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `table_config` (`tcon_Id`, `db_name`, `table_name`) VALUES ('1', '', 'menu,menu_options,page_menu,templates,theme_base_colors,theme_base_font,theme_headings_font,theme_lead_font,theme_palette,theme_settings,themes');

DROP TABLE IF EXISTS `table_settings`;
CREATE TABLE `table_settings` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `templates`;
CREATE TABLE `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `content_html` longtext DEFAULT NULL,
  `content_css` longtext DEFAULT NULL,
  `content_php` longtext DEFAULT NULL,
  `content_js` longtext DEFAULT NULL,
  `is_system_template` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;


DROP TABLE IF EXISTS `theme_base_colors`;
CREATE TABLE `theme_base_colors` (
  `idtbc` int(11) NOT NULL AUTO_INCREMENT,
  `body_color` char(8) DEFAULT NULL,
  `text_color` char(8) DEFAULT NULL,
  `links_color` char(8) DEFAULT NULL,
  PRIMARY KEY (`idtbc`) USING BTREE,
  UNIQUE KEY `theme_id` (`idtbc`) USING BTREE,
  CONSTRAINT `FK_theme_base_colors` FOREIGN KEY (`idtbc`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `theme_base_colors` (`idtbc`, `body_color`, `text_color`, `links_color`) VALUES ('1', '#ffffff', '#2f0202', '#172fa2');

DROP TABLE IF EXISTS `theme_base_font`;
CREATE TABLE `theme_base_font` (
  `idtbf` int(11) NOT NULL AUTO_INCREMENT,
  `family` char(50) DEFAULT NULL,
  `size` char(50) DEFAULT NULL,
  `weight` enum('default','light','normal','bold') NOT NULL DEFAULT 'default',
  `line_height` char(50) DEFAULT NULL,
  PRIMARY KEY (`idtbf`) USING BTREE,
  UNIQUE KEY `theme_id` (`idtbf`) USING BTREE,
  CONSTRAINT `FK_theme_base_font` FOREIGN KEY (`idtbf`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `theme_base_font` (`idtbf`, `family`, `size`, `weight`, `line_height`) VALUES ('1', '', '', 'default', '');

DROP TABLE IF EXISTS `theme_headings_font`;
CREATE TABLE `theme_headings_font` (
  `idthf` int(11) NOT NULL AUTO_INCREMENT,
  `family` char(50) DEFAULT NULL,
  `weight` enum('default','light','normal','bold') NOT NULL DEFAULT 'default',
  `line_weight` char(50) DEFAULT NULL,
  PRIMARY KEY (`idthf`) USING BTREE,
  UNIQUE KEY `theme_id` (`idthf`) USING BTREE,
  CONSTRAINT `FK_theme_headings_font` FOREIGN KEY (`idthf`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `theme_headings_font` (`idthf`, `family`, `weight`, `line_weight`) VALUES ('1', '', 'default', '');

DROP TABLE IF EXISTS `theme_lead_font`;
CREATE TABLE `theme_lead_font` (
  `idtlf` int(11) NOT NULL AUTO_INCREMENT,
  `size` char(6) DEFAULT NULL,
  `weight` char(6) DEFAULT NULL,
  PRIMARY KEY (`idtlf`) USING BTREE,
  UNIQUE KEY `theme_id` (`idtlf`) USING BTREE,
  CONSTRAINT `FK_theme_lead_font` FOREIGN KEY (`idtlf`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `theme_lead_font` (`idtlf`, `size`, `weight`) VALUES ('1', '', '');

DROP TABLE IF EXISTS `theme_palette`;
CREATE TABLE `theme_palette` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `theme_palette` (`idtp`, `primary_color`, `secondary_color`, `info_color`, `light_color`, `dark_color`, `success_color`, `warning_color`, `danger_color`, `custom_color`, `custom_light_color`, `custom_dark_color`) VALUES ('1', '#993f3f', '#814040', '#1f53a5', '#000000', '#4b3737', '#000000', '#d24747', '#000000', '#000000', '#d4b8b8', '#000000');

DROP TABLE IF EXISTS `theme_settings`;
CREATE TABLE `theme_settings` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `theme_settings` (`idts`, `container`, `spacer`, `radius`, `radius_sm`, `radius_lg`, `font_size`) VALUES ('1', 'default', 'default', NULL, NULL, NULL, 'default');

DROP TABLE IF EXISTS `themes`;
CREATE TABLE `themes` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` char(50) NOT NULL,
  `theme_bootstrap` char(50) NOT NULL,
  `base_default` enum('Yes','No') NOT NULL DEFAULT 'No',
  `active_theme` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`theme_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `themes` (`theme_id`, `theme_name`, `theme_bootstrap`, `base_default`, `active_theme`) VALUES ('1', 'theme color', 'cerulean', 'Yes', 'Yes');

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens` (
  `tokenid` char(25) NOT NULL,
  `userid` char(128) NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`tokenid`) USING BTREE,
  UNIQUE KEY `tokenid_UNIQUE` (`tokenid`) USING BTREE,
  UNIQUE KEY `userid_UNIQUE` (`userid`) USING BTREE,
  CONSTRAINT `userid_t` FOREIGN KEY (`userid`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `topics`;
CREATE TABLE `topics` (
  `topicId` int(11) NOT NULL AUTO_INCREMENT,
  `topic_name` char(100) DEFAULT NULL,
  PRIMARY KEY (`topicId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `total_visitors`;
CREATE TABLE `total_visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` char(128) DEFAULT NULL,
  `time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('1', 'VzB2V21zVUVpVGJvUGt6TEoxTFJHZz09', '2023-10-25 06:21:21');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('2', 'Q3NucDBJazVVclkrZTE1RkVyME4zUT09', '2023-12-23 04:06:12');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('3', 'N0U5cmRDWUd6aHJBeDkvL3JlanZvUT09', '2024-04-12 03:38:46');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('4', 'dXJZeUJrUDNHZFZDOThheEg4OWZlZz09', '2023-12-27 04:25:59');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('5', 'amtrNGpkMWo2VitFQUtHaTNZeWFOQT09', '2024-04-14 08:33:35');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('6', 'UHp6aTI1UXVOczlpTUd6TjY5UDN3QT09', '2025-01-22 01:29:32');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('7', 'cnM4dVlWYXUxQlV3UUFYYzlZL0dhUT09', '2025-03-29 04:35:47');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('8', 'NzNyU1dCbGpQK2ZtOEZrc0NNRUh6Zz09', '2026-10-17 14:30:13');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('9', 'L1E4aFVnK3BpUnQxWU0yYnhrd2x0QT09', '2026-10-17 14:34:01');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('10', 'Iymlht1AY2k+lL3TRz+SYYLrkfQlh+i34AKL8OP1KjZJL1CA4g==', '2026-09-02 23:36:04');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('11', '+1U86rcdMcPpskxTh8UNGJli9X3x0HbvZchVzXr+PCMqPTXpqg==', '2026-09-02 23:38:27');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('12', '0Ra/bBLFEP8vust98yQDpnxn+6w8DxP96c1xmaIItvfijH831g==', '2026-09-02 23:38:39');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('13', 'a119ScRvRw8V2W62C2o4LZFnGh6chVVNYsGR5duG0qpPogFNaA==', '2026-09-02 23:38:41');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('14', 'ZDe/xLNSPPcw9QjeeJxa/0wD5CO9M8PoUioNVP7oztOFJMeTKA==', '2026-09-02 23:38:44');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('15', 'jt7mqlKQ0MXiEXz26Mhtnl14sk+Djg1fP9YbiFVVfQp7oqVBYA==', '2026-09-02 23:38:46');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('16', 'Apz5DIw8f73AKI97VjJVy/iwZikPp1dyLEl/kLnyP7VcwWw9Fg==', '2026-09-03 16:07:42');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('17', 'ctarlp9yCeK5b0HqXH+N+isoLm7r6mOgk2An6S4XmEn9jXmZ7w==', '2026-09-03 16:29:06');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('18', 'MO4v8lQwDsA/Mjpg+vJWkoPfNeRWYYBTM7sgnANPAbYwLShYVw==', '2026-09-07 20:08:20');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('19', 'g5eS9a+fzfNB2Giyu3wohDllfkr8CKNzCner9YLYCINDFcI2NQ==', '2026-09-08 01:57:06');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('20', 'h7mMRWjMR8r+wNkQx2lR3FcC463epAikmBPknOg7HXRTq/234w==', '2026-09-15 23:55:06');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('21', 'kxa3BiRSoofDKvL/Mkzvia/jmxnSsqMWXHBiHdtawBW3bNk7Eg==', '2026-09-15 23:55:54');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('22', 'asGcQK3KjF5SJCWL8I7GpQo/se4gZ207OGYAfCDrD+kENoNXUw==', '2026-09-16 00:03:23');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('23', 'yYPdOxat3tOLdve/UINiTLlf75YYGs6+ODy3L9pXA94fB1Y7Rw==', '2026-10-16 16:22:28');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('24', 'VcbLNrAwGg+Y0okwWgRrEjIJE+HyYbsTwBrtHFGO9QM0mf/dzw==', '2026-10-16 16:22:28');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('25', '6MYKJyQPA0Ibk2M78cqrMVHteWnDOp/FXE1r5PSjsBoBr7mXGA==', '2026-10-16 16:30:22');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('26', '6jPMKBw887IwUDDGiOw3HHeWmosZMGn62iSGOW9Gn1f1tMLUlQ==', '2026-10-16 17:03:51');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('27', '060vL6GO5+I085e/UBS33awcf9gAGi3OrcJuPGm8QY/lgdveYg==', '2026-10-16 17:03:52');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('28', '9AAhX582537UjaNKFQZdKGt0JTS50J99hfITCfN5jpoVuM7u1w==', '2026-10-16 17:07:23');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('29', 'or2/VhA72mo/lim9QsDui2GIff3a4qITMOHt5YimwowbPw1RxA==', '2026-10-16 17:07:32');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('30', 'R6/7D+XLrJhQhq2gJarZyE1zyeSyTDsHzp4XTta/6Y2y8sUbSQ==', '2026-10-16 17:07:43');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('31', 'CPkZEYyqzvsnApHg/Fo4uCWrXiWKVcNn6ztGEmndRrNAM18v8A==', '2026-10-16 17:07:55');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('32', 'D/F0e1IobhacxXuedxl3YBTFg6/hHWZSVU26JbZEgOCMHiU6lw==', '2026-10-16 17:08:02');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('33', 'P3+bOu1J9oBwmy6huO+t+v62ocdBdiIJ21duh/1PintgFS6neg==', '2026-10-16 17:08:06');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('34', 'SrMZP3AgKxhPJvgO4UHE6ePecvSEwPmkhqwu5ivLttw+dvBOyw==', '2026-10-16 17:08:09');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('35', 'P8++29YyY/ecFInV0Fv9LC6lWd1MsznY8HaX4V4ULw+61PmCTQ==', '2026-10-16 17:08:12');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('36', 'OdUsI+ihUn6u/lQposO8PlbgcyH8PHWiv4we+SYtCfeaaVjgpw==', '2026-10-16 17:08:16');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('37', 'dDkBolRKF0zFfn3sDkKqDKdcCDaVe0KXvPxh5XaK4Pn9iEd3uA==', '2026-10-16 17:08:18');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('38', '5jMABL47cgRt2ZgFhIVAdrtmTlDWeFxGWcyvDtopeAwqjHyRLA==', '2026-10-16 17:08:21');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('39', 'VR9XoDICgbHIR+bPxLMoMceylJ9joigjiN/mj8guOzKPJ6xLyQ==', '2026-10-16 17:08:24');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('40', 'Zbc+/zm+2IduvKKHpMhKkJ7B7dqGAnE8KfWTt2MYZEyCOSAeXw==', '2026-10-16 17:08:27');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('41', '4O5SavMOl15o4a9re7I+Hw8YIXiO+LKl9vCdp6LZ9LgBqBll0g==', '2026-10-16 18:14:47');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('42', 'ylKnY1MvKz0uvbO44WXnwtToCRQQakOlRqjY8fRhxqQIrTEiQA==', '2026-10-16 18:14:49');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('43', '96DwwxPM+WWrdBxKPxOwy/EUS89KfsSdBCesxJXiFB9u5jQBXg==', '2026-10-16 18:14:49');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('44', 'pKsZmZdtuKW5uFQaOSi8wvfoX/eAGcLYqo5l0h06SoE2Jo3MsQ==', '2026-10-16 18:14:58');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('45', 'yxvhHTVHzaUqGNfRakVDvCkN3EyAkptloNjXGa1kwas4UrqUrw==', '2026-10-16 18:15:00');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('46', '2QdE/iLKngcYOXlgj8rQ4SCj3WBJcH1z81/spyDktiw7/cADog==', '2026-10-16 18:15:03');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('47', '/yepK8pGOTL5402WKAJG8EzDB8B4BXk/agP31HqSQ/Q3q1kjHg==', '2026-10-16 18:15:05');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('48', 'VMk8bBIykqh5WbG1h7L8ylKKOKwM7KiNHatXYEKig1hsU1LzBQ==', '2026-10-16 18:15:07');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('49', 'FwZIkz+GqlNC/og+hB/Fzy6w4TqBHeOm1eN5JLN9xsnbEfFcwA==', '2026-10-16 18:15:09');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('50', '0dokLQL0JzboF6Lh3/Cw9Q9cT556MQFiUEHSSjtYgmBnySEOJA==', '2026-10-16 18:15:11');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('51', 'uir6PjVOC5cWfPG4PrvOUiH2hMRNvTVxQZ5mRApnzWgR2wvCVg==', '2026-10-16 18:15:13');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('52', 'b9RuJ26BSFEgF78Jw/dx7/E826gdSuHf7cvJ7Cy+tjQPi7fGFA==', '2026-10-16 18:15:15');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('53', 'mLnbkSJpb1HdzUaMAXjIaMZZK2BCBpeClIJMWa2Ee+mblmzNTw==', '2026-10-16 18:15:19');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('54', 'HqLghUFZDwwQ3ou10Yq8rssNAMS6cjqJE6bEetvYoKb7K5RPcg==', '2026-10-16 18:15:22');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('55', 'qDyYmdJcmR9jX9F9Z/GQfn/WcBHEVEWAN86yfkbVeqNDwNz9Dg==', '2026-10-16 18:22:12');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('56', 'zZ5Z0H4GusN+iHWO+7XkC3q9u7UoMJgULkWIlFLRRblwbpgRoQ==', '2026-10-16 18:22:13');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('57', 'U9vP6fl3pFZwlIFv9FtAd40k/kIpmbpnUnUvr7PimbMLN5efOQ==', '2026-10-16 18:23:00');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('58', 'b2i1Vq5YEh+a2n7uIh4mEmaFEyGUeLdOSmtvZL4xIlhzTDryHA==', '2026-10-16 18:23:07');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('59', 'bZAAzyPy7g575lZvkhZS+UEk+ZBoofRXsOCLDfyVszk7jeWbpQ==', '2026-10-16 18:23:10');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('60', '8z/0QN2Xd7tZvOc6Icv98ZqVFjQ2NTqYrP9XgChpGyNXoPA36w==', '2026-10-16 18:28:22');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('61', '7mLfQwXlxZcHPT+j5/R5H9b9xiTTtjfJHwIut+IpWYNgoxKCww==', '2026-10-16 18:28:24');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('62', 'U/xPaemDRndYFEIPeMj7L4ZTRvS4VtLZK8U5Smiq/4hhDz8/dQ==', '2026-10-16 22:35:47');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('63', 'jRyGQoZIiyWxHiH5XRM9RYljeYZgiHvJyox9zDDNZzONzTMVKw==', '2026-10-16 22:35:47');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('64', 'Fr+o6XqcP2u1Kd5X78cR7l9E2zsNwvFJncHLscxCEm0in11oFw==', '2026-10-16 22:35:52');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('65', 'obDEzkT6sQAm3p5Gkd+t6HpveeDe7ZsztWyiQDy6vd1/NEGifA==', '2026-10-16 22:35:57');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('66', 'SCvGXqXVLCYsgJb6P+WCIJ1nakq9GDpxeZdCBWikC4ZAPovxvA==', '2026-10-16 22:36:00');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('67', 'MPzxsI5WGa8c2daWfuio7k9VIZ89/98I8LaxiVrJznThBaHawQ==', '2026-10-16 22:36:03');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('68', 'bIp9sUuTNrwBqp5y5448/yu/flBRjfi5LTfG+lR+ZCpvdy3+og==', '2026-10-17 13:51:13');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('69', 'NzgEvCTlT4Ge6rqoFlSZQUhe9p4zmyGvSUllBWXcd9CdliuQNg==', '2026-10-17 13:51:14');
INSERT INTO `total_visitors` (`id`, `session`, `time`) VALUES ('70', 'bGNETlYwc3hJRUtmQkIzVzk4SHBFQT09', '2026-10-17 14:34:01');

DROP TABLE IF EXISTS `type_actions`;
CREATE TABLE `type_actions` (
  `idta` int(11) NOT NULL AUTO_INCREMENT,
  `action` char(20) NOT NULL,
  PRIMARY KEY (`idta`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `type_actions` (`idta`, `action`) VALUES ('1', 'validation');
INSERT INTO `type_actions` (`idta`, `action`) VALUES ('2', 'verification');
INSERT INTO `type_actions` (`idta`, `action`) VALUES ('3', 'approval');
INSERT INTO `type_actions` (`idta`, `action`) VALUES ('4', 'validated');
INSERT INTO `type_actions` (`idta`, `action`) VALUES ('5', 'verified');
INSERT INTO `type_actions` (`idta`, `action`) VALUES ('6', 'approved');

DROP TABLE IF EXISTS `type_menu`;
CREATE TABLE `type_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_menu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `type_page`;
CREATE TABLE `type_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_page` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `idUser` char(128) NOT NULL,
  `usercode` char(128) NOT NULL,
  `username` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `usr_type` char(50) NOT NULL DEFAULT 'guest',
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `ip` char(50) NOT NULL,
  `signup_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email_verified` varchar(128) DEFAULT NULL,
  `document_verified` int(11) NOT NULL DEFAULT 0,
  `mobile_verified` int(11) NOT NULL DEFAULT 0,
  `mkpin` varchar(128) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idUser`) USING BTREE,
  UNIQUE KEY `ID_user` (`idUser`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE,
  UNIQUE KEY `usercode_U` (`usercode`),
  KEY `usercode` (`usercode`),
  CONSTRAINT `FK_users_uverify` FOREIGN KEY (`idUser`) REFERENCES `uverify` (`iduv`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users` (`idUser`, `usercode`, `username`, `email`, `password`, `usr_type`, `verified`, `status`, `ip`, `signup_time`, `email_verified`, `document_verified`, `mobile_verified`, `mkpin`, `created_at`, `updated_at`, `deleted_at`, `last_login`) VALUES ('8670c2c7ece94dc51e1639dd426b58b7495a95ab316fbe2d86eaf4fe14eb2693', 'z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', 'Y3lWZVE1d2RSTitUTHAvQTRoK3JnZz09', 'K0xKWGhGVnJJdUt5NG5HUDJKOS9iWk50SGtzOFRyekRYYThxRXFVMnU4Yz0=', 'SUNrbmJsdGlteUpkQWhYcjBNanIrbEhjR21ML2Vqd0NDWnJJSnp1WHdjbz0=', 'guest', '0', '0', '127.0.0.1', '2025-03-29 05:29:37', '51a5e3e5418a30a29896154f289727ebb09ddd54e5fdefcf8ccbe1b8d97b8c1f', '0', '0', 'TFFJL1QvaTNtRFQwWnByVzlNRTJqUT09', '2025-03-28 23:29:37', '2025-03-28 23:29:37', '2025-03-28 23:29:37', '2025-03-28 23:29:37');

DROP TABLE IF EXISTS `users_actions`;
CREATE TABLE `users_actions` (
  `usercode` char(128) NOT NULL,
  `action` char(20) NOT NULL,
  `validation` char(128) NOT NULL,
  `verification` char(128) DEFAULT NULL,
  `approval` char(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`usercode`),
  UNIQUE KEY `usercode` (`usercode`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_actions` (`usercode`, `action`, `validation`, `verification`, `approval`, `created_at`, `updated_at`) VALUES ('z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', 'validation', '51a5e3e5418a30a29896154f289727ebb09ddd54e5fdefcf8ccbe1b8d97b8c1f', NULL, NULL, '2025-03-28 23:29:37', '2025-03-28 23:29:37');

DROP TABLE IF EXISTS `users_active`;
CREATE TABLE `users_active` (
  `usercode` char(128) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `usercode_U` (`usercode`) USING BTREE,
  KEY `usercode` (`usercode`),
  CONSTRAINT `FK_users_active` FOREIGN KEY (`usercode`) REFERENCES `users_actions` (`usercode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_active` (`usercode`, `is_active`, `timestamp`) VALUES ('z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', '1', '2025-03-28 23:29:37');

DROP TABLE IF EXISTS `users_assigned_role`;
CREATE TABLE `users_assigned_role` (
  `usercode` char(128) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`usercode`),
  UNIQUE KEY `usercode` (`usercode`),
  KEY `FK_users_roles` (`role_id`),
  CONSTRAINT `FK_users_assigned_role` FOREIGN KEY (`usercode`) REFERENCES `users` (`usercode`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_users_roles` FOREIGN KEY (`role_id`) REFERENCES `users_roles` (`idRol`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_banned`;
CREATE TABLE `users_banned` (
  `usercode` char(128) NOT NULL,
  `username` char(128) NOT NULL DEFAULT '',
  `email` char(128) NOT NULL DEFAULT '',
  `banned_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `banned_hours` float NOT NULL,
  `hours_remaining` double DEFAULT NULL,
  UNIQUE KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_compare`;
CREATE TABLE `users_compare` (
  `usercode` char(128) NOT NULL,
  `ratings` int(11) DEFAULT 0,
  `likes` int(11) DEFAULT 0,
  `followers` int(11) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `recommendation` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 0,
  UNIQUE KEY `usercode_U` (`usercode`),
  KEY `usercode` (`usercode`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_contacts`;
CREATE TABLE `users_contacts` (
  `iduc` int(11) NOT NULL AUTO_INCREMENT,
  `usercode` char(128) NOT NULL,
  `contactcode` char(128) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`iduc`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_csp`;
CREATE TABLE `users_csp` (
  `idUser` char(128) NOT NULL,
  `usercode` char(128) NOT NULL,
  `username` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `usr_type` char(50) NOT NULL DEFAULT 'guest',
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `ip` char(30) NOT NULL,
  `signup_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email_verified` varchar(128) DEFAULT NULL,
  `document_verified` int(11) NOT NULL DEFAULT 0,
  `mobile_verified` int(11) NOT NULL DEFAULT 0,
  `mkpin` varchar(128) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idUser`) USING BTREE,
  UNIQUE KEY `ID_user` (`idUser`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE,
  UNIQUE KEY `usercode_U` (`usercode`) USING BTREE,
  KEY `usercode` (`usercode`) USING BTREE,
  CONSTRAINT `FK_cspusers_uverify` FOREIGN KEY (`idUser`) REFERENCES `uverify` (`iduv`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_deleted`;
CREATE TABLE `users_deleted` (
  `usercode` char(128) NOT NULL,
  `username` varchar(65) NOT NULL,
  `password` varchar(65) NOT NULL,
  `email` varchar(65) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 0,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `banned` tinyint(1) NOT NULL DEFAULT 0,
  `mod_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_followers`;
CREATE TABLE `users_followers` (
  `iduf` int(11) NOT NULL AUTO_INCREMENT,
  `usercode` char(128) NOT NULL,
  `fusercode` char(128) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`iduf`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_forgot_pass`;
CREATE TABLE `users_forgot_pass` (
  `username` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `mkhash` varchar(128) NOT NULL,
  `password_key` varchar(128) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expired_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_forgot_pin`;
CREATE TABLE `users_forgot_pin` (
  `username` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `mkhash` varchar(128) DEFAULT NULL,
  `pin_key` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `expired_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE `users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_level` (`group_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_info`;
CREATE TABLE `users_info` (
  `userid` char(128) NOT NULL,
  `usercode` char(128) NOT NULL,
  `firstname` varchar(60) DEFAULT NULL,
  `lastname` varchar(60) DEFAULT NULL,
  `gender` enum('Woman','Male','With doubt') DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `country` varchar(45) DEFAULT 'Perú',
  `state` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `address1` varchar(45) DEFAULT NULL,
  `address2` varchar(45) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `userid_UNIQUE` (`userid`),
  UNIQUE KEY `usercode_U` (`usercode`),
  KEY `fk_userids_idx` (`userid`),
  KEY `usercode` (`usercode`),
  CONSTRAINT `FK_users_info_uverify` FOREIGN KEY (`userid`) REFERENCES `uverify` (`iduv`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_info` (`userid`, `usercode`, `firstname`, `lastname`, `gender`, `age`, `birthday`, `phone`, `country`, `state`, `city`, `address1`, `address2`, `is_active`, `created_at`, `updated_at`) VALUES ('8670c2c7ece94dc51e1639dd426b58b7495a95ab316fbe2d86eaf4fe14eb2693', 'z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', 'Jose', 'Mantilla', NULL, NULL, NULL, NULL, 'Perú', NULL, NULL, NULL, NULL, '0', '2025-03-28 23:29:37', '2025-03-28 23:29:37');

DROP TABLE IF EXISTS `users_jail`;
CREATE TABLE `users_jail` (
  `user_id` char(128) NOT NULL,
  `usercode` char(128) NOT NULL,
  `banned_hours` float NOT NULL DEFAULT 24,
  `reason` varchar(2000) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  KEY `fk_userid_idx` (`user_id`),
  CONSTRAINT `FK_user_jail` FOREIGN KEY (`user_id`) REFERENCES `users_csp` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_likes`;
CREATE TABLE `users_likes` (
  `idul` int(11) NOT NULL AUTO_INCREMENT,
  `usercode` char(128) NOT NULL,
  `lusercode` char(128) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idul`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_locations`;
CREATE TABLE `users_locations` (
  `usercode` char(128) NOT NULL,
  `user_type` tinyint(4) NOT NULL DEFAULT 0,
  `name_location_1` char(50) DEFAULT NULL,
  `address_1` varchar(300) DEFAULT NULL,
  `name_location_2` char(50) DEFAULT NULL,
  `address_2` varchar(300) DEFAULT NULL,
  `name_location_3` char(50) DEFAULT NULL,
  `address_3` varchar(300) DEFAULT NULL,
  `name_location_4` char(50) DEFAULT NULL,
  `address_4` varchar(300) DEFAULT NULL,
  `name_location_5` char(50) DEFAULT NULL,
  `address_5` varchar(300) DEFAULT NULL,
  UNIQUE KEY `usercode_U` (`usercode`),
  KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_logs`;
CREATE TABLE `users_logs` (
  `idlogs` int(11) NOT NULL,
  `usercode` char(128) NOT NULL,
  `logs` char(128) NOT NULL,
  `ip` char(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idlogs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_notes`;
CREATE TABLE `users_notes` (
  `usercode` char(128) NOT NULL,
  `userprov` char(128) NOT NULL,
  `notes` varchar(1000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `usercode_U` (`usercode`),
  KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_permissions`;
CREATE TABLE `users_permissions` (
  `idPer` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'General',
  `required` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idPer`) USING BTREE,
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `id_UNIQUE` (`idPer`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('1', 'Verify Users', 'Administration permission allowing for the verification of new users', 'Users', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('2', 'Delete Unverified Users', 'Administration permission allowing the deletion of unverified users', 'Users', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('3', 'Ban Users', 'Moderation permission allowing the banning of users', 'Users', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('4', 'Assign Roles to Users', 'Administration permission allowing the assignment of roles to users', 'Users', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('5', 'Assign Users to Roles', 'Administration permission allowing the assignment of users to roles', 'Roles', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('6', 'Create Roles', 'Administration permission allowing for the creation of new roles', 'Roles', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('7', 'Delete Roles', 'Administration permission allowing for the deletion of roles', 'Roles', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('8', 'Create Permissions', 'Administration permission allowing for the creation of new permissions', 'Permissions', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('9', 'Delete Permissions', 'Administration permission allowing for the deletion of permissions', 'Permissions', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('10', 'Assign Permissions to Roles', 'Administration permission allowing the assignment of permissions to roles', 'Roles', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('11', 'Edit Site Config', 'Administration permission allowing the editing of core site configuration (dangerous)', 'Administration', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('12', 'View Permissions', 'Administration permission allowing the viewing of all permissions', 'Permissions', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('13', 'View Roles', 'Administration permission allowing for the viewing of all roles', 'Roles', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('14', 'View Users', 'Administration permission allowing for the viewing of all users', 'Users', '1');
INSERT INTO `users_permissions` (`idPer`, `name`, `description`, `category`, `required`) VALUES ('15', 'Delete Users', 'Administration permission allowing for the deletion of users', 'Users', '1');

DROP TABLE IF EXISTS `users_plans`;
CREATE TABLE `users_plans` (
  `usercode` char(128) NOT NULL,
  `verification` tinyint(1) DEFAULT 0,
  `upgrades` tinyint(1) DEFAULT 0,
  `type_plan` char(20) DEFAULT 'Cliente',
  `days_plan` tinyint(4) DEFAULT 7,
  `required` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `usercode_U` (`usercode`),
  KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_plans` (`usercode`, `verification`, `upgrades`, `type_plan`, `days_plan`, `required`, `created_at`, `updated_at`) VALUES ('z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', '0', '0', 'Cliente', '7', '0', '2025-03-28 23:29:37', '2025-03-28 23:29:37');

DROP TABLE IF EXISTS `users_privacy`;
CREATE TABLE `users_privacy` (
  `idUsr` char(128) NOT NULL,
  `usercode` char(128) NOT NULL,
  `verification` char(128) DEFAULT NULL,
  `nationality` char(50) DEFAULT NULL,
  `type_document` enum('DNI','Carnet Extranjeria','Pasaporte') DEFAULT NULL,
  `number_document` varchar(12) DEFAULT NULL,
  `name_company` varchar(50) DEFAULT NULL,
  `ruc_number` varchar(11) DEFAULT NULL,
  `logo_image` varchar(150) DEFAULT NULL,
  `required` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idUsr`),
  UNIQUE KEY `usercode_U` (`usercode`) USING BTREE,
  UNIQUE KEY `id_Pri` (`idUsr`) USING BTREE,
  KEY `usercode` (`usercode`),
  CONSTRAINT `FK_privacy_uverify` FOREIGN KEY (`idUsr`) REFERENCES `uverify` (`iduv`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_privacy` (`idUsr`, `usercode`, `verification`, `nationality`, `type_document`, `number_document`, `name_company`, `ruc_number`, `logo_image`, `required`, `created_at`, `updated_at`) VALUES ('8670c2c7ece94dc51e1639dd426b58b7495a95ab316fbe2d86eaf4fe14eb2693', 'z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '2025-03-28 23:29:37', '2025-03-28 23:29:37');

DROP TABLE IF EXISTS `users_profiles`;
CREATE TABLE `users_profiles` (
  `idp` char(128) NOT NULL,
  `usercode` char(128) NOT NULL,
  `usrtypes` char(128) DEFAULT NULL,
  `mkhash` varchar(256) NOT NULL DEFAULT '',
  `avatar` varchar(250) DEFAULT NULL,
  `profile_image` varchar(250) DEFAULT NULL,
  `profile_cover` varchar(250) DEFAULT NULL,
  `public_phone` char(20) DEFAULT NULL,
  `public_email` varchar(128) DEFAULT NULL,
  `social_media` enum('Yes','No') DEFAULT 'Yes',
  `profession` varchar(128) DEFAULT NULL,
  `occupation` varchar(128) DEFAULT NULL,
  `profile_bio` varchar(500) DEFAULT NULL,
  `language` varchar(128) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `banned` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idp`) USING BTREE,
  UNIQUE KEY `usercode_U` (`usercode`),
  UNIQUE KEY `id_Pro` (`idp`) USING BTREE,
  UNIQUE KEY `phone` (`public_phone`) USING BTREE,
  UNIQUE KEY `public_email` (`public_email`),
  KEY `usercode` (`usercode`),
  CONSTRAINT `FK_profiles_uverify` FOREIGN KEY (`idp`) REFERENCES `uverify` (`iduv`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_profiles` (`idp`, `usercode`, `usrtypes`, `mkhash`, `avatar`, `profile_image`, `profile_cover`, `public_phone`, `public_email`, `social_media`, `profession`, `occupation`, `profile_bio`, `language`, `is_active`, `banned`, `created_at`, `updated_at`) VALUES ('8670c2c7ece94dc51e1639dd426b58b7495a95ab316fbe2d86eaf4fe14eb2693', 'z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', NULL, 'faa711e07cf488eb35a9af2de4a593b5e8b8cf079829a9c193453d3862b7c6ce', NULL, NULL, NULL, NULL, NULL, 'Yes', NULL, NULL, NULL, NULL, '0', '1', '2025-03-28 23:29:37', '2026-10-17 12:34:01');

DROP TABLE IF EXISTS `users_rating`;
CREATE TABLE `users_rating` (
  `usercode` char(128) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `star_1` int(11) NOT NULL DEFAULT 0,
  `star_2` int(11) NOT NULL DEFAULT 0,
  `star_3` int(11) NOT NULL DEFAULT 0,
  `star_4` int(11) NOT NULL DEFAULT 0,
  `star_5` int(11) NOT NULL DEFAULT 0,
  UNIQUE KEY `usercode_U` (`usercode`) USING BTREE,
  KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_role_permissions`;
CREATE TABLE `users_role_permissions` (
  `idRp` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`idRp`) USING BTREE,
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `FK_urs_permission` FOREIGN KEY (`permission_id`) REFERENCES `users_permissions` (`idPer`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_usr_role` FOREIGN KEY (`role_id`) REFERENCES `users_roles` (`idRol`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('1', '1', '1');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('2', '1', '2');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('3', '1', '3');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('4', '1', '4');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('5', '1', '5');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('6', '1', '6');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('7', '1', '7');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('8', '1', '8');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('9', '1', '9');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('10', '1', '10');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('11', '1', '11');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('12', '1', '12');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('13', '1', '13');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('14', '1', '14');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('15', '1', '15');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('16', '2', '1');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('17', '2', '2');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('18', '2', '3');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('19', '2', '4');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('20', '2', '5');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('21', '2', '12');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('22', '2', '13');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('23', '2', '14');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('24', '2', '15');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('25', '3', '12');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('27', '3', '13');
INSERT INTO `users_role_permissions` (`idRp`, `role_id`, `permission_id`) VALUES ('28', '3', '14');

DROP TABLE IF EXISTS `users_roles`;
CREATE TABLE `users_roles` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `default_role` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`idRol`) USING BTREE,
  UNIQUE KEY `name_UNIQUE` (`name`) USING BTREE,
  UNIQUE KEY `default_role_UNIQUE` (`default_role`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_roles` (`idRol`, `name`, `description`, `required`, `default_role`) VALUES ('1', 'Super Admin', 'Master administrator of site', '1', '9');
INSERT INTO `users_roles` (`idRol`, `name`, `description`, `required`, `default_role`) VALUES ('2', 'Admin', 'Site administrator', '1', '5');
INSERT INTO `users_roles` (`idRol`, `name`, `description`, `required`, `default_role`) VALUES ('3', 'Manager', 'Manager content', '1', '3');
INSERT INTO `users_roles` (`idRol`, `name`, `description`, `required`, `default_role`) VALUES ('4', 'Stantard User', 'Default site role for standard users', '1', '1');
INSERT INTO `users_roles` (`idRol`, `name`, `description`, `required`, `default_role`) VALUES ('5', 'Guest', 'Guest visit', '0', '0');

DROP TABLE IF EXISTS `users_searches`;
CREATE TABLE `users_searches` (
  `usercode` char(128) NOT NULL,
  `searches` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `usercode_U` (`usercode`),
  KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_searches` (`usercode`, `searches`, `created_at`, `updated_at`) VALUES ('z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', NULL, '2025-03-28 23:29:37', '2025-03-28 23:29:37');

DROP TABLE IF EXISTS `users_secures`;
CREATE TABLE `users_secures` (
  `idUsr` char(128) NOT NULL,
  `usercode` char(128) NOT NULL,
  `type_user` char(20) DEFAULT NULL,
  `type_plan` int(11) DEFAULT NULL,
  `type_document` enum('DNI','Carnet Extranjeria','Pasaporte') DEFAULT NULL,
  `id_document` char(12) DEFAULT NULL,
  `name_company` char(50) DEFAULT NULL,
  `ruc_number` char(11) DEFAULT NULL,
  `business_sector` char(30) DEFAULT NULL,
  `phone` char(15) DEFAULT NULL,
  `mobile_phone` char(15) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `location` varchar(250) DEFAULT NULL,
  `folder_files` char(64) DEFAULT NULL,
  `validation` char(128) DEFAULT NULL,
  `activation_code` char(128) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `required` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idUsr`),
  UNIQUE KEY `usercode_U` (`usercode`) USING BTREE,
  UNIQUE KEY `id_Sec` (`idUsr`) USING BTREE,
  UNIQUE KEY `id_document` (`id_document`),
  UNIQUE KEY `ruc` (`ruc_number`) USING BTREE,
  KEY `usercode` (`usercode`),
  CONSTRAINT `FK_secures_uverify` FOREIGN KEY (`idUsr`) REFERENCES `uverify` (`iduv`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_secures` (`idUsr`, `usercode`, `type_user`, `type_plan`, `type_document`, `id_document`, `name_company`, `ruc_number`, `business_sector`, `phone`, `mobile_phone`, `address`, `location`, `folder_files`, `validation`, `activation_code`, `is_active`, `required`, `created_at`, `updated_at`) VALUES ('8670c2c7ece94dc51e1639dd426b58b7495a95ab316fbe2d86eaf4fe14eb2693', 'z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '2025-03-28 23:29:37', '2025-03-28 23:29:37');

DROP TABLE IF EXISTS `users_settings`;
CREATE TABLE `users_settings` (
  `idUsr` int(11) NOT NULL,
  `usercode` char(128) DEFAULT NULL,
  `level` char(50) DEFAULT NULL,
  `activity` char(50) DEFAULT NULL,
  `record` char(50) DEFAULT NULL,
  `logs` char(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_social_media`;
CREATE TABLE `users_social_media` (
  `usercode` char(128) NOT NULL,
  `website` char(150) DEFAULT NULL,
  `whatsapp` char(100) DEFAULT NULL,
  `instagram` char(100) DEFAULT NULL,
  `facebook` char(100) DEFAULT NULL,
  `telegram` char(100) DEFAULT NULL,
  `youtube` char(100) DEFAULT NULL,
  `twitter` char(100) DEFAULT NULL,
  `tiktok` char(100) DEFAULT NULL,
  `tumblr` char(100) DEFAULT NULL,
  `linkedin` char(100) DEFAULT NULL,
  UNIQUE KEY `usercode_U` (`usercode`),
  KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_social_media` (`usercode`, `website`, `whatsapp`, `instagram`, `facebook`, `telegram`, `youtube`, `twitter`, `tiktok`, `tumblr`, `linkedin`) VALUES ('z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `users_to_group`;
CREATE TABLE `users_to_group` (
  `user_admin_id` int(11) NOT NULL,
  `user_groups_id` int(11) NOT NULL,
  PRIMARY KEY (`user_admin_id`,`user_groups_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `users_types`;
CREATE TABLE `users_types` (
  `usercode` char(128) DEFAULT NULL,
  `user_type` char(128) DEFAULT NULL,
  `val_user` tinyint(1) DEFAULT 1,
  UNIQUE KEY `usercode_U` (`usercode`) USING BTREE,
  KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_types` (`usercode`, `user_type`, `val_user`) VALUES ('z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', NULL, '1');

DROP TABLE IF EXISTS `users_verifications`;
CREATE TABLE `users_verifications` (
  `usercode` char(128) NOT NULL,
  `verification` char(128) DEFAULT NULL,
  `is_verify` tinyint(1) DEFAULT 0,
  `portraid_date` varchar(150) DEFAULT NULL,
  `image_document_front` varchar(150) DEFAULT NULL,
  `image_document_back` varchar(150) DEFAULT NULL,
  `payment_document` varchar(150) DEFAULT NULL,
  UNIQUE KEY `usercode_U` (`usercode`),
  KEY `usercode` (`usercode`),
  CONSTRAINT `FK_users_verification` FOREIGN KEY (`usercode`) REFERENCES `users_actions` (`usercode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `users_verifications` (`usercode`, `verification`, `is_verify`, `portraid_date`, `image_document_front`, `image_document_back`, `payment_document`) VALUES ('z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', NULL, '0', NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `uverify`;
CREATE TABLE `uverify` (
  `iduv` char(128) NOT NULL,
  `usercode` char(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `usr_type` char(128) NOT NULL DEFAULT '',
  `mktoken` varchar(256) NOT NULL,
  `mkkey` varchar(256) NOT NULL,
  `mkhash` varchar(256) NOT NULL,
  `mkpin` varchar(128) NOT NULL,
  `level` char(50) NOT NULL DEFAULT 'Guest',
  `level_key` char(128) NOT NULL,
  `recovery_phrase` varchar(1024) DEFAULT NULL,
  `activation_code` varchar(128) DEFAULT NULL,
  `password_key` varchar(256) DEFAULT NULL,
  `pin_key` varchar(256) DEFAULT NULL,
  `rp_active` tinyint(1) NOT NULL DEFAULT 0,
  `is_activate` tinyint(1) NOT NULL DEFAULT 0,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_banned` tinyint(1) NOT NULL DEFAULT 1,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`iduv`) USING BTREE,
  UNIQUE KEY `iduv` (`iduv`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE,
  UNIQUE KEY `usercode` (`usercode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `uverify` (`iduv`, `usercode`, `username`, `email`, `password`, `usr_type`, `mktoken`, `mkkey`, `mkhash`, `mkpin`, `level`, `level_key`, `recovery_phrase`, `activation_code`, `password_key`, `pin_key`, `rp_active`, `is_activate`, `is_verified`, `is_banned`, `timestamp`) VALUES ('8670c2c7ece94dc51e1639dd426b58b7495a95ab316fbe2d86eaf4fe14eb2693', 'z32p@wsH5OG7IFBhl1B6j7u4%R%UCYm2LZByyK1j9c&11}hFlxs}SJ0573833557', 'QURJYXVuWFdXU3NmeFZYWVRWQkVpQT09', 'b1FTN3FEUkdremNoV3YraUtmM3pBZ2drUkhyZUJhQWFCS2VQZjBBMjQ2Yz0=', 'SUNrbmJsdGlteUpkQWhYcjBNanIrbEhjR21ML2Vqd0NDWnJJSnp1WHdjbz0=', '', 'EXeRyaFpzoF8afPwb7UZ2bcr7YUO1Bcdp4Pu1Z19l3Zq663WyZFoisixBn5Pf1l6', '4kcGoEjeW1XBeIeBJ70ApasJJRzDKOVnPOVBBJOcZcVj8EZ9QdKn19ut7zLQesef', 'faa711e07cf488eb35a9af2de4a593b5e8b8cf079829a9c193453d3862b7c6ce', 'TFFJL1QvaTNtRFQwWnByVzlNRTJqUT09', 'Super Admin', 'NRmtGSK1fSOyhe%Vpf%rsapKVnTm|NC[V0Co7w4w]TCHk$9KYm3DCGzhliHVl[og', NULL, NULL, NULL, NULL, '0', '1', '1', '0', '2026-10-17 12:34:01');

DROP TABLE IF EXISTS `view_log`;
CREATE TABLE `view_log` (
  `idVl` int(11) NOT NULL,
  `usercode` char(128) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `stars` int(11) DEFAULT NULL,
  `applause` int(11) DEFAULT NULL,
  `acceptance` int(11) DEFAULT NULL,
  `like` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `visit_logs`;
CREATE TABLE `visit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `path` varchar(500) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `visited_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ip` (`ip_address`),
  KEY `idx_visited` (`visited_at`),
  KEY `idx_country` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `visitor`;
CREATE TABLE `visitor` (
  `ip` varchar(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-03-27 03:00:56', '2025-03-27 09:03:35');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-03-28 21:18:27', '2025-03-29 21:00:18');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('::1', '2025-03-28 23:38:26', '2025-03-29 20:40:09');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-03-31 22:32:26', '2025-04-01 05:32:28');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-04-02 22:29:02', '2025-04-04 07:01:42');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-04-05 00:30:39', '2025-04-05 08:20:49');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('::1', '2025-04-05 01:37:46', '2025-04-07 05:37:49');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-04-06 02:55:09', '2025-04-06 22:51:51');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-04-07 20:17:47', '2025-04-08 06:23:32');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('::1', '2025-04-07 23:22:44', '2025-04-08 09:00:08');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-04-09 20:59:09', '2025-04-10 03:59:18');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-04-29 22:24:47', '2025-04-30 07:01:26');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-05-25 21:28:29', '2025-05-26 04:37:44');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-05-30 01:31:12', '2025-06-03 05:00:27');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-06-03 22:46:23', '2025-06-07 21:25:12');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-06-08 21:55:59', '2025-06-14 15:55:24');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-07-08 22:20:59', '2025-07-08 22:20:59');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-07-16 19:13:07', '2025-07-17 02:14:04');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-07-18 10:29:39', '2025-07-20 02:24:33');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-07-20 22:38:18', '2025-07-22 03:10:00');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-08-09 15:49:20', '2025-08-10 05:48:30');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-08-11 20:46:24', '2025-08-14 21:43:08');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-08-16 13:10:40', '2025-08-17 02:55:49');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-08-18 21:15:20', '2025-08-19 06:28:46');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-08-21 11:57:15', '2025-08-23 07:51:38');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('::1', '2025-08-22 19:44:14', '2025-08-23 02:44:50');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('::1', '2025-08-24 22:11:36', '2025-08-26 05:08:02');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-08-26 12:34:05', '2025-08-26 19:34:15');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-09-17 21:56:50', '2025-09-18 07:07:34');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2025-12-08 22:22:19', '2025-12-09 04:22:20');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2026-01-02 01:13:39', '2026-01-03 02:02:23');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2026-01-08 11:56:34', '2026-01-08 23:25:19');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2026-09-02 18:36:04', '2026-09-03 16:29:06');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2026-09-07 15:08:20', '2026-09-08 01:57:06');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2026-09-15 18:55:06', '2026-09-16 00:03:23');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('127.0.0.1', '2026-10-16 14:22:28', '2026-10-17 14:30:13');
INSERT INTO `visitor` (`ip`, `timestamp`, `updated_at`) VALUES ('::1', '2026-10-17 12:18:44', '2026-10-17 14:34:01');

DROP TABLE IF EXISTS `visitor_productos`;
CREATE TABLE `visitor_productos` (
  `visitsprId` int(11) NOT NULL AUTO_INCREMENT,
  `ip` char(20) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`visitsprId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


DROP TABLE IF EXISTS `visitor_servicios`;
CREATE TABLE `visitor_servicios` (
  `visitsprId` int(11) NOT NULL AUTO_INCREMENT,
  `ip` char(20) DEFAULT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`visitsprId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;


SET FOREIGN_KEY_CHECKS=1;
