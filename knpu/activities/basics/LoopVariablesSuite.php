<?php

use KnpU\ActivityRunner\Assert\AssertSuite;

use KnpU\ActivityRunner\Result;

class LoopVariablesSuite extends AssertSuite
{
    public function runTest(Result $resultSummary)
    {
        $input = $resultSummary->getInput('product.twig');
        $output = $resultSummary->getOutput();

        // Matches "{% for" without worrying about how spaces.
        $this->assertRegExp('#{%\s*for#', $input,
            'Did you forget to add the "{% for tag in tags %}" tag?'
        );

        $context = require(__DIR__.'/context_loop_variables.php');
        foreach ($context['tags'] as $tag) {
            $this->assertContains($tag, $output,
                'Did you forget to render the tags?'
            );
        }

        // Finally checks that things are in the right spot.
        $crawler = $this->getCrawler($output);
        $tagLis = $crawler->filter('ul.tags li');

        $this->assertCount(3, $tagLis,
            sprintf('I expected to see 3 tag li elements printed out, but I see "%s"', count($tagLis))
        );
    }
}
