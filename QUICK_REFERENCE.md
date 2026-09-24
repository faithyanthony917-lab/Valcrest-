# Quick Reference - Valcrest Meridian Capital

## 📁 Project Location
`C:\Users\emman\Documents\public_html\`

## 🚀 Ready to Deploy
All files are in `public_html/` and ready to upload to Hostinger.

---

## 📋 Three Key Documents

1. **COMPLETION_SUMMARY.md** – What was built, what's working, success criteria met
2. **PROJECT_README.md** – Complete technical documentation, architecture, API reference
3. **DEPLOYMENT_CHECKLIST.md** – Step-by-step guide to go live on Hostinger (8 steps)

---

## ✅ What's Done

- ✅ Public website with static pages
- ✅ User authentication (register, login, logout)
- ✅ Dual wallet system (main + profit)
- ✅ Database-driven investment plans
- ✅ Full investment lifecycle (select → invest → mature → release)
- ✅ Automatic maturity release via cron job
- ✅ Admin console for request approval
- ✅ Unified transaction history
- ✅ CSRF protection, password hashing, session security
- ✅ Clean URLs (/portal/dashboard/ not /portal/dashboard/index.php)
- ✅ Mobile-responsive design with working hamburger menu

---

## 🔧 Key Configuration Files

**Local development (XAMPP, etc.):**
```
backend/config/config.php (copy from config.example.php)
Update with your local MySQL credentials
```

**Live deployment (Hostinger):**
```
backend/config/config.php
Must be updated with REAL Hostinger credentials:
- Database name: u103464132_valcrest
- Username: u103464132_valcrest
- Password: (from Hostinger Control Panel → MySQL Databases)
```

---

## 🔐 Database Migration

Before testing, run in phpMyAdmin:
```
backend/migration-investments.sql
```

This adds investment support to existing database without losing data.

---

## 🕐 Cron Job Setup

Hostinger Control Panel → Cron Jobs → Add:
```
Command: /usr/bin/php /home/u103464132/public_html/cron/release-investments.php
Frequency: Every 1 hour
```

This automatically releases matured investments to profit wallets.

---

## 📊 Database Tables

- **users** – User accounts
- **accounts** – Wallets (main + profit)
- **investment_plans** – Investment products (add via DB)
- **investments** – User investments
- **financial_requests** – Deposit/withdrawal requests
- **ledger_transactions** – Complete transaction history
- **support_tickets** – Support inquiries
- **password_reset_tokens** – Password reset flow

---

## 🔗 Main Entry Points

| URL | File | Purpose |
|-----|------|---------|
| `/` | `index.html` | Home page |
| `/auth/login/` | `auth/login/index.html` | Login page |
| `/auth/register/` | `auth/register/index.html` | Register page |
| `/portal/dashboard/` | `portal/dashboard/index.php` | User dashboard |
| `/portal/investments/` | `portal/investments/index.php` | Investment plans |
| `/portal/deposits/` | `portal/deposits/index.php` | Request deposit |
| `/admin/` | `admin/index.php` | Admin console |
| `/api/auth/login` | `api/auth/login.php` | Login API |
| `/api/user/investment-create` | `api/user/investment-create.php` | Create investment |

---

## 📝 API Examples

**Get CSRF token:**
```bash
GET https://valcrestmeridiancapital.com/api/auth/csrf
```

**Register:**
```bash
POST https://valcrestmeridiancapital.com/api/auth/register
Body: {
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "username": "johndoe",
  "password": "SecurePass123!",
  "password_confirm": "SecurePass123!",
  "country": "UK"
}
```

**Create investment:**
```bash
POST https://valcrestmeridiancapital.com/api/user/investment-create
Header: X-CSRF-Token: [token]
Cookie: valcrest_session=[session_id]
Body: {
  "planId": 1,
  "accountId": 5,
  "amount": 5000.00
}
```

---

## 🧪 Local Testing Flow

1. Register account → wallets auto-created
2. Login → dashboard shows balance
3. Request deposit → pending in DB
4. Manually approve in admin console
5. Select investment plan → see return calculation
6. Click "Invest Now" → wallet debited, investment created
7. Check investment logs → shows principal, return, maturity date
8. Wait (or manually run cron) → profit wallet credited

---

## 🌐 Deployment Steps (TL;DR)

1. Backup current Hostinger site & database
2. Upload `public_html/` contents to Hostinger root
3. Update `backend/config/config.php` with live credentials
4. Import `backend/migration-investments.sql` in phpMyAdmin
5. Create cron job for `cron/release-investments.php` (hourly)
6. Test: register, login, invest, admin approve
7. Monitor logs for errors

**Full guide:** See `DEPLOYMENT_CHECKLIST.md`

---

## 🆘 Common Issues

**404 on portal pages?**
- Check `.htaccess` exists in root
- Verify Apache mod_rewrite enabled

**Database connection error?**
- Check credentials in config.php
- Verify database exists in phpMyAdmin

**Cron not running?**
- Check command path in Hostinger Cron Jobs
- Test manually: `php cron/release-investments.php`

**CSS/images not loading?**
- Clear browser cache
- Check asset paths in HTML (relative vs absolute)

---

## 📞 Support Resources

- **Technical docs:** PROJECT_README.md
- **Deployment guide:** DEPLOYMENT_CHECKLIST.md
- **Hostinger help:** Control Panel → Support
- **Database help:** phpMyAdmin interface

---

## 🎯 Next Action

👉 **Read DEPLOYMENT_CHECKLIST.md and follow the 8 deployment steps**

---

**Project:** Valcrest Meridian Capital  
**Status:** ✅ Production Ready  
**Location:** C:\Users\emman\Documents\public_html\  
**Deploy to:** Hostinger (valcrestmeridiancapital.com)  
