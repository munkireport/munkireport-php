<div id="detectx-tab"></div>
<h2 data-i18n="detectx.listing.clienttab"></h2>

<script>
$(document).on('appReady', function(){
  $.getJSON(appUrl + '/module/detectx/get_data/' + serialNumber, function(data){
    // Set count to detectx's number of issues
    $('#detectx-cnt').text(data[0].numberofissues);
    var skipThese = ['id','serial_number'];
    $.each(data, function(i,d){

      // Generate rows from data
      var rows = ''
      for (var prop in d){
        // Skip skipThese
        if(skipThese.indexOf(prop) == -1){
          if(prop == 'searchdate'){
            var reporteddate = d[prop];
            var date = new Date(reporteddate * 1000)
            rows = rows + '<tr><th>'+i18n.t('detectx.listing.'+prop)+'</th><td>'+moment(date).format('llll')+'</td></tr>';
          }
          else {
            if(prop == 'issues'){
              var issue = '';
              issue = d[prop].split(';').join('<br />');
              rows = rows + '<tr><th>'+i18n.t('detectx.listing.'+prop)+'</th><td>'+issue+'</td></tr>';
            }
            else{
              rows = rows + '<tr><th>'+i18n.t('detectx.listing.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
            }
          }
        }
      }
      $('#detectx-tab')
      .append($('<table style="max-width: 900px">')
      .addClass('table table-responsive table-striped table-condensed')
      .append($('<tbody>')
      .append(rows)))
    })

  });
});
</script>
