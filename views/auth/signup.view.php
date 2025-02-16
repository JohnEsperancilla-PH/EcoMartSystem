<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | Ecomart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light min-vh-100">
    <div class="container py-4">
        <div class="card border-0 shadow mx-auto" style="max-width: 800px;">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-danger">Create Your Account</h2>
                    <p class="text-muted">Join Ecomart and start shopping sustainably</p>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form method="POST" action="/register" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3 fw-bold">Personal Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">First Name</label>
                                    <input type="text" name="first_name"
                                        class="form-control <?php echo isset($_SESSION['first_name_error']) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>"
                                        placeholder="Enter first name" required>
                                    <?php if (isset($_SESSION['first_name_error'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['first_name_error']); ?></div>
                                        <?php unset($_SESSION['first_name_error']); ?>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Last Name</label>
                                    <input type="text" name="last_name"
                                        class="form-control <?php echo isset($_SESSION['last_name_error']) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>"
                                        placeholder="Enter last name" required>
                                    <?php if (isset($_SESSION['last_name_error'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['last_name_error']); ?></div>
                                        <?php unset($_SESSION['last_name_error']); ?>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Gender</label>
                                    <select class="form-select <?php echo isset($_SESSION['gender_error']) ? 'is-invalid' : ''; ?>"
                                        name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                    <?php if (isset($_SESSION['gender_error'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['gender_error']); ?></div>
                                        <?php unset($_SESSION['gender_error']); ?>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Birthdate</label>
                                    <input type="date" name="birthdate"
                                        class="form-control <?php echo isset($_SESSION['birthdate_error']) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo htmlspecialchars($_POST['birthdate'] ?? ''); ?>"
                                        required>
                                    <?php if (isset($_SESSION['birthdate_error'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['birthdate_error']); ?></div>
                                        <?php unset($_SESSION['birthdate_error']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Contact & Security -->
                        <div class="col-md-6">
                            <h5 class="mb-3 fw-bold">Contact & Security</h5>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email"
                                        class="form-control <?php echo isset($_SESSION['email_error']) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                        placeholder="Enter email" required>
                                    <?php if (isset($_SESSION['email_error'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['email_error']); ?></div>
                                        <?php unset($_SESSION['email_error']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mobile Number (PH)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                    <input type="text" name="mobile_number"
                                        class="form-control <?php echo isset($_SESSION['mobile_error']) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo htmlspecialchars($_POST['mobile_number'] ?? ''); ?>"
                                        pattern="09[0-9]{9}" placeholder="09XXXXXXXXX" required>
                                    <?php if (isset($_SESSION['mobile_error'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['mobile_error']); ?></div>
                                        <?php unset($_SESSION['mobile_error']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password"
                                        class="form-control <?php echo isset($_SESSION['password_error']) ? 'is-invalid' : ''; ?>"
                                        placeholder="Enter password" required>
                                    <?php if (isset($_SESSION['password_error'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['password_error']); ?></div>
                                        <?php unset($_SESSION['password_error']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="confirm_password"
                                        class="form-control <?php echo isset($_SESSION['confirm_password_error']) ? 'is-invalid' : ''; ?>"
                                        placeholder="Re-enter password" required>
                                    <?php if (isset($_SESSION['confirm_password_error'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['confirm_password_error']); ?></div>
                                        <?php unset($_SESSION['confirm_password_error']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="form-check mb-4">
                        <input class="form-check-input <?php echo isset($_SESSION['terms_error']) ? 'is-invalid' : ''; ?>"
                            type="checkbox" name="terms_accepted" id="terms_accepted" required>
                        <label class="form-check-label" for="terms_accepted">
                            I agree to the <a href="terms.php" class="text-danger text-decoration-none" target="_blank">Terms and Conditions</a> and Privacy Policy
                        </label>
                        <?php if (isset($_SESSION['terms_error'])): ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($_SESSION['terms_error']); ?></div>
                            <?php unset($_SESSION['terms_error']); ?>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg w-100 mb-3">Create Account</button>

                    <p class="text-center mb-0">
                        Already have an account? <a href="/login" class="text-danger text-decoration-none">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>