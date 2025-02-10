<?php include __DIR__ . '/../components/header.php'; ?>
<div class="signup-page">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="width: 350px;">
            <h3 class="text-center mb-3">Sign Up</h3>
            <form method="POST" action="/register">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mobile Number (PH)</label>
                    <input type="text" name="mobile_number" class="form-control" pattern="09[0-9]{9}" placeholder="09XXXXXXXXX" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter password" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="terms_accepted" required>
                    <label class="form-check-label">
                        I have read and agree to the <a href="terms.php">Terms and Conditions</a>
                    </label>
                </div>
                <button type="submit" class="btn btn-danger w-100">Next</button>
            </form>
        </div>
    </div>
</div>
