#!/bin/bash

################################################################################
#   Purpose: Run all unit tests                                                #
#   Test: Tested on php 7.4.11 and PHPUnit 9.1.5                               #
#                                                                              #
#   Run all the different test classes sequentially                            #
################################################################################

# paths
phpunit=/var/www/html/vendor/bin/phpunit
test_dir=/var/www/html/tests/

# font colors
green="\033[0;32m"
orange="\033[0;33m"
noColor="\033[0m"

# set timer to 0
SECONDS=0

# run all the different test classes sequentially
for i in Admin Basic Error Login Shop Websec; do

    printf "\nTesting ${orange}${i}${noColor} functions ...\n\n"
    $phpunit $test_dir${i}FunctionsTest.php
done

printf "\n${green}It took $SECONDS seconds to run all tests!${noColor}\n"
