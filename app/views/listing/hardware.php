<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3>Hardware report <span id="total-count" class='label label-primary'>…</span></h3>
		  
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'>Name</th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'>Serial</th>
		        <th data-i18n="listing.username" data-colname='reportdata.long_username'>Username</th>
		        <th data-i18n="listing.hardware.description" data-colname='machine.machine_desc'>Description</th>
		        <th data-i18n="memory.memory" data-colname='machine.physical_memory'>Memory</th>
		        <th data-colname='machine.number_processors'>Processors</th>
		        <th data-colname='machine.cpu_arch'>CPU</th>
		        <th data-colname='machine.current_processor_speed'>Speed</th>
		        <th data-colname='machine.boot_rom_version'>Boot ROM</th>
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
        
        // Get column names from data attribute
		var columnDefs = [],
            col = 0; // Column counter
		$('.table th').map(function(){
              columnDefs.push({name: $(this).data('colname'), targets: col});
              col++;
		});
	    oTable = $('.table').dataTable( {
            columnDefs: columnDefs,
            ajax: {
                url: "<?=url('datatables/data')?>",
                type: "POST",
                data: function(d){
                    
                    // Look for 'between' statement todo: make generic
                    if(d.search.value.match(/^\d+GB memory \d+GB$/))
                    {
                        // Add column specific search
                        d.columns[4].search.value = d.search.value.replace(/(\d+GB) memory (\d+GB)/, function(m, from, to){return ' BETWEEN ' + parseInt(from) + ' AND ' + parseInt(to)});
                        // Clear global search
                        d.search.value = '';

                    }

                    // Look for a bigger/smaller/equal statement
                    if(d.search.value.match(/^memory [<>=] \d+GB$/))
                    {
                        // Add column specific search
                        d.columns[4].search.value = d.search.value.replace(/.*([<>=] )(\d+GB)$/, function(m, o, content){return o + parseInt(content)});
                        // Clear global search
                        d.search.value = '';

                        //dumpj(out)
                    }

                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
	        createdRow: function( nRow, aData, iDataIndex ) {
	        	// Update name in first column to link
	        	var name=$('td:eq(0)', nRow).html();
	        	if(name == ''){name = "No Name"};
	        	var sn=$('td:eq(1)', nRow).html();
	        	var link = mr.getClientDetailLink(name, sn);
	        	$('td:eq(0)', nRow).html(link);

	        	var mem=$('td:eq(4)', nRow).html();
	        	$('td:eq(4)', nRow).html(parseInt(mem) + ' GB');
	        }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>