<?php

namespace Challenges\Basics;

use KnpU\Gladiator\MultipleChoice\AnswerBuilder;
use KnpU\Gladiator\MultipleChoiceChallengeInterface;

class IncorrectOpenTagsMC implements MultipleChoiceChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
Which of the following is **incorrect** Twig code:
EOF;

    }

    public function configureAnswers(AnswerBuilder $builder)
    {
        $builder->addAnswer('```twig
{{
pageTitle     }}
```')
            ->addAnswer('```twig
{# for nonExistentVariable in bad syntax here #}
```')
            ->addAnswer('```twig
{{ for product in my_products }}
```', true)
            ->addAnswer('```twig
{% set penguins = \'dapper\' %}
```');
    }

    public function getExplanation()
    {
        return <<<EOF
* A) is correct because whitespace - even line breaks - inside Twig code are ok
(and actually, have no effect!)

* B) is correct because everything between `{#` and `#}` are comments. So, even
though this *would* be bad Twig code, it's totally ignored.

* C) is *not* correct, because the `for` tag must be used with the say something
tag: `{% for product in my_products %}`.

* D) is correct because `{% set ... ` is one of the valid "tags" that can be used
with the "say something" tag. We haven't used it yet, but it's totally valid.
EOF;
    }
}
