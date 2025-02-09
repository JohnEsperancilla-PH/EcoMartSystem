<?php
include 'includes/header.php'; // Include universal header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - EcoMart</title>
    <!-- Bootstrap 5.3 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <!-- Main Content Container (with top margin and padding to account for a fixed navbar if present) -->
    <div class="container mt-5 pt-4">
    <h1 class="mb-4">Contact Us</h1>
    <p>Please reach out to the appropriate department below:</p>
    
    <div class="row">
        <!-- Customer Care Card -->
        <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
            Customer Care
            </div>
            <div class="card-body">
            <p>If you have inquiries about our products or would like a quote, please contact our Sales team.</p>
            <ul class="list-unstyled">
                <li><strong>Phone:</strong> (123) 456-7890</li>
                <li><strong>Email:</strong> customercare@ecomart.com.ph</li>
            </ul>
            </div>
        </div>
        </div>

        <!-- Careers Card -->
        <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
            Careers
            </div>
            <div class="card-body">
            <p>For any career related matters, please contact our Careers team.</p>
            <ul class="list-unstyled">
                <li><strong>Phone:</strong> (987) 654-3210</li>
                <li><strong>Email:</strong> hrd2@ecomart.com.ph</li>
            </ul>
            </div>
        </div>
        </div>

        <!-- Franchising Card -->
        <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
            Franchising
            </div>
            <div class="card-body">
            <p>For any franchising concerns, please contact our Franchising team to discuss details.</p>
            <ul class="list-unstyled">
                <li><strong>Phone:</strong> (555) 555-5555</li>
                <li><strong>Email:</strong> franchising@ecomart.com.ph</li>
            </ul>
            </div>
        </div>
        </div>
    </div>
    </div>

    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'includes/footer.php'; // Include universal footer ?>