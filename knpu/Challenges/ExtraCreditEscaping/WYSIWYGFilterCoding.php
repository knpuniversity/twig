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

class WYSIWYGFilterCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
In our admin section, the content penguins are using a WYSIWYG editor to edit the
pant descriptions.  But immediately, ugly HTML tags started printing out, instead
of being rendered! Add a filter to save the day.
EOF;
    }

    public function getChallengeBuilder()
    {
        $fileBuilder = new ChallengeBuilder();

        $fileBuilder->addFileContents('singleItem.twig', <<<EOF
<h1>{{ product.name }}</h1>

<div>
    {{ product.description }}
</div>
EOF
        );

        $fileBuilder->setEntryPointFilename('singleItem.twig');

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
        $product = new \PantsProduct('The Black and Tan Trouser', 50);
        $product->setDescription('The <strong>Hottest</strong> pants this fall!');
        $context->addVariable('product', $product);
    }

    public function grade(CodingExecutionResult $result)
    {
        $normalGrader = new PhpGradingTool($result);
        $htmlGrader = new HtmlOutputGradingTool($result);

        $htmlGrader->assertOutputContains('<strong>', 'The `<strong>` tag still looks escaped - how can you make it raw?');
        $normalGrader->assertInputContains('singleItem.twig', 'raw', 'Use the `raw` filter to *not* escape HTML tags');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('singleItem.twig', <<<EOF
<h1>{{ product.name }}</h1>

<div>
    {{ product.description|raw }}
</div>
EOF
        );
    }
}
