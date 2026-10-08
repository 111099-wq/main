<?php
require_once __DIR__ . '/auth.php';
require_login();

$user = current_user();
$page_title = $page_title ?? 'Keuangan';
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($page_title) ?> - Keuangan</title>


    <style>

        /* ================================
           RESET
        ================================= */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
            color: #172033;
        }

        a {
            text-decoration: none;
        }


        /* ================================
           APP
        ================================= */

        .app {
            min-height: 100vh;
        }


        /* ================================
           SIDEBAR
        ================================= */

        .sidebar {
            position: fixed;

            top: 0;
            left: 0;
            bottom: 0;

            width: 250px;

            background: #ffffff;

            border-right: 1px solid #e5e7eb;

            display: flex;
            flex-direction: column;

            z-index: 9999;
        }


        /* ================================
           LOGO
        ================================= */

        .logo-area {
            height: 76px;

            padding: 0 22px;

            display: flex;
            align-items: center;

            border-bottom: 1px solid #eeeeee;
        }

        .logo {
            width: 40px;
            height: 40px;

            border-radius: 10px;

            background: #2563eb;

            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 19px;
            font-weight: bold;

            margin-right: 11px;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-text strong {
            color: #111827;
            font-size: 16px;
        }

        .logo-text small {
            color: #94a3b8;
            font-size: 11px;
            margin-top: 3px;
        }


        /* ================================
           MENU
        ================================= */

        .menu {
            flex: 1;

            padding: 18px 13px;

            overflow-y: auto;
        }

        .menu a {
            display: flex;

            align-items: center;

            height: 44px;

            padding: 0 14px;

            margin-bottom: 4px;

            border-radius: 8px;

            color: #64748b;

            font-size: 14px;

            transition: 0.2s;
        }

        .menu a:hover {
            background: #eff6ff;
            color: #2563eb;
        }

        .menu a.active {
            background: #2563eb;
            color: white;
        }

        .menu-icon {
            width: 28px;

            display: inline-flex;

            justify-content: center;
            align-items: center;

            margin-right: 8px;

            font-size: 16px;
        }


        /* ================================
           LOGOUT
        ================================= */

        .sidebar-bottom {
            padding: 14px;

            border-top: 1px solid #e5e7eb;
        }

        .logout {
            display: flex;

            align-items: center;

            height: 44px;

            padding: 0 14px;

            border-radius: 8px;

            color: #dc2626;

            font-size: 14px;
        }

        .logout:hover {
            background: #fef2f2;
        }


        /* ================================
           MAIN
        ================================= */

        .main {
            margin-left: 250px;

            min-height: 100vh;
        }


        /* ================================
           TOPBAR
        ================================= */

        .topbar {
            height: 76px;

            background: white;

            border-bottom: 1px solid #e5e7eb;

            display: flex;

            align-items: center;

            padding: 0 32px;

            position: sticky;

            top: 0;

            z-index: 500;
        }

        .page-heading {
            flex: 1;
        }

        .page-heading h2 {
            font-size: 19px;

            color: #111827;
        }


        /* ================================
           USER
        ================================= */

        .user-profile {
            display: flex;

            align-items: center;

            gap: 10px;
        }

        .avatar {
            width: 40px;
            height: 40px;

            border-radius: 50%;

            background: #dbeafe;

            color: #2563eb;

            display: flex;

            align-items: center;
            justify-content: center;

            font-weight: bold;
        }

        .user-info {
            display: flex;

            flex-direction: column;

            gap: 3px;
        }

        .user-info strong {
            font-size: 13px;

            color: #111827;
        }

        .user-info small {
            font-size: 11px;

            color: #94a3b8;
        }


        /* ================================
           CONTENT
        ================================= */

        .content {
            padding: 32px;
        }


        /* ================================
           MENU BUTTON
        ================================= */

        .menu-button {
            display: none;

            border: none;

            background: transparent;

            font-size: 23px;

            cursor: pointer;

            margin-right: 15px;
        }


        /* ================================
           WELCOME
        ================================= */

        .welcome {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 28px;
        }

        .welcome h1 {
            font-size: 30px;

            color: #111827;

            margin-bottom: 6px;
        }

        .welcome p {
            font-size: 14px;

            color: #64748b;
        }


        /* ================================
           BUTTON
        ================================= */

        .btn {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            padding: 13px 20px;

            border-radius: 9px;

            font-size: 14px;

            font-weight: 600;
        }

        .btn.primary {
            background: #2563eb;

            color: white;
        }

        .btn.primary:hover {
            background: #1d4ed8;
        }


        /* ================================
           STAT
        ================================= */

        .stat-grid {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 20px;

            margin-bottom: 28px;
        }

        .stat-card {
            background: white;

            border: 1px solid #e5e7eb;

            border-radius: 12px;

            padding: 24px;

            box-shadow:
                0 2px 6px rgba(0,0,0,0.03);
        }

        .stat-card span {
            display: block;

            color: #64748b;

            font-size: 13px;

            margin-bottom: 12px;
        }

        .stat-card strong {
            display: block;

            color: #111827;

            font-size: 25px;

            margin-bottom: 8px;
        }

        .stat-card small {
            color: #94a3b8;

            font-size: 12px;
        }

        .income {
            color: #16a34a !important;
        }

        .expense {
            color: #dc2626 !important;
        }


        /* ================================
           PANEL
        ================================= */

        .panel {
            background: white;

            border: 1px solid #e5e7eb;

            border-radius: 12px;

            overflow: hidden;

            box-shadow:
                0 2px 6px rgba(0,0,0,0.03);
        }

        .panel-header {
            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 24px 28px;

            border-bottom: 1px solid #eef0f3;
        }

        .panel-header h2 {
            font-size: 18px;

            margin-bottom: 6px;
        }

        .panel-header p {
            font-size: 13px;

            color: #64748b;
        }

        .text-link {
            color: #2563eb;

            font-size: 14px;

            font-weight: 600;
        }


        /* ================================
           EMPTY
        ================================= */

        .empty {
            min-height: 180px;

            display: flex;

            align-items: center;
            justify-content: center;

            color: #94a3b8;

            font-size: 14px;
        }


        /* ================================
           TABLE
        ================================= */

        .table-wrap {
            width: 100%;

            overflow-x: auto;
        }

        table {
            width: 100%;

            border-collapse: collapse;
        }

        th {
            background: #f8fafc;

            color: #64748b;

            text-align: left;

            font-size: 12px;

            padding: 14px 20px;

            border-bottom: 1px solid #e5e7eb;
        }

        td {
            color: #334155;

            font-size: 13px;

            padding: 15px 20px;

            border-bottom: 1px solid #f1f5f9;
        }


        /* ================================
           BADGE
        ================================= */

        .badge {
            display: inline-block;

            padding: 5px 9px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: 600;
        }

        .badge.success {
            background: #dcfce7;

            color: #15803d;
        }

        .badge.danger {
            background: #fee2e2;

            color: #b91c1c;
        }


        /* ================================
           RESPONSIVE
        ================================= */

        @media (max-width: 1100px) {

            .stat-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

        }


        @media (max-width: 768px) {

            .sidebar {
                transform: translateX(-100%);

                transition: transform 0.25s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
            }

            .menu-button {
                display: block;
            }

            .topbar {
                padding: 0 18px;
            }

            .content {
                padding: 20px;
            }

            .welcome {
                align-items: flex-start;

                flex-direction: column;
            }

            .stat-grid {
                grid-template-columns: 1fr;
            }

            .user-info {
                display: none;
            }

        }


        @media (max-width: 480px) {

            .content {
                padding: 15px;
            }

            .welcome h1 {
                font-size: 24px;
            }

        }

    </style>

</head>


<body>

<div class="app">


    <!-- SIDEBAR -->

    <aside class="sidebar" id="sidebar">


        <!-- LOGO -->

        <div class="logo-area">

            <div class="logo">
                K
            </div>

            <div class="logo-text">

                <strong>
                    Keuangan
                </strong>

                <small>
                    Finance Manager
                </small>

            </div>

        </div>


        <!-- MENU -->

        <nav class="menu">

            <a href="/keuangan_frontend_php/dashboard/index.php"
               class="<?= $page_title === 'Dashboard' ? 'active' : '' ?>">

                <span class="menu-icon">🏠</span>
                <span>Dashboard</span>

            </a>


            <a href="/keuangan_frontend_php/transactions/index.php"
               class="<?= $page_title === 'Transaksi' ? 'active' : '' ?>">

                <span class="menu-icon">💳</span>
                <span>Transaksi</span>

            </a>


            <a href="/keuangan_frontend_php/income/index.php"
               class="<?= $page_title === 'Pemasukan' ? 'active' : '' ?>">

                <span class="menu-icon">📈</span>
                <span>Pemasukan</span>

            </a>


            <a href="/keuangan_frontend_php/expense/index.php"
               class="<?= $page_title === 'Pengeluaran' ? 'active' : '' ?>">

                <span class="menu-icon">📉</span>
                <span>Pengeluaran</span>

            </a>


            <a href="/keuangan_frontend_php/accounts/index.php"
               class="<?= $page_title === 'Rekening' ? 'active' : '' ?>">

                <span class="menu-icon">🏦</span>
                <span>Rekening</span>

            </a>


            <a href="/keuangan_frontend_php/budgets/index.php"
               class="<?= $page_title === 'Anggaran' ? 'active' : '' ?>">

                <span class="menu-icon">💰</span>
                <span>Anggaran</span>

            </a>


            <a href="/keuangan_frontend_php/goals/index.php"
               class="<?= $page_title === 'Target Keuangan' ? 'active' : '' ?>">

                <span class="menu-icon">🎯</span>
                <span>Target Keuangan</span>

            </a>


            <a href="/keuangan_frontend_php/bills/index.php"
               class="<?= $page_title === 'Tagihan' ? 'active' : '' ?>">

                <span class="menu-icon">🧾</span>
                <span>Tagihan</span>

            </a>


            <a href="/keuangan_frontend_php/debts/index.php"
               class="<?= $page_title === 'Hutang & Piutang' ? 'active' : '' ?>">

                <span class="menu-icon">🤝</span>
                <span>Hutang & Piutang</span>

            </a>


            <a href="/keuangan_frontend_php/reports/index.php"
               class="<?= $page_title === 'Laporan' ? 'active' : '' ?>">

                <span class="menu-icon">📊</span>
                <span>Laporan</span>

            </a>


            <a href="/keuangan_frontend_php/analysis/index.php"
               class="<?= $page_title === 'Analisis' ? 'active' : '' ?>">

                <span class="menu-icon">📌</span>
                <span>Analisis</span>

            </a>


            <a href="/keuangan_frontend_php/notifications/index.php"
               class="<?= $page_title === 'Notifikasi' ? 'active' : '' ?>">

                <span class="menu-icon">🔔</span>
                <span>Notifikasi</span>

            </a>


            <a href="/keuangan_frontend_php/settings/index.php"
               class="<?= $page_title === 'Pengaturan' ? 'active' : '' ?>">

                <span class="menu-icon">⚙️</span>
                <span>Pengaturan</span>

            </a>

        </nav>


        <!-- LOGOUT -->

        <div class="sidebar-bottom">

            <a href="/keuangan_frontend_php/logout.php"
               class="logout">

                <span class="menu-icon">🚪</span>
                <span>Logout</span>

            </a>

        </div>

    </aside>


    <!-- MAIN -->

    <main class="main">


        <!-- TOPBAR -->

        <header class="topbar">

            <button
                type="button"
                class="menu-button"
                onclick="toggleSidebar()">

                ☰

            </button>


            <div class="page-heading">

                <h2>
                    <?= htmlspecialchars($page_title) ?>
                </h2>

            </div>


            <div class="user-profile">

                <div class="avatar">

                    <?= strtoupper(
                        substr(
                            $user['name'] ?? 'U',
                            0,
                            1
                        )
                    ) ?>

                </div>


                <div class="user-info">

                    <strong>
                        <?= htmlspecialchars(
                            $user['name'] ?? 'User'
                        ) ?>
                    </strong>

                    <small>
                        <?= htmlspecialchars(
                            $user['email'] ?? ''
                        ) ?>
                    </small>

                </div>

            </div>

        </header>


        <section class="content">