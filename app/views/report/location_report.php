<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

  <div class="row">

    <div class="col-md-12" id="map" style="height:500px"></div>

  </div>

</div>  <!-- /container -->

<script src="https://maps.googleapis.com/maps/api/js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/googlemaps/markerclusterer.js"></script>
<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<script>


var mapObj = {};

mapObj.machines = null;
mapObj.map = null;
mapObj.markerClusterer = null;
mapObj.markers = [];
mapObj.infoWindow = null;
mapObj.options = {gridSize: 30, maxZoom: 20};

mapObj.init = function() {
    var latlng = new google.maps.LatLng(39.91, 116.38);
    var options = {
            'zoom': 2,
            'center': latlng,
            'mapTypeId': google.maps.MapTypeId.ROADMAP
        };

    mapObj.map = new google.maps.Map($('#map')[0], options);
    
    $.getJSON( appUrl + '/module/location/get_map_data', function( data ) {
        mapObj.machines = data;
        mapObj.infoWindow = new google.maps.InfoWindow();
        mapObj.showMarkers();
    });
    
};

mapObj.showMarkers = function() {
    mapObj.markers = [];

    if (mapObj.markerClusterer) {
        mapObj.markerClusterer.clearMarkers();
    }

    var numMarkers = mapObj.machines.length;

    for (var i = 0; i < numMarkers; i++) {
        var titleText = mapObj.machines[i].serial_number;
        if (titleText === '') {
          titleText = 'No title';
        }



        var latLng = new google.maps.LatLng(mapObj.machines[i].latitude,
            mapObj.machines[i].longitude);

        var imageUrl = 'http://chart.apis.google.com/chart?cht=mm&chs=24x32&chco=' +
            'FFFFFF,008CFF,000000&ext=.png';
        var markerImage = new google.maps.MarkerImage(imageUrl,
            new google.maps.Size(24, 32));

        var marker = new google.maps.Marker({
          'position': latLng,
          'icon': markerImage
        });

        var fn = mapObj.markerClickFunction(mapObj.machines[i], latLng);
        google.maps.event.addListener(marker, 'click', fn);
        mapObj.markers.push(marker);
    }

    mapObj.markerClusterer = new MarkerClusterer(mapObj.map, mapObj.markers, mapObj.options);
};

// Show popup
mapObj.markerClickFunction = function(machine, latlng) {
    return function(e) {
    e.cancelBubble = true;
    e.returnValue = false;
    if (e.stopPropagation) {
      e.stopPropagation();
      e.preventDefault();
    }
    var title = machine.serial_number;
    var url = machine.photo_url;
    var fileurl = machine.photo_file_url;

    var infoHtml = '<div class="info"><h3>' + title +
      '</h3><div class="info-body">' +
      '<a href="' + url + '" target="_blank"></a></div>' +
      '<br/>' +
      '<a href="' + machine.owner_url + '" target="_blank">' + machine.owner_name +
      '</a></div></div>';

    mapObj.infoWindow.setContent(infoHtml);
    mapObj.infoWindow.setPosition(latlng);
    mapObj.infoWindow.open(mapObj.map);
    };
};

google.maps.event.addDomListener(window, 'load', mapObj.init);

</script>

<?php $this->view('partials/foot'); ?>