<?php
function weekday($action){
	switch ($action){
		case 1: 
			$action_out='Sunday';
		break;
		case 2: 
			$action_out='Monday';
		break;
		case 3: 
			$action_out='Tuesday';
		break;
		case 4: 
			$action_out='Wednesday';
		break;
		case 5: 
			$action_out='Thursday';
		break;
		case 6: 
			$action_out='Friday';
		break;
		case 7: 
			$action_out='Saturday';
		break;
	}
	return $action_out;
}

function hpsPaySchedule($cal_start_date,$hp_type,$hp_date,$hp_count){
	$hp_schedule=array();
	$start_date=substr($cal_start_date, 0, -2).$hp_date;
	
	if($hp_type=='Monthly'){
		for($i=1;$i<=$hp_count;$i++){
			$hp_schedule[$i]=date('Y-m-d', strtotime('+'.$i.' month', strtotime($start_date)));
		}
	}
	if($hp_type=='Weekly'){
		$day=weekday($hp_date);
		for($i=1;$i<=$hp_count;$i++){
			$hp_schedule[$i]=date('Y-m-d', strtotime('+'.$i.' week '.$day, strtotime($cal_start_date)));
		}
	}
	if($hp_type=='Daily'){
		for($i=1;$i<=$hp_count;$i++){
			$hp_schedule[$i]=date('Y-m-d', strtotime('+'.$i.' day', strtotime($cal_start_date)));
		}
	}
	return $hp_schedule;
}

	$invoice_no=$rag_id=array();
	$msg='';
	$out=true;
	include('config.php');
	

	$query="SELECT bm.invoice_no,cu.`name`,bm.`invoice_+total` + bm.`invoice_-total`,date(bm.billed_timestamp) FROM bill_main bm, bill bi, cust cu WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`=cu.id AND cu.`status`='1' AND bm.`status`!='0' GROUP BY bm.invoice_no";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$invoice_no[]=$row[0];
		$cust_name[]=$row[1];
		$bm_total[]=$row[2];
		$bm_date[]=$row[3];
	}
	
if(isset($_GET['inv'])){
	$pay_id=$pay_sm=$hp_schedule=$paid_dates=array();
	
	$bm_inv0=$_GET['inv'];
	$query="SELECT id,amount,date(payment_date),salesman FROM payment WHERE `status`=0 AND invoice_no='$bm_inv0' AND id NOT IN (SELECT payment_no FROM hp_payments)";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$pay_id[]=$row[0];
		$pay_amount[]=$row[1];
		$pay_pdate[]=$row[2];
		$pay_sm[]=$row[3];
	}
	
	$query="SELECT id,username FROM userprofile WHERE `status`=0 ORDER BY username";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$sm_id[]=$row[0];
		$sm_name[]=$row[1];
	} 
	
	$query="SELECT hst.`name`,his.`day`,his.payment_amount,his.payment_count,date(bm.billed_timestamp) FROM bill_main bm, hp_inv_schedule his, hp_schedule_type hst WHERE bm.invoice_no=his.invoice_no AND his.`type`=hst.id AND his.invoice_no='$bm_inv0'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$hp_type=$row[0];
	$hp_date=$row[1];
	$hp_amount=$row[2];
	$hp_count=$row[3];
	$bm_date1=$row[4];
	$hp_schedule=hpsPaySchedule($bm_date1,$hp_type,$hp_date,$hp_count);
		
	$query="SELECT instalment_date FROM hp_payments WHERE invoice_no='$bm_inv0'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$paid_dates[]=$row[0];
	}
	$hp_schedule=array_values(array_diff($hp_schedule,$paid_dates));
}

if(isset($_COOKIE['user_id'])){
 
?>

<html>
<head>
<script type="text/javascript">

function mapPaymentHP($pay_id){
	$date=document.getElementById('date_'+$pay_id).value;
	$salesman=document.getElementById('salesman_'+$pay_id).value;
	$schedule=document.getElementById('schedule_'+$pay_id).value;
	if(($date!='')&&($salesman!='')&($schedule!='')){
		document.getElementById('div_map_'+$pay_id).innerHTML=document.getElementById('loading').innerHTML;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var returntext=xmlhttp.responseText;
				if(returntext=='Done')
					document.getElementById('div_map_'+$pay_id).innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Done</span>';
				else
					document.getElementById('div_map_'+$pay_id).innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+returntext+'</span>';
			}
		};
		xmlhttp.open("POST", "test5.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('pay_id='+$pay_id+'&date='+$date+'&salesman='+$salesman+'&schedule='+$schedule);
		
	}else{
		alert('Please fill the form data');
	}
}


</script>
</head>
<body>
<?php
	$inv=0;
	if(isset($_GET['inv'])) $inv=$_GET['inv'];
?>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<table align="center">
	<tr><td>
		<table width="100%" cellspacing="0" style="font-family:Calibri">
			<tr style="background-color:gray; color:white"><th>Invoice No</th><th style="padding-left:10px; padding-right:10px;">Cust</th><th>Invoice Total</th></tr>
			<?php
			$inv_tmp='';
			$selected_cust=$selected_bb_total=$selected_bm_date='';
			for($i=0;$i<sizeof($invoice_no);$i++){
				if($inv==$invoice_no[$i]){
					$selected_cust=$cust_name[$i];
					$selected_bb_total=$bm_total[$i];
					$selected_bm_date=$bm_date[$i];
				}
			
					print '<tr><td  colspan="7"></td></tr>';
					print '<tr style="background-color:#EEEEEE;"><td style="padding-left:10px; padding-right:10px;"><a style="text-decoration:none" href="test4.php?inv='.$invoice_no[$i].'">'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td style="padding-left:10px; padding-right:10px;">'.$cust_name[$i].'</td><td style="padding-left:10px; padding-right:10px;" align="right"><strong>'.number_format($bm_total[$i]).'</strong></td></tr>';
			}
			?>
		</table>
	</td><td width="50px"></td><td valign="top">
		<?php
			if($out) 
				print '<h2 style="font-size:12pt; font-family:Calibri; color:green;">'.$msg.'<h2>';
			else
				print '<h2 style="font-size:12pt; font-family:Calibri; color:red;">'.$msg.'<h2>';
		?>
		<form action="test3.php" onsubmit="return validateForm()" method="post">
			<input type="hidden" id="hp_validation" name="hp_validation" value="0" />
			<input type="hidden" id="hp_date" name="hp_date" value="0" />
			<?php if($inv!=0){ ?>
				<input type="hidden" id="inv_no" name="inv_no" value="<?php print $inv; ?>" />
				
				<table style="font-size:12pt; font-family:Calibri" bgcolor="#E7EEF3">
					<tr><td>Selected Invoice No </td><td><a style="text-decoration:none;" href="index.php?components=bill2&action=finish_bill&id=<?php print $inv; ?>" target="_blank">: <strong><?php print str_pad($inv, 7, "0", STR_PAD_LEFT); ?></strong></a></td></tr>
					<tr><td>Selected Cust </td><td>: <strong><?php print $selected_cust; ?></strong></td></tr>
					<tr><td>Selected Total </td><td>: <strong><?php print number_format($selected_bb_total); ?></strong></td></tr>
					<tr><td>Invoice Date </td><td>: <?php print $selected_bm_date; ?></td></tr>
				</table>
				<hr />
				<table style="font-size:12pt; font-family:Calibri" bgcolor="#E7EEF3">
				<tr><td colspan="6" align="center" style="background-color:#467898; color:white;">Hire Purchase Payment Mapping</td></tr>
				<tr style="background-color:#DDDDDD; color:white;"><th>Payment ID</th><th>Amount</th><th>Date</th><th>Salesman</th><th>Instalment Date</th><th></th></tr>
				<tr><td class="sidetable1">
					<?php
						for($i=0;$i<sizeof($pay_id);$i++){
							print '<tr><td>'.str_pad($pay_id[$i], 7, "0", STR_PAD_LEFT).'</td><td align="right">'.number_format($pay_amount[$i]).'</td>';
							print '<td><input type="date" id="date_'.$pay_id[$i].'" name="date" value="'.$pay_pdate[$i].'" /></td>';
							print '<td><select id="salesman_'.$pay_id[$i].'" name="salesman">';
							for($j=0;$j<sizeof($sm_id);$j++){
								if($sm_id[$j]==$pay_sm[$i]) $select='selected="selected"'; else $select='';
								print '<option value="'.$sm_id[$j].'" '.$select.' >'.ucfirst($sm_name[$j]).'</option>';
							}
							print '</select>';
							print '</td><td><select id="schedule_'.$pay_id[$i].'" name="schedule">';
							print '<option value="">-SELECT-</option>';
							for($j=0;$j<sizeof($hp_schedule);$j++){
								print '<option value="'.$hp_schedule[$j].'" >'.ucfirst($hp_schedule[$j]).'</option>';
							}
							print '</select>';
							print '</td><td>';
							print '<div id="div_map_'.$pay_id[$i].'"><input type="button" value="MAP" onclick="mapPaymentHP('.$pay_id[$i].')" /></div>';
							print '</td>';
							print '</tr>';
						}
					?>
				</td></tr>
				</table>
			</form>
		<?php } ?>
	</td></tr>
</table>
</body>
</html>
<?php }else{
	print 'Please Log In to the System';
} ?>