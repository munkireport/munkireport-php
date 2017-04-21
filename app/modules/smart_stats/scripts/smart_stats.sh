#!/bin/bash

if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

# vvv New line variable vvv # no touchy
nl='
'
# ^^^ New line variable ^^^ # no touchy

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"
smart_stats_file="$DIR/cache/smart_stats.plist"

# Set smartctl path
if [[ -f /usr/local/sbin/smartctl ]]; then
     SMARTCTL="/usr/local/sbin/smartctl"
elif [[ -f /usr/local/bin/smartctl ]]; then
     SMARTCTL="/usr/local/bin/smartctl"
else
     echo "Error: smartctl not found! Is smartmontools installed?"
     exit 1
fi

# Delete the old file or plistbuddy will complain :/
rm "$smart_stats_file" 2>/dev/null

DISKARRAY=0

# Get UNIX name of physical drives for processing with smartctl and process each drive
for DISK in $(diskutil list | grep -e '/dev/disk' | grep -v -e 'virtual' | grep -v -e 'disk image' | awk {'print $1'} | tr '\n' ' ' | sed -e 's/\/dev\/disk//g')
do

# If drive is not supported, exclude it from output
if [[ "$($SMARTCTL -i /dev/disk${DISK})" == *"Smartctl open device: /dev/disk"* ]] ; then
     continue
fi

SMARTATTRIBUTES=$(${SMARTCTL} -A /dev/disk${DISK} | tail -n +8 | awk '{print $2, $10}' | cut -d '/' -f-1 | sed -e 's/#//' -e 's/\\/-/g' -e 's/\///g' -e 's/UDMA_CRC_Error_Count/UDMA_Error_Count/g' -e s'/(//g' -e 's/)//g')
SMARTINFO=$($SMARTCTL -i /dev/disk${DISK} | tail -n +5 | sed -e '1,/SMART support is: / s/SMART support is: /SMART is: /g')
SMARTERRORS=$($SMARTCTL -l error /dev/disk${DISK} | grep -e 'occurred at disk power-on lifetime' | awk {'print $1"PoH- "$8'} | head -n1)
SMARTERRORCOUNT=$($SMARTCTL -l error /dev/disk${DISK} | grep -e 'ATA Error Count: ' | awk {'print "error_count "$4'})
SMARTHEALTH=$($SMARTCTL -H /dev/disk${DISK} | awk -F: '/SMART overall-health self-assessment test result/ {$1=""; print "Overall_Health: " $0}')
DISKPROPER=$(echo $DISK | tr -cd '[[:alnum:]]')

SMARTSTATS="${nl}${SMARTATTRIBUTES}${nl}${SMARTINFO}${nl}${SMARTERRORS}${nl}${SMARTERRORCOUNT}${nl}${SMARTHEALTH}${nl}Disk Number: ${DISKPROPER}"

# Make a dict for the drive in the PLIST file
/usr/libexec/plistbuddy -c "add :${DISKARRAY} dict" "${smart_stats_file}" 1>/dev/null

for TRANSLATE in 'Model Family:     ' 'Device Model:     ' 'Serial Number:    ' 'LU WWN Device Id: ' 'Firmware Version: ' 'User Capacity:    ' 'Sector Size:      ' 'Rotation Rate:    ' 'Form Factor:      ' 'Device is:        ' 'ATA Version is:   ' 'SATA Version is:  ' 'SMART is: ' 'SMART support is: ' 'ErrorPoH- ' 'error_count ' 'Disk Number: ' 'Raw_Read_Error_Rate ' 'Throughput_Performance ' 'Spin_Up_Time ' 'Start_Stop_Count ' 'Reallocated_Sector_Ct ' 'Read_Channel_Margin ' 'Seek_Error_Rate ' 'Seek_Time_Performance ' 'Power_On_Hours ' 'Spin_Retry_Count ' 'Calibration_Retry_Count ' 'Power_Cycle_Count ' 'Read_Soft_Error_Rate ' 'Program_Fail_Count_Chip ' 'Erase_Fail_Count_Chip ' 'Wear_Leveling_Count ' 'Used_Rsvd_Blk_Cnt_Chip ' 'Used_Rsvd_Blk_Cnt_Tot ' 'Unused_Rsvd_Blk_Cnt_Tot ' 'Program_Fail_Cnt_Total ' 'Erase_Fail_Count_Total ' 'Runtime_Bad_Block ' 'End-to-End_Error ' 'Reported_Uncorrect ' 'Command_Timeout ' 'High_Fly_Writes ' 'Airflow_Temperature_Cel ' 'G-Sense_Error_Rate ' 'Power-Off_Retract_Count ' 'Load_Cycle_Count ' 'Temperature_Celsius ' 'Hardware_ECC_Recovered ' 'Reallocated_Event_Count ' 'Current_Pending_Sector ' 'Offline_Uncorrectable ' 'UDMA_Error_Count ' 'Multi_Zone_Error_Rate ' 'Soft_Read_Error_Rate ' 'Data_Address_Mark_Errs ' 'Run_Out_Cancel ' 'Soft_ECC_Correction ' 'Thermal_Asperity_Rate ' 'Flying_Height ' 'Spin_High_Current ' 'Spin_Buzz ' 'Offline_Seek_Performnce ' 'Disk_Shift ' 'Loaded_Hours ' 'Power_On_Hours_and_Msec ' 'Load_Retry_Count ' 'Load_Friction ' 'Load-in_Time ' 'Torq-amp_Count ' 'Head_Amplitude ' 'Available_Reservd_Space ' 'Media_Wearout_Indicator ' 'Head_Flying_Hours ' 'Total_LBAs_Written ' 'Total_LBAs_Read ' 'Read_Error_Retry_Rate ' 'Host_Reads_MiB ' 'Host_Writes_MiB ' 'Grown_Failing_Block_Ct ' 'Unexpect_Power_Loss_Ct ' 'Non4k_Aligned_Access ' 'SATA_Iface_Downshift ' 'Factory_Bad_Block_Ct ' 'Percent_Lifetime_Used ' 'Write_Error_Rate ' 'Success_RAIN_Recov_Cnt ' 'Total_Host_Sector_Write ' 'Host_Program_Page_Count ' 'Bckgnd_Program_Page_Cnt ' 'Perc_Rated_Life_Used ' 'Reallocate_NAND_Blk_Cnt ' 'Ave_Block-Erase_Count ' 'Unused_Reserve_NAND_Blk ' 'SATA_Interfac_Downshift ' 'SSD_Life_Left ' 'Life_Curve_Status ' 'SuperCap_Health ' 'Lifetime_Writes_GiB ' 'Lifetime_Reads_GiB ' 'Uncorrectable_Error_Cnt ' 'ECC_Error_Rate ' 'CRC_Error_Count ' 'Supercap_Status ' 'Exception_Mode_Status ' 'POR_Recovery_Count ' 'Total_Reads_GiB ' 'Total_Writes_GiB ' 'Thermal_Throttle ' 'Perc_Write-Erase_Count ' 'Perc_Avail_Resrvd_Space ' 'Perc_Write-Erase_Ct_BC ' 'SATA_PHY_Error ' 'Avg_Write-Erase_Count ' 'Free_Fall_Sensor ' 'Overall_Health: '
do 

    OUTKEY=$(awk -F' ' '{printf "%s%s\n", $1, $2}' <<< "${TRANSLATE}" | tr -cd '[[:alnum:]]._' )
    OUTVALUE=$(grep -e "${TRANSLATE}" <<< "${SMARTSTATS}" | sed -e "s/${TRANSLATE}//g" | tr -d '\r' | tr -d '\n' )
    /usr/libexec/plistbuddy -c "add ${DISKARRAY}:${OUTKEY} string ${OUTVALUE}" "${smart_stats_file}" 1>/dev/null

# Close TRANSLATE for
done

# Increment DISKARRAY
((DISKARRAY++))

# Close DISK for
done

exit 0
