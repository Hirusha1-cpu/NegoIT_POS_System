<?php
function billStatus($bm_type,$st_id){
	switch($st_id){
		case 0: $jasonArray["st_name"]='Deleted'; $jasonArray["st_color"]='#FF3300'; break;
		case 1: $jasonArray["st_name"]='Billed (Pending)'; $jasonArray["st_color"]='yellow'; break;
		case 2: $jasonArray["st_name"]='Billed (Picked)'; $jasonArray["st_color"]='yellow'; break;
		case 3: if($bm_type==3){ $jasonArray["st_name"]='Billed (Picked)'; } else { $jasonArray["st_name"]='Billed (Packed)'; } $jasonArray["st_color"]='yellow'; break;
		case 4: if($bm_type==3){ $jasonArray["st_name"]='Repaired'; }else{ $jasonArray["st_name"]='Billed (Shipped)'; } $jasonArray["st_color"]='yellow'; break;
		case 5: if($bm_type==3){ $jasonArray["st_name"]='Repaired | Delivered'; }else{ $jasonArray["st_name"]='Billed (Delivered)'; } $jasonArray["st_color"]='white'; break;
		case 6: $jasonArray["st_name"]='Rejected'; $jasonArray["st_color"]='orange'; break;
		case 7: $jasonArray["st_name"]='Rejected | Delivered'; $jasonArray["st_color"]='orange'; break;
	}
}

function getPortalCategory(){
	$user_id=$_COOKIE['user_id'];
	include('config.php');
	$query="SELECT portal_sup FROM userprofile WHERE id='$user_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	return $row[0];
}

function getSalesReport(){
	global $from_date,$to_date,$itm_id,$itm_desc,$inv_all_qty,$sold_all_qty,$rtn_all_qty,$inv_store2_qty,$store_id2,$store_name2;
	$itm_id=array();
	if((isset($_POST['from_date']))&&(isset($_POST['to_date']))){
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
	}else{
		$from_date=date("Y-m-d",time()-(60*60*24*30));
		$to_date=dateNow();
	}
		$i=0;
		$category=getPortalCategory();
		include('config.php');

			$query1="SELECT id,name FROM stores WHERE `status`=1";
			$result1=mysqli_query($conn,$query1);
			while($row1=mysqli_fetch_array($result1)){
				$store_id2[]=$row1[0];
				$store_name2[]=$row1[1];
			}

			$query1="SELECT id,description FROM inventory_items WHERE category='$category' AND `status`=1";
			$result1=mysqli_query($conn,$query1);
			while($row1=mysqli_fetch_array($result1)){
				$itm_id[]=$row1[0];
				$itm_desc[]=$row1[1];
				$itm_id_tmp=$row1[0];
				$result2 = mysqli_query($conn,"SELECT SUM(qty) as `qty` FROM inventory_qty WHERE item='$itm_id_tmp'");
				$row2 = mysqli_fetch_assoc($result2);
				$inv_all_qty2=$row2['qty'];
				$result2 = mysqli_query($conn,"SELECT SUM(qty) as `qty` FROM inventory_new WHERE item='$itm_id_tmp'");
				$row2 = mysqli_fetch_assoc($result2);
				$inv_all_qty[]=$row2['qty']+$inv_all_qty2;
				$result2 = mysqli_query($conn,"SELECT SUM(bi.qty) as `qty` FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bi.item='$itm_id_tmp' AND date(bm.`billed_timestamp`) BETWEEN  '$from_date' AND '$to_date' AND bm.`status`!=0");
				$row2 = mysqli_fetch_assoc($result2);
				if($row2['qty']!='') $sold_all_qty[]=$row2['qty']; else $sold_all_qty[]=0;
				$result2 = mysqli_query($conn,"SELECT SUM(rt.qty) as `qty` FROM return_main rm, `return` rt WHERE rm.invoice_no=rt.invoice_no AND rt.return_item='$itm_id_tmp' AND ( date(rm.return_date) BETWEEN '$from_date' AND '$to_date') AND rm.`status`!='0'");
				$row2 = mysqli_fetch_assoc($result2);
				if($row2['qty']!='') $rtn_all_qty[]=$row2['qty']; else $rtn_all_qty[]=0;
				
				$query1="";

				for($j=0;$j<sizeof($store_id2);$j++){
					$store2=$store_id2[$j];
					$result2 = mysqli_query($conn,"SELECT SUM(itq.qty) as `qty` FROM inventory_qty itq WHERE itq.item='$itm_id_tmp' AND itq.location='$store2'");
					$row2 = mysqli_fetch_assoc($result2);
					$inv_store2_qty0=$row2['qty'];
					$result2 = mysqli_query($conn,"SELECT SUM(itn.qty) as `qty` FROM inventory_new itn WHERE itn.item='$itm_id_tmp' AND itn.store='$store2'");
					$row2 = mysqli_fetch_assoc($result2);
					$inv_store2_qty[$i][$store2]=$row2['qty']+$inv_store2_qty0;
				}
				$i++;
		}
}

function getMonthlySale(){
	global $from_date,$to_date,$month_list,$item_id,$item_name,$quantity,$inv_all_qty,$store_id2,$store_name2,$inv_store2_qty;
	$item_id=$month_list=$quantity=array();
	$i=0;
	$category=getPortalCategory();
	include('config.php');
	if((isset($_POST['from_date']))&&(isset($_POST['to_date']))){
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
	}else{
		$from_date=date("Y-m-d",time()-(60*60*24*90));
		$to_date=dateNow();
	}
			$proceed=false;
			$k=10;
			
			$year1=substr($from_date,0,4);
			$year2=substr($to_date,0,4);
			$month1=substr($from_date,5,2);
			$month2=substr($to_date,5,2);
			if($year2>$year1){ $proceed=true; }else{
				if($month2>$month1){ $proceed=true; }
			}
			if($proceed){
				$query1="SELECT id,name FROM stores WHERE `status`=1";
				$result1=mysqli_query($conn,$query1);
				while($row1=mysqli_fetch_array($result1)){
					$store_id2[]=$row1[0];
					$store_name2[]=$row1[1];
				}

				
				$query="SELECT item,SUM(qty) FROM inventory_new GROUP BY item";
				$result=mysqli_query($conn,$query);
				while($row=mysqli_fetch_array($result)){
					$inv_all_qty1[$row[0]]=$row[1]; 
				} 

				$query="SELECT itm.id,itm.po_description,itm.supplier,SUM(itq.qty) FROM inventory_items itm, item_category itc, inventory_qty itq WHERE itm.id=itq.item AND itm.category=itc.id AND itc.id='$category' AND itm.`status`=1 GROUP BY itm.id";
				$result=mysqli_query($conn,$query);
				while($row=mysqli_fetch_array($result)){
					$item_id_tmp=$row[0]; 
					$item_id[]=$row[0]; 
					$item_name[]=$row[1]; 
					$item_supplier[]=$row[2]; 
					if(array_key_exists($item_id_tmp,$inv_all_qty1)) {
						$inv_all_qty[]=$row[3]+$inv_all_qty1[$item_id_tmp];
					 }else{
						$inv_all_qty[]=$row[3];
					}
					
	
					for($j=0;$j<sizeof($store_id2);$j++){
						$store2=$store_id2[$j];
						$result2 = mysqli_query($conn,"SELECT itq.qty as `qty` FROM inventory_qty itq WHERE itq.location='$store2' AND itq.item='$item_id_tmp'");
						$row2 = mysqli_fetch_assoc($result2);
						$inv_store2_qty0=$row2['qty'];
						$result2 = mysqli_query($conn,"SELECT SUM(itn.qty) as `qty` FROM inventory_new itn WHERE itn.store='$store2' AND itn.item='$item_id_tmp'");
						$row2 = mysqli_fetch_assoc($result2);
						$inv_store2_qty[$i][$store2]=$row2['qty']+$inv_store2_qty0;
					}
					$i++;
					
				} 
		
				
				$month_now=$year1.'-'.$month1;
				while($month_now!='0000-00'){
					$month_list[]=$month_now;
					for($i=0;$i<sizeof($item_id);$i++){
						$quantity[$month_now][$item_id[$i]]=0;
					}
					$month_now=monthCount($year1,$month1,$year2,$month2);
					$year1=substr($month_now,0,4);
					$month1=substr($month_now,5,2);
				}
				
				for($i=0;$i<sizeof($month_list);$i++){
					$query="SELECT itm.id,SUM(bi.qty) FROM bill bi, bill_main bm, inventory_items itm, item_category itc WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND itm.category=itc.id AND itc.id='$category' AND bm.`status`!='0' AND itm.pr_sr='1' AND bm.billed_timestamp LIKE '$month_list[$i]%' GROUP BY itm.id";
					$result=mysqli_query($conn,$query);
					while($row=mysqli_fetch_array($result)){
						$quantity[$month_list[$i]][$row[0]]=$row[1]; 
					} 
				}
		}
}

function getMonthlyReturn(){
	global $from_date,$to_date,$month_list,$item_id,$item_name,$quantity,$inv_all_qty,$store_id2,$store_name2,$inv_store2_qty;
	$item_id=$month_list=$quantity=array();
	$i=0;
	$category=getPortalCategory();
	include('config.php');
	if((isset($_POST['from_date']))&&(isset($_POST['to_date']))){
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
	}else{
		$from_date=date("Y-m-d",time()-(60*60*24*90));
		$to_date=dateNow();
	}
			$proceed=false;
			$k=10;
			
			$year1=substr($from_date,0,4);
			$year2=substr($to_date,0,4);
			$month1=substr($from_date,5,2);
			$month2=substr($to_date,5,2);
			if($year2>$year1){ $proceed=true; }else{
				if($month2>$month1){ $proceed=true; }
			}
			if($proceed){
				$query1="SELECT id,name FROM stores WHERE `status`=1";
				$result1=mysqli_query($conn,$query1);
				while($row1=mysqli_fetch_array($result1)){
					$store_id2[]=$row1[0];
					$store_name2[]=$row1[1];
				}

				
				$query="SELECT item,SUM(qty) FROM inventory_new GROUP BY item";
				$result=mysqli_query($conn,$query);
				while($row=mysqli_fetch_array($result)){
					$inv_all_qty1[$row[0]]=$row[1]; 
				} 

				$query="SELECT itm.id,itm.po_description,itm.supplier,SUM(itq.qty) FROM inventory_items itm, item_category itc, inventory_qty itq WHERE itm.id=itq.item AND itm.category=itc.id AND itc.id='$category' AND itm.`status`=1 GROUP BY itm.id";
				$result=mysqli_query($conn,$query);
				while($row=mysqli_fetch_array($result)){
					$item_id_tmp=$row[0]; 
					$item_id[]=$row[0]; 
					$item_name[]=$row[1]; 
					$item_supplier[]=$row[2]; 
					if(array_key_exists($item_id_tmp,$inv_all_qty1)) {
						$inv_all_qty[]=$row[3]+$inv_all_qty1[$item_id_tmp];
					 }else{
						$inv_all_qty[]=$row[3];
					}
					
	
					for($j=0;$j<sizeof($store_id2);$j++){
						$store2=$store_id2[$j];
						$result2 = mysqli_query($conn,"SELECT itq.qty as `qty` FROM inventory_qty itq WHERE itq.location='$store2' AND itq.item='$item_id_tmp'");
						$row2 = mysqli_fetch_assoc($result2);
						$inv_store2_qty0=$row2['qty'];
						$result2 = mysqli_query($conn,"SELECT SUM(itn.qty) as `qty` FROM inventory_new itn WHERE itn.store='$store2' AND itn.item='$item_id_tmp'");
						$row2 = mysqli_fetch_assoc($result2);
						$inv_store2_qty[$i][$store2]=$row2['qty']+$inv_store2_qty0;
					}
					$i++;
					
				} 
		
				
				$month_now=$year1.'-'.$month1;
				while($month_now!='0000-00'){
					$month_list[]=$month_now;
					for($i=0;$i<sizeof($item_id);$i++){
						$quantity[$month_now][$item_id[$i]]=0;
					}
					$month_now=monthCount($year1,$month1,$year2,$month2);
					$year1=substr($month_now,0,4);
					$month1=substr($month_now,5,2);
				}
				
				for($i=0;$i<sizeof($month_list);$i++){
					$query="SELECT itm.id,SUM(rt.qty) FROM `return` rt, return_main rm, inventory_items itm, item_category itc WHERE rm.invoice_no=rt.invoice_no AND rt.return_item=itm.id AND itm.category=itc.id AND itc.id='$category' AND rm.`status`!='0' AND itm.pr_sr='1' AND rm.return_date LIKE '$month_list[$i]%' GROUP BY itm.id";
					$result=mysqli_query($conn,$query);
					while($row=mysqli_fetch_array($result)){
						$quantity[$month_list[$i]][$row[0]]=$row[1]; 
					} 
				}
		}
}

function getDashboard(){
	global $rtn_itm_id,$rtn_itm_desc,$rtn_itm_qty;
	$rtn_itm_id=$rtn_itm_desc=$rtn_itm_qty=array();
	if((isset($_POST['from_date']))&&(isset($_POST['to_date']))){
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
	}else{
		$from_date=date("Y-m-d",time()-(60*60*24*30));
		$to_date=dateNow();
	}
		$i=0;
		$category=getPortalCategory();
		include('config.php');

			$query1="SELECT itm.id,itm.description,SUM(rt.qty) FROM `return` rt, return_main rm, inventory_items itm, item_category itc WHERE rm.invoice_no=rt.invoice_no AND rt.return_item=itm.id AND itm.category=itc.id AND itc.id='$category' AND rm.`status`!='0' AND itm.pr_sr='1' AND ( date(rm.return_date) BETWEEN '$from_date' AND '$to_date') GROUP BY itm.id";
			$result1=mysqli_query($conn,$query1);
			while($row1=mysqli_fetch_array($result1)){
				$rtn_itm_id[]=$row1[0];
				$rtn_itm_desc[]=$row1[1];
				$rtn_itm_qty[]=$row1[2];
		}
}

?>