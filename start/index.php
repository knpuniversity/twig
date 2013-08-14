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
            'pageTitle' => 'Suit Up!',
            'products' => array(
                'Serious Businessman',
                'Penguin Dress',
                'Sportstar Penguin',
                'Angel Costume',
                'Penguin Accessories',
                'Super Cool Penguin',
            ),
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