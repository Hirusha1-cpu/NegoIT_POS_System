<?php
                include_once  'template/m_header.php';
                $store_report=$group_report=$salesman_report='ALL';
?>
<!-- ------------------------------------------------------------------------------------ -->
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($cu_name);$x++){ print '"'.$cu_name[$x].'",'; } ?>	];
		$( "#cust" ).autocomplete({
			source: availableTags1
		});
		var availableTags2 = [<?php for ($x=0;$x<sizeof($item_desc);$x++){ print '"'.$item_desc[$x].'",'; } ?>	];
		$( "#item0" ).autocomplete({
			source: availableTags2
		});
	});
	
	function submitQForm(){
		var itemid_arr = [<?php for ($x=0;$x<sizeof($item_id);$x++){ print '"'.$item_id[$x].'",'; } ?>	];
		var itemdes_arr = [<?php for ($x=0;$x<sizeof($item_desc);$x++){ print '"'.$item_desc[$x].'",'; } ?>	];
		var item=document.getElementById('item0').value;
		var submit=false;
		if(item!=''){
			var a=itemdes_arr.indexOf(item);
			if(a != -1){
				document.getElementById('item').value=itemid_arr[a];
				submit=true;
			}
		}else submit=true;
		if(submit) document.getElementById('search_form').submit();
	}
</script>

<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
<form id="search_form" action="index.php" method="get" >
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="quotation_list" />
	<input type="hidden" name="item"  id="item"  />
	<table border="0" align="center" height="100%" cellspacing="0" style="font-size:10pt; font-family:Calibri; border-radius: 15px;" bgcolor="#F0F0F0">
	<tr><td width="50px"></td><td align="center"><table><tr><td><strong>From</strong></td><td><input type="date" id="from_date" name="from_date" value="<?php print $from_date; ?>" /></td></tr>
						  <tr><td><strong>To</strong></td><td><input type="date" id="to_date" name="to_date" value="<?php print $to_date; ?>" /></td></tr></table>
	</td><td width="30px"></td><td align="center" rowspan="2">
		<table>
		<tr><td><strong>Customer</strong></td><td><input type="text" id="cust" name="cust" value="<?php print $cust; ?>" /></td></tr>
		<tr><td><strong>Item</strong></td><td><input type="text" id="item0" value="<?php print $item0; ?>" /></td></tr>
		<tr><td><strong>Shop</strong></td><td>
				<select id="st" name="st" onchange="submitQForm()">
				<option value="" >-ALL-</option>
				<?php for($i=0;$i<sizeof($store_id);$i++){
						if($store==$store_id[$i]){
							$select='selected="selected"';
							$store_report=$store_name[$i];
						}else $select='';
					print '<option value="'.$store_id[$i].'" '.$select.'>'.$store_name[$i].'</option>';
				}
				?>
				</select>
		</td></tr>
		</table>
	</td><td width="30px" rowspan="2"><a onclick="submitQForm()" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a></td></tr>
	<tr><td width="30px"></td><td align="center">
		<table>
		<tr><td><strong>Salesman</strong></td><td>
			<select id="sm" name="sm" onchange="submitQForm()">
			<option value="" >-ALL-</option>
			<?php for($i=0;$i<sizeof($up_id);$i++){
					if($salesman==$up_id[$i]){
						$select='selected="selected"';
						$salesman_report=$up_name[$i];
					}else $select='';
				print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
			}
			?>
		</select>
		</td></tr>
		<tr><td><strong>Status</strong></td><td>
			<select id="status" name="status" onchange="submitQForm()">
			<option value="all" >-ALL-</option>
			<option value="1" <?php if($status==1) print 'selected="selected"'; ?> >On Going</option>
			<option value="2" <?php if($status==2) print 'selected="selected"'; ?> >Pending</option>
			<option value="3" <?php if($status==3) print 'selected="selected"'; ?> >Approved</option>
			<option value="4" <?php if($status==4) print 'selected="selected"'; ?> >Rejected</option>
			<option value="5" <?php if($status==5) print 'selected="selected"'; ?> >Sent to Customer</option>
			<option value="6" <?php if($status==6) print 'selected="selected"'; ?> >Customer Accepted</option>
			<option value="7" <?php if($status==7) print 'selected="selected"'; ?> >Customer Rejected</option>
			<option value="8" <?php if($status==8) print 'selected="selected"'; ?> >Completed</option>
			</select>
		</td></tr>
		</table>
	</td><td width="30px"></td>
	</tr>
	</table>
</form>
<hr />
	<table align="center"><tr><td width="10px" bgcolor="orange"></td><td> - Quantity available in SUM of all stores </td></tr></table>
		<table align="center" height="100%" border="1" cellspacing="0" style="font-size:8pt; font-family:Calibri">
		<tr><td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold"></td></tr>
		<tr bgcolor="#E5E5E5"><th>Quotation No</th><th class="shipmentTB3">Created Date</th><th class="shipmentTB3">Store</th><th class="shipmentTB3">Customer</th><th class="shipmentTB3">Heading</th><th class="shipmentTB3">Salesman</th><th class="shipmentTB3">Amount</th><th width="100px">Status</th></tr>
	<?php
		for($i=0;$i<sizeof($qm_id);$i++){
			$qm_heading0=str_replace("Quotation for ","",$qm_heading[$i]);
			if(strlen($qm_heading0)>25) $qm_heading0=substr($qm_heading0,0,25).'...'; else $qm_heading0=$qm_heading0;
			if($qm_qty_avalability[$i]) $row_color='orange'; else $row_color='#F5F5F5';
			if($qm_status[$i]=='7') $qm_st='<a style="cursor:pointer; text-decoration:none" title="'.$qm_rejected_com[$i].'">'.$qm_status_name[$i].'</a>'; else $qm_st=$qm_status_name[$i];
			print '<tr bgcolor="'.$row_color.'"><td align="center"><a href="index.php?components='.$components.'&action=qo_finish&id='.$qm_id[$i].'">'.str_pad($qm_id[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center"><a style="cursor:pointer; color:blue" title="Time: '.substr($qm_created_date[$i],11,5).'" >'.substr($qm_created_date[$i],0,10).'</a></td><td class="shipmentTB3">'.$qm_store[$i].'</td><td class="shipmentTB3">'.$qm_cust[$i].'</td><td class="shipmentTB3"><a title="'.$qm_heading[$i].'" style="cursor:pointer; color:blue">'.$qm_heading0.'</a></td><td class="shipmentTB3">'.ucfirst($qm_created_by[$i]).'</td><td class="shipmentTB3" align="right">'.number_format($qm_amount[$i]).'</td><td align="center" style="color:'.$qm_status_color[$i].'" bgcolor="grey">'.$qm_st.'</td></tr>';
		}
	?>	
		</table>
	
  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
