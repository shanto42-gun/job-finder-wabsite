<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

if (isLoggedIn()) {
    redirect('/jobwebsite/');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName  = sanitize($_POST['full_name'] ?? '');
    $email     = sanitize($_POST['email'] ?? '');
    $phone     = sanitize($_POST['phone'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirmPw = $_POST['confirm_password'] ?? '';

    if (empty($fullName) || empty($email) || empty($password) || empty($confirmPw)) {
        $error = 'Please fill in all required fields.';
    } elseif (strlen($fullName) < 2) {
        $error = 'Full name must be at least 2 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirmPw) {
        $error = 'Passwords do not match.';
    } else {
        // Check duplicate email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            $stmt->close();
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt2 = $conn->prepare("INSERT INTO users (full_name, email, password, phone) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("ssss", $fullName, $email, $hash, $phone);

            if ($stmt2->execute()) {
                $userId = $conn->insert_id;
                $_SESSION['user_id']    = $userId;
                $_SESSION['user_name']  = $fullName;
                $_SESSION['user_email'] = $email;
                setFlash('success', 'Account created! Welcome to JobVerse, ' . $fullName . ' 🎉');
                redirect('/jobwebsite/');
            } else {
                $error = 'Registration failed. Please try again.';
            }
            $stmt2->close();
        }
        if (isset($stmt) && !$stmt->errno) {
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up – JobVerse</title>
    <meta name="description" content="Create your free JobVerse account and start applying for amazing jobs today.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/jobwebsite/assets/css/style.css">
</head>
<body>
<div class="jv-auth-wrapper">
    <div class="jv-auth-card" style="max-width: 480px;">
        <!-- Logo -->
        <div class="jv-auth-logo">
            <a href="/jobwebsite/">
                <img src="/jobwebsite/assets/images/logo.png" alt="JobVerse">
            </a>
            <div class="jv-auth-title">Create Account ✨</div>
            <p class="jv-auth-subtitle">Join thousands finding their dream jobs</p>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger jv-alert mb-3 py-2" role="alert">
            <i class="material-icons align-middle me-1" style="font-size:18px">error</i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="/jobwebsite/signup.php" novalidate>
            <div class="mb-3">
                <label class="form-label jv-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control jv-input" name="full_name"
                       placeholder="Your full name" required
                       value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label jv-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control jv-input" name="email"
                       placeholder="you@email.com" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label jv-label">WhatsApp / Phone <span class="text-muted">(optional)</span></label>
                <input type="tel" class="form-control jv-input" name="phone"
                       placeholder="+92 300 1234567"
                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label jv-label">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control jv-input" name="password"
                       placeholder="Min. 6 characters" required>
            </div>

            <div class="mb-4">
                <label class="form-label jv-label">Confirm Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control jv-input" name="confirm_password"
                       placeholder="Repeat your password" required>
            </div>

            <button type="submit" class="btn jv-btn-primary w-100 mb-3">
                <i class="material-icons align-middle me-1" style="font-size:18px">person_add</i>
                Create Account
            </button>
        </form>

        <div class="jv-divider">or</div>

        <p class="text-center" style="color:var(--jv-text-muted);font-size:0.9rem;">
            Already have an account?
            <a href="/jobwebsite/login.php" class="text-decoration-none" style="color:var(--jv-primary-light);font-weight:600;">
                Log in
            </a>
        </p>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
</body>
</html>
