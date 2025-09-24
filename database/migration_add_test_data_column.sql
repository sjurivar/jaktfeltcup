-- Migration: Add is_test_data column to all tables
-- Run this if you have an existing database without the is_test_data column

-- Add is_test_data column to users table
ALTER TABLE jaktfelt_users ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_users_test_data ON jaktfelt_users(is_test_data);

-- Add is_test_data column to seasons table
ALTER TABLE jaktfelt_seasons ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_seasons_test_data ON jaktfelt_seasons(is_test_data);

-- Add is_test_data column to competitions table
ALTER TABLE jaktfelt_competitions ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_competitions_test_data ON jaktfelt_competitions(is_test_data);

-- Add is_test_data column to categories table
ALTER TABLE jaktfelt_categories ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_categories_test_data ON jaktfelt_categories(is_test_data);

-- Add is_test_data column to competition_categories table
ALTER TABLE jaktfelt_competition_categories ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_competition_categories_test_data ON jaktfelt_competition_categories(is_test_data);

-- Add is_test_data column to registrations table
ALTER TABLE jaktfelt_registrations ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_registrations_test_data ON jaktfelt_registrations(is_test_data);

-- Add is_test_data column to results table
ALTER TABLE jaktfelt_results ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_results_test_data ON jaktfelt_results(is_test_data);

-- Add is_test_data column to point_systems table
ALTER TABLE jaktfelt_point_systems ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_point_systems_test_data ON jaktfelt_point_systems(is_test_data);

-- Add is_test_data column to point_rules table
ALTER TABLE jaktfelt_point_rules ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_point_rules_test_data ON jaktfelt_point_rules(is_test_data);

-- Add is_test_data column to season_point_systems table
ALTER TABLE jaktfelt_season_point_systems ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_season_point_systems_test_data ON jaktfelt_season_point_systems(is_test_data);

-- Add is_test_data column to email_verifications table
ALTER TABLE jaktfelt_email_verifications ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_email_verifications_test_data ON jaktfelt_email_verifications(is_test_data);

-- Add is_test_data column to notifications table
ALTER TABLE jaktfelt_notifications ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_notifications_test_data ON jaktfelt_notifications(is_test_data);

-- Add is_test_data column to offline_sync table
ALTER TABLE jaktfelt_offline_sync ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_offline_sync_test_data ON jaktfelt_offline_sync(is_test_data);

-- Add is_test_data column to audit_log table
ALTER TABLE jaktfelt_audit_log ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE;
CREATE INDEX idx_audit_log_test_data ON jaktfelt_audit_log(is_test_data);
