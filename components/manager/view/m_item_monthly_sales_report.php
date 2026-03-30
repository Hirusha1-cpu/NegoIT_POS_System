<?php
include_once 'template/m_header.php';
$menu_components = $_GET['components'];
?>

<!-- Include Select2 & jQuery -->
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
  .w3-top,
  .w3-bottom,
  header,
  footer,
  #header,
  #footer {
    z-index: 9999 !important;
    position: fixed;
    /* Ensure they are fixed if not already */
  }

  /* --- GENERAL TABLE STYLES --- */
  .table-header {
    background-color: black;
    color: white;
    font-weight: bold;
    padding: 5px;
    white-space: nowrap;
    font-size: 12px;
  }

  .row-total {
    background-color: #EEE;
    font-weight: bold;
  }

  .shipmentTB6 {
    font-size: 12px;
    padding: 5px;
  }

  /* --- STICKY COLUMN (Freezes Customer Name) --- */
  .cust-col {
    white-space: nowrap;
    font-weight: bold;
    color: #005588;
    position: sticky;
    left: 0;
    background-color: #fff;
    z-index: 2;
    border-right: 2px solid #ccc;
    /* Limit width */
    overflow: hidden;
    text-overflow: ellipsis;
    /* Add dots if name is too long */
  }

  .data-cell {
    padding: 5px;
    min-width: 60px;
    text-align: center;
    font-size: 12px;
  }

  /* ----------------------------------------------------------- */
  /*   CRITICAL SELECT2 LONG NAME FIXES                          */
  /* ----------------------------------------------------------- */

  /* 1. Force the container to respect the parent width */
  .select2-container {
    width: 100% !important;
    max-width: 100%;
    font-size: 12px;
    /* Keep text small on mobile */
  }

  /* 2. Style the "Closed" box */
  .select2-container .select2-selection--single {
    height: 35px !important;
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
    padding: 2px;
  }

  /* 3. Handle Long Text in the "Closed" box: Cut off with (...) */
  .select2-selection__rendered {
    line-height: 28px !important;
    white-space: nowrap !important;
    /* Force single line */
    overflow: hidden !important;
    /* Hide overflow */
    text-overflow: ellipsis !important;
    /* Add "..." at the end */
    display: block !important;
    padding-right: 20px;
    /* Make room for the 'x' button */
    color: #333 !important;
  }

  /* 4. Align the Arrow */
  .select2-selection__arrow {
    height: 33px !important;
  }

  /* 5. Handle Long Text in the "Open" List: WRAP IT so you can read it */
  .select2-results__option {
    white-space: normal !important;
    /* Allow wrapping */
    word-wrap: break-word !important;
    font-size: 12px;
    padding: 8px 5px !important;
    border-bottom: 1px solid #eee;
    line-height: 1.3 !important;
  }

  /* 6. Prevent the dropdown menu from being wider than the screen */
  .select2-dropdown {
    max-width: 90vw !important;
    /* 90% of screen width */
    width: auto !important;
    /* Auto adjust */
    min-width: 200px;
    /* Minimum readable width */
  }

  /* 7. Specific fix for Table Layout inputs */
  .filter-input-td {
    max-width: 60vw;
    /* Prevent TD from blowing out screen */
    width: 100%;
  }
</style>

<div class="w3-container" style="margin-top:75px">
  <hr>
  <div class="w3-row">
    <!-- Spacing Col -->
    <div class="w3-col s1"></div>

    <!-- Filter Section -->
    <div class="w3-col s10">
      <form id="filter_form" action="index.php" method="get">
        <input type="hidden" name="components" value="<?php print $menu_components; ?>" />
        <input type="hidden" name="action" value="item_monthly_sales_report" />

        <table height="100%" cellspacing="0" style="font-family:Calibri; width:100%; table-layout: fixed;"
          bgcolor="#F0F0F0" border="0">
          <!-- Note: table-layout: fixed prevents the table from expanding infinitely -->
          <col width="30%">
          <col width="70%">

          <!-- Date Range -->
          <tr>
            <td class="shipmentTB6"><strong>From</strong></td>
            <td class="filter-input-td"> : <input type="date" name="date1" value="<?php echo $date1; ?>"
                style="width:90%" onchange="document.getElementById('filter_form').submit();"></td>
          </tr>
          <tr>
            <td class="shipmentTB6"><strong>To</strong></td>
            <td class="filter-input-td"> : <input type="date" name="date2" value="<?php echo $date2; ?>"
                style="width:90%" onchange="document.getElementById('filter_form').submit();"></td>
          </tr>

          <!-- ITEM FILTER (Select2) -->
          <tr>
            <td class="shipmentTB6" style="color:maroon;"><strong>Item*</strong></td>
            <td class="filter-input-td"> :
              <select id="item_select" name="item_id" onchange="document.getElementById('filter_form').submit();">
                <option value="">-- SEARCH ITEM --</option>
                <?php
                for ($i = 0; $i < count($item_id_list); $i++) {
                  $sel = ($item_req == $item_id_list[$i]) ? 'selected' : '';
                  echo '<option value="' . $item_id_list[$i] . '" ' . $sel . '>' . $item_name_list[$i] . '</option>';
                }
                ?>
              </select>
            </td>
          </tr>

          <!-- Town Filter -->
          <tr>
            <td class="shipmentTB6"><strong>Cust Town:</strong></td>
            <td class="filter-input-td"> :
              <select id="town_select" name="town" onchange="document.getElementById('filter_form').submit();">
                <option value="all">-- ALL --</option>
                <?php
                for ($i = 0; $i < count($town_id); $i++) {
                  $sel = ($town_req == $town_id[$i]) ? 'selected' : '';
                  echo '<option value="' . $town_id[$i] . '" ' . $sel . '>' . $town_name[$i] . '</option>';
                }
                ?>
              </select>
            </td>
          </tr>

          <!-- Salesman Filter -->
          <tr>
            <td class="shipmentTB6"><strong>Bill Salesman:</strong></td>
            <td class="filter-input-td"> :
              <select id="salesman_select" name="salesman" onchange="document.getElementById('filter_form').submit();">
                <option value="all">-- ALL --</option>
                <?php
                for ($i = 0; $i < sizeof($up_id); $i++) {
                  $sel = ($up_id[$i] == $salesman_req) ? 'selected' : '';
                  echo '<option value="' . $up_id[$i] . '" ' . $sel . '>' . ucfirst($up_name[$i]) . '</option>';
                }
                ?>
              </select>
            </td>
          </tr>

          <!-- Group Filter -->
          <tr>
            <td class="shipmentTB6"><strong>Group</strong></td>
            <td class="filter-input-td"> :
              <select id="group_select" name="group" onchange="document.getElementById('filter_form').submit();">
                <option value="all">-- ALL --</option>
                <?php
                for ($i = 0; $i < sizeof($gp_id); $i++) {
                  $sel = ($gp_id[$i] == $group_req) ? 'selected' : '';
                  echo '<option value="' . $gp_id[$i] . '" ' . $sel . '>' . ucfirst($gp_name[$i]) . '</option>';
                }
                ?>
              </select>
            </td>
          </tr>

          <tr>
            <td colspan="2" align="center" style="padding:10px;">
              <a onclick="document.getElementById('filter_form').submit();" style="cursor:pointer">
                <img src="images/search.png" style="width:40px; vertical-align:middle" /> Refresh
              </a>
            </td>
          </tr>
        </table>
      </form>
    </div>
    <div class="w3-col s1"></div>
  </div>

  <hr>

  <!-- DATA TABLE SECTION -->
  <?php if ($item_req != '') { ?>
    <div class="w3-row">
      <div class="w3-col">
        <h4 align="center" style="font-family:Calibri; color:#333; margin:0px;">Item Sales Matrix</h4>
        <br />

        <!-- Horizontal Scroll Container -->
        <table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"
          style="font-family:Calibri; overflow-x: auto; display: block; white-space: nowrap;">
          <thead>
            <tr>
              <td class="table-header cust-col" style="background-color:black; color:white;">Customer</td>
              <?php
              foreach ($month_headers as $key => $label) {
                echo '<td class="table-header" align="center">' . $label . '</td>';
              }
              ?>
              <td class="table-header" align="center" style="background-color: maroon;">Total</td>
            </tr>
          </thead>
          <tbody>
            <?php
            if (empty($cust_names)) {
              echo '<tr><td colspan="' . (count($month_headers) + 2) . '" align="center" style="padding:20px;">No sales found.</td></tr>';
            } else {
              foreach ($cust_names as $cust_id => $name) {
                $row_total = 0;
                echo '<tr>';
                // Customer Name (Sticky Left)
                echo '<td class="cust-col" style="padding-left:5px; border-right:2px solid #ccc;">' . ucfirst($name) . '</td>';

                foreach ($month_headers as $ym => $label) {
                  $qty = isset($report_data[$cust_id][$ym]) ? $report_data[$cust_id][$ym] : 0;

                  // Color Logic
                  if ($qty > 0) {
                    $display = number_format($qty, 0);
                    $style = 'font-weight:bold; color:black;';
                  } elseif ($qty < 0) {
                    $display = number_format($qty, 0);
                    $style = 'font-weight:bold; color:red;'; // Changed back to RED for negative
                  } else {
                    $display = '-';
                    $style = 'color:#CCC;';
                  }

                  echo '<td class="data-cell" style="' . $style . '">' . $display . '</td>';
                  $row_total += $qty;
                }

                // Row Total
                echo '<td align="center" class="row-total">' . number_format($row_total, 0) . '</td>';
                echo '</tr>';
              }

              // Grand Total Row
              echo '<tr style="background-color: #333; color: white; font-weight: bold;">';
              echo '<td class="cust-col" align="right" style="background-color: #333; color: white; border-right:2px solid #ccc; padding-right:5px;">Totals</td>';
              $final_total = 0;
              foreach ($month_headers as $ym => $label) {
                $m_total = $grand_totals_month[$ym];
                $final_total += $m_total;
                echo '<td align="center" class="data-cell">' . ($m_total != 0 ? number_format($m_total, 0) : '-') . '</td>';
              }
              echo '<td align="center" style="background-color: maroon;">' . number_format($final_total, 0) . '</td>';
              echo '</tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php } else { ?>
    <div align="center" style="margin-top:20px; font-family:Calibri; color:gray;">
      <h5>Please select an Item.</h5>
    </div>
  <?php } ?>

  <br><br>
</div>

<!-- Initialize Select2 for Mobile -->
<script>
  $(document).ready(function () {
    // Add specific class to dropdowns to apply specific width logic
    $('#item_select').select2({ placeholder: "Search Item...", allowClear: true });
    $('#town_select').select2({ placeholder: "All Towns", allowClear: true });
    $('#salesman_select').select2({ placeholder: "All Salesmen", allowClear: true });
    $('#group_select').select2({ placeholder: "All Groups", allowClear: true });
  });
</script>

<?php include_once 'template/m_footer.php'; ?>