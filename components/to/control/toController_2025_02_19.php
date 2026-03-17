<?php
    switch ($_REQUEST['action']){
        case "home" :
            header('Location: index.php?components=to&action=quotation');
        break;

        case "set_district" :
            include_once  'components/billing/modle/billingModule.php';
            setDistrict();
            header('Location: index.php?components=to&action=quotation');
         break;

        case "quotation":
            include_once  'components/billing/modle/billingModule.php';
            include_once  'components/supervisor/modle/supervisorModule.php';
            getDistrict();
            getQuotationItems();
            if (isset($_COOKIE['district'])) {
                getItems($item_filter, $sub_system, $systemid);
                getCust(1, '1');
                if (isset($_GET['cust'])) {
                    if (validateQuotNo()) header('Location: index.php?components=to&action=new_quot&cust_id=' . $_GET['cust'] . '&validity=30');
                }
            }
            if(isMobile())
                include_once  'components/supervisor/view/m_quotation.php';
            else
                include_once  'components/supervisor/view/quotation.php';
        break;

        case "new_quot":
            include_once  'components/supervisor/modle/supervisorModule.php';
            if (newQuot($_REQUEST['cust_id']))
                header('Location: index.php?components=to&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust);
            else
                header('Location: index.php?components=to&action=quotation&message=' . $message . '&re=fail');
        break;

        case "apend_quot":
            include_once  'components/billing/modle/billingModule.php';
            include_once  'components/supervisor/modle/supervisorModule.php';
            if (apendQuot()) {
               header('Location: index.php?components=to&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
            } else {
               header('Location: index.php?components=to&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
            }
        break;

        case "qo_item_gpdate" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            if(updateQuot()){
                header('Location: index.php?components=to&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=success');
            }else{
                header('Location: index.php?components=to&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=fail');
            }
        break;

        case "qo_item_remove" :
        include_once  'components/supervisor/modle/supervisorModule.php';
        if(removeQuot()){
                header('Location: index.php?components=to&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=success');
            }else{
                header('Location: index.php?components=to&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=fail');
            }
        break;

        case "qo_terms" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            getQOTerms();
            getDetaultTerms();
            if (isMobile())
                include_once  'components/supervisor/view/m_quotation_terms.php';
            else
                include_once  'components/supervisor/view/quotation_terms.php';
        break;

        case "set_qo_terms" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            if(setQuotTerms())
                header('Location: index.php?components=to&action=set_quot_status&id='.$quot_no.'&new_status=2');
            else
                header('Location: index.php?components=to&action=qo_terms&id='.$quot_no.'&message='.$message.'&re=fail');
        break;

        case "set_quot_status" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            if(setQuotStatus($_GET['new_status']))
                header('Location: index.php?components=to&action=qo_finish&id='.$quot_no);
            else
                header('Location: index.php?components=to&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
        break;

        case "qo_revise" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            if(qoRevise())
                header('Location: index.php?components=to&action=qo_finish&id='.$quot_no);
            else
                header('Location: index.php?components=to&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
        break;

        case "set_submit" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            setQuotStatus(5);
        break;

        case "qo_finish" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            qoPermission();
            qoDetails();
            qoNote();
            qoTemplate();
            if(isMobile())
                include_once  'components/supervisor/view/m_qo_print.php';
            else
                include_once  'components/supervisor/view/qo_print.php';
        break;

        case "quotation_ongoing" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            getOnGoing();
            if (isMobile())
                include_once  'components/supervisor/view/m_quotation_ongoing.php';
            else
                include_once  'components/supervisor/view/quotation_ongoing.php';
        break;

        case "quotation_list" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            getQuotList($sub_system);
            getFilter($sub_system);
            getCustSup($sub_system);
            if (isMobile())
                include_once  'components/supervisor/view/m_quotation_list.php';
            else
                include_once  'components/supervisor/view/quotation_list.php';
        break;

        case "search_quot" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            if(searchQuot($_POST['search1']))
                header('Location: index.php?components=to&action=qo_finish&id='.$_POST['search1']);
            else
                header('Location: index.php?components=to&action=quotation&message=Invalid%20Quotation%20Number&re=fail');
        break;

        case "qo_add_image" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            if(qoAddImage())
                header('Location: index.php?components=to&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=success');
             else
                header('Location: index.php?components=to&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=fail');
        break;

        case "qo_delete_image" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            if(qoDeleteImage())
                header('Location: index.php?components=to&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=success');
            else
                header('Location: index.php?components=to&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=fail');
        break;

        case "qo_img_height" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            if(qoImgHeight())
                header('Location: index.php?components=to&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=success');
            else
                header('Location: index.php?components=to&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=fail');
        break;

        case "qo_add_note" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            if(qoAddNote())
                header('Location: index.php?components=to&action=qo_finish&id='.$quot_no);
            else
                header('Location: index.php?components=to&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
        break;

        case "qo_update_note" :
            include_once  'components/supervisor/modle/supervisorModule.php';
            if(qoUpdateNote())
                header('Location: index.php?components=to&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=success');
            else
                header('Location: index.php?components=to&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
        break;

        default:
            print '<p><srtong>Bad Request</strong></p>';
        break;
    }
?>