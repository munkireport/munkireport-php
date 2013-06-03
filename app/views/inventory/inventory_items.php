<?$this->view('partials/head')?>

<div class="container">

  <div class="row">

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('.table').dataTable({
            "iDisplayLength": 25,
            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
            "bStateSave": true,
            "aaSorting": [[0,'asc']]
        });
    } );
</script>

<h1>Inventory items (<?=count($inventory)?>)</h1>
<table class='table table-striped'>
<thead>
  <tr>
    <th>Name</th>
    <th>Version</th>
  </tr>
</thead>
<tbody>
  <?foreach($inventory as $name => $value):?>
    <?php $name_url=url('/inventory/items/'. rawurlencode($name)); ?>
    <tr>
      <td>
        <a href='<?=$name_url?>'><?=$name?></a>
      </td>
      <td>
        <?foreach($value as $version => $count):?>
            <?php $vers_url=$name_url . '/' . rawurlencode($version); ?>
            <a href='<?=$vers_url?>'><?=$version?></a>
            <?='&nbsp;&nbsp;' . $count . (($count != 1) ? ' machines' : ' machine')?><br/>
        <?endforeach?>
      </td>
    </tr>
  <?endforeach?>
</tbody>
</table>

</div> <!-- /row -->

</div>  <!-- /container -->

<?$this->view('partials/foot')?>