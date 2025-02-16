<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | EcoMart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/styles.css">
</head>

<body class="login-page">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="width: 350px;">
            <h3 class="text-center mb-3">Login</h3>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="/login">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control <?php echo isset($_SESSION['email_error']) ? 'is-invalid' : ''; ?>"
                        placeholder="Enter email" required>
                    <?php if (isset($_SESSION['email_error'])): ?>
                        <div class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['email_error']); ?></div>
                        <?php unset($_SESSION['email_error']); ?>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control <?php echo isset($_SESSION['password_error']) ? 'is-invalid' : ''; ?>"
                        placeholder="Enter password" required>
                    <?php if (isset($_SESSION['password_error'])): ?>
                        <div class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['password_error']); ?></div>
                        <?php unset($_SESSION['password_error']); ?>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control <?php echo isset($_SESSION['login_error']) ? 'is-invalid' : ''; ?>" required>
                        <option value="admin">Admin</option>
                        <option value="customer">Customer</option>
                    </select>
                    <?php if (isset($_SESSION['login_error'])): ?>
                        <div class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['login_error']); ?></div>
                        <?php unset($_SESSION['login_error']); ?>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-danger w-100">Login</button>
                <p class="text-center mt-3">
                    Don't have an account? <a href="/register" class="text-danger">Sign Up</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>