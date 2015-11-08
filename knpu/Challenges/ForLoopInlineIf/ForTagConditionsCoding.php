<?php

namespace Challenges\ForLoopInlineIf;

use KnpU\Gladiator\CodingChallenge\ChallengeBuilder;
use KnpU\Gladiator\CodingChallenge\Exception\GradingException;
use KnpU\Gladiator\CodingChallenge\CodingContext;
use KnpU\Gladiator\CodingChallenge\CorrectAnswer;
use KnpU\Gladiator\CodingChallengeInterface;
use KnpU\Gladiator\CodingChallenge\CodingExecutionResult;
use KnpU\Gladiator\Grading\HtmlOutputGradingTool;
use KnpU\Gladiator\Grading\PhpGradingTool;
use KnpU\Gladiator\Worker\WorkerLoaderInterface;

class ForTagConditionsCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
Change of plan! Instead of printing "out of stock", management wants to completely
hide any products that aren't available. That's no problem, because you've just read
the documentation about the `for` tag and found out that you can add
[conditions](http://twig.sensiolabs.org/doc/tags/for.html#adding-a-condition)
that filter things when looping. Use this to only print products with a quantity greater
than zero.
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
{% for product in products %}
    <h3>
        {{ product.name }}
    </h3>
{% endfor %}
EOF
        );
        $fileBuilder->setEntryPointFilename('fallCollection.twig');

        $fileBuilder->addFile(
            'PantsProduct.php',
            __DIR__.'/../stubs/PantsProduct.php'
        );

        return $fileBuilder;
    }

    public function getWorkerConfig(WorkerLoaderInterface $loader)
    {
        return $loader->load(__DIR__.'/../twig_worker.yml');
    }

    public function setupContext(CodingContext $context)
    {
        $context->requireFile('PantsProduct.php');
        $context->addVariable('products', array(
            new \PantsProduct('The Black and Tan Trouser', 50, 0),
            new \PantsProduct('Antarctic Snow Pants (in leopard seal print)', 99, 10),
            new \PantsProduct('South Shore Swim Shorts', 49, 0),
            new \PantsProduct('Starfish Halloween Costume', 35, 62)
        ));
    }

    public function grade(CodingExecutionResult $result)
    {
        $normalGrader = new PhpGradingTool($result);
        $htmlGrader = new HtmlOutputGradingTool($result);

        $htmlGrader->assertOutputDoesNotContain('The Black and Tan Trouser');
        $htmlGrader->assertOutputContains('Starfish Halloween Costume');
        $normalGrader->assertInputDoesNotContain('fallCollection.twig', '{% if', 'Hide the products with zero quantity, but without a new `{% if ...` statement: add an "if" part to the `{% for ...` tag.');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% for product in products if product.quantity > 0 %}
    <h3>
        {{ product.name }}
    </h3>
{% endfor %}
EOF
        );
    }
}
