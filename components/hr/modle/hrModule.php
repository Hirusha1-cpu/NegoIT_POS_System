<?php
function leaveStatus($st_id){
	switch ($st_id){
		case 0:
			$jasonArray["st_name"]='Deleted';
			$jasonArray["st_color"]='red';
		break;
		case 1:
			$jasonArray["st_name"]='Applied';
			$jasonArray["st_color"]='blue';
		break;
		case 2:
			$jasonArray["st_name"]='Approved';
			$jasonArray["st_color"]='green';
		break;
		case 3:
			$jasonArray["st_name"]='Rejected';
			$jasonArray["st_color"]='red';
		break;
	}
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

function hrUsers(){
	global $user_id,$user_name;
	$user_id=array();
	include('config.php');
	$query="SELECT up.id,up.username FROM userprofile up, hr_leave_to_user ltu WHERE ltu.`user`=up.id AND up.`status`=0 GROUP BY up.id ORDER BY up.username";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$user_id[]=$row[0];
		$user_name[]=$row[1];
	}
}

function getLeaveFormData($systemid,$sub_system){
	global $approver,$leave_id,$leave_name,$remaining_days,$user,$checkout;
	$user_id=$user=$_COOKIE['user_id'];
	$today=dateNow();
	if(isset($_GET['leave_user'])){
		if($_GET['leave_user']!='') $user=$_GET['leave_user'];
	}
	if(isset($_COOKIE['manager']) || isset($_COOKIE['top_manager']) || isset($_COOKIE['report'])) $approver=true; else $approver=false;
	if(($systemid==4)&&($sub_system==0)&&($user_id==30)) $approver=true;

	$completed_months=date("m",time());
	$this_year=date("Y",time());
	$this_month=date("Y-m",time());
	$take_type=$take_count=array();
	$i=$key1=0;
	$leave_id=array();

	include('config.php');
	$query="SELECT COUNT(id) FROM check_in_out WHERE DATE(`in_datetime`)='$today' AND user_id='$user' AND out_datetime is null";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($row[0]==1) $checkout=true; else $checkout=false;

	$query="SELECT leave_type,SUM(days) FROM hr_leave_take WHERE `status`='2' AND `user`='$user' AND year(from_date)='$this_year' GROUP BY leave_type";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$take_type[]=$row[0];
		$take_count[]=$row[1];
	}

	$query="SELECT lt.id,lt.name,lt.occurrence,lt.days,lt.apply_by_user FROM hr_leave_to_user lu, hr_leave_type lt WHERE lu.leave_type=lt.id AND lt.`status`='1' AND lu.`user`='$user'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		if(($row[4]==1)||($approver)){
				$leave_id[]=$row[0];
				$leave_name[]=$row[1];
				$occurrence=$row[2];
				$lt_days=$row[3];

				$key=array_search($row[0],$take_type);
				if($key>-1) $taken=$take_count[$key]; else $taken=0;
				if($occurrence==1){
					$remaining_days[]=$lt_days-$taken;
				}
				if($occurrence==2){
					$remaining_days[]=($completed_months*$lt_days)-$taken;
				}
				//-------------Annual Leave-----------------//
				//-------------Short Leave-----------------//
				if($row[1]=='Short Leave'){
					$query1="SELECT SUM(days) FROM hr_leave_take WHERE `status`='2' AND `user`='$user' AND from_date LIKE '$this_month%' AND `leave_type`='4'";
					$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
					$take_count1=$row1[0];
					$remaining_days[$i]=$lt_days-$take_count1;
				}
				//-------------Half Day------------------//
				if($row[1]=='Casual Leave'){ $key1=$i; }
				if($row[1]=='Half Day'){
					$remaining_days[$i]=$remaining_days[$key1]*2;
				}
				/*
				//-------------Weekly Leave------------------//
				if($row[1]=='Weekly Leave'){ $key1=$i;
					$remaining_days[$i]=(($completed_months+1)*$lt_days)-$taken;
				}
				*/
				$i++;
		}
	}

}

function getLeaveType(){
	global $leave_id,$leave_name;
	include('config.php');
	$query="SELECT id,name FROM hr_leave_type WHERE `status`=1";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$leave_id[]=$row[0];
		$leave_name[]=$row[1];
	}
}

function getLeaveTypeData(){
	global $user_id,$user_name,$leave_allo;
	$leave_allo=array();
	include('config.php');
	$query="SELECT id,username FROM userprofile WHERE `status`=0 ORDER BY username";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$user_id[]=$row[0];
		$user_name[]=$row[1];
		$leave_allo[$row[0]][]=0;
	}

	$query="SELECT `user`,leave_type FROM hr_leave_to_user";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$leave_allo[$row[0]][]=$row[1];
	}
}

function allocateUpdate(){
	$user=$_GET['user'];
	$leave=$_GET['leave'];
	$sub_action=$_GET['sub_action'];
	$proceed=false;
	$sub_leave=true;
	$out='Error';
	if(($user!='')&&($leave!='')&&($sub_action!='')){ $proceed=true; $out='Error'; }
	if($proceed){
		include('config.php');
		if($sub_action=='add'){
			$query="SELECT count(id) FROM hr_leave_to_user WHERE `leave_type`='$leave' AND `user`='$user'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$allocate_count=$row[0];
			if($leave==3){
				$query="SELECT `value` FROM settings WHERE setting='half_day_leave_deduct_type'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				$leave_type=$row[0];

				$query="SELECT count(id) FROM hr_leave_to_user WHERE `leave_type`='$leave_type' AND `user`='$user'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				if($row[0]==0)	$sub_leave=false;
			}
			if(($allocate_count==0)&&($sub_leave)){
				$query="INSERT INTO `hr_leave_to_user` (`leave_type`,`user`) VALUES ('$leave','$user')";
				$result=mysqli_query($conn,$query);
				if($result){ $out='Done'; }
			}
		}else if($sub_action=='remove'){
			if($leave==2){
				$query="SELECT count(id) FROM hr_leave_to_user WHERE `leave_type`='3' AND `user`='$user'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				if($row[0]==1)	$sub_leave=false;
			}
			if($sub_leave){
				$query="DELETE FROM `hr_leave_to_user` WHERE `leave_type`='$leave' AND `user`='$user'";
				$result=mysqli_query($conn,$query);
				if($result){ $out='Done'; }
			}
		}
	}
	return $out;
}
function applyLeave($sub_system){
	global $message,$user;
	$user=$_POST['leave_user'];
	$apply_by=$_COOKIE['user_id'];
	$apply_date=timeNow();
	$from_date=$_POST['from_date'];
	$to_date=$_POST['to_date'];
	$leave_type=$_POST['type'];
	$l_days=$_POST['l_days'];
	$reason=$_POST['reason'];
	$qry_n=$qry_d='';
	$status=1;
	include('config.php');

	if($leave_type=='3'){
		$query="SELECT `value` FROM settings WHERE setting='half_day_leave_deduct_type'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$leave_type=$row[0];
		$l_days=0.5;
	}

	$query="SELECT count(fn.id) FROM `function` fn, permission pe WHERE fn.id=pe.`function` AND pe.`user`='$apply_by' AND fn.name IN ('Manager','Top Manager')";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$auto_approve=$row[0];
	if($auto_approve>0){
		$status=2;
		$qry_n=",`approve_reject_by`,`approve_reject_date`";
		$qry_d=",'$apply_by','$apply_date'";
	}

	$query1="INSERT INTO `hr_leave_take` (`user`,`apply_by`,`apply_date`,`from_date`,`to_date`,`leave_type`,`days`,`reason`,`sub_system`,`status` $qry_n) VALUES ('$user','$apply_by','$apply_date','$from_date','$to_date','$leave_type','$l_days','$reason','$sub_system','$status' $qry_d)";
	$result1=mysqli_query($conn,$query1);

	if($result1){
		$message='The Leave was Applied Successfully';
		return true;
	}else{
		$message='Error: The Leave could not be Applied !';
		return false;
	}
}


function getUserLeaves($case){
	global $llog_id,$llog_user,$llog_apply_by,$llog_apply_date,$llog_approve_reject_by,$llog_approve_reject_date,$llog_from_date,$llog_to_date,$llog_type,$llog_days,$llog_reason,$llog_st_name,$llog_st_color,$filter_type,$filter_emp,$selected_st,$from_date,$to_date,$selected_year,$year_list;
	$user=$_COOKIE['user_id'];
	$filter_type=$filter_emp=$from_date=$to_date=$qry_year=$qry_date_range='';
	if(isset($_GET['leave_user'])){
		if($_GET['leave_user']!='') $user=$_GET['leave_user'];
	}
	if(isset($_GET['filter_emp'])){
		if($_GET['filter_emp']!='') $filter_emp=$_GET['filter_emp'];
	}
	if(isset($_GET['filter_type'])){
		if($_GET['filter_type']!='') $filter_type=$_GET['filter_type'];
	}

	$selected_year=$this_year=date("Y",time());
	$year_list[]=$this_year;
	$user_arr['']='';
	$qry2=$qry3="";
	$selected_st='all';
	if(isset($_GET['filter_year'])){
		if($_GET['filter_year']!='') $selected_year=$_GET['filter_year'];
		if($selected_year!=''){
			$qry_year="AND year(lta.from_date) ='$selected_year'";
		}
	}elseif(($_GET['action']=='home')||($_GET['action']=='my_leave')){
		$qry_year="AND year(lta.from_date) ='$selected_year'";
	}else{
		if(isset($_GET['from_date']) && isset($_GET['to_date'])){
			$from_date=$_GET['from_date'];
			$to_date=$_GET['to_date'];
			if(($from_date!='')&&($to_date!='')){
				$qry_date_range="AND ( date(lta.from_date) BETWEEN '$from_date' AND '$to_date')";
			}
		}
		if(($from_date=='')||($to_date=='')){
			$from_date=date("Y-m-d",time()-(60*60*24*30));
			$to_date=date("Y-m-d",time()+(60*60*24*30));
			if($qry_year=='')$qry_date_range="AND ( date(lta.from_date) BETWEEN '$from_date' AND '$to_date')";
		}
	}


	if(isset($_GET['filter_st'])){
		if(($_GET['filter_st']!='')&&($_GET['filter_st']!='all')){
			$selected_st=$_GET['filter_st'];
			$qry2="AND lta.`status`='$selected_st'";
		}
	}

	if($case=='private') $qry1="AND lta.`user`='$user'";
	if($case=='public') $qry1="";
	if($filter_emp!='')  $qry1="AND lta.`user`='$filter_emp'";
	if($filter_type!='')  $qry3="AND lta.`leave_type`='$filter_type'";
	$llog_id=array();

	include('config.php');
	$query="SELECT id,username FROM userprofile";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$user_arr[$row[0]]=ucfirst($row[1]);
	}

	$query="SELECT year(lta.from_date) FROM hr_leave_take lta WHERE lta.`status`!=0  $qry1 GROUP BY year(lta.from_date)";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$year_list[]=$row[0];
	}
		$year_list=array_unique($year_list);


	$query="SELECT lta.id,lta.`user`,lta.apply_by,lta.apply_date,lta.approve_reject_by,lta.approve_reject_date,lta.from_date,lta.to_date,lt.name,lta.days,lta.reason,lta.`status` FROM hr_leave_take lta, hr_leave_type lt WHERE lta.leave_type=lt.id $qry_year $qry_date_range $qry1 $qry2 $qry3 ORDER BY lta.from_date";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$llog_id[]=$row[0];
		$llog_user[]=$user_arr[$row[1]];
		$llog_apply_by[]=$user_arr[$row[2]];
		$llog_apply_date[]=substr($row[3],0,16);
		$llog_approve_reject_by[]=$user_arr[$row[4]];
		$llog_approve_reject_date[]=substr($row[5],0,16);
		$llog_from_date[]=$row[6];
		$llog_to_date[]=$row[7];
		if($row[9]==0.5) $llog_type[]='Half Day'; else $llog_type[]=$row[8];
		$llog_days[]=$row[9];
		$llog_reason[]=$row[10];
		$json_array=json_decode(leaveStatus($row[11]));
		$llog_st_name[]=$json_array->{"st_name"};
		$llog_st_color[]=$json_array->{"st_color"};
	}
}

function getOneLeave($case){
	global $id,$approver,$llog1_id,$llog1_user_id,$llog1_user,$llog1_apply_by,$llog1_apply_date,$llog1_approve_reject_by,$llog1_approve_reject_date,$llog1_deleted_by,$llog1_deleted_date,$llog1_from_date,$llog1_to_date,$llog1_type,$llog1_days,$llog1_reason,$llog1_st,$llog1_st_name,$llog1_st_color,$total_staff,$onleave_staff;
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		$user=$_COOKIE['user_id'];

		if($case=='private') $qry1="AND lta.`user`='$user'";
		if($case=='public') $qry1="";

		if(isset($_COOKIE['manager']) || isset($_COOKIE['top_manager']) || isset($_COOKIE['report'])) $approver=true; else $approver=false;
		$date_list=$user_arr=array();
		$user_arr['']='';
		$total_staff=$onleave_staff=0;

		include('config.php');
		$query="SELECT id,username FROM userprofile";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$user_arr[$row[0]]=ucfirst($row[1]);
		}

		$query="SELECT lta.id,lta.`user`,lta.apply_by,lta.apply_date,lta.approve_reject_by,lta.approve_reject_date,lta.deleted_by,lta.deleted_date,lta.from_date,lta.to_date,lt.name,lta.days,lta.reason,lta.`status` FROM hr_leave_take lta, hr_leave_type lt WHERE lta.leave_type=lt.id AND lta.`id`='$id' $qry1 ORDER BY lta.from_date";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$llog1_id=$row[0];
		$llog1_user_id=$row[1];
		$llog1_user=$user_arr[$row[1]];
		$llog1_apply_by=$user_arr[$row[2]];
		$llog1_apply_date=$row[3];
		$llog1_approve_reject_by=$user_arr[$row[4]];
		$llog1_approve_reject_date=$row[5];
		$llog1_deleted_by=$user_arr[$row[6]];
		$llog1_deleted_date=$row[7];
		$llog1_from_date=$row[8];
		$llog1_to_date=$row[9];
		if($row[11]==0.5) $llog1_type='Half Day'; else $llog1_type=$row[10];
		$llog1_days=$row[11];
		$llog1_reason=$row[12];
		$llog1_st=$row[13];
		$json_array=json_decode(leaveStatus($row[13]));
		$llog1_st_name=$json_array->{"st_name"};
		$llog1_st_color=$json_array->{"st_color"};

		$query="SELECT store FROM userprofile WHERE id='$llog1_user_id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$store=$row[0];
		$query="SELECT up.username FROM hr_leave_to_user ltu, userprofile up WHERE ltu.`user`=up.id AND up.store='$store' AND up.`status`='0' GROUP BY up.id";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$total_staff++;
		}

		$period = new DatePeriod(
		     new DateTime($llog1_from_date),
		     new DateInterval('P1D'),
		     new DateTime($llog1_to_date)
		);

		foreach ($period as $key => $value) {
		    $date_list[]=$value->format('Y-m-d');
		}
		$date_list[]=$llog1_to_date;
		for($i=0;$i<sizeof($date_list);$i++){
			$date_one=$date_list[$i];
			$query="SELECT count(lt.id) FROM hr_leave_take lt, userprofile up WHERE lt.`user`=up.id AND from_date<='$date_one' AND to_date>='$date_one' AND up.store='$store' AND lt.`status`='2'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$onleave_staff+=$row[0];
		}
	}
}

function setLeaveStatus($new_status){
	global $message,$leave_no;
	$leave_no=$_GET['id'];
	$action_by=$_COOKIE['user_id'];
	$datetime=timeNow();
	$datenow=dateNow();
	$out=true;
	$msg='';

	include('config.php');
	$result = mysqli_query($conn,"SELECT `apply_by`,`status` FROM hr_leave_take WHERE id='$leave_no'");
	$row = mysqli_fetch_assoc($result);
	$created_by=$row['apply_by'];
	$status=$row['status'];
	if(isset($_COOKIE['manager']) || isset($_COOKIE['top_manager']) || isset($_COOKIE['report'])) $approver=true; else $approver=false;

	if($action_by==$created_by) $le_owner=true; else $le_owner=false;

/* 1. Approve the Leave	 	*/		if($status==1 && $new_status==2 && $approver){ $query1="UPDATE `hr_leave_take` SET `status`='2',`approve_reject_by`='$action_by',`approve_reject_date`='$datetime' WHERE id='$leave_no'"; $msg='Approved'; }else
/* 2. Reject the Leave	 	*/		if($status==1 && $new_status==3 && $approver){ $query1="UPDATE `hr_leave_take` SET `status`='3',`approve_reject_by`='$action_by',`approve_reject_date`='$datetime' WHERE id='$leave_no'"; $msg='Rejected'; }else
/* 5. Delete the Leave	 	*/		if($status==1 && $new_status==0 && $le_owner){ $query1="UPDATE `hr_leave_take` SET `status`='0' WHERE id='$leave_no'"; $msg='Cancelled'; }else
									{ $out=false; $msg='Error: Undefine Action'; }

		if($out){
			$result1=mysqli_query($conn,$query1);
			if(!$result1){ $out=false; $msg='Error: Leave Status Could not be Changed'; }
		}
	if($out){
		$message='Leave was '.$msg.' Successfully';
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function deleteLeave($approver1){
	global $message,$leave_no;
	$leave_no=$_GET['id'];
	$master_pw5=$_GET['master_pw5'];
	$deleted_by=$_COOKIE['user_id'];
	$datetime=timeNow();
	$out=true;
	$message='The Leave was Deleted Successfully';

	include('config.php');
	$result = mysqli_query($conn,"SELECT `value` FROM settings WHERE `setting`='master_pw5'");
	$row = mysqli_fetch_assoc($result);
	$master_pw5_db=$row['value'];

	if(!$approver1){ $out=false; $message='Error: Unauthorized Action'; }
	if($master_pw5_db!=$master_pw5){ $out=false; $message='Error: Invalid Master Password'; }

	if($out){
		$query="UPDATE `hr_leave_take` SET `deleted_by`='$deleted_by',`deleted_date`='$datetime',`status`='0' WHERE id='$leave_no'";
		$result=mysqli_query($conn,$query);
		if(!$result){ $out=false; $message='Error: The Leave Could not be Deleted'; }
	}

	return $out;
}

function getShopStaff(){
	global $given_date,$store_id,$store_name,$total_staff_id,$total_staff_name,$onleave_staff;
	$store_id=$store_name=array();
	if(isset($_GET['given_date'])){
		$given_date=$_GET['given_date'];
	}else{
		$given_date=dateNow();
	}
	$total_staff=$onleave_staff=$total_staff_id=$total_staff_name=array();

	include('config.php');
	$query1="SELECT id,name FROM stores WHERE `status`='1'";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$st_id=$row1[0];
		$store_id[]=$row1[0];
		$store_name[]=$row1[1];

		$query="SELECT up.id,up.username FROM hr_leave_to_user ltu, userprofile up WHERE ltu.`user`=up.id AND up.store='$st_id' AND up.`status`='0' GROUP BY up.id";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$total_staff_id[$st_id][]=$row[0];
			$total_staff_name[$st_id][]=ucfirst($row[1]);
		}
		$onleave_staff[$st_id]=array();
		$i=0;
		$query="SELECT up.id FROM hr_leave_take lt, userprofile up WHERE lt.`user`=up.id AND from_date<='$given_date' AND to_date>='$given_date' AND up.store='$st_id' AND lt.`status`='2' AND lt.leave_type!='4'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$onleave_staff[$st_id][$i]=$row[0];
			$i++;
		}
	}
}

function getLeaveReport(){
	global $from_date,$to_date,$type0,$leave_user,$llog_user,$llog_type,$llog_days;
	$llog_type=array();
	if(isset($_GET['from_date'])&&isset($_GET['to_date'])&&isset($_GET['type'])&&isset($_GET['leave_user'])){
		$from_date=$_GET['from_date'];
		$to_date=$_GET['to_date'];
		$type0=$_GET['type'];
		$leave_user=$_GET['leave_user'];
		if(($from_date!='')&&($to_date!='')&&($type0!='')&&($leave_user!='')){
			$qry1=$qry2=$qry3=$qry4='';
			if($type0==2) $qry1="AND lta.days!='0.5'";
			if($type0==3){ $type=2; $qry1="AND lta.days='0.5'"; }else $type=$type0;
			if($leave_user!='all'){ $qry2="AND lta.`user`='$leave_user'"; $qry4="GROUP BY lt.id ORDER BY lt.id"; }
			if($type0!='all'){ $qry3="AND lta.leave_type='$type'"; $qry4="GROUP BY lta.`user` ORDER BY up.username"; }else $qry3="AND lta.leave_type!='4'";

			include('config.php');
			if(($leave_user!='all')||($type0!='all')){
				$query="SELECT lt.name,up.username,SUM(lta.days) FROM hr_leave_take lta, hr_leave_type lt, userprofile up WHERE lta.leave_type=lt.id AND lta.`user`=up.id AND lta.from_date>='$from_date' AND lta.to_date<='$to_date' AND lta.`status`='2' $qry1 $qry2 $qry3 $qry4";
				$result=mysqli_query($conn,$query);
				while($row=mysqli_fetch_array($result)){
					if($type0==3) $llog_type[]='Half Day'; else $llog_type[]=$row[0];
					$llog_user[]=ucfirst($row[1]);
					$llog_days[]=$row[2];
				}
			}
		}
	}
}

function inoutReport(){
	global $check_userid,$check_username,$from_date,$to_date,$inout_user,$cio_id,$cio_user,$cio_in_date,$cio_in_city,$cio_out_date,$cio_out_city;
	$cio_id=$check_userid=array();

	include('config.php');
	$query="SELECT id,username FROM userprofile WHERE mobile_rep='1' AND `status`='0'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$check_userid[]=$row[0];
		$check_username[]=ucfirst($row[1]);
	}
	if(isset($_GET['from_date'])&&isset($_GET['to_date'])&&isset($_GET['inout_user'])){
		$from_date=$_GET['from_date'];
		$to_date=$_GET['to_date'];
		$inout_user=$_GET['inout_user'];
		if(($from_date!='')&&($to_date!='')&&($inout_user!='')){
			$qry1='';
			if($inout_user!='all'){ $qry1="AND up.`id`='$inout_user'"; }

				$query="SELECT cio.id,up.username,cio.in_datetime,cio.in_city,cio.out_datetime,cio.out_city FROM check_in_out cio, userprofile up WHERE cio.user_id=up.id AND (( DATE(cio.in_datetime) BETWEEN '$from_date' AND '$to_date' ) OR (DATE(cio.out_datetime) BETWEEN '$from_date' AND '$to_date')) $qry1 ORDER BY cio.in_datetime,cio.id";
				$result=mysqli_query($conn,$query);
				while($row=mysqli_fetch_array($result)){
					$cio_id[]=$row[0];
					$cio_user[]=ucfirst($row[1]);
					$cio_in_date[]=$row[2];
					$cio_in_city[]=$row[3];
					$cio_out_date[]=$row[4];
					$cio_out_city[]=$row[5];
				}
		}
	}
}

function decodeMapData1(){
	global $map_api,$map_rep,$map_time,$sm_pinter,$map_x,$map_y;
	$cust_arr=$datalist=$map_cust=$map_x=$map_y=$map_sm=$unique_sm=$sm_pinter0=$sm_pinter=array();
	$id=$_GET['id'];
	include('config.php');
	$map_api=getGoogleAPI1();
	$map_rep=$map_time=$map_x=$map_y=array();
	$query="SELECT up.username,cio.in_datetime,cio.in_gps_x,cio.in_gps_y,cio.out_datetime,cio.out_gps_x,cio.out_gps_y FROM check_in_out cio, userprofile up WHERE cio.user_id=up.id AND cio.id='$id'";
	$result=mysqli_query($conn,$query);
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$map_rep=ucfirst($row[0]);
	$map_time[]="In Time: ".substr($row[1],11,5);
	$map_x[]=$row[2];
	$map_y[]=$row[3];
	$sm_pinter[]='2';
	if($row[4]!=''){
		$map_time[]="Out Time: ".substr($row[4],11,5);
		$map_x[]=$row[5];
		$map_y[]=$row[6];
		$sm_pinter[]='0';
	}
}

// update by nirmal 07_03_2024 (added error handling when city is not set)
function setCheckOut(){
	global $message;
	$gps_x=$_POST['gps_x'];
	$gps_y=$_POST['gps_y'];
	$user_id=$_COOKIE['user_id'];
	$time_now=timeNow();
	$date_now=substr($time_now,0,10);
	$message='Error: Could Not Be Checked Out!';
	$check_inout_id='';
	include('config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='api_geocoding'");
	$row = mysqli_fetch_assoc($result);
	$api_key2=$row['value'];

	$query="SELECT id FROM check_in_out WHERE DATE(`in_datetime`)='$date_now' AND user_id='$user_id' AND out_datetime is null";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$check_inout_id=$row[0];

	if($check_inout_id!=''){
		$data = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$gps_x.','.$gps_y.'&key='.$api_key2);
		$decodedData=json_decode($data);
		if (isset($decodedData->{"results"}[1]->{"formatted_address"})) {
			$city = $decodedData->{"results"}[1]->{"formatted_address"};
		} else {
			$city = '';
		}
		$query="UPDATE `check_in_out` SET `out_datetime`='$time_now',`out_gps_x`='$gps_x',`out_gps_y`='$gps_y',`out_city`='$city' WHERE id='$check_inout_id'";
		$result=mysqli_query($conn,$query);
	}else{
		$result=false;
		$message='Checkout is not available';
	}
	if($result){
		$message='Checked Out Time: '.substr($time_now,11,5);
		return true;
	}else{
		return false;
	}
}

?>