#! /bin/bash

docker run --rm --tty -v $PWD:/app composer:1.10 install
# docker run --rm --tty -v $PWD:/app composer:1.10 require nelmio/api-doc-bundle
docker run --rm -v $PWD:/app -w /app node:lts-alpine yarn install && yarn run build
docker-compose build

echo '
________                      ._.
\______ \   ____   ____   ____| |
 |    |  \ /  _ \ /    \_/ __ \ |
 |    `   (  <_> )   |  \  ___/\|
/_______  /\____/|___|  /\___  >_
        \/            \/     \/\/
'