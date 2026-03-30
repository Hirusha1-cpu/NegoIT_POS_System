<?php
include_once 'template/m_header.php';
?>

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
      <table align="center">
        <tr>
          <td>Cust Odr No: </td>
          <td>
            <form method="get" action="index.php">
              <input type="hidden" name="components" value="backend" />
              <input type="hidden" name="action" value="cust_order_mgmt" />
              <input type="text" style="width:250px" name="bill_no" id="bill_no" placeholder="Invoice Number" /><input
                type="Submit" value="Search" />
            </form>
          </td>
        </tr>
      </table>
      <br>
      <?php
      if ($inv_found) { ?>
        <table align="center" style="font-family:Calibri;">
          <tr>
            <th style="background-color:#467898;color :white;" width="150px">Cust Odr No:</th>
            <td style="padding-left:10px; background-color:#CCCCCC">
              <strong><?php print str_pad($id, 7, "0", STR_PAD_LEFT); ?></strong>
            </td>
          </tr>
          <tr>
            <th style="background-color:#467898;color :white;" width="150px">Date</th>
            <td style="padding-left:10px; background-color:#CCCCCC"><?php print $inv_date; ?></td>
          </tr>
          <tr>
            <th style="background-color:#467898;color :white;" width="150px">Store</th>
            <td style="padding-left:10px; background-color:#CCCCCC"><?php print $inv_store; ?></td>
          </tr>
          <tr>
            <th style="background-color:#467898;color :white;" width="150px">Status</th>
            <td style="padding-left:10px; background-color:#CCCCCC; color:<?php print $status_color; ?>">
              <strong><?php print $status_out; ?></strong>
            </td>
          </tr>
        </table>
        <br />

        <form method="post" action="index.php?components=backend&action=set_cust_order_main">
          <input type="hidden" name="bill_no" value="<?php print $_GET['bill_no']; ?>">
          <table align="center" style="font-family:Calibri;" bgcolor="#EEEEEE">
            <tr>
              <td>
                <table align="center" style="font-family:Calibri;">
                  <tr>
                    <th style="background-color:#467898;color:white;" width="100px">Status</th>
                    <th>
                      <select name="status" id="statusSelect">
                        <?php if ($inv_status == 3 || $inv_status == 4 || $inv_status == 5) { ?>
                          <option value="1" <?php if ($inv_status == 1)
                            print 'selected="selected"'; ?>>Billed</option>
                        <?php } else { ?>
                          <option value="1" <?php if ($inv_status == 1)
                            print 'selected="selected"'; ?>>Billed</option>
                          <option value="2" <?php if ($inv_status == 2)
                            print 'selected="selected"'; ?>>Picked</option>
                          <option value="3" <?php if ($inv_status == 3)
                            print 'selected="selected"'; ?>>Packed</option>
                          <option value="4" <?php if ($inv_status == 4)
                            print 'selected="selected"'; ?>>Shipped</option>
                          <option value="5" <?php if ($inv_status == 5)
                            print 'selected="selected"'; ?>>Delivered</option>
                        </select>
                      <?php } ?>
                    </th>
                  </tr>
                </table>
              </td>
              <?php if ($inv_status != 0 && $inv_status != 1 && $inv_status != 2) { ?>
                <td width="120px" align="center">
                  <input type="submit" value="Set" style="height:50px; width:70px"
                    onclick="return confirm('Are you sure you want to set this status?');" />
                </td>
              <?php } ?>
            </tr>
          </table>
        </form>
      <?php }
      ?>
    </div>
  </div>
</div>
</div>
<hr>
<br />
<?php
include_once 'template/m_footer.php';
?>