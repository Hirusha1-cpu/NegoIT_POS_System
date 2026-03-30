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
        // var bank = document.getElementById('bank').value;
        var status = document.getElementById('status').value;
        var datefrom = document.getElementById('datefrom').value;
        var dateto = document.getElementById('dateto').value;

        // Construct URL with all filter parameters
        var url = 'index.php?components=<?php echo $components; ?>&action=cheque_payments_sent_report'
                // + '&bank=' + bank
                + '&status=' + status
                + '&datefrom=' + datefrom
                + '&dateto=' + dateto;

        // Trigger page reload with updated filters in the URL
        window.location = url;
    }

    function deletePayment(paymentId) {
        if (confirm("Are you sure you want to delete this payment?")) {
            // Get the cell containing the button for the specific row
            var buttonCell = document.querySelector("#row_" + paymentId + " td:last-child");

            // Show loading GIF in place of the Delete button
            if (buttonCell) {
                buttonCell.innerHTML = '<img src="images/loading.gif" style="width:40px" />';
            }

            // Create a new AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php?components=<?php echo $components; ?>&action=delete_cheque_transfer_ajax", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Handle the response from the server
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);

                        if (response.status === "success") {
                            // Replace loading GIF with "DELETED" in red text
                            if (buttonCell) {
                                buttonCell.innerHTML = '<span style="color: red; font-weight: bold;">DELETED</span>';
                            }
                            alert(response.message); // Show success message
                        } else {
                            // Restore Delete button if there is an error
                            if (buttonCell) {
                                buttonCell.innerHTML = '<button onclick="deletePayment(' + paymentId + ')">Delete</button>';
                            }
                            alert(response.message || "Error deleting item.");
                        }
                    } else {
                        // Restore Delete button if the request fails
                        if (buttonCell) {
                            buttonCell.innerHTML = '<button onclick="deletePayment(' + paymentId + ')">Delete</button>';
                        }
                        alert("Request failed. Please try again.");
                    }
                }
            };

            // Send the payment ID and type to the server
            xhr.send("id=" + encodeURIComponent(paymentId));
        }
    }

    function addPayment(paymentId) {
        // Get the user select element for the current payment row
        var userSelect = document.querySelector("#row_" + paymentId + " select[name='user']");
        var selectedUser = userSelect.value;
        var selectedUserName = userSelect.options[userSelect.selectedIndex].text;
        if (!selectedUser) {
            alert("Please select a user.");
            return;
        }
        if (confirm("Are you sure you want to send this payment to: " + selectedUserName + "?")) {
            // Get the cell containing the button for the specific row
            var buttonCell = document.querySelector("#row_" + paymentId + " td:last-child");

            // Show loading GIF in place of the button
            if (buttonCell) {
                buttonCell.innerHTML = '<img src="images/loading.gif" style="width:40px" />';
            }

            // Create a new AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php?components=<?php echo $components; ?>&action=add_cheque_transfer_return_to_user_ajax", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            // Prepare the data to send, including both payment ID and selected user
            var data = "id=" + encodeURIComponent(paymentId) + "&user=" + encodeURIComponent(selectedUser);

            // Handle the response from the server
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    // Remove the loading GIF
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);

                        if (response.status === "success") {
                            // Replace loading GIF with "DONE" in green text
                            if (buttonCell) {
                                buttonCell.innerHTML = '<span style="color: green; font-weight: bold;">DONE</span>';
                            }
                            userSelect.disabled = true;  // Disable the select element
                            alert(response.message);
                        } else {
                            // Replace loading GIF with the original "Notify" button on failure
                            if (buttonCell) {
                                buttonCell.innerHTML = '<button onclick="addPayment(' + paymentId + ')">Mark as Send</button>';
                            }
                            alert(response.message || "Error adding payment.");
                        }
                    } else {
                        // Replace loading GIF with the original "Notify" button on failure
                        if (buttonCell) {
                            buttonCell.innerHTML = '<button onclick="addPayment(' + paymentId + ')">Mark as Send</button>';
                        }
                        alert("Request failed. Please try again.");
                    }
                }
            };

            // Send the payment ID to the server
            xhr.send(data);
        }
    }
</script>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<form action="index.php?components=<?php print $components; ?>&action=pending_cheque_transfers" method="get">
    <table border="0" width="900px" align="center" height="100%" cellspacing="0"
        style="font-size:10pt; font-family:Calibri; border-radius: 5px;" bgcolor="#F0F0F0">
        <tr>
            <td colspan="7">
                <br> &nbsp;&nbsp;&nbsp;Transfer Return Cheque to Salesman
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
            <th class="tb2">Return Send To</th>
            <th class="tb2" style="text-align:center;">Action</th>
        </tr>
        <?php
            for ($i = 0; $i < sizeof($payment_id); $i++) {
                $notify_btn = '<button onclick="addPayment(' . $trans_id[$i] . ')">Mark as Send</button>';
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
                        $status_text = 'Bank Return';
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
                        <td style="padding-right:10px; padding-left:10px">' . $trans_from[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $status_text . '</td>
                        <td style="padding-right:10px; padding-left:10px">
                            <select id="user" name="user">
                                <option value="">-SELECT USER-</option>';
                                for ($j = 0; $j < sizeof($sm_id); $j++) {
                                    if($sm_id[$j] == $_COOKIE['user_id']) continue;
                                    if($salesman_id[$i] != $sm_id[$j]) continue;
                                    print '<option value="' . $sm_id[$j] . '">' . ucfirst($sm_name[$j]) . '</option>';
                                }
                            print '</select>
                        </td>
                        <td style="padding-right:10px; padding-left:10px; text-align:center;">' . $notify_btn . '</td>
                </tr>';
            }
            print '<tr style="height: 35px">
                    <td colspan="17" align="right" style="padding-right:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
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

    function transHistory(paymentId) {
        window.open('index.php?components=<?php echo $components; ?>&action=cheque_transfer_status_summery&id=' + paymentId, '_blank');
    }
</script>

<?php
    include_once 'template/footer.php';
?>