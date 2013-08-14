<?php

use KnpU\ActivityRunner\Assert\AssertSuite;

class PropertyAccessorSuite extends AssertSuite
{
    public function testHasProductName()
    {
        $context = $this->getActivity()->getContext();
        $output = $this->getOutput();
        $name = $context['product']->getName();

        $this->assertContains($name, $output,
            'Did you forget to render the name? Remember to use the "say something" syntax: {{ product.varName }}'
        );

        $h1Text = $this->getCrawler()->filter('h1')->text();

        $this->assertContains($name, $h1Text,
            'It looks like you rendered the name, but did you forget to put it inside the h1 tag?'
        );
    }

    public function testHasPrice()
    {
        $context = $this->getActivity()->getContext();
        $output = $this->getOutput();
        $price = $context['product']->getPrice();

        $this->assertContains((string) $price, $output,
            'Did you forget to render the price? Remember to use the "say something" syntax: {{ product.varName }}'
        );

        $priceText = $this->getCrawler()->filter('.price')->text();

        $this->assertContains((string) $price, $priceText,
            'It looks like you rendered the price, but did you forget to put it inside the `.price` tag?'
        );
    }
}
