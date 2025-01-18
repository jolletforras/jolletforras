ALTER TABLE `articles`
	CHANGE COLUMN `category_id` `category_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `user_id`;
