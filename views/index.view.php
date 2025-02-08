<?php
include 'includes/header.php'; // Include universal header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | EcoMart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
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
</body>
</html>

<?php include 'includes/footer.php'; ?>