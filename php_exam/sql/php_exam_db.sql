-- Création base
CREATE DATABASE IF NOT EXISTS php_exam_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE php_exam_db;

-- Table User
CREATE TABLE `User` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` int DEFAULT '0',
  `role` enum('user','creator','admin') DEFAULT 'user',
  `profile_picture` varchar(255) DEFAULT 'default.png',
  `bio` text,
  `skill_level` enum('Junior','Intermédiaire','Senior') DEFAULT 'Junior',
  `score` INT NOT NULL DEFAULT 0,
  `is_banned` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Challenge
CREATE TABLE `Challenge` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `difficulty` varchar(100) NOT NULL,
  `price` int NOT NULL,
  `author_id` int NOT NULL,
  `image_url` varchar(255) DEFAULT 'challenge_default.png',
  `access_url` varchar(255) DEFAULT NULL,
  `flag_hash` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `Challenge_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `User` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Cart
CREATE TABLE `Cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `challenge_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `challenge_id` (`challenge_id`),
  UNIQUE KEY `unique_cart_item` (`user_id`, `challenge_id`),
  CONSTRAINT `Cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  CONSTRAINT `Cart_ibfk_2` FOREIGN KEY (`challenge_id`) REFERENCES `Challenge` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Invoice
CREATE TABLE `Invoice` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `amount` int NOT NULL,
  `billing_address` varchar(255) NOT NULL,
  `billing_city` varchar(100) NOT NULL,
  `billing_zip` varchar(20) NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `Invoice_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table InvoiceItem (compatible with both schemas)
CREATE TABLE `InvoiceItem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice_id` int NOT NULL,
  `challenge_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `challenge_id` (`challenge_id`),
  CONSTRAINT `InvoiceItem_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `Invoice` (`id`) ON DELETE CASCADE,
  CONSTRAINT `InvoiceItem_ibfk_2` FOREIGN KEY (`challenge_id`) REFERENCES `Challenge` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Submission
CREATE TABLE `Submission` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `challenge_id` int NOT NULL,
  `flag_submitted` varchar(255) NOT NULL,
  `is_valid` tinyint(1) NOT NULL,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `challenge_id` (`challenge_id`),
  KEY `idx_submission_valid` (`is_valid`, `user_id`, `challenge_id`),
  CONSTRAINT `Submission_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  CONSTRAINT `Submission_ibfk_2` FOREIGN KEY (`challenge_id`) REFERENCES `Challenge` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table UserChallenge (for tracking purchased challenges)
CREATE TABLE `UserChallenge` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `challenge_id` int NOT NULL,
  `acquired_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_challenge` (`user_id`, `challenge_id`),
  CONSTRAINT `UserChallenge_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  CONSTRAINT `UserChallenge_ibfk_2` FOREIGN KEY (`challenge_id`) REFERENCES `Challenge` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table ChallengeInstance (for managing instances)
CREATE TABLE `ChallengeInstance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `challenge_id` int NOT NULL,
  `available_instances` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  CONSTRAINT `ChallengeInstance_ibfk_1` FOREIGN KEY (`challenge_id`) REFERENCES `Challenge` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

