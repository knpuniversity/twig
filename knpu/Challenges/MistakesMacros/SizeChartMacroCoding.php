<?php

namespace Challenges\MistakesMacros;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

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
`fallCollection`.twig.
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
        $result->assertInputContains('macros.twig', 'printSizingChart(');
        $result->assertInputContains('macros.twig', 'showXLColumn');
        $result->assertOutputContains('5-15 lbs');
        $result->assertOutputDoesNotContain('61-85 lbs');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% extends 'layout.twig' %}
{% use 'macros.twig' as myMacros %}

{% block body %}
    {{ myMacros.printSizingChart(false) }}

    <h1>{{ fallCollectionTitle }}</h1>
{% endblock %}
EOF
        );

        $correctAnswer->setFileContents('mainCollection.twig', <<<EOF
{% extends 'layout.twig' %}
{% use 'macros.twig' as myMacros %}

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
