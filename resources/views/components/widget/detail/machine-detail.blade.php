<!-- blade version of machine_detail_widget1 -->
<div class="col">
    <div class="row">
        <div class="col">
            <img id="apple_hardware_icon" class="img-fluid" alt="An image of this hardware model">
        </div>
        <div class="col">
            <span>macOS <span class="machine-os_version"></span></span><br>
            <span><span class="machine-physical_memory"></span> GB</span><br>
            <span><span class="machine-serial_number"></span></span><br>
            <span><span class="reportdata-remote_ip"></span></span><br>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <span class="machine-machine_desc"></span> <a class="machine-refresh-desc" href=""><i class="fa fa-refresh"></i></a>
        </div>
    </div>
</div>

<script>
  var apple_hardware_icon_url = "{{ config('_munkireport.apple_hardware_icon_url') }}";
  $('#apple_hardware_icon')
    .attr('src', apple_hardware_icon_url.replace('%s&amp;', serialNumber.substring(8) + '&' ))

  // ------------------------------------ Refresh machine description
  $('.machine-refresh-desc')
    .attr('href', appUrl + '/module/machine/model_lookup/' + serialNumber)
    .click(function(e){
      e.preventDefault();
      // show that we're doing a lookup
      $('.machine-machine_desc').text(i18n.t('loading'));
      $.getJSON( appUrl + '/module/machine/model_lookup/' + serialNumber, function( data ) {
        if(data['error'] == ''){
          $('.machine-machine_desc').text(data['model']);
        }
        else{
          $('.machine-machine_desc').text(data['error']);
        }
      });
    });
</script>
