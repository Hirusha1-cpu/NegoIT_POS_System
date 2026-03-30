<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">
	function validateAddGroup(){
		$user_id=document.getElementById('user_id').value;
		$group_id=document.getElementById('group_id').value;
		
		if(($user_id!='')&&($group_id!='')){
			document.getElementById('div_submit').innerHTML=document.getElementById('loading').innerHTML;
			return true;
		}else{
			alert('Please Select a User and a Group');
			return false;
		}
	}
	
	function removeGroup($i,$user_id,$group_id){
		var check= confirm("Do you want to Remove this Group Allocation ?");
		if(check==true){
			document.getElementById('div_rm_'+$i).innerHTML=document.getElementById('loading').innerHTML;
 			window.location = 'index.php?components=settings&action=remove_group_allocation&user_id='+$user_id+'&group_id='+$group_id;
		}
	}
</script>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px;" /></div>

<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?>
<br />
<table align="center">
<tr style="font-family:Calibri; font-weight:bold; color:white"><td align="center" bgcolor="#AAAAAA" width="400px">Users</td><td></td><td align="center" bgcolor="#AAAAAA">Group<br />Allocation</td></tr>
<tr><td style="vertical-align:top">
	
	<table width="100%" style="font-family:Calibri">
	<tr style="background-color:#DDDDDD"><th>User</th><th>Allocated Groups</th></tr>
	<?php 
	for($i=0;$i<sizeof($up2_id);$i++){
			$style='style="text-decoration:none; color:blue;"';
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			if($user_id==$up2_id[$i]){ $color='#DDDDDD'; $style='style="text-decoration:none; color:maroon; font-weight:bold"'; }
		print '<tr style="background-color:'.$color.'"><td class="shipmentTB4"><a '.$style.' href="index.php?components=settings&action=group_allocation&user_id='.$up2_id[$i].'">'.ucfirst($up2_name[$i]).'</a></td><td align="right" class="shipmentTB4">'.$up2_groups[$i].'</td></tr>';
	} ?>
	</table>

</td><td width="50px"></td><td style="vertical-align:top">
	<form method="post" action="index.php?components=settings&action=add_group_allocation" onsubmit="return validateAddGroup()" >
	<input type="hidden" id="user_id" name="user_id" value="<?php print $user_id; ?>" />
	<table bgcolor="#DDDDDD" style="font-family:Calibri" width="100%" >
	<tr><td colspan="6" height="10px"></td></tr>
	<tr><td width="20px"></td><td><strong>Customer Group</strong></td><td width="50px"></td><td rowspan="3" width="20px"><div id="div_submit"><input type="submit" value="Add" style="width:50px; height:40px" /></div></td><td rowspan="3" width="20px"></td></tr>
	<tr><td width="20px"></td><td><strong>
	<select id="group_id" name="group_id" >
		<option value="">-SELECT-</option>
		<?php 
		for($i=0;$i<sizeof($gp_id);$i++){
			print '<option value="'.$gp_id[$i].'">'.$gp_name[$i].'</option>';
		} ?>
	</select>
	</strong></td><td></td></tr>
	<tr><td colspan="3" height="10px"></td></tr>
	</table>
	</form>
	
	<hr>	
	
	<table  style="font-family:Calibri" width="100%">
	<tr style="background-color:#DDDDDD"><th width="150px">Allocated Group</th><th width="50px"></th></tr>
	<?php for($i=0;$i<sizeof($allo_groupid);$i++){
	print '<tr style="background-color:#EEEEEE;"><td class="shipmentTB3">'.$allo_groupname[$i].'</td><td align="center"><div id="div_rm_'.$i.'"><a href="#" style="color:red; text-decoration:none;" title="Remove Group" onclick="removeGroup('."'$i','$user_id','$allo_groupid[$i]'".')" ><strong>X</strong></a></div></td></tr>';
	} ?>
	</table>
</td></tr>
</table>

<?php
                include_once  'template/footer.php';
?>