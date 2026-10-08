<?php
session_start();
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    $_SESSION['login_error'] = 'Email dan password wajib diisi dengan benar.';
    header('Location: ../login.php');
    exit;
}

$stmt = db()->prepare('SELECT id, name, email, password, role, status FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = 'Email atau password salah.';
    header('Location: ../login.php');
    exit;
}

if ($user['status'] !== 'active') {
    $_SESSION['login_error'] = 'Akun Anda tidak aktif. Hubungi administrator.';
    header('Location: ../login.php');
    exit;
}

session_regenerate_id(true);

unset($user['password']);
$_SESSION['user'] = $user;

header('Location: ../dashboard/index.php');
exit;
