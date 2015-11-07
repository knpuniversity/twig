<?php

namespace Challenges\TestsIfStatements;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\PhpGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

class ComplexTestAndNotCoding implements CodingChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
When we show the featured product, sometimes we pass a `quantityRemaining` variable
to `_featuredProduct.twig`, but sometimes we don't. To make matters worse, remember that
Penguins hate odd numbers! Add an `if` statement so that the `featured-quantity`
element is printed *only* if `quantityRemaining`is defined **and** if it is *not*
an odd number.

**Hint**: No `quantityRemaining` variable is being passed to `fallCollection.twig`.
But you can test your logic by passing a `quantityRemaining` variable in your
`include()` function with different values.
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
{{ include('_featuredProduct.twig') }}
EOF
        );
        $fileBuilder->addFileContents('_featuredProduct.twig', <<<EOF
    <section class="featured-product">
        This week's featured product is a pin-striped full suit, complete
        with cane, monocle and an elegant pocket watch!

        <div class="featured-quantity">
            {{ quantityRemaining }}
        </div>

        <button class="btn btn-primary">Buy now</button>
    </section>
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
    }

    public function grade(CodingExecutionResult $result)
    {
        $normalGrader = new PhpGradingTool($result);

        $normalGrader->assertInputContains('_featuredProduct.twig', 'defined', 'Use the `defined` filter to see if `quantityRemaining` is defined');
        $normalGrader->assertInputContains('_featuredProduct.twig', 'is not odd', 'Use `is not odd` to make sure `quantityRemaining` is not an odd number. (Hint: you could also use `is even` in real life... but not to pass this challenge)');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('_featuredProduct.twig', <<<EOF
<section class="featured-product">
    This week's featured product is a pin-striped full suit, complete
    with cane, monocle and an elegant pocket watch!

    {% if quantityRemaining is defined and quantityRemaining is not odd %}
    <div class="featured-quantity">
        {{ quantityRemaining }}
    </div>
    {% endif %}

    <button class="btn btn-primary">Buy now</button>
</section>
EOF
        );
    }
}
