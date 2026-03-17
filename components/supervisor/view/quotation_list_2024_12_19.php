<?php
	include_once  'template/header.php';
	$store_report=$group_report=$salesman_report='ALL';
	$decimal = getDecimalPlaces(1);
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
		var availableTags1 = [<?php for ($x=0;$x<sizeof($cu_name);$x++){ print '"'.$cu_name[$x].'",'; } ?>	];
		$( "#cust" ).autocomplete({
			source: availableTags1
		});
		var availableTags2 = [<?php for ($x=0;$x<sizeof($item_desc);$x++){ print '"'.$item_desc[$x].'",'; } ?>	];
		$( "#item0" ).autocomplete({
			source: availableTags2
		});
	});

	function submitQForm(){
		var itemid_arr = [<?php for ($x=0;$x<sizeof($item_id);$x++){ print '"'.$item_id[$x].'",'; } ?>	];
		var itemdes_arr = [<?php for ($x=0;$x<sizeof($item_desc);$x++){ print '"'.$item_desc[$x].'",'; } ?>	];
		var item=document.getElementById('item0').value;
		var submit=false;
		if(item!=''){
			var a=itemdes_arr.indexOf(item);
			if(a != -1){
				document.getElementById('item').value=itemid_arr[a];
				submit=true;
			}
		}else submit=true;
		if(submit) document.getElementById('search_form').submit();
	}

	function submitQForm2(){
		document.getElementById('searchquo').submit();
	}

	function completeCheck($case){
		var qo_no = [<?php for ($x=0;$x<sizeof($qm_inv_check);$x++){ print '"'.$qm_inv_check[$x].'",'; } ?>	];
		var last_qo=qo_no[(qo_no.length)-1];
		if((document.getElementById("qo_check").checked)&&((qo_no.length)>0)){
			document.getElementById('loading_qocheck').innerHTML=document.getElementById('loading').innerHTML;
			for(var i=0;i<qo_no.length;i++){
				completeCheckPHP($case,qo_no[i],last_qo);
			}
		}else{
			for(var i=0;i<qo_no.length;i++){
					document.getElementById('col1_'+qo_no[i]).parentNode.style.background = '';
					document.getElementById('col2_'+qo_no[i]).parentNode.style.background = '';
					document.getElementById('col3_'+qo_no[i]).parentNode.style.background = '';
					document.getElementById('col4_'+qo_no[i]).parentNode.style.background = '';
					document.getElementById('col5_'+qo_no[i]).parentNode.style.background = '';
					document.getElementById('col6_'+qo_no[i]).parentNode.style.background = '';
					document.getElementById('col7_'+qo_no[i]).parentNode.style.background = '';
					document.getElementById('col8_'+qo_no[i]).parentNode.style.background = '';
			}
		}
	}

	function completeCheckPHP($case,qo_no,last_qo){
	  if($case==1){ var color='#FFA500'; var txt='yes'; }
	  if($case==2){ var color='#EE6666'; var txt='no'; }
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    var returntext=this.responseText;
	    	$values=returntext.split(',');
			if($values[0]==txt){
				document.getElementById('col1_'+qo_no).parentNode.style.background = color;
				document.getElementById('col2_'+qo_no).parentNode.style.background = color;
				document.getElementById('col3_'+qo_no).parentNode.style.background = color;
				document.getElementById('col4_'+qo_no).parentNode.style.background = color;
				document.getElementById('col5_'+qo_no).parentNode.style.background = color;
				document.getElementById('col6_'+qo_no).parentNode.style.background = color;
				document.getElementById('col7_'+qo_no).parentNode.style.background = color;
				document.getElementById('col8_'+qo_no).parentNode.style.background = color;
			}
				document.getElementById('col8_'+qo_no).innerHTML = $values[1]+'%';
				if(qo_no=last_qo) document.getElementById('loading_qocheck').innerHTML='';
	    }
	  };
	  xhttp.open("GET", 'index.php?components=<?php print $_GET['components']; ?>&action=qo_complete_check&qo_no='+qo_no, true);
	  xhttp.send();
	}
</script>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:30px" /></div>

<table width="900px" align="center" height="100%" cellspacing="0" style="font-size:10pt; font-family:Calibri; border-radius: 15px;" bgcolor="#F0F0F0">
	<form id="search_form" action="index.php" method="get" >
		<input type="hidden" name="components" value="<?php print $components; ?>" />
		<input type="hidden" name="action" value="quotation_list" />
		<input type="hidden" name="item"  id="item"  />
		<tr>
			<td width="50px"></td>
			<td align="center">
					<table>
						<tr>
							<td><strong>From</strong></td>
							<td><input type="date" id="from_date" name="from_date" value="<?php print $from_date; ?>" /></td>
						</tr>
						<tr>
							<td><strong>To</strong></td>
							<td><input type="date" id="to_date" name="to_date" value="<?php print $to_date; ?>" /></td>
						</tr>
					</table>
			</td>
			<td width="30px"></td>
			<td>
				<table>
					<tr>
						<td><strong>Customer</strong></td><td><input type="text" id="cust" name="cust" value="<?php print $cust; ?>" /></td>
					</tr>
					<tr>
						<td><strong>Item</strong></td><td><input type="text" id="item0" value="<?php print $item0; ?>" /></td>
					</tr>
				</table>
			</td>
			<td width="30px"></td>
			<td width="30px"></td>
			<td align="center"><strong>Shop/Store</strong>&nbsp;&nbsp;&nbsp;
				<select id="st" name="st" onchange="submitQForm()">
					<option value="" >-ALL-</option>
					<?php for($i=0;$i<sizeof($store_id);$i++){
							if($store==$store_id[$i]){
								$select='selected="selected"';
								$store_report=$store_name[$i];
							}else $select='';
						print '<option value="'.$store_id[$i].'" '.$select.'>'.$store_name[$i].'</option>';
					}
					?>
				</select>
			</td>
			<td width="30px"></td>
			<td align="center"><strong>Salesman</strong>&nbsp;&nbsp;&nbsp;
				<select id="sm" name="sm" onchange="submitQForm()">
					<option value="" >-ALL-</option>
					<?php for($i=0;$i<sizeof($up_id);$i++){
							if($salesman==$up_id[$i]){
								$select='selected="selected"';
								$salesman_report=$up_name[$i];
							}else $select='';
						print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
					}
					?>
				</select>
			</td>
			<td width="30px"></td>
			<td align="center"><strong>Status</strong>&nbsp;&nbsp;&nbsp;
				<select id="status" name="status" onchange="submitQForm()">
					<option value="all" >-ALL-</option>
					<option value="1" <?php if($status==1) print 'selected="selected"'; ?> >On Going</option>
					<option value="2" <?php if($status==2) print 'selected="selected"'; ?> >Pending</option>
					<option value="3" <?php if($status==3) print 'selected="selected"'; ?> >Approved</option>
					<option value="4" <?php if($status==4) print 'selected="selected"'; ?> >Rejected</option>
					<option value="5" <?php if($status==5) print 'selected="selected"'; ?> >Sent to Customer</option>
					<option value="6" <?php if($status==6) print 'selected="selected"'; ?> >Customer Accepted</option>
					<option value="7" <?php if($status==7) print 'selected="selected"'; ?> >Customer Rejected</option>
					<option value="8" <?php if($status==8) print 'selected="selected"'; ?> >Completed</option>
				</select>
			</td>
			<td>
				<a onclick="submitQForm()" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a>
			</td>
			<td width="50px"></td>
		</tr>
	</form>
	<tr>
		<td></td>
		<td colspan="7" align="left">
			<form id="searchquo" action="index.php?components=<?php print $components; ?>&action=search_quot" method="post">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" style="width:110px" name="search1" id="search1" placeholder="Quotation Number" />
				<a onclick="submitQForm2()" style="cursor:pointer">
					<img src="images/search.png" style="width:30px; vertical-align:middle" />
				</a>
			</form>
		</td>
		<td colspan="3" style="color:maroon">
			<?php
				if(($_GET['status']=='all')||($_GET['status']==6)) print '<input type="checkbox" id="qo_check" onclick="completeCheck(1)" /> Fully Filled Quotations';
				if($_GET['status']==8) print '<input type="checkbox" id="qo_check" onclick="completeCheck(2)" /> Partially Filled Quotations';
			?>
		</td>
		<td>
			<div id="loading_qocheck"></div>
		</td>
	</tr>
</table>


<table align="center" style="font-size: 10pt; margin-top: 10px;">
	<tr>
		<td width="20px" bgcolor="wheat"></td>
		<td> - Quantity available in SUM of all stores | </td>
		<?php if($_GET['status']==8)
			print '<td width="20px" bgcolor="#EE6666"></td>
			<td> - Partially Filled Quotations </td>';
			else print '<td width="20px" bgcolor="#FFA500"></td>
			<td> - Fully Filled Quotations </td>';
		?>
	</tr>
</table>

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Quotation Report</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;From Date &nbsp;&nbsp;&nbsp;</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;To Date &nbsp;&nbsp;&nbsp;</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $to_date; ?>&nbsp;&nbsp;</td></tr>
	</table><br />
</div>

<br />
<div id="print">
	<table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
		<tr>
			<td colspan="<?php if($components != 'to') echo "12"; else echo "11"; ?>" style="border:0; background-color:black; color:white; font-weight:bold"></td>
		</tr>
		<tr bgcolor="#E5E5E5">
			<th width="60px">#</th>
			<th>Quotation No</th>
			<th width="100px">Created Date</th>
			<th width="100px">Store</th>
			<th width="200px">Customer</th>
			<th width="200px">Heading</th>
			<th width="100px">Salesman</th>
			<?php if($components != 'to'){ ?> <th width="100px">Amount</th> <?php } ?>
			<th width="80px">Completed %</th>
			<th width="100px">Status</th>
		</tr>
			<?php
				for($i=0;$i<sizeof($qm_id);$i++){
					$qm_heading0=str_replace("Quotation for ","",$qm_heading[$i]);
					if(strlen($qm_heading0)>25) $qm_heading0=substr($qm_heading0,0,25).'...'; else $qm_heading0=$qm_heading0;
					if($qm_qty_avalability[$i]) $row_color='wheat'; else $row_color='#F5F5F5';
					if($qm_status[$i]=='7') $qm_st='<a style="cursor:pointer; text-decoration:none" title="'.$qm_rejected_com[$i].'">'.$qm_status_name[$i].'</a>'; else $qm_st=$qm_status_name[$i];
					if($qm_status[$i]==2) $action0='qo_finish'; else $action0='qo_one';
					if($components == 'to') $action0='qo_finish';

					print
						'<tr bgcolor="'.$row_color.'">
							<td align="center">
								'.($i+1).'
							</td>
							<td align="center">
								<div id="col1_'.$qm_id[$i].'">
									<a href="index.php?components='.$components.'&action='.$action0.'&id='.$qm_id[$i].'" title="Name: '.$qm_custname[$i].'Mobile: '.$qm_mobile[$i].'Tel: '.$qm_tel[$i].'" target="_blank">'.str_pad($qm_id[$i], 7, "0", STR_PAD_LEFT).'
									</a>
								</div>
							</td>
							<td align="center">
								<div id="col2_'.$qm_id[$i].'"><a style="cursor:pointer; color:blue" title="Time: '.substr($qm_created_date[$i],11,5).'" >'.substr($qm_created_date[$i],0,10).'</a></div>
							</td>
							<td class="shipmentTB3">
								<div id="col3_'.$qm_id[$i].'">'.$qm_store[$i].'</div>
							</td>
							<td class="shipmentTB3">
								<div id="col4_'.$qm_id[$i].'">'.$qm_cust[$i].'</div></td><td class="shipmentTB3"><div id="col5_'.$qm_id[$i].'"><a title="'.$qm_heading[$i].'" style="cursor:pointer; color:blue">'.$qm_heading0.'</a></div>
							</td>
							<td class="shipmentTB3">
								<div id="col6_'.$qm_id[$i].'">'.ucfirst($qm_created_by[$i]).'</div>
							</td>';
							if(($components != 'to')){
								print '<td class="shipmentTB3" align="right">
									<div id="col7_'.$qm_id[$i].'">'.number_format($qm_amount[$i], $decimal).'</div>
								</td>';
							}
							print '<td align="right" class="shipmentTB3" >
								<div id="col8_'.$qm_id[$i].'" ></div>
							</td>
							<td align="center" style="color:'.$qm_status_color[$i].'" bgcolor="grey">
								'.$qm_st.'
							</td>
						</tr>';
				}
			?>
	</table>
</div>

<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/print.png" alt="icon" /><br /><a style="cursor:pointer; text-decoration:none" title="'.$qm_rejected_com[$i].'"></a>
					Print
					</span>
				</a>
			</div>
		</td>
	</tr>
</table>

<br />
<?php
    include_once  'template/footer.php';
?>