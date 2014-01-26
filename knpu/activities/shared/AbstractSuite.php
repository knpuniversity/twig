<?php

use KnpU\ActivityRunner\Assert\AssertSuite;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractSuite extends AssertSuite
{
    protected function findElementByCss($css, $output, $noneMsg)
    {
        $ele = $this->getCrawler($output)->filter($css);
        $this->assertNotEquals(0, count($ele), $noneMsg);

        return $ele;
    }
}