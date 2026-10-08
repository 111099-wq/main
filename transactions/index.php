<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
$user = current_user();
$pdo = db();

$stmt = $pdo->prepare("SELECT t.*, c.name AS category_name, a.name AS account_name
    FROM transactions t
    LEFT JOIN categories c ON c.id = t.category_id
    INNER JOIN accounts a ON a.id = t.account_id
    WHERE t.user_id = ?
    ORDER BY t.transaction_date DESC, t.id DESC");
$stmt->execute([$user['id']]);
$transactions = $stmt->fetchAll();

$page_title = 'Transaksi';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="welcome">
    <div>
        <h1>Transaksi</h1>
        <p>Kelola seluruh pemasukan dan pengeluaran Anda.</p>
    </div>
    <a class="btn primary" href="create.php">+ Tambah Transaksi</a>
</div>

<div class="panel">
    <div class="panel-header">
        <div>
            <h2>Daftar Transaksi</h2>
            <p>Semua aktivitas keuangan Anda.</p>
        </div>
    </div>

    <?php if (!$transactions): ?>
        <div class="empty">Belum ada transaksi.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr>
                    <th>Tanggal</th><th>Keterangan</th><th>Kategori</th>
                    <th>Rekening</th><th>Jenis</th><th>Nominal</th><th>Aksi</th>
                </tr></thead>
                <tbody>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['transaction_date']) ?></td>
                        <td><?= htmlspecialchars($t['description'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($t['category_name'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($t['account_name']) ?></td>
                        <td><span class="badge <?= $t['type'] === 'income' ? 'success' : 'danger' ?>">
                            <?= $t['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran' ?>
                        </span></td>
                        <td class="<?= $t['type'] === 'income' ? 'income' : 'expense' ?>">
                            <?= $t['type'] === 'income' ? '+' : '-' ?>
                            Rp <?= number_format($t['amount'], 0, ',', '.') ?>
                        </td>
                        <td>
                            <a class="btn-small danger-btn" href="delete.php?id=<?= (int)$t['id'] ?>"
                               onclick="return confirm('Hapus transaksi ini? Saldo rekening juga akan dikembalikan.')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
