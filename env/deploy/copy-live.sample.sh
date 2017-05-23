#!/usr/bin/env bash

# Could automate this but it's easier to hardcode at this stage
LIVE_URL="https://boilerplate.com"

# Import our environment variables
source ".env"

# Copy over all upload files
scp -r user@host:~/path/app/uploads web/app

# Copy the database over
wp @live db export - | wp db import -

#
wp search-replace "${LIVE_URL}" "${WP_HOME}"
