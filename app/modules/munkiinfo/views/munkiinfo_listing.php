<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Munkiinfo_model;
?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
		  <h3><span data-i18n="munkiinfo.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>
		  
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="username" data-colname='reportdata.long_username'></th>
		        <th data-i18n="munkiinfo.key" data-colname='munkiinfo.munkiinfo_key'></th>
		        <th data-i18n="munkiinfo.value" data-colname='munkiinfo.munkiinfo_value'></th>
                <th data-i18n="last_seen" data-sort="desc" data-colname='reportdata.timestamp'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="6" class="dataTables_empty"></td>
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
                    
                    // Look for a bigger/smaller/equal statement
                    if(d.search.value.match(/^protocol = .+$/))
                    {
                        // Add column specific search
                        d.columns[3].search.value = d.search.value.replace(/.*= (.+)$/, '$1');
                        // Clear global search
                        d.search.value = '';
                        console.log(d.columns[3].search.value)
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
                var link = mr.getClientDetailLink(name, sn, '#tab_munki');
                $('td:eq(0)', nRow).html(link);

                // Format date
                var checkin = parseInt($('td:eq(5)', nRow).html());
                var date = new Date(checkin * 1000);
                $('td:eq(5)', nRow).html(moment(date).fromNow());
            }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
