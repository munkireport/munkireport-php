<div class="col-lg-8 col-md-6">
    <div class="card" id="events-widget">
        <div class="card-header" data-container="body" data-i18n="[title]events.widget_title">
            <i class="fa fa-bullhorn"></i>
            <span data-i18n="event_plural"></span>
            <a href="/show/listing/event/event" class="pull-right text-reset"><i class="fa fa-list"></i></a>
        </div>
        <div class="list-group scroll-box" style="max-height: 308px"></div>
    </div>
</div>

<script src="<?php echo asset('assets/js/event/format_event_data.js')?>"></script>

<script>
$(document).on('appUpdate', function(){
    
    var list = $('#events-widget div.scroll-box'),
        icons = {
            danger:'fa-times-circle',
            warning: 'fa-warning',
            info: 'fa-info-circle',
            success: 'fa-check-circle'
        },
        update_time = function(){
            $( "time" ).each(function( index ) {
                var date = new Date($(this).attr('datetime') * 1000);
                $(this).text(moment(date).fromNow());
            });
        },
        get_module_item  = function(item){
            formatEventData(item);
            
            // Get appropriate icon
            var icon = '<i class="text-'+item.type+' fa '+icons[item.type]+'"></i> ',
                url = appUrl+'/clients/detail/'+item.serial_number+item.tab,
                date = new Date(item.timestamp * 1000);
            
            return $('<a class="list-group-item">')
                        .attr('href', url)
                        .append($('<span class="pull-right" style="padding-left: 10px">')
                            .text(moment(date).fromNow())
                            
                        )
                        .append(icon)
                        .append($('<span>').text(item.machine.computer_name))
                        .append($('<span class="d-sm-none d-md-inline"> | </span>'))
                        .append($('<br class="d-none d-sm-block d-md-none">'))
                        .append($('<span>').text(item.module + ' '+item.msg))
        };

    $.getJSON( appUrl + '/module/event/get/50') // TODO make this configurable
    .done(function( data ) {

        if(data.error)
        {
            alert(data.error)
            if(data.reload)
            {
                location.reload();
            }
        }
        list.empty();

        var arrayLength = data.items.length
        if (arrayLength)
        {
            for (var i = 0; i < arrayLength; i++) {
                list.append(get_module_item(data.items[i]));
            }

            update_time();
        }
        else
        {
            list.append('<span class="list-group-item">No messages</span>');
        }


    }).fail(function( jqxhr, textStatus, error ) {
        list.empty();
        var err = textStatus + ", " + error;
        list.append('<span class="list-group-item list-group-item-danger">'+
            "Request Failed: " + err+'</span>')
    });
});
</script>
