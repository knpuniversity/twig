<?php

namespace Challenges\ExtraCreditEscaping;

use KnpU\ActivityRunner\Activity\MultipleChoice\AnswerBuilder;
use KnpU\ActivityRunner\Activity\MultipleChoiceChallengeInterface;

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
        $builder->addAnswer('ANSWER HERE')
            ->addAnswer('{{ ('Hello '~name)|upper }} B) {{- 'Hello'|upper -}} C) {{ name|default('Leanna') }} D) {~ name|upper ~}', true)
            ->addAnswer('ANSWER HERE')
            ->addAnswer('ANSWER HERE');
    }

    public function getExplanation()
    {
        return <<<EOF
EXPLANATION GOES HERE
    }
}
