<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($adminTitle) ? htmlspecialchars($adminTitle) . ' – JobVerse Admin' : 'JobVerse Admin' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/jobwebsite/assets/css/style.css">
    <style>
        .jv-admin-layout { display: flex; min-height: 100vh; }
        .jv-sidebar {
            width: 260px; min-width: 260px;
            background: rgba(13,15,26,0.95);
            border-right: 1px solid var(--jv-border);
            padding: 0;
            display: flex; flex-direction: column;
            position: sticky; top: 0; height: 100vh;
            overflow-y: auto;
        }
        .jv-sidebar-header {
            padding: 1.75rem 1.5rem;
            border-bottom: 1px solid var(--jv-border);
        }
        .jv-sidebar-logo { font-size: 1.4rem; font-weight: 800; }
        .jv-sidebar-label {
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px;
            color: var(--jv-text-muted);
            padding: 1.25rem 1.5rem 0.5rem;
        }
        .jv-sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 0.75rem 1.5rem;
            color: var(--jv-text-muted);
            text-decoration: none;
            font-weight: 500; font-size: 0.9rem;
            transition: var(--jv-transition);
            border-radius: 0;
            margin: 1px 0.75rem;
            border-radius: 10px;
        }
        .jv-sidebar-link:hover, .jv-sidebar-link.active {
            background: rgba(124,58,237,0.12);
            color: var(--jv-text);
        }
        .jv-sidebar-link.active { color: var(--jv-primary-light); }
        .jv-sidebar-link .material-icons { font-size: 20px; }
        .jv-admin-content { flex: 1; overflow-y: auto; }
        .jv-admin-topbar {
            background: rgba(13,15,26,0.8);
            border-bottom: 1px solid var(--jv-border);
            padding: 1rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            backdrop-filter: blur(20px);
            position: sticky; top: 0; z-index: 100;
        }
        .jv-admin-title { font-size: 1.2rem; font-weight: 700; }
        .jv-admin-inner { padding: 2rem; }
        .jv-admin-card {
            background: var(--jv-card);
            border: 1px solid var(--jv-border);
            border-radius: var(--jv-radius);
            padding: 1.75rem;
            margin-bottom: 1.5rem;
        }
        .jv-stat-card {
            background: var(--jv-card);
            border: 1px solid var(--jv-border);
            border-radius: var(--jv-radius);
            padding: 1.5rem;
            position: relative; overflow: hidden;
        }
        .jv-stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: var(--jv-gradient);
        }
        .jv-stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: rgba(124,58,237,0.15);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1rem;
        }
        .jv-stat-icon .material-icons { color: var(--jv-primary-light); font-size: 24px; }
        .jv-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .jv-table th {
            background: rgba(255,255,255,0.04);
            color: var(--jv-text-muted);
            font-size: 0.75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--jv-border);
            text-align: left;
        }
        .jv-table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            color: var(--jv-text);
            font-size: 0.9rem;
            vertical-align: middle;
        }
        .jv-table tr:hover td { background: rgba(255,255,255,0.015); }
        .jv-table tr:last-child td { border-bottom: none; }
        @media (max-width: 992px) {
            .jv-sidebar { display: none; }
        }
        .jv-form-section { margin-bottom: 2rem; }
        .jv-form-section-title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--jv-text-muted); margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--jv-border); }
    </style>
</head>
<body>
<div class="jv-admin-layout">
<!-- SIDEBAR -->
<aside class="jv-sidebar">
    <div class="jv-sidebar-header">
        <div class="jv-sidebar-logo">
            Job<span class="jv-brand-accent">Verse</span>
        </div>
        <div style="font-size:0.75rem;color:var(--jv-text-muted);margin-top:4px;">Admin Panel</div>
    </div>

    <div class="jv-sidebar-label">Main</div>
    <a href="/jobwebsite/admin/dashboard.php" class="jv-sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
        <i class="material-icons">dashboard</i> Dashboard
    </a>

    <div class="jv-sidebar-label">Jobs</div>
    <a href="/jobwebsite/admin/jobs/job-list.php" class="jv-sidebar-link <?= in_array(basename($_SERVER['PHP_SELF']), ['job-list.php','edit-job.php']) ? 'active' : '' ?>">
        <i class="material-icons">work</i> All Jobs
    </a>
    <a href="/jobwebsite/admin/jobs/add-job.php" class="jv-sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'add-job.php' ? 'active' : '' ?>">
        <i class="material-icons">add_circle</i> Post New Job
    </a>

    <div class="jv-sidebar-label">Applicants</div>
    <a href="/jobwebsite/admin/applications/applications.php" class="jv-sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'applications.php' ? 'active' : '' ?>">
        <i class="material-icons">people</i> Applications
    </a>

    <div class="mt-auto" style="padding: 1.5rem;">
        <a href="/jobwebsite/" target="_blank" class="jv-sidebar-link" style="margin:0;padding:0.6rem 1rem;font-size:0.82rem;">
            <i class="material-icons" style="font-size:16px">open_in_new</i> View Site
        </a>
        <a href="/jobwebsite/admin/logout.php" class="jv-sidebar-link" style="margin:0;padding:0.6rem 1rem;font-size:0.82rem;color:#f87171;">
            <i class="material-icons" style="font-size:16px">logout</i> Logout
        </a>
    </div>
</aside>

<!-- CONTENT AREA -->
<div class="jv-admin-content">
    <div class="jv-admin-topbar">
        <div class="jv-admin-title"><?= isset($adminTitle) ? htmlspecialchars($adminTitle) : 'Dashboard' ?></div>
        <div style="display:flex;align-items:center;gap:8px;color:var(--jv-text-muted);font-size:0.85rem;">
            <i class="material-icons" style="font-size:18px">admin_panel_settings</i>
            <?= htmlspecialchars('GUNPARK') ?>
        </div>
    </div>
    <div class="jv-admin-inner">
