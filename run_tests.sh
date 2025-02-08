#!/usr/bin/env bash

set -e

declare -a phps=("8.4" "8.3" "8.2" "8.1" "8.0")
if [[ $1 ]]; then
  phps=("$1")
fi

for php in "${phps[@]}"; do
    echo '============================================='
    echo "> [php $php]"
    echo '============================================='

    sudo update-alternatives --set php "/usr/bin/php${php}"

    composer update
    composer test
done
