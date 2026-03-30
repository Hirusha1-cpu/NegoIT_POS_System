<?php
    include_once  'components/billing/modle/billingModule.php';
    include_once  'template/common.php';
	generatePayment();
?>
<html>
<body>
<print>
################################################
################### PAYMENT ####################
################################################<br>
  <?php print $tm_company; ?><br>  <?php print str_replace("<br />","
  ",$tm_address); ?><br>  Tel: <?php print $tm_tel; ?><br>
  PAYMENT # [<?php print  str_pad($payment_id, 7, "0", STR_PAD_LEFT); ?> ]
  DATE: <?php print $payment_date; ?><br>
------------------------------------------------<br><br>
<?php if($invoice_no!=0){ ?>
     For Invoice  :<?php print str_pad($invoice_no, 7, "0", STR_PAD_LEFT); ?>
<?php } ?>
      AMOUNT      : <?php print ' '.number_format($amount); ?><br>
     Payment Type : <?php print $payment_type_n; ?><br>
<?php if($payment_type==2){ ?>
     
     Bank         :<?php print $chque_bank; ?>
     
     Branch       :<?php print $chque_branch; ?>
     
     Chque No     :<?php print $chque_no; ?>
     
     Chque Date   :<?php print $chque_date; ?>
<?php }else if($payment_type==3){ ?>

     Bank         :<?php print $bank_trans; ?>
<?php } ?>

<?php
if($comment!=''){
print '<br>----------------------------------------------<br>';
print $comment.'<br>';
}
print '----------------------------------------------<br>';
print '	     Salesman : '.ucfirst($salesman).' <br><br>';
print '     Signature : ----------------------------- <br>';
print '	     Customer : '.ucfirst($cust_name).' <br><br>';
print '     Signature : ----------------------------- <br>';
print '     Name      : <br>';
print '----------------------------------------------<br>';
print '	          IT WAS A PLEASURE TO SERVE YOU  <br>';
print '	                    THANK YOU ';
?>
<br><br>
</print>

</body>
</html>