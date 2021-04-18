<div class="col-lg-4 col-md-6">
	<div class="card" id="client-widget">
        <div class="card-header">
            <i class="fa fa-group"></i>
            <span data-i18n="client.activity"></span>
            <a href="/show/listing/reportdata/clients" class="pull-right"><i class="fa fa-list"></i></a>
        </div>
        <div class="card-body d-flex flex-column justify-content-center">
            <div class="d-flex justify-content-center">
                <svg id="test1" class="center-block" style="width:258px; height: 258px"></svg>
            </div>
            <div class="text-muted text-center">
                <span data-i18n="client.total"></span>: <span class="total-clients"></span> <span class="total-change"></span>
                |
                <span data-i18n="client.hour"></span>: <span class="hour-clients"></span> <span class="lasthour-change"></span>
            </div>

        </div>
	</div>
</div>

<script>

$(document).on('appReady', function() {
	
	// Add tooltip
	$.getJSON( appUrl + '/module/reportdata/get_inactive_days', function( data ) {
		$('#client-widget>div.card-header')
			.attr('title', (i18n.t('client.panel_title', {number: data.inactive_days})))
			.tooltip();
	});

	var active = i18n.t('active'),
		inactive = i18n.t('inactive');

	var testdata1 = [
		{ key: active, y: 0 },
		{ key: inactive, y: 100 }
	];
	var chart;
	nv.addGraph(function () {
		chart = nv.models.pieChart()
			.x(function (d) { return d.key })
			.y(function (d) { return d.y })
			.donut(true)
			.showLabels(false);

		chart.title(" ");
		chart.legend.updateState(false);
		chart.tooltip.valueFormatter(function(d){return d});

		d3.select("#client-widget svg")
			.datum(testdata1)
			.call(chart);

		drawGraph();

	});

	// Get data and update graph
	var drawGraph = function(){
		var url = appUrl + '/module/reportdata/get_lastseen_stats';
		d3.json(url, function(data) {
			testdata1[0].y = data.lastcustom // Active
			testdata1[1].y = data.total - data.lastcustom // Inactive
			chart.title("" + data.lastcustom);

			d3.select('#test1').datum(testdata1).transition().duration(500).call(chart);
			chart.update();

			var total = +$('#client-widget span.total-clients').text(),
				lasthour = +$('#client-widget span.hour-clients').text();

			// Set count for total and hour
			$('#client-widget span.total-clients').text(data.total);
			$('#client-widget span.hour-clients').text(data.lasthour);

			// Show change for total
			$('#client-widget span.total-change').removeClass().addClass('total-change');
			if(total < data.total){
				$('#client-widget span.total-change').addClass('fa fa-caret-up text-success');
			}
			else if(total > data.total){
				$('#client-widget span.total-change').addClass('fa fa-caret-down text-danger');
			}

			// Show change for hour
			$('#client-widget span.lasthour-change').removeClass().addClass('lasthour-change');
			if(lasthour < data.lasthour){
				$('#client-widget span.lasthour-change').addClass('fa fa-caret-up text-success');
			}
			else if(lasthour > data.lasthour){
				$('#client-widget span.lasthour-change').addClass('fa fa-caret-down text-danger');
			}


		});

	};


	// update chart data
	$(document).on('appUpdate', function(){drawGraph()});

});
</script>
