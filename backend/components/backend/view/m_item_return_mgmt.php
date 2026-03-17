<?php
include_once 'template/m_header.php';
?>
<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
  .th-style {
    background-color: #467898;
    color: white;
    text-align: left;
    padding: 0 10px;
  }

  .td-style {
    padding: 0 10px;
    background-color: #CCCCCC;
    text-align: left;
  }
</style>

<div class="w3-container" style="margin-top:75px">
  <hr>
  <div class="w3-row">
    <div class="w3-col s3">
      <!-- Optional sidebar or empty space -->
    </div>
    <div class="w3-col">
      <?php
      // Display any message passed from the controller
      if (isset($_REQUEST['message'])) {
        $color = ($_REQUEST['re'] == 'success') ? 'green' : 'red';
        echo '<table align="center" style="font-size:11pt">
                <tr>
                  <td>
                    <span style="color:' . $color .
          '; font-weight:bold;">' .
          htmlspecialchars($_REQUEST['message']) .
          '</span><br /><br />
                  </td>
                </tr>
              </table>';
      }
      ?>

      <!-- Search Form for Invoice Number -->
      <table align="center">
        <tr>
          <td id="search_label">Invoice No : </td>
          <td style="padding-left:10px;">
            <form method="get" action="index.php">
              <input type="hidden" name="components" value="backend" />
              <input type="hidden" name="action" value="item_return_mgmt" />
              <input type="text" style="width:200px;" name="invoice_id" id="invoice_id" placeholder="Invoice Number" />
              <input type="submit" value="Search" />
            </form>
          </td>
        </tr>
      </table>
      <br>

      <?php if (isset($inv_found) && $inv_found): ?>
        <!-- Invoice Details -->
        <table align="center" style="font-size:11pt;">
          <tr>
            <th class="th-style" colspan="2">Return Invoice Details</th>
          </tr>
          <tr>
            <td class="td-style"><strong>Customer:</strong></td>
            <td class="td-style"><?php echo htmlspecialchars($invoice_cust_name); ?></td>
          </tr>
          <tr>
            <td class="td-style"><strong>Salesman:</strong></td>
            <td class="td-style"><?php echo htmlspecialchars($invoice_salesman); ?></td>
          </tr>
          <tr>
            <td class="td-style"><strong>Return Date:</strong></td>
            <td class="td-style"><?php echo htmlspecialchars($invoice_date); ?></td>
          </tr>
        </table>
        <br>

        <!-- Update Form for Return & Replace Items -->
        <form method="post" action="index.php?components=backend&amp;action=update_item_return">
          <!-- Pass the invoice id so it can be used in the update handler -->
          <input type="hidden" name="invoice_id" value="<?php echo htmlspecialchars($invoice_id); ?>">

          <!-- Customer Update Field -->
          <table align="center" style="font-size:11pt;">
            <tr>
              <th class="th-style" colspan="2">Update Invoice Details</th>
            </tr>
            <tr>
              <td class="td-style"><strong>New Customer:</strong></td>
              <td class="td-style">
                <select id="new_return_cust_id" name="new_return_cust_id" style="width: 100%;">
                  <option value="">Select a customer</option>
                  <?php
                  // Loop over the $customers array to build options
                  foreach ($customers as $customer) {
                    $selected = (isset($current_customer_id) && $current_customer_id == $customer['id'])
                      ? 'selected' : '';
                    echo '<option value="' . $customer['id'] . '" ' . $selected . '>'
                      . htmlspecialchars($customer['name']) . '</option>';
                  }
                  ?>
                </select>
              </td>
            </tr>
          </table>

          <br>

          <table align="center" style="font-size:11pt;">
            <tr>
              <th class="th-style">#</th>
              <th class="th-style">Return Item</th>
              <th class="th-style">Replace Item</th>
            </tr>
            <?php
            // Loop through each return record for this invoice
            for ($i = 0; $i < count($return_item); $i++):
              ?>
              <tr>
                <td class="td-style">
                  <?php echo ($i + 1); ?>
                  <!-- Include hidden identifier for the record -->
                  <input type="hidden" name="return_record_ids[]"
                    value="<?php echo htmlspecialchars($return_record_ids[$i]); ?>">
                </td>
                <td class="td-style">
                  <select class="return_item_select" name="return_item[]" style="width: 100%;">
                    <option value="">Select Return Item</option>
                    <?php foreach ($inventory_options as $item):
                      $selected = ($item['description'] === $return_item[$i]) ? 'selected' : '';
                      ?>
                      <option value="<?php echo $item['id']; ?>" <?php echo $selected; ?>>
                        <?php echo htmlspecialchars($item['description']); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td class="td-style">
                  <select class="replace_item_select" name="replace_item[]" style="width: 100%;">
                    <option value="">Select Replace Item</option>
                    <?php foreach ($inventory_options as $item):
                      $selected = ($item['description'] === $replace_item[$i]) ? 'selected' : '';
                      ?>
                      <option value="<?php echo $item['id']; ?>" <?php echo $selected; ?>>
                        <?php echo htmlspecialchars($item['description']); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
              </tr>
            <?php endfor; ?>
            <tr>
              <td colspan="3" align="center">
                <input type="submit" value="Update Items" style="width:100%">
              </td>
            </tr>
          </table>
        </form>
      <?php endif; ?>

    </div>
  </div>
</div>
<hr>
<br />

<!-- Include jQuery and Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function () {
    // Initialize Select2 for customer dropdown
    $('#new_return_cust_id').select2({
      placeholder: 'Select a customer',
      allowClear: true
    });

    // Initialize Select2 for return items dropdown(s)
    $('.return_item_select').select2({
      placeholder: 'Select Return Item',
      allowClear: true
    });

    // Initialize Select2 for replace items dropdown(s)
    $('.replace_item_select').select2({
      placeholder: 'Select Replace Item',
      allowClear: true
    });
  });
</script>

<?php
include_once 'template/m_footer.php';
?>