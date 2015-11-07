<?php

namespace Challenges\TestsIfStatements;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\HtmlOutputGradingTool;
use KnpU\Gladiator\Grading\PhpGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

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

    public function getWorkerConfig(WorkerLoaderInterface $loader)
    {
        return $loader->load(__DIR__.'/../twig_worker.yml');
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
        $normalGrader = new PhpGradingTool($result);
        $htmlGrader = new HtmlOutputGradingTool($result);

        $htmlGrader->assertOutputContains('We promise, 1 more pair of pants is coming very soon!');
        $normalGrader->assertInputContains('fallCollection.twig', 'length', 'Use the `length` filter to count the products');
        $normalGrader->assertInputContains('fallCollection.twig', 'is odd', 'Use the `is odd` to see if there are an odd number of products');
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
