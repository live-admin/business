#!/bin/bash
set -e
DIR="$( cd "$( dirname "$0"  )" && pwd  )"
cd $DIR
./cd-deploy.sh "${image_name}" "${last_image_name}" "fail" "false"