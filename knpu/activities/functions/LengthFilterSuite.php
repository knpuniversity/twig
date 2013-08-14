<?php

use KnpU\ActivityRunner\Assert\AssertSuite;

class LengthFilterSuite extends AssertSuite
{
    public function testDataTagCountSetProperly()
    {
        $output = $this->getOutput();
        $crawler = $this->getCrawler();
        $context = $this->getActivity()->getContext();

        $tagsUI = $crawler->filter('ul.tags[data-tag-count]');

        if ($tagsUI->count() !== 1) {
            $this->fail('Make sure to add a "data-tag-count" attribute to the ul.tags element');
        }

        $dataTagCount = $tagsUI->attr('data-tag-count');

        $this->assertEquals(count($context['tags']), $dataTagCount, sprintf(
            'Since there are "%d" tags, I expected the data-tag-count to be equal to "%d". But instead, I see "%d".',
            count($context['tags']),
            count($context['tags']),
            $dataTagCount
        ));
    }
}
