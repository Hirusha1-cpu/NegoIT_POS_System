<?php
function debugStart($debug_input1,$debug_input2){
	$debug_proceed=false;
	$debug_timezone=5.5;
	$debug_id=0;
	include('config.php');
	if($_REQUEST['components']=='billing'){
		if($_REQUEST['action']=='apend_bill'){
			$debug_proceed=true;
			$debug_item=$_REQUEST['itemid'];
			$debug_store=$_COOKIE['store'];
			$debug_actionqty=-$_REQUEST['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Bill-Add_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
			$debug_actionid=0;
		}
		if($_REQUEST['action']=='bill_item_gpdate'){
			$debug_proceed=true;
			$debug_actionid=$_REQUEST['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT item FROM bill WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=-$_REQUEST['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Bill-Update_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='bill_item_remove'){
			$debug_proceed=true;
			$debug_actionid=$_REQUEST['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT item,qty FROM bill WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Bill-Remove_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='delete'){
			$debug_proceed=true;
			$debug_actionid=$debug_input1;
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT item,qty FROM bill WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Bill-Delete';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='apend_return'){
			$debug_proceed=true;
			$debug_item=$_REQUEST['return_itemid'];
			$debug_store=$_COOKIE['store'];
			$debug_actionqty=-$_REQUEST['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Return-Add_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
			$debug_actionid=0;
		}
		if($_REQUEST['action']=='return_item_gpdate'){
			$debug_proceed=true;
			$debug_actionid=$_REQUEST['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT item FROM `return` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=$_REQUEST['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Return-Update_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='return_item_remove'){
			$debug_proceed=true;
			$debug_actionid=$_REQUEST['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT return_item,qty FROM `return` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['return_item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Return-Remove_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='delete_return'){
			$debug_proceed=true;
			$debug_actionid=$debug_input1;
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT return_item,qty FROM `return` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['return_item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Return-Delete';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='add_warranty_inv'){
			$debug_proceed=true;
			$debug_item=$_REQUEST['itemid'];
			$debug_store=$_COOKIE['store'];
			$debug_actionqty=-1;
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Warranty-Allocate_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
			$debug_actionid=0;
		}
		if($_REQUEST['action']=='return_warranty_inv'){
			$debug_proceed=true;
			$debug_item=$debug_input1;
			$debug_store=$_COOKIE['store'];
			$debug_actionqty=$debug_input2;
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Warranty-Return_Item_to_Inv';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
			$debug_actionid=0;
		}
	}
	
	if($_REQUEST['components']=='bill2'){
		if($_REQUEST['action']=='create_invoice'){
			$debug_proceed=true;
			$input_list=explode("|",$debug_input1);
			$debug_item=$input_list[0];
			$debug_store=$input_list[1];
			$debug_actionqty=-$debug_input2;
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Bill2-Add_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
			$debug_actionid=0;
		}
		if($_REQUEST['action']=='bill_item_gpdate'){
			$debug_proceed=true;
			$debug_actionid=$_REQUEST['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT item FROM bill WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=-$_REQUEST['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Bill2-Update_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='bill_item_remove'){
			$debug_proceed=true;
			$debug_actionid=$_REQUEST['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT item,qty FROM bill WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Bill2-Remove_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='delete'){
			$debug_proceed=true;
			$debug_actionid=$debug_input1;
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT item,qty FROM bill WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Bill2-Delete';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='apend_return'){
			$debug_proceed=true;
			$debug_item=$_REQUEST['return_itemid'];
			$debug_store=$_COOKIE['store'];
			$debug_actionqty=-$_REQUEST['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Return-Add_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
			$debug_actionid=0;
		}
		if($_REQUEST['action']=='return_item_gpdate'){
			$debug_proceed=true;
			$debug_actionid=$_REQUEST['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT item FROM `return` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=$_REQUEST['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Return-Update_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='return_item_remove'){
			$debug_proceed=true;
			$debug_actionid=$_REQUEST['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT return_item,qty FROM `return` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['return_item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Return-Remove_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='delete_return'){
			$debug_proceed=true;
			$debug_actionid=$debug_input1;
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT return_item,qty FROM `return` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['return_item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Return-Delete';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='add_warranty_inv'){
			$debug_proceed=true;
			$debug_item=$_REQUEST['itemid'];
			$debug_store=$_COOKIE['store'];
			$debug_actionqty=-1;
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Warranty-Allocate_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
			$debug_actionid=0;
		}
		if($_REQUEST['action']=='return_warranty_inv'){
			$debug_proceed=true;
			$debug_item=$debug_input1;
			$debug_store=$_COOKIE['store'];
			$debug_actionqty=$debug_input2;
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Warranty-Return_Item_to_Inv';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
			$debug_actionid=0;
		}
	}
	
	if($_REQUEST['components']=='order_process'){
		if($_REQUEST['action']=='move_unic_inv'){
			$debug_proceed=true;
			$debug_actionid=$_GET['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT item,qty FROM `return` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='OderProcess-Moveto_Inv';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='process_return'){
			$debug_proceed=true;
			$debug_actionid=0;
			$debug_itqid=$debug_input1;
			$debug_actionqty=$debug_input2;
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT item,qty FROM inventory_qty WHERE id='$debug_itqid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_startqty=$row['qty'];
			$debug_action='OderProcess-Process_return';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
	}
	
	if($_REQUEST['components']=='trans'){
		if($_REQUEST['action']=='apend_gtn'){
			$debug_proceed=true;
			$debug_item=$debug_input1;
			$debug_store=$_COOKIE['store'];
			$debug_actionqty=-$debug_input2;
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Trans-Add_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
			$debug_actionid=0;
		}
		if($_REQUEST['action']=='gtn_item_gpdate'){
			$debug_proceed=true;
			$debug_actionid=$_REQUEST['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT item FROM `transfer` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=-$_REQUEST['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Trans-Update_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='gtn_item_remove'){
			$debug_proceed=true;
			$debug_actionid=$_REQUEST['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT item,qty FROM `transfer` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Trans-Remove_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='delete'){
			$debug_proceed=true;
			$debug_actionid=$debug_input1;
			$debug_store=$debug_input2;
			$result = mysqli_query($conn,"SELECT item,qty FROM `transfer` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Trans-Delete';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='reject'){
			$debug_proceed=true;
			$debug_actionid=$debug_input1;
			$debug_store=$debug_input2;
			$result = mysqli_query($conn,"SELECT item,qty FROM `transfer` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Trans-Reject';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='approve'){
			$debug_proceed=true;
			$debug_actionid=$debug_input1;
			$debug_store=$debug_input2;
			$result = mysqli_query($conn,"SELECT item,qty FROM `transfer` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_actionqty=$row['qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Trans-Approve';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
	}
	
	if($_REQUEST['components']=='inventory'){
		if($_REQUEST['action']=='add_qty'){
			$debug_proceed=true;
			$debug_item=$debug_input1;
			$debug_store=$_COOKIE['store'];
			$debug_actionqty=$debug_input2;
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Inventory-Add_Qty';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
			$debug_actionid=0;
		}
		if($_REQUEST['action']=='shipment_item_gpdate'){
			$debug_proceed=true;
			$debug_actionid=$_REQUEST['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT inv_item FROM `inventory_shipment` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['inv_item'];
			$debug_actionqty=$_REQUEST['qty_new'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Inventory-Update_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='shipment_item_remove'){
			$debug_proceed=true;
			$debug_actionid=$_REQUEST['id'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT inv_item,added_qty FROM `inventory_shipment` WHERE id='$debug_actionid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['inv_item'];
			$debug_actionqty=-$row['added_qty'];
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Inventory-Remove_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='delete_unic'){
			$debug_proceed=true;
			$debug_sn=$_GET['sn'];
			$debug_store=$_COOKIE['store'];
			$result = mysqli_query($conn,"SELECT id,itq_id FROM inventory_unic_item WHERE `status`!='2' AND sn='$debug_sn' LIMIT 1");
			$row = mysqli_fetch_assoc($result);
			$debug_actionid=$row['id'];
			$debug_itqid=$row['itq_id'];
			$debug_actionqty=-1;
			$result = mysqli_query($conn,"SELECT item,qty FROM inventory_qty WHERE id='$debug_itqid'");
			$row = mysqli_fetch_assoc($result);
			$debug_item=$row['item'];
			$debug_startqty=$row['qty'];
			$debug_action='Inventory-Delete_Unic';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
		}
		if($_REQUEST['action']=='edit_item2'){
			$debug_proceed=true;
			$debug_actionid=0;
			$debug_item=$_GET['item_id'];
			$debug_store=$debug_input1;
			$debug_actionqty=$debug_input2;
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Inventory-Edit_Item';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
			if($debug_startqty==$debug_actionqty){
				$debug_proceed=false;
				$debug_id=0;
			}
			if(($debug_startqty==NULL) && ($debug_actionqty==0)){
				$debug_proceed=false;
				$debug_id=0;
			}
		}
	}
	
	if($_REQUEST['components']=='manager'){
		if($_REQUEST['action']=='adjust_qty'){
			$debug_proceed=true;
			$debug_actionid=0;
			$debug_item=$_REQUEST['item_id'];
			$debug_store=$debug_input1;
			$debug_actionqty=$debug_input2;
			$result = mysqli_query($conn,"SELECT id,qty FROM inventory_qty WHERE item='$debug_item' AND location='$debug_store'");
			$row = mysqli_fetch_assoc($result);
			$debug_itqid=$row['id'];
			$debug_startqty=$row['qty'];
			$debug_action='Manager-Adjust_Qty';
			$debug_date=date("Y-m-d H:i:s",time()+(60*60*$debug_timezone));
			if(($debug_startqty==NULL) && ($debug_actionqty==0)){
				$debug_proceed=false;
				$debug_id=0;
			}
		}
	}
	
	if($debug_proceed){
		$debug_salesman=$_COOKIE['user_id'];
		$result = mysqli_query($conn,"SELECT sum(qty) as `qty` FROM inventory_new WHERE store='$debug_store' AND item='$debug_item'");
		$row = mysqli_fetch_assoc($result);
		$debug_startqty+=$row['qty'];
		
		$result = mysqli_query($conn,"SELECT unic FROM inventory_items WHERE id='$debug_item'");
		$row = mysqli_fetch_assoc($result);
		$debug_unic=$row['unic'];
		if($debug_unic==1){
			$result = mysqli_query($conn,"SELECT count(id) as `qty` FROM inventory_unic_item WHERE itq_id='$debug_itqid' AND `status`=0");
			$row = mysqli_fetch_assoc($result);
			$debug_start_uqty=$row['qty'];
		}else $debug_start_uqty=0;
		
		$query="INSERT INTO `debug` (`item`,`itq_id`,`start_qty`,`action_qty`,`unic`,`start_uqty`,`action`,`action_id`,`date`,`salesman`,`store`) VALUES ('$debug_item','$debug_itqid','$debug_startqty','$debug_actionqty','$debug_unic','$debug_start_uqty','$debug_action','$debug_actionid','$debug_date','$debug_salesman','$debug_store')";
		mysqli_query($conn,$query);
		$debug_id=mysqli_insert_id($conn);
	}
	return $debug_id;
}






function debugEnd($debug_id,$debug_status){
$debug_actionqry="";
$debug_store=$_COOKIE['store'];
include('config.php');
if($debug_id!=0){
	$debug_proceed=false;
	if($_REQUEST['components']=='billing'){
		$debug_proceed=true;
		if($_REQUEST['action']=='apend_bill'){
			$result = mysqli_query($conn,"SELECT MAX(id) as `lastid` FROM bill");
			$row = mysqli_fetch_assoc($result);
			$debug_actionid=$row['lastid'];
			$debug_actionqry=",`action_id`='$debug_actionid'";
		}else
		if($_REQUEST['action']=='add_warranty_inv'){
			$result = mysqli_query($conn,"SELECT MAX(id) as `lastid` FROM warranty");
			$row = mysqli_fetch_assoc($result);
			$debug_actionid=$row['lastid'];
			$debug_actionqry=",`action_id`='$debug_actionid'";
		}else
		if($_REQUEST['action']=='return_warranty_inv'){
			$result = mysqli_query($conn,"SELECT MAX(id) as `lastid` FROM `inventory_shipment`");
			$row = mysqli_fetch_assoc($result);
			$debug_actionid=$row['lastid'];
			$debug_actionqry=",`action_id`='$debug_actionid'";
		}else
		if($_REQUEST['action']=='apend_return'){
			$result = mysqli_query($conn,"SELECT MAX(id) as `lastid` FROM `return`");
			$row = mysqli_fetch_assoc($result);
			$debug_actionid=$row['lastid'];
			$debug_actionqry=",`action_id`='$debug_actionid'";
		}
	}
	if($_REQUEST['components']=='bill2'){
		$debug_proceed=true;
		if($_REQUEST['action']=='create_invoice'){
			$result = mysqli_query($conn,"SELECT MAX(id) as `lastid` FROM bill");
			$row = mysqli_fetch_assoc($result);
			$debug_actionid=$row['lastid'];
			$debug_actionqry=",`action_id`='$debug_actionid'";
		}else
		if($_REQUEST['action']=='delete'){
			$query="SELECT bm.mapped_inventory FROM bill_main bm, bill bi, debug db WHERE bm.invoice_no=bi.invoice_no AND db.action_id=bi.id AND db.id='$debug_id'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$debug_store=$row[0];
		}else
		if($_REQUEST['action']=='add_warranty_inv'){
			$result = mysqli_query($conn,"SELECT MAX(id) as `lastid` FROM warranty");
			$row = mysqli_fetch_assoc($result);
			$debug_actionid=$row['lastid'];
			$debug_actionqry=",`action_id`='$debug_actionid'";
		}else
		if($_REQUEST['action']=='return_warranty_inv'){
			$result = mysqli_query($conn,"SELECT MAX(id) as `lastid` FROM `inventory_shipment`");
			$row = mysqli_fetch_assoc($result);
			$debug_actionid=$row['lastid'];
			$debug_actionqry=",`action_id`='$debug_actionid'";
		}else
		if($_REQUEST['action']=='apend_return'){
			$result = mysqli_query($conn,"SELECT MAX(id) as `lastid` FROM `return`");
			$row = mysqli_fetch_assoc($result);
			$debug_actionid=$row['lastid'];
			$debug_actionqry=",`action_id`='$debug_actionid'";
		}
	}
	
	if($_REQUEST['components']=='order_process'){
		$debug_proceed=true;
	}	

	if($_REQUEST['components']=='trans'){
		$debug_proceed=true;
		if($_REQUEST['action']=='apend_gtn'){
			$result = mysqli_query($conn,"SELECT MAX(id) as `lastid` FROM `transfer`");
			$row = mysqli_fetch_assoc($result);
			$debug_actionid=$row['lastid'];
			$debug_actionqry=",`action_id`='$debug_actionid'";
		}else
		if($_REQUEST['action']=='reject'){
			$result = mysqli_query($conn,"SELECT tr.from_stores FROM debug dg, transfer tr WHERE tr.id=dg.action_id AND dg.id='$debug_id'");
			$row = mysqli_fetch_assoc($result);
			$debug_store=$row['from_stores'];
		}
	}

	if($_REQUEST['components']=='inventory'){
		$debug_proceed=true;
		if($_REQUEST['action']=='add_qty'){
			$result = mysqli_query($conn,"SELECT MAX(id) as `lastid` FROM `inventory_shipment`");
			$row = mysqli_fetch_assoc($result);
			$debug_actionid=$row['lastid'];
			$debug_actionqry=",`action_id`='$debug_actionid'";
		}else
		if($_REQUEST['action']=='edit_item'){
			$result = mysqli_query($conn,"SELECT itq.location FROM debug dg, inventory_qty itq WHERE dg.itq_id=itq.id AND dg.id='$debug_id'");
			$row = mysqli_fetch_assoc($result);
			$debug_store=$row['location'];
		}
	}

	if($_REQUEST['components']=='manager'){
		$debug_proceed=true;
		if($_REQUEST['action']=='adjust_qty'){
			$result = mysqli_query($conn,"SELECT itq.location FROM debug dg, inventory_qty itq WHERE dg.itq_id=itq.id AND dg.id='$debug_id'");
			$row = mysqli_fetch_assoc($result);
			$debug_store=$row['location'];
		}
	}
	
	if($debug_proceed){
		$result = mysqli_query($conn,"SELECT itq.qty,dg.unic,itq.id FROM debug dg, inventory_qty itq WHERE itq.item=dg.item AND itq.location='$debug_store' AND dg.id='$debug_id'");
		$row = mysqli_fetch_assoc($result);
		$debug_endqty=$row['qty'];
		$debug_unic=$row['unic'];
		$debug_itqid=$row['id'];
		
		$result = mysqli_query($conn,"SELECT sum(itn.qty) as `qty` FROM debug dg ,inventory_new itn WHERE dg.item=itn.item AND itn.store='$debug_store' AND dg.id='$debug_id'");
		$row = mysqli_fetch_assoc($result);
		$debug_endqty+=$row['qty'];
		
		if($debug_unic==1){
			$result = mysqli_query($conn,"SELECT count(id) as `qty` FROM inventory_unic_item WHERE itq_id='$debug_itqid' AND `status`=0");
			$row = mysqli_fetch_assoc($result);
			$debug_end_uqty=$row['qty'];
		}else $debug_end_uqty=0;
		
		$debug_salesman=$_COOKIE['user_id'];
		$query="UPDATE `debug` SET `end_qty`='$debug_endqty',`end_uqty`='$debug_end_uqty',`action_result`='$debug_status' $debug_actionqry  WHERE `id`='$debug_id'";
		mysqli_query($conn,$query);
	}
}
}

?>