<?php

namespace Challenges\MistakesMacros;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\HtmlOutputGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

class IncludeMistakeCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
You've been coding like a machine until someone brought birthday cake <i class="fa fa-birthday-cake"></i>
to the office and you quit working immediately! Now, in a slight sugar-coma,
you're ready to keep working. Start by fixing the error in this template:
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('aboutPenguins.twig', <<<EOF
{{ include('_cannotFly.twig', reason: 'little wings') }}
EOF
        );
        $fileBuilder->setEntryPointFilename('aboutPenguins.twig');

        $fileBuilder->addFileContents('_cannotFly.twig', <<<EOF
<div>
    Penguins cannot fly, due to: {{ reason }}
</div>
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

    }

    public function grade(CodingExecutionResult $result)
    {
        $htmlGrader = new HtmlOutputGradingTool($result);

        $htmlGrader->assertOutputContains('due to: little wings');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('aboutPenguins.twig', <<<EOF
{{ include('_cannotFly.twig', { 'reason': 'little wings' }) }}
EOF
        );
    }
}
