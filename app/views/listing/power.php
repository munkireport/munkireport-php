<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Power_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="nav.reports.power"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="listing.username" data-colname='reportdata.long_username'></th>
		        <th data-i18n="listing.power.designed" data-colname='power.design_capacity'></th>
		        <th data-i18n="listing.power.capacity" data-colname='power.max_capacity'></th>
		        <th data-i18n="listing.power.cycles" data-colname='power.cycle_count'></th>
		        <th data-i18n="listing.power.health" data-colname='power.max_percent'></th>
		        <th data-i18n="listing.power.condition" data-colname='power.condition'></th>
		        <th data-i18n="listing.power.current" data-colname='power.current_capacity'></th>
		        <th data-i18n="listing.power.charged" data-colname='power.current_percent'></th>
				<?php
					$temperature_unit=conf('temperature_unit');
					if ( $temperature_unit == "F" ) {
						echo "<th data-colname='power.temperature'>Temp°F</th>";
					} else {
						echo "<th data-colname='power.temperature'>Temp°C</th>";
					}
				?>
		        <th data-i18n="listing.power.manufactured" data-colname='power.manufacture_date'></th> 
		        <th data-i18n="listing.checkin" data-sort="desc" data-colname='power.timestamp'></th> 
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="13" class="dataTables_empty"></td>
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
                    d.mrColNotEmpty = "power.condition";
                    
                    // Look for 'between' statement todo: make generic
                    if(d.search.value.match(/^\d+% max_percent \d+%$/))
                    {
                        // Add column specific search
                        d.columns[6].search.value = d.search.value.replace(/(\d+%) max_percent (\d+%)/, function(m, from, to){return ' BETWEEN ' + parseInt(from) + ' AND ' + parseInt(to)});
                        // Clear global search
                        d.search.value = '';

                        //dumpj(d)
                    }

                    // Look for a bigger/smaller/equal statement
                    if(d.search.value.match(/^max_percent [<>=] \d+%$/))
                    {
                        // Add column specific search
                        d.columns[6].search.value = d.search.value.replace(/.*([<>=] )(\d+%)$/, function(m, o, content){return o + parseInt(content)});
                        // Clear global search
                        d.search.value = '';

                        //dumpj(out)
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_power-tab');
	        	$('td:eq(0)', nRow).html(link);
			
	        	// Format designed capacity
	        	var fs=$('td:eq(3)', nRow).html();
	        	$('td:eq(3)', nRow).addClass('text-right');
                
	        	// Format maximum capacity
	        	var fs=$('td:eq(4)', nRow).html();
	        	$('td:eq(4)', nRow).addClass('text-right');
                
	        	// Format cycles
	        	var fs=$('td:eq(5)', nRow).html();
	        	$('td:eq(5)', nRow).addClass('text-right');
                
	        	// Format battery health
	        	var max_percent=$('td:eq(6)', nRow).html();
	        	var cls = max_percent > 89 ? 'success' : (max_percent > 79 ? 'warning' : 'danger');
	        	$('td:eq(6)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+max_percent+'%;">'+max_percent+'%</div></div>');
                
				// Format battery condition
	        	var status=$('td:eq(7)', nRow).html();
	        	status = status == 'Normal' ? '<span class="label label-success">'+i18n.t('widget.power.normal')+'</span>' : 
	        	status = status == 'Replace Soon' ? '<span class="label label-warning">'+i18n.t('widget.power.soon')+'</span>' : 
	        	status = status == 'Service Battery' ? '<span class="label label-warning">'+i18n.t('widget.power.service')+'</span>' : 
	        	status = status == 'Check Battery' ? '<span class="label label-warning">'+i18n.t('widget.power.check')+'</span>' : 
	        	status = status == 'Replace Now' ? '<span class="label label-danger">'+i18n.t('widget.power.now')+'</span>' : 
	        	status = status == '' ? '<span class="label label-danger">'+i18n.t('widget.power.now')+'</span>' : 
	        		(status === 'No Battery' ? '<span class="label label-danger">'+i18n.t('widget.power.nobattery')+'</span>' : '')
	        	$('td:eq(7)', nRow).html(status)
                
	        	// Format current charge
	        	var fs=$('td:eq(8)', nRow).html();
	        	$('td:eq(8)', nRow).addClass('text-right');
                
	        	// Format percentage
	        	var fs=$('td:eq(9)', nRow).html();
	        	$('td:eq(9)', nRow).addClass('text-right');
                
	        	// Format temperature
				// Check config for temperature_unit °C or °F
				// °C * 9/5 + 32 = °F
				var temperature_unit = "<?=conf('temperature_unit')?>";
				if ( temperature_unit == "F" ){
					// Fahrenheit
					var temperature=$('td:eq(10)', nRow).html();
					if ( temperature == 0 || temperature == "" ){
						temperature = "";
					} else {
						temperature = (((temperature * 9/5 ) + 3200 ) / 100).toFixed(1)+"°F";
					}
					$('td:eq(10)', nRow).html(temperature).addClass('text-right');
				} else {
					// Celsius
		        	var temperature=$('td:eq(10)', nRow).html();
					if ( temperature == 0 || temperature == "" ){
						temperature = "";
					} else {
				       	temperature = (temperature / 100).toFixed(1)+"°C";
					}
		        	$('td:eq(10)', nRow).html(temperature).addClass('text-right');
				}
                
	        	// Format Manufacture date
	        	var date=$('td:eq(11)', nRow).html();
                if(date === '1980-00-00'){
	        		$('td:eq(11)', nRow).addClass('text-right danger').html(i18n.t('widget.power.now'));
                } else {
	        	if(date){
	        		a = moment(date)
	        		b = a.diff(moment(), 'years', true)
	        		if(a.diff(moment(), 'years', true) < -4)
	        		{
	        			$('td:eq(11)', nRow).addClass('danger')
	        		}
	        		if(Math.round(b) == 4)
	        		{
	        			
	        		}
	        		$('td:eq(11)', nRow).addClass('text-right').html('<span title="'+date+'">'+moment(date).fromNow()+'</span>');
	        	}
                }
                
				// Format Check-In timestamp
				var checkin = parseInt($('td:eq(12)', nRow).html());
				var date = new Date(checkin * 1000);
				$('td:eq(12)', nRow).html('<span title="'+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span>');
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
