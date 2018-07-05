<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Icloud_model;
?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
	<h3><span data-i18n="icloud.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="icloud.account_id" data-colname='icloud.account_id'></th>
		        <th data-i18n="icloud.display_name" data-colname='icloud.display_name'></th>
		        <th data-i18n="icloud.logged_in" data-colname='icloud.logged_in'></th>
		        <th data-i18n="icloud.clouddesktop_desktop_enabled" data-colname='icloud.clouddesktop_desktop_enabled'></th>
		        <th data-i18n="icloud.clouddesktop_documents_enabled" data-colname='icloud.clouddesktop_documents_enabled'></th>
		        <th data-i18n="icloud.clouddesktop_drive_enabled" data-colname='icloud.clouddesktop_drive_enabled'></th>
		        <th data-i18n="icloud.mobile_documents_enabled" data-colname='icloud.mobile_documents_enabled'></th>
		        <th data-i18n="icloud.cloud_photo_enabled" data-colname='icloud.cloud_photo_enabled'></th>
		        <th data-i18n="icloud.photo_stream_enabled" data-colname='icloud.photo_stream_enabled'></th>
		        <th data-i18n="icloud.shared_streams_enabled" data-colname='icloud.shared_streams_enabled'></th>
		        <th data-i18n="icloud.mail_and_notes_enabled" data-colname='icloud.mail_and_notes_enabled'></th>
		        <th data-i18n="icloud.contacts_enabled" data-colname='icloud.contacts_enabled'></th>
		        <th data-i18n="icloud.calendar_enabled" data-colname='icloud.calendar_enabled'></th>
		        <th data-i18n="icloud.reminders_enabled" data-colname='icloud.reminders_enabled'></th>
		        <th data-i18n="icloud.bookmarks_enabled" data-colname='icloud.bookmarks_enabled'></th>
		        <th data-i18n="icloud.notes_enabled" data-colname='icloud.notes_enabled'></th>
		        <th data-i18n="icloud.siri_enabled" data-colname='icloud.siri_enabled'></th>
		        <th data-i18n="icloud.imessage_syncing_enabled" data-colname='icloud.imessage_syncing_enabled'></th>
		        <th data-i18n="icloud.keychain_sync_enabled" data-colname='icloud.keychain_sync_enabled'></th>
		        <th data-i18n="icloud.back_to_my_mac_enabled" data-colname='icloud.back_to_my_mac_enabled'></th>
		        <th data-i18n="icloud.find_my_mac_enabled" data-colname='icloud.find_my_mac_enabled'></th>
		        <th data-i18n="icloud.beta" data-colname='icloud.beta'></th>
		        <th data-i18n="icloud.is_managed_apple_id" data-colname='icloud.is_managed_apple_id'></th>
		        <th data-i18n="icloud.primary_email_verified" data-colname='icloud.primary_email_verified'></th>
		        <th data-i18n="icloud.account_dsid" data-colname='icloud.account_dsid'></th>
		        <th data-i18n="icloud.prefpath" data-colname='icloud.prefpath'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="27" class="dataTables_empty"></td>
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
                    d.mrColNotEmpty = "icloud.account_id";

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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_icloud-tab');
	        	$('td:eq(0)', nRow).html(link);

	        	// logged_in
                var status=$('td:eq(4)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
                $('td:eq(4)', nRow).html(status)
                
                // clouddesktop_desktop_enabled
                var status=$('td:eq(5)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(5)', nRow).html(status)
                
                // clouddesktop_documents_enabled
                var status=$('td:eq(6)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(6)', nRow).html(status)
                
                // clouddesktop_drive_enabled
                var status=$('td:eq(7)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(7)', nRow).html(status)
                
                // mobile_documents_enabled
                var status=$('td:eq(8)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(8)', nRow).html(status)
                
                // cloud_photo_enabled
                var status=$('td:eq(9)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(9)', nRow).html(status)
                
                // photo_stream_enabled
                var status=$('td:eq(10)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(10)', nRow).html(status)
                
                // shared_streams_enabled
                var status=$('td:eq(11)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(11)', nRow).html(status)
                
                // mail_and_notes_enabled
                var status=$('td:eq(12)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(12)', nRow).html(status)
                
                // contacts_enabled
                var status=$('td:eq(13)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(13)', nRow).html(status)
                
                // calendar_enabled
                var status=$('td:eq(14)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(14)', nRow).html(status)
                
                // reminders_enabled
                var status=$('td:eq(15)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(15)', nRow).html(status)
                
                // bookmarks_enabled
                var status=$('td:eq(16)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(16)', nRow).html(status)
                
                // notes_enabled
                var status=$('td:eq(17)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(17)', nRow).html(status)
                
                // siri_enabled
                var status=$('td:eq(18)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(18)', nRow).html(status)
                
                // imessage_syncing_enabled
                var status=$('td:eq(19)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(19)', nRow).html(status)
                
                // keychain_sync_enabled
                var status=$('td:eq(20)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(20)', nRow).html(status)
                
                // back_to_my_mac_enabled
                var status=$('td:eq(21)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(21)', nRow).html(status)
                
                // find_my_mac_enabled
                var status=$('td:eq(22)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
                $('td:eq(22)', nRow).html(status)
                
                // beta
                var status=$('td:eq(23)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
                $('td:eq(23)', nRow).html(status)
                
                // is_managed_apple_id
                var status=$('td:eq(24)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
                $('td:eq(24)', nRow).html(status)
                
                // primary_email_verified
                var status=$('td:eq(25)', nRow).html();
                status = status == 1 ? '<span class="label label-success">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-danger">'+i18n.t('no')+'</span>' : '')
                $('td:eq(25)', nRow).html(status)
                
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
