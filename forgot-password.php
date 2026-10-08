<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Keuangan</title>
    <link rel="stylesheet" href="/keuangan/assets/css/auth.css">
</head>
<body class="auth-page">
<div class="auth-single">
    <div class="auth-card">
        <div class="brand">
            <div class="brand-icon">K</div>
            <div><strong>Keuangan</strong><small>Finance Manager</small></div>
        </div>
        <h2>Lupa password</h2>
        <p class="muted">Masukkan email Anda. Fitur pengiriman email dapat dihubungkan ke SMTP pada tahap berikutnya.</p>
        <form action="#" method="post" onsubmit="alert('Fitur reset password akan dihubungkan ke SMTP.'); return false;">
            <label>Email</label>
            <input type="email" required placeholder="nama@email.com">
            <button class="btn primary full" type="submit">Kirim Link Reset</button>
        </form>
        <div class="auth-links"><a href="login.php">Kembali ke login</a></div>
    </div>
</div>
</body>
</html>
