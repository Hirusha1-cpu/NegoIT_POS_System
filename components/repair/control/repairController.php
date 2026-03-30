<?php
if(passwordExpire()) header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');

        switch ($_REQUEST['action'])
        {
            case "list_pending" :
               include_once  'components/repair/modle/repairModule.php';
               getOrder('pending');
               if(isMobile())
       		   		include_once  'components/repair/view/m_home.php';
               else
       		   		include_once  'components/repair/view/home.php';
            break;
            
            case "list_my" :
               include_once  'components/repair/modle/repairModule.php';
               getOrder('picked');
               if(isMobile())
       		   		include_once  'components/repair/view/m_home.php';
               else
       		   		include_once  'components/repair/view/home.php';
            break;
            
            case "list_finished" :
               include_once  'components/repair/modle/repairModule.php';
               getOrder('finished');
               if(isMobile())
       		   		include_once  'components/repair/view/m_home.php';
               else
       		   		include_once  'components/repair/view/home.php';
            break;
            
            case "list_rejected" :
               include_once  'components/repair/modle/repairModule.php';
               getOrder('rejected');
               if(isMobile())
       		   		include_once  'components/repair/view/m_home.php';
               else
       		   		include_once  'components/repair/view/home.php';
            break;
            
            case "list_one" :
               include_once  'components/repair/modle/repairModule.php';
               getOneOrder();
               getParts();
               getCustDetails(); 
               getRepairComments();
               if(isMobile())
       		   		include_once  'components/repair/view/m_one.php';
               else
       		   		include_once  'components/repair/view/one.php';
            break;
                        
            case "list_one_done" :
               include_once  'components/repair/modle/repairModule.php';
               getOneOrder();
               getParts();
               getCustDetails(); 
               if(isMobile())
       		   		include_once  'components/repair/view/m_one.php';
               else
       		   		include_once  'components/repair/view/one.php';
            break;
            
            case "change_st" :
               include_once  'components/repair/modle/repairModule.php';
               searchJob();
               if(isMobile())
       		   		include_once  'components/repair/view/m_change_st.php';
               else
       		   		include_once  'components/repair/view/change_st.php';
            break;
            
            case "get_part_drawer" :
               include_once  'components/repair/modle/repairModule.php';
               print getPartDrawer();
            break;
                        
            case "update_st" :
               include_once  'components/repair/modle/repairModule.php';
               if(updateStatus())
               		header('Location: index.php?components=repair&action=change_st&invoice_no='.$_POST['invoice_no'].'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=change_st&invoice_no='.$_POST['invoice_no'].'&message='.$message.'&re=fail');
            break;
            
            case "pick" :
               include_once  'components/repair/modle/repairModule.php';
               if(setStatus('pick'))
               		header('Location: index.php?components=repair&action=list_one&id='.$_GET['id'].'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=list_one&id='.$_GET['id'].'&message='.$message.'&re=fail');
            break;
                        
            case "unassign" :
               include_once  'components/repair/modle/repairModule.php';
               if(orderUnassign())
               		header('Location: index.php?components=repair&action=list_pending&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=list_one&id='.$_GET['id'].'&message='.$message.'&re=fail');
            break;
                        
            case "apend_part" :
               include_once  'components/repair/modle/repairModule.php';
               if(apendPart())
               		header('Location: index.php?components=repair&action=list_one&id='.$_GET['id'].'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=list_one&id='.$_GET['id'].'&message='.$message.'&re=fail');
            break;
                        
            case "remove_part" :
               include_once  'components/repair/modle/repairModule.php';
               if(removePart())
               		header('Location: index.php?components=repair&action=list_one&id='.$_GET['id'].'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=list_one&id='.$_GET['id'].'&message='.$message.'&re=fail');
            break;
                        
            case "finish" :
               include_once  'components/repair/modle/repairModule.php';
               if(setStatus('finish'))
               		header('Location: index.php?components=repair&action=list_pending&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=list_one&id='.$_GET['id'].'&message='.$message.'&re=fail');
            break;
            
            case "reject" :
               include_once  'components/repair/modle/repairModule.php';
               if(setStatus('reject'))
               		header('Location: index.php?components=repair&action=list_pending&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=list_one&id='.$_GET['id'].'&message='.$message.'&re=fail');
            break;
            
            case "update_price" :
               include_once  'components/repair/modle/repairModule.php';
               if(updatePrice())
               		header('Location: index.php?components=repair&action=list_one&id='.$bm_inv.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=list_one&id='.$bm_inv.'&message='.$message.'&re=fail');
            break;
            
            case "add_repair_comment" :
               include_once  'components/repair/modle/repairModule.php';
               if(addRepairComment($_POST['repcom_type']))
               		header('Location: index.php?components=repair&action=list_one&id='.$bm_inv.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=list_one&id='.$bm_inv.'&message='.$message.'&re=fail');
            break;
            
            case "del_repair_comment" :
               include_once  'components/repair/modle/repairModule.php';
               if(delRepairComment())
               		header('Location: index.php?components=repair&action=list_one&id='.$bm_inv.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=list_one&id='.$bm_inv.'&message='.$message.'&re=fail');
            break;
            
            case "add_bo_comment" :
               include_once  'components/repair/modle/repairModule.php';
               if(addBOComment($_GET['co']))
               		header('Location: index.php?components=repair&action=list_one&id='.$bm_inv.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=list_one&id='.$bm_inv.'&message='.$message.'&re=fail');
            break;
            
            case "remove_bo_comment" :
               include_once  'components/repair/modle/repairModule.php';
               if(removeBOComment($_GET['co']))
               		header('Location: index.php?components=repair&action=list_one&id='.$bm_inv.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=list_one&id='.$bm_inv.'&message='.$message.'&re=fail');
            break;
            
            case "repair_force_accept" :
               include_once  'components/repair/modle/repairModule.php';
               if(repairForceAccept())
               		header('Location: index.php?components=repair&action=list_one&id='.$bm_inv.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=repair&action=list_one&id='.$bm_inv.'&message='.$message.'&re=fail');
            break;
            
            //----------------------Invoice Preview--------------------------------//
            case "inv_preview" :
               include_once  'components/billing/modle/billingModule.php';
            	billPermission();
                billDetails(); 
            	billTemplate();
            	getBank();
            	getSalesman2();
               	include_once  'components/repair/view/inv_preview.php';
            break;

            //----------------------------------------------------------------------//
            case "rep_item_list":
               include_once  'components/repair/modle/repairModule.php';
               listRepItem($sub_system);
               include_once  'template/ajax_list.php';
            break;

            case "more_rep_item":
               include_once  'components/repair/modle/repairModule.php';
               print moreRepItem($sub_system);
            break;

            default:
                print '<p><srtong>Bad Request</strong></p>';
            break;
        }
?>