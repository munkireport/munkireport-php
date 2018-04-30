<h2 data-i18n="sentinelone.client_tab"></h2>
  
<div id="sentinelone-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>
  
  <div id="sentinelone-view" class="row hide">
    <div class="col-md-7">
      <table id="sentinelone-table" class="table table-striped">
        <tr>
          <th data-i18n="sentinelone.key"></th>
          <th data-i18n="sentinelone.value"></th>
        </tr>
      </table>
    </div>
  </div>

<script>
$(document).on('appReady', function(e, lang) {

  // Get munki_facts data
  $.getJSON( appUrl + '/module/sentinelone/get_data/' + serialNumber, function( data ) {
    if( Object.keys(data).length === 0 ){
      $('#sentinelone-msg').text(i18n.t('sentinelone.not_found'));
    }
    else{
      // Hide
      $('#sentinelone-msg').text('');
      $('#sentinelone-view').removeClass('hide');
                  
      // Add data
      for (var key in data) {
        $('#sentinelone-table tbody').append(
            $('<tr/>').append(
                $('<th/>').text(key),
                $('<td/>').text(data[key])
            )
        )
      }
    }

  });
});
  
</script>
