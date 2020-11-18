
################################################################################
#   Purpose: Run all unit tests inside the PHP docker container                #
#   Test: Tested on php 7.4.11                                                 #
#                                                                              #
#   Get name of the php_apache container                                       #
#   Run all unit test classes INSIDE the php container                         #
################################################################################

# get name of the php_apache container
php_container="$(docker ps --format "{{.Names}}" | grep php_apache)"

# run all unit test classes INSIDE the php container 
docker exec -it $php_container /bin/bash -c "/var/www/html/tests/run_all_test_classes.sh"