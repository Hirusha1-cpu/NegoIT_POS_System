<?php
if(passwordExpire()) header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');
   switch ($_REQUEST['action'])
   {
      case "home" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         getCategory();
         getStore();
         getSupplier();
         getItems();
         include_once  'components/purchaseOrder/view/home.php';
      break;

      case "home2" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         getCategory();
         getStore();
         getSupplier();
         getItemSale();
         include_once  'components/purchaseOrder/view/home2.php';
      break;

      case "home3" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         getCategory();
         getGroup();
         getSupplier();
         getItems();
         include_once  'components/purchaseOrder/view/home3.php';
      break;

      case "home4" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         getCategory();
         getGroup();
         getSupplier();
         getItemSale();
         include_once  'components/purchaseOrder/view/home4.php';
      break;

      case "new_po" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         getSupplier();
         include_once  'components/purchaseOrder/view/new_po.php';
      break;

      case "add_itempo" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         print addItemPO();
      break;

      case "supplier" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         getSupplier();
         include_once  'components/purchaseOrder/view/manageSupplier.php';
      break;

      case "add_supplier" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         if(addSupplier())
               header('Location: index.php?components=purchase_order&action=supplier&message='.$message.'&re=success');
            else
               header('Location: index.php?components=purchase_order&action=supplier&message='.$message.'&re=fail');
      break;

      case "update_supplier" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         if(updateSupplier())
               header('Location: index.php?components=purchase_order&action=supplier&message='.$message.'&re=success');
            else
               header('Location: index.php?components=purchase_order&action=supplier&message='.$message.'&re=fail');
      break;

      case "edit_supplier" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         getSupplier();
         getOneSupplier('id');
         include_once  'components/purchaseOrder/view/manageSupplier.php';
      break;

      case "search_supplier" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         getSupplier();
         getOneSupplier('name');
         include_once  'components/purchaseOrder/view/manageSupplier.php';
      break;

      case "changest_sup" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         if(setStatusSupplier())
               header('Location: index.php?components=purchase_order&action=supplier&message='.$message.'&re=success');
            else
               header('Location: index.php?components=purchase_order&action=supplier&message='.$message.'&re=fail');
      break;

      case "list_po" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         listPO('100');
         include_once  'components/purchaseOrder/view/list_po.php';
      break;

      case "one_po" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         getItems2();
         onePO();
         getStore();
         include_once  'components/purchaseOrder/view/one_po.php';
      break;

      case "create_po" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         if(createPO())
               header('Location: index.php?components=purchase_order&action=one_po&id='.$po_no.'&message='.$message.'&re=success');
            else
               header('Location: index.php?components=purchase_order&action=new_po&message='.$message.'&re=fail');
      break;

      // updated by nirmal 03_10_2023
      case "append_po" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         if(appendPO())
            header('Location: index.php?components=purchase_order&action=one_po&id='.$po_no.'&message='.$message.'&re=success');
         else
            header('Location: index.php?components=purchase_order&action=one_po&id='.$po_no.'&message='.$message.'&re=fail');

      break;

      case "update_po" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         if(updatePO())
               header('Location: index.php?components=purchase_order&action=one_po&id='.$po_no.'&message='.$message.'&re=success');
            else
               header('Location: index.php?components=purchase_order&action=one_po&id='.$po_no.'&message='.$message.'&re=fail');
      break;

      case "remove_item_po" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         if(removeItemPO())
               header('Location: index.php?components=purchase_order&action=one_po&id='.$po_no.'&message='.$message.'&re=success');
            else
               header('Location: index.php?components=purchase_order&action=one_po&id='.$po_no.'&message='.$message.'&re=fail');
      break;

      case "lock_po" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         if(lockPO())
               header('Location: index.php?components=purchase_order&action=list_po&message='.$message.'&re=success');
            else
               header('Location: index.php?components=purchase_order&action=list_po&message='.$message.'&re=fail');
      break;

      case "unlock_po" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         if(unlockPO())
               header('Location: index.php?components=purchase_order&action=list_po&message='.$message.'&re=success');
            else
               header('Location: index.php?components=purchase_order&action=list_po&message='.$message.'&re=fail');
      break;

      case "download_po" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         downloadPO($systemid);
      break;

      case "email_po" :
         include_once  'components/purchaseOrder/modle/poModule.php';
         downloadPO($systemid);
         if(emailPO($systemid))
               header('Location: index.php?components=purchase_order&action=one_po&id='.$po_no.'&message='.$message.'&re=success');
            else
               header('Location: index.php?components=purchase_order&action=one_po&id='.$po_no.'&message='.$message.'&re=fail');
      break;

      default:
            print '<p><srtong>Bad Request</strong></p>';
      break;
   }
?>