<?php
                include_once  'template/m_header.php';
?>
<script type="text/javascript">
	//get GPS coordinates-------------------------------------------//
	function billLocation() {
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(showPosition2);
	    }
	}
	function showPosition2(position) {
	    document.getElementById('gps_x').value=position.coords.latitude; 
	    document.getElementById('gps_y').value=position.coords.longitude; 
	}
	
	function validateCheckIn(){
		var gps_x=document.getElementById('gps_x').value;	
		var gps_y=document.getElementById('gps_y').value;	
			
		if((gps_x==0)||(gps_y==0)){
			window.alert('Please Turn On GPS');
			return false;
		}else{
	  		document.getElementById('checkin_div').innerHTML=document.getElementById('loading').innerHTML;
			document.getElementById('checkin_form').submit();
		}
	}
	
</script>
<!-- ------------------------------------------------------------------------------------ -->

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:65px" /><br />Please Wait</div>
<div class="w3-container" style="margin-top:75px">
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}
?>	
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
  	<form id="checkin_form" action="index.php?components=authenticate&action=set_check_in" method="post">
	  	<input type="hidden" id="gps_x" name="gps_x" value="0" />
		<input type="hidden" id="gps_y" name="gps_y" value="0" />
	  <table align="center">
	  	<tr><td align="center"><div id="checkin_div"><a onclick="validateCheckIn()" style="cursor:pointer"><img src="images/check_in.png" style="width:150px; vertical-align:middle" /><br />Check In</a></div></td></tr>
	  </table>
	</form>
  </div>
</div>
</div>
<script type="text/javascript">
	billLocation();
</script>

<?php
                include_once  'template/m_footer.php';
?>