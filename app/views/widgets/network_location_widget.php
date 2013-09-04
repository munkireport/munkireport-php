		<div class="col-lg-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="icon-globe"></i> Network locations</h3>
				
				</div>

				<div class="panel-body">
					

					<div style="height: 200px" id="ip-plot"></div>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

		<script>
		$(function(){
			var parms = { 
				"Campus": ["145.108.", "130.37."]
			};

			// IP Plot
			$.getJSON("<?=url('flot/ip')?>", {'req':JSON.stringify(parms)}, function(data) {
				$.plot("#ip-plot", data,{
				    series: {
				        pie: {
				            show: true,
				            radius: 1,
				            label: {
				                show: true,
				                radius: 2/3,
				                formatter: labelFormatter,
				                threshold: 0.1,
				                background: {
				                    opacity: 0.8
				                }
				            }
				        }
				    },
				    colors: ["#00CDCD", "#0278D3", "#FFC700", "#FF7400"]
			    });
			});
		});
		</script>