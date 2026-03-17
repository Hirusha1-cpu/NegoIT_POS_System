<?php
	$invoice_no=$rag_id=array();
	$msg='';
	$out=true;
	include('config.php');
	
	if(isset($_POST['hp_validation'])){
		$hp_validation=$_POST['hp_validation'];
		$cal_start=$_POST['cal_start'];
		$hp_date=$_POST['hp_date'];
		$inv_no=$_POST['inv_no'];
		$hp_type=$_POST['hp_type'];
		$hp_amount=$_POST['hp_amount'];
		$hp_count=$_POST['hp_count'];
		$bm_date0=$_POST['bm_date'].' 10:00:00';
		$recovery_agent0=$_POST['recovery_agent'];
		$salesman0=$_POST['salesman'];
		$his_id=$_POST['his_id'];
		$bi_id=$_POST['bi_id'];
		$bi_qty=$_POST['bi_qty'];
		if($hp_validation==1){
			$msg='Invoice No: '.str_pad($inv_no, 7, "0", STR_PAD_LEFT).' Schedule was Updated Successfully';
			/*
			print $inv_no.'<br />';
			print $hp_type.'<br />';
			print $hp_date.'<br />';
			print $hp_amount.'<br />';
			print $hp_count.'<br />';
			*/
			
			if($out){
				$query="UPDATE `bill` SET `qty`='$bi_qty' WHERE `id`='$bi_id'";
				$result=mysqli_query($conn,$query);
		   	 	if(!$result){ $out=false; $msg='Error: Failed to Update Bill QTY'; }
			}
				
			if($out){
				$query="SELECT SUM(unit_price * qty) FROM bill WHERE qty>0 AND invoice_no='$inv_no'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				if($row[0]!='') $up_total=$row[0]; else $up_total=0;
				$query="SELECT SUM(unit_price * qty) FROM bill WHERE qty<0 AND invoice_no='$inv_no'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				if($row[0]!='') $down_total=$row[0]; else $down_total=0;
			
				$query="UPDATE `bill_main` SET `recovery_agent`='$recovery_agent0',`billed_by`='$salesman0',`billed_timestamp`='$bm_date0',`invoice_+total`='$up_total',`invoice_-total`='$down_total' WHERE invoice_no='$inv_no'";
				$result=mysqli_query($conn,$query);
		   	 	if(!$result){ $out=false; $msg='Error: Failed to Update Bill Details'; }
			}

		    if($out){
				$query="UPDATE `hp_inv_schedule` SET `cal_start_date`='$cal_start',`type`='$hp_type',`day`='$hp_date',`payment_amount`='$hp_amount',`payment_count`='$hp_count' WHERE id='$his_id'";
		   	 	$result=mysqli_query($conn,$query);
		   	 	if(!$result){ $out=false; $msg='Error: Failed to Updated the Hire Purchasing Schedule'; }
		    }
		}
	}
	
	if(isset($_GET['inv'])){
		$inv=$_GET['inv'];
		$query="SELECT id,cal_start_date,`type`,`day`,payment_amount,payment_count FROM hp_inv_schedule WHERE invoice_no='$inv'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$his_id=$row[0];
		$his_cal_start=$row[1];
		$his_type=$row[2];
		$his_day=$row[3];
		$his_amount=$row[4];
		$his_count=$row[5];
		
		$query="SELECT bi.id,bi.qty,bi.unit_price,itm.description FROM  bill bi, inventory_items itm WHERE bi.item=itm.id AND bi.invoice_no='$inv'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$bi_id=$row[0];
			$bi_qty=$row[1];
			$bi_uprice=$row[2];
			$bi_desc=$row[3];
		}
	}
	
	
	$query="SELECT bm.invoice_no,cu.`name`,itm.description,bi.qty,bi.unit_price,bm.`invoice_+total` + bm.`invoice_-total`,bm.recovery_agent,bm.billed_by,date(bm.billed_timestamp) FROM bill_main bm, bill bi, cust cu, inventory_items itm WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`=cu.id AND bi.item=itm.id AND cu.`status`='1' AND bm.`status`!='0' AND bm.invoice_no IN (SELECT invoice_no FROM hp_inv_schedule) AND bm.type='2'";
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
	}
	if($type==2){
		document.getElementById('div_hp_date1').style.display="none";
		document.getElementById('div_hp_date2').style.display="block";
		document.getElementById('hp_validation').value=0;
		document.getElementById('hp_status').innerHTML='<span style="color:red">Fail</span>';
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
					print '<tr style="background-color:#EEEEEE;"><td style="padding-left:10px; padding-right:10px;"><a style="text-decoration:none" href="test6.php?inv='.$invoice_no[$i].'">'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td style="padding-left:10px; padding-right:10px;">'.$cust_name[$i].'</td><td style="padding-left:10px; padding-right:10px;">'.$item[$i].'</td><td style="padding-left:10px; padding-right:10px;" align="right">'.number_format($qty[$i]).'</td><td style="padding-left:10px; padding-right:10px;" align="right">'.number_format($unit_price[$i]).'</td><td style="padding-left:10px; padding-right:10px;" align="right">'.number_format($qty[$i] * $unit_price[$i]).'</td><td style="padding-left:10px; padding-right:10px;" align="right"><strong>'.number_format($bm_total[$i]).'</strong></td></tr>';
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
		<form action="test6.php?inv=<?php if(isset($_GET['inv'])) print $_GET['inv']; ?>" onsubmit="return validateForm()" method="post">
			<input type="hidden" id="hp_validation" name="hp_validation" value="0" />
			<input type="hidden" id="hp_date" name="hp_date" value="0" />
			<?php if($inv!=0){ ?>
				<input type="hidden" id="inv_no" name="inv_no" value="<?php print $inv; ?>" />
				<input type="hidden" id="his_id" name="his_id" value="<?php print $his_id; ?>" />
				
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
					<tr><td>Bill Item </td><td>
						 <input type="hidden" id="bi_id" name="bi_id" value="<?php print $bi_id; ?>" />
						 <table>
						 <tr bgcolor="#DDDDDD"><th>Item Desc</th><th>Unit Price</th><th>QTY</th></tr>
						 <tr bgcolor="#EEEEEE">
						 <td><?php print $bi_desc; ?></td>
						 <td><input type="number" id="uprice" name="uprice" readonly="readonly" value="<?php print $bi_uprice; ?>" style="width:40px; text-align:right;" /></td>
						 <td><input type="number" id="bi_qty" name="bi_qty" value="<?php print $bi_qty; ?>" style="width:70px; text-align:right;" /></td>
						 </tr>
						 </table>
					</td></tr>
				</table>
				<hr />
				<table style="font-size:12pt; font-family:Calibri" bgcolor="#E7EEF3">
				<tr><td colspan="2" align="center" style="background-color:#467898; color:white;">Hire Purchase Instalment</td></tr>
				<tr><td class="sidetable1">Cal Start Date</td><td class="sidetable2"><input type="date" id="cal_start" name="cal_start" value="<?php print $his_cal_start; ?>" /></td></tr>
				<tr><td class="sidetable1">Schedule Type</td><td class="sidetable2">
					<select id="hp_type" name="hp_type" onchange="setHPType(this.value)">
						<option value="" >-SELECT-</option>
						<?php
						for($i=0;$i<sizeof($hp_type_id);$i++){
							if($hp_type_id[$i]==$his_type) $select='selected="selected"'; else $select='';
							print '<option value="'.$hp_type_id[$i].'" '.$select.'>'.$hp_type_name[$i].'</option>';
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
							if($i==$his_day) $select='selected="selected"'; else $select='';
							print '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
						}
						?>
					</select>
					</div>
					<div id="div_hp_date2" style="display:none" >
					<select id="hp_date_list2" onchange="setHPDate(this.value)">
						<option value="" >-SELECT-</option>
						<option value="1" <?php if($his_day==1) print 'selected="selected"'; ?> >Sunday</option>
						<option value="2" <?php if($his_day==2) print 'selected="selected"'; ?> >Monday</option>
						<option value="3" <?php if($his_day==3) print 'selected="selected"'; ?> >Tuesday</option>
						<option value="4" <?php if($his_day==4) print 'selected="selected"'; ?> >Wednesday</option>
						<option value="5" <?php if($his_day==5) print 'selected="selected"'; ?> >Thursday</option>
						<option value="6" <?php if($his_day==6) print 'selected="selected"'; ?> >Friday</option>
						<option value="7" <?php if($his_day==7) print 'selected="selected"'; ?> >Saturday</option>
					</select>
					</div>
			
				</td></tr>
				<tr><td colspan="2">
					<table cellspacing="0">
					<tr bgcolor="#DDDDDD" style="font-size:9pt"><th>Amount</th><th bgcolor="#EEEEEE"></th><th>Instalment Count</th><th bgcolor="#EEEEEE"></th><th>Total</th><th></th></tr>
					<tr bgcolor="#EEEEEE"><td align="center"><input type="number" id="hp_amount" name="hp_amount" style="width:80px; text-align:right;" onclick="this.value=''" value="<?php print $his_amount; ?>" /></td><td align="center" bgcolor="#EEEEEE">X</td><td align="center"><input type="number" id="hp_count" name="hp_count" style="width:80px; text-align:right;" onclick="this.value=''" value="<?php print $his_count; ?>" /></td><td align="center" bgcolor="#EEEEEE">=</td><td align="center"><input type="number" id="hp_total" style="width:80px; text-align:right;" readonly="readonly" /></td><td align="center"><input type="button" value="Cal" onclick="HPcal()" /></td></tr>
					</table>
				</td></tr>
				<tr><td class="sidetable1">Status</td><td align="center" class="sidetable2"><div id="hp_status"></div></td></tr>
				</table>
				<div id="div_submit"><input type="submit" style="width:120px; height:40px" value="Update Schedule" /></div>
			</form>
		<?php } ?>
	</td></tr>
</table>

<script>
setHPType(<?php print $his_type; ?>);
setHPDate(<?php print $his_day; ?>);
HPcal();
</script>
</body>
</html>
<?php }else{
	print 'Please Log In to the System';
} ?>