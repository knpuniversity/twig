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

class CreateLayoutRemoveDuplicationCoding implements CodingChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
The team hates duplication! But now we have two templates: one for the
main collection, and another for the fall collection: the whole HTML
layout is in both! Move the duplicated layout HTML into the new `layout.twig`
file. Remove the duplication from both `mainCollection.twig`
and `fallCollection.twig` and use template inheritance to keep things looking
fly.
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
<html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <header>Penguins Pants Plus!</header>

        <h1>{{ fallCollectionTitle }}</h1>

        <div>The fall products are coming soon!</div>
    </body>
</html>
EOF
        );
        $fileBuilder->setEntryPointFilename('fallCollection.twig');

        $fileBuilder->addFileContents('mainCollection.twig', <<<EOF
<html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <header>Penguins Pants Plus!</header>

        <h1>Welcome to our main collection or products</h1>

        <div>The main products are being updated!</div>
    </body>
</html>
EOF
        );

        $fileBuilder->addFileContents('layout.twig', '');

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

        $normalGrader->assertInputContains('fallCollection.twig', 'extends', '`fallCollection.twig` needs to "extend" `layout.twig`');
        $normalGrader->assertInputContains('fallCollection.twig', 'layout.twig', '`fallCollection.twig` needs to "extend" `layout.twig`');
        $normalGrader->assertInputContains('mainCollection.twig', 'extends', '`mainCollection.twig` needs to "extend" `layout.twig`');
        $normalGrader->assertInputContains('mainCollection.twig', 'layout.twig', '`mainCollection.twig` needs to "extend" `layout.twig`');

        $normalGrader->assertInputContains('layout.twig', '<html', 'Put the entire HTML layout into `layout.twig`');
        $normalGrader->assertInputContains('layout.twig', '</html>', 'Put the entire HTML layout into `layout.twig`');
        $normalGrader->assertInputContains('layout.twig', '<header>', 'The `<header>` belongs in `layout.twig` too, since it\'s repeated on both pages');

        $normalGrader->assertInputDoesNotContain('fallCollection.twig', '<html', 'You no longer need the HTML layout (e.g. the `<html>` tag) inside of `fallCollection.twig`');
        $htmlGrader->assertOutputContains('The fall products are coming soon!');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% extends 'layout.twig' %}

{% block body %}

    <h1>{{ fallCollectionTitle }}</h1>

    <div>The fall products are coming soon!</div>

{% endblock %}
EOF
        );

        $correctAnswer->setFileContents('mainCollection.twig', <<<EOF
{% extends 'layout.twig' %}

{% block body %}

    <h1>Welcome to our main collection or products</h1>

    <div>The main products are being updated!</div>

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
        <header>Penguins Pants Plus!</header>

        {% block body %}{% endblock %}
    </body>
</html>
EOF
        );
    }
}
