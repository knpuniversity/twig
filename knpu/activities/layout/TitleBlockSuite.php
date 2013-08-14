<?php

use KnpU\ActivityRunner\Assert\AssertSuite;

class BasicLayoutSuite extends AssertSuite
{
    public function testLayoutIsExtended()
    {
        $context = $this->getActivity()->getContext();
        $expectedTitle = 'Products Home';

        $inputFiles = $this->getActivity()->getInputFiles();
        $layoutInput = $inputFiles->get('layout.twig');
        $productInput = $inputFiles->get('product.twig');

        // look for {% block title %} with any number of spaces
        $err = "Make sure you create a new `title` block in `layout.twig`: `{% block title %}Twig! Yeehaw!{% endblock %}`";
        $this->assertRegExp('#{%\s*block\s+title\s*%}#', $layoutInput, $err);

        // look for {% block title %} with any number of spaces
        $err = "To override the title in `product.twig`, add a `{% block title %}` to the `product.twig` template. Put the customized title between it and `{% endblock %}`";
        $this->assertRegExp('#{%\s*block\s+title\s*#', $productInput, $err);

        // look for the h1 tag inside the layout's #layout element
        $title = $this->getCrawler()->filter('title')->text();
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
