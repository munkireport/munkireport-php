<?$this->view('partials/head')?>

<? //Initialize models needed for the table
  new Machine_model;
  new Reportdata_model;
  new Displays_info_model;
?>

<div class="container">

  <div class="row">

    <div class="col-lg-12">

    <script type="text/javascript">

      $(document).ready(function() {

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
            // Translating vendors column
            var vendor=$('td:eq(0)', nRow).html();
            switch (vendor)
            {
            case "610":
              vendor="Apple"
              break;
            case "10ac":
              vendor="DELL"
              break;
            }
            $('td:eq(0)', nRow).html(vendor)

          } //end fnCreatedRow

        } ); //end oTable

        // Use hash as searchquery
        if(window.location.hash.substring(1))
        {
          oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
        }

      } );
    </script>

    <h3>Displays report <span id="total-count" class='label label-primary'>…</span></h3>

      <table class="table table-striped table-condensed table-bordered">

        <thead>
          <tr>
            <th data-colname='displays#vendor'>Vendor</th>
            <th data-colname='displays#model'>Model</th>
            <th data-colname='displays#display_serial'>Serial number</th>
            <th data-colname='displays#manufactured'>Manufactured</th>
            <th data-colname='displays#native'>Max. resolution</th>
            <th data-colname='displays#timestamp'>Last seen</th>
            <th data-colname='displays#machine_serial'>On computer</th>
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

<?$this->view('partials/foot')?>
