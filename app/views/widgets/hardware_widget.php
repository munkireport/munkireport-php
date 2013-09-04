<div class="col-lg-4">

	<div class="panel panel-default">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="icon-globe"></i> Hardware breakdown</h3>
		
		</div>

		<div class="panel-body">
			

			<div style="height: 200px" id="hw-plot"></div>

		</div>

	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).ready(function() {
	var parms = {}
	// IP Plot
	$.getJSON("<?=url('flot/hw')?>", {'req':JSON.stringify(parms)}, function(data) {
		$.plot("#hw-plot", data,{
		    series: {
		    	pie: {
		            show: true,
		            radius: 1,
		            label: {
		                show: true,
		                radius: 2/3,
		                formatter: hwLabelFormatter,
		                threshold: 0.1,
		                background: {
		                    opacity: 0.8
		                }
		            }
				}
			},
			xaxis:
			{
				show: false
			}
	    });
	});

	function hwLabelFormatter(label, series) {
		return "<div style='font-size:150%; text-align:center; padding:2px; color:white;'>" + series.data[0][1] + "</div>";
	}
});
</script>