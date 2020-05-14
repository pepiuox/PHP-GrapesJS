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

