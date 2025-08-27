<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg border-0" style="border-radius: 15px;">
            <div class="card-header text-center" style="background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%); color: white; border-radius: 15px 15px 0 0;">
                <h4 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Login to BookHeaven</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>
                <div class="text-center">
                    <p class="mb-0">Don't have an account? <a href="/app.php?page=register" class="text-decoration-none">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
