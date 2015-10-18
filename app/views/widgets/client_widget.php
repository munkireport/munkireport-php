<div class="col-lg-4 col-md-6">

	<div class="panel panel-default">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-group"></i> Active clients</h3>
		
		</div>

		<div class="panel-body text-center">
			
			<svg id="test1" class="center-block"></svg>
		
		</div>
	
	</div>
	
</div>

<script>

$(document).on('appReady', function() {

	var testdata1 = [
		{ key: "Active", y: 0 },
		{ key: "Inactive", y: 100 }
	];
	var arcRadius1 = [
		{ inner: .75, outer: 1.25 },
		{ inner: 0.85, outer: 1.15 }
	];
	var colors = ["green", "gray"];
	var chart;
	var height = 350;
	var width = 350;
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
		
		chart.title("0000");
		
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
			chart.title(data.total);

			d3.select('#test1').datum(testdata1).transition().duration(500).call(chart);
			nv.utils.windowResize(chart.update);
		});

	};
	
	
	// update chart data
	$(document).on('appUpdate', function(){drawGraph()});
		
});
</script>



