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
        var salesman = document.getElementById('salesman').value;
        var from = document.getElementById('from').value;
        var status = document.getElementById('status').value;
        var datefrom = document.getElementById('datefrom').value;
        var dateto = document.getElementById('dateto').value;

        var url = 'index.php?components=<?php echo $components; ?>&action=pending_cheque_transfers'
            + '&salesman=' + salesman
            + '&from=' + from
            + '&status=' + status
            + '&datefrom=' + datefrom
            + '&dateto=' + dateto;
        window.location = url;
    }

    function changeStatus(selectElement, depositId) {
        // Get the selected value and text
        const selectedValue = selectElement.value;
        const selectedText = selectElement.options[selectElement.selectedIndex]?.text || '';

        // Check if a valid option is selected
        if (!selectedValue) {
            alert("Please select a valid option.");
            return;
        }

        if (confirm('Are you sure you want to change the status of this transfer to ' + selectedText + '?')) {
            // Send AJAX request
            fetch('index.php?components=<?php echo $components; ?>&action=add_cheque_transfer_ajax', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    deposit_id: depositId,
                    status: selectedValue
                })
            })
            .then(response => response.json())  // Parse JSON response
            .then(result => {
                if (result.success) {
                    try {
                        // Disable the select element
                        selectElement.disabled = true;
                        const parentCell = selectElement.parentNode;

                        if (parentCell) {
                            // Replace <select> with "DONE" span
                            const doneMessage = document.createElement('span');
                            doneMessage.textContent = 'DONE';
                            doneMessage.style.color = 'green';
                            doneMessage.style.fontWeight = 'bold';
                            parentCell.replaceChild(doneMessage, selectElement);

                            alert(result.message);
                        } else {
                            console.error("Parent node is null. DOM structure might be incorrect.");
                        }
                    } catch (error) {
                        console.error('Error while updating DOM:', error);
                    }
                } else {
                    // Show server error message
                    alert(result.message);
                }
            })
            .catch(error => {
                console.error("AJAX error:", error);
                alert('An error occurred while updating the status: ' + error);
            });
        }
    }
</script>

<form action="index.php?components=<?php print $components; ?>&action=pending_cheque_transfers" method="get">
    <table border="0" width="900px" align="center" height="100%" cellspacing="0"
        style="font-size:10pt; font-family:Calibri; border-radius: 5px;" bgcolor="#F0F0F0">
        <tr>
            <td align="center">
                <strong>Salesman</strong>&nbsp;&nbsp;&nbsp;
                <select id="salesman" name="salesman" onchange="filter();">
                    <option value="">-ALL-</option>
                    <?php for ($i = 0; $i < sizeof($sm_id); $i++) {
                        if (isset($_GET['salesman'])) {
                            if ($_GET['salesman'] == $sm_id[$i]) {
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
                <select id="from" name="from" onchange="filter();">
                    <option value="">-ALL-</option>
                    <?php for ($i = 0; $i < sizeof($manage_user_id); $i++) {
                        if (isset($_GET['from'])) {
                            if ($_GET['from'] == $manage_user_id[$i]) {
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
                <strong>Status</strong>&nbsp;&nbsp;&nbsp;
                <select id="status" name="status" onchange="filter();">
                    <option value="" <?php if (!isset($_GET['status']) || $_GET['status'] === '')
                        echo 'selected="selected"'; ?>>-ALL-</option>
                    <option value="0" <?php if (isset($_GET['status']) && $_GET['status'] === '0')
                        echo 'selected="selected"'; ?>>PENDING</option>
                    <option value="4" <?php if (isset($_GET['status']) && $_GET['status'] === '4')
                        echo 'selected="selected"'; ?>>IN TRANS PENDING</option>
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
                <br> &nbsp;&nbsp;&nbsp;Pending cheque transfers
                <hr />
            </td>
        </tr>
    </table>
</form>
<br>

<div>
    <table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
        <tr>
            <td colspan="16" style="border:0; background-color:black; color:white; font-weight:bold"></td>
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
            <th class="tb2">Modify Cheque</th>
            <th class="tb2">Status</th>
            <th class="tb2" width="80px" style="text-align:center;">Action</th>
        </tr>
        <?php
        for ($i = 0; $i < sizeof($payment_id); $i++) {
            $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id=' . $payment_id[$i] . '">' . str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT) . '</a>';
            $status_text = "";
            $modify_cheque_text = "";
            if($modify_cheque[$i] == 1){
                $modify_cheque_text = '✅';
            }
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
                        <td style="padding-right:10px; padding-left:10px; text-align:center;"><input type="checkbox" class="checkRow" onclick="updateCheckedCount()"//></td>
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
                        <td align="center" style="padding-right:10px; padding-left:10px">' . $modify_cheque_text . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $status_text . '</td>
                        <td style="padding-right:10px; padding-left:10px; text-align:center;">';
                        if ($trans_status[$i] == 0) {
                            print '<select id="status-select-' . $trans_id[$i] . '" onchange="changeStatus(this,' . $trans_id[$i] . ')">
                                                        <option value="">-SELECT-</option>
                                                        <option value="1">Accepted</option>
                                                        <option value="2">Not Receive</option>
                                                    </select>';
                        } else if ($trans_status[$i] == 4) {
                            print '<select id="status-select-' . $trans_id[$i] . '" onchange="changeStatus(this,' . $trans_id[$i] . ')">
                                                    <option value="">-SELECT-</option>
                                                    <option value="5">Accept In-Trans</option>
                                                    <option value="6">Not Receive In-Trans</option>
                                                    </select>';
                        } else {
                            print '<select id="status-select-' . $trans_id[$i] . '" onchange="changeStatus(this,' . $trans_id[$i] . ')">
                                                    <option value="">-UNKNOWN-</option>
                                                    </select>';
                        }
                        print '</td>
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
            } else {
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