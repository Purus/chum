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

INSERT INTO `social_base_config` VALUES 
(1,'base','site_name','demo','Site name'),
(2,'base','site_tagline','demo','Site tagline'),
(3,'base','site_description','Just another Oxwall site','Site Description');
