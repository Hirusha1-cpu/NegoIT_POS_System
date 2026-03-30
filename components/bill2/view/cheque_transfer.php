<?php
include_once 'template/header.php';
$decimal = getDecimalPlaces(1);
?>
<style>
    .hidden {
        display: none;
    }

    table {
        font-family: Calibri;
    }

    /* Center align radio buttons and dropdowns in the same row */
    .center-align {
        vertical-align: middle;
    }

    /* Style to make dropdowns and input fields uniform */
    select,
    input[type="number"] {
        width: 200px;
        padding: 5px;
        font-size: 12pt;
    }

    /* Align radio buttons and labels together */
    .radio-group label {
        font-size: 12pt;
        vertical-align: middle;
        margin-right: 10px;
    }

    .radio-group input[type="radio"] {
        margin-right: 0px;
        vertical-align: top;
    }

    /* Style the submit button */
    #addpayment input[type="submit"] {
        width: 120px;
        height: 40px;
        background-color: #0158C2;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 12pt;
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

<?php
if (isset($_REQUEST['message'])) {
    if ($_REQUEST['re'] == 'success')
        $color = 'green';
    else
        $color = '#DD3333';
    print '<script type="text/javascript">document.getElementById("notifications").innerHTML=' . "'" . '<span style="color:' . $color . '; font-weight:bold;font-size:12pt;">' . $_REQUEST['message'] . '</span>' . "'" . ';</script>';
}
?>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<table align="center">
    <tr>
        <td valign="top">
            <div
                style="margin:auto, 0, auto; background-color:#EEEEEF; border-radius: 15px; padding: 10px; height:50px; margin-bottom: 10px;">
                <table height="100%" style="color:#0158C2; font-family:Calibri; font-size:16pt; vertical-align:middle">
                    <tr>
                        <td><strong>Add Cheque Transfer</strong></td>
                    </tr>
                </table>
            </div>
            <br />
        </td>
    </tr>
</table>

<div id="print">
    <table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
        <tr>
            <td colspan="13" style="border:0; background-color:black; color:white; font-weight:bold"></td>
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
            <th class="tb2">Status</th>
            <th class="tb2">Send To</th>
            <th class="tb2" style="text-align:center;">Action</th>
        </tr>
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