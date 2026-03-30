<?php
                include_once  '../../modle/billingModule.php';
                include_once  '../../../../template/common.php';
				generateInvoice('bi.id');
                if($bi_type==1 || $bi_type==2){
                	$bill_title='INVOICE';
                	$sub_title='INVOICE NO';
                	$advance='';
                }else if($bi_type==3){
                	$bill_title='INVOICE';
                	$sub_title='REPAIR NO';
                	$advance='Advance';
                }else if($bi_type==4 || $bi_type==5){
                	if($bm_status<3){
                	$bill_title='CUST ORDER';
                	$sub_title='ORDER NO';
                	$advance='Advance'; 
                	}else{
                	$bill_title='INVOICE';
                	$sub_title='INVOICE NO';
                	$advance='';
                	}
                }
                $dn=$_GET['dn'];
                if($dn=='yes'){
                	$bill_title='DELIVERY NOTE';
                	$sub_title='DELIVERY NOTE NO';
                }

?>
<div id="print_top"></div>
	<table border="0" cellspacing="0" ><tr><td>
		  <table width="250px" align="center" style="font-family:Arial, Helvetica, sans-serif">
		  <tr><td><span style="font-family:'Arial'; font-size:20pt"><?php print $bill_title; ?></span></td></tr>
		  <tr><td style="font-size:10pt"><strong><?php print $tm_company; ?></strong>.<br />
			<?php print $tm_address; ?><br />
			Tel: <?php print $tm_tel; ?>
			</td></tr>
			<tr><td height="10px"></td></tr>
			<tr><td style="font-size:8pt">
			<?php print $sub_title; ?> # [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?> ]<br />
			<span  style="font-family:Arial; font-size:8pt">
			DATE: <?php print $bi_date; ?><br /><br />
			</span>
			</td></tr>
			<tr><td height="8px"></td></tr>
		  </table>
		
			<table align="center" width="250px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
			<tr><td colspan="4"><hr></td></tr>
			<tr style="font-family:Arial; font-size:8pt; text-align:center"><td>DESCRIPTION</td><?php if($dn=='no'){ ?><td>UNIT<br />PRICE</td><?php } ?><td>QTY</td><?php if($dn=='no'){ ?><td>TOTAL</td><?php } ?></tr>
			<tr><td colspan="4"><hr></td></tr>
		<?php
			 for($i=0;$i<sizeof($bill_id);$i++){
			 	if($bi_return_odr[$i]==0){
					 print '<tr><td colspan="4">'.$bi_desc[$i].'</td></tr>';
					 print '<tr><td>'.$bi_code[$i].'</td>';
					 if($dn=='no') print '<td align="right">'.$bi_price[$i].'</td>';
					 print '<td align="right">'.$bi_qty[$i].'</td>';
					 if($dn=='no') print '<td align="right">'.number_format($bi_qty[$i]*$bi_price[$i]).'</td>';
					 if($dn=='yes') print '<td></td><td></td>';
					 print '</tr>';
					 if($dn=='no') print '<tr><td colspan="4">&nbsp;</td></tr>';
				}
			}
			 if($dn=='no'){
				if($pay_type==3) $cash_name='Bank Transfer'; else $cash_name='Cash';
				print '	<tr><td colspan="4"><hr></td></tr>';
				print '<tr style="font-size:10pt;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total Amount</td><td align="right">'.number_format($total).'&nbsp;&nbsp;</td></tr>';	
				print '<tr style="font-size:10pt;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: '.$cash_name.'</td><td align="right">'.number_format($cash_amount).'&nbsp;&nbsp;</td></tr>';	
				print '<tr style="font-size:10pt;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: Cheque</td><td align="right">'.number_format($chque_amount).'&nbsp;&nbsp;</td></tr>';	
				print '<tr style="font-size:10pt;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Remaining Balance</td><td align="right">'.number_format($total-$cash_amount-$chque_amount).'&nbsp;&nbsp;</td></tr>';	
				if($chq0_fullNo!='') 
				print '	<tr><td colspan="4"><hr></td></tr>';
				print '	<tr><td colspan="4" align="center"><span style="padding-right:30px">'.$chq0_fullNo.'</span></td></tr>';
			}
		?>	
			</table>
		<?php  if($dn=='no'){ ?>
			<table align="center" width="250px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
			<tr><td colspan="2"><hr></td></tr>
			<tr><td width="60px">Salesman </td><td>: <?php print ucfirst($up_salesman); ?></td></tr>
			<tr><td colspan="2"><?php print '<a href="../../../../index.php?components=billing&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" >'.ucfirst($bi_cust).'</a>'; ?></td></tr>
			<tr><td height="30px">Signature </td><td>: ...............................................</td></tr>
			<tr><td height="30px">Name </td><td>: ...............................................</td></tr>
			<tr><td colspan="2"><hr></td></tr>
			<tr><td colspan="2" align="center">IT WAS A PLEASURE TO SERVE YOU<br><br>THANK YOU</td></tr>
			<tr><td colspan="2"><br></td></tr>
			</table>
		<?php }else{ ?>
			<table align="center" width="210px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
			<tr><td colspan="2"><hr></td></tr>
			<tr><td width="60px">Order Packed By </td><td>: <?php print ucfirst($bm_packed_by); ?></td></tr>
			<tr><td height="30px">Signature </td><td>: ...............................................</td></tr>
			<tr><td height="30px">Name </td><td>: ...............................................</td></tr>
			<tr><td height="30px">Date </td><td>: ...............................................</td></tr>
			<tr><td height="30px" colspan="2"><hr /></td></tr>
			<tr><td>Customer :</td><td>: <?php print '<a href="../../../../index.php?components=billing&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" >'.ucfirst($bi_cust0).'</a>'; ?></td></tr>
			<tr><td height="30px">Signature </td><td>: ...............................................</td></tr>
			<tr><td height="30px">Name </td><td>: ...............................................</td></tr>
			<tr><td height="30px">Date </td><td>: ...............................................</td></tr>
			<tr><td colspan="2"><hr></td></tr>
			<tr><td colspan="2" align="center">IT WAS A PLEASURE TO SERVE YOU<br><br>THANK YOU</td></tr>
			<tr><td colspan="2"><br></td></tr>
			</table>
		<?php } ?>
		</td></tr></table>
<br />