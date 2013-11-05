<?php

require_once __DIR__.'/../src/Product.php';

$product = new Product();
$product->setName('Super cool widget phone!');
$product->setPrice(599.99);
$product->setPostedAt(new \DateTime('June 5, 2013'));

return array(
    'product' => $product
);
