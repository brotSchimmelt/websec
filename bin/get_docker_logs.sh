#!/bin/bash

################################################################################
#   Purpose: Extract selected log files from docker containers                 #
#   Test: Tested on Ubuntu 18 LTS                                              #
#   Note: Not yet tested on Ubuntu 20 LTS                                      #
#   Author: tknebler@gmail.com                                                 #
#                                                                              #
#   Select which log files to copy                                             #
#   Copy log files from docker containers                                      #
################################################################################

# get container names
php_apache="$(docker ps --format "{{.Names}}" | grep php_apache)"
db_login="$(docker ps --format "{{.Names}}" | grep db_login)"
db_shop="$(docker ps --format "{{.Names}}" | grep db_shop)"

printf "## This script outputs all relevant log files\n"
printf "## from the docker container into this directory.\n\n"
printf "## The relevant commands can also be found under: "\
"https://docs.docker.com/engine/reference/commandline/logs/ \n"

# Select log files
user_input=-1
while (( $user_input < 1 || $user_input > 5 )); do
printf "\n1) Apache error logs 2) Apache access logs \n3) MSMPT (mail) logs "\
"4) MySQL logs \n5) All of the above\n\n"
printf "Select an option: "
read user_input
done

# Choose log file option
case $user_input in
    1) 
        printf "creating file ...\n"
        docker logs -t --details $php_apache > /dev/null 2> errors_php.log
        ;;
    2)
        printf "creating file ...\n"
        docker logs -t --details $php_apache 2> /dev/null > access.log
        ;;
    3)
        printf "creating file ...\n"
        docker cp $php_apache:/var/log/msmtp/msmtp.log msmtp.log 2>/dev/null
        ;;
    4)
        printf "creating file ...\n"
        docker logs -t $db_login 2> mysql_login.log > /dev/null
        docker logs -t $db_shop 2> mysql_shop.log > /dev/null
        ;;
    5)
        printf "creating files ...\n"
        docker logs -t --details $php_apache > /dev/null 2> errors_php.log
        docker logs -t --details $php_apache 2> /dev/null > access.log
        docker cp $php_apache:/var/log/msmtp/msmtp.log msmtp.log 2>/dev/null
        docker logs -t $db_login 2> mysql_login.log > /dev/null
        docker logs -t $db_shop 2> mysql_shop.log > /dev/null
        ;;
    *)
        echo "Error: No valid choice"
        exit 1
        ;;
esac
