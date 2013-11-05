<?php

use KnpU\ActivityRunner\Assert\AssertSuite;
use KnpU\ActivityRunner\Result;

class PropertyAccessorSuite extends AssertSuite
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

        $h1Text = $this->getCrawler($output)->filter('h1')->text();

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

        $priceText = $this->getCrawler($output)->filter('.price')->text();

        $this->assertContains((string) $price, $priceText,
            'It looks like you rendered the price, but did you forget to put it inside the `.price` tag?'
        );
    }

    public function testHasPostedAt(Result $resultSummary, Product $product)
    {
        $output = $resultSummary->getOutput();
        // Now check that things are in the right spot.
        $postedAts = $this->getCrawler($output)->filter('.posted-at');

        $this->assertEquals(1, count($postedAts), 'Did you forget to render the date inside an element with a "posted-at" class?');
        $this->assertRegexp("/2013-06-05/", $postedAts->text());
    }
}
