<div class="col-lg-4 col-md-6">

	<div class="panel panel-default" id="client-widget">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-group"></i>
				<span data-i18n="client.activity"></span>
			</h3>
		
		</div>

		<div class="panel-body">
			
			<svg id="test1" class="center-block"></svg>
			<div class="text-muted text-center">
				<span data-i18n="client.total"></span>: <span class="total-clients"></span>
				|
				<span data-i18n="client.hour"></span>: <span class="hour-clients"></span>
			</div>

		</div>
	
	</div>
	
</div>

<script>

$(document).on('appReady', function() {

	// Add tooltip
	$('#client-widget>div.panel-heading')
		.attr('title', i18n.t('client.panel_title'))
		.tooltip();
	
	var active = i18n.t('active'),
		inactive = i18n.t('inactive');
	
	var testdata1 = [
		{ key: active, y: 0 },
		{ key: inactive, y: 100 }
	];
	var arcRadius1 = [
		{ inner: .75, outer: 1.25 },
		{ inner: 0.85, outer: 1.15 }
	];
	var colors = ["green", "gray"];
	var chart;
	var height = 258;
	var width = 258;
	nv.addGraph(function () {
		chart = nv.models.pieChart()
			.x(function (d) { return d.key })
			.y(function (d) { return d.y })
			.donut(true)
			.showLabels(false)
			.width(width)
			.height(height)
			.growOnHover(false)
			.arcsRadius(arcRadius1);
		
		chart.title("    ");
		
		chart.legend.dispatch.legendClick = function(d, i){
		//redraw
			console.log(d)
			return false
		};

		
		d3.select("#test1")
			.datum(testdata1)
			.attr('width', width)
			.attr('height', height)
			.call(chart);
		
		drawGraph();

	});
	
	// Get data and update graph
	drawGraph = function(){
		var url = appUrl + '/module/reportdata/get_lastseen_stats';
		d3.json(url, function(data) {
			testdata1[0].y = data.lastmonth // Active
			testdata1[1].y = data.total - data.lastmonth // Inactive
			chart.title(data.lastmonth);

			d3.select('#test1').datum(testdata1).transition().duration(500).call(chart);
			nv.utils.windowResize(chart.update);
			
			$('#client-widget span.total-clients').text(data.total);
			$('#client-widget span.hour-clients').text(data.lasthour);
		});

	};
	
	
	// update chart data
	$(document).on('appUpdate', function(){drawGraph()});
		
});
</script>



