<?php

namespace Challenges\FunctionsFilters;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\HtmlOutputGradingTool;
use KnpU\Gladiator\Grading\PhpGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

class DumpFindVariableCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
All the AirPupnMeow PHP developers are at lunch. You know that
a new variable is being passed to the template that's set to
the description for the fall collection, but you don't know what
the variable is called! Use the `dump()` function to find out
what the variable is called (and don't worry that the challenge
is graded wrong at first). Then, print this variable inside
the `header` tag.
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
<header>
    <!-- print the unknown variable here -->
</header>
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
        $context->addVariable(
            'fallCollectionPromoDescription',
            'Still wearing your summer swim shorts? Time to plan for the 9 month winter in style. Look no further than our fall collection at Penguins Pants Plus!'
        );
    }

    public function grade(CodingExecutionResult $result)
    {
        $normalGrader = new PhpGradingTool($result);
        $htmlGrader = new HtmlOutputGradingTool($result);

        $normalGrader->assertInputContains('fallCollection.twig', 'fallCollectionPromoDescription', 'I don\'t see you printing the unknown variable yet...');
        $htmlGrader->assertOutputContains('Still wearing your summer swim shorts');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
<header>
    {{ fallCollectionPromoDescription }}
</header>
EOF
        );
    }
}
