#!/usr/bin/env bash

##
# Environment Variables Set in BitBucket Pipelines Settings
# - APPLICATION_NAME
# - AWS_S3_BUCKET
#
# The variable ENVIRONMENT is set by the bitbucket-pipelines.yaml file.
#
##

DATETIME=$(date +'%Y-%m-%d-%H%M%S')
VERSION_LABEL="$DATETIME-$ENVIRONMENT"
FILENAME="$VERSION_LABEL.zip"
MESSAGE=$(git log -1 --pretty=%B)
# Only get the first 200 characters of the commit message - otherwise eb dies
MESSAGE=${MESSAGE:0:199}

zip "$FILENAME" -x *.git* -r * .[^.]*

# Move file to s3 bucket
aws s3 cp "$FILENAME" "s3://$AWS_S3_BUCKET/builds/$FILENAME"

aws elasticbeanstalk create-application-version --application-name "$APPLICATION_NAME" --version-label "$VERSION_LABEL" --description "$MESSAGE" --source-bundle S3Bucket="$AWS_S3_BUCKET",S3Key="builds/$FILENAME"

aws elasticbeanstalk update-environment --application-name "$APPLICATION_NAME" --version-label "$VERSION_LABEL" --environment-name "$ENVIRONMENT"