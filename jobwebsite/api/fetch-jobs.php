<?php
/**
 * API: Fetch Jobs (AJAX endpoint)
 * Returns all active jobs as JSON
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

$result = $conn->query("SELECT id, title, company, location, job_type, salary, created_at FROM jobs WHERE is_active = 1 ORDER BY created_at DESC");

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch jobs.']);
    exit();
}

$jobs = [];
while ($row = $result->fetch_assoc()) {
    $row['time_ago'] = timeAgo($row['created_at']);
    $jobs[] = $row;
}

echo json_encode(['success' => true, 'jobs' => $jobs]);
