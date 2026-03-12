-- ═══════════════════════════════════════════
-- Rooms seed
-- ═══════════════════════════════════════════
INSERT INTO rooms (room_number) VALUES
    ('101'),
    ('102'),
    ('103'),
    ('104'),
    ('105'),
    ('201'),
    ('202'),
    ('203'),
    ('204'),
    ('205'),
    ('301'),
    ('302'),
    ('303'),
    ('304'),
    ('305'),
    ('Conference A'),
    ('Conference B'),
    ('Server Room'),
    ('Reception'),
    ('Manager Office');


-- ═══════════════════════════════════════════
-- Admin user seed
-- Password: Admin@1234
-- Hash generated with PASSWORD_BCRYPT
-- ═══════════════════════════════════════════
INSERT INTO users (name, email, password, role, is_active)
VALUES (
    'Super Admin',
    'admin@admin.com',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    1
);


-- ═══════════════════════════════════════════
-- NOTE: The password hash above is a bcrypt
-- hash of:  Admin@1234
--
-- To generate a fresh hash in PHP:
--   echo password_hash('Admin@1234', PASSWORD_BCRYPT);
--
-- Change the password after first login.
-- ═══════════════════════════════════════════