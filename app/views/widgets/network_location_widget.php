		<div class="col-md-4">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-globe"></i> Network locations</h3>
				
				</div>

				<div id="ip-panel" class="panel-body text-center">
					
					<svg id="network-plot" style="width:100%; height: 300px"></svg>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

		<script>



		$(document).on('appReady', function() {

			//drawGraph("<?php echo url('module/reportdata/ip'); ?>", '#ip-plot', pieOptions, parms);
		    function isnotzero(point)
		    {
		    	return point.cnt > 0;
		    }

		    var url = appUrl + '/module/reportdata/ip'
		    var chart;
		    d3.json(url, function(err, data){

	    	    var height = 300;
			    var width = 350;

			   	// Filter data
		    	data = data.filter(isnotzero);

				nv.addGraph(function() {
					var chart = nv.models.pieChart()
					    .x(function(d) { return d.key })
					    .y(function(d) { return d.cnt })
					    .showLabels(false);

					chart.title("" + d3.sum(data, function(d){
						return d.cnt;
					}));

					chart.pie.donut(true);

					d3.select("#network-plot")
					    .datum(data)
					    .transition().duration(1200)
					    .style('height', height)
					    .call(chart);
					
					// Adjust title (count) depending on active slices
					chart.dispatch.on('stateChange.legend', function (newState) {
						var disabled = newState.disabled;
						chart.title("" + d3.sum(data, function(d, i){
							return d.cnt * !disabled[i];
						}));
			        });
											
					return chart;

			    });

			});

		});
		</script>