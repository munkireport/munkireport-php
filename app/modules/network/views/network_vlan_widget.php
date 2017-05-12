		<div class="col-lg-12">

			<div class="panel panel-default" id="network-vlan-widget">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-globe"></i>
					    <span data-i18n="network.widget.network_vlan"></span>
					    <list-link data-url="/show/listing/network/network"></list-link>
					</h3>

				</div>

				<div class="panel-body" style="overflow-x: auto; padding: 0">

					<svg style="height: 200px" id="ip-plot"></svg>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

		<script>

		$(document).on('appReady', function() {

			//drawGraph("<?php echo url('module/reportdata/ip'); ?>", '#ip-plot', pieOptions, parms);

		    var url = appUrl + '/module/network/routers'
		    var chart;
		    d3.json(url, function(err, data){

		    	var minWidth = data.length * 60 + 175
		    	var panelWidth = d3.select('#network-vlan-widget .panel-body').style("width");

		    	var width = Math.max(minWidth, parseInt(panelWidth))

		    	// Sort data on count
		    	data.sort(function(a,b){
		    		return b.cnt - a.cnt
		    	});

	    		nv.addGraph(function() {
			        var chart = nv.models.discreteBarChart()
			            .x(function(d) { return d.key })
			            .y(function(d) { return d.cnt })
			            .staggerLabels(true)
			            .valueFormat(d3.format(','))
			            .showValues(true)
						.color(mr.getGraphPref('barColor', 'network-vlan-widget', []))
			            .duration(250)
				        ;
					chart.tooltip.enabled(false);
			        d3.select('#ip-plot')
			            .datum([{key: 'network', values:data}])
			            .style("width", width + "px")
			            .call(chart)
			            ;
			        nv.utils.windowResize(function(){
			        	panelWidth = d3.select('#network-vlan-widget .panel-body').style("width");
						width = Math.max(minWidth, parseInt(panelWidth))
						d3.select('#ip-plot')
							.style("width", width + "px")
							.call(chart)
			        	//chart.update
			        });

			        return chart;
		       	});
			});

		});
		</script>
