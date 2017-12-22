<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Applications_model;
?>

<div class="container">
  <div class="row">
	<div class="col-lg-12">

	  <h3><span data-i18n="applications.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
			<th data-i18n="name" data-colname='applications.name'></th>
			<th data-i18n="version" data-colname='applications.version'></th>
			<th data-i18n="applications.signed_by" data-colname='applications.signed_by'></th>
			<th data-i18n="applications.obtained_from" data-colname='applications.obtained_from'></th>
			<th data-i18n="applications.last_modified" data-colname='applications.last_modified'></th>
			<th data-i18n="applications.has64bit" data-colname='applications.has64bit'></th>
			<th data-i18n="path" data-colname='applications.path'></th>
			<th data-i18n="info" data-colname='applications.info'></th>
		  </tr>
		</thead>

		<tbody>
		  <tr>
			<td data-i18n="listing.loading" colspan="10" class="dataTables_empty"></td>
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
                     d.mrColNotEmpty = "name";

                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'applications.' + d.search.value){
                                d.columns[index].search.value = '> 0';
                            }
                        });

                    }
        		    // IDK what this does
                    if(d.search.value.match(/^\d+\.\d+(\.(\d+)?)?$/)){
                        var search = d.search.value.split('.').map(function(x){return ('0'+x).slice(-2)}).join('');
                        d.search.value = search;
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_applications-tab');
	        	$('td:eq(0)', nRow).html(link);
                
                // Localize Obtained From
	        	var obtained_from=$('td:eq(5)', nRow).html();
	        	obtained_from = obtained_from == 'unknown' ? i18n.t('unknown') :
	        	obtained_from = obtained_from == 'mac_app_store' ? i18n.t('applications.mac_app_store') :
	        	obtained_from = obtained_from == 'apple' ? "Apple":
	        	(obtained_from === 'identified_developer' ? i18n.t('applications.identified_developer') : obtained_from)
	        	$('td:eq(5)', nRow).html(obtained_from)
                
                // Format date
	        	var event = parseInt($('td:eq(6)', nRow).html());
	        	var date = new Date(event * 1000);
	        	$('td:eq(6)', nRow).html('<span title="' + moment(date).fromNow() + '">'+ moment(date).format('llll')+'</span>');
                
	        	// 64-bit Yes/No
	        	var bit64=$('td:eq(7)', nRow).html();
	        	bit64 = bit64 == '1' ? i18n.t('Yes') :
	        	(bit64 === '0' ? i18n.t('No') : '')
	        	$('td:eq(7)', nRow).html(bit64)
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
