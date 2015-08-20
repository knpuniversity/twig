<?php

namespace Challenges\ObjectsAndArrays;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class PrintProductObjectsCoding implements CodingChallengeInterface
{
    public function getQuestion()
    {
        return <<<EOF
The backend developers are getting really fancy and have changed the
products from simple strings to objects! Use the `dump()` function - or look
at the `PantsProduct` class - to check it out. 

Fix the template and print out both the `name` and `price` of the product. 
To be extra awesome, see if you can also print out the date each product will 
be released.
EOF;
    }

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();

        $fileBuilder->addFileContents('fallCollection.twig', <<<EOF
{% for product in products %}
    <h3>
        <!-- fix this to print the product's name -->
        {{ product }}

        <span class="price">
            <!-- print price here -->
        </span>

        <span class="released-at">
            <!-- extra credit! -->
        </span>
    </h3>
{% endfor %}
EOF
        );
        $fileBuilder->setEntryPointFilename('fallCollection.twig');

        // read this over here, get a list of files to send over
        // save the "extra" files on the other side, in the directory
        // -> remap any /../ to avoid leaving the directory

        // set the /../ relative path over here
        // resolve these to real files, by looping over and opening each one
        // send the "extra" files over there as a map: '/../stubs/PantsProduct.php' => 'PantsProduct.php'
        // save the "extra" files on the other side, in the directory, with the "local" filename
        // resolve each in the builder to real files, by looping over and setting the real path
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
            new \PantsProduct('The Black and Tan Trouser', 50),
            new \PantsProduct('Antarctic Snow Pants (in leopard seal print)', 99),
            new \PantsProduct('South Shore Swim Shorts', 49),
            new \PantsProduct('Starfish Halloween Costume', 35)
        ));
    }

    public function grade(CodingExecutionResult $result)
    {
        $result->assertOutputContains('The Black and Tan Trouser', 'Are you printing the product names?');
        $result->assertOutputContains(99, 'Are you printing the product prices?');
        $result->assertInputContains('fallCollection.twig', 'product.name', 'You can just use `product.name` to print the name. Behind the scenes. Twig calls the `getName()` function on `PantsProduct`.');
        $result->assertInputContains('fallCollection.twig', 'product.price', 'You can just use `product.price` to print the price. Behind the scenes. Twig calls the `getPrice()` function on `PantsProduct`.');
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('fallCollection.twig', <<<EOF
{% for product in products %}
    <h3>
        {{ product.name }}

        <span class="price">
            {{ product.price }}
        </span>

        <span class="released-at">
            <!-- This calls the getFutureReleaseDate() function on PantsProduct -->
            <!-- even though there isn't actually a futureReleaseDate property -->
            {{ product.futureReleaseDate|date('Y-m-d') }}
        </span>
    </h3>
{% endfor %}
EOF
        );
    }
}
