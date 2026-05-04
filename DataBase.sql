/*Base de datos de procesos de pagos*/

CREATE DATABASE IF NOT EXISTS `latinpaychallenge`;

use `latinpaychallenge`;

CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `payment_code`  VARCHAR(30) UNIQUE NOT NULL ,
  `merchant_id` INT null,
  `customer_document` varchar (20),
  `amount` DECIMAL(8,2) not null,
  `currency` CHAR ( 5) not null,
  `is_deleted` INT DEFAULT 0 COMMENT '0 = no eliminado, 1 = eliminado',
  `status` char(20) not null,
  `description` varchar(255) null,
  `paid_at` TIMESTAMP NULL  COMMENT 'Fecha y hora del registro en TimeZone 0',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP  COMMENT 'Fecha y hora del registro en TimeZone 0',
  `updated_at` DATETIME NULL
)
ENGINE = InnoDB
COMMENT 'Tabla de pagos';

create table if not EXISTS `bank_notifications` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `event_id` VARCHAR(30) UNIQUE NOT NULL ,
    `bank_transaction_id` VARCHAR(50) UNIQUE NOT NULL,
    `payment_code` VARCHAR(30) NOT NULL,
    `payload` JSON null comment 'payload original recibido',
    `amount` DECIMAL(8,2) not null,
    `is_deleted` INT DEFAULT 0 COMMENT '0 = no eliminado, 1 = eliminado',
    `currency` CHAR ( 5) not null,
    `status` char(20) NOT NULL,
    `paid_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP  COMMENT 'Fecha y hora del registro en TimeZone 0',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL
)ENGINE = InnoDB
COMMENT 'tabla de notificaciones de banco';


create table if not EXISTS `bank_reconciliation_movements` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `bank` VARCHAR(60) not null ,
    `bank_transaction_id` VARCHAR(50) not null,
    `bank_movement_id` VARCHAR(30) NOT NULL,
    `process_date` date NOT NULL,
    `status` char(20) NOT NULL,
    `payment_code` VARCHAR(30) NOT NULL,
    `amount` DECIMAL(8,2) not null,
    `currency` CHAR ( 5) not null,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL
)ENGINE = InnoDB
COMMENT 'Tabla de movimientos';


create table if not EXISTS `external_notifications` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `payment_id` int UNIQUE NOT NULL ,
    `status` char(20) NOT NULL,
    `attempts` int default 0,
    `error` text null,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL
)ENGINE = InnoDB
COMMENT 'Tabla de notificaciones externas';

CREATE TABLE IF NOT EXISTS `payment_audits` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `payment_id` BIGINT UNSIGNED NOT NULL,
  `action` VARCHAR(50) NOT NULL,
  `status` VARCHAR(20) NOT NULL,
  `description` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB
COMMENT 'Tabla de auditoría de pagos';

CREATE INDEX idx_payment_code ON bank_notifications(payment_code);
CREATE INDEX idx_status ON bank_notifications(status);
CREATE INDEX idx_payment_id ON payment_audits(payment_id);
CREATE INDEX idx_action ON payment_audits(action);
CREATE INDEX idx_payment_created ON payment_audits(payment_id, created_at);
