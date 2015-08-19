<?php

namespace Challenges\ForLoopInlineIf;

use KnpU\ActivityRunner\Activity\MultipleChoice\AnswerBuilder;
use KnpU\ActivityRunner\Activity\MultipleChoiceChallengeInterface;

class ValidForLoopVariableMC implements MultipleChoiceChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
Which of the following are valid things you can do with the loop variable?
EOF;

    }

    public function configureAnswers(AnswerBuilder $builder)
    {
        $builder->addAnswer('ANSWER HERE')
            ->addAnswer('ANSWER HERE')
            ->addAnswer('ANSWER HERE')
            ->addAnswer('ANSWER HERE');
    }

    public function getExplanation()
    {
        return <<<EOF
EXPLANATION GOES HERE
    }
}
