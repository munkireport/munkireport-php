#!/bin/sh
# Made by tuxudo

# Power and battery information

# Skip manual check
if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"
powerfile="$DIR/cache/powerinfo.plist"

# vvv New line variable vvv # no touchy
nl='
'
# ^^^ New line variable ^^^ # no touchy

# Gather standard powerer information and settings
pmset_general=$(pmset -g 2>/dev/null | sed -e 's/"//g' -e s'/powerbutton/ Sleep On Power Button /g')
pmset_schedule=$(pmset -g sched 2>/dev/null | sed -e 's/  /schedule: /g' -e 's/"//g')
pmset_ups=$(pmset -g ups 2>/dev/null | sed -e 's/on//g' -e 's/off//g' -e 's/"//g')
pmset_stats=$(pmset -g stats 2>/dev/null | sed -e 's/"//g')
pmset_adapter=$(pmset -g adapter 2>/dev/null | sed -e 's/W//g' -e 's/"//g')
pmset_therm=$(pmset -g therm 2>/dev/null | sed -E $'s/CPU_Scheduler_Limit/\\\nCPU_Scheduler_Limit/g' | sed -e 's/"//g' )
pmset_sysload=$(pmset -g sysload 2>/dev/null | sed -e 's/"//g')
pmset_assertions=$(pmset -g assertions 2>/dev/null | sed -e 's/"//g')
pmset_accps=$(pmset -g accps 2>/dev/null | grep -v "InternalBattery" | grep -v "Mouse" | grep -v "Keyboard" | sed -e 's/ -/UPS Name: /g' -e 's/"//g')
pmset_ups_name=$(pmset -g accps 2>/dev/null | grep -v "InternalBattery" | sed -e 's/ -/XWXWXW UPS Name: /g' -e 's/"//g' | awk -v FS="(XWXWXW|;)" '{print substr($2, 1, length($2)-4)}')
pmset_ups_percent=$(pmset -g accps 2>/dev/null | grep -v "InternalBattery" | grep -v "Mouse" | grep -v "Keyboard" | awk 'match($0,"%;"){print substr($0,RSTART-4,4)}' | awk -F' ' '{printf "%s%s\n", $1, $2}' | sed -e 's/"//g' -e 's/(//g' -e 's/)//g')
pmset_ups_charging_status=$(pmset -g accps 2>/dev/null | grep -v "InternalBattery" | grep -v "ow drawing from" | grep -o '.....$' | awk -F' ' '{printf "%s%s\n", $1, $2}' | sed -e 's/"//g')

pmset_ups_percent_out="UPS Percent: "$pmset_ups_percent
pmset_ups_charging_status_out="UPS Status: "$pmset_ups_charging_status

#### Battery Information ####

## Check if not a MacBook, MacBook Air or MacBook Pro
AppleSmartBattery=$(ioreg -n AppleSmartBattery -r)
if [ -z "$AppleSmartBattery" ]; then
	# echo No battery information found
	battery_info=""

else

battery_text1=$(ioreg -n AppleSmartBattery -r | sed -e 's/" = 65535/" = -1/g' -e 's/"//g')
battery_text2=$(system_profiler SPPowerDataType | grep 'Amperage')

## Battery Condition
BatteryInstalled=$(echo "$AppleSmartBattery" | grep "BatteryInstalled" | awk '{ print $NF }' | awk -F' ' '{printf "%s%s\n", $1, $2}')
if [ "$BatteryInstalled" == Yes ]; then
	Condition=`system_profiler SPPowerDataType | grep 'Condition' | awk '{$1=""; print}' | awk -F' ' '{printf "%s%s\n", $1, $2}'`  ## print all except first column
	condition="condition = ${Condition}"

    ## Sometimes an inserted battery will show up as blank
    if [[ -z "${Condition}" ]]; then
	   condition="condition = No Battery"
    fi

else
	condition="condition = No Battery"
fi

battery_info="${condition}${nl}${battery_text1}${nl}${battery_text2}"

fi

final_stats="${nl}${battery_info}${nl}${pmset_general}${nl}${pmset_schedule}${nl}${pmset_ups}${nl}${pmset_stats}${nl}${pmset_adapter}${nl}${pmset_therm}${nl}${pmset_sysload}${nl}${pmset_assertions}${nl}${pmset_accps}${nl}${pmset_ups_name}${nl}${pmset_ups_percent_out}${nl}${pmset_ups_charging_status_out}"

# Delete the old file or plistbuddy will complain :/
rm "$powerfile" 2>/dev/null

# Check if on 10.6 or 10.7, they output slightly different formatted information
if [[ $(/usr/bin/sw_vers -productVersion | /usr/bin/cut -d . -f 2) -lt 8 ]]; then
# Turn the final_stats text mess into a pretty PLIST file for uploading
for TRANSLATE in '      ManufactureDate = ' '      DesignCapacity = ' '      MaxCapacity = ' '      CurrentCapacity = ' '      CycleCount = ' '      Temperature = ' 'condition = ' ' standbydelay         ' ' standby              ' ' womp		' ' halfdim	' ' hibernatefile	' ' gpuswitch            ' ' sms		' ' networkoversleep	' ' disksleep	' ' sleep		' ' autopoweroffdelay    ' ' hibernatemode	' ' autopoweroff         ' ' ttyskeepawake	' ' displaysleep	' ' acwake		' ' lidwake	' ' Sleep On Power Button ' ' powernap             ' ' autorestart	' ' DestroyFVKeyOnStandby		' 'schedule: ' '  haltlevel		' '  haltafter		' '  haltremain		' ' lessbright           ' 'Sleep Count:' 'Dark Wake Count:' 'User Wake Count:' ' attage = ' ' AdapterID = ' ' Family Code = ' ' Serial Number = ' 'CPU_Scheduler_Limit 	= ' '	CPU_Available_CPUs 	= ' '	CPU_Speed_Limit 	= ' '   BackgroundTask                 ' '   ApplePushServiceTask                    ' '   UserIsActive                            ' '   PreventUserIdleDisplaySleep             ' '   PreventSystemSleep                      ' '   ExternalMedia                           ' '   PreventUserIdleSystemSleep              ' '   NetworkClientActive            ' '  combined level = ' '  - user level = ' '  - battery level = ' '  - thermal level = ' ' UPS Name:' 'Now drawing from ' 'UPS Percent: ' 'UPS Status: ' '      ExternalConnected = ' '      TimeRemaining = ' '      InstantTimeToEmpty = ' '      CellVoltage = ' '      Voltage = ' '      PermanentFailureStatus = ' '      Manufacturer = ' '      PackReserve = ' '      AvgTimeToFull = ' '      BatterySerialNumber = ' '      Amperage (mA): ' '      FullyCharged = ' '      IsCharging = ' '      DesignCycleCount9C = ' '      AvgTimeToEmpty = '
do
    OUTVALUE=$(grep -e "${TRANSLATE}" <<< "${final_stats}" | sed -e "s/${TRANSLATE}//g" )
    OUTKEY=$(awk -F' ' '{printf "%s%s\n", $1, $2}' <<< "${TRANSLATE}" | tr -cd '[[:alnum:]]' )
    /usr/libexec/PlistBuddy -c "add :${OUTKEY} string ${OUTVALUE}" "$powerfile" 1>/dev/null
done

# Else on 10.8+
else
# Turn the final_stats text mess into a pretty PLIST file for uploading
for TRANSLATE in '      ManufactureDate = ' '      DesignCapacity = ' '      MaxCapacity = ' '      CurrentCapacity = ' '      CycleCount = ' '      Temperature = ' 'condition = ' ' standbydelay         ' ' standby              ' ' womp                 ' ' halfdim              ' ' hibernatefile        ' ' gpuswitch            ' ' sms                  ' ' networkoversleep     ' ' disksleep            ' ' sleep                ' ' autopoweroffdelay    ' ' hibernatemode        ' ' autopoweroff         ' ' ttyskeepawake        ' ' displaysleep         ' ' acwake               ' ' lidwake              ' ' Sleep On Power Button ' ' powernap             ' ' autorestart          ' ' DestroyFVKeyOnStandby		' 'schedule: ' '  haltlevel		' '  haltafter		' '  haltremain		' ' lessbright           ' 'Sleep Count:' 'Dark Wake Count:' 'User Wake Count:' ' attage = ' ' AdapterID = ' ' Family Code = ' ' Serial Number = ' 'CPU_Scheduler_Limit 	= ' '	CPU_Available_CPUs 	= ' '	CPU_Speed_Limit 	= ' '   BackgroundTask                 ' '   ApplePushServiceTask           ' '   UserIsActive                   ' '   PreventUserIdleDisplaySleep    ' '   PreventSystemSleep             ' '   ExternalMedia                  ' '   PreventUserIdleSystemSleep     ' '   NetworkClientActive            ' '  combined level = ' '  - user level = ' '  - battery level = ' '  - thermal level = ' ' UPS Name:' 'Now drawing from ' 'UPS Percent: ' 'UPS Status: ' '      ExternalConnected = ' '      TimeRemaining = ' '      InstantTimeToEmpty = ' '      CellVoltage = ' '      Voltage = ' '      PermanentFailureStatus = ' '      Manufacturer = ' '      PackReserve = ' '      AvgTimeToFull = ' '      BatterySerialNumber = ' '      Amperage (mA): ' '      FullyCharged = ' '      IsCharging = ' '      DesignCycleCount9C = ' '      AvgTimeToEmpty = '
do
    OUTVALUE=$(grep -e "${TRANSLATE}" <<< "${final_stats}" | sed -e "s/${TRANSLATE}//g" )
    OUTKEY=$(awk -F' ' '{printf "%s%s\n", $1, $2}' <<< "${TRANSLATE}" | tr -cd '[[:alnum:]]' )
    /usr/libexec/PlistBuddy -c "add :${OUTKEY} string ${OUTVALUE}" "$powerfile" 1>/dev/null
done
fi

exit 0
