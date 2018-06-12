<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Sophos_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="sophos.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		        <th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='machine.serial_number'></th>
                <th data-i18n="sophos.install-status" data-colname='sophos.installed'></th>
                <th data-i18n="sophos.running-label" data-colname='sophos.running'></th>
                <th data-i18n="sophos.product-version" data-colname='sophos.product_version'></th>
                <th data-i18n="sophos.engine-version" data-colname='sophos.engine_version'></th>
                <th data-i18n="sophos.virus-data-version" data-colname='sophos.virus_data_version'></th>
                <th data-i18n="sophos.ui-version" data-colname='sophos.user_interface_version'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td colspan="8" class="dataTables_empty">Loading data from server</td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 13 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<script>

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
                d.mrColNotEmpty = "sophos.id"
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
        	var link = mr.getClientDetailLink(name, sn, '#tab_summary');
            $('td:eq(0)', nRow).html(link);
            

            // Turn running into red/green button
            var avr=$('td:eq(3)', nRow).html();
            $('td:eq(3)', nRow).html(function(){
                if( avr == '1'){
                    return '<span class="label label-success">'+i18n.t('sophos.running-status')+'</span>';
                } else if( avr == '0') {
                    return '<span class="label label-danger">'+i18n.t('sophos.not-running-status')+'</span>';
                } else {
                    return '';
                }
            });
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
