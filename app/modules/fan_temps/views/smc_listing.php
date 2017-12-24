<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Fan_temps_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="fan_temps.reporttitle_smc"></span> <span id="total-count" class='label label-primary'>…</span></h3>
		  
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="fan_temps.alsl_short" data-colname='fan_temps.alsl'></th>
		        <th data-i18n="fan_temps.msld_short" data-colname='fan_temps.msld'></th>
		        <th data-i18n="fan_temps.mssd_short" data-colname='fan_temps.mssd'></th>
		        <th data-i18n="fan_temps.discin_short" data-colname='fan_temps.discin'></th>
		        <th data-i18n="fan_temps.mstm_short" data-colname='fan_temps.mstm'></th>
		        <th data-i18n="fan_temps.lsof_short" data-colname='fan_temps.lsof'></th>
		        <th data-i18n="fan_temps.spht_short" data-colname='fan_temps.spht'></th>
		        <th data-i18n="fan_temps.sph0_short" data-colname='fan_temps.sph0'></th>
		        <th data-i18n="fan_temps.sght" data-colname='fan_temps.sght'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="11" class="dataTables_empty"></td>
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
                var link = mr.getClientDetailLink(name, sn, '#tab_smc-tab');
	        	$('td:eq(0)', nRow).html(link);

                var columnvar=$('td:eq(2)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(2)', nRow).html('');
                } else{				 
                     $('td:eq(2)', nRow).html(columnvar+" lux");
                }
                
	        	var columnvar=$('td:eq(3)', nRow).html();
	        	columnvar = columnvar == '1' ? i18n.t('fan_temps.closed') :
	        	(columnvar === '0' ? i18n.t('fan_temps.open') : '')
	        	$('td:eq(3)', nRow).html(columnvar)
                
                var columnvar=$('td:eq(4)', nRow).html();
	        	columnvar = columnvar == '5' ? i18n.t('fan_temps.shutdown5_short') :
	        	columnvar = columnvar == '3' ? i18n.t('fan_temps.shutdown3_short') :
	        	columnvar = columnvar == '2' ? i18n.t('fan_temps.shutdown2_short') :
	        	columnvar = columnvar == '1' ? i18n.t('fan_temps.shutdown1_short') :
	        	columnvar = columnvar == '0' ? i18n.t('fan_temps.shutdown0_short') :
	        	columnvar = columnvar == '-1' ? i18n.t('fan_temps.shutdown_1_short') :
	        	columnvar = columnvar == '-2' ? i18n.t('fan_temps.shutdown_2_short') :
	        	columnvar = columnvar == '-3' ? i18n.t('fan_temps.shutdown_3_short') :
	        	columnvar = columnvar == '-4' ? i18n.t('fan_temps.shutdown_4_short') :
	        	columnvar = columnvar == '-30' ? i18n.t('fan_temps.shutdown_30_short') :
	        	columnvar = columnvar == '-40' ? i18n.t('fan_temps.shutdown_40_short') :
	        	columnvar = columnvar == '-50' ? i18n.t('fan_temps.shutdown_50_short') :
	        	columnvar = columnvar == '-60' ? i18n.t('fan_temps.shutdown_60_short') :
	        	columnvar = columnvar == '-61' ? i18n.t('fan_temps.shutdown_61_short') :
	        	columnvar = columnvar == '-62' ? i18n.t('fan_temps.shutdown_62_short') :
	        	columnvar = columnvar == '-70' ? i18n.t('fan_temps.shutdown_70_short') :
	        	columnvar = columnvar == '-71' ? i18n.t('fan_temps.shutdown_71_short') :
	        	columnvar = columnvar == '-72' ? i18n.t('fan_temps.shutdown_72_short') :
	        	columnvar = columnvar == '-74' ? i18n.t('fan_temps.shutdown_74_short') :
	        	columnvar = columnvar == '-75' ? i18n.t('fan_temps.shutdown_75_short') :
	        	columnvar = columnvar == '-76' ? i18n.t('fan_temps.shutdown_76_short') :
	        	columnvar = columnvar == '-78' ? i18n.t('fan_temps.shutdown_78_short') :
	        	columnvar = columnvar == '-79' ? i18n.t('fan_temps.shutdown_79_short') :
	        	columnvar = columnvar == '-82' ? i18n.t('fan_temps.shutdown_82_short') :
	        	columnvar = columnvar == '-83' ? i18n.t('fan_temps.shutdown_83_short') :
	        	columnvar = columnvar == '-84' ? i18n.t('fan_temps.shutdown_84_short') :
	        	columnvar = columnvar == '-86' ? i18n.t('fan_temps.shutdown_86_short') :
	        	columnvar = columnvar == '-95' ? i18n.t('fan_temps.shutdown_95_short') :
	        	columnvar = columnvar == '-100' ? i18n.t('fan_temps.shutdown_100_short') :
	        	columnvar = columnvar == '-101' ? i18n.t('fan_temps.shutdown_101_short') :
	        	columnvar = columnvar == '-102' ? i18n.t('fan_temps.shutdown_102_short') :
	        	columnvar = columnvar == '-103' ? i18n.t('fan_temps.shutdown_103_short') :
	        	columnvar = columnvar == '-127' ? i18n.t('fan_temps.shutdown_127_short') :
                (columnvar === '-128' ? i18n.t('fan_temps.shutdown_128_short') : columnvar)
	        	$('td:eq(4)', nRow).html(columnvar)
                
                var columnvar=$('td:eq(5)', nRow).html();
	        	columnvar = columnvar == 'true' ? i18n.t('yes') :
	        	(columnvar === 'false' ? i18n.t('no') : '')
	        	$('td:eq(5)', nRow).html(columnvar)
                
                var columnvar=$('td:eq(6)', nRow).html();
	        	columnvar = columnvar == '1' ? i18n.t('enabled') :
	        	(columnvar === '0' ? i18n.t('disabled') : '')
	        	$('td:eq(6)', nRow).html(columnvar)
                
                var columnvar=$('td:eq(7)', nRow).html();
	        	columnvar = columnvar == '1' ? i18n.t('off') :
	        	(columnvar === '0' ? i18n.t('on') : '')
	        	$('td:eq(7)', nRow).html(columnvar)
                
                var columnvar=$('td:eq(8)', nRow).html();
	        	columnvar = columnvar == '1' ? i18n.t('yes') :
	        	(columnvar === '0' ? i18n.t('no') : '')
	        	$('td:eq(8)', nRow).html(columnvar)
                
                var columnvar=$('td:eq(10)', nRow).html();
	        	columnvar = columnvar == '1' ? i18n.t('yes') :
	        	(columnvar === '0' ? i18n.t('no') : '')
	        	$('td:eq(10)', nRow).html(columnvar)
                
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
