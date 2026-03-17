<?php
        switch ($_REQUEST['action'])
        {
            
            case "lock" :
               include_once  'components/backend/modle/backendModule.php';
	            getUnlockBills();
	            getOneLockSt();
               include_once  'components/backend/view/m_lock.php';
            break;
                        
            case "changelock" :
               include_once  'components/backend/modle/backendModule.php';
               if(changeLock())
               		header('Location: index.php?components=backend&action=lock&lockinvid='.$bill_id.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=lock&lockinvid='.$bill_id.'&message='.$message.'&re=fail');
            break;
            
            case "delete" :
               include_once  'components/backend/modle/backendModule.php';
               if($_GET['type']=='bill'){
               		billStatus();
	            	generateInvoice();
               }
               if($_GET['type']=='pay'){
	                payStatus();
	            	generatePayment();
               }
               if($_GET['type']=='commission'){
	                comStatus();
               }
               include_once  'components/backend/view/m_delete.php';
            break;
            
            case "delete_search" :
               include_once  'components/backend/modle/backendModule.php';
                if(searchDelete())
               		header('Location: index.php?components=backend&action=delete&type='.$type.'&id='.$id);
               	else
               		header('Location: index.php?components=backend&action=delete&type&message='.$message.'&re=fail');
            break;
            
            case "delete_bill" :
               include_once  'template/debug.php';
               include_once  '../components/billing/modle/billingModule.php';
               if(deleteBill(2,1))
               		header('Location: index.php?components=backend&action=delete&type=bill&id='.$_GET['id'].'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=delete&type&message='.$message.'&re=fail');
            break;
            
            case "delete_bill2" :
               include_once  'template/debug.php';
               include_once  '../components/bill2/modle/bill2Module.php';
               if(deleteInvoice(2,1))
               		header('Location: index.php?components=backend&action=delete&type=bill&id='.$_GET['id'].'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=delete&type&message='.$message.'&re=fail');
            break;
            
            case "delete_pay" :
               include_once  'template/debug.php';
               include_once  '../components/billing/modle/billingModule.php';
               if(deletePayment(2,1))
               		header('Location: index.php?components=backend&action=delete&type=pay&id='.$_GET['id'].'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=delete&type&message='.$message.'&re=fail');
            break;
            
            case "delete_commission_report" :
               include_once  '../components/manager/modle/managerModule.php';
               if(hpDeleteCommission(1))
               		header('Location: index.php?components=backend&action=delete&type=commission&id='.$_GET['id'].'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=delete&type&message='.$message.'&re=fail');
            break;
            
            case "inv_mgmt" :
               include_once  'components/backend/modle/backendModule.php';
	            searchInv();
               include_once  'components/backend/view/m_invmgmt.php';
            break;
            
            case "set_inv_main" :
               include_once  'components/backend/modle/backendModule.php';
               if(setInvMain())
               		header('Location: index.php?components=backend&action=inv_mgmt&bill_no='.$bill_no.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=inv_mgmt&bill_no='.$bill_no.'&message='.$message.'&re=fail');
            break;
            
            case "clear_cat" :
               include_once  'components/backend/modle/backendModule.php';
	            getCategory();
	            getStore();
	            getJobId();
               include_once  'components/backend/view/m_clearCat.php';
            break;
                        
            case "set_clear" :
               include_once  'components/backend/modle/backendModule.php';
               if(setClear())
               		header('Location: index.php?components=backend&action=clear_cat&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=clear_cat&message='.$message.'&re=fail');
            break;
                        
            case "restore_clear_cat" :
               include_once  'components/backend/modle/backendModule.php';
               if(restoreClearCat())
               		header('Location: index.php?components=backend&action=clear_cat&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=clear_cat&message='.$message.'&re=fail');
            break;
            
            case "inv_order" :
               include_once  'components/backend/view/m_order.php';
            break;
                        
            case "inv_setorder" :
               include_once  'components/backend/modle/backendModule.php';
               invSetOrder();
            break;
			//---------------------------debug--------------------------------//
            
            case "debug" :
               include_once  'components/backend/modle/backendModule.php';
	            getDebug();
               include_once  'components/backend/view/m_debug.php';
            break;
            
            case "debug_ack" :
               include_once  'components/backend/modle/backendModule.php';
               if(debugAck())
               		header('Location: index.php?components=backend&action=debug&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=debug&message='.$message.'&re=fail');
            break;
			//---------------------------mismatch--------------------------------//
            case "mismatch" :
               include_once  'components/backend/modle/backendModule.php';
	            getInvMismatch();
               include_once  'components/backend/view/m_mismatch.php';
            break;
            
            case "validate_error" :
               include_once  'components/backend/modle/backendModule.php';
	           print validateError();
            break;
            
            case "mismatch_one" :
               include_once  'components/backend/modle/backendModule.php';
	            getOneMismatch();
               include_once  'components/backend/view/m_mismatch_one.php';
            break;
            
            case "mismatch_up" :
               include_once  'components/backend/modle/backendModule.php';
               if(updateItqQty(+1))
               		header('Location: index.php?components=backend&action=mismatch&list=err&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=mismatch&list=err&message='.$message.'&re=fail');
            break;
            
            case "mismatch_down" :
               include_once  'components/backend/modle/backendModule.php';
               if(updateItqQty(-1))
               		header('Location: index.php?components=backend&action=mismatch&list=err&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=mismatch&list=err&message='.$message.'&re=fail');
            break;
            
			//---------------------------subscription--------------------------------//
            case "show_sub" :
               include_once  'components/backend/modle/backendModule.php';
	            getSubscription();
               include_once  'components/backend/view/m_subscription.php';
            break;
            
            case "sub_up" :
               include_once  'components/backend/modle/backendModule.php';
               if(incrementSub(1))
               		header('Location: index.php?components=backend&action=show_sub&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=show_sub&message='.$message.'&re=fail');
            break;
            
            case "sub_down" :
               include_once  'components/backend/modle/backendModule.php';
               if(incrementSub(-1))
               		header('Location: index.php?components=backend&action=show_sub&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=backend&action=show_sub&message='.$message.'&re=fail');
            break;

            default:
                print '<p><srtong>Bad Request</strong></p>';
            break;
        }
?>