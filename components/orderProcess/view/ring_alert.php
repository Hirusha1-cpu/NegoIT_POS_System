<html>
<head>
<script type="text/javascript">
	function hideAlert(){
		document.getElementById("div_bell").style.display = "block";
		document.getElementById("div_alert").style.display = "none";
	}
	
	function displayAlert(){
		document.getElementById("div_bell").style.display = "none";
		document.getElementById("div_alert").style.display = "block";
		setTimeout(hideAlert, 5000);
	}
	
	function getAlerts(){
	  var id_list0=document.getElementById('id_list0').value;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
		    var returntext=this.responseText;
		    if(returntext!=id_list0){
				document.getElementById("audio_button").click();
				displayAlert();
				document.getElementById('id_list0').value = returntext;
		    }
	    }
	  };
	  xhttp.open("GET", 'index.php?components=order_process&action=get_alert', true);
	  xhttp.send();
	}	
		
var x = setInterval(function() {
	$current_count=parseInt(document.getElementById('count').value);
	$new_count=$current_count-1;
	document.getElementById('count').value=$new_count;
	if(($new_count==0)||($new_count==-5)||($new_count==-10)||($new_count==-20)||($new_count==-60)||($new_count<-120)){
		getAlerts();
		document.getElementById('count').value=20;
	}
	if($new_count==-600){
		location.reload();
	}
}, 1000);
</script>
</head>
<body>
<audio id="myAudio">
  <source src="audio/bell1.mp3" type="audio/mpeg">
</audio>


<input type="hidden" id="id_list0" value="" />
<br /><br /><br /><br /><br />
<table align="center" style="font-family:Calibri">
<tr><td align="center">Timer : <input type="text" id="count" value="20" style="width:50px; text-align:center" /> &nbsp;&nbsp;<button onclick="playAudio()" type="button" id="audio_button">Test Bell</button></td></tr>
<tr><td align="center"></td><br /></tr>
<tr><td align="center"><div id="div_bell"><img src="images/bell.png" /></div><div style="display:none" id="div_alert"><img src="images/radiacion.gif" /></div></td></tr>
</table>


<script type="text/javascript">
	var player = document.getElementById("myAudio"); 
	
	function playAudio() { 
	    player.play(); 
	} 
	getAlerts();
</script>
</body>
</html>