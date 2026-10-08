<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: dashboard/index.php');
    exit;
}

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Keuangan</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="auth-page">
<div class="auth-layout">
    <section class="auth-hero">
        <div class="hero-content">
            <div class="brand large">
                <div class="brand-icon">K</div>
                <div><strong>Keuangan</strong><small>Finance Manager</small></div>
            </div>
            <h1>Kelola keuangan dengan lebih mudah.</h1>
            <p>Pantau pemasukan, pengeluaran, saldo, anggaran, dan target keuangan dalam satu dashboard.</p>
            <div class="feature-list">
                <span>✓ Dashboard keuangan</span>
                <span>✓ Laporan & grafik</span>
                <span>✓ Anggaran & target</span>
            </div>
        </div>
    </section>

    <section class="auth-card-wrap">
        <div class="auth-card">
            <h2>Selamat datang kembali</h2>
            <p class="muted">Masuk ke akun Anda untuk melanjutkan.</p>

            <?php if ($error): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="auth/login_process.php" method="post">
                <label>Email</label>
                <input type="email" name="email" placeholder="nama@email.com" required autocomplete="email">

                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">

                <button class="btn primary full" type="submit">Masuk</button>
            </form>

            <div class="auth-links">
                <a href="forgot-password.php">Lupa password?</a>
                <span>Belum punya akun?</span>
                <a href="register.php">Daftar sekarang</a>
            </div>
        </div>
    </section>
</div>
</body>
</html>
