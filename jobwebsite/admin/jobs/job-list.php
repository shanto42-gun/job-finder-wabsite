<?php
$adminTitle = 'All Jobs';
require_once __DIR__ . '/../../admin/includes/admin-auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../admin/includes/admin-header.php';

$jobs = $conn->query("SELECT * FROM jobs ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
$flash = getFlash();
?>

<?php if ($flash): ?>
<div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-danger' ?> jv-alert alert-dismissible fade show mb-4 py-2" role="alert">
    <?= htmlspecialchars($flash['message']) ?>
    <button type="button" class="btn-close" data-mdb-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 style="font-size:1.3rem;font-weight:700;margin:0;">All Job Listings</h2>
        <p style="color:var(--jv-text-muted);font-size:0.85rem;margin:4px 0 0;"><?= count($jobs) ?> total jobs</p>
    </div>
    <a href="/jobwebsite/admin/jobs/add-job.php" class="btn jv-btn-primary">
        <i class="material-icons align-middle me-1">add</i> Post New Job
    </a>
</div>

<div class="jv-admin-card" style="padding:0;overflow:hidden;">
    <?php if (empty($jobs)): ?>
    <div class="jv-empty" style="padding:3rem;">
        <i class="material-icons">work_off</i>
        <p>No jobs posted yet.</p>
        <a href="/jobwebsite/admin/jobs/add-job.php" class="btn jv-btn-primary mt-2">Post First Job</a>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="jv-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Job Title</th>
                <th>Company</th>
                <th>Type</th>
                <th>Salary</th>
                <th>Status</th>
                <th>Posted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($jobs as $i => $job): ?>
        <tr>
            <td style="color:var(--jv-text-muted);font-size:0.8rem;"><?= $i + 1 ?></td>
            <td>
                <div style="font-weight:600;font-size:0.9rem;"><?= htmlspecialchars($job['title']) ?></div>
                <div style="font-size:0.78rem;color:var(--jv-text-muted);"><?= htmlspecialchars($job['location']) ?></div>
            </td>
            <td style="font-size:0.88rem;"><?= htmlspecialchars($job['company']) ?></td>
            <td>
                <span class="jv-badge jv-badge-<?= getJobTypeBadge($job['job_type']) ?>" style="font-size:0.7rem;">
                    <?= htmlspecialchars($job['job_type']) ?>
                </span>
            </td>
            <td style="font-size:0.85rem;color:var(--jv-success);"><?= $job['salary'] ? htmlspecialchars($job['salary']) : '–' ?></td>
            <td>
                <?php if ($job['is_active']): ?>
                <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.78rem;color:#4ade80;">
                    <span style="width:6px;height:6px;border-radius:50%;background:#4ade80;display:inline-block;"></span> Active
                </span>
                <?php else: ?>
                <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.78rem;color:var(--jv-text-muted);">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--jv-text-muted);display:inline-block;"></span> Hidden
                </span>
                <?php endif; ?>
            </td>
            <td style="font-size:0.8rem;color:var(--jv-text-muted);"><?= timeAgo($job['created_at']) ?></td>
            <td>
                <div style="display:flex;gap:6px;">
                    <a href="/jobwebsite/admin/jobs/edit-job.php?id=<?= $job['id'] ?>"
                       class="btn jv-btn-outline btn-sm" title="Edit">
                        <i class="material-icons" style="font-size:16px">edit</i>
                    </a>
                    <a href="/jobwebsite/admin/jobs/delete-job.php?id=<?= $job['id'] ?>"
                       class="btn jv-btn-danger btn-sm"
                       onclick="return confirm('Delete this job? This cannot be undone.')" title="Delete">
                        <i class="material-icons" style="font-size:16px">delete</i>
                    </a>
                    <a href="/jobwebsite/job-details.php?id=<?= $job['id'] ?>" target="_blank"
                       class="btn jv-btn-outline btn-sm" title="Preview">
                        <i class="material-icons" style="font-size:16px">open_in_new</i>
                    </a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

    </div></div></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
</body></html>
