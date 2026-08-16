<?php
require_once __DIR__ . '/includes/functions.php';
session_destroy();
header("Location: /jobwebsite/login.php");
exit();
