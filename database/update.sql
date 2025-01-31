ALTER TABLE `articles`
	CHANGE COLUMN `category_id` `category_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `user_id`;

ALTER TABLE `podcasts`
	ADD COLUMN `body` TEXT NULL AFTER `title`;

CREATE TABLE IF NOT EXISTS `creation_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creation_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `creation_id` (`creation_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;