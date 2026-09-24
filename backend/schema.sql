-- Select the target database in phpMyAdmin before importing this file.
-- Hostinger users should select u103464132_valcrest.

CREATE TABLE users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  username VARCHAR(50) NOT NULL,
  country VARCHAR(100) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
  status ENUM('active', 'suspended') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY users_email_unique (email),
  UNIQUE KEY users_username_unique (username)
) ENGINE=InnoDB;

CREATE TABLE password_reset_tokens (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY password_reset_token_hash_unique (token_hash),
  KEY password_reset_user_expiry (user_id, expires_at),
  CONSTRAINT password_reset_user_fk FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE accounts (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'GBP',
  wallet_type ENUM('main', 'profit') NOT NULL DEFAULT 'main',
  available_balance DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY accounts_user_currency_wallet_unique (user_id, currency, wallet_type),
  CONSTRAINT accounts_user_fk FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE investment_plans (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  description VARCHAR(500) NULL,
  currency CHAR(3) NOT NULL DEFAULT 'GBP',
  minimum_amount DECIMAL(14,2) NOT NULL,
  maximum_amount DECIMAL(14,2) NULL,
  return_rate DECIMAL(8,4) NOT NULL,
  term_days INT UNSIGNED NOT NULL,
  capital_back TINYINT(1) NOT NULL DEFAULT 1,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY investment_plans_status (status)
) ENGINE=InnoDB;

CREATE TABLE investments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  plan_id BIGINT UNSIGNED NOT NULL,
  source_account_id BIGINT UNSIGNED NOT NULL,
  profit_account_id BIGINT UNSIGNED NOT NULL,
  principal DECIMAL(14,2) NOT NULL,
  expected_return DECIMAL(14,2) NOT NULL,
  currency CHAR(3) NOT NULL,
  starts_at DATETIME NOT NULL,
  matures_at DATETIME NOT NULL,
  status ENUM('active', 'released', 'cancelled') NOT NULL DEFAULT 'active',
  reference VARCHAR(32) NOT NULL,
  released_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY investments_reference_unique (reference),
  KEY investments_user_status (user_id, status),
  KEY investments_maturity (status, matures_at),
  CONSTRAINT investments_user_fk FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
  CONSTRAINT investments_plan_fk FOREIGN KEY (plan_id) REFERENCES investment_plans (id),
  CONSTRAINT investments_source_account_fk FOREIGN KEY (source_account_id) REFERENCES accounts (id),
  CONSTRAINT investments_profit_account_fk FOREIGN KEY (profit_account_id) REFERENCES accounts (id)
) ENGINE=InnoDB;

CREATE TABLE financial_requests (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  account_id BIGINT UNSIGNED NOT NULL,
  type ENUM('deposit', 'withdrawal') NOT NULL,
  amount DECIMAL(14,2) NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'GBP',
  status ENUM('pending', 'approved', 'rejected', 'cancelled') NOT NULL DEFAULT 'pending',
  reference VARCHAR(32) NOT NULL,
  notes VARCHAR(500) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY financial_requests_reference_unique (reference),
  KEY financial_requests_user_created (user_id, created_at),
  CONSTRAINT financial_requests_user_fk FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
  CONSTRAINT financial_requests_account_fk FOREIGN KEY (account_id) REFERENCES accounts (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE ledger_transactions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  account_id BIGINT UNSIGNED NOT NULL,
  type VARCHAR(40) NOT NULL,
  amount DECIMAL(14,2) NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'GBP',
  status ENUM('pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
  description VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY ledger_transactions_user_created (user_id, created_at),
  CONSTRAINT ledger_transactions_user_fk FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
  CONSTRAINT ledger_transactions_account_fk FOREIGN KEY (account_id) REFERENCES accounts (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE transfers (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  sender_user_id BIGINT UNSIGNED NOT NULL,
  recipient_user_id BIGINT UNSIGNED NOT NULL,
  sender_account_id BIGINT UNSIGNED NOT NULL,
  recipient_account_id BIGINT UNSIGNED NOT NULL,
  amount DECIMAL(14,2) NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'GBP',
  status ENUM('completed', 'failed') NOT NULL DEFAULT 'completed',
  reference VARCHAR(32) NOT NULL,
  note VARCHAR(500) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY transfers_reference_unique (reference),
  KEY transfers_sender_created (sender_user_id, created_at),
  KEY transfers_recipient_created (recipient_user_id, created_at),
  CONSTRAINT transfers_sender_user_fk FOREIGN KEY (sender_user_id) REFERENCES users (id) ON DELETE CASCADE,
  CONSTRAINT transfers_recipient_user_fk FOREIGN KEY (recipient_user_id) REFERENCES users (id) ON DELETE CASCADE,
  CONSTRAINT transfers_sender_account_fk FOREIGN KEY (sender_account_id) REFERENCES accounts (id) ON DELETE CASCADE,
  CONSTRAINT transfers_recipient_account_fk FOREIGN KEY (recipient_account_id) REFERENCES accounts (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE support_tickets (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  subject VARCHAR(180) NOT NULL,
  category VARCHAR(60) NOT NULL,
  status ENUM('open', 'in_progress', 'resolved', 'closed') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY support_tickets_user_updated (user_id, updated_at),
  CONSTRAINT support_tickets_user_fk FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE support_ticket_messages (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  ticket_id BIGINT UNSIGNED NOT NULL,
  author_user_id BIGINT UNSIGNED NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY support_messages_ticket_created (ticket_id, created_at),
  CONSTRAINT support_messages_ticket_fk FOREIGN KEY (ticket_id) REFERENCES support_tickets (id) ON DELETE CASCADE,
  CONSTRAINT support_messages_author_fk FOREIGN KEY (author_user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO investment_plans
  (name, description, currency, minimum_amount, maximum_amount, return_rate, term_days, capital_back)
VALUES
  ('Standard Growth', 'A balanced fixed-term investment plan.', 'GBP', 100.00, 100000.00, 5.0000, 30, 1),
  ('Long-Term Growth', 'A longer-term plan for patient capital.', 'GBP', 1000.00, 500000.00, 12.5000, 90, 1);
