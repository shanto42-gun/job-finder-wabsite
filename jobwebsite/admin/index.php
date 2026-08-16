<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

if (isAdminLoggedIn()) {
    redirect('/jobwebsite/admin/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter your username and password.';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id']       = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                redirect('/jobwebsite/admin/dashboard.php');
            } else {
                $error = 'Incorrect password.';
            }
        } else {
            $error = 'Admin account not found.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – JobVerse</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/jobwebsite/assets/css/style.css">
</head>
<body>
<div class="jv-auth-wrapper">
    <div class="jv-auth-card" style="max-width: 400px;">
        <div class="jv-auth-logo">
            <div style="width:56px;height:56px;border-radius:14px;background:var(--jv-gradient);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i class="material-icons" style="font-size:28px;color:#fff;">admin_panel_settings</i>
            </div>
            <div class="jv-auth-title">Admin Login</div>
            <p class="jv-auth-subtitle">JobVerse Admin Panel</p>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger jv-alert mb-3 py-2">
            <i class="material-icons align-middle me-1" style="font-size:18px">error</i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="/jobwebsite/admin/">
            <div class="mb-3">
                <label class="form-label jv-label">Username</label>
                <input type="text" class="form-control jv-input" name="username"
                       placeholder="Admin username" required
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="mb-4">
                <label class="form-label jv-label">Password</label>
                <input type="password" class="form-control jv-input" name="password"
                       placeholder="Password" required>
            </div>
            <button type="submit" class="btn jv-btn-primary w-100">
                <i class="material-icons align-middle me-1" style="font-size:18px">lock_open</i>
                Login to Admin
            </button>
        </form>

        <p class="text-center mt-4" style="font-size:0.8rem; color: var(--jv-text-muted);">
            Hint: <code style="color:var(--jv-primary-light);">YouBTech</code> / <code style="color:var(--jv-primary-light);">admin123</code>
        </p>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
</body>
</html>
