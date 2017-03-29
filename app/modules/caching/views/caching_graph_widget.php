<div class="col-sm-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-clock-o"></i>
			    <span data-i18n="caching.widget_title"></span>
			    <list-link data-url="/show/listing/caching/caching"></list-link>
			</h3>
		</div>
		<div class="panel-body">
			<svg id="cachingchart" style="width: 100%; height: 400px"></svg>
		</div>
	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).on('appReady', function() {

    var url = appUrl + '/module/caching/caching_graph'
    var chart;
    var data;

    d3.json(url, function(err, data){
                
        function graphData() {
            
            var cache = [],
                origin = [],
                purged = []
                graphData = [],
                datelength = data.dates.length;
            
            for (var i = 1; i < datelength ; i++) {
                origin.push({x: (new Date(data.dates[i])), y: data.origin[i]});
                purged.push({x: (new Date(data.dates[i])), y: data.purged[i]});
                cache.push({x: (new Date(data.dates[i])), y: data.cache[i]});
            }
            
//            alert(JSON.stringify(origin));
//            alert(moment(data.dates[i]).add(1, 'day'));
            
            return [
                {
                    values: cache,
                    key: i18n.t("caching.from_cache"),
                    color: "#04E61A",
                    strokeWidth: 3
                },
                {
                    values: origin,
                    key: i18n.t("caching.from_origin"),
                    color: "#FF0066",
                    strokeWidth: 3
                },
                {
                    values: purged,
                    key: i18n.t("caching.purgedbytes"),
                    color: "#FF7F0E",
                    strokeWidth: 3
                }
            ];
        }

        nv.addGraph(function() {
                
            chart = nv.models.lineChart()
                .useInteractiveGuideline(true)
                .duration(300);
            
			chart.xAxis.tickFormat(function(d, e) {
				if(e == undefined){ return d }
				return moment(d).format("MMMM DD");
			});
            
            chart.yAxis.tickFormat(function(d, e) {
				if(e == undefined){ return d }
				return fileSize(d, 2);
			});

            chart.xAxis.showMaxMin(false);
            chart.yAxis.showMaxMin(false);

            var tooltip = chart.interactiveLayer.tooltip;
			tooltip.headerFormatter(function (d) {
				return moment(d).format("MMMM DD");
			});

            d3.select('#cachingchart')
                .datum(graphData())
                .call(chart)
                .transition().duration(1000)
                .each('start', function() {
                    setTimeout(function() {
                        d3.selectAll('#cachingchart *').each(function() {
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
