<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">
function setLeaveStatus($id,$status){
	var check= confirm('Do you want to '+$status+' this Leave ?');
	if($status=='Approve') $newstatus=2;
	if($status=='Reject') $newstatus=3;
	if($status=='Cancel') $newstatus=0;
 	if(check== true){
 		document.getElementById('button_div_'+$status).innerHTML=document.getElementById('loading').innerHTML; 
		window.location = 'index.php?components=<?php print $components; ?>&action=set_leave_status2&id='+$id+'&new_status='+$newstatus;
	}
}

function deleteLeave($id){
	$master_pw5=document.getElementById('master_pw5').value;
	var check= confirm('Do you want to Delete this Leave ?');
 	if(check== true){
 		document.getElementById('button_div_Delete').innerHTML=document.getElementById('loading').innerHTML; 
		window.location = 'index.php?components=<?php print $components; ?>&action=delete_leave&id='+$id+'&master_pw5='+$master_pw5;
	}
}

function setFilter($id){
	$from_date=document.getElementById('from_date').value;
	$to_date=document.getElementById('to_date').value;
	$filter_type=document.getElementById('filter_type').value;
	$filter_st=document.getElementById('filter_st').value;
	$filter_emp=document.getElementById('filter_emp').value;
	if($id==''){ $leave_one='&id='+$id; }else{	$leave_one='';	}
	window.location = 'index.php?components=<?php print $components; ?>&action=leave_list'+$leave_one+'&from_date='+$from_date+'&to_date='+$to_date+'&filter_type='+$filter_type+'&filter_emp='+$filter_emp+'&filter_st='+$filter_st;
}
</script>
<!-- -------------------------------------------------------------------------------------------------------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:70px" /><br><span style="color:maroon; vertical-align:middle">Please Wait</span></div>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?></td></tr></table>
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Employee Leave Log</h2>
	<hr />
	<p align="center" style="color:#3333FF; font-family:Calibri"><strong>Employee ID - <?php print $llog1_user_id; ?></strong></p>
</div>



<table align="center"><tr><td valign="top">
	<?php if($id!=''){ ?>
	<div id="print">
	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri">
	<tr><td>
		<form method="post" action="index.php?components=hr&action=apply_leave" onsubmit="return calLeaveDays(1)">
		<table border="0" cellspacing="0" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr><td colspan="6" height="10px"></td></tr>
			<tr bgcolor="#FAFAFA"><td class="shipmentTB3"><strong>Leave Type</strong></td><td><input type="text" disabled="disabled" value="<?php print $llog1_type; ?>" /></td><td></td><td></td><td></td><td width="8px"></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#F5F5F5"><td class="shipmentTB3"><strong>Employee</strong></td><td><input type="text" disabled="disabled" value="<?php print $llog1_user; ?>" /></td><td></td><td></td><td></td><td width="8px"></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#FAFAFA"><td class="shipmentTB3"><strong>Applied by</strong></td><td><input type="text" disabled="disabled" value="<?php print $llog1_apply_by; ?>" /></td><td></td><td>Applied on</td><td><input type="text" disabled="disabled" value="<?php print $llog1_apply_date; ?>" /></td><td width="8px"></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#F5F5F5"><td class="shipmentTB3"><strong>Approved/ <br />Rejected by</strong></td><td><input type="text" disabled="disabled" value="<?php print $llog1_approve_reject_by; ?>" /></td><td></td><td>Approved<br />Rejected on</td><td><input type="text" disabled="disabled" value="<?php print $llog1_approve_reject_date; ?>" /></td><td width="8px"></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#FAFAFA"><td class="shipmentTB3"><strong>Start Date</strong></td><td><input type="date" id="from_date" name="from_date" value="<?php print $llog1_from_date; ?>" disabled="disabled" /></td><td width="10px"></td><td><strong>To Date</strong></td><td><input type="date" id="to_date" name="to_date"  value="<?php print $llog1_to_date; ?>"  disabled="disabled" /></td><td></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#FAFAFA"><td class="shipmentTB3"><strong>Leave Days</strong></td><td colspan="4"><input type="text" id="l_days0" disabled="disabled" value="<?php print $llog1_days; ?>" /></td><td></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#F5F5F5"><td class="shipmentTB3"><strong>Reason</strong></td><td colspan="4"><textarea id="reason" name="reason" rows="4" style="width:99%" disabled="disabled"><?php print $llog1_reason; ?></textarea></td><td></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#FAFAFA"><td class="shipmentTB3"><strong>Status</strong></td><td colspan="4" style="color:<?php print $llog1_st_color; ?>"><strong><?php print $llog1_st_name; ?></strong> <?php if(($llog1_st_name=='Deleted')&&($llog1_deleted_by=='')) print '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:9pt;">(Self Delete)</span>'; ?></td><td></td></tr>
			<tr><td colspan="6" height="10px"></td></tr>
			<?php if(($llog1_st_name=='Deleted')&&($llog1_deleted_by!='')){ ?>
			<tr bgcolor="#FAFAFA"><td class="shipmentTB3"><strong>Deleted by</strong></td><td><input type="text" disabled="disabled" value="<?php print $llog1_deleted_by; ?>" /></td><td></td><td>Deleted on</td><td><input type="text" disabled="disabled" value="<?php print $llog1_deleted_date; ?>" /></td><td width="8px"></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<?php } ?>
			<tr bgcolor="#F5F5F5"><td class="shipmentTB3"><strong>Shop Staff</strong></td><td class="shipmentTB3" colspan="4">
				<table width="100%">
				<tr bgcolor="#DDDDDD"><th>Total Staff</th><th>Currunt Staff</th><th>On-Leave Staff</th></tr>
				<tr bgcolor="#EEEEEE"><th><?php print $total_staff; ?></th><th><?php print $total_staff-$onleave_staff; ?></th><th><?php print $onleave_staff; ?></th></tr>
				</table>
			</td><td></td></tr>
			<tr><td colspan="6" height="10px"></td></tr>
		</table>
		</form>
	</td></tr></table>
	</div>
</td><td width="20px"></td><td valign="top">
	<table align="center">
	<tr><td align="center">
					<div class="prtbutton1">
					<a class="shortcut-button" style="text-decoration:none; font-family:Arial; color:white;" onclick="printdiv('print','printheader')" href="#">
						<img src="images/print.png" alt="icon" /><br />
						Print
					</a>
					</div>
		<?php if(($approver1) && (($llog1_st!=0)&&($llog1_st!=3))){ ?>
					<br />
					<div id="button_div_Delete" class="prtbutton2">
					<a class="shortcut-button" style="text-decoration:none; font-family:Arial; color:navy;" onclick="deleteLeave('<?php print $id; ?>')" href="#">
						<img src="images/cancel.png" alt="icon" /><br />
						Delete
					</a>
					</div>
					<input type="text" id="master_pw5" placeholder="Master PW5" style="width:75px;;" />
		<?php } ?>
	</td></tr>
	<tr><td align="center">
		<?php if($llog1_st==1){ ?>
					<div id="button_div_Cancel" class="prtbutton2">
					<a class="shortcut-button" style="text-decoration:none; font-family:Arial; color:navy;" onclick="setLeaveStatus('<?php print $id; ?>','Cancel')" href="#">
						<img src="images/cancel.png" alt="icon" /><br />
						Cancel
					</a>
					</div>
		<?php } ?>
	</td></tr>
	<tr><td align="center">
		<?php if(($llog1_st==1)&&($approver)){ ?>
					<div id="button_div_Approve" class="prtbutton1">
					<a class="shortcut-button"  style="text-decoration:none;" onclick="setLeaveStatus('<?php print $id; ?>','Approve')" href="#">
					<span style="text-decoration:none; font-family:Arial; color:navy;">
						<img src="images/approve.png" alt="icon" /><br />
						Approve
					</span>
					</a>
					</div>
		<?php } ?>
	</td></tr>
	<tr><td align="center">
		<?php if(($llog1_st==1)&&($approver)){ ?>
					<div id="button_div_Reject" class="prtbutton2">
					<a class="shortcut-button"  style="text-decoration:none;" onclick="setLeaveStatus('<?php print $id; ?>','Reject')" href="#">
					<span style="text-decoration:none; font-family:Arial; color:navy;">
						<img src="images/reject.png" alt="icon" /><br />
						Reject
					</span>
					</a>
					</div>
		<?php } ?>
	</td></tr>
	</table>
</td></tr>
<tr><td colspan="3" height="10px"></td></tr>
<?php } ?>
<tr><td colspan="3" height="10px" class="shipmentTB3" style="font-family:Calibri" bgcolor="silver">
	<table width="100%"><tr><td valign="middle">From Data <input type="date" id="from_date" name="from_date" value="<?php print $from_date; ?>" /> 
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To Data <input type="date" id="to_date" name="to_date" value="<?php print $to_date; ?>" /> 
	&nbsp;<a style="cursor:pointer" onclick="setFilter(<?php print $id; ?>)"><img src="images/search.png" style="width:25px; vertical-align:middle" /></a></td><td></td></tr></table>
</td></tr>
<tr><td colspan="3">
<!-- -----------------------------------LEAVE LOG------------------------------------------- -->
	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
		<table border="0" cellspacing="1" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB3">Leave Type</th><th class="shipmentTB3">Employee</th><th class="shipmentTB3">Applied</th><th class="shipmentTB3">Approved<br />Rejected</th><th class="shipmentTB3">Applied Date</th><th class="shipmentTB3">From Date</th><th class="shipmentTB3">To Date</th><th class="shipmentTB3">Duration</th><th class="shipmentTB3" width="150px">Reason</th><th class="shipmentTB3">Status</th></tr>
			<tr bgcolor="#467898" style="color:#F5F5F5"><th>
				<select id="filter_type" onchange="setFilter('<?php print $id; ?>')">
					<option value="">-ALL-</option>
						<?php 
						for($i=0;$i<sizeof($leave_id);$i++){
							if($leave_id[$i]==$filter_type) $select='selected="selected"'; else $select='';
							print '<option value="'.$leave_id[$i].'" '.$select.'>'.$leave_name[$i].'</option>';
						}
						?>
				</select>
			</th><th>
				<select id="filter_emp" onchange="setFilter('<?php print $id; ?>')">
					<option value="">-ALL-</option>
						<?php 
						for($i=0;$i<sizeof($user_id);$i++){
							if($user_id[$i]==$filter_emp) $select='selected="selected"'; else $select='';
							print '<option value="'.$user_id[$i].'" '.$select.'>'.ucfirst($user_name[$i]).'</option>';
						}
						?>
				</select>
			</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th>
				<select id="filter_st" onchange="setFilter('<?php print $id; ?>')">
					<option value="all" <?php if($selected_st=='all') print 'selected="selected"'; ?> >All</option>
					<option value="1" <?php if($selected_st=='1') print 'selected="selected"'; ?> >Applied</option>
					<option value="2" <?php if($selected_st=='2') print 'selected="selected"'; ?> >Approved</option>
					<option value="3" <?php if($selected_st=='3') print 'selected="selected"'; ?> >Rejected</option>
					<option value="0" <?php if($selected_st=='0') print 'selected="selected"'; ?> >Cancelled</option>
				</select>
			</th></tr>
			<?php 
			for($i=0;$i<sizeof($llog_id);$i++){
				if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
				if(strlen($llog_reason[$i])>20) $reason0=substr($llog_reason[$i],0,20).'...'; else $reason0=$llog_reason[$i];
				print '<tr bgcolor="'.$color.'" ><td class="shipmentTB3"><a href="index.php?components=hr&action=leave_list&id='.$llog_id[$i].'" title="Applied on: '.substr($llog_apply_date[$i],0,16).'" style="text-decoration:none;">'.$llog_type[$i].'</a></td><td class="shipmentTB3">'.$llog_user[$i].'</td><td class="shipmentTB3"><a title="Applied on: '.$llog_apply_date[$i].'" style="color:black; text-decoration:none; cursor:pointer">'.$llog_apply_by[$i].'</a></td><td class="shipmentTB3"><a title="Appled/Reject on: '.$llog_approve_reject_date[$i].'" style="color:black; text-decoration:none; cursor:pointer">'.$llog_approve_reject_by[$i].'</a></td><td class="shipmentTB3" align="center">'.$llog_apply_date[$i].'</td><td class="shipmentTB3" align="center">'.$llog_from_date[$i].'</td><td class="shipmentTB3" align="center">'.$llog_to_date[$i].'</td><td class="shipmentTB3" align="center">'.$llog_days[$i].'</td><td class="shipmentTB3"><a title="'.$llog_reason[$i].'" style="color:black; text-decoration:none; cursor:pointer">'.$reason0.'</a></td><td class="shipmentTB3" align="center" style="color:'.$llog_st_color[$i].'">'.$llog_st_name[$i].'</td></tr>';
			}
			?>
		</table>
	</td></tr></table>
</td></tr>
</table>	

<?php
                include_once  'template/footer.php';
?>