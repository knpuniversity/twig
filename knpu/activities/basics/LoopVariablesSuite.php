<?php

use KnpU\ActivityRunner\Assert\AssertSuite;

class LoopVariablesSuite extends AssertSuite
{
    public function testHasForTagInTags()
    {
        $inputFiles = $this->getActivity()->getInputFiles();
        $input = $inputFiles['product.twig'];

        // Matches "{% for" without worrying about how spaces.
        $this->assertRegExp('#{%\s*for#', $input,
            'Did you forget to add the "{% for tag in tags %}" tag?'
        );

        $context = $this->getActivity()->getContext();
        $output = $this->getOutput();

        foreach ($context['tags'] as $tag) {
            $this->assertContains($tag, $output,
                'Did you forget to render the tags?'
            );
        }

        // Finally checks that things are in the right spot.
        $crawler = $this->getCrawler();
        $tagLis = $crawler->filter('ul.tags li');

        $this->assertCount(3, $tagLis,
            sprintf('I expected to see 3 tag li elements printed out, but I see "%s"', count($tagLis))
        );
    }
}
