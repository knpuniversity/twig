<?php

namespace Challenges\ForLoopInlineIf;

use KnpU\Gladiator\MultipleChoice\AnswerBuilder;
use KnpU\Gladiator\MultipleChoiceChallengeInterface;

class ValidForLoopVariableMC implements MultipleChoiceChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
Which of the following is **not** a valid thing you can do with the `loop` variable?
EOF;

    }

    public function configureAnswers(AnswerBuilder $builder)
    {
        $builder->addAnswer('`{% if loop.first %}`')
            ->addAnswer('`{{ loop.previous }}`', true)
            ->addAnswer('`{{ loop.index0 }}`')
            ->addAnswer('`{% if loop.length > 5 %}`');
    }

    public function getExplanation()
    {
        return <<<EOF
Twig's documentation for the [for](http://twig.sensiolabs.org/doc/tags/for.html#the-loop-variable)
tag shows that all of these are valid, except for `loop.previous` - we made that
one up ;).
EOF;

    }
}
