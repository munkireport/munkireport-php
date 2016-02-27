<?php //Initialize models needed for the table
$wifi_obj = new wifi_model($serial_number);
?>

    <h2 data-i18n="wifi.wifiinfo"></h2>

        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <td style="width:220px">SSID</td>
                    <td><?=$wifi_obj->ssid?></td>
                    <td data-i18n="wifi.rssilevel" style="width:220px"></td>
                    <td><?=$wifi_obj->agrctlrssi?></td>
                </tr>
                <tr>
                    <td>BSSID</td>
                    <td><?=$wifi_obj->bssid?></td>
                    <td data-i18n="listing.wifi.channel"></td>
                    <td><?=$wifi_obj->channel?></td>
                </tr>
                <tr>
                    <td data-i18n="wifi.lasttrx"></td>
                    <td><?=$wifi_obj->lasttxrate?></td>
                    <td data-i18n="wifi.noise"></td>
                    <td><?=$wifi_obj->agrctlnoise?></td>
                </tr>
                <tr>
                    <td data-i18n="wifi.maxtrx"></td>
                    <td><?=$wifi_obj->maxrate?></td>
                    <td data-i18n="wifi.apmode"></td>
                    <td><?=$wifi_obj->op_mode?></td>
                </tr>
                <tr>
                    <td data-i18n="wifi.xauthtype"></td>
                    <td><?=$wifi_obj->x802_11_auth?></td>
                    <td data-i18n="wifi.state"></td>
                    <td><?=$wifi_obj->state?></td>
                </tr>
                <tr>
                    <td data-i18n="wifi.wifiauthtype"></td>
                    <td><?=$wifi_obj->link_auth?></td>
                </tr>
            </tbody>
        </table>
