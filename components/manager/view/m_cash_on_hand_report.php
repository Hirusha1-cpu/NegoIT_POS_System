<?php
include_once 'template/m_header.php';
$decimal = getDecimalPlaces(1);
?>

<div class="w3-container" style="margin-top:75px">
  <hr>
  <div class="w3-row">
    <div class="w3-col s3"></div>
    <div class="w3-col">
      Note: This report shows data of cash on hand and cash transfers.
    </div>
    <div class="w3-col">
      <hr />
      <table align="center" height="100%"
        style="font-size:10pt; font-family:Calibri; max-width: fit-content;overflow-x: auto;display: block;">
        <tr bgcolor="#CCCCCC">
          <th>#</th>
          <th>Salesman</th>
          <th>Cash On Hand</th>
          <th>Cash In Trans</th>
          <th>Cash Out Trans</th>
        </tr>
        <?php
        for ($i = 0; $i < sizeof($user_id); $i++) {
          print '<tr id="row_' . $user_id[$i] . '" bgcolor="#F5F5F5" style="height: 30px">
                    <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . $user_name[$i] . '</td>
                    <td align="right" style="padding-right:10px; padding-left:10px">' . ($amount_to_settle[$i] != 0 ? number_format($amount_to_settle[$i], $decimal) : '') . '</td>
                    <td align="right" style="padding-right:10px; padding-left:10px">' . ($payment_in_trans[$i] != 0 ? number_format($payment_in_trans[$i], $decimal) : '') . '</td>
                    <td align="right" style="padding-right:10px; padding-left:10px">' . ($payment_out_trans[$i] != 0 ? number_format($payment_out_trans[$i], $decimal) : '') . '</td>
            </tr>';
        }
        print '<tr style="height: 35px">
            <td colspan="2" align="right" style="padding-right:10px; padding-left:10px">Total</td>
            <td align="right" style="padding-right:10px; padding-left:10px">' . number_format(array_sum($amount_to_settle), $decimal) . '</td>
            <td align="right" style="padding-right:10px; padding-left:10px">' . number_format(array_sum($payment_in_trans), $decimal) . '</td>
            <td align="right" style="padding-right:10px; padding-left:10px">' . number_format(array_sum($payment_out_trans), $decimal) . '</td>
        </tr>';
        ?>
      </table>
    </div>
  </div>
</div>

</div>
<hr>
<br />
<?php
include_once 'template/m_footer.php';
?>