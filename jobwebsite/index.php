<?php
$pageTitle = 'Explore Jobs';
$pageDesc  = 'Browse hundreds of job opportunities. Find your perfect role on JobVerse.';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';

// Fetch all active jobs
$result = $conn->query("SELECT * FROM jobs WHERE is_active = 1 ORDER BY created_at DESC");
$jobs   = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$total  = count($jobs);
?>

<!-- Hero Section -->
<section class="jv-hero">
    <div class="container-fluid px-4">
        <div class="jv-hero-badge">
            <i class="material-icons" style="font-size:16px">auto_awesome</i>
            <?= $total ?> Live Opportunities
        </div>
        <h1>Find Your <span class="text-gradient">Dream Job</span><br>All In One Place</h1>
        <p>Explore curated job listings from top companies. Apply in seconds, right from your phone.</p>
        <?php if (!isLoggedIn()): ?>
        <div style="animation: fadeInUp 0.6s ease 0.3s both; display:inline-block;">
            <a href="/jobwebsite/signup.php" class="btn jv-btn-primary px-5 me-2">
                <i class="material-icons align-middle me-1" style="font-size:18px">rocket_launch</i>Get Started Free
            </a>
            <a href="/jobwebsite/login.php" class="btn jv-btn-outline px-4">Log In</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Jobs Section -->
<section class="py-2 pb-5">
    <div class="container-fluid px-4">
        <!-- Section Header -->
        <div class="jv-section-header">
            <div>
                <h2 class="jv-section-title">Latest Jobs</h2>
                <p class="jv-section-count" id="jobCount"><?= $total ?> job<?= $total !== 1 ? 's' : '' ?> available</p>
            </div>
            <button class="jv-refresh-btn" id="refreshJobsBtn" title="Refresh jobs">
                <i class="material-icons">refresh</i>
                <span>Refresh</span>
            </button>
        </div>

        <!-- Jobs Grid -->
        <?php if (empty($jobs)): ?>
        <div class="jv-empty">
            <i class="material-icons">work_off</i>
            <p>No jobs available at the moment. Check back soon!</p>
        </div>
        <?php else: ?>
        <div class="jv-jobs-grid" id="jobsContainer">
            <?php foreach ($jobs as $i => $job): ?>
            <div class="jv-job-card" style="animation-delay:<?= $i * 0.06 ?>s">
                <div class="d-flex gap-3 align-items-start mb-2">
                    <div class="jv-company-avatar"><?= strtoupper(substr($job['company'], 0, 1)) ?></div>
                    <div class="flex-grow-1">
                        <div class="jv-job-title"><?= htmlspecialchars($job['title']) ?></div>
                        <div class="jv-company-name"><?= htmlspecialchars($job['company']) ?></div>
                    </div>
                    <a href="/jobwebsite/job-details.php?id=<?= $job['id'] ?>"
                       class="text-decoration-none" style="color:var(--jv-text-muted)"
                       title="View details">
                        <i class="material-icons" style="font-size:20px">open_in_new</i>
                    </a>
                </div>

                <div class="jv-job-meta">
                    <span class="jv-meta-chip">
                        <i class="material-icons">location_on</i>
                        <?= htmlspecialchars($job['location']) ?>
                    </span>
                    <span class="jv-badge jv-badge-<?= getJobTypeBadge($job['job_type']) ?>">
                        <?= htmlspecialchars($job['job_type']) ?>
                    </span>
                </div>

                <?php if ($job['salary']): ?>
                <div class="jv-salary">
                    <i class="material-icons align-middle" style="font-size:17px">payments</i>
                    <?= htmlspecialchars($job['salary']) ?>
                </div>
                <?php endif; ?>

                <div class="jv-card-footer">
                    <span class="jv-posted-date">
                        <i class="material-icons align-middle" style="font-size:15px">schedule</i>
                        <?= timeAgo($job['created_at']) ?>
                    </span>
                    <button class="btn jv-btn-primary btn-sm btn-apply-now"
                            data-job-id="<?= $job['id'] ?>"
                            data-job-title="<?= htmlspecialchars($job['title']) ?>">
                        Apply Now
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
