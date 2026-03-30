<?php
                include_once  'template/header.php';
                $bill_salesman=$_COOKIE['user_id'];
                if(isset($_GET['s'])){ if($_GET['s']!='')  $bill_salesman=$_GET['s']; }
                $cust_odr=$_GET['cust_odr'];
                if($cust_odr=='yes') $main_tale_color='#DDDDFF'; else $main_tale_color='#E5E5E5';
                if($systemid==13) $decimal=2; else $decimal=0;
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
<?php	if($bmcust_name!=''){ ?>		
		var availableTags4 = [<?php for ($x=0;$x<sizeof($bank_code);$x++){ print '"'.$bank_code[$x].'",'; } ?>	];
		$( "#tags4" ).autocomplete({
			source: availableTags4
		});
<?php	} ?>		
	});
<?php	if($bmcust_name!=''){ ?>		
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

	window.onload = function() {
	  document.getElementById("amount_cash").focus();
	};	
	
	function payTypeSwitch(){
		if(document.forms["payForm"]["switch1"][0].checked) $pay_type=document.forms["payForm"]["switch1"][0].value;
		if(document.forms["payForm"]["switch1"][1].checked) $pay_type=document.forms["payForm"]["switch1"][1].value;
		if(document.forms["payForm"]["switch1"][2].checked) $pay_type=document.forms["payForm"]["switch1"][2].value;
		if($pay_type==1){
			var div_pay=document.getElementById('cash_bank_card_div1').innerHTML;
			document.getElementById('py_name1').innerHTML="Cash Payment";
		}
		if($pay_type==3){
			var div_pay=document.getElementById('cash_bank_card_div3').innerHTML;
			document.getElementById('py_name1').innerHTML="Bank Payment";
		}
		if($pay_type==4){
			var div_pay=document.getElementById('cash_bank_card_div4').innerHTML;
			document.getElementById('py_name1').innerHTML="Card Payment";
		}
		
		document.getElementById('cash_bank_card_div').innerHTML=div_pay;
		setPayment('cash');
	}
	
	</script>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------- -->
	<!-- ----------------------Cash Payment----------------------------------------------- -->
	<div id="cash_bank_card_div1" style="display:none">
	<table align="center" bgcolor="<?php print $main_tale_color; ?>" height="100%">
	<tr><td></td><td colspan="2">
		<table width="100%" border="0" cellspacing="0">
		<tr>
		<td valign="middle" bgcolor="#CCCCCC" align="center">Cash</td><td bgcolor="#CCCCCC"><input type="radio" name="switch1" value="1" onclick="payTypeSwitch()" checked="checked" /></td>
		<td valign="middle" bgcolor="#D9D9D9" align="center">Bank</td><td bgcolor="#D9D9D9"><input type="radio" name="switch1" value="3" onclick="payTypeSwitch()" /></td>
		<td valign="middle" bgcolor="#CCCCCC" align="center">Card</td><td bgcolor="#CCCCCC"><input type="radio" name="switch1" value="4" onclick="payTypeSwitch()" /></td>
		</tr>
		</table>
	</td><td></td></tr>
	<tr><td width="30px" ></td><td colspan="2" width="230px"><table width="100%"><tr><td style="font-size:12pt; color:navy; font-weight:bold; height:40px;">Cash Payment</td><td align="right">	</td></tr></table></td><td width="30px">
	</td></tr>
	<tr><td></td><td colspan="2"><table><tr><td><input type="text" name="amount_cash" id="amount_cash" value="0" style="width:60px; text-align:right" onclick="this.value=''" /></td><td><input type="button" value="Add Payment" style="width:100px; height:40px" onclick="setPayment('cash')" /></td></tr></table></td><td></td></tr>
	<tr><td height="10px" colspan="4"></td></tr>
	</table>
	</div>
	<!-- ----------------------Bank Payment----------------------------------------------- -->
	<div id="cash_bank_card_div3" style="display:none">
	<input type="hidden" name="cash_bank_switch" value="3" />
	<table align="center" bgcolor="<?php print $main_tale_color; ?>" height="100%">
	<tr><td></td><td colspan="2">
		<table width="100%" border="0" cellspacing="0">
		<tr>
		<td valign="middle" bgcolor="#CCCCCC" align="center">Cash</td><td bgcolor="#CCCCCC"><input type="radio" name="switch1" value="1" onclick="payTypeSwitch()" /></td>
		<td valign="middle" bgcolor="#D9D9D9" align="center">Bank</td><td bgcolor="#D9D9D9"><input type="radio" name="switch1" value="3" onclick="payTypeSwitch()" checked="checked" /></td>
		<td valign="middle" bgcolor="#CCCCCC" align="center">Card</td><td bgcolor="#CCCCCC"><input type="radio" name="switch1" value="4" onclick="payTypeSwitch()" /></td>
		</tr>
		</table>
	</td><td></td></tr>
	<tr><td width="30px" ></td><td colspan="2" width="230px"><table width="100%"><tr><td style="font-size:12pt; color:navy; font-weight:bold; height:40px;">Bank Payment</td><td align="right"> </td></tr></table></td><td width="30px">	</td></tr>
	<tr><td></td><td colspan="2">
		<table width="100%"><tr><td>
			<select name="tr_bank" id="tr_bank">
			<option value="">-SELECT BANK-</option>
			<?php for($i=0;$i<sizeof($ac_bank_id);$i++){
				print '<option value="'.$ac_bank_id[$i].'">'.$ac_bank_name[$i].'</option>';
			}	?>
			</select>
		</td><td><input type="text" name="amount_cash" id="amount_cash" value="0" style="width:60px; text-align:right" onclick="this.value=''" />
		</td></tr></table>
	</td><td></td></tr>
	<tr><td></td><td colspan="2" align="center"><input type="text" name="comment" id="comment" style="width:100%" placeholder="References" /></td><td></td></tr>
	<tr><td height="10px" colspan="4" align="center"><input type="button" value="Add Payment" style="width:100px; height:40px" onclick="setPayment('cash')" /></td></tr>
	<tr><td height="10px" colspan="4"></td></tr>
	</table>
	</div>
	<!-- ----------------------CARD Payment----------------------------------------------- -->
	<div id="cash_bank_card_div4" style="display:none">
	<input type="hidden" name="cash_bank_switch" value="4" />
	<table align="center" bgcolor="<?php print $main_tale_color; ?>" height="100%">
	<tr><td></td><td colspan="2">
		<table width="100%" border="0" cellspacing="0">
		<tr>
		<td valign="middle" bgcolor="#CCCCCC" align="center">Cash</td><td bgcolor="#CCCCCC"><input type="radio" name="switch1" value="1" onclick="payTypeSwitch()" /></td>
		<td valign="middle" bgcolor="#D9D9D9" align="center">Bank</td><td bgcolor="#D9D9D9"><input type="radio" name="switch1" value="3" onclick="payTypeSwitch()" /></td>
		<td valign="middle" bgcolor="#CCCCCC" align="center">Card</td><td bgcolor="#CCCCCC"><input type="radio" name="switch1" value="4" onclick="payTypeSwitch()" checked="checked" /></td>
		</tr>
		</table>
	</td><td></td></tr>
	<tr><td width="30px" ></td><td colspan="2" width="230px"><table width="100%"><tr><td style="font-size:12pt; color:navy; font-weight:bold; height:40px;">Card Payment</td><td align="right"> </td></tr></table></td><td width="30px">	</td></tr>
	<tr><td></td><td colspan="2">
		<table width="100%"><tr><td>
			<select name="card_bank" id="tr_bank">
			<option value="">-SELECT BANK-</option>
			<?php for($i=0;$i<sizeof($ac_bank_id);$i++){
				print '<option value="'.$ac_bank_id[$i].'">'.$ac_bank_name[$i].'</option>';
			}	?>
			</select>
		</td><td><input type="text" name="amount_cash" id="amount_cash" value="0" style="width:60px; text-align:right" onclick="this.value=''" />
		</td></tr></table>
	</td><td></td></tr>
	<tr><td></td><td colspan="2" >Card No <input type="text" name="card_no" id="comment" style="width:200px" placeholder="XXXX XXXX XXXX XXXX" /></td><td></td></tr>
	<tr><td height="10px" colspan="4" align="center"><input type="button" value="Add Payment" style="width:100px; height:40px" onclick="setPayment('cash')" /></td></tr>
	<tr><td height="10px" colspan="4"></td></tr>
	</table>
	</div>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------- -->
<form name="payForm" action="index.php?components=billing&action=add_billpayment&cust_odr=<?php print $_GET['cust_odr']; ?>&s=<?php print $_GET['s']; ?>" onsubmit="return validatePayment(2)" method="post" >
<input type="hidden" id="bm_type" value="<?php print $bm_type; ?>" />
<input type="hidden" id="payment_type" name="payment_type" />
<input type="hidden" id="payment_validity" value="1" />
<input type="hidden" name="salesman" id="salesman" value="<?php print $bill_salesman; ?>" />
<table align="center">
<tr><td><input type="button" value="Back" style="width:100px; height:30px" onclick="window.location = 'index.php?components=billing&action=home&cust_odr=<?php print $cust_odr; ?>&id=<?php print $_GET['id']; ?>&s=<?php print $_GET['s']; ?>&cust=<?php print $bmcust_id; ?>'" /></td><td colspan="4" style="padding-left:100px">
<h1 style="color:orange"><?php if($cust_odr=='no') print 'Bill Payment'; else print 'Advance Payment'; ?></h1>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span><br />'; 
	}
	?>
</td></tr>
<tr><td style="vertical-align:top;">
<!-- ------------------Cash Payment----------------------- -->
	<div id="cash_bank_card_div">
	<table align="center" bgcolor="<?php print $main_tale_color; ?>" height="100%">
	<tr><td></td><td colspan="2">
		<table width="100%" border="0" cellspacing="0">
		<tr>
		<td valign="middle" bgcolor="#CCCCCC" align="center">Cash</td><td bgcolor="#CCCCCC"><input type="radio" name="switch1" value="1" onclick="payTypeSwitch()" checked="checked" /></td>
		<td valign="middle" bgcolor="#D9D9D9" align="center">Bank</td><td bgcolor="#D9D9D9"><input type="radio" name="switch1" value="3" onclick="payTypeSwitch()" /></td>
		<td valign="middle" bgcolor="#CCCCCC" align="center">Card</td><td bgcolor="#CCCCCC"><input type="radio" name="switch1" value="4" onclick="payTypeSwitch()" /></td>
		</tr>
		</table>
	</td><td></td></tr>
	<tr><td width="30px" ></td><td colspan="2" width="230px"><table width="100%"><tr><td style="font-size:12pt; color:navy; font-weight:bold; height:40px;">Cash Payment</td><td align="right">	</td></tr></table></td><td width="30px">
	</td></tr>
	<tr><td></td><td colspan="2"><table><tr><td><input type="text" name="amount_cash" id="amount_cash" value="0" style="width:60px; text-align:right" onclick="this.value=''" /></td><td><input type="button" value="Add Payment" style="width:100px; height:40px" onclick="setPayment('cash')" /></td></tr></table></td><td></td></tr>
	<tr><td height="10px" colspan="4"></td></tr>
	</table>
	</div>
	<br />
	<!-- ------------------Credit Payment----------------------- -->
	<table align="center" bgcolor="#E2DDCC" height="100%" >
	<tr><td width="30px" ></td><td colspan="2" style="font-size:12pt; color:navy; font-weight:bold; height:40px; width:230px">Credit Payment</td><td width="30px"></td></tr>
	<tr><td></td><td colspan="2"><input type="text" name="amount_credit" id="amount_credit" value="0" style="width:80px; text-align:right" onclick="this.value=''" /> <input type="button" value="Add Payment" style="width:100px; height:40px" onclick="setPayment('credit')" /></td><td></td></tr>
	<tr><td height="10px" colspan="4"></td></tr>
	</table>

<!-- ------------------Chque Payment----------------------- -->
</td><td width="10px"></td><td style="vertical-align:top;">
	<table align="center" bgcolor="<?php print $main_tale_color; ?>">
	<tr><td width="30px"></td><td colspan="4" style="font-size:12pt; color:navy; font-weight:bold;" colspan="2"  ><br />Chque Payment<br /><br /></td></tr>
	<tr><td></td><td style="font-size:12pt">Amount</td><td><input type="text" name="amount_chque" id="amount_chque" value="0" onclick="this.value=''" /></td><td></td><td width="30px"></td></tr>
	<tr><td></td><td style="font-size:12pt"></td><td>Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bank &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Branch</td><td></td><td></td><td></td></tr>
	<tr><td></td><td style="font-size:12pt">Chque </td><td><input type="number" name="chque_no" id="chque_no" style="width:60px" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="chque_bank" id="tags4" style="width:35px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="chque_branch" id="chque_branch" style="width:35px" onfocus="validateBank()" /></td><td></td><td></td></tr>
	<tr><td></td><td style="font-size:12pt" height="20px"></td><td><div style="font-size:12pt" id="bk_name" align="right"></div></td><td><div style="font-size:12pt" id="av_qty" align="right"></div></td><td></td></tr>
	<tr><td></td><td style="font-size:12pt">Chque Date</td><td><input type="date" name="chque_date" id="chque_date" /></td><td><div style="font-size:12pt" id="av_qty" align="right"></div></td><td></td></tr>
	<tr><td></td><td></td><td colspan="3" height="10px"><input type="button" value="Add Payment" style="width:100px; height:35px" onclick="setPayment('chque')" /></td></tr>
	<tr><td colspan="5" height="10px"></td></tr>
	</table>
<br />
<div id="finalize"><input type="submit" value="Finalize" style="width:100%; height:60px; background-color:orange" /></div>
</td><td width="10px"></td><td style="vertical-align:top">
 <!-- -----------------------Bill Amount & Balance ---------------------------------->
 <table>
 <tr><td class="sidetable1" >Customer</td><td width="70px" align="right" class="sidetable2">
 	<?php
			print '<span style="font-size:12pt">'.$bmcust_name.'</span>';
			print '<input type="hidden" name="cust" id="cust" value="'.$bmcust_id.'" />';
	?>
 </td></tr>
 <tr><td class="sidetable1">Invoice No</td><td width="70px" align="right" class="sidetable2"><?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?><input type="hidden" name="invoice_no" value="<?php print $_GET['id']; ?>" /></td></tr>
 <tr><td colspan="2" height="30px" ></td></tr>
 <tr><td class="sidetable1">Bill Amount</td><td width="70px" align="right" class="sidetable2"><?php print number_format($invoiceTotal,2); ?><input type="hidden" id="invoicetotal" value="<?php print $invoiceTotal; ?>" /></td></tr>
 <tr><td class="sidetable1"><div id="py_name1">Cash Payment</div></td><td align="right" class="sidetable2"><div id="payment_cash_div" /></td></tr>
 <tr><td class="sidetable1">Chque Payment</td><td align="right" class="sidetable2"><div id="payment_chque_div" /></td></tr>
 <tr><td class="sidetable1">Credit Payment</td><td align="right" class="sidetable2"><div id="payment_credit_div" /></td></tr>
 <tr><td class="sidetable1">Balance</td><td align="right" class="sidetable2"><div id="payment_balance_div" ><?php print number_format($invoiceTotal,2); ?></div><input type="hidden" id="balance" value="<?php print $invoiceTotal; ?>" /></td></tr>
 <tr><td colspan="2" height="30px" ></td></tr>
 <tr><td class="sidetable1" title="Calculation of Remaining Credit Limit&#13;Customer Credit Limit - Invoice Total + Cash Payments + Deposited Cheque Payments" >Remaining of<br />Credit Limit</td><td align="right" class="sidetable2"><div id="cr_balance_div" ><?php print number_format($remaining_cr_limit); ?></div><input type="hidden" id="crlimitbalance" value="<?php print $remaining_cr_limit; ?>" /></td></tr>
 </table>
</td></tr>
</table>
<input type="hidden" id="crlimitbalance2" value="" />
</form>

<?php
                include_once  'template/footer.php';
?>