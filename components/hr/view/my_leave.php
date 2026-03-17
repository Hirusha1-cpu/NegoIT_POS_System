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
		window.location = 'index.php?components=<?php print $components; ?>&action=set_leave_status&id='+$id+'&new_status='+$newstatus;
	}
}

function setLeaveYear($id){
	$filter_year=document.getElementById('filter_year').value;
	if($id=='') $leave_one='&id='+$id; else $leave_one='';
	window.location = 'index.php?components=<?php print $components; ?>&action=my_leave'+$leave_one+'&filter_year='+$filter_year;
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
	<div id="print">
	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
		<form method="post" action="index.php?components=hr&action=apply_leave" onsubmit="return calLeaveDays(1)">
		<table border="0" cellspacing="0" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr><td colspan="6" height="10px"></td></tr>
			<tr bgcolor="#FAFAFA"><td class="shipmentTB4"><strong>Leave Type</strong></td><td><input type="text" disabled="disabled" value="<?php print $llog1_type; ?>" /></td><td></td><td></td><td></td><td width="8px"></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#F5F5F5"><td class="shipmentTB4"><strong>Employee</strong></td><td><input type="text" disabled="disabled" value="<?php print $llog1_user; ?>" /></td><td></td><td></td><td></td><td width="8px"></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#FAFAFA"><td class="shipmentTB4"><strong>Applied by</strong></td><td><input type="text" disabled="disabled" value="<?php print $llog1_apply_by; ?>" /></td><td></td><td>Applied on</td><td><input type="text" disabled="disabled" value="<?php print $llog1_apply_date; ?>" /></td><td width="8px"></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#F5F5F5"><td class="shipmentTB4"><strong>Approved by</strong></td><td><input type="text" disabled="disabled" value="<?php print $llog1_approve_reject_by; ?>" /></td><td></td><td>Approved on</td><td><input type="text" disabled="disabled" value="<?php print $llog1_approve_reject_date; ?>" /></td><td width="8px"></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#FAFAFA"><td class="shipmentTB4"><strong>Start Date</strong></td><td><input type="date" id="from_date" name="from_date" value="<?php print $llog1_from_date; ?>" disabled="disabled" /></td><td width="10px"></td><td><strong>To Date</strong></td><td><input type="date" id="to_date" name="to_date"  value="<?php print $llog1_to_date; ?>"  disabled="disabled" /></td><td></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#F5F5F5"><td class="shipmentTB4"><strong>Leave Days</strong></td><td colspan="4"><input type="text" id="l_days0" disabled="disabled" value="<?php print $llog1_days; ?>" /></td><td></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#FAFAFA"><td class="shipmentTB4"><strong>Reason</strong></td><td colspan="4"><textarea id="reason" name="reason" rows="4" style="width:99%" disabled="disabled"><?php print $llog1_reason; ?></textarea></td><td></td></tr>
			<tr><td colspan="6" height="2px"></td></tr>
			<tr bgcolor="#F5F5F5"><td class="shipmentTB4"><strong>Status</strong></td><td colspan="4" style="color:<?php print $llog1_st_color; ?>"><strong><?php if($llog1_type!='') print $llog1_st_name; ?></strong></td><td></td></tr>
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
<tr><td colspan="3" height="10px" class="shipmentTB4" style="font-family:Calibri" bgcolor="silver">Filter by Year 
	<select id="filter_year" onchange="setLeaveYear(<?php print $id; ?>)">
			<?php 
			for($i=0;$i<sizeof($year_list);$i++){
				if($year_list[$i]==$selected_year) $select='selected="selected"'; else $select='';
				print '<option value="'.$year_list[$i].'" '.$select.'>'.$year_list[$i].'</option>';
			}
			?>
	</select>
</td></tr>
<tr><td colspan="3">
<!-- -----------------------------------LEAVE LOG------------------------------------------- -->
	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
		<table border="0" cellspacing="1" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4">Leave Type</th><th class="shipmentTB4">From Date</th><th class="shipmentTB4">To Date</th><th class="shipmentTB4">Number of Days</th><th class="shipmentTB4" width="150px">Reason</th><th class="shipmentTB4">Status</th></tr>
			<?php 
			for($i=0;$i<sizeof($llog_id);$i++){
				if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
				if(strlen($llog_reason[$i])>20) $reason0=substr($llog_reason[$i],0,20).'...'; else $reason0=$llog_reason[$i];
				print '<tr bgcolor="'.$color.'" ><td class="shipmentTB4"><a href="index.php?components=hr&action=my_leave&id='.$llog_id[$i].'" title="Applied on: '.substr($llog_apply_date[$i],0,16).'" style="text-decoration:none;">'.$llog_type[$i].'</a></td><td class="shipmentTB4" align="center">'.$llog_from_date[$i].'</td><td class="shipmentTB4" align="center">'.$llog_to_date[$i].'</td><td class="shipmentTB4" align="center">'.$llog_days[$i].'</td><td class="shipmentTB4"><a title="'.$llog_reason[$i].'" style="color:black; text-decoration:none; cursor:pointer">'.$reason0.'</a></td><td class="shipmentTB4" align="center" style="color:'.$llog_st_color[$i].'">'.$llog_st_name[$i].'</td></tr>';
			}
			?>
		</table>
	</td></tr></table>
</td></tr>
</table>	

<?php
                include_once  'template/footer.php';
?>