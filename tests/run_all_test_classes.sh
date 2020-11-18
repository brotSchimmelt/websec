#!/bin/bash

################################################################################
#   Purpose: Run all unit tests                                                #
#   Test: Tested on php 7.4.11 and PHPUnit 9.1.5                               #
#                                                                              #
#   Run all the different test classes sequentially                            #
################################################################################

# run all the different test classes sequentially
/var/www/html/vendor/bin/phpunit /var/www/html/tests/BasicFunctionsTest.php
/var/www/html/vendor/bin/phpunit /var/www/html/tests/ErrorFunctionsTest.php
/var/www/html/vendor/bin/phpunit /var/www/html/tests/LoginFunctionsTest.php
