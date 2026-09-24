-- Run this migration after selecting u103464132_valcrest in phpMyAdmin.
-- It preserves existing users, wallets, requests, and ledger records.

SET @database_name = DATABASE();

SET @has_wallet_type = (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @database_name AND TABLE_NAME = 'accounts' AND COLUMN_NAME = 'wallet_type'
);
SET @sql = IF(
  @has_wallet_type = 0,
  'ALTER TABLE accounts ADD COLUMN wallet_type ENUM(''main'', ''profit'') NOT NULL DEFAULT ''main'' AFTER currency',
  'SELECT 1'
);
PREPARE migration_statement FROM @sql;
EXECUTE migration_statement;
DEALLOCATE PREPARE migration_statement;

SET @has_wallet_unique = (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = @database_name AND TABLE_NAME = 'accounts'
    AND INDEX_NAME = 'accounts_user_currency_wallet_unique'
);
SET @has_legacy_wallet_unique = (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = @database_name AND TABLE_NAME = 'accounts'
    AND INDEX_NAME = 'accounts_user_currency_unique'
);
SET @sql = IF(
  @has_legacy_wallet_unique = 1,
  'ALTER TABLE accounts DROP INDEX accounts_user_currency_unique',
  'SELECT 1'
);
PREPARE migration_statement FROM @sql;
EXECUTE migration_statement;
DEALLOCATE PREPARE migration_statement;

SET @sql = IF(
  @has_wallet_unique = 0,
  'ALTER TABLE accounts ADD UNIQUE KEY accounts_user_currency_wallet_unique (user_id, currency, wallet_type)',
  'SELECT 1'
);
PREPARE migration_statement FROM @sql;
EXECUTE migration_statement;
DEALLOCATE PREPARE migration_statement;

CREATE TABLE IF NOT EXISTS investment_plans (
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

CREATE TABLE IF NOT EXISTS investments (
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

INSERT INTO investment_plans
  (name, description, currency, minimum_amount, maximum_amount, return_rate, term_days, capital_back)
SELECT 'Standard Growth', 'A balanced fixed-term investment plan.', 'GBP', 100.00, 100000.00, 5.0000, 30, 1
WHERE NOT EXISTS (SELECT 1 FROM investment_plans);

INSERT INTO investment_plans
  (name, description, currency, minimum_amount, maximum_amount, return_rate, term_days, capital_back)
SELECT 'Long-Term Growth', 'A longer-term plan with an enhanced return.', 'GBP', 500.00, 250000.00, 12.5000, 90, 1
WHERE NOT EXISTS (SELECT 1 FROM investment_plans WHERE name = 'Long-Term Growth');

INSERT INTO accounts (user_id, currency, wallet_type)
SELECT u.id, 'GBP', 'profit'
FROM users u
LEFT JOIN accounts a ON a.user_id = u.id AND a.currency = 'GBP' AND a.wallet_type = 'profit'
WHERE a.id IS NULL;
