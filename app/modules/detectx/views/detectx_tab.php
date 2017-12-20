<div id="detectx-tab"></div>
<h2 data-i18n="detectx.title"></h2>
<script>
$(document).on('appReady', function(){
  $.getJSON(appUrl + '/module/detectx/get_data/' + serialNumber, function(data){
    // Set count to detectx's number of issues
    $('#detectx-cnt').text(data[0].numberofissues);
    var skipThese = ['id','serial_number'];
    $.each(data, function(i,d){

      // Generate rows from data
      var rows = '';
      for (var prop in d){
        // Skip skipThese
        if(skipThese.indexOf(prop) == -1){
           if (d[prop] == '' || d[prop] == null){
           // Do nothing for empty values to blank them

           } else if(d[prop] == 'No Issues Detected'){
                   rows = rows + '<tr><th>'+i18n.t('detectx.listing.'+prop)+'</th><td>'+i18n.t('detectx.listing.noissues')+'</td></tr>';
           } else if(d[prop] == 'Clean'){
                   rows = rows + '<tr><th>'+i18n.t('detectx.listing.'+prop)+'</th><td>'+i18n.t('detectx.listing.clean')+'</td></tr>';

           } else if(prop == 'searchdate'){
                   var date = new Date(d[prop] * 1000);
                   rows = rows + '<tr><th>'+i18n.t('detectx.listing.'+prop)+'</th><td><span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span></td></tr>';

           } else if (prop == 'spotlightindexing' && d[prop] == 1){
                   rows = rows + '<tr><th>'+i18n.t('detectx.listing.'+prop)+'</th><td>'+i18n.t('detectx.listing.true')+'</td></tr>';
           } else if (prop == 'spotlightindexing' && d[prop] == 0){
                   rows = rows + '<tr><th>'+i18n.t('detectx.listing.'+prop)+'</th><td>'+i18n.t('detectx.listing.false')+'</td></tr>';

           } else{
                   rows = rows + '<tr><th>'+i18n.t('detectx.listing.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
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
