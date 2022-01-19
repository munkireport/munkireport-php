var eventMessageCombined = function(colNumber, row){
    // assumes colNumber is module, colNumber + 1 is message and
    // colNumber + 2 is data
    item = {
      module: $('td:eq('+colNumber+')', row).text(),
      msg: $('td:eq('+(colNumber+1)+')', row).text(),
      data: $('td:eq('+(colNumber+2)+')', row).text()
    }
    formatEventData(item);
    $('td:eq('+(colNumber+1)+')', row).text(item.msg)
    $('td:eq('+(colNumber+2)+')', row).hide();
}

// Format event data
var formatEventData = function(item){
  
  item.tab = '#tab_summary';
  
  if(item.module == 'munkireport' || item.module == 'managedinstalls'){
    item.tab = '#tab_munki';
    item.module = '';
    item.data = item.data || '{}';
    item.msg = i18n.t(item.msg, JSON.parse(item.data));
  }
  else if(item.module == 'diskreport'){
    item.tab = '#tab_storage-tab';
    item.module = '';
    item.data = item.data || '{}';
    item.msg = i18n.t(item.msg, JSON.parse(item.data));
  }
  else if(item.module == 'reportdata'){
    item.msg = i18n.t(item.msg);
  }
  else if(item.module == 'certificate'){
    item.tab = '#tab_certificate-tab';
    item.module = '';
    item.data = item.data || '{}';
    var parsedData = JSON.parse(item.data);
    // Convert unix timestamp to relative time
    parsedData.moment = moment(parsedData.timestamp * 1000).fromNow();
    // console.log(parsedData)
    item.msg = i18n.t(item.msg, parsedData);
  }
}
