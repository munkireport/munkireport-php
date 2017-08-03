<h2 data-i18n="munki_conditions.client_tab"></h2>
  
<div id="munki_conditions-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>
  
  <div id="munki_conditions-view" class="row hide">
    <div class="col-md-7">
      <table id="#munki_conditions-table" class="table table-striped">
        <tr>
          <th data-i18n="munki_conditions.key"></th>
          <th data-i18n="munki_conditions.value"></th>
        </tr>
      </table>
    </div>
  </div>

<script>
$(document).on('appReady', function(e, lang) {

  // Get munki_conditions data
  $.getJSON( appUrl + '/module/munki_conditions/get_data/' + serialNumber, function( data ) {
    if( Object.keys(data).length === 0 ){
      $('#munki_conditions-msg').text(i18n.t('No Munki Condition Data Found!'));
    }
    else{
      // Hide
      $('#munki_conditions-msg').text('');
      $('#munki_conditions-view').removeClass('hide');
                  
      // Add data
      for (var key in data) {
        var element = '<tr><th>' + key + '</th><td>' + data[key] + '</td></tr>'
        $('#munki_conditions-table tbody').append(element)
      }
    }

  });
});
  
</script>