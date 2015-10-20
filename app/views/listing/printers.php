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

	<h3>Printer report <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-colname='machine.computer_name'>On Computer</th>
			<th data-colname='reportdata.serial_number'>Computer Serial</th>
			<th data-colname='printer.name'>Printer Name</th>
			<th data-colname='printer.ppd'>PPD</th>
			<th data-colname='printer.driver_version'>Drive Version</th>
			<th data-colname='printer.URL'>URL</th>
			<th data-colname='printer.default_set'>Default</th>
			<th data-colname='printer.printer_status'>Printer Status</th>
			<th data-colname='printer.printer_sharing'>Shared</th>
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
                url: "<?=url('datatables/data')?>",
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
                  var link = get_client_detail_link(name, sn, '<?php echo url(); ?>/', '#tab_printer-tab');
                  $('td:eq(0)', nRow).html(link);
                } else {
                  $('td:eq(0)', nRow).html(name);
                }

            } //end fnCreatedRow

        }); //end oTable

	});
</script>


<?php $this->view('partials/foot'); ?>
