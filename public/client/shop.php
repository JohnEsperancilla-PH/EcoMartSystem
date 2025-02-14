<?php

require_once DIR . '/Models/Categories.php';

$categoryModel = new Categories();
$categories = $categoryModel->getAllCategories();

require_once DIR . '/views/client/shop.view.php';
