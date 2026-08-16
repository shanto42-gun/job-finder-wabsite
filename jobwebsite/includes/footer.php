</main>
<!-- End Main Content -->

<!-- ========== FOOTER ========== -->
<footer class="jv-footer mt-auto py-4">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                <span class="jv-footer-brand">Job<span class="jv-brand-accent">Verse</span></span>
                <span class="jv-footer-copy ms-3">© <?= date('Y') ?> All rights reserved.</span>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <span class="jv-footer-copy">Built with ❤️ using PHP + MDBootstrap</span>
            </div>
        </div>
    </div>
</footer>

<!-- ========== APPLY MODAL ========== -->
<div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content jv-modal">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="applyModalLabel">
                    <i class="material-icons align-middle me-2 text-primary">send</i>
                    Apply for <span id="applyJobTitle" class="jv-brand-accent"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-mdb-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="applyFormArea">
                    <form id="applyForm" novalidate>
                        <input type="hidden" id="applyJobId" name="job_id">

                        <div class="mb-3">
                            <label class="form-label jv-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control jv-input" id="applyName" name="full_name"
                                   placeholder="Your full name" required
                                   value="<?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label jv-label">WhatsApp Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control jv-input" id="applyWhatsapp" name="whatsapp"
                                   placeholder="+92 300 1234567" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label jv-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control jv-input" id="applyEmail" name="email"
                                   placeholder="you@email.com" required
                                   value="<?= isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label jv-label">Short Message <span class="text-muted">(optional)</span></label>
                            <textarea class="form-control jv-input" id="applyMessage" name="message" rows="3"
                                      placeholder="Why are you a great fit for this role?"></textarea>
                        </div>

                        <div id="applyError" class="alert alert-danger d-none py-2"></div>

                        <button type="submit" class="btn jv-btn-primary w-100" id="applySubmitBtn">
                            <span class="btn-text">
                                <i class="material-icons align-middle me-1" style="font-size:18px">send</i> Submit Application
                            </span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" id="applySpinner"></span>
                        </button>
                    </form>
                </div>

                <!-- Success State -->
                <div id="applySuccess" class="text-center py-4 d-none">
                    <div class="jv-success-icon mb-3">
                        <i class="material-icons" style="font-size:64px;color:#22c55e">check_circle</i>
                    </div>
                    <h5 class="text-white mb-2">Application Sent! 🎉</h5>
                    <p class="text-muted">We've received your application. Good luck!</p>
                    <button class="btn jv-btn-outline mt-2" data-mdb-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MDB Bootstrap 5 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
<!-- Custom JS -->
<script src="/jobwebsite/assets/js/main.js"></script>
</body>
</html>
