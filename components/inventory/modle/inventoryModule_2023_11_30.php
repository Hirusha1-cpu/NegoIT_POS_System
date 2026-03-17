<?php
// Utility functions
function isNumeric($num){
	if(is_numeric($num) && $num >= 0){
		return true;
	}else{
		return false;
	}
}

function shipmentTmpItmStatus($st_code){
	$jsonArray=array();
	switch ($st_code){
		case "0" :
			$jsonArray["sn_txt"]='Error';
			$jsonArray["sn_color"]="#f44336";
		break;
		case "1" :
			$jsonArray["sn_txt"]='Pending';
			$jsonArray["sn_color"]="#2196F3";
		break;
		case "2" :
			$jsonArray["sn_txt"]='Saved';
			$jsonArray["sn_color"]="#04AA6D";
		break;
		case "3" :
			$jsonArray["sn_txt"]='SN in Production';
			$jsonArray["sn_color"]="#f44336";
		break;
		case "4" :
			$jsonArray["sn_txt"]='SN duplicate in Shipment';
			$jsonArray["sn_color"]="#f44336";
		break;
   }
	$myJSON = json_encode($jsonArray);
	return $myJSON;
}

function currentStore(){
	global $currentstore;
	$user_id=$_COOKIE['user_id'];
	include('config.php');
		$query="SELECT st.name FROM userprofile up, stores st WHERE up.store=st.id AND up.id=$user_id";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$currentstore=$row[0];
	}
}

function getCategory($sub_system){
	global $category_id,$category_name;
	$category_id=$category_name=array();
	include('config.php');
		$query="SELECT id,name FROM item_category WHERE `sub_system`='all' OR `sub_system`='$sub_system' ORDER BY name";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$category_id[]=$row[0];
			$category_name[]=$row[1];
	}
}

// update by nirmal 03_11_2023
function getStores($sub_system){
	global $stores_id,$stores_name;
	$systemid=inf_systemid(1);
	if($sub_system==0) $sub_system_qry=""; else $sub_system_qry="AND sub_system='$sub_system'";
	if($systemid == 13) $sub_system_qry="AND sub_system='$sub_system'";
	include('config.php');
		$query="SELECT id,name FROM stores WHERE status='1' $sub_system_qry";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$stores_id[]=$row[0];
			$stores_name[]=$row[1];
	}
}

function getSupplier(){
	global $su_id,$su_name,$su_email,$su_tel1,$su_tel2,$su_address,$su_country;
	$su_id=array();
	include('config.php');
	$query="SELECT id,name,email,tel1,tel2,address,country FROM supplier WHERE `status`=1 ORDER BY name";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$su_id[]=$row[0];
		$su_name[]=$row[1];
		$su_email[]=$row[2];
		$su_tel1[]=$row[3];
		$su_tel2[]=$row[4];
		$su_address[]=$row[5];
		$su_country[]=$row[6];
	}
}

function getShiomentID(){
	$user=$_COOKIE['user_id'];
	include('config.php');
	$result = mysqli_query($conn,"SELECT id,added_by FROM shipment_main ORDER BY id DESC LIMIT 1");
	$row = mysqli_fetch_assoc($result);
	$shipment_no=$row['id'];
	$added_by=$row['added_by'];
	if($shipment_no=='') $shipment_no=0;
	$result = mysqli_query($conn,"SELECT count(*) as `count` FROM inventory_shipment WHERE shipment_no='$shipment_no'");
	$row = mysqli_fetch_assoc($result);
	$shipment_data=$row['count'];
	if(($shipment_data==0)&&($user==$added_by)){
		mysqli_query($conn,"DELETE FROM shipment_main WHERE id='$shipment_no'");
	}else{
	$shipment_no++;
	}
	return $shipment_no;
}

// added by nirmal 07_07_2023
function getDefaultPrices(){
	include('config.php');
	global $default_max_r_rate, $default_min_w_rate, $default_max_w_rate, $unic_no, $default_commision;

	$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='default_commision'");
	$row = mysqli_fetch_assoc($result);
	$default_commision =  $row['value'];

	$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='default_max_r_rate'");
	$row = mysqli_fetch_assoc($result);
	$default_max_r_rate =  $row['value'];

	$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='default_max_w_rate'");
	$row = mysqli_fetch_assoc($result);
	$default_max_w_rate =  $row['value'];

	$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='default_min_w_rate'");
	$row = mysqli_fetch_assoc($result);
	$default_min_w_rate =  $row['value'];

	$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='unic_no'");
	$row = mysqli_fetch_assoc($result);
	$unic_no =  $row['value'];
}

// added by nirmal 21_9_24
function getMainShipmentID(){
	include('config.php');
	$result = mysqli_query($conn,"SELECT id FROM shipment_main ORDER BY id DESC LIMIT 1");
	$row = mysqli_fetch_assoc($result);
	$shipment_no=$row['id'];
	$shipment_no++;
	return $shipment_no;
}

// added by nirmal 21_08_11
function getShipmentIDTmp(){
	$user=$_COOKIE['user_id'];
	include('config.php');
	$result = mysqli_query($conn,"SELECT id,added_by FROM shipment_main_tmp ORDER BY id DESC LIMIT 1");
	$row = mysqli_fetch_assoc($result);
	$shipment_no=$row['id'];
	$added_by=$row['added_by'];

	if($shipment_no=='') $shipment_no=0;
	$result = mysqli_query($conn,"SELECT count(*) as `count` FROM shipment_item_tmp WHERE shipment_tmp_no='$shipment_no'");
	$row = mysqli_fetch_assoc($result);
	$shipment_data=$row['count'];

	if(($shipment_data == 0) && ($user==$added_by)){
		mysqli_query($conn,"DELETE FROM shipment_main_tmp WHERE id='$shipment_no'");
		$shipment_no++;
	}else{
		$shipment_no++;
	}
	return $shipment_no;
}

// added by nirmal 21_08_11
function addShipmentTmp($sub_system){
	global $message,$shipment_no;
	$shipment_no=getShipmentIDTmp();
	$user=$_COOKIE['user_id'];
	$store=$_COOKIE['store'];
	$ship_date=$_REQUEST['ship_date'];
	$suplier=$_REQUEST['suplier'];
	$ship_inv_no=$_REQUEST['ship_inv_no'];
	$ship_inv_date=$_REQUEST['ship_inv_date'];
	$ship_inv_dudate=$_REQUEST['ship_inv_dudate'];
	if($_REQUEST['sub']=='show_add_unic_tmp') $unic=1; else $unic=0;
	include('config.php');
	$query1="INSERT INTO `shipment_main_tmp` (`id`,`added_by`,`shipment_date`,`supplier`,`invoice_no`,`invoice_date`,`invoice_due`,`unic`,`store`,`sub_system`) VALUES ('$shipment_no','$user','$ship_date','$suplier','$ship_inv_no','$ship_inv_date','$ship_inv_dudate','$unic','$store','$sub_system')";
	$result1=mysqli_query($conn,$query1);

	if($result1){
		$message='Shipment was created successfully!';
		return true;
	}else{
		$message='Shipment could not be created !';
		return false;
	}
}

// update by nirmal 21_08_17
function addShipment($sub_system,$ship_date,$suplier,$ship_inv_no,$ship_inv_date,$ship_inv_dudate,$sub){
	global $message,$shipment_no;
	$shipment_no=getShiomentID();
	$user=$_COOKIE['user_id'];
	if($sub=='show_add_unic') $unic=1; else $unic=0;
	include('config.php');
	$query1="INSERT INTO `shipment_main` (`id`,`added_by`,`shipment_date`,`supplier`,`invoice_no`,`invoice_date`,`invoice_due`,`unic`,`sub_system`,`status`) VALUES ('$shipment_no','$user','$ship_date','$suplier','$ship_inv_no','$ship_inv_date','$ship_inv_dudate','$unic','$sub_system','0')";
	$result1=mysqli_query($conn,$query1);

	if($result1){
		$message='Shipment was Created Successfully!';
		return true;
	}else{
		$message='Shipment could not be Created !';
		return false;
	}
}

// added by nirmal 21_09_24
function addShipmentToMain($sub_system,$ship_date,$suplier,$ship_inv_no,$ship_inv_date,$ship_inv_dudate,$unic){
	global $message,$shipment_no;
	$shipment_no=getMainShipmentID();
	$user=$_COOKIE['user_id'];
	include('config.php');
	$query1="INSERT INTO `shipment_main` (`id`,`added_by`,`shipment_date`,`supplier`,`invoice_no`,`invoice_date`,`invoice_due`,`unic`,`sub_system`,`status`) VALUES ('$shipment_no','$user','$ship_date','$suplier','$ship_inv_no','$ship_inv_date','$ship_inv_dudate','$unic','$sub_system','0')";
	$result1=mysqli_query($conn,$query1);

	if($result1){
		$message='Shipment was Created Successfully!';
		return true;
	}else{
		$message='Shipment could not be Created !';
		return false;
	}
}

function findDuplicateItem($type,$data){
	include('config.php');
	if($type=='code') $qry="`code`='$data'";
	if($type=='description') $qry="`description`='$data'";
	if($type=='po_description') $qry="`po_description`='$data'";
		$result = mysqli_query($conn2,"SELECT COUNT(id) as `count` FROM `inventory_items` WHERE $qry");
		$row = mysqli_fetch_assoc($result);
		$count=$row['count'];
	if($count==0)
	return true;
	else
	return false;
}

// edit by nirmal 21_11_2023
function addItem($sub_system){
	global $message,$type;
	$code=preg_replace('/[^A-Za-z0-9\-+.\ _]/', '',strtoupper($_REQUEST['code']));
	$description=preg_replace('/[^A-Za-z0-9\-+.\ _]/', '',$_REQUEST['description']);
	$po_description=preg_replace('/[^A-Za-z0-9\-+.\ _]/', '',$_REQUEST['po_description']);
	if($po_description=='') $po_description=$description;
	$type=$_REQUEST['type'];
	$supplier=$_REQUEST['supplier'];
	$c_price=$_REQUEST['cost'];
	$count = 0;
	if($type==1){
		$w_price=$_REQUEST['w_price'];
		$r_price=$_REQUEST['r_price'];
		$min_w_rate=$_REQUEST['min_w_rate'];
		$max_w_rate=$_REQUEST['max_w_rate'];
		$max_r_rate=$_REQUEST['max_r_rate'];
		$drawer=$_REQUEST['drawer'];
		if(isset($_REQUEST['qty'])) $qty=$_REQUEST['qty']; else $qty=0;
		if($qty=='') $qty=0;
		$unic=$_REQUEST['unic'];
		$default_price=0;
	}
	if($type==2 || $type==3){
		$default_price=$_REQUEST['w_price'];
		$min_w_rate=0;
		$max_w_rate=0;
		$max_r_rate=0;
		$unic=0;
	}
	if($_REQUEST['commision']=='') $commision=0; else $commision=$_REQUEST['commision'];
	$tags_arr = array();
	$unit = '';
	if(isset($_REQUEST['tags'])){
		$tags_arr=explode("|",$_REQUEST['tags']);
	}
	if(isset($_REQUEST['unit_type'])){
		$unit = $_REQUEST['unit_type'];
	}

	$category=$_REQUEST['category'];
	$store0=$_COOKIE['store'];
	$message='Item was Added Successfully!';
	$out=true;
	include('config.php');

	if(($code=='')||($description=='')){ $out=false; $message='Description or Code cannot be Empty !'; }
	if((!findDuplicateItem('code',$code))||(!findDuplicateItem('description',$description))){ $out=false; $message='Duplicate Item Description or Code detected. !'; }
	// added by nirmal 21_11_2023
	if(($out) && ($type ==1)){
		if((!($w_price > 0)) || (!($r_price > 0))){
			$out=false;
			$message='Wholesale or Retail price cannot be zero or empty!';
		}
	}

	// added by nirmal 21_10_8
	for($i=0;$i<sizeof($tags_arr);$i++){
		$query="SELECT id,min_profit FROM tag_name WHERE `tag`='$tags_arr[$i]'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$tag_id=$row[0];
		if($row[1] > 0){
			$count += 1;
		}
	}
	if($count > 1){
		$out=false; $message='Error: You can only add one price tag to an item.';
	}

	if($out){
		if($unit != ''){
			$query1="INSERT INTO `inventory_items` (`code`,`description`,`po_description`,`min_w_rate`,`max_w_rate`,`max_r_rate`,`default_cost`,`default_price`,`commision`,`category`,`supplier`,`unic`,`pr_sr`,`sub_system`,`status`,`unit`) VALUES ('$code','$description','$po_description','$min_w_rate','$max_w_rate','$max_r_rate','$c_price','$default_price','$commision','$category','$supplier','$unic','$type','$sub_system','1','$unit')";
		}else{
			$query1="INSERT INTO `inventory_items` (`code`,`description`,`po_description`,`min_w_rate`,`max_w_rate`,`max_r_rate`,`default_cost`,`default_price`,`commision`,`category`,`supplier`,`unic`,`pr_sr`,`sub_system`,`status`) VALUES ('$code','$description','$po_description','$min_w_rate','$max_w_rate','$max_r_rate','$c_price','$default_price','$commision','$category','$supplier','$unic','$type','$sub_system','1')";
		}
		$result1=mysqli_query($conn,$query1);
		$idx = mysqli_insert_id($conn);
		if(!$result1){ $out=false; $message='Item could not be Added!'; }

		if($out){
			if($type==1){
				$query="SELECT id FROM stores WHERE sub_system='$sub_system'";
				$result=mysqli_query($conn,$query);
				while($row=mysqli_fetch_array($result)){
					$store=$row[0];
					if($store0==$store) $drawer0=$drawer; else $drawer0='';
					$query2="INSERT INTO `inventory_qty` (`item`,`location`,`w_price`,`r_price`,`c_price`,`qty`,`drawer_no`) VALUES ('$idx','$store','$w_price','$r_price','$c_price','$qty','$drawer0')";
					$result2=mysqli_query($conn,$query2);
					if(!$result2){ $out=false; $message='Quantity could not be Added!'; }
				}
			}
		}
		if($out){
			for($i=0;$i<sizeof($tags_arr);$i++){
				$query="SELECT id FROM tag_name WHERE `tag`='$tags_arr[$i]'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$tag_id=$row[0];

				$query="INSERT INTO `tag_assignment` (`item`,`tag`) VALUES ('$idx','$tag_id')";
				$result=mysqli_query($conn,$query);
			}
		}
	}

	if($out){
		return true;
	}else{
		return false;
	}
}

function checkMismatch($unic,$item,$st_id,$itq_id,$itq_qty){
	include('config.php');
	if($unic==1){
		$result = mysqli_query($conn2,"SELECT SUM(qty) as `qty` FROM inventory_new WHERE item='$item' AND store='$st_id'");
		$row = mysqli_fetch_assoc($result);
		$new_qty=$row['qty'];
		$qty=$itq_qty+$new_qty;
		$result = mysqli_query($conn2,"SELECT count(id) as `count` FROM inventory_unic_item WHERE itq_id='$itq_id' AND `status`=0");
		$row = mysqli_fetch_assoc($result);
		$count=$row['count'];
		if($count==$qty) return true; else return false;
	}else return true;
}

function getItems1($itmstatus){
	global $code,$description,$w_price,$r_price,$cost;
	$store=$_COOKIE['store'];
	if($itmstatus=='on') $itemst_qry='WHERE itm.`status`=1';
	if($itmstatus=='nounic') $itemst_qry='WHERE itm.`unic`=0 AND itm.`status`=1';
	if($itmstatus=='unic') $itemst_qry='WHERE itm.`unic`=1 AND itm.`status`=1';
	if($itmstatus=='off') $itemst_qry='';
	include('config.php');
	$query="SELECT itm.id,itm.code,itm.description FROM inventory_items itm LEFT JOIN (SELECT * FROM inventory_qty WHERE location='$store') as itq ON itm.id=itq.item $itemst_qry";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$item_id=$row[0];
		$code[]=$row[1];
		$description[]=$row[2];
		/*
		$w_pri=$row[3];
		$r_pri=$row[4];
		$c_pri=$row[5];
		if($w_pri==''){
			$result2 = mysqli_query($conn2,"SELECT itq.w_price,itq.r_price,itq.c_price FROM inventory_qty itq WHERE itq.item='$item_id' LIMIT 1");
			$row2 = mysqli_fetch_row($result2);
			$w_pri=$row2[0];
			$r_pri=$row2[1];
			$c_pri=$row2[2];
		}
		$w_price[]=$w_pri;
		$r_price[]=$r_pri;
		$cost[]=$c_pri;
		*/
	}
}

// added by nirmal 21_08_12, updated by nirmal 21_9_6
function listCodeData($itmstatus){
	global $data_list, $fn;
	$data_list = array();
	if ($_POST['keyword']) {
		$keyword=$_POST['keyword'];
		if($_POST['type']=='code'){
			$type='code';
			$fn = 'selectItemCode';
		}else{
			$type='description';
			$fn = 'selectItemDesc';
		}
		if($itmstatus=='unic') $unic='1'; else $unic='0';
		include('config.php');

		$query="SELECT itm.`$type` FROM inventory_items itm WHERE itm.`unic`='$unic' AND itm.`status`=1 AND itm.`$type` LIKE '%$keyword%' LIMIT 20";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$data_list[]=$row[0];
		}
	}
}

function getItemData(){
	$case=$_GET['case'];
	$item=$_GET['item'];
	$store=$_COOKIE['store'];
	$unic_cal=unicCal();

	if($case=='code') $qry="itm.`code`='$item'"; else $qry="itm.`description`='$item'";
	include('config.php');
	$query="SELECT itm.id,itm.`code`,itm.description,itq.c_price,itq.w_price,itq.r_price,itq.qty,itq.drawer_no,itq.id,itm.unic FROM inventory_items itm LEFT JOIN inventory_qty itq ON itm.id=itq.item AND itq.`location`='$store' WHERE $qry";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$itm_id=$row[0];
	$itm_code=$row[1];
	$itm_desc=$row[2];
	$itm_cprice=$row[3];
	$itm_wprice=$row[4];
	$itm_rprice=$row[5];
	$itm_itqqty=$row[6];
	$itm_drawer=$row[7];
	$itq_id=$row[8];
	$unic=$row[9];

	if($itm_cprice==''){
		$query="SELECT itq.c_price,itq.w_price,itq.r_price,itq.qty,itq.drawer_no FROM inventory_qty itq WHERE itq.`item`='$itm_id' LIMIT 1";
		$row=mysqli_fetch_row(mysqli_query($conn2,$query));
		$itm_cprice=$row[0];
		$itm_wprice=$row[1];
		$itm_rprice=$row[2];
		$itm_itqqty=$row[3];
		$itm_drawer=$row[4];
	}

	$query="SELECT SUM(itn.qty) FROM inventory_new itn WHERE itn.store='$store' AND itn.`item`='$itm_id'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$itm_itnqty=$row[0];

	$itm_qty=$itm_itqqty+$itm_itnqty;

	if(($unic_cal)&&($unic==1)){
		$query="SELECT count(id) FROM inventory_unic_item WHERE itq_id='$itq_id' AND `status`='0'";
		$row=mysqli_fetch_row(mysqli_query($conn2,$query));
		$itm_qty=$row[0];
	}

	$txt=$itm_id.'|'.$itm_code.'|'.$itm_desc.'|'.$itm_cprice.'|'.$itm_wprice.'|'.$itm_rprice.'|'.$itm_qty.'|'.$itm_drawer;
	return $txt;
}

// update by nirmal 03_11_2023
function getItems2($itmstatus){
	global $code,$description;
	$store=$_COOKIE['store'];
	$sub_system=$_COOKIE['sub_system'];
	if($itmstatus=='on') $itemst_qry='WHERE `status`=1';
	if($itmstatus=='nounic') $itemst_qry='WHERE `unic`=0 AND `status`=1';
	if($itmstatus=='unic') $itemst_qry='WHERE `unic`=1 AND `status`=1';
	if($itmstatus=='off'){
		$itemst_qry='';
		$sub_system_qry='WHERE `sub_system`= '.$sub_system;
	}else{
		$sub_system_qry='AND `sub_system`= '.$sub_system;
	}

	include('config.php');
	$query="SELECT `id`,`code`,`description` FROM inventory_items $itemst_qry $sub_system_qry ORDER BY `description`";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$item_id[]=$row[0];
		$code[]=$row[1];
		$description[]=$row[2];
	}
}

// update by nirmal 03_11_2023
function getItems($sub_system){
	global $id,$code,$description,$w_price,$r_price,$cost,$drawer,$qty,$reorder_level,$reorder_qty,$cat_id,$cat_name,$st_name,$color,$mismatch;
	$id=$itq_id=$code=$description=$w_price=$r_price=$cost=$drawer=$qty=$cat_id=$cat_name=$st_name=$color=array();
	$type=$_GET['type'];
	$unic_cal=unicCal();
	$category_qry=$store_qry=$tags_qry='';

	include('config.php');
	if(isset($_GET['category'])){
		$category_req=$_REQUEST['category'];
		if($category_req=='all') $category_qry=''; else $category_qry='ca.id='.$category_req.' AND';
	}
	if(isset($_GET['store'])){
		$store_req=$_GET['store'];
		if($store_req=='all') $store_qry='';
		elseif($store_req=='all-sub') $store_qry='st.`sub_system`='.$sub_system.' AND st.`status`!= 0 AND';
		else $store_qry='st.id='.$store_req.' AND';
	}
	if(isset($_GET['tags'])){
		$tag_selection=$_GET['tag_selection'];
		$tags_req=$_GET['tags'];
		if($tags_req!=''){
			$tags_list="tn.tag IN ('".str_replace("|","','",$tags_req)."')";
			$tags_list2='';
			$tmp_arr=array();
			$tmp_arr=explode("|",$tags_req);
			$match_count=sizeof($tmp_arr);
			if($tag_selection==1) $query="SELECT ta.item FROM tag_name tn, tag_assignment ta WHERE tn.id=ta.tag AND $tags_list";
			if($tag_selection==2) $query="SELECT ta.item,count(ta.tag) FROM tag_assignment ta, tag_name tn WHERE ta.tag=tn.id AND $tags_list GROUP BY ta.item HAVING count(ta.tag)>=$match_count";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$tags_list2.=$row[0].',';
			}
			if($tags_list2!=''){
				$tags_list2=substr($tags_list2, 0, -1);
				$tags_qry='inv.id IN ('.$tags_list2.') AND';
			}else{
				$tags_qry="inv.id IN ('') AND";
			}
		}
	}

	if(($type==2)||($type==3)){
		$query="SELECT inv.id,inv.code,inv.description,inv.default_price,inv.default_cost,ca.id,ca.name,st.name FROM inventory_items inv, item_category ca, stores st  WHERE $category_qry $store_qry $tags_qry inv.`category`=ca.id AND inv.pr_sr='$type' AND inv.`status`=1";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$id[]=$row[0];
			$code[]=$row[1];
			$description[]=$row[2];
			$w_price[]=$row[3];
			$cost[]=$row[4];
			$cat_id[]=$row[5];
			$cat_name[]=$row[6];
			$st_name[]=$row[7];
			$color[]='black';
		}
	}


	if(($type==1)||($type==5)){
		$query="SELECT inv.id,inv.code,inv.description,iqt.w_price,iqt.r_price,iqt.c_price,iqt.drawer_no,iqt.qty,ca.id,ca.name,st.name,st.id,iqt.id,inv.unic FROM inventory_items inv, inventory_qty iqt, item_category ca, stores st  WHERE $category_qry $store_qry $tags_qry st.id=iqt.location AND inv.id=iqt.item AND inv.`category`=ca.id AND inv.pr_sr='1' AND inv.`status`=1 ORDER BY iqt.drawer_no";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$id[]=$row[0];
			$code[]=$row[1];
			$description[]=$row[2];
			$drawer[]=$row[6];
			$cat_id[]=$row[8];
			$cat_name[]=$row[9];
			$st_name[]=$row[10];
			$itq_id[]=$row[12];
			$itq_id_tmp=$row[12];
			$unic=$row[13];
			if(($unic_cal)&&($unic==1)){
				$query1="SELECT COUNT(id),SUM(w_price),SUM(r_price),SUM(c_price) FROM inventory_unic_item WHERE itq_id='$itq_id_tmp' AND `status`='0'";
				$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
				$qty[]=$row1[0];
				if($row1[0]!=0){
					$w_price[]=$row1[1]/$row1[0];
					$r_price[]=$row1[2]/$row1[0];
					$cost[]=$row1[3]/$row1[0];
				}else{
					$w_price[]=0;
					$r_price[]=0;
					$cost[]=0;
				}
				$color[]='black';
			}else{
				$qty[]=$row[7];
				$w_price[]=$row[3];
				$r_price[]=$row[4];
				$cost[]=$row[5];
				if(checkMismatch($row[13],$row[0],$row[11],$row[12],$row[7]))	$color[]='black'; else{ $color[]='orange'; $mismatch++; }
			}
		}

		$query="SELECT inv.id,inv.code,inv.description,itn.w_price,itn.r_price,itn.c_price,itn.qty,ca.id,ca.name,st.name FROM inventory_items inv, inventory_new itn, item_category ca, stores st  WHERE $category_qry $store_qry $tags_qry st.id=itn.store AND inv.id=itn.item AND inv.`category`=ca.id AND inv.pr_sr='1' AND inv.`status`=1";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$id[]=$row[0];
			$code[]=$row[1].' (New)';
			$description[]=$row[2];
			$w_price[]=$row[3];
			$r_price[]=$row[4];
			$cost[]=$row[5];
			$drawer[]='';
			$qty[]=$row[6];
			$cat_id[]=$row[7];
			$cat_name[]=$row[8];
			$st_name[]=$row[9];
			$itq_id[]='n';
			$color[]='red';
		}
	}


	if($type==5){
		//-----Transfers-----------//
		$query="SELECT inv.id,inv.code,inv.description,iqt.w_price,iqt.r_price,iqt.c_price,iqt.drawer_no,tr.qty,ca.id,ca.name,st.name,iqt.id,inv.unic,tr.id FROM inventory_items inv, inventory_qty iqt, item_category ca, stores st, transfer_main tm, transfer tr  WHERE $category_qry $store_qry tm.gtn_no=tr.gtn_no AND tm.from_store=st.id AND tr.item=inv.id AND st.id=iqt.location AND inv.id=iqt.item AND inv.`category`=ca.id AND inv.pr_sr='1' AND tm.`status` IN (0,4) AND inv.`status`=1 ORDER BY iqt.drawer_no";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$itq_id_tmp=$row[11];
			$unic=$row[12];
			$tr_id=$row[13];
			if(($unic_cal)&&($unic==1)){
				$query1="SELECT COUNT(id),SUM(w_price),SUM(r_price),SUM(c_price) FROM inventory_unic_item WHERE itq_id='$itq_id_tmp' AND trans_id='$tr_id' AND `status`='3'";
				$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
				$qty1=$row1[0];
				$w_price1=$r_price1=$cost1=0;
				if($row1[0]!=0){
					$w_price1=$row1[1]/$row1[0];
					$r_price1=$row1[2]/$row1[0];
					$cost1=$row1[3]/$row1[0];
				}
			}else{
				$qty1=$row[7];
				$w_price1=$row[3];
				$r_price1=$row[4];
				$cost1=$row[5];
			}

			$key=array_search($itq_id_tmp,$itq_id);
			if($key>-1){
				$qty[$key]=$qty[$key]+$qty1;
				$color[$key]='blue';
			}else{
				$id[]=$row[0];
				$code[]=$row[1];
				$description[]=$row[2];
				$w_price[]=$w_price1;
				$r_price[]=$r_price1;
				$cost[]=$cost1;
				$drawer[]=$row[6];
				$qty[]=$qty1;
				$cat_id[]=$row[8];
				$cat_name[]=$row[9];
				$st_name[]=$row[10];
				$itq_id[]=$row[11];
				$color[]='black';
			}
		}
		//-----Unlocked and Undelivered Bills-----------//
		$query="SELECT inv.id,inv.code,inv.description,iqt.w_price,iqt.r_price,iqt.c_price,iqt.drawer_no,bi.qty,ca.id,ca.name,st.name,iqt.id,inv.unic,bi.id FROM inventory_items inv, inventory_qty iqt, item_category ca, stores st, bill bi, bill_main bm  WHERE $category_qry $store_qry bi.item=inv.id AND bi.invoice_no=bm.invoice_no AND bm.store=st.id AND st.id=iqt.location AND inv.id=iqt.item AND inv.`category`=ca.id AND inv.pr_sr='1' AND (bm.`lock`=0 OR bm.`status` IN (1,2,3,4)) AND bm.`status`!=0 AND inv.`status`=1 ORDER BY iqt.drawer_no";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$itq_id_tmp=$row[11];
			$unic=$row[12];
			$bi_id=$row[13];
			if(($unic_cal)&&($unic==1)){
				$query1="SELECT COUNT(id),SUM(w_price),SUM(r_price),SUM(c_price) FROM inventory_unic_item WHERE itq_id='$itq_id_tmp' AND bill_id='$bi_id' AND `status`='1'";
				$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
				$qty1=$row1[0];
				$w_price1=$r_price1=$cost1=0;
				if($row1[0]!=0){
					$w_price1=$row1[1]/$row1[0];
					$r_price1=$row1[2]/$row1[0];
					$cost1=$row1[3]/$row1[0];
				}
			}else{
				$qty1=$row[7];
				$w_price1=$row[3];
				$r_price1=$row[4];
				$cost1=$row[5];
			}

			$key=array_search($itq_id_tmp,$itq_id);
			if($key>-1){
				$qty[$key]=$qty[$key]+$qty1;
				$color[$key]='blue';
			}else{
				$id[]=$row[0];
				$code[]=$row[1];
				$description[]=$row[2];
				$w_price[]=$w_price1;
				$r_price[]=$r_price1;
				$cost[]=$cost1;
				$drawer[]=$row[6];
				$qty[]=$qty1;
				$cat_id[]=$row[8];
				$cat_name[]=$row[9];
				$st_name[]=$row[10];
				$itq_id[]=$row[11];
				$color[]='black';
			}
		}
	}
	if($type==4){
		$query="SELECT rpi.id,rp.name,rp.drawer_no,rpi.c_price,rpi.qty,rpi.reorder_level,rpi.reorder_qty,st.name FROM repair_parts_inventory rpi, repair_parts rp, stores st WHERE rpi.part=rp.id AND rpi.location=st.id AND $store_qry rp.`status`='1' ";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$id[]=$row[0];
			$description[]=$row[1];
			$drawer[]=$row[2];
			$cost[]=$row[3];
			$qty[]=$row[4];
			$reorder_level[]=$row[5];
			$reorder_qty[]=$row[6];
			$st_name[]=$row[7];
		}
	}
}

// updated by nirmal 06_11_2023
function getUnicItems(){
	$item_category=$item_desc=$item_sn=$item_shipment_date=$item_trans_date=array();
	$user=$_COOKIE['user'];
	$type=$_GET['type'];
	$inf_company=inf_company(1);
	$systemid=inf_systemid(1);
	$today=dateNow();
	$item_store='';
	$category_qry=$store_qry=$tags_qry='';

	include('config.php');

	if(isset($_GET['category'])){
		$category_req=$_REQUEST['category'];
		if($category_req=='all') $category_qry=''; else $category_qry='itc.id='.$category_req.' AND';
	}

	if(isset($_GET['store'])){
		$store_req=$_GET['store'];
		if($store_req=='all') $store_qry=''; else $store_qry='st.id='.$store_req.' AND';
	}

	if(isset($_GET['tags'])){
		$tag_selection=$_GET['tag_selection'];
		$tags_req=$_GET['tags'];
		if($tags_req!=''){
			$tags_list="tn.tag IN ('".str_replace("|","','",$tags_req)."')";
			$tags_list2='';
			$tmp_arr=array();
			$tmp_arr=explode("|",$tags_req);
			$match_count=sizeof($tmp_arr);
			if($tag_selection==1) $query="SELECT ta.item FROM tag_name tn, tag_assignment ta WHERE tn.id=ta.tag AND $tags_list";
			if($tag_selection==2) $query="SELECT ta.item,count(ta.tag) FROM tag_assignment ta, tag_name tn WHERE ta.tag=tn.id AND $tags_list GROUP BY ta.item HAVING count(ta.tag)>=$match_count";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$tags_list2.=$row[0].',';
			}
			if($tags_list2!=''){
				$tags_list2=substr($tags_list2, 0, -1);
				$tags_qry='itm.id IN ('.$tags_list2.') AND';
			}else{
				$tags_qry="itm.id IN ('') AND";
			}
		}
	}

	$query="SELECT itc.name,itm.description,itu.sn,st.name,date(sm.shipment_date),date(tm.`date`) FROM inventory_items itm, inventory_qty itq, item_category itc, stores st, inventory_unic_item itu LEFT JOIN transfer tr INNER JOIN transfer_main tm ON tm.gtn_no=tr.gtn_no ON itu.trans_id=tr.id LEFT JOIN shipment_main sm  ON itu.shipment_no=sm.id  WHERE $category_qry $store_qry $tags_qry itm.id=itq.item AND itq.id=itu.itq_id AND itm.category=itc.id AND st.id=itq.location AND itm.unic=1 AND itu.`status`=0 ORDER BY itc.name,itm.description,itu.sn";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$item_category[]=$row[0];
		$item_desc[]=$row[1];
		$item_sn[]=$row[2];
		$item_store=$row[3];
		$item_shipment_date[]=$row[4];
		$item_trans_date[]=$row[5];
		$item_inv[]='normal';
	}
	if($type==5){
		$query="SELECT itc.name,itm.description,itu.sn,st.name,date(sm.shipment_date),date(tm.`date`) FROM inventory_items itm, inventory_qty itq, item_category itc, stores st, transfer_main tm, transfer tr, inventory_unic_item itu, shipment_main sm WHERE $category_qry $store_qry $tags_qry itu.shipment_no=sm.id AND tm.gtn_no=tr.gtn_no AND itu.trans_id=tr.id AND itm.id=itq.item AND itq.id=itu.itq_id AND itm.category=itc.id AND st.id=itq.location AND itm.unic=1 AND tm.`status` IN (0,4) ORDER BY itc.name,itm.description,itu.sn";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$item_category[]=$row[0];
			$item_desc[]=$row[1];
			$item_sn[]=$row[2];
			$item_store=$row[3];
			$item_shipment_date[]=$row[4];
			$item_trans_date[]=$row[5];
			$item_inv[]='trans';
		}
		$query="SELECT itc.name,itm.description,itu.sn,st.name,date(sm.shipment_date),date(tm.`date`) FROM inventory_items itm, inventory_qty itq, item_category itc, stores st, shipment_main sm, bill_main bm, inventory_unic_item itu LEFT JOIN transfer tr INNER JOIN transfer_main tm ON tm.gtn_no=tr.gtn_no ON itu.trans_id=tr.id WHERE $category_qry $store_qry $tags_qry bm.invoice_no=itu.invoice_no AND itu.shipment_no=sm.id AND itm.id=itq.item AND itq.id=itu.itq_id AND itm.category=itc.id AND st.id=itq.location AND itm.unic=1 AND (bm.`lock`=0 OR bm.`status` IN (1,2,3,4)) ORDER BY itc.name,itm.description,itu.sn";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$item_category[]=$row[0];
			$item_desc[]=$row[1];
			$item_sn[]=$row[2];
			$item_store=$row[3];
			$item_shipment_date[]=$row[4];
			$item_trans_date[]=$row[5];
			$item_inv[]='bill';
		}
	}
	require_once ('components/inventory/view/excel_inventory_unic_list.php');
	//require_once ('plugin/PHPExcel-1.8/production/Inventory_Unic_List.php');
}

// added by nirmal 21_08_11, updated by nirmal 21_9_21
function getShipmentItemsTmp($shipment_no){
	global $ship_inv_id,$ship_itm_desc,$ship_item_qty,$ship_itm_st_name,$ship_itm_st_color,$editable;
	$ship_inv_id=$ship_itm_desc=$ship_item_qty=$ship_itm_st_name=$ship_itm_st_color=array();

	if($shipment_no != ""){
		include('config.php');

		$user_id=$_COOKIE['user_id'];
		$query = "SELECT added_by FROM `shipment_main_tmp` WHERE `id` = '$shipment_no'";
		$result=mysqli_query($conn2,$query);
		$row=mysqli_fetch_array($result);
		$added_by = $row[0];

		if($added_by == $user_id) $editable = true; else $editable = false;

		$query="SELECT sit.`id`,itm.`description`,sit.`added_qty`,sit.`status`,sit.`inv_item` FROM shipment_item_tmp sit, inventory_items itm WHERE sit.`inv_item`=itm.`id` AND sit.`shipment_tmp_no`='$shipment_no'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$ship_inv_id[]=$row[0];
			$ship_itm_desc[]=$row[1];
			$ship_item_qty[]=$row[2];

			$json_array=json_decode(shipmentTmpItmStatus($row[3]));
			$ship_itm_st_name[]=$json_array->{"sn_txt"};
			$ship_itm_st_color[]=$json_array->{"sn_color"};
		}
	}
}

// added by nirmal 21_9_2
function getShipmentUnicItemsTmp($shipment_no){
	global $ship_itm_id,$ship_itm_desc,$ship_itm_qty,$editable,$ship_itm_st_name,$ship_itm_st_color;
	$ship_itm_id=$ship_itm_desc=$ship_itm_qty=$ship_itm_st_name=$ship_itm_st_color=array();

	if($shipment_no != ""){
		include('config.php');

		$user_id=$_COOKIE['user_id'];
		$query = "SELECT added_by FROM `shipment_main_tmp` WHERE `id` = '$shipment_no'";
		$result=mysqli_query($conn2,$query);
		$row=mysqli_fetch_array($result);
		$added_by = $row[0];

		if($added_by == $user_id) $editable = true; else $editable = false;

		$query="SELECT sit.`id`, itm.`description`,count(sut.`shipment_tmp_itm_id`),sit.`status` FROM shipment_unic_tmp sut, inventory_items itm, shipment_item_tmp sit WHERE itm.`id` = sit.`inv_item` AND sut.`shipment_tmp_itm_id` = sit.`id` AND sit.`shipment_tmp_no`='$shipment_no' GROUP BY sit.`id`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$ship_itm_id[]=$row[0];
			$ship_itm_desc[]=$row[1];
			$ship_itm_qty[] = $row[2];

			$json_array=json_decode(shipmentTmpItmStatus($row[3]));
			$ship_itm_st_name[]=$json_array->{"sn_txt"};
			$ship_itm_st_color[]=$json_array->{"sn_color"};
		}
	}
}

// added by nirmal 21_08_30, update by nirmal 21_9_6
function getSnListTmp(){
	include('config.php');
	$shipment_tmp_itm_id = $_REQUEST['shipment_tmp_itm_id'];
	$user_id=$_COOKIE['user_id'];

	$query = "SELECT smt.added_by FROM `shipment_main_tmp` smt, shipment_item_tmp sit WHERE smt.`id`=sit.shipment_tmp_no AND sit.`id`='$shipment_tmp_itm_id'";
	$result=mysqli_query($conn2,$query);
	$row=mysqli_fetch_array($result);
	$added_by = $row[0];
	if($added_by == $user_id) $editable=true; else $editable=false;

	$query = "SELECT `id`,`sn`, `status` FROM shipment_unic_tmp WHERE `shipment_tmp_itm_id`='$shipment_tmp_itm_id' ORDER BY `id`";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$jsonArray["tmp_sn_id"][] = $row[0];
		$jsonArray["tmp_sn"][] = $row[1];
		$jsonArray["tmp_ship_itm_id"][] = $shipment_tmp_itm_id;
		$jsonArray["unic_tmp_editable"][] = $editable;
        switch ($row[2]){
            case "0" : $sn_st="Error";			break;
            case "1" : $sn_st="Pending";		break;
            case "2" : $sn_st="Saved";			break;
            case "3" : $sn_st="Duplicated";		break;
            case "4" : $sn_st="Duplicated";		break;
            default:   $sn_st="Critical Error";	break;
        }
		$jsonArray["tmp_sn_status"][] = $sn_st;
	}
	$myJSON = json_encode($jsonArray);
	return $myJSON;
}

function getShipmentItems(){
	global $ship_inv_id,$ship_itm_desc,$ship_item_qty;
	$ship_inv_id=array();
	if(isset($_REQUEST['shipment_no'])){
	$shipment_no=$_REQUEST['shipment_no'];

	include('config.php');
		$query="SELECT ins.id,itm.description,ins.added_qty FROM inventory_shipment ins, inventory_items itm WHERE ins.inv_item=itm.id AND ins.shipment_no='$shipment_no'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$ship_inv_id[]=$row[0];
			$ship_itm_desc[]=$row[1];
			$ship_item_qty[]=$row[2];
		}
	}
}

function getUnicList(){
	global $unic_cal,$itu_id,$itu_sn,$itu_status,$itm_description,$itu_w_price,$itu_r_price,$itu_c_price;
	$itu_sn=array();
	$unic_cal=unicCal();
	if(isset($_GET['ins_id'])){
		$ins_id=$_GET['ins_id'];
		$shipment_no=$_GET['shipment_no'];
		include('config.php');
		$query="SELECT itu.id,itu.sn,itu.`status`,itm.description,itu.warranty_item,itu.w_price,itu.r_price,itu.c_price FROM inventory_items itm, inventory_qty itq, inventory_unic_item itu WHERE itm.id=itq.item AND itq.id=itu.itq_id AND itu.inv_ship_id='$ins_id' AND itu.shipment_no='$shipment_no' AND itu.`status`!=2";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$itu_id[]=$row[0];
			$itu_sn[]=$row[1];
			if($row[4]==0) $itu_status[]=$row[2]; else $itu_status[]=20;
			$itm_description=$row[3];
			$itu_w_price[]=$row[5];
			$itu_r_price[]=$row[6];
			$itu_c_price[]=$row[7];
		}
	}
}

function updateOneUnicPrice(){
	$itu_id=$_GET['itu_id'];
	$c_price=$_GET['c_price'];
	$w_price=$_GET['w_price'];
	$r_price=$_GET['r_price'];
	$message='Error';
	$out=true;
	$status=$count=0;
	$query="";

	if(($c_price!='')&&($w_price!='')&&($r_price!='')){
		include('config.php');

		$query="SELECT `status`,invoice_no,bill_id FROM inventory_unic_item WHERE id='$itu_id'";
		$row=mysqli_fetch_row(mysqli_query($conn2,$query));
		$status=$row[0];
		$invoice_no=$row[1];
		$bill_id=$row[2];

		if($out){
			if($status==0)	$query="UPDATE `inventory_unic_item` SET `w_price`='$w_price',`r_price`='$r_price',`c_price`='$c_price' WHERE `id`='$itu_id'";
			if($status==1)	$query="UPDATE `inventory_unic_item` SET `c_price`='$c_price' WHERE `id`='$itu_id'";
			if($query!=""){
				$result=mysqli_query($conn,$query);
				if($result){ $message='Done'; }else{ $out=false; }
			}
		}

		if($out){
			$query="SELECT comment FROM bill WHERE id='$bill_id'";
			$row=mysqli_fetch_row(mysqli_query($conn2,$query));
			$comment=$row[0];
			if($comment!=''){
				$query="SELECT count(id),SUM(c_price) FROM inventory_unic_item WHERE sn IN ($comment)";
				$row=mysqli_fetch_row(mysqli_query($conn2,$query));
				$count=$row[0];
				$cost=$row[1];
			}

			if($status==1 && $count!=0){
				$unit_cost=$cost/$count;
				$query="UPDATE `bill` SET `cost`='$unit_cost' WHERE `id`='$bill_id'";
				$result=mysqli_query($conn,$query);
				if(!$result){ $message='Error: Invoice update failed'; $out=false; }
			}
		}
	}
	return $message;
}

function updateBulkUnicPrice(){
	$ins_id=$_GET['ins_id'];
	$c_price=$_GET['c_price'];
	$w_price=$_GET['w_price'];
	$r_price=$_GET['r_price'];
	$message='Error';
	$out=true;
	$query="";

	if(($c_price!='')||($w_price!='')||($r_price!='')){
		include('config.php');

		$qry0='';
		if($w_price!='') $qry0.="`w_price`='$w_price',";
		if($r_price!='') $qry0.="`r_price`='$r_price',";
		if($c_price!='') $qry0.="`c_price`='$c_price'";

		$query0="SELECT id,`status`,invoice_no,bill_id FROM inventory_unic_item WHERE inv_ship_id='$ins_id'";
		$result0=mysqli_query($conn,$query0);
		while($row0=mysqli_fetch_array($result0)){
			$itu_id=$row0[0];
			$status=$row0[1];
			$invoice_no=$row0[2];
			$bill_id=$row0[3];

			if(($status==1)&&($c_price>0)){
				$query="UPDATE `bill` SET `cost`='$c_price' WHERE `id`='$bill_id'";
				$result=mysqli_query($conn,$query);
				if(!$result) $out=false;
			}

			if($out){
				if($status==0)	$query="UPDATE `inventory_unic_item` SET $qry0 WHERE `id`='$itu_id'";
				if(($status==1)&&($c_price>0))	$query="UPDATE `inventory_unic_item` SET `c_price`='$c_price' WHERE `id`='$itu_id'";
				if($query!=""){
					$result=mysqli_query($conn,$query);
					if($result){ $message='Done';}
				}
			}
		}
	}
	return $message;
}

function editUnic(){
	global $message,$ins_id,$shipment_no;
	$ins_id=$_POST['ins_id'];
	$oldsn=$_POST['oldsn'];
	$newsn=$_POST['newsn'];
	$shipment_no=$_GET['shipment_no'];
	include('config.php');
	$result = mysqli_query($conn,"SELECT count(id) as `count` FROM inventory_unic_item WHERE sn='$newsn' AND `status`!=2");
	$row = mysqli_fetch_assoc($result);
	$unic_count=$row['count'];
	if($unic_count==0){
		$query="UPDATE `inventory_unic_item` SET `sn`='$newsn' WHERE `sn`='$oldsn' AND `status`=0";
		$result=mysqli_query($conn,$query);
		if($result){
			$message='Unic ID was Updates Successfully';
			return true;
		}else{
			$message='Error: Unic ID Could Not Be Updated';
			return false;
		}
	}else{
		$message='Error: Duplicated Unic ID Detected';
		return false;
	}
}

function deleteUnic($ins_id0,$sn){
	global $message,$ins_id,$shipment_no;
	$ins_id=$ins_id0;
	$user=$_COOKIE['user_id'];
	$time_now=timeNow();
	$unic_cal=unicCal();
	$result2=$result3=false;
	$proceed=true;
	include('config.php');
	$result0 = mysqli_query($conn,"SELECT itq_id,`status` FROM inventory_unic_item WHERE sn='$sn' AND `status`='0'");
	$row0 = mysqli_fetch_assoc($result0);
	$itq_id=$row0['itq_id'];
	$itq_status=$row0['status'];
	$result0 = mysqli_query($conn,"SELECT shipment_no,new_stock FROM inventory_shipment WHERE id='$ins_id'");
	$row0 = mysqli_fetch_assoc($result0);
	$shipment_no=$row0['shipment_no'];
	$newstock_id=$row0['new_stock'];

	if($proceed){
		if(!$unic_cal){
			if(mismatch($itq_id)){ $proceed=true; }else{ $proceed=false; $message='Error 107. Please contact Support !'; }
		}
	}
	if($proceed){ if($itq_status==0){ $proceed=true; }else{ $proceed=false; $message='Error: Item Status was Modified !'; }}

	if($proceed){
		$query="UPDATE `inventory_unic_item` SET `status`='2',`invoice_no`='0',`bill_id`='0',`return_id`='0',`trans_no`='0',`trans_id`='0',`deleted_by`='$user',`deleted_date`='$time_now' WHERE `sn`='$sn' AND `status`='0'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $proceed=false; $message='Error: Unique Item could not be deleted.'; }
	}

	if($proceed){
		if(!$unic_cal){
			if($newstock_id==0){
				$result0 = mysqli_query($conn,"SELECT qty FROM inventory_qty WHERE id='$itq_id'");
				$row0 = mysqli_fetch_assoc($result0);
				$itq_qty=$row0['qty'];
				$new_itq_qty=$itq_qty-1;
				$result2=mysqli_query($conn,"UPDATE `inventory_qty` SET `qty`='$new_itq_qty' WHERE `id`='$itq_id'");
			}else{
				$result0 = mysqli_query($conn,"SELECT qty FROM inventory_new WHERE id='$newstock_id'");
				$row0 = mysqli_fetch_assoc($result0);
				$itn_qty=$row0['qty'];
				if($itn_qty!=''){
					$new_itn_qty=$itn_qty-1;
					if($new_itn_qty>0){
						$result2=mysqli_query($conn,"UPDATE `inventory_new` SET `qty`='$new_itn_qty' WHERE `id`='$newstock_id'");
					}else{
						$result2=mysqli_query($conn,"DELETE FROM `inventory_new` WHERE `id`='$newstock_id'");
					}
				}else{
					$result0 = mysqli_query($conn,"SELECT qty FROM inventory_qty WHERE id='$itq_id'");
					$row0 = mysqli_fetch_assoc($result0);
					$itq_qty=$row0['qty'];
					$new_itq_qty=$itq_qty-1;
					$result2=mysqli_query($conn,"UPDATE `inventory_qty` SET `qty`='$new_itq_qty' WHERE `id`='$itq_id'");
				}
			}
		}else{
			$result2=true;
		}

		if($result2){
			$query="UPDATE `inventory_shipment` SET `added_qty`=added_qty-1 WHERE `id`='$ins_id'";
			$result=mysqli_query($conn,$query);
			if($result){ $message='Unic ID was Deleted Successfully'; }else{ $proceed=false; $message='Error: Unic ID Could Not Be Deleted'; }
		}
	}
	return $proceed;
}

// added by nirmal 21_9_8
function verifySnTmp($shipment_no, $sn, $attempt){
	include('config.php');
	$case = 0;

	$result = mysqli_query($conn,"SELECT count(id) as `count` FROM inventory_unic_item WHERE sn='$sn' AND `status`!=2");
	$row = mysqli_fetch_assoc($result);
	$unic_count_prod=$row['count'];

	$result = mysqli_query($conn,"SELECT count(sut.id) as `count` FROM shipment_item_tmp sit, shipment_unic_tmp sut WHERE sit.id=sut.shipment_tmp_itm_id AND sut.sn='$sn' AND sut.`status`!=2 AND sit.`shipment_tmp_no`='$shipment_no'");
	$row = mysqli_fetch_assoc($result);
	$unic_count_tmp=$row['count'];

	if($unic_count_prod > 0){
		$case = 3; // sn already exists in production
	}else{
		if($attempt == 'tmp'){
			if($unic_count_tmp == 0){
				$case = 1; // first time saving
			}else{
				$case = 4; // sn already exists in tmp
			}
		}
		if($attempt == 'finalize'){
			if($unic_count_tmp == 1) $case = 1; // no problem to save
			else $case = 4; // sn already exists in tmp
		}
	}

	return $case;
}

// add by nirmal 21_9_1, updated by nirmal 21_9_6
function removeSnTmp($sn_id){
	global $message,$shipment_no;
	$user=$_COOKIE['user_id'];
	$st_arr[]=array();
	$message = "Item was removed successfully";
	$out=true;
	include('config.php');

	if($out){
	 	// get auth for delete item
	 	$query="SELECT smt.id,sit.id,smt.`added_by` FROM shipment_main_tmp smt, shipment_item_tmp sit, shipment_unic_tmp sut WHERE smt.id=sit.shipment_tmp_no AND sit.id=sut.shipment_tmp_itm_id AND sut.id='$sn_id'";
		$result=mysqli_query($conn,$query);
		$row=mysqli_fetch_array($result);
		$shipment_no=$row[0];
		$shipment_tmp_itm_id=$row[1];
		$added_by=$row[2];

		if($added_by != $user){ $message = "Sorry! You don't have no authority to perform this action!"; $out=false; }
	}

	if($out){
		$query="DELETE FROM `shipment_unic_tmp` WHERE `id`='$sn_id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message = "Error: SN could not be Deleted"; $out=false; }
	}

	if($out){
		$query = "SELECT COUNT(id) as `count` FROM `shipment_unic_tmp` WHERE shipment_tmp_itm_id = '$shipment_tmp_itm_id'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_assoc($result);
		$count1=$row['count'];

		if($count1 == 0){
			$query = "DELETE FROM `shipment_item_tmp` WHERE `id`='$shipment_tmp_itm_id'";
			$result=mysqli_query($conn,$query);
			if(!$result){ $message = "Error: Temp Item could not be Deleted"; $out=false; }
		}else{
			$query = "SELECT COUNT(id) as `count` FROM `shipment_unic_tmp` WHERE shipment_tmp_itm_id = '$shipment_tmp_itm_id' AND `status` != '1'";
			$result=mysqli_query($conn,$query);
			$row = mysqli_fetch_assoc($result);
			$count2=$row['count'];

			if($count2==0){
				$query = "UPDATE `shipment_item_tmp` SET `status` = '1' WHERE `id` = '$shipment_tmp_itm_id'";
				$result=mysqli_query($conn,$query);
				if(!$result){ $message = "Error: Temp Item Status could not be Updated"; $out=false; }
			}else{
				$i=0;
				$query="SELECT `id`,`sn` FROM `shipment_unic_tmp` WHERE shipment_tmp_itm_id='$shipment_tmp_itm_id'";
				$result = mysqli_query($conn,$query);
				while($row = mysqli_fetch_assoc($result)){
					$sn_id = $row['id'];
					$sn=$row['sn'];
					if($out){
						$status=verifySnTmp($shipment_no,$sn,'finalize');
						$query2="UPDATE `shipment_unic_tmp` SET `status` = '$status' WHERE `id` = '$sn_id'";
						$result2=mysqli_query($conn,$query2);
						if(!$result){ $message = "Error: SN Status could not be Updated"; $out=false; }
						$st_arr[$i] = $status;
						$i+=1;
					}
  				}

				if($out){
					if(array_search(3,$st_arr)>-1) $checked_status3=true; else $checked_status3=false;
					if(array_search(4,$st_arr)>-1) $checked_status4=true; else $checked_status4=false;

					if($checked_status3){
						$item_add_status=3;
					}else if($checked_status4){
						$item_add_status=4;
					}else if((!$checked_status3)&&(!$checked_status4)){
						$item_add_status=1;
					}else $item_add_status=0;
					$query="UPDATE `shipment_item_tmp` SET `status` ='$item_add_status'  WHERE `id` = '$shipment_tmp_itm_id'";
					$result=mysqli_query($conn,$query);
					if(!$result){ $message='Error: The Item Status could not be Updated'; $out=false; }
				}
			}
		}
	}
	return $out;
}

// added by nirmal 21_08_17, updated by nirmal 21_12_17
function addQtyTmp($shipment,$unic,$itm_id,$qty,$c_price,$w_price,$r_price,$c_price2,$w_price2,$r_price2){
	global $message,$action,$shipment_no;
	$shipment_no=$shipment;
	if($unic=='yes') $action='show_add_unic_tmp'; else $action='show_add_qty_tmp';
	$message='Item was added to the shipment successfully';
	$user_id=$_COOKIE['user_id'];
	$user=$_COOKIE['user'];
	$time_now=timeNow();
	$out=true;

	// check is there is any non numeric character
	if((!isNumeric($c_price) || !isNumeric($w_price) || !isNumeric($r_price) || !isNumeric($c_price2) || !isNumeric($w_price2) || !isNumeric($r_price2))){
		$message = "Sorry! Your input values must be intigers. Please check your input numbers again";
		$out=false;
	}

	include('config.php');

	$query = "SELECT `code`,`unic` FROM `inventory_items` WHERE id ='$itm_id'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_assoc($result);
	$itm_code=$row['code'];
	$itm_unic=$row['unic'];
	if(($unic=='yes')&&($itm_unic==0)){ $message='You cannot add qty item from here!'; $out=false; }
	if(($unic=='no')&&($itm_unic==1)){ $message='You cannot add unic item from here!'; $out=false; }

	// check the owner of shipment to contine add more items to the shipment
	if($out){
		$query = "SELECT `added_by` FROM `shipment_main_tmp` WHERE `id`='$shipment_no'";
		$result=mysqli_query($conn,$query);
		$row=mysqli_fetch_array($result);
		$added_by = $row[0];
		if($added_by != $user_id) {	$message = "Sorry! You don't have no authority to perform this action!"; $out=false; }
	}

	if($itm_unic ==1){
		if($out){
			for($i=1;$i<=10;$i++){
				if($_REQUEST['sn'.$i]!=''){
					if($out){
						$sn=$_REQUEST['sn'.$i];
						if(!stringAndNumberValidation($sn)){
							$out=false;
							$message="Error: SN should only contains letters and numbers!";
						}
					}
				}
			}
		}
	}

	if($out){
		$query="INSERT INTO `shipment_item_tmp` (`shipment_tmp_no`,`inv_item`,`inv_code`,`added_qty`,`added_by`,`c_price`,`w_price`,`r_price`,`c_price2`,`w_price2`,`r_price2`,`unic`,`time`,`status`) VALUES ('$shipment_no','$itm_id','$itm_code','$qty','$user','$c_price','$w_price','$r_price','$c_price2','$w_price2','$r_price2','$itm_unic','$time_now','1')";
		$result=mysqli_query($conn,$query);
		$last_tmp_itm_id=mysqli_insert_id($conn);
		if(!$result){ $message='Error: The Item could not be added to DB'; $out=false; }
	}
	if($itm_unic == 1){
		if($out){
			$st_arr[] = array();
			for($i=1;$i<=10;$i++){
				if($_REQUEST['sn'.$i]!=''){
					if($out){
						$sn=$_REQUEST['sn'.$i];
						$status = verifySnTmp($shipment,$sn,'tmp');
						$st_arr[$i] = $status;
						$query0="INSERT INTO `shipment_unic_tmp` (`shipment_tmp_itm_id`,`sn`,`status`) VALUES ('$last_tmp_itm_id','$sn','$status')";
						$result0=mysqli_query($conn,$query0);
						if(!$result0){ $message='Error: The SN could not be added to the DB'; $out=false; }
					}
				}
			}
			// check shipment has any duplicate of original sn or same shipment
			if(array_search(3,$st_arr)>-1) $checked_status3=true; else $checked_status3=false;
			if(array_search(4,$st_arr)>-1) $checked_status4=true; else $checked_status4=false;

			if($out){
				if($checked_status3){
					$item_add_status=3;
				}else if($checked_status4){
					$item_add_status=4;
				}else if((!$checked_status3)&&(!$checked_status4)){
					$item_add_status=1;
				}else $item_add_status=0;
				$query1 = "UPDATE `shipment_item_tmp` SET `status` ='$item_add_status'  WHERE `id` = '$last_tmp_itm_id'";
				$result1=mysqli_query($conn,$query1);
				if(!$result1){ $message='Error: The Item Status could not be Updated'; $out=false; }
			}
		}
	}

	return $out;
}

// added by nirmal 21_9_6
function addQtyFinal($shipment_num,$unic,$code,$qty,$c_price,$w_price,$r_price,$c_price2,$w_price2,$r_price2,$sn){
	global $message,$shipment_no;
	$shipment_no = $shipment_num;
	$user=$_COOKIE['user'];
	$store=$_COOKIE['store'];
	if($unic == 1) $unic = 'yes';
	$time_now=timeNow();
	$c_price1= preg_replace("/[^0-9.]/",'',$c_price);
	$w_price1= preg_replace("/[^0-9.]/",'',$w_price);
	$r_price1= preg_replace("/[^0-9.]/",'',$r_price);
	$unic_cal=unicCal();
	$qty_old=$new_stock_id=$iqt_id='';
	$unic_count=$duplicate=$qty_old=$qty_old_total=$qty0=0;
	$message='Inventory could not be updated!';
	$out=true;
	if(($c_price1==$c_price2)&&($w_price1==$w_price2)&&($r_price1==$r_price2)) $temptable1=false; else $temptable1=true;
	if((($w_price1!=0)&&($w_price2==0))||(($r_price1!=0)&&($r_price2==0))) $temptable2=false; else $temptable2=true;
	include('config.php');

	$result = mysqli_query($conn,"SELECT id,unic FROM inventory_items WHERE code='$code'");
	$row = mysqli_fetch_assoc($result);
	$inv_id=$row['id'];
	$unic0=$row['unic'];


	if($unic== 'yes'){
		if(!validateDeleteShipment($shipment_no,1)){ $out=false; $message='Error: The Shipment is LOCKED. Cannot Apend any items!'; }
	}
	if($out){
		if(!(($unic0==0)||($unic=='yes'))){ $out=false; $message='Error: For this Item, Qty Cannot be Added from Here'; }
	}

	if($out){
		if($shipment_no==0){
			$query="SELECT MAX(shipment_no) FROM inventory_shipment";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_array($result)){
				$shipment_no=$row[0]+1;
			}
		}
		$query="SELECT id,qty FROM inventory_qty WHERE location='$store' AND `item`='$inv_id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$iqt_id=$row[0];
		$qty_old=$row[1];
		$qty_old_total=$row[1];
		$query="SELECT SUM(qty) FROM inventory_new WHERE store='$store' AND item='$inv_id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$qty_old_total+=$row[0];

		if($qty_old=='') $qty_old=0;
		if($qty_old_total=='') $qty_old_total=0;

		$query="INSERT INTO `inventory_shipment` (`shipment_no`,`inv_item`,`inv_code`,`location`,`cost`,`old_qty`,`added_qty`,`added_by`,`time`) VALUES ('$shipment_no','$inv_id','$code','$store','$c_price1','$qty_old_total','$qty','$user','$time_now')";
		$result=mysqli_query($conn,$query);
		$lastid=mysqli_insert_id($conn);
		if(!$result){
			$message = "Error: Items cannot be added to the Database!" . mysqli_error($conn);
			$out=false;
		}

 		if($out){
			if($unic=='yes'){
				for($i=0;$i<=9;$i++){
					if($sn[$i]!=''){
						$sn1 = $sn[$i];
						$result = mysqli_query($conn,"SELECT count(id) as `count` FROM inventory_unic_item WHERE sn='$sn1' AND `status`!=2");
						$row = mysqli_fetch_assoc($result);
						$unic_count=$row['count'];
						if($unic_count==0){
							$qty0++;
						}else{
							$duplicate++;
						}
					}
				}
				$qty=$qty0;
			}

			if($qty==0){
				mysqli_query($conn,"DELETE FROM `inventory_shipment` WHERE `id`='$lastid'");
			}
		}

        $debug_id=debugStart($inv_id,$qty);

		if($out){
			if($qty<=0){
				$out=false;
				$message='Error: Item Quantity is 0';
				if($duplicate>0)$message='Error: Duplicated Unic ID Detected';
			}
		}

		if($out){
			if($qty>0){
				if($iqt_id=='') $temptable1=false;
				$qty_new=$qty_old+$qty;
				if(($unic_cal)&&($unic=='yes')){
					$qty=$qty_new=0;
				}
				if($temptable1){
					if($temptable2){
						if($qty_old==0){
							$query="UPDATE `inventory_qty` SET `w_price`='$w_price1',`r_price`='$r_price1',`c_price`='$c_price1',`qty`='$qty_new' WHERE `id`='$iqt_id'";
							$result=mysqli_query($conn,$query);
							if(!$result){ $out=false; $message='Error: Failed to update the Inventory!'; }
						}else{
							$newtmp_id='';
							$query="SELECT id FROM inventory_new WHERE item='$inv_id' AND store='$store' AND `w_price`='$w_price1' AND `r_price`='$r_price1' AND `c_price`='$c_price1' LIMIT 1";
							$row=mysqli_fetch_row(mysqli_query($conn,$query));
							$newtmp_id=$row[0];
							if($newtmp_id!=''){
								$query="UPDATE `inventory_new` SET `qty`=qty+$qty, `shipment_no`=null WHERE id='$newtmp_id'";
								$result=mysqli_query($conn,$query);
								if(!$result){ $out=false; $message='Error: Failed to Update the New Inventory!'; }
								$new_stock_id=$newtmp_id;
							}else{
								$query="INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`,`shipment_no`) VALUES ('$inv_id','$w_price1','$r_price1','$c_price1','$qty','$store','$shipment_no') ";
								$result=mysqli_query($conn,$query);
								if(!$result){ $out=false; $message='Error: Failed to Add the New Inventory!'; }
								$new_stock_id=mysqli_insert_id($conn);
							}
						}
					}else{
						$out=false;
						mysqli_query($conn,"DELETE FROM `inventory_shipment` WHERE `id`='$lastid'");
						$message='If "Cost", "Wholesale Price", "Retail Price" was changed from 0<br />please update pricing from "EDIT ITEM"';
					}
				}else{
					if($iqt_id==''){
						$query="INSERT INTO `inventory_qty` (`item`,`location`,`w_price`,`r_price`,`c_price`,`qty`) VALUES ('$inv_id','$store','$w_price1','$r_price1','$c_price1','$qty') ";
						$result=mysqli_query($conn,$query);
						if(!$result){ $out=false; $message='Error: Failed to Add the Inventory!'; }
						$iqt_id=mysqli_insert_id($conn);
					}else{
						$query="UPDATE `inventory_qty` SET `qty`='$qty_new' WHERE `id`='$iqt_id'";
						$result=mysqli_query($conn,$query);
						if(!$result){ $out=false; $message='Error: Failed to Update the Inventory!'; }
					}
				}

			}
		}

		if($out){
			if($unic=='yes'){
				for($i=0;$i<=9;$i++){
					if($sn[$i] !=''){
						$sn1 = $sn[$i];
						$result = mysqli_query($conn,"SELECT count(id) as `count` FROM inventory_unic_item WHERE sn='$sn1' AND `status`!=2");
						$row = mysqli_fetch_assoc($result);
						$unic_count=$row['count'];
						if($unic_count==0){
							if($unic_cal)
								$query0="INSERT INTO `inventory_unic_item` (`itq_id`,`shipment_no`,`inv_ship_id`,`sn`,`w_price`,`r_price`,`c_price`,`status`) VALUES ('$iqt_id','$shipment_no','$lastid','$sn1','$w_price1','$r_price1','$c_price1','0') ";
							else
								$query0="INSERT INTO `inventory_unic_item` (`itq_id`,`shipment_no`,`inv_ship_id`,`sn`,`status`) VALUES ('$iqt_id','$shipment_no','$lastid','$sn1','0') ";
							$result0=mysqli_query($conn,$query0);
							if(!$result0){ $out=false; $message='Error: Failed to Add the Inventory Unique Database!'; }
						}
					}
				}
			}

			if($new_stock_id=='') $new_stock_id=0;
			if($unic=='yes') $qry7=",`added_qty`='$qty0'"; else $qry7="";
			$query="UPDATE `inventory_shipment` SET `new_stock`='$new_stock_id' $qry7 WHERE `id`='$lastid'";
			$result=mysqli_query($conn,$query);
			if(!$result){ $out=false; $message='Error: Failed to update the Shipment Item!'; }
			debugEnd($debug_id,'success');

			if($duplicate>0) $message='Alert: Duplicated Unic ID Detected'; else $message='Inventory was Updated Successfully!';
		}else{
			mysqli_query($conn,"DELETE FROM `inventory_shipment` WHERE `id`='$lastid'");
			debugEnd($debug_id,'fail');
		}
	}
	return $out;
}

// added by nirmal 21_09_27
function oneShipmentTmpFinalize($shipment_no, $sub_req, $sub_system){
	include('config.php');
	global $main_shipment_no,$action,$message;
	$ship_tmp_item_id = $unic = $inv_code = $add_qty = $c_price = $w_price = $r_price = $c_price2 = $w_price2 = $r_price2 = $ship_date = $suplier = $ship_inv_no = $ship_inv_date = $ship_inv_dudate = "";
	$action = $sub_req;
	$out = true;
	$main_shipment_no = 0;
	$one_item_saved = false;

	// check if any shipment of main shipment has any duplications
	$query="SELECT COUNT(`id`) FROM `shipment_item_tmp` WHERE `shipment_tmp_no`='$shipment_no' AND `status` IN('0','3','4')";
	$row=mysqli_fetch_row(mysqli_query($conn, $query));
	$count=$row[0];
	if($count > 0){ $message = "Please check your shipment Items before finalize the shipment"; $out = false; }

	if($out){
		// check if any item of this shipment no has record saved in original tables (this happen when one time a item successfully saved in original table but when hit finalize button again after removed error items)
		$query = "SELECT COUNT(`prod_shipment_no`) FROM `shipment_main_tmp` WHERE `id`='$shipment_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		if($row[0]>0) $one_item_saved=true; else $one_item_saved=false;

		// get records from shipment_main_tmp to save them inside  shipment_main
		$query="SELECT `prod_shipment_no`,`shipment_date`,`supplier`,`invoice_no`,`invoice_date`,`invoice_due`,`unic` FROM shipment_main_tmp WHERE id='$shipment_no'";

		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$prod_shipment_no=$row[0];
		$ship_date=$row[1];
		$suplier=$row[2];
		$ship_inv_no=$row[3];
		$ship_inv_date=$row[4];
		$ship_inv_dudate=$row[5];
		$unic=$row[6];

		// if there is already item saved for this tmp shipment no, not save new record again in shipment_main table
		if($one_item_saved){
			$main_shipment_no=$prod_shipment_no;
		}else{
			$out=addShipmentToMain($sub_system,$ship_date,$suplier,$ship_inv_no,$ship_inv_date,$ship_inv_dudate,$unic);
			if(!$out){$message='Error: The Shipment main could not be Inserted'; $out=false;}

			$query = "SELECT id FROM shipment_main ORDER BY id DESC LIMIT 1";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$main_shipment_no = $row[0];

			if($out){
				$query="UPDATE `shipment_main_tmp` SET `prod_shipment_no`= '$main_shipment_no' WHERE `id`='$shipment_no'";
				$result=mysqli_query($conn,$query);
				if(!$result){ $message='Error: The Item main temp could not be Updated'; $out=false; }
			}
		}
	}

	if($out){
		if($action == "show_add_unic_tmp"){
			// get items shipment items (sn sets)
			$query="SELECT `id`,inv_code,unic,added_qty,c_price,w_price,r_price,c_price2,w_price2,r_price2 FROM `shipment_item_tmp` WHERE `status` != '2' AND shipment_tmp_no ='$shipment_no'";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_array($result)){
				$i=$j=0;
				$st_arr = array();
				$sn_duplicate = 0;
				$sn_arr = array('','','','','','','','','','');

				$shipment_tmp_item_id=$row[0];
				$inv_code=$row[1];
				$unic=$row[2];
				$c_price = $row[4];
				$w_price = $row[5];
				$r_price = $row[6];
				$c_price2 = $row[7];
				$w_price2 = $row[8];
				$r_price2 = $row[9];

				$query1 = "SELECT `id`,`sn` FROM shipment_unic_tmp WHERE `shipment_tmp_itm_id`='$shipment_tmp_item_id' AND `status` IN('0','1')";
				$result1=mysqli_query($conn,$query1);

				while($row1 = mysqli_fetch_assoc($result1)){
					$unic_itm_id = $row1['id'];
					$row_sn = $row1['sn'];
					$status = verifySnTmp($shipment_no,$row_sn,'finalize');
					$st_arr[$j++] = $status;
					// if there is no sn duplicates in production or same shipment
					if($status == 1){
						$sn_arr[$i] = $row1['sn'];
						$i++;
					}else{
						// change status of unic item
						$query2="UPDATE `shipment_unic_tmp` SET `status`= '$status' WHERE `id`='$unic_itm_id'";
						$result2 = mysqli_query($conn,$query2);
						if($result2) { $sn_duplicate++; $out = false; $message = "Error: Please check your SNs  before finalize the shipment"; }
						else { $out = false; $message = "Error: SN status could not be Updated"; }
					}
	 			}
				// change status of shipment itm tmp table to prevent submit again with errors or duplicated
				if($sn_duplicate > 0){
					// check shipment has any duplicate of original sn or same shipment
					if(array_search(3,$st_arr)>-1) $checked_status3=true; else $checked_status3=false;
					if(array_search(4,$st_arr)>-1) $checked_status4=true; else $checked_status4=false;
					if($checked_status3){
						$item_add_status=3;
					}else if($checked_status4){
						$item_add_status=4;
					}else if((!$checked_status3)&&(!$checked_status4)){
						$item_add_status=1;
					}else $item_add_status=0;
					$query1 = "UPDATE `shipment_item_tmp` SET `status` ='$item_add_status'  WHERE `id` = '$shipment_tmp_item_id'";
					$result1=mysqli_query($conn,$query1);
					if(!$result1){ $message='Error: The Item Status could not be Updated'; $out=false; }
				}

				// send data to real function to save
				if($out){
					if(addQtyFinal($main_shipment_no,$unic,$inv_code,0,$c_price,$w_price,$r_price,$c_price2,$w_price2,$r_price2,$sn_arr)){
						$query2="UPDATE `shipment_unic_tmp` SET `status`= '2' WHERE shipment_tmp_itm_id = '$shipment_tmp_item_id'";
						$result2=mysqli_query($conn,$query2);
						if(!$result2){ $message='Error: The SN temp Status could not be Updated'; $out=false; }
						if($out){
							$query2="SELECT COUNT(sut.`id`) FROM shipment_item_tmp sit, shipment_unic_tmp sut WHERE sit.`id`=sut.`shipment_tmp_itm_id` AND sit.`shipment_tmp_no`='$shipment_no' AND sut.`status` != '2'";
							$row2=mysqli_fetch_row(mysqli_query($conn, $query2));
							$count=$row2[0];
							if($count == 0) {
								$query3 = "SELECT `id` FROM shipment_item_tmp WHERE shipment_tmp_no ='$shipment_no'";
								$result3=mysqli_query($conn,$query3);
								while($row3=mysqli_fetch_array($result3)){
									$ship_itm_tmp_no = $row3[0];
									mysqli_query($conn,"DELETE FROM `shipment_unic_tmp` WHERE `shipment_tmp_itm_id`='$ship_itm_tmp_no'");
									mysqli_query($conn,"DELETE FROM `shipment_item_tmp` WHERE `id`='$ship_itm_tmp_no'");
									mysqli_query($conn,"DELETE FROM `shipment_main_tmp` WHERE `id`='$shipment_no'");
								}
							}
						}
					}else{
						$out = false;
						$message='Error: The Shipment Unique Item could not be Updated';

						$query2="UPDATE `shipment_item_tmp` SET `status`= '0' WHERE `id` = '$shipment_tmp_item_id'";
						$result2 = mysqli_query($conn,$query2);
						if(!$result2){ $message='Error: The Shipment Item Status could not be Updated';}

						$query2="UPDATE `shipment_unic_tmp` SET `status`= '0' WHERE  shipment_tmp_itm_id = '$shipment_tmp_item_id'";
						$result2 = mysqli_query($conn,$query2);
						if(!$result2){ $message='Error: The Unic Item Status couldnot be Updated'; }
					}
				}
			}
		}else{
			// add all shipment_item_tmp data to respective real tables
			$query="SELECT `id`,inv_code,added_qty,c_price,w_price,r_price,c_price2,w_price2,r_price2,unic FROM `shipment_item_tmp` WHERE `status` != '2' AND shipment_tmp_no ='$shipment_no'";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_array($result)){
				$ship_tmp_item_id = $row[0];
				$inv_code = $row[1];
				$add_qty = $row[2];
				$c_price = $row[3];
				$w_price = $row[4];
				$r_price = $row[5];
				$c_price2 = $row[6];
				$w_price2 = $row[7];
				$r_price2 = $row[8];
				$unic = $row[9];

				// update status of tmp shipment according to saved status of original inventory tables.
				if(addQtyFinal($main_shipment_no,$unic,$inv_code,$add_qty,$c_price,$w_price,$r_price,$c_price2,$w_price2,$r_price2,"")){
					$query1="UPDATE `shipment_item_tmp` SET `status`= '2' WHERE `id`='$ship_tmp_item_id'";
					$result1 = mysqli_query($conn,$query1);
					if(!$result1){ $out = false; $message = "Error: Unable to update shipment temp status";}

					if($out){
						$query1 = "SELECT COUNT(id) FROM `shipment_item_tmp` WHERE shipment_tmp_no = '$shipment_no'";
						$row1 =mysqli_fetch_row(mysqli_query($conn,$query1));
						$shipment_item_count=$row1[0];
						$query1 = "SELECT COUNT(id) FROM `shipment_item_tmp` WHERE `shipment_tmp_no` = '$shipment_no' AND `status` ='2'";
						$row1 =mysqli_fetch_row(mysqli_query($conn,$query1));
						$shipment_added_item_count=$row1[0];
						if($shipment_item_count == $shipment_added_item_count){
							mysqli_query($conn,"DELETE FROM `shipment_item_tmp` WHERE `shipment_tmp_no`='$shipment_no'");
							mysqli_query($conn,"DELETE FROM `shipment_main_tmp` WHERE `id`='$shipment_no'");
						}
					}
				}else{
					$out = false;
					$query1="UPDATE `shipment_item_tmp` SET `status`= '0' WHERE `id`='$ship_tmp_item_id'";
					$result1 = mysqli_query($conn,$query1);
					$message = "Error: Unable to save to the original tables";
				}
			}
		}
	}
	return $out;
}

// added by nirmal 21_08_26
function getShipmentHeaderDetails($shipment_num){
	global $shipment_no,$supplier_id,$ship_date,$invoice_no,$invoice_date,$invoice_due, $editable;
	$user_id=$_COOKIE['user_id'];

	if(isset($shipment_num)){
		include('config.php');
		$query = "SELECT added_by FROM shipment_main_tmp smt WHERE id= '$shipment_num'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_assoc($result);
		$ship_user_id = $row['added_by'];

		if($user_id == $ship_user_id) $editable = true; else $editable = false;

		$query="SELECT `id`,`shipment_date`,`supplier`,`invoice_no`,`invoice_date`,`invoice_due` FROM shipment_main_tmp  WHERE `id`= '$shipment_num'";
		$result=mysqli_query($conn,$query);

		$row = mysqli_fetch_assoc($result);
		$shipment_no = $row['id'];
		$ship_date = $row['shipment_date'];
		$supplier_id = $row['supplier'];
		$invoice_no = $row['invoice_no'];
		$invoice_date = $row['invoice_date'];
		$invoice_due = $row['invoice_due'];
	}
}

// added by nirmal 21_08_27, updated by nirmal 21_08_30
function updateShipmentHeaderDetails(){
	global $message,$shipment_no,$action;
	$message = "Shipment header updated successfully!";
	$shipment_no = $_POST['shipment_no'];
	$action = $_POST['sub'];

	if(isset($shipment_no)){
		include('config.php');
		$ship_date = $_POST['ship_date'];
		$suplier = $_POST['suplier'];
		$ship_inv_no = $_POST['ship_inv_no'];
		$ship_inv_date = $_POST['ship_inv_date'];
		$ship_inv_dudate = $_POST['ship_inv_dudate'];

		$query="UPDATE `shipment_main_tmp` SET `invoice_no`='$ship_inv_no', `invoice_date`='$ship_inv_date', `invoice_due`='$ship_inv_dudate',`shipment_date`='$ship_date', `supplier`='$suplier' WHERE id = '$shipment_no'";

		$result=mysqli_query($conn,$query);
		if($result){
			return true;
		}else{
			$message = "Shipment header details could not be updated!";
			return false;
		}
	}
}

function addQty($shipment_num,$unic,$code,$qty,$c_price,$w_price,$r_price,$c_price2,$w_price2,$r_price2){

	global $message,$shipment_no,$action;
	$shipment_no = $shipment_num;
	$user=$_COOKIE['user'];
	$store=$_COOKIE['store'];
	$time_now=timeNow();
	$c_price1= preg_replace("/[^0-9.]/",'',$c_price);
	$w_price1= preg_replace("/[^0-9.]/",'',$w_price);
	$r_price1= preg_replace("/[^0-9.]/",'',$r_price);
	$unic_cal=unicCal();
	$qty_old=$new_stock_id=$iqt_id='';
	$unic_count=$duplicate=$qty_old=$qty_old_total=$qty0=0;
	$action='show_add_qty';
	$msg='Inventory could not be Updated!';
	if(($c_price1==$c_price2)&&($w_price1==$w_price2)&&($r_price1==$r_price2)) $temptable1=false; else $temptable1=true;
	if((($w_price1!=0)&&($w_price2==0))||(($r_price1!=0)&&($r_price2==0))) $temptable2=false; else $temptable2=true;
	//	if((($c_price1!=0)&&($c_price2==0))||(($w_price1!=0)&&($w_price2==0))||(($r_price1!=0)&&($r_price2==0))) $temptable2=false; else $temptable2=true;

	include('config.php');

	$result = mysqli_query($conn,"SELECT id,unic FROM inventory_items WHERE code='$code'");
	$row = mysqli_fetch_assoc($result);
	$inv_id=$row['id'];
	$unic0=$row['unic'];

	if($unic=='yes'){
		if(validateDeleteShipment($shipment_no,1)) $proceed=true; else{ $proceed=false; $msg='Error: The Shipment is LOCKED. Cannot Apend any items!'; }
	}else{
		$proceed=true;
	}

	if(((($unic0==0)||($unic=='yes'))) &&($proceed)){
		if($shipment_no==0){
			$query="SELECT MAX(shipment_no) FROM inventory_shipment";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_array($result)){
				$shipment_no=$row[0]+1;
			}
		}
		$query="SELECT id,qty FROM inventory_qty WHERE location='$store' AND `item`='$inv_id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$iqt_id=$row[0];
		$qty_old=$row[1];
		$qty_old_total=$row[1];
		$query="SELECT SUM(qty) FROM inventory_new WHERE store='$store' AND item='$inv_id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$qty_old_total+=$row[0];

		if($qty_old=='') $qty_old=0;
		if($qty_old_total=='') $qty_old_total=0;
		$query2="INSERT INTO `inventory_shipment` (`shipment_no`,`inv_item`,`inv_code`,`location`,`cost`,`old_qty`,`added_qty`,`added_by`,`time`) VALUES ('$shipment_no','$inv_id','$code','$store','$c_price1','$qty_old_total','$qty','$user','$time_now')";
		mysqli_query($conn,$query2);
		$lastid=mysqli_insert_id($conn);

		if($unic=='yes'){
			$action='show_add_unic';
			for($i=1;$i<=10;$i++){
				if($_REQUEST['sn'.$i]!=''){
					$sn=$_REQUEST['sn'.$i];
					$result = mysqli_query($conn,"SELECT count(id) as `count` FROM inventory_unic_item WHERE sn='$sn' AND `status`!=2");
					$row = mysqli_fetch_assoc($result);
					$unic_count=$row['count'];
					if($unic_count==0){
						$qty0++;
					}else{
						$duplicate++;
					}
				}
			}
			$qty=$qty0;
		}

		if($qty==0){
			mysqli_query($conn,"DELETE FROM `inventory_shipment` WHERE `id`='$lastid'");
		}

        $debug_id=debugStart($inv_id,$qty);

		if($qty>0){
			if($iqt_id=='') $temptable1=false;
			$qty_new=$qty_old+$qty;

			if(($unic_cal)&&($unic=='yes')){
				$qty=$qty_new=0;
			}
				if($temptable1){
					if($temptable2){
						if($qty_old==0){
							$query="UPDATE `inventory_qty` SET `w_price`='$w_price1',`r_price`='$r_price1',`c_price`='$c_price1',`qty`='$qty_new' WHERE `id`='$iqt_id'";
							$result2=mysqli_query($conn,$query);
						}else{
							$newtmp_id='';
							$query="SELECT id FROM inventory_new WHERE item='$inv_id' AND store='$store' AND `w_price`='$w_price1' AND `r_price`='$r_price1' AND `c_price`='$c_price1' LIMIT 1";
							$row=mysqli_fetch_row(mysqli_query($conn,$query));
							$newtmp_id=$row[0];
							if($newtmp_id!=''){
								$query="UPDATE `inventory_new` SET `qty`=qty+$qty, `shipment_no`=null WHERE id='$newtmp_id'";
								$result2=mysqli_query($conn,$query);
								$new_stock_id=$newtmp_id;
							}else{
								$query="INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`,`shipment_no`) VALUES ('$inv_id','$w_price1','$r_price1','$c_price1','$qty','$store','$shipment_no') ";
								$result2=mysqli_query($conn,$query);
								$new_stock_id=mysqli_insert_id($conn);
							}
						}
					}else{
						$result2=false;
						mysqli_query($conn,"DELETE FROM `inventory_shipment` WHERE `id`='$lastid'");
						$msg='If "Cost", "Wholesale Price", "Retail Price" was changed from 0<br />please update pricing from "EDIT ITEM"';
					}
				}else{
					if($iqt_id==''){
						$query="INSERT INTO `inventory_qty` (`item`,`location`,`w_price`,`r_price`,`c_price`,`qty`) VALUES ('$inv_id','$store','$w_price1','$r_price1','$c_price1','$qty') ";
						$result2=mysqli_query($conn,$query);
						$iqt_id=mysqli_insert_id($conn);
					}else{
						$query="UPDATE `inventory_qty` SET `qty`='$qty_new' WHERE `id`='$iqt_id'";
						$result2=mysqli_query($conn,$query);
					}
				}

		}else{
			$result2=false;
			if($duplicate>0)$msg='Error: Duplicated Unic ID Detected'; else $msg='Error: Item Quantity is 0';
		}

		if($result2){
			if($unic=='yes'){
				for($i=1;$i<=10;$i++){
					if($_REQUEST['sn'.$i]!=''){
						$sn=$_REQUEST['sn'.$i];
						$result = mysqli_query($conn,"SELECT count(id) as `count` FROM inventory_unic_item WHERE sn='$sn' AND `status`!=2");
						$row = mysqli_fetch_assoc($result);
						$unic_count=$row['count'];
						if($unic_count==0){
							if($unic_cal)
								$query0="INSERT INTO `inventory_unic_item` (`itq_id`,`shipment_no`,`inv_ship_id`,`sn`,`w_price`,`r_price`,`c_price`,`status`) VALUES ('$iqt_id','$shipment_no','$lastid','$sn','$w_price1','$r_price1','$c_price1','0') ";
							else
								$query0="INSERT INTO `inventory_unic_item` (`itq_id`,`shipment_no`,`inv_ship_id`,`sn`,`status`) VALUES ('$iqt_id','$shipment_no','$lastid','$sn','0') ";
							$result0=mysqli_query($conn,$query0);
						}
					}
				}
			}
			if($new_stock_id=='') $new_stock_id=0;
			if($unic=='yes') $qry7=",`added_qty`='$qty0'"; else $qry7="";
			$query2="UPDATE `inventory_shipment` SET `new_stock`='$new_stock_id' $qry7 WHERE `id`='$lastid'";
			$result2=mysqli_query($conn,$query2);
			debugEnd($debug_id,'success');
			if($duplicate>0)$message='Alert: Duplicated edit Item1 Unic ID Detected'; else $message='Inventory was Updated Successfully!';
			return true;
		}else{
			mysqli_query($conn,"DELETE FROM `inventory_shipment` WHERE `id`='$lastid'");
			debugEnd($debug_id,'fail');
			$message=$msg;
			return false;
		}
	}else{
		if(!$proceed) $message=$msg; else $message='For this Item, Qty Cannot be Added from Here';
		return false;
	}
}

// added by nirmal 21_08_23
function updateShipmentItemTmp(){
	global $message,$shipment_no;
	$itemid=$_GET['id'];
	$qty_new=$_GET['qty_new'];
	$result=false;

	include('config.php');
	$query="SELECT shipment_tmp_no  FROM shipment_item_tmp WHERE id='$itemid'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$shipment_no=$row[0];
	}

	if(($itemid != '') && ($qty_new != '')){
		$query2="UPDATE `shipment_item_tmp` SET `added_qty`='$qty_new' WHERE `id`=$itemid";
		$result2=mysqli_query($conn,$query2);
	}

	if($result2){
		$message='Item QTY was updated!';
		return true;
	}else{
		$message='Item could not be updated!';
		return false;
	}
}

function updateShipmentItem(){
	global $message,$shipment_no;
	$itemid=$_GET['id'];
	$qty_old=$_GET['qty_old'];
	$qty_new=$_GET['qty_new'];
	$result1=$result2=false;

	include('config.php');
		$query="SELECT ivq.id,ivq.qty,ins.shipment_no,ins.new_stock FROM inventory_shipment ins, inventory_qty ivq WHERE ins.inv_item=ivq.item AND ins.location=ivq.location AND ins.id='$itemid'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$ivq_id=$row[0];
			$ivq_qty=$row[1];
			$shipment_no=$row[2];
			$newstock_id=$row[3];
	}

	if($newstock_id==0){
		$new_ivq_qty=$ivq_qty-$qty_old+$qty_new;
		$query1="UPDATE `inventory_qty` SET `qty`='$new_ivq_qty' WHERE `id`=$ivq_id";
		$result1=mysqli_query($conn,$query1);
	}else{
		$result0 = mysqli_query($conn,"SELECT `qty` FROM inventory_new WHERE id='$newstock_id'");
		$row0 = mysqli_fetch_assoc($result0);
		$inq_qty=$row0['qty'];
		$new_inq_qty=$inq_qty-$qty_old+$qty_new;
		$query1="UPDATE `inventory_new` SET `qty`='$new_inq_qty' WHERE `id`='$newstock_id'";
		$result1=mysqli_query($conn,$query1);
	}

	if($result1){
		$query2="UPDATE `inventory_shipment` SET `added_qty`='$qty_new' WHERE `id`=$itemid";
		$result2=mysqli_query($conn,$query2);
	}

	if($result2){
		$message='Item QTY was Updated!';
		return true;
	}else{
		$message='Item could not be Updated!';
		return false;
	}
}

// added by nirmal 21_08_23
function removeShipmentQtyItemTmp(){
	global $message,$shipment_no;
	$itemid=$_GET['id'];
	$result = $result1 = false;

	include('config.php');

	$query="SELECT shipment_tmp_no  FROM shipment_item_tmp WHERE id='$itemid'";
	$result=mysqli_query($conn,$query);

	while($row=mysqli_fetch_array($result)){
		$shipment_no=$row[0];
	}

	if(($itemid != '') && ($shipment_no != '')){
		$query="DELETE FROM `shipment_item_tmp` WHERE `id` = '$itemid'";
		$result1=mysqli_query($conn,$query);
	}

	if($result1){
		$message='Item was removed from shipment!';
		return true;
	}else{
		$message='Item could not be removed!';
		return false;
	}
}

// added by nirmal 21_9_2
function removeUnicShipmentItemTmp(){
	global $message,$shipment_no;
	$itemid=$_GET['itm_id'];
	$user_id=$_COOKIE['user_id'];
	$message='Item was removed from shipment!';
	$out=true;

	include('config.php');

	$query="SELECT smt.id,smt.`added_by` FROM shipment_main_tmp smt, shipment_item_tmp sit WHERE smt.id=sit.shipment_tmp_no AND sit.id='$itemid'";
	$result=mysqli_query($conn,$query);
	$row=mysqli_fetch_array($result);
	$shipment_no=$row[0];
	$added_by=$row[1];
	if($added_by!=$user_id){ $message="Sorry! You don't have no authority to perform this action!"; $out=false; }

	if($out){
		$query="DELETE FROM `shipment_unic_tmp` WHERE `shipment_tmp_itm_id`='$itemid'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message="Error: SNs could not be Deleted" ;$out=false; }
	}
	if($out){
		$query="DELETE FROM `shipment_item_tmp` WHERE `id`='$itemid'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message="Error: Item could not be Deleted" ;$out=false; }
	}
	return $out;
}

function removeShipmentItem(){
	global $message,$shipment_no;
	$itemid=$_GET['id'];
	$qty_old=$_GET['qty_old'];
	$result1=$result2=false;

	include('config.php');
		$query="SELECT ivq.id,ivq.qty,ins.shipment_no,ins.new_stock FROM inventory_shipment ins, inventory_qty ivq WHERE ins.inv_item=ivq.item AND ins.location=ivq.location AND ins.id='$itemid'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$ivq_id=$row[0];
			$ivq_qty=$row[1];
			$shipment_no=$row[2];
			$newstock_id=$row[3];
		}

		if($newstock_id==0){
			$new_ivq_qty=$ivq_qty-$qty_old;
			$query1="UPDATE `inventory_qty` SET `qty`='$new_ivq_qty' WHERE `id`=$ivq_id";
			$result1=mysqli_query($conn,$query1);
		}else{
			$result0 = mysqli_query($conn,"SELECT `qty` FROM inventory_new WHERE id='$newstock_id'");
			$row0 = mysqli_fetch_assoc($result0);
			$inq_qty=$row0['qty'];
			if($inq_qty==$qty_old){
				$query1="DELETE FROM `inventory_new` WHERE `id` = '$newstock_id'";
				$result1=mysqli_query($conn,$query1);
			}else{
				$new_inq_qty=$inq_qty-$qty_old;
				$query1="UPDATE `inventory_new` SET `qty`='$new_inq_qty' WHERE `id`='$newstock_id'";
				$result1=mysqli_query($conn,$query1);
			}
		}

		if($result1){
			$query2="DELETE FROM `inventory_shipment` WHERE `id` = '$itemid'";
			$result2=mysqli_query($conn,$query2);
		}

		if($result2){
			$message='Item was Removed from Shipment!';
			return true;
		}else{
			$message='Item could not be Removed!';
			return false;
		}
}

// edit by nirmal 31_10_2023
function getOneItem($sub_system){
	global $unic_cal,$id,$code,$description,$po_description,$w_price,$r_price,$c_price,$min_w_rate,$max_w_rate,$max_r_rate,$itm_def_cost,$itm_def_price,$itm_pr_sr,$drawer,$qty,$commision,$category,$supplier,$stores_id,$itm_status,$itm_unic,$stores_name,$inv_type,$tb_id,$color1,$unit_type;
	$code0=$_REQUEST['code0'];
	$unic_cal=unicCal();
	$w_price=$r_price=$c_price=$drawer=$qty=$qty_new=array();
	$i=0;
	include('config.php');
	$query="SELECT inv.`id`,inv.`status`,inv.`category`,inv.`code`,inv.`description`,inv.`po_description`,inv.`min_w_rate`,inv.`max_w_rate`,inv.`max_r_rate`,inv.`commision`,inv.`unic`,inv.`default_cost`,inv.`default_price`,inv.`pr_sr`,inv.`supplier`,inv.`unit` FROM inventory_items inv WHERE inv.`code`='$code0'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$id=$row[0];
	$itm_status=$row[1];
	$category=$row[2];
	$code=$row[3];
	$description=$row[4];
	$po_description=$row[5];
	$min_w_rate=$row[6];
	$max_w_rate=$row[7];
	$max_r_rate=$row[8];
	$commision=$row[9];
	$itm_unic=$row[10];
	$itm_def_cost=$row[11];
	$itm_def_price=$row[12];
	$itm_pr_sr=$row[13];
	$supplier=$row[14];
	$unit_type=$row[15];

	$query="SELECT id,name FROM stores WHERE sub_system='$sub_system' AND status='1'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$stores_id_tmp=$row[0];
		$stores_id[$i]=$row[0];
		$stores_name[$i]=$row[1];
		$inv_type[$i]='normal';
		$tb_id[$i]='';
		$w_price[$i]=0;
		$r_price[$i]=0;
		$c_price[$i]=0;
		$drawer[$i]='';
		$qty[$i]=0;
		$color1[$i]='inherit';

		$query1="SELECT id,w_price,r_price,c_price,drawer_no,qty FROM inventory_qty WHERE location='$stores_id_tmp' AND item='$id'";
		$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
		$itq_id_tmp=$row1[0];
		$tb_id[$i]=$row1[0];
		$w_price[$i]=$row1[1];
		$r_price[$i]=$row1[2];
		$c_price[$i]=$row1[3];
		$drawer[$i]=$row1[4];
		$qty[$i]=$row1[5];

		if(($unic_cal)&&($itm_unic==1)){
			$query1="SELECT COUNT(id) FROM inventory_unic_item WHERE itq_id='$itq_id_tmp' AND `status`='0'";
			$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
			$qty[$i]=$row1[0];
		}
		$i++;

		$query1="SELECT id,w_price,r_price,c_price,qty FROM inventory_new WHERE `store`='$stores_id_tmp' AND item='$id'";
		$result1=mysqli_query($conn2,$query1);
		while($row1=mysqli_fetch_array($result1)){
			$stores_id[$i]=$row[0];
			$stores_name[$i]=$row[1].' (New)';
			$inv_type[$i]='new';
			$tb_id[$i]=$row1[0];
			$w_price[$i]=$row1[1];
			$r_price[$i]=$row1[2];
			$c_price[$i]=$row1[3];
			$drawer[$i]='';
			$qty[$i]=$row1[4];
			$color1[$i]='red';
			$i++;
		}
	}
}

// edit by nirmal 31_10_2023
function editItem1($sub_system){
	global $message,$code;
	$id=$_REQUEST['id'];
	$code=preg_replace('/[^A-Za-z0-9\-+.\ _]/', '',strtoupper($_REQUEST['code']));
	$description=preg_replace('/[^A-Za-z0-9\-+.\ _]/', '',$_REQUEST['description']);
	$po_description=preg_replace('/[^A-Za-z0-9\-+.\ _]/', '',$_REQUEST['po_description']);
	$min_w_rate=$_REQUEST['min_w_rate'];
	$max_w_rate=$_REQUEST['max_w_rate'];
	$max_r_rate=$_REQUEST['max_r_rate'];
	$def_cost=$_REQUEST['c_price'];
	$def_price=$_REQUEST['d_price'];
	$commision=$_REQUEST['commision'];
	$supplier=$_REQUEST['supplier'];
	$category=$_REQUEST['category'];
	if(isset($_REQUEST['item_st']) && $_REQUEST['item_st']=="on") $item_status=1;
	if(isset($_REQUEST['item_st']) && $_REQUEST['item_st']=="off") $item_status=0;
	if(isset($_REQUEST['item_unic']) && $_REQUEST['item_unic']=="yes") $item_unic=1;
	if(isset($_REQUEST['item_unic']) && $_REQUEST['item_unic']=="no") $item_unic=0;
	$unit = '';
	if(isset($_REQUEST['unit_type'])){
		$unit = $_REQUEST['unit_type'];
	}
	$result2=false;
	$qry_qty1=$qry_qty2=$qry_qty3='';
	$message='';
	$out=true;
	include('config.php');

	if(($code=='')&&($description=='')){ $out=false; $message='Description or Code cannot be Empty !'; }
	if($out){
		$query="SELECT code,description,po_description,pr_sr FROM inventory_items WHERE id='$id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$current_code=$row[0];
		$current_desc=$row[1];
		$current_podesc=$row[2];
		$pr_sr=$row[3];

		if($current_code!=$code){
			if(findDuplicateItem('code',$code)){
			$query="UPDATE `inventory_items` SET `code`='$code' WHERE `id`='$id'";
			$result1=mysqli_query($conn,$query);
			}else{ $out=false; $message='Duplicate Item Code detected. !'; }
		}
		if($current_desc!=$description){
			if(findDuplicateItem('description',$description)){
			$query="UPDATE `inventory_items` SET `description`='$description' WHERE `id`='$id'";
			$result1=mysqli_query($conn,$query);
			}else{ $out=false; $message='Duplicate Item Description detected. !'; }
		}
		if($current_podesc!=$po_description){
			if(findDuplicateItem('po_description',$po_description)){
			$query="UPDATE `inventory_items` SET `po_description`='$po_description' WHERE `id`='$id'";
			$result1=mysqli_query($conn,$query);
			}else{ $out=false; $message='Duplicate Item PO Description detected. !'; }
		}

		if($out){
				if($unit != ''){
					$query="UPDATE `inventory_items` SET `min_w_rate`='$min_w_rate',`max_w_rate`='$max_w_rate',`max_r_rate`='$max_r_rate',`default_cost`='$def_cost',`default_price`='$def_price',`commision`='$commision',`category`='$category',`supplier`='$supplier',`unic`='$item_unic',`status`='$item_status',`unit`='$unit' WHERE `id`='$id'";
				}else{
					$query="UPDATE `inventory_items` SET `min_w_rate`='$min_w_rate',`max_w_rate`='$max_w_rate',`max_r_rate`='$max_r_rate',`default_cost`='$def_cost',`default_price`='$def_price',`commision`='$commision',`category`='$category',`supplier`='$supplier',`unic`='$item_unic',`status`='$item_status',`unit`=NULL WHERE `id`='$id'";
				}

				$result1=mysqli_query($conn,$query);
				if($result1){
					$message='Item was Updated Successfully!';
				}else{
					$message='Item could not be Updated!';
					$out=false;
				}
		}
	}
	return $out;
}

function editItem2($sub_system){
	global $message;
	$message='Done';
	$master_pw=$_GET['master_pw'];
	$inv_type=$_GET['inv_type'];
	$tb_id=$_GET['tb_id'];
	$item_id=$_GET['item_id'];
	$stores_id=$_GET['stores_id'];
	$w_price=$_GET['w_price'];
	$r_price=$_GET['r_price'];
	$c_price=$_GET['c_price'];
	$qty=$_GET['qty'];
	$drawer=$_GET['drawer'];
	$out=true;
	$qty_qry="";
	$qty1=0;

	include('config.php');
	$query="SELECT `value` FROM settings WHERE setting='master_pw'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$master_pw0=$row[0];
	if($master_pw!=''){
		if($master_pw==$master_pw0){ $qty_qry=",`qty`='$qty'"; $qty1=$qty; }else{ $out=false;  $message='Invalid Password'; }
	}

	if($out){
		if($inv_type=='new'){
			if(($qty==0)&&($master_pw==$master_pw0)){
				$query="DELETE FROM `inventory_new` WHERE `id`='$tb_id'";
			}else{
				$query="UPDATE `inventory_new` SET `w_price`='$w_price',`r_price`='$r_price',`c_price`='$c_price' $qty_qry WHERE `id`='$tb_id'";
			}
		}elseif($inv_type=='normal'){
            $debug_id=debugStart($stores_id,$qty);
			if($tb_id==''){
				$query1="SELECT count(`id`) FROM inventory_qty WHERE item='$item_id' AND location='$stores_id'";
				$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
				if($row1[0]==1){ $out=false;  $message='Error1'; }else{
					$query="INSERT INTO `inventory_qty` (`item`,`location`,`w_price`,`r_price`,`c_price`,`drawer_no`,`qty`) VALUES ('$item_id','$stores_id','$w_price','$r_price','$c_price','$drawer','$qty1')";
				}
			}else{
				$query="UPDATE `inventory_qty` SET `w_price`='$w_price',`r_price`='$r_price',`c_price`='$c_price',`drawer_no`='$drawer' $qty_qry WHERE `id`='$tb_id'";
			}
		}
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false;  $message='Error'; }

		if(($result)&&($inv_type=='normal')){
			debugEnd($debug_id,'success');
		}else{
			debugEnd($debug_id,'fail');
		}
	}

	return $tb_id.'|'.$message;
}

// update by nirmal 22_11_2023
function getShipmentList($systemid,$sub_system){
	global $shipment_no,$salesman,$current_user,$date,$time,$active,$unic,$ship_store,$month,$store0,$shipment_edit_time,$ship_invoice_no;
	$user=$_COOKIE['user_id'];
	$month=$_GET['month'];
	$store0='all';
	$qry_st="";
	if($systemid==15){
		$shipment_edit_time=5;
	}else{
		$shipment_edit_time=1;
	}
	if(isset($_GET['store'])){
		$store0=$_GET['store'];
		if($store0!='all' && $store0!="") $qry_st="AND ins.location='$store0'";
	}
	$shipment_no=$store_name=$ship_invoice_no=array();
	if($month!=''){
		include('config.php');
		$result = mysqli_query($conn2,"SELECT `value` FROM settings WHERE setting='timezone'");
		$row = mysqli_fetch_assoc($result);
		$timezone=$row['value'];
		$time_now=time()+(60*60*$timezone);

		$store_name[""]="";
		$query="SELECT id,name FROM stores";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$store_name[$row[0]]=$row[1];
		}

		$query="SELECT ins.shipment_no,ins.added_by,date(ins.time),time(ins.time),ins.time,sm.unic,ins.location,sm.`invoice_no` FROM inventory_shipment ins LEFT JOIN shipment_main sm ON ins.shipment_no=sm.id WHERE sm.`status`!='3' AND sub_system='$sub_system' AND sm.shipment_date LIKE '$month%' $qry_st GROUP BY ins.shipment_no ORDER BY ins.shipment_no DESC";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$shipment_no[]=$row[0];
			$salesman[]=$row[1];
			$date[]=$row[2];
			$time[]=$row[3];
			$timestamp=strtotime($row[4]);
			$unic[]=$row[5];
			$ship_store[]=$store_name[$row[6]];
			$ship_invoice_no[]=$row[7];

			if(($time_now-$timestamp)<(3600*$shipment_edit_time)){
				if($row[5]==1){
					if(validateDeleteShipment($row[0],1))	$active[]=true; else $active[]=false;
				}else{
					$active[]=true;
				}
			}else{
				$active[]=false;
			}
		}
		$query1="SELECT `username` FROM userprofile WHERE `id`='$user'";
		$result1=mysqli_query($conn2,$query1);
		while($row1=mysqli_fetch_array($result1)){
			$current_user=$row1[0];
		}
	}
}

// added by nirmal 21_08_25
// update by nirmal 22_11_2023
function getTmpShipmentPendingList($sub_system){
	global $shipment_no_tmp,$salesman_tmp,$date_tmp,$unic_tmp,$ship_store_tmp,$edit_ship_tmp,$month_tmp,$invoice_no_tmp;
	$user_id=$_COOKIE['user_id'];
	$month_tmp=$_GET['month'];
	$shipment_no_tmp=$invoice_no_tmp=array();

	if($month_tmp!=''){
		include('config.php');
		$query="SELECT smt.`id`,up.`id`,up.`username`,smt.`shipment_date`,smt.`unic`,st.`name`,smt.`invoice_no` FROM shipment_main_tmp smt, userprofile up, stores st  WHERE smt.`added_by`=up.`id` AND smt.`store`=st.`id` AND smt.`sub_system`='$sub_system' AND smt.`shipment_date` LIKE '$month_tmp%' ORDER BY smt.`id` DESC";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$shipment_no_tmp[]=$row[0];
			$salesman_tmp[]=$row[2];
			$date_tmp[]=$row[3];
			$unic_tmp[]=$row[4];
			$ship_store_tmp[]=$row[5];
			$invoice_no_tmp[]=$row[6];
			if($user_id==$row[1]) $edit_ship_tmp[]=true; else $edit_ship_tmp[]=false;
		}
	}
}

function validateDeleteShipment($id,$case){
	include('config.php');
	$out=false;
	$query="SELECT unic FROM shipment_main WHERE id='$id'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$sm_unic=$row[0];

	if($sm_unic==1){
		$query="SELECT SUM(`status`) FROM inventory_unic_item WHERE shipment_no='$id'";
		$row=mysqli_fetch_row(mysqli_query($conn2,$query));
		if($row[0]==0) $out=true;
	}
	if($case==2){
		$query="SELECT `status` FROM shipment_main WHERE `id`='$id'";
		$row=mysqli_fetch_row(mysqli_query($conn2,$query));
		if($row[0]==2) $out=true; else 	$out=false;
	}
	return $out;
}

function oneShipment(){
	global $unicCal,$shipment_no,$ins_id,$ins_item,$ins_old_qty,$ins_added_qty,$ins_addedby,$ins_date,$ins_time,$ins_store,$ins_cost,$ins_cost_total,$itm_unic,$sm_date,$sm_supplier,$sm_invoice_no,$sm_invoice_date,$sm_invoice_duedate,$sm_unic,$sm_action,$sm_status;
	$shipment_no=$_REQUEST['shipment_no'];
	$unicCal=false;
	$ins_id=$ins_date=array();
	$sm_action='Locked';

	include('config.php');
	$query="SELECT su.name,sm.shipment_date,sm.invoice_no,sm.invoice_date,sm.invoice_due,sm.`unic`,sm.`status` FROM shipment_main sm, supplier su WHERE sm.`supplier`=su.id AND sm.id='$shipment_no'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$sm_supplier=$row[0];
	$sm_date=$row[1];
	$sm_invoice_no=$row[2];
	$sm_invoice_date=$row[3];
	$sm_invoice_duedate=$row[4];
	if($row[5]==1) $sm_unic='Unic Items'; else  $sm_unic='QTY Items';
	$sm_status=$row[6];
	if(($row[5]==1)&&(unicCal())) $unicCal=true;

	$query="SELECT ins.id,itm.description,ins.old_qty,ins.added_qty,ins.added_by,date(ins.time),time(ins.time),st.name,ins.cost,itm.unic FROM inventory_shipment ins, inventory_items itm, stores st WHERE ins.inv_item=itm.id AND ins.location=st.id AND ins.shipment_no='$shipment_no' ORDER BY ins.id";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$ins_id_tmp=$row[0];
		$ins_id[]=$row[0];
		$ins_item[]=$row[1];
		$ins_old_qty[]=$row[2];
		$ins_added_qty[]=$row[3];
		$ins_addedby[]=$row[4];
		$ins_date[]=$row[5];
		$ins_store[]=$row[7];
		$ins_cost[]=$row[8];
		$itm_unic[]=$row[9];

		if($unicCal){
			$query1="SELECT SUM(c_price) FROM inventory_unic_item WHERE inv_ship_id='$ins_id_tmp'";
			$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
			$ins_cost_total[]=$row1[0];
		}
	}
	$sm_action=validateDeleteShipment($shipment_no,1);
}

// updated by nirmal 06_11_2023
function oneShipmentExp(){
	$shipment_no=$_REQUEST['shipment_no'];
	$ins_date=$sh_itm_des=array();
	$user=$_COOKIE['user'];
	$inf_company=inf_company(1);
	$systemid=inf_systemid(1);
	$today=dateNow();
	include('config.php');
	$query="SELECT itm.description,ins.added_qty,ins.cost,inu.sn FROM inventory_shipment ins, inventory_items itm, inventory_unic_item inu WHERE ins.inv_item=itm.id AND ins.id=inu.inv_ship_id AND ins.shipment_no='$shipment_no' ORDER BY itm.description";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$sh_itm_des[]=$row[0];
		$sh_qty[]=$row[1];
		$sh_uprice[]=$row[2];
		$sh_total[]=$row[1]*$row[2];
		$sh_unic_id[]=$row[3];
	}
	require_once ('components/inventory/view/excel_one_shipment.php');
	//require_once ('plugin/PHPExcel-1.8/production/One_Shipment.php');
}

function getRepairPartMap(){
	global $item_id,$itm_desc,$repair_part_id,$repair_part_name,$rep_itm_id,$rep_itm_name;
	$item_id=$_GET['item'];
	include('config.php');
	$query="SELECT description FROM inventory_items WHERE id='$item_id'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$itm_desc=$row[0];

	$query="SELECT rm.id,rp.name FROM repair_parts_map rm, repair_parts rp WHERE rm.repair_part=rp.id AND rm.inv_item='$item_id'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$repair_part_id[]=$row[0];
		$repair_part_name[]=$row[1];
	}
	$query="SELECT id,name FROM repair_parts WHERE `status`=1";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$rep_itm_id[]=$row[0];
		$rep_itm_name[]=$row[1];
	}
}

function addRepairMap(){
	global $message;
	$item_id=$_GET['item_id'];
	$part_id=$_GET['part_id'];
	include('config.php');
	$query="INSERT INTO `repair_parts_map` (`inv_item`,`repair_part`) VALUES ('$item_id','$part_id')";
	$result=mysqli_query($conn,$query);
	if($result){
		$message='Repair Part was Mapped with Repair Job Successfully!';
		return true;
	}else{
		$message='Error: Repair Part could not be Mapped with Repair Job!';
		return false;
	}
}

function removeRepairMap(){
	global $message;
	$id=$_GET['id'];
	include('config.php');
	$query="DELETE FROM `repair_parts_map` WHERE id='$id'";
	$result=mysqli_query($conn,$query);
	if($result){
		$message='Repair Part Mapping was Removed Successfully!';
		return true;
	}else{
		$message='Error: Repair Part Mapping could not be Removed!';
		return false;
	}
}

// updated by nirmal 06_11_2023
function reorderRepairParts(){
	$store=$_COOKIE['store'];
	$user=$_COOKIE['user'];
	$inf_company=inf_company(1);
	$systemid=inf_systemid(1);
	$today=dateNow();
	include('config.php');
	$query="SELECT rp.name,ri.reorder_level-ri.qty,ri.reorder_qty FROM repair_parts_inventory ri, repair_parts rp WHERE rp.id=ri.part AND ri.qty<ri.reorder_level AND rp.`status`=1 AND ri.location='$store'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$rp_name[]=$row[0];
		if($row[2]>$row[1])	$ri_reorder_qty[]=$row[2]; else $ri_reorder_qty[]=$row[1];
	}
	require_once ('components/inventory/view/excel_repair_part_reorder.php');
	//require_once ('plugin/PHPExcel-1.8/production/Repairpart_Reorder.php');
}

function authDeleteShipment(){
	global $message,$shipment_no;
	$shipment_no=$_GET['shipment_no'];
	$out=true;
	$msg='Error: There war an error while deleting shipment!. Please contact NegoIT.';
	if(validateDeleteShipment($shipment_no,1)){
		include('config.php');
		$query="UPDATE shipment_main SET `status`='1' WHERE `id`='$shipment_no' AND `status`='0'";
		$result=mysqli_query($conn,$query);
		if($result){
			$message='Shipment was Submited for Deletion Successfully !';
			return true;
		}else{
			$message=$msg;
			return false;
		}
	}else{
		$message='You Cannot Delete a Locked Shipment!';
		return false;
	}
}

function deleteShipment(){
	global $message;
	$shipment_no=$_GET['shipment_no'];
	$user=$_COOKIE['user_id'];
	$time_now=timeNow();
	$out=true;
	$msg='Error: There war an error while deleting shipment!. Please contact NegoIT.';
	if(validateDeleteShipment($shipment_no,2)){
		include('config.php');
		$query="SELECT inv_ship_id,sn FROM inventory_unic_item WHERE shipment_no='$shipment_no'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$ins_id=$row[0];
			$sn=$row[1];
			$return=deleteUnic($ins_id,$sn);
			if(!$return){
				$out=false;
				break;
			}
		}
		if($out){
			$query2="UPDATE shipment_main SET `status`='3',`delete_by`='$user',`delete_date`='$time_now' WHERE `id`='$shipment_no'";
			$result2=mysqli_query($conn,$query2);
			if(!$result2) $out=false;
		}

		if($out){
			$message='Shipment was Deleted Successfully !';
			return true;
		}else{
			$message=$msg;
			return false;
		}
	}else{
		$message='You Cannot Delete a Locked or Unapproved Shipment!';
		return false;
	}
}
//---------------------------Special price ----------------------//

function getSpecialPrice($sub_system){
	global $sr_id,$sr_item,$sr_district,$sr_increment,$cr_id,$cr_category,$cr_district,$cr_increment;
	$sr_id=$cr_id=array();
	include('config.php');
	$query="SELECT sr.id,itm.description,sr.`district`,sr.increment FROM special_rate sr, inventory_items itm WHERE sr.item=itm.id AND sr.sub_system='$sub_system'";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$sr_id[]=$row[0];
			$sr_item[]=$row[1];
			$sr_district0=$row[2];
			$sr_increment[]=$row[3];
			if($sr_district0==0) $sr_district[]='All Island'; else{
				$query2="SELECT name FROM district WHERE `id`='$sr_district0'";
				$result2=mysqli_query($conn2,$query2);
				while($row2=mysqli_fetch_array($result2)){	$sr_district[]=$row2[0];	}
			}
		}
	$query="SELECT cr.id,ic.name,cr.`district`,cr.increment FROM category_rate cr, item_category ic WHERE cr.category=ic.id AND cr.sub_system='$sub_system'";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$cr_id[]=$row[0];
			$cr_category[]=$row[1];
			$cr_district0=$row[2];
			$cr_increment[]=$row[3];
			if($cr_district0==0) $cr_district[]='All Island'; else{
				$query2="SELECT name FROM district WHERE `id`='$cr_district0'";
				$result2=mysqli_query($conn2,$query2);
				while($row2=mysqli_fetch_array($result2)){	$cr_district[]=$row2[0];	}
			}
		}
}


function getDistrict(){
	global $district_id,$district_name;
	include('config.php');
	$district_id[]=0;
	$district_name[]='All Island';

		$query="SELECT id,name FROM district ORDER BY name";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$district_id[]=$row[0];
			$district_name[]=$row[1];
	}
}

function addSpecialPrice($sub_system){
	global $message;
	$itemdes=$_REQUEST['item'];
	$district=$_REQUEST['district'];
	$increment=$_REQUEST['increment'];
	$item='';
	include('config.php');

	$query0="SELECT id FROM inventory_items WHERE description='$itemdes'";
	$result0=mysqli_query($conn,$query0);
	while($row0=mysqli_fetch_array($result0)){	$item=$row0[0]; 	}

	$result1 = mysqli_query($conn,"SELECT count(id) as `count` FROM special_rate WHERE item='$item' AND district='$district'");
	$row1 = mysqli_fetch_assoc($result1);
	$count=$row1['count'];

	if($count==0){
		if($item!=''){
			$query="INSERT INTO `special_rate` (`item`,`district`,`increment`,`sub_system`) VALUES ('$item','$district','$increment','$sub_system')";
			$result=mysqli_query($conn,$query);
			if($result){
				$message='Item was Added Successfully!';
				return true;
			}else{
				$message='Item could not be Added!';
				return false;
			}
		}else{
			$message='Item was not Found';
			return false;
		}
	}else{
		$message='A Special Rate is Alredy Exist for Given Item & District';
		return false;
	}
}

function updateSpecialPrice(){
	global $message;
	$id=$_REQUEST['id'];
	$itemdes=$_REQUEST['item'];
	$district=$_REQUEST['district'];
	$increment=$_REQUEST['increment'];
	$item='';
	include('config.php');

	$query0="SELECT id FROM inventory_items WHERE description='$itemdes'";
	$result0=mysqli_query($conn,$query0);
	while($row0=mysqli_fetch_array($result0)){	$item=$row0[0]; 	}

	if($item!=''){
		$query="UPDATE `special_rate` SET `item`='$item', `district`='$district',`increment`='$increment'  WHERE `id`='$id'";
		$result=mysqli_query($conn,$query);
		if($result){
			$message='Item was Updated Successfully!';
			return true;
		}else{
			$message='Item could not be Updated!';
			return false;
		}
	}else{
		$message='Item was not Found';
		return false;
	}
}

function deleteSpecialPrice(){
	global $message;
	$id=$_REQUEST['id'];
	include('config.php');
		$query="DELETE FROM `special_rate` WHERE `id` = '$id'";
		$result=mysqli_query($conn,$query);
		if($result){
			$message='Spacial Rate was Removed Successfully!';
			return true;
		}else{
			$message='Spacial Rate could not be Removed!';
			return false;
		}
}


//---------------------------District price ----------------------//

function getDistrictPrice($sub_system){
	global $di_id,$di_name,$di_increment;
	include('config.php');
	$query="SELECT dt.id,dt.name,dr.increment FROM district dt LEFT JOIN (SELECT * FROM district_rate WHERE sub_system='$sub_system' ) dr ON dt.id=dr.`district` ORDER BY dt.id";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$di_id[]=$row[0];
			$di_name[]=$row[1];
			$di_increment[]=$row[2];
		}
}

function updateDistrictPrice($sub_system){
	global $message;
	$district=$_REQUEST['id'];
	$increment=$_REQUEST['increment'];
	include('config.php');
	$result= mysqli_query($conn,"SELECT count(id) FROM district_rate WHERE `district`='$district' AND `sub_system`='$sub_system'");
	$row= mysqli_fetch_row($result);
	$count=$row[0];

	if($count==0){
		$query2="INSERT INTO `district_rate` (`district`,`increment`,`sub_system`) VALUES ('$district','$increment','$sub_system')";
		$result2=mysqli_query($conn,$query2);
	}else{
		$query2="UPDATE `district_rate` SET `increment`='$increment'  WHERE `district`='$district' AND `sub_system`='$sub_system'";
		$result2=mysqli_query($conn,$query2);
	}
		if($result2){
			$message='District Increment was Updated Successfully!';
			return true;
		}else{
			$message='District Increment not be Updated!';
			return false;
		}
}

//---------------------------Temp Items ----------------------//


function getTempItem(){
	global $itt_id,$itt_itm,$itt_wprice,$itt_rprice,$itt_cprice,$itt_qty,$itt_store,$itt_shipment;
	$itt_id=array();
	include('config.php');
	$query="SELECT itt.id,itm.description,itt.w_price,itt.r_price,itt.c_price,itt.qty,st.name,itt.shipment_no FROM inventory_new itt, inventory_items itm, stores st WHERE itt.item=itm.id AND itt.store=st.id ORDER BY itt.id";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$itt_id[]=$row[0];
		$itt_itm[]=$row[1];
		$itt_wprice[]=$row[2];
		$itt_rprice[]=$row[3];
		$itt_cprice[]=$row[4];
		$itt_qty[]=$row[5];
		$itt_store[]=$row[6];
		$itt_shipment[]=$row[7];
	}
}
//------------------------Repair Items ----------------------------//
function addRepairInv(){
	global $message;
	$part=$_POST['part'];
	$qty=$_POST['qty'];
	$store=$_COOKIE['store'];
	$user=$_COOKIE['user_id'];
	$time_now=timeNow();
	$out=true;
	include('config.php');

	$result = mysqli_query($conn,"SELECT id FROM repair_parts WHERE name='$part'");
	$row = mysqli_fetch_assoc($result);
	$part_id=$row['id'];
	if($part_id==''){ $msg='Error: Invalid Item !'; $out=false; }
	if($out){
		$result = mysqli_query($conn,"SELECT `qty` FROM repair_parts_inventory WHERE `part`='$part_id' AND `location`='$store'");
		$row = mysqli_fetch_assoc($result);
		$old_qty=$row['qty'];
		if($old_qty=='') $old_qty=0;
		if($old_qty!=''){
			$query="UPDATE `repair_parts_inventory` SET `qty`=`qty`+$qty WHERE `part`='$part_id' AND `location`='$store'";
			$result=mysqli_query($conn,$query);
		}else{
			$query="INSERT INTO `repair_parts_inventory` (`part`,`location`,`qty`) VALUE ('$part_id','$store','$qty')";
			$result=mysqli_query($conn,$query);
		}
		if(!$result){ $msg='Error: Inventory Could Not Be Updated !'; $out=false; }
	}
	if($out){
		$query="INSERT INTO `repair_part_shipment` (`store`,`date`,`added_by`,`part`,`old_qty`,`added_qty`) VALUES ('$store','$time_now','$user','$part_id','$old_qty','$qty')";
		$result=mysqli_query($conn,$query);
		if(!$result){ $msg='Error: Inventory Updated But Shipment record was Failed !'; $out=false; }
	}

	if($out){
		$message='Repair Inventory was Updated Successfully!';
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function updateRepairInv(){
	global $message;
	$id=$_GET['id'];
	$cost=$_GET['cost'];
	$qty=$_GET['qty'];
	$reorder_level=$_GET['reorder_level'];
	$reorder_qty=$_GET['reorder_qty'];
	include('config.php');

	$query="UPDATE `repair_parts_inventory` SET `c_price`='$cost',`qty`='$qty',`reorder_level`='$reorder_level',`reorder_qty`='$reorder_qty' WHERE `id`='$id'";
	$result=mysqli_query($conn,$query);
	if($result){
		$message='Done';
		return true;
	}else{
		$message='Error';
		return false;
	}
}

function getParts(){
	global $part_id,$part_name,$part_drawer;
	$part_id=$part_name=$part_drawer=array();
	include('config.php');
	$query="SELECT id,name,drawer_no FROM repair_parts WHERE `status`=1 ORDER BY name";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$part_id[]=$row[0];
		$part_name[]=$row[1];
		$part_drawer[]=$row[2];
	}
}

function getDisParts(){
	global $dispart_id,$dispart_name,$dispart_drawer;
	$dispart_id=$dispart_name=$dispart_drawer=array();
	include('config.php');
	$query="SELECT id,name,drawer_no FROM repair_parts WHERE `status`=0 ORDER BY name";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$dispart_id[]=$row[0];
		$dispart_name[]=$row[1];
		$dispart_drawer[]=$row[1];
	}
}

function addRepairPart(){
	global $message;
	$name=$_GET['name'];
	$cost=$_GET['cost'];
	$drawer=$_GET['drawer'];
	$store=$_COOKIE['store'];
	$out=true;
	include('config.php');

	$result = mysqli_query($conn,"SELECT count(id) as `count` FROM repair_parts WHERE name='$name'");
	$row = mysqli_fetch_assoc($result);
	$count=$row['count'];

	if($count==0){
		$query="INSERT INTO `repair_parts` (`name`,`drawer_no`,`status`) VALUES ('$name','$drawer','1')";
		$result=mysqli_query($conn,$query);
		$part_id=mysqli_insert_id($conn);
		if($result){
			$query="INSERT INTO `repair_parts_inventory` (`part`,`location`,`qty`,`reorder_level`,`reorder_qty`,`c_price`) VALUES ('$part_id','$store','0','0','0','$cost')";
			$result=mysqli_query($conn,$query);
			if(!$result){ $msg='Error: Part Name Could Not Be Added !'; $out=false; }
		}else{ $msg='Error: Part Name Could Not Be Added !'; $out=false; }
	}else{ $msg='Error: Duplicated Name Found !'; $out=false; }
	if($out){
		$message='Part Name was Added Successfully!';
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function updateRepairPart(){
	global $message;
	$id=$_GET['id'];
	$new_name=$_GET['new_name'];
	$new_drawer=$_GET['new_drawer'];
	$out=true;
	include('config.php');

	$result = mysqli_query($conn,"SELECT name FROM repair_parts WHERE id='$id'");
	$row = mysqli_fetch_assoc($result);
	$old_name=$row['name'];
	$result = mysqli_query($conn,"SELECT count(id) as `count` FROM repair_parts WHERE name='$new_name'");
	$row = mysqli_fetch_assoc($result);
	$count=$row['count'];
	if($old_name==$new_name) $count=0;

	if($count==0){
		$query="UPDATE `repair_parts` SET `name`='$new_name',`drawer_no`='$new_drawer' WHERE `id`='$id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $msg='Error: Part Name Could Not Be Updated !'; $out=false; }
	}else{ $msg='Error: Duplicated Name Found !'; $out=false; }
	if($out){
		$message='Done';
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function deleteRepairPart(){
	global $message;
	$id=$_GET['id'];
	$out=true;
	$msg='';
	include('config.php');

	$result = mysqli_query($conn,"SELECT count(id) as `count` FROM repair_invoice WHERE parts='$id'");
	$row = mysqli_fetch_assoc($result);
	$count=$row['count'];
	$result = mysqli_query($conn,"SELECT qty FROM repair_parts_inventory WHERE part='$id'");
	$row = mysqli_fetch_assoc($result);
	if($row['qty']>0){ $msg='Quantity Exists'; $out=false; }

	if($out){
		if($count==0){
			$query="DELETE FROM `repair_parts_map` WHERE `repair_part`='$id'";
			$result=mysqli_query($conn,$query);
			$query="DELETE FROM `repair_parts_inventory` WHERE `part`='$id'";
			$result=mysqli_query($conn,$query);
			$query="DELETE FROM `repair_parts` WHERE `id`='$id'";
			$result=mysqli_query($conn,$query);
			if($result){ $msg='Deleted'; }else{ $msg='Error'; $out=false; }
		}else{
			$query="UPDATE `repair_parts` SET `status`='0' WHERE `id`='$id'";
			$result=mysqli_query($conn,$query);
			if($result){ $msg='Disabled'; }else{ $msg='Error'; $out=false; }
		}
	}
	if($out){
		$message=$msg;
		return true;
	}else{
		$message=$msg;
		return false;
	}
}


function enableRepairPart(){
	global $message;
	$id=$_GET['id'];
	$out=true;
	include('config.php');

	$query="UPDATE `repair_parts` SET `status`='1' WHERE `id`='$id'";
	$result=mysqli_query($conn,$query);
	if(!$result){ $msg='Error: Part Could Not Be Enabled !'; $out=false; }
	if($out){
		$message='Part was Enabled Successfully!';
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function drawerSearch(){
	global $drawer,$store,$report_date,$dr_no,$dr_item,$dr_qty,$dr_category;
	$store=$_GET['st'];
	$report_date=dateNow();
	$dr_no=array();
	if(isset($_GET['drawer'])){
		if($_GET['drawer']!=''){
			$drawer=$_GET['drawer'];
			include('config.php');
			$query="SELECT itq.drawer_no,itm.description,itq.qty,itc.name FROM inventory_items itm, inventory_qty itq, item_category itc WHERE itm.id=itq.item AND itm.category=itc.id AND itq.location='$store' AND itq.drawer_no LIKE '%$drawer%' ORDER BY itq.drawer_no";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$dr_no[]=$row[0];
				$dr_item[]=$row[1];
				$dr_qty[]=$row[2];
				$dr_category[]=$row[3];
			}
		}
	}
}

//------------------------------------------------------------------TAGS--------------------------------------------------------------------------//

function getTags2(){
	global $tag_id,$tag_name,$tag_profit,$tag_licount;
	$tag_id=$tag_name=array();
	include('config.php');
	$query="SELECT tn.id,tn.tag,tn.min_profit,COUNT(ta.id) FROM tag_name tn LEFT JOIN tag_assignment ta ON tn.id=ta.tag GROUP BY tn.id ORDER BY tn.tag";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$tag_id[]=$row[0];
		$tag_name[]=$row[1];
		$tag_profit[]=$row[2];
		$tag_licount[]=$row[3];
	}
}

function getOneTag(){
global $tag_name_one,$tag_profit_one,$tag_item_id,$tag_item_desc;
	$tag_item_id=$tag_item_desc=array();
	$tag_profit_one=0;
	if(isset($_GET['tag_id'])){
		$tag_id=$_GET['tag_id'];
		include('config.php');
		$query="SELECT tag,min_profit FROM tag_name WHERE `id`='$tag_id'";
		$row=mysqli_fetch_row(mysqli_query($conn2,$query));
		$tag_name_one=$row[0];
		$tag_profit_one=$row[1];

		$query="SELECT itm.id,itm.description FROM tag_assignment ta, inventory_items itm WHERE ta.item=itm.id AND ta.tag='$tag_id'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$tag_item_id[]=$row[0];
			$tag_item_desc[]=$row[1];
		}
	}
}

// updated by nirmal 21_10_5
function addTag($item_id,$tag_name){
	$out=true;
	$msg='Done';
	include('config.php');
	// added by nirmal 21_10_5
	$query="SELECT id,min_profit,COUNT(id) FROM tag_name WHERE `tag`='$tag_name'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$tag_id=$row[0];
	if($row[2]!=1){ $out=false; $msg='Error: Tag was not found'; }
	if($out){
		if($row[1] > 0){
			$query="SELECT COUNT(ta.`id`) FROM tag_assignment ta, tag_name tn WHERE ta.`tag`=tn.`id` AND tn.`min_profit`>0 AND ta.`item` ='$item_id'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			if($row[0] > 0){$out=false; $msg='Error: You can only add one price tag to an item';}
		}
	}
	if($out){
		$query="SELECT COUNT(id) FROM tag_assignment WHERE `item`='$item_id' AND `tag`='$tag_id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$count=$row[0];
		if($count==1){ $out=false; $msg='Error: Tag is already assigned'; }
		if($count>1){
			$query="DELETE FROM `tag_assignment` WHERE `item`='$item_id' AND `tag`='$tag_id'";
			$result=mysqli_query($conn,$query);
		}
	}

	if($out){
		$query="INSERT INTO `tag_assignment` (`item`,`tag`) VALUES ('$item_id','$tag_id')";
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false; $msg='Error: Tag Could Not be Added'; }
	}
	return $msg;
}

function createTag($tag_name,$tag_profit){
	$out=true;
	$msg='Done';
	if($tag_name==''){ $out=false; $msg='Error: Tag Name cannot be Empty'; }

	include('config.php');
	$query="SELECT COUNT(id) FROM tag_name WHERE `tag`='$tag_name'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($row[0]!=0){ $out=false; $msg='Error: Tag is already exists'; }
	if($out){
		$query="INSERT INTO `tag_name` (`tag`,`min_profit`) VALUES ('$tag_name','$tag_profit')";
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false; $msg='Error: Tag Could Not be Created'; }
	}
	$jasonArray["status"]=$msg;
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

function createAddTag($item_id,$tag_name){
	$out=true;
	$msg='Done';
	include('config.php');
	$query="SELECT COUNT(id) FROM tag_name WHERE `tag`='$tag_name'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($row[0]==0){
		if($out){
			$json_array=json_decode(createTag($tag_name,0));
			$msg=$json_array->{"status"};
		}
		if($out){
			$msg=addTag($item_id,$tag_name);
		}
	}else{
		$msg=addTag($item_id,$tag_name);
	}
	$jasonArray["status"]=$msg;
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

function removeTag($item_id,$tag_id){
	$msg='Done';
	$jasonArray=array();
	include('config.php');
	$query="SELECT tag FROM tag_name WHERE `id`='$tag_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$jasonArray["tag_name"]=$row[0];

	$query="DELETE FROM `tag_assignment` WHERE `item`='$item_id' AND `tag`='$tag_id'";
	$result=mysqli_query($conn,$query);
	if(!$result){ $out=false; $msg='Error: Tag Could Not be Removed'; }

	$jasonArray["status"]=$msg;
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

function deleteTag($tag_id){
global $message;
	$out=true;
	include('config.php');
	$query="SELECT tag FROM tag_name WHERE `id`='$tag_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$tag_name=$row[0];
	$message='TAG "'.$tag_name.'" was Deleted successfully';

	$query="SELECT COUNT(id) FROM tag_assignment WHERE tag='$tag_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($row[0]>0){ $out=false; $message='Please remove all associated items from this Tag before deletion'; }

	if($out){
		$query="DELETE FROM `tag_name` WHERE `id`='$tag_id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false; $message='Error: Tag Could Not be Deleted'; }
	}

	return $out;
}

function validateUpdatingTag($tag_id,$tag_profit){
	$msg='Ok';
	$out=true;
	include('config.php');
	if(!is_numeric($tag_profit)){
	 	$msg='Error: Please enter only numbers';
		$out=false;
	}
	if($out){
		if($tag_profit > 0){
			$query="SELECT COUNT(ta.`id`),tn.`id` FROM tag_assignment ta, tag_name tn WHERE ta.`tag`=tn.`id` AND tn.`min_profit`>0 AND ta.`item` IN (SELECT ta.`item` FROM tag_assignment ta, tag_name tn WHERE ta.`tag`=tn.`id` AND tn.`id`='$tag_id')";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			if($row[0] != 0) {
				$query = "SELECT min_profit FROM tag_name WHERE `id`='$row[1]'";
				if($row[1] != $tag_id){
					$msg='Error: Tag price cannot be updated, There are items exists with tags, which has prices';
				}
			}
		}
	}
	return $msg;
}

function updateTag($tag_id,$tag_name,$tag_profit){
	$out=true;
	$msg='Done';
	include('config.php');
	$query="SELECT COUNT(id) FROM tag_name WHERE `tag`='$tag_name' AND `id`!='$tag_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($row[0]!=0){ $out=false; $msg='Error: Tag is already exists'; }
	if($out){
		$query="UPDATE `tag_name` SET `tag`='$tag_name',`min_profit`='$tag_profit' WHERE id='$tag_id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false; $msg='Error: Tag Could Not be Updated'; }
	}
	return $msg;
}

function showItemTags($item_id){
	$jasonArray=array();
	include('config.php');
	$query="SELECT tn.id,tn.tag FROM tag_name tn, tag_assignment ta WHERE tn.id=ta.tag AND ta.item='$item_id'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
        $jasonArray[] = array(
            "tag_id" => $row[0],
            "tag_name"   => $row[1]
        );
	}
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

function searchItems($sub_system){
	$category=$_POST['category'];
	$key_word=$_POST['key_word'];
	$jasonArray=array();
	include('config.php');
	$query="SELECT id,description FROM inventory_items WHERE `sub_system`='$sub_system' AND category='$category' AND description LIKE '%$key_word%' AND `status`='1'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
        $jasonArray[] = array(
            "id" => $row[0],
            "desc"   => $row[1]
        );
	}
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

// added by nirmal 21_10_1'
function validateInsertingTags(){
	$jsonArray=array();
	$item_id_selected=$_POST['item_id_selected'];
	$selected_tag=$_POST['selected_tag'];
	$item_arr=explode(",",$item_id_selected);
	include('config.php');

	$query="SELECT COUNT(id),id,min_profit FROM tag_name WHERE `tag`='$selected_tag'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));

	for($i=0;$i<sizeof($item_arr);$i++){
		if($row[2] > 0){
			$query="SELECT COUNT(*) FROM tag_assignment ta, tag_name tn WHERE ta.tag=tn.id AND tn.min_profit>0 AND ta.item ='$item_arr[$i]'";
			$row1=mysqli_fetch_row(mysqli_query($conn,$query));
			$jsonArray[] = array(
	            "item_id" => $item_arr[$i],
	            "tag_count"   => $row1[0]
	        );
		}else{
			$jsonArray[] = array(
	            "item_id" => $item_arr[$i],
	            "tag_count"   => 0
	        );
		}
	}

	$myJSON = json_encode($jsonArray);
	return $myJSON;
}

function applyBulkTag(){
	$selected_tag=$_POST['selected_tag'];
	$item_id_selected=$_POST['item_id_selected'];
	$item_arr=explode(",",$item_id_selected);
	$out=true;
	$msg='Done';
	include('config.php');
	$query="SELECT COUNT(id),id FROM tag_name WHERE `tag`='$selected_tag'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($row[0]!=1){ $out=false; $msg='Error: Tag not found'; }
	$tag_id=$row[1];
	if($out){
		for($i=0;$i<sizeof($item_arr);$i++){
			if($out){
				$query="SELECT COUNT(id) FROM tag_assignment WHERE `item`='$item_arr[$i]' AND `tag`='$tag_id'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				if($row[0]==0){
					$query="INSERT INTO `tag_assignment` (`item`,`tag`) VALUES ('$item_arr[$i]','$tag_id')";
					$result=mysqli_query($conn,$query);
					if(!$result){ $out=false; $msg='Error: Tag Could Not be Added'; }
				}
			}
		}
	}
	return $msg;
}
?>