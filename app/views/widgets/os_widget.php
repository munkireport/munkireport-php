<div class="col-lg-12">

	<div class="panel panel-default">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="icon-desktop"></i> OS breakdown</h3>
		
		</div>

		<div class="panel-body">
			

			<div style="height: 300px" id="os-plot"></div>

		</div>

	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).ready(function() {

	// Copy barOptions
    myOptions = horBarOptions
	myOptions.legend = {show: false}
    myOptions.yaxis.tickFormatter = function(v, obj){//(v, {min : axis.min, max : axis.max})
		//label = obj.data[v].label
		//return '<a class = "btn btn-default btn-xs" href="<?=url('show/listing/clients')?>#' + label + '">' + label + '</a>';
	}

	var parms = {}
	// HW Plot
	drawGraph("<?=url('flot/os')?>", '#os-plot', myOptions, parms);

});
</script>