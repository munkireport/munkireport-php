<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Fonts_model;
?>

<div class="container">
  <div class="row">
	<div class="col-lg-12">

	  <h3><span data-i18n="fonts.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
			<th data-i18n="fonts.type_name" data-colname='fonts.type_name'></th>
			<th data-i18n="fonts.type_enabled" data-colname='fonts.type_enabled'></th>
			<th data-i18n="fonts.valid" data-colname='fonts.valid'></th>
			<th data-i18n="fonts.duplicate" data-colname='fonts.duplicate'></th>
			<th data-i18n="fonts.style" data-colname='fonts.style'></th>
			<th data-i18n="fonts.family" data-colname='fonts.family'></th>
			<th data-i18n="fonts.type" data-colname='fonts.type'></th>
			<th data-i18n="fonts.copy_protected" data-colname='fonts.copy_protected'></th>
			<th data-i18n="fonts.path" data-colname='fonts.path'></th>
			<th data-i18n="fonts.vendor" data-colname='fonts.vendor'></th>
		  </tr>
		</thead>

		<tbody>
		  <tr>
			<td data-i18n="listing.loading" colspan="12" class="dataTables_empty"></td>
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
                            if(item.name == 'fonts.' + d.search.value){
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_fonts-tab');
	        	$('td:eq(0)', nRow).html(link);
                
	        	var internal=$('td:eq(3)', nRow).html();
	        	internal = internal == '1' ? i18n.t('yes') :
	        	(internal === '0' ? i18n.t('no') : '')
	        	$('td:eq(3)', nRow).html(internal)
                
	        	var media=$('td:eq(4)', nRow).html();
	        	media = media == '1' ? i18n.t('yes') :
	        	(media === '0' ? i18n.t('no') : '')
	        	$('td:eq(4)', nRow).html(media)
                
	        	var media=$('td:eq(5)', nRow).html();
	        	media = media == '1' ? i18n.t('yes') :
	        	(media === '0' ? i18n.t('no') : '')
	        	$('td:eq(5)', nRow).html(media)
                
	        	var media=$('td:eq(9)', nRow).html();
	        	media = media == '1' ? i18n.t('yes') :
	        	(media === '0' ? i18n.t('no') : '')
	        	$('td:eq(9)', nRow).html(media)
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
