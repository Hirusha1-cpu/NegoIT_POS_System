<?php
function generateToken(){
global $token;
	$timestamp=time();
	$token=md5($timestamp);
	include('config.php');
		$query="SELECT MAX(id) FROM onetime_token";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$max_id=$row[0];
		$query="SELECT MIN(id) FROM onetime_token";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$min_id=$row[0];
		$next_id=$max_id+1;

		$query="UPDATE `onetime_token` SET `id`='$next_id',`token`='$token' WHERE id='$min_id'";
		mysqli_query($conn,$query);

}

// updated by nirmal 02_03_2022
function login(){
	global $message,$type,$force_check_in;
	$out=true;
	$message='Invalid Username or Password!';
	$timenow=timeNow();
	$today=substr($timenow,0,10);
	$force_check_in=false;
	$id=$authentication=$function=0;
	$store_name=$store_shopname=$user='';

	if(isset($_POST['uname'])&&isset($_POST['onetime_pass'])){
		$user=$_POST['uname'];
		$onetime_pass=$_POST['onetime_pass'];
		include('config.php');
		$query="SELECT `password`,`mobile_rep` FROM userprofile WHERE `username`='$user'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		if($row == null){
			$out = false;
		}
		if($out){
			$pass=$row[0];
			$mobile_rep=$row[1];
			$query="SELECT `token` FROM onetime_token ORDER BY `id` DESC";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_array($result)){
				$token=$row[0];
				if($onetime_pass==md5($pass.$token)){
					$authentication++;
				}
			}
		}
	}else{
		$out = false;
	}
		
	if(($out) && ($authentication>0)){
		$query="SELECT up.`id`,up.`username`,pe.`function`,fn.`name`,up.`store`,st.`retail`,st.`name`,st.`shop_name`,up.`direct_mkt`,st.`sub_system` FROM permission pe, `function` fn, `userprofile` up LEFT JOIN stores st ON up.`store`=st.`id` WHERE fn.`id`=pe.`function` AND pe.`user`=up.`id` AND up.`username`='$user' AND fn.`status`=1 AND up.`status`='0'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$id=$row[0];
			$user=$row[1];
			$function_id=$row[2];
			if($row[2] > 0){
				$function++;
			}
			$function_name=strtolower(str_replace(' ','_',$row[3]));
			$store=$row[4];
			$retail=$row[5];
			$store_name=$row[6];
			$store_shopname=$row[7];
			$direct_mkt=$row[8];
			$sub_system=$row[9];
			setcookie($function_name,$id, time()+3600*10);
		}
		if($store>0){
			setcookie("store",$store, time()+3600*10);
			setcookie("store_name",$store_name, time()+3600*10);
			setcookie("store_shop_name",$store_shopname, time()+3600*10);
		}
	}

	if(($id>0) && ($out)){
		$query="SELECT COUNT(id) FROM check_in_out WHERE DATE(`in_datetime`)='$today' AND user_id='$id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$checkin_count=$row[0];
		
		if(($mobile_rep==1)&&($checkin_count==0)) $force_check_in=true;

		$device_id='NULL';
		if(isset($_COOKIE['rsaid'])){
			$rsaid=$_COOKIE['rsaid'];
			$query="SELECT `id` FROM devices WHERE `key`='$rsaid'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$device_id="'".$row[0]."'";
		}

		//------------------Login Audit------------------------------//
		$query="INSERT INTO `login_audit` (`login_time`,`user`,`device`) VALUE ('$timenow','$id',$device_id)";
		mysqli_query($conn,$query);
		//----------------------------------------------------------//
		$sms_data=json_decode(sms_credential($sub_system));
		$sms_balance=$sms_data->{"balance"};
		setcookie("sms_balance",$sms_balance, time()+3600*10);
		if($direct_mkt==1) $retail=1;
		$keyhash=md5(time()+$id);
		setcookie("user_id",$id, time()+3600*10);
		setcookie("user",$user, time()+3600*10);
		setcookie("userkey",$keyhash, time()+3600*10);
		setcookie("direct_mkt",$direct_mkt, time()+3600*10);
		setcookie("fastprint",'off', time()+3600*10);
		setcookie("retail",$retail, time()+3600*10);
		setcookie("sub_system",$sub_system, time()+3600*10);
		$_SESSION["userkey"] = $keyhash;
		subsystemTheme($sub_system,$store,true);

		$result = mysqli_query($conn,"SELECT `value` FROM settings WHERE setting='commission_on_billing'");
		$row = mysqli_fetch_assoc($result);
		setcookie("commission_on_billing",$row['value'], time()+3600*10);

		$result = mysqli_query($conn,"SELECT `value` FROM settings WHERE setting='cus_details_on_billing'");
		$row = mysqli_fetch_assoc($result);
		setcookie("cus_details_on_billing",$row['value'], time()+3600*10);

		// Device check for register device. (first time user, give chance user to register user device, made by settings device check and device allocation)
		$query = "SELECT d.`id`, d.`name`, d.`key`, d.`expiration` FROM devices d, device_permission dp WHERE d.`id` = dp.`device` AND d.`auto_assign` = '1' AND d.`sub_system` = '$sub_system' AND d.`status` = '1' AND d.`start_date` = '".dateNow()."' AND dp.`user` = '$id'";
		$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
		$numResults = mysqli_num_rows(mysqli_query($conn,$query));
		// user has restrcited device. it will be this login device
		if($numResults > 0){
			// user has permission to save device for himself
			$date1 = date_create(dateNow());
			$date2 = date_create($row['expiration']);
			$dev_key = $row['key'];
			$device_id = $row['id'];
			$diff0 = date_diff($date1, $date2);
			$diff = $diff0->format("%a");
			if (setcookie("rsaid", $dev_key, time() + 86400 * $diff)) {
				$query = "UPDATE devices SET auto_assign = '0' WHERE id = '$device_id'";
				$result1=mysqli_query($conn,$query);
				if(!$result1){
					$message = 'Device was Registerd Un-successfull!';
					$out = false;
				}
			} else {
				$message = 'Device could not be Registerd!';
				$out = false;
			}
		}
	}else{
		$out = false;
	}
	return $out;
}

// update by nirmal 14_02_2022
function logout(){
	include('config.php');
	global $message;
	$query="SELECT id,name FROM `function`";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$function_id=$row[0];
		$function_name=strtolower(str_replace(' ','_',$row[1]));
		if(isset($_COOKIE[$function_name])){
			setcookie($function_name,'', time()-3600*10);
		}
	} 
	setcookie("sms_balance",'', time()-3600*10);
	setcookie("user_id",'', time()-3600*10);
	setcookie("user",'', time()-3600*10);
	setcookie("store",'', time()-3600*10);
	setcookie("store_name",'', time()-3600*10);
	setcookie("store_shop_name",'', time()-3600*10);
	setcookie("userkey",'', time()-3600*10);
	setcookie("direct_mkt",'', time()-3600*10);
	setcookie("fastprint",'', time()-3600*10);
	setcookie("retail",'', time()-3600*10);
	setcookie("sub_system",'', time()-3600*10);
	setcookie("theme_color",'', time()-3600*10);
	setcookie("theme_color_m1",'', time()-3600*10);
	setcookie("theme_color_m2",'', time()-3600*10);
	setcookie("commission_on_billing",'', time()-3600*10);
	session_unset(); 
	session_destroy(); 
	if(isset($_GET['type'])){
		if($_GET['type']==1) $message='Working Hours Has Expired!';
		if($_GET['type']==2) $message='This Device is not Permited!';
	}
}

function changePassword(){
	global $message;
	$msg='Old Password is Incorrect!';
	$user_id=$_COOKIE['user_id'];
	$onetime_pass=$_POST['onetime_pass'];
	$new_password=$_POST['passhash'];
	$authentication=0;
		include('config.php');
		$query="SELECT password FROM userprofile WHERE `id`='$user_id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$pass=$row[0];
		$query="SELECT token FROM onetime_token ORDER BY id DESC";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$token=$row[0];
			if($onetime_pass==md5($pass.$token)){
				$authentication++;
			}
		}
		if($pass==$new_password){
			$msg='Old Password and New Password Must Not be the SAME';
			$authentication=0;
		}
		if($authentication>0){
			$query1="UPDATE userprofile SET `password`='$new_password', `change_pw`='0' WHERE id='$user_id'";
			$result1=mysqli_query($conn,$query1);
			if($result1){
				$message='Password Was Changed Successfully';	
				return true;
			}else{
				$message='Error: Password Could Not Be Changed!';	
				return false;
			}
		}else{
			$message=$msg;	
			return false;
		}
}

function checkInSMS($user_id,$time){
	if(isset($_COOKIE['sub_system'])) $sub_system=$_COOKIE['sub_system']; else $sub_system=0;
	$sms_data=json_decode(sms_credential($sub_system));
	$sms_user=$sms_data->{"user"};
	$sms_pass=$sms_data->{"pass"};
	$sms_balance=$sms_data->{"balance"};
	$sms_device=$sms_data->{"device"};
	$sms_balance--;
	$mobile='0763158050';
	
	include('config.php');
	$query="SELECT username FROM userprofile WHERE id='$user_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$username=ucwords($row[0]);
		
	$message ="CHECK+IN-NLC-$username-NLC-$time";
	$text = urlencode($message);
	$url = "http://www.textit.biz/sendmsg/?id=$sms_user&pw=$sms_pass&eco=Y&to=$mobile&text=$text";
	$ret = file($url);
	$res= explode(":",$ret[0]);
	if (trim($res[0])=="OK") $mailstatus=true; else $mailstatus=false;
	if($mailstatus){
		if(set_sms_balance($sub_system,$sms_balance))	$msg='SMS Sent<hr />'; 	else 	$msg='Database Cound Not be Updated<hr />'; 
	}else $msg='SMS Could not be Sent<hr />';
}

function setCheckIn(){
	global $message;
	$gps_x=$_POST['gps_x'];
	$gps_y=$_POST['gps_y'];
	$user_id=$_COOKIE['user_id'];	
	$time_now=timeNow();
	$systemid=inf_systemid(1);
	$date_now=substr($time_now,0,10);
	$message='Error: Could Not Be Checked In!';	
		
	include('config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='api_geocoding'");
	$row = mysqli_fetch_assoc($result);
	$api_key2=$row['value'];
	
	$query="SELECT count(id) FROM check_in_out WHERE `user_id`='$user_id' AND date(in_datetime)='$date_now'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$count=$row[0];

	if($count==0){
		$data = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$gps_x.','.$gps_y.'&key='.$api_key2);
		$decodedData=json_decode($data);
		$city=$decodedData->{"results"}[1]->{"formatted_address"};
	
		$query="INSERT INTO `check_in_out` (`user_id`,`in_datetime`,`in_gps_x`,`in_gps_y`,`in_city`) VALUES ('$user_id','$time_now','$gps_x','$gps_y','$city')";
		$result=mysqli_query($conn,$query);
	}else{ $result=false; $message='Already Checked-In for Today';	}
	if($result){
		if($systemid==1) checkInSMS($user_id,$time_now);
		$message='Checked In Time: '.substr($time_now,11,5);	
		return true;
	}else{
		return false;
	}
}

?>