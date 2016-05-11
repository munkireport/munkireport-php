<?php $this->view('partials/head'); ?>

<div class="container">

    <div class="row">

        <h3 class="col-lg-12">Munkireport System Status</h3>
      
    </div>

    <div class="row">
        
        <div id="mr-db" class="col-lg-6">
            
            <h4>Database</h4>
            
            <table class="table table-striped"></table>
              
        </div>
        <div class="col-lg-6">
        </div>
    </div>
    <div class="row">
        
        <div class="col-lg-12">
            
            <?php 

            // Sort config file
            $this_config = $GLOBALS['conf'];
            ksort($this_config);

            ?>
              
            <h4>Configuration file</h4>

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

    </div>
    

</div>  <!-- /container -->

<script>
$(document).on('appReady', function(e, lang) {
    $.getJSON( appUrl + '/system/DataBaseInfo', function( data ) {
        var table = $('#mr-db table');
        for(var prop in data) {
            if(data[prop] == false){
                data[prop] = '<span class="label label-danger">No</span>';
            }
            table.append($('<tr>')
                .append($('<th>')
                    .html(prop))
                .append($('<td>')
                    .html(data[prop])))
        }
    });
});
</script>


<?php $this->view('partials/foot'); ?>