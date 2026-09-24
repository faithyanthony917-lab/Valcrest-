USE valcrest;

-- Generate a password hash with PHP:
-- php -r "echo password_hash('REPLACE_WITH_A_LONG_RANDOM_PASSWORD', PASSWORD_DEFAULT), PHP_EOL;"
INSERT INTO users (first_name, last_name, email, username, country, password_hash, role)
VALUES ('System', 'Administrator', 'admin@example.com', 'administrator', 'United Kingdom', 'REPLACE_WITH_GENERATED_HASH', 'admin');
