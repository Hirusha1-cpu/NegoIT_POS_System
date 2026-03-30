<?php
                include_once  'template/header.php';
                $store_report=$group_report=$salesman_report='ALL';
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
	});

	function submitQForm(){
		document.getElementById('search_form').submit();
	}
</script>
<!-- ------------------Item List----------------------- -->
<form id="search_form" action="index.php" method="get" >
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="quotation_report" />
	<input type="hidden" name="item"  id="item"  />
	<table width="900px" align="center" height="100%" cellspacing="0" style="font-size:10pt; font-family:Calibri; border-radius: 15px;" bgcolor="#F0F0F0">
	<tr><td width="50px"></td><td align="center">
		<table>
		<tr><td><strong>From</strong></td><td><input type="date" id="from_date" name="from_date" value="<?php print $from_date; ?>" /></td></tr>
		<tr><td><strong>To</strong></td><td><input type="date" id="to_date" name="to_date" value="<?php print $to_date; ?>" /></td></tr>
		</table>
	</td><td width="30px"></td><td>
		<table>
		<tr><td><strong>Customer</strong></td><td><input type="text" id="cust" name="cust" value="<?php print $cust; ?>" /></td></tr>
		<tr><td><strong>Quot No</strong></td><td><input type="text" id="qo_no" name="qo_no" value="<?php print $qo_no0; ?>" /></td></tr>
		</table>
	</td><td width="30px"></td>
	<td width="30px"></td><td align="center"><strong>Shop/Store</strong>&nbsp;&nbsp;&nbsp;
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
	</td><td width="30px"></td>
	<td align="center"><strong>User</strong>&nbsp;&nbsp;&nbsp;
			<select id="sm" name="sm" onchange="submitQForm()">
			<option value="" >-ALL-</option>
			<?php for($i=0;$i<sizeof($up_id);$i++){
					if($user==$up_id[$i]){
						$select='selected="selected"';
						$salesman_report=$up_name[$i];
					}else $select='';
				print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
			}
			?>
		</select>
	</td><td width="30px"></td>
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
	<td><a onclick="submitQForm()" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a></td>
	<td width="50px"></td>
	</tr>
	</table>
</form>

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
	<tr><td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold"></td></tr>
	<tr bgcolor="#E5E5E5"><th width="100px">Timestamp</th><th width="100px">Quotation No</th><th width="100px">User</th><th width="200px">Customer</th><th width="100px">Store</th><th width="150px">Heading</th><th width="300px">Note</th></tr>
<?php
	for($i=0;$i<sizeof($qn_qo_id);$i++){
		$qm_heading0=str_replace("Quotation for ","",$qn_heading[$i]);
		if(strlen($qn_heading[$i])>25) $qm_heading0=substr($qn_heading[$i],0,25).'...'; else $qm_heading0=$qn_heading[$i];
		print '<tr><td align="center">'.$qn_time[$i].'</td><td align="center"><a href="index.php?components='.$components.'&action=qo_finish&id='.$qn_qo_id[$i].'" title="Name: '.$qm_custname[$i].'
Mobile: '.$qm_mobile[$i].'
Tel: '.$qm_tel[$i].'">'.str_pad($qn_qo_id[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB3">'.ucfirst($qn_user[$i]).'</td><td class="shipmentTB3">'.$qn_cust[$i].'</td><td class="shipmentTB3">'.$qn_store[$i].'</td><td class="shipmentTB3"><a title="'.$qn_heading[$i].'" style="cursor:pointer; color:blue">'.$qm_heading0.'</a></td><td class="shipmentTB3">'.$qn_note[$i].'</td></tr>';
	}
?>	
	</table>
</div>	
<table align="center"><tr><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br /><a style="cursor:pointer; text-decoration:none" title="'.$qm_rejected_com[$i].'"></a>
	Print
	</span></a>
</div>

</td></tr></table>
<br />
<?php
                include_once  'template/footer.php';
?>