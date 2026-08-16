<?php
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    setFlash('warning', 'Please log in to access that page.');
    redirect('/jobwebsite/login.php');
}
