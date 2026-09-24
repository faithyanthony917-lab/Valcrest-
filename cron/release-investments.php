<?php

declare(strict_types=1);

require __DIR__ . '/../backend/src/bootstrap.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI execution required.');
}

$pdo->beginTransaction();
try {
    $query = $pdo->query(
        "SELECT i.id, i.user_id, i.profit_account_id, i.principal, i.expected_return, i.currency,
                i.reference, p.capital_back
         FROM investments i
         JOIN investment_plans p ON p.id = i.plan_id
         WHERE i.status = 'active' AND i.matures_at <= CURRENT_TIMESTAMP
         ORDER BY i.id
         FOR UPDATE"
    );
    $investments = $query->fetchAll();
    $credited = 0;
    $release = $pdo->prepare(
        "UPDATE accounts
         SET available_balance = available_balance + :amount
         WHERE id = :account_id AND user_id = :user_id AND wallet_type = 'profit'"
    );
    $ledger = $pdo->prepare(
        "INSERT INTO ledger_transactions
            (user_id, account_id, type, amount, currency, status, description)
         VALUES (:user_id, :account_id, 'investment_release', :amount, :currency, 'completed', :description)"
    );
    $markReleased = $pdo->prepare(
        "UPDATE investments SET status = 'released', released_at = CURRENT_TIMESTAMP
         WHERE id = :id AND status = 'active'"
    );

    foreach ($investments as $investment) {
        $amount = (float) $investment['expected_return'];
        if ((int) $investment['capital_back'] === 1) {
            $amount += (float) $investment['principal'];
        }
        $amount = number_format($amount, 2, '.', '');
        $release->execute([
            'amount' => $amount,
            'account_id' => $investment['profit_account_id'],
            'user_id' => $investment['user_id'],
        ]);
        if ($release->rowCount() !== 1) {
            throw new RuntimeException('The profit wallet is unavailable for investment ' . $investment['reference'] . '.');
        }
        $ledger->execute([
            'user_id' => $investment['user_id'],
            'account_id' => $investment['profit_account_id'],
            'amount' => $amount,
            'currency' => $investment['currency'],
            'description' => 'Investment matured ' . $investment['reference'],
        ]);
        $markReleased->execute(['id' => $investment['id']]);
        $credited++;
    }

    $pdo->commit();
    $payload = ['released' => $credited];
    echo json_encode($payload) . PHP_EOL;
    exit;
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, $exception->getMessage() . PHP_EOL);
    exit(1);
}
