<?php

namespace Challenges\FunctionsFilters;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class DateFilterCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
The penguins will want to get their flippers on these pants as 
*soon* as possible. So, we're passing in a `saleStartsAt` date 
variable that's set to *when* these fresh pants are available. Print 
this using the `date` **filter** and the `F jS` format (e.g. January 5th) 
inside the `h3` tag.
EOF;
    }

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
<h3>
    The sale starts: <!-- print the date here -->
</h3>
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
        $context->addVariable('saleStartsAt', $this->getSaleStartsAt());
    }

    public function grade(CodingExecutionResult $result)
    {
        $result->assertInputContains('fallCollection.twig', 'saleStartsAt');
        $result->assertInputContains('fallCollection.twig', 'F jS', 'Make sure you use the `F jS` (e.g. January 5th) format for the date');
        $result->assertElementContains('h3', $this->getSaleStartsAt()->format('F jS'));
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
<h3>
    The sale starts: {{ saleStartsAt|date('F jS') }}
</h3>
EOF
        );
    }

    private function getSaleStartsAt()
    {
        return new \DateTime('+1 week');
    }
}
