CREATE TABLE IF NOT EXISTS `pm` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) unsigned NOT NULL COMMENT 'sender id',
  `recipient_id` int(10) unsigned NOT NULL COMMENT 'sender id',
  `thread_id` int(10) unsigned NOT NULL DEFAULT '0',
  `read` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'message read',
  `ds` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'deleted by sender',
  `dr` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'deleted by recipient',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subject` text,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `thread_id` (`thread_id`),
  KEY `dr` (`dr`),
  KEY `ds` (`ds`)
) DEFAULT CHARSET=utf8;
