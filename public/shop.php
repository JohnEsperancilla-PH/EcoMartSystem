<?php

require_once __DIR__ . '/../Models/Categories.php';

$categoryModel = new Categories();
$categories = $categoryModel->getAllCategories();

require_once __DIR__ . '/../views/client/shop.view.php';
