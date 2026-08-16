<?php
$pageTitle = 'My Profile';
require_once __DIR__ . '/includes/auth-check.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';

$userId = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Count total applications
$stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM applications WHERE user_id = ?");
$stmt2->bind_param("i", $userId);
$stmt2->execute();
$appCount = $stmt2->get_result()->fetch_assoc()['total'];
$stmt2->close();

// Recent applications
$stmt3 = $conn->prepare("
    SELECT a.*, j.title AS job_title, j.company, j.job_type
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    WHERE a.user_id = ?
    ORDER BY a.applied_at DESC
    LIMIT 5
");
$stmt3->bind_param("i", $userId);
$stmt3->execute();
$recentApps = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt3->close();

$initials = strtoupper(substr($user['full_name'], 0, 1));
?>

<div class="container-fluid px-4 py-4">
    <div class="row g-4">

        <!-- Profile Sidebar -->
        <div class="col-lg-4">
            <div class="jv-profile-card text-center mb-3">
                <div class="jv-avatar"><?= $initials ?></div>
                <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:0.25rem;">
                    <?= htmlspecialchars($user['full_name']) ?>
                </h2>
                <p style="color:var(--jv-text-muted);font-size:0.9rem;margin-bottom:1.5rem;">
                    <?= htmlspecialchars($user['email']) ?>
                </p>

                <!-- Stats -->
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="jv-stat-box">
                            <div class="jv-stat-number"><?= $appCount ?></div>
                            <div class="jv-stat-label">Applied</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="jv-stat-box">
                            <div class="jv-stat-number">✓</div>
                            <div class="jv-stat-label">Active</div>
                        </div>
                    </div>
                </div>

                <a href="/jobwebsite/logout.php" class="btn jv-btn-danger w-100">
                    <i class="material-icons align-middle me-1" style="font-size:18px">logout</i>
                    Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Edit Profile -->
            <div class="jv-profile-card mb-4">
                <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:1.5rem;">
                    <i class="material-icons align-middle me-2 text-primary" style="font-size:20px">edit</i>
                    Edit Profile
                </h3>

                <form method="POST" action="/jobwebsite/update-profile.php">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label jv-label">Full Name</label>
                            <input type="text" class="form-control jv-input" name="full_name"
                                   value="<?= htmlspecialchars($user['full_name']) ?>" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label jv-label">WhatsApp / Phone</label>
                            <input type="tel" class="form-control jv-input" name="phone"
                                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                   placeholder="+92 300 1234567">
                        </div>
                        <div class="col-12">
                            <label class="form-label jv-label">Email Address</label>
                            <input type="email" class="form-control jv-input" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                            <small style="color:var(--jv-text-muted);font-size:0.78rem;">Email cannot be changed</small>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn jv-btn-primary">
                                <i class="material-icons align-middle me-1" style="font-size:18px">save</i>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Recent Applications -->
            <div class="jv-profile-card">
                <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:1.5rem;">
                    <i class="material-icons align-middle me-2 text-primary" style="font-size:20px">history</i>
                    Recent Applications
                </h3>

                <?php if (empty($recentApps)): ?>
                <div class="jv-empty" style="padding:2rem 0;">
                    <i class="material-icons">send</i>
                    <p>You haven't applied to any jobs yet.</p>
                    <a href="/jobwebsite/" class="btn jv-btn-primary btn-sm mt-2">Explore Jobs</a>
                </div>
                <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($recentApps as $app): ?>
                    <div style="background:rgba(255,255,255,0.03);border:1px solid var(--jv-border);border-radius:12px;padding:1rem;display:flex;align-items:center;gap:1rem;">
                        <div class="jv-company-avatar" style="width:40px;height:40px;font-size:1rem;border-radius:10px;flex-shrink:0;">
                            <?= strtoupper(substr($app['company'], 0, 1)) ?>
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-weight:600;font-size:0.95rem;"><?= htmlspecialchars($app['job_title']) ?></div>
                            <div style="color:var(--jv-text-muted);font-size:0.82rem;"><?= htmlspecialchars($app['company']) ?></div>
                        </div>
                        <div class="text-end">
                            <span class="jv-badge jv-badge-<?= getJobTypeBadge($app['job_type']) ?>" style="font-size:0.7rem;"><?= htmlspecialchars($app['job_type']) ?></span>
                            <div style="font-size:0.75rem;color:var(--jv-text-muted);margin-top:4px;"><?= timeAgo($app['applied_at']) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
