<?php $this->view('partials/head'); ?>
<?php new Business_unit ?>


<div class="container">

  <div class="row">

  	<div class="col-lg-12">
		<script type="text/javascript">

		$(document).on('appReady', function(e, lang) {

				// Get column names from data attribute
				var myCols = [];
				$('.table th').map(function(){
					  myCols.push({'mData' : $(this).data('colname')});
				}); 
			    oTable = $('.table').dataTable( {
			        "aoColumns": myCols,
			        "sAjaxSource": "<?php echo url('datatables/data'); ?>",
			        "fnServerParams": function ( aoData ) {
				    	// Don't use the machine table
				    	aoData.push( { "name": "mrAddMachineTbl", "value": 0 } );
				    },
			        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
			        	// Update name in first column to link
			        	var name=$('td:eq(0)', nRow).html();

			        }
			    } );
			} );
		</script>

		  <h3><span data-i18n="listing.ard.title"></span> <span id="total-count" class='label label-primary'>…</span></h3>
		  
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine_group#groupid'></th>
		      	<th data-i18n="listing.computername" data-colname='machine_group#property'></th>
		      	<th data-i18n="listing.computername" data-colname='machine_group#value'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td data-i18n="listing.loading" colspan="3" class="dataTables_empty"></td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?php $this->view('partials/foot'); ?>