<?php

function warrantyStatus($st_id){
	$jsonArray=array();
	switch ($st_id){
		case 0:
			$jsonArray["st_name"]='Deleted';
			$jsonArray["st_color"]='red';
		break;
		case 1:
			$jsonArray["st_name"]='Initiated';
			$jsonArray["st_color"]='orange';
		break;
		case 2:
			$jsonArray["st_name"]='Ongoin';
			$jsonArray["st_color"]='yellow';
		break;
		case 3:
			$jsonArray["st_name"]='Handed Over';
			$jsonArray["st_color"]='#00DDEE';
		break;
		case 4:
			$jsonArray["st_name"]='Completed';
			$jsonArray["st_color"]='white';
		break;
	}
	$myJSON = json_encode($jsonArray);
	return $myJSON;
}

function warrantySuAction($action){
	switch ($action){
		case 0:
			$action_out='Pending';
		break;
		case 1:
			$action_out='Repair';
		break;
		case 2:
			$action_out='Paid Repair';
		break;
		case 3:
			$action_out='Repair Reject';
		break;
		case 4:
			$action_out='Replace';
		break;
	}
	return $action_out;
}

function getDistrict(){
	global $district_id,$district_name,$current_district,$static_district;
	$store=$_COOKIE['store'];
	include('config.php');
	$query="SELECT id,`name` FROM district ORDER BY name";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$district_id[]=$row[0];
		$district_name[]=$row[1];
	}
	$query="SELECT `district` FROM stores WHERE id=$store";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$static_district=$row[0];

	if(isset($_COOKIE['district']))
		$current_district=$_COOKIE['district'];
	else
		$current_district='';
}

function setDistrict(){
	$district=$_GET['id'];
	setcookie("district",$district, time()+3600*10);
}

function getSalesman2(){
	global $sm_id,$sm_name;
	include('config.php');
	$query="SELECT id,username FROM userprofile WHERE `status`=0 ORDER BY username";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$sm_id[]=$row[0];
		$sm_name[]=$row[1];
	}
}

function getSalesman3($sub_system){
	global $sm_id,$sm_name;
	include('config.php');
		$query="SELECT DISTINCT up.id,up.username FROM userprofile up, permission pe, `function` fn WHERE up.id=pe.`user` AND pe.`function`=fn.id AND up.`sub_system`='$sub_system' AND up.`status`='0' AND fn.`status`=1 AND (fn.`name`='Bill2' OR fn.`name`='Billing') ORDER BY up.username";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$sm_id[]=$row[0];
			$sm_name[]=$row[1];
	}
}

// updated by nirmal 11_01_2022
function getTechnicient(){
	global $tech_id,$tech_name;
	include('config.php');
	$tech_id=$tech_name=array();
	// $query="SELECT id,username FROM userprofile WHERE `status`='0' AND id NOT IN (SELECT DISTINCT `user` FROM permission WHERE `function` IN (1,2,3,6,7,8,9,10,12,13,14))AND id IN (SELECT DISTINCT `user` FROM permission WHERE `function` IN (4))";
	$query="SELECT id,username FROM userprofile WHERE `status`='0' AND id NOT IN (SELECT DISTINCT `user` FROM permission WHERE `function` IN (1,3,4,6,7,8,9,10,12,13,14))AND id IN (SELECT DISTINCT `user` FROM permission WHERE `function` IN (5))";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$tech_id[]=$row[0];
		$tech_name[]=$row[1];
	}
}

function setHPstatus($invoice_no){
	include('config.php');
	$query="SELECT his.payment_amount,his.payment_count FROM hp_inv_schedule his WHERE his.invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$his_amount=is_null($row) ? 0 : $row[0];
	$his_count=is_null($row) ? 0 : $row[1];

	$query="SELECT SUM(py.amount) FROM hp_payments hp, payment py WHERE hp.payment_no=py.id AND py.chque_return='0' AND py.`status`='0' AND py.invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$instalment_total=$row[0];

	if(($his_amount * $his_count) == $instalment_total) $new_st='0'; else $new_st='1';

	$query="UPDATE `hp_inv_schedule` SET `status`='$new_st' WHERE `invoice_no`='$invoice_no'";
	$result=mysqli_query($conn,$query);
	if($result) return true; else return false;
}

function listSN($sub_system){
	global $data_list,$fn;
	$data_list=array();
	if($_POST['keyword']){
		$keyword=str_replace("'","",$_POST['keyword']);
		$cashback=$_GET['cashback'];
		$item_id=$_GET['item_id'];
		$cust_id=$_GET['cust_id'];
		$bm_no=$_GET['bm_no'];

		include('config.php');
		if($cashback==0){
			$fn='selectSN';
			$query="SELECT mapped_inventory FROM bill_main_tmp WHERE bm_no='$bm_no'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$mapped_inventory=$row[0];

			$query="SELECT itu.sn FROM inventory_unic_item itu, inventory_qty itq WHERE itu.itq_id=itq.id AND itq.location='$mapped_inventory' AND itq.item='$item_id' AND itu.`status`='0' AND itu.sn LIKE '%$keyword%' LIMIT 20";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_array($result)){
				$data_list[]=$row[0];
			}
		}
		if($cashback==1){
			$fn='selectSN2';
			$query="SELECT itu.sn FROM bill_main bm, bill bi, inventory_unic_item itu WHERE bm.invoice_no=bi.invoice_no AND itu.bill_id=bi.id AND itu.invoice_no=bm.invoice_no AND bm.`status`!=0 AND bm.`lock`=1 AND itu.`status`=1 AND bi.item='$item_id' AND bm.`cust`='$cust_id' AND itu.sn LIKE '%$keyword%' LIMIT 20";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_array($result)){
				$data_list[]=$row[0];
			}
		}
	}
}

function getUnicCashbackData($sub_system){
	$sn=$_POST['sn'];
	$jasonArray=array();
	include('config.php');
	$query="SELECT itu.id,bi.unit_price FROM bill bi, inventory_unic_item itu WHERE itu.bill_id=bi.id AND itu.`status`=1 AND itu.sn='$sn'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$itu_id=$row[0];
	$unit_price=$row[1];
	$jasonArray["itu_id"]=$itu_id;
	$jasonArray["unit_price"]=$unit_price;
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

// update by nirmal 02_08_2023 round and add descimal number to wholesale and retail dropdwon
function moreItem($sub_system,$systemid){
	if(isset($_GET['case'])){
		$case=$_GET['case'];
		$val=str_replace("'","",$_GET['val']);
		$bm_no=$_GET['bm_no'];
		$retail_shop=$_COOKIE['retail'];
		if(isset($_COOKIE['district']))	$district=$_COOKIE['district']; else $district=1;
		if($case=='code') $qry="`code`='$val'";
		if($case=='desc') $qry="`description`='$val'";
		$itm_qty=$decimal=0;
		$increment=$itn_qty='';
		#if($systemid==13) $decimal=2;
		#if($systemid==14) $decimal=2;
		if($retail_shop==1) $retail_default='selected="selected"'; else $retail_default='';
		$sp_item=$sp_increment=$sp_category=$sp_catincrement=array();
		$last_price='NA';

		include('config.php');
		$query="SELECT `value` FROM settings WHERE setting='decimal'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$decimal=$row[0];

		$query="SELECT mapped_inventory FROM bill_main_tmp WHERE bm_no='$bm_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$mapped_inventory=$row[0];

		$query="SELECT increment FROM district_rate WHERE `district`='$district' AND `sub_system`='$sub_system'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){	$increment=$row[0]; }
		if($increment=='') $increment=0;

		$query="SELECT id,`code`,description,default_price,default_cost,unic,pr_sr,category FROM inventory_items WHERE `status`='1' AND $qry";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$specialrate=$catspecialrate=false;
		$itm_id=$row[0];
		$itm_code=$row[1];
		$itm_desc=$row[2];
		$default_price=$row[3];
		$default_cost=$row[4];
		$itm_unic=$row[5];
		$itm_pr_sr=$row[6];
		$itm_category=$row[7];
		$query1="SELECT MAX(tn.min_profit) FROM tag_name tn, tag_assignment ta WHERE tn.id=ta.tag AND ta.item='$itm_id'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$min_profit=$row1[0];
		$query1="SELECT increment FROM special_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system' AND `item`='$itm_id'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		#$sp_increment=$row1[0];
		$sp_increment = is_null($row1) ? 0 : $row1[0];
		if($sp_increment!='') $specialrate=true;
		$query1="SELECT increment FROM category_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system' AND `category`='$itm_category'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		#$sp_catincrement=$row1[0];
		$sp_catincrement = is_null($row1) ? 0 : $row1[0];
		if($sp_catincrement!='') $catspecialrate=true;

		if($itm_pr_sr==2){
			$itm_uprice='<input type="number" name="uprice" id="uprice" value="'.$default_price.'"/>';
			// $itm_uprice='<select id="uprice"><option value="d,'.$default_price.'">'.number_format($default_price).'</option></select>';
			if($min_profit>0) $last_price=$default_cost+$min_profit;
		}elseif($itm_pr_sr==3){
			$itm_uprice='<input type="number" name="uprice" id="uprice" value="'.$default_price.'"/>';
			if($min_profit>0) $last_price=$default_cost+$min_profit;
		}else{
			if($itm_unic==1){
				// $itm_uprice='';
				$itm_uprice='<input type="number" name="uprice" id="uprice" value="'.$default_price.'"/>';
				if($min_profit>0) $last_price=$min_profit;
			}else{
				$query="SELECT w_price,r_price,c_price,qty FROM inventory_qty WHERE `location`='$mapped_inventory' AND `item`='$itm_id'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$w_price=$row[0];
				$r_price=$row[1];
				if($min_profit>0) $last_price=$row[2]+$min_profit;
				if($specialrate){
					$w_price=round($w_price*((100+$sp_increment)/100),$decimal);
					$r_price=round($r_price*((100+$sp_increment)/100),$decimal);
				}elseif($catspecialrate){
					$w_price=round($w_price*((100+$sp_catincrement)/100),$decimal);
					$r_price=round($r_price*((100+$sp_catincrement)/100),$decimal);
				}else{
					$w_price=round($w_price*((100+$increment)/100),$decimal);
					$r_price=round($r_price*((100+$increment)/100),$decimal);
				}
				$itm_uprice='<select id="uprice"><option value="w,'.$w_price.'">Wholesale - '.number_format($w_price, $decimal).'</option><option value="r,'.$r_price.'" '.$retail_default.'>Retail - '.number_format($r_price, $decimal).'</option></select>';
				$itm_qty=$row[3];
				if(($systemid==1)&&($sub_system!=0)&&($itm_qty>100)) $itm_qty='100+';
				$query="SELECT SUM(qty) FROM inventory_new WHERE `store`='$mapped_inventory' AND `item`='$itm_id'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$itn_qty=$row[0];
				if(($systemid==1)&&($sub_system!=0)&&($itn_qty>100)) $itn_qty='100+';
			}
		}
		$jasonArray["itm_id"]=$itm_id;
		$jasonArray["itm_code"]=$itm_code;
		$jasonArray["itm_desc"]=$itm_desc;
		$jasonArray["itm_unic"]=$itm_unic;
		$jasonArray["itm_pr_sr"]=$itm_pr_sr;
		$jasonArray["itm_uprice"]=$itm_uprice;
		$jasonArray["itm_lprice"]=$last_price;
		$jasonArray["itm_qty"]=$itm_qty;
		$jasonArray["itn_qty"]=$itn_qty;
		$myJSON = json_encode($jasonArray);
		return $myJSON;
	}
}

function getSNList($sub_system,$systemid){
	$bm_no=$_GET['bm_no'];
	$itm_id=$_GET['itm_id'];
	if(isset($_COOKIE['district']))	$district=$_COOKIE['district']; else $district=1;
	$data_list='';
	$decimal=getDecimalPlaces(1);
	$increment='';
	$specialrate=$catspecialrate=false;

	include('config.php');
	$query="SELECT mapped_inventory FROM bill_main_tmp WHERE bm_no='$bm_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$mapped_inventory=$row[0];

	$query="SELECT `category` FROM inventory_items WHERE id='$itm_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$itm_category=$row[0];

	$query="SELECT increment FROM district_rate WHERE `district`='$district' AND `sub_system`='$sub_system'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){	$increment=$row[0]; }
	if($increment=='') $increment=0;

	$query1="SELECT increment FROM special_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system' AND `item`='$itm_id'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$sp_increment=$row1[0];
	if($sp_increment!='') $specialrate=true;
	$query1="SELECT increment FROM category_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system' AND `category`='$itm_category'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$sp_catincrement=$row1[0];
	if($sp_catincrement!='') $catspecialrate=true;
	$query="SELECT itu.id,itu.sn,itu.r_price,itu.w_price FROM inventory_unic_item itu, inventory_qty itq WHERE itu.itq_id=itq.id AND itu.`status`='0' AND itq.location='$mapped_inventory' AND itq.item='$itm_id'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$r_price=$row[2];
		$w_price=$row[3];
				if($specialrate){
					$w_price=round($w_price*((100+$sp_increment)/100),$decimal);
					$r_price=round($r_price*((100+$sp_increment)/100),$decimal);
				}elseif($catspecialrate){
					$w_price=round($w_price*((100+$sp_catincrement)/100),$decimal);
					$r_price=round($r_price*((100+$sp_catincrement)/100),$decimal);
				}
				else{
					$w_price=round($w_price*((100+$increment)/100),$decimal);
					$r_price=round($r_price*((100+$increment)/100),$decimal);
				}
		$data_list.=$row[0].',';
		$data_list.=$row[1].',';
		$data_list.=$r_price.',';
		$data_list.=$w_price.'|';
	}
	$data_list=rtrim($data_list,'|');
	return $data_list;
}

function getTMPBillItems($sub_system){
	$bm_no=$_GET['bm_no'];
	$data_list='';

	include('config.php');
	$query="SELECT bi.id,itm.description,bi.qty,bi.unit_price,bi.`comment`,itm.unic FROM bill_tmp bi, inventory_items itm WHERE bi.item=itm.id AND bi.bm_no='$bm_no' ORDER BY bi.id";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$data_list.=$row[0].',';
		$data_list.=$row[1].',';
		$data_list.=$row[2].',';
		$data_list.=$row[3].',';
		if($row[5]==1){
			$items=explode(",",$row[4]);
			for($i=0;$i<sizeof($items);$i++){
				$itu_id=$items[$i];
				$query1="SELECT sn FROM inventory_unic_item WHERE id='$itu_id'";
				$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
				$sn=$row1[0];
				$data_list.='['.$sn.']<br />';
			}
			$data_list=rtrim($data_list,'<br />');
		}
		$data_list.='|';
	}
	$data_list=rtrim($data_list,'|');
	return $data_list;
}

function removeTMPBillItem($sub_system){
	$bill_id=$_GET['bill_id'];
	$user_id=$_COOKIE['user_id'];
	$msg='Error';
	$out=true;

	include('config.php');
	$query="SELECT bm.`sys_user`,bm.`billed_by`,bm.`bm_no` FROM bill_main_tmp bm, bill_tmp bi WHERE bm.bm_no=bi.bm_no AND bi.id='$bill_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if(($user_id==$row[0])||($user_id==$row[1])){
		$bm_no = $row[2];
		if($out){
			$query="DELETE FROM `bill_tmp` WHERE id= '$bill_id'";
			$result=mysqli_query($conn,$query);
			if($result) $msg='Done';
		}
	}else{ $msg='Error: You do not have permission to Remove this item'; }
	return $msg;
}

// update by nirmal 05_10_2023, quotation bill item updated logic added
function updateTMPBillItem($sub_system){
	$bill_id=$_GET['bill_id'];
	$qty=$_GET['qty'];
	$user_id=$_COOKIE['user_id'];
	$msg='Error';
	$out=true;

	include('config.php');
	$query="SELECT bm.sys_user,bm.billed_by,itm.unic,itm.pr_sr,bi.new_inv_ref,mapped_inventory,itm.id,bm.cust,bm.quotation_no  FROM bill_main_tmp bm, bill_tmp bi, inventory_items itm WHERE bm.bm_no=bi.bm_no AND bi.item=itm.id AND bi.id='$bill_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$sys_user=$row[0];
	$billed_by=$row[1];
	$unic=$row[2];
	$pr_sr=$row[3];
	$new_inv_ref=$row[4];
	$mapped_inventory=$row[5];
	$itm_id=$row[6];
	$cust=$row[7];
	$quotation_no=$row[8];

	// Check if the customer has an active quotation
	if($out){
		// if bill create to quatation cust order
		if(($quotation_no != 0)){
			// get quotation required qty for the item
			$query1 = "SELECT `qty` FROM quotation WHERE `quot_no`='$quotation_no' AND `item` = '$itm_id'";
			$result1 = mysqli_query($conn, $query1);
			$row1 = mysqli_fetch_row($result1);
			$qo_qty = $row1[0];
			$billed_item_qty = 0;

			if($out){
				// if user already has a bills to the quotation numb
				$query2 = "SELECT `invoice_no` FROM bill_main WHERE `quotation_no`='$quotation_no' AND `status` NOT IN (0,8)";
				$result2 = mysqli_query($conn, $query2);
				while ($row2 = mysqli_fetch_array($result2)) {
					$qo_invoice_no = $row2[0];
					$query3 = "SELECT `qty` FROM bill WHERE `invoice_no`='$qo_invoice_no' AND `item`='$itm_id'";
					$result3 = mysqli_query($conn, $query3);
					while ($row3 = mysqli_fetch_array($result3)) {
						$billed_item_qty += $row3[0];
					}
				}

				// if user has pending bills in related to quotation number
				$query4 = "SELECT `bm_no` FROM bill_main_tmp WHERE `quotation_no`='$quotation_no' AND `status` NOT IN (0,8)";
				$result4 = mysqli_query($conn, $query4);
				while ($row4 = mysqli_fetch_array($result4)) {
					$bm_tmp_no = $row4[0];
					$query5 = "SELECT `qty` FROM bill_tmp WHERE `bm_no`='$bm_tmp_no' AND `item`='$itm_id' AND `id` NOT IN('$bill_id')";
					$result5 = mysqli_query($conn, $query5);
					while ($row5 = mysqli_fetch_array($result5)) {
						$billed_item_qty += $row5[0];
					}
				}

				// Check if the updated quantity exceeds the limits
				if (($qty > $qo_qty) || (($qty + $billed_item_qty) > $qo_qty)) {
					$out = false;
					$msg = 'Error: Quantity exceeds the required amount in the linked quotation';
				}
			}
		}
	}


	if($out){
		if($pr_sr==1){
			if($new_inv_ref==0){
				$query="SELECT qty FROM inventory_qty WHERE item='$itm_id' AND `location`='$mapped_inventory'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				if($row[0]<$qty){ $out=false; $msg='Error: Insufficient Item Quantity in the Current Inventory'; }
			}else{
				$query="SELECT qty FROM inventory_new WHERE id='$new_inv_ref'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				if($row[0]<$qty){ $out=false; $msg='Error: Insufficient Item Quantity in New Items'; }
			}
		}
	}

	if($out){
		if(($user_id!=$sys_user)&&($user_id!=$billed_by)){ $out=false; $msg='Error: You do not have permission to Remove this item'; }
	}

	if($out){
		if($unic==0){
			if($qty==0){
				$query="DELETE FROM `bill_tmp` WHERE id='$bill_id'";
				$result=mysqli_query($conn,$query);
			}else{
				$query="UPDATE `bill_tmp` SET `qty`='$qty' WHERE id='$bill_id'";
				$result=mysqli_query($conn,$query);
			}
			if($result) $msg='Done';
		}else{
			$msg='Error: Invalid Action. Please remove the whole item';
		}
	}
	return $msg;
}

function validateBillItem($sub_system){
	$bill_id=$_GET['bill_id'];
	$user_id=$_COOKIE['user_id'];
	$unic_cal=unicCal();
	$out=false;
	$msg='ok';

	include('config.php');
	$query="SELECT bm.sys_user,bm.billed_by,bm.mapped_inventory,itm.id,itm.unic,bi.comment,bi.qty FROM bill_main_tmp bm, bill_tmp bi, inventory_items itm WHERE bm.bm_no=bi.bm_no AND bi.item=itm.id AND bi.id='$bill_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if(($user_id==$row[0])||($user_id==$row[1])){ $out=true; }else{ $msg='Error: You do not have permission to proceed'; }
	$mapped_inventory=$row[2];
	$itm_id=$row[3];
	$itm_unic=$row[4];
	$bi_sn_list=$row[5];
	$bi_qty=$row[6];

	if($out){
		if(($itm_unic==1)&&($unic_cal)){
			$items=explode(",",$bi_sn_list);
			for($i=0;$i<sizeof($items);$i++){
				$itu_id=$items[$i];
				$query1="SELECT `status` FROM inventory_unic_item WHERE id='$itu_id'";
				$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
				if($row1[0]!=0){ $out=false; $msg='Not Available'; }
			}
		}else{
			$query1="SELECT qty FROM inventory_qty WHERE item='$itm_id' AND location='$mapped_inventory'";
			$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
			if($row1[0]<$bi_qty){ $out=false; $msg='Insufficient Quantity'; }
		}
	}
	return $msg;
}

// updated by nirmal 10_01_2022
function validateTMPBill($case){
	$bm_no=$_GET['bm_no'];
	$user_id=$_COOKIE['user_id'];
	$unic_cal=unicCal();
	$data_list='';
	$main_msg_out='ok';

	include('config.php');
	$query="SELECT bi.id,bm.sys_user,bm.billed_by,bm.mapped_inventory,itm.id,itm.unic,GROUP_CONCAT(bi.`comment` SEPARATOR ','),sum(bi.qty),bi.new_inv_ref,bm.cust,itm.pr_sr FROM bill_main_tmp bm, bill_tmp bi, inventory_items itm WHERE bm.bm_no=bi.bm_no AND bi.item=itm.id AND bm.bm_no='$bm_no' GROUP BY itm.id,bi.new_inv_ref";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$bi_id=$row[0];
		if(($user_id==$row[1])||($user_id==$row[2])){ $out=true; $main_msg='ok'; }else{ $out=false; $main_msg='Error: You do not have permission to proceed'; }
		$mapped_inventory=$row[3];
		$itm_id=$row[4];
		$itm_unic=$row[5];
		$bi_sn_list=$row[6];
		$bi_qty=$row[7];
		$bi_new_inv_ref=$row[8];
		$bm_cust=$row[9];
		$pr_sr=$row[10];
		$data_list.=$bi_id.',';

		if($out){
			if($pr_sr==1){
				if(($itm_unic==1)&&($unic_cal)){
					$items=explode(",",$bi_sn_list);
					for($i=0;$i<sizeof($items);$i++){
						if($out){
							$itu_id=$items[$i];
							$query1="SELECT `status` FROM inventory_unic_item WHERE id='$itu_id'";
							$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
							if($bi_qty>0){ if($row1[0]==0){ $msg='ok'; }else{ $out=false; $msg='Not Available'; $main_msg='Error: Item\Items not Available'; }}
							else{ if($row1[0]==1){ $msg='ok'; }else{ $out=false; $msg='Not Available'; $main_msg='Error: Item\Items not Available'; }}
						}
					}
				}else{
					if($bi_qty>0){
						if($bi_new_inv_ref==0){
							$query1="SELECT qty FROM inventory_qty WHERE item='$itm_id' AND location='$mapped_inventory'";
							$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
							if($row1[0]>=$bi_qty){ $msg='ok'; }else{ $out=false; $main_msg=$msg='Insufficient Quantity'; }
						}else{
							$query1="SELECT qty FROM inventory_new WHERE id='$bi_new_inv_ref'";
							$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
							if($row1[0]>=$bi_qty){ $msg='ok'; }else{ $out=false; $main_msg=$msg='Insufficient Quantity'; }
						}
					}else{
						$query1="SELECT SUM(bi.qty) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`='$bm_cust' AND bi.item='$itm_id' AND bm.`status`!='0' AND bm.`lock`='1'";
						$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
						if($row1[0]>=-$bi_qty){ $msg='ok'; }else{ $out=false; $main_msg=$msg='Error: This Customer Did Not Purchase This Item OR Purchased Less Quantity'; }
					}
				}
			}
			if($pr_sr==2){
				$msg='ok';
			}
			if($pr_sr==3){
				$msg='ok';
			}
		}
		$data_list.=$msg.'|';
		if(!$out) $main_msg_out=$main_msg;
	}
	$data_list=rtrim($data_list,'|');
	if($case=='detail') return $data_list;
	if($case=='summery') return $main_msg_out;
}

// update by nirmal 29_03_2022 indent lines
function calculateDiscount($cust,$itemid,$price,$discount_value,$discount_type){
	include('config.php');
	$query="SELECT `status` FROM cust WHERE id='$cust'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$cust_type=$row[0];

	$query="SELECT min_w_rate,max_w_rate,max_r_rate,pr_sr FROM inventory_items WHERE id='$itemid'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$min_w_rate=$row[0];
	$max_w_rate=$row[1];
	$max_r_rate=$row[2];
	$pr_sr=$row[3];

	if($pr_sr==1){
		if($cust_type==1){
			if($discount_type=='percentage'){
				if($max_w_rate>=$discount_value){
					if($min_w_rate<=$discount_value){
						$discount=round(($price/100)*$discount_value);
					}else{
						$discount=round(($price/100)*$min_w_rate);
					}
				}else{ $discount='error'; }
			}else{
				if((($price*$max_w_rate)/100)>=$discount_value){
					if((($price*$min_w_rate)/100)<=$discount_value){
						$discount=$discount_value;
					}else{
						$discount=round((($price*$min_w_rate)/100));
					}
				}else{ $discount='error'; }
			}
		}else
		if($cust_type==2){
			if($discount_type=='percentage'){
				if($max_r_rate>=$discount_value) $discount=round(($price/100)*$discount_value); else $discount='error';
			}else{
				if((($price*$max_r_rate)/100)>=$discount_value) $discount=$discount_value; else $discount='error';
			}

		}
	}else{
		if($discount_type=='percentage'){
			$discount=round(($price/100)*$discount_value);
		}else{
			$discount=$discount_value;
		}
	}
	return $discount;
}

// update by nirmal 18_01_2022, discount bug fixed
// update by nirmal 05_10_2023, quotation bill item adding logic added
// update by nirmal 23_12_2023, price under value allow to bill to in sysid=
function addToBill($sub_system){
	$system_user=$_COOKIE['user_id'];
	$bm_no=$_GET['bm_no'];
	$itm_id=$_REQUEST['itm_id'];
	//$unic=$_GET['unic'];
	$qty=$_GET['qty'];
	$discount=floatval($_REQUEST['discount']);
	$discount_type=$_REQUEST['discount_type'];
	$cust=$_REQUEST['cust_id'];
	$uprice=$_REQUEST['uprice'];
	$itu_list=$_GET['itu_list'];
	$comment=$_GET['comment'];
	$rp_model=$_GET['rp_model'];
	$rp_sn=$_GET['rp_sn'];
	$technicient=$_GET['technicient'];
	$time_now=timeNow();
	$process_new=$out=true;
	$tmp_inv_record=false;
	$out=true;
	$qty1=0;
	$message='Done';
	include('config.php');

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='decimal'");
	$row = mysqli_fetch_assoc($result);
	$decimal=$row['value'];

	$query="SELECT sys_user,quotation_no FROM bill_main_tmp WHERE bm_no='$bm_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($row[0]!=$system_user){ $out=false; $message='Error: Insufficient Permission E112'; }
	$quotation_no = $row[1];

	$query="SELECT pr_sr,unic FROM inventory_items WHERE id='$itm_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$pr_sr=$row[0];
	$unic=$row[1];

	$query="SELECT MAX(tn.min_profit) FROM tag_name tn, tag_assignment ta WHERE tn.id=ta.tag AND ta.item='$itm_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$min_profit=$row[0];

	// Check if the customer has an active quotation
	if($out){
		if(($quotation_no != 0)){
			// get quotation required qty for the item
			$query2 = "SELECT `qty` FROM quotation WHERE `quot_no`='$quotation_no' AND `item` = '$itm_id'";
			$result2 = mysqli_query($conn, $query2);
			$row2 = mysqli_fetch_row($result2);
			$qo_qty = $row2[0];
			$billed_item_qty = 0;

			if(($qo_qty == '') || ($qo_qty == 0)){
				$out=false;
				$message = 'Error: This item not related to current quotation number';
			}

			if($out){
				// if user already has a bills to the quotation numb
				$query1 = "SELECT `invoice_no` FROM bill_main WHERE `quotation_no`='$quotation_no' AND `status` NOT IN (0,8)";
				$result1 = mysqli_query($conn, $query1);
				while ($row1 = mysqli_fetch_array($result1)) {
					$qo_invoice_no = $row1[0];
					$query2 = "SELECT `qty` FROM bill WHERE `invoice_no`='$qo_invoice_no' AND `item`='$itm_id'";
					$result2 = mysqli_query($conn, $query2);
					while ($row2 = mysqli_fetch_array($result2)) {
						$billed_item_qty += $row2[0];
					}
				}

				// if user has pending bills in related to quotation number
				$query3 = "SELECT `bm_no` FROM bill_main_tmp WHERE `quotation_no`='$quotation_no' AND `status` NOT IN (0,8)";
				$result3 = mysqli_query($conn, $query3);
				while ($row3 = mysqli_fetch_array($result3)) {
					$bm_tmp_no = $row3[0];
					$query4 = "SELECT `qty` FROM bill_tmp WHERE `bm_no`='$bm_tmp_no' AND `item`='$itm_id'";
					$result4 = mysqli_query($conn, $query4);
					while ($row4 = mysqli_fetch_array($result4)) {
						$billed_item_qty += $row4[0];
					}
				}

				if(($qty + $billed_item_qty) > $qo_qty){
					$out=false;
					$message = 'Error : Quantity exceeds required amount in linked quotation';
				}
			}
		}
	}

	if($out){
		if($pr_sr==1){
			if($unic==0){
				$uprice0=explode(",",$uprice);
				$price_c=$uprice0[0];
				$price=$uprice0[1]-$discount;
				if($price_c=='r') $qry1='r_price';
				if($price_c=='w') $qry1='w_price';

				$query="SELECT bm.mapped_inventory FROM bill_main_tmp bm WHERE bm.bm_no='$bm_no'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$mapped_inventory=$row[0];
				$query="SELECT qty FROM inventory_qty WHERE location='$mapped_inventory' AND item='$itm_id'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$itq_qty=$row[0];
				$query="SELECT SUM(qty) FROM inventory_new WHERE store='$mapped_inventory' AND item='$itm_id'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$itn_qty=$row[0];
				if(($itq_qty+$itn_qty)<$qty){ $out=false; $message='Error: Insufficient Item Quantity'; }


				$query="SELECT new_inv_ref,qty FROM bill_tmp WHERE bm_no='$bm_no' AND item='$itm_id' ORDER BY id DESC LIMIT 1";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$new_inv_ref = is_null($row) ? 0 : $row[0];
				$bi_qty = is_null($row) ? 0 : $row[0];
				if($new_inv_ref=='') $new_inv_ref=0;
				if($new_inv_ref!=0){
					$query="SELECT qty,$qry1 FROM inventory_new WHERE id='$new_inv_ref'";
					$row=mysqli_fetch_row(mysqli_query($conn,$query));
					if($row[0]<($bi_qty+$qty)){ $out=false; $message='Error: Insufficient Item Quantity in New Items'; }
					$price=$row[1];
					$itq_qty=$row[0]-$bi_qty;
				}

				if($out){
					if($itq_qty>=$qty) $qty0=$qty; else{ $qty0=$itq_qty; $qty1=$qty-$itq_qty; }
					$query="INSERT INTO `bill_tmp` (`bm_no`,`item`,`qty`,`unit_price`,`discount`,`comment`,`date`,`new_inv_ref`) VALUES ('$bm_no','$itm_id','$qty0','$price','$discount','$itu_list','$time_now','$new_inv_ref');";
					$result=mysqli_query($conn,$query);
					if(!$result){ $out=false; $message='Error: Failed to Add the Item'; }
					if($qty1>0){
						$query="SELECT id,qty,$qry1 FROM inventory_new WHERE store='$mapped_inventory' AND item='$itm_id' ORDER BY id";
						$result=mysqli_query($conn,$query);
						while($row=mysqli_fetch_array($result)){
							if($process_new){
								$itn_id=$row[0];
								$itn_qty=$row[1];
								$itn_price=$row[2];
								$price=$itn_price-$discount;
								if($itn_qty>=$qty1){ $qty0=$qty1; $process_new=false; }else{ $qty0=$itn_qty; $qty1=$qty1-$itn_qty; }

								$query1="INSERT INTO `bill_tmp` (`bm_no`,`item`,`qty`,`unit_price`,`discount`,`comment`,`date`,`new_inv_ref`) VALUES ('$bm_no','$itm_id','$qty0','$price','$discount','$itu_list','$time_now','$itn_id');";
								$result1=mysqli_query($conn,$query1);
								if(!$result1){ $out=false; $message='Error: Failed to Add the Item'; }
							}
						}
					}
				}
			}else{
				$items=explode("|",$itu_list);
				$qty=0;
				$uprice=$itu_list0=$existing_com='';

				for($i=0;$i<sizeof($items);$i++){
					$item=explode(",",$items[$i]);
					$itu_id=$item[0];
					$price_c=$item[1];
					$price=$item[2];

					if($price_c=='r') $qry1='r_price';
					if($price_c=='w') $qry1='w_price';
					$query="SELECT `comment` FROM bill_tmp WHERE bm_no='$bm_no'";
					$result=mysqli_query($conn,$query);
					while($row=mysqli_fetch_array($result)){
						$existing_com.=$row[0].',';
					}
					$existing_itu=explode(",",$existing_com);
					$key=array_search($itu_id,$existing_itu);
					if($key>-1){  $out=false; $message='Error: Item Already Added'; }

					if($price_c!='cashback'){
						if($out){
							$query="SELECT `status`,$qry1,c_price FROM inventory_unic_item WHERE `id`='$itu_id'";
							$row=mysqli_fetch_row(mysqli_query($conn,$query));
							if($row[0]!=0){ $out=false; $message='Error: Item No Longer Available'; }
							$c_price=$row[2];

							if($out){
								if($i>0){
									if($uprice!=$price){
										$itu_list0=rtrim($itu_list0,',');
										$uprice0=$uprice-$discount;

										if(!(isBillingPriceUnderValueActive(1))){
											if(($c_price+$min_profit)>$uprice0){ $out=false; $message="Price Under Value";}
										}
										if($out){
											$query="INSERT INTO `bill_tmp` (`bm_no`,`item`,`qty`,`unit_price`,`discount`,`comment`,`date`) VALUES ('$bm_no','$itm_id','$qty','$uprice0','$discount','$itu_list0','$time_now');";
											$result=mysqli_query($conn,$query);
											if(!$result){ $out=false; $message='Error: Failed to Add the Item'; }
										}

										$itu_list0='';
										$qty=0;
									}
								}
								$qty++;
								$uprice=$price;
								$itu_list0.=$itu_id.',';
							}
						}
					}else{
						$qty=-1;
						$uprice=$price;
						$itu_list0=$itu_id;
					}
				}
				if($out){
					$uprice0=$uprice-$discount;
					$itu_list0=rtrim($itu_list0,',');

					if(!(isBillingPriceUnderValueActive(1))){
						if(($c_price+$min_profit)>$uprice0){ $out=false; $message="Price Under Value";}
					}
					if($out){
						$query="INSERT INTO `bill_tmp` (`bm_no`,`item`,`qty`,`unit_price`,`discount`,`comment`,`date`) VALUES ('$bm_no','$itm_id','$qty','$uprice0','$discount','$itu_list0','$time_now');";
						$result=mysqli_query($conn,$query);
						if(!$result){ $out=false; $message='Error: Failed to Add the Item'; }
					}
				}
			}
		}

		if($pr_sr==2){
			$price=$uprice-$discount;

			$query="UPDATE `bill_main_tmp` SET `type`='2' WHERE `bm_no`='$bm_no'";
			$result=mysqli_query($conn,$query);
			if(!$result){
				$out=false; $message='Error: Bill Main Temp Update Failed';
			}

			if($out){
				$query="INSERT INTO `bill_tmp` (`bm_no`,`item`,`qty`,`unit_price`,`discount`,`comment`,`date`,`new_inv_ref`) VALUES ('$bm_no','$itm_id','$qty','$price','$discount','','$time_now','0');";
				$result=mysqli_query($conn,$query);
				if(!$result){ $out=false; $message='Error: Failed to Add the Item'; }
			}
		}

		if($pr_sr==3){
			$price=$uprice-$discount;
			if($technicient=='') $technicient=getNextTechnicient();
			$query="UPDATE `bill_main_tmp` SET `type`='3',`status`='3', `packed_by`='$technicient',`packed_timestamp`='$time_now' WHERE `bm_no`='$bm_no'";
			$result=mysqli_query($conn,$query);
			if(!$result){
				$out=false; $message='Error: Failed to Add the Item to Bill Main Temp';
			}
			if($out){
				$query="INSERT INTO `bill_tmp` (`bm_no`,`item`,`qty`,`unit_price`,`discount`,`comment`,`date`,`new_inv_ref`,`repair_model`,`repair_sn`) VALUES ('$bm_no','$itm_id','$qty','$price','$discount','$comment','$time_now','0','$rp_model','$rp_sn');";
				$result=mysqli_query($conn,$query);
				if(!$result){
					$out=false; $message='Error: Failed to Add the Item';
				}
			}
		}
	}

	return $message;
}

// update by nirmal 12_07_2023 (add if quickbooks customer created or not), 24_12_2023 added new temp bill number generate system
function newTMPBill($sub_system,$cust_odr,$bill_sm,$qo_no){
	$system_user=$_COOKIE['user_id'];
	$district=$_COOKIE['district'];
	$store=$_COOKIE['store'];
	$direct_mkt=$_COOKIE['direct_mkt'];
	if($bill_sm=='') $bill_sm=$system_user;
	if(isset($_POST['gps_x'])) $gps_x=$_POST['gps_x']; else $gps_x=0;
	if(isset($_POST['gps_y'])) $gps_y=$_POST['gps_y']; else $gps_y=0;
	$cust_id=$_REQUEST['cust_id'];
	$authorize_code=rand(1000,9999);
	$time_now=timeNow();
	$jasonArray=array();
	$out=true;
	$bm_type=1;
	$invoice_no=$bm_no=$back_off_com_type=0;
	$message='Done';
	$qb_msg = '';
	$is_custom_invoice_no_active = isCustomInvoiceNoActive(1);
	emptyBillDelete($bill_sm, 'bill2');
	if($is_custom_invoice_no_active){
		$invoice_no = generateBillNumber($store,'temp_bill');
	}

	if($cust_id==''){
		$out=false;
		$message='Please select the customer or phone number';
	}

	if($out){
		include('config.php');
		$query="SELECT st.workflow,up.mapped_inventory FROM stores st, userprofile up WHERE st.id=up.store AND up.id='$bill_sm'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$workflow=$row[0];
		if($row[1]!=0) $mapped_inventory=$row[1]; else $mapped_inventory=$store;
		if($workflow==1) $bm_status=1; else $bm_status=5;
		if($direct_mkt==1) $bm_status=1;
		if($cust_odr=='yes'){ $bm_status=1; $bm_type=4; }

		if($is_custom_invoice_no_active){
			mysqli_begin_transaction($conn); // Start a transaction
			$query="INSERT INTO `bill_main_tmp` (`bm_no`,`authorize_code`,`billed_district`,`quotation_no`,`cust`,`billed_by`,`sys_user`,`order_timestamp`,`type`,`store`,`mapped_inventory`,`gps_x`,`gps_y`,`back_off_com_type`,`sub_system`,`status`) VALUES ('$invoice_no','$authorize_code','$district','$qo_no','$cust_id','$bill_sm','$system_user','$time_now','$bm_type','$store','$mapped_inventory','$gps_x','$gps_y','$back_off_com_type','$sub_system','$bm_status');";
			$result3=mysqli_query($conn,$query);
			if($result3){
				mysqli_commit($conn); // Commit the transaction
				$selectQuery = "SELECT `bm_no` FROM bill_main_tmp ORDER BY `order_timestamp` DESC LIMIT 1"; // Retrieve the last inserted ID with a SELECT query
				$selectResult = mysqli_query($conn, $selectQuery);
				if ($selectResult) {
					$row = mysqli_fetch_assoc($selectResult);
					$bm_no = $row['bm_no'];
				} else {
					$message = "Error: " . mysqli_error($conn);
				}
			}
		}else{
			$query="INSERT INTO `bill_main_tmp` (`authorize_code`,`billed_district`,`quotation_no`,`cust`,`billed_by`,`sys_user`,`order_timestamp`,`type`,`store`,`mapped_inventory`,`gps_x`,`gps_y`,`back_off_com_type`,`sub_system`,`status`) VALUES ('$authorize_code','$district','$qo_no','$cust_id','$bill_sm','$system_user','$time_now','$bm_type','$store','$mapped_inventory','$gps_x','$gps_y','$back_off_com_type','$sub_system','$bm_status');";
			$result3=mysqli_query($conn,$query);
			$bm_no=mysqli_insert_id($conn);
		}
		if(!$result3){ $out=false; $message=$query; /* $message='Error: Bill Could Not be Created!h'; */ }
		// QuickBooks customer created checking
		if((isQuickBooksActive(1))){
			$result = mysqli_query($conn2, "SELECT `qb_cust_id` FROM `cust` WHERE `id`='$cust_id'");
			$row = mysqli_fetch_assoc($result);
			if(!empty($row)){
				if($row['qb_cust_id'] != ''){
					$qb_msg = ' Quickbooks updated.';
				} else{
					$qb_msg = ' Quickbooks update error.';
				}
			}
		}
	}
	$jasonArray["bm_no"]=$bm_no;
	$jasonArray["msg"]=$message;
	$jasonArray["qb_msg"]=$qb_msg;
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

function getBillMain($bill_no){
	global $discount,$hire_purchase,$bm_hire_purchase,$bm_type,$cust_odr,$cu_id,$cu_name,$cu_nic,
	$cu_mobile,$gps_x,$gps_y,$salesman_id,$salesman_name,$recovery_agent,$cu_asso_sman,$cust_mtype,
	$bill_item_count,$bill_total;
	$user_arr=array();

	include('config.php');
	$query="SELECT `value` FROM settings WHERE setting='discount'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$discount=$row[0];

	$query="SELECT COUNT(id) FROM `function` WHERE `name`='Hire Purchase' AND `status`='1'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($row[0]==1) $hire_purchase=true; else $hire_purchase=false;

	$query="SELECT `id`,`username` FROM userprofile";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$user_arr[$row[0]]=ucfirst($row[1]);
	}

	$query="SELECT btm.`type`,cu.id,cu.`name`,cu.nic,cu.mobile,btm.billed_by,btm.recovery_agent,cu.associated_salesman,cu.gps_x,cu.gps_y FROM bill_main_tmp btm, cust cu WHERE btm.`cust`=cu.id AND btm.`status`!=0 AND btm.bm_no='$bill_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$bm_type=$row[0];
	if(($bm_type==4)||($bm_type==5)) $cust_odr='yes'; else $cust_odr='no';
	$cu_id=$row[1];
	$cu_name=$row[2];
	$cu_nic=$row[3];
	$cu_mobile=$row[4];
	$salesman_id=$row[5];
	$salesman_name=$user_arr[$row[5]];
	$recovery_agent=$row[6];
	$cu_asso_sman=$user_arr[$row[7]];
	$gps_x=$row[8];
	$gps_y=$row[9];

	$cust_mtype=getMasterCust($cu_id);

	$query="SELECT count(id),SUM(qty*unit_price) FROM bill_tmp WHERE bm_no='$bill_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$bill_item_count=$row[0];
	$bill_total=$row[1];

	if($recovery_agent=='') $bm_hire_purchase=false; else $bm_hire_purchase=true;
}

function hpFormData(){
	global $hp_type_id,$hp_type_name,$today;
	$hp_type_id=$hp_type_name=array();
	$today=dateNow();
	include('config.php');
	$query="SELECT id,`name` FROM hp_schedule_type WHERE `status`='1'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$hp_type_id[]=$row[0];
		$hp_type_name[]=$row[1];
	}
}

function processInventoryNew($item,$lastitem,$store,$table){
	$nt_id=$itq_qty='';
	include('config.php');
	$query="SELECT id,item,w_price,r_price,c_price,qty FROM inventory_qty WHERE location='$store' AND item='$item'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$itq_itqid=$row[0];
		$itq_itmid=$row[1];
		$itq_wprice=$row[2];
		$itq_rprice=$row[3];
		$itq_cprice=$row[4];
		$itq_qty=$row[5];
	}
	$query2="SELECT id,w_price,r_price,c_price,qty FROM inventory_new WHERE store='$store' AND item='$item' ORDER BY id DESC";
	$result2=mysqli_query($conn,$query2);
	while($row2=mysqli_fetch_array($result2)){
		$nt_id=$row2[0];
		$nt_wprice=$row2[1];
		$nt_rprice=$row2[2];
		$nt_cprice=$row2[3];
		$nt_qty=$row2[4];
	}
	if(($itq_qty<=0)&&($nt_id!='')){
		$query3="INSERT INTO `inventory_temp` (`itq_id`,`item`,`location`,`w_price`,`r_price`,`c_price`,`qty`) VALUES ('$itq_itqid','$itq_itmid','$store','$itq_wprice','$itq_rprice','$itq_cprice','$itq_qty')";
		$result3=mysqli_query($conn,$query3);
		$lastid_temp=mysqli_insert_id($conn);

		$query3="UPDATE `inventory_qty` SET `w_price`='$nt_wprice',`r_price`='$nt_rprice',`c_price`='$nt_cprice',`qty`='$nt_qty' WHERE `id`='$itq_itqid'";
		$result3=mysqli_query($conn,$query3);
		if($result3){
			$query3="DELETE FROM `inventory_new` WHERE `id` = '$nt_id'";
			$result3=mysqli_query($conn,$query3);
		}
		if($lastitem!=0){
			$query4="UPDATE `$table` SET `no_update`='$lastid_temp' WHERE `id`='$lastitem'";
			mysqli_query($conn,$query4);
		}
	}
}

// updated by nirmal 02_08_2023 to add tax calculation to total, 25_12_2023 added new invoice number system
function createInvoice($sub_system){
	$bm_no=$_GET['bm_no'];
	$user_id=$_COOKIE['user_id'];
	$time_now=timeNow();
	$unic_cal=unicCal();
	$invoice_no='';
	$inventory_trigger=array();
	$itm_wprice=$itm_rprice=0;
	$out=false;
	$msg='Error';
	$qb_msg='';
	$is_custom_invoice_no_active = isCustomInvoiceNoActive(1);

	include('config.php');
	$query="SELECT billed_district,quotation_no,`cust`,billed_by,sys_user,recovery_agent,order_timestamp,`type`,store,mapped_inventory,gps_x,gps_y,back_off_com_type,back_off_comment,`sub_system`,`status`,`packed_by`,`packed_timestamp` FROM bill_main_tmp WHERE bm_no='$bm_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$bm_billed_district=$row[0];
	$bm_quotation_no=$row[1];
	$bm_cust=$row[2];
	$bm_billed_by=$row[3];
	$bm_sys_user=$row[4];
	$bm_recovery_agent=$row[5];
	$bm_order_timestamp=$row[6];
	$bm_type=$row[7];
	$bm_store=$row[8];
	$bm_mapped_inventory=$row[9];
	$bm_gps_x=$row[10];
	$bm_gps_y=$row[11];
	$bm_back_off_com_type=$row[12];
	$bm_back_off_comment=$row[13];
	$bm_sub_system=$row[14];
	$bm_status=$row[15];
	$bm_packed_by=$row[16];
	$bm_packed_timestamp=$row[17];

	if($is_custom_invoice_no_active){
		$orderhash=md5(time().$bm_no);
		$new_invoice_number = generateBillNumber($bm_store,'bill_main');
	}else{
		$orderhash=md5(time()+$bm_no);
	}
	$authorize_code=rand(1000,9999);

	if((($bm_billed_by==$user_id)||($bm_sys_user==$user_id))&&($sub_system==$bm_sub_system)){ $out=true; $msg='Done'; }

	if($out){
		if($bm_recovery_agent!=''){
			$qry_re1=",`recovery_agent`";
			$qry_re2=",'$bm_recovery_agent'";
		}else{
			$qry_re1="";
			$qry_re2="";
		}
		if($bm_packed_timestamp !=''){
			$qry_pt1=",`packed_timestamp`";
			$qry_pt2=",'$bm_packed_timestamp'";
		}else{
			$qry_pt1 = '';
			$qry_pt2 = '';
		}
		if($bm_packed_by !=''){
			$qry_pb1=",`packed_by`";
			$qry_pb2=",'$bm_packed_by'";
		}else{
			$qry_pb1 = '';
			$qry_pb2 = '';
		}
		$lock = 1;
		if($bm_type == 3) $lock = 2;
		if($bm_type == 4 || $bm_type == 5){
			$bm_status = 1;
			$lock = 2;
		}
		if($is_custom_invoice_no_active){
			$query="INSERT INTO `bill_main` (`invoice_no`,`order_hash`,`authorize_code`,`billed_district`,`quotation_no`,`module`,`cust`,`billed_by`,`sys_user` $qry_re1,`order_timestamp`,`billed_timestamp`,`type`,`store`,`mapped_inventory`,`lock`,`gps_x`,`gps_y`,`sub_system`,`status` $qry_pb1 $qry_pt1)
			VALUES ('$new_invoice_number','$orderhash','$authorize_code','$bm_billed_district','$bm_quotation_no','2','$bm_cust','$bm_billed_by','$bm_sys_user' $qry_re2,'$bm_order_timestamp','$time_now','$bm_type','$bm_store','$bm_mapped_inventory','$lock','$bm_gps_x','$bm_gps_y','$bm_sub_system','$bm_status' $qry_pb2 $qry_pt2);";
			$result=mysqli_query($conn,$query);
		}else{
			$query="SELECT MAX(invoice_no) FROM bill_main";
			$row1=mysqli_fetch_row(mysqli_query($conn,$query));
			$invoice_no=$row1[0];
			if($invoice_no==''){
				$query="SELECT MAX(invoice_no) FROM bill";
				$row1=mysqli_fetch_row(mysqli_query($conn,$query));
				$invoice_no=$row1[0];

				if($invoice_no==''){
					$invoice_no=1;
				}
			}else{
				$invoice_no = $invoice_no + 1;
			}
			$query="INSERT INTO `bill_main` (`invoice_no`,`order_hash`,`authorize_code`,`billed_district`,`quotation_no`,`module`,`cust`,`billed_by`,`sys_user` $qry_re1,`order_timestamp`,`billed_timestamp`,`type`,`store`,`mapped_inventory`,`lock`,`gps_x`,`gps_y`,`sub_system`,`status` $qry_pb1 $qry_pt1)
			VALUES ('$invoice_no','$orderhash','$authorize_code','$bm_billed_district','$bm_quotation_no','2','$bm_cust','$bm_billed_by','$bm_sys_user' $qry_re2,'$bm_order_timestamp','$time_now','$bm_type','$bm_store','$bm_mapped_inventory','$lock','$bm_gps_x','$bm_gps_y','$bm_sub_system','$bm_status' $qry_pb2 $qry_pt2);";
			$result=mysqli_query($conn,$query);
			$lastid_temp=mysqli_insert_id($conn);
		}
		if(!$result){ $out=false; $msg='Failed to Create the Invoice'; }
	}
	if($out){
		if($is_custom_invoice_no_active){
			$query="SELECT invoice_no FROM bill_main WHERE order_hash='$orderhash' LIMIT 1";
		}else{
			$query="SELECT invoice_no FROM bill_main WHERE order_hash='$orderhash' ORDER BY invoice_no DESC LIMIT 1";
		}
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$invoice_no=$row[0];
		if($invoice_no==''){ $out=false; $msg='Invoice Number Error'; }
	}
	if($out){
		$query="SELECT bi.item,bi.qty,bi.unit_price,bi.discount,bi.`comment`,bi.repair_model,bi.repair_sn,bi.`date`,bi.new_inv_ref,bi.gift,itm.unic,itm.pr_sr,itm.default_cost FROM bill_tmp bi, inventory_items itm WHERE bi.item=itm.id AND bi.bm_no='$bm_no'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			if($out){
				$item=$row[0];
				$qty=$row[1];
				$unit_price=$row[2];
				$discount=$row[3];
				$itu_id_list=$row[4];
				$repair_model=$row[5];
				$repair_sn=$row[6];
				$date=$row[7];
				$new_inv_ref=$row[8];
				$gift=$row[9];
				$itm_unic=$row[10];
				$itm_pr_sr=$row[11];
				$itm_dcost=$row[12];

				$q=$cost=$unic_cost=0;

				if($itm_pr_sr==1){
					if((($itm_unic==1)&&(!$unic_cal))||($itm_unic==0)){
						if($new_inv_ref==0){
							$query1="SELECT id,qty,c_price,w_price,r_price FROM inventory_qty WHERE item='$item' AND location='$bm_mapped_inventory'";
							$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
							$itq_id=$row1[0];
							$itq_qty=$row1[1];
							$itq_cost=$row1[2];
							$itm_wprice=$row1[3];
							$itm_rprice=$row1[4];
							$inventory_trigger[]=$item;
						}else{
							$query1="SELECT id,qty,c_price,w_price,r_price FROM inventory_new WHERE id='$new_inv_ref'";
							$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
							$itn_id=$row1[0];
							$itn_qty=$row1[1];
							$itn_cost=$row1[2];
							$itm_wprice=$row1[3];
							$itm_rprice=$row1[4];
						}
					}
				}

				$debug_id=debugStart($item.'|'.$bm_mapped_inventory,$qty);

				//---------Get SN list--------------//
				$itu_sn_list='';
				if($itm_unic==1){
					if($qty>0){ $st1=0; $st2=1; }else{ $st1=1; $st2=0; }
					$query1="SELECT sn,c_price FROM inventory_unic_item WHERE id IN ($itu_id_list) AND `status`='$st1'";
					$result1=mysqli_query($conn,$query1);
					while($row1=mysqli_fetch_array($result1)){
						$itu_sn_list.=$row1[0].',';
						$unic_cost+=$row1[1];
						$q++;
					}
					if($qty>0){
						if($qty!=$q){ $out=false; $msg='Error: Item Availability Mismatch'; }
					}else{
						if(-$qty!=$q){ $out=false; $msg='Error: Item Availability Mismatch'; }
					}
					$itu_sn_list=rtrim($itu_sn_list,',');
				}

				//----------Cost Cal---------------//
				$inventory_deduct=false;
				if($out){
					if($itm_pr_sr==1){
						if($itm_unic==1){
							if($unic_cal){
								if($qty>0){
									$cost=$unic_cost/$qty;
								}else{
									$cost=-($unic_cost/$qty);
								}
							}else{
								if($new_inv_ref==0){
									$inventory_deduct=true;
									$cost=$itq_cost;
									if($qty>$itq_qty){ $out=false; $msg='Error: Item Availability Mismatch'; }
								}else{
									$inventory_deduct=true;
									$cost=$itn_cost;
									if($qty>$itn_qty){ $out=false; $msg='Error: Item Availability Mismatch'; }
								}
							}
						}else{
							if($qty>0){
								if($new_inv_ref==0){
									$inventory_deduct=true;
									$cost=$itq_cost;
									if($qty>$itq_qty){ $out=false; $msg='Error: Item Availability Mismatch'; }
								}else{
									$inventory_deduct=true;
									$cost=$itn_cost;
									if($qty>$itn_qty){ $out=false; $msg='Error: Item Availability Mismatch'; }
								}
							}else{
								$inventory_deduct=true;
								$cost=$itq_cost;
							}
						}
					}else{
						$cost=$itm_dcost;
					}
				}
				//-------------------------------//

				if($itu_sn_list == ''){
					$itu_sn_list = $itu_id_list;
				}
				if($out){
					$query1="INSERT INTO `bill` (`invoice_no`,`item`,`qty`,`unit_price`,`cost`,`discount`,`w_price`,`r_price`,`comment`,`repair_model`,`repair_sn`,`date`,`gift`,`return_odr_replace`,`cross_trans`,`no_update`) VALUES ('$invoice_no','$item','$qty','$unit_price','$cost','$discount','$itm_wprice','$itm_rprice','$itu_sn_list','$repair_model','$repair_sn','$date','$gift','0','0','$new_inv_ref');";
					$result1=mysqli_query($conn,$query1);
					if(!$result1){ $out=false; $msg='Failed to Add Item to Invoice'; }
					$bill_id=mysqli_insert_id($conn);
				}
				if($out && $inventory_deduct){
					if($new_inv_ref==0){
						$query1="UPDATE `inventory_qty` SET `qty`=qty-$qty WHERE `id`='$itq_id'";
						$result1=mysqli_query($conn,$query1);
						if(!$result1){ $out=false; $msg='Error: Failed to Update the Inventory'; }
					}else{
						$query1="UPDATE `inventory_new` SET `qty`=qty-$qty WHERE `id`='$new_inv_ref'";
						$result1=mysqli_query($conn,$query1);
						if(!$result1){ $out=false; $msg='Error: Failed to Update the New Inventory'; }
					}
				}
				if($out && ($itm_unic==1)){
					$query1="UPDATE inventory_unic_item SET `invoice_no`='$invoice_no',`bill_id`='$bill_id',`status`='$st2' WHERE id IN ($itu_id_list) AND `status`='$st1'";
					$result1=mysqli_query($conn,$query1);
					if(!$result1){ $out=false; $msg='Error: Failed to Update the Unique Items'; }
				}
				if($out) debugEnd($debug_id,'success'); else debugEnd($debug_id,'fail');
			}
		}
	}
	if($out){
		$query="SELECT SUM(unit_price * qty) FROM bill WHERE qty>0 AND invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		if($row[0]!='') $up_total=$row[0]; else $up_total=0;
		$query="SELECT SUM(unit_price * qty) FROM bill WHERE qty<0 AND invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		if($row[0]!='') $down_total=$row[0]; else $down_total=0;
		// ------------ added by nirmal 26_07_2023
		$result = mysqli_query($conn, "SELECT `value` FROM settings WHERE setting='tax'");
		$row = mysqli_fetch_assoc($result);
		$tax_rate = $row['value'];

		$result = mysqli_query($conn, "SELECT `value` FROM settings WHERE setting='decimal'");
		$row = mysqli_fetch_assoc($result);
		$decimal = $row['value'];

		$total = $up_total + $down_total;
		$tax_added_value = (((($total) / ((100+$tax_rate)/100)) * ($tax_rate / 100)));

		$query="UPDATE bill_main SET `invoice_+total`='$up_total',`invoice_-total`='$down_total', `tax` = '$tax_added_value' WHERE invoice_no='$invoice_no'";
		// ------------
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false; $msg='Error: Failed to Update the Bill Total'; }
	}
	if($out){
		$query="DELETE FROM `bill_tmp` WHERE `bm_no`='$bm_no'";
		$result=mysqli_query($conn,$query);
		if($result){
			$query="DELETE FROM `bill_main_tmp` WHERE `bm_no`='$bm_no'";
			$result=mysqli_query($conn,$query);
			if(!$result){ $out=false; $msg='Error: Failed to Delete the Temp Bill'; }
		}
	}
	if($out){
		for($i=0;$i<sizeof($inventory_trigger);$i++){
			processInventoryNew($inventory_trigger[$i],0,$bm_store,'bill');
		}
	}
	return $msg.'|'.$invoice_no;
}

function changeSalesman(){
	$bm_no=$_GET['bm_no'];
	$new_sm=$_GET['new_sm'];
	$user_id=$_COOKIE['user_id'];
	$out=false;
	$msg='Error';
	include('config.php');
	$query="SELECT billed_by,sys_user FROM bill_main_tmp WHERE bm_no='$bm_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if(($row[0]==$user_id)||($row[1]==$user_id)){ $out=true; $msg='Done'; }

	if($out){
		$query="UPDATE `bill_main_tmp` SET `billed_by`='$new_sm' WHERE `bm_no`='$bm_no'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false; $msg='Error'; }
	}
	return $msg;
}

function changeRecoveryAgent(){
	$bm_no=$_GET['bm_no'];
	$new_rag=$_GET['new_rag'];
	$user_id=$_COOKIE['user_id'];
	$out=false;
	$msg='Error';
	include('config.php');
	$query="SELECT billed_by,sys_user FROM bill_main_tmp WHERE bm_no='$bm_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if(($row[0]==$user_id)||($row[1]==$user_id)){ $out=true; $msg='Done'; }

	if($out){
		if($new_rag==''){
			$query="UPDATE `bill_main_tmp` SET `recovery_agent`=NULL WHERE `bm_no`='$bm_no'";
		}else{
			$query="UPDATE `bill_main_tmp` SET `recovery_agent`='$new_rag' WHERE `bm_no`='$bm_no'";
		}
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false; $msg='Error'; }
	}
	return $msg;
}

function getDiscount($sub_system){
	$itemid=$_GET['itemid'];
	$cust_id=$_GET['cust'];

	include('config.php');
	$query="SELECT `status` FROM cust WHERE id='$cust_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$cust_type=$row[0];
	if($cust_type==1){
		$query="SELECT min_w_rate,max_w_rate FROM inventory_items WHERE id='$itemid'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$rate=$row[0].'% - '.$row[1].'%';
	}else
	if($cust_type==2){
		$query="SELECT max_r_rate FROM inventory_items WHERE id='$itemid'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$rate=$row[0].'%';
	}else $rate='0%';

	$query="SELECT value FROM settings WHERE setting='discount'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$discount_type=$row[0];
	$query="SELECT bi.unit_price,bi.discount FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`='$cust_id' AND bi.item='$itemid' AND bi.discount!=0 AND bm.`sub_system`='$sub_system' ORDER BY bi.id DESC LIMIT 1";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query));
	if(($row1[0]!='')&&($row1[1]!='')){
		if($discount_type=='percentage') $last_discount=round(($row1[1]/($row1[0]+$row1[1]))*100);
		if($discount_type=='price') $last_discount=$row1[1];
	}else $last_discount=0;

	return $rate.'|'.$last_discount;
}

function getAuthorize(){
	$invoice_no=$_GET['invoice_no'];
	include('config.php');
	$query="SELECT authorize_code FROM bill_main WHERE invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$authorize_code=$row[0];
	return $authorize_code;
}

function tempAuthCodeValidate(){
	$out='fail';
	$bm_no=$_POST['bm_no'];
	$auth_code=$_POST['auth_code'];
	include('config.php');
	$query="SELECT authorize_code FROM bill_main_tmp WHERE bm_no='$bm_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($auth_code==$row[0]) $out='validated';
	return $out;
}

function authCodeValidate(){
	$out='fail';
	$invoice_no=$_POST['invoice_no'];
	$auth_code=$_POST['auth_code'];
	include('config.php');
	$query="SELECT authorize_code FROM bill_main WHERE invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($auth_code==$row[0]) $out='validated';
	return $out;
}

function getBillCust(){
	global $bmcust_id,$bmcust_name,$bm_type;
	$invoice_no=$_GET['bill_no'];
	include('config.php');
	$result = mysqli_query($conn,"SELECT cu.id,cu.name,bm.type FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.invoice_no='$invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$bmcust_id=$row['id'];
	$bmcust_name=$row['name'];
	$bm_type=$row['type'];
}

function getItems2(){
	global $id,$description;
	include('config.php');
	$query="SELECT id,description FROM inventory_items WHERE `status`=1";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$id[]=$row[0];
		$description[]=$row[1];
	}
}

// update by nirmal 21_10_2023
function getItems($item_filter,$sub_system,$systemid){
	global $discount,$unic_qty,$id,$code,$description,$w_price,$r_price,$cost,$drawer,$qty,$tt_item,$tt_qty,$unic,$pr_sr,$unic_item_code,$unic_item_list,$unic_item_list2,$is_unic_item;
	$unic_item_code=$qry_filter='';
	$unic_item_list=$unic=$tt_item=$tt_qty=$drawer=$qty=$r_price=$w_price=$description=$code=$id=$unic_item_list2=$pr_sr=array();
	$store=$_COOKIE['store'];
	$user_id=$_COOKIE['user_id'];
	$increment='';
	$decimal=getDecimalPlaces(1);
	include('config.php');

	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='discount'");
	$row = mysqli_fetch_assoc($result);
	$discount=$row['value'];

	$result = mysqli_query($conn,"SELECT mapped_inventory FROM userprofile WHERE id='$user_id'");
	$row = mysqli_fetch_assoc($result);
	if($row['mapped_inventory']!=0) $store=$row['mapped_inventory'];
	if(isset($_COOKIE['district']))	$district=$_COOKIE['district']; else $district=1;
	if($_COOKIE['direct_mkt']==1) $qry_filter='AND inq.qty>0';
	$sp_item=$sp_increment=$sp_category=$sp_catincrement=array();
	if($item_filter=='all'){ 	$filter_product=true; $filter_service=true; }
	if($item_filter==1){ $filter_product=true; $filter_service=false; }
	if($item_filter==2){ $filter_product=false; $filter_service=true; }
	if($item_filter==3){ $filter_product=false; $filter_service=true; }
	if($item_filter==''){ 	$filter_product=true; $filter_service=true; }
	$category_filer = '';
	if(($systemid == 13) && ($_REQUEST['action'] == 'quotation')){
		$category_filer = " AND itm.`category` NOT IN (SELECT `id` FROM item_category WHERE `status` IN (0))";
	}

	if(isset($_GET['unic'])){
		$unicitemid=$_GET['itemid'];
		if($_GET['unic']=='yes'){
			if(isset($_GET['cashback'])) $itu_status=$_GET['cashback']; else $itu_status=0;
			$query1="SELECT itm.code,itm.unic FROM inventory_items itm WHERE itm.id='$unicitemid' $category_filer";
			$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
			$unic_item_code=$row1[0];
			$is_unic_item=$row1[1];
			$query1="SELECT itu.sn,itq.qty FROM inventory_items itm, inventory_qty itq, inventory_unic_item itu WHERE itm.id=itq.item AND itu.itq_id=itq.id AND itq.`location`='$store' AND itu.`status`='$itu_status' AND itm.id='$unicitemid' $category_filer";
			$result1=mysqli_query($conn,$query1);
			while($row1=mysqli_fetch_array($result1)){
				$unic_item_list[]=$row1[0];
				$unic_qty=$row1[1];
			}
			$query1="SELECT itu.sn FROM inventory_items itm, inventory_qty itq, inventory_unic_item itu WHERE itm.id=itq.item AND itu.itq_id=itq.id AND itu.`status`=1 AND itm.id='$unicitemid' $category_filer";
			$result1=mysqli_query($conn,$query1);
			while($row1=mysqli_fetch_array($result1)){
				$unic_item_list2[]=$row1[0];
			}
		}
		if($_GET['unic']=='no'){
			$result1 = mysqli_query($conn,"SELECT itm.code FROM inventory_items itm WHERE itm.id='$unicitemid' $category_filer");
			$row1 = mysqli_fetch_assoc($result1);
			$unic_item_code=$row1['code'];
		}
	}

	$query="SELECT increment FROM district_rate WHERE `district`='$district' AND `sub_system`='$sub_system'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){	$increment=(100+$row[0])/100; }
	if($increment=='') $increment=1;

	$query1="SELECT item,increment FROM special_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system'";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$sp_item[]=$row1[0];
		$sp_increment[]=$row1[1];
	}
	$query1="SELECT category,increment FROM category_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system'";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$sp_category[]=$row1[0];
		$sp_catincrement[]=$row1[1];
	}

	if($filter_product){
		$query="SELECT itm.id,itm.code,itm.description,inq.w_price,inq.r_price,inq.c_price,inq.drawer_no,inq.qty,itm.`category`,itm.`unic`,itm.pr_sr FROM inventory_items itm, inventory_qty inq WHERE itm.id=inq.item AND inq.location='$store' AND itm.`status`=1 AND itm.pr_sr=1 $qry_filter $category_filer";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$no_specialrate=$no_catspecialrate=true;
			$id[]=$row[0];
			$code[]=$row[1];
			$description[]=$row[2];
			$cost[]=$row[5];
			$drawer[]=$row[6];
			if(($systemid==1)&&($sub_system!=0)&&($row[7]>100)) $qty[]='100+'; else $qty[]=$row[7];
			$unic[]=$row[9];
			$pr_sr[]=$row[10];
			for($i=0;$i<sizeof($sp_item);$i++){
				if($sp_item[$i]==$row[0]){
					$no_specialrate=false;
					$w_price[]=round($row[3]*((100+$sp_increment[$i])/100),$decimal);
					$r_price[]=round($row[4]*((100+$sp_increment[$i])/100),$decimal);
				}
			}
			if($no_specialrate){
					for($i=0;$i<sizeof($sp_category);$i++){
						if($sp_category[$i]==$row[8]){
							$no_catspecialrate=false;
							$w_price[]=round($row[3]*((100+$sp_catincrement[$i])/100),$decimal);
							$r_price[]=round($row[4]*((100+$sp_catincrement[$i])/100),$decimal);
						}
					}
					if($no_catspecialrate){
						$w_price[]=round(($row[3]*$increment),$decimal);
						$r_price[]=round(($row[4]*$increment),$decimal);
					}
			}
		}
		$query3="SELECT itm.id,itn.qty FROM inventory_new itn, inventory_items itm WHERE itn.item=itm.id AND store='$store' $category_filer";
		$result3=mysqli_query($conn,$query3);
		while($row3=mysqli_fetch_array($result3)){
			$tt_item[]=$row3[0];
			if(($systemid==1)&&($sub_system!=0)&&($row3[1]>100)) $tt_qty[]='100+'; else $tt_qty[]=$row3[1];
		}
	}
	if($filter_service){
		if($item_filter==2) $qry_pr_sr="AND itm.pr_sr='2'"; else
		if($item_filter==3) $qry_pr_sr="AND itm.pr_sr='3'"; else
		$qry_pr_sr="AND itm.pr_sr IN (2,3)";
		$query="SELECT itm.id,itm.code,itm.description,itm.default_price,itm.pr_sr FROM inventory_items itm WHERE itm.`status`=1 $qry_pr_sr $category_filer";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$id[]=$row[0];
			$code[]=$row[1];
			$description[]=$row[2];
			$cost[]=0;
			$drawer[]=0;
			$qty[]=0;
			$unic[]=0;
			$w_price[]=$row[3];
			$r_price[]=0;
			$pr_sr[]=$row[4];
		}
	}
}

function validateBillNo(){
	include('config.php');
	$invoice_no=$_REQUEST['id'];
	$salesman=$_GET['s'];
	$result = mysqli_query($conn,"SELECT COUNT(invoice_no) as `count` FROM bill_main WHERE billed_by='$salesman' AND invoice_no='$invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$bm_exist=$row['count'];
	$result = mysqli_query($conn,"SELECT COUNT(id) as `count` FROM bill WHERE invoice_no='$invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$inv_count=$row['count'];

	if(($bm_exist==0)&&($inv_count>0))
		return true;
	else
		return false;
}

function getNextTechnicient(){
	$today=dateNow();
	include('config.php');
	$technicient=$techn_prio=$tech_next=array();
	$query1="SELECT `user` FROM permission WHERE `user` IN (SELECT pe.`user` FROM permission pe, userprofile up WHERE pe.`user`=up.id AND up.`status`=0 AND pe.`function`='5') GROUP BY `user` HAVING SUM(`function`)=5";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$technicient[]=$row1[0];
		$techn_prio[$row1[0]]=0;
	}
	$query1="SELECT packed_by,count(packed_by) FROM bill_main WHERE `type`='3' AND packed_by!='' AND `invoice_+total`!=0 AND `status`!='0' AND date(order_timestamp)='$today' GROUP BY packed_by";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$c=array_search($row1[0],$technicient);
		if($c>=0){
			$techn_prio[$row1[0]]=$row1[1];
		}
	}

	$tech_next=array_keys($techn_prio, min($techn_prio));
	return $tech_next[0];
}

function releaseCrossTrans($sub_system,$systemid){
	if(($systemid==1)&&($sub_system==1)&&(isset($_GET['cust']))){
		$invoice_no=$_GET['id'];
		$salesman=$_COOKIE['user_id'];
		$gtn_no='';
		include('config.php');
		$query="SELECT gtn_no FROM transfer_main WHERE invoice_no='$invoice_no' AND `status`='4' AND `user`='$salesman'";
		$result = mysqli_query($conn,$query);
		$row = mysqli_fetch_assoc($result);
		$gtn_no=$row['gtn_no'];

		if($gtn_no==''){
			$query="SELECT MAX(gtn_no) FROM transfer_main WHERE invoice_no='$invoice_no' AND `status`='5'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$gtn_no=$row[0];
		}
		if($gtn_no>0){
			$query="UPDATE `transfer_main` SET `status`='4' WHERE gtn_no='$gtn_no'";
			mysqli_query($conn,$query);
		}
	}
}

function crossTransfer($invoice_no,$itemid,$fromstore,$qty){
	$tostore=$_COOKIE['store'];
	$salesman=$_COOKIE['user_id'];
	$time_now=timeNow();
	$gtn_no=$inventory_qty=$inventory_id=$ivq_w_price=$ivq_r_price=$ivq_c_price='';

	include('config.php');
	$query="SELECT gtn_no FROM transfer_main WHERE invoice_no='$invoice_no' AND `status`='4' AND `user`='$salesman' AND `from_store`='$fromstore' AND `to_store`='$tostore'";
	$result = mysqli_query($conn,$query);
	$row = mysqli_fetch_assoc($result);
	$gtn_no=$row['gtn_no'];
	if($gtn_no==''){
		$result = mysqli_query($conn,"SELECT MAX(gtn_no) as trmainmax FROM transfer_main");
		$row = mysqli_fetch_assoc($result);
		$gtn_no=$row['trmainmax']+1;

		$query="INSERT INTO `transfer_main` (`gtn_no`,`invoice_no`,`from_store`,`to_store`,`user`,`date`,`status`) VALUES ('$gtn_no','$invoice_no','$fromstore','$tostore','$salesman','$time_now','4')";
		$result3=mysqli_query($conn,$query);
	}

	$query="SELECT ivq.qty,ivq.id,ivq.w_price,ivq.r_price,ivq.c_price FROM inventory_qty ivq WHERE ivq.location='$fromstore' AND ivq.item='$itemid'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$inventory_qty=$row[0];
		$inventory_id=$row[1];
		$ivq_w_price=$row[2];
		$ivq_r_price=$row[3];
		$ivq_c_price=$row[4];
	}

	if($inventory_qty>=$qty){
		$query2="INSERT INTO `transfer` (`gtn_no`,`item`,`w_price`,`r_price`,`c_price`,`qty`) VALUES ('$gtn_no','$itemid','$ivq_w_price','$ivq_r_price','$ivq_c_price','$qty')";
		$result2=mysqli_query($conn,$query2);
		if($result2){
			$new_qty=$inventory_qty-$qty;
			$query3="UPDATE `inventory_qty` SET `qty`='$new_qty' WHERE `id`=$inventory_id";
			$result3=mysqli_query($conn,$query3);
		}
	}
}

function updateBillitem(){
	global $message,$salesman,$cust,$invoice_no;
	$itemid=$_REQUEST['id'];
	$qty=$_REQUEST['qty'];
	$salesman=$_REQUEST['s'];
	$cust=$_REQUEST['cust'];
	$user_id=$_COOKIE['user_id'];
	$out=false;
	$msg='Item could not be Updated!';

	include('config.php');
		$result = mysqli_query($conn,"SELECT bm.mapped_inventory FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bi.id='$itemid'");
		$row = mysqli_fetch_assoc($result);
		$st_qry=$row['mapped_inventory'];

		$query="SELECT itm.pr_sr,bi.invoice_no FROM inventory_items itm, bill bi WHERE itm.id=bi.item AND bi.id='$itemid'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$pr_sr=$row[0];
		$invoice_no=$row[1];

		$query="SELECT ivq.id,ivq.qty,bi.qty,ivq.item FROM inventory_qty ivq, bill_main bm,bill bi WHERE bm.invoice_no=bi.invoice_no AND ivq.item=bi.item AND ivq.location=$st_qry AND bi.id='$itemid'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$ivq_id=$row[0];
		$ivq_qty=$row[1];
		$bi_qty=$row[2];
		$ivq_item=$row[3];

		$new_ivq_qty=$ivq_qty+$bi_qty-$qty;

		$query="SELECT tm.`status`,tm.`user`,tm.from_store,tr.id FROM transfer_main tm, transfer tr WHERE tm.gtn_no=tr.gtn_no AND tm.invoice_no='$invoice_no' AND tr.item='$ivq_item'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$tr_status=$row[0];
		$tr_user=$row[1];
		$tr_fromstore=$row[2];
		$tr_id=$row[3];
		if($tr_status!=''){
			if(($tr_status=='4')&&($tr_user==$user_id)){
				$query="SELECT ivq.id,ivq.qty FROM inventory_qty ivq WHERE ivq.location='$tr_fromstore' AND ivq.item='$ivq_item'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$ivq_id2=$row[0];
				$ivq_qty2=$row[1];
				$new_ivq_qty2=$ivq_qty2+$bi_qty-$qty;
				if(($ivq_qty2+$bi_qty)>=$qty){
					$query="UPDATE `transfer` SET `qty`='$qty' WHERE `id`='$tr_id'";
					$result1=mysqli_query($conn,$query);
					if(!$result1){ $out=false; $msg='Related GTN Quantity cannot be modified [112]'; }else{
						$query="UPDATE `inventory_qty` SET `qty`='$new_ivq_qty' WHERE `id`=$ivq_id";
						$result2=mysqli_query($conn,$query);
						if(!$result2){ $out=false; $msg='Related GTN Quantity cannot be modified [113]'; }else{
							$out=true;
							$ivq_id=$ivq_id2;
							$ivq_qty=$ivq_qty2;
							$new_ivq_qty=$new_ivq_qty2;
						}
					}
				}
			}else{ $out=false; $msg='Please contract related GTN owner to Modify QTY!'; }
		}else{ $out=true; }

	if($out){
		if((($ivq_qty+$bi_qty)>=$qty)||($pr_sr==2)){
			$query="UPDATE `bill` SET `qty`='$qty' WHERE `id`=$itemid";
			$result1=mysqli_query($conn,$query);
			if($result1){
				if($pr_sr==1){
					$query="UPDATE `inventory_qty` SET `qty`='$new_ivq_qty' WHERE `id`=$ivq_id";
					$result2=mysqli_query($conn,$query);
					processInventoryNew($ivq_item,$itemid,$st_qry,'bill');
					if($result2){ $out=true;	$msg='Item QTY was Updated!'; }
				}else{
					$out=true;	$msg='Item QTY was Updated!';
				}
			}
		}else{
			$msg='Insufficient Quantity in the Inventory!';
		}
	}

	$message=$msg;
	if($out) return true; else return false;
}

function removeBillitem(){
	global $message,$salesman,$cust,$invoice_no;
	$itemid=$_REQUEST['id'];
	$salesman=$_REQUEST['s'];
	$cust=$_REQUEST['cust'];
	$user_id=$_COOKIE['user_id'];
	//$store=$_COOKIE['store'];
	$output=$result7=$force_permit=false;
	$sn_remove='';
	include('config.php');
		$result = mysqli_query($conn,"SELECT bm.mapped_inventory FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bi.id='$itemid'");
		$row = mysqli_fetch_assoc($result);
		$store=$row['mapped_inventory'];
		$query="SELECT itm.pr_sr,bi.invoice_no FROM inventory_items itm, bill bi WHERE itm.id=bi.item AND bi.id='$itemid'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$pr_sr=$row[0];
		$invoice_no=$row[1];

		$query="SELECT ivq.id,ivq.qty,bi.qty,(bi.unit_price+bi.discount),bi.cost,ivq.w_price,ivq.r_price,ivq.c_price,ivq.item,bi.no_update,bm.`lock`,bm.`type`,bm.`status`,bm.invoice_no FROM inventory_qty ivq, bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND ivq.item=bi.item AND ivq.location='$store' AND bi.id='$itemid'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$ivq_id=$row[0];
		$ivq_qty=$row[1];
		$bi_qty=$row[2];
		$bi_price=$row[3];
		$bi_cprice=$row[4];
		$ivq_wprice=$row[5];
		$ivq_rprice=$row[6];
		$ivq_cprice=$row[7];
		$ivq_item=$row[8];
		$bi_noupdate=$row[9];
		$bm_lock=$row[10];
		$bm_type=$row[11];
		$bm_status=$row[12];
		$invoice_no=$row[13];

		$new_ivq_qty=$ivq_qty+$bi_qty;

		$query="SELECT tm.`status`,tm.`user`,tm.from_store,tr.id FROM transfer_main tm, transfer tr WHERE tm.gtn_no=tr.gtn_no AND tm.invoice_no='$invoice_no' AND tr.item='$ivq_item'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$tr_status=$row[0];
		$tr_user=$row[1];
		$tr_fromstore=$row[2];
		$tr_id=$row[3];
		if($tr_status!=''){
			if(($tr_status=='4')&&($tr_user==$user_id)){
				$query="SELECT ivq.id,ivq.qty FROM inventory_qty ivq WHERE ivq.location='$tr_fromstore' AND ivq.item='$ivq_item'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$ivq_id2=$row[0];
				$ivq_qty2=$row[1];
				$new_ivq_qty2=$ivq_qty2+$bi_qty;
					$query1="DELETE FROM `transfer` WHERE `id`='$tr_id'";
					$result1=mysqli_query($conn,$query1);
					if(!$result1){ $out=false; $msg='Related GTN Quantity cannot be modified [112]'; }else{
						$query="UPDATE `inventory_qty` SET `qty`='$new_ivq_qty' WHERE `id`='$ivq_id'";
						$result2=mysqli_query($conn,$query);
						if(!$result2){ $out=false; $msg='Related GTN Quantity cannot be modified [113]'; }else{
							$out=true;
							$ivq_id=$ivq_id2;
							$ivq_qty=$ivq_qty2;
							$new_ivq_qty=$new_ivq_qty2;
						}
					}
			}else{ $out=false; $msg='Please contract related GTN owner to Modify QTY!'; }
		}else{ $out=true; }


	if(($bm_type==4 || $bm_type==5)&&($bm_status==2)&&($out)) $force_permit=true;
	if(($bm_lock==0)||($force_permit)){
		if($pr_sr==1){
			if(($bi_noupdate==0)||($bi_noupdate==999999999)){
				$query="UPDATE `inventory_qty` SET `qty`='$new_ivq_qty' WHERE `id`=$ivq_id";
				$result7=mysqli_query($conn,$query);
			}else{
				$query="INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`) VALUES ('$ivq_item','$ivq_wprice','$ivq_rprice','$ivq_cprice','$ivq_qty','$store')";
				$result2=mysqli_query($conn,$query);
				$query="SELECT w_price,r_price,c_price FROM inventory_temp WHERE id='$bi_noupdate'";
				$result=mysqli_query($conn,$query);
				while($row=mysqli_fetch_array($result)){
					$tt_wprice=$row[0];
					$tt_rprice=$row[1];
					$tt_cprice=$row[2];
				}
				$query="UPDATE `inventory_qty` SET `w_price`='$tt_wprice',`r_price`='$tt_rprice',`c_price`='$tt_cprice',`qty`='$bi_qty' WHERE `id`=$ivq_id";
				$result7=mysqli_query($conn,$query);
			}
		}
		if(($result7)||($pr_sr==2)||($pr_sr==3)){
			$query="SELECT qty,`comment` FROM bill WHERE id='$itemid'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$bill_qty=$row[0];
			$sn_remove=$row[1];

			$query6="DELETE FROM `bill` WHERE `id` = '$itemid'";
			$result6=mysqli_query($conn,$query6);
			if((($pr_sr==2)||($pr_sr==3))&($result6)) $output=true;
		}

		if($pr_sr==1){
			if($result6){
				$output=true;
				$old_bill_id=$old_invo_no='';
				if($bill_qty<0){
					$query="SELECT id,invoice_no FROM bill WHERE qty>0 AND `comment` LIKE '%$sn_remove%'";
					$row=mysqli_fetch_row(mysqli_query($conn,$query));
					$old_bill_id=$row[0];
					$old_invo_no=$row[1];
					$query2="UPDATE `inventory_unic_item` SET `invoice_no`='$old_invo_no',`bill_id`='$old_bill_id',`status`='1' WHERE `bill_id`='$itemid'";
					$result2=mysqli_query($conn,$query2);
				}else{
					$query2="UPDATE `inventory_unic_item` SET `invoice_no`='0',`bill_id`='0',`status`='0' WHERE `bill_id`='$itemid'";
					$result2=mysqli_query($conn,$query2);
				}
			}
		}

			if($output){
				$message='Item was Removed from Invoice!';
				return true;
			}else{
				$message='Item could not be Removed!';
				return false;
			}
	}else{
		$message='You Canot Remove Items from a Finalyze Invoice';
		return false;
	}
}

function getInvoiceItems(){
	global $bill_id,$bi_desc,$bi_desc2,$bi_code,$bi_qty,$bi_price,$bi_drawer,$bi_no_update,$bill_cross_tr,$dups,$dups_count,$total,$item_filter,$bm_sys_user0;
	$pr_sr=$bm_sys_user0='';
	$bill_id=array();
	if(isset($_REQUEST['id'])){
	$invoice_no=$_REQUEST['id'];
	$store=$_COOKIE['store'];
	$temp_item='';
	$total=$remove_count=0;
	$dups=$dups_count=$bi_desc=$bi_desc2=$bill_cross_tr=array();
	include('config.php');
		$query="SELECT sys_user FROM bill_main WHERE invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$bm_sys_user0=$row[0];

		$query="SELECT bi.id,inv.description,bi.qty,bi.unit_price,ivq.drawer_no,bi.no_update,inv.code,inv.pr_sr,bi.cross_trans FROM bill bi, inventory_items inv LEFT JOIN inventory_qty ivq ON inv.id=ivq.item AND ivq.location='$store' WHERE bi.item=inv.id AND bi.invoice_no='$invoice_no' ORDER BY bi.id";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$unic_sn='';
			$bill_id_tmp=$row[0];
			$bill_id[]=$row[0];
			$result1 = mysqli_query($conn,"SELECT sn FROM inventory_unic_item WHERE bill_id='$bill_id_tmp' AND `status`=1");
			$row1 = mysqli_fetch_assoc($result1);
			$unic_sn=$row1['sn'];
			if($unic_sn!=''){
				$bi_desc[]=$row[1].'<br/>[ '.$unic_sn.' ]';
			}else{
				$bi_desc[]=$row[1];
			}
			$bi_desc2[]=$row[1];
			$bi_qty[]=$row[2];
			$bi_price[]=$row[3];
			$bi_drawer[]=$row[4];
			$bi_no_update[]=$row[5];
			$bi_code[]=$row[6];
			$pr_sr=$row[7];
			$bill_cross_tr[]=$row[8];
			$total+=$row[2]*$row[3];
	}

	foreach(array_count_values($bi_desc2) as $val => $c)
    if($c > 1){ $dups[] = $val; $dups_count[] = $c;	 }
    }
	if($pr_sr=='') $item_filter='all';
	if($pr_sr==1) $item_filter=1;
	if($pr_sr==2) $item_filter=2;
	if($pr_sr==3) $item_filter=3;
}

function getMasterCust($cust){
	$cust_mtype='normal';
	include('config.php');

	$query="SELECT master_cust FROM cust WHERE id='$cust'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$master_cust=$row[0];
	if($master_cust>0){
		$cust_mtype='secondary';
	}else{
		$query="SELECT COUNT(id) FROM cust WHERE master_cust='$cust'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		if($row[0]>0)	$cust_mtype='primary'; else $cust_mtype='normal';
	}
	return $cust_mtype;
}

function validateInvoice(){
	$invoice_no=$_GET['invoice_no'];
	$cust=$_GET['cust'];
	include('config.php');
	$result1 = mysqli_query($conn,"SELECT count(invoice_no) as `count` FROM bill_main WHERE `cust`='$cust' AND invoice_no='$invoice_no'");
	$row1 = mysqli_fetch_assoc($result1);
	$cust_invoice_count=$row1['count'];
	$result1 = mysqli_query($conn,"SELECT count(invoice_no) as `count` FROM payment WHERE invoice_no='$invoice_no'");
	$row1 = mysqli_fetch_assoc($result1);
	$payment_count=$row1['count'];

	if($cust_invoice_count>0){
		return 'payment count: '.$payment_count;
	}else{
		if($invoice_no=='')
			return 'Empty';
		else
			return 'Invalid';
	}
}

// updated by nirmal 03_11_2023
function generalPrint(){
	global $print_time,$key_dev_name,$tm_web,$tm_email, $trn_no;
	$key_dev_name='';

	$isMobile=isMobile();
	if($isMobile)	include('config.php');	else include('../../../../config.php');

	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone=$row['value'];
	$print_time=date("Y-m-d H:i:s",time()+(60*60*$timezone));

	if(isset($_COOKIE['rsaid'])){
		$key=$_COOKIE['rsaid'];
		$result = mysqli_query($conn,"SELECT `name` FROM devices WHERE `key`='$key'");
		$row = mysqli_fetch_assoc($result);
		$key_dev_name=$row['name'];
	}

	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='web'");
	$row = mysqli_fetch_assoc($result);
	$tm_web=$row['value'];

	$store = $_COOKIE['store'];
	$result = mysqli_query($conn,"SELECT `email` FROM stores WHERE `id`='$store'");
	// $result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='email'");
	$row = mysqli_fetch_assoc($result);
	$tm_email=$row['email'];

	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='trn_no'");
	$row = mysqli_fetch_assoc($result);
	$trn_no=$row['value'];
}

// update by Gayan 2023_06_16
// updated by nirmal 02_08_2023 (fixed tax added value showing)
// updated by nirmal 08_09_2023 (fixed bill type 2 getting more service item becasue of extra table joined)
// update by nirmal 01_11_2023 (get emp name instead username for systemid=13),14_12_2023 deliver note emp name
// updated by nirmal 03_01_2024 (get customer outstanding to invoice print (sysid = 1 & 17))
function generateInvoice($order_by){
	global $tm_company,$tm_address,$tm_tel,$chq0_fullNo,$bill_id,$bi_desc,$bi_code,$bi_discount,$bi_qty,
	$bi_price,$total,$ledc2,$bi_drawer,$bi_type,$pay_id,$cash_amount,$bank_amount,$chque_amount,$chq0_date,
	$bi_cust0,$bi_cust0_address,$bi_cust,$bi_salesman_id,$up_salesman,$bi_date,$bi_time,$cu_id,$cu_details,
	$up_mobile,$bm_status,$bm_quotation_no,$qm_warranty,$qm_terms,$qm_po,$bm_packed_by,$tm_shop,$bm_print_st,
	$bm_bocom_type,$bm_bocom,$bi_repair_sn,$up_packedby,$pay_type,$bi_return_odr,$return_odr,$bill_cross_tr,
	$hire_purchase,$hp_type,$hp_amount,$hp_schedule,$hp_schedule_remark,$hp_pay_id,$card_amount,$pay_pro_fee,$card_full_data, $decimal, $tax, $tax_added_value, $cust_tax_no, $sub_total, $credit_balance;

	$invoice_no=$_REQUEST['id'];
	$chq0_no=$chq0_bnk=$chq0_branch=$bm_packed_by='';
	$cash_amount=$bank_amount=$chque_amount=$pay_pro_fee=0;
	$username_array=$usermobile_array=$sn_list=$bill_cross_tr=$bill_id=$hp_schedule=$hp_schedule_remark=$hp_pay_id=array();
	$return_odr=$hire_purchase=false;
	$username_array['']='';
	$usermobile_array['']='';

	$isMobile=isMobile();
	if($isMobile)	include('config.php');	else include('../../../../config.php');

	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='paper_size'");
	$row = mysqli_fetch_assoc($result);
    $paper_size=$row['value'];
    if($paper_size=='A4') $break_point=3;
    if($paper_size=='A5') $break_point=2;
	if($isMobile) $break_point=1;

	$result1 = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='decimal'");
	$row1 = mysqli_fetch_assoc($result1);
	$decimal = $row1['value'];

	$result1 = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='systemid'");
	$row1 = mysqli_fetch_assoc($result1);
	$systemid = $row1['value'];

	$result2 = mysqli_query($conn,"SELECT value FROM settings WHERE setting='tax'");
	$row2 = mysqli_fetch_assoc($result2);
	$tax=$row2['value'];

	$user_name_column = 'username';
	if($systemid == 13){
		$user_name_column = 'emp_name';
	}
	$query="SELECT `id`,$user_name_column,`mobile` FROM userprofile";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$username_array[$row[0]]=$row[1];
		$usermobile_array[$row[0]]=$row[2];
	}

	$query="SELECT bm.`type`,cu.name,cu.shop_address,bm.billed_by,bm.packed_by,date(bm.billed_timestamp),time(bm.billed_timestamp),bm.`store`,cu.id,cu.nic,cu.mobile,cu.`status`,bm.`status`,bm.mapped_inventory,bm.quotation_no,bm.packed_by,bm.print_st,bm.back_off_com_type,bm.back_off_comment,bm.recovery_agent FROM bill_main bm, cust cu WHERE  bm.`cust`=cu.id AND bm.invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$bi_type=$row[0];
	if($row[11]==2) $bi_cust='Customer : '.$row[1].'<br />NIC: '.$row[9].' &nbsp;&nbsp; Mobile: '.$row[10]; else $bi_cust='Customer : '.$row[1];
	$bi_cust0=$row[1];
	$bi_cust0_address=$row[2];
	$bi_salesman_id=$row[3];
	$up_salesman=$username_array[$row[3]];
	$up_packedby=$username_array[$row[4]];
	$bi_date=$row[5];
	$bi_time=$row[6];
	$store=$row[7];
	$cu_id=$row[8];
	$cu_details='NIC        : '.$row[9].'&#13;Mobile  : '.$row[10];
	$up_mobile=$usermobile_array[$row[3]];
	$bm_status=$row[12];
	$bm_mapped_inventory=$row[13];
	$bm_quotation_no=$row[14];
	$bm_packed_by0=$row[15];
	$bm_print_st=$row[16];
	$bm_bocom_type=$row[17];
	$bm_bocom=$row[18];
	$bm_recovery_agent=$row[19];

	if(($bi_type==1)||($bi_type==4)){
		$query="SELECT bi.id,itm.description,bi.qty,bi.unit_price,itm.id,bi.`comment`,itm.`code`,bi.discount,itm.unic,bi.repair_model,bi.repair_sn,bi.return_odr_replace,bi.cross_trans,itq.drawer_no FROM bill bi, inventory_items itm, inventory_qty itq WHERE bi.item=itm.id AND itm.id=itq.item AND itq.location='$bm_mapped_inventory' AND bi.invoice_no='$invoice_no' ORDER BY $order_by";
	}else{
		$query="SELECT bi.id,itm.description,bi.qty,bi.unit_price,itm.id,bi.`comment`,itm.`code`,bi.discount,itm.unic,bi.repair_model,bi.repair_sn,bi.return_odr_replace,bi.cross_trans FROM bill bi, inventory_items itm WHERE  bi.item=itm.id AND bi.invoice_no='$invoice_no' ORDER BY bi.id";
	}

	// added by nirmal 27_07_2023
	$query2 = "SELECT `tax` FROM bill_main WHERE `invoice_no`='$invoice_no'";
	$row2=mysqli_fetch_row(mysqli_query($conn2,$query2));
	$tax_added_value=$row2[0];

	// added by nirmal 18_08_2023
	$query3 = "SELECT `tax_no` FROM cust WHERE `id`='$cu_id'";
	$row3=mysqli_fetch_row(mysqli_query($conn2,$query3));
	$cust_tax_no=$row3[0];

	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$bill_id_tmp=$row[0];
		$bill_id[]=$row[0];
		if($row[9]!=''||$row[10]!='') $bi_repair_sn="Model- $row[9]<br />SN- $row[10]<br />"; else $bi_repair_sn="";
		if(($bi_type==1)&&($row[8]==1)){
			$unic_sn='';
			$k=1;
		    $sn_list=explode(",",$row[5]);
			for($i=0;$i<sizeof($sn_list);$i++){
				if($k==$break_point){ $break_unic='<br />'; $k=0; }else{ $break_unic='&nbsp;&nbsp;'; }
				$unic_sn=$unic_sn.'['.$sn_list[$i].']'.$break_unic;
				$k++;
			}
			if($unic_sn!=''){
				$bi_desc[]=$row[1].'<br />'.$unic_sn;
			}else{
				$bi_desc[]=$row[1].'<br /><br />';
			}
		}else if(($bi_type==2)||($bi_type==3)||($bi_type==5)) $bi_desc[]='['.$row[1].']<br />'.$row[5].'<br />'.$bi_repair_sn;
		else if((($bi_type==1)||($bi_type==4))&&($row[8]==0)) $bi_desc[]=$row[1].'<br />';
		$bi_qty[]=$row[2];
		$bi_price[]=$row[3];
		if($row[11]==0)	$total+=($row[2]*$row[3]); else $return_odr=true;
		$ledc2[]=str_repeat('_',(12-strlen(number_format($row[2]*$row[3]))));
		$bi_code[]=$row[6];
		$bi_discount[]=$row[7];
		$bi_return_odr[]=$row[11];
		$bill_cross_tr[]=$row[12];
		$item_id=$row[4];
		if(($bi_type==1)||($bi_type==4)) $bi_drawer[]=$row[13]; else $bi_drawer[]='<br /><br /><br />';
	}
	$sub_total = $total - $tax_added_value;

	$rep_comment='';
	$query1="SELECT rc.`comment` FROM repair_comment rc WHERE rc.private_public='2' AND rc.bill_no='$invoice_no'";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$rep_comment.=$row1[0];
	}
	$bm_bocom.='<br />'.$rep_comment;

	$query1="SELECT id,payment_type,SUM(amount),chque_no,chque_bank,chque_branch,chque_date,card_no,bank_trans FROM payment WHERE bill_pay=1 AND invoice_no='$invoice_no' AND `status`=0 GROUP BY payment_type";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$pay_id[]=$row1[0];
		$pay_type=$row1[1];
		if($row1[1]==1) $cash_amount=$row1[2];
		if($row1[1]==2) $chque_amount=$row1[2];
		if($row1[1]==3) $bank_amount=$row1[2];
		if($row1[1]==4) $card_amount=$row1[2];
		$chq0_no=$row1[3];
		$chq0_bnk=$row1[4];
		$chq0_branch=$row1[5];
		$chq0_date=$row1[6];
		$card_bank=$row1[8];
		$card_no=$row1[7];
	}
	if($chq0_bnk>0){
		$query2="SELECT bank_code FROM bank WHERE id=$chq0_bnk";
		$result2=mysqli_query($conn,$query2);
		while($row2=mysqli_fetch_array($result2)){ $chq0_bnk=$row2[0]; }
		$chq0_fullNo='[ Cheque No: '.$chq0_no.'-'.$chq0_bnk.'-'.$chq0_branch.' ]';
	}else $chq0_fullNo='';

	if($card_amount>0){
		$query1="SELECT `name`,`processing_fee` FROM accounts WHERE id='$card_bank'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$bank_name=$row1[0];
		$pay_pro_fee=$row1[1];
		$card_full_data='Card    : ' . $card_no;
	}else $card_full_data='';


	if($bm_quotation_no!=0){
		$result = mysqli_query($conn,"SELECT warranty,terms2,cust_po FROM quotation_main WHERE id='$bm_quotation_no'");
		$row = mysqli_fetch_assoc($result);
		$qm_warranty=$row['warranty'];
		$qm_terms=$row['terms2'];
		$qm_po=$row['cust_po'];
	}
	if($bm_packed_by0!=''){
		$result = mysqli_query($conn,"SELECT $user_name_column FROM userprofile WHERE `id`='$bm_packed_by0'");
		$row = mysqli_fetch_assoc($result);
		$bm_packed_by=$row[$user_name_column];
	}

	//--------------------------Hire_Purchase--------------------------------------------------------//
	if($bm_recovery_agent!=''){
		$hire_purchase=true;
		$query="SELECT his.cal_start_date,hst.`name`,his.`day`,his.payment_amount,his.payment_count FROM hp_inv_schedule his, hp_schedule_type hst WHERE his.`type`=hst.id AND his.invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$hp_cal_start=$row[0];
		$hp_type=$row[1];
		$hp_date=$row[2];
		$hp_amount=$row[3];
		$hp_count=$row[4];

		$hp_schedule=hpsPaySchedule($hp_cal_start,$hp_type,$hp_date,$hp_count);
		for($i=1;$i<=sizeof($hp_schedule);$i++){
			$instalment_date=$hp_schedule[$i];
			$hp_schedule_remark[$i]='';
			$query="SELECT SUM(py.amount) FROM hp_payments hpy, payment py WHERE hpy.payment_no=py.id AND py.`status`=0 AND py.chque_return=0 AND py.invoice_no='$invoice_no' AND hpy.instalment_date='$instalment_date'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			if($row[0]!=''){
				if($row[0]<$hp_amount)	$hp_schedule_remark[$i]='Partially Paid';
				if($row[0]==$hp_amount)	$hp_schedule_remark[$i]='Fully Paid';
				if($row[0]>$hp_amount)	$hp_schedule_remark[$i]='Over Paid';
			}
		}
	}

	//-----------------------------------------------------------------------------------------------//
	$result = mysqli_query($conn,"SELECT name,shop_name,address,tel FROM stores WHERE id='$bm_mapped_inventory'");
	$row = mysqli_fetch_assoc($result);
	$tm_shop=$row['name'];
	$tm_company=$row['shop_name'];
	$tm_address=$row['address'];
	$tm_tel=$row['tel'];

	//------------------------------------ Customer total outstanding upto now ---------------------------------------//
	customerTotalOutstandingBalance($cu_id);
}

function customerTotalOutstandingBalance($cu_id){
	global $credit_balance;

	$isMobile=isMobile();
	if($isMobile)	include('config.php');	else include('../../../../config.php');

	$query1="SELECT SUM(bi.qty*bi.unit_price) as `total` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`cust`='$cu_id'";
	$result1 = mysqli_query($conn,$query1);
	$row1 = mysqli_fetch_assoc($result1);
	$totalinv=$row1['total'];

	$query1="SELECT SUM(py.amount) as `pay` FROM payment py WHERE py.`status`=0 AND py.`cust`='$cu_id' AND py.chque_return=0";
	$result1 = mysqli_query($conn,$query1);
	$row1 = mysqli_fetch_assoc($result1);
	$totalpay=$row1['pay'];
	$credit_balance=$totalinv-$totalpay;
}

// update by nirmal 22_11_2023 (get drawer no to return pending items)
function generateReturnList(){
	global $rt_code,$rt_desc,$rt_qty,$rt_pending_code,$rt_pending_desc,$rt_pending_qty,$return_cr_bal,$removed_code,$removed_desc,$removed_qty, $rt_pending_itm_drawer;
	$invoice_no=$_GET['id'];
	$rt_code=$rt_desc=$rt_qty=$rt_pending_code=$removed_code=$rt_pending_itm_drawer=array();
	$isMobile=isMobile();
	if($isMobile)	include('config.php');	else include('../../../../config.php');
	$query="SELECT `cust`,`mapped_inventory` FROM bill_main WHERE invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$rm_cust=$row[0];
	$bm_mapped_inventory=$row[1];

	$query="SELECT itm.code,itm.description,rt.qty FROM return_main rm, `return` rt, inventory_items itm WHERE rm.invoice_no=rt.invoice_no AND rt.replace_item=itm.id AND rm.`status`='2' AND rt.odr_packed='1' AND rt.odr_no='$invoice_no' ORDER BY itm.description";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		if($row[0]!='CREDIT RTN'){
			$rt_code[]=$row[0];
			$rt_desc[]=$row[1];
			$rt_qty[]=$row[2];
		}
	}
	$query="SELECT itm.code,itm.description,rt.qty,itm.id FROM return_main rm, `return` rt, inventory_items itm WHERE rm.invoice_no=rt.invoice_no AND rt.replace_item=itm.id AND rm.`status`='2' AND rt.odr_packed='0' AND rm.`cust`='$rm_cust' ORDER BY itm.description";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$rt_pending_code[]=$row[0];
		$rt_pending_desc[]=$row[1];
		$rt_pending_qty[]=$row[2];
		$rt_pending_id=$row[3];

		$query1 = "SELECT itm.drawer_no FROM inventory_qty itm WHERE itm.location = '$bm_mapped_inventory' AND itm.item = '$rt_pending_id'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		if (empty($row1)) {
			$rt_pending_itm_drawer[] = '';
		} else {
			if($row1[0] != ''){
				$rt_pending_itm_drawer[] = $row1[0];
			}else{
				$rt_pending_itm_drawer[] = '';
			}
		}
	}
	$query="SELECT itm.code,itm.description,rj.qty FROM return_remove_job rj, inventory_items itm WHERE rj.replace_item_rm=itm.id AND rj.odr_no='$invoice_no' ORDER BY itm.description";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$removed_code[]=$row[0];
		$removed_desc[]=$row[1];
		$removed_qty[]=$row[2];
	}
	$query="SELECT SUM(py.amount) FROM return_remove_job rj, payment py WHERE rj.payment_inv=py.id AND rj.odr_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$return_cr_bal=-1*$row[0];
	$query="SELECT SUM(unit_price*qty) FROM bill WHERE return_odr_replace='1' AND invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$return_cr_bal=$return_cr_bal-$row[0];
}

function generateInvoiceFast(){
	global $cash_amount,$chque_amount,$chq0_fullNo,$up_salesman,$bi_cust,$total;
	$chq0_no=$chq0_bnk=$chq0_branch='';
	$cash_amount=$chque_amount=0;
	$invoice_no=$_REQUEST['id'];
	include('config.php');

	$result1 = mysqli_query($conn,"SELECT DISTINCT up.username,cu.name,SUM(bi.qty * bi.unit_price) as `total` FROM bill_main bm, bill bi, userprofile up, cust cu WHERE bm.invoice_no=bi.invoice_no AND bm.billed_by=up.id AND bm.`cust`=cu.id AND bi.invoice_no='$invoice_no'");
	$row1 = mysqli_fetch_assoc($result1);
	$up_salesman=$row1['username'];
	$bi_cust=$row1['name'];
	$total=$row1['total'];

		$query1="SELECT id,payment_type,amount,chque_no,chque_bank,chque_branch FROM payment WHERE bill_pay=1 AND invoice_no='$invoice_no'";
		$result1=mysqli_query($conn,$query1);
		while($row1=mysqli_fetch_array($result1)){
			$pay_id[]=$row1[0];
			if($row1[1]==1) $cash_amount=$row1[2];
			if($row1[1]==2) $chque_amount=$row1[2];
			$chq0_no=$row1[3];
			$chq0_bnk=$row1[4];
			$chq0_branch=$row1[5];
		}
	if($chq0_bnk>0){
		$query2="SELECT bank_code FROM bank WHERE id=$chq0_bnk";
		$result2=mysqli_query($conn,$query2);
		while($row2=mysqli_fetch_array($result2)){ $chq0_bnk=$row2[0]; }
		$chq0_fullNo='[ Chque No: '.$chq0_no.'-'.$chq0_bnk.'-'.$chq0_branch.' ]';
	}else $chq0_fullNo='';
}

function billDeletePermission($invoice_no){
	include('config.php');
	$user=$_COOKIE['user_id'];
	$store=$_COOKIE['store'];
	$systemid=inf_systemid(1);
	$today=dateNow();
	$query="SELECT billed_by,date(`billed_timestamp`),`store`,`status` FROM bill_main WHERE invoice_no='$invoice_no'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$salesman=$row[0];
	$date=$row[1];
	$bm_store=$row[2];
	$bm_status=$row[3];

	if(($user==$salesman)&&($today==$date)&&($bm_status!=0)) $billpermission=true; else $billpermission=false;
	if((isset($_COOKIE['report']))&&($bm_store==$store)&&($systemid==4)&&($bm_status!=0)) $billpermission=true;
	if((isset($_COOKIE['settings']))&&($systemid==15)&&($bm_status!=0)) $billpermission=true;
	return $billpermission;
}

function billPermission(){
	global $billpermission,$bm_status,$bm_lock,$bm_type,$bm_cust,$status_out,$status_color;
	$invoice_no=$_REQUEST['id'];
	$billpermission=billDeletePermission($invoice_no);

	include('config.php');
	$query="SELECT `status`,`lock`,`type`,cust FROM bill_main WHERE invoice_no='$invoice_no'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
		$bm_status=$row[0];
		$bm_lock=$row[1];
		$bm_type=$row[2];
		$bm_cust=$row[3];

	switch($bm_status){
		case 0: $status_out='Deleted'; $status_color='#FF3300'; break;
		case 1: $status_out='Billed (Pending)'; $status_color='yellow'; break;
		case 2: $status_out='Billed (Picked)'; $status_color='yellow'; break;
		case 3: if($bm_type==3){ $status_out='Billed (Picked)'; } else { $status_out='Billed (Packed)'; } $status_color='yellow'; break;
		case 4: if($bm_type==3){ $status_out='Repaired'; }else{ $status_out='Billed (Shipped)'; } $status_color='yellow'; break;
		case 5: if($bm_type==3){ $status_out='Repaired | Delivered'; }else{ $status_out='Billed (Delivered)'; } $status_color='white'; break;
		case 6: $status_out='Rejected'; $status_color='orange'; break;
		case 7: $status_out='Rejected | Delivered'; $status_color='orange'; break;
	}
	if($bm_lock==0 && $bm_status!=0){ $status_out='Unlocked Bill';  $status_color='yellow'; }
}

function billDetails(){
	global $main_sub_system,$main_store,$main_refinv,$main_district,$main_quotation,$m_type,$main_type,$main_sms,
	$main_ordered_date,$main_billed_date,$main_billed_by,$main_billed_by_id,$main_sys_user,$main_recovery_agent,
	$main_packed_date,$main_packed_by,$main_shipped_date,$main_shipped_by,$main_deliverd_date,$main_deliverd_by,
	$main_deleted_date,$main_deleted_by,$main_total,$main_comment1,$main_comment2,$main_comment_pay,$main_sub_system_id,
	$sms_resend,$main_refinvid,$main_tracking_id,$hire_purchase,$hp_cal_start_date;

	$invoice_no=$_GET['id'];
	$today=dateNow();
	$sms_resend=0;
	$systemid=inf_systemid(1);
	include('config.php');
	$query="SELECT `id`,`username` FROM userprofile";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$usercheck[$row[0]]=$row[1];
	}
	$usercheck['']='-';
	$usercheck[0]='-';

	$query="SELECT ss.name,s1.name,s2.name,di.name,bm.quotation_no,bm.`type`,bm.sms,bm.order_timestamp,bm.billed_timestamp,bm.billed_by,bm.sys_user,bm.recovery_agent,bm.packed_timestamp,bm.packed_by,bm.shipped_timestamp,bm.shipped_by,bm.deliverd_timestamp,bm.deliverd_by,bm.deleted_timestamp,bm.deleted_by,bm.`invoice_+total`,bm.back_off_comment,bm.back_off_comment2,ss.id,bm.mapped_inventory,bm.tracking_id FROM bill_main bm, stores s1, stores s2, sub_system ss, district di WHERE bm.store=s1.id AND bm.mapped_inventory=s2.id AND bm.`sub_system`=ss.id AND bm.billed_district=di.id AND bm.invoice_no='$invoice_no'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$main_sub_system=$row[0];
	$main_store=$row[1];
	$main_refinv=$row[2];
	$main_district=$row[3];
	$main_quotation=$row[4];
	$m_type=$row[5];
	if($row[6]==1) $main_sms='SMS Sent'; else $main_sms='SMS Not Sent';
	$main_ordered_date=$row[7];
	$main_billed_date=$row[8];
	$main_billed_by=$usercheck[$row[9]];
	$main_billed_by_id=$row[9];
	$main_sys_user=$usercheck[$row[10]];
	$main_recovery_agent=$usercheck[$row[11]];
	$main_packed_date=$row[12];
	$main_packed_by=$usercheck[$row[13]];
	$main_shipped_date=$row[14];
	$main_shipped_by=$usercheck[$row[15]];
	$main_deliverd_date=$row[16];
	$main_deliverd_by=$usercheck[$row[17]];
	$main_deleted_date=$row[18];
	$main_deleted_by=$usercheck[$row[19]];
	$main_total=$row[20];
	$main_comment1=$row[21];
	$main_comment2=$row[22];
	$main_sub_system_id=$row[23];
	$main_refinvid=$row[24];
	$main_tracking_id=$row[25];
	if($main_recovery_agent=='-') $hire_purchase=false; else $hire_purchase=true;

	$query1="SELECT `comment` FROM payment WHERE bill_pay=1 AND invoice_no='$invoice_no'";
	$result1=mysqli_query($conn,$query1);
	$row1 = mysqli_fetch_row($result1);
	$main_comment_pay = is_null($row1) ? 0 : $row1[0];

	$query1="SELECT `cal_start_date` FROM hp_inv_schedule WHERE invoice_no='$invoice_no'";
	$result1=mysqli_query($conn,$query1);
	$row1 = mysqli_fetch_row($result1);
	$hp_cal_start_date = is_null($row1) ? 0 : $row1[0];

	$query1="SELECT id FROM sms WHERE `case`='1' AND `ref`='$invoice_no'";
	$result1=mysqli_query($conn,$query1);
	$row1 = mysqli_fetch_row($result1);
	$sms_resend = is_null($row1) ? 0 : $row1[0];

	switch($row[5]){
		case 1: $main_type='Sales Bill : Product'; break;
		case 2: $main_type='Sales Bill : Service'; break;
		case 3: $main_type='Repair'; break;
		case 4: $main_type='Cust Order : Product'; break;
		case 5: $main_type='Cust Order : Service'; break;
	}
}

function getRepView($sub_system,$systemid){
	global $pricename1,$pricename2;
	$pricename1="Wholesale";
	$pricename2="Retail";
	if($systemid==14){
		$salesman=$_COOKIE['user_id'];
		include('config.php');
		$result = mysqli_query($conn,"SELECT mobile_rep FROM userprofile WHERE id='$salesman'");
		$row = mysqli_fetch_assoc($result);
		if($row['mobile_rep']==1){
			$pricename1="Cash Price";
			$pricename2="Credit Price";
		}
	}
}

function billTemplate(){
	global $tm_shopname,$tm_template,$tm_address1,$tm_tel1;
	$store=$_COOKIE['store'];
	include('config.php');
	$result = mysqli_query($conn,"SELECT shop_name,billing_template,address,tel FROM stores WHERE id='$store'");
	$row = mysqli_fetch_assoc($result);
	$tm_shopname=$row['shop_name'];
	$tm_template=$row['billing_template'];
	$tm_address1=$row['address'];
	$tm_tel1=$row['tel'];
}

// updated by nirmal 07_03_2022
function today($systemid,$sub_system){
	global $invoice_no,$time,$invoice_total,$invoice_profit,$date,$lbm_type,$color,$cust,$py_id,$py_invno,$py_bill_pay,
	$py_type,$py_time,$py_amount,$py_cust,$linvoice_no,$ldate,$linvoice_total,$linvoice_profit,$lcust,$lcustid,
	$lbm_status,$bi_discount,$rt_invno,$rt_time,$rt_cust_id,$rt_cust_name,$rt_status,$lbm_salesman,$bm_sys_user,
	$lbm_sys_user,$py_sys_user,$rt_return_by,$tmp_bm_no,$tmp_date,$tmp_total,$tmp_cust,$tmp_custid,$tmp_bm_status,
	$tmp_bm_salesman,$tmp_bm_type,$tmp_bm_sys_user,$w_invno,$w_time,$w_cust_name,$w_no;

	$salesman=$_COOKIE['user_id'];
	$invoice_no=$linvoice_no=$py_id=$py_invno=$rt_invno=$tmp_bm_no=$w_invno=$w_time=$w_cust_name=$w_no=array();
	if(isset($_REQUEST['date'])){
		$date=$_REQUEST['date'];
	}else{
		$date=dateNow();
	}

	$total=0;
	if($systemid!=4){
		$salesman_qry1="AND bm.`sys_user`='$salesman'";
		$salesman_qry2="AND py.`sys_user`='$salesman'";
		$salesman_qry3="AND rm.`return_by`='$salesman'";
		$salesman_qry4="AND w.`taken_by`='$salesman'";
	}else{
		$salesman_qry1="AND bm.`sub_system`='$sub_system'";
		$salesman_qry2="AND py.`sub_system`='$sub_system'";
		$salesman_qry3="AND rm.`sub_system`='$sub_system'";
		$salesman_qry4="AND w.`sub_system`='$sub_system'";
	}

	include('config.php');

	$query="SELECT bi.invoice_no,time(bm.`billed_timestamp`),SUM(bi.qty*bi.unit_price),SUM((bi.qty*bi.unit_price)-(bi.qty*bi.cost)),cu.name,SUM(bi.discount),bm.`sys_user` FROM bill bi, bill_main bm, cust cu WHERE bi.invoice_no=bm.invoice_no AND cu.id=bm.`cust` AND bm.`status`!=0  $salesman_qry1 AND bm.`lock`!='0' AND date(bm.`billed_timestamp`)='$date' GROUP BY bi.invoice_no ORDER BY bm.`billed_timestamp`";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$invoice_no[]=$row[0];
		$time[]=$row[1];
		$invoice_total[]=$row[2];
		$invoice_profit[]=$row[3];
		$cust[]=$row[4];
		$bi_discount[]=$row[5];
		$bm_sys_user[]=$row[6];
	}

	$query="SELECT bi.invoice_no,date(bi.date),SUM(bi.qty*bi.unit_price),SUM((bi.qty*bi.unit_price)-(bi.qty*bi.cost)),cu.name,cu.id,bm.`status`,bm.billed_by,bm.`type`,bm.`sys_user` FROM bill bi, bill_main bm, cust cu WHERE bi.invoice_no=bm.invoice_no AND cu.id=bm.`cust` AND bm.`status`!=0  $salesman_qry1 AND bm.`lock`='0' GROUP BY bi.invoice_no ORDER BY bm.`billed_timestamp`";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$linvoice_no[]=$row[0];
		$ldate[]=$row[1];
		$linvoice_total[]=$row[2];
		$linvoice_profit[]=$row[3];
		$lcust[]=$row[4];
		$lcustid[]=$row[5];
		$lbm_status[]=$row[6];
		$lbm_salesman[]=$row[7];
		$lbm_type[]=$row[8];
		$lbm_sys_user[]=$row[9];
	}

	$query="SELECT bm.bm_no,date(bm.order_timestamp),SUM(bi.qty*bi.unit_price),cu.name,cu.id,bm.`status`,bm.billed_by,bm.`type`,bm.`sys_user` FROM bill_tmp bi, bill_main_tmp bm, cust cu WHERE bi.bm_no=bm.bm_no AND cu.id=bm.`cust` AND bm.`status`!=0  $salesman_qry1 GROUP BY bm.bm_no ORDER BY bm.`order_timestamp`";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$tmp_bm_no[]=$row[0];
		$tmp_date[]=$row[1];
		$tmp_total[]=$row[2];
		$tmp_cust[]=$row[3];
		$tmp_custid[]=$row[4];
		$tmp_bm_status[]=$row[5];
		$tmp_bm_salesman[]=$row[6];
		$tmp_bm_type[]=$row[7];
		$tmp_bm_sys_user[]=$row[8];
	}

	$query="SELECT py.id,py.invoice_no,py.bill_pay,py.payment_type,time(py.payment_date),py.amount,cu.name,py.`sys_user` FROM payment py, cust cu WHERE py.`cust`=cu.id AND py.`status`='0' $salesman_qry2 AND date(py.payment_date)='$date' ORDER BY py.payment_date";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$py_id[]=$row[0];
		$py_invno[]=$row[1];
		$py_bill_pay[]=$row[2];
		$py_type[]=$row[3];
		$py_time[]=$row[4];
		$py_amount[]=$row[5];
		$py_cust[]=$row[6];
		$py_sys_user[]=$row[7];
	}

	$query="SELECT rm.invoice_no,time(rm.return_date),cu.id,cu.name,rm.`status`,rm.return_by FROM `return_main` rm, `return` rt, cust cu WHERE rm.invoice_no=rt.invoice_no AND rm.`cust`=cu.id $salesman_qry3 AND (( date(rm.return_date)='$date' AND rm.`status`='2') OR (rm.`status`='1') ) GROUP BY rm.invoice_no ORDER BY rm.return_date";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$rt_invno[]=$row[0];
		$rt_time[]=$row[1];
		$rt_cust_id[]=$row[2];
		$rt_cust_name[]=$row[3];
		$rt_status[]=$row[4];
		$rt_return_by[]=$row[5];
	}

	// warranty details
	$query="SELECT w.`bm_inv`, w.`id`, time(w.`claim_date`), c.`name` FROM warranty w, cust c WHERE w.`customer` = c.`id` $salesman_qry4 AND date(w.`claim_date`)='$date' AND w.`status` != '0' ORDER BY w.`claim_date`";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$w_invno[]=$row[0];
		$w_no[]=$row[1];
		$w_time[]=$row[2];
		$w_cust_name[]=$row[3];
	}
}

function deleteGTN($n,$invoice_no){
	$salesman=$_COOKIE['user_id'];
	$result2=$out=false;
	$authorization=true;
	$datetime=timeNow();

	if($n==1) include('config.php'); else
	if($n==2) include('../config.php');
	$query="SELECT count(gtn_no) FROM transfer_main WHERE invoice_no='$invoice_no' AND `status` IN (0,4,5)";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$count=$row[0];
	if($count==0) $authorization=false;
	if($invoice_no==''||$invoice_no==0) $authorization=false;

	if($authorization){
		$query1="SELECT ivq.id,tr.qty,tm.gtn_no,tm.from_store,tm.to_store,tr.w_price,tr.r_price,tr.c_price,ivq.w_price,ivq.r_price,ivq.c_price,ivq.item,tr.no_update,tr.id FROM inventory_qty ivq, transfer_main tm, transfer tr WHERE tm.gtn_no=tr.gtn_no AND ivq.item=tr.item AND ivq.location=tm.from_store AND tm.`invoice_no`='$invoice_no' ORDER BY tr.id DESC";
		$result1=mysqli_query($conn,$query1);
		while($row1=mysqli_fetch_array($result1)){
			$ivq_id=$row1[0];
			$tr_qty=$row1[1];
			$gtn_no=$row1[2];
			$fromstore=$row1[3];
			$tostore=$row1[4];
			$tr_wprice=$row1[5];
			$tr_rprice=$row1[6];
			$tr_cprice=$row1[7];
			$ivq_wprice=$row1[8];
			$ivq_rprice=$row1[9];
			$ivq_cprice=$row1[10];
			$ivq_item=$row1[11];
			$bi_noupdate=$row1[12];
			$tr_id=$row1[13];

			$debug_id=debugStart($tr_id,$fromstore);
			if(($bi_noupdate==0)||($bi_noupdate==2)){
				$query7="UPDATE `inventory_qty` SET `qty`=qty+$tr_qty WHERE `id`='$ivq_id'";
				$result7=mysqli_query($conn,$query7);
			}else{
				$result = mysqli_query($conn,"SELECT qty FROM inventory_qty WHERE `id`=$ivq_id");
				$row = mysqli_fetch_assoc($result);
				$ivq_qty=$row['qty'];
				$query="INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`) VALUES ('$ivq_item','$ivq_wprice','$ivq_rprice','$ivq_cprice','$ivq_qty','$fromstore')";
				$result2=mysqli_query($conn,$query);
				$query7="UPDATE `inventory_qty` SET `w_price`='$tr_wprice',`r_price`='$tr_rprice',`c_price`='$tr_cprice',`qty`='$tr_qty' WHERE `id`=$ivq_id";
				$result7=mysqli_query($conn,$query7);
			}

			if($result7){
				$query2="UPDATE `inventory_unic_item` SET `trans_no`='0',`trans_id`='0',`status`='0' WHERE `trans_id`='$tr_id' AND `status`='3'";
				$result2=mysqli_query($conn,$query2);
				debugEnd($debug_id,'success');
			}else{
				debugEnd($debug_id,'fail');
			}
		}

		if($result7){
			$query6="UPDATE `transfer_main` SET `status`='3' ,`action_date`='$datetime' WHERE `invoice_no`='$invoice_no'";
			$result6=mysqli_query($conn,$query6);
		}

		if($result6) $out=true; else $out=false;
	}else{
		$out=false;
	}
	return $out;
}

function deleteTmpBill(){
	global $message;
	$bm_no=$_GET['bm_no'];
	$user_id=$_COOKIE['user_id'];
	$proceed=true;
	$message='TMP Bill was Deleted Successfully';

	include('config.php');
	$query="SELECT COUNT(bm_no) FROM bill_main_tmp WHERE bm_no='$bm_no' AND (billed_by='$user_id' OR sys_user='$user_id')";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$count=$row[0];
	if($count==0){ $proceed=false; $message='You dont have Permission to Delete this TMP Bill'; }
	if($proceed){
		$query="DELETE FROM `bill_tmp` WHERE `bm_no` = '$bm_no'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $proceed=false; $message='Error: Failed to Delete TMP Bill Items'; }
	}
	if($proceed){
		$query="DELETE FROM `bill_main_tmp` WHERE `bm_no` = '$bm_no'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $proceed=false; $message='Error: Failed to Delete TMP Bill'; }
	}
	return $proceed;
}

// update by nirmal 25_02_2022, update 12_12_2023 : add quickbooks invoice delete
function deleteInvoice($n,$force){
	global $message;
	$invoice_no=$_REQUEST['id'];
	$user_id=$_COOKIE['user_id'];
	$time_now=timeNow();
	$result7=$result6=$proceed=false;
	$itn_id=$qb_msg='';
	$message='Invoice was deleted successfully!';
	if($n==1) include('config.php'); else
	if($n==2) include('../config.php');

	if($force==1){
		$proceed=true;
	}else{
		if(billDeletePermission($invoice_no)){
			$proceed=true;
		}else{
			$proceed=false;
			$message='Error: Unauthorize request!';
		}
	}
	//----------------To Compatible With Backend Delete Requests----------------------//
	$result = mysqli_query($conn2,"SELECT value FROM settings WHERE setting='uniq_item_cal'");
	$row = mysqli_fetch_assoc($result);
	if($row['value']=='True') $unic_cal=true; else $unic_cal=false;
	//-------------------------------------------------------------------------------//
	$query="SELECT bi.qty,itm.unic FROM bill_main bm, bill bi, inventory_items itm WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND bm.invoice_no='$invoice_no'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		if(($row[0]<0) && ($row[1]==1) && (!$unic_cal)){ $proceed=false;
			$message='You cannot delete a Cash Back invoice if unique item contains!';
		}
	}

	if($proceed){
		$result = mysqli_query($conn,"SELECT bm.mapped_inventory FROM bill_main bm WHERE bm.invoice_no='$invoice_no'");
		$row = mysqli_fetch_assoc($result);
		$store=$row['mapped_inventory'];

		$query0="SELECT ivq.id,bi.qty,bi.id,(bi.unit_price+bi.discount),bi.cost,bi.w_price,bi.r_price,ivq.w_price,ivq.r_price,ivq.c_price,ivq.item,bi.no_update,ivq.location,itm.pr_sr,itm.unic,bm.`cust` FROM bill_main bm, bill bi, inventory_items itm, inventory_qty ivq WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND ivq.item=bi.item AND ivq.location='$store' AND bi.`invoice_no`='$invoice_no' ORDER BY bi.id DESC";
		$result0=mysqli_query($conn,$query0);
		while($row0=mysqli_fetch_array($result0)){
			$ivq_id=$row0[0];
			$bi_qty=$row0[1];
			$bi_id=$row0[2];
			$bi_price=$row0[3];
			$bi_cprice=$row0[4];
			$bi_wprice=$row0[5];
			$bi_rprice=$row0[6];
			$ivq_wprice=$row0[7];
			$ivq_rprice=$row0[8];
			$ivq_cprice=$row0[9];
			$ivq_item=$row0[10];
			$bi_noupdate=$row0[11];
			$ivq_location=$row0[12];
			$pr_sr=$row0[13];
			$itm_unic=$row0[14];
			$bm_cust=$row0[15];

			if($pr_sr==1){
				$debug_id=debugStart($bi_id,0);
				if((($itm_unic==1)&&(!$unic_cal))||($itm_unic==0)){
					if(($bi_cprice==$ivq_cprice)&&($bi_wprice==$ivq_wprice)&&($bi_rprice==$ivq_rprice)){
						$query="UPDATE `inventory_qty` SET `qty`=qty+$bi_qty WHERE `id`='$ivq_id'";
						$result=mysqli_query($conn,$query);
						if(!$result){ $proceed=false; $message='Error: Quantity Could Not be Updated'; }
					}else{
						$query="SELECT id FROM inventory_new WHERE w_price='$bi_wprice' AND r_price='$ivq_rprice' AND c_price='$bi_cprice' AND store='$store' AND item='$ivq_item'";
						$result=mysqli_query($conn,$query);
						while($row=mysqli_fetch_array($result)){
							$itn_id=$row[0];
						}
						if($itn_id!=''){
							$query="UPDATE `inventory_new` SET `qty`=qty+$bi_qty WHERE `id`='$itn_id'";
							$result=mysqli_query($conn,$query);
							if(!$result){ $proceed=false; $message='Error: Quantity could not be updated on new inventory'; }
						}else{
							$query="INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`) VALUES ('$ivq_item','$bi_wprice','$bi_rprice','$bi_cprice','$bi_qty','$store')";
							$result=mysqli_query($conn,$query);
							if(!$result){ $proceed=false; $message='Error: Quantity could not be add to the new inventory'; }
						}
					}
				}
				if($proceed && ($itm_unic==1)){
					if($bi_qty>0){
						$query="UPDATE `inventory_unic_item` SET `invoice_no`='0',`bill_id`='0',`status`='0' WHERE `bill_id`='$bi_id'";
						$result=mysqli_query($conn,$query);
						if(!$result){ $proceed=false; $message='Error: Failed to change status of unique items'; }
					}else{
						$query="SELECT sn FROM inventory_unic_item itu WHERE itu.`status`='0' AND itu.invoice_no='$invoice_no' AND itu.bill_id='$bi_id'";
						$row=mysqli_fetch_row(mysqli_query($conn,$query));
						$sn=$row[0];
						$query="SELECT bi.invoice_no,bi.id FROM  bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bm.`lock`=1 AND bm.`cust`='$bm_cust' AND bi.qty>0 AND bi.`comment` LIKE '%$sn%' ORDER BY bi.id DESC LIMIT 1";
						$row=mysqli_fetch_row(mysqli_query($conn,$query));
						$ori_invoice_no=$row[0];
						$ori_bi_id=$row[1];
						$query="UPDATE `inventory_unic_item` SET `invoice_no`='$ori_invoice_no',`bill_id`='$ori_bi_id',`status`='1' WHERE `status`='0' AND invoice_no='$invoice_no' AND bill_id='$bi_id'";
						$result=mysqli_query($conn,$query);
						if(!$result){ $proceed=false; $message='Error: Failed to change status of unique items'; }
					}
				}
				if($proceed){
					debugEnd($debug_id,'success');
				}else{
					debugEnd($debug_id,'fail');
				}
			}
		}
		if($proceed){
			$query="UPDATE `bill_main` SET `status`='0',`deleted_by`=$user_id,`deleted_timestamp`='$time_now' WHERE `invoice_no`='$invoice_no'";
			$result=mysqli_query($conn,$query);
			if(!$result){ $proceed=false; $message='Error: failed to change status of the invoice'; }
		}
		if($proceed){
			$query1="UPDATE `payment` SET `status`='1',`deleted_by`='$user_id',`deleted_date`='$time_now' WHERE bill_pay=1 AND invoice_no='$invoice_no'";
			$result=mysqli_query($conn,$query1);
			if(!$result){ $proceed=false; $message='Error: Failed to delete the payment'; }
		}

		if($proceed){
			$query1="UPDATE `return` SET `odr_no`=NULL,`odr_packed`=0,`odr_packed_date`=NULL,`odr_packed_by`=NULL WHERE odr_no='$invoice_no'";
			$result=mysqli_query($conn,$query1);
			if(!$result){ $proceed=false; $message='Error: Failed to remove packed return items'; }
		}

		// if there is any allocated repair items for this invoice, those repair item set back to correct qty
		$result2 = true;
		$query="SELECT `type` FROM bill_main WHERE invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$type=$row[0];
		if($type==3){
			$query="SELECT `id`,`parts`,`qty` FROM repair_invoice WHERE invoice_no='$invoice_no'";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_array($result)){
				$query1="SELECT `qty` FROM `repair_parts_inventory` WHERE `part`='$row[1]'";
				$result1 = mysqli_query($conn,$query1);
				if($result1){
					$row1=mysqli_fetch_row($result1);
					$rp_qty=$row1[0] + $row[2];
					$query2="UPDATE `repair_parts_inventory` SET `qty` = '$rp_qty'  WHERE `part`='$row[1]'";
					if(mysqli_query($conn,$query2)){
						$query3="DELETE FROM `repair_invoice` WHERE `id`= '$row[0]'";
						$result2=mysqli_query($conn,$query3);
						if(!$result2){
							$proceed=false; $message='Error: Failed to delete repair job';
						}
					}else{
						$proceed=false; $message='Error: Failed to update repair part quantity';
					}
				}
			}
		}
		if($proceed && $result2){
			deleteGTN($n,$invoice_no);
		}
	}
	// Quickbook invoice delete
	if(isQuickBooksActive(1)){
		if($proceed){
			$delete_messages = array();
			$query="SELECT `qb_txnid` FROM `bill_main` WHERE `invoice_no`='$invoice_no'";
			$result = mysqli_query($conn,$query);
			if($result){
				if (mysqli_num_rows($result) > 0) {
					$row = mysqli_fetch_assoc($result);
					$qb_txnid = $row['qb_txnid'];
					if($qb_txnid != ''){
						$qb_result = QBInvoiceDelete($qb_txnid);
						if($qb_result['status'] == 'success'){
							$query1 = "SELECT `qb_payment_id` FROM `payment` WHERE `bill_pay`=1 AND `invoice_no`='$invoice_no'";
							$result1 = mysqli_query($conn, $query1);
							if ($result1) {
								if (mysqli_num_rows($result1) > 0) {
									while ($row1 = mysqli_fetch_assoc($result1)) {
										$qb_payment_result =  QBPaymentDelete($row1['qb_payment_id']);
										if($qb_payment_result['status'] == 'error'){
											$delete_messages[] = "Error deleting QB payment ID {$row['qb_payment_id']}: " . $qb_payment_result['message'];
										}
									}
								}
							}
						}
						$qb_msg = $qb_result['message'];
					}
				}
			}
			// Display or process the collected messages as needed
			if (!empty($delete_messages)) {
				foreach ($delete_messages as $message) {
					echo $qb_msg . '<br>';
				}
			}
			$message .= $qb_msg;
		}
	}
	return $proceed;
}

function getBillTotal(){
	global $invoiceTotal;
	$invoice_no=$_GET['bill_no'];
	include('config.php');

	$query="SELECT SUM(qty*unit_price) FROM bill WHERE invoice_no='$invoice_no'";
	$result = mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$invoiceTotal=round($row[0],2);
}
/*
function getCreditStatus($sub_system,$cust_id){
	global $balance30,$balance14,$balance7,$balance0,$pending_chque,$cust_cr_limit,$remaining_cr_limit;
	$backdate30=date("Y-m-d",(time()-(30*24*60*60)));
	$backdate14=date("Y-m-d",(time()-(14*24*60*60)));
	$backdate7=date("Y-m-d",(time()-(7*24*60*60)));
	$today=dateNow();

	$remaining_cr_limit=getCreditStatus2($cust_id);

	include('config.php');
	$query="SELECT credit_limit,sub_system FROM cust WHERE id='$cust_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$cust_cr_limit=$row[0];
	if($row[1]==$sub_system){

	$query1="SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`type`!=3 AND bm.`cust`='$cust_id' AND bm.billed_timestamp<= '$backdate30 23:59:59'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$totalbill30=$row1[0];

	$query1="SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`type`!=3 AND bm.`cust`='$cust_id' AND bm.billed_timestamp<= '$backdate14 23:59:59'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$totalbill14=$row1[0];

	$query1="SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`type`!=3 AND bm.`cust`='$cust_id' AND bm.billed_timestamp<= '$backdate7 23:59:59'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$totalbill7=$row1[0];

	$query1="SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`type`!=3 AND bm.`cust`='$cust_id' ";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$totalbill0=$row1[0];

	$query1="SELECT SUM(bm.`invoice_-total`) FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`type`!=3 AND bm.`cust`='$cust_id'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$returnbill=$row1[0];

	$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.payment_date <= '$backdate30 23:59:59'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$payment_upto30=$row1[0];

	$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.payment_date <= '$backdate14 23:59:59'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$payment_upto14=$row1[0];

	$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.payment_date <= '$backdate7 23:59:59'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$payment_upto7=$row1[0];

	$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 ";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$payment_upto0=$row1[0];

	$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.bill_pay=2 AND py.payment_date > '$backdate30 23:59:59'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$payment_after30=$row1[0];

	$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.bill_pay=2 AND py.payment_date > '$backdate14 23:59:59'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$payment_after14=$row1[0];

	$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.bill_pay=2 AND py.payment_date > '$backdate7 23:59:59'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$payment_after7=$row1[0];

	$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.payment_type=2 AND py.chque_date > '$today'";
	$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
	$pending_chque=$row1[0];

	$balance30=$totalbill30+$returnbill-$payment_upto30-$payment_after30;
	$balance14=$totalbill14+$returnbill-$payment_upto14-$payment_after14;
	$balance7=$totalbill7+$returnbill-$payment_upto7-$payment_after7;
	$balance0=$totalbill0+$returnbill-$payment_upto0;
	if($balance30<0) $balance30=0;
	if($balance14<0) $balance14=0;
	if($balance7<0) $balance7=0;
	}
}
*/
// update by nirmal 28_11_2023
function getCreditStatus($sub_system,$cust_id){
	global $balance60, $balance90, $balance30,$balance14,$balance7,$balance0,$pending_chque,$cust_cr_limit,$remaining_cr_limit;
	$backdate30=date("Y-m-d",(time()-(30*24*60*60)));
	$backdate14=date("Y-m-d",(time()-(14*24*60*60)));
	$backdate7=date("Y-m-d",(time()-(7*24*60*60)));
	$backdate60 = date("Y-m-d", (time() - (60 * 24 * 60 * 60))); // Added for 60 days
	$backdate90 = date("Y-m-d", (time() - (90 * 24 * 60 * 60))); // Added for 90 days
	$today=dateNow();

	$remaining_cr_limit=getCreditStatus2($cust_id);

	include('config.php');
	$query="SELECT credit_limit,sub_system FROM cust WHERE id='$cust_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$cust_cr_limit=$row[0];

	if($row[1]==$sub_system){
		// New calculations for 90 days
		$query1 = "SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`type`!=3 AND bm.`cust`='$cust_id' AND bm.billed_timestamp<= '$backdate90 23:59:59'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$totalbill90 = $row1[0];

		// New calculations for 60 days
		$query1 = "SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`type`!=3 AND bm.`cust`='$cust_id' AND bm.billed_timestamp<= '$backdate60 23:59:59'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$totalbill60 = $row1[0];

		$query1="SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`type`!=3 AND bm.`cust`='$cust_id' AND bm.billed_timestamp<= '$backdate30 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$totalbill30=$row1[0];

		$query1="SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`type`!=3 AND bm.`cust`='$cust_id' AND bm.billed_timestamp<= '$backdate14 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$totalbill14=$row1[0];

		$query1="SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`type`!=3 AND bm.`cust`='$cust_id' AND bm.billed_timestamp<= '$backdate7 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$totalbill7=$row1[0];

		$query1="SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`type`!=3 AND bm.`cust`='$cust_id' ";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$totalbill0=$row1[0];

		$query1="SELECT SUM(bm.`invoice_-total`) FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`type`!=3 AND bm.`cust`='$cust_id'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$returnbill=$row1[0];

		// New calculations for 90 days
		$query1 = "SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.payment_date <= '$backdate90 23:59:59'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$payment_upto90 = $row1[0];

		// New calculations for 60 days
		$query1 = "SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.payment_date <= '$backdate60 23:59:59'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$payment_upto60 = $row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.payment_date <= '$backdate30 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_upto30=$row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.payment_date <= '$backdate14 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_upto14=$row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.payment_date <= '$backdate7 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_upto7=$row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 ";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_upto0=$row1[0];

		// New calculations for 90 days
		$query1 = "SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.bill_pay=2 AND py.payment_date > '$backdate90 23:59:59'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$payment_after90 = $row1[0];

		// New calculations for 60 days
		$query1 = "SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.bill_pay=2 AND py.payment_date > '$backdate60 23:59:59'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$payment_after60 = $row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.bill_pay=2 AND py.payment_date > '$backdate30 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_after30=$row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.bill_pay=2 AND py.payment_date > '$backdate14 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_after14=$row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.chque_return=0 AND py.bill_pay=2 AND py.payment_date > '$backdate7 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_after7=$row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$cust_id' AND py.payment_type=2 AND py.chque_date > '$today'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$pending_chque=$row1[0];

		$balance90 = $totalbill90 + $returnbill - $payment_upto90 - $payment_after90; // New calculations for 90 days
		$balance60 = $totalbill60 + $returnbill - $payment_upto60 - $payment_after60; // New calculations for 60 days
		$balance30=$totalbill30+$returnbill-$payment_upto30-$payment_after30;
		$balance14=$totalbill14+$returnbill-$payment_upto14-$payment_after14;
		$balance7=$totalbill7+$returnbill-$payment_upto7-$payment_after7;
		$balance0=$totalbill0+$returnbill-$payment_upto0;
		if ($balance90 < 0) $balance90 = 0; // New calculations for 90 days
		if ($balance60 < 0) $balance60 = 0; // New calculations for 60 days
		if($balance30<0) $balance30=0;
		if($balance14<0) $balance14=0;
		if($balance7<0) $balance7=0;
	}
}

function storeCrossCheck($sub_system,$systemid,$itmid){
	$target_store=0;
	if($systemid==1 && $sub_system==1) $target_store=2;
	include('config.php');
	$query="SELECT r_price,w_price,qty FROM inventory_qty WHERE item='$itmid' AND location='$target_store'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	return $row[0].','.$row[1].','.$row[2].','.$target_store;
}

function getQOPrice(){
	$qo_no=$_GET['qo_no'];
	$item_id=$_GET['item_id'];
	$qo_uprice='';
	include('config.php');
	$query="SELECT unit_price FROM quotation WHERE quot_no='$qo_no' AND item='$item_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$qo_uprice=$row[0];
	return $qo_uprice;
}

// updated by nirmal 21_12_23
function getCreditStatus2($cust){
	global $qo_no;
	$remaining_cr_limit=$totalbill0=0;
	$today=dateNow();

	include('config.php');

	$query="SELECT credit_limit FROM cust WHERE id='$cust'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$cust_cr_limit=$row[0];
	/*
			$query="SELECT SUM(bi.qty*bi.unit_price) FROM bill_tmp bi, bill_main_tmp bm WHERE bm.bm_no=bi.bm_no AND bm.`status`!=0 AND bm.`type`!=3 AND bm.`cust`='$cust' ";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$totalbill0=$row[0];
	*/
	$query="SELECT SUM(bi.qty*bi.unit_price) FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bm.exclude=0 AND bm.`type`!=3 AND bm.`cust`='$cust' ";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$totalbill0+=$row[0];

	$query="SELECT SUM(amount) FROM payment WHERE `status`=0 AND `cust`='$cust' AND payment_type IN (1,3,4)";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$payment_cash=$row[0];

	$query="SELECT SUM(amount) FROM payment WHERE `status`=0 AND `cust`='$cust' AND payment_type=2 AND chque_return=0 AND chque_date <= '$today'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$payment_chque=$row[0];

	$remaining_cr_limit=$cust_cr_limit-$totalbill0+$payment_cash+$payment_chque;

	return $remaining_cr_limit;
}

// updated by nirmal 21_12_24
function getCreditOutstanding2($cust,$invoice_no){
	global $qo_no;
	$total_oust=$invoice_oust=0;
	$jasonArray=array();

	if(isMobile())	include('config.php');	else include('../../../../config.php');

	$query="SELECT SUM(bi.qty*bi.unit_price) FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bm.exclude=0 AND bm.`type`!=3 AND bm.`cust`='$cust' ";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$totalbill0=$row[0];
	$query="SELECT SUM(amount) FROM payment WHERE `status`=0 AND `cust`='$cust' AND payment_type IN (1,3,4)";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$payment_cash=$row[0];
	$query="SELECT SUM(amount) FROM payment WHERE `status`=0 AND `cust`='$cust' AND payment_type=2 AND chque_return=0";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$payment_chque=$row[0];
	$total_oust=$totalbill0-$payment_cash-$payment_chque;

	if($invoice_no!=0){
		$query="SELECT SUM(bi.qty*bi.unit_price) FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bm.exclude=0 AND bm.`type`!=3 AND bm.`cust`='$cust' AND bm.invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$totalbill0=$row[0];
		$query="SELECT SUM(amount) FROM payment WHERE `status`=0 AND `cust`='$cust' AND payment_type IN (1,3,4) AND invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$payment_cash=$row[0];
		$query="SELECT SUM(amount) FROM payment WHERE `status`=0 AND `cust`='$cust' AND payment_type=2 AND chque_return=0 AND invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$payment_chque=$row[0];
		$invoice_oust=$totalbill0-$payment_cash-$payment_chque;
	}
	$jasonArray["total_oust"]=$total_oust;
	$jasonArray["invoice_oust"]=$invoice_oust;
	$myJSON = json_encode($jasonArray);

	return $myJSON;
}

// updated by nirmal 02_08_2023 to add tax calculation to total
function calculateTotal(){
	$item_id=$_GET['id'];
	include('config.php');
	$query="SELECT invoice_no FROM bill WHERE id='$item_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$invoice_no=$row[0];
	$result2 = mysqli_query($conn,"SELECT SUM(qty*unit_price) AS `total` FROM bill WHERE qty>0 AND invoice_no='$invoice_no'");
	$row2 = mysqli_fetch_assoc($result2);
	$up_total=$row2['total'];
	if($up_total=='') $up_total=0;
	$result2 = mysqli_query($conn,"SELECT SUM(qty*unit_price) AS `total` FROM bill WHERE qty<0 AND invoice_no='$invoice_no'");
	$row2 = mysqli_fetch_assoc($result2);
	$down_total=$row2['total'];
	if($down_total=='') $down_total=0;
	// ------------ added by nirmal 26_07_2023
	$result = mysqli_query($conn, "SELECT `value` FROM settings WHERE setting='tax'");
	$row = mysqli_fetch_assoc($result);
	$tax_rate = $row['value'];

	$result = mysqli_query($conn, "SELECT `value` FROM settings WHERE setting='decimal'");
	$row = mysqli_fetch_assoc($result);
	$decimal = $row['value'];

	$total = $up_total + $down_total;
	$tax_added_value = ((($total) / ((100+$tax_rate)/100)) * ($tax_rate / 100));

	$query3="UPDATE bill_main SET `invoice_+total`='$up_total', `invoice_-total`='$down_total', `tax` = '$tax_added_value' WHERE `invoice_no`='$invoice_no'";
	// ------------
	// $query3="UPDATE bill_main SET `invoice_+total`='$up_total', `invoice_-total`='$down_total' WHERE `invoice_no`='$invoice_no'"; OLD CODE
	$result3=mysqli_query($conn,$query3);
	if($result3){
		$message='Pre Calculation was Corrected Successfully!';
		return true;
	}else{
		$message='Error: Pre Calculation could not be Done!';
		return false;
	}
}

// update by nirmal 25_12_2023
function searchBill($id){
	global $cust_id,$cust_name;
	$id=ltrim($id, '0');
	include('config.php');
	$query="SELECT count(invoice_no) FROM bill_main WHERE invoice_no='$id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));

	if($row[0]==1) return true;
	else return false;
}

function searchPay($id){
	global $cust_id,$cust_name;
	$id=ltrim($id, '0');
	$output=0;
	include('config.php');
		$query="SELECT DISTINCT id FROM payment WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$output=$row[0];
	}
	if($output!=0) return true;
	else return false;
}

function searchBillCust($type,$search_txt,$sub_system){
	global $cust_id,$cust_name;
	$cust_id=array();
	$qry="";
	include('../../../../config.php');
	if($type=='id'){ $qry="`id`='$search_txt'"; }
	if($type=='name'){ $qry="`name` LIKE '%$search_txt%'"; }
	if($qry!=''){
		$query="SELECT id,name FROM cust WHERE $qry AND sub_system='$sub_system' LIMIT 50";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$cust_id[]=$row[0];
			$cust_name[]=$row[1];
		}
	}
}

function searchBillMob($mobile,$sub_system){
	global $cust_id,$cust_name,$cust_mobile;
	$cust_id=$cust_name=$cust_mobile=array();
	include('../../../../config.php');
	if($mobile!=''){
		$query="SELECT id,name,mobile FROM cust WHERE mobile LIKE '%$mobile%' AND sub_system='$sub_system' LIMIT 50";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$cust_id[]=$row[0];
			$cust_name[]=$row[1];
			$cust_mobile[]=$row[2];
		}
	}
}

// updated by nirmal 25_12_2023
function searchListBill($id){
	global $cubill_id,$cubill_date,$cubill_total;
	$cubill_id=array();
	$is_custom_invoice_no_active = isCustomInvoiceNoActive(1);
	include('../../../../config.php');
	if($is_custom_invoice_no_active){
		$query="SELECT invoice_no,date(billed_timestamp),round(`invoice_+total`+`invoice_-total`) FROM bill_main WHERE `cust`='$id' AND `status`!=0 ORDER BY CAST(invoice_no AS UNSIGNED) DESC LIMIT 20";
	}else{
		$query="SELECT invoice_no,date(billed_timestamp),round(`invoice_+total`+`invoice_-total`) FROM bill_main WHERE `cust`='$id' AND `status`!=0 ORDER BY invoice_no DESC LIMIT 20";
	}
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$cubill_id[]=$row[0];
		$cubill_date[]=$row[1];
		$cubill_total[]=$row[2];
	}
}

function searchUnicBill($sn){
	global $bill_no,$bill_type,$bill_color,$wa_no,$wa_st_name,$wa_st_color;
	include('../../../../config.php');
	$bill_no=$wa_no=$wa_st_name=$wa_st_color=array();
	if($sn!=''){
		$query="SELECT bm.invoice_no,bm.type FROM bill_main bm, bill bi  WHERE bm.invoice_no=bi.invoice_no AND bi.`comment` LIKE '%$sn%' GROUP BY bm.invoice_no LIMIT 15";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$bill_no[]=$row[0];
			switch($row[1]){
				case 1: $bill_type[]='Product'; $bill_color[]='green'; break;
				case 2: $bill_type[]='Service'; $bill_color[]='orange'; break;
				case 3: $bill_type[]='Repair'; $bill_color[]='maroon'; break;
				case 4: $bill_type[]='Product'; $bill_color[]='green'; break;
				case 5: $bill_type[]='Service'; $bill_color[]='orange'; break;
			}
		}
		$query="SELECT wa.id,wa.`status` FROM warranty wa WHERE wa.claim_sn='$sn' OR wa.inv_replace_sn='$sn' OR wa.suplier_replace_sn='$sn' OR wa.handover_sn='$sn'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$wa_no[]=$row[0];
			$json_array=json_decode(warrantyStatus($row[1]));
			$wa_st_name[]=$json_array->{"st_name"};
			$wa_st_color[]=$json_array->{"st_color"};
		}
	}
}

function searchReturn($id){
	$id=ltrim($id, '0');
	include('config.php');
	$query="SELECT count(invoice_no) FROM return_main WHERE invoice_no='$id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));

	if($row[0]==1) return true;
	else return false;
}

function printST(){
	$invoice_no=$_REQUEST['id'];
	include('config.php');
	$query="SELECT `print_st` FROM `bill_main` WHERE `invoice_no`='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($row[0]==0){
		$query="UPDATE `bill_main` SET `print_st`='1' WHERE `invoice_no`='$invoice_no'";
		$result=mysqli_query($conn,$query);
	}
}

function setDelivered(){
	global $message,$invoice_no;
	$invoice_no=$_POST['id'];
	$salesman=$_COOKIE['user_id'];
	$today=timeNow();
	$out=false;
	include('config.php');
	$query="SELECT `type`,`status`,`invoice_+total`,`invoice_-total` FROM `bill_main` WHERE `invoice_no`='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($row[0]==3 && ($row[1]==3 || $row[1]==6)){
		if(($row[2]==0)&&($row[3]<0)){
			$query="UPDATE `bill_main` SET `deliverd_by`='$salesman',`deliverd_timestamp`='$today',`status`='5',`lock`='1',`shipped_by`=`packed_by`,`shipped_timestamp`='$today' WHERE `invoice_no`='$invoice_no'";
		}else{
			$query="UPDATE `bill_main` SET `deliverd_by`='$salesman',`deliverd_timestamp`='$today',`status`='7',`lock`='1' WHERE `invoice_no`='$invoice_no'";
		}
		$result=mysqli_query($conn,$query);
		if($result) $out=true; else $message="Error: Bill Could Not be Updated!";
	}else $message="Error: Unauthorized Request!";

	if($out){
		$message="Bill Status Was Updated Successfully!";
		return true;
	}else{
		return false;
	}
}

// updated by nirmal 26_07_2023 to add tax calculation to total
function changeJobTotal(){
	global $message,$invoice_no;
	$invoice_no=$_GET['id'];
	$code=$_GET['code'];
	$new_total=$_GET['new_total'];
	$out=false;
	include('config.php');
	$query="SELECT authorize_code FROM bill_main WHERE invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$bm_code=$row[0];
	if($bm_code==$code)$out=true; else $message="Error: Invalid Authorization Code !";

	if($out){
		$query="SELECT count(id),qty FROM bill WHERE invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		if($row[0]==1){
			$new_uprice=$new_total/$row[1];
			$query="UPDATE `bill` SET `unit_price`='$new_uprice' WHERE `invoice_no`='$invoice_no'";
			$result=mysqli_query($conn,$query);
			// ------------ added by nirmal 26_07_2023
			$result = mysqli_query($conn, "SELECT `value` FROM settings WHERE setting='tax'");
			$row = mysqli_fetch_assoc($result);
			$tax_rate = $row['value'];

			$result = mysqli_query($conn, "SELECT `value` FROM settings WHERE setting='decimal'");
			$row = mysqli_fetch_assoc($result);
			$decimal = $row['value'];

			$tax_added_value = ((($new_total) / ((100+$tax_rate)/100)) * ($tax_rate / 100));

			$query="UPDATE `bill_main` SET `invoice_+total`='$new_total', `tax` = '$tax_added_value'  WHERE `invoice_no`='$invoice_no'";
			// ------------
			// $query="UPDATE `bill_main` SET `invoice_+total`='$new_total' WHERE `invoice_no`='$invoice_no'"; OLD CODE
			$result=mysqli_query($conn,$query);
			if($result) $out=true; else $message="Error: Job Total Could Not be Updated!";
		}else $message="Cannot Update a Job Contails More Than 1 Item!";
	}

	if($out){
		$message="Error: Job Total Was Updated Successfully!";
		return true;
	}else{
		return false;
	}
}

// updated by nirmal 21_03_2022
function sms(){
	$invoice_no=$_REQUEST['id'];
	$timenow=timeNow();
	$date_now=substr($timenow,0,10);
	$sms_sent=1;
	$msg='';
	if(isset($_COOKIE['sub_system'])) $sub_system=$_COOKIE['sub_system']; else $sub_system=0;
	//$inf_company=inf_company(1);
	$inf_from_email=inf_from_email();
	//$inf_to_email=inf_to_email();
	$sms_data=json_decode(sms_credential($sub_system));
	$sms_user=$sms_data->{"user"};
	$sms_pass=$sms_data->{"pass"};
	$sms_balance=$sms_data->{"balance"};
	$sms_device=$sms_data->{"device"};
	include('config.php');
	$result = mysqli_query($conn,"SELECT cu.sms as `cu_sms`,SUM(bi.qty * bi.unit_price) AS total,cu.mobile,bm.store,bm.`cust`,bm.`type`,bm.sms as `bm_sms`,date(bm.`billed_timestamp`) as `date`,st.shop_name_sms,cu.`status` FROM bill_main bm,bill bi, cust cu, stores st WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`=cu.id AND bm.`store`=st.id AND bi.invoice_no='$invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$sms_cust=$row['cu_sms'];
	$bill_total=$row['total'];
	$mobile=$row['mobile'];
	$store=$row['store'];
	$cust_tmp=$row['cust'];
	$bill_type=$row['type'];
	$sms_sent=$row['bm_sms'];
	$bill_date=$row['date'];
	$cust_type=$row['status'];
	$inf_company=$row['shop_name_sms'];
	if(($sms_cust==1)&&($sms_balance>0)&&($_SERVER['SERVER_NAME']==inf_url_primary())&&($bill_type!=3)&&($sms_sent==0)&&(strpos($mobile,"7")==1)){
		//		if(($sms_cust==1)&&($sms_balance>0)&&($bill_type!=3)&&($sms_sent==0)){
		$query1="SELECT SUM(bi.qty*bi.unit_price) as `total` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`cust`='$cust_tmp'";
		$result1 = mysqli_query($conn,$query1);
		$row1 = mysqli_fetch_assoc($result1);
		$totalinv=$row1['total'];
		$query1="SELECT SUM(py.amount) as `pay` FROM payment py WHERE py.`status`=0 AND py.`cust`='$cust_tmp' AND py.chque_return=0";
		$result1 = mysqli_query($conn,$query1);
		$row1 = mysqli_fetch_assoc($result1);
		$totalpay=$row1['pay'];
		$credit_balance=$totalinv-$totalpay;

		$result = mysqli_query($conn,"SELECT `workflow` FROM stores WHERE id='$store'");
		$row = mysqli_fetch_assoc($result);
		$workflow=$row['workflow'];

		// send total outstanding details only for stores, which has sms_outstanding = 1;
		$query="SELECT s.`sms_outstanding` FROM stores s, cust c WHERE c.`associated_store`=s.`id` AND c.`id`='$cust_tmp'";
		$result = mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$outstanding = $row[0];

		if($bill_type==4 || $bill_type==5){
			$inv_title='Order';
			$cr_balance_txt='-NLC-';
		}else{
			$inv_title='Invoice';
			$cr_balance_txt='++++Amount+=+'.number_format($bill_total).'+-NLC-';
			if($cust_type==1 && $outstanding ==1){
				$cr_balance_txt.='Total+Outstanding+=++'.number_format($credit_balance).'+-NLC-';
			}
		}
		//$message_header=$sms_user.'#'.$sms_pass.'#'.str_replace(" ","+",$inf_company).'+-NLC-Invoice+no:+'.str_pad($invoice_no, 7, "0", STR_PAD_LEFT).'++++Amount+=+'.number_format($bill_total).'+-NLC-Total+Outstanding+=++'.number_format($credit_balance).'+-NLC-';
		$message_header=str_replace(" ","+",$inf_company).'-NLC-'.$inv_title.'+no:+'.str_pad($invoice_no, 7, "0", STR_PAD_LEFT).$cr_balance_txt;

		if($sms_cust==1){
			if($workflow==1 || $bill_type==4 || $bill_type==5) $message =$message_header.'We+will+update+you+with+the+order+status+-NLC-Thank+you!';
			if($workflow==0) $message =$message_header.'It+was+a+pleasure+to+serve+you+-NLC-Thank+you!';
			/*
			$to      = 'netdefine@gmail.com';
			$subject = $mobile;
			$headers = 'From: '.$inf_from_email. "\r\n" .
				'Reply-To: '.$inf_from_email. "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			$mailstatus=mail($to, $subject, $message, $headers);
			*/
			$sms_balance--;
			//---------------------------------------------------------------//
			$text = urlencode($message);
			if($sms_device==""){
				$url = "http://www.textit.biz/sendmsg/?id=$sms_user&pw=$sms_pass&eco=Y&to=$mobile&text=$text";
				$ret = file($url);
				$res= explode(":",$ret[0]);
				if (trim($res[0])=="OK") $mailstatus=true; else $mailstatus=false;
			}else{
				$url = "http://mqtt.negoit.info/sms_gw.php?dev=$sms_device&ref1=bill&ref2=$invoice_no&u=$sms_user&p=$sms_pass&to=$mobile&text=$text";
				setcookie("sms_balance",$sms_balance, time()+3600*10);
				file($url);
				$mailstatus=false;
			}
			//----------------------------------------------------------------//
			$query="SELECT MAX(id) FROM sms";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$max_id=$row[0];
			$query="SELECT MIN(id) FROM sms";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$min_id=$row[0];
			$next_id=$max_id+1;
			$query="UPDATE `sms` SET `id`='$next_id',`timestamp`='$timenow',`case`='1',`ref`='$invoice_no',`text`='$message' WHERE id='$min_id'";
			mysqli_query($conn,$query);

			if($mailstatus){
				$query="UPDATE `bill_main` SET `sms`='1' WHERE `invoice_no`='$invoice_no'";
				mysqli_query($conn,$query);
				if(set_sms_balance($sub_system,$sms_balance))	$msg='SMS Sent<hr />'; 	else 	$msg='Database Cound Not be Updated<hr />';
			}else $msg='SMS Could not be Sent<hr />';
		}else $msg='SMS Disabled on Customer';
	}else $msg='SMS alredy sent OR bill is not issued on today';
	//	print $msg;
}

// updated by nirmal 21_03_2022
function sms2($payment_id,$cust,$amount){
	$timenow=timeNow();
	$date_now=substr($timenow,0,10);
	$sub_system=$_COOKIE['sub_system'];
	$msg='';
	$bm_type=false;
	//$inf_company=inf_company(1);
	$inf_from_email=inf_from_email();
	$sms_data=json_decode(sms_credential($sub_system));
	$sms_user=$sms_data->{"user"};
	$sms_pass=$sms_data->{"pass"};
	$sms_balance=$sms_data->{"balance"};
	$sms_device=$sms_data->{"device"};
	include('config.php');
	$result = mysqli_query($conn,"SELECT cu.sms,cu.mobile FROM cust cu WHERE cu.id='$cust'");
	$row = mysqli_fetch_assoc($result);
	$sms_cust=$row['sms'];
	$mobile=$row['mobile'];
	$result = mysqli_query($conn,"SELECT py.invoice_no,st.shop_name_sms FROM payment py, stores st WHERE py.store=st.id AND py.id='$payment_id'");
	$row = mysqli_fetch_assoc($result);
	$invoice_no=$row['invoice_no'];
	$inf_company=$row['shop_name_sms'];
	if($invoice_no==0){
		$bm_type=true;
	}else{
		$result = mysqli_query($conn,"SELECT `type` FROM bill_main WHERE invoice_no='$invoice_no'");
		$row = mysqli_fetch_assoc($result);
		$bm_type=$row['type'];
		if($bm_type!=3) $bm_type=true;
	}

	if(($sms_cust==1)&&($sms_balance>0)&&($_SERVER['SERVER_NAME']==inf_url_primary())&&($bm_type)&&(strpos($mobile,"7")==1)){
		//	if(($sms_cust==1)&&($sms_balance>0)&&($bm_type)){

		$query1="SELECT SUM(bi.qty*bi.unit_price) as `total`,bm.`type` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`cust`='$cust'";
		$result1 = mysqli_query($conn,$query1);
		$row1 = mysqli_fetch_assoc($result1);
		$totalinv=$row1['total'];
		$bm_type=$row1['type'];
		$query1="SELECT SUM(py.`amount`) as `pay` FROM payment py WHERE py.`status`='0' AND py.`cust`='$cust' AND py.chque_return='0'";
		$result1 = mysqli_query($conn,$query1);
		$row1 = mysqli_fetch_assoc($result1);
		$totalpay=$row1['pay'];
		$credit_balance=$totalinv-$totalpay;


		// send total outstanding details only for stores, which has sms_outstanding = 1;
		$query="SELECT s.`sms_outstanding` FROM stores s, cust c WHERE c.`associated_store`=s.`id` AND c.`id`='$cust'";
		$result = mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$outstanding = $row[0];

		if($outstanding == 1){
			$message_header=str_replace(" ","+",$inf_company).'+-NLC-Payment+Inv:+'.str_pad($payment_id, 7, "0", STR_PAD_LEFT).'++++Amount+=+'.number_format($amount).'+-NLC-Total+Outstanding+=++'.number_format($credit_balance).'+-NLC-';
		}else{
			$message_header=str_replace(" ","+",$inf_company).'+-NLC-Payment+Inv:+'.str_pad($payment_id, 7, "0", STR_PAD_LEFT).'++++Amount+=+'.number_format($amount).'+-NLC-';
		}
		//	$message_header=$sms_user.'#'.$sms_pass.'#'.str_replace(" ","+",$inf_company).'+\Payment+Inv:+'.str_pad($payment_id, 7, "0", STR_PAD_LEFT).'++++Amount+=+'.number_format($amount).'+\nTotal+Outstanding+=++'.number_format($credit_balance).'+\n';
		$message =$message_header.'Your+payment+has+been+received+-NLC-Thank+you!';
		/*
		$to      = 'netdefine@gmail.com';
		$subject = $mobile;
		$headers = 'From: '.$inf_from_email. "\r\n" .
		    'Reply-To: '.$inf_from_email. "\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		$mailstatus=mail($to, $subject, $message, $headers);
		*/
		$sms_balance--;
		//---------------------------------------------------------------//
		$text = urlencode($message);

		if($sms_device==""){
			$url = "http://www.textit.biz/sendmsg/?id=$sms_user&pw=$sms_pass&eco=Y&to=$mobile&text=$text";
			$ret = file($url);
			$res= explode(":",$ret[0]);
			if (trim($res[0])=="OK") $mailstatus=true; else $mailstatus=false;
		}else{
			$url = "http://mqtt.negoit.info/sms_gw.php?dev=$sms_device&ref1=pay&ref2=$payment_id&u=$sms_user&p=$sms_pass&to=$mobile&text=$text";
			setcookie("sms_balance",$sms_balance, time()+3600*10);
			file($url);
			$mailstatus=false;
		}

		//----------------------------------------------------------------//
				$query="SELECT MAX(id) FROM sms";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$max_id=$row[0];
				$query="SELECT MIN(id) FROM sms";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$min_id=$row[0];
				$next_id=$max_id+1;
				$query="UPDATE `sms` SET `id`='$next_id',`timestamp`='$timenow',`case`='2',`ref`='$payment_id',`text`='$message' WHERE id='$min_id'";
				mysqli_query($conn,$query);

		if($mailstatus){
			$query="UPDATE `payment` SET `sms`='1' WHERE `id`='$payment_id'";
			mysqli_query($conn,$query);
			if(set_sms_balance($sub_system,$sms_balance))	$msg='SMS Sent<hr />'; 	else $msg='Database Cound Not be Updated<hr />';
		}else $msg='SMS Could not be Sent<hr />';
	}
}

function setFastPrint(){
	global $invoice_no,$salesman,$cust;
	$invoice_no=$_GET['id'];
	$salesman=$_GET['s'];
	$cust=$_GET['cust'];
	if($_COOKIE['fastprint']=='on'){
		setcookie("fastprint",'off', time()+3600*10);
		if($_COOKIE['fastprint']=='off') return true; else return false;
	}else{
		setcookie("fastprint",'on', time()+3600*10);
		if($_COOKIE['fastprint']=='on') return true; else return false;
	}
}
//--------------------------payment--------------------------------------------------------------------------//

function getCust($status){
	global $cust_id,$cust_name,$cust_nic,$cust_mobile,$cust_asso_sman,$gps_x,$gps_y;
	$sub_system=$_COOKIE['sub_system'];
	include('config.php');
		$query="SELECT cu.id,cu.name,cu.nic,cu.mobile,up.username,cu.gps_x,cu.gps_y FROM cust cu, userprofile up WHERE cu.associated_salesman=up.id AND cu.sub_system='$sub_system' AND cu.`status` IN ($status)";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$cust_id[]=$row[0];
			$cust_name[]=$row[1];
			$cust_nic[]=$row[2];
			$cust_mobile[]=$row[3];
			$cust_asso_sman[]=$row[4];
			$gps_x[]=$row[5];
			$gps_y[]=$row[6];
	}
}

// update by nirmal 21_12_23
function getCustPayments(){
	global $payment_id,$payment_type,$payment_amount,$payment_chq_date,$payment_chq_return,$payment_date,$full_data,$pay_color;
	$cust0=$_REQUEST['cust'];
	$sub_system=$_COOKIE['sub_system'];
	$i=0;
	$payment_date=array();
	include('config.php');
	$query="SELECT py.payment_type,py.amount,py.chque_no,py.chque_date,py.chque_return,date(py.payment_date),py.chque_bank,py.id,py.bank_trans,py.card_no FROM payment py, cust cu WHERE py.`cust`=cu.id AND py.`status`='0' AND py.`cust`='$cust0' AND py.sub_system='$sub_system' ORDER BY py.id DESC LIMIT 10";
	$result=mysqli_query($conn,$query);

	while($row=mysqli_fetch_array($result)){
		if($row[0]==1){
			$payment_type[$i]='Cash';
			$pay_color[$i]='#009900';
			$full_data[$i]='';
		}
		if($row[0]==2){
			$bankid=$row[6];
			$payment_type[$i]='Chque';  $pay_color[$i]='blue';
			$query1="SELECT name FROM bank WHERE id='$bankid'";
			$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
			$bank_name=$row1[0];
			$full_data[$i]=$bank_name.' | '.$row[2].' | '.$row[3];
		}
		if($row[0]==3){
			$bankid=$row[8];
			$payment_type[$i]='Chque';  $pay_color[$i]='#00AAAA';
			$query1="SELECT name FROM accounts WHERE id='$bankid'";
			$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
			$bank_name=$row1[0];
			$full_data[$i]=$bank_name.' | Bank Transfer';
		}
		if($row[0]==4){
			$bankid=$row[8];
			$payment_type[$i]='Card';
			$pay_color[$i]='#CC3399';
			$query1="SELECT `name` FROM accounts WHERE id='$bankid'";
			$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
			$bank_name=$row1[0];
			$card_no=$row[9];
			$full_data[$i]='Card    : ' . $card_no. ' | Bank : ' . $bank_name;
		}
		$payment_amount[]=is_null($row) ? 0 : $row[1];
		$payment_chq_no[]=is_null($row) ? 0 : $row[2];
		$payment_chq_date[]=is_null($row) ? "" : $row[3];
		if($row[4]==1) $pay_color[$i]='red';
		$payment_date[]=$row[5];
		$payment_id[]=$row[7];
		$i++;
	}
}

function getBank(){
	global $bank_id,$bank_code,$bank_name,$ac_bank_id,$ac_bank_name;
	include('config.php');
	$query="SELECT id,bank_code,name FROM bank WHERE `status`='1'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$bank_id[]=$row[0];
		$bank_code[]=$row[1];
		$bank_name[]=$row[2];
	}
	$query="SELECT id,name FROM accounts WHERE bank_ac=1 AND `status`=1";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$ac_bank_id[]=$row[0];
		$ac_bank_name[]=$row[1];
	}
}

function addHirePurchase($sub_system){
	$invoice_no=$_POST['invoice_no'];
	$hp_cal_start=$_POST['hp_cal_start'];
	$hp_type=$_POST['hp_type'];
	$hp_date=$_POST['hp_date'];
	$hp_amount=$_POST['hp_amount'];
	$hp_count=$_POST['hp_count'];
    $out=true;
    $msg='Done';
	include('config.php');

    if($out){
		$query="SELECT bm.recovery_agent FROM bill_main bm WHERE bm.invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$recovery_agent=$row[0];
		if($recovery_agent==''){ $out=false; $msg='Error: This Invoice is not a Hire Purchasing Invoice'; }
    }
    if($out){
		$query="INSERT INTO `hp_inv_schedule` (`invoice_no`,`cal_start_date`,`type`,`day`,`payment_amount`,`payment_count`) VALUES ('$invoice_no','$hp_cal_start','$hp_type','$hp_date','$hp_amount','$hp_count')";
   	 	$result=mysqli_query($conn,$query);
   	 	if(!$result){ $out=false; $msg='Error: Failed to add the Hire Purchasing Schedule'; }
    }
   	return $msg;
}

function hpInstalmentFormData(){
	global $hp_schedule,$hp_amount,$today0;
	$invoice_no=$_GET['id'];
	$today0=dateNow();
	$hp_schedule=$paid_dates=array();

	include('config.php');
	$query="SELECT his.cal_start_date,hst.`name`,his.`day`,his.payment_amount,his.payment_count FROM hp_inv_schedule his, hp_schedule_type hst WHERE his.`type`=hst.id AND his.invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$hp_cal_start=$row[0];
	$hp_type=$row[1];
	$hp_date=$row[2];
	$hp_amount=$row[3];
	$hp_count=$row[4];
	$hp_schedule=hpsPaySchedule($hp_cal_start,$hp_type,$hp_date,$hp_count);

	$query="SELECT hp.instalment_date,SUM(py.amount) FROM hp_payments hp, payment py WHERE hp.payment_no=py.id AND hp.invoice_no='$invoice_no' GROUP BY hp.instalment_date";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		if($row[1]==$hp_amount){
			$paid_dates[]=$row[0];
		}
	}
	$hp_schedule=array_values(array_diff($hp_schedule,$paid_dates));
}

// updated by nirmal 08_12_2023
function addBillPayment($sub_system){
	$invoice_no=$_POST['invoice_no'];
    $amount_cash=$_POST['amount_cash'];
    if(isset($_POST['amount_card'])) $amount_card=$_POST['amount_card'];
	if(isset($_POST['card_bank'])) $card_bank=$_POST['card_bank'];
	if(isset($_POST['card_no'])) $card_no=$_POST['card_no'];
    $amount_bank=$_POST['amount_bank'];
    $tr_bank=$_POST['tr_bank'];
    $amount_chque=$_POST['amount_chque'];
    $chque_no=$_POST['chque_no'];
    $chque_no=$_POST['chque_no'];
    $chque_bank=$_POST['chque_bank'];
    $chque_branch=$_POST['chque_branch'];
    $chque_date=$_POST['chque_date'];
    $comment=$_POST['comment'];

	$sys_user=$_COOKIE['user_id'];
	$store=$_COOKIE['store'];
	$time_now=timeNow();
    $out=true;
    $msg='Done';
	$tran_bnk_n=$tran_bnk_v=$bill_total=$qb_msg='';
	include('config.php');

	if(isset($_REQUEST['amount_card'])){
		if($amount_card<>0){
			if($card_bank==''){ $out=false; $msg="Please Select the Card Terminal's Bank"; }
			if(strlen($card_no) == 4){
				$card_no = "**** **** **** ". $card_no;
			}else if((strlen($card_no) == 16) || (strlen($card_no) == 19)){
					$card_no = "**** **** **** ". substr($card_no,-4);
			}else{
				$out=false;
				$msg='Error: Please eneter a valid Payment Card Number!';
			}
		}
	}
	if($amount_bank<>0){
		if($tr_bank==''){ $out=false; $msg='Please Select the Money Transferred Bank'; }
		$tran_bnk_n=',`bank_trans`';
		$tran_bnk_v=",'$tr_bank'";
	}
    if(($out)&&($amount_chque<>0)){
	    if($chque_no=='') $out=false;
	    if($chque_bank=='') $out=false;
	    if($chque_branch=='') $out=false;
	    if($chque_date=='') $out=false;
	    if($out==false){
	    	$msg='Amount, Chque Bank, Branch, Chque No, Chque Date Must be filled';
	    }
    }

    if($out){
		$query="SELECT bm.`billed_by`, bm.`cust`, bm.`invoice_+total` + bm.`invoice_-total` FROM bill_main bm WHERE bm.`invoice_no`='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$billed_by=$row[0];
		$cust=$row[1];
		$bill_total = $row[2];
    }

    if(($out)&&($amount_cash<>0)){
		$query="INSERT INTO `payment` (`invoice_no`,`bill_pay`,`cust`,`payment_type`,`amount`,`salesman`,`sys_user`,`payment_date`,`comment`,`store`,`gps_x`,`gps_y`,`sub_system`) VALUES ('$invoice_no','1','$cust','1','$amount_cash','$billed_by','$sys_user','$time_now','','$store','0','0','$sub_system')";
   	 	$result=mysqli_query($conn,$query);
   	 	if(!$result){ $out=false; $msg='Error: Failed to add the Cash payment'; }
    }
	if(isset($_REQUEST['amount_card'])){
		if(($out)&&($amount_card<>0)){
			$query="INSERT INTO `payment` (`invoice_no`,`bill_pay`,`cust`,`payment_type`,`amount`,`salesman`,`sys_user`,`payment_date`,`comment`,`store`,`gps_x`,`gps_y`,`sub_system`,`card_no`,`bank_trans`)
			VALUES ('$invoice_no','1','$cust','4','$amount_card','$billed_by','$sys_user','$time_now','$comment','$store','0','0','$sub_system','$card_no','$card_bank')";
			$result=mysqli_query($conn,$query);
			if(!$result){ $out=false; $msg='Error: Failed to add the Card payment'; }
		}
	}
    if(($out)&&($amount_bank<>0)){
		$query="INSERT INTO `payment` (`invoice_no`,`bill_pay`,`cust`,`payment_type`,`amount`,`salesman`,`sys_user`,`payment_date`,`comment`,`store`,`gps_x`,`gps_y`,`sub_system`,`bank_trans`) VALUES ('$invoice_no','1','$cust','3','$amount_bank','$billed_by','$sys_user','$time_now','$comment','$store','0','0','$sub_system','$tr_bank')";
   	 	$result=mysqli_query($conn,$query);
   	 	if(!$result){ $out=false; $msg='Error: Failed to add the Bank payment'; }
    }
    if(($out)&&($amount_chque<>0)){
		$query="SELECT id FROM bank WHERE bank_code='$chque_bank'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$chque_bank_id=$row[0];
		$query="INSERT INTO `payment` (`invoice_no`,`bill_pay`,`cust`,`payment_type`,`amount`,`chque_no`,`chque_bank`,`chque_branch`,`chque_date`,`salesman`,`sys_user`,`payment_date`,`comment`,`store`,`gps_x`,`gps_y`,`sub_system`) VALUES ('$invoice_no','1','$cust','2','$amount_chque','$chque_no','$chque_bank_id','$chque_branch','$chque_date','$billed_by','$sys_user','$time_now','','$store','0','0','$sub_system')";
   	 	$result=mysqli_query($conn,$query);
   	 	if(!$result){ $out=false; $msg='Error: Failed to add the Cheque payment'; }
    }

	if($out){
		if((isQuickBooksActive(1))){
			$amount_cash = isset($amount_cash) ? $amount_cash : 0;
			$amount_card = isset($amount_card) ? $amount_card : 0;
			$amount_bank = isset($amount_bank) ? $amount_bank : 0;
			$amount_chque = isset($amount_chque) ? $amount_chque : 0;
			$amount = $amount_cash + $amount_card + $amount_bank + $amount_chque;

			$qb_cust_id = getCustomerQBId($cust);
			if($qb_cust_id){ // create invoice
				$bill_array = array('Amount'=>$bill_total, 'bill_invoice_no'=>$invoice_no, 'qb_cust_id'=> $qb_cust_id);
				$qb_result = QBInvoiceCreate($bill_array);
				if($qb_result['status'] == 'success'){
					$qb_doc_id = $qb_result['qb_doc_id'];
					$qb_txnid = $qb_result['qb_txnid'];
					$query = "UPDATE `bill_main` SET `qb_doc_id`='$qb_doc_id', `qb_txnid`='$qb_txnid' WHERE `invoice_no`='$invoice_no'";
					$result = mysqli_query($conn, $query);
					if(($result) && ($amount != '' || $amount != 0)){ // add payment
						$payment_array = array('qb_cust_id' => $qb_cust_id, 'amount'=> $amount, 'total_amount' => $bill_total, 'qb_invoice_id'=> $qb_txnid);
						$qb_payment_result = QBAddPaymentForInvoice($payment_array);
						if($qb_payment_result['status'] == 'success'){
							$qb_payment_id = $qb_payment_result['qb_payment_id'];
							$query = "UPDATE `payment` SET `qb_payment_id`='$qb_payment_id' WHERE `invoice_no`='$invoice_no' AND `bill_pay` ='1'";
							$result = mysqli_query($conn, $query);
						}else{
							$qb_msg .= $qb_payment_result['message'];
						}
					}
					$qb_msg = $qb_result['message'];
				}
			} else{ // customer create in quicks book
				$qb_result = QBCustomerAdd($cust);
				if($qb_result['status'] == 'success'){
					$qb_cust_id = $qb_result['qb_cust_id'];
					$query = "UPDATE `cust` SET `qb_cust_id`='$qb_cust_id' WHERE `id`='$cust'";
					$result = mysqli_query($conn, $query);
					if($result){ // create new invoice
						$bill_array = array('Amount'=>$bill_total, 'bill_invoice_no'=>$invoice_no, 'qb_cust_id'=> $qb_cust_id);
						$qb_result = QBInvoiceCreate($bill_array);
						if($qb_result['status'] == 'success'){
							$qb_doc_id = $qb_result['qb_doc_id'];
							$qb_txnid = $qb_result['qb_txnid'];
							$query = "UPDATE `bill_main` SET `qb_doc_id`='$qb_doc_id', `qb_txnid`='$qb_txnid' WHERE `invoice_no`='$invoice_no'";
							$result = mysqli_query($conn, $query);
							if(($result) && ($amount != '' || $amount != 0)){ // add payment
								$payment_array = array('qb_cust_id' => $qb_cust_id, 'amount'=> $amount, 'total_amount' => $bill_total, 'qb_invoice_id'=> $qb_txnid);
								$qb_payment_result = QBAddPaymentForInvoice($payment_array);
								if($qb_payment_result['status'] == 'success'){
									$qb_payment_id = $qb_payment_result['qb_payment_id'];
									$query = "UPDATE `payment` SET `qb_payment_id`='$qb_payment_id' WHERE `invoice_no`='$invoice_no' AND `bill_pay` ='1'";
									$result = mysqli_query($conn, $query);
								}else{
									$qb_msg .= $qb_payment_result['message'];
								}
							}
						}
						$qb_msg = $qb_result['message'];
					}
				}
				$qb_msg = $qb_result['message'];
			}
		}
	}

    return $msg.'|'.$qb_msg;
}

// update by nirmal 21_12_13
function getInvoicePay(){
	global $invoice_no,$bm_cust_id,$bm_cust,$py_id,$py_date,$py_hp_inst_date,$py_amount,$py_type,$bill_total,$bill_payment,$print_time,$bm_date_time,$tm_shop,$tm_company,$tm_address,$tm_tel,$tm_web,$tm_email;
	$py_id=array();
	$print_time=timeNow();
	if(isset($_GET['invoice_no'])){
		$invoice_no=$_GET['invoice_no'];
		include('config.php');
		$query="SELECT bm.`invoice_+total` + bm.`invoice_-total`,cu.`id`,cu.`name`,bm.mapped_inventory,bm.billed_timestamp FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$bill_total=$row[0];
		$bm_cust_id=$row[1];
		$bm_cust=$row[2];
		$bm_mapped_inventory=$row[3];
		$bm_date_time=$row[4];
		$query="SELECT SUM(`amount`) FROM payment WHERE `status`=0 AND `chque_return`=0 AND bill_pay=1 AND invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$bill_payment=$row[0];

		$query="SELECT py.id,date(py.payment_date),hp.instalment_date,py.amount,py.payment_type FROM payment py LEFT JOIN hp_payments hp ON py.id=hp.payment_no WHERE py.`status`=0 AND py.chque_return=0 AND py.bill_pay=2 AND py.invoice_no='$invoice_no'";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_id[]=$row[0];
			$py_date[]=$row[1];
			$py_hp_inst_date[]=$row[2];
			$py_amount[]=$row[3];
			if($row[4]==1) $py_type[]='CASH'; else
			if($row[4]==2) $py_type[]='CHEQUE'; else
			if($row[4]==3)$py_type[]='BANK TRANSFER'; else
			if($row[4]==4)$py_type[]='CARD PAYMENT';
		}

		$result = mysqli_query($conn,"SELECT name,shop_name,address,tel FROM stores WHERE id='$bm_mapped_inventory'");
		$row = mysqli_fetch_assoc($result);
		$tm_shop=$row['name'];
		$tm_company=$row['shop_name'];
		$tm_address=$row['address'];
		$tm_tel=$row['tel'];


		$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='web'");
		$row = mysqli_fetch_assoc($result);
		$tm_web=$row['value'];
		$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='email'");
		$row = mysqli_fetch_assoc($result);
		$tm_email=$row['value'];
	}
}

// updated by nirmal 02_08_2023 to add tax calculation to total, 12_12_2023 add quickbooks payment add
function addPayment($ocation){
	global $message,$cust,$bm_type,$payment_id,$invoice_no;

	$chque_branch_id=$tran_bnk_n=$tran_bnk_v='';
	$salesman=$_REQUEST['salesman'];
	$sys_user=$_COOKIE['user_id'];
	$cust=$_REQUEST['cust'];
	$payment_type=$_REQUEST['payment_type'];
	$invoice_no=$_REQUEST['invoice_no'];
	if($invoice_no=='') $invoice_no=0;
	if(isset($_POST['gps_x'])) $gps_x=$_POST['gps_x']; else $gps_x=0;
	if(isset($_POST['gps_y'])) $gps_y=$_POST['gps_y']; else $gps_y=0;
	$store=$_COOKIE['store'];
	$sub_system=$_COOKIE['sub_system'];
	$out=true;
	$message='Payment was Added Successfully!';

	if(isset($_REQUEST['comment'])) $comment=$_REQUEST['comment']; else $comment='';
	if(isset($_REQUEST['cash_bank_switch'])){
		$payment_type=1;
		if($_REQUEST['cash_bank_switch'] ==1){
			$tr_bank=$_POST['tr_bank'];
			$cash_type=3;
			$tran_bnk_n=',`bank_trans`';
			$tran_bnk_v=",'$tr_bank'";
		}
		if($_REQUEST['cash_bank_switch'] ==2){
			$card_bank=$_POST['card_bank'];
			$card_no=$_POST['card_no'];
			$cash_type=4;
			if($card_bank==''){ $out=false; $message="Please Select the Card Terminal's Bank"; }
			if(strlen($card_no) == 4){
				$card_no = "**** **** **** ". $card_no;
			}else if((strlen($card_no) == 16) || (strlen($card_no) == 19)){
				$card_no = "**** **** **** ". substr($card_no,-4);
			}else{
				$out=false;
				$message='Error: Please eneter a valid Payment Card Number!';
			}
			$tran_bnk_n=',`bank_trans`, `card_no`';
			$tran_bnk_v=",'$card_bank','$card_no'";
		}
	}else $cash_type=1;

	$emptyamount=true;
	$result2=$proceed=false;
	$today=timeNow();

	if($ocation=='bill'){
		$remaining_cr_limit=getCreditStatus2($cust);
		$amount_cash=$_REQUEST['amount_cash'];
		$amount_chque=$_REQUEST['amount_chque'];
		$bill_pay=1;
	}
	if($ocation=='pay'){
		$amount_cash=$_REQUEST['amount'];
		$amount_chque=$_REQUEST['amount'];
		if($_REQUEST['amount']=='') $emptyamount=false;
		$bill_pay=2;
	}

	if($out){
		include('config.php');
		$query="SELECT `lock`,`type`,`status` FROM bill_main WHERE invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$lock_status=is_null($row) ? 0 : $row[0];
		$bm_type=is_null($row) ? 0 : $row[1];
		$bm_status=is_null($row) ? 0 : $row[2];
		$query="SELECT SUM(qty*unit_price) FROM bill WHERE invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$inv_total=$row[0];
		if(($ocation=='bill')&&($bm_type!=3)){
			if(($remaining_cr_limit+$amount_cash)<0) $emptyamount=false;
		}

		if($emptyamount){
			$time_now=timeNow();
			if(($ocation=='bill')&&($lock_status==0)) $proceed=true; else
			if($ocation=='pay') $proceed=true;
			if($bm_type==3) $bill_pay=1;

			if((($payment_type==1)||($payment_type==3))&&($proceed)){
				$query="INSERT INTO `payment` (`invoice_no`,`bill_pay`,`cust`,`payment_type`,`amount`,`salesman`,`sys_user`,`payment_date`,`comment`,`store`,`gps_x`,`gps_y`,`sub_system` $tran_bnk_n) VALUES ('$invoice_no','$bill_pay','$cust','$cash_type','$amount_cash','$salesman','$sys_user','$time_now','$comment','$store','$gps_x','$gps_y','$sub_system' $tran_bnk_v)";
				if(($bm_type==3)&&($ocation=='pay')&&($amount_cash==0)){
					$result2=true; //for repair invoices (Rejected repaire to finalyze the job with 0 cost
				}else{
					$result2=mysqli_query($conn,$query);
					if(!$result2){
						$message='Payment could not be Added!';
						$out=false;
					}
				}
				// add quickbooks payment add
				if(isQuickBooksActive(1)){
					// check customer is created in quickbooks first
					if($invoice_no == 0){
						$qb_cust_id = getCustomerQBId($cust_id);
						$payment_array = array('qb_cust_id' => $qb_cust_id, 'total_amount' => $bill_total);
						if($qb_cust_id){

						}else{ // create customer

						}
						QBAddPaymentWithoutInvoice($payment_array);
					}else{
						$payment_array = array('qb_cust_id' => $qb_cust_id, 'amount'=> $amount, 'total_amount' => $bill_total, 'qb_invoice_id'=> $qb_txnid);
						QBAddPaymentForInvoice($payment_array);
					}

				}
			}

			if((($payment_type==2)||($payment_type==3))&&($proceed)){
				$chque_bank=$_REQUEST['chque_bank'];
				$chque_branch=$_REQUEST['chque_branch'];
				$chque_no=$_REQUEST['chque_no'];
				$chque_date=$_REQUEST['chque_date'];
				$chque_bank_id='';

				$query="SELECT id FROM bank WHERE bank_code='$chque_bank'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$chque_bank_id=$row[0];

				if($chque_bank_id!=''){
					$query="INSERT INTO `payment` (`invoice_no`,`bill_pay`,`cust`,`payment_type`,`amount`,`salesman`,`sys_user`,`payment_date`,`chque_no`,`chque_bank`,`chque_branch`,`chque_date`,`comment`,`store`,`gps_x`,`gps_y`,`sub_system`) VALUES ('$invoice_no','$bill_pay','$cust','2','$amount_chque','$salesman','$sys_user','$time_now','$chque_no','$chque_bank_id','$chque_branch','$chque_date','$comment','$store','$gps_x','$gps_y','$sub_system')";
					$result2=mysqli_query($conn,$query);
					if(!$result2){
						$message='Payment could not be Added!';
						$out=false;
					}
				}else $result2=false;
			}

			if($payment_type==0){
				$result2=true;
				$message='Credit was Added Successfully!';
			}

			$payment_id=mysqli_insert_id($conn);
		}

		if($result2){
			if(($ocation=='pay')&&($bm_type!=3)) sms2($payment_id,$cust,$_REQUEST['amount']);
		}

		if(($lock_status==0)&&($ocation=='bill')&&($result2)){
			if($bm_type==3 || $bm_type==4 || $bm_type==5) $lock_new=2; else $lock_new=1;
			$query="SELECT SUM(qty*unit_price) FROM bill WHERE qty>0 AND invoice_no='$invoice_no'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$payment1=$row[0];
			if($payment1=='') $payment1=0;

			$query="SELECT SUM(qty*unit_price) FROM bill WHERE qty<0 AND invoice_no='$invoice_no'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$payment2=$row[0];
			if($payment2=='') $payment2=0;

			// ------------ added by nirmal 26_07_2023
			$result = mysqli_query($conn, "SELECT `value` FROM settings WHERE setting='tax'");
			$row = mysqli_fetch_assoc($result);
			$tax_rate = $row['value'];

			$result = mysqli_query($conn, "SELECT `value` FROM settings WHERE setting='decimal'");
			$row = mysqli_fetch_assoc($result);
			$decimal = $row['value'];

			$total = $payment1 + $payment2;
			$tax_added_value = ((($total) / ((100+$tax_rate)/100)) * ($tax_rate / 100));

			$query="UPDATE bill_main SET `lock`='$lock_new', `billed_timestamp`='$today', `invoice_+total`='$payment1', `invoice_-total`='$payment2', `tax` = '$tax_added_value' WHERE invoice_no='$invoice_no'";
			// ------------
			// $query="UPDATE bill_main SET `lock`='$lock_new', `billed_timestamp`='$today', `invoice_+total`='$payment1', `invoice_-total`='$payment2' WHERE invoice_no='$invoice_no'"; OLD CODE
			$result=mysqli_query($conn,$query);
			if(!$result){
				$message='Bill Main could not be Updated!';
				$out=false;
			}
		}else if(($bm_type==3)&&($ocation=='pay')&&($result2)){
			if($bm_status==4 || $bm_status==6){
				if($bm_status==4) $next_status=5;
				if($bm_status==6) $next_status=7;
				$query="UPDATE bill_main SET `deliverd_by`='$salesman',`billed_timestamp`='$today',`deliverd_timestamp`='$today',`status`='$next_status',`lock`='1' WHERE invoice_no='$invoice_no'";
				$result=mysqli_query($conn,$query);
				if(!$result){
					$message='Bill Main could not be Updated!';
					$out=false;
				}
			}
		}

		if(!$result2){
			$message='Payment could not be Added!';
			$out=false;
		}
	}
	return $out;
}

function addHPpayment(){
	global  $message,$invoice_no;
	$invoice_no=$_POST['invoice_no'];
	$pay_type=$_POST['pay_type'];
	$inst_date=$_POST['inst_date'];
	$pay_amount=$_POST['pay_amount'];
	$comment=$_POST['comment'];
	$tr_bank=$_POST['tr_bank'];
	$chque_no=$_POST['chque_no'];
	$chque_bank=$_POST['chque_bank'];
	$chque_branch=$_POST['chque_branch'];
	$chque_date=$_POST['chque_date'];
	$user_id=$_COOKIE['user_id'];
	$store=$_COOKIE['store'];
	$time_now=timeNow();
	$gps_x=0;
	$gps_y=0;
	$out=true;
	$message='The Instalment Was Added Successfully';
	include('config.php');

	$query="SELECT bm.`cust`,bm.`sub_system`,his.id,his.payment_amount,his.payment_count FROM bill_main bm, hp_inv_schedule his WHERE bm.invoice_no=his.invoice_no AND bm.invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$cust=$row[0];
	$sub_system=$row[1];
	$his_id=$row[2];
	$his_amount=$row[3];
	$his_count=$row[4];

	if($pay_type==1){
		$query="INSERT INTO `payment` (`invoice_no`,`bill_pay`,`cust`,`payment_type`,`amount`,`salesman`,`sys_user`,`payment_date`,`comment`,`store`,`gps_x`,`gps_y`,`sub_system`) VALUES ('$invoice_no','2','$cust','1','$pay_amount','$user_id','$user_id','$time_now','$comment','$store','$gps_x','$gps_y','$sub_system')";
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false; $message='Error: Failed to Add The Instalment'; }
	}
	if($pay_type==2){
		$query="INSERT INTO `payment` (`invoice_no`,`bill_pay`,`cust`,`payment_type`,`amount`,`salesman`,`sys_user`,`payment_date`,`bank_trans`,`comment`,`store`,`gps_x`,`gps_y`,`sub_system`) VALUES ('$invoice_no','2','$cust','3','$pay_amount','$user_id','$user_id','$time_now','$tr_bank','$comment','$store','$gps_x','$gps_y','$sub_system')";
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false; $message='Error: Failed to Add The Instalment'; }
	}
	if($pay_type==3){
		$query="SELECT id FROM bank WHERE bank_code='$chque_bank'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$chque_bank_id=$row[0];
		$query="INSERT INTO `payment` (`invoice_no`,`bill_pay`,`cust`,`payment_type`,`amount`,`salesman`,`sys_user`,`payment_date`,`chque_no`,`chque_bank`,`chque_branch`,`chque_date`,`comment`,`store`,`gps_x`,`gps_y`,`sub_system`) VALUES ('$invoice_no','2','$cust','2','$pay_amount','$user_id','$user_id','$time_now','$chque_no','$chque_bank_id','$chque_branch','$chque_date','$comment','$store','$gps_x','$gps_y','$sub_system')";
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false; $message='Error: Failed to Add The Instalment'; }
	}

	if($out){
		$pay_id=mysqli_insert_id($conn);
		$query="INSERT INTO `hp_payments` (`invoice_no`,`payment_no`,`schedule`,`instalment_date`) VALUES ('$invoice_no','$pay_id','$his_id','$inst_date')";
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false; $message='Error: Failed to Add The Instalment Record'; }
	}

	if($out){
		if(!setHPstatus($invoice_no)){ $out=false; $message='Error: Failed Update HP Schedule Status'; }
	}

	return $out;
}

function hpGetPendingAmount(){
	$invoice_no=$_POST['invoice_no'];
	$inst_date=$_POST['inst_date'];
	include('config.php');

	$query="SELECT his.payment_amount FROM hp_inv_schedule his WHERE his.invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$hp_amount=$row[0];

	$query="SELECT SUM(py.amount) FROM hp_payments hp, payment py WHERE hp.payment_no=py.id AND hp.invoice_no='$invoice_no' AND hp.instalment_date='$inst_date' AND py.`status`=0 AND py.chque_return=0 GROUP BY hp.instalment_date";
	$result=mysqli_query($conn,$query);
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$paid_amount=$row[0];

	if($paid_amount>0) $type='Remaining Payment'; else $type='';
	$remaining=$hp_amount-$paid_amount;

	$jasonArray["type"]=$type;
	$jasonArray["remaining"]=$remaining;
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

function hpGetPaidInstalment(){
	global $invoice_no,$inst_date,$hp_schedule,$hp_amount,$py_id,$py_date,$py_amount;
	$py_id=$py_date=$py_amount=$hp_schedule=array();
	if(isset($_GET['invoice_no'])&&isset($_GET['inst_date'])){
		$invoice_no=$_GET['invoice_no'];
		$inst_date=$_GET['inst_date'];

		include('config.php');
		$query="SELECT his.cal_start_date,hst.`name`,his.`day`,his.payment_amount,his.payment_count FROM hp_inv_schedule his, hp_schedule_type hst WHERE his.`type`=hst.id AND his.invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$hp_cal_start=$row[0];
		$hp_type=$row[1];
		$hp_date=$row[2];
		$hp_amount=$row[3];
		$hp_count=$row[4];

		$hp_schedule=hpsPaySchedule($hp_cal_start,$hp_type,$hp_date,$hp_count);


		$query="SELECT py.id,date(py.payment_date),py.amount FROM hp_payments hp, payment py WHERE hp.payment_no=py.id AND py.`status`=0 AND py.chque_return=0 AND hp.instalment_date='$inst_date' AND hp.invoice_no='$invoice_no'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$py_id[]=$row[0];
			$py_date[]=$row[1];
			$py_amount[]=$row[2];
		}
	}
}

function setStatusCrossTrans($invoice_no,$st){
	$user_id=$_COOKIE['user_id'];
	include('config.php');
	$query="UPDATE transfer_main SET `status`='$st' WHERE invoice_no='$invoice_no' AND `user`='$user_id'";
	$result=mysqli_query($conn,$query);
}

// update by nirmal 21_12_23
// update by nirmal 03_01_2024 (shows customer outstanding)
function generatePayment(){
	global $tm_shop,$tm_company,$tm_address,$tm_tel,$payment_id,$cust_id,$cust_name,$payment_type,$amount,
	$chque_no,$chque_bank,$chque_bank_code,$chque_branch,$chque_date,$salesman,$payment_date,$invoice_no,$comment,
	$payment_type_n,$bank_trans,$cu_mobile,$cu_nic,$print_time,$cu_details,$payment_time,$cust_st,$total_oust,$invoice_oust,
	$card_no, $pay_pro_fee, $credit_balance;
	$payment_id=$_REQUEST['id'];

	if(isMobile())	include('config.php');	else include('../../../../config.php');
	$query="SELECT cu.id,cu.name,py.payment_type,py.amount,py.chque_no,py.chque_bank,py.chque_branch,py.chque_date,up.username,date(py.payment_date),py.invoice_no,py.store,py.`comment`,py.bank_trans,cu.nic,cu.mobile,time(py.payment_date),cu.`status`,py.`card_no` FROM payment py, cust cu, userprofile up WHERE py.cust=cu.id AND py.salesman=up.id AND py.id='$payment_id'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$cust_id=$row[0];
		$cust_name=$row[1];
		$payment_type=$row[2];
		$amount=$row[3];
		$chque_no=$row[4];
		$chque_bank_id=$row[5];
		$chque_branch=$row[6];
		$chque_date=$row[7];
		$salesman=$row[8];
		$payment_date=$row[9];
		$invoice_no=$row[10];
		$store=$row[11];
		$comment=$row[12];
		$bnk_trans=$row[13];
		$cu_mobile=$row[15];
		$cu_nic=$row[14];
		$cu_details='NIC        : '.$row[14].'&#13;Mobile  : '.$row[15];
		$payment_time=$row[16];
		$cust_st=$row[17];
		$card_no=$row[18];
		if($payment_type==1) $payment_type_n='CASH'; else
		if($payment_type==2) $payment_type_n='CHEQUE'; else
		if($payment_type==3) $payment_type_n='BANK TRANSFER'; else
		if($payment_type==4) $payment_type_n='CARD PAYMENT';
	}

	$result = mysqli_query($conn,"SELECT `value` FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone=$row['value'];
	$print_time=date("Y-m-d H:i:s",time()+(60*60*$timezone));

	$result = mysqli_query($conn,"SELECT `name`,`bank_code` FROM bank WHERE id='$chque_bank_id'");
	$row = mysqli_fetch_assoc($result);
	$chque_bank=is_null($row) ? "" : $row['name'];
	$chque_bank_code=is_null($row) ? "" : $row['bank_code'];

	$result = mysqli_query($conn,"SELECT `name`,`processing_fee` FROM accounts WHERE id='$bnk_trans'");
	$row = mysqli_fetch_assoc($result);
	$bank_trans=is_null($row) ? "" : $row['name'];
	if($payment_type == 4) $pay_pro_fee=$row['processing_fee'];

	$result = mysqli_query($conn,"SELECT `name`,`shop_name`,`address`,`tel` FROM stores WHERE id='$store'");
	$row = mysqli_fetch_assoc($result);
	$tm_shop=$row['name'];
	$tm_company=$row['shop_name'];
	$tm_address=$row['address'];
	$tm_tel=$row['tel'];

	$json_array=json_decode(getCreditOutstanding2($cust_id,$invoice_no));
	$total_oust=$json_array->{"total_oust"};
	$invoice_oust=$json_array->{"invoice_oust"};

	customerTotalOutstandingBalance($cust_id);
}

function payDetails(){
	global $main_sub_system,$main_store,$main_paid_date,$main_paid_by,$main_sys_user,$main_deleted_date,$main_deleted_by,$main_sub_system_id,$main_sms,$sms_resend;
	$pay_no=$_GET['id'];
	$sms_resend=0;
	include('config.php');
	$query="SELECT id,username FROM userprofile";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$usercheck[$row[0]]=$row[1];
	}
	$usercheck['']='-';
	$usercheck[0]='-';

	$query="SELECT ss.name,st.name,py.payment_date,py.salesman,py.sys_user,py.deleted_date,py.deleted_by,ss.id,py.sms FROM payment py, stores st, sub_system ss WHERE py.store=st.id AND py.`sub_system`=ss.id AND py.id='$pay_no'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$main_sub_system=$row[0];
	$main_store=$row[1];
	$main_paid_date=$row[2];
	$main_paid_by=$usercheck[$row[3]];
	$main_sys_user=$usercheck[$row[4]];
	$main_deleted_date=$row[5];
	$main_deleted_by=$usercheck[$row[6]];
	$main_sub_system_id=$row[7];
	if($row[8]==1) $main_sms='SMS Sent'; else $main_sms='SMS Not Sent';

	$query1="SELECT id FROM sms WHERE `case`='2' AND `ref`='$pay_no'";
	$result1=mysqli_query($conn,$query1);
	$row1 = mysqli_fetch_row($result1);
	$sms_resend=is_null($row1) ? 0 : $row1[0];
}

function paymentDeletePermission($payment_no){
	include('config.php');
	$user=$_COOKIE['user_id'];
	$store=$_COOKIE['store'];
	$systemid=inf_systemid(1);
	$today=dateNow();
	$query="SELECT DISTINCT salesman,date(`payment_date`),`status` FROM payment WHERE id='$payment_no'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$salesman=$row[0];
	$date=$row[1];
	$status=$row[2];

	if(($user==$salesman)&&($today==$date)&&($status==0)) $paymentpermission=true; else $paymentpermission=false;
	if(($systemid==15)&&(isset($_COOKIE['settings']))&&($status==0)) $paymentpermission=true;
	return $paymentpermission;
}

function paymentPermission(){
	global $paymentpermission,$py_status,$status_out,$status_color;
	$payment_no=$_REQUEST['id'];
	$paymentpermission=paymentDeletePermission($payment_no);
	include('config.php');
	$query="SELECT DISTINCT `status` FROM payment WHERE id='$payment_no'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$py_status=$row[0];

	switch($py_status){
		case 0: $status_out='Paid'; $status_color='white'; break;
		case 1: $status_out='Deleted'; $status_color='#FF3300'; break;
	}
}

function deletePayment($n,$force){
	global $message,$cust;
	$payment_id=$_REQUEST['id'];
	$user_id=$_COOKIE['user_id'];
	$time_now=timeNow();
	$out=false;
	$message='Payment was Deleted Successfully!';
	if($n==1) include('config.php'); else
	if($n==2) include('../config.php');

	$query="SELECT `cust`,invoice_no FROM payment WHERE id='$payment_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$cust=$row[0];
	$invoice_no=$row[1];

	if($force==1){ $out=true; }else{ if(paymentDeletePermission($payment_id)) $out=true; }
	if(!$out){ $message='Error: Unauthorize Request!'; }
	if($out){
		$query="UPDATE `payment` SET `status`='1',`deleted_by`='$user_id',`deleted_date`='$time_now' WHERE `id`='$payment_id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false;	$message='Error: Payment could not be Deleted!'; }
	}
	if($out){
		$query="DELETE FROM `hp_payments` WHERE `payment_no`='$payment_id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false;	$message='Error: Hire Purchase Record could not be Deleted!'; }
	}

	if(($out)&&($invoice_no!='')){
		if(!setHPstatus($invoice_no)){ $out=false; $message='Error: Failed Update HP Schedule Status'; }
	}

	return $out;
}
//--------------------------------------Item Return----------------------------//

function getReturnItems(){
	global $rt_id,$rt_itmdesc,$rt_qty,$rt_no_update;
	$rt_id=$rt_itmdesc=$rt_qty=$rt_no_update=array();
	if(isset($_REQUEST['id'])){
	$rtninvoice_no=$_REQUEST['id'];
	include('config.php');
		$query="SELECT rt.id,itm.description,rt.qty,rt.no_update FROM `return` rt, inventory_items itm WHERE rt.return_item=itm.id AND rt.invoice_no='$rtninvoice_no'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$rt_id[]=$row[0];
			$rt_itmdesc[]=$row[1];
			$rt_qty[]=$row[2];
			$rt_no_update[]=$row[3];
		}
	}
}

function newReturn($cust0,$gps_x,$gps_y){
	global $cust,$invoice_no,$message;
	$store=$_COOKIE['store'];
	$sub_system=$_COOKIE['sub_system'];
	$user_id=$_COOKIE['user_id'];
	$time_now=timeNow();
	$cust=$cust0;
	include('config.php');
	$result = mysqli_query($conn,"SELECT MAX(invoice_no) as `maxno` FROM return_main");
	$row = mysqli_fetch_assoc($result);
	$invoice_no=$row['maxno'];
	if($invoice_no==''){ $invoice_no=1;	}

	$result = mysqli_query($conn,"SELECT COUNT(id) as `count` FROM `return` WHERE invoice_no='$invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$item_count=$row['count'];
	if($item_count==0){
		$query2="DELETE FROM `return_main` WHERE `invoice_no` = '$invoice_no'";
		$result2=mysqli_query($conn,$query2);
	}else{
		$invoice_no=$invoice_no+1;
	}

	$result = mysqli_query($conn,"SELECT up.mapped_inventory FROM userprofile up WHERE up.id='$user_id'");
	$row = mysqli_fetch_assoc($result);
	if($row['mapped_inventory']!=0) $mapped_inventory=$row['mapped_inventory']; else $mapped_inventory=$store;

	$query="INSERT INTO `return_main` (`invoice_no`,`return_date`,`return_by`,`cust`,`store`,`mapped_inventory`,`gps_x`,`gps_y`,`sub_system`,`status`) VALUES ('$invoice_no','$time_now','$user_id','$cust','$store','$mapped_inventory','$gps_x','$gps_y','$sub_system','1')";
	$result3=mysqli_query($conn,$query);

	if($cust!='' && $result3){
		return true;
	}else{
		$$message="Error: Failed to Create Return Invoice";
		return false;
	}
}

// updated by nirmal 08_08_2023
function apendReturn(){
	global $message,$return_invoice_no,$cust;
	$return_invoice_no=$_REQUEST['id'];
	$return_itemid=$_REQUEST['return_itemid'];
	$replace_itemid=$_REQUEST['replace_itemid'];
	$qty=$_REQUEST['qty'];
	$salesman=$_COOKIE['user_id'];
	$cust=$_REQUEST['cust'];
	$store=$_COOKIE['store'];
	$unic_item1=$_REQUEST['unic_item1'];
	if($unic_item1==0) $unic_item1='';
	$unic_item2=$_REQUEST['unic_item2'];
	if($unic_item2==0) $unic_item2='';
	$extra_pay=$_REQUEST['extra_pay'];
	if($extra_pay=='') $extra_pay=0;
	if(isset($_POST['gps_x'])) $gps_x=$_POST['gps_x']; else $gps_x=0;
	if(isset($_POST['gps_y'])) $gps_y=$_POST['gps_y']; else $gps_y=0;
	$is_inuse_invno='';
	$result4=false;
	$proceed=true;

	include('config.php');
	$result = mysqli_query($conn,"SELECT COUNT(invoice_no) as `count`,`status` FROM return_main WHERE return_by='$salesman' AND invoice_no='$return_invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$bm_exist=$row['count'];
	$rm_status=$row['status'];
	if($bm_exist==0){
		newReturn($cust,$gps_x,$gps_y);
		$result = mysqli_query($conn,"SELECT MAX(invoice_no) as `invoice_no` FROM return_main WHERE return_by='$salesman'");
		$row = mysqli_fetch_assoc($result);
		$return_invoice_no=$row['invoice_no'];
	}

	$result = mysqli_query($conn,"SELECT up.mapped_inventory FROM userprofile up WHERE up.id='$salesman'");
	$row = mysqli_fetch_assoc($result);
	if($row['mapped_inventory']!=0) $mapped_inventory=$row['mapped_inventory']; else $mapped_inventory=$store;

	$result = mysqli_query($conn,"SELECT on_place_replace FROM stores WHERE id='$mapped_inventory'");
	$row = mysqli_fetch_assoc($result);
	$on_place_replace=$row['on_place_replace'];

	$result = mysqli_query($conn,"SELECT id FROM inventory_qty WHERE location='$mapped_inventory' AND item='$return_itemid'");
	$row = mysqli_fetch_assoc($result);
	$return_itq_id=$row['id'];
	$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE location='$mapped_inventory' AND item='$replace_itemid'");
	$row = mysqli_fetch_assoc($result);
	$replace_itq_id=$row['id'];
	$replace_itq_qty=$row['qty'];

	$result = mysqli_query($conn,"SELECT count(id) as `count` FROM inventory_unic_item WHERE sn='$unic_item1' AND `status`='1'");
	$row = mysqli_fetch_assoc($result);
	$ok_item1=$row['count'];
	$result = mysqli_query($conn,"SELECT count(id) as `count` FROM inventory_unic_item WHERE sn='$unic_item2' AND `status`='0'");
	$row = mysqli_fetch_assoc($result);
	$ok_item2=$row['count'];


	if($proceed){ if($rm_status==1){ $proceed=true; }else{ $proceed=false; $message='Error: Cannot Add items to a Locked Invoice'; }}
	if($proceed){
		if((($ok_item1==1)&&($ok_item2==1))||(($unic_item1==0)&&($unic_item2==0))){
			$proceed=true;
		}else{
			$proceed=false;
			$message='Invalid Item Detected!';
		}
	}

	if($proceed){ if(mismatch($return_itq_id)){ $proceed=true; }else{ $proceed=false; $message='Error 107. Please contact Support !'; }}
	if($proceed){ if(mismatch($replace_itq_id)){ $proceed=true; }else{ $proceed=false; $message='Error 107. Please contact Support !'; }}

	if(($ok_item1==1)&&($ok_item2==1))	$qty=1;
	$new_qty=$replace_itq_qty-$qty;

	if(($on_place_replace==1)&&($proceed)){ if($new_qty>=0){ $proceed=true; }else{ $proceed=false; $message='Insufficient Quantity!'; }}

	if($proceed){
		$query2="INSERT INTO `return` (`invoice_no`,`return_item`,`replace_item`,`return_sn`,`replace_sn`,`extra_pay`,`qty`,`status`) VALUES ('$return_invoice_no','$return_itemid','$replace_itemid','$unic_item1','$unic_item2','$extra_pay','$qty','0')";
		$result2=mysqli_query($conn,$query2);
		$lastitem=mysqli_insert_id($conn);
		if($result2){
			if($on_place_replace==1){
				$query="UPDATE `return` SET `odr_packed`='1' WHERE `id`='$lastitem'";
				$result=mysqli_query($conn,$query);
			}
			if(($ok_item1==1)&&($ok_item2==1)){
				$query="UPDATE `return` SET `no_update`='999999999' WHERE `id`='$lastitem'";
				$result=mysqli_query($conn,$query);
				$query2="UPDATE `inventory_unic_item` SET `status`='4',`return_id`='$lastitem' WHERE `sn`='$unic_item1' AND `status`='1'";
				$result2=mysqli_query($conn,$query2);
				if($result2){
				$query3="UPDATE `inventory_unic_item` SET `status`='5',`return_id`='$lastitem' WHERE `sn`='$unic_item2' AND `status`='0'";
				$result3=mysqli_query($conn,$query3);
				}
				if(($on_place_replace==1)&&($result3)){
				$query4="UPDATE `inventory_qty` SET `qty`=qty-1 WHERE `id`='$replace_itq_id'";
				$result4=mysqli_query($conn,$query4);
				}
			}else{
				if($on_place_replace==1){
					$query4="UPDATE `inventory_qty` SET `qty`=qty-$qty WHERE `id`='$replace_itq_id'";
					$result4=mysqli_query($conn,$query4);
				}else{
					$result4=true;
				}
			}
		}
		if($result4){
			if($on_place_replace==1) processInventoryNew($replace_itemid,$lastitem,$mapped_inventory,'return');
			$message='Item was Added to the Return Invoice!';
			return true;
		}else{
			$message='Item could not be Added to Return Invoice!';
			return false;
		}
	}else{
		return false;
	}
}

function updateReturnitem(){
	global $message,$cust,$return_invoice_no;
	$itemid=$_REQUEST['id'];
	$return_invoice_no=$_REQUEST['return_invoice_no'];
	$qty=$_REQUEST['qty'];
	$cust=$_REQUEST['cust'];
	$store=$_COOKIE['store'];
	$result=false;

	include('config.php');
	$result0 = mysqli_query($conn,"SELECT itq.id,itq.qty as `itqqty`,rt.qty as `rtqty` FROM inventory_qty itq, `return` rt WHERE rt.replace_item=itq.item AND itq.location='$store' AND rt.id='$itemid'");
	$row0 = mysqli_fetch_assoc($result0);
	$itq_id=$row0['id'];
	$itq_qty=$row0['itqqty'];
	$rt_qty=$row0['rtqty'];

	$new_qty=$itq_qty+$rt_qty-$qty;
	if(($itq_qty+$rt_qty)>=$qty){
		$query2="UPDATE `inventory_qty` SET `qty`='$new_qty' WHERE `id`='$itq_id'";
		$result2=mysqli_query($conn,$query2);
		if($result2){
		$query="UPDATE `return` SET `qty`='$qty' WHERE `id`=$itemid";
		$result=mysqli_query($conn,$query);
		}
		if($result){
			$message='Item QTY was Updated!';
			return true;
		}else{
			$message='Item could not be Updated!';
			return false;
		}
	}else{
		$message='Insufficient Quantity in the Inventory!';
		return false;
	}
}

function removeReturnitem(){
	global $message,$cust,$return_invoice_no;
	$itemid=$_REQUEST['id'];
	$return_invoice_no=$_REQUEST['return_invoice_no'];
	$cust=$_REQUEST['cust'];
	$store=$_COOKIE['store'];
	$output=false;

	include('config.php');
		$result0 = mysqli_query($conn,"SELECT itq.id,itq.qty as `itqqty`,rt.qty as `rtqty`,itq.w_price,itq.r_price,itq.c_price,itq.item,rt.no_update,rm.mapped_inventory FROM return_main rm, `return` rt, inventory_qty itq WHERE rm.invoice_no=rt.invoice_no AND rt.replace_item=itq.item AND itq.location=rm.`mapped_inventory` AND rt.id='$itemid'");
		$row0 = mysqli_fetch_assoc($result0);
		$itq_id=$row0['id'];
		$itq_qty=$row0['itqqty'];
		$rt_qty=$row0['rtqty'];
		$itq_wprice=$row0['w_price'];
		$itq_rprice=$row0['r_price'];
		$itq_cprice=$row0['c_price'];
		$itq_item=$row0['item'];
		$no_update=$row0['no_update'];
		$mapped_inventory=$row0['mapped_inventory'];

	$result = mysqli_query($conn,"SELECT on_place_replace FROM stores WHERE id='$mapped_inventory'");
	$row = mysqli_fetch_assoc($result);
	$on_place_replace=$row['on_place_replace'];
	if($on_place_replace==1){
		if(($no_update==0)||($no_update==999999999)){
			$new_qty=$itq_qty+$rt_qty;
			$query="UPDATE `inventory_qty` SET `qty`='$new_qty' WHERE `id`=$itq_id";
			$result7=mysqli_query($conn,$query);
		}else{
			$query="INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`) VALUES ('$itq_item','$itq_wprice','$itq_rprice','$itq_cprice','$itq_qty','$store')";
			$result2=mysqli_query($conn,$query);

			$result = mysqli_query($conn,"SELECT w_price,r_price,c_price FROM inventory_temp WHERE id='$no_update'");
			$row = mysqli_fetch_assoc($result);
			$tt_wprice=$row['w_price'];
			$tt_rprice=$row['r_price'];
			$tt_cprice=$row['c_price'];

			$query="UPDATE `inventory_qty` SET `w_price`='$tt_wprice',`r_price`='$tt_rprice',`c_price`='$tt_cprice',`qty`='$rt_qty' WHERE `id`=$itq_id";
			$result7=mysqli_query($conn,$query);
		}
	}else{
		$result7=true;
	}
	if($result7){
		$query6="DELETE FROM `return` WHERE `id`='$itemid'";
		$result6=mysqli_query($conn,$query6);
	}

		if($result6){
			$output=true;
			$query4="UPDATE `inventory_unic_item` SET `status`='1',`return_id`='0' WHERE `return_id`='$itemid' AND `status`='4'";
			$result4=mysqli_query($conn,$query4);
			$query4="UPDATE `inventory_unic_item` SET `status`='0',`return_id`='0' WHERE `return_id`='$itemid' AND `status`='5'";
			$result4=mysqli_query($conn,$query4);
		}

		if($output){
			$message='Item was Removed from Return Invoice!';
			return true;
		}else{
			$message='Item could not be Removed!';
			return false;
		}
}

function deleteReturn(){
	global $message;
	$return_invoice_no=$_REQUEST['id'];
	$user_id=$_COOKIE['user_id'];
	$today=timeNow();
	if(returnPermission($return_invoice_no)){
	include('config.php');

	$result = mysqli_query($conn,"SELECT mapped_inventory FROM return_main WHERE invoice_no='$return_invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$mapped_inventory=$row['mapped_inventory'];

	$result = mysqli_query($conn,"SELECT on_place_replace FROM stores WHERE id='$mapped_inventory'");
	$row = mysqli_fetch_assoc($result);
	$on_place_replace=$row['on_place_replace'];

	$query0="SELECT ivq.id,rt.qty,rt.id,ivq.w_price,ivq.r_price,ivq.c_price,ivq.item,rt.no_update,ivq.location,rm.mapped_inventory FROM return_main rm, `return` rt, inventory_qty ivq WHERE rm.invoice_no=rt.invoice_no AND ivq.item=rt.replace_item AND ivq.location=rm.mapped_inventory AND rm.`invoice_no`='$return_invoice_no' ORDER BY rt.id DESC";
	$result0=mysqli_query($conn,$query0);
	while($row0=mysqli_fetch_array($result0)){
		$ivq_id=$row0[0];
		$rt_qty=$row0[1];
		$rt_id=$row0[2];
		$ivq_wprice=$row0[3];
		$ivq_rprice=$row0[4];
		$ivq_cprice=$row0[5];
		$ivq_item=$row0[6];
		$rt_noupdate=$row0[7];
		$store=$row0[8];

		if($on_place_replace==1){
			$debug_id=debugStart($rt_id,0);
			if(($rt_noupdate==0)||($rt_noupdate==999999999)){
				$query="UPDATE `inventory_qty` SET `qty`=qty+$rt_qty WHERE `id`=$ivq_id";
				$result7=mysqli_query($conn,$query);
			}else{
				$result = mysqli_query($conn,"SELECT qty FROM inventory_qty WHERE `id`=$ivq_id");
				$row = mysqli_fetch_assoc($result);
				$ivq_qty=$row['qty'];
				$query="INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`) VALUES ('$ivq_item','$ivq_wprice','$ivq_rprice','$ivq_cprice','$ivq_qty','$store')";
				$result2=mysqli_query($conn,$query);
				$query="SELECT w_price,r_price,c_price FROM inventory_temp WHERE id='$rt_noupdate'";
				$result=mysqli_query($conn,$query);
				while($row=mysqli_fetch_array($result)){
					$tt_wprice=$row[0];
					$tt_rprice=$row[1];
					$tt_cprice=$row[2];
				}
				$query="UPDATE `inventory_qty` SET `w_price`='$tt_wprice',`r_price`='$tt_rprice',`c_price`='$tt_cprice',`qty`='$rt_qty' WHERE `id`=$ivq_id";
				$result7=mysqli_query($conn,$query);
			}
		}else{
			$result7=true;
		}
			if($result7){
				$query8="UPDATE `inventory_unic_item` SET `status`='1',`return_id`='0' WHERE `return_id`='$rt_id' AND `status`='4'";
				$result8=mysqli_query($conn,$query8);
				$query8="UPDATE `inventory_unic_item` SET `status`='0',`return_id`='0' WHERE `return_id`='$rt_id' AND `status`='5'";
				$result8=mysqli_query($conn,$query8);
				if($on_place_replace==1) debugEnd($debug_id,'success');
			}else{
				if($on_place_replace==1) debugEnd($debug_id,'fail');
			}
		}
		if($result7){
			$query6="UPDATE `return_main` SET `status`='0',`deleted_date`='$today',`deleted_by`='$user_id' WHERE `invoice_no` = '$return_invoice_no'";
			$result6=mysqli_query($conn,$query6);
		}

		if($result6){
			$message='Return Invoice was Deleted Successfully!';
			return true;
		}else{
			$message='Return Invoice Could Not be Deleted !';
			return false;
		}
	}else{
		$message='You dont have permission to Delete this Return Invoice!';
		return false;
	}
}

function generateRtnInvoice(){
	global $tm_shop,$tm_company,$tm_address,$tm_tel,$return_invoice_no,$bill_id,$bill_date,$bill_salesman,$bill_item,$bill_qty,$bill_custid,$bill_cust,$extra_pay;
	$return_invoice_no=$_REQUEST['id'];
	$isMobile=isMobile();
	if($isMobile)	include('config.php');	else include('../../../../config.php');
		$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='paper_size'");
		$row = mysqli_fetch_assoc($result);
	    $paper_size=$row['value'];
	    if($paper_size=='A4') $break_unic='&nbsp;&nbsp;';
	    if($paper_size=='A5') $break_unic='<br />';
		if($isMobile) $break_unic='<br />';

		$query="SELECT date(rm.return_date),up.username,cu.id,cu.name,rm.`store`,rm.mapped_inventory FROM return_main rm, cust cu, userprofile up WHERE rm.`cust`=cu.id AND rm.return_by=up.id AND rm.invoice_no='$return_invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$bill_date=$row[0];
		$bill_salesman=$row[1];
		$bill_custid=$row[2];
		$bill_cust=$row[3];
		$store=$row[4];
		$mapped_inventory=$row[5];

		$query="SELECT rt.id,itm1.description,itm2.description,rt.qty,rt.extra_pay,rt.return_sn,rt.replace_sn FROM `return` rt, inventory_items itm1, inventory_items itm2 WHERE rt.return_item=itm1.id AND rt.replace_item=itm2.id AND rt.invoice_no='$return_invoice_no'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$rt_id=$row[0];
			$returned_unic=$row[5];
			$replaced_unic=$row[6];
			$bill_id[]=$row[0];
			if($returned_unic!='' || $replaced_unic!=''){
				$bill_item[]='RETURN &nbsp;&nbsp;:&nbsp;&nbsp; '.$row[1].'&nbsp;&nbsp;&nbsp;'.$break_unic.'[ '.$returned_unic.' ]'.'<br />REPLACE : &nbsp;&nbsp;'.$row[2].'&nbsp;'.$break_unic.'[ '.$replaced_unic.' ]';
			}else $bill_item[]='RETURN &nbsp;&nbsp;:&nbsp;&nbsp; '.$row[1].'<br />REPLACE : &nbsp;&nbsp;'.$row[2];
			$bill_qty[]=$row[3];
			$extra_pay[]=$row[4];
		}
	$result = mysqli_query($conn,"SELECT name,shop_name,address,tel FROM stores WHERE id='$mapped_inventory'");
	$row = mysqli_fetch_assoc($result);
	$tm_shop=$row['name'];
	$tm_company=$row['shop_name'];
	$tm_address=$row['address'];
	$tm_tel=$row['tel'];
}

function getReplacementsn(){
	$itu_sn=array();
	$store=$_COOKIE['store'];
	$replace_itemid=$_GET['replace_itemid'];
	include('config.php');
	$query="SELECT itq.id,itm.unic FROM inventory_items itm, inventory_qty itq WHERE itm.id=itq.item AND itq.location='$store' AND itm.id='$replace_itemid'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$itq_id=$row[0];
	$item_unic=$row[1];
	if($item_unic==1){
		$query="SELECT sn FROM inventory_unic_item WHERE itq_id='$itq_id' AND `status`=0";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$itu_sn[]=$row[0];
		}
	}
	$sn= implode(',', $itu_sn);
	return $sn;
}

function getPricediff(){
	$store=$_COOKIE['store'];
	$return_itemid=$_GET['return_itemid'];
	$replace_itemid=$_GET['replace_itemid'];
	include('config.php');
	$query="SELECT itq.c_price FROM inventory_items itm, inventory_qty itq WHERE itm.id=itq.item AND itq.location='$store' AND itm.id='$return_itemid'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$return_cost=$row[0];
	$query="SELECT itq.c_price FROM inventory_items itm, inventory_qty itq WHERE itm.id=itq.item AND itq.location='$store' AND itm.id='$replace_itemid'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$replace_cost=$row[0];
	$diff=$replace_cost-$return_cost;
	return $diff;
}
//------------------------------------------Chque Return------------------------------------//

function getReturnChque($user){
	global $chq0_id,$chq0_invno,$chq0_cuname,$chq0_amount,$chq0_no,$chq0_bank,$chq0_branch,$chq0_date,$chq0_paymentdate,$chq0_returndate,$chq0_code,$chq0_salesman,$salesman_filter,$chq2_banked,$chq2_retuned,$chq2_postpone;
	$store=$_COOKIE['store'];
	$chq0_code=$salesman_filter=$chq0_salesman=$chq0_id=array();

	include('config.php');

	if($user=='all') $userfilter=''; else $userfilter="AND up.`id`='".$user."'";
	if($_GET['components']=='supervisor') $storefilter="AND py.`store`='".$store."'"; else $storefilter='';
	$custfilter='';
	if(isset($_GET['bill_no'])){
		$bill_no_t = $_GET['bill_no'];
		$query1="SELECT cust FROM bill_main_tmp WHERE bm_no='$bill_no_t'";
		$result = mysqli_query($conn,$query1);
		$row = mysqli_fetch_assoc($result);
		$custfilter="AND cu.`id`='".$row['cust']."'";
	}


			$query1="SELECT py.id,py.invoice_no,cu.name,py.amount,py.chque_no,ba.name,ba.bank_code,py.chque_branch,py.chque_date,py.payment_date,date(py.chque_return_date),up.username FROM payment py, bank ba, userprofile up, cust cu WHERE py.cust=cu.id AND py.salesman=up.id AND py.chque_bank=ba.id AND py.`status`=0 AND py.chque_return=1 AND py.chque_rtn_clear=0 $custfilter $userfilter $storefilter ORDER BY py.chque_return_date";
			$result1=mysqli_query($conn,$query1);
			while($row1=mysqli_fetch_array($result1)){
					$chq0_id[]=$row1[0];
					if($row1[1]!=0) $chq0_invno[]=str_pad($row1[1], 7, "0", STR_PAD_LEFT); else $chq0_invno[]='';
					$chq0_cuname[]=$row1[2];
					$chq0_amount[]=$row1[3];
					$chq0_no[]=$row1[4];
					$chq0_bank[]=$row1[5];
					$chq0_branch[]=$row1[7];
					$chq0_date[]=$row1[8];
					$chq0_paymentdate[]=$row1[9];
					$chq0_returndate[]=$row1[10];
					$chq0_code[]=$row1[4].'-'.$row1[6].'-'.$row1[7];
					$chq0_salesman[]=$row1[11];
			}
			$salesman_filter=array_unique($chq0_salesman);
			$salesman_filter=array_values($salesman_filter);
	if(isset($_GET['cust'])){
		$cust=$_GET['cust'];
		$result = mysqli_query($conn,"SELECT COUNT(id) AS `count` FROM payment WHERE payment_type=2 AND cust='$cust' AND `status`=0 AND chque_clear=1");
		$row = mysqli_fetch_assoc($result);
		$chq2_banked=$row['count'];
		$result = mysqli_query($conn,"SELECT COUNT(id) AS `count` FROM payment WHERE payment_type=2 AND cust='$cust' AND `status`=0 AND chque_return=1");
		$row = mysqli_fetch_assoc($result);
		$chq2_retuned=$row['count'];
		$result = mysqli_query($conn,"SELECT COUNT(id) AS `count` FROM payment WHERE payment_type=2 AND cust='$cust' AND `status`=0 AND chque_postpone=1");
		$row = mysqli_fetch_assoc($result);
		$chq2_postpone=$row['count'];
	}
}


function setChqRtnClear(){
	global $message;
	$id=$_REQUEST['id'];
	$time_now=timeNow();
	include('config.php');
		$query="UPDATE `payment` SET `chque_rtn_clear`='1',`chque_rtn_cle_date`='$time_now' WHERE `id`='$id'";
		$result=mysqli_query($conn,$query);

		if($result){
			$message='The Return Chque was Marked as Clear';
			return true;
		}else{
			$message='The Return Chque could not be Cleared';
			return false;
		}
}

function searchPayments(){
	global $invoice_id,$bill_total,$sh_payid,$sh_amount,$sh_date,$sh_color,$chq_return;
	$sh_payid=array();
	if(isset($_REQUEST['invoice_id'])){
		if($_REQUEST['invoice_id']!=''){
			$invoice_id=$_REQUEST['invoice_id'];
			include('config.php');
			$query="SELECT id,payment_type,amount,date(payment_date),chque_return FROM payment WHERE invoice_no='$invoice_id'";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_array($result)){
				$sh_payid[]=$row[0];
				$sh_amount[]=$row[2];
				$sh_date[]=$row[3];
				if($row[1]==1) $sh_color[]='green';
				if($row[1]==2){
					if($row[4]==0) $sh_color[]='blue'; else	$sh_color[]='red';
				}
				$chq_return[]=$row[4];
			}
			$result = mysqli_query($conn,"SELECT SUM(qty*unit_price) as `amo` FROM bill WHERE invoice_no='$invoice_id'");
			$row = mysqli_fetch_assoc($result);
			$bill_total=$row['amo'];
		}
	}
}

function returnPermission($invoice_no){
	$user=$_COOKIE['user_id'];
	$today=dateNow();
	include('config.php');
	$query="SELECT return_by,date(`return_date`),`status`,`store`,cust FROM return_main WHERE invoice_no='$invoice_no'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$salesman=$row[0];
	$date=$row[1];
	$rm_status=$row[2];
	$rm_store=$row[3];
	$rm_cust=$row[4];

	if(($user==$salesman)&&($today==$date)&&($rm_status!=0)) $billpermission=true; else $billpermission=false;
	return $billpermission;
}

function returnDetails(){
	global $billpermission,$rm_status,$rm_cust,$status_out,$status_color,$main_sub_system,$main_store,$main_refinv,$main_returned_date,$main_returned_by,$main_process,$main_deleted_date,$main_deleted_by;
	$invoice_no=$_GET['id'];
	$user=$_COOKIE['user_id'];
	$store=$_COOKIE['store'];
	$systemid=inf_systemid(1);
	$today=dateNow();
	include('config.php');
	$query="SELECT id,username FROM userprofile";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$usercheck[$row[0]]=$row[1];
	}
	$usercheck['']='-';

	$query="SELECT ss.name,s1.name,s2.name,rm.return_date,rm.return_by,count(rt.id),sum(rt.`status`),rm.deleted_date,rm.deleted_by FROM return_main rm, `return` rt, sub_system ss, stores s1, stores s2 WHERE rm.invoice_no=rt.invoice_no AND rm.`sub_system`=ss.id AND rm.store=s1.id AND rm.mapped_inventory=s2.id AND rm.invoice_no='$invoice_no'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$main_sub_system=$row[0];
	$main_store=$row[1];
	$main_refinv=$row[2];
	$main_returned_date=$row[3];
	$main_returned_by=$usercheck[$row[4]];
	if($row[5]==$row[6]) $main_process='Processed'; else if($row[6]==0) $main_process='Pending'; else if($row[6]>0) $main_process='Partially Processed';
	$main_deleted_date=$row[7];
	$main_deleted_by=$usercheck[$row[8]];

	$query="SELECT return_by,date(`return_date`),`status`,`store`,cust FROM return_main WHERE invoice_no='$invoice_no'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$salesman=$row[0];
	$date=$row[1];
	$rm_status=$row[2];
	$rm_store=$row[3];
	$rm_cust=$row[4];

	switch($rm_status){
		case 0: $status_out='Deleted'; $status_color='#FF3300'; break;
		case 1: $status_out='On-Going'; $status_color='yellow'; break;
		case 2: $status_out='Finished'; $status_color='white'; break;
	}
	if(($user==$salesman)&&($today==$date)&&($rm_status!=0)) $billpermission=true; else $billpermission=false;
}

function finalizeReturn(){
	global $message;
	$id=$_GET['id'];
	include('config.php');
	$query="UPDATE `return_main` SET `status`='2' WHERE `invoice_no`='$id'";
	$result=mysqli_query($conn,$query);

	if($result){
		$message='';
		return true;
	}else{
		$message='Error: The Return Invoice Could Not be Finalized';
		return false;
	}
}

//---------------------------------Cheque OPS-----------------------------------------------//
// added by E.S.P Nirmal 21_07_01
function checkOPS(){
	// return cheques
	global $chq0_id,$chq0_invno,$chq0_cuname,$chq0_amount,$chq0_no,$chq0_bank,$chq0_branch,$chq0_date,$chq0_paymentdate,$chq0_returndate,$chq0_code,$chq0_salesman,$salesman_filter,$chq2_banked,$chq2_retuned,
	$chq0_group_id, $chq0_group_name, $chq0_user_id, $chq0_username,$chq2_postpone,$salesman,$group,
	$chq0_postpond_id, $chq0_postpond_invno, $chq0_postpond_cuname, $chq0_postpond_amount, $chq0_postpond_no, $chq0_postpond_bank, $chq0_postpond_branch, $chq0_postponed_date, $chq0_postpond_paymentdate, $chq0_postpond_salesman, $chq0_postpond_date, $chq0_postpone, $chq0_postpond_code, $salesman_postpond_filter;

	$chq0_user_id=$chq0_group_id=$chq0_postpond_id=$chq0_code=$salesman_filter=$chq0_salesman=$chq0_id=array();

	$user_id=$_COOKIE['user_id'];
	$group =  $_GET['group'];
	$salesman =  $_GET['salesman'];

	if($group == 'all') {
		$groupfilter = "";
	} else {
		$groupfilter = "AND cu.associated_group = '".$group."'";
	}

	if($salesman== "all"){
		$userfilter = '';
	}else{
		$userfilter = "AND py.salesman = '".$salesman."'";
	}

	include('config.php');
	$query =  "SELECT cg.id, cg.name FROM user_to_group utp, cust_group cg WHERE cg.id = utp.`group` AND utp.`user` = $user_id ORDER BY cg.`name`";
	$result = mysqli_query($conn2,$query);
	while($row = mysqli_fetch_array($result)){
		$chq0_group_id[] = $row[0];
		$chq0_group_name[] = $row[1];
	}

	$query = "SELECT DISTINCT id, username FROM userprofile WHERE status = 0 ORDER BY username";
	$result = mysqli_query($conn2,$query);
	while($row = mysqli_fetch_array($result)){
		$chq0_user_id[] = $row[0];
		$chq0_username[] = $row[1];
	}

	// return chques
	$query = "SELECT py.id,py.invoice_no,cu.`name`,py.amount,py.chque_no,ba.`name`,ba.bank_code,py.chque_branch,py.chque_date,py.payment_date,DATE(py.chque_return_date),up.username
	FROM cust cu, userprofile up, payment py, bank ba, user_to_group utg
	WHERE py.cust = cu.id AND py.chque_bank=ba.id AND py.`salesman`=up.id AND cu.associated_group=utg.`group`
	AND py.`status`= 0 AND py.chque_return='1'AND py.chque_rtn_clear=0 $userfilter $groupfilter AND utg.`user`=$user_id ORDER BY py.chque_return_date";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$chq0_id[]=$row[0];
		if($row[1]!=0) $chq0_invno[]=str_pad($row[1], 7, "0", STR_PAD_LEFT); else $chq0_invno[]='';
		$chq0_cuname[]=$row[2];
		$chq0_amount[]=$row[3];
		$chq0_no[]=$row[4];
		$chq0_bank[]=$row[5];
		$chq0_branch[]=$row[7];
		$chq0_date[]=$row[8];
		$chq0_paymentdate[]=$row[9];
		$chq0_returndate[]=$row[10];
		$chq0_code[]=$row[4].'-'.$row[6].'-'.str_pad($row[7], 3, "0", STR_PAD_LEFT);
		$chq0_salesman[]=$row[11];
	}

	// postponded cheques
	$query = "SELECT py.id,py.invoice_no,cu.name,py.amount,py.chque_no,ba.name,ba.bank_code,py.chque_branch,py.chque_date,py.payment_date,up.username,date(py.chque_date2),py.chque_postpone
	FROM cust cu, userprofile up, payment py, bank ba, user_to_group utg
	WHERE py.cust = cu.id AND py.chque_bank=ba.id AND py.`salesman`=up.id AND cu.associated_group=utg.`group`
	AND py.`status`= 0 AND py.chque_postpone='1' $userfilter $groupfilter AND utg.`user`=$user_id ORDER BY py.chque_postpone,py.chque_date,py.chque_date2 DESC";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$chq0_postpond_id[] = $row[0];
		if ($row[1] != 0) $chq0_postpond_invno[] = str_pad($row[1], 7, "0", STR_PAD_LEFT);
		else $chq0_postpond_invno[] = '';
		$chq0_postpond_cuname[] = $row[2];
		$chq0_postpond_amount[] = $row[3];
		$chq0_postpond_no[] = $row[4];
		$chq0_postpond_bank[] = $row[5];
		$chq0_postpond_branch[] = $row[7];
		$chq0_postponed_date[] = $row[8];
		$chq0_postpond_paymentdate[] = $row[9];
		$chq0_postpond_salesman[] = $row[10];
		$chq0_postpond_date[] = $row[11];
		if ($row[12] == 1) $chq0_postpone[] = 'Postponed';
		if ($row[12] == 2) $chq0_postpone[] = 'Postpone-Clear';
		$chq0_postpond_code[] = $row[4].'-'.$row[6].'-'.str_pad($row[7], 3, "0", STR_PAD_LEFT);
	}
}

function tag_gps(){
	$id=$_GET['id'];
	$gps_x=$_GET['gps_x'];
	$gps_y=$_GET['gps_y'];
	include('config.php');
	$query="UPDATE `cust` SET `gps_x`='$gps_x',`gps_y`='$gps_y' WHERE `id`='$id'";
	$result=mysqli_query($conn,$query);

	if($result){
		return 'done';
	}else{
		return 'fail';
	}
}

function getWarrantyOngoingList(){
	global $wa_list_id,$wa_cust;
	$wa_list_id=$wa_cust=array();
	include('config.php');
	$query="SELECT wa.id,cu.name FROM warranty wa LEFT JOIN  cust cu ON wa.customer=cu.id WHERE wa.`status` IN (1,2,3) ORDER BY wa.id";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$wa_list_id[]=$row[0];
		if($row[1]==''){
			$wa_cust[]='In Store';
		}else{
			if(strlen($row[1])>15) $wa_cust[]=substr($row[1],0,15).'...'; else $wa_cust[]=$row[1];
		}
	}
}

function getWarrantyOne(){
	global $war_bminv,$war_taken_by,$war_qty,$war_claim_date,$war_cu_name,$war_su_name,$war_claim_item,$war_claim_sn,$war_suplier_action,$war_inv_by,$war_inv_item,$war_inv_sn,$war_inv_pay,$war_sup_item,$war_sup_sn,$war_sup_pay,$war_sent_date,$war_receive_date,$war_ho_item,$war_ho_sn,$war_ho_date,$war_ho_by,$war_status,$war_status,$war_claim_pos,$war_repair_pos,$war_replace_pos,$war_inv_pos,$war_cust_pay,$war_new_warranty,$war_issue,$war_status_name,$war_status_color;
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		$userprofile_arr=$item_arr=array();
		$userprofile_arr['']='';
		$item_arr['']='';
		include('config.php');
		$query="SELECT id,username FROM userprofile";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$userprofile_arr[$row[0]]=$row[1];
		}

		$query="SELECT id,description FROM inventory_items";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$item_arr[$row[0]]=$row[1];
		}

		$query="SELECT wa.bm_inv,wa.taken_by,wa.qty,date(wa.claim_date),wa.customer,su.name,wa.claim_item,wa.claim_sn,wa.suplier_action,wa.inv_allocate_by,wa.inv_replace_item,wa.inv_replace_sn,wa.inventory_pay,wa.suplier_replace_item,wa.suplier_replace_sn,wa.suplier_pay,wa.sent_to_suplier,wa.receive_from_suplier,wa.handover_item,wa.handover_sn,wa.handover_by,date(wa.handover_date),wa.`status`,wa.wa_claim_pos,wa.wa_repair_pos,wa.wa_replace_pos,wa.wa_inv_pos,wa.cust_pay,wa.new_warraty_duration,wa.issue FROM warranty wa, supplier su WHERE wa.suplier=su.id AND wa.id='$id'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$war_bminv=$row[0];
		$war_taken_by=$userprofile_arr[$row[1]];
		$war_qty=$row[2];
		$war_claim_date=$row[3];
		$war_cu_id=$row[4];
		$war_su_name=$row[5];
		$war_claim_item=$item_arr[$row[6]];
		$war_claim_sn=$row[7];
		$war_suplier_action=$row[8];
		$war_inv_by=$userprofile_arr[$row[9]];
		$war_inv_item=$item_arr[$row[10]];
		$war_inv_sn=$row[11];
		$war_inv_pay=$row[12];
		$war_sup_item=$item_arr[$row[13]];
		$war_sup_sn=$row[14];
		$war_sup_pay=$row[15];
		$war_sent_date=$row[16];
		$war_receive_date=$row[17];
		$war_ho_item=$item_arr[$row[18]];
		$war_ho_sn=$row[19];
		$war_ho_by=$userprofile_arr[$row[20]];
		$war_ho_date=$row[21];
		$war_status=$row[22];
		$war_claim_pos=$row[23];
		$war_repair_pos=$row[24];
		$war_replace_pos=$row[25];
		$war_inv_pos=$row[26];
		$war_cust_pay=$row[27];
		$war_new_warranty=$row[28];
		$war_issue=$row[29];
		$json_array=json_decode(warrantyStatus($war_status));
		$war_status_name=$json_array->{"st_name"};
		$war_status_color=$json_array->{"st_color"};
		$query1="SELECT cu.name FROM cust cu WHERE cu.id='$war_cu_id'";
		$result1=mysqli_query($conn,$query1);
		$row1 = mysqli_fetch_row($result1);
		$war_cu_name=$row1[0];
	}
}

function getWarrantyPrint(){
	global $war_bminv,$war_taken_by,$war_qty,$war_claim_date,$war_cu_name,$war_su_name,$war_claim_item,$war_claim_sn,$war_suplier_action,$war_inv_by,$war_inv_item,$war_inv_sn,$war_inv_pay,$war_sup_item,$war_sup_sn,$war_sup_pay,$war_sent_date,$war_receive_date,$war_ho_item,$war_ho_sn,$war_ho_date,$war_ho_by,$war_status,$war_status,$war_cust_id,$war_cust_mobile,$war_cust_pay,$cust_pay_amount,$war_new_warranty,$war_issue,$wa_warranty_exp,$tm_shop,$tm_company,$tm_address,$tm_tel,$war_status_name,$war_status_color;
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		$userprofile_arr=$item_arr=array();
		$userprofile_arr['']='';
		$item_arr['']='';
		$war_cu_name=$war_cust_mobile='';
		include('../../../../config.php');
		$query="SELECT id,username FROM userprofile";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$userprofile_arr[$row[0]]=$row[1];
		}

		$query="SELECT id,description FROM inventory_items";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$item_arr[$row[0]]=$row[1];
		}

		$query="SELECT wa.bm_inv,wa.taken_by,wa.qty,wa.claim_date,wa.customer,su.name,wa.claim_item,wa.claim_sn,wa.suplier_action,wa.inv_allocate_by,wa.inv_replace_item,wa.inv_replace_sn,wa.inventory_pay,wa.suplier_replace_item,wa.suplier_replace_sn,wa.suplier_pay,wa.sent_to_suplier,wa.receive_from_suplier,wa.handover_item,wa.handover_sn,wa.handover_by,date(wa.handover_date),wa.store,wa.`status`,wa.cust_pay,wa.cust_pay_amount,wa.new_warraty_duration,wa.issue FROM warranty wa, supplier su WHERE wa.suplier=su.id AND wa.id='$id'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$war_bminv=$row[0];
		$war_taken_by=$userprofile_arr[$row[1]];
		$war_qty=$row[2];
		$war_claim_date=$row[3];
		$war_cust_id=$row[4];
		$war_su_name=$row[5];
		$war_claim_item=$item_arr[$row[6]];
		$war_claim_sn=$row[7];
		$war_suplier_action=warrantySuAction($row[8]);
		$war_inv_by=$userprofile_arr[$row[9]];
		$war_inv_item=$item_arr[$row[10]];
		$war_inv_sn=$row[11];
		$war_inv_pay=$row[12];
		$war_sup_item=$item_arr[$row[13]];
		$war_sup_sn=$row[14];
		$war_sup_pay=$row[15];
		$war_sent_date=$row[16];
		$war_receive_date=$row[17];
		$war_ho_item=$item_arr[$row[18]];
		$war_ho_sn=$row[19];
		$war_ho_by=$userprofile_arr[$row[20]];
		$war_ho_date=$row[21];
		$war_store=$row[22];
		$war_status=$row[23];
		$war_cust_pay=$row[24];
		$cust_pay_amount=$row[25];
		$war_new_warranty=$row[26];
		$war_issue=$row[27];
		if($war_new_warranty=="") $war_new_warranty=0;
		$json_array=json_decode(warrantyStatus($war_status));
		$war_status_name=$json_array->{"st_name"};
		$war_status_color=$json_array->{"st_color"};
		$query="SELECT name,mobile FROM cust WHERE id='$war_cust_id'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$war_cu_name=$row[0];
		$war_cust_mobile='Mobile  : '.$row[1];
		$date=date_create($war_ho_date);
		date_add($date,date_interval_create_from_date_string("$war_new_warranty days"));
		$wa_warranty_exp=date_format($date,"Y-m-d");
	}

	$query="SELECT name,shop_name,address,tel FROM stores WHERE id='$war_store'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$tm_shop=$row[0];
	$tm_company=$row[1];
	$tm_address=$row[2];
	$tm_tel=$row[3];
}

function warrantySearch(){
	global $message,$id,$wa_list_id,$wa_cust;
	$wa_list_id=$wa_cust=array();
	$id='';
	$search=$_GET['search'];
	$out=false;
	include('config.php');
	$query="SELECT count(id),id FROM warranty WHERE id='$search'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	if($row[0]==1){
		$id=$row[1];
		$out=true;
	}else{
		$query="SELECT wa.id,cu.name FROM warranty wa, cust cu WHERE wa.customer=cu.id AND cu.name LIKE '%$search%' LIMIT 15";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$wa_list_id[]=$row[0];
			if(strlen($row[1])>15) $wa_cust[]=substr($row[1],0,15).'...'; else $wa_cust[]=$row[1];
		}
	}
	return $out;
}

// added by nirmal 21_10_28
function searchWarrantyListBill($id){
	global $warranty_id,$warranty_date;
	include('../../../../config.php');
	$query="SELECT `id`,DATE(`claim_date`) FROM warranty WHERE `bm_inv`='$id' ORDER BY `id` DESC LIMIT 20";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$warranty_id[]=$row[0];
		$warranty_date[]=$row[1];
	}
}

// added by nirmal 21_10_28
function searchWarranty($type,$param){
	global $inv_id,$cust_name,$cust_mobile,$warranty_id,$warranty_date,$sn;
	include('../../../../config.php');

	if(($type=='inv') && ($param!='')){
		$query="SELECT w.`id`,w.`bm_inv`, c.`name` FROM cust c, warranty w WHERE c.`id` = w.`customer` AND w.`bm_inv` LIKE '%$param%' LIMIT 50";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$waranty_id[]=$row[0];
			$inv_id[]=$row[1];
			$cust_name[]=$row[2];
		}
	}
	if(($type=='name') && ($param!='')){
		$query="SELECT c.`name`,w.`id`,DATE(w.`claim_date`) FROM cust c, warranty w WHERE c.`id` = w.`customer` AND c.`name` LIKE '%$param%' LIMIT 50";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$cust_name[]=$row[0];
			$warranty_id[]=$row[1];
			$warranty_date[]=$row[2];
		}
	}
	if(($type=='mob') && ($param!='')){
		$query="SELECT c.`name`,c.`mobile`,w.`id`,DATE(w.`claim_date`) FROM cust c, warranty w WHERE c.`id` = w.`customer` AND  `mobile` LIKE '%$param%' LIMIT 50";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$cust_name[]=$row[0];
			$cust_mobile[]=$row[1];
			$warranty_id[]=$row[2];
			$warranty_date[]=$row[3];
		}
	}
	if(($type=='emei') && ($param!='')){
		$query="SELECT `id`,`claim_sn` FROM `warranty` WHERE `claim_sn` LIKE '%$param%' AND `status` !=0 LIMIT 50";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$warranty_id[]=$row[0];
			$sn[]=$row[1];
		}
	}
}

function getWarrantyReplace(){
	global $replace_itm,$replace_sn,$replace_pay;
	$id=$_GET['id'];
	include('config.php');
	$query="SELECT itm.description,wa.suplier_replace_sn,wa.suplier_pay FROM warranty wa, inventory_items itm WHERE wa.suplier_replace_item=itm.id AND wa.id='$id'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$replace_itm=$row[0];
	$replace_sn=$row[1];
	$replace_pay=$row[2];
	if($replace_pay=='') $replace_pay=0;
}

function getWarrantyInv(){
	global $selected_itm;
	if(isset($_GET['itemid'])){
		$itemid=$_GET['itemid'];
		include('config.php');
		$query="SELECT itm.description FROM inventory_items itm WHERE itm.id='$itemid'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$selected_itm=$row[0];
	}
}

function getWarrantyPay(){
	global $pay_extra;
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		include('config.php');
		$query="SELECT inv_replace_item,inventory_pay,suplier_pay FROM warranty WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		if($row[0]!='')	$pay_extra=$row[1]; else $pay_extra=$row[2];
	}
}

function getWarrantyCustPay(){
	global $sup_paid;
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		include('config.php');
		$query="SELECT suplier_pay FROM warranty WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$sup_paid=$row[0];
	}
}

function deleteWarranty(){
	global $message,$id;
	$id=$_GET['id'];
	$out=true;
	include('config.php');
	$query="SELECT `status` FROM warranty WHERE id='$id'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	if($row[0]!=1){ $message="Claim Cannot be Deleted on this Stage"; $out=false; }
	if($out){
		$query="UPDATE `warranty` SET `status`='0' WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message="Claim Cannot be Deleted"; $out=false; }
	}
	if($out){
		$message="Claim was Deleted Successfully!";
		return true;
	}else{
		return false;
	}

}

function getWarrantyPrintTemplate(){
	global $tm_template,$war_status;
	$id=$_GET['id'];
	include('config.php');
	$query="SELECT `status` FROM warranty WHERE id='$id'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$war_status=$row[0];
	$query="SELECT st.billing_template FROM warranty wa, stores st WHERE wa.store=st.id AND wa.id='$id'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$tm_template=$row[0];
}

function validateWarranty($sn){
	$today=dateNow();
	$itu_id=$cu_id=$cu_name=$su_id=$su_name=$bm_inv=$itm_id=$itm_dec=$bm_date=$uptonow=$claim_history="";
	$bi_price=0;

	include('config.php');
	$query="SELECT itu.id,cu.id,cu.name,su.id,su.name,bm.invoice_no,bi.unit_price,itm.id,itm.description,date(bm.`billed_timestamp`) FROM inventory_unic_item itu, inventory_items itm, bill_main bm, bill bi, shipment_main sm, supplier su, cust cu WHERE itu.shipment_no=sm.id AND sm.`supplier`=su.id AND itu.bill_id=bi.id AND bi.item=itm.id AND itu.invoice_no=bm.invoice_no AND bm.`cust`=cu.id AND bm.`status`!=0 AND itu.`status` IN (1,5,7) AND itu.sn='$sn'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$itu_id=$row[0];
		$cu_id=$row[1];
		$cu_name=$row[2];
		$su_id=$row[3];
		$su_name=$row[4];
		$bm_inv=$row[5];
		$bi_price=$row[6];
		$itm_id=$row[7];
		$itm_dec=$row[8];
		$bm_date=$row[9];
	}
	if($itu_id!=''){
		$validity=1;
	}else{
		$query="SELECT itu.id,su.id,su.name,itq.c_price,itm.id,itm.description,sm.shipment_date FROM inventory_unic_item itu, inventory_items itm, inventory_qty itq, shipment_main sm, supplier su, cust cu WHERE itu.shipment_no=sm.id AND sm.`supplier`=su.id AND itu.itq_id=itq.id AND itq.item=itm.id AND itu.`status`='0' AND itu.sn='$sn'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$itu_id=$row[0];
			$su_id=$row[1];
			$su_name=$row[2];
			$bi_price=$row[3];
			$itm_id=$row[4];
			$itm_dec=$row[5];
			$bm_date=$row[6];
			$cu_name='In Stock';
		}
		if($itu_id!='') $validity=2; else $validity=0;
	}

	$date1=date_create($bm_date);
	$date2=date_create($today);
	$diff=date_diff($date1,$date2);
	$uptonow=$diff->format("%m").' ('.$diff->format("%a").' days)';

	$query="SELECT claim_history FROM warranty WHERE handover_sn='$sn' AND `status`='4' ORDER BY id DESC LIMIT 1";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$claim_history=$row[0];
	if($claim_history!=''){
		$claim_history=rtrim($claim_history,",");
		if($itu_id==''){
			$query="SELECT cu.id,cu.name,su.id,su.name,itm.id,itm.description,date(wa.claim_date) FROM warranty wa, inventory_items itm, supplier su, cust cu WHERE wa.handover_item=itm.id AND wa.suplier=su.id AND wa.customer=cu.id AND wa.inv_replace_item is null AND wa.handover_sn='$sn'";
			$result=mysqli_query($conn,$query);
			while($row1=mysqli_fetch_array($result)){
				$itu_id=0;
				$cu_id=$row1[0];
				$cu_name=$row1[1];
				$su_id=$row1[2];
				$su_name=$row1[3];
				$bm_inv=0;
				$bi_price=0;
				$itm_id=$row1[4];
				$itm_dec=$row1[5];
				$bm_date=$row1[6];
			if($row1[0]!='') $validity=1;
			}
		}
	}

	$jasonArray["validity"]=$validity;
	$jasonArray["cust_id"]=$cu_id;
	$jasonArray["cust_name"]=$cu_name;
	$jasonArray["su_id"]=$su_id;
	$jasonArray["su_name"]=$su_name;
	$jasonArray["inv_no"]=$bm_inv;
	$jasonArray["inv_no_full"]=str_pad($bm_inv, 7, "0", STR_PAD_LEFT);
	$jasonArray["bi_price"]=number_format($bi_price);
	$jasonArray["itm_id"]=$itm_id;
	$jasonArray["itm_dec"]=$itm_dec;
	$jasonArray["bm_date"]=$bm_date;
	$jasonArray["uptonow"]=$uptonow;
	$jasonArray["claim_history"]=$claim_history;
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

function warrantySubmit($sub_system){
	global $message,$id;
	$sn=$_POST['sn'];
	$issue=$_POST['issue'];
	$user_id=$_COOKIE['user_id'];
	$store=$_COOKIE['store'];
	$time_now=timeNow();
	$json_array=json_decode(validateWarranty($sn));
	$validity=$json_array->{"validity"};
	$cust_id=$json_array->{"cust_id"};
	$su_id=$json_array->{"su_id"};
	$inv_no=$json_array->{"inv_no"};
	$itm_id=$json_array->{"itm_id"};
	$claim_history=$json_array->{"claim_history"};
	if($claim_history!='') $claim_history.=',';
	//$claim_count++;
	$out=false;
	if($validity==0) $message="Validation Failed";

		include('config.php');
		$query="SELECT inv_ship_id FROM inventory_unic_item WHERE sn='$sn' AND `status`!=2";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		if($row[0]!='') $ins_id=$row[0]; else $ins_id=0;

		if($validity==1){
			$query="INSERT INTO `warranty` (`bm_inv`,`customer`,`ins_id`,`taken_by`,`qty`,`claim_date`,`claim_item`,`claim_sn`,`suplier`,`issue`,`wa_claim_pos`,`wa_repair_pos`,`wa_replace_pos`,`wa_inv_pos`,`store`,`sub_system`,`status`) VALUES ('$inv_no','$cust_id','$ins_id','$user_id','1','$time_now','$itm_id','$sn','$su_id','$issue','2','0','0','0','$store','$sub_system','1')";
			$result=mysqli_query($conn,$query);
			$id=mysqli_insert_id($conn);
			if($result) $out=true; else $message="Error: Claim Could not be Created !";
		}else if($validity==2){
			$message="Error: Claim Could not be Created !";
			$query="SELECT id,itq_id FROM inventory_unic_item WHERE `status`='0' AND sn='$sn'";
			$result=mysqli_query($conn,$query);
			$row = mysqli_fetch_row($result);
			$uid=$row[0];
			$u_qtq_id=$row[1];
			$query="INSERT INTO `warranty` (`ins_id`,`taken_by`,`qty`,`claim_date`,`claim_item`,`claim_sn`,`suplier`,`itu_id`,`issue`,`wa_claim_pos`,`wa_repair_pos`,`wa_replace_pos`,`wa_inv_pos`,`store`,`sub_system`,`status`) VALUES ('$ins_id','$user_id','1','$time_now','$itm_id','$sn','$su_id','$uid','$issue','2','0','0','0','$store','$sub_system','1')";
			$result=mysqli_query($conn,$query);
			$id=mysqli_insert_id($conn);
			if($result){
				$query="UPDATE `inventory_unic_item` SET `status`='7' WHERE id='$uid'";
				$result=mysqli_query($conn,$query);
				if($result){
					$query="UPDATE `inventory_qty` SET `qty`=qty-1 WHERE id='$u_qtq_id'";
					$result=mysqli_query($conn,$query);
					if($result) $out=true;
				}
			}
		}

	if($out){
		$claim_history.=$id.',';
		$query="UPDATE `warranty` SET `claim_history`='$claim_history' WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message="Claim Cannot be Added"; $out=false; }
	}
	if($out){
		$message="Claim was Added Successfully!";
		return true;
	}else{
		return false;
	}
}

function setWarrantyStatus(){
	global $message;
	$id=$_GET['id'];
	$claim=$_GET['claim'];
	$repair=$_GET['repair'];
	$replace=$_GET['replace'];
	$inv=$_GET['inv'];
	$st=$_GET['st'];
	$case=$_GET['case'];
	$user_id=$_COOKIE['user_id'];
	$date_now=dateNow();
	$out=true;
	$message='Claim was Updated Successfully';
	if($case==1){ $message='Item was Sent to Supplier'; $sub_qry="`sent_to_suplier`='$date_now',"; }
	if($case==2){ $message='Item was Received from Suplier'; $sub_qry="`receive_from_suplier`='$date_now',`suplier_receive_by`='$user_id',"; }
	if($case==3){ $message='Item was Sent to Supplier'; $sub_qry="`sent_to_suplier`='$date_now',`suplier_action`='0',"; }

	if($out){
		include('config.php');
		$query="UPDATE `warranty` SET  $sub_qry `wa_claim_pos`='$claim',`wa_repair_pos`='$repair',`wa_replace_pos`='$replace',`wa_inv_pos`='$inv',`status`='$st' WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message="Claim Cannot be Updated"; $out=false; }
	}
	if($out){
		return true;
	}else{
		return false;
	}
}

function setWarrantyHandover(){
	global $message;
	$id=$_GET['id'];
	$claim=$_GET['claim'];
	$repair=$_GET['repair'];
	$replace=$_GET['replace'];
	$inv=$_GET['inv'];
	$st=$_GET['st'];
	$user_id=$_COOKIE['user_id'];
	$time_now=timeNow();
	$out=true;

	if($claim==0 && $repair==1 && $replace==0 && $inv==0 && $st==4){ $message='Item was Handed Over'; $sub_qry="`handover_item`=claim_item,`handover_sn`=claim_sn,`handover_by`='$user_id',`handover_date`='$time_now'"; }
	if($claim==1 && $repair==0 && $replace==0 && $inv==0 && $st==4){ $message='Item was Handed Over'; $sub_qry="`handover_item`=claim_item,`handover_sn`=claim_sn,`handover_by`='$user_id',`handover_date`='$time_now'"; }
	if($claim==3 && $repair==0 && $replace==1 && $inv==0 && $st==4){ $message='Replacement was Handed Over'; $sub_qry="`handover_item`=suplier_replace_item,`handover_sn`=suplier_replace_sn,`handover_by`='$user_id',`handover_date`='$time_now'"; }
	if($claim==2 && $repair==0 && $replace==0 && $inv==1 && $st==3){ $message='Inventory Replacement was Handed Over'; $sub_qry="`handover_item`=inv_replace_item,`handover_sn`=inv_replace_sn,`handover_by`='$user_id',`handover_date`='$time_now'";  }
	if($claim==3 && $repair==0 && $replace==0 && $inv==1 && $st==3){ $message='Inventory Replacement was Handed Over'; $sub_qry="`handover_item`=inv_replace_item,`handover_sn`=inv_replace_sn,`handover_by`='$user_id',`handover_date`='$time_now'";  }
	if($claim==3 && $repair==0 && $replace==3 && $inv==1 && $st==3){ $message='Inventory Replacement was Handed Over'; $sub_qry="`handover_item`=inv_replace_item,`handover_sn`=inv_replace_sn,`handover_by`='$user_id',`handover_date`='$time_now'";  }
	if($claim==0 && $repair==3 && $replace==0 && $inv==1 && $st==3){ $message='Inventory Replacement was Handed Over'; $sub_qry="`handover_item`=inv_replace_item,`handover_sn`=inv_replace_sn,`handover_by`='$user_id',`handover_date`='$time_now'";  }
	if($claim==0 && $repair==0 && $replace==3 && $inv==1 && $st==3){ $message='Inventory Replacement was Handed Over'; $sub_qry="`handover_item`=inv_replace_item,`handover_sn`=inv_replace_sn,`handover_by`='$user_id',`handover_date`='$time_now'";  }
	//	if($case==2){ $message='Replacement was Handed Over'; $sub_qry="`handover_item`=suplier_replace_item,`handover_sn`=suplier_replace_sn,`handover_by`='$user_id',`handover_date`='$time_now',`status`='4'"; }
	//	if($case==3){ $message='Inventory Replacement was Handed Over'; $sub_qry="`handover_item`=inv_replace_item,`handover_sn`=inv_replace_sn,`handover_by`='$user_id',`handover_date`='$time_now',`status`='4'"; }
	//	if($case==4){ $message='Replacement was Handed Over'; $sub_qry="`handover_item`=suplier_replace_item,`handover_sn`=suplier_replace_sn,`handover_by`='$user_id',`handover_date`='$time_now',`status`='8'"; }
	//	if($case==5){ $message='Inventory Replacement was Handed Over'; $sub_qry="`handover_item`=inv_replace_item,`handover_sn`=inv_replace_sn,`handover_by`='$user_id',`handover_date`='$time_now',`status`='2'"; }
	if($out){
		include('config.php');
		$query="UPDATE `warranty` SET  $sub_qry ,`wa_claim_pos`='$claim',`wa_repair_pos`='$repair',`wa_replace_pos`='$replace',`wa_inv_pos`='$inv',`status`='$st' WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message="Claim Cannot be Updated"; $out=false; }
	}
	if($out){
		return true;
	}else{
		return false;
	}
}

function addJournal($wa_no,$pay,$case){
	global $message,$journal_id;
	$date=dateNow();
	$store=$_COOKIE['store'];
	$ref='Warranty '.$wa_no;
	$memo='';
	$placed_by=$_COOKIE['user_id'];
	$today=timeNow();
	$out=true;

	include('config.php');
	$result = mysqli_query($conn,"SELECT MAX(journal_id) as `maxid` FROM journal_main");
	$row = mysqli_fetch_assoc($result);
	$journal_id=$row['maxid'];
	if($journal_id=='') $journal_id=1; else $journal_id++;

	if($case==1){
		$query="SELECT ac.id,su.id FROM accounts ac, supplier su, warranty wa WHERE wa.suplier=su.id AND su.name=ac.name AND wa.id='$wa_no'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$to_ac=$row[0];
		$type_id=$row[1];
		$from_ac=1;
		$type='supplier';
	}elseif($case==2){
		$query="SELECT wa.customer,wa.inv_replace_sn,wa.inventory_pay,wa.suplier_pay FROM warranty wa WHERE wa.id='$wa_no'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$to_ac=1;
		$type_id=$row[0];
		$type='customer';
		if($row[1]!='') $pay=$row[2]; else $pay=$row[3];
		$query="SELECT id FROM accounts WHERE name='sales'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$from_ac=$row[0];
	}elseif($case==3){
		$query="SELECT wa.customer FROM warranty wa WHERE wa.id='$wa_no'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$to_ac=1;
		$type_id=$row[0];
		$from_ac=6;
		$type='customer';
	}

	if($pay!=0){
		$query="INSERT INTO `journal_main` (`journal_id`,`placed_by`,`placed_date`,`journal_date`,`store`,`ref_no`,`memo`,`status`) VALUES ('$journal_id','$placed_by','$today','$date','$store','$ref','$memo','1')";
		$result=mysqli_query($conn,$query);
		if(!$result) $out=false;


		if($out){
			$query2="INSERT INTO `journal_item` (`journal_id`,`account`,`description`,`cr_dr`,`amount`,`stakeholder_type`,`stakeholder_id`) VALUES ('$journal_id','$from_ac','$ref','cr','-$pay','$type','$type_id')";
			$result2=mysqli_query($conn,$query2);
			if(!$result2) $out=false;
			$query2="INSERT INTO `journal_item` (`journal_id`,`account`,`description`,`cr_dr`,`amount`,`stakeholder_type`,`stakeholder_id`) VALUES ('$journal_id','$to_ac','$ref','dr','$pay','$type','$type_id')";
			$result2=mysqli_query($conn,$query2);
			if(!$result2) $out=false;
		}
	}

	return $out;
}

function setWarrantyRepair(){
	global $message;
	$id=$_GET['id'];
	$claim=$_GET['claim'];
	$repair=$_GET['repair'];
	$case=$_GET['case'];
	$amo=$_GET['amo'];
	$user_id=$_COOKIE['user_id'];
	$time_now=timeNow();
	$out=true;
	/*
	if($case==2){
		if(!addJournal($id,$amo,1)) { $message="Payment Cannot be Added"; $out=false; }
	}
	*/
	if($out){
		include('config.php');
		$query="UPDATE `warranty` SET  `suplier_action`='$case',`wa_claim_pos`='$claim',`wa_repair_pos`='$repair',`suplier_pay`='$amo',`suplier_pay_by`='$user_id',`suplier_pay_date`='$time_now' WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message="Claim Cannot be Updated"; $out=false; }
	}
	if($out){
		$message="Claim was Updated Successfully";
		return true;
	}else{
		return false;
	}
}

function setWarrantyPay(){
	global $message,$id;
	$id=$_POST['id'];
	$pay_amount=$_POST['pay_amount'];
	$w_duration=$_POST['w_duration'];
	$user_id=$_COOKIE['user_id'];
	$time_now=timeNow();
	$out=true;

	//if(!addJournal($id,0,2)) { $message="Payment Cannot be Added"; $out=false; }

	if($out){
		include('config.php');
		$query="UPDATE `warranty` SET  `cust_pay`='1',`cust_pay_amount`='$pay_amount',`cust_pay_by`='$user_id',`cust_pay_date`='$time_now',`new_warraty_duration`='$w_duration' WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message="Claim Cannot be Updated"; $out=false; }
	}

	if($out){
		$message="The Payment was Added Successfully";
		return true;
	}else{
		$message="The Payment Could not be Added";
		return false;
	}
}

function setWarrantyCustPay(){
	global $message,$id;
	$id=$_POST['id'];
	$pay=$_POST['pay'];
	$user_id=$_COOKIE['user_id'];
	$time_now=timeNow();
	$out=true;

	//if(!addJournal($id,$pay,3)) { $message="Payment Cannot be Added"; $out=false; }

	if($out){
		include('config.php');
		$query="UPDATE `warranty` SET  `cust_pay`='1',`cust_pay_amount`='$pay',`cust_pay_by`='$user_id',`cust_pay_date`='$time_now' WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message="Claim Cannot be Updated"; $out=false; }
	}

	if($out){
		$message="The Payment was Added Successfully";
		return true;
	}else{
		$message="The Payment Could not be Added";
		return false;
	}
}

// updated by nirmal 23_02_2022
function setWarrantyReplace(){
	global $message,$id;
	$id=$_POST['id'];
	$itemid=$_POST['itemid'];
	$r_sn=$_POST['r_sn'];
	$r_pay=$_POST['r_pay'];
	$user_id=$_COOKIE['user_id'];
	$time_now=timeNow();
	$out=true;
	if($itemid==0){ $message="Invalid Item"; $out=false; }
	include('config.php');
	/*
	if($out){
		if($r_pay!=0){
			if(!addJournal($id,$r_pay,1)) { $message="Payment Cannot be Added"; $out=false; }
		}
	}
	*/
	if($out){
		$query="SELECT count(id) FROM inventory_unic_item WHERE `sn`='$r_sn'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		if($row[0]>0){
			$message="Replace SN already in the database!";
			$out=false;
		}
	}
	if($out){
		include('config.php');
		$query="UPDATE `warranty` SET  `suplier_action`='4',`wa_replace_pos`='3',`suplier_replace_item`='$itemid',`suplier_replace_sn`='$r_sn',`suplier_pay`='$r_pay',`suplier_pay_by`='$user_id',`suplier_pay_date`='$time_now' WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message="Claim Cannot be Updated"; $out=false; }
	}
	if($out){
		$message="Claim was Updated Successfully";
		return true;
	}else{
		return false;
	}
}

function addWarrantyInv(){
	global $message,$id;
	$id=$_POST['id'];
	$itemid=$_POST['itemid'];
	$i_sn=$_POST['i_sn'];
	$i_pay=$_POST['i_pay'];
	$claim=$_POST['claim'];
	$repair=$_POST['repair'];
	$replace=$_POST['replace'];
	$inv=$_POST['inv'];
	$st=$_POST['st'];
	$user_id=$_COOKIE['user_id'];
	$time_now=timeNow();
	$unic_cal=unicCal();
	$out=true;

	include('config.php');
	$query="SELECT `store` FROM warranty WHERE id='$id'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$store=$row[0];
	$query="SELECT id,qty FROM inventory_qty WHERE item='$itemid' AND location='$store'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$itq_id=$row[0];
	$itq_qty=$row[1];
	$query="SELECT id,count(id) FROM inventory_unic_item WHERE `sn`='$i_sn' AND `status`='0'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$itu_id=$row[0];
	$itu_count=$row[1];
	if(!$unic_cal){
		if($itq_qty>0){ $itq_qty--; }else{ $message='Insufficient Item Quantity'; $out=false; }
	}
	if($itu_count==0){ $message='Invalid SN'; $out=false; }

	if($out){
		$query="UPDATE inventory_qty SET `qty`='$itq_qty' WHERE item='$itemid' AND location='$store'";
		$result=mysqli_query($conn,$query);
		if($result){ processInventoryNew($itemid,$id,$store,'warranty'); }else{ $message='Error: Item Cannot be Added'; $out=false; }
	}
	if($out){
		$query="UPDATE inventory_unic_item SET `status`='7' WHERE `id`='$itu_id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message='Error: Item Cannot be Added1'; $out=false; }
	}
	if($out){
		$query="UPDATE warranty SET `inv_allocate_by`='$user_id',`inv_replace_item`='$itemid',`inv_itq_id`='$itq_id',`inv_itu_id`='$itu_id',`inv_replace_sn`='$i_sn',`inventory_pay`='$i_pay',`inventory_pay_by`='$user_id',`inventory_pay_date`='$time_now',`status`='$st',`wa_inv_pos`='2' WHERE `id`='$id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message='Error: Item Cannot be Added2'; $out=false; }
	}
	if($out){
		$message="Item was Allocated from Inventiry Successfully";
		return true;
	}else{
		return false;
	}
}

function returnWarrantyInv($st){
	global $message,$id;
	$id=$_GET['id'];
	$user_id=$_COOKIE['user_id'];
	$user_name=$_COOKIE['user'];
	$date_now=dateNow();
	$time_now=timeNow();
	$unic_cal=unicCal();
	$itm_code=$itm_cost=$itm_oldqty='';
	$out=true;

	include('config.php');

	if($st==1){	$query="SELECT claim_item,claim_sn,store,ins_id FROM warranty WHERE id='$id'"; $pos_qry="`wa_repair_pos`='4',"; }
	if($st==2){	$query="SELECT suplier_replace_item,suplier_replace_sn,store,ins_id FROM warranty WHERE id='$id'"; $pos_qry="`wa_replace_pos`='4',"; }
	if($st==3){	$query="SELECT suplier_replace_item,suplier_replace_sn,store,ins_id FROM warranty WHERE id='$id'"; $pos_qry="`wa_replace_pos`='4',`handover_item`=suplier_replace_item,`handover_sn`=suplier_replace_sn,`handover_by`='$user_id',`handover_date`='$time_now',"; }
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$inv_item=$row[0];
	$inv_sn=$row[1];
	$store=$row[2];
	$ins_id=$row[3];

	$query="SELECT itq.id,itm.code,itq.c_price,itq.qty FROM inventory_items itm, inventory_qty itq WHERE itm.id=itq.item AND itq.location='$store' AND itq.item='$inv_item'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$itq_id=$row[0];
	$itm_code=$row[1];
	$itm_cost=$row[2];
	$itm_oldqty=$row[3];
	if($itm_cost==''){
		$query="SELECT itq.id,itm.code,itq.c_price,itq.qty FROM inventory_items itm, inventory_qty itq WHERE itm.id=itq.item AND itq.item='$inv_item'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$itq_id=$row[0];
			$itm_code=$row[1];
			$itm_cost=$row[2];
			$itm_oldqty=$row[3];
		}
	}

	if($ins_id!=0){
		$query="SELECT shipment_no,cost FROM inventory_shipment WHERE id='$ins_id'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$ins_shipment_no=$row[0];
		$itm_code=$row[1];
	}

	if($itm_cost==''){ $message='This item details not found in your inventory'; $out=false; }
	if($ins_id==0){
		if($out){
		    $debug_id=debugStart($inv_item,1);
			$query="INSERT INTO `shipment_main` (`added_by`,`shipment_date`,`supplier`,`invoice_no`,`invoice_date`,`invoice_due`,`unic`,`status`) VALUES ('$user_id','$date_now','0','Rep Job : $id','$date_now','$date_now','1','0')";
			$result=mysqli_query($conn,$query);
			$shipment_no=mysqli_insert_id($conn);
			if(!$result){ $message='Error: Cannot Create a Shipment for this Item'; $out=false; }
		}
		if($out){
			$query="INSERT INTO `inventory_shipment` (`shipment_no`,`inv_item`,`inv_code`,`location`,`cost`,`old_qty`,`added_qty`,`added_by`,`time`,`new_stock`) VALUES ('$shipment_no','$inv_item','$itm_code','$store','$itm_cost','$itm_oldqty','1','$user_name','$time_now','0')";
			$result=mysqli_query($conn,$query);
			$ship_id=mysqli_insert_id($conn);
			if(!$result){ $message='Error: Item Could not be Added to Warranty Shipment'; $out=false; }
		}
		$warranty_item=0;
	}else{
		$shipment_no=$ins_shipment_no;
		$ship_id=$ins_id;
		$warranty_item=1;
	}
	if($out){
		$query="INSERT INTO `inventory_unic_item` (`itq_id`,`shipment_no`,`inv_ship_id`,`sn`,`warranty_item`,`status`) VALUES ('$itq_id','$shipment_no','$ship_id','$inv_sn','$warranty_item','0')";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message='Error: Item SN Could not be Added to Inventory'; $out=false; }
	}
	if(($out)&&(!$unic_cal)){
		$itm_newqty=$itm_oldqty+1;
		$query="UPDATE `inventory_qty` SET `qty`='$itm_newqty' WHERE id='$itq_id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message='Error: Inventory QTY Could not be Updated'; $out=false; }
	}
	if($out){
		$query="UPDATE `warranty` SET $pos_qry `status`='4' WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message='Error: Claim Could not be Finalyzed'; $out=false; }
	}


	if($out){
		$message='Item was Added to Inventory Successfully !';
		debugEnd($debug_id,'success');
		return true;
	}else{
		debugEnd($debug_id,'fail');
		return false;
	}
}

function returnWarrantyInv2($st){
	global $message,$id;
	$id=$_GET['id'];
	$user_id=$_COOKIE['user_id'];
	$time_now=timeNow();
	$out=true;

	include('config.php');

	$query="SELECT itu_id FROM warranty WHERE id='$id'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$itu_id=$row[0];
	$query="SELECT itq_id FROM inventory_unic_item WHERE id='$itu_id'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$itq_id=$row[0];
	$query="SELECT item FROM inventory_qty WHERE id='$itq_id'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
	$itq_item=$row[0];

	if($itu_id==''){ $message='Invalid Operation'; $out=false; }
	if($out){
		$ship_id=mysqli_insert_id($conn);
		$query="UPDATE `inventory_unic_item` SET `status`='0' WHERE id='$itu_id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message='Error: Operation Failed [805]'; $out=false; }
	}
	if($out){
		$query="UPDATE `inventory_qty` SET `qty`=qty+1 WHERE id='$itq_id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message='Error: Operation Failed [806]'; $out=false; }
	}
	if($out){
		if($st==1) $query="UPDATE `warranty` SET `handover_item`='$itq_item',`handover_sn`=claim_sn,`handover_by`='$user_id',`handover_date`='$time_now',`wa_repair_pos`='4',`status`='4' WHERE id='$id'";
		if($st==2) $query="UPDATE `warranty` SET `handover_item`='$itq_item',`handover_sn`=claim_sn,`handover_by`='$user_id',`handover_date`='$time_now',`wa_claim_pos`='4',`status`='4' WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message='Error: Operation Failed [807]'; $out=false; }
	}


	if($out){
		$message='Item was Returned to Inventory Successfully !';
		return true;
	}else{
		return false;
	}
}

//---------------------------------Reports-----------------------------------------------//

function searchCust(){
	global $fromdate,$todate,$cu_id,$cu_name,$search_name,$bm_no,$bm_total,$status_out,$status_color,$bm_store;
	if(isset($_REQUEST['datefrom'])) $fromdate=$_REQUEST['datefrom']; else $fromdate=date("Y-m-d",time()-2592000);
	if(isset($_REQUEST['dateto'])) $todate=$_REQUEST['dateto']; else $todate=dateNow();
	$search_name=$_GET['search_name'];
	$cu_id0=$_GET['cu_id'];
	$cu_id=$bm_no=array();
	if($search_name!=''){
		include('config.php');
		$query="SELECT id,name FROM cust WHERE name LIKE '%$search_name%' AND `status`!=0";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$cu_id[]=$row[0];
			$cu_name[]=$row[1];
		}
	}

	if($cu_id!=''){
		include('config.php');
		$query="SELECT bm.invoice_no,(bm.`invoice_+total`+bm.`invoice_-total`),bm.`type`,bm.`lock`,bm.`status`,st.name FROM bill_main bm, stores st WHERE bm.store=st.id AND bm.`cust`='$cu_id0' AND date(bm.billed_timestamp) BETWEEN '$fromdate' AND '$todate'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_no[]=$row[0];
			$bm_total[]=$row[1];
			$bm_store[]=$row[5];
			if($row[3]==0){
				$status_out[]='Unlocked'; $status_color[]='red';
			}else{
				switch($row[4]){
					case 0: $status_out[]='Deleted'; $status_color[]='#FF3300'; break;
					case 1: $status_out[]='Billed (Pending)'; $status_color[]='yellow'; break;
					case 2: $status_out[]='Billed (Picked)'; $status_color[]='yellow'; break;
					case 3: if($row[2]==3){ $status_out[]='Billed (Picked)'; } else { $status_out[]='Billed (Packed)'; } $status_color[]='yellow'; break;
					case 4: if($row[2]==3){ $status_out[]='Repaired'; }else{ $status_out[]='Billed (Shipped)'; } $status_color[]='yellow'; break;
					case 5: if($row[2]==3){ $status_out[]='Repaired | Delivered'; }else{ $status_out[]='Billed (Delivered)'; } $status_color[]='white'; break;
					case 6: $status_out[]='Rejected'; $status_color[]='orange'; break;
					case 7: $status_out[]='Rejected | Delivered'; $status_color[]='orange'; break;
				}
			}
		}
	}
}

?>