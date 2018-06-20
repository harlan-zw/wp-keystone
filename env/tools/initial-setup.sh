#!/usr/bin/env bash

# This script is intended to setup the entire local awaba environment for a user in just one script
echo "Building Environment"

# Firstly do menial copy tasks
echo "Setting up folders & files"

cp -n env/.env.example .env # copy over default environment variables
cp -n web/.htaccess.sample web/.htaccess # make sure we use a .htaccess
mkdir -p runtime/cache
mkdir -p runtime/apache
chmod -R 777 runtime

echo "Building Assets"

# Then Build all of our assets
./env/build/deploy.sh

# Add in our git hooks
./vendor/bin/cghooks add --no-lock

echo "Assets Built"

# Copy over all files and database
# ./env/tools/copy-env.sh live

# start selenium server
# nohup ./vendor/bin/selenium-server-standalone &