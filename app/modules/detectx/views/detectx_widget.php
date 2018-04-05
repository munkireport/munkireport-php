<div class="col-lg-4 col-md-6">

  <div class="panel panel-default" id="detectx-widget">

    <div class="panel-heading" data-container="body" title="DetectX status">

      <h3 class="panel-title"><i class="fa fa-times-circle-o"></i>
        <span data-i18n="detectx.widget.title"></span>
        <list-link data-url="/show/listing/detectx/detectx"></list-link>
      </h3>


    </div>
    <div class="panel-body text-center"></div>

  </div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

  $.getJSON( appUrl + '/module/detectx/get_stats', function( data ) {
    if(data.error){
      //alert(data.error);
      return;
    }

    var panel = $('#detectx-widget div.panel-body'),
    baseUrl = appUrl + '/show/listing/detectx/detectx/#';
    panel.empty();
    // Set statuses
    panel.append(' <a href="'+baseUrl+'clean" class="btn btn-success"><span class="bigger-150">'+data.Clean+'</span><br>'+i18n.t('detectx.widget.clean')+'</a>');
    panel.append(' <a href="'+baseUrl+'issues" class="btn btn-warning"><span class="bigger-150">'+data.Issues+'</span><br>'+i18n.t('detectx.widget.issues')+'</a>');
	panel.append(' <a href="'+baseUrl+'infected" class="btn btn-danger"><span class="bigger-150">'+data.Infected+'</span><br>'+i18n.t('detectx.widget.infected')+'</a>');

  });
});


</script>
