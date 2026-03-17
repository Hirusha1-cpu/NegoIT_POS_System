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

    if($bi_type==1 || $bi_type==2){
		$bill_title1='INVOICE';
	}else if($bi_type==3){
		$bill_title1='INVOICE';
	}else if($bi_type==4 || $bi_type==5){
		if($bm_status<3){
			$bill_title1='CUST ORDER';
		}else{
			$bill_title1='INVOICE';
		}
    }

    $decimal=0;
    if($systemid==13) $decimal=2; 
    if($systemid==14) $decimal=2; 
	if($systemid==17) $decimal=2; 
?>
<script>
function sendToQuickPrinterChrome($id){
    var commandsToPrint =document.getElementById('print').innerHTML;
    var textEncoded = encodeURI(commandsToPrint);
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET","index.php?components=billing&action=sms&id="+$id,true);
	xmlhttp.send();
    window.location.href="intent://"+textEncoded+"#Intent;scheme=quickprinter;package=pe.diegoveloper.printerserverapp;end;";
}
</script>
<!-- ------------------------------------------------------------------------------------ -->
<form method="post" >

<div class="w3-container" style="margin-top:75px">
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}

if(($tm_template==3)||($tm_template==4)) include_once  'components/billing/view/tpl/pos_bill2.php';
else  include_once  'components/billing/view/tpl/pos_bill1.php';


?>	

<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
	<?php if(($main_sub_system_id==$sub_system)||($main_refinvid==$_COOKIE['store'])){ ?>
  	<table width="90%">
	<tr><td bgcolor="#467898" style="font-family:Calibri; color:white; padding-left:10px">Invoice Status : <span <?php if($status_out=='Deleted') print 'class="blink"'; ?> style="color:<?php print $status_color; ?>;"><strong><?php print $status_out; ?></strong></span></td><td></td></tr>
  	<tr><td style="vertical-align:top;">
		  <table border="1" cellspacing="0" align="center"><tr><td>
		  <table width="90%" align="center">
		  <tr><td><span style="font-family:'Arial Black'; font-size:20pt"><?php print $bill_title1; ?></span></td></tr>
		  <tr><td><?php print $tm_company; ?><br />
			<?php print $tm_address; ?><br />
			Tel: <?php print $tm_tel; ?>
			</td></tr>
			<tr><td height="10px"></td></tr>
			<tr><td>
			INVOICE # [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?> ]<br />
			<span  style="font-family:Arial; font-size:11pt">
			DATE: <?php print $bi_date; ?><br /><br />
			</span>
			</td></tr>
			<tr><td height="10px"></td></tr>
		  </table>
		
			<table align="center" width="300px" border="0" cellspacing="0" >
			<tr><td colspan="4">------------------------------------------------------------------------</td></tr>
			<tr style="font-family:Arial; font-size:10pt"><th>DESCRIPTION</th><th>UNIT<br />PRICE</th><th>QTY</th><th>TOTAL</th></tr>
			<tr><td colspan="4">------------------------------------------------------------------------</td></tr>
		<?php
			for($i=0;$i<sizeof($bill_id);$i++){
				if($bi_return_odr[$i]==0){
					print '<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px">'.$bi_desc[$i].'</td><td align="right" style="border-bottom:0; border-top:0; ">'.number_format(($bi_price[$i]+$bi_discount[$i]),$decimal).'&nbsp;&nbsp;</td><td width="25px" style="border-bottom:0; border-top:0;" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($bi_qty[$i]).'</td><td align="right" style="border-bottom:0; border-top:0;">'.number_format(($bi_qty[$i]*$bi_price[$i]),$decimal).'&nbsp;&nbsp;</td></tr>';
					if($bi_discount[$i]!=0)
					print '<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px">Discount: '.number_format($bi_discount[$i]/($bi_price[$i]+$bi_discount[$i])*100).'%</td><td align="right">'.number_format($bi_price[$i],$decimal).'&nbsp;&nbsp;</td><td></td><td></td></tr>';
					print '<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px" colspan="4"></td></tr>';
				}
			}
				print '	<tr><td colspan="4">------------------------------------------------------------------------</td></tr>';
				if($pay_type==3) $cash_name='Bank Transfer'; else $cash_name='Cash';
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total Amount</td><td align="right">'.number_format($total,$decimal).'&nbsp;&nbsp;</td></tr>';	
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: '.$cash_name.'</td><td align="right">'.number_format($cash_amount,$decimal).'&nbsp;&nbsp;</td></tr>';	
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: Chque</td><td align="right">'.number_format($chque_amount,$decimal).'&nbsp;&nbsp;</td></tr>';	
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Remaining Balance</td><td align="right">'.number_format(($total-$cash_amount-$chque_amount),$decimal).'&nbsp;&nbsp;</td></tr>';	
				print '	<tr><td colspan="4">------------------------------------------------------------------------</td></tr>';
				if($chq0_fullNo!='') 
				print '	<tr><td colspan="4" align="center"><span style="padding-right:30px">'.$chq0_fullNo.'</span></td></tr>';
				print '	<tr><td colspan="4">------------------------------------------------------------------------</td></tr>';
				print '	<tr><td colspan="4">Salesman : '.ucfirst($up_salesman).'</td></tr>';
				if($systemid==17){
				print '	<tr><td colspan="4">Cust.Tax : '.$cu_nickname.'</td></tr>';
				}	
				print '	<tr><td colspan="4"><a href="index.php?components=billing&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" title="'.$cu_details.'" >'.ucfirst($bi_cust).'</a>'.'</td></tr>';
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
						print '<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px">'.$bi_desc[$i].'</td><td align="right" style="border-bottom:0; border-top:0; ">'.number_format($bi_price[$i]).'&nbsp;&nbsp;</td><td width="25px" style="border-bottom:0; border-top:0;" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($bi_qty[$i]).'</td><td align="right" style="border-bottom:0; border-top:0;">'.number_format($bi_qty[$i]*$bi_price[$i]).'&nbsp;&nbsp;</td></tr>';
						print '<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px" colspan="4"></td></tr>';
					}
				}
				print '	<tr><td colspan="4"><hr /></td></tr>';
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total New Replacement Amount</td><td align="right">'.number_format($total2).'&nbsp;&nbsp;</td></tr>';	
				print '	<tr><td colspan="4"><hr /></td></tr>';
			}
			if(sizeof($removed_code)>0){
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Remaining Return Item Credit</td><td align="right">'.number_format($return_cr_bal).'&nbsp;&nbsp;</td></tr>';	
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
			<br />
		</td></tr></table>
</td><td style="vertical-align:top" align="right">
		<table align="right">
		<tr><td>
	<?php if(($bm_lock==1)||($bm_lock==2)){ ?>
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px; text-align:center">
				<a class="shortcut-button" onclick="parent.location='printscheme://<?php print $url.'/'.$execute.'?id='.$_REQUEST['id']; ?>'" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/print.png" alt="icon" /><br />
					Print Bill
				</span></a>
			</div>
	<?php } ?>
			</td></tr>
		<tr><td>
	<?php if(($bm_lock==1)||($bm_lock==2)){ ?>
			<div style="background-color:#9966FF; border:medium; border-color:black; width:80px; text-align:center">
				<a class="shortcut-button" onclick="sendToQuickPrinterChrome(<?php print $_GET['id']; ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/print.png" alt="icon" /><br />
					New Print
				</span></a>
			</div>
	<?php } ?>
			</td></tr>
			<tr><td><br />
	<?php if($bm_status!=0){ 
				if($billpermission){ ?>
			<div style="background-color:#FF9191; border:medium; border-color:black; width:80px; text-align:center">
				<a class="shortcut-button" onclick="deleteBill(<?php print $_GET['id']; ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/cancel.png" alt="icon" /><br />
					Cancel Bill
				</span></a>
			</div>
	<?php } } ?>		
		</td></tr>
		</table>
  	</td></tr></table>
 <?php } ?>
  </div>
</div>
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col " align="center">

  </div>
</div>
<hr>
</div>
</form>

<?php
                include_once  'template/m_footer.php';
?>