<?php

namespace Challenges\ExtraCreditEscaping;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class SideBarBlockCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
Most pages override the sidebar block, but not `fallCollection.twig`!
Unfortunately, even though the sidebar is empty, the `<div class="col-xs-3">`
still exists, so the content is pushed to the right. Let's fix this:

A) Add some logic so the extra `<div class="col-xs-3">` and its closing tag
are only rendered if the sidebar block has content.

B) Change the `col-xs-9` to `col-xs-12` if the sidebar block has no content,
so it takes up the full-width of the page.
EOF;
    }

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
{% extends 'layout.twig' %}

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
        <div class="row">
            <div class="col-xs-9">
                {% block body %}{% endblock %}
            </div>
            <div class="col-xs-3">
                {% block sidebar %}{% endblock %}
            </div>
        </div>
    </body>
</html>
EOF
        );

        return $fileBuilder;
    }

    public function getExecutionMode()
    {
        return self::EXECUTION_MODE_TWIG_NORMAL;
    }

    public function setupContext(CodingContext $context)
    {
        $context->addVariable('fallCollectionTitle', 'Fall in love and look your best in the snow.');
    }

    public function grade(CodingExecutionResult $result)
    {
        $result->assertInputContains('layout.twig', 'block(', 'Use the `block(\'sidebar\') function to determine if the child template has any sidebar content');
        $result->assertOutputDoesNotContain('col-xs-3', 'Make sure the `.col-xs-3` element does not print at all, since there is no sidebar on this page.');
        $result->assertOutputContains('col-xs-12', 'Change the main content div\'s class to be `col-xs-9` when there is no sidebar content.');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('layout.twig', <<<EOF
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Penguins Pants Plus!</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="row">
            <div class="{{ block('sidebar') ? 'col-xs-9' : 'col-xs-12' }}">
                {% block body %}{% endblock %}
            </div>
            {% if block('sidebar') %}
            <div class="col-xs-3">
                {% block sidebar %}{% endblock %}
            </div>
            {% endif %}
        </div>
    </body>
</html>
EOF
        );
    }
}
