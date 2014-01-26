<?php

use KnpU\ActivityRunner\Result;

require __DIR__ . '/../shared/AbstractSuite.php';

class PropertyAccessorSuite extends AbstractSuite
{
    public function runTest(Result $resultSummary)
    {
        $context = require(__DIR__.'/context.php');
        $this->testHasProductName($resultSummary, $context['product']);
        $this->testHasPrice($resultSummary, $context['product']);
        $this->testHasPostedAt($resultSummary, $context['product']);
    }

    public function testHasProductName(Result $resultSummary, Product $product)
    {
        $output = $resultSummary->getOutput();
        $name = $product->getName();

        $this->assertContains($name, $output,
            'Did you forget to render the name? Remember to use the "say something" syntax: {{ product.varName }}'
        );

        $h1El = $this->findElementByCss('h1', $output, 'Could not find the h1 tag! Where did you put it? :).');
        $h1Text = $h1El->text();

        $this->assertContains($name, $h1Text,
            'It looks like you rendered the name, but did you forget to put it inside the h1 tag?'
        );
    }

    public function testHasPrice(Result $resultSummary, Product $product)
    {
        $output = $resultSummary->getOutput();
        $price = $product->getPrice();

        $this->assertContains((string) $price, $output,
            'Did you forget to render the price? Remember to use the "say something" syntax: {{ product.varName }}'
        );

        $priceEl = $this->findElementByCss('.price', $output, 'Could not find the .price element at all!');

        $this->assertContains((string) $price, $priceEl->text(),
            'It looks like you rendered the price, but did you forget to put it inside the `.price` tag?'
        );
    }

    public function testHasPostedAt(Result $resultSummary, Product $product)
    {
        $output = $resultSummary->getOutput();
        // Now check that things are in the right spot.
        $postedAts = $this->findElementByCss('.posted-at', $output, 'Did you forget to render the date inside an element with a "posted-at" class?');

        $this->assertRegexp("/2013-06-05/", $postedAts->text());
    }
}
