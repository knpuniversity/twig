<?php

namespace Challenges\ForLoopInlineIf;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\HtmlOutputGradingTool;
use KnpU\Gladiator\Grading\PhpGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

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

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

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

    public function getWorkerConfig(WorkerLoaderInterface $loader)
    {
        return $loader->load(__DIR__.'/../twig_worker.yml');
    }

    public function setupContext(CodingContext $context)
    {
        $context->addVariable('products', array());
    }

    public function grade(CodingExecutionResult $result)
    {
        $normalGrader = new PhpGradingTool($result);
        $htmlGrader = new HtmlOutputGradingTool($result);

        $normalGrader->assertInputContains('fallCollection.twig', 'else', 'Use an `else` with your `for` tag.');
        $normalGrader->assertInputContains('fallCollection.twig', 'No pants for you!');
        $normalGrader->assertInputDoesNotContain('fallCollection.twig', '{% if', 'You don\'t need the `{% if` statement that checks for the products anymore!');
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
