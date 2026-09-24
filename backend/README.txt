Valcrest PHP/MySQL backend

Setup on a hosted PHP server:

1. Create a MySQL database and user.
2. Import backend/schema.sql with the database user.
3. Copy backend/config/config.example.php to backend/config/config.php.
4. Put the real database DSN, username, password, and production origin in config.php.
5. Deploy the project so /api and /share are served by the same HTTPS origin.
6. Keep backend/config/config.php outside public web access when the hosting provider supports a private directory.

The backend supports account registration, login, logout, CSRF tokens, password hashing, and session-based identity. It also provides authenticated dashboard balance/summary and transaction endpoints, plus deposit and withdrawal request endpoints. Requests remain pending and do not alter balances; payment processing and administrator approval must be implemented before production use.

API endpoints:

- GET /api/auth/csrf.php
- POST /api/auth/register.php
- POST /api/auth/login.php
- POST /api/auth/logout.php
- POST /api/auth/forgot-password.php
- POST /api/auth/reset-password.php
- GET /api/user/dashboard.php
- GET /api/user/transactions.php
- POST /api/user/financial-request.php with {"type":"deposit"|"withdrawal","amount":"100.00","notes":"optional"}

The schema is intended to be imported as a fresh database. If you already imported an earlier version, run backend/migration-investments.sql instead of importing schema.sql again. Back up the database first.

Admin:

- Admin access starts at /share/login/. The /admin/login.html route now directs administrators to the shared account login.
- Admin console: /admin/
- Create the first administrator manually using backend/admin-seed.example.sql after generating a password hash.
- Never add public admin registration.
- Review requests carefully; approval changes the user's balance and creates a completed ledger entry inside a database transaction.
- All users now use /share/login/. Active administrators see an Admin console link in the user dashboard after signing in.
- Password reset uses PHPMailer over SMTP. Run `composer install`, configure the SMTP values in backend/config/config.php, and import the password_reset_tokens table from schema.sql.
- User dashboard pages are protected PHP routes. Configure the server to execute PHP and do not publish the original captured HTML files.
- Apache clean URLs are configured in the project-root .htaccess. Enable mod_rewrite and allow .htaccess overrides (AllowOverride All) on the hosted domain.
- Public pages use folder URLs such as /about-us/ and /contact/; direct index.html and page.html requests redirect to their clean folder URLs.
- Investment plans and investment records are database-backed at /portal/investments/ and /portal/investments/logs/. The selected main wallet is debited when an investment is confirmed, and the expected return is recorded in the investment log.
- Configure a Hostinger cron job to run `php /home/ACCOUNT/domains/DOMAIN/public_html/cron/release-investments.php` at least hourly. Matured investments are released once, credited to the user's profit wallet, and added to the unified ledger as `investment_release`.
