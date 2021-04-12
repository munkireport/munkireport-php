
var uptimeFormatter = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
    uptime = parseInt(col.text());
    if(uptime == 0) {
        col.html('')
    }
    else {
        col.html('<span title="'+i18n.t('boot_time')+': '+moment().subtract( uptime, 'seconds').format('llll')+'">'+moment().subtract(uptime, 'seconds').fromNow(true)+'</span>');
    }
}

var uptimeFilter = function(colNumber, d){

    // Look for 'between' statement todo: make generic
    if(d.search.value.match(/^\d+d uptime \d+d$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = d.search.value.replace(/(\d+)d uptime (\d+)d/, function(m, from, to){
            return ' BETWEEN ' + (parseInt(from)*86400) + ' AND ' + (parseInt(to)*86400)
        });
        // Clear global search
        d.search.value = '';
    }

    // Look for a bigger/smaller/equal statement
    if(d.search.value.match(/^uptime [<>=] \d+d$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = d.search.value.replace(/.*([<>=] )(\d+)d$/, function(m, o, content){
            return o + (parseInt(content)*86400)
        });
        // Clear global search
        d.search.value = '';
    }
}
