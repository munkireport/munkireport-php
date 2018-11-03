#!/bin/bash

# detectx controller
#CTL="${BASEURL}index.php?/module/detectx/"
# Set preference to include this file in the preflight check
setreportpref "detectx" "${CACHEPATH}detectx.json"
if [ ! -f "${CACHEPATH}detectx.json" ]; then
  touch "${CACHEPATH}detectx.json"
fi
