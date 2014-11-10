<?$this->view('partials/head', array(
  "scripts" => array(
    "clients/client_list.js"
  )
))?>

<div class="container">

  <div class="row">
    
    <div class="col-lg-12">
    
      <script type="text/javascript" charset="utf-8">
          $(document).on('appReady', function(e, lang) {
              $('.table').dataTable({
                  "bServerSide": false,
              });
          } );
      </script>
      
      <h3><?=$name?> <span id="inv-count" class='label label-primary'><?=count($inventory_items)?></span></h3>
      
      <? if (count($inventory_items)): ?>
          <!--
          <h2>Machines (<?=count($inventory_items)?>)</h2> 
          -->
          <table class='table table-striped table-condensed table-bordered'>
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
              <? $url=url('clients/detail/' . $item['serial'] . '#tab_inventory-items') ?>
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
    </div> <!-- /span 12 -->
    
  </div> <!-- /row -->
  
</div>  <!-- /container -->

<?$this->view('partials/foot')?>