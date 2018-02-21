<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Fan_temps_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="fan_temps.reporttitle_fans"></span> <span id="total-count" class='label label-primary'>…</span></h3>
		  
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="name" data-colname='fan_temps.fanlabel0'></th>
		        <th data-i18n="fan_temps.speed" data-colname='fan_temps.fan_0'></th>
		        <th data-i18n="fan_temps.minfan" data-colname='fan_temps.fanmin0'></th>
		        <th data-i18n="fan_temps.maxfan" data-colname='fan_temps.fanmax0'></th>
		        <th data-i18n="name" data-colname='fan_temps.fanlabel1'></th>
		        <th data-i18n="fan_temps.speed" data-colname='fan_temps.fan_1'></th>
		        <th data-i18n="fan_temps.minfan" data-colname='fan_temps.fanmin1'></th>
		        <th data-i18n="fan_temps.maxfan" data-colname='fan_temps.fanmax1'></th>
		        <th data-i18n="name" data-colname='fan_temps.fanlabel2'></th>
		        <th data-i18n="fan_temps.speed" data-colname='fan_temps.fan_2'></th>
		        <th data-i18n="fan_temps.minfan" data-colname='fan_temps.fanmin2'></th>
		        <th data-i18n="fan_temps.maxfan" data-colname='fan_temps.fanmax2'></th>
		        <th data-i18n="fan_temps.fnfd_short" data-colname='fan_temps.fnfd'></th>
		        <th data-i18n="fan_temps.mssf_short" data-colname='fan_temps.mssf'></th>
		        <th data-i18n="fan_temps.fnum_short" data-colname='fan_temps.fnum'></th>
		        <th data-i18n="fan_temps.dba0_short" data-colname='fan_temps.dba0'></th>
		        <th data-i18n="fan_temps.dba1_short" data-colname='fan_temps.dba1'></th>
		        <th data-i18n="fan_temps.dba2_short" data-colname='fan_temps.dba2'></th>
		        <th data-i18n="fan_temps.dbah_short" data-colname='fan_temps.dbah'></th>
		        <th data-i18n="fan_temps.dbat_short" data-colname='fan_temps.dbat'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="22" class="dataTables_empty"></td>
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
                var link = mr.getClientDetailLink(name, sn, '#tab_fans-tab');
	        	$('td:eq(0)', nRow).html(link);

                var columnvar=$('td:eq(3)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(3)', nRow).html('');
                } else{				 
                     $('td:eq(3)', nRow).html(columnvar+" "+i18n.t('fan_temps.rpm'));
                }
                
                var columnvar=$('td:eq(4)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(4)', nRow).html('');
                } else{				 
                     $('td:eq(4)', nRow).html(columnvar+" "+i18n.t('fan_temps.rpm'));
                }
                
                var columnvar=$('td:eq(5)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(5)', nRow).html('');
                } else{				 
                     $('td:eq(5)', nRow).html(columnvar+" "+i18n.t('fan_temps.rpm'));
                }
                
                var columnvar=$('td:eq(7)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(7)', nRow).html('');
                } else{				 
                     $('td:eq(7)', nRow).html(columnvar+" "+i18n.t('fan_temps.rpm'));
                }
                
                var columnvar=$('td:eq(8)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(8)', nRow).html('');
                } else{				 
                     $('td:eq(8)', nRow).html(columnvar+" "+i18n.t('fan_temps.rpm'));
                }
                
                var columnvar=$('td:eq(9)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(9)', nRow).html('');
                } else{				 
                     $('td:eq(9)', nRow).html(columnvar+" "+i18n.t('fan_temps.rpm'));
                }
                
                var columnvar=$('td:eq(11)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(11)', nRow).html('');
                } else{				 
                     $('td:eq(11)', nRow).html(columnvar+" "+i18n.t('fan_temps.rpm'));
                }
                
                var columnvar=$('td:eq(12)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(12)', nRow).html('');
                } else{				 
                     $('td:eq(12)', nRow).html(columnvar+" "+i18n.t('fan_temps.rpm'));
                }
                
                var columnvar=$('td:eq(13)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(13)', nRow).html('');
                } else{				 
                     $('td:eq(13)', nRow).html(columnvar+" "+i18n.t('fan_temps.rpm'));
                }

	        	var columnvar=$('td:eq(14)', nRow).html();
	        	columnvar = columnvar > '1' ? i18n.t('yes') :
	        	(columnvar === '0' ? i18n.t('no') : '')
	        	$('td:eq(14)', nRow).html(columnvar)
                
	        	var columnvar=$('td:eq(15)', nRow).html();
	        	columnvar = columnvar == '1' ? i18n.t('yes') :
	        	(columnvar === '0' ? i18n.t('no') : '')
	        	$('td:eq(15)', nRow).html(columnvar)
                
                var columnvar=$('td:eq(17)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(17)', nRow).html('');
                } else{				 
                     $('td:eq(17)', nRow).html(columnvar+" dBA");
                }
                
                var columnvar=$('td:eq(18)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(18)', nRow).html('');
                } else{				 
                     $('td:eq(18)', nRow).html(columnvar+" dBA");
                }
                
                var columnvar=$('td:eq(19)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(19)', nRow).html('');
                } else{				 
                     $('td:eq(19)', nRow).html(columnvar+" dBA");
                }
                
                var columnvar=$('td:eq(20)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(20)', nRow).html('');
                } else{				 
                     $('td:eq(20)', nRow).html(columnvar+" dBDA");
                }
                
                var columnvar=$('td:eq(21)', nRow).html();
                if(columnvar == "") {
                     $('td:eq(21)', nRow).html('');
                } else{				 
                     $('td:eq(21)', nRow).html(columnvar+" dBDA");
                }
                
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
