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
                        <td><strong>Bank Payment Deposit</strong></td>
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
            <td colspan="10" style="border:0; background-color:black; color:white; font-weight:bold"></td>
        </tr>
        <tr bgcolor="#E5E5E5" style="height: 25px">
            <th class="tb2"></th>
            <th class="tb2">#</th>
            <th class="tb2">Payment No</th>
            <th class="tb2">Customer Name</th>
            <th class="tb2">Amount</th>
            <th class="tb2">Bank Name</th>
            <th class="tb2">Date</th>
            <th class="tb2" style="width: 100px;">Ref</th>
            <th class="tb2" width="80px" style="text-align:center;">Action</th>
        </tr>
        <?php
            for ($i = 0; $i < sizeof($payment_id); $i++) {
                $payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id='.$payment_id[$i].'">'.str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT).'</a>';
                $notify_btn = '<button onclick="addPayment(' . $payment_id[$i] . ')">Notify</button>';
                print '<tr id="row_' . $payment_id[$i] . '" bgcolor="#F5F5F5" style="height: 30px">
                        <td style="padding-right:10px; padding-left:10px; text-align:center;"><input type="checkbox"/></td>
                        <td style="padding-right:10px; padding-left:10px">' . sprintf('%02d', ($i + 1)) . '</td>
                        <td style="padding-right:10px; padding-left:10px">' . $payment_link . '</td>
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
    </table>
</div>
<?php
include_once 'template/footer.php';
?>