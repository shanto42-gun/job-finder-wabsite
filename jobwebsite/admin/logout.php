<?php
require_once __DIR__ . '/../../includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();
unset($_SESSION['admin_id'], $_SESSION['admin_username']);
header("Location: /jobwebsite/admin/");
exit();
