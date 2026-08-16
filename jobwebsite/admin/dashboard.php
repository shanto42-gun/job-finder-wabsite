<?php
$adminTitle = 'Dashboard';
require_once __DIR__ . '/includes/admin-auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/admin-header.php';

// Stats
$totalJobs  = $conn->query("SELECT COUNT(*) AS c FROM jobs WHERE is_active=1")->fetch_assoc()['c'];
$totalApps  = $conn->query("SELECT COUNT(*) AS c FROM applications")->fetch_assoc()['c'];
$totalUsers = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$todayApps  = $conn->query("SELECT COUNT(*) AS c FROM applications WHERE DATE(applied_at) = CURDATE()")->fetch_assoc()['c'];

// Recent applications
$recentApps = $conn->query("
    SELECT a.full_name, a.email, a.applied_at, j.title AS job_title
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    ORDER BY a.applied_at DESC
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Recent jobs
$recentJobs = $conn->query("SELECT id, title, company, job_type, created_at FROM jobs ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
?>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <?php $stats = [
        ['icon'=>'work','label'=>'Active Jobs','value'=>$totalJobs,'color'=>'rgba(124,58,237,0.15)','icolor'=>'var(--jv-primary-light)'],
        ['icon'=>'people','label'=>'Total Applicants','value'=>$totalApps,'color'=>'rgba(6,182,212,0.15)','icolor'=>'#22d3ee'],
        ['icon'=>'person','label'=>'Registered Users','value'=>$totalUsers,'color'=>'rgba(34,197,94,0.15)','icolor'=>'#4ade80'],
        ['icon'=>'today','label'=> "Today's Applications",'value'=>$todayApps,'color'=>'rgba(245,158,11,0.15)','icolor'=>'#fbbf24'],
    ];
    foreach ($stats as $s): ?>
    <div class="col-sm-6 col-xl-3">
        <div class="jv-stat-card">
            <div class="jv-stat-icon" style="background:<?= $s['color'] ?>;">
                <i class="material-icons" style="color:<?= $s['icolor'] ?>"><?= $s['icon'] ?></i>
            </div>
            <div class="jv-stat-number" style="font-size:2.2rem;font-weight:800;"><?= $s['value'] ?></div>
            <div class="jv-stat-label"><?= $s['label'] ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-3">
    <!-- Recent Applications -->
    <div class="col-lg-7">
        <div class="jv-admin-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 style="font-size:1rem;font-weight:700;margin:0;">Recent Applications</h3>
                <a href="/jobwebsite/admin/applications/applications.php" class="btn jv-btn-outline btn-sm">View All</a>
            </div>
            <?php if (empty($recentApps)): ?>
            <div class="jv-empty" style="padding:2rem 0;">
                <i class="material-icons">inbox</i><p>No applications yet</p>
            </div>
            <?php else: ?>
            <table class="jv-table">
                <thead><tr><th>Applicant</th><th>Job</th><th>Applied</th></tr></thead>
                <tbody>
                <?php foreach ($recentApps as $app): ?>
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:0.88rem;"><?= htmlspecialchars($app['full_name']) ?></div>
                        <div style="font-size:0.78rem;color:var(--jv-text-muted);"><?= htmlspecialchars($app['email']) ?></div>
                    </td>
                    <td style="font-size:0.85rem;"><?= htmlspecialchars($app['job_title']) ?></td>
                    <td style="font-size:0.8rem;color:var(--jv-text-muted);"><?= timeAgo($app['applied_at']) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Jobs + Quick Actions -->
    <div class="col-lg-5">
        <div class="jv-admin-card mb-3">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem;">Quick Actions</h3>
            <div class="d-grid gap-2">
                <a href="/jobwebsite/admin/jobs/add-job.php" class="btn jv-btn-primary">
                    <i class="material-icons align-middle me-1">add_circle</i> Post New Job
                </a>
                <a href="/jobwebsite/admin/jobs/job-list.php" class="btn jv-btn-outline">
                    <i class="material-icons align-middle me-1">work</i> Manage Jobs
                </a>
                <a href="/jobwebsite/admin/applications/applications.php" class="btn jv-btn-outline">
                    <i class="material-icons align-middle me-1">people</i> View All Applicants
                </a>
            </div>
        </div>

        <div class="jv-admin-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 style="font-size:1rem;font-weight:700;margin:0;">Latest Jobs</h3>
                <a href="/jobwebsite/admin/jobs/job-list.php" class="btn jv-btn-outline btn-sm">Manage</a>
            </div>
            <?php foreach ($recentJobs as $job): ?>
            <div style="display:flex;align-items:center;gap:12px;padding:0.6rem 0;border-bottom:1px solid var(--jv-border);">
                <div class="jv-company-avatar" style="width:36px;height:36px;font-size:0.9rem;border-radius:8px;flex-shrink:0;">
                    <?= strtoupper(substr($job['company'], 0, 1)) ?>
                </div>
                <div class="flex-grow-1" style="min-width:0;">
                    <div style="font-size:0.85rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        <?= htmlspecialchars($job['title']) ?>
                    </div>
                    <div style="font-size:0.75rem;color:var(--jv-text-muted);"><?= htmlspecialchars($job['company']) ?></div>
                </div>
                <a href="/jobwebsite/admin/jobs/edit-job.php?id=<?= $job['id'] ?>" style="color:var(--jv-text-muted);">
                    <i class="material-icons" style="font-size:18px">edit</i>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

    </div><!-- jv-admin-inner -->
</div><!-- jv-admin-content -->
</div><!-- jv-admin-layout -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
</body></html>
