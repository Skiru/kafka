#!/usr/sh

docker-compose -p purple-clouds -f ./apps/mailer/docker-compose.yaml up -d --build
