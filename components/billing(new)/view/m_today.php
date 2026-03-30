<?php
    include_once  'template/m_header.php';
    $decimal=0;
    if($systemid==13) $decimal=2; 
    if($systemid==14) $decimal=2; 
    $user_id=$_COOKIE['user_id'];
?>

<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
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
	  	<div class="w3-col s3"></div>
	  	<div class="w3-col">
			<table align="center" bgcolor="#E5E5E5" border="1" cellspacing="0" style="font-size:x-small">
				<tr>
					<td colspan="4" style="background-color:silver; color:navy; font-family:Calibri; font-size:8pt" align="right">
						<table width="100%">
							<tr>
								<td bgcolor="red" width="15px" height="15px"></td><td>Discounted Inv</td>
								<td></td>
								<td bgcolor="#009900" width="15px" height="15px"></td>
								<td>Cash</td><td width="20px"></td>
								<td bgcolor="#CC3399" width="15px" height="15px"></td>
								<td>Card</td><td width="20px"></td>
								<td bgcolor="#00AAAA" width="15px" height="15px"></td>
								<td>Bank</td><td width="20px"></td>
								<td bgcolor="blue" width="15px" height="15px"></td>
								<td>Cheque</td><td width="20px"></td>
								<td bgcolor="black" width="15px" height="15px"></td>
								<td>Credit</td><td width="20px"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="4" style="background-color:silver; color:navy; font-family:Calibri; font-size:12pt">&nbsp;&nbsp;&nbsp;<strong>Today Invoices [Completed]</strong></td></tr>
				<tr><th width="85px">Invoice No</th><th width="80px">Time</th><th width="80px">Amount</th><th width="240px">Customer</th></tr>
				<?php
					$inv=0;
					for($i=0;$i<sizeof($invoice_no);$i++){
						if($bi_discount[$i]>0){ $color1='style="color:red"'; $title='title="Discounted Invoice"'; }else{ $color1=''; $title=''; }
						$key=array_search($invoice_no[$i],$py_invno);
						$color2='black';
						if($bm_sys_user[$i]==$user_id) $color='#EEEEEE'; else $color='#EEDDDD';
						if($key>-1){ 
							if($py_type[$key]==1) $color2='#009900'; 
							if($py_type[$key]==2) $color2='blue';
							if($py_type[$key]==3) $color2='#00AAAA';
							if($py_type[$key]==4) $color2='#CC3399';
						}
							print '<tr bgcolor="'.$color.'" '.$color1.'><td align="center">&nbsp;&nbsp;<a '.$title.' href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'">'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="50px"  align="center">'.$time[$i].'</td><td align="right" style="color:'.$color2.'"><strong>'.number_format($invoice_total[$i],$decimal).'&nbsp;&nbsp;</strong></td><td>&nbsp;&nbsp;'.$cust[$i].'</td></tr>';
					}
				?>	
			</table>

			<br>

			<table align="center" bgcolor="#E5E5E5" border="1" cellspacing="0" style="font-size:x-small">
				<tr><td colspan="4" style="background-color:silver; color:navy; font-family:Calibri; font-size:12pt">&nbsp;&nbsp;&nbsp;<strong>Pending Invoices</strong></td></tr>
				<tr><th>Invoice No</th><th width="90px">Date</th><th width="90px">Amount</th><th width="200px">Customer</th></tr>
				<?php
					$inv=0;
					for($i=0;$i<sizeof($linvoice_no);$i++){
							if($lbm_type[$i]==4 || $lbm_type[$i]==5) $cust_odr='yes'; else $cust_odr='no';
							if($lbm_sys_user[$i]==$user_id){
								$color='#EEEEEE';
								$edit='<input type="Button" value="Edit"  onclick="window.location = '."'".'index.php?components=billing&action=home&cust_odr='.$cust_odr.'&id='.$linvoice_no[$i].'&s='.$lbm_salesman[$i].'&cust='.$lcustid[$i]."'".'" />';
								}else{ $edit=''; $color='#EEDDDD'; }
							print '<tr bgcolor="'.$color.'"><td>&nbsp;&nbsp;<a href="index.php?components=billing&action=finish_bill&id='.$linvoice_no[$i].'">'.str_pad($linvoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>&nbsp;&nbsp;&nbsp;&nbsp;'.$edit.'</td><td width="50px"  align="center">'.$ldate[$i].'</td><td width="50px" align="right">'.number_format($linvoice_total[$i],$decimal).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$lcust[$i].'</td></tr>';
					}
				?>	
			</table>

			<br>

			<table align="center" bgcolor="#E5E5E5" border="1" cellspacing="0" style="font-size:x-small">
				<tr><td colspan="4" style="background-color:silver; color:navy; font-family:Calibri; font-size:12pt">&nbsp;&nbsp;&nbsp;<strong>Payment Invoices</strong></td></tr>
				<tr><th>Invoice No</th><th width="90px">Time</th><th width="90px">Amount</th><th width="200px">Customer</th></tr>
				<?php
				$inv=0;
					for($i=0;$i<sizeof($py_id);$i++){
						if($py_bill_pay[$i]==2){
							if($py_sys_user[$i]==$user_id) $color='#EEEEEE'; else $color='#EEDDDD';
							if($py_type[$i]==1) $color2='#009900'; 
							if($py_type[$i]==2) $color2='blue';
							if($py_type[$i]==3) $color2='#00AAAA';
							if($py_type[$i]==4) $color2='#CC3399';
							print '<tr bgcolor="'.$color.'"><td align="center"><a href="index.php?components=billing&action=finish_payment&id='.$py_id[$i].'">'.str_pad($py_id[$i], 7, "0", STR_PAD_LEFT).'</a></td><td width="50px" align="center">'.$py_time[$i].'</td><td align="right" style="color:'.$color2.'"><strong>'.number_format($py_amount[$i],$decimal).'&nbsp;&nbsp;</strong></td><td>&nbsp;&nbsp;'.$py_cust[$i].'</td></tr>';
						}
					}
				?>	
			</table>

			<br>

			<table align="center" bgcolor="#E5E5E5" border="1" cellspacing="0" style="font-family:Calibri;font-size :x-small">
				<tr><td colspan="4" style="background-color:silver; color:navy; font-family:Calibri; font-size:12pt">&nbsp;&nbsp;&nbsp;<strong>Return Invoices</strong></td></tr>
				<tr><th>Invoice No</th><th width="90px">Time</th><th width="290px">Customer</th></tr>
				<?php
				$inv=0;
					for($i=0;$i<sizeof($rt_invno);$i++){
						if($rt_status[$i]==1) $edit='<input type="Button" value="Edit"  onclick="window.location = '."'".'index.php?components=billing&action=item_return&id='.$rt_invno[$i].'&cust=15&cust='.$rt_cust_id[$i]."'".'" />'; else $edit='';
						if($rt_return_by[$i]==$user_id) $color='#EEEEEE'; else $color='#EEDDDD';
							print '<tr bgcolor="'.$color.'"><td align="center"><a href="index.php?components=billing&action=finish_return&id='.$rt_invno[$i].'">'.str_pad($rt_invno[$i], 7, "0", STR_PAD_LEFT).'</a>&nbsp;&nbsp;&nbsp;&nbsp;'.$edit.'</td><td width="50px" align="center">'.$rt_time[$i].'</td><td>&nbsp;&nbsp;'.$rt_cust_name[$i].'</td></tr>';
					}
				?>	
			</table>
		</div>
	</div>
</div>

<hr>

<?php
    include_once  'template/m_footer.php';
?>