	<table align="center" width="100%" border="1" cellspacing="1" style="font-size:11pt">
	<tr style="background-color:#CCCCCC"><th>Category</th><th>District</th><th width="100px">Increment</th></tr>
<?php
$inv=0;
	for($i=0;$i<sizeof($cr_id);$i++){
			print '<tr style="background-color:#EFEFEF"><td style="padding-left:10px; padding-right:10px"><a href="#" >'.$cr_category[$i].'</a></td><td  style="padding-left:10px; padding-right:10px">'.$cr_district[$i].'</td><td align="center">'.$cr_increment[$i].'</td></tr>';
	}
?>	
	</table>
	<br><hr><br>
	<table align="center" border="1" cellspacing="1" style="font-size:11pt">
	<tr style="background-color:#CCCCCC"><th>Item</th><th>District</th><th width="100px">Increment</th></tr>
<?php
$inv=0;
	for($i=0;$i<sizeof($sr_id);$i++){
			print '<tr style="background-color:#EFEFEF"><td style="padding-left:10px; padding-right:10px"><a href="index.php?components=inventory&action=show_specialprice&id='.$sr_id[$i].'" >'.$sr_item[$i].'</a></td><td  style="padding-left:10px; padding-right:10px">'.$sr_district[$i].'</td><td align="center">'.$sr_increment[$i].'</td></tr>';
	}
?>	
	</table>