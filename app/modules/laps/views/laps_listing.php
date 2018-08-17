<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Laps_model;
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h3><span data-i18n="laps.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>
            <table class="table table-striped table-condensed table-bordered">
            <thead>
                <tr>
                    <th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
                    <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
                    <th data-i18n="laps.useraccount" data-colname='laps.useraccount'></th>
                    <th data-i18n="laps.dateset" data-colname='laps.dateset'></th>
                    <th data-i18n="laps.dateexpires" data-colname='laps.dateexpires'></th>
                    <th data-i18n="laps.script_enabled_short" data-colname='laps.script_enabled'></th>
                    <th data-i18n="laps.days_till_expiration" data-colname='laps.days_till_expiration'></th>
                    <th data-i18n="laps.pass_length" data-colname='laps.pass_length'></th>
                    <th data-i18n="laps.alpha_numeric_only" data-colname='laps.alpha_numeric_only'></th>
                    <th data-i18n="laps.remote_management" data-colname='laps.remote_management'></th>
                    <th data-i18n="laps.keychain_remove" data-colname='laps.keychain_remove'></th>
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
                    d.mrColNotEmpty = "useraccount";
                    
                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'laps.' + d.search.value){
                                d.columns[index].search.value = '> 0';
                            }
                        });
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_laps-tab');
	        	$('td:eq(0)', nRow).html(link);
                
	        	// Format dateset timestamp
	        	var checkin = parseInt($('td:eq(3)', nRow).html());
	        	var date = new Date(checkin * 1000);
	        	$('td:eq(3)', nRow).html('<span title="'+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span>');
                
	        	// Format dateexpires timestamp
	        	var checkin = parseInt($('td:eq(4)', nRow).html());
	        	var date = new Date(checkin * 1000);
	        	$('td:eq(4)', nRow).html('<span title="'+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span>');
                
	        	// script_enabled
	        	var script_enabled=$('td:eq(5)', nRow).html();
	        	script_enabled = script_enabled == '1' ? i18n.t('yes') :
	        	(script_enabled === '0' ? i18n.t('no') : '')
	        	$('td:eq(5)', nRow).html(script_enabled)
                
	        	// days_till_expiration
	        	var days_till_expiration=$('td:eq(6)', nRow).html();
	        	if(days_till_expiration == "") {
                    $('td:eq(6)', nRow).html('');
	        	} else{				 
                    $('td:eq(6)', nRow).html(days_till_expiration+" "+i18n.t('date.day_plural'));
	        	}
                
	        	// alpha_numeric_only
	        	var alpha_numeric_only=$('td:eq(8)', nRow).html();
	        	alpha_numeric_only = alpha_numeric_only == '1' ? i18n.t('yes') :
	        	(alpha_numeric_only === '0' ? i18n.t('no') : '')
	        	$('td:eq(8)', nRow).html(alpha_numeric_only)
                
	        	// remote_management
	        	var remote_management=$('td:eq(9)', nRow).html();
	        	remote_management = remote_management == '1' ? i18n.t('enabled') :
	        	(remote_management === '0' ? i18n.t('disabled') : '')
	        	$('td:eq(9)', nRow).html(remote_management)
                
	        	// keychain_remove
	        	var keychain_remove=$('td:eq(10)', nRow).html();
	        	keychain_remove = keychain_remove == '1' ? i18n.t('yes') :
	        	(keychain_remove === '0' ? i18n.t('no') : '')
	        	$('td:eq(10)', nRow).html(keychain_remove)
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
