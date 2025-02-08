#!/usr/bin/env bash

sudo add-apt-repository ppa:ondrej/php -y

declare -a phps=("8.0" "8.1" "8.2" "8.3")

declare -a exts=("ast" "xdebug" "pdo" "mysqli" "curl" "gd" "mbstring" "mysql" "zip" "tidy" "xml" "sqlite3" "dom" "tokenizer" "xmlwriter" "mbstring" "bcmath" "bz2" "intl")

command="apt-get install --no-install-recommends -y"

for php in "${phps[@]}"; do
    command+=" php${php}"

    for ext in "${exts[@]}"; do
        command+=" php${php}-${ext}"
    done
done

eval "$command"

