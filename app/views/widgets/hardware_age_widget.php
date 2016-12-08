<div class="col-sm-6">

	<div class="panel panel-default" id='hardware-age-widget'>

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-clock-o"></i> <span data-i18n="widget.age.title"></span></h3>

		</div>

		<div class="panel-body">

			<svg style="width:100%"></svg>

		</div>

	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).on('appReady', function() {
	
	var height = 400;
	var chart;
	var osData = [{
			  "key": " ",
			  "color": "#1f77b4",
			  "values": []
		  }];

	nv.addGraph(function() {
	  chart = nv.models.multiBarHorizontalChart()
		  .x(function(d) { return d.label + ' ' + i18n.t('date.year')})
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

	  d3.select('#hardware-age-widget svg')
		  .attr('height', height)
		  .datum(osData)
		  .call(chart);

		// visit page on click
		chart.multibar.dispatch.on("elementClick", function(e) {
			var label = e.data.physical_memory;
			window.location.href = appUrl + '/show/listing/warranty';
		});

		nv.utils.windowResize(chart.update);
		drawGraph();

	});
	
	
	// Get data and update graph
	var drawGraph = function(){
		var url = appUrl + '/module/warranty/age';
		d3.json(url, function(data) {
			osData[0].values = data;
			height = data.length * 26 + 40;
			
			chart.height(height);
			d3.select('#hardware-age-widget svg')
				.attr('height', height)
				.datum(osData)
				.transition()
				.duration(500)
				.call(chart);
				
			chart.update();
			
		});
		
		// update chart data
		$(document).on('appUpdate', function(){drawGraph()});

	};


	// Clone barOptions
    // var myOptions = jQuery.extend(true, {}, horBarOptions);
	// myOptions.legend = {show: false}
	// myOptions.callBack = resizeAgeBox;
	// myOptions.reverseColors = true;
    // myOptions.yaxis.tickFormatter = function(v, obj){//(v, {min : axis.min, max : axis.max})
	// 	label = obj.data[v].label
	// 	return '<a class = "btn btn-default btn-xs" href="<?php echo url('show/listing/warranty'); ?>">' + label + ' ' + i18n.t('date.year') + '</a>';
	// }



});
</script>
