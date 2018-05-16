<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Backup2go_Model;
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">

            <h3><span data-i18n="backup2go.title"></span> <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="username" data-colname='reportdata.long_username'></th>
		        <th data-i18n="backup2go.date" data-colname='backup2go.backupdate'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="4" class="dataTables_empty"></td>
				</tr>
		    </tbody>
		  </table>

        </div>
    </div>
</div>

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
					d.mrColNotEmpty = "backup2go.backupdate"
                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            order: mySort,
            columnDefs: columnDefs,
			createdRow: function( nRow, aData, iDataIndex ) {
	        	//this is responsible for the output of the table

				//-- set col 1: computername
				var name=$('td:eq(0)', nRow).html();
				if(name == ''){name = "No Name"};
				var sn=$('td:eq(1)', nRow).html();
				var link = mr.getClientDetailLink(name, sn, '#tab_summary');
				$('td:eq(0)', nRow).html(link);

				//-- set col 2: serialnumber
				var serialnumber=$('td:eq(1)', nRow).html();
				$('td:eq(1)', nRow).html(serialnumber);

				//-- set col 3: user
				var user=$('td:eq(2)', nRow).html();
				$('td:eq(2)', nRow).html(user);

				//-- set col 4: last backup date
				var backupdate=$('td:eq(3)', nRow).html(); //date of backup

				if(backupdate !== "" && !isNaN(backupdate)){

					var date = new Date(backupdate * 1000);
					var bdate = moment(date);
					var now = moment();
					var days = now.diff(bdate, "days")

					if(days < 14){
					   cls = 'success ';
					} else if (days >= 14 && days < 28){
					   cls = 'warning';
					} else if (days >= 28){
					   cls = 'danger';
					}

					$('td:eq(3)', nRow).html('<span class="label label-'+cls+'"><span title="'+backupdate+'">'+bdate.fromNow()+'</span>');
				} else {
					$('td:eq(3)', nRow).html('<span class="label">'+i18n.t('unknown')+'</span>');
				}
		    }
	    });
	});
</script>
<?php $this->view('partials/foot'); ?>
