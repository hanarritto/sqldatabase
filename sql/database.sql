CREATE DATABASE IF NOT EXISTS dorm_access
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE dorm_access;

DROP TABLE IF EXISTS security_reports;
DROP TABLE IF EXISTS access_logs;
DROP TABLE IF EXISTS student_history;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS students;

CREATE TABLE students (
    student_id VARCHAR(20) PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    face_image VARCHAR(255) NULL,
    register_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('SuperAdmin','Staff') NOT NULL DEFAULT 'Staff',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE student_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    floor INT NOT NULL,
    room VARCHAR(20) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NULL,
    CONSTRAINT fk_history_student
        FOREIGN KEY (student_id) REFERENCES students(student_id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE access_logs (
    log_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NULL,
    login_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    access_type ENUM('Entry','Exit') NOT NULL,
    status ENUM('SUCCESS','FAILED') NOT NULL,
    device_id VARCHAR(50) NOT NULL,
    user_type ENUM('Student','Unknown','Staff') NOT NULL DEFAULT 'Student',
    CONSTRAINT fk_log_student
        FOREIGN KEY (student_id) REFERENCES students(student_id)
        ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE security_reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    log_id BIGINT NOT NULL,
    admin_id INT NULL,
    report_type VARCHAR(100) NOT NULL,
    report_detail TEXT NOT NULL,
    action_taken TEXT NULL,
    report_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_report_log
        FOREIGN KEY (log_id) REFERENCES access_logs(log_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_report_admin
        FOREIGN KEY (admin_id) REFERENCES admins(admin_id)
        ON UPDATE CASCADE ON DELETE SET NULL
);

-- Demo admin. Password = admin123
INSERT INTO admins (username, password_hash, role, created_at, is_active)
VALUES (
    'admin_main',
    '$2y$12$HG6o5arke1qYmIuZ0AFEyOcc6gB7ZM/ix2H7FB1DQVWgf8bK6tOra',
    'SuperAdmin',
    '2025-01-10 09:00:00',
    1
);

-- Optional example data is intentionally NOT inserted.
-- Add real records through the web interface.
