#!/bin/bash

################################################################################
#   Purpose: Convert the markdown documentations to valid html.                #
#   Test: Tested on Ubuntu 18 LTS and 20 LTS                                   #
#   Author: tknebler@gmail.com                                                 #
#                                                                              #
#   Check if 'grip' is installed                                               #
#   Convert markdown files in docs/ folder to html                             #
#   Fix formating in html files                                                #
################################################################################

Path=../docs
test_flag=0
cd $Path

# test if 'grip' is installed
python -c "import grip" &> /dev/null

# install 'grip'
if [ $? -gt 0 ]; then
test_flag=1
printf "## It seems like 'grip' is currently not installed on your system.\n"
printf "## 'grip' is a dependency neccessary in order to convert markdown "\
"files to html.\n"
read -p "## Do you want to install it now via pip? [Y/n] " answer
printf "\n\n"

    if [ -z $answer]; then
    answer='Y'
    fi

    if [ $answer == 'Y' ] || [ $answer == 'y' ]
    then
        pip install grip
    fi

    if [ $? -gt 0 ]; then
        printf "Error: It seems like you do not have pip installed on "\
        "your system.\n"
        printf "Please visit pypa.io and download the latest version of pip. "\
        "Then run this script again.\n"
        exit 1
    fi
fi


if [ $test_flag -gt 0 ]; then
# test again if 'grip' is now installed
python -c "import grip" &> /dev/null
fi

if [ $? -eq 0 ]; then

    for i in settings challenges errors shop docker test_environment; do

        md_file="${i}.md"
        if test -f "$md_file"; then
            html_file="${i}.html"
            rm -f $html_file
            grip $md_file --export $html_file # convert Markdown file to HTML
        fi

        # remove double headlines
        sed -i "" "s:$i.md::g" "$i".html
        sed -i "" "s:<h3>::g" "$i".html
        sed -i "" "s:</h3>::g" "$i".html
        sed -i "" 's:<span class="octicon octicon-book"></span>::g' "$i".html
    done
fi
