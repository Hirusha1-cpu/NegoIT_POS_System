<?php
    include_once  'template/header.php';
    $menu_components=$_GET['components'];
    $decimal=0;
    if($systemid==13) $decimal=2;
    if($systemid==14) $decimal=2;
?>
<!-- ------------------Item List----------------------- -->
<div style="background-color:#EEEEEE; border-radius:10px">
	<table align="center" height="100%" cellspacing="0" style="font-family:Calibri" bgcolor="#F0F0F0" border="0">
		<tr>
			<td align="right"><strong>Date </strong>:</td>
			<td colspan="3">
				<form action="index.php" method="get" >
					<input type="hidden" name="components" value="<?php print  $menu_components; ?>" />
					<input type="hidden" name="action" value="daily_sale_detail" />
					<input type="hidden" name="store" value="<?php print $_GET['store']; ?>" />
					<input type="hidden" name="salesman" value="<?php print $_GET['salesman']; ?>" />
					<input type="hidden" name="processby" value="<?php print $_GET['processby']; ?>" />
					<input type="hidden" name="group" value="<?php print $_GET['group']; ?>" />
					<input type="hidden" name="lock" value="<?php print $_GET['lock']; ?>" />
					<input type="hidden" name="type" value="<?php print $_GET['type']; ?>" />
					<input type="hidden" name="cashback" value="<?php print $_GET['cashback']; ?>" />
					<table><tr><td align="right"><input type="date" name="date" style="width:130px" value="<?php print $date; ?>" />
					<input type="submit" value="GET" />
					</td></tr></table>
				</form>
			</td>
			<td width="100px" align="right"><strong>Process By : </strong></td>
			<td>
				<select id="processby0" onchange="window.location = 'index.php?components=<?php print  $menu_components; ?>&action=daily_sale_detail&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date=<?php print $date; ?>'">
					<option value="all" >--ALL--</option>
					<?php
					$processbyname='ALL';
					 for($i=0;$i<sizeof($up_id);$i++){
					 	if($up_id[$i]==$_GET['processby']){ $select='selected="selected"'; $processbyname=ucfirst($up_name[$i]); }else{ $select=''; }
					 	print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
					 }
					?>
				</select>
			</td>
			<td align="right"><strong>Cash Back :</strong></td>
			<td>
				<select id="cashback" onchange="window.location = 'index.php?components=<?php print  $menu_components; ?>&action=daily_sale_detail&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date=<?php print $date; ?>'">
					<option value="yes" <?php if($_GET['cashback']=='yes') print 'selected="selected"'; ?> >-YES-</option>
					<option value="no" <?php if($_GET['cashback']=='no') print 'selected="selected"'; ?> >-NO-</option>
				</select>
			</td>
			<td width="200px" align="right" colspan="2">
				<input style="height:30px" type="button" value="Summary Report" onclick="window.location = 'index.php?components=<?php print  $menu_components; ?>&action=daily_sale&group=all&lock=1&type=&store='+document.getElementById('store0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date1=<?php print $date; ?>'" />
			</td>
		</tr>
		<tr>
			<td width="100px" align="right"><strong>Store : </strong></td>
			<td>
				<select id="store0" onchange="window.location = 'index.php?components=<?php print  $menu_components; ?>&action=daily_sale_detail&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date=<?php print $date; ?>'">
				<option value="all" >--ALL--</option>
				<?php
				$stname='ALL Stores';
				 for($i=0;$i<sizeof($store_id);$i++){
				 	if($store_id[$i]==$_GET['store']){ $select='selected="selected"'; $stname=ucfirst($store_name[$i]); }else{ $select=''; }
				 	print '<option value="'.$store_id[$i].'" '.$select.'>'.ucfirst($store_name[$i]).'</option>';
				 }
				?>
				</select>
			</td>
			<td width="80px" align="right"><strong>Group : </strong></td>
			<td>
				<select id="group0" onchange="window.location = 'index.php?components=<?php print  $menu_components; ?>&action=daily_sale_detail&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date=<?php print $date; ?>'">
					<option value="all" >--ALL--</option>
					<?php
					$gpname='ALL Groups';
					 for($i=0;$i<sizeof($gp_id);$i++){
					 	if($gp_id[$i]==$_GET['group']){ $select='selected="selected"'; $gpname=ucfirst($gp_name[$i]); }else{ $select=''; }
					 	print '<option value="'.$gp_id[$i].'" '.$select.'>'.ucfirst($gp_name[$i]).'</option>';
					 }
					?>
				</select>
			</td>
			<td width="100px" align="right"><strong>Salesman : </strong></td>
			<td>
				<select id="salesman0" onchange="window.location = 'index.php?components=<?php print  $menu_components; ?>&action=daily_sale_detail&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date=<?php print $date; ?>'">
					<option value="all" >--ALL--</option>
					<?php
					$salesmanname='ALL';
					 for($i=0;$i<sizeof($up_id);$i++){
					 	if($up_id[$i]==$_GET['salesman']){ $select='selected="selected"'; $salesmanname=ucfirst($up_name[$i]); }else{ $select=''; }
					 	print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
					 }
					?>
				</select>
			</td>
			<td width="100px" align="right"><strong>Bill Status : </strong></td>
			<td>
				<select id="lock" onchange="window.location = 'index.php?components=<?php print  $menu_components; ?>&action=daily_sale_detail&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date=<?php print $date; ?>'">
					<option value="1" <?php if($lock_req==1){ print 'selected="selected"'; $lockname='Lock'; } ?> >Lock</option>
					<option value="0" <?php if($lock_req==0){ print 'selected="selected"'; $lockname='Unlock'; } ?> >Unlock</option>
					<option value="all" <?php if($lock_req=='all'){ print 'selected="selected"'; $lockname='ALL'; } ?> >--ALL--</option>
				</select>
			</td>
			<td width="100px" align="right"><strong>Type : </strong></td>
			<td align="right">
				<select id="type" onchange="window.location = 'index.php?components=<?php print  $menu_components; ?>&action=daily_sale_detail&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date=<?php print $date; ?>'">
					<option value="" <?php if($type_req==''){ print 'selected="selected"'; $typename='ALL'; } ?> >--ALL--</option>
					<option value="1" <?php if($type_req==1){ print 'selected="selected"'; $typename='Product'; } ?> >Product</option>
					<option value="2" <?php if($type_req==2){ print 'selected="selected"'; $typename='Service'; } ?> >Service</option>
					<option value="3" <?php if($type_req==3){ print 'selected="selected"'; $typename='Return'; } ?> >Return</option>
					<option value="4" <?php if($type_req==4){ print 'selected="selected"'; $typename='Repair'; } ?> >Repair</option>
					<option value="5" <?php if($type_req==5){ print 'selected="selected"'; $typename='Warranty'; } ?> >Warranty</option>
				</select>
			</td>
		</tr>
	</table>
</div>

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Daily Sales Statement</h2>
	<table>
		<tr>
			<td>
				<table style="font-size:12pt" border="1" cellspacing="0">
					<tr><td style="background-color:#C0C0C0; padding-left:10px">Date</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $date; ?></td></tr>
					<tr><td width="100px" style="background-color:#C0C0C0; padding-left:10px">Store</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $stname; ?></td></tr>
					<tr><td style="background-color:#C0C0C0; padding-left:10px">Salesman</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $salesmanname; ?></td></tr>
				</table>
			</td>
			<td width="100px"></td>
			<td>
				<table style="font-size:12pt" border="1" cellspacing="0">
					<tr><td width="100px" style="background-color:#C0C0C0; padding-left:10px">Group</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $gpname; ?></td></tr>
					<tr><td style="background-color:#C0C0C0; padding-left:10px">Bill Status</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $lockname; ?></td></tr>
					<tr><td style="background-color:#C0C0C0; padding-left:10px">Bill Type</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $typename; ?></td></tr>
				</table>
			</td>
		</tr>
	</table>
	<hr/>
</div>

<br/><br/>
<div id="print">
	<?php if($type_req!=3 && $type_req!=5){ ?>
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri" >
			<tr>
				<td colspan="7" style="border:0; background-color:black; color:white; font-weight:bold">Invoiced Collection</td>
			</tr>
			<?php
				$inv=0;
				$store0='';
				$total_cash=$total_card=$total_bank=$total_chque=$total_credit=0;
				for($i=0;$i<sizeof($invoice_no);$i++){
					if($bi_discount[$i]>0){ $color='style="color:red"'; $title='title="Discounted Invoice"'; }else{ $color=''; $title=''; }
					if($store0!=$billed_store[$i]){
						print '<tr><td colspan="5" style="padding-left:20px; font-weight:bold; background-color:#0066AA; color:white">'.$billed_store[$i].'</td></tr>';
					}
						$bill_credit=$invoice_Total[$i]-$payment_card[$invoice_no[$i]]-$payment_cash[$invoice_no[$i]]-$payment_bank[$invoice_no[$i]]-$payment_chque[$invoice_no[$i]];
						print '<tr><td colspan="5" bgcolor="#888888" height="3px"></td></tr>';
						print '<tr '.$color.'><td colspan="5" width="800px" style="padding-top: 12px;padding-left: 12px;">Invoice No : <a '.$title.' href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'">'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a><table align="right" width="500px"><tr><td>Customer : </td><td>'.ucfirst($billed_cust[$i]).'</td></tr><tr><td>Salesman : </td><td>'.ucfirst($billed_by[$i]).'</td></tr></table><br>Time : '.$billed_time[$i].'</td></tr>';
						print '<tr bgcolor="#F3F3FF"><td colspan="5"><table width="100%" cellspacing="0"><tr><td><table border="1" cellspacing="0" width="600px" style="margin:auto;">';
						print '<tr><th>Description</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr>';
						for($j=0;$j<sizeof($bi_item[$invoice_no[$i]]);$j++){
							print '<tr><td class="billingformat1">'.$bi_item[$invoice_no[$i]][$j].'</td><td class="billingformat1" align="right">'.$bi_qty[$invoice_no[$i]][$j].'</td><td class="billingformat1" align="right">'.number_format($bi_uprice[$invoice_no[$i]][$j],$decimal).'</td><td class="billingformat1" align="right">'.number_format($bi_qty[$invoice_no[$i]][$j]*$bi_uprice[$invoice_no[$i]][$j]).'</td></tr>';
						}
						print '</table></td><td><table style="vertical-align:top" align="right" width="150px"  border="1" cellspacing="0">';
						print '<tr><td class="shipmentTB3"><strong>Total</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($invoice_Total[$i],$decimal).'</strong></td></tr>';
						print '<tr><td class="shipmentTB3">Cash</td><td class="shipmentTB3" align="right">'.number_format($payment_cash[$invoice_no[$i]]).'</td></tr>';
						// print '<tr><td class="shipmentTB3">Card</td><td class="shipmentTB3" align="right">'.number_format($payment_card[$invoice_no[$i]]).'</td></tr>';
						print '<tr><td class="shipmentTB3">Card</td><td class="shipmentTB3" align="right"><a href="#" style="text-decoration:none" title="'.$chq_details[$invoice_no[$i]].'">'.number_format($payment_card[$invoice_no[$i]],$decimal).'</a></td></tr>';
						print '<tr><td class="shipmentTB3">Bank</td><td class="shipmentTB3" align="right"><a href="#" style="text-decoration:none" title="'.$chq_details[$invoice_no[$i]].'">'.number_format($payment_bank[$invoice_no[$i]],$decimal).'</a></td></tr>';
						print '<tr><td class="shipmentTB3">Cheque</td><td class="shipmentTB3" align="right"><a href="#" style="text-decoration:none" title="'.$chq_details[$invoice_no[$i]].'">'.number_format($payment_chque[$invoice_no[$i]],$decimal).'</a></td></tr>';
						print '<tr><td class="shipmentTB3">Credit</td><td class="shipmentTB3" align="right">'.number_format($bill_credit,$decimal).'</td></tr>';
						print '</table></td></tr></table><table width="100%" border="1" cellspacing="0"><tr><td>'.str_replace('&#13;','</td><td>',$chq_details[$invoice_no[$i]]).'</td></tr></table></td></tr>';
						$total_cash+=$payment_cash[$invoice_no[$i]];
						$total_card+=$payment_card[$invoice_no[$i]];
						$total_bank+=$payment_bank[$invoice_no[$i]];
						$total_chque+=$payment_chque[$invoice_no[$i]];
						$total_credit+=$bill_credit;
					if(sizeof($invoice_no)==($i+1)){
						print '<tr style="font-weight:bold; background-color:gray; color:white">
								<td align="right" style="padding-right:10px;">Total Cash: '.number_format($total_cash,$decimal).'</td>
								<td align="right" style="padding-right:10px;">Total Card: '.number_format($total_card,$decimal).'</td>
								<td align="right" style="padding-right:10px;">Total Bank: '.number_format($total_bank,$decimal).'</td>
								<td align="right" style="padding-right:10px;">Total Cheque: '.number_format($total_chque,$decimal).'</td>
								<td align="right" style="padding-right:10px;">Total Credit: '.number_format($total_credit,$decimal).'</td>
							</tr>';
						print '</table><br /><table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
					}
						$store0=$billed_store[$i];
				}
				?>	
		</table>
	<?php } ?>
	<br/>
	<?php if($type_req!=3 && $type_req!=5){ ?>
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
			<tr>
				<td colspan="7" style="border:0; background-color:black; color:white; font-weight:bold">Payment Collection</td>
			</tr>
			<?php
				$inv=$total_payment=$total_payment_cash=$total_payment_card=$total_payment_bank=$total_payment_chque=0;
				$store0='';
			for($i=0;$i<sizeof($payment_id);$i++){
				if($store0!=$payment_store[$i]){
					print'<tr>
							<td colspan="7" style="padding-left:20px; font-weight:bold; background-color:#0066AA; color:white">'.$payment_store[$i].'</td>
						</tr>';
				}
				print'<tr>
							<td colspan="3" bgcolor="#888888" height="3px"></td>
						</tr>';
				print'<tr>
						<td colspan="3" width="800px" style="padding-top: 12px;padding-left: 12px;">
							Payment No : <a href="index.php?components=billing&action=finish_payment&id='.$payment_id[$i].'">'.str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT).'</a>
							<table align="right" width="500px">
								<tr>
									<td>Customer : </td>
									<td>'.ucfirst($payment_cust[$i]).'</td>
								</tr>
								<tr>
									<td>Salesman : </td>
									<td>'.ucfirst($payment_salesman[$i]).'</td>
								</tr>
							</table>
							<br>Time : '.$payment_time[$i].'
						</td>
					</tr>';
				print'<tr>
							<td bgcolor="#F3F3FF" colspan="3">
								<table align="center" border="1" cellspacing="0" width="600px" style="margin-top: 20px;">
									<tr>
										<td align="center">Payment Type : '.$payment_type[$i].'</td>
										<td align="center">Amount :'.number_format($payment_amount[$i],$decimal).'</td>
									</tr>
								</table>
							<br>';
							print'<table width="100%" border="1" cellspacing="0">
									<tr>
										<td>'.str_replace("&#13;",'</td>
										<td>',$payment_chq_details[$i]).'</td>
									</tr>
								</table>
						</td>
					</tr>';

				if($payment_type[$i]=='Cash') $total_payment_cash+=$payment_amount[$i];
				if($payment_type[$i]=='Card') $total_payment_card+=$payment_amount[$i];
				if($payment_type[$i]=='Bank') $total_payment_bank+=$payment_amount[$i];
				if($payment_type[$i]=='Chque') $total_payment_chque+=$payment_amount[$i];
				if(sizeof($payment_id)==($i+1)){
					print '<tr style="font-weight:bold; background-color:gray; color:white">
								<td align="right" colspan="3">
									<table align="center" border="1" cellspacing="0" width="100%">
										<tr>
											<td width="25%" align="center">Total Cash : '.number_format($total_payment_cash,$decimal).'</td>
											<td width="25%" align="center">Total Card : '.number_format($total_payment_card,$decimal).'</td>
											<td width="25%" align="center">Total Bank : '.number_format($total_payment_bank,$decimal).'</td>
											<td align="center" style="padding-right:10px;">Total Cheque : '.number_format($total_payment_chque,$decimal).'</td>
										</tr>
									</table>
								</td>
							</tr>';
					print '</table><br /><table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
				}
						$store0=$payment_store[$i];
			}
			?>	
		</table>
	<?php } ?>	
	<br />
	<?php if($type_req==3 || $type_req==''){ ?>
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
			<tr>
				<td colspan="7" style="border:0; background-color:black; color:white; font-weight:bold">Return Extra Pay Collection</td>
			</tr>
			<?php
				$rtn_sub_total=0;
				$store0='';
				for($i=0;$i<sizeof($rtn_no);$i++){
					$rtn_inv_total=0;
					if($store0!=$rtn_store[$i]){
						print '<tr><td colspan="7" style="padding-left:20px; font-weight:bold; background-color:#0066AA; color:white">'.$rtn_store[$i].'</td></tr>';
					}
						print '<tr><td colspan="3" bgcolor="#888888" height="3px"></td></tr>';
						print '<tr><td colspan="3" width="800px">Return No : <a href="index.php?components=billing&action=finish_return&id='.$rtn_no[$i].'">'.str_pad($rtn_no[$i], 7, "0", STR_PAD_LEFT).'</a><table align="right" width="500px"><tr><td>Customer : </td><td>'.ucfirst($rtn_cust[$i]).'</td></tr><tr><td>Salesman : </td><td>'.ucfirst($rtn_salesman[$i]).'</td></tr></table><br>Time : '.$rtn_time[$i].'</td></tr>';
						print '<tr bgcolor="#F3F3FF"><td colspan="3"><table width="100%" cellspacing="0"><tr><td><table border="1" cellspacing="0" width="600px">';
						print '<tr><th>Return</th><th>Replace</th><th>Qty</th><th>Extra Pay</th></tr>';
						for($j=0;$j<sizeof($rtn_returnitem[$rtn_no[$i]]);$j++){
							print '<tr><td class="billingformat1">'.$rtn_returnitem[$rtn_no[$i]][$j].'</td><td class="billingformat1">'.$rtn_replaceitem[$rtn_no[$i]][$j].'</td><td class="billingformat1" align="right">'.$rtn_qty[$rtn_no[$i]][$j].'</td><td class="billingformat1" align="right">'.number_format($rtn_expay[$rtn_no[$i]][$j],$decimal).'</td></tr>';
							$rtn_inv_total+=$rtn_expay[$rtn_no[$i]][$j];
						}
						print '</table></td><td><table style="vertical-align:top" align="right" width="150px"  border="1" cellspacing="0">';
						print '<tr><td class="shipmentTB3"><strong>Total</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($rtn_inv_total,$decimal).'</strong></td></tr>';
						print '</table></td></tr></table></td></tr>';
						$rtn_sub_total+=$rtn_inv_total;
					if(sizeof($rtn_no)==($i+1)){
						print '<tr style="font-weight:bold; background-color:gray; color:white"><td style="padding-left:10px;" colspan="3">Total Extra Pay: '.number_format($rtn_sub_total,$decimal).'</td></tr>';
						print '</table><br /><table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
					}
						$store0=$rtn_store[$i];
				}
			?>	
		</table>
	<?php } ?>
	<br />

	<?php if($type_req==5 || $type_req==''){ ?>
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
			<tr>
				<td colspan="7" style="border:0; background-color:black; color:white; font-weight:bold">Warranty Pay Collection</td>
			</tr>
			<tr>
				<th width="100px">Job No</th>
				<th width="200px" >Time</th>
				<th width="150px" >Extra Pay</th>
				<th width="100px">Salesman</th>
				<th width="200px">Entity</th>
			</tr>
			<?php 
				$inv=$total_payment=0;
				$store0='';
				for($i=0;$i<sizeof($wa_no);$i++){
					if($store0!=$wa_store[$i]){
						print '<tr><td colspan="7" style="padding-left:20px; font-weight:bold; background-color:#0066AA; color:white">'.$wa_store[$i].'</td></tr>';
					}
						print '<tr><td align="center"><a href="index.php?components=billing&action=warranty_show&id='.$wa_no[$i].'">'.str_pad($wa_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center" width="50px">'.substr($wa_time[$i],0,16).'</td>
						<td align="right" style="padding-right:10px;">'.number_format($wa_pay[$i],$decimal).'</td>
						<td class="shipmentTB3">'.ucfirst($wa_salesman[$i]).'</td><td class="shipmentTB3">'.$wa_entity[$i].'</td></tr>';
						$total_payment+=$wa_pay[$i];
					if(sizeof($wa_no)==($i+1)){
						print '<tr style="font-weight:bold; background-color:gray; color:white"><td colspan="2" align="right"  style="padding-right:10px;" >Total</td><td align="right" style="padding-right:10px;">'.number_format($total_payment,$decimal).'</td><td colspan="2"></td></tr>';
						print '</table><br /><table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
					}
						$store0=$wa_store[$i];
				}
			?>	
		</table>
	<?php } ?>
</div>

<br />

<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#">
					<span style="text-decoration:none; font-family:Arial; color:navy;">
						<img src="images/print.png" alt="icon" /><br />Print
					</span>
				</a>
			</div>
		</td>
	</tr>
</table>

<?php
    include_once  'template/footer.php';
?>