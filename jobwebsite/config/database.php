<?php
/**
 * Database Configuration
 * Job Platform – Auto-creates the database if it doesn't exist
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'job_platform');

// First connect WITHOUT specifying a database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die('<div style="font-family:monospace;background:#1a0a0a;color:#f87171;padding:2rem;border:1px solid #ef4444;border-radius:8px;margin:2rem;">
        <b>Database Connection Failed:</b><br>' . htmlspecialchars($conn->connect_error) . '<br><br>
        Make sure XAMPP MySQL is running.
    </div>');
}

$conn->set_charset('utf8mb4');

// Create the database if it doesn't exist
$conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

// Now select it
$conn->select_db(DB_NAME);

// Auto-run setup SQL if tables don't exist yet
$result = $conn->query("SHOW TABLES LIKE 'jobs'");
if ($result->num_rows === 0) {
    $sql = file_get_contents(__DIR__ . '/../setup.sql');
    // Remove CREATE DATABASE / USE statements so we don't conflict
    $sql = preg_replace('/^CREATE DATABASE.*?;/im', '', $sql);
    $sql = preg_replace('/^USE .*?;/im', '', $sql);

    // Execute multi-statement SQL
    $conn->multi_query($sql);
    // Flush all results
    do { $conn->use_result(); } while ($conn->more_results() && $conn->next_result());
}

// Fix admin password — always ensure 'YouBTech' has the correct hash for 'admin123'
$correctAdminHash = password_hash('admin123', PASSWORD_DEFAULT);
$fixAdmin = $conn->prepare("UPDATE admin_users SET password = ? WHERE username = 'YouBTech'");
if ($fixAdmin) {
    $fixAdmin->bind_param("s", $correctAdminHash);
    $fixAdmin->execute();
    $fixAdmin->close();
}

// Fix sample user passwords — ensure test accounts use 'password123'
$correctUserHash = password_hash('password123', PASSWORD_DEFAULT);
$fixUsers = $conn->prepare("UPDATE users SET password = ? WHERE email IN ('ahmed@example.com','sara@example.com')");
if ($fixUsers) {
    $fixUsers->bind_param("s", $correctUserHash);
    $fixUsers->execute();
    $fixUsers->close();
}
