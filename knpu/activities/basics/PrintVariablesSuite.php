<?php

use KnpU\ActivityRunner\Assert\AssertSuite;

use KnpU\ActivityRunner\Result;

class PrintVariablesSuite extends AssertSuite
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

        $h1Text = $this->getCrawler($output)->filter('h1')->text();

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

        $priceText = $this->getCrawler($output)->filter('.price')->text();

        $this->assertContains((string) $context['price'], $priceText,
            'It looks like you rendered the price, but did you forget to put it inside the `.price` tag?'
        );
    }
}
