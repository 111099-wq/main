<?php
session_start();
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmation = $_POST['password_confirmation'] ?? '';

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['register_error'] = 'Nama dan email wajib diisi dengan benar.';
    header('Location: ../register.php');
    exit;
}

if (strlen($password) < 8) {
    $_SESSION['register_error'] = 'Password minimal 8 karakter.';
    header('Location: ../register.php');
    exit;
}

if ($password !== $confirmation) {
    $_SESSION['register_error'] = 'Konfirmasi password tidak sama.';
    header('Location: ../register.php');
    exit;
}

$stmt = db()->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);

if ($stmt->fetch()) {
    $_SESSION['register_error'] = 'Email sudah terdaftar.';
    header('Location: ../register.php');
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = db()->prepare(
    "INSERT INTO users (name, email, password, role, status)
     VALUES (?, ?, ?, 'user', 'active')"
);
$stmt->execute([$name, $email, $hash]);

$userId = db()->lastInsertId();

$stmt = db()->prepare(
    "INSERT INTO accounts (user_id, name, type, initial_balance, current_balance)
     VALUES (?, 'Cash', 'cash', 0, 0)"
);
$stmt->execute([$userId]);

$stmt = db()->prepare('SELECT id, name, email, role, status FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

session_regenerate_id(true);
$_SESSION['user'] = $user;

header('Location: ../dashboard/index.php');
exit;
