<?php
                include_once  '../../modle/billingModule.php';
                include_once  '../../../../template/common.php';
                getWarrantyPrint();
				generalPrint();
?>
<style type="text/css">
@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
</style>


<!-- <table background="/images/inv_4_template.jpg" border="0"><tr><td> -->
<div id="print_top"></div>
<table height="96px" style="font-family:Arial">
<tr height="20px"><td colspan="5"></td></tr>
<tr><td width="0px"></td><td><div class="no-print"><img src="/images/inv_logo4.png" width="105px" /></div></td><td width="7px"></td><td width="2px" bgcolor="gray"></td><td align="center" width="220px"><div class="no-print"><?php print '<span style="font-size:15pt">'.$tm_company.'</span><br /><span style="font-size:10pt">'.$tm_address.'</span><br /><span style="font-size:10pt">Telephone: '.$tm_tel.'</span>'; ?></div></td></tr>
<tr height="4px"><td colspan="5"></td></tr>
</table>
<table><tr><td height="27px"></td></tr></table>
<table border="0" cellspacing="0" cellpadding="0" style="font-size:10pt; font-family:Verdana;" bgcolor="#ADCDCC"><tr><td width="0px" bgcolor="white"></td><td width="143px"></td><td style="font-size:16pt"><div class="no-print"><strong>TYPE</strong></div></td><td width="100px"></td><td>WARRANTY</td><td width="40px"></td></tr></table>
<table border="0" cellspacing="0" cellpadding="0" style="font-size:9pt; font-family:Verdana">
<tr height="16px"><td width="0px"></td><td width="60px" style="font-size:7pt"><div class="no-print"><strong>Inv No </strong></div></td><td><div class="no-print">:</div></td><td width="210px"><?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></td><td style="font-size:7pt" width="50px"><div class="no-print"><strong>Terminal</strong></div></td><td><div class="no-print">:</div></td><td><?php print $key_dev_name; ?></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt"><div class="no-print"><strong>Date </strong></div></td><td><div class="no-print">:</div></td><td><?php print substr($war_claim_date,0,10); ?></td><td style="font-size:7pt" ><div class="no-print"><strong></strong></div></td><td></td><td></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt"><div class="no-print"><strong>Cashier </strong></div></td><td><div class="no-print">:</div></td><td></td><td style="font-size:7pt" width="50px"><div class="no-print"><strong>Salesman</strong></div></td><td><div class="no-print">:</div></td><td><?php print ucfirst($war_taken_by); ?></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt"><div class="no-print"><strong>Location </strong></div></td><td><div class="no-print">:</div></td><td><?php print $tm_shop; ?></td><td style="font-size:7pt"><div class="no-print"><strong>Technicien</strong></div></td><td><div class="no-print">:</div></td><td></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt"><div class="no-print"><strong>Customer </strong></div></td><td><div class="no-print">:</div></td><td><?php print '<a href="../../../../index.php?components=billing&action=cust_details&id='.$war_cust_id.'&action2=warranty_print&id2='.$_GET['id'].'" target="_parent" title="'.$war_cust_mobile.'" >'.substr(ucfirst($war_cu_name),0,20).'</a>'; ?></td><td style="font-size:7pt" ><div class="no-print"><strong>Print Date</strong></div></td><td><div class="no-print">:</div></td><td style="font-size:8pt"><?php print substr($print_time,0,10); ?></td></tr>
<tr height="16px"><td width="0px"></td><td style="font-size:7pt">&nbsp;&nbsp;<strong>Print </strong></td><td>:</td><td></td><td style="font-size:7pt" ><div class="no-print"><strong>Print Time</strong></div></td><td><div class="no-print">:</div></td><td style="font-size:8pt"><?php print substr($print_time,11,8); ?></td></tr>
</table>
<table><tr><td height="4px"></td></tr></table>
<table border="0" cellspacing="0" cellpadding="0">
<tr><td width="0px"></td><td width="400px">
	<table border="0" style="font-size:6pt; font-family:Verdana" height="196px" width="100%">
	<tr height="11px" style="background-color:navy; color:white;"><td align="center"><div class="no-print">Warranty Details</div></td></tr>
	<tr><td valign="top">
		<table width="100%" style="font-size:10pt">
		<?php
		$company_action=$shop_action='';
		if($war_inv_sn!='') $shop_action='Inventory Replace'; else $shop_action=$war_suplier_action;
		print '<tr><td>Status</td><td> : &nbsp;'.$war_status_name.'</td></tr>';
		print '<tr><td>Company Action</td><td> : &nbsp;'.$war_suplier_action.'</td></tr>';
		print '<tr><td>Shop Action</td><td> : &nbsp;'.$shop_action.'</td></tr>';
		print '<tr><td></td><td>  &nbsp;</td></tr>';
		print '<tr><td>Claim Item</td><td> : &nbsp;'.$war_claim_item.'</td></tr>';
		print '<tr><td>Claim SN</td><td> : &nbsp;'.$war_claim_sn.'</td></tr>';
		
		if($war_ho_item!=''){
		print '<tr><td>Handover Item</td><td> : &nbsp;'.$war_ho_item.'</td></tr>';
		print '<tr><td>Handover SN</td><td> : &nbsp;'.$war_ho_sn.'</td></tr>';
		}
		print '<tr><td></td><td>  &nbsp;</td></tr>';
		if($war_sup_pay!=0){
		print '<tr><td><div class="no-print">Supplier Pay</div></td><td><div class="no-print"> : &nbsp;'.number_format($war_sup_pay).'</div></td></tr>';
		}
		if($war_inv_pay!=0){
		print '<tr><td><div class="no-print">Inventory Pay</div></td><td><div class="no-print"> : &nbsp;'.number_format($war_inv_pay).'</div></td></tr>';
		}
		if($war_cust_pay==1){
		if($war_inv_sn!='') $extra_pay=$war_inv_pay; else $extra_pay=$cust_pay_amount;
		print '<tr><td>Extra Pay</td><td> : &nbsp;'.number_format($extra_pay).'</td></tr>';
		}
		if($war_new_warranty>0){
		print '<tr><td>New Warranty<br />End Data</td><td> : &nbsp;'.$wa_warranty_exp.'</td></tr>';
		}
		?>
		</table>
	</td></tr>
	</table>
<table><tr><td height="28px"></td></tr></table>
	<table border="0" style="font-size:6pt; font-family:Verdana">
	<tr><td width="10px"></td><td><div class="no-print"><strong>Customer Signature</strong></div></td><td width="140px"></td><td><div class="no-print"><strong>Checked By</strong></div></td></tr>
	</table>
<table><tr><td height="10px"></td></tr></table>
</td><td width="30px"></td></tr></table>
<!-- </td></tr></table> -->
