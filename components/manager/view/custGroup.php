<?php
                include_once  'template/header.php';
?>

<!-- ------------------Item List----------------------- -->

<table align="center" cellspacing="0"><tr><td>

<?php
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color0='green'; else $color0='red';
print '<span style="color:'.$color0.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
}
?>
</td></tr></table>
<table width="900px" align="center" bgcolor="#E5E5E5">
<tr><td width="120px"></td><td style="font-family:Calibri; font-size:16pt; font-weight:bold; color:navy" align="center">Customer Grouping</td><td width="120px"><input type="button" value="Back" style="width:100px" onclick="window.location='index.php?components=<?php print $components; ?>&action=newcust'" /></td></tr>
</table>
<br />
<?php
	include_once  'components/manager/view/tpl/cust_group.php';

                include_once  'template/footer.php';
?>