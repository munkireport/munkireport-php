<div class="col-sm-6">

	<div class="panel panel-default">

		<div class="panel-heading">

			<h3 class="panel-title"><i class="fa fa-clock-o"></i> <span data-i18n="widget.age.title">Hardware Age</span></h3>

		</div>

		<div class="panel-body">

			<div id="age-plot"></div>

		</div>

	</div><!-- /panel -->

</div><!-- /col-lg-4 -->

<script>
$(document).on('appReady', function() {

	// Clone barOptions
    var myOptions = jQuery.extend(true, {}, horBarOptions);
	myOptions.legend = {show: false}
	myOptions.callBack = resizeAgeBox;
    myOptions.yaxis.tickFormatter = function(v, obj){//(v, {min : axis.min, max : axis.max})
		label = obj.data[v].label
		return '<a class = "btn btn-default btn-xs" href="<?=url('show/listing/warranty')?>">' + label + ' ' + i18n.t('date.year') + '</a>';
	}

	// Resize the container after we know how many items we have
	function resizeAgeBox(obj)
	{
		$('#age-plot').height(obj.length * 25 + 50);
	}

	var parms = {}
	// HW Plot
	drawGraph("<?=url('module/warranty/age')?>", '#age-plot', myOptions, parms);

});
</script>
