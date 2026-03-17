<?php
                include_once  'template/header.php';
?>

	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
	<script type="text/javascript">
		function removeItemRT($rtn_id){
			var check= confirm("Do want to Remove this item from Return Invoice ?");
			if(check==true){
				document.getElementById('remove_div').innerHTML=document.getElementById('loading').innerHTML;
				window.location = 'index.php?components=order_process&action=remove_one_return_item&odr_id=<?php print $_GET["odr_id"]; ?>&rtn_id='+$rtn_id;
			}		
		}
	</script>
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
	
	<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<table align="center" bgcolor="#E5E5E5"><tr><td colspan="5"><span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span></td></tr></table><br />'; 
	}
	?>
	
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

		<table align="center" style="font-size:10pt">
		<tr style="background-color:#467898;color :white;"><th colspan="3">Delete Item From Return Invoice</th><td width="50px"><input type="button" value="Back" style="width:100%" onclick="window.location = 'index.php?components=order_process&action=list_one_custodr&id=<?php print $_GET['odr_id']; ?>'" /></td></tr>
		<tr style="background-color:#C0C0C0"><th style="padding-left:20px; padding-right:20px">Item</th><th style="padding-left:10px; padding-right:10px" width="80px">Return Qty</th><th style="padding-left:20px; padding-right:20px" colspan="2">Action</th></tr>
		<?php
			print '<tr style="background-color:#F0F0F0"><td style="padding-left:20px; padding-right:20px">'.$itm_desc.'</td><td style="padding-right:20px" align="right">'.$rtn_qty.'</td><td align="center" colspan="2"><div id="remove_div" ><input type="button" value="Remove" onmouseup="removeItemRT('.$rtn_id.')" style="background-color:maroon; color:white" /></div></td></tr>';
		?>
		<tr><td colspan="4" style="font-weight:lighter; font-style:italic; color:gray">Please note that when you delete the item from return invoice, the credit amount will be shown in the Return Invoice.<br />Kindly Re print the Return Invoice</td></tr>
		</table>

<?php
                include_once  'template/footer.php';
?>