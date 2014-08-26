<?$this->view('partials/head')?>

<? //Initialize models needed for the table
  new Displays_info_model;
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
          "sAjaxSource": "<?=url('datatables/data')?>",
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
              var link = get_client_detail_link(name, sn, '<?=url()?>/', '#tab_displays-tab');
              $('td:eq(0)', nRow).html(link);
            } else {
              $('td:eq(0)', nRow).html(name);
            }

            // Internal vs External
            var status=$('td:eq(2)', nRow).html();
            status = status == 1 ? 'External' :
              (status == '0' ? 'Internal' : '')
            $('td:eq(2)', nRow).html(status)

            // Translating vendors column
            //todo: find how the hell Apple translates the EDID/DDC to these values
            // http://ftp.netbsd.org/pub/NetBSD/NetBSD-current/src/sys/dev/videomode/ediddevs
            // https://github.com/GNOME/gnome-desktop/blob/master/libgnome-desktop/gnome-pnp-ids.c
            // https://www.opensource.apple.com/source/xnu/xnu-124.7/iokit/Families/IOGraphics/AppleDDCDisplay.cpp
            var vendor=$('td:eq(3)', nRow).html();
            switch (vendor)
            {
            case "610":
              vendor="Apple"
              break;
            case "10ac":
              vendor="Dell"
              break;
            case "5c23":
              vendor="Wacom"
              break;
            case "4d10":
              vendor="Sharp"
              break;
            case "1e6d":
              vendor="LG"
              break;
            case "38a3":
              vendor="NEC"
              break;
            case "4c49":
              vendor="SMART Technologies"
              break;
            case "9d1":
              vendor="BenQ"
              break;
            case "4dd9":
              vendor="Sony"
              break;
            case "472":
              vendor="Acer"
              break;
            case "22f0":
                vendor="HP"
                break;
            case "34ac":
                vendor="Mitsubishi"
                break;
            case "5a63":
                vendor="ViewSonic"
                break;
            case "4c2d":
                vendor="Samsung"
                break;
            case "593a":
                vendor="Vizio"
                break;
            case "d82":
                vendor="CompuLab"
                break;
            case "3023":
                vendor="LaCie"
                break;
            case "3698":
                vendor="Matrox"
                break;
            }
            $('td:eq(3)', nRow).html(vendor)

            // Format timestamp from unix to relative and the title to timezone detail
            date = aData['displays#timestamp'];
            if(date)
            {
                  var formatted='<time title="'+ moment.unix(date).format("LLLL (Z)") + '" </time>' + moment.unix(date).fromNow();
                  $('td:eq(8)', nRow).html(formatted);
            }

          } //end fnCreatedRow

        } ); //end oTable

      } );
    </script>

    <h3>Displays report <span id="total-count" class='label label-primary'>…</span></h3>

      <table class="table table-striped table-condensed table-bordered">

        <thead>
          <tr>
            <th data-colname='machine#computer_name'>On computer</th>
            <th data-colname='displays#serial_number'>Computer serial</th>
            <th data-colname='displays#type'>Type</th>
            <th data-colname='displays#vendor'>Vendor</th>
            <th data-colname='displays#model'>Model</th>
            <th data-colname='displays#display_serial'>Serial number</th>
            <th data-colname='displays#manufactured'>Manufactured</th>
            <th data-colname='displays#native'>Native resolution</th>
            <th data-sort="desc" data-colname='displays#timestamp'>Detected</th>
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

<?$this->view('partials/foot')?>
