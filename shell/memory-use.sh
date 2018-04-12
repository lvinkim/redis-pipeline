#!/usr/bin/env bash

basepath=$(cd `dirname $0`; pwd)

host=$(hostname);
app="server"
item="memory_use"
value=$(free -m|grep Mem|awk '{printf "%2.0f", 100*($3-$6-$7)/$2}');

echo "{\"host\":\"${host}\",\"app\":\"${app}\",\"item\":\"${item}\",\"value\":\"${value}\"}" >> ${basepath}/../var/logstash/health_watcher.log;
