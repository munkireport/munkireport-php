<?php $this->view('partials/head', array(
  "scripts" => array(
    "clients/client_list.js"
  )
)); ?>

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

      <h3><?php echo $name; ?> <span id="inv-count" class='label label-primary'><?php echo count($inventory_items); ?></span></h3>

      <?php if (count($inventory_items)): ?>
          <!--
          <h2>Machines (<?php echo count($inventory_items); ?>)</h2>
          -->
          <table class='table table-striped table-condensed table-bordered'>
              <thead>
                  <tr>
                    <th data-i18n="listing.computername"></th>
                    <th data-i18n="username"></th>
                    <th data-i18n="version"></th>
                    <th data-i18n="bundle_id"></th>
                    <th data-i18n="cfbundlename"></th>
                    <th data-i18n="path"></th>
                  </tr>
              </thead>
              <tbody>
              <?php foreach($inventory_items as $item): ?>
              <?php $url=url('clients/detail/' . $item['serial_number'] . '#tab_inventory-items') ?>
                  <tr>
                      <td>
                        <a href='<?php echo $url; ?>'>
                          <?php echo $item['hostname']; ?>
                        </a>
                      </td>
                      <td><?php echo $item['username']; ?></td>
                      <td><?php echo $item['version']; ?></td>
                      <td><?php echo $item['bundleid']; ?></td>
                      <td><?php echo $item['bundlename']; ?></td>
                      <td><?php echo $item['path']; ?></td>
                  </tr>
              <?php endforeach; ?>
              </tbody>
          </table>
      <?php else: ?>
          <h2>Machines</h2>
          <p><i>No machines.</i></p>
      <?php endif ?>
    </div> <!-- /span 12 -->

  </div> <!-- /row -->

</div>  <!-- /container -->

<?php $this->view('partials/foot'); ?>
