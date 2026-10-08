<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: dashboard/index.php');
    exit;
}
$error = $_SESSION['register_error'] ?? '';
unset($_SESSION['register_error']);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Keuangan</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="auth-page">
<div class="auth-single">
    <div class="auth-card">
        <div class="brand">
            <div class="brand-icon">K</div>
            <div><strong>Keuangan</strong><small>Finance Manager</small></div>
        </div>
        <h2>Buat akun baru</h2>
        <p class="muted">Mulai kelola keuangan Anda hari ini.</p>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="auth/register_process.php" method="post">
            <label>Nama</label>
            <input type="text" name="name" required maxlength="100">

            <label>Email</label>
            <input type="email" name="email" required maxlength="150">

            <label>Password</label>
            <input type="password" name="password" required minlength="8">

            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required minlength="8">

            <button class="btn primary full" type="submit">Daftar</button>
        </form>

        <div class="auth-links">
            <span>Sudah punya akun?</span>
            <a href="login.php">Masuk</a>
        </div>
    </div>
</div>
</body>
</html>
