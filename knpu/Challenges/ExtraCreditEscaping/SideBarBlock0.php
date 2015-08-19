<?php

namespace Challenges\ExtraCreditEscaping;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class SideBarBlock implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
Most pages override the sidebar block, but not fallCollection.twig!
Unfortunately, even though the sidebar is empty, the <div class="col-xs-3">
still exists, so the content is pushed to the right. Let's fix this:

A) Add some logic so the extra `<div class="col-xs-3">` and its closing tag
are only rendered if the sidebar block has content.

B) Change the `col-xs-9` to `col-xs-12` if the sidebar block has no content,
so it takes up the full-width of the page.
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
