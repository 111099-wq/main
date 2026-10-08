<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();

$user = current_user();
$pdo = db();

$stmt = $pdo->prepare("SELECT t.*, c.name AS category_name, a.name AS account_name
    FROM transactions t
    LEFT JOIN categories c ON c.id = t.category_id
    INNER JOIN accounts a ON a.id = t.account_id
    WHERE t.user_id = ? AND t.type = 'income'
    ORDER BY t.transaction_date DESC, t.id DESC");
$stmt->execute([$user['id']]);
$incomes = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT COALESCE(SUM(amount),0)
    FROM transactions WHERE user_id = ? AND type = 'income'");
$stmt->execute([$user['id']]);
$totalIncome = (float)$stmt->fetchColumn();

$page_title = 'Pemasukan';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="welcome">
    <div>
        <h1>Pemasukan</h1>
        <p>Catat dan pantau semua uang yang masuk.</p>
    </div>
    <a class="btn primary" href="../transactions/create.php?type=income">+ Tambah Pemasukan</a>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <span>Total Seluruh Pemasukan</span>
        <strong class="income">Rp <?= number_format($totalIncome,0,',','.') ?></strong>
        <small>Akumulasi semua pemasukan</small>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <div><h2>Riwayat Pemasukan</h2><p>Daftar pemasukan yang telah dicatat.</p></div>
    </div>

    <?php if (!$incomes): ?>
        <div class="empty">Belum ada pemasukan.</div>
    <?php else: ?>
    <div class="table-wrap">
        <table>
            <thead><tr>
                <th>Tanggal</th><th>Keterangan</th><th>Kategori</th>
                <th>Rekening</th><th>Nominal</th><th>Aksi</th>
            </tr></thead>
            <tbody>
            <?php foreach ($incomes as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['transaction_date']) ?></td>
                    <td><?= htmlspecialchars($row['description'] ?: '-') ?></td>
                    <td><?= htmlspecialchars($row['category_name'] ?: '-') ?></td>
                    <td><?= htmlspecialchars($row['account_name']) ?></td>
                    <td class="income">+ Rp <?= number_format($row['amount'],0,',','.') ?></td>
                    <td>
                        <a class="btn-small danger-btn" href="../transactions/delete.php?id=<?= (int)$row['id'] ?>"
                           onclick="return confirm('Hapus pemasukan ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<style>
.btn-small{display:inline-flex;padding:7px 10px;border-radius:7px;font-size:11px;font-weight:600}
.danger-btn{background:#fee2e2;color:#b91c1c}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
