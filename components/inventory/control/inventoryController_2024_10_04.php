<?php

$action=$_REQUEST['action'];
if(passwordExpire()){
   header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');
}
if(!(($systemid!=1)||($systemid==1 && $sub_system==0 )||($systemid==1 && $sub_system==1 ))  && !(($action=='show_all_item')||($action=='export_shipment')||($action=='export_unic_list'))){
   header('Location: index.php?components=inventory&action=show_all_item&category=1&store='.$_COOKIE['store'].'&type=5');
}else{
   if(($systemid==1 && ($sub_system!=0 && $sub_system!=1 && $sub_system!=2)) && $action=='show_all_item' && ($_GET['store']!=$_COOKIE['store'])){
      header('Location: index.php?components=inventory&action=show_all_item&category=1&store='.$_COOKIE['store'].'&type=5');
   }
}

switch ($action){
   case "show_add_item" :
      include_once  'components/inventory/modle/inventoryModule.php';
      currentStore();
   	getCategory($sub_system);
   	getSupplier();
      getTags();
      getUnitTypes(1);
      if(isMobile())
      		include_once  'components/inventory/view/m_addItem.php';
      else
      		include_once  'components/inventory/view/addItem.php';
   break;

   case "add_item" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(addItem($sub_system))
      		header('Location: index.php?components=inventory&action=show_add_item&type='.$type.'&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=show_add_item&type='.$type.'&message='.$message.'&re=fail');
   break;

   case "show_add_shipment" :
   if($systemid==1){
   		$today=dateNow();
      		header("Location: index.php?components=inventory&action=add_shipment&ship_date=$today&suplier=1&ship_inv_no=0&ship_inv_date=$today&ship_inv_dudate=$today&sub=".$_REQUEST['sub']);
   }else{
      include_once  'components/inventory/modle/inventoryModule.php';
      $shipment_no=getShiomentID();
      getSupplier();
      if(isMobile())
      		include_once  'components/inventory/view/m_addShipment.php';
      else
      		include_once  'components/inventory/view/addShipment.php';
   }
   break;

   // added by nirmal 21_08_11
   case "show_add_shipment_tmp" :
   if($systemid==1){
   		$today=dateNow();
      		header("Location: index.php?components=inventory&action=add_shipment_tmp&ship_date=$today&suplier=1&ship_inv_no=0&ship_inv_date=$today&ship_inv_dudate=$today&sub=".$_REQUEST['sub']);
   }else{
      include_once  'components/inventory/modle/inventoryModule.php';
      $shipment_no=getShipmentIDTmp();
      getSupplier();
      if(isMobile())
      		include_once  'components/inventory/view/m_addShipmentTmp.php';
      else
      		include_once  'components/inventory/view/addShipmentTmp.php';
   }
   break;

   // added by nirmal 21_08_11
   case "add_shipment_tmp" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(addShipmentTmp($sub_system))
      		header('Location: index.php?components=inventory&action='.$_REQUEST['sub'].'&shipment_no='.$shipment_no);
   	else
   		header('Location: index.php?components=inventory&action=show_add_shipment_tmp&sub='.$_REQUEST['sub'].'&message='.$message.'&re=fail');
   break;

   // updated by nirmal 21_08_17
   case "add_shipment" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(addShipment($sub_system,$_REQUEST['ship_date'],$_REQUEST['suplier'],$_REQUEST['ship_inv_no'],$_REQUEST['ship_inv_date'],$_REQUEST['ship_inv_dudate'],$_REQUEST['sub'])){
         header('Location: index.php?components=inventory&action='.$_REQUEST['sub'].'&shipment_no='.$shipment_no);
      }else{
         header('Location: index.php?components=inventory&action=show_add_shipment&sub='.$_REQUEST['sub'].'&message='.$message.'&re=fail');
      }
   break;

   // added by nirmal 21_08_11
   case "show_add_qty_tmp":
      include_once  'components/inventory/modle/inventoryModule.php';
      currentStore();
      getShipmentItemsTmp($_REQUEST['shipment_no']);
      if(isMobile())
      	include_once  'components/inventory/view/m_addQtyTmp.php';
      else
      	include_once  'components/inventory/view/addQtyTmp.php';
   break;

   // added by nirmal 21_08_11
   case "list_qty_items":
      include_once  'components/inventory/modle/inventoryModule.php';
      listCodeData('nounic');
      include_once  'template/ajax_list.php';
   break;

   case "show_add_qty" :
      include_once  'components/inventory/modle/inventoryModule.php';
      currentStore();
      getItems1('nounic');
      getShipmentItems();
      if(isMobile())
      		include_once  'components/inventory/view/m_addQty.php';
      else
      		include_once  'components/inventory/view/addQty.php';
   break;

   case "get_item_data" :
      include_once  'components/inventory/modle/inventoryModule.php';
      print getItemData();
   break;

   case "show_add_unic" :
      include_once  'components/inventory/modle/inventoryModule.php';
      currentStore();
      getItems1('unic');
      getShipmentItems();
      // getUnicList();
       if(isMobile())
     		include_once  'components/inventory/view/m_addUnic.php';
       else
     		include_once  'components/inventory/view/addUnic.php';
   break;

   // added by nirmal 21_08_11
   case "list_unic_items":
      include_once  'components/inventory/modle/inventoryModule.php';
      listCodeData('unic');
      include_once  'template/ajax_list.php';
   break;

   // added by nirmal 21_08_11, updated by nirmal 21_9_2
   case "show_add_unic_tmp" :
      include_once  'components/inventory/modle/inventoryModule.php';
      currentStore();
      getShipmentUnicItemsTmp($_REQUEST['shipment_no'],$_REQUEST['action']);
      if(isMobile())
     		include_once  'components/inventory/view/m_addUnicTmp.php';
      else
     		include_once  'components/inventory/view/addUnicTmp.php';
   break;

   // added by nirmal 21_08_30
   case "list_sn_tmp":
      include_once  'components/inventory/modle/inventoryModule.php';
      print getSnListTmp();
   break;

   case "show_unic" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getUnicList();
      if(isMobile())
         include_once  'components/inventory/view/m_unic_items.php';
     else
         include_once  'components/inventory/view/unic_items.php';
   break;

   case "update_one_unic_price" :
      include_once  'components/inventory/modle/inventoryModule.php';
      print updateOneUnicPrice();
   break;

   case "update_bulk_unic_price" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(updateBulkUnicPrice())
      		header('Location: index.php?components=inventory&action=show_unic&shipment_no='.$_GET['shipment_no'].'&ins_id='.$_GET['ins_id'].'&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=show_unic&shipment_no='.$_GET['shipment_no'].'&ins_id='.$_GET['ins_id'].'&message='.$message.'&re=fail');
   break;

   case "show_edit_unic" :
      if(isMobile())
         include_once  'components/inventory/view/m_editUnic.php';
      else
         include_once  'components/inventory/view/editUnic.php';
   break;

   case "edit_unic" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(editUnic())
      		header('Location: index.php?components=inventory&action=show_unic&shipment_no='.$shipment_no.'&ins_id='.$ins_id.'&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=show_unic&shipment_no='.$shipment_no.'&ins_id='.$ins_id.'&message='.$message.'&re=fail');
   break;

   // added by nirmal 21_9_1
   case "remove_sn_tmp":
      include_once  'components/inventory/modle/inventoryModule.php';
       if(removeSnTmp($_GET['sn_id']))
      	header('Location: index.php?components=inventory&action=show_add_unic_tmp&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
      else
      	header('Location: index.php?components=inventory&action=show_add_unic_tmp&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
   break;

   case "delete_unic" :
      include_once  'components/inventory/modle/inventoryModule.php';
      $debug_id=debugStart(0,0);
      if(deleteUnic($_GET['ins_id'],$_GET['sn'])){
      		debugEnd($debug_id,'success');
      		header('Location: index.php?components=inventory&action=show_unic&shipment_no='.$shipment_no.'&ins_id='.$ins_id.'&message='.$message.'&re=success');
      	}else{
      		debugEnd($debug_id,'fail');
      		header('Location: index.php?components=inventory&action=show_unic&shipment_no='.$shipment_no.'&ins_id='.$ins_id.'&message='.$message.'&re=fail');
   	}
   break;

   // added by nirmal 21_08_11
   case "add_qty_tmp" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(addQtyTmp($_REQUEST['shipment_no'],$_REQUEST['unic'],$_REQUEST['item_id'],$_REQUEST['qty'],$_REQUEST['c_price1'],$_REQUEST['w_price1'],$_REQUEST['r_price1'],$_REQUEST['c_price2'],$_REQUEST['w_price2'],$_REQUEST['r_price2']))
      		header('Location: index.php?components=inventory&action='.$action.'&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action='.$action.'&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
   break;

   case "add_qty" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(addQty($_REQUEST['shipment_no'], $_REQUEST['unic'],$_REQUEST['item_id'],$_REQUEST['qty'],$_REQUEST['c_price1'],$_REQUEST['w_price1'],$_REQUEST['r_price1'],$_REQUEST['c_price2'],$_REQUEST['w_price2'],$_REQUEST['r_price2']))
      		header('Location: index.php?components=inventory&action='.$action.'&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action='.$action.'&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
   break;

   // added by nirmal 21_08_26
   case "shipment_item_update_tmp" :
      include_once  'components/inventory/modle/inventoryModule.php';
      $debug_id=debugStart(0,0);
      if(updateShipmentItemTmp()){
      		debugEnd($debug_id,'success');
      		header('Location: index.php?components=inventory&action=show_add_qty_tmp&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
      	}else{
      		debugEnd($debug_id,'fail');
      		header('Location: index.php?components=inventory&action=show_add_qty_tmp&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
   	}
   break;

   case "shipment_item_gpdate" :
      include_once  'components/inventory/modle/inventoryModule.php';
      $debug_id=debugStart(0,0);
      if(updateShipmentItem()){
      		debugEnd($debug_id,'success');
      		header('Location: index.php?components=inventory&action=show_add_qty&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
      	}else{
      		debugEnd($debug_id,'fail');
      		header('Location: index.php?components=inventory&action=show_add_qty&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
   	}
   break;

   // added by nirmal 21_08_26
   case "shipment_qty_item_remove_tmp" :
      include_once  'components/inventory/modle/inventoryModule.php';
      $debug_id=debugStart(0,0);
      if(removeShipmentQtyItemTmp()){
      		debugEnd($debug_id,'success');
      		header('Location: index.php?components=inventory&action=show_add_qty_tmp&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
      	}else{
      		debugEnd($debug_id,'fail');
      		header('Location: index.php?components=inventory&action=show_add_qty_tmp&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
   	}
   break;

   // added by nirmal 21_9_2
   case "shipment_unic_item_remove_tmp" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(removeUnicShipmentItemTmp()){
      		header('Location: index.php?components=inventory&action=show_add_unic_tmp&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
      	}else{
      		header('Location: index.php?components=inventory&action=show_add_unic_tmp&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
   	}
   break;

   case "shipment_item_remove" :
      include_once  'components/inventory/modle/inventoryModule.php';
      $debug_id=debugStart(0,0);
      if(removeShipmentItem()){
      		debugEnd($debug_id,'success');
      		header('Location: index.php?components=inventory&action=show_add_qty&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
      	}else{
      		debugEnd($debug_id,'fail');
      		header('Location: index.php?components=inventory&action=show_add_qty&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
   	}
   break;

   case "auth_delete_shipment" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(authDeleteShipment()){
      		header('Location: index.php?components=inventory&action=one_shipment&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
      }else{
      		header('Location: index.php?components=inventory&action=one_shipment&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
      }
   break;

   case "delete_shipment" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(deleteShipment()){
      		header('Location: index.php?components=inventory&action=shipmentlist&month='.date("Y-m",time()).'&message='.$message.'&re=success');
      }else{
      		header('Location: index.php?components=inventory&action=one_shipment&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
      }
   break;

   // update by nirmal 21_08_25
   case "shipmentlist" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getShipmentList($systemid,$sub_system);
      getTmpShipmentPendingList($sub_system);
      getStores($sub_system);
      if(isMobile())
         include_once  'components/inventory/view/m_listShipment.php';
      else
         include_once  'components/inventory/view/listShipment.php';
   break;

   // added by nirmal 21_08_11, updated by nirmal 21_08_24
   case "finalyze_one_shipment_tmp" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(oneShipmentTmpFinalize($_REQUEST['shipment_no'],$_REQUEST['sub'],$sub_system)){
         header('Location: index.php?components=inventory&action=one_shipment&shipment_no='.$main_shipment_no.'&message='.$message.'&re=success');
      }else{
         header('Location: index.php?components=inventory&action='.$action.'&shipment_no='.$_REQUEST['shipment_no'].'&message='.$message.'&re=fail');
      }
   break;

   // added by nirmal 21_08_26
   case "edit_shipment_header_tmp":
      include_once  'components/inventory/modle/inventoryModule.php';
      getSupplier();
      getShipmentHeaderDetails($_REQUEST['shipment_no']);
      if(isMobile())
         include_once  'components/inventory/view/m_edit_header_tmp.php';
      else
         include_once  'components/inventory/view/edit_header_tmp.php';
   break;

   // added by nirmal 21_08_27
   case "update_shipment_header_tmp":
      include_once  'components/inventory/modle/inventoryModule.php';
      if(updateShipmentHeaderDetails()){
         header('Location: index.php?components=inventory&action=edit_shipment_header_tmp&sub='.$action.'&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
      }else{
         header('Location: index.php?components=inventory&action=edit_shipment_header_tmp&sub='.$action.'&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
      }
   break;

   case "one_shipment" :
      include_once  'components/inventory/modle/inventoryModule.php';
      oneShipment();
      if(isMobile())
      		include_once  'components/inventory/view/m_oneShipment.php';
      else
      		include_once  'components/inventory/view/oneShipment.php';
   break;

   case "export_shipment" :
      include_once  'components/inventory/modle/inventoryModule.php';
      oneShipmentExp();
  break;

  case "reorder_repairparts" :
      include_once  'components/inventory/modle/inventoryModule.php';
      reorderRepairParts();
  break;

   case "show_edit_item" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getItems2('off');
      if(isMobile())
      		include_once  'components/inventory/view/m_editItem.php';
      else
      		include_once  'components/inventory/view/editItem.php';
   break;

   case "show_one_item" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getOneItem($sub_system);
      getCategory($sub_system);
      getSupplier();
      getTags2();
      getUnitTypes(1);
      if(isMobile())
      		include_once  'components/inventory/view/m_editItem.php';
      else
      		include_once  'components/inventory/view/editItem.php';
   break;

   case "edit_item1" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(editItem1($sub_system))
      		header('Location: index.php?components=inventory&action=show_one_item&code0='.urlencode($code).'&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=show_edit_item&message='.$message.'&re=fail');
   break;

   case "edit_item2" :
      include_once  'components/inventory/modle/inventoryModule.php';
      print editItem2($sub_system);
   break;

   // added by nirmal 12_07_2024
   case "edit_unics_price" :
      include_once  'components/inventory/modle/inventoryModule.php';
      print editUnicsPrice($sub_system);
   break;

   case "show_all_item" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getItems($sub_system);
      getCategory($sub_system);
      getStores($sub_system);
      if($_GET['type']==4) getParts();
      getTags();
      if(isMobile())
      		include_once  'components/inventory/view/m_allItem.php';
      else
      		include_once  'components/inventory/view/allItem.php';
   break;

   case "export_unic_list" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getUnicItems($sub_system);
   break;
   //------------------------Special Price----------------------------//
   case "show_specialprice" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getItems2('on');
      getDistrict();
      getSpecialPrice($sub_system);
      if(isMobile())
         include_once  'components/inventory/view/m_specialPrice.php';
      else
         include_once  'components/inventory/view/specialPrice.php';
   break;

   case "add_specialprice" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(addSpecialPrice($sub_system))
      		header('Location: index.php?components=inventory&action=show_specialprice&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=show_specialprice&message='.$message.'&re=fail');
   break;

   case "update_specialprice" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(updateSpecialPrice())
      		header('Location: index.php?components=inventory&action=show_specialprice&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=show_specialprice&message='.$message.'&re=fail');
   break;

   case "delete_specialprice" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(deleteSpecialPrice())
      		header('Location: index.php?components=inventory&action=show_specialprice&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=show_specialprice&message='.$message.'&re=fail');
   break;
   //------------------------District Price----------------------------//
   case "show_districtprice" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getDistrictPrice($sub_system);
      if(isMobile())
         include_once  'components/inventory/view/m_districtPrice.php';
     else
         include_once  'components/inventory/view/districtPrice.php';
   break;

   case "update_districtprice" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(updateDistrictPrice($sub_system))
      		header('Location: index.php?components=inventory&action=show_districtprice&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=show_districtprice&message='.$message.'&re=fail');
   break;
   //------------------------Temp Items ----------------------------//
   case "show_temp" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getTempItem();
      if(isMobile())
      		include_once  'components/inventory/view/m_tempItem.php';
      else
      		include_once  'components/inventory/view/tempItem.php';
   break;
   //------------------------Repair Items ----------------------------//
   case "add_repair_inv" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(addRepairInv())
      		header('Location: index.php?components=inventory&action=show_all_item&type='.$_GET['type'].'&category='.$_GET['category'].'&store='.$_GET['store'].'&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=show_all_item&type='.$_GET['type'].'&category='.$_GET['category'].'&store='.$_GET['store'].'&message='.$message.'&re=fail');
   break;

   case "update_repair_inv" :
      include_once  'components/inventory/modle/inventoryModule.php';
      updateRepairInv();
      print $message;
   break;

   case "repair_parts_list" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getParts();
      include_once  'components/inventory/view/repairPartsList.php';
   break;

   case "repair_parts_list_disabled" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getDisParts();
      include_once  'components/inventory/view/repairPartsList.php';
   break;

   case "add_repair_part" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(addRepairPart())
      		header('Location: index.php?components=inventory&action=repair_parts_list&type='.$_GET['type'].'&category='.$_GET['category'].'&store='.$_GET['store'].'&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=repair_parts_list&type='.$_GET['type'].'&category='.$_GET['category'].'&store='.$_GET['store'].'&message='.$message.'&re=fail');
   break;

   case "update_repair_part" :
      include_once  'components/inventory/modle/inventoryModule.php';
      updateRepairPart();
      print $message;
   break;

   case "delete_repair_part" :
      include_once  'components/inventory/modle/inventoryModule.php';
      deleteRepairPart();
      print $message;
   break;

   case "enable_repair_part" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(enableRepairPart())
      		header('Location: index.php?components=inventory&action=repair_parts_list&type='.$_GET['type'].'&category='.$_GET['category'].'&store='.$_GET['store'].'&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=repair_parts_list&type='.$_GET['type'].'&category='.$_GET['category'].'&store='.$_GET['store'].'&message='.$message.'&re=fail');
   break;

   case "show_repair_map" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getRepairPartMap();
      include_once  'components/inventory/view/repairPartMap.php';
   break;

   case "add_repair_map" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(addRepairMap())
      		header('Location: index.php?components=inventory&action=show_repair_map&item='.$_GET['item_id'].'&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=show_repair_map&item='.$_GET['item_id'].'&message='.$message.'&re=fail');
   break;

   case "remove_repair_map" :
      include_once  'components/inventory/modle/inventoryModule.php';
      if(removeRepairMap())
      		header('Location: index.php?components=inventory&action=show_repair_map&item='.$_GET['item_id'].'&message='.$message.'&re=success');
      	else
      		header('Location: index.php?components=inventory&action=show_repair_map&item='.$_GET['item_id'].'&message='.$message.'&re=fail');
   break;
   //------------------------Drawer Search ----------------------------//
   case "drawer_search" :
      include_once  'components/inventory/modle/inventoryModule.php';
      getStores($sub_system);
      drawerSearch();
      if(isMobile())
      		include_once  'components/inventory/view/m_drawerSearch.php';
      else
      		include_once  'components/inventory/view/drawerSearch.php';
   break;
   //------------------------Tags ----------------------------//
   case "tag_mgmt" :
     include_once  'components/inventory/modle/inventoryModule.php';
     getTags2();
     getOneTag();
     getCategory($sub_system);
     include_once  'components/inventory/view/tagMgmt.php';
   break;

   case "create_tag" :
      include_once  'components/inventory/modle/inventoryModule.php';
      print createTag($_POST['tag_name'],$_POST['tag_profit']);
   break;

   // added by nirmal 21_10_5
   case "check_updating_tag":
      include_once  'components/inventory/modle/inventoryModule.php';
      print validateUpdatingTag($_POST['tag_id'],$_POST['tag_profit']);
   break;

   case "update_tag" :
      include_once  'components/inventory/modle/inventoryModule.php';
      print updateTag($_POST['tag_id'],$_POST['tag_name'],$_POST['tag_profit']);
   break;

	case "tag-list":
      include_once  'template/common.php';
      listTag($sub_system);
      include_once  'template/ajax_list.php';
		break;

	case "create_add_tag":
        include_once  'components/inventory/modle/inventoryModule.php';
      print createAddTag($_POST['item'],$_POST['tag']);
		break;

	case "remove_tag":
        include_once  'components/inventory/modle/inventoryModule.php';
      print removeTag($_POST['item'],$_POST['tag_id']);
		break;

	case "delete_tag":
        include_once  'components/inventory/modle/inventoryModule.php';
         if(deleteTag($_GET['tag_id']))
         		header('Location: index.php?components=inventory&action=tag_mgmt&message='.$message.'&re=success');
         	else
			 header('Location: index.php?components=inventory&action=tag_mgmt&message='.$message.'&re=fail');
		break;

	case "show_item_tags":
        include_once  'components/inventory/modle/inventoryModule.php';
      print showItemTags($_POST['item']);
		break;

	case "search_items":
        include_once  'components/inventory/modle/inventoryModule.php';
      print searchItems($sub_system);
		break;

   case "check_tags_inserting":
        include_once  'components/inventory/modle/inventoryModule.php';
      print validateInsertingTags($sub_system);
		break;

	case "apply_bulk_tag":
        include_once  'components/inventory/modle/inventoryModule.php';
      print applyBulkTag($sub_system);
		break;

   // BARCODE
    case "barcode":
        include_once  'components/inventory/modle/inventoryModule.php';
        include_once  'components/inventory/view/barcode.php';
    break;

    case "code-list":
        include_once  'template/common.php';
        listItem($sub_system);
        include_once  'template/ajax_list.php';
    break;

     case "desc-list":
        include_once  'template/common.php';
        listItem($sub_system);
        include_once  'template/ajax_list.php';
    break;

    case "get_more_item_data":
      	include_once  'components/inventory/modle/inventoryModule.php';
		getOneItem2($sub_system, $systemid);
		include_once  'components/inventory/view/barcode.php';
    break;

	case "barcode_print":
	  	include_once  'components/inventory/view/tpl/barcode_print.php';
  break;

   default:
         print '<p><srtong>Bad Request</strong></p>';
   break;
}
?>