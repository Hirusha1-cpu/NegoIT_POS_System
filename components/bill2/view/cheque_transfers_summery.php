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
        var url = 'index.php?components=<?php echo $components; ?>&action=cheque_transfer_summery'
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
</script>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<form action="index.php?components=<?php print $components; ?>&action=cheque_transfer_summery" method="get">
    <table border="0" width="900px" align="center" height="100%" cellspacing="0"
        style="font-size:10pt; font-family:Calibri; border-radius: 5px;" bgcolor="#F0F0F0">
        <tr style="height: 40px">
            <td align="center">
                <strong>Status</strong>&nbsp;&nbsp;&nbsp;
                <select id="status" name="status" onchange="filter();">
                    <option value="" <?php if (!isset($_GET['status']) || $_GET['status'] === '')
                        echo 'selected="selected"'; ?>>-ALL-</option>
                    <option value="0" <?php if (isset($_GET['status']) && $_GET['status'] === '0')
                        echo 'selected="selected"'; ?>>PENDING</option>
                    <option value="2" <?php if (isset($_GET['status']) && $_GET['status'] === '2')
                        echo 'selected="selected"'; ?>>NOT RECEIVE</option>
                    <option value="3" <?php if (isset($_GET['status']) && ($_GET['status'] === '3' || $_GET['status'] === '4' || $_GET['status'] === '5' || $_GET['status'] === '6' || $_GET['status'] === '7' || $_GET['status'] === '8' || $_GET['status'] === '9' || $_GET['status'] === '10'))
                        echo 'selected="selected"'; ?>>ACCEPT</option>
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
                <br> &nbsp;&nbsp;&nbsp;Salesman Cheque Transfer Summery
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
            <th class="tb2">In Hand</th>
            <th class="tb2">Status</th>
            <th class="tb2" style="text-align:center;">Action</th>
        </tr>
        <?php
            for ($i = 0; $i < sizeof($payment_id); $i++) {
                $delete_btn = '';
                $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id='.$payment_id[$i].'">'.str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT).'</a>';
                if($status[$i] == 0) $delete_btn = '<button onclick="deletePayment(' . $trans_id[$i] . ')">Delete</button>';
                $status_text = '';
                switch($status[$i]){
                    case 0:
                        $status_text = 'Pending';
                    break;
                    case 2:
                        $status_text = 'Not Receive';
                    break;
                    default:
                        $status_text = 'Accept';
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
                        <td style="padding-right:10px; padding-left:10px">' . $trans_to[$i] . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $status_text . '</td>
                        <td style="padding-right:10px; padding-left:10px; text-align:center;">' . $delete_btn . '</td>
                </tr>';
            }
            print '<tr style="height: 35px">
                    <td colspan="14" align="right" style="padding-right:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
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