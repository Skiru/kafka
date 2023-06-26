#!/bin/sh

docker-compose -p purple-clouds down -v --remove-orphans
docker-compose -p purple-clouds -f ./apps/idp/docker-compose.yaml down -v --remove-orphans
docker-compose -p purple-clouds -f ./apps/mailer/docker-compose.yaml down -v --remove-orphans