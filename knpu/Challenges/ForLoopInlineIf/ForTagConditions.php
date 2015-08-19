<?php

namespace Challenges\ForLoopInlineIf;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class ForTagConditions implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
Change of plan! Instead of printing "out of stock", management wants to completely
hide any products that aren't available. That's no problem, because you've just read
the documentation about the `for` tag and found out that you can add conditions that filter
things when looping. Use this to only print products with a quantity greater than zero.
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
