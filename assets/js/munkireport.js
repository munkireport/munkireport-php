// Global functions

// Debug function to dump js objects
function dumpj(obj)
  {
    type = typeof(obj)
    if(type == 'object')
    {
      var out = {}
      for (var key in obj) {
        type = typeof(obj[key])
        if ( type == 'object')
        {
          out[key] = 'object'
        }
        else{
          out[key] = obj[key];
        }
      }
    }
    else{
      out = obj
    }
    alert(JSON.stringify(out));
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

// Draw a formatted graph with flotr2
// Handle resize events
// url: the url where the data comes from
// id: the jquery string for the domelement (eg #barchart)
// options: the flotr2 options for a particular chart
// parms: extra parameters to send to the server
function drawGraph(url, id, options, parms)
{
	$.getJSON(url, {'req':JSON.stringify(parms)}, function(data) {
		Flotr.draw($(id)[0], data, options);
		var myWidth = $(id).width()
		// Bind resize
		$(window).resize(function() {
			if( $(id).width() != myWidth)
			{
				Flotr.draw($(id)[0], data, options);
				myWidth = $(id).width()
			}
		});
	});
}

// Global variables
var barOptions = {
		    
	    	bars: {
	            show: true,
	            lineWidth: 0,
	            fillOpacity: 0.8,
	            barWidth: 0.9
			},
			markers: {
				show: true,
				position: 'ct'
			},
			xaxis:
			{
				showLabels: false
			},
			grid:
			{
				verticalLines : false,
			},
		    legend: {
				position : 'ne',
				backgroundColor: 'white',
				outlineColor: 'white'
			},
			shadowSize: 0
			
	    },
	    horBarOptions = {
		    
	    	bars: {
	            show: true,
	            lineWidth: 0,
	            fillOpacity: 0.8,
	            barWidth: 0.9,
	            horizontal: true
			},
			markers: {
				show: true,
				position: 'm',
				labelFormatter: function(obj){
					return (Math.round(obj.x*100)/100)+'';
				}
			},
			yaxis:
			{
				noticks: 1,
				tickFormatter: function (y) {
        			return y;
			      }
			},
			grid:
			{
				verticalLines : false,
			},
		    legend: {
				position : 'ne',
				backgroundColor: 'white',
				outlineColor: 'white'
			},
			shadowSize: 0
			
	    },
		pieOptions = {
				    
	        pie: {
	            show: true,
	            explode: 5,
	            sizeRatio: .9,
	            labelRadius: 1/3,
	            labelFormatter: function(total, value) {
					return "<div style='font-size:150%; text-align:center; padding:2px; color:white;'>" + value + "</div>";
				}
				
	        },
	        shadowSize: 0,
	        grid : {
		      verticalLines : false,
		      horizontalLines : false,
		      outlineWidth: 0
		    },
			xaxis : { showLabels : false },
		    yaxis : { showLabels : false },
		    legend: {
				position : 'ne',
				backgroundColor: 'white',
				outlineColor: 'white'
			},
	    };