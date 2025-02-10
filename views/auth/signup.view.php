<?php
include 'includes/header.php'; // Include universal header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | EcoMart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="signup-page">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="width: 350px;">
            <h3 class="text-center mb-3">Sign Up</h3>
            <form action="setup.php">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" placeholder="Enter email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mobile Number (PH)</label>
                    <input type="text" class="form-control" pattern="09[0-9]{9}" placeholder="09XXXXXXXXX" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" placeholder="Enter password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" placeholder="Re-enter password" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" required>
                    <label class="form-check-label">
                        I have read and agree to the <a href="terms.php">Terms and Conditions</a>
                    </label>
                </div>
                <button type="submit" class="btn btn-danger w-100">Next</button>
                <p class="text-center mt-3">
                    Already have an account? <a href="index.php" class="text-danger">Login</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>

<?php include 'includes/footer.php'; ?>
