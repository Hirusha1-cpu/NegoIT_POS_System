<?php
    include_once  'components/billing/modle/billingModule.php';
    include_once  'components/orderProcess/modle/orderProcessModule.php';
    include_once  'template/common.php';
	$systemid=inf_systemid(1);
	generateInvoice('itq.drawer_no');
	if($systemid==1){
		getUnpackedReturn($cu_id);
	}

?>
<html>
<body>
<print>
<br />
################################################
################# ORDER ITEMS ##################
################################################<br>
 INVOICE # [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?> ]       DATE: <?php print $bi_date; ?><br>
------------------------------------------------
 DESCRIPTION                  QTY      DRAWER
------------------------------------------------<br>
<?php
 for($i=0;$i<sizeof($bill_id);$i++){
 print str_replace("<br />","<br>",$bi_desc[$i]);
 print str_pad($bi_code[$i],15,' ',STR_PAD_RIGHT).'       '.str_pad(number_format($bi_qty[$i]),10,' ',STR_PAD_LEFT).'   '.str_pad(str_replace("<br />","<br>",$bi_drawer[$i]),5,' ',STR_PAD_LEFT).'<br>';
 print '<br>';
}
if($systemid==1){
 print '----------------------------------------------<br>';
 print '------------------RETURN ITEMS----------------<br>';
 print '----------------------------------------------<br>';
 for($i=0;$i<sizeof($rtn_itm_desc);$i++){
 print $rtn_itm_desc[$i].'<br>';
 print str_pad($rtn_itm_code[$i],15,' ',STR_PAD_RIGHT).'       '.str_pad(number_format($rtn_qty[$i]),10,' ',STR_PAD_LEFT).'  <br>';
 print '<br>';
 }
}
print '----------------------------------------------<br>';
print '	     Processed : '.ucfirst($_GET['user']).' <br>';
print '	     Customer  : '.ucfirst($bi_cust).' <br>';
print '<br><br>';
print '    Signature : ------------------------------- <br>';
print '    Name      : <br>';
print '----------------------------------------------<br>';
print ' ';
?>
</print>

</body>
</html>