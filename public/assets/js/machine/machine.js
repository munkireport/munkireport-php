
var memoryFormatter = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row);
    col.text(parseInt(col.text()) + ' GB');
}

var memoryFilter = function(colNumber, d){

    // Look for 'between' statement todo: make generic
    if(d.search.value.match(/^\d+GB memory \d+GB$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = d.search.value.replace(/(\d+GB) memory (\d+GB)/, function(m, from, to){
            return ' BETWEEN ' + parseInt(from) + ' AND ' + parseInt(to)
        });
        // Clear global search
        d.search.value = '';
    }

    // Look for a bigger/smaller/equal statement
    if(d.search.value.match(/^memory [<>=] \d+GB$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = d.search.value.replace(/.*([<>=] )(\d+GB)$/, function(m, o, content){
            return o + parseInt(content)
        });
        // Clear global search
        d.search.value = '';
    }
}
