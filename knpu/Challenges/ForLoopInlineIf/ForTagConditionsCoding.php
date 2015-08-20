<?php

namespace Challenges\ForLoopInlineIf;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

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

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();

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

    public function getExecutionMode()
    {
        return self::EXECUTION_MODE_TWIG_NORMAL;
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
        $result->assertOutputDoesNotContain('The Black and Tan Trouser');
        $result->assertOutputContains('Starfish Halloween Costume');
        $result->assertInputDoesNotContain('fallCollection.twig', '{% if', 'Hide the products with zero quantity, but without a new `{% if ...` statement: add an "if" part to the `{% for ...` tag.');
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
