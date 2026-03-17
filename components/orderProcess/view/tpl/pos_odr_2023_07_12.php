<?php
	//generateInvoice('itq.drawer_no');
//	if($systemid==1){
	//	getUnpackedReturn($cu_id);
//	}

?>
<div id="print_odr" style="display:none">
<barcode128><?php print str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?><br>
################################################
################# ORDER ITEMS ##################
################################################<br>
 INVOICE # [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?> ]       DATE: <?php print $bi_date; ?><br>
------------------------------------------------
 DESCRIPTION                  QTY      DRAWER
------------------------------------------------<br>
<?php
 for($i=0;$i<sizeof($bill_id);$i++){
 print str_replace("<br />","<BR>",$bi_desc[$i]);
 print str_pad($bi_code[$i],15,' ',STR_PAD_RIGHT).'       '.str_pad(number_format($bi_qty[$i]),10,' ',STR_PAD_LEFT).'   '.str_pad(str_replace("<br />","<BR>",$bi_drawer[$i]),5,' ',STR_PAD_LEFT).'<BR>';
 print '<BR>';
}
if($systemid==1){
 print '----------------------------------------------<br>';
 print '------------------RETURN ITEMS----------------<br>';
 print '----------------------------------------------<br>';
 for($i=0;$i<sizeof($rtn_itm_desc);$i++){
 print $rtn_itm_desc[$i].'<br>';
 print str_pad($rtn_itm_code[$i],15,' ',STR_PAD_RIGHT).'       '.str_pad(number_format($rtn_qty[$i]),10,' ',STR_PAD_LEFT).'   '.str_pad(str_replace("<br />","<BR>",$rtn_drawer[$i]),5,' ',STR_PAD_LEFT).'<BR>';
 print '<br>';
 }
}
print '----------------------------------------------<br>';
print '	     Processed : '.ucfirst($user_name).' <br>';
print '	     Customer  : '.ucfirst($bi_cust).' <br>';
print '<br><br>';
print '    Signature : ------------------------------- <br>';
print '    Name      : <br>';
print '----------------------------------------------<br>';
print ' ';
?>
<br>
<CUT>
</div>