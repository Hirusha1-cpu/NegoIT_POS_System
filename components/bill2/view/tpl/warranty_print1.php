<?php
                include_once  '../../modle/bill2Module.php';
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

<div id="print_top"></div>
<table width="100%" style="font-family:Arial; font-size:11pt">
<tr><td rowspan="2" valign="top">
<strong><?php print $tm_company; ?></strong><br />
<?php print $tm_address; ?><br />
Tel: <?php print $tm_tel; ?>
</td><td></td><td align="right"><span style="font-size:18pt">Warranty Invoice</span><br /></td></tr>
<tr><td></td><td align="right">
Warranty No # [<?php print  str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?> ]<br />
<span  style="font-family:Arial; font-size:11pt">
TIME: <?php print substr($war_claim_date,11,5); ?> &nbsp;&nbsp;&nbsp;&nbsp;DATE: <?php print substr($war_claim_date,0,10); ?><br /><br />
</span>
<?php
	$company_action=$shop_action='';
	if($war_inv_sn!='') $shop_action='Inventory Replace'; else $shop_action=$war_suplier_action;
?>
<table>
<tr><td>Status</td><td>: <?php print $war_status_name; ?></td></tr>
<tr><td>Company Action</td><td>: <?php print $war_suplier_action; ?></td></tr>
<tr><td>Shop Action</td><td>: <?php print $shop_action; ?></td></tr>
</table>
</td></tr>
</table>

	<table align="center" width="100%" border="1" cellspacing="0" border="1" style="font-family:Arial;" >
	<tr><td style="font-size:10pt; color:white; font-weight:bold; background-color:black; -webkit-print-color-adjust: exact;" align="center">Warranty Details</td></tr>
	<tr style="font-size:10pt" height="450px"><td valign="top" style="padding-left:30px"><br /><br />
	<table>
	<?php
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
	print '<tr><td>New Warranty End Data</td><td> : &nbsp;'.$wa_warranty_exp.'</td></tr>';
	}
	?>
	</table>
	</td></tr>
	</table>
	<table align="center" width="100%" border="1" cellspacing="0" >
	<tr style="font-size:8pt;"><td>
	<br />
		<table align="center" width="100%" style="font-family:Arial; font-size:9pt">
			<tr><td width="100px">Claim Salesman</td><td> : <?php print ucfirst($war_taken_by); ?></td><td></td><td width="80px">Signature</td><td width="130px">  ..............................<br /><br /></td></tr>
			<tr><td>Customer</td><td> : <?php print '<a href="../../../../index.php?components=bill2&action=cust_details&id='.$war_cust_id.'&action2=warranty_print&id2='.$_GET['id'].'" target="_parent" title="'.$war_cust_mobile.'" style="text-decoration:none" >'.ucfirst($war_cu_name).'</a>'; ?></td><td></td><td>Signature</td><td>  ..............................</td></tr>
		</table>
		<br />
	</td></tr>
	</table>
