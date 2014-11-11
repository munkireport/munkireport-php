<div class="col-md-6">

	<div class="panel panel-default">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-lightbulb-o"></i> Memory breakdown</h3>
		
		</div>

		<div class="panel-body">
			
			<div id="memory-plot"></div>

		</div>

	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).on('appReady', function(e, lang) {

	// Clone barOptions
    var myOptions = jQuery.extend(true, {}, horBarOptions);
	myOptions.legend = {show: false}
	myOptions.callBack = resizeBox;
    myOptions.yaxis.tickFormatter = function(v, obj){//(v, {min : axis.min, max : axis.max})
		label = obj.data[v].label
		return '<a class = "btn btn-default btn-xs" href="<?=url('show/listing/hardware')?>#' + label + '">' + label + '</a>';
	}

	// Resize the container after we know how many items we have
	function resizeBox(obj)
	{
		$('#memory-plot').height(obj.length * 25 + 50);
	}

	var parms = {}
	// HW Plot
	drawGraph("<?=url('module/machine/memory')?>", '#memory-plot', myOptions, parms);

});
</script>