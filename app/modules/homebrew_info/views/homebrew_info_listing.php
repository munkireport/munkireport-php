<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Homebrew_info_model;
?>

<div class="container">
  <div class="row">
	<div class="col-lg-12">

	  <h3><span data-i18n="homebrew_info.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
			<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
			<th data-i18n="homebrew_info.homebrew_version" data-colname='homebrew_info.homebrew_version'></th>
			<th data-i18n="homebrew_info.core_tap_head" data-colname='homebrew_info.core_tap_head'></th>
			<th data-i18n="homebrew_info.core_tap_origin" data-colname='homebrew_info.core_tap_origin'></th>
			<th data-i18n="homebrew_info.core_tap_last_commit" data-colname='homebrew_info.core_tap_last_commit'></th>
			<th data-i18n="homebrew_info.head" data-colname='homebrew_info.head'></th>
			<th data-i18n="homebrew_info.last_commit" data-colname='homebrew_info.last_commit'></th>
			<th data-i18n="homebrew_info.origin" data-colname='homebrew_info.origin'></th>
			<th data-i18n="homebrew_info.homebrew_bottle_domain" data-colname='homebrew_info.homebrew_bottle_domain'></th>
			<th data-i18n="homebrew_info.homebrew_cellar" data-colname='homebrew_info.homebrew_cellar'></th>
			<th data-i18n="homebrew_info.homebrew_prefix" data-colname='homebrew_info.homebrew_prefix'></th>
			<th data-i18n="homebrew_info.homebrew_repository" data-colname='homebrew_info.homebrew_repository'></th>
			<th data-i18n="homebrew_info.homebrew_git_config_file" data-colname='homebrew_info.homebrew_git_config_file'></th>
			<th data-i18n="homebrew_info.homebrew_ruby" data-colname='homebrew_info.homebrew_ruby'></th>
			<th data-i18n="homebrew_info.homebrew_noanalytics_this_run" data-colname='homebrew_info.homebrew_noanalytics_this_run'></th>
			<th data-i18n="homebrew_info.command_line_tools" data-colname='homebrew_info.command_line_tools'></th>
			<th data-i18n="homebrew_info.cpu" data-colname='homebrew_info.cpu'></th>
			<th data-i18n="homebrew_info.git" data-colname='homebrew_info.git'></th>
			<th data-i18n="homebrew_info.clang" data-colname='homebrew_info.clang'></th>
			<th data-i18n="homebrew_info.curl" data-colname='homebrew_info.curl'></th>
			<th data-i18n="homebrew_info.java" data-colname='homebrew_info.java'></th>
			<th data-i18n="homebrew_info.perl" data-colname='homebrew_info.perl'></th>
			<th data-i18n="homebrew_info.python" data-colname='homebrew_info.python'></th>
			<th data-i18n="homebrew_info.ruby" data-colname='homebrew_info.ruby'></th>
			<th data-i18n="homebrew_info.x11" data-colname='homebrew_info.x11'></th>
			<th data-i18n="homebrew_info.xcode" data-colname='homebrew_info.xcode'></th>
			<th data-i18n="homebrew_info.macos" data-colname='homebrew_info.macos'></th>
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
                     d.mrColNotEmpty = "homebrew_version";
                    
                    // Check for column in search
                    if(d.search.value){
                        $.each(d.columns, function(index, item){
                            if(item.name == 'homebrew_info.' + d.search.value){
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_homebrew_info-tab');
	        	$('td:eq(0)', nRow).html(link);
                
                // Analytics Enabled/Disabled
	        	var homebrew_noanalytics_this_run=$('td:eq(15)', nRow).html();
	        	homebrew_noanalytics_this_run = homebrew_noanalytics_this_run == '1' ? i18n.t('yes') :
	        	(homebrew_noanalytics_this_run === '0' ? i18n.t('no') : '')
	        	$('td:eq(15)', nRow).html(homebrew_noanalytics_this_run)
                
		    }
	    });

	});
</script>

<?php $this->view('partials/foot'); ?>
