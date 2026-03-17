	<div style="display:inline-block;">
		<table width="100%" align="center" bgcolor="#E5E5E5" style="font-family:Calibri;" align="center" >
		<tr><td style="font-size:16pt; font-weight:bold; color:navy" align="center">List Of Shipments</td></tr>
		</table>
		<p align="center" style="font-family:Calibri; font-size:10pt; color:gray">Please note that new shipments will be editable up to <?php print $shipment_edit_time; ?> hour/hours</p>
			<table align="center" style="font-family:Calibri">
			<tr bgcolor="#BBBBBB"><th width="120px">Shipment No</th><th width="100px">Entered Date</th><th width="100px">Time</th><th width="100px">Placed By</th><th class="shipmentTB4">Store</th></tr>
			<tr bgcolor="#BBBBBB"><th></th><th></th><th></th><th></th><th>
			<select id="store" onchange="filterStore()" >
			<option value="all" >ALL</option>
			<?php
			for($i=0;$i<sizeof($stores_id);$i++){
				if($store0==$stores_id[$i]) $select='selected="selected"'; else $select=''; 
					print '<option value="'.$stores_id[$i].'" '.$select.'>'.$stores_name[$i].'</option>';
			}
			?>	
			</select>
			</th></tr>

		<?php
		$inv=0;
			for($i=0;$i<sizeof($shipment_no);$i++){
				if($salesman[$i]==$current_user){
					$color1='blue'; 
				}else{
					$color1='gray';
				}
					print '<tr bgcolor="#DEDEDE"><td align="center"><a style="color:'.$color1.'; text-decoration:none;" href="index.php?components=inventory&action=one_shipment&shipment_no='.$shipment_no[$i].'">'.str_pad($shipment_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td width="50px" align="center">'.$date[$i].'</td><td width="50px" align="center">'.$time[$i].'</td><td class="shipmentTB4" >'.ucfirst($salesman[$i]).'</td><td class="shipmentTB4" >'.$ship_store[$i].'</td></tr>';
			}
		?>	
		</table>
	</div>



