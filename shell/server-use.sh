#!/usr/bin/env bash

basepath=$(cd `dirname $0`; pwd)

${basepath}/cpu-use.sh
${basepath}/disk-use.sh
${basepath}/memory-use.sh
