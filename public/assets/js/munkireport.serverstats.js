/*!
 * Serverstats for MunkiReport
 * requires nv.d3.js (https://github.com/novus/nvd3)
 */
drawServerPlots = function(hours) {

	try{
		if(serialNumber){}
	}
	catch(e){
		alert('Error: munkireport.serverstats.js - No serialNumber');
		return;
	}

	var colors = d3.scale.category20(),
		keyColor = function(d, i) {return colors(d.key)},
		dateformat = "L LT", // Moment.js dateformat
		charts = [], // Array holding all charts
		xTickCount = 4, // Amount of ticks on x axis
		siFormat = d3.format('0.2s'),
		byteFormat = function(d){ return siFormat(d) + 'B'},
		networkFormat = function(d){ return siFormat(d) + 'B/s'};

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
		$.ajax( appUrl + '/module/machine/report/' + serialNumber ),
		$.ajax( appUrl + '/module/servermetrics/get_data/' + serialNumber + '/' + hours ) )
	.done(function( a1, a2 )
	{
		// a1 and a2 are arguments resolved for the page1 and page2 ajax requests, respectively.
		// Each argument is an array with the following structure: [ data, statusText, jqXHR ]
		var maxMemory = Math.min(parseInt(a1[ 0 ]['physical_memory']), 48);
		var data = a2[ 0 ];

		var networkTraffic = [
			  {
				key: i18n.t('servermetrics.network.inbound_traffic'),
				values:[]
			  },
			  {
				key: i18n.t('servermetrics.network.outbound_traffic'),
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
				key: i18n.t('servermetrics.memory.usage'),
				values:[]
			  }
			],
			memoryPressure = [
			  {
				key: i18n.t('servermetrics.memory.pressure'),
				values: []
			  }
			],
			cachingServer = [
			  {
				key: i18n.t('servermetrics.caching.from_origin'),
				values:[]
			  },
			  {
				key: i18n.t('servermetrics.caching.from_peers'),
				values:[]
			  },
			  {
				key: i18n.t('servermetrics.caching.from_cache'),
				values:[]
			  }
			],
			connectedUsers = [
			  {
				key: i18n.t('servermetrics.sharing.afp_users'),
				values:[]
			  },
			  {
				key: i18n.t('servermetrics.sharing.smb_users'),
				values:[]
			  }
			]

			var datapoints = Object.keys(data).length,
					maxPoints = 500,
					skip = Math.ceil(datapoints / maxPoints),
					start = 0
					;

			for (var obj in data)
			{
				// Skip empty items
				if(data[obj][12] == 0){
					continue;
				}

				// Skip items (average would be better)
				start++;
				if( start % skip ){
					continue;
				}

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

			//console.log(memoryUsage[0].values.length)

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


			d3.select('#memory-usage')
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

			d3.select('#cpu-usage')
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

			chart.yAxis.tickFormat(networkFormat)
			  .showMaxMin(false);

			d3.select('#network-traffic')
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

			d3.select('#memory-pressure')
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

			chart.yAxis
				  	.ticks(4)
				  	.tickFormat(byteFormat)

			  .showMaxMin(false);

			d3.select('#caching-bytes-served')
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
				.yAxis
					.ticks(4)
					.tickFormat(d3.format('.0f'));

			d3.select('#sharing-connected-users')
				.datum(connectedUsers)
				.transition().duration(500)
				.call(chart)

			charts.push(chart.update);
			nv.utils.windowResize(chart.update);
			return chart;
		});


	});

};
