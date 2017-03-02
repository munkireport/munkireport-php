<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Location_model;
?>

<div class="container">

  <div class="row">

    <div class="col-lg-12">

      <h3><span data-i18n="location.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>
      
      <table class="table table-striped table-condensed table-bordered">
        <thead>
          <tr>
            <th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
            <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
            <th data-i18n="location.currentstatus" data-colname='location.CurrentStatus'></th>
            <th data-i18n="location.lastrun" data-colname='location.LastRun'></th>
            <th data-i18n="location.stalelocation" data-colname='location.StaleLocation'></th>
            <th data-i18n="location.address" data-colname='location.Address'></th>
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
                     d.mrColNotEmpty = "lastrun";
                    
                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'location.' + d.search.value){
                                d.columns[index].search.value = '> 0';
                            }
                        });

                    }
        		    // IDK what this does
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
                var link = mr.getClientDetailLink(name, sn, '#tab_location-tab');
	        	$('td:eq(0)', nRow).html(link);
                
	        	//Format Last Run Date timestamp
	        	var checkin = $('td:eq(3)', nRow).html();
	        	if(checkin !== "" ){
	        	$('td:eq(3)', nRow).html('<span title="' + checkin.replace(' +0000', 'GMT') + '">' + moment(checkin.replace(' +0000', 'Z')).fromNow()+'</span>');
	        	}
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>