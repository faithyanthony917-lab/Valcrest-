# Valcrest Meridian Capital - Project README

**Version:** 1.0  
**Status:** Ready for Hostinger deployment  
**Architecture:** Static public pages + PHP/MVC portal/admin  
**Database:** MySQL with PDO  
**Session:** PHP with CSRF protection  

---

## Overview

Valcrest Meridian Capital is a wealth management and investment platform that combines:

1. **Public Static Pages** – Corporate branding, service descriptions, contact info
2. **Authentication System** – Secure registration, login, password reset
3. **User Portal** – Dashboard, wallet management, investments, transactions, support tickets
4. **Admin Console** – Request approval, user management, investment monitoring
5. **Investment Engine** – Database-driven plans, wallet debit, automated release on maturity
6. **Financial Ledger** – Unified transaction history for all money movement

---

## Project Structure

```
public_html/
├── .htaccess                          # Apache mod_rewrite rules for clean URLs
├── index.html                         # Home page (static)
├── styles.css                         # Global styles
├── script.js                          # Global scripts
│
├── pages/                             # Static public pages
│   ├── about-us/index.html
│   ├── agriculture/index.html
│   ├── our-portfolio/index.html
│   ├── investment-management/index.html
│   └── ... (other service pages)
│
├── auth/                              # Authentication pages (static HTML)
│   ├── login/index.html
│   ├── register/index.html
│   ├── forgot-password/index.html
│   └── reset-password/index.html
│
├── api/                               # RESTful API endpoints (PHP)
│   ├── auth/
│   │   ├── register.php               # POST: Create account
│   │   ├── login.php                  # POST: Authenticate user
│   │   ├── logout.php                 # POST: Clear session
│   │   ├── change-password.php        # POST: Update password
│   │   ├── forgot-password.php        # POST: Request password reset
│   │   ├── reset-password.php         # POST: Confirm password reset
│   │   ├── csrf.php                   # GET: Retrieve CSRF token
│   │   └── me.php                     # GET: Current user details
│   │
│   ├── user/                          # Authenticated user endpoints
│   │   ├── dashboard.php              # GET: Dashboard summary
│   │   ├── transactions.php           # GET: Transaction history
│   │   ├── financial-request.php      # POST: Request deposit/withdrawal
│   │   ├── deposits.php               # GET: Deposit requests
│   │   ├── withdrawals.php            # GET: Withdrawal requests
│   │   ├── transfers.php              # POST: Transfer between users
│   │   ├── support-tickets.php        # POST/GET: Support tickets
│   │   ├── investments.php            # GET: User's investments
│   │   ├── investment-create.php      # POST: Create investment (debit wallet)
│   │   └── investment-cancel.php      # POST: Cancel active investment
│   │
│   └── admin/                         # Admin-only endpoints
│       ├── login.php                  # POST: Admin authentication
│       ├── request-action.php         # POST: Approve/reject financial request
│       ├── support-tickets.php        # GET/POST: Manage tickets
│       ├── users.php                  # GET: List users
│       └── investments.php            # GET: View investment portfolio
│
├── portal/                            # Authenticated user portal (PHP)
│   ├── index.php                      # Portal redirect/guard
│   ├── dashboard/
│   │   └── index.php                  # Main dashboard (wallet, stats, menu)
│   ├── investments/
│   │   ├── index.php                  # List investment plans
│   │   ├── preview.php                # Confirm investment & "Invest Now"
│   │   └── logs.php                   # View user's investments
│   ├── deposits/
│   │   ├── index.php                  # Request deposit
│   │   └── logs.php                   # View deposit requests
│   ├── withdrawals/
│   │   ├── index.php                  # Request withdrawal
│   │   └── logs.php                   # View withdrawal requests
│   ├── transfers/
│   │   ├── index.php                  # Transfer to another user
│   │   └── logs.php                   # View transfer history
│   ├── transactions/
│   │   └── index.php                  # Unified ledger view
│   ├── support/
│   │   └── index.php                  # Create/view support tickets
│   ├── settings/
│   │   ├── index.php                  # Account settings
│   │   └── change-password.php        # Change password form
│   ├── referral/
│   │   └── index.php                  # Referral program (if enabled)
│   ├── notifications/
│   │   └── index.php                  # User notifications
│   └── wallet/
│       └── exchange.php               # Wallet exchange (if enabled)
│
├── admin/                             # Admin console (PHP)
│   ├── index.php                      # Admin dashboard
│   └── login.html                     # Admin login redirect
│
├── backend/                           # Core backend logic
│   ├── src/
│   │   ├── bootstrap.php              # Core initialization (PDO, session, CSRF, auth)
│   │   ├── database.php               # Database utility functions
│   │   ├── helpers.php                # Helper functions (formatting, validation)
│   │   └── mailer.php                 # Email sending (SMTP)
│   ├── config/
│   │   ├── config.php                 # Live credentials (DO NOT commit to git)
│   │   ├── config.example.php         # Example config template
│   │   └── .htaccess                  # Deny direct access to config/
│   ├── schema.sql                     # Initial database schema
│   ├── migration-investments.sql      # Migration for investment & wallet types
│   ├── admin-seed.example.sql         # Optional: seed admin user
│   └── README.txt                     # Backend documentation
│
├── cron/                              # Background jobs
│   └── release-investments.php        # Cron: Release matured investments
│
├── assets/                            # Static assets
│   ├── css/
│   │   ├── site-navigation.css        # Menu & hamburger compatibility CSS
│   │   ├── portal-dashboard.css       # Portal-specific styles
│   │   └── brand.css                  # Brand colors & typography
│   ├── js/
│   │   ├── site-navigation.js         # Menu & hamburger JS
│   │   ├── portal-actions.js          # Portal AJAX handlers
│   │   ├── portal.js                  # Portal utilities
│   │   ├── change-password.js         # Password change form
│   │   └── utils.js                   # Common utilities
│   ├── images/
│   │   └── favicon.png
│   ├── frontend/                      # Captured WordPress frontend assets
│   │   ├── css/
│   │   ├── js/
│   │   └── ... (vendor libraries)
│   └── global/                        # Captured WordPress global assets
│       ├── css/
│       ├── js/
│       └── ... (vendor libraries)
│
├── wp-content/                        # WordPress legacy media
│   └── uploads/
│       └── ... (images, videos used by captured pages)
├── wp-includes/                       # WordPress legacy styles/scripts
├── share/                             # Legacy redirect folder
│   ├── auth.css                       # Auth page styling
│   └── ... (legacy assets)
│
├── storage/                           # Runtime storage
│   └── (empty, for logs/temp files if needed)
│
├── legacy/                            # Old content (kept for reference)
│   └── ... (archived pages)
│
└── DEPLOYMENT_CHECKLIST.md            # Step-by-step deployment guide
```

---

## Key Architecture Decisions

### 1. Static Pages + PHP Backend

**Why:** Preserves the original corporate landing pages while adding dynamic portal/admin functionality.

- Static HTML pages (pages/, index.html) are served as-is for SEO and fast load times
- PHP pages (api/, portal/, admin/) handle dynamic logic and database interaction
- Clean URLs via `.htaccess` rewrite rules (e.g., `/portal/dashboard/` → `portal/dashboard/index.php`)

### 2. Session-Based Authentication

**Security:**
- HTTP-only cookies (no JavaScript access)
- SameSite=Lax (CSRF protection)
- Secure flag when HTTPS is enabled
- Password hashing with `password_hash()` / `password_verify()`
- CSRF tokens on all state-changing API calls

**Flow:**
1. User registers → account created, wallet initialized
2. User logs in → session set, user ID stored
3. Protected pages/APIs check `require_page_auth()` or `require_api_auth()`
4. User logs out → session destroyed

### 3. Wallet System

**Dual Wallets per User per Currency:**
- `main` wallet – for deposits and withdrawals
- `profit` wallet – for investment returns

**Why:** Simplifies accounting; profit can be invested separately or withdrawn.

**Flow:**
1. User registers → main + profit wallets created (GBP)
2. User deposits → main wallet credited (after admin approval)
3. User invests → main wallet debited, investment record created
4. Investment matures → profit wallet credited with principal + return

### 4. Investment Model

**Database-Driven Plans:**
- Plan ID, name, description, minimum/maximum, return rate, term, capital-back flag
- All plans stored in `investment_plans` table
- Admin can add/edit plans without code changes

**Investment Lifecycle:**
1. User selects plan from portal
2. User chooses amount (within min/max) and wallet
3. "Invest Now" button → investment record created, wallet debited
4. Investment stored with maturity date (start_date + term_days)
5. Cron job checks for matured investments every hour
6. On maturity → profit wallet credited, investment marked "released"

### 5. Unified Ledger

**All transactions recorded:**
- Deposits, withdrawals, transfers, investment creation, investment release, support ticket fees (if any)
- Ledger shows: user, wallet, type, amount, status, description, timestamp
- Used for audit trail and transaction history reporting

### 6. Admin Approval System

**Financial Requests:**
1. User requests deposit/withdrawal
2. Request stored with `status = 'pending'`
3. Admin reviews in `/admin/`
4. Admin approves → wallet credited/debited, status = 'approved'
5. Admin rejects → request cancelled, ledger notes reason

**Support Tickets:**
- User creates ticket
- Admin can reply and resolve
- Ticket history preserved for compliance

### 7. Clean URL Routing

**Apache .htaccess rules:**
- Public pages: `/about-us/` → `pages/about-us/index.html`
- Auth: `/auth/login/` → `auth/login/index.html` (static) or `/api/auth/login.php` (API)
- Portal: `/portal/dashboard/` → `portal/dashboard/index.php`
- Admin: `/admin/` → `admin/index.php`
- Legacy redirects: `/share/user/dashboard/` → `/portal/dashboard/` (301)

**Why:** SEO-friendly, no `.php` extensions visible, better user experience.

---

## Database Schema Overview

### users
- id, first_name, last_name, email, username, country, password_hash, role (user/admin), status (active/suspended), created_at, updated_at

### accounts (wallets)
- id, user_id, currency (GBP), wallet_type (main/profit), available_balance, created_at, updated_at
- Unique constraint: (user_id, currency, wallet_type)

### investment_plans
- id, name, description, currency, minimum_amount, maximum_amount, return_rate, term_days, capital_back, status, created_at

### investments
- id, user_id, plan_id, source_account_id, profit_account_id, principal, expected_return, currency, starts_at, matures_at, status (active/released/cancelled), reference, released_at, created_at

### financial_requests
- id, user_id, account_id, type (deposit/withdrawal), amount, currency, status (pending/approved/rejected/cancelled), reference, notes, created_at, updated_at

### ledger_transactions
- id, user_id, account_id, type (deposit, withdrawal, transfer, investment_create, investment_release, transfer_out, etc.), amount, currency, status, description, created_at

### support_tickets
- id, user_id, subject, message, status (open/resolved), created_at, updated_at

### password_reset_tokens
- id, user_id, token_hash, expires_at, used_at, created_at

---

## API Reference

### Authentication Endpoints

#### POST /api/auth/register
**Body:** `{ "first_name", "last_name", "email", "username", "password", "password_confirm", "country" }`  
**Returns:** `{ "success": true }` or error  
**Creates:** User account, main wallet, profit wallet

#### POST /api/auth/login
**Body:** `{ "email", "password" }`  
**Returns:** `{ "user": {...} }` or error  
**Sets:** Session cookie

#### POST /api/auth/logout
**Returns:** `{ "success": true }`  
**Clears:** Session

#### GET /api/auth/csrf
**Returns:** `{ "csrf": "token_value" }`  
**Used:** To get CSRF token before form submission

#### GET /api/auth/me
**Returns:** Current user object or `{ "authenticated": false }`

### User Endpoints (Authenticated)

#### GET /api/user/dashboard
**Returns:** `{ "user": {...}, "balance": "1000.00", "transactions_count": 5, "tickets_count": 2 }`

#### GET /api/user/transactions
**Returns:** `{ "transactions": [...] }`

#### POST /api/user/financial-request
**Body:** `{ "type": "deposit" | "withdrawal", "amount", "currency" }`  
**Returns:** `{ "reference": "..." }`

#### GET /api/user/investments
**Returns:** `{ "investments": [...] }`

#### POST /api/user/investment-create
**Body:** `{ "planId", "amount", "accountId" }`  
**Returns:** `{ "investment_id": 123, "reference": "INV-..." }`  
**Effect:** Debit wallet, create investment, log transaction

### Admin Endpoints

#### POST /api/admin/request-action
**Body:** `{ "request_id", "action": "approve" | "reject", "notes" }`  
**Returns:** `{ "success": true }`  
**Effect:** Update request status, credit/debit wallet

#### GET /api/admin/investments
**Returns:** `{ "investments": [...] }`

---

## Security Checklist

- [x] Passwords hashed with `password_hash()` (PHP 7.2+)
- [x] CSRF tokens required on POST/PUT/DELETE
- [x] HTTP-only cookies (no XSS JavaScript access)
- [x] SameSite=Lax cookie flag
- [x] Secure flag on HTTPS
- [x] PDO prepared statements (no SQL injection)
- [x] Input validation on all endpoints
- [x] Output encoding with `htmlspecialchars()` (no XSS in HTML)
- [x] Admin role check on admin APIs
- [x] Transactional updates for financial operations
- [x] Config file out of webroot (in backend/config/)
- [x] Password reset tokens are single-use and time-limited
- [x] Email confirmation (optional, can be added)

---

## Running Locally (Development)

### Prerequisites
- PHP 7.4+ with PDO MySQL extension
- MySQL 5.7+ or MariaDB
- Apache with mod_rewrite enabled

### Setup

```bash
# 1. Copy config template
cp backend/config/config.example.php backend/config/config.php

# 2. Update config.php with local DB credentials
# Edit: dsn, username, password

# 3. Create database
mysql -u root -p -e "CREATE DATABASE valcrest;"

# 4. Import schema
mysql -u root -p valcrest < backend/schema.sql

# 5. Seed investment plans (optional)
mysql -u root -p valcrest < backend/migration-investments.sql

# 6. Start Apache & MySQL
# (via XAMPP, WAMP, or system services)

# 7. Visit http://localhost/public_html/
```

---

## Deployment to Hostinger

**See:** `DEPLOYMENT_CHECKLIST.md` for complete step-by-step instructions.

**TL;DR:**
1. Back up current site & DB
2. Upload `public_html/` contents to Hostinger public_html/
3. Update `backend/config/config.php` with live credentials
4. Import `backend/migration-investments.sql` in phpMyAdmin
5. Create cron job: `/usr/bin/php /home/u103464132/public_html/cron/release-investments.php` (hourly)
6. Test all flows (register, login, deposit, investment, etc.)

---

## Maintenance & Monitoring

### Regular Tasks

- **Daily:** Check error logs in Hostinger
- **Weekly:** Review support tickets and pending requests
- **Monthly:** Backup database and verify cron job ran
- **Quarterly:** Review investment maturity dates and release logs

### Logs

- PHP errors: Hostinger Control Panel → Logs
- Cron output: Hostinger Control Panel → Cron Jobs (email notifications)
- Database: phpMyAdmin → Server logs (if available)

### Useful SQL Queries

```sql
-- Check pending financial requests
SELECT * FROM financial_requests WHERE status = 'pending';

-- Check active investments nearing maturity
SELECT * FROM investments WHERE status = 'active' AND matures_at < DATE_ADD(NOW(), INTERVAL 7 DAY);

-- Check total user transactions
SELECT user_id, COUNT(*) as tx_count FROM ledger_transactions GROUP BY user_id;

-- Audit investment releases (cron activity)
SELECT * FROM investments WHERE status = 'released' ORDER BY released_at DESC LIMIT 20;

-- Check admin users
SELECT * FROM users WHERE role = 'admin';
```

---

## Troubleshooting

### Portal pages return 404
- Check `.htaccess` exists in public_html root
- Verify Apache mod_rewrite is enabled
- Check rewrite rule syntax matches URLs

### Database connection error
- Verify config.php credentials
- Test MySQL connection via phpMyAdmin
- Check database exists in Hostinger

### Cron job not running
- Verify command path in Hostinger Cron Jobs
- Check file permissions (should be readable by web server)
- Test manually: `php cron/release-investments.php`

### CSS/JS not loading
- Clear browser cache
- Check file paths in HTML (relative vs absolute)
- Verify assets/ folder structure on server

### Investment not being released
- Check if investment matures_at <= current timestamp
- Verify profit wallet exists
- Check cron job ran (check email notification)
- Query: `SELECT * FROM investments WHERE id = <id>;`

---

## Future Enhancements

- Email notifications (registration, investment maturity, approval)
- 2FA (two-factor authentication)
- API rate limiting
- User referral system (partially stubbed)
- Wallet exchange (partially stubbed)
- KYC/AML verification
- Multiple currencies (USD, EUR, etc.)
- Mobile app API

---

## Support & Documentation

- **Project:** Valcrest Meridian Capital
- **Version:** 1.0
- **Status:** Production-ready
- **Last Updated:** Today
- **Maintainer:** Your Team

For questions or issues, refer to `DEPLOYMENT_CHECKLIST.md` or contact your hosting provider.
