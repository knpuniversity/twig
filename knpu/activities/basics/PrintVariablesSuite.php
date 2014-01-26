<?php

use KnpU\ActivityRunner\Result;
require __DIR__ . '/../shared/AbstractSuite.php';

class PrintVariablesSuite extends AbstractSuite
{
    public function runTest(Result $resultSummary)
    {
        $context = require(__DIR__.'/context_print_variables.php');

        $this->testHasProductName($resultSummary, $context);
        $this->testHasPrice($resultSummary, $context);
    }

    public function testHasProductName(Result $resultSummary, array $context)
    {
        $output = $resultSummary->getOutput();

        $this->assertContains($context['name'], $output,
            'Did you forget to render the name? Remember to use the "say something" syntax: {{ varName }}'
        );

        $h1El = $this->findElementByCss('h1', $output, 'Could not find the h1 tag! Where did you put it? :).');
        $h1Text = $h1El->text();

        $this->assertContains($context['name'], $h1Text,
            'It looks like you rendered the name, but did you forget to put it inside the h1 tag?'
        );
    }

    public function testHasPrice(Result $resultSummary, array $context)
    {
        $output = $resultSummary->getOutput();

        $this->assertContains((string) $context['price'], $output,
            'Did you forget to render the price? Remember to use the "say something" syntax: {{ varName }}'
        );

        $priceEl = $this->findElementByCss('.price', $output, 'Could not find the .price element at all!');
        $priceText = $priceEl->text();

        $this->assertContains((string) $context['price'], $priceText,
            'It looks like you rendered the price, but did you forget to put it inside the `.price` tag?'
        );
    }
}
