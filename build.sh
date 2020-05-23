#! /bin/bash

# init app
docker run --rm -v $PWD:/app -w /app node:lts npm i
docker run --rm -v $PWD:/app -w /app node:lts npm rebuild node-sass
docker run --rm -v $PWD:/app -w /app node:lts npm run build
docker run --rm --interactive --tty -v $PWD:/app composer install --ignore-platform-reqs
docker-compose exec -T app console/yii migrate --interactive=0
docker-compose exec -T app console/yii rbac-migrate --interactive=0
docker-compose exec -T app console/yii message @common/config/messages/db.php
docker-compose exec -T app console/yii asset assets.php common/assets/generated/all.php

docker-compose restart


docker run --rm --tty -v $PWD:/app composer install --ignore-platform-reqs
