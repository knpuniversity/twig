<?php

use KnpU\ActivityRunner\Assert\AssertSuite;

class PrintVariablesSuite extends AssertSuite
{
    public function testHasProductName()
    {
        $context = $this->getActivity()->getContext();
        $output = $this->getOutput();

        $this->assertContains($context['name'], $output,
            'Did you forget to render the name? Remember to use the "say something" syntax: {{ varName }}'
        );

        $h1Text = $this->getCrawler()->filter('h1')->text();

        $this->assertContains($context['name'], $h1Text,
            'It looks like you rendered the name, but did you forget to put it inside the h1 tag?'
        );
    }

    public function testHasPrice()
    {
        $context = $this->getActivity()->getContext();
        $output = $this->getOutput();

        $this->assertContains((string) $context['price'], $output,
            'Did you forget to render the price? Remember to use the "say something" syntax: {{ varName }}'
        );

        $priceText = $this->getCrawler()->filter('.price')->text();

        $this->assertContains((string) $context['price'], $priceText,
            'It looks like you rendered the price, but did you forget to put it inside the `.price` tag?'
        );
    }
}
