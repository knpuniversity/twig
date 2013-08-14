<?php

use KnpU\ActivityRunner\Assert\AssertSuite;

class DumpSuite extends AssertSuite
{
    public function testDumpFunctionUsedProperly()
    {
        $inputFiles = $this->getActivity()->getInputFiles();
        $input = $inputFiles->get('product.twig');
        $err = 'Make sure to use the Twig dump() function inside of a say something tag ({{ foo }})';

        // Look for `dump()`, potentially with spaces in `dump()`.
        $this->assertRegExp('#dump\(\s*\)#', $input, $err);

        // Look for the var_dump output.
        $output = $this->getOutput();

        $this->assertContains("'name'", $output, $err);
        $this->assertContains("'price'", $output, $err);
        $this->assertContains("'postedAt'", $output, $err);
    }
}
