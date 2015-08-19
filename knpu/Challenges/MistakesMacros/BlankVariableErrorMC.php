<?php

namespace Challenges\MistakesMacros;

use KnpU\ActivityRunner\Activity\MultipleChoice\AnswerBuilder;
use KnpU\ActivityRunner\Activity\MultipleChoiceChallengeInterface;

class BlankVariableErrorMC implements MultipleChoiceChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
If you see the following error, what's the likely cause?:

'Item "name" for "" does not exist in homepage.twig'
EOF;

    }

    public function configureAnswers(AnswerBuilder $builder)
    {
        $builder->addAnswer(ANSWER HERE)
            ->addAnswer(ANSWER HERE)
            ->addAnswer(ANSWER HERE)
            ->addAnswer(ANSWER HERE);
    }

    public function getExplanation()
    {
        return <<<EOF
EXPLANATION GOES HERE
    }
}
