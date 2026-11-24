CREATE DATABASE IF NOT EXISTS webview_builder CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE webview_builder;
CREATE TABLE IF NOT EXISTS apps (
  id INT AUTO_INCREMENT PRIMARY KEY,
  app_code VARCHAR(20) UNIQUE NOT NULL,
  app_name VARCHAR(100) NOT NULL,
  app_url VARCHAR(255) NOT NULL,
  splash_duration INT DEFAULT 2000,
  splash_color VARCHAR(20) DEFAULT '#0A1A2F',
  splash_logo VARCHAR(255) DEFAULT '',
  app_icon VARCHAR(255) DEFAULT '',
  external_links TINYINT(1) DEFAULT 1,
  allow_download TINYINT(1) DEFAULT 1,
  allow_upload TINYINT(1) DEFAULT 1,
  pull_refresh TINYINT(1) DEFAULT 1,
  build_status VARCHAR(20) DEFAULT 'not_started',
  build_started_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
