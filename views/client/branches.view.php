<?php
include 'includes/header.php'; // Include universal header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Locator | EcoMart</title>
    <!-- Bootstrap 5.3 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<!-- Main Content Container (with top margin and padding to account for the fixed navbar) -->
<div class="container mt-5">
    <h1 class="mb-4">Store Locator</h1>
    <p>Find our store at the location below:</p>

    <!-- Responsive embed container for the Google Map using Bootstrap 5 ratio utility -->
    <div class="ratio ratio-16x9">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4035.1778637508564!2d120.41240824246626!3d15.919344950354462!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33914438e43cd959%3A0xb21146d5970b1f35!2sEco%20Mart!5e0!3m2!1sen!2sph!4v1739091024648!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'includes/footer.php'; // Include universal footer ?>
