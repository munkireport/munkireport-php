<?php $this->view('partials/head'); ?>

<div class="container">

  <div class="row">


	<h3 class="col-lg-12" data-i18n="notification.title"></h3>


	<div class="col-lg-12" id="notification_list">
        <div data-i18n="listing.loading" id="loading"></div>
    </div>


  </div> <!-- /row -->
</div>  <!-- /container -->


<script>

$(document).on('appReady', function(e, lang) {

    var url = appUrl + '/module/notification/get_list',
        bu_url = appUrl + '/admin/get_bu_data',
        bu_choices = [
            [-1, i18n.t("notification.business_units_all")]
        ],
        columns = [
            'notification_title',
            'notification_how',
            'notification_who',
            'notification_module',
            'notification_msg',
            'notification_severity',
            'notification_enabled',
            'business_unit'
        ],
        fields = [
            {
                name: "notification_title",
                label: i18n.t("notification.notification_title"),
                type: "text"
            },
            {
                name: "notification_how",
                label: i18n.t("notification.notification_how"),
                type: "select",
                choices: [
                    ['email', i18n.t("email")]
                ]
            },
            {
                name: "notification_who",
                label: i18n.t("notification.notification_who"),
                type: "email"
            },
            {
                name: "business_unit",
                label: i18n.t("notification.business_unit"),
                type: "select",
                choices: bu_choices
            },
            {
                name: "notification_module",
                label: i18n.t("notification.notification_module"),
                type: "select",
                choices: [
                    ['%', 'All Modules'],
                    ['disk', 'disk'],
                    ['munkireport', 'munkireport'],
                    ['reportdata', 'reportdata']
                ]
            },
            {
                name: "notification_severity",
                label: i18n.t("notification.notification_severity"),
                type: "select",
                choices: [
                    ['%', i18n.t("notification.all_levels")],
                    ['info', i18n.t("info")],
                    ['success', i18n.t("success")],
                    ['warning', i18n.t("warning")],
                    ['danger', i18n.t("danger")]
                ]
            },
            {
                name: "id",
                type: "hidden"
            }
        ],
        // -------------------- editNotification --------------------
        editNotification = function(){
            var conf = {
                title: 'notification.edit',
                ok: "dialog.save",
                delete: "delete",
                data: $(this).data()
            }
            showEditModal(conf);
        },
        
        // -------------------- newNotification --------------------
        newNotification = function(){
            var conf = {
                title: 'notification.new',
                ok: "dialog.save"
            }
            showEditModal(conf);
        },
        
        // -------------------- prepareDeleteNotification --------------------
        prepareDeleteNotification = function(){
            
        },

        // -------------------- showEditModal --------------------
        showEditModal = function(conf){
            var thisForm = $('<form class="form-horizontal">'),
                modalBody = $('#myModal .modal-body')
                    .empty()
                    .append(thisForm),
                myData = conf.data || {};

            $.each(fields, function(j, d){
                if(myData[d.name]){
                    d.value = myData[d.name];
                }
                else {
                    d.value = '';
                }
            });
            var form = new FormForm( thisForm, fields );
            form.render();
            $('#myModal .modal-title').text(i18n.t(conf.title));
            $('#myModal button.ok')
    			.text(i18n.t(conf.ok))
    			.off()
    			.click(function(){
                    var jqxhr = $.post( appUrl + "/module/notification/save", thisForm.serializeArray())
                        .done(function(data){
                            renderNotificationList();
                            $('#myModal').modal('hide');
                        })
                        .fail(function(jqXHR, textStatus, errorThrown){
                                alert('Request failed: '+textStatus+' '+errorThrown);
                        });
                });
            
            // Render delete button
            if(conf.delete){
                $('#myModal .modal-footer button.btn-danger')
                    .text(i18n.t(conf.delete))
                    .removeClass('hidden')
                    .data(myData)
                    .off()
                    .click(prepareDeleteNotification);
            }else{
                $('#myModal .modal-footer button.btn-danger')
                    .addClass('hidden');
            }
            
            // Show modal    
            $('#myModal').modal('show');
        },
        
        // -------------------- renderNotificationList --------------------
        renderNotificationList = function(){
            $.getJSON(url, function(data){
                var table = $('<table class="table table-striped">'),
                    thead = $('<thead>')
                    tr = $('<tr>'),
                    tbody = $('<tbody>');
                // Write header    
                $.each(columns, function(i,col){
                    tr.append($('<th>').text(i18n.t('notification.'+col)));
                });
                tr.append($('<th>')
                    .append($('<button class="btn btn-default btn-xs">')
                        .text('+')
                        .click(newNotification)));
                thead.append(tr);
                
                // Write body
                $.each(data, function(i,el){
                    var row = $('<tr>');
                    $.each(columns, function(j,col){
                        row.append($('<td>').text(el[col]));
                    });
                    // Add edit button
                    row.append($('<td>')
                            .append($('<button class="btn btn-default btn-xs">')
                                .data(el)
                                .text(i18n.t('edit'))
                                .click(editNotification)));
                    tbody.append(row);
                });
                $('#notification_list')
                    .empty()
                    .append(table
                        .append(thead)
                        .append(tbody));
                
                console.log(data);
            });

        };
    // End of vars
    
    // Add delete button to footer
    $('.modal-footer').prepend('<button type="button" class="btn btn-danger hidden pull-left"></button>');
    
    // Retrieve business units
    $.getJSON(bu_url, function(data){
        
        // Add business units to list
        $.each(data, function(i, el){
            bu_choices.push([el.unitid, el.name])
        });
        
        // Render list
        renderNotificationList();

    });

});

</script>

<?php $this->view('partials/foot'); ?>
