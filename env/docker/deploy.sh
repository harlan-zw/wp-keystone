#!/usr/bin/env bash

# Import our environment variables
source ".env"

docker login --username $DOCKER_HUB_USERNAME --password $DOCKER_HUB_PASSWORD

docker-compose build

docker-compose push