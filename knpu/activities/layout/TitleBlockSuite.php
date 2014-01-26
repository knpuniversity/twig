<?php

use KnpU\ActivityRunner\Result;

require __DIR__ . '/../shared/AbstractSuite.php';

class BasicLayoutSuite extends AbstractSuite
{
    public function runTest(Result $resultSummary)
    {
        $expectedTitle = 'Products Home';
        $output = $resultSummary->getOutput();

        $layoutInput = $resultSummary->getInput('layout.twig');
        $productInput = $resultSummary->getInput('product.twig');

        // look for {% block title %} with any number of spaces
        $err = "Make sure you create a new `title` block in `layout.twig`: `{% block title %}Twig! Yeehaw!{% endblock %}`";
        $this->assertRegExp('#{%\s*block\s+title\s*%}#', $layoutInput, $err);

        // look for {% block title %} with any number of spaces
        $err = "To override the title in `product.twig`, add a `{% block title %}` to the `product.twig` template. Put the customized title between it and `{% endblock %}`";
        $this->assertRegExp('#{%\s*block\s+title\s*#', $productInput, $err);

        // look for the title tag inside the layout's #layout element
        $titleEl = $this->findElementByCss('title', $output, 'I can\'t find your <title> tag at all! Check to make sure it\'s still there!');
        $title = $titleEl->text();
        // trim extra space
        $title = trim($title);
        $err = sprintf(
            "I see the <title> tag, but instead of saying `%s`, it says `%s`. Check to see that you're setting the correct title in the `title` block in `product.twig`",
            $expectedTitle,
            $title
        );
        $this->assertEquals($expectedTitle, $title, $err);
    }
}
