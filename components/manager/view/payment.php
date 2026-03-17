<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

	<script type="text/javascript">
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
		
		function validateSubPayment(){
			var $count=0;
		    var txt = "";
	        var payment_type = document.forms[0];
		    for (i = 0; i < payment_type.length; i++) {
		        if (payment_type[i].checked) {
		            txt = txt + payment_type[i].value + " ";
		        }
		    }
			if(txt=="") $count++;
			if(txt==1){
				if(document.getElementById('amount').value=='') $count++;
			}
			if(txt==2){
				if(document.getElementById('cheque_py').value==''){
					if(document.getElementById('chque_no').value=='') $count++;
					if(document.getElementById('tags4').value=='') $count++;
					if(document.getElementById('chque_branch').value=='') $count++;
					if(document.getElementById('chque_date').value=='') $count++;
					if(document.getElementById('amount').value=='') $count++;
				}
			}
			
			if($count==0){ return true; 
			}else{ 
				alert('Please Fill Payment Details');
				return false;
			}
		}
		
		function showChequeDiv($action){
			if($action=='hide'){
				document.getElementById("cheque_div").style.display = "none";
				document.getElementById("amount").disabled = false;
				document.getElementById('amount').value='';
				document.getElementById('cheque_py').value='';
			}
			if($action=='show')	document.getElementById("cheque_div").style.display = "block";
		}	
		
		function validateCustChq(){
			var cust_chq=document.getElementById('cheque_py').value;
			if(cust_chq==''){
				document.getElementById("my_cheque_div").style.display = "block";
				document.getElementById("amount").disabled = false;
				document.getElementById('amount').value='';
			}else{
				document.getElementById("my_cheque_div").style.display = "none";
				document.getElementById("amount").disabled = true;
			}
			
			var chq_pyid = [<?php for ($x=0;$x<sizeof($cheque_py_id);$x++){ print '"'.$cheque_py_id[$x].'",'; } ?>	];
			var chq_amount = [<?php for ($x=0;$x<sizeof($cheque_amount);$x++){ print '"'.$cheque_amount[$x].'",'; } ?>	];
			var cheque_py=document.getElementById('cheque_py').value;
			if(cheque_py!=''){
				var a=chq_pyid.indexOf(cheque_py);
				var amount=chq_amount[a];
				document.getElementById('amount').value=amount;
			}
		}
		
		function deletePayment(id){
		var check= confirm("Do you really want Delete this Payment?");
		 if (check== true)
			window.location = 'index.php?components=manager&action=delete_payment&id='+id;
		}
	</script>
<!-- ----------------------------------------------------------------------------- -->
<table align="center" style="font-family:Calibri"><tr><td><?php 
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
	print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
} ?><br /></td></tr></table>

<table align="center" style="font-family:Calibri; font-size:11pt; background-color:#467898; color:white; font-weight:600; border-radius: 15px;" width="1000px" ><tr><td align="center">Distributor Payment Submission</td></tr></table>

<table align="center"><tr><td>
<div style="background-color:#EEEEEE;  border-radius: 15px; padding-left:10px; padding-right:10px; padding-top:10px; padding-bottom:10px">
<table align="center" style="font-family:Calibri; font-size:12pt;">
	<tr><td valign="top">
	<!-- ------------------------------Payment Submission Form-------------------------------------- -->
	<?php if(!isset($_GET['pay_id'])){ ?>
	<form method="post" action="index.php?components=manager&action=add_payment" onsubmit="return validateSubPayment()">
	<table align="center" bgcolor="#EEEEEE" width="100%">
		<tr><td colspan="4" style="height:10px"></td></tr>
		<tr><td width="50px"></td><td><strong>Currunt Pending Balance</strong></td><td align="right" width="150px"><?php print number_format($bm_total-$py_total); ?></td><td width="50px"></td></tr>
		<tr><td></td><td><strong>Payment Type</strong></td><td align="right"><input type="radio" name="payment_type" id="payment_type" value="1" onchange="showChequeDiv('hide')" > Cash &nbsp;&nbsp;<input type="radio" name="payment_type" id="payment_type" value="2" onchange="showChequeDiv('show')" > Chque</td><td></td></tr>
		</table>
		<div id="cheque_div">
			<table align="center" bgcolor="#EEEEEE" width="100%">
			<tr><td width="50px"></td><td style="background-color:#BBEEDD"><strong>Cust Cheques</strong></td><td align="right" style="background-color:#BBEEDD">
				<select name="cheque_py" id="cheque_py" onchange="validateCustChq()">
					<option value="">-SELECT-</option>
					<?php for($i=0;$i<sizeof($cheque_py_id);$i++){
						print '<option value="'.$cheque_py_id[$i].'">'.$cheque_no[$i].'</option>';
					} ?>
				</select>
			</td><td width="50px"></td></tr>
			<tr><td></td><td colspan="2" valign="middle" style="background-color:#DDDDDD"><table width="100%"><tr><td><strong>My Chque</strong></td><td>
				<div id="my_cheque_div">
				<table align="right">
					<tr><td align="center">Code</td><td align="center">Bank</td><td align="center">Branch</td></tr>
					<tr><td align="center"><input type="number" name="chque_no" id="chque_no" style="width:60px" /></td><td align="center"><input type="text" name="chque_bank" id="tags4" style="width:35px" /></td><td align="center"><input type="text" name="chque_branch" id="chque_branch" style="width:35px" onfocus="validateBank()" /></td></tr>
					<tr><td align="center" colspan="3"><div style="font-size:11pt" id="bk_name" align="right"><br /></div></td></tr>
					<tr><td align="center" colspan="3"><input type="date" name="chque_date" id="chque_date" /></td></tr>
				</table>
				</div>
				</td></tr></table>
			</td><td></td></tr>
			</table>
		</div>
		<table align="center" bgcolor="#EEEEEE" width="100%">
		<tr><td width="50px"></td><td><strong>Payment Amount</strong></td><td align="right"><input type="text" id="amount" name="amount" value="" style="width:100px; text-align:right" /></td><td width="50px"></td></tr>
		<tr><td colspan="4" align="center"><input type="submit" value="Submit Payment" style="width:130px; height:40px" /></td></tr>
		<tr><td colspan="4" style="height:10px"></td></tr>
	</table>
	</form>
		<!-- ------------------------------Payment Show Form -------------------------------------- -->
	<?php }else{ ?>
	<table align="center" bgcolor="#EEEEEE" width="100%">
		<tr><td colspan="4" style="height:10px"></td></tr>
		<tr><td width="50px"></td><td><strong>Payment ID</strong></td><td align="right"><strong><?php print str_pad($_GET['pay_id'], 7, "0", STR_PAD_LEFT); ?></strong></td></tr>
		<tr><td width="50px"></td><td><strong>Payment Type</strong></td><td align="right"><input type="radio" name="payment_type" id="payment_type" value="1" <?php if($ps_type==1) print 'checked="checked"'; ?> disabled="disabled" > Cash &nbsp;&nbsp;<input type="radio" name="payment_type" id="payment_type" value="2" <?php if($ps_type==2) print 'checked="checked"'; ?> disabled="disabled" > Chque</td><td width="50px"></td></tr>
		</table>
		<?php if($ps_type==2){ ?>
			<table align="center" bgcolor="#EEEEEE" width="100%">
			<?php if($ps_cust_chq!=''){ ?><tr><td width="50px"></td><td style="background-color:#BBEEDD"><strong>Cust Cheques</strong></td><td align="right" style="background-color:#BBEEDD"><input type="text" value="<?php print $ps_chque_no.' : '.$bank_code[array_search($ps_chque_bank,$bank_id)].' : '.$ps_chque_branch; ?>" style="width:160px" disabled="disabled"  /></td><td width="50px"></td></tr><?php }else{ ?>
			<tr><td width="50px"></td><td colspan="2" valign="middle" style="background-color:#DDDDDD"><table width="100%"><tr><td><strong>My Chque</strong></td><td>
				<div id="my_cheque_div">
				<table align="right">
					<tr><td align="center">Code</td><td align="center">Bank</td><td align="center">Branch</td></tr>
					<tr><td align="center"><input type="number" name="chque_no" id="chque_no" style="width:60px" value="<?php print $ps_chque_no; ?>" disabled="disabled" /></td><td align="center"><input type="text" name="chque_bank" id="tags4" style="width:35px" value="<?php print $bank_code[array_search($ps_chque_bank,$bank_id)]; ?>" disabled="disabled" /></td><td align="center"><input type="text" name="chque_branch" id="chque_branch" style="width:35px" value="<?php print $ps_chque_branch; ?>" disabled="disabled" /></td></tr>
					<tr><td align="center" colspan="3"><div style="font-size:11pt" id="bk_name" align="right"><br /></div></td></tr>
					<tr><td align="center" colspan="3"><input type="date" name="chque_date" id="chque_date" value="<?php print $ps_chque_date; ?>" disabled="disabled" /></td></tr>
				</table>
				</div>
				</td></tr></table>
			</td><td width="50px"></td></tr>
			<?php } ?>
			</table>
		<?php } ?>
		<table align="center" bgcolor="#EEEEEE" width="100%">
		<tr><td width="50px"></td><td><strong>Payment Amount</strong></td><td align="right"><input type="text" id="amount" name="amount" value="<?php print $ps_amount; ?>" style="width:100px; text-align:right" disabled="disabled" /></td><td width="50px"></td></tr>
		<tr><td width="50px"></td><td><strong>Submited By</strong></td><td align="right"><?php print ucfirst($ps_submited_by).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <a title="Time: '.substr($ps_submited_date,11,5).'" style="cursor:pointer; color:blue">'.substr($ps_submited_date,0,10).'</a>'; ?></td><td width="50px"></td></tr>
		<tr><td width="50px"></td><td><strong>Processed By</strong></td><td align="right"><?php print ucfirst($ps_processed_by).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <a title="Time: '.substr($ps_processed_date,11,5).'" style="cursor:pointer; color:blue">'.substr($ps_processed_date,0,10).'</a>'; ?></td><td width="50px"></td></tr>
		<tr><td width="50px"></td><td><strong>Status</strong></td><td align="right" style="color:<?php print substr($ps_status,strpos($ps_status,'|')+1); ?>"><?php print substr($ps_status,0,strpos($ps_status,'|')); ?></td><td width="50px"></td></tr>
		<?php if(substr($ps_status,0,strpos($ps_status,'|'))=='Pending'){ ?><tr><td colspan="4" align="center"><input type="button" value="Delete" style="width:60px; height:40px; background-color:#E50000; color:white" onclick="deletePayment(<?php print $_GET['pay_id']; ?>);" /></td></tr><?php } ?>
		<tr><td colspan="4" style="height:10px"></td></tr>
	</table>
	<script type="text/javascript">validateBank();</script>
	<?php } ?>
</td></tr></table>
</div>
	<hr />
	<table align="center" style="font-size:11pt; font-family:Calibri">
		<tr style="background-color:#777777; color:white;"><td colspan="4" style="padding-left:20px">Last 10 Processed Payments</td></tr>
		<tr style="background-color:#467898; color:white;"><th style="padding-left:20px; padding-right:20px">Payment ID</th><th style="padding-left:20px; padding-right:20px">Submited Date</th><th style="padding-left:20px; padding-right:20px">Amount</th><th style="padding-left:20px; padding-right:20px">Action</th></tr>
		<?php for($i=0;$i<sizeof($last_id);$i++){
				if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			print '<tr style="background-color:'.$color.'"><td align="center"><a href="index.php?components=manager&action=payment&pay_id='.$last_id[$i].'" >'.str_pad($last_id[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center"><a style="cursor:pointer; color:blue;" title="Submited By: '.ucfirst($last_submited_by[$i]).'">'.$last_submited_date[$i].'</a></td><td style="padding-right:20px" align="right">'.number_format($last_amount[$i]).'</td><td align="center"><a style="cursor:pointer; color:'.substr($last_status[$i],strpos($last_status[$i],'|')+1).';" title="Processed By: '.ucfirst($last_processed_by[$i]).'&#13;Processed Date: '.$last_processed_date[$i].'">'.substr($last_status[$i],0,strpos($last_status[$i],'|')).'</a></td></tr>';
		} ?>
	</table>
	</td><td width="50px"></td><td valign="top">
	<table style="font-size:11pt; font-family:Calibri">
		<tr style="background-color:#777777; color:white;"><td colspan="4" style="padding-left:20px">Pending Payment Submissions</td></tr>
		<tr style="background-color:#467898; color:white;"><th style="padding-left:20px; padding-right:20px">Payment ID</th><th style="padding-left:20px; padding-right:20px">Submited Date</th><th style="padding-left:20px; padding-right:20px">Submited By</th><th style="padding-left:20px; padding-right:20px">Amount</th></tr>
		<?php for($i=0;$i<sizeof($pending_id);$i++){
				if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			print '<tr style="background-color:'.$color.'"><td align="center"><a href="index.php?components=manager&action=payment&pay_id='.$pending_id[$i].'" >'.str_pad($pending_id[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center"><a style="cursor:pointer; color:blue;" title="Time: '.substr($pending_submited_time[$i],0,5).'">'.$pending_submited_date[$i].'</a></td><td style="padding-left:20px">'.ucfirst($pending_submited_by[$i]).'</td><td style="padding-right:20px" align="right">'.number_format($pending_amount[$i]).'</td></tr>';
		} ?>
	</table>
	</td></tr>
</table>
<?php
                include_once  'template/footer.php';
?>