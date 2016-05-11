<?php $this->view('partials/head'); ?>

<?php //Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Ard_model;

// Sort config file
$this_config = $GLOBALS['conf'];
ksort($this_config);

$db_items = array(
    'pdo_dsn' => '',
    'pdo_opts' => '',
    'pdo_pass' => '',
    'pdo_user' => '',
    'mysql_create_tbl_opts' => '',
    'dbname' => '',
);
foreach($db_items as $key => $val){
    if(array_key_exists($key, $this_config))
    {
        // Copy item
        $db_items[$key] = $this_config[$key];
        // Remove from config
        unset($this_config[$key]);
    }
}

?>

<div class="container">

    <div class="row">

        <h3 class="col-lg-12">Munkireport System Status</h3>
      
    </div>

    <div class="row">
        
        <div class="col-lg-6">
            
            <h4>Database</h4>
            
            <table class="table table-striped">
              <?php foreach($db_items as $key => $item):?>
              
              <tr>
                  <th>
                      <?=$key?>
                  </th>
                  <td>
                      <pre><?print_r($item)?></pre>
                  </td>
              </tr>
              
              <?php endforeach?>
                  
              </table>
              
        </div>

        <div class="col-lg-6">

              
            <h4>Configuration</h4>

            <table class="table striped">
                <?php foreach($this_config as $key => $item):?>

                <tr>
                    <th>
                      <?=$key?>
                    </th>
                    <td>
                      <pre><?print_r($item)?></pre>
                    </td>
                </tr>

                <?php endforeach?>
              
            </table>
            
        </div>

  </div> <!-- /row -->
</div>  <!-- /container -->


<?php $this->view('partials/foot'); ?>