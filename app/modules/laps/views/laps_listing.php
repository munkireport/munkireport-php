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
                
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
