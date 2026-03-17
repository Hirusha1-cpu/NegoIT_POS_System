<?php
    include_once  'template/header.php';
?>
<script>
    function editShipment(){
        var sub = document.getElementById('sub').value;
        var shipment_no = document.getElementById('shipment_no').value;
		window.location = 'index.php?components=inventory&action='+sub+'&shipment_no='+shipment_no;
	}

</script>
<form action="index.php?components=inventory&action=update_shipment_header_tmp" method="post" onsubmit="return validateShipment()"  >
<input type="hidden" name="shipment_no" id="shipment_no" value="<?php print $shipment_no; ?>" />
<input type="hidden" name="sub" id="sub" value="<?php echo $_GET['sub']; ?>"/>
<table align="center" bgcolor="#E5E5E5" style="font-size:11pt; font-family:Calibri">
<tr><td colspan="4"><?php 
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color='green'; else $color='red';
print '<p style="color:'.$color.'; font-weight:bold;font-size:12pt;margin:10px;text-align:center">'.$_REQUEST['message'].'</p>'; 
}
?>
<br /></td></tr>
<tr><td colspan="4" style="padding: 0px 52px;"><input type="button" value="Back to Shipment" onclick="editShipment()" style="float:right; margin-bottom: 10px;"/></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Temp Shipment Number</td><td style="font-size:12pt" align="center"><strong><?php print str_pad($shipment_no, 7, "0", STR_PAD_LEFT); ?></strong></td><td width="50px"><br /><br /></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Shipment Date</td><td><input type="date" name="ship_date" id="ship_date" value="<?php print $ship_date; ?>" style="width:140px" /></td><td width="50px"><br /><br /></td></tr>
<tr><td colspan="4" height="10px"></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Supplier</td><td style="font-size:12pt">
	<select name="suplier" id="supplier" >
	<?php 
		print '<option value="">-SELECT-</option>';
		for($j=0;$j<sizeof($su_id);$j++){
            if($supplier_id == $su_id[$j]) print '<option value="'.$su_id[$j].'" selected>'.$su_name[$j].'</option>';
            else print '<option value="'.$su_id[$j].'" >'.$su_name[$j].'</option>';
		}
	?>
</select>
</td><td width="50px"></td></tr>
<tr><td colspan="4" height="10px"></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Invoice Number</td><td><input type="text" name="ship_inv_no" id="ship_inv_no" value="<?php print $invoice_no; ?>" style="width:140px" /></td><td width="50px"><br /><br /></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Invoice Date</td><td><input type="date" name="ship_inv_date" id="ship_inv_date" value="<?php print $invoice_date; ?>" style="width:140px" /></td><td width="50px"></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Payment Due Date</td><td><input type="date" name="ship_inv_dudate" id="ship_inv_dudate" value="<?php print $invoice_due; ?>" style="width:140px" /></td><td width="50px"></td></tr>
<tr><td colspan="4" align="center"><br /><input type="submit" value="Update Shipment Header" id="submit" style="width:180px; height:50px" /><br /><br /></td></tr>
</table>
</form>
<?php
    include_once  'template/footer.php';
?>