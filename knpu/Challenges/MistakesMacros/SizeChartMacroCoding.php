<?php

namespace Challenges\MistakesMacros;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\HtmlOutputGradingTool;
use KnpU\Gladiator\Grading\PhpGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

class SizeChartMacroCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
Penguins are friendly, relaxed creatures. And they hate it when their pants
fit too tight. To help them out, we've included a sizing chart in `mainCollection.twig`.
We want to re-use that sizing chart on `fallCollection.twig`, but not show the "XL" column,
because the fall collection only has the sizes S, M and L.

Refactor the sizing chart into a macro called `printSizingChart`, put it in `macros.twig`,
and make sure it has a `showXLColumn` argument that you use to only show XL when we want to.
Use this macro in both collection templates, making sure not to include the XL column for
`fallCollection.twig`.
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

        $fileBuilder->addFileContents('mainCollection.twig', <<<EOF
{% extends 'layout.twig' %}

{% block body %}
    <table class="sizing-chart">
        <tr>
            <th>S</th>
            <th>M</th>
            <th>L</th>
            <th>XL</th>
        </tr>
        <tr>
            <td>5-15 lbs</td>
            <td>16-35 lbs</td>
            <td>36-60 lbs</td>
            <td>61-85 lbs</td>
        </tr>
    </table>

    <h1>The Main Collection</h1>
{% endblock %}
EOF
        );

        $fileBuilder->addFileContents('macros.twig', <<<EOF
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
        <header>Penguins Pants Plus!</header>

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
        $normalGrader = new PhpGradingTool($result);
        $htmlGrader = new HtmlOutputGradingTool($result);

        $normalGrader->assertInputContains('macros.twig', 'printSizingChart(', 'I don\'t see the `printSizingChart` macro in `macros.twig`');
        $normalGrader->assertInputContains('macros.twig', 'showXLColumn', 'I don\'t see the `showXLColumn` argument for the `printSizingChart` macro. Add this and use it to hide/show the XL column.');
        $normalGrader->assertInputContains('fallCollection.twig', 'printSizingChart', 'You\'ll need to use the `printSizingChart` macro inside `fallCollection.twig');
        $normalGrader->assertInputContains('mainCollection.twig', 'printSizingChart', 'You\'ll need to use the `printSizingChart` macro inside `mainCollection.twig');
        $normalGrader->assertInputContains('mainCollection.twig', 'import', 'Don\'t forget to `import` `macros.twig` in `mainCollection.twig`.');
        $htmlGrader->assertOutputContains('5-15 lbs');
        $htmlGrader->assertOutputDoesNotContain('61-85 lbs');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% extends 'layout.twig' %}
{% import 'macros.twig' as myMacros %}

{% block body %}
    {{ myMacros.printSizingChart(false) }}

    <h1>{{ fallCollectionTitle }}</h1>
{% endblock %}
EOF
        );

        $correctAnswer->setFileContents('mainCollection.twig', <<<EOF
{% extends 'layout.twig' %}
{% import 'macros.twig' as myMacros %}

{% block body %}
    {{ myMacros.printSizingChart(true) }}

    <h1>The Main Collection</h1>
{% endblock %}
EOF
        );

        $correctAnswer->setFileContents('macros.twig', <<<EOF
{% macro printSizingChart(showXLColumn) %}
    <table class="sizing-chart">
        <tr>
            <th>S</th>
            <th>M</th>
            <th>L</th>
            {% if showXLColumn %}
                <th>XL</th>
            {% endif %}
        </tr>
        <tr>
            <td>5-15 lbs</td>
            <td>16-35 lbs</td>
            <td>36-60 lbs</td>
            {% if showXLColumn %}
                <td>61-85 lbs</td>
            {% endif %}
        </tr>
    </table>
{% endmacro %}
EOF
        );
    }
}
