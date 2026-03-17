<?php
	include_once  'template/header.php';
	$paper_size=paper_size(1);
	if($paper_size=='A4'){
		$page_width=680;
		$page_height=1040;
	}
	if($paper_size=='A5'){
		//$page_width=480;
		$page_width=523;
		$page_height=765;
	}
	if($_GET['action']=='finish_dn') $dn='yes'; else $dn='no';
	$user_id=$_COOKIE['user_id'];
?>


<?php
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

	<script type="text/javascript">
	window.onload = function() {
	  document.getElementById("keytxt").focus();
	};

<?php if($bm_type==3 && ($bm_status==4 || $bm_status==6)){ ?>
	$(function() {
		var availableTags4 = [<?php for ($x=0;$x<sizeof($bank_code);$x++){ print '"'.$bank_code[$x].'",'; } ?>	];
		$( "#tags4" ).autocomplete({
			source: availableTags4
		});
	});

	function validateBank(){
		var bank_code = [<?php for ($x=0;$x<sizeof($bank_code);$x++){ print '"'.$bank_code[$x].'",'; } ?>	];
		var bank_name = [<?php for ($x=0;$x<sizeof($bank_name);$x++){ print '"'.$bank_name[$x].'",'; } ?>	];
		var id=document.getElementById('tags4').value;
		var bank_code = bank_code.indexOf(id);
		if(bank_code==-1){
			document.getElementById('bk_name').innerHTML=bank_name[bank_code]='<span style="color:red">Invalid Bank Code</span>';
		}else{
			document.getElementById('bk_name').innerHTML='<span style="color:green">'+bank_name[bank_code]+'</span>';
		}
	}
<?php	} ?>

	function showHideDetails(){
		var details_key=document.getElementById('details_key').value;
		if(details_key=='hide'){
		document.getElementById('details_key').value='show';
		document.getElementById('details_link').innerHTML='<a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>- Hide Details</strong></a>';
		document.getElementById('details_div').style.display='block';
		}else if(details_key=='show'){
		document.getElementById('details_key').value='hide';
		document.getElementById('details_link').innerHTML='<a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>+ Show Details</strong></a>';
		document.getElementById('details_div').style.display='none';
		}
	}

	function changeJobTotal(){
		var invoice_no=document.getElementById('invoice_no').value;
		var code=document.getElementById('code').value;
		var job_total=document.getElementById('job_total').value;
		if(code!='' && job_total!=''){
			window.location = 'index.php?components=billing&action=change_job_total&id='+invoice_no+'&code='+code+'&new_total='+job_total;
		}else{
			window.alert('Please Fill Code and Job Total');
		}
	}

	function jobPayment(){
		var job_total0=document.getElementById('job_total0').value;
		var amount0=document.getElementById('amount_chque').value;
		if(job_total0==amount0){
			return validatePayment(1);
		}else{
			window.alert('Invalid Amount');
			return false;
		}
	}

	function smsResend($sms_id){
	  document.getElementById('div_smsresend').innerHTML=document.getElementById('loading').innerHTML;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
		    var returntext=this.responseText;
		    if(returntext=='done') document.getElementById('div_smsresend').innerHTML='<span style="color:green">Sent</span>';
		    else document.getElementById('div_smsresend').innerHTML='<span style="color:red">Error</span>';
	    }
	  };
	  xhttp.open("GET", 'index.php?components=billing&action=sms_resend&smsid='+$sms_id, true);
	  xhttp.send();
	}
</script>
<?php if(isset($_REQUEST['message'])){
			if($_REQUEST['re']=='success') $color='green'; else $color='red';
		print '<table align="center"><tr><td><span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span></td></tr></table>';
		}
?>
<input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
<input type="hidden" id="fastprint" value="<?php print $_COOKIE['fastprint']; ?>" />
<input type="hidden" id="job_total0" value="<?php print $main_total; ?>" />
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<?php if(($main_sub_system_id==$sub_system)||($main_refinvid==$_COOKIE['store'])){ ?>
<table align="center" style="font-family:Calibri; font-size:10pt"><tr><td valign="top">
	<?php if($bm_type==3 && $bm_status==4){ ?>
		<table width="100%" bgcolor="#EEEEEE">
		<tr><td width="50px"></td><td style="font-size:12pt"><strong>Change Job Total</strong></td><td><input type="text" id="code" style="width:40px;" placeholder="Code" /> <input type="text" id="job_total" style="width:60px; text-align:right; padding-right:10px" value="<?php print $main_total; ?>" /> <input type="button" value="Update" onclick="changeJobTotal()" /></td><td></td><td></td></tr>
		</table>
		<br />
		<form action="index.php?components=billing&action=add_payment" onsubmit="return jobPayment()" method="post" >
		<input type="hidden" id="payment_validity" value="1" />
		<input type="hidden" name="cust" id="cust" value="<?php print $bm_cust; ?>" />
		<input type="hidden" id="invoice_no" name="invoice_no" value="<?php print $_GET['id'] ?>" />
		<table align="center" bgcolor="#EEEEEE" style="font-family:Calibri; font-size:10pt">
		<tr><td colspan="5"><br /></td></tr>
		<tr><td colspan="5"><br /></td></tr>
		<tr><td width="50px"></td><td style="font-size:12pt">Payment Type<br /><br /></td><td colspan="2" valign="middle">
			<input type="radio" name="payment_type" id="payment_type" value="1" > Cash &nbsp;&nbsp;
		 	<input type="radio" name="payment_type" id="payment_type" value="2" > Chque &nbsp;&nbsp;
		<br /><br /></td><td width="50px"></td></tr>
		<tr><td></td><td style="font-size:12pt">Amount</td><td><input type="number" name="amount" id="amount_chque" /></td><td></td><td></td></tr>
		<tr><td></td><td style="font-size:12pt"></td><td>Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bank &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Branch</td><td></td><td></td><td></td></tr>
		<tr><td></td><td style="font-size:12pt">Chque </td><td><input type="number" name="chque_no" id="chque_no" style="width:60px" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="chque_bank" id="tags4" style="width:35px" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="chque_branch" id="chque_branch" style="width:35px" onfocus="validateBank()" /></td><td></td><td></td></tr>
		<tr><td></td><td style="font-size:12pt" height="20px"></td><td><div style="font-size:12pt" id="bk_name" align="right"></div></td><td><div style="font-size:12pt" id="av_qty" align="right"></div></td><td></td></tr>
		<tr><td></td><td style="font-size:12pt">Chque Date</td><td><input type="date" name="chque_date" id="chque_date" /></td><td><div style="font-size:12pt" id="av_qty" align="right"></div></td><td></td></tr>
		<tr><td></td><td style="font-size:12pt" height="20px"></td><td><div style="font-size:12pt" id="bk_name" align="right"></div></td><td><div style="font-size:12pt" id="av_qty" align="right"></div></td><td></td></tr>
		<tr><td></td><td style="font-size:12pt">Salesman</td><td>
			<select id="salesman" name="salesman">
			<?php for($i=0;$i<sizeof($sm_id);$i++){
				if($main_billed_by_id==$sm_id[$i]) $select='selected="selected"'; else $select='';
				print '<option value="'.$sm_id[$i].'" '.$select.'>'.ucfirst($sm_name[$i]).'</option>';
			}?>
			</select>
		</td><td><div style="font-size:12pt" id="av_qty" align="right"></div></td><td></td></tr>
		<tr><td></td><td></td><td colspan="3" height="10px"><div id="addpayment"><input type="submit" value="Add Payment" style="width:100px; height:40px" /></div></td></tr>
		<tr><td colspan="5" height="10px"></td></tr>
		</table>
		</form>
	<?php }else if($bm_type==3 && ($bm_status==3 || $bm_status==6)){ ?>
		<form action="index.php?components=billing&action=set_delivered" method="post" >
		<input type="hidden" name="id" value="<?php print $_GET['id']; ?>" />
		<table align="center" bgcolor="#EEEEEE" style="font-family:Calibri; font-size:10pt">
		<tr><td><input type="submit" value="Mark as Delivered" style="width:150px; height:60px; background-color:maroon; color:white; font-weight:bold" /></td></tr>
		</table>
		</form>
	<?php }
	 if($bm_type==3){ ?>
		<form method="post" action="index.php?components=billing&action=add_repair_comment&id=<?php print $_GET['id']; ?>" >
		<input type="hidden" id="repcom_type" name="repcom_type" value="3" />
		<table width="300px" style="font-size:10pt" >
		<tr style="background-color:#DDDDDD; color:maroon"><td colspan="3" align="center" ><strong>Technician Comment</strong></td></tr>
		<?php
			for($i=0;$i<sizeof($rc_id);$i++){
				if($rc_private_public[$i]=='Public') $color='green'; else $color='black';
				if($rc_user_id[$i]==$user_id) $delete_button='<a style="color:red; cursor:pointer" onclick="deleteRepairComment('.$rc_id[$i].')">x</a>'; else $delete_button='';
				print '<tr style="background-color:#DDDDDD"><td width="100px" class="shipmentTB4" colspan="2" style="color:'.$color.'"><a style="color:gray; cursor:pointer" title="Time: '.$rc_time[$i].'&#013;Audience: '.$rc_private_public[$i].'&#013;Type: '.$rc_type[$i].'">'.$rc_user[$i].':</a> '.$rc_comment[$i].'</td><td align="center" width="15px"><div id="repcom'.$rc_id[$i].'">'.$delete_button.'</div></td></tr>';
			}
		?>
		<tr style="background-color:#DDDDDD"><td class="shipmentTB4" align="center"><textarea rows="2" name="comment" style="width:100%"></textarea></td><td width="20px	" align="center" style="font-size:x-small" colspan="2"><input type="checkbox" name="publiccomment" value="yes" /><br />Public</td></tr>
		<tr style="background-color:#DDDDDD"><td colspan="3" class="shipmentTB4" align="center"><input type="submit" value="Submit" /></td></tr>
		</table>
		</form>
	<?php } ?>
		<input type="hidden" id="details_key" value="hide" />
		<div id="details_link"><a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>+ Show Details</strong></a></div>
		<div id="details_div" style="display:none">
		<table align="center" style="font-size:11pt; font-family:Calibri" >
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Sub System</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_sub_system; ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Store</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_store; ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Referred Inventory</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_refinv; ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Billed District</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_district; ?></td></tr>
		<?php if($main_quotation!='' && $main_quotation!=0)
			print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Quotation No</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.str_pad($main_quotation, 7, "0", STR_PAD_LEFT).'</td></tr>';
		?>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Type</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_type; ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">SMS Status</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><div id="div_smsresend"><table border="0" cellspacing="0"><tr><td><?php print $main_sms; ?> </td><td align="right"><?php if($sms_resend!=0) print '<input type="button" value="Resend" style="height:20px; font-size:10pt" onclick="smsResend('."'$sms_resend'".')" />'; ?></td></tr></table></div></td></tr>
		<?php if(($m_type==4)||($m_type==5)){
			print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Order Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.substr($main_ordered_date,0,16).'</td></tr>';
		} ?>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Invoiced Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print substr($main_billed_date,0,16); ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Invoiced By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_billed_by); ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">System User</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_sys_user); ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Tracking ID</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_tracking_id; ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Bill Comment 1</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_comment1); ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Bill Comment 2</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_comment1); ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Pay Comment</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_comment_pay); ?></td></tr>
		<?php if($bm_type==3 && $main_packed_by!='-'){
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Technicient</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.ucfirst($main_packed_by).'</td></tr>';
		}
			 if($bm_type==3 && $bm_status==5){
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Repaired Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.substr($main_shipped_date,0,16).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Delivered Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.substr($main_deliverd_date,0,16).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Delivered By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.ucfirst($main_deliverd_by).'</td></tr>';
		} ?>
		<?php if($bm_type==3 && $bm_status==7){
		if($main_shipped_by=='-') $rejected_by=$main_deliverd_by; else $rejected_by=$main_shipped_by;
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Rejected By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.ucfirst($rejected_by).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Rejected Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.substr($main_shipped_date,0,16).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Delivered Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.substr($main_deliverd_date,0,16).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Delivered By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.ucfirst($main_deliverd_by).'</td></tr>';
		} ?>
		<?php if($bm_status==0){
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Deleted Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.substr($main_deleted_date,0,16).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Deleted By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.ucfirst($main_deleted_by).'</td></tr>';
		} ?>
		</table>
		</div>
</td><td width="50px"></td><td>
	<table align="center" style="font-size:11pt" >
	<tr><td bgcolor="#467898" style="font-family:Calibri; color:white; padding-left:10px">Invoice Status : <span <?php if($status_out=='Deleted') print 'class="blink"'; ?> style="color:<?php print $status_color; ?>;"><strong><?php print $status_out; ?></strong></span></td><td></td></tr>
	<tr><td>
	<!-- ------------------Item List----------------------- -->
	<?php
	 if($tm_template==1) print '<iframe id="invoice_iframe" width="'.$page_width.'px" height="'.$page_height.'px" src="components/billing/view/tpl/invoice_print1.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==2) print '<iframe id="invoice_iframe" width="260px" height="740px" src="components/billing/view/tpl/invoice_print2.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==3) print '<iframe id="invoice_iframe" width="'.$page_width.'px" height="'.$page_height.'px" src="components/billing/view/tpl/invoice_print3.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==4) print '<iframe id="invoice_iframe" width="260px" height="740px" src="components/billing/view/tpl/invoice_print4.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==5) print '<iframe id="invoice_iframe" width="440px" height="550px" src="components/billing/view/tpl/invoice_print5.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==6) print '<iframe id="invoice_iframe" width="300px" height="740px" src="components/billing/view/tpl/invoice_print6.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==7) print '<iframe id="invoice_iframe" width="533px" height="775px" src="components/billing/view/tpl/invoice_print7.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==15) print '<iframe id="invoice_iframe" width="'.$page_width.'px" height="'.$page_height.'px" src="components/billing/view/tpl/invoice_print15.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==17) print '<iframe id="invoice_iframe" width="260px" height="740px" src="components/billing/view/tpl/invoice_print17.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==18) print '<iframe id="invoice_iframe" width="'.$page_width.'px" height="540px" src="components/billing/view/tpl/invoice_print18.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	?>


	</td><td valign="top" align="center">
					<div class="prtbutton1">
	<?php
	if(($bm_lock==1)||($bm_lock==2)){
		if($_COOKIE['fastprint']=='on'){
			print '<a class="shortcut-button" style="text-decoration:none; font-family:Arial; color:white;" onclick="print_bill2('."'print','".$_REQUEST['id']."'".')" href="#" >';
		}else{
			print '<a class="shortcut-button" style="text-decoration:none; font-family:Arial; color:white;" onclick="print_bill('.$_REQUEST['id'].')" href="#">';
		}
	?>
						<img src="images/print.png" alt="icon" /><br />
						Print Bill
					</a>
	<?php } ?>
					</div>
	<br />
		<?php if($bm_status!=0){
					if($billpermission){ ?>
					<div class="prtbutton2">
					<a class="shortcut-button" style="text-decoration:none; font-family:Arial; color:navy;" onclick="deleteBill(<?php print $_GET['id']; ?>)" href="#" >
						<img src="images/cancel.png" alt="icon" /><br />
						Cancel Bill
					</a>
					</div>
		<?php } } ?>
	<?php
	if($bm_lock==1){ ?>
	<input type="text" id="keytxt" style="width:10px; border:0px" onkeypress="KeyPress(event);" />
	<?php } ?>
	</td></tr>
	</table>
</td></tr></table>
<?php } ?>
<!-------------------------FastPrint----------------------------------------------------------------->
<?php if($_COOKIE['fastprint']=='on'){ ?>
<div id="printheader" style="display:none" ></div>
<div id="print" style="display:none" >
	<table width="210px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
		<?php
				print '<tr style="font-size:8pt;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Payment: Cash</td><td align="right">'.number_format($cash_amount).'&nbsp;&nbsp;</td></tr>';
				print '<tr style="font-size:8pt;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Payment: Chque</td><td align="right">'.number_format($chque_amount).'&nbsp;&nbsp;</td></tr>';
				print '<tr style="font-size:8pt;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Remaining Balance</td><td align="right">'.number_format($total-$cash_amount-$chque_amount).'&nbsp;&nbsp;</td></tr>';
				if($chq0_fullNo!='')
				print '	<tr><td colspan="4"><hr></td></tr>';
				print '	<tr><td colspan="4" align="center"><span style="padding-right:30px">'.$chq0_fullNo.'</span></td></tr>';
		?>
			</table>
			<table width="210px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
			<tr><td colspan="2"><hr></td></tr>
			<tr><td width="60px">Salesman :</td><td><?php print ucfirst($up_salesman); ?></td></tr>
			<tr><td>Customer :</td><td><?php print ucfirst($bi_cust); ?></td></tr>
			<tr><td height="30px">Signature :</td><td>...............................................</td></tr>
			<tr><td height="30px">Name :</td><td>...............................................</td></tr>
			<tr><td colspan="2"><hr></td></tr>
			<tr><td colspan="2" align="center">IT WAS A PLEASURE TO SERVE YOU<br><br>THANK YOU</td></tr>
			<tr><td colspan="2"><br></td></tr>
			</table>
<br />
</div>
<?php
}

                include_once  'template/footer.php';
?>