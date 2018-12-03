#!/bin/bash

set -e

SUCCESS_C='\e[0;32m' #green
ERROR_C='\e[0;31m' #red
INFO_C='\e[0;35m' #purple
NC='\e[0m' # No Color

COMPOSER_CONFIG="composer.json"
YARN_CONFIG="package.json"

# Does a composer update
function do_composer_install() {
    FLAG=""
    if [ "${WP_ENV}" == "production" ] || [ "${WP_ENV}" == "uat" ];
    then
        FLAG="--no-dev --no-scripts"
    elif [ "${WP_ENV}" != "development" ]
    then
        FLAG="--no-scripts"
    fi

    echo -ne "${INFO_C} composer install $FLAG...${NC}\n" # install composer

    COMPOSER_OUTPUT=$(composer install $FLAG --optimize-autoloader --no-interaction ) || { #attempt to install composer
        echo -e "${ERROR_C} Composer install failed${NC}"
        echo "$COMPOSER_OUTPUT"
        exit 1
    }
    echo -e "$COMPOSER_OUTPUT"
}

# install yarn dependencies / binaries
function do_yarn_install() {
    # update yarn dependencies
    echo -ne "${INFO_C} yarn...${NC}\n"
    yarn
}

# Builds our themes assets with yarn
function do_yarn_build() {
    FLAG=""
    if [ "${WP_ENV}" == "production" ] || [ "${WP_ENV}" == "uat" ];
    then
        FLAG=":production"
    fi

    # build stuff
    echo -ne "${INFO_C} yarn build$FLAG...${NC}\n"
    yarn build"$FLAG"

}

function clear_caches() {
  wp cache flush --allow-root
#  wp total-cache flush all
# w3 total cache

  # clear the blade cache
  rm -rf web/app/uploads/cache
}

# Import our environment variables
. ./.env #import constants

echo -e "${INFO_C}Building ${WP_HOME} - ${WP_ENV} ${NC}\n"

# Check root composer.json
do_composer_install

echo -e ""

do_yarn_install

echo -e ""

clear_caches

echo -e ""

echo -e "${SUCCESS_C}Assets build completed${NC}\n"
exit 0 #exit 0, all is ok
