<html>
<body>
<print>
	   INVOICE <br>
		<br>
  Zigo Technology (Pvt) Ltd.<br>
  No.159/19,Green Arcade,<br>
  Main Street, Colombo 11.<br>
  Tel: 0112 351111<br><br>
  INVOICE # [<?php print  str_pad(111, 7, "0", STR_PAD_LEFT); ?> ]<br>
  DATE: <?php print strtoupper (date("F d, Y",time())); ?><br>
 ------------------------------------------<br>
 DESCRIPTION      UNIT  PRICE  QTY <br>
<?php
 for($i=0;$i<sizeof($bill_id);$i++){
 print $bi_desc[$i].'<br>';
 print str_pad($bi_code[$i],15,'-',STR_PAD_RIGHT).'  '.str_pad(number_format($bi_price[$i]),6,'-',STR_PAD_LEFT).' --- '.str_pad(number_format($bi_qty[$i]),5,'-',STR_PAD_LEFT).'  '.str_pad(number_format($bi_qty[$i]*$bi_price[$i]),8,'-',STR_PAD_LEFT).'<br>';
 print '<br>';
}
print '------------------------------------------<br>';
print '           Total Amount '.str_pad(number_format($total),10,'-',STR_PAD_LEFT).'<br>';	
print '          Payment: Cash '.str_pad(number_format($cash_amount),10,'-',STR_PAD_LEFT).'<br>';	
print '         Payment: Chque '.str_pad(number_format($chque_amount),10,'-',STR_PAD_LEFT).'<br>';	
print '      Remaining Balance '.str_pad(number_format($total-$cash_amount-$chque_amount),10,'-',STR_PAD_LEFT).'<br>';	
print '------------------------------------------<br>';
if($chq0_fullNo!='') 
print '  '.$chq0_fullNo.'<br>';
print '------------------------------------------<br>';
print '	    Salesman : '.ucfirst($up_salesman).' <br>';
print '	    Customer : '.ucfirst($bi_cust).' <br>';
print '<br>';
print '    Signature : --------------------------- <br>';
print '------------------------------------------<br>';
print '	IT WAS A PLEASURE TO SERVE YOU  <br>';
print '	           THANK YOU';
?>
</print>

<input type="button" onclick="parent.location='printscheme://www.negoit.info/zigobilling3/index.php?components=billing&action=pos_print_bill&id=1430'" value="Print Call Printer Bluetooth" >
<br>

</body>
</html>