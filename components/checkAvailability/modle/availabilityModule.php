<?php
function getDistrict(){
global $district_id,$district_name,$current_district, $conn2;
	include('config.php');
		$query="SELECT id,name FROM district ORDER BY name";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$district_id[]=$row[0];
			$district_name[]=$row[1];
	}
	if(isset($_COOKIE['district']))
		$current_district=$_COOKIE['district'];
	else
		$current_district='';
}


function setDistrict(){
	$district=$_GET['id'];
	setcookie("district",$district, time()+3600*10);
}

// update by nirmal 01_11_2023
function getCategory(){
	global $category_id,$category_name,$conn2;
	$sub_system = $_COOKIE['sub_system'];
	include('config.php');
	$query = "SELECT `id`,`name` FROM item_category WHERE `sub_system`='$sub_system' OR `sub_system`='all'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$category_id[]=$row[0];
		$category_name[]=$row[1];
	}
}

function getCust($status,$sub_system){
	global $customer_id,$customer_name,$conn2;
	include('config.php');
		$query="SELECT cu.id,cu.name FROM cust cu, userprofile up WHERE cu.associated_salesman=up.id AND cu.`status` IN ($status) AND cu.sub_system='$sub_system'";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$customer_id[]=$row[0];
			$customer_name[]=$row[1];
	}
}

// update by nirmal 16_10_2023
// update by nirmal 2024_07_14 (for sysid 24, show only logged user store item category related stores and store's qty)
// update by nirmal 2024_07_30 (for get unic items sets group by prices)
function getItems($sub_system,$systemid){ // category dropdown select results
	global $id,$code,$description,$w_price,$r_price,$cost,$drawer,$qty,$cat_id,$cat_name,$sto_name,$code_uni,$description_uni,$decimal,$conn2;
	$district=$_COOKIE['district'];
	$user_id=$_COOKIE['user_id'];
	$store=$_COOKIE['store'];
	$unic_cal=unicCal();
	$increment='';
	$decimal=0;
	$systemid=inf_systemid(1);
	include('config.php');
	$id=array();
	$decimal=getDecimalPlaces(1);
	$code=$description=array();
	$sp_item=$sp_increment=$sp_category=$sp_catincrement=array();

	$result = mysqli_query($conn2,"SELECT mapped_inventory FROM userprofile WHERE id='$user_id'");
	$row = mysqli_fetch_assoc($result);
	$mapped_inventory=$row['mapped_inventory'];

	$query="SELECT gp.`group` FROM userprofile up, store_group gp WHERE up.store=gp.store AND up.id='$user_id'";
	$result = mysqli_query($conn2,$query);
	$row = mysqli_fetch_assoc($result);
	$group=$row['group'];

	$store_filter='';
	$subsystem_qry = '';
	$group_table = ",store_group gp";
	$group_qry = "AND st.id=gp.store AND gp.`group`='$group'";
	if($systemid == 24){
		$store_filter = "AND itq.`location` ='$store'";
		$subsystem_qry = 'AND itm.sub_system = '.$sub_system;

		if(isset($_COOKIE['manager'])){
			$subsystem_qry = 'AND itm.sub_system = '.$sub_system;
			$store_filter = '';
		}
		if(isset($_COOKIE['top_manager'])){
			$store_filter='';
			$subsystem_qry = '';
			$group_qry='';
			$group_table='';
		}
	}

	if($systemid == 13){
		$subsystem_qry = 'AND itm.sub_system = '.$sub_system;
	}

	if(isset($_GET['category'])){
		$category_req=$_REQUEST['category'];
		if($category_req=='all') $category_qry=''; else $category_qry='ca.id='.$category_req.' AND';
		if($category_req=='off') $category_qry='';
	}else{
		$category_qry='';
	}

	$query="SELECT increment FROM district_rate WHERE `district`='$district' AND `sub_system`='$sub_system'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){	$increment=(100+$row[0])/100; }
	if($increment=='') $increment=1;
	$query1="SELECT item,increment FROM special_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system'";
	$result1=mysqli_query($conn2,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$sp_item[]=$row1[0];
		$sp_increment[]=$row1[1];
	}
	$query1="SELECT category,increment FROM category_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system'";
	$result1=mysqli_query($conn2,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$sp_category[]=$row1[0];
		$sp_catincrement[]=$row1[1];
	}

	$query="SELECT itm.id,itm.code,itm.description,itm.default_price,itc.id,itc.`name`,SUM(if(rpi.qty>0, 1, 100000)) FROM inventory_items itm, item_category itc, repair_parts_map rm, repair_parts_inventory rpi, repair_parts rp WHERE rm.repair_part=rpi.part AND itm.id=rm.inv_item AND itm.category=itc.id AND rp.id=rpi.part AND rp.id=rm.repair_part AND rpi.location='$store' AND itm.pr_sr=3 AND itm.`status`=1 AND rp.`status`=1 $subsystem_qry GROUP BY itm.id";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
			$id[]=$row[0];
			$code[]=$row[1];
			$description[]=$row[2];
			$cost[]='';
			$drawer[]='';
			if($row[6]<100000) $qty[]='Available'; else $qty[]='-';
			$cat_id[]=$row[4];
			$cat_name[]=$row[5];
			$sto_name[]='Current Store';
			$w_price[]=$row[3];
			$r_price[]=$row[3];
	}

	if($mapped_inventory==0){
		$query="SELECT DISTINCT itm.id,itm.code,itm.description,itq.w_price,itq.r_price,itq.c_price,itq.drawer_no,itq.qty,ca.id,ca.name,st.name,itm.id,itm.unic,itq.id FROM inventory_items itm, inventory_qty itq, stores st, item_category ca $group_table WHERE itm.`id`=itq.`item` AND st.`id`=itq.location $group_qry AND itm.`status`=1  AND $category_qry itm.`category`=ca.id $store_filter";
	}else{
		$query="SELECT DISTINCT itm.id,itm.code,itm.description,itq.w_price,itq.r_price,itq.c_price,itq.drawer_no,itq.qty,ca.id,ca.name,st.name,itm.id,itm.unic,itq.id FROM inventory_items itm, inventory_qty itq, stores st, item_category ca WHERE itm.`id`=itq.`item` AND st.`id`=itq.location AND itq.location='$mapped_inventory' AND itm.`status`=1 AND $category_qry itm.`category`=ca.id $subsystem_qry";
	}
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
			$no_specialrate=$no_catspecialrate=true;
			$itm_id_tmp=$row[11];
			$itm_cat_tmp=$row[8];
			$unic=$row[12];
			$itq_id=$row[13];
			if(($unic_cal)&&($unic==1)){
				// old code
				// $query1="SELECT COUNT(id),SUM(w_price),SUM(r_price) FROM inventory_unic_item WHERE itq_id='$itq_id' AND `status`='0'";
				// $row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
				// $qty[]=$row1[0];
				// if($row1[0]>0) $wprice=$row1[1]/$row1[0]; else $wprice=0;
				// if($row1[0]>0) $rprice=$row1[2]/$row1[0]; else $rprice=0;

				// $query3 = "SELECT COUNT(iui.id),iui.w_price, iui.r_price, iui.c_price
				// FROM inventory_unic_item iui WHERE iui.itq_id = '$itq_id' AND iui.status = '0'
				// GROUP BY iui.w_price, iui.r_price";
				$query3 = "SELECT COUNT(iui.id), iui.w_price, iui.r_price, iui.c_price
           FROM inventory_unic_item iui 
           WHERE iui.itq_id = '$itq_id' AND iui.status = '0'
           GROUP BY iui.w_price, iui.r_price, iui.c_price";
				$result3=mysqli_query($conn2,$query3);
				while($row3=mysqli_fetch_array($result3)){
					$id[]=$row[0];
					$code[]=$row[1];
					$description[]=$row[2];
					$cost[]=$row[5];
					$drawer[]=$row[6];
					$cat_id[]=$row[8];
					$cat_name[]=$row[9];
					$sto_name[]=$row[10];

					$qty[]=$row3[0];
					$wprice=$row3[1];
					$rprice=$row3[2];

					for($i=0;$i<sizeof($sp_item);$i++){
						if($sp_item[$i]==$row[0]){
							$no_specialrate=false;
							$w_price[]=round($wprice*((100+$sp_increment[$i])/100),$decimal);
							$r_price[]=round($rprice*((100+$sp_increment[$i])/100),$decimal);
						}
					}
					if($no_specialrate){
						for($i=0;$i<sizeof($sp_category);$i++){
							if($sp_category[$i]==$row[8]){
								$no_catspecialrate=false;
								$w_price[]=round($wprice*((100+$sp_catincrement[$i])/100),$decimal);
								$r_price[]=round($rprice*((100+$sp_catincrement[$i])/100),$decimal);
							}
						}
						if($no_catspecialrate){
							$w_price[]=round(($wprice*$increment),$decimal);
							$r_price[]=round(($rprice*$increment),$decimal);
						}
					}
				}
			}else{
				$id[]=$row[0];
				$code[]=$row[1];
				$description[]=$row[2];
				$cost[]=$row[5];
				$drawer[]=$row[6];
				$cat_id[]=$row[8];
				$cat_name[]=$row[9];
				$sto_name[]=$row[10];

				$wprice=$row[3];
				$rprice=$row[4];
				$qty[]=$row[7];

				for($i=0;$i<sizeof($sp_item);$i++){
					if($sp_item[$i]==$row[0]){
						$no_specialrate=false;
						$w_price[]=round($wprice*((100+$sp_increment[$i])/100),$decimal);
						$r_price[]=round($rprice*((100+$sp_increment[$i])/100),$decimal);
					}
				}
				if($no_specialrate){
					for($i=0;$i<sizeof($sp_category);$i++){
						if($sp_category[$i]==$row[8]){
							$no_catspecialrate=false;
							$w_price[]=round($wprice*((100+$sp_catincrement[$i])/100),$decimal);
							$r_price[]=round($rprice*((100+$sp_catincrement[$i])/100),$decimal);
						}
					}
					if($no_catspecialrate){
						$w_price[]=round(($wprice*$increment),$decimal);
						$r_price[]=round(($rprice*$increment),$decimal);
					}
				}
			}
	}

	if($mapped_inventory==0)
		$query="SELECT itm.id,itm.code,itm.description,itn.w_price,itn.r_price,itn.c_price,itm.id,itn.qty,ca.id,ca.name,st.name,itm.id FROM inventory_items itm, inventory_new itn, stores st, store_group gp, item_category ca WHERE itm.`id`=itn.`item` AND st.`id`=itn.store AND st.id=gp.store AND itm.`status`=1 AND gp.`group`='$group' AND $category_qry itm.`category`=ca.id $subsystem_qry";
	else
		$query="SELECT itm.id,itm.code,itm.description,itn.w_price,itn.r_price,itn.c_price,itm.id,itn.qty,ca.id,ca.name,st.name,itm.id FROM inventory_items itm, inventory_new itn, stores st, item_category ca WHERE itm.`id`=itn.`item` AND st.`id`=itn.store AND itn.store='$mapped_inventory' AND itm.`status`=1 AND $category_qry itm.`category`=ca.id $subsystem_qry";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$no_specialrate=$no_catspecialrate=true;
			$id[]=$row[0];
			$code[]=$row[1];
			$description[]=$row[2].' (New)';
			$cost[]=$row[5];
			$drawer[]=0;
			$qty[]=$row[7];
			$cat_id[]=$row[8];
			$cat_name[]=$row[9];
			$sto_name[]=$row[10];
			$itm_id_tmp=$row[11];
			$itm_cat_tmp=$row[8];
			$wprice=$row[3];
			$rprice=$row[4];

		for($i=0;$i<sizeof($sp_item);$i++){
			if($sp_item[$i]==$row[0]){
				$no_specialrate=false;
				$w_price[]=round($wprice*((100+$sp_increment[$i])/100),$decimal);
				$r_price[]=round($rprice*((100+$sp_increment[$i])/100),$decimal);
			}
		}
		if($no_specialrate){
				for($i=0;$i<sizeof($sp_category);$i++){
					if($sp_category[$i]==$row[8]){
						$no_catspecialrate=false;
						$w_price[]=round($wprice*((100+$sp_catincrement[$i])/100),$decimal);
						$r_price[]=round($rprice*((100+$sp_catincrement[$i])/100),$decimal);
					}
				}
				if($no_catspecialrate){
					$w_price[]=round(($wprice*$increment),$decimal);
					$r_price[]=round(($rprice*$increment),$decimal	);
				}
		}
	}

	$code_uni=array_values(array_unique($code));
	$description_uni=array_values(array_unique($description));
}

// update by nirmal 2024_07_14 (for sysid==24 show only logged user store item qty, which omits subsystem store with their qty)
// function checkItem($sub_system){
// 	global $store_name,$item_w_price,$item_r_price,$item_qty,$search_code,$search_description,$item_dr,$decimal;
// 	$sp_item=$sp_increment=$sp_category=$sp_catincrement=$store_name=array();
// 	$user_id=$_COOKIE['user_id'];
// 	$district=$_COOKIE['district'];
// 	$unic_cal=unicCal();
// 	$increment=$store=$subsystem_qry=$store_filter='';
// 	$systemid=inf_systemid(1);
// 	if(isset($_REQUEST['code'])) $search_code=$_REQUEST['code']; else $search_code='';
// 	if(isset($_REQUEST['description'])) $search_description=$_REQUEST['description']; else $search_description='';

// 	if($search_code!='' || $search_description!=''){
// 		$query='';
// 		$decimal=0;
// 		include('config.php');
// 		$result = mysqli_query($conn,"SELECT `value` FROM settings WHERE setting='decimal'");
// 		$row = mysqli_fetch_assoc($result);
// 		$decimal=$row['value'];

// 		$result = mysqli_query($conn2,"SELECT mapped_inventory,store FROM userprofile WHERE id='$user_id'");
// 		$row = mysqli_fetch_assoc($result);
// 		$mapped_inventory=$row['mapped_inventory'];
// 		$store=$row['store'];

// 		$query="SELECT gp.`group` FROM userprofile up, store_group gp WHERE up.store=gp.store AND up.id='$user_id'";
// 		$result = mysqli_query($conn2,$query);
// 		$row = mysqli_fetch_assoc($result);
// 		$group=$row['group'];

// 		$query="SELECT id FROM inventory_items WHERE code='$search_code'";
// 		$row=mysqli_fetch_row(mysqli_query($conn,$query));
// 		$itm_id=$row[0];

// 		$query="SELECT increment FROM district_rate WHERE `district`='$district' AND `sub_system`='$sub_system'";
// 		$result=mysqli_query($conn,$query);
// 		while($row=mysqli_fetch_array($result)){	$increment=$row[0]; }
// 		if($increment=='') $increment=0;


// 		$group_table = ",store_group gp";
// 		$group_qry = "AND st.id=gp.store AND gp.`group`='$group'";
// 		if($systemid == 24){
// 			$store_filter = "AND itq.`location` ='$store'";
// 			if(isset($_COOKIE['manager'])){
// 				$subsystem_qry = 'AND itm.sub_system = '.$sub_system;
// 				$store_filter = '';
// 			}
// 			if(isset($_COOKIE['top_manager'])){
// 				$store_filter='';
// 				$subsystem_qry='';
// 				$group_table='';
// 				$group_qry='';
// 			}
// 		}

// 		if($search_code!=''){
// 			$query="SELECT rp.`drawer_no`,itm.default_price,SUM(if(rpi.qty>0, 1, 100000)) FROM inventory_items itm, item_category itc, repair_parts_map rm, repair_parts_inventory rpi, repair_parts rp WHERE rm.repair_part=rpi.part AND itm.id=rm.inv_item AND itm.category=itc.id AND rp.id=rpi.part AND rpi.location='$store' AND itm.pr_sr=3 AND itm.`status`=1 AND rp.`status`=1 AND itm.code='$search_code' GROUP BY itm.id";
// 			$result=mysqli_query($conn2,$query);
// 			while($row=mysqli_fetch_array($result)){
// 				$store_name[]='Current Store';
// 				$item_dr[]=$row[0];
// 				$item_w_price[]=$row[1];
// 				if($row[2]<100000) $item_qty[]='Available'; else $item_qty[]='-';
// 			}

// 			if($mapped_inventory==0){
// 				$query="SELECT st.name,itq.w_price,itq.r_price,itq.qty,itq.item,itm.`category`,itq.drawer_no,itq.id,itm.unic FROM inventory_items itm, inventory_qty itq, stores st $group_table WHERE itm.id=itq.item AND itq.location=st.id $group_qry AND st.`status`=1 AND itm.code='$search_code' $store_filter $subsystem_qry";
// 			}else{
// 				$query="SELECT st.name,itq.w_price,itq.r_price,itq.qty,itq.item,itm.`category`,itq.drawer_no,itq.id,itm.unic FROM inventory_items itm, inventory_qty itq, stores st WHERE itm.id=itq.item AND itq.location=st.id AND itq.location='$mapped_inventory' AND st.`status`=1 AND itm.code='$search_code'";
// 			}
// 			$result=mysqli_query($conn2,$query);
// 			while($row=mysqli_fetch_array($result)){
// 				$specialrate=$catspecialrate=false;
// 				$itm_category=$row[5];
// 				$item_dr[]=$row[6];
// 				$itq_id=$row[7];
// 				$unic=$row[8];
// 				$store_name[]=$row[0];

// 				$query1="SELECT increment FROM special_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system' AND `item`='$itm_id'";
// 				$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
// 				if(isset($row1[0])){
// 					$sp_increment=$row1[0];
// 				}else{
// 					$sp_increment=0;
// 				}

// 				if($sp_increment!='') $specialrate=true;
// 				$query1="SELECT increment FROM category_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system' AND `category`='$itm_category'";
// 				$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
// 				if(isset($row1[0])){
// 					$sp_catincrement=$row1[0];
// 				}else{
// 					$sp_catincrement=0;
// 				}
// 				if($sp_catincrement!='') $catspecialrate=true;

// 				if(($unic_cal)&&($unic==1)){
// 					$query1="SELECT COUNT(id),SUM(w_price),SUM(r_price) FROM inventory_unic_item WHERE itq_id='$itq_id' AND `status`='0'";
// 					$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
// 					$item_qty[]=$row1[0];
// 					if($row1[0]!=0){
// 						$w_price=$row1[1]/$row1[0];
// 						$r_price=$row1[2]/$row1[0];
// 						if($specialrate){
// 							$item_w_price[]=round($w_price*((100+$sp_increment)/100),$decimal);
// 							$item_r_price[]=round($r_price*((100+$sp_increment)/100),$decimal);
// 						}elseif($catspecialrate){
// 							$item_w_price[]=round($w_price*((100+$sp_catincrement)/100),$decimal);
// 							$item_r_price[]=round($r_price*((100+$sp_catincrement)/100),$decimal);
// 						}else{
// 							$item_w_price[]=round($w_price*((100+$increment)/100),$decimal);
// 							$item_r_price[]=round($r_price*((100+$increment)/100),$decimal);
// 						}
// 					}else{
// 						$item_w_price[]=0;
// 						$item_r_price[]=0;
// 					}
// 				}else{
// 					$item_qty[]=$row[3];
// 					$w_price=$row[1];
// 					$r_price=$row[2];
// 					if($specialrate){
// 						$item_w_price[]=round($w_price*((100+$sp_increment)/100),$decimal);
// 						$item_r_price[]=round($r_price*((100+$sp_increment)/100),$decimal);
// 					}elseif($catspecialrate){
// 						$item_w_price[]=round($w_price*((100+$sp_catincrement)/100),$decimal);
// 						$item_r_price[]=round($r_price*((100+$sp_catincrement)/100),$decimal);
// 					}else{
// 						$item_w_price[]=round($w_price*((100+$increment)/100),$decimal);
// 						$item_r_price[]=round($r_price*((100+$increment)/100),$decimal);
// 					}
// 				}
// 			}

// 			if($mapped_inventory==0)
// 				$query="SELECT st.name,itn.w_price,itn.r_price,itn.qty,itm.id,itm.`category` FROM inventory_items itm, inventory_new itn, stores st, store_group gp WHERE itm.id=itn.item AND itn.store=st.id AND st.id=gp.store AND st.`status`=1 AND gp.`group`='$group' AND itm.code='$search_code'";
// 			else
// 			$query="SELECT st.name,itn.w_price,itn.r_price,itn.qty,itm.id,itm.`category` FROM inventory_items itm, inventory_new itn, stores st WHERE itm.id=itn.item AND itn.store=st.id AND itn.store='$mapped_inventory' AND st.`status`=1 AND itm.code='$search_code'";
// 			$result=mysqli_query($conn2,$query);
// 			while($row=mysqli_fetch_array($result)){
// 				$specialrate=$catspecialrate=false;
// 				$store_name[]=$row[0].' (New)';
// 				$item_qty[]=$row[3];
// 				$itm_id=$row[4];
// 				$itm_category=$row[5];
// 				$item_dr[]='';

// 				$query1="SELECT increment FROM special_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system' AND `item`='$itm_id'";
// 				$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
// 				$sp_increment=$row1[0];
// 				if($sp_increment!='') $specialrate=true;
// 				$query1="SELECT increment FROM category_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system' AND `category`='$itm_category'";
// 				$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
// 				$sp_catincrement=$row1[0];
// 				if($sp_catincrement!='') $catspecialrate=true;

// 				$w_price=$row[1];
// 				$r_price=$row[2];
// 				if($specialrate){
// 					$item_w_price[]=round($w_price*((100+$sp_increment)/100),$decimal);
// 					$item_r_price[]=round($r_price*((100+$sp_increment)/100),$decimal);
// 				}elseif($catspecialrate){
// 					$item_w_price[]=round($w_price*((100+$sp_catincrement)/100),$decimal);
// 					$item_r_price[]=round($r_price*((100+$sp_catincrement)/100),$decimal);
// 				}else{
// 					$item_w_price[]=round($w_price*((100+$increment)/100),$decimal);
// 					$item_r_price[]=round($r_price*((100+$increment)/100),$decimal);
// 				}
// 			}
// 		}
// 	}
// }

// update by nirmal 2024_07_14 (for sysid==24 show only logged user store item qty, which omits subsystem store with their qty)
// update by nirmal 2024_07_30 (for get unic items sets group by prices)
function checkItem($sub_system){ // item code or desc search results
	global $store_name,$item_w_price,$item_r_price,$item_qty,$search_code,$search_description,$item_dr,$decimal, $conn, $conn2;
	$sp_item=$sp_increment=$sp_category=$sp_catincrement=$store_name=array();
	$user_id=$_COOKIE['user_id'];
	$district=$_COOKIE['district'];
	$unic_cal=unicCal();
	$increment=$store=$subsystem_qry=$store_filter='';
	$systemid=inf_systemid(1);
	if(isset($_REQUEST['code'])) $search_code=$_REQUEST['code']; else $search_code='';
	if(isset($_REQUEST['description'])) $search_description=$_REQUEST['description']; else $search_description='';

	if($search_code!='' || $search_description!=''){
		$query='';
		$decimal=0;
		include('config.php');
		$result = mysqli_query($conn,"SELECT `value` FROM settings WHERE setting='decimal'");
		$row = mysqli_fetch_assoc($result);
		$decimal=$row['value'];

		$result = mysqli_query($conn2,"SELECT mapped_inventory,store FROM userprofile WHERE id='$user_id'");
		$row = mysqli_fetch_assoc($result);
		$mapped_inventory=$row['mapped_inventory'];
		$store=$row['store'];

		$query="SELECT gp.`group` FROM userprofile up, store_group gp WHERE up.store=gp.store AND up.id='$user_id'";
		$result = mysqli_query($conn2,$query);
		$row = mysqli_fetch_assoc($result);
		$group=$row['group'];

		$query="SELECT id FROM inventory_items WHERE code='$search_code'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$itm_id=$row[0];

		$query="SELECT increment FROM district_rate WHERE `district`='$district' AND `sub_system`='$sub_system'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){	$increment=$row[0]; }
		if($increment=='') $increment=0;


		$group_table = ",store_group gp";
		$group_qry = "AND st.id=gp.store AND gp.`group`='$group'";
		if($systemid == 24){
			$store_filter = "AND itq.`location` ='$store'";
			if(isset($_COOKIE['manager'])){
				$subsystem_qry = 'AND itm.sub_system = '.$sub_system;
				$store_filter = '';
			}
			if(isset($_COOKIE['top_manager'])){
				$store_filter='';
				$subsystem_qry='';
				$group_table='';
				$group_qry='';
			}
		}

		if($search_code!=''){
			// $query="SELECT rp.`drawer_no`,itm.default_price,SUM(if(rpi.qty>0, 1, 100000)) FROM inventory_items itm, item_category itc, repair_parts_map rm, repair_parts_inventory rpi, repair_parts rp WHERE rm.repair_part=rpi.part AND itm.id=rm.inv_item AND itm.category=itc.id AND rp.id=rpi.part AND rpi.location='$store' AND itm.pr_sr=3 AND itm.`status`=1 AND rp.`status`=1 AND itm.code='$search_code' GROUP BY itm.id";
			$query = "SELECT rp.`drawer_no`, itm.default_price, SUM(if(rpi.qty>0, 1, 100000)) 
          FROM inventory_items itm, item_category itc, repair_parts_map rm, 
               repair_parts_inventory rpi, repair_parts rp 
          WHERE rm.repair_part = rpi.part 
            AND itm.id = rm.inv_item 
            AND itm.category = itc.id 
            AND rp.id = rpi.part 
            AND rpi.location = '$store' 
            AND itm.pr_sr = 3 
            AND itm.`status` = 1 
            AND rp.`status` = 1 
            AND itm.code = '$search_code' 
          GROUP BY itm.id, rp.drawer_no, itm.default_price";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$store_name[]='Current Store';
				$item_dr[]=$row[0];
				$item_w_price[]=$row[1];
				if($row[2]<100000) $item_qty[]='Available'; else $item_qty[]='-';
			}

			if($mapped_inventory==0){
				$query="SELECT st.name,itq.w_price,itq.r_price,itq.qty,itq.item,itm.`category`,itq.drawer_no,itq.id,itm.unic FROM inventory_items itm, inventory_qty itq, stores st $group_table WHERE itm.id=itq.item AND itq.location=st.id $group_qry AND st.`status`=1 AND itm.code='$search_code' $store_filter $subsystem_qry";
			}else{
				$query="SELECT st.name,itq.w_price,itq.r_price,itq.qty,itq.item,itm.`category`,itq.drawer_no,itq.id,itm.unic FROM inventory_items itm, inventory_qty itq, stores st WHERE itm.id=itq.item AND itq.location=st.id AND itq.location='$mapped_inventory' AND st.`status`=1 AND itm.code='$search_code'";
			}
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$specialrate=$catspecialrate=false;
				$itq_id=$row[7];
				$unic=$row[8];
				$itm_category=$row[5];

				$query1="SELECT increment FROM special_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system' AND `item`='$itm_id'";
				$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
				if(isset($row1[0])){
					$sp_increment=$row1[0];
				}else{
					$sp_increment=0;
				}

				if($sp_increment!='') $specialrate=true;
				$query2="SELECT increment FROM category_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system' AND `category`='$itm_category'";
				$row2=mysqli_fetch_row(mysqli_query($conn,$query2));
				if(isset($row2[0])){
					$sp_catincrement=$row2[0];
				}else{
					$sp_catincrement=0;
				}
				if($sp_catincrement!='') $catspecialrate=true;

				if(($unic_cal)&&($unic==1)){
					// $query3 = "SELECT COUNT(iui.id),iui.w_price, iui.r_price, iui.c_price
					// FROM inventory_unic_item iui WHERE iui.itq_id = '$itq_id' AND iui.status = '0'
					// GROUP BY iui.w_price";
					$query3 = "SELECT COUNT(iui.id), iui.w_price, iui.r_price, iui.c_price
           FROM inventory_unic_item iui 
           WHERE iui.itq_id = '$itq_id' AND iui.status = '0'
           GROUP BY iui.w_price, iui.r_price, iui.c_price";
					$result3=mysqli_query($conn2,$query3);
					while($row3=mysqli_fetch_array($result3)){
						$item_dr[]=$row[6];
						$store_name[]=$row[0];
						$item_qty[]=$row3[0];
						$w_price=$row3[1];
						$r_price=$row3[2];
						if($specialrate){
							$item_w_price[]=round($w_price*((100+$sp_increment)/100),$decimal);
							$item_r_price[]=round($r_price*((100+$sp_increment)/100),$decimal);
						}elseif($catspecialrate){
							$item_w_price[]=round($w_price*((100+$sp_catincrement)/100),$decimal);
							$item_r_price[]=round($r_price*((100+$sp_catincrement)/100),$decimal);
						}else{
							$item_w_price[]=round($w_price*((100+$increment)/100),$decimal);
							$item_r_price[]=round($r_price*((100+$increment)/100),$decimal);
						}
					}
				}else{
					$item_dr[]=$row[6];
					$store_name[]=$row[0];
					$item_qty[]=$row[3];
					$w_price=$row[1];
					$r_price=$row[2];
					if($specialrate){
						$item_w_price[]=round($w_price*((100+$sp_increment)/100),$decimal);
						$item_r_price[]=round($r_price*((100+$sp_increment)/100),$decimal);
					}elseif($catspecialrate){
						$item_w_price[]=round($w_price*((100+$sp_catincrement)/100),$decimal);
						$item_r_price[]=round($r_price*((100+$sp_catincrement)/100),$decimal);
					}else{
						$item_w_price[]=round($w_price*((100+$increment)/100),$decimal);
						$item_r_price[]=round($r_price*((100+$increment)/100),$decimal);
					}
				}
			}

			if($mapped_inventory==0)
				$query="SELECT st.name,itn.w_price,itn.r_price,itn.qty,itm.id,itm.`category` FROM inventory_items itm, inventory_new itn, stores st, store_group gp WHERE itm.id=itn.item AND itn.store=st.id AND st.id=gp.store AND st.`status`=1 AND gp.`group`='$group' AND itm.code='$search_code'";
			else
			$query="SELECT st.name,itn.w_price,itn.r_price,itn.qty,itm.id,itm.`category` FROM inventory_items itm, inventory_new itn, stores st WHERE itm.id=itn.item AND itn.store=st.id AND itn.store='$mapped_inventory' AND st.`status`=1 AND itm.code='$search_code'";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$specialrate=$catspecialrate=false;
				$store_name[]=$row[0].' (New)';
				$item_qty[]=$row[3];
				$itm_id=$row[4];
				$itm_category=$row[5];
				$item_dr[]='';

				$query1="SELECT increment FROM special_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system' AND `item`='$itm_id'";
				$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
				$sp_increment=$row1[0];
				if($sp_increment!='') $specialrate=true;
				$query1="SELECT increment FROM category_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system' AND `category`='$itm_category'";
				$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
				$sp_catincrement=$row1[0];
				if($sp_catincrement!='') $catspecialrate=true;

				$w_price=$row[1];
				$r_price=$row[2];
				if($specialrate){
					$item_w_price[]=round($w_price*((100+$sp_increment)/100),$decimal);
					$item_r_price[]=round($r_price*((100+$sp_increment)/100),$decimal);
				}elseif($catspecialrate){
					$item_w_price[]=round($w_price*((100+$sp_catincrement)/100),$decimal);
					$item_r_price[]=round($r_price*((100+$sp_catincrement)/100),$decimal);
				}else{
					$item_w_price[]=round($w_price*((100+$increment)/100),$decimal);
					$item_r_price[]=round($r_price*((100+$increment)/100),$decimal);
				}
			}
		}
	}
}

//------------------------------------Catalog-------------------------------//
function getItmes2(){
global $itm_id,$itm_desc,$conn2;
	$itm_id=$itm_desc=array();
	include('config.php');
	$query="SELECT id,description FROM inventory_items WHERE pr_sr=1 AND `status`=1";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$itm_id[]=$row[0];
		$itm_desc[]=$row[1];
	}
}

// update by nirmal 16_10_2023
function getCatalog(){
	global $decimal,$from_date,$to_date,$qty_order,$show_all,$direct_mkt,$sold_color,$today,$selected_cat,$filter_category_id,$filter_category_name,$district,$cust_id,$item_id,$item_desc,$cat_itemid,$cat_category,$cat_desc,$cat_min_w_rate,$cat_max_w_rate,$cat_max_r_rate,$w_price,$r_price,$cat_qty,$cat_last_rate,$cat_store,$conn;
	$sp_item=$sp_increment=$sp_category=$sp_catincrement=$cat_filter=$sold_items=$sold_discounted=$filter_category_id=$filter_category_name=$cat_desc=array();
	$direct_mkt=$_COOKIE['direct_mkt'];
	$user_id=$_COOKIE['user_id'];
	$unic_cal=unicCal();
	if(isset($_REQUEST['show_all'])){ $show_all='checked="checked"'; }else{ $show_all=''; }
	if(isset($_GET['order'])){ $qty_order=$_GET['order']; $order_qry="itq.qty $qty_order, itm.description"; }else{ $qty_order=''; $order_qry="itc.name, itm.description"; }
	if(isset($_GET['from_date'])){ $from_date=$_GET['from_date']; }else{ $from_date=date("Y-m-d",time()-365*24*60*60); }
	if(isset($_GET['to_date'])){ $to_date=$_GET['to_date']; }else{ $to_date=dateNow(); }

	include('config.php');
	$result = mysqli_query($conn,"SELECT `value` FROM settings WHERE setting='decimal'");
	$row = mysqli_fetch_assoc($result);
	$decimal=$row['value'];

	if(isset($_REQUEST['district'])||($direct_mkt==1)){
		if((isset($_REQUEST['item_id'])) && (isset($_REQUEST['cust_id']))){
			$item_id=$_REQUEST['item_id'];

			$result = mysqli_query($conn2,"SELECT mapped_inventory FROM userprofile WHERE id='$user_id'");
			$row = mysqli_fetch_assoc($result);
			$mapped_inventory=$row['mapped_inventory'];
			if($mapped_inventory==0) $store=$_COOKIE['store']; else $store=$mapped_inventory;

			if($_COOKIE['direct_mkt']==1){
				$district=9;
				$cust_id='';
			}else{
				$district=$_REQUEST['district'];
				$cust_id=$_REQUEST['cust_id'];
			}


			$today=dateNow();
			if(isset($_GET['filter_cat'])){
				$selected_cat=$_GET['filter_cat'];
				if($selected_cat=='all') $filter_qry=""; else	$filter_qry="AND itc.id='$selected_cat'";
			}else{
				$selected_cat='';
				$filter_qry="";
			}

			if($item_id==''){
				if($show_all=='')
					$item_filter="itq.location='$store' AND itq.qty>0";
				else
					$item_filter="itq.location='$store'";
			}else{
				$item_filter="itm.id='$item_id'";
			}

			$query1="SELECT DISTINCT itc.id,itc.name,itm.description FROM item_category itc, inventory_items itm, inventory_qty itq WHERE itc.id=itm.category AND itm.id=itq.item AND $item_filter ORDER BY itc.name";
			$result1=mysqli_query($conn2,$query1);
			while($row1=mysqli_fetch_array($result1)){
				$filter_category_id[]=$row1[0];
				$filter_category_name[]=$row1[1];
				if($item_id!='') $item_desc=$row1[2];
			}

			$query="SELECT increment FROM district WHERE id='$district'";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){	$increment=(100+$row[0])/100; }
			$query1="SELECT item,increment FROM special_rate WHERE district IN ($district,0)";
			$result1=mysqli_query($conn2,$query1);
			while($row1=mysqli_fetch_array($result1)){
				$sp_item[]=$row1[0];
				$sp_increment[]=$row1[1];
			}
			$query1="SELECT category,increment FROM category_rate WHERE district IN ($district,0)";
			$result1=mysqli_query($conn2,$query1);
			while($row1=mysqli_fetch_array($result1)){
				$sp_category[]=$row1[0];
				$sp_catincrement[]=$row1[1];
			}


			$query="SELECT bi.item,MAX(bi.discount) FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`='$cust_id' AND ( date(bm.billed_timestamp) BETWEEN '$from_date' AND '$to_date' ) GROUP BY bi.item";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$sold_items[]=$row[0];
				$sold_discounted[]=$row[1];
			}

			$query="SELECT itm.id,itc.name,itm.description,itm.min_w_rate,itm.max_w_rate,itq.w_price,itq.qty,itc.id,itq.r_price,itm.max_r_rate,st.name,itm.unic,itq.id FROM inventory_items itm, inventory_qty itq, item_category itc, stores st WHERE itm.id=itq.item AND itm.category=itc.id AND itq.location=st.id AND $item_filter $filter_qry ORDER BY $order_qry";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){

				$query1="SELECT SUM(qty) FROM inventory_new WHERE item='$row[0]' AND `store`='$store'";
				$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
				$itn_qty=$row1[0];

				$no_specialrate=$no_catspecialrate=true;
				$cat_itemid[]=$row[0];
				$cat_category[]=$row[1];
				$cat_desc[]=$row[2];
				$cat_min_w_rate[]=$row[3];
				$cat_max_w_rate[]=$row[4];
				$cat_max_r_rate[]=$row[9];
				$cat_w_price[]=$row[5];
				$cat_r_price[]=$row[8];
				$cat_store[]=$row[10];
				$cat_last_rate[]='';
				$unic=$row[11];
				$itq_id=$row[12];
				$key=array_search($row[0],$sold_items);
				if($key>-1){
					if($sold_discounted[$key]>0) $sold_color[]=1; else $sold_color[]=2;
				}else{
					$sold_color[]=3;
				}

				if(($unic_cal)&&($unic==1)){
					$query1="SELECT COUNT(id),SUM(w_price),SUM(r_price) FROM inventory_unic_item WHERE itq_id='$itq_id' AND `status`='0'";
					$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
					$cat_qty[]=$row1[0];
					if($row1[0]>0) $wprice=$row1[1]/$row1[0]; else $wprice=0;
					if($row1[0]>0) $rprice=$row1[2]/$row1[0]; else $rprice=0;
				}else{
					$wprice=$row[5];
					$rprice=$row[8];
					if($itn_qty!='') $cat_qty[]=$row[6].'<strong> + </strong><span style="color:red">'.$itn_qty.'</span>';  else $cat_qty[]=$row[6];
				}

				for($i=0;$i<sizeof($sp_item);$i++){
					if($sp_item[$i]==$row[0]){
						$no_specialrate=false;
						$w_price[]=round($wprice*((100+$sp_increment[$i])/100),$decimal);
						$r_price[]=round($rprice*((100+$sp_increment[$i])/100),$decimal);
					}
				}
				if($no_specialrate){
						for($i=0;$i<sizeof($sp_category);$i++){
							if($sp_category[$i]==$row[7]){
								$no_catspecialrate=false;
								$w_price[]=round($wprice*((100+$sp_catincrement[$i])/100),$decimal);
								$r_price[]=round($rprice*((100+$sp_catincrement[$i])/100),$decimal);
							}
						}
						if($no_catspecialrate){
							$w_price[]=round(($wprice*$increment),$decimal);
							$r_price[]=round(($rprice*$increment),$decimal);
						}
				}
			}
		}
	}
}

function getDiscount(){
	$itemid=$_GET['itemid'];
	$cust_id=$_GET['cust_id'];

	include('config.php');
	$query="SELECT bi.unit_price,bi.discount FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`='$cust_id' AND bi.item='$itemid' AND bi.discount>0 ORDER BY bi.id DESC LIMIT 1";
	$row1=mysqli_fetch_row(mysqli_query($conn2,$query));
	$last_sold_price=$row1[0]+$row1[1];
	if(($row1[0]!='')&&($row1[1]!='')){
		$last_discount=round(($row1[1]/($row1[0]+$row1[1]))*100);
	}else $last_discount=0;

	return $last_discount.'|'.$last_sold_price;
}

// update by nirmal 06_11_2023, update by nirmal (added get all category items)
function getStock(){
	global $mystore,$store_id,$store_name,$itm_id,$itm_des,$itm_qty,$itq_category_name,$conn2;
	if(isset($_GET['cat_id'])) $cat_id=$_GET['cat_id']; else $cat_id='';
	$mystore=$_COOKIE['store'];
	$user_id=$_COOKIE['user_id'];
	$unic_cal=unicCal();
	$systemid=inf_systemid(1);
	$sub_system=$_COOKIE['sub_system'];
	$itm_id_tmp='';
	$itm_id=$itq_id=$tr_qty=$bi_qty=$store_name=array();
	include('config.php');

	$result = mysqli_query($conn2,"SELECT `store_group` FROM userprofile WHERE `id`='$user_id'");
	$row = mysqli_fetch_assoc($result);
	$group=$row['store_group'];
	if($group == 0){
		$result = mysqli_query($conn2,"SELECT `group` FROM store_group WHERE store='$mystore'");
		$row = mysqli_fetch_assoc($result);
		$group=$row['group'];
	}

	$query="SELECT st.id,st.name FROM stores st, store_group sg WHERE st.id=sg.store AND sg.`group`='$group'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$store_id[]=$row[0];
		$store_name[]=$row[1];
	}

	if(($cat_id == 'all') && ($systemid == 13)){
		$cat_query = '';
		if (($sub_system == 1)) {
			$cat_query = "AND itm.category != '17'";
		}
	}else{
		$cat_query = "AND itm.category='$cat_id'";
	}

	$query="SELECT itm.id,itm.description,itq.location,itq.qty,itq.id,itm.unic,itm.pr_sr,itc.`name`  FROM inventory_items itm, inventory_qty itq, store_group sg, `item_category` itc  WHERE itm.id=itq.item AND itq.location=sg.store $cat_query AND itm.category=itc.`id` AND itm.pr_sr='1' AND sg.`group`='$group' ORDER BY itc.`name`, itm.description";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$itq_qty=$row[3];
		$itq_id[]=$row[4];
		$itq_id_tmp=$row[4];
 		$unic=$row[5];

		if((!$unic_cal)||(($unic_cal)&&($unic==0))){
			$query1="SELECT SUM(qty) FROM inventory_new WHERE item='$row[0]' AND `store`='$row[2]'";
			$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
			$itn_qty=$row1[0];
			$qty=$itq_qty+$itn_qty;
		}else{
			$query1="SELECT COUNT(id) FROM inventory_unic_item WHERE itq_id='$itq_id_tmp' AND `status`='0'";
			$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
			$qty=$row1[0];
		}

		if($qty>0){
			if($itm_id_tmp!=$row[0]){
				$itm_id[]=$row[0];
				$itm_des[$row[0]]=$row[1];
				$itq_category_name[$row[0]]=$row[7];
				for($i=0;$i<sizeof($store_id);$i++){
					$itm_qty[$row[0]][$i]='-';
					$tr_qty[$row[0]][$i]=0;
					$bi_qty[$row[0]][$i]=0;
					if($store_id[$i]==$row[2]) $itm_qty[$row[0]][$i]=$qty;
				}
			}else{
				for($i=0;$i<sizeof($store_id);$i++){
					if($store_id[$i]==$row[2]) $itm_qty[$row[0]][$i]=$qty;
				}
			}
			$itm_id_tmp=$row[0];
		}
	}

	$query="SELECT itm.id,itm.description,iqt.location,tr.qty,iqt.id,itm.unic,tr.id FROM inventory_items itm, inventory_qty iqt, stores st, store_group sg, transfer_main tm, transfer tr  WHERE itm.id=iqt.item AND tm.gtn_no=tr.gtn_no AND tm.from_store=st.id AND tr.item=itm.id AND st.id=iqt.location AND st.id=sg.store AND itm.pr_sr='1' $cat_query AND sg.`group`='$group' AND tm.`status` IN (0,4) AND itm.`status`=1 ORDER BY iqt.drawer_no";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$itm_id_tmp=$row[0];
		$itq_id_tmp=$row[4];
		$unic=$row[5];
		$tr_id=$row[6];

		if(($unic_cal)&&($unic==1)){
			$query1="SELECT COUNT(id) FROM inventory_unic_item WHERE itq_id='$itq_id_tmp' AND trans_id='$tr_id' AND `status`='3'";
			$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
			$qty=$row1[0];
		}else{
			$qty=$row[3];
		}

		for ($i = 0; $i < sizeof($store_id); $i++) {
			if ($qty > 0 && isset($store_id[$i]) && isset($row[2]) && $store_id[$i] == $row[2]) {
				if (!isset($tr_qty[$row[0]][$i])) {
					$tr_qty[$row[0]][$i] = 0;
				}
				$tr_qty[$row[0]][$i] += $qty;
			}
		}
	}
	for($i=0;$i<sizeof($itm_id);$i++){
		for($j=0;$j<sizeof($store_id);$j++){
			if($tr_qty[$itm_id[$i]][$j]>0){
				$itm_qty[$itm_id[$i]][$j].='<span style="color:blue"> + '.$tr_qty[$itm_id[$i]][$j].'</span>';
			}
		}
	}

	$query="SELECT itm.id,itm.description,iqt.location,bi.qty,iqt.id,itm.unic,bi.id FROM inventory_items itm, inventory_qty iqt,stores st, store_group sg, bill bi, bill_main bm  WHERE itm.category='$cat_id' AND bi.item=itm.id AND bi.invoice_no=bm.invoice_no AND bm.store=st.id AND st.id=iqt.location AND st.id=sg.store AND itm.id=iqt.item AND itm.pr_sr='1' AND (bm.`lock`=0 OR bm.`status` IN (1,2,3,4)) AND bm.`status`!=0 AND itm.`status`=1 ORDER BY iqt.drawer_no";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$itm_id_tmp=$row[0];
		$itq_id_tmp=$row[4];
		$unic=$row[5];
		$bi_id=$row[6];

		if(($unic_cal)&&($unic==1)){
			$query1="SELECT COUNT(id) FROM inventory_unic_item WHERE itq_id='$itq_id_tmp' AND bill_id='$bi_id' AND `status`='1'";
			$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
			$qty=$row1[0];
		}else{
			$qty=$row[3];
		}
		for($i=0;$i<sizeof($store_id);$i++){
			if($qty>0){
				//if($store_id[$i]==$row[2]) $bi_qty[$row[0]][$i]+=$qty;
				if (isset($store_id[$i]) && isset($bi_qty[$row[0]][$i]) && $store_id[$i] == $row[2]) {
					$bi_qty[$row[0]][$i] += $qty;
				}
			}
		}
	}

	for($i=0;$i<sizeof($itm_id);$i++){
		for($j=0;$j<sizeof($store_id);$j++){
			if($bi_qty[$itm_id[$i]][$j]>0){
				$itm_qty[$itm_id[$i]][$j].='<span style="color:yellow"> + '.$bi_qty[$itm_id[$i]][$j].'</span>';
			}
		}
	}

}

?>