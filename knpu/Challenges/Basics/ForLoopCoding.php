<?php

namespace Challenges\Basics;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\HtmlOutputGradingTool;
use KnpU\Gladiator\Grading\PhpGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

class ForLoopCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
The fall collection is done! Dapper penguins are practically pounding
their flippers on our door to get a sneak peek of this season's latest
styles (this year, it's lots of black and white). Loop over the `products`
variable and print each inside an `h3` tag.
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF

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

        $normalGrader->assertInputContains('fallCollection.twig', '{%', 'Make sure to use the "do" something tag `{%` with the `for` tag');
        $normalGrader->assertInputContains('fallCollection.twig', 'for', 'Use the `for` tag to loop');
        $htmlGrader->assertElementContains('h3', 'The Black and Tan Trouser');
        $htmlGrader->assertElementContains('h3', 'Antarctic Snow Pants (in leopard seal print)');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% for product in products %}
    <h3>{{ product }}</h3>
{% endfor %}
EOF
        );
    }
}
