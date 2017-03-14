<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new wifi_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="wifi.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		      	<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		      	<th data-colname='wifi.ssid'>SSID</th>
		      	<th data-colname='wifi.bssid'>BSSID</th>
		      	<th data-i18n="wifi.listing.state" data-colname='wifi.state'></th>
		      	<th data-i18n="wifi.listing.lasttx" data-colname='wifi.lasttxrate'></th>
		      	<th data-i18n="wifi.listing.maxtx" data-colname='wifi.maxrate'></th>
		      	<th data-i18n="wifi.listing.channel" data-colname='wifi.channel'></th>
		      	<th data-colname='wifi.id'>SNR</th>
		      	<th data-colname='wifi.agrctlrssi'>RSSI</th>
		      	<th data-i18n="wifi.listing.noise" data-colname='wifi.agrctlnoise'></th>
		      	<th data-i18n="wifi.listing.authtype" data-colname='wifi.x802_11_auth'></th>
		      	<th data-i18n="wifi.listing.linkauthtype" data-colname='wifi.link_auth'></th>
		      	<th data-i18n="wifi.listing.apmode" data-colname='wifi.op_mode'></th>
		      	<th data-colname='wifi.mcs'>MCS</th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="15" class="dataTables_empty"></td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<script type="text/javascript">

	$(document).on('appUpdate', function(e){

		var oTable = $('.table').DataTable();
		oTable.ajax.reload();
		return;

	});

	$(document).on('appReady', function(e, lang) {
		// Get column names from data attribute
		var columnDefs = [],
            col = 0; // Column counter
		$('.table th').map(function(){
              columnDefs.push({name: $(this).data('colname'), targets: col});
              col++;
		});
	    oTable = $('.table').dataTable( {
	        columnDefs: columnDefs,
	        ajax: {
                url: appUrl + '/datatables/data',
                type: "POST"
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
	        createdRow: function( nRow, aData, iDataIndex ) {
	        	// Update name in first column to link
	        	var name=$('td:eq(0)', nRow).html();
	        	if(name == ''){name = "No Name"};
	        	var sn=$('td:eq(1)', nRow).html();
	        	var link = mr.getClientDetailLink(name, sn, '#tab_wifi-tab');
	        	$('td:eq(0)', nRow).html(link);
                
                // Format Last Tx
	        	var lastTx=$('td:eq(5)', nRow).html();
	        	$('td:eq(5)', nRow).html('<span title="'+(lastTx*0.125)+' MB/sec">'+lastTx+" Mbps</span>");
                
                // Format Max Tx
	        	var maxTx=$('td:eq(6)', nRow).html();
	        	$('td:eq(6)', nRow).html('<span title="'+(maxTx*0.125)+' MB/sec">'+maxTx+" Mbps</span>");
                
	        	// Calculate signal to noise ratio
	        	var rssi=$('td:eq(9)', nRow).html();
	        	var noise=$('td:eq(10)', nRow).html();
	        	$('td:eq(8)', nRow).html('<span title="'+i18n.t('wifi.snr_detail')+'">'+(rssi-noise)+' db</span>');
                                
                // Format RSSI
	        	var rssi=$('td:eq(9)', nRow).html();
	        	$('td:eq(9)', nRow).html('<span title="'+i18n.t('wifi.rssi_detail')+'">'+rssi+' db</span>');
                                
                // Format Noise
	        	var noise=$('td:eq(10)', nRow).html();
	        	$('td:eq(10)', nRow).html('<span title="'+i18n.t('wifi.noise_detail')+'">'+noise+' db</span>');
                
	        	// Format 802.1x mode
	        	var eightmode=$('td:eq(11)', nRow).html();
	        	eightmode = eightmode == 'open' ? i18n.t('wifi.open') :
	        		(eightmode)
	        	$('td:eq(11)', nRow).html(eightmode)
                
	        	// Format Link Auth
	        	var linkauth=$('td:eq(12)', nRow).html();
	        	linkauth = linkauth == 'none' ? i18n.t('wifi.none') :
	        	linkauth = linkauth == '802.1x' ? i18n.t('wifi.802.1x') :
	        	linkauth = linkauth == 'leap' ? i18n.t('wifi.leap') :
	        	linkauth = linkauth == 'wps' ? i18n.t('wifi.wps') :
	        	linkauth = linkauth == 'wep' ? i18n.t('wifi.wep') :
	        	linkauth = linkauth == 'wpa' ? i18n.t('wifi.wpa') :
	        	linkauth = linkauth == 'wpa-psk' ? i18n.t('wifi.wpa_psk') :
	        	linkauth = linkauth == 'wpa2-psk' ? i18n.t('wifi.wpa2_psk') :
	        	(linkauth === 'wpa2' ? i18n.t('wifi.wpa2') : linkauth)
	        	$('td:eq(12)', nRow).html(linkauth)
                
	        	// Format AP Mode
	        	var apmode=$('td:eq(13)', nRow).html();
	        	apmode = apmode == 'station ' ? i18n.t('wifi.station') :
	        		(apmode)
	        	$('td:eq(13)', nRow).html(apmode)
                                
	        	// Blank row if no wifi
	        	var wifistate=$('td:eq(4)', nRow).html();
                if ( wifistate == 'no wifi' || wifistate == 'off') {
                    $('td:eq(5)', nRow).html("")
                    $('td:eq(6)', nRow).html("")
                    $('td:eq(7)', nRow).html("")
                    $('td:eq(8)', nRow).html("")
                    $('td:eq(9)', nRow).html("")
                    $('td:eq(10)', nRow).html("")
                    $('td:eq(11)', nRow).html("")
                    $('td:eq(12)', nRow).html("")
                    $('td:eq(13)', nRow).html("")
                    $('td:eq(14)', nRow).html("")
                }
                
	        	// Format wifi state
	        	var wifistate=$('td:eq(4)', nRow).html();
	        	wifistate = wifistate == 'running' ? i18n.t('wifi.running') :
	        	wifistate = wifistate == 'off' ? i18n.t('wifi.off') :
	        	wifistate = wifistate == 'no wifi' ? i18n.t('wifi.no_wifi') :
	        	wifistate = wifistate == 'init' ? i18n.t('wifi.init') :
	        	wifistate = wifistate == 'sharing' ? i18n.t('wifi.sharing') :
	        	(wifistate === 'unknown' ? i18n.t('wifi.unknown') : wifistate)
	        	$('td:eq(4)', nRow).html(wifistate)

	        }
	    });
	});
</script>

<?php $this->view('partials/foot'); ?>
