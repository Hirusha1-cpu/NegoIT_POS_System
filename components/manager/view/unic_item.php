<?php
                include_once  'template/header.php';
?>

<!-- ------------------Item List----------------------- -->
	<table align="center" height="100%" cellspacing="0" style="font-family:Calibri; border-radius: 15px; padding-left:20px; padding-right:20px" bgcolor="#EEEEEF">
	<tr><td colspan="2" align="right"  height="40px">
		<form action="index.php?components=manager&action=unic_items&store=<?php print $store; ?>" method="post" >
			<table><tr><td>
			<strong>Unique Item </strong>: </td><td>
			<select id="item0" onchange="window.location = 'index.php?components=manager&action=unic_items&item='+document.getElementById('item0').value+'&store='+document.getElementById('store0').value+'&status='+document.getElementById('status0').value" >
			<option value="" >--SELECT--</option>
			<?php
			 for($i=0;$i<sizeof($unic_item_id);$i++){
			 	if($unic_item_id[$i]==$item) $select='selected="selected"'; else $select='';
			 	print '<option value="'.$unic_item_id[$i].'" '.$select.'>'.ucfirst($unic_item_des[$i]).'</option>';
			 }
			?>
			</select>
			</td></tr></table>
		</form>
	</td><td width="100px" align="right"><strong>Store : </strong></td><td>
		<select id="store0" onchange="window.location = 'index.php?components=manager&action=unic_items&item='+document.getElementById('item0').value+'&store='+document.getElementById('store0').value+'&status='+document.getElementById('status0').value">
		<option value="all" >--ALL--</option>
		<?php
		 for($i=0;$i<sizeof($store_id);$i++){
		 	if($store_id[$i]==$store) $select='selected="selected"'; else $select='';
		 	print '<option value="'.$store_id[$i].'" '.$select.'>'.ucfirst($store_name[$i]).'</option>';
		 }
		?>
		</select>
	</td><td width="100px" align="right">
	<?php if(sizeof($status_list)<=1){ ?>
	<strong>Status : </strong></td><td>
		<select id="status0" onchange="window.location = 'index.php?components=manager&action=unic_items&item='+document.getElementById('item0').value+'&store='+document.getElementById('store0').value+'&status='+document.getElementById('status0').value">
		<option value="0" <?php if($status==0) print 'selected="selected"'; ?> >Available</option>
		<option value="1" <?php if($status==1) print 'selected="selected"'; ?> >Sold</option>
		<option value="2" <?php if($status==2) print 'selected="selected"'; ?> >Deleted</option>
		<option value="3" <?php if($status==3) print 'selected="selected"'; ?> >Pending Transfer</option>
		<option value="4" <?php if($status==4) print 'selected="selected"'; ?> >Returned</option>
		<option value="5" <?php if($status==5) print 'selected="selected"'; ?> >Replacement</option>
		<option value="6" <?php if($status==6) print 'selected="selected"'; ?> >Disposal</option>
		</select>
	<?php }else print '<input type="hidden" id="status0" value="0" /></td><td>'; ?>
	</td><td width="50px"></td><td colspan="6"><form method="get" action="index.php" onsubmit="return validateSearchUnic()" ><input type="hidden" name="components" value="manager" /><input type="hidden" name="action" value="unic_items" /><input type="text" name="search_unic" id="search_unic" placeholder="Serach by Unique ID" value="<?php print $sn; ?>" /><input type="submit" value="Search" /></form></td></tr>
	</table>
<br /><br />
	<table align="center"><tr><td>
	<div style="background-color:#EEEEEF; border-radius: 15px; padding-left:20px; padding-right:20px; padding-top:10px; padding-bottom:10px">

	<table align="center" width="250px"style="font-family:Calibri" >
	<tr bgcolor="#CCCCCC" style="color:navy"><td style="padding-left:10px; padding-right:10px;"><strong>Total Qty</strong></td><td align="right" style="padding-left:10px; padding-right:10px;"><strong><?php print sizeof($itu_sn); ?></strong></td></tr>
	</table>
	<br />
	<table align="center" height="100%" style="font-family:Calibri" >
	<tr bgcolor="#CCCCCC">
<?php
	if(sizeof($status_list)>0)	print '<th width="200px">Description</th><th width="100px">Store</th>';
	print '<th>Unique ID</th><th width="100px" >Shipment</th><th width="100px" >Transfer</th>';
	if(sizeof($status_list)>0)	print '<th width="100px">Status</th>';
	
	$st=$status;
 print '<th width="100px">Invice No</th><th width="100px">Return<br />Invice No</th><th width="250px" >Customer</th>'; 
	print '</tr>';
	for($i=0;$i<sizeof($itu_sn);$i++){
		print '<tr bgcolor="#FAFAFA">';
		if(sizeof($status_list)>0)	print '<td style="padding-left:10px; padding-right:10px;">'.$item_des[$i].'</td><td style="padding-left:10px; padding-right:10px;">'.$store_arr[$i].'</td>';
		print '<td style="padding-left:10px; padding-right:10px;">'.$itu_sn[$i].'</td><td align="center"><a href="index.php?components=inventory&action=one_shipment&shipment_no='.$itu_shipment[$i].'">'.str_pad($itu_shipment[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center">';
		if($itu_trans_no[$i]!=0) print '<a href="index.php?components=trans&action=print_gtn&approve_permission=0&id='.$itu_trans_no[$i].'">'.str_pad($itu_trans_no[$i], 7, "0", STR_PAD_LEFT).'</a>';
		print '</td>';
		if(sizeof($status_list)>0)	print '<td align="center">'.$status_list[$i].'</td>';
		print '<td align="center">';
		if($itu_invoice_no[$i]!=0) print '<a href="index.php?components=billing&action=finish_bill&id='.$itu_invoice_no[$i].'">'.str_pad($itu_invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>';
		print '</td><td align="center">';
		if($return_invoice_no[$i]!=0) print '<a href="index.php?components=billing&action=finish_return&id='.$return_invoice_no[$i].'">'.str_pad($return_invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>';
		print '</td><td style="padding-left:10px; padding-right:10px;"><a href="index.php?components=manager&action=editcust&id='.$cust_id[$i].'" >'.$cust[$i].'</a></td></tr>';
	}
?>
	</table>
<!-- ---------------------------------------------Track Records----------------------------------------------------------------------------------------------------------->
	<table align="center" width="815px"><tr style="background-color:#467898;color :white; font-family:Calibri"><td align="center">Track Records</td></tr></table>
	<table align="center"style="font-family:Calibri" width="815px"><tr><td align="center">
		<table>
		<tr bgcolor="#CCCCCC"><th width="200px">Transfer No</th></tr>
		<?php for($i=0;$i<sizeof($data_tr_no);$i++){
			$arraysearch=array_search($data_tr_no[$i],$itu_trans_no);
			if($arraysearch===false){
				print '<tr bgcolor="#FAFAFA"><td><a href="index.php?components=trans&action=print_gtn&approve_permission=0&id='.$data_tr_no[$i].'">'.str_pad($data_tr_no[$i], 7, "0", STR_PAD_LEFT).'</a></td></tr>';
			}
		} ?>
		</table>
	</td><td></td><td align="center">
		<table>
		<tr bgcolor="#CCCCCC"><th width="200px">Invoice No</th></tr>
		<?php for($i=0;$i<sizeof($data_bill_no);$i++){
			$arraysearch=array_search($data_bill_no[$i],$itu_invoice_no);
			if($arraysearch===false){
				print '<tr bgcolor="#FAFAFA"><td><a href="index.php?components=billing&action=finish_bill&id='.$data_bill_no[$i].'">'.str_pad($data_bill_no[$i], 7, "0", STR_PAD_LEFT).'</a></td></tr>';
			}
		} ?>
		</table>
	</td><td></td><td align="center">
		<table>
		<tr bgcolor="#CCCCCC"><th width="200px">Return Invoice No</th></tr>
		<?php for($i=0;$i<sizeof($data_rtn_no);$i++){
			$arraysearch=array_search($data_rtn_no[$i],$return_invoice_no);
			if($arraysearch===false){
				print '<tr bgcolor="#FAFAFA"><td><a href="index.php?components=billing&action=finish_return&id='.$data_rtn_no[$i].'">'.str_pad($data_rtn_no[$i], 7, "0", STR_PAD_LEFT).'</a></td></tr>';
			}
		} ?>
		</table>
	</td></tr></table>
<!-- ---------------------------------------------Warranty Records----------------------------------------------------------------------------------------------------------->
	<table align="center" width="815px"><tr style="background-color:#467898;color :white; font-family:Calibri"><td align="center" >Warranty Records</td></tr></table>
	<table align="center"style="font-family:Calibri" width="815px"><tr><td align="center">
		<table>
		<tr bgcolor="#CCCCCC"><th width="200px">Warranty No</th><th class="shipmentTB4">Store</th><th class="shipmentTB4">Status</th></tr>
		<?php for($i=0;$i<sizeof($warranty_no);$i++){
				print '<tr bgcolor="#FAFAFA"><td align="center"><a href="index.php?components=billing&action=warranty_show&id='.$warranty_no[$i].'">'.str_pad($warranty_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB3">'.$warranty_store[$i].'</td><td class="shipmentTB3" style="color:'.$warranty_st_color[$i].'" bgcolor="#CCCCCC">'.$warranty_st_name[$i].'</td></tr>';
			}
		?>
		</table>
	</td></tr></table>
	
	</div>
	</td></tr></table>

<?php
                include_once  'template/footer.php';
?>