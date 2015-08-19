<?php

namespace Challenges\FunctionsFilters;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class RandomFunctionWithFilterCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
We don't really know what color we'll have for each item yet. But to
create a realistic demo, use the `random()` function to randomly
print either `black`, `white` or `green` for the color of each
product. Then, make sure this prints in uppercase, to really make
this page SHOUT.
EOF;
    }

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Color</th>
        </tr>
    </thead>
    {% for product in products %}
        <tr>
            <td>{{ product }}</td>
            <td>
                <!-- print black, white or green here randomly and uppercase them -->
            </td>
        </tr>
    {% endfor %}
</table>
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
        $context->addVariable('products', array(
            'The Black and Tan Trouser',
            'Antarctic Snow Pants (in leopard seal print)',
            'South Shore Swim Shorts',
            'Starfish Halloween Costume'
        ));
    }

    public function grade(CodingExecutionResult $result)
    {
        $result->assertInputContains('fallCollection.twig', 'random(', 'Are you using the random() function?');
        $result->assertInputContains('fallCollection.twig', 'upper', 'Don\'t forget to use the `upper` filter to uppercase the colors!');
        $result->assertInputContains('fallCollection.twig', 'black', 'Use `black` (lowercase) as one of the random colors', true);
        $result->assertInputContains('fallCollection.twig', 'white', 'Use `black` (lowercase) as one of the random colors', true);
        $result->assertInputContains('fallCollection.twig', 'green', 'Use `black` (lowercase) as one of the random colors', true);
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Color</th>
        </tr>
    </thead>
    {% for product in products %}
        <tr>
            <td>{{ product }}</td>
            <td>
                {{ random({'black', 'white', 'green'})|upper }}
            </td>
        </tr>
    {% endfor %}
</table>
EOF
        );
    }
}
