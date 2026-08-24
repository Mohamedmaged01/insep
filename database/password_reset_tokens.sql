-- Table required by Laravel's password-reset broker (forgot-password feature).
-- Run once in phpMyAdmin. Safe to re-run (IF NOT EXISTS).

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: register the migration so `php artisan migrate:status` stays in sync.
-- INSERT INTO `migrations` (`migration`, `batch`)
-- VALUES ('2026_08_24_000001_create_password_reset_tokens_table',
--         (SELECT COALESCE(MAX(batch), 0) + 1 FROM (SELECT * FROM `migrations`) m));
