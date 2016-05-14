<?php $this->view('partials/head'); ?>

<div class="container">

    <div class="row">

        <h3 class="col-lg-12" data-i18n="system.status"></h3>
      
    </div>

    <div class="row">
        
        <div id="mr-phpinfo" class="col-lg-6">
            
            <h4 data-i18n="php.php"></h4>
            
            <table class="table table-striped"><tr><td data-i18n="loading"></td></tr></table>

        </div>

        <div id="mr-db" class="col-lg-6">
            
            <h4 data-i18n="database"></h4>
            
            <table class="table table-striped"><tr><td data-i18n="loading"></td></tr></table>
              
        </div>
    </div>    

</div>  <!-- /container -->

<script>
$(document).on('appReady', function(e, lang) {
    
    // Get database info
    $.getJSON( appUrl + '/system/DataBaseInfo', function( data ) {
        var table = $('#mr-db table').empty();
        for(var prop in data) {
            if(data[prop] === false){
                data[prop] = '<span class="label label-danger">No</span>';
            }
            if(data[prop] === true){
                data[prop] = '<span class="label label-success">Yes</span>';
            }

            table.append($('<tr>')
                .append($('<th>')
                    .html(i18n.t(prop)))
                .append($('<td>')
                    .html(data[prop])))
        }
    })
    .fail(function( jqxhr, textStatus, error ) {
        var err = textStatus + ", " + error;
        $('#mr-db table tr td')
            .empty()
            .addClass('text-danger')
            .text(i18n.t('errors.loading', {error:err}));
    });
    
    // Get php info
    $.getJSON( appUrl + '/system/phpInfo', function( data ) {
        var table = $('#mr-phpinfo table').empty();
        
        //console.log(data);
        
        // List of collectable values
        var list = {
            'php.version': data.Core['PHP Version'],
            'php.dom': data.dom['DOM/XML'] || false,
            'php.pdo': data.PDO['PDO support'] || false,
            'php.pdodrivers': data.PDO['PDO drivers'] || false
        }

        for(var prop in list) {
            
            if(list[prop] === false){
                list[prop] = '<span class="label label-danger">No</span>';
            }
            if(list[prop] === true){
                list[prop] = '<span class="label label-success">Yes</span>';
            }

            table.append($('<tr>')
                .append($('<th>')
                .html(i18n.t(prop)))
                .append($('<td>')
                    .html(list[prop])))
        }
    })
    .fail(function( jqxhr, textStatus, error ) {
        var err = textStatus + ", " + error;
        $('#mr-phpinfo table tr td')
            .empty()
            .addClass('text-danger')
            .text(i18n.t('errors.loading', {error:err}));
    });
});
</script>


<?php $this->view('partials/foot'); ?>