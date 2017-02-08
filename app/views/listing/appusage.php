<?php $this->view('partials/head'); ?>

<?php 
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Appusage_model;
?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
		  <h3><span data-i18n="listing.appusage.appusagereport"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		      	<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		      	<th data-i18n="listing.appusage.event" data-colname='appusage.event'></th>
		      	<th data-i18n="listing.appusage.appname" data-colname='appusage.app_name'></th>
			<th data-i18n="listing.appusage.lastevent" data-colname='appusage.last_time'></th>
		      	<th data-i18n="listing.appusage.count" data-colname='appusage.number_times'></th>
			<th data-i18n="version" data-colname='appusage.app_version'></th>      
			<th data-i18n="path" data-colname='appusage.app_path'></th>      
			<th data-i18n="bundle_id" data-colname='appusage.bundle_id'></th>
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
                url: appUrl + '/datatables/data',
                type: "POST",
                data: function(d){
                     d.mrColNotEmpty = "app_name";    
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_appusage-tab');
	        	$('td:eq(0)', nRow).html(link);
                
                // Get name link
                var appname=$('td:eq(3)', nRow).html();
                $('td:eq(3)', nRow).html(
                    $('<a>')
                        .attr('href', appUrl+'/module/inventory/items/'+appname)
                        .text(appname)
                )
                
                // Event type
                var autoreset=$('td:eq(2)', nRow).html();
                autoreset = autoreset == 'launch' ? i18n.t('listing.appusage.launch') :
                (autoreset === 'quit' ? i18n.t('listing.appusage.quit') : '')
                $('td:eq(2)', nRow).html(autoreset)
                
		    }
	    } );
	} );
</script>

<?php $this->view('partials/foot'); ?>
