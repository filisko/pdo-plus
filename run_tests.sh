#!/usr/bin/env bash

declare -a phps=("8.0" "8.1" "8.2" "8.3")

for php in "${phps[@]}"; do
    echo '============================================='
    echo "> [php $php]"
    echo '============================================='

    sudo update-alternatives --set php "/usr/bin/php${php}"

    composer update

    php vendor/bin/phpunit
done
