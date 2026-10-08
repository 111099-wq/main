<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();

$user = current_user();
$pdo = db();

$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(current_balance),0)
    FROM accounts
    WHERE user_id = ?
    AND status = 'active'
");
$stmt->execute([$user['id']]);
$balance = (float)$stmt->fetchColumn();


$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(amount),0)
    FROM transactions
    WHERE user_id = ?
    AND type = 'income'
    AND MONTH(transaction_date) = MONTH(CURRENT_DATE())
    AND YEAR(transaction_date) = YEAR(CURRENT_DATE())
");
$stmt->execute([$user['id']]);
$income = (float)$stmt->fetchColumn();


$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(amount),0)
    FROM transactions
    WHERE user_id = ?
    AND type = 'expense'
    AND MONTH(transaction_date) = MONTH(CURRENT_DATE())
    AND YEAR(transaction_date) = YEAR(CURRENT_DATE())
");
$stmt->execute([$user['id']]);
$expense = (float)$stmt->fetchColumn();


$stmt = $pdo->prepare("
    SELECT
        t.*,
        c.name AS category_name,
        a.name AS account_name
    FROM transactions t
    LEFT JOIN categories c
        ON c.id = t.category_id
    INNER JOIN accounts a
        ON a.id = t.account_id
    WHERE t.user_id = ?
    ORDER BY t.transaction_date DESC, t.id DESC
    LIMIT 8
");

$stmt->execute([$user['id']]);
$transactions = $stmt->fetchAll();



require_once __DIR__ . '/../includes/header.php';
?>


<!-- WELCOME -->

<div class="welcome">

    <div>

        <h1>
            Halo, <?= htmlspecialchars($user['name']) ?> 👋
        </h1>

        <p>
            Berikut ringkasan keuangan Anda.
        </p>

    </div>

    <a class="btn primary"
       href="../transactions/create.php">

        + Tambah Transaksi

    </a>

</div>


<!-- STATISTIK -->

<div class="stat-grid">

    <div class="stat-card">

        <span>Total Saldo</span>

        <strong>
            Rp <?= number_format($balance, 0, ',', '.') ?>
        </strong>

        <small>
            Semua rekening aktif
        </small>

    </div>


    <div class="stat-card">

        <span>Pemasukan Bulan Ini</span>

        <strong class="income">
            Rp <?= number_format($income, 0, ',', '.') ?>
        </strong>

        <small>
            Bulan berjalan
        </small>

    </div>


    <div class="stat-card">

        <span>Pengeluaran Bulan Ini</span>

        <strong class="expense">
            Rp <?= number_format($expense, 0, ',', '.') ?>
        </strong>

        <small>
            Bulan berjalan
        </small>

    </div>


    <div class="stat-card">

        <span>Sisa Bulan Ini</span>

        <strong>
            Rp <?= number_format(
                $income - $expense,
                0,
                ',',
                '.'
            ) ?>
        </strong>

        <small>
            Pemasukan - pengeluaran
        </small>

    </div>

</div>


<!-- TRANSAKSI -->

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Transaksi Terbaru
            </h2>

            <p>
                Aktivitas keuangan terakhir Anda.
            </p>

        </div>

        <a href="../transactions/index.php"
           class="text-link">

            Lihat semua

        </a>

    </div>


    <?php if (!$transactions): ?>

        <div class="empty">
            Belum ada transaksi.
        </div>

    <?php else: ?>

        <div class="table-wrap">

            <table>

                <thead>

                <tr>

                    <th>Tanggal</th>

                    <th>Keterangan</th>

                    <th>Kategori</th>

                    <th>Rekening</th>

                    <th>Jenis</th>

                    <th>Nominal</th>

                </tr>

                </thead>


                <tbody>

                <?php foreach ($transactions as $t): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars(
                                $t['transaction_date']
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $t['description'] ?: '-'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $t['category_name'] ?: '-'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $t['account_name']
                            ) ?>
                        </td>

                        <td>

                            <span class="badge
                                <?= $t['type'] === 'income'
                                    ? 'success'
                                    : 'danger'
                                ?>">

                                <?= $t['type'] === 'income'
                                    ? 'Pemasukan'
                                    : 'Pengeluaran'
                                ?>

                            </span>

                        </td>

                        <td class="<?= $t['type'] === 'income'
                            ? 'income'
                            : 'expense'
                        ?>">

                            <?= $t['type'] === 'income'
                                ? '+'
                                : '-'
                            ?>

                            Rp <?= number_format(
                                $t['amount'],
                                0,
                                ',',
                                '.'
                            ) ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>