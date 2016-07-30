<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Managedinstalls_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

	<h3><span data-i18n="managedinstalls.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
            <th data-i18n="name" data-colname='managedinstalls.name'></th>
            <th data-i18n="displayname" data-colname='managedinstalls.display_name'></th>
            <th data-i18n="version" data-colname='managedinstalls.version'></th>
            <th data-i18n="status" data-colname='managedinstalls.status'></th>
            <th data-i18n="type" data-colname='managedinstalls.type'></th>
            <th data-i18n="size" data-colname='managedinstalls.size'></th>
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
                    d.mrColNotEmpty = "managedinstalls.name"
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
                  var link = mr.getClientDetailLink(name, sn, '#tab_munki');
                  $('td:eq(0)', nRow).html(link);
                } else {
                  $('td:eq(0)', nRow).html(name);
                }
                
                $('td:last', nRow).html(fileSize($('td:last', nRow).html() * 1024));    

            } //end fnCreatedRow

        }); //end oTable

	});
</script>


<?php $this->view('partials/foot'); ?>
