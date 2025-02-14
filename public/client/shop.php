<?php

require_once DIR . '/Models/Categories.php';
require_once DIR . '/Models/Products.php';

$categoryModel = new Categories();
$productModel = new Products();

$categories = $categoryModel->getAllCategories();
$selectedCategory = $_GET['category'] ?? null;

if ($selectedCategory) {
    $products = $productModel->getProductsByCategory($selectedCategory);
} else {
    $products = $productModel->getAllProducts();
}

require_once DIR . '/views/client/shop.view.php';
