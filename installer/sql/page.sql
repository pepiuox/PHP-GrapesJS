-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.21-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6369
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

-- Dumping structure for table newcms.active_sessions
DROP TABLE IF EXISTS `active_sessions`;
CREATE TABLE IF NOT EXISTS `active_sessions` (
  `session` char(64) COLLATE utf8_bin DEFAULT NULL,
  `date_session` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table newcms.active_sessions: ~0 rows (approximately)

-- Dumping structure for table newcms.active_users
DROP TABLE IF EXISTS `active_users`;
CREATE TABLE IF NOT EXISTS `active_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.active_users: ~0 rows (approximately)

-- Dumping structure for table newcms.admins
DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `adminid` char(23) NOT NULL DEFAULT 'uuid_short();',
  `userid` char(128) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `superadmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.admins: ~0 rows (approximately)

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

-- Dumping structure for table newcms.banned_users
DROP TABLE IF EXISTS `banned_users`;
CREATE TABLE IF NOT EXISTS `banned_users` (
  `user_id` char(128) NOT NULL,
  `banned_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `banned_hours` float NOT NULL,
  `hours_remaining` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.banned_users: ~0 rows (approximately)

-- Dumping structure for table newcms.configuration
DROP TABLE IF EXISTS `configuration`;
CREATE TABLE IF NOT EXISTS `configuration` (
  `config_name` varchar(20) DEFAULT NULL,
  `config_value` varchar(250) DEFAULT NULL,
  UNIQUE KEY `type_name` (`config_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.configuration: ~30 rows (approximately)
INSERT INTO `configuration` (`config_name`, `config_value`) VALUES
	('DOMAIN_SITE', 'http://www.yourdomain.com'),
	('SITE_NAME', 'PHP GrapesJS'),
	('SITE_DESCRIPTION', 'Your description for your domains'),
	('SITE_KEYWORDS', 'Your keywords for your domains'),
	('SITE_CLASSIFICATION', 'Your classification for your domains'),
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

-- Dumping structure for table newcms.ip
DROP TABLE IF EXISTS `ip`;
CREATE TABLE IF NOT EXISTS `ip` (
  `id_session` char(128) DEFAULT NULL,
  `user_data` char(64) NOT NULL,
  `address` char(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.ip: ~4 rows (approximately)
INSERT INTO `ip` (`id_session`, `user_data`, `address`, `timestamp`) VALUES
	('0592ad6e0a352cd36b91970f6a7a9dc98d45485d', 'pepiuox@contact.net', '127.0.0.1', '2021-09-14 05:29:43'),
	('22a0fbc2d9a8667bea2a38d69a6e0f41cff9a798', 'contatct@pepiuox.net', '127.0.0.1', '2021-09-16 04:21:49'),
	('b1ed8551a80fa6c03457119e068b536f4d92b271', 'contact@ppiuox.net', '127.0.0.1', '2021-10-22 05:10:53'),
	('b1ed8551a80fa6c03457119e068b536f4d92b271', 'pepiuox@pepiuox.net', '127.0.0.1', '2021-10-22 05:11:27');

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
INSERT INTO `profiles` (`idp`, `mkhash`, `firstname`, `lastname`, `gender`, `age`, `avatar`, `birthday`, `phone`, `website`, `social_media`, `profession`, `occupation`, `public_email`, `address`, `followers_count`, `profile_image`, `profile_cover`, `profile_bio`, `language`, `active`, `banned`, `date`, `update`) VALUES
	('1095616718612d749c68bc3', '1b1fe70518efa4692018bd268bc86673fcbff952', 'Jose', 'Mantilla', 'Male', 46, '', '0000-00-00', '', '', '', '', '', '', '', 0, '', '', '', '', 0, 0, '2021-08-31 00:15:24', '2021-11-11 16:58:18');

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
INSERT INTO `users` (`idUser`, `username`, `email`, `password`, `verified`, `status`, `ip`, `signup_time`, `email_verified`, `document_verified`, `mobile_verified`, `mkpin`, `create_user`, `update_user`) VALUES
	('1095616718612d749c68bc3', 'Qnc5RllYMi9QendaSEQraGIweHlXdz09', 'TGRSOUdDM3o1N2hhaUJGRFJoaEltdmFXTExKTlkrK1VxaHVQUGVoSkJ4dz0=', 'cVR2T2YrY2JVQnExdnpLYlcvOTV4dz09', 1, 0, '127.0.0.1', '2021-08-31 00:15:24', '', 0, 0, '550044', '2021-08-31 00:15:24', '2021-08-31 00:15:24');

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

-- Dumping data for table newcms.users_roles: ~5 rows (approximately)
INSERT INTO `users_roles` (`idRol`, `name`, `description`, `required`, `default_role`) VALUES
	(1, 'Super Admin', 'Master administrator of site', 1, 9),
	(2, 'Admin', 'Site administrator', 1, 5),
	(3, 'Manager', 'Manager content', 1, 3),
	(4, 'Stantard User', 'Default site role for standard users', 1, 1),
	(5, 'Guest', 'Guest visit', 0, 0);

-- Dumping structure for table newcms.users_shop
DROP TABLE IF EXISTS `users_shop`;
CREATE TABLE IF NOT EXISTS `users_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(65) NOT NULL,
  `password1` varchar(255) NOT NULL,
  `password2` varchar(255) NOT NULL,
  `firstname` char(60) NOT NULL,
  `lastname` char(60) NOT NULL,
  `who` char(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.users_shop: ~0 rows (approximately)

-- Dumping structure for table newcms.users_sys
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table newcms.users_sys: ~0 rows (approximately)

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
INSERT INTO `uverify` (`iduv`, `username`, `email`, `password`, `mktoken`, `mkkey`, `mkhash`, `mkpin`, `level`, `recovery_phrase`, `activation_code`, `password_key`, `pin_key`, `rp_active`, `is_activated`, `verified`, `banned`, `timestamp`) VALUES
	('1095616718612d749c68bc3', 'pepiuox', 'contact@pepiuox.net', 'cVR2T2YrY2JVQnExdnpLYlcvOTV4dz09', '25cce270791d66425793377bf424ee92794e2b0c', '9eda604eafd869312131d4a7f8199c53ef5c80f3', '1b1fe70518efa4692018bd268bc86673fcbff952', '550044', 'Super Admin', '', '', '', '', 0, 1, 1, 0, '2021-11-11 16:58:18');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
