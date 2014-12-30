<?php //Initialize models needed for the table
$machine = new Machine_model($serial_number);
$location = new location_model($serial_number);
?>

	<h2>Location</h2>
	<table class="table table-striped">
	<tr>
		<td>Address: <?=$location->address?></td>
	</tr>
	<tr>
		<td>Approximate location within <?=$location->accuracy?> meters</td>
	</tr>
	</table>

    <meta charset="utf-8">
	 <style>
     	#map-canvas {
        width: 512px;
        height: 512px
      }
    </style>  
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script>
function initialize() {
  var myLatlng = new google.maps.LatLng(<?=$location->latitude?>,<?=$location->longitude?>);
  var mapOptions = {
    zoom: 15,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: '<?=$machine->computer_name?>'
  });
  
var myCity = new google.maps.Circle({
  center:myLatlng,
  radius:150,
  strokeColor:"#0000FF",
  strokeOpacity:0.2,
  strokeWeight:1,
  fillColor:"#0000FF",
  fillOpacity:0.2
  });

myCity.setMap(map);

}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
    <div id="map-canvas"></div>

    