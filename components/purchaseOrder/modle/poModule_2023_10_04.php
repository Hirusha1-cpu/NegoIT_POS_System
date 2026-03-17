<?php

function getCategory(){
	global $category_id,$category_name;
	$category_id=$category_name=array();
	include('config.php');
		$query="SELECT id,name FROM item_category";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$category_id[]=$row[0];
			$category_name[]=$row[1];
	} 
}

function getStore(){
	global $store_id,$store_name;
	$store_id=$store_name=array();
	include('config.php');
		$query="SELECT id,name FROM stores WHERE `status`=1";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$store_id[]=$row[0];
			$store_name[]=$row[1];
	} 
}

function getGroup(){
	global $group_id,$group_name;
	$group_id=$group_name=array();
	include('config.php');
		$query="SELECT group_id,group_name FROM store_group_main";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$group_id[]=$row[0];
			$group_name[]=$row[1];
	} 
}

function getItems(){
	global $itm_id,$itm_desc,$itm_supplier,$inv_store_qty,$inv_all_qty,$sold_store_qty,$sold_all_qty,$store_id2,$store_name2,$inv_store2_qty,$qty_list,$group_stores;
	$group_stores=$itm_id=array();
	if(((isset($_POST['category']))&&(isset($_POST['from_date']))&&(isset($_POST['to_date'])))||(isset($_GET['item_list'])&&isset($_GET['qty_list']))){
		$i=0;
		if(isset($_POST['category'])){
			$category=$_POST['category'];
			$category_qry="category='$category'";
			$itemlist_qry='';
			$from_date=$_POST['from_date'];
			$to_date=$_POST['to_date'];
			$order_qry="";
		}else{
			$category_qry='';
			$itemlist=$_GET['item_list'];
			$itemlist_qry="id IN ($itemlist)";
			$from_date=date("Y-m-d",time()+(30*24*60*60));
			$to_date=dateNow();
			$order_qry="ORDER BY Field (id,$itemlist)";
			$qty_list=(explode(",",$_GET['qty_list']));
		}
		include('config.php');
		if(isset($_POST['store']))	$store=$_POST['store']; else $store=$_COOKIE['store'];
		$select_st_qry1="AND itq.location='$store'";
		$select_st_qry2="AND itn.store='$store'";
		$select_st_qry3="AND bm.store='$store'";
			if(isset($_POST['group'])){
				if($_POST['group']!=''){
					$gp=$_POST['group'];
					if($gp=='0') $gpqry="sg.`group` IN (1,3)"; else $gpqry="sg.`group`='$gp'";
					$st_list='';
					$query="SELECT DISTINCT st.id,st.`name` FROM store_group sg, stores st WHERE sg.store=st.id AND $gpqry";
					$result=mysqli_query($conn,$query);
					while($row=mysqli_fetch_array($result)){
						$st_list.=$row[0].','; 
						$group_stores[]=$row[1];
					} 
					$st_list=rtrim($st_list,",");
					$st_qry="AND bm.store IN ($st_list)";
					$select_st_qry1="AND itq.location IN ($st_list)";
					$select_st_qry2="AND itn.store IN ($st_list)";
					$select_st_qry3="AND bm.store IN ($st_list)";
				} 
			}
		
		
			$query1="SELECT id,name FROM stores WHERE `status`=1";
			$result1=mysqli_query($conn,$query1);
			while($row1=mysqli_fetch_array($result1)){
				$store_id2[]=$row1[0];
				$store_name2[]=$row1[1];
			}

			$query1="SELECT id,description,supplier FROM inventory_items WHERE $category_qry $itemlist_qry AND `status`=1 $order_qry";
			$result1=mysqli_query($conn,$query1);
			while($row1=mysqli_fetch_array($result1)){
				$itm_id[]=$row1[0];
				$itm_desc[]=$row1[1];
				$itm_supplier[]=$row1[2];
				$itm_id_tmp=$row1[0];
				$result2 = mysqli_query($conn,"SELECT SUM(itq.qty) as `qty` FROM inventory_qty itq WHERE itq.item='$itm_id_tmp' $select_st_qry1");
				$row2 = mysqli_fetch_assoc($result2);
				$inv_store_qty0=$row2['qty'];
				$result2 = mysqli_query($conn,"SELECT SUM(itn.qty) as `qty` FROM inventory_new itn WHERE itn.item='$itm_id_tmp' $select_st_qry2");
				$row2 = mysqli_fetch_assoc($result2);
				$inv_store_qty[]=$row2['qty']+$inv_store_qty0;
				$result2 = mysqli_query($conn,"SELECT SUM(qty) as `qty` FROM inventory_qty WHERE item='$itm_id_tmp'");
				$row2 = mysqli_fetch_assoc($result2);
				$inv_all_qty2=$row2['qty'];
				$result2 = mysqli_query($conn,"SELECT SUM(qty) as `qty` FROM inventory_new WHERE item='$itm_id_tmp'");
				$row2 = mysqli_fetch_assoc($result2);
				$inv_all_qty[]=$row2['qty']+$inv_all_qty2;
				$result2 = mysqli_query($conn,"SELECT SUM(bi.qty) as `qty` FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bi.item='$itm_id_tmp' AND date(bm.`billed_timestamp`) BETWEEN  '$from_date' AND '$to_date' AND bm.`status`!=0 $select_st_qry3");
				$row2 = mysqli_fetch_assoc($result2);
				$sold_store_qty[]=$row2['qty'];
				$result2 = mysqli_query($conn,"SELECT SUM(bi.qty) as `qty` FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bi.item='$itm_id_tmp' AND date(bm.`billed_timestamp`) BETWEEN  '$from_date' AND '$to_date' AND bm.`status`!=0");
				$row2 = mysqli_fetch_assoc($result2);
				$sold_all_qty[]=$row2['qty'];
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
}

function getItems2(){
	global $itm_id,$itm_desc;

	include('config.php');
		
	$query="SELECT id,po_description FROM inventory_items WHERE `status`=1";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$itm_id[]=$row[0]; 
		$itm_desc[]=$row[1]; 
	} 
}
     
function getItemSale(){
	global $month_list,$item_id,$item_name,$item_supplier,$quantity,$inv_store_qty,$inv_all_qty,$store_id2,$store_name2,$inv_store2_qty,$group_stores;
	$item_id=$month_list=$quantity=$group_stores=array();
	$i=0;
	$st_qry=$st='';
	$select_st_qry1="AND itq.location=''";
	$select_st_qry2="AND itn.store=''";
	include('config.php');
	if(isset($_POST['from_date'])&&isset($_POST['to_date'])&&isset($_POST['category'])){
		$category=$_POST['category'];
		if($category!=''){
			$from_date=$_POST['from_date'];
			$to_date=$_POST['to_date'];
			if(isset($_POST['store'])){
				if($_POST['store']!=''){
					$st=$_POST['store'];
					$st_qry="AND bm.store='$st'";
					$select_st_qry1="AND itq.location='$st'";
					$select_st_qry2="AND itn.store='$st'";
				} 
			}
			if(isset($_POST['group'])){
				if($_POST['group']!=''){
					$gp=$_POST['group'];
					if($gp=='0') $gpqry="sg.`group` IN (1,3)"; else $gpqry="sg.`group`='$gp'";
					$st_list='';
					$query="SELECT DISTINCT st.id,st.`name` FROM store_group sg, stores st WHERE sg.store=st.id AND $gpqry";
					$result=mysqli_query($conn,$query);
					while($row=mysqli_fetch_array($result)){
						$st_list.=$row[0].','; 
						$group_stores[]=$row[1];
					} 
					$st_list=rtrim($st_list,",");
					$st_qry="AND bm.store IN ($st_list)";
					$select_st_qry1="AND itq.location IN ($st_list)";
					$select_st_qry2="AND itn.store IN ($st_list)";
				} 
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
					
					$query2="SELECT SUM(itq.qty) FROM inventory_qty itq WHERE itq.item='$item_id_tmp' $select_st_qry1";
					$row2=mysqli_fetch_row(mysqli_query($conn,$query2));
					$inv_store_qty0=$row2[0]; 					
					$query2="SELECT SUM(itn.qty) FROM inventory_new itn WHERE itn.item='$item_id_tmp' $select_st_qry2";
					$row2=mysqli_fetch_row(mysqli_query($conn,$query2));
					$inv_store_qty[]=$row2[0]+$inv_store_qty0; 					
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
					$query="SELECT itm.id,SUM(bi.qty) FROM bill bi, bill_main bm, inventory_items itm, item_category itc WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND itm.category=itc.id AND itc.id='$category' AND bm.`status`!='0' AND itm.pr_sr='1' $st_qry AND bm.billed_timestamp LIKE '$month_list[$i]%' GROUP BY itm.id";
					$result=mysqli_query($conn,$query);
					while($row=mysqli_fetch_array($result)){
						$quantity[$month_list[$i]][$row[0]]=$row[1]; 
					} 
				}
			}
		}
	}
}

function getSupplier(){
	global $su_id,$su_name,$su_email,$su_tel1,$su_tel2,$su_address,$su_country,$su_status;
	$su_id=array();
	include('config.php');
	$query="SELECT id,name,email,tel1,tel2,address,country,`status` FROM supplier";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$su_id[]=$row[0]; 
		$su_name[]=$row[1]; 
		$su_email[]=$row[2]; 
		$su_tel1[]=$row[3]; 
		$su_tel2[]=$row[4]; 
		$su_address[]=$row[5]; 
		$su_country[]=$row[6]; 
		$su_status[]=$row[7]; 
	} 
}

function getOneSupplier($mode){
	global $sup_id,$sup_name,$sup_email,$sup_tel1,$sup_tel2,$sup_address,$sup_country,$sup_status;
	$id=$_REQUEST['id'];
	if($mode=='id') $mode1="id='$id'";
	if($mode=='name') $mode1="name='$id'";
	include('config.php');
	$result = mysqli_query($conn,"SELECT id,name,email,tel1,tel2,address,country,`status` FROM supplier WHERE $mode1");
	$row = mysqli_fetch_assoc($result);
	$sup_id=$row['id'];
	$sup_name=$row['name'];
	$sup_email=$row['email'];
	$sup_tel1=$row['tel1'];
	$sup_tel2=$row['tel2'];
	$sup_address=$row['address'];
	$sup_country=$row['country'];
	$sup_status=$row['status'];
}

function addSupplier(){
	global $message;
	$sup_name=$_REQUEST['sup_name'];
	$email=$_REQUEST['email'];	
	$tel1=$_REQUEST['tel1'];
	$tel2=$_REQUEST['tel2'];
	$address=$_REQUEST['address'];	
	$country=$_REQUEST['country'];	
	if(isset($_POST['dis'])) $dis=true; else $dis=false;
	include('config.php');
	$result = mysqli_query($conn,"SELECT count(id) as `count` FROM supplier WHERE name='$sup_name'");
	$row = mysqli_fetch_assoc($result);
	$count=$row['count'];
	
	if($count==0){
		$query="INSERT INTO `supplier` (`name`,`email`,`tel1`,`tel2`,`address`,`country`,`status`) VALUES ('$sup_name','$email','$tel1','$tel2','$address','$country','1')";
		$result=mysqli_query($conn,$query);
		if($result){
			$query2="INSERT INTO `accounts` (`name`,`category`,`bank_ac`,`payment_ac`,`system_ac`,`status`) VALUES ('$sup_name','24','0','0','1','1')";
			$result2=mysqli_query($conn,$query2);
			if(($result2)&&($dis)){
				$query2="INSERT INTO `accounts` (`name`,`category`,`bank_ac`,`payment_ac`,`system_ac`,`status`) VALUES ('Dis-$sup_name','11','0','0','1','1')";
				$result2=mysqli_query($conn,$query2);
			}
		}
		if($result2){
			$message='Supplier was Added Successfully!';
			return true;
		}else{
			$message='Supplier could not be Added!';
			return false;
		}
	}else{
			$message='This Supplier is already exist !';
			return false;
	}
}

function updateSupplier(){
	global $message;
	$sup_id=$_REQUEST['sup_id'];
	$sup_name=$_REQUEST['sup_name'];
	$email=$_REQUEST['email'];	
	$tel1=$_REQUEST['tel1'];
	$tel2=$_REQUEST['tel2'];
	$address=$_REQUEST['address'];	
	$country=$_REQUEST['country'];
	$result2=false;	
	include('config.php');
	$result = mysqli_query($conn,"SELECT name FROM supplier WHERE id='$sup_id'");
	$row = mysqli_fetch_assoc($result);
	$sup_old_name=$row['name'];
	
	$query="UPDATE `supplier` SET `name`='$sup_name',`email`='$email',`tel1`='$tel1',`tel2`='$tel2',`address`='$address',`country`='$country' WHERE `id`='$sup_id'";
	$result1=mysqli_query($conn,$query);
	if($result1){
		$query="UPDATE `accounts` SET `name`='$sup_name' WHERE `name`='$sup_old_name'";
		$result2=mysqli_query($conn,$query);
	}
	if($result2){
		$message='Supplier was Updated Successfully!';
		return true;
	}else{
		$message='Supplier could not be Updated!';
		return false;
	}
}

function setStatusSupplier(){
	global $message;
	$sup_name=$_REQUEST['status'];
	$id=$_REQUEST['id'];
	if($sup_name=='on'){
		$status=1;
		$msg='Activated';
	}else{
		$status=0;
		$msg='Deactivated';
	}
	include('config.php');
	$query="UPDATE `supplier` SET `status`='$status' WHERE `id`='$id'";
	$result=mysqli_query($conn,$query);
	if($result){
		$query="SELECT name FROM supplier WHERE id='$id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$sup_name=$row[0];
		$query2="UPDATE `accounts` SET `status`='$status' WHERE `name`='$sup_name'";
		$result2=mysqli_query($conn,$query2);
	}
	if($result2){
		$message='Supplier was '.$msg.' Successfully!';
		return true;
	}else{
		$message='Supplier could not be '.$msg.'!';
		return false;
	}
}

function addItemPO(){
	include('config.php');
	$po_number_list[]=array();
	$supplier_list[]=array();
	$timenow=timeNow();
	$user=$_COOKIE['user_id'];
	$itemid=$_GET['itemid'];
	$qty=$_GET['qty'];
	$suplier=$_GET['suplier'];
	
	$query1="SELECT po_number,`supplier` FROM purchaseoder_main WHERE `status`=0";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$po_number_list[]=$row1[0];
		$supplier_list[]=$row1[1];
	}

	$result = mysqli_query($conn,"SELECT itm.po_description,itq.c_price FROM inventory_items itm, inventory_qty itq WHERE itm.id=itq.item AND itm.id='$itemid' LIMIT 1");
	$row = mysqli_fetch_assoc($result);
	$item_desc=$row['po_description'];
	$c_price=$row['c_price'];
		
				
	$po_key=array_search($suplier,$supplier_list);
	if($po_key>-1){
		$po_number=$po_number_list[$po_key];
	}else{
		$query="INSERT INTO `purchaseoder_main` (`supplier`,`created_date`,`status`) VALUES ('$suplier','$timenow','0')";
		$result=mysqli_query($conn,$query);
		$po_number=mysqli_insert_id($conn);
	}
		$query2="INSERT INTO `purchaseoder` (`po_number`,`item`,`item_description`,`c_price`,`qty`,`added_by`,`date`) VALUES ('$po_number','$itemid','$item_desc','$c_price','$qty','$user','$timenow')";
		$result2=mysqli_query($conn,$query2);

	if($result2){
		$message='Done';
	}else{
		$message='Error !';
	}
	return $message;
}

function listPO($limit){
	global $po_number,$po_sup,$po_date,$po_status,$po_cost,$po_status1,$po_color,$po_submited_date;
	$po_number=array();
	include('config.php');
	$query1="SELECT pom.po_number,su.name,date(pom.created_date),pom.`status`,SUM(po.qty * po.c_price),date(pom.submited_date) FROM supplier su, purchaseoder_main pom LEFT JOIN purchaseoder po ON pom.po_number=po.po_number WHERE pom.`supplier`=su.id GROUP BY pom.po_number ORDER BY pom.po_number DESC LIMIT $limit";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$po_number[]=$row1[0];
		$po_sup[]=$row1[1];
		$po_date[]=$row1[2];
		$po_status[]=$row1[3];
		$po_cost[]=$row1[4];
		$po_submited_date[]=$row1[5];
       		switch ($row1[3]){
            	case "0" :
            	$po_status1[]='Unlocked';
            	$po_color[]='red';
            	break;
           	 	case "1" :
            	$po_status1[]='Locked';
            	$po_color[]='blue';
           	 	break;
           	 	case "2" :
            	$po_status1[]='Submited';
            	$po_color[]='green';
           	 	break;
        	}
	}
}

function onePO(){
	global $po_id,$po_item,$po_qty,$po_cost,$po_date,$po_user,$po_category,$po_status;
	$id=$_GET['id'];
	include('config.php');
	$query1="SELECT po.id,po.item_description,po.qty,po.c_price,po.`date`,up.username,itc.name FROM purchaseoder po, userprofile up, inventory_items itm, item_category itc WHERE po.added_by=up.id AND po.item=itm.id AND itm.category=itc.id AND po.po_number='$id' ORDER BY itc.name";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$po_id[]=$row1[0];
		$po_item[]=$row1[1];
		$po_qty[]=$row1[2];
		$po_cost[]=$row1[2]*$row1[3];
		$po_date[]=$row1[4];
		$po_user[]=$row1[5];
		$po_category[]=$row1[6];
	}
	$result = mysqli_query($conn,"SELECT `status` FROM purchaseoder_main WHERE po_number='$id'");
	$row = mysqli_fetch_assoc($result);
	$po_status=$row['status'];
}

function createPO(){
	global $message,$po_no;
	$out=true;
	$supplier=$_POST['supplier'];
	$timenow=timeNow();
	if($supplier==''){$out=false; $message='Please Select a Supplier'; }
	include('config.php');
	if($out){
		$query="INSERT INTO `purchaseoder_main` (`supplier`,`created_date`,`status`) VALUES ('$supplier','$timenow','0')";
		$result=mysqli_query($conn,$query);
		$po_no=mysqli_insert_id($conn);
		if(!$result){$out=false; $message='Error: Purchase Order Cannot be Created'; }
	}
	
	if($out){
		$message='Purchase Order was Created Successfully !';
		return true;
	}else{
		return false;
	}
}

function appendPO(){
	global $message,$po_no;
	$po_no=$_GET['po'];
	$timenow=timeNow();
	$user=$_COOKIE['user_id'];
	$item_po=$_GET['item'];
	$qty_po=$_GET['qty'];
	
	include('config.php');
		$result = mysqli_query($conn,"SELECT itm.po_description,itq.c_price FROM inventory_items itm, inventory_qty itq WHERE itm.id=itq.item AND itm.id='$item_po' LIMIT 1");
		$row = mysqli_fetch_assoc($result);
		$item_desc=$row['po_description'];
		$c_price=$row['c_price'];

		$query2="INSERT INTO `purchaseoder` (`po_number`,`item`,`item_description`,`c_price`,`qty`,`added_by`,`date`) VALUES ('$po_no','$item_po','$item_desc','$c_price','$qty_po','$user','$timenow')";
		$result2=mysqli_query($conn,$query2);

	if($result2){
		$message='Purchase Order was Updated Successfully !';
		return true;
	}else{
		$message='Purchase Order could not be Updated !';
		return false;
	}
}

function updatePO(){
	global $message,$po_no;
	$po_no=$_POST['po_no'];
	$timenow=timeNow();
	$user=$_COOKIE['user_id'];
	include('config.php');
	$query1="SELECT id,item_description,qty FROM purchaseoder WHERE po_number='$po_no'";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$po_id=$row1[0];
		$po_item=$_POST["itm|$po_id"];
		$po_qty=$_POST["qty|$po_id"];
		if(($po_item!=$row1[1])||($po_qty!=$row1[2])){
			$query2="UPDATE `purchaseoder` SET `item_description`='$po_item',`qty`='$po_qty',`added_by`='$user',`date`='$timenow' WHERE `id`='$po_id'";
			$result2=mysqli_query($conn,$query2);
		}
	}
	if($result2){
		$message='Purchase Order was Updated Successfully !';
		return true;
	}else{
		$message='Purchase Order could not be Updated !';
		return false;
	}
}

function removeItemPO(){
	global $message,$po_no;
	$id=$_GET['id'];
	$po_no=$_GET['po_no'];
	include('config.php');
	$query="DELETE FROM `purchaseoder` WHERE `id` ='$id'";
	$result=mysqli_query($conn,$query);
	if($result){
		$message='Item was removed from Purchase Order Successfully !';
		return true;
	}else{
		$message='Item could not be Removed from Purchase Order !';
		return false;
	}
}

function lockPO(){
	global $message;
	$po_no=$_GET['po_no'];
	include('config.php');
	$query="UPDATE `purchaseoder_main` SET `status`='1' WHERE `po_number`='$po_no'";
	$result=mysqli_query($conn,$query);
	if($result){
		$message='Purchase Order was Locked Successfully !';
		return true;
	}else{
		$message='Purchase Order Could Not be Locked !';
		return false;
	}
}

function unlockPO(){
	global $message;
	$po_no=$_GET['po_no'];
	include('config.php');
	
	$result = mysqli_query($conn,"SELECT `supplier` FROM purchaseoder_main WHERE po_number='$po_no'");
	$row = mysqli_fetch_assoc($result);
	$supplier=$row['supplier'];
	$result = mysqli_query($conn,"SELECT COUNT(po_number) as `count` FROM purchaseoder_main WHERE `supplier`='$supplier' AND `status`='0'");
	$row = mysqli_fetch_assoc($result);
	$count=$row['count'];
	if($count==0){
		$query1="UPDATE `purchaseoder_main` SET `status`='0' WHERE `po_number`='$po_no'";
		$result1=mysqli_query($conn,$query1);
		if($result1){
			$message='Purchase Order was Unlocked Successfully !';
			return true;
		}else{
			$message='Purchase Order Could Not be Unlocked !';
			return false;
		}
	}else{
		$message='Only One PO can be Unlocked for a One Supplier at a time !';
		return false;
	}
}

function downloadPO($systemid){
	$po_no=$_GET['id'];
	$store_id=$_GET['store_id'];
	$user=$_COOKIE['user'];
	$inf_company=inf_company(1);
	//$systemid=inf_systemid(1);
	$inf_web=inf_web();
	if($store_id!='') $extra=true; else $extra=false;
	$i=0;
	include('config.php');
	
	$query="SELECT su.name,itm.code,po.item_description,po.qty,itc.name,itm.id FROM purchaseoder_main pom, purchaseoder po, supplier su, inventory_items itm, item_category itc WHERE po.item=itm.id AND itm.category=itc.id AND pom.po_number=po.po_number AND pom.`supplier`=su.id AND pom.po_number='$po_no' ORDER BY itc.name";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$po_sup=$row[0];
		$po_item_code[$i]=$row[1];
		$po_item[$i]=$row[2];
		$po_qty[$i]=$row[3];
		$po_category[$i]=$row[4];
		$itm_id_tmp=$row[5];
		$po_wprice[$i]=0;
		$po_drawer[$i]='';
		if($extra){
			$query2="SELECT w_price,drawer_no FROM inventory_qty WHERE item='$itm_id_tmp' AND location='$store_id'";
			$result2 = mysqli_query($conn,$query2);
			$row2 = mysqli_fetch_row($result2);
			$wprice_q=$row2[0];
			$po_drawer[$i]=$row2[1];
			$query2="SELECT w_price FROM inventory_new WHERE item='$itm_id_tmp' AND store='$store_id'";
			$result2 = mysqli_query($conn,$query2);
			$row2 = mysqli_fetch_row($result2);
			$wprice_n=$row2[0];
			if($wprice_n!='') $po_wprice[$i]=$wprice_n; else $po_wprice[$i]=$wprice_q;
		}
		$i++;
	}
	
	if($systemid==13){
		require_once ('plugin/PHPExcel-1.8/production/Purchase_Order13.php');
	}else{
		require_once ('components/purchaseOrder/view/excel_po.php');
		#require_once ('plugin/PHPExcel-1.8/production/Purchase_Order.php');
	}
}

function emailPO($systemid){
	global $message,$po_no;
	$po_no=$_GET['id'];
	$store=$_COOKIE['store'];
	$message='Email Could Not be Sent !';
	$timenow=timeNow();
	$inf_company=inf_company(1);
	$inf_url_primary=inf_url_primary();
	$inf_web=inf_web();
	$inf_from_email=inf_from_email();
	$inf_replyto_email=inf_replyto_email();
	$out=true;
	include('config.php');
	//---------------------------------------------------//
	$result = mysqli_query($conn2,"SELECT value FROM settings WHERE setting='smtp_server'");
	$row = mysqli_fetch_assoc($result);
	$smtp_server=$row['value'];
	$result = mysqli_query($conn2,"SELECT value FROM settings WHERE setting='smtp_port'");
	$row = mysqli_fetch_assoc($result);
	$smtp_port=$row['value'];
	$result = mysqli_query($conn2,"SELECT value FROM settings WHERE setting='smtp_username'");
	$row = mysqli_fetch_assoc($result);
	$smtp_username=$row['value'];
	$result = mysqli_query($conn2,"SELECT value FROM settings WHERE setting='smtp_password'");
	$row = mysqli_fetch_assoc($result);
	$smtp_password=$row['value'];
	$query="SELECT shop_name FROM stores WHERE id='$store'";
	$result=mysqli_query($conn2,$query);
	$row=mysqli_fetch_row($result);
	$shop_name=$row[0];
	//--------------------------------------------------//
	
	$result = mysqli_query($conn,"SELECT su.email FROM purchaseoder_main pom, supplier su WHERE pom.`supplier`=su.id AND pom.po_number='$po_no'");
	$row = mysqli_fetch_assoc($result);
	$su_email=$row['email'];
	if($su_email==''){ $out=false; $message='Error: No Email Address is Configured for the Supplier'; }
	
	if($inf_url_primary!=$_SERVER['SERVER_NAME']){ $out=false; $message='Error: Emails can be Dispatched from the Production System Only'; }
	
	if($out){
		$bodytext='Please find the attched purchase order';
		$subject='Purchase Order: '.str_pad($po_no, 7, "0", STR_PAD_LEFT);
		require 'plugin/vendor/autoload.php';
	    $mail = new PHPMailer\PHPMailer\PHPMailer();	
	    try {
		    // Specify the SMTP settings.
		    $mail->isSMTP();
			$mail->addReplyTo($inf_replyto_email, $shop_name);
		    $mail->setFrom($inf_from_email, $inf_company);
		    $mail->Username   = $smtp_username;
		    $mail->Password   = $smtp_password;
		    $mail->Host       = $smtp_server;
		    $mail->Port       = $smtp_port;
		    $mail->SMTPAuth   = true;
		    $mail->SMTPSecure = 'tls';
		    $mail->addCustomHeader('X-SES-CONFIGURATION-SET', '');
		
		    // Specify the message recipients.
		    $mail->addAddress($su_email);
		    $mail->addCC($inf_replyto_email);
		    // You can also add CC, BCC, and additional To recipients here.
		
		    // Specify the content of the message.
		    $mail->isHTML(true);
		    $mail->Subject    = $subject;
		    $mail->Body       = $bodytext;
		    $mail->AltBody    = '';
			if($systemid==13)	$mail->AddAttachment( 'plugin/PHPExcel-1.8/production/Purchase_Order13.xls' );
			else	$mail->AddAttachment( 'plugin/PHPExcel-1.8/production/Purchase_Order.xls' );
		    $email_send_st=$mail->Send();
			    $message="Email was Sent Successfully";
		} catch (phpmailerException $e) {
		    $message="An error occurred. {$e->errorMessage()}"; //Catch errors from PHPMailer.
		} catch (Exception $e) {
		    $message="Email not sent. {$mail->ErrorInfo}"; //Catch errors from Amazon SES.
		}
		
		
		if($email_send_st){
			$query1="UPDATE `purchaseoder_main` SET `submited_date`='$timenow',`status`='2' WHERE `po_number`='$po_no'";
			mysqli_query($conn,$query1);
			$message='Email was Sent Successfully !';
			$out=true;
		}else{
			//$message="Email Could Not be Sent";
			$out=false;
		}
	}
	return $out;
}
/*
function emailPO($systemid){
	global $message,$po_no;
	$po_no=$_GET['id'];
	$timenow=timeNow();
	$inf_company=inf_company(1);
	$inf_web=inf_web();
	$inf_from_email=inf_from_email();
	$inf_replyto_email=inf_replyto_email()();
	$inf_to_email=inf_to_email();
	include('config.php');
	$result = mysqli_query($conn,"SELECT su.email FROM purchaseoder_main pom, supplier su WHERE pom.`supplier`=su.id AND pom.po_number='$po_no'");
	$row = mysqli_fetch_assoc($result);
	$su_email=$row['email'];
	
	require_once('plugin/PHPMailer/class.phpmailer.php');
	$bodytext='Please find the attched purchase order';
	
	$email = new PHPMailer();
	$email->From      = $inf_from_email;
	$email->FromName  = $inf_company;
	$email->Subject   = 'Purchase Order: '.str_pad($po_no, 7, "0", STR_PAD_LEFT);
	$email->Body      = $bodytext;
	$email->AddAddress( $su_email );
	$email->addCC( $inf_to_email );
	
	if($systemid==13)	$email->AddAttachment( 'plugin/PHPExcel-1.8/production/Purchase_Order13.xls' );
	else	$email->AddAttachment( 'plugin/PHPExcel-1.8/production/Purchase_Order.xls' );
	
	if($email->Send()){
		$query1="UPDATE `purchaseoder_main` SET `submited_date`='$timenow',`status`='2' WHERE `po_number`='$po_no'";
		mysqli_query($conn,$query1);
		$message='Email was Sent Successfully !';
		return true;
	}else{
		$message='Email Could Not be Sent !';
		return false;
	}
}
*/
?>