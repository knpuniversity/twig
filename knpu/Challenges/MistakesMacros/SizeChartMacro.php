<?php

namespace Challenges\MistakesMacros;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class SizeChartMacro implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
Penguins are friendly, relaxed creatures. And they hate it when their pants
fit too tight. To help them out, we've included a sizing chart in mainCollection.twig.
We want to re-use that sizing chart on fallCollection.twig, but not show the "XL" column,
because the fall collection only has the sizes S, M and L.

Refactor the sizing chart into a macro called printSizingChart, put it in macros.twig,
and make sure it has a showXLColumn argument that you use to only show XL when we want to.
Use this macro in both collection templates, making sure not to include the XL column for
fallCollection.twig.
EOF;
    }

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
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
        $context->addVariable('collectionTitle', 'Fall in love and look your best in the snow.');
    }

    public function grade(CodingExecutionResult $result)
    {
        $result->assertInputContains('fallCollection.twig', 'collectionTitle');
        $result->assertElementContains('h1', 'Fall in love and look your best in the snow.');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
<h1>
    {{ collectionTitle }}
</h1>
EOF
        );
    }
}
