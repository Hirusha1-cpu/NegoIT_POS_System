<?php
                include_once  '../../modle/billingModule.php';
                include_once  '../../../../template/common.php';
                getWarrantyPrint();
				generalPrint();
                $paper_size=paper_size(2);
                if($paper_size=='A4'){
                	$page_height=820;
                }
                if($paper_size=='A5'){
                	$page_height=520;
                	$chequedate='';
                }
                	$bill_title1='WARRANTY';
                	$title1_size=18;
                	$bill_title2='Invoice';
                	$sub_title='INVOICE NO';
                	$advance='';

?>

<div style="background-image:url('/images/inv_7_template.png'); background-repeat:no-repeat; background-position: left 25px top 12px;" >
<div id="print_top"></div>
<table height="96px" style="font-family:Arial">
<tr height="14px"><td colspan="3"></td></tr>
<tr height="92px"><td width="170px"></td>
<td width="165px" valign="top">
	<span style="font-size:<?php print $title1_size; ?>pt"><?php print $bill_title1; ?></span><br />
	<span style="font-size:12pt"><?php print $bill_title2; ?></span><br />
	<br />
	<span style="font-size:10pt"><?php print $sub_title; ?>:[<?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?>]</span>
</td><td rowspan="2" width="158px" valign="top">
	<table style="font-size:9pt; font-weight:bold" cellspacing="0">
	<tr><td colspan="2" height="8px"></td></tr>
	<tr><td>INV DATE</td><td>: <?php print substr($war_claim_date,0,10); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>PRINT DATE</td><td>: <?php print substr($print_time,0,10); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>PRINT TIME</td><td>: <?php print substr($print_time,11,8); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>LOCATION</td><td style="font-size:8pt">: <?php print $tm_shop; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>TERMINAL</td><td style="font-size:8pt">: <?php print $key_dev_name; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>PRINT COPY</td><td>: </td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td>SALESMAN</td><td>: <?php print ucfirst($war_taken_by); ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	</table>
</td></tr>
<tr><td height="55px"></td><td></td></tr>

<tr height="4px"><td colspan="5"></td></tr>
</table>
<table><tr><td height="15px"></td></tr></table>

<table border="0" cellspacing="0" cellpadding="0" style="padding-left:10px">
<tr><td width="33px"></td><td width="444px" >

	<table border="0" style="font-size:6pt; font-family:Verdana" height="365px" width="100%">
	<tr><td valign="top">
		<table width="100%" style="font-size:10pt">
		<?php
		$company_action=$shop_action='';
		if($war_inv_sn!='') $shop_action='Inventory Replace'; else $shop_action=$war_suplier_action;
		print '<tr><td><strong>Status</strong></td><td> : &nbsp;'.$war_status_name.'</td></tr>';
		print '<tr><td><strong>Company Action</strong></td><td> : &nbsp;'.$war_suplier_action.'</td></tr>';
		print '<tr><td><strong>Shop Action</strong></td><td> : &nbsp;'.$shop_action.'</td></tr>';
		print '<tr><td colspan="2"><table width="100%"><tr><td><hr /></td></tr></table></td></tr>';
		print '<tr><td>Claim Item</td><td> : &nbsp;'.$war_claim_item.'</td></tr>';
		print '<tr><td>Claim SN</td><td> : &nbsp;'.$war_claim_sn.'</td></tr>';
		
		if($war_ho_item!=''){
		print '<tr><td>Handover Item</td><td> : &nbsp;'.$war_ho_item.'</td></tr>';
		print '<tr><td>Handover SN</td><td> : &nbsp;'.$war_ho_sn.'</td></tr>';
		}
		print '<tr><td colspan="2"><table width="100%"><tr><td><hr /></td></tr></table></td></tr>';
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
<table><tr><td height="30px"></td></tr></table>
<table width="100%"><tr><td><hr /></td></tr></table>
	<table border="0" style="font-size:9pt; font-family:Verdana">
	<tr><td width="10px"></td><td><strong>CUSTOMER:</strong> <?php print substr(ucfirst($war_cu_name),0,20).'<br />'.$war_cust_mobile; ?></td><td width="80px"></td><td align="right">
		<table style="font-size:9pt; font-family:Verdana">
			<tr><td><strong>Signature:</strong></td><td><br /><br /><br />..........................</td></tr>
		</table>
	</td></tr>
	</table>
</td><td width="10px"></td></tr></table>
<table><tr><td height="60px"></td></tr></table>

</div>
