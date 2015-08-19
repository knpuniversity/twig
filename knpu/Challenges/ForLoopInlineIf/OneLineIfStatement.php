<?php

namespace Challenges\ForLoopInlineIf;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class OneLineIfStatement implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
The intern added an if statement that either prints each product's
quantity or an "out of stock" message. It works great! But I can tell that
you like a challenge, see if you can write this all in one line, inside a
single {{ }} say something tag.

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
