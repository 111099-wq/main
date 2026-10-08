<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();

$user = current_user();
$pdo = db();
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT id, account_id, type, amount
        FROM transactions WHERE id = ? AND user_id = ? FOR UPDATE");
    $stmt->execute([$id, $user['id']]);
    $transaction = $stmt->fetch();

    if (!$transaction) throw new Exception('Transaksi tidak ditemukan.');

    $operator = $transaction['type'] === 'income' ? '-' : '+';

    $stmt = $pdo->prepare("UPDATE accounts
        SET current_balance = current_balance $operator ?
        WHERE id = ? AND user_id = ?");
    $stmt->execute([
        $transaction['amount'],
        $transaction['account_id'],
        $user['id']
    ]);

    $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user['id']]);

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
}

header('Location: index.php');
exit;
