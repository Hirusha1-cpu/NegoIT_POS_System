<?php
	$invoice_no=$rag_id=array();
	$msg='';
	$out=true;
	include('config.php');
	
	if(isset($_POST['hp_validation'])){
		$hp_validation=$_POST['hp_validation'];
		$hp_date=$_POST['hp_date'];
		$inv_no=$_POST['inv_no'];
		$hp_type=$_POST['hp_type'];
		$hp_amount=$_POST['hp_amount'];
		$hp_count=$_POST['hp_count'];
		$bm_date0=$_POST['bm_date'].' 10:00:00';
		$recovery_agent0=$_POST['recovery_agent'];
		$salesman0=$_POST['salesman'];
		if($hp_validation==1){
			$msg='Schedule was Created Successfully';
			/*
			print $inv_no.'<br />';
			print $hp_type.'<br />';
			print $hp_date.'<br />';
			print $hp_amount.'<br />';
			print $hp_count.'<br />';
			*/
			$query="SELECT COUNT(id) FROM hp_inv_schedule WHERE invoice_no='$inv_no'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			if($row[0]!=0){ $out=false; $msg='Error: Schedule is Already Available'; };
			
			if($out){
				$query="UPDATE `bill_main` SET `recovery_agent`='$recovery_agent0',`billed_by`='$salesman0',`billed_timestamp`='$bm_date0' WHERE invoice_no='$inv_no'";
				$result=mysqli_query($conn,$query);
			}
	
		    if($out){
				$query="INSERT INTO `hp_inv_schedule` (`invoice_no`,`type`,`day`,`payment_amount`,`payment_count`) VALUES ('$inv_no','$hp_type','$hp_date','$hp_amount','$hp_count')";
		   	 	$result=mysqli_query($conn,$query);
		   	 	if(!$result){ $out=false; $msg='Error: Failed to add the Hire Purchasing Schedule'; }
		    }
		}
	}
	
	$query="SELECT bm.invoice_no,cu.`name`,itm.description,bi.qty,bi.unit_price,bm.`invoice_+total` + bm.`invoice_-total`,bm.recovery_agent,bm.billed_by,date(bm.billed_timestamp) FROM bill_main bm, bill bi, cust cu, inventory_items itm WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`=cu.id AND bi.item=itm.id AND cu.`status`='1' AND bm.`status`!='0' AND bm.invoice_no NOT IN (SELECT invoice_no FROM hp_inv_schedule)";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$invoice_no[]=$row[0];
		$cust_name[]=$row[1];
		$item[]=$row[2];
		$qty[]=$row[3];
		$unit_price[]=$row[4];
		$bm_total[]=$row[5];
		$bm_rec[]=$row[6];
		$billed_by[]=$row[7];
		$bm_date[]=$row[8];
	}
	
	
	$query="SELECT id,`name` FROM hp_schedule_type WHERE `status`='1'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$hp_type_id[]=$row[0]; 
		$hp_type_name[]=$row[1]; 
	}
	
	$query="SELECT id,username FROM userprofile WHERE `status`=0 ORDER BY username";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$sm_id[]=$row[0];
		$sm_name[]=$row[1];
	} 

	$query="SELECT DISTINCT up.id,up.username FROM userprofile up, permission pe, `function` fn WHERE up.id=pe.`user` AND pe.`function`=fn.id AND up.`sub_system`='0' AND up.`status`='0' AND fn.`status`=1 AND fn.`name`='Hire Purchase' ORDER BY up.username";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$rag_id[]=$row[0];
		$rag_name[]=$row[1];
	} 
	
	
if(isset($_COOKIE['user_id'])){	
?>

<html>
<head>
<script type="text/javascript">
function HPcal(){
	$hp_amount=document.getElementById('hp_amount').value;
	$hp_count=document.getElementById('hp_count').value;
	if(($hp_amount>0)&&($hp_count>0)){
		$hp_total=$hp_amount * $hp_count;
		document.getElementById('hp_total').value=$hp_total;
	}
}

function setHPType($type){
	if($type==''){
		document.getElementById('hp_validation').value=0;
		document.getElementById('hp_status').innerHTML='<span style="color:red">Fail</span>';
		document.getElementById('hp_date_list1').value='';
		document.getElementById('hp_date_list2').value='';
	}
	if($type==1){
		document.getElementById('div_hp_date1').style.display="block";
		document.getElementById('div_hp_date2').style.display="none";
		document.getElementById('hp_validation').value=0;
		document.getElementById('hp_status').innerHTML='<span style="color:red">Fail</span>';
		document.getElementById('hp_date_list1').value='';
	}
	if($type==2){
		document.getElementById('div_hp_date1').style.display="none";
		document.getElementById('div_hp_date2').style.display="block";
		document.getElementById('hp_validation').value=0;
		document.getElementById('hp_status').innerHTML='<span style="color:red">Fail</span>';
		document.getElementById('hp_date_list2').value='';
	}
	if($type==3){
		document.getElementById('div_hp_date1').style.display="none";
		document.getElementById('div_hp_date2').style.display="none";
		document.getElementById('hp_validation').value=1;
		document.getElementById('hp_status').innerHTML='<span style="color:green">Pass</span>';
		document.getElementById('hp_date').value=0;
	}
}

function setHPDate($date){
	document.getElementById('hp_date').value=$date;
	if(document.getElementById('hp_type').value!=''){
		if($date!=''){
			document.getElementById('hp_validation').value=1;
			document.getElementById('hp_status').innerHTML='<span style="color:green">Pass</span>';
		}else{
			document.getElementById('hp_validation').value=0;
			document.getElementById('hp_status').innerHTML='<span style="color:red">Fail</span>';
		}
	}else{
		document.getElementById('hp_validation').value=0;
		document.getElementById('hp_status').innerHTML='<span style="color:red">Fail</span>';
	}
}

function validateForm(){
	$hp_validation=document.getElementById('hp_validation').value;
	$hp_amount=document.getElementById('hp_amount').value;
	$hp_count=document.getElementById('hp_count').value;
	$bm_date=document.getElementById('bm_date').value;
	$recovery_agent=document.getElementById('recovery_agent').value;
	$salesman=document.getElementById('salesman').value;
	$count=0;
	if($hp_validation!=1) $count++;
	if($hp_amount=='') $count++;
	if($hp_amount==0) $count++;
	if($hp_count=='') $count++;
	if($hp_count==0) $count++;
	if($bm_date=='') $count++;
	if($recovery_agent=='') $count++;
	if($salesman=='') $count++;
	if($count==0){
		document.getElementById('div_submit').innerHTML=document.getElementById('loading').innerHTML;
		return true;
	}else{
		alert('Validation Failed');
		return false;
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
			<tr style="background-color:gray; color:white"><th>Invoice No</th><th style="padding-left:10px; padding-right:10px;">Cust</th><th>Item</th><th>QTY</th><th>U/price</th><th>Total</th><th>Invoice Total</th></tr>
			<?php
			$inv_tmp='';
			$selected_cust=$selected_bb_total=$selected_bm_rec=$selected_bm_sm='';
			for($i=0;$i<sizeof($invoice_no);$i++){
				if($inv==$invoice_no[$i]){
					$selected_cust=$cust_name[$i];
					$selected_bb_total=$bm_total[$i];
					$selected_bm_rec=$bm_rec[$i];
					$selected_bm_sm=$billed_by[$i];
					$selected_bm_date=$bm_date[$i];
				}
			
				if($inv_tmp!=$invoice_no[$i]){
					print '<tr><td  colspan="7"></td></tr>';
					print '<tr style="background-color:#EEEEEE;"><td style="padding-left:10px; padding-right:10px;"><a style="text-decoration:none" href="test3.php?inv='.$invoice_no[$i].'">'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td style="padding-left:10px; padding-right:10px;">'.$cust_name[$i].'</td><td style="padding-left:10px; padding-right:10px;">'.$item[$i].'</td><td style="padding-left:10px; padding-right:10px;" align="right">'.number_format($qty[$i]).'</td><td style="padding-left:10px; padding-right:10px;" align="right">'.number_format($unit_price[$i]).'</td><td style="padding-left:10px; padding-right:10px;" align="right">'.number_format($qty[$i] * $unit_price[$i]).'</td><td style="padding-left:10px; padding-right:10px;" align="right"><strong>'.number_format($bm_total[$i]).'</strong></td></tr>';
				}else{
					print '<tr style="background-color:#EEEEEE;"><td></td><td></td><td style="padding-left:10px; padding-right:10px;">'.$item[$i].'</td><td style="padding-left:10px; padding-right:10px;" align="right">'.number_format($qty[$i]).'</td><td style="padding-left:10px; padding-right:10px;" align="right">'.number_format($unit_price[$i]).'</td><td style="padding-left:10px; padding-right:10px;" align="right">'.number_format($qty[$i] * $unit_price[$i]).'</td><td></td></tr>';
				}
				$inv_tmp=$invoice_no[$i];
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
					<tr><td>Invoice Date </td><td>: <input type="date" id="bm_date" name="bm_date" value="<?php print $selected_bm_date; ?>" /></td></tr>
					<tr><td>Salesman </td><td>: 
						<select id="salesman" name="salesman">
							<option value="">-SELECT-</option>
						<?php for($i=0;$i<sizeof($sm_id);$i++){
							if($sm_id[$i]==$selected_bm_sm) $select='selected="selected"'; else $select='';
							print '<option value="'.$sm_id[$i].'" '.$select.' >'.ucfirst($sm_name[$i]).'</option>';
						} ?>
						</select>
					</td></tr>
					<tr><td>Recovery Agent </td><td>: 
						<select id="recovery_agent" name="recovery_agent">
							<option value="">-SELECT-</option>
						<?php for($i=0;$i<sizeof($rag_id);$i++){
							if($rag_id[$i]==$selected_bm_rec) $select='selected="selected"'; else $select='';
							print '<option value="'.$rag_id[$i].'" '.$select.' >'.ucfirst($rag_name[$i]).'</option>';
						} ?>
						</select>
					</td></tr>
				</table>
				<hr />
				<table style="font-size:12pt; font-family:Calibri" bgcolor="#E7EEF3">
				<tr><td colspan="2" align="center" style="background-color:#467898; color:white;">Hire Purchase Instalment</td></tr>
				<tr><td class="sidetable1">Schedule Type</td><td class="sidetable2">
					<select id="hp_type" name="hp_type" onchange="setHPType(this.value)">
						<option value="">-SELECT-</option>
						<?php
						for($i=0;$i<sizeof($hp_type_id);$i++){
							print '<option value="'.$hp_type_id[$i].'">'.$hp_type_name[$i].'</option>';
						}
						?>
					</select>
				</td></tr>
				<tr><td class="sidetable1">Date</td><td class="sidetable2">
					<div id="div_hp_date1" style="display:none" >
					<select id="hp_date_list1" onchange="setHPDate(this.value)">
						<option value="" >-SELECT-</option>
						<?php
						for($i=1;$i<=28;$i++){
							print '<option value="'.$i.'">'.$i.'</option>';
						}
						?>
					</select>
					</div>
					<div id="div_hp_date2" style="display:none" >
					<select id="hp_date_list2" onchange="setHPDate(this.value)">
						<option value="" >-SELECT-</option>
						<option value="1" >Sunday</option>
						<option value="2" >Monday</option>
						<option value="3" >Tuesday</option>
						<option value="4" >Wednesday</option>
						<option value="5" >Thursday</option>
						<option value="6" >Friday</option>
						<option value="7" >Saturday</option>
					</select>
					</div>
			
				</td></tr>
				<tr><td colspan="2">
					<table cellspacing="0">
					<tr bgcolor="#DDDDDD" style="font-size:9pt"><th>Amount</th><th bgcolor="#EEEEEE"></th><th>Instalment Count</th><th bgcolor="#EEEEEE"></th><th>Total</th><th></th></tr>
					<tr bgcolor="#EEEEEE"><td align="center"><input type="number" id="hp_amount" name="hp_amount" style="width:80px; text-align:right;" onclick="this.value=''" /></td><td align="center" bgcolor="#EEEEEE">X</td><td align="center"><input type="number" id="hp_count" name="hp_count" style="width:80px; text-align:right;" onclick="this.value=''" /></td><td align="center" bgcolor="#EEEEEE">=</td><td align="center"><input type="number" id="hp_total" style="width:80px; text-align:right;" readonly="readonly" /></td><td align="center"><input type="button" value="Cal" onclick="HPcal()" /></td></tr>
					</table>
				</td></tr>
				<tr><td class="sidetable1">Status</td><td align="center" class="sidetable2"><div id="hp_status"></div></td></tr>
				</table>
				<div id="div_submit"><input type="submit" style="width:100px; height:40px" value="Add Schedule" /></div>
			</form>
		<?php } ?>
	</td></tr>
</table>
</body>
</html>
<?php }else{
	print 'Please Log In to the System';
} ?>