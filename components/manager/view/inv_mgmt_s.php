<?php
	include_once  'template/header.php';
	if($type=='bill_no'){
		$ty_name='Bill'; 
		$url='index.php?components=billing&action=finish_bill&id=';
	}else
	if($type=='pay_no'){
		$ty_name='Pay';
		$url='index.php?components=billing&action=finish_payment&id=';
	}
	$components=$_GET['components'];
?>
<script type="text/javascript">
	function searchINV($type){
	  var $invoice_no=document.getElementById($type).value;
	  if($invoice_no!=''){
		  window.location = 'index.php?components=<?php print $components; ?>&action=inv_mgmt&type='+$type+'&id='+$invoice_no;
	  }else{
	  	  window.alert('Invoice Number Cannot be Empty');
	  }
	}
	//updated by e.s.p.n 2021-07-27
	function changeSM($type){
		var check= confirm("Do you want to change Salesman ?");
	  	var $invoice_no=document.getElementById($type+'_hi').value;
		var $sm= document.getElementById('sm').value;
		var $master_pw4=document.getElementById('master_pw4').value;
	 	if(check==true){
			if($master_pw4!=''){
				document.getElementById('div_dateset1').innerHTML=document.getElementById('loading').innerHTML;
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				  	  var returntext=this.responseText;
						if(returntext=='Done'){
							document.getElementById('div_dateset1').innerHTML='<span style="color:green">Done</span>';
							document.getElementById('notifications').innerHTML='<span style="color:green">Salesman was Changed Successfully</span>';
						}else{
							document.getElementById('div_dateset1').innerHTML='<span style="color:red">Error</span>';
							document.getElementById('notifications').innerHTML='<span style="color:red">'+returntext+'</span>';
						}
					}
				};
				xmlhttp.open("POST", "index.php?components=manager&action=inv_mgmt_changesm", true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send('id='+$invoice_no+'&type='+$type+'&sm='+$sm+'&master_pw4='+$master_pw4);
			}else{
				alert('Please fill the Date and Master Password');
			}
		}
	}
	//updated by e.s.p.n 2021-07-27
	function changeRecoveryAgent($type){
		var check = confirm("Do you want to change Recovery Agent?");
	  	var $invoice_no = document.getElementById($type+'_hi').value;
		var $recovery_agent = document.getElementById('recoveryAgent').value;
		var $master_pw4 = document.getElementById('master_pw4').value;
	 	if(check==true){
			if($master_pw4!=''){
				document.getElementById('div_dateset2').innerHTML=document.getElementById('loading').innerHTML;
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				  	  var returntext=this.responseText;
						if(returntext=='Done'){
							document.getElementById('div_dateset2').innerHTML='<span style="color:green">Done</span>';
							document.getElementById('notifications').innerHTML='<span style="color:green">Recovery Agent was Changed Successfully</span>';
						}else{
							document.getElementById('div_dateset2').innerHTML='<span style="color:red">Error</span>';
							document.getElementById('notifications').innerHTML='<span style="color:red">'+returntext+'</span>';
						}
					}
				};
				xmlhttp.open("POST", "index.php?components=manager&action=inv_mgmt_change_recovery_agent", true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send('id='+$invoice_no+'&recovery_agent='+$recovery_agent+'&master_pw4='+$master_pw4);
			}else{
				alert('Please fill the Date and Master Password');
			}
		}
	}
		
	function setInvMain(){
		$inv_no=document.getElementById('inv_no').value;
		$inv_type=document.getElementById('inv_type').value;
		$inv_status=document.getElementById('inv_status').value;
		$inv_sms=document.getElementById('inv_sms').value;
		$master_pw4=document.getElementById('master_pw4').value;
		$auth_pass=document.getElementById('auth_pass').value;
		if($master_pw4!=''){
			document.getElementById('div_dateset2').innerHTML=document.getElementById('loading').innerHTML;
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			  	  var returntext=this.responseText;
					if(returntext=='Done'){
						document.getElementById('div_dateset2').innerHTML='<span style="color:green"><input type="button" onclick="setInvMain()" value="Set" style="height:50px; width:70px" /><br />Done</span>';
						document.getElementById('notifications').innerHTML='<span style="color:green">'+returntext+'</span>';
					}else{
						document.getElementById('div_dateset2').innerHTML='<span style="color:red"><input type="button" onclick="setInvMain()" value="Set" style="height:50px; width:70px" /><br />Error</span>';
						document.getElementById('notifications').innerHTML='<span style="color:red">'+returntext+'</span>';
					}
				}
			};
			xmlhttp.open("POST", "index.php?components=manager&action=set_inv_main", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('inv_no='+$inv_no+'&inv_type='+$inv_type+'&inv_status='+$inv_status+'&inv_sms='+$inv_sms+'&master_pw4='+$master_pw4+'&auth_pass='+$auth_pass);
		}else{
			alert('Please fill the Date and Master Password');
		}
	}
	
	function setInvDate(){
		$inv_no=document.getElementById('inv_no').value;
		$bm_date=document.getElementById('bm_date').value;
		$master_pw4=document.getElementById('master_pw4').value;
		if(document.getElementById('with_pay').checked == true) $with_pay='yes'; else $with_pay='no';
		if(($bm_date!='')&&($master_pw4!='')){
			document.getElementById('div_dateset3').innerHTML=document.getElementById('loading').innerHTML;
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			  	  var returntext=this.responseText;
					if(returntext=='Done'){
						document.getElementById('div_dateset3').innerHTML='<span style="color:green"><input type="button" value="SET" style="height:50px" onclick="setInvDate()" /><br />Done</span>';
						document.getElementById('notifications').innerHTML='<span style="color:green">'+returntext+'</span>';
					}else{
						document.getElementById('div_dateset3').innerHTML='<span style="color:red"><input type="button" value="SET" style="height:50px" onclick="setInvDate()" /><br />Error</span>';
						document.getElementById('notifications').innerHTML='<span style="color:red">'+returntext+'</span>';
					}
				}
			};
			xmlhttp.open("POST", "index.php?components=manager&action=set_inv_date", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('inv_no='+$inv_no+'&bm_date='+$bm_date+'&with_pay='+$with_pay+'&master_pw4='+$master_pw4);
		}else{
			alert('Please fill the Date and Master Password');
		}
	}
	
	function setPayDate(){
		$py_no=document.getElementById('py_no').value;
		$py_date=document.getElementById('py_date').value;
		$master_pw4=document.getElementById('master_pw4').value;
		if(($py_date!='')&&($master_pw4!='')){
			document.getElementById('div_dateset3').innerHTML=document.getElementById('loading').innerHTML;
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			  	  var returntext=this.responseText;
					if(returntext=='Done'){
						document.getElementById('div_dateset3').innerHTML='<span style="color:green"><input type="button" value="SET" style="height:50px" onclick="setPayDate()" /><br />Done</span>';
						document.getElementById('notifications').innerHTML='<span style="color:green">'+returntext+'</span>';
					}else{
						document.getElementById('div_dateset3').innerHTML='<span style="color:red"><input type="button" value="SET" style="height:50px" onclick="setPayDate()" /><br />Error</span>';
						document.getElementById('notifications').innerHTML='<span style="color:red">'+returntext+'</span>';
					}
				}
			};
			xmlhttp.open("POST", "index.php?components=manager&action=set_pay_date", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('py_no='+$py_no+'&py_date='+$py_date+'&master_pw4='+$master_pw4);
		}else{
			alert('Please fill the Date and Master Password');
		}
	}
</script>
<!-- ----------------------------------------------------------------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<table align="center" style="font-family:Calibri"><tr><td>
<?php if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
} ?>
</td></tr></table>

<table align="center" style="font-family:Calibri; font-weight:bold; border-radius: 15px;" bgcolor="#EEEEEF" width="600px"><tr><td align="center">Invoice Management</td></tr></table>
<br />
<table align="center" style="font-family:Calibri; font-weight:bold;" border="0">
<tr><td width="200" rowspan="2"></td><td align="center" width="130px" style="background-color:#EEEEEE"><input type="text" id="bill_no" placeholder="Invoice No" style="width:80px; text-align:center" /></td><td width="50px" align="center" style="background-color:#EEEEEE"></td><td><input type="button" value="Search Bill" onclick="searchINV('bill_no')" style="width:100px; height:40px; background-color:#EEEEEE;" value="<?php if($type=='bill_no') print $id; ?>" /></td><td width="200" rowspan="2" align="right"><input type="password" id="master_pw4" placeholder="Master Password4" style="height:40px; border-color:red; text-align:center" /></td></tr>
<tr style="background-color:#EEEEEE"><td align="center"><input type="text" id="pay_no" placeholder="Payment No" style="width:80px; text-align:center" /></td><td width="50px" align="center"></td><td><input type="button" value="Search Pay" onclick="searchINV('pay_no')" style="width:100px; height:40px" value="<?php if($type=='pay_no') print $id; ?>" /></td></tr>
</table>
<br />
<?php if(($type!='') && ($inv_billed_by!='')){ ?>
<input type="hidden" id="<?php print $type; ?>_hi" value="<?php print $id; ?>" />
<table align="center" style="font-family:Calibri;">
<tr style="background-color:#467898;color :white;"><th width="150px"><?php print $ty_name; ?> No</th><th width="150px">Date</th><th width="150px">Store</th><th width="150px">Amount</th><th width="150px">Status</th><th width="150px">Salesman</th><th width="120px">Recovery Agent</th></tr>
<?php 	print '<tr style="background-color:#CCCCCC"><td align="center"><a href="'.$url.$id.'">'.str_pad($id, 7, "0", STR_PAD_LEFT).'</a></td><td align="center">'.$inv_inv_date.'</td><td align="center">'.$inv_store.'</td><td align="right">'.number_format($inv_total).'&nbsp;&nbsp;&nbsp;</td><td align="center" style="color:'.$status_color.'">'.$status_out.'</td><td align="center">';
		print '<select id="sm" onchange="changeSM('."'$type'".')">';
		for($i=0;$i<sizeof($sm_id);$i++){
			if($sm_id[$i]==$inv_billed_by) { $select='selected="selected"'; } else{ $select='';}
			print '<option value="'.$sm_id[$i].'" '.$select.'>'.ucfirst($sm_name[$i]).'</option>';
		}
		print '</select>';
		print '<div id="div_dateset1"></div>';
		print '</td>';

		print '<td align="center">';
		if($recovery_agent != ''){
			print '<select id="recoveryAgent" onchange="changeRecoveryAgent('."'$type'".')">';
			for($i=0;$i<sizeof($sm_id);$i++){
				if($sm_id[$i]==$recovery_agent) { $select='selected="selected"'; } else{ $select='';}
				print '<option value="'.$sm_id[$i].'" '.$select.'>'.ucfirst($sm_name[$i]).'</option>';
			}
			print '</select>';
			print '<div id="div_dateset2"></div>';
		}
		print '</td>';
		print '</tr>';
		
if($inv_pay_id!=''){
?>
<tr style="background-color:maroon;color :white;"><th width="150px" colspan="6" align="left" class="shipmentTB3">Related Payment</th></tr>
<tr style="background-color:#467898;color :white;"><th width="150px">Payment No</th><th width="150px">Date</th><th width="150px"></th><th width="150px">Amount</th><th width="150px"></th><th width="150px">Salesman</th></tr>
<?php 	print '<tr style="background-color:#CCCCCC"><td align="center"><a href="index.php?components=billing&action=finish_payment&id='.$inv_pay_id.'">'.str_pad($inv_pay_id, 7, "0", STR_PAD_LEFT).'</a></td><td align="center">'.$inv_pay_date.'</td><td align="center" style="color:'.$inv_pay_typec.'">'.$inv_pay_typen.'</td><td align="right">'.number_format($inv_pay_amount).'&nbsp;&nbsp;&nbsp;</td><td></td><td align="center">'.ucfirst($inv_pay_by).'</td></tr>';
}
?>
</table>
<br /><br />
<?php if($_GET['type']=='bill_no'){ ?>
	<input type="hidden" id="inv_no" name="bill_no" value="<?php print $_GET['id']; ?>">
	<table align="center" style="font-family:Calibri;" bgcolor="#EEEEEE" border="0">
	<tr><td>
		<table style="font-family:Calibri;" border="0" >
		<tr><th style="background-color:#467898;color :white;" width="100px">Type</th><th>
			<select id="inv_type" name="type">
			<option value="1" <?php if($inv_type_id==1) print 'selected="selected"'; ?> >Sales Bill</option>
			<option value="4" <?php if($inv_type_id==4) print 'selected="selected"'; ?> >Cust Order</option>
			</select>
		</th></tr>
		<tr><th style="background-color:#467898;color :white;" width="100px">Status</th><th>
			<select id="inv_status" name="status">
			<option value="1" <?php if($inv_status==1) print 'selected="selected"'; ?> >Billed</option>
			<option value="2" <?php if($inv_status==2) print 'selected="selected"'; ?> >Picked</option>
			<option value="3" <?php if($inv_status==3) print 'selected="selected"'; ?> >Packed</option>
			<option value="4" <?php if($inv_status==4) print 'selected="selected"'; ?> >Shipped</option>
			<option value="5" <?php if($inv_status==5) print 'selected="selected"'; ?> >Delivered</option>
			</select>
		</th></tr>
		<tr><th style="background-color:#467898;color :white;" width="100px">SMS</th><th>
			<select id="inv_sms" name="sms">
			<option value="1" <?php if($inv_sms==1) print 'selected="selected"'; ?> >Sent</option>
			<option value="0" <?php if($inv_sms==0) print 'selected="selected"'; ?> >Not Send</option>
			</select>
		</th></tr>
		</table>
	</td><td width="120px" align="center">
		<input type="password" id="auth_pass" name="auth_pass" placeholder="Auth Code" style="width:65px" />
		<div id="div_dateset2"><input type="button" onclick="setInvMain()" value="Set" style="height:50px; width:70px" /></div>
	</td></tr>
	
	<tr style="background-color:white"><td height="10px" colspan="2"></td></tr>
	<tr><td colspan="2">
		<table style="font-family:Calibri;" border="0" >
		<tr><th style="background-color:#467898;color :white;" width="100px">Invoice Date</th><th><input type="date" id="bm_date" value="<?php print $inv_inv_date; ?>" style="width:130px" /></th><th rowspan="2"><div id="div_dateset3"><input type="button" value="SET" style="height:50px" onclick="setInvDate()" /></div></th></tr>
		<tr><th style="background-color:#467898;color :white;" width="100px">Set both Invoice & payment Date</th><th><input type="checkbox" id="with_pay" style="width:130px" /></th></tr>
		</table>
	</td></tr>
	</table>
<?php } ?>
<?php if($_GET['type']=='pay_no'){ ?>
	<input type="hidden" id="py_no" value="<?php print $_GET['id']; ?>">
	<table align="center" style="font-family:Calibri;" bgcolor="#EEEEEE" border="0">
	<tr><td colspan="2">
		<table style="font-family:Calibri;" border="0" >
		<tr><th style="background-color:#467898;color :white;" width="100px">Payment Date</th><th><input type="date" id="py_date" value="<?php print $pay_date; ?>" style="width:130px" /></th><th rowspan="2"><div id="div_dateset3"><input type="button" value="SET" style="height:50px" onclick="setPayDate()" /></div></th></tr>
		</table>
	</td></tr>
	</table>
<?php } ?>
<?php
}
                include_once  'template/footer.php';
?>