<?php
function timeNow(){
	include('../config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone=$row['value'];
	$time_now=date("Y-m-d H:i:s",time()+(60*60*$timezone));
	return $time_now;
}

function dateNow(){
	include('../config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone=$row['value'];
	$date_now=date("Y-m-d",time()+(60*60*$timezone));
	return $date_now;
}

function inf_company(){
	include('../config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='company_name'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_from_email(){
	include('../config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='from_email'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_to_email(){
	include('../config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='to_email'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_web(){
	include('../config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='web'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_url_primary(){
	include('../config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='url_primary'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_url_backup(){
	include('../config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='url_backup'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}

function timeCheck($userid){
	include('../config.php');

	$result1 = mysqli_query($conn,"SELECT time_restrict FROM userprofile WHERE id='$userid'");
	$row = mysqli_fetch_assoc($result1);
	$timecheck=$row['time_restrict'];
	if($timecheck==1){
		$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='timezone'");
		$row = mysqli_fetch_assoc($result);
		$timezone=$row['value'];
	
		$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='time_from'");
		$row = mysqli_fetch_assoc($result);
		$time_from=$row['value'];
		
		$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='time_to'");
		$row = mysqli_fetch_assoc($result);
		$time_to=$row['value'];
	
		$hour_now=date("H",time()+(60*60*$timezone));
		if(($time_from<$hour_now) && ($hour_now<$time_to))	$time_permit=true; else $time_permit=false;
	}else $time_permit=true;
	if(!$time_permit) header('Location: index.php?components=authenticate&action=logout&type=1');
	
} 

function deviceCheck($userid){
	include('../config.php');
	$result1 = mysqli_query($conn,"SELECT device_restrict FROM userprofile WHERE id='$userid'");
	$row = mysqli_fetch_assoc($result1);
	$devicecheck=$row['device_restrict'];
	if($devicecheck==1){
		$today=date("Y-m-d",time());
		if(isset($_COOKIE['rsaid']))	$rsaid=$_COOKIE['rsaid']; else $rsaid='hhdjdhdaa44hd';
		$result = mysqli_query($conn,"SELECT count(dp.id) as `count` FROM devices dv, device_permission dp WHERE dp.device=dv.id AND dv.`key`='$rsaid' AND dv.expiration>'$today' AND dp.`user`='$userid'");
		$row = mysqli_fetch_assoc($result);
		$count=$row['count'];
		if($count==0) header('Location: index.php?components=authenticate&action=logout&type=2');
	}
}

?>