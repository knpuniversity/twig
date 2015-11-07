<?php

namespace Challenges\LayoutTemplateInheritance;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\HtmlOutputGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

class FixExtendsErrorCoding implements CodingChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
Oh no! The intern did their best, but left this template with a big error!
They're back partying at university, so you need to find the mistake and
get this template working again.
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
{% extends 'layout.twig' %}

<h1>
    {{ fallCollectionTitle }}
</h1>

<div>
    The fall products are coming soon!
</div>
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
        <header>
            <div class="logo">Penguins Pants Plus!</div>
        </header>

        {% block body %}{% endblock %}

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
        $htmlGrader = new HtmlOutputGradingTool($result);

        $htmlGrader->assertOutputContains('The fall products are coming soon!');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% extends 'layout.twig' %}

{% block body %}

    <h1>
        {{ fallCollectionTitle }}
    </h1>

    <div>
        The fall products are coming soon!
    </div>

{% endblock %}
EOF
        );
    }
}
