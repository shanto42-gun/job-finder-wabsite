/**
 * JobVerse – main.js
 * AJAX job loading, apply form, UI interactions
 */

document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // APPLY NOW – Open Modal
    // ==========================================
    document.querySelectorAll('.btn-apply-now').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const jobId = this.dataset.jobId;
            const jobTitle = this.dataset.jobTitle;

            document.getElementById('applyJobId').value = jobId;
            document.getElementById('applyJobTitle').textContent = jobTitle;

            // Reset form state
            document.getElementById('applyFormArea').classList.remove('d-none');
            document.getElementById('applySuccess').classList.add('d-none');
            document.getElementById('applyForm').reset();
            document.getElementById('applyJobId').value = jobId;
            document.getElementById('applyError').classList.add('d-none');

            // Pre-fill if session data exists (handled in PHP already)

            const modal = new mdb.Modal(document.getElementById('applyModal'));
            modal.show();
        });
    });

    // ==========================================
    // APPLY FORM – Submit via AJAX
    // ==========================================
    const applyForm = document.getElementById('applyForm');
    if (applyForm) {
        applyForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('applySubmitBtn');
            const spinner = document.getElementById('applySpinner');
            const btnText = submitBtn.querySelector('.btn-text');
            const errorDiv = document.getElementById('applyError');

            // Client-side validation
            const name = document.getElementById('applyName').value.trim();
            const whatsapp = document.getElementById('applyWhatsapp').value.trim();
            const email = document.getElementById('applyEmail').value.trim();
            const jobId = document.getElementById('applyJobId').value;

            if (!name || !whatsapp || !email || !jobId) {
                errorDiv.textContent = 'Please fill in all required fields.';
                errorDiv.classList.remove('d-none');
                return;
            }

            // Email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errorDiv.textContent = 'Please enter a valid email address.';
                errorDiv.classList.remove('d-none');
                return;
            }

            // Loading state
            errorDiv.classList.add('d-none');
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            try {
                const formData = new FormData(applyForm);
                const response = await fetch('/jobwebsite/api/apply.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('applyFormArea').classList.add('d-none');
                    document.getElementById('applySuccess').classList.remove('d-none');
                } else {
                    errorDiv.textContent = data.message || 'Something went wrong. Please try again.';
                    errorDiv.classList.remove('d-none');
                }
            } catch (err) {
                errorDiv.textContent = 'Network error. Please check your connection.';
                errorDiv.classList.remove('d-none');
            } finally {
                submitBtn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        });
    }

    // ==========================================
    // AJAX REFRESH JOBS
    // ==========================================
    const refreshBtn = document.getElementById('refreshJobsBtn');
    const jobsContainer = document.getElementById('jobsContainer');

    if (refreshBtn && jobsContainer) {
        refreshBtn.addEventListener('click', async function () {
            refreshBtn.classList.add('spinning');
            refreshBtn.disabled = true;

            try {
                const response = await fetch('/jobwebsite/api/fetch-jobs.php');
                const data = await response.json();

                if (data.success && data.jobs) {
                    renderJobs(data.jobs);
                    updateJobCount(data.jobs.length);
                }
            } catch (err) {
                console.error('Failed to refresh jobs:', err);
            } finally {
                setTimeout(() => {
                    refreshBtn.classList.remove('spinning');
                    refreshBtn.disabled = false;
                }, 600);
            }
        });
    }

    function renderJobs(jobs) {
        if (!jobsContainer) return;

        if (jobs.length === 0) {
            jobsContainer.innerHTML = `
                <div class="col-12">
                    <div class="jv-empty">
                        <i class="material-icons">work_off</i>
                        <p>No jobs available at the moment.</p>
                    </div>
                </div>`;
            return;
        }

        const typeBadges = {
            'Full-time': 'jv-badge-primary',
            'Part-time': 'jv-badge-warning',
            'Remote': 'jv-badge-success',
            'Contract': 'jv-badge-info',
            'Internship': 'jv-badge-secondary'
        };

        jobsContainer.innerHTML = jobs.map((job, i) => `
            <div class="jv-job-card" style="animation-delay:${i * 0.06}s">
                <div class="d-flex gap-3 align-items-start mb-2">
                    <div class="jv-company-avatar">${job.company.charAt(0).toUpperCase()}</div>
                    <div class="flex-1 min-w-0">
                        <div class="jv-job-title">${escapeHtml(job.title)}</div>
                        <div class="jv-company-name">${escapeHtml(job.company)}</div>
                    </div>
                    <a href="/jobwebsite/job-details.php?id=${job.id}" class="btn-link text-muted">
                        <i class="material-icons">open_in_new</i>
                    </a>
                </div>

                <div class="jv-job-meta">
                    <span class="jv-meta-chip"><i class="material-icons">location_on</i>${escapeHtml(job.location)}</span>
                    <span class="jv-badge ${typeBadges[job.job_type] || 'jv-badge-secondary'}">${escapeHtml(job.job_type)}</span>
                </div>

                ${job.salary ? `<div class="jv-salary"><i class="material-icons align-middle" style="font-size:17px">payments</i> ${escapeHtml(job.salary)}</div>` : ''}

                <div class="jv-card-footer">
                    <span class="jv-posted-date"><i class="material-icons align-middle" style="font-size:15px">schedule</i> ${escapeHtml(job.time_ago)}</span>
                    <button class="btn jv-btn-primary btn-sm btn-apply-now"
                        data-job-id="${job.id}"
                        data-job-title="${escapeHtml(job.title)}">
                        Apply Now
                    </button>
                </div>
            </div>
        `).join('');

        // Re-bind apply buttons
        document.querySelectorAll('.btn-apply-now').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                document.getElementById('applyJobId').value = this.dataset.jobId;
                document.getElementById('applyJobTitle').textContent = this.dataset.jobTitle;
                document.getElementById('applyFormArea').classList.remove('d-none');
                document.getElementById('applySuccess').classList.add('d-none');
                document.getElementById('applyForm').reset();
                document.getElementById('applyJobId').value = this.dataset.jobId;
                document.getElementById('applyError').classList.add('d-none');
                const modal = new mdb.Modal(document.getElementById('applyModal'));
                modal.show();
            });
        });
    }

    function updateJobCount(count) {
        const countEl = document.getElementById('jobCount');
        if (countEl) countEl.textContent = count + ' job' + (count !== 1 ? 's' : '') + ' available';
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str || ''));
        return div.innerHTML;
    }

    // ==========================================
    // NAVBAR SEARCH (Live Filtering)
    // ==========================================
    const navSearch = document.getElementById('navJobSearch');
    const searchClear = document.getElementById('searchClear');

    if (navSearch) {
        navSearch.addEventListener('input', function (e) {
            const query = e.target.value.toLowerCase().trim();

            if (query.length > 0) {
                searchClear.classList.remove('d-none');
            } else {
                searchClear.classList.add('d-none');
            }

            // If we are on the jobs page, filter live
            const jobCards = document.querySelectorAll('.jv-job-card');
            if (jobCards.length > 0) {
                let visibleCount = 0;
                jobCards.forEach(card => {
                    const title = card.querySelector('.jv-job-title').textContent.toLowerCase();
                    const company = card.querySelector('.jv-company-name').textContent.toLowerCase();

                    if (title.includes(query) || company.includes(query)) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Update count if it exists
                updateJobCount(visibleCount);
                
                // Show empty state if nothing found
                let emptyState = document.getElementById('searchEmptyState');
                if (visibleCount === 0) {
                    if (!emptyState) {
                        emptyState = document.createElement('div');
                        emptyState.id = 'searchEmptyState';
                        emptyState.className = 'col-12 mt-4';
                        emptyState.innerHTML = `
                            <div class="jv-empty">
                                <i class="material-icons">search_off</i>
                                <h2>No jobs found</h2>
                                <p>We couldn't find any jobs matching "${query}"</p>
                            </div>
                        `;
                        jobsContainer.appendChild(emptyState);
                    } else {
                        emptyState.querySelector('p').textContent = `We couldn't find any jobs matching "${query}"`;
                        emptyState.style.display = 'block';
                    }
                } else if (emptyState) {
                    emptyState.style.display = 'none';
                }
            } else if (query.length > 0 && e.type === 'change') {
                // If they press enter on another page, send them to home with a search param (optional expansion)
                window.location.href = '/jobwebsite/?q=' + encodeURIComponent(query);
            }
        });

        if (searchClear) {
            searchClear.addEventListener('click', () => {
                navSearch.value = '';
                navSearch.dispatchEvent(new Event('input'));
                navSearch.focus();
            });
        }
        
        // If loaded with a query param from another page
        const urlParams = new URLSearchParams(window.location.search);
        const q = urlParams.get('q');
        if (q && navSearch) {
            navSearch.value = q;
            navSearch.dispatchEvent(new Event('input'));
        }
    }

    // ==========================================
    // FLASH AUTO-HIDE
    // ==========================================
    setTimeout(() => {
        document.querySelectorAll('.alert.jv-alert').forEach(el => {
            el.classList.remove('show');
            setTimeout(() => el.remove(), 300);
        });
    }, 5000);

});
