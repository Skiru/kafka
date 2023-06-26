#!/bin/sh

set -eux
ENV_FILE_PATH="./.env"

# Export required env variables
set -o allexport
# shellcheck source=./.env
. "${ENV_FILE_PATH}"
export DOCKER_NETWORK_NAME=$DOCKER_NETWORK_NAME
set +o allexport

sh pull-aplications-submodules.sh

# Run create docker network script
sh create-network.sh

#Build kafka
sh build-kafka.sh

sh build-idp.sh

sh build-mailer.sh
