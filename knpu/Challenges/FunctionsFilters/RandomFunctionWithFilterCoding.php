<?php

namespace Challenges\FunctionsFilters;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\HtmlOutputGradingTool;
use KnpU\Gladiator\Grading\PhpGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

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

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

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
                <!-- print black, white or green here randomly -->
                <!-- and make them all capital letters -->
            </td>
        </tr>
    {% endfor %}
</table>
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
        $context->addVariable('products', array(
            'The Black and Tan Trouser',
            'Antarctic Snow Pants (in leopard seal print)',
            'South Shore Swim Shorts',
            'Starfish Halloween Costume'
        ));
    }

    public function grade(CodingExecutionResult $result)
    {
        $normalGrader = new PhpGradingTool($result);
        $htmlGrader = new HtmlOutputGradingTool($result);

        $normalGrader->assertInputContains('fallCollection.twig', 'random(', 'Are you using the random() function?');
        $normalGrader->assertInputContains('fallCollection.twig', 'upper', 'Don\'t forget to use the `upper` filter to uppercase the colors!');
        $normalGrader->assertInputContains('fallCollection.twig', 'black', 'Use `black` (lowercase) as one of the random colors', true);
        $normalGrader->assertInputContains('fallCollection.twig', 'white', 'Use `black` (lowercase) as one of the random colors', true);
        $normalGrader->assertInputContains('fallCollection.twig', 'green', 'Use `black` (lowercase) as one of the random colors', true);

        if (!$htmlGrader->doesOutputContain('BLACK', true)
            && !$htmlGrader->doesOutputContain('WHITE', true)
            && !$htmlGrader->doesOutputContain('GREEN', true)
        ) {
            throw new GradingException('The output does not contain any of the colors BLACK, GREEN or WHITE - are you randomly selecting one of these and uppercasing them?');
        }
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
                {{ random(['black', 'white', 'green'])|upper }}
            </td>
        </tr>
    {% endfor %}
</table>
EOF
        );
    }
}
