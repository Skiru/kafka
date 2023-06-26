#!/usr/sh

docker-compose -p purple-clouds -f ./apps/idp/docker-compose.yaml up -d --build
