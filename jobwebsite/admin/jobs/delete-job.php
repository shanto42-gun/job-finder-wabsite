<?php
require_once __DIR__ . '/../../admin/includes/admin-auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('error', 'Invalid job ID.');
    redirect('/jobwebsite/admin/jobs/job-list.php');
}

$stmt = $conn->prepare("DELETE FROM jobs WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    setFlash('success', 'Job deleted successfully.');
} else {
    setFlash('error', 'Failed to delete job or job not found.');
}

$stmt->close();
redirect('/jobwebsite/admin/jobs/job-list.php');
