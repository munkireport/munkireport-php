#!/bin/sh

# Power information / status check

# Skip manual check
#if [ "$1" = 'manualcheck' ]; then
#	echo 'Manual check: skipping'
#	exit 0
#fi

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"
powerfile="$DIR/cache/powerinfo.txt"


#### Battery Information & Status ####

## Skip if not a MacBook, MacBook Air or MacBook Pro
ModelIdentifier=`system_profiler SPHardwareDataType | grep Identifier | awk '{ print $NF }'`

if [[ $ModelIdentifier == *MacBook* ]]; then
	## echo "MacBook's action here!";

	## Battery ManufactureDate
	# year=2013		## Uncomment to TEST
	# month=12		## Uncomment to TEST
	# day=31			## Uncomment to TEST
	ManufactureDate=`ioreg -n AppleSmartBattery -r | grep "ManufactureDate" | awk '{ print $NF }'`
	# ManufactureDate=`echo "(($year - 1980)*512) + (($month*32)+ $day)" | bc`   ## Uncomment to TEST
	year=`echo "1980+(($ManufactureDate)/512)" | bc`
	YearNumber=`echo "(($year - 1980)*512)" | bc`

	month=1
	while [[ $month -le 12 ]]
	do
		day=`echo "(($ManufactureDate - $YearNumber)-($month*32))" | bc`
		if [ $day -ge 1 ] && [ $day -le 31 ]; then
			# echo "ManufactureDate: $year-$month-$day"
			ManufactureDate="$year-$month-$day"
		fi
		month=$(( month+1 ))	
	done
	manufacture_date="manufacture_date = $ManufactureDate"


	## Battery's original design capacity
	DesignCapacity=`ioreg -n AppleSmartBattery -r | grep "DesignCapacity" | awk '{ print $NF }'`
	design_capacity="design_capacity = $DesignCapacity"


	## Battery's current maximum capacity
	MaxCapacity=`ioreg -n AppleSmartBattery -r | grep "MaxCapacity" | awk '{ print $NF }'`
	max_capacity="max_capacity = $MaxCapacity"


	## Battery's maximum capacity as percentage of original capacity
	#MaxPercent=$(echo $MaxCapacity $DesignCapacity | awk '{printf ("%i", $1/$2 * 100)}')
	MaxPercent=`echo "scale=2; 100*$MaxCapacity/$DesignCapacity" | bc`
	MaxPercent=`echo $MaxPercent | awk '{print int($1+0.5)}'`
	max_percent="max_percent = $MaxPercent"


	## Battery's current capacity
	CurrentCapacity=`ioreg -n AppleSmartBattery -r | grep "CurrentCapacity" | awk '{ print $NF }'`
	current_capacity="current_capacity = $CurrentCapacity"


	## Percentage of current maximum capacity
	#CurrentPercent=$(echo $CurrentCapacity $MaxCapacity | awk '{printf ("%i", $1/$2 * 100)}')
	CurrentPercent=`echo "scale=1; 100*$CurrentCapacity/$MaxCapacity" | bc`
	CurrentPercent=`echo $CurrentPercent | awk '{print int($1+0.5)}'`
	current_percent="current_percent = $CurrentPercent"


	## Cycle count
	CycleCount=`ioreg -n AppleSmartBattery -r | grep '"CycleCount" ' | awk '{ print $NF }'`
	cycle_count="cycle_count = $CycleCount"


	## Battery Temperature
	## °C * 9/5 + 32 = °F
	Temperature=`ioreg -n AppleSmartBattery -r | grep "Temperature" | awk '{ print $NF }'`
	celsius=`echo "scale=1; $Temperature/100" | bc`
	fahrenheit=`echo "scale=1; (($Temperature/100)*9/5)+32" | bc`
	temperature="temperature = $celsius°C / $fahrenheit°F"


	## Battery Condition
	Condition=`system_profiler SPPowerDataType | grep 'Condition' | awk '{$1=""; print}'`  ## print all except first collumn
	condition="condition = $Condition"


	echo $manufacture_date '\n'$design_capacity '\n'$max_capacity '\n'$max_percent '\n'$current_capacity '\n'$current_percent '\n'$cycle_count '\n'$temperature '\n'$condition > "$powerfile"

fi
exit 0