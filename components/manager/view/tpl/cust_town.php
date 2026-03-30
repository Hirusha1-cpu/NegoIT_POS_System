<script type="text/javascript">
	function deleteCustTown($id){
		var check= confirm("Do you want to Delete this Town?");
	 if (check== true)
		window.location = 'index.php?components=<?php print $components; ?>&action=delete_custtown&id='+$id;
	}
	
function setTownDefault($town_id){
	$town_default=document.getElementById('town_default').value;
	document.getElementById('tmp').innerHTML=document.getElementById('div_btn').innerHTML;
	document.getElementById('div_btn').innerHTML=document.getElementById('loading').innerHTML;
		
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
		    var returntext=this.responseText;
			if(returntext=='Done'){
				document.getElementById('div_twdf_'+$town_id).innerHTML=document.getElementById('ok').innerHTML;
				document.getElementById('town_default').value=$town_id;
				if($town_default!='') document.getElementById('div_twdf_'+$town_default).innerHTML='<input type="checkbox" onclick="setTownDefault('+$town_default+')" />';
			}else{
				document.getElementById('div_btn').innerHTML='<span style="color:red">Error</span>';
			}
			document.getElementById('div_btn').innerHTML=document.getElementById('tmp').innerHTML;
		}
	};
	xhttp.open("GET", 'index.php?components=manager&action=set_town_default&town_id='+$town_id, true);
	xhttp.send();
}
</script>

	<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
	<div id="ok" style="display:none"><img src="images/action_check.gif" /></div>
	<input type="hidden" id="town_default" value="<?php print $town_default; ?>" />
	<div id="tmp" style="display:none"></div>
	
	<table align="center" bgcolor="#E5E5E5" style="font-size:11pt; font-family:Calibri" border="0">
	<?php if($_GET['action']=='show_custtown'){ ?>
	<form method="post" action="index.php?components=<?php print $components; ?>&action=add_custtown">
	<tr height="50px"><td width="50px"></td><td>New Town</td><td><input type="text" name="name" id="name" /></td><td><div id="div_btn"><input type="submit" value="Add" /></div></td><td width="50px"></td></tr>
	</form>
	<?php }if($_GET['action']=='edit_custtown'){ 
		$a=array_search($_GET['id'],$town_id);
	?>
	<form method="post" action="index.php?components=<?php print $components; ?>&action=update_custtown">
	<input type="hidden" name="town_id" value="<?php print $_GET['id']; ?>" />
	<tr height="50px"><td width="50px"></td><td>Town Name</td><td><input type="text" name="name" id="name" value="<?php print $town_name[$a]; ?>" /></td><td><div id="div_btn"><input type="submit" value="Update" /></div></td><td width="50px"></td><td>Default</td></tr>
	</form>
	<?php } ?>
	<?php 
	for($i=0;$i<sizeof($town_id);$i++){
		if($town_id[$i]==$town_default) $default='<img src="images/action_check.gif" />'; else $default='<input type="checkbox" onclick="setTownDefault('.$town_id[$i].')" />';
		print '<tr><td bgcolor="#F1F1F1" colspan="3" style="padding-left:50px; padding-right:10px;">'.$town_name[$i].'</td><td bgcolor="#F1F1F1" style="padding-left:20px; padding-right:40px;" colspan="2"><a href="index.php?components='.$components.'&action=edit_custtown&id='.$town_id[$i].'"><img src="images/edit.gif" /></a><a style="cursor:pointer" onclick="deleteCustTown('.$town_id[$i].')"><img src="images/action_delete.gif" /></a></td><td bgcolor="#F1F1F1" align="center" ><div id="div_twdf_'.$town_id[$i].'">'.$default.'</div></td></tr>';
	} ?>
	</table>