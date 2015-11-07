<?php

namespace Challenges\IncludingOtherTemplates;

use KnpU\Gladiator\MultipleChoice\AnswerBuilder;
use KnpU\Gladiator\MultipleChoiceChallengeInterface;

class IncludeAndVariablesMC implements MultipleChoiceChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
Check out the following code:

```twig
{# source for homepage.twig #}

{{ include('_otherTemplate.twig', {'color': 'blue'}) }}
```

```twig
{# source for _otherTemplate.twig #}

{{ include('_thirdTemplate.twig', {
    'color': 'red',
    'number': 5
}) }}
```

```twig
{# source for _thirdTemplate.twig #}

{% set number = 10 %}

<h4>
    {{ color|default('green') }},
    {{ number|default(25) }},
    {{ name|default('Leanna') }},
    {{ food|default('tomato') }}
</h4>
```

Now, suppose that `homepage.twig` is rendered and passed a `food` variable
set to `pizza`. What will be printed inside the `<h4>` tag?
EOF;

    }

    public function configureAnswers(AnswerBuilder $builder)
    {
        $builder->addAnswer('green, 10, Leanna, tomato')
            ->addAnswer('blue, 5, Leanna, pizza')
            ->addAnswer('red, 10, Leanna, tomato')
            ->addAnswer('red, 10, Leanna, pizza', true);
    }

    public function getExplanation()
    {
        return <<<EOF
Look at each variable, one at a time:

* `color`: This is passed from `homepage.twig` to `_otherTemplate.twig` as `blue`.
But then, `_otherTemplate.twig` passes a new value to `_thirdTemplate.twig`: `red`.
The `default` filter doesn't do anything, since this value *is* set.

* `number`: At first, `number` is set to `5` inside `_thirdTemplate.twig`, but
the `{% set` call overrides this and sets it to 10.

* `name`: The `name` variable is *never* set. The `default` filter comes to the
rescue and prints out `Leanna` as a default value.

* `food`: The `food` variable is originally set to `pizza` and never overridden.
Even though it's not explicitly passed in the `include` calls, all variables in
one template are passed into the included template. So, when we're eventually
in `_thirdTemplate.twig`, `food` is still set to `pizza`.
EOF;
    }
}
