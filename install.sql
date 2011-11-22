CREATE TABLE IF NOT EXISTS `pm` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(10) unsigned NOT NULL COMMENT 'sender id',
  `recipient` int(10) unsigned NOT NULL COMMENT 'sender id',
  `read` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'message read',
  `ds` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'deleted by sender',
  `dr` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'deleted by recipient',
  `date` int(10) unsigned NOT NULL,
  `subject` text,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
