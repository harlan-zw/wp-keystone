#!/usr/bin/env bash

APPLICATION_NAME="local.boilerplate.com"

GIT_HASH=$(git log --pretty=format:'%H' -n 1)
FILENAME="$GIT_HASH.zip"
MESSAGE=$(git log -1 --pretty=%B)

zip "$FILENAME" -x *.git* -r * .[^.]*

# Move file to s3 bucket
aws s3 cp "$FILENAME" s3://elasticbeanstalk-ap-southeast-2-745484781960/builds/"$FILENAME"

aws elasticbeanstalk create-application-version --application-name "$APPLICATION_NAME" --version-label "$GIT_HASH" --description "$MESSAGE" --source-bundle S3Bucket=elasticbeanstalk-ap-southeast-2-745484781960,S3Key="builds/$FILENAME"

aws elasticbeanstalk update-environment --application-name "$APPLICATION_NAME" --version-label "$GIT_HASH" --environment-name "$ENVIRONMENT"