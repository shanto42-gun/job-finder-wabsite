<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('error', 'Invalid job listing.');
    redirect('/jobwebsite/');
}

$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ? AND is_active = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    setFlash('error', 'Job listing not found or no longer available.');
    redirect('/jobwebsite/');
}

$job = $result->fetch_assoc();
$stmt->close();

$pageTitle = htmlspecialchars($job['title']) . ' at ' . htmlspecialchars($job['company']);
$pageDesc  = substr(strip_tags($job['description']), 0, 160);

require_once __DIR__ . '/includes/header.php';
?>

<div class="container-fluid px-4 py-4">
    <!-- Back Button -->
    <a href="/jobwebsite/" class="btn jv-btn-outline btn-sm mb-4">
        <i class="material-icons align-middle" style="font-size:18px">arrow_back</i>
        Back to Jobs
    </a>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Job Header Card -->
            <div class="jv-details-header mb-4">
                <div class="d-flex gap-3 align-items-start flex-wrap">
                    <div class="jv-company-avatar" style="width:64px;height:64px;font-size:1.6rem;border-radius:16px;flex-shrink:0;">
                        <?= strtoupper(substr($job['company'], 0, 1)) ?>
                    </div>
                    <div class="flex-grow-1">
                        <h1 style="font-size:1.8rem;font-weight:800;margin-bottom:0.25rem;">
                            <?= htmlspecialchars($job['title']) ?>
                        </h1>
                        <div class="jv-company-name mb-3" style="font-size:1.05rem;">
                            <?= htmlspecialchars($job['company']) ?>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <span class="jv-meta-chip">
                                <i class="material-icons">location_on</i>
                                <?= htmlspecialchars($job['location']) ?>
                            </span>
                            <span class="jv-badge jv-badge-<?= getJobTypeBadge($job['job_type']) ?>">
                                <?= htmlspecialchars($job['job_type']) ?>
                            </span>
                            <?php if ($job['experience']): ?>
                            <span class="jv-meta-chip">
                                <i class="material-icons">workspace_premium</i>
                                <?= htmlspecialchars($job['experience']) ?> experience
                            </span>
                            <?php endif; ?>
                            <span class="jv-meta-chip">
                                <i class="material-icons">schedule</i>
                                Posted <?= timeAgo($job['created_at']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Description -->
            <div class="jv-details-card">
                <div class="jv-section-label">Job Description</div>
                <div style="color:var(--jv-text);line-height:1.8;white-space:pre-line;">
                    <?= htmlspecialchars($job['description']) ?>
                </div>
            </div>

            <!-- Skills -->
            <?php if ($job['skills']): ?>
            <div class="jv-details-card">
                <div class="jv-section-label">Skills & Requirements</div>
                <div class="d-flex flex-wrap gap-1">
                    <?php foreach (explode(',', $job['skills']) as $skill): ?>
                    <span class="jv-skill-chip"><?= htmlspecialchars(trim($skill)) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Apply Card -->
            <div class="jv-details-card" style="position:sticky;top:90px;">
                <?php if ($job['salary']): ?>
                <div class="text-center mb-3">
                    <div class="jv-section-label">Salary</div>
                    <div class="jv-salary" style="font-size:1.3rem;">
                        <?= htmlspecialchars($job['salary']) ?>
                    </div>
                </div>
                <hr style="border-color:var(--jv-border);">
                <?php endif; ?>

                <div class="mb-3">
                    <div class="jv-section-label mb-2">About the Company</div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="jv-company-avatar" style="width:40px;height:40px;font-size:1rem;border-radius:10px;">
                            <?= strtoupper(substr($job['company'], 0, 1)) ?>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:0.95rem;"><?= htmlspecialchars($job['company']) ?></div>
                            <div style="font-size:0.8rem;color:var(--jv-text-muted);"><?= htmlspecialchars($job['location']) ?></div>
                        </div>
                    </div>
                </div>

                <hr style="border-color:var(--jv-border);">

                <button class="btn jv-btn-primary w-100 py-3 btn-apply-now"
                        data-job-id="<?= $job['id'] ?>"
                        data-job-title="<?= htmlspecialchars($job['title']) ?>">
                    <i class="material-icons align-middle me-1">send</i>
                    Apply Now
                </button>

                <p class="text-center mt-2" style="font-size:0.78rem;color:var(--jv-text-muted);">
                    Quick apply – takes less than 2 minutes
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
