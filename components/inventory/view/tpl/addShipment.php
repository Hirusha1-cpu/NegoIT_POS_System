<form action="index.php?components=inventory&action=add_shipment" method="post" onsubmit="return validateShipment()"  >
<input type="hidden" name="sub" value="<?php print $_GET['sub']; ?>" />
<table align="center" bgcolor="#E5E5E5" style="font-size:11pt">
<tr><td colspan="4"><?php 
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color='green'; else $color='red';
print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'; 
}
?>
<br /></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Shipment Number</td><td style="font-size:12pt" align="center"><strong><?php print str_pad($shipment_no, 7, "0", STR_PAD_LEFT); ?></strong></td><td width="50px"><br /><br /></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Shipment Date</td><td><input type="date" name="ship_date" id="ship_date" value="<?php print dateNow(); ?>" style="width:140px" /></td><td width="50px"><br /><br /></td></tr>
<tr><td colspan="4" height="10px"></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Supplier</td><td style="font-size:12pt">
	<select name="suplier" id="supplier" >
	<?php 
		print '<option value="">-SELECT-</option>';
		for($j=0;$j<sizeof($su_id);$j++){
		print '<option value="'.$su_id[$j].'" >'.$su_name[$j].'</option>';
		}
	?>
</select>
</td><td width="50px"><a title="Add New Suplier" href="index.php?components=purchase_order&action=supplier" style="font-size:8pt; text-decoration:none">Add +</a></td></tr>
<tr><td colspan="4" height="10px"></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Invoice Number</td><td><input type="text" name="ship_inv_no" id="ship_inv_no" style="width:140px" /></td><td width="50px"><br /><br /></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Invoice Date</td><td><input type="date" name="ship_inv_date" id="ship_inv_date" style="width:140px" /></td><td width="50px"></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Payment Due Date</td><td><input type="date" name="ship_inv_dudate" id="ship_inv_dudate" style="width:140px" /></td><td width="50px"></td></tr>
<tr><td colspan="4" align="center"><br /><input type="submit" value="Add Shipment" style="width:130px; height:50px" /><br /><br /></td></tr>
</table>
</form>