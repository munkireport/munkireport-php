<?php $this->view('partials/head'); ?>

<?php // Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Jamf_model;
?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
      <h3><span data-i18n="jamf.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>
      <table class="table table-striped table-condensed table-bordered">
        <thead>
          <tr>
            <th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
            <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
            <th data-i18n="jamf.jamf_version" data-colname='jamf.jamf_version'></th>
            <th data-i18n="jamf.managed" data-colname='jamf.managed'></th>
            <th data-i18n="jamf.enrolled_via_dep" data-colname='jamf.enrolled_via_dep'></th>
            <th data-i18n="jamf.user_approved_enrollment" data-colname='jamf.user_approved_enrollment'></th>
            <th data-i18n="jamf.user_approved_mdm" data-colname='jamf.user_approved_mdm'></th>
            <th data-i18n="jamf.realname" data-colname='jamf.realname'></th>
            <th data-i18n="jamf.department" data-colname='jamf.department'></th>
            <th data-i18n="jamf.building" data-colname='jamf.building'></th>
            <th data-i18n="jamf.site_name" data-colname='jamf.site_name'></th>
            <th data-i18n="jamf.xprotect_version" data-colname='jamf.xprotect_version'></th>
            <th data-i18n="jamf.disable_automatic_login" data-colname='jamf.disable_automatic_login'></th>
            <th data-i18n="jamf.is_purchased" data-colname='jamf.is_purchased'></th>
            <th data-i18n="jamf.comands_completed" data-colname='jamf.comands_completed'></th>
            <th data-i18n="jamf.comands_pending" data-colname='jamf.comands_pending'></th>
            <th data-i18n="jamf.comands_failed" data-colname='jamf.comands_failed'></th>
            <th data-i18n="jamf.last_contact_time_epoch" data-colname='jamf.last_contact_time_epoch'></th>
            <th data-i18n="jamf.last_enrolled_date_epoch" data-colname='jamf.last_enrolled_date_epoch'></th>
            <th data-i18n="jamf.report_date_epoch" data-colname='jamf.report_date_epoch'></th>
          </tr>
        </thead>
        <tbody>
            <tr>
              <td data-i18n="listing.loading" colspan="20" class="dataTables_empty"></td>
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
                url: appUrl + '/datatables/data',
                type: "POST",
                data: function(d){
                    d.mrColNotEmpty = "jamf.jamf_id"
                    
                    // managed
                    if(d.search.value.match(/^managed = \d$/))
                    {
                        // Add column specific search
                        d.columns[3].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                        // Clear global search
                        d.search.value = '';
                    }
                    
                    // enrolled_via_dep
                    if(d.search.value.match(/^enrolled_via_dep = \d$/))
                    {
                        // Add column specific search
                        d.columns[4].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                        // Clear global search
                        d.search.value = '';
                    }
                    
                    // user_approved_enrollment
                    if(d.search.value.match(/^user_approved_enrollment = \d$/))
                    {
                        // Add column specific search
                        d.columns[5].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                        // Clear global search
                        d.search.value = '';
                    }
                    
                    // user_approved_mdm
                    if(d.search.value.match(/^user_approved_mdm = \d$/))
                    {
                        // Add column specific search
                        d.columns[6].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                        // Clear global search
                        d.search.value = '';
                    }
                    
                    // disable_automatic_login
                    if(d.search.value.match(/^disable_automatic_login = \d$/))
                    {
                        // Add column specific search
                        d.columns[12].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                        // Clear global search
                        d.search.value = '';
                    }
                    
                    // is_purchased
                    if(d.search.value.match(/^is_purchased = \d$/))
                    {
                        // Add column specific search
                        d.columns[13].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                        // Clear global search
                        d.search.value = '';
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_jamf-tab');
	        	$('td:eq(0)', nRow).html(link);
                 
	        	// Make serial number in second column link to Jamf
	        	var serial=$('td:eq(1)', nRow).html();
                var jamf_server = "<?php echo rtrim(conf('jamf_server'), '/'); ?>"; // Get the Jamf server address
	        	var link = '<a class="btn btn-default btn-xs" href="'+jamf_server+'/computers.html?sn='+serial+'" target="_blank" title="'+i18n.t('jamf.view_in_jamf')+'">'+serial+'</a>';
	        	$('td:eq(1)', nRow).html(link);
                
	        	// managed
	        	var colvar=$('td:eq(3)', nRow).html();
	        	colvar = colvar == 1 ? i18n.t('yes') :
	        	(colvar == 0 ? i18n.t('no') : '')
	        	$('td:eq(3)', nRow).html(colvar)
                
	        	// enrolled_via_dep
	        	var colvar=$('td:eq(4)', nRow).html();
	        	colvar = colvar == 1 ? i18n.t('yes') :
	        	(colvar == 0 ? i18n.t('no') : '')
	        	$('td:eq(4)', nRow).html(colvar)
                
	        	// user_approved_enrollment
	        	var colvar=$('td:eq(5)', nRow).html();
	        	colvar = colvar == '1' ? i18n.t('yes') :
	        	(colvar === '0' ? i18n.t('no') : '')
	        	$('td:eq(5)', nRow).html(colvar)
                
	        	// user_approved_mdm
	        	var colvar=$('td:eq(6)', nRow).html();
	        	colvar = colvar == '1' ? i18n.t('yes') :
	        	(colvar === '0' ? i18n.t('no') : '')
	        	$('td:eq(6)', nRow).html(colvar)
                
	        	// disable_automatic_login
	        	var colvar=$('td:eq(12)', nRow).html();
	        	colvar = colvar == '1' ? i18n.t('yes') :
	        	(colvar === '0' ? i18n.t('no') : '')
	        	$('td:eq(12)', nRow).html(colvar)
                
	        	// is_purchased
	        	var colvar=$('td:eq(13)', nRow).html();
	        	colvar = colvar == '1' ? i18n.t('jamf.is_purchased') :
	        	(colvar === '0' ? i18n.t('jamf.is_leased') : '')
	        	$('td:eq(13)', nRow).html(colvar)

	        	// Format last_contact_time_epoch timestamp
	        	var date = parseInt($('td:eq(17)', nRow).html());
	        	$('td:eq(17)', nRow).html('<span title="'+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span>');
                
	        	// Format last_enrolled_date_epoch timestamp
	        	var date = parseInt($('td:eq(18)', nRow).html());
	        	$('td:eq(18)', nRow).html('<span title="'+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span>');
                
	        	// Format report_date_epoch timestamp
	        	var date = parseInt($('td:eq(19)', nRow).html());
	        	$('td:eq(19)', nRow).html('<span title="'+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span>');
	        }
	    });
	});
</script>

<?php $this->view('partials/foot'); ?>
