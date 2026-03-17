<?php
include_once 'template/header.php';
?>
<!-- Original jQuery UI includes for Autocomplete -->
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- You can remove this if not used elsewhere -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- MODIFIED: Added styles for the main container -->
<style>
  /* NEW: This controls the main container for the table */
  #print {
    max-width: 1200px;
    /* Adjust this value as needed */
    margin: 0 auto;
    /* This centers the container */
  }

  /* This aligns the search box to the right within the container */
  .dataTables_filter {
    float: right !important;
    margin-bottom: 10px;
  }
</style>

<script type="text/javascript">
  // Your existing autocomplete script - no changes needed here
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

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:30px" /></div>

<div>
  <form action="index.php" method="get">
    <input type="hidden" name="components" value="<?php print $_GET['components']; ?>" />
    <input type="hidden" name="action" value="return_availability" />
    <input type="hidden" id="customer_id" name="customer_id" value="" />
    <table align="center" height="100%" cellspacing="0" style="font-size:10pt">
      <tr>
        <td colspan="2" align="right">
          <table>
            <tr>
              <td>
                <strong>Customer</strong> :
              </td>
              <td align="right">
                <input type="text" id="tags1" value="<?php print $customer; ?>" onclick="this.value=''"
                  style="width: 200px;" placeholder="Customer Name" />
              </td>
            </tr>
          </table>
        </td>
        <td>
          <input type="submit" onclick="setCustID()" value="GET" />
        </td>
      </tr>
    </table>
  </form>
</div>

<br /><br />

<!-- The #print div is now styled to be centered with a max-width -->
<div id="print">
  <!-- MODIFIED: Removed align="center" from table, it's now handled by the #print div's CSS -->
  <table id="returnAvailabilityTable" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"
    style="font-size:12pt; -webkit-print-color-adjust: exact; font-family: calibri; width: 100%;">
    <thead style="background-color:#E5E5E5; color:black; font-weight:bold;">
      <tr style="background-color: black; color: white;">
        <td colspan="8" style="border:0; padding-left:10px">Table showing the return availability of items for a
          specific customer</td>
      </tr>
      <tr height="30px">
        <th width="60px" class="shipmentTB3">#</th>
        <th class="shipmentTB3">Code</th>
        <th class="shipmentTB3">Description</th>
        <th class="shipmentTB3">Sold (+) QTY</th>
        <th class="shipmentTB3">Sold (-) QTY</th>
        <th class="shipmentTB3">Packed QTY</th>
        <th class="shipmentTB3">Un-Packed QTY</th>
        <th class="shipmentTB3">Return Eligible QTY</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if (isset($return_availability) && !empty($return_availability) && is_array($return_availability)) {
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
    </tbody>
  </table>
</div>

<br />

<!-- DataTables JavaScript library -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Script to initialize DataTables on your table -->
<script>
  $(document).ready(function () {
    $('#returnAvailabilityTable').DataTable({
      pageLength: 50,
      searching: true,
      lengthChange: false,
      paging: true,
      info: true,
      "columnDefs": [{
        "orderable": false,
        "targets": [0, 3, 4, 5, 6]
      }]
    });
  });
</script>

<?php
include_once 'template/footer.php';
?>