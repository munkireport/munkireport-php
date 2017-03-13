<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Caching_model;
?>

<div class="container">
  <div class="row">
	<div class="col-lg-12">

	  <h3><span data-i18n="caching.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
			<th data-i18n="caching.date" data-colname='caching.collectiondateepoch'></th>
			<th data-i18n="caching.bytesfromcachetoclients" data-colname='caching.bytesfromcachetoclients'></th>
			<th data-i18n="caching.bytesfrompeerstoclients" data-colname='caching.bytesfrompeerstoclients'></th>
			<th data-i18n="caching.bytesfromorigintoclients" data-colname='caching.bytesfromorigintoclients'></th>
			<th data-i18n="caching.requestsfrompeers" data-colname='caching.requestsfrompeers'></th>
			<th data-i18n="caching.requestsfromclients" data-colname='caching.requestsfromclients'></th>
			<th data-i18n="caching.purged_1" data-colname='caching.bytespurgedyoungerthan1day'></th>
			<th data-i18n="caching.purged_7" data-colname='caching.bytespurgedyoungerthan7days'></th>
			<th data-i18n="caching.purged_30" data-colname='caching.bytespurgedyoungerthan30days'></th>
			<th data-i18n="caching.total_purged" data-colname='caching.bytespurgedtotal'></th>
		  </tr>
		</thead>

		<tbody>
		  <tr>
			<td data-i18n="listing.loading" colspan="12" class="dataTables_empty"></td>
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
                     d.mrColNotEmpty = "bytesfromcachetoclients";

                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'caching.' + d.search.value){
                                d.columns[index].search.value = '> 0';
                            }
                        });

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
                var link = mr.getClientDetailLink(name, sn, '#tab_caching-tab');
	        	$('td:eq(0)', nRow).html(link);

	        	// Format date
	        	var event = parseInt($('td:eq(2)', nRow).html());
	        	var date = new Date(event * 1000);
	        	$('td:eq(2)', nRow).html('<span title="'+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span>');

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(3)', nRow).html());
	        	$('td:eq(3)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(4)', nRow).html());
	        	$('td:eq(4)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(5)', nRow).html());
	        	$('td:eq(5)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(8)', nRow).html());
	        	$('td:eq(8)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(9)', nRow).html());
	        	$('td:eq(9)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(10)', nRow).html());
	        	$('td:eq(10)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(11)', nRow).html());
	        	$('td:eq(11)', nRow).html(fileSize(checkin, 2));
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
