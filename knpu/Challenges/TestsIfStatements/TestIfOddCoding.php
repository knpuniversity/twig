<?php

namespace Challenges\TestsIfStatements;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class TestIfOddCoding implements CodingChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
Penguins are very superstitious about odd numbers. If we have an odd number
of total pants available, print a message that says:
`We promise, 1 more pair of pants is coming very soon!` to calm their fears.
EOF;
    }

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
{% for product in products %}
    <h3>
        {{ product.name }}

        <span class="price">{{ product.price }}</span>
    </h3>
{% endfor %}

{# Print your message down here if there are an odd number of pants! #}
EOF
        );
        $fileBuilder->setEntryPointFilename('fallCollection.twig');

        $fileBuilder->addFile(
            'PantsProduct.php',
            __DIR__.'/../stubs/PantsProduct.php'
        );

        return $fileBuilder;
    }

    public function getExecutionMode()
    {
        return self::EXECUTION_MODE_TWIG_NORMAL;
    }

    public function setupContext(CodingContext $context)
    {
        $context->requireFile('PantsProduct.php');

        $context->addVariable('products', array(
            new \PantsProduct('The Black and Tan Trouser', 50),
            new \PantsProduct('Antarctic Snow Pants (in leopard seal print)', 99),
            new \PantsProduct('South Shore Swim Shorts', 49),
        ));
    }

    public function grade(CodingExecutionResult $result)
    {
        $result->assertOutputContains('We promise, 1 more pair of pants is coming very soon!');
        $result->assertInputContains('fallCollection.twig', 'length', 'Use the `length` filter to count the products');
        $result->assertInputContains('fallCollection.twig', 'is odd', 'Use the `is odd` to see if there are an odd number of products');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% for product in products %}
    <h3>
        {{ product.name }}

        <span class="price">{{ product.price }}</span>
    </h3>
{% endfor %}

{% if products|length is odd %}
    <div class="alert alert-info">
        We promise, 1 more pair of pants is coming very soon!
    </div>
{% endif %}
EOF
        );
    }
}
