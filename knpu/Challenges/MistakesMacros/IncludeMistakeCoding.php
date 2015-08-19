<?php

namespace Challenges\MistakesMacros;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class IncludeMistakeCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
Fix the following code:
EOF;
    }

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();

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

    public function getExecutionMode()
    {
        return self::EXECUTION_MODE_TWIG_NORMAL;
    }

    public function setupContext(CodingContext $context)
    {

    }

    public function grade(CodingExecutionResult $result)
    {
        $result->assertOutputContains('due to: little wings');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('aboutPenguins.twig', <<<EOF
{{ include('_cannotFly.twig', { 'reason': 'little wings' }) }}
EOF
        );
    }
}
