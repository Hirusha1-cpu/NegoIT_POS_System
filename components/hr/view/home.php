<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">
	function calLeaveDays($case){
	<?php for($i=0;$i<sizeof($leave_id);$i++){
		  print 'document.getElementById("lt_div_'.$leave_id[$i].'").innerHTML=document.getElementById("lt_hid_'.$leave_id[$i].'").value;';
	}?>

		$proceed=false;
		$msg='';
		$from_date=document.getElementById('from_date').value;
		$to_date=document.getElementById('to_date').value;
		$type=document.getElementById('type').value;		
         if($type==''){ $proceed=false; $msg='Please Select Leave Type'; }else{ $proceed=true; }
         if($proceed){
         	if($type=='3' || $type=='4'){
         		if(document.getElementById('hid0').value=='0'){
	         		document.getElementById('div_hid1').innerHTML=document.getElementById('div_todate2').innerHTML;
	         		document.getElementById('div_todate1').innerHTML='';
	         		document.getElementById('hid0').value='1';
         		}
	         	document.getElementById('div_todate2').innerHTML='<input type="hidden" name="to_date" value="'+$from_date+'" />';
	         	$to_date=$from_date;
         	}else{
         		if(document.getElementById('hid0').value=='1'){
	         		document.getElementById('div_todate1').innerHTML='To Date';
	         		document.getElementById('div_todate2').innerHTML=document.getElementById('div_hid1').innerHTML;
	         		document.getElementById('div_hid1').innerHTML='';
         			document.getElementById('hid0').value='0';
         		}
         	}
         }
         
         var date1=new Date($from_date);
         var date2=new Date($to_date);
         var diff=date2.getTime() - date1.getTime();
		 if($from_date=='' || $to_date==''){ $proceed=false; $msg='Invalid date'; $case=2; }
         if($proceed){
		     if(diff>=0){
			 	var timeDiff = Math.abs(date2.getTime() - date1.getTime());
			    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24))+1;
			    document.getElementById('l_days0').value=diffDays;
			    document.getElementById('l_days').value=diffDays;
			    $remaining=document.getElementById('lt_hid_'+$type).value;
			    $remaining=$remaining-diffDays;
				if($remaining<0){ 
					if($type=='5' || $type=='6'){
				    	document.getElementById('lt_div_'+$type).innerHTML=$remaining;
					}else{
					  	$proceed=false; 
					   	$msg='Leave Count Exceeded'; 
				   	}
				}else{
				    document.getElementById('lt_div_'+$type).innerHTML=$remaining;
				}
		     }else{
			    document.getElementById('l_days0').value=0;
			    document.getElementById('l_days').value=0;
		        $msg='Invalid date';
		        $proceed=false;
		     }
		 }
		 if($proceed){
		 	return true;
		 }else{
		 	if($case==1) alert($msg);
		 	return false;
		 }
 	}
 	
 	function setLeaveUser(){
		$leave_user=document.getElementById('leave_user').value;
		window.location = 'index.php?components=hr&action=home&leave_user='+$leave_user;
 	}
 	
 	function leaveFormValidate(){
 		if(calLeaveDays(1)){
 			document.getElementById('div_apply').innerHTML=document.getElementById('loading').innerHTML; 
		 	return true;
 		}else{
		 	return false;
 		}
 	}
</script>
<!-- -------------------------------------------------------------------------------------------------------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?></td></tr></table>

<input type="hidden" id="hid0" value="0" />
<div id="div_hid1" style="display:none"></div>
<table align="center"><tr><td valign="top">
	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
		<form method="post" action="index.php?components=hr&action=apply_leave" onsubmit="return leaveFormValidate()">
		<table border="0" cellspacing="0" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr><td colspan="6" height="10px"></td></tr>
			<tr bgcolor="#FAFAFA"><td class="shipmentTB4"><strong>Leave Type</strong></td><td>
				<select id="type" name="type" onchange="calLeaveDays(2)">
					<option value="">-SELECT-</option>
					<?php 
					for($i=0;$i<sizeof($leave_id);$i++){
						print '<option value="'.$leave_id[$i].'">'.$leave_name[$i].'</option>';
					}
					?>
				</select>	
			</td><td></td>
			<?php if($approver){
					print '<td><strong>User</strong></td><td>';
					print '<select id="leave_user" name="leave_user" onchange="setLeaveUser()">';
						for($i=0;$i<sizeof($user_id);$i++){
							if($user_id[$i]==$user) $select='selected="selected"'; else $select='';
							print '<option value="'.$user_id[$i].'" '.$select.'>'.ucfirst($user_name[$i]).'</option>';
						}
					print '</select>';
				}else{
					print '<td></td><td>';
					print '<input type="hidden" id="leave_user" name="leave_user" value="'.$user.'" />';
				}
				?>
			</td><td width="8px"></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#F5F5F5"><td class="shipmentTB4"><strong>Start Date</strong></td><td><input type="date" id="from_date" name="from_date" onchange="calLeaveDays(1)" /></td><td width="10px"></td><td><strong><div id="div_todate1">To Date</div></strong></td><td><div id="div_todate2"><input type="date" id="to_date" name="to_date" onchange="calLeaveDays(1)" /></div></td><td></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#FAFAFA"><td class="shipmentTB4"><strong>Leave Days</strong></td><td colspan="4"><input type="text" id="l_days0" disabled="disabled" /><input type="hidden" id="l_days" name="l_days" /></td><td></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#F5F5F5"><td class="shipmentTB4"><strong>Reason</strong></td><td colspan="4"><textarea id="reason" name="reason" rows="4" style="width:99%"></textarea></td><td></td></tr>
			<tr><td colspan="6" align="center"><div id="div_apply"><input type="submit" value="Apply" style="width:90px; height:30px" /></div></td></tr>
			<tr><td colspan="6" height="10px"></td></tr>
		</table>
		</form>
	</td></tr></table>
</td><td width="20px"></td><td valign="top">
	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
		<table border="0" cellspacing="0" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr><td colspan="2" class="shipmentTB4" bgcolor="#467898" style="color:#F5F5F5">Leave Balance</td></tr>
			<?php 
			for($i=0;$i<sizeof($leave_id);$i++){
				if($leave_name[$i]=='Half Day'){
					print '<tr><td bgcolor="#EEEEEE" class="shipmentTB4"></td><td  bgcolor="#FAFAFA" class="shipmentTB4" align="right" width="50px"><div id="lt_div_'.$leave_id[$i].'" style="display:none;">'.round($remaining_days[$i],2).'</div> <input type="hidden" id="lt_hid_'.$leave_id[$i].'" value="'.round($remaining_days[$i],2).'" /></td></tr>';
				}else{
					print '<tr><td bgcolor="#EEEEEE" class="shipmentTB4">'.$leave_name[$i].'</td><td  bgcolor="#FAFAFA" class="shipmentTB4" align="right" width="50px"><div id="lt_div_'.$leave_id[$i].'">'.round($remaining_days[$i],2).'</div> <input type="hidden" id="lt_hid_'.$leave_id[$i].'" value="'.round($remaining_days[$i],2).'" /></td></tr>';
				}
			}
			?>
		</table>
	</td></tr></table>
</td></tr>
<tr><td colspan="3" height="10px"><hr /></td></tr>
<tr><td colspan="3">
<!-- -----------------------------------LEAVE LOG------------------------------------------- -->
	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
		<table border="0" cellspacing="1" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4">Leave Type</th><th class="shipmentTB4">From Date</th><th class="shipmentTB4">To Date</th><th class="shipmentTB4">Duration</th><th class="shipmentTB4" width="150px">Reason</th><th class="shipmentTB4">Status</th></tr>
			<?php 
			if($_COOKIE['user_id']==$user) $link=true; else $link=false;
			for($i=0;$i<sizeof($llog_id);$i++){
				if($link) $url='index.php?components=hr&action=my_leave&id='.$llog_id[$i]; else $url='#';
				if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
				if(strlen($llog_reason[$i])>20) $reason0=substr($llog_reason[$i],0,20).'...'; else $reason0=$llog_reason[$i];
				print '<tr bgcolor="'.$color.'" ><td class="shipmentTB4"><a href="'.$url.'" title="Applied on: '.substr($llog_apply_date[$i],0,16).'" style="text-decoration:none;">'.$llog_type[$i].'</a></td><td class="shipmentTB4" align="center">'.$llog_from_date[$i].'</td><td class="shipmentTB4" align="center">'.$llog_to_date[$i].'</td><td class="shipmentTB4" align="center">'.$llog_days[$i].'</td><td class="shipmentTB4"><a title="'.$llog_reason[$i].'" style="color:black; text-decoration:none; cursor:pointer">'.$reason0.'</a></td><td class="shipmentTB4" align="center" style="color:'.$llog_st_color[$i].'">'.$llog_st_name[$i].'</td></tr>';
			}
			?>
		</table>
	</td></tr></table>
</td></tr>
</table>	

<?php
                include_once  'template/footer.php';
?>