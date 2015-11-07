<?php

namespace Challenges\MistakesMacros;

use KnpU\Gladiator\MultipleChoice\AnswerBuilder;
use KnpU\Gladiator\MultipleChoiceChallengeInterface;

class BlankVariableErrorMC implements MultipleChoiceChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
Check out the following code:

```twig
{# homepage.twig #}

<h1>{{ product.name }}</h1>
```

If you see the following error, what's the likely cause?:

    Item "name" for "" does not exist in homepage.twig
EOF;

    }

    public function configureAnswers(AnswerBuilder $builder)
    {
        $builder->addAnswer('The `product` variable might be null, instead of an object', true)
            ->addAnswer('There might not be a `name` property on the product object')
            ->addAnswer('There might not be a `getName()` function on the product object')
            ->addAnswer('The `product` variable might be an array, but we\'re treating it like an object');
    }

    public function getExplanation()
    {
        return <<<EOF
This is a classic error that means that `product` is empty - like `null`, or an empty
string - instead of it being an object or an array (which is what we were expecting).
If `B` or `C` were true, you would see a different error. And `D` is completely wrong:
you can say `product.name` whether `product` is an object or an associative array
that has a `name` key.
EOF;
    }
}
