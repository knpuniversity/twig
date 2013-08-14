<?php

require __DIR__.'/../src/Product.php';

$product = new Product();
$product->setName('Super cool widget phone!');
$product->setPrice(599.99);

return array(
    'product' => $product
);
