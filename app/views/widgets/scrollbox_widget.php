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
        <div class="list-group scroll-box"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
    
    var widgetId = "<?=$widget_id?>"
    var box = $('#<?=$widget_id?> div.scroll-box');
    var showCounter = <?php if(isset($show_counter) && $showcounter):?>true<?php else:?>false<?php endif?>; 
    var apiUrl = "<?=$api_url?>"
    var searchKey = "<?=$search_key?>"
    var listingLink = "<?=$listing_link?>"
    var i18nEmptyResult = "<?php echo isset($i18n_empty_result)?$i18n_empty_result:''?>"
    var badgeType = "<?php echo isset($badge)?$badge:'none'?>"
    var urlType = "<?php echo isset($url_type)?$url_type:'listing'?>"
    var generateListgroupItem = function(url, content, badge){
        return $('<a>')
            .addClass("list-group-item")
            .attr('href', appUrl + url)
            .text(content)
            .append(badge)
    }

	$.getJSON( appUrl + apiUrl, function( data ) {
		
		box.empty();

		if(data.length){
			$.each(data, function(i,d){
                if(badgeType == 'reg_timestamp'){
                    var badge = '<span class="pull-right">'+moment(d.reg_timestamp * 1000).fromNow()+'</span>';
                }else if(badgeType == 'percent'){
                    var badge = '<span class="badge pull-right">'+d.percent+'%</span>';
                }else if(badgeType == 'count'){
                    var badge = '<span class="badge pull-right">'+d.count+'</span>';
                }else if(badgeType == 'none'){
                    var badge = '';
                }else{
                    var badge = '<span class="badge pull-right">'+d[badgeType]+'</span>';
                }
                if( ! d[searchKey]){
					box.append('<span class="list-group-item">'+i18n.t('empty')+badge+'</span>')
				}
                else{
                    if(urlType == 'client_detail'){
                        box.append(generateListgroupItem('/clients/detail/'+d.serial_number, d[searchKey], badge));
                    }else{
                        box.append(generateListgroupItem(listingLink+'#'+d[searchKey], d[searchKey], badge));
                    }
                }
			});
		}
		else{
            if (i18nEmptyResult){
                box.append('<span class="list-group-item">'+i18n.t(i18nEmptyResult)+'</span>');
            }else{
                box.append('<span class="list-group-item">'+i18n.t('no_clients')+'</span>');
            }
        }
        
        if(showCounter){
            $('#'+widgetId+' .counter').text(data.length);
        }
	});
});	
</script>
