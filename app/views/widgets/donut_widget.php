<div class="col-lg-6 col-md-6">
    <div class="panel panel-default" id="<?=$widget_id?>">
        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="fa <?=$icon?>"></i>
                <span data-i18n="<?=$i18n_title?>"></span>
                <list-link data-url="<?=$listing_link?>"></list-link>
            </h3>
        </div>

        <div class="panel-body text-center">
            <svg id="<?=$widget_id?>-plot" style="width:100%; height:300px;"></svg>
        </div>
    </div>
</div>

<script>
$(document).on('appUpdate', function() {

    var widgetId = "<?=$widget_id?>";
    var apiUrl = "<?=$api_url?>";

    // Fetch JSON data from API
    d3.json(appUrl + apiUrl, function(err, data){

        if (err || !data || !data.length) {
            d3.select("#" + widgetId + "-plot")
              .append("text")
              .attr("x", 150)
              .attr("y", 150)
              .attr("text-anchor","middle")
              .text("No data");
            return;
        }

        var total = d3.sum(data, function(d){ return d.count; });

        nv.addGraph(function() {

            var chart = nv.models.pieChart()
                .x(function(d) { return d.label; })
                .y(function(d) { return d.count; })
                .showLabels(false)
                .donut(true)
                .color(d3.scale.category10().range());

            chart.title("" + total);

            chart.tooltip.valueFormatter(function(d){
                var percent = (d / total * 100).toFixed(1);
                return d + ' (' + percent + '%)';
            });

            d3.select("#" + widgetId + "-plot")
                .datum(data)
                .transition().duration(1000)
                .call(chart);

            return chart;
        });
    });
});
</script>
