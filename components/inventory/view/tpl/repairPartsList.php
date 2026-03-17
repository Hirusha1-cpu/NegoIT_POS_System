<?php
if($_GET['action']=='repair_parts_list_disabled'){
	$button_name1='Item List'; 
	$button_lnk1='repair_parts_list';
}else{
	$button_name1='Disabled Item List';
	$button_lnk1='repair_parts_list_disabled';
}
?>
<script type="text/javascript">
	function addRepairPart(){
		$name=document.getElementById('name').value; 
		$cost=document.getElementById('cost').value; 
		$drawer=document.getElementById('drawer').value; 
		if(($name!='')&&($cost>0)){
			window.location = 'index.php?components=inventory&action=add_repair_part&type=<?php print $_GET['type']; ?>&category=<?php print $_GET['category']; ?>&store=<?php print $_GET['store']; ?>&name='+$name+'&cost='+$cost+'&drawer='+$drawer;
		}else{ window.alert('Part Name Cannot be Empty!'); }
	}
	function updateRepairPart($id){
		$new_name=document.getElementById('part'+$id).value; 
		$new_drawer=document.getElementById('drawer'+$id).value; 
			  document.getElementById('partaction|'+$id).innerHTML=document.getElementById("loading").innerHTML;
			  var xhttp = new XMLHttpRequest();
			  xhttp.onreadystatechange = function() {
			    if (this.readyState == 4 && this.status == 200) {
			    var returntext=this.responseText;
			    if(returntext=='Done'){
			    	document.getElementById('partaction|'+$id).innerHTML='<span style="color:green;">'+returntext+'</span>';
			    }else{
			    	document.getElementById('partaction|'+$id).innerHTML='<span style="color:red;">'+returntext+'</span>';
			    }
			    }
			  };
			  xhttp.open("GET", 'index.php?components=inventory&action=update_repair_part&id='+$id+'&new_name='+$new_name+'&new_drawer='+$new_drawer, true);
			  xhttp.send();
	}
	function deleteRepairPart($id){
		var check= confirm("Do you want Delete this Repair Part? \n\nNote: If it cannot be deleted, it will be disabled");
		if (check== true){
			  document.getElementById('partaction|'+$id).innerHTML=document.getElementById("loading").innerHTML;
			  var part=document.getElementById('part'+$id).value;
			  var drawer=document.getElementById('drawer'+$id).value;
			  var xhttp = new XMLHttpRequest();
			  xhttp.onreadystatechange = function() {
			    if (this.readyState == 4 && this.status == 200) {
			    var returntext=this.responseText;
			    if(returntext!='Error'){
			    	document.getElementById('part|'+$id).innerHTML='<span style="color:gray;">'+part+'</span>';
			    	document.getElementById('partdrawer|'+$id).innerHTML='<span style="color:gray;">'+drawer+'</span>';
			    	document.getElementById('partaction|'+$id).innerHTML='<span style="color:green;">'+returntext+'</span>';
			    }else{
			    	document.getElementById('partaction|'+$id).innerHTML='<span style="color:red;">'+returntext+'</span>';
			    }
			    }
			  };
			  xhttp.open("GET", 'index.php?components=inventory&action=delete_repair_part&id='+$id, true);
			  xhttp.send();
		}
	}
	function enableRepairPart($id){
		var check= confirm("Do you want Enable This Repair Part?");
		if (check== true)
		window.location = 'index.php?components=inventory&action=enable_repair_part&type=<?php print $_GET['type']; ?>&category=<?php print $_GET['category']; ?>&store=<?php print $_GET['store']; ?>&id='+$id;
	}
</script>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:30px" /></div>

<table align="center" border="0" style="font-family:Calibri; font-size:11pt" width="80%" bgcolor="#F5F5F5">
<tr><td align="center" style="font-weight:bold; color:navy;">REPAIR ITEM INVENTORY</td>
<?php 
print '<td width="130px"><input type="button" style="width:100%" value="'.$button_name1.'" onclick="window.location = \'index.php?components=inventory&action='.$button_lnk1.'&category='.$_GET['category'].'&store='.$_GET['store'].'&type=4\'" /></td>';
print '<td width="70px"><input type="button" value="Back" onclick="window.location = '."'index.php?components=inventory&action=show_all_item&category=".$_GET['category']."&store=".$_GET['store']."&type=4'".'" /></td>';
?></tr>
</table>
<table width="100%" style="font-family:Calibri">
<tr><td align="center"><?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'; 
	}
	?></td></tr>
</table>

<table align="center" style="font-family:Calibri">
<tr><td valign="top">
	<table align="center" bgcolor="#EEEEEE">
	<tr><td colspan="3"></td></tr>
	<tr><td width="20px"></td><td>Part Name </td><td><input type="text" name="name" id="name" value="" /></td><td width="20px"></td></tr>
	<tr><td width="20px"></td><td>Cost </td><td><input type="text" name="cost" id="cost" value="" /></td><td width="20px"></td></tr>
	<tr><td width="20px"></td><td>Drawer No </td><td><input type="text" name="drawer" id="drawer" value="" /></td><td width="20px"></td></tr>
	<tr><td colspan="3" align="center"><input type="button" value="Add New Part" onclick="addRepairPart()" /></td></tr>
	</table>
</td><td width="50px"></td><td>
	<table>
	<tr style="background-color:#467898;color :white;" ><th>Part Name</th><th>Drawer No</th><th>Action</th></tr>
	<?php 
	if($_GET['action']=='repair_parts_list'){
		for($i=0;$i<sizeof($part_id);$i++){
			if(($i%2)==0) $color='#EAEAEA'; else $color='#F5F5F5';
			print '<tr bgcolor="'.$color.'"><td><div id="part|'.$part_id[$i].'"><input type="text" id="part'.$part_id[$i].'" value="'.$part_name[$i].'" style="width:250px" /></a></td><td align="center"><div id="partdrawer|'.$part_id[$i].'"><input type="text" id="drawer'.$part_id[$i].'" value="'.$part_drawer[$i].'" style="width:60px" /></div></td><td align="center"><div id="partaction|'.$part_id[$i].'"><input type="button" value="Update" onclick="updateRepairPart('.$part_id[$i].')" /> &nbsp;<a style="color:red; cursor:pointer;" onclick="deleteRepairPart('.$part_id[$i].')">x</a>&nbsp;&nbsp;</div></td></tr>';
		}
	}
	if($_GET['action']=='repair_parts_list_disabled'){
		print '<tr><th colspan="3" bgcolor="#DDDDDD" style="color:#333333">Disabled Parts</th></tr>';
		for($i=0;$i<sizeof($dispart_id);$i++){
			if(($i%2)==0) $color='#EAEAEA'; else $color='#F5F5F5';
			print '<tr bgcolor="'.$color.'" ><td><input type="text" value="'.$dispart_name[$i].'" disabled="disabled" /></td><td align="center"><input type="text" value="'.$dispart_name[$i].'" disabled="disabled" style="width:60px" /></td><td align="center"><input type="button" value="Enable" onclick="enableRepairPart('.$dispart_id[$i].')" /> </td></tr>';
		} 
	}
	?>
	</table>
</td></tr>
</table>