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
			<th data-i18n="caching.activated" data-colname='caching.activated'></th>
			<th data-i18n="caching.active" data-colname='caching.active'></th>
			<th data-i18n="caching.cachestatus" data-colname='caching.cachestatus'></th>
			<th data-i18n="caching.reachability" data-colname='caching.reachability'></th>
			<th data-i18n="caching.cachefree" data-colname='caching.cachefree'></th>
			<th data-i18n="caching.cacheused" data-colname='caching.cacheused'></th>
			<th data-i18n="caching.cachelimit" data-colname='caching.cachelimit'></th>
			<th data-i18n="caching.startupstatus" data-colname='caching.startupstatus'></th>
			<th data-i18n="caching.registrationstatus" data-colname='caching.registrationstatus'></th>
			<th data-i18n="caching.publicaddress" data-colname='caching.publicaddress'></th>
			<th data-i18n="caching.privateaddresses" data-colname='caching.privateaddresses'></th>
			<th data-i18n="caching.macsoftware" data-colname='caching.macsoftware'></th>
			<th data-i18n="caching.appletvsoftware" data-colname='caching.appletvsoftware'></th>
			<th data-i18n="caching.iossoftware" data-colname='caching.iossoftware'></th>
			<th data-i18n="caching.iclouddata" data-colname='caching.iclouddata'></th>
			<th data-i18n="caching.booksdata" data-colname='caching.booksdata'></th>
			<th data-i18n="caching.itunesudata" data-colname='caching.itunesudata'></th>
			<th data-i18n="caching.moviesdata" data-colname='caching.moviesdata'></th>
			<th data-i18n="caching.musicdata" data-colname='caching.musicdata'></th>
			<th data-i18n="caching.otherdata" data-colname='caching.otherdata'></th>
			<th data-i18n="caching.serverguid" data-colname='caching.serverguid'></th>
			<th data-i18n="caching.totalbytesreturnedtoclients" data-colname='caching.totalbytesreturnedtoclients'></th>
			<th data-i18n="caching.totalbytesreturnedtopeers" data-colname='caching.totalbytesreturnedtopeers'></th>
			<th data-i18n="caching.totalbytesstoredfromorigin" data-colname='caching.totalbytesstoredfromorigin'></th>
			<th data-i18n="caching.totalbytesstoredfrompeers" data-colname='caching.totalbytesstoredfrompeers'></th>
			<th data-i18n="caching.totalbytesdropped" data-colname='caching.totalbytesdropped'></th>
			<th data-i18n="caching.totalbytesimported" data-colname='caching.totalbytesimported'></th>
		  </tr>
		</thead>

		<tbody>
		  <tr>
			<td data-i18n="listing.loading" colspan="29" class="dataTables_empty"></td>
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
                     d.mrColNotEmptyBlank = "startupstatus";

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
                
	        	// activated
	        	var pizza=$('td:eq(2)', nRow).html();
	        	pizza = pizza == '1' ? i18n.t('yes') :
	        	(pizza === '0' ? i18n.t('no') : '')
	        	$('td:eq(2)', nRow).html(pizza)
                
	        	// active
	        	var pizza=$('td:eq(3)', nRow).html();
	        	pizza = pizza == '1' ? i18n.t('yes') :
	        	(pizza === '0' ? i18n.t('no') : '')
	        	$('td:eq(3)', nRow).html(pizza)

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(6)', nRow).html());
	        	$('td:eq(6)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(7)', nRow).html());
	        	$('td:eq(7)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(8)', nRow).html());
	        	$('td:eq(8)', nRow).html(fileSize(checkin, 2));
                
	        	// startup status
	        	var pizza=$('td:eq(9)', nRow).html();
	        	pizza = pizza == 'FAILED' ? i18n.t('failed') : pizza
	        	$('td:eq(9)', nRow).html(pizza)
                
	        	// registration status
	        	var regstatus=$('td:eq(10)', nRow).html();
	        	regstatus = regstatus == '1' ? i18n.t('caching.registered') :
                regstatus = regstatus == '0' ? i18n.t('caching.not_registered') :
	        	(regstatus === '-1' ? i18n.t('error') : regstatus)
	        	$('td:eq(10)', nRow).html(regstatus)

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(13)', nRow).html());
	        	$('td:eq(13)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(14)', nRow).html());
	        	$('td:eq(14)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(15)', nRow).html());
	        	$('td:eq(15)', nRow).html(fileSize(checkin, 2));
                
	        	// Format byte size
	        	var checkin = parseInt($('td:eq(16)', nRow).html());
	        	$('td:eq(16)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(17)', nRow).html());
	        	$('td:eq(17)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(18)', nRow).html());
	        	$('td:eq(18)', nRow).html(fileSize(checkin, 2));
                
	        	// Format byte size
	        	var checkin = parseInt($('td:eq(19)', nRow).html());
	        	$('td:eq(19)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(20)', nRow).html());
	        	$('td:eq(20)', nRow).html(fileSize(checkin, 2));

	        	// Format byte size
	        	var checkin = parseInt($('td:eq(21)', nRow).html());
	        	$('td:eq(21)', nRow).html(fileSize(checkin, 2));
                
	        	// Format byte size
	        	var checkin = parseInt($('td:eq(23)', nRow).html());
	        	$('td:eq(23)', nRow).html(fileSize(checkin, 2)); 
                
	        	// Format byte size
	        	var checkin = parseInt($('td:eq(24)', nRow).html());
	        	$('td:eq(24)', nRow).html(fileSize(checkin, 2));
                
	        	// Format byte size
	        	var checkin = parseInt($('td:eq(25)', nRow).html());
	        	$('td:eq(25)', nRow).html(fileSize(checkin, 2));
                
	        	// Format byte size
	        	var checkin = parseInt($('td:eq(26)', nRow).html());
	        	$('td:eq(26)', nRow).html(fileSize(checkin, 2));
                                
	        	// Format byte size
	        	var checkin = parseInt($('td:eq(27)', nRow).html());
	        	$('td:eq(27)', nRow).html(fileSize(checkin, 2));
                                
	        	// Format byte size
	        	var checkin = parseInt($('td:eq(28)', nRow).html());
	        	$('td:eq(28)', nRow).html(fileSize(checkin, 2));
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
