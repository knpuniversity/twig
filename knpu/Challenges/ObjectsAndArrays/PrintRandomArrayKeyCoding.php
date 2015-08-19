<?php

namespace Challenges\ObjectsAndArrays;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class PrintRandomArrayKeyCoding implements CodingChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
Penguins are known to be indecisive. To make things easier for them,
we're creating a random product selector. In the template, you have a
new `randomProductKey` variable, that's a number from 0 to 3 - matching
the indices on the `products` array. Use this new variable to print
the "random product" inside the `h3` tag.
EOF;
    }

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
<div class="featured-product">
    <!-- use randomProductKey to randomly select and print a random product -->
</div>

{% for product in products %}
    <h3>{{ product }}</h3>
{% endfor %}
EOF
        );

        return $fileBuilder;
    }

    public function getExecutionMode()
    {
        return self::EXECUTION_MODE_TWIG_NORMAL;
    }

    public function setupContext(CodingContext $context)
    {
        $context->addVariable('products', array(
            'The Black and Tan Trouser',
            'Antarctic Snow Pants (in leopard seal print)',
            'South Shore Swim Shorts',
            'Starfish Halloween Costume'
        ));
        $context->addVariable('randomProductKey', rand(0, 3));
    }

    public function grade(CodingExecutionResult $result)
    {
        $result->assertInputContains('fallCollection.twig', 'products[randomProductKey]', 'Use the `products[variableName]` syntax to get a variable key from the array.');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
<div class="featured-product">
    {{ products[randomProductKey] }}
</div>

{% for product in products %}
    <h3>{{ product }}</h3>
{% endfor %}
EOF
        );
    }
}
