<?php
/**
 * API: Submit Job Application
 * Handles the apply form POST submission
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

$jobId    = intval($_POST['job_id'] ?? 0);
$fullName = sanitize($_POST['full_name'] ?? '');
$whatsapp = sanitize($_POST['whatsapp'] ?? '');
$email    = sanitize($_POST['email'] ?? '');
$message  = sanitize($_POST['message'] ?? '');

// Validation
if ($jobId <= 0 || empty($fullName) || empty($whatsapp) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit();
}

// Check job exists
$stmt = $conn->prepare("SELECT id FROM jobs WHERE id = ? AND is_active = 1");
$stmt->bind_param("i", $jobId);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Job not found or no longer available.']);
    exit();
}
$stmt->close();

// Get user ID if logged in
$userId = isLoggedIn() ? intval($_SESSION['user_id']) : null;

// Prevent duplicate applications from same email for same job
$stmt2 = $conn->prepare("SELECT id FROM applications WHERE job_id = ? AND email = ?");
$stmt2->bind_param("is", $jobId, $email);
$stmt2->execute();
$stmt2->store_result();
if ($stmt2->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'You have already applied for this job.']);
    exit();
}
$stmt2->close();

// Insert application
$stmt3 = $conn->prepare("INSERT INTO applications (job_id, user_id, full_name, email, whatsapp, message) VALUES (?, ?, ?, ?, ?, ?)");
$stmt3->bind_param("iissss", $jobId, $userId, $fullName, $email, $whatsapp, $message);

if ($stmt3->execute()) {
    echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit application. Please try again.']);
}

$stmt3->close();
