<?php $this->view('partials/head'); ?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">



<script type="text/javascript" charset="utf-8">
    $(document).on('appReady', function(e, lang) {
        $('.table').dataTable({
            "bServerSide": false,
            "aaSorting": [[0,'asc']],
            "fnDrawCallback": function( oSettings ) {
				$('#inv-count').html(oSettings.fnRecordsTotal());
			}
        });
    } );
</script>
<h3>Inventory items <span id="inv-count" class='label label-primary'>…</span></h3>
<table class='table table-striped table-condensed table-bordered'>
  <thead>
    <tr>
      <th>Name</th>
      <th>Version</th>
    </tr>
  </thead>
  <tbody>
  	<?php $inventory_item_obj = new Inventory_model();
	$items = $inventory_item_obj->select_all();
  
	$inventory = array();
	foreach($items as $item)
	{
		$name = $item->name;
		$version = $item->version;
		$installs = $item->num_installs;

		$inventory[$name][$version] = $installs;
	}
?>
    <?php foreach($inventory as $name => $value): ?>
    <?php $name_url=url('module/inventory/items/'. rawurlencode($name)); ?>
    <tr>
      <td>
        <a href='<?php echo $name_url; ?>'><?php echo $name; ?></a>
      </td>
      <td>
        <?php foreach($value as $version => $count): ?>
        <?php $vers_url=$name_url . '/' . rawurlencode($version); ?>
        <a href='<?php echo $vers_url; ?>'><?php echo $version; ?>
          <span class='badge badge-info pull-right'><?php echo $count; ?></span>
        </a><br />
        <?php endforeach; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?php $this->view('partials/foot'); ?>