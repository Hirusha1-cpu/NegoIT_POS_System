<div style="display:inline-block; margin-right: 40px">
	<table width="100%" align="center" bgcolor="#E5E5E5" style="font-family:Calibri;" align="center">
		<tr>
			<td style="font-size:16pt; font-weight:bold; color:navy" align="center">List Of Pending Shipments</td>
		</tr>
	</table>
	<p align="center" style="font-family:Calibri; font-size:10pt; color:gray">Temporarily saved shipments</p>
	<table align="center" style="font-family:Calibri">
		<tr bgcolor="#CCCCCC">
			<th width="50px">#</th>
			<th>Shipment Tmp No</th>
			<th width="100px">Invoice No</th>
			<th width="100px">Entered Date</th>
			<th width="120px">Placed By</th>
			<th class="shipmentTB4">Store</th>
		</tr>

		<?php
			$inv=0;
			for($i=0;$i<sizeof($shipment_no_tmp);$i++){
				if($unic_tmp[$i]==1) $sub='show_add_unic_tmp'; else $sub='show_add_qty_tmp';
				if($edit_ship_tmp[$i]){
					$color1='blue';
					$edit='<input type="Button" value="Edit"  onclick="window.location = '."'".'index.php?components=inventory&action='.$sub.'&shipment_no='.$shipment_no_tmp[$i]."'".'" />';
				}else{
					$color1='gray';
					$edit='';
				}
				print '<tr bgcolor="#EEEEEE">
					<td align="center">
						<span>'.sprintf('%02d',($i+1)).'</span>
					</td>
					<td align="center" class="shipmentTB3"><a style="color:'.$color1.'; text-decoration:none;"
							href="index.php?components=inventory&action='.$sub.'&shipment_no='.$shipment_no_tmp[$i].'">'.str_pad($shipment_no_tmp[$i],
							7, "0", STR_PAD_LEFT).'</a>&nbsp;&nbsp;&nbsp;&nbsp;'.$edit.'</td>
					<td width="50px" align="left" class="shipmentTB4">'.$invoice_no_tmp[$i].'</td>
					<td width="50px" align="center">'.$date_tmp[$i].'</td>
					<td class="shipmentTB4">'.ucfirst($salesman_tmp[$i]).'</td>
					<td class="shipmentTB4">'.$ship_store_tmp[$i].'</td>
				</tr>';
			}
		?>
	</table>
</div>
