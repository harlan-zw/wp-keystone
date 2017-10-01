#!/usr/bin/env bash

## Copy Environment Utility ##

# This script is intended to be used to copy the staging or production environments for all required data to create a copied environment.
# It will copy over all uploaded files and it will copy over the database, doing a search and replace for our URL.
# For this script to work the target server needs to have wp-cli installed

if (( $# != 1 ))
then
  echo "Usage: copy-env.sh <environment>"
  exit 1
fi

echo "Beginning environment duplication from $1 to local."

# Import our environment variables
source ".env"

ALIASES=$(wp cli alias --format=json)

# Here we need to extract the wp-cli.yml constants
SSH_PATH=$(php -r "
function replace_first(\$find, \$replace, \$subject) {
    return implode(\$replace, explode(\$find, \$subject, 2));
}
\$key = '@$1';
echo replace_first('/', ':/', json_decode('$ALIASES')->\$key->ssh);
")
COPY_URL=$(php -r "
\$key = '@$1';
echo json_decode('$ALIASES')->\$key->url;
")


echo "Copying over files SCP using SSH path $SSH_PATH"

# Copy over all upload files
scp -r $SSH_PATH/web/app/uploads web/app

# Disable database copying for now

#echo "Copying over database"
#
## Copy the database over
#wp @$1 db export - | wp db import -
#
#echo "Doing search replace with environment url $COPY_URL"
#
##
#wp search-replace "${COPY_URL}" "${WP_HOME}"
