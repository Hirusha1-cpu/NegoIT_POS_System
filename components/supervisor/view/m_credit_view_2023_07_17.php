<?php
    include_once  'template/m_header.php';
    $store_report=$group_report='ALL';
    $display_cr=$_GET['display'];
?>
<!-- ------------------------------------------------------------------------------------ -->
<script type="text/javascript">

function showHideCols(col_no){

	var rows = document.getElementById('credittable').rows;
	//el2 = document.getElementById(el);
	//console.log(el2);
	if(document.getElementById('f'+col_no).checked){
		for (var row = 0; row < rows.length; row++) {
	        var cols = rows[row].cells;
	        if (col_no >= 0 && col_no < cols.length) {
				// return;
	            cols[col_no].style.display = '';
	        }
			
	    }
	}else{
	    for (var row = 0; row < rows.length; row++) {
	        var cols = rows[row].cells;
	        if (col_no >= 0 && col_no < cols.length) {
				// return;
	            cols[col_no].style.display = 'none';
	        }
			
	    }
	}
}

function filter(){
	$store =  document.getElementById('store').value;
	$group = document.getElementById('group').value;
	$town = document.getElementById('town').value;
	$salesman = document.getElementById('salesman').value;
	$display = document.getElementById('display').value;
	$sub_system = document.getElementById('sub_system').value;
	$as_of = document.getElementById('as_of').value;
	
	window.location = 'index.php?components=<?php print $components; ?>&action=credit&st='+$store+'&tw='+$town+'&gp='+$group+'&up='+$salesman+'&display='+$display+'&sub_system='+$sub_system+'&as_of='+$as_of;

	// subsystem
	// window.location = 'index.php?components=<?php print $components; ?>&action=credit&st='+document.getElementById('store').value+'&gp='+document.getElementById('group').value+'&up='+document.getElementById('salesman').value+'&display='+document.getElementById('display').value+'&sub_system='+document.getElementById('sub_system').value+'&as_of='+document.getElementById('as_of').value

	// store 
	// window.location = 'index.php?components=<?php print $components; ?>&action=credit&st='+this.value+'&gp='+document.getElementById('group').value+'&up='+document.getElementById('salesman').value+'&display='+document.getElementById('display').value

	//group 
	// window.location = 'index.php?components=<?php print $components; ?>&action=credit&gp='+this.value+'&st='+document.getElementById('store').value+'&up='+document.getElementById('salesman').value+'&display='+document.getElementById('display').value"

	//salesman
	// window.location = 'index.php?components=<?php print $components; ?>&action=credit&gp='+document.getElementById('group').value+'&st='+document.getElementById('store').value+'&up='+this.value+'&display='+document.getElementById('display').value

	//display 
	// window.location = 'index.php?components=<?php print $components; ?>&action=credit&st='+document.getElementById('store').value+'&gp='+document.getElementById('group').value+'&up='+document.getElementById('salesman').value+'&display='+document.getElementById('display').value

	//as of 
	// window.location = 'index.php?components=<?php print $components; ?>&action=credit&st='+document.getElementById('store').value+'&gp='+document.getElementById('group').value+'&up='+document.getElementById('salesman').value+'&display='+document.getElementById('display').value+'&sub_system='+document.getElementById('sub_system').value+'&as_of='+document.getElementById('as_of').value
}
// function showHideCols(do_show, col_no, el){

// 	var rows = document.getElementById('credittable').rows;

// 	if(document.getElementById(el).checked){
// 	    for (var row = 0; row < rows.length; row++) {
// 	        var cols = rows[row].cells;
// 	        if (col_no >= 0 && col_no < cols.length) {
// 				// return;
// 	            cols[col_no].style.display = 'none';
// 	        }
			
// 	    }
// 	}else{
// 		for (var row = 0; row < rows.length; row++) {
// 	        var cols = rows[row].cells;
// 	        if (col_no >= 0 && col_no < cols.length) {
// 				// return;
// 	            cols[col_no].style.display = '';
// 	        }
			
// 	    }
// 	}
// }

</script>

<style type="text/css">
		/* table#credittable td:nth-child(3) {
		   display: none;
		}
		table#credittable th:nth-child(3) {
		   display: none;
		}
		table#credittable td:nth-child(4) {
		   display: none;
		}
		table#credittable th:nth-child(4) {
		   display: none;
		}		 */

</style>

<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
<form action="index.php?components=<?php print $components; ?>&action=credit" method="post" >
	<table width="100%" height="100%" cellspacing="0" border="0" style="font-size:10pt; border-radius:10px" bgcolor="#EFEFEF">
	<tr><td height="15px" colspan="2"></td></tr>
	
	<?php if($components=='topmanager' || $components=='report'){ ?>
	<tr><td class="shipmentTB4"><strong>Sub System</strong>&nbsp;&nbsp;&nbsp;</td><td>
			<select id="sub_system" onchange="filter();">
			<option value="all" >-ALL-</option>
			<?php for($i=0;$i<sizeof($sub_system_list);$i++){
				if(isset($_GET['sub_system'])){
					if($_GET['sub_system']==$sub_system_list[$i]){
						$select='selected="selected"';
						$subsys_report=$sub_system_names[$i];
					}else $select='';
				}else $select='';
				print '<option value="'.$sub_system_list[$i].'" '.$select.'>'.$sub_system_names[$i].'</option>';
			}
			?>
		</select>
	</td></tr>
	<?php }else{ print '<input type="hidden" id="sub_system" value="'.$sub_system.'" />'; } ?>
	
	<tr><td class="shipmentTB4"><strong>Shop/Store</strong>&nbsp;&nbsp;&nbsp;</td><td>
			<select id="store" onchange="filter();">
			<option value="" >-ALL-</option>
			<?php for($i=0;$i<sizeof($store_id);$i++){
				if(isset($_GET['st'])){
					if($_GET['st']==$store_id[$i]){
						$select='selected="selected"';
						$store_report=$store_name[$i];
					}else $select='';
				}else $select='';
				print '<option value="'.$store_id[$i].'" '.$select.'>'.$store_name[$i].'</option>';
			}
			?>
		</select>
	</td></tr>
	<tr><td class="shipmentTB4"><strong>Town</strong>&nbsp;&nbsp;&nbsp;</td><td>
			<select id="town" onchange="filter();">
			<option value="" >-ALL-</option>
			<?php for($i=0;$i<sizeof($town_id);$i++){
				if(isset($_GET['tw'])){
					if($_GET['tw']==$town_id[$i]){
						$select='selected="selected"';
						$group_report=$town_name[$i];
					}else $select='';
				}else $select='';
				print '<option value="'.$town_id[$i].'" '.$select.'>'.$town_name[$i].'</option>';
			}
			?>
		</select>
	</td></tr>	
	<tr><td class="shipmentTB4"><strong>Group</strong>&nbsp;&nbsp;&nbsp;</td><td>
			<select id="group" onchange="filter();">
			<option value="" >-ALL-</option>
			<?php for($i=0;$i<sizeof($gp_id);$i++){
				if(isset($_GET['gp'])){
					if($_GET['gp']==$gp_id[$i]){
						$select='selected="selected"';
						$group_report=$gp_name[$i];
					}else $select='';
				}else $select='';
				print '<option value="'.$gp_id[$i].'" '.$select.'>'.$gp_name[$i].'</option>';
			}
			?>
		</select>
	</td></tr>
	<?php if($_GET['components']=='report' || $_GET['components']=='supervisor'){ ?>
	<tr><td class="shipmentTB4"><strong title="Associated Salesman">Salesman</strong>&nbsp;&nbsp;&nbsp;</td><td>
			<select id="salesman" onchange="filter();">
			<option value="" >-ALL-</option>
			<?php for($i=0;$i<sizeof($up_id);$i++){
				if(isset($_GET['up'])){
					if($_GET['up']==$up_id[$i]){
						$select='selected="selected"';
						$salesman_report=$up_name[$i];
					}else $select='';
				}else $select='';
				print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
			}
			?>
		</select>
	</td></tr>
	<?php }else{ ?>
		<input type="hidden" id="salesman" />
	<?php } ?>
	<tr><td class="shipmentTB4"><strong>Display</strong>&nbsp;&nbsp;&nbsp;</td><td>
			<select id="display" onchange="filter();">
			<option value="1" <?php if($display_cr==1) print 'selected="selected"'; ?> >-ALL-</option>
			<option value="2" <?php if($display_cr==2) print 'selected="selected"'; ?> >Outstanding Cust</option>
		</select>
	</td></tr>
	<tr><td class="shipmentTB4"><strong>As of</strong>&nbsp;&nbsp;&nbsp;</td><td>
	<input type="date" name="as_of" id="as_of" value="<?php print $as_of; ?>" onchange="filter()"/></td></tr>
	<tr><td colspan="2"  class="shipmentTB4"><br>Note: This report shows the amount of Credit per Customer</td></tr>
	<tr><td height="15px" colspan="2"></td></tr>
	</table>
</form>
<hr />
<br/>
	<div id="fliter">
		<table align="center">
		<tr><td class="shipmentTB3" bgcolor="#E5E5E5"><strong>Up to</strong></td>
		<td class="shipmentTB3" bgcolor="#F5F5F5">90+ <input type="checkbox" id="f1" onclick="showHideCols(1)" /></td>
		<td class="shipmentTB3" bgcolor="#E5E5E5">60+ <input type="checkbox" id="f2" onclick="showHideCols(2)" /></td>
		<td class="shipmentTB3" bgcolor="#F5F5F5">45+ <input type="checkbox" id="f3" onclick="showHideCols(3)" /></td>
		<td class="shipmentTB3" bgcolor="#E5E5E5">30+ <input type="checkbox" id="f4" onclick="showHideCols(4)"></td>
		<td class="shipmentTB3" bgcolor="#F5F5F5">14+ <input type="checkbox" id="f5" checked="checked" onclick="showHideCols(5)"></td>
		<td class="shipmentTB3" bgcolor="#E5E5E5">7+ <input type="checkbox" id="f6" checked="checked" onclick="showHideCols(6)"></td>
		<td class="shipmentTB3" bgcolor="#F5F5F5">Now <input type="checkbox" id="f7" checked="checked" onclick="showHideCols(7)"></td>
		</tr>
		</table>
	</div>
	<!-- <input type="button" value="Show Hide" onclick="showHideCols(0,false)" /> -->

	<table id="credittable" align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
	<!-- <tr><td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold"></td></tr> -->
	<!-- <tr bgcolor="#E5E5E5"><td>Customer</td><td width="100px">Up to 30+</td><td width="100px">Up to 14+</td><td width="100px">Up to 7+</td><td width="100px">Up to Now</td></tr> -->
	<tr><td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold"></td></tr>
	<tr bgcolor="#E5E5E5"><th>Customer</th><th width="100px">Up to 90+</th><th width="100px">Up to 60+</th><th width="100px">Up to 45+</th><th width="100px">Up to 30+</th><th width="100px">Up to 14+</th><th width="100px">Up to 7+</th><th width="100px" title="Calculation of 'Up to Now'&#13;Invoice Total - Cash Payments - All Chque Payments">Up to Now</th></tr>
<?php
	for($i=0;$i<sizeof($cust_id);$i++){
		if($display_cr==2){
			if(($cust_cr_balance90[$i]!=0)||($cust_cr_balance60[$i]!=0)||($cust_cr_balance45[$i]!=0)||($cust_cr_balance30[$i]!=0)||($cust_cr_balance14[$i]!=0)||($cust_cr_balance7[$i]!=0)||($cust_cr_balance0[$i]!=0))
			print '<tr bgcolor="#F5F5F5"><td style="padding-right:10px; padding-left:10px">'.ucfirst($cust_name[$i]).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance90[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance60[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance45[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance30[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance14[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance7[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance0[$i],$decimal).'</td></tr>';
		}else{
			print '<tr bgcolor="#F5F5F5"><td style="padding-right:10px; padding-left:10px">'.ucfirst($cust_name[$i]).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance90[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance60[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance45[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance30[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance14[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance7[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($cust_cr_balance0[$i],$decimal).'</td></tr>';
		}
	}
	print '<tr><th align="left" style="padding-right:10px; padding-left:10px">Total Amount</th><th align="right" style="padding-right:10px">'.number_format($credit_total90,$decimal).'</th><th align="right" style="padding-right:10px">'.number_format($credit_total60,$decimal).'</th><th align="right" style="padding-right:10px">'.number_format($credit_total45,$decimal).'</th><th align="right" style="padding-right:10px">'.number_format($credit_total30,$decimal).'</th><th align="right" style="padding-right:10px">'.number_format($credit_total14,$decimal).'</th><th align="right" style="padding-right:10px">'.number_format($credit_total7,$decimal).'</th><th align="right" style="padding-right:10px">'.number_format($credit_total0,$decimal).'</th>';
?>	
</tr>
	</table>

  </div>
</div>
</div>
<hr>
<br />
<?php
    include_once  'template/m_footer.php';
?>
<script>
	showHideCols(1);
	showHideCols(2);
	showHideCols(3);
	showHideCols(4);
	showHideCols(5);
	showHideCols(6);
	showHideCols(7);
</script>