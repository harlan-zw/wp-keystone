#!/usr/bin/env bash

# Usage: buildDeployableArtifact.sh "production" "boilerplate" "elasticbeanstalk-ap-southeast-2-xxxxxxxxxxxx"

ENVIRONMENT=$1
APPLICATION_NAME=$2
S3_BUCKET=$3

GIT_HASH=$(git log --pretty=format:'%H' -n 1)
FILENAME="$GIT_HASH.zip"
MESSAGE=$(git log -1 --pretty=%B)

zip builds/"$FILENAME" -r * .[^.]*

/root/.local/bin/aws s3 cp builds/"$FILENAME" s3://"$S3_BUCKET"/builds/"$FILENAME"

/root/.local/bin/aws elasticbeanstalk create-application-version --application-name "$APPLICATION_NAME" --version-label "$GIT_HASH" --description "$MESSAGE" --source-bundle S3Bucket="$S3_BUCKET",S3Key="builds/$FILENAME"

/root/.local/bin/aws elasticbeanstalk update-environment --application-name "$APPLICATION_NAME" --version-label "$GIT_HASH" --environment-name "$ENVIRONMENT"