#!/bin/sh

set -eux
ENV_FILE="$1"

# Export required env variables
set -o allexport
# shellcheck source=./.env
. "${ENV_FILE}"
export DOCKER_NETWORK_NAME=$DOCKER_NETWORK_NAME
set +o allexport

# Run create docker network script
sh create-network.sh

#Build kafka app
docker-compose up -d
