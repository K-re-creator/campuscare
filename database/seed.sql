USE campuscare;

-- Clean existing mock data rows cleanly
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE bookings;
TRUNCATE TABLE services;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Insert Initial User Profiles (Password hashes matched to 'password123' via PHP)
INSERT INTO users (id, email, password_hash, role) VALUES
(1, 'student@example.test', '$2y$10$wK7lAnb63v.mbeG6zXN1YeC38Tf7Lcl1/mXQ.1B/7j6L2tS1P2W2a', 'customer'),
(2, 'staff@example.test', '$2y$10$wK7lAnb63v.mbeG6zXN1YeC38Tf7Lcl1/mXQ.1B/7j6L2tS1P2W2a', 'staff'),
(3, 'admin@example.test', '$2y$10$wK7lAnb63v.mbeG6zXN1YeC38Tf7Lcl1/mXQ.1B/7j6L2tS1P2W2a', 'admin');

-- 2. Insert Core Service Offerings
INSERT INTO services (id, name, duration_minutes) VALUES
(1, 'Academic Advising', 45),
(2, 'IT Support Counter', 15),
(3, 'Student Health Clinic', 30);

-- 3. Insert Baseline Bookings Relationships
INSERT INTO bookings (user_id, service_id, starts_at, status, notes) VALUES
(1, 1, '2026-11-10 09:00:00', 'confirmed', 'Discussing faculty path planning support.'),
(1, 2, '2026-11-12 10:30:00', 'pending', 'Hardware diagnostics checkout configuration.'),
(2, 3, '2026-11-15 14:00:00', 'completed', 'Standard checkup consultation.');
