<?php
	include_once  'template/m_header.php';
	generateInvoice('bi.id');
	generalPrint();
	generateReturnList();

	$fqdn=$_SERVER['SERVER_NAME'];
	if($fqdn==$inf_url_primary){
		$url=$inf_url_primary;
	}else{
		$url=$inf_url_backup;
	}

	if(($tm_template==3)||($tm_template==4)) $execute='pos_bill2.php'; else $execute='pos_bill1.php';
	if($bm_status==6){
		$bill_title1='CUST ORDER';
		$sub_title='INVOICE NO';
		if(($tax != '') && ($tax != 0)) { $sub_title='TAX INVOICE NO'; }
	}else{
		$bill_title1='INVOICE';
		$sub_title='INVOICE NO';
		if(($tax != '') && ($tax != 0)){ $bill_title1='TAX INVOICE'; $sub_title='TAX INVOICE NO'; }
	}
	if($bi_type==3){
		$bill_title1='INVOICE';
		$sub_title='REPAIR NO';
		if(($tax != '') && ($tax != 0)) { $bill_title1='TAX INVOICE'; }
	}
	$decimal_places = 0;
    if(($systemid==13 ) || ($systemid==14 ) || ($systemid==17)) $decimal_places=2;
?>
<script>
	function sendToQuickPrinterChrome($id){
		var commandsToPrint =document.getElementById('print').innerHTML;
		var textEncoded = encodeURI(commandsToPrint);
		xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET","index.php?components=bill2&action=sms&id="+$id,true);
		xmlhttp.send();
		window.location.href="intent://"+textEncoded+"#Intent;scheme=quickprinter;package=pe.diegoveloper.printerserverapp;end;";
	}

	function deleteInvoice(id){
		var check= confirm("Do you really want to Delete this Bill?");
	 if (check== true)
		window.location = 'index.php?components=bill2&action=delete&id='+id;
	}
</script>


<form method="post">
	<div class="w3-container" style="margin-top:75px">
		<?php
			if(isset($_REQUEST['message'])){
				if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
				print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
			}

			if(($tm_template==3)||($tm_template==4)){
				include_once  'components/bill2/view/tpl/pos_bill2.php';
			}
			else{
				include_once  'components/bill2/view/tpl/pos_bill1.php';
			}
		?>
		<table align="center">
			<tr>
				<td>
					<div id="notifications"></div>
				</td>
			</tr>
		</table>
		<hr>
		<div class="w3-row">
			<div class="w3-col s3"></div>
  			<div class="w3-col">
				<?php if(($main_sub_system_id==$sub_system)||($main_refinvid==$_COOKIE['store'])){ ?>
  					<table width="90%">
						<!-- Invoice status -->
						<tr>
							<td bgcolor="#467898" style="font-family:Calibri; color:white; padding-left:10px">Invoice Status : <span <?php if($status_out=='Deleted') print 'class="blink"'; ?> style="color:<?php print $status_color; ?>;"><strong><?php print $status_out; ?></strong></span></td>
							<td></td>
						</tr>
						<!-- Invoice -->
  						<tr>
							<!-- Bill Body -->
							<td style="vertical-align:top;">
		  						<table border="1" cellspacing="0" align="center">
									<tr>
										<td>
											<!-- Invoice Top Header -->
											<table width="90%" align="center">
												<tr>
													<td>
														<span style="font-family:'Arial Black'; font-size:20pt"><?php print $bill_title1; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<?php print $tm_company; ?><br />
														<?php print $tm_address; ?><br />
														Tel: <?php print $tm_tel; ?>
														<?php if($trn_no != ''){ ?>
															<br>
															TRN No : <?php print $trn_no; ?></td>
														<?php } ?>
													</td>
												</tr>
												<tr>
													<td height="10px"></td>
												</tr>
												<tr>
													<td>
														<?php print $sub_title; ?>: [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?>]<br />
														<span  style="font-family:Arial; font-size:11pt">
															DATE: <?php print $bi_date; ?><br /><br />
														</span>
													</td>
												</tr>
												<tr>
													<td height="10px"></td>
												</tr>
											</table>
											<!--/ Invoice Top Header -->

											<!-- Invoice Body -->
											<table align="center" width="300px" border="0" cellspacing="0" >
												<tr>
													<td colspan="4">------------------------------------------------------------------------</td>
												</tr>
												<tr style="font-family:Arial; font-size:10pt">
													<th>DESCRIPTION</th>
													<th>UNIT<br />PRICE</th>
													<th>QTY</th>
													<th>TOTAL</th>
												</tr>
												<tr>
													<td colspan="4">------------------------------------------------------------------------</td>
												</tr>
												<?php
													for($i=0;$i<sizeof($bill_id);$i++){
														if($bi_return_odr[$i]==0){
															print '
															<tr style="font-size:10pt" height="20px">
																<td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px">'.$bi_desc[$i].'</td>
																<td align="right" style="border-bottom:0; border-top:0; ">'.number_format(($bi_price[$i]+$bi_discount[$i]),$decimal_places).'&nbsp;&nbsp;</td>
																<td width="25px" style="border-bottom:0; border-top:0;" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($bi_qty[$i]).'</td>
																<td align="right" style="border-bottom:0; border-top:0;">'.number_format(($bi_qty[$i]*$bi_price[$i]),$decimal_places).'&nbsp;&nbsp;</td></tr>';
															if($bi_discount[$i]!=0)
																print '
																		<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px">Discount: '.number_format($bi_discount[$i]/($bi_price[$i]+$bi_discount[$i])*100).'%</td>
																		<td align="right">'.number_format($bi_price[$i],$decimal_places).'&nbsp;&nbsp;</td>
																		<td></td>
																		<td></td>
																	</tr>';
																print '
															<tr style="font-size:10pt" height="20px">
																<td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px" colspan="4">
																</td>
															</tr>';
														}
													}

													if(($pay_pro_fee > 0) && ($card_amount > 0)){
														$total1 = ((($pay_pro_fee / 100) * $card_amount) + $total);
													}else{
														$total1 = $total;
													}
													print '	<tr><td colspan="4">------------------------------------------------------------------------</td></tr>';

													if(($tax != '') && ($tax != 0)) {
														print '<tr style="font-size:10pt; font-weight:900;">
																<td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0;">Subtotal</td>
																<td align="right">'.number_format($sub_total, $decimal_places).'&nbsp;&nbsp;</td>
															</tr>';
														print '<tr style="font-size:10pt; font-weight:900;">
																<td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0;">Value Added Tax '.$tax.'%</td>
																<td align="right">'.number_format($tax_added_value, $decimal_places).'&nbsp;&nbsp;</td>
															</tr>';
													}
													print '<tr style="font-size:10pt; font-weight:900;">
															<td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0;">Bill Total</td>
															<td align="right">'.number_format($total1, $decimal_places).'&nbsp;&nbsp;</td>
														</tr>';
													print '<tr style="font-size:10pt; font-weight:900;">
															<td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0;">Payment: Cash</td>
															<td align="right">'.number_format($cash_amount, $decimal_places).'&nbsp;&nbsp;</td>
														</tr>';
													print '<tr style="font-size:10pt; font-weight:900;">
															<td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0;">Payment: Card</td>
															<td align="right">'.number_format($card_amount, $decimal_places).'&nbsp;&nbsp;</td>
														</tr>';
													print '<tr style="font-size:10pt; font-weight:900;">
															<td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0;">Payment Processing Fee</td>
															<td align="right">'.$pay_pro_fee. '%&nbsp;&nbsp;</td>
														</tr>';
													print '<tr style="font-size:10pt; font-weight:900;">
															<td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0;">Payment: Bank</td>
															<td align="right">'.number_format($bank_amount, $decimal_places).'&nbsp;&nbsp;</td>
														</tr>';
													print '<tr style="font-size:10pt; font-weight:900;">
															<td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0;">Payment: Cheque</td>
															<td align="right">'.number_format($chque_amount, $decimal_places).'&nbsp;&nbsp;</td>
														</tr>';
													print '<tr style="font-size:10pt; font-weight:900;">
															<td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0;">Remaining Balance</td>
															<td align="right">'.number_format(($total-$cash_amount-$card_amount-$chque_amount-$bank_amount), $decimal_places).'&nbsp;&nbsp;</td>
														</tr>';
													// if($pay_type==3) $cash_name='Bank Transfer'; else $cash_name='Cash';
													// print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total Amount</td><td align="right">'.number_format($total,$decimal).'&nbsp;&nbsp;</td></tr>';
													// print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: '.$cash_name.'</td><td align="right">'.number_format($cash_amount,$decimal).'&nbsp;&nbsp;</td></tr>';
													// print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: Chque</td><td align="right">'.number_format($chque_amount,$decimal).'&nbsp;&nbsp;</td></tr>';
													// print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Remaining Balancee</td><td align="right">'.number_format(($total-$cash_amount-$chque_amount),$decimal).'&nbsp;&nbsp;</td></tr>';
													print '	<tr><td colspan="4">------------------------------------------------------------------------</td></tr>';
													if($chq0_fullNo!=''){
														print '	<tr><td colspan="4" align="center"><span style="padding-right:30px">'.$chq0_fullNo.'</span></td></tr>';
														print '	<tr><td colspan="4" align="center"><span style="padding-right:30px">'.$chequedate.'</span></td></tr>';
													}
													print '	<tr><td colspan="4">------------------------------------------------------------------------</td></tr>';
													print '	<tr><td colspan="4">Salesman : '.ucfirst($up_salesman).'</td></tr>';
													print '	<tr><td colspan="4"><a href="index.php?components=bill2&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" title="'.$cu_details.'" >'.ucfirst($bi_cust).'</a>'.'</td></tr>';
													if(($tax_added_value != 0) && ($trn_no != '' || $trn_no != 0)){
														print '	<tr><td colspan="4">TRN No : '.$cust_tax_no.'</td></tr>';
													}
													print '	<tr><td colspan="4">&nbsp;</td></tr>';
													print '	<tr><td colspan="4">Signature : _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ </td></tr>';
													print '	<tr><td colspan="4">------------------------------------------------------------------------</td></tr>';
													print '	<tr><td colspan="4" align="center">IT WAS A PLEASURE TO SERVE YOU</td></tr>';
													print '	<tr><td colspan="4" align="center">THANK YOU</td></tr>';
													print '	<tr><td colspan="4"><hr /></td></tr>';
													if($return_odr){
														print '	<tr><td colspan="4" align="center">NEW REPLACEMENTS FOR RETURN ITEMS</td></tr>';
														print '	<tr><td colspan="4"><hr /></td></tr>';
														print ' <tr style="font-family:Arial; font-size:10pt"><th>DESCRIPTION</th><th>UNIT<br />PRICE</th><th>QTY</th><th>TOTAL</th></tr>';
														print '	<tr><td colspan="4"><hr /></td></tr>';
														$total2=0;
														for($i=0;$i<sizeof($bill_id);$i++){
															if($bi_return_odr[$i]==1){
																$total2+=$bi_qty[$i]*$bi_price[$i];
																print '<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px">'.$bi_desc[$i].'</td><td align="right" style="border-bottom:0; border-top:0; ">'.number_format($bi_price[$i], $decimal_places).'&nbsp;&nbsp;</td><td width="25px" style="border-bottom:0; border-top:0;" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($bi_qty[$i]).'</td><td align="right" style="border-bottom:0; border-top:0;">'.number_format(($bi_qty[$i]*$bi_price[$i]), $decimal_places).'&nbsp;&nbsp;</td></tr>';
																print '<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px" colspan="4"></td></tr>';
															}
														}
														print '	<tr><td colspan="4"><hr /></td></tr>';
														print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total New Replacement Amount</td><td align="right">'.number_format($total2, $decimal_places).'&nbsp;&nbsp;</td></tr>';
														print '	<tr><td colspan="4"><hr /></td></tr>';
													}
													if(sizeof($removed_code)>0){
														print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Remaining Return Item Credit</td><td align="right">'.number_format($return_cr_bal, $decimal_places).'&nbsp;&nbsp;</td></tr>';
														print '	<tr><td colspan="4"><hr /></td></tr>';
													}
													if(sizeof($rt_code)>0){
														print '	<tr><td colspan="4" align="center">REPLACEMENTS FOR RETURN ITEMS</td></tr>';
														print '	<tr><td colspan="4"><hr /></td></tr>';
														print '	<tr style="font-family:Arial; font-size:10pt"><th colspan="3">DESCRIPTION</th><th>QTY</th></tr>';
														for($i=0;$i<sizeof($rt_code);$i++){
															print '<tr><td colspan="3">'.$rt_desc[$i].'</td><td align="right">'.number_format($rt_qty[$i]).'&nbsp;&nbsp;</td></tr>';
															}
														print '	<tr><td colspan="4"><hr /></td></tr>';
													}
													if((sizeof($rt_pending_code)>0)||(sizeof($removed_code)>0)){
														print '	<tr><td colspan="4"><hr /></td></tr>';
														print '	<tr><td colspan="4" align="center">PENDING RETURN ITEMS</td></tr>';
														print '	<tr><td colspan="4"><hr /></td></tr>';
														print '	<tr style="font-family:Arial; font-size:10pt"><th colspan="3">DESCRIPTION</th><th>QTY</th></tr>';
														for($i=0;$i<sizeof($rt_pending_code);$i++){
															print '<tr><td colspan="3">'.$rt_pending_desc[$i].'</td><td align="right">'.number_format($rt_pending_qty[$i]).'&nbsp;&nbsp;</td></tr>';
														}
														print '	<tr><td colspan="4"><br /></td></tr>';
														for($i=0;$i<sizeof($removed_code);$i++){
															print '<tr><td colspan="3"><strike>'.$removed_desc[$i].'</strike></td><td align="right"><strike>'.number_format($removed_qty[$i]).'</strike>&nbsp;&nbsp;</td></tr>';
														}
														print '	<tr><td colspan="4"><hr /></td></tr>';
													}
												?>
											</table>
											<!--/ Invoice Body -->
											<br/>
										</td>
									</tr>
								</table>
							</td>
							<!-- Bill Print Options -->
							<td style="vertical-align:top" align="right">
								<table align="right">
									<tr>
										<td>
											<?php if(($bm_lock==1)||($bm_lock==2)){ ?>
												<div style="background-color:#6699FF; border:medium; border-color:black; width:80px; text-align:center">
													<a class="shortcut-button" onclick="parent.location='printscheme://<?php print $url.'/'.$execute.'?id='.$_REQUEST['id']; ?>'" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
														<img src="images/print.png" alt="icon" /><br />
														Print Bill
													</span></a>
												</div>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td>
											<?php if(($bm_lock==1)||($bm_lock==2)){ ?>
												<div style="background-color:#9966FF; border:medium; border-color:black; width:80px; text-align:center">
													<a class="shortcut-button" onclick="sendToQuickPrinterChrome(<?php print $_GET['id']; ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
														<img src="images/print.png" alt="icon" /><br />
														New Print
													</span></a>
												</div>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td>
											<br/>
											<?php if($bm_status!=0){
													if($billpermission){ ?>
													<div style="background-color:#FF9191; border:medium; border-color:black; width:80px; text-align:center">
														<a class="shortcut-button" onclick="deleteInvoice(<?php print $_GET['id']; ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
															<img src="images/cancel.png" alt="icon" /><br />
															Cancel Bill
														</span></a>
													</div>
											<?php } } ?>
										</td>
									</tr>
								</table>
  							</td>
						</tr>
					</table>
 				<?php } ?>
  			</div>
		</div>
		<hr>
	</div>
</form>

<?php
    include_once  'template/m_footer.php';
?>