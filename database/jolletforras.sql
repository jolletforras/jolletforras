-- 2024.11.29--ei állapot

-- --------------------------------------------------------
-- Hoszt:                        127.0.0.1
-- Szerver verzió:               10.4.32-MariaDB - mariadb.org binary distribution
-- Szerver OS:                   Win64
-- HeidiSQL Verzió:              12.7.0.6850
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Struktúra mentése tábla jolletforras_github. articles
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `title` varchar(60) NOT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `body` text NOT NULL,
  `short_description` text NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `show` enum('just_profile','portal_too') DEFAULT 'just_profile',
  `counter` smallint(5) unsigned NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `articles_editor_id_foreign` (`user_id`),
  CONSTRAINT `articles_editor_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. article_group
CREATE TABLE IF NOT EXISTS `article_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. article_group_tag
CREATE TABLE IF NOT EXISTS `article_group_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `group_tag_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`),
  KEY `group_tag_id` (`group_tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(60) NOT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `body` text NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `type` enum('article','creation') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo_counter` smallint(6) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `articles_editor_id_foreign` (`user_id`),
  CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. commendations
CREATE TABLE IF NOT EXISTS `commendations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(80) NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `public` smallint(1) unsigned NOT NULL DEFAULT 1,
  `active` smallint(1) unsigned NOT NULL DEFAULT 0,
  `approved` smallint(1) unsigned NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_image` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `has_image` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `counter` smallint(5) unsigned NOT NULL DEFAULT 0,
  `photo_counter` smallint(6) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `forums_user_id_foreign` (`user_id`),
  CONSTRAINT `commendations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. commendation_group
CREATE TABLE IF NOT EXISTS `commendation_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `commendation_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `commendation_id` (`commendation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. commendation_group_tag
CREATE TABLE IF NOT EXISTS `commendation_group_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commendation_id` int(10) unsigned NOT NULL,
  `group_tag_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shorted_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `commentable_id` int(10) unsigned NOT NULL,
  `commentable_type` varchar(60) NOT NULL,
  `commenter_id` int(10) unsigned NOT NULL,
  `lev1_comment_id` int(11) DEFAULT NULL,
  `to_comment_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `comments_commentable_id_index` (`commentable_id`),
  KEY `comments_commentable_type_index` (`commentable_type`)
) ENGINE=InnoDB AUTO_INCREMENT=375 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. creations
CREATE TABLE IF NOT EXISTS `creations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `title` varchar(80) NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `has_image` smallint(1) unsigned NOT NULL DEFAULT 0,
  `public` smallint(1) unsigned NOT NULL DEFAULT 1,
  `active` smallint(1) unsigned NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_image` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `counter` smallint(5) unsigned NOT NULL DEFAULT 0,
  `photo_counter` smallint(6) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `forums_user_id_foreign` (`user_id`),
  CONSTRAINT `creations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. events
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `title` varchar(60) NOT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `expiration_date` date DEFAULT '2024-01-01',
  `shorted_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `counter` smallint(5) unsigned NOT NULL DEFAULT 0,
  `visibility` enum('group','portal','public') NOT NULL DEFAULT 'portal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. event_user
CREATE TABLE IF NOT EXISTS `event_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `participate` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`event_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. forums
CREATE TABLE IF NOT EXISTS `forums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL DEFAULT 0,
  `type` enum('conversation','announcement','knowledge') NOT NULL,
  `title` varchar(60) NOT NULL,
  `shorted_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `counter` smallint(5) unsigned NOT NULL DEFAULT 0,
  `active` smallint(1) unsigned NOT NULL DEFAULT 1,
  `email_sent` smallint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `forums_user_id_foreign` (`user_id`),
  CONSTRAINT `forums_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. forum_forum_tag
CREATE TABLE IF NOT EXISTS `forum_forum_tag` (
  `forum_id` int(10) unsigned NOT NULL,
  `forum_tag_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `forum_tag_forum_id_index` (`forum_id`),
  KEY `forum_tag_tag_id_index` (`forum_tag_id`),
  CONSTRAINT `forum_tag_forum_id_foreign` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forum_tag_tag_id_foreign` FOREIGN KEY (`forum_tag_id`) REFERENCES `forum_tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. forum_tags
CREATE TABLE IF NOT EXISTS `forum_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `group_id` int(10) unsigned NOT NULL DEFAULT 0,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. groupnews
CREATE TABLE IF NOT EXISTS `groupnews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(60) NOT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `visibility` enum('portal','public') NOT NULL DEFAULT 'portal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `news_editor_id_foreign` (`user_id`),
  CONSTRAINT `groupnews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. groups
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `description` text NOT NULL,
  `agreement` text NOT NULL,
  `member_info` text DEFAULT NULL,
  `admin_info` text DEFAULT NULL,
  `ask_motivation` tinyint(1) DEFAULT 0,
  `webpage_name` varchar(60) DEFAULT NULL,
  `webpage_url` varchar(64) DEFAULT NULL,
  `location` varchar(25) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `zip_code` smallint(5) unsigned DEFAULT NULL,
  `lat` decimal(5,3) DEFAULT NULL,
  `lng` decimal(5,3) DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT 0,
  `knowledge_tab` tinyint(1) NOT NULL DEFAULT 0,
  `knowledge_info` text DEFAULT NULL,
  `user_visibility` enum('group','portal','public') NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_news_at` date DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `counter` smallint(6) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `photo_counter` smallint(6) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. group_group_tag
CREATE TABLE IF NOT EXISTS `group_group_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `group_tag_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_group_theme_group_id_index` (`group_id`),
  KEY `group_group_theme_group_theme_id_index` (`group_tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. group_project
CREATE TABLE IF NOT EXISTS `group_project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. group_tags
CREATE TABLE IF NOT EXISTS `group_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_themes_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. group_tag_project
CREATE TABLE IF NOT EXISTS `group_tag_project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `group_tag_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `project_tag_id` (`group_tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. group_user
CREATE TABLE IF NOT EXISTS `group_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `motivation` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2102 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. guides
CREATE TABLE IF NOT EXISTS `guides` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `short_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. ideas
CREATE TABLE IF NOT EXISTS `ideas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(60) NOT NULL,
  `body` text NOT NULL,
  `looking_for` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `slug` varchar(60) NOT NULL,
  `counter` smallint(6) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. idea_idea_skill
CREATE TABLE IF NOT EXISTS `idea_idea_skill` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idea_id` int(10) unsigned NOT NULL,
  `idea_skill_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idea_idea_skill_idea_id_index` (`idea_id`),
  KEY `idea_idea_skill_idea_skill_id_index` (`idea_skill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. idea_skills
CREATE TABLE IF NOT EXISTS `idea_skills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `slug` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idea_skills_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. invites
CREATE TABLE IF NOT EXISTS `invites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `email` varchar(60) NOT NULL,
  `activation_code` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `activation_code_UNIQUE` (`activation_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. newsletters
CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(60) NOT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `short_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `articles_editor_id_foreign` (`user_id`) USING BTREE,
  CONSTRAINT `newsletters_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. notices
CREATE TABLE IF NOT EXISTS `notices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(10) NOT NULL,
  `notifiable_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('Forum','Event') NOT NULL,
  `comment_id` int(11) DEFAULT 0,
  `new` smallint(5) unsigned NOT NULL DEFAULT 0,
  `email` tinyint(1) DEFAULT 0,
  `email_sent` tinyint(1) DEFAULT 0,
  `ask_notice` tinyint(1) DEFAULT 0,
  `read_it` tinyint(1) DEFAULT 0,
  `login_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`notifiable_id`,`user_id`,`type`),
  UNIQUE KEY `id` (`id`),
  KEY `user_id` (`user_id`),
  KEY `notifiable_id` (`notifiable_id`),
  KEY `login_code` (`login_code`)
) ENGINE=InnoDB AUTO_INCREMENT=1201 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. podcasts
CREATE TABLE IF NOT EXISTS `podcasts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `event_id` int(10) unsigned DEFAULT NULL,
  `group_id` int(10) unsigned DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. projectnews
CREATE TABLE IF NOT EXISTS `projectnews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(60) NOT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `visibility` enum('portal','public') NOT NULL DEFAULT 'portal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `news_editor_id_foreign` (`user_id`),
  CONSTRAINT `news_editor_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. projects
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(60) NOT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `body` text NOT NULL,
  `my_undertake` text NOT NULL,
  `looking_for` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT 0,
  `location` varchar(25) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `zip_code` smallint(5) unsigned DEFAULT NULL,
  `lat` decimal(5,3) DEFAULT NULL,
  `lng` decimal(5,3) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_news_at` date DEFAULT NULL,
  `slug` varchar(60) NOT NULL,
  `counter` smallint(6) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `approved` smallint(1) unsigned NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `photo_counter` smallint(6) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. project_user
CREATE TABLE IF NOT EXISTS `project_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. sendemails
CREATE TABLE IF NOT EXISTS `sendemails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. usernotices
CREATE TABLE IF NOT EXISTS `usernotices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `type` enum('Article','Creation') NOT NULL,
  `post_created_at` date DEFAULT NULL,
  PRIMARY KEY (`user_id`,`post_id`,`type`),
  UNIQUE KEY `id` (`id`),
  KEY `user_id` (`user_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1072 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `email` varchar(60) NOT NULL,
  `full_name` varchar(40) DEFAULT NULL,
  `has_photo` tinyint(1) NOT NULL DEFAULT 0,
  `birth_year` smallint(5) unsigned DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `zip_code` smallint(5) unsigned DEFAULT NULL,
  `lat` decimal(5,3) DEFAULT NULL,
  `lng` decimal(5,3) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `public` tinyint(1) NOT NULL DEFAULT 0,
  `registred_by` int(10) unsigned DEFAULT NULL,
  `registry_type` tinyint(1) DEFAULT NULL,
  `introduction` text DEFAULT NULL,
  `interest` text DEFAULT NULL,
  `intention` text DEFAULT NULL,
  `facebook_url` varchar(64) DEFAULT NULL,
  `webpage_name` varchar(200) DEFAULT NULL,
  `webpage_url` varchar(200) DEFAULT NULL,
  `has_article` tinyint(1) NOT NULL DEFAULT 0,
  `has_creation` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(60) NOT NULL,
  `login_code` varchar(10) DEFAULT NULL,
  `email_sent_at` datetime DEFAULT NULL,
  `activation_code` varchar(10) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(30) DEFAULT NULL,
  `user_new_post` smallint(5) unsigned NOT NULL DEFAULT 0,
  `new_post` smallint(5) unsigned NOT NULL DEFAULT 0,
  `my_post_comment_notice` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'hozzászólnak valamelyik témámhoz, eseményhez, kezdeményezéshez',
  `new_post_notice` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'új téma, esemény, kezdeményezés esetén',
  `theme_comment_notice` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'általam hozzászólthoz hozzászólnak',
  `can_login_with_code` tinyint(1) NOT NULL DEFAULT 1,
  `admin` tinyint(1) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `photo_counter` smallint(6) DEFAULT 0,
  `nr_creation_image` smallint(3) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. user_interests
CREATE TABLE IF NOT EXISTS `user_interests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_interests_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. user_skills
CREATE TABLE IF NOT EXISTS `user_skills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_skills_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. user_user_interest
CREATE TABLE IF NOT EXISTS `user_user_interest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `user_interest_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_user_interest_user_id_index` (`user_id`),
  KEY `user_user_interest_user_interest_id_index` (`user_interest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

-- Struktúra mentése tábla jolletforras_github. user_user_skill
CREATE TABLE IF NOT EXISTS `user_user_skill` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `user_skill_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_user_skill_user_id_index` (`user_id`),
  KEY `user_user_skill_user_skill_id_index` (`user_skill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Az adatok exportálása nem lett kiválasztva.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
