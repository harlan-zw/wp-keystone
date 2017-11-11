#!/usr/bin/env bash

# This script is intended to setup the entire local awaba environment for a user in just one script
echo "Building Environment"

# Firstly do menial copy tasks
echo "Setting up folders & files"

cp -R env/hooks .git/ # setup hooks
cp -n env/.env.example .env # copy over default environment variables
cp -n web/.htaccess.sample web/.htaccess # make sure we use a .htaccess
mkdir -p logs # need a logs directory for apache logs
mkdir -p runtime/cache
chmod -R 777 runtime
chmod -R 777 logs

echo "Building Assets"

docker exec project-slug /home/wp/env/build/deploy.sh
# Then Build all of our assets
./env/build/deploy.sh

echo "Assets Built"

# Copy over all files and database
# ./env/tools/copy-env.sh live

# start selenium server
# nohup ./vendor/bin/selenium-server-standalone &