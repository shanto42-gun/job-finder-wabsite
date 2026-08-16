<?php
$adminTitle = 'Post New Job';
require_once __DIR__ . '/../../admin/includes/admin-auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = sanitize($_POST['title'] ?? '');
    $company     = sanitize($_POST['company'] ?? '');
    $location    = sanitize($_POST['location'] ?? '');
    $jobType     = sanitize($_POST['job_type'] ?? '');
    $salary      = sanitize($_POST['salary'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $skills      = sanitize($_POST['skills'] ?? '');
    $experience  = sanitize($_POST['experience'] ?? '');
    $isActive    = isset($_POST['is_active']) ? 1 : 0;

    if (empty($title) || empty($company) || empty($location) || empty($jobType) || empty($description)) {
        $error = 'Please fill in all required fields.';
    } else {
        $stmt = $conn->prepare("INSERT INTO jobs (title, company, location, job_type, salary, description, skills, experience, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssi", $title, $company, $location, $jobType, $salary, $description, $skills, $experience, $isActive);

        if ($stmt->execute()) {
            setFlash('success', "Job '{$title}' posted successfully!");
            $stmt->close();
            redirect('/jobwebsite/admin/jobs/job-list.php');
        } else {
            $error = 'Failed to post job. Please try again.';
            $stmt->close();
        }
    }
}

require_once __DIR__ . '/../../admin/includes/admin-header.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="/jobwebsite/admin/jobs/job-list.php" class="btn jv-btn-outline btn-sm">
        <i class="material-icons align-middle" style="font-size:18px">arrow_back</i>
    </a>
    <div>
        <h2 style="font-size:1.3rem;font-weight:700;margin:0;">Post New Job</h2>
        <p style="color:var(--jv-text-muted);font-size:0.85rem;margin:0;">Fill in the details below</p>
    </div>
</div>

<?php if ($error): ?>
<div class="alert alert-danger jv-alert mb-3 py-2">
    <i class="material-icons align-middle me-1" style="font-size:18px">error</i>
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<form method="POST">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="jv-admin-card">
                <div class="jv-form-section-title">Job Information</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label jv-label">Job Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control jv-input" name="title"
                               placeholder="e.g. Senior Frontend Developer" required
                               value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label jv-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control jv-input" name="company"
                               placeholder="e.g. TechNova Inc." required
                               value="<?= htmlspecialchars($_POST['company'] ?? '') ?>">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label jv-label">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control jv-input" name="location"
                               placeholder="e.g. Karachi, Pakistan or Remote" required
                               value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label jv-label">Job Type <span class="text-danger">*</span></label>
                        <select class="form-control jv-input" name="job_type" required>
                            <option value="">Select type...</option>
                            <?php foreach (['Full-time','Part-time','Remote','Contract','Internship'] as $type): ?>
                            <option value="<?= $type ?>" <?= ($_POST['job_type'] ?? '') === $type ? 'selected' : '' ?>>
                                <?= $type ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label jv-label">Salary <span class="text-muted">(optional)</span></label>
                        <input type="text" class="form-control jv-input" name="salary"
                               placeholder="e.g. $3,000–$5,000/mo"
                               value="<?= htmlspecialchars($_POST['salary'] ?? '') ?>">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label jv-label">Experience Required <span class="text-muted">(optional)</span></label>
                        <input type="text" class="form-control jv-input" name="experience"
                               placeholder="e.g. 2+ years"
                               value="<?= htmlspecialchars($_POST['experience'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label jv-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control jv-input" name="description" rows="8"
                                  placeholder="Full job description..." required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label jv-label">Skills / Requirements <span class="text-muted">(comma-separated)</span></label>
                        <input type="text" class="form-control jv-input" name="skills"
                               placeholder="e.g. PHP, MySQL, JavaScript, Git"
                               value="<?= htmlspecialchars($_POST['skills'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="jv-admin-card" style="position:sticky;top:80px;">
                <div class="jv-form-section-title">Publish Settings</div>
                <div class="form-check mb-4" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);border-radius:10px;padding:1rem 1rem 1rem 3rem;">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                           <?= !isset($_POST['title']) || isset($_POST['is_active']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="isActive" style="color:var(--jv-text);font-weight:600;">
                        Publish immediately
                        <div style="font-size:0.78rem;color:var(--jv-text-muted);font-weight:400;">Job will be visible on the site</div>
                    </label>
                </div>

                <button type="submit" class="btn jv-btn-primary w-100 mb-2">
                    <i class="material-icons align-middle me-1">publish</i>
                    Post Job
                </button>
                <a href="/jobwebsite/admin/jobs/job-list.php" class="btn jv-btn-outline w-100">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>

    </div></div></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
</body></html>
