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
        var bank = document.getElementById('bank').value;
        var salesaman = document.getElementById('salesaman').value;
        var datefrom = document.getElementById('datefrom').value;
        var dateto = document.getElementById('dateto').value;

        // Construct URL with all filter parameters
        var url = 'index.php?components=<?php echo $components; ?>&action=pending_payment_bank_deposits'
            + '&bank=' + bank
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
                fetch('index.php?components=<?php echo $components; ?>&action=change_payment_deposit_status_ajax', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded' // Change to application/x-www-form-urlencoded
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
                            doneMessage.style.marginLeft = '10px'; // Optional: add some spacing
                            doneMessage.style.fontWeight = 'BOLD';
                            selectElement.parentNode.insertBefore(doneMessage, selectElement.nextSibling); // Insert the message after the select element
                            alert(result.message);
                        } else {
                            // Handle error (you can show a message to the user)
                            alert('Failed to update status: ' + result.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating the status.');
                    });
            }
        }
    }


</script>

<form action="index.php?components=<?php print $components; ?>&action=pending_payment_bank_deposits" method="post">
    <table border="0" width="900px" align="center" height="100%" cellspacing="0"
        style="font-size:10pt; font-family:Calibri; border-radius: 5px;" bgcolor="#F0F0F0">
        <tr style="height: 40px">
            <td align="center">
                <strong>Salesaman</strong>&nbsp;&nbsp;&nbsp;
                <select id="salesaman" name="salesaman" onchange="filter();">
                    <option value="">-ALL-</option>
                    <?php for ($i = 0; $i < sizeof($sm_id); $i++) {
                        if (isset($_GET['salesaman'])) {
                            if ($_GET['salesaman'] == $sm_id[$i]) {
                                $select = 'selected="selected"';
                                $salesman = $sm_name[$i];
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
                <strong>Bank</strong>&nbsp;&nbsp;&nbsp;
                <select id="bank" name="bank" onchange="filter();">
                    <option value="">-ALL-</option>
                    <?php for ($i = 0; $i < sizeof($bnk_id); $i++) {
                        if (isset($_GET['bank'])) {
                            if ($_GET['bank'] == $bnk_id[$i]) {
                                $select = 'selected="selected"';
                                $bank_account = $bnk_name[$i];
                            } else
                                $select = '';
                        } else
                            $select = '';
                        print '<option value="' . $bnk_id[$i] . '" ' . $select . '>' . ucfirst($bnk_name[$i]) . '</option>';
                    }
                    ?>
                </select>
            </td>
            <td align="center">
                <strong>From</strong>&nbsp;&nbsp;&nbsp;
                <input type="date" id="datefrom" name="datefrom" style="width:130px"
                    value="<?php echo isset($_GET['datefrom']) ? htmlspecialchars($_GET['datefrom']) : ''; ?>"
                    onchange="filter();"/>
            </td>
            <td align="center">
                <strong>To</strong>&nbsp;&nbsp;&nbsp;
                <input type="date" id="dateto" name="dateto" style="width:130px"
                    value="<?php echo isset($_GET['dateto']) ? htmlspecialchars($_GET['dateto']) : ''; ?>"
                    onchange="filter();"/>
            </td>
        </tr>
        <tr>
            <td colspan="7">
                <br> &nbsp;&nbsp;&nbsp;Note: This table shows bank transfers collected by salesman (pending).
                <hr />
            </td>
        </tr>
    </table>
</form>
<br>

<div id="print">
    <table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
        <tr>
            <td colspan="13" style="border:0; background-color:black; color:white; font-weight:bold"></td>
        </tr>
        <tr bgcolor="#E5E5E5" style="height: 25px">
            <th class="tb2"><span id="checkedCount"></span></th>
            <th class="tb2">#</th>
            <th class="tb2">Trans No</th>
            <th class="tb2">Payment No</th>
            <th class="tb2">Customer Name</th>
            <th class="tb2">Amount</th>
            <th class="tb2">Bank Name</th>
            <th class="tb2">Date</th>
            <th class="tb2" style="width: 100px;">Ref</th>
            <th class="tb2">Transfer Date</th>
            <th class="tb2">Salesman</th>
            <th class="tb2">Status</th>
            <th class="tb2" width="80px" style="text-align:center;">Action</th>
        </tr>
        <?php
            for ($i = 0; $i < sizeof($payment_id); $i++) {
                $payment_link = '<a href="index.php?components=billing&action=finish_payment&id='.$payment_id[$i].'">'.str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT).'</a>';
                switch ($payment_status[$i]) {
                    case '0':
                        $status = 'CANCELED';
                        break;
                    case '1':
                        $status = 'PENDING';
                        break;
                    case '2':
                        $status = 'ACCEPTED';
                        break;
                    case '3':
                        $status = 'REJECTED';
                        break;
                    case '4':
                        $status = 'BANKED';
                        break;
                    default:
                        $status = 'UNKNOWN';
                        break;
                }

                print '<tr id="row_' . $payment_deposit_id[$i] . '" bgcolor="#F5F5F5" style="height: 30px">
                        <td style="padding-right:10px; padding-left:10px; text-align:center;"><input type="checkbox" class="checkRow" onclick="updateCheckedCount()"//></td>
                        <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($payment_deposit_id[$i])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">'.$payment_link.'</td>
                        <td style="padding-right:10px; padding-left:10px">' . $cust_name[$i] . '</td>
                        <td align="right" style="padding-right:10px; padding-left:10px">' . number_format($payment_amount[$i], $decimal) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $bank_name[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_date[$i])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $comment[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_transfer_date[$i])) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $payment_placed_by[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $status . '</td>
                        <td style="padding-right:10px; padding-left:10px; text-align:center;">
                            <select id="status-select-' . $payment_deposit_id[$i] . '" onchange="changeStatus(this,' . $payment_deposit_id[$i] . ')">
                                <option value="">-SELECT-</option>
                                <option value="2">Accepted</option>
                                <option value="3">Rejected</option>
                            </select>
                        </td>
                </tr>';
            }
            print '<tr style="height: 35px">
                    <td colspan="13" align="right" style="padding-right:10px; padding-left:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
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