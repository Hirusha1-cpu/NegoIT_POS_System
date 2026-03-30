<?php
	include_once  'template/header.php';
	$decimal = getDecimalPlaces(1);
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
		var availableTags1 = [<?php for ($x=0;$x<sizeof($itm_desc);$x++){ print '"'.$itm_desc[$x].'",'; } ?>	];
		$( "#item_po" ).autocomplete({
			source: availableTags1
		});
	});

	function appendPO(){
		var itm_id_arr = [<?php for ($x=0;$x<sizeof($itm_id);$x++){ print '"'.$itm_id[$x].'",'; } ?>	];
		var itm_desc_arr = [<?php for ($x=0;$x<sizeof($itm_desc);$x++){ print '"'.$itm_desc[$x].'",'; } ?>	];
		var po_no=document.getElementById('po_no').value;
		var item_po=document.getElementById('item_po').value;
		var qty_po=document.getElementById('qty_po').value;
		var a=itm_desc_arr.indexOf(item_po);
		var itm_id=itm_id_arr[a];
		window.location = 'index.php?components=purchase_order&action=append_po&po='+po_no+'&item='+itm_id+'&qty='+qty_po;
	}

	function downloadPO($po_id){
		var store_id=document.getElementById('store_id').value;
		window.location = 'index.php?components=purchase_order&action=download_po&id='+$po_id+'&store_id='+store_id;
	}

	function emailPO($po_id){
		document.getElementById('div_email_po').innerHTML=document.getElementById('loading').innerHTML;
		window.location = 'index.php?components=purchase_order&action=email_po&id='+$po_id+'&store_id=';
	}
</script>

<table align="center" style="font-size:12pt">
	<tr>
		<td>
			<?php
				if(isset($_REQUEST['message'])){
					if($_REQUEST['re']=='success') $color='green'; else $color='red';
				print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>';
				}
			?>
		</td>
	</tr>
</table>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<h2 align="center" style="color:#0158C2">List Of Purchase Order Items</h2>
<p align="center" style="font-size:10pt">Supplier : <?php if(isset($supplier)) print $supplier; ?></p>
<form action="index.php?components=purchase_order&action=update_po" method="post">
	<input type="hidden" id="po_no" name="po_no" value="<?php print $_GET['id']; ?>" />
	<table align="center" style="font-family:Calibri; font-size:12pt">
 		<tr bgcolor="#DDDDDD">
			<th width="50px"></th>
			<th bgcolor="#BBBBDD">Item</th>
			<th width="100px" bgcolor="#BBBBDD">Qty</th>
			<th bgcolor="#BBBBDD" width="100px">Action</th>
			<th width="100px" bgcolor="#FFFFFF"></th>
			<th rowspan="2">Date</th>
			<th rowspan="2">&nbsp;&nbsp;Added By&nbsp;&nbsp;</th>
			<th rowspan="2">&nbsp;&nbsp;Estimated<br>Cost&nbsp;&nbsp;</th>
		</tr>
		<?php if($po_status==0){ ?>
			<tr bgcolor="#DDDDDD">
				<th bgcolor="#EEEEEE" width="50px"></th>
				<th bgcolor="#EEEEEE">
					<input type="text" name="item_po" id="item_po" placeholder="Append New Item" />
				</th>
				<th width="100px" bgcolor="#EEEEEE">
					<input type="number" name="qty_po" id="qty_po" style="width:50px; text-align:right;" />
				</th>
				<th bgcolor="#EEEEEE" width="100px">
					<input type="button" value="Add" onclick="appendPO()" />
				</th>
				<th width="100px" bgcolor="#FFFFFF"></th>
			</tr>
		<?php }
		if($po_status==0) $disable=''; else $disable='disabled="disabled"';
		$cat='';
		if(isset($po_id) && is_array($po_id) && count($po_id) > 0) {
		for($i=0;$i<sizeof($po_id);$i++){
			if($cat!=$po_category[$i]){
				if($cat!='')
				print '<tr>
						<td bgcolor="#EEEEEE" colspan="4" height="20px"></td>
						<td colspan="5"></td>';
				print '<tr>
							<td style="color:white" bgcolor="#999999" colspan="4">&nbsp;&nbsp;<strong>'.$po_category[$i].'</strong></td>
							<td colspan="4"></td>';
							$cat=$po_category[$i];
			}
			if($po_status==0)
			$delete_button='<a href="#" onclick="removeItemPO('.$po_id[$i].')" title="Remove From PO"  style="font-size:14pt; color:red; font-weight:bold; text-decoration:none">X</a>';
			else $delete_button='';
			print '<tr bgcolor="#EEEEEE">
					<td align="center">'.($i+1).'</td>
					<td>&nbsp;&nbsp;<input '.$disable.' type="text" name="itm|'.$po_id[$i].'" value="'.$po_item[$i].'" />&nbsp;&nbsp;</td>
					<td align="center">&nbsp;&nbsp;<input '.$disable.' type="text" name="qty|'.$po_id[$i].'" value="'.$po_qty[$i].'" style="width:40px; text-align:right; padding-right:10px"/>&nbsp;&nbsp;</td>
					<td align="center">'.$delete_button.'</td>
					<td bgcolor="#FFFFFF"></td>
					<td>&nbsp;&nbsp;'.$po_date[$i].'&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;'.ucfirst($po_user[$i]).'&nbsp;&nbsp;</td>
					<td align="right" >&nbsp;&nbsp;'.number_format($po_cost[$i], $decimal).'&nbsp;&nbsp;</td>
				</tr>';
		} } else {
			print '<tr><td colspan="9" align="center" bgcolor="#EEEEEE">No items added to this PO yet</td></tr>';
		}
		?>
 		<tr>
			<td  bgcolor="#EEEEEE" colspan="4" align="center">
				<?php
					if($po_status==0) print '<input type="submit" value="Update" style="width:100px; height:40px" />';
				?>
			</td>
			<td colspan="4"></td>
		</tr>
		<tr style="height: 20px;"></tr>
 		<tr bgcolor="#EEEEEE">
			<td colspan="8" align="center">
				<table border="0" style="margin: 5px;">
					<tr>
						<?php
						if($po_status==0) print '<td><div id="div_lock_po"><input type="button" value="Lock PO" style="width:100px; height:50px; background-color:navy; color:white" onclick="lockPO()" /></div></td>';

						if($po_status==1){
							print '<td>
									<div id="div_unlock_po">
										<input type="button" value="Unlock PO" style="width:100px; height:50px; background-color:maroon; color:white" onclick="unlockPO()" />
									</div>
								</td>';
							print '<td>
									<div id="div_email_po">
										<input type="button" value="Send Email" style="width:100px; height:50px; background-color:orange; color:white" onclick="emailPO('.$_GET['id'].')" />
									</div>
								</td>';
						}
						if(($po_status==1)||($po_status==2)){
							print '<td><div id="div_download_po"><input type="button" value="Download PO" style="width:100px; height:50px; background-color:#229922; color:white" onclick="downloadPO('.$_GET['id'].')" /></div></td>';
						}
						?>
						<td>
							<select id="store_id">
								<option value="">-Store SELECT-</option>
								<?php for($i=0;$i<sizeof($store_id);$i++){
									print '<option value="'.$store_id[$i].'">'.$store_name[$i].'</option>';
								} ?>
							</select>
						</td>
					</tr>
				</table>
 				<p style="margin: 5px;">Select a Store to get Store Wholesale Price and Drawer details in PO Downloads</p>
 			</td>
		</tr>
	</table>
</form>

<?php
    include_once  'template/footer.php';
?>