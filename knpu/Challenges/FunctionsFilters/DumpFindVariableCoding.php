<?php

namespace Challenges\FunctionsFilters;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

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
the `h3` tag.
EOF;
    }

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
<header>
    <!-- print the unknown variable here -->
</header>
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
        $context->addVariable(
            'fallCollectionPromoDescription',
            'Still wearing your summer swim shorts? Time to plan for the 9 month winter in style. Look no further than our fall collection at Penguins Pants Plus!'
        );
    }

    public function grade(CodingExecutionResult $result)
    {
        $result->assertInputContains('fallCollection.twig', 'fallCollectionPromoDescription');
        $result->assertOutputContains('Still wearing your summer swim shorts');
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
