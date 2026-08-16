<?php
require_once __DIR__ . '/../../includes/functions.php';

if (!isAdminLoggedIn()) {
    header("Location: /jobwebsite/admin/");
    exit();
}
