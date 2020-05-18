<div class="col-lg-6 col-md-6">
    <div class="panel panel-default" id="<?=$widget_id?>">
        <div 
            class="panel-heading" 
            <?php if(isset($i18n_tooltip)):?> 
            data-i18n="[title]<?=$i18n_tooltip?>"
            <?php endif?>
            >
            <h3 class="panel-title"><i class="fa <?=$icon?>"></i>
                <span data-i18n="<?=$i18n_title?>"></span>
                <span class="counter badge"></span>
                <list-link data-url="<?=$listing_link?>"></list-link>
            </h3>
        </div>
		<div class="panel-body">

			<svg style="width:100%"></svg>

		</div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appReady', function(e, lang) {
    
    var widgetId = "<?=$widget_id?>"
    var box = $('#<?=$widget_id?> div.scroll-box');
    var showCounter = <?php if(isset($show_counter) && $showcounter):?>true<?php else:?>false<?php endif?>; 
    var apiUrl = "<?=$api_url?>"
    var searchKey = "<?=$search_key?>"
    var listingLink = "<?=$listing_link?>"
    var i18nEmptyResult = "<?php echo isset($i18n_empty_result)?$i18n_empty_result:''?>"
    var badgeType = "<?php echo isset($badge)?$badge:'default'?>"
    var urlType = "<?php echo isset($url_type)?$url_type:'listing'?>"

    <?php 
        $graph_margins = ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 70];

        if(isset($margin) && is_array($margin)){
            $graph_margins = array_merge($graph_margins, $margin);
        }
        
        if( ! isset($margin) || ! is_string($margin)){
            $margin = json_encode($graph_margins);
        }
    ?>

	var conf = {
		url: appUrl + apiUrl, // Url for json
        widget: widgetId, // Widget id
        margin: <?=$margin?>,
        <?php if(isset($search_component)):?>
		elementClickCallback: function(e){
			var label = e.data.label;
			window.location.href = appUrl + listingLink + '#' + <?=$search_component?> ;
        },
        <?php endif?>
        <?php if(isset($label_modifier)):?>
		labelModifier: function(label){
			return <?=$label_modifier?>;
        }
        <?php endif?>
	};

	mr.addGraph(conf);

});	
</script>
