<?php include __DIR__ . '/../components/header.php'; ?>

<div class="login-page">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="width: 350px;">
            <h3 class="text-center mb-3">Login</h3>
            <form>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" placeholder="Enter email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" placeholder="Enter password" required>
                </div>
                <button type="submit" class="btn btn-danger w-100">Login</button>
                <p class="text-center mt-3">
                    Don't have an account? <a href="signup.php" class="text-danger">Sign Up</a>
                </p>
            </form>
        </div>
    </div>
</div>