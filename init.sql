CREATE TABLE `chum_users` (
	`first_name` VARCHAR(50) NOT NULL,
	`last_name` VARCHAR(50) NOT NULL
)
COLLATE='latin1_swedish_ci';

CREATE TABLE `social_base_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` text,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=850 DEFAULT CHARSET=utf8;

INSERT INTO `social_configs` VALUES 
(1,'base','site_name','demo','Site name'),
(2,'base','site_tagline','demo','Site tagline'),
(3,'base','site_description','Just another Oxwall site','Site Description'),
(4,'base','current_theme','chum-chum','Current theme');

DROP TABLE IF EXISTS `social_themes`;
CREATE TABLE `social_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devName` varchar(255) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '0',
  `key` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `customCss` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=957 DEFAULT CHARSET=utf8;