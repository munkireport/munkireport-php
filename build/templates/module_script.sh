#!/bin/sh

# Script to collect data
# and put the data into outputfile

CWD=$(dirname $0)
CACHEDIR="$CWD/cache/"
OUTPUT_FILE="${CACHEDIR}MODULE.txt"
SEPARATOR=' = '

# Business logic goes here

# Output data here
echo "item1${SEPARATOR}string value" > ${OUTPUT_FILE}
echo "item2${SEPARATOR}100" >> ${OUTPUT_FILE}
