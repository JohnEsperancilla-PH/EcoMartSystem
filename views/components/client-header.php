<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top" style="z-index: 1030;">
        <div class="container">
        <a class="navbar-brand" href="/shop">
            <img src="..\images\ecomart-logo.png" alt="EcoMart Logo" height="40">
        </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="/shop">Shop</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/branches">Branches</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/process-order">Order</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/logout" id="logout-link">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('logout-link').addEventListener('click', function() {
            localStorage.removeItem('orderList');
        });
    </script>