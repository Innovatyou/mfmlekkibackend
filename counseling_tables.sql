-- Run this in phpMyAdmin or MySQL on the production server
-- Creates the 3 counseling tables required by the mobile app

CREATE TABLE IF NOT EXISTS `tbl_counseling_cases` (
  `id`              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id`       INT(11) UNSIGNED DEFAULT NULL,
  `member_name`     VARCHAR(255) NOT NULL DEFAULT '',
  `member_email`    VARCHAR(255) NOT NULL DEFAULT '',
  `member_phone`    VARCHAR(100) NOT NULL DEFAULT '',
  `category`        ENUM('marriage','family','grief','addiction','mental_health','financial','spiritual','relationship','other') NOT NULL DEFAULT 'other',
  `title`           VARCHAR(500) NOT NULL DEFAULT '',
  `status`          ENUM('open','in_progress','on_hold','closed','referred') NOT NULL DEFAULT 'open',
  `priority`        ENUM('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `assigned_to`     VARCHAR(255) NOT NULL DEFAULT '',
  `is_confidential` TINYINT(1) NOT NULL DEFAULT 1,
  `opened_by`       VARCHAR(255) NOT NULL DEFAULT '',
  `opened_at`       DATETIME DEFAULT NULL,
  `closed_at`       DATETIME DEFAULT NULL,
  `next_followup`   DATE DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_counseling_sessions` (
  `id`                   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `case_id`              INT(11) UNSIGNED NOT NULL,
  `session_type`         ENUM('in_person','phone','video','email','prayer','other') NOT NULL DEFAULT 'in_person',
  `meeting_platform`     VARCHAR(50) DEFAULT NULL,
  `meeting_link`         VARCHAR(500) DEFAULT NULL,
  `meeting_scheduled_at` DATETIME DEFAULT NULL,
  `meeting_status`       ENUM('pending','confirmed','completed','cancelled') DEFAULT NULL,
  `invite_sent`          TINYINT(1) NOT NULL DEFAULT 0,
  `session_date`         DATE DEFAULT NULL,
  `duration_minutes`     INT(5) DEFAULT NULL,
  `notes`                TEXT NOT NULL,
  `outcome`              TEXT DEFAULT NULL,
  `next_steps`           TEXT DEFAULT NULL,
  `logged_by`            VARCHAR(255) NOT NULL DEFAULT '',
  `created_at`           DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `case_id` (`case_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_counseling_reminders` (
  `id`            INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `case_id`       INT(11) UNSIGNED NOT NULL,
  `reminder_date` DATE DEFAULT NULL,
  `note`          TEXT DEFAULT NULL,
  `is_done`       TINYINT(1) NOT NULL DEFAULT 0,
  `created_by`    VARCHAR(255) NOT NULL DEFAULT '',
  `created_at`    DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `case_id` (`case_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
