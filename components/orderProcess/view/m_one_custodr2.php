<?php

include_once 'template/m_header.php';
generateInvoiceOdr('itq.drawer_no, bi.id');
$decimal = getDecimalPlaces(1);
$currency = getCurrency(1);
$systemid = inf_systemid(1);
$bill_module = bill_module(1);
$fqdn = $_SERVER['SERVER_NAME'];
$sub_system = $_COOKIE['sub_system'];
if ($fqdn == $inf_url_primary) {
  $url = $inf_url_primary;
} else {
  $url = $inf_url_backup;
}
?>

<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<!-- Make sure you have jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add jQuery UI for the autocomplete feature -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script type="text/javascript">
  function sendToQuickPrinterChrome($case) {
    var commandsToPrint = document.getElementById($case).innerHTML;
    var textEncoded = encodeURI(commandsToPrint);
    window.location.href = "intent://" + textEncoded + "#Intent;scheme=quickprinter;package=pe.diegoveloper.printerserverapp;end;";
  }
</script>

<style type="text/css">
  .style2 {
    color: navy;
    font-weight: bold;
    background-color: #EEEEEE;
  }

  /* --- Global Box-Sizing --- */
  * {
    box-sizing: border-box;
  }

  /* --- Main Layout Containers --- */
  .page-wrapper {
    width: 100%;
  }

  .main-container {
    width: 95%;
    max-width: 1400px;
    margin: 0 auto;
  }

  /* --- General Table Styling --- */
  .order-content table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  .order-content th,
  .order-content td {
    padding: 6px 10px;
    text-align: left;
    border: 1px solid #ddd;
    vertical-align: middle;
    font-size: 14px;
  }

  .order-content th {
    background-color: #f2f2f2;
  }

  .order-content table table th {
    background-color: #c0c0c0;
  }

  /* --- Master Cross-Check UI Box --- */
  .cross-check-ui-box {
    max-width: 1400px;
    margin: 20px auto;
    padding: 15px;
    border: 1px solid #c0c0c0;
    background-color: #f8f8f8;
    border-radius: 4px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    justify-content: center;
  }

  .cross-check-ui-box h4 {
    width: 100%;
    margin: 0 0 10px 0;
    text-align: center;
  }

  .cross-check-ui-box .input-group {
    display: flex;
    flex: 7;
    gap: 10px;
  }

  .cross-check-ui-box input[type='text'],
  .cross-check-ui-box input[type='number'] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .cross-check-ui-box #masterItemInput {
    flex: 3;
    min-width: 250px;
  }

  .cross-check-ui-box #masterBilledQty {
    flex: 1;
    min-width: 100px;
    text-align: center;
    font-weight: bold;
    background-color: #e9ecef;
  }

  .cross-check-ui-box #masterQtyInput {
    flex: 1;
    min-width: 100px;
  }

  .cross-check-ui-box button {
    flex: 1;
    padding: 8px 12px;
    border: none;
    cursor: pointer;
    font-weight: bold;
    border-radius: 4px;
    transition: background-color 0.2s;
  }

  .cross-check-ui-box #masterVerifyBtn {
    background-color: #467898;
    color: white;
  }

  .cross-check-ui-box #masterAddNewItemBtn {
    background-color: black;
    color: white;
  }

  .cross-check-ui-box #masterUpdateQtyBtn {
    background-color: #28a745;
    color: white;
  }

  .cross-check-ui-box button:disabled {
    background-color: #aaa;
    cursor: not-allowed;
  }

  #masterFeedback {
    width: 100%;
    margin-top: 10px;
    font-weight: bold;
    min-height: 20px;
    text-align: center;
  }

  /* --- Styles for Interactive Item Rows --- */
  .cross-check-item-row .billed-qty {
    background-color: #eee;
    font-weight: bold;
    border: 1px solid gray;
  }

  .cross-check-item-row input[type='number'] {
    width: 100%;
    padding: 6px;
    text-align: right;
  }

  .cross-check-item-row .actions-cell {
    text-align: right;
    white-space: nowrap;
  }

  .cross-check-item-row .status-icon {
    display: inline-block;
    width: 24px;
    height: 24px;
    font-size: 20px;
    text-align: center;
    line-height: 24px;
    font-weight: bold;
    vertical-align: middle;
  }

  /* Row Status Styles */
  .cross-check-item-row.verified {
    background-color: #d4edda !important;
  }

  .cross-check-item-row.mismatch {
    background-color: #fff3cd !important;
  }

  .cross-check-item-row.verified .status-icon::after {
    color: #155724;
    content: '\2714';
  }

  .cross-check-item-row.mismatch .status-icon::after {
    color: #856404;
    content: '\0021';
  }

  /* --- Autocomplete --- */
  .ui-autocomplete {
    z-index: 1000;
  }

  .ui-autocomplete .ui-menu-item {
    font-size: 14px;
    padding: 6px 10px;
  }

  /* --- Return Pending Items Cross-Check UI Box --- */
  .return-pending-cross-check-ui-box {
    max-width: 1400px;
    margin: 20px auto;
    padding: 15px;
    border: 1px solid #c0c0c0;
    background-color: #f8f8f8;
    border-radius: 4px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    justify-content: center;
  }

  .return-pending-cross-check-ui-box h4 {
    width: 100%;
    margin: 0 0 10px 0;
    text-align: center;
  }

  .return-pending-cross-check-ui-box .input-group {
    display: flex;
    flex: 7;
    gap: 10px;
  }

  .return-pending-cross-check-ui-box input[type='text'],
  .return-pending-cross-check-ui-box input[type='number'] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .return-pending-cross-check-ui-box #returnPendingItemInput {
    flex: 3;
    min-width: 250px;
  }

  .return-pending-cross-check-ui-box #returnPendingItemTotalQty {
    flex: 1;
    min-width: 100px;
    text-align: center;
    font-weight: bold;
    background-color: #e9ecef;
  }

  .return-pending-cross-check-ui-box #returnPendingPackQty {
    flex: 1;
    min-width: 100px;
  }

  .return-pending-cross-check-ui-box button {
    flex: 1;
    padding: 8px 12px;
    border: none;
    cursor: pointer;
    font-weight: bold;
    border-radius: 4px;
    transition: background-color 0.2s;
  }

  .return-pending-cross-check-ui-box #packReturnPendingItemBtn {
    background-color: #467898;
    color: white;
  }

  .return-pending-cross-check-ui-box #unpackReturnPendingItemBtn {
    background-color: #28a745;
    color: white;
  }

  .return-pending-cross-check-ui-box button:disabled {
    background-color: #aaa;
    cursor: not-allowed;
  }

  #returnPendingItemFeedback {
    width: 100%;
    margin-top: 10px;
    font-weight: bold;
    min-height: 20px;
    text-align: center;
  }

  /* Return item row styling */
  .return-item-row.verified {
    background-color: #d4edda !important;
    /* Green for packed items */
  }

  .return-item-row.verified td {
    border-color: #c3e6cb !important;
  }

  /* Button states for return items */
  .return-item-row.verified input[value="Pack"] {
    background-color: #28a745 !important;
    color: white !important;
    cursor: not-allowed;
  }

  .return-item-row.verified input[value="Remove"] {
    background-color: #dc3545 !important;
    color: white !important;
  }

  /* Status indicators for consistency */
  .return-item-row .status-indicator {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin-right: 8px;
  }

  .return-item-row.verified .status-indicator {
    background-color: #28a745;
  }

  .return-item-row:not(.verified) .status-indicator {
    background-color: #ffc107;
  }
</style>
</head>

<div class="w3-container" style="margin-top:75px">
  <?php
  if (isset($_REQUEST['id']))
    $id = $_REQUEST['id'];
  else
    $id = 0;
  if (isset($_REQUEST['message'])) {
    if ($_REQUEST['re'] == 'success')
      $color = 'green';
    else
      $color = 'red';
    print '<span style="color:' . $color . '; font-weight:bold;font-size:large;">' . $_REQUEST['message'] . '</span>';
  }
  if ($button != 'Pick') {
    include_once 'components/orderProcess/view/tpl/pos_odr.php';
  }
  include_once 'components/orderProcess/view/tpl/pos_address.php';
  ?>
  <hr>
  <div class="w3-row">
    <div class="w3-col s3"></div>
    <div class="w3-col">
      <div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

      <table align="center" style="font-size:12pt" width="100%">
        <tr>
          <td style="background-color:#467898;color :white;">
            <strong><?php if ($bm_status < 3)
              print 'Order No';
            else
              print 'Invoice No'; ?></strong>
          </td>
          <td bgcolor="#EEEEEE"><?php print str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?></td>
          <td></td>
          <td style="background-color:#467898;color :white;"><strong>Salesman</strong></td>
          <td bgcolor="#EEEEEE"><?php print ucfirst($bi_salesman); ?></td>
        </tr>
        <tr>
          <td style="background-color:#467898;color :white;"><strong>Order Date</strong></td>
          <td bgcolor="#EEEEEE"><?php print $odr_date; ?></td>
          <td></td>
          <td style="background-color:#467898;color :white;"><strong>Customer</strong></td>
          <td bgcolor="#EEEEEE">
            <?php print '<a href="index.php?components=' . $bill_module . '&action=cust_details&id=' . $cu_id . '&action2=list_one_custodr&id2=' . $_REQUEST['id'] . '" >' . ucfirst($bi_cust) . '</a>'; ?>
          </td>
        </tr>
        <tr>
          <td colspan="5" height="50px">
            <table align="center">
              <tr>
                <td>
                  <?php if ($button == 'Move to Cross Check' && $bi_seen_by == $_COOKIE['user']) {
                    if ($button == 'Move to Cross Check' && $systemid == 13 && $sub_system == 1)
                      $button1 = 'CROSS CHECK';
                    else
                      $button1 = 'CROSS CHECK';
                    ?>
                    <div id="orderprocess">
                      <input type="button" value="<?php print $button1; ?>"
                        style="height:50px; width:150px; background-color:#CC5100; font-weight:bold; color:white"
                        onclick="orderProcess('<?php print $button1; ?>')" />
                    </div>
                  <?php } ?>
                  <?php if ($button == 'Packed' && $bi_cross_checked_by == $_COOKIE['user']) {
                    if ($button == 'Packed' && $systemid == 13 && $sub_system == 1)
                      $button1 = 'Started';
                    else
                      $button1 = 'Packed';
                    ?>
                    <div id="orderprocess">
                      <input type="button" value="<?php print $button1; ?>"
                        style="height:50px; width:150px; background-color:#CC5100; font-weight:bold; color:white"
                        onclick="orderProcess('<?php print $button1; ?>')" />
                    </div>
                  <?php } ?>
                </td>
                <td width="20px"></td>
                <td>
                  <?php if ($button != 'Pick') { ?>
                    <input type="button" value="Print"
                      style="height:50px; width:150px; background-color:#007799; font-weight:bold; color:white"
                      onclick="sendToQuickPrinterChrome('print_odr')" />
                  <?php } ?>
                </td>
                <td width="20px"></td>
                <td>
                  <?php
                  if ((isset($_COOKIE['report']) || isset($_COOKIE['manager'])) && $button == 'Cross Check') {
                    print '<div id="orderprocess3"><input type="button" value="Unassign"
												style="height:50px; width:150px; background-color:orange; font-weight:bold; color:white"
												onclick="orderUnassign(' . $bm_type . ')" /></div>';
                  }
                  print '&nbsp;<input type="button" value="Print Tag" style="height:50px; width:90px; background-color:#007777; font-weight:bold; color:white" onclick="sendToQuickPrinterChrome(\'print_address\')" />';
                  ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <!-- Master Cross-Check UI Box -->
      <?php if ($bm_status == 11): ?>
        <div class="cross-check-ui-box">
          <h4>Billed Item Cross Check</h4>
          <div class="input-group">
            <input type="text" id="masterItemInput" placeholder="Scan or Enter Item Code/Description..." autofocus />
            <input type="text" id="masterBilledQty" placeholder="Billed Qty" readonly />
            <input type="number" id="masterQtyInput" placeholder="Enter Qty" min="0" step="any" />
          </div>
          <button type="button" id="masterVerifyBtn">Verify</button>
          <button type="button" id="masterUpdateQtyBtn" disabled>
            Update Qty
          </button>
          <button type="button" id="masterAddNewItemBtn" class="add-new-item-btn"
            data-href="<?php echo 'index.php?components=order_process&action=setdistrict_custodr&bill_no=' . $_GET['id'] . '&id=' . $bm_district . '&return=0'; ?>">
            Add New Item
          </button>
          <div id="masterFeedback"></div>
        </div>
      <?php endif; ?>

      <!-- Main Billed Items Table -->
      <table id="billed-items-table" width="100%" style="font-size:12pt">
        <thead style="background-color:#C0C0C0">
          <tr>
            <th style="width: 5%; text-align:center;">#</th>
            <th>Item Description</th>
            <th style="width: 10%;">Drawer No</th>
            <th style="width: 10%;">Billed Qty</th>
            <?php if ($bm_status == 11): ?>
              <th style="width: 10%;">Checked Qty</th>
              <th style="width: 15%; text-align:right;">Actions</th>
            <?php else: ?>
              <th style="width: 5%; text-align:right;"></th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php for ($i = 0; $i < sizeof($odr_bill_id); $i++) {
            if ($odr_bi_order[$i] == 0) {
              $row_class = $bm_status == 11 ? 'cross-check-item-row' : '';
              print '<tr class="' .
                $row_class .
                '"
										style="background-color:#F0F0F0"
										data-item-id="' .
                $odr_bill_id[$i] .
                '"
										data-item-desc="' .
                htmlspecialchars($odr_bi_desc[$i]) .
                '"
										data-item-qty="' .
                $odr_bi_qty[$i] .
                '"
										data-item-code="' .
                htmlspecialchars(trim($odr_bi_code[$i])) .
                '">
										<td style="text-align:center;">' .
                sprintf('%02d', ($i + 1)) .
                '</td>
										<td class="item-desc">' .
                $odr_bi_desc[$i] .
                '</td>
										<td align="right">' .
                $odr_bi_drawer[$i] .
                '</td>
										<td><input type="number" class="billed-qty" value="' .
                $odr_bi_qty[$i] .
                '" readonly/></td>';

              if ($bm_status == 11) {
                // --- NEW: Check if the quantity is greater than zero ---
                if ($odr_bi_qty[$i] > 0) {
                  // If quantity is positive, show the input box and buttons
                  print '<td><input type="number" class="checked-qty" placeholder="Enter Qty" step="any" onchange="verifyRowItem(' .
                    $odr_bill_id[$i] .
                    ')" /></td>
                    <td class="actions-cell">
                          <span class="status-icon"></span>
                          <button type="button" class="verify-btn" onclick="verifyRowItem(' .
                    $odr_bill_id[$i] .
                    ')">Verify</button>
                          <button type="button" class="remove-btn" onclick="removeItemODR(' .
                    $odr_bill_id[$i] .
                    ')" style="background-color:maroon; color:white">Remove</button>
                    </td>';
                } else {
                  print '<td></td>
                  <td class="actions-cell">
                      <button type="button" class="remove-btn"
                          onclick="removeItemODR(' . $odr_bill_id[$i] . ')" style="background-color:maroon; color:white">Remove</button>
                  </td>';
                }
              } else {
                print '<td align="right"><div id="button_' .
                  $odr_bill_id[$i] .
                  '"><input type="button" value="Remove" onmouseup="removeItemODR(' .
                  $odr_bill_id[$i] .
                  ')" style="background-color:maroon; color:white" /></div></td>';
              }
              print '</tr>';
            }
          } ?>
          <tr style="background-color:#F0F0F0">
            <td colspan="<?php echo $bm_status == 11 ? 5 : 3; ?>"></td>
            <td style="text-align:right;">
              <input type="button" value="Add New Item" class="add-new-item-btn"
                data-href="<?php echo 'index.php?components=order_process&action=setdistrict_custodr&bill_no=' . $_GET['id'] . '&id=' . $bm_district . '&return=0'; ?>" />
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Return Item Handling -->
      <?php if ($systemid == 1 || $systemid == 10 || $systemid == 16 || $systemid == 17) { ?>
        <hr />
        <table align="center" width="100%" style="font-size:12pt">
          <tr style="background-color:#467898; color:black;">
            <th height="20px">RETURN ITEM HANDLING</th>
          </tr>
        </table>
        <?php if ($bm_status == 11): ?>
          <!-- Return Pending Items Cross-Check UI Box -->
          <div class="return-pending-cross-check-ui-box">
            <h4>Return Pending Items Cross Check</h4>
            <div class="input-group">
              <input type="text" id="returnPendingItemInput" placeholder="Scan or Enter Item Code/Description..."
                autofocus />
              <input type="text" id="returnPendingItemTotalQty" placeholder="Total Qty" readonly />
              <input type="number" id="returnPendingPackQty" placeholder="Pack Qty" min="0" step="any" />
            </div>
            <button type="button" id="packReturnPendingItemBtn">Pack</button>
            <div id="returnPendingItemFeedback"></div>
          </div>
        <?php endif; ?>
        <table id="pending-returns-table" align="center" width="100%" style="font-size:12pt">
          <thead>
            <tr>
              <th style="background-color:#467898;color :white;" colspan="10">
                Return Items | Pending
              </th>
            </tr>
            <tr style="background-color:#C0C0C0">
              <th width="4%" align="center">#</th>
              <th width="2%" align="center"></th>
              <th width="8%">RT Invoice</th>
              <th width="10%">Return Date</th>
              <th width="45%">Item</th>
              <th width="8%">Return Qty</th>
              <th width="12%">Salesman</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody id="pending-returns-tbody">
            <?php for ($i = 0; $i < sizeof($rtn_id); $i++) {
              print '<tr class="return-item-row" style="background-color:#F0F0F0"
                data-rtn-id="' . $rtn_id[$i] . '"
                data-item-id="rtn-' . $rtn_id[$i] . '"
                data-item-code="' . htmlspecialchars(trim($rtn_itm_code[$i])) . '"
											data-item-desc="' . htmlspecialchars($rtn_itm_desc[$i]) . '"
											data-item-qty="' . $rtn_qty[$i] . '"
											data-item-type="return">
                      <td align="center">' . sprintf('%02d', ($i + 1)) . '</td>
										<td><div id="return1_' .
                $rtn_id[$i] .
                '"><input type="checkbox" /></div></td>
										<td align="center"><div id="return2_' .
                $rtn_id[$i] .
                '"><a href="index.php?components=' .
                $bill_module .
                '&action=finish_return&id=' .
                $rtn_inv[$i] .
                '">' .
                str_pad($rtn_inv[$i], 7, '0', STR_PAD_LEFT) .
                '</a></div></td>
										<td align="center"><div id="return3_' .
                $rtn_id[$i] .
                '">' .
                $rtn_date[$i] .
                '</div></td>
										<td>
                      <div id="return4_' . $rtn_id[$i] . '">
                      <a style="text-decoration:none" href="index.php?components=order_process&action=show_one_return_item&odr_id=' . $_GET['id'] .
                '&rtn_id=' .
                $rtn_id[$i] .
                '">' .
                htmlspecialchars($rtn_itm_desc[$i]) .
                '</a>
                  </div>
                  </td>
										<td align="right"><div id="return5_' .
                $rtn_id[$i] .
                '">' .
                $rtn_qty[$i] .
                '</div></td>
										<td align="right"><div id="return6_' .
                $rtn_id[$i] .
                '">' .
                ucfirst($rtn_by[$i]) .
                '</div></td>
										<td align="center"><div id="return7_' .
                $rtn_id[$i] .
                '"><input type="button" value="Pack" onmouseup="returnPacked(' .
                $rtn_id[$i] .
                ')" style="background-color:green; color:white; width:100%" /></div></td>
									</tr>';
            } ?>
          </tbody>
        </table>
        <table id="packed-returns-table" align="center" width="100%" style="font-size:12pt">
          <thead>
            <tr>
              <th style="background-color:#787898;color :white;" colspan="10">
                Return Items | Packed
              </th>
            </tr>
            <tr style="background-color:#C0C0C0">
              <th width="4%">#</th>
              <th width="2%"></th>
              <th width="8%">RT Invoice</th>
              <th width="10%">Return Date</th>
              <th width="45%">Item</th>
              <th width="8%">Return Qty</th>
              <th width="12%">Salesman</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody id="packed-returns-tbody">
            <?php for ($i = 0; $i < sizeof($rtn2_id); $i++) {
              print '<tr class="return-item-row" style="background-color:#F0F0F0"
                    data-rtn-id="' . $rtn2_id[$i] . '"
                    data-item-id="rtn-' . $rtn2_id[$i] . '"
                    data-item-code="' . htmlspecialchars(trim($rtn2_itm_code[$i])) . '"
                    data-item-qty="' . $rtn2_qty[$i] . '">
                    <td align="center">' . sprintf('%02d', ($i + 1)) . '</td>
										<td><div id="return1_done_' .
                $rtn2_id[$i] .
                '"><input type="checkbox" /></div></td>
										<td align="center"><div id="return2_done_' .
                $rtn2_id[$i] .
                '"><a href="index.php?components=' .
                $bill_module .
                '&action=finish_return&id=' .
                $rtn2_inv[$i] .
                '">' .
                str_pad($rtn2_inv[$i], 7, '0', STR_PAD_LEFT) .
                '</a></div></td>
										<td align="center"><div id="return3_done_' .
                $rtn2_id[$i] .
                '">' .
                $rtn2_date[$i] .
                '</div></td>
										<td><div id="return4_done_' .
                $rtn2_id[$i] .
                '">' .
                $rtn2_itm_desc[$i] .
                '</div></td>
										<td align="right"><div id="return5_done_' .
                $rtn2_id[$i] .
                '">' .
                $rtn2_qty[$i] .
                '</div></td>
										<td align="right"><div id="return6_done_' .
                $rtn2_id[$i] .
                '">' .
                ucfirst($rtn2_by[$i]) .
                '</div></td>
										<td align="center"><div id="return7_done_' .
                $rtn2_id[$i] .
                '"><input type="button" value="Remove" onmouseup="removeReturnPacked(' .
                $rtn2_id[$i] .
                ')" style="background-color:maroon; color:white; width:100%" /></div></td>
									</tr>';
            } ?>
          </tbody>
        </table>
      <?php } ?>

      <!-- Stages details -->
      <table width="100%" align="center" width="100%" style="font-size:12pt; margin-top: 30px;">
        <thead>
          <tr style="background-color:#C0C0C0">
            <th>Picked By</th>
            <th>Cross Checked By</th>
            <th>
              <?php echo $systemid == 13 && $sub_system == 1
                ? 'Started'
                : 'Packed'; ?>
              By
            </th>
            <?php if ($systemid != 13 || $sub_system != 1) { ?>
              <th>Shipped By</th>
            <?php } ?>
            <th>
              <?php echo $systemid == 13 && $sub_system == 1
                ? 'Finished'
                : 'Delivered'; ?>
              By
            </th>
          </tr>
        </thead>
        <tbody>
          <tr style="background-color:#F0F0F0">
            <td><?php print ucfirst($bi_seen_by); ?></td>
            <td><?php print ucfirst($bi_cross_checked_by); ?></td>
            <td><?php print ucfirst($bi_packed_by); ?></td>
            <?php if ($systemid != 13 || $sub_system != 1) { ?>
              <td><?php print ucfirst($bi_shipped_by); ?></td>
            <?php } ?>
            <td><?php print ucfirst($bi_deliverd_by); ?></td>
          </tr>
          <tr style="background-color:#F0F0F0">
            <td><?php print $bi_seen_date . '<br/>' . $bi_seen_time; ?></td>
            <td>
              <?php print $bi_cross_checked_date .
                '<br/>' .
                $bi_cross_checked_time; ?>
            </td>
            <td><?php print $bi_packed_date . '<br/>' . $bi_packed_time; ?></td>
            <?php if ($systemid != 13 || $sub_system != 1) { ?>
              <td>
                <?php print $bi_shipped_date . '<br/>' . $bi_shipped_time; ?>
              </td>
            <?php } ?>
            <td>
              <?php print $bi_deliverd_date . '<br/>' . $bi_deliverd_time; ?>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<hr>

<script type="text/javascript">
  const orderId_check = <?php echo json_encode($_REQUEST['id']); ?>;
  const orderId = <?php echo json_encode($_REQUEST['id']); ?>;
  const storageKey = `crossCheckState_${orderId_check}`;
  let itemDataMap = {};
  let currentSelectedItemCode = null; // This will store the *canonicalized* code

  // --- Auto-run function to clear localStorage after a successful submission ---
  (function handleSuccessfulOrderCleanup() {
    // Step 1: Check for the "promise" (the flag set by the previous page).
    const orderIdToClear = localStorage.getItem('orderToClearOnSuccess');
    if (!orderIdToClear) {
      return; // No promise was made, so do nothing.
    }

    // Step 2: Check for the "confirmation" (the URL parameters).
    const urlParams = new URLSearchParams(window.location.search);
    const isSuccess = urlParams.get('re') === 'success';
    const successId = urlParams.get('id');
    const hasLinkYes = urlParams.get('link') === 'yes';

    // THE FINAL, MOST SECURE CHECK:
    // All three conditions must be met.
    if (isSuccess && hasLinkYes && successId === orderIdToClear) {
      // Both promise and confirmation match. It's safe to delete.
      const keyToDelete = `crossCheckState_${orderIdToClear}`;
      localStorage.removeItem(keyToDelete);

      // Clean up the promise flag so this doesn't run again on a refresh.
      localStorage.removeItem('orderToClearOnSuccess');
    }
    // Also clear the flag on a specific failure to prevent it from sticking around.
    else if (urlParams.get('re') === 'fail' && successId === orderIdToClear) {
      localStorage.removeItem('orderToClearOnSuccess');
    }
  })(); // The final () still executes it immediately

  // --- Auto-run function to clear old localStorage data ---
  (function clearOldLocalStorageData() {
    const NINETY_DAYS_IN_MS = 90 * 24 * 60 * 60 * 1000;
    const cutoffTimestamp = new Date().getTime() - NINETY_DAYS_IN_MS;
    const keyPrefix = 'crossCheckState_';
    const keysToRemove = [];

    for (let i = 0; i < localStorage.length; i++) {
      const key = localStorage.key(i);
      if (key && key.startsWith(keyPrefix)) {
        try {
          const value = localStorage.getItem(key);
          const data = JSON.parse(value);
          if (data && data.lastUpdated && data.lastUpdated < cutoffTimestamp) {
            keysToRemove.push(key);
          }
        } catch (e) {
          console.error(`Could not parse localStorage item with key: ${key}. Flagging for removal.`, e);
          keysToRemove.push(key);
        }
      }
    }

    if (keysToRemove.length > 0) {
      keysToRemove.forEach(key => {
        localStorage.removeItem(key);
      });
    }
  })();

  function updateRowNumbers() {
    $('#pending-returns-tbody tr').each(function (index) {
      const rowNumber = String(index + 1).padStart(2, '0'); // Format with leading zero
      $(this).find('td:first').text(rowNumber);
    });
    $('#packed-returns-tbody tr').each(function (index) {
      const rowNumber = String(index + 1).padStart(2, '0'); // Format with leading zero
      $(this).find('td:first').text(rowNumber);
    });
  }

  // --- GLOBAL HELPER FUNCTION FOR STRING CANONICALIZATION ---
  // This function cleans up strings for consistent comparison:
  // - Collapses multiple spaces into a single space
  // - Trims leading/trailing spaces
  // - Converts to lowercase
  function canonicalizeString(str) {
    if (typeof str !== 'string') return '';
    return str.replace(/\s+/g, ' ').trim().toLowerCase();
  }

  // --- Standard Functions ---
  function orderUnassign($type) {
    if (confirm('Do you want to unassign this order?')) {
      document.getElementById('orderprocess3').innerHTML =
        document.getElementById('loading').innerHTML;
      const action2 = $type == 4 || $type == 5 ? 'list_custodr' : 'list_pending';
      window.location = `index.php?components=order_process&action=set_unassign&next_action=${action2}&id=<?php print $_GET[
        'id'
      ]; ?>`;
    }
  }

  function orderProcess(button) {
    if (confirm(`Are you sure you want to ${button} this order?`)) {
      document.getElementById('orderprocess').innerHTML =
        document.getElementById('loading').innerHTML;

      if ((button === 'PACKED' || button === 'Started') && <?php echo $bm_status == 11 ? 'true' : 'false'; ?>) {
        // --- NEW: Check if there are any items that actually need verification ---
        let hasPositiveQtyItems = false;
        $('#billed-items-table .cross-check-item-row').each(function () {
          const billedQty = parseFloat($(this).data('item-qty'));
          if (billedQty > 0) {
            hasPositiveQtyItems = true;
            return false; // Found one, no need to check further. Exit the loop.
          }
        });
        // --- END NEW SECTION ---

        const savedState = JSON.parse(localStorage.getItem(storageKey)) || {};
        const aggregatedChecklist = {};

        // 1. Process VERIFIED Billed Items
        $('#billed-items-table .cross-check-item-row').each(function () {
          const row = $(this);
          const billId = row.data('item-id');
          const originalCode = (row.data('item-code') || '').toString();
          const itemDesc = row.data('item-desc').toString();
          if (savedState[billId] && row.hasClass('verified')) {
            const canonicalCode = canonicalizeString(originalCode);
            if (!canonicalCode) return;
            const checkedQty = parseFloat(savedState[billId].checkedQty);
            if (isNaN(checkedQty)) return;
            if (!aggregatedChecklist[canonicalCode]) {
              aggregatedChecklist[canonicalCode] = { verifiedQty: 0, desc: itemDesc };
            }
            aggregatedChecklist[canonicalCode].verifiedQty += checkedQty;
          }
        });

        // 2. Process ALL Packed Return Items
        $('#packed-returns-tbody tr[data-item-id]').each(function () {
          const row = $(this);
          const originalCode = (row.data('item-code') || '').toString();
          const itemDesc = (row.data('item-desc') || '').toString();
          const itemQty = parseFloat(row.data('item-qty'));
          const canonicalCode = canonicalizeString(originalCode);
          if (!canonicalCode || isNaN(itemQty) || itemQty <= 0) return;
          if (!aggregatedChecklist[canonicalCode]) {
            aggregatedChecklist[canonicalCode] = { verifiedQty: 0, desc: itemDesc };
          }
          aggregatedChecklist[canonicalCode].verifiedQty += itemQty;
        });

        // --- MODIFIED: The smarter validation check ---
        if (Object.keys(aggregatedChecklist).length === 0 && hasPositiveQtyItems) {
          alert(
            'Verification Error: No items have been correctly verified. Please check the quantities before packing.',
          );
          document.getElementById(
            'orderprocess',
          ).innerHTML = `<input type="button" value="${button}" style="height:50px; width:150px; background-color:#CC5100; font-weight:bold; color:white" onclick="orderProcess('${button}')" />`;
          return; // Stop execution
        }

        // 4. Create and submit the form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action =
          'index.php?components=order_process&action=<?php print $button_action; ?>&id=<?php print $_REQUEST['id']; ?>';
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'checklistData';
        hiddenInput.value = JSON.stringify({
          checkedItems: aggregatedChecklist,
        });
        form.appendChild(hiddenInput);
        document.body.appendChild(form);
        localStorage.setItem('orderToClearOnSuccess', orderId);
        form.submit();
      } else {
        window.location =
          'index.php?components=order_process&action=<?php print $button_action; ?>&id=<?php print $_REQUEST['id']; ?>';
      }
    }
  }

  function removeItemODR(id) {
    if (confirm('Are you sure you want to perform this action?')) {
      const row = $(`tr[data-item-id="${id}"]`);
      const actionCell = row.find('.actions-cell');
      const originalContent = actionCell.html();
      actionCell.html(`<img src="images/loading.gif" style="width:20px" />`);

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
          if (this.responseText.toLowerCase().includes('success')) {
            row.fadeOut(300, function () {
              $(this).remove();
              saveState(id, ''); // Remove from localStorage
              buildItemDataMap();
              clearMasterInputs();
            });
          } else {
            alert('Error removing item: ' + this.responseText);
            actionCell.html(originalContent);
          }
        }
      };
      xhttp.open(
        'GET',
        `index.php?components=order_process&action=bill_item_remove&s=&cust=&id=${id}`,
        true,
      );
      xhttp.send();
    }
  }

  // --- Cross-Check specific logic ---
  <?php if ($bm_status == 11): ?>
    function saveState(itemId, checkedQty) {
      const state = JSON.parse(localStorage.getItem(storageKey)) || {};
      if (checkedQty === '' || isNaN(parseFloat(checkedQty))) {
        delete state[itemId];
      } else {
        state[itemId] = { checkedQty: checkedQty };
      }
      // Update the global lastUpdated timestamp
      state.lastUpdated = new Date().getTime();
      localStorage.setItem(storageKey, JSON.stringify(state));
    }

    function loadState() {
      const state = JSON.parse(localStorage.getItem(storageKey)) || {};
      // Exclude lastUpdated when processing items
      const { lastUpdated, ...itemsState } = state;

      $('.cross-check-item-row').each(function () {
        const row = $(this);
        const itemId = row.data('item-id');

        if (itemsState[itemId] && itemsState[itemId].checkedQty) {
          row.find('.checked-qty').val(itemsState[itemId].checkedQty);
          verifyRowItem(itemId);
        }
      });
    }

    function buildItemDataMap() {
      itemDataMap = {};
      $('.cross-check-item-row').each(function () {
        const row = $(this);
        const originalCode = row.data('item-code').toString();
        const originalDesc = row.data('item-desc').toString(); // Store original desc
        const canonicalCode = canonicalizeString(originalCode); // Canonicalize for map key
        const billedQty = parseFloat(row.data('item-qty'));
        const itemId = parseInt(row.data('item-id'));

        if (!originalCode || billedQty <= 0) {
          console.warn(`Billed Item: Skipping row ${itemId} due to empty code or non-positive quantity. Original Code: "${originalCode}" Qty: ${billedQty}`);
          return;
        }

        if (!itemDataMap[canonicalCode]) {
          itemDataMap[canonicalCode] = {
            desc: originalDesc, // Use original description for display
            originalCode: originalCode, // Store original code
            totalQty: 0,
            rowRefs: [],
          };
        }
        itemDataMap[canonicalCode].totalQty += billedQty;
        itemDataMap[canonicalCode].rowRefs.push({ id: itemId, qty: billedQty, originalCode: originalCode });
      });

      const source = Object.keys(itemDataMap).map((canonicalCode) => ({
        label: `${itemDataMap[canonicalCode].desc} (${itemDataMap[canonicalCode].originalCode})`, // Display original code for context
        value: canonicalCode, // Autocomplete value should be canonical code
        desc: itemDataMap[canonicalCode].desc, // Original description
        originalCode: itemDataMap[canonicalCode].originalCode // Original code
      }));
      $('#masterItemInput').autocomplete('option', 'source', source);
      console.log('Billed Items Map built:', itemDataMap); // Debug log
    }

    function verifyRowItem(itemId) {
      const row = $(`tr[data-item-id="${itemId}"]`);
      if (!row.length) return;

      const billedQty = parseFloat(row.find('.billed-qty').val());
      const checkedQtyInput = row.find('.checked-qty');
      const checkedQty = parseFloat(checkedQtyInput.val());

      // First, reset the visual state
      row.removeClass('verified mismatch');
      row.find('.verify-btn').text('Verify').prop('disabled', false);

      if (isNaN(checkedQty)) {
        // If the user clears the input, remove the item's state from storage
        saveState(itemId, '');
        return;
      }

      if (billedQty === checkedQty) {
        // --- SUCCESS ---
        // The quantity is a match. Add the 'verified' class AND save to localStorage.
        row.addClass('verified');
        row.find('.verify-btn').text('Verified').prop('disabled', true);
        saveState(itemId, checkedQtyInput.val()); // SAVE the valid state
      } else {
        // --- MISMATCH ---
        // The quantity is wrong. Add the 'mismatch' class and REMOVE from localStorage.
        row.addClass('mismatch');
        saveState(itemId, ''); // DELETE any invalid or outdated state
      }
    }

    function clearMasterInputs() {
      $('#masterItemInput').val('');
      $('#masterBilledQty').val('');
      $('#masterQtyInput').val('');
      $('#masterUpdateQtyBtn').prop('disabled', true);
      $('#masterFeedback').text('');
      currentSelectedItemCode = null;
    }

    // **NEW**: Function to check for and verify newly added items
    function checkNewItems() {
      const existingIdsJson = sessionStorage.getItem('existingItemIds');
      if (!existingIdsJson) {
        return; // Nothing to do if we didn't just add an item
      }

      const existingIds = JSON.parse(existingIdsJson);
      const existingIdSet = new Set(existingIds.map(Number)); // Use a Set for fast lookups

      $('.cross-check-item-row').each(function () {
        const row = $(this);
        const currentId = row.data('item-id');

        if (!existingIdSet.has(currentId)) {
          // This is a new item, as its ID wasn't here before
          const billedQty = row.find('.billed-qty').val();
          row.find('.checked-qty').val(billedQty);
          verifyRowItem(currentId); // This marks it verified and saves to localStorage
        }
      });

      // Clean up sessionStorage so this doesn't run on a normal refresh
      sessionStorage.removeItem('existingItemIds');
    }

    $(document).ready(function () {
      const masterItemInput = $('#masterItemInput');
      const masterBilledQtyInput = $('#masterBilledQty');
      const masterQtyInput = $('#masterQtyInput');
      const masterVerifyBtn = $('#masterVerifyBtn');
      const masterUpdateBtn = $('#masterUpdateQtyBtn');
      const masterFeedback = $('#masterFeedback');

      $('#masterItemInput').on('focus', function () {
        $(this).val('');
        $("#masterBilledQty").val('');
        $("#masterFeedback").text('');
      });

      masterItemInput.autocomplete({
        source: [],
        minLength: 1,
        select: function (event, ui) {
          event.preventDefault();
          const canonicalCode = ui.item.value; // ui.item.value is already canonicalized
          const itemData = itemDataMap[canonicalCode];

          currentSelectedItemCode = canonicalCode;
          masterItemInput.val(itemData.desc); // Show original description in input
          masterBilledQtyInput.val(itemData.totalQty);
          masterUpdateBtn.prop('disabled', false);
          masterQtyInput.focus().select();
        },
        // IMPORTANT: Customize the display of items in the dropdown
        _renderItem: function (ul, item) {
          return $("<li>")
            .append("<div>" + item.desc + " (" + item.originalCode + ")</div>")
            .appendTo(ul);
        }
      });

      function masterVerify() {
        if (!currentSelectedItemCode) {
          masterFeedback.text('Please select an item first.').css('color', 'orange');
          return;
        }
        const itemData = itemDataMap[currentSelectedItemCode]; // currentSelectedItemCode is canonicalized

        if (!itemData) {
          masterFeedback
            .text('Item not found in the system.')
            .css('color', 'red');
          return;
        }

        const checkedQty = parseFloat(masterQtyInput.val());
        if (isNaN(checkedQty)) {
          masterFeedback.text('Please enter a valid quantity.').css('color', 'red');
          return;
        }

        const itemTotalQty = itemData.totalQty;
        if (itemTotalQty !== checkedQty) {
          masterFeedback
            .text(
              `Quantity Mismatch! System: ${itemTotalQty}, Entered: ${checkedQty}`,
            )
            .css('color', 'red');
          itemData.rowRefs.forEach((rowRef) => {
            const row = $(`tr[data-item-id="${rowRef.id}"]`);
            row.find('.checked-qty').val(0);
            verifyRowItem(rowRef.id);
          });
          return;
        }

        itemData.rowRefs.forEach((rowRef) => {
          const row = $(`tr[data-item-id="${rowRef.id}"]`);
          row.find('.checked-qty').val(rowRef.qty);
          verifyRowItem(rowRef.id);
        });

        masterFeedback.text(`Verified: ${itemData.desc}`).css('color', 'green');
        clearMasterInputs();
        masterItemInput.focus();

        masterUpdateBtn.prop('disabled', false); // Enable update button after verify
      }

      // --- Event Handlers ---
      masterVerifyBtn.on('click', masterVerify);
      masterVerifyBtn.prop('disabled', true); // Disable verify button

      masterUpdateBtn.on('click', function () {
        if (!currentSelectedItemCode) {
          masterFeedback
            .text('Cannot update. No item selected.')
            .css('color', 'red');
          return;
        }
        const itemData = itemDataMap[currentSelectedItemCode]; // currentSelectedItemCode is canonicalized
        const newQty = parseFloat(masterQtyInput.val());

        if (isNaN(newQty)) {
          masterFeedback
            .text('Please enter a valid quantity to update.')
            .css('color', 'red');
          return;
        }

        // Check if this item appears in multiple rows
        if (itemData.rowRefs.length > 1) {
          const targetRowData = itemData.rowRefs[0]; // The row that will be updated
          const targetRowElement = $(`tr[data-item-id="${targetRowData.id}"]`);
          const rowNumber = targetRowElement.find('td:first').text().trim();

          // Calculate the total quantity of all *other* rows for this item
          const remainingQty = itemData.rowRefs
            .slice(1) // Get all elements except the first one
            .reduce((sum, row) => sum + row.qty, 0); // Sum their quantities

          // --- NEW: Calculate the potential new total ---
          const potentialTotalQty = newQty + remainingQty;

          const confirmationMessage =
            `This item appears on multiple rows.\n\n` +
            `You are about to update Row #${rowNumber} for "${itemData.desc}".\n\n` +
            `----------------------------------------\n` +
            `New Quantity for this row: ${newQty}\n` +
            `Quantity on other rows: ${remainingQty}\n` +
            `----------------------------------------\n` +
            `Potential New Total for this item: ${potentialTotalQty}\n\n` + // The new, helpful line
            `Do you want to continue?`;

          // Show the confirmation dialog. If the user clicks "Cancel", stop here.
          if (!confirm(confirmationMessage)) {
            return;
          }
        }

        masterFeedback.html(
          `<img src="images/loading.gif" style="width:20px" /> Updating...`,
        );

        // Update the quantity and mark the item as checked
        itemData.rowRefs.forEach((rowRef) => {
          const row = $(`tr[data-item-id="${rowRef.id}"]`);
          row.find('.checked-qty').val(newQty);
          verifyRowItem(rowRef.id); // Verify the row item
        });

        // Save the updated state to local storage
        saveState(itemData.rowRefs[0].id, newQty);

        window.location = `index.php?components=order_process&action=bill_item_gpdate&cust_odr=&id=${itemData.rowRefs[0].id}&qty=${newQty}&s=&cust=`;
      });

      masterUpdateBtn.prop('disabled', true); // Disable update button

      masterQtyInput.on('keydown', function (e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          masterVerify();
        }
      });

      masterItemInput.on('input', function () {
        if ($(this).val().trim() === '') {
          clearMasterInputs();
          masterVerifyBtn.prop('disabled', true); // Disable verify button
          masterUpdateBtn.prop('disabled', true); // Disable update button
        } else {
          masterVerifyBtn.prop('disabled', false); // Enable verify button
          masterUpdateBtn.prop('disabled', true); // Disable update button until verify is clicked
        }
      });

      // **NEW**: Event listener for the "Add New Item" button
      $('.add-new-item-btn').on('click', function (e) {
        e.preventDefault(); // Prevent default button action
        // Get all current item IDs and store them in sessionStorage
        const existingItemIds = $('.cross-check-item-row')
          .map(function () {
            return $(this).data('item-id');
          })
          .get();
        sessionStorage.setItem('existingItemIds', JSON.stringify(existingItemIds));
        // Now navigate to the add item page
        window.location = $(this).data('href');
      });

      // --- ADD THIS ENTIRE BLOCK for Smart Enter Key Handling ---
      masterItemInput.on('keydown', function (event) {
        if (event.key === 'Enter') {
          event.preventDefault(); // Always prevent the default Enter key action
          const term = $(this).val();
          const canonicalTerm = canonicalizeString(term); // Canonicalize input term

          console.log('Master: Searching for canonical term:', canonicalTerm); // Debug log

          if (!canonicalTerm) {
            masterFeedback.text('Please enter an item code or description.').css('color', 'orange');
            return;
          }

          // 1. Check for an EXACT canonical code match (perfect for barcode scanners)
          if (itemDataMap[canonicalTerm]) {
            const itemData = itemDataMap[canonicalTerm];
            console.log('Master: Exact canonical match found:', itemData); // Debug log
            currentSelectedItemCode = canonicalTerm;
            masterItemInput.val(itemData.desc); // Show original description to user
            masterBilledQtyInput.val(itemData.totalQty);
            masterUpdateBtn.prop('disabled', false);
            masterQtyInput.focus().select(); // Move focus to quantity input
            $(this).autocomplete('close'); // Hide dropdown
            return; // We're done
          }

          console.log('Master: No exact canonical match found. Searching for partial matches...'); // Debug log

          // 2. If no exact match, search for partial matches in the autocomplete list
          const source = $(this).autocomplete('instance').options.source;
          const searchResults = source.filter(function (item) {
            // Compare canonical term with canonical item code and canonical item description
            return item.value.includes(canonicalTerm) || canonicalizeString(item.desc).includes(canonicalTerm) || canonicalizeString(item.originalCode).includes(canonicalTerm);
          });

          console.log('Master: Search results for partial match:', searchResults); // Debug log

          // 3. Handle the results of the partial search
          if (searchResults.length === 1) {
            // If there's only ONE possible item, select it automatically
            const uniqueItem = searchResults[0];
            const itemData = itemDataMap[uniqueItem.value]; // .value is the canonical item code

            console.log('Master: Single partial match found:', itemData); // Debug log

            currentSelectedItemCode = uniqueItem.value;
            masterItemInput.val(itemData.desc);
            masterBilledQtyInput.val(itemData.totalQty);
            masterUpdateBtn.prop('disabled', false);
            masterQtyInput.focus().select();
            $(this).autocomplete('close');
          } else if (searchResults.length > 1) {
            console.log('Master: Multiple matches found. Opening dropdown...'); // Debug log
            // If there are multiple possibilities, open the dropdown for user to choose
            // Pass the original term to autocomplete search to allow default filtering
            $(this).autocomplete('search', term);
          } else {
            console.log('Master: No matches found. Showing "Item not found" message...'); // Debug log
            // No exact or partial matches found
            masterFeedback.text('Item not found.').css('color', 'red');
            $(this).select();
          }
        }
      });

      // --- Initial setup on page load ---
      buildItemDataMap();
      loadState(); // Load states for existing items first
      checkNewItems(); // Then, check for and auto-verify any new items
      updateRowNumbers(); // Ensure row numbers are correct on load
    });
  <?php endif; ?>
</script>

<script type="text/javascript">
  // ==================== 1. GLOBAL CORE HELPERS ====================

  function sendReturnAjax(
    url,
    successCallback,
    errorCallback,
    loadingCell = null,
    originalContent = null,
    context = null, // Add context parameter
  ) {
    if (loadingCell) {
      loadingCell.innerHTML = `<img src="images/loading.gif" style="width:20px" />`;
    }

    console.log('Sending AJAX request to:', url); // ✅ Add this

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
      console.log('ReadyState:', this.readyState, 'Status:', this.status, 'Response:', this.responseText); // ✅ Add this
      if (this.readyState == 4) {
        if (this.status == 200 && this.responseText.toLowerCase().includes('done')) {
          console.log('✅ AJAX Success');
          successCallback(this.responseText, context); // Pass context to success callback
        } else {
          console.log('❌ AJAX Error - Status:', this.status, 'Response:', this.responseText); // ✅ Add this
          errorCallback(this.responseText || 'Request failed');
          if (loadingCell && originalContent) {
            loadingCell.innerHTML = originalContent;
          }
        }
      }
    };
    xhttp.open('GET', url, true);
    xhttp.send();
  }

  function moveReturnRow(rtnId, fromTableBodyId, toTableBodyId, newButtonHtml) {
    const row = document.querySelector(`#${fromTableBodyId} tr[data-rtn-id="${rtnId}"]`);
    if (row) {
      const actionCellDiv = row.querySelector('[id^="return7_"]');
      if (actionCellDiv) {
        actionCellDiv.id = newButtonHtml.includes('Pack') ?
          `return7_${rtnId}` :
          `return7_done_${rtnId}`;
        actionCellDiv.innerHTML = newButtonHtml;
      }
      document.getElementById(toTableBodyId).appendChild(row);
    }
  }
  // ==================== 2. GLOBAL INTERFACE FUNCTIONS (Consolidated) ====================

  function returnPacked($id) {
    if (confirm('Are you sure you want to pack this item?')) {
      const packButtonCell = document.querySelector(`#return7_${$id}`);
      if (!packButtonCell) return; // Safety check

      const originalContent = packButtonCell.innerHTML;
      packButtonCell.innerHTML = `<img src="images/loading.gif" style="width:20px" />`;

      // Get the row to access its data attributes
      const row = document.querySelector(
        `#pending-returns-tbody tr[data-rtn-id='${$id}']`,
      );
      if (!row) {
        console.error('Could not find pending return row with id:', $id);
        packButtonCell.innerHTML = originalContent;
        return;
      }
      const qty = parseFloat(row.getAttribute('data-item-qty') || 0);

      const url = `index.php?components=order_process&action=return_packed&id=${$id}&odr_no=${orderId}`;

      const successCallback = function (response) {
        // 1. Update Local Storage State
        const state = JSON.parse(localStorage.getItem(storageKey)) || {};
        state[`return-${$id}`] = { checkedQty: qty.toString() };
        state.lastUpdated = new Date().getTime();
        localStorage.setItem(storageKey, JSON.stringify(state));

        // 2. Update the DOM
        row.classList.add('verified'); // Apply visual highlight
        packButtonCell.id = `return7_done_${$id}`; // Swap the container ID
        packButtonCell.innerHTML = `<input type="button" value="Remove" onmouseup="removeReturnPacked(${$id})" style="background-color:maroon; color:white; width:100%" />`;
        document.getElementById('packed-returns-tbody').appendChild(row);

        // 3. Keep the master UI box in sync
        if (window.buildReturnItemMaps) {
          window.buildReturnItemMaps();
        }
        updateRowNumbers(); // Update numbers after moving
      };

      const errorCallback = function (error) {
        alert('Error packing return item: ' + error);
        packButtonCell.innerHTML = originalContent; // Restore button on error
      };

      sendReturnAjax(
        url,
        successCallback,
        errorCallback,
        packButtonCell,
        originalContent,
      );
    }
  }

  function removeReturnPacked($id) {
    if (confirm('Are you sure you want to remove this packed item?')) {
      const removeButtonCell = document.querySelector(`#return7_done_${$id}`);
      if (!removeButtonCell) return; // Safety check

      const originalContent = removeButtonCell.innerHTML;
      removeButtonCell.innerHTML = `<img src="images/loading.gif" style="width:20px" />`;

      // Get the row to access its data attributes
      const row = document.querySelector(
        `#packed-returns-tbody tr[data-rtn-id='${$id}']`,
      );
      if (!row) {
        console.error('Could not find packed return row with id:', $id);
        removeButtonCell.innerHTML = originalContent;
        return;
      }

      const url = `index.php?components=order_process&action=remove_return_packed&id=${$id}&odr_no=${orderId}`;

      const successCallback = function (response, context) {
        // The 'context' here is the row element passed from the AJAX call
        const rowToMove = context;

        // 1. Update Local Storage State
        const state = JSON.parse(localStorage.getItem(storageKey)) || {};
        delete state[`return-${$id}`];
        state.lastUpdated = new Date().getTime();
        localStorage.setItem(storageKey, JSON.stringify(state));

        // 2. Update the DOM using the passed-in row context
        rowToMove.classList.remove('verified');
        removeButtonCell.id = `return7_${$id}`;
        removeButtonCell.innerHTML = `<input type="button" value="Pack" onmouseup="returnPacked(${$id})" style="background-color:green; color:white; width:100%" />`;
        document.getElementById('pending-returns-tbody').appendChild(rowToMove);

        // 3. Keep the master UI box in sync
        if (window.buildReturnItemMaps) {
          window.buildReturnItemMaps();
        }
        updateRowNumbers();
      };

      const errorCallback = function (error) {
        alert('Error unpacking return item: ' + error);
        removeButtonCell.innerHTML = originalContent; // Restore button on error
      };

      sendReturnAjax(
        url,
        successCallback,
        errorCallback,
        removeButtonCell,
        originalContent,
        row, // Pass the 'row' element as context
      );
    }
  }
  // ==================== 3. INITIALIZATION (Return Items Specific) ====================
  $(document).ready(function () {
    // Check if the UI box exists on the page
    if ($('.return-pending-cross-check-ui-box').length === 0) {
      return;
    }

    const returnPendingItemInput = $('#returnPendingItemInput');
    const returnPendingItemTotalQty = $('#returnPendingItemTotalQty');
    const returnPendingPackQtyInput = $('#returnPendingPackQty');
    const packReturnPendingItemBtn = $('#packReturnPendingItemBtn');
    const unpackReturnPendingItemBtn = $('#unpackReturnPendingItemBtn'); // FIX: Corrected ID here
    const returnPendingItemFeedback = $('#returnPendingItemFeedback');

    let pendingReturnItemsByCanonicalCode = {}; // Map uses canonical codes as keys
    let packedReturnItemsByCanonicalCode = {}; // Map uses canonical codes as keys
    let currentSelectedReturnItemCanonicalCode = null; // Stores canonical code
    let lastAutocompleteResults = [];


    // Enhanced subset sum algorithm with better error handling
    function findExactSubsetSum(items, targetQty) {
      const results = [];

      function backtrack(start, currentSum, currentSelection) {
        // Use tolerance for floating point comparison
        if (Math.abs(currentSum - targetQty) < 0.001) {
          results.push([...currentSelection]);
          return;
        }

        if (currentSum > targetQty || start >= items.length) {
          return;
        }

        // Include current item
        currentSelection.push(items[start]);
        backtrack(start + 1, currentSum + items[start].qty, currentSelection);
        currentSelection.pop();

        // Exclude current item
        backtrack(start + 1, currentSum, currentSelection);
      }

      // Sort by quantity to optimize search
      items.sort((a, b) => a.qty - b.qty);
      backtrack(0, 0, []);

      // Return the combination with the fewest items
      if (results.length > 0) {
        return results.reduce((min, current) =>
          current.length < min.length ? current : min
        );
      }
      return null;
    }

    // Enhanced AJAX function with detailed error handling
    async function sendReturnAjaxWithDetails(url, itemInfo) {
      return new Promise((resolve, reject) => {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
          if (this.readyState == 4) {
            if (this.status == 200) {
              const response = this.responseText.trim();
              if (response.toLowerCase().includes('done')) {
                resolve({
                  success: true,
                  itemInfo: itemInfo,
                  response: response
                });
              } else {
                resolve({
                  success: false,
                  itemInfo: itemInfo,
                  error: response,
                  isServerError: true
                });
              }
            } else {
              resolve({
                success: false,
                itemInfo: itemInfo,
                error: `HTTP Error: ${this.status} - ${this.statusText}`,
                isServerError: true
              });
            }
          }
        };

        xhttp.onerror = function () {
          resolve({
            success: false,
            itemInfo: itemInfo,
            error: 'Network error - failed to connect to server',
            isServerError: true
          });
        };

        xhttp.ontimeout = function () {
          resolve({
            success: false,
            itemInfo: itemInfo,
            error: 'Request timeout',
            isServerError: true
          });
        };

        xhttp.open('GET', url, true);
        xhttp.timeout = 30000; // 30 second timeout
        xhttp.send();
      });
    }

    // Save return item state with consistent format (only quantity and timestamp)
    function saveReturnItemState(rtnId, quantity, packed = true) {
      const state = JSON.parse(localStorage.getItem(storageKey)) || {};

      if (packed) {
        state[`return-${rtnId}`] = {
          checkedQty: quantity.toString()
        };
      } else {
        delete state[`return-${rtnId}`];
      }
      // Update the global lastUpdated timestamp
      state.lastUpdated = new Date().getTime();
      localStorage.setItem(storageKey, JSON.stringify(state));
    }

    // Load return item states and apply visual styling
    function loadReturnItemStates() {
      const state = JSON.parse(localStorage.getItem(storageKey)) || {};

      // Exclude lastUpdated when processing items
      const { lastUpdated, ...itemsState } = state;

      // Apply visual styling to packed return items
      Object.keys(itemsState).forEach(key => {
        if (key.startsWith('return-')) {
          const rtnId = key.replace('return-', '');
          const row = $(`tr[data-rtn-id="${rtnId}"]`);
          if (row.length) {
            row.addClass('verified');
            // Update button text if in pending table - CONSISTENT COLOR SCHEME
            const packButton = row.find('input[value="Pack"]');
            if (packButton.length) {
              packButton.val('Packed').prop('disabled', true)
                .css('background-color', '#28a745'); // Green for packed
            }

            // Update button in packed table - CONSISTENT MAROON COLOR
            const removeButton = row.find('input[value="Remove"]');
            if (removeButton.length) {
              removeButton.css('background-color', 'maroon')
                .css('color', 'white');
            }
          }
        }
      });
      console.log('Return Item states loaded from localStorage:', itemsState); // Debug log
    }

    // Update return item visual state with consistent colors
    function updateReturnItemVisualState(rtnId, quantity, packed = true) {
      const row = $(`tr[data-rtn-id="${rtnId}"]`);
      if (row.length) {
        if (packed) {
          row.addClass('verified');
          // Update button in pending table
          const packButton = row.find('input[value="Pack"]');
          if (packButton.length) {
            packButton.val('Packed').prop('disabled', true)
              .css('background-color', '#28a745'); // Green for packed
          }
        } else {
          row.removeClass('verified');
          // Update button in packed table - CONSISTENT MAROON COLOR
          const removeButton = row.find('input[value="Remove"]');
          if (removeButton.length) {
            removeButton.val('Remove').prop('disabled', false)
              .css('background-color', 'maroon') // Consistent maroon
              .css('color', 'white');
          }
        }
      }
    }

    // Enhanced moveReturnRow function with consistent visual state management
    function moveReturnRow(rtnId, fromTableBodyId, toTableBodyId, action, quantity) {
      const row = document.querySelector(`#${fromTableBodyId} tr[data-rtn-id="${rtnId}"]`);
      if (row) {
        const actionCellDiv = row.querySelector('[id^="return7_"]');
        if (actionCellDiv) {
          // Clear the cell completely before adding the new button
          actionCellDiv.innerHTML = '';

          const newButton = document.createElement('input');
          newButton.type = 'button';
          newButton.style.width = '100%';

          if (action === 'remove') {
            // Configure the new "Remove" button
            actionCellDiv.id = `return7_done_${rtnId}`;
            newButton.value = 'Remove';
            newButton.style.backgroundColor = 'maroon';
            newButton.style.color = 'white';
            newButton.onmouseup = function () {
              removeReturnPacked(rtnId);
            };
            row.classList.add('verified');
            saveReturnItemState(rtnId, quantity, true);
          } else {
            // Configure the new "Pack" button
            actionCellDiv.id = `return7_${rtnId}`;
            newButton.value = 'Pack';
            newButton.style.backgroundColor = 'green';
            newButton.style.color = 'white';
            newButton.onmouseup = function () {
              returnPacked(rtnId);
            };
            row.classList.remove('verified');
            saveReturnItemState(rtnId, quantity, false);
          }

          actionCellDiv.appendChild(newButton);
        }
        document.getElementById(toTableBodyId).appendChild(row);
      }
    }

    // Build maps of return items by canonical code
    window.buildReturnItemMaps = function () {
      pendingReturnItemsByCanonicalCode = {};
      packedReturnItemsByCanonicalCode = {};

      // Process pending returns
      $('#pending-returns-tbody tr').each(function () {
        const row = $(this);
        const rtnId = row.data('rtn-id');
        const originalItemCode = (row.data('item-code') || '').toString();
        const originalItemDesc = (row.data('item-desc') || '').toString();
        const canonicalItemCode = canonicalizeString(originalItemCode);
        const itemQty = parseFloat(row.data('item-qty') || 0);

        if (!rtnId || canonicalItemCode === '' || isNaN(itemQty) || itemQty <= 0) {
          console.warn(`Return Pending Item: Skipping row ${rtnId} due to invalid data. Canonical Code: "${canonicalItemCode}" Qty: ${itemQty}`);
          return;
        }

        if (!pendingReturnItemsByCanonicalCode[canonicalItemCode]) {
          pendingReturnItemsByCanonicalCode[canonicalItemCode] = {
            desc: originalItemDesc,
            originalCode: originalItemCode, // Store original code for display
            totalQty: 0,
            individualItems: [],
          };
        }
        pendingReturnItemsByCanonicalCode[canonicalItemCode].totalQty += itemQty;
        pendingReturnItemsByCanonicalCode[canonicalItemCode].individualItems.push({
          rtnId: rtnId,
          qty: itemQty,
          desc: originalItemDesc
        });
      });

      // Process packed returns
      $('#packed-returns-tbody tr').each(function () {
        const row = $(this);
        const rtnId = row.data('rtn-id');
        const originalItemCode = (row.data('item-code') || '').toString();
        const originalItemDesc = (row.data('item-desc') || '').toString();
        const canonicalItemCode = canonicalizeString(originalItemCode);
        const itemQty = parseFloat(row.data('item-qty') || 0);

        if (!rtnId || canonicalItemCode === '' || isNaN(itemQty) || itemQty <= 0) {
          console.warn(`Return Packed Item: Skipping row ${rtnId} due to invalid data. Canonical Code: "${canonicalItemCode}" Qty: ${itemQty}`);
          return;
        }

        if (!packedReturnItemsByCanonicalCode[canonicalItemCode]) {
          packedReturnItemsByCanonicalCode[canonicalItemCode] = {
            desc: originalItemDesc,
            originalCode: originalItemCode, // Store original code for display
            totalQty: 0,
            individualItems: [],
          };
        }
        packedReturnItemsByCanonicalCode[canonicalItemCode].totalQty += itemQty;
        packedReturnItemsByCanonicalCode[canonicalItemCode].individualItems.push({
          rtnId: rtnId,
          qty: itemQty,
          desc: originalItemDesc
        });
      });

      // Build autocomplete source - SHOW ORIGINAL DESCRIPTION AND CODE
      const allCanonicalCodes = new Set([
        ...Object.keys(pendingReturnItemsByCanonicalCode),
        ...Object.keys(packedReturnItemsByCanonicalCode)
      ]);

      const source = Array.from(allCanonicalCodes).map(canonicalCode => {
        const data = pendingReturnItemsByCanonicalCode[canonicalCode] || packedReturnItemsByCanonicalCode[canonicalCode];
        return {
          label: `${data.desc} (${data.originalCode})`, // Show description AND original code
          value: canonicalCode, // Value for selection should be canonical code
          desc: data.desc,
          originalCode: data.originalCode
        };
      });

      returnPendingItemInput.autocomplete('option', 'source', source);
      console.log('Return Pending Items Map built (pending):', pendingReturnItemsByCanonicalCode); // Debug log
      console.log('Return Packed Items Map built (packed):', packedReturnItemsByCanonicalCode); // Debug log
      console.log('Return Autocomplete Source:', source); // Debug log
    };

    function clearReturnPendingMasterInputs() {
      returnPendingItemInput.val('');
      returnPendingItemTotalQty.val('');
      returnPendingPackQtyInput.val('');
      packReturnPendingItemBtn.prop('disabled', true);
      unpackReturnPendingItemBtn.prop('disabled', true);
      returnPendingItemFeedback.text('');
      currentSelectedReturnItemCanonicalCode = null;
    }

    function updateButtonStates() {
      if (!currentSelectedReturnItemCanonicalCode) {
        packReturnPendingItemBtn.prop('disabled', true);
        unpackReturnPendingItemBtn.prop('disabled', true);
        return;
      }

      const pendingData = pendingReturnItemsByCanonicalCode[currentSelectedReturnItemCanonicalCode];
      const packedData = packedReturnItemsByCanonicalCode[currentSelectedReturnItemCanonicalCode];

      packReturnPendingItemBtn.prop('disabled', !pendingData || pendingData.totalQty === 0);
      unpackReturnPendingItemBtn.prop('disabled', !packedData || packedData.totalQty === 0);
    }

    function selectReturnItem(canonicalCode, itemDesc) {
      currentSelectedReturnItemCanonicalCode = canonicalCode;

      const pendingData = pendingReturnItemsByCanonicalCode[canonicalCode];
      const packedData = packedReturnItemsByCanonicalCode[canonicalCode];

      returnPendingItemInput.val(itemDesc); // Show original description
      returnPendingItemTotalQty.val(pendingData ? pendingData.totalQty : 0);

      updateButtonStates();
      returnPendingPackQtyInput.focus().select();
    }

    // Initialize autocomplete with enhanced behavior - LIKE BILL ITEMS
    returnPendingItemInput.autocomplete({
      source: function (request, response) {
        const term = request.term;
        const canonicalTerm = canonicalizeString(term); // Canonicalize input term

        const allItems = { ...pendingReturnItemsByCanonicalCode, ...packedReturnItemsByCanonicalCode };

        const matches = Object.keys(allItems)
          .filter(canonicalCode => {
            const item = allItems[canonicalCode];
            // Compare canonical term with canonical item code (the map key), canonical item description, AND canonical original code
            return canonicalCode.includes(canonicalTerm) ||
              canonicalizeString(item.desc).includes(canonicalTerm) ||
              canonicalizeString(item.originalCode).includes(canonicalTerm);
          })
          .map(canonicalCode => {
            const item = allItems[canonicalCode];
            return {
              label: `${item.desc} (${item.originalCode})`, // Show description AND original code
              value: canonicalCode, // Value for selection should be canonical code
              desc: item.desc,
              originalCode: item.originalCode
            };
          });

        lastAutocompleteResults = matches;
        response(matches);
      },
      minLength: 1,
      select: function (event, ui) {
        event.preventDefault();
        selectReturnItem(ui.item.value, ui.item.desc); // ui.item.value is already canonicalized
        $(this).autocomplete('close');
      },
      // IMPORTANT: Customize the display of items in the dropdown
      _renderItem: function (ul, item) {
        return $("<li>")
          .append("<div>" + item.desc + " (" + item.originalCode + ")</div>")
          .appendTo(ul);
      }
    });

    // AUTO-CLEAR when focusing on search input (EXACTLY LIKE BILL ITEMS)
    returnPendingItemInput.on('focus', function () {
      // Clear all inputs when focusing on search box
      clearReturnPendingMasterInputs();
      $(this).select(); // Select all text for easy replacement
    });

    // Enhanced Enter key handling with auto-selection (LIKE BILL ITEMS)
    returnPendingItemInput.on('keydown', function (event) {
      if (event.key === 'Enter') {
        event.preventDefault();
        const term = $(this).val();
        const canonicalTerm = canonicalizeString(term); // Canonicalize input term

        console.log('Return: Searching for canonical term on Enter:', canonicalTerm); // Debug log

        if (!canonicalTerm) {
          returnPendingItemFeedback.text('Please enter an item description.').css('color', 'orange');
          return;
        }

        const allItems = { ...pendingReturnItemsByCanonicalCode, ...packedReturnItemsByCanonicalCode };

        // 1. Try exact canonical code match first (for scanners)
        if (allItems[canonicalTerm]) {
          console.log('Return: Exact canonical code match found on Enter:', allItems[canonicalTerm]); // Debug log
          selectReturnItem(canonicalTerm, allItems[canonicalTerm].desc);
          $(this).autocomplete('close');
          return;
        }

        // 2. Try partial matches in description or original code
        const matchingCanonicalCodes = Object.keys(allItems).filter(canonicalCode => {
          const item = allItems[canonicalCode];
          return canonicalizeString(item.desc).includes(canonicalTerm) ||
            canonicalizeString(item.originalCode).includes(canonicalTerm); // Search original code
        });

        console.log('Return: Partial matches found on Enter:', matchingCanonicalCodes); // Debug log

        if (matchingCanonicalCodes.length === 1) {
          // Auto-select if only one match found (like bill items)
          const matchedCanonicalCode = matchingCanonicalCodes[0];
          selectReturnItem(matchedCanonicalCode, allItems[matchedCanonicalCode].desc);
          $(this).autocomplete('close');
        } else if (matchingCanonicalCodes.length > 1) {
          // Multiple matches - open dropdown
          $(this).autocomplete('search', term); // Use original term for autocomplete
          // Store the term for potential auto-selection after dropdown
          $(this).data('lastSearchTerm', term);
        } else {
          // No matches found
          returnPendingItemFeedback.text('Item not found.').css('color', 'red');
          $(this).select();
        }
      }
    });

    // Auto-select when dropdown opens with only one result
    returnPendingItemInput.on('autocompleteopen', function () {
      const term = $(this).data('lastSearchTerm');
      if (term && lastAutocompleteResults.length === 1) {
        // Small delay to ensure dropdown is visible
        setTimeout(() => {
          selectReturnItem(lastAutocompleteResults[0].value, lastAutocompleteResults[0].desc);
          $(this).autocomplete('close');
          $(this).removeData('lastSearchTerm');
        }, 100);
      }
    });

    // Enhanced pack button click handler
    packReturnPendingItemBtn.on('click', async function () {
      if (!currentSelectedReturnItemCanonicalCode) {
        returnPendingItemFeedback.text('Please select an item.').css('color', 'orange');
        returnPendingItemInput.focus();
        return;
      }

      const packQty = parseFloat(returnPendingPackQtyInput.val());
      if (isNaN(packQty) || packQty <= 0) {
        returnPendingItemFeedback.text('Please enter a valid quantity.').css('color', 'red');
        returnPendingPackQtyInput.focus().select();
        return;
      }

      const data = pendingReturnItemsByCanonicalCode[currentSelectedReturnItemCanonicalCode];
      if (!data || data.totalQty < packQty) {
        returnPendingItemFeedback.text('Insufficient pending quantity.').css('color', 'red');
        returnPendingPackQtyInput.focus().select();
        return;
      }

      // Find exact combination
      const combination = findExactSubsetSum(data.individualItems, packQty);
      if (!combination) {
        returnPendingItemFeedback.text(`No exact combination found for quantity ${packQty}.`).css('color', 'red');
        returnPendingPackQtyInput.focus().select();
        return;
      }

      // Show detailed progress
      returnPendingItemFeedback.html(`
        <div style="text-align: left;">
          <strong>Packing ${combination.length} item(s):</strong><br/>
          ${combination.map(item => `• ${item.desc} (Qty: ${item.qty}) - <span id="status-${item.rtnId}">Pending</span><br/>`).join('')}
        </div>
      `);

      packReturnPendingItemBtn.prop('disabled', true);
      unpackReturnPendingItemBtn.prop('disabled', true);

      const errors = [];
      let successCount = 0;

      // Process items sequentially to handle dependencies
      for (const item of combination) {
        const statusElement = $(`#status-${item.rtnId}`);
        statusElement.html('<em>Processing...</em>').css('color', 'blue');

        try {
          const url = `index.php?components=order_process&action=return_packed&id=${item.rtnId}&odr_no=${orderId}`;
          const result = await sendReturnAjaxWithDetails(url, item);

          if (result.success) {
            successCount++;
            statusElement.html('✓ Success').css('color', 'green');

            // Update local storage and visual state with CONSISTENT FORMAT
            saveReturnItemState(item.rtnId, item.qty, true);
            updateReturnItemVisualState(item.rtnId, item.qty, true);

            // Move row immediately for visual feedback with CONSISTENT MAROON COLOR
            moveReturnRow(
              item.rtnId,
              'pending-returns-tbody',
              'packed-returns-tbody',
              'remove', // This tells the function to create a "Remove" button
              item.qty,
            );
          } else {
            errors.push({
              rtnId: item.rtnId,
              desc: item.desc,
              qty: item.qty,
              error: result.error
            });
            statusElement.html('✗ Failed').css('color', 'red');

            // Show immediate alert for critical errors
            if (result.isServerError) {
              const userChoice = confirm(`Failed to pack item: ${item.desc}\nError: ${result.error}\n\nDo you want to continue with remaining items?`);
              if (!userChoice) {
                break; // Stop further processing if user chooses to stop
              }
            }
          }
        } catch (error) {
          errors.push({
            rtnId: item.rtnId,
            desc: item.desc,
            qty: item.qty,
            error: error.message || 'Unknown error'
          });
          statusElement.html('✗ Error').css('color', 'red');
        }
      }

      // Final summary
      let summaryHtml = '';
      if (successCount > 0) {
        summaryHtml += `<span style="color:green"><strong>Successfully packed ${successCount} item(s).</strong></span>`;
      }

      if (errors.length > 0) {
        summaryHtml += `<span style="color:red">
          <br/><strong>Failed to pack ${errors.length} item(s):</strong>
          <ul style="text-align: left; margin: 5px 0;">
            ${errors.map(err => `<li>${err.desc} (Qty: ${err.qty}) - ${err.error}</li>`).join('')}
          </ul>
        </span>`;
      }

      if (successCount === combination.length) {
        summaryHtml += `<br/><span style="color:green">All items packed successfully!</span>`;
      }

      returnPendingItemFeedback.append(`<div style="margin-top: 10px;">${summaryHtml}</div>`);

      // Rebuild maps and reset UI
      buildReturnItemMaps();
      setTimeout(() => {
        clearReturnPendingMasterInputs();
        returnPendingItemInput.focus();
      }, successCount > 0 ? 2000 : 0);
    });

    // Enhanced unpack button click handler
    unpackReturnPendingItemBtn.on('click', async function () {
      if (!currentSelectedReturnItemCanonicalCode) {
        returnPendingItemFeedback.text('Please select an item.').css('color', 'orange');
        returnPendingItemInput.focus();
        return;
      }

      const unpackQty = parseFloat(returnPendingPackQtyInput.val());
      if (isNaN(unpackQty) || unpackQty <= 0) {
        returnPendingItemFeedback.text('Please enter a valid quantity.').css('color', 'red');
        returnPendingPackQtyInput.focus().select();
        return;
      }

      const data = packedReturnItemsByCanonicalCode[currentSelectedReturnItemCanonicalCode];
      if (!data || data.totalQty < unpackQty) {
        returnPendingItemFeedback.text('Insufficient packed quantity.').css('color', 'red');
        returnPendingPackQtyInput.focus().select();
        return;
      }

      // Find exact combination
      const combination = findExactSubsetSum(data.individualItems, unpackQty);
      if (!combination) {
        returnPendingItemFeedback.text(`No exact combination found for quantity ${unpackQty}.`).css('color', 'red');
        returnPendingPackQtyInput.focus().select();
        return;
      }

      // Show detailed progress
      returnPendingItemFeedback.html(`
        <div style="text-align: left;">
          <strong>Unpacking ${combination.length} item(s):</strong><br/>
          ${combination.map(item => `• ${item.desc} (Qty: ${item.qty}) - <span id="status-unpack-${item.rtnId}">Pending</span><br/>`).join('')}
        </div>
      `);

      packReturnPendingItemBtn.prop('disabled', true);
      unpackReturnPendingItemBtn.prop('disabled', true);

      const errors = [];
      let successCount = 0;

      // Process items sequentially
      for (const item of combination) {
        const statusElement = $(`#status-unpack-${item.rtnId}`);
        statusElement.html('<em>Processing...</em>').css('color', 'blue');

        try {
          const url = `index.php?components=order_process&action=remove_return_packed&id=${item.rtnId}&odr_no=${orderId}`;
          const result = await sendReturnAjaxWithDetails(url, item);

          if (result.success) {
            successCount++;
            statusElement.html('✓ Success').css('color', 'green');

            // Update local storage and visual state with CONSISTENT FORMAT
            saveReturnItemState(item.rtnId, item.qty, false);
            updateReturnItemVisualState(item.rtnId, item.qty, false);

            // Move row immediately for visual feedback
            moveReturnRow(
              item.rtnId,
              'packed-returns-tbody',
              'pending-returns-tbody',
              'pack', // This tells the function to create a "Pack" button
              item.qty,
            );
          } else {
            errors.push({
              rtnId: item.rtnId,
              desc: item.desc,
              qty: item.qty,
              error: result.error
            });
            statusElement.html('✗ Failed').css('color', 'red');

            // Show immediate alert for critical errors
            if (result.isServerError) {
              const userChoice = confirm(`Failed to unpack item: ${item.desc}\nError: ${result.error}\n\nDo you want to continue with remaining items?`);
              if (!userChoice) {
                break; // Stop further processing if user chooses to stop
              }
            }
          }
        } catch (error) {
          errors.push({
            rtnId: item.rtnId,
            desc: item.desc,
            qty: item.qty,
            error: error.message || 'Unknown error'
          });
          statusElement.html('✗ Error').css('color', 'red');
        }
      }

      // Final summary
      let summaryHtml = '';
      if (successCount > 0) {
        summaryHtml += `<span style="color:green"><strong>Successfully unpacked ${successCount} item(s).</strong></span>`;
      }

      if (errors.length > 0) {
        summaryHtml += `<span style="color:red">
          <br/><strong>Failed to unpack ${errors.length} item(s):</strong>
          <ul style="text-align: left; margin: 5px 0;">
            ${errors.map(err => `<li>${err.desc} (Qty: ${err.qty}) - ${err.error}</li>`).join('')}
          </ul>
        </span>`;
      }

      if (successCount === combination.length) {
        summaryHtml += `<br/><span style="color:green">All items unpacked successfully!</span>`;
      }

      returnPendingItemFeedback.append(`<div style="margin-top: 10px;">${summaryHtml}</div>`);

      // Rebuild maps and reset UI
      buildReturnItemMaps();
      setTimeout(() => {
        clearReturnPendingMasterInputs();
        returnPendingItemInput.focus();
      }, successCount > 0 ? 2000 : 0);
    });

    // Quantity input enter key handler - auto-trigger pack/unpack
    returnPendingPackQtyInput.on('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        if (!packReturnPendingItemBtn.prop('disabled')) {
          packReturnPendingItemBtn.click();
        } else if (!unpackReturnPendingItemBtn.prop('disabled')) {
          unpackReturnPendingItemBtn.click();
        } else {
          returnPendingItemInput.focus(); // If no action possible, go back to search
        }
      }
    });

    // Clear feedback when user starts typing again
    returnPendingItemInput.on('input', function () {
      if ($(this).val().trim() && (returnPendingItemFeedback.text().includes('Successfully') || returnPendingItemFeedback.text().includes('Failed'))) {
        returnPendingItemFeedback.text('');
      }
    });

    // Initial setup
    buildReturnItemMaps();
    loadReturnItemStates(); // Load and apply visual states
    clearReturnPendingMasterInputs();
  });
</script>

<?php
include_once 'template/m_footer.php';
?>