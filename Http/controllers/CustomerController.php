<?php

class CustomerController
{
    public function shop()
    {
        include_once DIR . '/public/client/shop.php';
    }

    public function branches() {
        include_once DIR . '/public/client/branches.php';
    }

    public function processOrder() {

    }
}
