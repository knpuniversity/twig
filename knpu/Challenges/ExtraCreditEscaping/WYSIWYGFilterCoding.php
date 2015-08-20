<?php

namespace Challenges\ExtraCreditEscaping;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

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

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();

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

    public function getExecutionMode()
    {
        return self::EXECUTION_MODE_TWIG_NORMAL;
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
        $result->assertOutputContains('<strong>', 'The `<strong>` tag still looks escaped - how can you make it raw?');
        $result->assertInputContains('singleItem.twig', 'raw', 'Use the `raw` filter to *not* escape HTML tags');
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
