<?php
    include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
	<style type="text/css">
	.style2 {
		color: navy;
		font-weight: bold;
		background-color:#EEEEEE;
	}
	</style>

	<script>
		function setFilter(){
			var group = document.getElementById('group').value;
			var salesman= document.getElementById('salesman').value;
			window.location = 'index.php?components=billing&action=chque_ops&salesman='+salesman+'&group='+group;
		}
	</script>

</head>

<div class="w3-container" style="margin-top:75px">
	<?php 
		if(isset($_REQUEST['message'])){
			if($_REQUEST['re']=='success') $color='green'; else $color='red';
		print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
		}
	?>	

	<hr>

	<div class="w3-row">
	  	<div class="w3-col s3">
	  	</div>
		<div class="w3-col" style="overflow-x:auto;">
			<!-- List of return cheques -->
			<?php 
				if($components=='billing'){ 
			?>
				<div style="margin-top:10px;"></div>
				<div style="margin:0 auto; background-color:#EEEEEF; border-radius: 10px; height:30px; vertical-align:middle;  width:450px;" align="center">
					<table height="100%" align="center" style="color:#0158C2; font-family:Calibri; font-size:10pt; vertical-align:middle; width:450px;">
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
							<th class="shipmentTB3">Salesman By 
								<select id="salesman" onchange="setFilter(this)">
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
			<h4 align="center" style="color:#0158C2">List of Returned Chques for Assigned Groups</h4>

			<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"  style="font-size:xx-small; width:100%;">
				<tr style="font-weight:bold; background-color:#0066AA; color:white"><th>Chque No</th><th width="100px;" align="center">Chque Date</th><th width="100px;" align="center">Returned Date</th><th>Amount</th><th>Customer</th><th>Related Invoice</th><th>Salesman By</th></tr>
				<?php 
					$total_amount=0;
					for($i=0;$i<sizeof($chq0_id);$i++){
							$total_amount+=$chq0_amount[$i];
							print '<tr><td style="padding-left:10px; padding-right:10px"><a style="text-decoration:none; color:blue;" href="index.php?components=billing&action=finish_payment&id='.$chq0_id[$i].'">'.$chq0_no[$i].'</a></td><td style="padding-left:10px; padding-right:10px;" align="center">'.$chq0_date[$i].'</td><td style="padding-left:10px; padding-right:10px" align="center">'.$chq0_returndate[$i].'</td><td style="padding-left:10px; padding-right:10px" align="right">'.number_format($chq0_amount[$i]).'</td><td style="padding-left:10px; padding-right:10px" align="left">'.ucfirst($chq0_cuname[$i]).'</td><td align="center">'.$chq0_invno[$i].'</td>
							<td style="padding-left:10px; padding-right:10px">'.ucfirst($chq0_salesman[$i]).'</td>';
							print '</tr>';
					} 
					print '<tr><td colspan="3" align="right" style="padding-left:10px; padding-right:10px"><strong>Total</strong></td><td style="padding-left:10px; padding-right:10px" align="right">'.number_format($total_amount).'</td><td colspan="3"></td></tr>';
				?>
			</table>
			<!-- End of return cheques -->

			<!-- List of postponded cheques -->
			<div style="margin-top:40px;"></div>
			<h4 align="center" style="color:#0158C2">List of Postponed Cheques for Assigned Groups</h4>
			<div style="margin-top:10px;"></div>
			<table  align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"  style="font-size:xx-small; width:100%;">
				<tr style="font-weight:bold; background-color:#0066AA; color:white"><th>Cheque No</th><th width="100px;" align="center">Cheque Date</th><th width="100px;" align="center">Postponed Date</th><th>Amount</th><th>Customer</th><th>Related Invoice</th><th>Salesman By</th></tr>
				<?php 
					$total=0;
					for($i=0;$i<sizeof($chq0_postpond_id);$i++){
							print '<tr><td style="padding-left:10px; padding-right:10px"><a style="text-decoration:none; color:blue;" href="index.php?components=billing&action=finish_payment&id='.$chq0_postpond_id[$i].'">'.$chq0_postpond_no[$i].'</a></td><td style="padding-left:10px; padding-right:10px" align="center">'.$chq0_postpond_date[$i].'</td><td style="padding-left:10px; padding-right:10px" align="center">'.$chq0_postponed_date[$i].'</td><td style="padding-left:10px; padding-right:10px" align="right">'.number_format($chq0_postpond_amount[$i]).'</td><td style="padding-left:10px; padding-right:10px">'.ucfirst($chq0_postpond_cuname[$i]).'</td><td align="center">'.$chq0_postpond_invno[$i].'</td><td  style="padding-left:10px; padding-right:10px">'.ucfirst($chq0_postpond_salesman[$i]).'</td></tr>';
							$total+=$chq0_postpond_amount[$i];
					} 
					print '<tr><td colspan="3" align="right" style="padding-left:10px; padding-right:10px"><strong>Total</strong></td><td align="right" style="padding-left:10px; padding-right:10px">'.number_format($total).'</td><td colspan="5"></td></tr>';
				?>
			</table>
			<!-- End of List of postponded cheques -->
		</div>
	</div>
</div>
<hr>


<?php
    include_once  'template/m_footer.php';
?>