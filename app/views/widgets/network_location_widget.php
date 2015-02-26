		<div class="col-lg-6 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-globe"></i> Network locations</h3>
				
				</div>

				<div class="panel-body">
					
					<svg style="height: 200px" id="ip-plot"></svg>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

		<script>

		$(document).on('appReady', function() {

			var parms = {}; // Override network settings in config.php

			//drawGraph("<?php echo url('module/reportdata/ip'); ?>", '#ip-plot', pieOptions, parms);
		    
		    var url = baseUrl + 'index.php?/module/reportdata/ip'
		    var chart;
		    d3.json(url, function(err, data){

			    var height = 200;
			    var width = 350;
			    nv.addGraph(function() {
			        var chart = nv.models.pieChart()
			            .x(function(d) { return d.key })
			            .y(function(d) { return d.y })
			            .donut(true)
			            .width(width)
			            .height(height)
			            .padAngle(.08)
			            .cornerRadius(5);
			        chart.title("100%");
			        chart.pie.donutLabelsOutside(true).donut(true);
			        d3.select("#ip-plot")
			            .datum(data)
			            .transition().duration(1200)
			            .attr('width', width)
			            .attr('height', height)
			            .call(chart);
			        return chart;
			    });

			});

		});
		</script>