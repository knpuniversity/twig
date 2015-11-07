<?php

namespace Challenges\ExtraCreditEscaping;

use KnpU\Gladiator\MultipleChoice\AnswerBuilder;
use KnpU\Gladiator\MultipleChoiceChallengeInterface;

class BadTwigCodeMC implements MultipleChoiceChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
Which of the following is bad Twig code:
EOF;

    }

    public function configureAnswers(AnswerBuilder $builder)
    {
        $builder->addAnswer("`{{ ('Hello '~name)|upper }}`")
            ->addAnswer("`{{- 'Hello'|upper -}}`")
            ->addAnswer("`{~ name|upper ~}`", true);
    }

    public function getExplanation()
    {
        return <<<EOF
* Answer (A) uses the `~` to concatenate a string and a variable, totally legal!

* Answer (B) adds a `-` on the opening and closing `{{` in order to remove any
extra whitespace before or after this tag.

But (C) is totally made up - `{~` is not valid syntax.
EOF;
    }
}
