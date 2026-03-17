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

<form action="#" method="post">
	<table align="center" border="0"  style="font-size:12pt" bgcolor="#EEEEEE">
		<tr>
			<td width="600px" align="center"><strong>List of Expenses for :</strong>
				<select name="year" id="year" onchange="window.location = 'index.php?components=<?php print $components; ?>&action=list_expense&year='+this.value">
				<?php for($i=0;$i<sizeof($year_list);$i++){
					if($_GET['year']==$year_list[$i]) $select='selected="selected"'; else $select=''; 
					print '<option value="'.$year_list[$i].'" '.$select.'>'.$year_list[$i].'</option>';
				}	?>
				</select>
			</td>
			<td rowspan="2">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Back" style="width:150px; height:50px" onclick="window.location = 'index.php?components=<?php print $components; ?>&action=expense'" />
			</td>
		</tr>
		<tr>
			<td></td>
		</tr>
	</table>

	<br /><br />

	<table align="center" border="0"  style="font-size:12pt" bgcolor="#EEEEEE" width="800px">
		<tr bgcolor="#CCCCEE" style="font-size:12pt; color:navy; font-weight:bold">
			<th>Expense ID</th>
			<th class="shipmentTB3">Expense Date</th>
			<th class="shipmentTB3">Payee Type</th>
			<th class="shipmentTB3">Payee Name</th>
			<th align="center" class="shipmentTB3">Amount</th>
			<th align="center" class="shipmentTB3">Store</th>
			<th align="center" class="shipmentTB3">Placed By</th>
		</tr>
		<?php for($i=0;$i<sizeof($em_id);$i++){
			if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
			print '<tr bgcolor="'.$color.'">
						<td align="center">
							<a href="index.php?components='.$components.'&action=one_expense&id='.$em_id[$i].'">'.str_pad($em_id[$i], 7, "0", STR_PAD_LEFT).'</a>
						</td>
						<td align="center">'.$em_expense_date[$i].'</td>
						<td class="shipmentTB3">'.ucfirst($em_payee_type[$i]).'</td>
						<td class="shipmentTB3">'.ucfirst($payee_name[$i]).'</td>
						<td align="right"  class="shipmentTB3">'.number_format($em_amount[$i]).'</td>
						<td class="shipmentTB3">'.$st_name[$i].'</td>
						<td class="shipmentTB3">'.ucfirst($up_username[$i]).'</td>
					</tr>';
		} ?>
	</table>
</form>
	

<?php
    include_once  'template/footer.php';
?>