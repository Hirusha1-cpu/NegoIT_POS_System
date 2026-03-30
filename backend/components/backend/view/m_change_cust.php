<?php
include_once 'template/m_header.php';
?>

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="w3-container" style="margin-top:75px">
  <hr>
  <div class="w3-row">
    <div class="w3-col s3">
    </div>
    <div class="w3-col">

      <table align="center" style="font-size:11pt">
        <tr>
          <td>
            <?php
            if (isset($_REQUEST['message'])) {
              if ($_REQUEST['re'] == 'success')
                $color = 'green';
              else
                $color = 'red';
              print '<span style="color:' . $color . '; font-weight:bold;">' . $_REQUEST['message'] . '</span><br /><br />';
            }
            ?>
          </td>
        </tr>
      </table>

      <form method="get" action="index.php">
        <input type="hidden" name="components" value="backend" />
        <input type="hidden" name="action" value="change_cust" />
        <table align="center" width="90%">
          <tr bgcolor="#EEEEEE">
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr bgcolor="#EEEEEE">
            <td align="center">Invoice ID</td>
            <td align="center"><strong><input type="text" name="invoice_id" id="invoice_id" value="<?php if (isset($invoice_id))
              print $invoice_id; ?>" style="width: 100%;"> </strong></td>
          </tr>
          <tr bgcolor="#EEEEEE">
            <td colspan="2" align="right"><input type="submit" value="Search Invoice"
                style="width:180px; height:40px" /></td>
          </tr>
        </table>
      </form>

      <?php if (isset($inv_found) && $inv_found) { ?>
        <br>
        <form method="POST" action="index.php?components=backend&action=change_cust_invoice" id="invoice_change_form">
          <input type="hidden" name="invoice_id" value="<?php if (isset($invoice_id))
            print $invoice_id; ?>" />
          <table align="center" width="90%">
            <tr bgcolor="#EEEEEE">
              <td align="left">Invoice No</td>
              <td align="left">
                <strong><?php if (isset($invoice_id))
                  print str_pad($invoice_id, 7, "0", STR_PAD_LEFT); ?></strong>
              </td>
            </tr>
            <tr bgcolor="#EEEEEE">
              <td align="left">Existing Customer</td>
              <td align="left"><strong><?php if (isset($invoice_cust_name))
                print $invoice_cust_name; ?></strong></td>
            </tr>
            <tr bgcolor="#EEEEEE">
              <td align="left">Invoice Date</td>
              <td align="left"><strong><?php if (isset($invoice_date))
                print $invoice_date; ?></strong></td>
            </tr>
            <tr bgcolor="#EEEEEE">
              <td align="left">Salesman</td>
              <td align="left"><strong><?php if (isset($invoice_salesman))
                print $invoice_salesman; ?></strong></td>
            </tr>
            <tr bgcolor="#EEEEEE">
              <td align="left">New Customer</td>
              <td align="left">
                <strong>
                  <select id="new_invoice_cust_id" name="new_invoice_cust_id" style="width: 100%;">
                    <option value="">Select a customer</option>
                    <?php
                    // Assuming $customers is an array of customer names and IDs
                    foreach ($customers as $customer) {
                      echo '<option value="' . $customer['id'] . '">' . $customer['name'] . '</option>';
                    }
                    ?>
                  </select>
                </strong>
              </td>
            </tr>
            <tr bgcolor="#EEEEEE">
              <td colspan="2" align="center"><input type="submit" value="Change Cust" style="width:180px; height:40px" />
              </td>
            </tr>
          </table>
        </form>
      <?php } ?>
      <br>
      <hr>

      <form method="get" action="index.php">
        <input type="hidden" name="components" value="backend" />
        <input type="hidden" name="action" value="change_cust" />
        <table align="center" width="90%">
          <tr bgcolor="#EEEEEE">
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr bgcolor="#EEEEEE">
            <td align="center">Payment ID</td>
            <td align="center"><strong><input type="text" name="payment_id" id="payment_id" value="<?php if (isset($payment_id))
              print $payment_id; ?>" style="width: 100%;"> </strong></td>
          </tr>
          <tr bgcolor="#EEEEEE">
            <td colspan="2" align="right"><input type="submit" value="Search Payment"
                style="width:180px; height:40px" /></td>
          </tr>
        </table>
      </form>

      <?php if (isset($pay_found) && $pay_found) { ?>
        <form method="POST" action="index.php?components=backend&action=change_cust_payment" id="payment_change_form">
          <input type="hidden" name="payment_id" value="<?php if (isset($payment_id))
            print $payment_id; ?>" />
          <table align="center" width="90%">
            <tr bgcolor="#EEEEEE">
              <td align="left">Payment No</td>
              <td align="left"><strong><?php if (isset($payment_id))
                print str_pad($payment_id, 7, "0", STR_PAD_LEFT); ?></strong></td>
            </tr>
            <tr bgcolor="#EEEEEE">
              <td align="left">Existing Customer</td>
              <td align="left"><strong><?php if (isset($payment_cust_name))
                print $payment_cust_name; ?></strong></td>
            </tr>
            <tr bgcolor="#EEEEEE">
              <td align="left">Payment Date</td>
              <td align="left"><strong><?php if (isset($payment_date))
                print $payment_date; ?></strong></td>
            </tr>
            <tr bgcolor="#EEEEEE">
              <td align="left">Salesman</td>
              <td align="left"><strong><?php if (isset($payment_salesman))
                print $payment_salesman; ?></strong></td>
            </tr>
            <tr bgcolor="#EEEEEE">
              <td align="left">New Customer</td>
              <td align="left">
                <strong>
                  <select id="new_payment_cust_id" name="new_payment_cust_id" style="width: 100%;">
                    <option value="">Select a customer</option>
                    <?php
                    // Assuming $customers is an array of customer names and IDs
                    foreach ($customers as $customer) {
                      echo '<option value="' . $customer['id'] . '">' . $customer['name'] . '</option>';
                    }
                    ?>
                  </select>
                </strong>
              </td>
            </tr>
            <tr bgcolor="#EEEEEE">
              <td colspan="2" align="center"><input type="submit" value="Change Cust" style="width:180px; height:40px" />
              </td>
            </tr>
          </table>
        </form>
      <?php } ?>
      <br>
      <hr>
    </div>
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
    // Initialize Select2 for invoice customer dropdown
    $('#new_invoice_cust_id').select2({
      placeholder: 'Select a customer',
      allowClear: true
    });

    // Initialize Select2 for payment customer dropdown
    $('#new_payment_cust_id').select2({
      placeholder: 'Select a customer',
      allowClear: true
    });

    // Confirmation for invoice change
    $('#invoice_change_form').on('submit', function (e) {
      // Check if a customer is selected
      var selectedCustomer = $('#new_invoice_cust_id').val();
      if (!selectedCustomer) {
        alert('Please select a customer before proceeding.');
        e.preventDefault(); // Prevent form submission
        return;
      }

      // Confirm the change
      if (!confirm('Are you sure you want to change the customer for this invoice?')) {
        e.preventDefault(); // Prevent form submission
      }
    });

    // Confirmation for payment change
    $('#payment_change_form').on('submit', function (e) {
      // Check if a customer is selected
      var selectedCustomer = $('#new_payment_cust_id').val();
      if (!selectedCustomer) {
        alert('Please select a customer before proceeding.');
        e.preventDefault(); // Prevent form submission
        return;
      }

      // Confirm the change
      if (!confirm('Are you sure you want to change the customer for this payment?')) {
        e.preventDefault(); // Prevent form submission
      }
    });
  });
</script>

<?php
include_once 'template/m_footer.php';
?>