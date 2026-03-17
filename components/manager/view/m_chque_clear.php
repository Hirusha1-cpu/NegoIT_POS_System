<?php
    include_once  'template/m_header.php';
?>

<!--// Start of Check Cleared Chques  -->
<div class="w3-container" style="margin-top:75px">
	<hr>
	<div class="w3-row">
	  	<div class="w3-col s3"></div>
	 	<div class="w3-col">
			<form action="index.php?components=<?php print $_GET['components']; ?>&action=clear_chque_list" method="post"  >
				<table align="center" height="100%" cellspacing="0" style="font-size:10pt; font-family:Calibri" bgcolor="#CCCCCC">
				<tr><td align="center" width="200px"><strong>Filter Cheque Date</strong></td><td align="center" width="150px"><input type="text" name="year" value="<?php print $filter_year; ?>"  class="shipmentTB3" style="width:80px" /></td>
				<td width="250px" align="right" bgcolor="white"><input type="submit" style="width:130px" value="Search" /></td></tr>
				</table>
				<input type="submit" style="display:none" />
			</form> 
		</div>
		<div class="w3-col">
			<hr />
			<table align="center" height="100%" style="font-size:10pt; font-family:Calibri; max-width: fit-content;overflow-x: auto;display: block;">
				<tr bgcolor="#CCCCCC">
					<th>Related<br>Invoice</th>
					<th>Cheque No</th>
					<th>Cheque Bank</th>
					<th>Cheque Date</th>
					<th>Amount</th>
					<th>Deposited<br>Bank</th>
					<th width="200px">Custommer</th>
				</tr>
				<?php
					for($i=0;$i<sizeof($py_date);$i++){ 
					print '<tr bgcolor="#EEEEEE">
								<td class="shipmentTB3">
									<a href="index.php?components=billing&action=finish_bill&id='.$invoice[$i].'">'.str_pad($invoice[$i], 7, "0", STR_PAD_LEFT).'</a>
								</td>
								<td align="center"  class="shipmentTB3">
									<a href="#" title="Collected By : '.ucfirst($salesman[$i]).'" style="text-decoration:none">'.$chque_no[$i].' | '.$chque_bnk_code[$i].' | '.$chque_bnk_brn[$i].'</a>
								</td>
								<td class="shipmentTB3">'.$chque_bnk_name[$i].'</td>
								<td class="shipmentTB3">
									<a href="#" title="Collected on    : '.$py_date[$i].'&#13;Deposisted on : '.$deposit_date[$i].'&#13;Deposited By   : '.ucfirst($deposit_by[$i]).'" style="text-decoration:none">'.$chque_date[$i].'</a>
								</td>
								<td class="shipmentTB3" allign="right">'.number_format($amount[$i]).'</td>
								<td class="shipmentTB3">'.$deposit_bnk[$i].'</td>
								<td class="shipmentTB3">'.$cust[$i].'</td>
							</tr>';
					}
				?>
			</table>
		</div>	
	</div>
</div>
<!--// End of Check Cleared Chques  -->

</div>
<hr>
<br />
<?php
    include_once  'template/m_footer.php';
?>
