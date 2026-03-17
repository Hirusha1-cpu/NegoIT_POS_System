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
		$bill_title='INVOICE';
		$sub_title='INVOICE NO';
		$advance='';
		}
    }
?>
<div id="print" style="display:none">
################################################
################<?php print $bill_title; ?>################
################################################<br>
<BOLD><?php print $tm_company; ?><BR>
<?php print str_replace("<br />","<BR>",$tm_address); ?><BR>Tel: <?php print $tm_tel; ?><BR>
<?php print $sub_title; ?> # [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?> ]
DATE: <?php print $bi_date; ?><br>
------------------------------------------------
                UNIT      DIS.
 DESCRIPTION    PRICE    PRICE    QTY    TOTAL
------------------------------------------------<br>
<?php
 for($i=0;$i<sizeof($bill_id);$i++){
 if($bi_discount[$i]!=0) $dis_print='@'.number_format($bi_discount[$i]/($bi_price[$i]+$bi_discount[$i])*100).'%'; else $dis_print='';
 print str_replace("<br />","<BR>",$bi_desc[$i]);
 print str_pad($bi_code[$i].' '.$dis_print,16,' ',STR_PAD_RIGHT).'  '.str_pad(number_format($bi_price[$i]+$bi_discount[$i]),5,' ',STR_PAD_LEFT).'  '.str_pad(number_format($bi_price[$i]),5,' ',STR_PAD_LEFT).'   '.str_pad(number_format($bi_qty[$i]),4,' ',STR_PAD_LEFT).'  '.str_pad(number_format(($bi_qty[$i]*$bi_price[$i]),2),7,' ',STR_PAD_LEFT).'<br>';
 print '<br>';
}
print '----------------------------------------------<br>';
if($pay_type==3) $cash_name='Bank Tr'; else $cash_name='Cash   ';
print '         Total Amount '.str_pad(number_format($total,2),22,' ',STR_PAD_LEFT).'<br>';	
print $advance.' Payment:  '.$cash_name.' '.str_pad(number_format($cash_amount,2),18,' ',STR_PAD_LEFT).'<br>';	
print $advance.' Payment: Chque '.str_pad(number_format($chque_amount,2),21,' ',STR_PAD_LEFT).'<br>';	
print '    Remaining Balance '.str_pad(number_format(($total-$cash_amount-$chque_amount),2),22,' ',STR_PAD_LEFT).'<br>';	
print '----------------------------------------------<br>';
if($chq0_fullNo!=''){ 
print '  '.$chq0_fullNo.'<br>';
print '  '.$chequedate.'<br>';
}
print '----------------------------------------------<br>';
print '	     Salesman : '.ucfirst($up_salesman).'     : '.$up_mobile.' <br>';
print '	     Customer : '.ucfirst($bi_cust0).' <br>';
print '<br><br>';
print '    Signature : ------------------------------- <br>';
print '    Name      : <br>';
print '----------------------------------------------<br>';
print '	          IT WAS A PLEASURE TO SERVE YOU  <br>';
print '	                    THANK YOU<br><br>';
Print 'Print Timestamp : '.$print_time;
?>
<CUT>
</div>