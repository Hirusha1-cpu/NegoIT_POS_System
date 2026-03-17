<?php
    if($chq0_date!='') $chequedate='[ Cheque Date: '.$chq0_date.' ]'; else $chequedate='';
    if($bi_type==1 || $bi_type==2){
	    $bill_title='### INVOICE ####';
	    $sub_title='INVOICE NO';
	    $advance='       ';
    }else if($bi_type==3){
	    $bill_title='### INVOICE ####';
	    $sub_title='REPAIR NO';
	    $advance='Advance';
    }else if($bi_type==4 || $bi_type==5){
    	if($bm_status<3){
		$bill_title='CUST ORDER';
		$sub_title='ORDER NO';
		$advance='Advance'; 
		}else{
	    $bill_title='### INVOICE ####';
		$sub_title='INVOICE NO';
		$advance='';
		}
    }
	$systemid=inf_systemid(1);
    $decimal=0;
    if($systemid==14) $decimal=2; 
?>
<div id="print" style="display:none">
################################################
################<?php print $bill_title; ?>################
################################################<BR>
<BOLD><?php print $tm_company; ?><BR>
<?php print str_replace("<br />","<BR>",$tm_address); ?><BR>Tel: <?php print $tm_tel; ?><BR>
<?php print $sub_title; ?> # [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?> ]
DATE: <?php print $bi_date; ?><BR>
------------------------------------------------
 DESCRIPTION      U\PRICE       QTY      TOTAL
------------------------------------------------<br>
<?php
 for($i=0;$i<sizeof($bill_id);$i++){
 if($bi_return_odr[$i]==0){
 print str_replace("<br />","<BR>",$bi_desc[$i]);
 print str_pad($bi_code[$i],15,' ',STR_PAD_RIGHT).'  '.str_pad(number_format($bi_price[$i],$decimal),6,' ',STR_PAD_LEFT).'      '.str_pad(number_format($bi_qty[$i]),5,' ',STR_PAD_LEFT).'  '.str_pad(number_format(($bi_qty[$i]*$bi_price[$i]),$decimal),8,' ',STR_PAD_LEFT).'<BR>';
 print '<BR>';
 }
}

if(($pay_pro_fee > 0) && ($card_amount > 0)){
	$total1 = ((($pay_pro_fee / 100) * $card_amount) + $total);
}else{
	$total1 = $total; 
}
								
print '----------------------------------------------<BR>';
print 'Bill Total '. number_format($total1) . '<BR>';
print 'Payment: Cash '. number_format($cash_amount) .'<BR>';
print 'Payment: Card '. number_format($card_amount) .'<BR>';
print 'Payment Processing Fee '. $pay_pro_fee.' %' .'<BR>';
print 'Payment: Bank '. number_format($bank_amount) .'<BR>';
print 'Payment: Cheque '. number_format($chque_amount) .'<BR>';
print 'Remaining Balance '. number_format($total-$cash_amount-$card_amount-$chque_amount-$bank_amount) .'<BR>';
print '----------------------------------------------<BR>';
if($chq0_fullNo!=''){
	print ''.$chq0_fullNo.'<BR>';
	print ''.$chequedate.'<BR>';
}
print '----------------------------------------------<BR>';

// if($pay_type==3) $cash_name='Bank Tr'; else $cash_name='Cash   ';
// print '         Total Amount '.str_pad(number_format($total,$decimal),22,' ',STR_PAD_LEFT).'<BR>';	
// print $advance.' Payment:  '.$cash_name.' '.str_pad(number_format($cash_amount,$decimal),18,' ',STR_PAD_LEFT).'<BR>';	
// print $advance.' Payment: Cheque '.str_pad(number_format($chque_amount,$decimal),21,' ',STR_PAD_LEFT).'<BR>';	
// print '    Remaining Balance '.str_pad(number_format(($total-$cash_amount-$chque_amount),$decimal),22,' ',STR_PAD_LEFT).'<BR>';	
// print '----------------------------------------------<BR>';
// if($chq0_fullNo!=''){ 
// print '  '.$chq0_fullNo.'<BR>';
// print '  '.$chequedate.'<BR>';
// }
print '----------------------------------------------<BR>';
print '	     Salesman : '.ucfirst($up_salesman).' <BR>';
print '	     Customer : '.ucfirst($bi_cust0).' <BR>';
print '<BR><BR>';
print '    Signature : ------------------------------- <BR>';
print '    Name      : <BR>';
print '----------------------------------------------<BR>';
print '	          IT WAS A PLEASURE TO SERVE YOU  <BR>';
print '	                    THANK YOU<BR><BR>';
if($return_odr){
print '----------------------------------------------<BR>';
print '     NEW REPLACEMENTS FOR RETURN ITEMS <BR>';
print '----------------------------------------------<BR>';
	print ' DESCRIPTION      U\PRICE       QTY      TOTAL
	------------------------------------------------<BR>';
	$total2=0;
	 for($i=0;$i<sizeof($bill_id);$i++){
		 if($bi_return_odr[$i]==1){
		 $total2+=$bi_qty[$i]*$bi_price[$i];
		 print str_replace("<br />","<BR>",$bi_desc[$i]);
		 print str_pad($bi_code[$i],15,' ',STR_PAD_RIGHT).'  '.str_pad(number_format($bi_price[$i]),6,' ',STR_PAD_LEFT).'      '.str_pad(number_format($bi_qty[$i]),5,' ',STR_PAD_LEFT).'  '.str_pad(number_format($bi_qty[$i]*$bi_price[$i]),8,' ',STR_PAD_LEFT).'<br>';
		 print '<br>';
		 }
	 }
	print '------------------------------------------------<br>';
	print 'Total New Replacement Amount: '.str_pad(number_format($total2),10,' ',STR_PAD_LEFT).'<br>';	
	print '------------------------------------------------<br>';
}
if(sizeof($removed_code)>0){
	print 'Remaining Return Item Credit: '.str_pad(number_format($return_cr_bal),10,' ',STR_PAD_LEFT).'<br>';	
	print '------------------------------------------------<br>';
}
if(sizeof($rt_code)>0){
print '----------------------------------------------<br>';
print '       REPLACEMENTS FOR RETURN ITEMS <br>';
print '----------------------------------------------<br>';
	print 'DESCRIPTION                      QTY
	------------------------------------------------<br>';
	for($i=0;$i<sizeof($rt_code);$i++){
	 print $rt_desc[$i].'<br>';
	 print str_pad($rt_code[$i],25,' ',STR_PAD_RIGHT).'     '.str_pad(number_format($rt_qty[$i]),5,' ',STR_PAD_LEFT).'<br>';
	 print '<br>';
	}
	print '----------------------------------------------<br>';
}
if(sizeof($rt_pending_code)>0){
print '----------------------------------------------<br>';
print '             PENDING RETURN ITEMS <br>';
print '----------------------------------------------<br>';
print 'DESCRIPTION                      QTY
------------------------------------------------<br>';

for($i=0;$i<sizeof($rt_pending_code);$i++){
 print $rt_pending_desc[$i].'<br>';
 print str_pad($rt_pending_code[$i],25,' ',STR_PAD_RIGHT).'     '.str_pad(number_format($rt_pending_qty[$i]),5,' ',STR_PAD_LEFT).'<br>';
 print '<br>';
}
}
if(sizeof($removed_code)>0){
print '<br>';
print '----------------------------------------------<br>';
print '             CANCELED RETURN ITEMS <br>';
print '----------------------------------------------<br>';

for($i=0;$i<sizeof($removed_code);$i++){
 print $removed_desc[$i].'<br>';
 print str_pad($removed_code[$i],25,'-',STR_PAD_RIGHT).'-----'.str_pad(number_format($removed_qty[$i]),5,'-',STR_PAD_LEFT).'<br>';
 print '<br>';
}
}
print '----------------------------------------------<br>';
Print 'Print Timestamp : '.$print_time;
?>
<br>
<CUT>
</div>