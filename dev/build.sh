#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

readonly name="pdo-plus"

uid=$(id -u)
gid=$(id -g)
user=$(whoami)

docker build --build-arg user="$user" \
  --build-arg uid="$uid" \
  --build-arg gid="$gid" \
   -t $name $DIR/../.
docker run -it --rm  -v "$DIR/../":/usr/src/myapp -w /usr/src/myapp $name composer install
