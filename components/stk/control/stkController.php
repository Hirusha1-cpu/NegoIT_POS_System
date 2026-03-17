<?php
$action=$_REQUEST['action'];
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
            header('Location: index.php?components=stk&action=show_add_item&type='.$type.'&message='.$message.'&re=success');
        else
            header('Location: index.php?components=stk&action=show_add_item&type='.$type.'&message='.$message.'&re=fail');
    break;

    case "show_add_shipment" :
        if($systemid==1){
                $today=dateNow();
                   header("Location: index.php?components=stk&action=add_shipment&ship_date=$today&suplier=1&ship_inv_no=0&ship_inv_date=$today&ship_inv_dudate=$today&sub=".$_REQUEST['sub']);
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

    case "show_add_shipment_tmp" :
    if($systemid==1){
        $today=dateNow();
        header("Location: index.php?components=stk&action=add_shipment_tmp&ship_date=$today&suplier=1&ship_inv_no=0&ship_inv_date=$today&ship_inv_dudate=$today&sub=".$_REQUEST['sub']);
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

    case "add_shipment_tmp" :
        include_once  'components/inventory/modle/inventoryModule.php';
        if(addShipmentTmp($sub_system))
                header('Location: index.php?components=stk&action='.$_REQUEST['sub'].'&shipment_no='.$shipment_no);
        else
            header('Location: index.php?components=stk&action=show_add_shipment_tmp&sub='.$_REQUEST['sub'].'&message='.$message.'&re=fail');
    break;

    case "add_shipment" :
        include_once  'components/inventory/modle/inventoryModule.php';
        if(addShipment($sub_system,$_REQUEST['ship_date'],$_REQUEST['suplier'],$_REQUEST['ship_inv_no'],$_REQUEST['ship_inv_date'],$_REQUEST['ship_inv_dudate'],$_REQUEST['sub'])){
            header('Location: index.php?components=stk&action='.$_REQUEST['sub'].'&shipment_no='.$shipment_no);
        }else{
            header('Location: index.php?components=stk&action=show_add_shipment&sub='.$_REQUEST['sub'].'&message='.$message.'&re=fail');
        }
    break;

    case "show_add_qty_tmp":
        include_once  'components/inventory/modle/inventoryModule.php';
        currentStore();
        getShipmentItemsTmp($_REQUEST['shipment_no']);
        if(isMobile())
            include_once  'components/inventory/view/m_addQtyTmp.php';
        else
            include_once  'components/inventory/view/addQtyTmp.php';
    break;

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

    case "add_qty_tmp" :
        include_once  'components/inventory/modle/inventoryModule.php';
        if(addQtyTmp($_REQUEST['shipment_no'],$_REQUEST['unic'],$_REQUEST['item_id'],$_REQUEST['qty'],$_REQUEST['c_price1'],$_REQUEST['w_price1'],$_REQUEST['r_price1'],$_REQUEST['c_price2'],$_REQUEST['w_price2'],$_REQUEST['r_price2']))
            header('Location: index.php?components=stk&action='.$action.'&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
        else
            header('Location: index.php?components=stk&action='.$action.'&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
    break;

    case "add_qty" :
        include_once  'components/inventory/modle/inventoryModule.php';
        if(addQty($_REQUEST['shipment_no'], $_REQUEST['unic'],$_REQUEST['item_id'],$_REQUEST['qty'],$_REQUEST['c_price1'],$_REQUEST['w_price1'],$_REQUEST['r_price1'],$_REQUEST['c_price2'],$_REQUEST['w_price2'],$_REQUEST['r_price2']))
            header('Location: index.php?components=stk&action='.$action.'&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
        else
            header('Location: index.php?components=stk&action='.$action.'&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
    break;

    case "shipment_item_update_tmp" :
        include_once  'components/inventory/modle/inventoryModule.php';
        $debug_id=debugStart(0,0);
        if(updateShipmentItemTmp()){
            debugEnd($debug_id,'success');
            header('Location: index.php?components=stk&action=show_add_qty_tmp&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
        }else{
            debugEnd($debug_id,'fail');
            header('Location: index.php?components=stk&action=show_add_qty_tmp&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
        }
    break;

    case "shipment_item_gpdate" :
        include_once  'components/inventory/modle/inventoryModule.php';
        $debug_id=debugStart(0,0);
        if(updateShipmentItem()){
            debugEnd($debug_id,'success');
            header('Location: index.php?components=stk&action=show_add_qty&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
        }else{
            debugEnd($debug_id,'fail');
            header('Location: index.php?components=stk&action=show_add_qty&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
        }
    break;

    case "shipment_qty_item_remove_tmp" :
        include_once  'components/inventory/modle/inventoryModule.php';
        $debug_id=debugStart(0,0);
        if(removeShipmentQtyItemTmp()){
            debugEnd($debug_id,'success');
            header('Location: index.php?components=stk&action=show_add_qty_tmp&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
        }else{
            debugEnd($debug_id,'fail');
            header('Location: index.php?components=stk&action=show_add_qty_tmp&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
        }
    break;

    case "get_item_data" :
        include_once  'components/inventory/modle/inventoryModule.php';
        print getItemData();
    break;

    case "finalyze_one_shipment_tmp" :
        include_once  'components/inventory/modle/inventoryModule.php';
        if(oneShipmentTmpFinalize($_REQUEST['shipment_no'],$_REQUEST['sub'],$sub_system)){
           header('Location: index.php?components=stk&action=one_shipment&shipment_no='.$main_shipment_no.'&message='.$message.'&re=success');
        }else{
           header('Location: index.php?components=stk&action='.$action.'&shipment_no='.$_REQUEST['shipment_no'].'&message='.$message.'&re=fail');
        }
     break;

     case "edit_shipment_header_tmp":
        include_once  'components/inventory/modle/inventoryModule.php';
        getSupplier();
        getShipmentHeaderDetails($_REQUEST['shipment_no']);
        if(isMobile())
           include_once  'components/inventory/view/m_edit_header_tmp.php';
        else
           include_once  'components/inventory/view/edit_header_tmp.php';
     break;

    case "update_shipment_header_tmp":
        include_once  'components/inventory/modle/inventoryModule.php';
        if(updateShipmentHeaderDetails()){
            header('Location: index.php?components=stk&action=edit_shipment_header_tmp&sub='.$action.'&shipment_no='.$shipment_no.'&message='.$message.'&re=success');
        }else{
            header('Location: index.php?components=stk&action=edit_shipment_header_tmp&sub='.$action.'&shipment_no='.$shipment_no.'&message='.$message.'&re=fail');
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

    // case "shipmentlist" :
    //     include_once  'components/inventory/modle/inventoryModule.php';
    //     getShipmentList($systemid,$sub_system);
    //     getTmpShipmentPendingList($sub_system);
    //     getStores($sub_system);
    //     if(isMobile())
    //        include_once  'components/inventory/view/m_listShipment.php';
    //     else
    //        include_once  'components/inventory/view/listShipment.php';
    // break;

    case "export_shipment" :
        include_once  'components/inventory/modle/inventoryModule.php';
        oneShipmentExp();
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
        print validateInsertingTags();
    break;

    case "apply_bulk_tag":
        include_once  'components/inventory/modle/inventoryModule.php';
        print applyBulkTag();
    break;
}
?>