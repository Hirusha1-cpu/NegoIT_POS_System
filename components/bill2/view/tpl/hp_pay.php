<script type="text/javascript">
	function switchPayment(){
	  var type_val = "";
	 if(document.getElementById("payment_type1").checked) type_val=1;
	 if(document.getElementById("payment_type2").checked) type_val=2;
	 if(document.getElementById("payment_type3").checked) type_val=3;
	  
	  	if(type_val==1){
		  	document.getElementById("sw_cheque").style.display = "none";
	  		document.getElementById("sw_bank").style.display = "none";
	  		document.getElementById("div_bank_sw").innerHTML='';
	  	}
	  	if(type_val==2){
		  	document.getElementById("sw_cheque").style.display = "none";
	  		document.getElementById("sw_bank").style.display = "block";
	  		document.getElementById("div_bank_sw").innerHTML='<input type="hidden" name="cash_bank_switch" value="1" />';
	  	}
	  	if(type_val==3){
		  	document.getElementById("sw_cheque").style.display = "block";
	  		document.getElementById("sw_bank").style.display = "none";
	  		document.getElementById("div_bank_sw").innerHTML='';
	  	}
	  	document.getElementById("pay_type").value=type_val;
	}

function getRemainingAmount(){
	$invoice_no=document.getElementById("invoice_no").value;
	$inst_date=document.getElementById("inst_date").value;
    $today=document.getElementById("today").value;
    document.getElementById("div_amount").innerHTML=document.getElementById("loading").innerHTML;
    
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var myObj = JSON.parse(xmlhttp.responseText);
				document.getElementById("div_amount").innerHTML=myObj.type;
				document.getElementById("pay_amount").value=myObj.remaining;
				document.getElementById("pay_amount0").value=myObj.remaining;
		}
	};

	xmlhttp.open("POST", "index.php?components=bill2&action=hp_get_pending_amount", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('invoice_no='+$invoice_no+'&inst_date='+$inst_date);
}

function validateHPPay(){
	$pay_type=document.getElementById("pay_type").value;
	$inst_date=document.getElementById("inst_date").value;
	$tr_bank=document.getElementById("tr_bank").value;
	$bank_auth_validity=document.getElementById("bank_auth_validity").value;
	$chque_no=document.getElementById("chque_no").value;
	$chque_bank=document.getElementById("tags4").value;
	$chque_branch=document.getElementById("chque_branch").value;
	$chque_date=document.getElementById("chque_date").value;
	$pay_amount=parseFloat(document.getElementById("pay_amount").value);
	$pay_amount0=parseFloat(document.getElementById("pay_amount0").value);	
	
	var $count=0;
	var $msg="";
    if($pay_type==0) $count++;
    if($inst_date=='') $count++;
    if($count>0) $msg='Please Select Payment Type and Instalment Date';
    if(($count==0)&&($pay_type==2)){
    	if($tr_bank=='') $count++;
    	if($count>0) $msg='Please Fill Bank Details';
    	if($count==0){
	    	if($bank_auth_validity==0){$count++; $msg="Auth Code Validation Failed"; }
    	}
    }
    if(($count==0)&&($pay_type==3)){
    	if($chque_no=='') $count++;
    	if($chque_bank=='') $count++;
    	if($chque_branch=='') $count++;
    	if($chque_date=='') $count++;
    	if($count>0) $msg='Please Fill Cheque Details';
    }
    
    if($count==0){
    	$extra=$pay_amount-$pay_amount0;
    	if($pay_amount==0){ $count++; $msg='The Amount cannot be Empty'; }
    	if($pay_amount>$pay_amount0){ $count++; $msg='Over pay is denied! Kindly pay the extra amount of '+$extra+' to next installment.'; }
    }
    
    if($count>0){
    	alert($msg);
    	return false;
    }else{
    	document.getElementById("addpayment").innerHTML=document.getElementById("loading").innerHTML;
    	return true;
    }
}

function bankPayValidate(){
    $auth_code=document.getElementById('auth_code').value;
	$invoice_no=document.getElementById("invoice_no").value;
	document.getElementById('div_bk_validate').innerHTML=document.getElementById('loading').innerHTML;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var returntext=xmlhttp.responseText;
			if(returntext!=''){
				if(returntext=='validated'){
					document.getElementById('bank_auth_validity').value=1;
					document.getElementById('div_bk_validate').innerHTML='<span style="color:green">Validated</span>';
				}else{
					document.getElementById('bank_auth_validity').value=0;
					document.getElementById('div_bk_validate').innerHTML='<span style="color:red">Invalid Code</span>';
				}
			}
		}
	};
	xmlhttp.open("POST", "index.php?components=bill2&action=auth_code_validate", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('invoice_no='+$invoice_no+'&auth_code='+$auth_code);
}

</script>

		<input type="hidden" id="pay_amount0" name="pay_amount0" value="0" />
		<input type="hidden" id="bank_auth_validity" value="<?php if($systemid==15) print '0'; else print '1'; ?>" />
		
		<form name="payForm" action="index.php?components=bill2&action=add_hp_payment" onsubmit="return validateHPPay()" method="post" >
		<input type="hidden" name="invoice_no" id="invoice_no" value="<?php print $_GET['id']; ?>" />
		<input type="hidden" name="pay_type" id="pay_type" value="0" />
		<input type="hidden" id="today" value="<?php print $today0; ?>" />
		<table align="center" bgcolor="#E5E5E5" style="border-radius: 15px;" border="0">
		<tr><td width="50px"></td><td style="font-size:12pt">Payment Type</td><td>  
			<input type="radio" name="payment_type" id="payment_type1" value="1" onchange="switchPayment()" > Cash &nbsp;&nbsp;
			<input type="radio" name="payment_type" id="payment_type2" value="3" onchange="switchPayment()" > Bank &nbsp;&nbsp;
		 	<input type="radio" name="payment_type" id="payment_type3" value="2" onchange="switchPayment()" > Chque &nbsp;&nbsp;
		<br /><br /></td><td width="50px"></td></tr>
		<tr><td></td><td colspan="2">
			<div id="sw_bank" style="display:none">
					<table width="100%" style="font-size:12pt" cellspacing="0">
					<tr><td style="font-size:12pt" width="100px">Bank</td><td>
					<select name="tr_bank" id="tr_bank">
						<option value="">-SELECT BANK-</option>
						<?php for($i=0;$i<sizeof($ac_bank_id);$i++){
							print '<option value="'.$ac_bank_id[$i].'">'.$ac_bank_name[$i].'</option>';
						}	?>
						</select>
					</td><td></td></tr>
					<?php if($systemid==15){ ?>
						<tr><td style="font-size:12pt" width="100px"></td><td>
							<input type="password" id="auth_code" style="width:80px" placeholder="Auth Code" /><input type="button" onclick="bankPayValidate()" value="validate" />
						</td><td><div id="div_bk_validate"></div></td></tr>
					<?php } ?>
					</table>
					<dir id="div_bank_sw" ></dir>
			</div>
		</td><td></td></tr>
		<tr><td></td><td colspan="2">
			<div id="sw_cheque" style="display:none">
					<table width="100%" style="font-size:12pt" cellspacing="0">
					<tr><td width="100px"></td><td>Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bank &nbsp;&nbsp;&nbsp; Branch</td><td></td><td></td><td></td></tr>
					<tr><td>Chque </td><td><table cellspacing="0"><tr><td><input type="number" name="chque_no" id="chque_no" style="width:60px" />&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="chque_bank" id="tags4" style="width:35px" />&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="chque_branch" id="chque_branch" style="width:35px" onfocus="validateBank()" /></td></tr></table></td><td></td></tr>
					<tr><td height="20px"></td><td><div style="font-size:12pt" id="bk_name" align="right"></div></td><td><div style="font-size:12pt" id="av_qty" align="right"></div></td></tr>
					<tr><td>Chque Date</td><td><input type="date" name="chque_date" id="chque_date" /></td><td><div style="font-size:12pt" id="av_qty" align="right"></div></td></tr>
					</table>
			</div>
		</td><td></td></tr>
		<tr><td></td><td style="font-size:12pt">Instalment Date</td><td>
			<select id="inst_date" name="inst_date" onchange="getRemainingAmount()" >
				<option value="">-SELECT-</option>
				<?php for($i=0;$i<sizeof($hp_schedule);$i++){
				 print '<option value="'.$hp_schedule[$i].'">'.$hp_schedule[$i].'</option>';
				} ?>
			</select>
			
		&nbsp;&nbsp;&nbsp;&nbsp;
		</td><td></td></tr>
		<tr><td></td><td style="font-size:12pt">Amount</td><td colspan="2"><table cellspacing="0"><tr><td><input type="number" id="pay_amount" name="pay_amount" value="<?php print $hp_amount; ?>" style="text-align:right; width:80px" /></td><td width="10px"></td><td><div id="div_amount" align="center" style="color:blue"></div></td></tr></table></td></tr>
		<tr><td></td><td style="font-size:12pt">Comment</td><td><textarea name="comment" style="width:100%" ></textarea></td><td></td></tr>
		<tr><td></td><td></td><td colspan="2" height="10px"><div id="addpayment"><input type="submit" value="Add Payment" style="width:100px; height:50px" /></div></td></tr>
		<tr><td colspan="4" height="10px"></td></tr>
		</table>
		</form>
