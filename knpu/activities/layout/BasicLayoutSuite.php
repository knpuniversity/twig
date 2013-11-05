<?php

use KnpU\ActivityRunner\Assert\AssertSuite;
use KnpU\ActivityRunner\Result;

class BasicLayoutSuite extends AssertSuite
{
    public function runTest(Result $resultSummary)
    {
        $input = $resultSummary->getInput('product.twig');
        $output = $resultSummary->getOutput();

        // look for {% extends with any number of spaces
        $err = "Make sure you `extend` layout.twig: {% extends 'layout.twig' %}";
        $this->assertRegExp('#{%\s*extends#', $input, $err);

        // look for layout.twig
        $this->assertRegExp('#layout.twig#', $input, $err);

        // look for the h1 tag inside the layout's #layout element
        $wrapper = $this->getCrawler($output)->filter('#layout .product');
        $err = "I don't see the .product div anymore. Make sure you've surrounded the content in product.twig with `{%block body %}` `{% endblock %} so that its content shows up in the layout";
        $this->assertNotEmpty($wrapper, $err);
    }
}
