-- contact-system schema (MySQL 8 / MariaDB 10.4+)
-- mysql -u root -p < database/schema.sql

CREATE DATABASE IF NOT EXISTS contact_system
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE contact_system;

CREATE TABLE IF NOT EXISTS users (
  user_id    INT          NOT NULL AUTO_INCREMENT,
  username   VARCHAR(50)  NOT NULL,
  email      VARCHAR(100) NOT NULL,
  password   VARCHAR(255) NOT NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id),
  UNIQUE KEY username (username),
  UNIQUE KEY email (email)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contacts (
  contact_id   INT          NOT NULL AUTO_INCREMENT,
  user_id      INT          NOT NULL,
  name         VARCHAR(100) NOT NULL,
  company      VARCHAR(32)  NOT NULL,
  phone_number VARCHAR(20)  DEFAULT NULL,
  email        VARCHAR(100) DEFAULT NULL,
  created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (contact_id),
  KEY user_name (user_id, name),
  CONSTRAINT contacts_user_fk FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE
) ENGINE=InnoDB;
