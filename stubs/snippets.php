<?php

/*
 * The pageData variable we pass into our homepage.twig template
 */

array(
    'title'    => 'Suit Up!',
    'summary'  => 'You\'re hip, you\'re cool, you\'re a penguin! Now, start dressing like one! Find the latest suits, bow-ties, swim shorts and other outfits here!',
    'hasSale'  => 1,
);

/*
 * The Product objects array
 */
 'products' => array(
     new Product('Serious Businessman', 'formal.png'),
     new Product('Penguin Dress', 'dress.png'),
     new Product('Sportstar Penguin', 'sports.png'),
     new Product('Angel Costume', 'angel-costume.png'),
     new Product('Penguin Accessories', 'swatter.png'),
     new Product('Super Cool Penguin', 'super-cool.png'),
 ),