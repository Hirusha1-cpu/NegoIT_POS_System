<?php
    $decimal = getDecimalPlaces(1);
    $currency = getCurrency(1);
?>
<div id="print" style="display:none">
    ############################################
    ################# PAYMENT ##################
    ############################################
    <BOLD>
        <?php print $tm_company; ?><BR>
        <?php print str_replace("<br />","<BR>",$tm_address); ?><BR>Tel: <?php print $tm_tel; ?><BR>
        PAYMENT # [<?php print  str_pad($payment_id, 7, "0", STR_PAD_LEFT); ?>]
        DATE: <?php print $payment_date; ?><BR>
        ----------------------------------------<br><br>
        <?php if($invoice_no!=0){ ?>
        For Invoice :<?php print str_pad($invoice_no, 7, "0", STR_PAD_LEFT); ?><br>
        <?php } ?>
        AMOUNT : <?php print $currency.'. '.number_format($amount, $decimal); ?><br>
        Payment Type : <?php print $payment_type_n; ?><br>
        <?php if($payment_type==2){ ?>
            Bank :<?php print $chque_bank; ?>

            Branch :<?php print $chque_branch; ?>

            Cheque No :<?php print $chque_no; ?>

            Cheque Date :<?php print $chque_date; ?>
        <?php }else if($payment_type==3){ ?>

            Bank :<?php print $bank_trans; ?>
        <?php } ?>
    <?php if(isCustomerTotalOutstandingShowInBill(1)) { ?>
        Total
        Outstanding
        Amount : <?php print $currency.'. '.number_format($credit_balance, $decimal); ?>
    <?php } ?>
        <?php
            if($comment!=''){
                print '<br>----------------------------------------<<br>';
                print $comment.'<br>';
            }
            print '----------------------------------------<br>';
            print '	Salesman : '.ucfirst($salesman).' <br><br>';
            print 'Signature : ----------------------------- <br>';
            print '	Customer : '.ucfirst($cust_name).' <br><br>';
            print 'Signature : ----------------------------- <br>';
            print 'Name      : <br>';
            print '-----------------------------------------<br>';
            print '	        IT WAS A PLEASURE TO SERVE YOU  <br>';
            print '	                  THANK YOU ';
        ?>
        <br>
        <CUT>
</div>