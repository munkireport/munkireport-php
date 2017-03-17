<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Fan_temps_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="fan_temps.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>
		  
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="fan_temps.fan_0" data-colname='fan_temps.fan_0'></th>
		        <th data-i18n="fan_temps.fan_1" data-colname='fan_temps.fan_1'></th>
		        <th data-i18n="fan_temps.fan_2" data-colname='fan_temps.fan_2'></th>
		        <th data-i18n="fan_temps.ta0p" data-colname='fan_temps.ta0p'></th>
		        <th data-i18n="fan_temps.tc0f" data-colname='fan_temps.tc0f'></th>
		        <th data-i18n="fan_temps.tc0d" data-colname='fan_temps.tc0d'></th>
		        <th data-i18n="fan_temps.tc0p_short" data-colname='fan_temps.tc0p'></th>
		        <th data-i18n="fan_temps.tb0t" data-colname='fan_temps.tb0t'></th>
		        <th data-i18n="fan_temps.tb1t" data-colname='fan_temps.tb1t'></th>
		        <th data-i18n="fan_temps.tb2t" data-colname='fan_temps.tb2t'></th>
		        <th data-i18n="fan_temps.tg0d" data-colname='fan_temps.tg0d'></th>
		        <th data-i18n="fan_temps.tg0h" data-colname='fan_temps.tg0h'></th>
		        <th data-i18n="fan_temps.tg0p_short" data-colname='fan_temps.tg0p'></th>
		        <th data-i18n="fan_temps.tl0p_short" data-colname='fan_temps.tl0p'></th>
		        <th data-i18n="fan_temps.th0p_short" data-colname='fan_temps.th0p'></th>
		        <th data-i18n="fan_temps.th0h" data-colname='fan_temps.th0h'></th>
		        <th data-i18n="fan_temps.th1h" data-colname='fan_temps.th0h'></th>
		        <th data-i18n="fan_temps.th2h" data-colname='fan_temps.th2h'></th>
		        <th data-i18n="fan_temps.tm0s" data-colname='fan_temps.tm0s'></th>
		        <th data-i18n="fan_temps.tm0p_short" data-colname='fan_temps.tm0p'></th>
		        <th data-i18n="fan_temps.ts0p" data-colname='fan_temps.ts0p'></th>
		        <th data-i18n="fan_temps.tm0p_short" data-colname='fan_temps.tm0p'></th>
		        <th data-i18n="fan_temps.tn0h" data-colname='fan_temps.tn0h'></th>
		        <th data-i18n="fan_temps.tn0d_short" data-colname='fan_temps.tn0d'></th>
		        <th data-i18n="fan_temps.tn0p_short" data-colname='fan_temps.tn0p'></th>
		        <th data-i18n="fan_temps.tp0p_short" data-colname='fan_temps.tp0p'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="28" class="dataTables_empty"></td>
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
                    d.mrColNotEmpty = "fan_temps.id";
                        
                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'munkireport.' + d.search.value){
                                d.columns[index].search.value = '> 0';
                            }
                        });

                    };
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
                var link = mr.getClientDetailLink(name, sn, '#tab_fan_temps-tab');
	        	$('td:eq(0)', nRow).html(link);

                var temperature_unit = "<?=conf('temperature_unit')?>";

			var columnvar=$('td:eq(2)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876543) {
				 $('td:eq(2)', nRow).html('');
			} else{				 
				 $('td:eq(2)', nRow).html(columnvar+" "+i18n.t('fan_temps.rpm'));
			}
                
			var columnvar=$('td:eq(3)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876543) {
				 $('td:eq(3)', nRow).html('');
			} else{				 
				 $('td:eq(3)', nRow).html(columnvar+" "+i18n.t('fan_temps.rpm'));
			}
                
			var columnvar=$('td:eq(4)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876543) {
				 $('td:eq(4)', nRow).html('');
			} else{				 
				 $('td:eq(4)', nRow).html(columnvar+" "+i18n.t('fan_temps.rpm'));
			}
                
			// ambient air 0
			var columnvar=$('td:eq(5)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(5)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(5)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(5)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                             
			var columnvar=$('td:eq(6)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(6)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(6)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(6)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                             
			var columnvar=$('td:eq(7)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(7)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(7)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(7)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                              
			var columnvar=$('td:eq(8)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(8)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(8)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(8)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                             
			var columnvar=$('td:eq(9)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(9)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(9)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(9)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                              
			var columnvar=$('td:eq(10)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(10)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(10)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(10)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                             
			// 
			var columnvar=$('td:eq(11)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(11)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(11)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(11)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                          
			// 
			var columnvar=$('td:eq(12)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(12)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(12)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(12)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                           
			// 
			var columnvar=$('td:eq(13)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(13)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(13)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(13)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                          
			// 
			var columnvar=$('td:eq(14)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(14)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(14)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(14)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                          
			// 
			var columnvar=$('td:eq(15)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(15)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(15)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(15)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                         
			// 
			var columnvar=$('td:eq(16)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(16)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(16)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(16)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                           
			// 
			var columnvar=$('td:eq(17)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(17)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(17)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(17)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                          
			// 
			var columnvar=$('td:eq(18)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(18)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(18)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(18)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                          
			// 
			var columnvar=$('td:eq(19)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(19)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(19)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(19)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                         
			// 
			var columnvar=$('td:eq(20)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(20)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(20)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(20)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                           
			// 
			var columnvar=$('td:eq(21)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(21)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(21)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(21)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                          
			// 
			var columnvar=$('td:eq(22)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(22)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(22)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(22)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                          
			// 
			var columnvar=$('td:eq(23)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(23)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(23)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(23)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                          
			// 
			var columnvar=$('td:eq(24)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(24)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(24)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(24)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                          
			// 
			var columnvar=$('td:eq(25)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(25)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(25)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(25)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                          
			// 
			var columnvar=$('td:eq(26)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(26)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(26)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(26)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}
                                          
			// 
			var columnvar=$('td:eq(27)', nRow).html();
			if(columnvar === "-9876540" || columnvar === -9876540) {
				 $('td:eq(27)', nRow).html('');
			} else{				 
				 temperature_c = parseFloat((columnvar * 1).toFixed(2))+"°C";
				 temperature_f = parseFloat(((columnvar * 9/5 ) + 32 ).toFixed(2))+"°F";
				 if ( temperature_unit == "F" ){
				      $('td:eq(27)', nRow).html('<span title="'+temperature_c+'">'+temperature_f+'</span>');
				 } else{
				      $('td:eq(27)', nRow).html('<span title="'+temperature_f+'">'+temperature_c+'</span>');
				 }
			}        
                
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
