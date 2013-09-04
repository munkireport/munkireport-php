// Global functions

// Label formatter for pie charts
function labelFormatter(label, series) {
	return "<div style='font-size:150%; text-align:center; padding:2px; color:white;'>" + series.data[0][1] + "</div>";
}

// Filesize formatter
function fileSize(size, decimals) {
	if(decimals == undefined){decimals = 0};
	var i = Math.floor( Math.log(size) / Math.log(1024) );
	return ( size / Math.pow(1024, i) ).toFixed(decimals) * 1 + ' ' + ['', 'K', 'M', 'G', 'T'][i] + 'B';
}

// Plural formatter
String.prototype.pluralize = function(count, plural)
{
  if (plural == null)
    plural = this + 's';

  return (count == 1 ? this : plural) 
}