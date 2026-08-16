<?php
$adminTitle = 'Applications';
require_once __DIR__ . '/../../admin/includes/admin-auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../admin/includes/admin-header.php';

// Fetch all with job and user info
$applications = $conn->query("
    SELECT
        a.*,
        j.title AS job_title,
        j.company,
        j.job_type
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    ORDER BY a.applied_at DESC
")->fetch_all(MYSQLI_ASSOC);

$total = count($applications);
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 style="font-size:1.3rem;font-weight:700;margin:0;">All Applications</h2>
        <p style="color:var(--jv-text-muted);font-size:0.85rem;margin:4px 0 0;">
            <?= $total ?> application<?= $total !== 1 ? 's' : '' ?> received
        </p>
    </div>
    <div style="display:flex;align-items:center;gap:8px;font-size:0.85rem;color:var(--jv-text-muted);">
        <i class="material-icons" style="font-size:18px">people</i>
        <?= $total ?> total
    </div>
</div>

<div class="jv-admin-card" style="padding:0;overflow:hidden;">
    <?php if (empty($applications)): ?>
    <div class="jv-empty" style="padding:3rem;">
        <i class="material-icons">inbox</i>
        <p>No applications yet.</p>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="jv-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Applicant</th>
                <th>Contact</th>
                <th>Job Applied For</th>
                <th>Message</th>
                <th>Applied</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($applications as $i => $app): ?>
        <tr>
            <td style="color:var(--jv-text-muted);font-size:0.8rem;"><?= $i + 1 ?></td>
            <td>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="jv-company-avatar" style="width:36px;height:36px;font-size:0.9rem;border-radius:50%;">
                        <?= strtoupper(substr($app['full_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:0.88rem;"><?= htmlspecialchars($app['full_name']) ?></div>
                    </div>
                </div>
            </td>
            <td>
                <div style="font-size:0.82rem;">
                    <div style="margin-bottom:2px;">
                        <i class="material-icons align-middle" style="font-size:14px;color:var(--jv-text-muted);">email</i>
                        <a href="mailto:<?= htmlspecialchars($app['email']) ?>" style="color:var(--jv-primary-light);text-decoration:none;">
                            <?= htmlspecialchars($app['email']) ?>
                        </a>
                    </div>
                    <div>
                        <i class="material-icons align-middle" style="font-size:14px;color:var(--jv-text-muted);">phone</i>
                        <a href="https://wa.me/<?= preg_replace('/\D/', '', $app['whatsapp']) ?>" target="_blank" style="color:#4ade80;text-decoration:none;">
                            <?= htmlspecialchars($app['whatsapp']) ?>
                        </a>
                    </div>
                </div>
            </td>
            <td>
                <div style="font-weight:600;font-size:0.88rem;"><?= htmlspecialchars($app['job_title']) ?></div>
                <div style="font-size:0.78rem;color:var(--jv-text-muted);"><?= htmlspecialchars($app['company']) ?></div>
                <span class="jv-badge jv-badge-<?= getJobTypeBadge($app['job_type']) ?>" style="font-size:0.65rem;margin-top:4px;">
                    <?= htmlspecialchars($app['job_type']) ?>
                </span>
            </td>
            <td style="max-width:220px;">
                <?php if ($app['message']): ?>
                <div style="font-size:0.8rem;color:var(--jv-text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;"
                     title="<?= htmlspecialchars($app['message']) ?>">
                    <?= htmlspecialchars(substr($app['message'], 0, 80)) ?><?= strlen($app['message']) > 80 ? '...' : '' ?>
                </div>
                <?php else: ?>
                <span style="color:var(--jv-text-muted);font-size:0.8rem;">–</span>
                <?php endif; ?>
            </td>
            <td style="font-size:0.8rem;color:var(--jv-text-muted);white-space:nowrap;">
                <?= timeAgo($app['applied_at']) ?>
                <div style="font-size:0.72rem;"><?= date('M j, Y', strtotime($app['applied_at'])) ?></div>
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
