<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/src/Product.php';

/*
 * Go to this file in your web-browser to render Twig templates:
 *
 *  * http://localhost/index.php            -> index.twig
 *  * http://localhost/index.php/contact    -> contact.twig
 *
 * ... etc ...
 */

// 1) create a Symfony Request, used only to help make each URL render a different Twig template
use Symfony\Component\HttpFoundation\Request;
$request = Request::createFromGlobals();
$uri = $request->getPathInfo();

// 2) bootstrap Twig!
$loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
$twig = new Twig_Environment($loader, array(
    // cache disabled, since this is just a testing project
    'cache' => false,
    'debug' => true,
    'strict_variables' => true
));
$twig->addExtension(new Twig_Extension_Debug());

// 3) create a few different "pages"
switch ($uri) {
    // The Homepage! (/)
    case '/':
        echo $twig->render('homepage.twig', array(
            'pageData' => array(
                'title'    => 'Suit Up!',
                'summary'  => 'You\'re <strong>hip</strong>, you\'re cool, you\'re a penguin! Now, start dressing like one! Find the latest suits, bow-ties, swim shorts and other outfits here!',
                'hasSale'  => 1,
            ),
            'products' => array(
                new Product('Serious Businessman', 'formal.png'),
                new Product('Penguin Dress', 'dress.png'),
                new Product('Sportstar Penguin', 'sports.png'),
            ),
            'featuredProducts' => array(
                new Product('Angel Costume', 'angel-costume.png'),
                new Product('Penguin Accessories', 'swatter.png'),
                new Product('Super Cool Penguin', 'super-cool.png'),
            ),
            'saleEndsAt' => new \DateTime('+1 month'),
        ));

        break;

    case '/contact':
        echo $twig->render('contact.twig', array(
            'pageData' => array(
                'title' => 'Find us in the south pole!',
            )
        ));
        break;

    // All other pages
    default:
        // if we have anything else, render the URL + .twig (e.g. /about -> about.twig)
        $template = substr($uri, 1).'.twig';

        echo $twig->render($template, array(
            'title' => 'Some random page!',
        ));
}