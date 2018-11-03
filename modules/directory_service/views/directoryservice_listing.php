<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Directory_service_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="directory_service.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="username" data-colname='reportdata.long_username'></th>
		        <th data-i18n="directory_service.directory_service" data-colname='directoryservice.which_directory_service'></th>
		        <th data-i18n="directory_service.ad_domain" data-colname='directoryservice.addomain'></th>
		        <th data-i18n="directory_service.computeraccount" data-colname='directoryservice.computeraccount'></th>
		        <th data-i18n="directory_service.ad_comments" data-colname='directoryservice.directory_service_comments'></th>
		        <th data-i18n="directory_service.mobileaccount" data-colname='directoryservice.createmobileaccount'></th>
		        <th data-i18n="directory_service.networkprotocol" data-colname='directoryservice.networkprotocoltobeused'></th>
		        <th data-i18n="directory_service.admin_groups" data-colname='directoryservice.allowedadmingroups'></th>
		        <th data-i18n="directory_service.bound" data-colname='directoryservice.bound'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="10" class="dataTables_empty"></td>
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
            columnDefs = [{ visible: false, targets: hideThese }]; //Column Definitions

        $('.table th').map(function(){

            columnDefs.push({name: $(this).data('colname'), targets: col});

            if($(this).data('sort'))
            {
              mySort.push([col, $(this).data('sort')])
            }

            if($(this).data('hide'))
            {
              hideThese.push(col);
            }

            col++
        });

	    oTable = $('.table').dataTable( {
            ajax: {
                url: appUrl + '/datatables/data',
                type: "POST"
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_directory-tab');
	        	$('td:eq(0)', nRow).html(link);

	        	// Translate bool. todo function for any bool we find
	        	var status=$('td:eq(7)', nRow).html();
	        	status = status == 1 ? i18n.t('yes') :
	        	(status === '0' ? i18n.t('no') : '')
	        	$('td:eq(7)', nRow).html(status)
		    }
	    } );
	} );
</script>

<?php $this->view('partials/foot'); ?>
