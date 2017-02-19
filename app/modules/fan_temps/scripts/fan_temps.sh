#!/bin/bash

# Create cache dir if it does not exist
DIR=$(dirname $0)
mkdir -p "$DIR/cache"
fan_temps_file="$DIR/cache/fan_temps.xml"

# vvv New line variable vvv # no touchy
nl='
'
# ^^^ New line variable ^^^ # no touchy

# Gather temperature data
TEMPDATA=$(/usr/local/munki/smckit -tudm | perl -pe 's/\e([^\[\]]|\[.*?[a-zA-Z]|\].*?\a)//g' | sed -e 's/°C //' | cut -d "(" -f2)
FANLABLE=$(/usr/local/munki/smckit -f |  tr -d '\000' | perl -pe 's/\e([^\[\]]|\[.*?[a-zA-Z]|\].*?\a)//g' | tr -cd '[[:alnum:]]. .\n' | grep -e '\[id ' | sed -e 's/\[id /fanlable/g' -e 's/\]//g' | awk -F\, '{gsub(/[ \t]+$/, "", $1); print $1 ""}')
FANDATA="${TEMPDATA}${nl}${FANLABLE}${nl}$(/usr/local/munki/smckit -f |  tr -d '\000' | perl -pe 's/\e([^\[\]]|\[.*?[a-zA-Z]|\].*?\a)//g' | grep -e 'id 0\|Current:' | sed -e '1,/	Current:  /s/	Current:  /FAN_0 /;t' -e '1,/	Current:  /s/	Current:  /FAN_1 /;t' -e '1,/	Current:  /s/	Current:  /FAN_2 /;t' -e '1,/	Current:  /s/	Current:  /FAN_3 /;t' -e '1,/	Current:  /s/	Current:  /FAN_4 /;t' -e '1,/	Current:  /s/	Current:  /FAN_5 /;t' -e '1,/	Current:  /s/	Current:  /FAN_6 /;t' -e '1,/	Current:  /s/	Current:  /FAN_7 /;t' -e '1,/	Current:  /s/	Current:  /FAN_8 /;t' -e 's/ RPM//g' | awk -F' ' '{printf "%s %s\n", $1, $2}')"

# Delete the old file or plistbuddy will complain :/
rm "$fan_temps_file" 2>/dev/null

# Turn the FANDATA text mess into a pretty XML file for uploading
for TRANSLATE in 'FAN_0 ' 'FAN_1 ' 'FAN_2 ' 'FAN_3 ' 'FAN_4 ' 'FAN_5 ' 'FAN_6 ' 'FAN_7 ' 'FAN_8 ' 'fanlable0 ' 'fanlable1 ' 'fanlable2 ' 'fanlable3 ' 'fanlable4 ' 'fanlable5 ' 'fanlable6 ' 'fanlable7 ' 'fanlable8 ' 'Disc in ODD:      ' 'TA0P)  ' 'TA1P)  ' 'TC0D)  ' 'TC0P)  ' 'TC0H)  ' 'TB0T)  ' 'TB1T)  ' 'TB2T)  ' 'TB3T)  ' 'TG0D)  ' 'TG0P)  ' 'TG0H)  ' 'Th0H)  ' 'Th1H)  ' 'Th2H)  ' 'TM0P)  ' 'TM0S)  ' 'Ts0P)  ' 'TN0D)  ' 'TN0P)  ' 'TI0P)  ' 'TI1P)  ' 'TTF0)  ' 'TNFP)  ' 'TCFP)  ' 'TC0E)  ' 'TC1C)  ' 'TC2C)  ' 'TC3C)  ' 'TC4C)  ' 'TCGC)  ' 'TCSA)  ' 'TCXC)  ' 'TG1D)  ' 'TG1F)  ' 'TG1d)  ' 'TGTC)  ' 'TGTD)  ' 'TPCD)  ' 'Ts1S)  ' 'Tsqf)  ' 'TO0P)  ' 'TN0H)  ' 'Tm0P)  ' 'TL0P)  ' 'TC0F)  ' 'TA0p)  ' 'TC0C)  ' 'TG0p)  ' 'TH0O)  ' 'TH1O)  ' 'TL0V)  ' 'TL0p)  ' 'TL1V)  ' 'TL2V)  ' 'TLAV)  ' 'TLBV)  ' 'TLCV)  ' 'TO0p)  ' 'TS0V)  ' 'TS2V)  ' 'Tm0p)  ' 'Tp1P)  ' 'Tp2H)  ' 'Tp3H)  ' 'Tp3v)  ' 'Tp0P)  ' 'TH0P)  ' 'TA1p)  ' 'TC0G)  ' 'TC0J)  ' 'TC0c)  ' 'TC0d)  ' 'TC0p)  ' 'TC1c)  ' 'TC2c)  ' 'TC3c)  ' 'TCGc)  ' 'TCPG)  ' 'TCSC)  ' 'TCSc)  ' 'TCTD)  ' 'TCXc)  ' 'TCXr)  ' 'TH0A)  ' 'TH0B)  ' 'TH0C)  ' 'TH0F)  ' 'TH0X)  ' 'TH0a)  ' 'TH0b)  ' 'TH0c)  ' 'TH1A)  ' 'TH1B)  ' 'TH1C)  ' 'TH1F)  ' 'TH1X)  ' 'TH1a)  ' 'TH1b)  ' 'TH1c)  ' 'TI0p)  ' 'TI1p)  ' 'TM0p)  ' 'TMBS)  ' 'TP0p)  ' 'TW0P)  ' 'TW0p)  ' 'Tp0C)  ' 'Ts0G)  ' 'TBXT)  ' 'TH0R)  ' 'TH0V)  ' 'TH0x)  ' 'THSP)  ' 'TMLB)  ' 'Ts0S)  ' 'Ts1P)  ' 'Ts2S)  ' 'TCAC)  ' 'TCAD)  ' 'TCAG)  ' 'TCAH)  ' 'TCAS)  ' 'TCBC)  ' 'TCBD)  ' 'TCBG)  ' 'TCBH)  ' 'TCBS)  ' 'TH1P)  ' 'TH1V)  ' 'TH2F)  ' 'TH2P)  ' 'TH2V)  ' 'TH3F)  ' 'TH3P)  ' 'TH3V)  ' 'TH4F)  ' 'TH4P)  ' 'TH4V)  ' 'THPS)  ' 'THTG)  ' 'TM1P)  ' 'TM2P)  ' 'TM3P)  ' 'TM4P)  ' 'TM5P)  ' 'TM6P)  ' 'TM7P)  ' 'TM8P)  ' 'TMA1)  ' 'TMA2)  ' 'TMA3)  ' 'TMA4)  ' 'TMB1)  ' 'TMB2)  ' 'TMB3)  ' 'TMB4)  ' 'TMHS)  ' 'TMLS)  ' 'TMPS)  ' 'TMPV)  ' 'TMTG)  ' 'TNTG)  ' 'Te1F)  ' 'Te1P)  ' 'Te1S)  ' 'Te2F)  ' 'Te2S)  ' 'Te3F)  ' 'Te3S)  ' 'Te4F)  ' 'Te4S)  ' 'Te5S)  ' 'Te5F)  ' 'TeGG)  ' 'TeGP)  ' 'TeRG)  ' 'TeRP)  ' 'Tp1C)  ' 'TpPS)  ' 'TpTG)  ' 'TA0S)  ' 'TA1S)  ' 'TA2S)  ' 'TA3S)  ' 'Tb0P)  ' 'TC1D)  ' 'TC1E)  ' 'TC1F)  ' 'TC1H)  ' 'TC1P)  ' 'TC5C)  ' 'TC6C)  ' 'TC7C)  ' 'TC8C)  ' 'TCHP)  ' 'TG1H)  ' 'TM1S)  ' 'TM8S)  ' 'TM9P)  ' 'TM9S)  ' 'TN0C)  ' 'TN1P)  ' 'TP0D)  ' 'Tp2P)  ' 'Tp3P)  ' 'Tp4P)  ' 'Tp5P)  ' 'TS0C)  ' 'TL1p)  ' 'TGVP)  ' 'TaRC)  ' 'TaLC)  ' 'TTRD)  ' 'TTLD)  ' 'Th0N)  ' 'TS2P)  ' 'TG0T)  ' 'TC2P)  ' 'TC3P)  ' 'TG0C)  ' 'TA2p)  ' 'TG0r)  ' 'TG1p)  ' 'TG1r)  ' 'TM0r)  ' 'TM1r)  ' 'Te0t)  ' 'Tp0t)  ' 'TMAP)  ' 'TH1R)  '
do 
	OUTVALUE=$(grep -e "${TRANSLATE}" <<< "${FANDATA}" | sed -e "s/${TRANSLATE}//g" )
    OUTKEY=$(awk -F' ' '{printf "%s%s\n", $1, $2}' <<< "${TRANSLATE}" | sed -e 's/Unknown//g' | tr -cd '[[:alnum:]]' )
    /usr/libexec/plistbuddy -c "add :${OUTKEY} string ${OUTVALUE}" "$fan_temps_file" 1>/dev/null
done

exit 0