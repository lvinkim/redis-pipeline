#!/usr/bin/env bash

basepath=$(cd `dirname $0`; pwd)

host=$(hostname);
app="server"
item="disk_use"
value=$(df -hl | awk 'BEGIN {max = 0} {if ($5+0 > max+0) max=$5} END {printf "%3.0f", max}');

echo "{\"host\":\"${host}\",\"app\":\"${app}\",\"item\":\"${item}\",\"value\":\"${value}\"}" >> ${basepath}/../var/logstash/health_watcher.log;
