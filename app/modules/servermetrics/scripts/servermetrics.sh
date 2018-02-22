#!/bin/sh
# Crude Code™ by @tuxudo

# OS check
OSVERSION=$(/usr/bin/sw_vers -productVersion | /usr/bin/cut -d . -f 2)

if [[ ${OSVERSION} -gt 10 ]]; then

# Check if Metrics.sqlite exists
if [ -f /Library/Server/Metrics/metrics.sqlite ]; then

/usr/bin/sqlite3 /Library/Server/Metrics/metrics.sqlite -csv "SELECT DATETIME(collectionDate, 'unixepoch'), dataValue FROM metrics;" > /tmp/servermetrics.csv

counter=0

input="/tmp/servermetrics.csv"
while IFS='' read -r line || [[ -n "$line" ]]; do

LINETEST=$(cut -f2 -d',' <<< $line)
if [[ -z $LINETEST ]]; then
line="$line 0.0"
fi

if [[ "$counter" == 0 ]]; then
counter=2
echo '{'${line/$','/$': ['} > /tmp/servermetrics.json

elif [[ "$counter" == 1 ]]; then
counter=$((counter+1))
echo '], '${line/$','/$': ['} >> /tmp/servermetrics.json

elif [[ "$counter" == 16 ]]; then
counter=1
line=${line/$','/$', '}
echo ${line##*\"} >> /tmp/servermetrics.json

else
counter=$((counter+1))
line=${line/$','/$', '}
echo ${line##*\"} >> /tmp/servermetrics.json

fi

done < "$input"

echo "]}" >> /tmp/servermetrics.json
tr -d '\n' < /tmp/servermetrics.json > /usr/local/munki/preflight.d/cache/servermetrics.json

fi
fi

exit 0
