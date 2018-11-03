<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
  new Displays_info_model;
  new Machine_model;
?>

<div class="container">

  <div class="row">

	<div class="col-lg-12">

	<h3><span data-i18n="displays_info.reporttitle"></span> <span id="total-count" class='label label-primary'>…</span></h3>

	  <table class="table table-striped table-condensed table-bordered">

		<thead>
		  <tr>
			<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
			<th data-i18n="displays_info.machineserial" data-colname='reportdata.serial_number'></th>
			<th data-i18n="type" data-colname='displays.type'></th>
			<th data-i18n="displays_info.vendor" data-colname='displays.vendor'></th>
			<th data-i18n="displays_info.model" data-colname='displays.model'></th>
			<th data-i18n="serial" data-colname='displays.display_serial'></th>
			<th data-i18n="displays_info.manufactured" data-colname='displays.manufactured'></th>
			<th data-i18n="displays_info.nativeresolution" data-colname='displays.native'></th>
			<th data-i18n="displays_info.detected" data-sort="desc" data-colname='displays.timestamp'></th>
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
                url: appUrl + '/datatables/data',
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
                status = status == 1 ? i18n.t('displays_info.external') : //ex
                (status == '0' ? i18n.t('displays_info.internal') : '')
                $('td:eq(2)', nRow).html(status)

                // Translating vendors column
                //todo: find how the hell Apple translates the EDID/DDC to these values
                // http://ftp.netbsd.org/pub/NetBSD/NetBSD-current/src/sys/dev/videomode/ediddevs
                // https://github.com/GNOME/gnome-desktop/blob/master/libgnome-desktop/gnome-pnp-ids.c
                // https://www.opensource.apple.com/source/xnu/xnu-124.7/iokit/Families/IOGraphics/AppleDDCDisplay.cpp
                var vendor=$('td:eq(3)', nRow).html();
                $('td:eq(3)', nRow).html(mr.display_vendors[vendor] || vendor);

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
