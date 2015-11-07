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

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
<h3>
    The sale starts: <!-- print the date here -->
</h3>
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
        $context->addVariable('saleStartsAt', $this->getSaleStartsAt());
    }

    public function grade(CodingExecutionResult $result)
    {
        $normalGrader = new PhpGradingTool($result);
        $htmlGrader = new HtmlOutputGradingTool($result);

        $normalGrader->assertInputContains('fallCollection.twig', 'saleStartsAt');
        $normalGrader->assertInputContains('fallCollection.twig', 'F jS', 'Make sure you use the `F jS` (e.g. January 5th) format for the date');
        $htmlGrader->assertElementContains('h3', $this->getSaleStartsAt()->format('F jS'));
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
