<?php
                include_once  'template/header.php';
?>
	<script src="js/md5.min.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />


<script type="text/javascript">
	function setLeave($user_id,$leave_id){
		$id1='type_'+$leave_id+'_'+$user_id;
		$id2='div_'+$leave_id+'_'+$user_id;
	  	document.getElementById($id2).innerHTML=document.getElementById('loading').innerHTML;
		if(document.getElementById($id1).checked) $sub_action='add'; else $sub_action='remove';
		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
		    var returntext=this.responseText;
		    	if(returntext=='Done'){
		    		document.getElementById($id2).innerHTML='<span style="color:green">Done</span>';
		    	}else{
		    		document.getElementById($id2).innerHTML='<span style="color:red">Error</span>';
		    	}
		    }
		  };
		  xhttp.open("GET", 'index.php?components=hr&action=allocate_update&user='+$user_id+'&leave='+$leave_id+'&sub_action='+$sub_action, true);
		  xhttp.send();
	}
	
	
</script>
<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:25px" /></div>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>
<form action="#" method="post">
	<input type="hidden" id="user_leave_allocate" name="user_leave_allocate" value="" />
	<div class="headerscrol" id="myHeader"  >
		<table id="myHeaderTB" align="center" style="font-family:Calibri; font-size:11pt;" border="1" bordercolor="white">
		<tr bgcolor="#467898" style="color:white; height:110px"><th class="shipmentTB4" width="120px"><div>Employee</div></th>
		<?php 
		for($i=0;$i<sizeof($leave_id);$i++){
			print '<th style="width: 3.75em;" class="rotate"><div>'.str_replace("(","<br />(",$leave_name[$i]).'</div></th>';
		}
		?>
		</tr>
	</table>
	</div>
	
	<div class="content">
	<table align="center" style="font-family:Calibri; font-size:11pt" border="1" bordercolor="white" >
		<?php 
		for($i=0;$i<sizeof($user_id);$i++){
			if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
			print '<tr bgcolor="'.$color.'"><td class="shipmentTB4" width="120px">'.ucfirst($user_name[$i]).'</td>';
			for($j=0;$j<sizeof($leave_id);$j++){
				$check='';
				if (in_array($leave_id[$j], $leave_allo[$user_id[$i]])) $check='checked="checked"';
				print '<td align="center" width="55px"><table cellspacing="0" align="center"><tr><td><input type="checkbox" '.$check.' id="type_'.$leave_id[$j].'_'.$user_id[$i].'" onchange="setLeave('."'$user_id[$i]','$leave_id[$j]'".')" /></td><td><div id="div_'.$leave_id[$j].'_'.$user_id[$i].'"></div></td></tr></table></td>';
			}
			print '</tr>';
		}
		?>
		</table>
	</div>
</form>
	
<script>
window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}
</script>

<?php
                include_once  'template/footer.php';
?>