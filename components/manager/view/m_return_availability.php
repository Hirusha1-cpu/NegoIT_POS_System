<?php
include_once 'template/m_header.php';
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
  $(function () {
    var availableTags1 = [<?php for ($x = 0; $x < sizeof($cu_name0); $x++) {
      print '"' . $cu_name0[$x] . '",';
    } ?>];
    $("#tags1").autocomplete({
      source: availableTags1
    });
  });

  function setCustID() {
    var id_arr = [<?php for ($x = 0; $x < sizeof($cu_id0); $x++) {
      print '"' . $cu_id0[$x] . '",';
    } ?>];
    var name_arr = [<?php for ($x = 0; $x < sizeof($cu_name0); $x++) {
      print '"' . $cu_name0[$x] . '",';
    } ?>];
    var name = document.getElementById('tags1').value;
    if (name != '') {
      var a = name_arr.indexOf(name);
      document.getElementById('customer_id').value = id_arr[a];
    }
  }
</script>

<style>
  .table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    /* smooth scrolling on iOS */
  }

  .table-responsive table {
    min-width: 800px;
    border-collapse: collapse;
  }
</style>

</head>

<div class="w3-container" style="margin-top:75px">
  <?php
  if (isset($_REQUEST['message'])) {
    if ($_REQUEST['re'] == 'success')
      $color = 'green';
    else
      $color = 'red';
    print '<span style="color:' . $color . '; font-weight:bold;font-size:large;">' . $_REQUEST['message'] . '</span>';
  }
  ?>
  <hr>

  <div id="loading" style="display:none"><img src="images/loading.gif" style="width:30px" /></div>

  <div class="w3-row">
    <div class="w3-col s3">
    </div>
    <div class="w3-col">
      <form action="index.php" method="get">
        <input type="hidden" name="components" value="<?php print $_GET['components']; ?>" />
        <input type="hidden" name="action" value="return_availability" />
        <input type="hidden" id="customer_id" name="customer_id" value="" />
        <table align="center" width="100%">
          <tr>
            <td width="25%">Customer</td>
            <td width="50%"><input type="text" name="customer" id="tags1" value="<?php print $customer; ?>"
                onclick="this.value=''" style="width:100%" /></td>
            <td align="right" width="25%"><input type="submit" onclick="setCustID()" value="GET" style="width:80px;" />
            </td>
          </tr>
        </table>
      </form>

      <br />

      <div class="table-responsive">
        <table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"
          style="font-size:xx-small; overflow-x:auto;">
          <tr>
            <td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold; padding-left:10px">
              Item Return
              Availability</td>
          </tr>
          <tr>
            <th class="shipmentTB3">#</th>
            <th class="shipmentTB3">Code</th>
            <th class="shipmentTB3">Description</th>
            <th class="shipmentTB3">Sold (+) QTY</th>
            <th class="shipmentTB3">Sold (-) QTY</th>
            <th class="shipmentTB3">Packed QTY</th>
            <th class="shipmentTB3">Un-Packed QTY</th>
            <th class="shipmentTB3">Return Eligible QTY</th>
          </tr>
          <?php
          if (isset($return_availability) && !empty($return_availability) && is_array($return_availability)) {
            // Fetch and display the return availability data for the selected customer
            $i = 1;
            foreach ($return_availability as $item) {
              if ($item['sold_positive_qty'] > 0 || $item['sold_negative_qty'] > 0 || $item['return_packed_qty'] > 0 || $item['return_unpacked_qty'] > 0 || $item['return_available_qty'] > 0) {
                echo "<tr height='25px;' style='background-color: #FFFFFF;'>";
                echo "<td align='center' style='padding:0 5px;'>" . sprintf('%02d', ($i)) . "</td>";
                echo "<td align='left' style='padding:0 5px;'>" . htmlspecialchars($item['code']) . "</td>";
                echo "<td align='left' style='padding:0 5px;'>" . htmlspecialchars($item['description']) . "</td>";
                echo "<td align='right' style='padding:0 5px;'>" . htmlspecialchars($item['sold_positive_qty']) . "</td>";
                echo "<td align='right' style='padding:0 5px;'>" . htmlspecialchars($item['sold_negative_qty']) . "</td>";
                echo "<td align='right' style='padding:0 5px;'>" . htmlspecialchars($item['return_packed_qty']) . "</td>";
                echo "<td align='right' style='padding:0 5px;'>" . htmlspecialchars($item['return_unpacked_qty']) . "</td>";
                echo "<td align='right' style='padding:0 5px;'>" . htmlspecialchars($item['return_available_qty']) . "</td>";
                echo "</tr>";
                $i++;
              }
            }
          }
          ?>
        </table>
      </div>
      <br /><br />
    </div>
  </div>
</div>

<hr>

<?php
include_once 'template/m_footer.php';
?>