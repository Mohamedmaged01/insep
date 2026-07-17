-- Bilingual content columns (Arabic + English) for all content tables.
-- Adds `X_ar` / `X_en` for each translatable field and backfills the existing
-- value into `X_ar` (existing content treated as Arabic). The legacy `X` column
-- is kept as a display fallback. Safe to run once on the live database.

-- ===== courses =====
ALTER TABLE `courses`
  ADD COLUMN `title_ar` TEXT NULL,             ADD COLUMN `title_en` TEXT NULL,
  ADD COLUMN `description_ar` TEXT NULL,        ADD COLUMN `description_en` TEXT NULL,
  ADD COLUMN `content_ar` TEXT NULL,            ADD COLUMN `content_en` TEXT NULL,
  ADD COLUMN `features_ar` TEXT NULL,           ADD COLUMN `features_en` TEXT NULL,
  ADD COLUMN `accreditation_ar` TEXT NULL,      ADD COLUMN `accreditation_en` TEXT NULL,
  ADD COLUMN `job_opportunities_ar` TEXT NULL,  ADD COLUMN `job_opportunities_en` TEXT NULL,
  ADD COLUMN `duration_ar` TEXT NULL,           ADD COLUMN `duration_en` TEXT NULL;
UPDATE `courses` SET
  `title_ar`             = `title`             WHERE `title_ar` IS NULL AND `title` IS NOT NULL;
UPDATE `courses` SET `description_ar`       = `description`       WHERE `description_ar` IS NULL AND `description` IS NOT NULL;
UPDATE `courses` SET `content_ar`           = `content`           WHERE `content_ar` IS NULL AND `content` IS NOT NULL;
UPDATE `courses` SET `features_ar`          = `features`          WHERE `features_ar` IS NULL AND `features` IS NOT NULL;
UPDATE `courses` SET `accreditation_ar`     = `accreditation`     WHERE `accreditation_ar` IS NULL AND `accreditation` IS NOT NULL;
UPDATE `courses` SET `job_opportunities_ar` = `job_opportunities` WHERE `job_opportunities_ar` IS NULL AND `job_opportunities` IS NOT NULL;
UPDATE `courses` SET `duration_ar`          = `duration`          WHERE `duration_ar` IS NULL AND `duration` IS NOT NULL;

-- ===== news =====
ALTER TABLE `news`
  ADD COLUMN `title_ar` TEXT NULL,       ADD COLUMN `title_en` TEXT NULL,
  ADD COLUMN `description_ar` TEXT NULL, ADD COLUMN `description_en` TEXT NULL;
UPDATE `news` SET `title_ar`       = `title`       WHERE `title_ar` IS NULL AND `title` IS NOT NULL;
UPDATE `news` SET `description_ar` = `description` WHERE `description_ar` IS NULL AND `description` IS NOT NULL;

-- ===== committee_members =====
ALTER TABLE `committee_members`
  ADD COLUMN `name_ar` TEXT NULL,           ADD COLUMN `name_en` TEXT NULL,
  ADD COLUMN `title_ar` TEXT NULL,          ADD COLUMN `title_en` TEXT NULL,
  ADD COLUMN `specialization_ar` TEXT NULL, ADD COLUMN `specialization_en` TEXT NULL,
  ADD COLUMN `bio_ar` TEXT NULL,            ADD COLUMN `bio_en` TEXT NULL;
UPDATE `committee_members` SET `name_ar`           = `name`           WHERE `name_ar` IS NULL AND `name` IS NOT NULL;
UPDATE `committee_members` SET `title_ar`          = `title`          WHERE `title_ar` IS NULL AND `title` IS NOT NULL;
UPDATE `committee_members` SET `specialization_ar` = `specialization` WHERE `specialization_ar` IS NULL AND `specialization` IS NOT NULL;
UPDATE `committee_members` SET `bio_ar`            = `bio`            WHERE `bio_ar` IS NULL AND `bio` IS NOT NULL;

-- ===== batches =====
ALTER TABLE `batches`
  ADD COLUMN `name_ar` TEXT NULL, ADD COLUMN `name_en` TEXT NULL;
UPDATE `batches` SET `name_ar` = `name` WHERE `name_ar` IS NULL AND `name` IS NOT NULL;

-- ===== exams =====
ALTER TABLE `exams`
  ADD COLUMN `title_ar` TEXT NULL, ADD COLUMN `title_en` TEXT NULL;
UPDATE `exams` SET `title_ar` = `title` WHERE `title_ar` IS NULL AND `title` IS NOT NULL;

-- ===== resources =====
ALTER TABLE `resources`
  ADD COLUMN `title_ar` TEXT NULL, ADD COLUMN `title_en` TEXT NULL;
UPDATE `resources` SET `title_ar` = `title` WHERE `title_ar` IS NULL AND `title` IS NOT NULL;

-- ===== live_sessions =====
ALTER TABLE `live_sessions`
  ADD COLUMN `title_ar` TEXT NULL, ADD COLUMN `title_en` TEXT NULL;
UPDATE `live_sessions` SET `title_ar` = `title` WHERE `title_ar` IS NULL AND `title` IS NOT NULL;

-- ===== sections (name_ar/name_en already exist) =====
ALTER TABLE `sections`
  ADD COLUMN `description_ar` TEXT NULL, ADD COLUMN `description_en` TEXT NULL;
UPDATE `sections` SET `description_ar` = `description` WHERE `description_ar` IS NULL AND `description` IS NOT NULL;

-- Register the migration so `php artisan migrate` won't re-run it:
INSERT INTO `migrations` (`migration`, `batch`)
VALUES ('2026_06_24_000004_add_bilingual_columns', (SELECT b FROM (SELECT COALESCE(MAX(`batch`),0)+1 AS b FROM `migrations`) x));
