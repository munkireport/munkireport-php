<?php $this->view('partials/head'); ?>
<div class="container">
    <div class="row">
        <div class="col-lg-3">
            <span id="laps_admin_title"></span>
            
            <span id="laps_admin_label"></span>
            <div class="input-group hide" id="serial_enter">
                <input type="text" class="form-control" maxlength="12" id="serial_number">
                <span class="input-group-btn">
                    <button id="laps_serial_text" class="btn btn-success" type="button" data-i18n="laps.submit"></button>
                </span>
            </div>
            <br/>
            <table class="table table-striped hide" id="laps_table">
                <tr>
                    <th data-i18n="laps.useraccount"></th>
                    <td id="useraccount"></td>
                </tr>
                <tr>
                    <th data-i18n="laps.dateset"></th>
                    <td id="dateset"></td>
                </tr>
                <tr>
                    <th data-i18n="laps.dateexpires"></th>
                    <td id="dateexpires"></td>
                </tr>
                <tr>
                    <th data-i18n="laps.days_till_expiration" style="vertical-align: middle;"></th>
                    <td id="days_till_expiration" style="padding-top: 4px;padding-bottom: 3px;"></td>
                </tr>
                <tr>
                    <th data-i18n="laps.pass_length" style="vertical-align: middle;"></th>
                    <td id="pass_length" style="padding-top: 4px;padding-bottom: 3px;"></td>
                </tr>
                <tr>
                    <th data-i18n="laps.script_enabled" style="vertical-align: middle;"></th>
                    <td id="script_enabled" style="padding-top: 3px;padding-bottom: 0px;"></td>
                </tr>
                <tr>
                    <th data-i18n="laps.alpha_numeric_only" style="vertical-align: middle;"></th>
                    <td id="alpha_numeric_only" style="padding-top: 3px;padding-bottom: 0px;"></td>
                </tr>
                <tr>
                    <th data-i18n="laps.keychain_remove" style="vertical-align: middle;"></th>
                    <td id="keychain_remove" style="padding-top: 3px;padding-bottom: 0px;"></td>
                </tr>
                <tr>
                    <th data-i18n="laps.remote_management"></th>
                    <td id="remote_management"></td>
                </tr>
                <tr id="password-row">
                    <th data-i18n="laps.password"></th>
                    <td id="password"><button data-i18n="laps.show_password" class="btn btn-default btn-xs" id="laps_show_button" style="padding-top: 0px;padding-bottom: 0px;"></button></td>
                </tr>
            </table>
            <button id="laps_reset_button_first" class="btn btn-danger hide" type="button" data-i18n="laps.reset" data-toggle="modal" data-target="#passwordresetModal"></button>
            <button id="laps_save_button" class="btn btn-success hide" type="button" data-i18n="laps.save"></button>
        </div>
        <div class="col-lg-8">
            <span id="laps_admin_lookup"></span>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="passwordresetModal" tabindex="-1" role="dialog" aria-labelledby="Password Cycle Confirmation">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="passwordModelLabel"  data-i18n="laps.reset_confirm_title"></h4>
          </div>
          <div class="modal-body" data-i18n="laps.reset_confirm_body"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" data-i18n="laps.reset" id="laps_reset_button"></button>
            <button type="button" class="btn btn-default" data-dismiss="modal" data-i18n="dialog.cancel"></button>
          </div>
        </div>
      </div>
    </div><!-- /Modal -->
</div>  <!-- /container -->

<script>
    
$(document).on('appReady', function(e, lang) {
    
    // Generate header and localize label
    $('#laps_admin_title').html('<h3 style="margin-top: 0px;">'+i18n.t('laps.admin')+'&nbsp&nbsp<button id="view_audits_button" type="button" class="btn btn-primary btn-xs">'+i18n.t('laps.view_audits')+'</button></h3>');
    
    // Check if encryption key is set
    if (parseInt("<?php echo strlen(conf('laps_encryption_key')); ?>") > 0) {
        $('#laps_admin_label').html('<label for="serial_number" data-i18n="serial_number" class="col-form-label col-form-label-lg" style="font-size: 18px">'+i18n.t('serial')+': </label>');
        $('#serial_enter').removeClass('hide');
        
        // Get serial number from URL
        var url_serial_number = location.search.split('/admins/laps/laps/')[1]
        // Check if serial was in URL, if is process with serial number
        if (url_serial_number) {
            serialNumber = url_serial_number
            // Clear any existing error
            $('#laps_admin_label').html('<label for="serial_number" data-i18n="serial_number" class="col-form-label col-form-label-lg" style="font-size: 18px">'+i18n.t('serial')+': </label>');
            getData(url_serial_number, true)
        }
    } else {
        $('#laps_admin_label').html('<label for="serial_number" class="col-form-label col-form-label-lg" style="font-size: 18px"><span class="text-danger">'+i18n.t('laps.no_encrypt_key')+'</span></label>');
        $('#serial_enter').html('<button id="generate_key_button" type="button" class="btn btn-default">'+i18n.t('laps.generate_key')+'</button>');
        $('#serial_enter').removeClass('hide');
        $('#view_audits_button').addClass('hide disable');
    }
        
    // What to do when submit button is clicked
    $('#laps_serial_text').click(function (e) {
        // Get the serial number from the input
        serialNumber = document.getElementById("serial_number").value;
        
        // Check that we have a serial number
        if (! serialNumber == ''){
            // Clear any existing error
            $('#laps_admin_label').html('<label for="serial_number" data-i18n="serial_number" class="col-form-label col-form-label-lg" style="font-size: 18px">'+i18n.t('serial')+': </label>');
            getData(serialNumber, true)
        } else {
            // Show no serial number error
            $('#laps_admin_label').html('<label for="serial_number" data-i18n="serial_number" class="col-form-label col-form-label-lg" style="font-size: 18px">'+i18n.t('serial')+':&nbsp&nbsp<span class="text-danger">'+i18n.t('laps.no_serial')+'</span></label>');
            
            // Hide tables and reset password button
            $('#laps_table').addClass('hide');
            $('#laps_reset_button_first').addClass('hide');
            $('#laps_save_button').addClass('hide');
            $('#laps_admin_lookup').html("");
        }
    });
    
    // What to do when reset button is clicked
    $('#laps_reset_button').click(function (e) {
        saveAdmin(serialNumber, true)
        getData(serialNumber, false)
    });
    
    // What to do when save button is clicked
    $('#laps_save_button').click(function (e) {
        saveAdmin(serialNumber, false)
        updateAuditTable(serialNumber)
    });
    
    // What to do when generate key button is clicked
    $('#generate_key_button').click(function (e) {        
        // Disable generate key button
        $('#generate_key_button').addClass('hide disable');
        
        // Get the generated encryption key
        $.getJSON( appUrl + '/module/laps/generate_laps_key', function( data ) {
            $('#serial_enter').html(i18n.t('laps.key_generated_1')+"<br/>"+i18n.t('laps.key_generated_3')+'<br>'+i18n.t('laps.key_generated_2')+"<br/><br/>$conf['laps_encryption_key'] = '"+data.key);
        });
    });
    
    // What to do when view all audits button is clicked
    $('#view_audits_button').click(function (e) {        

        $('#laps_table').addClass('hide');
        $('#laps_reset_button_first').addClass('hide');
        $('#laps_save_button').addClass('hide');
        $('#laps_admin_lookup').html("");
        updateAuditTable("")
    });
    
    // Get and show password
    $('#laps_show_button').click(function (e) {        
        // Disable get password button
        $('#laps_show_button').addClass('hide disable');
        
        //Get password in JSON format
        $.getJSON(appUrl + '/module/laps/get_password/'+serialNumber, function (processdata) {
            $('#password').text(processdata.password);
        });
        
        // Then update audit table
        updateAuditTable(serialNumber)
    });
});


// Function to show save button if things are changed
function showsave()
{
    $('#laps_save_button').removeClass('hide');
}
    
// Function to save admin data
function saveAdmin(serialNumber, resetPassword)
{
    // Get the values from the inputs
    days_till_expiration_in = document.getElementById("days_till_expiration_value").value;
    pass_length_in = document.getElementById("pass_length_value").value;

    // Translate T/F to 1/0
    if (document.getElementById("alpha_numeric_only_value").checked){
        alpha_numeric_only_in = 1
    } else {
        alpha_numeric_only_in = 0
    }

    if (document.getElementById("script_enabled_value").checked){
        script_enabled_in = 1
    } else {
        script_enabled_in = 0
    }

    if (document.getElementById("keychain_remove_value").checked){
        keychain_remove_in = 1
    } else {
        keychain_remove_in = 0
    }

    // Reset password if true
    if (resetPassword){
         var save_info = JSON.stringify({"dateexpires":"1","days_till_expiration":days_till_expiration_in,"pass_length":pass_length_in,"alpha_numeric_only":alpha_numeric_only_in,"script_enabled":script_enabled_in,"keychain_remove":keychain_remove_in});
    } else {
         var save_info = JSON.stringify({"days_till_expiration":days_till_expiration_in,"pass_length":pass_length_in,"alpha_numeric_only":alpha_numeric_only_in,"script_enabled":script_enabled_in,"keychain_remove":keychain_remove_in});
    }
    $('#laps_save_button').addClass('hide');
    
    // Post to save info
    xhr = new XMLHttpRequest();
    var url = appUrl + '/module/laps/save_admin_info/'+serialNumber;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json");
    xhr.send(save_info); 
}
    
// Function to save audit trail
function getData(serialNumber, audit)
{
    // Just viewing or refreshing
    if (audit){
        jsonURL = appUrl + '/module/laps/get_data_admin/' + serialNumber
    } else {
        jsonURL = appUrl + '/module/laps/get_data/' + serialNumber
    }
    
    // Get the JSON response for the serial number
    $.getJSON( jsonURL, function( data ) {
        
        // Check if serial number is found
        if (data == "Serial not found or no macOSLAPS data") {
            // Show invalid serial number or no data error
            $('#laps_admin_label').html('<label for="serial_number" data-i18n="serial_number" class="col-form-label col-form-label-lg" style="font-size: 18px">'+i18n.t('serial')+':&nbsp&nbsp<span class="text-danger">'+i18n.t('laps.not_found')+'</span></label>');
            
            // Hide tables and reset password button
            $('#laps_table').addClass('hide');
            $('#laps_reset_button_first').addClass('hide');
            $('#laps_save_button').addClass('hide');
            $('#laps_admin_lookup').html("");
        } else {
        
            // Audit Data Table
            updateAuditTable(serialNumber);

            // User account
            $('#useraccount').html(data.useraccount);

            // Days bewteen expirations
            $('#days_till_expiration').html('<input type="number" name="pass_length" min="1" max="999" value="'+data.days_till_expiration+'" style="width: 50px;" id="days_till_expiration_value" onclick="showsave();">&nbsp;&nbsp;'+i18n.t('date.day_plural'));   

            // Password length
            $('#pass_length').html('<input type="number" name="pass_length" min="1" max="128" value="'+data.pass_length+'" style="width: 50px;" id="pass_length_value" onclick="showsave();">&nbsp;&nbsp;'+i18n.t('laps.characters'));

            // Format remote_management boolean
            if(data.remote_management === "1" || data.remote_management === 1) {
                 $('#remote_management').text(i18n.t('enabled'));
            } else if(data.script_enabled === "0" || data.remote_management === 0) {
                 $('#remote_management').text(i18n.t('disabled'));
            } else{
                 $('#remote_management').text("");
            }

            // Fill in alpha_numeric_only checkbox
            var alpha_numeric_only = data.alpha_numeric_only;
            if (alpha_numeric_only == 1) {
                $('#alpha_numeric_only').html('<label class="switch" style="margin-bottom: 0px;"><input type="checkbox" checked id="alpha_numeric_only_value" onclick="showsave();"><span class="slider round"></span></label>');
            } else {
                $('#alpha_numeric_only').html('<label class="switch" style="margin-bottom: 0px;"><input type="checkbox" id="alpha_numeric_only_value" onclick="showsave();"><span class="slider round"></span></label>');
            }

            // Fill in keychain_remove checkbox
            var keychain_remove = data.keychain_remove;
            if (keychain_remove == 1) {
                $('#keychain_remove').html('<label class="switch" style="margin-bottom: 0px;"><input type="checkbox" checked id="keychain_remove_value" onclick="showsave();"><span class="slider round"></span></label>');
            } else {
                $('#keychain_remove').html('<label class="switch" style="margin-bottom: 0px;"><input type="checkbox" id="keychain_remove_value" onclick="showsave();"><span class="slider round"></span></label>');
            }

            // Fill in script_enabled checkbox
            var script_enabled = data.script_enabled;
            if (script_enabled == 1) {
                $('#script_enabled').html('<label class="switch" style="margin-bottom: 0px;"><input type="checkbox" checked id="script_enabled_value" onclick="showsave();"><span class="slider round"></span></label>');
            } else {
                $('#script_enabled').html('<label class="switch" style="margin-bottom: 0px;"><input type="checkbox" id="script_enabled_value" onclick="showsave();"><span class="slider round"></span></label>');
            }

            // Date expires
            if (data.dateexpires == 1 || data.dateexpires == "1") {
                $('#dateexpires').html(i18n.t('laps.password_will_cycle'));
            } else {
                var dateexpires = moment(data.dateexpires * 1000);
                $('#dateexpires').html('<time title="'+dateexpires.format('LLLL')+'" style="cursor: pointer;">'+dateexpires.fromNow()+'</time>');
            }

            // Dateset
            var dateset = moment(data.dateset * 1000);
            $('#dateset').html('<time title="'+dateset.format('LLLL')+'" style="cursor: pointer;">'+dateset.fromNow()+'</time>');

            // Show password reset button
            $('#laps_reset_button_first').removeClass('hide');

            // Check if should show password row and show button
            var password_decrypt_enabled = "<?php echo conf('laps_password_decrypt_enabled'); ?>";
            if (password_decrypt_enabled != 1 || data.password_view !== 1){
                $('#password-row').addClass('hide'); 
                $('#laps_show_button').addClass('disabled'); 
            }

            // Unhide table once data is loaded
            $('#laps_table').removeClass('hide');

            // Set tooltips
            $( "time" ).each(function( index ) {
                $(this).tooltip().css('cursor', 'pointer');
            });
        }
    });
}
    
// Function to get update audit table
function updateAuditTable(serialNumber)
{
    $.getJSON(appUrl + '/module/laps/get_audit/'+serialNumber, function (auditjson) {
        if (serialNumber == ''){
            var auditrows = '<br/><h3 style="margin-top: 0px;">'+i18n.t('laps.audit_all')+'</h3>'
            // Process each serial number
            $.each(auditjson, function(i,d){
                // Build out the table
                var auditdata =  JSON.parse(d['audit']).reverse();
                auditrows = auditrows+'<h4 style="margin-top: 0px;">'+d['serial_number']+'</h4><table class="table table-striped table-condensed"><tbody><thead><tr><th>'+i18n.t('laps.timestamp')+'</th><th>'+i18n.t('laps.username')+'</th><th>'+i18n.t('laps.action')+'</th><th>'+i18n.t('laps.remote_ip')+'</th><th>'+i18n.t('laps.user_agent')+'</th></tr></thead>'
                if (parseInt(auditdata.length) == 0 ){
                        auditrows = auditrows+'<tr><td>'+i18n.t('laps.no_audit')+'</td><td></td><td></td></tr>';   
                } else {
                    $.each(auditdata, function(i,d){
                        // Fix date/time
                        var timehuman = '<time title="'+moment((parseInt(d['timestamp'])*1000)).fromNow()+'" style="cursor: pointer;">'+moment((parseInt(d['timestamp']))*1000).format('llll')+'</time>'
                        // Generate rows from data
                        // If action is unauthorized, make row red
                        if (d['action'].includes("_unauth")){
                            auditrows = auditrows + '<tr class="danger"><td>'+timehuman+'</td><td>'+d['username']+'</td><td>'+i18n.t('laps.'+d['action'])+'</td><td>'+d['remote_ip']+'</td><td>'+d['user_agent']+'</td></tr>';
                        }else{
                            auditrows = auditrows + '<tr><td>'+timehuman+'</td><td>'+d['username']+'</td><td>'+i18n.t('laps.'+d['action'])+'</td><td>'+d['remote_ip']+'</td><td>'+d['user_agent']+'</td></tr>';
                        }
                    })
                }
                auditrows = auditrows+"</tbody></table>" // Close audit table framework
            });
            $('#laps_admin_lookup').html(auditrows); // Cssign to HTML ID
        } else {
            // Build out table for one serial
            var auditdata = JSON.parse(auditjson['audit']).reverse();
            // Make the table framework
            var auditrows = '<br/><h4 style="margin-top: 18px;">'+i18n.t('laps.audit')+'</h4><table class="table table-striped table-condensed"><tbody><thead><tr><th>'+i18n.t('laps.timestamp')+'</th><th>'+i18n.t('laps.username')+'</th><th>'+i18n.t('laps.action')+'</th><th>'+i18n.t('laps.remote_ip')+'</th><th>'+i18n.t('laps.user_agent')+'</th></tr></thead>'
            if (parseInt(auditdata.length) == 0 ){
                    auditrows = auditrows+'<tr><td>'+i18n.t('laps.no_audit')+'</td><td></td><td></td></tr>';   
            } else {
                $.each(auditdata, function(i,d){
                    // Fix date/time
                    var timehuman = '<time title="'+moment((parseInt(d['timestamp'])*1000)).fromNow()+'" style="cursor: pointer;">'+moment((parseInt(d['timestamp']))*1000).format('llll')+'</time>'
                    // Generate rows from data
                    // If action is unauthorized, make row red
                    if (d['action'].includes("_unauth")){
                        auditrows = auditrows + '<tr class="danger"><td>'+timehuman+'</td><td>'+d['username']+'</td><td>'+i18n.t('laps.'+d['action'])+'</td><td>'+d['remote_ip']+'</td><td>'+d['user_agent']+'</td></tr>';
                    }else{
                        auditrows = auditrows + '<tr><td>'+timehuman+'</td><td>'+d['username']+'</td><td>'+i18n.t('laps.'+d['action'])+'</td><td>'+d['remote_ip']+'</td><td>'+d['user_agent']+'</td></tr>';
                    }
                })
            }
            $('#laps_admin_lookup').html(auditrows+"</tbody></table>") // Close audit table framework and assign to HTML ID     
        }
    });
}  
</script>

<?php $this->view('partials/foot'); ?>