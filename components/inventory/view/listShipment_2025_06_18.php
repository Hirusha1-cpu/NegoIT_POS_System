<?php
    include_once  'template/header.php';
	$decimal = getDecimalPlaces(1);
?>

<script type="text/javascript">
	function filterStore(){
		var month=document.getElementById('month').value;
		var store=document.getElementById('store').value;
		window.location = 'index.php?components=<?php print $components; ?>&action=shipmentlist&month='+month+'&store='+store;
	}
</script>

<table align="center" cellspacing="0">
	<tr>
		<td>
			<?php
				if(isset($_REQUEST['message'])){
					if($_REQUEST['re']=='success') $color0='green'; else $color0='red';
				print '<span style="color:'.$color0.'; font-weight:bold;">'.$_REQUEST['message'].'</span>';
				}
			?>
		</td>
	</tr>
</table>

<div
	style="display: flex;margin: auto;overflow: hidden;text-align: center;justify-content: center;align-items: center;padding:20px; font-family:Calibri">
	<span>Month Filter : </span><input type="month" name="month" id="month" value="<?php print $_GET['month']; ?>"
		onchange="window.location = 'index.php?components=<?php print $components; ?>&action=shipmentlist&month='+this.value"
		style="margin-left: 10px" />
</div>

<table align="center">
	<tr>
		<td valign="top">
			<?php include_once  'components/inventory/view/tpl/listShipment_table1.php'; ?>
		</td>
		<td width="80px"></td>
		<td valign="top">
			<?php include_once  'components/inventory/view/tpl/listShipment_table2.php'; ?>
		</td>
	</tr>
</table>

<?php
    include_once  'template/footer.php';
?>