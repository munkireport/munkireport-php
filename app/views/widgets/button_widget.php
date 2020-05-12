<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="<?=$widget_id?>">
        <div 
            class="panel-heading" 
            <?php if(isset($i18n_tooltip)):?> 
            data-i18n="[title]<?=$i18n_tooltip?>"
            <?php endif?>
            data-container="body">
            <h3 class="panel-title"><i class="fa <?=$icon?>"></i>
                <span data-i18n="<?=$i18n_title?>"></span>
                <span class="counter badge"></span>
                <list-link data-url="<?=$listing_link?>"></list-link>
            </h3>
        </div>
		<div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
    
    var apiUrl = "<?=$api_url?>";
    var widgetId = "<?=$widget_id?>";
    var listingLink = "<?=$listing_link?>";
    var i18nEmptyResult = "<?php echo isset($i18n_empty_result)?$i18n_empty_result:''?>"

	var body = $('#' + widgetId + ' div.panel-body');
	
	$.getJSON( appUrl + apiUrl, function( data ) {
		
		// Clear previous content
		body.empty();
        
        var buttons = JSON.parse('<?php echo json_encode($buttons)?>');
        var total_count = 0;
		
		// Calculate entries
		if(data.length){
			
			// render
			$.each(buttons, function(i, o){
                var button = data.find(x => x.label === o.label);
                var count = button ? button.count : 0;

                total_count = total_count + count;

                // Hide when count is zero
                if( o.hide_when_zero && count == 0){
                    return;
                }

                // Use localized label
                if( o.i18n_label){
                    label = i18n.t(o.i18n_label);
                }else{
                    label = o.label;
                }

                // Set default class to btn-info
                o.class = o.class ? o.class : 'btn-info';

                // Search component
                if( o.search_component ){
                    searchComponent = '/#'+encodeURIComponent(o.search_component);
                }else{
                    searchComponent = '';
                }

				body.append(
                    $('<a>')
                        .attr('href', appUrl+listingLink+searchComponent)
                        .css('min-width', '70px')
                        .addClass("btn " + o.class)
                        .toggleClass("disabled", count == 0)
                        .addClass(function(){ return count ? '' : 'disabled'})
                        .append(
                            $('<span>')
                                .addClass('bigger-150')
                                .text(count)
                        )
                        .append('<br>')
                        .append(document.createTextNode(label))
                ).append(' ')
			});
        }
        if(total_count == 0){
            if (i18nEmptyResult){
                body.append(i18n.t(i18nEmptyResult));
            }else{
                body.append(i18n.t('no_clients'));
            }
        }
	});
});	
</script>
