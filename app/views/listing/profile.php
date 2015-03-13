<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
  new Profile_model;
  new Machine_model;
?>

<div class="container">

  <div class="row">

    <div class="col-lg-12">

    <script type="text/javascript">

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
          "sAjaxSource": "<?php echo url('datatables/data'); ?>",
          "aaSorting": mySort,
          "aoColumns": myCols,
          "aoColumnDefs": [
            { 'bVisible': false, "aTargets": hideThese }
          ],
          "fnCreatedRow": function( nRow, aData, iDataIndex ) {

            // Update computer name to link
            var name=$('td:eq(0)', nRow).html();
            if(name == ''){name = "No Name"};
            var sn=$('td:eq(1)', nRow).html();
            if(sn){
              var link = get_client_detail_link(name, sn, '<?php echo url(); ?>/', '#tab_displays-tab');
              $('td:eq(0)', nRow).html(link);
            } else {
              $('td:eq(0)', nRow).html(name);
            }

            // Format timestamp from unix to relative and the title to timezone detail
            date = aData['profile#timestamp'];
            if(date)
            {
                  var formatted='<time title="'+ moment.unix(date).format("LLLL (Z)") + '" </time>' + moment.unix(date).fromNow();
                  $('td:eq(8)', nRow).html(formatted);
            }

          } //end fnCreatedRow

        } ); //end oTable

      } );
    </script>

    <h3>Profile report <span id="total-count" class='label label-primary'>…</span></h3>

      <table class="table table-striped table-condensed table-bordered">

        <thead>
          <tr>
            <th data-colname='machine#computer_name'>Computer Name</th>
            <th data-colname='machine#serial_number'>Serial</th>
            <th data-colname='profile#profile_UUID'>UUID</th>
            <th data-colname='profile#profile_name'>Profile Name</th>
            <th data-colname='profile#payload_name'>Payload Name</th>
            <th data-colname='profile#payload_display'>Display Name</th>
            <th data-colname='profile#payload_data'>Data</th>
            <th data-colname='profile#profile_removal_allowed'>Removable?</th>
            <th data-sort="desc" data-colname='profile#timestamp'>Detected</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td colspan="6" class="dataTables_empty">Loading data from server</td>
          </tr>
        </tbody>

      </table>

    </div> <!-- /span 12 -->

  </div> <!-- /row -->

</div>  <!-- /container -->

<?php $this->view('partials/foot'); ?>
