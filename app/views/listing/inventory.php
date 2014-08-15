<?$this->view('partials/head')?>

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
  	<?	$inventory_item_obj = new Inventory_model();
	$items = $inventory_item_obj->select('name, version, COUNT(id) AS num_installs', '1 GROUP BY name, version');

	$inventory = array();
	foreach($items as $item)
	{
		$name = $item['name'];
		$version = $item['version'];
		$installs = $item['num_installs'];

		$inventory[$name][$version] = $installs;
	}
?>
    <?foreach($inventory as $name => $value):?>
    <?php $name_url=url('module/inventory/items/'. rawurlencode($name)); ?>
    <tr>
      <td>
        <a href='<?=$name_url?>'><?=$name?></a>
      </td>
      <td>
        <?foreach($value as $version => $count):?>
        <?php $vers_url=$name_url . '/' . rawurlencode($version); ?>
        <a href='<?=$vers_url?>'><?=$version?>
          <span class='badge badge-info pull-right'><?=$count?></span>
        </a><br />
        <?endforeach?>
      </td>
    </tr>
    <?endforeach?>
  </tbody>
</table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>