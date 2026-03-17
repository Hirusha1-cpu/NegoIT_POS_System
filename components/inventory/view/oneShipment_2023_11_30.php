<?php
                include_once  'template/header.php';
?>

<!-- ------------------Item List----------------------- -->
<br /><br />
<table align="center" cellspacing="0"><tr><td>

<?php
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?>
</td></tr></table>
<table align="center"><tr><td valign="top">
<?php
                include_once  'components/inventory/view/tpl/oneShipment_header.php';
?>
</td><td width="100px"></td><td valign="top">
<?php
                include_once  'components/inventory/view/tpl/oneShipment_footer.php';
?>
</td></tr></table>
<?php
                include_once  'template/footer.php';
?>