<?php
                include_once  'template/m_header.php';
                $bill_salesman=$_COOKIE['user_id'];
                if(isset($_GET['s'])){ if($_GET['s']!='')  $bill_salesman=$_GET['s']; }
                $cust_odr=$_GET['cust_odr'];
                if($cust_odr=='yes') $main_tale_color='#FFDDCC'; else $main_tale_color='#EEEEEE';
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
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
	</script>
<!-- ------------------------------------------------------------------------------------ -->
<form name="payForm" action="index.php?components=billing&action=add_billpayment&cust_odr=<?php print $_GET['cust_odr']; ?>&s=<?php print $_GET['s']; ?>" onsubmit="return validatePayment(2)" method="post" >
<input type="hidden" id="bm_type" value="<?php print $bm_type; ?>" />
<input type="hidden" id="payment_type" name="payment_type" />
<input type="hidden" id="payment_validity" value="1" />
<input type="hidden" name="salesman" id="salesman" value="<?php print $bill_salesman; ?>" />
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
  <div class="w3-col">
	<table align="center"><tr><td style="vertical-align:top;">
		<div id="landscape" style="vertical-align:top" ></div>
	</td><td width="10px"></td><td style="vertical-align:top;">
			 <!-- -----------------------Bill Amount & Balance ---------------------------------->
			 <table align="center" style="font-size:medium" width="100%">
			 <tr><td class="sidetable1" width="40%" >Customer</td><td  width="60%" align="right" class="sidetable2">
			 	<?php
						print '<span style="font-size:12pt">'.$bmcust_name.'</span>';
						print '<input type="hidden" name="cust" id="cust" value="'.$bmcust_id.'" />';
				?>
			 </td></tr>
			 <tr><td class="sidetable1">Invoice No</td><td align="right" class="sidetable2"><?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?><input type="hidden" name="invoice_no" value="<?php print $_GET['id']; ?>" /></td></tr>
			 <tr><td colspan="2" height="30px" ></td></tr>
			 <tr><td class="sidetable1">Bill Amount</td><td align="right" class="sidetable2"><?php print number_format($invoiceTotal,2); ?><input type="hidden" id="invoicetotal" value="<?php print $invoiceTotal; ?>" /></td></tr>
			 <tr><td class="sidetable1">Cash</td><td align="right" class="sidetable2"><div id="payment_cash_div" /></td></tr>
			 <tr><td class="sidetable1">Chque</td><td align="right" class="sidetable2"><div id="payment_chque_div" /></td></tr>
			 <tr><td class="sidetable1">Credit</td><td align="right" class="sidetable2"><div id="payment_credit_div" /></td></tr>
			 <tr><td class="sidetable1">Balance</td><td align="right" class="sidetable2"><div id="payment_balance_div" ><?php print number_format($invoiceTotal,2); ?></div><input type="hidden" id="balance" value="<?php print $invoiceTotal; ?>" /></td></tr>
			 <tr><td colspan="2" height="30px" ></td></tr>
			 <tr><td class="sidetable1">Remaining of<br />Credit Limit</td><td align="right" class="sidetable2"><div id="cr_balance_div" ><?php print number_format($remaining_cr_limit,2); ?></div><input type="hidden" id="crlimitbalance" value="<?php print $remaining_cr_limit; ?>" /></td></tr>
			 </table>
	</td></tr></table>
	<input type="hidden" id="crlimitbalance2" value="" />
  </div>
</div>
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col " align="center">
  <div id="portrait">
		 <!-- -----------------------Add Payment ---------------------------------->
	  <table align="center" bgcolor="<?php print $main_tale_color; ?>" width="100%" style="font-size:large">
	  	<tr><td colspan="3" style="font-size:large; color:navy; font-weight:bold;" align="center">Add Payment</td></tr>
	  	<tr><td height="30px" colspan="3"></td></tr>
	  	<tr><td>&nbsp;&nbsp;Cash</td><td width="20px"></td><td><input type="text" name="amount_cash" id="amount_cash" value="0" style="width:100px; text-align:right; padding-right:10px" onclick="this.value=''" /><input type="button" value="Add" style="width:47px; height:33px; color:navy" onclick="setPayment('cash')" /></td></tr>
	  	<tr><td height="10px" colspan="3"></td></tr>
	  	<tr bgcolor="#E2DDCC"><td>&nbsp;&nbsp;Credit</td><td></td><td><input type="text" name="amount_credit" id="amount_credit" value="0" style="width:100px; text-align:right; padding-right:10px" onclick="this.value=''" /><input type="button" value="Add" style="width:47px; height:33px; color:navy" onclick="setPayment('credit')" /></td></tr>
	  	<tr><td height="10px" colspan="3"></td></tr>
	  	<tr><td>&nbsp;&nbsp;Chque</td><td colspan="2">
			<table bgcolor="<?php print $main_tale_color; ?>" cellspacing="0" border="0">
			<tr><td style="font-size:medium" width="20px"></td><td><input type="text" name="amount_chque" id="amount_chque" value="0" style="width:100px; text-align:right; padding-right:10px" onclick="this.value=''" />  Rs</td><td></td><td width="10px"></td></tr>
			<tr><td style="font-size:medium"></td><td><input type="number" name="chque_no" id="chque_no" value="" style="font-size:large; width:80px" placeholder="Code" />
			  <input type="number" name="chque_bank" id="tags4" value="" style="font-size:large; width:55px" placeholder="Bank" />
			  <input type="number" name="chque_branch" id="chque_branch" value="" onfocus="validateBank()" style="font-size:large; width:45px" placeholder="Brn" />
			</td><td></td><td></td></tr>
			<tr><td style="font-size:medium" height="5px"></td><td><div style="font-size:12pt" id="bk_name" align="right"></div></td><td><div style="font-size:12pt" id="av_qty" align="right"></div></td><td></td></tr>
			<tr><td style="font-size:medium"></td><td><input type="date" name="chque_date" id="chque_date" value="" style="font-size:large; width:120px" placeholder="Chque Date" /><input type="button" value="Add" style="width:47px; height:33px; color:navy" onclick="setPayment('chque')" /></td><td></td><td></td></tr>
			<tr><td></td><td colspan="3" height="10px"></td></tr>
			<tr><td colspan="4" height="4px"></td></tr>
			</table>
	  	</td></tr>
	  	<tr><td height="4px" colspan="3"></td></tr>
	  	<tr><td align="center" colspan="3"><div id="finalize"><input type="submit" value="Finalzse" style="width:95%; height:60px; background-color:maroon; color:white" /></div></td></tr>
	  	<tr><td height="30px" colspan="3"></td></tr>
	  </table>
	</div>
  </div>
</div>
<hr>
</div>
</form>

<?php
                include_once  'template/m_footer.php';
?>