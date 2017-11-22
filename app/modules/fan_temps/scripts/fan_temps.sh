#!/bin/bash
# Created by tuxudo for MunkiReport-PHP

# Skip manual check
if [ "$1" = 'manualcheck' ]; then
	echo 'Manual check: skipping'
	exit 0
fi

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"
fan_temps_file="$DIR/cache/fan_temps.plist"

# vvv New line variable vvv # no touchy
nl='
'
# ^^^ New line variable ^^^ # no touchy

MACHINE_MODEL=$(/usr/sbin/sysctl hw.model)

# Check for Xserve, they don't report fan data correctly
if [[ "${MACHINE_MODEL}" == *"Xserve"* ]] ; then
# Gather fan and temperature data
FANDATA=$(/usr/local/munki/smckit -tudm |  tr -d '\000' | perl -pe 's/\e([^\[\]]|\[.*?[a-zA-Z]|\].*?\a)//g' | sed  -e 's/°C //' -e 's/Disc in ODD:/DiscIn/g' -e 's/)//' | cut -d "(" -f2 | awk -F' ' '{printf "%s %s\n", $1, $2" "$3" "$4}')

# If not an Xserve
else
# Gather fan and temperature data
FANDATA=$(/usr/local/munki/smckit -ftudm |  tr -d '\000' | perl -pe 's/\e([^\[\]]|\[.*?[a-zA-Z]|\].*?\a)//g' | sed -e '1,/	Current:  /s/	Current:  /FAN_0_Current /;t' -e '1,/	Min:  /s/	Min:  /FAN_0_Min /;t' -e '1,/	Max:  /s/	Max:  /FAN_0_Max /;t' -e '1,/	Current:  /s/	Current:  /FAN_1_Current /;t' -e '1,/	Min:  /s/	Min:  /FAN_1_Min /;t' -e '1,/	Max:  /s/	Max:  /FAN_1_Max /;t' -e '1,/	Current:  /s/	Current:  /FAN_2_Current /;t' -e '1,/	Min:  /s/	Min:  /FAN_2_Min /;t' -e '1,/	Max:  /s/	Max:  /FAN_2_Max /;t' -e '1,/	Current:  /s/	Current:  /FAN_3_Current /;t' -e '1,/	Min:  /s/	Min:  /FAN_3_Min /;t' -e '1,/	Max:  /s/	Max:  /FAN_3_Max /;t' -e '1,/	Current:  /s/	Current:  /FAN_4_Current /;t' -e '1,/	Min:  /s/	Min:  /FAN_4_Min /;t' -e '1,/	Max:  /s/	Max:  /FAN_4_Max /;t' -e '1,/	Current:  /s/	Current:  /FAN_5_Current /;t' -e '1,/	Min:  /s/	Min:  /FAN_5_Min /;t' -e '1,/	Max:  /s/	Max:  /FAN_5_Max /;t' -e '1,/	Current:  /s/	Current:  /FAN_6_Current /;t' -e '1,/	Min:  /s/	Min:  /FAN_6_Min /;t' -e '1,/	Max:  /s/	Max:  /FAN_6_Max /;t' -e '1,/	Current:  /s/	Current:  /FAN_7_Current /;t' -e '1,/	Min:  /s/	Min:  /FAN_7_Min /;t' -e '1,/	Max:  /s/	Max:  /FAN_7_Max /;t' -e '1,/	Current:  /s/	Current:  /FAN_8_Current /;t' -e '1,/	Min:  /s/	Min:  /FAN_8_Min /;t' -e '1,/	Max:  /s/	Max:  /FAN_8_Max /;t' -e 's/\[id 0\]/FAN_0_Label/g' -e 's/\[id 1\]/FAN_1_Label/g' -e 's/\[id 2\]/FAN_2_Label/g' -e 's/\[id 3\]/FAN_3_Label/g' -e 's/\[id 4\]/FAN_4_Label/g' -e 's/\[id 5\]/FAN_5_Label/g' -e 's/\[id 6\]/FAN_6_Label/g' -e 's/\[id 7\]/FAN_7_Label/g' -e 's/\[id 8\]/FAN_8_Label/g' -e 's/°C //' | sed -e 's/ RPM//g' -e 's/Disc in ODD:/DiscIn/g' -e 's/)//' | cut -d "(" -f2 | awk -F' ' '{printf "%s %s\n", $1, $2" "$3" "$4}')

fi

# Delete the old file or plistbuddy will complain :/
rm "$fan_temps_file" 2>/dev/null

# Turn the FANDATA text mess into a pretty PLIST file for uploading
for TRANSLATE in 'FAN_0_Label ' 'FAN_1_Label ' 'FAN_2_Label ' 'FAN_3_Label ' 'FAN_4_Label ' 'FAN_5_Label ' 'FAN_6_Label ' 'FAN_7_Label ' 'FAN_8_Label ' 'FAN_0_Current ' 'FAN_1_Current ' 'FAN_2_Current ' 'FAN_3_Current ' 'FAN_4_Current ' 'FAN_5_Current ' 'FAN_6_Current ' 'FAN_7_Current ' 'FAN_8_Current ' 'FAN_0_Min ' 'FAN_1_Min ' 'FAN_2_Min ' 'FAN_3_Min ' 'FAN_4_Min ' 'FAN_5_Min ' 'FAN_6_Min ' 'FAN_7_Min ' 'FAN_8_Min ' 'FAN_0_Max ' 'FAN_1_Max ' 'FAN_2_Max ' 'FAN_3_Max ' 'FAN_4_Max ' 'FAN_5_Max ' 'FAN_6_Max ' 'FAN_7_Max ' 'FAN_8_Max ' 'DiscIn ' 'TA0P ' 'TA1P ' 'TC0D ' 'TC0P ' 'TC0H ' 'TB0T ' 'TB1T ' 'TB2T ' 'TB3T ' 'TG0D ' 'TG0P ' 'TG0H ' 'Th0H ' 'Th1H ' 'Th2H ' 'TM0P ' 'TM0S ' 'Ts0P ' 'TN0D ' 'TN0P ' 'TI0P ' 'TI1P ' 'TTF0 ' 'TNFP ' 'TCFP ' 'TC0E ' 'TC1C ' 'TC2C ' 'TC3C ' 'TC4C ' 'TCGC ' 'TCSA ' 'TCXC ' 'TG1D ' 'TG1F ' 'TG1d ' 'TGTC ' 'TGTD ' 'TPCD ' 'Ts1S ' 'Tsqf ' 'TO0P ' 'TN0H ' 'Tm0P ' 'TL0P ' 'TC0F ' 'TA0p ' 'TC0C ' 'TG0p ' 'TH0O ' 'TH1O ' 'TL0V ' 'TL0p ' 'TL1V ' 'TL2V ' 'TLAV ' 'TLBV ' 'TLCV ' 'TO0p ' 'TS0V ' 'TS2V ' 'Tm0p ' 'Tp1P ' 'Tp2H ' 'Tp3H ' 'Tp3v ' 'Tp0P ' 'TH0P ' 'TA1p ' 'TC0G ' 'TC0J ' 'TC0c ' 'TC0d ' 'TC0p ' 'TC1c ' 'TC2c ' 'TC3c ' 'TCGc ' 'TCPG ' 'TCSC ' 'TCSc ' 'TCTD ' 'TCXc ' 'TCXr ' 'TH0A ' 'TH0B ' 'TH0C ' 'TH0F ' 'TH0X ' 'TH0a ' 'TH0b ' 'TH0c ' 'TH1A ' 'TH1B ' 'TH1C ' 'TH1F ' 'TH1X ' 'TH1a ' 'TH1b ' 'TH1c ' 'TI0p ' 'TI1p ' 'TM0p ' 'TMBS ' 'TP0p ' 'TW0P ' 'TW0p ' 'Tp0C ' 'Ts0G ' 'TBXT ' 'TH0R ' 'TH0V ' 'TH0x ' 'THSP ' 'TMLB ' 'Ts0S ' 'Ts1P ' 'Ts2S ' 'TCAC ' 'TCAD ' 'TCAG ' 'TCAH ' 'TCAS ' 'TCBC ' 'TCBD ' 'TCBG ' 'TCBH ' 'TCBS ' 'TH1P ' 'TH1V ' 'TH2F ' 'TH2P ' 'TH2V ' 'TH3F ' 'TH3P ' 'TH3V ' 'TH4F ' 'TH4P ' 'TH4V ' 'THPS ' 'THTG ' 'TM1P ' 'TM2P ' 'TM3P ' 'TM4P ' 'TM5P ' 'TM6P ' 'TM7P ' 'TM8P ' 'TMA1 ' 'TMA2 ' 'TMA3 ' 'TMA4 ' 'TMB1 ' 'TMB2 ' 'TMB3 ' 'TMB4 ' 'TMHS ' 'TMLS ' 'TMPS ' 'TMPV ' 'TMTG ' 'TNTG ' 'Te1F ' 'Te1P ' 'Te1S ' 'Te2F ' 'Te2S ' 'Te3F ' 'Te3S ' 'Te4F ' 'Te4S ' 'Te5S ' 'Te5F ' 'TeGG ' 'TeGP ' 'TeRG ' 'TeRP ' 'Tp1C ' 'TpPS ' 'TpTG ' 'TA0S ' 'TA1S ' 'TA2S ' 'TA3S ' 'Tb0P ' 'TC1D ' 'TC1E ' 'TC1F ' 'TC1H ' 'TC1P ' 'TC5C ' 'TC6C ' 'TC7C ' 'TC8C ' 'TCHP ' 'TG1H ' 'TM1S ' 'TM8S ' 'TM9P ' 'TM9S ' 'TN0C ' 'TN1P ' 'TP0D ' 'Tp2P ' 'Tp3P ' 'Tp4P ' 'Tp5P ' 'TS0C ' 'TL1p ' 'TGVP ' 'TaRC ' 'TaLC ' 'TTRD ' 'TTLD ' 'Th0N ' 'TS2P ' 'TG0T ' 'TC2P ' 'TC3P ' 'TG0C ' 'TA2p ' 'TG0r ' 'TG1p ' 'TG1r ' 'TM0r ' 'TM1r ' 'Te0t ' 'Tp0t ' 'TMAP ' 'TH1R ' 'TA0G ' 'TA2G ' 'TA4S ' 'TA5S ' 'TC2D ' 'TG0G ' 'TH1G ' 'TH2G ' 'TH3G ' 'TM2S ' 'TM3S ' 'TM4S ' 'TM5S ' 'TM6S ' 'TM7S ' 'TMAS ' 'TMCS ' 'Tp0G ' 'Tp1G ' 'Tp2G '
do 
	OUTVALUE=$(grep -e "${TRANSLATE}" <<< "${FANDATA}" | sed -e "s/${TRANSLATE}//g" -e 's/[[:space:]]*$//')
    OUTKEY=$(awk -F' ' '{printf "%s%s\n", $1, $2}' <<< "${TRANSLATE}" | tr -cd '[[:alnum:]]._' )
    /usr/libexec/PlistBuddy -c "add :${OUTKEY} string ${OUTVALUE}" "$fan_temps_file" 1>/dev/null
done

exit 0
