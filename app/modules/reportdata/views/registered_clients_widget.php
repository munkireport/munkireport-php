<div class="col-sm-12">

	<div class="panel panel-default">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-clock-o"></i> <span data-i18n="widget.registered.title"></span></h3>

		</div>

		<div class="panel-body">

			<svg id="chart1" style="width: 100%; height: 400px"></svg>

		</div>

	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).on('appReady', function() {
	var colors = d3.scale.category20();
    var keyColor = function(d, i) {return colors(d.key)};

    var url = appUrl + '/module/reportdata/new_clients'
    var chart;

    d3.json(url, function(err, data){

        var graphData = [],
            datelength = data.dates.length;

        for (var type in data.types)
        {
            var total = 0
                temp = {key: type, values: []};
            for (var i = 0; i < datelength; i++) {
                if (i.toString() in data.types[type]){
                    total = total + data.types[type][i];
                }
                temp.values.push([(new Date(data.dates[i])), total])
            }
            graphData.push(temp);
        }
		
        nv.addGraph(function() {
            chart = nv.models.stackedAreaChart()
                .useInteractiveGuideline(true)
                .x(function(d) { return d[0] })
                .y(function(d) { return d[1] })
                .controlLabels(i18n.t('chart.stackedarea', {returnObjectTrees: true}))
                .color(keyColor)
                .duration(300);
			chart.xAxis.tickFormat(function(d, e) {
				if(e == undefined){ return d}
				if(moment(d).month()){
					return moment(d).format("MMMM");
				}
				return moment(d).format("YYYY") 
			});			
			
            chart.xAxis.showMaxMin(false);

            chart.yAxis.showMaxMin(false);
			
            var tooltip = chart.interactiveLayer.tooltip;
			tooltip.headerFormatter(function (d) { 
				return moment(d).format('MMMM YYYY'); 
			});
			
			
            d3.select('#chart1')
                .datum(graphData)
                .transition().duration(1000)
                .call(chart)
                .each('start', function() {
                    setTimeout(function() {
                        d3.selectAll('#chart1 *').each(function() {
                            if(this.__transition__)
                                this.__transition__.duration = 1;
                        })
                    }, 0)
                });
            nv.utils.windowResize(chart.update);
            return chart;
        });
    });

});
</script>
