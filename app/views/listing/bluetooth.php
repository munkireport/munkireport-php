<?$this->view('partials/head')?>

<? //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Bluetooth_model;
?>

<div class="container">

  <div class="row">
    <div class="col-lg-12">
    <script type="text/javascript">
    
    $(document).on('appUpdate', function(e){

        var oTable = $('.table').DataTable();
        oTable.ajax.reload();
        return;

    });


    $(document).on('appReady', function(e, lang) {

        // Get modifiers from data attribute
        var myCols = [], // Colnames
            mySort = [], // Initial sort
            hideThese = [], // Hidden columns
            col = 0; // Column counter

        $('.table th').map(function(){

            myCols.push({'mData' : $(this).data('colname')});

            if($(this).data('sort'))
            {
              mySort.push([col, $(this).data('sort')])
            }

            if($(this).data('hide'))
            {
              hideThese.push(col);
            }

            col++
        });

          oTable = $('.table').dataTable( {
              "bProcessing": true,
              "bServerSide": true,
              "sAjaxSource": "<?=url('datatables/data')?>",
              "aaSorting": mySort,
              "aoColumns": myCols,
              "aoColumnDefs": [
                { 'bVisible': false, "aTargets": hideThese }
          ],
              "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                // Update name in first column to link
                var name=$('td:eq(0)', nRow).html();
                if(name == ''){name = "No Name"};
                var sn=$('td:eq(1)', nRow).html();
                var link = get_client_detail_link(name, sn, '<?=url()?>/', '#tab_summary');
                $('td:eq(0)', nRow).html(link);

                // Status
                var status=$('td:eq(3)', nRow).html();
                status = status == 1 ? '<span class="label label-success">'+i18n.t('on')+'</span>' :
                (status === '0' ? '<span class="label label-danger">'+i18n.t('off')+'</span>' : '')
                $('td:eq(3)', nRow).html(status)

                // Format keyboard percentage
                var keyboard=$('td:eq(4)', nRow).html();
                var cls = keyboard < 15 ? 'danger' : (keyboard < 40 ? 'warning' : 'success');
                $('td:eq(4)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+keyboard+'%;">'+keyboard+'%</div></div>');

                // Format mouse percentage
                var mouse=$('td:eq(5)', nRow).html();
                var cls = mouse < 15 ? 'danger' : (mouse < 40 ? 'warning' : 'success');
                $('td:eq(5)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+mouse+'%;">'+mouse+'%</div></div>');

                // Format trackpad percentage
                var trackpad=$('td:eq(6)', nRow).html();
                var cls = trackpad < 15 ? 'danger' : (trackpad < 40 ? 'warning' : 'success');
                $('td:eq(6)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+trackpad+'%;">'+trackpad+'%</div></div>');

                // Hide all those negative ones. TODO function for any bool we find
                // var status=$('td:eq(7)', nRow).html();
                // status = status == 1 ? 'Yes' :
                // (status === '0' ? 'No' : '')
                // $('td:eq(7)', nRow).html(status)

            }
          } );

          // Use hash as searchquery
          if(window.location.hash.substring(1))
          {
          oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
          }

      } );
    </script>

      <h3>Bluetooth report <span id="total-count" class='label label-primary'>…</span></h3>

      <table class="table table-striped table-condensed table-bordered">
        <thead>
          <tr>
              <th data-i18n="listing.computername" data-colname='machine#computer_name'></th>
              <th data-i18n="serial" data-colname='reportdata#serial_number'></th>
              <th data-i18n="listing.username" data-colname='reportdata#long_username'></th>
              <th data-i18n="listing.bluetooth.status" data-colname='bluetooth#bluetooth_status'></th> 
              <th data-i18n="listing.bluetooth.keyboard_battery" data-colname='bluetooth#keyboard_battery'></th>
              <th data-i18n="listing.bluetooth.mouse_battery" data-colname='bluetooth#mouse_battery'></th>
              <th data-i18n="listing.bluetooth.trackpad_battery" data-colname='bluetooth#trackpad_battery'></th>
          </tr>
        </thead>
        <tbody>
          <tr>
          <td colspan="5" class="dataTables_empty">Loading data from server</td>
        </tr>
        </tbody>
      </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?php $this->view('partials/foot'); ?>
