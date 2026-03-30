<?php
include_once 'template/m_header.php';
?>
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
          <td id="search_label">Transfer No : </td>
          <td style="padding-left:10px;">
            <form method="get" action="index.php">
              <input type="hidden" name="components" value="backend" />
              <input type="hidden" name="action" value="cash_payment_deposit" />
              <input type="text" style="width:200px;" name="id" id="id" placeholder="Transfer Number" />
              <input type="submit" value="Search" />
            </form>
          </td>
        </tr>
      </table>
      <br>

      <?php if (isset($payment_found) && $payment_found): ?>
        <!-- Transfer Details -->
        <table align="center" style="font-size:11pt;">
          <tr>
            <th class="th-style" colspan="2">Transfer Payment Details</th>
          </tr>
          <tr>
            <td class="td-style"><strong>Trans No:</strong></td>
            <td class="td-style"><?php echo htmlspecialchars($id); ?></td>
          </tr>
          <tr>
            <td class="td-style"><strong>Trans Source:</strong></td>
            <td class="td-style"><?php
            if ($transfer_source == 1) {
              echo "USER";
            } else if ($transfer_source == 2) {
              echo "BANK";
            } else {
              echo "Unknown";
            }
            ?></td>
          </tr>
          <tr>
            <td class="td-style"><strong>Amount:</strong></td>
            <td class="td-style"><?php echo htmlspecialchars(number_format($amount, 2)); ?></td>
          </tr>
          <tr>
            <td class="td-style"><strong>Salesman:</strong></td>
            <td class="td-style"><?php echo htmlspecialchars($payment_placed_by); ?></td>
          </tr>
          <tr>
            <td class="td-style"><strong>Deposited To:</strong></td>
            <td class="td-style"><?php echo htmlspecialchars($bank_name); ?></td>
          </tr>
          <tr>
            <td class="td-style"><strong>Transfer To:</strong></td>
            <td class="td-style"><?php echo htmlspecialchars($transfer_to); ?></td>
          </tr>
          <tr>
            <td class="td-style"><strong>Transfer Date:</strong></td>
            <td class="td-style"><?php echo htmlspecialchars($transfer_date); ?></td>
          </tr>
          <tr>
            <td class="td-style"><strong>Approved/Reviewed By:</strong></td>
            <td class="td-style"><?php echo htmlspecialchars($approved_by); ?></td>
          </tr>
          <tr>
            <td class="td-style"><strong>Status:</strong></td>
            <td class="td-style"><?php
            if ($status == 0) {
              echo "Canceled";
            } elseif ($status == 1) {
              echo "Pending";
            } elseif ($status == 2) {
              echo "Accepted";
            } else {
              echo "Unknown";
            }
            ?></td>
          </tr>
          <?php if (isset($payment_found) && $payment_found) { ?>
            <!-- Update Form for Return & Replace Items -->
            <form method="post" action="index.php?components=backend&amp;action=delete_cash_payment_deposit">
              <!-- Pass the invoice id so it can be used in the update handler -->
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
              <tr>
                <td colspan="2" align="center">
                  <input type="submit" value="Delete Trans" style="width:100%"
                    onclick="return confirm('Are you sure you want to delete this transaction?');">
                </td>
              </tr>
            </form>
          <?php } ?>
        </table>
        <br>
      <?php endif ?>
    </div>
  </div>
</div>
<hr>
<br />
<?php
include_once 'template/m_footer.php';
?>