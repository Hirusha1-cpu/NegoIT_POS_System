<?php
    include_once 'template/header.php';
    $decimal = getDecimalPlaces(1);
?>
<style>
    tr {
        transition: background-color 0.3s ease;
    }
</style>

<script type="text/javascript">
    function filter() {
        var user = document.getElementById('user').value;
        var datefrom = document.getElementById('datefrom').value;
        var dateto = document.getElementById('dateto').value;

        var url = 'index.php?components=<?php echo $components; ?>&action=cheque_on_hand'
                // + '&bank=' + bank
                + '&user=' + user
                + '&datefrom=' + datefrom
                + '&dateto=' + dateto;

        window.location = url;
    }
</script>

<form action="index.php?components=<?php print $components; ?>&action=pending_cheque_transfers" method="get">
    <table border="0" width="900px" align="center" height="100%" cellspacing="0"
        style="font-size:10pt; font-family:Calibri; border-radius: 5px;" bgcolor="#F0F0F0">
        <tr>
            <td align="center">
                <strong>Salesman</strong>&nbsp;&nbsp;&nbsp;
                <select id="user" name="user" onchange="filter();">
                    <option value="">-ALL-</option>
                    <?php for ($i = 0; $i < sizeof($sm_id); $i++) {
                        if (isset($_GET['user'])) {
                            if ($_GET['user'] == $sm_id[$i]) {
                                $select = 'selected="selected"';
                            } else
                                $select = '';
                        } else
                            $select = '';
                        print '<option value="' . $sm_id[$i] . '" ' . $select . '>' . ucfirst($sm_name[$i]) . '</option>';
                    }
                    ?>
                </select>
            </td>
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
        </tr>
        <tr>
            <td colspan="7">
                <br> &nbsp;&nbsp;&nbsp;Cheque On Hand Report
                <hr />
            </td>
        </tr>
    </table>
</form>
<br>

<div>
    <table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
        <tr>
            <td colspan="14" style="border:0; background-color:black; color:white; font-weight:bold"></td>
        </tr>
        <tr bgcolor="#E5E5E5" style="height: 25px">
            <th class="tb2"><span id="checkedCount"></span></th>
            <th class="tb2">#</th>
            <th class="tb2">Payment No</th>
            <th class="tb2">Cheque No</th>
            <th class="tb2">Cheque Date</th>
            <th class="tb2">Amount</th>
            <th class="tb2">Bank Name</th>
            <th class="tb2">Customer Name</th>
            <th class="tb2">Payment Date</th>
            <th class="tb2">Transfer Date</th>
            <th class="tb2">Salesman</th>
            <th class="tb2">Status</th>
            <th class="tb2">On Hand</th>
        </tr>
        <?php
            for ($i = 0; $i < sizeof($payment_id); $i++) {
                $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id='.$payment_id[$i].'">'.str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT).'</a>';
                $status_text = '';
                switch($status[$i]){
                    case 0:
                        $status_text = 'Pending';
                    break;
                    case 1:
                        $status_text = 'Accept';
                    break;
                    case 2:
                        $status_text = 'Not Receive';
                    break;
                    case 3:
                        $status_text = 'Success';
                    break;
                    case 4:
                        $status_text = 'In Trans';
                    break;
                    case 5:
                        $status_text = 'Accept Trans';
                    break;
                    case 6:
                        $status_text = 'Reject Trans';
                    break;
                    case 7:
                        $status_text = 'Bank Reject';
                    break;
                    case 8:
                        $status_text = 'Cash Receive';
                    break;
                    case 9:
                        $status_text = 'Issue New Cheque';
                    break;
                    case 10:
                        $status_text = 'Modify Cheque';
                    break;
                    case 11:
                        $status_text = 'Return Pending';
                    break;
                    case 12:
                        $status_text = 'Return Accept';
                    break;
                    case 13:
                        $status_text = 'Return Reject';
                    break;
                    default:
                        $status_text = 'Unknown';
                    break;
                }
                print '<tr id="row_' . $trans_id[$i] . '" bgcolor="#F5F5F5" style="height: 30px">
                        <td style="padding-right:10px; padding-left:10px; text-align:center;"><input type="checkbox" class="checkRow" onclick="updateCheckedCount()"/></td>
                        <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $payment_link . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $chq_full_no[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $chq_date[$i] . '</td>
                        <td align="right" style="padding-right:10px">' . number_format($payment_amount[$i], $decimal) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $bank_name[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $cust_name[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_date[$i])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($trans_time[$i])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $salesman[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $status_text . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $trans_to[$i] . '</td>
                </tr>';
            }
            print '<tr style="height: 35px">
                    <td colspan="14" align="right" style="padding-right:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
                </tr>';
        ?>
    </table>
</div>

<div style="margin-top:40px">
    <table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
        <tr>
            <td colspan="14" style="border:0; background-color:black; color:white; font-weight:bold"></td>
        </tr>
        <tr bgcolor="#E5E5E5" style="height: 25px">
            <th class="tb2"><span id="salesmanCheckedCount"></span></th>
            <th class="tb2">#</th>
            <th class="tb2">Payment No</th>
            <th class="tb2">Cheque No</th>
            <th class="tb2">Cheque Date</th>
            <th class="tb2">Amount</th>
            <th class="tb2">Bank Name</th>
            <th class="tb2">Customer Name</th>
            <th class="tb2">Payment Date</th>
            <th class="tb2">Salesman</th>
        </tr>
        <?php
            for ($j = 0; $j < sizeof($payment_id_1); $j++) {
                $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id='.$payment_id_1[$j].'">'.str_pad($payment_id_1[$j], 7, "0", STR_PAD_LEFT).'</a>';
                print '<tr id="salesman_row_' . $payment_id_1[$j] . '" bgcolor="#F5F5F5" style="height: 30px">
                        <td style="padding-right:10px; padding-left:10px; text-align:center;"><input type="checkbox" class="salesmanCheckRow" onclick="updateCheckedCount1()"/></td>
                        <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($j + 1)) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $payment_link . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $chq_full_no_1[$j] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $chq_date_1[$j] . '</td>
                        <td align="right" style="padding-right:10px">' . number_format($payment_amount_1[$j], $decimal) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $bank_name_1[$j] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $cust_name_1[$j] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_date_1[$j])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $salesman_1[$j] . '</td>
                </tr>';
            }
            print '<tr style="height: 35px">
                    <td colspan="14" align="right" style="padding-right:10px">Total Amount: ' . number_format(array_sum($payment_amount_1), $decimal) . '</td>
                </tr>';
        ?>
    </table>
</div>


<script>
    function updateCheckedCount() {
        const checkboxes = document.querySelectorAll(".checkRow");
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        const checkedCountDisplay = document.getElementById("checkedCount");
        checkedCountDisplay.textContent = checkedCount > 0 ? checkedCount : ""; // Display "" if no checkboxes are checked

        // Highlight rows for checked checkboxes
        checkboxes.forEach(checkbox => {
            const row = checkbox.closest("tr");
            if (checkbox.checked) {
                row.style.backgroundColor = "#BFED9B"; // Highlight color
            }else{
                row.style.backgroundColor = "#F5F5F5"; // Highlight color
            }
        });
    }

    function updateCheckedCount1() {
        const checkboxes = document.querySelectorAll(".salesmanCheckRow");
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        const checkedCountDisplay = document.getElementById("salesmanCheckedCount");
        checkedCountDisplay.textContent = checkedCount > 0 ? checkedCount : ""; // Display "" if no checkboxes are checked

        // Highlight rows for checked checkboxes
        checkboxes.forEach(checkbox => {
            const row = checkbox.closest("tr");
            if (checkbox.checked) {
                row.style.backgroundColor = "#BFED9B"; // Highlight color
            }else{
                row.style.backgroundColor = "#F5F5F5"; // Highlight color
            }
        });
    }

    // Call it initially to set the count on page load
    updateCheckedCount();

    // Call it initially to set the count on page load
    updateCheckedCount1();
</script>

<?php
    include_once 'template/footer.php';
?>