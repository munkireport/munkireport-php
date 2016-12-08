<div class="col-md-6">

	<div class="panel panel-default" id="memory-widget">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-lightbulb-o"></i> <span data-i18n="widget.memory.title"></span></h3>
		
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
  		var url = appUrl + '/module/machine/get_memory_stats';
  		d3.json(url, function(data) {
  			osData[0].values = data;
  			height = data.length * 26 + 40;
  			
  			chart.height(height);
  			d3.select('#memory-widget svg')
  				.attr('height', height)
  				.datum(osData)
  				.transition()
  				.duration(500)
  				.call(chart);
  				
  			chart.update();
  			
  		});
  	};
	
	nv.addGraph(function() {
	  chart = nv.models.multiBarHorizontalChart()
		  .x(function(d) { return d.label + ' GB' })
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

	  d3.select('#memory-widget svg')
		  .attr('height', height)
		  .datum(osData)
		  .call(chart);

		// visit page on click
		chart.multibar.dispatch.on("elementClick", function(e) {
			var label = e.data.label;
			window.location.href = appUrl + '/show/listing/hardware#' + encodeURIComponent('memory = ') + parseInt(label) + 'GB' ;
		});

		nv.utils.windowResize(chart.update);
		drawGraph();

	});
	
	// update chart data
	$(document).on('appUpdate', function(){drawGraph()});

	

});
</script>