# Keuangan - Frontend PHP Native

## Struktur
- login.php
- register.php
- forgot-password.php
- logout.php
- dashboard/index.php
- auth/login_process.php
- auth/register_process.php
- includes/
- assets/

## Instalasi XAMPP
1. Buat folder `keuangan` di `htdocs`.
2. Extract isi ZIP ke `htdocs/keuangan`.
3. Import database `keuangan_db` yang sudah dibuat sebelumnya.
4. Sesuaikan `config.php` jika username/password MySQL berbeda.
5. Buka `http://localhost/keuangan/`.

## Catatan
- Sistem menggunakan PHP native + PDO.
- Password disimpan menggunakan `password_hash()`.
- Login menggunakan session.
- Logout menghancurkan session.
- Register otomatis membuat rekening Cash dengan saldo awal 0.
- Link menu selain Dashboard masih berupa placeholder dan akan diisi pada tahap modul berikutnya.
