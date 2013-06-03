<?$this->view('partials/head')?>

<div class="container">

    <div class="row">

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('.table').dataTable({
            "iDisplayLength": 25,
            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
            "bStateSave": true,
            "aaSorting": [[1,'asc']]
        });
    } );
</script>

<h1><?=$name?></h1>

<? if (count($inventory_items)): ?>
    <h2>Machines (<?=count($inventory_items)?>)</h2>
    <table class='table table-striped'>
        <thead>
            <tr>
              <th>Hostname</th>
              <th>Username</th>
              <th>Version</th>
              <th>BundleID</th>
              <th>CFBundleName</th>
              <th>Path</th>
            </tr>
        </thead>
        <tbody>
        <? foreach($inventory_items as $item): ?>
        <? $url=url('/inventory/detail/' . $item['serial']) ?>
            <tr>
                <td>
                  <a href='<?= $url ?>'>
                    <?= $item['hostname'] ?>
                  </a>
                </td>
                <td><?= $item['username'] ?></td>
                <td><?= $item['version'] ?></td>
                <td><?= $item['bundleid'] ?></td>
                <td><?= $item['bundlename'] ?></td>
                <td><?= $item['path'] ?></td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
<? else: ?>
    <h2>Machines</h2>
    <p><i>No machines.</i></p>
<? endif ?>

    </div> <!-- /row -->

</div>  <!-- /container -->

<?$this->view('partials/foot')?>