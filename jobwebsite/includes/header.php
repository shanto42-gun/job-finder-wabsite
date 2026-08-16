<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/functions.php';
$flash = getFlash();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' – JobVerse' : 'JobVerse – Find Your Dream Job' ?></title>
    <meta name="description" content="<?= isset($pageDesc) ? htmlspecialchars($pageDesc) : 'Discover thousands of job opportunities on JobVerse. Apply now and take the next step in your career.' ?>">

    <!-- MDB Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" />
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/jobwebsite/assets/css/style.css">
</head>
<body>

<!-- ========== NAVBAR ========== -->
<nav class="navbar navbar-expand-lg navbar-dark jv-navbar sticky-top" id="mainNav">
    <div class="container-fluid px-4">
        <!-- Brand -->
        <a class="navbar-brand jv-brand" href="/jobwebsite/">
            <img src="/jobwebsite/assets/images/logo.png" alt="JobVerse" height="36" class="jv-logo">
            <span>Job<span class="jv-brand-accent">Verse</span></span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0" type="button" data-mdb-collapse-init data-mdb-target="#navbarContent">
            <i class="material-icons">menu</i>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">

                <!-- 🔍 SEARCH BOX -->
                <li class="nav-item me-2">
                    <div class="jv-navbar-search" id="navbarSearchWrap">
                        <i class="material-icons jv-search-icon">search</i>
                        <input
                            type="text"
                            id="navJobSearch"
                            class="jv-search-input"
                            placeholder="Search jobs, companies..."
                            autocomplete="off"
                        >
                        <span class="jv-search-clear d-none" id="searchClear" title="Clear">
                            <i class="material-icons" style="font-size:16px">close</i>
                        </span>
                    </div>
                </li>

                <?php if (isLoggedIn()): ?>
                <li class="nav-item">
                    <a class="nav-link jv-nav-link <?= $currentPage === 'profile.php' ? 'active' : '' ?>" href="/jobwebsite/profile.php">
                        <i class="material-icons align-middle me-1" style="font-size:18px">person</i>
                        <?= htmlspecialchars($_SESSION['user_name'] ?? 'Profile') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link jv-nav-link" href="/jobwebsite/logout.php">
                        <i class="material-icons align-middle me-1" style="font-size:18px">logout</i> Logout
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link jv-nav-link <?= $currentPage === 'login.php' ? 'active' : '' ?>" href="/jobwebsite/login.php">
                        <i class="material-icons align-middle me-1" style="font-size:18px">login</i> Login
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a class="btn jv-btn-primary btn-sm px-4" href="/jobwebsite/signup.php">
                        Sign Up
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<?php if ($flash): ?>
<div class="container-fluid px-4 mt-3">
    <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : htmlspecialchars($flash['type']) ?> alert-dismissible fade show jv-alert" role="alert">
        <i class="material-icons align-middle me-2"><?= $flash['type'] === 'success' ? 'check_circle' : ($flash['type'] === 'error' ? 'error' : 'info') ?></i>
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-mdb-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<!-- Main Content Wrapper -->
<main class="jv-main">
