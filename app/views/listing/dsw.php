<?$this->view('partials/head')?>

<? //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Dsw_model;
?>

<div class="container">

  <div class="row">

    <div class="col-lg-12">
        <script type="text/javascript">

        $(document).ready(function() {

                // Get column names from data attribute
                var myCols = [];
                $('.table th').map(function(){
                      myCols.push({'mData' : $(this).data('colname')});
                });
                oTable = $('.table').dataTable( {
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "<?=url('datatables/data')?>",
                    "aoColumns": myCols,
                    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                        // Update name in first column to link
                        var name=$('td:eq(0)', nRow).html();
                        if(name == ''){name = "No Name"};
                        var sn=$('td:eq(1)', nRow).html();
                        var link = get_client_detail_link(name, sn, '<?=url()?>/');
                        $('td:eq(0)', nRow).html(link);

                    }
                } );

                // Use hash as searchquery
                if(window.location.hash.substring(1))
                {
                    oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
                }


            } );
        </script>

          <h3>DS report <span id="total-count" class='label label-primary'>…</span></h3>

          <table class="table table-striped table-condensed table-bordered">
            <thead>
              <tr>
                <th data-colname='machine#computer_name'>Name</th>
                <th data-colname='machine#serial_number'>Serial</th>
                <th data-colname='reportdata#long_username'>Username</th>
                <th data-colname='deploystudiow#ds_workflow'>DS Workflow</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" class="dataTables_empty">Loading data from server</td>
                </tr>
            </tbody>
          </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>
