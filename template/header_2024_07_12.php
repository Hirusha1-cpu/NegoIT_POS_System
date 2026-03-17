<?php
include_once  'template/menuselect.php';
if (isset($_COOKIE['user_id'])) $global_user_id = $_COOKIE['user_id'];
else $global_user_id = '';
if (isset($_COOKIE['store'])) $global_store_id = $_COOKIE['store'];
else $global_store_id = '';

$inf_url_primary = inf_url_primary();
$inf_url_backup = inf_url_backup();
$subscription_endin = subscription();
if ($subscription_endin < 10) $subscription_color = 'red';
else $subscription_color = 'black';
if (($subscription_endin < 1) && (isset($_GET['action']))) {
  if ($_GET['action'] != 'expire') print '<script type="text/javascript">window.location = \'index.php?components=authenticate&action=expire\';</script>';
}
$fqdn = $_SERVER['SERVER_NAME'];
$headercolor = '#DDDDDD';
if ($fqdn == $inf_url_primary) {
  if((isset($_COOKIE['sub_system']))){
    $json_array = json_decode(subsystemTheme($sub_system, $global_store_id, false));
    $headercolor = $json_array->{"theme_color"};
  }
} else if ($fqdn == $inf_url_backup) {
  $headercolor = '#FFCA8A';
} else {
  $headercolor = '#DDDDDD';
}
$inf_company = inf_company(1);
$module_count = 0;
if (isset($_COOKIE['sms_balance'])) $smsbalance = $_COOKIE['sms_balance'];
else $smsbalance = 0;

$ssl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
if (($fqdn == $inf_url_primary) && ($fqdn != 'test.negoit.info')) {
  //  if($ssl=='http') header('Location: https://'.$inf_url_primary);
}
$components = $_REQUEST['components'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <script src="js/billing_v2.8.js"></script>
  <link rel="stylesheet" href="css/billing_v1.5.css" type="text/css" media="screen" />
  <title><?php print $inf_company; ?></title>
  <!-- Toaster css -->
  <link rel="stylesheet" href="css/toastr.min.css">
</head>

<body>

  <?php
    if (isset($_COOKIE['user_id'])) {
      if (isset($_COOKIE['store_name'])) $header_st_name = $_COOKIE['store_name'] . ' &nbsp;&nbsp;| ';
      else $header_st_name = '';

      switch ($components){
        case 'inventory':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=inventory";
          break;
        case 'availability':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=availability";
          break;
        case 'trans':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=trans";
          break;
        case 'billing':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=billing";
          break;
        case 'bill2':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=bill2";
          break;
        case 'supervisor':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=supervisor";
          break;
        case 'manager':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=manager";
          break;
        case 'topmanager':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=top_manager";
          break;
        case 'purchase_order':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=po";
          break;
        case 'accounts':
          $guid_link = "https://billinguserguide.negoit.net/";
          break;
        case 'hire_purchase':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=hire_purchase";
          break;
        case 'fin':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=finance";
          break;
        case 'hr':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=hr";
          break;
        case 'report':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=reports";
          break;
        case 'settings':
          $guid_link = "https://billinguserguide.negoit.net/index.php?components=home&action=settings";
          break;
        default:
          $guid_link = "https://billinguserguide.negoit.net/";
          break;
      }

      if (($subscription_endin > 7) && ($smsbalance < 100)) {
        $content = '<span style="color:red">Your SMS Balance is <strong>' . $smsbalance . '</strong></span>&nbsp;&nbsp;&nbsp; Please Reload Your Account';
      } else {
        $content = 'Your Subscription Will Expire in <span style="color:' . $subscription_color . '">' . $subscription_endin . '</span> Days';
      }
      print '<div style="background-color: ' . $headercolor . '; border-radius: 5px;">
              <table width="95%" align="center" cellspacing="0">
                <tr style="background-color:' . $headercolor . '">
                  <td>
                    <input type="button" onclick="window.location = ' . "'$back1'" . '" value="Back" style="width:100px" />
                  </td>
                  <td align="center" style="font-family:Calibri; font-size:12pt;">
                    <div id="notifications">' . $content . '</div>
                  </td>
                  <td style="font-family:Calibri; font-weight:bold; font-size:10pt" align="right">
                      <a href='.$guid_link.' target="_blank"><img src="images/help_blue.png" style="width:13px; width: 14px; padding-right: 6px; padding-top: 5px; display: inline;" /></a>
                      ' . $header_st_name . '<a href="index.php?components=authenticate&action=change_pw" style="text-decoration:none">' . ucfirst($_COOKIE['user']) . '</a>
                      &nbsp;&nbsp;<a href="index.php?components=authenticate&action=logout">LogOut</a>
                  </td>
                </tr>
  		        </table>
            </div>';
  }

  ?>
  <style type="text/css">
    .hederButton {
      background-color: #4678BB;
      border-radius: 4px;
    }

    .hederButton:hover {
      background-color: #3567AA;
      border-radius: 4px;
    }
  </style>
  <table cellspacing="0">
    <tr>
      <td height="1px"></td>
    </tr>
  </table>
  <div style="background-color: <?php print $headercolor; ?>; border-radius: 5px;">
    <table align="center" bgcolor="<?php print $headercolor; ?>" width="95%">
      <?php
        //---------------------------------------Menus---------------------------------------------//
        if (isset($_COOKIE['inventory'])) {
            $module_count += 2;
            if ($systemid == 14) $menu_cat = 'all';
            else $menu_cat = '1';
            if (repairPartReorder()) {
              $localstyle5 = 'style="color:red"';
              $localstyle6 = 'style="font-size:11pt; background-color:#996144;"';
            } else {
              $localstyle5 = '';
              $localstyle6 = 'style="font-size:11pt;"';
            }
            ?>
            <tr>
              <td>
                <div
                    <?php
                        $store = $_COOKIE['store'];
                        if ($components == 'inventory') print 'class="Main_menu1"';
                        else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=inventory&action=show_all_item&category=<?php print $menu_cat; ?>&store=<?php print $store; ?>&type=1" <?php print $localstyle5; ?> title="Inventory">INV</a>
                </div>
              </td>
              <td width="30px"></td>
        <?php }
        if (isset($_COOKIE['check_availability'])) {
          $module_count += 2;
          if ($_COOKIE['direct_mkt'] == 0) $url_ava = 'index.php?components=availability&action=home&category=all';
          else $url_ava = 'index.php?components=availability&action=home&action=catalog';
          ?>
            <td>
              <div <?php if ($components == 'availability') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="<?php print $url_ava; ?>" title="Availability">AVA</a></div>
            </td>
            <td width="30px"></td>
        <?php }
        if (isset($_COOKIE['billing'])) {
          $module_count += 2; ?>
            <td>
              <div <?php if ($components == 'billing') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=billing&action=home&s=<?php print $_COOKIE['user_id']; ?>&cust_odr=no" title="Billing">BILL</a></div>
            </td>
            <td width="30px"></td>
          <?php }

        if (isset($_COOKIE['bill2'])) {
          $module_count += 2; ?>
            <td>
              <div <?php if ($components == 'bill2') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=bill2&action=home&s=<?php print $_COOKIE['user_id']; ?>&cust_odr=no" title="Billing">BILL2</a></div>
            </td>
            <td width="30px"></td>
          <?php }
        if (isset($_COOKIE['order_process'])) {
          $module_count += 2; ?>
            <td>
              <div <?php if ($components == 'order_process') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=order_process&action=list_custodr" title="Order Processing">ODR</a></div>
            </td>
            <td width="30px"></td>
          <?php }
        if (isset($_COOKIE['repair'])) {
          $module_count += 2; ?>
            <td>
              <div <?php if ($components == 'repair') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=repair&action=list_pending" title="Repair">Repair</a></div>
            </td>
            <td width="30px"></td>
          <?php }
        if (isset($_COOKIE['stores_transfer'])) {
          $module_count += 2;
          if (checkPendingGTN()) {
            $localstyle = 'style="color:red"';
            $localstyle3 = 'style="background-color: #996144;"';
          } else {
            $localstyle = $localstyle3 = '';
          }
          ?>
            <td>
              <div <?php if ($components == 'trans') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=trans&action=home" <?php print $localstyle; ?> title="Transfer">Trans</a></div>
            </td>
            <td width="30px"></td>
          <?php }
        if (isset($_COOKIE['supervisor'])) {
          $module_count += 2;
          if (checkPendingCust($sub_system)) {
            $localstyle1 = 'style="color:red"';
            $localstyle2 = 'style="background-color: #996144;"';
          } else {
            $localstyle1 = $localstyle2 = '';
          }
          if (deleteAck()) {
            $localstyle12 = 'style="background-color: #996144;"';
          } else {
            $localstyle12 = '';
          }
          ?>
            <td>
              <div <?php if ($components == 'supervisor') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=supervisor&action=sale&store=<?php print $_COOKIE['store']; ?>&group=all&salesman=all&processby=all&lock=1&type=" <?php print $localstyle1; ?> title="Supervisor">SUP</a></div>
            </td>
            <td width="30px"></td>
            <?php }
        if (isset($_COOKIE['manager'])) {
            $module_count += 2;
            if (checkPendingCust($sub_system)) {
              $localstyle1 = 'style="color:red"';
              $localstyle2 = 'style="background-color: #996144;"';
            } else {
              $localstyle1 = $localstyle2 = '';
            }
            if ($systemid == 13 && $sub_system == 1) { ?>
              <td>
                <div <?php if ($components == 'manager') print 'class="Main_menu1"';
                      else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters"
                    href="index.php?components=manager&action=quotation_approve" <?php print $localstyle1; ?>
                    title="Manager">MGR</a></div>
              </td>
            <?php } else { ?>
              <td>
                <div <?php if ($components == 'manager') print 'class="Main_menu1"';
                      else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters"
                    href="index.php?components=manager&action=daily_sale&store=all&group=all&salesman=all&processby=all&lock=1&cashback=no&type="
                    <?php print $localstyle1; ?> title="Manager">MGR</a></div>
              </td>
            <?php } ?>
            <td width="30px"></td>
          <?php }
        if (isset($_COOKIE['top_manager'])) {
            $module_count += 2;
            if (checkPendingCust('all')) {
              $localstyle1 = 'style="color:red"';
              $localstyle4 = 'style="background-color: #996144;"';
            } else {
              $localstyle1 = $localstyle4 = '';
            }
          ?>
            <td>
              <div <?php if ($components == 'topmanager') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=topmanager&action=daily_sale&store=all&group=all&salesman=all&processby=all&lock=1&cashback=no&type=" <?php print $localstyle1;
                    if ($_REQUEST['components'] == 'topmanager') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?> title="Top Manager">TOP-MGR</a>
            </td>
            <td width="30px"></td>
          <?php }
          if (isset($_COOKIE['purchase_order'])) {
            $module_count += 2; ?>
            <td>
              <div <?php if ($components == 'purchase_order') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=purchase_order&action=home">PO</a></div>
            </td>
            <td width="30px"></td>
          <?php }
          if (isset($_COOKIE['hire_purchase'])) {
            $module_count += 2; ?>
            <td>
              <div <?php if ($components == 'hire_purchase') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=hire_purchase&action=home" title="Hire Purchase">HP</a></div>
            </td>
            <td width="30px"></td>
          <?php }
        if (isset($_COOKIE['marketing'])) {
            $module_count += 2; ?>
            <td>
              <div <?php if ($components == 'marketing') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=marketing&action=mk_home" title="Marketing">MKT</a></div>
            </td>
            <td width="30px"></td>
          <?php }
          if (isset($_COOKIE['accounts'])) {
            $module_count += 2; ?>
            <td>
              <div <?php if ($components == 'accounts') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=accounts&action=expense" title="Accounts">ACC</a></div>
            </td>
            <td width="30px"></td>
          <?php }
        if (isset($_COOKIE['fin'])) {
            $module_count += 2; ?>
            <td>
              <div <?php if ($components == 'fin') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=fin&action=home&id=35&from_date=2000-01-01" title="Finance">FIN</a></div>
            </td>
            <td width="30px"></td>
          <?php }
        if (isset($_COOKIE['hr'])) {
            $module_count += 2;
            if (checkPendingHR($sub_system)) {
              $localstyle9 = 'style="color:red"';
              $localstyle10 = 'style="background-color: #996144;"';
            } else {
              $localstyle9 = $localstyle10 = '';
            }
          ?>
            <td>
              <div <?php if ($components == 'hr') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=hr&action=home" <?php print $localstyle9; ?> title="HR">HR</a></div>
            </td>
            <td width="30px"></td>
          <?php }
        if (isset($_COOKIE['to'])) {
            $module_count += 2; ?>
            <td>
              <div <?php if ($components == 'to') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=to&action=home" title="Technical Officer">TO</a></div>
            </td>
            <td width="30px"></td>
          <?php }
        if (isset($_COOKIE['report'])) {
            $module_count += 2;
            if (requestApproval()) {
              $localstyle7 = 'style="color:red"';
              $localstyle8 = 'style="background-color: #996144;"';
            } else {
              $localstyle7 = $localstyle8 = '';
            }
            if (deleteAck()) {
              $localstyle7 = 'style="color:red"';
              $localstyle11 = 'style="background-color: #996144;"';
            } else {
              $localstyle7 = $localstyle11 = '';
            }
          ?>
            <td>
              <div <?php if ($components == 'report') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=report&action=sales_report" <?php print $localstyle7; ?> title="Report">REP</a></div>
            </td>
            <td width="30px"></td>
          <?php }
        if (isset($_COOKIE['settings'])) {
            $module_count += 1; ?>
            <td>
              <div <?php if ($components == 'settings') print 'class="Main_menu1"';
                    else print 'class="Main_menu2"'; ?>><a class="Main_menu_letters" href="index.php?components=settings&action=manage_user" title="Settings">SET</a></div>
            </td>
        <?php } ?>
      </tr>
      <tr>
        <td colspan="<?php print $module_count; ?>" align="center">
        <?php
          // ------------------------ AVAILABILITY ------------------------ //
          if ($_REQUEST['components'] == 'availability'){
              if($_COOKIE['direct_mkt'] == 0) print '<button onmouseover="billingMenu2()" '.$slect1.' onclick="window.location = \'index.php?components=availability&action=home&category=all\'">Check Availability</button> ';
              if(($systemid == 1 && $approver1) || ($systemid == 14) || ($systemid == 15) || ($systemid == 16) || ($systemid == 17) || ($systemid == 13 && $sub_system == 0)) print '<button onmouseover="billingMenu2()" '.$slect2.' onclick="window.location = \'index.php?components=availability&action=catalog\'">Catalog</button> ';
              if(($systemid == 10) || ($systemid == 13) || ($systemid == 14) || ($systemid == 15) || ($systemid == 16)) print '<button onmouseover="billingMenu2()" '.$slect3.' onclick="window.location = \'index.php?components=availability&action=stock\'">Stock</button> ';
              if(($systemid == 10) || ($systemid == 15)) print '<button onmouseover="billingMenu2()" '.$slect4.' onclick="window.location = \'index.php?components=availability&action=sn_lookup&item_id=\'">SN Lookup</button> ';
              if(($systemid == 10) || ($systemid == 15)) print '<button onmouseover="billingMenu2()" '.$slect5.' onclick="window.location = \'index.php?components=availability&action=sn_lookup_price&item_id=\'">SN Price</button> ';
          }
          // ------------------------ INVENTORY ------------------------ //
          if ($_REQUEST['components'] == 'inventory') {
            if (($systemid != 1) || ($systemid == 1 && $sub_system == 0) || ($systemid == 1 && $sub_system == 1) || ($systemid == 1 && $sub_system == 2)) { ?>
              <a href="index.php?components=inventory&action=show_add_item&type=1" <?php print $slect1; ?>>Add Item</a>
              <a href="index.php?components=inventory&action=show_add_shipment_tmp&sub=show_add_qty_tmp" <?php print $slect2; ?>>Add Qty</a>
              <a href="index.php?components=inventory&action=show_add_shipment_tmp&sub=show_add_unic_tmp" <?php print $slect3; ?>>Add Unique</a>
              <a href="index.php?components=inventory&action=show_edit_item" <?php print $slect4; ?>>Edit Item</a>
              <a href="index.php?components=inventory&action=tag_mgmt" <?php print $slect11; ?>>Tags</a>
              <a href="index.php?components=inventory&action=show_specialprice" <?php print $slect5; ?>>Special Rate</a>
              <a href="index.php?components=inventory&action=show_districtprice" <?php print $slect6; ?>>District Rate</a>
            <?php } ?>
            <?php if ($systemid == 2) { ?>
              <div class="dropdown">
                <button onmouseover="billingMenu2()" onmouseleave="billingMenu2()" <?php print $slect7; ?> onclick="window.location = 'index.php?components=inventory&action=show_all_item&category=1&store=<?php print $_COOKIE['store']; ?>&type=5'">All Items</button>
                <?php if (isset($_COOKIE['report'])) { ?>
                  <div id="myDropdown" class="dropdown-content">
                    <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=inventory&action=show_all_item&category=1&store=<?php print $_COOKIE['store']; ?>&type=1">Product INV</a>
                    <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=inventory&action=show_all_item&category=1&store=<?php print $_COOKIE['store']; ?>&type=5">Unallocated Product INV</a>
                    <?php if ($sub_system == 0) { ?> <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=inventory&action=show_all_item&category=1&store=1&type=<?php print $_COOKIE['store']; ?>">Service INV</a><?php } ?>
                    <?php if ($sub_system == 0) { ?> <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=inventory&action=show_all_item&category=1&store=1&type=<?php print $_COOKIE['store']; ?>">Repair INV</a><?php } ?>
                    <?php if ($sub_system == 0) { ?> <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=inventory&action=show_all_item&category=1&store=1&type=<?php print $_COOKIE['store']; ?>">Repair Parts INV</a><?php } ?>
                  </div>
                <?php } ?>
              </div>
              <?php
            } else {
              if ($_COOKIE['user'] != 'dataentry') {
              ?>
                <div class="dropdown">
                  <button onmouseover="billingMenu2()" onmouseleave="billingMenu2()" <?php print $slect7;
                    print $localstyle6; ?> onclick="window.location = 'index.php?components=inventory&action=show_all_item&category=1&store=1&type=1'">All Items</button>
                  <div id="myDropdown" class="dropdown-content">
                    <?php
                      $store = $_COOKIE['store'];
                    ?>
                    <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=inventory&action=show_all_item&category=1&store=<?php print $store; ?>&type=1">Product INV</a>
                    <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=inventory&action=show_all_item&category=1&store=<?php print $store; ?>&type=5">Unallocated Product INV</a>
                    <?php if ($systemid != 1 || $sub_system == 0) { ?> <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=inventory&action=show_all_item&category=1&store=1&type=2">Service INV</a><?php } ?>
                    <?php if ($systemid != 1 || $sub_system == 0) { ?> <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=inventory&action=show_all_item&category=1&store=1&type=3">Repair INV</a><?php } ?>
                    <?php if ($systemid != 1 || $sub_system == 0) { ?> <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" href="index.php?components=inventory&action=show_all_item&category=1&store=1&type=4" <?php print $localstyle6; ?>>Repair Parts INV</a><?php } ?>
                  </div>
                </div>
            <?php
              }
            }
            ?>
            <a href="index.php?components=inventory&action=show_temp" <?php print $slect8; ?>>New Items</a>
            <a href="index.php?components=inventory&action=drawer_search&st=<?php print $_COOKIE['store']; ?>" <?php print $slect9; ?>>Drawer Search</a>
            <a href="index.php?components=inventory&action=shipmentlist&month=<?php print date("Y-m", time()); ?>" <?php print $slect10; ?>><?php if($systemid == 13 && $sub_system == 1) echo 'Cost of Operations'; else print 'Shipments';?></a>
			<a href="index.php?components=inventory&action=barcode" <?php print $slect12; ?>>Barcode</a>
          <?php }

          // ------------------------ BILLING ------------------------ //
          if ($_REQUEST['components'] == 'billing') { ?>
            <a href="index.php?components=billing&action=home&s=<?php print $_COOKIE['user_id']; ?>&cust_odr=no" <?php print $slect1; ?>>Sales Billing</a>
            <?php if (($systemid == 1) || ($systemid == 10) || ($systemid == 13) || ($systemid == 16) || ($systemid == 17)) { ?>
              <a href="index.php?components=billing&action=home&s=<?php print $_COOKIE['user_id']; ?>&cust_odr=yes" <?php print $slect2; ?>>Cust Order</a> <?php } ?>
            <a href="index.php?components=billing&action=payment" <?php print $slect4; ?>>Payment</a>
            <a href="index.php?components=billing&action=today" <?php print $slect5; ?>>Today Invoices</a>
            <div class="dropdown">
              <button onmouseover="billingMenu2()" onmouseleave="billingMenu2()" onclick="window.location = 'index.php?components=billing&action=quotation'" <?php print $slect12; ?>>Quotation</button>
              <div id="myDropdown" class="dropdown-content">
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=billing&action=quotation">New Quotation</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=billing&action=quotation_ongoing">On-Going Quotation</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=billing&action=quotation_list&cust=&item=&st=&sm=&status=all">Quotation List</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=billing&action=quotation_report&cust=&qo_no=&st=&sm=&status=all">Report</a>
              </div>
            </div>
            <a href="index.php?components=billing&action=chque_return" <?php print $slect6; ?>>Cheque Return</a>
            <a href="index.php?components=billing&action=chque_ops&group=all&salesman=all" <?php print $slect7; ?>>Cheque OPS</a>
            <?php if (($systemid != 15) && ($systemid != 14) && ($systemid != 24)) print '<a href="index.php?components=billing&action=item_return" ' . $slect8 . ' >Item Return</a>'; ?>
            <a href="index.php?components=billing&action=drawer_search&st=<?php print $_COOKIE['store']; ?>" <?php print $slect9; ?>>Drawer Search</a>
            <?php if ($_COOKIE['retail'] || $systemid == 14) print '<a href="index.php?components=billing&action=warranty" ' . $slect10 . ' >Warranty</a>'; ?>
            <div class="dropdown">
              <button onmouseover="billingMenu3()" onmouseleave="billingMenu3()" <?php print $slect11; ?>>Reports</button>
              <div id="myDropdown3" class="dropdown-content">
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=billing&action=credit&st=<?php print $_COOKIE['store']; ?>&gp=&display=2">Credit Report</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=billing&action=cust_sale">Customer Report</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=billing&action=sales_report2">Sales Report2</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=billing&action=cust_bill&search_name=&cu_id=">Customer Bills</a>
                <?php if ($systemid != 1 && $systemid != 4) { ?>
                  <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=billing&action=sale&store=all&group=all&salesman=<?php print $_COOKIE['user_id']; ?>&processby=all&lock=1&type=">My Sales</a>
                <?php } ?>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=billing&action=sold_qty&date=<?php print dateNow(); ?>">Daily Sold Qty</a>
                <?php if((isset($_COOKIE['cus_details_on_billing'])) && ($_COOKIE['cus_details_on_billing'] == 1)){ ?>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=billing&action=mk_home">Customer Details</a>
                <?php } ?>
                <?php if((isset($_COOKIE['commission_on_billing'])) && ($_COOKIE['commission_on_billing'] == 1)){ ?>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=billing&action=salesman_commission_new">Commission Report</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=billing&action=salesman_commission_old">Old Commission Reports</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=billing&action=salesman_commission_incomplete_one">Incomplete Commission Report</a>
                <?php } ?>
            </div>
          <?php }

          // ------------------------ BILL 2 ------------------------ //
          if ($_REQUEST['components'] == 'bill2') { ?>
            <a href="index.php?components=bill2&action=home&s=<?php print $_COOKIE['user_id']; ?>&cust_odr=no" <?php print $slect1; ?>>Sales Billing</a>
            <?php if (($systemid == 1) || ($systemid == 10) || ($systemid == 13) || ($systemid == 16) || ($systemid == 17)) { ?>
              <a href="index.php?components=bill2&action=home&s=<?php print $_COOKIE['user_id']; ?>&cust_odr=yes" <?php print $slect2; ?>>Cust Order</a> <?php } ?>
            <a href="index.php?components=bill2&action=payment_home" <?php print $slect4; ?>>Payment</a>
            <a href="index.php?components=bill2&action=today" <?php print $slect5; ?>>Today Invoices</a>
            <div class="dropdown">
              <button onmouseover="billingMenu2()" onmouseleave="billingMenu2()" onclick="#" <?php print $slect12; ?>>Quotation</button>
              <div id="myDropdown" class="dropdown-content">
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=bill2&action=quotation">New Quotation</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=bill2&action=quotation_ongoing">On-Going Quotation</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=bill2&action=quotation_list&cust=&item=&st=&sm=&status=all">Quotation List</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=bill2&action=quotation_report&cust=&qo_no=&st=&sm=&status=all">Report</a>
              </div>
            </div>
            <a href="index.php?components=bill2&action=chque_return" <?php print $slect6; ?>>Cheque Return</a>
            <a href="index.php?components=bill2&action=chque_ops&group=all&salesman=all" <?php print $slect7; ?>>Cheque OPS</a>
            <?php if ($systemid != 15) print '<a href="index.php?components=bill2&action=item_return" ' . $slect8 . ' >Item Return</a>'; ?>
            <a href="index.php?components=bill2&action=drawer_search&st=<?php print $_COOKIE['store']; ?>" <?php print $slect9; ?>>Drawer Search</a>
            <?php if ($_COOKIE['retail'] || $systemid == 14) print '<a href="index.php?components=bill2&action=warranty" ' . $slect10 . ' >Warranty</a>'; ?>
            <div class="dropdown">
              <button onmouseover="billingMenu3()" onmouseleave="billingMenu3()" <?php print $slect11; ?>>Reports</button>
              <div id="myDropdown3" class="dropdown-content">
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=bill2&action=credit&st=<?php print $_COOKIE['store']; ?>&gp=&display=2">Credit Report</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=bill2&action=cust_sale">Customer Report</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=bill2&action=sales_report2">Sales Report2</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=bill2&action=cust_bill&search_name=&cu_id=">Customer Bills</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=bill2&action=show_invoice_pay">Invoice Pay</a>
                <?php if ($systemid != 1 && $systemid != 4) { ?>
                  <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=bill2&action=sale&store=all&group=all&salesman=<?php print $_COOKIE['user_id']; ?>&processby=all&lock=1&type=">My Sales</a>
                <?php } ?>
                <?php if((isset($_COOKIE['cus_details_on_billing'])) && ($_COOKIE['cus_details_on_billing'] == 1)){ ?>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=bill2&action=mk_home">Customer Details</a>
                <?php } ?>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=bill2&action=sold_qty&date=<?php print dateNow(); ?>">Daily Sold Qty</a>
                <?php if((isset($_COOKIE['commission_on_billing'])) && ($_COOKIE['commission_on_billing'] == 1)){ ?>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=bill2&action=salesman_commission_new">Commission Report</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=bill2&action=salesman_commission_old">Old Commission Reports</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=bill2&action=salesman_commission_incomplete_one">Incomplete Commission Report</a>
                <?php } ?>
              </div>
            </div>
          <?php }

          // ------------------------ ORDER PROCESS ------------------------ //
          if ($_REQUEST['components'] == 'order_process') { ?>
            <a href="index.php?components=order_process&action=list_custodr" <?php print $slect1; ?>>Cust Order</a>
            <button onmouseover="billingMenu2()" <?php print $slect2; ?> onclick="window.location = 'index.php?components=order_process&action=list_pending'">Pending</button>
            <a href="index.php?components=order_process&action=list_my" <?php print $slect3; ?>>My List</a>
            <a href="index.php?components=order_process&action=list_packed" <?php print $slect4; ?>><?php if($systemid == 13 && $sub_system == 1) echo 'Started'; else print 'Packed';?></a>
            <?php if($systemid !=  13 || $sub_system != 1) { ?>
              <a href="index.php?components=order_process&action=list_shipped" <?php print $slect5; ?>>Shipped</a>
            <?php } ?>
            <a href="index.php?components=order_process&action=list_delivered" <?php print $slect6; ?>><?php if($systemid == 13 && $sub_system == 1) echo 'Finished'; else print 'Delivered';?></a>
            <a href="index.php?components=order_process&action=show_check" <?php print $slect7; ?>>Final Check</a>
            <a href="index.php?components=order_process&action=list_return" <?php print $slect8; ?>>Return</a>
            <?php if ($approver2) { ?>
              <div class="dropdown">
                <button onmouseover="billingMenu3()" onmouseleave="billingMenu3()" <?php print $slect9; ?>>Reports</button>
                <div id="myDropdown3" class="dropdown-content">
                  <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=order_process&action=report_commision">Commission Report</a>
                  <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=order_process&action=report_tracking">Tracking List</a>
                </div>
              </div>
            <?php } ?>
            <a href="index.php?components=order_process&action=ring_alert" target="_blank"><img src="images/bell.png" width="20px" align="absmiddle" /></a>
          <?php }

          // ------------------------ REPAIR ------------------------ //
          if ($_REQUEST['components'] == 'repair') { ?>
            <button onmouseover="billingMenu2()" <?php print $slect1; ?> onclick="window.location = 'index.php?components=repair&action=list_pending'">Pending</button>
            <a href="index.php?components=repair&action=list_my" <?php print $slect2; ?>>My List</a>
            <a href="index.php?components=repair&action=list_rejected" <?php print $slect3; ?>>Rejected</a>
            <a href="index.php?components=repair&action=list_finished" <?php print $slect4; ?>>Finished</a>
            <?php if (isset($_COOKIE['manager'])) { ?>
              <a href="index.php?components=repair&action=change_st" <?php print $slect5; ?>>MGR</a>
            <?php } ?>
          <?php }

          // ------------------------ TRANS ------------------------ //
          if ($_REQUEST['components'] == 'trans') { ?>
            <button onmouseover="billingMenu2()" <?php print $slect1; ?> onclick="window.location = 'index.php?components=trans&action=home'">New</button>
            <a href="index.php?components=trans&action=approval" <?php print $localstyle3;
                                                                  print $slect2; ?>>Approval Pending</a>
            <a href="index.php?components=trans&action=today" <?php print $slect3; ?>>Today</a>
            <a href="index.php?components=trans&action=last100" <?php print $slect4; ?>>Last100</a>
            <a href="index.php?components=trans&action=drawer_search&st=<?php print $_COOKIE['store']; ?>" <?php print $slect5; ?>>Drawer Search</a>
            <?php if ($approver1) print '<a href="index.php?components=trans&action=items_in_transfer" '.$slect6.'>Items in Transfer</a>'; ?>
          <?php }

          // ------------------------ SUPERVISOR ------------------------ //
          if ($_REQUEST['components'] == 'supervisor') { ?>
            <a href="index.php?components=supervisor&action=sale&store=<?php print $_COOKIE['store']; ?>&group=all&salesman=all&processby=all&lock=1&type=" <?php print $slect1; ?>>Sales Report</a>
            <a href="index.php?components=supervisor&action=repair_income" <?php print $slect2; ?>>Repair Income</a>
            <a href="index.php?components=supervisor&action=credit&st=<?php print $_COOKIE['store']; ?>&gp=&display=2" <?php print $slect3; ?>>Credit Report</a>
            <a href="index.php?components=supervisor&action=sales_byrep" <?php print $slect4; ?>>Sales by Rep</a>
            <a href="index.php?components=supervisor&action=show_return_summary&sm=all&cu=" <?php print $slect5; ?>>Return Invoices</a>
            <?php if (isDeletedMenuActive())
                    print '<a href="index.php?components=supervisor&action=deleted"  ' . $localstyle12 . ' ' . $slect6 . ' >Deleted</a>'; ?>
            <a href="index.php?components=supervisor&action=chque&group=all&salesman=all" <?php print $slect7; ?>>Cheque</a>
            <div class="dropdown">
              <button onmouseover="billingMenu2()" onmouseleave="billingMenu2()" onclick="window.location = 'index.php?components=supervisor&action=quotation'" <?php print $slect8; ?>>Quotation</button>
              <div id="myDropdown" class="dropdown-content">
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=supervisor&action=quotation">New Quotation</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=supervisor&action=quotation_ongoing">On-Going Quotation</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=supervisor&action=quotation_list&cust=&item=&st=&sm=&status=all">Quotation List</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=supervisor&action=quotation_report&cust=&qo_no=&st=&sm=&status=all">Report</a>
              </div>
            </div>
            <div class="dropdown">
              <button onmouseover="billingMenu3()" onmouseleave="billingMenu3()" <?php print $slect10; ?>>Incompleted</button>
              <div id="myDropdown3" class="dropdown-content">
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=supervisor&action=unlocked">Unlocked Bills</a>
                <?php if(bill_module(1) == 'bill2') { ?> <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=supervisor&action=temporary_bills">Temporary Bills</a> <?php } ?>
              </div>
            </div>
            <a href="index.php?components=supervisor&action=newcust" <?php print $localstyle2; print $slect9; ?>>Customer MGMT</a>
            <a href="index.php?components=supervisor&action=sn_lookup&item_id=" <?php print $slect11; ?>>S/N Lookup</a>
          <?php }

          // ------------------------ MANAGER ------------------------ //
          if ($_REQUEST['components'] == 'manager') { ?>
            <!-- Reports dropdown-->
            <div class="dropdown">
              <button onmouseover="billingMenu2()" onmouseleave="billingMenu2()" <?php print $slect1; ?>>Reports</button>
              <div id="myDropdown" class="dropdown-content">
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=daily_sale&store=all&group=all&salesman=all&processby=all&lock=1&cashback=no&type=">Daily Sales</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=cust_sale">Customer Report</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=sales_report2">Sales Report2</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=sales_summary">Sales Summary</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=repair_income">Repair Income</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=sales_bycategory">Sales by Category</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=sales_byrep">Sales by Rep</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=unvisited&type=unvisited&asso_salesman=all">Unvisited Customers</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=credit&st=<?php print $_COOKIE['store']; ?>&display=2">Credit Report</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=show_return_summary&sm=all&cu=">Return Invoices</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=show_return&sm=all&cu=">Return Items</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=show_disposal">Disposal</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=sold_qty&store=all">Daily Sold Qty</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=warranty&store=all">Warranty</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=manager&action=tax_report">Tax Report</a>
              </div>
            </div>
            <!--/ Reports dropdown-->
            <?php if((isset($_COOKIE['cust_dob_on_manager'])) && ($_COOKIE['cust_dob_on_manager'] == 1)){ ?>
              <div class="dropdown">
                <button onmouseover="billingMenu5()" onmouseleave="billingMenu5()" <?php print $localstyle2; print $slect2; ?> >Cust MGMT</button>
                <div id="myDropdown5" class="dropdown-content">
                  <a onmouseover="billingMenu5()" onmouseleave="billingMenu5()" style="font-size:11pt" href="index.php?components=manager&action=newcust">Customer Management</a>
                  <a onmouseover="billingMenu5()" onmouseleave="billingMenu5()" style="font-size:11pt" href="index.php?components=manager&action=cust_dob">Customer Birthday</a>
                </div>
              </div>
            <?php }else{ ?>
              <a href="index.php?components=manager&action=newcust" <?php print $localstyle2; print $slect2; ?>>Cust MGMT</a>
            <?php } ?>
            <a href="index.php?components=manager&action=device_mgmt" <?php print $slect4; ?>>Device MGMT</a>

            <div class="dropdown">
              <button onmouseover="billingMenu3()" onmouseleave="billingMenu3()" <?php print $slect5; ?>>Incompleted</button>
              <div id="myDropdown3" class="dropdown-content">
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=manager&action=unlocked">Unlocked Bills</a>
                <?php if(bill_module(1) == 'bill2') { ?>  <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=manager&action=temporary_bills">Temporary Bills</a> <?php } ?>
              </div>
            </div>

            <!-- Quatation dropdown-->
            <div class="dropdown">
              <button onmouseover="billingMenu4()" onmouseleave="billingMenu4()" onclick="window.location = 'index.php?components=manager&action=quotation'" <?php print $slect6; ?>>Quotation</button>
              <div id="myDropdown4" class="dropdown-content">
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=manager&action=quotation">New Quotation</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=manager&action=quotation_ongoing">On-Going Quotation</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=manager&action=quotation_approve">Approve Quotation</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=manager&action=quotation_list&cust=&item=&st=&sm=&status=all">Quotation List</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=manager&action=quotation_report&cust=&qo_no=&st=&sm=&status=all">Report</a>
              </div>
            </div>
            <!--/ Quatation dropdown-->
            <a href="index.php?components=manager&action=unic_items&item&store=all&status=0" <?php print $slect7; ?>>Unique <img src="images/search.png" style="width:18px"/></a>
            <a href="index.php?components=manager&action=sn_lookup&item_id=" <?php print $slect8; ?>>S/N <img src="images/search.png" style="width:18px" /></a>
            <!-- Hirepurchase dropdown -->
            <div class="dropdown">
              <button onmouseover="billingMenu6()" onmouseleave="billingMenu6()" <?php print $slect9; ?>>Hire Purchase</button>
              <div id="myDropdown6" class="dropdown-content">
                <a onmouseover="billingMenu6()" onmouseleave="billingMenu6()" style="font-size:11pt" href="index.php?components=manager&action=hp_active_list">Active List</a>
                <?php if ($systemid != 15) { ?>
                  <a onmouseover="billingMenu6()" onmouseleave="billingMenu6()" style="font-size:11pt" href="index.php?components=manager&action=hp_deductions&rec_agent=all&type=all">Delay Payment - Deductions</a>
                  <a onmouseover="billingMenu6()" onmouseleave="billingMenu6()" style="font-size:11pt" href="index.php?components=manager&action=hp_commission_new">New Commission Report</a>
                  <a onmouseover="billingMenu6()" onmouseleave="billingMenu6()" style="font-size:11pt" href="index.php?components=manager&action=hp_commission_old">Old Commission Reports</a>
                <?php } ?>
              </div>
            </div>
            <!-- /Hirepurchase dropdown -->
            <?php
              if($systemid == 13 && $sub_system == 1){
                print '<a href="index.php?components=manager&action=shipment" ' . $slect10 . '>Cost of Operations</a>';
              }
              if($sub_system == 0){
                print '<a href="index.php?components=manager&action=shipment" ' . $slect10 . '>Shipment</a>';
              }
            ?>
            <!-- Cheque dropdown -->
            <div class="dropdown">
              <button onmouseover="billingMenu7()" onmouseleave="billingMenu7()" <?php print $slect11; ?>>Cheque</button>
              <div id="myDropdown7" class="dropdown-content">
                <a onmouseover="billingMenu7()" onmouseleave="billingMenu7()" style="font-size:11pt" href="index.php?components=manager&action=chque_pending_finalyze">Cheque Pending Finalyze</a>
                <a onmouseover="billingMenu7()" onmouseleave="billingMenu7()" style="font-size:11pt" href="index.php?components=manager&action=chque_realize_report_onedate">Cheques to be Realized</a>
                <a onmouseover="billingMenu7()" onmouseleave="billingMenu7()" style="font-size:11pt" href="index.php?components=manager&action=chque_return">Cheque Return</a>
                <a onmouseover="billingMenu7()" onmouseleave="billingMenu7()" style="font-size:11pt" href="index.php?components=manager&action=chque_postpone">Cheque Postpone</a>
              </div>
            </div>
            <!--/ Cheque dropdown -->
            <a href="index.php?components=manager&action=list_return" <?php print $slect12; ?>>Return MGMT</a>
            <a href="index.php?components=manager&action=inv_mgmt&type=" <?php print $slect13; ?>>INV MGMT</a>
            <a href="index.php?components=manager&action=qty_mgmt" <?php print $slect14; ?>>Set QTY</a>
            <?php if($systemid!=15) print '<a href="index.php?components=manager&action=authorize_code" title="Authorize Code" '.$slect15.' >Auth Code</a>'; ?>
            <!-- Payment dropdown -->
            <div class="dropdown">
              <button onmouseover="billingMenu8()" onmouseleave="billingMenu8()" onclick="window.location = 'index.php?components=manager&action=payment'" <?php print $slect16; ?>>Payment</button>
              <div id="myDropdown8" class="dropdown-content">
                <a onmouseover="billingMenu8()" onmouseleave="billingMenu8()" style="font-size:11pt" href="index.php?components=manager&action=payment">Payment Dashboard</a>
                <a onmouseover="billingMenu8()" onmouseleave="billingMenu8()" style="font-size:11pt" href="index.php?components=manager&action=payment_history">Report History</a>
              </div>
            </div>
            <!--/ Payment dropdown -->
          <?php }

          // ------------------------ TOP MANAGER ------------------------ //
          if ($_REQUEST['components'] == 'topmanager') { ?>
            <div class="dropdown">
              <button onmouseover="billingMenu2()" onmouseleave="billingMenu2()" <?php print $slect1; ?>>Reports</button>
              <div id="myDropdown" class="dropdown-content">
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=daily_sale&store=all&group=all&salesman=all&processby=all&lock=1&cashback=no&type=">Daily Sales</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=cust_sale">Customer Report</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=sales_report2">Sales Report2</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=sales_summary">Sales Summary</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=repair_income">Repair Income</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=sales_bycategory">Sales by Category</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=sales_byrep">Sales by Rep</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=unvisited&type=unvisited&asso_salesman=all">Unvisited Customers</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=credit&st=<?php print $_COOKIE['store']; ?>&display=2&sub_system=0">Credit Report</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=show_return_summary&sm=all&cu=">Return Invoices</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=show_return&sm=all&cu=">Return Items</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=show_disposal">Disposal</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=topmanager&action=sold_qty&store=all">Daily Sold Qty</a>
              </div>
            </div>
            <a href="index.php?components=topmanager&action=newcust" <?php print $localstyle4; print $slect2; ?>>Customer MGMT</a>
            <a href="index.php?components=topmanager&action=device_mgmt" <?php print $slect4; ?>>Device MGMT</a>
            <div class="dropdown">
              <button onmouseover="billingMenu3()" onmouseleave="billingMenu3()" <?php print $slect5; ?>>Incompleted</button>
              <div id="myDropdown3" class="dropdown-content">
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=topmanager&action=unlocked">Unlocked Bills</a>
                <?php if(bill_module(1) == 'bill2') { ?> <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=topmanager&action=temporary_bills">Temporary Bills</a> <?php } ?>
              </div>
            </div>
            <div class="dropdown">
              <button onmouseover="billingMenu4()" onmouseleave="billingMenu4()" onclick="window.location = 'index.php?components=topmanager&action=quotation'" <?php print $slect6; ?>>Quotation</button>
              <div id="myDropdown4" class="dropdown-content">
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=topmanager&action=quotation">New Quotation</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=topmanager&action=quotation_ongoing">On-Going Quotation</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=topmanager&action=quotation_approve">Approve Quotation</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=topmanager&action=quotation_list&cust=&item=&st=&sm=&status=all">Quotation List</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=topmanager&action=quotation_report&cust=&qo_no=&st=&sm=&status=all">Report</a>
              </div>
            </div>
            <a href="index.php?components=topmanager&action=unic_items&item&store=all&status=0" <?php print $slect7; ?>>Unique <img src="images/search.png" style="width:18px" /></a>
            <a href="index.php?components=topmanager&action=sn_lookup&item_id=" <?php print $slect8; ?>>S/N <img src="images/search.png" style="width:18px" /></a>
            <a href="index.php?components=topmanager&action=shipment" <?php print $slect9; ?>><?php if($systemid == 13 && $sub_system == 1) echo 'Cost of Operations'; else print 'Shipments';?></a>
            <div class="dropdown">
              <button onmouseover="billingMenu5()" onmouseleave="billingMenu5()" <?php print $slect10; ?>>Cheque</button>
              <div id="myDropdown5" class="dropdown-content">
                <a onmouseover="billingMenu5()" onmouseleave="billingMenu5()" style="font-size:11pt" href="index.php?components=topmanager&action=chque_pending_finalyze">Cheque Pending Finalyze</a>
                <a onmouseover="billingMenu5()" onmouseleave="billingMenu5()" style="font-size:11pt" href="index.php?components=topmanager&action=chque_realize_report_onedate">Cheque Realize Report</a>
                <a onmouseover="billingMenu5()" onmouseleave="billingMenu5()" style="font-size:11pt" href="index.php?components=topmanager&action=chque_return">Cheque Return</a>
                <a onmouseover="billingMenu5()" onmouseleave="billingMenu5()" style="font-size:11pt" href="index.php?components=topmanager&action=chque_postpone">Cheque Postpone</a>
              </div>
            </div>
            <a href="index.php?components=topmanager&action=inv_mgmt&type=" <?php print $slect11; ?>>INV MGMT</a>
            <a href="index.php?components=topmanager&action=authorize_code" title="Authorize Code" <?php print $slect12; ?>>Auth Code</a>
            <a href="index.php?components=topmanager&action=manage_user" <?php print $slect13; ?>>Manage Users</a>
            <div class="dropdown">
              <button onmouseover="billingMenu8()" onmouseleave="billingMenu8()" onclick="window.location = 'index.php?components=topmanager&action=payment'" <?php print $slect14; ?>>Payment</button>
              <div id="myDropdown8" class="dropdown-content">
                <a onmouseover="billingMenu8()" onmouseleave="billingMenu8()" style="font-size:11pt" href="index.php?components=topmanager&action=payment">Payment Dashboard</a>
                <a onmouseover="billingMenu8()" onmouseleave="billingMenu8()" style="font-size:11pt" href="index.php?components=topmanager&action=payment_history">Report History</a>
              </div>
            </div>


          <?php }

          // ------------------------ PURCHASE ORDER ------------------------ //
          if ($_REQUEST['components'] == 'purchase_order') { ?>
            <button onmouseover="billingMenu2()" <?php print $slect1; ?> onclick="window.location = 'index.php?components=purchase_order&action=new_po'">New PO</button>
            <div class="dropdown">
              <button onmouseover="billingMenu3()" onmouseleave="billingMenu3()" onclick="" <?php print $slect2; ?>>Generate PO</button>
              <div id="myDropdown3" class="dropdown-content">
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt; width:150px;" href="index.php?components=purchase_order&action=home">Total Sales | Store</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt; width:150px;" href="index.php?components=purchase_order&action=home2">Monthly Sales | Store</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt; width:150px;" href="index.php?components=purchase_order&action=home3">Total Sales | Group</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt; width:150px;" href="index.php?components=purchase_order&action=home4">Monthly Sales | Group</a>
              </div>
            </div>
            <a href="index.php?components=purchase_order&action=list_po" <?php print $slect6; ?>>PO List</a>
            <?php if ((isset($_COOKIE['manager'])) || (isset($_COOKIE['top_manager']))) print '<a href="index.php?components=purchase_order&action=supplier" ' . $slect7 . ' >Supplier</a>'; ?>
          <?php }

          // ------------------------ HR ------------------------ //
          if ($_REQUEST['components'] == 'hr') { ?>
            <button onmouseover="billingMenu2()" <?php print $slect1; ?> onclick="window.location = 'index.php?components=hr&action=home'">Apply Leave</button>
            <button onmouseover="billingMenu2()" <?php print $slect2; ?> onclick="window.location = 'index.php?components=hr&action=my_leave'">My Leave</button>
            <?php if ($approver1) { ?>
              <button onmouseover="billingMenu2()" <?php print $slect3; ?> onclick="window.location = 'index.php?components=hr&action=leave_list'" <?php print $localstyle10; ?>>Leave List</button>
            <?php }
            if ($approver2) { ?>
              <button onmouseover="billingMenu2()" <?php print $slect4; ?> onclick="window.location = 'index.php?components=hr&action=shop_staff'">Shop Staff</button>
            <?php }
            if ($approver1) { ?>
              <button onmouseover="billingMenu2()" <?php print $slect5; ?> onclick="window.location = 'index.php?components=hr&action=allocate'">Leave Allocate</button>
              <button onmouseover="billingMenu2()" <?php print $slect6; ?> onclick="window.location = 'index.php?components=hr&action=leave_report'">Leave Report</button>
              <button onmouseover="billingMenu2()" <?php print $slect7; ?> onclick="window.location = 'index.php?components=hr&action=inout_report'">In-Out Report</button>
            <?php }
          }

          // ------------------------ HIRE PURCHASE ------------------------ //
          if ($_REQUEST['components'] == 'hire_purchase') { ?>
            <button onmouseover="billingMenu2()" <?php print $slect1; ?> onclick="window.location = 'index.php?components=hire_purchase&action=home'">Home</button>
            <a href="index.php?components=hire_purchase&action=show_invoice_pay" <?php print $slect2; ?>>Invoice Pay</a>
            <a href="index.php?components=hire_purchase&action=collection&rag_id=<?php print $_COOKIE['user_id']; ?>" <?php print $slect3; ?>>Collection</a>
            <a href="index.php?components=hire_purchase&action=invoice_outstanding&rag_id=<?php print $_COOKIE['user_id']; ?>" <?php print $slect4; ?>>Outstanding</a>
            <a href="index.php?components=hire_purchase&action=cust_list" <?php print $slect5; ?>>Customer List</a>


          <?php }

          // ------------------------ MARKETING ------------------------ //
          if ($_REQUEST['components'] == 'marketing') { ?>
            <button onmouseover="billingMenu2()" <?php print $slect1; ?> onclick="window.location = 'index.php?components=marketing&action=mk_home'">Home</button>
            <a href="index.php?components=marketing&action=by_sale" <?php print $slect2; ?>>By Sale</a>
            <a href="index.php?components=marketing&action=sale&store=all&group=all&salesman=all&processby=all&lock=1&type=" <?php print $slect3; ?>>Sales Report</a>
            <a href="index.php?components=marketing&action=sales_report2" <?php print $slect4; ?>>Sales Report2</a>
            <a href="index.php?components=marketing&action=sales_by_salesman&store=all&group=all&salesman=all&processby=all&lock=1&type=" <?php print $slect5; ?>>Sales-Salesman</a>
            <a href="index.php?components=marketing&action=cust_sale" <?php print $slect6; ?>>Customer Report</a>
            <a href="index.php?components=marketing&action=credit&st=1&display=2&sub_system=0" <?php print $slect7; ?>>Credit Report</a>
            <a href="index.php?components=marketing&action=credit_trend" <?php print $slect8; ?>>Credit Trend</a>
            <a href="index.php?components=marketing&action=catalog" <?php print $slect9; ?>>Catalog</a>
            <a href="index.php?components=marketing&action=item_check" <?php print $slect10; ?>>Item Check</a>
          <?php }

          // ------------------------ ACCOUNTS ------------------------ //
          if ($_REQUEST['components'] == 'accounts') { ?>
              <button style="font-size:11pt" onclick="window.location = 'index.php?components=accounts&action=expense'" <?php print $slect1; ?>>Expenses</button>
              <button style="font-size:11pt" onclick="window.location = 'index.php?components=accounts&action=chart_of_accounts'" <?php print $slect2; ?>>Chart of Accounts</button>
          <?php }

          // ------------------------ FINANZE ------------------------ //
          if ($_REQUEST['components'] == 'fin') { ?>
            <a href="index.php?components=fin&action=home&id=35&from_date=2000-01-01" <?php print $slect1; ?>>Dashboard</a>
            <a href="index.php?components=fin&action=expense" <?php print $slect2; ?>>Expense</a>
            <a href="index.php?components=fin&action=journal_entry" <?php print $slect3; ?>>Journal Entry</a>
            <div class="dropdown">
              <button onmouseover="billingMenu2()" onmouseleave="billingMenu2()" onclick="window.location = 'index.php?components=fin&action=quotation'" <?php print $slect4; ?>>Quotation</button>
              <div id="myDropdown" class="dropdown-content">
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=fin&action=quotation">New Quotation</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=fin&action=quotation_ongoing">On-Going Quotation</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=fin&action=quotation_list&cust=&item=&st=&sm=&status=all">Quotation List</a>
                <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=fin&action=quotation_report&cust=&qo_no=&st=&sm=&status=all">Report</a>
              </div>
            </div>
            <div class="dropdown">
              <button onmouseover="billingMenu3()" onmouseleave="billingMenu3()" <?php print $slect5; ?>>Reports</button>
              <div id="myDropdown3" class="dropdown-content">
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=fin&action=report&to_date=<?php print dateNow(); ?>">FIN Report</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=fin&action=daily_sale&store=all&group=all&salesman=all&processby=all&lock=1&cashback=no&type=">Daily Sales</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=fin&action=cust_sale">Customer Report</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=fin&action=credit&st=&display=2">Credit Report</a>
              </div>
            </div>
            <div class="dropdown">
              <button onmouseover="billingMenu4()" onmouseleave="billingMenu4()" <?php print $slect6; ?>>Cheques</button>
              <div id="myDropdown4" class="dropdown-content">
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=fin&action=chque_pending_finalyze">Cheque Pending Finalyze</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=fin&action=chque_realize_report_onedate">Cheque Realize Report</a>
                <!-- <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=fin&action=chque">Cheque</a> -->
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=fin&action=chque_return">Cheque Return</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=fin&action=chque_postpone">Cheque Postpone</a>
              </div>
            </div>
            <a href="index.php?components=fin&action=salary" <?php print $slect7; ?>>Salary</a>
            <a href="index.php?components=fin&action=payroll" <?php print $slect8; ?>>Payroll</a>
            <a href="index.php?components=fin&action=loan" <?php print $slect9; ?>>Employee Loan</a>
            <a href="index.php?components=fin&action=chart_of_accounts" <?php print $slect10; ?>>Chart of Accounts</a>


          <?php }

          // ------------------------ REPORT ------------------------ //
          if ($_REQUEST['components'] == 'report') { ?>
            <div class="dropdown">
              <button onmouseover="billingMenu5()" onmouseleave="billingMenu5()" <?php print $slect1; ?>>Reports</button>
              <div id="myDropdown5" class="dropdown-content">
                <a onmouseover="billingMenu5()" onmouseleave="billingMenu5()" style="font-size:11pt" href="index.php?components=report&action=sales_report">Sales Report</a>
                <a onmouseover="billingMenu5()" onmouseleave="billingMenu5()" style="font-size:11pt" href="index.php?components=report&action=category_profit&subsys=all">Category <?php if($systemid==1) print 'Cash'; else print 'Profit'; ?></a>
                <?php if(isProfitReportActive()){ ?><a onmouseover="billingMenu5()" onmouseleave="billingMenu5()" style="font-size:11pt" href="index.php?components=report&action=profit_report" >Profit Report</a> <?php } ?>
              </div>
            </div>
            <div class="dropdown">
              <button onmouseover="billingMenu3()" onmouseleave="billingMenu3()" <?php print $slect3; ?>>Trend</button>
              <div id="myDropdown3" class="dropdown-content">
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=report&action=sales_trend&sys=0">Sales Trend</a>
                <a onmouseover="billingMenu3()" onmouseleave="billingMenu3()" style="font-size:11pt" href="index.php?components=report&action=credit_trend">Creadit Trend</a>
              </div>
            </div>

            <a href="index.php?components=report&action=deleted" <?php print $localstyle11; print $slect5; ?>>Deleted</a>
            <a href="index.php?components=report&action=salesman" <?php print $slect6; ?>>Salesman</a>
            <!-- <a href="index.php?components=report&action=chque" <?php print $slect7; ?>>Cheque</a> -->
            <div class="dropdown">
              <button onmouseover="billingMenu7()" onmouseleave="billingMenu7()" <?php print $slect16; ?>>Cheque</button>
              <div id="myDropdown7" class="dropdown-content">
                <a  onmouseover="billingMenu7()" onmouseleave="billingMenu7()" style="font-size:11pt" href="index.php?components=report&action=chque_pending_finalyze">Cheque Pending Finalyze</a>
              </div>
            </div>
            <a href="index.php?components=report&action=credit&st=&gp=&display=2&sub_system=0" <?php print $slect8; ?>>Credit</a>
            <div class="dropdown">
              <button onmouseover="billingMenu4()" onmouseleave="billingMenu4()" <?php print $slect7; ?>>Commission</button>
              <div id="myDropdown4" class="dropdown-content">
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=report&action=payment_commision">Commission</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=report&action=salesman_commission_new">New Salesman Commission Report</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=report&action=salesman_commission_old">Old Salesman Commission Report</a>
                <a onmouseover="billingMenu4()" onmouseleave="billingMenu4()" style="font-size:11pt" href="index.php?components=report&action=salesman_commission_incomplete">Incomplete Commission Report</a>
              </div>
            </div>
            <div class="dropdown">
              <button onmouseover="billingMenu6()" onmouseleave="billingMenu6()" <?php print $slect15; ?>>Hire Purchase</button>
              <div id="myDropdown6" class="dropdown-content">
                <a onmouseover="billingMenu6()" onmouseleave="billingMenu6()" style="font-size:11pt" href="index.php?components=report&action=hp_active_list">Active List</a>
                <a onmouseover="billingMenu6()" onmouseleave="billingMenu6()" style="font-size:11pt" href="index.php?components=report&action=hp_deductions&rec_agent=all&type=all">Delay Payment - Deductions</a>
                <a onmouseover="billingMenu6()" onmouseleave="billingMenu6()" style="font-size:11pt" href="index.php?components=report&action=hp_commission_new">New Commission Report</a>
                <a onmouseover="billingMenu6()" onmouseleave="billingMenu6()" style="font-size:11pt" href="index.php?components=report&action=hp_commission_old">Old Commission Reports</a>
              </div>
            </div>
            <a href="index.php?components=report&action=unlocked" <?php print $slect10; ?>>Unlocked</a>
            <a href="index.php?components=report&action=return_items&sm=" <?php print $slect11; ?>>Return Items</a>
            <a href="index.php?components=report&action=cost" <?php print $slect12; ?>>Cost</a>
            <div class="dropdown">
              <button onmouseover="billingMenu2()" <?php print $slect13; ?>>Sub Reports</button>
              <div id="myDropdown" class="dropdown-content">
                <a style="font-size:11pt" href="index.php?components=report&action=sub&report_type=newcust_salesman&store=all">New Customers by Salesman</a>
                <a style="font-size:11pt" href="index.php?components=report&action=sub&report_type=itembysalesman">Item Sales by Salesman</a>
                <a style="font-size:11pt" href="index.php?components=report&action=sub&report_type=useraudit">User Audit</a>
                <a style="font-size:11pt" href="index.php?components=report&action=sub&report_type=transaudit">Transfer Audit</a>
                <a style="font-size:11pt" href="index.php?components=report&action=sub&report_type=crlimitaudit">Credit Limit Audit</a>
                <a style="font-size:11pt" href="index.php?components=report&action=sub&report_type=editqtyaudit">Edit Qty Audit</a>
                <a style="font-size:11pt" href="index.php?components=report&action=sub&report_type=loginaudit">Login Audit</a>
                <a style="font-size:11pt" href="index.php?components=report&action=sub&report_type=billeditaudit">Bill Edit Audit</a>
                <a style="font-size:11pt" href="index.php?components=report&action=sub&report_type=payeditaudit">Pay Edit Audit</a>
              </div>
            </div>
            <a href="index.php?components=report&action=authorize_code" <?php print $slect4; ?> >Auth Code</a>
            <a href="index.php?components=report&action=approval" <?php print $localstyle8;  print $slect14; ?>>Approval</a>
          <?php }

          // ------------------------ SETTINGS ------------------------ //
          if ($_REQUEST['components'] == 'settings') { ?>
            <button onmouseover="billingMenu2()" <?php print $slect1; ?> onclick="window.location = 'index.php?components=settings&action=manage_user'">Manage Users</button>
            <a href="index.php?components=settings&action=manage_category" <?php print $slect2; ?>>Manage Category</a>
            <a href="index.php?components=settings&action=system_settings" <?php print $slect3; ?>>System Settings</a>
            <a href="index.php?components=settings&action=devices" <?php print $slect4; ?>>Devices</a>
            <a href="index.php?components=settings&action=group_allocation" <?php print $slect5; ?>>Group Allocation</a>
            <?php if ($systemid == 2) { ?><a href="index.php?components=settings&action=bill_edit" <?php print $slect6; ?>>Bill Edit</a> <?php } ?>
          <?php }

          // ------------------------ PORTALSUP ------------------------ //
          if ($_REQUEST['components'] == 'portalsup') { ?>
            <button onmouseover="billingMenu2()" <?php print $slect1; ?> onclick="window.location = 'index.php?components=portalsup&action=dashboard'">Dashboard</button>
            <a href="index.php?components=portalsup&action=sales_report" <?php print $slect2; ?>>Sales Report</a>
            <a href="index.php?components=portalsup&action=monthly_sales" <?php print $slect3; ?>>Monthly Sales</a>
            <a href="index.php?components=portalsup&action=monthly_return" <?php print $slect4; ?>>Monthly Return</a>
          <?php }

          // ------------------------ TO ------------------------ //
          if($_REQUEST['components'] == 'to'){ ?>
            <div class="dropdown">
                <button onmouseover="billingMenu2()" onmouseleave="billingMenu2()" onclick="window.location = 'index.php?components=to&action=quotation'" <?php print $slect1; ?>>Quotation</button>
                <div id="myDropdown" class="dropdown-content">
                  <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=to&action=quotation">New Quotation</a>
                  <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=to&action=quotation_ongoing">On-Going Quotation</a>
                  <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt" href="index.php?components=to&action=quotation_list&cust=&item=&st=&sm=&status=all">Quotation List</a>
                </div>
              </div>
        <?php } ?>
        </td>
      </tr>
    </table>
  </div>
  <hr />