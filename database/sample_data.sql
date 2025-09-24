-- Jaktfeltcup Sample Data
-- This file contains sample/test data for development and testing
-- All data is marked with is_test_data = TRUE to distinguish from real data

-- Insert sample seasons (mark as test data)
INSERT INTO jaktfelt_seasons (name, year, is_active, start_date, end_date, is_test_data) VALUES 
('Jaktfeltcup 2023 (Test)', 2023, FALSE, '2023-01-01', '2023-12-31', TRUE),
('Jaktfeltcup 2024 (Test)', 2024, TRUE, '2024-01-01', '2024-12-31', TRUE);

-- Insert sample categories (mark as test data)
INSERT INTO jaktfelt_categories (name, description, min_age, max_age, weapon_type, is_active, is_test_data) VALUES 
('Senior (Test)', 'Senior klasse - Testdata', 18, 99, 'Rifle', TRUE, TRUE),
('Junior (Test)', 'Junior klasse - Testdata', 12, 17, 'Rifle', TRUE, TRUE),
('Veteran (Test)', 'Veteran klasse - Testdata', 50, 99, 'Rifle', TRUE, TRUE),
('Dame (Test)', 'Dame klasse - Testdata', 18, 99, 'Rifle', TRUE, TRUE);

-- Insert sample users (mark as test data)
INSERT INTO jaktfelt_users (username, email, password_hash, first_name, last_name, phone, date_of_birth, address, role, is_active, email_verified, is_test_data) VALUES 
('testadmin', 'admin@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test', 'Administrator', '12345678', '1980-01-01', 'Testveien 1, 0001 Oslo', 'admin', TRUE, TRUE, TRUE),
('testorganizer', 'organizer@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test', 'Arrangør', '12345679', '1975-05-15', 'Arrangørveien 2, 0002 Oslo', 'organizer', TRUE, TRUE, TRUE),
('testdeltaker1', 'deltaker1@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ole', 'Hansen', '12345680', '1990-03-20', 'Deltakerveien 3, 0003 Oslo', 'participant', TRUE, TRUE, TRUE),
('testdeltaker2', 'deltaker2@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kari', 'Nordmann', '12345681', '1985-07-10', 'Deltakerveien 4, 0004 Oslo', 'participant', TRUE, TRUE, TRUE),
('testdeltaker3', 'deltaker3@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Erik', 'Johansen', '12345682', '1992-11-05', 'Deltakerveien 5, 0005 Oslo', 'participant', TRUE, TRUE, TRUE),
('testdeltaker4', 'deltaker4@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Anna', 'Larsen', '12345683', '1988-09-15', 'Deltakerveien 6, 0006 Oslo', 'participant', TRUE, TRUE, TRUE),
('testdeltaker5', 'deltaker5@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lars', 'Andersen', '12345684', '1995-01-30', 'Deltakerveien 7, 0007 Oslo', 'participant', TRUE, TRUE, TRUE),
('testdeltaker6', 'deltaker6@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ingrid', 'Pedersen', '12345685', '1983-06-25', 'Deltakerveien 8, 0008 Oslo', 'participant', TRUE, TRUE, TRUE),
('testdeltaker7', 'deltaker7@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bjørn', 'Olsen', '12345686', '1991-12-12', 'Deltakerveien 9, 0009 Oslo', 'participant', TRUE, TRUE, TRUE),
('testdeltaker8', 'deltaker8@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Marianne', 'Haugen', '12345687', '1987-04-18', 'Deltakerveien 10, 0010 Oslo', 'participant', TRUE, TRUE, TRUE),
('testdeltaker9', 'deltaker9@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Tom', 'Berg', '12345688', '1993-08-22', 'Deltakerveien 11, 0011 Oslo', 'participant', TRUE, TRUE, TRUE),
('testdeltaker10', 'deltaker10@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sofie', 'Kristiansen', '12345689', '1989-10-08', 'Deltakerveien 12, 0012 Oslo', 'participant', TRUE, TRUE, TRUE);

-- Insert sample competitions (mark as test data)
INSERT INTO jaktfelt_competitions (season_id, name, description, location, date, registration_deadline, status, organizer_id, is_test_data) VALUES 
(2, 'Vårstevnet 2024 (Test)', 'Første stevne i sesongen - Testdata', 'Oslo Skytebane', '2024-04-15', '2024-04-10', 'upcoming', 2, TRUE),
(2, 'Sommerstevnet 2024 (Test)', 'Andre stevne i sesongen - Testdata', 'Bergen Skytebane', '2024-06-20', '2024-06-15', 'upcoming', 2, TRUE),
(2, 'Høststevnet 2024 (Test)', 'Tredje stevne i sesongen - Testdata', 'Trondheim Skytebane', '2024-09-10', '2024-09-05', 'upcoming', 2, TRUE),
(2, 'Vinterstevnet 2024 (Test)', 'Avsluttende stevne i sesongen - Testdata', 'Stavanger Skytebane', '2024-11-25', '2024-11-20', 'upcoming', 2, TRUE);

-- Insert sample competition categories (mark as test data)
INSERT INTO jaktfelt_competition_categories (competition_id, category_id, is_test_data) VALUES 
(1, 1, TRUE), (1, 2, TRUE), (1, 3, TRUE), (1, 4, TRUE),
(2, 1, TRUE), (2, 2, TRUE), (2, 3, TRUE), (2, 4, TRUE),
(3, 1, TRUE), (3, 2, TRUE), (3, 3, TRUE), (3, 4, TRUE),
(4, 1, TRUE), (4, 2, TRUE), (4, 3, TRUE), (4, 4, TRUE);

-- Insert sample registrations (mark as test data)
INSERT INTO jaktfelt_registrations (user_id, competition_id, category_id, registration_date, status, notes, is_test_data) VALUES 
-- Vårstevnet 2024 registrations
(3, 1, 1, '2024-03-15 10:30:00', 'confirmed', 'Test registrering', TRUE),
(4, 1, 4, '2024-03-16 14:20:00', 'confirmed', 'Test registrering', TRUE),
(5, 1, 1, '2024-03-17 09:15:00', 'confirmed', 'Test registrering', TRUE),
(6, 1, 2, '2024-03-18 16:45:00', 'confirmed', 'Test registrering', TRUE),
(7, 1, 1, '2024-03-19 11:30:00', 'confirmed', 'Test registrering', TRUE),
(8, 1, 3, '2024-03-20 13:15:00', 'confirmed', 'Test registrering', TRUE),
(9, 1, 2, '2024-03-21 15:00:00', 'confirmed', 'Test registrering', TRUE),
(10, 1, 1, '2024-03-22 12:30:00', 'confirmed', 'Test registrering', TRUE),
(11, 1, 4, '2024-03-23 14:45:00', 'confirmed', 'Test registrering', TRUE),
(12, 1, 2, '2024-03-24 10:00:00', 'confirmed', 'Test registrering', TRUE),

-- Sommerstevnet 2024 registrations
(3, 2, 1, '2024-05-10 09:30:00', 'confirmed', 'Test registrering', TRUE),
(4, 2, 4, '2024-05-11 14:20:00', 'confirmed', 'Test registrering', TRUE),
(5, 2, 1, '2024-05-12 11:15:00', 'confirmed', 'Test registrering', TRUE),
(6, 2, 2, '2024-05-13 16:45:00', 'confirmed', 'Test registrering', TRUE),
(7, 2, 1, '2024-05-14 13:30:00', 'confirmed', 'Test registrering', TRUE),
(8, 2, 3, '2024-05-15 15:15:00', 'confirmed', 'Test registrering', TRUE),
(9, 2, 2, '2024-05-16 12:00:00', 'confirmed', 'Test registrering', TRUE),
(10, 2, 1, '2024-05-17 14:30:00', 'confirmed', 'Test registrering', TRUE),
(11, 2, 4, '2024-05-18 16:45:00', 'confirmed', 'Test registrering', TRUE),
(12, 2, 2, '2024-05-19 10:00:00', 'confirmed', 'Test registrering', TRUE),

-- Høststevnet 2024 registrations
(3, 3, 1, '2024-08-15 09:30:00', 'confirmed', 'Test registrering', TRUE),
(4, 3, 4, '2024-08-16 14:20:00', 'confirmed', 'Test registrering', TRUE),
(5, 3, 1, '2024-08-17 11:15:00', 'confirmed', 'Test registrering', TRUE),
(6, 3, 2, '2024-08-18 16:45:00', 'confirmed', 'Test registrering', TRUE),
(7, 3, 1, '2024-08-19 13:30:00', 'confirmed', 'Test registrering', TRUE),
(8, 3, 3, '2024-08-20 15:15:00', 'confirmed', 'Test registrering', TRUE),
(9, 3, 2, '2024-08-21 12:00:00', 'confirmed', 'Test registrering', TRUE),
(10, 3, 1, '2024-08-22 14:30:00', 'confirmed', 'Test registrering', TRUE),
(11, 3, 4, '2024-08-23 16:45:00', 'confirmed', 'Test registrering', TRUE),
(12, 3, 2, '2024-08-24 10:00:00', 'confirmed', 'Test registrering', TRUE),

-- Vinterstevnet 2024 registrations
(3, 4, 1, '2024-10-15 09:30:00', 'confirmed', 'Test registrering', TRUE),
(4, 4, 4, '2024-10-16 14:20:00', 'confirmed', 'Test registrering', TRUE),
(5, 4, 1, '2024-10-17 11:15:00', 'confirmed', 'Test registrering', TRUE),
(6, 4, 2, '2024-10-18 16:45:00', 'confirmed', 'Test registrering', TRUE),
(7, 4, 1, '2024-10-19 13:30:00', 'confirmed', 'Test registrering', TRUE),
(8, 4, 3, '2024-10-20 15:15:00', 'confirmed', 'Test registrering', TRUE),
(9, 4, 2, '2024-10-21 12:00:00', 'confirmed', 'Test registrering', TRUE),
(10, 4, 1, '2024-10-22 14:30:00', 'confirmed', 'Test registrering', TRUE),
(11, 4, 4, '2024-10-23 16:45:00', 'confirmed', 'Test registrering', TRUE),
(12, 4, 2, '2024-10-24 10:00:00', 'confirmed', 'Test registrering', TRUE);

-- Insert sample results (mark as test data)
INSERT INTO jaktfelt_results (competition_id, user_id, category_id, score, position, points_awarded, is_walk_in, notes, entered_by, entered_at, is_test_data) VALUES 
-- Vårstevnet 2024 results
(1, 3, 1, 95, 1, 100, FALSE, 'Test resultat', 2, '2024-04-15 16:30:00', TRUE),
(1, 5, 1, 92, 2, 90, FALSE, 'Test resultat', 2, '2024-04-15 16:35:00', TRUE),
(1, 7, 1, 89, 3, 80, FALSE, 'Test resultat', 2, '2024-04-15 16:40:00', TRUE),
(1, 10, 1, 87, 4, 70, FALSE, 'Test resultat', 2, '2024-04-15 16:45:00', TRUE),
(1, 4, 4, 93, 1, 100, FALSE, 'Test resultat', 2, '2024-04-15 16:50:00', TRUE),
(1, 11, 4, 91, 2, 90, FALSE, 'Test resultat', 2, '2024-04-15 16:55:00', TRUE),
(1, 6, 2, 88, 1, 100, FALSE, 'Test resultat', 2, '2024-04-15 17:00:00', TRUE),
(1, 9, 2, 85, 2, 90, FALSE, 'Test resultat', 2, '2024-04-15 17:05:00', TRUE),
(1, 12, 2, 82, 3, 80, FALSE, 'Test resultat', 2, '2024-04-15 17:10:00', TRUE),
(1, 8, 3, 90, 1, 100, FALSE, 'Test resultat', 2, '2024-04-15 17:15:00', TRUE),

-- Sommerstevnet 2024 results
(2, 3, 1, 97, 1, 100, FALSE, 'Test resultat', 2, '2024-06-20 16:30:00', TRUE),
(2, 5, 1, 94, 2, 90, FALSE, 'Test resultat', 2, '2024-06-20 16:35:00', TRUE),
(2, 7, 1, 91, 3, 80, FALSE, 'Test resultat', 2, '2024-06-20 16:40:00', TRUE),
(2, 10, 1, 88, 4, 70, FALSE, 'Test resultat', 2, '2024-06-20 16:45:00', TRUE),
(2, 4, 4, 95, 1, 100, FALSE, 'Test resultat', 2, '2024-06-20 16:50:00', TRUE),
(2, 11, 4, 92, 2, 90, FALSE, 'Test resultat', 2, '2024-06-20 16:55:00', TRUE),
(2, 6, 2, 89, 1, 100, FALSE, 'Test resultat', 2, '2024-06-20 17:00:00', TRUE),
(2, 9, 2, 86, 2, 90, FALSE, 'Test resultat', 2, '2024-06-20 17:05:00', TRUE),
(2, 12, 2, 83, 3, 80, FALSE, 'Test resultat', 2, '2024-06-20 17:10:00', TRUE),
(2, 8, 3, 91, 1, 100, FALSE, 'Test resultat', 2, '2024-06-20 17:15:00', TRUE),

-- Høststevnet 2024 results
(3, 3, 1, 96, 1, 100, FALSE, 'Test resultat', 2, '2024-09-10 16:30:00', TRUE),
(3, 5, 1, 93, 2, 90, FALSE, 'Test resultat', 2, '2024-09-10 16:35:00', TRUE),
(3, 7, 1, 90, 3, 80, FALSE, 'Test resultat', 2, '2024-09-10 16:40:00', TRUE),
(3, 10, 1, 87, 4, 70, FALSE, 'Test resultat', 2, '2024-09-10 16:45:00', TRUE),
(3, 4, 4, 94, 1, 100, FALSE, 'Test resultat', 2, '2024-09-10 16:50:00', TRUE),
(3, 11, 4, 91, 2, 90, FALSE, 'Test resultat', 2, '2024-09-10 16:55:00', TRUE),
(3, 6, 2, 88, 1, 100, FALSE, 'Test resultat', 2, '2024-09-10 17:00:00', TRUE),
(3, 9, 2, 85, 2, 90, FALSE, 'Test resultat', 2, '2024-09-10 17:05:00', TRUE),
(3, 12, 2, 82, 3, 80, FALSE, 'Test resultat', 2, '2024-09-10 17:10:00', TRUE),
(3, 8, 3, 89, 1, 100, FALSE, 'Test resultat', 2, '2024-09-10 17:15:00', TRUE),

-- Vinterstevnet 2024 results
(4, 3, 1, 98, 1, 100, FALSE, 'Test resultat', 2, '2024-11-25 16:30:00', TRUE),
(4, 5, 1, 95, 2, 90, FALSE, 'Test resultat', 2, '2024-11-25 16:35:00', TRUE),
(4, 7, 1, 92, 3, 80, FALSE, 'Test resultat', 2, '2024-11-25 16:40:00', TRUE),
(4, 10, 1, 89, 4, 70, FALSE, 'Test resultat', 2, '2024-11-25 16:45:00', TRUE),
(4, 4, 4, 96, 1, 100, FALSE, 'Test resultat', 2, '2024-11-25 16:50:00', TRUE),
(4, 11, 4, 93, 2, 90, FALSE, 'Test resultat', 2, '2024-11-25 16:55:00', TRUE),
(4, 6, 2, 90, 1, 100, FALSE, 'Test resultat', 2, '2024-11-25 17:00:00', TRUE),
(4, 9, 2, 87, 2, 90, FALSE, 'Test resultat', 2, '2024-11-25 17:05:00', TRUE),
(4, 12, 2, 84, 3, 80, FALSE, 'Test resultat', 2, '2024-11-25 17:10:00', TRUE),
(4, 8, 3, 92, 1, 100, FALSE, 'Test resultat', 2, '2024-11-25 17:15:00', TRUE);

-- Insert sample point systems (mark as test data)
INSERT INTO jaktfelt_point_systems (name, description, is_active, is_test_data) VALUES 
('Standard Test', 'Standard poengsystem for testdata: 1.plass=100p, 2.plass=90p, osv.', TRUE, TRUE);

-- Insert sample point rules (mark as test data)
INSERT INTO jaktfelt_point_rules (point_system_id, position, points, is_test_data) VALUES 
(2, 1, 100, TRUE),
(2, 2, 90, TRUE),
(2, 3, 80, TRUE),
(2, 4, 70, TRUE),
(2, 5, 60, TRUE),
(2, 6, 50, TRUE),
(2, 7, 40, TRUE),
(2, 8, 30, TRUE),
(2, 9, 20, TRUE),
(2, 10, 10, TRUE);

-- Insert sample season point system assignment (mark as test data)
INSERT INTO jaktfelt_season_point_systems (season_id, point_system_id, is_test_data) VALUES 
(2, 2, TRUE);

-- Insert sample notifications (mark as test data)
INSERT INTO jaktfelt_notifications (user_id, type, subject, message, status, sent_at, is_test_data) VALUES 
(3, 'email', 'Velkommen til Vårstevnet 2024', 'Hei Ole! Du er påmeldt Vårstevnet 2024. Stevnet starter 15. april kl. 09:00.', 'sent', '2024-04-01 10:00:00', TRUE),
(4, 'email', 'Velkommen til Vårstevnet 2024', 'Hei Kari! Du er påmeldt Vårstevnet 2024. Stevnet starter 15. april kl. 09:00.', 'sent', '2024-04-01 10:00:00', TRUE),
(5, 'email', 'Velkommen til Vårstevnet 2024', 'Hei Erik! Du er påmeldt Vårstevnet 2024. Stevnet starter 15. april kl. 09:00.', 'sent', '2024-04-01 10:00:00', TRUE);

-- Insert sample audit log entries (mark as test data)
INSERT INTO jaktfelt_audit_log (user_id, table_name, record_id, action, old_values, new_values, ip_address, user_agent, is_test_data) VALUES 
(2, 'jaktfelt_competitions', 1, 'create', NULL, '{"name":"Vårstevnet 2024 (Test)","location":"Oslo Skytebane"}', '127.0.0.1', 'Mozilla/5.0 (Test Browser)', TRUE),
(2, 'jaktfelt_results', 1, 'create', NULL, '{"score":95,"position":1}', '127.0.0.1', 'Mozilla/5.0 (Test Browser)', TRUE),
(2, 'jaktfelt_results', 2, 'create', NULL, '{"score":92,"position":2}', '127.0.0.1', 'Mozilla/5.0 (Test Browser)', TRUE);
