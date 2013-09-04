<div class="col-lg-6">

	<div class="panel panel-default">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="icon-desktop"></i> Hardware breakdown</h3>
		
		</div>

		<div class="panel-body">
			

			<div style="height: 200px" id="hw-plot"></div>

		</div>

	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).ready(function() {
	var parms = {}
	// HW Plot
	$.getJSON("<?=url('flot/hw')?>", {'req':JSON.stringify(parms)}, function(data) {
		$.plot("#hw-plot", data,{
		    series: {
		    	bars: {
		            show: true,
		            lineWidth: 0,
		            fill: 0.8,
		            barWidth: 0.9
				}
			},
			xaxis:
			{
				show: false
			},
			grid:
			{
				borderColor: '#eee'
			},
			legend: {
				labelFormatter: function(label, series) {
				// series is the series object for the label
				return '<a href="<?=url('show/listing/hardware')?>#' + label + '">' + label + '</a>';
				}
			}
			
	    });
	});

	function hwLabelFormatter(label, series) {
		return "<div style='font-size:150%; text-align:center; padding:2px; color:white;'>" + series.data[0][1] + "</div>";
	}
});
</script>