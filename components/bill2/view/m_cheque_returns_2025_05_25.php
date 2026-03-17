<?php
    include_once 'template/m_header.php';
    $decimal = getDecimalPlaces(1);
?>

<style>
    table {
        font-family: Calibri;
    }

    .wrap {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
    }

    .table-responsive {
        overflow-x: auto;
        white-space: nowrap;
    }

    tr {
        transition: background-color 0.3s ease;
    }
</style>

<script type="text/javascript">
    function filter() {
        // var bank = document.getElementById('bank').value;
        var status = document.getElementById('status').value;
        var datefrom = document.getElementById('datefrom').value;
        var dateto = document.getElementById('dateto').value;

        // Construct URL with all filter parameters
        var url = 'index.php?components=<?php echo $components; ?>&action=cheque_transfer_returns'
                // + '&bank=' + bank
                + '&status=' + status
                + '&datefrom=' + datefrom
                + '&dateto=' + dateto;

        // Trigger page reload with updated filters in the URL
        window.location = url;
    }
    function changeStatus(selectElement, depositId) {
        const selectedValue = selectElement.value;
        const selectedText = selectElement.options[selectElement.selectedIndex].text;

        // Check if a valid option is selected
        if (selectedValue) {
            if (confirm('Are you sure you want to change the status of this payment to: ' + selectedText+'?')) {
                // Send AJAX request
                fetch('index.php?components=<?php echo $components; ?>&action=add_return_cheque_transfer_ajax', {
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
                        console.log('Parsed result:', result);
                        console.log('Result Status:', result.status);
                        if (result.status === 'success') {
                            try {
                                selectElement.style.display = 'none'; // Hide the select element
                                const doneMessage = document.createElement('span');
                                doneMessage.textContent = 'DONE';
                                doneMessage.style.color = 'green'; // Set text color to green
                                doneMessage.style.marginLeft = '10px';
                                doneMessage.style.fontWeight = 'BOLD';
                                selectElement.parentNode.insertBefore(doneMessage, selectElement.nextSibling); // Insert the message after the select element
                                alert(result.message); // Show success message
                            } catch (error) {
                                console.error('Error while updating DOM:', error);
                            }
                        } else {
                            alert(result.message); // Handle server error message
                        }
                    })
                    .catch(error => {
                        alert('An error occurred while updating the status.' + error);
                    });
            }
        }
    }

</script>

<div class="w3-container" style="margin-top:75px">
    <table align="center">
        <tr>
            <td>
                <div id="notifications"></div>
            </td>
        </tr>
    </table>
    <hr>
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">
            <!-- Header -->
            <table align="center" cellspacing="0" class="tbl-header" width="100%">
                <tr>
                    <td align="center" style="padding: 10px; font-size: 13pt;">
                        <div style="margin:auto, 0, auto, 10px; background-color:#EEEEEF; border-radius: 15px; padding: 10px; height:50px; margin-bottom: 10px;">
                            <table height="100%"
                                style="color:#0158C2; font-family:Calibri; font-size:16pt; vertical-align:middle">
                                <tr>
                                    <td><strong>Cheque Returns</strong></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            <!--/ Header -->

            <table align="center" cellspacing="0" class="tbl-header" width="100%">
                <tr>
                    <td style="vertical-align:top;">
                        <table align="center">
                            <tr>
                                <td>
                                    <table align="center" bgcolor="#E5E5E5" style="border-radius: 15px; padding:15px;">
                                        <tr>
                                            <td width="50px"></td>
                                            <td>Status</td>
                                            <td colspan="2">
                                                <select id="status" name="status" onchange="filter();">
                                                    <option value="" <?php if (!isset($_GET['status']) || $_GET['status'] === '')
                                                        echo 'selected="selected"'; ?>>-ALL-</option>
                                                    <option value="11" <?php if (isset($_GET['status']) && $_GET['status'] === '11')
                                                        echo 'selected="selected"'; ?>>RETURN PENDING</option>
                                                    <option value="12" <?php if (isset($_GET['status']) && $_GET['status'] === '12')
                                                        echo 'selected="selected"'; ?>>RETURN ACCEPT</option>
                                                    <option value="13" <?php if (isset($_GET['status']) && $_GET['status'] === '13')
                                                        echo 'selected="selected"'; ?>>RETURN REJECT</option>
                                                </select>
                                            </td>
                                            <td width="50px"></td>
                                        </tr>
                                        <tr>
                                            <td width="50px"></td>
                                            <td>From</td>
                                            <td colspan="2">
                                                <input type="date" id="datefrom" name="datefrom" style="width:130px"
                                                    value="<?php echo isset($fromdate) ? htmlspecialchars($fromdate) : ''; ?>"
                                                    onchange="filter();"/>
                                            </td>
                                            <td width="50px"></td>
                                        </tr>
                                        <tr>
                                            <td width="50px"></td>
                                            <td>To</td>
                                            <td colspan="2">
                                                <input type="date" id="dateto" name="dateto" style="width:130px"
                                                    value="<?php echo isset($todate) ? htmlspecialchars($todate) : ''; ?>"
                                                    onchange="filter();"/>
                                            </td>
                                            <td width="50px"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="wrap">
                <div class="table-responsive">
                    <table align="center" border="0" class="styled-table" width="100%">
                        <thead>
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
                                <th class="tb2">From</th>
                                <th class="tb2">Status</th>
                                <th class="tb2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            for ($i = 0; $i < sizeof($payment_id); $i++) {
                                $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id='.$payment_id[$i].'">'.str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT).'</a>';
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
                                        $status_text = 'Accept';
                                    break;
                                    case 4:
                                        $status_text = 'Accept';
                                    break;
                                    case 5:
                                        $status_text = 'Accept';
                                    break;
                                    case 6:
                                        $status_text = 'Accept';
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
                                        <td style="padding-right:10px; padding-left:10px">' . $trans_from[$i] . '</td>
                                        <td style="padding-right:10px; padding-left:10px">' . $status_text . '</td>';
                                        if ($status[$i] == 11) {
                                            print '
                                                <td style="padding-right:10px; padding-left:10px">
                                                    <select id="status-select-' . $trans_id[$i] . '" onchange="changeStatus(this,' . $trans_id[$i] . ')">
                                                        <option value="">-SELECT-</option>
                                                        <option value="12">Accepted</option>
                                                        <option value="13">Not Receive</option>
                                                    </select>
                                                </td>';
                                        }else{
                                            print '<td style="padding-right:10px; padding-left:10px"></td>';
                                        }
                                        print '</tr>';
                            }
                            print '<tr style="height: 35px">
                                    <td colspan="14" align="right" style="padding-right:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
                                </tr>';
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>

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
    include_once 'template/m_footer.php';
?>