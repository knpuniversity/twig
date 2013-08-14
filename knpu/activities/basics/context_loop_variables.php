<?php

$parentContext = require __DIR__.'/context_print_variables.php';

return array_merge($parentContext, array(
    'tags' => array('foo', 'bar', 'baz'),
));