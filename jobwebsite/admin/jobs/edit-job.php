<?php
$adminTitle = 'Edit Job';
require_once __DIR__ . '/../../admin/includes/admin-auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('error', 'Invalid job ID.');
    redirect('/jobwebsite/admin/jobs/job-list.php');
}

// Fetch existing job
$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$job) {
    setFlash('error', 'Job not found.');
    redirect('/jobwebsite/admin/jobs/job-list.php');
}

$error = '';

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
        $stmt2 = $conn->prepare("UPDATE jobs SET title=?, company=?, location=?, job_type=?, salary=?, description=?, skills=?, experience=?, is_active=? WHERE id=?");
        $stmt2->bind_param("ssssssssii", $title, $company, $location, $jobType, $salary, $description, $skills, $experience, $isActive, $id);

        if ($stmt2->execute()) {
            setFlash('success', "Job '{$title}' updated successfully!");
            $stmt2->close();
            redirect('/jobwebsite/admin/jobs/job-list.php');
        } else {
            $error = 'Update failed. Please try again.';
            $stmt2->close();
        }
    }
    // Update local vars for repopulation
    $job = array_merge($job, [
        'title' => $title, 'company' => $company, 'location' => $location,
        'job_type' => $jobType, 'salary' => $salary, 'description' => $description,
        'skills' => $skills, 'experience' => $experience, 'is_active' => $isActive
    ]);
}

require_once __DIR__ . '/../../admin/includes/admin-header.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="/jobwebsite/admin/jobs/job-list.php" class="btn jv-btn-outline btn-sm">
        <i class="material-icons align-middle" style="font-size:18px">arrow_back</i>
    </a>
    <div>
        <h2 style="font-size:1.3rem;font-weight:700;margin:0;">Edit Job</h2>
        <p style="color:var(--jv-text-muted);font-size:0.85rem;margin:0;"><?= htmlspecialchars($job['title']) ?></p>
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
                               value="<?= htmlspecialchars($job['title']) ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label jv-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control jv-input" name="company"
                               value="<?= htmlspecialchars($job['company']) ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label jv-label">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control jv-input" name="location"
                               value="<?= htmlspecialchars($job['location']) ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label jv-label">Job Type <span class="text-danger">*</span></label>
                        <select class="form-control jv-input" name="job_type" required>
                            <?php foreach (['Full-time','Part-time','Remote','Contract','Internship'] as $type): ?>
                            <option value="<?= $type ?>" <?= $job['job_type'] === $type ? 'selected' : '' ?>>
                                <?= $type ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label jv-label">Salary</label>
                        <input type="text" class="form-control jv-input" name="salary"
                               value="<?= htmlspecialchars($job['salary'] ?? '') ?>">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label jv-label">Experience Required</label>
                        <input type="text" class="form-control jv-input" name="experience"
                               value="<?= htmlspecialchars($job['experience'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label jv-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control jv-input" name="description" rows="8" required><?= htmlspecialchars($job['description']) ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label jv-label">Skills <span class="text-muted">(comma-separated)</span></label>
                        <input type="text" class="form-control jv-input" name="skills"
                               value="<?= htmlspecialchars($job['skills'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="jv-admin-card" style="position:sticky;top:80px;">
                <div class="jv-form-section-title">Publish Settings</div>
                <div class="form-check mb-4" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);border-radius:10px;padding:1rem 1rem 1rem 3rem;">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                           <?= $job['is_active'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="isActive" style="color:var(--jv-text);font-weight:600;">
                        Published
                        <div style="font-size:0.78rem;color:var(--jv-text-muted);font-weight:400;">Visible on the public site</div>
                    </label>
                </div>

                <button type="submit" class="btn jv-btn-primary w-100 mb-2">
                    <i class="material-icons align-middle me-1">save</i> Save Changes
                </button>
                <a href="/jobwebsite/admin/jobs/job-list.php" class="btn jv-btn-outline w-100 mb-2">Cancel</a>
                <a href="/jobwebsite/admin/jobs/delete-job.php?id=<?= $id ?>"
                   class="btn jv-btn-danger w-100"
                   onclick="return confirm('Permanently delete this job?')">
                    <i class="material-icons align-middle me-1" style="font-size:16px">delete</i> Delete Job
                </a>
            </div>
        </div>
    </div>
</form>

    </div></div></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
</body></html>
