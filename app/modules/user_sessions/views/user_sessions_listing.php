<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new User_sessions_model;
?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
		  <h3><span data-i18n="user_sessions.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		      	<th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		      	<th data-i18n="event" data-colname='user_sessions.event'></th>
		      	<th data-i18n="username" data-colname='user_sessions.user'></th>
		      	<th data-i18n="user_sessions.uid" data-colname='user_sessions.uid'></th>		      	
		      	<th data-i18n="user_sessions.ipaddress" data-colname='user_sessions.remote_ssh'></th>
		      	<th data-i18n="user_sessions.time" data-colname='user_sessions.time'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="6" class="dataTables_empty"></td>
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
                     d.mrColNotEmpty = "event";
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_user_sessions-tab');
	        	$('td:eq(0)', nRow).html(link);
                
             	// Event type
	        	var eventlocal=$('td:eq(2)', nRow).html();
	        	eventlocal = eventlocal == 'login' ? i18n.t('user_sessions.login') :
	        	eventlocal = eventlocal == 'sshlogin' ? i18n.t('user_sessions.sshlogin') :
	        	eventlocal = eventlocal == 'reboot' ? i18n.t('user_sessions.reboot') :
	        	eventlocal = eventlocal == 'shutdown' ? i18n.t('user_sessions.shutdown') :
	        	(eventlocal === 'logout' ? i18n.t('user_sessions.logout') : eventlocal)
	        	$('td:eq(2)', nRow).html(eventlocal)

	        	// Format date
	        	var event = parseInt($('td:eq(6)', nRow).html());
	        	var date = new Date(event * 1000);
	        	$('td:eq(6)', nRow).html('<span title="' + moment(date).format('llll') + '">'+moment(date).fromNow()+'</span>');

		    }
	    } );
	} );
</script>

<?php $this->view('partials/foot'); ?>
