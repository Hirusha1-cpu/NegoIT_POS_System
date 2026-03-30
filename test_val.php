<?php
if(isset($_GET['update'])) $update=true; else $update=false;
	$i=1;
 
	include('config.php');
	$query1="SELECT itq.id,itq.w_price FROM inventory_qty_back itq, inventory_items itm WHERE itm.id=itq.item AND itm.category=37";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$bak_itq_id=$row1[0];
		$bak_itq_price=$row1[1];
		
		$query2="SELECT itq.w_price FROM inventory_qty itq WHERE itq.id='$bak_itq_id'";
		$row2=mysqli_fetch_row(mysqli_query($conn,$query2));
		$itq_price=$row2[0];
		
		print $i.'| '.$bak_itq_id.' - '.$bak_itq_price.' - '.$itq_price.'<br />';

		if($update){
			$query3="UPDATE inventory_qty itq SET itq.w_price='$bak_itq_price' WHERE itq.id='$bak_itq_id'";
			$result3=mysqli_query($conn,$query3);
			if($result3) print $bak_itq_id.' - Done<br />'; else print $bak_itq_id.' - Error<br />';
		}
		$i++;
	}
	
?>