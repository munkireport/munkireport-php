<style>svg{height: 400px; width:100%;}</style>

<h2 data-i18n="memory.usage"></h2>
<div class="comment" data-section="chart1"></div>
<svg id="chart1"></svg>

<h2 data-i18n="cpu.usage"></h2>
<svg id="chart2"></svg>

<h2 data-i18n="network.traffic"></h2>
<svg id="chart3"></svg>

<h2 data-i18n="memory.pressure"></h2>
<svg id="chart4"></svg>

<h2 data-i18n="caching.bytes_served"></h2>
<svg id="chart5"></svg>

<h2 data-i18n="sharing.connected_users"></h2>
<svg id="chart6"></svg>


	<script>

function sortByDateAscending(a, b) {
	// Dates will be cast to numbers automagically:
	return a.x - b.x;
}


$(document).on('appReady', function(e, lang) {

	var colors = d3.scale.category20(),
		serialNumber = '<?php echo $serial_number?>',
		keyColor = function(d, i) {return colors(d.key)},
		dateformat = "L LT", // Moment.js dateformat
		charts = [],
		xTickCount = 4; // Amount of ticks on x axis

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		// Call update on all charts when #serverstats
		// becomes active so nvd3 knows about the width
		// (hidden tabs have no width)
		if($(e.target).attr('href') == '#serverstats')
		{
			charts.forEach(function(callback) {
				callback();
			});
		}
	})


	$.when( 
		$.ajax( baseUrl + 'index.php?/module/machine/report/' + serialNumber ), 
		$.ajax( baseUrl + 'index.php?/module/servermetrics/get_data/' + serialNumber + '/24' ) )
	.done(function( a1, a2 )
	{
		// a1 and a2 are arguments resolved for the page1 and page2 ajax requests, respectively.
		// Each argument is an array with the following structure: [ data, statusText, jqXHR ]
		var maxMemory = Math.min(parseInt(a1[ 0 ]['physical_memory']), 48);
		var data = a2[ 0 ];
		var networkTraffic = [
			  {
				key: i18n.t('network.inbound_traffic'),
				values:[]
			  },
			  {
				key: i18n.t('network.outbound_traffic'),
				color: "#ff7f0e",
				values:[]
			  }
			],
			cpuUsage = [
			  {
				key: i18n.t('user.user'),
				values:[]
			  },
			  {
				key: i18n.t('system.system'),
				color: "#ff7f0e",
				values:[]
			  }
			],
			memoryUsage = [
			  {
				key: i18n.t('memory.usage'),
				values:[]
			  }
			],
			memoryPressure = [
			  {
				key: i18n.t('memory.pressure'),
				values: []
			  }
			],
			cachingServer = [
			  {
				key: i18n.t('caching.from_origin'),
				values:[]
			  },
			  {
				key: i18n.t('caching.from_peers'),
				values:[]
			  },
			  {
				key: i18n.t('caching.from_cache'),
				values:[]
			  }
			],
			connectedUsers = [
			  {
				key: i18n.t('sharing.afp_users'),
				values:[]
			  },
			  {
				key: i18n.t('sharing.smb_users'),
				values:[]
			  }
			]

		for (var obj in data)
		{
			var date = new Date (obj.replace(' ', 'T'))
			
			cpuUsage[0].values.push({x: date, y: data[obj][5]}) // User
			cpuUsage[1].values.push({x: date, y: data[obj][12]}) // System
			networkTraffic[0].values.push({x: date, y: data[obj][10]}) // Inbound
			networkTraffic[1].values.push({x: date, y: data[obj][13]}) // Outbound
			memoryPressure[0].values.push({x: date, y: data[obj][11]})
			memoryUsage[0].values.push({x: date, y: data[obj][6] + data[obj][7]}) // Wired + Active
			cachingServer[0].values.push({x: date, y: data[obj][3]}) // From Origin
			cachingServer[1].values.push({x: date, y: data[obj][4]}) // From Peers
			cachingServer[2].values.push({x: date, y: data[obj][2]}) // From Cache
			connectedUsers[0].values.push({x: date, y: data[obj][0]}) // AFP
			connectedUsers[1].values.push({x: date, y: data[obj][1]}) // SMB
		}

		// Memory Usage
		nv.addGraph(function() {
			chart = nv.models.lineChart()
				.y(function(d) { return d.y ? d3.round(d.y / Math.pow(1024, 3), 1): null })
				.yDomain([0, maxMemory])
				.duration(300);

			chart.xAxis
			  .ticks(xTickCount)
			  .tickFormat(function(d) { return moment(d).format(dateformat) })
			  .showMaxMin(false);

			chart.yAxis
			.ticks(6)
			  .tickFormat(function(d){return d + ' GB'})
			  .showMaxMin(false)
			  

			d3.select('#chart1')
				.datum(memoryUsage)
				.transition().duration(500)
				.call(chart)

			charts.push(chart.update);
			nv.utils.windowResize(chart.update);
		});

		// CPU Usage
		nv.addGraph(function() {
			chart = nv.models.lineChart()
				.y(function(d) { return d.y ? d.y : null })
				.duration(300);
			chart.xAxis
			  .ticks(xTickCount)
			  .tickFormat(function(d) { return moment(d).format(dateformat) })
			  .showMaxMin(false);

			chart.yDomain([0,1])
			  .yAxis
				  .ticks(4)
				  .tickFormat(d3.format('%'));

			d3.select('#chart2')
				.datum(cpuUsage)
				.transition().duration(500)
				.call(chart)

			charts.push(chart.update);
			nv.utils.windowResize(chart.update);
			return chart;
		});

		// Network traffic
		nv.addGraph(function() {
			chart = nv.models.lineChart()
				.y(function(d) { return d.y ? d.y : null })
				.duration(300);
			chart.xAxis
			  .ticks(xTickCount)
			  .tickFormat(function(d) { return moment(d).format(dateformat) })
			  .showMaxMin(false);

			chart.yAxis.tickFormat(d3.format('s'))
			  .showMaxMin(false);

			d3.select('#chart3')
				.datum(networkTraffic)
				.transition().duration(500)
				.call(chart)

			charts.push(chart.update);
			nv.utils.windowResize(chart.update);
			return chart;
		});

		// Memory Pressure
		nv.addGraph(function() {
			chart = nv.models.lineChart()
				.y(function(d) { return d.y ? d.y : null })
				.duration(300);
			chart.xAxis
			  .ticks(xTickCount)
			  .tickFormat(function(d) { return moment(d).format(dateformat) })
			  .showMaxMin(false);

			chart.yDomain([0,1])
			  .yAxis
			  	.ticks(4)
			  	.tickFormat(d3.format('%'));

			d3.select('#chart4')
				.datum(memoryPressure)
				.transition().duration(500)
				.call(chart)

			charts.push(chart.update);
			nv.utils.windowResize(chart.update);
			return chart;
		});

		// Caching server
		nv.addGraph(function() {
			chart = nv.models.lineChart()
				.y(function(d) { return d.y ? d.y : null })
				.duration(300);
			chart.xAxis
			  .ticks(xTickCount)
			  .tickFormat(function(d) { return moment(d).format(dateformat) })
			  .showMaxMin(false);

			chart.yAxis.tickFormat(d3.format('s'))
			  .showMaxMin(false);

			d3.select('#chart5')
				.datum(cachingServer)
				.transition().duration(500)
				.call(chart)

			charts.push(chart.update);
			nv.utils.windowResize(chart.update);
			return chart;
		});

		// File Sharing Users
		nv.addGraph(function() {
			chart = nv.models.lineChart()
				.y(function(d) { return d.y ? d.y : null })
				.duration(300);
			chart.xAxis
			  .ticks(xTickCount)
			  .tickFormat(function(d) { return moment(d).format(dateformat) })
			  .showMaxMin(false);

			chart.yDomain([0,1])
			  .yAxis.tickFormat(d3.format('.0f'));

			d3.select('#chart6')
				.datum(connectedUsers)
				.transition().duration(500)
				.call(chart)

			charts.push(chart.update);
			nv.utils.windowResize(chart.update);
			return chart;
		}); 


	});


});
</script>