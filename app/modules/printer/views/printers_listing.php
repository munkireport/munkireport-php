<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Printer_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

	<h3><span data-i18n="printer.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
			<th data-i18n="printer.name" data-colname='printer.name'></th>
			<th data-i18n="printer.ppd" data-colname='printer.ppd'></th>
			<th data-i18n="printer.driver_version" data-colname='printer.driver_version'></th>
			<th data-i18n="printer.url" data-colname='printer.URL'></th>
			<th data-i18n="printer.default_set" data-colname='printer.default_set'></th>
			<th data-i18n="printer.printer_status" data-colname='printer.printer_status'></th>
			<th data-i18n="printer.printer_sharing" data-colname='printer.printer_sharing'></th>
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
                    d.mrColNotEmpty = "printer.name"
                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            order: mySort,
            columnDefs: columnDefs,
		    createdRow: function( nRow, aData, iDataIndex ) {

                // Update computer name to link
                var name=$('td:eq(0)', nRow).html();
                if(name == ''){name = "No Name"};
                var sn=$('td:eq(1)', nRow).html();
                if(sn){
                  var link = mr.getClientDetailLink(name, sn, '#tab_printer-tab');
                  $('td:eq(0)', nRow).html(link);
                } else {
                  $('td:eq(0)', nRow).html(name);
                }

                // Default Set
                var defaultset=$('td:eq(6)', nRow).html();
                defaultset = defaultset == 'yes' ? i18n.t('yes') :
                (defaultset === 'no' ? i18n.t('no') : '')
                $('td:eq(6)', nRow).html(defaultset)

                // Printer Status
                var printerstatus=$('td:eq(7)', nRow).html();
                printerstatus = printerstatus == 'idle' ? i18n.t('printer.idle') :
                printerstatus = printerstatus == 'error' ? i18n.t('printer.error') :
                printerstatus = printerstatus == 'in use' ? i18n.t('printer.in_use') :
                (printerstatus === 'offline' ? i18n.t('printer.offline') : '')
                $('td:eq(7)', nRow).html(printerstatus)

                // Sharing
                var printersharing=$('td:eq(8)', nRow).html();
                printersharing = printersharing == 'yes' ? i18n.t('yes') :
                (printersharing === 'no' ? i18n.t('no') : '')
                $('td:eq(8)', nRow).html(printersharing)

            } //end fnCreatedRow

        }); //end oTable

	});
</script>


<?php $this->view('partials/foot'); ?>
