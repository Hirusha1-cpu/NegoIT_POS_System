<?php
$slect1 = $slect2 = $slect3 = $slect4 = $slect5 = $slect6 = $slect7 = $slect8 = $slect9 = $slect10 = $slect11 = $slect12 = $slect13
    = $slect14 = $slect15 = $slect16 = $slect18 = $slect17 = 'class="dropbtn1"';

// ------------------------ STK ------------------------ //
if ($_REQUEST['components'] == 'stk') {
    switch ($_REQUEST['action']) {
        case "show_add_item":
            $slect1 = 'class="dropbtn2"';
            break;
        case "show_add_qty":
            $slect2 = 'class="dropbtn2"';
            break;
        case "show_add_qty_tmp":
            $slect2 = 'class="dropbtn2"';
            break;
        case "show_add_unic":
            $slect3 = 'class="dropbtn2"';
            break;
        case "show_add_unic_tmp":
            $slect3 = 'class="dropbtn2"';
            break;
        case "show_edit_item":
            $slect4 = 'class="dropbtn2"';
            break;
        case "show_one_item":
            $slect4 = 'class="dropbtn2"';
            break;
        case "show_specialprice":
            $slect5 = 'class="dropbtn2"';
            break;
        case "show_districtprice":
            $slect6 = 'class="dropbtn2"';
            break;
        case "show_all_item":
            $slect7 = 'class="dropbtn2"';
            break;
        case "repair_parts_list":
            $slect7 = 'class="dropbtn2"';
            break;
        case "show_repair_map":
            $slect7 = 'class="dropbtn2"';
            break;
        case "show_temp":
            $slect8 = 'class="dropbtn2"';
            break;
        case "drawer_search":
            $slect9 = 'class="dropbtn2"';
            break;
        case "shipmentlist":
            $slect10 = 'class="dropbtn2"';
            break;
        case "one_shipment":
            $slect10 = 'class="dropbtn2"';
            break;
        case "show_unic":
            $slect10 = 'class="dropbtn2"';
            break;
        case "show_edit_unic":
            $slect10 = 'class="dropbtn2"';
            break;
        case "tag_mgmt":
            $slect11 = 'class="dropbtn2"';
            break;
        case "barcode":
            $slect12 = 'class="dropbtn2"';
            break;
        case "get_more_item_data":
            $slect12 = 'class="dropbtn2"';
            break;
    }
    if (isset($_REQUEST['sub'])) {
        switch ($_REQUEST['sub']) {
            case "show_add_unic_tmp":
                $slect3 = 'class="dropbtn2"';
                break;
            case "show_add_qty_tmp":
                $slect2 = 'class="dropbtn2"';
                break;
        }
    }
}

// ------------------------ AVAILABILITY ------------------------ //
if ($_REQUEST['components'] == 'availability') {
    switch ($_REQUEST['action']) {
        case "home":
            $slect1 = 'class="dropbtn2"';
            break;
        case "catalog":
            $slect2 = 'class="dropbtn2"';
            break;
        case "stock":
            $slect3 = 'class="dropbtn2"';
            break;
        case "sn_lookup":
            $slect4 = 'class="dropbtn2"';
            break;
        case "sn_lookup_price":
            $slect5 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ INVENTORY ------------------------ //
if ($_REQUEST['components'] == 'inventory') {
    switch ($_REQUEST['action']) {
        case "show_add_item":
            $slect1 = 'class="dropbtn2"';
            break;
        case "show_add_qty":
            $slect2 = 'class="dropbtn2"';
            break;
        case "show_add_qty_tmp":
            $slect2 = 'class="dropbtn2"';
            break;
        case "show_add_unic":
            $slect3 = 'class="dropbtn2"';
            break;
        case "show_add_unic_tmp":
            $slect3 = 'class="dropbtn2"';
            break;
        case "show_edit_item":
            $slect4 = 'class="dropbtn2"';
            break;
        case "show_one_item":
            $slect4 = 'class="dropbtn2"';
            break;
        case "show_specialprice":
            $slect5 = 'class="dropbtn2"';
            break;
        case "show_districtprice":
            $slect6 = 'class="dropbtn2"';
            break;
        case "show_all_item":
            $slect7 = 'class="dropbtn2"';
            break;
        case "repair_parts_list":
            $slect7 = 'class="dropbtn2"';
            break;
        case "show_repair_map":
            $slect7 = 'class="dropbtn2"';
            break;
        case "show_temp":
            $slect8 = 'class="dropbtn2"';
            break;
        case "drawer_search":
            $slect9 = 'class="dropbtn2"';
            break;
        case "shipmentlist":
            $slect10 = 'class="dropbtn2"';
            break;
        case "one_shipment":
            $slect10 = 'class="dropbtn2"';
            break;
        case "show_unic":
            $slect10 = 'class="dropbtn2"';
            break;
        case "show_edit_unic":
            $slect10 = 'class="dropbtn2"';
            break;
        case "tag_mgmt":
            $slect11 = 'class="dropbtn2"';
            break;
        case "barcode":
            $slect12 = 'class="dropbtn2"';
            break;
        case "get_more_item_data":
            $slect12 = 'class="dropbtn2"';
            break;
    }
    if (isset($_REQUEST['sub'])) {
        switch ($_REQUEST['sub']) {
            case "show_add_unic_tmp":
                $slect3 = 'class="dropbtn2"';
                break;
            case "show_add_qty_tmp":
                $slect2 = 'class="dropbtn2"';
                break;
        }
    }
}

// ------------------------ BILL ------------------------ //
if ($_REQUEST['components'] == 'billing') {
    switch ($_REQUEST['action']) {
        case "home":
            if ($_GET['cust_odr'] == 'no')
                $slect1 = 'class="dropbtn2"';
            if ($_GET['cust_odr'] == 'yes')
                $slect2 = 'class="dropbtn2"';
            break;
        case "pay_bill":
            if ($_GET['cust_odr'] == 'no')
                $slect1 = 'class="dropbtn2"';
            if ($_GET['cust_odr'] == 'yes')
                $slect2 = 'class="dropbtn2"';
            break;
        case "finish_bill":
            $slect1 = 'class="dropbtn2"';
            break;
        case "payment":
            $slect4 = 'class="dropbtn2"';
            break;
        case "today":
            $slect5 = 'class="dropbtn2"';
            break;
        case "chque_return":
            $slect6 = 'class="dropbtn2"';
            break;
        case "chque_ops":
            $slect7 = 'class="dropbtn2"';
            break;
        case "item_return":
            $slect8 = 'class="dropbtn2"';
            break;
        case "finish_return":
            $slect8 = 'class="dropbtn2"';
            break;
        case "drawer_search":
            $slect9 = 'class="dropbtn2"';
            break;
        case "warranty":
            $slect10 = 'class="dropbtn2"';
            break;
        case "warranty_show":
            $slect10 = 'class="dropbtn2"';
            break;
        case "warranty_inventory":
            $slect10 = 'class="dropbtn2"';
            break;
        case "warranty_repair":
            $slect10 = 'class="dropbtn2"';
            break;
        case "warranty_replace":
            $slect10 = 'class="dropbtn2"';
            break;
        case "warranty_pay":
            $slect10 = 'class="dropbtn2"';
            break;
        case "warranty_cust_pay":
            $slect10 = 'class="dropbtn2"';
            break;
        case "credit":
            $slect11 = 'class="dropbtn2"';
            break;
        case "cust_sale":
            $slect11 = 'class="dropbtn2"';
            break;
        case "sales_report2":
            $slect11 = 'class="dropbtn2"';
            break;
        case "sales_report3":
            $slect11 = 'class="dropbtn2"';
            break;
        case "sale":
            $slect11 = 'class="dropbtn2"';
            break;
        case "cust_bill":
            $slect11 = 'class="dropbtn2"';
            break;
        case "sold_qty":
            $slect11 = 'class="dropbtn2"';
            break;
        case "salesman_commission_new":
            $slect11 = 'class="dropbtn2"';
            break;
        case "salesman_commission_old":
            $slect11 = 'class="dropbtn2"';
            break;
        case "salesman_commission_one":
            $slect11 = 'class="dropbtn2"';
            break;
        case "salesman_commission_incomplete_one":
            $slect11 = 'class="dropbtn2"';
            break;
        case "mk_home":
            $slect11 = 'class="dropbtn2"';
            break;
        case "quotation":
            $slect12 = 'class="dropbtn2"';
            break;
        case "quotation_ongoing":
            $slect12 = 'class="dropbtn2"';
            break;
        case "quotation_list":
            $slect12 = 'class="dropbtn2"';
            break;
        case "qo_one":
            $slect12 = 'class="dropbtn2"';
            break;
        case "qo_terms":
            $slect12 = 'class="dropbtn2"';
            break;
        case "qo_finish":
            $slect12 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ BILL 2 ------------------------ //
if ($_REQUEST['components'] == 'bill2') {
    switch ($_REQUEST['action']) {
        case "home":
            if ($_GET['cust_odr'] == 'no')
                $slect1 = 'class="dropbtn2"';
            if ($_GET['cust_odr'] == 'yes')
                $slect2 = 'class="dropbtn2"';
            break;
        case "bill_item":
            if ($_GET['cust_odr'] == 'no')
                $slect1 = 'class="dropbtn2"';
            if ($_GET['cust_odr'] == 'yes')
                $slect2 = 'class="dropbtn2"';
            break;
        case "pay_bill":
            if ($_GET['cust_odr'] == 'no')
                $slect1 = 'class="dropbtn2"';
            if ($_GET['cust_odr'] == 'yes')
                $slect2 = 'class="dropbtn2"';
            break;
        case "finish_bill":
            $slect1 = 'class="dropbtn2"';
            break;
        case "payment_home":
            $slect4 = 'class="dropbtn2"';
            break;
        case "payment_form":
            $slect4 = 'class="dropbtn2"';
            break;
        case "finish_payment":
            $slect4 = 'class="dropbtn2"';
            break;
        case "today":
            $slect5 = 'class="dropbtn2"';
            break;
        case "chque_return":
            $slect6 = 'class="dropbtn2"';
            break;
        case "chque_ops":
            $slect7 = 'class="dropbtn2"';
            break;
        case "item_return":
            $slect8 = 'class="dropbtn2"';
            break;
        case "finish_return":
            $slect8 = 'class="dropbtn2"';
            break;
        case "drawer_search":
            $slect9 = 'class="dropbtn2"';
            break;
        case "warranty":
            $slect10 = 'class="dropbtn2"';
            break;
        case "warranty_show":
            $slect10 = 'class="dropbtn2"';
            break;
        case "warranty_inventory":
            $slect10 = 'class="dropbtn2"';
            break;
        case "warranty_repair":
            $slect10 = 'class="dropbtn2"';
            break;
        case "warranty_replace":
            $slect10 = 'class="dropbtn2"';
            break;
        case "warranty_pay":
            $slect10 = 'class="dropbtn2"';
            break;
        case "warranty_cust_pay":
            $slect10 = 'class="dropbtn2"';
            break;
        case "credit":
            $slect11 = 'class="dropbtn2"';
            break;
        case "cust_sale":
            $slect11 = 'class="dropbtn2"';
            break;
        case "sales_report2":
            $slect11 = 'class="dropbtn2"';
            break;
        case "sales_report3":
            $slect11 = 'class="dropbtn2"';
            break;
        case "sale":
            $slect11 = 'class="dropbtn2"';
            break;
        case "cust_bill":
            $slect11 = 'class="dropbtn2"';
            break;
        case "show_invoice_pay":
            $slect11 = 'class="dropbtn2"';
            break;
        case "sold_qty":
            $slect11 = 'class="dropbtn2"';
            break;
        case "customer_details":
            $slect11 = 'class="dropbtn2"';
            break;
        case "salesman_commission_new":
            $slect11 = 'class="dropbtn2"';
            break;
        case "salesman_commission_old":
            $slect11 = 'class="dropbtn2"';
            break;
        case "salesman_commission_one":
            $slect11 = 'class="dropbtn2"';
            break;
        case "salesman_commission_incomplete_one":
            $slect11 = 'class="dropbtn2"';
            break;
        case "mk_home":
            $slect11 = 'class="dropbtn2"';
            break;
        case "quotation":
            $slect12 = 'class="dropbtn2"';
            break;
        case "quotation_ongoing":
            $slect12 = 'class="dropbtn2"';
            break;
        case "quotation_list":
            $slect12 = 'class="dropbtn2"';
            break;
        case "quotation_report":
            $slect12 = 'class="dropbtn2"';
            break;
        case "qo_one":
            $slect12 = 'class="dropbtn2"';
            break;
        case "qo_terms":
            $slect12 = 'class="dropbtn2"';
            break;
        case "qo_finish":
            $slect12 = 'class="dropbtn2"';
            break;
        case "cash_payment_deposit":
            $slect13 = 'class="dropbtn2"';
            break;
        case "cash_sent_report":
            $slect13 = 'class="dropbtn2"';
            break;
        case "bank_payment_deposit":
            $slect13 = 'class="dropbtn2"';
            break;
        case "bank_payments_sent_report":
            $slect13 = 'class="dropbtn2"';
            break;
        case "cheque_transfer":
            $slect13 = 'class="dropbtn2"';
            break;
        case "cheque_transfer_summery":
            $slect13 = 'class="dropbtn2"';
            break;
        case "cheque_transfer_status_summery":
            $slect13 = 'class="dropbtn2"';
            break;
        case "cheque_transfer_returns":
            $slect13 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ ORDER PROCESS ------------------------ //
if ($_REQUEST['components'] == 'order_process') {
    switch ($_REQUEST['action']) {
        case "list_custodr":
            $slect1 = 'class="dropbtn2"';
            break;
        case "list_one_custodr":
            $slect1 = 'class="dropbtn2"';
            break;
        case "edit_custodr":
            $slect1 = 'class="dropbtn2"';
            break;
        case "showadd_custodr":
            $slect1 = 'class="dropbtn2"';
            break;
        case "list_pending":
            $slect2 = 'class="dropbtn2"';
            break;
        case "list_my":
            $slect3 = 'class="dropbtn2"';
            break;
        case "list_packed":
            $slect4 = 'class="dropbtn2"';
            break;
        case "list_one":
            $slect4 = 'class="dropbtn2"';
            break;
        case "list_shipped":
            $slect5 = 'class="dropbtn2"';
            break;
        case "list_delivered":
            $slect6 = 'class="dropbtn2"';
            break;
        case "show_check":
            $slect7 = 'class="dropbtn2"';
            break;
        case "one_odr_check":
            $slect7 = 'class="dropbtn2"';
            break;
        case "list_return":
            $slect8 = 'class="dropbtn2"';
            break;
        case "list_unic_return":
            $slect8 = 'class="dropbtn2"';
            break;
        case "report_commision":
            $slect9 = 'class="dropbtn2"';
            break;
        case "report_tracking":
            $slect9 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ REPAIR ------------------------ //
if ($_REQUEST['components'] == 'repair') {
    switch ($_REQUEST['action']) {
        case "list_pending":
            $slect1 = 'class="dropbtn2"';
            break;
        case "list_one":
            $slect1 = 'class="dropbtn2"';
            break;
        case "list_my":
            $slect2 = 'class="dropbtn2"';
            break;
        case "list_rejected":
            $slect3 = 'class="dropbtn2"';
            break;
        case "list_finished":
            $slect4 = 'class="dropbtn2"';
            break;
        case "change_st":
            $slect5 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ TRANS ------------------------ //
if ($_REQUEST['components'] == 'trans') {
    switch ($_REQUEST['action']) {
        case "home":
            $slect1 = 'class="dropbtn2"';
            break;
        case "approval":
            $slect2 = 'class="dropbtn2"';
            break;
        case "today":
            $slect3 = 'class="dropbtn2"';
            break;
        case "last100":
            $slect4 = 'class="dropbtn2"';
            break;
        case "drawer_search":
            $slect5 = 'class="dropbtn2"';
            break;
        case "items_in_transfer":
            $slect6 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ SUPERVISOR ------------------------ //
if ($_REQUEST['components'] == 'supervisor') {
    switch ($_REQUEST['action']) {
        case "sale":
            $slect1 = 'class="dropbtn2"';
            break;
        case "daily_sale":
            $slect1 = 'class="dropbtn2"';
            break;
        case "repair_income":
            $slect2 = 'class="dropbtn2"';
            break;
        case "credit":
            $slect3 = 'class="dropbtn2"';
            break;
        case "sales_byrep":
            $slect4 = 'class="dropbtn2"';
            break;
        case "show_return_summary":
            $slect5 = 'class="dropbtn2"';
            break;
        case "deleted":
            $slect6 = 'class="dropbtn2"';
            break;
        case "chque":
            $slect7 = 'class="dropbtn2"';
            break;
        case "quotation":
            $slect8 = 'class="dropbtn2"';
            break;
        case "quotation_ongoing":
            $slect8 = 'class="dropbtn2"';
            break;
        case "quotation_list":
            $slect8 = 'class="dropbtn2"';
            break;
        case "quotation_report":
            $slect8 = 'class="dropbtn2"';
            break;
        case "qo_finish":
            $slect8 = 'class="dropbtn2"';
            break;
        case "qo_one":
            $slect8 = 'class="dropbtn2"';
            break;
        case "qo_terms":
            $slect8 = 'class="dropbtn2"';
            break;
        case "newcust":
            $slect9 = 'class="dropbtn2"';
            break;
        case "editcust":
            $slect9 = 'class="dropbtn2"';
            break;
        case "disabledcust":
            $slect9 = 'class="dropbtn2"';
            break;
        case "searchcust":
            $slect9 = 'class="dropbtn2"';
            break;
        case "show_custgroup":
            $slect9 = 'class="dropbtn2"';
            break;
        case "edit_custgroup":
            $slect9 = 'class="dropbtn2"';
            break;
        case "temporary_bills":
            $slect10 = 'class="dropbtn2"';
            break;
        case "unlocked":
            $slect10 = 'class="dropbtn2"';
            break;
        case "sn_lookup":
            $slect11 = 'class="dropbtn2"';
            break;
        case "pending_payment_cash_deposits":
            $slect12 = 'class="dropbtn2"';
            break;
        case "pending_payment_bank_deposits":
            $slect12 = 'class="dropbtn2"';
            break;
        case "pending_cheque_transfers":
            $slect12 = 'class="dropbtn2"';
            break;
        case "cash_transfer_deposits_report":
            $slect12 = 'class="dropbtn2"';
            break;
        case "bank_transfer_deposits_report":
            $slect12 = 'class="dropbtn2"';
            break;
        case "cash_on_hand_report":
            $slect12 = 'class="dropbtn2"';
            break;
        case "cheque_transfer":
            $slect12 = 'class="dropbtn2"';
            break;
        case "approved_cheque_transfers":
            $slect12 = 'class="dropbtn2"';
            break;
        case "trans_return_cheque":
            $slect12 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ MANAGER ------------------------ //
if ($_REQUEST['components'] == 'manager') {
    switch ($_REQUEST['action']) {
        case "daily_sale":
            $slect1 = 'class="dropbtn2"';
            break;
        case "daily_sale_detail":
            $slect1 = 'class="dropbtn2"';
            break;
        case "cust_sale":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sales_report2":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sales_report3":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sales_summary":
            $slect1 = 'class="dropbtn2"';
            break;
        case "repair_income":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sales_bycategory":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sales_byrep":
            $slect1 = 'class="dropbtn2"';
            break;
        case "credit":
            $slect1 = 'class="dropbtn2"';
            break;
        case "unvisited":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sold_qty":
            $slect1 = 'class="dropbtn2"';
            break;
        case "warranty":
            $slect1 = 'class="dropbtn2"';
            break;
        case "tax_report":
            $slect1 = 'class="dropbtn2"';
            break;
        case "tax_report_detail":
            $slect1 = 'class="dropbtn2"';
            break;
        case "show_map":
            $slect1 = 'class="dropbtn2"';
            break;
        case "cust_dob":
            $slect2 = 'class="dropbtn2"';
            break;
        case "special_event_sms":
            $slect2 = 'class="dropbtn2"';
            break;
        case "newcust":
            $slect2 = 'class="dropbtn2"';
            break;
        case "editcust":
            $slect2 = 'class="dropbtn2"';
            break;
        case "disabledcust":
            $slect2 = 'class="dropbtn2"';
            break;
        case "searchcust":
            $slect2 = 'class="dropbtn2"';
            break;
        case "show_custgroup":
            $slect2 = 'class="dropbtn2"';
            break;
        case "edit_custgroup":
            $slect2 = 'class="dropbtn2"';
            break;
        case "show_return_summary":
            $slect1 = 'class="dropbtn2"';
            break;
        case "show_return":
            $slect1 = 'class="dropbtn2"';
            break;
        case "return_availability":
            $slect1 = 'class="dropbtn2"';
            break;
        case "show_disposal":
            $slect1 = 'class="dropbtn2"';
            break;
        case "device_mgmt":
            $slect4 = 'class="dropbtn2"';
            break;
        case "temporary_bills":
            $slect5 = 'class="dropbtn2"';
            break;
        case "unlocked":
            $slect5 = 'class="dropbtn2"';
            break;
        case "quotation":
            $slect6 = 'class="dropbtn2"';
            break;
        case "quotation_ongoing":
            $slect6 = 'class="dropbtn2"';
            break;
        case "quotation_approve":
            $slect6 = 'class="dropbtn2"';
            break;
        case "quotation_list":
            $slect6 = 'class="dropbtn2"';
            break;
        case "quotation_report":
            $slect6 = 'class="dropbtn2"';
            break;
        case "qo_one":
            $slect6 = 'class="dropbtn2"';
            break;
        case "qo_terms":
            $slect6 = 'class="dropbtn2"';
            break;
        case "qo_finish":
            $slect6 = 'class="dropbtn2"';
            break;
        case "unic_items":
            $slect7 = 'class="dropbtn2"';
            break;
        case "sn_lookup":
            $slect8 = 'class="dropbtn2"';
            break;
        case "hp_active_list":
            $slect9 = 'class="dropbtn2"';
            break;
        case "hp_deductions":
            $slect9 = 'class="dropbtn2"';
            break;
        case "hp_commission_new":
            $slect9 = 'class="dropbtn2"';
            break;
        case "hp_commission_old":
            $slect9 = 'class="dropbtn2"';
            break;
        case "shipment":
            $slect10 = 'class="dropbtn2"';
            break;
        case "chque_pending_finalyze":
            $slect11 = 'class="dropbtn2"';
            break;
        case "chque_pending_finalyze2":
            $slect11 = 'class="dropbtn2"';
            break;
        case "chque_realize_report_onedate":
            $slect11 = 'class="dropbtn2"';
            break;
        case "chque_realize_report_daterange":
            $slect11 = 'class="dropbtn2"';
            break;
        case "clear_chque_list":
            $slect11 = 'class="dropbtn2"';
            break;
        case "chque_return":
            $slect11 = 'class="dropbtn2"';
            break;
        case "chque_postpone":
            $slect11 = 'class="dropbtn2"';
            break;
        case "list_return":
            $slect12 = 'class="dropbtn2"';
            break;
        case "inv_mgmt":
            $slect13 = 'class="dropbtn2"';
            break;
        case "qty_mgmt":
            $slect14 = 'class="dropbtn2"';
            break;
        case "authorize_code":
            $slect15 = 'class="dropbtn2"';
            break;
        case "payment":
            $slect16 = 'class="dropbtn2"';
            break;
        case "payment_history":
            $slect16 = 'class="dropbtn2"';
            break;
        case "stores_settings":
            $slect17 = 'class="dropbtn2"';
            break;
        case "pending_payment_cash_deposits":
            $slect18 = 'class="dropbtn2"';
            break;
        case "pending_payment_bank_deposits":
            $slect18 = 'class="dropbtn2"';
            break;
        case "pending_cheque_transfers":
            $slect18 = 'class="dropbtn2"';
            break;
        case "cash_transfer_deposits_report":
            $slect18 = 'class="dropbtn2"';
            break;
        case "bank_transfer_deposits_report":
            $slect18 = 'class="dropbtn2"';
            break;
        case "cash_on_hand_report":
            $slect18 = 'class="dropbtn2"';
            break;
        case "cheque_transfer":
            $slect18 = 'class="dropbtn2"';
            break;
        case "approved_cheque_transfers":
            $slect18 = 'class="dropbtn2"';
            break;
        case "cheque_trans_summery":
            $slect18 = 'class="dropbtn2"';
            break;
        case "cheque_transfer_status_summery":
            $slect18 = 'class="dropbtn2"';
            break;
        case "cheque_on_hand":
            $slect18 = 'class="dropbtn2"';
            break;
        case "trans_return_cheque":
            $slect18 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ TOP MANAGER ------------------------ //
if ($_REQUEST['components'] == 'topmanager') {
    switch ($_REQUEST['action']) {
        case "daily_sale":
            $slect1 = 'class="dropbtn2"';
            break;
        case "daily_sale_detail":
            $slect1 = 'class="dropbtn2"';
            break;
        case "cust_sale":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sales_report2":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sales_report3":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sales_summary":
            $slect1 = 'class="dropbtn2"';
            break;
        case "repair_income":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sales_bycategory":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sales_byrep":
            $slect1 = 'class="dropbtn2"';
            break;
        case "credit":
            $slect1 = 'class="dropbtn2"';
            break;
        case "unvisited":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sold_qty":
            $slect1 = 'class="dropbtn2"';
            break;
        case "trans_report":
            $slect1 = 'class="dropbtn2"';
            break;
        case "newcust":
            $slect2 = 'class="dropbtn2"';
            break;
        case "disabledcust":
            $slect2 = 'class="dropbtn2"';
            break;
        case "editcust":
            $slect2 = 'class="dropbtn2"';
            break;
        case "searchcust":
            $slect2 = 'class="dropbtn2"';
            break;
        case "show_custgroup":
            $slect2 = 'class="dropbtn2"';
            break;
        case "edit_custgroup":
            $slect2 = 'class="dropbtn2"';
            break;
        case "show_return_summary":
            $slect1 = 'class="dropbtn2"';
            break;
        case "show_return":
            $slect1 = 'class="dropbtn2"';
            break;
        case "show_disposal":
            $slect1 = 'class="dropbtn2"';
            break;
        case "device_mgmt":
            $slect4 = 'class="dropbtn2"';
            break;
        case "temporary_bills":
            $slect5 = 'class="dropbtn2"';
            break;
        case "unlocked":
            $slect5 = 'class="dropbtn2"';
            break;
        case "quotation":
            $slect6 = 'class="dropbtn2"';
            break;
        case "quotation_ongoing":
            $slect6 = 'class="dropbtn2"';
            break;
        case "quotation_approve":
            $slect6 = 'class="dropbtn2"';
            break;
        case "quotation_list":
            $slect6 = 'class="dropbtn2"';
            break;
        case "quotation_report":
            $slect6 = 'class="dropbtn2"';
            break;
        case "qo_one":
            $slect6 = 'class="dropbtn2"';
            break;
        case "qo_terms":
            $slect6 = 'class="dropbtn2"';
            break;
        case "qo_finish":
            $slect6 = 'class="dropbtn2"';
            break;
        case "unic_items":
            $slect7 = 'class="dropbtn2"';
            break;
        case "sn_lookup":
            $slect8 = 'class="dropbtn2"';
            break;
        case "shipment":
            $slect9 = 'class="dropbtn2"';
            break;
        // case "chque" :
        // 	$slect10='class="dropbtn2"';
        // break;
        case "chque_pending_finalyze":
            $slect10 = 'class="dropbtn2"';
            break;
        case "chque_pending_finalyze2":
            $slect10 = 'class="dropbtn2"';
            break;
        // case "chque_range" :
        // 	$slect10='class="dropbtn2"';
        // break;
        case "chque_realize_report_onedate":
            $slect10 = 'class="dropbtn2"';
            break;
        case "chque_realize_report_daterange":
            $slect10 = 'class="dropbtn2"';
            break;
        case "clear_chque_list":
            $slect10 = 'class="dropbtn2"';
            break;
        case "chque_return":
            $slect10 = 'class="dropbtn2"';
            break;
        case "chque_postpone":
            $slect10 = 'class="dropbtn2"';
            break;
        case "inv_mgmt":
            $slect11 = 'class="dropbtn2"';
            break;
        case "authorize_code":
            $slect12 = 'class="dropbtn2"';
            break;
        case "manage_user":
            $slect13 = 'class="dropbtn2"';
            break;
        case "payment":
            $slect14 = 'class="dropbtn2"';
            break;
        case "payment_history":
            $slect14 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ PO ------------------------ //
if ($_REQUEST['components'] == 'purchase_order') {
    switch ($_REQUEST['action']) {
        case "new_po":
            $slect1 = 'class="dropbtn2"';
            break;
        case "home":
            $slect2 = 'class="dropbtn2"';
            break;
        case "home2":
            $slect2 = 'class="dropbtn2"';
            break;
        case "home3":
            $slect2 = 'class="dropbtn2"';
            break;
        case "home4":
            $slect2 = 'class="dropbtn2"';
            break;
        case "list_po":
            $slect6 = 'class="dropbtn2"';
            break;
        case "one_po":
            $slect6 = 'class="dropbtn2"';
            break;
        case "supplier":
            $slect7 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ HP ------------------------ //
if ($_REQUEST['components'] == 'hire_purchase') {
    switch ($_REQUEST['action']) {
        case "home":
            $slect1 = 'class="dropbtn2"';
            break;
        case "show_invoice_pay":
            $slect2 = 'class="dropbtn2"';
            break;
        case "collection":
            $slect3 = 'class="dropbtn2"';
            break;
        case "invoice_outstanding":
            $slect4 = 'class="dropbtn2"';
            break;
        case "cust_list":
            $slect5 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ MARKETING ------------------------ //
if ($_REQUEST['components'] == 'marketing') {
    switch ($_REQUEST['action']) {
        case "mk_home":
            $slect1 = 'class="dropbtn2"';
            break;
        case "by_sale":
            $slect2 = 'class="dropbtn2"';
            break;
        case "sale":
            $slect3 = 'class="dropbtn2"';
            break;
        case "daily_sale":
            $slect3 = 'class="dropbtn2"';
            break;
        case "sales_report2":
            $slect4 = 'class="dropbtn2"';
            break;
        case "sales_report3":
            $slect4 = 'class="dropbtn2"';
            break;
        case "sales_by_salesman":
            $slect5 = 'class="dropbtn2"';
            break;
        case "cust_sale":
            $slect6 = 'class="dropbtn2"';
            break;
        case "credit":
            $slect7 = 'class="dropbtn2"';
            break;
        case "credit_trend":
            $slect8 = 'class="dropbtn2"';
            break;
        case "catalog":
            $slect9 = 'class="dropbtn2"';
            break;
        case "item_check":
            $slect10 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ HR ------------------------ //
if ($_REQUEST['components'] == 'hr') {
    switch ($_REQUEST['action']) {
        case "home":
            $slect1 = 'class="dropbtn2"';
            break;
        case "my_leave":
            $slect2 = 'class="dropbtn2"';
            break;
        case "leave_list":
            $slect3 = 'class="dropbtn2"';
            break;
        case "shop_staff":
            $slect4 = 'class="dropbtn2"';
            break;
        case "allocate":
            $slect5 = 'class="dropbtn2"';
            break;
        case "leave_report":
            $slect6 = 'class="dropbtn2"';
            break;
        case "inout_report":
            $slect7 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ TO ------------------------ //
if ($_REQUEST['components'] == 'hire_purchase') {
    switch ($_REQUEST['action']) {
        case "home":
            $slect1 = 'class="dropbtn2"';
            break;
    }
}
// ------------------------ ACCOUNTS ------------------------ //
if ($_REQUEST['components'] == 'accounts') {
    switch ($_REQUEST['action']) {
        case "expense":
            $slect1 = 'class="dropbtn2"';
            break;
        case "list_expense":
            $slect1 = 'class="dropbtn2"';
            break;
        case "one_expense":
            $slect1 = 'class="dropbtn2"';
            break;
        case "chart_of_accounts":
            $slect2 = 'class="dropbtn2"';
            break;
        case "acount_history":
            $slect2 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ FINANCE ------------------------ //
if ($_REQUEST['components'] == 'fin') {
    switch ($_REQUEST['action']) {
        case "home":
            $slect1 = 'class="dropbtn2"';
            break;
        case "expense":
            $slect2 = 'class="dropbtn2"';
            break;
        case "list_expense":
            $slect2 = 'class="dropbtn2"';
            break;
        case "one_expense":
            $slect2 = 'class="dropbtn2"';
            break;
        case "journal_entry":
            $slect3 = 'class="dropbtn2"';
            break;
        case "list_journal":
            $slect3 = 'class="dropbtn2"';
            break;
        case "one_journal":
            $slect3 = 'class="dropbtn2"';
            break;
        case "quotation":
            $slect4 = 'class="dropbtn2"';
            break;
        case "quotation_ongoing":
            $slect4 = 'class="dropbtn2"';
            break;
        case "quotation_list":
            $slect4 = 'class="dropbtn2"';
            break;
        case "quotation_report":
            $slect4 = 'class="dropbtn2"';
            break;
        case "qo_one":
            $slect4 = 'class="dropbtn2"';
            break;
        case "qo_terms":
            $slect4 = 'class="dropbtn2"';
            break;
        case "qo_finish":
            $slect4 = 'class="dropbtn2"';
            break;
        case "report":
            $slect5 = 'class="dropbtn2"';
            break;
        case "rep_balance_sheet":
            $slect5 = 'class="dropbtn2"';
            break;
        case "rep_profit_and_loss":
            $slect5 = 'class="dropbtn2"';
            break;
        case "rep_trial_balance":
            $slect5 = 'class="dropbtn2"';
            break;
        case "daily_sale":
            $slect5 = 'class="dropbtn2"';
            break;
        case "cust_sale":
            $slect5 = 'class="dropbtn2"';
            break;
        case "credit":
            $slect5 = 'class="dropbtn2"';
            break;
        case "chque_pending_finalyze":
            $slect6 = 'class="dropbtn2"';
            break;
        case "chque_pending_finalyze2":
            $slect6 = 'class="dropbtn2"';
            break;
        case "chque_realize_report_onedate":
            $slect6 = 'class="dropbtn2"';
            break;
        case "chque_realize_report_daterange":
            $slect6 = 'class="dropbtn2"';
            break;
        case "clear_chque_list":
            $slect6 = 'class="dropbtn2"';
            break;
        case "chque_return":
            $slect6 = 'class="dropbtn2"';
            break;
        case "salary":
            $slect7 = 'class="dropbtn2"';
            break;
        case "one_salary":
            $slect7 = 'class="dropbtn2"';
            break;
        case "payroll":
            $slect8 = 'class="dropbtn2"';
            break;
        case "payroll_list":
            $slect8 = 'class="dropbtn2"';
            break;
        case "payroll_one":
            $slect8 = 'class="dropbtn2"';
            break;
        case "loan":
            $slect9 = 'class="dropbtn2"';
            break;
        case "loan_one":
            $slect9 = 'class="dropbtn2"';
            break;
        case "chart_of_accounts":
            $slect10 = 'class="dropbtn2"';
            break;
        case "one_chart_of_accounts":
            $slect10 = 'class="dropbtn2"';
            break;
        case "acount_history":
            $slect10 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ REPORT ------------------------ //
if ($_REQUEST['components'] == 'report') {
    switch ($_REQUEST['action']) {
        case "sales_report":
            $slect1 = 'class="dropbtn2"';
            break;
        case "category_profit":
            $slect1 = 'class="dropbtn2"';
            break;
        case "profit_report":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sales_trend":
            $slect3 = 'class="dropbtn2"';
            break;
        case "credit_trend":
            $slect3 = 'class="dropbtn2"';
            break;
        case "authorize_code":
            $slect4 = 'class="dropbtn2"';
            break;
        case "deleted":
            $slect5 = 'class="dropbtn2"';
            break;
        case "clear_chque_list":
            $slect16 = 'class="dropbtn2"';
            break;
        case "salesman":
            $slect6 = 'class="dropbtn2"';
            break;
        case "salesman_invoices":
            $slect6 = 'class="dropbtn2"';
            break;
        case "payment_commission":
            $slect7 = 'class="dropbtn2"';
            break;
        case "salesman_commission_new":
            $slect7 = 'class="dropbtn2"';
            break;
        case "salesman_commission_old":
            $slect7 = 'class="dropbtn2"';
            break;
        case "salesman_commission_one":
            $slect7 = 'class="dropbtn2"';
            break;
        case "salesman_commission_one_user":
            $slect7 = 'class="dropbtn2"';
            break;
        case "salesman_commission_incomplete":
            $slect7 = 'class="dropbtn2"';
            break;
        case "salesman_commission_incomplete_one":
            $slect7 = 'class="dropbtn2"';
            break;
        case "chque_pending_finalyze":
            $slect16 = 'class="dropbtn2"';
            break;
        case "chque_pending_finalyze2":
            $slect16 = 'class="dropbtn2"';
            break;
        case "credit":
            $slect8 = 'class="dropbtn2"';
            break;
        case "payment_commision":
            $slect9 = 'class="dropbtn2"';
            break;
        case "unlocked":
            $slect10 = 'class="dropbtn2"';
            break;
        case "return_items":
            $slect11 = 'class="dropbtn2"';
            break;
        case "return_one":
            $slect11 = 'class="dropbtn2"';
            break;
        case "cost":
            $slect12 = 'class="dropbtn2"';
            break;
        case "sub":
            $slect13 = 'class="dropbtn2"';
            break;
        case "approval":
            $slect14 = 'class="dropbtn2"';
            break;
        case "hp_active_list":
            $slect15 = 'class="dropbtn2"';
            break;
        case "hp_deductions":
            $slect15 = 'class="dropbtn2"';
            break;
        case "hp_commission_new":
            $slect15 = 'class="dropbtn2"';
            break;
        case "hp_commission_old":
            $slect15 = 'class="dropbtn2"';
            break;
        case "hp_commission_one":
            $slect15 = 'class="dropbtn2"';
            break;
        case "hp_commission_one_user":
            $slect15 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ SETTINGS ------------------------ //
if ($_REQUEST['components'] == 'settings') {
    switch ($_REQUEST['action']) {
        case "manage_user":
            $slect1 = 'class="dropbtn2"';
            break;
        case "edit_user":
            $slect1 = 'class="dropbtn2"';
            break;
        case "manage_category":
            $slect2 = 'class="dropbtn2"';
            break;
        case "system_settings":
            $slect3 = 'class="dropbtn2"';
            break;
        case "devices":
            $slect4 = 'class="dropbtn2"';
            break;
        case "group_allocation":
            $slect5 = 'class="dropbtn2"';
            break;
        case "bill_edit":
            $slect6 = 'class="dropbtn2"';
            break;
        case "price_edit":
            $slect7 = 'class="dropbtn2"';
            break;
        case "pay_edit":
            $slect8 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ PORTALSUP ------------------------ //
if ($_REQUEST['components'] == 'portalsup') {
    switch ($_REQUEST['action']) {
        case "dashboard":
            $slect1 = 'class="dropbtn2"';
            break;
        case "sales_report":
            $slect2 = 'class="dropbtn2"';
            break;
        case "monthly_sales":
            $slect3 = 'class="dropbtn2"';
            break;
        case "monthly_return":
            $slect4 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ TO ------------------------ //
if ($_REQUEST['components'] == 'to') {
    switch ($_REQUEST['action']) {
        case "quotation":
            $slect1 = 'class="dropbtn2"';
            break;
        case "qo_terms":
            $slect1 = 'class="dropbtn2"';
            break;
        case "qo_finish":
            $slect1 = 'class="dropbtn2"';
            break;
        case "quotation_ongoing":
            $slect1 = 'class="dropbtn2"';
            break;
        case "quotation_list":
            $slect1 = 'class="dropbtn2"';
            break;
        case "quotation_report":
            $slect1 = 'class="dropbtn2"';
            break;
        case "qo_one":
            $slect1 = 'class="dropbtn2"';
            break;
    }
}

// ------------------------ QB ------------------------ //
if ($_REQUEST['components'] == 'qb') {
    switch ($_REQUEST['action']) {
        case "accounts":
            $slect1 = 'class="dropbtn2"';
            break;
        case "general_ledger":
            $slect1 = 'class="dropbtn2"';
            break;
        case "profit_and_loss":
            $slect2 = 'class="dropbtn2"';
            break;
        case "trial_balance":
            $slect3 = 'class="dropbtn2"';
            break;
        case "balance_sheet":
            $slect4 = 'class="dropbtn2"';
            break;
        case "journal_report":
            $slect5 = 'class="dropbtn2"';
            break;
        case "vendors":
            $slect6 = 'class="dropbtn2"';
            break;
        case "customers":
            $slect7 = 'class="dropbtn2"';
            break;
        case "dashboard":
            $slect8 = 'class="dropbtn2"';
            break;
        case "employees":
            $slect9 = 'class="dropbtn2"';
            break;
    }
}
?>