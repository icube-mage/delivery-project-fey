#!/bin/bash
if [ -z "$1" ] ; then
        echo 'pipeline are setuped incorrectly'
        exit 1
fi
bid=$(echo $BUILD_ID)
ts=$(date +"%Y%m%d")
githead=$(git rev-parse HEAD)
set -ex
echo "Building Backend Docker image with tag " $$githead-$$ts$$bid
echo $githead-$ts$bid

docker login -u _json_key -p "$(cat /etc/service_key/service-account-key.json | tr '\n' ' ')" https://asia.gcr.io

# Build apps image
docker build -t asia.gcr.io/sirclo-iii-nonprod/$1-dev:$githead-$ts$bid -f Dockerfile .
docker push asia.gcr.io/sirclo-iii-nonprod/$1-dev:$githead-$ts$bid
