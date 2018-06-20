#!/usr/bin/env bash

docker-compose up -d

# Add our SSH key so we can use copy-env script
docker cp ~/.ssh/ mcgrath:/home/wp/.ssh/

cp -n env/.env.local .env # copy over default environment variables

./env/tools/initial-setup.sh

echo "local.mcgrathfoundation.com.au is ready to go!"
