First Attempt at Activities Skeleton
====================================

This is a WIP on how we should organize activities. We want to make things as
simple as possible, but still allow for the teacher to do some advanced things.

* activities.yml:       Holds a list of activities and their metadata

* bootstrap:            Holds a bootstrap file that should be included before each test

* basics                An example directory that holds some things for some activities
    * run.php           The file that should be run to execute the test. It should be
                        magically passed an "$input" variable and should return the
                        output. The bootstrap should have already been included by now.
                        In our case, many tests reuse this same bootstrap file.

    * test_*            The file called after run.php. For each activity, the "test" file
                        is specified in the activity's metadata. This file iss passed the `$output`
                        (the return value from `run.php`. It's job is to throw a detailed
                        exception if the answer was incorrect or not throw an exception otherwise.
                        This should have access to any variables setup in run.php.

Challenges
----------

1) Minimize "setup" code for each activity

2) Ability to run activities independently while creating these things. Obviously, there
    will be some "KnpU" system which is needed to call bootstrap, execute run.php, execute
    test.php and understand the results. But I'd like a way to be able to very easily
    run an activity from the command line while developing, pass it some input,
    and see what the result is.

