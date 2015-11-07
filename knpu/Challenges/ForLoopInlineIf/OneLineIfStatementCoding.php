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

class OneLineIfStatementCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
The intern added an if statement that either prints each product's
quantity or an "out of stock" message. It works great! But I can tell that
you like a challenge: see if you can write this all in one line, inside a
single `{{ }}` "say something" tag.
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
{% for product in products %}
    <h3>
        {{ product.name }}

        <div class="stock-status">
            {% if product.quantity > 0 %}
                {{ product.quantity }}
            {% else %}
                Out of stock
            {% endif %}
        </div>
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

        $htmlGrader->assertElementContains('.stock-status', 'Out of Stock');
        $htmlGrader->assertElementContains('.stock-status', '62');
        $normalGrader->assertInputDoesNotContain('fallCollection.twig', '{% if', 'Remove the `{% if` statement and replace it with a single line that does the same thing');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% for product in products %}
    <h3>
        {{ product.name }}

        <div class="stock-status">
            {{ product.quantity > 0 ? product.quantity : 'Out of stock' }}
        </div>
    </h3>
{% endfor %}
EOF
        );
    }
}
