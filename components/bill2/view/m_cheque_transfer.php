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

<script>
    function addPayment(paymentId) {
        // Get the user select element for the current payment row
        var userSelect = document.querySelector("#row_" + paymentId + " select[name='user']");

        // Ensure the select element exists
        if (!userSelect) {
            alert("Select element not found.");
            return;
        }

        // Get the selected user's value and text
        var selectedUser = userSelect.value;
        var selectedText = userSelect.options[userSelect.selectedIndex].text;

        // Check if a user is selected
        if (!selectedUser) {
            alert("Please select a user.");
            return; // Exit the function if no user is selected
        }

        // Confirm the action and show the username
        if (confirm("Are you sure you want to mark this payment as sent to " + selectedText + "?")) {
            // Get the cell containing the button for the specific row
            var buttonCell = document.querySelector("#row_" + paymentId + " td:last-child");

            // Show loading GIF in place of the button
            if (buttonCell) {
                buttonCell.innerHTML = '<img src="images/loading.gif" style="width:40px" />';
            }

            // Create a new AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php?components=<?php echo $components; ?>&action=add_cheque_transfer_ajax", true);
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
                                    <td><strong>Add Cheque Trans</strong></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            <!--/ Header -->

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
                                <th class="tb2">Status</th>
                                <th class="tb2">Send To</th>
                                <th class="tb2" style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            for ($i = 0; $i < sizeof($payment_id); $i++) {
                                $status_text = 'To be send';
                                if($modify_cheque[$i] == 1){
                                    $status_text = 'Modify Cheque';
                                }
                                $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id='.$payment_id[$i].'">'.str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT).'</a>';
                                $notify_btn = '<button onclick="addPayment(' . $payment_id[$i] . ')">Mark as Send</button>';
                                print '<tr id="row_' . $payment_id[$i] . '" bgcolor="#F5F5F5" style="height: 30px">
                                        <td style="padding-right:10px; padding-left:10px; text-align:center;"><input type="checkbox" class="checkRow" onclick="updateCheckedCount()"/></td>
                                        <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                                        <td style="padding-right:10px; padding-left:10px">' . $payment_link . '</td>
                                        <td style="padding-right:10px; padding-left:10px">' . $chq_full_no[$i] . '</td>
                                        <td style="padding-right:10px; padding-left:10px">' . $chq_date[$i] . '</td>
                                        <td align="right" style="padding-right:10px; padding-left:10px">' . number_format($payment_amount[$i], $decimal) . '</td>
                                        <td style="padding-right:10px; padding-left:10px">' . $bank_name[$i] . '</td>
                                        <td style="padding-right:10px; padding-left:10px">' . $cust_name[$i] . '</td>
                                        <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_date[$i])) . '</td>
                                        <td style="padding-right:10px; padding-left:10px">' . $status_text . '</td>
                                        <td style="padding-right:10px; padding-left:10px">
                                            <select id="user" name="user">
                                                <option value="">-SELECT USER-</option>';
                                                for ($j = 0; $j < sizeof($sm_id); $j++) {
                                                    if($sm_id[$j] == $_COOKIE['user_id']) continue;
                                                    print '<option value="' . $sm_id[$j] . '">' . ucfirst($sm_name[$j]) . '</option>';
                                                }
                                            print '</select>
                                        </td>
                                        <td style="padding-right:10px; padding-left:10px; text-align:center;">' . $notify_btn . '</td>
                                </tr>';
                            }
                            print '<tr style="height: 35px">
                                    <td colspan="13" align="right" style="padding-right:10px; padding-left:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
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