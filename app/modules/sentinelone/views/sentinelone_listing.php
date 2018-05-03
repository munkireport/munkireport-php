<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Sentinelone_model;
?>

<div class="container">

  <div class="row">

      <div class="col-lg-12">

          <h3><span data-i18n="security.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>

          <table class="table table-striped table-condensed table-bordered">
            <thead>
              <tr>
                <th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
                <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
                <th data-i18n="username" data-colname='reportdata.long_username'></th>
                <th data-i18n="sentinelone.agent_running" data-colname='sentinelone.agent_running'></th>
                <th data-i18n="sentinelone.active_threats" data-colname='sentinelone.active_threats_present'></th>
                <th data-i18n="sentinelone.self_protection_enabled" data-colname='sentinelone.self_protection_enabled'></th>
                <th data-i18n="sentinelone.enforcing_security" data-colname='sentinelone.enforcing_security'></th>
                <th data-i18n="sentinelone.agent_version" data-colname='sentinelone.agent_version'></th>
                <th data-i18n="sentinelone.mgmt_url" data-colname='sentinelone.mgmt_url'></th>
                <th data-i18n="sentinelone.agent_id" data-colname='sentinelone.agent_id'></th>
                <th data-i18n="sentinelone.last_seen" data-colname='sentinelone.last_seen'></th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-i18n="listing.loading" colspan="15" class="dataTables_empty"></td>
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

            // Look for a encrypted statement
                    if(d.search.value.match(/^agent_running = \d$/))
                    {
                        // Add column specific search
                        d.columns[3].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                        // Clear global search
                        d.search.value = '';
                    }

                    if(d.search.value.match(/^active_threats_present = \d$/))
                    {
                        // Add column specific search
                        d.columns[4].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                        // Clear global search
                        d.search.value = '';
                    }
                    if(d.search.value.match(/^enforcing_security = \d$/))
                    {
                        // Add column specific search
                        d.columns[5].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
                        // Clear global search
                        d.search.value = '';
                    }
                    if(d.search.value.match(/^self_protection_enabled = \d$/))
                    {
                        // Add column specific search
                        d.columns[6].search.value = d.search.value.replace(/.*(\d)$/, '= $1');
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
                // Update name in first column to link
                var name=$('td:eq(0)', nRow).html();
                if(name == ''){name = "No Name"};
                var sn=$('td:eq(1)', nRow).html();
                var link = mr.getClientDetailLink(name, sn, '#tab_sentinelone');
                $('td:eq(0)', nRow).html(link);

                //agent-running formatting
                var ar = $('td:eq(3)', nRow).html();
                $('td:eq(3)', nRow).html(function(){
                    if( ar == '1'){
                        return '<span class="label label-success">'+i18n.t('true')+'</span>';
                    } else if (ar == '0') {
                        return '<span class="label label-danger">'+i18n.t('false')+'</span>';
                    }
                });

                //active-threats formatting
                var at = $('td:eq(4)', nRow).html();
                $('td:eq(4)', nRow).html(function(){
                  if( at == '1'){
                        return '<span class="label label-danger">'+i18n.t('true')+'</span>';
                    } else if( at == '0'){
                        return '<span class="label label-success">'+i18n.t('false')+'</span>';
                    }
                });

                //enforcing-security formatting
                var es = $('td:eq(5)', nRow).html();
                $('td:eq(5)', nRow).html(function(){
                    if( es == '1'){
                        return '<span class="label label-success">'+i18n.t('true')+'</span>';
                    } else if (es == '0') {
                        return '<span class="label label-danger">'+i18n.t('false')+'</span>';
                    }
                });

                //self-protection-enabled formatting
                var sp = $('td:eq(6)', nRow).html();
                $('td:eq(6)', nRow).html(function(){
                    if( sp == '1'){
                        return '<span class="label label-success">'+i18n.t('true')+'</span>';
                    } else if (sp == '0') {
                        return '<span class="label label-danger">'+i18n.t('false')+'</span>';
                    }
                });

                // Format date
                var last_seen = parseInt($('td:eq(10)', nRow).html());
                 if (last_seen) {
                    // The data coming in is UTC. This calculates the timezone difference
                    var now = new Date;
                    var utc_timestamp = Date.UTC(now.getFullYear(),now.getMonth(), now.getDate(), now.getHours(), now.getMinutes(), now.getSeconds(), now.getMilliseconds());
                    var epoch_tz_diff = (now.getTime() - utc_timestamp) / 1000;
                    var adjusted_date = new Date(0);
                    var date = adjusted_date.setUTCSeconds(last_seen - epoch_tz_diff);
                    $('td:eq(10)', nRow).html('<span title="'+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span>');
                }
        }
    });
  });
</script>

<?php $this->view('partials/foot'); ?>
