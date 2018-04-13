#!/usr/bin/env bash

basepath=$(cd `dirname $0`; pwd)

host=$(hostname);
app="server"
item="cpu_use"
value=$(top -b -n2 -p 1 | fgrep "Cpu(s)" | tail -1 | awk -F'id,' -v prefix="$prefix" '{ split($1, vs, ","); v=vs[length(vs)]; sub("%", "", v); printf "%s%.1f\n", prefix, 100 - v }');

echo "{\"host\":\"${host}\",\"app\":\"${app}\",\"item\":\"${item}\",\"value\":${value}}" >> ${basepath}/../var/logstash/health-watcher.log;
