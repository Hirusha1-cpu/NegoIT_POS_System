<?php
include_once 'template/header.php';
$menu_components = $_GET['components'];
?>

<!-- 1. Include jQuery (Legacy 1.8.0 as requested) & Select2 -->
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
  .padding {
    padding-left: 10px;
    padding-right: 10px;
  }

  .table-header {
    background-color: black;
    color: white;
    font-weight: bold;
    padding: 5px;
  }

  .row-total {
    background-color: #EEE;
    font-weight: bold;
  }

  /* Fix for Select2 width in table cells */
  .select2-container {
    width: 100% !important;
    max-width: 300px;
    text-align: left;
  }

  tr.data-row:hover td {
    background-color: #ffffcc !important;
    /* Light Yellow on Hover */
    cursor: default;
  }
</style>

<!-- FILTERS SECTION -->
<div style="background-color:#EEEEEE; border-radius:10px; padding: 10px;">
  <form id="filter_form" action="index.php" method="get">
    <input type="hidden" name="components" value="<?php print $menu_components; ?>" />
    <input type="hidden" name="action" value="item_monthly_sales_report" />

    <table align="center" cellspacing="5" style="font-family:Calibri">
      <tr>
        <!-- Date Range -->
        <td align="right"><strong>From:</strong></td>
        <td><input type="date" name="date1" value="<?php echo $date1; ?>" style="width:130px"></td>
        <td align="right"><strong>To:</strong></td>
        <td><input type="date" name="date2" value="<?php echo $date2; ?>" style="width:130px"></td>

        <!-- ITEM FILTER (Using Select2) -->
        <td align="right" style="color:maroon;"><strong>Select Item*:</strong></td>
        <td width="250">
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

        <td>
          <!-- Search Icon (Optional now, since Select2 triggers submit on change) -->
          <a onclick="document.getElementById('filter_form').submit();" style="cursor:pointer">
            <img src="images/search.png" style="width:30px; vertical-align:middle" />
          </a>
        </td>
      </tr>
      <tr>
        <!-- Town / District -->
        <td align="right"><strong>Cust Town:</strong></td>
        <td>
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

        <!-- Salesman -->
        <td align="right"><strong>Bill Salesman:</strong></td>
        <td>
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

        <!-- Cust Group -->
        <td align="right"><strong>Group:</strong></td>
        <td>
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
    </table>
  </form>
</div>
<br />

<!-- REPORT GRID -->
<?php if ($item_req != '') { ?>
  <div id="print">
    <h5 align="center" style="font-family:Calibri; color:#333;">Item Sales Matrix</h5>

    <table align="center" bgcolor="#E5E5E5" border="1" cellspacing="0"
      style="font-family:Calibri; width: auto; min-width: 50%;">
      <!-- Headers -->
      <thead>
        <tr>
          <td class="table-header" width="30px">#</td>
          <td class="table-header">Customer Name</td>
          <?php
          foreach ($month_headers as $key => $label) {
            echo '<td class="table-header" align="center" width="80px">' . $label . '</td>';
          }
          ?>
          <td class="table-header" align="center" width="80px" style="background-color: maroon;">Total</td>
        </tr>
      </thead>

      <!-- Data Body -->
      <tbody>
        <?php
        if (empty($cust_names)) {
          echo '<tr><td colspan="' . (count($month_headers) + 3) . '" align="center" style="padding:20px;">No sales found for this criteria.</td></tr>';
        } else {
          $i = 1;
          foreach ($cust_names as $cust_id => $name) {
            $row_total = 0;
            echo '<tr class="data-row">';
            echo '<td align="center">' . sprintf('%02d', $i++) . '</td>';
            echo '<td class="padding" style="font-weight:bold; color:#005588;">' . ucfirst($name) . '</td>';

            foreach ($month_headers as $ym => $label) {
              $qty = isset($report_data[$cust_id][$ym]) ? $report_data[$cust_id][$ym] : 0;
              $display = ($qty == 0) ? '-' : number_format($qty, 0);
              $style = ($qty == 0) ? 'color:#CCC;' : 'font-weight:bold;';

              echo '<td align="right" style="' . $style . '" class="padding">' . $display . '</td>';
              $row_total += $qty;
            }

            echo '<td align="right" class="row-total padding">' . number_format($row_total, 0) . '</td>';
            echo '</tr>';
          }

          // Grand Total Row
          echo '<tr style="background-color: #333; color: white; font-weight: bold;" class="padding">';
          echo '<td colspan="2" align="right" class="padding">Monthly Totals</td>';
          $final_total = 0;
          foreach ($month_headers as $ym => $label) {
            $m_total = $grand_totals_month[$ym];
            $final_total += $m_total;
            echo '<td align="right" class="padding">' . ($m_total > 0 ? number_format($m_total, 0) : '-') . '</td>';
          }
          echo '<td align="right" style="background-color: maroon;" class="padding">' . number_format($final_total, 0) . '</td>';
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
<?php } else { ?>
  <div align="center" style="margin-top:10px; font-family:Calibri; color:gray;">
    <h5>Please select an Item to view the Sales Matrix.</h5>
  </div>
<?php } ?>

<!-- INITIALIZE SELECT2 -->
<script>
  $(document).ready(function () {
    // Initialize Item Select with search
    $('#item_select').select2({
      placeholder: "Search Item...",
      allowClear: true,
      width: 'resolve' // Uses the width of the container or CSS
    });

    // Initialize other filters
    $('#town_select').select2({ placeholder: "All Towns", allowClear: true });
    $('#salesman_select').select2({ placeholder: "All Salesmen", allowClear: true });
    $('#group_select').select2({ placeholder: "All Groups", allowClear: true });
  });
</script>

<?php include_once 'template/footer.php'; ?>