#!/bin/sh

# Power information

# Skip manual check
if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"
powerfile="$DIR/cache/powerinfo.txt"

# Gather standard powerer information and settings
pmset_general=$(pmset -g 2>/dev/null)
pmset_schedule=$(pmset -g sched | sed -e 's/  /schedule: /g' 2>/dev/null)
pmset_ups=$(pmset -g ups | sed -e 's/on//g' -e 's/off//g' 2>/dev/null)
pmset_stats=$(pmset -g stats 2>/dev/null)
pmset_adapter=$(pmset -g adapter | sed -e 's/W//g' 2>/dev/null)
pmset_therm=$(pmset -g therm | sed -E $'s/CPU_Scheduler_Limit/\\\nCPU_Scheduler_Limit/g' 2>/dev/null)
pmset_sysload=$(pmset -g sysload 2>/dev/null)
pmset_assertions=$(pmset -g assertions 2>/dev/null)
pmset_accps=$(pmset -g accps | grep -v "InternalBattery" | sed -e 's/ -/UPS Name: /g' 2>/dev/null)
pmset_ups_name=$(pmset -g accps | grep -v "InternalBattery" | sed -e 's/ -/XWXWXW UPS Name: /g' | awk -v FS="(XWXWXW|;)" '{print substr($2, 1, length($2)-4)}' 2>/dev/null)
pmset_ups_percent=$(pmset -g accps | grep -v "InternalBattery" | awk 'match($0,"%;"){print substr($0,RSTART-4,4)}' | awk -F' ' '{printf "%s%s\n", $1, $2}' 2>/dev/null)
pmset_ups_charging_status=$(pmset -g accps | grep -v "InternalBattery" | grep -v "ow drawing from" | grep -o '.....$' | awk -F' ' '{printf "%s%s\n", $1, $2}' 2>/dev/null)

pmset_ups_percent_out="UPS Percent: "$pmset_ups_percent
pmset_ups_charging_status_out="UPS Status: "$pmset_ups_charging_status

#### Battery Information ####

## Check if not a MacBook, MacBook Air or MacBook Pro
AppleSmartBattery=$(ioreg -n AppleSmartBattery -r)
if [ -z "$AppleSmartBattery" ]; then
	# echo No battery information found
	battery_info=""

else

battery_text=$(ioreg -n AppleSmartBattery -r | sed -e 's/" = 65535/" = -9876543/g')

## Battery Condition
BatteryInstalled=$(echo "$AppleSmartBattery" | grep "BatteryInstalled" | awk '{ print $NF }' | awk -F' ' '{printf "%s%s\n", $1, $2}')
if [ "$BatteryInstalled" == Yes ]; then
	Condition=`system_profiler SPPowerDataType | grep 'Condition' | awk '{$1=""; print}' | awk -F' ' '{printf "%s%s\n", $1, $2}'`  ## print all except first column
	condition="condition = $Condition"
    
    ## Sometimes an inserted battery will show up as blank
    if [[ -z "${Condition}" ]]; then
	   condition="condition = No Battery"
    fi
    
else
	condition="condition = No Battery"
fi

battery_info="${battery_text}\n${condition}"

fi 

final_stats="\n${battery_info}\n${pmset_general}\n${pmset_schedule}\n${pmset_ups}\n${pmset_stats}\n${pmset_adapter}\n${pmset_therm}\n${pmset_sysload}\n${pmset_assertions}\n$pmset_accps\n${pmset_ups_name}\n${pmset_ups_percent_out}\n${pmset_ups_charging_status_out}"

echo -e "${final_stats}" > "$powerfile"

exit 0
