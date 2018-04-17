#!/usr/bin/env bash

basepath=$(cd `dirname $0`; pwd)

host=$(hostname);
app="server"
item="memory_use"
value=$(free -m | awk 'NR==2{printf "%3.0f", $3*100/$2 }');

echo "{\"host\":\"${host}\",\"app\":\"${app}\",\"item\":\"${item}\",\"value\":${value}}" >> ${basepath}/../var/logstash/health-watcher.log;
