<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

	<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($su_name);$x++){ print '"'.$su_name[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});
</script>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>
	
<table align="center"><tr><td valign="top">
<?php 
if((isset($_COOKIE['manager']))||(isset($_COOKIE['top_manager']))){
	if($_GET['action']=='supplier')include_once  'components/purchaseOrder/view/tpl/add_supplier.php';
	if($_GET['action']=='edit_supplier')include_once  'components/purchaseOrder/view/tpl/edit_supplier.php';
	if($_GET['action']=='search_supplier')include_once  'components/purchaseOrder/view/tpl/edit_supplier.php';
}
?>
</td><td width="50px"></td><td valign="top">
	<table ><tr><td style="font-size:12pt"><input type="text" name="code" id="tags1" /><input type="button" value="Search" onclick="window.location = 'index.php?components=purchase_order&action=search_supplier&id='+document.getElementById('tags1').value"  /></td></tr></table>
	<br />
	<table align="center" >
	<tr style="font-size:12pt; font-family:'Courier New', Courier, monospace; background-color:#CCCCCC"><th>Shop Name</th><th>Email</th><th>Country</th></tr>
	<?php
	if((isset($_COOKIE['manager']))||(isset($_COOKIE['top_manager']))){
		for($i=0;$i<sizeof($su_id);$i++){
			if($su_status[$i]==1) $color=''; else	$color='style="color:silver"';
			print '<tr style="font-size:11pt; font-family:'."'Courier New'".', Courier, monospace; background-color:#EFEFEF"><td '.$color.'>&nbsp;&nbsp;<a '.$color.' href="index.php?components=purchase_order&action=edit_supplier&id='.$su_id[$i].'"><strong>'.$su_name[$i].'</strong></a>&nbsp;&nbsp;</td><td '.$color.'>&nbsp;&nbsp;<a href="mailto:'.$su_email[$i].'">'.$su_email[$i].'</a>&nbsp;&nbsp;</td><td '.$color.'>&nbsp;&nbsp;'.$su_country[$i].'&nbsp;&nbsp;</td></tr>';
		}
	}
	?>
	</table>
</td></tr></table>


<?php
                include_once  'template/footer.php';
?>