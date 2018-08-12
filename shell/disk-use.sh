#!/usr/bin/env bash

basepath=$(cd `dirname $0`; pwd)
date=$(date +%F);

host=$(hostname);
app="server"
item="disk_use"
value=$(df -hl | awk 'BEGIN {max = 0} {if ($5+0 > max+0) max=$5} END {printf "%3.0f", max}');

value=$(echo $value | awk 'gsub(/^ *| *$/,"")');

if [ -z ${value} ];then
    value=0;
fi

echo "{\"host\":\"${host}\",\"app\":\"${app}\",\"item\":\"${item}\",\"value\":${value}}" >> ${basepath}/../var/logstash/health-watcher.log.${date};
