<?php
                include_once  'template/header.php';
?>

<form action="index.php?components=settings&action=add_category" method="post" onsubmit="return validateAddCategory()" >
<table align="center" bgcolor="#E5E5E5" style="font-family:Calibri">
<tr><td colspan="4"><?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?><br /></td></tr>
<tr><td width="50px"></td><td>Category</td><td><input type="text" name="category" id="category" /></td><td width="50px"></td><td>Sub System
	<select id="sub_sys" name="sub_sys">
	<option value="">-SELECT-</option>
	<option value="all">ALL</option>
	<?php for($i=0;$i<sizeof($sub_id);$i++){
		print '<option value="'.$sub_id[$i].'">'.$sub_name[$i].'</option>';
	}	
	?>
	</select>
</td><td width="50px"></td></tr>
<tr><td colspan="5" align="center"><br /><input type="submit" value="Add Category" style="width:130px; height:50px" /><br /><br /></td></tr>
</table>
</form>

<table align="center" bgcolor="#EFEFEF" border="0" style="font-family:Calibri">
<tr style="background-color:#467898; color:white;"><th>Category Name</th><th>Sub System</th><th>Action</th></tr>
<?php
	for($i=0;$i<sizeof($category_id);$i++){
		if(($i%2)==0) $color='#F1F1F1'; else $color='#DDDDDD';
		print '<tr style="background-color:'.$color.'"><td style="padding-left:20px; padding-right:20px;">'.$category_name[$i].'</td><td style="padding-left:20px; padding-right:20px;">'.$category_sub[$i].'</td><td><input type="button" onclick="window.location = '."'".'index.php?components=settings&action=delete_category&id='.$category_id[$i]."'".'" value="Delete" /></td></tr>';
	}

?>

</table>

<?php
                include_once  'template/footer.php';
?>