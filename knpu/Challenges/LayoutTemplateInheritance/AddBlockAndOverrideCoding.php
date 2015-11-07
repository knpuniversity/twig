<?php

namespace Challenges\LayoutTemplateInheritance;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\HtmlOutputGradingTool;
use KnpU\Gladiator\Grading\PhpGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

class AddBlockAndOverrideCoding implements CodingChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
Every page on the site has our tag line at the bottom:
`You're hip, you're cool, you're a penguin!`. But on the fall collection page,
you need to override that to say: `Winter is coming! Get your pants!`. 

Update `layout.twig` so that we can override this phrase. Then actually override it in
`fallCollection.twig`. Just make sure that `You're hip, you're cool, you're 
a penguin!` is still the default tag line, for all the other templates that 
*don't* override it.
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
{% extends 'layout.twig' %}

{#
    make some change so that the footer says:
    Winter is coming! Get your pants!
#}

{% block body %}
    <h1>{{ fallCollectionTitle }}</h1>
{% endblock %}
EOF
        );
        $fileBuilder->setEntryPointFilename('fallCollection.twig');

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

        $htmlGrader->assertOutputContains('Winter is coming! Get your pants!');
        $htmlGrader->assertElementContains('footer', 'Winter is coming! Get your pants!', 'It looks like the `<footer>` HTML tag is gone. Print the text inside of this tag');
        $normalGrader->assertInputDoesNotContain('fallCollection.twig', '<footer>', 'You don\'t need to have the `<footer>` tag inside of `fallCollection.twig`. Instead, only put this `layout.twig`, and make your block only override the contents *inside* of it');

        $normalGrader->assertInputContains('layout.twig', 'You\'re hip, you\'re cool, you\'re a penguin!', 'Oh no! The original caption - `You\'re hip, you\'re cool, you\'re a penguin!` will now be missing from every other template that does *not* override the block. Make this be the default footer content.');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% extends 'layout.twig' %}

{% block footer_caption %}
    Winter is coming! Get your pants!
{% endblock %}

{% block body %}
    <h1>{{ fallCollectionTitle }}</h1>
{% endblock %}
EOF
        );

        $correctAnswer->setFileContents('layout.twig', <<<EOF
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Penguins Pants Plus!</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        {% block body %}{% endblock %}

        <footer>
            {% block footer_caption %}
                You're hip, you're cool, you're a penguin!
            {% endblock %}
        </footer>
    </body>
</html>
EOF
        );
    }
}
