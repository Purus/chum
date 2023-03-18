<?php

use Chum\ChumDb;

$dbPrefix = CHUM_DB_PREFIX;

$sql =
    <<<EOT

CREATE TABLE `{$dbPrefix}blogs_post` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `authorId` INTEGER(11) NOT NULL,
  `title` VARCHAR(512) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `post` TEXT COLLATE utf8_general_ci NOT NULL,
  `timestamp` INTEGER(11) NOT NULL,
  `isDraft` TINYINT(1) NOT NULL,
  `privacy` varchar(50) NOT NULL default 'everybody',
  PRIMARY KEY (`id`),
  KEY `authorId` (`authorId`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

EOT;

ChumDb::getInstance()->run($sql);