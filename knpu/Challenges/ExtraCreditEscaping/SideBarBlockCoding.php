<?php

namespace Challenges\ExtraCreditEscaping;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\HtmlOutputGradingTool;
use KnpU\Gladiator\Grading\PhpGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

class SideBarBlockCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        // hint would be: use the `block()` function

        return <<<EOF
Most pages override the sidebar block, but not `fallCollection.twig`!
Unfortunately, even though the sidebar is empty, the `<div class="col-xs-3">`
element in `layout.twig` still exists, so the content is pushed to the right.
Let's fix this:

A) Add some logic so the extra `<div class="col-xs-3">` (in `layout.twig`) and its
closing tag are only rendered if the sidebar block has content.

B) Change the `col-xs-9` to `col-xs-12` if the sidebar block has no content,
so it takes up the full-width of the page.
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
{% extends 'layout.twig' %}

{% block body %}
    <h1>{{ fallCollectionTitle }}</h1>
{% endblock %}
EOF
        );
        $fileBuilder->setEntryPointFilename('fallCollection.twig');

        $fileBuilder->addFileContents('layout.twig', <<<EOF
<html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-3">
                    {% block sidebar %}{% endblock %}
                </div>
                <div class="col-xs-9">
                    {% block body %}{% endblock %}
                </div>
            </div>
        </div>
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

        $normalGrader->assertInputContains('layout.twig', 'block(', 'Use the `block(\'sidebar\') function to determine if the child template has any sidebar content');
        $htmlGrader->assertOutputDoesNotContain('col-xs-3', 'Make sure the `.col-xs-3` element does not print at all, since there is no sidebar on this page.');
        $htmlGrader->assertOutputContains('col-xs-12', 'Change the main content div\'s class to be `col-xs-9` when there is no sidebar content.');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('layout.twig', <<<EOF
<html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="row">
                {% if block('sidebar') %}
                <div class="col-xs-3">
                    {% block sidebar %}{% endblock %}
                </div>
                {% endif %}

                <div class="{{ block('sidebar') ? 'col-xs-9' : 'col-xs-12' }}">
                    {% block body %}{% endblock %}
                </div>
            </div>
        </div>
    </body>
</html>
EOF
        );
    }
}
