Power Module
==============

Reports on Power and Battery information 

- Power Supply information (Energy Saving settings)
- Sleep / Wake settings
- Other Power related information from pmset
- Battery information
Note: Battery condition can fluctuate between "Service Battery" and "Normal".

Data can be viewed at
- Power under Listings menu 
- Power tab on the client details page
- Power widgets under Reports menu
- Batteries under Listing menu
- Battery tab on the client details page

Power module
==============

* manufacture_date - VARCHAR(255) - Date of battery's manufacture
* design_capacity - INT(11) - Design capacity of battery
* max_capacity - INT(11) - Maximum capacity of battery
* max_percent - INT(11) - Maximum percent of battery
* current_capacity - INT(11) - Current capacity of battery
* current_percent - INT(11) - Current percent of battery
* cycle_count - INT(11) - Amount of battery cycles
* temperature - INT(11) - Temperature of battery
* condition - VARCHAR(255) - Current battery condition
* sleep_prevented_by - TEXT - List of processes preventing sleep
* hibernatefile - VARCHAR(255) - Path of hibernate file
* schedule - VARCHAR(255) - Scheduled events
* adapter_id - VARCHAR(255) - ID of power adapter
* family_code - VARCHAR(255) - Family code of adapter
* adapter_serial_number - VARCHAR(255) - Serial number of adapter
* combined_sys_load - VARCHAR(255) - Combined system load status
* user_sys_load - VARCHAR(255) - User system load status
* thermal_level - VARCHAR(255) - Therm level status
* battery_level - VARCHAR(255) - Battery level status
* ups_name - VARCHAR(255) - Name of UPS
* active_profile - VARCHAR(255) - Currently active power profile
* ups_charging_status - VARCHAR(255) - Charging status of UPS
* externalconnected - VARCHAR(255) - Is power adapter connected
* cellvoltage - VARCHAR(255) - Voltage levels of battery cells
* manufacturer - VARCHAR(255) - Manufacturer of battery
* batteryserialnumber - VARCHAR(255) - Serial number of battery
* fullycharged - VARCHAR(255) - Is battery fully charged
* ischarging - VARCHAR(255) - Is battery charging
* standbydelay - INT(11) - Seconds until standby
* standby - INT(11) - Standby enabled
* womp - INT(11) - Wake-on-Magic-Packet enabled
* halfdim - INT(11) - Dim display on this power source
* gpuswitch - INT(11) - Automatic GPU switching enabled
* sms - INT(11) - Sudden motion sensor enabled
* networkoversleep - INT(11) - Network over sleep enabled
* disksleep - INT(11) - Minutes until disk sleeps
* sleep - INT(11) - Minutes until system sleeps
* autopoweroffdelay - INT(11) - Minutes until auto power off
* hibernatemode - INT(11) - Hibernate mode
* autopoweroff - INT(11) - Is auto power off enabled
* ttyskeepawake - INT(11) - Keep awake when remote TTY session is active
* displaysleep - INT(11) - Minutes until display sleeps
* acwake - INT(11) - Wake when connected/disconnected from AC
* lidwake - INT(11) - Wake when lid is opened
* sleep_on_power_button - INT(11) - Sleep if power button is pressed
* autorestart - INT(11) - Automatically restart on kernel panic
* destroyfvkeyonstandby - INT(11) - Destroy FileVault keys on standby
* powernap - INT(11) - Power Nap enabled
* haltlevel - INT(11) - Shut down/sleep when UPS reaches this percent
* haltafter - INT(11) - Shut down/sleep after running on UPS for this long
* haltremain - INT(11) - Shut down/sleep when this many minutes remain on UPS
* lessbright - INT(11) - Dim display on this power source, legacy
* sleep_count - INT(11) - Amount of times machine has slept since boot
* dark_wake_count - INT(11) - Amount of dark wakes since boot
* user_wake_count - INT(11) - Amount of time machine has woken up since boot
* wattage - INT(11) - Wattage of power adapter
* backgroundtask - INT(11) - Is a background task preventing sleep
* applepushservicetask - INT(11) - Is an Apple push service task preventing sleep
* userisactive - INT(11) - Is an active user preventing sleep
* preventuseridledisplaysleep - INT(11) - Keep display awake when user is idle
* preventsystemsleep - INT(11) - Keep system awake
* externalmedia - INT(11) - Is extneral media keeping machine awake
* preventuseridlesystemsleep - INT(11) - Keep system awake when user is idle
* networkclientactive - INT(11) - Is a network client keeping machine awake
* cpu_scheduler_limit - INT(11) - CPU scheduler throttle limit
* cpu_available_cpus - INT(11) - How many CPUs are available for tasks
* cpu_speed_limit - INT(11) - CPU thermal throttle limit
* ups_percent - INT(11) - Percent of UPS power
* timeremaining - INT(11) - Time remaining until fully charged or fully discharged
* instanttimetoempty - INT(11) - Estimated instant time to empty battery
* voltage - FLOAT - Batter voltage level
* permanentfailurestatus - INT(11) - Has battery permanently failed
* packreserve - INT(11) - mAh of battery reserve power
* avgtimetofull - INT(11) - Estimated time until full battery
* amperage - FLOAT - Amperage of battery
* designcyclecount - INT(11) - Design cycle count of battery
* avgtimetoempty - INT(11) - Estimated time until empty battery