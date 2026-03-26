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

function myActiveInvoices($user_id){
	global $hp_type,$hp_invoice_no,$hp_bill_date,$hp_cust,$hp_payday,$hp_amount,$hp_paycount,$hp_paidcount, $conn2;
	$hp_invoice_no=array();
	if($user_id=='all') $qry_agent=""; else $qry_agent="AND bm.recovery_agent='$user_id'";
	include('config.php');
	// $query="SELECT ht.`name`,hs.invoice_no,date(bm.billed_timestamp),cu.`name`,hs.`day`,hs.payment_amount,hs.payment_count,COUNT(hp.id) FROM bill_main bm, hp_schedule_type ht, cust cu, hp_inv_schedule hs LEFT JOIN hp_payments hp ON hs.id=hp.`schedule` WHERE bm.invoice_no=hs.invoice_no AND hs.`type`=ht.id AND bm.`cust`=cu.id AND bm.`lock`=1 AND bm.`status`!=0 AND hs.`status`=1 $qry_agent GROUP BY bm.invoice_no ORDER BY cu.`name`";
	$query = "SELECT 
    hs.invoice_no,
    MAX(ht.name) as type_name,
    DATE(MAX(bm.billed_timestamp)) as bill_date,
    MAX(cu.name) as customer_name,
    SUM(hs.payment_amount) as total_payment_amount,
    SUM(hs.payment_count) as total_payment_count,
    COUNT(hp.id) as total_paid_count
FROM bill_main bm
JOIN hp_inv_schedule hs ON bm.invoice_no = hs.invoice_no
JOIN hp_schedule_type ht ON hs.type = ht.id
JOIN cust cu ON bm.cust = cu.id
LEFT JOIN hp_payments hp ON hs.id = hp.schedule
WHERE bm.lock = 1 
AND bm.status != 0 
AND hs.status = 1 
$qry_agent
GROUP BY hs.invoice_no
ORDER BY customer_name";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$hp_type[]=$row[0];
		$hp_invoice_no[]=$row[1];
		$hp_bill_date[]=$row[2];
		$hp_payday[]=$row[4];
		$hp_amount[]=$row[5];
		$hp_paycount[]=$row[6];
		$hp_paidcount[]=$row[7];
		if(strlen($row[3])>30) $hp_cust[]=substr($row[3],0,30).'..'; else $hp_cust[]=$row[3];
	}
}

function paymentDateIssueList($user_id){
global $issue_list1,$issue_list2,$issue_list3;
	$hp_schedule=$issue_list1=$issue_list2=$issue_list3=array();
	include('config.php');
	$query="SELECT bm.invoice_no,his.cal_start_date,ht.`name`,his.`day`,his.payment_count FROM hp_inv_schedule his, hp_schedule_type ht, hp_payments hp, bill_main bm WHERE his.`type`=ht.id AND his.id=hp.`schedule` AND bm.invoice_no=hp.invoice_no AND bm.`lock`=1 AND bm.`status`!=0 AND his.`status`='1' GROUP BY bm.invoice_no";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$bm_invoice_no=$row[0];
		$hp_cal_start=$row[1];
		$hp_type=$row[2];
		$hp_day=$row[3];
		$hp_count=$row[4];
		$hp_schedule=hpsPaySchedule($hp_cal_start,$hp_type,$hp_day,$hp_count);

		$query1="SELECT hp.instalment_date,py.amount FROM hp_payments hp, payment py WHERE hp.payment_no=py.id AND hp.invoice_no='$bm_invoice_no'";
		$result1=mysqli_query($conn2,$query1);
		while($row1=mysqli_fetch_array($result1)){
			$py_inst_date=$row1[0];
			$key=array_search($py_inst_date,$hp_schedule);
			if($key==false){
				$issue_list1[]=$bm_invoice_no;
				$issue_list2[]=$py_inst_date;
				$issue_list3[]=$row1[1];
			}
		}
	}
}

function getInvoiceData(){
	$invoice_no=$_POST['invoice_no'];
	$inst_str='';
	include('config.php');

	$query="SELECT his.cal_start_date,ht.`name`,his.`day`,his.payment_amount,his.payment_count,cu.`name`,cu.nickname,bm.`invoice_+total` + bm.`invoice_-total` FROM hp_inv_schedule his, hp_schedule_type ht, bill_main bm, cust cu WHERE his.`type`=ht.id AND his.invoice_no=bm.invoice_no AND bm.`cust`=cu.id AND his.invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$hp_cal_start=$row[0];
	$hp_type=$row[1];
	$hp_day=$row[2];
	$hp_amount=$row[3];
	$hp_count=$row[4];
	$hp_cust=$row[5];
	$hp_nick=$row[6];
	$bm_total=$row[7];
	
	if($hp_type==2) $schdule=$hp_type.' - Every '.weekday($hp_day); else $schdule=$hp_type.' - Every '.$hp_day;
	
	$query="SELECT SUM(amount) FROM payment WHERE `status`='0' AND chque_return=0 AND invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	if($row[0]=='') $pay_total=0; else $pay_total=$row[0];
	
	$hp_schedule=hpsPaySchedule($hp_cal_start,$hp_type,$hp_day,$hp_count);

	for($i=1;$i<=sizeof($hp_schedule);$i++){
		$instalment_date=$hp_schedule[$i];
		$inst_str.=$instalment_date.',';
		
		$hp_schedule_remark[$i]='';
		$total_pay=0;
		$query="SELECT py.id,py.amount FROM hp_payments hpy, payment py WHERE hpy.payment_no=py.id AND py.`status`=0 AND py.chque_return=0 AND hpy.invoice_no='$invoice_no' AND hpy.instalment_date='$instalment_date'";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$inst_str.=$row[0].'-'.$row[1].'/';
			$total_pay+=$row[1];
		}
		$inst_str=rtrim($inst_str,'/');
		$inst_str.=','.$total_pay.'|';

	}
	$inst_str=rtrim($inst_str,'|');

	$jasonArray["invoice_no"]=$invoice_no;
	$jasonArray["hp_cal_start"]=$hp_cal_start;
	$jasonArray["hp_schdule"]=$schdule;
	$jasonArray["hp_amount"]=$hp_amount;
	$jasonArray["hp_count"]=$hp_count;
	$jasonArray["hp_cust"]=$hp_cust;
	$jasonArray["hp_nick"]=$hp_nick;
	$jasonArray["bm_total"]=$bm_total;
	$jasonArray["pay_total"]=$pay_total;
	$jasonArray["inst_str"]=$inst_str;
	$myJSON = json_encode($jasonArray);
	return $myJSON;
	
}

function getCustName(){
	$id=$_POST['id'];
	include('config.php');
	$query="SELECT `name` FROM cust WHERE id='$id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	return $row[0];
}
			
function custList($sub_system,$user_id){
global $his_cu_id,$his_cu_name, $conn2;
	if($sub_system=='all') $sub_system_qry=""; else	$sub_system_qry="AND cu.`sub_system`='$sub_system'";
	if($user_id=='all') $qry_agent=""; else $qry_agent="AND bm.recovery_agent='$user_id'";

	include('config.php');
	$query="SELECT cu.id,cu.`name` FROM hp_inv_schedule his, bill_main bm, cust cu WHERE his.invoice_no=bm.invoice_no AND bm.`cust`=cu.id AND bm.`status`!=0 AND his.`status`=1 $sub_system_qry $qry_agent GROUP BY cu.id ORDER BY cu.`name`";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$his_cu_id[]=$row[0];
		$his_cu_name[]=$row[1];
	}
}

function getHPInvoiceList($user_id){
	$cust_id=$_POST['cust_id'];
	if($user_id=='all') $qry_agent=""; else $qry_agent="AND bm.recovery_agent='$user_id'";
	$out_str='';
	
	include('config.php');
	$query="SELECT  bm.invoice_no FROM hp_inv_schedule his, bill_main bm WHERE his.invoice_no=bm.invoice_no AND bm.`status`!=0 AND his.`status`=1 AND bm.`cust`='$cust_id' $qry_agent";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$out_str.=$row[0].'|';
	}
	$out_str=rtrim($out_str,'|');
	
	return $out_str;
}

function hpsPayScheduleDue($cal_start_date,$hp_type,$hp_date,$hp_count,$today){
	$start_date=substr($cal_start_date, 0, -2).$hp_date;
	$hp_schedule=array();
		
	if($hp_type=='Monthly'){
		for($i=1;$i<=$hp_count;$i++){
			$date=date('Y-m-d', strtotime('+'.$i.' month', strtotime($start_date)));
			if($date<=$today){
				$hp_schedule[$i]=$date;
			}
		}
	}
	if($hp_type=='Weekly'){
		$day=weekday($hp_date);
		for($i=1;$i<=$hp_count;$i++){
			$date=date('Y-m-d', strtotime('+'.$i.' week '.$day, strtotime($cal_start_date)));
			if($date<=$today){
				$hp_schedule[$i]=$date;
			}
		}
	}
	if($hp_type=='Daily'){
		for($i=1;$i<=$hp_count;$i++){
			$date=date('Y-m-d', strtotime('+'.$i.' day', strtotime($cal_start_date)));
			if($date<=$today){
				$hp_schedule[$i]=$date;
			}
		}
	}
	return $hp_schedule;
}

function hpsPayScheduleToday($cal_start_date,$hp_type,$hp_date,$hp_count,$today){
	$k=true;
	$start_date=substr($cal_start_date, 0, -2).$hp_date;
	$hp_schedule='';
		
	if($hp_type=='Monthly'){
		for($i=1;$i<=$hp_count;$i++){
			$date=date('Y-m-d', strtotime('+'.$i.' month', strtotime($start_date)));
			if (($date==$today)&&($k)){
				$hp_schedule=$date;
				$k=false;
			}
		}
	}
	if($hp_type=='Weekly'){
		$day=weekday($hp_date);
		for($i=1;$i<=$hp_count;$i++){
			$date=date('Y-m-d', strtotime('+'.$i.' week '.$day, strtotime($cal_start_date)));
			if (($date==$today)&&($k)){
				$hp_schedule=$date;
				$k=false;
			}
		}
	}
	if($hp_type=='Daily'){
		for($i=1;$i<=$hp_count;$i++){
			$date=date('Y-m-d', strtotime('+'.$i.' day', strtotime($cal_start_date)));
			if (($date==$today)&&($k)){
				$hp_schedule=$date;
				$k=false;
			}
		}
	}
	return $hp_schedule;
}

function hpsPayScheduleUpcomming($cal_start_date,$hp_type,$hp_date,$hp_count,$today){
	$k=true;
	$start_date=substr($cal_start_date, 0, -2).$hp_date;
	$hp_schedule='';
		
	if($hp_type=='Monthly'){
		for($i=1;$i<=$hp_count;$i++){
			$date=date('Y-m-d', strtotime('+'.$i.' month', strtotime($start_date)));
			if (($date>=$today)&&($k)){
				$hp_schedule=$date;
				$k=false;
			}
		}
	}
	if($hp_type=='Weekly'){
		$day=weekday($hp_date);
		for($i=1;$i<=$hp_count;$i++){
			$date=date('Y-m-d', strtotime('+'.$i.' week '.$day, strtotime($cal_start_date)));
			if (($date>=$today)&&($k)){
				$hp_schedule=$date;
				$k=false;
			}
		}
	}
	if($hp_type=='Daily'){
		for($i=1;$i<=$hp_count;$i++){
			$date=date('Y-m-d', strtotime('+'.$i.' day', strtotime($cal_start_date)));
			if (($date>=$today)&&($k)){
				$hp_schedule=$date;
				$k=false;
			}
		}
	}
	return $hp_schedule;
}

function upCommingCollection($user_id){
global 	$future,$upcol_inv,$upcol_amo,$upcol_remaining,$upcol_type,$upcol_inst,$downcol_inv,$downcol_amo,$downcol_remaining,$downcol_type,$downcol_inst, $conn2;
	$upcol_inv=$upcol_amo=$upcol_remaining=$upcol_type=$upcol_inst=array();
	$downcol_inv=$downcol_amo=$downcol_remaining=$downcol_type=$downcol_inst=array();
	if(isset($_GET['future'])) $future=$_GET['future']; else $future='UpComming';
	$today=dateNow();
	if($user_id=='all') $qry_agent=""; else $qry_agent="AND bm.recovery_agent='$user_id'";
	
	include('config.php');
	$query="SELECT bm.invoice_no,his.cal_start_date,ht.`name`,his.`day`,his.payment_count,his.payment_amount FROM hp_inv_schedule his, hp_schedule_type ht, bill_main bm WHERE his.`type`=ht.id AND his.invoice_no=bm.invoice_no AND bm.`status`!='0' AND his.`status`='1' $qry_agent";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$hp_bm_no=$row[0];
		$hp_cal_start=$row[1];
		$hp_type=$row[2];
		$hp_day=$row[3];
		$hp_count=$row[4];
		$hp_amount=$row[5];
		$hp_schedule_due=hpsPayScheduleDue($hp_cal_start,$hp_type,$hp_day,$hp_count,$today);
		for($i=1;$i<=sizeof($hp_schedule_due);$i++){
			$instalment_date=$hp_schedule_due[$i];
			$query1="SELECT SUM(py.amount) FROM hp_payments hpy, payment py WHERE hpy.payment_no=py.id AND py.`status`=0 AND py.chque_return=0 AND py.invoice_no='$hp_bm_no' AND hpy.instalment_date='$instalment_date'";
			$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
				if($row1[0]<$hp_amount){	
					$downcol_inv[]=$hp_bm_no;
					$downcol_amo[]=$hp_amount;
					$downcol_remaining[]=($hp_amount - $row1[0]);
					$downcol_type[]=$hp_type;
					$downcol_inst[]=$instalment_date;
				}
		}
		
		if($future=='UpComming') $instalment_date=hpsPayScheduleUpcomming($hp_cal_start,$hp_type,$hp_day,$hp_count,$today);
		if($future=='Today') $instalment_date=hpsPayScheduleToday($hp_cal_start,$hp_type,$hp_day,$hp_count,$today);
		
		if($instalment_date!=''){
			$query1="SELECT SUM(py.amount) FROM hp_payments hpy, payment py WHERE hpy.payment_no=py.id AND py.`status`=0 AND py.chque_return=0 AND py.invoice_no='$hp_bm_no' AND hpy.instalment_date='$instalment_date'";
			$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
				if($row1[0]<$hp_amount){	
					$upcol_inv[]=$hp_bm_no;
					$upcol_amo[]=$hp_amount;
					$upcol_remaining[]=($hp_amount - $row1[0]);
					$upcol_type[]=$hp_type;
					$upcol_inst[]=$instalment_date;
				}
		}
	}
}

function getInvoiceOutstanding($user_id,$sub_system){
global 	$group,$town,$cu_gp_id,$cu_gp_name,$cu_tw_id,$cu_tw_name,$type,$cu_id,$downcol_inv,$downcol_amo,$downcol_inv_outstanding,$downcol_type,$downcol_inst,$downcol_cust_id,$downcol_cust_name,$downcol_tel,$sch_type_id,$sch_type_name,$monthb0L,$monthb1L,$monthb2L,$monthb3L,$monthb0N,$monthb1N,$monthb2N,$monthb3N, $conn2;
	$cu_gp_id=$cu_tw_id=$downcol_inv=$downcol_amo=$downcol_remaining=$downcol_type=$downcol_inst=$sch_type_id=array();
	$inv_no=0;
	$today=dateNow();
	if(isset($_GET['type'])) $type=$_GET['type']; else $type='';
	if(isset($_GET['cu_id'])) $cu_id=$_GET['cu_id']; else $cu_id='';
	if(isset($_GET['group'])) $group=$_GET['group']; else $group='all';
	if(isset($_GET['town'])) $town=$_GET['town']; else $town='all';
	if($cu_id=='') $qry_cust=""; else $qry_cust="AND cu.id='$cu_id'";
	if($user_id=='all') $qry_agent=""; else $qry_agent="AND bm.recovery_agent='$user_id'";
	if($group=='all') $qry_group=""; else $qry_group="AND cu.associated_group='$group'";
	if($town=='all') $qry_town=""; else $qry_town="AND cu.associated_town='$town'";
	
	include('config.php');
	
	$query="SELECT id,`name` FROM cust_group WHERE sub_system='$sub_system'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$cu_gp_id[]=$row[0];
		$cu_gp_name[]=$row[1];
	}
	$query="SELECT id,`name` FROM town";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$cu_tw_id[]=$row[0];
		$cu_tw_name[]=$row[1];
	}
	
	if($type==1){
		$monthb0L = date("Y-F", time());
		$monthb1L = date("Y-F", strtotime('-1 month',strtotime($today)));
		$monthb2L = date("Y-F", strtotime('-2 month',strtotime($today)));
		$monthb3L = date("Y-F", strtotime('-3 month',strtotime($today)));
		$monthb0N = date("Y-m", time());
		$monthb1N = date("Y-m", strtotime('-1 month',strtotime($today)));
		$monthb2N = date("Y-m", strtotime('-2 month',strtotime($today)));
		$monthb3N = date("Y-m", strtotime('-3 month',strtotime($today)));
	}
	if($type==2){
		$monthb0L = date("Y-F (W)", time());
		$monthb1L = date("Y-F (W)", strtotime('-1 week',strtotime($today)));
		$monthb2L = date("Y-F (W)", strtotime('-2 week',strtotime($today)));
		$monthb3L = date("Y-F (W)", strtotime('-3 week',strtotime($today)));
		$monthb0N = date("Y-W", time());
		$monthb1N = date("Y-W", strtotime('-1 week',strtotime($today)));
		$monthb2N = date("Y-W", strtotime('-2 week',strtotime($today)));
		$monthb3N = date("Y-W", strtotime('-3 week',strtotime($today)));
	}
	if($type==3){
		$monthb0L = date("Y-F-d", time());
		$monthb1L = date("Y-F-d", strtotime('-1 day',strtotime($today)));
		$monthb2L = date("Y-F-d", strtotime('-2 day',strtotime($today)));
		$monthb3L = date("Y-F-d", strtotime('-3 day',strtotime($today)));
		$monthb0N = date("Y-m-d", time());
		$monthb1N = date("Y-m-d", strtotime('-1 day',strtotime($today)));
		$monthb2N = date("Y-m-d", strtotime('-2 day',strtotime($today)));
		$monthb3N = date("Y-m-d", strtotime('-3 month',strtotime($today)));
	}
	
	
	$query="SELECT id,`name` FROM hp_schedule_type WHERE `status`='1'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$sch_type_id[]=$row[0];
		$sch_type_name[]=$row[1];
	}
	
	if($type!=''){
		$query="SELECT bm.invoice_no,his.cal_start_date,ht.`name`,his.`day`,his.payment_count,his.payment_amount,his.payment_count,bm.`invoice_+total` + bm.`invoice_-total`,cu.`id`,cu.`name`,cu.`mobile` FROM hp_inv_schedule his, hp_schedule_type ht, bill_main bm, cust cu WHERE his.`type`=ht.id AND his.invoice_no=bm.invoice_no AND bm.`cust`=cu.id AND bm.`status`!='0' AND his.`status`='1' AND ht.id='$type' $qry_cust $qry_agent $qry_group $qry_town ORDER BY bm.invoice_no";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$hp_bm_no=$row[0];
			$hp_cal_start=$row[1];
			$hp_type=$row[2];
			$hp_day=$row[3];
			$hp_count=$row[4];
			$hp_amount=$row[5];
			$hp_total=$row[5] * $row[6];
			$bm_total=$row[7];
			$hp_cust_id=$row[8];
			$hp_cust_name=$row[9];
			$hp_mobile=$row[10];
			$hp_schedule_due=hpsPayScheduleDue($hp_cal_start,$hp_type,$hp_day,$hp_count,$today);
			for($i=1;$i<=sizeof($hp_schedule_due);$i++){
				$instalment_date=$hp_schedule_due[$i];
				$query1="SELECT SUM(py.amount) FROM hp_payments hpy, payment py WHERE hpy.payment_no=py.id AND py.`status`=0 AND py.chque_return=0 AND py.invoice_no='$hp_bm_no' AND hpy.instalment_date='$instalment_date'";
//				$query1="SELECT SUM(py.amount) FROM payment py WHERE py.`status`=0 AND py.chque_return=0 AND py.invoice_no='$hp_bm_no'";
				$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
					if(($instalment_date<=$today)&&($row1[0]<$hp_amount)){	
						if($inv_no!=$hp_bm_no){
							$downcol_amo[$hp_bm_no][$monthb0N]=0;
							$downcol_amo[$hp_bm_no][$monthb1N]=0;
							$downcol_amo[$hp_bm_no][$monthb2N]=0;
							$downcol_amo[$hp_bm_no][$monthb3N]=0;
							$downcol_amo[$hp_bm_no]['before']=0;
							
							$downcol_inst[$hp_bm_no][$monthb0N]='';
							$downcol_inst[$hp_bm_no][$monthb1N]='';
							$downcol_inst[$hp_bm_no][$monthb2N]='';
							$downcol_inst[$hp_bm_no][$monthb3N]='';
							$downcol_inst[$hp_bm_no]['before']='';
						}
						if($type==1) $instalment_set=substr($instalment_date,0,7);
						if($type==2) $instalment_set=date("Y-W", strtotime($instalment_date));
						if($type==3) $instalment_set=$instalment_date;
						
						if($instalment_set==$monthb0N){ $downcol_amo[$hp_bm_no][$instalment_set]=$hp_amount-$row1[0]; $downcol_inst[$hp_bm_no][$instalment_set]=$instalment_date; }
						elseif($instalment_set==$monthb1N){ $downcol_amo[$hp_bm_no][$instalment_set]=$hp_amount-$row1[0]; $downcol_inst[$hp_bm_no][$instalment_set]=$instalment_date; }
						elseif($instalment_set==$monthb2N){ $downcol_amo[$hp_bm_no][$instalment_set]=$hp_amount-$row1[0]; $downcol_inst[$hp_bm_no][$instalment_set]=$instalment_date; }
						elseif($instalment_set==$monthb3N){ $downcol_amo[$hp_bm_no][$instalment_set]=$hp_amount-$row1[0]; $downcol_inst[$hp_bm_no][$instalment_set]=$instalment_date; }
						else{ $downcol_amo[$hp_bm_no]['before']+=$hp_amount-$row1[0]; $downcol_inst[$hp_bm_no]['before']=$instalment_date; }
						
						if($inv_no!=$hp_bm_no){
							$query2="SELECT SUM(py.amount) FROM payment py WHERE py.`status`=0 AND py.chque_return=0 AND py.invoice_no='$hp_bm_no'";
							$row2=mysqli_fetch_row(mysqli_query($conn2,$query2));
							$downcol_inv[]=$hp_bm_no;
							$downcol_inv_outstanding[$hp_bm_no]=($bm_total - $row2[0]);
							$downcol_type[$hp_bm_no]=$hp_type;
							$downcol_cust_id[$hp_bm_no]=$hp_cust_id;
							$downcol_cust_name[$hp_bm_no]=$hp_cust_name;
							$downcol_tel[$hp_bm_no]=$hp_mobile;
						}
						$inv_no=$hp_bm_no;
					}
			}
		}
	}
}

?>