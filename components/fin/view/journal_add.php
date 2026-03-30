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
		$( "#exp10" ).autocomplete({ source: availableTags2 });
	});


	function getPayee($id){
		var payee_cust = [<?php for ($x=0;$x<sizeof($cu_name);$x++){ print '"'.$cu_name[$x].'",'; } ?>	];
		var payee_sup = [<?php for ($x=0;$x<sizeof($su_name);$x++){ print '"'.$su_name[$x].'",'; } ?>	];
		var payee_emp = [<?php for ($x=0;$x<sizeof($up_name);$x++){ print '"'.$up_name[$x].'",'; } ?>	];
		var payee_other = [<?php for ($x=0;$x<sizeof($py_name);$x++){ print '"'.$py_name[$x].'",'; } ?>	];
		var payee_type=document.getElementById('payee_type'+$id).value;
		if(payee_type=='customer') var availableTags1=payee_cust;
		if(payee_type=='supplier') var availableTags1=payee_sup;
		if(payee_type=='employee') var availableTags1=payee_emp;
		// if(payee_type=='other') var availableTags1=payee_other;
		if(payee_type==''){
			document.getElementById('payee_div'+$id).innerHTML='<input type="hidden" id="payee'+$id+'" name="payee'+$id+'" />';
		}else{
			document.getElementById('payee_div'+$id).innerHTML='<input type="text" name="payee'+$id+'" id="payee'+$id+'" style="width:140px" />';
		}
		$( "#payee"+$id ).autocomplete({
			source: availableTags1
		});
	}

	function validateJournal(){
		var $count1=$count2=0;
		$out=true;
		$msg='Invalid Data Type or No Journal was Selected';
		var journals = [<?php for ($x=0;$x<sizeof($ac_name);$x++){ print '"'.$ac_name[$x].'",'; } ?>	];
		for($i=1;$i<=10;$i++){
			var exp=document.getElementById('exp'+$i).value;
			var cr=document.getElementById('cr'+$i).value;
			var dr=document.getElementById('dr'+$i).value;
			if((cr!='')&&(dr!='')){ $count1++; $msg='Bothe Credit and Debits cannot be Filled for one Recode'; }
			if(exp==''){ $count1++; }else{
				if(journals.indexOf(exp)==-1){ $count1++; }else{
					if((cr=='')&&(dr=='')){ $count1++; }else{
						if(cr!=''){ if(isNaN(cr)) $count1++; }
						if(dr!=''){ if(isNaN(dr)) $count1++; }
					}
				}
			}
		}
		if($count1==10){ $out=false;  }

	    if(document.getElementById('date').value=='') $count2++;
	    if(document.getElementById('store').value=='') $count2++;
		if($count2>0){ $out=false; $msg='Date and Store Must be filled'; }

		if($out){
			return true;
		}else{
			window.alert($msg);
			return false;
		}
	}
	</script>
<!-- ------------------------------------------------------------------------------------------------------------------ -->

<table align="center" style="font-size:12pt"><tr><td>
<?php
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>';
	}
?></td></tr></table>
<form action="index.php?components=fin&action=add_journal" method="post" onsubmit="return validateJournal()">
	<table align="center" border="0"  style="font-size:12pt" bgcolor="#EEEEEE">
	<tr><td width="30px"></td><td><strong>Date &nbsp;: </strong></td><td><input type="date" name="date" id="date" value="<?php print dateNow(); ?>" style="width:140px" />
		</td><td width="50px"></td><td>
		<strong>Store : </strong></td><td>
		<select name="store" id="store" style="width:140px">
		<option value="">-SELECT-</option>
		<?php for($i=0;$i<sizeof($st_id);$i++){
			print '<option value="'.$st_id[$i].'">'.$st_name[$i].'</option>';
		} ?>
		</select>
		</td><td width="50px"></td><td>
		<strong>Ref No &nbsp;&nbsp;: </strong></td><td><input type="text" name="ref" id="ref" style="width:140px" />
		</td><td width="30px"></td><td>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="List Of Journals" style="width:150px; height:50px" onclick="window.location = 'index.php?components=fin&action=list_journal&year=<?php print date("Y",time()); ?>'" />
	</td></tr>
	</table>
	<br /><br />
	<table align="center" border="0">
	<tr  bgcolor="#CCCCEE" style="font-size:12pt; color:navy; font-weight:bold"><td></td><td>&nbsp;&nbsp;Account</td><td align="center">Debits</td><td align="center">Credits</td><td>&nbsp;&nbsp;Description</td><td align="center">Type</td><td align="center">Name</td></tr>
	<?php for($i=1;$i<=10;$i++){
		if(($i%2)==0) $row_color='#EEEEEE'; else $row_color='#DDDDDD';
		print '<tr bgcolor="'.$row_color.'"><td align="right" class="shipmentTB4">'.$i.'</td><td class="shipmentTB3"><input type="text" name="exp'.$i.'" id="exp'.$i.'" /></td><td align="center" class="shipmentTB3"><input type="text" name="dr'.$i.'" id="dr'.$i.'" style="width:50px; text-align:right" /></td><td align="center" class="shipmentTB3"><input type="text" name="cr'.$i.'" id="cr'.$i.'" style="width:50px; text-align:right" /></td><td class="shipmentTB3"><input type="text" name="des'.$i.'" id="des'.$i.'" style="width:350px" /></td><td align="center" class="shipmentTB3">';
		print '<select name="payee_type'.$i.'" id="payee_type'.$i.'" style="width:80px" onchange="getPayee('."'$i'".')">
		<option value="">-SELECT-</option>
			<option value="customer">Customer</option>
			<option value="supplier">Supplier</option>
			<option value="employee">Employee</option>
		</select>';
		print '</td><td align="center" class="shipmentTB3"><div id="payee_div'.$i.'"><input type="hidden" id="payee'.$i.'" name="payee'.$i.'" /></div></td></tr>';
	} ?>
	<tr bgcolor="#EEEEEE"><td colspan="7" align="center"><textarea placeholder="Memo" name="memo" style="width:90%"></textarea></td></tr>
	<tr bgcolor="#EEEEEE"><td colspan="7" align="center"><input type="submit" value="Submit" style="width:100px; height:50px" /></td></tr>
	</table>
</form>


<?php
                include_once  'template/footer.php';
?>