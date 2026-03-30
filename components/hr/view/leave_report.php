<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">
	function filterLeave($case){
		$from_date=document.getElementById('from_date').value;
		$to_date=document.getElementById('to_date').value;
		$type=document.getElementById('type').value;
		$leave_user=document.getElementById('leave_user').value;
		$type0=$leave_user0='all';
		if($case=='type') $type0=$type;
		if($case=='user') $leave_user0=$leave_user;
		window.location = 'index.php?components=hr&action=leave_report&from_date='+$from_date+'&to_date='+$to_date+'&type='+$type0+'&leave_user='+$leave_user0;
	}
	
	function printdivhr($x,$y){
		document.getElementById('table1').border="1"
		printdiv($x,$y);
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



<table align="center"><tr><td valign="top">
<tr><td height="10px" class="shipmentTB4" style="font-family:Calibri; border-radius:5px" bgcolor="silver">
	<form id="report_form" method="get" action="index.php" >
		<input type="hidden" name="components" value="hr" />
		<input type="hidden" name="action" value="leave_report" />
		<table width="100%"><tr><td>From Date <input type="date" id="from_date" name="from_date" value="<?php print $from_date; ?>" /></td>
		<td>To Date <input type="date" id="to_date" name="to_date" value="<?php print $to_date; ?>" /></td><td width="50px"></td>
		<td>Leave Type
			<select id="type" name="type" onchange="filterLeave('type')">
				<option value="all">-SELECT-</option>
				<?php 
				$header_le_type='';
				for($i=0;$i<sizeof($leave_id);$i++){
					if($leave_id[$i]==$type0){ $select='selected="selected"'; $header_le_type=$leave_name[$i]; }else $select='';
					print '<option value="'.$leave_id[$i].'" '.$select.'>'.$leave_name[$i].'</option>';
				}	?>
			</select>
		</td><td width="160px">&nbsp;User
			<select id="leave_user" name="leave_user" onchange="filterLeave('user')">
				<option value="all" >-SELECT-</option>
				<?php 
				$header_le_user='';
				for($i=0;$i<sizeof($user_id);$i++){
						if($user_id[$i]==$leave_user){ $select='selected="selected"'; $header_le_user=ucfirst($user_name[$i]); }else $select='';
						print '<option value="'.$user_id[$i].'" '.$select.'>'.ucfirst($user_name[$i]).'</option>';
					}	?>
			</select>
		</td></tr>
		</table>
	</form>
</td></tr>
<tr><td>
<!-- -----------------------------------LEAVE LOG------------------------------------------- -->
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Employee Leave Report</h2>
	<hr />
	<table border="1" align="center" width="600px">
	<tr><td>&nbsp;&nbsp;From Date&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To Date&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<?php print $to_date; ?>&nbsp;&nbsp;</td></tr>
	<tr><td>&nbsp;&nbsp;Employee&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<?php print $header_le_user; ?>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Leave Type&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<?php print $header_le_type; ?>&nbsp;&nbsp;</td></tr>
	</table>
	<hr />
</div>


	<div id="print">
	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
		<table border="0" id="table1" cellspacing="1" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4">Leave Type</th><th class="shipmentTB4">Employee</th><th class="shipmentTB4">Leave Count</th></tr>
			<?php 
			for($i=0;$i<sizeof($llog_type);$i++){
				if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
				if($llog_type[$i]=='Half Day') $leave_count=$llog_days[$i]*2; else $leave_count=$llog_days[$i]; 
				print '<tr bgcolor="'.$color.'" ><td class="shipmentTB4">'.$llog_type[$i].'</td><td class="shipmentTB4">'.$llog_user[$i].'</td><td class="shipmentTB4" align="center">'.$leave_count.'</td></tr>';
			}
			?>
		</table>
	</td></tr></table>
	</div>
</td></tr>
<tr><td align="center">
	<br />
	<div class="prtbutton1">
	<a class="shortcut-button" style="text-decoration:none; font-family:Arial; color:white;" onclick="printdivhr('print','printheader')" href="#">
		<img src="images/print.png" alt="icon" /><br />
		Print
	</a>
	</div>

</td></tr>
</table>	

<?php
                include_once  'template/footer.php';
?>