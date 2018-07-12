<?php $this->view('partials/head'); ?>

<div class="container">
    <div class="row"><span id="jamf_pull_all"></span></div>
    <div class="col-lg-6">
        <div id="GetAllJamf-Progress" class="progress hide">
            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                <span id="Progress-Bar-Percent"></span>
            </div>
        </div>
        <br id="Progress-Space" class="hide">
        <div id="Jamf-System-Status"></div>
    </div>
</div>  <!-- /container -->

<script>
var jamf_pull_all_running = 0;
    
$(document).on('appReady', function(e, lang) {
    
    // Generate pull all button and header    
    $('#jamf_pull_all').html('<h3 class="col-lg-6" >&nbsp;&nbsp;'+i18n.t('jamf.title_admin')+'&nbsp;&nbsp;<button id="GetAllJamf" class="btn btn-default btn-xs hide">'+i18n.t("jamf.pull_in_all")+'</button></h3>');
        
    // Get Jamf server URL
    var jamf_server = "<?php echo rtrim(conf('jamf_server'), '/'); ?>";

    // Check if Jamf lookups are enabled
    if ("<?php echo conf('jamf_enable'); ?>" == true) {
        var jamf_enabled = i18n.t('yes');   
        var jamf_enabled_int = 1;
        $('#GetAllJamf').removeClass('hide');
    } else { 
        var jamf_enabled = i18n.t('no');
        var jamf_enabled_int = 0;
    }
    
    jamf_pull_all_running = 0;
    
    // Check if Jamf API password is set
    if (parseInt("<?php echo strlen(conf('jamf_password')); ?>") > 0) {
        var jamf_password = i18n.t('yes');    
    } else { 
        var jamf_password = i18n.t('no');
    }
    
    // Get verify SSL state
    if ("<?php echo conf('jamf_verify_ssl'); ?>" == true) {
        var jamf_verify_ssl = i18n.t('yes');    
    } else { 
        var jamf_verify_ssl = i18n.t('no');
    }
    
    // Get Jamf API username
    var jamf_username = "<?php echo conf('jamf_username'); ?>"
        
    if (jamf_enabled_int == 1){
        // Get JSS user info using the same process as the jamf_helper
        var jss_json = '<?php
            // Only run if Jamf lookups are enabled
            if(conf('jamf_enable') == TRUE){
                if(conf('jamf_verify_ssl') == FALSE || $jamf_verify_ssl == 'false' || $jamf_verify_ssl == 'FALSE' || $jamf_verify_ssl == '0' || $jamf_verify_ssl == 0){
                    $jamf_verify_ssl = 0;
                } else {
                    $jamf_verify_ssl = 1;
                }
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, rtrim(conf('jamf_server'), '/').'/JSSResource/jssuser');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout of 5 seconds
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, conf('jamf_username').':'.conf('jamf_password'));
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $jamf_verify_ssl);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $jamf_verify_ssl);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Accept: application/json'));
                
                $jamfresult = curl_exec($ch);
                if (strpos($jamfresult, 'The request requires user authentication') !== false){
                    echo '{"user": "Unauthorized"}';
                } else if ( $jamfresult == "" ){
                    echo '{"user": "404_Not_Found"}';
                } else {
                    echo $jamfresult;
                }
            }
        ?>'
        
        // Process JSS user data
        var jssuserdata = JSON.parse(jss_json);
    }
    
    // Build table
    var jssrows = '<table class="table table-striped table-condensed"><tbody>'
    jssrows = jssrows + '<tr><th>'+i18n.t('jamf.lookups_enabled')+'</th><td>'+jamf_enabled+'</td></tr>';
    jssrows = jssrows + '<tr><th>'+i18n.t('jamf.jss_server')+'</th><td><a target="_blank" href="'+jamf_server+'">'+jamf_server+'</a></td></tr>';
    jssrows = jssrows + '<tr><th>'+i18n.t('jamf.jamf_verify_ssl')+'</th><td>'+jamf_verify_ssl+'</td></tr>';
    jssrows = jssrows + '<tr><th>'+i18n.t('jamf.jamf_username')+'</th><td>'+jamf_username+'</td></tr>';
    jssrows = jssrows + '<tr><th>'+i18n.t('jamf.password_set')+'</th><td>'+jamf_password+'</td></tr>';
    if (jamf_enabled_int == 1 && jssuserdata['user'] !== "Unauthorized" && jssuserdata['user'] !== "404_Not_Found"){
        jssrows = jssrows + '<tr><th>'+i18n.t('jamf.institution')+'</th><td>'+jssuserdata['user']['institution']+'</td></tr>';
        jssrows = jssrows + '<tr><th>'+i18n.t('jamf.license_type')+'</th><td>'+jssuserdata['user']['license_type']+'</td></tr>';
        jssrows = jssrows + '<tr><th>'+i18n.t('jamf.product')+'</th><td>'+jssuserdata['user']['product']+'</td></tr>';
        jssrows = jssrows + '<tr><th>'+i18n.t('jamf.version')+'</th><td>'+jssuserdata['user']['version']+'</td></tr>';
        
        var jssprivileges = ''
        $.each(jssuserdata['user']['privileges'], function(i,d){
            jssprivileges = jssprivileges + d + "</br>";
        }) 
        
        jssrows = jssrows + '<tr><th>'+i18n.t('jamf.privileges')+'</th><td>'+jssprivileges+'</td></tr>'
    } else if (jamf_enabled_int == 1 && jssuserdata['user'] == "Unauthorized"){
        jssrows = jssrows + '<tr><th class="danger">'+i18n.t('error')+'</th><td class="danger">'+i18n.t('jamf.not_authorized')+'</td></tr>';
    } else if (jamf_enabled_int == 1 && jssuserdata['user'] == "404_Not_Found"){
        jssrows = jssrows + '<tr><th class="danger">'+i18n.t('error')+'</th><td class="danger">'+i18n.t('jamf.bad_server')+'</td></tr>';
    }
        
    $('#Jamf-System-Status').html(jssrows+'</tbody></table>') // Close table framework and assign to HTML ID
    
    $('#GetAllJamf').click(function (e) {
        // Disable button and unhide progress bar
        $('#GetAllJamf').html(i18n.t('jamf.processing')+'...');
        $('#Progress-Bar-Percent').text('0%');
        $('#GetAllJamf-Progress').removeClass('hide');
        $('#Progress-Space').removeClass('hide');
        $('#GetAllJamf').addClass('disabled');
        jamf_pull_all_running = 1;
        
        // Get JSON of all serial numbers
        $.getJSON(appUrl + '/module/jamf/pull_all_jamf_data', function (processdata) {
        
            // Set count of serial numbers to be processed
            var progressmax = (processdata.length);
            var progessvalue = 0;
            $('.progress-bar').attr('aria-valuemax', progressmax);
            
            // Process list of serial numbers
            for (var serialindex = 0; serialindex < progressmax; serialindex++) {
                // Get JSON for each serial number
                $.getJSON(appUrl + '/module/jamf/pull_all_jamf_data/'+processdata[serialindex], function (resultdata) {
                    // Calculate progress bar's percent
                    var processpercent = Math.round((((progessvalue+1)/progressmax)*100));
                    progessvalue++
                    $('.progress-bar').css('width', (processpercent+'%')).attr('aria-valuenow', processpercent);
                    $('#Progress-Bar-Percent').text(processpercent+'%');
                    
                    // Cleanup and reset when done processing serials
                    if ((progessvalue) == progressmax) {
                        // Make button clickable again and hide process bar elements
                        $('#GetAllJamf').html(i18n.t('jamf.pull_in_all'));
                        $('#GetAllJamf').removeClass('disabled');
                        jamf_pull_all_running = 0;
                        $("#Progress-Space").fadeOut(1200, function() {
                            $('#Progress-Space').addClass('hide')
                            var progresselement = document.getElementById('Progress-Space');
                            progresselement.style.display = null;
                            progresselement.style.opacity = null;
                        });
                        $("#GetAllJamf-Progress").fadeOut( 1200, function() {
                            $('#GetAllJamf-Progress').addClass('hide')
                            var progresselement = document.getElementById('GetAllJamf-Progress');
                            progresselement.style.display = null;
                            progresselement.style.opacity = null;
                            $('.progress-bar').css('width', 0+'%').attr('aria-valuenow', 0);
                        });
                    }
                });
            };
        });
    });
});

// Warning about leaving page if Jamf pull all is running
window.onbeforeunload = function() {
    if (jamf_pull_all_running == 1) {
        return i18n.t('jamf.leave_page_warning');
    } else {
        return;
    }
};
    
</script>

<?php $this->view('partials/foot'); ?>