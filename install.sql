CREATE TABLE IF NOT EXISTS `pm` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender` varchar(50) NOT NULL,
  `recipient` varchar(50) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'message read',
  `ds` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'deleted by sender',
  `dr` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'deleted by recipient',
  `date` int(10) unsigned NOT NULL,
  `subject` text,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
