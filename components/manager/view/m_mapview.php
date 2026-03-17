<?php
                include_once  'template/m_header.php';
?>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?php print $map_api; ?>&callback=initMap">
</script>
<script type="text/javascript">
function initMap() {
  var center = {lat: 7.4772701, lng: 80.175021};
  var locations = [  <?php for($i=0;$i<sizeof($map_cust);$i++){	print "['Salesman : $map_sm[$i]\\nCustomer : $map_cust[$i]',   $map_x[$i], $map_y[$i]],";	} ?>  ];
  var pointers = [  <?php for($i=0;$i<sizeof($sm_pinter);$i++){	print $sm_pinter[$i].',';	} ?>  ];
   
   var iconURLPrefix = 'images/map_pin/';
   var icons = [
      iconURLPrefix + '1.png',
      iconURLPrefix + '2.png',
      iconURLPrefix + '3.png',
      iconURLPrefix + '4.png',
      iconURLPrefix + '5.png',
      iconURLPrefix + '6.png',      
      iconURLPrefix + '7.png',
      iconURLPrefix + '8.png',
      iconURLPrefix + '9.png',
      iconURLPrefix + '10.png',
      iconURLPrefix + '11.png',
      iconURLPrefix + '12.png',
      iconURLPrefix + '13.png',
      iconURLPrefix + '14.png',
      iconURLPrefix + '15.png',
      iconURLPrefix + '16.png',
      iconURLPrefix + '17.png',
      iconURLPrefix + '18.png',
      iconURLPrefix + '19.png',
      iconURLPrefix + '20.png'
    ];
    var iconsLength = icons.length;
var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 8,
    center: center
  });
var infowindow =  new google.maps.InfoWindow({});
var marker, count;


for (count = 0; count < locations.length; count++) {
	var iconCounter = pointers[count];
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(locations[count][1], locations[count][2]),
      map: map,
      icon: icons[iconCounter],
      title: locations[count][0]
    });
google.maps.event.addListener(marker, 'click', (function (marker, count) {
      return function () {
        infowindow.setContent(locations[count][0]);
        infowindow.open(map, marker);
      }
    })(marker, count));
  }
}
</script>
<style type="text/css">
#map {
  height: 400px;
  width: 100%;
  background-color: grey;
}
</style>
<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
		<table align="center" style="font-size:11pt"><tr><td>
		<?php 
			if(isset($_REQUEST['message'])){
				if($_REQUEST['re']=='success') $color='green'; else $color='red';
			print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />'; 
			}
		?></td></tr></table>
		
		<div id="map"></div>
  </div>
</div>
</div>

<?php
                include_once  'template/m_footer.php';
?>