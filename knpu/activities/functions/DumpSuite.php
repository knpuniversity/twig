<?php

use KnpU\ActivityRunner\Assert\AssertSuite;

use KnpU\ActivityRunner\Result;

class DumpSuite extends AssertSuite
{
    public function runTest(Result $resultSummary)
    {
        $input = $resultSummary->getInput();
        $err = 'Make sure to use the Twig dump() function inside of a say something tag ({{ foo }})';

        // Look for `dump()`, potentially with spaces in `dump()`.
        $this->assertRegExp('#dump\(\s*\)#', $input, $err);

        // Look for the var_dump output.
        $output = $resultSummary->getOutput();

        $this->assertContains('name', $output, $err);
        $this->assertContains('price', $output, $err);
        $this->assertContains('postedAt', $output, $err);
    }
}
