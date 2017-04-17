<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Certificate_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="certificate.title"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="certificate.commonname" data-colname='certificate.cert_cn'></th>
		        <th data-i18n="certificate.expires" data-colname='certificate.cert_exp_time'></th>
		        <th data-i18n="certificate.expiration_date" data-colname='certificate.cert_exp_time'></th>
		        <th data-i18n="certificate.issuer" data-colname='certificate.issuer'></th>
                <th data-i18n="certificate.location" data-colname='certificate.cert_location'></th>
				<th data-i18n="listing.checkin" data-colname='reportdata.timestamp'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="6" class="dataTables_empty"></td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 13 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<script type="text/javascript">

	$(document).on('appUpdate', function(e){
		// On update, redraw table
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
                type: "POST",
                data: function( d ){
                  d.mrColNotEmpty = "certificate.id"
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_certificate-tab');
	        	$('td:eq(0)', nRow).html(link);

	        	// Format relative expiration date
	        	var checkin = parseInt($('td:eq(3)', nRow).html());
	        	var date = new Date(checkin * 1000);
	        	var diff = moment().diff(date, 'days');
	        	var cls = diff > 0 ? 'danger' : (diff > -90 ? 'warning' : 'success');
	        	$('td:eq(3)', nRow).html('<span class="label label-'+cls+'"><span title="'+date+'">'+moment(date).fromNow()+'</span>');

	        	// Format expiration date
	        	var checkin = parseInt($('td:eq(4)', nRow).html());
	        	var date = new Date(checkin * 1000);
	        	$('td:eq(4)', nRow).html('<span title="'+moment(date).format('LLLL')+'">'+date+'</span>');

	        	// Format Check-In timestamp
	        	var checkin = parseInt($('td:eq(7)', nRow).html());
	        	var date = new Date(checkin * 1000);
	        	$('td:eq(7)', nRow).html('<span title="'+date+'">'+moment(date).fromNow()+'</span>');

		    }
	    } );
	    // Use hash as searchquery
	    if(window.location.hash.substring(1))
	    {
			oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
	    }

	} );
</script>

<?php $this->view('partials/foot')?>
