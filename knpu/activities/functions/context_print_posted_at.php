<?php

$parentContext = require __DIR__.'/../basics/context_loop_variables.php';

return array_merge($parentContext, array(
    'postedAt' => new \DateTime('June 5, 2013'),
));
