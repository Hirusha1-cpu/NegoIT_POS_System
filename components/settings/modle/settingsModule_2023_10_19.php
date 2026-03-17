<?php
function getCategory(){
	global $category_id,$category_name,$sub_id,$sub_name,$category_sub;
	$category_id=$category_name=$sub_id=$sub_name=$sub_list=array();
	include('config.php');
		$query="SELECT id,name FROM `sub_system` WHERE `status`=1 ORDER BY name";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$sub_id[]=$row[0];
			$sub_name[]=$row[1];
			$sub_list[$row[0]]=$row[1];
		} 
		
		$query="SELECT id,name,sub_system FROM item_category";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$category_id[]=$row[0];
			$category_name[]=$row[1];
			if($row[2]=='all'){
				$category_sub[]='All';
			}else{
				$category_sub[]=$sub_list[$row[2]];
			}
	}
}

function getBanks(){
	global $bank_id,$bank_name;
	include('config.php');
		$query="SELECT id,name FROM bank WHERE `status`=1";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$bank_id[]=$row[0];
			$bank_name[]=$row[1];
	} 
}

function getUsers(){
global $function_id,$function_name,$uprof_id,$uprof_name,$per_id,$usr_name,$usr_type,$usr_function,$st_id,$st_name,$uprof_store,$uprof_status,$storeavailable,$uprof_devicecheck,$uprof_timecheck,$uprof_mobrep,$uprof_sub_system,$uprof_map_inv,$st_sub_sys,$uprof_sub_sysnm,$sub_sysid,$sub_sysname;
	$components=$_GET['components'];
	if($components=='topmanager'){ 
		$user_qry="AND sub_system!=0"; 
		$sub_qry="AND id!=0"; 
		$function_qry="AND name IN ('Inventory','Billing','Order Process','Check Availability','Stores Transfer','Supervisor','Manager')";
	}else{ 
		$user_qry=$sub_qry=$function_qry=""; 
	}
	include('config.php');
		$query="SELECT pe.id,up.username,fn.name FROM userprofile up, `function` fn, permission pe WHERE up.id=pe.`user` AND fn.id=pe.`function` AND fn.`status`=1";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$per_id[]=$row[0];
			$usr_name[]=$row[1];
			$usr_function[]=$row[2];
	} 
		$query="SELECT id,name FROM `function` WHERE `status`=1 $function_qry";
		$result=mysqli_query($conn,$query);
		while($row2=mysqli_fetch_array($result)){
			$function_id[]=$row2[0];
			$function_name[]=$row2[1];
	} 
		$query="SELECT up.id,up.username,up.store,up.device_restrict,up.time_restrict,up.mobile_rep,up.`status`,up.`sub_system`,ss.name,up.mapped_inventory FROM `userprofile` up, sub_system ss WHERE up.`sub_system`=ss.id $user_qry ORDER BY up.`sub_system`, up.username";
		$result=mysqli_query($conn,$query);
		while($row2=mysqli_fetch_array($result)){
			$uprof_id[]=$row2[0];
			$uprof_name[]=$row2[1];
			$uprof_store[]=$row2[2];
			$uprof_devicecheck[]=$row2[3];
			$uprof_timecheck[]=$row2[4];
			$uprof_mobrep[]=$row2[5];
			$uprof_status[]=$row2[6];
			$uid=$row2[0];
			$uprof_sub_system[]=$row2[7];
			$uprof_sub_sysnm[]=$row2[8];
			$uprof_map_inv[]=$row2[9];
			
			$query3="SELECT  count(pe.id) FROM permission pe, `function` fn WHERE pe.`function`=fn.id AND fn.`store_depend`=1 AND fn.`status`=1 AND pe.`user`='$uid'";
			$result3=mysqli_query($conn,$query3);
			while($row3=mysqli_fetch_array($result3)){
			$storeavailable[$uid]=$row3[0];
			}

	} 
	$query="SELECT id,name,sub_system FROM `stores` WHERE `status`=1 ORDER BY name";
	$result=mysqli_query($conn,$query);
	while($row2=mysqli_fetch_array($result)){
		$st_id[]=$row2[0];
		$st_name[]=$row2[1];
		$st_sub_sys[]=$row2[2];
	} 
	$query="SELECT id,name FROM sub_system WHERE `status`=1 $sub_qry";
	$result=mysqli_query($conn,$query);
	while($row2=mysqli_fetch_array($result)){
		$sub_sysid[]=$row2[0];
		$sub_sysname[]=$row2[1];
	} 
}

function getOneUser(){
global $usr_id1,$usr_uname1,$usr_emp_name,$usr_nic,$usr_mobile,$usr_bank_name,$usr_bank_branch,$usr_bank_ac,$usr_status1,$usr_direct_mkt;
	$usr_id1=$_REQUEST['id'];
	include('config.php');
		$query="SELECT up.username,up.emp_name,up.nic,up.mobile,bk.name,up.bank_branch,up.bank_ac,up.`status`,up.direct_mkt FROM userprofile up LEFT JOIN bank bk ON up.bank_id=bk.id WHERE up.id='$usr_id1'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$usr_uname1=$row[0];
			$usr_emp_name=$row[1];
			$usr_nic=$row[2];
			$usr_mobile=$row[3];
			$usr_bank_name=$row[4];
			$usr_bank_branch=$row[5];
			$usr_bank_ac=$row[6];
			$usr_status1= $row[7];
			if($row[8]==1)	$usr_direct_mkt='checked="checked"'; else $usr_direct_mkt='';
	} 
}


function setStatusUser($status){
	global $message;
	$id=$_REQUEST['id'];
	if($status==0) $msg='Activated';
	if($status==1) $msg='Deactivated';
	include('config.php');

		$query="UPDATE `userprofile` SET `status`='$status' WHERE `id`='$id'";
		$result=mysqli_query($conn,$query);
		
		if($result){
			$message='User was '.$msg.' Successfully!';
			return true;
		}else{
			$message='User could not be '.$msg.'!';
			return false;
		}

}

function addUser(){
	global $message;
	$username=strtolower($_POST['user_name']);
	$password=$_POST['passhash'];
	$emp_name=$_POST['emp_name'];
	$nic=$_POST['nic'];
	$mobile=$_POST['mobile'];
	$bank_name=$_POST['bank_name'];
	$bank_branch=$_POST['bank_branch'];
	$bank_ac=$_POST['bank_ac'];
	$sub_system=$_POST['sub_sys'];
	if(isset($_POST['change_pw']) && $_POST['change_pw']!="") $change_pw=1; else $change_pw=0;
	if(isset($_POST['direct_mkt']) && $_POST['direct_mkt']!="") $direct_mkt=1; else $direct_mkt=0;
	
	include('config.php');
		$query="SELECT id FROM bank WHERE name='$bank_name'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$bank_id=$row[0];
		if($bank_id=='') $bank_id=0;
		$query="SELECT count(id) FROM `userprofile` WHERE `username`='$username'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$id_count=$row[0];

	if($id_count==0){	
		$query="INSERT INTO `userprofile` (`username`,`password`,`change_pw`,`direct_mkt`,`emp_name`,`nic`,`mobile`,`bank_id`,`bank_branch`,`bank_ac`,`sub_system`,`status`) VALUES ('$username','$password','$change_pw','$direct_mkt','$emp_name','$nic','$mobile','$bank_id','$bank_branch','$bank_ac','$sub_system','0')";
		$result=mysqli_query($conn,$query);
		if($result){
			$message='User was Added Successfully!';
			return true;
		}else{
			$message='User could not be Added!';
			return false;
		}
	}else{
			$message='This User is already exist !';
			return false;
	}
}


function updateUser(){
	global $message;
	$user_id=$_POST['user_id'];
	$username=strtolower($_POST['user_name']);
	$password=$_POST['passhash'];
	$emp_name=$_POST['emp_name'];
	$nic=$_POST['nic'];
	$mobile=$_POST['mobile'];
	$bank_name=$_POST['bank_name'];
	$bank_branch=$_POST['bank_branch'];
	$bank_ac=$_POST['bank_ac'];
	if(isset($_POST['change_pw']) && $_POST['change_pw']!="") $change_pw=1; else $change_pw=0;
	if(isset($_POST['direct_mkt']) && $_POST['direct_mkt']!="") $direct_mkt=1; else $direct_mkt=0;
	if($password!=md5('')){
		$pass_qry=",`password`='$password'";
		$chpass_qry=",`change_pw`='$change_pw'";
	}else{
		$pass_qry="";
		$chpass_qry="";
	}

	include('config.php');
		$query="SELECT id FROM bank WHERE name='$bank_name'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$bank_id=$row[0];
		if($bank_id=='') $bank_id=0;
		$query="SELECT count(id) FROM `userprofile` WHERE `username`='$username' AND id!='$user_id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$id_count=$row[0];


	if($id_count==0){	
		$query="UPDATE `userprofile` SET `username`='$username',`direct_mkt`='$direct_mkt',`emp_name`='$emp_name',`nic`='$nic',`mobile`='$mobile',`bank_id`='$bank_id',`bank_branch`='$bank_branch',`bank_ac`='$bank_ac' $pass_qry $chpass_qry WHERE `id`='$user_id'";
		$result=mysqli_query($conn,$query);
		if($result){
			$message='User was Updated Successfully!';
			return true;
		}else{
			$message='User could not be Updated!';
			return false;
		}
	}else{
			$message='This User is already exist !';
			return false;
	}
}

function updatePermission(){
	include('config.php');

	$user=substr($_REQUEST['id'],0,strpos($_REQUEST['id'],'|'));
	$function=substr($_REQUEST['id'],(strpos($_REQUEST['id'],'|')+1),strlen($_REQUEST['id']));
	$to_do=$_REQUEST['permission'];
	
	if($to_do=='add'){
		$query="INSERT INTO `permission` (`user`,`function`) VALUES ('$user','$function')";
		$result=mysqli_query($conn,$query);
	}
	
	if($to_do=='del'){
		$query="DELETE FROM `permission` WHERE `user`='$user' AND `function`='$function'";
		$result=mysqli_query($conn,$query);
	}
	if($result) return 'Done'; else return 'Error';
}

function updateDevicecheck(){
	include('config.php');
	$user=substr($_REQUEST['id'],0,strpos($_REQUEST['id'],'|'));
	$to_do=$_REQUEST['devicecheck'];
	if($to_do=='add'){
		$query="UPDATE `userprofile` SET `device_restrict`='1' WHERE `id`='$user'";
		$result=mysqli_query($conn,$query);
	}
	if($to_do=='del'){
		$query="UPDATE `userprofile` SET `device_restrict`='0' WHERE `id`='$user'";
		$result=mysqli_query($conn,$query);
	}
	if($result) return 'Done'; else return 'Error';
}

function updateTimecheck(){
	include('config.php');
	$user=substr($_REQUEST['id'],0,strpos($_REQUEST['id'],'|'));
	$to_do=$_REQUEST['timecheck'];
	if($to_do=='add'){
		$query="UPDATE `userprofile` SET `time_restrict`='1' WHERE `id`='$user'";
		$result=mysqli_query($conn,$query);
	}
	if($to_do=='del'){
		$query="UPDATE `userprofile` SET `time_restrict`='0' WHERE `id`='$user'";
		$result=mysqli_query($conn,$query);
	}
	if($result) return 'Done'; else return 'Error';
}

function updateMobileRep(){
	include('config.php');
	$user=substr($_REQUEST['id'],0,strpos($_REQUEST['id'],'|'));
	$to_do=$_REQUEST['mobilerep'];
	if($to_do=='add'){
		$query="UPDATE `userprofile` SET `mobile_rep`='1' WHERE `id`='$user'";
		$result=mysqli_query($conn,$query);
	}
	if($to_do=='del'){
		$query="UPDATE `userprofile` SET `mobile_rep`='0' WHERE `id`='$user'";
		$result=mysqli_query($conn,$query);
	}
	if($result) return 'Done'; else return 'Error';
}

function updateStoreaso(){
	global $message;
	include('config.php');
	$message='User was Added to the Store Successfully!';

	$userID=substr($_REQUEST['id'],0,strpos($_REQUEST['id'],'s'));
	$store=$_GET['store'];
	if($store==0) 	$message='User was Removed from All Stores!';
	
		$query="UPDATE `userprofile` SET `store`='$store' WHERE `id`='$userID'";
		$result=mysqli_query($conn,$query);
		
	if($result){
		return true;
	}else{
		$message='User could not be Added to the Store!';
		return false;
	}
}

function updateMapInv(){
	global $message;
	include('config.php');
	$message='User was Mapped to the Inventory Successfully!';

	$userID=$_GET['id'];
	$mapped_inventory=$_GET['mapinv'];
	if($mapped_inventory==0) 	$message='User was Set to Default Inventory!';
	
		$query="UPDATE `userprofile` SET `mapped_inventory`='$mapped_inventory' WHERE `id`='$userID'";
		$result=mysqli_query($conn,$query);
		
	if($result){
		return true;
	}else{
		$message='User could not be Mapped to the Inventory !';
		return false;
	}
}


function addCategory(){
	global $message;
	$category=ucfirst($_POST['category']);
	$sub_sys=$_POST['sub_sys'];
	$out=true;
	include('config.php');
	
	if(($category=='')||($sub_sys=='')){ $out=false; $msg='Category Name and Sub System Must be Filled!'; }
	
	if($out){
		$query="SELECT count(id) FROM `item_category` WHERE `name`='$category' AND `sub_system`='$sub_sys'";
		$result=mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		if($row[0]>0){ $out=false; $msg='This Category is already exist !'; }
	}
	
	if($out){	
		$query="INSERT INTO `item_category` (`name`,`sub_system`) VALUES ('$category','$sub_sys')";
		$result=mysqli_query($conn,$query);
		if($result){ $msg='Category was Added Successfully!'; }else{ $out=false; $msg='Category could not be Added!'; }
	}
	
	$message=$msg;
	return $out;
}


function deleteCategory(){
	global $message;
	$category_id=strtoupper($_REQUEST['id']);

	include('config.php');
	
		$query="SELECT count(id) FROM `inventory_items` WHERE `category`='$category_id'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$id=$row[0];
		} 

	if($id==0){	
		$query="DELETE FROM `item_category` WHERE `id`='$category_id'";
		$result=mysqli_query($conn,$query);
		
		if($result){
			$message='Category was Deleted Successfully!';
			return true;
		}else{
			$message='Category could not be Deleted !';
			return false;
		}
	}else{
			$message='Inventory items are bind with this Category. Please change them before deleting !';
			return false;
	}
}

//------------------------------------System Settings-----------------------------//

function getSettings(){
global $inventory_temp_fagmented,$time_from,$time_to,$time_now,$precal_err_inv;
	$precal_err_inv=array();
	include('config.php');
	$result = mysqli_query($conn,"SELECT COUNT(itq_id) as `count` FROM inventory_temp");
	$row = mysqli_fetch_assoc($result);
	$inventory_temp_fagmented=$row['count'];
	
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='time_from'");
	$row = mysqli_fetch_assoc($result);
	$time_from=$row['value'];
	
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='time_to'");
	$row = mysqli_fetch_assoc($result);
	$time_to=$row['value'];
	
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone=$row['value'];
	$time_now=date("Y-m-d H:i:s",time()+(60*60*$timezone));
	
	$query="SELECT bm1.invoice_no FROM bill_main bm1, (
				SELECT invoice_no,SUM(qty * unit_price) AS `total` FROM bill GROUP BY invoice_no
			) AS bi2
			WHERE bi2.invoice_no=bm1.invoice_no AND bm1.`status`!=0 AND bm1.`lock`=1
			AND bi2.total!=(bm1.`invoice_+total` + bm1.`invoice_-total`)";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$precal_err_inv[]=$row[0];
	} 
}

function preCalBill(){
	global $message;
	include('config.php');

	$query="SELECT bm1.invoice_no FROM bill_main bm1, (
				SELECT invoice_no,SUM(qty * unit_price) AS `total` FROM bill GROUP BY invoice_no
			) AS bi2
			WHERE bi2.invoice_no=bm1.invoice_no AND bm1.`status`!=0 AND bm1.`lock`=1
			AND bi2.total!=(bm1.`invoice_+total` + bm1.`invoice_-total`)";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$precal_err_inv=$row[0];
		
		$result2 = mysqli_query($conn,"SELECT SUM(qty*unit_price) AS `total` FROM bill WHERE qty>0 AND invoice_no='$precal_err_inv'");
		$row2 = mysqli_fetch_assoc($result2);
		$up_total=$row2['total'];
		if($up_total=='') $up_total=0;
		$result2 = mysqli_query($conn,"SELECT SUM(qty*unit_price) AS `total` FROM bill WHERE qty<0 AND invoice_no='$precal_err_inv'");
		$row2 = mysqli_fetch_assoc($result2);
		$down_total=$row2['total'];
		if($down_total=='') $down_total=0;
		$query3="UPDATE bill_main SET `invoice_+total`='$up_total', `invoice_-total`='$down_total' WHERE `invoice_no`='$precal_err_inv'";
		$result3=mysqli_query($conn,$query3);
	} 

	if($result3){
		$message='Pre Calculation was Corrected Successfully!';
		return true;
	}else{
		$message='Error: Pre Calculation could not be Done!';
		return false;
	}
}

function clearInvtemp(){
	global $message;
	include('config.php');

	$result = mysqli_query($conn,"SELECT MAX(id) as maxid FROM inventory_temp");
	$row = mysqli_fetch_assoc($result);
	$lastid=$row['maxid'];

	$query2="DELETE FROM `inventory_temp` WHERE `id` != '$lastid'";
	$result2=mysqli_query($conn,$query2);
		
	if($result2){
		$message='Database was cleared Successfully!';
		return true;
	}else{
		$message='Database could not be cleared!';
		return false;
	}

}

function updateTime(){
	global $message;
	$time_from=$_POST['time_from'];
	$time_to=$_POST['time_to'];
	if(is_numeric($time_from)) $update=true; else $update=false;
	if(is_numeric($time_to)) $update=true; else $update=false;
	if($update){
		if((0<$time_from)&&($time_from<24)) $update=true; else $update=false;
		if((0<$time_to)&&($time_tp<24)) $update=true; else $update=false;
		if($time_from<$time_to) $update=true; else $update=false;
	}
	include('config.php');
	if($update){
		$query1="UPDATE `settings` SET `value`='$time_from' WHERE `setting`='time_from'";
		$result1=mysqli_query($conn,$query1);
		$query2="UPDATE `settings` SET `value`='$time_to' WHERE `setting`='time_to'";
		$result2=mysqli_query($conn,$query2);
		if($result1 && $result2){
			$message='Time was Updated Successfully!';
			return true;
		}else{
			$message='Time could not be Updated!';
			return false;
		}
	}else{
		$message='Invalid Time';
		return false;
	}
}
//----------------------------------------Devices---------------------------------//

function getDevices(){
	global $dev_id,$dev_name,$dev_exp,$dev_status,$one_dev_name,$usr_id,$usr_name;
	$one_dev_name='';
	$dev_id=array();
	include('config.php');
	$query="SELECT id,name,expiration,`status` FROM devices ORDER BY `status` DESC, name";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$dev_id[]=$row[0];
		$dev_name[]=$row[1];
		$dev_exp[]=$row[2];
		$dev_status[]=$row[3];
	} 
	
	if(isset($_GET['dev_id'])){
		$edit_dev_id=$_GET['dev_id'];
		$result = mysqli_query($conn,"SELECT name FROM devices WHERE id='$edit_dev_id'");
		$row = mysqli_fetch_assoc($result);
		$one_dev_name=$row['name'];
	}
	
	$query="SELECT id,username FROM userprofile WHERE `status`=0";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$usr_id[]=$row[0];
		$usr_name[]=ucfirst($row[1]);
	} 
}

function addDevice($sub_system){
	global $message;
	$dev_name=$_POST['device_name'];
	$dev_key=md5($dev_name.time());
	$today=dateNow();
	$dev_exp=date("Y-m-d",time()+(60*60*24*365));
	include('config.php');
	$result = mysqli_query($conn,"SELECT COUNT(id) as `count` FROM devices WHERE name='$dev_name'");
	$row = mysqli_fetch_assoc($result);
	$devcount=$row['count'];
	if($devcount==0){
		$query2="INSERT INTO `devices` (`name`,`key`,`start_date`,`expiration`,`auto_assign`,`sub_system`,`status`) VALUES ('$dev_name','$dev_key','$today','$dev_exp','1','$sub_system','1')";		
		$result2=mysqli_query($conn,$query2);
			
		if($result2){
			$message='Device was Created Successfully!';
			return true;
		}else{
			$message='Device could not be Created!';
			return false;
		}
	}else{
		$message='The Device is Already Exist!';
		return false;
	}
}

function changeDevice(){
	global $message;
	$dev_id=$_REQUEST['id'];
	if($_REQUEST['stat']=='on') $dev_stat=1;
	if($_REQUEST['stat']=='off') $dev_stat=0;
	include('config.php');
	$query="UPDATE `devices` SET `status`='$dev_stat' WHERE `id`='$dev_id'";
	$result=mysqli_query($conn,$query);
			
	if($result){
		$message='Device was Updated Successfully!';
		return true;
	}else{
		$message='Device could not be Updated !';
		return false;
	}
}

function reKeyDevice(){
	global $message;
	$dev_id=$_REQUEST['id'];
	$dev_key=md5($dev_id.time().rand());
	$today=dateNow();
	$dev_exp=date("Y-m-d",time()+(60*60*24*365));
	include('config.php');
	$query="UPDATE `devices` SET `key`='$dev_key',`start_date`='$today',`expiration`='$dev_exp',`auto_assign`='1' WHERE `id`='$dev_id'";
	$result=mysqli_query($conn,$query);
			
	if($result){
		$message='New Key was Added Successfully!';
		return true;
	}else{
		$message='Key Could not be Changed !';
		return false;
	}
}

function renameDevice(){
	global $message;
	$dev_id=$_REQUEST['dev_id'];
	$dev_name=$_REQUEST['device_name'];
	include('config.php');
	$query="UPDATE `devices` SET `name`='$dev_name' WHERE `id`='$dev_id'";
	$result=mysqli_query($conn,$query);
	if($result){
		$message='Device was Renamed Successfully!';
		return true;
	}else{
		$message='Device could not be Renamed !';
		return false;
	}
}

function getDevicePermission(){
	global $per_id,$per_dev,$per_user;
	$per_id=array();
	include('config.php');
	$query="SELECT dp.id,dv.name,up.username FROM device_permission dp, devices dv, userprofile up WHERE dp.device=dv.id AND dp.`user`=up.id";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$per_id[]=$row[0];
		$per_dev[]=$row[1];
		$per_user[]=ucfirst($row[2]);
	}
}

function getPermissionGrid(){
	global $user_name,$dp_id,$user_id;
	$dp_id=array();
	if(isset($_REQUEST['user_name'])){
		$user_name=$_REQUEST['user_name'];
		
		include('config.php');
		$query="SELECT id FROM userprofile WHERE username='$user_name'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$user_id=$row[0]; 			

		$query="SELECT device FROM device_permission WHERE `user`='$user_id'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$dp_id[]=$row[0];
		}
	}
}

function addGridPermission(){
	global $message,$user_name;
	$user_id=$_POST['grid_user_id'];
	$result2=false;
	
	include('config.php');
	$query="SELECT username FROM userprofile WHERE id='$user_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$user_name=$row[0]; 			
	
	$query="DELETE FROM device_permission WHERE `user`='$user_id'";
	mysqli_query($conn,$query);

	$query="SELECT id FROM devices";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$dp_id=$row[0];
		if(isset($_POST["per_$dp_id"])){
			$query="INSERT INTO `device_permission` (`device`,`user`) VALUES ('$dp_id','$user_id')";	
			$result2=mysqli_query($conn,$query);
		}
	}
	if($result2){
		$message='Permission was Added Successfully!';
		return true;
	}else{
		$message='Permission could not be Added !';
		return false;
	}
}

function addDevicePermission(){
	global $message;
	$dev_id=$_REQUEST['per_dev'];
	$usr_id=$_REQUEST['per_usr'];
	include('config.php');
	$query="SELECT COUNT(id) FROM device_permission WHERE `device`='$dev_id' AND `user`='$usr_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$count=$row[0]; 			
	if($count==0){
		$query="INSERT INTO `device_permission` (`device`,`user`) VALUES ('$dev_id','$usr_id')";		
		$result=mysqli_query($conn,$query);
		if($result){
			$message='Permission was Added Successfully!';
			return true;
		}else{
			$message='Permission could not be Added !';
			return false;
		}
	}else{
		$message='Same Permission was Alredy Given !';
		return false;
	}
}

function delDevicePermission(){
	global $message;
	$id=$_REQUEST['id'];
	include('config.php');
	$query="DELETE FROM `device_permission` WHERE id='$id'";		
	$result=mysqli_query($conn,$query);
	if($result){
		$message='Permission was Removed Successfully!';
		return true;
	}else{
		$message='Permission could not be Removed !';
		return false;
	}
}
//---------------------------------------Group Allocation--------------------------------------------//
/*
function getMKTUser(){
global $mktuser_id,$mktuser_name;
	$mktuser_id=array();
	include('config.php');

	$query="SELECT DISTINCT up.id,up.username FROM userprofile up, permission pe, `function` fn WHERE up.id=pe.`user` AND pe.`function`=fn.id AND up.`status`=0 AND fn.`name`='Marketing'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$mktuser_id[]=$row[0];
		$mktuser_name[]=ucfirst($row[1]);
	}
}
*/

function getAllocation(){
global $up2_id,$up2_name,$up2_groups,$user_id,$gp_id,$gp_name,$allo_groupid,$allo_groupname;
	if(isset($_GET['user_id'])) $user_id=$_GET['user_id']; else $user_id='';
	$gp_id=$allo_groupid=$allo_groupname=array();

	include('config.php');

	$query="SELECT up.id,up.`username`,COUNT(ug.id) FROM userprofile up LEFT JOIN user_to_group ug ON up.id=ug.`user` WHERE up.`status`='0' GROUP BY up.id ORDER BY up.`username`";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$up2_id[]=$row[0];
		$up2_name[]=$row[1];
		$up2_groups[]=$row[2];
	}
	
	$query="SELECT id,`name` FROM cust_group ";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$gp_id[]=$row[0];
		$gp_name[]=$row[1];
	}
	
	if($user_id!=''){
		$query="SELECT cg.id,cg.`name` FROM user_to_group ug, cust_group cg WHERE ug.`group`=cg.id AND ug.`user`='$user_id'";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$allo_groupid[]=$row[0];
			$allo_groupname[]=$row[1];
		}
	}
}

function addGroupAllocation(){
global $message;
	$user_id=$_POST['user_id'];
	$group_id=$_POST['group_id'];
	$out=true;
	$message='The Group was Allocated Successfully';
	if(($user_id=='')||($group_id=='')){ $message='Please Select Both a User and a Group'; $out=false; }
	
	if($out){
		include('config.php');
		$query="INSERT INTO `user_to_group` (`user`,`group`) VALUES ('$user_id','$group_id')";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message='Error: The Group Could Not be Allocated'; $out=false; }
	}
	return $out;
	
}

function removeGroupAllocation(){
global $message;
	$user_id=$_GET['user_id'];
	$group_id=$_GET['group_id'];
	$out=true;
	$message='The Group Allocation was Removed Successfully';
	if(($user_id=='')||($group_id=='')){ $message='Error: Invalid Request'; $out=false; }
	
	if($out){
		include('config.php');
		$query="DELETE FROM `user_to_group` WHERE `user`='$user_id' AND `group`='$group_id'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $message='Error: The Group Allocation Could Not be Removed'; $out=false; }
	}
	return $out;
	
}
//---------------------------------------EDIT BILL--------------------------------------------//
function billStatus($status_id){
	switch ($status_id) {
		case '0' :
		$status_name='Deleted';
		break;
		case '1' :
		$status_name='Billed';
		break;
		case '2' :
		$status_name='Seen';
		break;
		case '3' :
		$status_name='Packed';
		break;
		case '4' :
		$status_name='Shipped';
		break;
		case '5' :
		$status_name='Deliverd';
		break;
	}
	return $status_name;
}

function searchBill(){
	global $bi_id,$bill_no,$bm_date,$bm_total,$bm_status,$bm_lock,$bm_sms,$st_name,$up_name,$cu_name,$itm_des,$bi_qty,$bi_uprice,$bi_discount,$cash_amount,$chque_amount,$chq0_fullNo,$chequedate;
	if(isset($_GET['bill_no'])){
	$itm_des=$bi_qty=$bi_uprice=$bi_discount=array();
	$cash_amount=$chque_amount=0;
	
		$bill_no=$_GET['bill_no'];
	 	include('config.php');
		$query1="SELECT COUNT(invoice_no) FROM bill_main WHERE invoice_no='$bill_no'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$bill_no_validate=$row1[0]; 
		
		if($bill_no_validate==1){
			$query="SELECT bm.billed_timestamp,bm.`invoice_+total`,bm.`invoice_-total`,bm.`status`,bm.`lock`,bm.sms,st.name,up.username,cu.name,itm.description,bi.qty,bi.unit_price,bi.discount,bi.id,bm.`cust` FROM bill_main bm, bill bi, cust cu, userprofile up, stores st, inventory_items itm WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`=cu.id AND bm.billed_by=up.id AND bm.store=st.id AND bi.item=itm.id AND bm.invoice_no='$bill_no'";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_array($result)){
				$bm_date=$row[0];
				$bm_total=$row[1]+$row[2];
				$bm_status=billStatus($row[3]);
				if($row[4]==1) $bm_lock='Locked'; else $bm_lock='Unlocked';
				if($row[5]==1) $bm_sms='SMS Sent'; else $bm_sms='SMS not Sent';
				$st_name=$row[6];
				$up_name=$row[7];
				$cu_name=$row[8];
				$itm_des[]=$row[9];
				$bi_qty[]=$row[10];
				$bi_uprice[]=$row[11];
				$bi_discount[]=$row[12];
				$bi_id[]=$row[13];
				$bm_cust=$row[14];
			} 
			$query="SELECT py.payment_type,py.amount,py.chque_no,bk.bank_code,py.chque_branch,py.chque_date FROM payment py LEFT JOIN bank bk ON py.chque_bank=bk.id WHERE py.chque_return=0 AND py.`status`=0 AND py.invoice_no='$bill_no' AND py.`cust`='$bm_cust'";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_array($result)){
				if($row[0]==1) $cash_amount+=$row[1];
				if($row[0]==2) $chque_amount+=$row[1];
				$chq0_fullNo='[ Cheque No: '.$row[2].'-'.$row[3].'-'.$row[4].' ]';
				$chequedate=$row[5];
			}
		}					
	}
}

function updateBill($systemid){
global $message;
	$bill_id=$_GET['bill_id'];
	$new_discount=$_GET['new_discount'];
	$bill_no=$_GET['bill_no'];
	$result2=false;
	
	if($systemid==2){ 
		include('config.php');
			$query="SELECT unit_price,discount FROM bill WHERE id='$bill_id'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$bi_uprice=$row[0]; 
			$bi_discount=$row[1];
			
			$new_uprice=$bi_uprice+$bi_discount-$new_discount;
			
			$query1="UPDATE `bill` SET `discount`='$new_discount',`unit_price`='$new_uprice' WHERE `id`='$bill_id'";
			$result1=mysqli_query($conn,$query1);
			if($result1){
				$query="SELECT SUM(qty*unit_price) FROM bill WHERE qty>0 AND invoice_no='$bill_no'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$plus_total=$row[0]; 
				if($plus_total=='') $plus_total=0;
				$query="SELECT SUM(qty*unit_price) FROM bill WHERE qty<0 AND invoice_no='$bill_no'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$minus_total=$row[0]; 
				if($minus_total=='') $minus_total=0;
				
				$query2="UPDATE `bill_main` SET `invoice_+total`='$plus_total' WHERE `invoice_no`='$bill_no'";
				$result2=mysqli_query($conn,$query2);
				$query2="UPDATE `bill_main` SET `invoice_-total`='$minus_total' WHERE `invoice_no`='$bill_no'";
				$result2=mysqli_query($conn,$query2);
			}
	}
	if($result2){
		$message='Discount was Updated Successfully!';
		return true;
	}else{
		$message='Discount Could Not be Updated !';
		return false;
	}
}


?>