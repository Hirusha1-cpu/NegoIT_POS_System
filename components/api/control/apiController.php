<?php

switch ($_REQUEST['action']) {
    case "sms_ststus":
        include_once 'components/api/modle/apiModule.php';
        smsStstusUpdate();
        break;

    case "sms_pending":
        include_once 'components/api/modle/apiModule.php';
        smsPending();
        include_once 'components/api/view/sms.php';
        break;

    case "test":
        echo "API is working";
        break;

    // product api
    case "fetch_products_data":
        include_once 'components/api/modle/apiModule.php';
        fetchProductsData();
        break;

    case "fetch_product_sales":
        include_once 'components/api/modle/apiModule.php';
        fetchProductSales();
        break;

    case "search_shops":
        include_once 'components/api/modle/apiModule.php';
        searchShops();
        break;

    case "fetch_shop_details":
        include_once 'components/api/modle/apiModule.php';
        fetchShopDetails();
        break;

    default:
        print '<p><strong>Bad Request</strong></p>';
        break;
}
?>