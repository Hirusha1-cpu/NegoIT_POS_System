<?php
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
function generateToken(){
	global $token;
	$timestamp=time();
	$token=md5($timestamp);
	include('../config.php');
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


// added by nirmal 18_03_2022
function login(){
	global $message;
	$out=false;
	$message='Invalid Username or Password!';
	$authentication=$token=0;

	if(isset($_POST['uname'])&&isset($_POST['onetime_pass'])){
		$user=$_REQUEST['uname'];
		$onetime_pass=$_POST['onetime_pass'];
		include('../config.php');
		$query="SELECT `id`,`username`,`password` FROM userprofile_backend WHERE `username`='$user' AND `status` = '1'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		if($row){
			$user_id=$row[0];
			$user=$row[1];
			$pass=$row[2];
			$query="SELECT `token` FROM onetime_token ORDER BY `id` DESC";
			$result=mysqli_query($conn,$query);
			while($row1=mysqli_fetch_array($result)){
				$token=$row1[0];
				if($onetime_pass==md5($pass.$token)){
					$authentication++;
				}
			}
			
			if($authentication>0){
				$keyhash=md5(time()+$user_id);
				setcookie("backend",$user_id, time()+3600*10);
				setcookie("user_id",$user_id, time()+3600*10);
				setcookie("user",$user, time()+3600*10);
				setcookie("userkey",$keyhash, time()+3600*10);
				$_SESSION["userkey"] = $keyhash;
				$message='Login Success!';
				$out=true;
			}
		}
	}
	return $out;
}

function logout(){
	setcookie("backend",'', time()-3600*10);
	setcookie("user_id",'', time()-3600*10);
	setcookie("user",'', time()-3600*10);
	setcookie("userkey",'', time()-3600*10);
	session_unset(); 
	session_destroy(); 
	return true;
}
?>