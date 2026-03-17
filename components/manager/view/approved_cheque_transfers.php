<?php
    include_once 'template/header.php';
    $decimal = getDecimalPlaces(1);
?>

<script type="text/javascript">
    function filter() {
        // var bank = document.getElementById('bank').value;
        // var status = document.getElementById('status').value;
        var salesaman = document.getElementById('salesaman').value;
        // var reviewedByElement = document.getElementById('reviewed_by');
        // var reviewed_by = reviewedByElement ? reviewedByElement.value : null;
        var datefrom = document.getElementById('datefrom').value;
        var dateto = document.getElementById('dateto').value;

        // Construct URL with all filter parameters
        var url = 'index.php?components=<?php echo $components; ?>&action=cheque_transfer'
            // + '&bank=' + bank
            + '&salesaman=' + salesaman
            + '&datefrom=' + datefrom
            + '&dateto=' + dateto;

        // Trigger page reload with updated filters in the URL
        window.location = url;
    }

    function changeStatus(selectElement, depositId) {
        const selectedValue = selectElement.value;

        // Check if a valid option is selected
        if (selectedValue) {
            if (confirm('Are you sure you want to change the status of this deposit?')) {
                // Send AJAX request
                fetch('index.php?components=<?php echo $components; ?>&action=change_cheque_transfer_status_ajax', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        deposit_id: depositId,
                        status: selectedValue
                    })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        // Hide the entire select element
                        selectElement.style.display = 'none'; // Hide the select element

                        // Create a done message
                        const doneMessage = document.createElement('span');
                        doneMessage.textContent = 'DONE';
                        doneMessage.style.color = 'green'; // Set text color to green
                        doneMessage.style.fontWeight = 'BOLD';
                        selectElement.parentNode.insertBefore(doneMessage, selectElement.nextSibling); // Insert the message after the select element
                        alert(result.message);
                    } else {
                        // Handle error (you can show a message to the user)
                        alert('Failed to update status: ' + result.message);
                    }
                })
                .catch(error => {
                    alert('An error occurred while updating the status.' + error);
                });
            }
        }
    }

</script>

<style>
    tr {
        transition: background-color 0.3s ease;
    }
</style>

<form action="index.php?components=<?php print $components; ?>&action=cheque_transfer" method="post">
    <table border="0" width="900px" align="center" height="100%" cellspacing="0"
        style="font-size:10pt; font-family:Calibri; border-radius: 5px;" bgcolor="#F0F0F0">
        <!-- <tr style="height: 40px">
            <td align="center">
                <strong>Salesman</strong>&nbsp;&nbsp;&nbsp;
                <select id="salesaman" name="salesaman" onchange="filter();">
                    <option value="">-ALL-</option>
                    <?php for ($i = 0; $i < sizeof($manage_user_id); $i++) {
                        if (isset($_GET['salesaman'])) {
                            if ($_GET['salesaman'] == $manage_user_id[$i]) {
                                $select = 'selected="selected"';
                            } else
                                $select = '';
                        } else
                            $select = '';
                        print '<option value="' . $manage_user_id[$i] . '" ' . $select . '>' . ucfirst($manage_user_name[$i]) . '</option>';
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
        </tr> -->
        <tr>
            <td colspan="7">
                <br> &nbsp;&nbsp;&nbsp;Note: This report shows approved cheque transfers collected by you (cheque in hand).
                <hr />
            </td>
        </tr>
    </table>
</form>
<br>

<div>
    <table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
        <tr>
            <td colspan="17" style="border:0; background-color:black; color:white; font-weight:bold"></td>
        </tr>
        <tr bgcolor="#E5E5E5" style="height: 25px">
            <th class="tb2"><span id="checkedCount"></span></th>
            <th class="tb2">#</th>
            <th class="tb2">Trans No</th>
            <th class="tb2">Payment No</th>
            <th class="tb2">Cheque No</th>
            <th class="tb2">Cheque Date</th>
            <th class="tb2">Amount</th>
            <th class="tb2">Bank Name</th>
            <th class="tb2">Customer Name</th>
            <th class="tb2">Payment Date</th>
            <th class="tb2">Transfer Date</th>
            <th class="tb2">Salesman</th>
            <th class="tb2">From</th>
            <th class="tb2">Status</th>
            <th class="tb2">Change Status</th>
        </tr>
        <?php
        for ($i = 0; $i < sizeof($payment_id); $i++) {
            $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id=' . $payment_id[$i] . '">' . str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT) . '</a>';
            $status_text = '';
            switch($trans_status[$i]){
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
                    $status_text = 'Accept In-Trans';
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
                        <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($trans_id[$i])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $payment_link . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $chq_full_no[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $chq_date[$i] . '</td>
                        <td align="right" style="padding-right:10px; padding-left:10px">' . number_format($payment_amount[$i], $decimal) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $bank_name[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $cust_name[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_date[$i])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($trans_time[$i])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $salesman[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $trans_from[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $status_text . '</td>
                        <td style="padding-right:10px; padding-left:10px; text-align:center;">
                            <select name="status" onchange="changeStatus(this,' . $trans_id[$i] . ')">
                                <option value="">-SELECT STATUS-</option>
                                <option value="10">Modify Cheque</option>
                            </select>
                        </td>
                </tr>';
        }
        print '<tr style="height: 35px">
                    <td colspan="16" align="right" style="padding-right:10px; padding-left:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
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

    // Call it initially to set the count on page load
    updateCheckedCount();
</script>

<?php
    include_once 'template/footer.php';
?>