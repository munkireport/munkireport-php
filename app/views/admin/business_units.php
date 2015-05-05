<?php $this->view('partials/head'); ?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">

		<h3 data-i18n="admin.bu_overview"></h3>

		<div id="bu_units"></div>
		<div data-i18n="listing.loading" id="loading"></div>

    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button data-i18n="dialog.cancel" type="button" class="btn btn-default" data-dismiss="modal"></button>
        <button type="button" class="btn btn-primary ok"></button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

	$(document).on('appReady', function(e, lang) {

		var edit = function(){

				// Get unit data
				var data = $(this).closest('.unit').data();

				$('#myModal .modal-body')
					.empty()
					.append($('<form>')
						.submit(save)
						.append($('<input>')
							.attr('name', 'name')
							.val(data.name))
					);
				$('#myModal .modal-title').text(i18n.t("admin.edit_bu.title"));
				$('#myModal button.ok')
					.data(data)
					.text(i18n.t("dialog.save"))
					.click(save);

				$('#myModal').modal('show');
			},
			save = function(e){
				// In case we get called by submit
				e.preventDefault();

				var data = $('#myModal button.ok').data();

				// Collect values from inputs
				$('#myModal input').each(function(){
					data[$(this).attr('name')] = $(this).val();
				});

				// Store object in database
				var jqxhr = $.post( baseUrl + "admin/save_business_unit", data)
				.done(function(){

					// Dismiss modal
					$('#myModal').modal('hide');

					// Update unit
					$('.unitid-' + data.unitid + ' .name')
						.text(data.name)
				})
				.fail(function() {
					alert( "Could not save" );
				});

			},
			remove_dialog = function(){

				// Get unit data
				var data = $(this).closest('.unit').data();

				// Set texts
				$('#myModal .modal-body').text(i18n.t("admin.remove_bu.content"));
				$('#myModal .modal-title').text(i18n.t("admin.remove_bu.title"));
				$('#myModal button.ok').text(i18n.t("dialog.ok_remove"));

				// Add unitid to ok button
				$('#myModal button.ok')
					.data({unitid: data.unitid})
					.click(remove);

				// Show modal
				$('#myModal').modal('show');

			},
			remove = function(){
				var unitid = $(this).data().unitid;
				var url = baseUrl + 'admin/remove_business_unit/' + unitid;
				$.getJSON(url, function(data){
					if(data.success == true)
					{
						// Dismiss modal
						$('#myModal').modal('hide');
						// Update listing
						$('.unitid-' + unitid).remove();
					}
				});	
			}

		// Get all business units
		$.getJSON(baseUrl + 'admin/get_bu_data', function(data){
			// Remove Loading row
			$('#loading').hide();
			$.each(data, function(index, value){
				$('#bu_units')
					.append($('<div>')
						.data(value)
						.addClass('unit unitid-' + value.unitid)
						.append($('<h3>')
							.addClass('name')
							.text(value.name))
						.append($('<div>')
							.append($('<a>')
								.addClass('btn btn-xs btn-default')
								.click(edit)
								.text('edit'))									
							.append($('<a>')
								.addClass('btn btn-xs btn-default')
								.click(remove_dialog)
								.text('delete')))
					);
			});

		});
		
	} );
</script>

<?php $this->view('partials/foot'); ?>