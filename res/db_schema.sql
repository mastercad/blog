-- --------------------------------------------------------
-- Host:                         ultadmin.dyn.unister.lan
-- Server Version:               5.5.50-0+deb8u1 - (Debian)
-- Server Betriebssystem:        debian-linux-gnu
-- HeidiSQL Version:             9.1.0.4941
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Exportiere Struktur von Tabelle symfony_blog.city
DROP TABLE IF EXISTS `city`;
CREATE TABLE IF NOT EXISTS `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle symfony_blog.city: ~0 rows (ungefähr)
DELETE FROM `city`;
/*!40000 ALTER TABLE `city` DISABLE KEYS */;
/*!40000 ALTER TABLE `city` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.comment
DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `coment` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `site_id` int(10) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_user_idx` (`user_id`),
  KEY `comment_site_idx` (`site_id`),
  CONSTRAINT `comment_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `comment_site` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportiere Daten aus Tabelle symfony_blog.comment: ~0 rows (ungefähr)
DELETE FROM `comment`;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.keyword
DROP TABLE IF EXISTS `keyword`;
CREATE TABLE IF NOT EXISTS `keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(25) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`key`),
  KEY `KEY` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportiere Daten aus Tabelle symfony_blog.keyword: ~0 rows (ungefähr)
DELETE FROM `keyword`;
/*!40000 ALTER TABLE `keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `keyword` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.project
DROP TABLE IF EXISTS `project`;
CREATE TABLE IF NOT EXISTS `project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle symfony_blog.project: ~0 rows (ungefähr)
DELETE FROM `project`;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
/*!40000 ALTER TABLE `project` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.project_x_user
DROP TABLE IF EXISTS `project_x_user`;
CREATE TABLE IF NOT EXISTS `project_x_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `userGroupProject_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_id_UNIQUE` (`project_id`,`user_id`,`userGroupProject_id`),
  KEY `project_x_user_project_idx` (`project_id`),
  KEY `project_x_user_user_idx` (`user_id`),
  KEY `fk_project_x_user_userGroupProject1_idx` (`userGroupProject_id`),
  CONSTRAINT `project_x_user_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `project_x_user_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_project_x_user_userGroupProject1` FOREIGN KEY (`userGroupProject_id`) REFERENCES `userGroupProject` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle symfony_blog.project_x_user: ~0 rows (ungefähr)
DELETE FROM `project_x_user`;
/*!40000 ALTER TABLE `project_x_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_x_user` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.Route
DROP TABLE IF EXISTS `Route`;
CREATE TABLE IF NOT EXISTS `Route` (
  `id` int(11) NOT NULL,
  `action` varchar(255) DEFAULT NULL,
  `resource` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle symfony_blog.Route: ~0 rows (ungefähr)
DELETE FROM `Route`;
/*!40000 ALTER TABLE `Route` DISABLE KEYS */;
/*!40000 ALTER TABLE `Route` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.site
DROP TABLE IF EXISTS `site`;
CREATE TABLE IF NOT EXISTS `site` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `title` text NOT NULL,
  `visibility` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 = Project, 2 = App, 3 = Alle',
  `createUser_id` int(10) unsigned NOT NULL,
  `updateUser_id` int(10) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_site_user1_idx` (`createUser_id`),
  KEY `fk_site_user2_idx` (`updateUser_id`),
  CONSTRAINT `fk_site_user1` FOREIGN KEY (`createUser_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_site_user2` FOREIGN KEY (`updateUser_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportiere Daten aus Tabelle symfony_blog.site: ~0 rows (ungefähr)
DELETE FROM `site`;
/*!40000 ALTER TABLE `site` DISABLE KEYS */;
/*!40000 ALTER TABLE `site` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.siteHistory
DROP TABLE IF EXISTS `siteHistory`;
CREATE TABLE IF NOT EXISTS `siteHistory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `title` text NOT NULL,
  `sites_id` int(10) unsigned NOT NULL,
  `createUser_id` int(10) unsigned NOT NULL,
  `updateUser_id` int(10) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `KEY` (`sites_id`),
  KEY `fk_siteHistory_user1_idx` (`updateUser_id`),
  KEY `fk_siteHistory_user2_idx` (`createUser_id`),
  CONSTRAINT `sitesHistory_sites` FOREIGN KEY (`sites_id`) REFERENCES `site` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_siteHistory_user1` FOREIGN KEY (`updateUser_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_siteHistory_user2` FOREIGN KEY (`createUser_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle symfony_blog.siteHistory: ~0 rows (ungefähr)
DELETE FROM `siteHistory`;
/*!40000 ALTER TABLE `siteHistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `siteHistory` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.site_x_keyword
DROP TABLE IF EXISTS `site_x_keyword`;
CREATE TABLE IF NOT EXISTS `site_x_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL,
  `keyword_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site_x_keyword_site` (`site_id`),
  KEY `site_x_keyword_keyword` (`keyword_id`),
  CONSTRAINT `site_x_keyword_site` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `site_x_keyword_keyword` FOREIGN KEY (`keyword_id`) REFERENCES `keyword` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportiere Daten aus Tabelle symfony_blog.site_x_keyword: ~0 rows (ungefähr)
DELETE FROM `site_x_keyword`;
/*!40000 ALTER TABLE `site_x_keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_x_keyword` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.site_x_site
DROP TABLE IF EXISTS `site_x_site`;
CREATE TABLE IF NOT EXISTS `site_x_site` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mainSite_id` int(10) unsigned NOT NULL,
  `childSite_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site_x_site_mainsite_idx` (`mainSite_id`),
  KEY `site_x_site_childsite_idx` (`childSite_id`),
  CONSTRAINT `site_x_site_mainsite` FOREIGN KEY (`mainSite_id`) REFERENCES `site` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `site_x_site_childsite` FOREIGN KEY (`childSite_id`) REFERENCES `site` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle symfony_blog.site_x_site: ~0 rows (ungefähr)
DELETE FROM `site_x_site`;
/*!40000 ALTER TABLE `site_x_site` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_x_site` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `picturePath` varchar(250) DEFAULT NULL,
  `userGroup_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `loginName` varchar(50) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_userGroup` (`userGroup_id`),
  CONSTRAINT `fk_user_userGroup1` FOREIGN KEY (`userGroup_id`) REFERENCES `userGroup` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Exportiere Daten aus Tabelle symfony_blog.user: ~4 rows (ungefähr)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `picturePath`, `userGroup_id`, `email`, `password`, `loginName`, `created`, `updated`) VALUES
	(1, NULL, 2, 'test@test.de', 'test', 'test', '2016-08-16 15:06:09', NULL),
	(2, NULL, 1, 'nico.bauer@unister.de', '098f6bcd4621d373cade4e832627b4f6', 'n.bauer_kl', '2016-08-17 10:51:30', NULL),
	(3, NULL, 1, 'nico.bauer@unister.de', '098f6bcd4621d373cade4e832627b4f6', 'n.bauer_kl', '2016-08-17 11:25:18', NULL),
	(4, NULL, 1, 'nico.bauer@unister.de', '098f6bcd4621d373cade4e832627b4f6', 'n.bauer_kl', '2016-08-17 11:56:01', NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.userGroup
DROP TABLE IF EXISTS `userGroup`;
CREATE TABLE IF NOT EXISTS `userGroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle symfony_blog.userGroup: ~3 rows (ungefähr)
DELETE FROM `userGroup`;
/*!40000 ALTER TABLE `userGroup` DISABLE KEYS */;
INSERT INTO `userGroup` (`id`, `name`, `created`, `updated`) VALUES
	(1, 'Guest', '2016-08-15 13:50:00', '2016-08-15 13:50:00'),
	(2, 'Admin', '2016-08-15 14:19:00', '2016-08-15 13:50:00'),
	(3, 'User', '2016-08-15 14:33:52', NULL);
/*!40000 ALTER TABLE `userGroup` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.userGroupProject
DROP TABLE IF EXISTS `userGroupProject`;
CREATE TABLE IF NOT EXISTS `userGroupProject` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `read` tinyint(4) NOT NULL,
  `create` tinyint(4) NOT NULL,
  `edit` tinyint(4) NOT NULL,
  `delete` tinyint(4) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportiere Daten aus Tabelle symfony_blog.userGroupProject: ~0 rows (ungefähr)
DELETE FROM `userGroupProject`;
/*!40000 ALTER TABLE `userGroupProject` DISABLE KEYS */;
/*!40000 ALTER TABLE `userGroupProject` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.userGroup_x_Route
DROP TABLE IF EXISTS `userGroup_x_Route`;
CREATE TABLE IF NOT EXISTS `userGroup_x_Route` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userGroup_id` int(11) NOT NULL,
  `Route_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Route_UserGroup` (`userGroup_id`,`Route_id`),
  KEY `fk_userGroup_x_Route_userGroup1_idx` (`userGroup_id`),
  KEY `fk_userGroup_x_Route_Route1_idx` (`Route_id`),
  CONSTRAINT `fk_userGroup_x_Route_userGroup1` FOREIGN KEY (`userGroup_id`) REFERENCES `userGroup` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_userGroup_x_Route_Route1` FOREIGN KEY (`Route_id`) REFERENCES `Route` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle symfony_blog.userGroup_x_Route: ~0 rows (ungefähr)
DELETE FROM `userGroup_x_Route`;
/*!40000 ALTER TABLE `userGroup_x_Route` DISABLE KEYS */;
/*!40000 ALTER TABLE `userGroup_x_Route` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle symfony_blog.userInformation
DROP TABLE IF EXISTS `userInformation`;
CREATE TABLE IF NOT EXISTS `userInformation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lastName` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `informations` text,
  `street` varchar(250) DEFAULT NULL,
  `streetNumber` varchar(5) DEFAULT NULL,
  `plz` varchar(5) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `INDEX` (`city_id`),
  KEY `userInformations_user_idx` (`user_id`),
  CONSTRAINT `users_citys` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `userInformations_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportiere Daten aus Tabelle symfony_blog.userInformation: ~0 rows (ungefähr)
DELETE FROM `userInformation`;
/*!40000 ALTER TABLE `userInformation` DISABLE KEYS */;
/*!40000 ALTER TABLE `userInformation` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
