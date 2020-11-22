# Tests

This directory contains all [PHPUnit](https://phpunit.de/) test classes for the php functions.

The test classes can be run separately with the command:

```shell
$ ./vendor/bin/phpunit <TestClass>.php
```

If you wish to run all tests in a row, you can use the **run_all_test_classes.sh** script. In addition to that, you can run all tests inside the php docker container with the **bin/run_unit_tests_docker.sh** script (recommended).

To avoid any unexpected behavior you should run the tests only in a non-production environment!
 