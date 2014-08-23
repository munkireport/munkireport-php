<div class="col-lg-6 col-md-6">

	<div class="panel panel-default">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-desktop"></i> Hardware type breakdown</h3>

		</div>

		<div class="panel-body">

			<div style="height: 200px" id="hw-plot"></div>

		</div>

	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).on('appReady', function(e, lang) {

	// Copy barOptions
    myOptions = jQuery.extend({}, barOptions);
    myOptions.legend.labelFormatter = function(label, series) {
			// series is the series object for the label
			return '<a href="<?=url('show/listing/hardware')?>#' + label + '">' + label + '</a>';
			}

	var parms = {}
	// HW Plot
	drawGraph("<?=url('module/machine/hw')?>", '#hw-plot', myOptions, parms);

});
</script>
