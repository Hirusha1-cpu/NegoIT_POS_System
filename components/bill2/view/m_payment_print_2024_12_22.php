<?php
	include_once  'template/m_header.php';
	include_once  'template/common.php';
	generatePayment();
	$currency = getCurrency(1);
    $decimal = getDecimalPlaces(1);

	$fqdn=$_SERVER['SERVER_NAME'];
	if($fqdn==$inf_url_primary){
		$url=$inf_url_primary;
	}else{
		$url=$inf_url_backup;
	}
?>

<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>

<script>
	function sendToQuickPrinterChrome(){
		var commandsToPrint =document.getElementById('print').innerHTML;
		var textEncoded = encodeURI(commandsToPrint);
		window.location.href="intent://"+textEncoded+"#Intent;scheme=quickprinter;package=pe.diegoveloper.printerserverapp;end;";
	}
</script>

<!-- ------------------------------------------------------------------------------------ -->
<?php
 include_once  'components/bill2/view/tpl/pos_payment.php';
?>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<form action="#" onsubmit="return validatePayment(2)" method="post" >

<div class="w3-container" style="margin-top:75px">
<?php
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>';
	}
?>

<hr>
<div class="w3-row">
 	<div class="w3-col s3"></div>
	<div class="w3-col">
		<?php if(($main_sub_system_id==$sub_system) || (($systemid==24) && (isset($_COOKIE['top_manager'])))){ ?>
			<table width="95%" border="0">
				<tr>
					<td bgcolor="#467898" style="font-family:Calibri; color:white; padding-left:10px">Payment Invoice Status : <span <?php if($status_out=='Deleted') print 'class="blink"'; ?> style="color:<?php print $status_color; ?>;"><strong><?php print $status_out; ?></strong></span>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<table border="1" cellspacing="0" align="center">
							<tr>
								<td>
									<table width="80%" align="center">
										<tr>
											<td><span style="font-family:'Arial Black'; font-size:20pt">PAYMENT</span></td>
										</tr>
										<tr>
											<td>
												<?php print $tm_company; ?><br />
												<?php print $tm_address; ?><br />
												Tel: <?php print $tm_tel; ?>
											</td>
										</tr>
										<tr><td height="10px"></td></tr>
										<tr>
											<td>
												PAYMENT # [<?php print  str_pad($payment_id, 7, "0", STR_PAD_LEFT); ?>]<br />
												<span  style="font-family:Arial; font-size:11pt">
												DATE: <?php print $payment_date; ?><br /><br />
												</span>
											</td>
										</tr>
										<tr><td height="10px"></td></tr>
									</table>

									<table align="center" width="300px" border="0" cellspacing="0" >
										<tr>
											<td colspan="2">------------------------------------------------------------------------</td>
										</tr>
										<?php if($invoice_no!=0)
											print '<tr><td>&nbsp;&nbsp;For Invoice</td><td>'.str_pad($invoice_no, 7, "0", STR_PAD_LEFT).'</td></tr>';
											?>
											<tr>
												<td>&nbsp;&nbsp;Paid Amount<br/>&nbsp;&nbsp;[<?php print $payment_type_n; ?>]</td>
												<td><?php print $currency.' '.number_format($amount, $decimal); ?></td>
											</tr>
											<?php if(isCustomerTotalOutstandingShowInBill(1)) { ?>
												<tr style="" id="total_outstanding_row">
													<td>&nbsp;&nbsp;Total <br>&nbsp;&nbsp;Outstanding<br/>&nbsp;&nbsp;Amount</td>
													<td><?php print $currency; ?> <span id="credit_balance_span"><?php print number_format($credit_balance, $decimal); ?></span></td>
												</tr>
											<?php } ?>
											<tr>
												<td colspan="2">
													<br /><br />
												</td>
											</tr>
										<?php if($payment_type==2){ ?>
											<tr><td>&nbsp;&nbsp;Bank</td><td><?php print $chque_bank; ?></td></tr>
											<tr><td>&nbsp;&nbsp;Branch</td><td><?php print $chque_branch; ?></td></tr>
											<tr><td>&nbsp;&nbsp;Cheque No</td><td><?php print $chque_no; ?></td></tr>
											<tr><td>&nbsp;&nbsp;Cheque Date</td><td><?php print $chque_date; ?></td></tr>
										<?php }
										if($payment_type==3){ ?>
											<tr><td>&nbsp;&nbsp;Bank</td><td><?php print $bank_trans; ?></td></tr>
										<?php } ?>
										<tr><td colspan="2">------------------------------------------------------------------------</td></tr>
									</table>

									<table>
										<tr><td width="80px">&nbsp;&nbsp;Salesman</td><td><?php print ucfirst($salesman); ?></td></tr>
										<tr><td>&nbsp;&nbsp;Signature</td><td></td></tr>
										<tr><td>&nbsp;&nbsp;Paid By</td><td ><?php print ucfirst($cust_name); ?></td></tr>
										<tr><td>&nbsp;&nbsp;Signature</td><td></td></tr>
										<tr><td colspan="4">------------------------------------------------------------------------</td></tr>
									</table>

									<br />
								</td>
							</tr>
						</table>
					</td>
					<td style="vertical-align:top" align="right">
						<table align="right">
							<tr>
								<td>
									<div style="background-color:#6699FF; border:medium; border-color:black; width:80px; text-align:center">
										<a class="shortcut-button" onclick="parent.location='printscheme://<?php print $url; ?>/pos_payment.php?id=<?php print $_REQUEST['id']; ?>&store=<?php print $_COOKIE['store']; ?>'" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
											<img src="images/print.png" alt="icon" /><br />
											Print
										</span></a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div style="background-color:#9966FF; border:medium; border-color:black; width:80px; text-align:center">
										<a class="shortcut-button" onclick="sendToQuickPrinterChrome()" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
											<img src="images/print.png" alt="icon" /><br />
											New Print
										</span></a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<br />
										<?php if($py_status==0){
										if($paymentpermission){ ?>
											<div style="background-color:#FF9191; border:medium; border-color:black; width:80px; text-align:center">
												<a class="shortcut-button" onclick="deletePayment(<?php print $_GET['id']; ?>,'bill2')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
													<img src="images/cancel.png" alt="icon" /><br />
													Cancel
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

<div class="w3-row">
  	<div class="w3-col s3">
  	</div>
  	<div class="w3-col " align="center"></div>
</div>

<hr>
</div>
</form>

<?php
    include_once  'template/m_footer.php';
?>