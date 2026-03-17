	<table align="center" width="100%" border="0" style="font-size:11pt">
		<tr style="background-color:#CCCCCC; height: 30px;">
			<th width="60px">#</th>
			<th>Category</th>
			<th>District</th>
			<th width="100px">Increment</th>
		</tr>
			<?php
				$inv=0;
				for($i=0;$i<sizeof($cr_id);$i++){
					if(($i%2)==0) $color='style="background-color:#FAFAFA"'; else
					$color='style="background-color:"#EEEEEE"';
					print '<tr  '.$color.'>
						<td style="padding-left:10px; padding-right:10px" align="center">'.sprintf('%02d',($i+1)).'</td>
						<td style="padding-left:10px; padding-right:10px"><a href="#" style="text-decoration:none;">'.$cr_category[$i].'</a></td>
						<td style="padding-left:10px; padding-right:10px">'.$cr_district[$i].'</td>
						<td align="right">'.$cr_increment[$i].'</td>
					</tr>';
				}
			?>
	</table>
	<br>
	<hr><br>
	<table align="center" border="0" style="font-size:11pt">
		<tr style="background-color:#CCCCCC; height: 30px;">
			<th width="60px">#</th>
			<th>Item</th>
			<th>District</th>
			<th width="100px">Increment</th>
		</tr>
		<?php
			$inv=0;
			for($i=0;$i<sizeof($sr_id);$i++){
				if(($i%2)==0) $color='style="background-color:#FAFAFA"'; else
				$color='style="background-color:"#EEEEEE"';
				print '<tr '.$color.'>
					<td style="padding-left:10px; padding-right:10px" align="center">'.sprintf('%02d',($i+1)).'</td>
					<td style="padding-left:10px; padding-right:10px">
						<a style="text-decoration:none;"
							href="index.php?components=inventory&action=show_specialprice&id='.$sr_id[$i].'">'.$sr_item[$i].'</a>
					</td>
					<td style="padding-left:10px; padding-right:10px">'.$sr_district[$i].'</td>
					<td align="right">'.$sr_increment[$i].'</td>
				</tr>';
			}
		?>
	</table>