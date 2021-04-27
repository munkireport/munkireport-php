<div class="col-lg-4 col-md-6">
    <div class="card" id="<?=$widget_id?>">
        <div 
            class="card-header"
            <?php if(isset($i18n_tooltip)):?> 
            data-i18n="[title]<?=$i18n_tooltip?>"
            <?php endif?>
            data-container="body">
            <i class="fa <?=$icon?>"></i>
            <span data-i18n="<?=$i18n_title?>"></span>
            <span class="counter badge"></span>
            <a href="<?=$listing_link?>" class="pull-right text-reset"><i class="fa fa-list"></i></a>
        </div>
        <div class="scroll-box">
            <ul class="list-group"></ul>
        </div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {
    
    var widgetId = "<?=$widget_id?>"
    var box = $('#<?=$widget_id?> div.scroll-box');
    var listGroup = $('#<?=$widget_id?> .list-group');
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
            .addClass("list-group-item-action")
            .attr('href', appUrl + url)
            .text(content)
            .append(badge)
    }

	$.getJSON( appUrl + apiUrl, function( data ) {

        listGroup.empty();

		if(data.length){
			$.each(data, function(i,d){
                if(badgeType == 'reg_timestamp'){
                    var badge = '<span class="pull-right">'+moment(d.reg_timestamp * 1000).fromNow()+'</span>';
                }else if(badgeType == 'percent'){
                    var badge = '<span class="badge badge-secondary pull-right">'+d.percent+'%</span>';
                }else if(badgeType == 'count'){
                    var badge = '<span class="badge badge-secondary pull-right">'+d.count+'</span>';
                }else if(badgeType == 'none'){
                    var badge = '';
                }else{
                    var badge = '<span class="badge badge-secondary pull-right">'+d[badgeType]+'</span>';
                }
                if( ! d[searchKey]){
					box.append('<span class="list-group-item">'+i18n.t('empty')+badge+'</span>')
				}
                else{
                    if(urlType == 'client_detail'){
                        listGroup.append(generateListgroupItem('/clients/detail/'+d.serial_number, d[searchKey], badge));
                    }else{
                        listGroup.append(generateListgroupItem(listingLink+'#'+d[searchKey], d[searchKey], badge));
                    }
                }
			});
		}
		else{
            if (i18nEmptyResult){
                listGroup.append('<li class="list-group-item">'+i18n.t(i18nEmptyResult)+'</li>');
            }else{
                listGroup.append('<li class="list-group-item">'+i18n.t('no_clients')+'</li>');
            }
        }
        
        if(showCounter){
            $('#'+widgetId+' .counter').text(data.length);
        }
	});
});	
</script>
