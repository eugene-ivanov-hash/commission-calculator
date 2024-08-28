#!/usr/bin/env bash

set -e

cd "$(dirname "$0")/../"

if [ ! -f .env ]
then
  cp .env.dist .env
else
  . .env
fi

LINUX_USER_ID=$(id -u)

COMPOSER_HOME='~/.composer'

if [[ "$OSTYPE" == "darwin"* ]]; then
  XDEBUG_CLIENT_HOST=${XDEBUG_CLIENT_HOST:-host.docker.internal}
else
  XDEBUG_CLIENT_HOST=${XDEBUG_CLIENT_HOST:-172.17.0.1}
fi

COMPOSE_FILE=${COMPOSE_FILE:-docker-compose.yaml}

for var in LINUX_USER_ID XDEBUG_CLIENT_HOST USE_DOCKER_SYNC COMPOSE_FILE COMPOSER_HOME
do
  ENVIRONMENT=$(sed "s ^$var=.* $var=${!var} " .env)
  echo "$ENVIRONMENT" > .env
done
