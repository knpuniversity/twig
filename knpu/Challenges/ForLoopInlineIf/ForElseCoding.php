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
for-else ability to remove the if statement at the bottom. We're passing
in an empty `products` array variable, so make sure that `No pants for you!`
still prints out since the inventory is empty.
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

{% if products is empty %}
    <div class="alert alert-warning">
        No pants for you!
    </div>
{% endif %}
EOF
        );
        $fileBuilder->setEntryPointFilename('fallCollection.twig');

        return $fileBuilder;
    }

    public function getExecutionMode()
    {
        return self::EXECUTION_MODE_TWIG_NORMAL;
    }

    public function setupContext(CodingContext $context)
    {
        $context->addVariable('products', array());
    }

    public function grade(CodingExecutionResult $result)
    {
        $result->assertInputContains('fallCollection.twig', 'else', 'Use an `else` with your `for` tag.');
        $result->assertInputContains('fallCollection.twig', 'No pants for you!');
        $result->assertInputDoesNotContain('fallCollection.twig', '{% if', 'You don\'t need the `{% if` statement that checks for the products anymore!');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% for product in products %}
    <h3>
        {{ product.name }}

        <span class="price">{{ product.price }}</span>
    </h3>
{% else %}
    <div class="alert alert-warning">
        No pants for you!
    </div>
{% endfor %}
EOF
        );
    }
}
