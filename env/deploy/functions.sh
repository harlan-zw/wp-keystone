#!/usr/bin/env bash

SUCCESS_C='\e[0;32m' #green
ERROR_C='\e[0;31m' #red
INFO_C='\e[0;35m' #purple
NC='\e[0m' # No Color

COMPOSER_CONFIG="composer.json"
YARN_CONFIG="package.json"

# Checks if a file is modified
function is_modified_git() {

    echo -ne "${INFO_C}  Checking $1 for changes..."

    DIFF=$(git --no-pager diff --stat @{1} -- $1 2>/dev/null) || {
        echo -ne "${ERROR_C}Git check failed:${NC}\n"
        git --no-pager diff --stat @{1} -- $1
        return 0 # if the diff test fails, return true so the deployment will continue
    }

    DIFF_COUNT=$(echo "$DIFF" | wc -l)

    if [[ "$DIFF_COUNT" > "1" ]];then
        echo -ne "${INFO_C} Modified:${NC}\n"
        git --no-pager diff --stat @{1} -- $1
    else
        echo -ne "${NC} No Changes\n"
    fi

    [[ "$DIFF_COUNT" > "1" ]]
}

# Does a composer update
function do_composer_update() {

    OLD_DIR=$(pwd)
    WORKING_DIR=$(dirname $1)
    cd $WORKING_DIR

    FLAG="--dev --ignore-platform-reqs"
    if [ "${WP_ENV}" == "production" ];
    then
        FLAG="--no-dev"
    fi

    echo -ne "${INFO_C}  Updating Composer [$1 $FLAG]...${NC}\n" # install composer

    COMPOSER_OUTPUT=$(composer install $FLAG --optimize-autoloader --no-interaction ) || { #attempt to install composer
        echo -e "${ERROR_C} Composer install failed${NC}"
        echo "$COMPOSER_OUTPUT"
        exit 1
    }
    echo -e "$COMPOSER_OUTPUT"
    #run composer dump-autoload if anything in the app has changed (it creates an autoloader file with all the classes)
    COMPOSER_DUMP_AUTOLOAD=$(composer dump-autoload -o 1>/dev/null 2>/dev/null) || {
        echo -e "${ERROR_C} Problem running composer dump-autoload ${NC}\n"
        echo "$COMPOSER_DUMP_AUTOLOAD"
        exit 1
    }
    echo -ne "${SUCCESS_C}  Packages updated successfully ${NC}\n\n"
    cd $OLD_DIR
}

# Updates yarn dependencies
function do_yarn_update() {

    OLD_DIR=$(pwd)
    WORKING_DIR=$(dirname $1)
    cd $WORKING_DIR

    # update yarn dependencies
    echo -ne "${INFO_C}  Updating Yarn Packages [$1 ]...${NC}\n"
    yarn --ignore-optional

    cd $OLD_DIR
}

# Builds our themes assets with yarn
function do_yarn_build() {

    OLD_DIR=$(pwd)
    WORKING_DIR=$(dirname $1)
    cd $WORKING_DIR

    FLAG=""
    if [ "${WP_ENV}" == "production" ];
    then
        FLAG=":production"
    fi

    # build stuff
    echo -ne "${INFO_C}  Building Yarn [$1 $FLAG]...${NC}\n"
    yarn run build"$FLAG" --ignore-optional

    cd $OLD_DIR
}
# Checks if composer is installed globally or locally
function check_composer_install() {

    type -t composer >/dev/null 2>&1 || {

        type -t $HOME/composer.phar >/dev/null 2>&1 || {
                echo -e "${ERROR_C} Command composer isn't available, and local composer.phar not found in home directory${NC}"
                exit 1
        }
        shopt -s expand_aliases #direct bash to actually parse aliases
        alias composer='$HOME/composer.phar' # create a new alias
        echo "${SUCCESS_C}Found Composer at $HOME/composer.phar - Checking for composer.json changes${NC}"
    }
    echo -e "${SUCCESS_C}Found Composer Global - Checking for composer.json changes${NC}"
}

# Checks if user has yarn globally
function check_yarn_install() {

   type -t yarn >/dev/null 2>&1 || {
       echo -e "${ERROR_C} Command yarn isn't available.${NC}"
    }
    echo -e "${SUCCESS_C}Found Yarn - Checking for package.json changes${NC}"
}

function clear_caches() {
  wp cache flush
  wp total-cache flush all

  # clear the blade cache
  rm -rf web/app/uploads/cache
}

function push_dist_files() {
    if [ "${WP_ENV}" != "development" ];
    then
        wp s3 push-dist
    fi
}