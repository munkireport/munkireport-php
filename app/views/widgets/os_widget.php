<div class="col-md-6">

	<div class="panel panel-default" id="os-widget">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-apple"></i> OS breakdown</h3>
		
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

	nv.addGraph(function() {
	  chart = nv.models.multiBarHorizontalChart()
	      .x(function(d) { return mr.integerToVersion(d.label) })
	      .y(function(d) { return d.count })
	      .margin({top: 20, right: 10, bottom: 20, left: 70})
	      .showValues(true)
		  .valueFormat(d3.format(''))
	      .tooltips(false)
	      .showControls(false)
		  .showLegend(false)
		  .height(height);

	  chart.yAxis
	      .tickFormat(d3.format(''));

	  d3.select('#os-widget svg')
		  .attr('height', height)
	      .datum(osData)
	      .call(chart);

		// visit page on click
		chart.multibar.dispatch.on("elementClick", function(e) {
		    var label = mr.integerToVersion(e.data.label);
			window.location.href = appUrl + '/show/listing/clients#' + label;
		    //console.log(e.point.label);
		});

		nv.utils.windowResize(chart.update);
		drawGraph();

	});
	
	
	// Get data and update graph
	var drawGraph = function(){
		var url = appUrl + '/module/machine/os';
		d3.json(url, function(data) {
			osData[0].values = data;
			height = data.length * 26 + 40;
			
			chart.height(height);
			console.log(data.length)
			d3.select('#os-widget svg')
				.attr('height', height)
				.datum(osData)
				.transition()
				.duration(500)
				.call(chart);
				
			chart.update();
			
			
		});

	};
	
	// update chart data
	$(document).on('appUpdate', function(){drawGraph()});

});



</script>