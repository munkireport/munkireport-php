		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-hdd-o"></i> <span data-i18n="free_disk_space">Free Disk Space</span></h3>
				
				</div>

				<div class="panel-body text-center">


					<a id="disk-success" href="<?php echo url('show/listing/disk#'.rawurlencode('freespace > 10GB')); ?>" class="btn btn-success hide">
						<span class="bigger-150"></span><br>
						10GB +
					</a>
					<a id="disk-warning" href="<?php echo url('show/listing/disk#'.rawurlencode('5GB freespace 10GB')); ?>" class="btn btn-warning hide">
						<span class="bigger-150"></span><br>
						&lt; 10GB
					</a>
					<a id="disk-danger" href="<?php echo url('show/listing/disk#'.rawurlencode('freespace < 5GB')); ?>" class="btn btn-danger hide">
						<span class="bigger-150"></span><br>
						&lt; 5GB
					</a>

                    <span id="disk-nodata" data-i18n="no_clients"></span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

<script>
$(document).on('appReady appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/disk_report/get_stats', function( data ) {

        console.log(data)
        // Show no clients span
        $('#disk-nodata').removeClass('hide');

        $.each(data, function(prop, val){
            if(val > 0)
            {
                $('#disk-' + prop).removeClass('hide');
                $('#disk-' + prop + '>span').html(val);

                // Hide no clients span
                $('#disk-nodata').addClass('hide');
            }
            else
            {
                $('#disk-' + prop).addClass('hide');
            }
        });
    });
});


</script>
