<?php

namespace Challenges\ForLoopInlineIf;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class ForElseCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
This template works well enough, but it could be easier! Leverage Twig's
for-else ability to remove the if statement at the bottom. Make sure it still
prints out 'No pants for you!' in case the inventory is empty.
EOF;
    }

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
{% for product in products %}
    <h3>
        {{ product.name }}

        <span class="price">
            {{ product.price }}
        </span>
    </h3>
{% endfor %}

{% if products is empty %}
    <div class="alert alert-warning">
        No pants for you!
    </div>
{% endif %}
EOF
        );
        $fileBuilder->setEntryPointFilename('fallCollection.twig');

        $fileBuilder->addFileContents(
            'PantsProduct.php',
            file_get_contents(__DIR__.'/../stubs/PantsProduct.php')
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
            new \PantsProduct('The Black and Tan Trouser', 50),
            new \PantsProduct('Antarctic Snow Pants (in leopard seal print)', 99),
            new \PantsProduct('South Shore Swim Shorts', 49),
            new \PantsProduct('Starfish Halloween Costume', 35)
        ));
    }

    public function grade(CodingExecutionResult $result)
    {
        $result->assertInputContains('fallCollection.twig', 'else', 'Use an `else` with your `for` tag.');
        $result->assertOutputContains('No pants for you!');
        $result->assertInputDoesNotContain('fallCollection.twig', '{% if', 'You don\'t need the `{% if` statement that checks for the products anymore!');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% for product in products %}
    <h3>
        {{ product.name }}

        <span class="price">
            {{ product.price }}
        </span>
    </h3>
{% else %}
    <div class="alert alert-warning">
        No pants for you!
    </div>
{% endif %}
EOF
        );
    }
}
