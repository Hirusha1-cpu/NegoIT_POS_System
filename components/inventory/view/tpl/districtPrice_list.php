	<table align="center" border="1" cellspacing="1" style="font-size:11pt">
	<tr style="background-color:#CCCCCC"><th>District</th><th width="100px">Increment</th></tr>
<?php
$inv=0;
	for($i=0;$i<sizeof($di_id);$i++){
			print '<tr style="background-color:#EFEFEF"><td style="padding-left:10px; padding-right:10px"><a href="index.php?components=inventory&action=show_districtprice&id='.$di_id[$i].'" >'.$di_name[$i].'</a></td><td align="center">'.$di_increment[$i].'</td></tr>';
	}
?>	
	</table>