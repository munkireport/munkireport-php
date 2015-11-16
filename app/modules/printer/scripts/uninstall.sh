#!/bin/bash

# Remove printer script
rm -f "${MUNKIPATH}preflight.d/printer.py"

# Remove printers.txt file
rm -f "${MUNKIPATH}preflight.d/cache/printer.txt"
