-- Run this in phpMyAdmin / HeidiSQL on the `churchbackend` database

CREATE TABLE IF NOT EXISTS `counseling_requests` (
  `id`             INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `email`          VARCHAR(255)    NOT NULL,
  `name`           VARCHAR(255)    NOT NULL,
  `category`       VARCHAR(50)     NOT NULL,
  `title`          VARCHAR(500)    NOT NULL,
  `note`           TEXT,
  `status`         ENUM('open','in_progress','on_hold','closed','referred') NOT NULL DEFAULT 'open',
  `priority`       ENUM('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `assigned_to`    VARCHAR(255)    DEFAULT NULL,
  `opened_at`      DATETIME        DEFAULT NULL,
  `next_followup`  DATE            DEFAULT NULL,
  `created_at`     DATETIME        DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `counseling_video_sessions` (
  `id`                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `counseling_request_id`   INT UNSIGNED DEFAULT NULL,
  `email`                   VARCHAR(255) NOT NULL,
  `meeting_platform`        VARCHAR(50)  NOT NULL,
  `meeting_link`            VARCHAR(500) DEFAULT NULL,
  `meeting_scheduled_at`    DATETIME     DEFAULT NULL,
  `meeting_status`          ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  `duration_minutes`        INT UNSIGNED DEFAULT 60,
  `case_title`              VARCHAR(500) DEFAULT NULL,
  `assigned_to`             VARCHAR(255) DEFAULT NULL,
  `created_at`              DATETIME     DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_scheduled_at` (`meeting_scheduled_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
