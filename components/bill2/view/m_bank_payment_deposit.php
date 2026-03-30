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
</style>

<script>
    function addPayment(paymentId) {
        if (confirm("Are you sure you want to notify this payment?")) {
            // Get the cell containing the button for the specific row
            var buttonCell = document.querySelector("#row_" + paymentId + " td:last-child");

            // Show loading GIF in place of the button
            if (buttonCell) {
                buttonCell.innerHTML = '<img src="images/loading.gif" style="width:40px" />';
            }

            // Create a new AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php?components=<?php echo $components; ?>&action=add_bank_transfer_ajax", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

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
                            alert("Bank payment added successfully.");
                        } else {
                            // Replace loading GIF with the original "Notify" button on failure
                            if (buttonCell) {
                                buttonCell.innerHTML = '<button onclick="addPayment(' + paymentId + ')">Notify</button>';
                            }
                            alert(response.message || "Error adding payment.");
                        }
                    } else {
                        // Replace loading GIF with the original "Notify" button on failure
                        if (buttonCell) {
                            buttonCell.innerHTML = '<button onclick="addPayment(' + paymentId + ')">Notify</button>';
                        }
                        alert("Request failed. Please try again.");
                    }
                }
            };

            // Send the payment ID to the server
            xhr.send("id=" + encodeURIComponent(paymentId));
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
                                    <td><strong>Bank Payment Deposit</strong></td>
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
                            <tr>
                                <td colspan="10" style="color: black; background: #dddddd;" class="td-style">
                                    <strong style="padding-left: 10px">Cash amount sent as a transfer to bank</strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="tb2"></th>
                                <th class="tb2">#</th>
                                <th class="tb2">Payment No</th>
                                <th class="tb2">Invoice No</th>
                                <th class="tb2">Customer Name</th>
                                <th class="tb2">Amount</th>
                                <th class="tb2">Bank Name</th>
                                <th class="tb2">Date</th>
                                <th class="tb2" style="width: 100px;">Ref</th>
                                <th class="tb2" width="80px" style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    for ($i = 0; $i < sizeof($payment_id); $i++) {
                                        $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id='.$payment_id[$i].'">'.str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT).'</a>';
                                        if($invoice_no[$i] !== '0' && $invoice_no[$i] !== 0 && $invoice_no[$i] !== ''){
                                            $invoice_link = '<a target="_blank" href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'">'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>';
                                        } else{
                                            $invoice_link = '';
                                        }
                                        $notify_btn = '<button onclick="addPayment(' . $payment_id[$i] . ')">Notify</button>';
                                        print '<tr id="row_' . $payment_id[$i] . '" bgcolor="#F5F5F5" style="height: 30px">
                                                <td style="padding-right:10px; padding-left:10px; text-align:center;"><input type="checkbox"/></td>
                                                <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                                                <td style="padding-right:10px; padding-left:10px">' . $payment_link . '</td>
                                                <td style="padding-right:10px; padding-left:10px">' . $invoice_link . '</td>
                                                <td style="padding-right:10px; padding-left:10px">' . $cust_name[$i] . '</td>
                                                <td align="right" style="padding-right:10px">' . number_format($payment_amount[$i], $decimal) . '</td>
                                                <td style="padding-right:10px; padding-left:10px">' . $bank_name[$i] . '</td>
                                                <td style="padding-right:10px; padding-left:10px">' . date('Y-m-d H:i', strtotime($payment_date[$i])) . '</td>
                                                <td style="padding-right:10px; padding-left:10px">' . $comment[$i] . '</td>
                                                <td style="padding-right:10px; padding-left:10px; text-align:center;">' . $notify_btn . '</td>
                                        </tr>';
                                    }
                                    print '<tr style="height: 35px">
                                            <td colspan="10" align="right" style="padding-right:10px">Total Amount: ' . number_format(array_sum($payment_amount), $decimal) . '</td>
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

<?php
    include_once 'template/m_footer.php';
?>