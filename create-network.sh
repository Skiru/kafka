#!/bin/sh

set -eux

NETWORK=$(docker network ls --filter name=$DOCKER_NETWORK_NAME --format json)

if [ "$NETWORK" = "" ]; then
    docker network create $DOCKER_NETWORK_NAME
fi
