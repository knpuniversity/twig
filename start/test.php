<?php

require __DIR__.'/src/Product.php';

// setup some variables
$pageTitle = 'Suit Up!';
$products = array(
    new Product('Serious Businessman', 'formal.png'),
    new Product('Penguin Dress', 'dress.png'),
    new Product('Sportstar Penguin', 'sports.png'),
    new Product('Angel Costume', 'angel-costume.png'),
    new Product('Penguin Accessories', 'swatter.png'),
    new Product('Super Cool Penguin', 'super-cool.png'),
);

// render out PHP template
include __DIR__.'/templates/homepage.php';