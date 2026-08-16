<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

if (isLoggedIn()) {
    redirect('/jobwebsite/');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter your email and password.';
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_name']  = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                setFlash('success', 'Welcome back, ' . $user['full_name'] . '! 👋');
                redirect('/jobwebsite/');
            } else {
                $error = 'Incorrect password. Please try again.';
            }
        } else {
            $error = 'No account found with that email address.';
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
    <title>Login – JobVerse</title>
    <meta name="description" content="Log in to your JobVerse account to explore and apply for jobs.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/jobwebsite/assets/css/style.css">
</head>
<body>
<div class="jv-auth-wrapper">
    <div class="jv-auth-card">
        <!-- Logo -->
        <div class="jv-auth-logo">
            <a href="/jobwebsite/">
                <img src="/jobwebsite/assets/images/logo.png" alt="JobVerse">
            </a>
            <div class="jv-auth-title">Welcome back 👋</div>
            <p class="jv-auth-subtitle">Log in to continue your job search</p>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger jv-alert mb-3 py-2" role="alert">
            <i class="material-icons align-middle me-1" style="font-size:18px">error</i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="/jobwebsite/login.php" novalidate>
            <div class="mb-3">
                <label class="form-label jv-label">Email Address</label>
                <input type="email" class="form-control jv-input" name="email" id="email"
                       placeholder="you@email.com" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="mb-4">
                <label class="form-label jv-label">Password</label>
                <div class="position-relative">
                    <input type="password" class="form-control jv-input" name="password" id="password"
                           placeholder="Your password" required>
                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3"
                            onclick="togglePassword()" style="color:var(--jv-text-muted);padding:0;">
                        <i class="material-icons" id="passwordToggleIcon" style="font-size:20px">visibility</i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn jv-btn-primary w-100 mb-3">
                <i class="material-icons align-middle me-1" style="font-size:18px">login</i>
                Log In
            </button>
        </form>

        <div class="jv-divider">or</div>

        <p class="text-center" style="color:var(--jv-text-muted);font-size:0.9rem;">
            Don't have an account?
            <a href="/jobwebsite/signup.php" class="text-decoration-none" style="color:var(--jv-primary-light);font-weight:600;">
                Sign up free
            </a>
        </p>

        <p class="text-center mt-3" style="font-size:0.8rem;color:var(--jv-text-muted)">
            <a href="/jobwebsite/admin/" class="text-decoration-none" style="color:var(--jv-text-muted);">
                <i class="material-icons align-middle" style="font-size:14px">admin_panel_settings</i>
                Admin Panel
            </a>
        </p>
    </div>
</div>

<script>
function togglePassword() {
    const pw = document.getElementById('password');
    const icon = document.getElementById('passwordToggleIcon');
    if (pw.type === 'password') {
        pw.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        pw.type = 'password';
        icon.textContent = 'visibility';
    }
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
</body>
</html>
