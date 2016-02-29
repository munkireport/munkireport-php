<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Munkireport_model;
new munkiinfo_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3>Munki report <span id="total-count" class='label label-primary'>…</span></h3>
		  
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="listing.username" data-colname='reportdata.long_username'></th>
		        <th data-i18n="network.ip_address" data-colname='reportdata.remote_ip'></th>
			<th data-i18n="os.version" data-colname='machine.os_version'></th>
		        <th data-i18n="munki.version" data-colname='munkireport.version'></th>
			<th data-i18n="munki.munkiprotocol" data-colname='munkiinfo.munkiprotocol'></th>
		        <th data-i18n="last_seen" data-i18n="listing.munki.latest_run" data-sort="desc" data-colname='munkireport.timestamp'>Latest Run</th>
		        <th data-i18n="munki.run_type" data-colname='munkireport.runtype'></th>
		        <th data-i18n="error_plural" data-colname='munkireport.errors'></th>
		        <th data-i18n="warning_plural" data-colname='munkireport.warnings'></th>
		        <th data-i18n="listing.munki.pending_install_plural" data-colname='munkireport.pendinginstalls'></th>
		        <th data-i18n="listing.munki.package_installed_plural" data-colname='munkireport.installresults'></th>
		        <th data-i18n="listing.munki.package_removed_plural" data-colname='munkireport.removalresults'></th>
				<th data-i18n="manifest.name" data-colname='munkireport.manifestname'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="9" class="dataTables_empty"></td>
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

        // Get modifiers from data attribute
        var mySort = [], // Initial sort
            hideThese = [], // Hidden columns
            col = 0, // Column counter
            runtypes = [], // Array for runtype column 
            columnDefs = [{ visible: false, targets: hideThese }]; //Column Definitions

        $('.table th').map(function(){

            columnDefs.push({name: $(this).data('colname'), targets: col});

            if($(this).data('sort')){
              mySort.push([col, $(this).data('sort')])
            }

            if($(this).data('hide')){
              hideThese.push(col);
            }

            col++
        });

	    oTable = $('.table').dataTable( {
            ajax: {
                url: "<?=url('datatables/data')?>",
                type: "POST",
                data: function(d){
                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'munkireport.' + d.search.value){
                                d.columns[index].search.value = '> 0';
                            }
                        });

                    }
        		    // OS version
                    if(d.search.value.match(/^\d+\.\d+(\.(\d+)?)?$/)){
                        var search = d.search.value.split('.').map(function(x){return ('0'+x).slice(-2)}).join('');
                        d.search.value = search;
                    }
                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            order: mySort,
            columnDefs: columnDefs,
		    createdRow: function( nRow, aData, iDataIndex ) {
	        	// Update name in first column to link
	        	var name=$('td:eq(0)', nRow).html();
	        	if(name == ''){name = "No Name"};
	        	var sn=$('td:eq(1)', nRow).html();
                var link = get_client_detail_link(name, sn, appUrl + '/', '#tab_munki');
	        	$('td:eq(0)', nRow).html(link);

	        	// https is good
	        	var munkiprotocol=$('td:eq(6)', nRow).html();
	        	munkiprotocol = munkiprotocol == 'https' ? '<span class="label label-success">https</span>' : 
	        		(munkiprotocol)
	        	$('td:eq(6)', nRow).html(munkiprotocol)
	        	// Warn on http
	        	var munkiprotocol=$('td:eq(6)', nRow).html();
	        	munkiprotocol = munkiprotocol == 'http' ? '<span class="label label-danger">http</span>' : 
	        		(munkiprotocol)
	        	$('td:eq(6)', nRow).html(munkiprotocol)
	        	// Warn on http
	        	var munkiprotocol=$('td:eq(6)', nRow).html();
	        	munkiprotocol = munkiprotocol == 'localrepo' ? '<span class="label label-info">local repo</span>' : 
	        		(munkiprotocol)
	        	$('td:eq(6)', nRow).html(munkiprotocol)

	        	// Format date
	        	date = $('td:eq(7)', nRow).text();
	                $('td:eq(7)', nRow).html('never');
	        	if(date){
	              	$('td:eq(7)', nRow).html(moment(date).fromNow());
	        	}

                // Format OS Version
                var osvers = $('td:eq(4)', nRow).html();
                if( osvers > 0 && osvers.indexOf(".") == -1)
                {
                  osvers = osvers.match(/.{2}/g).map(function(x){return +x}).join('.')
                }
                $('td:eq(4)', nRow).html(osvers);

		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
