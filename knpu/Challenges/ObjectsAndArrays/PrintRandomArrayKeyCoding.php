<?php

namespace Challenges\ObjectsAndArrays;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\PhpGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

class PrintRandomArrayKeyCoding implements CodingChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
Penguins are known to be indecisive. To make things easier for them,
we're creating a random product selector. In the template, you have a
new `randomProductKey` variable, that's a number from 0 to 3 - matching
the indices on the `products` array. Use this new variable to print
the "random product" inside the `h2` tag.
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
<h2 class="featured-product">
    <!-- use randomProductKey to randomly select and print a random product -->
</h2>

{% for product in products %}
    <h3>{{ product }}</h3>
{% endfor %}
EOF
        );

        return $fileBuilder;
    }

    public function getWorkerConfig(WorkerLoaderInterface $loader)
    {
        return $loader->load(__DIR__.'/../twig_worker.yml');
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
        $normalGrader = new PhpGradingTool($result);

        $normalGrader->assertInputContains('fallCollection.twig', 'products[randomProductKey]', 'Use the `products[variableName]` syntax to get a variable key from the array.');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
<h2 class="featured-product">
    {{ products[randomProductKey] }}
</h2>

{% for product in products %}
    <h3>{{ product }}</h3>
{% endfor %}
EOF
        );
    }
}
