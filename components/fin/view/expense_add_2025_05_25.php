<?php
    include_once  'template/header.php';
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>

<!-- Scripts -->
<script type="text/javascript">


	function validateExpense(){
		var $count1=$count2=0;
		$out=true;
		var expenses = [<?php for ($x=0;$x<sizeof($ac_name);$x++){ print '"'.$ac_name[$x].'",'; } ?>	];
		for($i=1;$i<=10;$i++){
			var exp=document.getElementById('exp'+$i).value;
			var amo=document.getElementById('amo'+$i).value;
			if(exp==''){ $count1++; }else{
				if(expenses.indexOf(exp)==-1){ $count1++; }else{
					if(amo==''){ $count1++; }else{
						if(isNaN(amo)) $count1++;
					}
				}
			}
		}
		if($count1==10){ $out=false; $msg='Invalid or No Expense was Selected'; }
		
	    if(document.getElementById('date').value=='') $count2++;
	    if(document.getElementById('store').value=='') $count2++;
	    if(document.getElementById('tags1').value=='') $count2++;
	    if(document.getElementById('from_account').value=='') $count2++;
	    if(document.getElementById('method').value=='') $count2++;
		if($count2>0){ $out=false; $msg='Store, Payee, Account, Date, Method Must be filled'; }
		
		if($out){
			return true;
		}else{
			window.alert($msg);
			return false;
		}
	}
	function getBalance($account){
		$balance = document.getElementById("balance");
		if($account != ''){
			$balance.innerHTML=document.getElementById('loading').innerHTML;
			var xmlhttp = new XMLHttpRequest();
	  		xmlhttp.onreadystatechange = function() {
		    	if (this.readyState == 4 && this.status == 200) {
		    		var returntext=this.responseText;
					if(returntext!=''){
						$balance.innerHTML=returntext;
					}else{
						$balance.innerHTML=""
					}
		    	}
	  		};
			$currentDate = new Date(+new Date().setHours(0, 0, 0,0)+ 86400000).toLocaleDateString('fr-CA');
			xmlhttp.open("GET", 'index.php?components=<?php print $_GET['components']; ?>&action=account_balance&method=ajax&from_date='+$currentDate+'&to_date='+$currentDate+'&id='+$account, true);
			xmlhttp.send();
		}else{
			$balance.innerHTML=""
		}
	}

	function getPayee(){
		var payee_cust = [<?php for ($x=0;$x<sizeof($cu_name);$x++){ print '"'.$cu_name[$x].'",'; } ?>	];
		var payee_sup = [<?php for ($x=0;$x<sizeof($su_name);$x++){ print '"'.$su_name[$x].'",'; } ?>	];
		var payee_emp = [<?php for ($x=0;$x<sizeof($up_name);$x++){ print '"'.$up_name[$x].'",'; } ?>	];
		var payee_other = [<?php for ($x=0;$x<sizeof($py_name);$x++){ print '"'.$py_name[$x].'",'; } ?>	];
		var payee_type=document.getElementById('payee_type').value;
		if(payee_type=='customer') var availableTags1=payee_cust;
		if(payee_type=='supplier') var availableTags1=payee_sup;
		if(payee_type=='employee') var availableTags1=payee_emp;
		if(payee_type=='other') var availableTags1=payee_other;
		if(payee_type==''){
			document.getElementById('payee_div').innerHTML='<input type="hidden" id="tags1" />';
		}else{
			document.getElementById('payee_div').innerHTML='<input type="text" name="payee" id="tags1" style="width:140px" />';
		}
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
		var availableTags2 = [<?php for ($x=0;$x<sizeof($ac_name);$x++){ print '"'.$ac_name[$x].'",'; } ?>	];
		$( "#exp1" ).autocomplete({	source: availableTags2 });
		$( "#exp2" ).autocomplete({	source: availableTags2 });
		$( "#exp3" ).autocomplete({	source: availableTags2 });
		$( "#exp4" ).autocomplete({	source: availableTags2 });
		$( "#exp5" ).autocomplete({	source: availableTags2 });
		$( "#exp6" ).autocomplete({	source: availableTags2 });
		$( "#exp7" ).autocomplete({	source: availableTags2 });
		$( "#exp8" ).autocomplete({	source: availableTags2 });
		$( "#exp9" ).autocomplete({	source: availableTags2 });
		$( "#exp10" ).autocomplete({	source: availableTags2 });
	}

</script>
<!--// Scripts -->

<!-- Notifications -->
<table align="center" style="font-size:12pt">
	<tr>
		<td>
			<?php 
				if(isset($_REQUEST['message'])){
					if($_REQUEST['re']=='success') $color='green'; else $color='red';
				print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
				}
			?>
	</td>
</tr>
</table>
<!--// Notifications -->

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:30px;"/></div>
<form action="index.php?components=<?php print $components; ?>&action=add_expense" method="post" onsubmit="return validateExpense()">
	<table align="center" border="0"  style="font-size:12pt" bgcolor="#EEEEEE">
		<tr><td style="padding:10px 10px 10px 10px"><table>
			<tr><td>
			<strong>Date &nbsp;: </strong></td><td><input type="date" name="date" id="date" value="<?php print dateNow(); ?>" style="width:140px" />
			</td></tr>
			<tr><td>
			<strong>Store : </strong></td><td>
			<select name="store" id="store" style="width:140px" <?php if($components=='accounts') print 'disabled' ?>>
			<option value="">-SELECT-</option>
			<?php for($i=0;$i<sizeof($st_id);$i++){
				if($components == 'accounts'){
					$store = $_COOKIE['store'];
					if($st_id[$i]==$store){
						print '<option value="'.$st_id[$i].'" selected="selected" disabled>'.$st_name[$i].'</option>';
					}
				}else{
					print '<option value="'.$st_id[$i].'">'.$st_name[$i].'</option>';
				}
			} ?>
			</select>
			</td></tr>
			<tr><td>
			<strong>Ref No &nbsp;&nbsp;: </strong></td><td><input type="text" name="ref" id="ref" style="width:140px" />
			</td></tr>
		</table></td><td width="40px"></td><td><table>
			<tr><td>
			<strong>Payee Type : </strong></td><td>
			<select name="payee_type" id="payee_type" style="width:140px" onchange="getPayee()">
			<option value="">-SELECT-</option>
				<option value="customer">Customer</option>
				<option value="supplier">Supplier</option>
				<option value="employee">Employee</option>
				<option value="other">Other</option>
			</select>
			</td></tr>
			<tr><td>
			<strong>Payee &nbsp;&nbsp;: </strong></td><td><div id="payee_div"><input type="hidden" id="tags1" /></div>
			</td></tr>
			<tr><td><br /></td></tr>
		</table></td><td width="40px"></td>
		<td>
			<table>
				<tr><td>
				<strong>From Account :</strong></td><td>
				<select name="from_account" id="from_account" style="width:140px" onchange="getBalance(this.value);">
				<option value="">-SELECT-</option>
				<?php for($i=0;$i<sizeof($fromac_id);$i++){
					print '<option value="'.$fromac_id[$i].'">'.$fromac_name[$i].'</option>';
				} ?>
				</select>
				</td></tr>
				<tr>
					<td>
						<strong><a title="Payment Method">Method : <a/></strong>
					</td>
					<td>
						<select name="method" id="method" style="width:140px">
						<option value="">-SELECT-</option>
						<?php for($i=0;$i<sizeof($method_id);$i++){
							print '<option value="'.$method_id[$i].'">'.$method_name[$i].'</option>';
						} ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<strong><a title="Account Balance">Account Balance : <a/></strong>
					</td>
					<td>
						<div id="balance" style="text-align:right"></div>
					</td>
				</tr>
			</table>
		</td>
		<td style="padding-left:10px; padding-right:10px;">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="List Of Expenses" style="width:150px; height:50px" onclick="window.location = 'index.php?components=<?php print $components; ?>&action=list_expense&year=<?php print date("Y",time()); ?>'" />
		</td></tr>
	</table>
	<br /><br />
	<table align="center" border="0">
		<tr  bgcolor="#CCCCEE" style="font-size:12pt; color:navy; font-weight:bold"><td></td><td>&nbsp;&nbsp;Account</td><td>&nbsp;&nbsp;Description</td><td align="center">Amount</td></tr>
		<tr bgcolor="#EEEEEE"><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="exp1" id="exp1" />&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="des1" id="des1" style="width:350px" />&nbsp;&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;&nbsp;<input type="text" name="amo1" id="amo1" style="width:50px; text-align:right" />&nbsp;&nbsp;&nbsp;</td></tr>
		<tr bgcolor="#DDDDDD"><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="exp2" id="exp2" />&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="des2" id="des2" style="width:350px" />&nbsp;&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;&nbsp;<input type="text" name="amo2" id="amo2" style="width:50px; text-align:right" />&nbsp;&nbsp;&nbsp;</td></tr>
		<tr bgcolor="#EEEEEE"><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="exp3" id="exp3" />&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="des3" id="des3" style="width:350px" />&nbsp;&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;&nbsp;<input type="text" name="amo3" id="amo3" style="width:50px; text-align:right" />&nbsp;&nbsp;&nbsp;</td></tr>
		<tr bgcolor="#DDDDDD"><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="exp4" id="exp4" />&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="des4" id="des4" style="width:350px" />&nbsp;&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;&nbsp;<input type="text" name="amo4" id="amo4" style="width:50px; text-align:right" />&nbsp;&nbsp;&nbsp;</td></tr>
		<tr bgcolor="#EEEEEE"><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="exp5" id="exp5" />&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="des5" id="des5" style="width:350px" />&nbsp;&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;&nbsp;<input type="text" name="amo5" id="amo5" style="width:50px; text-align:right" />&nbsp;&nbsp;&nbsp;</td></tr>
		<tr bgcolor="#DDDDDD"><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="exp6" id="exp6" />&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="des6" id="des6" style="width:350px" />&nbsp;&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;&nbsp;<input type="text" name="amo6" id="amo6" style="width:50px; text-align:right" />&nbsp;&nbsp;&nbsp;</td></tr>
		<tr bgcolor="#EEEEEE"><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="exp7" id="exp7" />&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="des7" id="des7" style="width:350px" />&nbsp;&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;&nbsp;<input type="text" name="amo7" id="amo7" style="width:50px; text-align:right" />&nbsp;&nbsp;&nbsp;</td></tr>
		<tr bgcolor="#DDDDDD"><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="exp8" id="exp8" />&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="des8" id="des8" style="width:350px" />&nbsp;&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;&nbsp;<input type="text" name="amo8" id="amo8" style="width:50px; text-align:right" />&nbsp;&nbsp;&nbsp;</td></tr>
		<tr bgcolor="#EEEEEE"><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="exp9" id="exp9" />&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="des9" id="des9" style="width:350px" />&nbsp;&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;&nbsp;<input type="text" name="amo9" id="amo9" style="width:50px; text-align:right" />&nbsp;&nbsp;&nbsp;</td></tr>
		<tr bgcolor="#DDDDDD"><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;10&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="exp10" id="exp10" />&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" name="des10" id="des10" style="width:350px" />&nbsp;&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;&nbsp;<input type="text" name="amo10" id="amo10" style="width:50px; text-align:right" />&nbsp;&nbsp;&nbsp;</td></tr>
		<tr bgcolor="#EEEEEE"><td colspan="4" align="center"><textarea placeholder="Memo" name="memo" style="width:90%"></textarea></td></tr>
		<tr bgcolor="#EEEEEE"><td colspan="4" align="center"><input type="submit" value="Submit" style="width:100px; height:50px" /></td></tr>
	</table>
</form>
	
<?php
    include_once  'template/footer.php';
?>