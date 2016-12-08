<div class="col-lg-6 col-md-6">

	<div class="panel panel-default" id="hardware-type-widget">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-desktop"></i> Hardware type breakdown</h3>

		</div>

		<div class="panel-body">

			<svg style="width:100%"></svg>

		</div>

	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).on('appReady', function(e, lang) {
	
	var height = 400;
	var chart;
	var osData = [{
			  "key": " ",
			  "color": "#1f77b4",
			  "values": []
		  }];
		  
	// Get data and update graph
	var drawGraph = function(){
		var url = appUrl + '/module/machine/hw';
		d3.json(url, function(data) {
			osData[0].values = data;
			
			d3.select('#hardware-type-widget svg')
				.datum(osData)
				.transition()
				.duration(500)
				.call(chart);
				
			chart.update();
			
		});
	};

	
	nv.addGraph(function() {
		chart = nv.models.discreteBarChart()
			.x(function(d) { return d.label })
			.y(function(d) { return d.count })
			.staggerLabels(true)
			.valueFormat(d3.format(''))
			.tooltips(false)
			.showValues(true)
			.height(height);
	
		chart.yAxis
			.tickFormat(d3.format(''));

	  d3.select('#hardware-type-widget svg')
		  .attr('height', height)
	    .datum(osData)
	    .transition().duration(500)
	    .call(chart)
	    ;

	  nv.utils.windowResize(chart.update);
	  drawGraph();

	});
	
		
	// update chart data
	$(document).on('appUpdate', function(){drawGraph()});

});
</script>
