<?php
require_once __DIR__ . '/includes/auth-check.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/jobwebsite/profile.php');
}

$userId   = $_SESSION['user_id'];
$fullName = sanitize($_POST['full_name'] ?? '');
$phone    = sanitize($_POST['phone'] ?? '');

if (empty($fullName)) {
    setFlash('error', 'Full name cannot be empty.');
    redirect('/jobwebsite/profile.php');
}

$stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ? WHERE id = ?");
$stmt->bind_param("ssi", $fullName, $phone, $userId);

if ($stmt->execute()) {
    $_SESSION['user_name'] = $fullName;
    setFlash('success', 'Profile updated successfully!');
} else {
    setFlash('error', 'Failed to update profile. Please try again.');
}

$stmt->close();
redirect('/jobwebsite/profile.php');
