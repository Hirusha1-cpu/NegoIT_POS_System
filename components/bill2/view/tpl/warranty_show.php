	<table width="100%" border="0" style="font-size:10pt">
	<tr><td align="center" colspan="2" bgcolor="#DEDEEE" style="color:#4678bb; font-size:16pt"><?php if(!isset($_GET['id'])) print 'New Warranty Claim'; else print 'Claim ID: '.str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></td></tr>
	<?php
	$cust_l1=$company_l1=$inventory_l1=$cust_item=$waranty_item=$inventory_item=$company_item='';
    switch ($war_claim_pos){
	    case 0 :
	    break;
	    case 1 :
	    	$cust_item='<img src="images/claim_item.png" title="Model: '.$war_claim_item.'&#10;SN: '.$war_claim_sn.'" />';
	    break;
	    case 2 :
	    	$waranty_item='<img src="images/claim_item.png" title="Model: '.$war_claim_item.'&#10;SN: '.$war_claim_sn.'" />';
	   		$cust_l1='<img src="images/arrow_right.png" />';
			if($war_inv_pos==0){
				$company_l1='<table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_right_2_2.png" style="padding-top:3px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyStatus(3,0,0,0,2,1)" /></td><td><img src="images/arrow_right_2_1.png" style="padding-top:3px" /></td></tr></table>';
			}else{
				$company_l1='<table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_right_2_2.png" style="padding-top:3px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyStatus(3,0,0,1,3,1)" /></td><td><img src="images/arrow_right_2_1.png" style="padding-top:3px" /></td></tr></table>';
			}
			if($war_suplier_action==3){
				$company_l1='<table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_right_2_2.png" style="padding-top:3px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyStatus(3,0,0,0,2,3)" /></td><td><img src="images/arrow_right_2_1.png" style="padding-top:3px" /></td></tr></table>';
				if($war_cu_name==''){
	   				$cust_l1='';
					$inventory_l1='<div id="div_805"><table><tr><td><img src="images/arrow_up.png" /></td><td align="center"><img src="images/arrow_down_2_2.png" /><br /><br /><input type="button" value="ADD" style="width:60px" class="rotate90" onclick="returnWarrantyInventory2(2)" /><br /><br /><img src="images/arrow_down_2_1.png" /></td></tr></table></div>';
				}else{
					$cust_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyHandover(1,0,0,0,4)" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
				}
			}else{
				if($war_cu_name==''){
	   				$cust_l1='';
	   				$inventory_l1='<img src="images/arrow_up.png" />';
				}else{
					$inventory_l1='<img src="images/arrow_up_2_1.png" /><br /><br /><input type="button" value="Allocate" class="rotate90" onclick="showWarrantyInventory(2,0,0,2,2)" /><br /><br /><img src="images/arrow_up_2_2.png" />';
				}
			}
	    break;
	    case 3 :
	    	$company_item='<img src="images/claim_item.png" title="Model: '.$war_claim_item.'&#10;SN: '.$war_claim_sn.'" />';
	    	if($war_suplier_action==0) $company_item.='<br /><input type="button" value="Repair" style="background-color:#33AA33; color:white" onclick="warrantyRepair()" /><input type="button" value="Replace" style="background-color:#4678bb; color:white" onclick="showWarrantyReplace()" />';
	   		$company_l1='<img src="images/arrow_right.png" />';
			if($war_cu_name==''){
	   			$inventory_l1='<img src="images/arrow_up.png" />';
			}else{
		   		$cust_l1='<img src="images/arrow_right.png" />';
			}
			if($war_repair_pos==0 && $war_replace_pos==0)	$inventory_l1='<img src="images/arrow_up_2_1.png" /><br /><br /><input type="button" value="Allocate" class="rotate90" onclick="showWarrantyInventory(3,0,0,2,2)" /><br /><br /><img src="images/arrow_up_2_2.png" />';
			if($war_repair_pos==3 && $war_replace_pos==0)	$inventory_l1='<img src="images/arrow_up_2_1.png" /><br /><br /><input type="button" value="Allocate" class="rotate90" onclick="showWarrantyInventory(0,3,0,2,2)" /><br /><br /><img src="images/arrow_up_2_2.png" />';
			if($war_repair_pos==0 && $war_replace_pos==3)	$inventory_l1='<img src="images/arrow_up_2_1.png" /><br /><br /><input type="button" value="Allocate" class="rotate90" onclick="showWarrantyInventory(0,0,3,2,2)" /><br /><br /><img src="images/arrow_up_2_2.png" />';
	   		if($war_suplier_action==3) $company_l1.='<br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyStatus(2,0,0,0,2,2)" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
	    break;
	    case 4 :
	    	$inventory_item='<img src="images/claim_item.png" title="Model: '.$war_claim_item.'&#10;SN: '.$war_claim_sn.'" />';
	   		$inventory_l1='<img src="images/arrow_down.png" />';
	    	if($war_cu_name==''){
		   		$company_l1='<img src="images/arrow_right.png" /><br /><img src="images/arrow_left.png" />';
		   		$inventory_l1='<table><tr><td><img src="images/arrow_up.png" /></td><td><img src="images/arrow_down.png" /></td></tr></table>';
	    	}else{
		   		$cust_l1='<img src="images/arrow_right.png" />';
	    	}
	    break;
	 }
	 
    switch ($war_repair_pos){
	    case 0 :
	    break;
	    case 1 :
	    	$cust_item.='<img src="images/repair_item.png" title="Model: '.$war_claim_item.'&#10;SN: '.$war_claim_sn.'" />';
	   		$cust_l1.='<img src="images/arrow_right.png" /><br /><img src="images/arrow_left.png" />';
	   		$company_l1.='<img src="images/arrow_right.png" /><br /><img src="images/arrow_left.png" />';
	    break;
	    case 2 :
	    	$waranty_item.='<img src="images/repair_item.png" title="Model: '.$war_claim_item.'&#10;SN: '.$war_claim_sn.'" />';
	   		$company_l1='<img src="images/arrow_right.png" /><br /><img src="images/arrow_left.png" />';
			if($war_cu_name==''){
		   		$inventory_l1='<div id="div_805"><table><tr><td><img src="images/arrow_up.png" /></td><td align="center"><img src="images/arrow_down_2_2.png" /><br /><br /><input type="button" value="ADD" style="width:60px" class="rotate90" onclick="returnWarrantyInventory2(1)" /><br /><br /><img src="images/arrow_down_2_1.png" /></td></tr></table></div>';
			}else{
				if(($war_sup_pay!=0)&&($war_cust_pay==0)){
					$cust_item='<input type="button" value="Collect Payment" style="height:40px;" onclick="showWACustPay()" />';
			   		$cust_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="alert('."'Please Collect the Payment'".')" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
				}else{
			   		$cust_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyHandover(0,1,0,0,4)" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
				}
			}
	    break;
	    case 3 :
	    	$company_item.='<img src="images/repair_item.png" title="Model: '.$war_claim_item.'&#10;SN: '.$war_claim_sn.'" />';
			if($war_cu_name==''){
	   			$inventory_l1.='<img src="images/arrow_up.png" />';
			}else{
	   			$cust_l1.='<img src="images/arrow_right.png" />';
				if($war_claim_pos==0 && $war_inv_pos==0)
				$inventory_l1='<img src="images/arrow_up_2_1.png" /><br /><br /><input type="button" value="Allocate" class="rotate90" onclick="showWarrantyInventory(0,3,0,2,2)" /><br /><br /><img src="images/arrow_up_2_2.png" />';
			}
	   		if($war_inv_pos==0) $company_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyStatus(0,2,0,0,2,2)" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
	   		if($war_inv_pos==1) $company_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyStatus(0,2,0,1,3,2)" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
	    break;
	    case 4 :
	    	$inventory_item.='<img src="images/repair_item.png" title="Model: '.$war_claim_item.'&#10;SN: '.$war_claim_sn.'" />';
	   		$company_l1='<img src="images/arrow_right.png" /><br /><img src="images/arrow_left.png" />';
	   		$inventory_l1='<table><tr><td><img src="images/arrow_up.png" /></td><td><img src="images/arrow_down.png" /></td></tr></table>';
			if($war_cu_name!=''){
				$cust_item.='<img src="images/inv_item.png" title="Model: '.$war_inv_item.'&#10;SN: '.$war_inv_sn.'" />';
	   			$cust_l1='<img src="images/arrow_right.png" /><br /><img src="images/arrow_left.png" />';
			}
	    break;
	 }
	 
    switch ($war_replace_pos){
	    case 0 :
	    break;
	    case 1 :
	    	$cust_item.='<img src="images/replace_item.png" title="Model: '.$war_sup_item.'&#10;SN: '.$war_sup_sn.'" />';
	   		$cust_l1='<img src="images/arrow_right.png" /><br /><img src="images/arrow_left.png" />';
	   		$company_l1='<img src="images/arrow_right.png" /><br /><img src="images/arrow_left.png" />';
	    	if($war_inv_pos==0) $inventory_l1='';
	    break;
	    case 2 :
	    	$waranty_item.=' <img src="images/replace_item.png" title="Model: '.$war_sup_item.'&#10;SN: '.$war_sup_sn.'" />';
	   		if(($war_sup_pay==0 && $war_inv_pay==0) || ($war_cust_pay==1)){ 
			   	if($war_cu_name==''){
			   		$inventory_l1='<table><tr><td><img src="images/arrow_up.png" /></td><td align="center"><img src="images/arrow_down_2_2.png" /><br /><br /><input type="button" value="ADD" style="width:60px" class="rotate90" onclick="returnWarrantyInventory(3)" /><br /><br /><img src="images/arrow_down_2_1.png" /></td></tr></table>';
			   	}else{
	   				$cust_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyHandover(3,0,1,0,4)" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
	   			}
	   		}else{
			   	if($war_cu_name==''){
			   		$inventory_l1='<table><tr><td><img src="images/arrow_up.png" /></td><td align="center"><img src="images/arrow_down_2_2.png" /><br /><br /><input type="button" value="ADD" style="width:60px" class="rotate90" onclick="returnWarrantyInventory(3)" /><br /><br /><img src="images/arrow_down_2_1.png" /></td></tr></table>';
			   	}else{
	   				$cust_item='<input type="button" value="Collect Payment" style="height:40px;" onclick="collectWarrantypay()" />';
			   		$cust_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="alert('."'Please Collect the Payment'".')" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
			   	}
	   		}
	   		$company_l1='<img src="images/arrow_right.png" /><br /><img src="images/arrow_left.png" />';
	    //	if($war_inv_pos==1) $inventory_l1='<table><tr><td><img src="images/arrow_up.png" /></td><td align="center"><img src="images/arrow_down_2_2.png" /><br /><br /><input type="button" value="ADD" style="width:60px" class="rotate90" onclick="returnWarrantyInventory(2)" /><br /><br /><img src="images/arrow_down_2_1.png" /></td></tr></table>';
	    break;
	    case 3 :
	   		$cust_l1='<img src="images/arrow_right.png" />';
	    	$company_item.=' <img src="images/replace_item.png" title="Model: '.$war_sup_item.'&#10;SN: '.$war_sup_sn.'" />';
	    	if($war_sup_sn==''){
	    		if($war_inv_pos!=2) $company_item.='<br /><input type="button" value="Replace" style="background-color:#4678bb; color:white" onclick="showWarrantyReplace(2)" />';
	   			$company_l1='<img src="images/arrow_right.png" />';
				$inventory_l1='<img src="images/arrow_up_2_1.png" /><br /><br /><input type="button" value="Allocate" class="rotate90" onclick="showWarrantyInventory(3,0,3,2,2)" /><br /><br /><img src="images/arrow_up_2_2.png" />';
	    	}else{
	   			if($war_inv_pos==0){
	   				if($war_cu_name=='') $cust_l1=$war_cu_name;
	   				$company_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyStatus(3,0,2,0,2,2)" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
	   			}
	   			if($war_inv_pos==1) $company_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyStatus(3,0,2,1,3,2)" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
	    	}
	    break;
	    case 4 :
	    	if($war_cu_name!=''){
	    		$cust_item.='<img src="images/inv_item.png" title="Model: '.$war_inv_item.'&#10;SN: '.$war_inv_sn.'" />';
	   			$cust_l1='<img src="images/arrow_right.png" /><br /><img src="images/arrow_left.png" />';
	    	}
	    	$inventory_item.='<img src="images/replace_item.png" title="Model: '.$war_sup_item.'&#10;SN: '.$war_sup_sn.'" />';
	   		$company_l1='<img src="images/arrow_right.png" /><br /><img src="images/arrow_left.png" />';
	   		$inventory_l1='<table><tr><td><img src="images/arrow_up.png" /></td><td><img src="images/arrow_down.png" /></td></tr></table>';
	    break;
	 }
	 
    switch ($war_inv_pos){
	    case 0 :
	    break;
	    case 1 :
	    	$cust_item='<img src="images/inv_item.png" title="Model: '.$war_inv_item.'&#10;SN: '.$war_inv_sn.'" />';
	   		$cust_l1='<img src="images/arrow_right.png" /><br /><img src="images/arrow_left.png" />';
	   		if($war_repair_pos==2) $inventory_l1='<table><tr><td><img src="images/arrow_up.png" /></td><td align="center"><img src="images/arrow_down_2_2.png" /><br /><br /><input type="button" value="ADD" style="width:60px" class="rotate90" onclick="returnWarrantyInventory(1)" /><br /><br /><img src="images/arrow_down_2_1.png" /></td></tr></table>';
	   		else if($war_replace_pos==2) $inventory_l1='<table><tr><td><img src="images/arrow_up.png" /></td><td align="center"><img src="images/arrow_down_2_2.png" /><br /><br /><input type="button" value="ADD" style="width:60px" class="rotate90" onclick="returnWarrantyInventory(2)" /><br /><br /><img src="images/arrow_down_2_1.png" /></td></tr></table>';
	   		else if(($war_repair_pos==4)||($war_replace_pos==4)) $inventory_l1='<table><tr><td><img src="images/arrow_up.png" /></td><td><img src="images/arrow_down.png" /></td></tr></table>';
	   		else $inventory_l1='<img src="images/arrow_up.png" />';
		break;
	    case 2 :
	    	$waranty_item.=' <img src="images/inv_item.png" title="Model: '.$war_inv_item.'&#10;SN: '.$war_inv_sn.'" />';
	   		if(($war_sup_pay==0 && $war_inv_sn=='') || ($war_cust_pay==1)){ 
		   		if($war_claim_pos==2) 
		   			$cust_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyHandover(2,0,0,1,3)" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
		   		if($war_claim_pos==3 && $war_repair_pos==0 && $war_replace_pos==0)
		   			$cust_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyHandover(3,0,0,1,3)" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
		   		if($war_claim_pos==0 && $war_repair_pos==3 && $war_replace_pos==0)
		   			$cust_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyHandover(0,3,0,1,3)" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
		   		if($war_claim_pos==3 && $war_repair_pos==0 && $war_replace_pos==3)
		   			$cust_l1='<img src="images/arrow_right.png" /><br /><table cellspacing="0" border="0" cellpadding="0"><tr><td><img src="images/arrow_left_2_1.png" style="padding-top:2px" /></td><td><input type="button" value="Send" style="width:50px" onclick="setWarrantyHandover(3,0,3,1,3)" /></td><td><img src="images/arrow_left_2_2.png" style="padding-top:2px" /></td></tr></table>';
	   		}else{
	   			$cust_item='<input type="button" value="Collect Payment" style="height:40px;" onclick="collectWarrantypay()" />';
	   			$cust_l1='<img src="images/arrow_right.png" />';
	   		}
	   		$inventory_l1='<img src="images/arrow_up.png" />';
	   		if(($war_repair_pos==3)||($war_replace_pos==3)) $company_l1='<img src="images/arrow_right.png" />';
	   		if($war_claim_pos==2) $company_l1='';
	    break;
	    case 3 :
	    break;
	    case 4 :
	    break;
	 }
	 
	    
	    
	?>
	<tr><td align="center" colspan="2" valign="top">
	<br />
		<table align="center">
		<tr><td width="115px" height="100px" bgcolor="#DDFFDD" align="center" background="images/customer_1.png">
			<input type="hidden" id="id" value="<?php print $_GET['id']; ?>" />
			<?php 
			if($war_cu_name!=''){
			print '<table>';
			print '<tr><td align="center">'.$cust_item.'</td></tr>';
			print '<tr><td style="color:maroon; font-size:8pt;">'.$war_claim_item.'</td></tr>';
			print '<tr><td style="color:white; font-size:8pt;" bgcolor="#AAAAAA" align="center">'.$war_claim_sn.'</td></tr>';
			print '</table>';
			}
			?>
		</td><td width="100px">
			<?php print $cust_l1; ?>
		</td><td width="115px" height="100px" bgcolor="#DDDDFF" align="center" background="images/warranty_1.png">
			<?php print $waranty_item; ?>
		</td><td width="100px" align="center">
			<?php print $company_l1; ?>
		</td><td width="115px" height="100px" bgcolor="#EEEEAA" align="center" background="images/company_1.png" style="background-repeat:no-repeat">
			<?php print $company_item; ?>
		</td></tr>
		<tr><td rowspan="2" valign="top">
			<?php 
			if($war_cu_name!=''){
				print '<table style="font-size:8pt; color:gray">';
				print '<tr><td>Inv</td><td>: <a href="index.php?components=bill2&action=finish_bill&id='.$war_bminv.'" style="text-decoration:none">'.str_pad($war_bminv, 7, "0", STR_PAD_LEFT).'</a></td></tr>';
				print '<tr><td>Date</td><td>: '.$war_claim_date.'</td></tr>';
				print '<tr><td>By</td><td>: '.ucfirst($war_taken_by).'</td></tr>';
				if($war_status==4){
					print '<tr><td colspan="2"><br /></td></tr>';
					print '<tr><td colspan="2" align="center" bgcolor="#DDDDDD">Handover Details</td></tr>';
					print '<tr><td>Date</td><td>: '.$war_ho_date.'</td></tr>';
					print '<tr><td>By</td><td>: '.ucfirst($war_ho_by).'</td></tr>';
					print '<tr><td colspan="2">'.$war_ho_item.'</td></tr>';
					print '<tr><td colspan="2">'.$war_ho_sn.'</td></tr>';
				} 
				print '</table>';
			} 
			?>
			
		</td><td></td><td align="center" height="100px">
			<?php print $inventory_l1; ?>
		</td><td></td><td valign="top">
			<table style="font-size:8pt; color:gray">
			<?php if($war_sent_date!=''){
			print '<tr><td title="Sent to Supplier on this Date">Sent</td><td>: '.$war_sent_date.'</td></tr>';
			} 
			if($war_receive_date!=''){
			print '<tr><td title="Reveived from Supplier on this Date">Received</td><td>: '.$war_receive_date.'</td></tr>';
			} ?>
			</table>
		</td></tr>
		<tr><td>
			<?php 
			if($war_cu_name==''){
				print '<table style="font-size:8pt; color:gray">';
				print '<tr><td>Date</td><td>: '.$war_claim_date.'</td></tr>';
				print '<tr><td>By</td><td>: '.ucfirst($war_taken_by).'</td></tr>';
				if($war_status==4){
					print '<tr><td colspan="2"><br /></td></tr>';
					print '<tr><td colspan="2" align="center" bgcolor="#DDDDDD">Handover Details</td></tr>';
					print '<tr><td>Date</td><td>: '.$war_ho_date.'</td></tr>';
					print '<tr><td>By</td><td>: '.ucfirst($war_ho_by).'</td></tr>';
					print '<tr><td colspan="2">'.$war_ho_item.'</td></tr>';
					print '<tr><td colspan="2">'.$war_ho_sn.'</td></tr>';
				} 
				print '</table>';
			}
			?>
			
		</td><td width="115px" height="100px" bgcolor="#FFDDFF" align="center" background="images/shop_1.png" style="background-repeat:no-repeat" >
		<?php print $inventory_item; ?>
			<?php 
			if($war_cu_name==''){
			print '<table>';
			print '<tr><td align="center" >'.$cust_item.'</td></tr>';
			print '<tr><td style="color:maroon; font-size:8pt; background-color:#DDDDDD">'.$war_claim_item.'</td></tr>';
			print '<tr><td style="color:white; font-size:8pt;" bgcolor="#AAAAAA" align="center">'.$war_claim_sn.'</td></tr>';
			print '</table>';
			}
			?> 
		</td><td></td><td align="center"><?php if($war_suplier_action==2 || $war_suplier_action==3) print '<span style="font-size:12pt; color:red; font-weight:bold">No Warranty</span>'; ?></td></tr>
		</table>
	</td></tr>
	<tr><td style="padding-left:20px"><hr />Issue : <?php print $war_issue; ?></td></tr>
	</table>