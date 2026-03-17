<div id="print" style="display:none">
################################################
################ RETURN INVOICE ################
################################################
<BOLD><?php print $tm_company; ?><BR>
<?php print str_replace("<br />","<BR>",$tm_address); ?><BR>Tel: <?php print $tm_tel; ?><BR>
RETURN INVOICE # [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?> ]
DATE: <?php print $bill_date; ?><BR>
------------------------------------------------
           ITEM                    QTY      
------------------------------------------------<br>
<?php
 for($i=0;$i<sizeof($bill_id);$i++){
 $print_item=str_replace("&nbsp;"," ",$bill_item[$i]);
 $print_item=str_replace("<br />","<br>",$print_item);
 print str_pad($print_item,35,' ',STR_PAD_RIGHT).'  '.str_pad(number_format($bill_qty[$i]),6,' ',STR_PAD_LEFT).'  <br>';
 print '<br>';
}
print '<br>';
print '----------------------------------------------<br>';
print '	 Salesman : '.ucfirst($bill_salesman).' <br>';
print '	 Customer : '.ucfirst($bill_cust).' <br>';
print '<br><br>';
print 'Signature : ------------------------------- <br>';
print 'Name      : <br>';
print '----------------------------------------------<br>';
print 'Note: By Signing this, Customer confirms that <br>he/she received replacement items for above <br>listed <br><br>';
print '	                   THANK YOU <br>';

?>
<CUT>
</div>