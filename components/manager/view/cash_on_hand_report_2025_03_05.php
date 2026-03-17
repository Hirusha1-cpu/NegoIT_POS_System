<?php
    include_once 'template/header.php';
    $decimal = getDecimalPlaces(1);
?>

<script type="text/javascript">
    // function filter() {
    //     var datefrom = document.getElementById('datefrom').value;
    //     var dateto = document.getElementById('dateto').value;

    //     // Construct URL with all filter parameters
    //     var url = 'index.php?components=<?php echo $components; ?>&action=cash_on_hand_report'
    //             + '&datefrom=' + datefrom
    //             + '&dateto=' + dateto;

    //     // Trigger page reload with updated filters in the URL
    //     window.location = url;
    // }
</script>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<form action="index.php?components=<?php print $components; ?>&action=cash_on_hand_report" method="post">
    <table border="0" width="900px" align="center" height="100%" cellspacing="0"
        style="font-size:10pt; font-family:Calibri; border-radius: 5px;" bgcolor="#F0F0F0">
        <!-- <tr style="height: 40px">
            <td align="center">
                <strong>From</strong>&nbsp;&nbsp;&nbsp;
                <input type="date" id="datefrom" name="datefrom" style="width:130px"
                    value="<?php echo isset($fromdate) ? htmlspecialchars($fromdate) : ''; ?>"
                    onchange="filter();"/>
            </td>
            <td align="center">
                <strong>To</strong>&nbsp;&nbsp;&nbsp;
                <input type="date" id="dateto" name="dateto" style="width:130px"
                    value="<?php echo isset($todate) ? htmlspecialchars($todate) : ''; ?>"
                    onchange="filter();"/>
            </td>
        </tr> -->
        <tr>
            <td colspan="7">
                <br> &nbsp;&nbsp;&nbsp;Note: This report shows data of cash on hand and cash in trans.
                <hr />
            </td>
        </tr>
    </table>
</form>
<br>

<div id="print">
    <table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
        <tr>
            <td colspan="10" style="border:0; background-color:black; color:white; font-weight:bold"></td>
        </tr>
        <tr bgcolor="#E5E5E5" style="height: 25px">
            <th class="tb2">#</th>
            <th class="tb2">Salesman</th>
            <th class="tb2">Cash On Hand</th>
            <th class="tb2">Cash In Trans</th>
        </tr>
        <?php

        for ($i = 0; $i < sizeof($user_id); $i++) {
            print '<tr id="row_' . $user_id[$i] . '" bgcolor="#F5F5F5" style="height: 30px">
                    <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                    <td style="padding-right:10px; padding-left:10px">' . $user_name[$i] . '</td>
                    <td align="right" style="padding-right:10px; padding-left:10px">' . number_format($amount_to_settle[$i], $decimal) . '</td>
                    <td align="right" style="padding-right:10px; padding-left:10px">' . number_format($payment_in_trans[$i], $decimal) . '</td>
            </tr>';
        }
        print '<tr style="height: 35px">
            <td colspan="2" align="right" style="padding-right:10px; padding-left:10px">Total</td>
            <td align="right" style="padding-right:10px; padding-left:10px">'. number_format(array_sum($amount_to_settle), $decimal) . '</td>
            <td align="right" style="padding-right:10px; padding-left:10px">'. number_format(array_sum($payment_in_trans), $decimal) . '</td>
        </tr>';
        ?>
    </table>
</div>

<?php
include_once 'template/footer.php';
?>