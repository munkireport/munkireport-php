<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
  new Displays_info_model;
  new Machine_model;
?>

<div class="container">

  <div class="row">

	<div class="col-lg-12">

	<h3>Displays report <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-colname='machine.computer_name'>On computer</th>
			<th data-colname='reportdata.serial_number'>Computer serial</th>
			<th data-colname='displays.type'>Type</th>
			<th data-colname='displays.vendor'>Vendor</th>
			<th data-colname='displays.model'>Model</th>
			<th data-colname='displays.display_serial'>Serial number</th>
			<th data-colname='displays.manufactured'>Manufactured</th>
			<th data-colname='displays.native'>Native resolution</th>
			<th data-sort="desc" data-colname='displays.timestamp'>Detected</th>
		  </tr>
		</thead>

		<tbody>
		  <tr>
			<td data-i18n="listing.loading" colspan="9" class="dataTables_empty"></td>
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
                url: "<?=url('datatables/data')?>",
                type: "POST",
                data: function(d){
                    d.mrColNotEmpty = "displays.vendor"
                    
                    // Look for 'external' keyword
                    if(d.search.value.match(/^external$/))
                    {
                        // Add column specific search
                        d.columns[2].search.value = '= 1';
                        // Clear global search
                        d.search.value = '';
                    }
                    
                    // Look for 'external' keyword
                    if(d.search.value.match(/^internal/))
                    {
                        // Add column specific search
                        d.columns[2].search.value = '= 0';
                        // Clear global search
                        d.search.value = '';
                    }

                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            order: mySort,
            columnDefs: columnDefs,
		    createdRow: function( nRow, aData, iDataIndex ) {

                // Update computer name to link
                var name=$('td:eq(0)', nRow).html();
                if(name == ''){name = "No Name"};
                var sn=$('td:eq(1)', nRow).html();
                if(sn){
                  var link = mr.getClientDetailLink(name, sn, '#tab_displays-tab');
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
                case "4ca3":
                	vendor="Epson"
                	break;
                case "170e":
                	vendor="Extron"
                	break;
                case "e11":
                	vendor="Compaq"
                	break;
                case "24d3":
                	vendor="ASK Proxima"
                	break;
                case "410c":
                	vendor="Philips"
                	break;
                case "15c3":
                	vendor="Eizo"
                	break;
                case "26cd":
                	vendor="iiyama"
                	break;
                }
                $('td:eq(3)', nRow).html(vendor)

                // Format manufactured from unix to human friendly and the title to relative
                date = $('td:eq(6)', nRow).html();
                if(moment(date, 'YYYY-MM', true).isValid())
                {
                	  var formatted='<time title="'+ moment(date).fromNow() + '" </time>' + moment(date).format("MMMM YYYY");
                	  $('td:eq(6)', nRow).html(formatted);
                }

                // Format timestamp from unix to relative and the title to timezone detail
                date = $('td:eq(8)', nRow).html();
                if(date)
                {
                	  var formatted='<time title="'+ moment.unix(date).format("LLLL (Z)") + '" </time>' + moment.unix(date).fromNow();
                	  $('td:eq(8)', nRow).html(formatted);
                }

            } //end fnCreatedRow

        }); //end oTable

	});
</script>

<?php $this->view('partials/foot'); ?>
