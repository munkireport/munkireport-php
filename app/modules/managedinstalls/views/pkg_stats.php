<?php $this->view('partials/head', array('scripts' => array("clients/client_list.js"))); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Munkireport_model;
new munkiinfo_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		  <h3><span data-i18n="managedinstalls.installratio_report"></span> <span id="total-count" class='label label-primary'>…</span></h3>
          
          <table id="pkg-stats-table" class="table table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Ratio</th>
                <th>Installed</th>
                <th>Pending</th>
                <th>Failed</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->


<script>

$(document).on('appReady', function(e, lang) {
    $.getJSON(appUrl + '/module/managedinstalls/get_pkg_stats/', function(data){
            
        var dataSet = [],
            linkUrl = appUrl + '/module/managedinstalls/listing#';

        $.each(data, function(index, val){
            if(val.name){
                var display_name = val.display_name || val.name,
                    installed = val.installed || 0,
                    pending = val.pending_install || 0,
                    failed = val.install_failed || 0,
                    total = installed + pending + failed,
                    pct = total ? Math.round((total - pending - failed)/total * 100) : 0;
                dataSet.push([
                    display_name,
                    pct + '%',
                    installed,
                    pending,
                    failed
                ])
            }
        });
                
        // Initialize datatables
        $('#pkg-stats-table tbody').empty();
        $('#pkg-stats-table').dataTable({
            data: dataSet,
            serverSide: false,
            order: [0,'asc'],
            createdRow: function( nRow, aData, iDataIndex ) {
                var name=$('td:eq(0)', nRow).html();
                $('td:eq(0)', nRow).html('<a href="'+linkUrl+name+'">'+name+'</a>');

            },
            drawCallback: function( oSettings ) {
    			$('#total-count').html(oSettings.fnRecordsTotal());
            }
        });
    });
});

</script>

<?php $this->view('partials/foot'); ?>
