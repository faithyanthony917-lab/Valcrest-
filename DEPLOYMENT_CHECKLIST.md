# Valcrest Meridian Capital - Deployment Checklist

**Current Status:** Local development complete. Ready for Hostinger deployment.

**Target Server:** Hostinger  
**Database:** u103464132_valcrest  
**Domain:** valcrestmeridiancapital.com  
**Project Root:** public_html/  

---

## Pre-Deployment Verification

- [ ] All files are present in `public_html/` (local)
- [ ] `.htaccess` is properly configured for clean URLs and routing
- [ ] Backend config file (`backend/config/config.php`) has placeholder passwords (to be filled on live)
- [ ] Database schema files exist (`backend/schema.sql`, `backend/migration-investments.sql`)
- [ ] Cron script is ready (`cron/release-investments.php`)
- [ ] All API endpoints are in place (`api/auth/*`, `api/user/*`, `api/admin/*`)
- [ ] All portal pages are in place (`portal/dashboard/`, `portal/investments/`, etc.)
- [ ] Static public pages are intact (`pages/`, `index.html`, `auth/login/`, etc.)
- [ ] WordPress/legacy asset directories exist (`wp-content/`, `wp-includes/`, `share/`)
- [ ] Menu and navigation assets are in place (`assets/css/site-navigation.css`, `assets/js/site-navigation.js`)

---

## Step 1: Backup on Hostinger

**Action:** Back up your current live site and database before uploading.

```bash
# Via Hostinger Control Panel:
# 1. Go to File Manager
# 2. Right-click on public_html → Compress/Download
# 3. Note: Save the backup locally for safety

# Via Database:
# 1. Go to phpMyAdmin
# 2. Select database: u103464132_valcrest
# 3. Export (keep current schema)
# 4. Save the exported file locally
```

---

## Step 2: Upload Project to Hostinger

**Action:** Upload the local `public_html/` folder contents to your live server's `public_html/`.

**Important:** Upload the **contents** of `public_html/`, not the folder itself. On Hostinger:
- Do NOT create a new folder like `public_html/vriv/`
- Upload all files directly into the existing `public_html/` directory
- This preserves the domain's root behavior

### Upload Method (choose one):

**Option A: File Manager (Hostinger Control Panel)**
1. Log in to Hostinger Control Panel
2. Go to **File Manager**
3. Navigate to **public_html/**
4. Delete the old contents (or keep as backup in a subfolder like `_old/`)
5. Upload all files and folders from your local `public_html/`
6. Verify folder structure matches locally

**Option B: SFTP**
```bash
# Using an SFTP client (e.g., FileZilla, WinSCP):
sftp://your-ftp-user@your-ftp-host
# Navigate to public_html/
# Upload all local public_html/ contents
```

**Option C: Command Line (if SSH available)**
```bash
# On your local machine:
scp -r /path/to/public_html/* user@host:/home/user/public_html/
```

---

## Step 3: Restore Database Credentials

**Action:** Update `backend/config/config.php` on the live server with real Hostinger database credentials.

**Via File Manager:**
1. Navigate to `public_html/backend/config/`
2. Right-click `config.php` → Edit
3. Replace:
   ```php
   'password' => 'replace_with_database_password',
   ```
   with your real Hostinger database password (from Hostinger Control Panel → MySQL Databases)

4. Update if needed:
   ```php
   'allowed_origin' => 'https://valcrestmeridiancapital.com',
   'base_url' => 'https://valcrestmeridiancapital.com',
   ```

5. Save and close

---

## Step 4: Import Database Migration

**Action:** Run the migration script in phpMyAdmin to add investment tables and wallet types to your existing database.

**Via phpMyAdmin:**
1. Log in to Hostinger Control Panel → phpMyAdmin
2. Select database: **u103464132_valcrest**
3. Click **Import** tab
4. Click **Choose File**, select `backend/migration-investments.sql` (from your local machine)
5. Click **Go**
6. Wait for completion (should show success message)

**What this does:**
- Adds `wallet_type` column to `accounts` table if missing
- Creates `investment_plans` table (if not exists)
- Creates `investments` table (if not exists)
- Updates indexes for wallet type separation
- Preserves all existing user/wallet/request/ledger data

---

## Step 5: Create Investment Plans (Optional)

If no investment plans exist in the database, seed them via phpMyAdmin:

**Via phpMyAdmin → SQL tab:**
```sql
INSERT INTO investment_plans 
  (name, description, currency, minimum_amount, maximum_amount, return_rate, term_days, capital_back, status)
VALUES
  ('30-Day Silver Plan', 'Secure short-term investment', 'GBP', 1000.00, 50000.00, 5.00, 30, 1, 'active'),
  ('90-Day Gold Plan', 'Medium-term premium plan', 'GBP', 5000.00, 100000.00, 8.50, 90, 1, 'active'),
  ('365-Day Diamond Plan', 'Long-term wealth building', 'GBP', 10000.00, NULL, 12.00, 365, 1, 'active');
```

---

## Step 6: Configure Cron Job for Investment Release

**Action:** Set up a cron job to automatically release matured investments.

**Via Hostinger Control Panel:**
1. Go to **Cron Jobs** (under Advanced → Cron Jobs)
2. Create a new cron job with:
   - **Email for notifications:** your@email.com
   - **Command:**
     ```
     /usr/bin/php /home/u103464132/public_html/cron/release-investments.php
     ```
   - **Frequency:** Every hour (or as desired)
     - Common choice: **Run every 1 hour**
     - Or: Custom: `0 * * * *` (every hour at minute 0)

3. Click **Add**

**What this does:**
- Checks for investments that have reached their maturity date
- Credits the profit wallet with principal + expected return
- Updates investment status to "released"
- Logs the transaction in the ledger
- Runs automatically on schedule

---

## Step 7: Live Smoke Test

**Action:** Test critical functionality on the live site.

### Public Pages
- [ ] Home page loads: `https://valcrestmeridiancapital.com/`
- [ ] Navigation works (hamburger on mobile, dropdowns)
- [ ] Portfolio page loads: `/our-portfolio/`
- [ ] Images and CSS render properly

### Authentication
- [ ] Register page loads: `/auth/register/`
- [ ] Login page loads: `/auth/login/`
- [ ] Create a test account (register)
- [ ] Log in with test account
- [ ] Verify main + profit wallets created
- [ ] Log out works

### Portal Dashboard
- [ ] Dashboard loads: `/portal/dashboard/`
- [ ] Shows correct balance
- [ ] Shows transaction count
- [ ] Shows support ticket count
- [ ] "Admin Console" button visible if admin account

### Financial Flows
- [ ] **Deposit:** Navigate to `/portal/deposits/`, request deposit, verify ledger entry
- [ ] **Admin Approval:** Log in as admin, approve/reject the deposit request
- [ ] **Withdrawal:** Request withdrawal, admin approve, verify profit wallet decreases
- [ ] **Transfer:** Make a transfer between users/wallets, check ledger
- [ ] **Support Ticket:** Create support ticket, verify in admin console

### Investments
- [ ] Investments page loads: `/portal/investments/`
- [ ] Plans display from database
- [ ] Click plan → confirmation page
- [ ] Enter amount → expected return calculates correctly
- [ ] Select wallet → "Invest Now"
- [ ] Verify investment recorded in DB
- [ ] Verify wallet debited
- [ ] Check investment logs page
- [ ] Wait for cron to release (or manually trigger via CLI)
- [ ] Verify profit wallet credited

### Admin Console
- [ ] Admin dashboard loads: `/admin/`
- [ ] Lists pending requests/tickets
- [ ] Can approve/reject financial requests
- [ ] Can view investment portfolio
- [ ] Can access user list

### Transactions History
- [ ] Unified transaction log shows all money movement: `/portal/transactions/`
- [ ] Filter by type/date if implemented

---

## Step 8: Cleanup & Final Checks

- [ ] Remove any test accounts or mark as inactive
- [ ] Verify backup still available locally
- [ ] Check error logs for any PHP warnings/errors (use Hostinger logs)
- [ ] Test site on mobile (responsive design)
- [ ] Verify HTTPS certificate is active
- [ ] Test API responses via curl (optional, for advanced users)

---

## Troubleshooting

### Issue: 404 on portal pages
**Solution:** 
1. Check `.htaccess` is in `public_html/` root
2. Verify Apache `mod_rewrite` is enabled on Hostinger (usually is by default)
3. Check clean URL rewrite rules in `.htaccess`

### Issue: Database connection error
**Solution:**
1. Verify credentials in `backend/config/config.php`
2. Check database name is `u103464132_valcrest`
3. Confirm password is correct in Hostinger Control Panel → MySQL Databases
4. Try connecting via phpMyAdmin first

### Issue: Cron job not running
**Solution:**
1. Verify cron command path is correct: `/usr/bin/php /home/u103464132/public_html/cron/release-investments.php`
2. Check Hostinger cron logs
3. Manually test via SSH:
   ```bash
   php /home/u103464132/public_html/cron/release-investments.php
   ```

### Issue: CSS/JS not loading
**Solution:**
1. Check `.htaccess` isn't blocking `assets/` directory
2. Verify `assets/css/` and `assets/js/` folders exist on live server
3. Check browser console for 404 errors
4. Ensure correct relative paths in HTML files

### Issue: Investment plans not showing
**Solution:**
1. Verify migration was imported successfully
2. In phpMyAdmin, run: `SELECT * FROM investment_plans;`
3. If empty, run the seed SQL from Step 5

---

## Important Notes

- **Config password:** Never commit the real password to git. Keep `backend/config/config.php` out of version control on live.
- **Session security:** HTTPS is required for secure cookies. Ensure Hostinger SSL certificate is active.
- **Database backups:** Regularly back up the live database before major changes.
- **Cron logs:** Check Hostinger cron job logs to ensure release-investments.php runs without errors.
- **Clean URLs:** The `.htaccess` file is critical. Do not modify unless you understand Apache rewrite rules.

---

## Quick Reference: Key Files

| File | Purpose |
|------|---------|
| `.htaccess` | Route rewrite rules for clean URLs |
| `backend/config/config.php` | Database credentials & app config |
| `backend/schema.sql` | Initial database schema |
| `backend/migration-investments.sql` | Investment & wallet type migration |
| `cron/release-investments.php` | Cron job for matured investment release |
| `api/auth/register.php` | Registration API |
| `api/auth/login.php` | Login API |
| `api/user/investment-create.php` | Investment creation & wallet debit |
| `portal/dashboard/index.php` | Authenticated dashboard |
| `portal/investments/index.php` | Investment plans listing |
| `portal/investments/preview.php` | Investment confirmation & "Invest Now" |

---

## Support & Further Help

- For **Hostinger-specific issues:** Contact Hostinger support
- For **database issues:** Use phpMyAdmin in Hostinger Control Panel
- For **code issues:** Review the API response in browser network tab
- For **cron debugging:** Check Hostinger email notifications or cron logs

---

**Deployment ready:** All components are prepared and tested locally. Follow steps 1–8 to go live.

**Last updated:** Today  
**Version:** 1.0
