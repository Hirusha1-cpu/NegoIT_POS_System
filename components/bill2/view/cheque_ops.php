<?php
    include_once  'template/header.php';
?>
	<!-- ------------------Item List----------------------- -->
	<table align="center"><tr><td>
	<script>
		function setFilter(){
			var group = document.getElementById('group').value;
			var salesman= document.getElementById('salesman').value;
			window.location = 'index.php?components=bill2&action=chque_ops&salesman='+salesman+'&group='+group;
		}
	</script>
	<?php 
		if(isset($_REQUEST['message'])){
			if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
				print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
		}
	?>
	</td></tr>
	</table>

	<?php 
		if($components=='bill2'){ 
	?>
	<div style="margin-top:10px;"></div>
	<div style="margin:10px auto; background-color:#EEEEEF; border-radius: 10px; height:30px; vertical-align:middle;" align="center">
		<table height="100%" align="center" style="color:#0158C2; font-family:Calibri; font-size:10pt; vertical-align:middle">
			<tr style="font-weight:bold;">
				<th class="shipmentTB3">Group By 
					<select onchange="setFilter()" id="group">
						<option value="all" selected="selected">All</option>
						<?php 
							for($i=0;$i<sizeof($chq0_group_id);$i++){
								if($group == $chq0_group_id[$i]) $selected = 'selected="selected"'; else $selected = '';
								echo '<option value="'.$chq0_group_id[$i].'" '.$selected.'>'.$chq0_group_name[$i].'</option>';
							}
						?>
					</select>
				</th>
				<th class="shipmentTB3">Collected By 
					<select id="salesman" onchange="setFilter()">
						<option value="all" selected="selected">All</option>
						<?php 
							for($i=0;$i<sizeof($chq0_user_id);$i++){
								if($salesman==$chq0_user_id[$i]) $select='selected="selected"'; else $select='';
								print '<option value="'.$chq0_user_id[$i].'" '.$select.'>'.ucfirst($chq0_username[$i]).'</option>';
							}
						?>
					</select>
				</th>
			</tr>
		</table>
	</div>
	<?php } ?>
	
	<br />
	<table height="100%" align="center" width="700px" style="background-color:#EEEEEF; color:#0158C2; font-family:Calibri; font-size:16pt; vertical-align:middle; border-radius:10px"><tr><td align="center"><strong>List of Returned Chques for Assigned Groups</strong></td></tr></table>
	<br />

	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"  style="font-size:10pt; font-family:Calibri">
		<tr style="font-weight:bold; background-color:#0066AA; color:white"><th>Chque No</th><th>Bank</th><th width="100px;" align="center">Chque Date</th><th width="100px;" align="center">Returned Date</th><th>Amount</th><th>Customer</th><th>Related Invoice</th><th><?php if($components=='bill2') print 'Collected By'; ?></th></tr>
	<?php 
		$total_amount=0;
		for($i=0;$i<sizeof($chq0_id);$i++){
				$total_amount+=$chq0_amount[$i];
				print '<tr><td style="padding-left:10px; padding-right:10px"><a style="text-decoration:none" href="index.php?components=bill2&action=finish_payment&id='.$chq0_id[$i].'">'.$chq0_code[$i].'</a></td><td style="padding-left:10px; padding-right:10px" align="left">'.$chq0_bank[$i].'</td><td style="padding-left:10px; padding-right:10px;" align="center">'.$chq0_date[$i].'</td><td style="padding-left:10px; padding-right:10px" align="center">'.$chq0_returndate[$i].'</td><td style="padding-left:10px; padding-right:10px" align="right">'.number_format($chq0_amount[$i]).'</td><td style="padding-left:10px; padding-right:10px" align="left">'.ucfirst($chq0_cuname[$i]).'</td><td align="center">'.$chq0_invno[$i].'</td>';
				if($components=='bill2') print '<td style="padding-left:10px; padding-right:10px">'.ucfirst($chq0_salesman[$i]).'</td>';
		} 
		print '<tr><td colspan="4" align="right" style="padding-left:10px; padding-right:10px"><strong>Total</strong></td><td style="padding-left:10px; padding-right:10px" align="right">'.number_format($total_amount).'</td><td colspan="3"></td></tr>';
	?>
	</table>

	<div style="margin-top:30px;"></div>
	<br />
	<table height="100%" align="center" width="700px" style="background-color:#EEEEEF; color:#0158C2; font-family:Calibri; font-size:16pt; vertical-align:middle; border-radius:10px"><tr><td align="center"><strong>List of Postponed Chques for Assigned Groups</strong></td></tr></table>
	<br />
	<div style="margin-bottom:10px;"></div>

	<table  align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"  style="font-size:10pt; font-family:Calibri">
	<tr style="font-weight:bold; background-color:#0066AA; color:white"><th>Cheque No</th><th>Bank</th><th width="100px;" align="center">Cheque Date</th><th width="100px;" align="center">Postponed Date</th><th>Amount</th><th>Customer</th><th>Related Invoice</th><th>Collected By</th></tr>
	<?php 
		$total=0;
		for($i=0;$i<sizeof($chq0_postpond_id);$i++){
				print '<tr><td style="padding-left:10px; padding-right:10px"><a style="text-decoration:none" href="index.php?components=bill2&action=finish_payment&id='.$chq0_postpond_id[$i].'">'.$chq0_postpond_code[$i].'</a></td><td style="padding-left:10px; padding-right:10px" align="left">'.$chq0_postpond_bank[$i].'</td><td style="padding-left:10px; padding-right:10px" align="center">'.$chq0_postpond_date[$i].'</td><td style="padding-left:10px; padding-right:10px" align="center">'.$chq0_postponed_date[$i].'</td><td style="padding-left:10px; padding-right:10px" align="right">'.number_format($chq0_postpond_amount[$i]).'</td><td style="padding-left:10px; padding-right:10px">'.ucfirst($chq0_postpond_cuname[$i]).'</td><td align="center">'.$chq0_postpond_invno[$i].'</td><td  style="padding-left:10px; padding-right:10px">'.ucfirst($chq0_postpond_salesman[$i]).'</td></tr>';
				$total+=$chq0_postpond_amount[$i];
		} 
		print '<tr><td colspan="4" align="right" style="padding-left:10px; padding-right:10px"><strong>Total</strong></td><td align="right" style="padding-left:10px; padding-right:10px">'.number_format($total).'</td><td colspan="5"></td></tr>';
	?>
	</table>

<?php
    include_once  'template/footer.php';
?>