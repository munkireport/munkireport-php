<?php $this->view('partials/head')?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Localadmin_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="localadmin.title"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='machine.serial_number'></th>
		        <th data-i18n="localadmin.currentuser" data-colname='reportdata.console_user'></th>
		        <th data-i18n="localadmin.users" data-colname='localadmin.users'></th> 
		      </tr>
		    </thead>
		    <tbody>
		      <tr>
			<td data-i18n="listing.loading" colspan="5" class="dataTables_empty"></td>
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

        // Get column names from data attribute
        var columnDefs = [], //Column Definitions
            col = 0; // Column counter
        $('.table th').map(function(){
            columnDefs.push({name: $(this).data('colname'), targets: col});
            col++;
        });
        var oTable = $('.table').dataTable( {
            ajax: {
                url: "<?php echo url('datatables/data'); ?>",
                type: "POST",
                data: function( d ){
                    // Look for 'osversion' statement
                    if(d.search.value.match(/^\d+\.\d+(\.(\d+)?)?$/)){
                        var search = d.search.value.split('.').map(function(x){return ('0'+x).slice(-2)}).join('');
                        d.search.value = search;
                    }
              
                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            columnDefs: columnDefs,
            createdRow: function( nRow, aData, iDataIndex ) {
                // Update name in first column to link
                var name=$('td:eq(0)', nRow).html();
                if(name == ''){name = "No Name"};
                var sn=$('td:eq(1)', nRow).html();
                var link = mr.getClientDetailLink(name, sn, '<?php echo url(); ?>/');
                $('td:eq(0)', nRow).html(link);
                    
            }
        } );
    } );
</script>

<?php $this->view('partials/foot'); ?>

