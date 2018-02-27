<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Launchdaemons_model;
?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
	<h3><span data-i18n="launchdaemons.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="launchdaemons.label" data-colname='launchdaemons.label'></th>
		        <th data-i18n="launchdaemons.path" data-colname='launchdaemons.path'></th>
		        <th data-i18n="launchdaemons.disabled" data-colname='launchdaemons.disabled'></th>
		        <th data-i18n="launchdaemons.program" data-colname='launchdaemons.program'></th>
		        <th data-i18n="launchdaemons.programarguments" data-colname='launchdaemons.programarguments'></th>
		        <th data-i18n="launchdaemons.ondemand" data-colname='launchdaemons.ondemand'></th>
		        <th data-i18n="launchdaemons.runatload" data-colname='launchdaemons.runatload'></th>
		        <th data-i18n="launchdaemons.startonmount" data-colname='launchdaemons.startonmount'></th>
		        <th data-i18n="launchdaemons.keepalive" data-colname='launchdaemons.keepalive'></th>
		        <th data-i18n="launchdaemons.startinterval" data-colname='launchdaemons.startinterval'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="12" class="dataTables_empty"></td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 13 -->
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
                    d.mrColNotEmpty = "launchdaemons.label";

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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_launchdaemons-tab');
	        	$('td:eq(0)', nRow).html(link);

	        	// disabled
	        	var columnvar=$('td:eq(4)', nRow).html();
	        	columnvar = columnvar == '1' ? i18n.t('yes') :
	        	(columnvar == '0' ? i18n.t('no') : '')
	        	$('td:eq(4)', nRow).html(columnvar)

	        	// ondemand
	        	var columnvar=$('td:eq(7)', nRow).html();
	        	columnvar = columnvar == '1' ? i18n.t('yes') :
	        	(columnvar == '0' ? i18n.t('no') : '')
	        	$('td:eq(7)', nRow).html(columnvar)

	        	// runatload
	        	var columnvar=$('td:eq(8)', nRow).html();
	        	columnvar = columnvar == '1' ? i18n.t('yes') :
	        	(columnvar == '0' ? i18n.t('no') : '')
	        	$('td:eq(8)', nRow).html(columnvar)

	        	// startonmount
	        	var columnvar=$('td:eq(9)', nRow).html();
	        	columnvar = columnvar == '1' ? i18n.t('yes') :
	        	(columnvar == '0' ? i18n.t('no') : '')
	        	$('td:eq(9)', nRow).html(columnvar)

                // keepalive
	        	var columnvar=$('td:eq(10)', nRow).html();
	        	columnvar = columnvar == '1' ? i18n.t('yes') :
	        	(columnvar == '0' ? i18n.t('no') : '')
	        	$('td:eq(10)', nRow).html(columnvar)
                
                // startinterval
                var columnvar=parseInt($('td:eq(11)', nRow).html());
                if( columnvar) {
	        	  	$('td:eq(11)', nRow).html(moment.duration(columnvar, "seconds").humanize());
                } else {
                     $('td:eq(11)', nRow).html('');
                }
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
