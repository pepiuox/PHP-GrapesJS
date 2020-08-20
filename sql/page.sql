-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.13-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.0.0.6085
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando estructura para tabla demosite.config
DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `type_name` varchar(20) DEFAULT NULL,
  `value` varchar(250) DEFAULT NULL,
  KEY `type_name` (`type_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla demosite.config: ~19 rows (aproximadamente)
DELETE FROM `config`;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` (`type_name`, `value`) VALUES
	('DOMAIN_SITE', 'www.yourdomain.com'),
	('SITE_NAME', 'Your site'),
	('SITE_DESC', 'Your keywords for your domains'),
	('SITE_KEYWORDS', 'Your keywords for your domains'),
	('SITE_DESCRIPTION', 'Your keywords for your domains'),
	('SITE_ADMIN', 'system'),
	('SITE_CONFIG', 'config'),
	('SITE_LIST', 'list'),
	('SITE_EDITOR', 'editor'),
	('SITE_BUILDER', 'builder'),
	('SITE_LANGUAGE_1', 'English'),
	('SITE_LANGUAGE_2', 'EspaÃ±ol'),
	('SITE_EMAIL', 'info@yourdomain.com'),
	('IMG_PAGE', 'http://yourdomain.com/uploads/image-page.jpg'),
	('NAME_CONTACT', 'Your Name'),
	('PHONE_CONTACT', '0051 999888777'),
	('EMAIL_CONTACT', 'info@yourdomain.com'),
	('FOLDER_IMAGES', 'uploads'),
	('SITE_CREATOR', '@pepiuox'),
	('TWITTER', '@yourdomain'),
	('FACEBOOKID', 'yourfacebookid'),
	('SKYPE', 'Your Domain'),
	('TELEGRAM', 'Your Name'),
	('WHATSAPP', '+51 999888777'),
	('ADMIN_NAME', 'admin'),
	('ADMIN_LEVEL', NULL);
/*!40000 ALTER TABLE `config` ENABLE KEYS */;

-- Volcando estructura para tabla demosite.menu
DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `idMenu` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `title_page` varchar(100) DEFAULT NULL,
  `link_page` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT 0,
  PRIMARY KEY (`idMenu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla demosite.menu: ~0 rows (aproximadamente)
DELETE FROM `menu`;


-- Volcando estructura para tabla demosite.page
DROP TABLE IF EXISTS `page`;
CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `template` varchar(150) DEFAULT NULL,
  `base_template` varchar(150) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `style` longtext DEFAULT NULL,
  `starpage` int(11) DEFAULT 0,
  `level` int(11) DEFAULT 1,
  `parent` int(11) DEFAULT 0,
  `sort` int(11) DEFAULT 0,
  `active` int(11) DEFAULT 1,
  `update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla demosite.page: ~0 rows (aproximadamente)
DELETE FROM `page`;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
