<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();

$user = current_user();
$pdo = db();
$type = $_GET['type'] ?? $_POST['type'] ?? 'income';

if (!in_array($type, ['income', 'expense'], true)) $type = 'income';

$errors = [];

$stmt = $pdo->prepare("SELECT id, name, current_balance FROM accounts
    WHERE user_id = ? AND status = 'active' ORDER BY name ASC");
$stmt->execute([$user['id']]);
$accounts = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT id, name FROM categories
    WHERE (user_id = ? OR user_id IS NULL) AND type = ? ORDER BY name ASC");
$stmt->execute([$user['id'], $type]);
$categories = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_id = (int)($_POST['account_id'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $amount = (float)($_POST['amount'] ?? 0);
    $transaction_date = $_POST['transaction_date'] ?? date('Y-m-d');
    $description = trim($_POST['description'] ?? '');

    if ($account_id <= 0) $errors[] = 'Pilih rekening terlebih dahulu.';
    if ($amount <= 0) $errors[] = 'Nominal harus lebih dari 0.';
    if (!$transaction_date) $errors[] = 'Tanggal transaksi wajib diisi.';

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT id, current_balance FROM accounts
                WHERE id = ? AND user_id = ? AND status = 'active' FOR UPDATE");
            $stmt->execute([$account_id, $user['id']]);
            $account = $stmt->fetch();

            if (!$account) throw new Exception('Rekening tidak ditemukan.');

            if ($type === 'expense' && (float)$account['current_balance'] < $amount) {
                throw new Exception('Saldo rekening tidak mencukupi.');
            }

            $stmt = $pdo->prepare("INSERT INTO transactions
                (user_id, account_id, category_id, type, amount, transaction_date, description)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $user['id'], $account_id,
                $category_id > 0 ? $category_id : null,
                $type, $amount, $transaction_date, $description
            ]);

            $operator = $type === 'income' ? '+' : '-';
            $stmt = $pdo->prepare("UPDATE accounts
                SET current_balance = current_balance $operator ?
                WHERE id = ? AND user_id = ?");
            $stmt->execute([$amount, $account_id, $user['id']]);

            $pdo->commit();

            header('Location: ../' . ($type === 'income' ? 'income' : 'expense') . '/index.php?success=1');
            exit;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $errors[] = $e->getMessage();
        }
    }
}

$page_title = $type === 'income' ? 'Tambah Pemasukan' : 'Tambah Pengeluaran';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="welcome">
    <div>
        <h1><?= $type === 'income' ? 'Tambah Pemasukan' : 'Tambah Pengeluaran' ?></h1>
        <p>Catat transaksi keuangan baru.</p>
    </div>
    <a class="btn" href="../<?= $type === 'income' ? 'income' : 'expense' ?>/index.php"
       style="background:#e5e7eb;color:#374151;">Kembali</a>
</div>

<?php if ($errors): ?>
<div class="alert-error">
    <?php foreach ($errors as $error): ?>
        <div><?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="panel form-panel">
<form method="post">
    <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

    <div class="form-grid">
        <div class="form-group">
            <label>Jenis Transaksi</label>
            <input type="text" value="<?= $type === 'income' ? 'Pemasukan' : 'Pengeluaran' ?>" readonly>
        </div>

        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="transaction_date"
                   value="<?= htmlspecialchars($_POST['transaction_date'] ?? date('Y-m-d')) ?>" required>
        </div>

        <div class="form-group">
            <label>Rekening</label>
            <select name="account_id" required>
                <option value="">-- Pilih Rekening --</option>
                <?php foreach ($accounts as $account): ?>
                    <option value="<?= (int)$account['id'] ?>"
                        <?= ((int)($_POST['account_id'] ?? 0) === (int)$account['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($account['name']) ?> -
                        Rp <?= number_format($account['current_balance'], 0, ',', '.') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="category_id">
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int)$category['id'] ?>"
                        <?= ((int)($_POST['category_id'] ?? 0) === (int)$category['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Nominal</label>
            <input type="number" name="amount" min="1" step="0.01"
                   placeholder="Contoh: 50000"
                   value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>" required>
        </div>

        <div class="form-group full">
            <label>Keterangan</label>
            <textarea name="description" rows="4"
                      placeholder="Contoh: Gaji, makan, transportasi, listrik, dll."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="form-actions">
        <a class="btn" href="../<?= $type === 'income' ? 'income' : 'expense' ?>/index.php"
           style="background:#e5e7eb;color:#374151;">Batal</a>
        <button class="btn primary" type="submit">
            Simpan <?= $type === 'income' ? 'Pemasukan' : 'Pengeluaran' ?>
        </button>
    </div>
</form>
</div>

<style>
.form-panel{padding:28px}
.form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}
.form-group{display:flex;flex-direction:column;gap:8px}
.form-group.full{grid-column:1/-1}
.form-group label{font-size:13px;font-weight:600;color:#374151}
.form-group input,.form-group select,.form-group textarea{width:100%;border:1px solid #d1d5db;border-radius:8px;padding:11px 12px;font-family:inherit;font-size:14px;outline:none;background:#fff}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1)}
.form-group textarea{resize:vertical}
.form-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:25px}
.alert-error{background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:13px 16px;border-radius:9px;margin-bottom:20px;font-size:13px}
@media(max-width:700px){.form-grid{grid-template-columns:1fr}.form-group.full{grid-column:auto}}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
