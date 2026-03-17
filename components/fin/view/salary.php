<?php
                include_once  'template/header.php';
?>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>

<table align="center">
<tr><td valign="top">
<?php
	if($_GET['action']=='salary') print '<table align="center" border="0" bgcolor="#EEEEEE" width="400px" style="font-family:Calibri"><tr><td style="height:200px"></td><td align="center">Please Select an Employee</td><td></td></tr></table>';
	if($_GET['action']=='one_salary')include_once  'components/fin/view/tpl/update_salary.php';
?>
</td><td width="10px"></td><td valign="top">
	<table align="center" border="0" width="250px" style="font-family:Calibri">
	<tr bgcolor="#7e9099"><th align="left" style="padding-left:20px; color:white">Employee</th></tr>
	<?php 
	for($i=0;$i<sizeof($emp_id);$i++){
		print '<tr bgcolor="#f9f9f9"><td style="padding-left:40px;"><a href="index.php?components=fin&action=one_salary&id='.$emp_id[$i].'" style="text-decoration:none;">'.ucfirst($emp_name[$i]).'</a></td></tr>';
	} ?>
	</table>
</td></tr>
</table>

<?php
                include_once  'template/footer.php';
?>