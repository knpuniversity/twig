<?php

namespace Challenges\IncludingOtherTemplates;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\HtmlOutputGradingTool;
use KnpU\Gladiator\Grading\PhpGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

class UseIncludeCoding implements CodingChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
More duplication!!?? It must have been the intern! Refactor the
"Featured Product" section by moving it into `_featuredProduct.twig` and
including this from both templates.
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
{% extends 'layout.twig' %}

{% block body %}
    <section class="featured-product">
        This week's featured product is a pin-striped full suit, complete
        with cane, monocle and an elegant pocket watch!

        <button class="btn btn-primary">Buy now</button>
    </section>

    <h1>{{ fallCollectionTitle }}</h1>
{% endblock %}
EOF
        );
        $fileBuilder->setEntryPointFilename('fallCollection.twig');

        $fileBuilder->addFileContents('mainCollection.twig', <<<EOF
{% extends 'layout.twig' %}

{% block body %}
    <section class="featured-product">
        This week's featured product is a pin-striped full suit, complete
        with cane, monocle and an elegant pocket watch!

        <button class="btn btn-primary">Buy now</button>
    </section>

    <h1>The Main Collection</h1>
{% endblock %}
EOF
        );

        $fileBuilder->addFileContents('_featuredProduct.twig', <<<EOF
EOF
        );

        $fileBuilder->addFileContents('layout.twig', <<<EOF
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Penguins Pants Plus!</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        {% block body %}{% endblock %}

        <footer>
            You're hip, you're cool, you're a penguin!
        </footer>
    </body>
</html>
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
        $context->addVariable('fallCollectionTitle', 'Fall in love and look your best in the snow.');
    }

    public function grade(CodingExecutionResult $result)
    {
        $normalGrader = new PhpGradingTool($result);
        $htmlGrader = new HtmlOutputGradingTool($result);

        $htmlGrader->assertOutputContains('pin-striped full suit');
        $normalGrader->assertInputContains('fallCollection.twig', 'include');
        $normalGrader->assertInputContains('_featuredProduct.twig', 'pin-striped full suit');
        $normalGrader->assertInputDoesNotContain('fallCollection.twig', 'pin-striped full suit', 'Now that you\'re including `_featuredProduct.twig`, you should remove the "pin-striped full suit" text from `fallCollection.twig`');
        $normalGrader->assertInputContains('mainCollection.twig', 'include');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% extends 'layout.twig' %}

{% block body %}
    {{ include('_featuredProduct.twig') }}

    <h1>{{ fallCollectionTitle }}</h1>
{% endblock %}
EOF
        );

        $correctAnswer->setFileContents('mainCollection.twig', <<<EOF
{% extends 'layout.twig' %}

{% block body %}
    {{ include('_featuredProduct.twig') }}

    <h1>The Main Collection</h1>
{% endblock %}
EOF
        );

        $correctAnswer->setFileContents('_featuredProduct.twig', <<<EOF
<section class="featured-product">
    This week's featured product is a pin-striped full suit, complete
    with cane, monocle and an elegant pocket watch!

    <button class="btn btn-primary">Buy now</button>
</section>
EOF
        );
    }
}
