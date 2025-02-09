<?php
include 'includes/header.php'; // Include universal header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoMart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
</head>

<body>
<div class="container mt-4">
    <!-- Home Image Carousel -->
    <div id="promotionCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/ecomart-home-1.jpg" style="height: 360px; object-fit: cover;" class="d-block w-100" alt="Promotion 1">
            </div>
        </div>
    </div>
    
    <!-- What's New Section -->
    <h2 class="mt-4">What's New</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="placeholder-img">Image</div>
                <div class="card-body">
                    <h5 class="card-title">Product 1</h5>
                    <p class="card-text">$10.00</p>
                    <button class="btn btn-primary">Add to Cart</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="placeholder-img">Image</div>
                <div class="card-body">
                    <h5 class="card-title">Product 2</h5>
                    <p class="card-text">$15.00</p>
                    <button class="btn btn-primary">Add to Cart</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="placeholder-img">Image</div>
                <div class="card-body">
                    <h5 class="card-title">Product 1</h5>
                    <p class="card-text">$10.00</p>
                    <button class="btn btn-primary">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Advertisement Section -->
    <div id="promotionCarousel" class="carousel slide mt-4" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/ecomart-home-2.png" style="height: 360px; object-fit: cover;" class="d-block w-100" alt="Promotion 1">
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php include 'includes/footer.php'; ?>
