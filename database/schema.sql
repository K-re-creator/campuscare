-- 1. Initialize Database Environment with Full UTF-8 Unicode Support
CREATE DATABASE IF NOT EXISTS campuscare
CHARACTER SET utf8mb4 
COLLATE utf8mb4_general_ci;

USE campuscare;

-- 2. Drop existing tables in reverse relational dependency order to prevent constraint lockouts
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS users;

-- 3. Create Users Relational Table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(254) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer', 'staff', 'admin') NOT NULL DEFAULT 'customer',
    active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    -- Rule: Enforce that no two system profiles can register identical emails
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB;

-- 4. Create Services Relational Table
CREATE TABLE services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    duration_minutes SMALLINT UNSIGNED NOT NULL,
    active BOOLEAN NOT NULL DEFAULT TRUE,
    -- Rule: Enforce boundaries on valid operational consultation duration windows
    CONSTRAINT chk_service_duration CHECK (duration_minutes BETWEEN 5 AND 480)
) ENGINE=InnoDB;

-- 5. Create Bookings Transaction Table
CREATE TABLE bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    service_id BIGINT UNSIGNED NOT NULL,
    starts_at DATETIME NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') NOT NULL DEFAULT 'pending',
    notes VARCHAR(500) NOT NULL DEFAULT '',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Relational Constraint Controls: Prevent deletion of records that reference active entities
    CONSTRAINT fk_booking_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT fk_booking_service FOREIGN KEY (service_id) 
        REFERENCES services(id) ON DELETE RESTRICT,
        
    -- Concurrency Control: Prevent overbooking the same time slot for a single service profile
    UNIQUE KEY uq_service_slot (service_id, starts_at),
    -- High Performance Index: Accelerates landing dashboard queries for historical sorting
    KEY ix_user_created (user_id, created_at)
) ENGINE=InnoDB;
